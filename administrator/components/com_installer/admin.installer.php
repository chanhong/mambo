<?php
/**
* @package Mambo
* @subpackage Installer
* @author Mambo Foundation Inc see README.php
* @copyright Mambo Foundation Inc.
* See COPYRIGHT.php for copyright notices and details.
* @license GNU/GPL Version 2, see LICENSE.php
* Mambo is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; version 2 of the License.
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( T_('Direct Access to this location is not allowed.') );

require_once( $mainframe->getPath( 'admin_html' ) );
require_once( $mainframe->getPath( 'class' ) );

$element 	= mosGetParam( $_REQUEST, 'element', '' );
$client 	= mosGetParam( $_REQUEST, 'client', '' );
// ensure user has access to this function
if (!$acl->acl_check( 'administration', 'install', 'users', $my->usertype, $element . 's', 'all' ) ) {
	mosRedirect( 'index2.php', T_('You are not authorized to view this resource.') );
}

// map the element to the required derived class
$classMap = array(
	'universal' => 'mosInstaller',
    'component' => 'mosInstaller',
    'language' => 'mosInstaller',
    'mambot' => 'mosInstaller',
    'module' => 'mosInstaller',
    'template' => 'mosInstaller',
    'include' => 'mosInstaller',
    'parameters' => 'mosInstaller'
);

if (array_key_exists ( $element, $classMap )) {
//	require_once( $mainframe->getPath( 'installer_class', $element ) );

	switch ($task) {

		case 'uploadfile':
		    uploadPackage( $classMap[$element], $option, $element, $client );
			break;

		case 'installfromdir':
			installFromDirectory( $classMap[$element], $option, $element, $client );
			break;
		
		case 'installfromurl':
		    installFromUrl ($classMap[$element], $option, $element, $client);
		    break;
		    
		case 'thesource':
		    HTML_installer::theSourceForm($option, $element, $client);
		    break;
		    
		case 'addon':
		    HTML_installer::AddonForm($classMap[$element], $option, $element, $client);
		    break;

		case 'remove':
		    $uninstaller = $element.'_uninstall';
		    if (is_callable($uninstaller)) {
				$cid = mosGetParam($_REQUEST, 'cid', array(0));
				if (is_array($cid) AND isset($cid[0])) {
				    $uninstaller ($cid[0], $option, $client);
				    exit ();
				}
				mosRedirect(returnTo($option, $element, $client), T_('There was nothing selected to be uninstalled') );
			}
		    else mosRedirect(returnTo($option, $element, $client), T_('Uninstaller not found for element [%s]') );
			break;

		default:
			$path = $mosConfig_absolute_path . "/administrator/components/com_installer/$element/$element.php";

			if (file_exists( $path )) {
				require $path;
			} else {
				echo sprintf(T_('Installer not found for element [%s]'), $element);
			}
		    break;
	}
}
else {
	echo sprintf(T_('Installer not available for element [%s]'), $element);
}


function returnTo ($option, $element, $client) {
	switch ($element) {
		case 'template':
			return "index2.php?option=com_templates&client=$client";
		case 'language':
			return "index2.php?option=com_languages";
		default:
			return "index2.php?option=$option&element=$element";
	}
}

/**
* @param string Path to the addons xml file
*/
function getCurrentAddonVersion ($xmlfile) {
	global $mosConfig_absolute_path;
	$xmlfilepath = $mosConfig_absolute_path . $xmlfile;
	//Check the file exists and is thus installed
	if (file_exists($xmlfilepath)) {
		//Load the XML files
		if (!$mXML = simplexml_load_file($xmlfilepath)) {
			return "Can't parse XML";
		} else {
		//Look for current version
		/* Note: Ampersands in  the XML data create an issue for SimpleXML.  The solution
		is to either wrap the text in CDATA or enter in the XML file as &amp;.  More info
		on the issue here - http://changelog.ca/log/2005/06/14/php-simplexml-cdata-problem--and-my-solution.
		Need to revisit later... for now the current XML files have been corrected.*/
		$version = $mXML->version;
		return $version;
		}
	} else {
	    return "Not Installed";
	}	
}

/**
* @param string The class name for the installer
* @param string The URL option
* @param string The element name
*/
function uploadPackage( $installerClass, $option, $element, $client ) {
	global $mainframe;

	// Check if file uploads are enabled
	if (!(bool)ini_get('file_uploads')) {
		$message = new mosError (T_('The installer can\'t continue before file uploads are enabled. Please use the install from directory method.'), _MOS_ERROR_FATAL);
		HTML_installer::showInstallMessage($message, T_('Installer - Error'), returnTo( $option, $element, $client ));
		exit();
	}

	// Check that the zlib is available
	if(!extension_loaded('zlib')) {
		$message = new mosError (T_('The installer can\'t continue before zlib is installed'), _MOS_ERROR_FATAL);
		HTML_installer::showInstallMessage($message, T_('Installer - Error'), returnTo( $option, $element, $client ));
		exit();
	}

	$userfile = mosGetParam( $_FILES, 'userfile', null );

	if (!$userfile) {
		$message = new mosError (T_('No file selected'), _MOS_ERROR_FATAL);
		HTML_installer::showInstallMessage($message, T_('Upload new module - error'), returnTo($option, $element, $client));
		exit();
	}

	$userfile_name = $userfile['name'];

	
  if (get_magic_quotes_gpc()==0) $userfile['tmp_name'] = stripslashes($userfile['tmp_name']);
  if (uploadFile( $userfile['tmp_name'], $userfile['name'], $message )) {
//	if (uploadFile( stripslashes($userfile['tmp_name']), $userfile['name'], $message )) {
		$installer =& new $installerClass();
		if (!$installer->extractArchive( $userfile['name'] )) {
			$installer->cleanUpInstall();
			HTML_installer::showInstallMessage( $installer->getErrors(), sprintf(T_('Upload %s - Upload Failed'), $element),
				returnTo( $option, $element, $client ) );
		}
		$ret = $installer->install();
		$installer->cleanUpInstall();
		HTML_installer::showInstallMessage( $installer->getErrors(), T_('Upload ').$element.' - '.($ret ? T_('Success') : T_('Failed')),
			returnTo( $option, $element, $client ) );
	}
	else HTML_installer::showInstallMessage( $message, sprintf(T_('Upload %s -  Upload Error'), $element),
			returnTo( $option, $element, $client ) );
}

/**
* Install a template from a directory
* @param string The URL option
*/
function installFromDirectory( $installerClass, $option, $element, $client ) {
	$userfile = mosGetParam( $_REQUEST, 'userfile', '' );
	if (!$userfile) {
		mosRedirect( "index2.php?option=$option&element=module", T_('Please select a directory') );
	}
	$path = mosPathName( $userfile );
	if (!is_dir( $path )) {
		$path = dirname( $path );
	}
	$installer =& new $installerClass();
	$ret = $installer->install( $path );
	$installer->cleanUpInstall();
	HTML_installer::showInstallMessage( $installer->getErrors(), T_('Install new ').$element.' - '.($ret ? T_('Success') : T_('Error')),
		returnTo( $option, $element, $client ) );
}
/**
* Install a template from an HTTP URL
* @param string The URL option
*/
function installFromUrl( $installerClass, $option, $element, $client ) {
	// Check that the zlib is available
	if(!extension_loaded('zlib')) {
		$message = new mosError (T_('The installer can\'t continue before zlib is installed'), _MOS_ERROR_FATAL);
		HTML_installer::showInstallMessage($message, 'Installer - Error', returnTo( $option, $element, $client ));
		exit();
	}
	$userurl = mosGetParam( $_REQUEST, 'userurl', '' );
	if (!$userurl || $userurl[0]=='http://') {
		$message = new mosError (T_('Please select an HTTP URL'), _MOS_ERROR_FATAL);
		HTML_installer::showInstallMessage($message, T_('Installer - Error'), returnTo($option, $element, $client));
		exit();
	}
	foreach ($userurl as $value) {
	$url_data = parse_url($value);
	if (isset($url_data['path'])) $userfilename = basename($url_data['path']);
	else $userfilename = '';
	if (!$userfilename) {
		$message = new mosError (T_('The URL did not define a file name'), _MOS_ERROR_FATAL);
		HTML_installer::showInstallMessage($message, T_('Installer - Error'), returnTo($option, $element, $client));
	}
	if (uploadUrl($value, $userfilename, $message )) {
		$installer = new $installerClass();
		if (!$installer->extractArchive($userfilename)) {
			$installer->cleanUpInstall();
			HTML_installer::showInstallMessage( $installer->getErrors(), T_('Upload ').$element.' - '.T_('Failed'),
				returnTo( $option, $element, $client ) );
		}
		$ret = $installer->install();
		$installer->cleanUpInstall();
		HTML_installer::showInstallMessage( $installer->getErrors(), T_('Upload ').$element.' - '.($ret ? T_('Success') : T_('Failed')),
			returnTo( $option, $element, $client ) );
	} else {
		HTML_installer::showInstallMessage( $message, T_('Upload ').$element.' - '.T_('Error'),
			returnTo( $option, $element, $client ) );
	}
}
}
/**
* @param string The name of the php (temporary) uploaded file
* @param string The name of the file to put in the temp directory
* @param string The message to return
*/
function uploadFile( $filename, $userfile_name, &$error ) {
	global $mosConfig_absolute_path;
	$baseDir = mosPathName( $mosConfig_absolute_path . '/media' );

	if (file_exists( $baseDir )) {
		if (is_writable( $baseDir )) {
			if (move_uploaded_file( $filename, $baseDir . $userfile_name )) {
			    if (mosChmod( $baseDir . $userfile_name )) {
			        return true;
				} else {
					$msg = T_('Failed to change the permissions of the uploaded file.');
				}
			} else {
				$msg = T_('Failed to move uploaded file to <code>/media</code> directory.');
			}
		} else {
				$msg = "";
				switch($_FILES['userfile']['error']) {
					case UPLOAD_ERR_INI_SIZE:
						$msg = T_("<font color='red'>The uploaded file exceeds the 'upload_max_filesize' directive in php.ini.</font><br />");
					break;
					case UPLOAD_ERR_FORM_SIZE:
						$msg = T_("<font color='red'>The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.</font><br />");
					break;
					case UPLOAD_ERR_PARTIAL:
						$msg = T_("<font color='red'>The uploaded file was only partially uploaded.</font><br />");
					break;
					case UPLOAD_ERR_NO_FILE:
						$msg = T_("<font color='red'>No file was uploaded.</font><br />");
					break;
					case UPLOAD_ERR_NO_TMP_DIR:
						$msg = T_("<font color='red'>Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.</font><br />");
					break;
					case UPLOAD_ERR_CANT_WRITE:
						$msg = T_("<font color='red'>Failed to write file to disk. Introduced in PHP 5.1.0.</font><br />");
				}
				$msg .= T_('Failed to move uploaded file to <code>/media</code> directory.');
		}
	} else {
		    $msg = T_('Upload failed as <code>/media</code> directory is not writable.');
	}
	$error = new mosError ($msg, _MOS_ERROR_FATAL);
	return false;
}
/**
* @param string The name of the php (temporary) uploaded file
* @param string The name of the file to put in the temp directory
* @param string The message to return
*/
function uploadUrl( $userurl, $userfilename, &$error ) {
	global $mosConfig_absolute_path;
	$baseDir = mosPathName( $mosConfig_absolute_path . '/media' );
	if (file_exists( $baseDir )) {
		if (is_writable( $baseDir )) {
			if ($fpin = @fopen($userurl, 'rb') AND is_resource($fpin)) {
			    if ($fpout = @fopen($baseDir.$userfilename, 'wb') AND is_resource($fpout)) {
			        while (!feof($fpin)) {
			            $data = fgets($fpin, 1024);
			            fwrite($fpout, $data);
			        }
					fclose($fpout);
					fclose($fpin);
				    if (mosChmod( $baseDir.$userfilename )) return true;
					else $msg = T_('Failed to change the permissions of the uploaded file.');
				}
				else $msg = T_('Failed to open the local file from the URL.');
			}
			else $msg = T_('Failed to open the specified URL.');
		}
		else $msg = T_('Upload failed as <code>/media</code> directory is not writable.');
	}
	else $msg = T_('Upload failed as <code>/media</code> directory does not exist.');
	$error = new mosError ($msg, _MOS_ERROR_FATAL);
	return false;
}

	/**
	* Component uninstall method
	* @param int The id of the module
	* @param string The URL option
	* @param int The client id
	*/
	function component_uninstall( $cid, $option, $client=0 ) {
		$database =& mamboDatabase::getInstance();
		$sql = "SELECT * FROM #__components WHERE id=$cid";
		$database->setQuery($sql);
		if (!$database->loadObject( $row )) {
			$message = new mosError ($database->stderr(true), _MOS_ERROR_FATAL);
			HTML_installer::showInstallMessage($message, T_('Uninstall -  error'), "index2.php?option=$option&element=component");
			exit();
		}
		if ($row->iscore) {
			$message = new mosError (sprintf(T_('Component %s is a core component, and can not be uninstalled.<br />You need to unpublish it if you don\'t want to use it'), $row->name), _MOS_ERROR_FATAL);
			HTML_installer::showInstallMessage($message, 'Uninstall -  error', "index2.php?option=$option&element=component");
			exit();
		}
		// Try to find the XML file
		$here = mosPathName( mamboCore::get('mosConfig_absolute_path').'/administrator/components/'.$row->option );
		$filesindir = mosReadDirectory($here, '.xml$');
		if (count($filesindir) > 0) {
			$allerrors = new mosErrorSet();
			foreach ($filesindir as $file) {
				$parser =& new mosUninstallXML ($here.$file);
				$parser->uninstall();
				$allerrors->mergeAnother($parser->errors);
			}
	  		$ret = ($allerrors->getMaxLevel() < _MOS_ERROR_FATAL);
			HTML_installer::showInstallMessage( $allerrors->getErrors(), T_('Uninstall component - ').($ret ? T_('Success') : T_('Error')),
				returnTo( $option, 'component', $client ) );
		}
		else {
		    $com_name = $row->option;
			$dir = new mosDirectory(mosPathName(mamboCore::get('mosConfig_absolute_path').'/components/'.$com_name));
			$dir->deleteAll();
			$dir = new mosDirectory(mosPathName(mamboCore::get('mosConfig_absolute_path').'/administrator/components/'.$com_name));
			$dir->deleteAll();
			$sql = "DELETE FROM #__components WHERE `option`='$com_name'";
			$database->setQuery($sql);
			$database->query();
			$message = new mosError (T_('Uninstaller could not find XML file, but cleaned database'), _MOS_ERROR_WARN);
			HTML_installer::showInstallMessage($message, T_('Uninstall ').T_('component - ').T_('Success'), returnTo($option, 'component', $client));
		}
		exit();
	}

	/**
	* Module uninstall method
	* @param int The id of the module
	* @param string The URL option
	* @param int The client id
	*/
	function module_uninstall( $id, $option, $client=0 ) {
		$database =& mamboDatabase::getInstance();
		$mosConfig_absolute_path = mamboCore::get('mosConfig_absolute_path');
		$query = "SELECT module, iscore, client_id FROM #__modules WHERE id = '$id'";
		$database->setQuery( $query );
		$database->loadObject( $row );
		if ($row->iscore) {
			$message = new mosError (sprintf(T_('%s is a core module, and can not be uninstalled.<br />You need to unpublish it if you don\'t want to use it'), $row->title), _MOS_ERROR_FATAL);
			HTML_installer::showInstallMessage($message, 'Uninstall -  error', returnTo( $option, 'module', $row->client_id ? '' : 'admin' ) );
			exit();
		}
		$query = "DELETE FROM #__modules_menu WHERE moduleid=$id";
		$database->setQuery( $query );
		if (!$database->query()) {
		    $msg = $database->stderr;
		    die( $msg );
		}
		if ( $row->client_id ) $basepath = $mosConfig_absolute_path . '/administrator/modules/';
		else $basepath = $mosConfig_absolute_path . '/modules/';
  		$xmlfile = $basepath . $row->module . '.xml';
  		$parser =& new mosUninstallXML ($xmlfile);
  		$parser->uninstall();
  		$ret = ($parser->errors->getMaxLevel() < _MOS_ERROR_FATAL);
		HTML_installer::showInstallMessage( $parser->errors->getErrors(), T_('Uninstall module - ').($ret ? T_('Success') : T_('Error')),
			returnTo( $option, 'module', $client ) );
  		exit ();
	}

	/**
	* Mambot install method
	* @param int The id of the module
	* @param string The URL option
	* @param int The client id
	*/
	function mambot_uninstall( $id, $option, $client=0 ) {
		$database =& mamboDatabase::getInstance();
		$mosConfig_absolute_path = mamboCore::get('mosConfig_absolute_path');
		$database->setQuery( "SELECT name, folder, element, iscore FROM #__mambots WHERE id = $id" );
		$database->loadObject( $row );
		if ($database->getErrorNum()) {
			$message = new mosError ($database->stderr(), _MOS_ERROR_FATAL);
			HTML_installer::showInstallMessage($message, T_('Uninstall -  error'),
			returnTo( $option, 'mambot', $client ) );
			exit();
		}
		if ($row == null) {
			$message = new mosError (T_('Invalid object id'), _MOS_ERROR_FATAL);
			HTML_installer::showInstallMessage($message, T_('Uninstall -  error'), returnTo($option, 'mambot', $client));
			exit();
		}
		if (trim( $row->folder ) == '') {
			$message = new mosError (T_('Folder field empty, cannot remove files'), _MOS_ERROR_FATAL);
			HTML_installer::showInstallMessage($message, T_('Uninstall -  error'), returnTo($option, 'mambot', $client));
			exit();
		}
		$xmlfile = $mosConfig_absolute_path.'/mambots/'.$row->folder.'/'.$row->element.'.xml';
		if (file_exists($xmlfile)) {
			$parser =& new mosUninstallXML ($xmlfile);
			$ret = $parser->uninstall();
			$showerrors = $parser->getErrors();
		}
		else {
			$database->setQuery("DELETE FROM #__mambots WHERE id = $id");
			$ret = $database->query();
			$showerrors = new mosError (T_('Uninstaller did its best with no XML file present'), _MOS_ERROR_WARN);
		}
		HTML_installer::showInstallMessage( $showerrors, T_('Uninstall mambot - ').($ret ? T_('Success') : T_('Error')),
			returnTo( $option, 'mambot', $client ) );
		exit ();
	}

	/**
	* Template uninstall method
	* @param int The id of the module
	* @param string The URL option
	* @param int The client id
	*/
	function template_uninstall( $id, $option, $client=0 ) {
		$id = str_replace( array( '\\', '/' ), '', $id );
		$mosConfig_absolute_path = mamboCore::get('mosConfig_absolute_path');
		// Find if normal or admin template and delete corresponding files & directories
		if ($client=='admin') {
			$basepath = mamboCore::get('mosConfig_absolute_path').'/administrator/templates/' . $id; 
		}
		else {
			$basepath = mamboCore::get('mosConfig_absolute_path').'/templates/' . $id; 
		}
		//Use $basepath to remove the template files and directory	
		$tdir = new mosDirectory($basepath);
		$tdir->deleteAll();

		$message = new mosError (T_('Uninstall template - '), _MOS_ERROR_INFORM);
		HTML_installer::showInstallMessage($message, T_('Success'), returnTo($option, 'template', $client));
	    exit ();
	}

	/**
	* Language uninstall method
	* @param int The id of the module
	* @param string The URL option
	* @param int The client id
	*/
	function language_uninstall( $id, $option, $client=0 ) {
		$id = str_replace( array( '\\', '/' ), '', $id );
		$basepath = mamboCore::get('mosConfig_absolute_path').'/language/';
		$xmlfile = $basepath . $id . '.xml';
		// see if there is an xml install file, must be same name as element
		if (file_exists( $xmlfile )) {
			$parser =& new mosUninstallXML ($xmlfile);
			$parser->uninstall();
	  		$ret = ($parser->errors->getMaxLevel() < _MOS_ERROR_FATAL);
			HTML_installer::showInstallMessage( $parser->errors->getErrors(), T_('Uninstall language - ').($ret ? T_('Success') : T_('Error')),
				returnTo( $option, 'language', $client ) );
		}
		else {
			$message = new mosError (T_('Language id empty, cannot remove files'), _MOS_ERROR_FATAL);
			HTML_installer::showInstallMessage($message, T_('Uninstall -  error'), "index2.php?option=com_languages");
		}
		exit();
	}


?>
