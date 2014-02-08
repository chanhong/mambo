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
?>
<?php echo "<?xml version=\"1.0\"?>\r\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $mosConfig_sitename; ?> - <?php echo T_('Administration') ?> [Mambo]</title>
<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
<style type="text/css">
@import url(templates/mambo_admin/css/admin_login.css);
</style>
<script language="javascript" type="text/javascript">
	function setFocus() {
		document.loginForm.usrname.select();
		document.loginForm.usrname.focus();
	}
</script>
</head>
<body onload="setFocus();">
<div id="wrapper">
    <div id="header">
           <div id="mambo"><img src="templates/mambo_admin/images/mambo.gif" alt="<?php echo T_('Mambo Logo') ?>" /></div>
    </div>
</div>
<div id="ctr" align="center">
	<div class="login">
		<div class="login-form">
			<img src="templates/mambo_admin/images/login.gif" alt="<?php echo T_('Login') ?>" />
        	<form action="index.php" method="post" name="loginForm" id="loginForm">
			<div class="form-block">
	        	<div class="inputlabel"><?php echo T_('Username') ?></div>
		    	<div><input name="usrname" type="text" class="inputbox" size="15" /></div>
	        	<div class="inputlabel"><?php echo T_('Password') ?></div>
		    	<div><input name="pass" type="password" class="inputbox" size="15" /></div>
		    	<input type="hidden" name="option" value="login" />
	        	<div align="left"><input type="submit" name="submit" class="button" value="<?php echo T_('Login') ?>" /></div>
        	</div>
			</form>
    	</div>
		<div class="login-text">
			<div class="ctr"><img src="templates/mambo_admin/images/security.png" width="64" height="64" alt="security" /></div>
        	<p><?php echo T_('Welcome to Mambo!') ?></p>
			<p><?php echo T_('Use a valid username and password to gain access to the administration console.') ?></p>
    	</div>
		<div class="clr"></div>
	</div>
	
</div>
<div id="break"></div>
<noscript>
!Warning! Javascript must be enabled for proper operation of the Administrator
</noscript>
<div class="footer" align="center">
<?php
	include ($mosConfig_absolute_path . "/includes/footer.php");
?>
</div>
</body>
</html>
