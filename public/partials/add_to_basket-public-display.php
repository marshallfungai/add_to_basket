<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://marshallfungai.github.io
 * @since      1.0.0
 *
 * @package    Add_to_basket
 * @subpackage Add_to_basket/public/partials
 */

//This file should primarily consist of HTML with a little bit of PHP. -->

$options = get_option($this->plugin_name.'-options');


if($options['listing-title-header-status']){
	$s_display_title = $args['title'] != ''? $args['title']: $options['listing-title-header'];
	echo('<h4>' . $s_display_title . '</h4>');
}

echo '<ul>';
foreach ( $items as $item ) {

     $meta_product_id = $this->get_basket_item_meta($item->ID, 'a2b_metabox_product_price_text') ?? $item->ID;
     $meta_product_price = $this->get_basket_item_meta($item->ID, 'a2b_metabox_product_price_text');
     $meta_product_btn = $this->get_basket_item_meta($item->ID, 'a2b_metabox_product_btn_label_text');

	echo('<li><button id="'.$meta_product_id.'"><b>' . $item->post_title . ' | ' . $meta_product_btn .' | '. $meta_product_price . '</button></li>');
} // foreach
echo ('</ul>');
