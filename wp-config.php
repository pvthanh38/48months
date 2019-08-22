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
define('DB_NAME', 'wp_48months');

/** MySQL database username */
define('DB_USER', '48months');

/** MySQL database password */
define('DB_PASSWORD', '12345678');

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
define('AUTH_KEY',         '-|et`}f4o5n=3fJT{xh3&TmV|D!GFRG:4V>:yVW7-JVx_s []c+h@pf<Op I*cTj');
define('SECURE_AUTH_KEY',  'y5h}]Ho@zEZ6TyS@Psv8APs+Y+_Cm-1{7y/T!U(i|IY/Di+s.ki:c2lRHTX4KDwc');
define('LOGGED_IN_KEY',    'zS4%Kn_V[+(CM$Nz-f:5!:v7R4]jp)ZGeofUY?xT3Zxl?Ke{Vj/OPvz 4N}Qf!)$');
define('NONCE_KEY',        'FKl1!qRp<t&I>DMYpFLAO,FO^kh=/;QkzY9nrO91#/*Dt*S}};ekq$x{MbHzaeq0');
define('AUTH_SALT',        'J-Zn,Y|c>?4%i]a^%rOQvMv+8 IM@hopD)!PY4z!7rD:Oz`+i*-Rpo^6J]a5=zxv');
define('SECURE_AUTH_SALT', '!`^[_b.s7!&H3|Xg.i}<QDmu%j)JkD?WV:q %zb+:K3O/{Wx|z.XU,U 9[zrBi9h');
define('LOGGED_IN_SALT',   '?MqrW/PrgbWpw?}#{j9s&h.bot65A$*8<hOHRpfo-:O*%dNNqwj}|m1AF?eR}gP&');
define('NONCE_SALT',       'L,Y<;Su_Wq[>6EtkG`n[lKfOMDNy(v,VDZH]P~: f/jrWsjr/kgB*{ve853<CY@/');

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