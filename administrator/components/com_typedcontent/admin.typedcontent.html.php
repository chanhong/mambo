<?php
/**
* @package Mambo
* @subpackage Content
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

class HTML_typedcontent {

	/**
	* Writes a list of the content items
	* @param array An array of content objects
	*/
	function showContent( &$rows, &$pageNav, $option, $search, &$lists ) {
		global $my, $acl;

		mosCommonHTML::loadOverlib();
		?>
		<form action="index2.php" method="post" name="adminForm">

		<table class="adminheading">
		<tr>
			<th class="edit">
			<?php echo T_('Static Content Manager'); ?>
			</th>
			<td>
			<?php echo T_('Filter:'); ?>&nbsp;
			</td>
			<td>
			<input type="text" name="search" value="<?php echo $search;?>" class="text_area" onChange="document.adminForm.submit();" />
			</td>
			<td>
			&nbsp;&nbsp;&nbsp;<?php echo T_('Order:'); ?>&nbsp;
			</td>
			<td>
			<?php echo $lists['order']; ?>
			</td>
			<td width="right">
			<?php echo $lists['authorid'];?>
			</td>
		</tr>
		</table>

		<table class="adminlist">
		<tr>
			<th width="5">
			#
			</th>
			<th width="5px">
			<input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $rows ); ?>);" />
			</th>
			<th class="title">
			<?php echo T_('Title'); ?>
			</th>
			<th width="5%">
			<?php echo T_('Published'); ?>
			</th>
			<th nowrap="nowrap" width="5%">
			<?php echo T_('Front Page'); ?>
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
			<th width="5%">
			<?php echo T_('ID'); ?>
			</th>
			<th width="1%" align="left">
			<?php echo T_('Links'); ?>
			</th>
			<th width="20%" align="left">
			<?php echo T_('Author'); ?>
			</th>
			<th align="center" width="10">
			<?php echo T_('Date'); ?>
			</th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i];

			$now = date( "Y-m-d H:i:s" );
			if ( $now <= $row->publish_up && $row->state == "1" ) {
				$img = 'publish_y.png';
				$alt = T_('Published');
			} else if ( ( $now <= $row->publish_down || $row->publish_down == "0000-00-00 00:00:00" ) && $row->state == "1" ) {
				$img = 'publish_g.png';
				$alt = T_('Published');
			} else if ( $now > $row->publish_down && $row->state == "1" ) {
				$img = 'publish_r.png';
				$alt = T_('Expired');
			} elseif ( $row->state == "0" ) {
				$img = "publish_x.png";
				$alt = T_('Unpublished');
			}
			$times = '';
			if (isset($row->publish_up)) {
				if ($row->publish_up == '0000-00-00 00:00:00') {
					$times .= "<tr><td>".T_('Start:')." ".T_('Always')."</td></tr>";
				} else {
					$times .= "<tr><td>".T_('Start:')." $row->publish_up</td></tr>";
				}
			}
			if (isset($row->publish_down)) {
				if ($row->publish_down == '0000-00-00 00:00:00') {
					$times .= "<tr><td>".T_('Finish:')." ".T_('No Expiry')."</td></tr>";
				} else {
					$times .= "<tr><td>".T_('Finish:')." $row->publish_down</td></tr>";
				}
			}

			if ( !$row->access ) {
				$color_access = 'style="color: green;"';
				$task_access = 'accessregistered';
			} else if ( $row->access == 1 ) {
				$color_access = 'style="color: red;"';
				$task_access = 'accessspecial';
			} else {
				$color_access = 'style="color: black;"';
				$task_access = 'accesspublic';
			}

			$link = 'index2.php?option=com_typedcontent&task=edit&hidemainmenu=1&id='. $row->id;

			if ( $row->checked_out ) {
				$checked	 		= mosCommonHTML::checkedOut( $row );
			} else {
				$checked	 		= mosHTML::idBox( $i, $row->id, ($row->checked_out && $row->checked_out != $my->id ) );
			}

			if ( $acl->acl_check( 'administration', 'manage', 'users', $my->usertype, 'components', 'com_users' ) ) {
				if ( $row->created_by_alias ) {
					$author = $row->created_by_alias;
				} else {
					$linkA 	= 'index2.php?option=com_users&task=editA&hidemainmenu=1&id='. $row->created_by;
					$author = '<a href="'. $linkA .'" title="'.T_('Edit User').'">'. $row->creator .'</a>';
				}
			} else {
				if ( $row->created_by_alias ) {
					$author = $row->created_by_alias;
				} else {
					$author = $row->creator;
				}
			}

			$date = mosFormatDate( $row->created, '%x' );
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
				if ( $row->checked_out && ( $row->checked_out != $my->id ) ) {
					echo $row->title;
					if ( $row->title_alias ) {
						echo ' (<i>'. $row->title_alias .'</i>)';
					}
				} else {
					?>
					<a href="<?php echo $link; ?>" title="<?php echo T_('Edit Static Content'); ?>">
					<?php
					echo $row->title;
					if ( $row->title_alias ) {
						echo ' (<i>'. $row->title_alias .'</i>)';
					}
					?>
					</a>
					<?php
				}
				?>
				</td>
				<?php
				if ( $times ) {
					?>
					<td align="center">
					<a href="javascript: void(0);" onMouseOver="return overlib('<table><?php echo $times; ?></table>', CAPTION, '<?php echo T_('Publish Information'); ?>', BELOW, RIGHT);" onMouseOut="return nd();" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $row->state ? "unpublish" : "publish";?>')">
					<img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="<?php echo $alt; ?>" />
					</a>
					</td>
					<?php
				}
				?>
				<td align="center">
				<a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $i;?>','toggle_frontpage')">
				<img src="images/<?php echo ( $row->frontpage ) ? 'tick.png' : 'publish_x.png';?>" width="12" height="12" border="0" alt="<?php echo ( $row->frontpage ) ? T_('Yes') : T_('No');?>" />
				</a>
				</td>
				<td align="center" colspan="2">
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" class="text_area" style="text-align: center" />
				</td>
				<td align="center">
				<a href="javascript: void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $task_access;?>')" <?php echo $color_access; ?>>
				<?php echo $row->groupname;?>
				</a>
				</td>
				<td align="center">
				<?php echo $row->id;?>
				</td>
				<td align="center">
				<?php echo $row->links;?>
				</td>
				<td align="left">
				<?php echo $author;?>
				</td>
				<td>
				<?php echo $date; ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>

		<?php echo $pageNav->getListFooter(); ?>
		<?php mosCommonHTML::ContentLegend(); ?>

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
		<?php
	}

	function edit( &$row, &$images, &$lists, &$params, $option, &$menus ) {
		global $mosConfig_live_site;

		//mosMakeHtmlSafe( $row );
		$tabs = new mosTabs( 1 );
		// used to hide "Reset Hits" when hits = 0
		if ( !$row->hits ) {
			$visibility = "style='display: none; visbility: hidden;'";
		} else {
			$visibility = "";
		}

		mosCommonHTML::loadOverlib();
		mosCommonHTML::loadCalendar();
		?>
		<script language="javascript" type="text/javascript">
		var folderimages = new Array;
		<?php
		$i = 0;
		foreach ($images as $k=>$items) {
			foreach ($items as $v) {
				echo "\n	folderimages[".$i++."] = new Array( '$k','".addslashes( $v->value )."','".addslashes( $v->text )."' );";
			}
		}
		?>
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}

			if ( pressbutton ==' resethits' ) {
				if (confirm('<?php echo T_('Are you sure you want to reset the Hits to Zero? \nAny unsaved changes to this content will be lost.'); ?>')){
					submitform( pressbutton );
					return;
				} else {
					return;
				}
			}

			if ( pressbutton == 'menulink' ) {
				if ( form.menuselect.value == "" ) {
					alert( "<?php echo T_('Please select a Menu'); ?>" );
					return;
				} else if ( form.link_name.value == "" ) {
					alert( "<?php echo T_('Please enter a Name for this menu item'); ?>" );
					return;
				}
			}

			var temp = new Array;
			for (var i=0, n=form.imagelist.options.length; i < n; i++) {
				temp[i] = form.imagelist.options[i].value;
			}
			form.images.value = temp.join( '\n' );

			try {
				document.adminForm.onsubmit();
			}
			catch(e){}
			if (trim(form.title.value) == ""){
				alert( "<?php echo T_('Content item must have a title'); ?>" );
			} else if (trim(form.name.value) == ""){
				alert( "<?php echo T_('Content item must have a name'); ?>" );
			} else {
				if ( form.reset_hits.checked ) {
					form.hits.value = 0;
				} else {
				}
				<?php getEditorContents( 'editor1', 'introtext' ) ; ?>
				submitform( pressbutton );
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
		<table class="adminheading">
		<tr>
			<th class="edit">
			<?php echo T_('Static Content Item:'); ?>
			<small>
			<?php echo $row->id ? T_('Edit') : T_('New');?>
			</small>
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
		<form action="index2.php" method="post" name="adminForm">
		<input type ="hidden" name="simple_editing" value=''>
		
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td valign="top">
				<table class="adminform">
				<tr>
					<th colspan="3">
					<?php echo T_('Item Details'); ?>
					</th>
				<tr>
				<tr>
					<td align="left">
					<?php echo T_('Title:'); ?>
					</td>
					<td>
					<input class="inputbox" type="text" name="title" size="30" maxlength="100" value="<?php echo $row->title; ?>" />
					</td>
				</tr>
				<tr>
					<td align="left">
					<?php echo T_('Title Alias:'); ?>
					</td>
					<td>
					<input class="inputbox" type="text" name="title_alias" size="30" maxlength="100" value="<?php echo $row->title_alias; ?>" />
					</td>
				</tr>
				<tr>
					<td valign="top" align="left" colspan="2">
					<?php echo T_('Text: (required)'); ?><br />
					<?php
					// parameters : areaname, content, hidden field, width, height, rows, cols
					editorArea( 'editor1',  $row->introtext, 'introtext', '100%;', '400', '65', '50' );
					?>
					</td>
				</tr>
				</table>
			</td>
			
			<td valign="top" align="right">
			<div id="simpleediting" style="display:<?php echo $simpleediting;?>">
			<table width="100%" >
				<tr>
					<td width="200">
			
						<table width="400">
						<tr>
							<td >
				<?php
				$tabs->startPane("content-pane");
				$tabs->startTab(T_("Publishing"),"publish-page");
				?>
				<table class="adminform">
				<tr>
					<th colspan="2">
					<?php echo T_('Publishing Info'); ?>
					</th>
				<tr>
				<tr>
					<td valign="top" align="right">
					<?php echo T_('State:'); ?>
					</td>
					<td>
					<?php echo $row->state > 0 ? 'Published' : 'Draft Unpublished'; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">
					<?php echo T_('Published:'); ?>
					</td>
					<td>
					<input type="checkbox" name="published" value="1" <?php echo $row->state ? 'checked="checked"' : ''; ?> />
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">
					<?php echo T_('Show on Frontpage:'); ?>
					</td>
					<td>
					<input type="checkbox" name="frontpage" value="1" <?php echo $row->frontpage ? 'checked="checked"' : ''; ?> />
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">
					<?php echo T_('Access Level:'); ?>
					</td>
					<td>
					<?php echo $lists['access']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">
					<?php echo T_('Author Alias:'); ?>
					</td>
					<td>
					<input type="text" name="created_by_alias" size="30" maxlength="100" value="<?php echo $row->created_by_alias; ?>" class="inputbox" />
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">
					<?php echo T_('Change Creator:'); ?>
					</td>
					<td>
					<?php echo $lists['created_by']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">
					<?php echo T_('Override Created Date'); ?>
					</td>
					<td>
					<input class="inputbox" type="text" name="created" id="created" size="25" maxlength="19" value="<?php echo $row->created; ?>" />
					<input name="reset" type="reset" class="button" onClick="return showCalendar('created', 'y-mm-dd');" value="...">
					</td>
				</tr>
				<tr>
					<td width="20%" align="right">
					<?php echo T_('Start Publishing:'); ?>
					</td>
					<td width="80%">
					<input class="inputbox" type="text" name="publish_up" id="publish_up" size="25" maxlength="19" value="<?php echo $row->publish_up; ?>" />
					<input type="reset" class="button" value="..." onclick="return showCalendar('publish_up', 'y-mm-dd');">
					</td>
				</tr>
				<tr>
					<td width="20%" align="right">
					<?php echo T_('Finish Publishing:'); ?>
					</td>
					<td width="80%">
					<input class="inputbox" type="text" name="publish_down" id="publish_down" size="25" maxlength="19" value="<?php echo $row->publish_down; ?>" />
					<input type="reset" class="button" value="..." onclick="return showCalendar('publish_down', 'y-mm-dd');">
					</td>
				</tr>
				</table>
				<br />
				<table class="adminform">
				<?php
				if ( $row->id ) {
					?>
					<tr>
						<td>
						<strong><?php echo T_('Content ID:'); ?></strong>
						</td>
						<td>
						<?php echo $row->id; ?>
						</td>
					</tr>
					<?php
				}
				?>
				<tr>
					<td width="90px" valign="top" align="right">
					<strong><?php echo T_('State:'); ?></strong>
					</td>
					<td>
					<?php echo $row->state > 0 ? T_('Published') : ($row->state < 0 ? T_('Archived') : T_('Draft Unpublished'));?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">
					<strong><?php echo T_('Hits'); ?></strong>
					</td>
					<td>
					<?php echo $row->hits;?>
					<div <?php echo $visibility; ?>>
					<input name="reset_hits" type="button" class="button" value="<?php echo T_('Reset Hit Count'); ?>" onClick="submitbutton('resethits');">
					</div>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">
					<strong><?php echo T_('Version'); ?></strong>
					</td>
					<td>
					<?php echo "$row->version";?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">
					<strong><?php echo T_('Created'); ?></strong>
					</td>
					<td>
					<?php echo $row->created ? "$row->created</td></tr><tr><td valign='top' align='right'><strong>".T_('By')."</strong></td><td>$row->creator" : T_("New document");?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">
					<strong><?php echo T_('Last Modified'); ?></strong>
					</td>
					<td>
					<?php echo $row->modified ? "$row->modified</td></tr><tr><td valign='top' align='right'><strong>".T_('By')."</strong></td><td>$row->modifier" : T_("Not modified");?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right">
					<strong><?php echo T_('Expires'); ?></strong>
					</td>
					<td>
					<?php echo "$row->publish_down";?>
					</td>
				</tr>
				</table>
				<?php
				$tabs->endTab();
				$tabs->startTab(T_("Images"),"images-page");
				?>
				<table class="adminform">
				<tr>
					<th colspan="2">
					<?php echo T_('MOSImage Control'); ?>
					</th>
				<tr>
				<tr>
					<td colspan="6">
					<?php echo T_('Sub-folder:'); ?> <?php echo $lists['folders'];?>
					</td>
				</tr>
				<tr>
					<td>
					<?php echo T_('Gallery Images'); ?>
					<br />
					<?php echo $lists['imagefiles'];?>
					<br />
					<input class="button" type="button" value="<?php echo T_('Add'); ?>" onClick="addSelectedToList('adminForm','imagefiles','imagelist')" />
					</td>
					<td valign="top">
					<img name="view_imagefiles" src="../images/M_images/blank.png" width="100" />
					</td>
				</tr>
				<tr>
					<td>
					<?php echo T_('Content Images:'); ?>
					<br />
					<?php echo $lists['imagelist'];?>
					<br />
					<input class="button" type="button" value="<?php echo T_('up'); ?>" onClick="moveInList('adminForm','imagelist',adminForm.imagelist.selectedIndex,-1)" />
					<input class="button" type="button" value="<?php echo T_('down'); ?>" onClick="moveInList('adminForm','imagelist',adminForm.imagelist.selectedIndex,+1)" />
					<input class="button" type="button" value="<?php echo T_('remove'); ?>" onClick="delSelectedFromList('adminForm','imagelist')" />
					</td>
					<td valign="top">
					<img name="view_imagelist" src="../images/M_images/blank.png" width="100" />
					</td>
				</tr>
				<tr>
					<td>
					<?php echo T_('Edit the image selected:'); ?>
						<table>
						<tr>
							<td align="right">
							<?php echo T_('Source'); ?>
							</td>
							<td>
							<input type="text" name= "_source" value="" />
							</td>
						</tr>
						<tr>
							<td align="right">
							<?php echo T_('Align'); ?>
							</td>
							<td>
							<?php echo $lists['_align']; ?>
							</td>
						</tr>
						<tr>
							<td align="right">
							<?php echo T_('Alt Text'); ?>
							</td>
							<td>
							<input type="text" name="_alt" value="" />
							</td>
						</tr>
						<tr>
							<td align="right">
							<?php echo T_('Border'); ?>
							</td>
							<td>
							<input type="text" name="_border" value="" size="3" maxlength="1" />
							</td>
						</tr>
						<tr>
							<td align="right">
							<?php echo T_('Caption:'); ?>
							</td>
							<td>
							<input class="text_area" type="text" name="_caption" value="" size="30" />
							</td>
						</tr>
						<tr>
							<td align="right">
							<?php echo T_('Caption Position:'); ?>
							</td>
							<td>
							<?php echo $lists['_caption_position']; ?>
							</td>
						</tr>
						<tr>
							<td align="right">
							<?php echo T_('Caption Align:'); ?>
							</td>
							<td>
							<?php echo $lists['_caption_align']; ?>
							</td>
						</tr>
						<tr>
							<td align="right">
							<?php echo T_('Width:'); ?>
							</td>
							<td>
							<input class="text_area" type="text" name="_width" value="" size="5" maxlength="5" />
							</td>
						</tr>
						<tr>
							<td colspan="2">
							<input class="button" type="button" value="<?php echo T_('Apply'); ?>" onClick="applyImageProps()" />
							</td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
				<?php
				$tabs->endTab();
				$tabs->startTab(T_("Parameters"),"params-page");
				?>
				<table class="adminform">
				<tr>
					<th colspan="2">
					<?php echo T_('Parameter Control'); ?>
					</th>
				<tr>
				<tr>
					<td>
					<?php echo $params->render();?>
					</td>
				</tr>
				</table>
				<?php
				$tabs->endTab();
				$tabs->startTab(T_("Meta Info"),"metadata-page");
				?>
				<table class="adminform">
				<tr>
					<th colspan="2">
					<?php echo T_('Meta Data'); ?>
					</th>
				<tr>
				<tr>
					<td align="left">
					<?php echo T_('Description:'); ?><br />
					<textarea class="inputbox" cols="40" rows="5" name="metadesc" style="width:300px"><?php echo str_replace('&','&amp;',$row->metadesc); ?></textarea>
					</td>
				</tr>
				<tr>
					<td align="left">
					<?php echo T_('Keywords:'); ?><br />
					<textarea class="inputbox" cols="40" rows="5" name="metakey" style="width:300px"><?php echo str_replace('&','&amp;',$row->metakey); ?></textarea>
					</td>
				</tr>
				</table>
				<?php
				$tabs->endTab();
				$tabs->startTab(T_("Link to Menu"),"link-page");
				?>
				<table class="adminform">
				<tr>
					<th colspan="2">
					<?php echo T_('Link to Menu'); ?>
					</th>
				<tr>
				<tr>
					<td colspan="2">
					<?php echo T_('This will create a "Link - Static Content" in the menu you select'); ?>
					<br /><br />
					</td>
				<tr>
				<tr>
					<td valign="top" width="90px">
					<?php echo T_('Select a Menu'); ?>
					</td>
					<td>
					<?php echo $lists['menuselect']; ?>
					</td>
				<tr>
				<tr>
					<td valign="top" width="90px">
					<?php echo T_('Menu Item Name'); ?>
					</td>
					<td>
					<input type="text" name="link_name" class="inputbox" value="" size="30" />
					</td>
				<tr>
				<tr>
					<td>
					</td>
					<td>
					<input name="menu_link" type="button" class="button" value="<?php echo T_('Link to Menu'); ?>" onClick="submitbutton('menulink');" />
					</td>
				<tr>
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
					mosCommonHTML::menuLinksContent( $menus );
				}
				?>
				<tr>
					<td colspan="2">
					</td>
				</tr>
				</table>
				<?php
				$tabs->endTab();
				$tabs->endPane();
				?>
		    	</td>
						</tr>
						
						</table>
					</td>
					</tr>
					</table>
					</div>
					</td>
					
				</tr>
			</table>
			
		</td>
	</tr>
</table>
		<input type="hidden" name="images" value="" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="hits" value="<?php echo $row->hits; ?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<?php
	}
}
?>