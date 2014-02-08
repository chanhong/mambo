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

class TOOLBAR_modules {
    /**
	* Draws the menu for a New module
	*/
   
    function _NEW()	{
        mosMenuBar::startTable();
        mosMenuBar::preview( 'modulewindow' );
        mosMenuBar::spacer();
        mosMenuBar::save();
        mosMenuBar::spacer();
        mosMenuBar::apply();
        mosMenuBar::spacer();
        mosMenuBar::cancel();
        mosMenuBar::spacer();
        mosMenuBar::help( 'new' );
        mosMenuBar::endTable();
    }

    /**
	* Draws the menu for Editing an existing module
	*/
    function _EDIT( $cur_template, $publish ) {
        global $id;

        mosMenuBar::startTable();
		?>
			<td><a class="toolbar" href="#" onClick="if (typeof document.adminForm.content == 'undefined') { alert('<?php echo T_('You can only preview typed modules.') ?>'); } else { var content = document.adminForm.content.value; content = content.replace('#', '');  var title = document.adminForm.title.value; title = title.replace('#', ''); window.open('popups/modulewindow.php?title=' + title + '&content=' + content + '&t=<?php echo $cur_template; ?>', 'win1', 'status=no,toolbar=no,scrollbars=auto,titlebar=no,menubar=no,resizable=yes,width=200,height=400,directories=no,location=no'); }" onmouseout="MM_swapImgRestore();"  onmouseover="MM_swapImage('preview','','images/preview_f2.png',1);"><img src="images/preview.png" alt="<?php echo T_('Preview') ?>" border="0" name="preview" align="middle"><br /><?php echo T_('Preview') ?></a></td>
		<?php
		mosMenuBar::spacer();
		mosMenuBar::save();
		mosMenuBar::spacer();
		mosMenuBar::apply();
		mosMenuBar::spacer();
		if ( $id ) {
		    // for existing content items the button is renamed `close`
		    mosMenuBar::cancel( 'cancel', T_('Close') );
		} else {
		    mosMenuBar::cancel();
		}
		mosMenuBar::spacer();

		$result = '';
		if ($_POST) {
		    $cid = (int) $GLOBALS['cid'][0];
		} else {
		    $cid = (int) $_GET['id'];
		}
		$database =& mamboDatabase::getInstance();
		$database->setQuery('select module from #__modules where id = '.$cid);
		$result = substr($database->loadResult(), 4);

		mosMenuBar::help( $result ? $result : 'edit' );
		mosMenuBar::endTable();
    }
    function _DEFAULT() {
        mosMenuBar::startTable();
        mosMenuBar::publishList();
        mosMenuBar::spacer();
        mosMenuBar::unpublishList();
        mosMenuBar::spacer();
        mosMenuBar::custom( 'copy', 'copy.png', 'copy_f2.png', T_('Copy'), true );
        mosMenuBar::spacer();
        mosMenuBar::addNewX();
        mosMenuBar::spacer();
        mosMenuBar::editListX();
        mosMenuBar::spacer();
        mosMenuBar::deleteList();
        mosMenuBar::spacer();
        mosMenuBar::help( 'admin.manager' );
        mosMenuBar::endTable();
    }
}
?>
