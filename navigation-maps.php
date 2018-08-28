<?php

/*
Plugin Name: Navigation Maps
Description: Navigation Maps for COMMEUNSEULHOMME missions.
Version: 1.0
Author: Lyketil - Digital Lab
Author URI: https://lyketil.com
*/

if ( !class_exists("LyNavMap") ) {
    class LyNavMap {

        /**
         * Installs the necessary database
         *
         * Creates a new database to mostly store lat/long coordinates associated with a time 
         *
         * @since 1.0.0
         * @link https://codex.wordpress.org/Creating_Tables_with_Plugins
         */
        function map_install() {
            global $wpdb;
            $this->map_db_version = '1.0'; // creates version of the DB for easier update

            // $wpdb->show_errors();
            // $wpdb->print_error();

            $new_table = $wpdb->prefix . 'ly_nav_map';  // $wpdb->prefix sends back the db prefix (eg: wp_)

            print_r($wpdb->get_var("SHOW TABLES LIKE '$new_table'"));

            if ( $wpdb->get_var("SHOW TABLES LIKE '$new_table'") != $new_table ) {
                $charset_collate = $wpdb->get_charset_collate(); // If we don't do this, some characters could end up being converted to just ?'s when saved in our table

                $sql = "CREATE TABLE $new_table (
                        id bigint(20) NOT NULL AUTO_INCREMENT,
                        title text NOT NULL,
                        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                        created_at timestamp,
                        longitude text NOT NULL,
                        latitude text NOT NULL,
                        PRIMARY KEY  (id)
                    ) $charset_collate;";
                require_once (ABSPATH.'wp-admin/includes/upgrade.php'); // necessary to use dbDelta (not part of WP API)
                dbDelta($sql); // works with a CREATE sql query, creates or updates the database

                add_option( 'map_db_version', $this->map_db_version ); // creates a new option row to store version
            }
        }

        // when desinstalling the plugin
        function map_uninstall() {
            global $wpdb;

            $target_table = $wpdb->prefix . 'ly_nav_map'; // identifies table to drop

            if ( $wpdb->get_var("SHOW TABLES LIKE '$target_table'") == $target_table ) {
                $sql = "DROP TABLE $target_table";
                $wpdb->query( $sql );
            }
        }

        // actions at initialization: add to admin menu
        function init() {

        }

        // designing the admin interface
        function map_admin_page() {

        }

        // requiring backend files, styles, js
        function map_backend_imports() {

        }

        // requiring frontend files, styles, js
        function map_frontend_imports() {
            
        }

        // defining shortcode
        function map_shortcode() {

        }

        // CRUD: SQL INSERT (Create)
        // CRUD: SQL SELECT (Read)
        // CRUD: SQL UPDATE (Update)
        // CRUD: SQL DELETER (Delete)

    }
}

// action hooks: make sure that WordPress calls this function when the plugin is activated by a WordPress administrator
if ( class_exists("LyNavMap") ) {
    $inst_map = new LyNavMap();
}

// hook on plugin activation
if ( isset( $inst_map ) ) {
    // hook on plugin activation
    register_activation_hook( __FILE__, array( $inst_map, "map_install" ) );

    // hook on plugin deactivation
    register_deactivation_hook( __FILE__, array( $inst_map, "map_uninstall" ) );
}

?>