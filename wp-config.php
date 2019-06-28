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
define( 'DB_NAME', 'gazsixuni' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         ';7EZ}:+p`-XFRpBRTwUuot>>z&/G73;)cI,v7mb^FYPQ),x!8tAwE?S?DDMpbQA0' );
define( 'SECURE_AUTH_KEY',  'r.vdcj0A_GKS:p>-OC1|giX>R56LCFBa!%y`Ey@!VvZ.!W` >3,+m~2q7i)(Qbxl' );
define( 'LOGGED_IN_KEY',    'H[mLgaYk?d{MIa/~l.(UXO>TZwPmOXA@N, =?C7G{rkWnAN2H:dXYbp [K.m//Vo' );
define( 'NONCE_KEY',        'W1tAxh?ynECwm&Aa!}M$)bm]aI]]$SUD$KZ4&s[SHJR_*_;NSWq*UaR8PY!@Y;G6' );
define( 'AUTH_SALT',        'd=Aw/?mjs0!P*Xhc/BvYy!#}}5Irt 5=-N$a2REL/V5Mz6GMN9yh9m02_yBS>,S$' );
define( 'SECURE_AUTH_SALT', '4YhqV~tHZq[zrig:`)m(`>Q;M#48.|hzhAo!NF.u/B0!o[PI8TEj)R<2BAPe> k{' );
define( 'LOGGED_IN_SALT',   '428mzS(RO6`]:[ kal=!H_%ElKoR|.sLrpsAbMM{R(e%C;YA9.,I0a|>?g:7X:e8' );
define( 'NONCE_SALT',       'oxy+#rU-2_{`1,0/S}8FM#P!waHf)#^aTv-K&!$U[|PI{V^MX~es%bNU@gJ1d&!X' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );


@ini_set( 'upload_max_filesize' , '128M' );
@ini_set( 'post_max_size', '128M');
@ini_set( 'memory_limit', '256M' );
@ini_set( 'max_execution_time', '300' );
@ini_set( 'max_input_time', '300' );