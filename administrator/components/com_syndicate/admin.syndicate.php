<?php
/**
* @package Mambo
* @subpackage Syndicate
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
if (!($acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'all' ) | $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_contact' ))) {
	mosRedirect( 'index2.php', T_('You are not authorized to view this resource.') );
}

require_once( $mainframe->getPath( 'admin_html' ) );


switch ($task) {

	case 'save':
		saveSyndicate( $option );
		break;

  case 'cancel':
		cancelSyndicate( );
		break;

	default:
		showSyndicate( $option );
		break;
}

/**
* List the records
* @param string The current GET/POST option
*/
function showSyndicate( $option ) {
	global $database, $mainframe, $mosConfig_list_limit;
	
	$query = "SELECT a.id"
	. "\n FROM #__components AS a"
	. "\n WHERE a.name = 'Syndicate'"
	;
	$database->setQuery( $query );
	$id = $database->loadResult();

	// load the row from the db table
	$row = new mosComponent( $database );
	$row->load( $id );

	// get params definitions
	$params =& new mosAdminParameters( $row->params, $mainframe->getPath( 'com_xml', $row->option ), 'component' );

	HTML_syndicate::settings( $option, $params, $id );
}

/**
* Saves the record from an edit form submit
* @param string The current GET/POST option
*/
function saveSyndicate( $option ) {
	global $database;

	$params = mosGetParam( $_POST, 'params', '' );
	if (is_array( $params )) {
	    $txt = array();
	    foreach ($params as $k=>$v) {
	        $txt[] = "$k=$v";
		}
		$_POST['params'] = mosParameters::textareaHandling( $txt );
	}

	$id = mosGetParam( $_POST, 'id', '17' );
	$row = new mosComponent( $database );
	$row->load( $id );

	if (!$row->bind( $_POST )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	if (!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	$msg = T_('Settings successfully Saved');
	mosRedirect( 'index2.php?option='. $option, $msg );
}

/** 
* Cancels editing and checks in the record
*/
function cancelSyndicate(){
	mosRedirect( 'index2.php' );
}
?>
