<?php
/**
 * Request handler file.
 */
$method = filter_input( INPUT_GET, 'method', FILTER_SANITIZE_ENCODED );

if ( 'export' === $method ) {
	
	require_once plugin_dir_path( __FILE__ ) . 'dumper.php';
	

	// Step 1: Export Local WordPress Database
	$host = DB_HOST;
	$dbname = DB_NAME;
	$username = DB_USER;
	$password = DB_PASSWORD;

	try {

		$new_dir = "";

		$wp_upload_dir = wp_upload_dir();
		
		$dir_name = sanitize_title( get_bloginfo('name') . '-' . get_current_theme() );

		// plugins directory
		$plugins_dir = plugin_dir_path( __DIR__ );
		
		// Create directory.
		if ( wp_mkdir_p(  WP_CONTENT_DIR .'/'. $dir_name ) ) {
			
			// The name of the directory we will put our sql file
			$new_dir = WP_CONTENT_DIR .'/'. $dir_name;

			$sql_file = $new_dir . '/' . $dir_name . '.sql';

			$dump = new Ifsnop\Mysqldump\Mysqldump( "mysql:host=$host;dbname=$dbname", $username, $password );

			if ( chmod( $new_dir, 0777 ) ) {

				WP_Filesystem();

				// Dump the sql file
				@$dump->start( $sql_file );
				
				// Next, copy uploads dir
				$new_uploads_dir = WP_CONTENT_DIR . '/' . $dir_name . '/uploads';
				
				if ( wp_mkdir_p( $new_uploads_dir ) ) {

					copy_dir(
						$wp_upload_dir['basedir'], $new_uploads_dir, 

						$skip_list = array( $wp_upload_dir['path'] . '/' . $dir_name ) 
					);

				} else {

					die( 'Unable to move "uploads" dir' );

				}

				// Next, copy the plugins dir
				$new_plugins_dir =  WP_CONTENT_DIR . '/' . $dir_name . '/plugins';
				
				if ( wp_mkdir_p( $new_plugins_dir ) ) {

					copy_dir( $plugins_dir, $new_plugins_dir, $skip_list = array() );

				} else {

					die( 'Unable to copy ' . $plugins_dir . ' to ' . $new_plugins_dir );

				}

				// Next, zip the file.
				elephant_zip( WP_CONTENT_DIR .'/'. $dir_name, WP_CONTENT_DIR .'/'. $dir_name . '.zip', true );

				chmod( $wp_upload_dir['path'], 0755 );

			} else {

				die('Directory unwritable. Make sure you have the permission to do so.');

			}

		} else {
			
			die('Directory unwritable. Make sure you have the permission to do so.');

		}

		die();

	} catch (Exception $e ) {

    	echo $e->getMessage();

	}

	
	// Step 2: Uploading WordPress Files to Live Site
	
	// Step 3: Creating MySQL Database on Live Site
	// Step 4: Importing WordPress Database on Live Site
	// Step 5: Changing the Site URL
	// Step 6: Setting Up your Live Site

} elseif( 'import' === $method ) {
	
	echo 'Processing import ...';

} else {

	echo 'Undefine action ...';
}
die();