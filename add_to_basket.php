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
 * @package           Add_to_basket
 *
 * @wordpress-plugin
 * Plugin Name:       Add To Basket : Instant Payments
 * Plugin URI:        https://addtobasket.net/
 * Description:       Instant payment solution for you.
 * Version:           1.0.0
 * Author:            Fungai Marshall, Gunes Erdemi
 * Author URI:        https://marshallfungai.github.io/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       add2basket
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Check if Add to basket is active
 */
define('A2B_ACTIVE', false);

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ADD_TO_BASKET_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-add_to_basket-activator.php
 */
function activate_add_to_basket() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-add_to_basket-activator.php';
	Add_to_basket_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-add_to_basket-deactivator.php
 */
function deactivate_add_to_basket() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-add_to_basket-deactivator.php';
	Add_to_basket_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_add_to_basket' );
register_deactivation_hook( __FILE__, 'deactivate_add_to_basket' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-add_to_basket.php';

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

	$plugin = new Add_to_basket();
	$plugin->run();

}
run_add_to_basket();
