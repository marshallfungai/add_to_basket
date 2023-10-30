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

// Extract values from the $atts array or provide default values
$local_id = !empty($atts['id']) ? esc_attr($atts['id']) : '';
$local_name = !empty($atts['name']) ? esc_attr($atts['name']) : '';
$local_label = !empty($atts['label']) ? esc_html($atts['label']) : '';
$local_value = !empty($atts['value']) ? esc_attr($atts['value']) : '';
$local_placeholder = !empty($atts['placeholder']) ? esc_attr($atts['placeholder']) : '';
$local_type = !empty($atts['type']) ? esc_attr($atts['type']) : '';
$local_input_status = !empty($atts['input_status']) ? esc_attr($atts['input_status']) : '';
$local_description = !empty($atts['description']) ? esc_html($atts['description']) : '';

echo "<div class='a2b-admin-metaboxes'>";

if (!empty($local_label)) {
	echo '<label class="bold" for="' . $local_id . '">' . $local_label . ' : </label>';
}

?>

<input
	id="<?php echo $local_id; ?>"
	name="<?php echo $local_name; ?>"
	placeholder="<?php echo $local_placeholder; ?>"
	type="<?php echo $local_type; ?>"
	<?php echo $local_input_status; ?>
	value="<?php echo $local_value; ?>"
/>

<?php
if (!empty($local_description)) {
	echo '<span class="desc">' . $local_description . '</span><hr/>';
}
?>

</div>
