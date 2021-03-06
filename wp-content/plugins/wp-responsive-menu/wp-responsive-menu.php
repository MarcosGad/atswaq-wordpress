<?php
/*
Plugin Name: WP Responsive Menu
Plugin URI: http://magnigenie.com/wp-responsive-menu-mobile-menu-plugin-wordpress/
Description: WP Responsive Menu is mobile menu plugin that lets you add a highly customizable responsive menu to any WordPress site in no time.
Version: 3.1.3
Author: MagniGenie
Author URI: http://magnigenie.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/**
 * Enable Localization
 */
define( 'MG_WPRM_FILE', __FILE__ );
define(	'MG_WPRM_PATH', plugin_dir_path( __FILE__ ) );
define( 'MG_WPRM_BASE', plugin_basename( __FILE__ ) );
define( 'MG_WPRM_BASE_NAME', basename( dirname( __FILE__ ) ) );
define( 'MG_WPRM_DEMO_SITE_URL', 'http://demo.magnigenie.com/wp-responsive-menu-pro' );

add_action('plugins_loaded', 'wprmenu_load_textdomain');

function wprmenu_load_textdomain() {
	load_plugin_textdomain( 'wprmenu', false, MG_WPRM_BASE_NAME . '/languages/' );
}


/**
 * Add admin settings
 */
define( 'WPR_OPTIONS_FRAMEWORK_DIRECTORY', plugins_url( '/inc/', __FILE__ ) );
define( 'WPR_OPTIONS_FRAMEWORK_PATH', dirname( __FILE__ ) . '/inc/' );

require_once dirname( __FILE__ ) . '/inc/options-framework.php';
require_once dirname( __FILE__ ) . '/inc/wprmclass.php';

new WP_Responsive_Menu();