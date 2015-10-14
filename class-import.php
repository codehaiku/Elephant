<?php

class ElephantImport {

	var $options_uri  = "http://";
	var $options_path = "";
	var $sql_path = "";

	public function __construct() {

		$this->options_uri  = ELEPHANT_IMPORT_URL . '/' . 'wp_options.txt';
		$this->options_path = ELEPHANT_IMPORT_DIR . '/' . 'wp_options.txt';
		$this->sql_path     = ELEPHANT_IMPORT_DIR . '/' . 'data.sql';

		return;
	}

	public function import() {

		$this->update_option()
			 ->import_mysql()
			 ->copy_files();

		return $this;
	}

	public function update_option() {

		$contents = unserialize( wp_remote_fopen( $this->options_uri ) );
		
		if ( ! empty( $contents ) ) {

			foreach ( $contents as $option ) {
				
				if ( is_serialized( $option['option_value'] ) ) {
					$option['option_value'] = unserialize( $option['option_value'] );
				}

				update_option( 
					$option['option_name'], 
					$option['option_value'],
					$option['autoload']
				);

			}
		}

		return $this;
	}

	public function import_mysql() {

		
		global $wpdb;

		// Temporary variable, used to store current query
		$templine = '';
		
		// Read in entire file

		$lines = file( $this->sql_path );

		// Loop through each line
		foreach ( $lines as $line )
		{

			// Skip it if it's a comment
			if ( substr( $line, 0, 3 ) === "/*!" || substr( $line, 0, 2 ) == '--' || $line == '')
			    continue;

			// Add this line to the current segment
			$templine .= $line;
			// If it has a semicolon at the end, it's the end of the query
			if (substr(trim($line), -1, 1) == ';')
			{
			    // Perform the query
			    $templine . '<br/>';
			    $wpdb->query( $templine ) or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
			    // Reset temp variable to empty
			    $templine = '';
			}
		}

		return $this;
	}

	public function copy_files(){

		copy_dir( ELEPHANT_IMPORT_DIR . '/uploads', WP_CONTENT_DIR . '/uploads' );

		return $this;

	}

}

?>