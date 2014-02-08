<?php
/**
* @package Mambo
* @subpackage Banners
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
	case 'newclient':
	case 'editclient':
	case 'editclientA':
		TOOLBAR_bannerClient::_EDIT();
		break;

	case 'listclients':
		TOOLBAR_bannerClient::_DEFAULT();
		break;

	case 'new':
	case 'edit':
	case 'editA':
		TOOLBAR_banners::_EDIT();
		break;

	default:
		TOOLBAR_banners::_DEFAULT();
		break;
}
?>