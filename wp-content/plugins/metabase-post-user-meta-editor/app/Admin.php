<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/davidtowoju
 * @since      0.1.0
 *
 * @package    Metabase
 * @subpackage Metabase/admin
 */

namespace Metabase;

use function Metabase\Includes\view;

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Metabase
 * @subpackage Metabase/admin
 * @author     David Towoju <hello@figarts.co>
 */
class Admin {

	private $plugin_name;
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register post metabox
	 *
	 * @param string $post_type current post type.
	 * @return void
	 */
	public function register_post_metaboxes( $post_type ) {
		// Use filter to add post types that should be skipped
		$post_types = apply_filters( 'metabox-ignore-post-types', [] );

		if ( ! in_array( $post_type, $post_types ) ) {

			$this->enqueue_styles();
			$this->enqueue_scripts();

			add_meta_box(
				'metabase',
				__( 'Meta', 'sitepoint' ),
				[ $this, 'render' ],
				$post_type,
			);
		}
	}

	/**
	 * Register user metabox
	 *
	 * @param WP_User $user current user object.
	 * @return void
	 */
	public function register_user_metaboxes( $user ) {
		$this->enqueue_styles();
		$this->enqueue_scripts();

		$custom_fields    = get_user_meta( $user->ID );
		ksort($custom_fields);
		$custom_fields_tr = [];

		foreach ( $custom_fields as $key => $value ) {
			$custom_fields_tr[] = [
				'key'   => $key,
				'value' => array_map('maybe_unserialize', $value),
			];
		}

		$custom_fields = $custom_fields_tr;
		Includes\view(
			'user-meta',
			[
				'fields' => $custom_fields,
				'object' => $user->ID,
				'type'   => 'user',
			]
		);
	}

	/**
	 * Render post metabox
	 *
	 * @param WP_Post $post
	 * @return void
	 */
	public function render( $post ) {
		$custom_fields    = get_post_custom( $post->ID );
		ksort($custom_fields);

		$custom_fields_tr = [];
		foreach ( $custom_fields as $key => $value ) {
			$custom_fields_tr[] = [
				'key'   => $key,
				'value' => array_map('maybe_unserialize', $value),
			];
		}

		$custom_fields = $custom_fields_tr;
		Includes\view(
			'post-meta',
			[
				'fields' => $custom_fields,
				'object' => $post->ID,
				'type'   => 'post',
			]
		);
	}

	public function handle_edit() {
		// Nonce verification
		if ( ! isset( $_POST['security'] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['security'] ), 'trash-nonce' )
		) {
			wp_send_json_error( array( 'message' => 'Nonce is invalid.' ) );
		}

		// Authorization check
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => esc_html__('Not authorised to delete this meta', 'metabase') ] );
		}

		$field = isset( $_POST['field'] ) ? sanitize_text_field( wp_unslash( $_POST['field'] ) ) : [];
		if ( $field ) {
			$field = json_decode( $field, true );
		}

		$type   = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';
		$object = isset( $_POST['object'] ) ? absint( wp_unslash( $_POST['object'] ) ) : 0;
		if ( $object <= 0 ) {
			wp_send_json_error( [ 'message' => esc_html__('No object to delete', 'metabase') ] );
		}

		switch ( $type ) {
			case 'post':
				delete_post_meta( $object, $field['key'], maybe_serialize( $field['value'][0] ) );
				wp_send_json_success( [ 'message' => 'success' ] );
				break;

			case 'user':
				delete_user_meta( $object, $field['key'], maybe_serialize( $field['value'][0] ) );
				wp_send_json_success( [ 'message' => 'success' ] );
				break;

			default:
				break;
		}

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		$admin_css = METABASE_DIR_URL . 'resources/css/admin.css';
		wp_enqueue_style( $this->plugin_name, $admin_css, [], $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script(
			$this->plugin_name,
			METABASE_DIR_URL . 'resources/js/admin.js',
			array( 'wp-i18n', 'jquery-ui-datepicker' ),
			'1.0.1',
			true
		);

		wp_localize_script(
			$this->plugin_name,
			'metabaseJS',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'action'  => 'metabase',
				'nonce'   => wp_create_nonce( 'trash-nonce' ),
			)
		);
	}
}
