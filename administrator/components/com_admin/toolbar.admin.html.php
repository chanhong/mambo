<?php
/**
* @package Mambo
* @subpackage Admin
* @author Mambo Foundation Inc see README.php
* @copyright Mambo Foundation Inc.
* See COPYRIGHT.php for copyright notices and details.
* @license GNU/GPL Version 2, see LICENSE.php
* Mambo is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; version 2 of the License.
*/

class TOOLBAR_admin {
	function _SYSINFO() {
		mosMenuBar::startTable();
		mosMenuBar::help( 'system.info' );
		mosMenuBar::endTable();
	}
	/**
	* Draws the menu for a New category
	*/
	function _CPANEL() {
		mosMenuBar::startTable();
		mosMenuBar::help( 'cpanel' );
		mosMenuBar::endTable();
	}
	/**
	* Draws the menu for a New category
	*/
	function _DEFAULT() {
		mosMenuBar::startTable();
		//mosMenuBar::help( 'cpanel' );
		mosMenuBar::endTable();
	}
}
?>
