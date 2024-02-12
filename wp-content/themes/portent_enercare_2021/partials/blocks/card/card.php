<?php
// Create class attribute allowing for custom "className" and "align" values.
$classes = '';
if( !empty($block['className']) ) {
	$classes .= sprintf( ' %s', $block['className'] );
}
if( !empty($block['align']) ) {
	$classes .= sprintf( ' align%s', $block['align'] );
}

if( !empty($block['backgroundColor'] ) ) {
	$classes .= sprintf(' has-%s-background-color', $block['backgroundColor']);
}

if( get_field( 'justification' ) ) {
	$classes .= sprintf(' block-card--justify-%s', get_field( 'justification' ) );
}

if( get_field( 'alignment' ) ) {
	$classes .= sprintf(' block-card--align-%s', get_field( 'alignment' ) );
}

$allowed_blocks = array(
	'core/image',
	'core/paragraph',
	'core/header',
	'core/buttons',
	'core/list',
	'core/heading'
);

?>

<div class="block-card <?php echo esc_attr($classes); ?>">
	<InnerBlocks allowedBlocks="<?= esc_attr( wp_json_encode( $allowed_blocks ) );  ?>" />
</div>
