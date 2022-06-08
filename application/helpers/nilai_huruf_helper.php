<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('nilai_huruf'))
{      
    function nilai_huruf($angka) {
        $angka = (float)$angka;

		if($angka >= 90) {
			return 'A';
		} elseif($angka >= 80) {
			return 'B';
		} elseif($angka >= 65) {
			return 'C';
		} elseif($angka >= 60) {
			return 'D';
		} else {
			return 'E';
		}		
    
    }        
} //eakhir