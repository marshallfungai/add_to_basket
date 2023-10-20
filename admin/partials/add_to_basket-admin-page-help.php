<?php

/**
 * Provide an admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://marshallfungai.github.io
 * @since      1.0.0
 *
 * @package    Now Hiring
 * @subpackage Now Hiring/admin/partials
 */

?><h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
<h2><?php esc_html_e( 'Client Key', 'add2basket' ); ?></h2>
<p><?php esc_html_e( 'Client key gives you connects your host to Add to basket account:', 'add2basket' ); ?></p>
<p><?php printf( wp_kses( __( 'It can only be obtained from: <a href="%1$s">www.addtobasket.net</a>.', 'add2basket' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( 'https://addtobasket.net/' ) ); ?></p>
<p><?php esc_html_e( 'After getting your client key, enter it in A2B settings', 'add2basket' ); ?></p>
<h2><?php esc_html_e( 'Shortcode', 'add2basket' ); ?></h2>
<p><?php esc_html_e( 'The simplest version of the shortcode is:', 'add2basket' ); ?></p>
<pre><code>[add2basket]</code></pre>
<p><?php esc_html_e( 'Enter that in the Editor on any page or post to display all the "Add to basket" (A2B) buy buttons.', 'add2basket' ); ?></p>
<p><?php esc_html_e( 'This is an example with all the default attributes used:', 'add2basket' ); ?></p>
<pre><code>[add2basket num="5"]</code></pre>

<h3><?php esc_html_e( 'Shortcode Attributes', 'add2basket' ); ?></h3>
<p><?php esc_html_e( 'There are currently three attributes that can be added to the shortcode to filter A2B buy buttons:', 'add2basket' ); ?></p>
<ol>
	<li><?php esc_html_e( 'order', 'add2basket' ); ?></li>
	<li><?php esc_html_e( 'num', 'add2basket' ); ?></li>
	<li><?php esc_html_e( 'title', 'add2basket' ); ?></li>
</ol>
<h4><?php esc_html_e( 'order', 'add2basket' ); ?></h4>
<p><?php printf( wp_kses( __( 'Changes the display order of the A2B buttons. Default value is "date", but can use any of <a href="%1$s">the "orderby" parameters for WP_Query</a>.', 'add2basket' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( 'https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters' ) ); ?></p>
<p><?php esc_html_e( 'Examples of the order attribute:', 'add2basket' ); ?></p>
<ul>
	<li><?php esc_html_e( 'order="title" (order by post title)', 'add2basket' ); ?></li>
	<li><?php esc_html_e( 'order="name" (order by post slug)', 'add2basket' ); ?></li>
	<li><?php esc_html_e( 'order="rand" (random order)', 'add2basket' ); ?></li>
</ul>

<h4><?php esc_html_e( 'num', 'add2basket' ); ?></h4>
<p><?php esc_html_e( 'Determines how many A2B buttons are displayed. The default value is 100 which displays all of them. Must be a positive value. To display all, use a high number.', 'add2basket' ); ?></p>
<p><?php esc_html_e( 'Examples of the quantity attribute:', 'add2basket' ); ?></p>
<ul>
	<li><?php esc_html_e( 'num="3" (only show 3 buy buttons)', 'add2basket' ); ?></li>
	<li><?php esc_html_e( 'num="125" (only show 125 buy buttons)', 'add2basket' ); ?></li>
	<li><?php esc_html_e( 'num="999" (large number to display to all A2B buttons)', 'add2basket' ); ?></li>
</ul>

<h4><?php esc_html_e( 'title', 'add2basket' ); ?></h4>
<p><?php esc_html_e( 'Just adds a custom title on top of the list ', 'add2basket' ); ?></p>
<p><?php esc_html_e( 'Examples of the location attribute:', 'add2basket' ); ?></p>
<ul>
	<li><?php esc_html_e( 'title="My products"', 'add2basket' ); ?></li>
	<li><?php esc_html_e( 'title="Telephone buy buttons"', 'add2basket' ); ?></li>
</ul>

<h4><?php esc_html_e( 'WP_Query', 'add2basket' ); ?></h4>
<p><?php printf( wp_kses( __( 'The shortcode will also accept any of <a href="%1$s">the parameters for WP_Query</a>.', 'add2basket' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( 'https://codex.wordpress.org/Class_Reference/WP_Query' ) ); ?></p>
