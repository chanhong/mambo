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
class saveAction extends Action
{
    function execute(&$controller, &$request)
    {
        $iso639 = strtolower(mosGetParam($_POST, 'iso639'));
        $iso3166 = mosGetParam($_POST, 'iso3166_2');
        $iso3166_3 = mosGetParam($_POST, 'iso3166_3');
        $lang  = $iso639;
        $lang .= strlen($iso3166) == 2 ? '_'.$iso3166 : '';
        $root = mamboCore::get('rootPath');
        $langfile = $root.'/language/'.$lang.'.xml';

        switch ($_POST['act'])
        {
            case 'language':
            if (file_exists($langfile)) {
                $this->updatelanguage($lang);
            }
            else {
                $this->createlanguage($iso639, $iso3166, $iso3166_3);
            }
            return $controller->redirect('index', 'language');
            break;
            case 'catalogs':
            default:
            $this->updatecatalog(false);
            return $controller->redirect('index', 'catalogs');
            break;
        }
    }



    function createLanguage($iso639, $iso3166, $iso3166_3) {
        $locales = mamboLanguage::getLocales();
        $default = $locales['locales'][$iso639];
        $lang  = $iso639;
        $lang .= strlen($iso3166) == 2 ? '_'.$iso3166 : '';
        $language =& new mamboLanguage($lang);
        foreach ($default as $k => $v) {
            if (in_array($k, array_keys(get_class_vars(get_class($language))))) {
                $language->$k = $v;
            }
        }
        foreach ($_POST as $k => $v) {
            if (in_array($k, array_keys(get_class_vars(get_class($language))))) {
                $language->$k = $v;
            }
        }
        $language->name = $lang;
        $language->description = $language->title.' Locale';
        if (!empty($language->territory)) $language->description .= ' For '.$language->territory;
        $language->locale = $lang.'.'.$language->charset.','.$lang.','.$iso639.','.strtolower($language->title);
        $language->iso3166_3 = $iso3166_3;
        $language->creationdate = date('d-m-Y');
        $language->author = 'Mambo Foundation Inc.';
        $language->authorurl = 'http://www.mambo-foundation.org';
        $language->authoremail = 'translation@mambo-foundation.org';
        $language->copyright = 'Refer to copyright.php';
        $language->license = 'http://www.gnu.org/copyleft/gpl.html GNU/GPL';
        $language->setPlurals($_POST['plural_form']);

        $textdomain = rtrim($language->path, '\/');
        $dir = $textdomain.'/'.$language->name;
        $untranslated = $textdomain.'/untranslated';
        $charset = $language->charset;
        $langfiles  = mosReadDirectory($untranslated,'.pot$');
        @mkdir($dir);
        @mkdir($dir.'/LC_MESSAGES');

        //$gettext_admin = new PHPGettextAdmin();
        foreach ($langfiles as $domain)  {
            $domain = substr($domain,0,-4);
            /*if (file_exists("$textdomain/glossary/$lang.$charset.po")) {
                copy("$textdomain/glossary/$lang.$charset.po", "$dir/$lang.po");
                $gettext_admin->initialize_translation($domain, $textdomain, $lang, $charset);
                $gettext_admin->compile($lang, $textdomain, $charset);
            } else {*/
                copy("$untranslated/$domain.pot", "$dir/$domain.po");
            //}
        }        
        //if (!file_exists("$textdomain/$lang/$lang.po")) {
        //    @copy("$textdomain/glossary/untranslated.pot", "$textdomain/$lang/$lang.po");
        //}
        $language->save();
    }
    
    function updatelanguage($lang)
    {

        $language =& new mamboLanguage($lang);
        $language->load();
        foreach ($_POST as $k => $v) {
            if (in_array($k, array_keys(get_class_vars(get_class($language))))) {
                $language->$k = $v;
            }
        }
        $language->setPlurals($_POST['plural_form']);
        $language->save();
    }

    function updatecatalog($compile = true, $add_to_dict = true)
    {

        $domain     = $_POST['domain'];
        $textdomain = $_POST['textdomain'];
        $lang       = $_POST['lang'];

        $catalog = new PHPGettext_catalog($domain, $textdomain);
        $catalog->setproperty('mode', 'po');
        $catalog->setproperty('lang', $lang);
        $catalog->load();

        $catalog->setComments($_POST['comments']);
        $catalog->setHeaders($_POST['headers']);
        $plural_forms = $catalog->headers['Plural-Forms'];
        preg_match('/nplurals[\s]*[=]{1}[\s]*([\d]+);[\s]*plural[\s]*[=]{1}[\s]*(.*);/', $plural_forms, $matches);
        $is_plural = $matches[1] > 1;
        foreach ($_POST as $key => $value) {
            if (preg_match('/^([a-z]+[_]?[a-z]+?)[_]?([0-9]+)?_([0-9]+)$/', $key, $matches))  {
                switch ($matches[1])
                {
                    case 'msgid':
                        if (get_magic_quotes_gpc() == 1){
                            $value = stripslashes($value);
                            //$value = htmlentities($value);
                        }
                    $messages[$matches[3]]['msgid'] = $value;
                    break;
                    case 'msgid_plural':
                        if ($is_plural){
                            $messages[$matches[3]]['msgid_plural'] = $value;
                        }
                    break;
                    case 'msgstr':
                    if (!empty($messages[$matches[3]]['msgid_plural'])) {

                        if ($matches[2] != '') {
                            $messages[$matches[3]]['msgstr'][$matches[2]] =  stripslashes($value);
                        } else {
                            $messages[$matches[3]]['msgstr'][0] =  stripslashes($value);
                            $messages[$matches[3]]['msgstr'][1] =  '';
                        }
                    } else {
                        $messages[$matches[3]]['msgstr'] =  stripslashes($value);
                    }
                    break;
                    case 'fuzzy':
                    $messages[$matches[3]]['fuzzy'] = $value == 'true' ? true : false;
                    break;
                }
            }
        }
        foreach ($messages as $index => $arr) {
            if (strcmp($catalog->strings[$index]->msgid, $arr['msgid']) == 0) {
                $catalog->strings[$index]->setmsgstr($arr['msgstr']);
                $catalog->strings[$index]->msgid_plural = isset($arr['msgid_plural'])?$arr['msgid_plural']:null;;
                $catalog->strings[$index]->setfuzzy($arr['fuzzy']);
            }
        }
        $catalog->save();

        $language = new mamboLanguage($lang);
        $language->save();

        $gettext_admin = new PHPGettextAdmin();
        $gettext_admin->add_to_dict($domain, $textdomain, $lang, $language->charset);
        $catalog->load();

        if ($compile) {
            $catalog->setproperty('mode', 'mo');
            $catalog->save();
        }

    }
}


?>