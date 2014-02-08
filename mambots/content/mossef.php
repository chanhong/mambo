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

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

$this->registerFunction( 'onPrepareContent', 'botMosSef' );

/**
* Converting internal relative links to SEF URLs
*
* <strong>Usage:</strong>
* <code><a href="...relative link..."></code>
*/
function botMosSef( $published, &$row, &$params, $page=0 ) {
	global $mosConfig_absolute_path, $mosConfig_live_site;

	if (is_callable(array($row, 'getText'))) $localtext = $row->getText();
	else $localtext = $row->text;
	// define the regular expression for the bot
	$regex = "#href=\"(.*?)\"#s";

	// perform the replacement
	$localtext = preg_replace_callback( $regex, 'botMosSef_replacer', $localtext );

	if (is_callable(array($row, 'saveText'))) $row->saveText($localtext);
	else $row->text = $localtext;

	return true;
}
/**
* Replaces the matched tags
* @param array An array of matches (see preg_match_all)
* @return string
*/
function botMosSef_replacer( &$matches ) {
	if ( substr($matches[1],0,1)=="#" ) {
		// anchor
		$temp = split("index.php", $_SERVER['REQUEST_URI']);
		return "href=\"".sefRelToAbs("index.php".@$temp[1]).$matches[1]."\"";
	} else {
		return "href=\"".sefRelToAbs($matches[1])."\"";
	}
}
?>
