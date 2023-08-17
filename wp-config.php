<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache



/**

 * The base configuration for WordPress

 *

 * The wp-config.php creation script uses this file during the installation.

 * You don't have to use the web site, you can copy this file to "wp-config.php"

 * and fill in the values.

 *

 * This file contains the following configurations:

 *

 * * Database settings

 * * Secret keys

 * * Database table prefix

 * * ABSPATH

 *

 * @link https://wordpress.org/support/article/editing-wp-config-php/

 *

 * @package WordPress

 */


// ** Database settings - You can get this info from your web host ** //

/** The name of the database for WordPress */

define( 'DB_NAME', 'bitnami_wordpress' );


/** Database username */

define( 'DB_USER', 'root' );


/** Database password */

define( 'DB_PASSWORD', '' );


/** Database hostname */

define( 'DB_HOST', '127.0.0.1:3306' );


/** Database charset to use in creating database tables. */

define( 'DB_CHARSET', 'utf8' );


/** The database collate type. Don't change this if in doubt. */

define( 'DB_COLLATE', '' );


/**#@+

 * Authentication unique keys and salts.

 *

 * Change these to different unique phrases! You can generate these using

 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.

 *

 * You can change these at any point in time to invalidate all existing cookies.

 * This will force all users to have to log in again.

 *

 * @since 2.6.0

 */

define( 'AUTH_KEY',         ';FGS3Y:H [|Ed_#Y Peyu8iqcGM83,:qFPexirwMmt6k?q`H+vM3rj|]>x|$kdwj' );

define( 'SECURE_AUTH_KEY',  'CFUfk0Sm=mx]3E#zho[Z-_sZYBS|[ETPu=WlL*?:X2/C{X--{h*vkLO]VB/BT{>C' );

define( 'LOGGED_IN_KEY',    '~wEh,t8rDT@n7(e|S_B,4ah)NC5GNCvQy#ttLSI}`27FVFb)VU%E1]xL3-p{[1pA' );

define( 'NONCE_KEY',        'Ys7OEY+`m42ShF0>(_q|99%XT,2xlJ4zu-MlquYm[|WUo!S%R.8pF:NyN}C1xl_#' );

define( 'AUTH_SALT',        '%d_K/_9:ySb[YwNoS6Ii6@<M$0496:oF<x,t-6o7_6.e7NpH#@2$*e@#=plsqsV' );

define( 'SECURE_AUTH_SALT', '/%h0PiR:sJrq#oZ&91<5PvRn9`4vb>OcV.v5kb@`qtPsBd3h#2,OT6K`>#Zo|;tJ' );

define( 'LOGGED_IN_SALT',   'RPmW<#27B6HYGxH*)m g}cy!;3TFt3s2FmN vz3=WlSR;:?6jrmv:LmW]r[to4rP' );

define( 'NONCE_SALT',       'Qz_*;t#,0i<`8QU5h>x|XwlwTT/O_c(]JVYE&`(yyYYjN0NK.~,f(h?QN%oI~P7f' );


/**#@-*/


/**

 * WordPress database table prefix.

 *

 * You can have multiple installations in one database if you give each

 * a unique prefix. Only numbers, letters, and underscores please!

 */

$table_prefix = 'wp_';


/**

 * For developers: WordPress debugging mode.

 *

 * Change this to true to enable the display of notices during development.

 * It is strongly recommended that plugin and theme developers use WP_DEBUG

 * in their development environments.

 *

 * For information on other constants that can be used for debugging,

 * visit the documentation.

 *

 * @link https://wordpress.org/support/article/debugging-in-wordpress/

 */

define( 'WP_DEBUG', false );

define( 'WP_DEBUG_LOG', true );


/* Add any custom values between this line and the "stop editing" line. */




define( 'FS_METHOD', 'direct' );
/**
 * The WP_SITEURL and WP_HOME options are configured to access from any hostname or IP address.
 * If you want to access only from an specific domain, you can modify them. For example:
 *  define('WP_HOME','http://example.com');
 *  define('WP_SITEURL','http://example.com');
 *
 */
if ( defined( 'WP_CLI' ) ) {
	$_SERVER['HTTP_HOST'] = '127.0.0.1';
}

define( 'WP_HOME', 'http://localhost:81/polykart' );
define( 'WP_SITEURL', 'http://localhost:81/polykart' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */


/** Absolute path to the WordPress directory. */

if ( ! defined( 'ABSPATH' ) ) {

	define( 'ABSPATH', __DIR__ . '/' );

}


/** Sets up WordPress vars and included files. */

require_once ABSPATH . 'wp-settings.php';

/**
 * Disable pingback.ping xmlrpc method to prevent WordPress from participating in DDoS attacks
 * More info at: https://docs.bitnami.com/general/apps/wordpress/troubleshooting/xmlrpc-and-pingback/
 */
if ( !defined( 'WP_CLI' ) ) {
	// remove x-pingback HTTP header
	add_filter("wp_headers", function($headers) {
		unset($headers["X-Pingback"]);
		return $headers;
	});
	// disable pingbacks
	add_filter( "xmlrpc_methods", function( $methods ) {
		unset( $methods["pingback.ping"] );
		return $methods;
	});
}
