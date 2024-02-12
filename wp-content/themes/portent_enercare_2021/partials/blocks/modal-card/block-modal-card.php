<?php
/**
 * Modal Card
 *
 * This is actually the EMC card. Overtime this could morph into something more useful but for now just EMCs
 */
// Create class attribute allowing for custom "className" and "align" values.
$classes = 'block-emc-card';
if( !empty($block['className']) ) {
	$classes .= sprintf( ' %s', $block['className'] );
}
if( !empty($block['align']) ) {
	$classes .= sprintf( ' align%s', $block['align'] );
}

if( !empty($block['backgroundColor'] ) ) {
	$classes .= sprintf(' has-%s-background-color', $block['backgroundColor']);
}

//ACF Fields
$emc_image = get_field('image') ? get_field('image') : get_field( 'enercare_default_image', 'options');
$emc_name = get_field('name') ? get_field('name') : __( 'EMC Name', 'portent-enercare');
$emc_title = get_field('title') ? get_field('title') : __( 'EMC Title', 'portent-enercare');
$emc_bio = get_field( 'bio' );
$cta_text = get_field('cta_text') ? get_field('cta_text') : __( 'Read Bio', 'portent-enercare');
?>

<div class="block-emc-card__wrapper">
	<div class="<?php echo esc_attr($classes); ?>">
		<?= wp_get_attachment_image( $emc_image, '1-1-2x', false, array( 'class' =>'block-emc-card__image', 'alt'=>'') ); ?>
		<h3><?= $emc_name ?></h3>
		<span class="block-emc-card__text"><?= $emc_title ?></span>
		<?php if( !empty( $emc_bio ) && $emc_bio !== '' ) : ?>
			<button class="block-emc-card__trigger wp-block-button__link has-red-background-color has-background"><?= $cta_text ?></button>
		<?php endif; ?>
	</div>
	<?php if( !empty( $emc_bio ) && $emc_bio !== '' ) : ?>
		<div class="block-emc-card__content" aria-hidden="true">
			<button class="block-emc-card__close-button" aria-label="Close Bio"><img width="24" height="24" alt="Close Bio" src="<?= get_template_directory_uri() . '/assets/icons/utility/close.svg'?>" /></button>
			<div class="block-emc-card__bio-header">
				<?= wp_get_attachment_image( $emc_image, '1-1', false, array( 'class' =>'block-emc-card__bio-header__image', 'alt'=>'') ); ?>
				<strong><?= $emc_name ?></strong>
			</div>
			<p class="block-emc-card__bio"><?= $emc_bio ?></p>
		</div>
	<?php endif; ?>
</div>
