<?php
/**
 * Custom Email Template
 *
 * This template is used for custom email notifications.
 *
 * @package your-child-theme
 * @version 1.0
 */

// Prevent direct access to this file.
if (!defined('ABSPATH')) {
    exit;
}
$order = wc_get_order($order_id); // Get the order object

// Example: Display the customer's billing address
$billing_address = $order->get_formatted_billing_address();
echo '<p>' . $billing_address . '</p>';

// Add your own custom HTML and PHP code as needed
