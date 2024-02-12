<?php
/**
 * Template Name: Builder Portal
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

// if user is not logged in, redirect user to login
if (!is_user_logged_in()) {
  auth_redirect();
  exit;
}

// Breadcrumbs above page title
add_action( 'tha_entry_top', 'enercare_breadcrumbs', 8 );

// Build the page
require get_template_directory() . '/index.php';
