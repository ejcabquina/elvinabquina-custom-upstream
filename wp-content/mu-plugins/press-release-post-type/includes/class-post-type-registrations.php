<?php
/**
 * Press Release Post Type
 *
 * @package   Press_Release_Post_Type
 * @license   GPL-2.0+
 */

/**
 * Register post types and taxonomies.
 *
 * @package Press_Release_Post_Type
 */
class Press_Release_Post_Type_Registrations {

	public $post_type = 'press-release';

	public $taxonomies = array(
	    'pr-year'
    );

	public function init() {
		// Add the Press Release post type and taxonomies
		add_action( 'init', array( $this, 'register' ) );
	}

	/**
	 * Initiate registrations of post type and taxonomies.
	 *
	 * @uses Press_Release_Post_Type_Registrations::register_post_type()
	 * @uses Press_Release_Post_Type_Registrations::register_taxonomy_category()
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
			'name'               => __( 'Press Releases', 'press-release-post-type' ),
			'singular_name'      => __( 'Press Release', 'press-release-post-type' ),
			'add_new'            => __( 'Add Press Release', 'press-release-post-type' ),
			'add_new_item'       => __( 'Add Press Release', 'press-release-post-type' ),
			'edit_item'          => __( 'Edit Press Release', 'press-release-post-type' ),
			'new_item'           => __( 'New Press Release', 'press-release-post-type' ),
			'view_item'          => __( 'View Press Release', 'press-release-post-type' ),
			'search_items'       => __( 'Search Press Releases', 'press-release-post-type' ),
			'not_found'          => __( 'No Press Releases found', 'press-release-post-type' ),
			'not_found_in_trash' => __( 'No Press Releases in the trash', 'press-release-post-type' )
		);

		$supports = array(
			'title',
			'editor',
			'thumbnail',
			'custom-fields',
			'revisions'
		);

		$args = array(
			'labels'              => $labels,
			'supports'            => $supports,
			'public'              => true,
      'show_in_rest'        => true, // required for Gutenberg
			'capability_type'     => 'page',
			'rewrite'             => array( 'slug' => 'about/press-releases', 'with_front' => false ),
			'menu_position'       => 31,
			'menu_icon'           => 'dashicons-megaphone',
			'has_archive'         => true,
			//'taxonomies'          => $taxonomies,
      'exclude_from_search' => false,
      'publicly_queryable'  => true
		);

		$args = apply_filters( 'press_release_post_type_args', $args );

		register_post_type( $this->post_type, $args );
	}

    /**
     * Register a taxonomy for Press Release Years.
     */
    protected function register_taxonomy_category() {
        $labels = array(
            'name'                       => __( 'Years', 'press-release-post-type' ),
            'singular_name'              => __( 'Year', 'press-release-post-type' ),
            'menu_name'                  => __( 'Years', 'press-release-post-type' ),
            'edit_item'                  => __( 'Edit Year', 'press-release-post-type' ),
            'update_item'                => __( 'Update Year', 'press-release-post-type' ),
            'add_new_item'               => __( 'Add New Year', 'press-release-post-type' ),
            'new_item_name'              => __( 'New Year Name', 'press-release-post-type' ),
            'parent_item'                => __( 'Parent Year', 'press-release-post-type' ),
            'parent_item_colon'          => __( 'Parent Year:', 'press-release-post-type' ),
            'all_items'                  => __( 'All Years', 'press-release-post-type' ),
            'search_items'               => __( 'Search Years', 'press-release-post-type' ),
            'popular_items'              => __( 'Popular Years', 'press-release-post-type' ),
            'separate_items_with_commas' => __( 'Separate Years with commas', 'press-release-post-type' ),
            'add_or_remove_items'        => __( 'Add or remove Years', 'press-release-post-type' ),
            'choose_from_most_used'      => __( 'Choose from the most used Years', 'press-release-post-type' ),
            'not_found'                  => __( 'No Years found.', 'press-release-post-type' )
        );

        $args = array(
            'labels'            => $labels,
            'public'            => true,
            'show_in_nav_menus' => true,
            'show_ui'           => true,
            'show_tagcloud'     => true,
            'hierarchical'      => true,
            'rewrite'           => array( 'slug' => 'about/press-releases/year', 'with_front' => false ),
            'show_admin_column' => true,
            'query_var'         => true,
            'show_in_rest'      => true  // required for Gutenberg
        );

        $args = apply_filters( 'press_release_post_type_category_args', $args );

        register_taxonomy( $this->taxonomies[0], $this->post_type, $args );

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