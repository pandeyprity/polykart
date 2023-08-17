<?php
/**
 * Plugin Name: WooCommerce Stock Notifications
 * Description: Sends email notifications when a product is back in stock.
 * Version: 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Activation hook
register_activation_hook(__FILE__, 'woocommerce_stock_notifications_activate');

function woocommerce_stock_notifications_activate() {
    // Add any activation tasks here
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'woocommerce_stock_notifications_deactivate');

function woocommerce_stock_notifications_deactivate() {
    // Add any deactivation tasks here
}

// Hook into the product stock status change
add_action('woocommerce_product_set_stock_status', 'send_stock_notification_emails', 10, 3);

function send_stock_notification_emails($product_id, $stock_status, $product) {
    // Check if the product is transitioning to "in stock"
    if ($stock_status === 'instock') {
        // Retrieve the list of email addresses from the custom field
        $email_list = get_post_meta($product_id, 'stock_notification_emails', true);

        if (!empty($email_list)) {
            $subject = 'Product Back in Stock';
            $message = 'The product ' . $product->get_name() . ' is back in stock.';

            // Send email to each email address on the list
            foreach ($email_list as $email) {
                wp_mail($email, $subject, $message);
            }

            // Clear the email list after sending notifications
            delete_post_meta($product_id, 'stock_notification_emails');
        }
    }
}

// Add a custom field to store email addresses for stock notifications
add_action('woocommerce_product_options_general_product_data', 'add_stock_notification_field');

function add_stock_notification_field() {
    global $post;

    echo '<div class="options_group">';
    woocommerce_wp_textarea_input(
        array(
            'id'          => 'stock_notification_emails',
            'label'       => __('Stock Notification Emails', 'text-domain'),
            'placeholder' => __('Enter email addresses for stock notifications, separated by commas', 'text-domain'),
            'description' => __('Customers who sign up for stock notifications will receive an email when this product is back in stock.', 'text-domain'),
            'value'       => get_post_meta($post->ID, 'stock_notification_emails', true),
        )
    );
    echo '</div>';
}

// Save stock notification email addresses
add_action('woocommerce_process_product_meta', 'save_stock_notification_emails');

function save_stock_notification_emails($post_id) {
    $emails = isset($_POST['stock_notification_emails']) ? sanitize_textarea_field($_POST['stock_notification_emails']) : '';
    update_post_meta($post_id, 'stock_notification_emails', explode(',', $emails));
}
