<?php
/*
Plugin Name:  Karza GST Validator
Plugin URI: https://teqfocus.com
Description: A plugin to manage Karza API (Integration)
Version: 2.8.0
Author: A Ali
*/

// Create the table on plugin activation
function karza_activate() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'karza_response';
    $setting_table = $wpdb->prefix . 'karza_api_setting';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "
        SET lc_time_names = 'en_IN';
        CREATE TABLE IF NOT EXISTS $table_name (
            `id` int(10) AUTO_INCREMENT PRIMARY KEY,
            `gst_no` varchar(255) DEFAULT NULL,
            `trade_name` varchar(255) DEFAULT NULL,
            `address` varchar(255) DEFAULT NULL,
            `pdf_file` varchar(255) DEFAULT NULL,
            `last_updated_on` varchar(255) DEFAULT NULL,
            `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
            `status` tinyint(4) NOT NULL DEFAULT 1
        ) $charset_collate;
        
        DROP TABLE IF EXISTS $setting_table;
        CREATE TABLE IF NOT EXISTS  $setting_table (
            `id` int(10) AUTO_INCREMENT PRIMARY KEY,
            `consolidate` varchar(255) DEFAULT NULL,
            `extendedPeriod` varchar(255) DEFAULT NULL,
            `consent` varchar(255) DEFAULT NULL,
            `pagination` tinyint(4) default 10,
            `karza_key` varchar(255) DEFAULT NULL,
            `api_url` varchar(255) DEFAULT NULL,
            `gst_validation_endpoint_url` varchar(255)  DEFAULT NULL,
            `email_to` text DEFAULT NULL
        );
		ALTER TABLE $setting_table ADD column gst_validation_endpoint_url varchar(255)  DEFAULT NULL;
        
        INSERT INTO $setting_table (id, consolidate, extendedPeriod, consent, karza_key, api_url, gst_validation_endpoint_url, email_to)
        VALUES (1, 'false', 'false', 'Y', 'GGWE7he8cjb51bdoPgCv', 'https://api.karza.in/gst/uat/v2/gst-return-auth-advance', 'https://api.karza.in/gst/uat/v2/gst-verification', 'akbar@teqfocus.com');
		update $setting_table set gst_validation_endpoint_url='https://api.karza.in/gst/uat/v2/gst-verification';";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta( $sql );

    //create folder for store gst document inside uploads folder
    $upload_dir = wp_upload_dir();
    $folder_path = $upload_dir['basedir'] . '/karza-gst-document';

    if ( ! file_exists( $folder_path ) ) {
        wp_mkdir_p( $folder_path );
    }
}
register_activation_hook( __FILE__, 'karza_activate');


function karza_deactivate() {
    // do nothing on deativation of plugin

}
register_deactivation_hook( __FILE__, 'karza_deactivate' );

// Add a menu item for the plugin
function karza_menu() {
    add_menu_page(
        'Karza GST', //page title
        'Karza GST', //menu title
        'manage_options', //capabilities
        'karza-list', //menu slug
        'karza_list',//function
        'dashicons-shield',//icon
        30
    );

    //adding submenu to a menu
    add_submenu_page('karza-list',//parent page slug
        'API Setting',//page title
        'API Setting',//menu title
        'manage_options',//manage optios
        'karza-setting',//slug
        'karza_setting'//function
    );

}
add_action('admin_menu', 'karza_menu');


// Define a function that calls add_action
function callback_handler_init()
{
    // Register the callback endpoint
    add_action('rest_api_init', function () {
        register_rest_route('gst/v1', '/callback/', array(
            'methods' => 'POST',
            'callback' => 'callback_handler',
            'permission_callback' => '__return_true',
        ));
    });
}
// Hook the function into the 'init' action
add_action('init', 'callback_handler_init');

// Define the callback function
function callback_handler($request)
{
    file_put_contents(WP_CONTENT_DIR . '/log-file.log', current_time('mysql'). print_r($request->get_params(), true));
    $response=callback_response_handler($request->get_params());
    file_put_contents(WP_CONTENT_DIR . '/log-file.log', current_time('mysql'). print_r(json_encode($response), true), FILE_APPEND);
    file_put_contents(WP_CONTENT_DIR . '/response-handler.log', current_time('mysql'). print_r(json_encode($response), true). "\n\n", FILE_APPEND);
    echo json_encode($response);
}


// returns the root directory path of particular plugin
define('KARZA_DIR', realpath(dirname(__FILE__)));
require_once(KARZA_DIR . '/karza_functions.php');