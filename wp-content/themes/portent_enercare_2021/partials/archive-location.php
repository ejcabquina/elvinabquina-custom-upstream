<?php
/**
 * Location Information Card
 */

echo '<article class="location-summary block-card wp-block-acf-card">';

	//enercare_post_summary_image();

	echo '<div class="block-location-info location-summary__content">';
		//enercare_entry_category();
		enercare_post_summary_title();
  echo '<hr />';
    getLocationInfo(get_the_ID());
	echo '</div>';

echo '</article>';
