<?php
/**
 * Site Header
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

$logo_id = get_field( 'enercare_default_logo', 'options');

echo '<!DOCTYPE html>';
tha_html_before();
echo '<html ' . get_language_attributes() . '>';

echo '<head>';
	tha_head_top();
	wp_head();
	tha_head_bottom();
echo '</head>';

echo '<body class="' . join( ' ', get_body_class() ) . '">';
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}
if ( function_exists( 'gtm4wp_the_gtm_tag' ) ) { gtm4wp_the_gtm_tag(); }
tha_body_top();
echo '<div class="site-container">';
	echo '<a class="skip-link screen-reader-text" href="#main-content">' . esc_html__( 'Skip to content', 'ea-starter' ) . '</a>';

	tha_header_before();
	echo '<header class="site-header" role="banner">';
		tha_header_top();
		echo '<div class="wrap">';
		echo '<div class="title-area">';
		echo '<a href="' . esc_url( home_url() ) . '" rel="home">' . wp_get_attachment_image($logo_id, 'full', false, ['alt' =>  'Link to Enercare.ca home page', 'loading' => '']) . '</a>';
		echo '</div>';
		tha_header_bottom();
	echo '</div></header>';
	tha_header_after();
	echo '<div class="site-inner" id="main-content">';
