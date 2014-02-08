<?php
/**
* @package Mambo
* @subpackage Messages
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

switch ( $task ) {

	case "view":
		TOOLBAR_messages::_VIEW();
		break;

	case "new":
	case "edit":
	case "reply":
		TOOLBAR_messages::_EDIT();
		break;

	case "config":
		TOOLBAR_messages::_CONFIG();
		break;

	default:
		TOOLBAR_messages::_DEFAULT();
		break;
}
?>