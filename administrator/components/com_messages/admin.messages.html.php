<?php
/**
* @package Mambo
* @subpackage Messages
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

class HTML_messages {
	function showMessages( &$rows, $pageNav, $search, $option ) {
?>
<form action="index2.php" method="post" name="adminForm">
  <table class="adminheading">
    <tr>
      <th class="inbox"><?php echo T_('Private Messaging'); ?></th>
      <td><?php echo T_('Search:'); ?></td>
      <td> <input type="text" name="search" value="<?php echo $search;?>" class="inputbox" onChange="document.adminForm.submit();" />
      </td>
    </tr>
  </table>
  <table class="adminlist">
    <tr>
      <th width="20">#</th>
      <th width="5%" class="title"> <input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count($rows); ?>);" />
      </th>
      <th width="60%" class="title"><?php echo T_('Subject'); ?></th>
      <th width="15%" class="title"><?php echo T_('From'); ?></th>
      <!-- <th width="20%" class="title">UserType</th> -->
      <th width="15%" class="title"><?php echo T_('Date'); ?></th>
      <th width="5%" class="title"><?php echo T_('Read'); ?></th>
    </tr>
<?php
$k = 0;
for ($i=0, $n=count( $rows ); $i < $n; $i++) {
	$row =& $rows[$i];
?>
    <tr class="<?php echo "row$k"; ?>">
      <td width="20"><?php echo $i+1+$pageNav->limitstart;?></td>
      <td width="5%"><?php echo mosHTML::idBox( $i, $row->message_id ); ?></td>
      <td width="60%"> <a href="#edit" onClick="hideMainMenu();return listItemTask('cb<?php echo $i;?>','view')">
        <?php echo $row->subject; ?> </a> </td>
      <td width="15%"><?php echo $row->user_from; ?></td>
      <td width="15%"><?php echo $row->date_time; ?></td>
      <td width="15%"><?php
      if (intval( $row->state ) == "1") {
      	echo T_("Read");
      } else {
      	echo T_("Unread");
	  } ?></td>
    </tr>
    <?php $k = 1 - $k;
			} ?>
	</table>
	<?php echo $pageNav->getListFooter(); ?>
  <input type="hidden" name="option" value="<?php echo $option;?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="boxchecked" value="0" />
  <input type="hidden" name="hidemainmenu" value="0" />
</form>
<?php }

function editConfig( &$vars, $option) {

	$tabs = new mosTabs(0);
?>
<table class="adminheading">
  <tr>
    <th class="msgconfig"><?php echo T_('Private Messaging Configuration'); ?></th>
  </tr>
</table>
<?php
$tabs->startPane("messages");
$tabs->startTab(T_("General"),"general-page");
?>
<form action="index2.php" method="post" name="adminForm">
<script language="javascript" type="text/javascript">
function submitbutton(pressbutton) {
	var form = document.adminForm;
	if (pressbutton == 'saveconfig') {
		if (confirm ("<?php echo T_('Are you sure?'); ?>")) {
			submitform( pressbutton );
		}
	} else {
		document.location.href = 'index2.php?option=<?php echo $option;?>';
	}
}
</script>


    <table class="adminform">
      <tr>
        <td width="20%"><?php echo T_('Lock Inbox:'); ?></td>
        <td> <?php echo $vars['lock']; ?> </td>
      </tr>
      <tr>
        <td width="20%"><?php echo T_('Mail me on new Message:'); ?></td>
        <td> <?php echo $vars['mail_on_new']; ?> </td>
      </tr>
    </table>


<?php
$tabs->endTab();
$tabs->endPane();
?>  <input type="hidden" name="option" value="<?php echo $option; ?>">
  <input type="hidden" name="task" value="">
</form>
<?php }

function viewMessage( &$row, $option ) {
?>
	<table class="adminheading">
		<tr>
			<th class="inbox"><?php echo T_('View Private Message'); ?></th>
		</tr>
	</table>

	<form action="index2.php" method="post" name="adminForm">
	<table class="adminform">
		<tr>
			<td width="100"><?php echo T_('From:'); ?></td>
			<td width="85%" bgcolor="#ffffff"><?php echo $row->user_from;?></td>
		</tr>
		<tr>
			<td><?php echo T_('Posted:'); ?></td>
			<td bgcolor="#ffffff"><?php echo $row->date_time;?></td>
		</tr>
		<tr>
			<td><?php echo T_('Subject:'); ?></td>
			<td bgcolor="#ffffff"><?php echo $row->subject;?></td>
		</tr>
		<tr>
			<td valign="top"><?php echo T_('Message:'); ?></td>
			<td width="100%" bgcolor="#ffffff"><pre><?php echo htmlspecialchars( $row->message );?></pre></td>
		</tr>
	</table>
	<input type="hidden" name="option" value="<?php echo $option;?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="1" />
	<input type="hidden" name="cid[]" value="<?php echo $row->message_id; ?>" />
	<input type="hidden" name="userid" value="<?php echo $row->user_id_from; ?>" />
	<input type="hidden" name="subject" value="Re: <?php echo $row->subject; ?>" />
	<input type="hidden" name="hidemainmenu" value="0" />
	</form>
<?php }

function newMessage($option, $recipientslist, $subject ) {
	global $my;
?>
	<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}

		// do field validation
		if (form.subject.value == "") {
			alert( "<?php echo T_('You must provide a subject.'); ?>" );
		} else if (form.message.value == "") {
			alert( "<?php echo T_('You must provide a message.'); ?>" );
		} else if (getSelectedValue('adminForm','user_id_to') < 1) {
			alert( "<?php echo T_('You must select a recipient.'); ?>" );
		} else {
			submitform( pressbutton );
		}
	}
	</script>

	<table class="adminheading">
		<tr>
			<th class="inbox"><?php echo T_('New Private Message'); ?></th>
		</tr>
	</table>

	<form action="index2.php" method="post" name="adminForm">
	<table class="adminform">
		<tr>
			<td width="100"><?php echo T_('To:'); ?></td>
			<td width="85%"><?php echo $recipientslist; ?></td>
		</tr>
		<tr>
			<td><?php echo T_('Subject:'); ?></td>
			<td>
				<input type="text" name="subject" size="50" maxlength="100" class="inputbox" value="<?php echo $subject; ?>"/>
			</td>
		</tr>
		<tr>
			<td valign="top"><?php echo T_('Message:'); ?></td>
			<td width="100%">
				<textarea name="message" style="width:100%" rows="30" class="inputbox"></textarea>
			</td>
		</tr>
	</table>
	<input type="hidden" name="user_id_from" value="<?php echo $my->id; ?>">
	<input type="hidden" name="option" value="<?php echo $option; ?>">
	<input type="hidden" name="task" value="">
	</form>
<?php }

}?>