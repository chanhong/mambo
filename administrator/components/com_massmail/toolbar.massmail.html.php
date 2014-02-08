<?php
/**
* @package Mambo
* @subpackage Massmail
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

class TOOLBAR_massmail {
	/**
	* Draws the menu for a New Contact
	*/
	function _DEFAULT() {
		mosMenuBar::startTable();
		mosMenuBar::custom('send','publish.png','publish_f2.png',T_('Send Mail'),false);
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::spacer();
		mosMenuBar::help( 'main' );
		mosMenuBar::endTable();
	}
}
?>
