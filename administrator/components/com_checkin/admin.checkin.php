<?php
/**
* @package Mambo
* @subpackage Checkin
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

if (!$acl->acl_check( 'administration', 'config', 'users', $my->usertype )) {
	mosRedirect( 'index2.php', T_('You are not authorized to view this resource.') );
}
?>
<table class="adminheading">
<tr>
<th class="checkin"><?php echo T_('Global Check-in') ?></th>
</tr>
</table>
<table class="adminform">
<tr>
	<th class="title"><?php echo T_('Database Table') ?></th>
	<th class="title"><?php echo T_('# of Items') ?></th>
	<th class="title"><?php echo T_('Checked-In') ?></th>
	<th class="title">&nbsp;</th>
</tr>
<?php
$lt = mysql_list_tables($mosConfig_db);
$k = 0;
while (list($tn) = mysql_fetch_array( $lt )) {
	// make sure we get the right tables based on prefix
	if (!preg_match( "/^".$mosConfig_dbprefix."/i", $tn )) {
		continue;
	}
	$lf = mysql_list_fields($mosConfig_db, "$tn");
	$nf = mysql_num_fields($lf);

	$foundCO = false;
	$foundCOT = false;
	$foundE = false;
	for ($i = 0; $i < $nf; $i++) {
		$fname = mysql_field_name($lf, $i);
		if ( $fname == 'checked_out') {
			$foundCO = true;
		} else if ( $fname == 'checked_out_time') {
			$foundCOT = true;
		} else if ( $fname == 'editor') {
			$foundE = true;
		}
	}

	if ($foundCO && $foundCOT) {
		if ($foundE) {
			$database->setQuery( "SELECT checked_out, editor FROM $tn WHERE checked_out > 0" );
		} else {
			$database->setQuery( "SELECT checked_out FROM $tn WHERE checked_out > 0" );
		}
		$res = $database->query();
		$num = $database->getNumRows( $res );

		if ($foundE) {
			$database->setQuery( "UPDATE $tn SET checked_out=0, checked_out_time='00:00:00', editor=NULL WHERE checked_out > 0" );
		} else {
			$database->setQuery( "UPDATE $tn SET checked_out=0, checked_out_time='0000-00-00 00:00:00' WHERE checked_out > 0" );
		}
		$res = $database->query();

		if ($res == 1) {
			if ($num > 0) {
				echo "<tr class=\"row$k\">";
				echo "\n	<td width=\"350\">".T_('Checking table')." - $tn</td>";
				echo "\n	<td width=\"150\">".sprintf(Tn_('Checked in <strong>%d</strong> item','Checked in <strong>%d</strong> items', $num), $num)."</td>";
				echo "\n	<td width=\"100\" align=\"center\"><img src=\"images/tick.png\" border=\"0\" alt=\"tick\" /></td>";
				echo "\n	<td>&nbsp;</td>";
				echo "\n</tr>";
			} else {
				echo "<tr class=\"row$k\">";
				echo "\n	<td width=\"350\">".T_('Checking table')." - $tn</td>";
				echo "\n	<td width=\"150\">".sprintf(Tn_('Checked in <strong>%d</strong> item','Checked in <strong>%d</strong> items', $num), $num)."</td>";
				echo "\n	<td width=\"100\">&nbsp;</td>";
				echo "\n	<td>&nbsp;</td>";
				echo "\n</tr>";
			}
			$k = 1 - $k;
		}
	}
}
?>
<tr>
	<td colspan="4"><strong><?php echo T_('Checked out items have now been all checked in') ?></strong></td>
</tr>
</table>
