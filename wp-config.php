<?php

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'dbatswaq');

/** MySQL database username */
define('DB_USER', 'polycarpus');

/** MySQL database password */
define('DB_PASSWORD', '#Pola24846912');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '(# 8UOQTad|HA{F9>3Ver(jx?wq*_jx4Fo7!72$tf;GeBhhdDzJe9=uvw^1cDgvr');
define('SECURE_AUTH_KEY',  'cPx7>p;VaX=P4^{P1}0TsE,bsLEzM-^5Qik!Fnom5{?QA|^v;1%fGDdr[x:#<r|!');
define('LOGGED_IN_KEY',    'vlm[o2*9Gi;Q+t~hw!2 o7k#`Vi=YPXtGjv)YePdBWTZ:yk3<!,RZnF<)|F)3{a-');
define('NONCE_KEY',        '3`5cpKw;+q^@8#]6md.>Wwrs.Y5 @b2rdn3(YWt8a)ubUyo<~U12jKm R9DTVT]d');
define('AUTH_SALT',        '.+d.K4>K?}2i~B5 n;)-#{6K_(R;-{SPLgp_i)`vQ:#9U+APGdIT3+iwo!+eu<5<');
define('SECURE_AUTH_SALT', '=;^z6KRhj[S0DrM$YAz f5`x/Za*dnd2]RFt$!VC1C-19ztDtz+oPQ+vxlNnip(Q');
define('LOGGED_IN_SALT',   'pIy5,Q?)3I{5APd&oTsi5H~/Q8b,o6|Vu/!V@`S1 F$3-Ox-Tm;{e8l$j+H4UVK ');
define('NONCE_SALT',       'pHt$^y5^sHZRokH+v{e5FwdZD~Zvim665-<.`vSgVn67,6D%N=$VzR)UPL`i#lc~');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
/* define( 'WP_MAX_MEMORY_LIMIT', '256M' );*/ 