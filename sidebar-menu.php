<?php
/*
  Plugin Name: Sidebar Menu
  Plugin URI: https://www.hoosoft.com/plugins/sidebar-menu/
  Description: A sidebar menu allows you to organize information vertically, it is a part of a web page that makes it easy to place navigation or display links to help customers find important information easily. Also, it improves the availability area of the site.
  Version: 1.0.4
  Author: Hoosoft
  Author URI: http://www.hoosoft.com
  Text Domain: sidebar-menu
  Domain Path: /languages
  License: GPLv2 or later
*/

if ( ! defined( 'ABSPATH' ) ) return;

  $plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);
  $plugin_version = $plugin_data['Version'];
  define( 'SIDEBAR_MENU_DIR_PATH',  plugin_dir_path( __FILE__ ));
  define( 'SIDEBAR_MENU_INCLUDE_DIR', SIDEBAR_MENU_DIR_PATH.'includes' );
  define( 'SIDEBAR_MENU_DIR_URL',  plugin_dir_url( __FILE__ ));
  define( 'SIDEBAR_MENU_VER', $plugin_version );

  require_once SIDEBAR_MENU_INCLUDE_DIR.'/plugin.php';