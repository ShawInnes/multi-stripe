<?php
/**
 * Plugin Name: Multi-Stripe
 * Author: Shaw Innes
 *
 * @package Multi-Stripe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

defined( 'WPINC' ) || die;

define( 'WP_DEBUG', true );

// https://codex.wordpress.org/Writing_a_Plugin
// add_option($name, $value, $deprecated, $autoload);
// get_option($option);
// update_option($option_name, $newvalue);

// https://codex.wordpress.org/Adding_Administration_Menus

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	include_once 'admin/enable-field-admin.php';
	include_once 'admin/price-field-admin.php';
	include_once 'display/price-field-display.php';

	add_action( 'plugins_loaded', 'custom_field_start' );
}

function custom_field_start() {
	if ( is_admin() ) {
		$admin_enable = new Multi_Stripe_Enable_WooCommerce_Field();
		$admin_enable->init();
		$admin_price = new Multi_Stripe_Price_WooCommerce_Field();
		$admin_price->init();
	} else {
		$plugin_enable = new Multi_Stripe_Enable_WooCommerce_Field();
		$plugin_enable->init();
		$plugin_price = new Multi_Stripe_Price_WooCommerce_Field();
		$plugin_price->init();
	}
}

