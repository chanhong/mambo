<?php
/**
* @package Mambo
* @subpackage Menus
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

// ensure user has access to this function
if (!$acl->acl_check( 'administration', 'manage', 'users', $my->usertype, 'components', 'com_menumanager' )) {
	mosRedirect( 'index2.php', T_('You are not authorized to view this resource.') );
}

require_once( $mainframe->getPath( 'admin_html' ) );

$menu 		= mosGetParam( $_GET, 'menu', '' );
$task 		= mosGetParam( $_REQUEST, 'task', array(0) );
$type 		= mosGetParam( $_POST, 'type', '' );
$cid 		= mosGetParam( $_POST, 'cid', '' );

switch ($task) {
	case 'new':
		editMenu( $option, '' );
		break;

	case 'edit':
		if ( !$menu ) {
			$menu = $cid[0];
		}
		editMenu( $option, $menu );
		break;

	case 'savemenu':
		saveMenu();
		break;

	case 'deleteconfirm':
		deleteconfirm( $option, $cid );
		break;

	case 'deletemenu':
		deleteMenu( $option, $cid, $type );
		break;

	case 'copyconfirm':
		copyConfirm( $option, $cid[0] );
		break;

	case 'copymenu':
		copyMenu( $option, $cid, $type );
		break;

	case 'cancel':
		cancelMenu( $option );
		break;

	default:
		showMenu( $option );
		break;
}


/**
* Compiles a list of menumanager items
*/
function showMenu( $option ) {
	global $database, $mainframe, $mosConfig_list_limit;

	$limit 		= $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit );
	$limitstart = $mainframe->getUserStateFromRequest( "view{". $option ."}limitstart", 'limitstart', 0 );

	$menuTypes 	= mosAdminMenus::menutypes();				
	$total		= count( $menuTypes );
	$i			= 0;
	foreach ( $menuTypes as $a ) {
		$menus[$i]->type 		= $a;		
		
		// query to get number of modules for menutype
		$query = "SELECT count( id )"
		. "\n FROM #__modules"
		. "\n WHERE module = 'mod_mainmenu'"
		. "\n AND params LIKE '%$a%'"
		;
		$database->setQuery( $query );
		$modules = $database->loadResult();		
		
		if ( !$modules ) {
			$modules = '-';
		}
		$menus[$i]->modules = $modules;
		
		$i++;
	}

	// Query to get published menu item counts
	$query = "SELECT a.menutype, count( a.menutype ) as num"
	. "\n FROM #__menu AS a"
	. "\n WHERE a.published = 1"
	. "\n GROUP BY a.menutype"
	. "\n ORDER BY a.menutype"
	;
	$database->setQuery( $query );
	$published = $database->loadObjectList();

	// Query to get unpublished menu item counts
	$query = "SELECT a.menutype, count( a.menutype ) as num"
	. "\n FROM #__menu AS a"
	. "\n WHERE a.published = 0"
	. "\n GROUP BY a.menutype"
	. "\n ORDER BY a.menutype"
	;
	$database->setQuery( $query );
	$unpublished = $database->loadObjectList();
	if (!$unpublished) $unpublished = array();

	// Query to get trash menu item counts
	$query = "SELECT a.menutype, count( a.menutype ) as num"
	. "\n FROM #__menu AS a"
	. "\n WHERE a.published = -2"
	. "\n GROUP BY a.menutype"
	. "\n ORDER BY a.menutype"
	;
	$database->setQuery( $query );
	$trash = $database->loadObjectList();
	if (!$trash) $trash = array();

	for( $i = 0; $i < $total; $i++ ) {
		// adds published count
		foreach ( $published as $count ) {
			if ( $menus[$i]->type == $count->menutype ) {
				$menus[$i]->published = $count->num;
			}
		}
		if ( @!$menus[$i]->published ) {
			$menus[$i]->published = '-';
		}
		// adds unpublished count
		foreach ( $unpublished as $count ) {
			if ( $menus[$i]->type == $count->menutype ) {
				$menus[$i]->unpublished = $count->num;
			}
		}
		if ( @!$menus[$i]->unpublished ) {
			$menus[$i]->unpublished = '-';
		}
		// adds trash count
		foreach ( $trash as $count ) {
			if ( $menus[$i]->type == $count->menutype ) {
				$menus[$i]->trash = $count->num;
			}
		}
		if ( @!$menus[$i]->trash ) {
			$menus[$i]->trash = '-';
		}
	}

	require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit  );

	HTML_menumanager::show( $option, $menus, $pageNav );
}


/**
* Edits a mod_mainmenu module
*
* @param option	options for the edit mode
* @param cid	menu id
*/
function editMenu( $option, $menu ) {
	global $database;
	
	if( $menu ) {
		$row->menutype 	= $menu;
	} else {
		$row = new mosModule( $database );
		// setting default values
		$row->menutype 	= '';
		$row->iscore 	= 0;
		$row->published = 0;
		$row->position 	= 'left';
		$row->module 	= 'mod_mainmenu';
	}

	HTML_menumanager::edit( $row, $option );
}

/**
* Creates a new mod_mainmenu module, which makes the menu visible
* this is a workaround until a new dedicated table for menu management can be created
*/
function saveMenu() {
	global $database;

	$menutype 		= mosGetParam( $_POST, 'menutype', '' );
	$old_menutype 	= mosGetParam( $_POST, 'old_menutype', '' );
	$new			= mosGetParam( $_POST, 'new', 1 );

	// block to stop renaming of 'mainmenu' menutype
	if ( $old_menutype == 'mainmenu' ) {
		if ( $menutype <> 'mainmenu' ) {
			echo "<script> alert('".T_('You cannot rename the "mainmenu" Menu as this will disrupt the proper operation of Mambo')."'); window.history.go(-1); </script>\n";
			exit;			
		}
	}
	
	// check for unique menutype for new menus
	$query = "SELECT params"
	. "\n FROM #__modules"
	. "\n WHERE module = 'mod_mainmenu'"
	;
	$database->setQuery( $query );
	$menus = $database->loadResultArray();	
	foreach ( $menus as $menu ) {
		$pparser = new mosParameters($menu);
		$params = $pparser->getParams();
		if ( $params->menutype == $menutype ) {
			echo "<script> alert('".T_('A menu already exists with that name - you must enter a unique Menu Name')."'); window.history.go(-1); </script>\n";
			exit;
		}
	}
	
	switch ( $new ) {
		case 1:
		// create a new module for the new menu
			$row = new mosModule( $database );
			$row->bind( $_POST );
		
			$row->params = 'menutype='. $menutype;
	
			// check then store data in db	
			if (!$row->check()) {
				echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
				exit();
			}
			if (!$row->store()) {
				echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
				exit();
			}
			
			$row->checkin();
			$row->updateOrder( "position='". $row->position ."'" );
			
			// module assigned to show on All pages by default
			// ToDO: Changed to become a mambo db-object
			$query = "INSERT INTO #__modules_menu VALUES ( $row->id, 0 )";
			$database->setQuery( $query );
			if ( !$database->query() ) {
				echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			}
			
			$msg = sprintf(T_('New Menu created [ %s ]'), $menutype);			
			break;
	
		default:
		// change menutype being of all mod_mainmenu modules calling old menutype
			$query = "SELECT id"
			. "\n FROM #__modules"
			. "\n WHERE module = 'mod_mainmenu'"
			. "\n AND params LIKE '%$old_menutype%'"						
			;
			$database->setQuery( $query );
			$modules = $database->loadResultArray();
			
			foreach ( $modules as $module ) {
				$row = new mosModule( $database );
				$row->load( $module );
				
				$save = 0;
				$pparser = new mosParameters($row->params);
				$params = $pparser->getParams();
				if ( $params->menutype == $old_menutype ) {
					$params->menutype 	= $menutype;
					$save 				= 1;
				}
				
				// save changes to module 'menutype' param
				if ( $save ) {
					$txt = array();
					foreach ( $params as $k=>$v) {
						$txt[] = "$k=$v";
					}
					$row->params = implode( "\n", $txt );
					
					// check then store data in db	
					if ( !$row->check() ) {
						echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
						exit();
					}
					if ( !$row->store() ) {
						echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
						exit();
					}
					
					$row->checkin();
				}				
			}
			
		// change menutype of all menuitems using old menutype
			if ( $menutype <> $old_menutype ) {
				$query = "UPDATE #__menu SET menutype = '$menutype' WHERE menutype = '$old_menutype'";
				$database->setQuery( $query );
				$database->query();
			}
			
			$msg = T_('Menu Items & Modules updated');
			break;
	}
		
	mosRedirect( 'index2.php?option=com_menumanager', $msg );
}

/**
* Compiles a list of the items you have selected to permanently delte
*/
function deleteConfirm( $option, $types ) {
	global $database;

	if ( in_array('mainmenu', $types )) {
		$types = array_diff($types, array('mainmenu'));
		echo "<script> alert('".T_('You cannot delete the "mainmenu" menu as it is a core menu')."'); </script>\n";
	}

	$menus = implode("','",$types);
	// list of menu items to delete
	$query = 	"SELECT a.name, a.id"
	. "\n FROM #__menu AS a"
	. "\n WHERE ( a.menutype IN ( '$menus' ) )"
	. "\n ORDER BY a.name"
	;
	$database->setQuery( $query );
	$items = $database->loadObjectList();

	foreach ($types as $type) {
		// list of modules to delete
		$query = "SELECT id"
		. "\n FROM #__modules"
		. "\n WHERE module = 'mod_mainmenu'"
		. "\n AND params LIKE '%$type%'"						
		;
		$database->setQuery( $query );
		$mods = $database->loadResultArray();
		foreach ( $mods as $module ) {
			$row = new mosModule( $database );
			$row->load( $module );
			$pparser = new mosParameters($row->params);
			$params = $pparser->getParams();
			if ( $params->menutype == $type ) {
				$mid[] = $module;
			}		
		}
	}

	@$mids = implode( ',', $mid );
	$query = "SELECT id, title"
	. "\n FROM #__modules"
	. "\n WHERE id IN ( $mids )"
	;
	$database->setQuery( $query );
	@$modules = $database->loadObjectList();

	HTML_menumanager::showDelete( $option, $type, $items, $modules );
}

/**
* Deletes menu items(s) you have selected
*/
function deleteMenu( $option, $cid, $type ) {
	global $database;

	if ( $type == 'mainmenu' ) {
		echo "<script> alert('".T_('You cannot delete the "mainmenu" menu as it is a core menu')."'); window.history.go(-1); </script>\n";
		exit();		
	}
	

	$mids 		= mosGetParam( $_POST, 'mids', 0 );	
	if ( is_array( $mids ) ) {
		$mids = implode( ',', $mids );
	}
	// delete menu items
	$query = 	"DELETE FROM #__menu"
	. "\n WHERE ( id IN ( $mids ) )"
	;
	$database->setQuery( $query );
	if ( !$database->query() ) {
		echo "<script> alert('". $database->getErrorMsg() ."');</script>\n";
		exit;
	}

	if ( is_array( $cid ) ) {
		$cids = implode( ',', $cid );
	} else {
		$cids = $cid;
	}
	
	// checks whether any modules to delete
	if ( $cids ) {		
		// delete modules
		$database->setQuery( "DELETE FROM #__modules WHERE id IN ( $cids )" );
		if ( !$database->query() ) {
			echo "<script> alert('". $database->getErrorMsg() ."'); window.history.go(-1); </script>\n";
			exit;
		}
		// delete all module entires in mos_modules_menu
		$database->setQuery( "DELETE FROM #__modules_menu WHERE moduleid IN ( ". $cids ." )" );
		if ( !$database->query() ) {
			echo "<script> alert('". $database->getErrorMsg() ."');</script>\n";
			exit;
		}
		
		// reorder modules after deletion
		$mod = new mosModule( $database );
		$mod->ordering = 0;
		$mod->updateOrder( "position='left'" );
		$mod->updateOrder( "position='right'" );
	}

	$msg = 'Menu Deleted';
	mosRedirect( 'index2.php?option=' . $option, $msg );
}


/**
* Compiles a list of the items you have selected to Copy
*/
function copyConfirm( $option, $type ) {
	global $database;

	// Content Items query
	$query = 	"SELECT a.name, a.id"
	. "\n FROM #__menu AS a"
	. "\n WHERE ( a.menutype IN ( '". $type ."' ) )"
	. "\n ORDER BY a.name"
	;
	$database->setQuery( $query );
	$items = $database->loadObjectList();

	HTML_menumanager::showCopy( $option, $type, $items );
}


/**
* Copies a complete menu, all its items and creates a new module, using the name speified
*/
function copyMenu( $option, $cid, $type ) {
	global $database;

	$menu_name 		= mosGetParam( $_POST, 'menu_name', 'New Menu' );
	$module_name 	= mosGetParam( $_POST, 'module_name', 'New Module' );
	
	// check for unique menutype for new menu copy
	$query = "SELECT params"
	. "\n FROM #__modules"
	. "\n WHERE module = 'mod_mainmenu'"
	;
	$database->setQuery( $query );
	$menus = $database->loadResultArray();	
	foreach ( $menus as $menu ) {
		$pparser = new mosParameters($menu);
		$params = $pparser->getParams();
		if ( $params->menutype == $menu_name ) {
			echo "<script> alert('".T_('A menu with that name already exists - you must enter a unique Menu Name')."'); window.history.go(-1); </script>\n";
			exit;
		}
	}
	
	// copy the menu items
	$mids 		= mosGetParam( $_POST, 'mids', '' );
	$total 		= count( $mids );
	$copy 		= new mosMenu( $database );
	$original 	= new mosMenu( $database );
	sort( $mids );
	$a_ids 		= array();
	
	foreach( $mids as $mid ) {
		$original->load( $mid );
		$copy 			= $original;
		$copy->id 		= NULL;
		$copy->parent 	= $a_ids[$original->parent];		
		$copy->menutype = $menu_name;
		
		if ( !$copy->check() ) {
			echo "<script> alert('".$copy->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		if ( !$copy->store() ) {
			echo "<script> alert('".$copy->getError()."'); window.history.go(-1); </script>\n";
			exit();
		}
		$a_ids[$original->id] = $copy->id;
	}

	// create the module copy
	$row = new mosModule( $database );
	$row->load( 0 );
	$row->title 	= $module_name;
	$row->iscore 	= 0;
	$row->published = 1;
	$row->position 	= 'left';
	$row->module 	= 'mod_mainmenu';
	$row->params 	= 'menutype='. $menu_name;

	if (!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	$row->checkin();
	$row->updateOrder( "position='". $row->position ."'" );
	// module assigned to show on All pages by default
	// ToDO: Changed to become a mambo db-object
	$query = "INSERT INTO #__modules_menu VALUES ( $row->id, 0 )";
	$database->setQuery( $query );
	if ( !$database->query() ) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	$msg = sprintf(Tn_('Copy of Menu `%s` created, consisting of %d item', 'Copy of Menu `%s` created, consisting of %d items', $total), $type, $total);
	mosRedirect( 'index2.php?option=' . $option, $msg );
}

/**
* Cancels an edit operation
* @param option	options for the operation
*/
function cancelMenu( $option ) {
	mosRedirect( 'index2.php?option=' . $option . '&task=view' );
}
?>
