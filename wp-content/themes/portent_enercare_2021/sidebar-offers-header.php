<?php
/**
 * Sidebar
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/
$sidebar = 'offers-header';

$display = is_active_sidebar( $sidebar );
if ( ! apply_filters( 'enercare_display_sidebar', $display ) )
	return;

echo '<section class="archive-widget-area archive" role="complementary">';
	tha_sidebar_top();
	if ( is_active_sidebar( $sidebar ) )
		dynamic_sidebar( $sidebar );
	tha_sidebar_bottom();
echo '</section>';
