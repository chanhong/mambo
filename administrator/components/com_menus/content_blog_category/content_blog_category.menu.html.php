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

/**
* Writes the edit form for new and existing content item
*
* A new record is defined when <var>$row</var> is passed with the <var>id</var>
* property set to 0.
*/
class content_blog_category_html {

	function edit( &$menu, &$lists, &$params, $option ) {
		/* in the HTML below, references to "section" were changed to "category" */
		global $mosConfig_live_site;
		?>
		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}
			var form = document.adminForm;
			<?php
			if ( !$menu->id ) {
				?>
				if ( form.name.value == '' ) {
					alert( '<?php echo T_('This Menu item must have a title'); ?>' );
					return;
				} else {
					submitform( pressbutton );
				}
				<?php
			} else {
				?>
				if ( form.name.value == '' ) {
					alert( '<?php echo T_('This Menu item must have a title'); ?>' );
				} else {
					submitform( pressbutton );
				}
				<?php
			}
			?>
		}
		</script>

		<form action="index2.php" method="post" name="adminForm">

		<table class="adminheading">
		<tr>
			<th>
			<?php echo $menu->id ? T_('Edit') : T_('Add');?> <?php echo T_('Menu Item :: Blog - Content Category'); ?>
			</th>
		</tr>
		</table>

		<table width="100%">
		<tr valign="top">
			<td width="60%">
				<table class="adminform">
				<tr>
					<th colspan="3">
					<?php echo T_('Details'); ?>
					</th>
				</tr>
				<tr>
					<td width="10%" align="right"><?php echo T_('Name:'); ?></td>
					<td width="200px">
					<input class="inputbox" type="text" name="name" size="30" maxlength="100" value="<?php echo $menu->name; ?>" />
					</td>
					<td>
					</td>
				</tr>
				<tr>
			  		<td valign="top" align="right">
			  		<?php echo T_('Category:'); ?>
			  		</td>
			 		<td>
			  		<?php echo $lists['categoryid']; ?>
			 		</td>
			 		<td valign="top">
			 		<?php
			 		echo mosToolTip( T_('You can select multiple Categories') )
			 		?>
			  		</td>
				</tr>
				<tr>
					<td align="right"><?php echo T_('Url:'); ?></td>
					<td colspan="2">
					<?php echo $lists['link']; ?>
					</td>
				</tr>
				<tr>
					<td align="right"><?php echo T_('Parent Item:'); ?></td>
					<td colspan="2">
					<?php echo $lists['parent'];?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right"><?php echo T_('Ordering:'); ?></td>
					<td colspan="2">
					<?php echo $lists['ordering']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right"><?php echo T_('Access Level:'); ?></td>
					<td colspan="2">
					<?php echo $lists['access']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right"><?php echo T_('Published:'); ?></td>
					<td colspan="2">
					<?php echo $lists['published']; ?>
					</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				</table>
			</td>
			<td width="40%">
				<table class="adminform">
				<tr>
					<th>
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
		</tr>
		</table>

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="id" value="<?php echo $menu->id; ?>" />
		<input type="hidden" name="menutype" value="<?php echo $menu->menutype; ?>" />
		<input type="hidden" name="type" value="<?php echo $menu->type; ?>" />
		<input type="hidden" name="link" value="index.php?option=com_content&task=blogcategory&id=0" />
		<input type="hidden" name="componentid" value="0" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
		<script language="Javascript" src="<?php echo $mosConfig_live_site;?>/includes/js/overlib_mini.js"></script>
		<?php
	}
}
?>