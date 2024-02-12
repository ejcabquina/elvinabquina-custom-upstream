<?php
/**
 * FAQ Post Type
 *
 * @package   FAQ_Post_Type
 * @license   GPL-2.0+
 */

/**
 * Register post types and taxonomies.
 *
 * @package FAQ_Post_Type
 */
class FAQ_Post_Type_Registrations {

	public $post_type = 'faq';

	public $taxonomies = array(
	  'faq-category'
  );

	public function init() {
		// Add the FAQ post type and taxonomies
		add_action( 'init', array( $this, 'register' ) );
	}

	/**
	 * Initiate registrations of post type and taxonomies.
	 *
	 * @uses FAQ_Post_Type_Registrations::register_post_type()
	 * @uses FAQ_Post_Type_Registrations::register_taxonomy_category()
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
			'name'               => __( 'FAQs', 'faq-post-type' ),
			'singular_name'      => __( 'FAQ', 'faq-post-type' ),
			'add_new'            => __( 'Add FAQ', 'faq-post-type' ),
			'add_new_item'       => __( 'Add FAQ', 'faq-post-type' ),
			'edit_item'          => __( 'Edit FAQ', 'faq-post-type' ),
			'new_item'           => __( 'New FAQ', 'faq-post-type' ),
			'view_item'          => __( 'View FAQ', 'faq-post-type' ),
			'search_items'       => __( 'Search FAQs', 'faq-post-type' ),
			'not_found'          => __( 'No FAQs found', 'faq-post-type' ),
			'not_found_in_trash' => __( 'No FAQs in the trash', 'faq-post-type' ),
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
			'capability_type'     => 'page',
			'rewrite'             => array( 'slug' => 'faq', 'with_front' => false ),
			'menu_position'       => 31,
			'menu_icon'           => 'dashicons-testimonial',
			'has_archive'         => true,
			'taxonomies'          => $this->taxonomies,
      'exclude_from_search' => true,
      'publicly_queryable'  => true
		);

		$args = apply_filters( 'faq_post_type_args', $args );

		register_post_type( $this->post_type, $args );
	}

    /**
     * Register a taxonomy for FAQ Types.
     */
    protected function register_taxonomy_category() {
      
      $labels = array(
        'name'                       => __( 'FAQ Category', 'faq-post-type' ),
        'singular_name'              => __( 'FAQ Category', 'faq-post-type' ),
        'menu_name'                  => __( 'FAQ Categories', 'faq-post-type' ),
        'edit_item'                  => __( 'Edit FAQ Category', 'faq-post-type' ),
        'update_item'                => __( 'Update FAQ Category', 'faq-post-type' ),
        'add_new_item'               => __( 'Add New FAQ Category', 'faq-post-type' ),
        'new_item_name'              => __( 'New FAQ Category Name', 'faq-post-type' ),
        'parent_item'                => __( 'Parent Type', 'faq-post-type' ),
        'parent_item_colon'          => __( 'Parent Type:', 'faq-post-type' ),
        'all_items'                  => __( 'All FAQ Categories', 'faq-post-type' ),
        'search_items'               => __( 'Search FAQ Categories', 'faq-post-type' ),
        'popular_items'              => __( 'Popular FAQ Categories', 'faq-post-type' ),
        'separate_items_with_commas' => __( 'Separate FAQ Categories with commas', 'faq-post-type' ),
        'add_or_remove_items'        => __( 'Add or remove FAQ Categories', 'faq-post-type' ),
        'choose_from_most_used'      => __( 'Choose from the most used FAQ Categories', 'faq-post-type' ),
        'not_found'                  => __( 'No FAQ Categories found.', 'faq-post-type' ),
      );

      $args = array(
        'labels'            => $labels,
        'public'            => true,
        'show_in_nav_menus' => true,
        'show_ui'           => true,
        'show_tagcloud'     => true,
        'hierarchical'      => true,
        'rewrite'           => array( 'slug' => 'faq', 'with_front' => false ),
        'show_admin_column' => true,
        'query_var'         => true,
      );

      $args = apply_filters( 'FAQ_post_type_category_args', $args );
      
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