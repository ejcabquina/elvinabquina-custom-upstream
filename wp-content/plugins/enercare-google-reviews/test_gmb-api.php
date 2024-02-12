<?php
/**
  * Google Business Profile API Testing for the Enercare Google Reviews plugin
  **/
  
require_once('../../../wp-load.php' );

require_once 'google-api-php-client/vendor/autoload.php';
require_once 'google-api-services-mybusiness/MyBusiness.php';
//require_once 'GmbClient.php';

//$google_api_account = 'accounts/103584978847197657494'; // old API account
$google_api_account = 'accounts/106513238376387655903';
//$google_api_account = 'accounts/109730369054319482939';

$client = new Google\Client();
$client->setApplicationName("enercare");
$client->setAuthConfig('gmb_api_credentials.json');
$client->addScope("https://www.googleapis.com/auth/business.manage");
$client->setSubject("enercaredigital@gmail.com");

//$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
$redirect_uri = 'http://localhost';
$client->setRedirectUri($redirect_uri);

$upload_dir = wp_upload_dir();
$tokenPath = $upload_dir['basedir'] . '/private/enercare-google-reviews/token.json';

if (file_exists($tokenPath)) {
  $accessToken = json_decode(file_get_contents($tokenPath), true);
  $client->setAccessToken($accessToken);
} else {
  $tokenPath = __DIR__ . '/token.json';
  if (file_exists($tokenPath)) {
    $accessToken = json_decode(file_get_contents($tokenPath), true);
    $client->setAccessToken($accessToken);
  }
}

$client->authorize();

// If there is no previous token or it's expired.
if ($client->isAccessTokenExpired()) {
  // Refresh the token if possible, else fetch a new one.
  if ($client->getRefreshToken()) {
    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
  } else {
    error_log('Enercare Google Reviews ERROR: Access Token is expired and could not refresh token. Run refreshGMBAccessToken.php locally to regenerate and re-upload token.json');
    //add_action('admin_notices', 'enercare_gmb_reviews_api_access_notice');
  }
  
  // if our private dir does not exist, create it
  if (!file_exists(dirname($tokenPath))) {
    mkdir(dirname($tokenPath), 0700, true);
  }
  file_put_contents($tokenPath, json_encode($client->getAccessToken()));
}

/*
$my_business_account = new Google_Service_MyBusinessAccountManagement($client);
$list_accounts_response = $my_business_account->accounts->listAccounts();
//var_dump($list_accounts_response);exit;

$account = $list_accounts_response[0];
//var_dump($account);
*/

$mybusinessService = new Google_Service_MyBusinessBusinessInformation($client);
$locations = $mybusinessService->accounts_locations;
$queryParams = [
  "pageSize" => 100,
  'readMask' => "name,storeCode,storefrontAddress,profile,metadata"
];

$locationsList = $locations->listAccountsLocations($google_api_account, $queryParams);
$locations = $locationsList->getLocations();

/*
echo 'Locations found: ' . sizeof($locations) . '<hr />';
//var_dump($locations);
foreach ($locations as $location) {
  var_dump($location);
  $location_id = $location->name;
  $location_name = $location->storeCode;
  $locality = $location->storefrontAddress->locality;
  if ($locality) {
    $location_name = $locality . " (" . $location_name . ")";
  }
  $location_details = $location->profile->descriptions;
  $leave_review_url = $location->metadata->newReviewUri;
  
  echo 'location_id: ' . $location_id . '<br />';
  echo 'location_name: ' . $location_name . '<br />';
  echo 'location_details: ' . $location_details . '<br />';
  echo 'leave_review_url: ' . $leave_review_url . '<br />';
  
  echo '<hr />';
}
*/

// ******************** TESTING REVIEWS *************************** /
// Reviews still uses GMB API v4

// Belleville (351)
$reviews_location_id = 'locations/8397680477290990729';
$reviews_location_id = $google_api_account . '/' . $reviews_location_id;
//$reviews_location_id = 'accounts/103584978847197657494/locations/9477176476440020077';

/*
// oshawa (durham east)
$reviews_location_id = 'locations/9477176476440020077';
$reviews_location_id = $google_api_account . '/' . $reviews_location_id;
//$reviews_location_id = 'accounts/103584978847197657494/locations/9477176476440020077';
*/

$gmbService = new Google_Service_MyBusiness($client);
$reviews = $gmbService->accounts_locations_reviews;
$res = $reviews->listAccountsLocationsReviews($reviews_location_id, ['pageSize' => 50]);
var_dump($res);
