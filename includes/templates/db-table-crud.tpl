<?php

$table_name        = '{{table_name}}';
$function_prefix   = '{{function_prefix}}';
$columns_raw       = '{{columns}}';
$text_domain       = '{{text_domain}}';

global $wpdb;
$table_full_name = $wpdb->prefix . $table_name;

// Function to create/update the database table
if ( ! function_exists( $function_prefix . '_create_table' ) ) {
	function {{function_prefix}}_create_table() {
		global $wpdb;
		$table_full_name = $wpdb->prefix . '{{table_name}}';
		$charset_collate = $wpdb->get_charset_collate();

		$columns_sql = array();
		$columns_defs = explode( ", ", '{{columns}}' );
		foreach ( $columns_defs as $col_def ) {
			$parts = explode( ":", $col_def );
			$name = sanitize_key( $parts[0] );
			$type = sanitize_key( $parts[1] );
			$length = ! empty( $parts[2] ) && 'NULL' !== $parts[2] ? '(' . absint( $parts[2] ) . ')' : '';
			$nullable = ! empty( $parts[3] ) ? sanitize_text_field( $parts[3] ) : '';
			$default = ! empty( $parts[4] ) && 'NULL' !== $parts[4] ? 'DEFAULT ' . esc_sql( $parts[4] ) : '';
			$extra = ! empty( $parts[5] ) ? sanitize_text_field( $parts[5] ) : '';

			$columns_sql[] = "`{$name}` {$type}{$length} {$nullable} {$default} {$extra}";
		}
		$columns_sql = implode( ",\n\t\t", $columns_sql );

		$sql = "CREATE TABLE {$table_full_name} (
			{$columns_sql}
		) {$charset_collate};";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
	add_action( 'plugins_loaded', $function_prefix . '_create_table' );
}

// Function to insert data
if ( ! function_exists( $function_prefix . '_insert' ) ) {
	function {{function_prefix}}_insert( $data ) {
		global $wpdb;
		$table_full_name = $wpdb->prefix . '{{table_name}}';
		$wpdb->insert( $table_full_name, $data );
		return $wpdb->insert_id;
	}
}

// Function to retrieve data
if ( ! function_exists( $function_prefix . '_get' ) ) {
	function {{function_prefix}}_get( $where = '', $single = false ) {
		global $wpdb;
		$table_full_name = $wpdb->prefix . '{{table_name}}';
		$sql = "SELECT * FROM {$table_full_name}";
		if ( ! empty( $where ) ) {
			$sql .= " WHERE {$where}";
		}
		return $single ? $wpdb->get_row( $sql ) : $wpdb->get_results( $sql );
	}
}

// Function to update data
if ( ! function_exists( $function_prefix . '_update' ) ) {
	function {{function_prefix}}_update( $data, $where ) {
		global $wpdb;
		$table_full_name = $wpdb->prefix . '{{table_name}}';
		return $wpdb->update( $table_full_name, $data, $where );
	}
}

// Function to delete data
if ( ! function_exists( $function_prefix . '_delete' ) ) {
	function {{function_prefix}}_delete( $where ) {
		global $wpdb;
		$table_full_name = $wpdb->prefix . '{{table_name}}';
		return $wpdb->delete( $table_full_name, $where );
	}
}

