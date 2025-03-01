<?php
/**
 * OptionTree Option Type Functions.
 *
 * Functions used to build each option type.
 *
 * @package OptionTree
 */

if ( ! defined( 'OT_VERSION' ) ) {
	exit( 'No direct script access allowed' );
}

if ( ! function_exists( 'ot_display_by_type' ) ) {

	/**
	 * Builds the HTML for each of the available option types by calling those
	 * function with call_user_func and passing the arguments to the second param.
	 *
	 * All fields are required!
	 *
	 * @param array $args The array of arguments are as follows.
	 *     @var string $type Type of option.
	 *     @var string $field_id The field ID.
	 *     @var string $field_name The field Name.
	 *     @var mixed  $field_value The field value is a string or an array of values.
	 *     @var string $field_desc The field description.
	 *     @var string $field_std The standard value.
	 *     @var string $field_class Extra CSS classes.
	 *     @var array  $field_choices The array of option choices.
	 *     @var array  $field_settings The array of settings for a list item.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_display_by_type( $args = array() ) {

		// Allow filters to be executed on the array.
		$args = apply_filters( 'ot_display_by_type', $args );

		if ( empty( $args['type'] ) ) {
			return;
		}

		// Build the function name.
		$function_name_by_type = str_replace( '-', '_', 'ot_type_' . $args['type'] );

		// Call the function & pass in arguments array.
		if ( function_exists( $function_name_by_type ) ) {
			call_user_func( $function_name_by_type, $args );
		} else {
			echo '<p>' . esc_html__( 'Sorry, this function does not exist', 'sidebar-menu' ) . '</p>';
		}

	}
}

if ( ! function_exists( 'ot_type_background' ) ) {

	/**
	 * Background option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_type_background( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// If an attachment ID is stored here fetch its URL and replace the value.
		if ( isset( $field_value['background-image'] ) && wp_attachment_is_image( $field_value['background-image'] ) ) {

			$attachment_data = wp_get_attachment_image_src( $field_value['background-image'], 'original' );

			/* check for attachment data */
			if ( $attachment_data ) {

				$field_src = $attachment_data[0];

			}
		}

		// Format setting outer wrapper.
		echo '<div class="format-setting type-background ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Allow fields to be filtered.
		$ot_recognized_background_fields = apply_filters(
			'ot_recognized_background_fields',
			array(
				'background-color',
				'background-repeat',
				'background-attachment',
				'background-position',
				'background-size',
				'background-image',
			),
			$field_id
		);

		echo '<div class="ot-background-group">';

		// Build background color.
		if ( in_array( 'background-color', $ot_recognized_background_fields, true ) ) {

			echo '<div class="option-tree-ui-colorpicker-input-wrap">';

			echo '<script>jQuery(document).ready(function($) { OT_UI.bind_colorpicker("' . esc_attr( $field_id ) . '-picker"); });</script>';

			$background_color = isset( $field_value['background-color'] ) ? $field_value['background-color'] : '';

			echo '<input type="text" name="' . esc_attr( $field_name ) . '[background-color]" id="' . esc_attr( $field_id ) . '-picker" value="' . esc_attr( $background_color ) . '" class="hide-color-picker ' . esc_attr( $field_class ) . '" />';

			echo '</div>';
		}

		// Build background repeat.
		if ( in_array( 'background-repeat', $ot_recognized_background_fields, true ) ) {

			$background_repeat = isset( $field_value['background-repeat'] ) ? esc_attr( $field_value['background-repeat'] ) : '';

			echo '<select name="' . esc_attr( $field_name ) . '[background-repeat]" id="' . esc_attr( $field_id ) . '-repeat" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';

			echo '<option value="">' . esc_html__( 'background-repeat', 'sidebar-menu' ) . '</option>';
			foreach ( ot_recognized_background_repeat( $field_id ) as $key => $value ) {

				echo '<option value="' . esc_attr( $key ) . '" ' . selected( $background_repeat, $key, false ) . '>' . esc_attr( $value ) . '</option>';
			}

			echo '</select>';
		}

		// Build background attachment.
		if ( in_array( 'background-attachment', $ot_recognized_background_fields, true ) ) {

			$background_attachment = isset( $field_value['background-attachment'] ) ? $field_value['background-attachment'] : '';

			echo '<select name="' . esc_attr( $field_name ) . '[background-attachment]" id="' . esc_attr( $field_id ) . '-attachment" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';

			echo '<option value="">' . esc_html__( 'background-attachment', 'sidebar-menu' ) . '</option>';

			foreach ( ot_recognized_background_attachment( $field_id ) as $key => $value ) {

				echo '<option value="' . esc_attr( $key ) . '" ' . selected( $background_attachment, $key, false ) . '>' . esc_attr( $value ) . '</option>';
			}

			echo '</select>';
		}

		// Build background position.
		if ( in_array( 'background-position', $ot_recognized_background_fields, true ) ) {

			$background_position = isset( $field_value['background-position'] ) ? $field_value['background-position'] : '';

			echo '<select name="' . esc_attr( $field_name ) . '[background-position]" id="' . esc_attr( $field_id ) . '-position" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';

			echo '<option value="">' . esc_html__( 'background-position', 'sidebar-menu' ) . '</option>';

			foreach ( ot_recognized_background_position( $field_id ) as $key => $value ) {

				echo '<option value="' . esc_attr( $key ) . '" ' . selected( $background_position, $key, false ) . '>' . esc_attr( $value ) . '</option>';
			}

			echo '</select>';
		}

		// Build background size .
		if ( in_array( 'background-size', $ot_recognized_background_fields, true ) ) {

			/**
			 * Use this filter to create a select instead of an text input.
			 * Be sure to return the array in the correct format. Add an empty
			 * value to the first choice so the user can leave it blank.
			 *
			 *  Example: array(
			 *    array(
			 *      'label' => 'background-size',
			 *      'value' => ''
			 *    ),
			 *    array(
			 *      'label' => 'cover',
			 *      'value' => 'cover'
			 *    ),
			 *    array(
			 *      'label' => 'contain',
			 *      'value' => 'contain'
			 *    )
			 *  )
			 */
			$choices = apply_filters( 'ot_type_background_size_choices', '', $field_id );

			if ( is_array( $choices ) && ! empty( $choices ) ) {

				// Build select.
				echo '<select name="' . esc_attr( $field_name ) . '[background-size]" id="' . esc_attr( $field_id ) . '-size" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';

				foreach ( (array) $choices as $choice ) {
					if ( isset( $choice['value'] ) && isset( $choice['label'] ) ) {
						echo '<option value="' . esc_attr( $choice['value'] ) . '" ' . selected( ( isset( $field_value['background-size'] ) ? $field_value['background-size'] : '' ), $choice['value'], false ) . '>' . esc_attr( $choice['label'] ) . '</option>';
					}
				}

				echo '</select>';
			} else {

				echo '<input type="text" name="' . esc_attr( $field_name ) . '[background-size]" id="' . esc_attr( $field_id ) . '-size" value="' . esc_attr( isset( $field_value['background-size'] ) ? $field_value['background-size'] : '' ) . '" class="widefat ot-background-size-input option-tree-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . esc_html__( 'background-size', 'sidebar-menu' ) . '" />';
			}
		}

		echo '</div>';

		// Build background image.
		if ( in_array( 'background-image', $ot_recognized_background_fields, true ) ) {

			echo '<div class="option-tree-ui-upload-parent">';

			// Input.
			echo '<input type="text" name="' . esc_attr( $field_name ) . '[background-image]" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( isset( $field_value['background-image'] ) ? $field_value['background-image'] : '' ) . '" class="widefat option-tree-ui-upload-input ' . esc_attr( $field_class ) . '" placeholder="' . esc_html__( 'background-image', 'sidebar-menu' ) . '" />';

			// Add media button.
			echo '<a href="javascript:void(0);" class="ot_upload_media option-tree-ui-button button button-primary light" rel="' . esc_attr( $post_id ) . '" title="' . esc_html__( 'Add Media', 'sidebar-menu' ) . '"><span class="icon ot-icon-plus-circle"></span>' . esc_html__( 'Add Media', 'sidebar-menu' ) . '</a>';

			echo '</div>';

			// Media.
			if ( isset( $field_value['background-image'] ) && '' !== $field_value['background-image'] ) {

				/* replace image src */
				if ( isset( $field_src ) ) {
					$field_value['background-image'] = $field_src;
				}

				echo '<div class="option-tree-ui-media-wrap" id="' . esc_attr( $field_id ) . '_media">';

				if ( preg_match( '/\.(?:jpe?g|png|gif|ico)$/i', $field_value['background-image'] ) ) {
					echo '<div class="option-tree-ui-image-wrap"><img src="' . esc_url_raw( $field_value['background-image'] ) . '" alt="" /></div>';
				}

				echo '<a href="javascript:(void);" class="option-tree-ui-remove-media option-tree-ui-button button button-secondary light" title="' . esc_html__( 'Remove Media', 'sidebar-menu' ) . '"><span class="icon ot-icon-minus-circle"></span>' . esc_html__( 'Remove Media', 'sidebar-menu' ) . '</a>';

				echo '</div>';
			}
		}

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_border' ) ) {

	/**
	 * Border Option Type
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args The options arguments.
	 *
	 * @access public
	 * @since  2.5.0
	 */
	function ot_type_border( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-border ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Allow fields to be filtered.
		$ot_recognized_border_fields = apply_filters(
			'ot_recognized_border_fields',
			array(
				'width',
				'unit',
				'style',
				'color',
			),
			$field_id
		);

		// Build border width.
		if ( in_array( 'width', $ot_recognized_border_fields, true ) ) {

			$width = isset( $field_value['width'] ) ? $field_value['width'] : '';

			echo '<div class="ot-option-group ot-option-group--one-sixth"><input type="text" name="' . esc_attr( $field_name ) . '[width]" id="' . esc_attr( $field_id ) . '-width" value="' . esc_attr( $width ) . '" class="widefat option-tree-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . esc_html__( 'width', 'sidebar-menu' ) . '" /></div>';
		}

		// Build unit dropdown.
		if ( in_array( 'unit', $ot_recognized_border_fields, true ) ) {

			echo '<div class="ot-option-group ot-option-group--one-fourth">';

			echo '<select name="' . esc_attr( $field_name ) . '[unit]" id="' . esc_attr( $field_id ) . '-unit" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';

			echo '<option value="">' . esc_html__( 'unit', 'sidebar-menu' ) . '</option>';

			foreach ( ot_recognized_border_unit_types( $field_id ) as $unit ) {
				echo '<option value="' . esc_attr( $unit ) . '" ' . ( isset( $field_value['unit'] ) ? selected( $field_value['unit'], $unit, false ) : '' ) . '>' . esc_attr( $unit ) . '</option>';
			}

			echo '</select>';

			echo '</div>';
		}

		// Build style dropdown.
		if ( in_array( 'style', $ot_recognized_border_fields, true ) ) {

			echo '<div class="ot-option-group ot-option-group--one-fourth">';

			echo '<select name="' . esc_attr( $field_name ) . '[style]" id="' . esc_attr( $field_id ) . '-style" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';

			echo '<option value="">' . esc_html__( 'style', 'sidebar-menu' ) . '</option>';

			foreach ( ot_recognized_border_style_types( $field_id ) as $key => $style ) {
				echo '<option value="' . esc_attr( $key ) . '" ' . ( isset( $field_value['style'] ) ? selected( $field_value['style'], $key, false ) : '' ) . '>' . esc_attr( $style ) . '</option>';
			}

			echo '</select>';

			echo '</div>';
		}

		// Build color.
		if ( in_array( 'color', $ot_recognized_border_fields, true ) ) {

			echo '<div class="option-tree-ui-colorpicker-input-wrap">';

			echo '<script>jQuery(document).ready(function($) { OT_UI.bind_colorpicker("' . esc_attr( $field_id ) . '-picker"); });</script>';

			$color = isset( $field_value['color'] ) ? $field_value['color'] : '';

			echo '<input type="text" name="' . esc_attr( $field_name ) . '[color]" id="' . esc_attr( $field_id ) . '-picker" value="' . esc_attr( $color ) . '" class="hide-color-picker ' . esc_attr( $field_class ) . '" />';

			echo '</div>';
		}

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_box_shadow' ) ) {

	/**
	 * Box Shadow Option Type
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args The options arguments.
	 *
	 * @access public
	 * @since  2.5.0
	 */
	function ot_type_box_shadow( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-box-shadow ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Allow fields to be filtered.
		$ot_recognized_box_shadow_fields = apply_filters(
			'ot_recognized_box_shadow_fields',
			array(
				'inset',
				'offset-x',
				'offset-y',
				'blur-radius',
				'spread-radius',
				'color',
			),
			$field_id
		);

		// Build inset.
		if ( in_array( 'inset', $ot_recognized_box_shadow_fields, true ) ) {

			echo '<div class="ot-option-group ot-option-group--checkbox"><p>';
			echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[inset]" id="' . esc_attr( $field_id ) . '-inset" value="inset" ' . ( isset( $field_value['inset'] ) ? checked( $field_value['inset'], 'inset', false ) : '' ) . ' class="option-tree-ui-checkbox ' . esc_attr( $field_class ) . '" />';
			echo '<label for="' . esc_attr( $field_id ) . '-inset">inset</label>';
			echo '</p></div>';
		}

		// Build horizontal offset.
		if ( in_array( 'offset-x', $ot_recognized_box_shadow_fields, true ) ) {

			$offset_x = isset( $field_value['offset-x'] ) ? esc_attr( $field_value['offset-x'] ) : '';

			echo '<div class="ot-option-group ot-option-group--one-fifth"><span class="ot-icon-arrows-h ot-option-group--icon"></span><input type="text" name="' . esc_attr( $field_name ) . '[offset-x]" id="' . esc_attr( $field_id ) . '-offset-x" value="' . esc_attr( $offset_x ) . '" class="widefat option-tree-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . esc_html__( 'offset-x', 'sidebar-menu' ) . '" /></div>';
		}

		// Build vertical offset.
		if ( in_array( 'offset-y', $ot_recognized_box_shadow_fields, true ) ) {

			$offset_y = isset( $field_value['offset-y'] ) ? esc_attr( $field_value['offset-y'] ) : '';

			echo '<div class="ot-option-group ot-option-group--one-fifth"><span class="ot-icon-arrows-v ot-option-group--icon"></span><input type="text" name="' . esc_attr( $field_name ) . '[offset-y]" id="' . esc_attr( $field_id ) . '-offset-y" value="' . esc_attr( $offset_y ) . '" class="widefat option-tree-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . esc_html__( 'offset-y', 'sidebar-menu' ) . '" /></div>';
		}

		// Build blur-radius radius.
		if ( in_array( 'blur-radius', $ot_recognized_box_shadow_fields, true ) ) {

			$blur_radius = isset( $field_value['blur-radius'] ) ? esc_attr( $field_value['blur-radius'] ) : '';

			echo '<div class="ot-option-group ot-option-group--one-fifth"><span class="ot-icon-circle ot-option-group--icon"></span><input type="text" name="' . esc_attr( $field_name ) . '[blur-radius]" id="' . esc_attr( $field_id ) . '-blur-radius" value="' . esc_attr( $blur_radius ) . '" class="widefat option-tree-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . esc_html__( 'blur-radius', 'sidebar-menu' ) . '" /></div>';
		}

		// Build spread-radius radius.
		if ( in_array( 'spread-radius', $ot_recognized_box_shadow_fields, true ) ) {

			$spread_radius = isset( $field_value['spread-radius'] ) ? esc_attr( $field_value['spread-radius'] ) : '';

			echo '<div class="ot-option-group ot-option-group--one-fifth"><span class="ot-icon-arrows-alt ot-option-group--icon"></span><input type="text" name="' . esc_attr( $field_name ) . '[spread-radius]" id="' . esc_attr( $field_id ) . '-spread-radius" value="' . esc_attr( $spread_radius ) . '" class="widefat option-tree-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . esc_html__( 'spread-radius', 'sidebar-menu' ) . '" /></div>';
		}

		// Build color.
		if ( in_array( 'color', $ot_recognized_box_shadow_fields, true ) ) {

			echo '<div class="option-tree-ui-colorpicker-input-wrap">';

			echo '<script>jQuery(document).ready(function($) { OT_UI.bind_colorpicker("' . esc_attr( $field_id ) . '-picker"); });</script>';

			$color = isset( $field_value['color'] ) ? $field_value['color'] : '';

			echo '<input type="text" name="' . esc_attr( $field_name ) . '[color]" id="' . esc_attr( $field_id ) . '-picker" value="' . esc_attr( $color ) . '" class="hide-color-picker ' . esc_attr( $field_class ) . '" />';

			echo '</div>';
		}

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_category_checkbox' ) ) {

	/**
	 * Category Checkbox option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_type_category_checkbox( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args );// phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-category-checkbox type-checkbox ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Get category array.
		$categories = get_categories( apply_filters( 'ot_type_category_checkbox_query', array( 'hide_empty' => false ), $field_id ) );

		// Build categories.
		if ( ! empty( $categories ) ) {
			foreach ( $categories as $category ) {
				echo '<p>';
				echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . esc_attr( $category->term_id ) . ']" id="' . esc_attr( $field_id ) . '-' . esc_attr( $category->term_id ) . '" value="' . esc_attr( $category->term_id ) . '" ' . ( isset( $field_value[ $category->term_id ] ) ? checked( $field_value[ $category->term_id ], $category->term_id, false ) : '' ) . ' class="option-tree-ui-checkbox ' . esc_attr( $field_class ) . '" />';
				echo '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $category->term_id ) . '">' . esc_attr( $category->name ) . '</label>';
				echo '</p>';
			}
		} else {
			echo '<p>' . esc_html__( 'No Categories Found', 'sidebar-menu' ) . '</p>';
		}

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_category_select' ) ) {

	/**
	 * Category Select option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_type_category_select( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-category-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Build category.
		echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';

		// Get category array.
		$categories = get_categories( apply_filters( 'ot_type_category_select_query', array( 'hide_empty' => false ), $field_id ) );

		// Has cats.
		if ( ! empty( $categories ) ) {
			echo '<option value="">-- ' . esc_html__( 'Choose One', 'sidebar-menu' ) . ' --</option>';
			foreach ( $categories as $category ) {
				echo '<option value="' . esc_attr( $category->term_id ) . '" ' . selected( $field_value, $category->term_id, false ) . '>' . esc_attr( $category->name ) . '</option>';
			}
		} else {
			echo '<option value="">' . esc_html__( 'No Categories Found', 'sidebar-menu' ) . '</option>';
		}

		echo '</select>';

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_checkbox' ) ) {

	/**
	 * Checkbox option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_type_checkbox( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-checkbox ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Build checkbox.
		foreach ( (array) $field_choices as $key => $choice ) {
			if ( isset( $choice['value'] ) && isset( $choice['label'] ) ) {
				echo '<p>';
				echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . esc_attr( $key ) . ']" id="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '" value="' . esc_attr( $choice['value'] ) . '" ' . ( isset( $field_value[ $key ] ) ? checked( $field_value[ $key ], $choice['value'], false ) : '' ) . ' class="option-tree-ui-checkbox ' . esc_attr( $field_class ) . '" />';
				echo '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '">' . esc_attr( $choice['label'] ) . '</label>';
				echo '</p>';
			}
		}

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_colorpicker' ) ) {

	/**
	 * Colorpicker option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access  public
	 * @since   2.0
	 * @updated 2.2.0
	 */
	function ot_type_colorpicker( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-colorpicker ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Build colorpicker.
		echo '<div class="option-tree-ui-colorpicker-input-wrap">';

		// Colorpicker JS.
		echo '<script>jQuery(document).ready(function($) { OT_UI.bind_colorpicker("' . esc_attr( $field_id ) . '"); });</script>';

		// Input.
		echo '<input type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( $field_value ) . '" class="hide-color-picker ' . esc_attr( $field_class ) . '"' . ( ! empty( $field_std ) ? ' data-default-color="' . esc_attr( $field_std ) . '"' : '' ) . ' />';

		echo '</div>';

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_colorpicker_opacity' ) ) {

	/**
	 * Colorpicker Opacity option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.5.0
	 */
	function ot_type_colorpicker_opacity( $args = array() ) {

		$args['field_class'] = isset( $args['field_class'] ) ? $args['field_class'] . ' ot-colorpicker-opacity' : 'ot-colorpicker-opacity';
		ot_type_colorpicker( $args );
	}
}

if ( ! function_exists( 'ot_type_css' ) ) {

	/**
	 * CSS option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_type_css( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-css simple ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Build textarea for CSS.
		echo '<textarea class="hidden" id="textarea_' . esc_attr( $field_id ) . '" name="' . esc_attr( $field_name ) . '">' . esc_textarea( $field_value ) . '</textarea>';

		// Build pre to convert it into ace editor later.
		echo '<pre class="ot-css-editor ' . esc_attr( $field_class ) . '" id="' . esc_attr( $field_id ) . '">' . esc_textarea( $field_value ) . '</pre>';

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_custom_post_type_checkbox' ) ) {

	/**
	 * Custom Post Type Checkbox option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_type_custom_post_type_checkbox( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-custom-post-type-checkbox type-checkbox ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Setup the post types.
		$post_type = isset( $field_post_type ) ? explode( ',', $field_post_type ) : array( 'post' );

		// Query posts array.
		$my_posts = get_posts(
			apply_filters(
				'ot_type_custom_post_type_checkbox_query',
				array(
					'post_type'      => $post_type,
					'posts_per_page' => -1,
					'orderby'        => 'title',
					'order'          => 'ASC',
					'post_status'    => 'any',
				),
				$field_id
			)
		);

		// Has posts.
		if ( is_array( $my_posts ) && ! empty( $my_posts ) ) {
			foreach ( $my_posts as $my_post ) {
				$post_title = ! empty( $my_post->post_title ) ? $my_post->post_title : 'Untitled';
				echo '<p>';
				echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . esc_attr( $my_post->ID ) . ']" id="' . esc_attr( $field_id ) . '-' . esc_attr( $my_post->ID ) . '" value="' . esc_attr( $my_post->ID ) . '" ' . ( isset( $field_value[ $my_post->ID ] ) ? checked( $field_value[ $my_post->ID ], $my_post->ID, false ) : '' ) . ' class="option-tree-ui-checkbox ' . esc_attr( $field_class ) . '" />';
				echo '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $my_post->ID ) . '">' . esc_html( $post_title ) . '</label>';
				echo '</p>';
			}
		} else {
			echo '<p>' . esc_html__( 'No Posts Found', 'sidebar-menu' ) . '</p>';
		}

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_custom_post_type_select' ) ) {

	/**
	 * Custom Post Type Select option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_type_custom_post_type_select( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-custom-post-type-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Build category.
		echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';

		// Setup the post types.
		$post_type = isset( $field_post_type ) ? explode( ',', $field_post_type ) : array( 'post' );

		// Query posts array.
		$my_posts = get_posts(
			apply_filters(
				'ot_type_custom_post_type_select_query',
				array(
					'post_type'      => $post_type,
					'posts_per_page' => -1,
					'orderby'        => 'title',
					'order'          => 'ASC',
					'post_status'    => 'any',
				),
				$field_id
			)
		);

		// Has posts.
		if ( is_array( $my_posts ) && ! empty( $my_posts ) ) {
			echo '<option value="">-- ' . esc_html__( 'Choose One', 'sidebar-menu' ) . ' --</option>';
			foreach ( $my_posts as $my_post ) {
				$post_title = ! empty( $my_post->post_title ) ? $my_post->post_title : 'Untitled';
				echo '<option value="' . esc_attr( $my_post->ID ) . '" ' . selected( $field_value, $my_post->ID, false ) . '>' . esc_html( $post_title ) . '</option>';
			}
		} else {
			echo '<option value="">' . esc_html__( 'No Posts Found', 'sidebar-menu' ) . '</option>';
		}

		echo '</select>';

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_date_picker' ) ) {

	/**
	 * Date Picker option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.3
	 */
	function ot_type_date_picker( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Filter date format.
		$date_format = apply_filters( 'ot_type_date_picker_date_format', 'yy-mm-dd', $field_id );

		/**
		 * Filter the addition of the readonly attribute.
		 *
		 * @since 2.5.0
		 *
		 * @param bool $is_readonly Whether to add the 'readonly' attribute. Default 'false'.
		 * @param string $field_id The field ID.
		 */
		$is_readonly = apply_filters( 'ot_type_date_picker_readonly', false, $field_id );

		// Format setting outer wrapper.
		echo '<div class="format-setting type-date-picker ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Date picker JS.
		echo '<script>jQuery(document).ready(function($) { OT_UI.bind_date_picker("' . esc_attr( $field_id ) . '", "' . esc_attr( $date_format ) . '"); });</script>';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Build date picker.
		echo '<input type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( $field_value ) . '" class="widefat option-tree-ui-input ' . esc_attr( $field_class ) . '"' . ( true === $is_readonly ? ' readonly' : '' ) . ' />';

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_date_time_picker' ) ) {

	/**
	 * Date Time Picker option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.3
	 */
	function ot_type_date_time_picker( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Filter date format.
		$date_format = apply_filters( 'ot_type_date_time_picker_date_format', 'yy-mm-dd', $field_id );

		/**
		 * Filter the addition of the readonly attribute.
		 *
		 * @since 2.5.0
		 *
		 * @param bool $is_readonly Whether to add the 'readonly' attribute. Default 'false'.
		 * @param string $field_id The field ID.
		 */
		$is_readonly = apply_filters( 'ot_type_date_time_picker_readonly', false, $field_id );

		// Format setting outer wrapper.
		echo '<div class="format-setting type-date-time-picker ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Date time picker JS.
		echo '<script>jQuery(document).ready(function($) { OT_UI.bind_date_time_picker("' . esc_attr( $field_id ) . '", "' . esc_attr( $date_format ) . '"); });</script>';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Build date time picker.
		echo '<input type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( $field_value ) . '" class="widefat option-tree-ui-input ' . esc_attr( $field_class ) . '"' . ( true === $is_readonly ? ' readonly' : '' ) . ' />';

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_dimension' ) ) {

	/**
	 * Dimension Option Type
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args The options arguments.
	 *
	 * @access public
	 * @since  2.5.0
	 */
	function ot_type_dimension( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-dimension ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Allow fields to be filtered.
		$ot_recognized_dimension_fields = apply_filters(
			'ot_recognized_dimension_fields',
			array(
				'width',
				'height',
				'unit',
			),
			$field_id
		);

		// Build width dimension.
		if ( in_array( 'width', $ot_recognized_dimension_fields, true ) ) {

			$width = isset( $field_value['width'] ) ? esc_attr( $field_value['width'] ) : '';
			echo '<div class="ot-option-group ot-option-group--one-third"><span class="ot-icon-arrows-h ot-option-group--icon"></span><input type="text" name="' . esc_attr( $field_name ) . '[width]" id="' . esc_attr( $field_id ) . '-width" value="' . esc_attr( $width ) . '" class="widefat option-tree-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . esc_html__( 'width', 'sidebar-menu' ) . '" /></div>';
		}

		// Build height dimension.
		if ( in_array( 'height', $ot_recognized_dimension_fields, true ) ) {

			$height = isset( $field_value['height'] ) ? esc_attr( $field_value['height'] ) : '';
			echo '<div class="ot-option-group ot-option-group--one-third"><span class="ot-icon-arrows-v ot-option-group--icon"></span><input type="text" name="' . esc_attr( $field_name ) . '[height]" id="' . esc_attr( $field_id ) . '-height" value="' . esc_attr( $height ) . '" class="widefat option-tree-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . esc_html__( 'height', 'sidebar-menu' ) . '" /></div>';
		}

		// Build unit dropdown.
		if ( in_array( 'unit', $ot_recognized_dimension_fields, true ) ) {

			echo '<div class="ot-option-group ot-option-group--one-third ot-option-group--is-last">';

			echo '<select name="' . esc_attr( $field_name ) . '[unit]" id="' . esc_attr( $field_id ) . '-unit" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';

			echo '<option value="">' . esc_html__( 'unit', 'sidebar-menu' ) . '</option>';

			foreach ( ot_recognized_dimension_unit_types( $field_id ) as $unit ) {
				echo '<option value="' . esc_attr( $unit ) . '" ' . ( isset( $field_value['unit'] ) ? selected( $field_value['unit'], $unit, false ) : '' ) . '>' . esc_attr( $unit ) . '</option>';
			}

			echo '</select>';

			echo '</div>';
		}

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_gallery' ) ) {

	/**
	 * Gallery option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args The options arguments.
	 *
	 * @access public
	 * @since  2.2.0
	 */
	function ot_type_gallery( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-gallery ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		$field_value = trim( $field_value );

		// Saved values.
		echo '<input type="hidden" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( $field_value ) . '" class="ot-gallery-value ' . esc_attr( $field_class ) . '" />';

		// Search the string for the IDs.
		preg_match( '/ids=\'(.*?)\'/', $field_value, $matches );

		// Turn the field value into an array of IDs.
		if ( isset( $matches[1] ) ) {

			// Found the IDs in the shortcode.
			$ids = explode( ',', $matches[1] );
		} else {

			// The string is only IDs.
			$ids = ! empty( $field_value ) && '' !== $field_value ? explode( ',', $field_value ) : array();
		}

		// Has attachment IDs.
		if ( ! empty( $ids ) ) {

			echo '<ul class="ot-gallery-list">';

			foreach ( $ids as $id ) {

				if ( '' === $id ) {
					continue;
				}

				$thumbnail = wp_get_attachment_image_src( $id, 'thumbnail' );

				echo '<li><img  src="' . esc_url_raw( $thumbnail[0] ) . '" width="75" height="75" /></li>';
			}

			echo '</ul>';

			echo '
			<div class="ot-gallery-buttons">
				<a href="#" class="option-tree-ui-button button button-secondary hug-left ot-gallery-delete">' . esc_html__( 'Delete Gallery', 'sidebar-menu' ) . '</a>
				<a href="#" class="option-tree-ui-button button button-primary right hug-right ot-gallery-edit">' . esc_html__( 'Edit Gallery', 'sidebar-menu' ) . '</a>
			</div>';

		} else {

			echo '
			<div class="ot-gallery-buttons">
				<a href="#" class="option-tree-ui-button button button-primary right hug-right ot-gallery-edit">' . esc_html__( 'Create Gallery', 'sidebar-menu' ) . '</a>
			</div>';

		}

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_google_fonts' ) ) {

	/**
	 * Google Fonts option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.5.0
	 */
	function ot_type_google_fonts( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-google-font ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Allow fields to be filtered.
		$ot_recognized_google_fonts_fields = apply_filters(
			'ot_recognized_google_font_fields',
			array(
				'variants',
				'subsets',
			),
			$field_id
		);

		// Set a default to show at least one item.
		if ( ! is_array( $field_value ) || empty( $field_value ) ) {
			$field_value = array(
				array(
					'family'   => '',
					'variants' => array(),
					'subsets'  => array(),
				),
			);
		}

		foreach ( $field_value as $key => $value ) {

			echo '<div class="type-google-font-group">';

			// Build font family.
			$family = isset( $value['family'] ) ? $value['family'] : '';
			echo '<div class="option-tree-google-font-family">';
			echo '<a href="javascript:void(0);" class="js-remove-google-font option-tree-ui-button button button-secondary light" title="' . esc_html__( 'Remove Google Font', 'sidebar-menu' ) . '"><span class="icon ot-icon-minus-circle"/>' . esc_html__( 'Remove Google Font', 'sidebar-menu' ) . '</a>';
			echo '<select name="' . esc_attr( $field_name ) . '[' . esc_attr( $key ) . '][family]" id="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';
			echo '<option value="">' . esc_html__( '-- Choose One --', 'sidebar-menu' ) . '</option>';
			foreach ( ot_recognized_google_font_families( $field_id ) as $family_key => $family_value ) {
				echo '<option value="' . esc_attr( $family_key ) . '" ' . selected( $family, $family_key, false ) . '>' . esc_html( $family_value ) . '</option>';
			}
			echo '</select>';
			echo '</div>';

			// Build font variants.
			if ( in_array( 'variants', $ot_recognized_google_fonts_fields, true ) ) {
				$variants = isset( $value['variants'] ) ? $value['variants'] : array();
				echo '<div class="option-tree-google-font-variants" data-field-id-prefix="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '-" data-field-name="' . esc_attr( $field_name ) . '[' . esc_attr( $key ) . '][variants]" data-field-class="option-tree-ui-checkbox ' . esc_attr( $field_class ) . '">';
				foreach ( ot_recognized_google_font_variants( $field_id, $family ) as $variant_key => $variant ) {
					echo '<p class="checkbox-wrap">';
					echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . esc_attr( $key ) . '][variants][]" id="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '-' . esc_attr( $variant ) . '" value="' . esc_attr( $variant ) . '" ' . checked( in_array( $variant, $variants, true ), true, false ) . ' class="option-tree-ui-checkbox ' . esc_attr( $field_class ) . '" />';
					echo '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '-' . esc_attr( $variant ) . '">' . esc_html( $variant ) . '</label>';
					echo '</p>';
				}
				echo '</div>';
			}

			// Build font subsets.
			if ( in_array( 'subsets', $ot_recognized_google_fonts_fields, true ) ) {
				$subsets = isset( $value['subsets'] ) ? $value['subsets'] : array();
				echo '<div class="option-tree-google-font-subsets" data-field-id-prefix="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '-" data-field-name="' . esc_attr( $field_name ) . '[' . esc_attr( $key ) . '][subsets]" data-field-class="option-tree-ui-checkbox ' . esc_attr( $field_class ) . '">';
				foreach ( ot_recognized_google_font_subsets( $field_id, $family ) as $subset_key => $subset ) {
					echo '<p class="checkbox-wrap">';
					echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . esc_attr( $key ) . '][subsets][]" id="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '-' . esc_attr( $subset ) . '" value="' . esc_attr( $subset ) . '" ' . checked( in_array( $subset, $subsets, true ), true, false ) . ' class="option-tree-ui-checkbox ' . esc_attr( $field_class ) . '" />';
					echo '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '-' . esc_attr( $subset ) . '">' . esc_html( $subset ) . '</label>';
					echo '</p>';
				}
				echo '</div>';
			}

			echo '</div>';
		}

		echo '<div class="type-google-font-group-clone">';

		/* build font family */
		echo '<div class="option-tree-google-font-family">';
		echo '<a href="javascript:void(0);" class="js-remove-google-font option-tree-ui-button button button-secondary light" title="' . esc_html__( 'Remove Google Font', 'sidebar-menu' ) . '"><span class="icon ot-icon-minus-circle"/>' . esc_html__( 'Remove Google Font', 'sidebar-menu' ) . '</a>';
		echo '<select name="' . esc_attr( $field_name ) . '[%key%][family]" id="' . esc_attr( $field_id ) . '-%key%" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';
		echo '<option value="">' . esc_html__( '-- Choose One --', 'sidebar-menu' ) . '</option>';

		foreach ( ot_recognized_google_font_families( $field_id ) as $family_key => $family_value ) {
			echo '<option value="' . esc_attr( $family_key ) . '">' . esc_html( $family_value ) . '</option>';
		}

		echo '</select>';
		echo '</div>';

		// Build font variants.
		if ( in_array( 'variants', $ot_recognized_google_fonts_fields, true ) ) {
			echo '<div class="option-tree-google-font-variants" data-field-id-prefix="' . esc_attr( $field_id ) . '-%key%-" data-field-name="' . esc_attr( $field_name ) . '[%key%][variants]" data-field-class="option-tree-ui-checkbox ' . esc_attr( $field_class ) . '">';
			echo '</div>';
		}

		// Build font subsets.
		if ( in_array( 'subsets', $ot_recognized_google_fonts_fields, true ) ) {
			echo '<div class="option-tree-google-font-subsets" data-field-id-prefix="' . esc_attr( $field_id ) . '-%key%-" data-field-name="' . esc_attr( $field_name ) . '[%key%][subsets]" data-field-class="option-tree-ui-checkbox ' . esc_attr( $field_class ) . '">';
			echo '</div>';
		}

		echo '</div>';

		echo '<a href="javascript:void(0);" class="js-add-google-font option-tree-ui-button button button-primary right hug-right" title="' . esc_html__( 'Add Google Font', 'sidebar-menu' ) . '">' . esc_html__( 'Add Google Font', 'sidebar-menu' ) . '</a>';

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_javascript' ) ) {

	/**
	 * JavaScript option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.5.0
	 */
	function ot_type_javascript( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-javascript simple ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Build textarea for CSS.
		echo '<textarea class="hidden" id="textarea_' . esc_attr( $field_id ) . '" name="' . esc_attr( $field_name ) . '">' . esc_textarea( $field_value ) . '</textarea>';

		// Build pre to convert it into ace editor later.
		echo '<pre class="ot-javascript-editor ' . esc_attr( $field_class ) . '" id="' . esc_attr( $field_id ) . '">' . esc_textarea( $field_value ) . '</pre>';

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_link_color' ) ) {

	/**
	 * Link Color option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args The options arguments.
	 *
	 * @access public
	 * @since  2.5.0
	 */
	function ot_type_link_color( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-link-color ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Allow fields to be filtered.
		$ot_recognized_link_color_fields = apply_filters(
			'ot_recognized_link_color_fields',
			array(
				'link'    => _x( 'Standard', 'color picker', 'sidebar-menu' ),
				'hover'   => _x( 'Hover', 'color picker', 'sidebar-menu' ),
				'active'  => _x( 'Active', 'color picker', 'sidebar-menu' ),
				'visited' => _x( 'Visited', 'color picker', 'sidebar-menu' ),
				'focus'   => _x( 'Focus', 'color picker', 'sidebar-menu' ),
			),
			$field_id
		);

		// Build link color fields.
		foreach ( $ot_recognized_link_color_fields as $type => $label ) {

			if ( array_key_exists( $type, $ot_recognized_link_color_fields ) ) {

				echo '<div class="option-tree-ui-colorpicker-input-wrap">';

				echo '<label for="' . esc_attr( $field_id ) . '-picker-' . esc_attr( $type ) . '" class="option-tree-ui-colorpicker-label">' . esc_attr( $label ) . '</label>';

				// Colorpicker JS.
				echo '<script>jQuery(document).ready(function($) { OT_UI.bind_colorpicker("' . esc_attr( $field_id ) . '-picker-' . esc_attr( $type ) . '"); });</script>';

				// Set color.
				$color = isset( $field_value[ $type ] ) ? esc_attr( $field_value[ $type ] ) : '';

				// Set default color.
				$std = isset( $field_std[ $type ] ) ? 'data-default-color="' . $field_std[ $type ] . '"' : '';

				// Input.
				echo '<input type="text" name="' . esc_attr( $field_name ) . '[' . esc_attr( $type ) . ']" id="' . esc_attr( $field_id ) . '-picker-' . esc_attr( $type ) . '" value="' . esc_attr( $color ) . '" class="hide-color-picker ' . esc_attr( $field_class ) . '" ' . esc_attr( $std ) . ' />';

				echo '</div>';

			}
		}

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_list_item' ) ) {

	/**
	 * List Item option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_type_list_item( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Default.
		$sortable = true;

		// Check if the list can be sorted.
		if ( ! empty( $field_class ) ) {
			$classes = explode( ' ', $field_class );
			if ( in_array( 'not-sortable', $classes, true ) ) {
				$sortable = false;
				str_replace( 'not-sortable', '', $field_class );
			}
		}

		// Format setting outer wrapper.
		echo '<div class="format-setting type-list-item ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Pass the settings array arround.
		echo '<input type="hidden" name="' . esc_attr( $field_id ) . '_settings_array" id="' . esc_attr( $field_id ) . '_settings_array" value="' . esc_attr( ot_encode( $field_settings ) ) . '" />';

		/**
		 * Settings pages have array wrappers like 'option_tree'.
		 * So we need that value to create a proper array to save to.
		 * This is only for NON metabox settings.
		 */
		if ( ! isset( $get_option ) ) {
			$get_option = '';
		}

		// Build list items.
		echo '<ul class="option-tree-setting-wrap' . ( $sortable ? ' option-tree-sortable' : '' ) . '" data-name="' . esc_attr( $field_id ) . '" data-id="' . esc_attr( $post_id ) . '" data-get-option="' . esc_attr( $get_option ) . '" data-type="' . esc_attr( $type ) . '">';

		if ( is_array( $field_value ) && ! empty( $field_value ) ) {

			foreach ( $field_value as $key => $list_item ) {

				echo '<li class="ui-state-default list-list-item">';
				ot_list_item_view( $field_id, $key, $list_item, $post_id, $get_option, $field_settings, $type );
				echo '</li>';
			}
		}

		echo '</ul>';

		// Button.
		echo '<a href="javascript:void(0);" class="option-tree-list-item-add option-tree-ui-button button button-primary right hug-right" title="' . esc_html__( 'Add New', 'sidebar-menu' ) . '">' . esc_html__( 'Add New', 'sidebar-menu' ) . '</a>';

		// Description.
		$list_desc = $sortable ? __( 'You can re-order with drag & drop, the order will update after saving.', 'sidebar-menu' ) : '';
		echo '<div class="list-item-description">' . esc_html( apply_filters( 'ot_list_item_description', $list_desc, $field_id ) ) . '</div>';

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_measurement' ) ) {

	/**
	 * Measurement option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_type_measurement( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-measurement ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		echo '<div class="option-tree-ui-measurement-input-wrap">';

		echo '<input type="text" name="' . esc_attr( $field_name ) . '[0]" id="' . esc_attr( $field_id ) . '-0" value="' . esc_attr( ( isset( $field_value[0] ) ? $field_value[0] : '' ) ) . '" class="widefat option-tree-ui-input ' . esc_attr( $field_class ) . '" />';

		echo '</div>';

		// Build measurement.
		echo '<select name="' . esc_attr( $field_name ) . '[1]" id="' . esc_attr( $field_id ) . '-1" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';

		echo '<option value="">' . esc_html__( 'unit', 'sidebar-menu' ) . '</option>';

		foreach ( ot_measurement_unit_types( $field_id ) as $unit ) {
			echo '<option value="' . esc_attr( $unit ) . '" ' . ( isset( $field_value[1] ) ? selected( $field_value[1], $unit, false ) : '' ) . '>' . esc_attr( $unit ) . '</option>';
		}

		echo '</select>';

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_numeric_slider' ) ) {

	/**
	 * Numeric Slider option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.1
	 */
	function ot_type_numeric_slider( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		$_options = explode( ',', $field_min_max_step );
		$min      = isset( $_options[0] ) ? $_options[0] : 0;
		$max      = isset( $_options[1] ) ? $_options[1] : 100;
		$step     = isset( $_options[2] ) ? $_options[2] : 1;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-numeric-slider ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		echo '<div class="ot-numeric-slider-wrap">';

		echo '<input type="hidden" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="ot-numeric-slider-hidden-input" value="' . esc_attr( $field_value ) . '" data-min="' . esc_attr( $min ) . '" data-max="' . esc_attr( $max ) . '" data-step="' . esc_attr( $step ) . '">';

		echo '<input type="text" class="ot-numeric-slider-helper-input widefat option-tree-ui-input ' . esc_attr( $field_class ) . '" value="' . esc_attr( $field_value ) . '" readonly>';

		echo '<div id="ot_numeric_slider_' . esc_attr( $field_id ) . '" class="ot-numeric-slider"></div>';

		echo '</div>';

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_on_off' ) ) {

	/**
	 * On/Off option type
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args The options arguments.
	 *
	 * @access public
	 * @since  2.2.0
	 */
	function ot_type_on_off( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-radio ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Force only two choices, and allowing filtering on the choices value & label.
		$field_choices = array(
			array(
				/**
				 * Filter the value of the On button.
				 *
				 * @since 2.5.0
				 *
				 * @param string $value The On button value. Default 'on'.
				 * @param string $field_id The field ID.
				 * @param string $filter_id For filtering both on/off value with one function.
				 */
				'value' => apply_filters( 'ot_on_off_switch_on_value', 'on', $field_id, 'on' ),
				/**
				 * Filter the label of the On button.
				 *
				 * @since 2.5.0
				 *
				 * @param string $label The On button label. Default 'On'.
				 * @param string $field_id The field ID.
				 * @param string $filter_id For filtering both on/off label with one function.
				 */
				'label' => apply_filters( 'ot_on_off_switch_on_label', esc_html__( 'On', 'sidebar-menu' ), $field_id, 'on' ),
			),
			array(
				/**
				 * Filter the value of the Off button.
				 *
				 * @since 2.5.0
				 *
				 * @param string $value The Off button value. Default 'off'.
				 * @param string $field_id The field ID.
				 * @param string $filter_id For filtering both on/off value with one function.
				 */
				'value' => apply_filters( 'ot_on_off_switch_off_value', 'off', $field_id, 'off' ),
				/**
				 * Filter the label of the Off button.
				 *
				 * @since 2.5.0
				 *
				 * @param string $label The Off button label. Default 'Off'.
				 * @param string $field_id The field ID.
				 * @param string $filter_id For filtering both on/off label with one function.
				 */
				'label' => apply_filters( 'ot_on_off_switch_off_label', esc_html__( 'Off', 'sidebar-menu' ), $field_id, 'off' ),
			),
		);

		/**
		 * Filter the width of the On/Off switch.
		 *
		 * @since 2.5.0
		 *
		 * @param string $switch_width The switch width. Default '100px'.
		 * @param string $field_id     The field ID.
		 */
		$switch_width = apply_filters( 'ot_on_off_switch_width', '100px', $field_id );

		echo '<div class="on-off-switch"' . ( '100px' !== $switch_width ? sprintf( ' style="width:%s"', esc_attr( $switch_width ) ) : '' ) . '>'; // phpcs:ignore

		// Build radio.
		foreach ( (array) $field_choices as $key => $choice ) {
			echo '
            <input type="radio" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '" value="' . esc_attr( $choice['value'] ) . '" ' . checked( $field_value, $choice['value'], false ) . ' class="radio option-tree-ui-radio ' . esc_attr( $field_class ) . '" />
            <label for="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '" onclick="">' . esc_attr( $choice['label'] ) . '</label>';
		}

		echo '<span class="slide-button"></span>';

		echo '</div>';

		echo '</div>';

		echo '</div>';

	}
}

if ( ! function_exists( 'ot_type_page_checkbox' ) ) {

	/**
	 * Page Checkbox option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_type_page_checkbox( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-page-checkbox type-checkbox ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Query pages array.
		$my_posts = get_posts(
			apply_filters(
				'ot_type_page_checkbox_query',
				array(
					'post_type'      => array( 'page' ),
					'posts_per_page' => -1,
					'orderby'        => 'title',
					'order'          => 'ASC',
					'post_status'    => 'any',
				),
				$field_id
			)
		);

		// Has pages.
		if ( is_array( $my_posts ) && ! empty( $my_posts ) ) {
			foreach ( $my_posts as $my_post ) {
				$post_title = ! empty( $my_post->post_title ) ? $my_post->post_title : 'Untitled';
				echo '<p>';
				echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . esc_attr( $my_post->ID ) . ']" id="' . esc_attr( $field_id ) . '-' . esc_attr( $my_post->ID ) . '" value="' . esc_attr( $my_post->ID ) . '" ' . ( isset( $field_value[ $my_post->ID ] ) ? checked( $field_value[ $my_post->ID ], $my_post->ID, false ) : '' ) . ' class="option-tree-ui-checkbox ' . esc_attr( $field_class ) . '" />';
				echo '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $my_post->ID ) . '">' . esc_html( $post_title ) . '</label>';
				echo '</p>';
			}
		} else {
			echo '<p>' . esc_html__( 'No Pages Found', 'sidebar-menu' ) . '</p>';
		}

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_page_select' ) ) {

	/**
	 * Page Select option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_type_page_select( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-page-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Build page select.
		echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';

		// Query pages array.
		$my_posts = get_posts(
			apply_filters(
				'ot_type_page_select_query',
				array(
					'post_type'      => array( 'page' ),
					'posts_per_page' => -1,
					'orderby'        => 'title',
					'order'          => 'ASC',
					'post_status'    => 'any',
				),
				$field_id
			)
		);

		// Has pages.
		if ( is_array( $my_posts ) && ! empty( $my_posts ) ) {
			echo '<option value="">-- ' . esc_html__( 'Choose One', 'sidebar-menu' ) . ' --</option>';
			foreach ( $my_posts as $my_post ) {
				$post_title = ! empty( $my_post->post_title ) ? $my_post->post_title : 'Untitled';
				echo '<option value="' . esc_attr( $my_post->ID ) . '" ' . selected( $field_value, $my_post->ID, false ) . '>' . esc_html( $post_title ) . '</option>';
			}
		} else {
			echo '<option value="">' . esc_html__( 'No Pages Found', 'sidebar-menu' ) . '</option>';
		}

		echo '</select>';

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_post_checkbox' ) ) {

	/**
	 * Post Checkbox option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_type_post_checkbox( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-post-checkbox type-checkbox ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Query posts array.
		$my_posts = get_posts(
			apply_filters(
				'ot_type_post_checkbox_query',
				array(
					'post_type'      => array( 'post' ),
					'posts_per_page' => -1,
					'orderby'        => 'title',
					'order'          => 'ASC',
					'post_status'    => 'any',
				),
				$field_id
			)
		);

		// Has posts.
		if ( is_array( $my_posts ) && ! empty( $my_posts ) ) {
			foreach ( $my_posts as $my_post ) {
				$post_title = ! empty( $my_post->post_title ) ? $my_post->post_title : 'Untitled';
				echo '<p>';
				echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . esc_attr( $my_post->ID ) . ']" id="' . esc_attr( $field_id ) . '-' . esc_attr( $my_post->ID ) . '" value="' . esc_attr( $my_post->ID ) . '" ' . ( isset( $field_value[ $my_post->ID ] ) ? checked( $field_value[ $my_post->ID ], $my_post->ID, false ) : '' ) . ' class="option-tree-ui-checkbox ' . esc_attr( $field_class ) . '" />';
				echo '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $my_post->ID ) . '">' . esc_html( $post_title ) . '</label>';
				echo '</p>';
			}
		} else {
			echo '<p>' . esc_html__( 'No Posts Found', 'sidebar-menu' ) . '</p>';
		}

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_post_select' ) ) {

	/**
	 * Post Select option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_type_post_select( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-post-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		/* description */
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Build page select.
		echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';

		// Query posts array.
		$my_posts = get_posts(
			apply_filters(
				'ot_type_post_select_query',
				array(
					'post_type'      => array( 'post' ),
					'posts_per_page' => -1,
					'orderby'        => 'title',
					'order'          => 'ASC',
					'post_status'    => 'any',
				),
				$field_id
			)
		);

		// Has posts.
		if ( is_array( $my_posts ) && ! empty( $my_posts ) ) {
			echo '<option value="">-- ' . esc_html__( 'Choose One', 'sidebar-menu' ) . ' --</option>';
			foreach ( $my_posts as $my_post ) {
				$post_title = ! empty( $my_post->post_title ) ? $my_post->post_title : 'Untitled';
				echo '<option value="' . esc_attr( $my_post->ID ) . '" ' . selected( $field_value, $my_post->ID, false ) . '>' . esc_html( $post_title ) . '</option>';
			}
		} else {
			echo '<option value="">' . esc_html__( 'No Posts Found', 'sidebar-menu' ) . '</option>';
		}

		echo '</select>';

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_radio' ) ) {

	/**
	 * Radio option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_type_radio( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-radio ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Build radio.
		foreach ( (array) $field_choices as $key => $choice ) {
			echo '<p><input type="radio" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '" value="' . esc_attr( $choice['value'] ) . '" ' . checked( $field_value, $choice['value'], false ) . ' class="radio option-tree-ui-radio ' . esc_attr( $field_class ) . '" /><label for="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '">' . esc_attr( $choice['label'] ) . '</label></p>';
		}

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_radio_image' ) ) {

	/**
	 * Radio Images option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_type_radio_image( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-radio-image ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		/**
		 * Load the default filterable images if nothing
		 * has been set in the choices array.
		 */
		if ( empty( $field_choices ) ) {
			$field_choices = ot_radio_images( $field_id );
		}

		// Build radio image.
		foreach ( (array) $field_choices as $key => $choice ) {

			$src = str_replace( 'OT_URL', OT_URL, $choice['src'] );
			$src = str_replace( 'OT_THEME_URL', OT_THEME_URL, $src );

			// Make radio image source filterable.
			$src = apply_filters( 'ot_type_radio_image_src', $src, $field_id );

			/**
			 * Filter the image attributes.
			 *
			 * @since 2.5.3
			 *
			 * @param string $attributes The image attributes.
			 * @param string $field_id The field ID.
			 * @param array $choice The choice.
			 */
			$attributes = apply_filters( 'ot_type_radio_image_attributes', '', $field_id, $choice );

			echo '<div class="option-tree-ui-radio-images">';
			echo '<p style="display:none"><input type="radio" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '" value="' . esc_attr( $choice['value'] ) . '" ' . checked( $field_value, $choice['value'], false ) . ' class="option-tree-ui-radio option-tree-ui-images" /><label for="' . esc_attr( $field_id ) . '-' . esc_attr( $key ) . '">' . esc_attr( $choice['label'] ) . '</label></p>';
			echo '<img ' . sanitize_text_field( $attributes ) . ' src="' . esc_url( $src ) . '" alt="' . esc_attr( $choice['label'] ) . '" title="' . esc_attr( $choice['label'] ) . '" class="option-tree-ui-radio-image ' . esc_attr( $field_class ) . ( $field_value === $choice['value'] ? ' option-tree-ui-radio-image-selected' : '' ) . '" />'; // phpcs:ignore
			echo '</div>';
		}

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_select' ) ) {

	/**
	 * Select option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_type_select( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Filter choices array.
		$field_choices = apply_filters( 'ot_type_select_choices', $field_choices, $field_id );

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Build select.
		echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';
		foreach ( (array) $field_choices as $choice ) {
			if ( isset( $choice['value'] ) && isset( $choice['label'] ) ) {
				echo '<option '.(isset($choice['disable']) && $choice['disable'] == 'true'?'disabled="disabled"':"").' value="' . esc_attr( $choice['value'] ) . '"' . selected( $field_value, $choice['value'], false ) . '>' . esc_attr( $choice['label'] ) . '</option>';
			}
		}

		echo '</select>';

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_sidebar_select' ) ) {

	/**
	 * Sidebar Select option type.
	 *
	 * This option type makes it possible for users to select a WordPress registered sidebar
	 * to use on a specific area. By using the two provided filters, 'ot_recognized_sidebars',
	 * and 'ot_recognized_sidebars_{$field_id}' we can be selective about which sidebars are
	 * available on a specific content area.
	 *
	 * For example, if we create a WordPress theme that provides the ability to change the
	 * Blog Sidebar and we don't want to have the footer sidebars available on this area,
	 * we can unset those sidebars either manually or by using a regular expression if we
	 * have a common name like footer-sidebar-$i.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.1
	 */
	function ot_type_sidebar_select( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-sidebar-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Build page select.
		echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';

		// Get the registered sidebars.
		global $wp_registered_sidebars;

		$sidebars = array();
		foreach ( $wp_registered_sidebars as $id => $sidebar ) {
			$sidebars[ $id ] = $sidebar['name'];
		}

		// Filters to restrict which sidebars are allowed to be selected, for example we can restrict footer sidebars to be selectable on a blog page.
		$sidebars = apply_filters( 'ot_recognized_sidebars', $sidebars );
		$sidebars = apply_filters( 'ot_recognized_sidebars_' . $field_id, $sidebars );

		// Has sidebars.
		if ( count( $sidebars ) ) {
			echo '<option value="">-- ' . esc_html__( 'Choose Sidebar', 'sidebar-menu' ) . ' --</option>';
			foreach ( $sidebars as $id => $sidebar ) {
				echo '<option value="' . esc_attr( $id ) . '" ' . selected( $field_value, $id, false ) . '>' . esc_attr( $sidebar ) . '</option>';
			}
		} else {
			echo '<option value="">' . esc_html__( 'No Sidebars', 'sidebar-menu' ) . '</option>';
		}

		echo '</select>';

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_slider' ) ) {

	/**
	 * List Item option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_type_slider( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-slider ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Pass the settings array around.
		echo '<input type="hidden" name="' . esc_attr( $field_id ) . '_settings_array" id="' . esc_attr( $field_id ) . '_settings_array" value="' . esc_attr( ot_encode( $field_settings ) ) . '" />';

		/**
		 * Settings pages have array wrappers like 'option_tree'.
		 * So we need that value to create a proper array to save to.
		 * This is only for NON metabox settings.
		 */
		if ( ! isset( $get_option ) ) {
			$get_option = '';
		}

		// Build list items.
		echo '<ul class="option-tree-setting-wrap option-tree-sortable" data-name="' . esc_attr( $field_id ) . '" data-id="' . esc_attr( $post_id ) . '" data-get-option="' . esc_attr( $get_option ) . '" data-type="' . esc_attr( $type ) . '">';

		if ( is_array( $field_value ) && ! empty( $field_value ) ) {

			foreach ( $field_value as $key => $list_item ) {

				echo '<li class="ui-state-default list-list-item">';
				ot_list_item_view( $field_id, $key, $list_item, $post_id, $get_option, $field_settings, $type );
				echo '</li>';
			}
		}

		echo '</ul>';

		// Button.
		echo '<a href="javascript:void(0);" class="option-tree-list-item-add option-tree-ui-button button button-primary right hug-right" title="' . esc_html__( 'Add New', 'sidebar-menu' ) . '">' . esc_html__( 'Add New', 'sidebar-menu' ) . '</a>'; // phpcs:ignore

		// Description.
		echo '<div class="list-item-description">' . esc_html__( 'You can re-order with drag & drop, the order will update after saving.', 'sidebar-menu' ) . '</div>';

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_social_links' ) ) {

	/**
	 * Social Links option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.4.0
	 */
	function ot_type_social_links( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Load the default social links.
		if ( empty( $field_value ) && apply_filters( 'ot_type_social_links_load_defaults', true, $field_id ) ) {

			$field_value = apply_filters(
				'ot_type_social_links_defaults',
				array(
					array(
						'name'  => __( 'Facebook', 'sidebar-menu' ),
						'title' => '',
						'href'  => '',
					),
					array(
						'name'  => __( 'Twitter', 'sidebar-menu' ),
						'title' => '',
						'href'  => '',
					),
					array(
						'name'  => __( 'Google+', 'sidebar-menu' ),
						'title' => '',
						'href'  => '',
					),
					array(
						'name'  => __( 'LinkedIn', 'sidebar-menu' ),
						'title' => '',
						'href'  => '',
					),
					array(
						'name'  => __( 'Pinterest', 'sidebar-menu' ),
						'title' => '',
						'href'  => '',
					),
					array(
						'name'  => __( 'Youtube', 'sidebar-menu' ),
						'title' => '',
						'href'  => '',
					),
					array(
						'name'  => __( 'Dribbble', 'sidebar-menu' ),
						'title' => '',
						'href'  => '',
					),
					array(
						'name'  => __( 'Github', 'sidebar-menu' ),
						'title' => '',
						'href'  => '',
					),
					array(
						'name'  => __( 'Forrst', 'sidebar-menu' ),
						'title' => '',
						'href'  => '',
					),
					array(
						'name'  => __( 'Digg', 'sidebar-menu' ),
						'title' => '',
						'href'  => '',
					),
					array(
						'name'  => __( 'Delicious', 'sidebar-menu' ),
						'title' => '',
						'href'  => '',
					),
					array(
						'name'  => __( 'Tumblr', 'sidebar-menu' ),
						'title' => '',
						'href'  => '',
					),
					array(
						'name'  => __( 'Skype', 'sidebar-menu' ),
						'title' => '',
						'href'  => '',
					),
					array(
						'name'  => __( 'SoundCloud', 'sidebar-menu' ),
						'title' => '',
						'href'  => '',
					),
					array(
						'name'  => __( 'Vimeo', 'sidebar-menu' ),
						'title' => '',
						'href'  => '',
					),
					array(
						'name'  => __( 'Flickr', 'sidebar-menu' ),
						'title' => '',
						'href'  => '',
					),
					array(
						'name'  => __( 'VK.com', 'sidebar-menu' ),
						'title' => '',
						'href'  => '',
					),
				),
				$field_id
			);

		}

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-social-list-item ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Pass the settings array around.
		echo '<input type="hidden" name="' . esc_attr( $field_id ) . '_settings_array" id="' . esc_attr( $field_id ) . '_settings_array" value="' . esc_attr( ot_encode( $field_settings ) ) . '" />';

		/**
		 * Settings pages have array wrappers like 'option_tree'.
		 * So we need that value to create a proper array to save to.
		 * This is only for NON metabox settings.
		 */
		if ( ! isset( $get_option ) ) {
			$get_option = '';
		}

		// Build list items.
		echo '<ul class="option-tree-setting-wrap option-tree-sortable" data-name="' . esc_attr( $field_id ) . '" data-id="' . esc_attr( $post_id ) . '" data-get-option="' . esc_attr( $get_option ) . '" data-type="' . esc_attr( $type ) . '">';

		if ( is_array( $field_value ) && ! empty( $field_value ) ) {

			foreach ( $field_value as $key => $link ) {

				echo '<li class="ui-state-default list-list-item">';
				ot_social_links_view( $field_id, $key, $link, $post_id, $get_option, $field_settings );
				echo '</li>';
			}
		}

		echo '</ul>';

		// Button.
		echo '<a href="javascript:void(0);" class="option-tree-social-links-add option-tree-ui-button button button-primary right hug-right" title="' . esc_html__( 'Add New', 'sidebar-menu' ) . '">' . esc_html__( 'Add New', 'sidebar-menu' ) . '</a>'; // phpcs:ignore

		// Description.
		echo '<div class="list-item-description">' . esc_html( apply_filters( 'ot_social_links_description', __( 'You can re-order with drag & drop, the order will update after saving.', 'sidebar-menu' ), $field_id ) ) . '</div>';

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_spacing' ) ) {

	/**
	 * Spacing Option Type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.5.0
	 */
	function ot_type_spacing( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-spacing ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Allow fields to be filtered.
		$ot_recognized_spacing_fields = apply_filters(
			'ot_recognized_spacing_fields',
			array(
				'top',
				'right',
				'bottom',
				'left',
				'unit',
			),
			$field_id
		);

		// Build top spacing.
		if ( in_array( 'top', $ot_recognized_spacing_fields, true ) ) {

			$top = isset( $field_value['top'] ) ? $field_value['top'] : '';

			echo '<div class="ot-option-group"><span class="ot-icon-arrow-up ot-option-group--icon"></span><input type="text" name="' . esc_attr( $field_name ) . '[top]" id="' . esc_attr( $field_id ) . '-top" value="' . esc_attr( $top ) . '" class="widefat option-tree-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . esc_html__( 'top', 'sidebar-menu' ) . '" /></div>';
		}

		// Build right spacing.
		if ( in_array( 'right', $ot_recognized_spacing_fields, true ) ) {

			$right = isset( $field_value['right'] ) ? $field_value['right'] : '';

			echo '<div class="ot-option-group"><span class="ot-icon-arrow-right ot-option-group--icon"></span></span><input type="text" name="' . esc_attr( $field_name ) . '[right]" id="' . esc_attr( $field_id ) . '-right" value="' . esc_attr( $right ) . '" class="widefat option-tree-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . esc_html__( 'right', 'sidebar-menu' ) . '" /></div>';
		}

		// Build bottom spacing.
		if ( in_array( 'bottom', $ot_recognized_spacing_fields, true ) ) {

			$bottom = isset( $field_value['bottom'] ) ? $field_value['bottom'] : '';

			echo '<div class="ot-option-group"><span class="ot-icon-arrow-down ot-option-group--icon"></span><input type="text" name="' . esc_attr( $field_name ) . '[bottom]" id="' . esc_attr( $field_id ) . '-bottom" value="' . esc_attr( $bottom ) . '" class="widefat option-tree-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . esc_html__( 'bottom', 'sidebar-menu' ) . '" /></div>';
		}

		// Build left spacing.
		if ( in_array( 'left', $ot_recognized_spacing_fields, true ) ) {

			$left = isset( $field_value['left'] ) ? $field_value['left'] : '';

			echo '<div class="ot-option-group"><span class="ot-icon-arrow-left ot-option-group--icon"></span><input type="text" name="' . esc_attr( $field_name ) . '[left]" id="' . esc_attr( $field_id ) . '-left" value="' . esc_attr( $left ) . '" class="widefat option-tree-ui-input ' . esc_attr( $field_class ) . '" placeholder="' . esc_html__( 'left', 'sidebar-menu' ) . '" /></div>';
		}

		// Build unit dropdown.
		if ( in_array( 'unit', $ot_recognized_spacing_fields, true ) ) {

			echo '<div class="ot-option-group ot-option-group--is-last">';

			echo '<select name="' . esc_attr( $field_name ) . '[unit]" id="' . esc_attr( $field_id ) . '-unit" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';

			echo '<option value="">' . esc_html__( 'unit', 'sidebar-menu' ) . '</option>';

			foreach ( ot_recognized_spacing_unit_types( $field_id ) as $unit ) {
				echo '<option value="' . esc_attr( $unit ) . '"' . ( isset( $field_value['unit'] ) ? selected( $field_value['unit'], $unit, false ) : '' ) . '>' . esc_attr( $unit ) . '</option>';
			}

			echo '</select>';

			echo '</div>';
		}

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_tab' ) ) {

	/**
	 * Tab option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @access public
	 * @since  2.3.0
	 */
	function ot_type_tab() {
		echo '<div class="format-setting type-tab"><br /></div>';
	}
}

if ( ! function_exists( 'ot_type_tag_checkbox' ) ) {

	/**
	 * Tag Checkbox option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_type_tag_checkbox( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-tag-checkbox type-checkbox ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Get tags.
		$tags = get_tags( array( 'hide_empty' => false ) );

		// Has tags.
		if ( $tags ) {
			foreach ( $tags as $tag ) {
				echo '<p>';
				echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . esc_attr( $tag->term_id ) . ']" id="' . esc_attr( $field_id ) . '-' . esc_attr( $tag->term_id ) . '" value="' . esc_attr( $tag->term_id ) . '" ' . ( isset( $field_value[ $tag->term_id ] ) ? checked( $field_value[ $tag->term_id ], $tag->term_id, false ) : '' ) . ' class="option-tree-ui-checkbox ' . esc_attr( $field_class ) . '" />';
				echo '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $tag->term_id ) . '">' . esc_attr( $tag->name ) . '</label>';
				echo '</p>';
			}
		} else {
			echo '<p>' . esc_html__( 'No Tags Found', 'sidebar-menu' ) . '</p>';
		}

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_tag_select' ) ) {

	/**
	 * Tag Select option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_type_tag_select( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-tag-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Build tag select.
		echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';

		// Get tags.
		$tags = get_tags( array( 'hide_empty' => false ) );

		// Has tags.
		if ( $tags ) {
			echo '<option value="">-- ' . esc_html__( 'Choose One', 'sidebar-menu' ) . ' --</option>';
			foreach ( $tags as $tag ) {
				echo '<option value="' . esc_attr( $tag->term_id ) . '"' . selected( $field_value, $tag->term_id, false ) . '>' . esc_attr( $tag->name ) . '</option>';
			}
		} else {
			echo '<option value="">' . esc_html__( 'No Tags Found', 'sidebar-menu' ) . '</option>';
		}

		echo '</select>';

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_taxonomy_checkbox' ) ) {

	/**
	 * Taxonomy Checkbox option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_type_taxonomy_checkbox( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-taxonomy-checkbox type-checkbox ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Setup the taxonomy.
		$taxonomy = isset( $field_taxonomy ) ? explode( ',', $field_taxonomy ) : array( 'category' );

		// Get taxonomies.
		$taxonomies = get_categories(
			apply_filters(
				'ot_type_taxonomy_checkbox_query',
				array(
					'hide_empty' => false,
					'taxonomy'   => $taxonomy,
				),
				$field_id
			)
		);

		// Has tags.
		if ( $taxonomies ) {
			foreach ( $taxonomies as $taxonomy ) {
				echo '<p>';
				echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . esc_attr( $taxonomy->term_id ) . ']" id="' . esc_attr( $field_id ) . '-' . esc_attr( $taxonomy->term_id ) . '" value="' . esc_attr( $taxonomy->term_id ) . '" ' . ( isset( $field_value[ $taxonomy->term_id ] ) ? checked( $field_value[ $taxonomy->term_id ], $taxonomy->term_id, false ) : '' ) . ' class="option-tree-ui-checkbox ' . esc_attr( $field_class ) . '" />';
				echo '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $taxonomy->term_id ) . '">' . esc_attr( $taxonomy->name ) . '</label>';
				echo '</p>';
			}
		} else {
			echo '<p>' . esc_html__( 'No Taxonomies Found', 'sidebar-menu' ) . '</p>';
		}

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_taxonomy_select' ) ) {

	/**
	 * Taxonomy Select option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_type_taxonomy_select( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-tag-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Build tag select.
		echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';

		// Setup the taxonomy.
		$taxonomy = isset( $field_taxonomy ) ? explode( ',', $field_taxonomy ) : array( 'category' );

		// Get taxonomies.
		$taxonomies = get_categories(
			apply_filters(
				'ot_type_taxonomy_select_query',
				array(
					'hide_empty' => false,
					'taxonomy'   => $taxonomy,
				),
				$field_id
			)
		);

		// Has tags.
		if ( $taxonomies ) {
			echo '<option value="">-- ' . esc_html__( 'Choose One', 'sidebar-menu' ) . ' --</option>';
			foreach ( $taxonomies as $taxonomy ) {
				echo '<option value="' . esc_attr( $taxonomy->term_id ) . '"' . selected( $field_value, $taxonomy->term_id, false ) . '>' . esc_attr( $taxonomy->name ) . '</option>';
			}
		} else {
			echo '<option value="">' . esc_html__( 'No Taxonomies Found', 'sidebar-menu' ) . '</option>';
		}

		echo '</select>';

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_text' ) ) {

	/**
	 * Text option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_type_text( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-text ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Build text input.
		echo '<input type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( $field_value ) . '" class="widefat option-tree-ui-input ' . esc_attr( $field_class ) . '" />';

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_textarea' ) ) {

	/**
	 * Textarea option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_type_textarea( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-textarea ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . ' fill-area">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Build textarea.
		wp_editor(
			$field_value,
			esc_attr( $field_id ),
			array(
				'editor_class'  => esc_attr( $field_class ),
				'wpautop'       => apply_filters( 'ot_wpautop', false, $field_id ),
				'media_buttons' => apply_filters( 'ot_media_buttons', true, $field_id ),
				'textarea_name' => esc_attr( $field_name ),
				'textarea_rows' => esc_attr( $field_rows ),
				'tinymce'       => apply_filters( 'ot_tinymce', true, $field_id ),
				'quicktags'     => apply_filters( 'ot_quicktags', array( 'buttons' => 'strong,em,link,block,del,ins,img,ul,ol,li,code,spell,close' ), $field_id ),
			)
		);

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_textarea_simple' ) ) {

	/**
	 * Textarea Simple option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_type_textarea_simple( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-textarea simple ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Filter to allow wpautop.
		$wpautop = apply_filters( 'ot_wpautop', false, $field_id );

		// Wpautop $field_value.
		if ( true === $wpautop ) {
			$field_value = wpautop( $field_value );
		}

		// Build textarea simple.
		echo '<textarea class="textarea ' . esc_attr( $field_class ) . '" rows="' . esc_attr( $field_rows ) . '" cols="40" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '">' . esc_textarea( $field_value ) . '</textarea>';

		echo '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_textblock' ) ) {

	/**
	 * Textblock option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_type_textblock( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Format setting outer wrapper.
		echo '<div class="format-setting type-textblock wide-desc">';

		// Description.
		echo '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_textblock_titled' ) ) {

	/**
	 * Textblock Titled option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_type_textblock_titled( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Format setting outer wrapper.
		echo '<div class="format-setting type-textblock titled wide-desc">';

		// Description.
		echo '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>';

		echo '</div>';
	}
}

if ( ! function_exists( 'ot_type_typography' ) ) {

	/**
	 * Typography option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_type_typography( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// Format setting outer wrapper.
		echo '<div class="format-setting type-typography ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : '';

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Allow fields to be filtered.
		$ot_recognized_typography_fields = apply_filters(
			'ot_recognized_typography_fields',
			array(
				'font-color',
				'font-family',
				'font-size',
				'font-style',
				'font-variant',
				'font-weight',
				'letter-spacing',
				'line-height',
				'text-decoration',
				'text-transform',
			),
			$field_id
		);

		// Build font color.
		if ( in_array( 'font-color', $ot_recognized_typography_fields, true ) ) {

			// Build colorpicker.
			echo '<div class="option-tree-ui-colorpicker-input-wrap">';

			// Colorpicker JS.
			echo '<script>jQuery(document).ready(function($) { OT_UI.bind_colorpicker("' . esc_attr( $field_id ) . '-picker"); });</script>';

			// Set background color.
			$background_color = isset( $field_value['font-color'] ) ? esc_attr( $field_value['font-color'] ) : '';

			/* input */
			echo '<input type="text" name="' . esc_attr( $field_name ) . '[font-color]" id="' . esc_attr( $field_id ) . '-picker" value="' . esc_attr( $background_color ) . '" class="hide-color-picker ' . esc_attr( $field_class ) . '" />';

			echo '</div>';
		}

		// Build font family.
		if ( in_array( 'font-family', $ot_recognized_typography_fields, true ) ) {
			$font_family = isset( $field_value['font-family'] ) ? $field_value['font-family'] : '';
			echo '<select name="' . esc_attr( $field_name ) . '[font-family]" id="' . esc_attr( $field_id ) . '-font-family" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';
			echo '<option value="">font-family</option>';
			foreach ( ot_recognized_font_families( $field_id ) as $key => $value ) {
				echo '<option value="' . esc_attr( $key ) . '" ' . selected( $font_family, $key, false ) . '>' . esc_attr( $value ) . '</option>';
			}
			echo '</select>';
		}

		// Build font size.
		if ( in_array( 'font-size', $ot_recognized_typography_fields, true ) ) {
			$font_size = isset( $field_value['font-size'] ) ? esc_attr( $field_value['font-size'] ) : '';
			echo '<select name="' . esc_attr( $field_name ) . '[font-size]" id="' . esc_attr( $field_id ) . '-font-size" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';
			echo '<option value="">font-size</option>';
			foreach ( ot_recognized_font_sizes( $field_id ) as $option ) {
				echo '<option value="' . esc_attr( $option ) . '" ' . selected( $font_size, $option, false ) . '>' . esc_attr( $option ) . '</option>';
			}
			echo '</select>';
		}

		// Build font style.
		if ( in_array( 'font-style', $ot_recognized_typography_fields, true ) ) {
			$font_style = isset( $field_value['font-style'] ) ? esc_attr( $field_value['font-style'] ) : '';
			echo '<select name="' . esc_attr( $field_name ) . '[font-style]" id="' . esc_attr( $field_id ) . '-font-style" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';
			echo '<option value="">font-style</option>';
			foreach ( ot_recognized_font_styles( $field_id ) as $key => $value ) {
				echo '<option value="' . esc_attr( $key ) . '" ' . selected( $font_style, $key, false ) . '>' . esc_attr( $value ) . '</option>';
			}
			echo '</select>';
		}

		// Build font variant.
		if ( in_array( 'font-variant', $ot_recognized_typography_fields, true ) ) {
			$font_variant = isset( $field_value['font-variant'] ) ? esc_attr( $field_value['font-variant'] ) : '';
			echo '<select name="' . esc_attr( $field_name ) . '[font-variant]" id="' . esc_attr( $field_id ) . '-font-variant" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';
			echo '<option value="">font-variant</option>';
			foreach ( ot_recognized_font_variants( $field_id ) as $key => $value ) {
				echo '<option value="' . esc_attr( $key ) . '" ' . selected( $font_variant, $key, false ) . '>' . esc_attr( $value ) . '</option>';
			}
			echo '</select>';
		}

		// Build font weight.
		if ( in_array( 'font-weight', $ot_recognized_typography_fields, true ) ) {
			$font_weight = isset( $field_value['font-weight'] ) ? esc_attr( $field_value['font-weight'] ) : '';
			echo '<select name="' . esc_attr( $field_name ) . '[font-weight]" id="' . esc_attr( $field_id ) . '-font-weight" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';
			echo '<option value="">font-weight</option>';
			foreach ( ot_recognized_font_weights( $field_id ) as $key => $value ) {
				echo '<option value="' . esc_attr( $key ) . '" ' . selected( $font_weight, $key, false ) . '>' . esc_attr( $value ) . '</option>';
			}
			echo '</select>';
		}

		// Build letter spacing.
		if ( in_array( 'letter-spacing', $ot_recognized_typography_fields, true ) ) {
			$letter_spacing = isset( $field_value['letter-spacing'] ) ? esc_attr( $field_value['letter-spacing'] ) : '';
			echo '<select name="' . esc_attr( $field_name ) . '[letter-spacing]" id="' . esc_attr( $field_id ) . '-letter-spacing" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';
			echo '<option value="">letter-spacing</option>';
			foreach ( ot_recognized_letter_spacing( $field_id ) as $option ) {
				echo '<option value="' . esc_attr( $option ) . '" ' . selected( $letter_spacing, $option, false ) . '>' . esc_attr( $option ) . '</option>';
			}
			echo '</select>';
		}

		// Build line height.
		if ( in_array( 'line-height', $ot_recognized_typography_fields, true ) ) {
			$line_height = isset( $field_value['line-height'] ) ? esc_attr( $field_value['line-height'] ) : '';
			echo '<select name="' . esc_attr( $field_name ) . '[line-height]" id="' . esc_attr( $field_id ) . '-line-height" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';
			echo '<option value="">line-height</option>';
			foreach ( ot_recognized_line_heights( $field_id ) as $option ) {
				echo '<option value="' . esc_attr( $option ) . '" ' . selected( $line_height, $option, false ) . '>' . esc_attr( $option ) . '</option>';
			}
			echo '</select>';
		}

		// Build text decoration.
		if ( in_array( 'text-decoration', $ot_recognized_typography_fields, true ) ) {
			$text_decoration = isset( $field_value['text-decoration'] ) ? esc_attr( $field_value['text-decoration'] ) : '';
			echo '<select name="' . esc_attr( $field_name ) . '[text-decoration]" id="' . esc_attr( $field_id ) . '-text-decoration" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';
			echo '<option value="">text-decoration</option>';
			foreach ( ot_recognized_text_decorations( $field_id ) as $key => $value ) {
				echo '<option value="' . esc_attr( $key ) . '" ' . selected( $text_decoration, $key, false ) . '>' . esc_attr( $value ) . '</option>';
			}
			echo '</select>';
		}

		// Build text transform.
		if ( in_array( 'text-transform', $ot_recognized_typography_fields, true ) ) {
			$text_transform = isset( $field_value['text-transform'] ) ? esc_attr( $field_value['text-transform'] ) : '';
			echo '<select name="' . esc_attr( $field_name ) . '[text-transform]" id="' . esc_attr( $field_id ) . '-text-transform" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';
			echo '<option value="">text-transform</option>';
			foreach ( ot_recognized_text_transformations( $field_id ) as $key => $value ) {
				echo '<option value="' . esc_attr( $key ) . '" ' . selected( $text_transform, $key, false ) . '>' . esc_attr( $value ) . '</option>';
			}
			echo '</select>';
		}

		echo '</div>';

		echo '</div>';

	}
}

if ( ! function_exists( 'ot_type_upload' ) ) {

	/**
	 * Upload option type.
	 *
	 * See @ot_display_by_type to see the full list of available arguments.
	 *
	 * @param array $args An array of arguments.
	 *
	 * @access public
	 * @since  2.0
	 */
	function ot_type_upload( $args = array() ) {

		// Turns arguments array into variables.
		extract( $args ); // phpcs:ignore

		// Verify a description.
		$has_desc = ! empty( $field_desc ) ? true : false;

		// If an attachment ID is stored here fetch its URL and replace the value.
		if ( $field_value && wp_attachment_is_image( $field_value ) ) {

			$attachment_data = wp_get_attachment_image_src( $field_value, 'original' );

			// Check for attachment data.
			if ( $attachment_data ) {

				$field_src = $attachment_data[0];
			}
		}

		// Format setting outer wrapper.
		echo '<div class="format-setting type-upload ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		// Description.
		echo $has_desc ? '<div class="description">' . wp_kses_post( htmlspecialchars_decode( $field_desc ) ) . '</div>' : ''; // phpcs:ignore

		// Format setting inner wrapper.
		echo '<div class="format-setting-inner">';

		// Build upload.
		echo '<div class="option-tree-ui-upload-parent">';

		echo '<input type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( $field_value ) . '" class="widefat option-tree-ui-upload-input ' . esc_attr( $field_class ) . '" />';

		// Add media button.
		echo '<a href="javascript:void(0);" class="ot_upload_media option-tree-ui-button button button-primary light" rel="' . esc_attr( $post_id ) . '" title="' . esc_html__( 'Add Media', 'sidebar-menu' ) . '"><span class="icon ot-icon-plus-circle"></span>' . esc_html__( 'Add Media', 'sidebar-menu' ) . '</a>'; // phpcs:ignore

		echo '</div>';

		// Media.
		if ( $field_value ) {

			echo '<div class="option-tree-ui-media-wrap" id="' . esc_attr( $field_id ) . '_media">';

			// Replace image src.
			if ( isset( $field_src ) ) {
				$field_value = $field_src;
			}

			if ( preg_match( '/\.(?:jpe?g|png|gif|ico)$/i', $field_value ) ) {
				echo '<div class="option-tree-ui-image-wrap"><img src="' . esc_url( $field_value ) . '" alt="" /></div>';
			}

			echo '<a href="javascript:(void);" class="option-tree-ui-remove-media option-tree-ui-button button button-secondary light" title="' . esc_html__( 'Remove Media', 'sidebar-menu' ) . '"><span class="icon ot-icon-minus-circle"></span>' . esc_html__( 'Remove Media', 'sidebar-menu' ) . '</a>';

			echo '</div>';

		}

		echo '</div>';

		echo '</div>';
	}
}
