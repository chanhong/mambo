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

switch ($task) {
	case 'content_blog_category':
		// this is the new item, ie, the same name as the menu `type`
		content_blog_category::edit( 0, $menutype, $option );
		break;

	case 'edit':
		content_blog_category::edit( $cid[0], $menutype, $option );
		break;

	case 'save':
	case 'apply':
		content_blog_category::saveMenu( $option, $task );
		break;
}
?>
