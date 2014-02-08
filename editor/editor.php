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

if (!defined( '_MOS_EDITOR_INCLUDED' )) {
	$_MAMBOTS->loadBotGroup( 'editors' );
	$_MAMBOTS->loadBotGroup( 'editors-xtd' );

	function initEditor() {
		static $initiated;
		global $_MAMBOTS;
		
		if (!$initiated){
            $results = $_MAMBOTS->trigger( 'onInitEditor' );
			foreach ($results as $result) {
			    if (trim($result)) {
			        echo $result;
				}
			}
			$initiated = true;
		}
	}
	function getEditorContents( $editorArea, $hiddenField ) {
		global $_MAMBOTS;

		$results = $_MAMBOTS->trigger( 'onGetEditorContents', array( $editorArea, $hiddenField ) );
		foreach ($results as $result) {
		    if (trim($result)) {
		        echo $result;
			}
		}
	}
	// just present a textarea
	function editorArea( $name, $content, $hiddenField, $width, $height, $col, $row ) {
		global $_MAMBOTS;

		$results = $_MAMBOTS->trigger( 'onEditorArea', array( $name, $content, $hiddenField, $width, $height, $col, $row ) );
		foreach ($results as $result) {
		    if (trim($result)) {
		        echo $result;
			}
		}
	}
	define( '_MOS_EDITOR_INCLUDED', 1 );
}
?>