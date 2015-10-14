<?php
/**
 * Request handler file.
 */
//require_once plugin_dir_path( __FILE__ ) . 'functions.php';

$method = filter_input( INPUT_GET, 'method', FILTER_SANITIZE_ENCODED );

if ( 'export' === $method ) {
	
	if ( elephant_export_state() ) {
		
		wp_safe_redirect( admin_url( 'tools.php?page=elephant-select-page&export-success=yes' ) );

	} else {

		die( 'unable to export file ');

	}

} elseif( 'import' === $method ) {
	
	echo 'Processing import ...';

} elseif( 'delete' === $method ) {

	$demo_path = get_option( 'elephant_exported_zip_path' );

	if ( $demo_path ) {

		if ( file_exists( $demo_path  ) ) {

			@unlink( $demo_path );
			
		}
		
		$stored_option_keys = array( 'elephant_exported_dir_path',
									 'elephant_exported_zip_path',
									 'elephant_exported_zip_file',
									 'elephant_exported_zip_file_timestamp' );

		foreach ( $stored_option_keys as $option ) {
			delete_option( $option );
		}

	}

	wp_safe_redirect( admin_url( 'tools.php?page=elephant-select-page' ) );

} else {

	echo 'Undefine action ...';
}
die();