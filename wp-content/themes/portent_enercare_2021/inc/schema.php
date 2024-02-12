<?php

add_action('tha_head_bottom', 'enercare_schema');

function enercare_schema() {
  global $post;
  global $ecReviews;
  
  // check if post allows faq schema and contains enercare-faqs block. if so, generate schema for it
  if (get_field('include_faq_schema', $post->ID) && has_block('acf/enercare-faqs', $post->ID)) {
    enercare_schema_faqs($post);
  }
  
  $schema = get_field('schema', $post->ID);
  if ($schema) { 
    //var_dump($gmb_location);
    
    // if AggregateRating schema
    if (strpos($schema, 'AggregateRating') !== false) {
      // grab the associated gmb_location id
      $gmb_location = get_field('gmb_location', $post->ID);
      if ($gmb_location) {
        $location_id = get_field('gmb_location_id', $gmb_location->ID);
        // check if variable ratingValue exists. if so, get value
        if (strpos($schema, '%ratingValue%') !== false) {
          $aggregate_rating = $ecReviews->getAggregateRating($location_id);
          $schema = str_replace("%ratingValue%", number_format($aggregate_rating,1,'.',''), $schema);
        }
        // check if variable ratingCount exists. if so, get value
        if (strpos($schema, '%ratingCount%') !== false) {
          $total_reviews = $ecReviews->getReviewsCount($location_id);
          $schema = str_replace("%ratingCount%", number_format($total_reviews,0,'.',''), $schema);
        }
      }
    }
    
  ?>
  <script type="application/ld+json">
  <?php echo $schema . "\n"; ?>
  </script>
  <?php
  }
  
}

function enercare_schema_faqs($post) {
  $blocks = parse_blocks( $post->post_content );
  $mainEntity = "";
	foreach( $blocks as $block ) {
		if( 'acf/enercare-faqs' !== $block['blockName'] )
			continue;
    
    if (isset($block['attrs']['data']['faqs'])) {
      $faq_ids = $block['attrs']['data']['faqs'];
      foreach ($faq_ids as $fid) {
        $faq_post = get_post($fid);
        $mainEntity .= '
          {
            "@type": "Question",
            "name": "' . htmlspecialchars(trim($faq_post->post_title)) . '",
            "acceptedAnswer": {
              "@type": "Answer",
              "text": "' . htmlspecialchars(trim($faq_post->post_content)) . '"
            }
          },';
      }
      $mainEntity = rtrim($mainEntity, ",");
    }
  }
  if ($mainEntity != "") {
  ?>
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [<?php echo $mainEntity; ?>]
  }
  </script>
  <?php
  }
}

/*
function enercare_schema_locations() {
  global $post;
  global $ecReviews;
  
  $gmb_location = get_field('gmb_location', $post->ID);
  if ($gmb_location) { 
    //var_dump($gmb_location);
    $location_id = get_field('gmb_location_id', $gmb_location->ID);
    $total_reviews = $ecReviews->getReviewsCount($location_id);
    $aggregate_rating = $ecReviews->getAggregateRating($location_id);
  ?>
    
    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "AggregateRating",
      "itemReviewed": {
        "@type": "LocalBusiness",
        "@id": "<?php echo get_the_permalink(); ?>",
        "url": "<?php echo get_the_permalink(); ?>",
        "name": "Enercare Barrie",
        "telephone": "1-866-633-1553",
        "image": {
            "@type": "ImageObject",
            "url": "https://www.enercare.ca/themes/custom/enercare/images/enercare-logo.png"
        },
        "openingHoursSpecification": {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": "Mo-Su",
            "opens": "All day"
            },
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Barrie",
            "addressRegion": "ON",
            "addressCountry": "Canada"
        }
      },
      "ratingValue": "<?php echo number_format($aggregate_rating,1); ?>",
      "bestRating": "5",
      "ratingCount": "<?php echo number_format($total_reviews,0); ?>"
    }
    </script>
  <?php
  }
  
}
*/
