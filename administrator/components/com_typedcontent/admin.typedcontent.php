<?php
/**
* @package Mambo
* @subpackage Content
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

require_once( $mainframe->getPath( 'admin_html' ) );
require_once($mosConfig_absolute_path.'/components/com_content/content.class.php');

$id 	= mosGetParam( $_REQUEST, 'id', '' );
$cid 	= mosGetParam( $_POST, 'cid', array(0) );
if (!is_array( $cid )) {
	$cid = array(0);
}


switch ( $task ) {
	case 'cancel':
		cancel( $option );
		break;

	case 'new':
		edit( 0, $option );
		break;

	case 'edit':
		edit( $id, $option );
		break;

	case 'editA':
		edit( $cid[0], $option );
		break;

	case 'go2menu':
	case 'go2menuitem':
	case 'resethits':
	case 'menulink':
	case 'save':
	case 'apply':
		save( $option, $task );
		break;

	case 'remove':
		trash( $cid, $option );
		break;

	case 'publish':
		changeState( $cid, 1, $option );
		break;

	case 'unpublish':
		changeState( $cid, 0, $option );
		break;

	case 'accesspublic':
		changeAccess( $cid[0], 0, $option );
		break;

	case 'accessregistered':
		changeAccess( $cid[0], 1, $option );
		break;

	case 'accessspecial':
		changeAccess( $cid[0], 2, $option );
		break;

	case 'saveorder':
		saveOrder( $cid );
		break;

	case 'toggle_frontpage':
		toggleFrontPage( $cid, $option );
		break;
		
	default:
		view( $option );
		break;
}

/**
* Compiles a list of installed or defined modules
* @param database A database connector object
*/
function view( $option ) {
	global $database, $mainframe, $mosConfig_list_limit;

	$filter_authorid 	= $mainframe->getUserStateFromRequest( "filter_authorid{$option}", 'filter_authorid', 0 );
	$order 				= $mainframe->getUserStateFromRequest( "zorder", 'zorder', 'c.ordering DESC' );
	$limit 				= $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit );
	$limitstart 		= $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 );
	$search 			= $mainframe->getUserStateFromRequest( "search{$option}", 'search', '' );
	$search 			= $database->getEscaped( trim( strtolower( $search ) ) );

	// used by filter
	if ( $search ) {
		$search_query = "\n AND ( LOWER( c.title ) LIKE '%$search%' OR LOWER( c.title_alias ) LIKE '%$search%' )";
	} else {
		$search_query = '';
	}

	$filter = '';
	if ( $filter_authorid > 0 ) {
		$filter = "\n AND c.created_by = '$filter_authorid'";
	}

	// get the total number of records
	$query = "SELECT count(*)"
	. "\n FROM #__content AS c"
	. "\n WHERE c.sectionid = '0'"
	. "\n AND c.catid = '0'"
	. "\n AND c.state <> '-2'"
	. $filter
	;
	$database->setQuery( $query );
	$total = $database->loadResult();
	require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit );

	$query = "SELECT c.*, g.name AS groupname, u.name AS editor, z.name AS creator, f.content_id AS frontpage"
	. "\n FROM #__content AS c"
	. "\n LEFT JOIN #__groups AS g ON g.id = c.access"
	. "\n LEFT JOIN #__users AS u ON u.id = c.checked_out"
	. "\n LEFT JOIN #__users AS z ON z.id = c.created_by"
	. "\n LEFT JOIN #__content_frontpage AS f ON f.content_id = c.id"
	. "\n WHERE c.sectionid = '0'"
	. "\n AND c.catid = '0'"
	. "\n AND c.state <> '-2'"
	. $search_query
	. $filter
	. "\n ORDER BY ". $order
	. "\n LIMIT $pageNav->limitstart,$pageNav->limit"
	;
	$database->setQuery( $query );
	$rows = $database->loadObjectList();

	if ($database->getErrorNum()) {
		echo $database->stderr();
		return false;
	}

	$count = count( $rows );
	for( $i = 0; $i < $count; $i++ ) {
		$query = "SELECT COUNT( id )"
		. "\n FROM #__menu"
		. "\n WHERE componentid = ". $rows[$i]->id
		. "\n AND type = 'content_typed'"
		. "\n AND published <> '-2'"
		;
		$database->setQuery( $query );
		$rows[$i]->links = $database->loadResult();
	}

	$ordering[] = mosHTML::makeOption( 'c.ordering ASC', T_('Ordering asc') );
	$ordering[] = mosHTML::makeOption( 'c.ordering DESC', T_('Ordering desc') );
	$ordering[] = mosHTML::makeOption( 'c.id ASC', T_('ID asc') );
	$ordering[] = mosHTML::makeOption( 'c.id DESC', T_('ID desc') );
	$ordering[] = mosHTML::makeOption( 'c.title ASC', T_('Title asc') );
	$ordering[] = mosHTML::makeOption( 'c.title DESC', T_('Title desc') );
	$ordering[] = mosHTML::makeOption( 'c.created ASC', T_('Date asc') );
	$ordering[] = mosHTML::makeOption( 'c.created DESC', T_('Date desc') );
	$ordering[] = mosHTML::makeOption( 'z.name ASC', T_('Author asc') );
	$ordering[] = mosHTML::makeOption( 'z.name DESC', T_('Author desc') );
	$ordering[] = mosHTML::makeOption( 'c.state ASC', T_('Published asc') );
	$ordering[] = mosHTML::makeOption( 'c.state DESC', T_('Published desc') );
	$ordering[] = mosHTML::makeOption( 'c.access ASC', T_('Access asc') );
	$ordering[] = mosHTML::makeOption( 'c.access DESC', T_('Access desc') );
	$javascript = 'onchange="document.adminForm.submit();"';
	$lists['order'] = mosHTML::selectList( $ordering, 'zorder', 'class="inputbox" size="1"'. $javascript, 'value', 'text', $order );

	// get list of Authors for dropdown filter
	$query = "SELECT c.created_by AS value, u.name AS text"
	. "\n FROM #__content AS c"
	. "\n LEFT JOIN #__users AS u ON u.id = c.created_by"
	. "\n WHERE c.sectionid = 0"
	. "\n GROUP BY u.name"
	. "\n ORDER BY u.name"
	;
	$authors[] = mosHTML::makeOption( '0', T_('- All Authors -') );
	$database->setQuery( $query );
	$_dbAuthors = $database->loadObjectList();
	if (is_array($_dbAuthors)){
        $authors = array_merge( $authors, $_dbAuthors );
    }
	$lists['authorid']	= mosHTML::selectList( $authors, 'filter_authorid', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', $filter_authorid );

	HTML_typedcontent::showContent( $rows, $pageNav, $option, $search, $lists );
}

/**
* Compiles information to add or edit content
* @param database A database connector object
* @param string The name of the category section
* @param integer The unique id of the category to edit (0 if new)
*/
function edit( $uid, $option ) {
	global $database, $my, $mainframe;
	global $mosConfig_absolute_path, $mosConfig_live_site;

	$row = new mosContent( $database );

	// fail if checked out not by 'me'
	if ($row->checked_out && $row->checked_out <> $my->id) {
		echo "<script>alert('".sprintf(T_('The module % is currently being edited by another administrator'), $row->title)."'); document.location.href='index2.php?option=$option'</script>\n";
		exit(0);
	}

	$lists = array();

	if ($uid) {
		// load the row from the db table
		$row->load( $uid );
		$row->checkout( $my->id );
		if (trim( $row->images )) {
			$row->images = explode( "\n", $row->images );
		} else {
			$row->images = array();
		}
		if (trim( $row->publish_down ) == "0000-00-00 00:00:00") {
			$row->publish_down = "Never";
		}

		$query = "SELECT name from #__users"
		. "\n WHERE id=$row->created_by"
		;
		$database->setQuery( $query );
		$row->creator = $database->loadResult();

		$query = "SELECT name from #__users"
		. "\n WHERE id=$row->modified_by"
		;
		$database->setQuery( $query );
		$row->modifier = $database->loadResult();
		
		$query = "SELECT content_id from #__content_frontpage"
		. "\n WHERE content_id=$row->id"
		;
		$database->setQuery( $query );
		$row->frontpage = $database->loadResult();

		// get list of links to this item
		$_and 	= "\n AND componentid = ". $row->id;
		$menus 	= mosAdminMenus::Links2Menu( 'content_typed', $_and );
	} else {
		// initialise values for a new item
		$row->version = 0;
		$row->state = 1;
		$row->images = array();
		$row->publish_up = date( "Y-m-d", time() );
		$row->publish_down = "Never";
		$row->sectionid = 0;
		$row->catid = 0;
		$row->creator = '';
		$row->modifier = '';
		$row->ordering = 0;		
		$row->frontpage = 0;
		$menus = array();
	}

	// calls function to read image from directory
	$pathA 	= $mosConfig_absolute_path .'/images/stories';
	$pathL 		= $mosConfig_live_site .'/images/stories';
	$images 	= array();
	$folders 	= array();
	$folders[] 	= mosHTML::makeOption( '/' );
	mosAdminMenus::ReadImages( $pathA, '/', $folders, $images );
	// list of folders in images/stories/
	$lists['folders'] 		= mosAdminMenus::GetImageFolders( $folders, $pathL );
	// list of images in specfic folder in images/stories/
	$lists['imagefiles']	= mosAdminMenus::GetImages( $images, $pathL );
	// list of saved images
	$lists['imagelist'] 	= mosAdminMenus::GetSavedImages( $row, $pathL );

	// build list of users
	$active = ( intval( $row->created_by ) ? intval( $row->created_by ) : $my->id );
	$lists['created_by'] 	= mosAdminMenus::UserSelect( 'created_by', $active );
	// build the html select list for the group access
	$lists['access'] 		= mosAdminMenus::Access( $row );
	// build the html select list for menu selection
	$lists['menuselect']	= mosAdminMenus::MenuSelect( );
	// build the select list for the image positions
	$lists['_align'] 		= mosAdminMenus::Positions( '_align' );
	// build the select list for the image caption alignment
	$lists['_caption_align'] 	= mosAdminMenus::Positions( '_caption_align' );
	// build the select list for the image caption position
	$pos[] = mosHTML::makeOption( 'bottom', T_('Bottom') );
	$pos[] = mosHTML::makeOption( 'top', T_('Top') );
	$lists['_caption_position'] = mosHTML::selectList( $pos, '_caption_position', 'class="inputbox" size="1"', 'value', 'text' );

	// get params definitions
	$params =& new mosAdminParameters( $row->attribs, $mainframe->getPath( 'com_xml', 'com_typedcontent' ), 'component' );

	HTML_typedcontent::edit( $row, $images, $lists, $params, $option, $menus );
}

/**
* Saves the typed content item
*/
function save( $option, $task ) {
	global $database, $my, $mainframe;

	$menu 		= mosGetParam( $_POST, 'menu', 'mainmenu' );
	$menuid		= mosGetParam( $_POST, 'menuid', 0 );

	$row = new mosContent( $database );
	if (!$row->bind( $_POST )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	if ( $row->id ) {
		$row->modified = date( 'Y-m-d H:i:s' );
		$row->modified_by = $my->id;
	} else {
		$row->created = date( 'Y-m-d H:i:s' );
		$row->created_by = $my->id;
	}
	if (trim( $row->publish_down ) == 'Never') {
		$row->publish_down = '0000-00-00 00:00:00';
	}

	// Save Parameters
	$params = mosGetParam( $_POST, 'params', '' );
	if (is_array( $params )) {
		$txt = array();
		foreach ( $params as $k=>$v) {
			$txt[] = "$k=$v";
		}
		$row->attribs = implode( "\n", $txt );
	}

	// code cleaner for xhtml transitional compliance
	$row->introtext = str_replace( '<br>', '<br />', $row->introtext );

	$row->state = mosGetParam( $_REQUEST, 'published', 0 );

	if (!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	// manage frontpage items
	require_once( $mainframe->getPath( 'class', 'com_frontpage' ) );
	$fp = new mosFrontPage( $database );

	if (mosGetParam( $_REQUEST, 'frontpage', 0 )) {

		// toggles go to first place
		if (!$fp->load( $row->id )) {
			// new entry
			$database->setQuery( "INSERT INTO #__content_frontpage VALUES ('$row->id','1')" );
			if (!$database->query()) {
				echo "<script> alert('".$database->stderr()."');</script>\n";
				exit();
			}
			$fp->ordering = 1;
		}
	} else {
		// no frontpage mask
		if (!$fp->delete( $row->id )) {
			$msg .= $fp->stderr();
		}
		$fp->ordering = 0;
	}
	$fp->updateOrder();

	$row->checkin();

	switch ( $task ) {
		case 'go2menu':
			mosRedirect( 'index2.php?option=com_menus&menutype='. $menu );
			break;

		case 'go2menuitem':
			mosRedirect( 'index2.php?option=com_menus&menutype='. $menu .'&task=edit&hidemainmenu=1&id='. $menuid );
			break;

		case 'menulink':
			menuLink( $option, $row->id );
			break;

		case 'resethits':
			resethits( $option, $row->id );
			break;

		case 'save':
			$msg = T_('Typed Content Item saved');
			mosRedirect( 'index2.php?option='. $option, $msg );
			break;

		case 'apply':
		default:
			$msg = T_('Changes to Typed Content Item saved');
			mosRedirect( 'index2.php?option='. $option .'&task=edit&hidemainmenu=1&id='. $row->id, $msg );
			break;
	}
}

/**
* Trashes the typed content item
*/
function trash( &$cid, $option ) {
	global $database, $mainframe;

	$total = count( $cid );
	if ( $total < 1) {
		echo "<script> alert('".T_('Select an item to delete')."'); window.history.go(-1);</script>\n";
		exit;
	}

	$state = '-2';
	$ordering = '0';
	//seperate contentids
	$cids = implode( ',', $cid );
	$query = 	"UPDATE #__content SET state = '". $state ."', ordering = '". $ordering ."'"
	. "\n WHERE id IN ( ". $cids ." )"
	;
	$database->setQuery( $query );
	if ( !$database->query() ) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	$msg = sprintf(Tn_('%d Item sent to the Trash', '%d Items sent to the Trash', $total), $total) ."";
	mosRedirect( 'index2.php?option='. $option, $msg );
}

/**
* Changes the state of one or more content pages
* @param string The name of the category section
* @param integer A unique category id (passed from an edit form)
* @param array An array of unique category id numbers
* @param integer 0 if unpublishing, 1 if publishing
* @param string The name of the current user
*/
function changeState( $cid=null, $state=0, $option ) {
	global $database, $my;

	if (count( $cid ) < 1) {
		$action = $state == 1 ? T_('publish') : ($state == -1 ? T_('archive') : T_('unpublish'));
		echo "<script> alert('".sprintf(T_('Select an item to %s'), $action)."'); window.history.go(-1);</script>\n";
		exit;
	}

	$total = count ( $cid );
	$cids = implode( ',', $cid );

	$database->setQuery( "UPDATE #__content SET state='$state'"
	. "\nWHERE id IN ($cids) AND (checked_out=0 OR (checked_out='$my->id'))"
	);
	if (!$database->query()) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	if (count( $cid ) == 1) {
		$row = new mosContent( $database );
		$row->checkin( $cid[0] );
	}

	if ( $state == "1" ) {
		$msg = $total ."";
		$msg = sprintf(Tn_('%d Item successfully Published', '%d Items successfully Published', $total), $total);
	} else if ( $state == "0" ) {
	    $msg = sprintf(Tn_('%d Item successfully Unpublished', '%d Items successfully Unpublished', $total), $total);
	}
	mosRedirect( 'index2.php?option='. $option .'&msg='. $msg );
}

/**
* changes the access level of a record
* @param integer The increment to reorder by
*/
function changeAccess( $id, $access, $option  ) {
	global $database;

	$row = new mosContent( $database );
	$row->load( $id );
	$row->access = $access;

	if ( !$row->check() ) {
		return $row->getError();
	}
	if ( !$row->store() ) {
		return $row->getError();
	}

	mosRedirect( 'index2.php?option='. $option );
}


/**
* Function to reset Hit count of a content item
*/
function resethits( $option, $id ) {
	global $database;

	$row = new mosContent($database);
	$row->Load( $id );
	$row->hits = "0";
	$row->store();
	$row->checkin();

	$msg = T_('Successfully Reset Hit');
	mosRedirect( 'index2.php?option='. $option .'&task=edit&hidemainmenu=1&id='. $row->id, $msg );
}

/**
* Cancels an edit operation
* @param database A database connector object
*/
function cancel( $option ) {
	global $database;

	$row = new mosContent( $database );
	$row->bind( $_POST );
	// sanitize
	$row->id = intval($row->id);
	$row->checkin();
	mosRedirect( 'index2.php?option='. $option );
}

function menuLink( $option, $id ) {
	global $database;

	$menu 	= mosGetParam( $_POST, 'menuselect', '' );
	$link 	= mosGetParam( $_POST, 'link_name', '' );

	$row 				= new mosMenu( $database );
	$row->menutype 		= $menu;
	$row->name 			= $link;
	$row->type 			= 'content_typed';
	$row->published		= 1;
	$row->componentid	= $id;
	$row->link			= 'index.php?option=com_content&task=view&id='. $id;
	$row->ordering		= 9999;

	if (!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	$row->checkin();
	$row->updateOrder( "menutype='$row->menutype' AND parent='$row->parent'" );

	$msg =sprintf(T_('%s (Link - Static Content) in menu: %s successfully created'),$link , $menu);
	mosRedirect( 'index2.php?option='. $option .'&task=edit&hidemainmenu=1&id='. $id, $msg );
}

function go2menu() {
	global $database;

	// checkin content
	$row = new mosContent( $database );
	$row->bind( $_POST );
	$row->checkin();

	$menu = mosGetParam( $_POST, 'menu', 'mainmenu' );

	mosRedirect( 'index2.php?option=com_menus&menutype='. $menu );
}

function go2menuitem() {
	global $database;

	// checkin content
	$row = new mosContent( $database );
	$row->bind( $_POST );
	$row->checkin();

	$menu 	= mosGetParam( $_POST, 'menu', 'mainmenu' );
	$id		= mosGetParam( $_POST, 'menuid', 0 );

	mosRedirect( 'index2.php?option=com_menus&menutype='. $menu .'&task=edit&hidemainmenu=1&id='. $id );
}

function saveOrder( &$cid ) {
	global $database;
	$order 		= mosGetParam( $_POST, 'order', array(0) );
	$row		= new mosMenu( $database );
	$categories = array();
    // update ordering values
    foreach ($cid as $i=>$ciditem) {
		$row->load( $ciditem );
		if ($row->ordering != $order[$i]) {
			$row->ordering = $order[$i];
	        if (!$row->store()) {
	            echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
	            exit();
	        }
	        // remember to updateOrder this group
	        $categories[$row->catid] = $row->id;
	    }
	}
	// execute updateOrder for each group
	foreach ($categories as $catid=>$rowid) {
		$row->updateOrder("catid = $catid AND state >= 0");
	} // foreach

	$msg 	= T_('New ordering saved');
	mosRedirect( 'index2.php?option=com_typedcontent', $msg );
} // saveOrder


/**
* Changes the state of one or more content pages
* @param string The name of the category section
* @param integer A unique category id (passed from an edit form)
* @param array An array of unique category id numbers
* @param integer 0 if unpublishing, 1 if publishing
* @param string The name of the current user
*/
function toggleFrontPage( $cid, $option ) {
	global $database, $my, $mainframe;

	if (count( $cid ) < 1) {
		echo "<script> alert('".T_('Select an item to toggle')."'); window.history.go(-1);</script>\n";
		exit;
	}

	$msg = '';
	require_once( $mainframe->getPath( 'class', 'com_frontpage' ) );

	$fp = new mosFrontPage( $database );
	foreach ($cid as $id) {
		// toggles go to first place
		if ($fp->load( $id )) {
			if (!$fp->delete( $id )) {
				$msg .= $fp->stderr();
			}
			$fp->ordering = 0;
		} else {
			// new entry
			$database->setQuery( "INSERT INTO #__content_frontpage VALUES ('$id','0')" );
			if (!$database->query()) {
				echo "<script> alert('".$database->stderr()."');</script>\n";
				exit();
			}
			$fp->ordering = 0;
		}
		$fp->updateOrder();
	}	
	mosRedirect( 'index2.php?option=com_typedcontent' );
} //toggleFrontPage

?>
