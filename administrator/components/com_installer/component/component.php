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

require_once( $mainframe->getPath( 'installer_html', 'component' ) );

showInstalledComponents( $option );

/**
* @param string The URL option
*/
function showInstalledComponents( $option ) {
	global $database, $mosConfig_absolute_path;

	$database->setQuery( "SELECT *"
	. "\n FROM #__components"
	. "\n WHERE parent = 0 AND iscore = 0"
	. "\n ORDER BY name"
	);
	$rows = $database->loadObjectList();

	// Read the component dir to find components
	$componentBaseDir	= mosPathName($mosConfig_absolute_path.'/administrator/components');
	$componentDirs = mosReadDirectory( $componentBaseDir );

	$n = count( $rows );
	for ($i = 0; $i < $n; $i++) {
	    $row =& $rows[$i];

		$dirName = mosPathName($componentBaseDir.$row->option);
		$xmlFilesInDir = mosReadDirectory( $dirName, '.xml$' );
		foreach ($xmlFilesInDir as $xmlfile) {
			// Read the file to see if it's a valid component XML file
			$parser =& new mosXMLDescription($dirName.$xmlfile);
			if ($parser->getType() != 'component') continue;
			$row->creationdate = $parser->getCreationDate('component');
			$row->author = $parser->getAuthor('component');
			$row->copyright = $parser->getCopyright('component');
			$row->authorEmail = $parser->getAuthorEmail('component');
			$row->authorUrl = $parser->getAuthorUrl('component');
			$row->version = $parser->getVersion('component');
			$row->mosname = strtolower( str_replace( " ", "_", $row->name ) );
		}
	}

	HTML_component::showInstalledComponents( $rows, $option );
}
?>
