<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://marshallfungai.github.io
 * @since      1.0.0
 *
 * @package    Add_to_basket
 * @subpackage Add_to_basket/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">

	<h2><?php echo esc_html(get_admin_page_title()); ?></h2>

	<form method="post" name="add-to-basket_options" action="options.php">

		<?php
		//Grab all options
		$options = get_option($this->plugin_name);
		//print_r($options); exit;
		// Title atb
		$atb_title = $options['atb-title'];
		?>
		<?php
		settings_fields($this->plugin_name);
		do_settings_sections($this->plugin_name);
		?>



		<!-- Optional title for atb list -->
		<fieldset>
			<legend class="screen-reader-text"><span>Include title in ATB list.</span></legend>
			<label for="<?php echo $this->plugin_name; ?>-atb-title">
				<input type="checkbox" id="<?php echo $this->plugin_name; ?>-atb-title" name="<?php echo $this->plugin_name; ?>[atb-title]" value="1" <?php checked($atb_title, 1); ?>/>
				<span><?php esc_attr_e('Include title in basket items list?', $this->plugin_name); ?></span>
			</label>
		</fieldset>

		<br/>
		<hr/>
		<?php submit_button('Save all changes', 'primary','submit', TRUE); ?>

	</form>

</div>
