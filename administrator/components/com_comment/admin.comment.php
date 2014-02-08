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

require_once( $mosConfig_absolute_path."/administrator/components/com_comment/class.comment.php");
require_once( $mainframe->getPath( 'admin_html' ) );

switch ($task) {

	case "new":
		editComment( $option, 0 );
		break;

	case "edit":
		editComment( $option, $cid[0] );
		break;

	case 'editA':
		editComment( $option, intval( $id ) );
		break;

	case "save":
		saveComment( $option );
		break;

	case "remove":
		removeComments( $cid, $option );
		break;

	case "publish":
		publishComments( $cid, 1, $option );
		break;

	case "unpublish":
		publishComments( $cid, 0, $option );
		break;

	case "settings":
		showConfig( $option );
		break;

	case "savesettings":
		$allow_comments_in_sections = implode(',',$_POST['mcselections']);
		saveConfig ($option, $auto_publish_comments, $allow_anonymous_entries, $notify_new_entries, $allow_comments_in_sections, $comments_per_page, $admin_comments_length);
		break;

	default:
		showComments( $option );
		break;

}

/**
 * @param option
 * @return list of comments
 */
function showComments ( $option ) {
	global $database, $mainframe;
	$limit      = $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', 10 );
	$limitstart = $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 );
	$search     = $mainframe->getUserStateFromRequest( "search{$option}", 'search', '' );
	$search     = $database->getEscaped( trim( strtolower( $search ) ) );
	$where = array();
	if ($search) {
		$where[] = "LOWER(comments) LIKE '%$search%'";
	}
	$database->setQuery( "SELECT count(*) FROM #__comment AS a" . (count( $where ) ? "\nWHERE " . implode( ' AND ', $where ) : "") );
	$total = $database->loadResult();
	echo $database->getErrorMsg();
	include_once( "includes/pageNavigation.php" );
	$pageNav = new mosPageNav( $total, $limitstart, $limit  );
	$database->setQuery( "SELECT c.title, a.* FROM #__comment as a"
		. "\n LEFT JOIN #__content AS c ON a.articleid = c.id"
		. (count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "")
		. "\n ORDER BY a.id DESC"
		. "\n LIMIT $pageNav->limitstart,$pageNav->limit"
	);
	$rows = $database->loadObjectList();
	if ($database->getErrorNum()) {
		echo $database->stderr();
		return false;
	}
	HTML_comment::showComments( $option, $rows, $search, $pageNav );
}

/**
 * @param option
 * @param id
 * @return edit box for article or new comment box
 */
function editComment( $option, $uid ) {
	global $database, $my;
	$row = new moscomment( $database );
	$row->load( $uid );
	$contentitem[] = mosHTML::makeOption( '0', 'Select Content Item' );
	$database->setQuery( "SELECT id AS value, title AS text FROM #__content ORDER BY title" );
	$contentitem = array_merge( $contentitem, $database->loadObjectList() );
	if (count( $contentitem ) < 1) {
		mosRedirect( "index2.php?option=com_sections&scope=content", 'You must add sections first.' );
	}
	$clist = mosHTML::selectList( $contentitem, 'articleid', 'class="inputbox" size="1"', 'value', 'text', intval( $row->articleid ) );
	if ($uid) {
		$row->checkout( $my->id );
	} else {
		$row->published = 0;
	}
	$publist = mosHTML::yesnoRadioList( 'published', 'class="inputbox"', $row->published );
	HTML_comment::editComment( $option, $row, $clist, $publist );
}

/**
 * @param option
 * @return saves comment
 */
function saveComment( $option ) {
	global $database;
	$row = new moscomment( $database );
	if (!$row->bind( $_POST )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	$row->startdate = date( "Y-m-d H:i:s" );
	$row->ip   = getenv('REMOTE_ADDR');
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	$row->updateOrder( "articleid='$row->articleid'" );
	mosRedirect( "index2.php?option=$option" );
}


/**
 * @param cid
 * @param publish
 * @param option
 * @return publishes / unpublishes article comment
 */
function publishComments( $cid=null, $publish=1,  $option ) {
  global $database;
  if (!is_array( $cid ) || count( $cid ) < 1) {
    $action = $publish ? 'publish' : 'unpublish';
    echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
    exit;
  }
  $cids = implode( ',', $cid );
  $database->setQuery( "UPDATE #__comment SET published='$publish' WHERE id IN ($cids)" );
  if (!$database->query()) {
    echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
    exit();
  }
  mosRedirect( "index2.php?option=$option" );
}

/**
 * @param option
 * @return builds admin configuration options
 */
function showConfig( $option ) {
	global $mosConfig_absolute_path, $database, $mosConfig_mailfrom;
	require($mosConfig_absolute_path."/administrator/components/com_comment/config.comment.php");
	?>
	<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		submitform( pressbutton );
		}
	</script>
  <form action="index2.php" method="POST" name="adminForm">
  <?php
  $gbtabs = new mosTabs( 0 );
  $gbtabs->startPane( "_comment" );
  $gbtabs->startTab("General","General-page");
  ?>
  <table width="100%" border="0" cellpadding="4" cellspacing="2" class="adminForm">
    <tr align="center" valign="middle">
      <td align="left" valign="top"><strong><?php echo T_('Sections available'); ?>:</strong></td>
      <td align="left" valign="top"><select size="5" name="mcselections[]" class="inputbox" multiple="multiple">
      <?php
        $seclistarray = explode (",", $allow_comments_in_sections);
        $database -> setQuery("SELECT id,title FROM #__sections ORDER BY title ASC");
        $dbsectionlist = $database -> loadObjectList();
				echo "<option value='0' ";
				if (in_array (0, $seclistarray)) echo "selected";
				echo ">Static Content</option>";
        foreach ($dbsectionlist as $slrow){
          echo "<option value='$slrow->id' ";
          if (in_array ($slrow->id, $seclistarray)) echo "selected";
          echo ">$slrow->title</option>";
        }
      ?>
        </select>
      </td>
      <td width="50%" align="left" valign="top">Choose which section(s) should
        use the comment system. Hold down [CTRL] to make multiple selections.</td>
    </tr>
    <tr align="center" valign="middle">
      <td align="left" valign="top"><strong><?php echo T_('Autopublish Comments') ?>:</strong></td>
      <td align="left" valign="top">
      <?php echo mosHTML::yesnoRadioList( 'auto_publish_comments', 'class="inputbox"', $auto_publish_comments ); ?>
      </td>
      <td align="left" valign="top"><?php echo T_('Automatically publish new comments') ?></td>
    </tr>
    <tr align="center" valign="middle">
      <td align="left" valign="top"><strong><?php echo T_('Anonymous Comments') ?>:</strong></td>
      <td align="left" valign="top">
      <?php echo mosHTML::yesnoRadioList( 'allow_anonymous_entries', 'class="inputbox"', $allow_anonymous_entries ); ?>
      </td>
      <td align="left" valign="top"><?php echo T_('Allow unregistered users to post comments') ?></td>
    </tr>
	 <tr align="center" valign="middle">
      <td align="left" valign="top"><strong><?php echo T_('Comments Per Page'); ?>:</strong></td>
      <td align="left" valign="top">
      <?php
			$pp = array(
			mosHTML::makeOption(5,5),
			mosHTML::makeOption(10,10),
			mosHTML::makeOption(15,15),
			mosHTML::makeOption(20,20),
			mosHTML::makeOption(25,25),
			mosHTML::makeOption(30,30),
			mosHTML::makeOption(50,50),
			);
			echo mosHTML::selectList( $pp, 'comments_per_page',	'class="inputbox" size="1"', 'value', 'text', $comments_per_page);
      ?>
      </td>
      <td align="left" valign="top"><?php echo T_('When comments exceed the set level the page will automatically paginate') ?></td>
    </tr>
  </table>
    <?php
    $gbtabs->endTab();
    $gbtabs->startTab("Notification","Notification-page");
	?>
	<table width="100%" border="0" cellpadding="4" cellspacing="2" class="adminForm">
    <tr align="center" valign="middle">
      <td align="left" valign="top"><strong><?php echo T_('Notify Admin'); ?>:</strong></td>
      <td align="left" valign="top">
      <?php
        echo mosHTML::yesnoRadioList( 'notify_new_entries', 'class="inputbox"', $notify_new_entries );
      ?>
      </td>
      <td align="left" valign="top" width="50%"><?php echo T_('Notify the administrator by email
        upon new comments'); ?></td>
    </tr>
    <tr align="center" valign="middle">
      <td align="left" valign="top"><strong><?php echo T_('Administrator Email'); ?>:</strong></td>
      <td align="left" valign="top"><?php echo $mosConfig_mailfrom; ?></td>
      <td align="left" valign="top"><?php echo T_('set in Global Configuration / Mail'); ?></td>
    </tr>
  </table>
  <?php
  $gbtabs->endTab();
  $gbtabs->startTab("Admin","Admin-page");
	?>
	<table width="100%" border="0" cellpadding="4" cellspacing="2" class="adminForm">
		<tr align="center" valign="middle">
			<td align="left" valign="top"><strong><?php echo T_('Comment Length'); ?>:</strong></td>
			<td align="left" valign="top">
			<input name="admin_comments_length" type="text" size="5" value="<?php echo $admin_comments_length; ?>" />
			</td>
			<td align="left" valign="top" width="50%"><?php echo T_('The length of comment to show
			in the admin screen before it is truncated.'); ?></td>
		</tr>
	</table>
  <?php
  $gbtabs->endTab();
  $gbtabs->endPane();
  ?>
  <input type="hidden" name="option" value="<?php echo $option; ?>">
  <input type="hidden" name="task" value="">
  <input type="hidden" name="boxchecked" value="0">
	</form>
	<?php
}

/**
 * @param option
 * @param auto_publish_comments
 * @param allow_anonymous_entries
 * @param notify_new_entries
 * @param allow_comments_in_sections
 * @param comments_per_page
 * @param admin_comments_length
 * @return saves configuration file
 */
function saveConfig ($option, $auto_publish_comments, $allow_anonymous_entries, $notify_new_entries, $allow_comments_in_sections, $comments_per_page, $admin_comments_length) {
	$configfile = "components/com_comment/config.comment.php";
	@chmod ($configfile, 0766);
	$permission = is_writable($configfile);
	if (!$permission) {
		$mosmsg = "Config file not writeable!";
		mosRedirect("index2.php?option=$option&act=config",$mosmsg);
		break;
	}
	$config  = "<?php\n";
	$config .= "\$auto_publish_comments = \"$auto_publish_comments\";\n";
	$config .= "\$allow_anonymous_entries = \"$allow_anonymous_entries\";\n";
	$config .= "\$notify_new_entries = \"$notify_new_entries\";\n";
	$config .= "\$allow_comments_in_sections = \"$allow_comments_in_sections\";\n";
	$config .= "\$comments_per_page = \"$comments_per_page\";\n";
	$config .= "\$admin_comments_length = \"$admin_comments_length\";\n";
	$config .= "?>";
	if ($fp = fopen("$configfile", "w")) {
		fputs($fp, $config, strlen($config));
		fclose ($fp);
	}
	mosRedirect("index2.php?option=$option&task=settings", "Settings saved");
}

/**
 * @param cid
 * @param option
 * @return deletes selected article
 */
function removeComments( $cid, $option ) {
	global $database;
	if (count( $cid )) {
		$cids = implode( ',', $cid );
		$database->setQuery( "DELETE FROM #__comment WHERE id IN ($cids)" );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
	}
	mosRedirect( "index2.php?option=$option" );
}