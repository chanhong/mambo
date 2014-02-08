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

mosAdminMenus::menuItem( $type );

$scid 	= mosGetParam( $_POST, 'scid', '' );

switch ($task) {
	case 'content_typed':
		// this is the new item, ie, the same name as the menu `type`
		content_typed_menu::edit( 0, $menutype, $option );
		break;
		
	case 'edit':
		content_typed_menu::edit( $cid[0], $menutype, $option );
		break;
		
	case 'save':
	case 'apply':
		saveMenu( $option, $task );
		break;
		
	case 'redirect':
		content_typed_menu::redirect( $scid );
		break;		
}
?>
