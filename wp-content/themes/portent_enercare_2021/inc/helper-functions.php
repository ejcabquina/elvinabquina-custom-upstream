<?php
/**
 * Helper Functions
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

// Duplicate 'the_content' filters
global $wp_embed;
add_filter( 'enercare_the_content', array( $wp_embed, 'run_shortcode' ), 8 );
add_filter( 'enercare_the_content', array( $wp_embed, 'autoembed'     ), 8 );
add_filter( 'enercare_the_content', 'wptexturize'        );
add_filter( 'enercare_the_content', 'convert_chars'      );
add_filter( 'enercare_the_content', 'wpautop'            );
add_filter( 'enercare_the_content', 'shortcode_unautop'  );
add_filter( 'enercare_the_content', 'do_shortcode'       );

/**
 * Get the first term attached to post
 *
 * @param string $taxonomy
 * @param string/int $field, pass false to return object
 * @param int $post_id
 * @return string/object
 */
function enercare_first_term( $args = [] ) {

	$defaults = [
		'taxonomy'	=> 'category',
		'field'		=> null,
		'post_id'	=> null,
	];

	$args = wp_parse_args( $args, $defaults );

	$post_id = !empty( $args['post_id'] ) ? intval( $args['post_id'] ) : get_the_ID();
	$field = !empty( $args['field'] ) ? esc_attr( $args['field'] ) : false;
	$term = false;

	// Use WP SEO Primary Term
	// from https://github.com/Yoast/wordpress-seo/issues/4038
	if( class_exists( 'WPSEO_Primary_Term' ) ) {
		$term = get_term( ( new WPSEO_Primary_Term( $args['taxonomy'],  $post_id ) )->get_primary_term(), $args['taxonomy'] );
	}

	// Fallback on term with highest post count
	if( ! $term || is_wp_error( $term ) ) {

		$terms = get_the_terms( $post_id, $args['taxonomy'] );

		if( empty( $terms ) || is_wp_error( $terms ) )
			return false;

		// If there's only one term, use that
		if( 1 == count( $terms ) ) {
			$term = array_shift( $terms );

		// If there's more than one...
		} else {

			// Sort by term order if available
			// @uses WP Term Order plugin
			if( isset( $terms[0]->order ) ) {
				$list = array();
				foreach( $terms as $term )
					$list[$term->order] = $term;
				ksort( $list, SORT_NUMERIC );

			// Or sort by post count
			} else {
				$list = array();
				foreach( $terms as $term )
					$list[$term->count] = $term;
				ksort( $list, SORT_NUMERIC );
				$list = array_reverse( $list );
			}

			$term = array_shift( $list );
		}
	}

	// Output
	if( !empty( $field ) && isset( $term->$field ) )
		return $term->$field;

	else
		return $term;
}

/**
 * Conditional CSS Classes
 *
 * @param string $base_classes, classes always applied
 * @param string $optional_class, additional class applied if $conditional is true
 * @param bool $conditional, whether to add $optional_class or not
 * @return string $classes
 */
function enercare_class( $base_classes, $optional_class, $conditional ) {
	return $conditional ? $base_classes . ' ' . $optional_class : $base_classes;
}

/**
 *  Background Image Style
 *
 * @param int $image_id
 * @return string $output
 */
function enercare_bg_image_style( $image_id = false, $image_size = 'full' ) {
	if( !empty( $image_id ) )
		return ' style="background-image: url(' . wp_get_attachment_image_url( $image_id, $image_size ) . ');"';
}

/**
 * Get Icon
 * This function is in charge of displaying SVG icons across the site.
 *
 * Place each <svg> source in the /assets/icons/{group}/ directory, without adding
 * both `width` and `height` attributes, since these are added dynamically,
 * before rendering the SVG code.
 *
 * All icons are assumed to have equal width and height, hence the option
 * to only specify a `$size` parameter in the svg methods.
 *
 */
function enercare_icon( $atts = array() ) {

	$atts = shortcode_atts( array(
		'icon'	=> false,
		'group'	=> 'utility',
		'size'	=> 16,
		'class'	=> false,
		'label'	=> false,
	), $atts );

	if( empty( $atts['icon'] ) )
		return;

	$icon_path = get_theme_file_path( '/assets/icons/' . $atts['group'] . '/' . $atts['icon'] . '.svg' );
	if( ! file_exists( $icon_path ) )
		return;

		$icon = file_get_contents( $icon_path );

		$class = 'svg-icon';
		if( !empty( $atts['class'] ) )
			$class .= ' ' . esc_attr( $atts['class'] );

		if( false !== $atts['size'] ) {
			$repl = sprintf( '<svg class="' . $class . '" width="%d" height="%d" aria-hidden="true" role="img" focusable="false" ', $atts['size'], $atts['size'] );
			$svg  = preg_replace( '/^<svg /', $repl, trim( $icon ) ); // Add extra attributes to SVG code.
		} else {
			$svg = preg_replace( '/^<svg /', '<svg class="' . $class . '"', trim( $icon ) );
		}
		$svg  = preg_replace( "/([\n\t]+)/", ' ', $svg ); // Remove newlines & tabs.
		$svg  = preg_replace( '/>\s*</', '><', $svg ); // Remove white space between SVG tags.

		if( !empty( $atts['label'] ) ) {
			$svg = str_replace( '<svg class', '<svg aria-label="' . esc_attr( $atts['label'] ) . '" class', $svg );
			$svg = str_replace( 'aria-hidden="true"', '', $svg );
		}

		return $svg;
}

/**
 * Has Action
 *
 */
function enercare_has_action( $hook ) {
	ob_start();
	do_action( $hook );
	$output = ob_get_clean();
	return !empty( $output );
}

/**
 * Breadcrumbs
 *
 */
function enercare_breadcrumbs($display = true) {
	if ( function_exists('yoast_breadcrumb') ) {
    global $post;
    // exclude breadcrumbs on these post_types, pages, etc.
    if (
        is_front_page() ||
        is_home() ||
        (is_single() && $post->post_type == "landing-page") ||
        (is_archive('press-release') && isset($_GET['pr-year']))
       ) {
      return false;
    }

    if ($display == "" && $display !== false)
      $display = true;

    $breadcrumbs = yoast_breadcrumb( '<div class="breadcrumbs"><nav id="breadcrumbs" class="breadcrumbs__navigation" aria-label="breadcrumb"><ol class="breadcrumb">','</ul></nav></div>',$display );
    if (!$display)
      return $breadcrumbs;
	}
}

/**
 * Filter the output of Yoast breadcrumbs so each item is an <li> with schema markup
 * @param $link_output
 * @param $link
 *
 * @return string
 */
function enercare_filter_yoast_breadcrumb_items( $link_output, $link ) {

	$new_link_output = '<li>';
	$new_link_output .= '<a href="' . $link['url'] . '" aria-current="page">' . $link['text'] . '</a>';
	$new_link_output .= '</li>';

	return $new_link_output;
}
add_filter( 'wpseo_breadcrumb_single_link', 'enercare_filter_yoast_breadcrumb_items', 10, 2 );


/**
 * Filter the output of Yoast breadcrumbs to remove <span> tags added by the plugin
 * @param $output
 *
 * @return mixed
 */
function enercare_filter_yoast_breadcrumb_output( $output ){

	$from = '<span>';
	$to = '</span>';
	$output = str_replace( $from, $to, $output );

	return $output;
}
add_filter( 'wpseo_breadcrumb_output', 'enercare_filter_yoast_breadcrumb_output' );

/**
 * Banner (Covid)
 *
 */
function enercare_banner($display = true) {
  $global_banner_toggle = get_field('banner_toggle', 'option');
  $banner_text = get_field('banner_text', 'option');
  $post_banner_toggle = get_field('banner_toggle');
  $post_banner_text = get_field('banner_text');
  if ($post_banner_text && !empty(trim($post_banner_text)))
    $banner_text = $post_banner_text;

  if (!$post_banner_toggle && $global_banner_toggle) {
    $output = '<div class="banner-operational-group banner-operational">' . $banner_text . '</div>';
  }

  if ($display == "" && $display !== false)
    $display = true;

  if (!$display)
    return $output;
  else
    echo $output;
}

/**
 * OBA Banner
 *
 */
function enercare_oba_banner($display = true) {
  $global_banner_toggle = get_field('oba_banner_toggle', 'option');
  $banner_text = get_field('oba_banner_text', 'option');
  $post_banner_toggle = get_field('oba_banner_toggle');
  $post_banner_text = get_field('oba_banner_text');
  if ($post_banner_text && !empty(trim($post_banner_text)))
    $banner_text = $post_banner_text;

  if (!$post_banner_toggle && $global_banner_toggle) {
    $output = '<div class="banner-operational-group oba-banner-operational">' . $banner_text . '</div>';
  }

  if ($display == "" && $display !== false)
    $display = true;

  if (!$display)
    return $output;
  else
    echo $output;
}

/**
 * Taxonomy Filtering
 *
 */
function enercare_filter_taxonomy_by_post_type() {
  $post_type = get_post_type();
  if( is_author() ) {
  	$post_type = 'post';
  }

  $output = "";
  //Jeremiah: Commenting this call out and separating it into a different call to more tightly control the output within an individual archive
//  if ($post_type == 'campaign' || $post_type == 'location') {
//    $output .=  get_postal_code_filter();
//  }

  $taxonomies = get_object_taxonomies($post_type, 'object');
  $output .= '<div class="filter-control__container"><div class="filter-control-header"><h2 class="filter-title has-text-align-center">Filter Results</h2><button class="filter-reset">Reset Filters</button></div><div class="taxonomy-filters flex-grid">';

	if(!empty($taxonomies) ) {
		//Certain default taxonomies we want to always exclude
		$tax_exclusions = ['post_tag', 'post_format', 'yst_prominent_words', 'provinces'];
    foreach ( $taxonomies as $taxonomy ) {
    	if(!in_array($taxonomy->name, $tax_exclusions)) {
        $output .= get_taxonomy_filter($taxonomy);
      }
    }
  }
	$output .= '</div></div>';
	echo $output;
}
/**
 * Taxonomy Filtering
 *
 */
function get_taxonomy_filter( $taxonomy ) {
	$output = '<div class="select-container flex-grid-cell">';
	$output .= '<button class="taxonomy-filters__category-control" for="taxonomy_' . $taxonomy->name . '"><span id="label-' . $taxonomy->name . '">' . ucfirst($taxonomy->label) . '</span>'.enercare_icon( array( 'icon' => 'arrow-down', 'size' => 16, 'title' => 'Filter Dropdown' ) ).'</button>';
	$output .= '<div class="multiSelect">';
	$order = "ASC";
	// if "year" is found in taxonomy name, order by DESC.
	if (strpos($taxonomy->name, 'year') !== false) {
		$order = "DESC";
	}
	$terms = get_terms(array('taxonomy' => $taxonomy->name, 'hide_empty' => false, 'orderby' => 'name', 'order' => $order));
	foreach ($terms as $term) {
		$ariapressed = "false";
		// check if term and cat are in URL params, if so, mark ariapressed to true
		if (strpos(esc_attr($_GET['terms-' . $taxonomy->name]), $term->slug) !== false) {
			$ariapressed = "true";
		}

		//Check the count number on the taxonomy. If it is empty then disable the button.
		$disabled = $term->count <= 0 ? 'disabled' : '';
		$output .= '<button class="multi-dropdown-button" '. $disabled .' data-taxonomy="' . $taxonomy->name . '" aria-pressed="' . $ariapressed . '" data-term="' . esc_attr($term->slug) . '">' . esc_html($term->name) .'</button>';
	}
	$output .= '</div></div>';

	return $output;
}
/**
 * Taxonomy Filtering
 *
 */
function enercare_filter_archive( $query ) {
  if ( is_admin() ) { return; }
  if ( (is_archive() && $query->is_main_query()) || is_home() || is_author() ) {
  	$categories = null;
  	$taxquery = null;
  	$author = null;

	  /**
	   * If Author Post Type
	   */
  	if( is_author() ) {
  		$author = get_the_author_meta('ID');
    }

    // exclude featured post from showing up in main query
    if (is_home()) {
      // grab the ID of the page we're using to get posts
      $posts_page_ID = get_option( 'page_for_posts' );
      // grab the 'featured_post' field to exclude from query.
      // have to use wpdb to make a side query otherwise the world falls down using ACF's get_field function, which also uses WP_QUERY
      global $wpdb;
      $featured_post = $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = 'featured_post' AND post_id = %s", $posts_page_ID ) );
      if (!$featured_post)
        $featured_post = get_the_ID();

      //var_dump($featured_post);exit;
      $query->set('post__not_in', array($featured_post));
    }

    // exclude child pages from the location archive page
    if (is_post_type_archive('location')) {
      $query->set('post_parent', 0);
    }

	  /**
	   * If the URL parameter 'cat' is set
	   */
    if (!empty($_GET['cat']) || !empty($_GET['postal_code']) || !empty($_GET['province'])) {
      $cats = array();
      if (!empty($_GET['cat']))
        $cats = explode(",", $_GET['cat']);
      $postal_code = esc_attr($_GET['postal_code']);
      $province = esc_attr($_GET['province']);
      $taxquery = array();
      $categories = array();

      // check if postal_code URL param exists
      if ($postal_code && $postal_code != "") {
        // if we're on the campaign archive page, get campaigns by postal code
        if (is_post_type_archive('campaign')) {
          $campaigns = getCampaignsByPostalCode($postal_code);
          $campaign_ids = array();
          foreach($campaigns as $campaign) {
            if (isset($campaign->ID)) {
              $campaign_ids[] = $campaign->ID;
            }
          }

          // grab all campaigns that have an associated location
          $campaigns_with_locations = getCampaignsWithLocations();
          $cwl_ids[] = array();
          foreach($campaigns_with_locations as $cwl) {
            // remove any campaigns that have already been queried
            if (isset($cwl->ID) && !in_array($cwl->ID, $campaign_ids))
              $cwl_ids[] = $cwl->ID;
          }

          // don't show all other location-specific campaigns.
          if( !empty( $cwl_ids) ) {
            $query->set('post__not_in', $cwl_ids);
          }

        // if we're on the location archive page, get locations by postal code
        } elseif (is_post_type_archive('location')) {
          $location = getLocationByPostalCode($postal_code);
          $location_ids = array();
          if ($location) {
            $location_ids[] = $location->ID;
          }
          if( !empty( $location_ids) ) {
            $query->set('post__in', $location_ids);
          }
        }
      }

      // check if province URL param exists
      if ($province && $province != "") {
        // if we're on the campaign archive page, get campaigns by postal code
        if (is_post_type_archive('campaign')) {
          $taxquery[] = array(
            'taxonomy' => 'provinces',
            'field' => 'slug',
            'terms' => array($province),
          );
        // if we're on the location archive page, get locations by postal code
        } elseif (is_post_type_archive('location')) {
          $taxquery[] = array(
            'taxonomy' => 'provinces',
            'field' => 'slug',
            'terms' => array($province),
          );
        }
      }

		/**
		 * Loop through each category
		 */
      foreach ($cats as $cat) {

      	/*
      	 * if this category find matching url paramter with its name terms-{category}. Explode those terms into an array, then for each term
      	 * get the WP category by slug and push the object into the $categories array
      	 */
      	if (esc_attr($cat) === 'category') {
          $categories_raw = explode(",", esc_attr($_GET['terms-' . $cat]));
          foreach( $categories_raw as $cat ) {
            $categories[] = get_category_by_slug(esc_attr($cat))->term_id;
          }
        } else {
          $taxquery[] = array(
            'taxonomy' => esc_attr($cat),
            'field' => 'slug',
            'author' => $author,
            'terms' => explode(",", esc_attr($_GET['terms-' . $cat] ) ),
          );
        }
      }

      if( !empty( $categories) ) {
        $query->set('category__in', $categories);
      }

      if( !empty( $taxquery ) ) {
        $query->set('tax_query', $taxquery);
      }

      if( !empty( $metaquery ) ) {
        $query->set('meta_query', $metaquery);
      }

    }

  }

  return $query;
}
add_action( 'pre_get_posts', 'enercare_filter_archive');

/**
 * Taxonomy Dropdown Filtering
 *
 */
function get_taxonomy_dropdown_filter( $taxonomy ) {
  $taxonomy_details = get_taxonomy($taxonomy);
	$output = '<div class="select-container">';
    $output .= '<label for="category-select">' . $taxonomy_details->label . '</label>';
    $output .= '<select id="category-select" class="category-filter__select" data-taxonomy="' . $taxonomy . '">';

		$terms = get_terms(array('taxonomy' => $taxonomy, 'hide_empty' => false, 'orderby' => 'name', 'order' => 'ASC'));
    $output .= '<option value="">- Select A ' . $taxonomy_details->label . ' -</option>';
    foreach ($terms as $term) {
      $selected = "";
      if (isset($_GET[$taxonomy]) && esc_attr($_GET[$taxonomy]) == $term->slug)
        $selected = " selected";
      $output .= '<option value="' . $term->slug . '"' . $selected . '>' . $term->name . '</option>';
    }

		$output .= '</select>';
	$output .= '</div>';

	return $output;
}
function the_taxonomy_dropdown_filter($taxonomy) {
	echo get_taxonomy_dropdown_filter($taxonomy);
}

/**
 * Province Filter
 */
function get_province_filter() {
	$output = '<div class="province-filter">';
		$output .= '<label for="province-select">Province</label>';
		$output .= '<select id="province-select" class="province-filter__select" data-taxonomy="province">';

      $provinces = get_terms( array(
        'taxonomy' => 'provinces',
        'hide_empty' => false,
      ));
			$output .= '<option value="">- Select A Province -</option>';
      foreach ($provinces as $province) {
        $selected = "";
        if (isset($_GET['province']) && esc_attr($_GET['province']) == $province->slug)
          $selected = " selected";
        $output .= '<option value="' . $province->slug . '"' . $selected . '>' . $province->name . '</option>';
      }

		$output .= '</select>';
	$output .= '</div>';
	return $output;
}

function the_province_filter() {
	echo get_province_filter();
}

/**
 * @return string
 *
 * Postal Code Filter
 */
function get_postal_code_filter() {
  $output = '<div class="postal-code-filter postal-code-input-container flex-grid-cell">';
    $output .= '<label class="postal-code-filter__label" for="postalCode">Postal Code <span class="required-text" aria-hidden="true">(required)</span></label>';
    $output .= '<label for="postalCode" class="form-error"></label>';
    $output .= '<div class="postal-code-filter__form">';
        $output .= '<input class="postal-code-filter__input" required autocomplete="postal-code" pattern="/^[ABCEGHJ-NPRSTVXY]\d[ABCEGHJ-NPRSTV-Z][ -]?\d[ABCEGHJ-NPRSTV-Z]\d$/i" type="text" id="postalCode" name="postalCode" value="" />';
        $output .= '<button class="postal-code-filter__submit">Go</button>';
    $output .= '</div>';
    $output .= '<label for="postalCode" class="postal-code-filter__message">eg: A1A1A1</label>';
  $output .= '</div>';

  return $output;
}


/**
 * Rendering function for postal code filter
 */
function the_postal_code_filter() {
	echo get_postal_code_filter();
}

/**
 * Get the author's bio image
 */
function get_author_headshot( $author_id = false, $image_class = "author-image" ) {
	if( !$author_id ) {
		$author_id = get_the_author_meta('ID');
	}

	$author_headshot = get_field('author_headshot', 'user_'.$author_id );
	if( empty($author_headshot) ) {
		$author_headshot = get_field('default_author_headshot', 'options');
	}

	return wp_get_attachment_image($author_headshot, 'thumbnail', null, array( 'class' => $image_class, 'role' => 'presentation' ) );
}
