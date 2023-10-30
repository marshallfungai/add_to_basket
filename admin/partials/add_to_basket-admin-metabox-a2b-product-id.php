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

wp_nonce_field($this->plugin_name, 'a2b_metabox_product_key');

$atts = array();
//$atts['class'] 			= 'widefat';

$atts['id'] = 'a2b_metabox_product_key_text';
$atts['label'] = 'Product ID';
$atts['name'] = 'a2b_metabox_product_key_text';
$atts['placeholder'] = '#Product Key/ID';
$atts['type'] = 'text';
$atts['value'] = '';
$atts['input_status'] = 'disabled';
$atts['description'] = 'Product key for the product in relation to A2B. Note, it is NOT the same as the WP post id or any locally generated id.';


if (!empty($this->meta[$atts['id']][0])) {

	$atts['value'] = $this->meta[$atts['id']][0];

}

apply_filters($this->plugin_name . '-field-' . $atts['id'], $atts);

?>

 <p>
	<?php

	include(plugin_dir_path(__FILE__) . $this->plugin_name . '-admin-field-text.php');

	?></p>
