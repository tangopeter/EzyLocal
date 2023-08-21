<?php

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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wp_ezyLocal2_db');
/** Database username */
define('DB_USER', 'wp_ezyLocal_user');
/** Database password */
define('DB_PASSWORD', 'wp_ezyLocal_pw');
/** Database hostname */
define('DB_HOST', 'localhost');
/** Database charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The database collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

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
define('AUTH_KEY',         'Nk$[rd:S0#:26:,Fb<Z4^r/>VYy[_Xu=t 6wZ#zS1WAgP$UyP9),UslC&1/_.c><');
define('SECURE_AUTH_KEY',  'ElY):a+e^/-xqoxGV)ymGn/Z|P!nB-[Uh;U7Uwi9})#]#<U8O,$EfrD3(,B$7)Wn');
define('LOGGED_IN_KEY',    '3BC1{7~g(p|+zBttibxosqY[(B8:/R6jb4n+rwP#)|Eq?Q;wx|b,)wg@`?%8#`<D');
define('NONCE_KEY',        ')Z+$IPt,/2*-~b^8}K@`-^E7@}ThRx-Jabcz|lzZw%M94&}|wjnF~yC5]Gkf96ir');
define('AUTH_SALT',        'O|~A-&[+)TV$RR#7PK1ey-Z:|+|?W3kS0eb|q#.+l[ykO}z<={m;f]KU>A#?k[l+');
define('SECURE_AUTH_SALT', '%{MSw-#C7P%`Ystv8c7m4GsT!,O#z/)-L$h2db,*<G?)M+%LN_ ;|:&*O^{y< H-');
define('LOGGED_IN_SALT',   '?9b4Ddc,mR(EdtS :[{X^yrWE,UT~g_x020GJ+tDoO4ygY oR&--)tvE;GcnPH7p');
define('NONCE_SALT',       '$rA9=_@ki3{~o$`tGu9u[AM#0:_|_./|]2+F4X+CT.wdTC?s GEpA{TXASD#+0;d');

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/* Add any custom values between this line and the "stop editing" line. */

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
// if (!defined('WP_DEBUG')) {
// 	define('WP_DEBUG', true);
// }
define('WP_DEBUG', false);
define('SCRIPT_DEBUG', false);

define('FORCE_SSL_ADMIN', true);

// in some setups HTTP_X_FORWARDED_PROTO might contain 
// a comma-separated list e.g. http,https
// so check for https existence
if (strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false)
       $_SERVER['HTTPS']='on'; 
define('WP_HOME','https://ezylocal:8890/');
define('WP_SITEURL','https://ezylocal:8890/');

/* That's all, stop editing! Happy publishing. */
/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
	define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
