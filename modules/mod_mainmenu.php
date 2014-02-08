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

$menuhandler =& mosMenuHandler::getInstance();
$params->def( 'menutype', 'mainmenu' );
$params->def( 'class_sfx', '' );
$params->def( 'menu_images', 0 );
$params->def( 'menu_images_align', 0 );
$params->def( 'expand_menu', 0 );
$params->def( 'indent_image', 0 );
$params->def( 'indent_image1', 'indent1.png' );
$params->def( 'indent_image2', 'indent2.png' );
$params->def( 'indent_image3', 'indent3.png' );
$params->def( 'indent_image4', 'indent4.png' );
$params->def( 'indent_image5', 'indent5.png' );
$params->def( 'indent_image6', 'indent.png' );
$params->def( 'spacer', '' );
$params->def( 'end_spacer', '' );

$menu_style = $params->get( 'menu_style', 'vert_indent' );

switch ( $menu_style ) {
	case 'list_flat':
	$menuhandler->mosShowHFMenu( $params, 1 );
	break;

	case 'horiz_flat':
	$menuhandler->mosShowHFMenu( $params, 0 );
	break;

	default:
	$menuhandler->mosShowVIMenu( $params );
	break;
}
?>
