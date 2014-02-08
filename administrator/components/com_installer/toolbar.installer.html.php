<?php
/**
* @package Mambo
* @subpackage Installer
* @author Mambo Foundation Inc see README.php
* @copyright Mambo Foundation Inc.
* See COPYRIGHT.php for copyright notices and details.
* @license GNU/GPL Version 2, see LICENSE.php
* Mambo is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; version 2 of the License.
*/

class TOOLBAR_installer
{
	function _DEFAULT()	{
		mosMenuBar::startTable();
		if ($GLOBALS['task'] == 'thesource') {
		    mosMenuBar::help( 'source' );
		} else {
		    mosMenuBar::help( 'universal' );
		}
		mosMenuBar::endTable();
	}

	function _DEFAULT2($element)	{
		mosMenuBar::startTable();
		mosMenuBar::deleteList( '', 'remove', T_('Uninstall'));
		mosMenuBar::spacer();
		mosMenuBar::help( $element );
		mosMenuBar::endTable();
	}

	function _NEW()	{
		mosMenuBar::startTable();
		mosMenuBar::save();
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::endTable();
	}
}
?>
