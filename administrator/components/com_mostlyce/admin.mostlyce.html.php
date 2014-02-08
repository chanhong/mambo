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

class HTML_expConfig {

        function showconfig( &$row, &$lists, $option) {
                global $mosConfig_absolute_path, $mosConfig_live_site, $mosConfig_lang;
                $tabs = new mosTabs(0);
                
                if (!file_exists("$mosConfig_absolute_path/mambots/editors/mostlyce/jscripts/tiny_mce/info/editor_info_$mosConfig_lang.php")) {
                	$editor_info_lang = "english";
                } else {
                	$editor_info_lang = "$mosConfig_lang";
                }
                
                //Setup version information so we can decide which tooltip library to utilize
				if (file_exists($mosConfig_absolute_path.'includes/version.php')) {
					require_once($mosConfig_absolute_path.'includes/version.php');
					$_VERSION =& new version();
					$version = (float) $_VERSION->RELEASE;
				} else {
					//Unsupported Mambo version.  MOStlyCE 2.0+ is for Mambo 4.6+.
					$version = 0;
				}
				
				//Build version specific tooltip statement
				if ($version>=4.7) {
					mosCommonHTML::loadPrototype();
					mosCommonHTML::loadToolTip();
					$lib = 'prototype';
				} else {
					mosCommonHTML::loadOverlib();
					$lib = 'overlib';
				}

                ?>
                <?php 
                if ($version < 4.6) {
                	echo '<span style="color: red;font-size: 1.5em;">'.T_('Warning: This version of MOStlyCE is not support in Mambo versions less than 4.6!').'</span>';
                }
                ?>
                <table class="adminheading">
                <tr>
                        <th class="config">
                        <?php echo T_('MOStlyCE Configuration :'); ?>
                        <span class="componentheading">
                        <?php echo T_('mostlyce_config.php is :'); ?>
                         <?php echo is_writable( '../mambots/editors/mostlyce/jscripts/tiny_mce/mostlyce_config.php' ) ? '<span style="color: green;font-weight:bold;">'.T_('Writeable').'</span>' : '<span style="color: red;font-weight:bold;">'.T_('Unwriteable').'</span>'?>
                        </span>
                        </th>
                </tr>
                </table>
                <script type="text/javascript">
                function submitbutton(pressbutton) {
                        var form = document.adminForm;
                        if (pressbutton == 'save') {
                                //if (confirm ("Are you sure?")) {
                                submitform( pressbutton );
                                //}
                        } else {
                                document.location.href = 'index2.php';
                        }
                }
                </script>
                <form action="index2.php" method="post" name="adminForm">
                <?php
                $tabs->startPane("mosCE");
                $tabs->startTab(T_('General'),"editor_options");
                ?>
                <table class="adminform">
                <tr>
                    <td colspan="2"><?php echo sprintf(T_('The URL specified in $mosConfig_live_site in configuration.php is <strong>%s</strong><br />You <strong>MUST</strong> access this site from this <strong>exact URL</strong> when editing content.'),$mosConfig_live_site); ?>
                </tr>
                <tr>
                        <td>
                        <?php echo T_('Editor Themes'); ?>:
                        </td>
                        <td><?php echo $lists['editor_themes'];?></td>
                </tr>
				<tr>
                        <td>
                        <?php echo T_('Editor Compression'); ?>:
                        </td>
                        <td><?php 
                        	echo $lists['editor_compression'];
							$tip = T_('Turning compression on enables the TinyMCE compressor. This reduces traffic and speeds up the editor by up to 75%. NOTE: Not recommended for IE users at this time.  Causes editor in IE not to appear.');
                            echo mosToolTip($tip, 'editor_lang', '', 'tooltip.png', '', '#', $lib);
                            ?>
						</td>
                </tr>
                <tr>
                        <td>
                        <?php echo T_('Editor Language'); ?>:
                        </td>
                        <td><?php 
                        	echo $lists['editor_lang']; 
                        	$tip = T_('Additional languages can be added by downloading and install TinyMCE language packs found here - http://tinymce.moxiecode.com/language.php');
                            echo mosToolTip($tip, 'editor_lang', '', 'tooltip.png', '', '#', $lib);
                            ?>
                        </td>
                </tr>
                <tr>
                        <td>
                        <?php echo T_('List of supported Languages.'); ?>:
                        </td>
                        <td><input type="text" class="text_area" size="50" name="editor_lang_list" value="<?php echo $row->editor_lang_list; ?>" /></td>
                </tr>

                <tr>
                        <td>
                        <?php echo T_('Override Template CSS'); ?>:
                        </td>
                        <td><?php echo $lists['editor_css_override'];
                        	$tip = T_('Override your Template CSS file');
                            echo mosToolTip($tip, 'editor_lang', '', 'tooltip.png', '', '#', $lib);
                            ?>
                        </td>
                </tr>
                <tr>
                        <td>
                        <?php echo T_('Custom CSS File') ?>:
                        </td>
                        <td>
                        <input type="text" class="text_area" size="30" name="editor_custom_css" value="<?php echo $row->editor_custom_css; ?>" />
                        <?php $tip = T_('Name of custom css file. This file should be placed in your Template CSS directory.');
                              echo mosToolTip($tip, 'editor_lang', '', 'tooltip.png', '', '#', $lib);
                        ?>
                        </td>
                </tr>
                <tr>
                        <td>
                        <?php echo T_('Newlines'); ?>:
                        </td>
                        <td><?php echo $lists['editor_newlines'];
                        		$tip = T_('Result of a carriage return in the editor content area, BR or P.');
                                echo mosToolTip($tip, 'editor_lang', '', 'tooltip.png', '', '#', $lib);
                            ?>
                        </td>
                </tr>
                <tr>
                        <td>
                        <?php echo T_('Convert Absolute URLS.'); ?>:
                        </td>
                        <td><?php echo $lists['editor_convert_urls'];
                        	$tip = T_('If Yes, Absolute URLS from the Editor are converted to Relative URLS for page display. Necessary for SEF support.');
                            echo mosToolTip($tip, 'editor_lang', '', 'tooltip.png', '', '#', $lib);
                            ?>
                        </td>
                </tr>
                <tr>
                        <td>
                        <?php echo T_('Allow script elements') ?>:
                        </td>
                        <td><?php echo $lists['editor_script_acl'];?>
                        &nbsp;
                        <?php echo T_('SCRIPT Elements'); ?>
                        <input type="text" class="text_area" size="40" name="editor_script_elms" value="<?php echo $row->editor_script_elms; ?>" />
                        <?php $tip = T_('List of SCRIPT Elements. Must be seperated by | ');
                               echo mosToolTip($tip, 'editor_lang', '', 'tooltip.png', '', '#', $lib);
                        ?>
                        </td>
                </tr>
                <tr>
                        <td>
                        <?php echo T_('Allow IFRAME elements'); ?>:
                        </td>
                        <td><?php echo $lists['editor_iframe_acl'];?>
                        &nbsp;
                        <?php echo T_('IFRAME Elements'); ?>
                        <input type="text" class="text_area" size="40" name="editor_iframe_elms" value="<?php echo $row->editor_iframe_elms; ?>" />
                        <?php $tip = T_('List of IFRAME Elements. Must be seperated by | ');
                              echo mosToolTip($tip, 'editor_lang', '', 'tooltip.png', '', '#', $lib);
                        ?>
                        </td>
                </tr>
                <tr>
                        <td>
                        <?php echo T_('Extended Elements List'); ?>:
                        </td>
                        <td><input type="text" class="text_area" size="80" name="editor_xtd_elms" value="<?php echo $row->editor_xtd_elms; ?>" />
                        <?php $tip = T_('List of Extended Elements. Format is tag1[element1|element2],tag2[element1|element2]');
                              echo mosToolTip($tip, 'editor_lang', '', 'tooltip.png', '', '#', $lib);
                        ?>
                        </td>
                </tr>
                <tr>
                        <td>
                        <?php echo T_('Path to HTML Template Directory'); ?>:
                        </td>
                        <td><?php echo $mosConfig_absolute_path;?><input type="text" class="text_area" size="30" name="editor_tmpl_dir" value="<?php echo $row->editor_tmpl_dir; ?>" />
                        <?php $tip = T_('Absolute path to the directory where HTML templates are stored (HTML Template plugin).');
                              echo mosToolTip($tip, 'editor_lang', '', 'tooltip.png', '', '#', $lib);
                        ?>
                        </td>
                </tr>
                
                <tr>
                        <td>
                        <?php echo T_('Directionality'); ?>:
                        </td>
                        <td><?php echo $lists['editor_direction']; ?></td>
                </tr>
                </table>
                <?php
                $tabs->endTab();
                $tabs->startTab(T_('Layout'),"editor_layout");
                ?>
                <table class="adminform" border="1" width="75%">
                <tr>
                    <td><?php echo T_('Preview :'); ?></td>
                    <td>
                            <?php $row_buttons1 = explode(',', $row->editor_layout_row1);
                            foreach ($row_buttons1 as $btnImg1){
                              echo'<img src="components/com_mostlyce/images/'.trim($btnImg1).'.gif" alt="'.$btnImg1.'" title="'.$btnImg1.'" />';
                            }?>
                            <?php $row_buttons2 = explode(',', $row->editor_layout_row2);
                            foreach ($row_buttons2 as $btnImg2){
                              echo'<img src="components/com_mostlyce/images/'.trim($btnImg2).'.gif" alt="'.$btnImg2.'" title="'.$btnImg2.'" />';
                            }?>
                            <?php $row_buttons3 = explode(',', $row->editor_layout_row3);
                            foreach ($row_buttons3 as $btnImg3){
                              echo'<img src="components/com_mostlyce/images/'.trim($btnImg3).'.gif" alt="'.$btnImg3.'" title="'.$btnImg3.'" />';
                            }?>
                            <?php $row_buttons4 = explode(',', $row->editor_layout_row4);
                            foreach ($row_buttons4 as $btnImg4){
                              echo'<img src="components/com_mostlyce/images/'.trim($btnImg4).'.gif" alt="'.$btnImg4.'" title="'.$btnImg4.'" />';
                            }?>
                    </td>
                </tr>
                <tr>
                        <td>
                        <?php echo T_('Editor Width'); ?>:
                        </td>
                        <td>
                        <input type="text" class="text_area" size="5" name="editor_width" value="<?php echo $row->editor_width; ?>" />&nbsp;px
                        </td>
                </tr>
                <tr>
                        <td>
                        <?php echo T_('Editor Height'); ?>:
                        </td>
                        <td>
                        <input type="text" class="text_area" size="5" name="editor_height" value="<?php echo $row->editor_height; ?>" />&nbsp;px
                        </td>
                </tr>
                <tr>
                        <td colspan="2">
                        <?php echo T_('You can remove buttons from the layout by placing a minus sign (-) in front of the button name, eg: -cut'); ?>
                        </td>
                </tr>
                <tr>
                        <td>
                        <?php echo T_('Row 1 buttons'); ?>:
                        </td>
                        <td><textarea class="text_area" cols="100" rows="5" name="editor_layout_row1"><?php echo $row->editor_layout_row1; ?></textarea></td>
                </tr>
                <tr>
                        <td>
                        <?php echo T_('Row 2 buttons'); ?>:
                        </td>
                        <td><textarea class="text_area" cols="100" rows="5" name="editor_layout_row2"><?php echo $row->editor_layout_row2; ?></textarea></td>
                </tr>
                <tr>
                        <td>
                        <?php echo T_('Row 3 buttons'); ?>:
                        </td>
                        <td><textarea class="text_area" cols="100" rows="5" name="editor_layout_row3"><?php echo $row->editor_layout_row3; ?></textarea></td>
                </tr>
                <tr>
                        <td>
                        <?php echo T_('Row 4 buttons'); ?>:
                        </td>
                        <td><textarea class="text_area" cols="100" rows="5" name="editor_layout_row4"><?php echo $row->editor_layout_row4; ?></textarea></td>
                </tr>
                </table>
                <?php
                $tabs->endTab();
                $tabs->startTab(T_('Plugins'),"editor_plugins");
                ?>
                <table class="adminform">
                <tr>
                <td><?php echo T_('<strong>Note:</strong>  Disabling these may speed up the editor since there is less to load, but it will also mean the editor has less functionality.  If you disable plugins you\'ll probably want to rearrange your plugin icon layout as well.'); ?>
                </td>
                </tr>
                </table>
                <table class="adminform">
                <tr>
                        <td>
                        <?php echo T_('Load Date/Time'); ?>:
                        </td>
                        <td>
                        <?php echo $lists['editor_plugin_datetime_acl']; ?>
                        </td>
                </tr>
                <tr>
                        <td>
                        <?php echo T_('Date Format'); ?>:
                        </td>
                        <td>
                        <input type="text" class="text_area" size="30" name="editor_plugin_dateformat" value="<?php echo $row->editor_plugin_dateformat; ?>" />
                        <?php $tip = T_('%y  year as number without century (00 to 99), %Y year as number including the century, %d day of month as number (01 to 31), %m month as number (01 to 12), %D same as %m/%d/%y');
                              echo mosToolTip($tip, 'editor_lang', '', 'tooltip.png', '', '#', $lib);
                        ?>
                        </td>
                </tr>
                <tr>
                        <td>
                        <?php echo T_('Time Format') ?>:
                        </td>
                        <td>
                        <input type="text" class="text_area" size="30" name="editor_plugin_timeformat" value="<?php echo $row->editor_plugin_timeformat; ?>" />
                        <?php $tip = T_('%r time with a.m./p.m. , %H hour as number 24-hour clock (00 to 23), %I hour as number 12-hour clock (01 to 12), %M min as number (00-59), %S sec as number (00-59), %p either a.m. or p.m. based on given time');
                              echo mosToolTip($tip, 'editor_lang', '', 'tooltip.png', '', '#', $lib);
                        ?>
                        </td>
                </tr>
                <tr>
                        <td>
                        <?php echo T_('Load Emotions'); ?>:
                        </td>
                        <td>
                        <?php echo $lists['editor_plugin_emotions_acl']; ?>
                        </td>
                </tr>
                <tr>
                        <td>
                        <?php echo T_('Load Media (ex) Flash'); ?>:
                        </td>
                        <td>
                        <?php echo $lists['editor_plugin_media_acl']; ?>
                        </td>
                </tr>
                <tr>
                        <td>
                        <?php echo T_('Load Tables'); ?>:
                        </td>
                        <td>
                        <?php echo $lists['editor_plugin_table_acl']; ?>
                        </td>
                </tr>
                <tr>
                        <td>
                        <?php echo T_('Load Font Options'); ?>:
                        </td>
                        <td>
                        <?php echo $lists['editor_font_tools_acl']; ?>
                        </td>
                </tr>
                <tr>
                        <td>
                        <?php echo T_('Load Print'); ?>:
                        </td>
                        <td><?php echo $lists['editor_plugin_print']; ?></td>
                </tr>
                <tr>
                        <td>
                        <?php echo T_('Load Search and Replace'); ?>:
                        </td>
                        <td><?php echo $lists['editor_plugin_searchreplace']; ?></td>
                </tr>
                <tr>
                        <td>
                        <?php echo T_('Load Spellchecker'); ?>:
                        </td>
                        <td>
                        <?php echo $lists['editor_plugin_spellchecker']; ?>
                        <?php $tip = T_('The Spellchecker plugin is still experimental, but seems to work well in FireFox 1.5+ on non-Windows machines.  This plugin requires CURL.');
                              echo mosToolTip($tip, 'editor_lang', '', 'tooltip.png', '', '#', $lib);
                        ?>
                        </td>
                </tr>
                <tr>
                        <td>
                        <?php echo T_('Load Layers'); ?>:
                        </td>
                        <td><?php echo $lists['editor_plugin_layer']; ?></td>
                </tr>
                <tr>
                        <td>
                        <?php echo T_('3rd Party Plugins'); ?>:
                        </td>
                        <td>
                        <input type="text" class="text_area" size="30" name="editor_extra_plugins" value="<?php echo $row->editor_extra_plugins; ?>" />
                        <?php $tip = T_('Comma seperated list of extra plugins. The plugins indicated should be placed in the MOStlyCE plugins directory and should be named exactly as specified.');
                              echo mosToolTip($tip, 'editor_lang', '', 'tooltip.png', '', '#', $lib);
                        ?>
                        </td>
                </tr>
                </table>
                <?php
                $tabs->endTab();
                $tabs->startTab(T_('Image Mgr'),"editor_img_mgr_tab");
                ?>
                <table class="adminform">
                <tr>
                <?php $docroot = $_SERVER['DOCUMENT_ROOT']; ?>

                <td>
                <?php echo sprintf( T_('
						<strong>Note:</strong>  The Image Manager plugin allows for basic image editing and
						linking functionality directly within the editor.  It duplicates some of the
						functionality found in Media Manager, but allows you to do these functions
						while actually creating content.  The file manager supports some basic file
						management capabilites like uploading, creating directories, renaming and
						deleting files / folders, etc.  It does not yet allow for linking to files
						which are not images.<br /><br />
						
						<span style="color: Red;"><strong>Important</strong></span>: In
						order to utilize the Image Manager functionality you must create the folder
						structure shown below in your document root folder.  Not your Mambo root,
						your document root!  For this installation your document root is
						<strong>%s</strong>.  You can create this structure manually or extract the
						UserFiles.zip file included with MOStlyCE at that location.  You can find
						the UserFiles.zip file at /mambots/editors/mostlyce.<br /><br />
						
						You must also make sure these folders are writable after you have created
						them (ex) chmod to 0755:<br />
						   1) UserFiles<br />
						 2) UserFiles/Image<br />
						 3) UserFiles/File<br />
						 4) UserFiles/Media<br />
						 5) UserFiles/Flash'),$docroot); 
                ?>

                </td>
                </tr>
                </table>
                <table class="adminform">
                <tr>
                        <td>
                        <?php echo T_('Load Image Manager'); ?>:
                        <?php echo $lists['editor_plugin_img_mgr']; ?>
                        <?php $tip = T_('Turns the Image Manager plugin on and off. Do not turn this on until you have created the folder structure show above and have made them writable. NOTE: Not recommended for use with the Opera web browser.');
                              echo mosToolTip($tip, 'editor_lang', '', 'tooltip.png', '', '#', $lib);
                        ?>
                        </td>
                </tr>
                <tr>
                        <td>
                        <?php echo T_('Display {mosimage} Icon'); ?>:
                        <?php echo'<img src="components/com_mostlyce/images/mosimage.gif" alt="mosimage" title="mosimage" />'; ?>
                        <?php echo $lists['editor_mosimage_icon']; ?>
                        <?php $tip = T_('If you are using the MOStlyCE Image Manager plugin then you may wish to remove the original {mosimage} option.');
                              echo mosToolTip($tip, 'editor_lang', '', 'tooltip.png', '', '#', $lib);
                        ?>
                        </td>
                </tr>
                </table>
                <?php
                $tabs->endTab();
                $tabs->startTab(T_('Editor Info'),"editor_info2");
                ?>
                <table class="adminform">
                <tr>
                    <td>
                    <?php readfile( "$mosConfig_absolute_path/mambots/editors/mostlyce/jscripts/tiny_mce/info/editor_info_$editor_info_lang.txt" ); ?>
                    </td>
                </tr>
                </table>
                <?php
                $tabs->endTab();
                $tabs->endPane();
                ?>

                <input type="hidden" name="option" value="<?php echo $option; ?>" />
                <input type="hidden" name="task" value="" />
                </form>
                <?php
        }

}
?>