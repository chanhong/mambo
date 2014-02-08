<?php
/**
* @package Mambo
* @author Mambo Foundation Inc see README.php
* @author Mambo Foundation Inc see README.php
* @copyright Mambo Foundation Inc.
* See COPYRIGHT.php for copyright notices and details.
* @license GNU/GPL Version 2, see LICENSE.php
* Mambo is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; version 2 of the License.
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

if (!function_exists( 'array_change_key_case' )) {
	if (!defined('CASE_LOWER')) {
	    define('CASE_LOWER', 0);
	}
	if (!defined('CASE_UPPER')) {
	    define('CASE_UPPER', 1);
	}
	function array_change_key_case( $input, $case=CASE_LOWER ) {
	    if (!is_array( $input )) {
	        return false;
		}
		$array = array();
		foreach ($input as $k=>$v) {
			if ($case) {
			    $array[strtoupper( $k )] = $v;
			} else {
			    $array[strtolower( $k )] = $v;
			}
		}
		return $array;
	}
}
?>