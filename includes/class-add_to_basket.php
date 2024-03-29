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
		$this->plugin_name = 'add_to_basket';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_metabox_hooks();
		$this->checkClientKey();
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
		require_once ADD_TO_BASKET_PATH. 'vendor/autoload.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once ADD_TO_BASKET_PATH . 'includes/class-add_to_basket-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once ADD_TO_BASKET_PATH. 'includes/class-add_to_basket-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once ADD_TO_BASKET_PATH . 'admin/class-add_to_basket-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once ADD_TO_BASKET_PATH . 'public/class-add_to_basket-public.php';

		/**
		 * The class responsible for defining all actions relating to metaboxes.
		 */
		require_once ADD_TO_BASKET_PATH . 'admin/class-add_to_basket-admin-metaboxes.php';

		/**
		 * The class responsible for sanitizing user input
		 */
		require_once ADD_TO_BASKET_PATH . 'includes/class-add_to_basket-sanitize.php';

		/**
		 * The class responsible for all global functions.
		 */
		require_once ADD_TO_BASKET_PATH . 'includes/add-to-basket-global-functions.php';

		$this->loader = new Add_to_basket_Loader();
		$this->sanitizer = new Add_to_basket_Sanitize();
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
	private function define_admin_hooks() {

		$plugin_admin = new Add_to_basket_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'fn_add_to_basket_admin' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' ); // Add menu page

        // Add Settings link to the plugin
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_sub_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_sections' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_fields' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new Add_to_basket_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );
	}

	/**
	 * Register all of the hooks related to metaboxes
	 *
	 * @since 		1.0.0
	 * @access 		private
	 */
	private function define_metabox_hooks() {

		$plugin_metaboxes = new Add_to_basket_Metaboxes( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'add_meta_boxes', $plugin_metaboxes, 'add_metaboxes' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_metaboxes, 'set_meta' );
		$this->loader->add_action( 'save_post', $plugin_metaboxes, 'validate_meta', 10, 2 );

	} // define_metabox_hooks()

	/**
	 *  Check if client key is valid.
	 *
	 * @since     1.0.0
	 *
	 */
	protected function checkClientKey() {
		$options = get_option($this->plugin_name.'-options');
		if(isset($options['client-key'])){
			//define('A2B_ACTIVE', true);
			$this->clientValid = true;
			return true;
		}
		//define('A2B_ACTIVE', false);
		$this->clientValid = false;
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
