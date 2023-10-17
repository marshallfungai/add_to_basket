<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://marshallfungai.github.io
 * @since      1.0.0
 *
 * @package    Add_to_basket
 * @subpackage Add_to_basket/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Add_to_basket
 * @subpackage Add_to_basket/public
 * @author     Fungai Marshall <marshall@devartists.com>
 */
class Add_to_basket_Public {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/add_to_basket-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/add_to_basket-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Registers all shortcodes at once
	 *
	 * @return [type] [description]
	 */

	public function register_shortcodes() {
		add_shortcode( 'add2basket', array( $this, 'list_basket' ) );
		//add_shortcode('Basket_item', 'display_add_to_bsket_content_shortcode');
	} // register_shortcodes()


	/**
	 * Displays all basket items
	 *
	 * @echo Title : Add To Basket Post type
	 * @echo Content : Add To Basket Post type
	 *
	 */

	function display_add_to_bsket_content_shortcode() {
		// Query and display custom post type content
		$args = array(
			'post_type' => $this->plugin_name,
			'posts_per_page' => -1, // Display all posts
		);
		$query = new WP_Query($args);

		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();
				// Display content here
				the_title();
				the_content();
			}
		} else {
			echo 'No Basket Items found.';
		}
		wp_reset_postdata();
	}
    //add_shortcode('custom_post_type_display', 'display_add_to_bsket_content_shortcode');


	/**
	 * Returns a post object of random quotes
	 *
	 * @param array $params An array of optional parameters
	 * quantity Number of quote posts to return
	 *
	 * @return object A post object
	 */

	public function get_basket_items($params) {
		$return = '';
		$args = array(
			'post_type' => $this->plugin_name,
			'posts_per_page' => $params,
			'orderby' => 'rand'
		);

		$query = new WP_Query( $args );

		if ( is_wp_error( $query ) ) {
			$return = 'Oops!...No posts for you!';
		} else {
			$return = $query->posts;
		}

		return $return;
	} // get_basket_items()



	/**
	 * Processes shortcode basketItems
	 *
	 * @param array $atts The attributes from the shortcode
	 *
	 *
	 * @return mixed $output Output of the buffer
	 */

	public function list_basket( $atts = array() ) {
		ob_start();

		$args = shortcode_atts( array(
			'num' => -1,
			'title' => '',),
			$atts
		);

		$items = $this->get_basket_items($args['num']);
		//var_dump(json_encode($items));

		if ( is_array( $items ) || is_object( $items ) ) {
			include ('partials/add_to_basket-public-display.php');
		} else {
			echo $items;
		}

		$output = ob_get_contents();
		ob_end_clean();

		return $output;

	} // get_basket_items()

}
