<?php
/**
 * Provides the markup for any text field
 *
 * @link       https://marshallfungai.github.io
 * @since      1.0.0
 *
 * @package    Add_to_basket
 * @subpackage Add_to_basket/admin/partials
 */


echo "<div class='a2b-admin-metaboxes'>";

if ( ! empty( $atts['label'] ) ) {
	echo '<label class="bold" for="' . esc_attr( $atts['id'] ) . '">' . esc_html_e( $atts['label'], 'addtobasket' ) . ' : </label>';
}


// $options2 = get_option($this->plugin_name . '-options', );
// if($options2[$atts['id']]){
// 	$value = $options2[$atts['id']];
// }

//print_r( get_option($this->plugin_name)); exit;

?>

<input
	class="<?php echo esc_attr( $atts['class'] ); ?>"
	id="<?php echo esc_attr( $atts['id'] ); ?>"
	name="<?php echo esc_attr( $atts['id'] ); ?>"
	placeholder="<?php echo esc_attr( $atts['placeholder'] ); ?>"
	type="<?php echo esc_attr( $atts['type'] ); ?>"
	value="<?php echo esc_attr( $atts['value']  );  ?>" />

<?php
if (! empty( $atts['description'] )) {
	echo '<span class="description">' . esc_html_e( $atts['description'], 'addtobasket' ) . ' </span>';
}
?>

</div>


