<?php
/**
* @package Mambo
* @subpackage Polls
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

require_once( $mainframe->getPath( 'toolbar_html' ) );

switch ($task) {
	case 'new':
		TOOLBAR_poll::_NEW();
		break;

	case 'edit':
		$cid = mosGetParam( $_REQUEST, 'cid', array(0) );
		if (!is_array( $cid )) {
			$cid = array(0);
		}

		$database->setQuery( "SELECT published FROM #__polls WHERE id='$cid[0]'" );
		$published = $database->loadResult();

		$cur_template = $mainframe->getTemplate();
		
		TOOLBAR_poll::_EDIT( $cid[0], $cur_template );
		break;

	case 'editA':
		$id = mosGetParam( $_REQUEST, 'id', 0 );
		
		$database->setQuery( "SELECT published FROM #__polls WHERE id='$id'" );
		$published = $database->loadResult();

		$cur_template = $mainframe->getTemplate();
		
		TOOLBAR_poll::_EDIT( $id, $cur_template );
		break;

	default:
		TOOLBAR_poll::_DEFAULT();
		break;
}
?>