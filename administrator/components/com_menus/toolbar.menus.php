<?php
/**
* @package Mambo
* @subpackage Menus
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
require_once( $mainframe->getPath( 'toolbar_default' ) );

switch ($task) {
	case 'new':
		TOOLBAR_menus::_NEW();
		break;

	case 'movemenu':
		TOOLBAR_menus::_MOVEMENU();
		break;

	case 'copymenu':
		TOOLBAR_menus::_COPYMENU();
		break;

	case 'edit':
		$cid 	= mosGetParam( $_POST, 'cid', array(0) );
		if (!is_array( $cid )) {
			$cid = array(0);
		}
		$path 	= $mosConfig_absolute_path .'/administrator/components/com_menus/';	

		if ( $cid[0] ) {
			$query = "SELECT type FROM #__menu WHERE id = $cid[0]";
			$database->setQuery( $query );
			$type = $database->loadResult();
			$item_path  = $path . $type .'/'. $type .'.menubar.php';
			
			if ( $type ) {
				if ( file_exists( $item_path  ) ) {
					require_once( $item_path  );
				} else {
					TOOLBAR_menus::_EDIT($type);
				}
			} else {
				echo $database->stderr();
			}
		} else {
			$type 		= mosGetParam( $_REQUEST, 'type', null );
			$item_path  = $path . $type .'/'. $type .'.menubar.php';
			
			if ( $type ) {
				if ( file_exists( $item_path ) ) {
					require_once( $item_path  );
				} else {
					TOOLBAR_menus::_EDIT($type);
				}
			} else {
				TOOLBAR_menus::_EDIT($type);
			}
		}
		break;

	default:
		$type 		= trim( mosGetParam( $_REQUEST, 'type', null ) );
		$item_path  = $path . $type .'/'. $type .'.menubar.php';
		
		if ( $type ) {
			if ( file_exists( $item_path ) ) {
				require_once( $item_path );
			} else {
				TOOLBAR_menus::_DEFAULT();
			}
		} else {
			TOOLBAR_menus::_DEFAULT();
		}
		break;
}
?>
