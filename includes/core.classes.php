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

/**
* Mambo basic error object
*/
define ('_MOS_ERROR_INFORM', 0);
define ('_MOS_ERROR_WARN', 1);
define ('_MOS_ERROR_SEVERE', 2);
define ('_MOS_ERROR_FATAL', 3);

/**
 * Enter description here...
 *
 */
class mosError {
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    var $text = '';
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    var $level = 0;

    /**
     * Enter description here...
     *
     * @param unknown_type $text
     * @param unknown_type $level
     * @return mosError
     */
    function mosError ($text='', $level=_MOS_ERROR_INFORM) {
        $this->text = $text;
        $this->level = $level;
    }
}

/**
* Mambo group of errors for some particular operation
*/
class mosErrorSet {
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    var $errors = array();
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    var $maxlevel = 0;

    // Parameter is an error object
    /**
     * Enter description here...
     *
     * @param unknown_type $error
     */
    function addError ($error) {
        $this->errors[] = $error;
        if ($error->level > $this->maxlevel) $this->maxlevel = $error->level;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $text
     * @param unknown_type $level
     */
    function addErrorDetails ($text='', $level=_MOS_ERROR_INFORM) {
        $error = new mosError($text, $level);
        $this->addError($error);
    }

    /**
     * Enter description here...
     *
     * @return unknown
     */
    function &getErrors () {
        return $this->errors;
    }

    /**
     * Enter description here...
     *
     * @return unknown
     */
    function getMaxLevel () {
        return $this->maxlevel;
    }

    /**
     * Enter description here...
     *
     * @return unknown
     */
    function getCount () {
        return count($this->errors);
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $errorset
     */
    function mergeAnother ($errorset) {
        $this->errors = array_merge($this->errors, $errorset->errors);
    }

}


/* This is the new error handler to store errors in the database
class mosErrorHandler {
var $types = array (
E_STRICT => 'Strict check',
E_USER_WARNING => 'User Warning',
E_USER_NOTICE => 'User Notice',
E_WARNING => 'Warning',
E_NOTICE => 'Notice',
E_CORE_WARNING => 'Core Warning',
E_COMPILE_WARNING => 'Compile Warning',
E_USER_ERROR => 'User Error',
E_ERROR => 'Error',
E_PARSE => 'Parse error',
E_CORE_ERROR => 'Core Error',
E_COMPILE_ERROR => 'Compile Error'
);

function mosErrorHandler () {
set_error_handler(array(&$this, 'handler'));
}

function handler ($errno, $errstr, $errfile, $errline, $errcontext) {
if ($errno = E_STRICT) return;
$string = $this->types[$errno].': '.$errstr.' in '.$errfile.' at '.$errline;
$database =& mamboDatabase::getInstance();
if (eregi('^(sql)$', $errstr)) {
$extra = $database->getErrorMsg();
}
if (function_exists('debug_backtrace')) {
foreach(debug_backtrace() as $back) {
if (@$back['file']) {
$extra .= "\n".$back['file'].':'.$back['line'];
}
}
}
$database->setQuery("DELETE FROM #__errors WHERE file=$errfile AND line=$errline AND number=$errno");
$database->query();
$database->setQuery("INSERT INTO #__errors VALUES (0, $errno, '$errfile', $errline, '$string', '$extra')");
$database->query();
}
}
*/

/**
 * Enter description here...
 *
 */
class mamboCore {
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    var $rootPath = '';
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    var $Itemid = 0;
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    var $option = '';
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    var $subdirectory;
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    var $current_user = null;
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    var $do_gzip_compress = false;
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    var $init_errorlevel = 0;

    /**
     * Enter description here...
     *
     * @return mamboCore
     */
    function mamboCore () {
        global $adminside;
        $this->init_errorlevel = error_reporting(0);
        //$this->rootPath = str_replace('\\', '/', realpath(str_replace('includes', '', dirname(__FILE__))));
        $this->rootPath = str_replace('\\', '/',str_replace('includes', '', dirname(__FILE__)));
        $this->checkConfig();
        $this->Itemid = (int)mosGetParam($_REQUEST, 'Itemid', 0);
        $this->getConfig();
        $this->fixLanguage();
        @set_magic_quotes_runtime( 0 );
        if (@$this->mosConfig_error_reporting > 0 OR @$this->mosConfig_error_reporting ===0) error_reporting($this->mosConfig_error_reporting);
        else error_reporting($this->init_errorlevel);
    }

    /**
     * Enter description here...
     *
     * @return unknown
     */
    function &getMamboCore () {
        static $instance;
        if (!is_object($instance)) $instance = new mamboCore();
        return $instance;
    }

    /**
     * Enter description here...
     *
     * @return unknown
     */
    function rootPath () {
        //if (realpath($this->rootPath) === false) die ('Invalid program load path');
        if (file_exists($this->rootPath) === false) die ('Invalid program load path');
        return $this->rootPath;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $property
     * @return unknown
     */
    function get ($property) {
        $config =& mamboCore::getMamboCore();
        if ($property == 'mosConfig_absolute_path') {
            //if (realpath($config->mosConfig_absolute_path) === false) die ('Invalid program load path');
            if (file_exists($config->mosConfig_absolute_path) === false) die ('Invalid program load path');
            else return $config->rootPath;
        }
        if (isset($config->$property)) return $config->$property;
        trigger_error("Invalid property ($property) requested from mamboCore");
        return null;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $property
     * @return unknown
     */
    function is_set ($property) {
        $config =& mamboCore::getMamboCore();
        return isset($config->$property);
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $property
     * @param unknown_type $value
     * @return unknown
     */
    function set ($property, $value) {
        $config =& mamboCore::getMamboCore();
        $config->$property = $value;
        $GLOBALS[$property] = $value;
        return $value;
    }

    /**
     * Enter description here...
     *
     */
    function checkConfig () {
        // checks for configuration file, if none found loads installation page
        if (!file_exists($this->rootPath.'/configuration.php') OR filesize($this->rootPath.'/configuration.php') < 10 ) {
            header( 'Location: installation/index.php' );
            exit();
        }
    }

    /**
     * Enter description here...
     *
     */
    function getConfig () {
        global $adminside;
        $code = '';
        $f = @fopen($this->rootPath.'/configuration.php','rb');
        if ($f) {
            while ($f AND !feof($f)) {
                $line = fgets($f);
                $altered = preg_replace('/^\$/', '$this->', $line);
                if ($altered != $line) $code .= $altered;
            }
        }
        else {
            #header( 'Location: installation/index.php' );
            exit();
        }
        fclose($f);
        eval($code);


        /*if (isset($_SERVER['DOCUMENT_ROOT']) AND strlen($_SERVER['DOCUMENT_ROOT'])) {
            $docroot = str_replace('\\', '/', str_replace('\\\\', '\\', $_SERVER['DOCUMENT_ROOT']));
        }
        else {*/
            // Find information about where execution started
            $origin = array_pop(debug_backtrace());
            // Find the PHP script at the start, with a fix for Windows slashes
            $absolutepath = str_replace('\\', '/', $origin['file']);
            $localpath = $_SERVER['PHP_SELF'];
            $docroot = substr($absolutepath,0,strpos($absolutepath,$localpath));
        /*}*/
        $mamboroot = str_replace('\\', '/', rtrim($this->rootPath, '\/'));
        $this->subdirectory = substr($mamboroot, strlen($docroot));

        $scheme = isset($_SERVER['HTTP_SCHEME']) ? $_SERVER['HTTP_SCHEME'] : ((isset($_SERVER['HTTPS']) AND strtolower($_SERVER['HTTPS'] != 'off')) ? 'https' : 'http');
        if (isset($_SERVER['HTTP_HOST'])) {
            $withport = explode(':', $_SERVER['HTTP_HOST']);
            $servername = $withport[0];
            if (isset($withport[1])) $port = ':'.$withport[1];
        }
        elseif (isset($_SERVER['SERVER_NAME'])) $servername = $_SERVER['SERVER_NAME'];
        else trigger_error(T_('Impossible to determine the name of this server'), E_USER_ERROR);
        if (!isset($port) AND !empty($_SERVER['SERVER_PORT'])) $port = ':'.$_SERVER['SERVER_PORT'];
        if (isset($port)) {
            if (($scheme == 'http' AND $port == ':80') OR ($scheme == 'https' AND $port == ':443')) $port = '';
        }
        else $port = '';
        $afterscheme = '://'.$servername.$port.$this->subdirectory;
        //$this->mosConfig_live_site = $this->mosConfig_secure_site = $scheme.$afterscheme;
        $this->mosConfig_unsecure_site = 'http'.$afterscheme;
        $this->mosConfig_absolute_path = $this->rootPath;
        preg_match_all('/\$this\-\>([A-Za-z_][A-Za-z0-9_]*)/', $code, $matches);
        foreach ($matches[1] as $match) $GLOBALS[$match] = $this->$match;
        if (!isset($this->mosConfig_register_globals)) {
            $this->mosConfig_register_globals = 0;
            $GLOBALS['mosConfig_register_globals'] = 0;
        }

    }

    /**
     * Enter description here...
     *
     * @return unknown
     */
    function getFavIcon () {
        // favourites icon
        if (!isset($this->mosConfig_favicon)) $this->mosConfig_favicon = 'favicon.ico';
        if (!file_exists($this->rootPath.'/images/'.$this->mosConfig_favicon)) $this->mosConfig_favicon = 'favicon.ico';
        return $this->mosConfig_live_site.'/images/'.$this->mosConfig_favicon;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $user
     * @param unknown_type $database
     */
function offlineCheck (&$user, &$database) {
    global $adminside;
        if (($this->mosConfig_offline && !$adminside) OR file_exists($this->rootPath.'/installation/index.php')) {
            require_once($this->rootPath().'/administrator/includes/admin.php');
            session_name(md5($this->mosConfig_live_site));
            session_start();
            $session =& mosSession::getCurrent();
            $my =& new mosUser();
            $my->getSessionData();
            if (mosSession::validate($my)) return;
            include("$this->mosConfig_absolute_path/offline.php");
            exit();
        }
    }

    /**
     * Enter description here...
     *
     */
    function fixLanguage () {
        require_once($this->mosConfig_absolute_path.'/includes/phpgettext/phpgettext.class.php');
        require_once($this->mosConfig_absolute_path.'/includes/phpgettext/error.php');
        require_once($this->mosConfig_absolute_path.'/includes/mambofunc.php');
        require_once($this->mosConfig_absolute_path.'/includes/mambolanguage.class.php');
        if (!mosGetParam($_REQUEST, 'lang'));
	else $this->mosConfig_locale = mosGetParam($_REQUEST, 'lang', $this->mosConfig_locale);
        $language =& new mamboLanguage($this->mosConfig_locale, $this->rootPath.'/language/');
        $languages = $language->getLanguages();
        $charset = $language->get('charset');
        $dateformat = $language->get('dateformat');
        $this->mosConfig_lang = $language->get('lang');
        $this->current_language = $language;
        if (!defined('_ISO')) DEFINE('_ISO','charset='.$charset);
		header('Content-type: text/html; '._ISO);
        if (!defined('_DATE_FORMAT_LC')) DEFINE('_DATE_FORMAT_LC', $dateformat); //Uses PHP's strftime Command Format
        if (!defined('_DATE_FORMAT_LC2')) DEFINE('_DATE_FORMAT_LC2', $dateformat);

        #error_reporting(E_ALL)        ;
        ##########  DEPRECATED ############
        if (isset($this->mosConfig_lang) AND $this->mosConfig_lang);
        else $this->set('mosConfig_lang', 'english');
        $language_file = "$this->mosConfig_absolute_path/language/$this->mosConfig_lang.php";
        if (file_exists($language_file)) require_once ($language_file);
        else require_once ("$this->mosConfig_absolute_path/language/english.php");
        ###################################




    }

    /**
     * Enter description here...
     *
     */
	function handleGlobals () {
        $superglobals = array($_SERVER, $_ENV, $_FILES, $_COOKIE, $_POST, $_GET);
        if (isset( $_SESSION )) array_unshift ( $superglobals , $_SESSION );

        // Emulate register_globals on
        if (!ini_get('register_globals') && $this->mosConfig_register_globals) {
            while(list($key,$value)=each($_GET)) {
                if (!isset($GLOBALS[$key])) $GLOBALS[$key]=$value;
            }
            while(list($key,$value)=each($_POST)) {
                if (!isset($GLOBALS[$key])) $GLOBALS[$key]=$value;
            }
        }
        // Emulate register_globals off
        elseif (ini_get('register_globals') && !$this->mosConfig_register_globals) {
            foreach ( $superglobals as $superglobal ) {
                foreach ( $superglobal as $key => $value) {
                    unset( $GLOBALS[$key]);
                    unset( $GLOBALS[$key]);
                }
            }
        }
    }

    /**
     * Enter description here...
     *
     * @return unknown
     */
    function determineOptionAndItemid () {
        $this->Itemid = (int)mosGetParam($_REQUEST, 'Itemid', 0);
        if ($option = strtolower(mosGetParam($_REQUEST, 'option')));
        else {
            $menuhandler =& mosMenuHandler::getInstance();
            $menus =& $menuhandler->getByParentOrder($this->Itemid, 'mainmenu');
            $this->Itemid = $menus[0]->id;
            $_REQUEST['Itemid'] = $this->Itemid;
            $link = $menus[0]->link;
            $pos = strpos( $link, '?' );
            if ($pos !== false) $link = substr( $link, $pos+1 ). '&Itemid='.$this->Itemid;
            parse_str( $link, $temp );
            /** this is a patch, need to rework when globals are handled better */
			foreach ($temp as $k=>$v) $_GET[$k] = $_REQUEST[$k] = $v;
            if (isset($temp['option'])) $option = $temp['option'];
            else return '';
        }
        /** patch to lessen the impact on templates */
        if ($option == 'search') $option = 'com_search';
        // checking if we can find the Itemid thru the component
        if ($this->Itemid === 0) {
            if ( $option == 'com_content') {
                require_once($this->rootPath().'/components/com_content/content.class.php');
                $handler =& contentHandler::getInstance();
                $this->Itemid = (int)$handler->getItemid(mosGetParam($_REQUEST, 'id', 0 ));
                $_REQUEST['Itemid'] = $this->Itemid;
            }
            else {
                $menuhandler =& mosMenuHandler::getInstance();
                $this->Itemid = $menuhandler->getIdLikeLink("index.php?option=$option");
                if ($this->Itemid === 0) {
                    $menuhandler =& mosMenuHandler::getInstance();
                    $menus =& $menuhandler->getByParentOrder($this->Itemid, 'mainmenu');
                }
            }
        }
        return trim(htmlspecialchars($option));
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $url
     * @param unknown_type $msg
     */
    function redirect ($url, $msg='') {
        $callcheck = array('InputFilter', 'process');
        if (!is_callable($callcheck)) require_once(mamboCore::get('mosConfig_absolute_path').'/includes/phpInputFilter/class.inputfilter.php');
        // specific filters
        $iFilter =& new InputFilter();
        $url = $iFilter->process( $url );
        $message = trim($iFilter->process($msg));
        if ($iFilter->badAttributeValue(array('href', $url))) $url = mamboCore::get('mosConfig_live_site');
        if ($message) {
            if (strpos($url, '?')) $url .= '&mosmsg='.urlencode($message);
            else $url .= '?mosmsg='.urlencode($message);
        }
        if (headers_sent()) echo "<script>document.location.href='$url';</script>\n";
        else {
            @ob_end_clean(); // clear output buffer
            header( "Location: $url" );
        }
        exit();
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $text
     */
    function logMessage ($text) {
        // JS Popup message
        if (mosGetParam( $_POST, 'message', 0 )) {
			?>
			<script type="text/javascript">
			<!--//
			alert( "<?php echo $text; ?>" );
			//-->
			</script>
			<?php
        }
        if ($return = mosGetParam( $_REQUEST, 'return', '' )) {
            $this->redirect( $return );
        }
        else {
            $this->redirect( $this->mosConfig_live_site.'/index.php' );
        }
    }

    /**
     * Enter description here...
     *
     */
    function handleLogin () {
        require_once($this->rootPath().'/includes/authenticator.php');
        $authenticator =& mamboAuthenticator::getInstance();
        $loggedin = $authenticator->loginUser();
        if ($loggedin) $this->logMessage(T_('You have Logged In succesfully'));
        else mamboCore::redirect('index.php');
    }

    /**
     * Enter description here...
     *
     */
    function handleLogout () {
        require_once($this->rootPath().'/includes/authenticator.php');
        $authenticator =& mamboAuthenticator::getInstance();
        $authenticator->logoutUser();
        $this->logMessage(T_('You have Logged Out successfully'));
    }

    /**
     * Enter description here...
     *
     */
    function standardHeaders () {
        header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
        header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
        header( 'Cache-Control: no-store, no-cache, must-revalidate' );
        header( 'Cache-Control: post-check=0, pre-check=0', false );
        header( 'Pragma: no-cache' );
        $mambothandler =& mosMambotHandler::getInstance();
        $mambothandler->loadBotGroup('system');
        $mambothandler->trigger('onHeaders', array($this));
    }

    /**
     * Enter description here...
     *
     */
    function initGzip() {
        $this->do_gzip_compress = FALSE;
        //zlib.output_compression and ob_gzhandler don't get along well so we'll check to make
        //that zlib.output_compression is not enable in the php.ini before turning on ob_gzhandler
        if ( $this->mosConfig_gzip == 1 AND (int)ini_get('zlib.output_compression') != 1 ) {
            $phpver = phpversion();
            $useragent = mosGetParam( $_SERVER, 'HTTP_USER_AGENT', '' );
            $canZip = mosGetParam( $_SERVER, 'HTTP_ACCEPT_ENCODING', '' );

            if ( $phpver >= '4.0.4pl1' AND
            ( strpos($useragent,'compatible') !== false ||
            strpos($useragent,'Gecko')      !== false
            )
            ) {
                if ( extension_loaded('zlib') ) {
                    ob_start( 'ob_gzhandler' );
                    return;
                }
            } else if ( $phpver > '4.0' ) {
                if ( strpos($canZip,'gzip') !== false ) {
                    if (extension_loaded( 'zlib' )) {
                        $this->do_gzip_compress = TRUE;
                        ob_start();
                        ob_implicit_flush(0);

                        header( 'Content-Encoding: gzip' );
                        return;
                    }
                }
            }
        }
        ob_start();
    }

    /**
	* Perform GZIP
	*/
    function doGzip() {
        if ( $this->do_gzip_compress ) {
            /**
			*Borrowed from php.net!
			*/
            $gzip_contents = ob_get_contents();
            ob_end_clean();

            $gzip_size = strlen($gzip_contents);
            $gzip_crc = crc32($gzip_contents);

            $gzip_contents = gzcompress($gzip_contents, 9);
            $gzip_contents = substr($gzip_contents, 0, strlen($gzip_contents) - 4);

            echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
            echo $gzip_contents;
            echo pack('V', $gzip_crc);
            echo pack('V', $gzip_size);
        } else {
            ob_end_flush();
        }
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $separator
     * @param unknown_type $field
     * @return unknown
     */
    function getLastPart ($separator, $field) {
        $parts = explode($separator, $field);
        return $parts[count($parts)-1];
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $separator
     * @param unknown_type $field
     * @return unknown
     */
    function allButLast ($separator, $field) {
        $lastSize = strlen(mamboCore::getLastPart($separator,$field));
        return substr($field, 0, strlen($field)-$lastSize);
    }

}


/**
* Sorts an Array of objects
*/
class mosObjectSorter {
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    var $_keyname = '';
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    var $_direction = 0;
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    var $_object_array = array();

    /**
     * Enter description here...
     *
     * @param unknown_type $a
     * @param unknown_type $k
     * @param unknown_type $sort_direction
     * @return mosObjectSorter
     */
    function mosObjectSorter (&$a, $k, $sort_direction=1) {
        $this->_keyname = $k;
        $this->_direction = $sort_direction;
        $this->_object_array =& $a;
        $this->sort();
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $a
     * @param unknown_type $b
     * @return unknown
     */
    function mosObjectCompare (&$a, &$b) {
        $key = $this->_keyname;
        if ($a->$key > $b->$key) return $this->_direction;
        if ($a->$key < $b->$key) return -$this->_direction;
        return 0;
    }

    /**
     * Enter description here...
     *
     */
    function sort () {
        usort($this->_object_array, array($this,'mosObjectCompare'));
    }

}

/**
* Pathway handler
* @package Mambo
*/
class mosPathway {
    /** @var array Names for display in pathway */
    var $_names = null;
    /** @var array URLs for links from pathway */
    var $_urls = null;

    /**
	* Constructor
	*/
    function mosPathway () {
        $menuhandler =& mosMenuHandler::getInstance();
        $menus =& $menuhandler->getByParentOrder(0,'mainmenu');
        $home = $menus[0];
        $this->_names[] = $home->name;
        $this->_urls[] = sefRelToAbs($home->link."&Itemid=$home->id");
    }

    /**
	* Singleton accessor
	*/
    function &getInstance () {
        static $instance;
        if (!is_object($instance)) $instance = new mosPathway();
        return $instance;
    }

    /**
	* Add an item to the pathway
	*/
    function addItem ($name, $givenurl) {
        $last = count($this->_names) - 1;
        if (!$name) return;
        $url = sefRelToAbs($givenurl);
        if ($name == $this->_names[$last] AND $url == $this->_urls[$last]) return;
        $this->_names[$last+1] = $name;
        $this->_urls[$last+1] = $url;
    }

    /**
     * Enter description here...
     *
     */
    function reduceToOne () {
        for ($i = count($this->_names) - 1; $i > 0; $i--) {
            unset($this->_names[$i]);
            unset($this->_urls[$i]);
        }
    }

    /**
	* Make a pathway string for display
	*/
    function makePathway () {
        $mainframe =& mosMainFrame::getInstance();
    	$customs = $mainframe->getCustomPathWay();
    	$last = count($this->_names) - 1;
        if ($last == 0 AND count($customs == 0)) return '';
        $result = "<span class='pathway'>";
        $config =& mamboCore::getMamboCore();
        $rootpath = $config->rootPath();
        $imgPath =  'templates/'.$mainframe->getTemplate().'/images/arrow.png';
        if (file_exists( "$rootpath/$imgPath" )) $img = "<img src='$config->mosConfig_live_site/$imgPath' border='0' alt='arrow' />";
        else {
            $imgPath = '/images/M_images/arrow.png';
            if (file_exists( "$rootpath/$imgPath" )) $img = "<img src='$config->mosConfig_live_site/images/M_images/arrow.png' alt='arrow' />";
            else $img = '&gt;';
        }
		$uri =& mosUriHelper::getInstance();
        foreach ($this->_names as $i=>$name) {
			$uri->setUri($this->_urls[$i]);
            if ($i === $last AND count($customs) == 0) $result .= "$name</span>";
            elseif (strstr($uri->get('task'), 'view')) $result .= ""; 
            else {
                $sefurl = sefRelToAbs($this->_urls[$i]);
                $result .= "<a href='$sefurl' class='pathway'>$name</a>";
                $result .= "&nbsp;$img&nbsp;";
            }
        }
        foreach ($customs as $custom) $result .= $custom;
        if (count($customs)) $result .= '</span>';
        return $result;
    }

}

/**
* Module database table class
* @package Mambo
*/
class mosMenu extends mosDBTable {
    /** @var int Primary key */
    var $id=null;
    /** @var string */
    var $menutype=null;
    /** @var string */
    var $name=null;
    /** @var string */
    var $link=null;
    /** @var int */
    var $type=null;
    /** @var int */
    var $published=null;
    /** @var int */
    var $componentid=null;
    /** @var int */
    var $parent=null;
    /** @var int */
    var $sublevel=null;
    /** @var int */
    var $ordering=null;
    /** @var boolean */
    var $checked_out=null;
    /** @var datetime */
    var $checked_out_time=null;
    /** @var boolean */
    var $pollid=null;

    /** @var string */
    var $browserNav=null;
    /** @var int */
    var $access=null;
    /** @var int */
    var $utaccess=null;
    /** @var string */
    var $params=null;

    /**
	* @param database A database connector object
	*/
    function mosMenu() {
        $db =& mamboDatabase::getInstance();
        $this->mosDBTable( '#__menu', 'id', $db );
    }
    /**
	*	binds an array/hash to this object
	*	@param int $oid optional argument, if not specifed then the value of current key is used
	*	@return any result from the database operation
	*/
    function load( $oid=null ) {
        $k = $this->_tbl_key;
        if ($oid !== null) $this->$k = $oid;
        if ($this->$k === null) return false;
        $menuhandler =& mosMenuHandler::getInstance();
        $menu =& $menuhandler->getMenuById($this->$k);
        if ($menu) {
            foreach (get_object_vars($menu) as $key=>$data) $this->$key = $data;
            return true;
        }
        else return false;
    }

}

/**
* File Manager including safe mode provision?
* @package Mambo
*/
class mosFileManager {

    /**
	* Singleton accessor
	*/
    function &getInstance () {
        static $instance;
        if (!is_object($instance)) $instance = new mosFileManager();
        return $instance;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $file
     * @return unknown
     */
    function deleteFile ($file) {
        if (file_exists($file)) {
            @chmod($file, 0644);
            return unlink($file);
        }
        return true;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $dir
     * @return unknown
     */
    function deleteDirectory ($dir) {
        if (file_exists($dir)) {
            if (is_dir($dir)) {
                @chmod($dir, 0755);
                return rmdir($dir);
            }
            return false;
        }
        return true;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $fileSysObject
     */
    function setPermissions ($fileSysObject) {
        if (file_exists($fileSysObject))  {
            if (is_dir($fileSysObject)) $perms = mamboCore::get('mosConfig_dirperms');
            else $perms = mamboCore::get('mosConfig_fileperms');
            if ($perms) {
                $origmask = @umask(0);
                $mode = octdec($perms);
                @chmod($fileSysObject, $mode);
                @umask($origmask);
            }
        }
    }

    /**
	 * Enter description here...
	 *
	 * @param unknown_type $dir
	 * @return unknown
	 */
    function makeDirectory ($dir) {
        $perms = mamboCore::get('mosConfig_dirperms');
        $origmask = @umask(0);
        if ($perms) $result = @mkdir($dir, octdec($perms));
        else $result = @mkdir($dir, 0755);
        if ($result) $this->setPermissions($dir);
        @umask($origmask);
        return $result;
    }


    /**
	 * Enter description here...
	 *
	 * @param unknown_type $dir
	 * @param unknown_type $onlyCheck
	 * @return unknown
	 */
    function createDirectory ($dir, $onlyCheck=false) {
        if (file_exists($dir)) {
            if (is_dir($dir) AND is_writable($dir)) return true;
            else return false;
        }
        list($upDirectory, $count) = $this->containingDirectory($dir);
        if ($count > 1 AND !file_exists($upDirectory) AND !($result = $this->createDirectory($upDirectory, $onlyCheck))) return false;
        if ($onlyCheck AND isset($result)) return true;
        if (!is_dir($upDirectory) OR !is_writable($upDirectory)) return false;
        if ($onlyCheck) return true;
        else return $this->makeDirectory($dir);
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $dir
     * @return unknown
     */
    function containingDirectory ($dir) {
        $dirs = preg_split('*[/|\\\]*', $dir);
        for ($i = count($dirs)-1; $i >= 0; $i--) {
            $text = trim($dirs[$i]);
            unset($dirs[$i]);
            if ($text) break;
        }
        $result2 = count($dirs);
        $result1 = implode('/',$dirs).($result2 > 1 ? '' : '/');
        return array($result1, $result2);
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $from
     * @param unknown_type $to
     * @return unknown
     */
    function simpleCopy ($from, $to) {
        if (@copy($from, $to)) {
            $this->setPermissions($to);
            return true;
        }
        else return false;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $from
     * @param unknown_type $to
     * @return unknown
     */
    function forceCopy ($from, $to) {
        $todir = dirname($to);
        if (!file_exists($todir)) $this->createDirectory($todir);
        if (!file_exists($todir)) return false;
        $name = basename($from);
        $this->deleteFile($to.$name);
        return $this->simpleCopy ($from, $to);
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $from
     * @param unknown_type $to
     * @return unknown
     */
    function lightCopy ($from, $to) {
        $name = basename($from);
        if (file_exists($to.$name)) return false;
        $todir = dirname($to);
        if (!file_exists($todir)) $this->createDirectory($todir);
        if (!file_exists($todir)) return false;
        return $this->simpleCopy ($from, $to);
    }

    /**
	 * Enter description here...
	 *
	 * @param unknown_type $to
	 * @return unknown
	 */
    function acceptCopy ($to) {
        $todir = dirname($to);
        return $this->createDirectory($todir, true);
    }


    /**
	* Function to strip additional / or \ in a path name
	* @param string The path
	* @param boolean Add trailing slash
	*/
    function mosPathName($p_path, $p_addtrailingslash=true) {
        if (substr(PHP_OS, 0, 3) == 'WIN')	{
            $retval = str_replace( '/', '\\', $p_path );
            if ($p_addtrailingslash AND substr( $retval, -1 ) != '\\') $retval .= '\\';
            // Remove double \\
            $retval = str_replace( '\\\\', '\\', $retval );
        }
        else {
            $retval = str_replace( '\\', '/', $p_path );
            if ($p_addtrailingslash AND substr( $retval, -1 ) != '/') $retval .= '/';
            // Remove double //
            $retval = str_replace('//','/',$retval);
        }
        return $retval;
    }

    /**
	* Chmods files and directories recursively to mos global permissions. Available from 4.5.2 up.
	* @param path The starting file or directory (no trailing slash)
	* @param filemode Integer value to chmod files. NULL = dont chmod files.
	* @param dirmode Integer value to chmod directories. NULL = dont chmod directories.
	* @return TRUE=all succeeded FALSE=one or more chmods failed
	*/
    function mosChmod($path)
    {
        $fileperms = mamboCore::get('mosConfig_fileperms');
        if ($fileperms != '') $filemode = octdec($fileperms);
        else $filemode = null;
        $dirperms = mamboCore::get('mosConfig_dirperms');
        if ($dirperms != '') $dirmode = octdec($dirperms);
        else $dirmode = null;
        if (isset($filemode) OR isset($dirmode))
        return $this->mosChmodRecursive($path, $filemode, $dirmode);
        return true;
    } // mosChmod

    /**
	* Chmods files and directories recursively to given permissions. Available from 4.5.2 up.
	* @param path The starting file or directory (no trailing slash)
	* @param filemode Integer value to chmod files. NULL = dont chmod files.
	* @param dirmode Integer value to chmod directories. NULL = dont chmod directories.
	* @return TRUE=all succeeded FALSE=one or more chmods failed
	*/
    function mosChmodRecursive($path, $filemode=NULL, $dirmode=NULL) {
        $ret = true;
        if (is_dir($path)) {
            $topdir =& new mosDirectory($path);
            $files =& $topdir->listFiles ('', 'file', true, true);
            $dirs =& $topdir->listFiles ('', 'dir', true, true);
        }
        else {
            $files = array($path);
            $dirs = array();
        }
        if (isset($filemode)) foreach ($files as $file) $ret = @chmod($file, $filemode) ? $ret : false;
        if (isset($dirmode)) foreach ($dirs as $dir) $ret = @chmod($dir, $dirmode) ? $ret : false;
        return $ret;
    }

}

/**
 * Enter description here...
 *
 */
class mosDirectory {
    /**
     * Enter description here...
     *
     * @var unknown_type
     */
    var $path = '';

    /**
     * Enter description here...
     *
     * @param unknown_type $path
     * @return mosDirectory
     */
    function mosDirectory ($path) {
        $path = str_replace('\\', '/', $path);
        if (substr($path,-1,1) == '/') $this->path = $path;
        else $this->path = $path.'/';
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $type
     * @param unknown_type $recurse
     * @param unknown_type $fullpath
     * @return unknown
     */
    function &listAll ($type='file', $recurse=false, $fullpath=false) {
        $results = array();
        if ($dir = @opendir($this->path)) {
            while (false !== ($file = readdir($dir))) {
                if ($file == '.' OR $file == '..') continue;
                if (is_dir($this->path.$file)) {
                    if ($recurse) {
                        $subdir = new mosDirectory($this->path.$file);
                        $results = array_merge($results, $subdir->listAll($type, $recurse, $fullpath));
                        unset($subdir);
                    }
                    if ($type == 'file') continue;
                }
                elseif ($type == 'dir') continue;
                if ($fullpath) $results[] = $this->path.$file;
                else $results[] = $file;
            }
            closedir($dir);
        }
	asort($results);
        return $results;
    }

    /**
     * Enter description here...
     *
     * @return unknown
     */
    function soleDir () {
        $found = '';
        if ($dir = @opendir($this->path)) {
            while (false !== ($file = readdir($dir))) {
                if ($file == '.' OR $file == '..') continue;
                if (is_dir($this->path.$file)) {
                    if ($found) return '';
                    else $found = $file;
                }
                else return '';
            }
            closedir($dir);
        }
        return $found;
    }

    /**
     * Enter description here...
     *
     */
    function deleteAll () {
        if (!file_exists($this->path)) return;
        $subdirs =& $this->listAll ('dir', false, true);
        foreach ($subdirs as $subdir) {
            $subdirectory = new mosDirectory($subdir);
            $subdirectory->deleteAll();
            unset($subdirectory);
        }
        $filemanager =& mosFileManager::getInstance();
        $files =& $this->listAll ('file', false, true);
        foreach ($files as $file) $filemanager->deleteFile($file);
        $filemanager->deleteDirectory($this->path);
    }

    /**
     * Enter description here...
     *
     * @return unknown
     */
    function createFresh () {
        $this->deleteAll();
        $filemanager =& mosFileManager::getInstance();
        $filemanager->createDirectory($this->path);
        return true;
    }

    /**
     * Enter description here...
     *
     */
    function createIfNeeded () {
        if (!file_exists($this->path)) {
            $filemanager =& mosFileManager::getInstance();
            $filemanager->createDirectory($this->path);
        }
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $pattern
     * @param unknown_type $type
     * @param unknown_type $recurse
     * @param unknown_type $fullpath
     * @return unknown
     */
    function &listFiles ($pattern='', $type='file', $recurse=false, $fullpath=false) {
        $results = array();
        $all =& $this->listAll($type, $recurse, $fullpath);
        foreach ($all as $file) {
            $name = basename($file);
            if ($pattern AND !preg_match( "/$pattern/", $name )) continue;
            if (($name != 'index.html') AND ($name[0] != '.')) $results[] = $file;
        }
        return $results;
    }

    /**
     * Enter description here...
     *
     * @return unknown
     */
    function getSize () {
        $totalsize = 0;
        $files =& $this->listFiles();
        foreach ($files as $file) $totalsize += filesize($this->path.$file);
        return $totalsize;
    }

}


/**
* Menu handler
* @package Mambo
*/
class mosMenuHandler {
    /** @var array Menu objects currently available */
    var $_menus = null;
    /** @var array Counts of menu items by type and published status */
    var $_counts = null;
    /** @var array Access to stored menu objects by ID */
    var $_idlinks = null;
    /** @var array Items that may be useful for setting Itemid */
    var $_byParentOrder = null;

    /**
	* Constructor
	*/
    function mosMenuHandler() {
        $database =& mamboDatabase::getInstance();
        $sql = "SELECT * FROM #__menu ORDER BY name";
        $this->_menus =& $database->doSQLget($sql, 'mosMenu');
        if (!$this->_menus) $this->_menus = array();
        foreach ($this->_menus as $key=>$menu) {
            $this->_idlinks[$menu->id] = $key;
            if ($menu->published == 1) $this->_byParentOrder[$menu->parent][$menu->ordering][$menu->menutype] = $key;
            if (isset($this->_counts[$menu->menutype][$menu->published])) $this->_counts[$menu->menutype][$menu->published]++;
            else $this->_counts[$menu->menutype][$menu->published] = 1;
        }
        if ($this->_byParentOrder) {
            foreach ($this->_byParentOrder as $parent=>$outer) ksort($this->_byParentOrder[$parent]);
            ksort($this->_byParentOrder);
        }
    }
    /**
	* Singleton accessor
	*/
    function &getInstance () {
        static $instance;
        if (!is_object($instance)) $instance = new mosMenuHandler();
        return $instance;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $id
     * @return unknown
     */
    function &getMenuByID ($id) {
        if (isset($this->_idlinks[$id])) {
            $key = $this->_idlinks[$id];
            $result = $this->_menus[$key];
        }
        else $result = null;
        return $result;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $type
     * @param unknown_type $published
     * @return unknown
     */
    function getMenuCount ($type, $published) {
        if (isset($this->_counts[$type][$published])) return $this->_counts[$type][$published];
        else return 0;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $types
     * @return unknown
     */
    function &getMenusByType ($types) {
        $checker = explode(',', $types);
        $result = null;
        foreach ($this->_menus as $menu) {
            if (in_array($menu->menutype, $checker)) $result[] = $menu;
        }
        return $result;
    }

    /**
     * Enter description here...
     *
     * @return unknown
     */
    function &getMenuTypes () {
        $types = array();
        foreach ($this->_menus as $menu) {
            if (!isset($types[$menu->menutype])) $types[$menu->menutype] = 0;
            $types[$menu->menutype]++;
        }
        return $types;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $type
     * @param unknown_type $link
     * @return unknown
     */
    function getIDByTypeLink ($type, $link) {
        foreach ($this->_menus as $menu) {
            if ($menu->published == 1 AND ($type == '*' OR $menu->type == $type) AND $menu->link == $link) return $menu->id;
        }
        return null;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $link
     * @return unknown
     */
    function getIDLikeLink ($link) {
        $exact = $this->getIdByTypeLink('*', $link);
        if ($exact !== null) return $exact;
        foreach ($this->_menus as $menu) {
            if ($menu->published == 1 AND strpos($menu->link,$link) === 0) return $menu->id;
        }
        return 0;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $type
     * @param unknown_type $componentid
     * @return unknown
     */
    function getIDByTypeCid ($type, $componentid) {
        foreach ($this->_menus as $menu) {
            if ($menu->published == 1 AND $menu->type == $type AND $menu->componentid == $componentid) return $menu->id;
        }
        return null;
    }

    /**
     * Enter description here...
     *
     * @return unknown
     */
    function getGlobalBlogSectionCount () {
        $count = 0;
        foreach ($this->_menus as $menu) {
            if ($menu->type == 'content_blog_section' AND $menu->published == 1 AND $menu->componentid == 0) $count++;
        }
        return $count;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $Itemid
     * @param unknown_type $type
     * @param unknown_type $id
     * @param unknown_type $catid
     * @return unknown
     */
    function getContentItemid ($Itemid, $type, $id, $catid=0) {
        if ($Itemid) return $Itemid;
        foreach ($this->_menus as $menu) {
            if (strpos($menu->link,'index.php?option=com_content') === false AND strpos($menu->link,'index.php?option=content') === false) continue;
            if (strpos($menu->link, $type) === false) continue;
            if ($catid) {
                if (strpos($menu->link, "&id=$catid") === false) continue;
                if (strpos($menu->link, "&sectionid=$id") === false) continue;
            }
            elseif (strpos($menu->link, "&id=$id") === false) continue;
            return $menu->id;
        }
        return 0;
    }

    /**
     * Enter description here...
     *
     * @return unknown
     */
    function getBestQueryMatch () {
        parse_str($_SERVER['QUERY_STRING'], $qitems);
        if (!isset($qitems['option'])) return 0;
        $failures = 999;
        $best = 0;
        foreach ($this->_menus as $menu) {
            $split = explode('?', $menu->link);
            if (isset($split[1])) parse_str($split[1], $mitems);
            else continue;
            if (!isset($mitems['option']) OR $mitems['option'] != $qitems['option']) continue;
            $thisfail = 0;
            foreach ($mitems as $key=>$mitem) if (!isset($qitems[$key]) OR $mitem != $qitems[$key]) $thisfail++;
            if ($thisfail < $failures) {
                $best = $menu->id;
                $failures = $thisfail;
            }
        }
        return $best;
    }


    /**
     * Enter description here...
     *
     * @param unknown_type $link
     * @return unknown
     */
    function &maxAccessLink ($link) {
        $selected = null;
        $access = 0;
        foreach ($this->_menus as $key=>$menu) {
            if (strpos($menu->link,$link) === 0 AND $menu->access > $access) {
                $access = $menu->access;
                $selected =& $this->_menus[$key];
            }
        }
        return $selected;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $Itemid
     * @param unknown_type $menutype
     * @param unknown_type $maxaccess
     * @param unknown_type $noparent
     * @return unknown
     */
    function &getByParentOrder ($Itemid, $menutype, $maxaccess=0, $noparent=false) {
        $result = array();
        if ($this->_byParentOrder !== null) {
            foreach ($this->_byParentOrder as $parent=>$outer) {
                foreach ($outer as $ordering=>$inner) {
                    foreach ($inner as $mtype=>$last) {
                        $key = $this->_byParentOrder[$parent][$ordering][$mtype];
                        $menu = $this->_menus[$key];
                        if ($menutype AND $mtype != $menutype) continue;
                        if ($Itemid AND $Itemid != $menu->id) continue;
                        if ($menu->access > $maxaccess) continue;
                        if ($noparent AND $parent != 0) continue;
                        $result[] = $this->_menus[$key];
                    }
                }
            }
        }
        if ($Itemid == 0 && !count($result)){
            $result[0] = new stdclass;
            $result[0]->id = 1;
            $result[0]->link = 'index.php?option=com_frontpage';
            $result[0]->parent = 0;
            $result[0]->type = 'components';
            $result[0]->browserNav = 0;
            $result[0]->name = 'Home';
        }
        return $result;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $Itemid
     */
    function setPathway ($Itemid) {
        if ($Itemid) {
            $menu =& $this->getMenuByID($Itemid);
            if (!$menu) return;
            if ($menu->parent) $this->setPathway($menu->parent);
            $pathway =& mosPathway::getInstance();
            $pathway->addItem($menu->name, $menu->link."&Itemid=$Itemid");
        }
    }

    /**
	* Checks whether a menu option is within the users access level
	* @param int Item id number
	* @param string The menu option
	* @param int The users group ID number
	* @param database A database connector object
	* @return boolean True if the visitor's group at least equal to the menu access
	*/
    function menuCheck( $Itemid, $menu_option, $task, $gid ) {
        // Construct a link to this component - if no menu for it, assume it is OK
        $dblink="index.php?option=$menu_option";
        if ($this->getIDLikeLink($dblink) == 0) return true;
        if ($Itemid) {
            $menu =& $this->getMenuByID($Itemid);
            if (!$menu) return false;
            if (strpos($menu->link,$dblink) ===0) {
                $access = $menu->access;
            } elseif ($menu_option == 'com_content' AND $Itemid == 1) {
                return true;
            }
        }
        if (!isset($access)) {
            if ($task!='') $dblink .= "&task=$task";
            $menu =& $this->maxAccessLink($dblink);
            if (isset($menu)) {
                $access = $menu->access;
                mamboCore::set('Itemid', $menu->id);
            }
        }
        return isset($access) ? $access <= $gid : false;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $mitem
     * @param unknown_type $level
     * @param unknown_type $params
     * @param unknown_type $Itemid
     * @return unknown
     */
    function mosGetMenuLink( &$mitem, $level=0, &$params, $Itemid ) {
        $txt = '';

        switch ($mitem->type) {
            case 'separator':
            case 'component_item_link':
            break;
            case 'content_item_link':
            $temp = split("&task=view&id=", $mitem->link);
            if (isset($temp[1])) {
                require_once(mamboCore::get('mosConfig_absolute_path').'/components/com_content/content.class.php');
                $handler =& contentHandler::getInstance();
                $mitem->link .= '&Itemid='.$handler->getItemid($temp[1]);
            }
            break;
            case 'url':
            $link = strtolower($mitem->link);
            if (substr($link,0,10) == 'index.php?' AND strpos($link,'itemid=') === false) $mitem->link .= '&Itemid='. $mitem->id;
            break;
            case 'content_typed':
            default:
            $mitem->link .= '&Itemid='.$mitem->id;
            break;
        }
        // Active Menu highlighting
        if ( $Itemid == $mitem->id ) $id = 'id="active_menu'.$params->get( 'class_sfx' ).'"';
        else $id = '';
        $mitem->link = ampReplace( $mitem->link );
        if (strcasecmp(substr($mitem->link,0,4), 'http')) $mitem->link = sefRelToAbs( $mitem->link );
        if ($level > 0) $menuclass = 'sublevel';
        else $menuclass = 'mainlevel';
        $menuclass .= $params->get( 'class_sfx');

        switch ($mitem->browserNav) {
            // cases are slightly different
            case 1:
            // open in a new window
            $txt = '<a href="'. $mitem->link .'" target="_blank" class="'. $menuclass .'" '. $id .'>'. $mitem->name .'</a>';
            break;

            case 2:
            // open in a popup window
            $txt = "<a href=\"#\" onclick=\"javascript: window.open('". $mitem->link ."', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550'); return false\" class=\"$menuclass\" ". $id .">". $mitem->name ."</a>\n";
            break;

            case 3:
            // don't link it
            $txt = '<span class="'. $menuclass .'" '. $id .'>'. $mitem->name .'</span>';
            break;

            default:	// formerly case 2
            // open in parent window
            $txt = '<a href="'. $mitem->link .'" class="'. $menuclass .'" '. $id .'>'. $mitem->name .'</a>';
            break;
        }

        if ( $params->get( 'menu_images' ) ) {
            $menu_params =& new mosParameters( $mitem->params );
            $menu_image = $menu_params->def( 'menu_image', -1 );
            if ($menu_image AND $menu_image <> '-1') {
                $image = '<img src="'. mamboCore::get('mosConfig_live_site') .'/images/stories/'. $menu_image .'" border="0" alt="'. $mitem->name .'"/>';
                if ( $params->get('menu_images_align')) $txt = $txt .' '. $image;
                else $txt = $image .' '. $txt;
            }
        }
        return $txt;
    }

    /**
	* Vertically Indented Menu
	*/
    function mosShowVIMenu(  &$params ) {
        global $my, $cur_template, $Itemid;
        if (mamboCore::get('mosConfig_shownoauth')) $maxaccess = 9999999;
        else $maxaccess = $my->getAccessGid();
        $rows =& $this->getByParentOrder(0, $params->get('menutype'), $maxaccess);
        foreach ($rows as $i=>$row) $crosslink[$row->id] = $i;
        // indent icons
        $base = mamboCore::get('mosConfig_live_site');
        switch ( $params->get( 'indent_image' ) ) {
            case '1':
            // Default images
            for ( $i = 1; $i < 7; $i++ ) {
                $img[$i] = "<img src=\"$base/images/M_images/indent$i.png\" alt=\"indent$i\" />";
            }
            break;
            case '2':
            // Use Params
            for ( $i = 1; $i < 7; $i++ ) {
                $parm = $params->get('indent_image'. $i);
                if ($parm == '-1' ) $img[$i] = NULL;
                else $img[$i] = "<img src=\"$base/images/M_images/$parm\" alt=\"indent$i\" />";
            }
            break;
            case '3':
            // None
            for ( $i = 1; $i < 7; $i++ ) $img[$i] = NULL;
            break;
            default:
            // Template
            $imgpath = $base.'/templates/'. $cur_template .'/images';
            for ( $i = 1; $i < 7; $i++ ) {
                $img[$i] = "<img src=\"$base/templates/$cur_template/images/indent$i.png\" alt=\"indent$i\" />";
            }
            break;
        }

        $indents = array(
        // block prefix / item prefix / item suffix / block suffix
        array( '<table width="100%" border="0" cellpadding="0" cellspacing="0">', '<tr align="left"><td>' , '</td></tr>', '</table>' ),
        array( '', '<div style="padding-left: 4px">'. $img[1] , '</div>', '' ),
        array( '', '<div style="padding-left: 8px">'. $img[2] , '</div>', '' ),
        array( '', '<div style="padding-left: 12px">'. $img[3] , '</div>', '' ),
        array( '', '<div style="padding-left: 16px">'. $img[4] , '</div>', '' ),
        array( '', '<div style="padding-left: 20px">'. $img[5] , '</div>', '' ),
        array( '', '<div style="padding-left: 24px">'. $img[6] , '</div>', '' ),
        );

        // establish the hierarchy of the menu
        $children = array();
        // first pass - collect children
        foreach ($rows as $v ) $children[$v->parent][] = $v;
        // second pass - collect 'open' menus
        $open = array( $Itemid );
        for ($i = 0; $i < 20 AND isset($crosslink[$open[$i]]) AND isset($rows[$crosslink[$open[$i]]]); $i++) {
            $next = $rows[$crosslink[$open[$i]]]->parent;
            if ($next) $open[$i+1] = $next;
            else break;
        }

        $this->mosRecurseVIMenu( 0, 0, $children, $open, $indents, $params );

    }

    /**
	* Utility function to recursively work through a vertically indented
	* hierarchial menu
	*/
    function mosRecurseVIMenu( $id, $level, &$children, &$open, &$indents, &$params ) {
        global $Itemid;
        if (@$children[$id]) {
            $n = min( $level, count($indents )-1);
            echo "\n".$indents[$n][0];
            foreach ($children[$id] as $row) {
                echo "\n".$indents[$n][1];
                echo $this->mosGetMenuLink( $row, $level, $params, $Itemid );
                // show menu with menu expanded - submenus visible
                if ($params->get('expand_menu') OR in_array($row->id, $open)) $this->mosRecurseVIMenu( $row->id, $level+1, $children, $open, $indents, $params );
                echo $indents[$n][2];
            }
            echo "\n".$indents[$n][3];
        }
    }

    /**
	* Draws a horizontal 'flat' style menu (very simple case)
	*/
    function mosShowHFMenu(  &$params, $style=0 ) {
        global $my, $cur_template, $Itemid;

        if (mamboCore::get('mosConfig_shownoauth')) $maxaccess = 9999999;
        else $maxaccess = $my->getAccessGid();
        $rows =& $this->getByParentOrder(0, $params->get('menutype'), $maxaccess, true);

        $links = array();
        foreach ($rows as $row) $links[] = $this->mosGetMenuLink( $row, 0, $params, $Itemid );
        $menuclass = 'mainlevel'. $params->get( 'class_sfx' );
        if (count( $links )) {
            if ($style == 1) {
                echo '<ul id="'. $menuclass .'">';
                foreach ($links as $link) echo '<li>' . $link . '</li>';
                echo '</ul>';
            }
            else {
                echo '<table width="100%" border="0" cellpadding="0" cellspacing="1">';
                echo '<tr>';
                echo '<td nowrap="nowrap">';
                echo '<span class="'. $menuclass .'"> '. $params->get( 'end_spacer' ) .' </span>';
                echo implode( '<span class="'. $menuclass .'"> '. $params->get( 'spacer' ) .' </span>', $links );
                echo '<span class="'. $menuclass .'"> '. $params->get( 'end_spacer' ) .' </span>';
                echo '</td></tr>';
                echo '</table>';
            }
        }
    }
}

/**
* Plugin handler
* @package Mambo
*/
class mosMambotHandler {
    /** @var array An array of functions in event groups */
    var $_events=null;
    /** @var array An array of lists */
    var $_lists=null;
    /** @var array An array of mambots */
    var $_bots=null;
    /** @var array An array of bools showing if corresponding bot is registered */
    var $_registered=array();
    /** @var int Index of the mambot being loaded */
    var $_loading=null;

    /**
	* Constructor
	*/
    function mosMambotHandler() {
        $my = mamboCore::is_set('currentUser') ? mamboCore::get('currentUser') : null;
        $gid = $my ? $my->gid : 0;
        $this->_events = array();
        $database =& mamboDatabase::getInstance();
        $database->setQuery( "SELECT folder, element, published, params, CONCAT_WS('/',folder,element) AS lookup"
        . "\nFROM #__mambots"
        . "\nWHERE published >= 1 AND access <= $gid"
        . "\nORDER BY ordering"
        );
        $this->_bots = $database->loadObjectList();
        if (!$this->_bots) $this->_bots = array();
    }
    /**
	* Singleton accessor
	*/
    function &getInstance () {
        static $instance;
        if (!is_object($instance)) $instance = new mosMambotHandler();
        return $instance;
    }
    /**
    * Register a class-type mambot, provided it has a perform method
    * - can register for multiple events if desired
    * @param object The mambot object
    * @param mixed string or array of strings - the mambot events to be registered
    * @param int the subscript for use in the main array of mambots
    */
    function _botRegister (&$botObject, &$selected, $i) {
        $function = array(&$botObject, 'perform');
        if (!is_callable($function)) return;
        if (is_array($selected)) foreach ($selected as $select) $this->_botRegister($botObject, $select);
        $this->_events[$selected][] = array ($function, $i);
        $this->_registered[$i] = true;
    }

    /**
	* Loads all the bot files for a particular group
	* @param string The group name, relates to the sub-directory in the mambots directory
	*/
    function loadBotGroup( $group ) {
        global $_MAMBOTS;
        $group = trim( $group );
        $total = 0;
        $basepath = mamboCore::get('mosConfig_absolute_path');
        foreach ($this->_bots as $i=>$bot) {
            if ($bot->folder != $group OR isset($this->_registered[$i])) continue;
            $path = "$basepath/mambots/$bot->folder/$bot->element.php";
            if (file_exists( $path )) {
                $this->_loading = $i;
                require_once( $path );
                if (!isset($this->_registered[$i])) {
                    $botclass = str_replace('.','_',$bot->element);
                    if (class_exists($botclass)) {
                        $newbot = new $botclass();
                        if (is_callable(array(&$newbot, 'register'))) {
                            $selected = $newbot->register();
                            $this->_botRegister($newbot, $selected, $i);
                        }
						unset($newbot);
                    }
                }
                $total++;
            }
        }
        $this->_loading = null;
        if ($total) return true;
        return false;
    }
    /**
	* Registers a function to a particular event group
	* @param string The event name
	* @param string The function name
	*/
    function registerFunction( $event, $function ) {
        $this->_events[$event][] = array( $function, $this->_loading );
        $this->_registered[$this->_loading] = true;
    }
    /**
	* Makes a option for a particular list in a group
	* @param string The group name
	* @param string The list name
	* @param string The value for the list option
	* @param string The text for the list option
	*/
    function addListOption( $group, $listName, $value, $text='' ) {
        $this->_lists[$group][$listName][] = mosHTML::makeOption( $value, $text );
    }
    /**
	* @param string The group name
	* @param string The list name
	* @return array
	*/
    function getList( $group, $listName ) {
        return $this->_lists[$group][$listName];
    }
    /**
	* Calls all functions according to passed parameters
	* @param string The event name
	* @param array An array of arguments
	* @param boolean True is unpublished bots are to be processed
	* @return array An array of results from each function call
	*/
    function &_runBots ($event, $args, $doUnpublished=false) {
        $result = array();
        if (isset( $this->_events[$event] )) {
            foreach ($this->_events[$event] as $func) {
                if (is_callable( $func[0] )) {
                    $botparams = $this->_bots[$func[1]]->params;
                    $args[] = new mosParameters($botparams);
                    $args[] = $event;
                    if ($doUnpublished) {
                        $args[0] = $this->_bots[$func[1]]->published;
                        $result[] = call_user_func_array( $func[0], $args );
                    } else if ($this->_bots[$func[1]]->published) {
                        $result[] = call_user_func_array( $func[0], $args );
                    }
                }
            }
        }
        return $result;
    }
    /**
	* Calls all functions associated with an event group
	* @param string The event name
	* @param array An array of arguments
	* @param boolean True is unpublished bots are to be processed
	* @return array An array of results from each function call
	*/
    function trigger( $event, $args=null, $doUnpublished=false ) {
        if ($args === null) $args = array();
        // prepend the published argument
        if ($doUnpublished) array_unshift( $args, null );
        $result =& $this->_runBots($event, $args, $doUnpublished);
        return $result;
    }
    /**
	* Same as trigger but only returns the first event and
	* allows for a variable argument list
	* @param string The event name
	* @return array The result of the first function call
	*/
    function call( $event ) {
        $args =& func_get_args();
        array_shift( $args );
        $result =& $this->_runBots($event, $args);
        if (isset($result[0])) return $result[0];
        return null;
    }
    
    function getBot($element, $folder) {
        $returnBot = '';
        foreach ($this->_bots as $i=>$bot) {
            if ($bot->folder == $folder && $bot->element == $element){
                $returnBot = $bot;
                break;
            }
        }
        return $returnBot;
    }
}

/**
* Users Table Class
*
* Provides access to the mos_templates table
* @package Mambo
*/
class mosUser extends mosDBTable {
    /** @var int Unique id*/
    var $id=null;
    /** @var string The users real name (or nickname)*/
    var $name=null;
    /** @var string The login name*/
    var $username=null;
    /** @var string email*/
    var $email=null;
    /** @var string MD5 encrypted password*/
    var $password=null;
    /** @var string */
    var $usertype=null;
    /** @var int */
    var $block=null;
    /** @var int */
    var $sendEmail=null;
    /** @var int The group id number */
    var $gid=null;
    /** @var int Group number from ACL */
    var $grp=null;
    /** @var datetime */
    var $registerDate=null;
    /** @var datetime */
    var $lastvisitDate=null;
    /** @var string activation hash*/
    var $activation=null;
    /** @var string */
    var $params=null;

    /**
	* @param database A database connector object
	*/
    function mosUser() {
        $database =& mamboDatabase::getInstance();
        $this->mosDBTable( '#__users', 'id', $database );
    }

    /**
	 * Return true if this user is an administrator, false otherwise
	 */
    function isAdmin() {
        return ( strtolower( $this->usertype ) == 'superadministrator' OR strtolower( $this->usertype ) == 'super administrator' OR (isset($this->grp) AND $this->grp == 16) ) ? true : false;
    }

    /**
	 * Fill a user object with information from the current session
	 */
    function getSessionData() {
        $session =& mosSession::getCurrent();
        $this->id = intval( $session->userid );
        $this->username = $session->username;
        $this->usertype = $session->usertype;
        $this->gid = intval ($session->gid);
    }

    function getSession () {
        $this->id = mosGetParam( $_SESSION, 'session_user_id', 0 );
        $this->username = mosGetParam( $_SESSION, 'session_username', '' );
        $this->usertype = mosGetParam( $_SESSION, 'session_usertype', '' );
        $this->gid = mosGetParam( $_SESSION, 'session_gid', 0 );
        $this->grp = mosGetParam( $_SESSION, 'session_grp', 0);
    }
    /**
	 * User access level
	 */
	function getAccessGid() {
		static $access;
		if (!isset($access)) {
			$acl = new gacl;
			$access = $this->id > 0 ? 1 : 0;
			$access += $acl->acl_check( 'action', 'access', 'users', $this->usertype, 'frontend', 'special' );
		}
		return $access;
	}
    /**
	 * Validation and filtering
	 * @return boolean True is satisfactory
	 */
    function check() {
    	Global $mosConfig_absolute_path;
    	//include $mosConfig_absolute_path . ('/language/english.php');
        $this->_error = '';
        if ($this->name == '') $this->_error = _REGWARN_NAME;
        elseif ($this->username == '') $this->_error = _REGWARN_UNAME;
        elseif (strlen($this->username) < 3 OR preg_match("/[\\<\\>\\\"\\'\\%\\;\\(\\)\\&\\+\\-]/", $this->username)) $this->_error = sprintf( _VALID_AZ09, _PROMPT_UNAME, 2 );
        elseif (($this->email == '') OR preg_match("/[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}/", $this->email ) == 0) $this->_error = _REGWARN_MAIL;
        else {
            // check for existing username
            $username = strtolower($this->username);
            $this->_db->setQuery( "SELECT COUNT(id) FROM #__users "
            . "\nWHERE LOWER(username)='$username' AND id!='$this->id'"
            );
            if ($this->_db->loadResult()) $this->_error = _REGWARN_INUSE;
            elseif (mamboCore::get('mosConfig_uniquemail')) {
                // check for existing email
                $this->_db->setQuery( "SELECT COUNT(id) FROM #__users "
                . "\nWHERE email='$this->email' AND id!='$this->id'"
                );
                if ($this->_db->loadResult()) $this->_error = _REGWARN_EMAIL_INUSE;
            }
        }
        if ($this->_error) return false;
        return true;
    }

    function store( $updateNulls=false ) {
        global $acl, $migrate;
        $section_value = 'users';
        if( $this->id AND !$migrate) {
            // update existing record
            $ret = $this->_db->updateObject( $this->_tbl, $this, 'id', $updateNulls );
            // syncronise ACL
            // single group handled at the moment
            // trivial to expand to multiple groups
            $groups = $acl->get_object_groups( $section_value, $this->id, 'ARO' );
            $acl->del_group_object( $groups[0], $section_value, $this->id, 'ARO' );
            $acl->add_group_object( $this->gid, $section_value, $this->id, 'ARO' );
            $object_id = $acl->get_object_id( $section_value, $this->id, 'ARO' );
            $acl->edit_object( $object_id, $section_value, $this->_db->getEscaped( $this->name ), $this->id, 0, 0, 'ARO' );
        }
        else {
            // new record
            $ret = $this->_db->insertObject( $this->_tbl, $this, 'id' );
            // syncronise ACL
            $acl->add_object( $section_value, $this->_db->getEscaped( $this->name ), $this->id, null, null, 'ARO' );
            $acl->add_group_object( $this->gid, $section_value, $this->id, 'ARO' );
        }
        if ($ret) return true;
        $this->_error = "mosUser::store failed <br />" . $this->_db->getErrorMsg();
        return false;
    }

    function delete($oid=null) {
        global $acl;
        $k = $this->_tbl_key;
        if ($oid) $this->id = intval( $oid );
        $aro_id = $acl->get_object_id( 'users', $this->$k, 'ARO' );
        $acl->del_object( $aro_id, 'ARO', true );
        //		$authoriser = mosAuthorisationAdmin::getInstance();
        //		$authoriser->dropAccess('mosUser', $this->id);
        $this->_error = '';
        $this->_db->setQuery( "DELETE FROM $this->_tbl WHERE id = '".$this->id."'" );
        if ($this->_db->query()) {
            // cleanup related data

            // :: private messaging
            $this->_db->setQuery( "DELETE FROM #__messages_cfg WHERE user_id='".$this->id."'" );
            if (!$this->_db->query()) $this->_error = $this->_db->getErrorMsg();
            else {
                $this->_db->setQuery( "DELETE FROM #__messages WHERE user_id_to='".$this->$k."'" );
                if (!$this->_db->query()) $this->_error = $this->_db->getErrorMsg();
            }
        } else $this->_error = $this->_db->getErrorMsg();
        if ($this->_error) return false;
        return true;
    }
}

/**
* User login details class
* @package Mambo
*/
class mosLoginDetails {
    var $_user = '';
    var $_password = '';
    var $_remember = '';

    function mosLoginDetails ($user, $password='', $remember='') {
        $this->_user = $user;
        $this->_password = $password;
        $this->_remember = $remember;
    }

    function getUser () {
        return $this->_user;
    }

    function getPassword () {
        return $this->_password;
    }

    function getRemember () {
        return $this->_remember;
    }

}
/**
* Mambo Mainframe class
*
* Provide many supporting API functions
* @package Mambo
*/
class mosMainFrame {
    /** @var database Internal database class pointer */
    var $_db=null;
    /** @var object A default option (e.g. component) */
    var $_option=null;
    /** @var string The current template */
    var $_template=null;
    /** @var array An array to hold global user state within a session */
    var $_userstate=null;
    /** @var array An array of page meta information */
    var $_head=null;
    /** @var string Custom html string to append to the pathway */
    var $_custom_pathway=array();

    /**
	* Class constructor
	* @param database A database connection object
	* @param string The url option
	* @param string The path of the mos directory
	*/
    function mosMainFrame( &$db, $option, $basePath, $isAdmin=false ) {
        $this->_db =& $db;
        // load the configuration values
        //return( $this->loadConfig() );
        $this->_setTemplate($isAdmin);
        if (substr($option,0,4) != 'com_') $this->_option = "com_$option";
        else $this->_option = $option;
        if (isset( $_SESSION['session_userstate'] )) $this->_userstate =& $_SESSION['session_userstate'];
        else $this->_userstate = null;
        $this->_head['title'] = $GLOBALS['mosConfig_sitename'];
        $this->_head['meta'] = array();
        $this->_head['custom'] = array();
        mosMainFrame::getInstance($this);
    }

    /**
    * Get the current user - deprecated - use mamboCore instead
    */
    function getUser() {
        return mamboCore::get('currentUser');
    }
    /**
    * Logout the current user - deprecated - use the code here directly
    */
    function logout() {
        require_once(mamboCore::get('mosConfig_absolute_path').'/includes/authenticator.php');
        $authenticator =& mamboAuthenticator::getInstance();
        $authenticator->logoutUser();
    }
    /**
    * Login a user given name and password - deprecated - use the code here directly
    */
    function login ($username=null,$passwd=null) {
        require_once(mamboCore::get('mosConfig_absolute_path').'/includes/authenticator.php');
        $authenticator =& mamboAuthenticator::getInstance();
        return $authenticator->loginUser($username, $passwd);
    }

    /**
	* @param object
	*/
	function &getInstance () {
	    global $mainframe;
	    if (isset($mainframe)) {
	        return $mainframe;
	    } else {
	        $result = null;
	        return $result;
	    }
	}

    /**
	* @param string
	*/
    function setPageTitle( $title=null ) {
        if (mamboCore::get('mosConfig_pagetitles')) {
            $title = trim(htmlspecialchars($title));
            $base = mamboCore::get('mosConfig_sitename');
            $this->_head['title'] = $title ?  $title.' - '.$base : $base;
        }
    }
    /**
	* @return string
	*/
    function getPageTitle() {
        return $this->_head['title'];
    }

    /**
	* @param string The value of the name attibute
	* @param string The value of the content attibute
	* @param string Text to display before the tag
	* @param string Text to display after the tag
	*/
    function addMetaTag( $name, $content, $prepend='', $append='' ) {
        list($name, $content) = $this->_tidyMetaData($name, $content);
        $prepend = trim($prepend);
        $append = trim($append);
        $this->_head['meta'][$name] = array($content, $prepend, $append);
    }
    /**
	* @param string The value of the name attibute
	*/
    function _getMetaTag ($name) {
        return isset($this->_head['meta'][$name]) ?  $this->_head['meta'][$name] : array('', '', '');
    }
    /**
	* @param string The value of the name attibute
	* @param string The value of the content attibute to append to the existing
	*/
    function _tidyMetaData($name, $content) {
        $result[] = trim(htmlspecialchars($name));
        $result[] = trim(htmlspecialchars($content));
        return $result;
    }
    /**
	* @param string The value of the name attibute
	* @param string The value of the content attibute to append to the existing
	* Tags ordered in with Site Keywords and Description first
	*/
    function appendMetaTag( $name, $content, $ifEmpty=false ) {
        list($name, $content) = $this->_tidyMetaData($name, $content);
        $tag = $this->_getMetaTag($name);
        if ($tag[0] AND $ifEmpty) return;
        if ($tag[0] AND $content) $content .= ', ';
        $tag[0] = $content.$tag[0];
        $this->_head['meta'][$name] =  $tag;
    }

    /**
	* @param string The value of the name attibute
	* @param string The value of the content attibute to append to the existing
	*/
    function prependMetaTag( $name, $content ) {
        list($name, $content) = $this->_tidyMetaData($name, $content);
        $tag = $this->_getMetaTag($name);
        $tag[0] = $content.$tag[0];
        $this->_head['meta'][$name] =  $tag;
    }
    /**
	 * Adds a custom html string to the head block
	 * @param string The html to add to the head
	 */
    function addCustomHeadTag( $html ) {
        $this->_head['custom'][] = trim( $html );
    }
    /**
	* @return string
	*/
    function getHead() {
        $head[] = '<title>'.$this->_head['title'].'</title>';
        foreach ($this->_head['meta'] as $name=>$meta) {
            if ($meta[1]) $head[] = $meta[1];
            $head[] = '<meta name="' . $name . '" content="' . $meta[0] . '" />';
            if ($meta[2]) $head[] = $meta[2];
        }
        foreach ($this->_head['custom'] as $html) $head[] = $html;
        return implode( "\n", $head )."\n";
    }
    /**
	* @return string
	*/
    function getCustomPathWay() {
        return $this->_custom_pathway;
    }

    function appendPathWay($html) {
        $this->_custom_pathway[] = $html;
    }

    /**
	* Gets the value of a user state variable
	* @param string The name of the variable
	*/
    function getUserState( $var_name ) {
        return is_array($this->_userstate) ? mosGetParam($this->_userstate, $var_name, null) : null;
    }
    /**
	* Sets the value of a user state variable
	* @param string The name of the variable
	* @param string The value of the variable
	*/
    function setUserState( $var_name, $var_value ) {
        if (is_array( $this->_userstate )) $this->_userstate[$var_name] = $var_value;
    }
    /**
	* Gets the value of a user state variable
	* @param string The name of the user state variable
	* @param string The name of the variable passed in a request
	* @param string The default value for the variable if not found
	*/
    function getUserStateFromRequest( $var_name, $req_name, $var_default=null ) {
		if (is_array($this->_userstate)) {
			if (isset($_REQUEST[$req_name])) $this->setUserState($var_name, $_REQUEST[$req_name]);
			else if (isset($var_default) AND !isset($this->_userstate[$var_name])) $this->setUserState($var_name, $var_default);
			return $this->_userstate[$var_name];
		} else {
			return null;
		}
    }
    /**
	* Initialises the user session
	*
	* Old sessions are flushed based on the configuration value for the cookie
	* lifetime. If an existing session, then the last access time is updated.
	* If a new session, a session id is generated and a record is created in
	* the mos_sessions table.
	*/
    function &initSession() {
        $session =& mosSession::getCurrent();
        return $session;
    }

    /**
	* @param string The name of the variable (from configuration.php)
	* @return mixed The value of the configuration variable or null if not found
	*/
    function getCfg( $varname ) {
        return mamboCore::get('mosConfig_'.$varname);
    }

    function _setTemplate( $isAdmin=false ) {
        global $Itemid;
        $cur_template = '';
        $sql = "SELECT template, client_id, menuid FROM #__templates_menu WHERE (client_id=0 or client_id=1)";
        if (isset($Itemid) AND $Itemid) $sql .= " AND (menuid=0 OR menuid=$Itemid)";
        else $sql .= " AND menuid=0";
        $sql .= " ORDER BY client_id, menuid";
        $this->_db->setQuery($sql);
        $templates = $this->_db->loadObjectList();
        foreach ($templates as $template) {
            if ($template->client_id == 1) {
                if ($isAdmin) $cur_template = $template->template;
            }
            else $cur_template = $template->template;
        }
        if ($isAdmin) {
            $path = mamboCore::get('mosConfig_absolute_path')."/administrator/templates/$cur_template/index.php";
            if (!file_exists( $path )) $cur_template = 'mambo_admin';
        }
        else {
            // TemplateChooser Start
            $mos_user_template = mosGetParam( $_COOKIE, 'mos_user_template', '' );
            $mos_change_template = mosGetParam( $_REQUEST, 'mos_change_template', $mos_user_template );
            if ($mos_change_template) {
                // check that template exists in case it was deleted
                $path = mamboCore::get('mosConfig_absolute_path')."/templates/$mos_change_template/index.php";
                if (strpos($mos_change_template,'..') == false AND strpos($mos_change_template,':') == false AND file_exists($path)) {
                    $lifetime = 60*10;
                    $cur_template = $mos_change_template;
                    setcookie( "mos_user_template", "$mos_change_template", time()+$lifetime);
                } else 	setcookie( "mos_user_template", "", time()-3600 );
            }
            // TemplateChooser End
        }
        $this->_template = $cur_template;
    }

    function getTemplate() {
        return $this->_template;
    }

    /**
	* Checks to see if an image exists in the current templates image directory
 	* if it does it loads this image.  Otherwise the default image is loaded.
	* Also can be used in conjunction with the menulist param to create the chosen image
	* load the default or use no image
	*/
    function ImageCheck( $file, $directory='/images/M_images/', $param=NULL, $param_directory='/images/M_images/', $alt=NULL, $name='image', $type=1, $align='middle' ) {
        $basepath = mamboCore::get('mosConfig_live_site');
        if ($param) $image = $basepath.$param_directory.$param;
        else {
            $endpath = '/templates/'.$this->getTemplate().'/images/'.$file;
            if (file_exists(mamboCore::get('mosConfig_absolute_path').$endpath)) $image = $basepath.$endpath;
            else $image = $basepath.$directory.$file;  // outputs only path to image
        }
        // outputs actual html <img> tag
        if ($type) $image = '<img src="'. $image .'" alt="'. $alt .'" align="'. $align .'" name="'. $name .'" border="0" />';
        return $image;
    }

    /**
	* Returns the first to be found of one or more files, or null
	*
	*/
    function tryFiles ($first_choice, $second_choice=null, $third_choice=null) {
        if (file_exists($first_choice)) return $first_choice;
        elseif ($second_choice AND file_exists($second_choice)) return $second_choice;
        elseif ($third_choice AND file_exists($third_choice)) return $third_choice;
        else return null;
    }

    /**
	* Returns a standard path variable
	*
	*/
    function getPath( $varname, $option='' ) {
        $base = mamboCore::get('mosConfig_absolute_path');
        $origoption = $option;
        if (!$option) $option = $this->_option;
        $name = substr($option,4);
        $bac_admin = "$base/administrator/components/com_admin/";
        $baco = "$base/administrator/components/$option/";
        $bttc = "$base/templates/$this->_template/components/";
        $bco = "$base/components/$option/";
        $bai = "$base/administrator/includes/";
        $bi = "$base/includes/";

        switch ($varname) {
            case 'front': return $this->tryFiles ($bco."$name.php");
            case 'front_html': return $this->tryFiles ($bttc."$name.html.php", $bco."$name.html.php");
            case 'admin': return $this->tryFiles ($baco."admin.$name.php", $bac_admin.'admin.admin.php');
            case 'admin_html': return $this->tryFiles ($baco."admin.$name.html.php", $bac_admin.'admin.admin.html.php');
            case 'toolbar': return $this->tryFiles ($baco."toolbar.$name.php");
            case 'toolbar_html': return $this->tryFiles ($baco."toolbar.$name.html.php");
            case 'toolbar_default': return $this->tryFiles ($bai.'toolbar.html.php');
            case 'class': return $this->tryFiles ($bco."$name.class.php", $baco."$name.class.php", $bi."$name.php");
            case 'com_xml': return $this->tryFiles ($baco."$name.xml", $bco."$name.xml");
            case 'mod0_xml':
            if ($origoption) $path = $base."/modules/$option.xml";
            else $path = $base.'/modules/custom.xml';
            return $this->tryFiles ($path);
            case 'mod1_xml':
            if ($origoption) $path = $base."/administrator/modules/$option.xml";
            else $path = $base.'/administrator/modules/custom.xml';
            return $this->tryFiles ($path);
            case 'bot_xml': return $this->tryFiles ($base."/mambots/$option.xml");
            case 'menu_xml': return $this->tryFiles ($base."/administrator/components/com_menus/$option/$option.xml");
            case 'installer_html': return $this->tryFiles($base."/administrator/components/com_installer/$option/$option.html.php");
            case 'installer_class': return $this->tryFiles($base."/administrator/components/com_installer/$option/$option.class.php");
        }
    }

    /**
	* Detects a 'visit'
	*
	* This function updates the agent and domain table hits for a particular
	* visitor.  The user agent is recorded/incremented if this is the first visit.
	* A cookie is set to mark the first visit.
	*/
    function detect() {
        if (mamboCore::get('mosConfig_enable_stats') == 1) {
            if (mosGetParam( $_COOKIE, 'mosvisitor', 0 )) return;
            setcookie( "mosvisitor", "1" );

            $agent = $_SERVER['HTTP_USER_AGENT'];
            $browser = mosGetBrowser( $agent );
            $os = mosGetOS( $agent );
            $domain = gethostbyaddr( $_SERVER['REMOTE_ADDR'] );
            // tease out the last element of the domain
            $tldomain = split( "\.", $domain );
            $tldomain = $tldomain[count( $tldomain )-1];
            if (is_numeric( $tldomain )) {
                $tldomain = "Unknown";
            }

            $this->_db->setQuery( "SELECT count(*), type FROM #__stats_agents WHERE (agent='$browser' AND type=0) OR (agent='$os' AND type=1) OR (agent='$tldomain' AND type=2) GROUP BY type");
            $stats = $this->_db->loadObjectList();
            $sql['browser'] = "INSERT INTO #__stats_agents (agent,type) VALUES ('$browser',0)";
            $sql['os'] = "INSERT INTO #__stats_agents (agent,type) VALUES ('$os',1)";
            $sql['domain'] = "INSERT INTO #__stats_agents (agent,type) VALUES ('$tldomain',2)";
            if ($stats) foreach ($stats as $stat) {
                if ($stat->type == 0) $sql['browser'] = "UPDATE #__stats_agents SET hits=(hits+1) WHERE agent='$browser' AND type=0";
                if ($stat->type == 1) $sql['os'] = "UPDATE #__stats_agents SET hits=(hits+1) WHERE agent='$os' AND type=1";
                if ($stat->type == 2) $sql['domain'] = "UPDATE #__stats_agents SET hits=(hits+1) WHERE agent='$tldomain' AND type=2";
            }
            $this->_db->setQuery(implode('; ',$sql));
            $this->_db->query_batch();
        }
    }

    /**
	* @return correct Itemid for Content Item
	*/
    function getItemid ($id, $typed=1, $link=1, $bs=1, $bc=1, $gbs=1) {
        require_once(mamboCore::get('mosConfig_absolute_path').'/components/com_content/content.class.php');
        $handler =& contentHandler::getInstance();
        return $handler->getItemid($id, $typed, $link, $bs, $bc, $gbs);
    }

    function liveBookMark () {
        // support for Firefox Live Bookmarks ability for site syndication
        $live_bookmark = 0;
        $c_handler =& mosComponentHandler::getInstance();
        $params =& $c_handler->getParamsByName('Syndicate');
        if (!is_null($params)){
           $live_bookmark = $params->get( 'live_bookmark', 0 );
        }
        
        if ($live_bookmark) {
            // custom bookmark file name
            $bookmark_file = $params->get( 'bookmark_file', $live_bookmark );
            $link_file 	= mamboCore::get('mosConfig_live_site').'/cache/'. $bookmark_file;
            $filename 	= mamboCore::get('mosConfig_absolute_path').'/cache/'. $bookmark_file;
            $cache 		= $params->get( 'cache', 1 );
            $cache_time = $params->get( 'cache_time', 3600 );
            $title 		= $params->def( 'title', mamboCore::get('mosConfig_sitename') );
            // checks to see if cache file exists, to determine whether to create a new one
            if ( !file_exists( $filename ) || ( ( time() - filemtime( $filename ) ) > $cache_time ) ) {
                $task		= 'live_bookmark';
                // sets bookmark feed type
                $_GET['feed'] = str_replace( '.xml', '', $live_bookmark );
                // loads rss component to create bookmark file
                require_once( mamboCore::get('mosConfig_absolute_path').'/components/com_rss/rss.php' );
            }
            // outputs link tag for page
			?>
			<link rel="alternate" type="application/rss+xml" title="<?php echo $title; ?>" href="<?php echo $link_file; ?>" />
			<?php
        }
    }
    /**
	* Render head tags
	*   tags are assembled into an associative array with the following elements:
	*     - title
	*     - meta
	*     - mambojavascript
	*     - custom (custom head tags)
	*     - livebookmark
	*     - favicon
	* @param unknown keys - array elements to output (null = output all)
	* @param unknown exclude - array elements to exclude in output
	*
	* Usage: 	mosShowHead() - to render all tags
	*			mosShowHead('title') - to render a single tag
	*			mosShowHead(array('title', 'meta')) - to selectively render tags (in order)
	*			mosShowHead(null, 'custom') - to exclude a single tag
	*			mosShowHead(null, array('custom','favicon')) - to exclude multiple tags
	*/
    function mosShowHead($keys='', $exclude='') {
		if (!is_array($keys))
			if ($keys !== '' && !is_null($keys))
				$keys = array($keys);
			else $keys = array();
		if (!is_array($exclude))
			if ($exclude !== '')
				$exclude = array($exclude);
			else $exclude = array();

		$this->_head['output'] = array();

		$head = array();;
		$head['title'] = '<title>'.$this->_head['title'].'</title>';

        $this->appendMetaTag( 'description', mamboCore::get('mosConfig_MetaDesc'), true );
        $this->appendMetaTag( 'keywords', mamboCore::get('mosConfig_MetaKeys'), true );
		$head['meta'] = array();
        foreach ($this->_head['meta'] as $name=>$meta) {
            if ($meta[1]) $head['meta'][] = $meta[1];
            $head['meta'][] = '<meta name="' . $name . '" content="' . $meta[0] . '" />';
            if ($meta[2]) $head['meta'][] = $meta[2];
        }
		$head['meta'] = implode( "\n", $head['meta'] );

        $my = mamboCore::get('currentUser');
		$head['mambojavascript'] = $my->id ? '<script type="text/javascript" src="'.mamboCore::get('mosConfig_live_site').'/includes/js/mambojavascript.js"></script>' : '';

		$head['custom'] = array();
		foreach ($this->_head['custom'] as $html)
			if (trim($html) !== '')
				$head['custom'][] = $html;
		if (count($head['custom']) !== 0)
			$head['custom'] = implode( "\n", $head['custom'] );
		else
			$head['custom'] = '';

		ob_start();
        $this->liveBookMark();
		$head['livebookmark'] = ob_get_contents();
		ob_end_clean();

		$configuration =& mamboCore::getMamboCore();
		$head['favicon'] = "<link rel=\"shortcut icon\" href=\"".$configuration->getFavIcon()."\" />";

		foreach($head as $key=>$value)
				$this->_head['output'][$key] = "$value";

		$tags = $this->_head['output'];
		if (count($keys) == 0) {
			foreach($tags as $key=>$value) 
				if (!in_array($key, $exclude))
					if ($value !== '')
						echo trim($value)."\n";
		} else {
			foreach($keys as $key)
				if (isset($tags[$key])) 
					if(trim($tags[$key]) !== '')
						echo trim($tags[$key])."\n";
		}
	}

    /**
	* retained for backward compatability
	*/
	function getBlogSectionCount() {
		require_once(mamboCore::get('mosConfig_absolute_path').'/components/com_content/content.class.php');
		$handler =& new contentHandler();
		return $handler->getBlogSectionCount();
	}

	function getBlogCategoryCount() {
		require_once(mamboCore::get('mosConfig_absolute_path').'/components/com_content/content.class.php');
		$handler =& new contentHandler();
		return $handler->getBlogCategoryCount();
	}

	function getGlobalBlogSectionCount() {
		require_once(mamboCore::get('mosConfig_absolute_path').'/components/com_content/content.class.php');
		$handler =& new contentHandler();
		return $handler->getGlobalBlogSectionCount();
	}

	function getStaticContentCount() {
		require_once(mamboCore::get('mosConfig_absolute_path').'/components/com_content/content.class.php');
		$handler =& new contentHandler();
		return $handler->getStaticContentCount();
	}

	function getContentItemLinkCount() {
		require_once(mamboCore::get('mosConfig_absolute_path').'/components/com_content/content.class.php');
		$handler =& new contentHandler();
		return $handler->getContentItemLinkCount();
	}
	/**
	* retained for backward compatability
	*/

}

/**
* Class to support function caching
* @package Mambo
*/
class mosCache {
    /**
	* @return object A function cache object
	*/
    function &getCache(  $group=''  ) {
        $mosConfig_absolute_path = mamboCore::get('mosConfig_absolute_path');
        require_once($mosConfig_absolute_path.'/includes/Cache/Lite/Function.php');
        $path = mamboCore::get('mosConfig_cachepath');
        $caching = mamboCore::get('mosConfig_caching');
        $time = mamboCore::get('mosConfig_cachetime');
        $options = array(
        'cacheDir' => "$path/",
        'caching' => $caching,
        'defaultGroup' => $group,
        'lifeTime' => $time
        );
        $cache =& new Cache_Lite_Function( $options );
        return $cache;
    }
    /**
	* Cleans the cache
	*/
    function cleanCache ($group=false) {
        if (mamboCore::get('mosConfig_caching')) {
            $cache =& mosCache::getCache( $group );
            $cache->clean( $group );
        }
    }
}

/**
* Session database table class
* @package Mambo
*/
class mosSession extends mosDBTable {
    /** @var int Primary key */
    var $session_id=null;
    /** @var time */
    var $time=null;
    /** @var int User ID */
    var $userid=0;
    /** @var string */
    var $usertype=null;
    /** @var string */
    var $username='';
    /** @var int User group ID */
    var $gid=0;
    /** @var int */
    var $guest=1;
    /** @var string */
    var $_session_cookie=null;

    /**
	* @param database A database connector object
	*/
    function mosSession() {
        $database =& mamboDatabase::getInstance();
        $this->mosDBTable( '#__session', 'session_id', $database );
        $this->time = time();
    }

    function validate ($user) {
        // check against db record of session
        $session_id = mosGetParam( $_SESSION, 'session_id', '' );
        $logintime = mosGetParam( $_SESSION, 'session_logintime', '' );
        if ($session_id == md5( $user->id.$user->username.$user->usertype.$logintime )) {
            $current_time = time();
            $database =& mamboDatabase::getInstance();
            $database->setQuery ("UPDATE #__session"
            . "\nSET time='$current_time', guest=-3-guest"
            . "\nWHERE session_id='$session_id'"
            . " AND username = '" . $database->getEscaped( $user->username ) . "'"
            . " AND userid = " . intval( $user->id )
            );
            if (!$result = $database->query()) echo $database->stderr();
            elseif ($database->getAffectedRows() == 1) return true;
        }
        return false;
    }

    function &getCurrent () {
        static $currentSession;
        if (!is_object($currentSession)) {
            $currentSession = new mosSession();
            mosSession::purge();
            $sessionCookieName = md5('site'.mamboCore::get('mosConfig_live_site'));
            $sessioncookie = mosGetParam($_COOKIE, $sessionCookieName, null);
            $usercookie = mosGetParam($_COOKIE, 'usercookie', null);
            if ($currentSession->load(md5($sessioncookie.$_SERVER['REMOTE_ADDR']))) {
                // Session cookie exists, update time in session table
                $currentSession->time = time();
                $currentSession->update();
            } else {
                $currentSession->generateId();
                if (!$currentSession->insert()) {
                    die( $currentSession->getError() );
                }
                setcookie( $sessionCookieName, $currentSession->getCookie(), time() + 43200, '/' );
                //$_COOKIE["sessioncookie"] = $session->getCookie();
                if ($usercookie) {
                    // Remember me cookie exists. Login with usercookie info.
                    require_once (mamboCore::get('mosConfig_absolute_path').'/includes/authenticator.php');
                    $authenticator =& mamboAuthenticator::getInstance();
                    $authenticator->authenticateUser ($message, $usercookie['username'], $usercookie['password'], null, $currentSession);
                }
            }
        }
        return $currentSession;
    }

    function insert() {
        $ret = $this->_db->insertObject( $this->_tbl, $this );

        if( !$ret ) {
            $this->_error = strtolower(get_class( $this ))."::store failed <br />" . $this->_db->stderr();
            return false;
        } else {
            return true;
        }
    }

    function update( $updateNulls=false ) {
        $ret = $this->_db->updateObject( $this->_tbl, $this, 'session_id', $updateNulls );

        if( !$ret ) {
            $this->_error = strtolower(get_class( $this ))."::store failed <br />" . $this->_db->stderr();
            return false;
        } else {
            return true;
        }
    }

    function generateId() {
        $failsafe = 20;
        $randnum = 0;
        while ($failsafe--) {
            $randnum = md5( uniqid( microtime(), 1 ) );
            if ($randnum != "") {
                $cryptrandnum = md5( $randnum );
                $this->_db->setQuery( "SELECT $this->_tbl_key FROM $this->_tbl WHERE $this->_tbl_key=MD5('$randnum')" );
                if(!($result = $this->_db->query())) {
                    die( $this->_db->stderr( true ));
                    // todo: handle gracefully
                }
                if ($this->_db->getNumRows($result) == 0) {
                    break;
                }
            }
        }
        $this->_session_cookie = $randnum;
        $this->session_id = md5( $randnum . $_SERVER['REMOTE_ADDR'] );
    }

    function getCookie() {
        return $this->_session_cookie;
    }

    function purge() {
        $past = time() - intval(mamboCore::get('mosConfig_lifetime'));
        $adminpast = time() - 3600;
        $database =& mamboDatabase::getInstance();
        $database->setQuery("DELETE FROM #__session WHERE (time<$past AND guest>=0) OR (time<$adminpast AND guest<0)");
        return $database->query();
    }

}

/**
* Parameters handler
* @package Mambo
*/
class mosParameters {
    /** @var object */
    var $_params = null;
    /** @var string The raw params string */
    var $_raw = null;
    /**
* Constructor
* @param string The raw parms text
* @param string Path to the xml setup file
* @var string The type of setup file
*/
    function mosParameters( $text, $process_sections = false) {
        $this->_params = $this->parse( $text, $process_sections );
        $this->_raw = $text;
    }
    /**
* Get the result of parsing the string provided on creation
* @return string parsed result
*/
    function getParams () {
        return $this->_params;
    }
    /**
* @param string The name of the param
* @param string The value of the parameter
* @return string The set value
*/
    function set( $key, $value='' ) {
        $this->_params->$key = $value;
        return $value;
    }
    /**
* Sets a default value if not alreay assigned
* @param string The name of the param
* @param string The value of the parameter
* @return string The set value
*/
    function def( $key, $value='' ) {
        return $this->set( $key, $this->get( $key, $value ) );
    }
    /**
* @param string The name of the param
* @param mixed The default value if not found
* @return string
*/
    function get( $key, $default='' ) {
        if (isset( $this->_params->$key )) return $this->_params->$key === '' ? $default : $this->_params->$key;
        else return $default;
    }
    /**
* Look to see if string is bracketed by opener and closer
* If so, extract and trim the bracketed string
* Otherwise, return a null string
**/
    function getBracketed ($text, $opener, $closer) {
        if (strlen($text) > 1 AND ($text[0] != $opener OR substr($text,-1) != $closer)) return '';
        else return trim(substr($text,1,-1));
    }
    /**
* Parse an .ini string, based on phpDocumentor phpDocumentor_parse_ini_file function
* @param mixed The ini string or array of lines
* @param boolean add an associative index for each section [in brackets]
* @return object
*/
    function parse( $txt, $process_sections = false ) {
        $result = new stdClass();
        if (is_string($txt)) $lines = explode( "\n", $txt );
        elseif (is_array($txt)) $lines = $txt;
        else return $result;

        $sec_name = '';
        $unparsed = 0;

        foreach ($lines as $line) {
            // ignore comments and null lines
            $line = trim($line);
            if (strlen($line) == 0 OR $line[0] == ';') continue;

            if ($sec_name = $this->getBracketed($line, '[', ']')) {
                if ($process_sections) $result->$sec_name = new stdClass();
                continue;
            }

            if (count($propsetter = explode ('=', $line, 2)) == 2) {
                $property = trim($propsetter[0]);
                if ($pquoted = $this->getBracketed($property, '"', '"')) $property = stripcslashes($pquoted);
                $value = trim($propsetter[1]);
                if ($value == 'false') $value = false;
                elseif ($value == 'true') $value = true;
                else if ($vquoted = $this->getBracketed($value, '"', '"')) $value = stripcslashes($vquoted);
                if ($process_sections AND $sec_name) $result->$sec_name->$property = $value;
                else $result->$property = $value;
            }
            else {
                $property = '__invalid' . $unparsed++ . '__';
                if ($process_sections AND $sec_name) $result->$sec_name->$property = $line;
                else $result->$property = $line;
            }
        }
        return $result;
    }
    /**
	* @param string The name of the control, or the default text area if a setup file is not found
	* @return string HTML
	*/
    function render( $name='params' ) {
        if (is_file($this->_path)) {
            $parser = new mosXMLParams ($this->_path, $this, $name);
            if (count($parser->html)) return implode("\n", $parser->html);
        }
        $raw = $this->_raw;
        return "<textarea name='$name' cols='40' rows='10' class='text_area'>$raw</textarea>";
    }

    /**
	* special handling for textarea param
	*/
    function textareaHandling( &$txt ) {
        foreach ($txt as $key=>$value) $txt[$key] = str_replace("\n", '<br />', $value);
        return implode( "\n", $txt );
    }
}

/**
* Page generation time
* @package Mambo
*/
class mosProfiler {
    var $start=0;
    var $prefix='';

    function mosProfiler( $prefix='' ) {
        $this->start = $this->getmicrotime();
        $this->prefix = $prefix;
    }

    function mark( $label ) {
        return sprintf ( "\n<div class=\"profiler\">$this->prefix %.3f $label</div>", $this->getmicrotime() - $this->start );
    }

    function getmicrotime(){
        list($usec, $sec) = explode(" ",microtime());
        return ((float)$usec + (float)$sec);
    }
}


/**
 * @author Mikolaj Jedrzejak <mikolajj@op.pl>
 * @copyright Copyright Mikolaj Jedrzejak (c) 2003-2004
 * @version 1.0 2004-07-27 00:37
 * @link http://www.unicode.org Unicode Homepage
 * @link http://www.mikkom.pl My Homepage
 * 
 **/
$PATH_TO_CLASS = dirname(ereg_replace("\\\\","/",__FILE__)) . "/" . "ConvertTables" . "/";
@require_once($PATH_TO_CLASS."/charsetmapping.php");
define ("CONVERT_TABLES_DIR", $PATH_TO_CLASS);
define ("DEBUG_MODE", 1);

/**
 * -- 1.0 2004-07-28 --
 * 
 * -- The most important thing --
 * I want to thank all people who helped me fix all bugs, small and big once.
 * I hope that you don't mind that your names are in this file.
 * 
 * -- Some Apache issues --
 * I get info from Lukas Lisa, that in some cases with special apache configuration
 * you have to put header() function with proper encoding to get your result
 * displayed correctly.
 * If you want to see what I mean, go to demo.php and demo1.php
 * 
 * -- BETA 1.0 2003-10-21 --
 * 
 * -- You should know about... --
 * For good understanding this class you shouls read all this stuff first :) but if you are
 * in a hurry just start the demo.php and see what's inside.
 * 1. That I'm not good in english at 03:45 :) - so forgive me all mistakes
 * 2. This class is a BETA version because I haven't tested it enough
 * 3. Feel free to contact me with questions, bug reports and mistakes in PHP and this documentation (email below)
 * 
 * -- In a few words... --
 * Why ConvertCharset class?
 * 
 * I have made this class because I had a lot of problems with diferent charsets. First because people
 * from Microsoft wanted to have thair own encoding, second because people from Macromedia didn't
 * thought about other languages, third because sometimes I need to use text written on MAC, and of course
 * it has its own encoding :)
 * 
 * Notice & remember:
 * - When I'm saying 1 byte string I mean 1 byte per char.
 * - When I'm saying multibyte string I mean more than one byte per char.
 * 
 * So, this are main FEATURES of this class:
 * - conversion between 1 byte charsets
 * - conversion from 1 byte to multi byte charset (utf-8)
 * - conversion from multibyte charset (utf-8) to 1 byte charset
 * - every conversion output can be save with numeric entities (browser charset independent - not a full truth)
 * 
 * This is a list of charsets you can operate with, the basic rule is that a char have to be in both charsets,
 * otherwise you'll get an error.
 * 
 * - WINDOWS
 * - windows-1250 - Central Europe
 * - windows-1251 - Cyrillic
 * - windows-1252 - Latin I
 * - windows-1253 - Greek
 * - windows-1254 - Turkish
 * - windows-1255 - Hebrew
 * - windows-1256 - Arabic
 * - windows-1257 - Baltic
 * - windows-1258 - Viet Nam
 * - cp874 - Thai - this file is also for DOS
 * 
 * - DOS
 * - cp437 - Latin US
 * - cp737 - Greek
 * - cp775 - BaltRim
 * - cp850 - Latin1
 * - cp852 - Latin2
 * - cp855 - Cyrylic
 * - cp857 - Turkish
 * - cp860 - Portuguese
 * - cp861 - Iceland
 * - cp862 - Hebrew
 * - cp863 - Canada
 * - cp864 - Arabic
 * - cp865 - Nordic
 * - cp866 - Cyrylic Russian (this is the one, used in IE "Cyrillic (DOS)" )
 * - cp869 - Greek2
 * 
 * - MAC (Apple)
 * - x-mac-cyrillic
 * - x-mac-greek
 * - x-mac-icelandic
 * - x-mac-ce
 * - x-mac-roman
 * 
 * - ISO (Unix/Linux)
 * - iso-8859-1
 * - iso-8859-2
 * - iso-8859-3
 * - iso-8859-4
 * - iso-8859-5
 * - iso-8859-6
 * - iso-8859-7
 * - iso-8859-8
 * - iso-8859-9
 * - iso-8859-10
 * - iso-8859-11
 * - iso-8859-12
 * - iso-8859-13
 * - iso-8859-14
 * - iso-8859-15
 * - iso-8859-16
 * 
 * - MISCELLANEOUS
 * - gsm0338 (ETSI GSM 03.38)
 * - cp037
 * - cp424
 * - cp500 
 * - cp856
 * - cp875
 * - cp1006
 * - cp1026
 * - koi8-r (Cyrillic)
 * - koi8-u (Cyrillic Ukrainian)
 * - nextstep
 * - us-ascii
 * - us-ascii-quotes
 * 
 * - DSP implementation for NeXT
 * - stdenc
 * - symbol
 * - zdingbat
 * 
 * - And specially for old Polish programs
 * - mazovia
 *  
 * -- Now, to the point... --
 * Here are main variables.
 * 
 * DEBUG_MODE
 * 
 * You can set this value to:
 * - -1 - No errors or comments
 * - 0  - Only error messages, no comments
 * - 1  - Error messages and comments
 * 
 * Default value is 1, and during first steps with class it should be left as is. 
 *
 * CONVERT_TABLES_DIR
 * 
 * This is a place where you store all files with charset encodings. Filenames should have
 * the same names as encodings. My advise is to keep existing names, because thay
 * were taken from unicode.org (www.unicode.org), and after update to unicode 3.0 or 4.0
 * the names of files will be the same, so if you want to save your time...uff, leave the
 * names as thay are for future updates.
 * 
 * The directory with edings files should be in a class location directory by default,
 * but of course you can change it if you like. 
 * 
 * @package All about charset...
 * @author Mikolaj Jedrzejak <mikolajj@op.pl>
 * @copyright Copyright Mikolaj Jedrzejak (c) 2003-2004
 * @version 1.0 2004-07-27 23:11
 * @access public
 * 
 * @link http://www.unicode.org Unicode Homepage
 **/
class ConvertCharset {
	var $RecognizedEncoding; //This value keeps information if string contains multibyte chars.
	var $Entities; // This value keeps information if output should be with numeric entities.

	/**
	 * CharsetChange::NumUnicodeEntity()
	 * 
	 * Unicode encoding bytes, bits representation.
	 * Each b represents a bit that can be used to store character data.
	 * - bytes, bits, binary representation
	 * - 1,   7,  0bbbbbbb
	 * - 2,  11,  110bbbbb 10bbbbbb
	 * - 3,  16,  1110bbbb 10bbbbbb 10bbbbbb
	 * - 4,  21,  11110bbb 10bbbbbb 10bbbbbb 10bbbbbb
	 * 
	 * This function is written in a "long" way, for everyone who woluld like to analize
	 * the process of unicode encoding and understand it. All other functions like HexToUtf
	 * will be written in a "shortest" way I can write tham :) it does'n mean thay are short
	 * of course. You can chech it in HexToUtf() (link below) - very similar function.
	 * 
	 * IMPORTANT: Remember that $UnicodeString input CANNOT have single byte upper half
	 * extended ASCII codes, why? Because there is a posibility that this function will eat
	 * the following char thinking it's miltibyte unicode char.
	 * 
	 * @param string $UnicodeString Input Unicode string (1 char can take more than 1 byte)
	 * @return string This is an input string olso with unicode chars, bus saved as entities
	 * @see HexToUtf()
	 **/
	function UnicodeEntity ($UnicodeString) 
	{
	  $OutString  = "";
	  $StringLenght = strlen ($UnicodeString);
	  for ($CharPosition = 0; $CharPosition < $StringLenght; $CharPosition++) 
		{
	    $Char = $UnicodeString [$CharPosition];
	    $AsciiChar = ord ($Char);

	   if ($AsciiChar < 128) //1 7 0bbbbbbb (127)
	   {
		   $OutString .= $Char; 
	   }
	   else if ($AsciiChar >> 5 == 6) //2 11 110bbbbb 10bbbbbb (2047)
	   {
		   $FirstByte = ($AsciiChar & 31);
		   $CharPosition++;
		   $Char = $UnicodeString [$CharPosition];
		   $AsciiChar = ord ($Char);
		   $SecondByte = ($AsciiChar & 63);
		   $AsciiChar = ($FirstByte * 64) + $SecondByte;
		   $Entity = sprintf ("&#%d;", $AsciiChar);
		   $OutString .= $Entity;
	   }
	   else if ($AsciiChar >> 4  == 14)  //3 16 1110bbbb 10bbbbbb 10bbbbbb
	   {
			$FirstByte = ($AsciiChar & 31);
			$CharPosition++;
			$Char = $UnicodeString [$CharPosition];
			$AsciiChar = ord ($Char);
			$SecondByte = ($AsciiChar & 63);
			$CharPosition++;
			$Char = $UnicodeString [$CharPosition];
			$AsciiChar = ord ($Char);
			$ThidrByte = ($AsciiChar & 63);
			$AsciiChar = ((($FirstByte * 64) + $SecondByte) * 64) + $ThidrByte;
			
			$Entity = sprintf ("&#%d;", $AsciiChar);
			$OutString .= $Entity;
	    }
		else if ($AsciiChar >> 3 == 30) //4 21 11110bbb 10bbbbbb 10bbbbbb 10bbbbbb
		{
			$FirstByte = ($AsciiChar & 31);
			$CharPosition++;
			$Char = $UnicodeString [$CharPosition];
			$AsciiChar = ord ($Char);
			$SecondByte = ($AsciiChar & 63);
			$CharPosition++;
			$Char = $UnicodeString [$CharPosition];
			$AsciiChar = ord ($Char);
			$ThidrByte = ($AsciiChar & 63);
			$CharPosition++;
			$Char = $UnicodeString [$CharPosition];
			$AsciiChar = ord ($Char);
			$FourthByte = ($AsciiChar & 63);
			$AsciiChar = ((((($FirstByte * 64) + $SecondByte) * 64) + $ThidrByte) * 64) + $FourthByte;
	
			$Entity = sprintf ("&#%d;", $AsciiChar);
			$OutString .= $Entity;
	    }
	  }
	  return $OutString;
	} 
	
	/**
	 * ConvertCharset::HexToUtf()
	 * 
	 * This simple function gets unicode  char up to 4 bytes and return it as a regular char.
	 * It is very similar to  UnicodeEntity function (link below). There is one difference 
	 * in returned format. This time it's a regular char(s), in most cases it will be one or two chars. 
	 * 
	 * @param string $UtfCharInHex Hexadecimal value of a unicode char.
	 * @return string Encoded hexadecimal value as a regular char.
	 * @see UnicodeEntity()
	 **/
	function HexToUtf ($UtfCharInHex)
	{
		$OutputChar = "";
		$UtfCharInDec = hexdec($UtfCharInHex);
		if($UtfCharInDec<128) $OutputChar .= chr($UtfCharInDec);
    else if($UtfCharInDec<2048)$OutputChar .= chr(($UtfCharInDec>>6)+192).chr(($UtfCharInDec&63)+128);
    else if($UtfCharInDec<65536)$OutputChar .= chr(($UtfCharInDec>>12)+224).chr((($UtfCharInDec>>6)&63)+128).chr(($UtfCharInDec&63)+128);
    else if($UtfCharInDec<2097152)$OutputChar .= chr($UtfCharInDec>>18+240).chr((($UtfCharInDec>>12)&63)+128).chr(($UtfCharInDec>>6)&63+128). chr($UtfCharInDec&63+128);
	return $OutputChar;
	}


	/**
	 * CharsetChange::MakeConvertTable()
	 * 
	 * This function creates table with two SBCS (Single Byte Character Set). Every conversion
	 * is through this table.
	 *  
	 * - The file with encoding tables have to be save in "Format A" of unicode.org charset table format! This is usualy writen in a header of every charset file.
	 * - BOTH charsets MUST be SBCS
	 * - The files with encoding tables have to be complet (Non of chars can be missing, unles you are sure you are not going to use it)
	 * 
	 * "Format A" encoding file, if you have to build it by yourself should aplly these rules:
	 * - you can comment everything with #
	 * - first column contains 1 byte chars in hex starting from 0x..
	 * - second column contains unicode equivalent in hex starting from 0x....
	 * - then every next column is optional, but in "Format A" it should contain unicode char name or/and your own comment
	 * - the columns can be splited by "spaces", "tabs", "," or any combination of these
	 * - below is an example
	 * 
	 * <code>
	 * #
	 * #	The entries are in ANSI X3.4 order.
	 * #
	 * 0x00	0x0000	#	NULL end extra comment, if needed
	 * 0x01	0x0001	#	START OF HEADING
	 * # Oh, one more thing, you can make comments inside of a rows if you like.
	 * 0x02	0x0002	#	START OF TEXT
	 * 0x03	0x0003	#	END OF TEXT
	 * next line, and so on...
	 * </code>
	 * 
	 * You can get full tables with encodings from http://www.unicode.org
	 * 
	 * @param string $FirstEncoding Name of first encoding and first encoding filename (thay have to be the same)
	 * @param string $SecondEncoding Name of second encoding and second encoding filename (thay have to be the same). Optional for building a joined table.
	 * @return array Table necessary to change one encoding to another.
	 **/
	function MakeConvertTable ($FirstEncoding, $SecondEncoding = "") 
	{
		$ConvertTable = array();
		for($i = 0; $i < func_num_args(); $i++)
		{
			/**
			 * Because func_*** can't be used inside of another function call
			 * we have to save it as a separate value.
			 **/
			$FileName = func_get_arg($i);
			if (!is_file(CONVERT_TABLES_DIR . $FileName)) 
			{
			    print $this->DebugOutput(0, 0, CONVERT_TABLES_DIR . $FileName); //Print an error message
					exit;
			}
			$FileWithEncTabe = fopen(CONVERT_TABLES_DIR . $FileName, "r") or die(); //This die(); is just to make sure...
		  while(!feof($FileWithEncTabe))
			{
				/**
				 * We asume that line is not longer
				 * than 1024 which is the default value for fgets function 
				 **/
		   if($OneLine=trim(fgets($FileWithEncTabe, 1024)))
			 {
				/**
				 * We don't need all comment lines. I check only for "#" sign, because
				 * this is a way of making comments by unicode.org in thair encoding files
				 * and that's where the files are from :-)
				 **/
		   	if (substr($OneLine, 0, 1) != "#") 
				{
					/**
					 * Sometimes inside the charset file the hex walues are separated by
					 * "space" and sometimes by "tab", the below preg_split can also be used
					 * to split files where separator is a ",", "\r", "\n" and "\f"
					 **/
					$HexValue = preg_split ("/[\s,]+/", $OneLine, 3);  //We need only first 2 values
						/**
						 * Sometimes char is UNDEFINED, or missing so we can't use it for convertion
						 **/
						if (substr($HexValue[1], 0, 1) != "#") 
						{
								$ArrayKey = strtoupper(str_replace(strtolower("0x"), "", $HexValue[1]));
								$ArrayValue = strtoupper(str_replace(strtolower("0x"), "", $HexValue[0]));
								$ConvertTable[func_get_arg($i)][$ArrayKey] = $ArrayValue;
						}
				} //if (substr($OneLine,...
		   } //if($OneLine=trim(f...
		  } //while(!feof($FirstFileWi...
		} //for($i = 0; $i < func_...
	/**
	 * The last thing is to check if by any reason both encoding tables are not the same.
	 * For example, it will happen when you save the encoding table file with a wrong name
	 *  - of another charset. 
	 **/
	if ((func_num_args() > 1) && (count($ConvertTable[$FirstEncoding]) == count($ConvertTable[$SecondEncoding])) && (count(array_diff_assoc($ConvertTable[$FirstEncoding], $ConvertTable[$SecondEncoding])) == 0)) 
	{
	    print $this->DebugOutput(1, 1, "$FirstEncoding, $SecondEncoding");
	}
	return $ConvertTable;
	}
	
	
	
	/**
	 * ConvertCharset::Convert()
	 * 
	 * This is a basic function you are using. I hope that you can figure out this function syntax :-)
	 * 
	 * @param string $StringToChange The string you want to change :)
	 * @param string $FromCharset Name of $StringToChange encoding, you have to know it.
	 * @param string $ToCharset Name of a charset you want to get for $StringToChange.
	 * @param boolean $TurnOnEntities Set to true or 1 if you want to use numeric entities insted of regular chars.
	 * @return string Converted string in brand new encoding :)
	 * @version 1.0 2004-07-27 01:09
	 **/
	function Convert ($StringToChange, $FromCharset, $ToCharset, $TurnOnEntities = false)
	{
		/**
		 * Check are there all variables 
		 **/
		 /*if ($StringToChange == "") 
		 {
				print $this->DebugOutput(0, 3, "\$StringToChange");
		 }
		 else*/ 
		if ($FromCharset == "") 
		 {
		 		print $this->DebugOutput(0, 3, "\$FromCharset");	
		 }
		 else if ($ToCharset == "") 
		 {
		 		print $this->DebugOutput(0, 3, "\$ToCharset");	
		 }
		 
		/**
		 * Now a few variables need to be set. 
		 **/
		$NewString = "";
		$this->Entities = $TurnOnEntities;
		
		/**
		 * For all people who like to use uppercase for charset encoding names :) 
		 **/
		$FromCharset = strtolower($FromCharset);
		$ToCharset   = strtolower($ToCharset);

		/**
		 * Of course you can make a conversion from one charset to the same one :) 
		 * but I feel obligate to let you know about it. 
		 **/
		if ($FromCharset == $ToCharset) 
		{
		    print $this->DebugOutput(1, 0, $FromCharset);
		}
		if (($FromCharset == $ToCharset) AND ($FromCharset == "utf-8")) 
		{
		    print $this->DebugOutput(0, 4, $FromCharset);
				exit;
		}
		
		/**
		 * This divison was made to prevent errors during convertion to/from utf-8 with
		 * "entities" enabled, because we need to use proper destination(to)/source(from)
		 * encoding table to write proper entities.
		 * 
		 * This is the first case. We are convertinf from 1byte chars...
		 **/
		if ($FromCharset != "utf-8") 
		{
				/**
				 * Now build table with both charsets for encoding change. 
				 **/
				if ($ToCharset != "utf-8") 
				{
					$CharsetTable = $this->MakeConvertTable ($FromCharset, $ToCharset);
				}
				else
				{
					$CharsetTable = $this->MakeConvertTable ($FromCharset);
				}
				/**
				 * For each char in a string... 
				 **/
				for ($i = 0; $i < strlen($StringToChange); $i++)
				{
					$HexChar = "";
					$UnicodeHexChar = "";
					$HexChar = strtoupper(dechex(ord($StringToChange[$i])));
					// This is fix from Mario Klingemann, it prevents
					// droping chars below 16 because of missing leading 0 [zeros]
					if (strlen($HexChar)==1) $HexChar = "0".$HexChar;
					//end of fix by Mario Klingemann
					// This is quick fix of 10 chars in gsm0338
					// Thanks goes to Andrea Carpani who pointed on this problem
					// and solve it ;)
					if (($FromCharset == "gsm0338") && ($HexChar == '1B')) {
						$i++;
						$HexChar .= strtoupper(dechex(ord($StringToChange[$i])));
					}
					// end of workarround on 10 chars from gsm0338
					if ($ToCharset != "utf-8") 
					{
						if (in_array($HexChar, $CharsetTable[$FromCharset]))
						{
							$UnicodeHexChar = array_search($HexChar, $CharsetTable[$FromCharset]);
							$UnicodeHexChars = explode("+",$UnicodeHexChar);
							for($UnicodeHexCharElement = 0; $UnicodeHexCharElement < count($UnicodeHexChars); $UnicodeHexCharElement++)
							{
							  if (array_key_exists($UnicodeHexChars[$UnicodeHexCharElement], $CharsetTable[$ToCharset])) 
								{
									if ($this->Entities == true) 
									{
										$NewString .= $this->UnicodeEntity($this->HexToUtf($UnicodeHexChars[$UnicodeHexCharElement]));
									}
									else
									{
										$NewString .= chr(hexdec($CharsetTable[$ToCharset][$UnicodeHexChars[$UnicodeHexCharElement]]));
									}
								}
							 	else
								{
										print $this->DebugOutput(0, 1, $StringToChange[$i]);
								}
							} //for($UnicodeH...
						}
						else
						{
							print $this->DebugOutput(0, 2,$StringToChange[$i]);
						}
					}
					else
					{
						if (in_array("$HexChar", $CharsetTable[$FromCharset])) 
						{
							$UnicodeHexChar = array_search($HexChar, $CharsetTable[$FromCharset]);
							/**
					     * Sometimes there are two or more utf-8 chars per one regular char.
							 * Extream, example is polish old Mazovia encoding, where one char contains
							 * two lettes 007a (z) and 0142 (l slash), we need to figure out how to
							 * solve this problem.
							 * The letters are merge with "plus" sign, there can be more than two chars.
							 * In Mazowia we have 007A+0142, but sometimes it can look like this
							 * 0x007A+0x0142+0x2034 (that string means nothing, it just shows the possibility...)
					     **/
							$UnicodeHexChars = explode("+",$UnicodeHexChar);
							for($UnicodeHexCharElement = 0; $UnicodeHexCharElement < count($UnicodeHexChars); $UnicodeHexCharElement++)
							{
								if ($this->Entities == true) 
								{
									$NewString .= $this->UnicodeEntity($this->HexToUtf($UnicodeHexChars[$UnicodeHexCharElement]));
								}
								else
								{
									$NewString .= $this->HexToUtf($UnicodeHexChars[$UnicodeHexCharElement]);
								}
							} // for							
						}
						else
						{
							print $this->DebugOutput(0, 2, $StringToChange[$i]);
						}
					}					
				}
		}
		/**
		 * This is second case. We are encoding from multibyte char string. 
		 **/
		else if($FromCharset == "utf-8")
		{
			$HexChar = "";
			$UnicodeHexChar = "";
			$CharsetTable = $this->MakeConvertTable ($ToCharset);
			foreach ($CharsetTable[$ToCharset] as $UnicodeHexChar => $HexChar)
			{
					if ($this->Entities == true) {
						$EntitieOrChar = $this->UnicodeEntity($this->HexToUtf($UnicodeHexChar));
					}
					else
					{
						$EntitieOrChar = chr(hexdec($HexChar));
					}
					$StringToChange = str_replace($this->HexToUtf($UnicodeHexChar), $EntitieOrChar, $StringToChange);
			}
			$NewString = $StringToChange;
		}
	
	return $NewString;
	}
	
	/**
	 * ConvertCharset::DebugOutput()
	 * 
	 * This function is not really necessary, the debug output could stay inside of
	 * source code but like this, it's easier to manage and translate.
	 * Besides I couldn't find good coment/debug class :-) Maybe I'll write one someday... 
	 * 
	 * All messages depend on DEBUG_MODE level, as I was writing before you can set this value to:
   * - -1 - No errors or notces are shown
   * - 0  - Only error messages are shown, no notices 
   * - 1  - Error messages and notices are shown
	 * 
	 * @param int $Group Message groupe: error - 0, notice - 1
	 * @param int $Number Following message number 
	 * @param mix $Value This walue is whatever you want, usualy it's some parameter value, for better message understanding.
	 * @return string String with a proper message.
	 **/
	function DebugOutput ($Group, $Number, $Value = false)
	{
		//$Debug [$Group][$Number] = "Message, can by with $Value";
		//$Group[0] - Errors
		//$Group[1] - Notice
		$Debug[0][0] = "Error, can NOT read file: " . $Value . "<br />";
		$Debug[0][1] = "Error, can't find maching char \"". $Value ."\" in destination encoding table!" . "<br />";
		$Debug[0][2] = "Error, can't find maching char \"". $Value ."\" in source encoding table!" . "<br />";
		$Debug[0][3] = "Error, you did NOT set variable " . $Value . " in Convert() function." . "<br />";
		$Debug[0][4] = "You can NOT convert string from " . $Value . " to " . $Value . "!" .  "<br />";
		$Debug[1][0] = "Notice, you are trying to convert string from ". $Value ." to ". $Value .", don't you feel it's strange? ;-)" . "<br />";
		$Debug[1][1] = "Notice, both charsets " . $Value . " are identical! Check encoding tables files." . "<br />";
		$Debug[1][2] = "Notice, there is no unicode char in the string you are trying to convert." . "<br />";
		
		if (DEBUG_MODE >= $Group) 
		{
	  	return $Debug[$Group][$Number];
		}
	} // function DebugOutput

} //class ends here

?>
