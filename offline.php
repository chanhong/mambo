<?php
/**
* Site off-line page
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

// needed to seperate the ISO number from the language file constant _ISO
$iso = split( '=', _ISO );
// xml prolog
echo '<?xml version="1.0" encoding="'. $iso[1] .'"?' .'>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo mamboCore::get('mosConfig_sitename'); ?> - <?php echo T_('Offline') ?></title>
<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
</head>
<body>

<p>&nbsp;</p>
<table width="550" align="center" style="background-color: #ffffff; border: 1px solid">
<tr>
	<td width="60%" height="50" align="center">
	<img src="<?php echo mamboCore::get('mosConfig_live_site'); ?>/images/logo.png" alt="<?php echo T_('Mambo Logo') ?>" align="middle" />
	</td>
</tr>
<tr> 
	<td align="center">
	<h1>
	<?php echo mamboCore::get('mosConfig_sitename'); ?>
	</h1>
	</td>
</tr>
<?php
if ( mamboCore::get('mosConfig_offline') == 1 ) {
	?>
	<tr> 
		<td width="39%" align="center">
		<h2>
		<?php echo mamboCore::get('mosConfig_offline_message'); ?>
		</h2>
		</td>
	</tr>
	<?php
} else if (@$mosSystemError) {
	?>
	<tr> 
		<td width="39%" align="center">
		<h2>
		<?php echo mamboCore::get('mosConfig_error_message'); ?>
		</h2>
		<?php echo $mosSystemError; ?>
		</td>
	</tr>
	<?php
} else {
	?>
	<tr> 
		<td width="39%" align="center">
		<h2>
		<?php echo T_('For your security please completely remove the installation directory including all files and sub-folders  - then refresh this page'); ?>
		</h2>
		<h3>
		<?php echo T_('For support, the official forum can be found at:') . "<a href=\"http://forum.mambo-foundation.org\"> http://forum.mambo-foundation.org</a>"; ?> 
		</h3>
		</td>
	</tr>
	<?php
}
?>
</table>

</body>
</html>