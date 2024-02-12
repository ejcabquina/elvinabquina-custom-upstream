<?php
/**
  * Plugin Name: Enercare Call Tracking Anchor Manipulation
  * Plugin URI: https://www.enercare.ca
  * Description: This WordPress plugin checks all anchor tags that have a href with "tel:" defined and adds a "cl-phone" class.
  * Version: 1.0
  * Author: Portent
  * Author URI: https://www.portent.com/
  **/

defined('ABSPATH') or die('Nope, not accessing this');

register_activation_hook(__FILE__, 'active');
register_deactivation_hook(__FILE__, 'deactive');

if( !is_admin() ) {
  add_filter('the_content', 'enercare_the_content_manip', 15);

  /*
  function enercare_buffer_start() { ob_start("enercare_content_manip"); } 
  function enercare_buffer_end() { ob_end_flush(); }

  add_action('after_setup_theme', 'enercare_buffer_start');
  add_action('shutdown', 'enercare_buffer_end');
  */
  
}

function enercare_content_manip($the_content) {

  if( $the_content == '' ) return $the_content;
  if( get_query_var('amp') ) return $the_content;

  libxml_use_internal_errors(true);

  $post = new DOMDocument();
  $post->encoding = 'utf-8';
  //$post->loadHTML( utf8_decode( $the_content ) );
  $post->loadHTML(mb_convert_encoding($the_content, 'HTML-ENTITIES', 'UTF-8'));

  //Add Header
  $header = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
  $header .= $post->saveHTML( $post->documentElement );

  $anchors = $post->getElementsByTagName('a');
  
  foreach( $anchors as $anchor ) {
    $anchorHref = $anchor->getAttribute('href');
    $anchorClass = $anchor->getAttribute('class');
    if ($anchorClass != "")
      $anchorClass = $anchorClass . " ";
    
    if (strpos($anchorHref, 'tel:') !== false) {
      var_dump($anchorHref);
      var_dump($anchorClass);
      $anchor->setAttribute('class', $anchorClass . 'cl-phone');
    }
  };

  return $post->saveHTML();
}


function enercare_the_content_manip($the_content) {

  if( $the_content == '' ) return $the_content;
  if( get_query_var('amp') ) return $the_content;

  libxml_use_internal_errors(true);

  $post = new DOMDocument();
  $post->encoding = 'utf-8';
  //$post->loadHTML( utf8_decode( $the_content ) );
  $post->loadHTML(mb_convert_encoding($the_content, 'HTML-ENTITIES', 'UTF-8'));

  //Add Header
  $header = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
  $header .= $post->saveHTML( $post->documentElement );

  $anchors = $post->getElementsByTagName('a');
  
  foreach( $anchors as $anchor ) {
    $anchorHref = $anchor->getAttribute('href');
    $anchorClass = $anchor->getAttribute('class');
    if ($anchorClass != "")
      $anchorClass = $anchorClass . " ";
    
    if (strpos($anchorHref, 'tel:') !== false) {
      var_dump($anchorHref);
      var_dump($anchorClass);
      $anchor->setAttribute('class', $anchorClass . 'cl-phone');
    }
  };

  return $post->saveHTML();
}