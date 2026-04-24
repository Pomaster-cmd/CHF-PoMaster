<?php
namespace CHFPoMaster\Core;

use CHFPoMaster\Elementor\Widgets\Nav_Menu_Widget;
use CHFPoMaster\Elementor\Widgets\Site_Logo_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Elementor integration layer.
 */
class Elementor_Integration {

	/**
	 * Boot hooks.
	 */
	public function __construct() {
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
	}

	/**
	 * Register a dedicated Elementor widget category.
	 *
	 * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager.
	 * @return void
	 */
	public function register_category( $elements_manager ) {
		if ( ! is_object( $elements_manager ) || ! method_exists( $elements_manager, 'add_category' ) ) {
			return;
		}

		$elements_manager->add_category(
			'chf-pomaster',
			array(
				'title' => __( 'CHF PoMaster', 'chf-pomaster' ),
				'icon'  => 'fa fa-plug',
			)
		);
	}

	/**
	 * Register plugin widgets.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 * @return void
	 */
	public function register_widgets( $widgets_manager ) {
		if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
			return;
		}

		require_once CHF_POMASTER_PATH . 'includes/Elementor/Widgets/class-site-logo-widget.php';
		require_once CHF_POMASTER_PATH . 'includes/Elementor/Widgets/class-nav-menu-widget.php';

		$widgets_manager->register( new Site_Logo_Widget() );
		$widgets_manager->register( new Nav_Menu_Widget() );
	}
}
