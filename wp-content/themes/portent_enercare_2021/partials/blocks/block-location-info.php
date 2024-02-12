<?php
/**
 * Location Info Block
 *
 * @package      Enercare
 * @author       Portent
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

echo '<div class="block-location-info block-card wp-block-acf-card is-style-shadowed block-location-info--tablet--'.$tablet_appearance.'">';

getLocationInfo(get_the_ID(), "phone");

echo '</div>';
