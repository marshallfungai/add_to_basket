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
//print_r($options); exit;
if($options['listing-title-header-status']){
	$s_display_title = $args['title'] != ''? $args['title']: $options['listing-title-header'];
	echo('<h4>' . $s_display_title . '</h4>');
}

echo '<ul>';
foreach ( $items as $item ) {
	echo('<li><button><b>' . $item->post_title . ' | ' . $item->post_content . '</button></li>');
} // foreach
echo ('</ul>');
