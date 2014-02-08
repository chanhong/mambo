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

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class TOOLBAR_FrontPage {
	function _DEFAULT() {
		mosMenuBar::startTable();
		mosMenuBar::publishList();
		mosMenuBar::spacer();
		mosMenuBar::unpublishList();
		mosMenuBar::spacer();
		mosMenuBar::archiveList();
		mosMenuBar::spacer();
		mosMenuBar::custom('remove','delete.png','delete_f2.png',T_('Remove'), true);
		mosMenuBar::spacer();
		mosMenuBar::help( 'manager' );
		mosMenuBar::endTable();
	}
}
?>