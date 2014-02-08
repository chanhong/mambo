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

$database->setQuery( "SELECT COUNT(*) FROM #__messages WHERE state='0' AND user_id_to='$my->id'" );
$unread = $database->loadResult();

if ($unread) {
	echo "<a href=\"index2.php?option=com_messages\" style=\"color: red; text-decoration: none;  font-weight: bold\">$unread <img src=\"images/mail.png\" align=\"middle\" border=\"0\" alt=\"".T_('Mail')."\" /></a>";
} else {
    echo "<a href=\"index2.php?option=com_messages\" style=\"color: black; text-decoration: none;\">$unread <img src=\"images/nomail.png\" align=\"middle\" border=\"0\" alt=\"".T_('Mail')."\" /></a>";
}
?>