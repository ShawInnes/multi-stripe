<?php

class Multi_Stripe_Enable_WooCommerce_Field {
	private $textfield_id;

	public function __construct() {
		$this->textfield_id = 'multi_currency_field_checkbox';
	}

	public function init() {

		add_action(
			'woocommerce_product_options_general_product_data',
			array( $this, 'product_options_grouping' )
		);

		add_action(
			'woocommerce_process_product_meta',
			array( $this, 'add_custom_linked_field_save' )
		);
	}

	public function product_options_grouping() {
		$cb_args = array(
			'id'    => $this->textfield_id,
			'label' => 'Enable GBP price?'
		);
		woocommerce_wp_checkbox( $cb_args );

	}

	public function add_custom_linked_field_save( $post_id ) {

		if ( ! ( isset( $_POST['woocommerce_meta_nonce'], $_POST[ $this->textfield_id ] ) || wp_verify_nonce( sanitize_key( $_POST['woocommerce_meta_nonce'] ), 'woocommerce_save_data' ) ) ) {
			return false;
		}

		$field_value = sanitize_text_field(
			wp_unslash( $_POST[ $this->textfield_id ] )
		);

		update_post_meta(
			$post_id,
			$this->textfield_id,
			esc_attr( $field_value )
		);
	}
}

