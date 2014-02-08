<?php
/**
* @package Mambo
* @subpackage Templates
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

class TOOLBAR_templates {
	function _DEFAULT($client) {
		mosMenuBar::startTable();
		if ($client=="admin") {
			mosMenuBar::custom('publish', 'publish.png', 'publish_f2.png', T_('Default'), true);
			mosMenuBar::spacer();
			$client = '.'.$client;
		} else {
			mosMenuBar::makeDefault();
			mosMenuBar::spacer();
			mosMenuBar::assign();
			mosMenuBar::spacer();
		}
		
		mosMenuBar::addNew();
		mosMenuBar::spacer();
		mosMenuBar::editHtmlX( 'edit_source' );
		mosMenuBar::spacer();
		mosMenuBar::editCssX( 'edit_css' );
		mosMenuBar::spacer();
		mosMenuBar::deleteList();
		mosMenuBar::spacer();
		mosMenuBar::help( 'manager' . $client );
		mosMenuBar::endTable();
	}
 	function _VIEW(){
		mosMenuBar::startTable();
		mosMenuBar::back();
		mosMenuBar::endTable();
	}

	function _EDIT_SOURCE(){
		mosMenuBar::startTable();
		mosMenuBar::save( 'save_source' );
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::spacer();
		mosMenuBar::help( 'edit-html' );
		mosMenuBar::endTable();
	}

	function _EDIT_CSS(){
		mosMenuBar::startTable();
		mosMenuBar::save( 'save_css' );
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::spacer();
		mosMenuBar::help( 'edit-css' );
		mosMenuBar::endTable();
	}

	function _ASSIGN(){
		mosMenuBar::startTable();
		mosMenuBar::save( 'save_assign', T_('Save') );
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::spacer();
		mosMenuBar::help( 'assign' );
		mosMenuBar::endTable();
	}

	function _POSITIONS(){
		mosMenuBar::startTable();
		mosMenuBar::save( 'save_positions' );
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::spacer();
		mosMenuBar::help( 'module-positions' );
		mosMenuBar::endTable();
	}
}
?>
