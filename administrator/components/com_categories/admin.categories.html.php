<?php
/**
* @package Mambo
* @subpackage Categories
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

class categories_html {

	/**
	* Writes a list of the categories for a section
	* @param array An array of category objects
	* @param string The name of the category section
	*/
	function show( &$rows, $section, $section_name, &$pageNav, &$lists, $type ) {
		global $my, $mosConfig_live_site;

		mosCommonHTML::loadOverlib();
		?>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<?php
			if ( $section == 'content') {
				?>
				<th class="categories">
				<?php echo T_('Category Manager'); ?> <small><small>[ <?php echo T_('Content: All'); ?> ]</small></small>
				</th>
				<td width="right">
				<?php echo $lists['sectionid'];?>
				</td>
				<?php
			} else {
				if ( is_numeric( $section ) ) {
					$query = 'com_content&sectionid=' . $section;
				} else {
					if ( $section == 'com_contact_details' ) {
						$query = 'com_contact';
					} else {
						$query = $section;
					}
				}
				?>
				<th class="categories">
				<?php echo T_('Category Manager'); ?> <small><small>[ <?php echo $section_name;?> ]</small></small>
				</th>
				<?php
			}
			?>
		</tr>
		</table>

		<table class="adminlist">
		<tr>
			<th width="10" align="left">
			#
			</th>
			<th width="20">
			<input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $rows );?>);" />
			</th>
			<th class="title">
			<?php echo T_('Category Name'); ?>
			</th>
			<th width="10%">
			<?php echo T_('Published'); ?>
			</th>
			<?php
			if ( $section <> 'content') {
				?>
				<th colspan="2" width="5%">
				<?php echo T_('Reorder'); ?>
				</th>
				<?php
			}
			?>
			<th width="2%">
			<?php echo T_('Order'); ?>
			</th>
			<th width="1%">
			<a href="javascript: saveorder( <?php echo count( $rows )-1; ?> )"><img src="images/filesave.png" border="0" width="16" height="16" alt="<?php echo T_('Save Order'); ?>" /></a>
			</th>
			<th width="10%">
			<?php echo T_('Access'); ?>
			</th>
			<?php
			if ( $section == 'content') {
				?>
				<th width="12%" align="left">
				<?php echo T_('Section'); ?>
				</th>
				<?php
			}
			?>
			<th width="5%" nowrap>
			<?php echo T_('Category ID'); ?>
			</th>
			<?php
			if ( $type == 'content') {
				?>
				<th width="5%">
				# <?php echo T_('Active'); ?>
				</th>
				<th width="5%">
				# <?php echo T_('Trash'); ?>
				</th>
				<?php
			} else {
				?>
				<th width="20%">
				</th>
				<?php
			}
			?>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row 	= &$rows[$i];

			$row->sect_link = 'index2.php?option=com_sections&task=editA&hidemainmenu=1&id='. $row->section;

			$link = 'index2.php?option=com_categories&section='. $section .'&task=editA&hidemainmenu=1&id='. $row->id;

			$access 	= mosCommonHTML::AccessProcessing( $row, $i );
			$checked 	= mosCommonHTML::CheckedOutProcessing( $row, $i );
			$published 	= mosCommonHTML::PublishedProcessing( $row, $i );
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
				<?php echo $pageNav->rowNumber( $i ); ?>
				</td>
				<td>
				<?php echo $checked; ?>
				</td>
				<td>
				<?php
				if ( $row->checked_out_contact_category && ( $row->checked_out_contact_category != $my->id ) ) {
					echo $row->name .' ( '. $row->title .' )';
				} else {
					?>
					<a href="<?php echo $link; ?>">
					<?php echo $row->name .' ( '. $row->title .' )'; ?>
					</a>
					<?php
				}
				?>
				</td>
				<td align="center">
				<?php echo $published;?>
				</td>
				<?php
				if ( $section <> 'content' ) {
					?>
					<td>
					<?php echo $pageNav->orderUpIcon( $i ); ?>
					</td>
					<td>
					<?php echo $pageNav->orderDownIcon( $i, $n ); ?>
					</td>
					<?php
				}
				?>
				<td align="center" colspan="2">
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
				</td>
				<td align="center">
				<?php echo $access;?>
				</td>
				<?php
				if ( $section == 'content' ) {
					?>
					<td align="left">
					<a href="<?php echo $row->sect_link; ?>" title="<?php echo T_('Edit Section'); ?>">
					<?php echo $row->section_name; ?>
					</a>
					</td>
					<?php
				}
				?>
				<td align="center">
				<?php echo $row->id; ?>
				</td>
				<?php
				if ( $type == 'content') {
					?>
					<td align="center">
					<?php echo $row->active; ?>
					</td>
					<td align="center">
					<?php echo $row->trash; ?>
					</td>
					<?php
				} else {
					?>
					<td>
					</td>
					<?php
				}
				$k = 1 - $k;
				?>
			</tr>
			<?php
		}
		?>
		</table>

		<?php echo $pageNav->getListFooter(); ?>

		<input type="hidden" name="option" value="com_categories" />
		<input type="hidden" name="section" value="<?php echo $section;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="chosen" value="" />
		<input type="hidden" name="act" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="type" value="<?php echo $type; ?>" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
		<?php
	}

	/**
	* Writes the edit form for new and existing categories
	* @param mosCategory The category object
	* @param string
	* @param array
	*/
	function edit( &$row, &$lists, $redirect, $menus ) {
		if ($row->image == "") {
			$row->image = 'blank.png';
		}

		if ( $redirect == 'content' ) {
			$component = T_('Content');
		} else {
			$component = ucfirst( substr( $redirect, 4 ) );
			if ( $redirect == 'com_contact_details' ) {
				$component = T_('Contact');
			}
		}
		mosMakeHtmlSafe( $row, ENT_QUOTES, 'description' );
		?>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton, section) {
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}

			if ( pressbutton == 'menulink' ) {
				if ( form.menuselect.value == "" ) {
					alert( "<?php echo T_('Please select a Menu'); ?>" );
					return;
				} else if ( form.link_type.value == "" ) {
					alert( "<?php echo T_('Please select a menu type'); ?>" );
					return;
				} else if ( form.link_name.value == "" ) {
					alert( "<?php echo T_('Please enter a Name for this menu item'); ?>" );
					return;
				}
			}

			if ( form.name.value == "" ) {
				alert("<?php echo T_('Category must have a name'); ?>");
			} else {
				<?php getEditorContents( 'editor1', 'description' ) ; ?>
				submitform(pressbutton);
			}
		}
		// show / hide publishing information
			function displayParameterInfo()
			{

				if(document.getElementById('simpleediting').style.display == 'block')
				{
					document.getElementById('simpleediting').style.display = 'none';
					document.getElementById('show').style.display = 'block';
					document.getElementById('hide').style.display = 'none';
					document.adminForm.simple_editing.value ='on';
				}
				else
				{
					document.getElementById('simpleediting').style.display = 'block';
					document.getElementById('show').style.display = 'none';
					document.getElementById('hide').style.display = 'block';
					document.adminForm.simple_editing.value ='off';
				}

			}
		</script>
		<?php
		if($_SESSION['simple_editing'] == 'on')
		{
			$simpleediting ='none';
			$simple = 'block';
			$advanced = 'none';
		}
		else
		{

			$advanced = 'block';
			$simple = 'none';
			$simpleediting ='block';
		}

		?>
		<form action="index2.php" method="post" name="adminForm">
		<input type ="hidden" name="simple_editing" value='' />
		<table class="adminheading">
		<tr>
			<th class="categories">
			<?php echo T_('Category:'); ?>
			<small>
			<?php echo $row->id ? T_('Edit') : T_('New');?>
			</small>
			<small><small>
			[ <?php echo $component; ?>: <?php echo $row->name; ?> ]
			</small></small>
			</th>
		</tr>
		</table>
	<table width="100%">
			<tr>
				<td valign="top" align="right">
				<div id = "show" style="display:<?php echo $simple;?>">
				<a href="javascript:displayParameterInfo();"><?php echo T_('Show Advanced Details'); ?></a>
				</div>
				<div id = "hide" style="display:<?php echo $advanced;?>">
				<a href="javascript:displayParameterInfo();"><?php echo T_('Hide Advanced Details'); ?></a>
				</div>
				</td>
			</tr>
		</table>
		<table width="100%">
		<tr>
			<td valign="top" >
				<table class="adminform">
				<tr>
					<th colspan="3">
					<?php echo T_('Category Details'); ?>
					</th>
				</tr>
				<tr>
					<td>
					<?php echo T_('Category Title:'); ?>
					</td>
					<td colspan="2">
					<input class="text_area" type="text" name="title" value="<?php echo $row->title; ?>" size="50" maxlength="50" title="<?php echo T_('A short name to appear in menus'); ?>" />
					</td>
				</tr>
				<tr>
					<td>
					<?php echo T_('Category Name:'); ?>
					</td>
					<td colspan="2">
					<input class="text_area" type="text" name="name" value="<?php echo $row->name; ?>" size="50" maxlength="255" title="<?php echo T_('A long name to be displayed in headings'); ?>" />
					</td>
				</tr>
				<tr>
					<td>
					<?php echo T_('Section:'); ?>
					</td>
					<td colspan="2">
					<?php echo $lists['section']; ?>
					</td>
				</tr>
				<tr>
					<td>
					<?php echo T_('Image:'); ?>
					</td>
					<td>
					<?php echo $lists['image']; ?>
					</td>
					<td rowspan="4" width="50%">
					<script language="javascript" type="text/javascript">
					if (document.forms[0].image.options.value!=''){
					  jsimg='../images/stories/' + getSelectedValue( 'adminForm', 'image' );
					} else {
					  jsimg='../images/M_images/blank.png';
					}
					document.write('<img src=' + jsimg + ' name="imagelib" width="80" height="80" border="2" alt="<?php echo T_('Preview'); ?>" />');
					</script>
					</td>
				</tr>
				<tr>
					<td>
					<?php echo T_('Image Position:'); ?>
					</td>
					<td>
					<?php echo $lists['image_position']; ?>
					</td>
				</tr>
				<tr>
					<td>
					<?php echo T_('Ordering:'); ?>
					</td>
					<td>
					<?php echo $lists['ordering']; ?>
					</td>
				</tr>
				<tr>
					<td>
					<?php echo T_('Access Level:'); ?>
					</td>
					<td>
					<?php echo $lists['access']; ?>
					</td>
				</tr>
				<tr>
					<td>
					<?php echo T_('Published:'); ?>
					</td>
					<td>
					<?php echo $lists['published']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top">
					<?php echo T_('Description:'); ?>
					</td>
					<td colspan="2">
					<?php
					// parameters : areaname, content, hidden field, width, height, rows, cols
					editorArea( 'editor1',  $row->description , 'description', '100%;', '300', '60', '20' ) ; ?>
					</td>
				</tr>
				</table>
			</td>
			<td valign="top" align="right">
			<div id="simpleediting" style="display:<?php echo $simpleediting;?>">
			<table cellspacing="0" cellpadding="0" border="0" width="100%" >
				<tr>
					<td width="40%">
			<?php
			if ( $row->id > 0 ) {
    		?>
				<table class="adminform">
				<tr>
					<th colspan="2">
					<?php echo T_('Link to Menu'); ?>
					</th>
				</tr>
				<tr>
					<td colspan="2">
					<?php echo T_('This will create a new menu item in the menu you select'); ?>
					<br /><br />
					</td>
				</tr>
				<tr>
					<td valign="top" width="100px">
					<?php echo T_('Select a Menu'); ?>
					</td>
					<td>
					<?php echo $lists['menuselect']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" width="100px">
					<?php echo T_('Select Menu Type'); ?>
					</td>
					<td>
					<?php echo $lists['link_type']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" width="100px">
					<?php echo T_('Menu Item Name'); ?>
					</td>
					<td>
					<input type="text" name="link_name" class="inputbox" value="" size="25" />
					</td>
				</tr>
				<tr>
					<td>
					</td>
					<td>
					<input name="menu_link" type="button" class="button" value="<?php echo T_('Link to Menu'); ?>" onClick="submitbutton('menulink');" />
					</td>
				</tr>
				<tr>
					<th colspan="2">
					<?php echo T_('Existing Menu Links'); ?>
					</th>
				</tr>
				<?php
				if ( $menus == NULL ) {
					?>
					<tr>
						<td colspan="2">
						<?php echo T_('None'); ?>
						</td>
					</tr>
					<?php
				} else {
					mosCommonHTML::menuLinksSecCat( $menus );
				}
				?>
				<tr>
					<td colspan="2">
					</td>
				</tr>
				</table>
			<?php
			} else {
			?>
			<table class="adminform" width="40%">
				<tr><th>&nbsp;</th></tr>
				<tr><td><?php echo T_('Menu links available when saved'); ?></td></tr>
			</table>
			<?php
			}
			?>
			</td>
		</tr>
		</table>
		</div>
		</td>
		</tr>
		</table>
		<input type="hidden" name="option" value="com_categories" />
		<input type="hidden" name="oldtitle" value="<?php echo $row->title ; ?>" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="sectionid" value="<?php echo $row->section; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
		</td>
		</tr>
		</table>
		<?php
	}


	/**
	* Form to select Section to move Category to
	*/
	function moveCategorySelect( $option, $cid, $SectionList, $items, $sectionOld, $contents, $redirect ) {
		?>
		<form action="index2.php" method="post" name="adminForm">
		<br />
		<table class="adminheading">
		<tr>
			<th class="categories">
			<?php echo T_('Move Category'); ?>
			</th>
		</tr>
		</table>

		<br />
		<table class="adminform">
		<tr>
			<td width="3%"></td>
			<td align="left" valign="top" width="30%">
			<strong><?php echo T_('Move to Section:'); ?></strong>
			<br />
			<?php echo $SectionList ?>
			<br /><br />
			</td>
			<td align="left" valign="top" width="20%">
			<strong><?php echo T_('Categories being moved:'); ?></strong>
			<br />
			<?php
			echo "<ol>";
			foreach ( $items as $item ) {
				echo "<li>". $item->name ."</li>";
			}
			echo "</ol>";
			?>
			</td>
			<td valign="top" width="20%">
			<strong><?php echo T_('Content Items being moved:'); ?></strong>
			<br />
			<?php
			if ($contents) {
				echo "<ol>";
				foreach ( $contents as $content ) {
					echo "<li>". $content->title ."</li>";
				}
				echo "</ol>";
			} else {
				echo '<br />None';
			}			
			?>
			</td>
			<td valign="top">
			<?php echo T_('This will move the Categories listed <br />and all the items within the category (also listed) <br />to the selected Section.'); ?>
			</td>.
		</tr>
		</table>
		<br /><br />

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="section" value="<?php echo $sectionOld;?>" />
		<input type="hidden" name="boxchecked" value="1" />
		<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
		<input type="hidden" name="task" value="" />
		<?php
		foreach ( $cid as $id ) {
			echo "\n <input type=\"hidden\" name=\"cid[]\" value=\"$id\" />";
		}
		?>
		</form>
		<?php
	}


	/**
	* Form to select Section to copy Category to
	*/
	function copyCategorySelect( $option, $cid, $SectionList, $items, $sectionOld, $contents, $redirect ) {
		?>
		<form action="index2.php" method="post" name="adminForm">
		<br />
		<table class="adminheading">
		<tr>
			<th class="categories">
			<?php echo T_('Copy Category'); ?>
			</th>
		</tr>
		</table>

		<br />
		<table class="adminform">
		<tr>
			<td width="3%"></td>
			<td align="left" valign="top" width="30%">
			<strong><?php echo T_('Copy to Section:'); ?></strong>
			<br />
			<?php echo $SectionList ?>
			<br /><br />
			</td>
			<td align="left" valign="top" width="20%">
			<strong><?php echo T_('Categories being copied:'); ?></strong>
			<br />
			<?php
			echo "<ol>";
			foreach ( $items as $item ) {
				echo "<li>". $item->name ."</li>";
			}
			echo "</ol>";
			?>
			</td>
			<td valign="top" width="20%">
			<strong><?php echo T_('Content Items being copied:'); ?></strong>
			<br />
			<?php
			echo "<ol>";
			foreach ( $contents as $content ) {
				echo "<li>". $content->title ."</li>";
				echo "\n <input type=\"hidden\" name=\"item[]\" value=\"$content->id\" />";
			}
			echo "</ol>";
			?>
			</td>
			<td valign="top"><?php echo T_('
			This will copy the Categories listed
			<br />
			and all the items within the category (also listed)
			<br />
			to the selected Section.'); ?>
			</td>.
		</tr>
		</table>
		<br /><br />

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="section" value="<?php echo $sectionOld;?>" />
		<input type="hidden" name="boxchecked" value="1" />
		<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
		<input type="hidden" name="task" value="" />
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