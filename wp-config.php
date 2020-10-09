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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'catalogo-online' );

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
define( 'AUTH_KEY',         'C%8[fZ4=CK(MEC$H=EIs=;X]GwB4uyOpT7;0QcL39f: /OnJ1$[p+tC`:|yz1YMw' );
define( 'SECURE_AUTH_KEY',  's^-n|2:_*_/:P@FC)<SL3~Jr0aL{RV9C$^&YY3k%4U+d@BPTTJ,D=4Dl3dGz$W*M' );
define( 'LOGGED_IN_KEY',    'aZvE*WIsS/c/J}-Cgn<9f<i@JjO#5+M}&Sm$}GeJ+J,kBcq;P^%qXSWRC]2k@Oj=' );
define( 'NONCE_KEY',        's vh0!6f.&d!xm{Xv {Q)/uqT%%f7y 4a<G<qJajULnB`Qb(* e6/n+5w}L<%i}2' );
define( 'AUTH_SALT',        'FV5BtX,EkK#BHBw:){SPGU>JB+IZuXd@<lAAjU4Y52+JYeG4|;nFrT!Vf^q`e>f1' );
define( 'SECURE_AUTH_SALT', ':KYu+#z.ZfH@ 3pe5G!@<U<#i0Ng*d8gZU{mICq[n |*k]fi[-qC}8=eL&|+:86C' );
define( 'LOGGED_IN_SALT',   '}t32S:$S]~u+(c.DW1GH+Ci.m;uqx_lLFe<QrQl;l=OU[k=*c4^Ks8 l*8{-dbM5' );
define( 'NONCE_SALT',       'lwRh_$%1!DaN-lT3Y1/B6y`g9m3gL/!DazlO(VFl~Co>7HIWQ.,;KdohgBpY^dXq' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpcatalogo_';

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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
