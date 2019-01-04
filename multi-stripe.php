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
}

add_action( 'plugins_loaded', 'custom_field_start' );
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

function cw_change_product_html( $price_html, $product ) {
	$multi_currency_enabled = get_post_meta( $product->id, 'multi_currency_field_checkbox', true );

	if ( ! empty( $multi_currency_enabled ) ) {
		$multi_currency = get_post_meta( $product->id, 'multi_currency_field', true );
		if ( ! empty( $multi_currency ) ) {
			$price_html = '<span class="amount">' . wc_price( $multi_currency, array( 'currency' => 'GBP' ) ) . '</span>';
		}
	}

	return $price_html;
}

add_filter( 'woocommerce_get_price_html', 'cw_change_product_html', 10, 2 );

function cw_change_product_price_cart( $price, $cart_item, $cart_item_key ) {
	$multi_currency_enabled = get_post_meta( $cart_item['product_id'], 'multi_currency_field_checkbox', true );

	if ( ! empty( $multi_currency_enabled ) ) {
		$multi_currency = get_post_meta( $cart_item['product_id'], 'multi_currency_field', true );
		if ( ! empty( $multi_currency ) ) {
			$price = wc_price( $multi_currency, array( 'currency' => 'GBP' ) );
		}
	}

	return $price;
}

add_filter( 'woocommerce_cart_item_price', 'cw_change_product_price_cart', 10, 3 );