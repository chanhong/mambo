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

$query = "SELECT a.id, a.sectionid, a.title, a.created, u.name, a.created_by_alias, a.created_by"
. "\n FROM #__content AS a"
. "\n LEFT JOIN #__users AS u ON u.id=a.created_by"
. "\n WHERE a.state <> '-2'"
. "\n ORDER BY created DESC"
. "\n LIMIT 10"
;
$database->setQuery( $query );
$rows = $database->loadObjectList();
?>

<table class="adminlist">
<tr>
    <th colspan="3">
	<?php echo T_('Most Recently Added Content'); ?>
	</th>
</tr>
<?php

if ($rows) {
	foreach ($rows as $row) {
		if ( $row->sectionid == 0 ) {
			$link = 'index2.php?option=com_typedcontent&amp;task=edit&amp;hidemainmenu=1&amp;id='. $row->id;
		} else {
			$link = 'index2.php?option=com_content&amp;task=edit&amp;hidemainmenu=1&amp;id='. $row->id;
		}

		if ( $acl->acl_check( 'administration', 'manage', 'users', $my->usertype, 'components', 'com_users' ) ) {
			if ( $row->created_by_alias ) {
				$author = $row->created_by_alias;
			} else {
				$linkA 	= 'index2.php?option=com_users&amp;task=editA&amp;hidemainmenu=1&amp;id='. $row->created_by;
				$author = '<a href="'. $linkA .'" title="'.T_('Edit User') .'">'. htmlspecialchars( $row->name, ENT_QUOTES ) .'</a>';
			}
		} else {
			if ( $row->created_by_alias ) {
				$author = $row->created_by_alias;
			} else {
				$author = htmlspecialchars( $row->name, ENT_QUOTES );
			}
		}
		?>
		<tr>
			<td>
			<a href="<?php echo $link; ?>">
			<?php echo htmlspecialchars($row->title, ENT_QUOTES);?>
			</a>
			</td>
			<td>
			<?php echo $row->created;?>
			</td>
			<td>
			<?php echo $author;?>
			</td>
		</tr>
		<?php
	}	
} else {
	?>
	<tr>
		<td colspan="3">Nothing to show</td>
	</tr>
	<?php
}
?>
</table>