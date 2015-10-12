<?php

function elephant_delete( $dir ) {

    $it = new RecursiveDirectoryIterator( $dir, RecursiveDirectoryIterator::SKIP_DOTS );
    $files = new RecursiveIteratorIterator( $it,
                 RecursiveIteratorIterator::CHILD_FIRST );

    foreach ( $files as $file ) {
        
        if ( $file->isDir() ) {
            rmdir( $file->getRealPath() );
        } else {
            unlink( $file->getRealPath() );
        }
    }

    rmdir($dir);

}

function elephant_zip( $source, $destination )
{
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }

    $source = str_replace('\\', '/', realpath($source));

    if (is_dir($source) === true)
    {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file)
        {
            $file = str_replace('\\', '/', $file);

            // Ignore "." and ".." folders
            if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                continue;

            $file = realpath($file);

            if (is_dir($file) === true)
            {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            }
            else if (is_file($file) === true)
            {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    }
    else if (is_file($source) === true)
    {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}

function elephant_export_state() {

    global $wpdb;

    require_once plugin_dir_path( __FILE__ ) . 'dumper.php';

    // Step 1: Export Local WordPress Database.
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

            $settings = array(
                    'exclude-tables' => array( $wpdb->prefix . 'options' )
                );

            $dump = new Ifsnop\Mysqldump\Mysqldump( "mysql:host=$host;dbname=$dbname", $username, $password, $settings );

            if ( chmod( $new_dir, 0777 ) ) {

                WP_Filesystem();

                // Dump the sql file.
                @$dump->start( $sql_file );
                
                // Next, copy uploads dir.
                $new_uploads_dir = WP_CONTENT_DIR . '/' . $dir_name . '/uploads';
                
                $export = new ElephantExport();

                $export->export_options_table();

                if ( wp_mkdir_p( $new_uploads_dir ) ) {

                    copy_dir(

                        $wp_upload_dir['basedir'], $new_uploads_dir, 

                        $skip_list = array( $wp_upload_dir['path'] . '/' . $dir_name )

                    );

                } else {

                    die( 'Unable to move "uploads" dir' );

                }

                // Next, copy the plugins dir.
                $new_plugins_dir =  WP_CONTENT_DIR . '/' . $dir_name . '/plugins';
                
                if ( wp_mkdir_p( $new_plugins_dir ) ) {

                    copy_dir( $plugins_dir, $new_plugins_dir, $skip_list = array() );

                } else {

                    die( 'Unable to copy ' . $plugins_dir . ' to ' . $new_plugins_dir );

                }

                // Next, zip the file.
                $zip_location = WP_CONTENT_DIR .'/'. $dir_name . '.zip';

                if ( elephant_zip( WP_CONTENT_DIR .'/'. $dir_name, $zip_location, true ) ) {
                    
                    $timestamp = date( 'F j, Y H:i:s', current_time( 'timestamp', 0 ) );

                    update_option( 'elephant_exported_dir_path',  WP_CONTENT_DIR . '/' . $dir_name  );
                    update_option( 'elephant_exported_zip_path',  WP_CONTENT_DIR . '/' . $dir_name . '.zip'  );
                    update_option( 'elephant_exported_zip_file',  content_url() . '/' . $dir_name . '.zip'  );
                    update_option( 'elephant_exported_zip_file_timestamp', $timestamp );

                    // delete the directory after zipping it
                    elephant_delete( WP_CONTENT_DIR . '/' . $dir_name );

                } else {

                    die( 'Unable to ZIP file' );

                }

                chmod( $wp_upload_dir['path'], 0755 );

            } else {

                die('Directory unwritable. Make sure you have the permission to do so.');

            }

        } else {
            
            die('Directory unwritable. Make sure you have the permission to do so.');

        }

    } catch ( Exception $e ) {

        echo $e->getMessage();

        return false;

    }

    return true;
}

function elephant_import_theme_mod()
{
    
    $theme_mod_json_url = "http://localhost/elephant/wp-content/elephant-dev-twenty-fifteen/theme_mods.txt";

    try {
        $theme_mod_json = wp_remote_get( $theme_mod_json_url );
    } catch ( Exception $e ) {
        $theme_mod_json = "";
    } 

    if ( !empty( $theme_mod_json ) ) {
        $theme_mod_json = unserialize( $theme_mod_json['body'] );
        foreach ( $theme_mod_json as $key => $value ) {
            set_theme_mod( $key, $value );
        }
    }

    return;
}