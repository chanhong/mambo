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

function mosTreeRecurse( $id, $indent, $list, &$children, $maxlevel=9999, $level=0, $type=1,$parent='parent') {
	if (@$children[$id] AND $level <= $maxlevel) {
		$newindent = $indent.($type ? '.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' : '&nbsp;&nbsp;');
		$pre = $type ? '<sup>L</sup>&nbsp;' : '- ';
		foreach ($children[$id] as $v) {
			$id = $v->id;
			$list[$id] = $v;
			$list[$id]->treename = $indent.($v->$parent == 0 ? '' : $pre).$v->name;
			$list[$id]->children = count( @$children[$id] );
			$list[$id]->level = $level;
			$list = mosTreeRecurse( $id, $newindent, $list, $children, $maxlevel, $level+1, $type );
		}
	}
	return $list;
}

/**
* @param string SQL with ordering As value and 'name field' AS text
* @param integer The length of the truncated headline
*/
function mosGetOrderingList( $sql, $chop='30' ) {
	$database = mamboDatabase::getInstance();
	$database->setQuery( $sql );
	if (!($orders = $database->loadObjectList())) {
		if ($database->getErrorNum()) {
			echo $database->stderr();
			return false;
		} else {
			$order[] = mosHTML::makeOption( 1, 'first' );
			return $order;
		}
	}
	$order[] = mosHTML::makeOption( 0, '0 first' );
	foreach ($orders as $ord) {
		$text = strlen($ord->text) > $chop ? substr($ord->text,0,$chop)."..." : $ord->text;
		$order[] = mosHTML::makeOption( $ord->value, $ord->value.' ('.$text.')' );
	}
	$order[] = mosHTML::makeOption( $ord->value+1, ($ord->value+1).' last' );
	return $order;
}

/**
* Common HTML Output Files
* @package Mambo
*/
class mosAdminMenus {
	/**
	* build the select list for Menu Ordering
	*/
	function Ordering( &$row, $id ) {
		global $database;

		if ( $id ) {
			$order = mosGetOrderingList( "SELECT ordering AS value, name AS text"
			. "\n FROM #__menu"
			. "\n WHERE menutype='". $row->menutype ."'"
			. "\n AND parent='". $row->parent ."'"
			. "\n AND published != '-2'"
			. "\n ORDER BY ordering"
			);
			$ordering = mosHTML::selectList( $order, 'ordering', 'class="inputbox" size="1"', 'value', 'text', intval( $row->ordering ) );
		}
		else {
			$ordering = '<input type="hidden" name="ordering" value="'. $row->ordering .'" />'. T_('New items default to the last place. Ordering can be changed after this item is saved.');
		}
		return $ordering;
	}

	/**
	* build the select list for access level
	* @param object a module object
	* @return mixed a select list
	*/
	function Access( &$row ) {
		global $database;

		$query = 'SELECT id AS value, name AS text FROM #__groups ORDER BY id';
		$database->setQuery( $query );
		$groups = $database->loadObjectList();
		$access = mosHTML::selectList( $groups, 'access', 'class="inputbox" size="3"', 'value', 'text', intval( $row->access ) );
		return $access;
	}

	/**
	* build the select list for module group access
	*/
	function groupAccess( &$row ) {
		global $acl;
		$gtree = $acl->get_group_children_tree( null, 'USERS', false );
		$list = array();
		$j = 0;
		for($i = 0; $i<count($gtree); $i++) {
			$temp = explode('-', $gtree[$i]->text);
			$idx = (count($temp) == 1) ? 0 : 1;
			if ($gtree[$i]->value != 29 && $gtree[$i]->value != 30) {
				$list[$j]->value = $gtree[$i]->value;
				$list[$j]->text = trim(str_replace('&nbsp;','',$temp[$idx]));
				$j++;
			}
		}
		$groups = explode(',',$row->groups);
		for($i=0; $i < count($groups); $i++) {
			$group = new stdclass;
			$group->value = $groups[$i];
			$groups[$i] = $group;
		}
		$groupSelect = mosHTML::selectList( $list, 'groups[]', 'class="inputbox" multiple="multiple" size="6"', 'value', 'text', $groups );
		return $groupSelect;
	}

	/**
	* build the select list for parent item
	*/
	function Parent( &$row ) {
		global $database;

		// get a list of the menu items
		$query = "SELECT m.*"
		. "\n FROM #__menu m"
		. "\n WHERE menutype='$row->menutype'"
		. "\n AND parent!='$row->id'"
		. "\n AND published <> -2"
		. "\n ORDER BY ordering"
		;
		$database->setQuery( $query );
		$mitems = $database->loadObjectList();

		// establish the hierarchy of the menu
		$children = array();
		// first pass - collect children
		if (is_array($mitems)) {
			foreach ( $mitems as $v ) {
				$pt = $v->parent;
				$list = @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
		}
		// second pass - get an indent list of the items
		$list = mosTreeRecurse( 0, '', array(), $children, 9999, 0, 0 );

		// assemble menu items to the array
		$mitems = array();
		$mitems[] = mosHTML::makeOption( '0', 'Top' );
		$this_treename = '';
		foreach ( $list as $item ) {
			if ( $this_treename ) {
				if ( $item->id != $row->id && strpos( $item->treename, $this_treename ) === false) {
					$mitems[] = mosHTML::makeOption( $item->id, $item->treename );
				}
			} else {
				if ( $item->id != $row->id ) {
					$mitems[] = mosHTML::makeOption( $item->id, $item->treename );
				} else {
					$this_treename = "$item->treename/";
				}
			}
		}
		$parent = mosHTML::selectList( $mitems, 'parent', 'class="inputbox" size="1"', 'value', 'text', $row->parent );
		return $parent;
	}

	/**
	* build a radio button option for published state
	*/
	function Published( &$row ) {
		$published = mosHTML::yesnoRadioList( 'published', 'class="inputbox"', $row->published );
		return $published;
	}

	/**
	* build the link/url of a menu item
	*/
	function Link( &$row, $id, $link=NULL ) {
		if ( $id ) {
			if ( $link ) {
				$link = $row->link;
			} else {
				$link = $row->link .'&amp;Itemid='. $row->id;
			}
		} else {
			$link = NULL;
		}
		return $link;
	}

	/**
	* build the select list for target window
	*/
	function Target( &$row ) {
		$click[] = mosHTML::makeOption( '0',  T_('Parent Window With Browser Navigation'));
		$click[] = mosHTML::makeOption( '1',  T_('New Window With Browser Navigation'));
		$click[] = mosHTML::makeOption( '2', T_('New Window Without Browser Navigation'));
		$target = mosHTML::selectList( $click, 'browserNav', 'class="inputbox" size="4"', 'value', 'text', intval( $row->browserNav ) );
		return $target;
	}

	/**
	* build the multiple select list for Menu Links/Pages
	*/
	function MenuLinks( &$lookup, $all=NULL, $none=NULL ) {
		global $database;

		// get a list of the menu items
		$database->setQuery( "SELECT m.*"
		. "\n FROM #__menu m"
		. "\n WHERE type != 'separator'"
		. "\n AND link NOT LIKE '%tp:/%'"
		. "\n AND published = '1'"
		. "\n ORDER BY menutype, parent, ordering"
		);
		$mitems = $database->loadObjectList();
		$mitems_temp = $mitems;

		// establish the hierarchy of the menu
		$children = array();
		// first pass - collect children
		foreach ( $mitems as $v ) {
			$id = $v->id;
			$pt = $v->parent;
			$list = @$children[$pt] ? $children[$pt] : array();
			array_push( $list, $v );
			$children[$pt] = $list;
		}
		// second pass - get an indent list of the items
		$list = mosTreeRecurse( intval( $mitems[0]->parent ), '', array(), $children, 9999, 0, 0 );

		// Code that adds menu name to Display of Page(s)
		$text_count = "0";
		$mitems_spacer = $mitems_temp[0]->menutype;
		foreach ($list as $list_a) {
			foreach ($mitems_temp as $mitems_a) {
				if ($mitems_a->id == $list_a->id) {
					// Code that inserts the blank line that seperates different menus
					if ($mitems_a->menutype <> $mitems_spacer) {
						$list_temp[] = mosHTML::makeOption( -999, '----' );
						$mitems_spacer = $mitems_a->menutype;
					}
					$text = $mitems_a->menutype." | ".$list_a->treename;
					$list_temp[] = mosHTML::makeOption( $list_a->id, $text );
					if ( strlen($text) > $text_count) {
						$text_count = strlen($text);
					}
				}
			}
		}
		$list = $list_temp;

		$mitems = array();
		if ( $all ) {
			// prepare an array with 'all' as the first item
			$mitems[] = mosHTML::makeOption( 0, T_('All') );
			// adds space, in select box which is not saved
			$mitems[] = mosHTML::makeOption( -999, '----' );
		}
		if ( $none ) {
			// prepare an array with 'all' as the first item
			$mitems[] = mosHTML::makeOption( -998, T_('None') ); 
			// adds space, in select box which is not saved
			$mitems[] = mosHTML::makeOption( -999, '----' );
		}
		// append the rest of the menu items to the array
		foreach ($list as $item) {
			$mitems[] = mosHTML::makeOption( $item->value, $item->text );
		}
		if ($lookup == NULL) {
			$lookup = '-998';
		}  
		$pages = mosHTML::selectList( $mitems, 'selections[]', 'class="inputbox" size="26" multiple="multiple"', 'value', 'text', $lookup );
		return $pages;
	}


	/**
	* build the select list to choose a category
	*/
	function Category( &$menu, $id, $javascript='' ) {
		global $database;

		$query = "SELECT c.id AS `value`, c.section AS `id`, CONCAT_WS( ' / ', s.title, c.title) AS `text`"
		. "\n FROM #__sections AS s"
		. "\n INNER JOIN #__categories AS c ON c.section = s.id"
		. "\n WHERE s.scope = 'content'"
		. "\n ORDER BY s.name,c.name"
		;
		$database->setQuery( $query );
		$rows = $database->loadObjectList();
		$category = '';
		if ( $id ) {
			foreach ( $rows as $row ) {
				if ( $row->value == $menu->componentid ) {
					$category = $row->text;
				}
			}
			$category .= '<input type="hidden" name="componentid" value="'. $menu->componentid .'" />';
			$category .= '<input type="hidden" name="link" value="'. $menu->link .'" />';
		} else {
			$category = mosHTML::selectList( $rows, 'componentid', 'class="inputbox" size="10"'. $javascript, 'value', 'text' );
			$category .= '<input type="hidden" name="link" value="" />';
		}
		return $category;
	}

	/**
	* build the select list to choose a section
	*/
	function Section( &$menu, $id, $all=0 ) {
		global $database;

		$query = "SELECT s.id AS `value`, s.id AS `id`, s.title AS `text`"
		. "\n FROM #__sections AS s"
		. "\n WHERE s.scope = 'content'"
		. "\n ORDER BY s.name"
		;
		$database->setQuery( $query );
		if ( $all ) {
			$rows[] = mosHTML::makeOption( 0, T_('- All Sections -') );
			$rows = array_merge( $rows, $database->loadObjectList() );
		} else {
			$rows = $database->loadObjectList();
		}

		if ( $id ) {
			foreach ( $rows as $row ) {
				if ( $row->value == $menu->componentid ) {
					$section = $row->text;
				}
			}
			$section .= '<input type="hidden" name="componentid" value="'. $menu->componentid .'" />';
			$section .= '<input type="hidden" name="link" value="'. $menu->link .'" />';
		} else {
			$section = mosHTML::selectList( $rows, 'componentid', 'class="inputbox" size="10"', 'value', 'text' );
			$section .= '<input type="hidden" name="link" value="" />';
		}
		return $section;
	}

	/**
	* build the select list to choose a component
	*/
	function Component( &$menu, $id ) {
		global $database;

		$query = "SELECT c.id AS value, c.name AS text, c.link"
		. "\n FROM #__components AS c"
		. "\n WHERE c.link <> ''"
		. "\n ORDER BY c.name"
		;
		$database->setQuery( $query );
		$rows = $database->loadObjectList( );
		if ( $id ) {
			// existing component, just show name
			foreach ( $rows as $row ) {
				if ( $row->value == $menu->componentid ) {
					$component = $row->text;
				}
			}
			$component .= '<input type="hidden" name="componentid" value="'. $menu->componentid .'" />';
		} else {
			$component = mosHTML::selectList( $rows, 'componentid', 'class="inputbox" size="10"', 'value', 'text' );
		}
		return $component;
	}

	/**
	* build the select list to choose a component
	*/
	function ComponentName( &$menu, $id ) {
		global $database;

		$query = "SELECT c.id AS value, c.name AS text, c.link"
		. "\n FROM #__components AS c"
		. "\n WHERE c.link <> ''"
		. "\n ORDER BY c.name"
		;
		$database->setQuery( $query );
		$rows = $database->loadObjectList( );

		$component = 'Component';
		foreach ( $rows as $row ) {
			if ( $row->value == $menu->componentid ) {
				$component = $row->text;
			}
		}

		return $component;
	}

	/**
	* build the select list to choose an image
	*/
	function Images( $name, &$active, $javascript=NULL, $directory=NULL ) {
		global $mosConfig_absolute_path;

		if ( !$javascript ) {
			$javascript = "onchange=\"javascript:if (document.forms[0].image.options[selectedIndex].value!='') {document.imagelib.src='../images/stories/' + document.forms[0].image.options[selectedIndex].value} else {document.imagelib.src='../images/blank.png'}\"";
		}
		if ( !$directory ) {
			$directory = '/images/stories';
		}

		$imageFiles = mosReadDirectory( $mosConfig_absolute_path . $directory );
		$images = array(  mosHTML::makeOption( '', T_('- Select Image -') ) );
		foreach ( $imageFiles as $file ) {
			if ( eregi( "bmp|gif|jpg|png", $file ) ) {
				$images[] = mosHTML::makeOption( $file );
			}
		}
		$images = mosHTML::selectList( $images, $name, 'class="inputbox" size="1" '. $javascript, 'value', 'text', $active );

		return $images;
	}

	/**
	* build the select list for Ordering of a specified Table
	*/
	function SpecificOrdering( &$row, $id, $query, $neworder=0 ) {
		global $database;

		if ( $neworder ) {
			$text = T_('New items default to the first place. Ordering can be changed after this item is saved.');
		} else {
			$text = T_('New items default to the last place. Ordering can be changed after this item is saved.');
		}

		if ( $id ) {
			$order = mosGetOrderingList( $query );
			$ordering = mosHTML::selectList( $order, 'ordering', 'class="inputbox" size="1"', 'value', 'text', intval( $row->ordering ) );
		} else {
			$ordering = '<input type="hidden" name="ordering" value="'. $row->ordering .'" />'. $text;
		}
		return $ordering;
	}

	/**
	* Select list of active users
	*/
	function UserSelect( $name, $active, $nouser=0, $javascript=NULL, $order='name' ) {
		global $database, $my;

		$query = "SELECT id AS value, CONCAT(name,' (',username,')') AS text"
		. "\n FROM #__users"
		. "\n WHERE block = '0'"
		. "\n ORDER BY ". $order
		;
		$database->setQuery( $query );
		if ( $nouser ) {
			$users[] = mosHTML::makeOption( '0', T_('- No User -') );
			$users = array_merge( $users, $database->loadObjectList() );
		} else {
			$users = $database->loadObjectList();
		}

		$users = mosHTML::selectList( $users, $name, 'class="inputbox" size="1" '. $javascript, 'value', 'text', $active );

		return $users;
	}

	/**
	* Select list of positions - generally used for location of images
	*/
	function Positions( $name, $active=NULL, $javascript=NULL, $none=1, $center=1, $left=1, $right=1 ) {
		if ( $none ) {
			$pos[] = mosHTML::makeOption( '', T_('None') );
		}
		if ( $center ) {
			$pos[] = mosHTML::makeOption( 'center', T_('Center') );
		}
		if ( $left ) {
			$pos[] = mosHTML::makeOption( 'left', T_('Left') );
		}
		if ( $right ) {
			$pos[] = mosHTML::makeOption( 'right', T_('Right') );
		}

		$positions = mosHTML::selectList( $pos, $name, 'class="inputbox" size="1"'. $javascript, 'value', 'text', $active );

		return $positions;
	}

	/**
	* Select list of active categories for components
	*/
	function ComponentCategory( $name, $section, $active=NULL, $javascript=NULL, $order='ordering', $size=1, $sel_cat=1 ) {
		global $database;

		$query = "SELECT id AS value, name AS text"
		. "\n FROM #__categories"
		. "\n WHERE section = '". $section ."'"
		. "\n AND published = '1'"
		. "\n ORDER BY ". $order
		;
		$database->setQuery( $query );
		$categories = $database->loadObjectList();
		if (!$categories) $categories = array();
		if ( $sel_cat ) array_unshift($categories, mosHTML::makeOption('0', T_('- All Categories -')));
		if ( count( $categories ) < 1 ) mosRedirect( 'index2.php?option=com_categories&section='. $section, T_('You must create a category first.') );
		$categorylist = mosHTML::selectList( $categories, $name, 'class="inputbox" size="'. $size .'" '. $javascript, 'value', 'text', $active );
		return $categorylist;
	}

	/**
	* Select list of active sections
	*/
	function SelectSection( $name, $active=NULL, $javascript=NULL, $order='ordering' ) {
		global $database;

		$categories[] = mosHTML::makeOption( '0', T_('- All Sections -') );
		$query = "SELECT id AS value, title AS text"
		. "\n FROM #__sections"
		. "\n WHERE published = '1'"
		. "\n ORDER BY ". $order
		;
		$database->setQuery( $query );
		if (is_array($database->loadObjectList())) {
		  $sections = array_merge( $categories, $database->loadObjectList() );
		}
		$category = mosHTML::selectList( $sections, $name, 'class="inputbox" size="1" '. $javascript, 'value', 'text', $active );

		return $category;
	}

	/**
	* Select list of menu items for a specific menu
	*/
	function Links2Menu( $type, $_and ) {
		global $database;

		$query = "SELECT *"
		. "\n FROM #__menu"
		. "\n WHERE type = '". $type ."'"
		. "\n AND published = '1'"
		. $_and
		;
		$database->setQuery( $query );
		$menus = $database->loadObjectList();

		return $menus;
	}

	/**
	* Select list of menus
	*/
	function MenuSelect( $name='menuselect', $javascript=NULL ) {
		global $database;

		$query = "SELECT params"
		. "\n FROM #__modules"
		. "\n WHERE module = 'mod_mainmenu'"
		;
		$database->setQuery( $query );
		$menus = $database->loadObjectList();
		$total = count( $menus );
		for( $i = 0; $i < $total; $i++ ) {
			$params = mosParseParams( $menus[$i]->params );
			$menuselect[$i]->value 	= $params->menutype;
			$menuselect[$i]->text 	= $params->menutype;
		}
		// sort array of objects
		SortArrayObjects( $menuselect, 'text', 1 );

		$menus = mosHTML::selectList( $menuselect, $name, 'class="inputbox" size="10" '. $javascript, 'value', 'text' );

		return $menus;
	}

	/**
	* Checks to see if an image exists in the current templates image directory
 	* if it does it loads this image.  Otherwise the default image is loaded.
	* Also can be used in conjunction with the menulist param to create the chosen image
	* load the default or use no image
	*/
	function ImageCheckAdmin( $file, $directory='/administrator/images/', $param=NULL, $param_directory='/administrator/images/', $alt=NULL, $name=NULL, $type=1, $align='middle' ) {
		$mosConfig_live_site = mamboCore::get('mosConfig_live_site');
		$mainframe = mosMainFrame::getInstance();
		$cur_template = $mainframe->getTemplate();
		if ($param) $image = $mosConfig_live_site. $param_directory . $param;
		else {
			if ( file_exists(mamboCore::get('mosConfig_absolute_path').'/administrator/templates/'. $cur_template .'/images/'. $file ) ) {
				$image = $mosConfig_live_site .'/administrator/templates/'. $cur_template .'/images/'. $file;
			}
			else $image = $mosConfig_live_site. $directory . $file;
		}
		// outputs actual html <img> tag
		if ( $type ) $image = '<img src="'. $image .'" alt="'. $alt .'" align="'. $align .'" name="'. $name .'" border="0" />';
		return $image;
	}

	/**
	* Internal function to recursive scan the media manager directories
	* @param string Path to scan
	* @param string root path of this folder
	* @param array  Value array of all existing folders
	* @param array  Value array of all existing images
	*/
	function ReadImages( $imagePath, $folderPath, &$folders, &$images ) {
		$imgDir =& new mosDirectory($imagePath);
		$imgFiles =& $imgDir->listFiles ('.', 'both');

		foreach ($imgFiles as $file) {
			$ff_ = $folderPath . $file .'/';
			$ff = $folderPath . $file;
			$i_f = $imagePath .'/'. $file;

			if ( is_dir( $i_f ) AND $file <> 'CVS' ) {
				$folders[] = mosHTML::makeOption( $ff_ );
				mosAdminMenus::ReadImages( $i_f, $ff_, $folders, $images );
			} else if ( eregi( "bmp|gif|jpg|png", $file ) AND is_file( $i_f ) ) {
				// leading / we don't need
				$imageFile = substr( $ff, 1 );
				$images[$folderPath][] = mosHTML::makeOption( $imageFile, $file );
			}
		}
	}

	function GetImageFolders( &$folders, $path ) {
		$javascript 	= "onchange=\"changeDynaList( 'imagefiles', folderimages, document.adminForm.folders.options[document.adminForm.folders.selectedIndex].value, 0, 0);  previewImage( 'imagefiles', 'view_imagefiles', '$path/' );\"";
		$getfolders 	= mosHTML::selectList( $folders, 'folders', 'class="inputbox" size="1" '. $javascript, 'value', 'text', '/' );
		return $getfolders;
	}

	function GetImages( &$images, $path ) {
		if ( !isset($images['/'] ) ) $images['/'][] = mosHTML::makeOption( '' );
		//$javascript	= "onchange=\"previewImage( 'imagefiles', 'view_imagefiles', '$path/' )\" onfocus=\"previewImage( 'imagefiles', 'view_imagefiles', '$path/' )\"";
		$javascript	= "onchange=\"previewImage( 'imagefiles', 'view_imagefiles', '$path/' )\"";
		$getimages	= mosHTML::selectList( $images['/'], 'imagefiles', 'class="inputbox" size="10" multiple="multiple" '. $javascript , 'value', 'text', null );

		return $getimages;
	}

	function GetSavedImages( &$row, $path ) {
		$images2 = array();
		foreach( $row->images as $file ) {
			$temp = explode( '|', $file );
			$filename = strrchr($temp[0], '/') ? substr( strrchr($temp[0], '/' ), 1 ) : $temp[0];
			$images2[] = mosHTML::makeOption( $file, $filename );
		}
		//$javascript	= "onchange=\"previewImage( 'imagelist', 'view_imagelist', '$path/' ); showImageProps( '$path/' ); \" onfocus=\"previewImage( 'imagelist', 'view_imagelist', '$path/' )\"";
		$javascript	= "onchange=\"previewImage( 'imagelist', 'view_imagelist', '$path/' ); showImageProps( '$path/' ); \"";
		$imagelist 	= mosHTML::selectList( $images2, 'imagelist', 'class="inputbox" size="10" '. $javascript, 'value', 'text' );

		return $imagelist;
	}

	function menutypes() {
		$modulehandler =& mosModuleHandler::getInstance();
		$modMenus =& $modulehandler->getByName('mod_mainmenu', false, true);

		$menuhandler =& mosMenuHandler::getInstance();
		$mtypes =& $menuhandler->getMenuTypes();
		$menuTypes = array();
		foreach ($mtypes as $type=>$count) $menuTypes[] = $type;
		foreach ($modMenus as $modMenu) {
			mosMakeHtmlSafe($modMenu) ;
			$modParams 	= mosParseParams( $modMenu->params );
			$menuType 	= @$modParams->menutype ? $modParams->menutype : 'mainmenu';
			if (!in_array($menuType, $menuTypes)) $menuTypes[] = $menuType;
		}

		// sorts menutypes
		asort( $menuTypes );

		return $menuTypes;
	}

	/*
	* loads files required for menu items
	*/
	function menuItem( $item ) {
		global $mosConfig_absolute_path;

		$path = $mosConfig_absolute_path .'/administrator/components/com_menus/'. $item .'/';
		include_once( $path . $item .'.class.php' );
		include_once( $path . $item .'.menu.html.php' );
	}
}

?>
