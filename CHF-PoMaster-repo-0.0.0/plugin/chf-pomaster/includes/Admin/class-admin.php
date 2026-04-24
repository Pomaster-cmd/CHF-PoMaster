<?php
namespace CHFPoMaster\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin notices and lightweight admin integration.
 */
class Admin {

	/**
	 * Boot hooks.
	 */
	public function __construct() {
		add_action( 'admin_notices', array( $this, 'maybe_show_elementor_notice' ) );
	}

	/**
	 * Show a warning when Elementor is missing.
	 *
	 * @return void
	 */
	public function maybe_show_elementor_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( did_action( 'elementor/loaded' ) ) {
			return;
		}

		echo '<div class="notice notice-warning"><p>';
		echo esc_html__( 'CHF PoMaster requires Elementor Free to edit header and footer templates.', 'chf-pomaster' );
		echo '</p></div>';
	}
}
