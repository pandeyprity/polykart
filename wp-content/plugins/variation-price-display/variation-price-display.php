<?php
/**
 * Plugin Name: Variation Price Display Range for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/variation-price-display
 * Description: Adds lots of advanced options to control how you display the price for your WooCommerce variable products.
 * Author: WPXtension
 * Version: 1.3.7
 * Domain Path: /languages
 * Requires at least: 5.8
 * Tested up to: 6.2
 * Requires PHP: 7.2
 * WC requires at least: 5.5
 * WC tested up to: 7.7
 * Text Domain: variation-price-display
 * Author URI: https://wpxtension.com/
 */

defined( 'ABSPATH' ) or die( 'Keep Silent' );

if ( ! defined( 'VARIATION_PRICE_DISPLAY_PLUGIN_FILE' ) ) {
    define( 'VARIATION_PRICE_DISPLAY_PLUGIN_FILE', __FILE__ );
}


// Include the main class.
if ( ! class_exists( 'Variation_Price_Display', false ) ) {
    require_once dirname( __FILE__ ) . '/includes/class-variation-price-display.php';
}

// Require woocommerce admin message
function variation_price_display_wc_requirement_notice() {

    if ( ! class_exists( 'WooCommerce' ) ) {
        $text    = esc_html__( 'WooCommerce', 'variation-price-display' );
        $link    = esc_url( add_query_arg( array(
            'tab'       => 'plugin-information',
            'plugin'    => 'woocommerce',
            'TB_iframe' => 'true',
            'width'     => '640',
            'height'    => '500',
        ), admin_url( 'plugin-install.php' ) ) );
        $message = wp_kses( __( "<strong>Variation Price Display Range for WooCommerce</strong> is an add-on of ", 'variation-price-display' ), array( 'strong' => array() ) );

        printf( '<div class="%1$s"><p>%2$s <a class="thickbox open-plugin-details-modal" href="%3$s"><strong>%4$s</strong></a></p></div>', 'notice notice-error', $message, $link, $text );
    }
}

add_action( 'admin_notices', 'variation_price_display_wc_requirement_notice' );


/**
 * Returns the main instance.
 */

function variation_price_display() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid

    if ( ! class_exists( 'WooCommerce', false ) ) {
        return false;
    }

    if ( function_exists( 'variation_price_display_pro' ) ) {
        return variation_price_display_pro();
    }

    return Variation_Price_Display::instance();
}

add_action( 'plugins_loaded', 'variation_price_display' );