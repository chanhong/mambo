<?php
/**
* @package Mambo
* @subpackage Modules
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

	case 'editA':
	case 'edit':
		$cid = mosGetParam( $_POST, 'cid', 0 );
		if ( !is_array( $cid ) ){
			$mid = mosGetParam( $_POST, 'id', 0 );;
		} else {
			$mid = $cid[0];
		}
		
		$published = 0;
		if ( $mid ) {
			$query = "SELECT published FROM #__modules WHERE id='$mid'";
			$database->setQuery( $query );
			$published = $database->loadResult();
		}
		$cur_template = $mainframe->getTemplate();
		TOOLBAR_modules::_EDIT( $cur_template, $published );
		break;

	case 'new':
		TOOLBAR_modules::_NEW();
		break;

	default:
		TOOLBAR_modules::_DEFAULT();
		break;
}
?>