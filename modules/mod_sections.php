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

global $mosConfig_offset, $Itemid;

//** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

$count = intval( $params->get( 'count', 20 ) );
$access = !$mainframe->getCfg( 'shownoauth' );
$now = date( 'Y-m-d H:i:s', time() + $mosConfig_offset * 60 * 60 );

$database->setQuery(
"SELECT a.id AS id, a.title AS title, COUNT(b.id) as cnt"
. "\n FROM #__sections as a"
. "\n LEFT JOIN #__content as b"
. "\n ON a.id=b.sectionid"
. ($access ? "\n AND b.access<='$my->gid'" : "" )
. "\n AND (b.publish_up = '0000-00-00 00:00:00' OR b.publish_up <= '". $now ."' )"
. "\n AND (b.publish_down = '0000-00-00 00:00:00' OR b.publish_down >= '". $now ."' )"
. "\n WHERE a.scope='content'"
. "\n AND a.published='1'"
. ($access ? "\n AND a.access<='$my->gid'" : "" )
. "\n GROUP BY a.id"
. "\n HAVING COUNT(b.id)>0"
. "\n ORDER BY a.ordering"
. "\n LIMIT $count"
);

$rows = $database->loadObjectList();
echo "<ul>\n";
if ($rows) {
	foreach ($rows as $row) {
		echo "  <li><a href=\"" . sefRelToAbs("index.php?option=com_content&task=blogsection&id=".$row->id) . ($Itemid ? "&Itemid=$Itemid" : '') . "\">" . $row->title . "</a></li>\n";
	}
	echo "</ul>\n";
}
?>