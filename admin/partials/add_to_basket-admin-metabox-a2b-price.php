<?php
/**
 * Provide the view for a metabox
 *
 * @link        https://marshallfungai.github.io
 * @since        1.0.0
 *
 * @package    Add_to_basket
 * @subpackage Add_to_basket/admin/partials
 */

wp_nonce_field($this->plugin_name, 'a2b_metabox_product_price');

$atts = array();

$atts['id'] = 'a2b_metabox_product_price_text';
$atts['label'] = 'Price';
$atts['name'] = 'a2b_metabox_product_price_text';
$atts['placeholder'] = 'Product Price';
$atts['type'] = 'text';
$atts['value'] = '';
$atts['input_status'] = 'enabled';
$atts['description'] = 'Product price for the product in relation to A2B';

if (!empty($this->meta[$atts['id']][0])) {

	$atts['value'] = $this->meta[$atts['id']][0];

}

apply_filters($this->plugin_name . '-field-' . $atts['id'], $atts);


?><p><?php
	include(plugin_dir_path(__FILE__) . $this->plugin_name . '-admin-field-text.php');

	?></p>
