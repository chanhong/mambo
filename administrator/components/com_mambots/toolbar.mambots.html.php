<?php
/**
* @package Mambo
* @subpackage Mambots
* @author Mambo Foundation Inc see README.php
* @copyright Mambo Foundation Inc.
* See COPYRIGHT.php for copyright notices and details.
* @license GNU/GPL Version 2, see LICENSE.php
* Mambo is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; version 2 of the License.
*/

class TOOLBAR_modules {
    /**
	* Draws the menu for Editing an existing module
	*/
    function _EDIT() {
        global $id;

        mosMenuBar::startTable();
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
            if ($_POST) {
                $cid = (int) $GLOBALS['cid'][0];
            } else {
                $cid = (int) $_GET['id'];
            }
            $database =& mamboDatabase::getInstance();
            $database->setQuery('select element from #__mambots where id = '.$cid);
            $result = $database->loadResult();
            mosMenuBar::help( $result ? $result : 'edit' );
        }
        mosMenuBar::endTable();
    }

    function _DEFAULT() {
        mosMenuBar::startTable();
        mosMenuBar::publishList();
        mosMenuBar::spacer();
        mosMenuBar::unpublishList();
        mosMenuBar::spacer();
        mosMenuBar::addNewX();
        mosMenuBar::spacer();
        mosMenuBar::editListX();
        mosMenuBar::spacer();
        mosMenuBar::deleteList();
        mosMenuBar::spacer();
        mosMenuBar::help( 'manager' );
        mosMenuBar::endTable();
    }
}
?>
