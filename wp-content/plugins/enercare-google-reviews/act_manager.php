<?php
	$successful = false;
  
	if (isset($_POST['ecReviewsSettingsForm'])) {
    
    if (isset($_POST['ecreviews_enable_cron'])) {
      update_option('ecreviews_enable_cron', 1);
    } else {
      update_option('ecreviews_enable_cron', 0);
    }
    
    if (isset($_POST['ecreviews_enable_banner_cron'])) {
      update_option('ecreviews_enable_banner_cron', 1);
      $this->updateGmbBannerReviews();
    } else {
      update_option('ecreviews_enable_banner_cron', 0);
    }
    
    if (isset($_POST['ecreviews_admin_email'])) {
      update_option('ecreviews_admin_email', $_POST['ecreviews_admin_email']);
    }
    
    if (isset($_POST['ecreviews_is_debugging'])) {
      update_option('ecreviews_is_debugging', 1);
    } else {
      update_option('ecreviews_is_debugging', 0);
    }
    
		$successful = true;
	}
  
  if (isset($_POST['ecReviewsSyncReviewsForm'])) {
    $sync_count = $_POST['ecreviews_reviews_sync_count'];
    $this->syncGmbReviews($sync_count);
    
    $syncreviews_successful = true;
  }
  
  if (isset($_POST['ecReviewsSyncLocationsForm'])) {
    $this->syncGmbLocations();
    
    $synclocations_successful = true;
  }
  
  $location = null;
  if (isset($_POST['ecReviewsTestPostalCodeForm'])) {
    if (function_exists('getLocationByPostalCode')) {
      $location = getLocationByPostalCode($_POST['ecreviews_postal_code']);
    }
  }
?>