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

$session_id = mosGetParam( $_SESSION, 'session_id', '' );

// Get no. of users online not including current session
$query = "SELECT count(session_id) FROM #__session"
."\n WHERE session_id <> '$session_id'";

$database->setQuery($query);
$online_num = intval( $database->loadResult() );

echo $online_num . " <img src=\"images/users.png\" align=\"middle\" alt=\"".T_('Users Online') ."\" />";
?>