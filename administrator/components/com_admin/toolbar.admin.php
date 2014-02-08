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

require_once( $mainframe->getPath( 'toolbar_html' ) );

switch ($task){
    case 'sysinfo':
        TOOLBAR_admin::_SYSINFO();
        break;
    case 'listcomponents':
    case 'sysinfo':
    case 'versioninfo':
    case 'help':
    case 'preview':
    case 'preview2':
        TOOLBAR_admin::_DEFAULT();
        break;
    case 'cpanel':
    default:
        TOOLBAR_admin::_CPANEL();
        break;
}
?>