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

class TOOLBAR_menus {
	/**
	* Draws the menu for a New top menu item
	*/
	function _NEW()	{
		mosMenuBar::startTable();
		mosMenuBar::customX( 'edit', 'next.png', 'next_f2.png', T_('Next'), true );
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::spacer();
		mosMenuBar::help( 'new' );
		mosMenuBar::endTable();
	}

	/**
	* Draws the menu to Move Menut Items
	*/
	function _MOVEMENU()	{
		mosMenuBar::startTable();
		mosMenuBar::custom( 'movemenusave', 'move.png', 'move_f2.png', T_('Move'), false );
		mosMenuBar::spacer();
		mosMenuBar::cancel( 'cancelmovemenu' );
		mosMenuBar::spacer();
		mosMenuBar::help( 'move' );
		mosMenuBar::endTable();
	}

	/**
	* Draws the menu to Move Menut Items
	*/
	function _COPYMENU()	{
		mosMenuBar::startTable();
		mosMenuBar::custom( 'copymenusave', 'copy.png', 'copy_f2.png', T_('Copy'), false );
		mosMenuBar::spacer();
		mosMenuBar::cancel( 'cancelcopymenu' );
		mosMenuBar::spacer();
		mosMenuBar::help( 'copy' );
		mosMenuBar::endTable();
	}

	/**
	* Draws the menu to edit a menu item
	*/
	function _EDIT($type) {
		global $id;
		$hs='';

		if ( !$id ) {
			$cid = mosGetParam( $_POST, 'cid', array(0) );
			$id = $cid[0];
		}
		$menutype 	= mosGetParam( $_REQUEST, 'menutype', 'mainmenu' );
		
		mosMenuBar::startTable();
		if ( !$id ) {
			$link = 'index2.php?option=com_menus&menutype='. $menutype .'&task=new&hidemainmenu=1';
			mosMenuBar::back( T_('Back'), $link );
			mosMenuBar::spacer();
		}
		mosMenuBar::save();
		mosMenuBar::spacer();
		mosMenuBar::apply();
		mosMenuBar::spacer();
		if ( $id ) {
			// for existing content items the button is renamed `close`
			mosMenuBar::cancel( 'cancel', T_('Close') );
		} else {
			mosMenuBar::cancel();
		}
		mosMenuBar::spacer();
		mosMenuBar::help( $type ); 
		mosMenuBar::endTable();
	}

	function _DEFAULT() {
		mosMenuBar::startTable();
		mosMenuBar::addNewX();
		mosMenuBar::spacer();
		mosMenuBar::editListX();
		mosMenuBar::spacer();
		mosMenuBar::publishList();
		mosMenuBar::spacer();
		mosMenuBar::unpublishList();
		mosMenuBar::spacer();
		mosMenuBar::customX( 'movemenu', 'move.png', 'move_f2.png', T_('Move'), true );
		mosMenuBar::spacer();
		mosMenuBar::customX( 'copymenu', 'copy.png', 'copy_f2.png', T_('Copy'), true );
		mosMenuBar::spacer();
		mosMenuBar::trash();
		mosMenuBar::spacer();
		mosMenuBar::help( 'main' );
		mosMenuBar::endTable();
	}
}
?>
