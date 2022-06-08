<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

//require_once('PHPExcel/IOFactory.php');
define('PHPEXCEL_ROOT', dirname(__FILE__) . '/');
require(PHPEXCEL_ROOT . 'PHPExcel/IOFactory.php');
	
class IOFactory extends PHPExcel_IOFactory {
	
	public function __construct()
	{
		//parent::__construct();
	}
}

?>