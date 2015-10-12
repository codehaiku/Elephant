<?php

class ElephantImport {

	public function __construct(){}

	public function import() {

		$this->update_option()
			 ->import_mysql()
			 ->copy_files();

		die();

	}



	public function update_option() {

		$uri = ELEPHANT_EXPORT_URL . '/' . ELEPHANT_EXPORT_NAME . '_wp_options.txt';

		$contents = unserialize( wp_remote_fopen( $uri ) );
		
		if ( ! empty( $contents ) ) {
			foreach ( $contents as $options ) {
				update_option( 
					$options['option_name'], 
					$options['option_value'],
					$options['autoload']
				);
			}
		}

		return $this;
	}

	public function import_mysql() {
		return $this;
	}

	public function copy_files(){
		
	}

}

?>