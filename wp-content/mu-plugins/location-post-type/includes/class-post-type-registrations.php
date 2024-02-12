<?php
/**
 * Location Post Type
 *
 * @package   Location_Post_Type
 * @license   GPL-2.0+
 */

/**
 * Register post types and taxonomies.
 *
 * @package Location_Post_Type
 */
class Location_Post_Type_Registrations {

	public $post_type = 'location';

	public $taxonomies = array(
	  'services',
    'provinces'
  );

	public function init() {
		// Add the Location post type and taxonomies
		add_action( 'init', array( $this, 'register' ) );
	}

	/**
	 * Initiate registrations of post type and taxonomies.
	 *
	 * @uses Location_Post_Type_Registrations::register_post_type()
	 * @uses Location_Post_Type_Registrations::register_taxonomy_category()
	 */
	public function register() {
    // register taxonomies first so their similar slugs will match
		$this->register_taxonomy_category();
    $this->register_post_type();		

		// Customizes the post's permalink
		add_filter( 'post_type_link', array( $this, 'customize_post_permalink'), 10, 4);
	}

	/**
	 * Register the custom post type.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	protected function register_post_type() {
		$labels = array(
			'name'               => __( 'Locations', 'location-post-type' ),
			'singular_name'      => __( 'Location', 'location-post-type' ),
			'add_new'            => __( 'Add Location', 'location-post-type' ),
			'add_new_item'       => __( 'Add Location', 'location-post-type' ),
			'edit_item'          => __( 'Edit Location', 'location-post-type' ),
			'new_item'           => __( 'New Location', 'location-post-type' ),
			'view_item'          => __( 'View Location', 'location-post-type' ),
			'search_items'       => __( 'Search Locations', 'location-post-type' ),
			'not_found'          => __( 'No Locations found', 'location-post-type' ),
			'not_found_in_trash' => __( 'No Locations in the trash', 'location-post-type' ),
		);

		$supports = array(
			'title',
			'editor',
			'thumbnail',
			'custom-fields',
			'revisions',
      'page-attributes'
		);

		$args = array(
			'labels'              => $labels,
			'supports'            => $supports,
			'public'              => true,
			'capability_type'     => 'page',
			'rewrite'             => array( 'slug' => 'locations', 'with_front' => false ),
			'menu_position'       => 31,
			'menu_icon'           => 'dashicons-store',
			'has_archive'         => true,
			'taxonomies'          => $this->taxonomies,
      'exclude_from_search' => true,
      'publicly_queryable'  => true,
      'hierarchical'        => true,
      'show_in_rest'        => true
		);

		$args = apply_filters( 'location_post_type_args', $args );

		register_post_type( $this->post_type, $args );
	}

    /**
     * Register a taxonomy for Location Types.
     */
    protected function register_taxonomy_category() {
      $labels = array(
          'name'                       => __( 'Services', 'location-post-type' ),
          'singular_name'              => __( 'Service', 'location-post-type' ),
          'menu_name'                  => __( 'Services', 'location-post-type' ),
          'edit_item'                  => __( 'Edit Service', 'location-post-type' ),
          'update_item'                => __( 'Update Service', 'location-post-type' ),
          'add_new_item'               => __( 'Add New Service', 'location-post-type' ),
          'new_item_name'              => __( 'New Service Name', 'location-post-type' ),
          'parent_item'                => __( 'Parent Type', 'location-post-type' ),
          'parent_item_colon'          => __( 'Parent Type:', 'location-post-type' ),
          'all_items'                  => __( 'All Services', 'location-post-type' ),
          'search_items'               => __( 'Search Services', 'location-post-type' ),
          'popular_items'              => __( 'Popular Services', 'location-post-type' ),
          'separate_items_with_commas' => __( 'Separate Services with commas', 'location-post-type' ),
          'add_or_remove_items'        => __( 'Add or remove Services', 'location-post-type' ),
          'choose_from_most_used'      => __( 'Choose from the most used Services', 'location-post-type' ),
          'not_found'                  => __( 'No Services found.', 'location-post-type' ),
      );

      $args = array(
          'labels'            => $labels,
          'public'            => true,
          'show_in_nav_menus' => true,
          'show_ui'           => true,
          'show_tagcloud'     => true,
          'hierarchical'      => true,
          'rewrite'           => array( 'slug' => 'taxonomy/services', 'with_front' => false ),
          'show_admin_column' => true,
          'query_var'         => true,
          'show_in_rest'      => true
      );

      $args = apply_filters( 'Location_post_type_category_args', $args );
      register_taxonomy( $this->taxonomies[0], array($this->post_type, 'page'), $args );
      
      $labels = array(
          'name'                       => __( 'Provinces', 'location-post-type' ),
          'singular_name'              => __( 'Province', 'location-post-type' ),
          'menu_name'                  => __( 'Provinces', 'location-post-type' ),
          'edit_item'                  => __( 'Edit Province', 'location-post-type' ),
          'update_item'                => __( 'Update Province', 'location-post-type' ),
          'add_new_item'               => __( 'Add New Province', 'location-post-type' ),
          'new_item_name'              => __( 'New Province Name', 'location-post-type' ),
          'parent_item'                => __( 'Parent Type', 'location-post-type' ),
          'parent_item_colon'          => __( 'Parent Type:', 'location-post-type' ),
          'all_items'                  => __( 'All Provinces', 'location-post-type' ),
          'search_items'               => __( 'Search Provinces', 'location-post-type' ),
          'popular_items'              => __( 'Popular Provinces', 'location-post-type' ),
          'separate_items_with_commas' => __( 'Separate Provinces with commas', 'location-post-type' ),
          'add_or_remove_items'        => __( 'Add or remove Provinces', 'location-post-type' ),
          'choose_from_most_used'      => __( 'Choose from the most used Provinces', 'location-post-type' ),
          'not_found'                  => __( 'No Provinces found.', 'location-post-type' ),
      );

      $args = array(
          'labels'            => $labels,
          'public'            => false,
          'show_in_nav_menus' => true,
          'show_ui'           => true,
          'show_tagcloud'     => true,
          'hierarchical'      => true,
          'rewrite'           => array( 'slug' => 'taxonomy/provinces', 'with_front' => false ),
          'show_admin_column' => true,
          'query_var'         => true,
          'show_in_rest'      => true
      );

      $args = apply_filters( 'Location_post_type_category_args', $args );
      register_taxonomy( $this->taxonomies[1], array($this->post_type, 'campaign'), $args );
    }

    /**
     * Customizes the post's permalink
     */
    public function customize_post_permalink($post_link, $post, $leavename, $sample) {
        if ( false !== strpos( $post_link, '%'.$this->taxonomies[0].'%' ) ) {
            //Check for YOAST primary category meta data
            $maybe_yoast = get_post_meta($post->ID,'_yoast_wpseo_primary_'.$this->taxonomies[0],false);
            if( !empty( $maybe_yoast ) ) {
                $primary_term = get_term_by( 'term_id', intval( $maybe_yoast[0] ), $this->taxonomies[0] );
                $slug = $primary_term->slug;
            } else {
                $taxonomies = get_the_terms( $post->ID, $this->taxonomies[0] );
                if ($taxonomies && sizeof($taxonomies) > 0) {
                    $slug = array_pop($taxonomies)->slug;
                } else {
                    $slug = "uncategorized";
                }
            }
            $post_link = str_replace( '%'.$this->taxonomies[0].'%', $slug, $post_link );
        }
        return $post_link;
    }
}