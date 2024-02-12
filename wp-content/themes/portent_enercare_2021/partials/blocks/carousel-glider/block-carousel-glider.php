<?php
// Create class attribute allowing for custom "className" and "align" values.
$classes = 'block-carousel';
if( !empty($block['className']) ) {
	$classes .= sprintf( ' %s', $block['className'] );
}
if( !empty($block['align']) ) {
	$classes .= sprintf( ' align%s', $block['align'] );
	$allowed_blocks = array( 'acf/glider-slide', 'acf/ecm-card', 'acf/offer-card' );
}

$slides_to_show = get_field('slides_to_show');
$slides_to_advance = get_field('slides_to_advance');

$breakpoints_arr = array();
while ( have_rows('breakpoints') ) { the_row();
	$breakpoints_arr[] = array(
			'breakpoint' => get_sub_field('breakpoint'),
			'settings' => array(
					'slidesToShow' => get_sub_field('slides_to_show'),
					'slidesToScroll' => get_sub_field('slides_to_advance'),
					'itemWidth' => get_sub_field('item_width'),
					'duration' => get_sub_field('duration')
			)
	);
}

$hash = hash('adler32', random_bytes(18));
?>

<div class="block-carousel__wrapper" data-num-slides="<?= $slides_to_show; ?>" data-num-advance="<?= $slides_to_advance; ?>" data-breakpoints='<?= json_encode($breakpoints_arr); ?>' data-id="<?= $hash; ?>">

	<div class="<?php echo esc_attr($classes); ?>">
		<?php echo '<InnerBlocks allowedBlocks="' . esc_attr( wp_json_encode( $allowed_blocks ) ) . '" />'; ?>
	</div>

	<div class="block-carousel__controls">
		<?php if (get_field('arrows')) { ?>
			<?php if (get_field('rewind')) { ?>
				<button aria-label="Previous" class="block-carousel__prev glider-prev-<?= $hash; ?>"><img alt="Previous" src="<?= get_template_directory_uri() . '/assets/icons/utility/arrow_back.svg' ?>" /></button>
			<?php } ?>
		<?php } ?>

		<?php if (get_field('dots')) { ?>
			<div role="tablist" class="dots block-carousel__dots glider-dots-<?= $hash; ?>"></div>
		<?php } ?>

		<?php if (get_field('arrows')) { ?>
			<button aria-label="Next" class="block-carousel__next glider-next-<?= $hash; ?>"><img alt="Next" src="<?= get_template_directory_uri() . '/assets/icons/utility/arrow_forward.svg' ?>" /></button>
		<?php } ?>
	</div>
</div>
