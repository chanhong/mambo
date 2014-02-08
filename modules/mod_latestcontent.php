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

global $mosConfig_offset, $mosConfig_live_site, $mainframe, $acl, $my;
require_once(mamboCore::get('mosConfig_absolute_path').'/components/com_content/content.html.php');

$type 		= intval( $params->get( 'type', 1 ) );
$count 		= intval( $params->get( 'count', 5 ) );
$catid 		= trim( $params->get( 'catid' ) );
$secid 		= trim( $params->get( 'secid' ) );
$show_front	= $params->get( 'show_front', 1 );
$class_sfx	= $params->get( 'moduleclass_sfx' );
$style 		= $params->get( 'style' );
$readmore 	= $params->get( 'readmore',0 );
$image 		= $params->get( 'image' );

$params->set( 'intro_only', 1 );
$params->set( 'hide_author', 1 );
$params->set( 'hide_createdate', 0 );
$params->set( 'hide_modifydate', 1 );


$now 		= date( 'Y-m-d H:i:s', time() + $mosConfig_offset * 60 * 60 );

//$access = !$mainframe->getCfg( 'shownoauth' );
// Disable edit ability icon
$access = new stdClass();
$access->canEdit 	= 0;
$access->canEditOwn = 0;
$access->canPublish = 0;


$viewAccess = ($my->gid >= $acl->get_group_id( 'Registered', 'ARO' ) ? 1 : 0) + ($my->gid >= $acl->get_group_id( 'Author', 'ARO' ) ? 1 : 0);

// select between Content Items, Static Content or both
switch ( $type ) {
	case 2: //Static Content only
		$query = "SELECT a.id, a.title"
		. "\n FROM #__content AS a"
		. "\n WHERE ( a.state = '1' AND a.checked_out = '0' AND a.sectionid = '0' )"
		. "\n AND ( a.publish_up = '0000-00-00 00:00:00' OR a.publish_up <= '". $now ."' )"
		. "\n AND ( a.publish_down = '0000-00-00 00:00:00' OR a.publish_down >= '". $now ."' )"
    	. ( $access ? "\n AND a.access <= '". $viewAccess ."'" : '' )
		. "\n ORDER BY a.created DESC LIMIT $count"
		;
		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		break;

	case 3: //Both
		$query = "SELECT a.id, a.title, a.sectionid"
		. "\n FROM #__content AS a"
		. "\n WHERE ( a.state = '1' AND a.checked_out = '0' )"
		. "\n AND ( a.publish_up = '0000-00-00 00:00:00' OR a.publish_up <= '". $now ."' )"
		. "\n AND ( a.publish_down = '0000-00-00 00:00:00' OR a.publish_down >= '". $now ."' )"
    	. ( $access ? "\n AND a.access <= '". $viewAccess ."'" : '' )
		. "\n ORDER BY a.created DESC LIMIT $count"
		;
		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		break;

	case 1:  //Content Items only
	default:
		$query = "SELECT a.id, a.title, a.sectionid, a.catid"
		. "\n FROM #__content AS a"
		. "\n LEFT JOIN #__content_frontpage AS f ON f.content_id = a.id"
		. "\n WHERE ( a.state = '1' AND a.checked_out = '0' AND a.sectionid > '0' )"
		. "\n AND ( a.publish_up = '0000-00-00 00:00:00' OR a.publish_up <= '". $now ."' )"
		. "\n AND ( a.publish_down = '0000-00-00 00:00:00' OR a.publish_down >= '". $now ."' )"
    	. ( $access ? "\n AND a.access <= '". $viewAccess ."'" : '' ) // original code
		. ( $catid ? "\n AND ( a.catid IN (". $catid .") )" : '' )
		. ( $secid ? "\n AND ( a.sectionid IN (". $secid .") )" : '' )
		. ( $show_front == "0" ? "\n AND f.content_id IS NULL" : '' )
		. "\n ORDER BY a.created DESC LIMIT $count"
		;
		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		break;
}

// needed to reduce queries used by getItemid for Content Items
if ( ( $type == 1 ) || ( $type == 3 ) ) {
	require_once(mamboCore::get('mosConfig_absolute_path').'/components/com_content/content.class.php');
	$handler =& new contentHandler();
	$bs 	= $handler->getBlogSectionCount();
	$bc 	= $handler->getBlogCategoryCount();
	$gbs 	= $handler->getGlobalBlogSectionCount();
}

// Output

$newrow = new mosContent( $database );

if ($rows) {

	switch ( $style ) {
		case 'vert':
			echo "\n<table class=\"moduletable" . $class_sfx . "\">\n";


			foreach ( $rows as $row ) {
				// get Itemid
				switch ( $type ) {
					case 2://Static Content only
						$query = "SELECT id"
						. "\n FROM #__menu"
						. "\n WHERE type = 'content_typed'"
						. "\n AND componentid = $row->id"
						;
						$database->setQuery( $query );
						$Itemid = $database->loadResult();
						break;

					case 3://Both
						if ( $row->sectionid ) {
							$Itemid = $mainframe->getItemid( $row->id, 0, 0, $bs, $bc, $gbs );
						} else {
							$query = "SELECT id"
							. "\n FROM #__menu"
							. "\n WHERE type = 'content_typed'"
							. "\n AND componentid = $row->id"
							;
							$database->setQuery( $query );
							$Itemid = $database->loadResult();
						}
						break;

					case 1://Content Items only
					default:
						//$Itemid = $mainframe->getItemid( $row->id, 0, 0, $bs, $bc, $gbs );
						$query = "SELECT id"
							. "\n FROM #__menu"
							. "\n WHERE "
							. "\n link LIKE '%task=section&id=".$row->sectionid."%'"
							. "\n OR link LIKE '%sectionid=".$row->sectionid."%'"
							;
							$database->setQuery( $query );
							$Itemid = $database->loadResult();
						break;
				}

				// Blank itemid checker for SEF
				/*
				if ($Itemid == NULL) {
					$Itemid = '';
				} else {
					$Itemid = '&amp;Itemid='. $Itemid;
				}
				*/

				$link = sefRelToAbs( 'index.php?option=com_content&amp;task=view&amp;id='. $row->id . $Itemid);
				echo "<tr>\n<td valign=top>\n";
				//echo '<a href="'.$link.'">'.$row->title.'</a>'."\n";
				$newrow->load( $row->id );
				$newrow->text = $newrow->introtext;
				$newrow->groups = '';
				$ItemidCount = array('bc'=>$bc, 'bs'=>$bs, 'gbs'=>$gbs);
				HTML_content::show( $newrow, $params, $access, 0, 'com_content',$ItemidCount);
				//print_r($ItemidCount);
				//echo '<a href="'.$link.'">Read More..</a>'."\n";
				echo "<td>\n<tr>\n";
			}
			echo "</table>\n";
		break;


		case 'horiz':
		default:

			echo "\n<table class=\"moduletable" . $class_sfx . "\">\n";
			echo "<tr>\n";
			foreach ( $rows as $row ) {
				// get Itemid
				switch ( $type ) {
					case 2://Static Content only
						$query = "SELECT id"
						. "\n FROM #__menu"
						. "\n WHERE type = 'content_typed'"
						. "\n AND componentid = $row->id"
						;
						$database->setQuery( $query );
						$Itemid = $database->loadResult();
						break;

					case 3://Both
						if ( $row->sectionid ) {
							$Itemid = $mainframe->getItemid( $row->id, 0, 0, $bs, $bc, $gbs );
						} else {
							$query = "SELECT id"
							. "\n FROM #__menu"
							. "\n WHERE type = 'content_typed'"
							. "\n AND componentid = $row->id"
							;
							$database->setQuery( $query );
							$Itemid = $database->loadResult();
						}
						break;

					case 1://Content Items only
					default:
						//$Itemid = $mainframe->getItemid( $row->id, 0, 0, $bs, $bc, $gbs );
						$query = "SELECT id"
							. "\n FROM #__menu"
							. "\n WHERE "
							. "\n link LIKE '%task=section&id=".$row->sectionid."%'"
							. "\n OR link LIKE '%sectionid=".$row->sectionid."%'"
							;
							$database->setQuery( $query );
							$Itemid = $database->loadResult();
						break;
				}

				// Blank itemid checker for SEF
				/*
				if ($Itemid == NULL) {
					$Itemid = '';
				} else {
					$Itemid = '&amp;Itemid='. $Itemid;
				}
				*/

				$link = sefRelToAbs( 'index.php?option=com_content&amp;task=view&amp;id='. $row->id . $Itemid);
				echo "<td valign=top>\n";
				//echo '<a href="'.$link.'">'.$row->title.'</a>'."\n";
				$newrow->load( $row->id );
				$newrow->text = $newrow->introtext;
				$newrow->groups = '';
				$ItemidCount = array('bc'=>$bc, 'bs'=>$bs, 'gbs'=>$gbs);
				//$params->set( 'readmore', 0 );
				HTML_content::show( $newrow, $params, $access, 0, 'com_content', $ItemidCount);
				//print_r($ItemidCount);
				//echo '<a class="readon" href="'.$link.'">Read More..</a>'."\n";
				echo "</td>\n";
			}
			echo "</tr>\n</table>\n";
		break;
	}

}
?>