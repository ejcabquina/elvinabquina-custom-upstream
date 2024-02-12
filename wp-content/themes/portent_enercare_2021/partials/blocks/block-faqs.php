<?php
/**
 * FAQs Block
 *
 * @package      Enercare
 * @author       Portent
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

$heading = get_field( 'header' );
$content = get_field( 'content' );
$faqs = get_field( 'faqs' );

$classes = 'block-faqs';
if( !empty($block['className']) ) {
	$classes .= sprintf( ' %s', $block['className'] );
}

echo '<div class="' . $classes . '" data-allow-multiple>';

if( !empty( $heading ) )
	echo '<h3 class="block-faqs--header">' . esc_html( $heading ) . '</h3>';
if( !empty( $content ) )
	echo '<p class="block-faqs--content">' . esc_html( $content ) . '</p>';

if( !empty( $faqs ) )  {
  echo '<ul class="block-faqs--list">';
  foreach ($faqs as $faq) {
    echo '<li class="block-faqs--faq-container">';
        echo '<h4><button class="block-faqs--button" aria-expanded="false" aria-controls="faq_' . $faq->ID . '">' .esc_html($faq->post_title) . '</button></h4>';

    echo '<div id="faq_' . $faq->ID . '" class="block-faqs--faq-answer-container" data-state="closed" aria-labelledby="faq_' . $faq->ID . '">';
        echo $faq->post_content;
    echo '</div>';

    echo '</li>';
  }
  echo '<ul>';
}

echo '</div>';
