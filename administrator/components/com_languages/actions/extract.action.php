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
class extractAction extends Action
{

    function execute(&$controller, &$request)
    {
        //if (!isset($_POST['domain']))

        $path = mamboCore::get('rootPath');
        $frontpaths  = array("$path/templates", "$path/modules", "$path/includes", "$path/mambots", "$path/");
        $adminpaths  = array("$path/administrator/modules", "$path/administrator/popups", "$path/administrator/includes", "$path/administrator/templates");
        $cmtpaths = array_merge(glob("$path/administrator/components/com*"), glob("$path/components/com*"));
        //$mambopaths = array_merge($frontpaths,$adminpaths,$cmtpaths);
        foreach ($cmtpaths as $p) {
            preg_match('/com_(.*)$/', $p, $matches);
            $components[$matches[1]][]  =  $p;
        }

        $dir = $path.'/language/untranslated';
        // only continue if we have no .pot files
        if (!is_dir($dir)) {
            @mkdir($dir);
        } else {
            return $controller->redirect('index', 'language');
        }/**/

        set_time_limit(300);
        if (!file_exists("$dir/installation.pot") && file_exists("$path/installation")) {
            $this->extract('installation', array($path.'/installation'));
        }
        /*if (!file_exists("$dir/mambo.pot")) {
            $this->extract('mambo', $mambopaths);
        }*/
        if (!file_exists("$dir/frontend.pot")) {
            $this->extract('frontend', $frontpaths);
        }
        if (!file_exists("$dir/administrator.pot")) {
            $this->extract('administrator', $adminpaths);
        }

        foreach ($components as $name => $dirs) {
            if (!file_exists("$dir/$name.pot")) {
                $this->extract($name, $dirs);
            }
        }

        $controller->redirect('index', 'language');
        #$controller->view('language');

    }

    function extract($domain, $scandirs, $language='untranslated')
    {

        $path = mamboCore::get('rootPath');
        $textdomain = $path.'/language';

        if (!file_exists("$textdomain/$language/$domain.pot")) {
            $catalog = new PHPGettext_Catalog($domain, $textdomain);
            $catalog->setproperty('mode', _MODE_POT_);
            $catalog->setproperty('lang', $language);
            $headers = $this->header();
            $catalog->setproperty('comments', $headers[0]);
            $catalog->setproperty('headers', $headers[1]);
            $catalog->save();
        }

        $this->scan_xml($domain, $textdomain, $scandirs, $language);
        
        $gettext_admin = new PHPGettextAdmin();
        $cwd = getcwd();
        chdir($path);

        $php_sources = array();
        if (is_array($scandirs))  {
            foreach ($scandirs as $subdir)  {
                $php_sources = array_merge($php_sources, $this->read_dir($subdir, 'php', true));
            }
        } else {
            $php_sources = $this->read_dir($scandirs, 'php', true);
        }
        $gettext_admin->xgettext($domain, $textdomain, $php_sources, $language);

        chdir($cwd);

        return true;
    }

    function scan_xml($domain, $path, $scandirs, $language='untranslated')
    {
        $catalog = new PHPGettext_Catalog($domain, $path);
        $catalog->setproperty('mode', _MODE_POT_);
        $catalog->setproperty('lang', $language);
        $catalog->load();
        $xml_sources = array();
        if (is_array($scandirs))  {
            foreach ($scandirs as $subdir)  {
                $xml_sources = array_merge($xml_sources, $this->read_dir($subdir, 'xml', true));
            }
        } else {
            $xml_sources = $this->read_dir($scandirs, 'xml', true);
        }

        if (count($xml_sources) > 0) {
            $strings = array();
            foreach ($xml_sources as $file) {
                $p = xml_parser_create();
                xml_parser_set_option($p, XML_OPTION_CASE_FOLDING, 0);
                xml_parser_set_option($p, XML_OPTION_SKIP_WHITE, 1);
                xml_parse_into_struct($p, file_get_contents(mamboCore::get('rootPath').'/'.$file), $values);
                xml_parser_free($p);
                foreach($values as $key => $value)
                {
                    switch ($value['tag'])
                    {
                        case 'name':
                        case 'description':
                        case 'option':
                        case 'menu':
                        if (isset($value['value']) && strlen($value['value']) >=1) $strings[$file][] = addcslashes($value['value'],'"');
                        break;
                        case 'param':
                        if (isset($value['attributes']) && $value['attributes']['type'] != 'spacer') {
                            if (isset($value['attributes']['label'])) $strings[$file][] = addcslashes($value['attributes']['label'],'"');
                            if (isset($value['attributes']['description'])) $strings[$file][] = addcslashes($value['attributes']['description'],'"');
                        }
                        break;
                    }
                }
                if (is_array($strings[$file]))
                $strings[$file] = array_values(array_unique($strings[$file]));
            }
            foreach ($strings as $file => $str) {
                foreach ($str as $msg)
                $messages[trim($msg)][] = '#: '.$file;
            }
            if (is_array($messages)){
                foreach ($messages as $msgid => $comments) {
                    if (!empty($msgid))
                    $catalog->addentry($msgid, null, null, $comments);#($msgid, $msgid_plural=null, $msgstr=null, $comments=array())
                }
            }
            $catalog->save();
        }
    }

    function read_dir($dir, $filetype='php', $checkSlash = false)
    {
        static $root_path;
        $deep = true;
        if (substr($dir,-1)=='/' && $checkSlash ) $deep = false;
        if (is_null($root_path))
        $root_path = str_replace( '\\', '/', mamboCore::get('rootPath') );
        if (!file_exists($dir)) return false;
        $array = array();
        $d = dir($dir);
        while (false !== ($entry = $d->read())) {
            if($entry!='.' && $entry!='..') {
                $entry = "$dir/$entry";
                $entry = str_replace( '\\', '/', $entry );
                if(is_dir($entry) && $deep) {
                    $array = array_merge($array, $this->read_dir($entry, $filetype));
                } elseif (preg_match('/.'.$filetype.'$/', $entry)) {
                    $new_entry = str_replace($root_path.'/', '', $entry);
                    if ($new_entry[0] == '/') $new_entry = substr($new_entry, 1);
                    $array[] = $new_entry;
                }
            }
        }
        $d->close();
        return $array;
    }

    function header($charset='utf-8', $plurals='nplurals=2; plural=n == 1 ? 0 : 1;'){
        $year = date('Y');
        $comments = <<<EOT
# Mambo Open Source.
# Copyright (C) 2005 - $year Mambo Foundation Inc.
# This file is distributed under the same license as the Mambo package.
# Translation Team <translation@mambo-foundation.org>, $year#
#
#, fuzzy
EOT;
        $comments = explode("\n", $comments);
        $headers = array(
        'Project-Id-Version'    => 'Mambo 4.6',
        'Report-Msgid-Bugs-To'  => 'translation@mambo-foundation.org',
        'POT-Creation-Date'     => date('Y-m-d h:iO'),
        'PO-Revision-Date'      => date('Y-m-d h:iO'),
        'Last-Translator'       => 'Translation <translation@mambo-foundation.org>',
        'Language-Team'         => 'Translation <translation@mambo-foundation.org>',
        'MIME-Version'          => '1.0',
        'Content-Type'          => 'text/plain; charset='.$charset,
        'Content-Transfer-Encoding' => '8bit',
        'Plural-Forms'              => $plurals
        );
        return array($comments, $headers);
    }
}
?>