<?php
/**
* @package Mambo
* @author Mambo Foundation Inc see README.php
* @copyright Mambo Foundation Inc.
* See COPYRIGHT.php for copyright notices and details.
* @license GNU/GPL Version 2, see LICENSE.php
* Mambo is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; version 2 of the License.
*/

class mosSEF {
    
    var $prefix = '';
    var $custom_code = array();
    var $custom_name = array();
    var $custom_PHP = array();
    var $custom_short = array();
    var $sef_content_task = array();
    var $sef_name_chars = array();
    var $sef_translate_chars = array();
    var $sef_name_string = '';
    var $sef_translate_string = '';
    var $content_data = null;
    var $content_items = array();
    var $content_sections = array();
    var $content_categories = array();
    var $SEF_SPACE;
    
    function mosSEF () {
        /*******************************************************************************
        **  The following are parameters for the ReMOSef Search Engine
        **  Optimisation component.  $this->SEF_SPACE should be set to the
        **  character that is to replace blanks in names that form the URL.
        **  You can vary this, although the only sensible choices seem to be
        **  underscore or hyphen (_ or -).
        **
        **  The arrays $this->custom_code and $this->custom_name must be kept exactly
        **  in step with each other.  $this->custom_code is a list of the components
        **  that are to be handled by ReMOSef.
        **
        **  The array $this->custom_name is the alternative name that will be used
        **  in the optimised URL to identify the component and can be whatever
        **  you please so long as it is unique and legal for a URL.  Apart from
        **  using the custom name for the component, ReMOSef will do no further
        **  translation of the URL than is done by the standard Mambo SEF - UNLESS
        **  there is a sef_ext.php file installed for that component.  The
        **  exception to this is ReMOSitory - the optimisation code for Remository
        **  is integrated in ReMOSef.  For other components, if a sef_ext.php is
        **  present, it will be invoked by ReMOSef.
        **
        **  The array $remosef_content_task is capable of translating the tasks
        **  understood by the Mambo content component.  Please DO NOT CHANGE what
        **  is to the left of the equals sign.  When putting different values on
        **  the right hand side, remember that they must all be different, and must
        **  also be different from any of the custom names used for components.
        **  Without that, Remosef cannot figure out what a SEF URL means and will
        **  give unpredictable results.
        **
        **  Note that the names in custom_name must not be allowed to clash with
        **  the names used as tasks by the content component, in their
        **  translated form - see below.
        *******************************************************************************/
        $this->SEF_SPACE = "_";                 // divide words with underscores
                                            // can be changed to a hyphen "-"
        $this->custom_code = array('com_frontpage','com_contact');
        $this->custom_name = array('Frontpage','Contact_Us');
        /*******************************************************************************
        **  The following are the parameters for the optional content specific
        **  URL optimisation.  They are not used within the standard SEF processing
        **  unless you add in a sef_ext.php that is integrated with the Mambo SEF.
        **
        **  The following two lines define the translations that SEF will perform on
        **  names of sections and categories when translating them for inclusion in a URL.
        **  Each item in $this->sef_name_chars is translated into the corresponding
        **  element of $this->sef_translate_chars.
        **
        **  NOTE it is important that space be the last translate character, since the
        **  characters are processed in the order in which they appear.  Since earlier
        **  translates may create new spaces, it is vital that the space translation is
        **  done last.
        **
        **  You can extend these arrays as you wish, although it is obviously important
        **  to make sure that the items of one match the items of the other exactly.
        *******************************************************************************/
        $this->sef_name_chars = array('?', '&', '/', ' ');
        $this->sef_translate_chars = array('', 'and', ' or ', $this->SEF_SPACE);
        $this->sef_name_string = '�\'';
        $this->sef_translate_string = '--';

/************** DO NOT MAKE CHANGES IN THE FOLLOWING LINES EXCEPT AT YOUR OWN RISK! ********************/

        if (mamboCore::is_set('mosConfig_sef_prefix')) $this->prefix = mamboCore::get('mosConfig_sef_prefix');
        else $this->prefix = 'mos';
        if ($this->prefix == 'content' OR $this->prefix == 'component') $this->prefix = 'mos';
        foreach ($this->custom_code as $code) {
            $codefile = "components/$code/sef_ext.php";
            if (file_exists($codefile)) {
                include ($codefile);
                $this->custom_PHP[] = true;
            }
            else $this->custom_PHP[] = false;
            $split = explode('_',$code);
            $this->custom_short[] = $split[1];
        }

        $this->sef_content_task['findkey'] = 'findkey';
        $this->sef_content_task['view'] = 'view';
        $this->sef_content_task['section'] = 'section';
        $this->sef_content_task['category'] = 'category';
        $this->sef_content_task['blogsection'] = 'blogsection';
        $this->sef_content_task['blogcategorymulti'] = 'blogcategorymulti';
        $this->sef_content_task['blogcategory'] = 'blogcategory';
        $this->sef_content_task['archivesection'] = 'archivesection';
        $this->sef_content_task['archivecategory'] = 'archivecategory';
        $this->sef_content_task['save'] = 'save';
        $this->sef_content_task['cancel'] = 'cancel';
        $this->sef_content_task['emailform'] = 'emailform';
        $this->sef_content_task['emailsend'] = 'emailsend';
        $this->sef_content_task['vote'] = 'vote';
        $this->sef_content_task['showblogsection'] = 'showblogsection';

    }
    
    function &getInstance () {
        static $instance;
        if (!is_object($instance)) $instance = new mosSEF();
        return $instance;
    }
    
    function nameForURL ($string) {
        $accented = "���������������������������������������������������������������������".$this->sef_name_string;
        $nonaccent = "SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy".$this->sef_translate_string;
        $string = strtr($string, $accented, $nonaccent);
        foreach ($this->sef_name_chars as $i=>$char) $string = str_replace($char, $this->sef_translate_chars[$i], $string);
        $string = urlencode($string);
        return $string;
    }
        

    function sefRetrieval($register_globals){
        
        if (preg_match('/(\b)GLOBALS|_REQUEST|_SERVER|_ENV|_COOKIE|_GET|_POST|_FILES|_SESSION(\b)/i', $_SERVER['REQUEST_URI']) > 0) {
            die('Invalid Request');
        }
        if (mamboCore::get('mosConfig_sef')) {
            $subdir = mamboCore::get('subdirectory');
            $uri = substr($_SERVER['REQUEST_URI'], strlen($subdir));
            $url_array = explode('/', $uri);
            /**
            * Content
            * /$option/$task/$sectionid/$id/$Itemid/$limit/$limitstart
            */
            if (!isset($url_array[2]) AND strpos($uri, '?')) return 0;
            elseif ($url_array[2] == 'syndstyle') {
                array_splice($url_array, 2, 1);
                $_REQUEST['syndstyle'] = $_GET['syndstyle'] = $syndstyle = 'yes';
                $QUERY_STRING = 'syndstyle=yes&';
            }
            else $QUERY_STRING = '';

            // language hook for content
            $lang = "";
            $parms = array();
            foreach($url_array as $key=>$value) {
                if ( strcasecmp(substr($value,0,5),'lang,') == 0 ) {
                    $parts = explode(",", $value);
                    if (count($parts) > 1) {
                        $lang = $_REQUEST['lang'] = $_GET['lang'] = $parts[1];
                    }
                }
                elseif ( $value != '' AND $key > 1 ) $parms[] = $value;
            }
            
            $foundit = false;
            if ($url_array[1] == 'content') {
                $num = count($parms);
                $foundit = true;
                $_REQUEST['option'] = $_GET['option'] = $option = 'com_content';
                if (!is_numeric($parms[$num-1])) {
                    $_REQUEST['lang'] = $_GET['lang'] = $lang = array_pop($parms);
                    $num--;
                }
                // $option/$task/$sectionid/$id/$Itemid/$limit/$limitstart/
                $task = array_search($parms[0], $this->sef_content_task);
                if ($task === false OR $task === null) return 1;
                $_REQUEST['task'] = $_GET['task'] = $task;
                $QUERY_STRING .= "option=com_content&task=$task";

                if ($task == 'archivecategory') {
                    $_REQUEST['year'] = $_GET['year'] = $year = intval(@$parms[1]);
                    $_REQUEST['month'] = $_GET['month'] = $month = intval(@$parms[2]);
                    $_REQUEST['module'] = $_GET['module'] = $module = intval(@$parms[3]);
                    $QUERY_STRING .= "&year=$year&month=$month&module=$module";
                }
                else {
                    for ($i = 1; $i <= $num-1; $i++) {
                        if (strcmp($parms[$i], (int)$parms[$i]) !== 0) return 1;
                    }
                                                
                    $i = 1;
                    if ($num == 6 OR $num == 4) {
                        $_REQUEST['sectionid'] = $_GET['sectionid'] = $sectionid = $parms[$i];
                        $QUERY_STRING .= "&sectionid=$sectionid";
                        $i++;
                    }
                    if ($num > 1) {
                        $_REQUEST['id'] = $_GET['id'] = $id = $parms[$i];
                        $QUERY_STRING .= "&id=$id";
                    }
                    if ($num > 2) {
                        $_REQUEST['Itemid'] = $_GET['Itemid'] = $Itemid = $parms[$i+1];
                        mamboCore::set('Itemid',$Itemid);
                        $QUERY_STRING .= "&Itemid=$Itemid";
                    }
                    if ($num > 4) {
                        $_REQUEST['limit'] = $_GET['limit'] = $limit = $parms[$i+2];
                        $_REQUEST['limitstart'] = $_GET['limitstart'] = $limitstart = $parms[$i+3];
                        $QUERY_STRING .= "&limit=$limit&limitstart=$limitstart";
                        $i+=2;
                    }
                }
                if ($lang!="") {
                    $QUERY_STRING .= "&lang=$lang";
                }
            }
                
            /*
            Components
            http://www.domain.com/component/$name,$value
            */
            elseif ($url_array[1] == 'component') {
                $QUERY_STRING .= $this->default_revert('component');
                if ($QUERY_STRING) $foundit = true;
            }
            elseif ($url_array[1] == $this->prefix) {
                $menuhandler =& mosMenuHandler::getInstance();
                foreach ($this->custom_name as $i=>$compname) {
                    if ($url_array[2] == $compname) {
                        $origname = $this->custom_code[$i];
                        $_REQUEST['Itemid'] = $_GET['Itemid'] = $Itemid = $menuhandler->getIDLikeLink("index.php?option=$origname");
                        mamboCore::set('Itemid', $Itemid);
                        if ($this->custom_PHP[$i]) {
                            $fixup = '$QUERY_STRING .= "option='.$origname.'&Itemid=".$Itemid.sef_'.$this->custom_short[$i].'::revert($url_array,1);';
                            eval($fixup);
                        }
                        else $QUERY_STRING .= "option=$origname&Itemid=$Itemid".$this->default_revert($compname);
                        $_REQUEST['option'] = $this->custom_code[$i];
                        $foundit = true;
                        break;
                    }
                }
                if (!$foundit) {
                    $content_sef = mamboCore::get('mosConfig_absolute_path').'/components/com_content/sef_ext.php';
                    if (file_exists($content_sef)) {
                        require_once($content_sef);
                        $crevert = sef_content::revert($url_array,1);
                        if ($crevert) {
                            $foundit = true;
                            $QUERY_STRING .= $crevert;
                        }
                    }
                }
            }
            if ($foundit) {
                $_SERVER['QUERY_STRING'] = $QUERY_STRING;
                $REQUEST_URI = '/index.php?'.$QUERY_STRING;
                $_SERVER['REQUEST_URI'] = $REQUEST_URI;
                return 0;
            }
            else return 1;
        }
        return 0;
    }
    
    function default_revert ($specialname) {
        $request = explode($specialname.'/', $_SERVER['REQUEST_URI']);
        if (isset($request[1])) $parmset = explode("/", $request[1]);
        else $parmset = array();
        $QUERY_STRING = '';
        $menuhandler =& mosMenuHandler::getInstance();
        foreach($parmset as $values) {
            $parts = explode(",", $values);
            if (count($parts) > 1) {
                $_REQUEST[$parts[0]] = $_GET[$parts[0]] = $parts[1];
                if ($parts[0] == 'option') {
                    $_REQUEST['Itemid'] = $_GET['Itemid'] = $Itemid = $menuhandler->getIDLikeLink("index.php?option=$parts[1]");
                    mamboCore::set('Itemid', $Itemid);
                    $QUERY_STRING .= "$parts[0]=$parts[1]&Itemid=$Itemid";
                }
                $QUERY_STRING .= "&$parts[0]=$parts[1]";
            }
        }
        return $QUERY_STRING;
    }

    function sefRelToAbs( $string ) {
        global $mosConfig_nok_content,$iso_client_lang;

        $server = mamboCore::get('mosConfig_live_site');
        if ($string == 'index.php') return $server.'/';
        if (strtolower(substr($string,0,9)) != 'index.php' OR eregi('^(([^:/?#]+):)',$string)) return $string;
        $anchor = '';
        if (preg_match('/#((.)+)$/',$string,$parts)){
            $anchor = $parts[1];
            $string = substr($string, 0, (strlen($anchor) + 1)*-1);
        }
        if (mosGetParam($_GET, 'syndstyle', '') == 'yes') $string .= '&syndstyle=yes';
        if($mosConfig_nok_content AND (strpos( strtolower($string),"lang=") === false)) {
            $string .= "&lang=$iso_client_lang";
        }
        $passed_string = str_replace('&amp;', '&', $string);
        if (!mamboCore::get('mosConfig_sef')) return $this->plusAnchor($server.'/'.str_replace( '&', '&amp;', $passed_string ),$anchor);
        $string = substr($passed_string,10);
        $option = $task = '';
        $oktasks = true;
        $syndmark = '';
        parse_str($string, $params);
        foreach ($params as $key=>$value) {
            $lowkey = strtolower($key);
            $lowvalue = strtolower($value);
            $unset = true;
            switch ($lowkey) {
                case 'option':
                    $option = $lowvalue;
                    break;
                case 'task':
                    $task = $value;
                    if ($lowvalue == 'new' OR $lowvalue == 'edit') $oktasks = false;
                    break;
                case 'syndstyle':
                    $syndmark = '/syndstyle';
                    break;
                default:
                    $check_params[$lowkey] = $key;
                    $unset = false;
            }
            if ($unset) unset($params[$key]);
        }
        // Process content items
        if (($option == 'com_content' OR $option == 'content') AND $oktasks) {
            /*
            Content
            index.php?option=com_content&task=$task&sectionid=$sectionid&id=$id&Itemid=$Itemid&limit=$limit&limitstart=$limitstart
            */
            $task = $this->sef_content_task[$task];
            $content_sef = mamboCore::get('mosConfig_absolute_path').'/components/com_content/sef_ext.php';
            if (file_exists($content_sef)) {
                require_once($content_sef);
                return $this->plusAnchor($server.'/'.$this->prefix.$syndmark.sef_content::create($task, $params),$anchor);
            }
            $keys = array('sectionid', 'id', 'itemid', 'limit', 'limitstart', 'year', 'month', 'module', 'lang');
            $string = '/content'.$syndmark.'/'.$task.'/';
            foreach ($keys as $key) {
                if (isset($check_params[$key])) {
                    $pkey = $check_params[$key];
                    $string .= $params[$pkey].'/';
                }
            }
            return $this->plusAnchor($server.$string,$anchor);
        }
        // Other types of URL than content do not use Itemid in the SEO version
        if (isset($check_params['itemid']) && isset($check_params['id'])) {
            $pkey = $check_params['itemid'];
            unset($params[$pkey]);
            unset($check_params['itemid']);
        }
        // Process customised components
        $i = array_search($option,$this->custom_code);
        if ($i !== false AND $i !== null) {
            if ($this->custom_PHP[$i]) eval('$string = sef_'.$this->custom_short[$i].'::create($passed_string);');
            else $string = $this->componentDetails($params,$task);
            return $this->plusAnchor($server.'/'.$this->prefix.$syndmark.'/'.$this->custom_name[$i].'/'.$string,$anchor);
        }
        // Process ordinary components
        if (strpos($option,'com_')===0 AND $option != 'com_registration' AND $oktasks) {
            return $this->plusAnchor("$server/component$syndmark/option,$option/".$this->componentDetails($params,$task),$anchor);
        }
        // Anything else is returned as received, except it is guaranteed that & will be &amp;
        return $this->plusAnchor($server.'/'.str_replace( '&', '&amp;', $passed_string ),$anchor);
    }

    function componentDetails (&$params, $task) {
        $string = ($task ? "task,$task/" : '');
        foreach ($params as $key=>$param) $string .= "$key,$param/";
        return $string;
    }

    function plusAnchor($sefString, $anchor){
        if ( substr($sefString,-1) == '/' ){
            $sefString = substr($sefString,0,-1);
            if ($anchor!=''){
                $sefString.="#{$anchor}";
            }else{
                $sefString.='/';
            }
        }
        return $sefString;
    }

}

function sefRelToAbs ($string) {
    $sef =& mosSEF::getInstance();
    return $sef->sefRelToAbs($string);
}

if (mamboCore::get('mosConfig_sef') AND $indextype == 3) {
    $sef =& mosSEF::getInstance();
    $urlerror = $sef->sefRetrieval(mamboCore::get('mosConfig_register_globals'));
    $indextype = 1;
}
?>
