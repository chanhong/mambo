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
* Sorts an Array of objects
* sort_direction [1 = Ascending] [-1 = Descending]
*/
function SortArrayObjects( &$a, $k, $sort_direction=1 ) {
	$sorter =& new mosObjectSorter($a, $k, $sort_direction);
}

/**
* Sends mail to admin
* Deprecated - not used in Mambo (code copied into weblinks.php)
* Could do with a better facility that works out who to send it to as well
* Note the "email" parameter was not used in the earlier version
*/
function mosSendAdminMail( $adminName, $adminEmail, $email, $type, $title, $author ) {
	$mosConfig_live_site = mamboCore::get('mosConfig_live_site');
	$from = mamboCore::get('mosConfig_mailfrom');
	$fromname = mamboCore::get('mosConfig_fromname');
	$subject = sprintf(T_("User Submitted '%s'"), $type);
	$message = T_("Hello %s,\n\n
A user submitted %s:\n[ %s ]\n
has been just been submitted by user:\n[ %s ]\n\n
for %\n\nPlease go to %s/administrator to view and approve this %s\n\n
Please do not respond to this message as it is automatically generated and is for information purposes only.");
	$message = sprintf($message, $adminName ,$type, $title, $author, $mosConfig_live_site, $mosConfig_live_site, $type);
	require_once(mamboCore::get('mosConfig_absolute_path').'/includes/phpmailer/class.phpmailer.php');
	$mail =& new mosMailer ($from, $fromname, $subject, $message);
	return $mail->mosMail($adminEmail);
}

/*
* Includes pathway file
* Needed by templates
*/
function mosPathWay() {
	$pathway =& mosPathway::getInstance();
	echo $pathway->makePathway();
}

/**
* Displays a not authorised message
*
* If the user is not logged in then an addition message is displayed.
*/
function mosNotAuth() {
	global $my;

	echo T_('You are not authorized to view this resource.');
	if ($my->id < 1) {
		echo "<br />" . T_('You need to login.');
	}
}

/**
* Replaces &amp; with & for xhtml compliance
*
* Needed to handle unicode conflicts due to unicode conflicts
* Deprecated - simply code the line below
*/
function ampReplace( $text ) {

	$text = str_replace( '&#', '*-*', $text );
	$text = str_replace( '&', '&amp;', $text );
	$text = str_replace( '*-*', '&#', $text );

	return $text;
	//return preg_replace('/(&)([^#]|$)/','&amp;$2', $text);
}

/**
* Chmods files and directories recursively to given permissions. Available from 4.5.2 up.
* @param path The starting file or directory (no trailing slash)
* @param filemode Integer value to chmod files. NULL = dont chmod files.
* @param dirmode Integer value to chmod directories. NULL = dont chmod directories.
* @return TRUE=all succeeded FALSE=one or more chmods failed
*/
function mosChmodRecursive($path, $filemode=NULL, $dirmode=NULL)
{
	$fileman =& mosFileManager::getInstance();
	return $fileman->mosChmodRecursive($path, $filemode, $dirmode);
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
	$fileman =& mosFileManager::getInstance();
	return $fileman->mosChmod($path);
} // mosChmod

/**
 * Function to convert array to integer values
 * Deprecated - not used within Mambo
 */
function mosArrayToInts( &$array, $default=null ) {
	if (is_array( $array )) {
		$n = count( $array );
		for ($i = 0; $i < $n; $i++) {
			$array[$i] = intval( $array[$i] );
		}
	} else {
		if (is_null( $default )) {
			return array();
		} else {
			return array( $default );
		}
	}
}

/**
* Strip slashes from strings or arrays of strings
* @param value the input string or array
*/
function mosStripslashes(&$value)
{
	$database =& mamboDatabase::getInstance();
	return $database->mosStripslashes($value);
}

/**
* Copy the named array content into the object as properties
* only existing properties of object are filled. when undefined in hash, properties wont be deleted
* @param array the input array
* @param obj byref the object to fill of any class
* @param string
* @param boolean
*/
function mosBindArrayToObject( $array, &$obj, $ignore='', $prefix=NULL, $checkSlashes=true ) {
	$database =& mamboDatabase::getInstance();
	return $database->mosBindArrayToOBject($array, $obj, $ignore='', $prefix=NULL, $checkSlashes=true);
}

/**
* Utility function to read the files in a directory
* @param string The file system path
* @param string A filter for the names
* @param boolean Recurse search into sub-directories
* @param boolean True if to prepend the full path to the file name
*/
function mosReadDirectory( $path, $filter='.', $recurse=false, $fullpath=false  ) {
	if (@is_dir($path)) {
		$dir =& new mosDirectory($path);
		$arr =& $dir->listFiles ($filter, $type='both', $recurse, $fullpath);
	}
	else $arr = array();
	return $arr;
}
	
/**
* Utility function redirect the browser location to another url
*
* Can optionally provide a message.
* @param string The file system path
* @param string A filter for the names
*/
function mosRedirect( $url, $msg='' ) {
	mamboCore::redirect($url, $msg);
}

/**
* Function to strip additional / or \ in a path name
* @param string The path
* @param boolean Add trailing slash
*/
function mosPathName($p_path,$p_addtrailingslash = true) {
	$fileman =& mosFileManager::getInstance();
	return $fileman->mosPathName($p_path,$p_addtrailingslash);
}

/**
* Checks the user agent string against known browsers
*/
function mosGetBrowser( $agent ) {
	require( "includes/agent_browser.php" );

	if (preg_match( "/msie[\/\sa-z]*([\d\.]*)/i", $agent, $m )
	&& !preg_match( "/webtv/i", $agent )
	&& !preg_match( "/omniweb/i", $agent )
	&& !preg_match( "/opera/i", $agent )) {
		// IE
		return "MS Internet Explorer $m[1]";
	} else if (preg_match( "/netscape.?\/([\d\.]*)/i", $agent, $m )) {
		// Netscape 6.x, 7.x ...
		return "Netscape $m[1]";
	} else if ( preg_match( "/mozilla[\/\sa-z]*([\d\.]*)/i", $agent, $m )
	&& !preg_match( "/gecko/i", $agent )
	&& !preg_match( "/compatible/i", $agent )
	&& !preg_match( "/opera/i", $agent )
	&& !preg_match( "/galeon/i", $agent )
	&& !preg_match( "/safari/i", $agent )) {
		// Netscape 3.x, 4.x ...
		return "Netscape $m[2]";
	} else {
		// Other
		$found = false;
		foreach ($browserSearchOrder as $key) {
			if (preg_match( "/$key.?\/([\d\.]*)/i", $agent, $m )) {
				$name = "$browsersAlias[$key] $m[1]";
				return $name;
				break;
			}
		}
	}

	return 'Unknown';
}

/**
* Checks the user agent string against known operating systems
*/
function mosGetOS( $agent ) {
	require( "includes/agent_os.php" );

	foreach ($osSearchOrder as $key) {
		if (preg_match( "/$key/i", $agent )) {
			return $osAlias[$key];
			break;
		}
	}

	return 'Unknown';
}

/**
* Makes a variable safe to display in forms
*
* Object parameters that are non-string, array, object or start with underscore
* will be converted
* @param object An object to be parsed
* @param int The optional quote style for the htmlspecialchars function
* @param string|array An optional single field name or array of field names not
*                     to be parsed (eg, for a textarea)
*/
function mosMakeHtmlSafe( &$mixed, $quote_style=ENT_QUOTES, $exclude_keys='' ) {
	if (is_object( $mixed )) {
		foreach (get_object_vars( $mixed ) as $k => $v) {
			if (is_array( $v ) || is_object( $v ) || $v == NULL || substr( $k, 1, 1 ) == '_' ) {
				continue;
			}
			if (is_string( $exclude_keys ) && $k == $exclude_keys) {
				continue;
			} else if (is_array( $exclude_keys ) && in_array( $k, $exclude_keys )) {
				continue;
			}
			$mixed->$k = htmlspecialchars( $v, $quote_style );
		}
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
function mosMenuCheck( $Itemid, $menu_option, $task, $gid ) {
	$menuhandler =& mosMenuHandler::getInstance();
	return $menuhandler->menuCheck($Itemid, $menu_option, $task, $gid);
}

/**
* Returns formated date according to current local and adds time offset
* @param string date in datetime format
* @param string format optional format for strftime
* @param offset time offset if different than global one
* @returns formated date
*/
function mosFormatDate( $date, $format="", $offset="" ){
    $core = mamboCore::getMamboCore();
	if ( $format == '' ) {
		// %Y-%m-%d %H:%M:%S
		$format = $core->current_language->date_format;
	}
	
	
	if ( !$offset ) {
		$offset = $core->mosConfig_offset;
	}
	if ( $date && ereg( "([0-9]{4})-([0-9]{2})-([0-9]{2})[ ]([0-9]{2}):([0-9]{2}):([0-9]{2})", $date, $regs ) ) {
	    $date = mktime( $regs[4], $regs[5], $regs[6], $regs[2], $regs[3], $regs[1] );
		$date = $date > -1 ? $core->current_language->getDate($format,  $date + ($offset*60*60)) : '-';
	}
	return $date;
}

/**
* Returns current date according to current local and time offset
* @param string format optional format for strftime
* @returns current date
*/
function mosCurrentDate( $format="" ) {
	$core =& mamboCore::getMamboCore();
	$offset = mamboCore::get('mosConfig_offset');
	if ($format=="") {
		$format = $format = $core->current_language->date_format;
	}
	$date = $core->current_language->getDate($format,  time() + ($offset*60*60));
	return $date;
}

/**
* Utility function to provide ToolTips
* @param string ToolTip text
* @param string Box title
* @returns HTML code for ToolTip
*/
function mosToolTip( $tooltip, $title='', $width='', $image='tooltip.png', $text='', $href='#' ) {
	global $mosConfig_live_site;

	if ( $width ) {
		$width = ', WIDTH, \''.$width .'\'';
	}
	if ( $title ) {
		$title = ', CAPTION, \''.$title .'\'';
	}
	if ( !$text ) {
		$image 	= $mosConfig_live_site . '/includes/js/ThemeOffice/'. $image;
		$text 	= '<img src="'. $image .'" border="0" />';
	}
	$style = 'style="text-decoration: none; color: #333;"';
	if ( $href ) {
		$style = '';
	}
	$tip 	= "<a href=\"". $href ."\" onMouseOver=\"return overlib('" . $tooltip . "'". $title .", BELOW, RIGHT". $width .");\" onmouseout=\"return nd();\" ". $style .">". $text ."</a>";
	return $tip;
}

/**
* Utility function to provide Warning Icons
* @param string Warning text
* @param string Box title
* @returns HTML code for Warning
*/
function mosWarning($warning, $title=null) {
    if (is_null($title)) $title = T_('Mambo Warning');
	$mosConfig_live_site = mamboCore::get('mosConfig_live_site');
	$tip = "<a href=\"#\" onMouseOver=\"return overlib('" . $warning . "', CAPTION, '$title', BELOW, RIGHT);\" onmouseout=\"return nd();\"><img src=\"" . $mosConfig_live_site . "/includes/js/ThemeOffice/warning.png\" border=\"0\" /></a>";
	return $tip;
}

function mosCreateGUID(){
	srand((double)microtime()*1000000);
	$r = rand ;
	$u = uniqid(getmypid() . $r . (double)microtime()*1000000,1);
	$m = md5 ($u);
	return($m);
}

function mosCompressID( $ID ){
	return(Base64_encode(pack("H*",$ID)));
}

function mosExpandID( $ID ) {
	return ( implode(unpack("H*",Base64_decode($ID)), '') );
}

/**
* Mail function (uses phpMailer)
* @param string From e-mail address
* @param string From name
* @param string/array Recipient e-mail address(es)
* @param string E-mail subject
* @param string Message body
* @param boolean false = plain text, true = HTML
* @param string/array CC e-mail address(es)
* @param string/array BCC e-mail address(es)
* @param string/array Attachment file name(s)
* @param string/array Reply-to e-mail address
* @param string/array Reply-to name
*/
function mosMail($from, $fromname, $recipient, $subject, $body, $mode=0, $cc=NULL, $bcc=NULL, $attachment=NULL, $replyto=NULL, $replytoname=NULL ) {
	require_once(mamboCore::get('mosConfig_absolute_path').'/includes/phpmailer/class.phpmailer.php');
	$mail =& new mosMailer ($from, $fromname, $subject, $body);
	$result = $mail->mosMail($recipient, $mode, $cc, $bcc, $attachment, $replyto, $replytoname);
	return $result;
} // mosMail

/**
* Create mail object
* @return mail object
*/
function &mosCreateMail ($from, $fromname, $subject, $body) {
	require_once(mamboCore::get('mosConfig_absolute_path').'/includes/phpmailer/class.phpmailer.php');
	$mail =& new mosMailer ($from, $fromname, $subject, $body);
	return $mail;
} 

/**
* Random password generator
* @return password
*/
function mosMakePassword() {
	require_once(mamboCore::get('mosConfig_absolute_path').'/includes/authenticator.php');
	$authenticator =& mamboAuthenticator::getInstance();
	return $authenticator->mosMakePassword();
}

if (!function_exists('html_entity_decode')) {
	/**
	* html_entity_decode function for backward compatability in PHP
	* @param string
	* @param string
	*/
	function html_entity_decode ($string, $opt = ENT_COMPAT) {

		$trans_tbl = get_html_translation_table (HTML_ENTITIES);
		$trans_tbl = array_flip ($trans_tbl);

		if ($opt & 1) { // Translating single quotes
		// Add single quote to translation table;
		// doesn't appear to be there by default
		$trans_tbl["&apos;"] = "'";
		}

		if (!($opt & 2)) { // Not translating double quotes
		// Remove double quote from translation table
		unset($trans_tbl["&quot;"]);
		}

		return strtr ($string, $trans_tbl);
	}
}

/**
* @param string
* @return string
* Deprecated - use the code within this function instead - not used in Mambo
*/
function mosParseParams( $txt ) {
	$pparser = new mosParameters($txt);
	return $pparser->getParams();
}

class mosEmpty {
	function def( $key, $value='' ) {
	    return 1;
	}
	function get( $key, $default='' ) {
	    return 1;
	}
}

function mosIsRTL(){
    $core = mamboCore::getMamboCore();
    if ( $core->current_language->text_direction == "rtl" ){
        return true;
    } else {
        return false;
    }
}  

/**
* Utility path directory separator sanitizer
* @param string the path to sanitize
* @param boolean false = don't strip trailing slashes true = strip trailing slashes
* @return sanitized path
*/
function mosPath($path, $stripTrailing=false) {
	if ($stripTrailing) $path = preg_replace("/[\/\\\\]+$/", "", $path);
	$ds = defined('DIRECTORY_SEPARATOR') ? DIRECTORY_SEPARATOR : '/';
	$path = preg_replace("/[\/\\\\]+/",$ds,$path);
	return $path;
}

/**
* Utility debugging shortcut
* @param string echoed with a trailing <br /> tag
*/
function e($string) {
	echo "$string<br />";
}

/**
* Utility debugging shortcut
* @param list print_r(list) enclosed by <pre></pre> tags
*/
function pr($list) {
	echo "<pre>";
	print_r($list);
	echo "</pre>";
}
?>