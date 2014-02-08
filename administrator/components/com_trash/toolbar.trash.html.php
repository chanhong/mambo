<?php
/**
* @package Mambo
* @subpackage Trash
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

class TOOLBAR_Trash {
	function _DEFAULT() {
		mosMenuBar::startTable();
		mosMenuBar::custom('restoreconfirm','restore.png','restore_f2.png',T_('Restore'), true);
		mosMenuBar::spacer();
		mosMenuBar::custom('deleteconfirm','delete.png','delete_f2.png',T_('Delete'), true);
		mosMenuBar::spacer();
		mosMenuBar::help( 'manager' );
		mosMenuBar::endTable();
	}

	function _DELETE() {
		mosMenuBar::startTable();
		mosMenuBar::cancel();
		mosMenuBar::endTable();
	}

	function _SETTINGS() {
		mosMenuBar::startTable();
		mosMenuBar::save();
		mosMenuBar::spacer();
		mosMenuBar::back();
		mosMenuBar::endTable();
	}

}
?>
