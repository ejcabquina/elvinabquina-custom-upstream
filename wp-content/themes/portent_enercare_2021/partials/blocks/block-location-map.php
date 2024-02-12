 <?php
/**
 * Location Map Block
 *
 * @package      Enercare
 * @author       Portent
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

echo '<div class="block-location-map block-location-map--tablet--'.$tablet_appearance.'">';

if (get_field('google_maps_url', get_the_ID())) {
  echo '<iframe class="" src="' . get_field('google_maps_url', get_the_ID()) . '" width="568" height="400" loading="lazy" style="border-radius: 2px; box-shadow: none; border: none;"></iframe>';
}

echo '</div>';

 
 