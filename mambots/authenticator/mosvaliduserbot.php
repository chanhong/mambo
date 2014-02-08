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

class mosValidUserBot {
	
	function register () {
		return 'requiredLogin';
	}

	function perform ($loginobject) {
		$username = $loginobject->getUser();
		$passwd = md5($loginobject->getPassword());
		$remember = $loginobject->getRemember();
		$authenticator =& mamboAuthenticator::getInstance();
		$checkuser = $authenticator->authenticateUser($message, $username, $passwd, $remember);
		return $message;
	}
}

?>