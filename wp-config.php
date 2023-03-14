<?php

define( 'WP_CACHE', false ); // Added by WP Rocket

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


// ** MySQL settings - You can get this info from your web host ** //

/** The name of the database for WordPress */

define( 'DB_NAME', "vetschool" );


/** MySQL database username */

define( 'DB_USER', "root" );


/** MySQL database password */

define( 'DB_PASSWORD', "zentry2020!" );


/** MySQL hostname */

define( 'DB_HOST', "localhost:8889" );


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

define( 'AUTH_KEY',         'hK{qo%hMTsli|K<7uOk)5PIn- Dp,OytEA>kqH7,|^3,u6Y%n)3Fq[T1.HZ C4fS' );

define( 'SECURE_AUTH_KEY',  ' ?}=#e|Uw1[ADOjf^<X}3gj.[Z`ky7g/?xBPYa$MGNk>-%muU}^NRu dhmqOqiE#' );

define( 'LOGGED_IN_KEY',    'bj!8QXJVMnE#d!T$:L5l:!O2;XgPn91-(07l!]EmXHH,vZ[FfwiIg^qdad&HENGu' );

define( 'NONCE_KEY',        '4__3dOMi>rKch@u]J7 S%fv<`?-j~C%X&`UI%`%K McgLa %2580Zn^p9gB+:Pb-' );

define( 'AUTH_SALT',        '4|p2>{+#`6uf&N5vE}l>OF$4)ban8*vY@_UFH81Uw/7ar}Gh_[X>[MW/wAxrBt+>' );

define( 'SECURE_AUTH_SALT', 'Lpexrcy&]|Ux[ZWL@!:2}[eu!{d&@:0&CV[ =|]&6*@l&oo0Cb~Yw_y=USqlL0;F' );

define( 'LOGGED_IN_SALT',   'jy1<4uN2IY1V3-s#=BBvT@d~Spu7bX8qOI28*X3-=pi,t+$|IM7x(6FgiB%ioR~q' );

define( 'NONCE_SALT',       'fGh1f7wJ4FY8HoumslwFtce[ 6HrNo9QAZrWVKg3 YgR;6n;0s)O0igH;7[-+3);' );

define('DISABLE_WP_CRON', true);
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

