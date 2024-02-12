<?php
/**
 * 404 / No Results partial
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/


echo '<section class="no-results not-found">';

  if ( is_post_type_archive('campaign') ) {
    echo '<header class="entry-header"><h1 class="entry-title">' . esc_html__( 'No Active Offers', 'ea-starter' ) . '</h1></header>';
  } elseif ( is_search() ) {
    echo '<header class="archive-description"><h1 class="entry-title">' . esc_html__( 'Search Results', 'ea-starter' ) . '</h1></header>';
  } else {
    echo '<header class="entry-header"><h1 class="entry-title">' . esc_html__( 'Nothing Found', 'ea-starter' ) . '</h1></header>';
  }
	
	
  echo '<div class="entry-content">';

	if ( is_search() ) {

		//echo '<p>' . esc_html__( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'ea-starter' ) . '</p>';
		//get_search_form();

	} elseif ( is_post_type_archive('campaign') ) {
    
    echo '<p>' . esc_html__( 'There are no active offers at this time. Please check back soon!', 'ea-starter' ) . '</p>';
    
  } else {

		echo '<p>' . esc_html__( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'ea-starter' ) . '</p>';
		/**
		 * @todo there will be more development required for the 404 view after launch
		 */
		get_search_form();
	}

	echo '</div>';
echo '</section>';
