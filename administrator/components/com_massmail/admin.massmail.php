<?php
/**
* @package Mambo
* @subpackage Massmail
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

// ensure user has access to this function
if (!$acl->acl_check( 'administration', 'manage', 'users', $my->usertype, 'components', 'com_massmail' )) {
	mosRedirect( 'index2.php', T_('You are not authorized to view this resource.') );
}

require_once( $mainframe->getPath( 'admin_html' ) );

switch ($task) {
	case 'send':
		sendMail();
		break;

	case 'cancel':
		mosRedirect( 'index2.php' );
		break;

	default:
		messageForm( $option );
		break;
}

function messageForm( $option ) {
	global $acl;

	$gtree = array(
	mosHTML::makeOption( 0, T_('- All User Groups -') )
	);

	// get list of groups
	$lists = array();
	$gtree = array_merge( $gtree, $acl->get_group_children_tree( null, 'USERS', false ) );
	$lists['gid'] = mosHTML::selectList( $gtree, 'mm_group', 'size="10"', 'value', 'text', 0 );

	HTML_massmail::messageForm( $lists, $option );
}

function sendMail() {
	global $database, $my, $acl;
	global $mosConfig_sitename;
	global $mosConfig_mailfrom, $mosConfig_fromname;

	$mode				= mosGetParam( $_POST, 'mm_mode', 0 );
	$subject			= mosGetParam( $_POST, 'mm_subject', '' );
	$gou				= mosGetParam( $_POST, 'mm_group', NULL );
	$recurse			= mosGetParam( $_POST, 'mm_recurse', 'NO_RECURSE' );
	$inc_blocked		= mosGetParam( $_POST, 'inc_blocked', 0 );
	// pulls message inoformation either in text or html format
	if ( $mode ) {
		$message_body	= $_POST['mm_message'];
	} else {
		// automatically removes html formatting
		$message_body	= mosGetParam( $_POST, 'mm_message', '' );
	}
	$message_body 		= stripslashes( $message_body );
	
	if (!$message_body || !$subject || $gou === null) {
	    $msg = T_('Please fill in the form correctly');
		mosRedirect( 'index2.php?option=com_massmail&mosmsg='.$msg );
	}

	// get users in the group out of the acl
	$to = $acl->get_group_objects( $gou, 'ARO', $recurse );

	$rows = array();
	if ( count( $to['users'] ) || $gou === '0' ) {
		// Get sending email address
		$query = "SELECT email FROM #__users WHERE id='$my->id'";
		$database->setQuery( $query );
		$my->email = $database->loadResult();

		// Get all users email and group except for senders
		$query = "SELECT email FROM #__users"
		. "\n WHERE id != '$my->id'"
		. ( $inc_blocked !== '0' ? " AND block = 0 ": '' )
		. ( $gou !== '0' ? " AND id IN (" . implode( ',', $to['users'] ) . ")" : '' )
		;
		$database->setQuery( $query );
		$rows = $database->loadObjectList();

		// Build e-mail message format
		$message_header 	= sprintf( T_("This is an email from '%s'

Message:
"), $mosConfig_sitename );
		$message 			= $message_header . $message_body;
		$subject 			= $mosConfig_sitename. ' / '. stripslashes( $subject);

		//Send email
		foreach ($rows as $row) {
			mosMail( $mosConfig_mailfrom, $mosConfig_fromname, $row->email, $subject, $message, $mode );
		}
	}
	
	$msg = sprintf(Tn_('E-mail sent to %d user.', 'E-mail sent to %d users.', count($rows)), count($rows));
	mosRedirect( 'index2.php?option=com_massmail', $msg );
}
?>
