<?php
/**
* @package Mambo
* @subpackage Content
* @author Mambo Foundation Inc see README.php
* @copyright Mambo Foundation Inc.
* See COPYRIGHT.php for copyright notices and details.
* @license GNU/GPL Version 2, see LICENSE.php
* Mambo is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; version 2 of the License.
*/

class TOOLBAR_content {
	function _EDIT() {
		global $id;
		
		mosMenuBar::startTable();
		mosMenuBar::preview( 'contentwindow', true );
		mosMenuBar::spacer();
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
		if ($GLOBALS['task'] == 'new') {
		    mosMenuBar::help( 'new' );
		} else {
		    mosMenuBar::help( 'edit' );
		}
		mosMenuBar::endTable();
	}

	function _ARCHIVE() {
		mosMenuBar::startTable();
		mosMenuBar::unarchiveList();
		mosMenuBar::spacer();
		mosMenuBar::custom( 'remove', 'delete.png', 'delete_f2.png', T_('Trash'), false );
		mosMenuBar::spacer();
		mosMenuBar::help( 'archive' );
		mosMenuBar::endTable();
	}

	function _MOVE() {
		mosMenuBar::startTable();
		mosMenuBar::custom( 'movesectsave', 'save.png', 'save_f2.png', T_('Save'), false );
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::spacer();
		mosMenuBar::help( 'move' );
		mosMenuBar::endTable();
	}

	function _COPY() {
		mosMenuBar::startTable();
		mosMenuBar::custom( 'copysave', 'save.png', 'save_f2.png', T_('Save'), false );
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::spacer();
		mosMenuBar::help( 'copy' );
		mosMenuBar::endTable();
	}

	function _DEFAULT() {
		mosMenuBar::startTable();
		mosMenuBar::addNewX();
		mosMenuBar::spacer();
		mosMenuBar::editListX( 'editA' );
		mosMenuBar::spacer();
		mosMenuBar::publishList();
		mosMenuBar::spacer();
		mosMenuBar::unpublishList();
		mosMenuBar::spacer();
		mosMenuBar::customX( 'movesect', 'move.png', 'move_f2.png', T_('Move') );
		mosMenuBar::spacer();
		mosMenuBar::customX( 'copy', 'copy.png', 'copy_f2.png', T_('Copy') );
		mosMenuBar::spacer();
		mosMenuBar::archiveList();
		mosMenuBar::spacer();
		mosMenuBar::trash();
		mosMenuBar::spacer();
		mosMenuBar::help( 'manager' );
		mosMenuBar::endTable();
	}
}
?>
