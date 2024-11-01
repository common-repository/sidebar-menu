<?php
namespace SidebarMenu\Classes;
use SidebarMenu\Classes\Config;

class Helper{

	protected static $instance = null;
	protected $load_fontawesome = [];

	public function __construct( $args = [] ) {

		require( SIDEBAR_MENU_INCLUDE_DIR . '/option-tree/ot-loader.php' );
		require( SIDEBAR_MENU_INCLUDE_DIR. '/meta-boxes.php' );
		require( SIDEBAR_MENU_INCLUDE_DIR . '/plugin-options.php' );

		add_filter( 'ot_show_pages', '__return_false' );
		add_filter( 'ot_show_options_ui', '__return_false' );
		add_filter( 'ot_show_new_layout', '__return_false' );
		//add_filter( 'ot_use_theme_options', '__return_false' );
		add_filter('ot_theme_options_parent_slug', array($this, 'options_parent_slug' ));
		add_filter('ot_theme_options_page_title', array($this, 'options_page_title' ));
		add_filter('ot_theme_options_menu_title', array($this, 'options_page_title' ));
		add_filter('ot_theme_options_menu_slug', array($this, 'options_menu_slug' ));
		add_filter( 'ot_settings_id', array( $this,'get_settings_id') );
		add_filter( 'ot_header_version_text', array( $this,'header_version_text') );
		add_filter( 'ot_header_logo_link', array( $this,'header_logo_link') );

		add_action( 'plugins_loaded', array( $this, 'init' ) );
		add_action( 'wp_enqueue_scripts', array($this, 'frontend_scripts' ));
		add_action( 'admin_enqueue_scripts', array($this, 'admin_scripts' ));
		add_shortcode('sidebarmenu', array($this, 'shortcode' )); 
		add_filter('sidebarmenu_before', array($this, 'sidebarmenu_before' ), 10, 2);
		add_filter('sidebarmenu_after', array($this, 'sidebarmenu_after' ), 10, 2);

		add_filter('sidebarmenu_custom_css', array($this, 'sidebarmenu_fixed_css' ), 10, 4);
		add_filter('wp_footer', array($this, 'sidebarmenu_fixed_menu' ));

		add_action('init', array($this, 'shortcode_post' ));
		add_filter('manage_sidebar-menu_posts_columns', function($columns) {
			return array_merge($columns, ['shortcode' => __('Shortcode', 'sidebar-menu')]);
		});
		 
		add_action('manage_sidebar-menu_posts_custom_column', function($column_key, $post_id) {
			if ($column_key == 'shortcode') {
				echo '[sidebarmenu id="'.absint($post_id).'"]';
			}
		}, 10, 2);

	}

	public static function init() {
		
	}

	function get_settings_id(){

		return '_sidebarmenu_settings';
	}
	
	function options_parent_slug(){
	  return 'edit.php?post_type=sidebar-menu';
	}

	function options_page_title(){
		return __('Settings', 'sidebar-menu');
	}

	function options_menu_slug(){
		return 'sidebarmenu-settings';
	}

	function header_version_text(){
		return __('Sidebar Menu Settings', 'sidebar-menu');
	}

	function header_logo_link(){
		return '';
	}

	function frontend_scripts() {

		$scripts = Config::get_front_scripts();

		foreach ($scripts['styles'] as $k=>$v) {
			wp_register_style($k, $v[0], $v[1], $v[2], $v[3]);
		}

		foreach ($scripts['scripts'] as $k=>$v) {
			wp_register_script($k, $v[0], $v[1], $v[2], $v[3]);
		}
		
		$load_fontawesome = ot_get_option( 'load_fontawesome' );
		$this->load_fontawesome = $load_fontawesome;
		if(isset($load_fontawesome[1]) && 'yes' == $load_fontawesome[1]){
			wp_enqueue_style('font-awesome-6-all');
			wp_enqueue_style('font-awesome-4-shims');
		}
	}

	function admin_scripts() {

		$scripts = Config::get_admin_scripts();
		$currentScreen = get_current_screen();
		if(isset($currentScreen->post_type) && 'sidebar-menu' == $currentScreen->post_type){
			wp_enqueue_style( 'wp-color-picker' );
			foreach ($scripts['scripts'] as $k=>$v) {
				wp_enqueue_script($k, $v[0], $v[1], $v[2], $v[3]);
			}
		}
	}

	function sidebarmenu_before($content, $id) {
		$content_before = get_post_meta( $id, '_sidebarmenu_content_before', true );
		return do_shortcode(wp_kses_post($content_before));
	}

	function sidebarmenu_after($content, $id) {
		$content_after = get_post_meta( $id, '_sidebarmenu_content_after', true );
		return do_shortcode(wp_kses_post($content_after));
	}

	function sidebarmenu_fixed_menu(){
		global $post;
		if(is_singular()){
			$menu_fixed = get_post_meta( $post->ID, '_sidebarmenu_fixed', true );
			$sidebarmenu_id = get_post_meta( $post->ID, '_sidebarmenu_fixed_menu', true );
			if('on' == $menu_fixed && absint($sidebarmenu_id) > 0){
				echo do_shortcode('[sidebarmenu id="'.absint($sidebarmenu_id ).'" fixed="yes"]');
			}
		}
	}
	function sidebarmenu_fixed_css($css, $menu_id, $post_id, $css_id){

		$width = get_post_meta($post_id, '_sidebarmenu_width', true);
		$height = get_post_meta($post_id, '_sidebarmenu_height', true);
		$top_padding = get_post_meta($post_id, '_sidebarmenu_top_padding', true);
		$left_padding = get_post_meta($post_id, '_sidebarmenu_left_padding', true);
		$right_padding = get_post_meta($post_id, '_sidebarmenu_right_padding', true);
		$menu_fixed = get_post_meta( $post_id, '_sidebarmenu_fixed', true );
		$position = get_post_meta( $post_id, '_sidebarmenu_position', true );
		$style = get_post_meta( $menu_id, '_sidebarmenu_settings_style', true );
		$coloractive = get_post_meta( $menu_id, '_sidebarmenu_settings_coloractive', true );
		$selector = '#'.$css_id.'.sidebarmenu-fixed';

		if(is_admin_bar_showing()){ $top_padding = absint($top_padding) + 32;}
	
		$add_css = $selector.'{position:fixed !important;width:'.absint($width).'px;top:'.absint($top_padding).'px;}';

		if('1' == $height){
			$add_css .= $selector.'{height:100vh;}';
		}
		if('right' == $position){
			$add_css .= $selector.'{right:'.absint($right_padding).'px;}';
		}else{
			$add_css .= $selector.'{left:'.absint($left_padding).'px;}';
		}

		switch($style){
			case "side-icon":
				if('right' == $position){
					$add_css .= $selector.' .hoo-side-icon ul{right:100%;left:initial;}';
				}
				break;
			case "animation":
				$add_css .= $selector.' .hoo-animation li ul, '.$selector.' .hoo-animation li ul li ul{right: 280px;left:initial;}';
				$add_css .= $selector.' .hoo-animation li ul:before, '.$selector.' .hoo-animation li ul li ul:before {border-left: 5px solid '.esc_attr($coloractive).'; border-right: initial;}';
				$add_css .= $selector.' .hoo-animation li ul,'.$selector.' .hoo-animation li ul li ul {border-right: 4px solid '.esc_attr($coloractive).';border-left: initial;}';
				$add_css .= $selector.' .hoo-animation li ul:before{right: -9px;left: initial;}';
				break;
			case "normal":

				break;
		}
		
		$css = $css.$add_css;
		$fixed_css = apply_filters('sidebarmenu_fixed_css', $css, $menu_id, $post_id, $css_id);
		return $fixed_css;

	}

	function randStr($len){
		$chars='0123456789abcdefghijklmnopqrstuvwxyz';
		$string='';
		for(;$len>=1;$len--)
		{
			$position=rand()%strlen($chars);
			$string.=substr($chars,$position,1);
		}
		return $string;
	}

	function shortcode($atts) {
		global $post;
		$default = array(
			'offsettop' => 90,
			'footeroffset' => 300,
            'scrolltime' => 500,
			'menu' => '',
			'style' => 'normal',
			'sticky' => 'yes',
			'coloractive' => '',
			'color' => '',
			'id' => 0,
			'fixed' => 'no'
		);

		$options = shortcode_atts($default, $atts);

		if(is_numeric($options['id']) && $options['id'] > 0){
			// $settings = get_post_meta( $options['id'], '_sidebar_menu_settings', true );
			$settings['menu'] = get_post_meta( $options['id'], '_sidebarmenu_settings_menu', true );
			$settings['style'] = get_post_meta( $options['id'], '_sidebarmenu_settings_style', true );
			$settings['offsettop'] = get_post_meta( $options['id'], '_sidebarmenu_settings_offsettop', true );
			$settings['scrolltime'] = get_post_meta( $options['id'], '_sidebarmenu_settings_scrolltime', true );
			$settings['footeroffset'] = get_post_meta( $options['id'], '_sidebarmenu_settings_footeroffset', true );
			$settings['coloractive'] = get_post_meta( $options['id'], '_sidebarmenu_settings_coloractive', true );
			$settings['color'] = get_post_meta( $options['id'], '_sidebarmenu_settings_color', true );
			$settings['sticky'] = get_post_meta( $options['id'], '_sidebarmenu_settings_sticky', true );
			$settings['type'] = get_post_meta( $options['id'], '_sidebarmenu_settings_type', true );
			$options = shortcode_atts($options, $settings);
		}

		$menu_class = '';
		$link_before = '';
		$link_after = '';
		$walker = '';
		$wrapper_class = 'sidebar-menu sidebarmenu-'.$options['style'];
		$wrapper_id = 'sidebarmenu-'.$this->randStr(5);

		switch($options['style']){
			case "normal":
				$menu_class .= ' hoo-normal';
				break;
			case "classic":
				$menu_class .= ' hoo-classic hoo-sidenav';
				break;
			case "animation":
				$menu_class .= ' hoo-animation';
				$link_before = '<strong>';
				$link_after = '</strong>';
				$walker = new Description_Walker;
				break;
			case "side-icon":
				$menu_class .= ' hoo-side-icon red';
				if(isset($settings['type']) && 'dark' == $settings['type']){
					$wrapper_class .= ' dark';
					$menu_class .= ' dark';
				} 
				wp_enqueue_script('sidebarmenu-side-icon');
				wp_add_inline_script('sidebarmenu-side-icon', 'jQuery(document).ready(function($){$(".hoo-side-icon").verticalnav({speed: 400,align: "left"});});', 'after');
				break;
		}

		if(('yes' == $options['sticky'] || '' == $options['sticky']) && 'yes' != $options['fixed']){
			$wrapper_class .= ' sidebarmenu-sticky';
		}

		if('yes' == $options['fixed']){
			$wrapper_class .= ' sidebarmenu-fixed';
		}

		$menu = wp_nav_menu(['echo'=>false, 'menu' => $options['menu'], 'container_class'=>'sidebarmenu-container', 'menu_class' => $menu_class,'link_before' => $link_before, 'link_after' => $link_after,'walker' => $walker]);

		$before = apply_filters('sidebarmenu_before', '', $options['id']);
		$after = apply_filters('sidebarmenu_after', '', $options['id']);

		$sidebarmenu = '<div id="'.$wrapper_id.'" class="'.$wrapper_class.'"><div class="sidebarmenu-before">'.$before.'</div>'.$menu.'<div class="sidebarmenu-after">'.$after.'</div></div>';
		
		if(isset($this->load_fontawesome[0]) && 'yes' == $this->load_fontawesome[0]){
			wp_enqueue_style('font-awesome-6-all');
			wp_enqueue_style('font-awesome-4-shims');
		}

		wp_enqueue_script('sidebarmenu-main');
		wp_enqueue_style('sidebarmenu-main');
		wp_add_inline_script('sidebarmenu-main', 'var sidebarmenuConfig = '.json_encode($options), 'before');

		$custom_css = '';

		if($options['color'] !='') $custom_css .= ".sidebar-menu a{color: ".esc_attr($options['color']).";}";
		if($options['coloractive'] !='') {
			switch($options['style']){
				case "normal":
				case "classic":
					$custom_css .= ".sidebar-menu .hoo-classic > .active:focus > a, .sidebar-menu .hoo-classic > .active:hover > a, .sidebar-menu .hoo-classic > .active > a {color: ".esc_attr($options['coloractive']).";border-left: 2px solid ".esc_attr($options['coloractive']).";}.sidebar-menu li.active a, .sidebar-menu li a:hover{color: ".esc_attr($options['coloractive']).";}";
					break;
				case "animation":
					$custom_css .= ".hoo-animation li:hover > a, .hoo-animation li.active > a{color: ".esc_attr($options['coloractive']).";}.hoo-animation li.active > a {border-left: 4px solid ".esc_attr($options['coloractive'])."; border-right: 4px solid ".esc_attr($options['coloractive']).";}.hoo-animation li.active > a:before{border-left: 5px solid ".esc_attr($options['coloractive']).";}.hoo-animation li.active > a:after {border-right: 5px solid ".esc_attr($options['coloractive']).";}.hoo-animation li ul li ul li > ul:before {border-bottom:5px solid ".esc_attr($options['coloractive']).";}.hoo-animation li ul li ul li > ul:before {border-bottom:5px solid ".esc_attr($options['coloractive']).";}.hoo-animation li ul, .hoo-animation li ul li ul { border-left: 4px solid ".esc_attr($options['coloractive']).";}.hoo-animation li ul:before,.hoo-animation li ul li ul:before{border-right: 5px solid ".esc_attr($options['coloractive']).";}";
					break;
			}
	
		}

		$custom_css = apply_filters('sidebarmenu_custom_css', $custom_css, $options['id'], $post->ID, $wrapper_id);

        if($custom_css) wp_add_inline_style( 'sidebarmenu-main', $custom_css );

		return wp_kses_post($sidebarmenu);
	}
	
	function shortcode_post() {

		$labels = array(
			'name'          => __('Sidebar Menu', 'sidebar-menu'),
			'singular_name' => 'sidebar-menu'
		);
	
		$supports = array(
			'title',
			'custom-fields'
		);
	
		$args = array(
			'labels'              => $labels,
			'description'         => __('Post type post Sidebar Menu', 'sidebar-menu'),
			'supports'            => $supports,
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'menu_position'       => 5,
			'menu_icon'           =>  SIDEBAR_MENU_DIR_URL.'assets/images/menu-20.png',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'post'
		);
	
		register_post_type('sidebar-menu', $args);
	}
	
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

}