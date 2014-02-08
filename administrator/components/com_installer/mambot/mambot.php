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
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

// ensure user has access to this function
if ( !$acl->acl_check( 'administration', 'install', 'users', $my->usertype, $element . 's', 'all' ) ) {
	mosRedirect( 'index2.php', T_('You are not authorized to view this resource.') );
}

require_once( $mainframe->getPath( 'installer_html', 'mambot' ) );

showInstalledMambots( $option );

function showInstalledMambots( $_option ) {
	global $database, $mosConfig_absolute_path;

	$database->setQuery( "SELECT id, name, folder, element, client_id"
	. "\n FROM #__mambots"
	. "\n WHERE iscore='0'"
	. "\n ORDER BY folder, name"
	);
	$rows = $database->loadObjectList();

	// path to mambot directory
	$mambotBaseDir	= mosPathName($mosConfig_absolute_path.'/mambots');

	$id = 0;
	$n = count( $rows );
	for ($i = 0; $i < $n; $i++) {
	    $row =& $rows[$i];
		// xml file for module
		$xmlfile = mosPathName ($mambotBaseDir.$row->folder).$row->element.".xml";

		if (file_exists( $xmlfile )) {
			$parser =& new mosXMLDescription($xmlfile);
			if ($parser->getType() != 'mambot') continue;
			$row->creationdate = $parser->getCreationDate('mambot');
			$row->author = $parser->getAuthor('mambot');
			$row->copyright = $parser->getCopyright('mambot');
			$row->authorEmail = $parser->getAuthorEmail('mambot');
			$row->authorUrl = $parser->getAuthorUrl('mambot');
			$row->version = $parser->getVersion('mambot');

/*			$xmlDoc =& new DOMIT_Lite_Document();
			$xmlDoc->resolveErrors( true );
			if (!$xmlDoc->loadXML( $xmlfile, false, true )) {
				continue;
			}

			$element = &$xmlDoc->documentElement;

			if ($element->getTagName() != 'mosinstall') {
				continue;
			}
			if ($element->getAttribute( "type" ) != "mambot") {
				continue;
			}

			$element = &$xmlDoc->getElementsByPath('creationDate', 1);
			$row->creationdate = $element ? $element->getText() : '';

			$element = &$xmlDoc->getElementsByPath('author', 1);
			$row->author = $element ? $element->getText() : '';

			$element = &$xmlDoc->getElementsByPath('copyright', 1);
			$row->copyright = $element ? $element->getText() : '';

			$element = &$xmlDoc->getElementsByPath('authorEmail', 1);
			$row->authorEmail = $element ? $element->getText() : '';

			$element = &$xmlDoc->getElementsByPath('authorUrl', 1);
			$row->authorUrl = $element ? $element->getText() : '';

			$element = &$xmlDoc->getElementsByPath('version', 1);
			$row->version = $element ? $element->getText() : '';
*/
		}
	}

	HTML_mambot::showInstalledMambots($rows, $_option, $id, $xmlfile );
}
?>
