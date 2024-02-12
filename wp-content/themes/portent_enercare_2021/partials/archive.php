<?php
/**
 * Archive partial
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

echo '<article class="post-summary block-card block-card--archive">';

	enercare_post_summary_image();

	echo '<div class="post-summary__content block-card--content">';
		//enercare_entry_category();
    enercare_post_date();
		enercare_post_summary_title('h3');
    enercare_post_summary();
	echo '</div>';

echo '</article>';
