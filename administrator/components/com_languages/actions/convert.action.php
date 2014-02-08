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
class convertAction extends Action
{
    function execute(&$controller, &$request)
    {

        $from_charset   = $_POST['charset'];
        $to_charset     = $_POST['newcharset'];
        $lang           = $_POST['language'];
        $textdomain = mamboCore::get('rootPath')."/language";

        $language = new mamboLanguage($lang);
        $language->load();
        $language->charset = $to_charset;
        $language->save();
        
        $gettext_admin = new PHPGettextAdmin();
        foreach ($language->files as $arr) { 
            $gettext_admin->convert_charset($arr['domain'], $textdomain, $lang, $from_charset, $to_charset);                                    
            $gettext_admin->message_format($arr['domain'], $textdomain, $lang);            
        }
        
        #return $controller->redirect('index', 'language');

        $request->set('task', 'edit');
        $request->set('act', 'language');
        $request->set('lang', $lang);
        $controller->view('edit');
        #

        /*$admin = new PHPGettextAdmin();
        $admin->convert_encoding($catalog, $from, $to);
        dump(iconv_get_encoding());*/
    }
}

?>