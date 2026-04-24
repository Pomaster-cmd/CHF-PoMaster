<?php
namespace CHFPoMaster\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manage JSON preset import and export.
 */
class Preset_Manager {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_submenu' ) );
		add_action( 'admin_post_chfp_import_preset', array( $this, 'handle_import' ) );
		add_action( 'admin_post_chfp_export_preset', array( $this, 'handle_export' ) );
	}

	/**
	 * Register the admin submenu.
	 *
	 * @return void
	 */
	public function register_submenu() {
		add_submenu_page(
			'edit.php?post_type=' . CHF_POMASTER_POST_TYPE,
			__( 'JSON Presets', 'chf-pomaster' ),
			__( 'JSON Presets', 'chf-pomaster' ),
			'edit_posts',
			'chfp-json-presets',
			array( $this, 'render_page' )
		);
	}

	/**
	 * Render the presets page.
	 *
	 * @return void
	 */
	public function render_page() {
		$templates = get_posts(
			array(
				'post_type'      => CHF_POMASTER_POST_TYPE,
				'post_status'    => array( 'publish', 'draft', 'private' ),
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);

		$import_status = isset( $_GET['chfp_import_status'] ) ? sanitize_key( wp_unslash( $_GET['chfp_import_status'] ) ) : '';
		$import_post   = isset( $_GET['chfp_import_post'] ) ? absint( $_GET['chfp_import_post'] ) : 0;
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'CHF JSON Presets', 'chf-pomaster' ); ?></h1>

			<?php if ( 'success' === $import_status && $import_post ) : ?>
				<div class="notice notice-success"><p>
					<?php
					printf(
						esc_html__( 'Preset imported successfully. Template ID: %d', 'chf-pomaster' ),
						(int) $import_post
					);
					?>
				</p></div>
			<?php elseif ( 'error' === $import_status ) : ?>
				<div class="notice notice-error"><p>
					<?php esc_html_e( 'Preset import failed. Please validate the JSON payload.', 'chf-pomaster' ); ?>
				</p></div>
			<?php endif; ?>

			<h2><?php esc_html_e( 'Import Preset', 'chf-pomaster' ); ?></h2>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php wp_nonce_field( 'chfp_import_preset', 'chfp_import_nonce' ); ?>
				<input type="hidden" name="action" value="chfp_import_preset" />

				<p>
					<textarea name="chfp_preset_json" rows="18" style="width:100%;font-family:monospace;" placeholder="<?php echo esc_attr( '{ "schema_version":"1.0.0", "title":"My Preset", "template_type":"header", "active":true, "conditions":["entire_site"], "elementor_data":[] }' ); ?>"></textarea>
				</p>

				<?php submit_button( __( 'Import JSON Preset', 'chf-pomaster' ) ); ?>
			</form>

			<hr />

			<h2><?php esc_html_e( 'Export Preset', 'chf-pomaster' ); ?></h2>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php wp_nonce_field( 'chfp_export_preset', 'chfp_export_nonce' ); ?>
				<input type="hidden" name="action" value="chfp_export_preset" />

				<p>
					<select name="chfp_export_post_id" class="regular-text">
						<option value=""><?php esc_html_e( 'Select a template', 'chf-pomaster' ); ?></option>
						<?php foreach ( $templates as $template ) : ?>
							<option value="<?php echo esc_attr( (string) $template->ID ); ?>">
								<?php echo esc_html( $template->post_title . ' (#' . $template->ID . ')' ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</p>

				<?php submit_button( __( 'Download JSON Preset', 'chf-pomaster' ) ); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Handle preset import.
	 *
	 * @return void
	 */
	public function handle_import() {
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_die( esc_html__( 'You are not allowed to do this.', 'chf-pomaster' ) );
		}

		check_admin_referer( 'chfp_import_preset', 'chfp_import_nonce' );

		$payload = isset( $_POST['chfp_preset_json'] ) ? wp_unslash( $_POST['chfp_preset_json'] ) : '';
		$post_id = $this->import_payload( $payload );

		if ( is_wp_error( $post_id ) ) {
			wp_safe_redirect(
				add_query_arg(
					array(
						'post_type'          => CHF_POMASTER_POST_TYPE,
						'page'               => 'chfp-json-presets',
						'chfp_import_status' => 'error',
					),
					admin_url( 'edit.php' )
				)
			);
			exit;
		}

		wp_safe_redirect(
			add_query_arg(
				array(
					'post_type'          => CHF_POMASTER_POST_TYPE,
					'page'               => 'chfp-json-presets',
					'chfp_import_status' => 'success',
					'chfp_import_post'   => (int) $post_id,
				),
				admin_url( 'edit.php' )
			)
		);
		exit;
	}

	/**
	 * Handle preset export.
	 *
	 * @return void
	 */
	public function handle_export() {
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_die( esc_html__( 'You are not allowed to do this.', 'chf-pomaster' ) );
		}

		check_admin_referer( 'chfp_export_preset', 'chfp_export_nonce' );

		$post_id = isset( $_POST['chfp_export_post_id'] ) ? absint( $_POST['chfp_export_post_id'] ) : 0;

		if ( ! $post_id || CHF_POMASTER_POST_TYPE !== get_post_type( $post_id ) ) {
			wp_die( esc_html__( 'Invalid template selected.', 'chf-pomaster' ) );
		}

		$payload = $this->export_payload( $post_id );

		nocache_headers();
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=chfp-preset-' . $post_id . '.json' );

		echo wp_json_encode( $payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		exit;
	}

	/**
	 * Import a raw JSON payload.
	 *
	 * @param string $payload Raw JSON payload.
	 * @return int|\WP_Error
	 */
	private function import_payload( $payload ) {
		if ( ! is_string( $payload ) || '' === trim( $payload ) ) {
			return new \WP_Error( 'empty_payload', __( 'Empty JSON payload.', 'chf-pomaster' ) );
		}

		$data = json_decode( $payload, true );

		if ( ! is_array( $data ) ) {
			return new \WP_Error( 'invalid_json', __( 'Invalid JSON.', 'chf-pomaster' ) );
		}

		if ( empty( $data['title'] ) || empty( $data['template_type'] ) || ! isset( $data['elementor_data'] ) ) {
			return new \WP_Error( 'invalid_schema', __( 'Invalid preset schema.', 'chf-pomaster' ) );
		}

		$template_type = sanitize_key( $data['template_type'] );
		$template_type = in_array( $template_type, array( 'header', 'footer' ), true ) ? $template_type : 'header';

		$conditions = isset( $data['conditions'] ) && is_array( $data['conditions'] ) ? array_map( 'sanitize_key', $data['conditions'] ) : array( 'entire_site' );
		$conditions = array_values( array_unique( $conditions ) );
		$active     = ! empty( $data['active'] ) ? '1' : '0';

		if ( empty( $conditions ) ) {
			$conditions = array( 'entire_site' );
		}

		if ( in_array( 'entire_site', $conditions, true ) ) {
			$conditions = array( 'entire_site' );
		}

		$post_id = wp_insert_post(
			array(
				'post_type'    => CHF_POMASTER_POST_TYPE,
				'post_status'  => 'publish',
				'post_title'   => sanitize_text_field( $data['title'] ),
				'post_content' => '',
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			return $post_id;
		}

		update_post_meta( $post_id, '_chfp_location', $template_type );
		update_post_meta( $post_id, '_chfp_active', $active );
		update_post_meta( $post_id, '_chfp_conditions', $conditions );

		update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
		update_post_meta( $post_id, '_elementor_template_type', 'wp-page' );
		update_post_meta( $post_id, '_elementor_version', defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '' );
		update_post_meta( $post_id, '_elementor_page_settings', array() );
		update_post_meta( $post_id, '_elementor_data', wp_slash( wp_json_encode( $data['elementor_data'] ) ) );

		return (int) $post_id;
	}

	/**
	 * Export a template as structured JSON.
	 *
	 * @param int $post_id Template ID.
	 * @return array
	 */
	private function export_payload( $post_id ) {
		$elementor_data = get_post_meta( $post_id, '_elementor_data', true );
		$elementor_data = is_string( $elementor_data ) ? json_decode( $elementor_data, true ) : $elementor_data;

		if ( ! is_array( $elementor_data ) ) {
			$elementor_data = array();
		}

		return array(
			'schema_version' => '1.0.0',
			'title'          => get_the_title( $post_id ),
			'template_type'  => get_post_meta( $post_id, '_chfp_location', true ) ?: 'header',
			'active'         => '1' === (string) get_post_meta( $post_id, '_chfp_active', true ),
			'conditions'     => get_post_meta( $post_id, '_chfp_conditions', true ) ?: array( 'entire_site' ),
			'elementor_data' => $elementor_data,
		);
	}
}
