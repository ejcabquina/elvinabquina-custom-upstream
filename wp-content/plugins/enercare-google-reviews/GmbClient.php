<?php

/**
 * Class GmbClient
 *
 * @package Drupal\enercare_reviews
 */
class GmbClient {

  /**
   * @var \Google_Service_MyBusiness
   */
  protected $gmb_service;

  /**
   * @var \Google_Client
   */
  protected $google_client;

  /**
   * @var string
   */
  protected $service_account_credentials_file;

  /**
   * @var string
   */
  protected $oauth_credentials_file;

  /**
   * @var string
   */
  protected $google_api_project_name;

  /**
   * @var string
   */
  protected $google_api_scope;

  /**
   * @var string
   */
  protected $google_api_user;

  /**
   * @var string
   */
  protected $google_api_account;

  /**
   * @var string
   */
  protected $google_api_refresh_token;

  /**
   * GmbClient constructor.
   *
   * @param \Google_Service_MyBusiness $gmb_service
   * @param \Google_Client $google_client
   *
   * @throws \Google_Exception
   */
  public function __construct(\Google_Service_MyBusiness $gmb_service, \Google_Client $google_client) {
    // TODO: Move some of these to admin config
    $this->gmb_service = $gmb_service;
    $this->google_client = $google_client;
    $this->service_account_credentials_file = 'Enercare-19ffdfc49004.json';
    $this->oauth_credentials_file = 'gmb_api_credentials.json';
    $this->google_api_project_name = 'enercare';
    $this->google_api_scope = 'https://www.googleapis.com/auth/business.manage';
    $this->google_api_user = 'enercaredigital@gmail.com';
    //$this->google_api_account = 'accounts/103584978847197657494'; // old account number pre MyBusinessBusinessInfo API
    $this->google_api_account = 'accounts/106513238376387655903';
    //$this->google_api_refresh_token = '1//04sQVR_Ny5GqrCgYIARAAGAQSNwF-L9IrwDDO4UbJoFBGXSF_3Y0j1TgmFfZo5f0EDfVDNdKhWves6SuTAQ3JoQQiiuMIv9Jqybg';

    $this->authenticate();
  }

  /**
   *
   */
  protected function authenticateWithServiceAccount() {
    putenv('GOOGLE_APPLICATION_CREDENTIALS=' . plugin_dir_path( __FILE__ ) . $this->service_account_credentials_file);
    $this->google_client->useApplicationDefaultCredentials();
  }

  /**
   * @throws \Google_Exception
   */
  protected function authenticate() {
    $this->google_client->setApplicationName($this->google_api_project_name);
    $this->google_client->setAuthConfig(plugin_dir_path( __FILE__ ) . $this->oauth_credentials_file);
    $this->google_client->addScope($this->google_api_scope);
    $this->google_client->setSubject($this->google_api_user);
    
    //$this->google_client->refreshToken($this->google_api_refresh_token);
    
    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    
    // 
    
    $upload_dir = wp_upload_dir();
    $tokenPath = $upload_dir['basedir'] . '/private/enercare-google-reviews/token.json';
    
    if (file_exists($tokenPath)) {
      $accessToken = json_decode(file_get_contents($tokenPath), true);
      $this->google_client->setAccessToken($accessToken);
    } else {
      $tokenPath = __DIR__ . '/token.json';
      if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $this->google_client->setAccessToken($accessToken);
      }
    }
    
    $this->google_client->authorize();
    
    // If there is no previous token or it's expired.
    if ($this->google_client->isAccessTokenExpired()) {
      // Refresh the token if possible, else fetch a new one.
      if ($this->google_client->getRefreshToken()) {
        $this->google_client->fetchAccessTokenWithRefreshToken($this->google_client->getRefreshToken());
      } else {
        error_log('Enercare Google Reviews ERROR: Access Token is expired and could not refresh token. Run refreshGMBAccessToken.php locally to regenerate and re-upload token.json');
        add_action('admin_notices', 'enercare_gmb_reviews_api_access_notice');
      }
      
      // if our private dir does not exist, create it
      if (!file_exists(dirname($tokenPath))) {
        mkdir(dirname($tokenPath), 0700, true);
      }
      file_put_contents($tokenPath, json_encode($this->google_client->getAccessToken()));
    }
    
  }

  /**
   * @return mixed
   */
  public function getAccounts() {
    $accounts = $this->gmb_service->accounts;
    $res = $accounts->listAccounts();
    return $res->getAccounts();
  }

  /**
   * @return mixed
   */
  public function getLocations() {
    $mybusinessService = new Google_Service_MyBusinessBusinessInformation($this->google_client);
    $locations = $mybusinessService->accounts_locations;
    $queryParams = [
      "pageSize" => 100,
      'readMask' => "name,storeCode,storefrontAddress,profile,metadata"
    ];

    $locationsList = $locations->listAccountsLocations($this->google_api_account, $queryParams);
    $locations = $locationsList->getLocations();
    
    return $locations;
    
    /* old deprecated code from GMB API v4
    $locations = $this->gmb_service->accounts_locations;
    $res = $locations->listAccountsLocations($this->google_api_account);
    return $res->getLocations();
    */
  }

  /**
   * @param $location_object_id
   *
   * @return mixed
   */
  public function getReviews($location_object_id, $pageSize = 50) {
    $reviews = $this->gmb_service->accounts_locations_reviews;
    $res = $reviews->listAccountsLocationsReviews($location_object_id, ['pageSize' => $pageSize]);
    return $res;
  }

  /**
   * @return array|bool
   */
  public function getReviewsBatchManual($pageSize = 50) {
    global $ecReviews;
    $locations = $ecReviews->getLocations();

    $reviews = [];

    foreach ($locations as $location) {
      $gmb_object_id = get_post_meta($location->ID, 'gmb_location_id', true);
      // for newer locations using the MyBusinessBusinessInformation API, we need to append the account ID for looking up reviews
      if (strpos($gmb_object_id, 'accounts/103584978847197657494/') === false) {
        $gmb_object_id = 'accounts/103584978847197657494/' . $gmb_object_id;
      }
      $reviews[$gmb_object_id]['review_response'] = $this->getReviews($gmb_object_id, $pageSize);
    }

    return $reviews;
  }

  /**
   * @return mixed
   */
  public function getReviewsBatch() {
    global $ecReviews;
    $batchLocations = new \Google_Service_MyBusiness_BatchGetReviewsRequest();
    
    $locations = $ecReviews->getLocations();
    $location_gmb_ids = [];
    
    /****** batchGetReviews is breaking when using more than 1 locationName in the array, which makes it useless. ******/
    foreach ($locations as $location) {
      $location_gmb_ids[] = get_post_meta($location->ID, 'gmb_location_id', true);
      //echo get_post_meta($location->ID, 'gmb_location_id', true) . '<br />';
    }
    $batchLocations->setLocationNames($location_gmb_ids);
    
    /*
      $batchLocations->setLocationNames([
      'accounts/103584978847197657494/locations/18442810825726843831',
      'accounts/103584978847197657494/locations/17391413516882815650',
      'accounts/103584978847197657494/locations/15536580574124552698',
      'accounts/103584978847197657494/locations/14605883734443205973',
      'accounts/103584978847197657494/locations/13976511597374970711',
      'accounts/103584978847197657494/locations/12701406066123182528',
      'accounts/103584978847197657494/locations/12433064867296865439',
      'accounts/103584978847197657494/locations/10734342559790089077',
      'accounts/103584978847197657494/locations/10603231421245783405',
      'accounts/103584978847197657494/locations/10092739299173897082',
      'accounts/103584978847197657494/locations/10036163871974680960',
      'accounts/103584978847197657494/locations/9477176476440020077',
      'accounts/103584978847197657494/locations/9353626881184379667',
      'accounts/103584978847197657494/locations/8950586863879767422',
      'accounts/103584978847197657494/locations/7244784258585707171',
      'accounts/103584978847197657494/locations/5774496563842088657',
      'accounts/103584978847197657494/locations/5757951116923020660',
      'accounts/103584978847197657494/locations/4546433103970822851',
      'accounts/103584978847197657494/locations/4295276311033838711',
      'accounts/103584978847197657494/locations/1183401312617167695',
      'accounts/103584978847197657494/locations/376979084968293248',
      'accounts/103584978847197657494/locations/17762338160833870568',
      'accounts/103584978847197657494/locations/9924612716891372548',
      'accounts/103584978847197657494/locations/39950434456035932',
    ]);*/
    
    /*$batchLocations->setLocationNames([
      'accounts/103584978847197657494/locations/5757951116923020660',
      //'accounts/103584978847197657494/locations/39950434456035932',
    ]);*/
    $batchLocations->setIgnoreRatingOnlyReviews(TRUE);
    $batchLocations->setPageSize(20);
    //$batchLocations->setPageToken('page');

    $locations = $this->gmb_service->accounts_locations;
    $res = $locations->batchGetReviews('accounts/103584978847197657494', $batchLocations);
    return $res->getLocationReviews();
  }

}
