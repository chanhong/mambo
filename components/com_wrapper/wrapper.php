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

/** load the html drawing class */
require_once( $mainframe->getPath( 'front_html' ) );

showWrap( $option );

function showWrap( $option ) {
	global $database, $Itemid, $mainframe;

	$menu =& new mosMenu( $database );
	$menu->load( $Itemid );
	$params =& new mosParameters( $menu->params );
	$params->def( 'back_button', $mainframe->getCfg( 'back_button' ) );
	$params->def( 'scrolling', 'auto' );
	$params->def( 'page_title', '1' );
	$params->def( 'pageclass_sfx', '' );
	$params->def( 'header', $menu->name );
	$params->def( 'height', '500' );
	$params->def( 'height_auto', '1' );
	$params->def( 'width', '100%' );
	$params->def( 'add', '1' );
	$url = $params->def( 'url', '' );
	
	if ( $params->get( 'add' ) ) {
		// adds 'http://' if none is set	
		if ( !strstr( $url, 'http' ) && !strstr( $url, 'https' ) ) {
			$row->url = 'http://'. $url;
		} else {
			$row->url = $url;
		}
	} else {
		$row->url = $url;
	}

	// auto height control
	if ( $params->def( 'height_auto' ) ) {
		$row->load = 'window.onload = iFrameHeight;';
	} else {
		$row->load = '';
	}

  $mainframe->SetPageTitle($menu->name);
	HTML_wrapper::displayWrap( $row, $params, $menu );
}

?>
