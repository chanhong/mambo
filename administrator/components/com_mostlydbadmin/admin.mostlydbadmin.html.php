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

/**
* @package Mambo_4.6
*/
class HTML_dbadmin
{
	function backupIntro( $tablelist, $p_option )
	{
	?>
		<table cellpadding="4" cellspacing="0"  width="100%">
		<tr>
			<td width="100%" class="sectionname"><img src="images/backup.png" class="imgcenter"><?php echo T_('Database Backup'); ?></td>
		</tr>
		</table>
		<form action="index2.php?option=com_mostlydbadmin&task=doBackup" method="post">
		<table  align="center" cellspacing="0" cellpadding="2" width="100%" class="adminform">
		</tr>
		<tr>
			<td><?php echo T_('Where would you like to back up your Database Tables to?'); ?><br /> <br />
				<input type="radio" name="OutDest" value="screen" />
					<?php echo T_('Display Results on the Screen'); ?><br /> 
				<input type="radio" name="OutDest" value="remote"/>
					<?php echo T_('Download to a file on my local computer'); ?><br /> 
				<input type="radio" name="OutDest" value="local" / checked="checked" >
					<?php echo T_('Store the file in the backup directory on the server'); ?>
			</td>
			<td>&nbsp;</td>
			<td><?php echo T_('What format would you like to save them as?'); ?><br /> <br />
			<?php if (function_exists('gzcompress'))
			{
			?>
			<input type="radio" name="OutType" value="zip" /><?php echo T_('As a Zip file'); ?><br />
			<?php
			}
			if (function_exists('bzcompress'))
			{
			?>
			<input type="radio" name="OutType" value="bzip" /><?php echo T_('As a BZip file'); ?><br />
			<?php
			}
			if (function_exists('gzencode'))
			{
			?>
			<input type="radio" name="OutType" value="gzip" /><?php echo T_('As a GZip file'); ?><br />
			<?php
			}
			?>
			<input type="radio" name="OutType" value="sql" checked="checked" /><?php echo T_('As a SQL (plain text) file'); ?>
			<br />
			<input type="radio" name="OutType" value="html" /><?php echo T_('As formatted HTML'); ?></td>
		</tr>
		<tr>
		<td> <p><?php echo T_('What do you want to back up?'); ?><br /><br />
			<input type="radio" name="toBackUp" value="data" /><?php echo T_('Data Only'); ?><br />
			<input type="radio" name="toBackUp" value="structure" /><?php echo T_('Structure Only'); ?><br />
			<input type="radio" name="toBackUp" value="both" checked="checked" /><?php echo T_('Data and Structure'); ?></p>
		</td>
		<td>&nbsp;</td>
		<td> <p align="left"><?php echo T_('Which Database Tables would you like to back up?<br />
          Please note, it is highly recommended you select ALL your tables.'); ?></p>
		  <?php echo $tablelist; ?>
		</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td align="center">&nbsp;<br /> <input type="submit" value="<?php echo T_('Backup the Selected Tables'); ?>" class="button" /></td>
		</tr>
	</table>
	</form>
	<?php
	}
	
	function restoreIntro($enctype,$uploads_okay,$local_backup_path)
	{
	?>
		<table cellpadding="4" cellspacing="0"  width="100%">
		<tr>
			<td width="100%" class="sectionname"><img src="images/dbrestore.png" class="imgcenter">Database Restore</td>
		</tr>
		</table>
		<table  align="center" cellspacing="0" cellpadding="2" width="100%" class="adminform">
		<form action="index2.php?option=com_mostlydbadmin&task=doRestore" method="post" <?php echo $enctype;?>>
		<tr>
			<th class="title" colspan="3"><?php echo T_('Existing Backups'); ?></th>
		</tr>
		<?php
	if (isset($local_backup_path))
	{
		if ($handle = @opendir($local_backup_path))
		{
		?>
		<tr><td>&nbsp;</td><td><strong><?php echo T_('Backup File Name'); ?></strong></td><td><strong><?php echo T_('Created Date/Time'); ?></strong></td></tr>
		<?php
		while ($file = @readdir($handle))
		{
			if (is_file($local_backup_path . "/" . $file))
			{
				if (eregi(".\.sql$",$file) || eregi(".\.bz2$",$file) || eregi(".\.gz$",$file) || eregi(".\.zip$",$file))
				{
					echo "\t\t<tr><td align=\"center\"><input type=\"radio\" name=\"file\" value=\"$file\"></td><td>$file</td><td>" . date("m/d/y H:i:sa", filemtime($local_backup_path . "/" . $file)) . "</td></tr>\n";
				}
			}
		}
		}
		else
		{
			echo "\t\t<tr><td colspan=\"3\" class=\"error\">". T_('Error!<br />Invalid or non-existant backup path in your configuration file :')." <br />" . $local_backup_path . "/" . $file . "</td></tr>\n";
		}
		@closedir($handle);
	}
	else
	{
		echo "\t\t<tr><td colspan=\"3\" class=\"error\">". T_('Error!<br />Backup path in your configuration file has not been configured.')."</td></tr>\n";
	}
	if ($uploads_okay)
	{
		?>
		<tr>
			<td colspan="3"><br /><?php echo T_('Or alternatively, if you\'ve downloaded a backup to your computer, you can restore from a local file :'); ?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><br /><input type="file" name="upfile" class="button"></td>
			<td>&nbsp;</td>
		</tr>
		<?php
	}
		?>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;<br />
			<input type="submit" class="button" value="<?php echo T_('Perform the Restore'); ?>" />&nbsp;&nbsp; <input type="reset" class="button" value="<?php echo T_('Reset'); ?>" /></td>
			<td>&nbsp;</td>
		</tr>
		</form>
	</table>
	<?php
	}
	function showDbAdminMessage($message,$title,$option,$task)
	{
		global $PHP_SELF;
		?>
			<table  cellpadding="4" cellspacing="0" width="100%" class="adminlist">
		<tr>
			<th width="55%" class="title"><?php echo $title; ?></th>
		</tr>
		<tr>
			<td align="left"><strong><?php echo $message; ?></strong></td>
		</tr>
		</table>
		<?php
	}

	function xquery( $sql='', $msg='', $rows=null, $option ) {
?>
<form action="index2.php" method="post" name="adminForm">
  <table cellpadding="4" cellspacing="0"  width="100%">
    <tr>
      <td width="100%" class="sectionname"><img src="images/query.png" class="imgcenter"><?php echo T_('Execute Query'); ?></td> 
      <td nowrap="nowrap">&nbsp;</td>
    </tr>
  </table>
 <table cellpadding="4" cellspacing="1"  width="100%" class="adminform">
	<tr>
		<td>SQL:</td>
	</tr>
	<tr>
		<td><textarea name="sql" rows="10" cols="80" class="inputbox"><?php echo $sql;?></textarea></td>
	</tr>
	<tr>
		<td>
			<input type="submit" value="<?php echo T_('Execute Query'); ?>" class="button" />
			<input type="button" value="<?php echo T_('Clear Query'); ?>" class="button" onclick="document.adminForm.sql.value=''" />
			<input type="checkbox" name="batch" value="1" /> <?php echo T_('Batch Mode'); ?>
		</td>
	</tr>
<?php	if ($msg) { ?>
	<tr>
		<td><?php echo $msg;?></td>
	</tr>
<?php	} ?>
<?php	
		if (is_array( $rows ) && count( $rows ) > 0) {
			$n = count( $rows );
?>
	<tr>
		<td>
			<table cellspacing="0" cellpadding="2" border="1">
				<tr>
					<th>#</th>
<?php		foreach($rows[0] as $key => $value) { ?>
					<th><?php echo $key;?></th>
<?php 		} ?>
				<tr>
<?php		for ($i=0; $i < $n; $i++) {
				echo "\n	<tr>";
				echo "\n		<td>$i</td>";
				foreach($rows[$i] as $key => $value) {
					echo "\n		<td>$value</td>";
				}
				echo "\n	</tr>";
			}
?>
			</table>
		</td>
	</tr>
<?php	} ?>
 </table>
<input type="hidden" name="option" value="<?php echo $option; ?>" />
<input type="hidden" name="task" value="xquery" />
</form>
<?php
	}
}
?>
