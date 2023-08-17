<?php
/*
Plugin Name:  Buyer Onboarding Form
Plugin URI: https://teqfocus.com
Description: A plugin to manage buyer onboarding information
Version: 1.1.0
Author: A Ali
Author Email: akbar@teqfocus.com
*/

// Create the table on plugin activation
function wp_buyer_onboarding_activate() {
    global $wpdb;

    $table_name1 = $wpdb->prefix . 'buyer_onboarding';
    $table_name2 = $wpdb->prefix . 'product_notification';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name1 (
        `id` int(10) AUTO_INCREMENT PRIMARY KEY,
        `product_groups` varchar(255) DEFAULT NULL,
        `sub_category` varchar(255) DEFAULT NULL,
        `brands` varchar(255) DEFAULT NULL,
        `grades` varchar(255) DEFAULT NULL,
        `contact_person` varchar(255) DEFAULT NULL,
        `whatsapp_no` varchar(255) DEFAULT NULL,
        `email` varchar(255) DEFAULT NULL,
        `tan_no` varchar(255) DEFAULT NULL,
        `gst_certificate` varchar(255) DEFAULT NULL,
        `state` varchar(255) DEFAULT NULL,
        `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
        `status` tinyint(4) NOT NULL DEFAULT 1
    ) $charset_collate;


    CREATE TABLE IF NOT EXISTS $table_name2 (
            `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `product_id` int NOT NULL,
            `product_name` varchar(255) NOT NULL,
            `category` varchar(255) NOT NULL,
            `brand` varchar(255) DEFAULT NULL,
            `quantity` double DEFAULT 0,
            `price` double DEFAULT 0,
            `notification` tinyint NULL DEFAULT 0,
            `last_update_on` timestamp default NOW() ON UPDATE CURRENT_TIMESTAMP,
            `last_update_by` int NOT NULL,
            `timestamp` timestamp DEFAULT NOW(),
            `status` tinyint NOT NULL DEFAULT 1
        ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    //create folder for store gst document inside uploads folder
    $upload_dir = wp_upload_dir();
    $folder_path = $upload_dir['basedir'] . '/wp-buyer-onboarding';

    if ( ! file_exists( $folder_path ) ) {
        wp_mkdir_p( $folder_path );
    }
}
register_activation_hook( __FILE__, 'wp_buyer_onboarding_activate' );


function wp_buyer_onboarding_deactivate() {
    // do nothing on deativation of plugin

}
register_deactivation_hook( __FILE__, 'wp_buyer_onboarding_deactivate' );

// Add a menu item for the plugin
function wp_buyer_onboarding_menu() {
    add_menu_page(
        'Buyer Onboarding', //page title
        'Buyer Onboarding', //menu title
        'manage_options', //capabilities
        'wp-buyer-onboarding-list', //menu slug
        'wp_buyer_onboarding_list',//function
        'dashicons-media-default',// icon
        30
    );

    //adding submenu to a menu
    add_submenu_page('wp-buyer-onboarding-list',//parent page slug
        'Add New',//page title
        'Add New',//menu title
        'manage_options',//manage optios
        'wp-buyer-onboarding-create',//slug
        'wp_buyer_onboarding_create'//function
    );
    
    //adding submenu to a menu
    // add_submenu_page('wp-buyer-onboarding-list',//parent page slug
    //     'Product List',//page title
    //     'Product List',//menu title
    //     'manage_options',//manage optios
    //     'wp-buyer-onboarding-product-list',//slug
    //     'wp_buyer_onboarding_product_list'//function
    // );
}
add_action('admin_menu', 'wp_buyer_onboarding_menu');


// returns the root directory path of particular plugin
define('ONBOARDING_DIR', realpath(dirname(__FILE__)));
require_once(ONBOARDING_DIR . '/functions.php');