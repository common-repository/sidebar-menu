<?php

add_action( 'admin_init', 'sidebarmenu_meta_boxes' );

function sidebarmenu_meta_boxes() {

	global $pagenow;

	$shortcode = isset($_GET['post'])? '[sidebarmenu id="'.absint($_GET['post']).'"]':'';
	$position = [
		[
			'value' => 'left',
			'label' => __( 'Left', 'sidebar-menu' ),
			'disable'   => '',
		],
		[
			'value' => 'right',
			'label' => __( 'Right', 'sidebar-menu' ),
			'disable'   => '',
		]
	];

	$choice = [['value' => '', 'label' => __( '-- Choose One --', 'sidebar-menu' ), 'src'   => '',]];
	$menu_items[] = $choice;
	$menus = wp_get_nav_menus();
	$sidebar_menus = [];

	$styles = [
		[
			'value' => 'normal',
			'label' => __( 'Normal', 'sidebar-menu' ),
			'disable'   => '',
		],
		[
			'value' => 'classic',
			'label' => __( 'Classic', 'sidebar-menu' ),
			'disable'   => '',
		],
		[
			'value' => 'animation',
			'label' => __( 'Animation', 'sidebar-menu' ),
			'disable'   => '',
		],
		[
			'value' => 'side-icon',
			'label' => __( 'Side Icon', 'sidebar-menu' ),
			'disable'   => '',
		],
	];
	
	$styles = apply_filters('sidebarmenu_styles', $styles);
	$styles = array_merge ($choice, $styles);

	foreach($menus as $item){
		$menu_items[] = ['label' => $item->name, 'value' => $item->name, 'src' =>''];
	}

	$shortcoe_settings_meta_box = array(
		'id'       => '_sidebarmenu_settings_shortcoe_meta_box',
		'title'    => __( 'Sidebar Menu Settings', 'sidebar-menu' ),
		'desc'     => '',
		'pages'    => array( 'sidebar-menu' ),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => array(
			array(
				'label' => __( 'General', 'sidebar-menu' ),
				'id'    => 'general',
				'type'  => 'tab',
			),

			array(
				'id'           => '_sidebarmenu_settings_menu',
				'label'        => __( 'Menu', 'sidebar-menu' ),
				'desc'         => sprintf(__('Select a menu. Don\'t have a menu yet? <a href="%s" target="_blank">Create a menu.</a>', 'sidebar-menu') , esc_url(admin_url('nav-menus.php'))),
				'std'          => '',
				'type'         => 'select',
				'section'      => 'general',
				'rows'         => '',
				'post_type'    => '',
				'taxonomy'     => '',
				'min_max_step' => '',
				'class'        => '',
				'condition'    => '',
				'operator'     => 'and',
				'choices'      =>  $menu_items,
			),
			array(
				'id'           => '_sidebarmenu_settings_style',
				'label'        => __( 'Menu Style', 'sidebar-menu' ),
				'desc'         => '',
				'std'          => 'classic',
				'type'         => 'select',
				'section'      => 'general',
				'rows'         => '',
				'post_type'    => '',
				'taxonomy'     => '',
				'min_max_step' => '',
				'class'        => '',
				'condition'    => '',
				'operator'     => 'and',
				'choices'      => $styles
			),
			array(
				'id'           => '_sidebarmenu_settings_sticky',
				'label'        => __( 'Sticky Menu', 'sidebar-menu' ),
				'desc'         => __( 'Pin the menu on the page side when the page scrolls up and down.', 'sidebar-menu' ),
				'std'          => 'yes',
				'type'         => 'select',
				'section'      => 'general',
				'rows'         => '',
				'post_type'    => '',
				'taxonomy'     => '',
				'min_max_step' => '',
				'class'        => '',
				'condition'    => '',
				'operator'     => 'and',
				'choices'      => array(
					array(
						'value' => 'yes',
						'label' => __( 'Yes', 'sidebar-menu' ),
						'src'   => '',
					),
					array(
						'value' => 'no',
						'label' => __( 'No', 'sidebar-menu' ),
						'src'   => '',
					),
				),
			),
			array(
				'id'           => '_sidebarmenu_settings_offsettop',
				'label'        => __( 'Top offset', 'sidebar-menu' ),
				'desc'         => esc_html__('Top offset, usually, it is the height of the website header.', 'sidebar-menu'),
				'std'          => '90',
				'type'         => 'numeric-slider',
				'section'      => 'general',
				'rows'         => '',
				'post_type'    => '',
				'taxonomy'     => '',
				'min_max_step' => '0,1000,1',
				'class'        => '',
				'condition'    => '_sidebarmenu_settings_sticky:is(yes)',
				'operator'     => 'and',
			),
			array(
				'label' => __( 'Footer offset', 'sidebar-menu' ),
				'id'    => '_sidebarmenu_settings_footeroffset',
				'type'  => 'text',
				'std' => 500,
				'condition'    => '_sidebarmenu_settings_sticky:is(yes)',
				'desc'  => esc_html__('When the page scrolls to the footer, cancel the fixed menu. It is usually the height of the footer.', 'sidebar-menu')
			),
			array(
				'label' => __( 'Smooth Scroll Time', 'sidebar-menu' ),
				'id'    => '_sidebarmenu_settings_scrolltime',
				'type'  => 'text',
				'std' => 300,
				'desc'  => __( 'Smooth scrolling speed.', 'sidebar-menu' ),
			),
			array(
				'id'           => '_sidebarmenu_settings_type',
				'label'        => __( 'Color Style', 'sidebar-menu' ),
				'desc'         => '',
				'std'          => '',
				'type'         => 'select',
				'class'        => '',
				'condition'    => '_sidebarmenu_settings_style:is(side-icon)',
				'operator'     => 'and',
				'choices'      =>  [
					[
						'value' => 'dark',
						'label' => __( 'Dark', 'sidebar-menu' ),
						'disable'   => '',
					],
					[
						'value' => 'light',
						'label' => __( 'Light', 'sidebar-menu' ),
						'disable'   => '',
					]
				],
			),
			array(
				'id'           => '_sidebarmenu_settings_coloractive',
				'label'        => __( 'Color of active item', 'sidebar-menu' ),
				'desc'         => __( 'The color of activated menu item.', 'sidebar-menu' ),
				'std'          => '',
				'type'         => 'colorpicker',
				'class'        => '',
				'condition'    => '_sidebarmenu_settings_style:not(side-icon)',
				'operator'     => 'and',
			),
			array(
				'id'           => '_sidebarmenu_settings_color',
				'label'        => __( 'Menu Color', 'sidebar-menu' ),
				'desc'         => __( 'Menu font color.', 'sidebar-menu' ),
				'std'          => '',
				'type'         => 'colorpicker',
				'class'        => '',
				'condition'    => '_sidebarmenu_settings_style:not(side-icon)',
				'operator'     => 'and',
			),

			array(
				'id'           => '_sidebarmenu_content_before',
				'label'        => __( 'Content Before Menu', 'sidebar-menu' ),
				'desc'         => '',
				'std'          => '',
				'type'         => 'textarea-simple',
				'rows'         => '10',
				'post_type'    => '',
				'taxonomy'     => '',
				'min_max_step' => '',
				'class'        => '',
				'condition'    => '',
				'operator'     => 'and',
			),
			array(
				'id'           => '_sidebarmenu_content_after',
				'label'        => __( 'Content Before Menu', 'sidebar-menu' ),
				'desc'         => '',
				'std'          => '',
				'type'         => 'textarea-simple',
				'rows'         => '10',
				'post_type'    => '',
				'taxonomy'     => '',
				'min_max_step' => '',
				'class'        => '',
				'condition'    => '',
				'operator'     => 'and',
			),

		),
	);
	
	$shortcoe_meta_box = array(
		'id'       => '_sidebarmenu_shortcoe_meta_box',
		'title'    => __( 'Sidebar Menu Shortcode', 'sidebar-menu' ),
		'desc'     => '',
		'pages'    => array( 'sidebar-menu' ),
		'context'  => 'side',
		'priority' => 'high',
		'fields'   => array(

			array(
				'id'           => '_sidebarmenu_settings_menu',
				'label'        => __( 'Shortcode', 'sidebar-menu' ),
				'desc'         => $shortcode,
				'std'          => '',
				'type'         => 'textblock-titled',
				'section'      => '',
				'rows'         => '',
				'post_type'    => '',
				'taxonomy'     => '',
				'min_max_step' => '',
				'class'        => '',
				'condition'    => '',
				'operator'     => 'and',
				'choices'      =>  '',
			),
		)
	);

	if('post.php' == $pagenow || 'post-new.php' == $pagenow){
		$args = array(  
			'post_type' => 'sidebar-menu',
			'post_status' => 'publish',
			'posts_per_page' => 30, 
			'orderby' => '', 
			'order' => '', 
		);
	
		$loop = new WP_Query( $args ); 
		
		while ( $loop->have_posts() ) : $loop->the_post(); 
			$sidebar_menus[] = [
				'value' => get_the_ID(),
				'label' => get_the_title(),
				'disable'   => '',
			];
		endwhile;
	
		wp_reset_postdata();

		$sidebar_menus = array_merge ($choice, $sidebar_menus);
	}

	$page_meta_box = array(
		'id'       => '_sidebarmenu_page_meta_box',
		'title'    => __( 'Sidebar Menu Options', 'sidebar-menu' ),
		'desc'     => '',
		'pages'    => array( 'page', 'post' ),
		'context'  => 'side',
		'priority' => 'high',
		'fields'   => array(
			array(
				'label' => __( 'Fixed Sidebar Menu', 'sidebar-menu' ),
				'id'    => '_sidebarmenu_fixed',
				'type'  => 'on-off',
				'desc'  => __( 'Pin the "Sidebar Menu" to the page side.', 'sidebar-menu' ),
				'std'   => 'off',
			),
			array(
				'id'           => '_sidebarmenu_fixed_menu',
				'label'        => __( 'Sidebar Menu', 'sidebar-menu' ),
				'desc'         => sprintf(__('Select a sidebar menu. Don\'t have a sidebar menu yet? <a href="%s" target="_blank">Create a Sidebar Menu.</a>', 'sidebar-menu') , esc_url(admin_url('post-new.php?post_type=sidebar-menu'))),
				'std'          => '',
				'type'         => 'select',
				'class'        => '',
				'condition'    => '',
				'operator'     => 'and',
				'choices'      =>  $sidebar_menus,
			),
			array(
				'id'           => '_sidebarmenu_position',
				'label'        => __( 'Position', 'sidebar-menu' ),
				'desc'         => __('Pin the "Sidebar Menu" to the left or right side of the page.', 'sidebar-menu'),
				'std'          => '',
				'type'         => 'select',
				'class'        => '',
				'condition'    => '',
				'operator'     => 'and',
				'choices'      =>  $position,
			),
			// array(
			// 	'id'           => '_sidebarmenu_width',
			// 	'label'        => __( 'Sidebar Menu Width', 'sidebar-menu' ),
			// 	'desc'         => '',
			// 	'std'          => '250px',
			// 	'type'         => 'text',
			// 	'class'        => '',
			// 	'condition'    => '',
			// 	'operator'     => 'and',
			// ),
			array(
				'id'           => '_sidebarmenu_width',
				'label'        => __( 'Sidebar Menu Width', 'sidebar-menu' ),
				'desc'         =>  '',
				'std'          => '250',
				'type'         => 'numeric-slider',
				'min_max_step' => '100,500,1',
				'class'        => '',
				'condition'    => '',
				'operator'     => 'and',
			),
			array(
				'id'           => '_sidebarmenu_height',
				'label'        => __( 'Height', 'sidebar-menu' ),
				'desc'         => __('Sidebar Menu height.', 'sidebar-menu'),
				'std'          => '1',
				'type'         => 'select',
				'class'        => '',
				'condition'    => '',
				'operator'     => 'and',
				'choices'      =>  [
					[
						'value' => '0',
						'label' => __( 'Auto Height', 'sidebar-menu' ),
						'disable'   => '',
					],
					[
						'value' => '1',
						'label' => __( 'Full Screen Height', 'sidebar-menu' ),
						'disable'   => '',
					]
				],
			),
			array(
				'id'           => '_sidebarmenu_top_padding',
				'label'        => __( 'Top Padding', 'sidebar-menu' ),
				'desc'         =>  '',
				'std'          => '0',
				'type'         => 'numeric-slider',
				'min_max_step' => '0,300,1',
				'class'        => '',
				'condition'    => '',
				'operator'     => 'and',
			),
			array(
				'id'           => '_sidebarmenu_left_padding',
				'label'        => __( 'Left Padding', 'sidebar-menu' ),
				'desc'         =>  '',
				'std'          => '0',
				'type'         => 'numeric-slider',
				'min_max_step' => '0,200,1',
				'class'        => '',
				'condition'    => '_sidebarmenu_position:is(left)',
				'operator'     => 'and',
			),
			array(
				'id'           => '_sidebarmenu_right_padding',
				'label'        => __( 'Right Padding', 'sidebar-menu' ),
				'desc'         =>  '',
				'std'          => '0',
				'type'         => 'numeric-slider',
				'min_max_step' => '0,200,1',
				'class'        => '',
				'condition'    => '_sidebarmenu_position:is(right)',
				'operator'     => 'and',
			),
		)
	);

	if ( function_exists( 'ot_register_meta_box' ) ) {
		ot_register_meta_box( $shortcoe_meta_box );
		ot_register_meta_box( $shortcoe_settings_meta_box );
		ot_register_meta_box( $page_meta_box );
	}
}
