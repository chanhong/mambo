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

if (!defined( '_MOS_POLL_MODULE' )) {
	/** ensure that functions are declared only once */
	define( '_MOS_POLL_MODULE', 1 );

	function show_poll_vote_form( $Itemid ) {
		global $database;

		$Itemid = mosGetParam( $_REQUEST, 'Itemid', 0 );

		$query1 = "SELECT p.id, p.title"
		."\nFROM #__polls AS p, #__poll_menu AS pm"
		."\nWHERE (pm.menuid='$Itemid' OR pm.menuid='0') AND p.id=pm.pollid"
		."\nAND p.published=1";

		$database->setQuery( $query1 );
		$polls = $database->loadObjectList();

		if($database->getErrorNum()) {
			echo "MB ".$database->stderr(true);
			return;
		}

		if ($polls) foreach ($polls as $poll) {
			if ($poll->id && $poll->title) {

				$query = "SELECT id, text FROM #__poll_data"
				. "\nWHERE pollid='$poll->id' AND text <> ''"
				. "\nORDER BY id";
				$database->setQuery($query);

				if(!($options = $database->loadObjectList())) {
					echo "MD ".$database->stderr(true);
					return;
				}
				poll_vote_form_html( $poll, $options, $Itemid );
			}
		}
	}

	function poll_vote_form_html( &$poll, &$options, $Itemid ) {
		$tabclass_arr=array("sectiontableentry2","sectiontableentry1");
		$tabcnt = 0;
		?>
		<form name="form2" method="post" action="<?php echo sefRelToAbs("index.php?option=com_poll&amp;Itemid=$Itemid"); ?>">
		<table width="95%" border="0" cellspacing="0" cellpadding="1" align="center">
			<tr>
			  <td colspan="2" class="poll"><strong><?php echo $poll->title; ?></strong></td>
			</tr>
			<tr>
			  <td align="center">
			  <table class='pollstableborder' cellspacing='0' cellpadding='0' border='0'>
		<?php
		for ($i=0, $n=count( $options ); $i < $n; $i++) { ?>
			<tr>
			  <td class='<?php echo $tabclass_arr[$tabcnt]; ?>' valign="top"><input type="radio" name="voteid" id="voteid<?php echo $options[$i]->id;?>" value="<?php echo $options[$i]->id;?>" alt="<?php echo $options[$i]->id;?>" /></td>
			  <td class='<?php echo $tabclass_arr[$tabcnt]; ?>' valign="top"><label for="voteid<?php echo $options[$i]->id;?>"><?php echo $options[$i]->text; ?></label></td>
			</tr>
			<?php
			if ($tabcnt == 1){
				$tabcnt = 0;
			} else {
				$tabcnt++;
			}
		}
		?>
			  </table>
			  </td>
			</tr>
			<tr>
			  <td colspan="2" align="center">
			  <input type="submit" name="task_button" class="button" value="<?php echo T_('Vote'); ?>" />&nbsp;&nbsp;
			  <input type="button" name="option" class="button" value="<?php echo T_('Results'); ?>" onclick="document.location.href='<?php echo sefRelToAbs("index.php?option=com_poll&amp;task=results&amp;id=$poll->id"); ?>';" />
			  </td>
			</tr>
		</table>
		<input type="hidden" name="id" value="<?php echo $poll->id;?>" />
		<input type="hidden" name="task" value="vote" />
		</form>
	<?php
	}
}
show_poll_vote_form( $Itemid );
?>
