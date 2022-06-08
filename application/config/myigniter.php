<?php

defined('BASEPATH') or exit('No direct script access allowed');
/*
 * Basic config
 *
 * Author : Nur Sahid
 */

$config['version'] = '2.0.2'; // Site version

// Site
$config['site'] = [
    'title' => 'Sistem Administrasi Manajemen Sekolah', // Default Title entire page
    'favicon' => 'assets/img/favicon.png', // Default Favicon
    'logo' => 'assets/img/logo/logo.png' // Default Logo
];

// Template
$config['template'] = [
    'front_template' => 'template/front_template', // Default front template
    'backend_template' => 'template/admin_template', // Default backend template
    'auth_template' => 'template/auth_template' // Default auth template
];

// Auth view
$config['view'] = [
    'login' => 'auth/login', // Default login view template
    'register' => 'auth/register', // Defaul register view template
    'forgot_password' => 'auth/forgot_password', // Default forgot password view template
    'reset_password' => 'auth/reset_password' // Default reset password view template
];

// Route
$config['route'] = [
    'default_page' => 'home', // Default first page route
    'login_success' => 'admin/dashboard' // Default redirect after success logedin
];

// Email Configuration
$config['email_config'] = [
    'protocol' => 'smtp',
    'smtp_host' => 'mail.kotaxdev.co',
    'smtp_user' => 'support@kotaxdev.co',
    'smtp_pass' => '',
    'smtp_port' => 587,
    'mailtype' => 'html',
    'charset' => 'iso-8859-1'
];

// social login
$config['facebook'] = [
    'appId' => '380835822278539',
    'secret' => '5b72d6329b48b310e29ca181d5af67f2',
    'access_token' => '380835822278539|4fFg48cQCDHotW74VgFvBzmTyyw',
    'fields' => 'id,name,email,first_name,last_name,birthday,about,gender,location,picture.type(large)'
];

$config['google'] = [
    'client_id' => '69371986177-fp39509a46252f15q6b7fm8i7o9uqog0.apps.googleusercontent.com',
    'client_secret' => 'UPHTYfG3KtCq2dF8VnZoXobT',
    'redirect_uri' => 'login',
];

$config['twitter'] = [
    'CONSUMER_KEY' => 'NeYOMXtjpxtuT7ZArTiplo5IL',
    'CONSUMER_SECRET' => '9Wba4B1Y5qES5jZLbRCgletrFi2u7HJ89HilIbXLIwY0yp70BO',
    'OAUTH_CALLBACK' => 'login',
];

$config['linkedin'] = [
    'appKey' => '86a9fuizxy35f1',
    'appSecret' => '4EcYeSTNzL5yz6zh',
    'callbackUrl' => 'login'
];

// default time zone
$config['timezone'] = 'Asia/Makassar';

// skins setting 
# blue-light
# yellow
# yellow-light
# green 
# green-light   
# purple
# purple-light  
# red   
# red-light
# black
# black-light
$config['skin'] = 'green'; // selected skin

$config['google_recaptcha'] = [
    'site_key' => '6LeTSh8UAAAAAOXygATUlkTAFwdJuxecYZ8WzrwY',
    'secret_ket' => '6LeTSh8UAAAAAJTAP4jBsnHevXnPr13ThaZNmiur'
];

// optional features feel free to enable or disable this features in your web app
$config['features'] = [
    'google_recaptcha' => false,
    'login_via_facebook' => true,
    'login_via_google' => true,
    'login_via_twitter' => true,
    'login_via_linkedin' => true,
    'disable_all_social_logins' => true
];

// Debugbar
$config['debugbar'] = false; // True show debugbar
