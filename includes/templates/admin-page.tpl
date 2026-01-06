<?php

$function_name = '{{function_name}}';
$page_title    = '{{page_title}}';
$menu_title    = '{{menu_title}}';
$capability    = '{{capability}}';
$menu_slug     = '{{menu_slug}}';
$icon_url      = '{{icon_url}}';
$position      = '{{position}}';
$parent_slug   = '{{parent_slug}}';
$text_domain   = '{{text_domain}}';

if ( ! function_exists( $function_name ) ) {
	function {{function_name}}() {
		if ( 'top_level' === '{{menu_type}}' ) {
			add_menu_page(
				esc_html__( $page_title, $text_domain ),
				esc_html__( $menu_title, $text_domain ),
				esc_attr( $capability ),
				esc_attr( $menu_slug ),
				'render_{{function_name}}_page',
				esc_url( $icon_url ),
				absint( $position )
			);
		} elseif ( 'submenu' === '{{menu_type}}' ) {
			add_submenu_page(
				esc_attr( $parent_slug ),
				esc_html__( $page_title, $text_domain ),
				esc_html__( $menu_title, $text_domain ),
				esc_attr( $capability ),
				esc_attr( $menu_slug ),
				'render_{{function_name}}_page'
			);
		}
	}
	add_action( 'admin_menu', $function_name );
}

// Render function for the admin page.
if ( ! function_exists( 'render_{{function_name}}_page' ) ) {
	function render_{{function_name}}_page() {
		?>
		<div class="wrap">
			<h1><?php echo esc_html__( $page_title, $text_domain ); ?></h1>
			<p><?php echo esc_html__( 'Welcome to your custom admin page!', $text_domain ); ?></p>
		</div>
		<?php
	}
}