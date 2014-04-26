<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * You can view all defined constants with:
 * print_r(@get_defined_constants());
 *
 * @package WordPress
 */

define( 'WP_CONFIG_PATH', dirname(__FILE__) );

if ( isset($_ENV['PANTHEON_ENVIRONMENT']) ) {
	
	define('WP_ENV', $_ENV['PANTHEON_ENVIRONMENT']);

	// ** MySQL settings - included in the Pantheon Environment ** //
	/** The name of the database for WordPress */
	define('DB_NAME', $_ENV['DB_NAME']);

	/** MySQL database username */
	define('DB_USER', $_ENV['DB_USER']);

	/** MySQL database password */
	define('DB_PASSWORD', $_ENV['DB_PASSWORD']);

	/** MySQL hostname; on Pantheon this includes a specific port number. */
	define('DB_HOST', $_ENV['DB_HOST'] . ':' . $_ENV['DB_PORT']);

	/** Database Charset to use in creating database tables. */
	define('DB_CHARSET', 'utf8');

	/** The Database Collate type. Don't change this if in doubt. */
	define('DB_COLLATE', '');

	/** Table Prefix **/
	$table_prefix = 'wp_';

	/**#@+
* Authentication Unique Keys and Salts.
*
* Change these to different unique phrases!
* You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
* You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
*
* Pantheon sets these values for you also. If you want to shuffle them you
* can do so via your dashboard.
*
* @since 2.6.0
*/
	define('AUTH_KEY', $_ENV['AUTH_KEY']);
	define('SECURE_AUTH_KEY', $_ENV['SECURE_AUTH_KEY']);
	define('LOGGED_IN_KEY', $_ENV['LOGGED_IN_KEY']);
	define('NONCE_KEY', $_ENV['NONCE_KEY']);
	define('AUTH_SALT', $_ENV['AUTH_SALT']);
	define('SECURE_AUTH_SALT', $_ENV['SECURE_AUTH_SALT']);
	define('LOGGED_IN_SALT', $_ENV['LOGGED_IN_SALT']);
	define('NONCE_SALT', $_ENV['NONCE_SALT']);
	/**#@-*/

	/** A couple extra tweaks to help things run well on Pantheon. **/
	if (isset($_SERVER['HTTP_HOST'])) {
	 	define('WP_HOME', 'http://' . $_SERVER['HTTP_HOST']);
	 	define('WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST'] );
	}
	// Don't show deprecations; useful under PHP 5.5
	error_reporting(E_ALL ^ E_DEPRECATED);

} else {

	/**
	 * Include packages installed by composer
	 */
	require_once( dirname(__DIR__) . '/vendor/autoload.php' );

	/**
	 * Load environment variables from the .env file with Dotenv
	 */
	Dotenv::load( WP_CONFIG_PATH );
	Dotenv::required( array('DB_NAME', 'DB_USER', 'DB_PASSWORD') );

	/**
	 * Set up our global environment constant and load its config first
	 * Default: local
	 */
	define( 'WP_ENV', getenv('WP_ENV') ? getenv('WP_ENV') : 'local' );

	/**
	 * Load DB credentials according to development environment
	 */
	define( 'DB_NAME', getenv('DB_NAME') );
	define( 'DB_USER', getenv('DB_USER') );
	define( 'DB_PASSWORD', getenv('DB_PASSWORD') );
	define( 'DB_HOST', getenv('DB_HOST') ? getenv('DB_HOST') : 'localhost' );

	/**
	 * WordPress Database Table settings
	 *
	 * You can have multiple installations in one database if you give each a unique
	 * prefix. Only numbers, letters, and underscores please!
	 */
	$table_prefix  = getenv('table_prefix') ? getenv('table_prefix') : 'wp_';
	define( 'DB_CHARSET', 'utf8' );
	define( 'DB_COLLATE', '' );

	/**
	 * Define WordPress URLs if needed (all of these will override the settings in the wp_options table)
	 */
	 
	/* URL where people can reach your website */
	define( 'WP_HOME', getenv('WP_HOME') ? getenv('WP_HOME') : 'http://' . $_SERVER['SERVER_NAME'] );

	/* URL where wordpress core files reside */
	define( 'WP_SITEURL', getenv('WP_SITEURL') ? getenv('WP_SITEURL') : 'http://' . $_SERVER['SERVER_NAME'] );

	/*
	 * Define wp-content directory
	 */
	/* Full local path for content directory */
	define( 'WP_CONTENT_DIR', WP_CONFIG_PATH . '/wp-content' );
	/* URL of content directory */
	define( 'WP_CONTENT_URL', WP_HOME . '/wp-content' );

	/**
	 * Set plugin directory, if needed
	 */
	/* Local path to plugin directory */
	//define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
	/* URL of plugin directory */
	//define( 'WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins' );

	/**
	 * Set mu-plugins directory, if needed
	 */
	/* Local path to mu-plugin directory */
	//define( 'WPMU_PLUGIN_DIR', WP_CONTENT_DIR . '/mu-plugins' );
	/* URL of plugin -mudirectory */
	//define( 'WPMU_PLUGIN_URL', WP_CONTENT_URL . '/mu-plugins' );

	/**
	 * Set uploads folder
	 */
	//define( 'UPLOADS', WP_CONTENT_DIR . '/uploads' );


	/**
	 * Authentication Unique Keys and Salts
	 */
	define( 'AUTH_KEY',         getenv('AUTH_KEY') );
	define( 'SECURE_AUTH_KEY',  getenv('SECURE_AUTH_KEY') );
	define( 'LOGGED_IN_KEY',    getenv('LOGGED_IN_KEY') );
	define( 'NONCE_KEY',        getenv('NONCE_KEY') );
	define( 'AUTH_SALT',        getenv('AUTH_SALT') );
	define( 'SECURE_AUTH_SALT', getenv('SECURE_AUTH_SALT') );
	define( 'LOGGED_IN_SALT',   getenv('LOGGED_IN_SALT') );
	define( 'NONCE_SALT',       getenv('NONCE_SALT') );
}

/**
* For developers: WordPress debugging mode.
*
* Change this to true to enable the display of notices during development.
* It is strongly recommended that plugin and theme developers use WP_DEBUG
* in their development environments.
*/

if ( 'local' === WP_ENV || 'dev' === WP_ENV )
	define( 'WP_DEBUG', true );
else
	define( 'WP_DEBUG', false );

if ( WP_DEBUG ) {

	// Custom logging function
	if(!function_exists('log_me')){
		function log_me( $message ) {
			if( is_array( $message ) || is_object( $message ) ){
				error_log( print_r( $message, true ) );
			} else {
				error_log( $message );
			}
		}
	}

	/* Set PHP error and error log settings to override server settings, if needed*/
	@ini_set( 'log_errors','On' );
	@ini_set( 'display_errors','On' );
	@ini_set( 'error_reporting', E_ALL );
	
	/*
	 * This will log all errors notices and warnings to a file called debug.log in
	 * wp-content only when WP_DEBUG is true. if Apache does not have write permission, 
	 * you may need to create the file first and set the appropriate permissions (i.e. use 644).
	 */
	@ini_set( 'error_log', WP_CONFIG_PATH . '/php_error.log' );
	define( 'WP_DEBUG_LOG', true );
	
	
	/* Display notices or not (set logging to true if this is false) */
	define( 'WP_DEBUG_DISPLAY', true );

	/*
	* Save database queries to an array that can be displayed
	* Note that this will have a performance impact on the site
	*
	* Access these through $wpdb->queries
	*/
	// define( 'SAVEQUERIES', true );
	
	/* Script Debugging */
	/*
	 * If true, changes made to the scriptname.dev.js and filename.dev.css files in the
	 * wp-includes/js, wp-includes/css, wp-admin/js, and wp-admin/css directories will be
	 * reflected on your site.
	 */
	// define( 'SCRIPT_DEBUG', true );
	
	/*
	 * Disable javascript concatenation in admin area
	 */
	// define( 'CONCATENATE_SCRIPTS', false );
}

/**
 * Theme and stylesheet paths
 * (probably shouldn't use these)
 */
// define( 'TEMPLATEPATH', get_template_directory() );
// define( 'STYLESHEETPATH', get_stylesheet_directory() );

/**
 * Set the default theme
 *
 * Put this in wp-config-sample.php and WordPress will use this setting when installing
 */
// define( 'WP_DEFAULT_THEME', 'twentyfourteen' );

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define( 'WPLANG', '' );

/* Enable this constant temporarily to change the site url in the database
 * 1. Uncomment line below
 * 2. Navigate to http://mynewsitedomain.com/wp-login.php and login
 * 3. Be sure to recomment this line
 */
// define( 'RELOCATE',true ); 


/* Additional WordPress settings */

/* Change interval for AJAX saves when editing posts */
// define( 'AUTOSAVE_INTERVAL', 160 );  // seconds

/* Specify number of post revisions to save (or disable with 'false') */
// define( 'WP_POST_REVISIONS', 3 );

/* Specify number of days content is held in trash before being permanently deleted */
// define( 'EMPTY_TRASH_DAYS', 30 );  // default is 30 days; set to 0 to disable trash

/* Enable the Trash feature for media */
// define( 'MEDIA_TRASH', true );

/* Change the URLs temporarily before doing a search and replace in the database */
// ob_start( 'nacin_dev_urls' );
// 	function nacin_dev_urls( $buffer ) {
// 	$live = 'http://olddomain.com';
// 	$dev = 'http://newdomain.com'; return str_replace( $live, $dev, $buffer );
// 	}

/**
 * Set Cookie Domain
 * 
 * The domain set in the cookies for WordPress can be specified for those with unusual domain
 * setups. One reason is if subdomains are used to serve static content. To prevent WordPress
 * cookies from being sent with each request to static content on your subdomain you can set
 * the cookie domain to your non-static domain only.
 */
// define( 'COOKIE_DOMAIN', 'www.askapache.com' );

/* Additional Cookie Settings */
// define( 'COOKIEPATH', preg_replace( '|https?://[^/]+|i', '', get_option( 'home' ) . '/' ) );
// define( 'SITECOOKIEPATH', preg_replace( '|https?://[^/]+|i', '', get_option( 'siteurl' ) . '/' ) );
// define( 'ADMIN_COOKIE_PATH', SITECOOKIEPATH . 'wp-admin' );
// define( 'PLUGINS_COOKIE_PATH', preg_replace( '|https?://[^/]+|i', '', WP_PLUGIN_URL ) );


/**
 * Disable Plugin and Theme Editors
 *
 * Warning: may break plugins that rely on current_user_can('edit_plugins')
 */
// define( 'DISALLOW_FILE_EDIT',true );

/**
 * Disable Plugin and Theme Update Installation
 * 
 * Blocks users being able to use the plugin and theme installation/update functionality
 * from the WordPress admin area. Also disallows the theme and plugin editors
 */
// define( 'DISALLOW_FILE_MODS',true );

/**
 * Allow unfiltered uploads--administrators can upload any file type
 */
// define( 'ALLOW_UNFILTERED_UPLOADS', true );

/* Enable WordPress Multisite */

// define( 'WP_ALLOW_MULTISITE', true );
// define( 'SUBDOMAIN_INSTALL', false );
// define( 'DOMAIN_CURRENT_SITE', $_SERVER['SERVER_NAME'] );
// define( 'PATH_CURRENT_SITE', '/' );
// define( 'SITE_ID_CURRENT_SITE', 1 );
// define( 'BLOG_ID_CURRENT_SITE', 1 );

/* Configure Multisite */
// define( 'SUNRISE', 'on' );

/**
 * WP_CRON Settings
 */
// If disabling WP_CRON, set a cron job like `*/5 * * * * curl http://example.com/wp/wp-cron.php` in your server's crontab file

/* Disable cron entirely */
// define( 'DISABLE_WP_CRON',true );

/* Make sure a cron process cannot run more than once every so many seconds */
// define( 'WP_CRON_LOCK_TIMEOUT',60 );

/* Alternative cron that uses redirection, but isn't as reliable */
// define( 'ALTERNATE_WP_CRON', true );

/**
 * Server Setting Overrides
 */

/* View all defined php constants */
// print_r( @get_defined_constants() );

/* Increase PHP memory limit settings, if possible/needed */
// define( 'WP_MEMORY_LIMIT', '64M' );

/* Change PHP memory limit in WordPress administration area */
// define( 'WP_MAX_MEMORY_LIMIT', '256M' );

/* Attempt to override default file permissions */
// define( 'FS_CHMOD_DIR', (0755 & ~ umask()) );
// define( 'FS_CHMOD_FILE', (0644 & ~ umask()) );

/**
 * Enable Automatic Database Repair
 * Note that this can be accessed at /wp-admin/maint/repair.php even when not logged in
 */
// define( 'WP_ALLOW_REPAIR', true );

/**
 * Do Not Upgrade Global Tables
 * 
 * Prevents upgrade functions from doing expensive database queries on global tables
 *
 * Particularly useful for sites with large user and usermeta tables, so the database upgrade
 * can be done manually
 *
 * Also useful for installations that share user tables between bbPress and WordPress installs
 * Where only one site should be the upgrade master
 */
// define( 'DO_NOT_UPGRADE_GLOBAL_TABLES', true );

/**
 * Custom User and Usermeta Tables
 *
 * Defined a custom user and usermeta table that can be used for multiple instances of WordPress
 */
// define( 'CUSTOM_USER_TABLE', $table_prefix.'my_users' );
// define( 'CUSTOM_USER_META_TABLE', $table_prefix.'my_usermeta' );

/**
 * SSL
 */
/* Force SSL Login */
// define( 'FORCE_SSL_LOGIN',true );

/* Force SSL for Logins and Admin */
// define( 'FORCE_SSL_ADMIN',true );

/**
 * Auto updating
 */
/* Disable all core updates: */
// define( 'WP_AUTO_UPDATE_CORE', false );

/* Enable all core updates, including minor and major: */
// define( 'WP_AUTO_UPDATE_CORE', true );

/* Enable core updates for minor releases (default) */
// define( 'WP_AUTO_UPDATE_CORE', 'minor' );

/* Disable automatic updater completely */
// define( 'AUTOMATIC_UPDATER_DISABLED', true );

/* Skip adding new default theme with new major versions */
// define( 'CORE_UPGRADE_SKIP_NEW_BUNDLED', true );

/**
 * Block external URL requests
 *
 * WP_ACCESSIBLE_HOSTS allow additional hosts to bypass the block, and is a comma separated list of hostnames to allow, and can include wildcard subdomains
 */
// define( 'WP_HTTP_BLOCK_EXTERNAL', true );
// define( 'WP_ACCESSIBLE_HOSTS', 'api.wordpress.org,*.github.com' );

/**
 * WordPress.com API key
 */
// define( 'WPCOM_API_KEY', 'YourKeyHere' );

/**
 * Caching
 *
 * Whether to include the wp-content/advanced-cache.php script
 */
// define( 'WP_CACHE', false );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define( 'ABSPATH', WP_CONFIG_PATH . '/wp/' );

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
