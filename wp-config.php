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
define('DB_NAME', 'diemnhan_db2');

/** MySQL database username */
define('DB_USER', 'diemnhan_us');

/** MySQL database password */
define('DB_PASSWORD', 'diemnhan!@#');

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
define('AUTH_KEY',         'oeyk7ekyof252o4ih5r4woidjsw1rydbvy7fmkdiffsrfoc1uw3ctxrgidfp42ax');
define('SECURE_AUTH_KEY',  '0fdzthsiwc6idq1sra0yemzu3fv8wudy5k0gltda0g15hhnrc15fup9tlecozi4v');
define('LOGGED_IN_KEY',    't909rhhgj3ijpgnj1xq7geco7x8lbnuesxwaixkgqwhngnx685wgxtc13osghstc');
define('NONCE_KEY',        'mfrvrlvcjwvplda3s5bjmobi7n9wvtpqojxbpx1qe6kdlq0wcx1hc4wrbkj62ix4');
define('AUTH_SALT',        'b0ecizdkznyanuwtqh01l1hcljzzektyvp6fihno1feqr6xegugq58w75drvui3r');
define('SECURE_AUTH_SALT', 'vbrt4yhqkbu43sgisiufo4f7kgvhy1esrbakshzvmpay9iwdtccdegw6el6ifzet');
define('LOGGED_IN_SALT',   '6zesbdnltb5yezxo86mxxkjsgwh5eb1tthlurvckp6um84z2b8zwl31boquaiako');
define('NONCE_SALT',       'tvtksamnwzkdk5sn5flvo7s7gftbhc6smkfgws2i1anamui5vf7vwpwuchv38gj7');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'nice_';

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
define( 'WP_DEBUG', true );
define( 'SCRIPT_DEBUG', true );
if ( WP_DEBUG ) {
    define( 'WP_DEBUG_LOG', true );
    define( 'WP_DEBUG_DISPLAY', false );
}
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
