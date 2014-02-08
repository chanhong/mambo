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
class content_archive_category_menu_html {

	function editCategory( &$menu, &$lists, &$params, $option ) {
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
				if ( getSelectedValue( 'adminForm', 'componentid' ) < 1 ) {
					alert( '<?php echo T_('You must select a category'); ?>' );
					return;
				}
				sectcat = getSelectedText( 'adminForm', 'componentid' );
				sectcats = sectcat.split('/');
				section = getSelectedOption( 'adminForm', 'componentid' );

				form.link.value = "index.php?option=com_content&task=archivecategory&id=" + form.componentid.value;
				if ( form.name.value == '' ) {
					form.name.value = sectcats[1];
				}
				submitform( pressbutton );
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
			<?php echo $menu->id ? T_('Edit') : T_('Add');?> <?php echo T_('Menu Item :: Blog - Content Category Archive'); ?>
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
					<td width="10%" align="right" valign="top"><?php echo T_('Name:'); ?></td>
					<td width="200px">
					<input type="text" name="name" size="30" maxlength="100" class="inputbox" value="<?php echo $menu->name; ?>"/>
					</td>
					<td>
					<?php
					if ( !$menu->id ) {
						echo mosToolTip( T_('If you leave this blank the Category name will be automatically used') );
					}
					?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right"><?php echo T_('Category:'); ?></td>
					<td>
					<?php echo $lists['componentid']; ?>
					</td>
				</tr>
				<tr>
					<td align="right"><?php echo T_('Url:'); ?></td>
					<td>
					<?php echo $lists['link']; ?>
					</td>
				</tr>
				<tr>
					<td align="right"><?php echo T_('Parent Item:'); ?></td>
					<td>
					<?php echo $lists['parent']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right"><?php echo T_('Ordering:'); ?></td>
					<td>
					<?php echo $lists['ordering']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right"><?php echo T_('Access Level:'); ?></td>
					<td>
					<?php echo $lists['access']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right"><?php echo T_('Published:'); ?></td>
					<td>
					<?php echo $lists['published']; ?>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
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
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
		<script language="Javascript" src="<?php echo $mosConfig_live_site;?>/includes/js/overlib_mini.js"></script>
		<?php
	}
}
?>