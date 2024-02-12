<div class="wrap">
	<h2><?php _e( 'Upload Campaigns', 'campaign-post-type' ); ?></h2>
  
  <div class="description">
    <p>Upload a filled out CSV following the <a href="<?php echo plugin_dir_url( __FILE__ ).'enercare-campaign-import-example.csv'; ?>" target="_blank">campaign import template</a>. Please these things in mind with the import upload:
      <ul class="ul-disc">
      <li>The system does not detect duplicate campaigns. Whatever is on the sheet will be attempted to be imported.</li>
      <li>The system will attempt to find a media image based on the image URL provided. If it does not, it will attempt to upload and attach the new image.<br />
          If you are using a previously used image, please find the URL of that image so we don't create a bunch of duplicated images in the media library.
      </li>
      <li>All campaigns are published immediately, so be sure that the start and end dates are properly defined, which controls their display across the site.</li>
      <li><strong>Be sure to review the results of the upload!</strong></li>
      </ul>
    </p>
    
  </div>
  
  <?php if (isset($successful) && $successful) { ?>
	<div class="updated">
    <p>Campaigns uploaded.</p>
  </div>
	<?php } ?>
  
	<form name="ecCampaignUploadForm" action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="ecCampaignUploadForm" value="">
    
    <table class="form-table" cellspacing="0" cellpadding="0">
      <tbody>
        <tr>
          <th>Campaigns CSV File</th>
          <td>
            <input type="file" name="ec_campaign_upload_file" value="" class="" />
          </td>
        </tr>
        
      </tbody>
    </table>
    
    <p class="submit"><input type="submit" name="submit" value="Upload" class="button-primary" /></p>
  </form>
	
</div>