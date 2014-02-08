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

global $mosConfig_list_limit;

require_once( $mosConfig_absolute_path .'/administrator/includes/pageNavigation.php' );

$limit 			= $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit );
$limitstart 	= $mainframe->getUserStateFromRequest( "view{$option}", 'limitstart', 0 );

// hides Administrator or Super Administrator from list depending on usertype
$_and = '';
if ( $my->gid == 24 ) {
	$_and = "\n AND userid != '25'";
}
if ( $my->gid == 23 ) {
	$_and = "\n AND userid != '25'";
	$_and .= "\n AND userid != '24'";
}

// get the total number of records
$query = "SELECT COUNT(*)"
. "\n FROM #__session"
. "\n WHERE userid != 0"
. $_and
. "\n ORDER BY usertype, username"
;
$database->setQuery( $query );
$total = $database->loadResult();

// page navigation
$pageNav = new mosPageNav( $total, $limitstart, $limit );

$query = "SELECT *"
. "\n FROM #__session"
. "\n WHERE userid != 0"
. $_and
. "\n ORDER BY usertype, username"
. "\n LIMIT $pageNav->limitstart, $pageNav->limit"
;
$database->setQuery( $query );
$rows = $database->loadObjectList();
?>
<table class="adminlist">
<tr>
    <th colspan="4">
	<?php echo T_('Currently Logged in Users') ?>
	</th>
</tr>
<?php
$i = 0;
foreach ( $rows as $row ) {
	if ( $acl->acl_check( 'administration', 'manage', 'users', $my->usertype, 'components', 'com_users' ) ) {
		$link 	= 'index2.php?option=com_users&amp;task=editA&amp;hidemainmenu=1&amp;id='. $row->userid;
		$name 	= '<a href="'. $link .'" title="'._('Edit User').'">'. $row->username .'</a>';
	} else {
		$name 	= $row->username;
	}
	?>
	<tr>
		<td width="5%">
		<?php echo $pageNav->rowNumber( $i ); ?>
		</td>
		<td>
		<?php echo $name;?>
		</td>
		<td>
		<?php echo $row->usertype;?>
		</td>
		<?php
		if ( $acl->acl_check( 'administration', 'manage', 'users', $my->usertype, 'components', 'com_users' ) ) {
			?>
			<td>
			<a href="index2.php?option=com_users&amp;task=flogout&amp;id=<?php echo $row->userid; ?>">
			<img src="images/publish_x.png" width="12" height="12" border="0" alt="<?php echo T_('Logout') ?>" Title="<?php echo T_('Force Logout User') ?>" />
			</a>		
			</td>
			<?php
		}
		?>
	</tr>
	<?php
	$i++;
}
?>
</table>
<?php echo $pageNav->getListFooter(); ?>
<input type="hidden" name="option" value="" />
