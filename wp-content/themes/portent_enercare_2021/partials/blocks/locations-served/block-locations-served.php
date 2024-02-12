<?php

$block_class_base = 'block-locations-served';
$classes = $block_class_base;
$classes .= ' alignfull';
if( !empty($block['className']) ) {
	$classes .= sprintf( ' %s', $block['className'] );
}

$section_header = get_field('title');
$locations = get_field('locations');
?>

<section class="<?= $classes ?>">
	<div class="<?= $block_class_base ?>__containers" data-open=false>
		<div data-allow-toggle class="<?= $block_class_base ?>__locations">
			<h2 class="<?= $block_class_base ?>__header">
				<button aria-expanded="false" class="<?= $block_class_base ?>__trigger" aria-controls="locations-served-list">
					<?= $section_header ?>
					<span role="presentation" class="<?= $block_class_base ?>__accordion-icon"></span>
				</button>
			</h2>
				<ul hidden class="<?= $block_class_base ?>__location-list" id="locations-served-list">
					<?php
					while(have_rows('locations')) {
						the_row();
						echo '<li><a href=" '. get_sub_field('link')['url'].' ">'. get_sub_field('link')['title'] .'</a></li>';
					}
					?>
				</ul>
		</div>
		<div class="<?= $block_class_base ?>__button">
			<a href="/locations" class="<?= $block_class_base ?>__cta"><img width=24 height=24 alt="" role="presentation" src="<?= get_template_directory_uri() ?>/assets/icons/maps/place_white_24dp_rounded.svg" />Find A Location</a>
		</div>
	</div>
</section>
