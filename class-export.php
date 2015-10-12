<?php

class ElephantExport {

	
	var $dir_name = "";

	public function __construct() {

		$this->dir_name = sanitize_title( get_bloginfo('name') . '-' . get_current_theme() );

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

		file_put_contents( ELEPHANT_EXPORT_DIR . '/' . $this->dir_name . '_wp_options.txt', $options );

		return true;

	}

}