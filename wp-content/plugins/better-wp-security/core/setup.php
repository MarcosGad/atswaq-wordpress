<?php

/**
 * Plugin activation, upgrade, deactivation and uninstall
 *
 * @package iThemes-Security
 * @since   4.0
 */
final class ITSEC_Setup {
	public static function handle_activation() {
		self::setup_plugin_data();

		if ( ! ITSEC_Modules::get_setting( 'global', 'initial_build' ) ) {
			ITSEC_Modules::set_setting( 'global', 'initial_build', ITSEC_Core::get_plugin_build() );
		}
	}

	public static function handle_deactivation() {
		if ( ! self::is_at_least_one_itsec_version_active() ) {
			return;
		}

		if ( defined( 'ITSEC_DEVELOPMENT' ) && ITSEC_DEVELOPMENT ) {
			// Set this in wp-config.php to run the uninstall routine on deactivate.
			self::deactivate();
			self::uninstall();
		} else {
			self::deactivate();
		}
	}

	public static function handle_uninstall() {

		if ( self::is_at_least_one_itsec_version_active() ) {
			return;
		}

		self::deactivate();

		$uninstalled = self::get_version_being_uninstalled();
		$loaded      = self::get_version_loaded();

		if ( $uninstalled === $loaded ) {
			self::uninstall();
		}
	}

	public static function handle_upgrade( $build = false ) {
		if ( ! ITSEC_Modules::get_setting( 'global', 'initial_build' ) ) {
			ITSEC_Modules::set_setting( 'global', 'initial_build', ITSEC_Core::get_plugin_build() - 1 );
		}

		self::setup_plugin_data( $build );
	}

	private static function setup_plugin_data( $build = false ) {
		// Determine build number of current data if it was not passed in.

		if ( empty( $build ) ) {
			$build = ITSEC_Modules::get_setting( 'global', 'build' );
		}

		if ( empty( $build ) ) {
			$plugin_data = get_site_option( 'itsec_data' );

			if ( is_array( $plugin_data ) && ! empty( $plugin_data['build'] ) ) {
				$build = $plugin_data['build'];
				ITSEC_Modules::set_setting( 'global', 'activation_timestamp', $plugin_data['activation_timestamp'] );
			}

			delete_site_option( 'itsec_data' );
		}

		if ( empty( $build ) ) {
			if ( is_multisite() ) {
				switch_to_blog( 1 );
				$bwps_options = get_option( 'bit51_bwps' );
				restore_current_blog();
			} else {
				$bwps_options = get_option( 'bit51_bwps' );
			}

			if ( false !== $bwps_options ) {
				self::upgrade_from_bwps();

				$build = 3064;
			}
		}

		if ( ! ITSEC_Modules::get_setting( 'global', 'activation_timestamp' ) ) {
			ITSEC_Modules::set_setting( 'global', 'activation_timestamp', ITSEC_Core::get_current_time_gmt() );
		}

		// Ensure that the database tables are present and updated to the current schema.
		ITSEC_Lib::create_database_tables();

		// Run activation routines for modules to ensure that they are properly set up.
		$itsec_modules = ITSEC_Modules::get_instance();
		$itsec_modules->run_activation();

		if ( empty( $build ) ) {
			ITSEC_Lib::schedule_cron_test();
		} else {
			// Existing install. Perform data upgrades.

			if ( $build < 4031 ) {
				self::upgrade_data_to_4031();
			}

			if ( $build < 4067 ) {
				delete_site_option( 'itsec_pro_just_activated' );
			}

			if ( $build < 4069 ) {
				self::upgrade_data_to_4069();
				delete_site_option( 'itsec_free_just_activated' );
			}

			if ( $build < 4076 ) {
				$digest = wp_next_scheduled( 'itsec_digest_email' );

				if ( $digest ) {
					wp_unschedule_event( $digest, 'itsec_digest_email' );
				}
			}

			// Run upgrade routines for modules to ensure that they are up-to-date.
			$itsec_modules = ITSEC_Modules::get_instance();
			$itsec_modules->run_upgrade( $build, ITSEC_Core::get_plugin_build() );
		}


		// Ensure that the active modules are loaded and regenerate the configs.
		ITSEC_Modules::run_active_modules();
		$itsec_files = ITSEC_Core::get_itsec_files();
		$itsec_files->do_activate();

		if ( $build < 4079 ) {
			ITSEC_Core::get_scheduler()->register_events();

			wp_clear_scheduled_hook( 'itsec_purge_lockouts' );
			wp_clear_scheduled_hook( 'itsec_clear_locks' );

			ITSEC_Lib::schedule_cron_test();
		}

		if ( $build < 4080 ) {
			ITSEC_Core::get_scheduler()->uninstall();
			ITSEC_Core::get_scheduler()->register_events();

			$crons = _get_cron_array();

			foreach ( $crons as $timestamp => $args ) {
				unset( $crons[ $timestamp ]['itsec_cron_test'] );

				if ( empty( $crons[ $timestamp ] ) ) {
					unset( $crons[ $timestamp ] );
				}
			}

			_set_cron_array( $crons );

			ITSEC_Lib::schedule_cron_test();
		}

		if ( null === get_site_option( 'itsec-enable-grade-report', null ) ) {
			update_site_option( 'itsec-enable-grade-report', ITSEC_Modules::get_setting( 'global', 'enable_grade_report' ) );
		}

		ITSEC_Core::get_scheduler()->register_events();

		// Update stored build number.
		ITSEC_Modules::set_setting( 'global', 'build', ITSEC_Core::get_plugin_build() );
	}

	private static function deactivate() {

		$itsec_modules = ITSEC_Modules::get_instance();
		$itsec_modules->run_deactivation();

		$itsec_files = ITSEC_Core::get_itsec_files();
		$itsec_files->do_deactivate();

		ITSEC_Core::get_scheduler()->uninstall();

		delete_site_option( 'itsec_temp_whitelist_ip' );
		delete_site_transient( 'itsec_notification_running' );
		delete_site_transient( 'itsec_wp_upload_dir' );
		wp_clear_scheduled_hook( 'itsec_digest_email' );
		wp_clear_scheduled_hook( 'itsec_purge_lockouts' );

		$htaccess = ITSEC_Lib::get_htaccess();

		//Make sure we can write to the file
		$perms = substr( sprintf( '%o', @fileperms( $htaccess ) ), - 4 );

		if ( $perms == '0444' ) {
			@chmod( $htaccess, 0664 );
		}

		flush_rewrite_rules();

		//reset file permissions if we changed them
		if ( $perms == '0444' ) {
			@chmod( $htaccess, 0444 );
		}

		ITSEC_Lib::clear_caches();

	}

	private static function uninstall() {
		require_once( ITSEC_Core::get_core_dir() . '/lib/schema.php' );
		require_once( ITSEC_Core::get_core_dir() . '/lib/class-itsec-lib-directory.php' );

		ITSEC_Modules::run_uninstall();

		$itsec_files = ITSEC_Core::get_itsec_files();
		$itsec_files->do_deactivate();

		delete_site_option( 'itsec-storage' );
		delete_site_option( 'itsec_active_modules' );
		delete_site_option( 'itsec-enable-grade-report' );

		ITSEC_Schema::remove_database_tables();
		ITSEC_Lib_Directory::remove( ITSEC_Core::get_storage_dir() );
		ITSEC_Lib::clear_caches();

	}

	private static function get_version_being_uninstalled() {

		if ( doing_action( 'uninstall_better-wp-security/better-wp-security.php' ) ) {
			return 'free';
		}

		if ( doing_action( 'uninstall_ithemes-security-pro/ithemes-security-pro.php' ) ) {
			return 'pro';
		}

		return '';
	}

	private static function get_version_loaded() {

		$plugin_dir = plugin_basename( dirname( dirname( __FILE__ ) ) );

		if ( $plugin_dir === 'better-wp-security' ) {
			return 'free';
		}

		if ( $plugin_dir === 'ithemes-security-pro' ) {
			return 'pro';
		}

		return '';
	}

	private static function is_at_least_one_itsec_version_active() {

		$active_plugins = self::get_active_plugins();

		foreach ( $active_plugins as $active_plugin ) {
			$file = basename( $active_plugin );

			if ( in_array( $file, array( 'better-wp-security.php', 'ithemes-security-pro.php' ) ) ) {
				return true;
			}
		}

		return false;
	}

	private static function get_active_plugins() {

		$active_plugins = (array) get_option( 'active_plugins', array() );

		if ( is_multisite() ) {
			$network_plugins = (array) get_site_option( 'active_sitewide_plugins', array() );
			$active_plugins = array_merge( $active_plugins, array_keys( $network_plugins ) );
		}

		return $active_plugins;
	}

	private static function upgrade_from_bwps() {
		global $itsec_bwps_options, $wpdb;

		if ( wp_next_scheduled( 'bwps_backup' ) ) {
			wp_clear_scheduled_hook( 'bwps_backup' );
		}

		if ( is_multisite() ) {
			switch_to_blog( 1 );
		}

		$itsec_bwps_options = get_option( 'bit51_bwps' );

		delete_option( 'bit51_bwps' );
		delete_option( 'bwps_intrusion_warning' );
		delete_option( 'bit51_bwps_data' );
		delete_option( 'bwps_file_log' );
		delete_option( 'bwps_awaymode' );
		delete_option( 'bwps_filecheck' );
		delete_option( 'BWPS_Login_Slug' );
		delete_option( 'BWPS_options' );
		delete_option( 'BWPS_versions' );
		delete_site_transient( 'bit51_bwps_backup' );
		delete_site_transient( 'bwps_away' );

		if ( is_multisite() ) {
			restore_current_blog();
		}

		$wpdb->query( "DROP TABLE IF EXISTS `" . $wpdb->base_prefix . "bwps_lockouts`;" );
		$wpdb->query( "DROP TABLE IF EXISTS `" . $wpdb->base_prefix . "bwps_log`;" );
		$wpdb->query( "DROP TABLE IF EXISTS `" . $wpdb->base_prefix . "BWPS_d404`;" );
		$wpdb->query( "DROP TABLE IF EXISTS `" . $wpdb->base_prefix . "BWPS_ll`;" );
		$wpdb->query( "DROP TABLE IF EXISTS `" . $wpdb->base_prefix . "BWPS_lockouts`;" );


		if ( ! is_array( $itsec_bwps_options ) ) {
			return;
		}

		$current_options = get_site_option( 'itsec_global' );

		if ( $current_options === false ) {
			$current_options = array(
				'blacklist'          => true,
				'blacklist_count'    => 3,
				'lockout_white_list' => array(),
				'log_location'       => ITSEC_Core::get_storage_dir( 'logs' ),
				'write_files'        => false,
			);
		}

		$current_options['notification_email']    = array( isset( $itsec_bwps_options['ll_emailaddress'] ) && strlen( $itsec_bwps_options['ll_emailaddress'] ) ? $itsec_bwps_options['ll_emailaddress'] : get_option( 'admin_email' ) );
		$current_options['backup_email']          = array( isset( $itsec_bwps_options['backup_emailaddress'] ) && strlen( $itsec_bwps_options['backup_emailaddress'] ) ? $itsec_bwps_options['backup_emailaddress'] : get_option( 'admin_email' ) );
		$current_options['blacklist']             = isset( $itsec_bwps_options['ll_blacklistip'] ) && $itsec_bwps_options['ll_blacklistip'] == 0 ? false : true;
		$current_options['blacklist_count']       = isset( $itsec_bwps_options['ll_blacklistipthreshold'] ) && intval( $itsec_bwps_options['ll_blacklistipthreshold'] ) > 0 ? intval( $itsec_bwps_options['ll_blacklistipthreshold'] ) : 3;
		$current_options['write_files']           = isset( $itsec_bwps_options['st_writefiles'] ) && $itsec_bwps_options['st_writefiles'] == 1 ? true : false;
		$itsec_globals['settings']['write_files'] = $current_options['write_files'];

		if ( isset( $itsec_bwps_options['id_whitelist'] ) && ! is_array( $itsec_bwps_options['id_whitelist'] ) && strlen( $itsec_bwps_options['id_whitelist'] ) > 1 ) {

			$raw_hosts = explode( PHP_EOL, $itsec_bwps_options['id_whitelist'] );

			foreach ( $raw_hosts as $host ) {

				if ( strlen( $host ) > 1 ) {
					$current_options['lockout_white_list'][] = $host;
				}

			}

		}

		update_site_option( 'itsec_global', $current_options );
	}

	private static function upgrade_data_to_4031() {
		$banned_option = get_site_option( 'itsec_ban_users' );

		if ( isset( $banned_option['white_list'] ) ) {

			$banned_white_list = $banned_option['white_list'];
			$options           = get_site_option( 'itsec_global' );
			$white_list        = isset( $options['lockout_white_list'] ) ? $options['lockout_white_list'] : array();

			if ( ! is_array( $white_list ) ) {
				$white_list = explode( PHP_EOL, $white_list );
			}

			if ( ! is_array( $banned_white_list ) ) {
				$banned_white_list = explode( PHP_EOL, $banned_white_list );
			}

			$new_white_list = array_merge( $white_list, $banned_white_list );

			$options['lockout_white_list'] = $new_white_list;

			update_site_option( 'itsec_global', $options );

		}

	}

	private static function upgrade_data_to_4069() {
		global $wpdb;

		delete_site_option( 'itsec_api_nag' );
		delete_site_option( 'itsec_initials' );
		delete_site_option( 'itsec_flush_old_rewrites' );
		delete_site_option( 'itsec_manual_update' );
		delete_site_option( 'itsec_rewrites_changed' );
		delete_site_option( 'itsec_config_changed' );
		delete_site_option( 'itsec_had_other_version' );
		delete_site_option( 'itsec_no_file_lock_release' );
		delete_site_option( 'itsec_clear_login' );
		delete_site_option( 'itsec_jquery_version' );
		delete_site_transient( 'ITSEC_SHOW_WRITE_FILES_TOOLTIP' );
		delete_site_transient( 'itsec_upload_dir' );

		if ( ! is_multisite() ) {
			$wpdb->update( $wpdb->options, array( 'autoload' => 'yes' ), array( 'option_name' => 'itsec_active_modules' ) );
			$wpdb->update( $wpdb->options, array( 'autoload' => 'yes' ), array( 'option_name' => 'itsec-storage' ) );
		}
	}
}
