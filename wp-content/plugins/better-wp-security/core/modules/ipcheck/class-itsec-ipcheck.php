<?php

/**
 * iThemes IPCheck API Wrapper.
 *
 * Provides static calls to the iThemes IPCheck API
 *
 * @since   4.5
 *
 * @package iThemes_Security
 *
 */
class ITSEC_IPCheck {
	public function run() {
		add_filter( 'authenticate', array( $this, 'filter_authenticate' ), 10000, 3 ); // Set a very late priority so that we run after actual authentication takes place.
	}

	public function filter_authenticate( $user, $username, $password ) {
		/** @var $itsec_lockout ITSEC_Lockout */
		global $itsec_lockout;

		if ( is_wp_error( $user ) && $user->get_error_codes() == array( 'empty_username', 'empty_password' ) ) {
			// This is not an authentication attempt. It is simply the login page loading.
			return $user;
		}

		require_once( dirname( __FILE__ ) . '/utilities.php' );

		$enable_ban = ITSEC_Modules::get_setting( 'network-brute-force', 'enable_ban' );

		if ( is_wp_error( $user ) || null == $user ) {
			if ( ITSEC_Network_Brute_Force_Utilities::report_ip() && $enable_ban ) {
				ITSEC_Log::add_notice( 'ipcheck', 'failed-login-by-blocked-ip', array( 'details' => ITSEC_Lib::get_login_details() ) );
				$itsec_lockout->execute_lock( array( 'network_lock' => true ) );
			}
		} elseif ( $enable_ban && ITSEC_Network_Brute_Force_Utilities::is_ip_banned() ) {
			ITSEC_Log::add_critical_issue( 'ipcheck', 'successful-login-by-blocked-ip', array( 'details' => ITSEC_Lib::get_login_details() ) );
			$itsec_lockout->execute_lock( array( 'network_lock' => true ) );
		}

		return $user;
	}
}
