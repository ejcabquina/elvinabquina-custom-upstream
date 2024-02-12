<?php
/**
 * Related Post Partial
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

echo '<article class="post-summary block-card block-card--related-post">';

enercare_post_summary_image('5-3-image');

echo '<div class="post-summary__content block-card--content">';
enercare_entry_category();
enercare_post_summary_title();
echo '</div>';

echo '</article>';
