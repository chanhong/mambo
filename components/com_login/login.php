<?php
/**
* @package Mambo
* @subpackage Users
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

// load the html drawing class
require_once( $mainframe->getPath( 'front_html' ) );

global $database, $my;
global $mosConfig_live_site;

$return = mosGetParam( $_SERVER, 'REQUEST_URI', null );
$return = ampReplace( $return );

$menuhandler =& mosMenuHandler::getInstance();
$menu =& $menuhandler->getMenuByID($Itemid);
$params =& new mosParameters( $menu->params );

$params->def( 'page_title', 1 );
$params->def( 'header_login', $menu->name );
$params->def( 'header_logout', $menu->name );
$params->def( 'pageclass_sfx', '' );
$params->def( 'back_button', $mainframe->getCfg( 'back_button' ) );
$params->def( 'login', $mosConfig_live_site );
$params->def( 'logout', $mosConfig_live_site );
$params->def( 'login_message', 0 );
$params->def( 'logout_message', 0 );
$params->def( 'description_login', 1 );
$params->def( 'description_logout', 1 );
$params->def( 'description_login_text', T_('To access the Private areas of this site please Login') );
$params->def( 'description_logout_text', T_('You are now Logged in to a private area of this site') );
$params->def( 'image_login', 'key.jpg' );
$params->def( 'image_logout', 'key.jpg' );
$params->def( 'image_login_align', 'right' );
$params->def( 'image_logout_align', 'right' );
$params->def( 'registration', $mainframe->getCfg( 'allowUserRegistration' ) );

$image_login = '';
$image_logout = '';
if ( $params->get( 'image_login' ) <> -1 ) {
	$image = $mosConfig_live_site .'/images/stories/'. $params->get( 'image_login' );
	$image_login = '<img src="'. $image  .'" align="'. $params->get( 'image_login_align' ) .'" hspace="10" alt="" />';
}
if ( $params->get( 'image_logout' ) <> -1 ) {
	$image = $mosConfig_live_site .'/images/stories/'. $params->get( 'image_logout' );
	$image_logout = '<img src="'. $image .'" align="'. $params->get( 'image_logout_align' ) .'" hspace="10" alt="" />';
}

if ( $my->id ) {
	loginHTML::logoutpage( $params, $image_logout );
} else {
	loginHTML::loginpage( $params, $image_login );
}

?>
