<?php
namespace CHFPoMaster\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Resolve the best matching template for the current request.
 */
class Template_Resolver {

	/**
	 * Find a matching template ID for a given location.
	 *
	 * @param string $location header|footer.
	 * @return int
	 */
	public function get_template_id( $location ) {
		$query = new \WP_Query(
			array(
				'post_type'              => CHF_POMASTER_POST_TYPE,
				'post_status'            => 'publish',
				'posts_per_page'         => -1,
				'orderby'                => array(
					'menu_order' => 'ASC',
					'date'       => 'DESC',
				),
				'meta_query'             => array(
					'relation' => 'AND',
					array(
						'key'   => '_chfp_location',
						'value' => $location,
					),
					array(
						'key'   => '_chfp_active',
						'value' => '1',
					),
				),
				'ignore_sticky_posts'    => true,
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			)
		);

		if ( empty( $query->posts ) ) {
			return 0;
		}

		foreach ( $query->posts as $post ) {
			if ( $this->matches_request( (int) $post->ID ) ) {
				return (int) $post->ID;
			}
		}

		return 0;
	}

	/**
	 * Check whether a template matches the current request.
	 *
	 * @param int $post_id Template ID.
	 * @return bool
	 */
	public function matches_request( $post_id ) {
		$conditions = get_post_meta( $post_id, '_chfp_conditions', true );
		$conditions = is_array( $conditions ) ? $conditions : array( 'entire_site' );

		if ( empty( $conditions ) || in_array( 'entire_site', $conditions, true ) ) {
			return true;
		}

		foreach ( $conditions as $condition ) {
			switch ( $condition ) {
				case 'front_page':
					if ( is_front_page() ) {
						return true;
					}
					break;

				case 'blog_index':
					if ( is_home() ) {
						return true;
					}
					break;

				case 'singular':
					if ( is_singular() ) {
						return true;
					}
					break;

				case 'archive':
					if ( is_archive() ) {
						return true;
					}
					break;

				case 'search':
					if ( is_search() ) {
						return true;
					}
					break;

				case '404':
					if ( is_404() ) {
						return true;
					}
					break;
			}
		}

		return false;
	}
}
