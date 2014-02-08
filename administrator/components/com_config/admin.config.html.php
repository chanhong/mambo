<?php
/**
* @package Mambo
* @subpackage Config
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

class HTML_config {

	function showconfig( &$row, &$lists, $option) {
		global $mosConfig_absolute_path, $mosConfig_live_site;
		$tabs = new mosTabs(1);
?>
		<script type="text/javascript">
		<!--
	        function saveFilePerms()
	        {
				var f = document.adminForm;
				if (f.filePermsMode0.checked)
					f.config_fileperms.value = '';
				else {
					var perms = 0;
		        	if (f.filePermsUserRead.checked) perms += 400;
					if (f.filePermsUserWrite.checked) perms += 200;
					if (f.filePermsUserExecute.checked) perms += 100;
					if (f.filePermsGroupRead.checked) perms += 40;
					if (f.filePermsGroupWrite.checked) perms += 20;
					if (f.filePermsGroupExecute.checked) perms += 10;
					if (f.filePermsWorldRead.checked) perms += 4;
					if (f.filePermsWorldWrite.checked) perms += 2;
					if (f.filePermsWorldExecute.checked) perms += 1;
					f.config_fileperms.value = '0'+''+perms;
				}
	        }
	        function changeFilePermsMode(mode)
	        {
	            if(document.getElementById) {
	                switch (mode) {
	                    case 0:
	                        document.getElementById('filePermsValue').style.display = 'none';
	                        document.getElementById('filePermsTooltip').style.display = '';
	                        document.getElementById('filePermsFlags').style.display = 'none';
	                        break;
	                    default:
	                        document.getElementById('filePermsValue').style.display = '';
	                        document.getElementById('filePermsTooltip').style.display = 'none';
	                        document.getElementById('filePermsFlags').style.display = '';
	                } // switch
	            } // if
				saveFilePerms();
	        }
	        function saveDirPerms()
	        {
				var f = document.adminForm;
				if (f.dirPermsMode0.checked)
					f.config_dirperms.value = '';
				else {
					var perms = 0;
		        	if (f.dirPermsUserRead.checked) perms += 400;
					if (f.dirPermsUserWrite.checked) perms += 200;
					if (f.dirPermsUserSearch.checked) perms += 100;
					if (f.dirPermsGroupRead.checked) perms += 40;
					if (f.dirPermsGroupWrite.checked) perms += 20;
					if (f.dirPermsGroupSearch.checked) perms += 10;
					if (f.dirPermsWorldRead.checked) perms += 4;
					if (f.dirPermsWorldWrite.checked) perms += 2;
					if (f.dirPermsWorldSearch.checked) perms += 1;
					f.config_dirperms.value = '0'+''+perms;
				}
	        }
	        function changeDirPermsMode(mode)
	        {
	            if(document.getElementById) {
	                switch (mode) {
	                    case 0:
	                        document.getElementById('dirPermsValue').style.display = 'none';
	                        document.getElementById('dirPermsTooltip').style.display = '';
	                        document.getElementById('dirPermsFlags').style.display = 'none';
	                        break;
	                    default:
	                        document.getElementById('dirPermsValue').style.display = '';
	                        document.getElementById('dirPermsTooltip').style.display = 'none';
	                        document.getElementById('dirPermsFlags').style.display = '';
	                } // switch
	            } // if
				saveDirPerms();
	        }
        //-->
		</script>
		<form action="index2.php" method="post" name="adminForm">
		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
	    <table cellpadding="1" cellspacing="1" border="0" width="100%">
	    <tr>
	        <td width="250"><table class="adminheading"><tr><th nowrap class="config"><?php echo T_('Global Configuration'); ?></th></tr></table></td>
	        <td width="270">
	            <span class="componentheading"><?php echo T_('configuration.php is :'); ?>
	            <strong><?php echo is_writable( '../configuration.php' ) ? '<span class="green"> '.T_('Writeable').'</span>' : '<span class="red"> '.T_('Unwriteable').'</span>' ?></strong>
	            </span>
	        </td>
<?php
	        if (mosIsChmodable('../configuration.php')) {
	            if (is_writable('../configuration.php')) {
?>
	        <td>
	            <input type="checkbox" id="disable_write" name="disable_write" value="1"/>
	            <label for="disable_write"><?php echo T_('Make unwriteable after saving'); ?></label>
	        </td>
<?php
	            } else {
?>
	        <td>
	            <input type="checkbox" id="enable_write" name="enable_write" value="1"/>
	            <label for="enable_write"><?php echo T_('Override write protection while saving'); ?></label>
	        </td>
<?php
	            } // if
	        } // if
?>
	    </tr>
	    </table>
<?php
		$tabs->startPane("configPane");
		$tabs->startTab(T_("Site"),"site-page");
?>
		<table class="adminform">
		<tr>
			<td width="185"><?php echo T_('Site Offline:'); ?></td>
			<td><?php echo $lists['offline']; ?></td>
		</tr>
		<tr>
			<td valign="top"><?php echo T_('Offline Message:'); ?></td>
			<td><textarea class="text_area" cols="60" rows="2" style="width:500px; height:40px" name="config_offline_message"><?php echo htmlspecialchars($row->config_offline_message, ENT_QUOTES); ?></textarea><?php
				$tip = T_('A message that displays if your site is offline');
				echo mosToolTip( $tip );
			?></td>
		</tr>
		<tr>
			<td valign="top"><?php echo T_('System Error Message:'); ?></td>
			<td><textarea class="text_area" cols="60" rows="2" style="width:500px; height:40px" name="config_error_message"><?php echo htmlspecialchars($row->config_error_message, ENT_QUOTES); ?></textarea><?php
				$tip = T_('A message that displays if Mambo could not connect to the database');
				echo mosToolTip( $tip );
			?></td>
		</tr>
		<tr>
			<td><?php echo T_('Site Name:'); ?></td>
			<td><input class="text_area" type="text" name="config_sitename" size="50" value="<?php echo $row->config_sitename; ?>"/></td>
		</tr>
		<tr>
			<td><?php echo T_('Use Captcha Authentication:'); ?></td>
			<td><?php echo $lists['usecaptcha']; ?><?php
				$tip = T_('Captcha images assist in the reduction of automated entries and spam. Requires the GD image library');
				echo mosToolTip( $tip );
				echo T_('*Requires the GD image library.');?></td>
		</tr>
		<tr>
			<td><?php echo T_('Show UnAuthorized Links:'); ?></td>
			<td><?php echo $lists['auth']; ?><?php
				$tip = T_('If yes, will show links to content to registered content even if you are not logged in.  The user will need to login to see the item in full.');
				echo mosToolTip( $tip );
			?></td>
		</tr>
		<tr>
			<td><?php echo T_('Allow User Registration:'); ?></td>
			<td><?php echo $lists['allowuserregistration']; ?><?php
				$tip = T_('If yes, allows users to self-register');
				echo mosToolTip( $tip );
			?></td>
		</tr>
		<tr>
			<td><?php echo T_('Use New Account Activation:'); ?></td>
			<td><?php echo $lists['useractivation']; ?>
			<?php
				$tip = T_('If yes, the user will be mailed a link to activate their account before they can log in.');
				echo mosToolTip( $tip );
			?></td>
		</tr>

		<tr>
			<td><?php echo T_('Require Unique Email:'); ?></td>
			<td><?php echo $lists['uniquemail']; ?><?php
				$tip = T_('If yes, users cannot share the same email address');
				echo mosToolTip( $tip );
			?></td>
		</tr>

		<tr>
			<td><?php echo T_('Debug Site:'); ?></td>
			<td><?php echo $lists['debug']; ?><?php
				$tip = T_('If yes, displays diagnostic information and SQL errors if present');
				echo mosToolTip( $tip );
			?></td>
		</tr>
		<tr>
			<td><?php echo T_('WYSIWYG Editor:'); ?></td>
			<td><?php echo $lists['editor']; ?></td>
		</tr>
		<tr>
			<td><?php echo T_('List Length:'); ?></td>
			<td><?php echo $lists['list_length']; ?><?php
				$tip = T_('Sets the default length of lists in the administrator for all users');
				echo mosToolTip( $tip );
			?></td>
		</tr>
		<tr>
			<td><?php echo T_('Favorites Site Icon:'); ?></td>
			<td>
			<input class="text_area" type="text" name="config_favicon" size="20" value="<?php echo $row->config_favicon; ?>"/>
<?php
			$tip = T_('If left blank or the file cannot be found, the default favicon.ico will be used.');
			echo mosToolTip( $tip, T_('Favourite Icon') );
?>			</td>
		</tr>
		</table>
<?php
		$tabs->endTab();
		$tabs->startTab(T_('Locale'),"Locale-page");
?>
		<table class="adminform">
		<tr>
			<td width="185"><?php echo T_('Language:'); ?></td>
			<td><?php echo $lists['lang']; ?><input type="hidden" name="config_lang" value="<?php echo $row->config_lang; ?>"></td>
		</tr>
		<tr>
			<td width="185"><?php echo T_('Time Offset:'); ?></td>
			<td>
			<?php echo $lists['offset']; ?>
<?php
			$tip = sprintf(T_("Current date/time configured to display: %s"), mosCurrentDate(_DATE_FORMAT_LC2));
			echo mosToolTip($tip);
?>			</td>
		</tr>
		<tr>
			<td width="185"><?php echo T_('Debugging:'); ?></td>
			<td><?php echo $lists['locale_debug']; ?></td>
		</tr>
		<tr>
			<td width="185"><?php echo T_('Use Gettext:'); ?></td>
			<td><?php echo $lists['locale_use_gettext']; ?></td>
		</tr>
		</table>
<?php
		$tabs->endTab();
		$tabs->startTab(T_('Content'),"content-page");
?>
		<table class="adminform">
		<tr>
			<td colspan="3">* <?php echo T_('These Parameters control Output elements'); ?> *<br /><br /></td>
		</tr>
		<tr>
			<td width="200"><?php echo T_('Linked Titles:'); ?></td>
			<td width="100"><?php echo $lists['link_titles']; ?></td>
			<td><?php
				$tip = T_('If yes, the title of content items will be hyperlinked to the item');
				echo mosToolTip( $tip );
			?></td>
		</tr>
		<tr>
			<td width="200"><?php echo T_('Read More Link:'); ?></td>
			<td width="100"><?php echo $lists['readmore']; ?></td>
			<td><?php
				$tip = T_('If set to show, the read-more link will show if main-text has been provided for the item');
				echo mosToolTip( $tip );
			?></td>
		</tr>
		<tr>
			<td><?php echo T_('Item Rating/Voting:'); ?></td>
			<td><?php echo $lists['vote']; ?></td>
			<td><?php
				$tip = T_('If set to show, a voting system will be enabled for content items');
				echo mosToolTip( $tip );
			?></td>
		</tr>
		<tr>
			<td><?php echo T_('Author Names:'); ?></td>
			<td><?php echo $lists['hideauthor']; ?></td>
			<td><?php
				$tip = T_('If set to show, the name of the author will be displayed.  This a global setting but can be changed at menu and item levels.');
				echo mosToolTip( $tip );
			?></td>
		</tr>
		<tr>
			<td><?php echo T_('Created Date and Time:'); ?></td>
			<td><?php echo $lists['hidecreate']; ?></td>
			<td><?php
				$tip = T_('If set to show, the date and time an item was created will be displayed. This a global setting but can be changed at menu and item levels.');
				echo mosToolTip( $tip );
			?></td>
		</tr>
		<tr>
			<td><?php echo T_('Modified Date and Time:'); ?></td>
			<td><?php echo $lists['hidemodify']; ?></td>
			<td><?php
				$tip = T_('If set to show, the date and time an item was last modified will be displayed.  This a global setting but can be changed at menu and item levels.');
				echo mosToolTip( $tip );
			?></td>
		</tr>
		<tr>
			<td><?php echo T_('Hits:'); ?></td>
			<td><?php echo $lists['hits']; ?></td>
			<td><?php
				$tip = T_('If set to show, the hits for a particular item will be displayed.  This a global setting but can be changed at menu and item levels.');
				echo mosToolTip( $tip );
			?></td>
		</tr>
		<tr>
			<td><?php echo T_('PDF Icon:'); ?></td>
			<td><?php echo $lists['hidepdf']; ?></td>
<?php
			if (!is_writable( "$mosConfig_absolute_path/media/" )) {
				echo "<td align=\"left\">";
				echo mosToolTip(T_('Option not available as /media directory not writable'));
				echo "</td>";
			} else {
?>				<td>&nbsp;</td>
<?php
			}
?>		</tr>
		<tr>
			<td><?php echo T_('Print Icon:'); ?></td>
			<td><?php echo $lists['hideprint']; ?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><?php echo T_('Email Icon:'); ?></td>
			<td><?php echo $lists['hideemail']; ?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><?php echo T_('Icons:'); ?></td>
			<td><?php echo $lists['icons']; ?></td>
			<td><?php echo mosToolTip(T_('Print, PDF and Email will utilise Icons or Text')); ?></td>
		</tr>
		<tr>
			<td><?php echo T_('Table of Contents on multi-page items:'); ?></td>
			<td><?php echo $lists['multipage_toc']; ?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><?php echo T_('Back Button:'); ?></td>
			<td><?php echo $lists['back_button']; ?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><?php echo T_('Content Item Navigation:'); ?></td>
			<td><?php echo $lists['item_navigation']; ?></td>
			<td>&nbsp;</td>
		</tr>
<!-- prepared for future releases
		<tr>
			<td>Multi lingual content support:</td>
			<td><?php //echo $lists['ml_support']; ?></td>
			<td><?php //echo mosToolTip('In order to use multi lingual content you MUST have installed the MambelFish component.'); ?></td>
		</tr>
-->
		<input type="hidden" name="config_ml_support" value="<?php echo $row->config_ml_support?>">
		</table>
<?php
		$tabs->endTab();
		$tabs->startTab(T_("Database"),"db-page");
?>
		<table class="adminform">
		<tr>
			<td width="185"><?php echo T_('Hostname:'); ?></td>
			<td><input class="text_area" type="text" name="config_host" size="25" value="<?php echo $row->config_host; ?>"/></td>
		</tr>
		<tr>
			<td><?php echo T_('MySQL Username:'); ?></td>
			<td><input class="text_area" type="text" name="config_user" size="25" value="<?php echo $row->config_user; ?>"/></td>
		</tr>
		<tr>
			<td><?php echo T_('MySQL Password:'); ?></td>
			<td><input class="text_area" type="text" name="config_password" size="25" value="<?php echo $row->config_password; ?>"/></td>
		</tr>
		<tr>
			<td><?php echo T_('MySQL Database:'); ?></td>
			<td><input class="text_area" type="text" name="config_db" size="25" value="<?php echo $row->config_db; ?>"/></td>
		</tr>
		<tr>
			<td><?php echo T_('MySQL Database Prefix:'); ?></td>
			<td>
			<input class="text_area" type="text" name="config_dbprefix" size="10" value="<?php echo $row->config_dbprefix; ?>"/>
			&nbsp;<?php echo mosWarning(T_('!! DO NOT CHANGE UNLESS YOU HAVE A DATABASE BUILT USING TABLES WITH THE PREFIX YOU ARE SETTING !!')); ?>
			</td>
		</tr>
		</table>
<?php
		$tabs->endTab();
		$tabs->startTab(T_('Server'),"server-page");
?>
		<table class="adminform">
		<tr>
			<td width="185"><?php echo T_('Absolute Path:'); ?></td>
			<td width="450"><strong><?php echo $row->config_path; ?></strong></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><?php echo T_('Live Site:'); ?></td>
			<td><input class="text_area" type="text" name="config_live_site" size="50" value="<?php echo $row->config_live_site; ?>"/></td>
			<td>&nbsp;</td>
		</tr>
		<!--
		<tr>
			<td><?php echo T_('Secret Word:'); ?></td>
			<td><strong><?php echo $row->config_secret; ?></strong></td>
			<td>&nbsp;</td>
		</tr>
		-->
		<tr>
			<td><?php echo T_('GZIP Page Compression:'); ?></td>
			<td>
			<?php echo $lists['gzip']; ?>
			<?php echo mosToolTip(T_('Compress buffered output if supported')); ?>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><?php echo T_('Login Session Lifetime:'); ?></td>
			<td>
			<input class="text_area" type="text" name="config_lifetime" size="10" value="<?php echo $row->config_lifetime; ?>"/>
			&nbsp;<?php echo T_('seconds'); ?>&nbsp;
			<?php echo mosToolTip(T_('Auto logout after this time of inactivity')); ?>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><?php echo T_('Error Reporting:'); ?></td>
			<td><?php echo $lists['error_reporting']; ?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><?php echo T_('Register Globals Emulation:'); ?></td>
			<td>
			<?php echo $lists['register_globals']; ?>
			<?php
			echo mosToolTip(T_("Register globals emulation. Some components may stop working if this option is set to Off."));
			?>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><?php echo T_('Help Server:'); ?></td>
			<td><input class="text_area" type="text" name="config_helpurl" size="50" value="<?php echo $row->config_helpurl; ?>"/></td>
		</tr>
		<tr>
<?php
	$mode = 0;
	$flags = 0644;
	if ($row->config_fileperms!='') {
		$mode = 1;
		$flags = octdec($row->config_fileperms);
	} // if
?>
			<td valign="top"><?php echo T_('File Creation:'); ?></td>
	        <td>
	            <fieldset><legend><?php echo T_('File Permissions'); ?></legend>
	                <table cellpadding="1" cellspacing="1" border="0">
	                    <tr>
	                        <td><input type="radio" id="filePermsMode0" name="filePermsMode" value="0" onclick="changeFilePermsMode(0)"<?php if (!$mode) echo ' checked="checked"'; ?>/></td>
	                        <td><label for="filePermsMode0"><?php echo T_('Dont CHMOD new files (use server defaults)'); ?></label></td>
	                    </tr>
	                    <tr>
	                        <td><input type="radio" id="filePermsMode1" name="filePermsMode" value="1" onclick="changeFilePermsMode(1)"<?php if ($mode) echo ' checked="checked"'; ?>/></td>
	                        <td>
								<label for="filePermsMode1"><?php echo T_('CHMOD new files'); ?></label>
								<span id="filePermsValue"<?php if (!$mode) echo ' style="display:none"'; ?>>
								<?php echo T_('to:'); ?>	<input class="text_area" type="text" readonly="readonly" name="config_fileperms" size="4" value="<?php echo $row->config_fileperms; ?>"/>
								</span>
								<span id="filePermsTooltip"<?php if ($mode) echo ' style="display:none"'; ?>>
								&nbsp;<?php echo mosToolTip(T_('Select this option to define permission flags for new created files')); ?>
								</span>
							</td>
	                    </tr>
	                    <tr id="filePermsFlags"<?php if (!$mode) echo ' style="display:none"'; ?>>
	                        <td>&nbsp;</td>
	                        <td>
	                            <table cellpadding="0" cellspacing="1" border="0">
	                                <tr>
	                                    <td style="padding:0px"><?php echo T_('User:'); ?></td>
	                                    <td style="padding:0px"><input type="checkbox" id="filePermsUserRead" name="filePermsUserRead" value="1" onclick="saveFilePerms()"<?php if ($flags & 0400) echo ' checked="checked"'; ?>/></td>
	                                    <td style="padding:0px"><label for="filePermsUserRead"><?php echo T_('read'); ?></label></td>
	                                    <td style="padding:0px"><input type="checkbox" id="filePermsUserWrite" name="filePermsUserWrite" value="1" onclick="saveFilePerms()"<?php if ($flags & 0200) echo ' checked="checked"'; ?>/></td>
	                                    <td style="padding:0px"><label for="filePermsUserWrite"><?php echo T_('write'); ?></label></td>
	                                    <td style="padding:0px"><input type="checkbox" id="filePermsUserExecute" name="filePermsUserExecute" value="1" onclick="saveFilePerms()"<?php if ($flags & 0100) echo ' checked="checked"'; ?>/></td>
	                                    <td style="padding:0px" colspan="3"><label for="filePermsUserExecute"><?php echo T_('execute'); ?></label></td>
	                                </tr>
	                                <tr>
	                                    <td style="padding:0px"><?php echo T_('Group:'); ?></td>
	                                    <td style="padding:0px"><input type="checkbox" id="filePermsGroupRead" name="filePermsGroupRead" value="1" onclick="saveFilePerms()"<?php if ($flags & 040) echo ' checked="checked"'; ?>/></td>
	                                    <td style="padding:0px"><label for="filePermsGroupRead"><?php echo T_('read'); ?></label></td>
	                                    <td style="padding:0px"><input type="checkbox" id="filePermsGroupWrite" name="filePermsGroupWrite" value="1" onclick="saveFilePerms()"<?php if ($flags & 020) echo ' checked="checked"'; ?>/></td>
	                                    <td style="padding:0px"><label for="filePermsGroupWrite"><?php echo T_('write'); ?></label></td>
	                                    <td style="padding:0px"><input type="checkbox" id="filePermsGroupExecute" name="filePermsGroupExecute" value="1" onclick="saveFilePerms()"<?php if ($flags & 010) echo ' checked="checked"'; ?>/></td>
	                                    <td style="padding:0px" width="70"><label for="filePermsGroupExecute"><?php echo T_('execute'); ?></label></td>
										<td><input type="checkbox" id="applyFilePerms" name="applyFilePerms" value="1"/></td>
	                                    <td nowrap="nowrap">
											<label for="applyFilePerms">
												<?php echo T_('Apply to existing files'); ?>
												&nbsp;<?php
												echo mosWarning(
													T_('Checking here will apply the permission flags to <em>all existing files</em> of the site.<br />'.
													'<strong>INAPPROPRIATE USAGE OF THIS OPTION MAY RENDER THE SITE INOPERATIVE!</strong>')
												);?>
											</label>
										</td>
	                                </tr>
	                                <tr>
	                                    <td style="padding:0px"><?php echo T_('World:'); ?></td>
	                                    <td style="padding:0px"><input type="checkbox" id="filePermsWorldRead" name="filePermsWorldRead" value="1" onclick="saveFilePerms()"<?php if ($flags & 04) echo ' checked="checked"'; ?>/></td>
	                                    <td style="padding:0px"><label for="filePermsWorldRead"><?php echo T_('read'); ?></label></td>
	                                    <td style="padding:0px"><input type="checkbox" id="filePermsWorldWrite" name="filePermsWorldWrite" value="1" onclick="saveFilePerms()"<?php if ($flags & 02) echo ' checked="checked"'; ?>/></td>
	                                    <td style="padding:0px"><label for="filePermsWorldWrite"><?php echo T_('write'); ?></label></td>
	                                    <td style="padding:0px"><input type="checkbox" id="filePermsWorldExecute" name="filePermsWorldExecute" value="1" onclick="saveFilePerms()"<?php if ($flags & 01) echo ' checked="checked"'; ?>/></td>
	                                    <td style="padding:0px" colspan="4"><label for="filePermsWorldExecute"><?php echo T_('execute'); ?></label></td>
	                                </tr>
	                            </table>
	                        </td>
	                    </tr>
	                </table>
	            </fieldset>
	        </td>
			<td>&nbsp;</td>
	    </tr>
	    <tr>
<?php
	$mode = 0;
	$flags = 0755;
	if ($row->config_dirperms!='') {
		$mode = 1;
		$flags = octdec($row->config_dirperms);
	} // if
?>
			<td valign="top"><?php echo T_('Directory Creation:'); ?></td>
	        <td>
	            <fieldset><legend><?php echo T_('Directory Permissions'); ?></legend>
	                <table cellpadding="1" cellspacing="1" border="0">
	                    <tr>
	                        <td><input type="radio" id="dirPermsMode0" name="dirPermsMode" value="0" onclick="changeDirPermsMode(0)"<?php if (!$mode) echo ' checked="checked"'; ?>/></td>
	                        <td><label for="dirPermsMode0"><?php echo T_('Dont CHMOD new directories (use server defaults)'); ?></label></td>
	                    </tr>
	                    <tr>
	                        <td><input type="radio" id="dirPermsMode1" name="dirPermsMode" value="1" onclick="changeDirPermsMode(1)"<?php if ($mode) echo ' checked="checked"'; ?>/></td>
	                        <td>
								<label for="dirPermsMode1"><?php echo T_('CHMOD new directories'); ?></label>
								<span id="dirPermsValue"<?php if (!$mode) echo ' style="display:none"'; ?>>
   							    to: <input class="text_area" type="text" readonly="readonly" name="config_dirperms" size="4" value="<?php echo $row->config_dirperms; ?>"/>
								</span>
								<span id="dirPermsTooltip"<?php if ($mode) echo ' style="display:none"'; ?>>
								&nbsp;<?php echo mosToolTip(T_('Select this option to define permission flags for new created directories')); ?>
								</span>
							</td>
	                    </tr>
	                    <tr id="dirPermsFlags"<?php if (!$mode) echo ' style="display:none"'; ?>>
	                        <td>&nbsp;</td>
	                        <td>
	                            <table cellpadding="1" cellspacing="0" border="0">
	                                <tr>
	                                    <td style="padding:0px"><?php echo T_('User:'); ?></td>
	                                    <td style="padding:0px"><input type="checkbox" id="dirPermsUserRead" name="dirPermsUserRead" value="1" onclick="saveDirPerms()"<?php if ($flags & 0400) echo ' checked="checked"'; ?>/></td>
	                                    <td style="padding:0px"><label for="dirPermsUserRead"><?php echo T_('read'); ?></label></td>
	                                    <td style="padding:0px"><input type="checkbox" id="dirPermsUserWrite" name="dirPermsUserWrite" value="1" onclick="saveDirPerms()"<?php if ($flags & 0200) echo ' checked="checked"'; ?>/></td>
	                                    <td style="padding:0px"><label for="dirPermsUserWrite"><?php echo T_('write'); ?></label></td>
	                                    <td style="padding:0px"><input type="checkbox" id="dirPermsUserSearch" name="dirPermsUserSearch" value="1" onclick="saveDirPerms()"<?php if ($flags & 0100) echo ' checked="checked"'; ?>/></td>
	                                    <td style="padding:0px" colspan="3"><label for="dirPermsUserSearch"><?php echo T_('search'); ?></label></td>
	                                </tr>
	                                <tr>
	                                    <td style="padding:0px"><?php echo T_('Group:'); ?></td>
	                                    <td style="padding:0px"><input type="checkbox" id="dirPermsGroupRead" name="dirPermsGroupRead" value="1" onclick="saveDirPerms()"<?php if ($flags & 040) echo ' checked="checked"'; ?>/></td>
	                                    <td style="padding:0px"><label for="dirPermsGroupRead"><?php echo T_('read'); ?></label></td>
	                                    <td style="padding:0px"><input type="checkbox" id="dirPermsGroupWrite" name="dirPermsGroupWrite" value="1" onclick="saveDirPerms()"<?php if ($flags & 020) echo ' checked="checked"'; ?>/></td>
	                                    <td style="padding:0px"><label for="dirPermsGroupWrite"><?php echo T_('write'); ?></label></td>
	                                    <td style="padding:0px"><input type="checkbox" id="dirPermsGroupSearch" name="dirPermsGroupSearch" value="1" onclick="saveDirPerms()"<?php if ($flags & 010) echo ' checked="checked"'; ?>/></td>
	                                    <td style="padding:0px" width="70"><label for="dirPermsGroupSearch"><?php echo T_('search'); ?></label></td>
										<td><input type="checkbox" id="applyDirPerms" name="applyDirPerms" value="1"/></td>
	                                    <td nowrap="nowrap">
											<label for="applyDirPerms">
												<?php echo T_('Apply to existing directories'); ?>
												&nbsp;<?php
												echo mosWarning(T_('Checking here will apply the permission flags to <em>all existing directories</em> of the site.<br />'.
													'<strong>INAPPROPRIATE USAGE OF THIS OPTION MAY RENDER THE SITE INOPERATIVE!</strong>'));?>
											</label>
										</td>
	                                </tr>
	                                <tr>
	                                    <td style="padding:0px"><?php echo T_('World:'); ?></td>
	                                    <td style="padding:0px"><input type="checkbox" id="dirPermsWorldRead" name="dirPermsWorldRead" value="1" onclick="saveDirPerms()"<?php if ($flags & 04) echo ' checked="checked"'; ?>/></td>
	                                    <td style="padding:0px"><label for="dirPermsWorldRead"><?php echo T_('read'); ?></label></td>
	                                    <td style="padding:0px"><input type="checkbox" id="dirPermsWorldWrite" name="dirPermsWorldWrite" value="1" onclick="saveDirPerms()"<?php if ($flags & 02) echo ' checked="checked"'; ?>/></td>
	                                    <td style="padding:0px"><label for="dirPermsWorldWrite"><?php echo T_('write'); ?></label></td>
	                                    <td style="padding:0px"><input type="checkbox" id="dirPermsWorldSearch" name="dirPermsWorldSearch" value="1" onclick="saveDirPerms()"<?php if ($flags & 01) echo ' checked="checked"'; ?>/></td>
	                                    <td style="padding:0px" colspan="3"><label for="dirPermsWorldSearch"><?php echo T_('search'); ?></label></td>
	                                </tr>
	                            </table>
	                        </td>
	                    </tr>
	                </table>
	            </fieldset>
	        </td>
			<td>&nbsp;</td>
	      </tr>
		</table>
<?php
		$tabs->endTab();
		$tabs->startTab(T_('Metadata'),"metadata-page");
?>
		<table class="adminform">
		<tr>
			<td width="185" valign="top"><?php echo T_('Global Site Meta Description:'); ?></td>
			<td><textarea class="text_area" cols="50" rows="3" style="width:500px; height:50px" name="config_metadesc"><?php echo htmlspecialchars($row->config_metadesc, ENT_QUOTES); ?></textarea></td>
		</tr>
		<tr>
			<td valign="top"><?php echo T_('Global Site Meta Keywords:'); ?></td>
			<td><textarea class="text_area" cols="50" rows="3" style="width:500px; height:50px" name="config_metakeys"><?php echo htmlspecialchars($row->config_metakeys, ENT_QUOTES); ?></textarea></td>
		</tr>
		<tr>
			<td valign="top"><?php echo T_('Show Title Meta Tag:'); ?></td>
			<td>
			<?php echo $lists['metatitle']; ?>
			&nbsp;&nbsp;&nbsp;
			<?php echo mosToolTip(T_('Show the title meta tag when viewing content items')); ?>
			</td>
		  	</tr>
		<tr>
			<td valign="top"><?php echo T_('Show Author Meta Tag:'); ?></td>
			<td>
			<?php echo $lists['metaauthor']; ?>
			&nbsp;&nbsp;&nbsp;
			<?php echo mosToolTip(T_('Show the author meta tag when viewing content items')); ?>
			</td>
		</tr>
		</table>
<?php
		$tabs->endTab();
		$tabs->startTab(T_('Mail'),"mail-page");
?>
		<table class="adminform">
		<tr>
			<td width="185"><?php echo T_('Mailer:'); ?></td>
			<td><?php echo $lists['mailer']; ?></td>
		</tr>
		<tr>
			<td><?php echo T_('Mail From:'); ?></td>
			<td><input class="text_area" type="text" name="config_mailfrom" size="50" value="<?php echo $row->config_mailfrom; ?>"/></td>
		</tr>
		<tr>
			<td><?php echo T_('From Name:'); ?></td>
			<td><input class="text_area" type="text" name="config_fromname" size="50" value="<?php echo $row->config_fromname; ?>"/></td>
		</tr>
		<tr>
			<td><?php echo T_('Sendmail Path:'); ?></td>
			<td><input class="text_area" type="text" name="config_sendmail" size="50" value="<?php echo $row->config_sendmail; ?>"/></td>
		</tr>
		<tr>
			<td><?php echo T_('SMTP Auth:'); ?></td>
			<td><?php echo $lists['smtpauth']; ?></td>
		</tr>
		<tr>
			<td><?php echo T_('SMTP User:'); ?></td>
			<td><input class="text_area" type="text" name="config_smtpuser" size="50" value="<?php echo $row->config_smtpuser; ?>"/></td>
		</tr>
		<tr>
			<td><?php echo T_('SMTP Pass'); ?>:</td>
			<td><input class="text_area" type="text" name="config_smtppass" size="50" value="<?php echo $row->config_smtppass; ?>"/></td>
		</tr>
		<tr>
			<td><?php echo T_('SMTP Host:'); ?></td>
			<td><input class="text_area" type="text" name="config_smtphost" size="50" value="<?php echo $row->config_smtphost; ?>"/></td>
		</tr>
		</table>
<?php
		$tabs->endTab();
		$tabs->startTab(T_("Cache"),"cache-page");
?>
		<table class="adminform" border="0">
		<?php
		if (is_writeable($row->config_cachepath)) {
			?>
			<tr>
				<td width="185"><?php echo T_('Caching:'); ?></td>
				<td width="500"><?php echo $lists['caching']; ?></td>
				<td>&nbsp;</td>
			</tr>
			<?php
		}
		?>
		<tr>
			<td><?php echo T_('Cache Folder:'); ?></td>
			<td>
			<input class="text_area" type="text" name="config_cachepath" size="50" value="<?php echo $row->config_cachepath; ?>"/>
<?php
			if (is_writeable($row->config_cachepath)) {
				echo mosToolTip(T_('Current cache is directory is <strong>Writeable</strong>'));
			} else {
				echo mosWarning(T_('The cache directory is UNWRITEABLE - please set this directory to CHMOD755 before turning on the cache'));
			}
?>			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><?php echo T_('Cache Time:'); ?></td>
			<td><input class="text_area" type="text" name="config_cachetime" size="5" value="<?php echo $row->config_cachetime; ?>"/> <?php echo T_('seconds'); ?></td>
			<td>&nbsp;</td>
		</tr>
		</table>
		<?php
		$tabs->endTab();
		$tabs->startTab(T_('Statistics'),"stats-page");
		?>
		<table class="adminform">
		<tr>
			<td width="185"><?php echo T_('Statistics:'); ?></td>
			<td width="100"><?php echo $lists['enable_stats']; ?></td>
			<td><?php echo mostooltip(T_('Enable/disable collection of site statistics')); ?></td>
		</tr>
		<tr>
			<td><?php echo T_('Log Content Hits by Date:'); ?></td>
			<td><?php echo $lists['log_items']; ?></td>
			<td><span class="error"><?php echo mosWarning(T_('WARNING : Large amounts of data will be collected')); ?></span></td>
		</tr>
		<tr>
			<td><?php echo T_('Log Search Strings:'); ?></td>
			<td><?php echo $lists['log_searches']; ?></td>
			<td>&nbsp;</td>
		</tr>
		</table>
		<?php
		$tabs->endTab();
		$tabs->startTab(T_('SEO'),"seo-page");
		?>
		<table class="adminform">
		<tr>
			<td width="200"><strong><?php echo T_('Search Engine Optimization'); ?></strong></td>
			<td width="100">&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><?php echo T_('Search Engine Friendly URLs:'); ?></td>
			<td><?php echo $lists['sef']; ?>&nbsp;</td>
			<td><span class="error"><?php echo mosWarning(T_('Apache only! Rename htaccess.txt to .htaccess before activating')); ?></span></td>
		</tr>
		<tr>
			<td><?php echo T_('Dynamic Page Titles:'); ?></td>
			<td><?php echo $lists['pagetitles']; ?></td>
			<td><?php echo mosToolTip(T_('Dynamically changes the page title to reflect current content viewed')); ?></td>
		</tr>
		</table>
<?php
		$tabs->endTab();
		$tabs->endPane();
?>
		<input type="hidden" name="option" value="<?php echo $option; ?>"/>
		<input type="hidden" name="config_path" value="<?php echo $row->config_path; ?>"/>
		<input type="hidden" name="config_secret" value="<?php echo $row->config_secret; ?>"/>
	  	<input type="hidden" name="task" value=""/>
		</form>
		<script  type="text/javascript" src="<?php echo $mosConfig_live_site;?>/includes/js/overlib_mini.js"></script>
<?php
	}

}
?>