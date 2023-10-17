<?php

/**
 * Provides the markup for an upload field
 *
 * @link       http://slushman.com
 * @since      1.0.0
 *
 * @package    Add_to_basket
 * @subpackage Add_to_basket/admin/partials
 */

if ( ! empty( $atts['label'] ) ) {

	?><label for="<?php echo esc_attr( $atts['id'] ); ?>"><?php esc_html_e( $atts['label'], 'add2basket' ); ?>: </label><?php

}

?><input
	class="<?php echo esc_attr( $atts['class'] ); ?>"
	data-id="url-file"
	id="<?php echo esc_attr( $atts['id'] ); ?>"
	name="<?php echo esc_attr( $atts['name'] ); ?>"
	type="<?php echo esc_attr( $atts['type'] ); ?>"
	value="<?php echo esc_attr( $atts['value'] ); ?>" />
<a href="#" class="" id="upload-file"><?php esc_html_e( $atts['label-upload'], 'add2basket' ); ?></a>
<a href="#" class="hide" id="remove-file"><?php esc_html_e( $atts['label-remove'], 'add2basket' ); ?></a>
