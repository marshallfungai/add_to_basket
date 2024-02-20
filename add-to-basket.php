<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://marshallfungai.github.io
 * @since             1.0.0
 * @package           add2basket
 *
 * @wordpress-plugin
 * Plugin Name:       Add To Basket : Instant Payments (Woocommerce Extension)
 * Plugin URI:        https://addtobasket.net/
 * Description:       Woocommerce plugin from Add To Basket gateway.
 * Version:           1.0.0
 * Author:            Fungai Marshall, Gunes Erdemi
 * Author URI:        https://marshallfungai.github.io/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       addtobasket
 * Domain Path:       /languages
 */

 
if ( ! defined( 'WPINC' ) ) die;     // If this file is called directly, abort.
// Define plugin globals
//define('ADD_TO_BASKET_ACTIVE', false);
define( 'ADD_TO_BASKET_VERSION', '1.0.0' );
define( 'ADD_TO_BASKET_PATH', plugin_dir_path( __FILE__ ) );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-add_to_basket-activator.php
 */
function activate_add_to_basket() {
	require_once ADD_TO_BASKET_PATH . 'includes/class-add-to-basket-activator.php';
	AddtoBasket_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-add_to_basket-deactivator.php
 */
function deactivate_add_to_basket() {
	require_once ADD_TO_BASKET_PATH . 'includes/class-add-to-basket-deactivator.php';
	AddtoBasket_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_add_to_basket' );
register_deactivation_hook( __FILE__, 'deactivate_add_to_basket' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-add-to-basket.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_add_to_basket() {

	
	add_action('woocommerce_init', 'check_woocommerce_init', 11);

	function check_woocommerce_init() {
		if (class_exists('WC_Payment_Gateway')) {
			$plugin = new Add_to_basket();
			$plugin->run();
		}
	}

}
run_add_to_basket();