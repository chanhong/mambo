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
//require_once( $mainframe->getPath( 'toolbar_default' ) );

$act = mosGetParam( $_REQUEST, 'act', '' );
if ($act) {
	$task = $act;
}

switch ($task) {
	case "new":
	case "edit":
		TOOLBAR_menumanager::_NEWMENU($task);
		break;

	case "copyconfirm":
		TOOLBAR_menumanager::_COPYMENU();
		break;

	case "deleteconfirm":
		TOOLBAR_menumanager::_DELETE();
		break;

	default:
		TOOLBAR_menumanager::_DEFAULT();
		break;
}
?>