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
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
error_reporting(E_ALL);


// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'proweb' );

/** MySQL database username */
define( 'DB_USER', 'proweb' );

/** MySQL database password */
define( 'DB_PASSWORD', 'macayleR2011@' );

/** MySQL hostname */
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
define( 'AUTH_KEY',         '9~A9$LU~SVIrTz5EDAm#|tSCs{!}n9;Xltpcq$ChF[D)FvTV6RYso;6 3En(*$h]' );
define( 'SECURE_AUTH_KEY',  'G{Upe5Q2o;E.8hMhGg/uB/Xom@X-fLX,$^_t2E5T[i7+L4Vfte@,{1gZsAw@0#]5' );
define( 'LOGGED_IN_KEY',    '0(PHmbs?P4XlgxbtB3V]P#N)DV9wFF>YU*!>U]g>Dp&t*q{Egt1P<w=ibLr2Ty/@' );
define( 'NONCE_KEY',        '#pCeN9N.TLMr6yVA6ZB:W<on_Lp%A+o&;,{V5|phl?Wt`NYH@cT@ZuPbxG2.((gJ' );
define( 'AUTH_SALT',        '`d_7<pnR+U]LDyaNSb#Z=Jp=@y]|}b=ZQqWJeQ~:{TS|?,zhjX<+3FO]K~iD$I4Q' );
define( 'SECURE_AUTH_SALT', 'eB!~I.vJ_vh``N*d:Q7)Z>=!v9(,?EP.fy^[Y/^s%$fm?uDw*DB/>ER&.4kI]i$j' );
define( 'LOGGED_IN_SALT',   '>F-C_.jx@213 2Fb?.@cT8j6T=3~qJY^,[[KS{Y!2E&xxQ1jiJ=!jnIF@8eh hB4' );
define( 'NONCE_SALT',       '+TEcL/qOU.+0y4>8lFW!Zk#<]#r~T?Id,KZ9<mdgt`9CFm|[&aMe$UJmPdj-Nz$F' );

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

if (function_exists('mysqli_report')) {
    mysqli_report(MYSQLI_REPORT_OFF);
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
