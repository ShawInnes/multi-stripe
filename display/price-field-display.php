<?php

class Multi_Stripe_WooCommerce_Display {

	private $textfield_id;

	public function __construct() {
		$this->textfield_id = 'multi_currency_field';
	}

	public function init() {
//		add_action(
//			'woocommerce_product_options_pricing',
//			array( $this, 'custom_price' )
//		);
	}


	public function custom_price() {

		$teaser = get_post_meta( get_the_ID(), $this->textfield_id, true );
		if ( empty( $teaser ) ) {
			return;
		}

		echo esc_html( $teaser );
	}
}