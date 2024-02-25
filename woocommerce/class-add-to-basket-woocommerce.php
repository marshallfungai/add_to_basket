<?php

class Add_To_Basket_Woocommerce extends WC_Payment_Gateway{

		/**
	 * The plugin options.
	 *
	 * @since 		1.0.0
	 * @access 		private
	 * @var 		string 			$options    The plugin options.
	 */
	private $options;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

 
    /**
	 * Constructor for the gateway.
	 */
	public function __construct() {

		$this->plugin_name = ADD_TO_BASKET_PLUGIN_NAME;
		$this->set_options();
		$this->setup_properties();
		$this->init_form_fields();
		$this->init_settings();

		// Get settings.
		$this->title              = $this->get_option( 'title' );
		$this->description        = $this->get_option( 'description' );
		$this->api_key            = $this->get_option( 'api_key' );
		$this->widget_id          = $this->get_option( 'widget_id' );
		$this->instructions       = $this->get_option( 'instructions' );
		$this->enable_for_methods = $this->get_option( 'enable_for_methods', array() );
		$this->enable_for_virtual = $this->get_option( 'enable_for_virtual', 'yes' ) === 'yes';

		// add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		// add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );
		// add_filter( 'woocommerce_payment_complete_order_status', array( $this, 'change_payment_complete_order_status' ), 10, 3 );

		// // Customer Emails.
		// add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
	}

	/**
	 * Setup general properties for the gateway.
	 */
	protected function setup_properties() {
		$this->id                 = $this->plugin_name;
		$this->icon               = apply_filters( 'woocommerce_a2b_icon', plugins_url('../assets/icon.png', __FILE__ ) );
		$this->method_title       = __( 'Add To Basket', 'add2basket' );
		$this->api_key            = __( 'Add API Key', 'add2basket' );
		$this->widget_id          = __( 'Add Widget ID', 'add2basket' );
		$this->method_description = __( 'Add to basket helps you aggregate all your payment gateways in one place.', 'add2basket' );
		$this->has_fields         = false;
	}

	/**
	 *  Init payment gateway
	 */
    public function add_to_basket_payment_init($gateways) {
        $gateways[] = 'Add_To_Basket_Woocommerce';
        return $gateways;
    }

	/**
	 * Process admin settings on save
	 */
	public function process_admin_options() {
		// Check Add to basket API key
		$api_key_id = 'woocommerce_' . $this->plugin_name . '_' . sanitize_text_field($_POST['api_key']);
		$api_key = isset($_POST[$api_key_id]) ? sanitize_text_field($_POST['$api_key_id']) : '';

		if (!$this->check_API_Key($api_key)) {
			WC_Admin_Settings::add_error(__('Option cannot be saved due to a specific condition.', 'your-text-domain'));
			return;
		}

		// Process and save other settings as needed
		$this->update_option('api_key', $api_key);
		$this->update_option('description', sanitize_text_field($_POST['description']));
		$this->update_option('widget_id', sanitize_text_field($_POST['widget_id']));
		$this->update_option('instructions', sanitize_text_field($_POST['instructions']));
		$this->update_option('enable_for_methods', $_POST['enable_for_methods']);

		// Save the settings (if needed)
		//$this->save_settings();

		// Add a notice to indicate that settings have been saved
		WC_Admin_Settings::add_message(__('Settings saved.', 'add2basket'));
	}


	protected function check_API_Key($s_apiKey, $i_can_import_products=true) {

		/// TODO: Check api key with add to basket server
		$data1 = array('client_key' => $s_apiKey);
        $args1 = array(
			'client_key' => $data1,
		);
        $login_api_url = 'https://api.addtobasket.net/ws/wp/set_login';
        $verifyResponse = wp_remote_post($login_api_url , $args1);

        if (is_wp_error($verifyResponse)) {
            return false; // Verification failed
        }
		$verifyBody = wp_remote_retrieve_body($verifyResponse);
        $o_access_key = json_decode($verifyBody, true);
		update_option( $this->plugin_name . '_a2b_access', $o_access_key );

		// import products
		if($i_can_import_products == 1) {
			$decodedData = json_decode($jsonData);

			$validateToAPI = new stdClass();
			$validateToAPI->ISTANBUL = $o_access_key['ISTANBUL'];
			$validateToAPI->NEWYORK = $o_access_key['NEWYORK'];
			$validateToAPI->SESS_BRIDGE = $o_access_key['SESS_BRIDGE'];
			$validateToAPI->LONDON = $o_access_key['LONDON'];
			$validateToAPI->FRANKFURT = $o_access_key['FRANKFURT'];

			$args2 = array(
				's_tahir' => array("userContent" => array($validateToAPI)),
			);

			//wp_die(json_encode($args2));
			$products_api_url = 'https://api.addtobasket.net/ws/wp/get_products';
            $productResponse = wp_remote_post($login_api_url , $args2);
			//wp_die(json_encode($productResponse['body']));
			if (is_wp_error($productResponse)) {
				return false; // Verification failed
			}
			$productsBody = wp_remote_retrieve_body($productResponse);
			$products = json_decode($productsBody, true);

			$this->sync_products_a2b_and_woocommerce($products);
			
		}

		$options = get_option($this->plugin_name.'-options');
		// if(isset($options['client-key'])){
		// 	//define('A2B_ACTIVE', true);
		// 	$this->clientValid = true;
		// 	return true;
		// }
		// //define('A2B_ACTIVE', false);
		// $this->clientValid = false;
		return true;
	}

	/**
	 * Sync products to A2B and Woommerce
	 */
	private function sync_products_a2b_and_woocommerce($products) {
		
		$new_post = array(
			'post_title' => 'Iphone XXI',
			'post_content' => '',
			'post_status' => 'public',
			'post_type' => $this->plugin_name
		);

		$post_id = wp_insert_post( $new_post );
		
		if( $post_id ){
			update_option( $this->plugin_name . '_a2b_products', $products ); // Save the products to the database (temporary)
			// Update custom field on the new post
			//update_post_meta( $post_id, 'my_custom_field', 'Hello!' );

			// $plugin_metaboxes = new Add_to_basket_Metaboxes( $this->plugin_name, ADD_TO_BASKET_VERSION );
			// $metas = $plugin_metaboxes->get_metabox_fields();
			// foreach ($metas as $meta) {
			// 	$name = $meta[0];
			// 	$type = $meta[1];
			// 	$new_value = $plugin_metaboxes->sanitizer($type, 'Some value');
			// 	update_post_meta($post_id, $name, $new_value);
			// } 
			
		} else {
			echo "Error, post not inserted";
		}
	}


	/**
	 * Initialise Gateway Settings Form Fields.
	 */
	public function init_form_fields() {
		$this->form_fields = array(
			'enabled'            => array(
				'title'       => __( 'Enable/Disable', 'add2basket' ),
				'label'       => __( 'Enable Add To Basket', 'add2basket' ),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no',
			),
			'title'              => array(
				'title'       => __( 'Title', 'add2basket' ),
				'type'        => 'text',
				'description' => __( 'Add To Basket method description that the customer will see on your checkout.', 'add2basket' ),
				'default'     => __( 'Add To Basket', 'add2basket' ),
				'desc_tip'    => true,
			),
			'api_key'             => array(
				'title'       => __( 'API Key', 'add2basket' ),
				'type'        => 'text',
				'description' => __( 'Add your API key', 'add2basket' ),
				'desc_tip'    => true,
			),
			'sync_products'             => array(
				'title'       => __( 'Sync Products', 'add2basket' ),
				'type'        => 'checkbox',
				'description' => __( 'Snyc products between "Add to basket" and "Woocommerce".', 'add2basket' ),
				//'desc_tip'    => __( 'The plugin will attempt to match products between woocommerce and add to basket.', 'add2basket' ),
				'desc_tip'    => true,
				'default'     => 'no',
			),
			'widget_id'           => array(
				'title'       => __( 'Widget ID', 'add2basket' ),
				'type'        => 'text',
				'description' => __( 'Add your Widget key', 'add2basket' ),
				'desc_tip'    => true,
			),
			'description'        => array(
				'title'       => __( 'Description', 'add2basket' ),
				'type'        => 'textarea',
				'description' => __( 'Add  to basket allows you to have multiple payments methods and also manage products from dedicated ecommerce platform', 'add2basket' ),
				'default'     => __( 'Add  to basket allows you to have multiple payments methods and also manage products from dedicated ecommerce platform', 'add2basket' ),
				'desc_tip'    => true,
			),
			'instructions'       => array(
				'title'       => __( 'Instructions', 'add2basket' ),
				'type'        => 'textarea',
				'description' => __( 'Instructions that will be added to the thank you page.', 'add2basket' ),
				'default'     => __( 'First login to <a href="addtobasket.com">addtobasket.com</a>,and get api key. We take care of the rest for you, to sync your products.', 'add2basket' ),
				'desc_tip'    => true,
			),
			'enable_for_methods' => array(
				'title'             => __( 'Enable for shipping methods', 'add2basket' ),
				'type'              => 'multiselect',
				'class'             => 'wc-enhanced-select',
				'css'               => 'width: 400px;',
				'default'           => '',
				'description'       => __( 'If A2B is only available for certain methods, set it up here. Leave blank to enable for all methods.', 'add2basket' ),
				'options'           => $this->load_shipping_method_options(),
				'desc_tip'          => true,
				'custom_attributes' => array(
					'data-placeholder' => __( 'Select shipping methods', 'add2basket' ),
				),
			),
			'enable_for_virtual' => array(
				'title'   => __( 'Accept for virtual orders', 'add2basket' ),
				'label'   => __( 'Accept Add To Basket if the order is virtual', 'add2basket' ),
				'type'    => 'checkbox',
				'default' => 'yes',
			),
		);
	}

	/**
	 * Sets the class variable $options
	 */
	private function set_options() {
		$this->options = get_option( $this->plugin_name . '-options' );
	} // set_options()

    

	/**
	 * Check If The Gateway Is Available For Use.
	 *
	 * @return bool
	 */
	public function is_available() {
		$order          = null;
		$needs_shipping = false;

		// Test if shipping is needed first.
		if ( WC()->cart && WC()->cart->needs_shipping() ) {
			$needs_shipping = true;
		} elseif ( is_page( wc_get_page_id( 'checkout' ) ) && 0 < get_query_var( 'order-pay' ) ) {
			$order_id = absint( get_query_var( 'order-pay' ) );
			$order    = wc_get_order( $order_id );

			// Test if order needs shipping.
			if ( 0 < count( $order->get_items() ) ) {
				foreach ( $order->get_items() as $item ) {
					$_product = $item->get_product();
					if ( $_product && $_product->needs_shipping() ) {
						$needs_shipping = true;
						break;
					}
				}
			}
		}

		$needs_shipping = apply_filters( 'woocommerce_cart_needs_shipping', $needs_shipping );

		// Virtual order, with virtual disabled.
		if ( ! $this->enable_for_virtual && ! $needs_shipping ) {
			return false;
		}

		// Only apply if all packages are being shipped via chosen method, or order is virtual.
		if ( ! empty( $this->enable_for_methods ) && $needs_shipping ) {
			$order_shipping_items            = is_object( $order ) ? $order->get_shipping_methods() : false;
			$chosen_shipping_methods_session = WC()->session->get( 'chosen_shipping_methods' );

			if ( $order_shipping_items ) {
				$canonical_rate_ids = $this->get_canonical_order_shipping_item_rate_ids( $order_shipping_items );
			} else {
				$canonical_rate_ids = $this->get_canonical_package_rate_ids( $chosen_shipping_methods_session );
			}

			if ( ! count( $this->get_matching_rates( $canonical_rate_ids ) ) ) {
				return false;
			}
		}

		return parent::is_available();
	}

	/**
	 * Checks to see whether or not the admin settings are being accessed by the current request.
	 *
	 * @return bool
	 */
	private function is_accessing_settings() {
		if ( is_admin() ) {
			// phpcs:disable WordPress.Security.NonceVerification
			if ( ! isset( $_REQUEST['page'] ) || 'wc-settings' !== $_REQUEST['page'] ) {
				return false;
			}
			if ( ! isset( $_REQUEST['tab'] ) || 'checkout' !== $_REQUEST['tab'] ) {
				return false;
			}
			if ( ! isset( $_REQUEST['section'] ) || 'addtobasket' !== $_REQUEST['section'] ) {
				return false;
			}
			// phpcs:enable WordPress.Security.NonceVerification

			return true;
		}

		return false;
	}

	/**
	 * Loads all of the shipping method options for the enable_for_methods field.
	 *
	 * @return array
	 */
	private function load_shipping_method_options() {
		// Since this is expensive, we only want to do it if we're actually on the settings page.
		if ( ! $this->is_accessing_settings() ) {
			return array();
		}

		$data_store = WC_Data_Store::load( 'shipping-zone' );
		$raw_zones  = $data_store->get_zones();

		foreach ( $raw_zones as $raw_zone ) {
			$zones[] = new WC_Shipping_Zone( $raw_zone );
		}

		$zones[] = new WC_Shipping_Zone( 0 );

		$options = array();
		foreach ( WC()->shipping()->load_shipping_methods() as $method ) {

			$options[ $method->get_method_title() ] = array();

			// Translators: %1$s shipping method name.
			$options[ $method->get_method_title() ][ $method->id ] = sprintf( __( 'Any &quot;%1$s&quot; method', 'add2basket' ), $method->get_method_title() );

			foreach ( $zones as $zone ) {

				$shipping_method_instances = $zone->get_shipping_methods();

				foreach ( $shipping_method_instances as $shipping_method_instance_id => $shipping_method_instance ) {

					if ( $shipping_method_instance->id !== $method->id ) {
						continue;
					}

					$option_id = $shipping_method_instance->get_rate_id();

					// Translators: %1$s shipping method title, %2$s shipping method id.
					$option_instance_title = sprintf( __( '%1$s (#%2$s)', 'add2basket' ), $shipping_method_instance->get_title(), $shipping_method_instance_id );

					// Translators: %1$s zone name, %2$s shipping method instance name.
					$option_title = sprintf( __( '%1$s &ndash; %2$s', 'add2basket' ), $zone->get_id() ? $zone->get_zone_name() : __( 'Other locations', 'add2basket' ), $option_instance_title );

					$options[ $method->get_method_title() ][ $option_id ] = $option_title;
				}
			}
		}

		return $options;
	}

	/**
	 * Converts the chosen rate IDs generated by Shipping Methods to a canonical 'method_id:instance_id' format.
	 *
	 * @since  3.4.0
	 *
	 * @param  array $order_shipping_items  Array of WC_Order_Item_Shipping objects.
	 * @return array $canonical_rate_ids    Rate IDs in a canonical format.
	 */
	private function get_canonical_order_shipping_item_rate_ids( $order_shipping_items ) {

		$canonical_rate_ids = array();

		foreach ( $order_shipping_items as $order_shipping_item ) {
			$canonical_rate_ids[] = $order_shipping_item->get_method_id() . ':' . $order_shipping_item->get_instance_id();
		}

		return $canonical_rate_ids;
	}

	/**
	 * Converts the chosen rate IDs generated by Shipping Methods to a canonical 'method_id:instance_id' format.
	 *
	 * @since  3.4.0
	 *
	 * @param  array $chosen_package_rate_ids Rate IDs as generated by shipping methods. Can be anything if a shipping method doesn't honor WC conventions.
	 * @return array $canonical_rate_ids  Rate IDs in a canonical format.
	 */
	private function get_canonical_package_rate_ids( $chosen_package_rate_ids ) {

		$shipping_packages  = WC()->shipping()->get_packages();
		$canonical_rate_ids = array();

		if ( ! empty( $chosen_package_rate_ids ) && is_array( $chosen_package_rate_ids ) ) {
			foreach ( $chosen_package_rate_ids as $package_key => $chosen_package_rate_id ) {
				if ( ! empty( $shipping_packages[ $package_key ]['rates'][ $chosen_package_rate_id ] ) ) {
					$chosen_rate          = $shipping_packages[ $package_key ]['rates'][ $chosen_package_rate_id ];
					$canonical_rate_ids[] = $chosen_rate->get_method_id() . ':' . $chosen_rate->get_instance_id();
				}
			}
		}

		return $canonical_rate_ids;
	}

	/**
	 * Indicates whether a rate exists in an array of canonically-formatted rate IDs that activates this gateway.
	 *
	 * @since  3.4.0
	 *
	 * @param array $rate_ids Rate ids to check.
	 * @return boolean
	 */
	private function get_matching_rates( $rate_ids ) {
		// First, match entries in 'method_id:instance_id' format. Then, match entries in 'method_id' format by stripping off the instance ID from the candidates.
		return array_unique( array_merge( array_intersect( $this->enable_for_methods, $rate_ids ), array_intersect( $this->enable_for_methods, array_unique( array_map( 'wc_get_string_before_colon', $rate_ids ) ) ) ) );
	}

	/**
	 * Process the payment and return the result.
	 *
	 * @param int $order_id Order ID.
	 * @return array
	 */
	public function process_payment( $order_id ) {
		$order = wc_get_order( $order_id );

		if ( $order->get_total() > 0 ) {
			$this->addtobasket_payment_processing( $order );
		}
	}

	private function addtobasket_payment_processing( $order ) {

		$total = intval( $order->get_total() );
		var_dump($total);

		$phone = esc_attr( $_POST['payment_number'] );
		$network_id = '1'; // mtn
		$reason = 'Test';

		$url = 'https://e.patasente.com/phantom-api/pay-with-patasente/' . $this->api_key . '/' . $this->widget_id . '?phone=' . $phone . '&amount=' . $total . '&mobile_money_company_id=' . $network_id . '&reason=' . 'Payment for Order: ' .$order_id;

		var_dump($url);

		$response = wp_remote_post( $url, array( 'timeout' => 45 ) );

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			return "Something went wrong: $error_message";
		}

		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			$order->update_status( apply_filters( 'woocommerce_a2b_process_payment_order_status', $order->has_downloadable_item() ? 'wc-invoiced' : 'processing', $order ), __( 'Payments pending.', 'add2basket' ) );
		}

		if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
			$response_body = wp_remote_retrieve_body( $response );
			var_dump($response_body['message']);
			if ( 'Thank you! Your payment was successful' === $response_body['message'] ) {
				$order->payment_complete();

				// Remove cart.
				WC()->cart->empty_cart();

				// Return thankyou redirect.
				return array(
					'result'   => 'success',
					'redirect' => $this->get_return_url( $order ),
				);
			}
		}
	}

	/**
	 * Output for the order received page.
	 */
	public function thankyou_page() {
		if ( $this->instructions ) {
			echo wp_kses_post( wpautop( wptexturize( $this->instructions ) ) );
		}
	}

	/**
	 * Change payment complete order status to completed for Add To Basket orders.
	 *
	 * @since  3.1.0
	 * @param  string         $status Current order status.
	 * @param  int            $order_id Order ID.
	 * @param  WC_Order|false $order Order object.
	 * @return string
	 */
	public function change_payment_complete_order_status( $status, $order_id = 0, $order = false ) {
		if ( $order && 'addtobasket' === $order->get_payment_method() ) {
			$status = 'completed';
		}
		return $status;
	}

	/**
	 * Add content to the WC emails.
	 *
	 * @param WC_Order $order Order object.
	 * @param bool     $sent_to_admin  Sent to admin.
	 * @param bool     $plain_text Email format: plain text or HTML.
	 */
	public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
		if ( $this->instructions && ! $sent_to_admin && $this->id === $order->get_payment_method() ) {
			echo wp_kses_post( wpautop( wptexturize( $this->instructions ) ) . PHP_EOL );
		}
	}
}