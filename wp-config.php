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
define('AUTH_KEY',          '3Ci$=yx)AcB=2*Ssr&>a&~K0hsQ%=L@ b,Q0}V/q7eCAvE^=8mWSMlJKZt=A.E8I');
define('SECURE_AUTH_KEY',   ')U8e*.5?1sx]`6V?:(>R4mEpA_h(@n@gr<zSB-b`bl~=Xftyd^WkAXN]2Huu<dVw');
define('LOGGED_IN_KEY',     '!!,NRNVZ6y`YPTZ la$JS9r(V(3_chLnywug?;sl{;hE3|h9ms2{ij!&rZfS#xbc');
define('NONCE_KEY',         'f^x%GKp#{fjI^Jk]?nJ-ceH,n`ajrE>}}IiD${`oAV^EsO%i!?1#aQBGZ8e0hvz{');
define('AUTH_SALT',         'l#L)tO]>z^WH)n{V5k?e*F%(HG$YJTR]Y1TT,-`>6!_T;4}apG$FBrul7cPZnB>@');
define('SECURE_AUTH_SALT',  't;q5-TUT}0TxGn^mZ<:=Soxs]ZKPo9xNZJ)o=usVKq>rU/z3u_*QpX2~DA!2ps-j');
define('LOGGED_IN_SALT',    'o{)18YL5j$H]h,KExKjR_:G@!HfT>qQiI9z 4s;Z_>;<dqIdK919^_Yl`XU|{r;A');
define('NONCE_SALT',        'Aw!YD3,h-vhM<xsSmsuZH^T[NM**?o$op$M/b>1t>SdiFae ]h UzL~kFxU{LiJ&');
define('WP_CACHE_KEY_SALT', 'tEZjBdGsg+Lu>lx(Obs7ow_}mTW#xl9&Xa-`.aW/N-1kEpz5 8sH1fW   6@QiR,');


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


/* That's all, stop editing! Happy publishing. */
/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
	define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
