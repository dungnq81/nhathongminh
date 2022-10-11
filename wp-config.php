<?php
define( 'WP_CACHE', true );

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

/** PHP Memory */
const WP_MEMORY_LIMIT     = '512';
const WP_MAX_MEMORY_LIMIT = '512';

const DISALLOW_FILE_EDIT = true;
const DISALLOW_FILE_MODS = true;

/* SSL */
const FORCE_SSL_LOGIN = true;
const FORCE_SSL_ADMIN = true;

const WP_POST_REVISIONS = 1;
const EMPTY_TRASH_DAYS  = 5;
const AUTOSAVE_INTERVAL = 120;

/** Disable WordPress core auto-update, */
const WP_AUTO_UPDATE_CORE = false;

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */

const DB_NAME = 'bkavsmarthomecom_23VEswxXAW';
const DB_USER = 'bkavsmarthomecom_23VEswxXAW';
const DB_PASSWORD = 'ejh2WYjD!QIGd';

const DB_HOST = 'localhost';
const DB_CHARSET = 'utf8mb4';
const DB_COLLATE = '';

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
define( 'AUTH_KEY',         '3S*63[~>Oph?:T^?>&%10B0,L,K^:t,)`+TYZ?iu,[Oga]7_3,uI3fL~.)=KP=>Y' );
define( 'SECURE_AUTH_KEY',  '^[(vdV7yYOQR]k7wz9BDJBa#}3!?^W.Xhb<1p}[rI EuMb9Zj;1db]FYFQZ!h8KL' );
define( 'LOGGED_IN_KEY',    'LD}g6*:+A@y%CYwM4@W9s*^c9yy^fk)c&eY?PIs]b=&8^}$,|%Ui}~&)h]SQq|q@' );
define( 'NONCE_KEY',        '`a[b }N3ut}_{@8.^s+1-8JnyvI.lf9dzRi[U3bByU@h-~DB59=f[R[`~dI).3VS' );
define( 'AUTH_SALT',        '50KG4)rww0[>7yRu`Emx+;tkAX-FhpF/G@Exv`F]=-a|Q:Nu-47(/K<gk^b<t{~N' );
define( 'SECURE_AUTH_SALT', 'B9OF LYUy@6d*P_z3!FA!jO,ZbKf*k7Om~76hIMFj6&:<^Dzkh=QPK0H`T.9f4o3' );
define( 'LOGGED_IN_SALT',   '_E+(xp>e3R/0>/hZ-pxZ;~gy:uA5)||$Kta?$MH_6pd1XK>+ki7FB.*P]j$^adM-' );
define( 'NONCE_SALT',       '?p;(:|)aZCtTD>3FJlVFqUXQ^cL!`YlzDhAOZ]i5z^=`ZoIOT(M:qwU:NVx+*)35' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'w_';

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
const WP_DEBUG = false;

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
