<div class="wrap">
	<h2><?php _e( 'Enercare Google Reviews Settings', 'enercare-google-reviews' ); ?></h2>
	
  <?php if (isset($successful) && $successful) { ?>
	<div class="updated">
    <p>Settings Saved.</p>
  </div>
	<?php } ?>
  
	<form name="ecReviewsSettingsForm" action="" method="post">
    <input type="hidden" name="ecReviewsSettingsForm" value="">
    
    <table class="form-table" cellspacing="0" cellpadding="0">
      <tbody>
        <tr>
          <th>
            Enable Cron API<br />
            <small>Check this in production to enable the GMB sync cron job to operate.</small>
          </th>
          <td>
            <input type="checkbox" name="ecreviews_enable_cron" value="1"<?php if (get_option('ecreviews_enable_cron')) { ?> checked="checked"<?php } ?> /> Yes<br />
          </td>
        </tr>
        
        <tr>
          <th>
            Enable Banner Cron<br />
            <small>Check this to enable the daily updating the GMB review counts and aggregate rating fields for the global banner display.</small>
          </th>
          <td>
            <input type="checkbox" name="ecreviews_enable_banner_cron" value="1"<?php if (get_option('ecreviews_enable_banner_cron')) { ?> checked="checked"<?php } ?> /> Yes<br />
          </td>
        </tr>
      
        <!--
        <tr>
          <th>API Attempts Threshold</th>
          <td>
            <select name="ecreviews_api_attempts_threshold">
              <option value="2"<?php if (get_option('ecreviews_api_attempts_threshold') == 2) { ?> selected<?php } ?>>2</option>
              <option value="5"<?php if (get_option('ecreviews_api_attempts_threshold') == 5) { ?> selected<?php } ?>>5</option>
              <option value="10"<?php if (get_option('ecreviews_api_attempts_threshold') == 10) { ?> selected<?php } ?>>10</option>
              <option value="15"<?php if (get_option('ecreviews_api_attempts_threshold') == 15) { ?> selected<?php } ?>>15</option>
            </select>
          </td>
        </tr>
        -->
        
        <tr>
          <th>Admin Email for Notifications</th>
          <td>
            <input type="text" name="ecreviews_admin_email" value="<?php echo get_option('ecreviews_admin_email'); ?>" placeholder="email@domain.com, email@domain.com" class="" />
          </td>
        </tr>
        
        <tr>
          <th>Enable Debugging?</th>
          <td>
            <input type="checkbox" name="ecreviews_is_debugging" value="1"<?php if (get_option('ecreviews_is_debugging')) { ?> checked="checked"<?php } ?> /> Yes<br />
          </td>
        </tr>
        
      </tbody>
    </table>
    
    <p class="submit"><input type="submit" name="submit" value="Save" class="button-primary" /></p>
  </form>
  
  <h3><?php _e( 'Reviews Sync', 'enercare-google-reviews' ); ?></h3>
  
  <?php if (isset($syncreviews_successful) && $syncreviews_successful) { ?>
	<div class="updated">
    <p>Reviews synced.</p>
  </div>
	<?php } ?>
  
  <form name="ecReviewsSyncReviewsForm" action="" method="post">
    <input type="hidden" name="ecReviewsSyncReviewsForm" value="">
    
    <table class="form-table" cellspacing="0" cellpadding="0">
      <tbody>
        <tr>
          <th>Total current reviews:</th>
          <td><?php echo $this->getReviewsCount(); ?></td>
        </tr>
        <tr>
          <th>Select the number of reviews to sync for each location</th>
          <td>
            <select name="ecreviews_reviews_sync_count">
              <option value="25">25</option>
              <option value="50">50</option>
              <option value="100">100</option>
              <option value="200">200</option>
            </select>
          </td>
        </tr>
      </tbody>
    </table>
    <p class="submit"><input type="submit" name="submit" value="Run Reviews Sync" class="button-primary" /></p>
  </form>
  
  <h3><?php _e( 'Locations Sync', 'enercare-google-reviews' ); ?></h3>
  
  <?php if (isset($synclocations_successful) && $synclocations_successful) { ?>
	<div class="updated">
    <p>Locations synced.</p>
  </div>
	<?php } ?>
  
  <form name="ecReviewsSyncLocationsForm" action="" method="post">
    <input type="hidden" name="ecReviewsSyncLocationsForm" value="">
    
    <table class="form-table" cellspacing="0" cellpadding="0">
      <tbody>
        <tr>
          <th>Total current locations:</th>
          <td><?php echo $this->getLocationsCount(); ?></td>
        </tr>
      </tbody>
    </table>
    <p class="submit"><input type="submit" name="submit" value="Run Locations Sync" class="button-primary" /></p>
  </form>
  
  <form name="ecReviewsTestPostalCodeForm" action="" method="post">
    <input type="hidden" name="ecReviewsTestPostalCodeForm" value="">
    
    <table class="form-table" cellspacing="0" cellpadding="0">
      <tbody>
        <tr>
          <th>Postal code:</th>
          <td>
            <input type="text" name="ecreviews_postal_code" value="<?php if (isset($_POST['ecreviews_postal_code'])) { echo $_POST['ecreviews_postal_code']; } ?>" placeholder="A2A 2A2" class="" />
            <?php if (isset($_POST['ecreviews_postal_code'])) { ?>
              <?php if ($location) { ?>
                <a href="<?php echo get_the_permalink($location->ID); ?>"><?php echo $location->post_title; ?></a>
              <?php } else { ?>
                No locations found for that postal code.
              <?php } ?>
            <?php } ?>
          </td>
        </tr>
      </tbody>
    </table>
    <p class="submit"><input type="submit" name="submit" value="Search" class="button-primary" /></p>
  </form>
  
</div>