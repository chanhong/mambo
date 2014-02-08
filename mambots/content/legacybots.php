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

$_MAMBOTS->registerFunction( 'onPrepareContent', 'botLegacyBots' );

/**
* Process any legacy bots in the /mambots directory
*
* THIS FILE CAN BE **SAFELY REMOVED** IF YOU HAVE NO LEGACY MAMBOTS
* @param object A content object
* @param int A bit-wise mask of options
* @param int The page number
*/
function botLegacyBots( $published, &$row, &$params, $page=0 ) {
	global $mosConfig_absolute_path;

	// process any legacy bots
	$bots = mosReadDirectory( "$mosConfig_absolute_path/mambots", "\.php$" );
	sort( $bots );
	foreach ($bots as $bot) {
		require "mambots/$bot";
	}
}
?>
