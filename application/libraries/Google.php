<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Google {

  public function __construct() {
    // Load autoload Library file.
    require( dirname(__FILE__) . "/Google/autoload.php" );   
  }

  public function googlelog() {

    //Insert your cient ID and secret 
    //You can get it from : https://console.developers.google.com/
    $CI =& get_instance();
    $google_config = $CI->config->item('google');
    $client_id = $google_config['client_id'];
    $client_secret = $google_config['client_secret'];
    $redirect_uri = base_url() . $google_config['redirect_uri'];   // Return url for google login. It should be same with the link provided in google login app created on https://console.developers.google.com


    $client = new Google_Client();
    $client->setClientId($client_id);
    $client->setClientSecret($client_secret);
    $client->setRedirectUri($redirect_uri);
    $client->addScope("email");
    $client->addScope("profile");


    
    $service = new Google_Service_Oauth2($client);


    if (isset($_GET['code'])) {
      $client->authenticate($_GET['code']);
      $_SESSION['google_access_token'] = $client->getAccessToken();
      header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
      exit;
    }
    /************************************************
      If we have an access token, we can make
      requests, else we generate an authentication URL.
     ************************************************/
      if (isset($_SESSION['google_access_token']) && $_SESSION['google_access_token']) {
        $client->setAccessToken($_SESSION['google_access_token']);
        $googleuser = $service->userinfo->get(); //get user info 
        return $googleuser;   //  Return Google Login user infor
      } else {
        $googlelogin = $client->createAuthUrl();
        return $googlelogin;  // Return Google Login Url
      }
    }

  }

  /* End of file Google.php */
  /* Location:  ./application/libraries/Google.php */
