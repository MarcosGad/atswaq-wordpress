<?php

/*
 * Loader for WP REST API endpoints that are synced with WP.com.
 *
 * On WP.com see:
 *  - wp-content/mu-plugins/rest-api.php
 *  - wp-content/rest-api-plugins/jetpack-endpoints/
 */

function wpcom_rest_api_v2_load_plugin_files( $file_pattern ) {
	$plugins = glob( dirname( __FILE__ ) . '/' . $file_pattern );

	if ( ! is_array( $plugins ) ) {
		return;
	}

	foreach ( array_filter( $plugins, 'is_file' ) as $plugin ) {
		require_once $plugin;
	}
}

// API v2 plugins: define a class, then call this function.
function wpcom_rest_api_v2_load_plugin( $class_name ) {
	global $wpcom_rest_api_v2_plugins;

	if ( ! isset( $wpcom_rest_api_v2_plugins ) ) {
		$_GLOBALS['wpcom_rest_api_v2_plugins'] = $wpcom_rest_api_v2_plugins = array();
	}

	if ( ! isset( $wpcom_rest_api_v2_plugins[ $class_name ] ) ) {
		$wpcom_rest_api_v2_plugins[ $class_name ] = new $class_name;
	}
}

require dirname( __FILE__ ) . '/class-wpcom-rest-field-controller.php';

// Now load the endpoint files.
wpcom_rest_api_v2_load_plugin_files( 'wpcom-endpoints/*.php' );
wpcom_rest_api_v2_load_plugin_files( 'wpcom-fields/*.php' );
