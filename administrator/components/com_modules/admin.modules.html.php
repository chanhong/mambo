<?php
/**
* @package Mambo
* @subpackage Modules
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

class HTML_modules {

	/**
	* Writes a list of the defined modules
	* @param array An array of category objects
	*/
	function showModules( &$rows, $myid, $client, &$pageNav, $option, &$lists, $search ) {
		global $my;

		mosCommonHTML::loadOverlib();
		?>
		<form action="index2.php" method="post" name="adminForm">

		<table class="adminheading">
		<tr>
			<th class="modules" rowspan="2">
			<?php echo T_('Module Manager'); ?> <small><small>[ <?php echo $client == 'admin' ? T_('Administrator') : T_('Site');?> ]</small></small>
			</th>
			<td width="right">
			<?php echo $lists['position'];?>
			</td>
			<td width="right">
			<?php echo $lists['type'];?>
			</td>
		</tr>
		<tr>
			<td align="right">
			<?php echo T_('Filter:'); ?>
			</td>
			<td>
			<input type="text" name="search" value="<?php echo $search;?>" class="text_area" onChange="document.adminForm.submit();" />
			</td>
		</tr>
		</table>

		<table class="adminlist">
		<tr>
			<th width="20px">#</th>
			<th width="20px">
			<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows );?>);" />
			</th>
			<th class="title">
			<?php echo T_('Module Name'); ?>
			</th>
			<th nowrap="nowrap" width="10%">
			<?php echo T_('Published'); ?>
			</th>
			<th colspan="2" align="center" width="5%">
			<?php echo T_('Reorder'); ?>
			</th>
			<th width="2%">
			<?php echo T_('Order'); ?>
			</th>
			<th width="1%">
			<a href="javascript: saveorder( <?php echo count( $rows )-1; ?> )"><img src="images/filesave.png" border="0" width="16" height="16" alt="<?php echo T_('Save Order'); ?>" /></a>
			</th>
			<?php
			if ( !$client ) {
				?>
				<th nowrap="nowrap" width="7%">
				<?php echo T_('Access'); ?>
				</th>
				<?php
			}
			?>
			<th nowrap="nowrap" width="7%">
			<?php echo T_('Position'); ?>
			</th>
			<th nowrap="nowrap" width="5%">
			<?php echo T_('Pages'); ?>
			</th>
			<th nowrap="nowrap" width="5%">
			<?php echo T_('ID'); ?>
			</th>
			<th nowrap="nowrap" width="10%" align="left">
			<?php echo T_('Type'); ?>
			</th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row 	= &$rows[$i];

			$link = 'index2.php?option=com_modules&client='. $client .'&task=editA&hidemainmenu=1&id='. $row->id;

			$access 	= mosCommonHTML::AccessProcessing( $row, $i );
			$checked 	= mosCommonHTML::CheckedOutProcessing( $row, $i );
			$published 	= mosCommonHTML::PublishedProcessing( $row, $i );
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td align="right">
				<?php echo $pageNav->rowNumber( $i ); ?>
				</td>
				<td>
				<?php echo $checked; ?>
				</td>
				<td>
				<?php
				if ( $row->checked_out && ( $row->checked_out != $my->id ) ) {
					echo $row->title;
				} else {
					?>
					<a href="<?php echo $link; ?>">
					<?php echo $row->title; ?>
					</a>
					<?php
				}
				?>
				</td>
				<td align="center">
				<?php echo $published;?>
				</td>
				<td>
				<?php echo $pageNav->orderUpIcon( $i, ($row->position == @$rows[$i-1]->position) ); ?>
				</td>
				<td>
				<?php echo $pageNav->orderDownIcon( $i, $n, ($row->position == @$rows[$i+1]->position) ); ?>
				</td>
				<td align="center" colspan="2">
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
				</td>
				<?php
				if ( !$client ) {
					?>
					<td align="center">
					<?php echo $access;?>
					</td>
					<?php
				}
				?>
				<td align="center">
				<?php echo $row->position; ?>
				</td>
				<td align="center">
				<?php
				if (is_null( $row->pages )) {
					echo T_('None');
				} else if ($row->pages > 0) {
					echo T_('Varies');
				} else {
					echo T_('All');
				}
				?>
				</td>
				<td align="center">
				<?php echo $row->id;?>
				</td>
				<td align="left">
				<?php echo $row->module ? $row->module : T_("User");?>
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
		<input type="hidden" name="client" value="<?php echo $client;?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
		<?php
	}

	/**
	* Writes the edit form for new and existing module
	*
	* A new record is defined when <var>$row</var> is passed with the <var>id</var>
	* property set to 0.
	* @param mosCategory The category object
	* @param array <p>The modules of the left side.  The array elements are in the form
	* <var>$leftorder[<i>order</i>] = <i>label</i></var>
	* where <i>order</i> is the module order from the db table and <i>label</i> is a
	* text label associciated with the order.</p>
	* @param array See notes for leftorder
	* @param array An array of select lists
	* @param object Parameters
	*/
	function editModule( &$row, &$orders2, &$lists, &$params, $option ) {
		global $mosConfig_live_site;

		$row->titleA = '';
		if ( $row->id ) {
			$row->titleA = '<small><small>[ '. $row->title .' ]</small></small>';
		}

		mosCommonHTML::loadOverlib();
		?>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			if ( pressbutton == 'save' ) {
				if ( document.adminForm.title.value == "" ) {
					alert("<?php echo T_('Module must have a title'); ?>");
					return;
				} else if ( (document.adminForm.access.value == 2) && (document.adminForm.elements['groups[]'].selectedIndex < 0) ) {
						alert("<?php echo T_('Special Access groups required for access level Special'); ?>");
						return;
				}
			} else {
				<?php if ($row->module == "") {
					getEditorContents( 'editor1', 'content' );
				}?>
				submitform(pressbutton);
			}
			submitform(pressbutton);
		}
		<!--
		var originalOrder = '<?php echo $row->ordering;?>';
		var originalPos = '<?php echo $row->position;?>';
		var orders = new Array();	// array in the format [key,value,text]
		<?php	$i = 0;
		foreach ($orders2 as $k=>$items) {
			foreach ($items as $v) {
				echo "\n	orders[".$i++."] = new Array( \"$k\",\"$v->value\",\"$v->text\" );";
			}
		}
		?>
		//-->
		</script>
		<table class="adminheading">
		<tr>
			<th class="modules">
			<?php echo $lists['client_id'] ? T_('Administrator') : T_('Site');?>
			<?php echo T_('Module:'); ?>
			<small>
			<?php echo $row->id ? T_('Edit') : T_('New');?>
			</small>
			<?php echo $row->titleA; ?>
			</th>
		</tr>
		</table>

		<form action="index2.php" method="post" name="adminForm">

		<table cellspacing="0" cellpadding="0" width="100%">
		<tr valign="top">
			<td width="60%">
				<table class="adminform">
				<tr>
					<th colspan="2">
					<?php echo T_('Details'); ?>
					</th>
				</tr>
				<tr>
					<td width="100" align="left">
					<?php echo T_('Title:'); ?>
					</td>
					<td>
					<input class="text_area" type="text" name="title" size="35" value="<?php echo $row->title; ?>" />
					</td>
				</tr>
				<!-- START selectable pages -->
				<tr>
					<td width="100" align="left">
					<?php echo T_('Show title:'); ?>
					</td>
					<td>
					<?php echo $lists['showtitle']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="left">
					<?php echo T_('Position:'); ?>
					</td>
					<td>
					<?php echo $lists['position']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="left">
					<?php echo T_('Module Order:'); ?>
					</td>
					<td>
					<script language="javascript" type="text/javascript">
					<!--
					writeDynaList( 'class="inputbox" name="ordering" size="1"', orders, originalPos, originalPos, originalOrder );
					//-->
					</script>
					</td>
				</tr>
				<tr>
					<td valign="top" align="left">
					<?php echo T_('Access Level:'); ?>
					</td>
					<td>
					<?php echo $lists['access']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="left">
					<?php echo T_('Special Access:'); ?>
					</td>
					<td>
					<?php echo $lists['groups']."&nbsp;".mosToolTip(T_('Special Access Groups'), T_('If Access Level is Special, only the selected groups will have access to the module')); ?>
					</td>
				</tr>
				<tr>
					<td valign="top">
					<?php echo T_('Published:'); ?>
					</td>
					<td>
					<?php echo $lists['published']; ?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
					</td>
				</tr>
				<tr>
					<td valign="top">
					<?php echo T_('ID:'); ?>
					</td>
					<td>
					<?php echo $row->id; ?>
					</td>
				</tr>
				<tr>
					<td valign="top">
					<?php echo T_('Description:'); ?>
					</td>
					<td>
					<?php echo $row->description; ?>
					</td>
				</tr>
				</table>

				<table class="adminform">
				<tr>
					<th >
					<?php echo T_('Parameters'); ?>
					</th>
				</tr>
				<tr>
					<td>
					<?php echo $params->render();?>
					</td>
				</tr>
				</table>
			</td>
			<td width="40%" >
				<table width="100%" class="adminform">
				<tr>
					<th>
					<?php echo T_('Pages / Items'); ?>
					</th>
				</tr>
				<tr>
					<td>
					<?php echo T_('Menu Item Link(s):'); ?>
					<br />
					<?php echo $lists['selections']; ?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<?php
		if ($row->module == "") {
			?>
			<tr>
				<td colspan="2">
						<table width="100%" class="adminform">
						<tr>
							<th colspan="2">
							<?php echo T_('Custom Output'); ?>
							</th>
						</tr>
						<tr>
							<td valign="top" align="left">
							<?php echo T_('Content:'); ?>
							</td>
							<td>
							<?php
							// parameters : areaname, content, hidden field, width, height, rows, cols
							editorArea( 'editor1',  $row->content , 'content', '700', '350', '95', '30' ) ; ?>
							</td>
						</tr>
						</table>
				</td>
			</tr>
			<?php
		}
		?>
		</table>

		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="original" value="<?php echo $row->ordering; ?>" />
		<input type="hidden" name="module" value="<?php echo $row->module; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="client_id" value="<?php echo $lists['client_id']; ?>" />
		<?php
		if ( $row->client_id || $lists['client_id'] ) {
			echo '<input type="hidden" name="client" value="admin" />';
		}
		?>
		</form>
		<?php
	}

}
?>