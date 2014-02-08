<?php
/**
* @package Mambo
* @subpackage Menus
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

class HTML_menusections {

	function showMenusections( $rows, $pageNav, $search, $levellist, $menutype, $option ) {
		global $my;

		mosCommonHTML::loadOverlib();
		?>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th class="menus">
			<?php printf(T_('Menu Manager <small><small>[ %s ]</small></small>'), $menutype)  ?>
			</th>
			<td nowrap="true">
			<?php echo T_('Max Levels'); ?>
			</td>
			<td>
			<?php echo $levellist;?>
			</td>
			<td>
			<?php echo T_('Filter:'); ?>
			</td>
			<td>
			<input type="text" name="search" value="<?php echo $search;?>" class="inputbox" />
			</td>
		</tr>
		<?php
		if ( $menutype == 'mainmenu' ) {
			?>
			<tr>
				<td align="right" nowrap style="color: red; font-weight: normal;" colspan="5">
				* <?php echo T_('You cannot `delete` this menu as it is required for the proper operation of Mambo'); ?> *
				<br />
				<span style="color: black;">
				* <?php echo T_('The 1st Published item in this menu [mainmenu] is the default `Homepage` for the site'); ?> *
				</span>
				</td>
			</tr>
			<?php
		}
		?>
		</table>

		<table class="adminlist">
		<tr>
			<th width="20">
			#
			</th>
			<th width="20">
			<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" />
			</th>
			<th class="title" width="40%">
			<?php echo T_('Menu Item'); ?>
			</th>
			<th width="5%">
			<?php echo T_('Published'); ?>
			</th>
			<th colspan="2" width="5%">
			<?php echo T_('Reorder'); ?>
			</th>
			<th width="2%">
			<?php echo T_('Order'); ?>
			</th>
			<th width="1%">
			<a href="javascript: saveorder( <?php echo count( $rows )-1; ?> )"><img src="images/filesave.png" border="0" width="16" height="16" alt="<?php echo T_('Save Order'); ?>" /></a>
			</th>
			<th width="10%">
			<?php echo T_('Access'); ?>
			</th>
			<th>
			<?php echo T_('Itemid'); ?>
			</th>
			<th width="35%" align="left">
			<?php echo T_('Type'); ?>
			</th>
			<th>
			<?php echo T_('CID'); ?>
			</th>
		</tr>
	    <?php
		$k = 0;
		$i = 0;
		$n = count( $rows );
		foreach ($rows as $row) {
			$access 	= mosCommonHTML::AccessProcessing( $row, $i );
			$checked 	= mosCommonHTML::CheckedOutProcessing( $row, $i );
			$published 	= mosCommonHTML::PublishedProcessing( $row, $i );
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
				<?php echo $i + 1 + $pageNav->limitstart;?>
				</td>
				<td>
				<?php echo $checked; ?>
				</td>
				<td nowrap="nowrap">
				<?php
				if ( $row->checked_out && ( $row->checked_out != $my->id ) ) {
					echo $row->treename;
				} else {
					$link = 'index2.php?option=com_menus&menutype='. $row->menutype .'&task=edit&id='. $row->id . '&hidemainmenu=1';
					?>
					<a href="<?php echo $link; ?>">
					<?php echo $row->treename; ?>
					</a>
					<?php
				}
				?>
				</td>
				<td width="10%" align="center">
				<?php echo $published;?>
				</td>
				<td>
				<?php echo $pageNav->orderUpIcon( $i ); ?>
				</td>
				<td>
				<?php echo $pageNav->orderDownIcon( $i, $n ); ?>
				</td>
				<td align="center" colspan="2">
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
				</td>
				<td align="center">
				<?php echo $access;?>
				</td>
				<td align="center">
				<?php echo $row->id; ?>
				</td>
				<td align="left">
				<?php
				echo mosToolTip( $row->descrip, '', 280, 'tooltip.png', $row->type, $row->edit );
				?>
				</td>
				<td align="center">
				<?php echo $row->componentid; ?>
				</td>
		    </tr>
			<?php
			$k = 1 - $k;
			$i++;
		}
		?>
		</table>

		<?php echo $pageNav->getListFooter(); ?>

		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="menutype" value="<?php echo $menutype; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
		<?php
	}


	/**
	* Displays a selection list for menu item types
	*/
	function addMenuItem( &$cid, $menutype, $option, $types_content, $types_component, $types_link, $types_other ) {

		mosCommonHTML::loadOverlib();
		?>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th class="menus">
			<?php echo T_('New Menu Item'); ?>
			</th>
			<td valign="bottom" nowrap style="color: red;">
			<?php //echo _MENU_GROUP; ?>
			</td>
		</tr>
		</table>
<style type="text/css">
fieldset {
	border: 1px solid #777;
}
legend {
	font-weight: bold;
}
</style>
<table class="adminform">
	<tr>
		<td width="50%" valign="top">
			<fieldset>
				<legend><?php echo T_('Content'); ?></legend>
				<table class="adminform">
				<?php
				$k 		= 0;
				$count 	= count( $types_content );
					for ( $i=0; $i < $count; $i++ ) {
					$row = &$types_content[$i];

					$link = 'index2.php?option=com_menus&menutype='. $menutype .'&task=edit&hidemainmenu=1&type='. $row->type;
					?>
					<tr class="<?php echo "row$k"; ?>">
						<td width="20">
						<input type="radio" id="cb<?php echo $i;?>" name="type" value="<?php echo $row->type; ?>" onClick="isChecked(this.checked);" />
						</td>
						<td>
						<a href="<?php echo $link; ?>">
						<?php echo T_($row->name); ?>
						</a>
						</td>
						<td align="center" width="20">
						<?php
						echo mosToolTip( T_($row->descrip), T_($row->name), 250 );
						?>
						</td>
					</tr>
					<?php
					$k = 1 - $k;
				}
				?>
				</table>
			</fieldset>
			<fieldset>
				<legend><?php echo T_('Miscellaneous'); ?></legend>
				<table class="adminform">
				<?php
				$k 		= 0;
				$count 	= count( $types_other );
					for ( $i=0; $i < $count; $i++ ) {
					$row = &$types_other[$i];

					$link = 'index2.php?option=com_menus&menutype='. $menutype .'&task=edit&type='. $row->type;
					?>
					<tr class="<?php echo "row$k"; ?>">
						<td width="20">
						<input type="radio" id="cb<?php echo $i;?>" name="type" value="<?php echo $row->type; ?>" onClick="isChecked(this.checked);" />
						</td>
						<td>
						<a href="<?php echo $link; ?>">
						<?php echo T_($row->name); ?>
						</a>
						</td>
						<td align="center" width="20">
						<?php
						echo mosToolTip( T_($row->descrip), T_($row->name), 250 );
						?>
						</td>
					</tr>
					<?php
					$k = 1 - $k;
				}
				?>
				</table>
			</fieldset>
			* <?php echo T_('Note that some menu types appear in more that one grouping, but they are still the same menu type.'); ?>
		</td>
		<td width="50%" valign="top">
			<fieldset>
				<legend><?php echo T_('Components'); ?></legend>
				<table class="adminform">
				<?php
				$k 		= 0;
				$count 	= count( $types_component );
					for ( $i=0; $i < $count; $i++ ) {
					$row = &$types_component[$i];

					$link = 'index2.php?option=com_menus&menutype='. $menutype .'&task=edit&type='. $row->type;
					?>
					<tr class="<?php echo "row$k"; ?>">
						<td width="20">
						<input type="radio" id="cb<?php echo $i;?>" name="type" value="<?php echo $row->type; ?>" onClick="isChecked(this.checked);" />
						</td>
						<td>
						<a href="<?php echo $link; ?>">
						<?php echo T_($row->name); ?>
						</a>
						</td>
						<td align="center" width="20">
						<?php
						echo mosToolTip( T_($row->descrip), T_($row->name), 250 );
						?>
						</td>
					</tr>
					<?php
					$k = 1 - $k;
				}
				?>
				</table>
			</fieldset>
			<fieldset>
				<legend><?php echo T_('Links'); ?></legend>
				<table class="adminform">
				<?php
				$k 		= 0;
				$count 	= count( $types_link );
					for ( $i=0; $i < $count; $i++ ) {
					$row = &$types_link[$i];

					$link = 'index2.php?option=com_menus&menutype='. $menutype .'&task=edit&type='. $row->type;
					?>
					<tr class="<?php echo "row$k"; ?>">
						<td width="20">
						<input type="radio" id="cb<?php echo $i;?>" name="type" value="<?php echo $row->type; ?>" onClick="isChecked(this.checked);" />
						</td>
						<td>
						<a href="<?php echo $link; ?>">
						<?php echo T_($row->name); ?>
						</a>
						</td>
						<td align="center" width="20">
						<?php
						echo mosToolTip( T_($row->descrip), T_($row->name), 250 );
						?>
						</td>
					</tr>
					<?php
					$k = 1 - $k;
				}
				?>
				</table>
			</fieldset>
		</td>
	</tr>
</table>



		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="menutype" value="<?php echo $menutype; ?>" />
		<input type="hidden" name="task" value="edit" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
		<?php
	}


	/**
	* Form to select Menu to move menu item(s) to
	*/
	function moveMenu( $option, $cid, $MenuList, $items, $menutype  ) {
		?>
		<form action="index2.php" method="post" name="adminForm">
		<br />
		<table class="adminheading">
		<tr>
			<th>
			<?php echo T_('Move Menu Items'); ?>
			</th>
		</tr>
		</table>

		<br />
		<table class="adminform">
		<tr>
			<td width="3%"></td>
			<td align="left" valign="top" width="30%">
			<strong><?php echo T_('Move to Menu:'); ?></strong>
			<br />
			<?php echo $MenuList ?>
			<br /><br />
			</td>
			<td align="left" valign="top">
			<strong>
			<?php echo T_('Menu Items being moved:'); ?>
			</strong>
			<br />
			<ol>
			<?php
			foreach ( $items as $item ) {
				?>
				<li>
				<?php echo $item->name; ?>
				</li>
				<?php
			}
			?>
			</ol>
			</td>
		</tr>
		</table>
		<br /><br />

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="boxchecked" value="1" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="menutype" value="<?php echo $menutype; ?>" />
		<?php
		foreach ( $cid as $id ) {
			echo "\n <input type=\"hidden\" name=\"cid[]\" value=\"$id\" />";
		}
		?>
		</form>
		<?php
	}


	/**
	* Form to select Menu to copy menu item(s) to
	*/
	function copyMenu( $option, $cid, $MenuList, $items, $menutype  ) {
		?>
		<form action="index2.php" method="post" name="adminForm">
		<br />
		<table class="adminheading">
		<tr>
			<th>
			<?php echo T_('Copy Menu Items'); ?>
			</th>
		</tr>
		</table>

		<br />
		<table class="adminform">
		<tr>
			<td width="3%"></td>
			<td align="left" valign="top" width="30%">
			<strong>
			<?php echo T_('Copy to Menu:'); ?>
			</strong>
			<br />
			<?php echo $MenuList ?>
			<br /><br />
			</td>
			<td align="left" valign="top">
			<strong>
			<?php echo T_('Menu Items being copied:'); ?>
			</strong>
			<br />
			<ol>
			<?php
			foreach ( $items as $item ) {
				?>
				<li>
				<?php echo $item->name; ?>
				</li>
				<?php
			}
			?>
			</ol>
			</td>
		</tr>
		</table>
		<br /><br />

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="menutype" value="<?php echo $menutype; ?>" />
		<?php
		foreach ( $cid as $id ) {
			echo "\n <input type=\"hidden\" name=\"cid[]\" value=\"$id\" />";
		}
		?>
		</form>
		<?php
	}


}
?>