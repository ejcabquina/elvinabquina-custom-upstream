<?php
/**
 * 404 / No Results partial
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/


echo '<div class="content-area wrap no-results not-found">';
echo '<div class="entry-content">';

if( function_exists( 'enercare_block_area' ) )
	enercare_block_area()->show('404');

get_search_form();

echo '</div>';
echo '</div>';
