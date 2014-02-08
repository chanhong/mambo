<?php
/**
* @package Mambo
* @subpackage Templates
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

$client = mosGetParam( $_REQUEST, 'client', '' );

switch ($task) {

	case "view":
		TOOLBAR_templates::_VIEW();
		break;

	case "edit_source":
		TOOLBAR_templates::_EDIT_SOURCE();
		break;

	case "edit_css":
		TOOLBAR_templates::_EDIT_CSS();
		break;

	case "assign":
		TOOLBAR_templates::_ASSIGN();
		break;

	case "positions":
		TOOLBAR_templates::_POSITIONS();
		break;

	default:
		TOOLBAR_templates::_DEFAULT($client);
		break;

}
?>
