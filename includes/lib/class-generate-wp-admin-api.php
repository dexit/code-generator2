<?php
/**
 * Admin API class file.
 *
 * @package WordPress Plugin Template/Admin/API
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin API class.
 */
class Generate_WP_Admin_API {

	/**
	 * Parent plugin class.
	 *
	 * @var object
	 * @access public
	 * @since  1.0.0
	 */
	public $parent;

	/**
	 * Constructor function.
	 *
	 * @param object $parent Parent object.
	 */
	public function __construct( $parent = null ) {
		$this->parent = $parent;
	}

	/**
	 * Display a single form field.
	 *
	 * @param  array   $args  Field arguments.
	 * @param  object  $post  Post object.
	 * @param  boolean $echo  Whether to echo the field HTML or return it.
	 * @return string          HTML for the field.
	 */
	public function display_field( $args = array(), $post = null, $echo = true ) {
		$field = wp_parse_args( $args['field'], array(
			'id'          => '',
			'label'       => '',
			'description' => '',
			'type'        => 'text',
			'default'     => '',
			'placeholder' => '',
			'options'     => array(),
			'attributes'  => array(),
			'min'         => '',
			'max'         => '',
			'prefix'      => '', // Added for consistency with usage in settings.
			'list'        => false,
		) );

		$option_name = $args['prefix'] . $field['id']; // Use 'prefix' from $args.
		$data        = get_option( $option_name, $field['default'] );

		// Handle data for specific field types, e.g., checkbox_multi.
		if ( in_array( $field['type'], array( 'checkbox_multi', 'select_multi' ), true ) ) {
			$data = (array) $data;
		}

		$html = '';
		$props = '';
		if ( ! empty( $field['attributes'] ) ) {
			foreach ( $field['attributes'] as $prop_key => $prop_val ) {
				$props .= ' data-' . esc_attr( $prop_key ) . '="' . esc_attr( $prop_val ) . '"';
			}
		}

		// Add custom attributes for conditional display (e.g., for showing/hiding fields)
		if ( ! empty( $field['data'] ) && is_array( $field['data'] ) ) {
			foreach ( $field['data'] as $data_key => $data_val ) {
				$props .= ' data-' . esc_attr( $data_key ) . '="' . esc_attr( $data_val ) . '"';
			}
		}

		switch ( $field['type'] ) {
			case 'text':
			case 'number':
			case 'password':
				$min = '';
				if ( ! empty( $field['min'] ) ) {
					$min = ' min="' . esc_attr( $field['min'] ) . '"';
				}
				$max = '';
				if ( ! empty( $field['max'] ) ) {
					$max = ' max="' . esc_attr( $field['max'] ) . '"';
				}
				$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="' . esc_attr( $field['type'] ) . '" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" value="' . esc_attr( $data ) . '"' . $props . $min . $max . '/>' . "
";
				break;
			case 'text_secret':
				$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="text" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" value=""' . $props . ' />' . "
";
				break;
			case 'textarea':
				$html .= '<textarea id="' . esc_attr( $field['id'] ) . '" rows="5" cols="50" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '"' . $props . '>' . esc_textarea( $data ) . '</textarea><br/>' . "
";
				break;
			case 'checkbox':
				$checked = '';
				if ( $data && 'on' === $data ) {
					$checked = 'checked="checked"';
				}
				$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="' . esc_attr( $field['type'] ) . '" name="' . esc_attr( $option_name ) . '" ' . $checked . $props . ' />' . "
";
				break;
			case 'checkbox_multi':
				foreach ( $field['options'] as $k => $v ) {
					$checked = false;
					if ( in_array( $k, $data, true ) ) {
						$checked = true;
					}
					$html .= '<p' . $props . '><label class="checkbox_multi"><input type="checkbox" ' . checked( $checked, true, false ) . ' name="' . esc_attr( $option_name ) . '[]" value="' . esc_attr( $k ) . '" /> ' . esc_html( $v ) . '</label></p> ';
				}
				break;
			case 'radio':
				foreach ( $field['options'] as $k => $v ) {
					$checked = false;
					if ( $k === $data ) {
						$checked = true;
					}
					$html .= '<label ' . $props . '><input type="radio" ' . checked( $checked, true, false ) . ' name="' . esc_attr( $option_name ) . '" value="' . esc_attr( $k ) . '" id="' . esc_attr( $field['id'] . '_' . $k ) . '" /> ' . esc_html( $v ) . '</label> ';
				}
				break;
			case 'select':
				$html .= '<select name="' . esc_attr( $option_name ) . '" id="' . esc_attr( $field['id'] ) . '"' . $props . '>';
				foreach ( $field['options'] as $k => $v ) {
					$selected = false;
					if ( $k === $data ) {
						$selected = true;
					}
					$html .= '<option ' . selected( $selected, true, false ) . ' value="' . esc_attr( $k ) . '">' . esc_html( $v ) . '</option>';
				}
				$html .= '</select>' . "
";
				break;
			case 'select_multi':
				$html .= '<select name="' . esc_attr( $option_name ) . '[]" id="' . esc_attr( $field['id'] ) . '" multiple="multiple"' . $props . '>';
				foreach ( $field['options'] as $k => $v ) {
					$selected = false;
					if ( in_array( $k, $data, true ) ) {
						$selected = true;
					}
					$html .= '<option ' . selected( $selected, true, false ) . ' value="' . esc_attr( $k ) . '">' . esc_html( $v ) . '</option>';
				}
				$html .= '</select>' . "
";
				break;
			case 'color':
				// Using WordPress's built-in color picker.
				$html .= '<input type="text" name="' . esc_attr( $option_name ) . '" class="color-field" value="' . esc_attr( $data ) . '" data-default-color="' . esc_attr( $field['default'] ) . '"' . $props . ' />';
				break;
			case 'image':
				// Using WordPress's media uploader.
				$image_id = $data;
				$image_url = '';
				if ( $image_id ) {
					$image_url = wp_get_attachment_url( $image_id );
				}
				$html .= '<div class="uploader">';
				$html .= '<input type="hidden" name="' . esc_attr( $option_name ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $image_id ) . '" />';
				$html .= '<button class="button upload_image_button">' . esc_html__( 'Upload Image', 'generate-wp' ) . '</button>';
				$html .= '<button class="button remove_image_button" style="' . ( empty( $image_id ) ? 'display:none;' : '' ) . '">' . esc_html__( 'Remove Image', 'generate-wp' ) . '</button>';
				$html .= '<div class="image-preview" style="' . ( empty( $image_url ) ? 'display:none;' : '' ) . '">';
				$html .= '<img src="' . esc_url( $image_url ) . '" style="max-width:100px; max-height:100px;" />';
				$html .= '</div>';
				$html .= '</div>' . "
";
				break;
			case 'editor':
				// WordPress editor (TinyMCE).
				ob_start();
				wp_editor( $data, esc_attr( $field['id'] ), array(
					'textarea_name' => esc_attr( $option_name ),
					'textarea_rows' => 10,
					'teeny'         => false,
					'media_buttons' => true,
				) );
				$html .= ob_get_clean();
				break;
			default:
				// Fallback for unknown types, consider sanitizing generic text.
				$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="text" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" value="' . esc_attr( $data ) . '"' . $props . ' />' . "
";
				break;
		}

		// Add description if available.
		if ( ! empty( $field['description'] ) ) {
			$html .= '<p class="description">' . esc_html( $field['description'] ) . '</p>' . "
";
		}

		if ( $echo ) {
			echo $html; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- This is a display function, output is handled by specific field types or general fallback.
		} else {
			return $html;
		}
	}
}