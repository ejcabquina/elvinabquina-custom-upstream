<?php
	$successful = false;
  
	if (isset($_POST['ecCampaignUploadForm'])) {
   
    if (isset($_FILES['ec_campaign_upload_file'])) {
      ec_campaign_process_upload($_FILES['ec_campaign_upload_file']);
    }
    
		$successful = true;
	}
?>