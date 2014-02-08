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

class TOOLBAR_categories {
	/**
	* Draws the menu for Editing an existing category
	* @param int The published state (to display the inverse button)
	*/
	function _EDIT() {
		global $id;
		
		mosMenuBar::startTable();
		mosMenuBar::media_manager();
		mosMenuBar::spacer();
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
		$section='';
		if (strstr($GLOBALS['section'], 'com_contact')){
		    $section = 'contact.';
		} 
		if (strstr($GLOBALS['section'], 'com_newsfeeds')){
		    $section = 'newsfeeds.';		    
		}
		if (strstr($GLOBALS['section'], 'com_weblinks')){
		    $section = 'weblinks.';		    
		}
		
		if ($GLOBALS['task'] == 'new') {
		    mosMenuBar::help( $section.'new' );
		} else {
		    mosMenuBar::help( $section.'edit' );
		}
		mosMenuBar::endTable();
	}
	/**
	* Draws the menu for Moving existing categories
	* @param int The published state (to display the inverse button)
	*/
	function _MOVE() {
		mosMenuBar::startTable();
		mosMenuBar::save( 'movesave' );
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::spacer();
		mosMenuBar::help( 'move' );
		mosMenuBar::endTable();
	}
	/**
	* Draws the menu for Copying existing categories
	* @param int The published state (to display the inverse button)
	*/
	function _COPY() {
		mosMenuBar::startTable();
		mosMenuBar::save( 'copysave' );
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::spacer();
		mosMenuBar::help( 'copy' );
		mosMenuBar::endTable();
	}
	/**
	* Draws the menu for Editing an existing category
	*/
	function _DEFAULT(){
		$section = mosGetParam( $_REQUEST, 'section', '' );

		mosMenuBar::startTable();
		mosMenuBar::publishList();
		mosMenuBar::spacer();
		mosMenuBar::unpublishList();
		mosMenuBar::spacer();
		mosMenuBar::addNewX();
		mosMenuBar::spacer();
		if ( $section == 'content' || ( $section > 0 ) ) {
			mosMenuBar::customX( 'moveselect', 'move.png', 'move_f2.png', T_('Move'), true );
			mosMenuBar::spacer();
			mosMenuBar::customX( 'copyselect', 'copy.png', 'copy_f2.png', T_('Copy'), true );
			mosMenuBar::spacer();
		}
		mosMenuBar::editListX();
		mosMenuBar::spacer();
		mosMenuBar::deleteList();
		mosMenuBar::spacer();
		
		$section='';
		
		if (strstr($GLOBALS['section'], 'com_contact')){
		    $section = 'contact.';
		} 
		if (strstr($GLOBALS['section'], 'com_newsfeeds')){
		    $section = 'newsfeeds.';		    
		}
		if (strstr($GLOBALS['section'], 'com_weblinks')){
		    $section = 'weblinks.';		    
		}
		mosMenuBar::help( $section.'manager' );
		mosMenuBar::endTable();
	}
}
?>
