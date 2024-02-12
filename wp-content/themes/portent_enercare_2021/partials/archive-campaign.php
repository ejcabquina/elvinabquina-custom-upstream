<?php
/**
 * Archive partial
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/
$campaign_heading = get_field('heading');
$campaign_subheading = get_field('subheading');
$campaign_image = wp_get_attachment_image( get_field('icon'), '3-2', false, array( 'class' =>'block-offer-card__image', 'alt'=>'') );
$campaign_expiration = get_field('end_date');
$terms_and_conditions = get_field('terms_and_conditions');
$campaign_destination = get_field('destination');
$classes = 'has-white-background-color has-background has-white-background-color';
$cta_text = get_field('cta_text') ? get_field('cta_text') : __( 'Shop Plans', 'portent-enercare');
$cta_icon = get_field('cta_icon') ? wp_get_attachment_image_src( get_field('cta_icon'), 'full', false)[0] : get_template_directory_uri() .'/assets/icons/action/shopping_cart_black_24dp_rounded.svg';
$campaign = get_post(get_the_ID());

//Get Terms and conditions setup
require get_template_directory() . '/partials/campaign-terms-conditions.php';

echo '<article class="post-summary block-card block-card--archive is-style-shadowed">';

	require get_template_directory() . '/partials/campaign-default.php';

echo '</article>';
