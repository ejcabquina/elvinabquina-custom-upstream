<?php
// Create class attribute allowing for custom "className" and "align" values.
$classes = '';
if( !empty($block['className']) ) {
	$classes .= sprintf( ' %s', $block['className'] );
}
if( !empty($block['align']) ) {
	$classes .= sprintf( ' align%s', $block['align'] );
}

$slide_image = get_field('slide_image');

?>
<div class="block-slide__wrapper alignfull">
	<?= wp_get_attachment_image( $slide_image, '16-9-3x', false, array('class'=>'block-slide__image'))?>
	<div class="<?php echo esc_attr($classes); ?>">
		<InnerBlocks />
	</div>
</div>
