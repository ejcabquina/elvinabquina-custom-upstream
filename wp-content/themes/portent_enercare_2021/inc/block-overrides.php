<?php

function enercare_render_cover_block( $block_content, $block ) {
	if ( 'core/cover' !== $block['blockName'] ) {
		return $block_content;
	}
	
  $block_content .= '<div class="banner-group alignfull">';
  $block_content .= enercare_banner(false);
  $block_content .= enercare_oba_banner(false);
  $block_content .= '</div>';
  $block_content .= enercare_breadcrumbs(false);
	return $block_content;
}
add_filter( 'render_block', 'enercare_render_cover_block', 10, 2 );

function enercare_render_search_block( $block_content, $block ) {
	if ( 'core/search' !== $block['blockName'] ) {
		return $block_content;
	}
	
  $block_content = str_replace('name="s"', 'name="addsearch"', $block_content);
	return $block_content;
}
add_filter( 'render_block', 'enercare_render_search_block', 10, 2 );