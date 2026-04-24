<?php
namespace CHFPoMaster;

use CHFPoMaster\Admin\Admin;
use CHFPoMaster\Admin\Meta_Boxes;
use CHFPoMaster\Admin\Preset_Manager;
use CHFPoMaster\Core\Assets;
use CHFPoMaster\Core\Elementor_Integration;
use CHFPoMaster\Core\Frontend;
use CHFPoMaster\Core\Post_Type;
use CHFPoMaster\Core\Template_Resolver;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin orchestrator.
 */
final class Plugin {

	/**
	 * Singleton instance.
	 *
	 * @var Plugin|null
	 */
	private static $instance = null;

	/**
	 * Get singleton instance.
	 *
	 * @return Plugin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Plugin constructor.
	 */
	private function __construct() {
		$this->load_files();
		$this->boot_services();
	}

	/**
	 * Load all required class files.
	 *
	 * @return void
	 */
	private function load_files() {
		require_once CHF_POMASTER_PATH . 'includes/Core/class-assets.php';
		require_once CHF_POMASTER_PATH . 'includes/Core/class-post-type.php';
		require_once CHF_POMASTER_PATH . 'includes/Core/class-template-resolver.php';
		require_once CHF_POMASTER_PATH . 'includes/Core/class-frontend.php';
		require_once CHF_POMASTER_PATH . 'includes/Core/class-elementor-integration.php';

		require_once CHF_POMASTER_PATH . 'includes/Admin/class-admin.php';
		require_once CHF_POMASTER_PATH . 'includes/Admin/class-meta-boxes.php';
		require_once CHF_POMASTER_PATH . 'includes/Admin/class-preset-manager.php';

	}

	/**
	 * Boot plugin services.
	 *
	 * @return void
	 */
	private function boot_services() {
		$resolver = new Template_Resolver();

		new Assets();
		new Post_Type();
		new Meta_Boxes();
		new Frontend( $resolver );
		new Admin();
		new Preset_Manager();
		new Elementor_Integration();

		add_action( 'init', array( $this, 'load_textdomain' ) );
	}

	/**
	 * Load plugin translations.
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'chf-pomaster', false, dirname( plugin_basename( CHF_POMASTER_FILE ) ) . '/languages' );
	}
}
