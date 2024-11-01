<?php

add_action( 'init', 'sidebarmenu_options' );

function sidebarmenu_options() {

	if ( ! function_exists( 'ot_settings_id' ) || ! is_admin() ) {
		return false;
	}

	$saved_settings = get_option( ot_settings_id(), array() );

	$custom_settings = array(
		'sections'        => array(
			array(
				'id'    => 'general',
				'title' => __( 'General', 'sidebar-menu' ),
			),
		),
		'settings'        => array(
			
			array(
				'id'           => 'load_fontawesome',
				'label'        => __( 'Font Awesome Icons', 'sidebar-menu' ),
				'desc'         => '',
				'std'          => '',
				'type'         => 'checkbox',
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
						'label' => __( 'Load Font Awesome for all Sidebar Menus.', 'sidebar-menu' ),
						'src'   => '',
					),
					array(
						'value' => 'yes',
						'label' => __( 'Load Font Awesome on all pages.', 'sidebar-menu' ),
						'src'   => '',
					),
				),
			),

			
		),
	);

	$custom_settings = apply_filters( ot_settings_id() . '_args', $custom_settings );

	if ( $saved_settings !== $custom_settings ) {
		update_option( ot_settings_id(), $custom_settings );
	}

	global $ot_has_custom_theme_options;
	$ot_has_custom_theme_options = true;
}
