<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller']   = 'home';
$route['404_override']         = 'Error404';
$route['translate_uri_dashes'] = FALSE;

/*
| SiAMAS Route
*/
$route['page/(:any)']            = 'page/index/$1';
$route['table/(:any)']           = 'myigniter/table/$1';
$route['login']                  = 'admin/login';
$route['logout']                 = 'admin/logout';
$route['activate/(:num)/(:any)'] = 'user/activate/$1/$2';
$route['register']               = 'user/register';
$route['forgot-password']        = 'user/forgot_password';
$route['reset-password/(:any)']  = 'user/reset_password/$1';

$route['daftar-alumni'] 		 = 'daftar_alumni';
$route['daftar-alumni/post']['post'] = "daftar_alumni/validateForm";
$route['sambutan-kepala-sekolah']	 = 'page/sambutan-kepala-sekolah';
$route['ajax-kontak'] 	 		     = "kontak";
$route['ajax-kontak/post']['post']   = "kontak/validateForm";
$route['botdetect/captcha-handler']  = 'botdetect/captcha_handler/index';

$route['pencarian'] 		= 'berita/search';
$route['kategori/(:any)'] 	= 'berita/kategori/$1';
$route['arsip/([0-9]{4})/([0-9]{2})'] = 'berita/arsip/$1/$2';
$route['tag/(:any)'] 		= 'berita/tags/$1';