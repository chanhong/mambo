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

$tstart = mosProfiler::getmicrotime();
// needed to seperate the ISO number from the language file constant _ISO
$iso = explode( '=', _ISO );
// xml prolog
echo '<?xml version="1.0" encoding="'. $iso[1] .'"?' .'>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $mosConfig_sitename; ?> - <?php echo T_('Administration') ?> [Mambo]</title>
<link rel="stylesheet" href="templates/mambo_admin_blue/css/template_css.css" type="text/css" />
<link rel="stylesheet" href="templates/mambo_admin_blue/css/theme.css" type="text/css" />
<script language="JavaScript" src="<?php echo $mosConfig_live_site; ?>/includes/js/JSCookMenu.js" type="text/javascript"></script>
<script language="JavaScript" src="<?php echo $mosConfig_live_site; ?>/includes/js/ThemeOffice/theme.js" type="text/javascript"></script>
<script language="JavaScript" src="<?php echo $mosConfig_live_site; ?>/includes/js/mambojavascript.js" type="text/javascript"></script>
<?php
// if(@$_REQUEST["task"] == "edit" || @$_REQUEST["task"] == "new") {
// MUST be included ALWAYS for custom components to work
	include_once( $mosConfig_absolute_path . "/editor/editor.php" );
	initEditor();
//}
?>
<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
<!--
*	DO NOT REMOVE THE FOLLOWING - FAILURE TO COMPLY IS A DIRECT VIOLATION
*	OF THE GNU GENERAL PUBLIC LICENSE - http://www.gnu.org/copyleft/gpl.html
-->
<?php
echo "<meta name=\"Generator\" content=\"copyright  Refer to copyright.php  All rights reserved.\" />\r\n";
?>
<!--
*	END OF COPYRIGHT
-->
</head>
<body onload="MM_preloadImages('images/help_f2.png','images/archive_f2.png','images/back_f2.png','images/cancel_f2.png','images/delete_f2.png','images/edit_f2.png','images/new_f2.png','images/preview_f2.png','images/publish_f2.png','images/save_f2.png','images/unarchive_f2.png','images/unpublish_f2.png','images/upload_f2.png')">
<div id="mambover">
<?php echo 'Mambo version: '.$_VERSION->RELEASE.'.'. $_VERSION->DEV_LEVEL; ?>
</div>
<div id="wrapper">
    <div id="header">
           <div id="mambo"><img src="templates/mambo_admin_blue/images/header_text.png" alt="<?php echo T_('Mambo Logo') ?>" /></div>
    </div>
</div>
<?php if (!mosGetParam( $_REQUEST, 'hidemainmenu', 0 )) { ?>
<table width="100%" class="menubar" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td class="menubackgr"><?php mosLoadAdminModule( 'fullmenu' );?></td>
    <td class="menubackgr" align="right">
        <div id="wrapper1">
			<?php mosLoadAdminModules( 'header', 3 );?>
		</div>
	</td>
    <td class="menubackgr" align="right"><strong><?php echo $my->username;?>: </strong><a href="index2.php?option=logout" style="color: #333333; font-weight: bold"><?php echo T_('Logout') ?></a> &nbsp;</td>
    </tr>
</table>
<?php } ?>
<table width="100%" class="menubar" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td class="menudottedline" width="40%">
    	<?php mosLoadAdminModule( 'pathway' );?>
    </td>
    <td class="menudottedline" align="right">
  		<?php mosLoadAdminModule( 'toolbar' );?>
    </td>
  </tr>
</table>
<br />
<?php mosLoadAdminModule( 'mosmsg' );?>
<div align="center">
<div class="main">
<table width="100%" border="0">
  <tr>
    <td valign="middle" align="center">
	<?php
	// Show list of items to edit or delete or create new
	if ($path = $mainframe->getPath( 'admin' )) {
		require $path;
	} else {
		echo "<img src=\"images/logo.png\" border=\"0\" alt=\"".T_('Mambo Logo')."\" />\r\n<br />\r\n";
	}
	?>
   </td>
  </tr>
</table>
</div>
</div>
<table width="99%" border="0">
<tr>
<td align="center"><?php
include ($mosConfig_absolute_path . "/includes/footer.php");
echo ("<div class=\"smallgrey\">");
$tend = mosProfiler::getmicrotime();
$totaltime = ($tend - $tstart);
printf ("Page was generated in %f seconds", $totaltime);
echo ("</div>");
?>
</td></tr></table>
<?php mosLoadAdminModules( 'debug' );?>
</body>
</html>