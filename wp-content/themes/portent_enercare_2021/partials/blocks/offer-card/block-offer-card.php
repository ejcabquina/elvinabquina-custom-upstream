<?php
$classes = 'block-offer-card';

if (!empty($block['className'])) {
  $classes .= sprintf(' %s', $block['className']);
}

if( !empty($block['align']) ) {
	$classes .= sprintf( ' align%s', $block['align'] );

}
if( !empty($block['fontSize']) ) {
	$classes .= sprintf( ' is-font-size-%s', $block['fontSize'] );

}

if (!empty($block['backgroundColor'])) {
  $classes .= sprintf(' has-%s-background-color', $block['backgroundColor']);
}

$variation = get_field('variation');
$block_style = get_field('block_style');
$campaign_categories = get_field('campaign_categories');

$and_or = get_field('and_or');
$tax_relation = "AND";
if (!$and_or)
  $tax_relation = "OR";

$unique = get_field('unique');
$override = get_field('override');
$campaign = get_field('campaign');
$cta_image = get_field('cta_image');
$cta_text = get_field('cta_text') ? get_field('cta_text') : __('Shop Plans', 'portent-enercare');
$cta_icon = get_field('cta_icon') ? get_field('cta_icon')['url'] : get_template_directory_uri() . '/assets/icons/action/shopping_cart_black_24dp_rounded.svg';

$tax_query = array();
$tax_query['relation'] = $tax_relation;
if ($campaign_categories && sizeof($campaign_categories) > 0) {
  foreach ($campaign_categories as $cc) {
    $tax_query[] = array(
      'taxonomy'  => 'campaign-category',
      'field'     => 'id',
      'terms'     => $cc
    );
  }
}

if (!$override) {
  $today = date("Ymd");
  $args = array(
    'post_type'     => 'campaign',
    'post_status'   => 'publish',
    'numberposts'   => 1,
    'meta_query'    => array(
      array(
        'relation' => 'AND',
        'start_date' => array(
          'key'       => 'start_date',
          'value'     => $today,
          'compare'   => '<='
        ),
        'end_date' => array(
          'key'       => 'end_date',
          'value'     => $today,
          'compare'   => '>='
        ),
      ),
      array(
        'priority' => array(
          'key'       => 'priority',
          'compare'   => 'EXISTS',
        ),
      )
    ),
    'tax_query'     => $tax_query,
    'orderby' => array(
      'priority'       => 'DESC',
      'start_date'     => 'ASC',
    )
  );

  /*
  $query = new WP_Query($args);
  echo $query->request; exit;
  */

  $campaigns = get_posts($args);
  if (isset($campaigns) && !empty($campaigns)) {
    $campaign = $campaigns[0];
  }
} elseif (isset($campaign) && !empty($campaign)) {
  $campaign = $campaign[0];
}

//only display block if valid campaign found
if (isset($campaign) && !empty($campaign)) {
  $campaign_heading = get_field('heading', $campaign->ID);
  $campaign_subheading = get_field('subheading', $campaign->ID);
  $campaign_excerpt = get_field('excerpt', $campaign->ID);
  $campaign_image = wp_get_attachment_image( get_field('icon', $campaign->ID), '3-2', false, array( 'class' =>'block-offer-card__image', 'alt'=>'') );
  $campaign_expiration = get_field('end_date', $campaign->ID);
  $campaign_destination = get_field('destination', $campaign->ID);

  /**
   * Now that we have an ID to work with check CTA text and Icon and decide replacements.
   *
   * If we do not have an OFFER CARD override but there is a CAMPAIGN level setting use that
   */
  if (!get_field('cta_text') && get_field('cta_text', $campaign->ID)) {
    $cta_text = get_field('cta_text', $campaign->ID);
  }
  if (!get_field('cta_icon') && get_field('cta_icon', $campaign->ID)) {
    $cta_icon = wp_get_attachment_image_src(get_field('cta_icon', $campaign->ID), 'full', false)[0];
  }

  // if defined, we're overriding the offer destination URL
  if (get_field('cta_url_override')) {
    $campaign_destination = get_field('cta_url_override');
  }
  require get_template_directory() . '/partials/campaign-terms-conditions.php';
?>

  <?php
  switch ($variation) {
    case 'Masthead Offer Card':
      $classes .= ' block-offer-card__wrapper__masthead';
      require get_template_directory() . '/partials/campaign-masthead.php';
      break;

    case 'Full Width with Image':
      require get_template_directory() . '/partials/campaign-full-width.php';
      break;

    case 'Blue Highlight':
      $classes .= ' block-offer-card__blue-highlight';
      require get_template_directory() . '/partials/campaign-blue-highlight.php';
      break;

    case 'Classic':
      require get_template_directory() . '/partials/campaign-classic.php';
      break;

    default:
      require get_template_directory() . '/partials/campaign-default.php';
      break;
  }
  ?>

<?php } elseif (is_admin()) { ?>
  <p>*** <strong>No campaigns found for the defined criteria</strong>. This message will only show in the admin editor. ***</p>
<?php } //end campaign exists if 
?>