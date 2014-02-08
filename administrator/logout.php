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

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

$currentDate = date("Y-m-d\TH:i:s");

if (isset($_SESSION['session_user_id']) && $_SESSION['session_user_id']!="") {
	$database->setQuery( "UPDATE #__users SET lastvisitDate='$currentDate' WHERE id='" . $_SESSION['session_user_id'] . "'");

	if (!$database->query()) {
        echo $database->stderr();
	}
}

if (isset($_SESSION['session_id']) && $_SESSION['session_id']!="") {
	$database->setQuery( "DELETE FROM #__session WHERE session_id='" . $_SESSION['session_id'] . "'");

	if (!$database->query()) {
		echo $database->stderr();
	}
}

$name = "";
$fullname = "";
$id = "";
$session_id = "";

session_unregister( "session_id" );
session_unregister( "session_user_id" );
session_unregister( "session_username" );
session_unregister( "session_usertype" );
session_unregister( "session_logintime" );

if (session_is_registered( "session_id" )) {
	session_destroy();
}
if (session_is_registered( "session_user_id" )) {
	session_destroy();
}
if (session_is_registered( "session_username" )) {
	session_destroy();
}
if (session_is_registered( "session_usertype" )) {
	session_destroy();
}
if (session_is_registered( "session_logintime" )) {
	session_destroy();
}
$configuration =& mamboCore::getMamboCore();
$configuration->redirect( "../index.php" );
?>
