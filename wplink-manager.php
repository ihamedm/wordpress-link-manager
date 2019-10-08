<?php
/**
 *
 * @wordpress-plugin
 * Plugin Name: Wordpress Link Manager
 * Plugin URI: https://
 * Description: Wordpress plugin to create pretty and short link based on your site URL for external (or internal) ugly links
 * Version: 0.1
 * Author: Hamed Movasaqpoor
 * Author URI: https://movasaqpoor.ir
 * Text Domain: wplman
 * Domain Path: /i18n/languages/
 *
 *
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WpLinkManager
 *
 * Main plugin class
 */
class WpLinkManager{

	/**
	 * Version
	 *
	 * @var string
	 */
	static $version = '0.1';

	/**
	 * Plugin Directory Path
	 *
	 * @var string
	 */
	static $path ;

	/**
	 * Plugin Name
	 *
	 * @var string
	 */
	static $name;

	/**
	 * Plugin URL
	 *
	 * @var string
	 */
	static $url;

	/**
	 * Database option name(key)
	 *
	 * @var string
	 */
	static $option_prefix = 'wplman_';

	/**
	 * Text Domain for translate strings
	 *
	 * @var string
	 */
	static $text_domain = 'wplman';


	/**
	 * Initialise Minapp
	 */
	public static function init(){

		/**
		 * properties values
		 */
		self::$path = plugin_dir_path(__FILE__);
		self::$url  = plugin_dir_url(__FILE__);



		/**
		 * define statics to use on plugin
		 */
		define('WPLMAN_NAME',       self::$name);
		define('WPLMAN_URL',        self::$url);
		define('WPLMAN_PATH',       self::$path);
		define('WPLMAN_VERSION',    self::$version);
		define('WPLMAN_PREFIX',     self::$option_prefix);
		define('WPLMAN_TEXTDOMAIN', self::$text_domain);



		/**
		 * Handel some stuff on deactivate / uninstall the plugin
		 *
		 */
		register_deactivation_hook( __FILE__    , array( __CLASS__ , 'wplman_deactivating' ) );
		register_uninstall_hook(    __FILE__    , array( __CLASS__ , 'wplman_uninstalling' ) );



		/**
		 * basic initialise
		 *
		 */
		self::internationalization();



		/**
		 * load all stuff in admin area
		 *
		 * - Meta fields for post types
		 * - Filters for post types
		 * - Option pages
		 *
		 */
		if ( is_admin() ) {
			require_once dirname( __FILE__ ) . '/includes/Wplman_MetaFields.php';
			require_once dirname( __FILE__ ) . '/includes/class-wplman-ajax.php';
			require_once dirname( __FILE__ ) . '/includes/wplman-dashboard.php';
		}else{
			// action and include that only need for frontend
		}




		/**
		 * include basic classes
		 * - Base class AND FUNCTIONS
		 * - Checks before installing plugin [dependencies]
		 */
		require_once dirname( __FILE__ ) . '/includes/library/wplman-helpers.php';
		require_once dirname( __FILE__ ) . '/includes/library/Wplman_Dependencies.php';
		require_once dirname( __FILE__ ) . '/includes/Wplman_PostType.php';
		require_once dirname( __FILE__ ) . '/includes/class-wplman-redirect.php';
		require_once dirname( __FILE__ ) . '/includes/class-wplman-frontend.php';
	}



	public static function internationalization(){
		load_plugin_textdomain( WPLMAN_TEXTDOMAIN, false, basename( WpLinkManager::$path ) . '/i18n/languages' );
	}


	/**
	 * on Plugin deactivated
	 */
	public static function wplman_deactivating() {
		do_action( 'wplman_deactivated' );
	}


	/**
	 * on Plugin uninstalled
	 */
	public static function wplman_uninstalling() {
		do_action( 'wplman_uninstalled' );
	}


	/**
	 * on Plugin activated
	 */
	public function wplman_activated($plugin){
		if ( $plugin == plugin_basename( __FILE__ ) ) {
			do_action( 'wplman_activated' );
			wp_safe_redirect( admin_url( 'admin.php?page=wplman-dashboard' ) );
			exit;
		}
	}

}

// Activate the plugin
add_action( 'plugins_loaded', array( 'WpLinkManager' , 'init' ) );

// do somethings after activating
add_action( 'activated_plugin', array( 'WpLinkManager' , 'wplman_activated' ));

