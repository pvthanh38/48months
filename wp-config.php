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
define('AUTH_KEY',         '(UsSvoziIEUnF#-[E7AnjEpwQ0R4=^r[}acw +gnx%Jc! y3^JgocRiojOL0T6Cr');
define('SECURE_AUTH_KEY',  'I)Bp2w{8JX@?UK=W9/WQI9H]G&aSER#B&e^ 2@/l B6Y`!Q=AdPR={S*qt?Qn@Is');
define('LOGGED_IN_KEY',    'g;J{$I_#-Cg7>g2|8p{EM[@[#vRz$xTWH34{FRwDPlfi|GF5ES #e`3k |{TI6u~');
define('NONCE_KEY',        'kM-K)E0WzPWblrr$DEI[v;<(K<D*$_iSoxHJ6_M!m#Rn?^#/]v*:~>dtWVN7aR%w');
define('AUTH_SALT',        'k2sm9EY<2<Tr)_L7o4/8@_~8swT,e*bukE:)Fr1JlXhUwB=Nofx]aZN=7FsEg>}]');
define('SECURE_AUTH_SALT', '>W#J0|G/&$0fQ=C8~(&~6SRPdP+8|<1zX0o9SL,fS_#L+t7{=0*4V?U2zr&::V?<');
define('LOGGED_IN_SALT',   '}sNPSHVEG#8d(*9VZ.9L N>|TPcSX[<)NXW*E6R74C52d2dVQIlJB)zJ2an3c|Tr');
define('NONCE_SALT',       'ExE<5E(zCV-2Lyg|5~D+K@@6HMY^)T#*,NGy$-,5t>0R;+yyR_p,wN%Y+3jgViqB');

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
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
