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
<h2><?php esc_html_e( 'Shortcode', 'add2basket' ); ?></h2>
<p><?php esc_html_e( 'The simplest version of the shortcode is:', 'add2basket' ); ?></p>
<pre><code>[add2basket]</code></pre>
<p><?php esc_html_e( 'Enter that in the Editor on any page or post to display all the job opening posts.', 'add2basket' ); ?></p>
<p><?php esc_html_e( 'This is an example with all the default attributes used:', 'add2basket' ); ?></p>
<pre><code>[add2basket num="5"]</code></pre>

<h3><?php esc_html_e( 'Shortcode Attributes', 'add2basket' ); ?></h3>
<p><?php esc_html_e( 'There are currently three attributes that can be added to the shortcode to filter job opening posts:', 'add2basket' ); ?></p>
<ol>
	<li><?php esc_html_e( 'order', 'add2basket' ); ?></li>
	<li><?php esc_html_e( 'quantity', 'add2basket' ); ?></li>
	<li><?php esc_html_e( 'location', 'add2basket' ); ?></li>
</ol>
<h4><?php esc_html_e( 'order', 'add2basket' ); ?></h4>
<p><?php printf( wp_kses( __( 'Changes the display order of the job opening posts. Default value is "date", but can use any of <a href="%1$s">the "orderby" parameters for WP_Query</a>.', 'add2basket' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( 'https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters' ) ); ?></p>
<p><?php esc_html_e( 'Examples of the order attribute:', 'add2basket' ); ?></p>
<ul>
	<li><?php esc_html_e( 'order="title" (order by post title)', 'add2basket' ); ?></li>
	<li><?php esc_html_e( 'order="name" (order by post slug)', 'add2basket' ); ?></li>
	<li><?php esc_html_e( 'order="rand" (random order)', 'add2basket' ); ?></li>
</ul>

<h4><?php esc_html_e( 'quantity', 'add2basket' ); ?></h4>
<p><?php esc_html_e( 'Determines how many job opening posts are displayed. The default value is 100. Must be a positive value. To display all, use a high number.', 'add2basket' ); ?></p>
<p><?php esc_html_e( 'Examples of the quantity attribute:', 'add2basket' ); ?></p>
<ul>
	<li><?php esc_html_e( 'quantity="3" (only show 3 openings)', 'add2basket' ); ?></li>
	<li><?php esc_html_e( 'quantity="125" (only show 125 openings)', 'add2basket' ); ?></li>
	<li><?php esc_html_e( 'quantity="999" (large number to display to all openings)', 'add2basket' ); ?></li>
</ul>

<h4><?php esc_html_e( 'location', 'add2basket' ); ?></h4>
<p><?php esc_html_e( 'Filters job openings based on the value of the job location metabox field. The value should be the ', 'add2basket' ); ?></p>
<p><?php esc_html_e( 'Examples of the location attribute:', 'add2basket' ); ?></p>
<ul>
	<li><?php esc_html_e( 'location="St Louis"', 'add2basket' ); ?></li>
	<li><?php esc_html_e( 'location="Decatur"', 'add2basket' ); ?></li>
	<li><?php esc_html_e( 'location="Chicago"', 'add2basket' ); ?></li>
</ul>

<h4><?php esc_html_e( 'WP_Query', 'add2basket' ); ?></h4>
<p><?php printf( wp_kses( __( 'The shortcode will also accept any of <a href="%1$s">the parameters for WP_Query</a>.', 'add2basket' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( 'https://codex.wordpress.org/Class_Reference/WP_Query' ) ); ?></p>
