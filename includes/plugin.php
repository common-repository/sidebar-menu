<?php
namespace SidebarMenu;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin {

    /**
	 * Instance.
	 *
	 * Holds the plugin instance.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @var Plugin
	 */
	public static $instance = null;
    /**
	 * Plugin constructor.
	 *
	 * Initializing MageeShortcodes plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 */
    private function __construct() {
		$this->register_autoloader();
		\SidebarMenu\Classes\Helper::get_instance();
	}

    /**
	 * Instance.
	 *
	 * Ensures only one instance of the plugin class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			do_action( 'sidebarmenu/loaded' );
		}

		return self::$instance;
	}
    /**
	 * Register autoloader.
	 *
	 * @since 1.0.0
	 * @access private
	 */
    private function register_autoloader() {
		require_once SIDEBAR_MENU_INCLUDE_DIR . '/autoloader.php';
		Autoloader::run();
	}
}

Plugin::instance();
