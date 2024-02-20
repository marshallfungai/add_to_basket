<?php

/**
 * Fired during plugin activation
 *
 * @link       https://marshallfungai.github.io
 * @since      1.0.0
 *
 * @package    Add_to_basket
 * @subpackage Add_to_basket/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    AddtoBasket
 * @subpackage Add2Basket/includes
 * @author     Fungai Marshall <marshall@devartists.com>
 */
class AddtoBasket_Activator {

	/**
	 * Activate plugin
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		//wp_die(print_r(apply_filters( 'active_plugins', get_option('active_plugins'))));
        if (! in_array('woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option('active_plugins')))) die;  // check for woocommerce
	}
}