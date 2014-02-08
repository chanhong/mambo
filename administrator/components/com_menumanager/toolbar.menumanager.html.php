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

class TOOLBAR_menumanager {
	/**
	* Draws the menu for the Menu Manager
	*/
	function _DEFAULT() {
		mosMenuBar::startTable();
		mosMenuBar::addNewX();
		mosMenuBar::spacer();
		mosMenuBar::editListX();
		mosMenuBar::spacer();
		mosMenuBar::customX( 'copyconfirm', 'copy.png', 'copy_f2.png', T_('Copy'), true );
		mosMenuBar::spacer();
		mosMenuBar::customX( 'deleteconfirm', 'delete.png', 'delete_f2.png', T_('Delete'), true );
		mosMenuBar::spacer();
		mosMenuBar::help( 'main' );
		mosMenuBar::endTable();
	}

	/**
	* Draws the menu to delete a menu
	*/
	function _DELETE() {
		mosMenuBar::startTable();
		mosMenuBar::cancel( );
		mosMenuBar::endTable();
	}

	/**
	* Draws the menu to create a New menu
	*/
	function _NEWMENU($task = 'new')	{
		mosMenuBar::startTable();
		mosMenuBar::custom( 'savemenu', 'save.png', 'save_f2.png', T_('Save'), false );
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::spacer();
		mosMenuBar::help( $task );
		mosMenuBar::endTable();
	}

	/**
	* Draws the menu to create a New menu
	*/
	function _COPYMENU()	{
		mosMenuBar::startTable();
		mosMenuBar::custom( 'copymenu', 'copy.png', 'copy_f2.png', T_('Copy'), false );
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::spacer();
		mosMenuBar::help( 'copy' );
		mosMenuBar::endTable();
	}

}
?>
