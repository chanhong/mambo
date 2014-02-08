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
//if ( !$acl->acl_check( 'administration', 'install', 'users', $my->usertype, $element . 's', 'all' ) ) {
//	mosRedirect( 'index2.php', T_('You are not authorized to view this resource.') );
//}

require_once( $mainframe->getPath( 'installer_html', 'component' ) );

$suppress_install = false;
if (!$suppress_install) {
HTML_installer::showInstallForm( T_('Install Mambo plugin (component, module, mambot, template, include, parameter, composite)'), $option, 'universal', '', dirname(__FILE__) );
?>
<table class="content">
<?php
writableCell( 'administrator/components' );
writableCell( 'administrator/modules' );
writableCell( 'administrator/templates' );
writableCell( 'components' );
writableCell( 'mambots' );
writableCell( 'mambots/content' );
writableCell( 'mambots/editors' );
writableCell( 'mambots/editors-xtd' );
writableCell( 'mambots/search' );
writableCell( 'media' );
writableCell( 'modules' );
writableCell( 'templates' );
?>
</table>
<?php
}

?>
