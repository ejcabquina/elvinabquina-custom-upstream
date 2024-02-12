<?php
/**
 * Functions
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

/*
BEFORE MODIFYING THIS THEME:
Please read the instructions here (private repo): https://github.com/billerickson/EA-Starter/wiki
Devs, contact me if you need access
*/

define( 'portent_VERSION', filemtime( get_template_directory() . '/assets/css/main.css' ) );

// General cleanup
include_once( get_template_directory() . '/inc/wordpress-cleanup.php' );

// Theme
include_once( get_template_directory() . '/inc/tha-theme-hooks.php' );
include_once( get_template_directory() . '/inc/layouts.php' );
include_once( get_template_directory() . '/inc/helper-functions.php' );
// include_once( get_template_directory() . '/inc/navigation.php' );
include_once( get_template_directory() . '/inc/enercare-nav.php' );
include_once( get_template_directory() . '/inc/loop.php' );
include_once( get_template_directory() . '/inc/template-tags.php' );
include_once( get_template_directory() . '/inc/site-footer.php' );
include_once( get_template_directory() . '/inc/schema.php' );

// Editor
include_once( get_template_directory() . '/inc/disable-editor.php' );
include_once( get_template_directory() . '/inc/tinymce.php' );

// Functionality
include_once( get_template_directory() . '/inc/login-logo.php' );
include_once( get_template_directory() . '/inc/social-links.php' );

// Blocks
include_once( get_template_directory() . '/inc/acf-blocks.php');
include_once( get_template_directory() . '/inc/block-area.php' );
include_once( get_template_directory() . '/inc/block-overrides.php' );
include_once( get_template_directory() . '/inc/block-patterns.php' );

// Plugin Support
include_once( get_template_directory() . '/inc/acf.php' );
include_once( get_template_directory() . '/inc/amp.php' );
include_once( get_template_directory() . '/inc/shared-counts.php' );
include_once( get_template_directory() . '/inc/wpforms.php' );
include_once( get_template_directory() . '/inc/gforms.php' );

/**
 * Enqueue scripts and styles.
 */
function enercare_scripts() {

	/**
	 * Script Registrations
	 */
	wp_register_script( 'glider-js', get_template_directory_uri() . '/assets/js/glider/glider.min.js', null , null, true );
	wp_register_style( 'glider-css', get_template_directory_uri() . '/assets/js/glider/glider.min.css', null , null );

  /**
   * AddSearch Registrations
   */
  wp_enqueue_script( 'addsearch-client-script', get_template_directory_uri() . '/assets/js/addsearch/addsearch-js-client.min.js',null, null, true );
  wp_enqueue_script( 'addsearch-ui-script', get_template_directory_uri() . '/assets/js/addsearch/addsearch-search-ui.min.js',null, null, true );
	wp_enqueue_style( 'addsearch-style', get_template_directory_uri() . '/assets/js/addsearch/addsearch-search-ui.min.css', null , null );

	/**
	 * Simple State Manager
	 */
	wp_enqueue_script('simple-state-manager', get_template_directory_uri() . '/assets/js/SimpleStateManager/dist/ssm.min.js', null, '4.1.0', true);

	/**
	 * Youtube Lite Registrations
	 * This script is used to handle the youtube lite lazy loading iframe. Youtube's embed iframe is expensive this helps load it only when it is needed
	 */
	wp_register_script('lite-youtube-embed-script', get_template_directory_uri() . '/assets/js/lite-youtube-embed/src/lite-yt-embed.js', null, true );
	wp_register_style('lite-youtube-embed-style', get_template_directory_uri() . '/assets/js/lite-youtube-embed/src/lite-yt-embed.css', null, null );

	/**
	 * Micromodal
	 */
	wp_register_script('micromodal-script', get_template_directory_uri() . '/assets/js/micromodal.min.js', null, null );

	if( ! enercare_is_amp() ) {
		wp_enqueue_script( 'ea-global', get_template_directory_uri() . '/assets/js/global.min.js', array( 'jquery' ), filemtime( get_template_directory() . '/assets/js/global.min.js' ), true );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Move jQuery to footer
		if( ! is_admin() ) {
			wp_deregister_script( 'jquery' );
			wp_register_script( 'jquery', includes_url( '/js/jquery/jquery.min.js' ), false, NULL, true );
			wp_enqueue_script( 'jquery' );
		}

    if( is_archive() ) {
      wp_enqueue_script( 'ea-category-filters', get_template_directory_uri() . '/assets/js/category-filters.js', array( 'jquery' ), filemtime( get_template_directory() . '/assets/js/category-filters.js' ), true );
    }

	if( has_block( 'core/image' ) ) {
		add_action('wp_footer', 'add_diagram_modal');
		wp_enqueue_script( 'micromodal-script' );
		wp_enqueue_script( 'diagram-modal-init', get_template_directory_uri() . '/assets/js/block--image--diagram.js', array('micromodal-script'), true);
	}

	//wp_enqueue_script( 'ea-slider-poly', get_template_directory_uri() . '/assets/js/sliding-menu-polyfills.js', array(), filemtime( get_template_directory() . '/assets/js/sliding-menu-polyfills.js' ), true );

	//wp_enqueue_script( 'ea-slider-menu', get_template_directory_uri() . '/assets/js/sliding-menu.js', array( 'ea-slider-poly' ), filemtime( get_template_directory() . '/assets/js/sliding-menu.js' ), true );

	//wp_enqueue_script( 'ea-slider-init', get_template_directory_uri() . '/assets/js/slide-intiate.js', array( 'ea-slider-menu' ), filemtime( get_template_directory() . '/assets/js/slide-intiate.js' ), true );

	}

	//@todo do this right eventually
	//wp_enqueue_script( 'column-carousels', get_template_directory_uri() . '/assets/js/block--columns-carousel.js', array( 'glider-js', 'simple-state-manager', 'ea-global' ), filemtime( get_template_directory() . '/assets/js/sliding-menu-polyfills.js' ), true );
	wp_enqueue_script( 'glider-js', get_template_directory_uri() . '/assets/js/glider/glider.min.js', null , null, true );
	wp_enqueue_style( 'glider-css', get_template_directory_uri() . '/assets/js/glider/glider.min.css', null , null );


	/**
	 * Offer Related Registrations
	 */
	wp_register_style( 'block-offer-card-style', get_template_directory_uri() . '/assets/css/block--offer-card.css', array('ea-style'), false, 'screen');
	wp_register_script( 'block--offer-card-script', get_template_directory_uri() . '/assets/js/block--offer-card.js', array('micromodal-script'), null, true);

	/**
	 * Blog Post Enqueues
	 */
	if( is_singular('post') ) {
		wp_enqueue_style('dashicons');
		wp_enqueue_script('related-posts-carousel', get_template_directory_uri() . '/assets/js/related-posts.js', array( 'simple-state-manager', 'glider-js', 'ea-global'), null, true);
	}

	/**
	 * Archive Enqueues
	 */
	if( is_post_type_archive( 'location' ) ) {
		wp_enqueue_style( 'enercare-archive-locations', get_template_directory_uri() . '/assets/css/archive--locations.css', array( 'ea-style' ), null );
	}

	if( is_post_type_archive( 'campaign' ) ||
	    is_tax('campaign-category') ) {
		wp_enqueue_script( 'micromodal-script');
		wp_enqueue_script( 'block--offer-card-script');
		wp_enqueue_style( 'enercare-archive-campaigns', get_template_directory_uri() . '/assets/css/archive--campaign.css', array( 'ea-style' ), null );
	}


	wp_enqueue_style( 'ea-fonts', enercare_theme_fonts_url() );
	wp_enqueue_style( 'ea-style', get_template_directory_uri() . '/assets/css/main.css', array(), filemtime( get_template_directory() . '/assets/css/main.css' ) );

  // Only load legal terms toggle functionality on post types that use it
  if( get_field('terms_and_conditions') ) {
    wp_enqueue_script( 'legal-terms', get_template_directory_uri() . '/assets/js/legal-terms.js', null, null, true );
  }

  /**
  * Search Scripts
  **/
  if(is_search()) {
	  wp_enqueue_script('search-scripts', get_template_directory_uri() . '/assets/js/search.js', null, null, true);
  }

	/*
	 * Block level enqueues
	 */
	if( has_block( 'enercare-google-reviews/ecreviews-block' ) ) {
		wp_enqueue_script('glider-js');
		wp_enqueue_style('glider-css');
		wp_enqueue_script( 'enercare-reviews-scripts', get_template_directory_uri() . '/assets/js/block--reviews.js', array( 'glider-js', 'ea-global' ), null, true );
		wp_enqueue_style( 'enercare-reviews-style', get_template_directory_uri() . '/assets/css/block--reviews-block.css', array( 'ea-style', 'glider-css' ), null );
	}

	if( has_block( 'core/table' ) ) {
		wp_enqueue_style( 'enercare-table-styles', get_template_directory_uri() . '/assets/css/block--table.min.css', array('ea-style'), null );

	}

	if( has_block( 'core/cover') ) {
		wp_enqueue_style( 'enercare-cover-styles', get_template_directory_uri() . '/assets/css/block--cover.min.css', array('ea-style'), null );
	}
}
add_action( 'wp_enqueue_scripts', 'enercare_scripts' );

/**
 * Gutenberg scripts and styles
 *
 */
function enercare_gutenberg_scripts() {
	wp_enqueue_style( 'ea-fonts', enercare_theme_fonts_url() );
	wp_enqueue_script( 'ea-editor', get_template_directory_uri() . '/assets/js/editor.js', array( 'wp-blocks', 'wp-dom' ), filemtime( get_template_directory() . '/assets/js/editor.js' ), true );
}
add_action( 'enqueue_block_editor_assets', 'enercare_gutenberg_scripts' );

/**
 * Defer specific theme level scripts
 */
function enercare_defer_scripts( $tag, $handle, $src ) {
	$defer = array(
		'glider-js',
		'legal-terms',
		'block--offer-card-script',
		'micromodal-script',
		'diagram-modal-init',
		'related-posts-carousel',
		'block-locations-served-script',
		'block--faqs-script',
		'block-blog-posts-script',
		'column-carousels',
		'block-tabbed-content-script',
		'portent-accordion-script',
		'enercare-reviews-scripts'
	);

	if ( in_array( $handle, $defer ) ) {
		return '<script src="' . $src . '" defer="defer" type="text/javascript"></script>' . "\n";
	}

	return $tag;
}

/**
 * Theme Fonts URL
 *
 */
function enercare_theme_fonts_url() {
	return false;
}

if ( ! function_exists( 'enercare_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function enercare_setup() {
	/*
	 * Make theme available for translation.
	 */
	load_theme_textdomain( 'ea-starter', get_template_directory() . '/languages' );

	// Editor Styles
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor-style.css' );

	// Admin Bar Styling
	add_theme_support( 'admin-bar', array( 'callback' => '__return_false' ) );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Body open hook
	add_theme_support( 'body-open' );

  // Allow page template selection
  add_theme_support( 'block-templates' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet.
	 *
	 */
	 $GLOBALS['content_width'] = apply_filters( 'enercare_content_width', 768 );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Navigation Menu', 'ea-starter' ),
		'secondary' => esc_html__( 'Secondary Navigation Menu', 'ea-starter' ),
		'footer-areas' => esc_html__('Areas We Serve', 'ea-starter')
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/**
	 * Image Sizes
	 */
	add_image_size( '1-1-small', 100, 100, true);
	add_image_size( '1-1', 300, 300, true);
	add_image_size( '1-1-2x', 600, 600, true);
	add_image_size( '1-1-3x', 900, 900, true);
	add_image_size('3-2-small', 372, 248);
	add_image_size('3-2', 572, 382);
	add_image_size('3-2-2x', 804, 536);
	add_image_size('3-2-3x', 1122, 748);
	add_image_size('16-9-small', 400, 225);
	add_image_size('16-9', 624, 351);
	add_image_size('16-9-2x', 960, 540);
	add_image_size('16-9-3x', 1440, 810);
	add_image_size('21-9-small', 350, 150);
	add_image_size('21-9', 728, 312);
	add_image_size('21-9-2x', 1435, 615);
	add_image_size('21-9-3x', 1980, 850);

	// Gutenberg

	// -- Responsive embeds
	add_theme_support( 'responsive-embeds' );

	// -- Wide Images
	add_theme_support( 'align-wide' );

	// -- Disable custom font sizes
	add_theme_support( 'disable-custom-font-sizes' );

	// -- Editor Font Styles
	add_theme_support( 'editor-font-sizes', array(
		array(
			'name'      => __( 'Small', 'ea-starter' ),
			'shortName' => __( 'S', 'ea-starter' ),
			'size'      => 14,
			'slug'      => 'small'
		),
		array(
			'name'      => __( 'Normal', 'ea-starter' ),
			'shortName' => __( 'M', 'ea-starter' ),
			'size'      => 20,
			'slug'      => 'normal'
		),
		array(
			'name'      => __( 'Large', 'ea-starter' ),
			'shortName' => __( 'L', 'ea-starter' ),
			'size'      => 24,
			'slug'      => 'large'
		),
	) );

	// -- Disable Custom Colors
	add_theme_support( 'disable-custom-colors' );

	// -- Editor Color Palette
	add_theme_support( 'editor-color-palette', array(
		array(
			'name'  => __( 'Red', 'portent' ),
			'slug'  => 'red',
			'color'	=> '#DC1C28',
		),
		array(
			'name'  => __( 'Dark Red', 'portent' ),
			'slug'  => 'dark-red',
			'color' => '#C30D18',
		),
		array(
			'name'  => __( 'Darker Red', 'portent' ),
			'slug'  => 'darker-red',
			'color' => '#AB000B',
		),
		array(
			'name'  => __( 'Light Red', 'portent' ),
			'slug'  => 'light-red',
			'color' => '#F6323E',
		),
		array(
			'name'  => __( 'Lighter Red', 'portent' ),
			'slug'  => 'lighter-red',
			'color' => '#FE4B56',
		),
		array(
			'name'  => __( 'Blue', 'portent' ),
			'slug'  => 'blue',
			'color' => '#B9D9EC',
		),
		array(
			'name'  => __( 'Dark Blue', 'portent' ),
			'slug'  => 'dark-blue',
			'color' => '#A9CDE2',
		),
		array(
			'name'  => __( 'Darker Blue', 'portent' ),
			'slug'  => 'darker-blue',
			'color' => '#95BCD3',
		),
		array(
			'name'  => __( 'Light Blue', 'portent' ),
			'slug'  => 'light-blue',
			'color' => '#C6E3F4',
		),
		array(
			'name'  => __( 'Lighter Blue', 'portent' ),
			'slug'  => 'lighter-blue',
			'color' => '#D1EBFB',
		),
		array(
			'name' => __( 'Bluejay Blue', 'portent'),
			'slug' => 'bluejay-blue',
			'color' => '#134A8E'
		),
		array(
			'name'  => __( 'Grey (Text)', 'portent' ),
			'slug'  => 'grey',
			'color' => '#29292C',
		),
		array(
			'name'  => __( 'Black', 'portent' ),
			'slug'  => 'black',
			'color' => '#000000',
		),
		array(
			'name'  => __( 'Grey 80%', 'portent' ),
			'slug'  => 'grey-80',
			'color' => '#3D3D43',
		),
		array(
			'name'  => __( 'Grey 70%', 'portent' ),
			'slug'  => 'grey-70',
			'color' => '#505156',
		),
		array(
			'name'  => __( 'Grey 60%', 'portent' ),
			'slug'  => 'grey-60',
			'color' => '#646469',
		),
		array(
			'name'  => __( 'Grey 40%', 'portent' ),
			'slug'  => 'grey-40',
			'color' => '#B3B1B1',
		),
		array(
			'name'  => __( 'Grey 30%', 'portent' ),
			'slug'  => 'grey-30',
			'color' => '#D3D1D1',
		),
		array(
			'name'  => __( 'Grey 20%', 'portent' ),
			'slug'  => 'grey-20',
			'color' => '#E6E4E4',
		),
		array(
			'name'  => __( 'Grey 10%', 'portent' ),
			'slug'  => 'grey-10',
			'color' => '#F5F2F2',
		),
		array(
			'name'  => __( 'White', 'portent' ),
			'slug'  => 'white',
			'color' => '#FFFFFF',
		),
	) );

}
endif;
add_action( 'after_setup_theme', 'enercare_setup' );

$args = array(
  'labels' => array(
                'name'                       => __( 'Content Hub Categories', 'page' ),
                'singular_name'              => __( 'Content Hub Category', 'page' ),
                'menu_name'                  => __( 'Content Hub Categories', 'page' ),
                'edit_item'                  => __( 'Edit Category', 'page' ),
                'update_item'                => __( 'Update Category', 'page' ),
                'add_new_item'               => __( 'Add New Category', 'page' ),
                'new_item_name'              => __( 'New Category Name', 'page' ),
                'parent_item'                => __( 'Parent Type', 'page' ),
                'parent_item_colon'          => __( 'Parent Type:', 'page' ),
                'all_items'                  => __( 'All Content Hub Categories', 'page' ),
                'search_items'               => __( 'Search Content Hub Categories', 'page' ),
                'popular_items'              => __( 'Popular Content Hub Categories', 'page' ),
                'separate_items_with_commas' => __( 'Separate Content Hub Categories with commas', 'page' ),
                'add_or_remove_items'        => __( 'Add or remove Content Hub Categories', 'page' ),
                'choose_from_most_used'      => __( 'Choose from the most used Content Hub Categories', 'page' ),
                'not_found'                  => __( 'No Content Hub Categories found.', 'page' ),
              ),
  'public'            => false,
  'show_in_nav_menus' => true,
  'show_ui'           => true,
  'show_tagcloud'     => true,
  'hierarchical'      => true,
  'rewrite'           => array( 'slug' => 'taxonomy/content-hub', 'with_front' => false ),
  'show_admin_column' => true,
  'query_var'         => true,
  'show_in_rest'      => true
);
register_taxonomy( 'content-hub-categories', 'page', $args );

/**
 * Gravity Form Filter for ACF Fields
 */
function acf_load_gravity_form_choices( $field ) {

	// reset choices
	$field['choices'] = array();

	//Get all gravity forms
	if ( is_plugin_active( 'gravityforms/gravityforms.php' ) ) {
		$forms = GFAPI::get_forms();
	}

	 //loop through array and add to field 'choices'
	if( is_array( $forms ) && !empty( $forms )) {
		foreach( $forms as $form ) {
			$field['choices'][ $form['id'] ] = $form['title'];
		}
	} else {
		$field['choices'][ 0 ] = 'No Active Gravity Forms Found';
	}

	// return the field
	return $field;
}

add_filter('acf/load_field/name=gravity_form', 'acf_load_gravity_form_choices');
add_filter('acf/load_field/name=default_contact_form', 'acf_load_gravity_form_choices');
add_filter('acf/load_field/name=default_email_form', 'acf_load_gravity_form_choices');


/**
 * Changing default error message Gravity Forms (GF2.5)
 */
add_filter("gform_validation_message", "gwp_change_message", 10, 2);
function gwp_change_message($message, $form){
	return '<strong class="gform_submission_error gform_submission_error_header hide_summary">
				<span class="gform-icon gform-icon--close" aria-hidden="true"></span>
				Your submission contains errors. Please review and correct errors and try your submission again.
			</strong>';
}

/**
 * Template Hierarchy
 *
 */
function enercare_template_hierarchy( $template ) {

	if( is_home() )
		$template = get_query_template( 'archive' );
  elseif ( is_search() )
    $template = get_query_template( 'search' );
	return $template;
}
add_filter( 'template_include', 'enercare_template_hierarchy' );

/**
 * Replace 's' search param with 'addsearch'
 */
add_filter('init', function() {
  global $wp;

  $wp->add_query_var( 'addsearch' );
  $wp->remove_query_var( 's' );
});
add_filter('request', function($request) {
  if ( isset( $_REQUEST['addsearch'] ) ){
    $request['s'] = $_REQUEST['addsearch'];
  }
  return $request;
});

/**
 * Insert Adobe Analytics Script
 *
 * Injects the adobe script if the current path matches one of the paths in the defined array.
 */
function enercare_adobe_script() {
  $paths = array(
    '/commercial/',
    '/contact-us/',
    '/support/accounts/'
  );
  foreach ($paths as $path) {
    if (strpos($_SERVER['REQUEST_URI'], $path) !== false) {
      echo '<script src="//assets.adobedtm.com/175f7caa2b90/2cdcc41c4baa/launch-4a4c88c4b872.min.js" async></script>';
    }
  }
}
add_action('tha_head_bottom', 'enercare_adobe_script');


/**
 * Appointment Banner
 */
function enercare_appointment_banner() {
  //If page has site override set to Commercial, don't display the banner
  if (get_field('site_override') && get_field('site_override') == 'Commercial') {
    return;
  }

	//We expect this to be TRUE
	$is_banner_active_global = get_field( 'appointment_banner_toggle', 'options');

	//Grab emergency setting
	$is_banner_emergency = get_field( 'appointnent_banner_is_emergency', 'options' );

	//By default we assume this is FALSE because banners need to be explicitly turned off
	$is_banner_active_page = get_field( 'appointment_banner_toggle');

	//Specific location activations / deactivations
	if(is_singular( 'post' ) ) {
		$is_banner_active_global = false;
	}

	if( is_category() || is_tax() || is_post_type_archive('location') ) {
		$is_banner_active_global = false;
	}

		if ( $is_banner_active_global && ! $is_banner_active_page ) {
			//If the banner is active lets get it setup
			$appointment_banner_icon_id = get_field( 'appointment_banner_icon' ) ? get_field( 'appointment_banner_icon' ) : get_field( 'appointment_banner_icon', 'options' );
			$appointment_banner_title = get_field( 'appointment_banner_title' ) ? get_field( 'appointment_banner_title' ) : get_field( 'appointment_banner_title', 'options' );
			$appointment_banner_link_array = get_field( 'appointment_banner_link' ) ? get_field( 'appointment_banner_link' ) : get_field( 'appointment_banner_link', 'options' );
			$appointment_banner_link_text = get_field( 'appointment_banner_link_text' ) ? get_field( 'appointment_banner_link_text' ) : get_field( 'appointment_banner_link_text', 'options' );
			$appointment_banner_is_emergency = '';

			//if the banner is an emergency roll values back to global
			if( $is_banner_emergency ) {
				$appointment_banner_icon_id =  get_field( 'appointment_banner_icon', 'options' );
				$appointment_banner_title = get_field( 'appointment_banner_title', 'options' );
				$appointment_banner_link_array = get_field( 'appointment_banner_link', 'options' );
				$appointment_banner_link_text = get_field( 'appointment_banner_link_text', 'options' );
				$appointment_banner_is_emergency = 'appointment-banner--emergency';
			}

			echo '<aside class="appointment-banner ' . $appointment_banner_is_emergency . '" >';
			echo wp_get_attachment_image( $appointment_banner_icon_id, '1-1', false, array(
				'class' => 'appointment-banner__icon',
				'alt'   => ''
			) );
			echo '<span class="appointment-banner__title">' . $appointment_banner_title . '</span>';
			echo '<a class="appointment-banner__link" aria-label="Current Offers - Get Deals" target="' . $appointment_banner_link_array['target'] . '" href="' . $appointment_banner_link_array['url'] . '">' . $appointment_banner_link_text . '</a>';
			echo '</aside>';
		}

}
add_action('tha_content_before', 'enercare_appointment_banner');

/**
 * Critical CSS
 */
function enercare_critical_css() {
  // don't use critical styles if this URL param is present. used for regenerating critical styles
  if (!isset($_GET['nocritical'])) {
    global $post;
    $base_crit_dir = get_template_directory() . '/assets/css/critical/';
    echo '<!--Start Critical CSS --><style>';
    // these conditionals should match what we are generating in our gulpfile
    if (is_front_page()) {
      echo "/* critical/front.css */ ";
      include_once ( $base_crit_dir . 'front.css' );
    } else if (is_home()) {
      echo "/* critical/blog.css */ ";
      include_once ( $base_crit_dir . 'blog.css' );
    } else if (is_single() && $post->post_type == "post") {
      echo "/* critical/blog-single.css */ ";
      include_once ( $base_crit_dir . 'blog-single.css' );
    } else if (is_archive() && $post->post_type == "location") {
      echo "/* critical/locations.css */ ";
      include_once ( $base_crit_dir . 'locations.css' );
    } else if (is_single() && $post->post_type == "location") {
      echo "/* critical/single-location.css */ ";
      include_once ( $base_crit_dir . 'single-location.css' );
    } else if (is_archive() && $post->post_type == "campaign") {
      echo "/* critical/special-offers.css */ ";
      include_once ( $base_crit_dir . 'special-offers.css' );
    } else if (is_search()) {
      echo "/* critical/search.css */ ";
      include_once ( $base_crit_dir . 'search.css' );
    } else {
      include_once ( $base_crit_dir . 'default.css' );
    }
    echo '</style><!--End Critical CSS -->';
  }
}
add_action( 'tha_head_top', 'enercare_critical_css', 2, 0 );

/**
 * Critical CSS
 */
function enercare_preload_assets() {
  $base_fonts_dir = get_template_directory_uri() . '/assets/fonts/';
  echo '<link rel="preload" href="' . $base_fonts_dir . 'PostGrotesk-Black.WOFF" as="font" type="font/woff">' . "\n";
  echo '<link rel="preload" href="' . $base_fonts_dir . 'PostGrotesk-BlackItalic.WOFF" as="font" type="font/woff">' . "\n";
  echo '<link rel="preload" href="' . $base_fonts_dir . 'PostGrotesk-Bold.WOFF" as="font" type="font/woff">' . "\n";
  echo '<link rel="preload" href="' . $base_fonts_dir . 'PostGrotesk-BoldItalic.WOFF" as="font" type="font/woff">' . "\n";
  echo '<link rel="preload" href="' . $base_fonts_dir . 'PostGrotesk-Book.WOFF" as="font" type="font/woff">' . "\n";
  echo '<link rel="preload" href="' . $base_fonts_dir . 'PostGrotesk-BookItalic.WOFF" as="font" type="font/woff">' . "\n";
  echo '<link rel="preload" href="' . $base_fonts_dir . 'PostGrotesk-Light.WOFF" as="font" type="font/woff">' . "\n";
  echo '<link rel="preload" href="' . $base_fonts_dir . 'PostGrotesk-LightItalic.WOFF" as="font" type="font/woff">' . "\n";
  echo '<link rel="preload" href="' . $base_fonts_dir . 'PostGrotesk-Medium.WOFF" as="font" type="font/woff">' . "\n";
  echo '<link rel="preload" href="' . $base_fonts_dir . 'PostGrotesk-MediumItalic.WOFF" as="font" type="font/woff">' . "\n";
  echo '<link rel="preload" href="' . $base_fonts_dir . 'PostGrotesk-Thin.WOFF" as="font" type="font/woff">' . "\n";
  echo '<link rel="preload" href="' . $base_fonts_dir . 'PostGrotesk-ThinItalic.WOFF" as="font" type="font/woff">' . "\n";
}
add_action( 'tha_head_top', 'enercare_preload_assets', 1, 0 );

/**
 * Adding filter for skipping lazy loading on certain images in the_content
 */
function enercare_skip_loading_lazy_images( $value, $image, $context ) {
  if ( 'the_content' === $context ) {
    // if an image has a class of wp-block-cover__image-background, skip
    if ( false !== strpos( $image, 'wp-block-cover__image-background' ) ) {
      return false;
    }
  }
  return $value;
}
add_filter('wp_img_tag_add_loading_attr', 'enercare_skip_loading_lazy_images', 10, 3);

add_filter( 'script_loader_tag', 'enercare_defer_scripts', 10, 3 );
/**
 * Customizing Pre Get Posts. Campaign Archive Filter All Post Results
 */
if (!is_admin()) {
  add_action( 'pre_get_posts', 'enercare_pre_get_posts' );
}
function enercare_pre_get_posts( $query ) {
	if ( (is_post_type_archive( 'campaign' ) || is_tax('campaign-category')) && $query->is_main_query() ) :
    $today = date("Ymd");
    $query->set('meta_query', array(
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
        'relation' => 'OR',
        array(
          'key'       => 'is_private',
          'compare'   => 'NOT EXISTS',
          'value'     => 'completely'
        ),
        array(
          'key'       => 'is_private',
          'value'     => '1',
          'compare'   => '!='
        )
      ),
      array(
        'priority' => array(
          'key'       => 'priority',
          'compare'   => 'EXISTS',
        ),
      )
    ));
    $query->set('orderby', array(
      'priority'       => 'DESC',
      'start_date'     => 'ASC',
    ));
		$query->set( 'posts_per_page', -1 );
	endif;

	if ( is_post_type_archive( 'location' ) && $query->is_main_query() ) :
		$query->set( 'posts_per_page', -1 );
	endif;
}

/**
 * Hooking Gravity forms to enqueue GF scripts
 */
add_action( 'gform_enqueue_scripts', 'enqueue_custom_script', 10, 2 );
function enqueue_custom_script( $form, $is_ajax ) {
  wp_enqueue_script( 'enercare_gform_script', get_template_directory_uri() . '/assets/js/form--gravity-form.js', array('jquery'), null, true);

	/**
	 * Enqueue script to locate errors within gravity forms and report them to screen reader users through the use of a polite aria-live element.
	 */
    wp_enqueue_script('enercare_gform_error_report', get_template_directory_uri() . '/assets/js/form--gravity-report.js', array('jquery'), null, true);

	$cssClassCheck = strpos($form['cssClass'], 'bogo-form');
	if ( $cssClassCheck !== false && !is_admin() ) {
		wp_enqueue_script( 'enercare_bogo_script', get_template_directory_uri() . '/assets/js/form--bogo-form.js', array('jquery'), null, true);
	}
}

/**
 * Check user being logged in and redirect to appropriate location
 */
function enercare_login_redirect( $redirect_to, $request, $user ) {
  if ($user->user_login == "builder") {
    return home_url('builder/builder-portal');
  }
  return admin_url();
}
add_filter( 'login_redirect', 'enercare_login_redirect', 10, 3 );

/**
 * Add micro modal markup for diagram images
 */
function add_diagram_modal() {
	require get_template_directory() . '/partials/modal-diagram-image.php';
}
