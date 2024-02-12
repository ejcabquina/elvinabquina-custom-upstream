<?php
/**
 * Campaign Post Type
 *
 * @package   Campaign_Post_Type
 * @license   GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Campaign Post Type
 * Plugin URI:  http://github.com/devinsays/team-post-type
 * Description: Enables a campaign post type, taxonomy and metaboxes.
 * Version:     0.1.0
 * Author:      Portent
 * Author URI:  http://github.com/devinsays/team-post-type
 * Text Domain: campaign-post-type
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Required files for registering the post type and taxonomies.
require plugin_dir_path( __FILE__ ) . 'includes/class-post-type.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-post-type-registrations.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-post-type-metaboxes.php';

// Instantiate registration class, so we can add it as a dependency to main plugin class.
$post_type_registrations = new Campaign_Post_Type_Registrations;

// Instantiate main plugin file, so activation callback does not need to be static.
$post_type = new Campaign_Post_Type( $post_type_registrations );

// Register callback that is fired when the plugin is activated.
register_activation_hook( __FILE__, array( $post_type, 'activate' ) );

// Initialize registrations for post-activation requests.
$post_type_registrations->init();

// Initialize metaboxes
//$post_type_metaboxes = new Success_Story_Post_Type_Metaboxes;
//$post_type_metaboxes->init();


/**
 * Adds styling to the dashboard for the post type and adds team posts
 * to the "At a Glance" metabox.
 */
if ( is_admin() ) {

	// Loads for users viewing the WordPress dashboard
	if ( ! class_exists( 'Dashboard_Glancer' ) ) {
		require plugin_dir_path( __FILE__ ) . 'includes/class-dashboard-glancer.php';  // WP 3.8
	}

	require plugin_dir_path( __FILE__ ) . 'includes/class-post-type-admin.php';

	$post_type_admin = new Campaign_Post_Type_Admin( $post_type_registrations );
	$post_type_admin->init();
  
  
  define( 'ECCAMPAIGNUPLOADS_ADMIN_URL',       admin_url() . "admin.php?page=campaign-post-type/uploader.php" );
  add_action('admin_menu', 'ec_campaign_create_menu');

}

// Add Submenu page to Campaign post type
function ec_campaign_create_menu() {
  add_submenu_page ( 
    'edit.php?post_type=campaign', 
    __( 'Upload Campaigns', 'ec-campaign-upload' ), 
    __ ( 'Upload Campaigns', 'ec-campaign-upload' ), 
    'administrator', 
    'upload', 
    'ec_campaign_settings_manager'
  );
}

function ec_campaign_settings_manager() {
  include_once( dirname( __FILE__ ) . '/act_manager.php' );
  include_once( dirname( __FILE__ ) . '/manager.php' );
}

function ec_campaign_process_upload($file) {
  //var_dump($file);
  
  $handle = fopen($file['tmp_name'], "r");
  for ($i = 0; $row = fgetcsv($handle); $i++) {
    // let's skip the header row
    if ($i == 0) {
      continue;
    }
    //var_dump($row);
    
    /* streamlined custom CSV: */
    $title = $row[0];
    $heading = $row[1];
    $subheading = $row[2];
    $start_date = $row[3];
    $end_date = $row[4];
    $campaign_categories = $row[5];
    $image = $row[6];
    $image_alt = $row[7];
    $phone_number = $row[8];
    $link_text = $row[9];
    $link_url = $row[10];
    $cta_text = $row[11];
    $cta_icon = $row[12];
    $terms = $row[13];
    $locations = $row[14];
    $priority = $row[15];
    
    // loop through defined campaign categories and lookup the category term id
    $category_term_ids = array();
    if ($campaign_categories && $campaign_categories != "") {
      // breakout categories by comma
      $cats = explode(",", $campaign_categories);
      foreach ($cats as $cat) {
        $term = get_term_by('name', trim($cat), 'campaign-category');
        if ($term) {
          $category_term_ids[] = $term->term_id;
        }
      }
    }
    
    // loop through defined associated locations and lookup the post id(s)
    $location_post_ids = array();
    if ($locations && $locations != "") {
      // breakout categories by comma
      $locations_arr = explode(",", $locations);
      foreach ($locations_arr as $location) {
        $location_post = get_page_by_title(trim($location), OBJECT, 'location');
        if ($location_post) {
          $location_post_ids[] = $location_post->ID;
        }
      }
    }
    
    // Create post object
    $campaign_post = array(
      'post_type'     => 'campaign',
      'post_title'    => html_entity_decode($title),
      'post_content'  => "",
      'post_status'   => 'publish',
      'post_author'   => 1,
      'post_date'     => date('Y-m-d 00:00:00'),
      //'post_name'     => $post_name
    );
    
    // Insert the post into the database
    $wp_post_id = wp_insert_post($campaign_post);
    //echo "post id: " . $wp_post_id . "<br />";
    if ($wp_post_id == 0) {
      var_dump($campaign_post);
    }
   
    // Associate category taxonomy
    $tax_result = wp_set_post_terms($wp_post_id, $category_term_ids, 'campaign-category');
    //echo "tax result: " . $tax_result . "<br />";
    
    if ($heading)
      update_field('heading', $heading, $wp_post_id);
    if ($subheading)
      update_field('subheading', $subheading, $wp_post_id);
    
    if ($start_date) {
      $start_date = date('Ymd', strtotime($start_date));
      update_field('start_date', $start_date, $wp_post_id);
    }
    if ($end_date) {
      $end_date = date('Ymd', strtotime($end_date));
      update_field('end_date', $end_date, $wp_post_id);
    }
      
    if ($cta_text)
      update_field('cta_text', $cta_text, $wp_post_id);
    if ($phone_number)
      update_field('phone_number', $phone_number, $wp_post_id);
    if ($terms)
      update_field('terms_and_conditions', $terms, $wp_post_id);
    if ($priority)
      update_field('priority', $priority, $wp_post_id);
    
    if ($link_text or $link_url) {
      $link_array = array(
        'title' => $link_text,
        'url' => $link_url,
        'target' => ''
      );
      update_field('destination', $link_array, $wp_post_id);
    }
    
    if (sizeof($location_post_ids) > 0) {
      // field_611eb8c4ffde8
      update_field('locations', $location_post_ids, $wp_post_id);
    }
    
    /*
    if ($metatitle)
      update_post_meta($wp_post_id, '_yoast_wpseo_title', $metatitle);
    if ($metadesc)
      update_post_meta($wp_post_id, '_yoast_wpseo_metadesc', $metadesc);
    */
    
  /*********************************************
  * START FEATURED IMAGE
  *********************************************/
  // if image url is defined, grab and insert
  if ($image && $image != "") {
    $attach_id = null;
    
    $image_lookup = $image;
    if ($_SERVER['SERVER_NAME'] != "www.enercare.ca") {
      $image_lookup = str_replace("https://www.enercare.ca", $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'], $image_lookup);
    }
    // try and lookup the attachment post id from the url
    $attach_id = attachment_url_to_postid($image_lookup);
    
    if (!$attach_id) {
      $filename = basename($image);
    
      // Get the path to the upload directory.
      $wp_upload_dir = wp_upload_dir();
      //var_dump($wp_upload_dir);
      $uploads_dir = $wp_upload_dir['path'] . "/";
      
      // upload file to its listing directory
      file_put_contents($uploads_dir . $filename, file_get_contents($image));
      
      $filetype = wp_check_filetype( basename( $uploads_dir . $filename ), null );
      // Prepare an array of post data for the attachment.
      $attachment = array(
          'guid'           => $wp_upload_dir['url'] . "/" . $filename, 
          'post_mime_type' => $filetype['type'],
          'post_title'     => preg_replace( '/\.[^.]+$/', '', $filename ),
          'post_content'   => '',
          'post_status'    => 'inherit'
      );

      $attach_id = wp_insert_attachment( $attachment, $uploads_dir . $filename, $wp_post_id );
      $attach_data = wp_generate_attachment_metadata( $attach_id, $uploads_dir . $filename );
      wp_update_attachment_metadata( $attach_id, $attach_data );
      if ($image_alt && $image_alt != "") {
        update_post_meta($attach_id, '_wp_attachment_image_alt', $image_alt);
      }
      
    }
    
    if ($attach_id) {
      // sets image for the "icon" campaign field
      update_field('field_611eaf7e7df15', $attach_id, $wp_post_id);
      // sets image as featured image
      set_post_thumbnail($wp_post_id, $attach_id);
    }
  }
  
  /*********************************************
  * END FEATURED IMAGE
  *********************************************/
    
    
    
  }
  fclose($handle);
  
}

function getCampaignsByPostalCode($postalcode) {
  // strip out any spaces
  $postalcode = str_replace(" ", "", $postalcode);
  // grab the location/patch that defines this postal code as being serviced
  $location = getLocationByPostalCode($postalcode);
  
  if ($location) {
    $posts = get_posts(array(
      'numberposts'   => 1,
      'post_type'     => 'campaign',
      'post_status'   => 'publish',
      'meta_query'    => array(
        array(
          'key' => 'locations',
          'value' => $location->ID,
          'compare' => 'LIKE'
        )
      )
    ));
    if (sizeof($posts) > 0)
      return $posts;
    else
      return array(-1);
  }
  return array(-1);
}

// function queries all campaigns that have an associated location
function getCampaignsWithLocations() {
  $posts = get_posts(array(
    'numberposts'   => -1,
    'post_type'     => 'campaign',
    'post_status'   => 'publish',
    'meta_query'    => array(
      'relation' => 'AND',
      array(
        'key' => 'locations',
        'compare' => 'EXISTS'
      ),
      array(
        'key' => 'locations',
        'value'   => array(''),
        'compare' => 'NOT IN'
      )
    )
  ));
  if (sizeof($posts) > 0)
    return $posts;
  else
    return array(-1);
}