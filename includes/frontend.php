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

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
/**
* Displays the capture output of the main element
*/
function mosMainBody() {
	$handler =& mosComponentHandler::getInstance();
	$handler->mosMainBody();
}
/**
* Utility functions and classes
*/
function mosLoadComponent( $name ) {
	// set up some global variables for use by the frontend component
	global $mainframe, $database;
	include( $mainframe->getCfg( 'absolute_path' )."/components/com_$name/$name.php" );
}
/**
* @param string THe template position
*/
function mosCountModules( $position='left' ) {
	$handler =& mosModuleHandler::getInstance();
	return $handler->mosCountModules($position);
}

/**
* @param string The position
* @param int The style.  0=normal, 1=horiz, -1=no wrapper
*/
function mosLoadModules( $position='left', $style=0 ) {
	$handler =& mosModuleHandler::getInstance();
	return $handler->mosLoadModules($position, $style);
}
/**
* Assembles head tags
*/
function mosShowHead($keys='', $exclude='') {
	$mainframe =& mosMainFrame::getInstance();
	$mainframe->mosShowHead($keys, $exclude);
}
?>
