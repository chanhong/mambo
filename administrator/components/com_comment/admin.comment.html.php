<?php
/**
* @package Mambo
* @subpackage Comment
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


class HTML_comment {

	/**
	 * @param option
	 * @param rows - article details
	 * @param search - search criteria
	 * @param pageNav - page navigation status
	 * @return list of comments.
	 */
  function showComments( $option, &$rows, &$search, &$pageNav ) {

	# Load configuration file
	global $mosConfig_absolute_path, $mosConfig_live_site, $mainframe, $acl, $my;
	
  require($mosConfig_absolute_path."/administrator/components/com_comment/config.comment.php");

    ?>
    <form action="index2.php" method="post" name="adminForm">
	<table class="adminheading">
		<tr>
			<th>
			<?php echo T_('Comment Manager'); ?>
			</th>
			<td>
			<?php echo T_('Filter:'); ?>
			</td>
			<td>
			<input type="text" name="search" value="<?php echo $search;?>" class="inputbox" onChange="document.adminForm.submit();" />
			</td>
		</tr>
	</table>
    <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
      <tr>
	  	<th width="20">#</th>
        <th width="20" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" /></th>
        <th><div align="center"><?php echo T_('Author'); ?></div></th>
        <th><div align="left"><?php echo T_('Comments'); ?></div></th>
        <th><div align="center"><?php echo T_('Date'); ?></div></th>
        <th><div align="center"><?php echo T_('Content Item'); ?></div></th>
        <th><div align="center"><?php echo T_('Published'); ?></div></th>
      </tr>
      <?php
    $k = 0;
    for ($i=0, $n=count( $rows ); $i < $n; $i++) {
      $row = &$rows[$i];
	  $row->article_link = 'index2.php?option=com_comment&task=editA&hidemainmenu=1&id='. $row->id;
	  $link = 'index2.php?option=com_content&sectionid=0&task=edit&hidemainmenu=1&id='. $row->articleid;

      echo "<tr class='row$k'>";
	  echo "<td width='20'>".$pageNav->rowNumber( $i )."</td>";
      echo "<td width='5%'><input type='checkbox' id='cb$i' name='cid[]' value='$row->id' onclick='isChecked(this.checked);' /></td>";
      echo "<td align='center'>$row->name</td>";

      if(strlen($row->comments) > $admin_comments_length) {
        $row->comments  = substr($row->comments,0,$admin_comments_length-3);
        $row->comments .= "...";
      }

      echo "<td align='left'><a href='$row->article_link'>$row->comments</td>";
      echo "<td align='center'>$row->startdate</td>";
      echo "<td align='center'><a href='$link'>$row->title</a></td>";

      $task = $row->published ? 'unpublish' : 'publish';
      $img = $row->published ? 'publish_g.png' : 'publish_x.png';

      ?>
        <td width="10%" align="center"><a href="javascript: void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $task;?>')"><img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="" /></a></td>
    </tr>
    <?php    $k = 1 - $k; } ?>

  </table>

		<?php echo $pageNav->getListFooter(); ?>
  <input type="hidden" name="option" value="<?php echo $option;?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="hidemainmenu" value="0">
  <input type="hidden" name="boxchecked" value="0" />
  </form>
  <?php
  }

	/**
	 * @param option
	 * @param rows - article id, or 0 for new comment
	 * @param clist - article list to enable comments to be moved
	 * @param puplist - yes/no publish selection box
	 * @return article comments to edit/move or new article comments box
	 */
  function editComment( $option, &$row, &$clist, &$puplist ) {
    mosMakeHtmlSafe( $row, ENT_QUOTES, 'comments' );
    ?>

    <script language="javascript" type="text/javascript">
    function submitbutton(pressbutton) {
      var form = document.adminForm;
      if (pressbutton == 'cancel') {
        submitform( pressbutton );
        return;
      }
      // validation
      if (form.comments.value == ""){
        alert( "<?php echo T_('You must add a comment') ?>" );
      } else if (form.articleid.value == "0"){
        alert( "<?php echo T_('You must select a content item.') ?>" );
      } else {
        submitform( pressbutton );
      }
    }
    </script>

	<table cellpadding="4" cellspacing="0" border="0" width="100%">
		<tr>
			<td width="100%"><span class="sectionname"><?php echo $row->id ? 'Edit' : 'Add';?> <?php echo T_('Comments'); ?></span></td>
		</tr>
	</table>

    <table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">
    <form action="index2.php" method="post" name="adminForm" id="adminForm">
      <tr>
        <td width="20%" align="right"><?php echo T_('Name') ;?>:</td>
        <td width="80%">
          <input class="inputbox" type="text" name="name" size="50" maxlength="30" value="<?php echo $row->name;?>" />
        </td>
      </tr>

      <tr>
        <td valign="top" align="right"><?php echo T_('Comments') ;?>:</td>
        <td>
          <textarea class="inputbox" cols="50" rows="5" name="comments"><?php echo $row->comments;?></textarea>
        </td>
      </tr>

      <tr>
        <td valign="top" align="right"><?php echo T_('Published') ;?>:</td>
        <td>
          <?php echo $puplist; ?>
        </td>
      </tr>
	  
      <tr>
        <td valign="top" align="right"><?php echo T_('Content Item') ;?>:</td>
        <td>
          <?php echo $clist; ?>
        </td>
      </tr>
	  
    </table>

    <input type="hidden" name="id" value="<?php echo $row->id; ?>" />
    <input type="hidden" name="option" value="<?php echo $option;?>" />
    <input type="hidden" name="task" value="" />
    </form>
  <?php
  }


}
?>