<?php

/*
 * 
 *  Plugin : One-click Social Media Login
 *  Created by : tecHindustan Solutions Pvt. Ltd.
 *  Version : 1.1
 *  File Name Name : LinkedIn Library
 *  Description : Used to interact with LinkedIn login libraries. Get the LinkedIn login urls and LinkedIn User 
 *  information. 
 * 
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Linkedinlib {

    public function linkedinlogin($data = NULL) {

        $CI =& get_instance();
        
        $API_CONFIG = $CI->config->item('linkedin');

        try {
            // include the LinkedIn class
            require_once( dirname(__FILE__) . '/linkedin/linkedin_3.2.0.class.php');

            // start the session
            if (!isset($_SESSION)) {
                throw new LinkedInException('This script requires session support, which appears to be disabled according to session_start().');
            }

            // display constants API config array

            define('PORT_HTTP', 80);      // Define HTTP port default is 80
            define('PORT_HTTP_SSL', 443); // Define SSl port default is 443
            // set index

            $data[LINKEDIN::_GET_TYPE] = (isset($data[LINKEDIN::_GET_TYPE])) ? $data[LINKEDIN::_GET_TYPE] : ''; // Check if get initial response
            switch ($data[LINKEDIN::_GET_TYPE]) {
                case 'initiate':

                    /*
                     * Handle user initiated LinkedIn connection, create the LinkedIn object.
                     */

                    // check for the correct http protocol (i.e. is this script being served via http or https)
                    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
                        $protocol = 'https';
                    } else {
                        $protocol = 'http';
                    }
//echo "<pre>"; print_r($_SERVER); echo "</pre>"; die;
                    // set the callback url
                    $API_CONFIG['callbackUrl'] = $protocol . '://' . $_SERVER['SERVER_NAME'] . ((($_SERVER['SERVER_PORT'] != PORT_HTTP) || ($_SERVER['SERVER_PORT'] != PORT_HTTP_SSL)) ? ':' . $_SERVER['SERVER_PORT'] : '') . $_SERVER['PHP_SELF'] . '?' . LINKEDIN::_GET_TYPE . '=initiate&' . LINKEDIN::_GET_RESPONSE . '=1';
                    $OBJ_linkedin = new LinkedIn($API_CONFIG);

                    // check for response from LinkedIn
                    $_GET[LINKEDIN::_GET_RESPONSE] = (isset($_GET[LINKEDIN::_GET_RESPONSE])) ? $_GET[LINKEDIN::_GET_RESPONSE] : '';
                    if (!$_GET[LINKEDIN::_GET_RESPONSE]) {
                        // LinkedIn hasn't sent us a response, the user is initiating the connection
                        // send a request for a LinkedIn access token
                        $response = $OBJ_linkedin->retrieveTokenRequest();
                        if ($response['success'] === TRUE) {
                            // store the request token
                            $_SESSION['oauth']['linkedin']['request'] = $response['linkedin'];

                            // redirect the user to the LinkedIn authentication/authorisation page to initiate validation.
                            header('Location: ' . LINKEDIN::_URL_AUTH . $response['linkedin']['oauth_token']);
                        } else {

                            redirect(base_url().$API_CONFIG['callbackUrl']);
                            // bad token request
                            //echo "Request token retrieval failed:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response, TRUE) . "</pre><br /><br />LINKEDIN OBJ:<br /><br /><pre>" . print_r($OBJ_linkedin, TRUE) . "</pre>";
                        }
                    } else {
                        // LinkedIn has sent a response, user has granted permission, take the temp access token, the user's secret and the verifier to request the user's real secret key
                        $response = $OBJ_linkedin->retrieveTokenAccess($_SESSION['oauth']['linkedin']['request']['oauth_token'], $_SESSION['oauth']['linkedin']['request']['oauth_token_secret'], $_GET['oauth_verifier']);
                        if ($response['success'] === TRUE) {
                            // the request went through without an error, gather user's 'access' tokens
                            $_SESSION['oauth']['linkedin']['access'] = $response['linkedin'];
                            // set the user as authorized for future quick reference
                            $_SESSION['oauth']['linkedin']['authorized'] = TRUE;
                            $_SESSION['oauth']['linkedin']['authorized'] = (isset($_SESSION['oauth']['linkedin']['authorized'])) ? $_SESSION['oauth']['linkedin']['authorized'] : FALSE;
                            if ($_SESSION['oauth']['linkedin']['authorized'] === TRUE) {
                                $OBJ_linkedin = new LinkedIn($API_CONFIG);
                                $OBJ_linkedin->setTokenAccess($_SESSION['oauth']['linkedin']['access']);
                                $OBJ_linkedin->setResponseFormat(LINKEDIN::_RESPONSE_XML);
                            }
                            $response = $OBJ_linkedin->profile('~:(id,first-name,last-name,picture-url,public-profile-url,email-address,summary,location:(name),date-of-birth)');   // Get User Info from LinkedIn
                            return $result = new SimpleXMLElement($response['linkedin']);   // Return Finial result
                        } 
                        else {

                            redirect(base_url().$API_CONFIG['callbackUrl']);
                            
                            // bad token access
                            //echo "Access token retrieval failed:<br /><br />RESPONSE:<br /><br /><pre>" . print_r($response, TRUE) . "</pre><br /><br />LINKEDIN OBJ:<br /><br /><pre>" . print_r($OBJ_linkedin, TRUE) . "</pre>";
                        }
                    }
                    break;

                    default:
                    // nothing being passed back, display demo page
                    // check PHP version
                    if (version_compare(PHP_VERSION, '5.0.0', '<')) {
                        throw new LinkedInException('You must be running version 5.x or greater of PHP to use this library.');
                    }

                    // check for cURL
                    if (extension_loaded('curl')) {
                        $curl_version = curl_version();
                        $curl_version = $curl_version['version'];
                    } else {
                        throw new LinkedInException('You must load the cURL extension to use this library.');
                    }
                    break;
                }
            } catch (LinkedInException $e) {
            // exception raised by library call
                echo $e->getMessage();
            }
        }

    }

    /* End of file linkedinlib.php */
    /* Location: ./application/libraries/linkedinlib.php */
    ?>

