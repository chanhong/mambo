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

// Set flag that this is a parent file
if (!defined('_VALID_MOS')) define( '_VALID_MOS', 1 );

// Include common.php
require_once( 'common.php' );
include_once( 'langconfig.php' );

$DBhostname = mosGetParam( $_POST, 'DBhostname', '' );
$DBuserName = mosGetParam( $_POST, 'DBuserName', '' );
$DBpassword = mosGetParam( $_POST, 'DBpassword', '' );
$DBname  	= mosGetParam( $_POST, 'DBname', '' );
$DBPrefix  	= mosGetParam( $_POST, 'DBPrefix', '' );
$sitename  	= mosGetParam( $_POST, 'sitename', '' );
$adminEmail = mosGetParam( $_POST, 'adminEmail', '');
$siteUrl  	= mosGetParam( $_POST, 'siteUrl', '' );
$absolutePath = mosGetParam( $_POST, 'absolutePath', '' );
$adminPassword = mosGetParam( $_POST, 'adminPassword', '');

$filePerms = '';
if (mosGetParam($_POST,'filePermsMode',0))
	$filePerms = '0'.
		(mosGetParam($_POST,'filePermsUserRead',0) * 4 +
		 mosGetParam($_POST,'filePermsUserWrite',0) * 2 +
		 mosGetParam($_POST,'filePermsUserExecute',0)).
		(mosGetParam($_POST,'filePermsGroupRead',0) * 4 +
		 mosGetParam($_POST,'filePermsGroupWrite',0) * 2 +
		 mosGetParam($_POST,'filePermsGroupExecute',0)).
		(mosGetParam($_POST,'filePermsWorldRead',0) * 4 +
		 mosGetParam($_POST,'filePermsWorldWrite',0) * 2 +
		 mosGetParam($_POST,'filePermsWorldExecute',0));

$dirPerms = '';
if (mosGetParam($_POST,'dirPermsMode',0))
	$dirPerms = '0'.
		(mosGetParam($_POST,'dirPermsUserRead',0) * 4 +
		 mosGetParam($_POST,'dirPermsUserWrite',0) * 2 +
		 mosGetParam($_POST,'dirPermsUserSearch',0)).
		(mosGetParam($_POST,'dirPermsGroupRead',0) * 4 +
		 mosGetParam($_POST,'dirPermsGroupWrite',0) * 2 +
		 mosGetParam($_POST,'dirPermsGroupSearch',0)).
		(mosGetParam($_POST,'dirPermsWorldRead',0) * 4 +
		 mosGetParam($_POST,'dirPermsWorldWrite',0) * 2 +
		 mosGetParam($_POST,'dirPermsWorldSearch',0));

if ((trim($adminEmail== "")) || (preg_match("/[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}/", $adminEmail )==false)) {
	echo "<form name=\"stepBack\" method=\"post\" action=\"install3.php\">
		<input type=\"hidden\" name=\"DBhostname\" value=\"$DBhostname\" />
		<input type=\"hidden\" name=\"DBuserName\" value=\"$DBuserName\" />
		<input type=\"hidden\" name=\"DBpassword\" value=\"$DBpassword\" />
		<input type=\"hidden\" name=\"DBname\" value=\"$DBname\" />
		<input type=\"hidden\" name=\"DBPrefix\" value=\"$DBPrefix\" />
		<input type=\"hidden\" name=\"DBcreated\" value=\"1\" />
		<input type=\"hidden\" name=\"sitename\" value=\"$sitename\" />
		<input type=\"hidden\" name=\"adminEmail\" value=\"$adminEmail\" />
		<input type=\"hidden\" name=\"siteUrl\" value=\"$siteUrl\" />
		<input type=\"hidden\" name=\"absolutePath\" value=\"$absolutePath\" />
		<input type=\"hidden\" name=\"filePerms\" value=\"$filePerms\" />
		<input type=\"hidden\" name=\"dirPerms\" value=\"$dirPerms\" />
		</form>";
	echo "<script>alert('".T_('You must provide a valid admin email address.')."'); document.stepBack.submit(); </script>";
	return;
}

if($DBhostname && $DBuserName && $DBname) {
	$configArray['DBhostname'] = $DBhostname;
	$configArray['DBuserName'] = $DBuserName;
	$configArray['DBpassword'] = $DBpassword;
	$configArray['DBname']	 = $DBname;
	$configArray['DBPrefix']   = $DBPrefix;
} else {
	echo "<form name=\"stepBack\" method=\"post\" action=\"install3.php\">
		<input type=\"hidden\" name=\"DBhostname\" value=\"$DBhostname\" />
		<input type=\"hidden\" name=\"DBuserName\" value=\"$DBuserName\" />
		<input type=\"hidden\" name=\"DBpassword\" value=\"$DBpassword\" />
		<input type=\"hidden\" name=\"DBname\" value=\"$DBname\" />
		<input type=\"hidden\" name=\"DBPrefix\" value=\"$DBPrefix\" />
		<input type=\"hidden\" name=\"DBcreated\" value=\"1\" />
		<input type=\"hidden\" name=\"sitename\" value=\"$sitename\" />
		<input type=\"hidden\" name=\"adminEmail\" value=\"$adminEmail\" />
		<input type=\"hidden\" name=\"siteUrl\" value=\"$siteUrl\" />
		<input type=\"hidden\" name=\"absolutePath\" value=\"$absolutePath\" />
		<input type=\"hidden\" name=\"filePerms\" value=\"$filePerms\" />
		<input type=\"hidden\" name=\"dirPerms\" value=\"$dirPerms\" />
		</form>";

	echo "<script>alert('".T_('The database details provided are incorrect and/or empty')."'); document.stepBack.submit(); </script>";
	return;
}

if ($sitename) {
	if (!get_magic_quotes_gpc()) {
		$configArray['sitename'] = addslashes($sitename);
	} else {
		$configArray['sitename'] = $sitename;
	}
} else {
	?>
		<form name=\"stepBack\" method=\"post\" action=\"install3.php\">
		<input type=\"hidden\" name=\"DBhostname\" value=\"$DBhostname\" />
		<input type=\"hidden\" name=\"DBuserName\" value=\"$DBuserName\" />
		<input type=\"hidden\" name=\"DBpassword\" value=\"$DBpassword\" />
		<input type=\"hidden\" name=\"DBname\" value=\"$DBname\" />
		<input type=\"hidden\" name=\"DBPrefix\" value=\"$DBPrefix\" />
		<input type=\"hidden\" name=\"DBcreated\" value=\"1\" />
		<input type=\"hidden\" name=\"sitename\" value=\"$sitename\" />
		<input type=\"hidden\" name=\"adminEmail\" value=\"$adminEmail\" />
		<input type=\"hidden\" name=\"siteUrl\" value=\"$siteUrl\" />
		<input type=\"hidden\" name=\"absolutePath\" value=\"$absolutePath\" />
		<input type=\"hidden\" name=\"filePerms\" value=\"$filePerms\" />
		<input type=\"hidden\" name=\"dirPerms\" value=\"$dirPerms\" />
		</form>


		<script type="text/javascript">alert('".T_('The sitename has not been provided'); document.stepBack2.submit();</script>
		<?php
	return;
}

if (file_exists( '../configuration.php' )) {
	$canWrite = is_writable( '../configuration.php' );
} else {
	$canWrite = is_writable( '..' );
}

if ($siteUrl) {
	$configArray['siteUrl']=$siteUrl;
	// Fix for Windows
	$absolutePath= str_replace("\\","/", $absolutePath);
	$absolutePath= str_replace("//","/", $absolutePath);
	$configArray['absolutePath']=$absolutePath;
	$configArray['filePerms']=$filePerms;
	$configArray['dirPerms']=$dirPerms;

	$config = "<?php\n";
	$config .= "\$mosConfig_offline = '0';\n";
	$config .= "\$mosConfig_host = '{$configArray['DBhostname']}';\n";
	$config .= "\$mosConfig_user = '{$configArray['DBuserName']}';\n";
	$config .= "\$mosConfig_password = '{$configArray['DBpassword']}';\n";
	$config .= "\$mosConfig_db = '{$configArray['DBname']}';\n";
	$config .= "\$mosConfig_dbprefix = '{$configArray['DBPrefix']}';\n";
	$config .= "\$mosConfig_lang = 'english';\n";
	$config .= "\$mosConfig_absolute_path = '{$configArray['absolutePath']}';\n";
	$config .= "\$mosConfig_live_site = '{$configArray['siteUrl']}';\n";
	$config .= "\$mosConfig_sitename = '{$configArray['sitename']}';\n";
	$config .= "\$mosConfig_shownoauth = '0';\n";
	$config .= "\$mosConfig_useractivation = '1';\n";
	$config .= "\$mosConfig_uniquemail = '1';\n";
	$config .= "\$mosConfig_offline_message = '".T_('This site is down for maintenance.<br /> Please check back again soon.')."';\n";
	$config .= "\$mosConfig_error_message = '".T_('This site is temporarily unavailable.<br /> Please notify the System Administrator')."';\n";
	$config .= "\$mosConfig_debug = '0';\n";
	$config .= "\$mosConfig_lifetime = '900';\n";
	$config .= "\$mosConfig_MetaDesc = '".T_('This site uses Mambo - the free, open source content management system')."';\n";
	$config .= "\$mosConfig_MetaKeys = 'mambo user, Mambo';\n";
	$config .= "\$mosConfig_MetaTitle = '1';\n";
	$config .= "\$mosConfig_MetaAuthor = '1';\n";
	$config .= "\$mosConfig_locale_debug = '0';\n";
	$config .= "\$mosConfig_locale_use_gettext = '0';\n";
	$config .= "\$mosConfig_locale = 'en';\n";
	$config .= "\$mosConfig_offset = '0';\n";
	$config .= "\$mosConfig_hideAuthor = '0';\n";
	$config .= "\$mosConfig_hideCreateDate = '0';\n";
	$config .= "\$mosConfig_hideModifyDate = '0';\n";
	$config .= "\$mosConfig_hidePdf = '".intval( !is_writable( "{$configArray['absolutePath']}/media/" ) )."';\n";
	$config .= "\$mosConfig_hidePrint = '0';\n";
	$config .= "\$mosConfig_hideEmail = '0';\n";
	$config .= "\$mosConfig_enable_log_items = '0';\n";
	$config .= "\$mosConfig_enable_log_searches = '0';\n";
	$config .= "\$mosConfig_enable_stats = '1';\n";
	$config .= "\$mosConfig_sef = '0';\n";
	$config .= "\$mosConfig_vote = '0';\n";
	$config .= "\$mosConfig_gzip = '0';\n";
	$config .= "\$mosConfig_multipage_toc = '1';\n";
	$config .= "\$mosConfig_allowUserRegistration = '1';\n";
	$config .= "\$mosConfig_link_titles = '0';\n";
	$config .= "\$mosConfig_error_reporting = '-1';\n";
	$config .= "\$mosConfig_register_globals = '1';\n";
	$config .= "\$mosConfig_list_limit = '50';\n";
	$config .= "\$mosConfig_caching = '0';\n";
	$config .= "\$mosConfig_cachepath = '{$configArray['absolutePath']}/cache';\n";
	$config .= "\$mosConfig_cachetime = '900';\n";
	$config .= "\$mosConfig_mailer = 'mail';\n";
	$config .= "\$mosConfig_mailfrom = '$adminEmail';\n";
	$config .= "\$mosConfig_fromname = '{$configArray['sitename']}';\n";
	$config .= "\$mosConfig_sendmail = '/usr/sbin/sendmail';\n";
	$config .= "\$mosConfig_smtpauth = '0';\n";
	$config .= "\$mosConfig_smtpuser = '';\n";
	$config .= "\$mosConfig_smtppass = '';\n";
	$config .= "\$mosConfig_smtphost = 'localhost';\n";
	$config .= "\$mosConfig_back_button = '0';\n";
	$config .= "\$mosConfig_item_navigation = '0';\n";
	$config .= "\$mosConfig_secret = '" . mosMakePassword(16) . "';\n";
	$config .= "\$mosConfig_pagetitles = '1';\n";
	$config .= "\$mosConfig_readmore = '1';\n";
	$config .= "\$mosConfig_hits = '1';\n";
	$config .= "\$mosConfig_icons = '1';\n";
	$config .= "\$mosConfig_favicon = 'favicon.ico';\n";
	$config .= "\$mosConfig_fileperms = '".$configArray['filePerms']."';\n";
	$config .= "\$mosConfig_dirperms = '".$configArray['dirPerms']."';\n";
	$config .= "\$mosConfig_helpurl = 'http://docs.mambo-foundation.org';\n";
	$config .= "\$mosConfig_mbf_content = '0';\n";
	$config .= "setlocale (LC_TIME, \$mosConfig_locale);\n";
	$config .= "?>";

	if ($canWrite && ($fp = fopen("../configuration.php", "w"))) {
		fputs( $fp, $config, strlen( $config ) );
		fclose( $fp );
	} else {
		$canWrite = false;
	} // if

	$cryptpass=md5($adminPassword);

	mysql_connect($DBhostname, $DBuserName, $DBpassword);
	mysql_select_db($DBname);

	// create the admin user
	$installdate = date("Y-m-d H:i:s");
	$query = "INSERT INTO `{$DBPrefix}users` VALUES (62, 'Administrator', 'admin', '$adminEmail', '$cryptpass', 'Super Administrator', 0, 1, 25, '$installdate', '0000-00-00 00:00:00', '', '')";
	mysql_query( $query );
	// add the ARO (Access Request Object)
	$query = "INSERT INTO `{$DBPrefix}core_acl_aro` VALUES (10,'users','62',0,'Administrator',0)";
	mysql_query( $query );
	// add the map between the ARO and the Group
	$query = "INSERT INTO `{$DBPrefix}core_acl_groups_aro_map` VALUES (25,'',10)";
	mysql_query( $query );

	// chmod files and directories if desired
   	$chmod_report = T_('Directory and file permissions left unchanged.');
	if ($filePerms != '' || $dirPerms != '') {
		$mosrootfiles = array(
			'administrator',
			'cache',
			'components',
			'editor',
			'files',
			'help',
			'images',
			'includes',
			'parameters',
			'installation',
			'language',
			'mambots',
			'media',
			'modules',
			'templates',
			'CHANGELOG',
			'configuration.php',
			'htaccess.txt',
			'index.php',
			'index2.php',
			'index3.php',
			'INSTALL',
			'LICENSE',
			'mainbody.php',
			'offline.php',
			'page404.php',
			'pathway.php',
            'README',
			'robots.txt'
		);
		$filemode = NULL;
		if ($filePerms != '') $filemode = octdec($filePerms);
		$dirmode = NULL;
		if ($dirPerms != '') $dirmode = octdec($dirPerms);
		$chmodOk = TRUE;
		foreach ($mosrootfiles as $file)
			if (!mosChmodRecursive($absolutePath.'/'.$file, $filemode, $dirmode))
				$chmodOk = FALSE;
		if ($chmodOk)
			$chmod_report = T_('File and directory permissions successfully changed.');
		else
			$chmod_report = T_('File and directory permissions could not be changed.<br/>'.
							'Please CHMOD mambo files and directories manually.');
	} // if chmod wanted
} else {
?>
	<form action="install3.php" method="post" name="stepBack3" id="stepBack3">
	  <input type="hidden" name="DBhostname" value="<?php echo $DBhostname;?>" />
	  <input type="hidden" name="DBusername" value="<?php echo $DBuserName;?>" />
	  <input type="hidden" name="DBpassword" value="<?php echo $DBpassword;?>" />
	  <input type="hidden" name="DBname" value="<?php echo $DBname;?>" />
	  <input type="hidden" name="DBPrefix" value="<?php echo $DBPrefix;?>" />
	  <input type="hidden" name="DBcreated" value="1" />
	  <input type="hidden" name="sitename" value="<?php echo $sitename;?>" />
	  <input type="hidden" name="adminEmail" value="$adminEmail" />
	  <input type="hidden" name="siteUrl" value="$siteUrl" />
	  <input type="hidden" name="absolutePath" value="$absolutePath" />
	  <input type="hidden" name="filePerms" value="$filePerms" />
	  <input type="hidden" name="dirPerms" value="$dirPerms" />
	</form>
	<script>alert('<?php echo T_('The site url has not been provided') ?>'); document.stepBack3.submit();</script>
<?php
}
echo "<?xml version=\"1.0\" encoding=\"".$charset."\"?".">";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $text_direction;?>">
<head>
<title><?php echo T_('Mambo - Web Installer') ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset ?>" />
<link rel="shortcut icon" href="../images/favicon.ico" />
<link rel="stylesheet" href="install<?php if($text_direction=='rtl') echo '_'.$text_direction ?>.css" type="text/css" />
</head>
<body>
<div id="wrapper">
	<div id="header">
		<div id="mambo"><img src="header_install.png" alt="<?php echo T_('Mambo Installation') ?>" /></div>
	</div>
</div>
<div id="ctr" align="center">
	<div class="install">
	<form action="dummy" name="form" id="form">
		<div id="stepbar">
			<div class="step-off"><?php echo T_('pre-installation check') ?></div>
			<div class="step-off"><?php echo T_('license') ?></div>
			<div class="step-off"><?php echo T_('step 1') ?></div>
			<div class="step-off"><?php echo T_('step 2') ?></div>
			<div class="step-off"><?php echo T_('step 3') ?></div>
			<div class="step-on"><?php echo T_('step 4') ?></div>
			<div class="far-right">
				<input class="button" type="button" name="runSite" value="<?php echo T_('View Site') ?>"
<?php
				if ($siteUrl) {
					echo "onClick='window.location.href=\"$siteUrl"."/index.php\" '";
				} else {
					echo "onClick='window.location.href=\"{$configArray['siteURL']}"."/index.php\" '";
				}
?>/>
				<input class="button" type="button" name="Admin" value="<?php echo T_('Administration') ?>"
<?php
				if ($siteUrl) {
					echo "onClick='window.location.href=\"$siteUrl"."/administrator/index.php\" '";
				} else {
					echo "onClick='window.location.href=\"{$configArray['siteURL']}"."/administrator/index.php\" '";
				}
?>/>
			</div>
		</div>
<div id="right2">
			<div id="step"><?php echo T_('step 4') ?></div>
			<div id="steposi"></div>
			<div class="clr"></div>
			<h1><?php echo T_('Congratulations! Mambo is installed') ?></h1>
			<div class="install-text"><?php echo T_('<p>Click the "View Site" button to start Mambo site or "Administration" to take you to administrator login. <br /><br />Please take a moment to fill out the form below.</p>') ?>
			</div>
			<div class="install-form">
				<div class="form-block">
					<table width="100%">
						<tr><td class="error" align="center"><?php echo T_('PLEASE REMEMBER TO COMPLETELY<br/>REMOVE THE INSTALLATION DIRECTORY') ?></td></tr>
						<tr><td align="center"><h5><?php echo T_('Administration Login Details') ?></h5></td></tr>
						<tr><td align="center" class="notice"><b><?php echo T_('Username :') ?> admin</b></td></tr>
						<tr><td align="center" class="notice"><b><?php echo T_('Password :') ?> <?php echo $adminPassword; ?></b></td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td align="right">&nbsp;</td></tr>
<?php					   if (!$canWrite) { ?>
						<tr>
							<td class="small"><?php echo T_('Your configuration file or directory is not writeable, or there was a problem creating the configuration file. You\'ll have to upload the following code by hand. Click in the textarea to highlight all of the code.') ?>
							</td>
						</tr>
						<tr>
							<td align="center">
								<textarea rows="5" cols="60" name="configcode" onClick="javascript:this.form.configcode.focus();this.form.configcode.select();" ><?php echo htmlspecialchars( $config );?></textarea>
							</td>
						</tr>
<?php					   } ?>
						<tr><td class="small"><?php /*echo $chmod_report*/; ?></td></tr>
					</table>
				</div>
			</div>
			<div id="break"></div>
		</div>
		<div class="clr"></div>
	</form>
	<div id="survey">
<form method="post" action="survey.php" name="email"><br />
  <table class="bodytext" border="0" cellpadding="3" cellspacing="0" width="100%">
    <tbody>
      <tr>
        <td>
        <div style="text-align: left;">The form below is optional. The information is intended to provide you with more information and to help us make Mambo better. We treat all data as confidential; it will not be shared with any third parties.<br /><br />
        </div>
        <table class="bodytext" border="0" cellpadding="3" cellspacing="0" width="100%">
          <tbody>
            <tr>
              <td colspan="4" bgcolor="#e0e0ff"><b>Your details:</b></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><b>Your name:</b></td>
              <td align="right">&nbsp;</td>
              <td><input maxlength="100" name="name" size="28" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><b>Your email address:</b></td>
              <td align="right">&nbsp;</td>
              <td><input maxlength="100" name="email" size="28" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><b>Your organization:</b></td>
              <td align="right">&nbsp;</td>
              <td><input maxlength="100" name="company" size="28" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><b>Intended use:</b></td>
              <td align="right">&nbsp;</td>
              <td>
              <select name="category">
              <option value="" selected="on">Select a category</option>
              <option value="personal">Personal</option>
              <option value="company">Company</option>
              <option value="government">Government</option>
              <option value="nonprofit">Non-profit</option>
              <option value="university">University</option>
              <option value="school">School</option>
              </select>
              </td>
            </tr>
            <tr>
              <td></td>
              <td><b>Subscribe to Mambo Security Announcement Mailing List?</b></td>
              <td></td>
              <td><a href="http://mambo-foundation.org/mailman/listinfo/security-notification_mambo-foundation.org" target="_blank">Click Here</a><br />
              </td>
            </tr>
            <tr>
              <td></td>
              <td><span style="font-weight: bold;">Are you interested in joining Team Mambo?</span></td>
              <td></td>
              <td><input name="teammambo" value="teammambo" type="checkbox" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td valign="top"><b>Additional comments / feedback:</b></td>
              <td valign="top">&nbsp;</td>
              <td valign="top"><textarea name="comments" cols="35" rows="3"></textarea>
              </td>
            </tr>
            <tr>
              <td></td>
              <td style="text-align: center;">Help support Mambo:</td>
              <td></td>
              <td>We appreciate your feedback!</td>
            </tr>
            <tr>
              <td></td>
              <td style="text-align: center;"><a
 href="http://www.mambo-foundation.org/view/Help_Support_the_Mambo_Foundation/" target="_blank"><img
 style="border: 0px solid ; width: 73px; height: 44px;" alt=""
 src="../images/M_images/donate.gif" /></a></td>
              <td></td>
              <td><input value="Proceed" type="submit" />
              <input value="Decline" type="submit" /></td>
            </tr>
          </tbody>
        </table>
        </td>
      </tr>
    </tbody>
  </table>
</form>
</div>
</div>
</div>
<div class="clr"></div>
<div class="ctr">
<?php echo T_('<a href="http://www.mambo-foundation.org" target="_blank">Mambo </a> is Free Software released under the <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">GNU/GPL License</a>.') ?>
</div>
</body>
</html>