<?php
/**
 * Cart Abandonment
 *
 * @package WATI-Chat-And-Notification
 */

/**
 * Cart abandonment tracking class.
 */
class WATI_Chat_And_Notification_Aband_Cart {



	/**
	 * Member Variable
	 *
	 * @var object instance
	 */
	private static $instance;

	/**
	 *  Initiator
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 *  Constructor function that initializes required actions and hooks.
	 */
	public function __construct() {

		$this->define_cart_abandonment_constants();

		add_action( 'admin_menu', array( $this, 'abandoned_cart_tracking_menu' ), 999 );
		add_action( 'admin_enqueue_scripts', array( $this, 'webhook_setting_script' ), 20 );
		add_action( 'woocommerce_after_checkout_form', array( $this, 'cart_abandonment_tracking_script' ) );

		//trigger abandoned checkout event
		add_action( 'wp_ajax_wati_cartflows_save_cart_abandonment_data', array( $this, 'save_cart_abandonment_data' ) );
		add_action( 'wp_ajax_nopriv_wati_cartflows_save_cart_abandonment_data', array( $this, 'save_cart_abandonment_data' ) );

		add_action( 'wp_ajax_wati_set_wordpress_domain_to_integration_service', array( $this, 'wati_set_wordpress_domain_to_integration_service' ) );
		add_action( 'wp_ajax_nopriv_wati_set_wordpress_domain_to_integration_service', array( $this, 'wati_set_wordpress_domain_to_integration_service' ) );
	
		add_action( 'wp_head', array( $this, 'whatsapp_chat_widget') );

		add_action( 'rest_api_init', function () {
			register_rest_route( 'api/v1', '/getWoocommerceInfo', array(
			  'methods' => 'GET',
			  'callback' => array( $this, 'getWoocommerceInfo' ),
			));
		});
		add_action( 'rest_api_init', function () {
			register_rest_route( 'api/v1', '/getAccessToken', array(
			  'methods' => 'GET',
			  'callback' => array( $this, 'getAccessToken' ),
			));
		});
		
		add_action( 'rest_api_init', function () {
			register_rest_route( 'api/v1', '/getOrderUrl', array(
			  'methods' => 'GET',
			  'callback' => array( $this, 'getOrderUrl' ),
			));
		});

    add_filter( 'jwt_auth_whitelist', function ( $endpoints ) {
        return array(
            '/wp-json/api/v1/getWoocommerceInfo',
            '/wp-json/api/v1/getOrderUrl',
            '/wp-json/api/v1/getAccessToken',
        );
    });

		add_filter( 'wp', array( $this, 'restore_cart_abandonment_data' ), 10 );		
		add_action( 'woocommerce_order_status_changed', array( $this, 'wati_ca_update_order_status' ), 999, 3 );
	}

	/**
	 *  Initialise all the constants
	 */
	public function define_cart_abandonment_constants() {
		define( 'WATI_CARTFLOWS_CART_ABANDONMENT_TRACKING_DIR', WP_WATI_DIR . 'modules/cart-abandonment/' );
		define( 'WATI_CARTFLOWS_CART_ABANDONMENT_TRACKING_URL', WP_WATI_URL . 'modules/cart-abandonment/' );
		define( 'WATI_CART_ABANDONED_ORDER', 'abandoned' );
		define( 'WATI_CART_COMPLETED_ORDER', 'completed' );
		define( 'WATI_CART_LOST_ORDER', 'lost' );
		define( 'WATI_CART_NORMAL_ORDER', 'normal' );
		define( 'WATI_CART_FAILED_ORDER', 'failed' );
		define( 'CARTFLOWS_ZAPIER_ACTION_AFTER_TIME', 1800 );

		define( 'WATI_ACTION_ABANDONED_CARTS', 'abandoned_carts' );
		define( 'WATI_ACTION_RECOVERED_CARTS', 'recovered_carts' );
		define( 'WATI_ACTION_LOST_CARTS', 'lost_carts' );
		define( 'WATI_ACTION_SETTINGS', 'settings' );
		define( 'WATI_ACTION_REPORTS', 'reports' );

		define( 'WATI_SUB_ACTION_REPORTS_VIEW', 'view' );
		define( 'WATI_SUB_ACTION_REPORTS_RESCHEDULE', 'reschedule' );

		define( 'WATI_DEFAULT_CUT_OFF_TIME', 15 );
		define( 'WATI_DEFAULT_COUPON_AMOUNT', 10 );

		define( 'WATI_CA_DATETIME_FORMAT', 'Y-m-d H:i:s' );

		define( 'WATI_CA_COUPON_DESCRIPTION', 'This coupon is for abandoned cart email templates.' );
		define( 'WATI_CA_COUPON_GENERATED_BY', 'wati-chat-and-notification' );
	}

	public function abandoned_cart_tracking_menu() {

		$capability = current_user_can( 'manage_woocommerce' ) ? 'manage_woocommerce' : 'manage_options';

		add_submenu_page(
			'woocommerce',
			__( 'WATI Chat & Notification', 'wati-chat-and-notification' ),
			__( 'WATI Chat & Notification', 'wati-chat-and-notification' ),
			$capability,
			WP_WATI_PAGE_NAME,
			array( $this, 'render_abandoned_cart_tracking' )
		);
	}

	public function render_abandoned_cart_tracking() {

		$api_key = $this->get_wati_setting_by_meta("api_key");
		$wati_domain = $this->get_wati_setting_by_meta("wati_domain");
		$wati_domain_front = $this->get_wati_setting_by_meta("wati_domain_front");

		$shop_name = $this->get_wati_setting_by_meta("shop_name");
		$email = $this->get_wati_setting_by_meta("email");
		$whatsapp_number = $this->get_wati_setting_by_meta("whatsapp_number");
		$code = $this->get_wati_setting_by_meta("code");
		$wati_setting_url = $wati_domain_front . "/registerWoocommerce?code=" . $code . "&wordpressDomain=" . get_home_url();

		if ($shop_name == "")
			$shop_name = $_SERVER['HTTP_HOST'];
		if ($email == ""){			
			global $current_user;			
			$current_user = wp_get_current_user();
			$email = (string) $current_user->user_email;
		}
			
		?>

		<?php
		include_once WATI_CARTFLOWS_CART_ABANDONMENT_TRACKING_DIR . 'includes/admin/wati-admin-settings.php';
		?>
		<?php
	}
	
	public function get_wati_setting_by_meta($meta_key) {
		global $wpdb;
		$wati_setting_table = $wpdb->prefix . WP_WATI_SETTING_TABLE;
		
		$res = $wpdb->get_row(
			$wpdb->prepare( "select * from $wati_setting_table where meta_key = %s", $meta_key ) // phpcs:ignore
		);

		if ( $res != null )
		{
			return $res->meta_value;
		}

		return null;
	}

	public function set_wati_setting_by_meta($input_meta_key, $input_meta_value) {
		global $wpdb;
		$wati_setting_tb       = $wpdb->prefix . WP_WATI_SETTING_TABLE;

		if ($input_meta_key == "integration_service_url") {
			$input_meta_value = "https://wati-integration-prod-service.clare.ai";
		}

		$meta_count = $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM $wati_setting_tb WHERE meta_key = %s ", $input_meta_key) );

		$meta_data = array(
			$input_meta_key  => $input_meta_value
		);

		if ( ( ! $meta_count ) ) {
			foreach ( $meta_data as $meta_key => $meta_value ) {
				$wpdb->query(
					$wpdb->prepare(
						"INSERT INTO $wati_setting_tb ( `meta_key`, `meta_value` ) 
						VALUES ( %s, %s )",
						$meta_key,
						$meta_value
					)
				);
			}
		} else {
			foreach ( $meta_data as $meta_key => $meta_value ) {				
				$wpdb->query(
					$wpdb->prepare(
						"UPDATE $wati_setting_tb SET meta_value = '$meta_value' WHERE meta_key = %s",
						$meta_key
					)
				);
			}
		}

		return true;

	}

	public function cart_abandonment_tracking_script() {
		$current_user        = wp_get_current_user();
		$roles               = $current_user->roles;
		$role                = array_shift( $roles );
		
		global $post;
		wp_enqueue_script(
			'wati-abandonment-tracking',
			WATI_CARTFLOWS_CART_ABANDONMENT_TRACKING_URL . 'assets/js/wati-abandonment-tracking.js',
			array( 'jquery' ),
			"1.0",
			true
		);

		$vars = array(
			'ajaxurl'                   => admin_url( 'admin-ajax.php' ),
			'_nonce'                    => wp_create_nonce( 'cartflows_save_cart_abandonment_data' ),
			'_post_id'                  => get_the_ID(),
			'_show_gdpr_message'        => false,
			'_gdpr_message'             => get_option( 'wcf_ca_gdpr_message' ),
			'_gdpr_nothanks_msg'        => __( 'No Thanks', 'wati-chat-and-notification' ),
			'_gdpr_after_no_thanks_msg' => __( 'You won\'t receive further emails from us, thank you!', 'wati-chat-and-notification' ),
			'enable_ca_tracking'        => true,
		);

		wp_localize_script( 'wati-abandonment-tracking', 'CartFlowsProCAVars', $vars );
	}

	public function webhook_setting_script() {
		$current_user        = wp_get_current_user();
		$roles               = $current_user->roles;
		$role                = array_shift( $roles );
		
		global $post;
		wp_enqueue_script(
			'webhook_setting_script',
			WATI_CARTFLOWS_CART_ABANDONMENT_TRACKING_URL . 'assets/js/webhook-setting.js',
			array( 'jquery' ),
			"1.0",
			true
		);

		$vars = array(
			'ajaxurl'                   => admin_url( 'admin-ajax.php' )
		);

		wp_localize_script( 'webhook_setting_script', 'WPVars', $vars );
	}

	public function save_cart_abandonment_data() {
		$post_data = $this->sanitize_post_data();
		if ( isset( $post_data['wcf_email'] ) ) {
			$user_email = sanitize_email( $post_data['wcf_email'] );
			global $wpdb;
			$cart_abandonment_table = $wpdb->prefix . WP_WATI_ABANDONMENT_TABLE;

			// Verify if email is already exists.
			$session_id               = WC()->session->get( 'wcf_session_id' );
			$session_checkout_details = null;
			if ( isset( $session_id ) ) {
				$session_checkout_details = $this->get_checkout_details( $session_id );
			} else {
				$session_checkout_details = $this->get_checkout_details_by_email( $user_email );
				if ( $session_checkout_details ) {
					$session_id = $session_checkout_details->session_id;
					WC()->session->set( 'wcf_session_id', $session_id );
				} else {
					$session_id = md5( uniqid( wp_rand(), true ) );
				}
			}

			$checkout_details = $this->prepare_abandonment_data( $post_data );

			if ( isset( $session_checkout_details ) && $session_checkout_details->order_status === "completed" ) {
				WC()->session->__unset( 'wcf_session_id' );
				$session_id = md5( uniqid( wp_rand(), true ) );
			}

			if ( isset( $checkout_details['cart_total'] ) && $checkout_details['cart_total'] > 0 ) {

				if ( ( ! is_null( $session_id ) ) && ! is_null( $session_checkout_details ) ) {

					// Updating row in the Database where users Session id = same as prevously saved in Session.
					$wpdb->update(
						$cart_abandonment_table,
						$checkout_details,
						array( 'session_id' => $session_id )
					);
					$this->webhook_abandonedCheckout_to_wati($session_id, '');
				} else {

					$checkout_details['session_id'] = sanitize_text_field( $session_id );
					// Inserting row into Database.
					$wpdb->insert(
						$cart_abandonment_table,
						$checkout_details
					);

					// Storing session_id in WooCommerce session.
					WC()->session->set( 'wcf_session_id', $session_id );
					$this->webhook_abandonedCheckout_to_wati($session_id, '');
				}
			}

			wp_send_json_success();
		}
	}

	public function wati_ca_update_order_status( $order_id, $old_order_status, $new_order_status ) {
		if ( ( WATI_CART_FAILED_ORDER === $new_order_status ) ) {
			return;
		}

		$session_id = null;

		if ( WC()->session ) {
			$session_id = WC()->session->get( 'wcf_session_id' );
		}

		if ( $order_id  && $session_id ) {

			$session_id = WC()->session->get( 'wcf_session_id' );
			$captured_data = $this->get_checkout_details( $session_id );
			if ( $captured_data ) {
				$captured_data->order_status = WATI_CART_COMPLETED_ORDER;
				$this->webhook_abandonedCheckout_to_wati($session_id, WATI_CART_COMPLETED_ORDER);
				
				global $wpdb;
				$cart_abandonment_table = $wpdb->prefix . WP_WATI_ABANDONMENT_TABLE;
				$wpdb->delete( $cart_abandonment_table, array( 'session_id' => sanitize_key( $session_id ) ) );
				if ( WC()->session ) {
					WC()->session->__unset( 'wcf_session_id' );
				}
			}
		}
	}

	public function restore_cart_abandonment_data( $fields = array() ) {
		global $woocommerce;
		$result = array();
		// Restore only of user is not logged in.
		$wcf_session_id = filter_input( INPUT_GET, 'session_id', FILTER_SANITIZE_STRING );
		$result = $this->get_checkout_details( $wcf_session_id );
		if ( isset( $result ) && (WATI_CART_ABANDONED_ORDER === $result->order_status || WATI_CART_LOST_ORDER === $result->order_status) ) {
			WC()->session->set( 'wcf_session_id', $wcf_session_id );
		}
		if ( $result ) {
			$cart_content = unserialize( $result->cart_contents );

			if ( $cart_content ) {
				$woocommerce->cart->empty_cart();
				wc_clear_notices();
				foreach ( $cart_content as $cart_item ) {

					$cart_item_data = array();
					$variation_data = array();
					$id             = $cart_item['product_id'];
					$qty            = $cart_item['quantity'];

					// Skip bundled products when added main product.
					if ( isset( $cart_item['bundled_by'] ) ) {
						continue;
					}

					if ( isset( $cart_item['variation'] ) ) {
						foreach ( $cart_item['variation']  as $key => $value ) {
							$variation_data[ $key ] = $value;
						}
					}

					$cart_item_data = $cart_item;

					$woocommerce->cart->add_to_cart( $id, $qty, $cart_item['variation_id'], $variation_data, $cart_item_data );
				}

				if ( isset( $token_data['wcf_coupon_code'] ) && ! $woocommerce->cart->applied_coupons ) {
					$woocommerce->cart->add_discount( $token_data['wcf_coupon_code'] );
				}
			}
			$other_fields = unserialize( $result->other_fields );

			$parts = explode( ',', $other_fields['wcf_location'] );
			if ( count( $parts ) > 1 ) {
				$country = $parts[0];
				$city    = trim( $parts[1] );
			} else {
				$country = $parts[0];
				$city    = '';
			}

			foreach ( $other_fields as $key => $value ) {
				$key           = str_replace( 'wcf_', '', $key );
				$_POST[ $key ] = sanitize_text_field( $value );
			}
			$_POST['billing_first_name'] = sanitize_text_field( $other_fields['wcf_first_name'] );
			$_POST['billing_last_name']  = sanitize_text_field( $other_fields['wcf_last_name'] );
			$_POST['billing_phone']      = sanitize_text_field( $other_fields['wcf_phone_number'] );
			$_POST['billing_email']      = sanitize_email( $result->email );
			$_POST['billing_city']       = sanitize_text_field( $city );
			$_POST['billing_country']    = sanitize_text_field( $country );

		}
		return $fields;
	}
	
	public function prepare_abandonment_data( $post_data = array() ) {

		if ( function_exists( 'WC' ) ) {

			// Retrieving cart total value and currency.
			$cart_total = WC()->cart->total;

			// Retrieving cart products and their quantities.
			$products     = WC()->cart->get_cart();
			$current_time = current_time( WATI_CA_DATETIME_FORMAT );
			$other_fields = array(
				'wcf_billing_company'     => $post_data['wcf_billing_company'],
				'wcf_billing_address_1'   => $post_data['wcf_billing_address_1'],
				'wcf_billing_address_2'   => $post_data['wcf_billing_address_2'],
				'wcf_billing_state'       => $post_data['wcf_billing_state'],
				'wcf_billing_postcode'    => $post_data['wcf_billing_postcode'],
				'wcf_shipping_first_name' => $post_data['wcf_shipping_first_name'],
				'wcf_shipping_last_name'  => $post_data['wcf_shipping_last_name'],
				'wcf_shipping_company'    => $post_data['wcf_shipping_company'],
				'wcf_shipping_country'    => $post_data['wcf_shipping_country'],
				'wcf_shipping_address_1'  => $post_data['wcf_shipping_address_1'],
				'wcf_shipping_address_2'  => $post_data['wcf_shipping_address_2'],
				'wcf_shipping_city'       => $post_data['wcf_shipping_city'],
				'wcf_shipping_state'      => $post_data['wcf_shipping_state'],
				'wcf_shipping_postcode'   => $post_data['wcf_shipping_postcode'],
				'wcf_order_comments'      => $post_data['wcf_order_comments'],
				'wcf_first_name'          => $post_data['wcf_name'],
				'wcf_last_name'           => $post_data['wcf_surname'],
				'wcf_phone_number'        => $post_data['wcf_phone'],
				'wcf_location'            => $post_data['wcf_country'] . ', ' . $post_data['wcf_city'],
			);

			$checkout_details = array(
				'email'         => $post_data['wcf_email'],
				'cart_contents' => serialize( $products ),
				'cart_total'    => sanitize_text_field( $cart_total ),
				'time'          => sanitize_text_field( $current_time ),
				'other_fields'  => serialize( $other_fields ),
				'checkout_id'   => $post_data['wcf_post_id'],
			);
		}
		return $checkout_details;
	}

	public function sanitize_post_data() {

		$input_post_values = array(
			'wcf_billing_company'     => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'wcf_email'               => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_EMAIL,
			),
			'wcf_billing_address_1'   => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'wcf_billing_address_2'   => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'wcf_billing_state'       => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'wcf_billing_postcode'    => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'wcf_shipping_first_name' => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'wcf_shipping_last_name'  => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'wcf_shipping_company'    => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'wcf_shipping_country'    => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'wcf_shipping_address_1'  => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'wcf_shipping_address_2'  => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'wcf_shipping_city'       => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'wcf_shipping_state'      => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'wcf_shipping_postcode'   => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'wcf_order_comments'      => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'wcf_name'                => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'wcf_surname'             => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'wcf_phone'               => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'wcf_country'             => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'wcf_city'                => array(
				'default'  => '',
				'sanitize' => FILTER_SANITIZE_STRING,
			),
			'wcf_post_id'             => array(
				'default'  => 0,
				'sanitize' => FILTER_SANITIZE_NUMBER_INT,
			),
		);

		$sanitized_post = array();
		foreach ( $input_post_values as $key => $input_post_value ) {

			if ( isset( $_POST[ $key ] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Missing
				$sanitized_post[ $key ] = filter_input( INPUT_POST, $key, $input_post_value['sanitize'] );
			} else {
				$sanitized_post[ $key ] = $input_post_value['default'];
			}
		}
		return $sanitized_post;

	}
	
	public function get_checkout_details( $wcf_session_id ) {
		global $wpdb;
		$cart_abandonment_table = $wpdb->prefix . WP_WATI_ABANDONMENT_TABLE;
		$result                 = $wpdb->get_row(
            $wpdb->prepare('SELECT * FROM `' . $cart_abandonment_table . '` WHERE session_id = %s AND order_status <> %s', $wcf_session_id, WATI_CART_COMPLETED_ORDER) // phpcs:ignore
		);
		return $result;
	}

	public function get_checkout_details_by_email( $email ) {
		global $wpdb;
		$cart_abandonment_table = $wpdb->prefix . WP_WATI_ABANDONMENT_TABLE;
		$result                 = $wpdb->get_row(
            $wpdb->prepare('SELECT * FROM `' . $cart_abandonment_table . '` WHERE email = %s AND `order_status` IN ( %s, %s )', $email, WATI_CART_ABANDONED_ORDER, WATI_CART_NORMAL_ORDER ) // phpcs:ignore
		);
		return $result;
	}

	public function wati_set_wordpress_domain_to_integration_service() {		
		$api_key = sanitize_text_field( $_POST['api_key'] );	
		$shop_name = sanitize_text_field( $_POST['shop_name'] );
		$whatsapp_number = sanitize_text_field( $_POST['whatsapp_number'] );
		$email = sanitize_email( $_POST['email'] );

		$url = $this->get_wati_setting_by_meta('integration_service_url') . "/api/v1/woocommerce/installPluginFromWordpress";

		$code = $this->rand_string(16);

		$this->set_wati_setting_by_meta("code", $code);
		$this->set_wati_setting_by_meta("shop_name", $shop_name);
		$this->set_wati_setting_by_meta("email", $email);
		$this->set_wati_setting_by_meta("whatsapp_number", $whatsapp_number);
		$this->set_wati_setting_by_meta("access_token", md5( uniqid( wp_rand(), true ) ));

		$data = array(
			'Id' => $api_key,
			'WordpressDomain' => get_home_url(),
			"Code" => $code
		);

		$options = [
			'body'        => json_encode($data),
			'headers'     => [
				'Content-Type' => 'application/json',
			],
			'timeout'     => 60,
			'redirection' => 5,
			'blocking'    => true,
			'httpversion' => '1.0',
			'sslverify'   => false,
			'data_format' => 'body',
		];
		
		$response = wp_remote_post( $url, $options );
		$response = json_decode( $response['body'] );
		if ($response && $response->result)
		{
			$this->set_wati_setting_by_meta("wati_domain", $response->watiDomain);
			$this->set_wati_setting_by_meta("wati_domain_front", $response->watiDomainFront);
			$this->set_wati_setting_by_meta("api_key", $api_key);
			$this->save_webhook_url($response->watiDomain);
			wp_send_json_success($response);
		}
		else{
			wp_send_json_success();
		}

	}

	public function webhook_abandonedCheckout_to_wati($session_id, $order_status) {
		$checkoutDetails = $this->get_checkout_details($session_id);

		$url = $this->get_wati_setting_by_meta('wati_domain') . "/api/v1/woocommerce/webhookCheckout";		
			
		$other_fields = unserialize( $checkoutDetails->other_fields );

		$parts = explode( ',', $other_fields['wcf_location'] );
		if ( count( $parts ) > 1 ) {
			$country = $parts[0];
		} else {
			$country = $parts[0];
		}

		$data = array(
			'sessionId' => $checkoutDetails->session_id,
			'email' => $checkoutDetails->email,
			'phone' => sanitize_text_field( $other_fields['wcf_phone_number'] ),
			'country' => sanitize_text_field( $country ),
			'name' => sanitize_text_field( $other_fields['wcf_first_name'] ).' '.sanitize_text_field( $other_fields['wcf_last_name'] ),
			'total' => $checkoutDetails->cart_total,
			'status' => ($order_status == '' ? $checkoutDetails->order_status : $order_status),
			'checkoutUrl' => get_permalink( $checkoutDetails->checkout_id ) . '?session_id=' . $checkoutDetails->session_id,
			'currency' => get_woocommerce_currency(),
			'wordpressDomain' => get_home_url()
		);
		
		$options = [
			'body'        => json_encode($data),
			'headers'     => [
				'Content-Type' => 'application/json',
			],
			'timeout'     => 60,
			'redirection' => 5,
			'blocking'    => true,
			'httpversion' => '1.0',
			'sslverify'   => false,
			'data_format' => 'body',
		];
		
		$response = wp_remote_post( $url, $options );

		return true;
	}

	public function getWoocommerceInfo() {
		$accessToken = sanitize_text_field( $_GET['accessToken'] );
		if ($accessToken == $this->get_wati_setting_by_meta('access_token')){
			return array(
				"currency" => get_woocommerce_currency(),
				"shopName" => $this->get_wati_setting_by_meta('shop_name'),
				"email" => $this->get_wati_setting_by_meta('email'),
				"whatsappNumber" => $this->get_wati_setting_by_meta('whatsapp_number'),
				"pluginActivated" => $this->get_wati_setting_by_meta('plugin_activated')
			);
		} else {
			return null;
		}
	}
	
	public function getAccessToken() {
		
		$code = sanitize_text_field( $_GET['code'] );
		if ($code == $this->get_wati_setting_by_meta('code')){
			$access_token = $this->get_wati_setting_by_meta("access_token");
			return array(
				"Access_Token" => $this->get_wati_setting_by_meta("access_token"),
				"WoocommerceInfo" => array(
					"Currency" => get_woocommerce_currency(),
					"ShopName" => $this->get_wati_setting_by_meta("shop_name"),
					"WhatsappNumber" => $this->get_wati_setting_by_meta("whatsapp_number"),
					"Email" => $this->get_wati_setting_by_meta("email")
				)
			);
		} else {
			return null;
		}
	}

	public function getOrderUrl(){
		$accessToken = sanitize_text_field( $_GET['accessToken'] );
		$order_id = sanitize_text_field( $_GET['order_id'] );
		if ($accessToken == $this->get_wati_setting_by_meta('access_token')){
			$order = wc_get_order($order_id);

			if (!$order){
				return null;
			}

			return array(
				"order_url" => $order->get_checkout_order_received_url()
			);
		} else {
			return null;
		}
	}
	
	public function save_webhook_url($watiDomain) {		
		$userID = get_current_user_id();

		$webhook = new WC_Webhook($this->get_wati_setting_by_meta("webhook_order_deleted_id"));
		$webhook->set_user_id($userID); 
		$webhook->set_topic( "order.deleted" ); 
		$webhook->set_secret( "secret" ); 
		$webhook->set_delivery_url( $watiDomain . "/api/v1/woocommerce/webhookOrders/order_deleted" ); 
		$webhook->set_status( "active" ); 
		$webhook->set_name( "WP_WATI_ORDER_DELETED" );
		$this->set_wati_setting_by_meta("webhook_order_deleted_id", $webhook->save());

		$webhook = new WC_Webhook($this->get_wati_setting_by_meta("webhook_order_updated_id"));
		$webhook->set_user_id($userID); 
		$webhook->set_topic( "order.updated" ); 
		$webhook->set_secret( "secret" ); 
		$webhook->set_delivery_url( $watiDomain . "/api/v1/woocommerce/webhookOrders/order_updated" ); 
		$webhook->set_status( "active" ); 
		$webhook->set_name( "WP_WATI_ORDER_UPDATED" );
		$this->set_wati_setting_by_meta("webhook_order_updated_id", $webhook->save());
		
		$webhook = new WC_Webhook($this->get_wati_setting_by_meta("webhook_order_created_id"));
		$webhook->set_user_id($userID); 
		$webhook->set_topic( "order.created" ); 
		$webhook->set_secret( "secret" ); 
		$webhook->set_delivery_url( $watiDomain . "/api/v1/woocommerce/webhookOrders/order_created" ); 
		$webhook->set_status( "active" ); 
		$webhook->set_name( "WP_WATI_ORDER_CREATED" );
		$this->set_wati_setting_by_meta("webhook_order_created_id", $webhook->save());
	}
	
	public function disable_webhook_url($watiDomain) {
		$userID = get_current_user_id();

		$webhook = new WC_Webhook($this->get_wati_setting_by_meta("webhook_order_deleted_id"));
		$webhook->set_user_id($userID); 
		$webhook->set_topic( "order.deleted" ); 
		$webhook->set_secret( "secret" ); 
		$webhook->set_delivery_url( $watiDomain . "/api/v1/woocommerce/webhookOrders/order_deleted" ); 
		$webhook->set_status( "disabled" ); 
		$webhook->set_name( "WP_WATI_ORDER_DELETED" );
		$this->set_wati_setting_by_meta("webhook_order_deleted_id", $webhook->save());

		$webhook = new WC_Webhook($this->get_wati_setting_by_meta("webhook_order_updated_id"));
		$webhook->set_user_id($userID); 
		$webhook->set_topic( "order.updated" ); 
		$webhook->set_secret( "secret" ); 
		$webhook->set_delivery_url( $watiDomain . "/api/v1/woocommerce/webhookOrders/order_updated" ); 
		$webhook->set_status( "disabled" ); 
		$webhook->set_name( "WP_WATI_ORDER_UPDATED" );
		$this->set_wati_setting_by_meta("webhook_order_updated_id", $webhook->save());
		
		$webhook = new WC_Webhook($this->get_wati_setting_by_meta("webhook_order_created_id"));
		$webhook->set_user_id($userID); 
		$webhook->set_topic( "order.created" ); 
		$webhook->set_secret( "secret" ); 
		$webhook->set_delivery_url( $watiDomain . "/api/v1/woocommerce/webhookOrders/order_created" ); 
		$webhook->set_status( "disabled" ); 
		$webhook->set_name( "WP_WATI_ORDER_CREATED" );
		$this->set_wati_setting_by_meta("webhook_order_created_id", $webhook->save());
	}

	function whatsapp_chat_widget() {
		if ($this->get_wati_setting_by_meta('wati_domain')){
			$url = $this->get_wati_setting_by_meta('wati_domain') . "/api/v1/woocommerce/whatsappChatScript?wordpressDomain=" . get_home_url();
			$options = [
				'headers'     => [
					'Content-Type' => 'application/json',
				],
				'timeout'     => 60,
				'redirection' => 5,
				'blocking'    => true,
				'httpversion' => '1.0',
				'sslverify'   => false,
				'data_format' => 'body',
			];
			$response = wp_remote_get( $url, $options );
			if (isset($response->errors)){
				return;
			}
			if (isset($response['body'])){
				echo '<script>' . $response['body']  . '</script>';
			}
		}
	}
	public function rand_string( $length ) {  
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";  
		$size = strlen( $chars );  
		$str = "";
		for( $i = 0; $i < $length; $i++ ) {  
			$str .= $chars[ rand( 0, $size - 1 ) ];
		}
		return $str;
	}
}

WATI_Chat_And_Notification_Aband_Cart::get_instance();
