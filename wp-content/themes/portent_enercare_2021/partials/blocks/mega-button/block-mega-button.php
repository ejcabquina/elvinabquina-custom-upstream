<?php
// Create class attribute allowing for custom "className" and "align" values.
$block_class_base = 'block-mega-button';
$classes = $block_class_base;

if( !empty($block['className']) ) {
	$classes .= sprintf( ' %s', $block['className'] );
}
if( !empty($block['align']) ) {
	//$classes .= sprintf( ' align%s', $block['align'] );
}
// if( !empty($block['backgroundColor'] ) ) {
// 	$background_class = sprintf(' has-%s-background-color has-background', $block['backgroundColor']);
// } else {
// 	$background_class = ' has-white-background-color has-background';
// }

if( get_field('sizing' ) ) {
 	$classes .= ' ' . $block_class_base . '--width-' . get_field('sizing' );
} else {
	$classes .= ' ' . $block_class_base . '--width-auto';
}

// ACF Fields
	$link = get_field('link');
	$image = get_field( 'image');
?>

<li class="<?php echo esc_attr($classes); ?>">
	<a class="<?=$background_class?> <?= $block_class_base ?>__link" href="<?= $link['url'] ?>">
		<?= wp_get_attachment_image( $image, '1-1', false, array( 'alt' => '', 'class' => $block_class_base. '__image' )); ?>
		<span class="<?= $block_class_base ?>__text"><?= $link['title'] ?></span>
	</a>
</li>
