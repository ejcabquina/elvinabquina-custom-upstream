<?php
set_time_limit(0);

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

require_once(ABSPATH . 'wp-load.php' );
global $wpdb;

$filename = "_metatags.csv";
$index = 0;
if (($handle = fopen($filename, "r")) !== FALSE) {
    
    $row = 1;
    while (($data = fgetcsv($handle)) !== FALSE) {
      if ($row == 1) {
        $row++;
        continue;
      }
      $post_url = $data[0];
      $meta_title = $data[1];
      $meta_desc = $data[2];
      
      //$content = html_entity_decode($content);
      $post_name = explode("/", $post_url);
      $post_name = $post_name[sizeof($post_name)-1];
     
      $args = array(
        'name'        => $post_name,
        'post_type'   => array('post','page','location','landing-page'),
        'post_status' => 'publish',
        'numberposts' => 1
      );
      $posts = get_posts($args);
      if ($posts) {
        $wp_post_id = $posts[0]->ID;
      }
      
      echo $post_url . ' => ' . $post_name . ' => ' . $wp_post_id . '<br />';
      
      if ($wp_post_id && $wp_post_id > 0) {
        echo '--url found<br />';
        if (isset($_GET['run'])) {
          if ($meta_title && $meta_title != "") {
            $result_title = update_post_meta($wp_post_id, '_yoast_wpseo_title', $meta_title);
            var_dump($result_title);
          }
          if ($meta_desc && $meta_desc != "") {
            $result_desc = update_post_meta($wp_post_id, '_yoast_wpseo_metadesc', $meta_desc);
            var_dump($result_desc);
          }
        }
      } else {
        echo '--url NOT found<br />';
      }
    
      $row++;
      echo "<hr />";
    }
    echo "total: " . $row;
}
?>