<?php
namespace CHFPoMaster\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render templates on the frontend.
 */
class Frontend {

	/**
	 * Template resolver.
	 *
	 * @var Template_Resolver
	 */
	private $resolver;

	/**
	 * Header render guard.
	 *
	 * @var bool
	 */
	private $header_rendered = false;

	/**
	 * Footer render guard.
	 *
	 * @var bool
	 */
	private $footer_rendered = false;

	/**
	 * Constructor.
	 *
	 * @param Template_Resolver $resolver Template resolver.
	 */
	public function __construct( Template_Resolver $resolver ) {
		$this->resolver = $resolver;

		add_action( 'wp_body_open', array( $this, 'render_header' ), 20 );
		add_action( 'wp_footer', array( $this, 'render_footer' ), 5 );
	}

	/**
	 * Render the active header template.
	 *
	 * @return void
	 */
	public function render_header() {
		if ( $this->header_rendered || is_admin() || wp_doing_ajax() ) {
			return;
		}

		$template_id = $this->resolver->get_template_id( 'header' );

		if ( ! $template_id ) {
			return;
		}

		$this->header_rendered = true;
		$this->render_template( $template_id, 'header' );
	}

	/**
	 * Render the active footer template.
	 *
	 * @return void
	 */
	public function render_footer() {
		if ( $this->footer_rendered || is_admin() || wp_doing_ajax() ) {
			return;
		}

		$template_id = $this->resolver->get_template_id( 'footer' );

		if ( ! $template_id ) {
			return;
		}

		$this->footer_rendered = true;
		$this->render_template( $template_id, 'footer' );
	}

	/**
	 * Render a template by ID.
	 *
	 * @param int    $template_id Template ID.
	 * @param string $location    Template location.
	 * @return void
	 */
	private function render_template( $template_id, $location ) {
		wp_enqueue_style( 'chf-pomaster-frontend' );
		wp_enqueue_style( 'chf-pomaster-widgets' );

		echo '<div class="chfpomaster-template chfpomaster-template--' . esc_attr( $location ) . '" data-chfp-location="' . esc_attr( $location ) . '">';

		if ( did_action( 'elementor/loaded' ) && class_exists( '\Elementor\Plugin' ) ) {
			$frontend = \Elementor\Plugin::$instance->frontend;

			if ( is_object( $frontend ) && method_exists( $frontend, 'get_builder_content_for_display' ) ) {
				echo $frontend->get_builder_content_for_display( $template_id, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</div>';
				return;
			}
		}

		$post = get_post( $template_id );

		if ( $post instanceof \WP_Post ) {
			echo apply_filters( 'the_content', $post->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo '</div>';
	}
}
