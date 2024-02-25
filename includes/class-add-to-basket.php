<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://marshallfungai.github.io
 * @since      1.0.0
 *
 * @package    Add_to_basket
 * @subpackage Add_to_basket/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Add_to_basket
 * @subpackage Add_to_basket/includes
 * @author     Fungai Marshall <marshall@devartists.com>
 */
class Add_to_basket {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Add_to_basket_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * Define woocommerce class for global access.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Add_To_Basket_Woocommerce    
	 */
	protected $class_woocommerce;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Sanitizer for cleaning user input
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      Add_to_basket_Sanitize    $sanitizer    Sanitizes data
	 */
	private $sanitizer;

	/**
	 * Keeps track of client validity through client key
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      boolean    $clientValid    client key status
	 */
	protected $clientValid;



	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'ADD_TO_BASKET_VERSION' ) ) {
			$this->version = ADD_TO_BASKET_VERSION;
		} else {
			$this->version = '2.0.0';
		}
		$this->plugin_name = ADD_TO_BASKET_PLUGIN_NAME;

		$this->load_dependencies();
		//$this->set_locale();
		$this->define_woocommerce_hooks();
		//$this->checkClientKey();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Add_to_basket_Loader. Orchestrates the hooks of the plugin.
	 * - Add_to_basket_i18n. Defines internationalization functionality.
	 * - Add_to_basket_Admin. Defines all hooks for the admin area.
	 * - Add_to_basket_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {


		 // Load composer autoload
		//require_once ADD_TO_BASKET_PATH. 'vendor/autoload.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once ADD_TO_BASKET_PATH . 'includes/class-add-to-basket-loader.php';

		/**
		 * The class responsible for woocommerce functions
		 */
		require_once ADD_TO_BASKET_PATH . 'woocommerce/class-add-to-basket-woocommerce.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once ADD_TO_BASKET_PATH. 'includes/class-add-to-basket-i18n.php';

		/**
		 * The class responsible for sanitizing user input
		 */
		//require_once ADD_TO_BASKET_PATH . 'includes/class-add-to-basket-sanitize.php';

		$this->loader = new Add_to_basket_Loader();
		//$this->sanitizer = new Add_to_basket_Sanitize();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Add_to_basket_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Add_to_basket_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_woocommerce_hooks() {

        /*
       	add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );
		add_filter( 'woocommerce_payment_complete_order_status', array( $this, 'change_payment_complete_order_status' ), 10, 3 );

		// Customer Emails.  
		add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 ); 
        
        */

        $plugin_woocommerce = new Add_To_Basket_Woocommerce( $this->get_plugin_name(), $this->get_version() );
		
		$this->loader->add_action( 'woocommerce_payment_gateways', $plugin_woocommerce, 'add_to_basket_payment_init' );
		$this->loader->add_action( 'woocommerce_update_options_payment_gateways_'. $this->get_plugin_name() , $plugin_woocommerce, 'process_admin_options' );
		//$this->loader->add_action( 'admin_enqueue_scripts',  $plugin_woocommerce, 'enqueue_scripts' );

        // Add Settings link to the plugin
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
		//$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename,  $plugin_woocommerce, 'add_action_links' );
		
	}

    public function add_to_basket_payment_init($gateways) {
        $gateways[] = 'Add_To_Basket_Woocommerce';
        return $gateways;
    }


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Add_to_basket_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}