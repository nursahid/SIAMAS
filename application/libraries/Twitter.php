<?php 
/*
 * 
 *  Plugin : One-click Social Media Login
 *  Created by : tecHindustan Solutions Pvt. Ltd.
 *  Version : 1.1
 *  File Name Name : Twitter Library
 *  Description : Used to interact with twitter login libraries. Get the Twitter login urls and Twitter User 
 *  information.
 * 
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Twitter 
{
    public function __construct()
    {    
      include_once( dirname(__FILE__) . "/twitter/twitteroauth.php" );   // Include twitteroauth Library file.
  }

    /*  
     * 
     *  Function : twitterlogin()
     *  Used to get Twitter Login url and user information after Login.
     * 
     */
    public function twitterlogin()
    {
        /*  Api Keys.   */
        $CI =& get_instance();
        $twitter_config = $CI->config->item('twitter');
        
        $CONSUMER_KEY = $twitter_config['CONSUMER_KEY'];
        $CONSUMER_SECRET =  $twitter_config['CONSUMER_SECRET'];
        $OAUTH_CALLBACK = base_url().'/'.$twitter_config['OAUTH_CALLBACK'];
        // ends here

        /*  If token is old, distroy session and redirect user to login page   */
        if(isset($_REQUEST['oauth_token']) && $_SESSION['token']  !== $_REQUEST['oauth_token']) 
        {
            session_destroy();
            redirect(base_url().'/'.$twitter_config['OAUTH_CALLBACK']);
        }
        //Successful response returns oauth_token, oauth_token_secret, user_id, and screen_name
        elseif(isset($_REQUEST['oauth_token']) && $_SESSION['token'] == $_REQUEST['oauth_token']) 
        {
            $connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $_SESSION['token'] , $_SESSION['token_secret']);  // Connection with twitterOAuth
            $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);   // Get access token
            if($connection->http_code == '200')
            {
                $_SESSION['status'] = 'verified';
                $_SESSION['request_vars'] = $access_token;

                $user_info = $connection->get('account/verify_credentials');    // Get User info
                //dump_exit($user_info);
                $data = array(
                    'loginId' => $user_info->id,
                    'loginType' => '3',
                    'name' => $user_info->name,
                    'firstName' => $user_info->name,
                    'lastName' => $user_info->name,
                    'email' => $user_info->id.'@twitter.com',
                    'profilePic' => $user_info->profile_image_url,
                    'location' => $user_info->location,
                    'created_on' => date('Y-m-d H:m:s')
                    );
                $_SESSION['user_info'] = $user_info;    // Set session of user information
                /*  Here will come your db functions to check existing users and insert users   */
                //Unset no longer needed request tokens
                unset($_SESSION['token']);
                unset($_SESSION['token_secret']);
                // check user
                
                $CI =& get_instance();
                $user = $CI->db->get_where('users', array('email' => $data['email']))->row();
                // signup
                if(is_object($user) == false){
                    $this->ion_auth_signup($data);
                }

                // login
                if(is_object($user) == true){
                    $this->ion_auth_signin($data);
                }
                redirect('login'); // Redirect to twitter page where we are showing user name and image.
            }
            else
            {
                die("error, try again later!");
            }
        }
        else    // If denied status
        {
            if(isset($_GET["denied"]))
            {
                redirect(base_url().$twitter_config['OAUTH_CALLBACK']);
                die();
            }

            //Fresh authentication
            $connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET);
            $request_token = $connection->getRequestToken($OAUTH_CALLBACK);

            //Received token info from twitter
            $_SESSION['token']          = $request_token['oauth_token'];
            $_SESSION['token_secret']   = $request_token['oauth_token_secret'];

            //Any value other than 200 is failure, so continue only if http code is 200
            if($connection->http_code == '200')
            {
                //redirect user to twitter
                $twitter_url = $connection->getAuthorizeURL($request_token['oauth_token']);
                return  $twitterlogin_url = $twitter_url;   // Twitter Login Url
            }
            else
            {
                return $twitterlogin_url ='';   
                die("error connecting to twitter! try again later!");
            }
        }
    }

     // do signup in ion_auth then do login session
    private function ion_auth_signup($data)
    {
        $username = $data['firstName'];
        $password = sha1(sha1(sha1(md5($data['email'])))); 
        $email = $data['email'];
        $additional_data = [
        'full_name' => $data['firstName'].' '.$data['lastName'],
        'additional' => '"on"',
        'active' => 1,
        'photo' => $data['profilePic']
        ];
        $CI =& get_instance();
        $CI->ion_auth->register($username, $password, $email, $additional_data);
        $user = $CI->db->get_where('users',['email'=>$email])->row();
        $CI->ion_auth->activate($user->id);
        $CI->ion_auth->login($email, $password, 0);
    }

    private function ion_auth_signin($data)
    {
        $identity = $data['email'];
        $password = sha1(sha1(sha1(md5($data['email'])))); 
        $remember = 0;
        // force the password 
        $CI =& get_instance();
        $userdata = new stdClass();
        $userdata->password = $CI->ion_auth->hash_password($password);
        $CI->db->where('email', $identity);
        $CI->db->update('users',$userdata);
        $CI->ion_auth->login($identity, $password, $remember);
    }
}
/* End of file twitter.php */
/* Location:  ./application/libraries/twitter.php */
?>