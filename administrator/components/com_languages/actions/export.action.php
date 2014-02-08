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
class exportAction extends Action
{
    function execute(&$controller, &$request)
    {
        $root = mamboCore::get('rootPath');
        $live = mamboCore::get('mosConfig_live_site');
        include($root.'/administrator/includes/pcl/pclzip.lib.php');
        chdir($root);

        $lang = mosGetParam($_POST, 'lang', '');
        $language = new mamboLanguage($lang);
        $language->load(true);
        $zipfile = "$root/media/MamboLanguage_$lang.zip";
        $archive = new PclZip($zipfile);

        foreach ($language->files as $file) {
            $v_list = $archive->add($root.'/'.$file['filename'], PCLZIP_OPT_REMOVE_PATH, $root.'language/');
            if ($v_list == 0){
               die("Error : ".$archive->errorInfo(true));
            }
        }

        if (ereg('Opera(/| )([0-9].[0-9]{1,2})', $UserAgent)) {
            $UserBrowser = "Opera";
        }
        elseif (ereg('MSIE ([0-9].[0-9]{1,2})', $UserAgent)) {
            $UserBrowser = "IE";
        } else {
            $UserBrowser = '';
        }
        $mime_type = 'application/x-zip';
        $filename = "MamboLanguage_$lang.zip";
        @ob_end_clean();
        ob_start();
        header('Content-Type: ' . $mime_type);
        header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');

        if ($UserBrowser == 'IE') {
            header('Content-Disposition: inline; filename="' . $filename . '"');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
        } else {
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Pragma: no-cache');
        }
        
        readfile($zipfile);
        ob_end_flush();
        $fmanager =& mosFileManager::getInstance();
        $fmanager->deleteFile($zipfile);
        exit(0);
    }
}

?>