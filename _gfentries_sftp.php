<?php

if (!empty($_GET['pwd']) && $_GET['pwd'] == 'en3rc@reADMINz0nly') {
  
  if (isset($_GET['getip'])) {
    $myip_contents = file_get_contents("https://ipapi.co/json/?key=WhUoPiyajREhtmerOaQAhxAfyaGLKMo74G1l0Ra8jlqfwMyee6");
    echo $myip_contents;
    die();
  }

  $response_content = "";

  $yesterday = time() - (60 * 60 * 24);
  $today = time();
  //$yesterday = $today;
  //$yesterday = strtotime("10/20/2021");
  //$today = strtotime("10/21/2021");
  
  if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'enercare.test') !== false) {
    $form_entry_urls = array(
      "buy-rent-leads" => "http://enercare.test/gf-entries-in-excel/1c11851cdb1550c4ac68de5d0827d265ffafec714bc0f0affe3338b1b5ce9362",
      "plans-leads" => "http://enercare.test/gf-entries-in-excel/49327079810197e728c5b6782f0ba3b9d6074bed5b9246b06d22db91fed6b34b",
      "smarter-home-leads" => "http://enercare.test/gf-entries-in-excel/98388c96ac137af6ab5ffc6eb4260baaeed2d2b31a19565a196cfacc0c02db17",
      "contact-support" => "http://enercare.test/gf-entries-in-excel/354206178643a0edb3962553e26437b66a7c0de3684999177d010724300c4ca5"
    );
  } elseif (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'dev-enercare.pantheonsite.io') !== false) {
    $form_entry_urls = array(
      "buy-rent-leads" => "https://dev-enercare.pantheonsite.io/gf-entries-in-excel/89dadbc40e2ef308b7b7b8f3dc0f890c7d4d4db1d62a0ca560b83e540890f3ef",
      "plans-leads" => "https://dev-enercare.pantheonsite.io/gf-entries-in-excel/138c4701a74adf28c43b219ca87e7697a24dd7bad3288082bd5a3bb0b1315b29",
      "smarter-home-leads" => "https://dev-enercare.pantheonsite.io/gf-entries-in-excel/c261abd59ac4da581a36cea370c16120dfd15612127db56a7b32e263345518cf",
      "contact-support" => "https://dev-enercare.pantheonsite.io/gf-entries-in-excel/26c15e95eae600c3417c25c9aa72712b235876c5d838ca34b3c0bd967d8e9b78"
    );
  } else if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'www.enercare.ca') !== false) {
    $form_entry_urls = array(
      "buy-rent-leads" => "",
      "plans-leads" => "",
      "smarter-home-leads" => "",
      "contact-support" => ""
    );
  }
  
  // loop through the forms
  $form_count = 1;
  foreach ($form_entry_urls as $form_id => $entry_url) {
    
    $start_date = date('Y-m-d', $yesterday);
    $end_date = date('Y-m-d', $yesterday);
    
    $entry_url .= "?start_date=" . $start_date . "&end_date=" . $end_date;

    echo $entry_url . '<br />';
    $content = file_get_contents($entry_url);
    //$content .= str_replace("\n\r", "", $content);
    
    $response_content .= "Generating CSV for " . $form_id . "<br />";
    $filename_environment = "Development";
    /*
    $filename_environment = "Development";
    if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'www.enercare.ca') !== false) {
      $filename_environment = "Production";
    }
    */
    
    // save the submissions to a csv in our private folder
    $cl_filename = date('Ymd', $today) . '_CLINK_' . $form_id . '_' . date('Ymd', $yesterday) . '_' . $filename_environment . '.csv';
    $file_results = file_put_contents('wp-content/uploads/private/gfcsvs/' . $cl_filename, $content);
    
    if (isset($_GET['upload'])) {
    
      // SFTP to Clearlink
      $clearlink_ssh_conn = ssh2_connect("ftp.clearlink.com", 22, array('hostkey' => 'ssh-rsa'));
      if ($clearlink_ssh_conn) {
        $pubkey = dirname(__FILE__) . '/wp-content/uploads/private/sshkeys/ecwebforms.pub';
        $privkey = dirname(__FILE__) . '/wp-content/uploads/private/sshkeys/ecwebforms';
        
        if (ssh2_auth_pubkey_file($clearlink_ssh_conn, 'portent_app01', $pubkey, $privkey)) {
          // send a file
          $upload_result = ssh2_scp_send($clearlink_ssh_conn, dirname(__FILE__) . '/wp-content/uploads/private/gfcsvs/' . $cl_filename, '/Enercare/Portent_Uploads/' . $cl_filename, 0644);
          $response_content .= "-- Uploading CSV: " . $upload_result . "<br /><br />";
        } else {
          die('SFTP connection made. Public key authentication failed.');
        }
      } else {
        die('Failed connecting to SFTP.');
      }
    
    }
    
    $form_count++;
  }

} else {
  // Access forbidden:
  header('HTTP/1.1 403 Forbidden');
}