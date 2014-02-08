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
class indexView extends View
{
    function render(&$renderer, &$request)
    {
        $rows           = array();
        $languageDir    = mamboCore::get('mosConfig_absolute_path')."/language/";
        $xmlFilesInDir  = mosReadDirectory($languageDir,'.xml$');
        $rowid = 0;               
        
        foreach($xmlFilesInDir as $xmlfile) {
            // Read the file to see if it's a valid template XML file
            $parser =& new mosXMLDescription($languageDir.$xmlfile);
            if ($parser->getType() != 'language') continue;
            $row                = new StdClass();
            $row->id            = $rowid;
            $row->language      = substr($xmlfile,0,-4);
            $row->name          = $parser->getName('language');
            $row->creationdate  = $parser->getCreationDate('language');
            $row->author        = $parser->getAuthor('language');
            $row->copyright     = $parser->getCopyright('language');
            $row->authorEmail   = $parser->getAuthorEmail('language');
            $row->authorUrl     = $parser->getAuthorUrl('language');
            $row->version       = $parser->getVersion('language');
            $row->checked_out = 0;
            $row->mosname = strtolower( str_replace( " ", "_", $row->name ) );
            $row->published = (mamboCore::get('mosConfig_locale') == $row->language) ? 1 : 0;
            $rows[] = $row;
            $rowid++;
            
        
        }
        
        $renderer->addvar('rows', $rows);
        $renderer->addvar('content', $renderer->fetch('table.tpl.php'));
        $renderer->display('form.tpl.php');
    }    
}
?>