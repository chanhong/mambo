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

/**
* Utility class for the button bar
*/
class mosMenuBar {

    /**
	* Writes the start of the button bar table
	*/
    function startTable() {
		?>
		<script language="JavaScript" type="text/JavaScript">
		<!--
		function MM_swapImgRestore() { //v3.0
		    var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
		}
		//-->
		</script>
		<table cellpadding="3" cellspacing="0" border="0">
		<tr>
		<?php
    }

    /**
	* Writes a custom option and task button for the button bar
	* @param string The task to perform (picked up by the switch($task) blocks
	* @param string The image to display
	* @param string The image to display when moused over
	* @param string The alt text for the icon image
	* @param boolean True if required to check that a standard list item is checked
	*/
    function custom( $task='', $icon='', $iconOver='', $alt='', $listSelect=true, $prefix='' ) {
		global $mosConfig_locale,$mosConfig_lang;
        if ($listSelect) {
            $href = "javascript:
			var task='$task';
			var locale='$mosConfig_locale';
			var language='$mosConfig_lang';
			if (document.adminForm.boxchecked.value == 0){
				alert('".sprintf(T_('Please make a selection from the list to %s'),$alt)."');
			}else {
				if(task=='translate'){
					if(getSelectedRadio('adminForm','lang')!=locale)
					{
						alert('".sprintf(T_('You can translate only the default language.The curren default language is %s.'),$mosConfig_lang)."');
					}else{
						".$prefix."submitbutton('$task');
					}
				}else{
					".$prefix."submitbutton('$task');
				}
		}";
        } else {
            $href = "javascript:".$prefix."submitbutton('$task')";
        }
        if ($icon && $iconOver) {
		?>
		<td>
		<a class="toolbar" href="<?php echo $href;?>" onmouseout="MM_swapImgRestore();"  onmouseover="MM_swapImage('<?php echo $task;?>','','images/<?php echo $iconOver;?>',1);">
		<img name="<?php echo $task;?>" src="images/<?php echo $icon;?>" alt="<?php echo $alt;?>" border="0" align="middle" /><br />
		<?php echo $alt; ?></a>
		</td>
		<?php
        } else {
		?>
		<td>
		<a class="toolbar" href="<?php echo $href;?>">
		<br />
		<?php echo $alt; ?></a>
		</td>
		<?php
        }
    }

    /**
	* Writes a custom option and task button for the button bar.
	* Extended version of custom() calling hideMainMenu() before submitbutton().
	* @param string The task to perform (picked up by the switch($task) blocks
	* @param string The image to display
	* @param string The image to display when moused over
	* @param string The alt text for the icon image
	* @param boolean True if required to check that a standard list item is checked
	*/
    function customX( $task='', $icon='', $iconOver='', $alt='', $listSelect=true ) {
        mosMenuBar::custom ($task, $icon, $iconOver, $alt, $listSelect, 'hideMainMenu();');
    }

    /**
	* Standard routine for displaying toolbar icon
	* @param string An override for the task
	* @param string An override for the alt text
	* @param string The name to be used as a legend and as the image name
	* @param
	*/
    function addToToolBar ($task, $alt, $name, $imagename, $extended=false, $listprompt='') {
        if (is_null($alt)) $alt = T_($name);
        $image = mosAdminMenus::ImageCheckAdmin( $imagename.'.png', '/administrator/images/', NULL, NULL, $alt, $task );
        $image2 = mosAdminMenus::ImageCheckAdmin( $imagename.'_f2.png', '/administrator/images/', NULL, NULL, $alt, $task, 0 );
		?>
		<td>
		<a class="toolbar" href="javascript:<?php echo mosMenuBar::makeJavaScript ($task, $extended, $listprompt); ?>" onmouseout="MM_swapImgRestore();"  onmouseover="MM_swapImage('<?php echo $task;?>','','<?php echo $image2; ?>',1);">
		<?php
		echo $image;
		echo '<br />';
		echo $alt;
		?>
		</a>
		</td>
		<?php
    }

    function makeJavaScript ($task, $extended, $listprompt='') {
        $script = '';
        if ($listprompt) $script .= "if (document.adminForm.boxchecked.value == 0){ alert('$listprompt'); } else";
        $script .= '{';
        if ($extended) $script .= 'hideMainMenu();';
        $script .= "submitbutton('$task')}";
        return $script;
    }

    function getTemplate () {
        global $database;
        $sql = "SELECT template FROM #__templates_menu WHERE client_id='1' AND menuid='0'";
        $database->setQuery( $sql );
        return $database->loadResult();
    }

    /**
	* Writes the common 'new' icon for the button bar
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    function addNew( $task='new', $alt=null ) {
        mosMenuBar::addToToolBar ($task, $alt, T_('New'), 'new');
    }

    /**
	* Writes the common 'new' icon for the button bar.
	* Extended version of addNew() calling hideMainMenu() before submitbutton().
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    function addNewX( $task='new', $alt=null ) {
        mosMenuBar::addToToolBar ($task, $alt, T_('New'), 'new', true);
    }

    /**
	* Writes a common 'publish' button
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    function publish( $task='publish', $alt=null ) {
        mosMenuBar::addToToolBar ($task, $alt, T_('Publish'), 'publish');
    }

    /**
	* Writes a common 'publish' button for a list of records
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    function publishList( $task='publish', $alt=null ) {
        $listprompt = T_('Please make a selection from the list to publish');
        mosMenuBar::addToToolBar ($task, $alt, T_('Publish'), 'publish', false, $listprompt);
    }

    /**
	* Writes a common 'default' button for a record
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    function makeDefault( $task='default', $alt=null ) {
        $listprompt = T_('Please select an item to make default');
        mosMenuBar::addToToolBar ($task, $alt, T_('Default'), 'publish', false, $listprompt);
    }

    /**
	* Writes a common 'assign' button for a record
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    function assign( $task='assign', $alt=null ) {
        $listprompt = T_('Please select an item to assign');
        mosMenuBar::addToToolBar ($task, $alt, T_('Assign'), 'publish', false, $listprompt);
    }

    /**
	* Writes a common 'unpublish' button
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    function unpublish( $task='unpublish', $alt=null ) {
        mosMenuBar::addToToolBar ($task, $alt, T_('Unpublish'), 'unpublish');
    }

    /**
	* Writes a common 'unpublish' button for a list of records
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    function unpublishList( $task='unpublish', $alt=null ) {
        $listprompt = T_('Please make a selection from the list to unpublish');
        mosMenuBar::addToToolBar ($task, $alt, T_('Unpublish'), 'unpublish', false, $listprompt);
    }

    /**
	* Writes a common 'archive' button for a list of records
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    function archiveList( $task='archive', $alt=null ) {
        $listprompt = T_('Please make a selection from the list to archive');
        mosMenuBar::addToToolBar ($task, $alt, T_('Archive'), 'archive', false, $listprompt);
    }

    /**
	* Writes an unarchive button for a list of records
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    function unarchiveList( $task='unarchive', $alt=null ) {
        $listprompt = T_('Please select a news story to unarchive');
        mosMenuBar::addToToolBar ($task, $alt, T_('Unarchive'), 'unarchive', false, $listprompt);
    }

    /**
	* Writes a common 'edit' button for a list of records
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    function editList( $task='edit', $alt=null ) {
        $listprompt = T_('Please select an item from the list to edit');
        mosMenuBar::addToToolBar ($task, $alt, T_('Edit'), 'edit', false, $listprompt);
    }

    /**
	* Writes a common 'edit' button for a list of records.
	* Extended version of editList() calling hideMainMenu() before submitbutton().
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    function editListX( $task='edit', $alt=null ) {
        $listprompt = T_('Please select an item from the list to edit');
        mosMenuBar::addToToolBar ($task, $alt, T_('Edit'), 'edit', true, $listprompt);
    }

    /**
	* Writes a common 'edit' button for a template html
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    function editHtml( $task='edit_source', $alt=null ) {
        $listprompt = T_('Please select an item from the list to edit');
        mosMenuBar::addToToolBar ($task, $alt, T_('Edit HTML'), 'html', false, $listprompt);
    }

    /**
	* Writes a common 'edit' button for a template html.
	* Extended version of editHtml() calling hideMainMenu() before submitbutton().
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    function editHtmlX( $task='edit_source', $alt=null ) {
        $listprompt = T_('Please select an item from the list to edit');
        mosMenuBar::addToToolBar ($task, $alt, T_('Edit HTML'), 'html', true, $listprompt);
    }

    /**
	* Writes a common 'edit' button for a template css
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    function editCss( $task='edit_css', $alt=null ) {
        $listprompt = T_('Please select an item from the list to edit');
        mosMenuBar::addToToolBar ($task, $alt, T_('Edit CSS'), 'css', false, $listprompt);
    }

    /**
	* Writes a common 'edit' button for a template css.
	* Extended version of editCss() calling hideMainMenu() before submitbutton().
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    function editCssX( $task='edit_css', $alt=null ) {
        $listprompt = T_('Please select an item from the list to edit');
        mosMenuBar::addToToolBar ($task, $alt, T_('Edit CSS'), 'css', true, $listprompt);
    }

    /**
	* Writes a common 'delete' button for a list of records
	* @param string  Postscript for the 'are you sure' message
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    function deleteList( $msg='', $task='remove', $alt=null ) {
        $listprompt = T_('Please make a selection from the list to delete');
        mosMenuBar::addToToolBar ($task, $alt, T_('Delete'), 'delete', false, $listprompt);
    }

    /**
	* Writes a common 'delete' button for a list of records.
	* Extended version of deleteList() calling hideMainMenu() before submitbutton().
	* @param string  Postscript for the 'are you sure' message
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    function deleteListX( $msg='', $task='remove', $alt=null ) {
        $listprompt = T_('Please make a selection from the list to delete');
        mosMenuBar::addToToolBar ($task, $alt, T_('Delete'), 'delete', true, $listprompt);
    }

    /**
	* Write a trash button that will move items to Trash Manager
	*/
    function trash( $task='remove', $alt=null ) {
        mosMenuBar::addToToolBar ($task, $alt, T_('Trash'), 'delete');
    }

    /**
	* Writes a preview button for a given option (opens a popup window)
	* @param string The name of the popup file (excluding the file extension)
	*/
    function preview( $popup='', $updateEditors=false ) {
        $image = mosAdminMenus::ImageCheckAdmin( 'preview.png', '/administrator/images/', NULL, NULL, T_('Preview'), 'preview' );
        $image2 = mosAdminMenus::ImageCheckAdmin( 'preview_f2.png', '/administrator/images/', NULL, NULL, T_('Preview'), 'preview', 0 );
        $cur_template = mosMenuBar::getTemplate();
		?>
		<td>
		<script language="javascript">
		function popup() {
		    <?php
		    if ($popup == 'contentwindow') {
		        getEditorContents( 'editor1', 'introtext' );
		        getEditorContents( 'editor2', 'fulltext' );
		    }
		    elseif ($popup == 'modulewindow') getEditorContents( 'editor1', 'content' );
		    ?>
		    window.open('index3.php?pop=/<?php echo $popup;?>.php&amp;t=<?php echo $cur_template; ?>', 'win1', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no');
		}
		</script>
	 	<a class="toolbar" href="#" onclick="popup();" onmouseout="MM_swapImgRestore();"  onmouseover="MM_swapImage('preview','','<?php echo $image2; ?>',1);">
		<?php echo $image; ?><br />
		<?php echo T_('Preview'); ?>
		</a>
		</td>
		<?php
    }

    /**
	* Writes a preview button for a given option (opens a popup window)
	* @param string The name of the popup file (excluding the file extension for an xml file)
	* @param boolean Use the help file in the component directory
	*/
    function help( $ref, $com=false ) {
        $image = mosAdminMenus::ImageCheckAdmin( 'help.png', '/administrator/images/', NULL, NULL, T_('Help'), 'help' );
        $image2 = mosAdminMenus::ImageCheckAdmin( 'help_f2.png', '/administrator/images/', NULL, NULL, T_('Help'), 'help', 0 );
        $mosConfig_live_site = mamboCore::get('mosConfig_live_site');
        $rootpath = mamboCore::get('rootPath');
        /*$helpUrl = mosGetParam( $GLOBALS, 'mosConfig_helpurl', '' );
        if ($helpUrl) {
        $url = $helpUrl . '/index2.php?option=com_content&amp;task=findkey&pop=1&keyref=' . urlencode( $ref );
        } else {*/
        $option = $GLOBALS['option'];
        if (substr($option,0,4) != 'com_') $option = "com_$option";
        $component = substr($option, 4);
        if ($com) {
            $url = '/administrator/components/' . $option . '/help/';
        }else{
            $url = '/help/';
        }
        $ref = $component.'.'.$ref . '.html';
        $url .= $ref;

        if (!file_exists($rootpath.'/help/'.$ref)) return false;
        $url = $mosConfig_live_site . $url;

        
        /*}*/

		?>
		<td>
		<a class="toolbar" href="#" onclick="window.open('<?php echo $url;?>', 'mambo_help_win', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no');" onmouseout="MM_swapImgRestore();"  onmouseover="MM_swapImage('help','','<?php echo $image2; ?>',1);">
		<?php echo $image . '<br />' . T_('Help'); ?>
		</a>
		</td>
		<?php
    }

    /**
	* Writes a save button for a given option
	* Apply operation leads to a save action only (does not leave edit mode)
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    function apply( $task='apply', $alt=null ) {
        mosMenuBar::addToToolBar ($task, $alt, T_('Apply'), 'apply');
    }

    /**
	* Writes a save button for a given option
	* Save operation leads to a save and then close action
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    function save( $task='save', $alt=null ) {
        mosMenuBar::addToToolBar ($task, $alt, T_('Save'), 'save');
    }

    /**
	* Writes a save button for a given option (NOTE this is being deprecated)
	*/
    function savenew() {
        $image = mosAdminMenus::ImageCheckAdmin( 'save.png', '/administrator/images/', NULL, NULL, 'save', 'save' );
        $image2 = mosAdminMenus::ImageCheckAdmin( 'save_f2.png', '/administrator/images/', NULL, NULL, 'save', 'save', 0 );
		?>
		<td>
		<a class="toolbar" href="javascript:submitbutton('savenew');" onmouseout="MM_swapImgRestore();"  onmouseover="MM_swapImage('save','','<?php echo $image2; ?>',1);">
		<?php echo $image; ?><br />
		<?php echo T_('Save'); ?>
		</a>
		</td>
		<?php
    }

    /**
	* Writes a save button for a given option (NOTE this is being deprecated)
	*/
    function saveedit() {
        $image = mosAdminMenus::ImageCheckAdmin( 'save.png', '/administrator/images/', NULL, NULL, 'save', 'save' );
        $image2 = mosAdminMenus::ImageCheckAdmin( 'save_f2.png', '/administrator/images/', NULL, NULL, 'save', 'save', 0 );
		?>
		<td>
		<a class="toolbar" href="javascript:submitbutton('saveedit');" onmouseout="MM_swapImgRestore();"  onmouseover="MM_swapImage('save','','<?php echo $image2; ?>',1);">
		<?php echo $image; ?><br />
		<?php echo T_('Save'); ?>
		</a>
		</td>
		<?php
    }

    /**
	* Writes a cancel button and invokes a cancel operation (eg a checkin)
	* @param string An override for the task
	* @param string An override for the alt text
	*/
    function cancel( $task='cancel', $alt=null ) {
        mosMenuBar::addToToolBar ($task, $alt, T_('Cancel'), 'cancel');
    }

    /**
	* Writes a cancel button that will go back to the previous page without doing
	* any other operation
	*/
    function back( $alt=null, $href='' ) {
        if (is_null($alt)) $alt = T_('Back');
        $image = mosAdminMenus::ImageCheckAdmin( 'back.png', '/administrator/images/', NULL, NULL, 'back', 'cancel' );
        $image2 = mosAdminMenus::ImageCheckAdmin( 'back_f2.png', '/administrator/images/', NULL, NULL, 'back', 'cancel', 0 );
        if ( $href ) {
            $link = $href;
        } else {
            $link = 'javascript:window.history.back();';
        }
		?>
		<td>
		<a class="toolbar" href="<?php echo $link; ?>" onmouseout="MM_swapImgRestore();"  onmouseover="MM_swapImage('cancel','','<?php echo $image2; ?>',1);">
		<?php echo $image; ?><br />
		<?php echo $alt;?>
		</a>
		</td>
		<?php
    }

    /**
	* Write a divider between menu buttons
	*/
    function divider() {
        $image = mosAdminMenus::ImageCheckAdmin( 'menu_divider.png', '/administrator/images/' );
		?>
		<td>
		<?php echo $image; ?>
		</td>
		<?php
    }

    /**
	* Writes a media_manager button
	* @param string The sub-drectory to upload the media to
	*/
    function media_manager( $directory = '', $alt=null ) {
        if (is_null($alt)) $alt = T_('Upload');
        $cur_template = mosMenuBar::getTemplate();
        $image = mosAdminMenus::ImageCheckAdmin( 'upload.png', '/administrator/images/', NULL, NULL, T_('Upload Image'), 'uploadPic' );
        $image2 = mosAdminMenus::ImageCheckAdmin( 'upload_f2.png', '/administrator/images/', NULL, NULL, T_('Upload Image'), 'uploadPic', 0 );
		?>
		<td>
		<a class="toolbar" href="#" onclick="popupWindow('index3.php?pop=uploadimage.php&amp;directory=<?php echo $directory; ?>&amp;t=<?php echo $cur_template; ?>','win1',350,100,'no');" onmouseout="MM_swapImgRestore();"  onmouseover="MM_swapImage('uploadPic','','<?php echo $image2; ?>',1);">
		<?php echo $image; ?><br />
		<?php echo $alt;?>
		</a>
		</td>
		<?php
    }

    /**
	* Writes a spacer cell
	* @param string The width for the cell
	*/
    function spacer( $width='' )
    {
        if ($width != '') {
?>
		<td width="<?php echo $width;?>">&nbsp;</td>
<?php
        } else {
?>
		<td>&nbsp;</td>
<?php
        }
    }

    /**
	* Writes the end of the menu bar table
	*/
    function endTable() {
		?>
		</tr>
		</table>
		<?php
    }
}
?>
