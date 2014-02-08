<?php
/**
* @package Mambo
* @subpackage Admin
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
require_once( $mainframe->getPath( 'admin_html' ) );

switch ($task) {
	
	case 'redirect':
		$goto = trim( strtolower( mosGetParam( $_REQUEST, 'link' ) ) );
		if ($goto == 'null') {
			$msg = T_('There is no link associated with this item');
			mosRedirect( 'index2.php?option=com_admin&task=listcomponents', $msg );
			exit();
		}
		$goto = str_replace( "'", '', $goto );
		mosRedirect($goto);
		break;
		
	case 'listcomponents':
		HTML_admin_misc::ListComponents();
		break;
	
	case 'sysinfo':
		HTML_admin_misc::system_info( $version, $option );
		break;
		
	case 'versioninfo':
		HTML_admin_misc::version_info();
		break;

	case 'help':
		HTML_admin_misc::help();
		break;

	case 'preview':
		HTML_admin_misc::preview();
		break;

	case 'preview2':
		HTML_admin_misc::preview( 1 );
		break;

	case 'cpanel':
    default:
		HTML_admin_misc::controlPanel();
		break;

}
?>