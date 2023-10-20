<?php

/**
 * Provide the view for a metabox
 *
 * @link 		http://slushman.com
 * @since 		1.0.0
 *
 * @package 	Now_Hiring
 * @subpackage 	Now_Hiring/admin/partials
 */

wp_nonce_field( $this->plugin_name, 'price' );

$atts 					= array();
//$atts['class'] 			= 'widefat';
$atts['description'] 	= '';
$atts['id'] 			= 'price';
$atts['label'] 			= 'Price';
$atts['name'] 			= 'price';
$atts['placeholder'] 	= '';
$atts['type'] 			= 'text';
$atts['value'] 			= '';

if ( ! empty( $this->meta[$atts['id']][0] ) ) {

	$atts['value'] = $this->meta[$atts['id']][0];

}

apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

?><p><?php

	include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-text.php' );

	?></p><?php



$atts 					= array();
$atts['description'] 	= '';
$atts['id'] 			= 'button-label';
$atts['label'] 			= 'Button Label';
//$atts['settings']['textarea_name'] = 'button-label';
$atts['name'] 			= 'price';
$atts['placeholder'] 	= '';
$atts['type'] 			= 'text';
$atts['value'] 			= '';

if ( ! empty( $this->meta[$atts['id']][0] ) ) {

	$atts['value'] = $this->meta[$atts['id']][0];

}

apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

?><p><?php

	//include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-textarea.php' );
	//include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-editor.php' );
	include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-text.php' );

	?></p><?php



$atts 					= array();
$atts['description'] 	= '';
$atts['id'] 			= 'item-key';
$atts['label'] 			= 'item key';
//$atts['settings']['textarea_name'] = 'job-additional-info';
$atts['placeholder'] 	= '';
$atts['type'] 			= 'text';
$atts['value'] 			= '';

if ( ! empty( $this->meta[$atts['id']][0] ) ) {

	$atts['value'] = $this->meta[$atts['id']][0];

}

apply_filters( $this->plugin_name . '-field-' . $atts['id'], $atts );

?><p><?php

	//include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-editor.php' );
	include( plugin_dir_path( __FILE__ ) . $this->plugin_name . '-admin-field-text.php' );

	?></p>
