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

/** Set flag that this is a parent file */
if (!defined('_VALID_MOS')) define( '_VALID_MOS', 1 );

$dir = isset($adminside)?"../":"";

if ( !file_exists($dir.'configuration.php' ) || filesize( $dir.'configuration.php' ) < 10 ) {
    header("Location: ".$dir."installation/index.php");
    exit();
}

$protects = array('_REQUEST', '_GET', '_POST', '_COOKIE', '_FILES', '_SERVER', '_ENV', 'GLOBALS', '_SESSION');
foreach ($protects as $protect) {
    if ( in_array($protect , array_keys($_REQUEST)) ||
    in_array($protect , array_keys($_GET)) ||
    in_array($protect , array_keys($_POST)) ||
    in_array($protect , array_keys($_COOKIE)) ||
    in_array($protect , array_keys($_FILES))) {
        die("Invalid Request.");
    }
}

/**
* used to leave the input element without trim it
*/
define( "_MOS_NOTRIM", 0x0001 );
/**
* used to leave the input element with all HTML tags
*/
define( "_MOS_ALLOWHTML", 0x0002 );
/**
* used to leave the input element without convert it to numeric
*/
define( "_MOS_ALLOWRAW", 0x0004 );
/**
* used to leave the input element without slashes
*/
define( "_MOS_NOMAGIC", 0x0008 );

/**
* function to sanitize input values from arrays
*
* This function provides a way to sanitize inputs, should be used to obtain values from 
* _POST, _GET, _COOKIES, etc; a default value can be passed to be used in case that not 
* values are founded to the element, a binary mask can be passed to discard some of  test,
*, this value is matched with _MOS_NOTRIM, _MOS_ALLOWHTML and, _MOS_ALLOWRAW, currently
* 3 test are do it, trim, strip html and convert the value to numeric when is possible.
*
* Example of use:
*
* To get task variable from the URL and select the view like default task, you can use:
*
* <code>$task = mosGetParam ($_GET,"task","view");</code>
*
* To get task variable from the URL, select the view like default task, allows HTML and 
* without trim you can use :
*
* <code>$task = mosGetParam ($_GET,"task","view",_MOS_NOTRIM+_MOS_ALLOWHTML);</code>
*
* @acces public
* @param array &$arr reference to array which contains the value
* @param string $name name of element searched
* @param mixed $def default value to use if nothing is founded
* @param int $mask mask to select checks that will do it
* @return mixed value from the selected element or default value if nothing was found 
*/
function mosGetParam( &$arr, $name, $def=null, $mask=0 ) {
    if (isset( $arr[$name] )) {
        if (is_array($arr[$name])) foreach ($arr[$name] as $key=>$element) $result[$key] = mosGetParam ($arr[$name], $key, $def, $mask);
        else {
            $result = $arr[$name];
            if (!($mask&_MOS_NOTRIM)) $result = trim($result);
            if (!is_numeric( $result)) {
                if (!($mask&_MOS_ALLOWHTML)) $result = strip_tags($result);
                if (!($mask&_MOS_ALLOWRAW)) {
                    if (is_numeric($def)) $result = intval($result);
                }
            }
            if (!get_magic_quotes_gpc()) {
                $result = addslashes( $result );
            }
        }
        return $result;
    } else {
        return $def;
    }
}

/**
* sets or returns the current side (frontend/backend) 
*
* This function returns TRUE when the user are in the backend area; this is set to
* TRUE when are invocated /administrator/index.php, /administrator/index2.php 
* or /administrator/index3.php, to set this value is not a normal use.
*
* @access public
* @param bool $val value used to set the adminSide value, not planned to be used by users
* @return bool TRUE when the user are in backend area, FALSE when are in frontend
*/
function adminSide($val='') {
    static $adminside;
    if (is_null($adminside)) {
        $adminside = ($val == '') ? 0 : $val;
    } else {
        $adminside = ($val == '') ? $adminside : $val;
    }
    return $adminside;
}


/**
* sets or returns the index type  
*
* This function returns 1, 2 or 3 depending of called file index.php, index2.php or index3.php.
*
* @access private
* @param int $val value used to set the indexType value, not planned to be used by users
* @return int return 1, 2 or 3 depending of called file 
*/

function indexType($val='') 
{
    static $indextype;
    if (is_null($indextype)) {
        $indextype = ($val == '') ? 1 : $val;
    } else {
        $indextype = ($val == '') ? $indextype : $val;
    }
    return $indextype;
}

if (!isset($adminside)) $adminside = 0;
if (!isset($indextype)) $indextype = 1;

adminSide($adminside);
indexType($indextype);

$adminside = adminSide();
$indextype = indexType();

require_once (dirname(__FILE__).'/includes/database.php');
require_once(dirname(__FILE__).'/includes/core.classes.php');
require_once(dirname(__FILE__).'/includes/core.helpers.php');
$configuration =& mamboCore::getMamboCore();
$configuration->handleGlobals();

if (!$adminside) {
    $urlerror = 0;
    $sefcode = dirname(__FILE__).'/components/com_sef/sef.php';
    if (file_exists($sefcode)) require_once($sefcode);
    else require_once(dirname(__FILE__).'/includes/sef.php');
}

$configuration->fixLanguage();

require($configuration->rootPath().'/includes/version.php');
$_VERSION =& new version();


$version = $_VERSION->PRODUCT .' '. $_VERSION->RELEASE .'.'. $_VERSION->DEV_LEVEL .' '
. $_VERSION->DEV_STATUS
.' [ '.$_VERSION->CODENAME .' ] '. $_VERSION->RELDATE .' '
. $_VERSION->RELTIME .' '. $_VERSION->RELTZ;

if (phpversion() < '4.2.0') require_once( $configuration->rootPath() . '/includes/compat.php41x.php' );
if (phpversion() < '4.3.0') require_once( $configuration->rootPath() . '/includes/compat.php42x.php' );
if (phpversion() < '5.0.0') require_once( $configuration->rootPath() . '/includes/compat.php5xx.php' );

$local_backup_path = $configuration->rootPath().'/administrator/backups';
$media_path = $configuration->rootPath().'/media/';
$image_path = $configuration->rootPath().'/images/stories';
$lang_path = $configuration->rootPath().'/language';
$image_size = 100;


$database =& mamboDatabase::getInstance();
// Start NokKaew patch
$mosConfig_nok_content=0;
if (file_exists( $configuration->rootPath().'components/com_nokkaew/nokkaew.php' ) && !$adminside ) {
	$mosConfig_nok_content=1;		// can also go into the configuration - but this might be overwritten!
	require_once( $configuration->rootPath()."administrator/components/com_nokkaew/nokkaew.class.php");
	require_once( $configuration->rootPath()."components/com_nokkaew/classes/nokkaew.class.php");
}
if( $mosConfig_nok_content ) {
	$database = new mlDatabase( $mosConfig_host, $mosConfig_user, $mosConfig_password, $mosConfig_db, $mosConfig_dbprefix );
} 

if ($mosConfig_nok_content) {
        $mosConfig_defaultLang = $mosConfig_locale;		// Save the default language of the site
        $iso_client_lang = NokKaew::discoverLanguage( $database );
        $_NOKKAEW_MANAGER = new NokKaewManager();
}
// end NokKaew Patch
$database->debug(mamboCore::get('mosConfig_debug'));

/** retrieve some possible request string (or form) arguments */
$type = (int)mosGetParam($_REQUEST, 'type', 1);
$do_pdf = (int)mosGetParam( $_REQUEST, 'do_pdf', 0 );
$id = (int)mosGetParam( $_REQUEST, 'id', 0 );
$task = htmlspecialchars(mosGetParam($_REQUEST, 'task', ''));
$act = strtolower(htmlspecialchars(mosGetParam($_REQUEST, 'act', '')));
$section = htmlspecialchars(mosGetParam($_REQUEST, 'section', ''));
$no_html = strtolower(mosGetParam($_REQUEST, 'no_html', ''));
$cid = (array) mosGetParam( $_POST, 'cid', array() );

ini_set('session.use_trans_sid', 0);
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);


/* initialize i18n */
$lang       = $configuration->current_language->name;
$charset    = $configuration->current_language->charset;
$gettext =& phpgettext();
$gettext->debug       = $configuration->mosConfig_locale_debug;
$gettext->has_gettext = $configuration->mosConfig_locale_use_gettext;
$language = new mamboLanguage($lang);
$gettext->setlocale($lang, $language->getSystemLocale());
$gettext->bindtextdomain($lang, $configuration->rootPath().'/language');
$gettext->bind_textdomain_codeset($lang, $charset);
$gettext->textdomain($lang);
#$gettext =& phpgettext(); dump($gettext);

if ($adminside) {
    // Start ACL
    require_once($configuration->rootPath().'/includes/gacl.class.php' );
    require_once($configuration->rootPath().'/includes/gacl_api.class.php' );
    $acl = new gacl_api();
    // Handle special admin side options
    $option = strtolower(mosGetParam($_REQUEST,'option','com_admin'));

    $domain = substr($option, 4);
    session_name(md5(mamboCore::get('mosConfig_live_site')));
    session_start();
    // restore some session variables
    $my = new mosUser();
    $my->getSession();
    if (mosSession::validate($my)) {
        mosSession::purge();
    } else {
        mosSession::purge();
        $my = null;
    }
    if (!$my AND $option == 'login') {
        $option='admin';
        require_once($configuration->rootPath().'/includes/authenticator.php');
        $authenticator =& mamboAuthenticator::getInstance();
        $my = $authenticator->loginAdmin($acl);
    }
    // Handle the remaining special options
    elseif ($option == 'logout') {
        require($configuration->rootPath().'/administrator/logout.php');
        exit();
    }
    // We can now create the mainframe object
    $mainframe =& new mosMainFrame($database, $option, '..', true);
    // Provided $my is set, we have a valid admin side session and can include remaining code
    if ($my) {
        mamboCore::set('currentUser', $my);
        if ($option == 'simple_mode') $admin_mode = 'on';
        elseif ($option == 'advanced_mode') $admin_mode = 'off';
        else $admin_mode = mosGetParam($_SESSION, 'simple_editing', '');
        $_SESSION['simple_editing'] = mosGetParam($_POST, 'simple_editing', $admin_mode);
        require_once($configuration->rootPath().'/administrator/includes/admin.php');
        require_once( $configuration->rootPath().'/includes/mambo.php' );
        require_once ($configuration->rootPath().'/includes/mambofunc.php');
        require_once ($configuration->rootPath().'/includes/mamboHTML.php');
        require_once( $configuration->rootPath().'/administrator/includes/mosAdminMenus.php');
        require_once($configuration->rootPath().'/administrator/includes/admin.php');
        require_once( $configuration->rootPath() . '/includes/cmtclasses.php' );
        require_once( $configuration->rootPath() . '/components/com_content/content.class.php' );
        $_MAMBOTS =& mosMambotHandler::getInstance();


        // If no_html is set, we avoid starting the template, and go straight to the component
        if ($no_html) {
            if ($path = $mainframe->getPath( "admin" )) require $path;
            exit();
        }
        $configuration->initGzip();
        // When adminside = 3 we assume that HTML is being explicitly written and do nothing more
        if ($adminside != 3) {
            $path = $configuration->rootPath().'/administrator/templates/'.$mainframe->getTemplate().'/index.php';
            require_once($path);
            $configuration->doGzip();
        }
        else {
            if (!isset($popup)) {
                $pop = mosGetParam($_REQUEST, 'pop', '');
                if ($pop) require($configuration->rootPath()."/administrator/popups/$pop");
                else require($configuration->rootPath()."/administrator/popups/index3pop.php");
                $configuration->doGzip();
            }
        }
    }
    // If $my was not set, the only possibility is to offer a login screen
    else {
        $configuration->initGzip();
        $path = $configuration->rootPath().'/administrator/templates/'.$mainframe->getTemplate().'/login.php';
        require_once( $path );
        $configuration->doGzip();
    }
}
// Finished admin side; the rest is user side code:
else {
    $option = $configuration->determineOptionAndItemid();
    $Itemid = $configuration->get('Itemid');

    $mainframe =& new mosMainFrame($database, $option, '.');
    if ($option == 'login') $configuration->handleLogin();
    elseif ($option == 'logout') $configuration->handleLogout();

    $session =& mosSession::getCurrent();
    $my =& new mosUser();
    $my->getSessionData();
    mamboCore::set('currentUser',$my);
    $configuration->offlineCheck($my, $database);
    $gid = intval( $my->gid );
    // gets template for page
    $cur_template = $mainframe->getTemplate();

    require_once( $configuration->rootPath().'/includes/frontend.php' );
    require_once( $configuration->rootPath().'/includes/mambo.php' );
    require_once ($configuration->rootPath().'/includes/mambofunc.php');
    require_once ($configuration->rootPath().'/includes/mamboHTML.php');

    if ($indextype == 2 AND $do_pdf == 1 ) {
        include_once('includes/pdf.php');
        exit();
    }

    /** detect first visit */
    $mainframe->detect();

    /** @global mosPlugin $_MAMBOTS */
    $_MAMBOTS =& mosMambotHandler::getInstance();
    require_once( $configuration->rootPath().'/editor/editor.php' );
    require_once( $configuration->rootPath() . '/includes/gacl.class.php' );
    require_once( $configuration->rootPath() . '/includes/gacl_api.class.php' );
    require_once( $configuration->rootPath() . '/components/com_content/content.class.php' );
    require_once( $configuration->rootPath() . '/includes/cmtclasses.php' );
    $acl = new gacl_api();

	/** Load system start mambot for 3pd **/
	$_MAMBOTS->loadBotGroup('system');
	$_MAMBOTS->trigger('onAfterStart');

    /** Get the component handler */
    $c_handler =& mosComponentHandler::getInstance();
    $c_handler->startBuffer();

    if (!$urlerror AND $path = $mainframe->getPath( 'front' )) {
        $menuhandler =& mosMenuHandler::getInstance();
        $ret = $menuhandler->menuCheck($Itemid, $option, $task, $my->getAccessGid());
        $menuhandler->setPathway($Itemid);
        if ($ret) {
            require ($path);
        }
        else mosNotAuth();
    }
    else {
        header ('HTTP/1.1 404 Not Found');
        $mainframe->setPageTitle(T_('404 Error - page not found'));
        include ($configuration->rootPath().'/page404.php');
    }

    $c_handler->endBuffer();

	/** cache modules output**/
	$m_handler =& mosModuleHandler::getInstance();
	$m_handler->initBuffers();

	/** load html helpers **/
	$html =& mosHtmlHelper::getInstance();

    $configuration->initGzip();

    $configuration->standardHeaders();
    if (mosGetParam($_GET, 'syndstyle', '') == 'yes') mosMainBody();
    elseif ($indextype == 1) {
        // loads template file
        if ( !file_exists( 'templates/'. $cur_template .'/index.php' ) ) {
            echo '<span style="color:red; font-weight:bold;">'.T_('Template File Not Found! Looking for template').'</span>&nbsp;'.$cur_template;
        } else {
            require_once( 'templates/'. $cur_template .'/index.php' );
            $mambothandler =& mosMambotHandler::getInstance();
            $mambothandler->loadBotGroup('system');
            $mambothandler->trigger('afterTemplate', array($configuration));
            echo "<!-- ".time()." -->";
        }
    }
    elseif ($indextype == 2) {
        if ( $no_html == 0 ) {
			$html->render('xmlprologue');
			$html->render('doctype');
            ?>
            <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
<?php
			$html->render('css');
			$html->render('charset');
			$html->renderMeta('robots', 'noindex, nofollow');
?>
            </head>
            <body class="contentpane">
            <?php mosMainBody(); ?>
            </body>
            </html>
            <?php
        } else {
            mosMainBody();
        }
    }

    $configuration->doGzip();
}
// displays queries performed for page
if ($configuration->get('mosConfig_debug') AND $adminside != 3) $database->displayLogged();
?>
