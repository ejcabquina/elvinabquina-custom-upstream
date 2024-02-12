<?php
/**
  * Plugin Name: Enercare Google Reviews
  * Plugin URI: https://www.enercare.ca
  * Description: This WordPress plugin integrates with the Google My Business API to pull and manage reviews per Enercare location.
  * Version: 1.0
  * Author: Portent
  * Author URI: https://www.portent.com/
  **/

defined('ABSPATH') or die('Nope, not accessing this');

require_once 'google-api-php-client/vendor/autoload.php';
require_once 'google-api-services-mybusiness/MyBusiness.php';
require_once 'GmbClient.php';

class ECReviews {
  protected $gmbClient;

  public function __construct() {
    register_activation_hook(__FILE__, array($this, 'plugin_activate'));
    register_deactivation_hook(__FILE__, array($this, 'plugin_deactivate'));

    // register post types on 'init'
    add_action('init', array($this, 'registerPostTypes'));

    // locations column management
    add_filter('manage_gmb_location_posts_columns', array($this, 'locations_posts_columns'));
    add_action('manage_gmb_location_posts_custom_column', array($this, 'locations_posts_custom_column'), 10, 2);

    // reviews column management
    add_filter('manage_gmb_review_posts_columns', array($this, 'reviews_posts_columns'));
    add_action('manage_gmb_review_posts_custom_column', array($this, 'reviews_posts_custom_column'), 10, 2);
    // reviews column sorting hooks
    add_filter('manage_edit-gmb_review_sortable_columns', array($this, 'review_table_sorting'));
    add_filter('request', array($this, 'review_location_column_orderby'));
    add_filter('request', array($this, 'review_rating_column_orderby'));
    add_filter('request', array($this, 'review_date_column_orderby'));
    // reviews column filtering
    add_action('restrict_manage_posts', array($this, 'reviews_table_filtering'));
    add_filter('parse_query', array($this, 'reviews_table_filter'));

    if ( is_admin() ) {
      define( 'ECREVIEWS_ADMIN_URL',       admin_url() . "admin.php?page=enercare-google-reviews/index.php" );
      add_action('admin_menu', array($this, 'create_menu'));
    }

    add_action('ecreviews_locations_sync_cron', array($this, 'syncLocationsCron'));
    if (! wp_next_scheduled ( 'ecreviews_locations_sync_cron' )) {
      // schedule cron for every day
      wp_schedule_event(time(), 'daily', 'ecreviews_locations_sync_cron');
    }

    add_action('ecreviews_reviews_sync_cron', array($this, 'syncReviewsCron'));
    if (! wp_next_scheduled ( 'ecreviews_reviews_sync_cron' )) {
      // schedule cron for every day
      wp_schedule_event(time(), 'daily', 'ecreviews_reviews_sync_cron');
    }
    
    add_action('ecreviews_reviews_banner_cron', array($this, 'syncReviewsBannerCron'));
    if (! wp_next_scheduled ( 'ecreviews_reviews_banner_cron' )) {
      // schedule cron for every day
      wp_schedule_event(time(), 'daily', 'ecreviews_reviews_banner_cron');
    }

    $this->initializeGMBClient();
  }

  public function plugin_activate($network_wide) {
    // save default location
    //update_option('ecreviews_default_location', 5);

  }

  public function plugin_deactivate($network_wide) {
    wp_clear_scheduled_hook('ecreviews_locations_sync_cron');
    wp_clear_scheduled_hook('ecreviews_reviews_sync_cron');
    wp_clear_scheduled_hook('ecreviews_reviews_banner_cron');
  }

  // Create Admin Menu
  public function create_menu() {
    // Adds the main menu for this plugin to the Wordpress Admin Panel
    add_menu_page ( __( 'Enercare Reviews', 'enercare-google-reviews' ), __ ( 'Enercare Reviews', 'enercare-google-reviews' ), 10, __FILE__, array($this, 'settings_manager'), 'dashicons-google' );
  }

  public function settings_manager() {
    include_once( dirname( __FILE__ ) . '/act_manager.php' );
    include_once( dirname( __FILE__ ) . '/manager.php' );
  }

  public function registerPostTypes() {
    /**
     * Register GMB Location post type
     */
    $labels = array(
			'name'               => __( 'GMB Locations', 'gmb_location-post-type' ),
			'singular_name'      => __( 'GMB Location', 'gmb_location-post-type' ),
			'add_new'            => __( 'Add GMB Location', 'gmb_location-post-type' ),
			'add_new_item'       => __( 'Add GMB Location', 'gmb_location-post-type' ),
			'edit_item'          => __( 'Edit GMB Location', 'gmb_location-post-type' ),
			'new_item'           => __( 'New GMB Location', 'gmb_location-post-type' ),
			'view_item'          => __( 'View GMB Location', 'gmb_location-post-type' ),
			'search_items'       => __( 'Search GMB Locations', 'gmb_location-post-type' ),
			'not_found'          => __( 'No GMB Locations found', 'gmb_location-post-type' ),
			'not_found_in_trash' => __( 'No GMB Locations in the trash', 'gmb_location-post-type' ),
		);
		$supports = array(
			'title',
			'editor',
      'custom-fields'
		);
		$args = array(
			'labels'              => $labels,
			'supports'            => $supports,
			'public'              => true,
			'capability_type'     => 'page',
			'rewrite'             => array( 'slug' => 'gmb/locations', 'with_front' => false ),
			'menu_position'       => 31,
			'menu_icon'           => 'dashicons-location',
			'has_archive'         => false,
			//'taxonomies'          => $taxonomies,
      'exclude_from_search' => true,
      'publicly_queryable'  => false,
      'show_in_rest'        => true,
		);
		$args = apply_filters( 'gmb_location_post_type_args', $args );
		register_post_type( 'gmb_location', $args );

    /**
     * Register GMB Review post type
     */
    $labels = array(
			'name'               => __( 'GMB Reviews', 'gmb_review-post-type' ),
			'singular_name'      => __( 'GMB Review', 'gmb_review-post-type' ),
			'add_new'            => __( 'Add GMB Review', 'gmb_review-post-type' ),
			'add_new_item'       => __( 'Add GMB Review', 'gmb_review-post-type' ),
			'edit_item'          => __( 'Edit GMB Review', 'gmb_review-post-type' ),
			'new_item'           => __( 'New GMB Review', 'gmb_review-post-type' ),
			'view_item'          => __( 'View GMB Review', 'gmb_review-post-type' ),
			'search_items'       => __( 'Search GMB Reviews', 'gmb_review-post-type' ),
			'not_found'          => __( 'No GMB Reviews found', 'gmb_review-post-type' ),
			'not_found_in_trash' => __( 'No GMB Reviews in the trash', 'gmb_review-post-type' ),
		);
		$supports = array(
			'title',
			'editor',
      'custom-fields'
		);
		$args = array(
			'labels'              => $labels,
			'supports'            => $supports,
			'public'              => true,
			'capability_type'     => 'page',
      'capabilities' => array(
        'create_posts' => false,
      ),
			'rewrite'             => array( 'slug' => 'gmb/reviews', 'with_front' => false ),
			'menu_position'       => 31,
			'menu_icon'           => 'dashicons-format-status',
			'has_archive'         => false,
			//'taxonomies'          => $taxonomies,
      'exclude_from_search' => true,
      'publicly_queryable'  => false,
      'show_in_rest'        => true,
		);
		$args = apply_filters( 'gmb_review_post_type_args', $args );
		register_post_type( 'gmb_review', $args );
  }

  public function locations_posts_columns($defaults) {
    $defaults['gmb_location_reviews'] = 'Reviews';
    $defaults['gmb_location_id']  = 'GMB LID';
    return $defaults;
  }
  public function locations_posts_custom_column($column_name, $post_id) {
    if ($column_name == 'gmb_location_reviews') {
      $location_id = get_post_meta($post_id, 'gmb_location_id', true);
      $total_reviews = $this->getReviewsCount($location_id);
      echo $total_reviews;
    }
    if ($column_name == 'gmb_location_id') {
      echo get_post_meta($post_id, 'gmb_location_id', true);
    }
  }

  public function reviews_posts_columns($defaults) {
    $defaults['gmb_review_location'] = 'Location';
    $defaults['gmb_review_reviewer'] = 'Reviewer';
    $defaults['gmb_review_comment'] = 'Comment';
    $defaults['gmb_review_rating']   = 'Rating';
    $defaults['gmb_review_date']    = 'Review Date';
    $defaults['gmb_review_id']  = 'GMB RID';
    return $defaults;
  }
  public function reviews_posts_custom_column($column_name, $post_id) {
    if ($column_name == 'gmb_review_location') {
      $location_id = get_post_meta($post_id, 'gmb_location_id', true);
      $args = array(
        'meta_key' => 'gmb_location_id',
        'meta_value' => $location_id,
        'post_type' => 'gmb_location',
        'post_status' => 'publish',
        'posts_per_page' => 1
      );
      $posts = get_posts($args);
      if ($posts && sizeof($posts) > 0) {
        echo $posts[0]->post_title;
      }
    }
    if ($column_name == 'gmb_review_reviewer') {
      echo get_post_meta($post_id, 'gmb_review_reviewer', true);
    }
    if ($column_name == 'gmb_review_comment') {
      echo get_the_content($post_id);
    }
    if ($column_name == 'gmb_review_rating') {
      echo get_post_meta($post_id, 'gmb_review_rating', true);
    }
    if ($column_name == 'gmb_review_date') {
      $review_date = get_post_meta($post_id, 'gmb_review_date', true);
      echo date(_x('F d, Y', 'Review date format', 'enercare-google-reviews'), strtotime($review_date));
    }
    if ($column_name == 'gmb_review_id') {
      echo get_post_meta($post_id, 'gmb_review_id', true);
    }
  }
  public function review_table_sorting( $columns ) {
    $columns['gmb_review_location'] = 'gmb_review_location';
    $columns['gmb_review_rating'] = 'gmb_review_rating';
    $columns['gmb_review_date'] = 'gmb_review_date';
    return $columns;
  }
  public function review_location_column_orderby( $vars ) {
    if ( isset( $vars['orderby'] ) && 'gmb_review_location' == $vars['orderby'] ) {
      $vars = array_merge($vars, array(
        'meta_key' => 'gmb_location_id',
        'orderby' => 'meta_value'
      ));
    }
    return $vars;
  }
  public function review_rating_column_orderby( $vars ) {
    if ( isset( $vars['orderby'] ) && 'gmb_review_rating' == $vars['orderby'] ) {
      $vars = array_merge($vars, array(
        'meta_key' => 'gmb_review_rating',
        'orderby' => 'meta_value'
      ));
    }
    return $vars;
  }
  public function review_date_column_orderby( $vars ) {
    if ( isset( $vars['orderby'] ) && 'gmb_review_date' == $vars['orderby'] ) {
      $vars = array_merge($vars, array(
        'meta_key' => 'gmb_review_date',
        'orderby' => 'meta_value'
      ));
    }
    return $vars;
  }
  public function reviews_table_filtering($post_type) {
    global $wpdb;
    if ( $post_type == 'gmb_review' ) {
      $dates = $wpdb->get_results( "SELECT EXTRACT(YEAR FROM meta_value) as year,  EXTRACT( MONTH FROM meta_value ) as month FROM $wpdb->postmeta WHERE meta_key = 'gmb_review_date' AND post_id IN ( SELECT ID FROM $wpdb->posts WHERE post_type = 'gmb_review' AND post_status != 'trash' ) GROUP BY year, month " );

      echo '<select class="" name="gmb_review_date" id="gmb_review_date">';
      echo '<option value="">' . __( 'Show all review dates', 'enercare-google-reviews' ) . '</option>';
      foreach( $dates as $date ) {
        $month = ( strlen( $date->month ) == 1 ) ? 0 . $date->month : $date->month;
        $value = $date->year . '-' . $month . '-' . '01 00:00:00';
        $name = date( 'F Y', strtotime( $value ) );

        $selected = ( !empty( $_GET['event_date'] ) AND $_GET['event_date'] == $value ) ? ' selected="selected"' : '';
        echo '<option value=' . $value . $selected . '>' . $name . '</option>';
      }
      echo '</select>';

      $locations = $this->getLocations();
      echo '<select class="" name="gmb_location_id" id="gmb_location_id">';
      echo '<option value="">' . __( 'Show all locations', 'enercare-google-reviews' ) . '</option>';
      foreach( $locations as $location ) {
        $name = $location->post_title;
        $value = get_post_meta($location->ID, 'gmb_location_id', true);

        $selected = ( !empty( $_GET['gmb_location_id'] ) AND $_GET['gmb_location_id'] == $value ) ? 'selected="selected"' : '';
        echo '<option value=' . $value . $selected . '>' . $name . '</option>';
      }
      echo '</select>';

    }
  }
  function reviews_table_filter($query) {
    if (is_admin() AND $query->query['post_type'] == 'gmb_review') {
      $qv = &$query->query_vars;
      $qv['meta_query'] = array();

      if (!empty($_GET['gmb_review_date'])) {
        $start_time = strtotime( $_GET['gmb_review_date'] );
        $end_time = mktime( 0, 0, 0, date( 'n', $start_time ) + 1, date( 'j', $start_time ), date( 'Y', $start_time ) );
        $end_date = date( 'Y-m-d H:i:s', $end_time );
        $qv['meta_query'][] = array(
          'field' => 'gmb_review_date',
          'value' => array($_GET['gmb_review_date'], $end_date),
          'compare' => 'BETWEEN',
          'type' => 'DATETIME'
        );
      }

      if (!empty($_GET['gmb_location_id'])) {
        $qv['meta_query'][] = array(
          'field' => 'gmb_location_id',
          'value' => $_GET['gmb_location_id'],
          'compare' => '=',
          'type' => 'CHAR'
        );
      }

      if( !empty( $_GET['orderby'] ) AND $_GET['orderby'] == 'gmb_review_date' ) {
        $qv['orderby'] = 'meta_value';
        $qv['meta_key'] = 'gmb_review_date';
        $qv['order'] = strtoupper($_GET['order']);
      }

    }
  }

  public static function postsWhereContentNotEmpty($where = '') {
    $where .= " AND trim(coalesce(post_content, '')) <>''";
    return $where;
  }
  public function getLocations() {
    $posts = get_posts(array(
      'numberposts'   => -1,
      'post_type'     => 'gmb_location',
      'post_status'   => 'publish'
    ));
    return $posts;
  }
  public function getLocationsCount() {
    $args = array(
      'post_type' => 'gmb_location',
      'post_status' => 'publish',
      'posts_per_page' => -1
    );
    $query = new WP_Query($args);
    $total_locations = $query->found_posts;
    return $total_locations;
  }

  public function getReviewsByLocation($location_id, $limit = 25) {
    // add filter to query to omit reviews with no comments. ie. post_content is empty
    add_filter('posts_where', array(__CLASS__, 'postsWhereContentNotEmpty'));
    $posts = get_posts(array(
      'numberposts'   => $limit,
      'post_type'     => 'gmb_review',
      'meta_key'      => 'gmb_location_id',
      'meta_value'    => $location_id,
      'post_status'   => 'publish',
      'suppress_filters' => false,
      'meta_query' => array(
                        array(
                          'key'     => 'gmb_review_rating',
                          'value'   => '4',
                          'compare' => '>='
                        )
                      )
    ));
    // remove the filter
    remove_filter('posts_where', array(__CLASS__, 'postsWhereContentNotEmpty'));
    return $posts;
  }
  public function getReviewsCount($location_id = null) {
    $args = array(
      'post_type' => 'gmb_review',
      'post_status' => 'publish',
      'posts_per_page' => -1,
      'fields' => 'ids',
    );
    if ($location_id) {
      $args['meta_key'] = 'gmb_location_id';
      $args['meta_value'] = $location_id;
    }
    $query = new WP_Query($args);
    $total_reviews = $query->found_posts;
    return $total_reviews;
  }
  public function getAggregateRating($location_id = null) {
    global $wpdb;
    $query = "
      SELECT SUM(pm.meta_value) / COUNT(pm.meta_value) AS 'avg'
      FROM wp_postmeta pm
      WHERE pm.meta_key = 'gmb_review_rating'
    ";
    if ($location_id) {
      $query .= "
        AND pm.post_id IN (
          SELECT pm2.post_id
            FROM wp_postmeta pm2
            LEFT JOIN wp_posts p ON (p.ID = pm2.post_id AND p.post_type = 'gmb_review')
            WHERE pm2.meta_key = 'gmb_location_id' AND pm2.meta_value = '" . $location_id . "'
        )
      ";
    }
    $result = $wpdb->get_row($query);
    if ($result && isset($result->avg))
      return $result->avg;
    else
      return false;
  }

  /**
   * Checks if GMB location post with location_id meta exists. If so, returns true.
   */
  public function checkGmbLocation($location_id) {
    $posts = get_posts(array(
      'numberposts'   => 1,
      'post_type'     => 'gmb_location',
      'meta_query' => array(
        array(
         'key'     => 'gmb_location_id',
         'value'   => $location_id,
         'compare' => 'LIKE'
        )
      )
    ));
    if (sizeof($posts) > 0) {
      $gmbLocation = $posts[0];
      return $gmbLocation->ID;
    } else
      return false;
  }

  /**
   * Creates a GMB location post and adds appropriate meta
   */
  public function insertGmbLocation($location_name, $location_id, $location_details, $leave_review_url, $view_reviews_url) {
    $location_post = array(
      'post_type'     => 'gmb_location',
      'post_title'    => wp_strip_all_tags($location_name),
      'post_content'  => wp_strip_all_tags($location_details),
      'post_status'   => 'publish',
      'post_author'   => 1,
    );
    $new_post_id = wp_insert_post($location_post);
    
    // for newer locations using the MyBusinessBusinessInformation API, we need to append the account ID for looking up reviews
    if (strpos($location_id, 'accounts/103584978847197657494/') === false) {
      $location_id = 'accounts/103584978847197657494/' . $location_id;
    }

    update_field('gmb_location_id', $location_id, $new_post_id);
    update_field('gmb_leave_review_url', $leave_review_url, $new_post_id);
    if ($view_reviews_url)
      update_field('gmb_view_reviews_url', $view_reviews_url, $new_post_id);
  }
  
  /**
   * Updates a GMB location post and its meta
   */
  public function updateGmbLocation($post_id, $location_name, $location_id, $location_details, $leave_review_url, $view_reviews_url) {
    $location_post = array(
      'ID'            => $post_id,
      'post_title'    => wp_strip_all_tags($location_name),
      'post_content'  => wp_strip_all_tags($location_details)
    );
    
    $result = wp_update_post($location_post);
    
    // for newer locations using the MyBusinessBusinessInformation API, we need to append the account ID for looking up reviews
    if (strpos($location_id, 'accounts/103584978847197657494/') === false) {
      $location_id = 'accounts/103584978847197657494/' . $location_id;
    }

    update_field('gmb_location_id', $location_id, $post_id);
    update_field('gmb_leave_review_url', $leave_review_url, $post_id);
    if ($view_reviews_url)
      update_field('gmb_view_reviews_url', $view_reviews_url, $post_id);
  }

  public function getGmbLocations() {
    $locations = $this->gmbClient->getLocations();
    return $locations;
  }

  public function syncGmbLocations() {
    $this->initializeGMBClient();
    $locations = $this->getGmbLocations();
    foreach ($locations as $location) {
      $location_id = $location->name;
      $location_name = $location->storeCode;
      $locality = $location->storefrontAddress->locality;
      if ($locality) {
        $location_name = $locality . " (" . $location_name . ")";
      }
      
      $location_details = $location->profile->descriptions;
      $leave_review_url = $location->metadata->newReviewUrl;
      
      $gmbLocation_id = $this->checkGmbLocation($location_id);
      if ($gmbLocation_id) {
        $this->updateGmbLocation($gmbLocation_id, $location_name, $location_id, $location_details, $leave_review_url, $view_reviews_url);
      } else {
        $this->insertGmbLocation($location_name, $location_id, $location_details, $leave_review_url, $view_reviews_url);
      }
    }
  }

  /**
   * Checks if GMB review post with review_id meta exists. If so, returns true.
   */
  public function checkGmbReview($review_id) {
    $review_posts = get_posts(array(
      'numberposts'   => 1,
      'post_type'     => 'gmb_review',
      'meta_key' => 'gmb_review_id',
      'meta_value' => $review_id,
      )
    );
    
    if (sizeof($review_posts) > 0)
      return true;
    else
      return false;
  }

  /**
   * Creates a GMB review post and adds appropriate meta
   */
  public function insertGmbReview($review_id, $review_name, $review_comment, $location_id, $review_date, $review_rating, $review_reviewer) {
    $location_post = array(
      'post_type'     => 'gmb_review',
      'post_title'    => wp_strip_all_tags($review_name),
      'post_content'  => wp_strip_all_tags($review_comment),
      'post_date'     => date('Y-m-d H:i:s', strtotime($review_date)),
      'post_status'   => 'publish',
      'post_author'   => 1,
    );
    $new_post_id = wp_insert_post($location_post);

    update_field('gmb_review_id', $review_id, $new_post_id);
    update_field('gmb_location_id', $location_id, $new_post_id);
    update_field('gmb_review_date', $review_date, $new_post_id);
    update_field('gmb_review_rating', $review_rating, $new_post_id);
    update_field('gmb_review_reviewer', $review_reviewer, $new_post_id);
  }

  public function syncGmbReviews($page_count = 50) {
    $this->initializeGMBClient();
    $reviews = $this->gmbClient->getReviewsBatchManual($page_count);
    //$reviews = $this->gmbClient->getReviewsBatch();
    //var_dump($reviews);exit;

    foreach ($reviews as $location_id => $review) {
      /* This logic works if using gmbClient->getReviewsBatch()
      $location_id = $review->name;
      $review_id = $review->review->reviewId;
      $review_date = $review->review->createDate;
      $review_rating = $review->review->starRating;
      $review_comment = $review->review->comment;
      $review_reviewer = $review->review->reviewer->displayName;
      */
      foreach ($review["review_response"]["reviews"] as $r) {
        $review_id = $r->reviewId;
        $review_date = $r->createTime;
        $review_rating = $r->starRating;
        $review_comment = $r->comment;
        $review_reviewer = $r->reviewer->displayName;

        if ($review_rating == "ONE")
          $review_rating = 1;
        else if ($review_rating == "TWO")
          $review_rating = 2;
        else if ($review_rating == "THREE")
          $review_rating = 3;
        else if ($review_rating == "FOUR")
          $review_rating = 4;
        else if ($review_rating == "FIVE")
          $review_rating = 5;

        $review_name = $review_reviewer . " on " . date(_x('F d, Y', 'Review date format', 'enercare-google-reviews'), strtotime($review_date));

        if (!$this->checkGmbReview($review_id)) {
          $this->insertGmbReview($review_id, $review_name, $review_comment, $location_id, $review_date, $review_rating, $review_reviewer);
        }
      }

    }
  }
  
  /**
   * Queries for total reviews and aggregate rating and updates an ACF field for the Global Reviews Banner display
   */
  public function updateGmbBannerReviews() {
    $rating = $this->getAggregateRating();
    $count = $this->getReviewsCount();
    if (function_exists("update_field")) {
      update_field('reviews_banner_rating', $rating, 'option');
      update_field('reviews_banner_count', $count, 'option');
    }
  }

  public function syncLocationsCron() {
    if (get_option('ecreviews_enable_cron')) {
      $this->syncGmbLocations();
    }
  }
  public function syncReviewsCron() {
    if (get_option('ecreviews_enable_cron')) {
      $this->syncGmbReviews();
    }
  }
  public function syncReviewsBannerCron() {
    if (get_option('ecreviews_enable_banner_cron')) {
      $this->updateGmbBannerReviews();
    }
  }

  public function initializeGMBClient() {
    $client = new Google_Client();
    $gmbService = new Google_Service_MyBusiness($client);
    $this->gmbClient = new GmbClient($gmbService, $client);
  }

}
$ecReviews = new ECReviews();

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/writing-your-first-block-type/
 */
function create_block_ecreviews_block_init() {
	register_block_type(
    __DIR__,
    array(
      'render_callback' => 'ecreviews_location_block_render_callback'
    )
  );
}
add_action( 'init', 'create_block_ecreviews_block_init' );

function ecreviews_location_block_render_callback( $attributes, $content ) {
  global $post;
  global $ecReviews;

  $location_id = null;
  $location_name = null;
  $location_reviews_url = null;
  $location_see_reviews_url = null;
  $output = "";
  if (!empty($attributes['locationId'])) {
    $location_id = get_post_meta($attributes['locationId'], 'gmb_location_id', true);
    $location_post = get_post($attributes['locationId']);
    $location_name = $location_post->post_title;
    $location_reviews_url = get_post_meta($post->ID, 'google_reviews_url', true);
    $location_see_reviews_url = get_post_meta($post->ID, 'google_see_reviews_url', true);
  }

  // check to see if the transient cache exists and user is not logged in. if so, grab it and return
  if (function_exists('get_transient') && !is_user_logged_in()) {
    $output = get_transient('ec_reviews_' . $location_id);
    if (!empty($output)) {
      if (isset($_GET['ecreviewsdebug']))
        $output = '<!-- debug: from transient cache -->' . "\n" . $output;
      return $output;
    }
  }
  
  $reviews = $ecReviews->getReviewsByLocation($location_id, 12);
  if ( count( $reviews ) === 0 ) {
    $output = 'No reviews were found.';
    if ($location_name) {
      $output = 'No reviews were found for ' . $location_name;
    }
    return $output;
  }

  if ($location_name && $location_name != "") {
    if (!is_admin()) {
      $output .= '<!-- enercare reviews for ' . $location_name . ' -->' . "\n";
    }
    //$output .= '<h2>Reviews for ' . $location_name . '</h2>' . "\n";
  }
  $output .= '<section class="block-reviews alignwide">';
  //@todo need to find a way to pull this out of this template and make the image and text editable
  $output .= '<div class="block-reviews__section-heading">';
      $output .= '<div class="block-reviews__section-heading__title">';
          $output .= '<h2 class="block-reviews__section-heading__header">What Our Customers Think About Us</h2>' . "\n";

  $total_reviews = $ecReviews->getReviewsCount($location_id);
  $aggregate_rating = $ecReviews->getAggregateRating($location_id);
  if ($total_reviews && $aggregate_rating) {
    $output .= '<div class="block-reviews__score-section"><!-- start score container -->';
    $output .= '<strong class="block-reviews__section-heading__score">'. number_format($aggregate_rating, 1) .'</strong>';
    $output .= '<div class="block-reviews__score-star-container">';
    $output .= '<div class="block-reviews__section-heading__stars block-reviews__stars">';
    for($x = 1; $x <= $aggregate_rating; $x++) {
      $output .= '<img width="15px" height="15px" class="block-reviews__star" alt="" role="presentation" src="' . plugin_dir_url(__FILE__) . 'img/full-star-enercare.svg">';
      // if on last loop iteration, check if rating is less than or equal to .5 and if so, show half star. otherwise round up to full star
      if ($x == intval($aggregate_rating)) {
        if (($aggregate_rating-$x) <= .5) {
          $output .= '<img width="15px" height="15px" class="block-reviews__star block-reviews__star--half" alt="" role="presentation" src="' . plugin_dir_url(__FILE__) . 'img/half-star-enercare.svg">';
        } else {
          $output .= '<img width="15px" height="15px" class="block-reviews__star" alt="" role="presentation" src="' . plugin_dir_url(__FILE__) . 'img/full-star-enercare.svg">';
        }
      }
    }
    $output .= '</div>';
    $output .= "<span>Average Based on " . number_format($total_reviews) . " Google Reviews</span>";
    $output .= "</div></div> <!-- end score container -->";
  }
  $output .= '</div><!-- end block-reviews__section-heading__title -->';
  $output .= '</div><!-- end block-reviews__section-heading -->';

  $output .= '<div class="block-reviews__wrapper">';
  $output .= '<div class="block-reviews__glider">';
  foreach ($reviews as $review) {
    if ( $review->post_content !== '' ) {
      $output .= '<div class="block-reviews__review">';

      $review_rating = get_post_meta( $review->ID, 'gmb_review_rating', true );
      $output        .= '<div class="block-reviews__stars">';
      for ( $x = 1; $x <= $review_rating; $x ++ ) {
        $output .= '<img width="15px" height="15px" class="block-reviews__star" alt="" role="presentation" src="' . plugin_dir_url( __FILE__ ) . 'img/full-star-enercare.svg">';
      }
      $output .= '</div>';

      // cap review content at 500 characters
      $post_content = $review->post_content;
      if (strlen($post_content) > 300)
        $post_content = substr($post_content, 0, 300) . "...";

      $output .= '<p class="block-reviews__review__content">' . $post_content . '</p>';
      $output .= '<span class="block-reviews__review__reviewer">' . get_post_meta( $review->ID, 'gmb_review_reviewer', true ) . '</span>';

      $today = date_create();
      $review_date = date_create($review->post_date);
      $interval = date_diff($today, $review_date);

      if ($interval->format('%d') > 182) {
        $review_date = "More than 6 months ago";
      } elseif ($interval->format('%d') > 31) {
        $review_date = "In the last 6 months";
      } elseif ($interval->format('%d') > 7) {
        $review_date = "In the last 30 days";
      } else {
        $review_date = "In the last week";
      }

      $output .= '<span class="block-reviews__review__date">' . $review_date . '</span>';
      $output .= '</div>';
    }
  }
  $output .= '</div>';

  $output .= '<div class="block-carousel__controls">';
  	$output .= '<button aria-label="Previous" class="glider-prev button__prev block-reviews__prev"><img alt="Previous" src="'.plugin_dir_url(__FILE__) . 'img/arrow_back_ios_black_24dp_rounded.svg" /></button>';
  	$output .= '<div role="tablist" class="dots block-reviews__dots"></div>';
  	$output .= '<button aria-label="Next" class="glider-next block-reviews__next"><img alt="Next" src="'.plugin_dir_url(__FILE__) . 'img/arrow_forward_ios_black_24dp_rounded.svg" /></button>';
  $output .= "</div>";

  $output .= '</div>';

  $output .= '<div class="block-reviews__section-footer">';
  if ($location_reviews_url && $location_reviews_url != "") {
    $output .= '<div class="block-reviews__section-footer__add-review"><a href="' . $location_reviews_url . '" class="wp-block-button__link has-red-background-color has-background" target="_blank">Add a Review</a></div>';
  }
  if ($location_see_reviews_url && $location_see_reviews_url != "") {
    $output .= '<div class="block-reviews__section-footer__see-reviews"><a href="' . $location_see_reviews_url . '" class="" target="_blank">See All Google Reviews</a></div>';
  }
  $output .= '</div><!-- end block-reviews__section-footer -->';

  $output .= '</section>';

  if (!is_admin() && !is_user_logged_in() && function_exists('set_transient')) {
    // if the output was processed, set transient cache for 1 day.
    set_transient('ec_reviews_' . $location_id, $output, DAY_IN_SECONDS);
  }

  return $output;
}

function enercare_gmb_reviews_api_access_notice() {
  ?>
  <div class="notice error enercare-google-reviews-api-access-notice" >
    <p><?php _e( 'Enercare Google Reviews ERROR: Access Token is expired and could not refresh token. Run refreshGMBAccessToken.php locally to regenerate and re-upload token.json', 'enercare-google-reviews' ); ?></p>
  </div>
  <?php
}

function enercare_gmb_reviews_display_global_banner() {
  // check if global reviews banner is set to be displayed.
  if (get_field('reviews_banner_toggle', 'option')) {
    $reviews_text = get_field( 'reviews_banner_text', 'option' );
    $reviews_count = get_field( 'reviews_banner_count', 'option' );
    $reviews_rating = get_field( 'reviews_banner_rating', 'option' );
    $output .= '<div class="global-reviews-banner">';
    $output .= '<div class="global-reviews-banner__stars">';
    for ( $x = 1; $x <= intval(ceil($reviews_rating)); $x ++ ) {
      $output .= '<img width="15px" height="15px" class="global-reviews-banner__star" alt="" role="presentation" src="' . plugin_dir_url(__FILE__) . 'img/full-star-enercare.svg">';
    }
    $output .= '</div>';
    $output .= '<div class="global-reviews-banner__count">';
    $output .= number_format($reviews_count, 0) . ' ' . $reviews_text;
    $output .= '</div>';
    $output .= '</div>';
    echo $output;
  }
}
