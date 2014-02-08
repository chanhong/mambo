<?php
/**
* Language Class for Mambo
* @package Mambo
* @author Mambo Foundation Inc see README.php
* @copyright Mambo Foundation Inc.
* See COPYRIGHT.php for copyright notices and details.
* @license GNU/GPL Version 2, see LICENSE.php
* Mambo is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; version 2 of the License.
*/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
class mamboLanguage {
    var $name = '';
    var $path = '';
    var $version = '4.6';
    var $title = '';
    var $description = '';
    var $creationdate = '';
    var $author = '';
    var $authorurl = '';
    var $authoremail = '';
    var $copyright = '';
    var $license = '';
    var $territory = '';
    var $text_direction = '';
    var $date_format = '';
    var $iso639 = '';
    var $iso3166_2 = '';
    var $iso3166_3 = '';
    var $locale = '';
    var $charset = '';
    var $codesets = array();
    var $plural_form = array();
    var $days = array('sun'=>'','mon'=>'','tue'=>'','wed'=>'','thu'=>'','fri'=>'','sat'=>'');
    var $months = array('jan'=>'','feb'=>'','mar'=>'','apr'=>'','may'=>'','jun'=>'','jul'=>'','aug'=>'','sep'=>'','oct'=>'','nov'=>'','dec'=>'');
    var $files = array();

    function mamboLanguage($lang, $path = null) {
        $this->name = $lang;
        $this->path = $path;
        if (is_null($this->path)) $this->path = mamboCore::get('rootPath').'/language/';
        $this->load();
    }

    function getFileName() {
        $file = $this->iso639;
        $file .= strlen($this->iso3166_2) == 2 ? '_' . $this->iso3166_2 : '';
        return $file;
    }

    function get($var) {
        return isset($this->$var) ? $this->$var : null;
    }
    function set($var, $value) {
        if (isset($this->$var)) $this->$var = $value;
    }
    function save() {
    global $page_,$task,$mapcharset;
    $this->updateFiles();
    $xml = $this->toXML();
    if( (($page_=="addpage") && ($task=="save")) || ($task=="convert") ){
        if (strtolower($this->charset) != 'utf-8') {
			$xml = $this->iconvert("utf-8",$mapcharset[$this->charset],$xml);
        }
    }
        /**/
        $fp = fopen($this->path . $this->getFileName() . '.xml', 'w+');
        fwrite($fp, $xml);
        fclose($fp);
    }

    function getLanguages() {
        $langfiles = glob($this->path . "*.xml");
        foreach($langfiles as $xml) {
            $xml = str_replace($this->path, '', $xml);
            if (substr($xml, 0, -4) != 'locales') {
                $lobj = &new mamboLanguage(substr($xml, 0, -4), $this->path) ;
                $langs[$lobj->name] = $lobj;
            }
        }
        return $langs;
    }

    function setPlurals($exp) {
        preg_match('/nplurals\s*=\s*(\d+)\s*;\s*plural\s*=\s*(.*)\s*;/', $exp, $plurals);
        $this->plural_form = array('nplurals' => $plurals[1], 'plural' => $plurals[2], 'expression' => $plurals[0]);
    }

    function getDate($format = null, $timestamp = null) {
        if (is_null($format)) $format = $this->date_format;
        if (is_null($timestamp)) {
            $timestamp = time();
        }
        $days = array_values($this->days);
        $months = array_values($this->months);
        $date = preg_replace('/%[aA]/', $days[(int)strftime('%w', $timestamp)], $format);
        $date = preg_replace('/%[bB]/', $months[(int)strftime('%m', $timestamp)-1], $date);
        return strftime($date, $timestamp);
    }

    function load($load_catalogs = false) {
		global $mapcharset;
        if (is_readable($this->path . $this->name . ".xml")) {
            $source = file_get_contents($this->path . $this->name . ".xml");
            if (preg_match('/<?xml.*encoding=[\'"](.*?)[\'"].*?>/m', $source, $m)) {
                $encoding = strtoupper($m[1]);
            } else {
                $encoding = "UTF-8";
            }

            if($encoding == "UTF-8" || $encoding == "US-ASCII" || $encoding == "ISO-8859-1") {
                $parser = xml_parser_create();
            } else {

                #if(function_exists('mb_convert_encoding')) {
					if(trim($this->charset)!="")
						$encoded_source = $this->iconvert($mapcharset[$this->charset],"UTF-8",$source);
					else
						$encoded_source = $this->iconvert($mapcharset[strtolower($encoding)],"UTF-8",$source);
               # }

                if($encoded_source != NULL) {
                    $source = str_replace ( $m[0],'<?xml version="1.0" encoding="utf-8"?>', $encoded_source);
                }

                $parser = xml_parser_create();
            }

            xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
            xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
            xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
            if (!xml_parse_into_struct($parser, implode("", file($this->path . $this->name . ".xml")), $values)) {
                die(sprintf("XML error: %s at ".$this->path . $this->name.".xml in line %d",
                xml_error_string(xml_get_error_code($parser)),
                xml_get_current_line_number($parser)));
            }
            xml_parser_free($parser);

            
            foreach($values as $key => $value) {
                $tag = strtolower($value['tag']);
                switch ($tag) {
                    case 'name':
                    $this->title = $value['value'];
                    $this->lang = strtolower($value['value']);
                    break;
                    case 'author':
                    case 'creationdate':
                    case 'copyright':
                    case 'license':
                    case 'authoremail':
                    case 'authorurl':
                    case 'version':
                    case 'description':
                    $this->$tag = $value['value'];
                    break;
                    case 'locale':
                    if ($value['type'] == 'open') {
                        foreach ($value['attributes'] as $k => $v)
                        $this->$k = $v;
                    }
                    break;
                    case 'date_format':
                    $this->date_format = $value['value'];
                    break;
                    case 'plural_form':
                    if (!empty($value['attributes']['expression'])) {
                        $this->plural_form = $value['attributes'];
                    }
                    break;
                    case 'charset':
                    $this->codesets[] = $value['value'];
                    break;
                    case 'days':
                    $this->days = $value['attributes'];
                    break;
                    case 'months':
                    $this->months = $value['attributes'];
                    break;
                    case 'filename':
                    if ($load_catalogs) {
                        $file = $value['attributes'];
                        $file['filename'] = $value['value'];
                        $this->files[] = $file;
                    }
                    break;
                }
            }
            $this->codesets = array_unique($this->codesets);
            if( class_exists('ConvertCharset') || function_exists('iconv') )
            {
                if(strtolower($encoding)!="utf-8")
                {
                    $attrs = get_object_vars($this);
					$this->arrayEncoding($attrs,$mapcharset[$this->charset]);
					$this->bindAttributes($attrs);
                }
            }
            return true;
        }
        return false;
    }
    function iconvert($fromcharset,$tocharset,$source,$useiconv=FALSE)
    {
		if(strtolower($fromcharset)==strtolower($tocharset)) 
			return $source;
        if($useiconv)
        {
            if(function_exists('iconv'))
            {
                return iconv($fromcharset,$tocharset,$source);
            }else
            {
                return FALSE;
            }
        }
        $NewEncoding = new ConvertCharset();
        return $NewEncoding->Convert($source,$fromcharset,$tocharset,false);
    }
    function bindAttributes($attrs)
    {
        if(!is_array($attrs))
            return false;
        foreach($attrs as $key=>$v)
        {
            $this->$key = $v;
        }
    }
    function arrayEncoding(&$attrs,$encoding,$useiconv=FALSE)
    {
        if(is_array($attrs))
        {
            foreach($attrs as $key=>$val)
            {
                if(is_array($val))
                    $this->arrayEncoding($attrs[$key],$encoding,$useiconv);
                else
                {
                    $attrs[$key] = $this->iconvert("utf-8",$encoding,$val,$useiconv);
                }
            }
        }
    }

    function updateFiles() {
        $dir = $this->path . $this->name . '/';
        $langfiles = mosReadDirectory($dir, '.po$');
        set_time_limit(60);

        foreach ($langfiles as $lf) {
            $domain = substr($lf, 0, -3);
            $catalog = new PHPGettext_Catalog($domain, $this->path);
            $catalog->setproperty('lang', $this->name);
            $catalog->setproperty('mode', _MODE_PO_);
            $catalog->load();
            $file['filename'] = "language/" . $this->name . '/' . $lf;
            $file['domain'] = $domain;
            $file['strings'] = count($catalog->strings);
            $file['percent'] = '';
            $file['translated'] = 0;
            $file['fuzzy'] = 0;
            $file['filetype'] = 'po';
            $pluralfuzz = false;
            foreach ($catalog->strings as $msg) {
                if (is_array($msg->msgstr)) {
                    foreach ($msg->msgstr as $i) {
                        $unt = empty($i);
                    }
                    if (!$unt) {
                        $file['translated']++;
                    }
                }
                if (!is_array($msg->msgstr) && !empty($msg->msgstr) && !$msg->is_fuzzy) {
                    $file['translated']++;
                }
                if ($msg->is_fuzzy) {
                    $file['fuzzy']++;
                }
            }


            $nonfuzzy = $file['strings'] - $file['fuzzy'];
            if (!$nonfuzzy) $nonfuzzy = 1;
            $file['percent'] = round($file['translated'] * 100 / $nonfuzzy, 2);
            unset($nonfuzzy);
            $this->files[] = $file;
        }
        $this->files[] = array('filename'=>"language/" . $this->name . '.xml','domain'=>"",'strings'=>"",'percent'=>"",'translated'=>0,'fuzzy'=>0,'filetype'=>'xml');
        $langfiles = mosReadDirectory($dir.'LC_MESSAGES/', '.mo$');
        set_time_limit(60);

        foreach ($langfiles as $lf) {
            $this->files[] = array('filename'=>"language/" . $this->name . '/LC_MESSAGES/' . $lf,'domain'=>"",'strings'=>"",'percent'=>"",'translated'=>0,'fuzzy'=>0,'filetype'=>'mo');
        }
        if(file_exists($this->path.'/glossary/'.  $this->name.".".$this->charset.".po"))
        $this->files[] = array('filename'=>"language/glossary/" . $this->name.".".$this->charset.".po",'domain'=>"",'strings'=>"",'percent'=>"",'translated'=>0,'fuzzy'=>0,'filetype'=>'gl');
    }

    function toXML() {
        $array[] = array('tag' => 'mosinstall', 'type' => 'open', 'level' => 1, 'attributes' => array('version' => '4.6', 'type' => 'language'));
        $array[] = array('tag' => 'name', 'type' => 'complete', 'level' => 2, 'value' => $this->title);
        $array[] = array('tag' => 'version', 'type' => 'complete', 'level' => 2, 'value' => $this->version);
        $array[] = array('tag' => 'description', 'type' => 'complete', 'level' => 2, 'value' => $this->description);
        $array[] = array('tag' => 'creationdate', 'type' => 'complete', 'level' => 2, 'value' => $this->creationdate);
        $array[] = array('tag' => 'author', 'type' => 'complete', 'level' => 2, 'value' => $this->author);
        $array[] = array('tag' => 'authorurl', 'type' => 'complete', 'level' => 2, 'value' => $this->authorurl);
        $array[] = array('tag' => 'authoremail', 'type' => 'complete', 'level' => 2, 'value' => $this->authoremail);
        $array[] = array('tag' => 'copyright', 'type' => 'complete', 'level' => 2, 'value' => $this->copyright);
        $array[] = array('tag' => 'license', 'type' => 'complete', 'level' => 2, 'value' => $this->license);
        $array[] = array('tag' => 'params', 'type' => 'open', 'level' => 2);
        $array[] = array('tag' => 'param', 'type' => 'complete', 'level' => 3, 'attributes' => array('name' => 'locale', 'type' => 'text', 'default' => $this->locale, 'label' => 'Locale String', 'description' => 'Locale string for setlocale() (eg. en, english)'));
        $array[] = array('tag' => 'param', 'type' => 'complete', 'level' => 3, 'attributes' => array('name' => 'charset', 'type' => 'text', 'default' => $this->charset, 'label' => 'Character Set', 'description' => 'Character set for this language.'));
        $array[] = array('tag' => 'param', 'type' => 'complete', 'level' => 3, 'attributes' => array('name' => 'text_direction', 'type' => 'text', 'default' => $this->text_direction, 'label' => 'Text Direction', 'description' => 'left-to-right or right-to-left'));
        $array[] = array('tag' => 'param', 'type' => 'complete', 'level' => 3, 'attributes' => array('name' => 'date_format', 'type' => 'text', 'default' => $this->date_format, 'label' => 'Date Format', 'description' => 'Date format for strftime() (eg. %A, %d %B %Y)'));
        $array[] = array('tag' => 'param', 'type' => 'complete', 'level' => 3, 'attributes' => array('name' => 'plural_form', 'type' => 'text', 'default' => htmlentities($this->plural_form['expression']), 'label' => 'Plural Forms', 'description' => 'Plural Forms expression'));
        $array[] = array('tag' => 'params', 'type' => 'close', 'level' => 2);
        $array[] = array('tag' => 'locale', 'type' => 'open', 'level' => 2, 'attributes' => array('name' => $this->name, 'title' => $this->title, 'territory' => $this->territory, 'locale' => $this->locale, 'text_direction' => $this->text_direction, 'iso639' => $this->iso639, 'iso3166_2' => $this->iso3166_2, 'iso3166_3' => $this->iso3166_3, 'charset' => $this->charset));
        $array[] = array('tag' => 'plural_form', 'type' => 'complete', 'level' => 3, 'attributes' => array('nplurals' => $this->plural_form['nplurals'] , 'plural' => htmlentities($this->plural_form['plural']), 'expression' => htmlentities($this->plural_form['expression'])));
        $array[] = array('tag' => 'date_format', 'type' => 'complete', 'level' => 3, 'value' => $this->date_format);
        $array[] = array('tag' => 'codesets', 'type' => 'open', 'level' => 3);
        foreach ($this->codesets as $charset) $array[] = array('tag' => 'charset', 'type' => 'complete', 'level' => 4, 'value' => $charset);
        $array[] = array('tag' => 'codesets', 'type' => 'close', 'level' => 3);
        foreach ($this->days as $name => $day) $days[$name] = $day;
        $array[] = array('tag' => 'days', 'type' => 'complete', 'level' => 3, 'attributes' => $days);
        foreach ($this->months as $name => $month) $months[$name] = $month;
        $array[] = array('tag' => 'months', 'type' => 'complete', 'level' => 3, 'attributes' => $months);
        $array[] = array('tag' => 'locale', 'type' => 'close', 'level' => 2);
        $array[] = array('tag' => 'files', 'type' => 'open', 'level' => 2);
        foreach ($this->files as $file) {
            $array[] = array('tag' => 'filename', 'type' => 'complete', 'level' => 3, 'value' => $file['filename'], 'attributes' => array('domain' => $file['domain'] , 'strings' => $file['strings'] , 'translated' => $file['translated'] , 'fuzzy' => $file['fuzzy'] , 'percent' => $file['percent'], 'filetype' => $file['filetype']));
        }
        $array[] = array('tag' => 'files', 'type' => 'close', 'level' => 2);
        $array[] = array('tag' => 'mosinstall', 'type' => 'close', 'level' => 1);

        $xml = "<?xml version=\"1.0\" encoding=\"$this->charset\"?>\n";
        if ((!empty($array)) AND (is_array($array))) {
            foreach ($array as $key => $value) {
                switch ($value["type"]) {
                    case "open":
                    $xml .= str_repeat("\t", $value["level"] - 1);
                    $xml .= "<" . strtolower($value["tag"]);
                    if (isset($value["attributes"])) {
                        foreach ($value["attributes"] as $k => $v) {
                            $xml .= sprintf(' %s="%s"', strtolower($k), $v);
                        }
                    }
                    $xml .= ">\n";
                    break;
                    case "complete":
                    $xml .= str_repeat("\t", $value["level"] - 1);
                    $xml .= "<" . strtolower($value["tag"]);
                    if (isset($value["attributes"])) {
                        foreach ($value["attributes"] as $k => $v) {
                            $xml .= sprintf(' %s="%s"', strtolower($k), $v);
                        }
                    }
                    $xml .= ">";
                    $xml .= isset($value['value']) ? $value['value'] : false;
                    $xml .= "</" . strtolower($value["tag"]) . ">\n";
                    break;
                    case "close":
                    $xml .= str_repeat("\t", $value["level"] - 1);
                    $xml .= "</" . strtolower($value["tag"]) . ">\n";
                    break;
                    default:
                    break;
                }
            }
        }
        return $xml;
    }

    function getLocales() {
        $xmlfile = "../language/locales.xml";
        $p = xml_parser_create();
        xml_parser_set_option($p, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($p, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($p, implode("", file($xmlfile)), $values);
        xml_parser_free($p);
        $locales = array();
        foreach($values as $key => $value) {
            switch ($value['tag']) {
                case 'locale':
                if ($value['type'] == 'open') {
                    $iso639 = $value['attributes']['iso639'];
                    $language[$iso639] = $value['attributes']['title'];
                    $locale[$iso639] = $value['attributes'];
                    $directions[$iso639] = $value['attributes']['text_direction'];
                }
                break;
                case 'territory':
                $t['iso3166_2'] = $value['attributes']['iso3166_2'];
                $t['iso3166_3'] = $value['attributes']['iso3166_3'];
                $t['territory'] = $value['value'];
                $territories[$iso639][] = $t;
                break;
                case 'charset':
                $locale[$iso639]['codesets'][] = $codesets[$iso639][] = $value['value'];
                break;
                case 'date_format':
                $locale[$iso639]['dateformats'] = $dateformats[$iso639] = $value['value'];
                break;
                case 'days':
                $locale[$iso639]['days'] = $value['attributes'];
                break;
                case 'months':
                $locale[$iso639]['months'] = $value['attributes'];
                break;
                case 'plural_form':
                $exp = '';
                if (!empty($value['attributes']['expression'])) {
                    $locale[$iso639]['plural_form'] = $value['attributes'];
                    $plural_forms[$iso639] = $value['attributes']['expression'];
                }
                break;
            }
        }
        $locales['locales'] = $locale;
        $locales['languages'] = $language;
        $locales['territories'] = $territories;
        $locales['codesets'] = $codesets;
        $locales['dateformats'] = $dateformats;
        $locales['directions'] = $directions;
        $locales['plural_forms'] = $plural_forms;
        return $locales;
    }

    function getSystemLocale(){
        if (substr(strtoupper(PHP_OS), 0, 3) == 'WIN'){
            return strtolower($this->title).($this->iso3166_3?'_'.strtolower($this->iso3166_3):'');
        } else {
            return $this->locale;
        }
    }
}

function getlocales() {
    return mamboLanguage::getLocales();
}
?>