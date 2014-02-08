<?php
/**
* @package Mambo
* @author Mambo Foundation Inc see README.php
* @copyright Mambo Foundation Inc.
* See COPYRIGHT.php for copyright notices and details.
* @license GNU/GPL Version 2, see LICENSE.php
* Mambo is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; version 2 of the License.
*/

function externalCallCheck($path,  $secret) {
	if (isset($_COOKIE['mostlyce']['startup_key']) && isset($_COOKIE['mostlyce']['usertype'])) {
		require_once($path.'/includes/phpInputFilter/class.inputfilter.php');
		$iFilter = new InputFilter( null, null, 1, 1 );
		$startupKey = trim($iFilter->process($_COOKIE['mostlyce']['startup_key'])); //The MOStlyCE rebuild key should match this
		$usertype = strtolower(str_replace(' ', '', trim($iFilter->process($_COOKIE['mostlyce']['usertype']))));
	} else {
		return false;
	}	

	$env = md5($_SERVER['HTTP_USER_AGENT']);
	$rebuildKey=md5($secret.$env.$_SERVER['REMOTE_ADDR']);
	if ($rebuildKey!==$startupKey) {
		return false;
	}

	//Valid user types
	$vUsers=array('author', 'editor', 'publisher', 'manager', 'administrator', 'superadministrator');
	if (!in_array($usertype, $vUsers)) {
		return false;
	}
	
	return true;
}

?>