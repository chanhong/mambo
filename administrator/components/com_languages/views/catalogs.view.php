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
class catalogsView extends View
{
    function render(&$renderer, &$request)
    {
        $lang = $request->get('lang');
        $language =& new mamboLanguage($lang);
        $language->load(true);
        $session =& $request->session();
        $col = isset($session['col']) ? $session['col'] : 'domain';
        $asc = isset($session['asc']) ? $session['asc'] : 1;
        $order = array();
        $files = $language->files;
		// search
        $search = $request->get('search');
		if($search !== '') {
			$ds = defined('DIRECTORY_SEPARATOR') ? DIRECTORY_SEPARATOR : '/';
			$basePath = mamboCore::get('mosConfig_absolute_path');
			$results = array();
			foreach($files as $file) {
				if($file['filetype'] !== 'po') continue;
				$filename = preg_replace("/[\/\\\\]+/",$ds,$basePath.$ds.$file['filename']);
				$content = file_get_contents($filename);
				$result = strpos($content, $search);
				if($result !== false) $results[] = $file;
			}
			if(count($results) > 0) $files = $results;
		}
		// end search
        foreach ($files as $key => $row) {
            if ($row['filetype'] == 'po'){
                $catalogs[]  = $row;
            }
        }
        // Obtain a list of columns
        foreach ($catalogs as $key => $row) {
            $order[$key]  = $row[$col];
        }
        array_multisort($order, $asc == 1 ? SORT_ASC : SORT_DESC, $catalogs);
        $renderer->addvar('col', $col);
        $renderer->addvar('asc', $asc);
        $renderer->addvar('rows', $catalogs);
        $renderer->addvar('header', sprintf(T_('Manage Translations: %s'), $lang));        
        $renderer->addvar('content', $renderer->fetch('catalogs.tpl.php'));
        $renderer->display('form.tpl.php');
    }
}
?>