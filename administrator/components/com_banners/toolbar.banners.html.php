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
class TOOLBAR_banners {
	/**
	* Draws the menu for to Edit a banner
	*/
	function _EDIT() {
		global $id;

		mosMenuBar::startTable();
		mosMenuBar::save();
		mosMenuBar::spacer();
		if ( $id ) {
			// for existing content items the button is renamed `close`
			mosMenuBar::cancel( 'cancel', T_('Close') );
		} else {
			mosMenuBar::cancel();
		}
		mosMenuBar::spacer();
		if ($GLOBALS['task'] == 'edit' || $GLOBALS['task'] == 'editA') {
		    mosMenuBar::help( 'manager.edit' );
		} else {
		    mosMenuBar::help( 'manager.new' );
		}
		mosMenuBar::endTable();
	}
	function _DEFAULT() {
		mosMenuBar::startTable();
		mosMenuBar::publishList();
		mosMenuBar::spacer();
		mosMenuBar::unpublishList();
		mosMenuBar::spacer();
		mosMenuBar::media_manager( 'banners' );
		mosMenuBar::spacer();
		mosMenuBar::addNewX();
		mosMenuBar::spacer();
		mosMenuBar::editListX();
		mosMenuBar::spacer();
		mosMenuBar::deleteList();
		mosMenuBar::spacer();
		mosMenuBar::help( 'manager' );
		mosMenuBar::endTable();
	}
}

/**
* @package Mambo
*/
class TOOLBAR_bannerClient {
	/**
	* Draws the menu for to Edit a client
	*/
	function _EDIT() {
		global $id;

		mosMenuBar::startTable();
		mosMenuBar::save( 'saveclient' );
		mosMenuBar::spacer();
		if ( $id ) {
			// for existing content items the button is renamed `close`
			mosMenuBar::cancel( 'cancelclient', T_('Close') );
		} else {
			mosMenuBar::cancel( 'cancelclient' );
		}
		mosMenuBar::spacer();
		if ($GLOBALS['task'] == 'editclient' || $GLOBALS['task'] == 'editclientA') {
		    mosMenuBar::help( 'client.edit' );
		} else {
		    mosMenuBar::help( 'client.new' );
		}
		mosMenuBar::endTable();
	}
	/**
	* Draws the default menu
	*/
	function _DEFAULT() {
		mosMenuBar::startTable();
		mosMenuBar::addNewX( 'newclient' );
		mosMenuBar::spacer();
		mosMenuBar::editListX( 'editclient' );
		mosMenuBar::spacer();
		mosMenuBar::deleteList( '', 'removeclients' );
		mosMenuBar::spacer();
		mosMenuBar::help( 'client' );
		mosMenuBar::endTable();
	}
}
?>
