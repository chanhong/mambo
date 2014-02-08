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

class TOOLBAR_messages {
	function _VIEW() {
		mosMenuBar::startTable();
		mosMenuBar::customX('reply', 'restore.png', 'restore_f2.png', T_('Reply'), false );
		mosMenuBar::spacer();
		mosMenuBar::deleteList();
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::endTable();
	}

	function _EDIT() {
		mosMenuBar::startTable();
		mosMenuBar::save( 'save', T_('Send') );
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::spacer();
		mosMenuBar::help( 'new' );
		mosMenuBar::endTable();
	}

	function _CONFIG() {
		mosMenuBar::startTable();
		mosMenuBar::save( 'saveconfig' );
		mosMenuBar::spacer();
		mosMenuBar::cancel( 'cancelconfig' );
		mosMenuBar::spacer();
		mosMenuBar::help( 'configuration' );
		mosMenuBar::endTable();
	}

	function _DEFAULT() {
		mosMenuBar::startTable();
		mosMenuBar::addNewX();
		mosMenuBar::spacer();
		mosMenuBar::deleteList();
		mosMenuBar::spacer();
		mosMenuBar::help( 'inbox' );
		mosMenuBar::endTable();
	}
}
?>
