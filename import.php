<?php
/**
 * Import Handler.
 *
 * @since  0.0.1
 */
defined( 'ABSPATH' ) or die('No script kiddies please!');

if ( empty( $_FILES ) ) { die('Unable to import empty file.'); }

if ( empty( $_FILES['demo_file'] ) ) { die('Unable to import empty file. "demo_file" argument not found.'); }

$file_name = $_FILES['demo_file']['name'];
$file_tmp  = $_FILES['demo_file']['tmp_name'];
$file_type = $_FILES['demo_file']['type'];
$file_size = $_FILES['demo_file']['size'];	

$file_uploaded_location = ELEPHANT_EXPORT_DIR . '/' . $file_name;

if ( is_dir( ELEPHANT_EXPORT_DIR ) ) {
	elephant_delete( ELEPHANT_EXPORT_DIR );
} 

try {
	mkdir( ELEPHANT_EXPORT_DIR );
} catch ( Exception $e ) {
	die( $e->getMessage() );
}

if ( ! move_uploaded_file( $file_tmp,  $file_uploaded_location ) ) {
	die( 'Unable to move uploaded file to ' . $file_uploaded_location . '. Pleae check if the system is writable.' );
}

WP_Filesystem();

// Try unzipping the file
if ( unzip_file( $file_uploaded_location, ELEPHANT_EXPORT_DIR ) ) {
	
	$import = new ElephantImport();
	$import->import();

}

//elephant_delete( ELEPHANT_EXPORT_DIR );

die();