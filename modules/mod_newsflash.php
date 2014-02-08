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

require_once( $mainframe->getPath( 'front_html', 'com_content') );
require_once($mosConfig_absolute_path.'/components/com_content/content.class.php');

global $my, $mosConfig_shownoauth, $mosConfig_offset, $acl;

// Disable edit ability icon
$access = new stdClass();
$access->canEdit 	= 0;
$access->canEditOwn = 0;
$access->canPublish = 0;

$now = date( 'Y-m-d H:i:s', time()+$mosConfig_offset*60*60 );

$catid 				= intval( $params->get( 'catid' ) );
$style 				= $params->get( 'style' );
$image 				= $params->get( 'image' );
$readmore 			= $params->get( 'readmore' );
$items 				= intval( $params->get( 'items' ) );
$moduleclass_sfx 	= $params->get( 'moduleclass_sfx' );

$params->set( 'intro_only', 1 );
$params->set( 'hide_author', 1 );
$params->set( 'hide_createdate', 0 );
$params->set( 'hide_modifydate', 1 );

if ( $items ) {
	$limit = "LIMIT ". $items;
} else {
	$limit = "";
}

$noauth = !$mainframe->getCfg( 'shownoauth' );

// query to determine article count
$query = "SELECT a.id"
."\n FROM #__content AS a"
."\n INNER JOIN #__categories AS b ON b.id = a.catid"
."\n WHERE a.state = 1"
. ( $noauth ? "\n AND a.access <= '". $my->gid ."' AND b.access <= '". $my->gid ."'" : '' )
."\n AND (a.publish_up = '0000-00-00 00:00:00' OR a.publish_up <= '". $now ."') "
."\n AND (a.publish_down = '0000-00-00 00:00:00' OR a.publish_down >= '". $now ."')"
."\n AND catid='". $catid ."' "
."\n ORDER BY a.ordering"
."\n ". $limit
;
$database->setQuery( $query );
$rows = $database->loadResultArray();

$row = new mosContent( $database );
if (!count($rows)) {
	return;
}
$numrows = count( $rows );

switch ($style) {
	case 'horiz':
		echo "\n<table class=\"moduletable" . $moduleclass_sfx . "\">\n";
		echo "<tr>\n";
		foreach ($rows as $id) {
			$row->load( $id );
			$row->text = $row->introtext;
			if ($image == 0) $row->text = preg_replace('/\<img[^>]+\>/i', '', $row->text); 
			$row->groups = '';
			echo '<td>';
			HTML_content::show( $row, $params, $access, 0, 'com_content' );
			echo "</td>\n";
		}
		echo "</tr>\n</table>\n";
	break;
	
	case 'vert':
		foreach ($rows as $id) {
			$row->load( $id );
			$row->text = $row->introtext;
			if ($image == 0) $row->text = preg_replace('/\<img[^>]+\>/i', '', $row->text); 
			$row->groups = '';
	
			HTML_content::show( $row, $params, $access, 0, 'com_content' );
		}
	break;
	
	case 'flash':
		default:
		if ($numrows > 0) {
			srand ((double) microtime() * 1000000);
			$flashnum = $rows[rand( 0, $numrows-1 )];
		} else {
			$flashnum = 0;
		}
		$row->load( $flashnum );
		$row->text = $row->introtext;
		if ($image == 0) $row->text = preg_replace('/\<img[^>]+\>/i', '', $row->text); 
		$row->groups = '';
	
		HTML_content::show( $row, $params, $access, 0, 'com_content' );
		break;
}
?>
