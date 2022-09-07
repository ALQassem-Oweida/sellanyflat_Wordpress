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
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'sellanyflat' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'Qassem123123' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'ao7A2k!]4Z`+w u^}HO/k_(|yUywgB:(9VvGP*/7>ScxntH4/4N.jv<j|dRQD&p&' );
define( 'SECURE_AUTH_KEY',  'jF!GlkoVg&qAW}QW|(Z12}{A1U1]VQQ=_u4$ul[n{s<.XCxCLm[ECa{Ty8u)K@qF' );
define( 'LOGGED_IN_KEY',    'Q;qwA.Yb*Q(Wck.b*YVP1amk#YXrD0tY<z^DB~OH`gOGzWHof]kcYBHWQ?;3(y0H' );
define( 'NONCE_KEY',        'I+9PDI]]{i&(!N4ccIlY#xvX)!T4s|90,NG-Da<@Z2U/zy.xSG-EfV6}vT;v9Ypd' );
define( 'AUTH_SALT',        'P`GTHI4I9^,?15vV%:O-+JT]tESFV8FZ-,pdAuc:vA!<#G_]p<5]^){=t^(Qc?UY' );
define( 'SECURE_AUTH_SALT', '5R)/z;:Vg$<hr:o^moJa)1lt:urB|~C+ >X9oZh,(;nR%[ut}mw$$S- [h9c($_p' );
define( 'LOGGED_IN_SALT',   'Oe%8o5U&zK[9Dcpc?`#tn;7mm?Sg/hk4fDd^s110AWATjcq|CTZmI->1cy{]o;-@' );
define( 'NONCE_SALT',       'l9P=z:O$f0[2Z7r7xTz1EOfC8x:~*s9T=MUI)xabI+:?.SLKdesqdnEMf1CkB<u(' );

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

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
