<?php
/*
Plugin Name: Elephant
Plugin URI:  http://dunhakdis.me
Description: This plugin exports/imports all WordPress theme settings, mods, etc.. This plugin is primarily used on Dunhakdis Themes.
Version:     0.0.1
Author:      Joseph Gabito, Dunhakdis
Author URI:  http://dunhakdis.me/about/#dunhakdis
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: elephant
*/
defined('ABSPATH') or die('No script kiddies please!');

define('ELEPHANT_ADMIN_MODE', true);

require_once plugin_dir_path( __FILE__ ) . 'functions.php';

if ( ELEPHANT_ADMIN_MODE ) {

    // Create 'Export Option' under 'Tools'
    add_action('admin_menu', 'elephant_register_submenu_page');

    function elephant_register_submenu_page() 
    {
    	
    	$elephant_options_title = __( 'Import Demo', 'elephant' );
        $elephant_options_uid = 'elephant-import-page';

        add_submenu_page( 
        	'tools.php',  $elephant_options_title, 
        	$elephant_options_title, 'manage_options', 
        	$elephant_options_uid, 'elephant_screen'
        );

        return;

    }

    function elephant_screen() 
    {

    	// Require the demo collections.
    	require_once plugin_dir_path( __FILE__ ) . 'demos.php';
        // Require the demo management screen.
      	require_once plugin_dir_path( __FILE__ ) . 'screen.php';

      	return;

    }

}

// Include the Stylesheet of the Elephant.
function elephant_admin_style() {

        wp_register_style( 'elephant_admin_style', plugins_url('/elephant/style.css') , false, '1.0.0' );

        wp_enqueue_style( 'elephant_admin_style' );

}

// Include all the actions we need to process our script.
add_action( 'wp_ajax_elepant_processor', 'elepant_processor' );

function elepant_processor() {
    // Request handler.
    require_once plugin_dir_path( __FILE__ ) . 'actions.php';
}

add_action( 'admin_enqueue_scripts', 'elephant_admin_style' );