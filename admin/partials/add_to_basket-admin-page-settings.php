<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://marshallfungai.github.io
 * @since      1.0.0
 *
 * @package    Add To Basket
 * @subpackage Add To Basket/admin/partials
 */


 
?><h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
<form method="post" action="options.php">
	<img width="200" title="<?php echo esc_attr($this->plugin_name); ?>" src="<?php echo plugin_dir_url(__FILE__) . '../images/logo1.png'; ?>" />

	<?php
	settings_errors();
	settings_fields( $this->plugin_name . '_verify_configs_section' );
	do_settings_sections( $this->plugin_name );
	submit_button( 'Save Settings' );

	?></form>
