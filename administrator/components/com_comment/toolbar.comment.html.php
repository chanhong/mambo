<?php
/**
* @package Mambo
* @subpackage Comment
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

class TOOLBAR_MenuComment {

	function CONFIG_MENU() {
		mosMenuBar::startTable();
		mosMenuBar::save( 'savesettings', T_('Save') );
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::spacer();
		mosMenuBar::help( 'settings' );
		mosMenuBar::endTable();
	}

	function FILE_MENU() {
		mosMenuBar::startTable();
		mosMenuBar::save();
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::spacer();
		if ($GLOBALS['task'] == 'new') {
		mosMenuBar::help( 'new' );
		} else {
		mosMenuBar::help( 'edit' );
		}
		mosMenuBar::endTable();
	}

	function DEFAULT_MENU() {
		mosMenuBar::startTable();
		mosMenuBar::publishList();
		mosMenuBar::spacer();
		mosMenuBar::unpublishList();
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
?>