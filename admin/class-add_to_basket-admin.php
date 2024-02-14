<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://marshallfungai.github.io
 * @since      1.0.0
 *
 * @package    Add_to_basket
 * @subpackage Add_to_basket/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Add_to_basket
 * @subpackage Add_to_basket/admin
 * @author     Fungai Marshall <marshall@devartists.com>
 */
class Add_to_basket_Admin {

	/**
	 * The plugin options.
	 *
	 * @since 		1.0.0
	 * @access 		private
	 * @var 		string 			$options    The plugin options.
	 */
	private $options;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The random integer id.
	 *
	 * @since        1.0.0
	 * @access       private
	 * @desc         integer id hedge against cache in dev mode.
	 * @var          integer    $randID   unique id .
	 */
	private $randID;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->randID = uniqid();
		$this->set_options();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Add_to_basket_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Add_to_basket_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$cacheBuster = $this->version;
		if(defined('WP_DEBUG') && WP_DEBUG){
			$cacheBuster = $this->randID;
		}

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/add_to_basket-admin.css', array(), $cacheBuster , 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Add_to_basket_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Add_to_basket_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$cacheBuster = $this->version;
		if(defined('WP_DEBUG') && WP_DEBUG){
			$cacheBuster = $this->randID;
		}
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/add_to_basket-admin.js', array( 'jquery' ), $cacheBuster, false );

	}

	
	/**
	 * Creates a text field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_text( $args ) { 
		
		
		$defaults['class'] 			= 'text widefat';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['placeholder'] 	= '';
		$defaults['type'] 			= 'text';
		
		$defaults['value'] 			= '' ;

		apply_filters( $this->plugin_name . '-field-text-options-defaults', $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];
			
		}

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-text.php' );

	} // field_text()


	/**
	 * Creates a checkbox field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_checkbox( $args ) {

		$defaults['class'] 			= '';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['value'] 			= 0;

		apply_filters( $this->plugin_name . '-field-checkbox-options-defaults', $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-checkbox.php' );

	} // field_checkbox()


	/**
	 * Creates an editor field
	 *
	 * NOTE: ID must only be lowercase letter, no spaces, dashes, or underscores.
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_editor( $args ) {

		$defaults['description'] 	= '';
		$defaults['settings'] 		= array( 'textarea_name' => $this->plugin_name . '-options[' . $args['id'] . ']' );
		$defaults['value'] 			= '';

		//apply_filters( $this->plugin_name . '-field-editor-options-defaults', $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-editor.php' );

	} // field_editor()


	/**
	 * Creates a set of radios field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_radios( $args ) {

		$defaults['class'] 			= '';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['value'] 			= 0;

		apply_filters( $this->plugin_name . '-field-radios-options-defaults', $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-radios.php' );

	} // field_radios()


	public function field_repeater( $args ) {

		$defaults['class'] 			= 'repeater';
		$defaults['fields'] 		= array();
		$defaults['id'] 			= '';
		$defaults['label-add'] 		= 'Add Item';
		$defaults['label-edit'] 	= 'Edit Item';
		$defaults['label-header'] 	= 'Item Name';
		$defaults['label-remove'] 	= 'Remove Item';
		$defaults['title-field'] 	= '';

		/*
				$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
		*/
		apply_filters( $this->plugin_name . '-field-repeater-options-defaults', $defaults );

		$setatts 	= wp_parse_args( $args, $defaults );
		$count 		= 1;
		$repeater 	= array();

		if ( ! empty( $this->options[$setatts['id']] ) ) {

			$repeater = maybe_unserialize( $this->options[$setatts['id']][0] );

		}

		if ( ! empty( $repeater ) ) {

			$count = count( $repeater );

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-repeater.php' );

	} // field_repeater()


	/**
	 * Creates a select field
	 *
	 * Note: label is blank since its created in the Settings API
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_select( $args ) {

		$defaults['aria'] 			= '';
		$defaults['blank'] 			= '';
		$defaults['class'] 			= 'widefat';
		$defaults['context'] 		= '';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['selections'] 	= array();
		$defaults['value'] 			= '';

		apply_filters( $this->plugin_name . '-field-select-options-defaults', $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		if ( empty( $atts['aria'] ) && ! empty( $atts['description'] ) ) {

			$atts['aria'] = $atts['description'];

		} elseif ( empty( $atts['aria'] ) && ! empty( $atts['label'] ) ) {

			$atts['aria'] = $atts['label'];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-select.php' );

	} // field_select()



	/**
	 * Creates a textarea field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_textarea( $args ) {

		$defaults['class'] 			= 'large-text';
		$defaults['cols'] 			= 50;
		$defaults['context'] 		= '';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['rows'] 			= 10;
		$defaults['value'] 			= '';

		apply_filters( $this->plugin_name . '-field-textarea-options-defaults', $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-textarea.php' );

	} 

	/**
	 * Creates a new custom post type
	 *
	 * @since 1.0.0
	 * @access public
	 * @uses register_post_type()
	 */
	public function fn_add_to_basket_admin() {
		$cap_type = 'post';
		$plural = 'A2B Item';
		$single = 'A2B Items';
		$cpt_name = $this->plugin_name;
		$opts['can_export'] = TRUE;
		$opts['capability_type'] = $cap_type;
		$opts['description'] = '';
		$opts['exclude_from_search'] = FALSE;
		$opts['has_archive'] = FALSE;
		$opts['hierarchical'] = FALSE;
		$opts['map_meta_cap'] = TRUE;
		$opts['menu_icon'] = 'dashicons-businessman';
		$opts['menu_position'] = 25;
		$opts['public'] = TRUE;
		$opts['publicly_querable'] = TRUE;
		$opts['query_var'] = TRUE;
		$opts['register_meta_box_cb'] = '';
		$opts['rewrite'] = FALSE;
		$opts['show_in_admin_bar'] = TRUE;
		$opts['show_in_menu'] = TRUE;
		$opts['show_in_nav_menu'] = TRUE;
		$opts['supports'] =  array('title', 'thumbnail', 'author');

		$opts['labels']['add_new'] = esc_html__( "Add New {$single}", 'add2basket' );
		$opts['labels']['add_new_item'] = esc_html__( "Add New {$single}", 'add2basket' );
		$opts['labels']['all_items'] = esc_html__( $plural, 'add2basket' );
		$opts['labels']['edit_item'] = esc_html__( "Edit {$single}" , 'add2basket' );
		$opts['labels']['menu_name'] = esc_html__( $plural, 'add2basket' );
		$opts['labels']['name'] = esc_html__( $plural, 'add2basket' );
		$opts['labels']['name_admin_bar'] = esc_html__( $single, 'add2basket' );
		$opts['labels']['new_item'] = esc_html__( "New {$single}", 'add2basket' );
		$opts['labels']['not_found'] = esc_html__( "No {$plural} Found", 'add2basket' );
		$opts['labels']['not_found_in_trash'] = esc_html__( "No {$plural} Found in Trash", 'add2basket' );
		$opts['labels']['parent_item_colon'] = esc_html__( "Parent {$plural} :", 'add2basket' );
		$opts['labels']['search_items'] = esc_html__( "Search {$plural}", 'add2basket' );
		$opts['labels']['singular_name'] = esc_html__( $single, 'add2basket' );
		$opts['labels']['view_item'] = esc_html__( "View {$single}", 'add2basket' );

		register_post_type( strtolower( $cpt_name ), $opts );
	} // new_cpt_job()

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since 1.0.0
	 */

	/**
	 * Adds a settings page link to a menu
	 *
	 * @link 		https://codex.wordpress.org/Administration_Menus
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function add_admin_sub_menu() {


		add_submenu_page(
			'edit.php?post_type='.$this->plugin_name,
			apply_filters( $this->plugin_name . '-settings-page-title', esc_html__( 'Add To Basket Settings', 'add2basket' ) ),
			apply_filters( $this->plugin_name . '-settings-menu-title', esc_html__( 'Settings', 'add2basket' ) ),
			'manage_options',
			$this->plugin_name . '-settings',
			array( $this, 'page_options' )
		);

		add_submenu_page(
			'edit.php?post_type='.$this->plugin_name,
			apply_filters( $this->plugin_name . '-settings-page-title', esc_html__( 'Add To Basket Help', 'add2basket' ) ),
			apply_filters( $this->plugin_name . '-settings-menu-title', esc_html__( 'Help', 'add2basket' ) ),
			'manage_options',
			$this->plugin_name . '-help',
			array( $this, 'page_help' )
		);

	} // add_menu()

	public function add_plugin_admin_menu() {
		add_options_page( 'ATB Options Settings', 'ATB Settings', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page'));
	}

	

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since 1.0.0
	 */

	public function add_action_links( $links ) {
		$settings_link = array(
			'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
		);
		return array_merge( $settings_link, $links );
	}

	
	/**
	 * Render the settings page for this plugin.
	 *
	 * @since 1.0.0
	 */

	public function display_plugin_setup_page() {

		include( plugin_dir_path( __FILE__ ) . 'partials/add_to_basket-admin-page-settings.php' );
	}


	/**
	 * Sets the class variable $options
	 */
	private function set_options() {
		$this->options = get_option( $this->plugin_name . '-options' );
	} // set_options()


	/**
	 * Registers settings fields with WordPress
	 */
	public function register_fields() {

		// add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );

		add_settings_field(
			'client_key',
			esc_html__( 'Client Key', 'add2basket' ),
			array( $this, 'field_text' ),
			$this->plugin_name , //page
			$this->plugin_name . '-options',  //section
			array(
				'label_for ' 	=> 'client_key',
				'description' 	=> 'Key to connect to "Add to basket" account given after creating seller account.',
				'id' 			=> 'client_key',
				'class' 	=> 'form-control',
				'placeholder' 	=> 'Enter client key',
				// 'value' 		=> 'Enter client key',
			)
		);

		add_settings_field(
			'listing-title-header-status',
			esc_html__( 'A2B Title status', 'add2basket' ),
			array( $this, 'field_checkbox' ),
			$this->plugin_name ,
			$this->plugin_name . '-options',
			array(
				'label_for ' 	=> 'listing-title-header-status',
				'description' 	=> 'Show/hide title on A2B listing page',
				'id' 			=> 'listing-title-header-status',
				'class' 	=> 'form-control',
				'default' 		=> '0',
				// 'value' 		=> 0,
			)
		);

		add_settings_field(
			'listing-title-header',
			esc_html__( 'A2B Title', 'add2basket' ),
			array( $this, 'field_text' ),
			$this->plugin_name ,
			$this->plugin_name . '-options',
			array(
				'label_for ' 	=> 'listing-title-header',
				'description' 	=> 'Title for A2B listing page',
				'id' 			=> 'listing-title-header',
				'class' 	    => 'form-control',
				'default' 		=> 'Instant Payment For You',
				// 'value' 		=> esc_html__( 'Instant Payment For You', 'add2basket' ),
			)
		);


	} // register_fields()

	/**
	 * Registers settings sections with WordPress
	 */
	public function register_sections() {

		add_settings_section(
			$this->plugin_name . '-options',
			apply_filters( $this->plugin_name . 'section-configs', esc_html__( 'Configs', 'add2basket' ) ),
			array( $this, 'section_configs' ),
			$this->plugin_name 
		);

	} // register_sections()

		/**
	 * Registers plugin settings
	 *
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function register_settings() {

		// register_setting( $option_group, $option_name, $sanitize_callback );
		register_setting(
			$this->plugin_name . '-options',
			$this->plugin_name . '-options',
			array( $this, 'validate_options' )
		);

	} // register_settings()


	/**
	 *  Verifies the settings with an external API
	 *
	 * @since 		1.0.0
	 * @param 		mixed 		$params 		iput that contains key for verificaion
	 * @return 		mixed 						The settings section
	 */

	private function verify_settings_with_api($input) {
	
		
        // Make your external API request here to verify the settings
        // If the verification is successful, return the verified data
        // If not, return false or an error message
		$data = array('client_key' => $input);
        $args = array(
			'body' => $data,
		);
        $api_url = 'https://api.addtobasket.net/ws/wp/set_login';
        $response = wp_remote_post($api_url, $args);

        if (is_wp_error($response)) {
            return false; // Verification failed
        }

        $body = wp_remote_retrieve_body($response);
        $verified_data = json_decode($body, true);

		//wp_die(print_r($verified_data));

        // You can add additional verification logic based on the API response
        // ...

        return $input; // Return verified data
    }


	/**
	 * Creates a settings section
	 *
	 * @since 		1.0.0
	 * @param 		array 		$params 		Array of parameters for the section
	 * @return 		mixed 						The settings section
	 */
	public function section_configs( $params ) {

		include( plugin_dir_path( __FILE__ ) . 'partials/add_to_basket-admin-section-configs.php' );

	} // section_configs()


	/**
	 * Creates the help page
	 *
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function page_help() {
		include(plugin_dir_path( __FILE__ ) .'partials/add_to_basket-admin-page-help.php');
	} // page_help()


	/**
	 * Creates the options page
	 *
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function page_options() {
		include( plugin_dir_path( __FILE__ ) . 'partials/add_to_basket-admin-page-settings.php' );
	} // page_options()


	/**
	 * Returns an array of options names, fields types, and default values
	 *
	 * @return 		array 			An array of options
	 */
	public static function get_options_list() {

		$options = array();

		$options[] = array( 'client_key', 'text', 'xxx' );
		$options[] = array( 'listing-title-header-status', 'checkbox', 0 );
		$options[] = array( 'listing-title-header', 'text', 'Instant Payments for you' );

		return $options;

	} // get_options_list()


	public function validate_options( $input ) {

		 // Validate and sanitize each individual option in the array
		// $input['client_key'] = sanitize_text_field($input['client_key']);
		$input['listing-title-header-status'] = intval($input['listing-title-header-status']);
		$input['listing-title-header'] = sanitize_text_field($input['listing-title-header']);

		$verified_data = $this->verify_settings_with_api(sanitize_text_field($input['client_key']));

        if ($verified_data !== false) {
            // If verification is successful, update the client_key with the verified data
            $input['client_key'] = $verified_data;
        } else {
            // If verification fails, display an error message
            add_settings_error(
                'client_key',
                'client_key_verification_failed',
                'Client Key verification failed. Please enter a valid key.',
                'error'
            );
        }

		
		  // Add more validations as needed
  
		return $input;


		// $valid 		= array();
		// $options 	= $this->get_options_list();
		
		// foreach ( $options as $option ) {

		// 	$name = $option[0];
		// 	$type = $option[1];

		// 	$valid[$option[0]] = $this->sanitizer( $type, $input[$name] );

		// 	/*if ( ! isset( $input[$option[0]] ) ) { continue; }

		// 	$sanitizer 	= new Add_to_basket_Sanitize();

		// 	$sanitizer->set_data( $input[$option[0]] );
		// 	$sanitizer->set_type( $option[1] );

		// 	$valid[$option[0]] = $sanitizer->clean();

		// 	if ( $valid[$option[0]] != $input[$option[0]] ) {

		// 		add_settings_error( $option[0], $option[0] . '_error', esc_html__( $option[0] . ' error.', 'now-hiring' ), 'error' );

		// 	}

		// 	unset( $sanitizer );
		// 		*/

		// }

		// return $valid;

	} // validate_options()

	
	private function sanitizer( $type, $unsanizited_data ) {

		if ( empty( $type ) ) { return; }
		if ( empty( $data ) ) { return; }
		

		$return 	= '';
		$sanitizer 	= new Add_to_basket_Sanitize();

		$sanitizer->set_data($data);
		$sanitizer->set_type($type);

		$return = $sanitizer->clean();

		unset( $sanitizer );

		return $return;

	} // sanitizer()

	

// field_textarea()

}
