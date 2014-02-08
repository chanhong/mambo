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
class removeAction extends Action
{
    function execute(&$controller, &$request)
    {
        $langpath = mamboCore::get('rootPath').'/language/';
        if ($_POST['lang'] && $_POST['lang'] != 'en') {
            $language =& new mamboLanguage($_POST['lang']);
            @unlink($langpath.'glossary/'.$_POST['lang'].'.'.$language->charset.'.po');
            @unlink($langpath.$_POST['lang'].'.xml');
            $this->rmdir($langpath.$_POST['lang']);
        }
        $controller->redirect('index', mosGetParam($_REQUEST, 'act', 'language'));
    }
    function rmdir($dir) {
        if($files = glob($dir."/*")){
            foreach($files as $file) {
                is_dir($file)? $this->rmdir($file) : unlink($file);
            }
        }
        rmdir($dir);
    }
}

?>