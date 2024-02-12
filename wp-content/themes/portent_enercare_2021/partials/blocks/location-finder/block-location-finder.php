<?php

$block_class_base = 'block-location-finder';
$classes = $block_class_base;
$classes .= ' alignfull';
if( !empty($block['className']) ) {
	$classes .= sprintf( ' %s', $block['className'] );
}

$section_header = get_field('title');
$section_content = get_field('content');
$locations_header = get_field('locations_heading');
$links_or_locations = get_field('lc_block_use_custom_urls');
$locations_locations = $links_or_locations ? get_field('custom_urls') : get_field('locations');
$locations_cta = get_field('locations_cta');
$form_image = wp_get_attachment_image( get_field('form_image') , 'full', null, array( 'alt' => '', 'class' => 'block-location-finder__icon' ));
$form_background = wp_get_attachment_image_src( get_field('form_background_image'), 'full');
$form_header = get_field('form_header');
$form_content = get_field('form_content');

?>

<section class="<?= $classes ?>">
	<div class="block-location-finder__content">
		<h2 class="block-location-finder__block-header has-white-color"><?= $section_header ?></h2>
		<p class="block-location-finder__block-description has-white-color"><?= $section_content ?></p>
		<h3 class="block-location-finder__list-header has-white-color"><?= $locations_header ?></h3>
		<ul class="block-location-finder__list">
		<?php
		  if ($locations_locations && sizeof($locations_locations) > 0) {
					foreach( $locations_locations as $location ) {
						/**
						 * Is it a location or a link set information appropriately.
						 */
						$location_title = 'Location';
						$location_url = '/';

						if($links_or_locations) {
							$location_title = $location['link']['title'];
							$location_url = $location['link']['url'];
						} else {
							$location_title = get_field('display_title', $location) ? get_field('display_title', $location) : get_the_title($location);
							$location_url = get_the_permalink($location);
						}

						echo '<li>';
							echo '<a class="has-white-color" href="'.$location_url.'">'. $location_title .'</a>';
						echo '</li>';
					}
		  }
		?>
		</ul>
    <?php if ($locations_cta && sizeof($locations_cta) > 0) { ?>
		<a class="wp-block-button__link has-red-background-color has-background" href="<?= $locations_cta['url'] ?>"><?= $locations_cta['title'] ?></a>
    <?php } ?>
	</div>
	<div class="block-location-finder__form" style="background-image:url(<?= $form_background[0] ?>)">
		<div class="block-location-finder__form-container">
			<?= $form_image ?>
			<h3 class="block-location-finder__header has-black-color"><?= $form_header ?></h3>
			<p class="block-location-finder__text has-black-color"><?= $form_content ?></p>
			<form method="get" action="<?= get_bloginfo('url'); ?>/locations/" class="block-location-finder__postal-form">
				<label for="location_finder__input">Postal Code <span class="required-text" aria-hidden="true">(required)</span></label>
				<div class="field-group">
					<input class="block-location-finder__input" required data-interface="location-finder-input" id="location_finder__input" autocomplete="postal-code" type="text" name="postal_code" maxlength="7" />
					<input class="block-location-finder__submit has-red-background-color has-background" data-interface="location-finder-submit" type="submit" value="Submit" />
				</div>
				<label for="location_finder__label-example">eg: A1A1A1</label>
			</form>
		</div>
	</div>
</section>
