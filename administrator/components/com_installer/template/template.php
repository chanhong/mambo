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
if ( !$acl->acl_check( 'administration', 'install', 'users', $my->usertype, $element . 's', 'all' ) ) {
	mosRedirect( 'index2.php', T_('You are not authorized to view this resource.') );
}

$client = mosGetParam( $_REQUEST, 'client', '' );

$userfile = mosGetParam( $_REQUEST, 'userfile', dirname( __FILE__ ) );
$userfile = mosPathName( $userfile );

HTML_installer::showInstallForm( T_('Install new Template').' <small><small>[ ' . ($client == 'admin' ? T_('Administrator') : T_('Site')) .' ]</small></small>',
	$option, 'template', $client, $userfile,
	'<a href="index2.php?option=com_templates&client='.$client.'">'.T_('Back to Templates').'</a>'
);
?>
<table class="content">
<?php
writableCell( 'media' );
writableCell( 'administrator/templates' );
writableCell( 'templates' );
writableCell( 'images/stories' );
?>
</table>
