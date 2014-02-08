<?php
/**
* @package Mambo
* @subpackage Polls
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

class HTML_poll {

	function showPolls( &$rows, &$pageNav, $option ) {
		global $my;

		mosCommonHTML::loadOverlib();
		?>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th><?php echo T_('Poll Manager'); ?></th>
		</tr>
		</table>

		<table class="adminlist">
		<tr>
			<th width="5">
			#
			</th>
			<th width="20">
			<input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $rows ); ?>);" />
			</th>
			<th align="left">
			<?php echo T_('Poll Question'); ?>
			</th>
			<th width="10%" align="center">
			<?php echo T_('Published'); ?>
			</th>
			<th width="10%" align="center">
			<?php echo T_('Options'); ?>
			</th>
			<th width="10%" align="center">
			<?php echo T_('Lag'); ?>
			</th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i];

			$link 	= 'index2.php?option=com_poll&task=editA&hidemainmenu=1&id='. $row->id;

			$task 	= $row->published ? 'unpublish' : 'publish';
			$img 	= $row->published ? 'publish_g.png' : 'publish_x.png';
			$alt 	= $row->published ? T_('Published') : T_('Unpublished');

			$checked 	= mosCommonHTML::CheckedOutProcessing( $row, $i );
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
				<?php echo $pageNav->rowNumber( $i ); ?>
				</td>
				<td>
				<?php echo $checked; ?>
				</td>
				<td>
				<a href="<?php echo $link; ?>" title="<?php echo T_('Edit Poll'); ?>">
				<?php echo $row->title; ?>
				</a>
				</td>
				<td align="center">
				<a href="javascript: void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $task;?>')">
				<img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="<?php echo $alt; ?>" />
				</a>
				</td>
				<td align="center">
				<?php echo $row->numoptions; ?>
				</td>
				<td align="center">
				<?php echo $row->lag; ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		</form>
		<?php
	}


	function editPoll( &$row, &$options, &$lists ) {
		mosMakeHtmlSafe( $row, ENT_QUOTES );
		?>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}
			// do field validation
			if (form.title.value == "") {
				alert( "<?php echo T_('Poll must have a title'); ?>" );
			} else if( isNaN( parseInt( form.lag.value ) ) ) {
				alert( "<?php echo T_('Poll must have a non-zero lag time'); ?>" );
			//} else if (form.menu.options.value == ""){
			//	alert( "Poll must have pages." );
			//} else if (form.adminForm.textfieldcheck.value == 0){
			//	alert( "Poll must have options." );
			} else {
				submitform( pressbutton );
			}
		}
		</script>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th>
			<?php echo T_('Poll:'); ?>
			<small>
			<?php echo $row->id ? T_('Edit') : T_('New');?>
			</small>
			</th>
		</tr>
		</table>

		<table class="adminform">
		<tr>
			<th colspan="4">
			<?php echo T_('Details'); ?>
			</th>
		</tr>
		<tr>
			<td width="10%">
			<?php echo T_('Question:'); ?>
			</td>
			<td>
			<input class="inputbox" type="text" name="title" size="60" value="<?php echo $row->title; ?>" />
			</td>
   			<td width="20px">&nbsp;

			</td>
			<td width="100%" rowspan="20" valign="top">
			<?php echo T_('Show on menu items:'); ?>
			<br />
			<?php echo $lists['select']; ?>
			</td>
		</tr>
		<tr>
			<td>
			<?php echo T_('Lag:'); ?>
			</td>
			<td>
			<input class="inputbox" type="text" name="lag" size="10" value="<?php echo $row->lag; ?>" /> (seconds between votes)
			</td>
		</tr>
		<tr>
			<td colspan="3">
			<br /><br />
			<?php echo T_('Options:'); ?>
			</td>
		</tr>
		<?php
		for ($i=0, $n=count( $options ); $i < $n; $i++ ) {
			?>
			<tr>
				<td>
				<?php echo ($i+1); ?>
				</td>
				<td>
				<input class="inputbox" type="text" name="polloption[<?php echo $options[$i]->id; ?>]" value="<?php echo htmlspecialchars( $options[$i]->text, ENT_QUOTES ); ?>" size="60" />
				</td>
			</tr>
			<?php
		}
		for (; $i < 12; $i++) {
			?>
			<tr>
				<td>
				<?php echo ($i+1); ?>
				</td>
				<td>
				<input class="inputbox" type="text" name="polloption[]" value="" size="60"/>
				</td>
			</tr>
			<?php
		}
		?>
		</table>

		<input type="hidden" name="task" value="">
		<input type="hidden" name="option" value="com_poll" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="textfieldcheck" value="<?php echo $n; ?>" />
		</form>
		<?php
	}

}
?>