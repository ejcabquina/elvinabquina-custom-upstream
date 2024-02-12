<?php
/**
 * Category
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

// Full width
add_filter( 'enercare_page_layout', 'enercare_return_full_width_content' );

/**
 * Body Class
 *
 */
function enercare_archive_body_class( $classes ) {
	$classes[] = 'archive';

	//Place a taxonomy body class to ease with styling and remove the need to specificity on which term or taxonomy is being used
	if(is_tax() ) {
		$classes[] = 'taxonomy';
	}
	return $classes;
}
add_filter( 'body_class', 'enercare_archive_body_class' );


/**
 * Archive Header
 *
 */
function enercare_archive_header() {
	include_once(get_template_directory() . '/partials/category-archive-header.php' );
}
add_action( 'tha_content_while_before', 'enercare_archive_header' );

// Breadcrumbs
add_action( 'enercare_archive_header_after', 'enercare_breadcrumbs', 5 );

// add section wrapper -- we'll use this on our ajax calls to replace the results, yes the name sucks... roll with it.
function enercare_archive_wrapper_top() {
	echo '<div class="archive-containment-field">';
    echo '<section class="archive-wrapper">';
}
add_action('tha_content_while_before', 'enercare_archive_wrapper_top');

function enercare_archive_wrapper_bottom() {
  echo '</section>';
  echo '</div>';
}
add_action('tha_content_bottom', 'enercare_archive_wrapper_bottom');

// Build the page
require get_template_directory() . '/index.php';
