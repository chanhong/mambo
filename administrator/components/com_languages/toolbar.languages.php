<?php
/**
* @package Mambo
* @subpackage Languages
* @author Mambo Foundation Inc see README.php
* @copyright Mambo Foundation Inc.
* See COPYRIGHT.php for copyright notices and details.
* @license GNU/GPL Version 2, see LICENSE.php
* Mambo is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; version 2 of the License.
*/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
class ToolBar
{
    var $request = array();
    var $map 	 = array();
    var $has_gettext;

    function ToolBar()
    {
        $this->request = $_REQUEST;
        $gettext_admin = new PHPGettextAdmin();
        $this->has_gettext = $gettext_admin->has_gettext;
    }
    function display($method = null)
    {
        $task = isset($_REQUEST['task']) && !empty($_REQUEST['task']) ? $_REQUEST['task'] : 'index';
        if($task == 'index' && isset($_REQUEST['act'])) $task = $_REQUEST['act'];
        if (is_null($method))$method = $task;
        if (in_array($method,  array_keys($this->map)))$method = $this->map[$method];
        if ($method && strlen($method) > 2 && method_exists($this, $method))
        return $this->$method();
        return false;
    }
    function _default(){}
}

class languagesToolbar extends ToolBar {

    var $map = array('index' => 'language','extract' => 'language', 'sort' => 'catalogs', 'new' => 'newlang', 'save' => 'edit', 'auto_translate' => 'edit', 'convert' => 'edit', 'apply' => 'edit');

    function edit() {
        mosMenuBar::startTable();
        if ($this->request['act'] != 'catalogs') {
            #mosMenuBar::custom( 'translate', 'edit.png', 'edit_f2.png', T_('Manage Translations'), false );
            #mosMenuBar::spacer();
        } else {
            mosMenuBar::custom( 'auto_translate', 'copy.png', 'copy_f2.png', T_('Auto Translate'), false );
            mosMenuBar::spacer(20);
            mosMenuBar::apply();
            mosMenuBar::spacer();
        }
        mosMenuBar::save();
        mosMenuBar::spacer();
        mosMenuBar::cancel();
        mosMenuBar::spacer();
        #mosMenuBar::help( 'screen.languages.edit' );
        mosMenuBar::endTable();
    }
    function update() {
        mosMenuBar::startTable();
        mosMenuBar::cancel();
        mosMenuBar::spacer();
        #mosMenuBar::help( 'screen.languages.update' );
        mosMenuBar::endTable();
    }

    function language() {
        mosMenuBar::startTable();
        if (!is_dir(mamboCore::get('rootPath').'/language/untranslated') && $this->has_gettext) {
            mosMenuBar::customX( 'extract', 'query.png', 'query.png', T_('Scan Sources'), false );
            mosMenuBar::spacer(50);
        }
        mosMenuBar::custom( 'install', 'move.png', 'move_f2.png', T_('Install'), false );
        mosMenuBar::spacer();
        mosMenuBar::custom( 'translate', 'edit.png', 'edit_f2.png', T_('Manage Translations'), true );
        mosMenuBar::spacer();
        mosMenuBar::custom( 'export', 'upload.png', 'upload_f2.png', T_('Export'));
        mosMenuBar::spacer();
        mosMenuBar::addNewX();
        mosMenuBar::spacer();
        mosMenuBar::editListX( 'edit' );
        mosMenuBar::spacer();
        mosMenuBar::deleteList();
        mosMenuBar::spacer();
        #mosMenuBar::help( 'screen.languages.language' );
        mosMenuBar::endTable();
    }

    function newlang() {
        mosMenuBar::startTable();
        mosMenuBar::save();
        mosMenuBar::spacer();
        mosMenuBar::cancel();
        mosMenuBar::spacer();
        #mosMenuBar::help( 'screen.languages.main' );
        mosMenuBar::endTable();
    }

    function catalogs()
    {
        mosMenuBar::startTable();
        mosMenuBar::customX( 'update', 'publish.png', 'publish_f2.png', T_('Update'), false );
        mosMenuBar::spacer();
        mosMenuBar::editListX( 'edit' );
        mosMenuBar::spacer();
        mosMenuBar::cancel();
        mosMenuBar::spacer();
        #mosMenuBar::help( 'screen.languages.main' );
        mosMenuBar::endTable();
    }
}

$toolbar = new languagesToolbar('languages');
$toolbar->display();

?>