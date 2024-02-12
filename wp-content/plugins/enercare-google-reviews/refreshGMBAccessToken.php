<?php
/**

DO NOT RUN THIS FILE ANYWHERE BY LOCALHOST

RUN THIS WHEN WE NEED TO RE-AUTHORIZE OUR TOKENS.
RUN THIS ON COMMAND LINE FROM LOCALHOST ENVIRONMENT.
USE ENERCAREDIGITAL@GMAIL.COM AS THE USER TO LOGIN WITH ON THE OAUTH FLOW URL THIS SCRIPT PROVIDES.
IT WILL REDIRECT YOU BACK TO A LOCALHOST URL WITH A "CODE=" URL PARAM. COPY/PASTE THAT VALUE INTO THE COMMAND PROMPT.
IT SHOULD GENERATE TOKEN.JSON FOR USE IN OUR PLUGIN.
TAKE TOKEN.JSON AND PUT IT IN /wp-content/uploads/private/enercare-google-reviews/
UPLOAD IT AND SHOULD BE GOOD.

*/


require_once 'google-api-php-client/vendor/autoload.php';

if (php_sapi_name() != 'cli') {
    throw new Exception('This application must be run on the command line.');
}

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient()
{
    $client = new Google_Client();
    
    // THIS FIXED A PROBLEM IN MY (ANDY) LOCALHOST. DO NOT RUN THIS LIVE UNLESS YOU HAVE TO. NOT SECURE.
    $guzzle = new GuzzleHttp\Client([
        'verify' => false
    ]);
    $client->setHttpClient($guzzle);
    
    $client->setApplicationName('enercare');
    $client->setScopes('https://www.googleapis.com/auth/business.manage');
    $client->setAuthConfig('gmb_api_credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = 'token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }
        // Save the token to a file.
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }
    return $client;
}


// Get the API client and construct the service object.
$client = getClient();