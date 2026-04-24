<?php
namespace CHFPoMaster\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Elementor widget for rendering WordPress menus.
 */
class Nav_Menu_Widget extends Widget_Base {

	/**
	 * Get widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'chfp_nav_menu';
	}

	/**
	 * Get widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'CHF Nav Menu', 'chf-pomaster' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-nav-menu';
	}

	/**
	 * Get widget category.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'chf-pomaster' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'chf-pomaster-widgets' );
	}

	/**
	 * Register widget controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Content', 'chf-pomaster' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'menu_id',
			array(
				'label'   => __( 'Menu', 'chf-pomaster' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->get_menu_options(),
			)
		);

		$this->add_responsive_control(
			'layout',
			array(
				'label'     => __( 'Layout', 'chf-pomaster' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'horizontal',
				'options'   => array(
					'horizontal' => __( 'Horizontal', 'chf-pomaster' ),
					'vertical'   => __( 'Vertical', 'chf-pomaster' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .chfp-nav-menu__items' => 'flex-direction: {{VALUE}};',
				),
				'selectors_dictionary' => array(
					'horizontal' => 'row',
					'vertical'   => 'column',
				),
			)
		);

		$this->add_responsive_control(
			'justify',
			array(
				'label'     => __( 'Justify', 'chf-pomaster' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'flex-start',
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Start', 'chf-pomaster' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'chf-pomaster' ),
						'icon'  => 'eicon-h-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'End', 'chf-pomaster' ),
						'icon'  => 'eicon-h-align-right',
					),
					'space-between' => array(
						'title' => __( 'Space Between', 'chf-pomaster' ),
						'icon'  => 'eicon-justify-space-between-h',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .chfp-nav-menu__items' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_wrapper',
			array(
				'label' => __( 'Wrapper', 'chf-pomaster' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'wrapper_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .chfp-nav-menu',
			)
		);

		$this->add_responsive_control(
			'wrapper_padding',
			array(
				'label'      => __( 'Padding', 'chf-pomaster' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .chfp-nav-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'wrapper_radius',
			array(
				'label'      => __( 'Border Radius', 'chf-pomaster' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 18,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .chfp-nav-menu' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'wrapper_border',
				'selector' => '{{WRAPPER}} .chfp-nav-menu',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'wrapper_shadow',
				'selector' => '{{WRAPPER}} .chfp-nav-menu',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_items',
			array(
				'label' => __( 'Items', 'chf-pomaster' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'gap',
			array(
				'label'      => __( 'Gap', 'chf-pomaster' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'default'    => array(
					'size' => 18,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .chfp-nav-menu__items' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'item_color',
			array(
				'label'     => __( 'Text Color', 'chf-pomaster' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .chfp-nav-menu a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'item_hover_color',
			array(
				'label'     => __( 'Hover Color', 'chf-pomaster' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .chfp-nav-menu a:hover, {{WRAPPER}} .chfp-nav-menu .current-menu-item > a, {{WRAPPER}} .chfp-nav-menu .current-menu-ancestor > a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'item_typography',
				'selector' => '{{WRAPPER}} .chfp-nav-menu a',
			)
		);

		$this->add_responsive_control(
			'item_padding',
			array(
				'label'      => __( 'Item Padding', 'chf-pomaster' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .chfp-nav-menu a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'item_radius',
			array(
				'label'      => __( 'Item Radius', 'chf-pomaster' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 12,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .chfp-nav-menu a' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'item_glass_background',
			array(
				'label'     => __( 'Item Background', 'chf-pomaster' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255,255,255,0.08)',
				'selectors' => array(
					'{{WRAPPER}} .chfp-nav-menu a' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'item_glass_hover_background',
			array(
				'label'     => __( 'Item Hover Background', 'chf-pomaster' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255,255,255,0.16)',
				'selectors' => array(
					'{{WRAPPER}} .chfp-nav-menu a:hover, {{WRAPPER}} .chfp-nav-menu .current-menu-item > a, {{WRAPPER}} .chfp-nav-menu .current-menu-ancestor > a' => 'background: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$menu_id  = ! empty( $settings['menu_id'] ) ? (int) $settings['menu_id'] : 0;

		if ( ! $menu_id ) {
			if ( $this->is_edit_mode() ) {
				echo '<div class="chfp-nav-menu__placeholder">' . esc_html__( 'Select a menu to render.', 'chf-pomaster' ) . '</div>';
			}
			return;
		}

		echo '<nav class="chfp-nav-menu" aria-label="' . esc_attr__( 'Header navigation', 'chf-pomaster' ) . '">';

		wp_nav_menu(
			array(
				'menu'        => $menu_id,
				'container'   => false,
				'fallback_cb' => false,
				'menu_class'  => 'chfp-nav-menu__items',
				'depth'       => 3,
				'echo'        => true,
			)
		);

		echo '</nav>';
	}

	/**
	 * Build menu options.
	 *
	 * @return array
	 */
	private function get_menu_options() {
		$options = array(
			'' => __( 'Select a menu', 'chf-pomaster' ),
		);

		$menus = wp_get_nav_menus();

		if ( empty( $menus ) || is_wp_error( $menus ) ) {
			return $options;
		}

		foreach ( $menus as $menu ) {
			$options[ $menu->term_id ] = $menu->name;
		}

		return $options;
	}

	/**
	 * Check whether the widget is rendered inside Elementor editor.
	 *
	 * @return bool
	 */
	private function is_edit_mode() {
		return class_exists( '\Elementor\Plugin' )
			&& isset( \Elementor\Plugin::$instance->editor )
			&& method_exists( \Elementor\Plugin::$instance->editor, 'is_edit_mode' )
			&& \Elementor\Plugin::$instance->editor->is_edit_mode();
	}
}
