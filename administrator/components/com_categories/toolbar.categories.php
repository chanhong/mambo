<?php
/**
* @package Mambo
* @subpackage Categories
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

switch ($task){
	case 'new':
	case 'edit':
	case 'editA':
		TOOLBAR_categories::_EDIT();
		break;

	case 'moveselect':
		TOOLBAR_categories::_MOVE();
		break;

	case 'copyselect':
		TOOLBAR_categories::_COPY();
		break;

	default:
		TOOLBAR_categories::_DEFAULT();
		break;
}
?>