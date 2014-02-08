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

$showmode = $params->get( 'showmode' );
$moduleclass_sfx = $params->get( 'moduleclass_sfx' );

$content="";

if ($showmode==0 || $showmode==2) {
	$query1 = "SELECT count(session_id) as guest_online FROM #__session WHERE guest=1 AND (usertype is NULL OR usertype='')";
	$database->setQuery($query1);
	$guest_array = $database->loadResult();

	$query2 = "SELECT DISTINCT count(username) as user_online FROM #__session WHERE guest=0 AND usertype <> 'administrator' AND usertype <> 'superadministrator'";
	$database->setQuery($query2);
	$user_array = $database->loadResult();

	if ($guest_array<>0 && $user_array==0) {
		$content .= sprintf(Tn_( 'We have %d guest online', 'We have %d guests online', $guest_array), $guest_array);
	}

	if ($guest_array==0 && $user_array<>0) {
		$content .= sprintf(Tn_('We have %d member online', 'We have %d members online', $user_array), $user_array);
	}

	if ($guest_array<>0 && $user_array<>0) {
	    $content .= sprintf(Tn_('We have %d guest online and ', 'We have %d guests online and ', $guest_array), $guest_array);
        $content .= sprintf(Tn_(' %d member online', ' %d members online', $user_array), $user_array);
	}
}

if ($showmode==1 || $showmode==2) {
	$query = "SELECT DISTINCT a.username"
	."\n FROM #__session AS a"
	."\n WHERE (a.guest=0)";
	$database->setQuery($query);
	$rows = $database->loadObjectList();
	if (is_array($rows)) {
		foreach($rows as $row) {
			$content .= "<ul>\n";
			$content .= "<li><strong>" . $row->username . "</strong></li>\n";
			$content .= "</ul>\n";
		}
	}
	if ($content == "") {
		echo T_( 'No Users Online') ."\n";
	}
}
?>