<?php
/**
 * Single Post
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

// Entry category in header
add_action( 'tha_entry_top', 'enercare_entry_category', 8 );
add_action( 'tha_entry_top', 'enercare_entry_author', 12 );
add_action( 'tha_entry_top', 'enercare_entry_header_share', 13 );

/**
 * Entry header share
 *
 */
function enercare_entry_header_share() {
	do_action( 'enercare_entry_header_share' );
}

/**
 * After Entry
 *
 */
function enercare_single_after_entry() {
	echo '<div class="after-entry">';

	// Breadcrumbs
	enercare_breadcrumbs();

	// Publish date
	echo '<p class="publish-date">Published on ' . get_the_date( 'F j, Y' ) . '</p>';

	// Sharing
	do_action( 'enercare_entry_footer_share' );

	echo '</div>';

}
add_action( 'tha_content_while_after', 'enercare_single_after_entry', 8 );

// Build the page
require get_template_directory() . '/index.php';
