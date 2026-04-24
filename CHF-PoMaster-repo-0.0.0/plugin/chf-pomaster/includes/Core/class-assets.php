<?php
namespace CHFPoMaster\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register all plugin assets.
 */
class Assets {

	/**
	 * Boot hooks.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend_assets' ) );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'register_frontend_assets' ) );
	}

	/**
	 * Register reusable frontend assets.
	 *
	 * @return void
	 */
	public function register_frontend_assets() {
		wp_register_style(
			'chf-pomaster-frontend',
			CHF_POMASTER_URL . 'assets/css/frontend.css',
			array(),
			CHF_POMASTER_VERSION
		);

		wp_register_style(
			'chf-pomaster-widgets',
			CHF_POMASTER_URL . 'assets/css/widgets.css',
			array( 'chf-pomaster-frontend' ),
			CHF_POMASTER_VERSION
		);
	}
}
