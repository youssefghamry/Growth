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
define( 'DB_NAME', 'Growth' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         'LT1n$?:u`WD#B}$(XBSEfr[3wCdfr(ti=vt:,+0O.b3-SD`<Ms#Jw8?nDd<-Vk<w' );
define( 'SECURE_AUTH_KEY',  '=ik-u3|+*k9<eirg7D;+*t<9rE;Lj[4;w[|ioo[uS`+;3odGxB^ibwBD>rwbqpY,' );
define( 'LOGGED_IN_KEY',    '2szX|2EsJecXix4]x,!F|=M906ucy[T?@i[2`XX/0^2~&-.CYw=B}>[+ 2UZ?Q1!' );
define( 'NONCE_KEY',        'F6*,!7{ioO| f<aP1)WKNe&5/C4~W35o1Xc]Zue)^[_B|,KNDW?r(H|W?xdJM56b' );
define( 'AUTH_SALT',        '4U~)tK~T<EQ!-tf7.mu@aw@(otR1Qxiw)O{X(oR!sN57)j1[UCk_)w-EeVEk7/sH' );
define( 'SECURE_AUTH_SALT', 'Ta;<1PbmY=HZz4Ne-p|rr{7)>R`kQFv3b}}fffRhAw<^;<ZwCb9)L~Y*Rijaevkw' );
define( 'LOGGED_IN_SALT',   '-XxDCo~Mc5FKZX;>R&5A?_boHy,F3uy^x_2g3c!d@3kQ&}p~Dp[MJ$GP-ha^{/=}' );
define( 'NONCE_SALT',       'KtW9eejIMYdHkTGabMnTc}K!vS+QV:k}O0S@1dZ3Z>S]irc$o%Qltp}~7xT#qAY*' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_Growth';

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
