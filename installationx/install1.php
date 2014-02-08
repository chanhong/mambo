<?php
/**
* Install instructions
* @package Mambo
* @author Mambo Foundation Inc see README.php
* @copyright Mambo Foundation Inc.
* See COPYRIGHT.php for copyright notices and details.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see
* LICENSE.php
* Mambo is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; version 2 of the
* License.
*/ 
if (!defined('_VALID_MOS')) define( '_VALID_MOS', 1 );

/** Include common.php */
include_once( 'common.php' );
include_once( 'langconfig.php' );

$DBhostname = mosGetParam( $_POST, 'DBhostname', '' );
$DBuserName = mosGetParam( $_POST, 'DBuserName', '' );
$DBpassword = mosGetParam( $_POST, 'DBpassword', '' );
$DBverifypassword = mosGetParam( $_POST, 'DBverifypassword', '' );
$DBname  	= mosGetParam( $_POST, 'DBname', '' );
$DBPrefix  	= mosGetParam( $_POST, 'DBPrefix', 'mos_' );
$DBDel  	= intval( mosGetParam( $_POST, 'DBDel', 0 ) );
$DBBackup  	= intval( mosGetParam( $_POST, 'DBBackup', 0 ) );
$DBSample  	= intval( mosGetParam( $_POST, 'DBSample', 1 ) );

echo "<?xml version=\"1.0\" encoding=\"".$charset."\"?".">";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $text_direction;?>">
<head>
<title><?php echo T_('Mambo - Web Installer') ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset ?>" />
<link rel="shortcut icon" href="../images/favicon.ico" />
<link rel="stylesheet" href="install<?php if($text_direction=='rtl') echo '_'.$text_direction ?>.css" type="text/css" />
<script  type="text/javascript">
<!--
function check()
{
	// form validation check
	var formValid=false;
	var f = document.form;
	if ( f.DBhostname.value == '' ) {
		alert('<?php echo T_('Please enter a Host name') ?>');
		f.DBhostname.focus();
		formValid=false;
	} else if ( f.DBuserName.value == '' ) {
		alert('<?php echo T_('Please enter a Database User Name') ?>');
		f.DBuserName.focus();
		formValid=false;	
	} else if ( f.DBname.value == '' ) {
		alert('<?php echo T_('Please enter a Name for your new Database') ?>');
		f.DBname.focus();
		formValid=false;
	} else if ( confirm('<?php echo T_('Are you sure these settings are correct? \nMambo will now attempt to populate a Database with the settings you have supplied') ?>')) {
		formValid=true;
	} 
	return formValid;
}
//-->
</script>
</head>
<body onload="document.form.DBhostname.focus();">
<div id="wrapper">
	<div id="header">
		<div id="mambo"><img src="header_install.png" alt="<?php echo T_('Mambo Installation') ?>" /></div>
	</div>
</div>
<div id="ctr" align="center">
	<form action="install2.php" method="post" name="form" id="form" onsubmit="return check();">
	<div class="install">
		<div id="stepbar">
			<div class="step-off"><?php echo T_('pre-installation check') ?></div>
			<div class="step-off"><?php echo T_('license') ?></div>
			<div class="step-on"><?php echo T_('step 1') ?></div>
			<div class="step-off"><?php echo T_('step 2') ?></div>
			<div class="step-off"><?php echo T_('step 3') ?></div>
			<div class="step-off"><?php echo T_('step 4') ?></div>
			<div class="far-right">
				<input class="button" type="submit" name="next" value="<?php echo T_('Next') ?> >>"/>
  			</div>
		</div>
		<div id="right">
	  		<div id="step"><?php echo T_('step 1') ?></div>
			<div id="steposi"></div>
  			<div class="clr"></div>
  			<h1><?php echo T_('MySQL database configuration:') ?></h1>
	  		<div class="install-text"><?php echo T_('<p>Setting up Mambo to run on your server involves 4 simple steps...</p><p>Please enter the hostname of the server Mambo is to be installed on.</p><p>Enter the MySQL username, password and database name you wish to use with Mambo.</p><p>Enter the table name prefix to be used by this Mambo instance and select how to do with in case existing tables from former installations.</p><p>Install the samples unless you are experienced Mambo user wanting to start with a completely empty site.</p><p><span class="red">Note:</span> MySQL Strict Mode is not supported!</p>') ?>
  			</div>
			<div class="install-form">
  	   			<div class="form-block">
  		 			<table class="content2">
  		  			<tr>
  						<td></td>
  						<td></td>
  						<td></td>
  					</tr>
  		  			<tr>
  						<td colspan="2"><?php echo T_('Host Name') ?><br/><input class="inputbox" type="text" name="DBhostname" value="<?php echo "$DBhostname"; ?>" /></td>
			  			<td><em><?php echo T_('This is usually "localhost"') ?></em></td>
  					</tr>
					<tr>
			  			<td colspan="2"><?php echo T_('MySQL User Name') ?><br/><input class="inputbox" type="text" name="DBuserName" value="<?php echo "$DBuserName"; ?>" /></td>
			  			<td><em><?php echo T_('Either something as "root" or a username given by the hoster') ?></em></td>
  					</tr>
			  		<tr>
			  			<td colspan="2"><?php echo T_('MySQL Password') ?><br/><input class="inputbox" type="password" name="DBpassword" value="<?php echo "$DBpassword"; ?>" /></td>
			  			<td><em><?php echo T_('For site security using a password for the mysql account is mandatory') ?></em></td>
					</tr>
					<tr>
			  			<td colspan="2"><?php echo T_('Verify MySQL Password') ?><br/><input class="inputbox" type="password" name="DBverifypassword" value="<?php echo "$DBverifypassword"; ?>" /></td>
			  			<td><em><?php echo T_('Retype password for verification') ?></em></td>
					</tr>
  		  			<tr>
  						<td colspan="2"><?php echo T_('MySQL Database Name') ?><br/><input class="inputbox" type="text" name="DBname" value="<?php echo "$DBname"; ?>" /></td>
			  			<td><em><?php echo T_('Some hosts allow only a certain DB name per site. Use table prefix in this case for distinct mambo sites.') ?></em></td>
  					</tr>
  		  			<tr>
  						<td colspan="2"><?php echo T_('MySQL Table Prefix') ?><br/><input class="inputbox" type="text" name="DBPrefix" value="<?php echo "$DBPrefix"; ?>" /></td>
			  			<td><em><?php echo T_('Dont use "old_" since this is used for backup tables') ?></em></td>
  					</tr>
  		  			<tr>
			  			<td><input type="checkbox" name="DBBackup" id="DBBackup" value="1" <?php if ($DBBackup) echo 'checked="checked"'; ?> /></td>
						<td><label for="DBBackup"><?php echo T_('Backup Old Tables') ?></label></td>
  						<td><em><?php echo T_('Any tables with the same prefix as that given in MySQL Table Prefix are renamed with the prefix old_ in preparation for a fresh install. If any tables with the old_ prefix already exist they will be removed and replaced by these new backups. Not selecting this option will cause any existing tables with the same names to simply be dropped without any backup copies being made.') ?></em></td>
			  		</tr>
  		  			<tr>
			  			<td><input type="checkbox" name="DBSample" id="DBSample" value="1" <?php if ($DBSample) echo 'checked="checked"'; ?> /></td>
						<td><label for="DBSample"><?php echo T_('Install Sample Data') ?></label></td>
			  			<td><em><?php echo T_('Dont uncheck this unless you are experienced with mambo!') ?></em></td>
			  		</tr>
		  		 	</table>
  				</div>
			</div>
		</div>
		<div class="clr"></div>
	</div>
	</form>
</div>
<div class="clr"></div>
<div class="ctr">
<?php echo T_('<a href="http://www.mambo-foundation.org" target="_blank">Mambo </a> is Free Software released under the <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">GNU/GPL License</a>.') ?>
</div>
</body>
</html>
