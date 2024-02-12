<?php

if( ! class_exists( 'Enercare_Nav_Widget_Walker') ) {
	class Enercare_Nav_Widget_Walker extends Walker_Nav_Menu {
		function start_el(&$output, $item, $depth=0, $arg=[], $id=0) {
			$output .= '<li role="listitem" class="' .  implode(" ", $item->classes) . '">';
			$output .= '<a href="' . $item->url . '">';
			$output .= $item->title;
			$output .= '</a>';
			$output .= '</li>';
		}
	}
}

add_filter('widget_nav_menu_args', 'enercare_widget_nav_args'); //for menus in widgets
function enercare_widget_nav_args($args) { //$args is only argument needed to add custom walker
	return array_merge( $args, array(
		'items_wrap' => '<ul role="list" id="%1$s" class="%2$s">%3$s</ul>',
		'walker' => new Enercare_Nav_Widget_Walker(),
	) );
}

/**
 * Custom Navigation Walker to turn hash links into spans
 *
**/
if ( ! class_exists( 'Enercare_Nav_Walker' ) ) :
	class Enercare_Nav_Walker extends Walker_Nav_Menu {
		function start_el(&$output, $item, $depth=0, $args=[], $id=0) {

			//Pull the has children bool. We will be using this to make some decisions
			$has_children = $args->walker->has_children;
			$topLevelWithChildren = '';

			/**
			 * If the menu item has children and a depth of 0 or less we want to apply a data attribute that allows us to easily track it
			 * The sub menu items that have children are place holders and do not require toggles so we only want this at the top level of the
			 * navigation
			 */
			if($has_children && $depth <=0 ) {
				$topLevelWithChildren = 'data-children=true';
			}

			//Check if menu has URL and add url class
			$has_url = ($item->url && $item->url != '#') ? true : false;
			if($has_url) {
				array_push($item->classes, "has-url");
			} elseif($depth == 0) {
                array_push($item->classes, "has-url");
            }

			/**
			 * Start output of list item. This item will contain links, toggle buttons and sub menus. We will use this
			 * element as a pivot at the top level when manipulating menu elements though JS
			 */
			$output .= "<li class='" .  implode(" ", $item->classes) . "' data-open=false ". $topLevelWithChildren .">";

			/**
			 * If the current menu item is at depth 0 AND it has children then open up a button element that we will
			 * use as a toggle. Else, we expect this to be a link within another sub menu, at a lower depth.
			 */
			if($depth <= 0 && $has_children ) {
				$output .= '<button data-interface="menu-toggle" aria-expanded="false" aria-haspopup="true">';
			} elseif($depth <= 0 && !$has_children) {
				$output .= '<a href="' . $item->url . '">';
			}

			/**
			 * use span if using hash as URL. If a sub menu item with a lower depth than 0 has a # as the URL
			 * we expect it to be a placeholder. This is commonly used to break larger tray menus apart into sections.
			 */
			if ($has_url && $depth > 0 ) {
				$output .= '<a href="' . $item->url . '" tabindex=-1>';
            } elseif ($depth > 0) {
				$output .= '<span>';
			}

			/**
			 * Handle the rendering of an icon for menu items.
			 */
			$icon_image_id = get_field('enercare_nav_icon', $item->ID);

			if($icon_image_id) {
				$output .= '<div class="menu-item__icon" aria-hidden="true">';
				$output .= @file_get_contents(get_attached_file($icon_image_id));
				$output .= '</div>';
				$output .= '<span class="menu-item__icon-title">'.$item->title.'</span>';
			} else {
				$output .= $item->title;
			}

			//Closing
			if($depth <= 0 && $args->walker->has_children ) {
				$output .= '</button>';
			} else {
				$output .= '</a>';
			}

			if ($item->url && $item->url != '#' && $depth > 0) {
				$output .= '</a>';
			} elseif ($depth > 0 ) {
				$output .= '</span>';
			}
		}
	}
endif;

/**
 * Creating the nav
 *
 */
function enercare_site_header() {
	//pulling from ACF options
	$phone_number = get_field('default_phone_number', 'option');
  //use default commercial phone number
  if (get_field('site_override') && get_field('site_override') == 'Commercial') {
    $phone_number = get_field('default_commercial_phone_number', 'option');
  }

  $main_nav_toggle = get_field('main_navigation_toggle');
  if (!$main_nav_toggle) {
    echo '<nav role="navigation" id="slider-menu" class="nav-menu">';
    echo'<div class="search-field__wrapper mobile-search-field__wrapper"><div id="mobile-searchfield" class="search-field"></div></div>';
    if( has_nav_menu( 'primary' ) ) {
      // check if page has Commercial site override. Display the commercial primary nav if so
      if (get_field('site_override') && get_field('site_override') == 'Commercial') {
        wp_nav_menu( array( 'menu' => 66, 'container' => false, 'walker' => new Enercare_Nav_Walker() ) );
      } else {
        wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu', 'container' => false, 'walker' => new Enercare_Nav_Walker() ) );
      }
    }
    echo '</nav>';
  }

	//phone section
	echo '<div class="site-header__header-phone header-phone">';
	echo '<span class="header-phone__cta"><strong>Speak with an expert</strong></span>';
	echo '<a class="header-phone__link cl-phone" href="tel:+'.$phone_number.'">';
		echo file_get_contents( get_template_directory() . '/assets/icons/communication/phone_black_24dp_rounded.svg' );
		echo '<span class="screen-reader-text">Activate to call Enercare at &nbsp;</span><strong class="header-phone__number">'.$phone_number.'</strong>';
	echo '</a>';
	echo '</div>';

  if (!$main_nav_toggle) {
    echo enercare_mobile_menu_toggle();
  }

}
add_action( 'tha_header_bottom', 'enercare_site_header', 11 );

/**
 * Super / Secondary Navigation
 */
function enercare_secondary_navigation() {
  $super_nav_toggle = get_field('super_navigation_toggle');
  if (!$super_nav_toggle) {
    echo '<div class="top-bar">';
    echo '<div class="wrap">';
    if ( has_nav_menu( 'secondary' ) ) {
      wp_nav_menu( array( 'theme_location'  => 'secondary',
                          'menu_id'         => 'secondary-menu',
                          'container_class' => 'top-bar__nav'
      ) );
    }
    if (function_exists('enercare_gmb_reviews_display_global_banner')) {
      enercare_gmb_reviews_display_global_banner();
    }
    
    get_search_form(true);
    echo '</div>';
    echo '</div>';
  }
}
add_action( 'tha_header_top', 'enercare_secondary_navigation');

/**
 * Search toggle
 *
 */
function enercare_search_toggle() {
	$output = '<button' . enercare_amp_class( 'search-toggle', 'active', 'searchActive' ) . enercare_amp_toggle( 'searchActive', array( 'menuActive', 'mobileFollow' ) ) . '>';
		$output .= enercare_icon( array( 'icon' => 'search', 'size' => 24, 'class' => 'open' ) );
    $output .= enercare_icon( array( 'icon' => 'close', 'size' => 24, 'class' => 'close', 'group' => 'navigation' ) );
		$output .= '<span class="screen-reader-text">Search</span>';
	$output .= '</button>';
	return $output;
}

/**
 * Mobile menu toggle
 *
 */
function enercare_mobile_menu_toggle() {
	$output = '<button' . enercare_amp_class( 'menu-toggle', 'active', 'menuActive' ) . enercare_amp_toggle( 'menuActive', array( 'searchActive', 'mobileFollow' ) ) . ' id="slider-menu-toggle" aria-expanded="false">';
		$output .= enercare_icon( array( 'icon' => 'menu', 'size' => 24, 'class' => 'open', 'group' => 'navigation' ) );
		$output .= enercare_icon( array( 'icon' => 'close', 'size' => 24, 'class' => 'close', 'group' => 'navigation' ) );
		$output .= '<span class="screen-reader-text">Menu</span>';
	$output .= '</button>';
	return $output;
}

/**
 * Previous/Next Archive Navigation (disabled)
 *
 */
function enercare_archive_navigation() {
	if( ! is_singular() )
		the_posts_navigation();
}
//add_action( 'tha_content_while_after', 'enercare_archive_navigation' );

/**
 * Archive Paginated Navigation
 *
 */
function enercare_archive_paginated_navigation() {

	if ( is_singular() || is_search() ) {
		return;
	}

	global $wp_query;

	// Stop execution if there's only one page.
	if ( $wp_query->max_num_pages <= 1 ) {
		return;
	}

	$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
	$max   = (int) $wp_query->max_num_pages;

	// Add current page to the array.
	if ( $paged >= 1 ) {
		$links[] = $paged;
	}

	// Add the pages around the current page to the array.
	if ( $paged >= 3 ) {
		$links[] = $paged - 1;
		$links[] = $paged - 2;
	}

	if ( ( $paged + 2 ) <= $max ) {
		$links[] = $paged + 2;
		$links[] = $paged + 1;
	}

	echo '<nav class="archive-pagination pagination">';

	$before_number = '<span class="screen-reader-text">' . __( 'Go to page', 'ea-starter' ) . '</span>';

	printf( '<ul role="navigation" aria-label="%s">', esc_attr__( 'Pagination', 'ea-starter' ) );

	// Previous Post Link.
	if ( get_previous_posts_link() ) {
		$label		= __( '<span class="screen-reader-text">Go to</span> Previous Page', 'ea-starter' );
		$link       = get_previous_posts_link( apply_filters( 'genesis_prev_link_text', '&#x000AB; ' . $label ) );
    $previous_posts = previous_posts(false);
    $previous_posts_rtrim = rtrim($previous_posts, "/");
    $link = str_replace($previous_posts, $previous_posts_rtrim, $link);
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Value is hardcoded and safe, not set via input.
		printf( '<li class="pagination-previous">%s</li>' . "\n", $link );
	}

	// Link to first page, plus ellipses if necessary.
	if ( ! in_array( 1, $links, true ) ) {
		$class = 1 === $paged ? ' class="active"' : '';

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Value is known to be safe, not set via input.
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, rtrim(get_pagenum_link( 1 ), "/"), trim( $before_number . ' 1' ) );

		if ( ! in_array( 2, $links, true ) ) {
			$label	= sprintf( '<span class="screen-reader-text">%s</span> &#x02026;', __( 'Interim pages omitted', 'ea-starter' ) );
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Value is known to be safe, not set via input.
			printf( '<li class="pagination-omission">%s</li> ' . "\n", $label );
		}
	}

	// Link to current page, plus 2 pages in either direction if necessary.
	sort( $links );
	foreach ( (array) $links as $link ) {
		$class = '';
		$aria  = '';
		if ( $paged === $link ) {
			$class = ' class="active" ';
			$aria  = ' aria-label="' . esc_attr__( 'Current page', 'ea-starter' ) . '" aria-current="page"';
		}

    $page_link = get_pagenum_link( $link );
    if ($link == 1) {
      $page_link = rtrim($page_link, "/");
    }

		printf(
			'<li%s><a href="%s"%s>%s</a></li>' . "\n",
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Value is safe, not set via input.
			$class,
			esc_url($page_link),
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Value is safe, not set via input.
			$aria,
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Value is safe, not set via input.
			trim( $before_number . ' ' . $link )
		);
	}

	// Link to last page, plus ellipses if necessary.
	if ( ! in_array( $max, $links, true ) ) {

		if ( ! in_array( $max - 1, $links, true ) ) {
			$label = sprintf( '<span class="screen-reader-text">%s</span> &#x02026;', __( 'Interim pages omitted', 'ea-starter' ) );
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Value is known to be safe, not set via input.
			printf( '<li class="pagination-omission">%s</li> ' . "\n", $label );
		}

		$class = $paged === $max ? ' class="active"' : '';
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Value is safe, not set via input.
		printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, get_pagenum_link( $max ), trim( $before_number . ' ' . $max ) );

	}

	// Next Post Link.
	if ( get_next_posts_link() ) {
		$label = __( '<span class="screen-reader-text">Go to</span> Next Page', 'ea-starter' );
		$link       = get_next_posts_link( apply_filters( 'genesis_next_link_text', $label . ' &#x000BB;' ) );
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Value is hardcoded and safe, not set via input.
		printf( '<li class="pagination-next">%s</li>' . "\n", $link );
	}

	echo '</ul>';
	echo '</nav>';
}
add_action( 'tha_content_while_after', 'enercare_archive_paginated_navigation' );
