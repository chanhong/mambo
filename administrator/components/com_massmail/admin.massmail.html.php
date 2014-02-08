<?php
/**
* @package Mambo
* @subpackage Massmail
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
* @package Mambo
* @subpackage Massmail
*/
class HTML_massmail {
	function messageForm( &$lists, $option ) {
		?>
		<script type="text/javascript">
			function submitbutton(pressbutton) {
				var form = document.adminForm;
				if (pressbutton == 'cancel') {
					submitform( pressbutton );
					return;
				}
				// do field validation
				if (form.mm_subject.value == ""){
					alert( '<?php echo T_('Please fill in the subject'); ?>' );
				} else if (getSelectedValue('adminForm','mm_group') < 0){
					alert( '<?php echo T_('Please select a group'); ?>' );
				} else if (form.mm_message.value == ""){
					alert( '<?php echo T_('Please fill in the message'); ?>' );
				} else {
					submitform( pressbutton );
				}
			}
		</script>

		<form action="index2.php" name="adminForm" method="post">
		<table class="adminheading">
		<tr>
			<th class="massemail">
			<?php echo T_('Mass Mail'); ?>
			</th>
		</tr>
		</table>

		<table class="adminform">
		<tr>
			<th colspan="2">
			<?php echo T_('Details'); ?>
			</th>
		</tr>
		<tr>
			<td width="150" valign="top">
			<?php echo T_('Group:'); ?>
			</td>
			<td width="85%">
			<?php echo $lists['gid']; ?>
			</td>
		</tr>
		<tr>
			<td>
			<?php echo T_('Mail to Child Groups:'); ?>
			</td>
			<td>
			<input type="checkbox" name="mm_recurse" value="RECURSE" />
			</td>
		</tr>
		<tr>
			<td>
			<?php echo T_('Send in HTML mode:'); ?>
			</td>
			<td>
			<input type="checkbox" name="mm_mode" value="1" />
			</td>
		</tr>
        		<tr>
			<td>
			<?php echo T_('Include blocked users:'); ?>
			</td>
			<td>
			<input type="checkbox" name="inc_blocked" value="0" />
			</td>
		</tr>
		<tr>
			<td>
			<?php echo T_('Subject'); ?>:
			</td>
			<td>
			<input class="inputbox" type="text" name="mm_subject" value="" size="50"/>
			</td>
		</tr>
		<tr>
			<td valign="top">
			<?php echo T_('Message:'); ?>
			</td>
			<td>
			<textarea cols="80" rows="25" name="mm_message" class="inputbox"></textarea>
			</td>
		</tr>
		</table>

		<input type="hidden" name="option" value="<?php echo $option; ?>"/>
		<input type="hidden" name="task" value=""/>
		</form>
		<?php
	}
}
?>