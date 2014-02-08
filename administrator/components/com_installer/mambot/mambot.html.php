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

class HTML_mambot {
/**
* Displays the installed non-core Mambot
* @param array An array of mambot object
* @param strong The URL option
*/
	function showInstalledMambots( &$rows, $option ) {
		?>
		<table class="adminheading">
		<tr>
			<th class="install">
			<?php echo T_('Installed Mambots'); ?>
			</th>
		</tr>
		<tr>
			<td>
			<?php echo T_('Only those Mambots that can be uninstalled are displayed - some Core Mambots cannot be removed.'); ?>
			<br /><br />
			</td>
		</tr>
		</table>
		<?php
		if ( count( $rows ) ) { ?>
			<form action="index2.php" method="post" name="adminForm">
			<table class="adminlist">
			<tr>
				<th width="20%" class="title">
				<?php echo T_('Mambot'); ?>
				</th>
				<th width="10%" class="title">
				<?php echo T_('Type'); ?>
				</th>
				<!--
				Currently Unsupported
				<th width="10%" align="left">
				Client
				</th>
				-->
				<th width="10%" align="left">
				<?php echo T_('Author'); ?>
				</th>
				<th width="5%" align="center">
				<?php echo T_('Version'); ?>
				</th>
				<th width="10%" align="center">
				<?php echo T_('Date'); ?>
				</th>
				<th width="15%" align="left">
				<?php echo T_('Author Email'); ?>
				</th>
				<th width="15%" align="left">
				<?php echo T_('Author URL'); ?>
				</th>
			</tr>
			<?php
			$rc = 0;
			$n = count( $rows );
			for ($i = 0; $i < $n; $i++) {
			    $row =& $rows[$i];
				?>
				<tr class="<?php echo "row$rc"; ?>">
					<td>
					<input type="radio" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->id; ?>" onclick="isChecked(this.checked);">
					<span class="bold">
					<?php echo $row->name; ?>
					</span>
					</td>
					<td>
					<?php echo $row->folder; ?>
					</td>
					<!--
					Currently Unsupported
					<td>
					<?php //echo $row->client_id == "0" ? 'Site' : 'Administrator'; ?>
					</td>
					-->
					<td>
					<?php echo @$row->author != '' ? $row->author : "&nbsp;"; ?>
					</td>
					<td align="center">
					<?php echo @$row->version != '' ? $row->version : "&nbsp;"; ?>
					</td>
					<td align="center">
					<?php echo @$row->creationdate != '' ? $row->creationdate : "&nbsp;"; ?>
					</td>
					<td>
					<?php echo @$row->authorEmail != '' ? $row->authorEmail : "&nbsp;"; ?>
					</td>
					<td>
					<?php echo @$row->authorUrl != "" ? "<a href=\"" .(substr( $row->authorUrl, 0, 7) == 'http://' ? $row->authorUrl : 'http://'.$row->authorUrl). "\" target=\"_blank\">$row->authorUrl</a>" : "&nbsp;";?>
					</td>
				</tr>
				<?php
				$rc = 1 - $rc;
			}
			?>
			</table>

			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="option" value="com_installer" />
			<input type="hidden" name="element" value="mambot" />
			</form>
			<?php
		} else {
			?>
			<?php echo T_('There are no non-core, custom mambots installed.'); ?>
			<?php
		}
	}
}
?>
