<?php

$namespace         = '{{namespace}}';
$route             = '{{route}}';
$method            = '{{method}}';
$callback          = '{{callback}}';
$permission_callback = '{{permission_callback}}';
$text_domain       = '{{text_domain}}';

if ( ! function_exists( '{{namespace_function}}' ) ) {
	function {{namespace_function}}() {
		register_rest_route( $namespace, $route, array(
			'methods'               => $method,
			'callback'              => $callback,
			'permission_callback'   => $permission_callback,
			'args'                  => array(), // @TODO: Add args dynamically
		));
	}
	add_action( 'rest_api_init', '{{namespace_function}}' );
}

// Default callback function (replace with your logic)
if ( ! function_exists( $callback ) ) {
	function {$callback}( WP_REST_Request $request ) {
		$response = array(
			'message' => esc_html__( 'This is your custom REST API endpoint!', $text_domain ),
			'params'  => $request->get_params(),
		);
		return new WP_REST_Response( $response, 200 );
	}
}

// Default permission callback function (replace with your logic)
if ( ! function_exists( $permission_callback ) ) {
	function {$permission_callback}() {
		return true; // @TODO: Implement proper permission checks
	}
}
