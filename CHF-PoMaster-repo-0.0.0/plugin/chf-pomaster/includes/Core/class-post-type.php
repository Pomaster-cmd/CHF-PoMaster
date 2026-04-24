<?php
namespace CHFPoMaster\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the template custom post type.
 */
class Post_Type {

	/**
	 * Boot hooks.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_filter( 'manage_' . CHF_POMASTER_POST_TYPE . '_posts_columns', array( $this, 'register_columns' ) );
		add_action( 'manage_' . CHF_POMASTER_POST_TYPE . '_posts_custom_column', array( $this, 'render_columns' ), 10, 2 );
		add_filter( 'elementor/cpt_support', array( $this, 'add_elementor_support_filter' ) );
	}

	/**
	 * Register the builder template post type.
	 *
	 * @return void
	 */
	public function register_post_type() {
		$labels = array(
			'name'               => __( 'Header & Footer Templates', 'chf-pomaster' ),
			'singular_name'      => __( 'Header/Footer Template', 'chf-pomaster' ),
			'add_new'            => __( 'Add New', 'chf-pomaster' ),
			'add_new_item'       => __( 'Add New Template', 'chf-pomaster' ),
			'edit_item'          => __( 'Edit Template', 'chf-pomaster' ),
			'new_item'           => __( 'New Template', 'chf-pomaster' ),
			'view_item'          => __( 'View Template', 'chf-pomaster' ),
			'search_items'       => __( 'Search Templates', 'chf-pomaster' ),
			'not_found'          => __( 'No templates found.', 'chf-pomaster' ),
			'not_found_in_trash' => __( 'No templates found in trash.', 'chf-pomaster' ),
			'menu_name'          => __( 'CHF Builder', 'chf-pomaster' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'menu_position'      => 58,
			'menu_icon'          => 'dashicons-screenoptions',
			'supports'           => array( 'title', 'editor', 'revisions' ),
			'capability_type'    => 'post',
			'map_meta_cap'       => true,
			'show_in_rest'       => true,
		);

		register_post_type( CHF_POMASTER_POST_TYPE, $args );

		add_post_type_support( CHF_POMASTER_POST_TYPE, 'elementor' );
	}

	/**
	 * Add the custom post type to Elementor supported post types.
	 *
	 * @param array $post_types Supported post types.
	 * @return array
	 */
	public function add_elementor_support_filter( $post_types ) {
		if ( ! is_array( $post_types ) ) {
			$post_types = array();
		}

		$post_types[] = CHF_POMASTER_POST_TYPE;

		return array_values( array_unique( $post_types ) );
	}

	/**
	 * Register admin columns.
	 *
	 * @param array $columns Existing columns.
	 * @return array
	 */
	public function register_columns( $columns ) {
		$columns['chfp_location']   = __( 'Location', 'chf-pomaster' );
		$columns['chfp_active']     = __( 'Active', 'chf-pomaster' );
		$columns['chfp_conditions'] = __( 'Conditions', 'chf-pomaster' );

		return $columns;
	}

	/**
	 * Render admin columns.
	 *
	 * @param string $column  Column name.
	 * @param int    $post_id Post ID.
	 * @return void
	 */
	public function render_columns( $column, $post_id ) {
		if ( 'chfp_location' === $column ) {
			$location = get_post_meta( $post_id, '_chfp_location', true );
			echo esc_html( $location ? ucfirst( $location ) : '—' );
		}

		if ( 'chfp_active' === $column ) {
			$active = get_post_meta( $post_id, '_chfp_active', true );
			echo $active ? esc_html__( 'Yes', 'chf-pomaster' ) : esc_html__( 'No', 'chf-pomaster' );
		}

		if ( 'chfp_conditions' === $column ) {
			$conditions = get_post_meta( $post_id, '_chfp_conditions', true );
			$conditions = is_array( $conditions ) ? $conditions : array();

			if ( empty( $conditions ) ) {
				echo '—';
				return;
			}

			$labels = array_map(
				static function ( $condition ) {
					return ucwords( str_replace( '_', ' ', (string) $condition ) );
				},
				$conditions
			);

			echo esc_html( implode( ', ', $labels ) );
		}
	}
}
