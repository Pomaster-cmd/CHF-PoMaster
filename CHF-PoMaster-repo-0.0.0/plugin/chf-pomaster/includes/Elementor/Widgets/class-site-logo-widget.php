<?php
namespace CHFPoMaster\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Elementor widget for rendering a site logo.
 */
class Site_Logo_Widget extends Widget_Base {

	/**
	 * Get widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'chfp_site_logo';
	}

	/**
	 * Get widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'CHF Site Logo', 'chf-pomaster' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-site-logo';
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
			'logo_source',
			array(
				'label'   => __( 'Logo Source', 'chf-pomaster' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'site_logo',
				'options' => array(
					'site_logo' => __( 'Site Logo', 'chf-pomaster' ),
					'custom'    => __( 'Custom Image', 'chf-pomaster' ),
				),
			)
		);

		$this->add_control(
			'custom_logo',
			array(
				'label'     => __( 'Custom Logo', 'chf-pomaster' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array(
					'logo_source' => 'custom',
				),
			)
		);

		$this->add_control(
			'fallback_text',
			array(
				'label'       => __( 'Fallback Text', 'chf-pomaster' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => get_bloginfo( 'name' ),
				'placeholder' => __( 'Site Name', 'chf-pomaster' ),
			)
		);

		$this->add_control(
			'link_to',
			array(
				'label'   => __( 'Link', 'chf-pomaster' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'home',
				'options' => array(
					'home'   => __( 'Homepage', 'chf-pomaster' ),
					'custom' => __( 'Custom URL', 'chf-pomaster' ),
					'none'   => __( 'None', 'chf-pomaster' ),
				),
			)
		);

		$this->add_control(
			'custom_url',
			array(
				'label'         => __( 'Custom URL', 'chf-pomaster' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => 'https://example.com',
				'show_external' => true,
				'condition'     => array(
					'link_to' => 'custom',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Logo', 'chf-pomaster' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'logo_width',
			array(
				'label'      => __( 'Width', 'chf-pomaster' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'vw' ),
				'default'    => array(
					'size' => 160,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .chfp-site-logo__image' => 'width: {{SIZE}}{{UNIT}}; max-width: 100%;',
				),
			)
		);

		$this->add_control(
			'fallback_color',
			array(
				'label'     => __( 'Fallback Text Color', 'chf-pomaster' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .chfp-site-logo__fallback' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'Alignment', 'chf-pomaster' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Left', 'chf-pomaster' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'chf-pomaster' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'Right', 'chf-pomaster' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'flex-start',
				'selectors' => array(
					'{{WRAPPER}} .chfp-site-logo' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'glass_background',
			array(
				'label'     => __( 'Glass Background', 'chf-pomaster' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255,255,255,0.10)',
				'selectors' => array(
					'{{WRAPPER}} .chfp-site-logo__link, {{WRAPPER}} .chfp-site-logo__fallback' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'glass_padding',
			array(
				'label'      => __( 'Padding', 'chf-pomaster' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', 'rem' ),
				'selectors'  => array(
					'{{WRAPPER}} .chfp-site-logo__link, {{WRAPPER}} .chfp-site-logo__fallback' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'glass_radius',
			array(
				'label'      => __( 'Border Radius', 'chf-pomaster' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 14,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .chfp-site-logo__link, {{WRAPPER}} .chfp-site-logo__fallback' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'glass_border',
				'selector' => '{{WRAPPER}} .chfp-site-logo__link, {{WRAPPER}} .chfp-site-logo__fallback',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'glass_shadow',
				'selector' => '{{WRAPPER}} .chfp-site-logo__link, {{WRAPPER}} .chfp-site-logo__fallback',
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
		$settings       = $this->get_settings_for_display();
		$link_url       = '';
		$link_attrs     = '';
		$logo_image_id  = 0;
		$fallback_text  = ! empty( $settings['fallback_text'] ) ? $settings['fallback_text'] : get_bloginfo( 'name' );
		$has_custom_url = ! empty( $settings['custom_url']['url'] );

		if ( 'site_logo' === $settings['logo_source'] ) {
			$logo_image_id = (int) get_theme_mod( 'custom_logo' );
		}

		if ( 'custom' === $settings['logo_source'] && ! empty( $settings['custom_logo']['id'] ) ) {
			$logo_image_id = (int) $settings['custom_logo']['id'];
		}

		if ( 'home' === $settings['link_to'] ) {
			$link_url = home_url( '/' );
		} elseif ( 'custom' === $settings['link_to'] && $has_custom_url ) {
			$link_url = $settings['custom_url']['url'];
		}

		if ( $link_url ) {
			$link_attrs = ' href="' . esc_url( $link_url ) . '"';

			if ( 'custom' === $settings['link_to'] && ! empty( $settings['custom_url']['is_external'] ) ) {
				$link_attrs .= ' target="_blank" rel="noopener noreferrer"';
			}
		}

		echo '<div class="chfp-site-logo">';

		if ( $logo_image_id ) {
			$image_html = wp_get_attachment_image(
				$logo_image_id,
				'full',
				false,
				array(
					'class' => 'chfp-site-logo__image',
				)
			);

			if ( $link_url ) {
				echo '<a class="chfp-site-logo__link"' . $link_attrs . '>' . $image_html . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				echo '<div class="chfp-site-logo__fallback-image">' . $image_html . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		} else {
			if ( $link_url ) {
				echo '<a class="chfp-site-logo__fallback"' . $link_attrs . '>' . esc_html( $fallback_text ) . '</a>';
			} else {
				echo '<div class="chfp-site-logo__fallback">' . esc_html( $fallback_text ) . '</div>';
			}
		}

		echo '</div>';
	}
}
