<?php
/**
 * Sidebar
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/
$sidebar = 'blog-archive-footer';

$display = is_active_sidebar( $sidebar );
if ( ! apply_filters( 'enercare_display_sidebar', $display ) )
	return;

echo '<aside class="archive-widget-area archive-footer" role="complementary">';
	tha_sidebar_top();
	if ( is_active_sidebar( $sidebar ) )
		dynamic_sidebar( $sidebar );
	tha_sidebar_bottom();
echo '</aside>';
