<?php

//Mega Button Switch Board
$allowed_blocks = array( 'acf/mega-button' );

$block_class_base = 'block-switchboard';
$classes = $block_class_base;
$destkop_columns = 3;
$desktop_column_class = sprintf( ' %s__container--desktop-col-%s', $block_class_base, $destkop_columns );
$mobile_columns = 1;
$mobile_column_class = sprintf( ' %s__container--mobile-col-%s', $block_class_base, $mobile_columns );

if( !empty($block['className']) ) {
	$classes .= sprintf( ' %s', $block['className'] );
}

if( !empty($block['align']) ) {
	//$classes .= sprintf( ' align%s', $block['align'] );
}

if(get_field('desktop_columns')) {
	$desktop_column_class = sprintf( ' %s__container--desktop-col-%s', $block_class_base, get_field('desktop_columns') );
}

if(get_field('mobile_columns')) {
	$mobile_column_class = sprintf( ' %s__container--mobile-col-%s', $block_class_base, get_field('mobile_columns') );
}

?>

<div class="block-switchboard <?php echo esc_attr($classes); ?>">
	<?php if(is_admin()){ echo '<h2 style="text-align: center">Mega Button Switch Board</h2>'; } ?>
	<ul class="block-switchboard__container <?= $desktop_column_class ?> <?= $mobile_column_class ?>">
		<InnerBlocks allowedBlocks="<?= esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" />
	</ul>
</div>
