<?php
/**
 * @version		0.9
 * @author      Carlos Souza
 * @copyright   Copyright (c) 2005 Carlos Souza <csouza@web-sense.net>
 * @package     PHPGettext
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link		http://phpgettext.web-sense.net
 *
 *
 */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
class PHPGettext
{
    var $has_gettext;

    /**
     * The current locale. eg: en-GB
     */
    var $lang;
    /**
     * The current locale. eg: en-GB
     */
    var $locale;

    /**
     * The current domain
     */
    var $domain;

    /**
     * The current character set
     */
    var $charset;

    /**
     * Container for the loaded domains
     */
    var $text_domains = array();

    /**
     * The asssociative array of headers for the current domain
     */
    var $headers = array();
    /**
     * The asssociative array of messages for the current domain
     */
    var $messages = array();

    /**
     * The debugging flag
     */
    var $debug;

    function PHPGettext() {}

    /**
     *
     * Set and lookup the locale from the environment variables.
     * Priority order for gettext is:
     * 1. LANGUAGE
     * 2. LC_ALL
     * 3. LC_MESSAGE
     * 4. LANG
     *
     * @return unknown
     */

    function setVariable($variable){
        if ($this->has_gettext){
            $this->has_gettext = $this->has_gettext && @putenv($variable);
        }
    }
    
    function setlocale($lang, $locale=false) {
        $this->setvariable("LANGUAGE=$lang");
        $this->setvariable("LC_ALL=$lang");
        $this->setvariable("LC_MESSAGE=$lang");
        $this->setvariable("LANG=$lang");
        $this->lang =  $lang;
        if ($locale) {
            $this->locale =  setlocale(LC_ALL,$locale);
        }
    }

    function getlocale() {
        if (empty($this->locale)) {
            $langs = array( getenv('LANGUAGE'),
            getenv('LC_ALL'),
            getenv('LC_MESSAGE'),
            getenv('LANG')
            );
            foreach ($langs as $lang)
            if ($lang){
                $this->locale = $lang;
                break;
            }
        }
        return $this->locale;
    }

    /**
     * debugging function
     *
     */
    function output($message, $untranslated = false){
        switch ($this->debug)
        {
            case 2:
            $trace = debug_backtrace();
            $html = '<span style="border-bottom: thin solid %s" title="%s(%d)">T_(%s)</span>';
            $str = sprintf($html, ($untranslated ? 'red' : 'green'), str_replace('\\', '/', $trace[2]['file']), $trace[2]['line'], $message);
            break;
            case 1:
            $str    = sprintf('%sT_(%s)',$untranslated ? '!' : '', $message);
            break;
            case 0:
            default:
            $str    = $message;
            break;
        }
        return $str;
    }

    /**
     * Alias for gettext
     * will also output the result if $output = true
     */
    function _($message, $output = false){
        $return = $this->gettext($message);
        if ($output) {
            echo $return;
            return true;
        }
        return $return;
    }

    /**
     * Lookup a message in the current domain
     * returns translation if it exists or original message
     */
    function gettext($message){
        $translation = $message;
        if ($this->has_gettext){
            $translation = gettext($message);
        }
        else{
           $message = addslashes($message);
           $message = str_replace("\n", '\n', $message);
           $message = str_replace("\r", '\r', $message);
           if (isset($this->messages[$this->domain][$message])) {
            $translation = !empty($this->messages[$this->domain][$message]) ? $this->messages[$this->domain][$message] : $message;
            }
        }
        $untranslated = (strcmp($translation, $message) === 0) ? true : false;
        return $this->output($translation, $untranslated);
    }

    /**
     * Override the current domain
     * The dgettext() function allows you to override the current domain for a single message lookup.
     */
    function dgettext($domain, $message){
        $translation = $message;
        if (array_key_exists($domain, $this->messages)){
            if (isset($this->messages[$domain][$message]))
            $translation = !empty($this->messages[$domain][$message]) ? $this->messages[$domain][$message] : $message;
        }
        $untranslated = (strcmp($translation, $message) === 0) ? true : false;
        return $this->output($translation, $untranslated);
    }

    /**
     * Plural version of gettext
     */
    function ngettext($msgid, $msgid_plural, $count){
        if ($this->has_gettext){
            $translation = ngettext($msgid, $msgid_plural, $count);
        }else{
            $msgid = addslashes($msgid);
            $msgid = str_replace("\n", '\n', $msgid);
            $msgid = str_replace("\r", '\r', $msgid);
            $plural = $this->getplural($count, $this->domain);
            $original = array($msgid, $msgid_plural);
            if (isset($this->messages[$this->domain][$msgid][$plural])) {
                $translation  = $this->messages[$this->domain][$msgid][$plural];
            } else {
                $original   = array($msgid, $msgid_plural);
                $translation  = $original[$plural];
            }
        }
        $untranslated = isset($original[$plural]) && (strcmp($translation, $original[$plural]) === 0) ? true : false;
        return $this->output($translation, $untranslated);
    }
    /**
     * Plural version of dgettext
     */
    function dngettext($domain, $msgid, $msgid_plural, $count){
        $original   = array($msgid, $msgid_plural);
        $plural = $this->getplural($count, $domain);
        if ($this->has_gettext){
            $translation = dngettext($domain, $msgid, $msgid_plural, $count);
        } else {
            $msgid = addslashes($msgid);
            $msgid = str_replace("\n", '\n', $msgid);
            $msgid = str_replace("\r", '\r', $msgid);
            if (isset($this->messages[$domain][$msgid][$plural])) {
                $translation  = $this->messages[$domain][$msgid][$plural];
            } else {
                $translation  = $original[$plural];
            }
        }
        $untranslated = (strcmp($translation, $original[$plural]) === 0) ? true : false;
        return $this->output($translation, $untranslated);
    }

    /**
     * Specify the character encoding in which the messages
     * from the DOMAIN message catalog will be returned
     *
     */
    function bind_textdomain_codeset($domain, $charset){
        if ($this->has_gettext){
            bind_textdomain_codeset($domain, $charset);
        }
        return $this->text_domains[$domain]["charset"] = $charset;
    }

    /**
     * Sets the path for a domain
     * if gettext is unavailable, translation files will be loaded here
     *
     */
    function bindtextdomain($domain, $path){
        if ($this->has_gettext){
            bindtextdomain($domain, $path);
        } else {
            $this->load($domain, $path);
        }
        return $this->text_domains[$domain]["path"] = $path;
    }

    /**
     * Sets the default domain textdomain
     */
    function textdomain($domain = null){
        if ($this->has_gettext) {
          $this->domain = textdomain($domain);
        }
        elseif (!is_null($domain)) {
            $this->domain = $domain;
            $this->load($domain, $this->text_domains[$this->domain]['path']);
        }
        return $this->domain;
    }

    /**
     * Overrides the domain for a single lookup
     * This function allows you to override the current domain for a single message lookup.
     * It also allows you to specify a category.
     * Categories are folders within the languages directory  .
     * currently, only LC_MESSAGES is implemented
     *
     *   The values for categories are:
     *   LC_CTYPE        0
     *   LC_NUMERIC      1
     *   LC_TIME         2
     *   LC_COLLATE      3
     *   LC_MONETARY     4
     *   LC_MESSAGES     5
     *   LC_ALL          6
     *
     *   not yet implemented
     */
    function dcgettext($domain, $message, $category){
        return $message;
    }

    /**
     * dcngettext -- Plural version of dcgettext
     * not yet implemented
     */
    function dcngettext($domain, $msg1, $msg2, $count, $category){
        return $msg1;
    }


    /**
     * Plural-Forms: nplurals=2; plural=n == 1 ? 0 : 1;
     *
     * nplurals - total number of plurals
     * plural   - the plural index
     *
     * Plural-Forms: nplurals=1; plural=0;
     * 1 form only
     *
     * Plural-Forms: nplurals=2; plural=n == 1 ? 0 : 1;
     * Plural-Forms: nplurals=2; plural=n != 1;
     * 2 forms, singular used for one only
     *
     * Plural-Forms: nplurals=2; plural=n>1;
     * 2 forms, singular used for zero and one
     *
     * Plural-Forms: nplurals=3; plural=n%10==1 && n%100!=11 ? 0 : n != 0 ? 1 : 2;
     * 3 forms, special case for zero
     *
     * Plural-Forms: nplurals=3; plural=n==1 ? 0 : n==2 ? 1 : 2;
     * 3 forms, special cases for one and two
     *
     * Plural-Forms: nplurals=4; plural=n%100==1 ? 0 : n%100==2 ? 1 : n%100==3 || n%100==4 ? 2 : 3;
     * 4 forms, special case for one and all numbers ending in 02, 03, or 04
     */
    function getplural($count, $domain) {
        if (isset($this->headers[$domain]['Plural-Forms'])) {
            $plural_forms = $this->headers[$domain]['Plural-Forms'];
            preg_match('/nplurals[\s]*[=]{1}[\s]*([\d]+);[\s]*plural[\s]*[=]{1}[\s]*(.*);/', $plural_forms, $matches);
            $nplurals   = $matches[1];
            $plural_exp = $matches[2];
            if ($nplurals > 1 && strpos($plural_exp, ':') === false) {
                $plural =  'return ('.preg_replace('/n/', $count, $plural_exp).') ? 1 : 0;';
            } else {
                $plural = 'return '.preg_replace('/n/', $count, $plural_exp).';';
            }
        } else {
            $plural = 'return '.preg_replace('/n/', $count, 'n != 1 ? 1 : 0;');
        }
        return eval($plural);
    }

    function load($domain, $path) {
        $root = dirname(__FILE__);
        require_once($root.'/phpgettext.catalog.php');
        $catalog = new PHPGettext_catalog($domain, $path);
        $catalog->setproperty('mode', _MODE_MO_);
        $catalog->setproperty('lang', $this->lang);
        $catalog->load();
        $this->headers[$domain] = $catalog->headers;
        foreach ($catalog->strings as $string)
        $this->messages[$domain][$string->msgid] = $string->msgstr;
    }
    //Thank you - Inicio Agregado Andres Felipe Vargas
    function add($domain) {
        $catalog = new PHPGettext_catalog($domain, $this->text_domains[$this->domain]["path"]);
        $catalog->setproperty('mode', _MODE_MO_);
        $catalog->setproperty('lang', $this->lang);
        $catalog->load();
        foreach ($catalog->strings as $string)
        $this->messages[$this->domain][$string->msgid] = $string->msgstr;
    }
    //end Inicio Agregado Andres Felipe Vargas

}


class PHPGettextAdmin
{
    var $has_gettext    = false;
    var $gettext_path   = '';
    var $debug          = false;
    var $is_windows     = false;

    function PHPGettextAdmin($debug = false)
    {
        if (ini_get('open_basedir') || ini_get('safe_mode') || strstr(ini_get("disable_functions"),"exec")) {
            $this->has_gettext = false;
            return false;
        }
        
        if (substr(strtoupper(PHP_OS), 0, 3) == 'WIN'){
            $this->is_windows = true;
        }
        
        $cmd = 'xgettext --help';
        exec($cmd, $output, $return);
        if ($output) {
            $this->has_gettext = true;
        }
        if ($debug) {
            $this->debug = $debug;
        }
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $domain
     * @param unknown_type $langdir
     */
    function message_format ($domain, $textdomain, $lang, $enc='utf-8')
    {
        $path   = "$textdomain/$lang";
        if ($this->has_gettext) {
            $cmd = $this->escCommand("msgfmt")." -o ".$this->escPath("{$path}/LC_MESSAGES/{$domain}.mo")." ".$this->escPath("{$path}/{$domain}.po");
            return $this->execute($cmd);
        }
        $catalog = new PHPGettext_catalog($domain, $textdomain);
        $catalog->setproperty('mode', _MODE_PO_);
        $catalog->setproperty('lang', $lang);
        $catalog->load();
        $catalog->setproperty('mode', _MODE_MO_);
        $catalog->save();
        return true;
    }
    /**
     * Enter description here...
     *
     * @param unknown_type $domain
     * @param unknown_type $langdir
     */
    function compile($lang, $textdomain, $enc='utf-8') {
        if (!is_dir("$textdomain/$lang/LC_MESSAGES")) {
            mkdir("$textdomain/$lang/LC_MESSAGES/");
        }
        $catalog = new PHPGettext_catalog($lang, $textdomain);
        $catalog->setproperty('mode', _MODE_PO_);
        $catalog->setproperty('lang', $lang);
        $headers = $this->header();
        $catalog->setproperty('comments', $headers[0]);
        $catalog->setproperty('headers', $headers[1]);
        $catalog->load();
        $d = dir($textdomain."/".$lang);
        while (false !== ($file = $d->read())){
           if (preg_match('/.po$/', $file)){
                list($file,$ext) = explode(".",$file);
                $catalog_aux = new PHPGettext_catalog($file, $textdomain);
                $catalog_aux->setproperty('mode', _MODE_PO_);
                $catalog_aux->setproperty('lang', $lang);
                $catalog_aux->load();
                foreach ($catalog_aux->strings as $msgid => $string){
                   if (!$string->is_fuzzy){
                        if (is_array($string->msgstr) ){
                            if(in_array("",$string->msgstr)){
                                continue;
                            }
                        } else if (!$string->msgstr){
                            continue;
                        }
                       $catalog->addentry($string->msgid, $string->msgid_plural,$string->msgstr, $string->comments );
                   }
                }
           }
        }
        $catalog->setproperty('mode', _MODE_MO_);
        $catalog->save();
        return true;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $domain
     * @param unknown_type $langdir
     */
    function initialize_translation ($domain, $textdomain, $lang, $enc='utf-8')
    {
        if (!$this->has_gettext) {
            return false;
        }
        set_time_limit(120);
        $path   = "$textdomain/$lang";
        copy("$textdomain/untranslated/$domain.pot", "$path/ref.po");
        $cmd = $this->escCommand("msgmerge")." --width=80 --compendium ".$this->escPath("{$textdomain}/glossary/{$lang}.{$enc}.po")." -o ".$this->escPath("{$path}/{$domain}.po")." ".$this->escPath("{$path}/ref.po")." ".$this->escPath("{$textdomain}/untranslated/{$domain}.pot");
        $this->execute($cmd);
        unlink("$textdomain/$lang/ref.po");
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $domain
     * @param unknown_type $langdir
     */
    
    function update_translation($domain, $textdomain, $lang, $enc='utf-8')
    {
        if (!file_exists("$textdomain/glossary/$lang.$enc.po")) return false;
        $catalog_aux = new PHPGettext_catalog($lang.".".$enc, $textdomain);
        $catalog_aux->setproperty('mode', _MODE_GLO_);
        $catalog_aux->setproperty('lang', $lang);
        $catalog_aux->load();
        foreach ($catalog_aux->strings as $msgid => $string){
           if (!$string->is_fuzzy){
              $trans[$string->msgid] = $string->msgstr;
           }
        }
        $catalog = new PHPGettext_catalog($domain, $textdomain);
        $catalog->setproperty('mode', _MODE_PO_);
        $catalog->setproperty('lang', $lang);
        $catalog->load();
		global $mapcharset;
		$charsets = explode("=",$catalog->headers["Content-Type"]);
		$codecharset = str_replace("\\n","",strtolower($charsets[1]));
		$NewEncoding = new ConvertCharset();
		foreach ($trans as $key => $tran){
			   if(trim($mapcharset[$codecharset])!="utf-8")
					$trans[$key] = $NewEncoding->Convert($tran,"utf-8",trim($mapcharset[$codecharset]),false);
        }
        $catalog->translate($trans );
        $catalog->save();
        return true;
    }
    
    /**
     * Enter description here...
     *
     * @param unknown_type $domain
     * @param unknown_type $langdir
     */

    function add_to_dict($domain, $textdomain, $lang, $enc='utf-8') {
        $textdomain = rtrim($textdomain, '\/');
        $path = "$textdomain/$lang";

        if (!is_dir("$textdomain/glossary/")) {
            mkdir("$textdomain/glossary/");
        }
        
        $catalog = new PHPGettext_catalog($domain, $textdomain);
        $catalog->setproperty('mode', _MODE_PO_);
        $catalog->setproperty('lang', $lang);
        $catalog->setproperty('charset', $enc);
        $catalog->load();

        foreach ($catalog->strings as $msgid => $string){
           if (!$string->is_fuzzy){
              if (is_array($string->msgstr) ){
                  if(in_array("",$string->msgstr)){
                  continue;
                  }
              } else if (!$string->msgstr){
                  continue;
              }
              $new[$string->msgid] = $string;
           }
        }

        $glossary = new PHPGettext_catalog($lang.".".$enc, $textdomain);
        $glossary->setproperty('mode', _MODE_GLO_);
        $glossary->setproperty('lang', $lang);        
        if (!file_exists("$textdomain/glossary/$lang.$enc.po")) {
            $headers = $this->header();
            $glossary->setproperty('comments', $headers[0]);
            $glossary->setproperty('headers', $headers[1]);
            $glossary->save();
        }else{
            $glossary->load();
        }
        
        $glossary->merge($new );
        $glossary->save();
        
        $language = new mamboLanguage($lang);
        $language->save();
        return true;
    }
    /**
     * Enter description here...
     *
     * @param unknown_type $domain
     * @param unknown_type $langdir
     */
    function convert_charset($domain, $textdomain, $lang, $from_charset, $to_charset) {

        $path = "$textdomain/$lang";
        if ($this->has_gettext) {
            $cmd = $this->escCommand("msgconv")." --to-code=$to_charset -o ".$this->escPath("{$path}/{$domain}.po")." ".$this->escPath("{$path}/{$domain}.po");
            $ret = $this->execute($cmd);
            return $ret;
        }

        if (!class_exists('ConvertCharset')) {
            return false;
        }

        $catalog = new PHPGettext_catalog($domain, $textdomain);
        $catalog->setproperty('mode', _MODE_PO_);
        $catalog->setproperty('lang', $lang);
        $catalog->load();
        $catalog->headers['Content-Type'] = "text/plain; charset=$to_charset\n";
        $NewEncoding = new ConvertCharset();
		foreach ($catalog->strings as $index => $message) {
            if (empty($message->msgid_plural)) {
				$catalog->strings[$index]->msgstr = $NewEncoding->Convert($message->msgstr,$from_charset,$to_charset,false);
            }
        }
        $catalog->save();
        return true;
    }


    /**
     * Invoke the xgettext utility with $args
     * the xgettext executable must be in PATH
     *
     * @param string the commandline arguments to gettext
     * @return unknown
     */
    function xgettext($domain, $textdomain, $php_sources, $lang='untranslated') {
        $path = mamboCore::get('rootPath');
        $n=count($php_sources);
        if (!$n) return false;
        $cmd  = $this->escCommand("xgettext");
        if (file_exists("$textdomain/$lang/$domain.pot")) $cmd  .= ' -j ';
        $cmd  .= " -n -c --sort-by-file --keyword=T_ --keyword=Tn_:1,2 --keyword=Td_:2 --keyword=Tdn_:2,3 --output-dir=".$this->escPath("{$textdomain}/{$lang}")." -o {$domain}.pot";
        if (count($php_sources) > 10) {
            $tmp_name = substr(uniqid(rand()),0,8).".txt";
            $tmpfile = $path."/media/$tmp_name";
            $fp = fopen($tmpfile, "w");
            fwrite($fp, implode("\r\n", $php_sources));
            fclose($fp);
            $cmd = $cmd." --files-from=".$this->escPath($tmpfile);
        } else {
            for($i=0;$i<$n;$i++){ 
                $php_sources[$i] = $this->escPath($php_sources[$i]);
            }
            $cmd = $cmd." ".implode(" ", $php_sources);
        }
        $ret = $this->execute($cmd);
        @unlink($tmpfile);

        return $ret;
    }

    
    /**
     * Enter description here...
     *
     * @param unknown_type $domain
     * @param unknown_type $langdir
     */
    function execute($cmd) {

        if (!$this || !$this->has_gettext) return false;

        $lastline = exec($cmd, $output, $retval);
        if ($this->debug || $retval > 0) {
            $trace = debug_backtrace();
            $msg = "<div style=\"border: thin solid silver; text-align:left;margin-left:10px\">";
            $msg .= "<p><span style=\"font-weight:bold\">PHPGettextAdmin->execute('</span><i>$cmd</i><span style=\"font-weight:bold\">')</span><br />in ".str_replace($_SERVER['DOCUMENT_ROOT'], '', $trace[1]['file']).":{$trace[1]['line']}</p>";
            $msg .= "<p><em><strong>trace:</strong></em></p><ul>";
            for ($a=1; $a < 3; $a++)  {
                $called = isset($trace[$a]['class']) ? $trace[$a]['class'] : '';
                $called .= $trace[$a]['type'].$trace[$a]['function']."(".@implode(', ',$trace[$a]['args']).")";
                $msg .= "<li>$called in ".str_replace($_SERVER['DOCUMENT_ROOT'], '', $trace[$a]['file'])." : {$trace[$a]['line']}</li>";
            }
            $msg .= "</ul>";
            $msg .= "<p><em><strong>return value:</strong></em>  $retval</p>";
            $msg .= "<p><em><strong>output:</strong></em> </p><pre>".implode("<br />",$output)."</pre>";
            $msg .= "</div>";
            echo $msg;
        }
        return $retval == 0;
    }
    
    function escCommand($command){
        if ($this->is_windows){
           $command = "call \"{$command}\"";
        }
        return $command;
    }

    function escPath($path){
        if ($this->is_windows){
           $path = "\"".str_replace("/","\\",$path)."\"";
        }
        return $path;
    }

    function header($charset='utf-8', $plurals='nplurals=2; plural=n == 1 ? 0 : 1;'){
        $year = date('Y');
        $comments = <<<EOT
# Mambo
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

/**
 * Enter description here...
 *
 * @return unknown
 */
function &phpgettext(){
    static $gettext;
    $root = dirname(__FILE__);
    if (is_null($gettext)) {
        require_once($root.'/phpgettext.class.php');
        $gettext = new PHPGettext();
        $gettext->has_gettext = true;
        if (!function_exists("gettext") || !function_exists("_")) {
            $gettext->has_gettext = false;
            require_once($root.'/phpgettext.compat.php');
        }
    }
    return $gettext;
}
function T_($message) {
    $gettext =& phpgettext();
    $trans = $gettext->gettext($message);
    return $trans;
}
function Tn_($msg1, $msg2, $count) {
    $gettext =& phpgettext();
    return $gettext->ngettext($msg1, $msg2, $count);
}
function Td_($domain, $message) {
    $gettext =& phpgettext();
    return $gettext->dgettext($domain, $message);
}
function Tdn_($domain, $msg1, $msg2, $count) {
    $gettext =& phpgettext();
    return $gettext->dngettext($domain, $msg1, $msg2, $count);
}
?>