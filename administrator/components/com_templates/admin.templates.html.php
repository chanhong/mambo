<?php
/**
* @package Mambo
* @subpackage Templates
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

class HTML_templates {
	/**
	* @param array An array of data objects
	* @param object A page navigation object
	* @param string The option
	*/
	function showTemplates( &$rows, &$pageNav, $option, $client ) {
		global $my, $mosConfig_live_site;

		if ( isset( $row->authorUrl) && $row->authorUrl != '' ) {
			$row->authorUrl = str_replace( 'http://', '', $row->authorUrl );
		}

		mosCommonHTML::loadOverlib();
		?>
		<script language="Javascript">
		<!--
		function showInfo(name) {
			var pattern = /\b \b/ig;
			name = name.replace(pattern,'_');
			name = name.toLowerCase();
			if (document.adminForm.doPreview.checked) {
				var src = '<?php echo $mosConfig_live_site . ($client == 'admin' ? '/administrator' : '');?>/templates/'+name+'/template_thumbnail.png';
				var html=name;
				html = '<br /><img border="1" src="'+src+'" name="imagelib" alt="<?php echo T_('No preview available'); ?>" width="206" height="145" />';
				return overlib(html, CAPTION, name)
			} else {
				return false;
			}
		}
		-->
		</script>

		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th class="templates">
			<?php echo T_('Template Manager'); ?> <small><small>[ <?php echo $client == 'admin' ? T_('Administrator') : T_('Site');?> ]</small></small>
			</th>
			<td align="right" nowrap="true">
			<?php echo T_('Preview Template'); ?>
			</td>
			<td align="right">
			<input type="checkbox" name="doPreview" checked="checked"/>
			</td>
		</tr>
		</table>
		<table class="adminlist">
		<tr>
			<th width="5%">#</th>
			<th width="5%">&nbsp;</th>
			<th width="25%" class="title">
			<?php echo T_('Name'); ?>
			</th>
			<?php
			if ( $client == 'admin' ) {
				?>
				<th width="10%">
				<?php echo T_('Default'); ?>
				</th>
				<?php
			} else {
				?>
				<th width="5%">
				<?php echo T_('Default'); ?>
				</th>
				<th width="5%">
				<?php echo T_('Assigned'); ?>
				</th>
				<?php
			}
			?>
			<th width="20%" align="left">
			<?php echo T_('Author'); ?>
			</th>
			<th width="5%" align="center">
			<?php echo T_('Version'); ?>
			</th>
			<th width="10%" align="center">
			<?php echo T_('Date'); ?>
			</th>
			<th width="20%" align="left">
			<?php echo T_('Author URL'); ?>
			</th>
		</tr>
		<?php
		$k = 0;
		for ( $i=0, $n = count( $rows ); $i < $n; $i++ ) {
			$row = &$rows[$i];
			?>
			<tr class="<?php echo 'row'. $k; ?>">
				<td>
				<?php echo $pageNav->rowNumber( $i ); ?>
				</td>
				<td>
				<?php
				if ( $row->checked_out && $row->checked_out != $my->id ) {
					?>
					&nbsp;
					<?php
				} else {
					?>
					<input type="radio" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->directory; ?>" onClick="isChecked(this.checked);" />
					<?php
				}
				?>
				</td>
				<td>
				<a href="#info" onmouseover="showInfo('<?php echo $row->name;?>')" onmouseout="return nd();">
				<?php echo $row->name;?>
				</a>
				</td>
				<?php
				if ( $client == 'admin' ) {
					?>
					<td align="center">
					<?php
					if ( $row->published == 1 ) {
						?>
					<img src="images/tick.png" alt="<?php echo T_('Published'); ?>">
						<?php
					} else {
						?>
						&nbsp;
						<?php
					}
					?>
					</td>
					<?php
				} else {
					?>
					<td align="center">
					<?php
					if ( $row->published == 1 ) {
						?>
						<img src="images/tick.png" alt="<?php echo T_('Default'); ?>">
						<?php
					} else {
						?>
						&nbsp;
						<?php
					}
					?>
					</td>
					<td align="center">
					<?php
					if ( $row->assigned == 1 ) {
						?>
						<img src="images/tick.png" alt="<?php echo T_('Assigned'); ?>" />
						<?php
					} else {
						?>
						&nbsp;
						<?php
					}
					?>
					</td>
					<?php
				}
				?>
				<td>
				<?php echo $row->authorEmail ? '<a href="mailto:'. $row->authorEmail .'">'. $row->author .'</a>' : $row->author; ?>
				</td>
				<td align="center">
				<?php echo $row->version; ?>
				</td>
				<td align="center">
				<?php echo $row->creationdate; ?>
				</td>
				<td>
				<a href="<?php echo substr( $row->authorUrl, 0, 7) == 'http://' ? $row->authorUrl : 'http://'.$row->authorUrl; ?>" target="_blank">
				<?php echo $row->authorUrl; ?>
				</a>
				</td>
			</tr>
			<?php
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0" />
		<input type="hidden" name="client" value="<?php echo $client;?>" />
		</form>
		<?php
	}


	/**
	* @param string Template name
	* @param string Source code
	* @param string The option
	*/
	function editTemplateSource( $template, &$content, $option, $client ) {
		global $mosConfig_absolute_path;
        $template_path =
            $mosConfig_absolute_path . ($client == 'admin' ? '/administrator':'') .
            '/templates/' . $template . '/index.php';
		?>
		<form action="index2.php" method="post" name="adminForm">
	    <table cellpadding="1" cellspacing="1" border="0" width="100%">
		<tr>
	        <td width="290"><table class="adminheading"><tr><th class="templates"><?php echo T_('Template HTML Editor'); ?></th></tr></table></td>
	        <td width="220">
	            <span class="componentheading">index.php is :
	            <strong><?php echo is_writable($template_path) ? '<span class="green">'.T_('Writeable').'</span>' : '<span class="red">'.T_('Unwriteable').'</span>' ?></strong>
	            </span>
	        </td>
<?php
	        if (mosIsChmodable($template_path)) {
	            if (is_writable($template_path)) {
?>
	        <td>
	            <input type="checkbox" id="disable_write" name="disable_write" value="1"/>
	            <label for="disable_write"><?php echo T_('Make unwriteable after saving'); ?></label>
	        </td>
<?php
	            } else {
?>
	        <td>
	            <input type="checkbox" id="enable_write" name="enable_write" value="1"/>
	            <label for="enable_write"><?php echo T_('Override write protection while saving'); ?></label>
	        </td>
<?php
	            } // if
	        } // if
?>
	    </tr>
	    </table>
		<table class="adminform">
	        <tr><th><?php echo $template_path; ?></th></tr>
	        <tr><td><textarea style="width:100%;height:500px" cols="110" rows="25" name="filecontent" class="inputbox"><?php echo $content; ?></textarea></td></tr>
		</table>
		<input type="hidden" name="template" value="<?php echo $template; ?>" />
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="client" value="<?php echo $client;?>" />
		</form>
		<?php
	}


	/**
	* @param string Template name
	* @param string Source code
	* @param string The option
	*/
	function editCSSSource( $template, &$content, $option, $client ) {
		global $mosConfig_absolute_path;
		$css_path =
			$mosConfig_absolute_path . ($client == 'admin' ? '/administrator' : '')
			. '/templates/' . $template . '/css/template_css.css';
		?>
		<form action="index2.php" method="post" name="adminForm">
	    <table cellpadding="1" cellspacing="1" border="0" width="100%">
		<tr>
	        <td width="280"><table class="adminheading"><tr><th class="templates"><?php echo T_('Template CSS Editor'); ?></th></tr></table></td>
	        <td width="260">
	            <span class="componentheading">template_css.css is :
	            <strong><?php echo is_writable($css_path) ? '<span class="green">'.T_('Writeable').'</span>' : '<span class="red">'.T_('Unwriteable').'</span>' ?></strong>
	            </span>
	        </td>
<?php
	        if (mosIsChmodable($css_path)) {
	            if (is_writable($css_path)) {
?>
	        <td>
	            <input type="checkbox" id="disable_write" name="disable_write" value="1"/>
	            <label for="disable_write"><?php echo T_('Make unwriteable after saving'); ?></label>
	        </td>
<?php
	            } else {
?>
	        <td>
	            <input type="checkbox" id="enable_write" name="enable_write" value="1"/>
	            <label for="enable_write"><?php echo T_('Override write protection while saving'); ?></label>
	        </td>
<?php
	            } // if
	        } // if
?>
	    </tr>
	    </table>
		<table class="adminform">
	        <tr><th><?php echo $css_path; ?></th></tr>
	        <tr><td><textarea style="width:100%;height:500px" cols="110" rows="25" name="filecontent" class="inputbox"><?php echo $content; ?></textarea></td></tr>
		</table>
		<input type="hidden" name="template" value="<?php echo $template; ?>" />
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="client" value="<?php echo $client;?>" />
		</form>
		<?php
	}


	/**
	* @param string Template name
	* @param string Menu list
	* @param string The option
	*/
	function assignTemplate( $template, &$menulist, $option ) {
		?>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminform">
		<tr>
			<th class="left" colspan="2">
			<?php printf(T_('Assign template %s to menu items'), $template)?>
			</th>
		</tr>
		<tr>
			<td valign="top" align="left">
			<?php echo T_('Page(s):'); ?>
			</td>
			<td width="90%">
			<?php echo $menulist; ?>
			</td>
		</tr>
		</table>
		<input type="hidden" name="template" value="<?php echo $template; ?>" />
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<?php
	}


	/**
	* @param array
	* @param string The option
	*/
	function editPositions( &$positions, $option ) {
		$rows = 25;
		$cols = 2;
		$n = $rows * $cols;
		?>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th class="templates">
			<?php echo T_('Module Positions'); ?>
			</th>
		</tr>
		</table>

		<table class="adminlist">
		<tr>
		<?php
		for ( $c = 0; $c < $cols; $c++ ) {
			?>
			<th width="25">
			#
			</th>
			<th align="left">
			<?php echo T_('Position'); ?>
			</th>
			<th align="left">
			<?php echo T_('Description'); ?>
			</th>
			<?php
		}
		?>
		</tr>
		<?php
		$i = 1;
		for ( $r = 0; $r < $rows; $r++ ) {
			?>
			<tr>
			<?php
			for ( $c = 0; $c < $cols; $c++ ) {
				?>
				<td>(<?php echo $i; ?>)</td>
				<td>
				<input type="text" name="position[<?php echo $i; ?>]" value="<?php echo @$positions[$i-1]->position; ?>" size="10" maxlength="10" />
				</td>
				<td>
				<input type="text" name="description[<?php echo $i; ?>]" value="<?php echo htmlspecialchars( @$positions[$i-1]->description ); ?>" size="50" maxlength="255" />
				</td>
				<?php
				$i++;
			}
			?>
			</tr>
			<?php
		}
		?>
		</table>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<?php
	}
}
?>