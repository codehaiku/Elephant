<?php

class ElephantExport {

	
	var $dir_name = "";

	public function __construct() {

		$this->dir_name = sanitize_title( get_bloginfo('name') . '-' . get_current_theme() );

	}

	public function export_attachment_files() {

		global $wpdb;
		
		$wp_upload_dir = wp_upload_dir();

		$attachments = $wpdb->get_results("SELECT meta_value from {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file';", OBJECT);
		
		// Create 'Uploads' directory
		if ( ! wp_mkdir_p( ELEPHANT_EXPORT_DIR . '/uploads' ) ) {

			die('Unable to create directory. Exiting ...');
			
		}
		
		foreach ( $attachments as $attachment ) {
			
			// Current Theme/Site "uploads" directory.
			$attachment_src = $wp_upload_dir['basedir'] . '/' . $attachment->meta_value;

			$attachment_dir =  ELEPHANT_EXPORT_DIR . '/uploads/' . dirname( $attachment->meta_value );

			if ( wp_mkdir_p ( $attachment_dir ) ) {
				
				// Copy to export directory under 'uploads'
				$attachment_destination = ELEPHANT_EXPORT_DIR . '/uploads/' . $attachment->meta_value;

				if ( ! copy( $attachment_src, $attachment_destination ) ) {
					
					echo "Export failed....<br/>";

					return false;

				}

			} else {
				
				echo 'Failed to copy file. Unable to create directory';
				
				return false;

			}
		
		}

        return true;

	}

	public function export_options_table() {

		global $wpdb;

		$option_filter  =  "option_name <> 'siteurl'";
		$option_filter .= " AND option_name <> 'home'";
		$option_filter .= " AND option_name <> 'blogname'";
		$option_filter .= " AND option_name <> 'blogdescription'";
		$option_filter .= " AND option_name <> 'admin_email'";
		$option_filter .= " AND option_name <> 'template'";
		$option_filter .= " AND option_name <> 'stylesheet'";
		
		$options = serialize( $wpdb->get_results( "SELECT * from {$wpdb->prefix}options WHERE {$option_filter}", ARRAY_A ) );

		file_put_contents( ELEPHANT_EXPORT_DIR . '/wp_options.txt', $options );

		return true;

	}

}