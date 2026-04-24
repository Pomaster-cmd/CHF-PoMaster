<?php
namespace CHFPoMaster\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manage template settings metaboxes.
 */
class Meta_Boxes {

	/**
	 * Available display conditions.
	 *
	 * @var array
	 */
	private $conditions = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->conditions = array(
			'entire_site' => __( 'Entire Site', 'chf-pomaster' ),
			'front_page'  => __( 'Front Page', 'chf-pomaster' ),
			'blog_index'  => __( 'Blog Index', 'chf-pomaster' ),
			'singular'    => __( 'All Singular', 'chf-pomaster' ),
			'archive'     => __( 'All Archives', 'chf-pomaster' ),
			'search'      => __( 'Search Results', 'chf-pomaster' ),
			'404'         => __( '404 Page', 'chf-pomaster' ),
		);

		add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
		add_action( 'save_post_' . CHF_POMASTER_POST_TYPE, array( $this, 'save_meta_boxes' ) );
	}

	/**
	 * Register meta boxes.
	 *
	 * @return void
	 */
	public function register_meta_boxes() {
		add_meta_box(
			'chfp-template-settings',
			__( 'Template Settings', 'chf-pomaster' ),
			array( $this, 'render_settings_meta_box' ),
			CHF_POMASTER_POST_TYPE,
			'side',
			'high'
		);
	}

	/**
	 * Render the settings box.
	 *
	 * @param \WP_Post $post Post object.
	 * @return void
	 */
	public function render_settings_meta_box( $post ) {
		$location   = get_post_meta( $post->ID, '_chfp_location', true );
		$active     = get_post_meta( $post->ID, '_chfp_active', true );
		$conditions = get_post_meta( $post->ID, '_chfp_conditions', true );

		$location   = $location ? $location : 'header';
		$active     = (string) $active ? '1' : '';
		$conditions = is_array( $conditions ) ? $conditions : array( 'entire_site' );

		wp_nonce_field( 'chfp_save_meta_boxes', 'chfp_meta_nonce' );
		?>
		<p>
			<label for="chfp_location"><strong><?php esc_html_e( 'Location', 'chf-pomaster' ); ?></strong></label>
			<select name="chfp_location" id="chfp_location" class="widefat">
				<option value="header" <?php selected( $location, 'header' ); ?>><?php esc_html_e( 'Header', 'chf-pomaster' ); ?></option>
				<option value="footer" <?php selected( $location, 'footer' ); ?>><?php esc_html_e( 'Footer', 'chf-pomaster' ); ?></option>
			</select>
		</p>

		<p>
			<label>
				<input type="checkbox" name="chfp_active" value="1" <?php checked( $active, '1' ); ?> />
				<?php esc_html_e( 'Enable this template', 'chf-pomaster' ); ?>
			</label>
		</p>

		<p><strong><?php esc_html_e( 'Display Conditions', 'chf-pomaster' ); ?></strong></p>

		<?php foreach ( $this->conditions as $value => $label ) : ?>
			<p>
				<label>
					<input
						type="checkbox"
						name="chfp_conditions[]"
						value="<?php echo esc_attr( $value ); ?>"
						<?php checked( in_array( $value, $conditions, true ) ); ?>
					/>
					<?php echo esc_html( $label ); ?>
				</label>
			</p>
		<?php endforeach; ?>

		<p class="description">
			<?php esc_html_e( 'If "Entire Site" is checked, it overrides other conditions.', 'chf-pomaster' ); ?>
		</p>
		<?php
	}

	/**
	 * Save metabox values.
	 *
	 * @param int $post_id Post ID.
	 * @return void
	 */
	public function save_meta_boxes( $post_id ) {
		if ( ! isset( $_POST['chfp_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['chfp_meta_nonce'] ) ), 'chfp_save_meta_boxes' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$location   = isset( $_POST['chfp_location'] ) ? sanitize_key( wp_unslash( $_POST['chfp_location'] ) ) : 'header';
		$active     = isset( $_POST['chfp_active'] ) ? '1' : '0';
		$conditions = isset( $_POST['chfp_conditions'] ) ? (array) wp_unslash( $_POST['chfp_conditions'] ) : array( 'entire_site' );

		$location   = in_array( $location, array( 'header', 'footer' ), true ) ? $location : 'header';
		$conditions = array_map( 'sanitize_key', $conditions );
		$conditions = array_values( array_unique( $conditions ) );

		if ( empty( $conditions ) ) {
			$conditions = array( 'entire_site' );
		}

		if ( in_array( 'entire_site', $conditions, true ) ) {
			$conditions = array( 'entire_site' );
		}

		update_post_meta( $post_id, '_chfp_location', $location );
		update_post_meta( $post_id, '_chfp_active', $active );
		update_post_meta( $post_id, '_chfp_conditions', $conditions );
	}
}
