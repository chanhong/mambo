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

/**
* Category database table class
*/
class mosCategory extends mosDBTable {
	/** @var int Primary key */
	var $id=null;
	/** @var int */
	var $parent_id=null;
	/** @var string The menu title for the Category (a short name)*/
	var $title=null;
	/** @var string The full name for the Category*/
	var $name=null;
	/** @var string */
	var $image=null;
	/** @var string */
	var $section=null;
	/** @var int */
	var $image_position=null;
	/** @var string */
	var $description=null;
	/** @var boolean */
	var $published=null;
	/** @var boolean */
	var $checked_out=null;
	/** @var time */
	var $checked_out_time=null;
	/** @var string */
	var $editor=null;
	/** @var int */
	var $ordering=null;
	/** @var int */
	var $access=null;
	/** @var int */
	var $count=null;
	/** @var string */
	var $params=null;

	/**
	* @param database A database connector object
	*/
	function mosCategory( &$db ) {
		$this->mosDBTable( '#__categories', 'id', $db );
	}
	// overloaded check function
	function check() {
		// check for valid name
		if (trim( $this->title ) == '') {
			$this->_error = "Your Category must contain a title.";
			return false;
		}
		if (trim( $this->name ) == '') {
			$this->_error = "Your Category must have a name.";
			return false;
		}
		// check for existing name
		$this->_db->setQuery( "SELECT id FROM #__categories "
		. "\nWHERE name='".$this->name."' AND section='".$this->section."'"
		);

		$xid = intval( $this->_db->loadResult() );
		if ($xid && $xid != intval( $this->id )) {
			$this->_error = "There is a category already with that name, please try again.";
			return false;
		}
		return true;
	}
}

/**
* Section database table class
* @package Mambo
*/
class mosSection extends mosDBTable {
	/** @var int Primary key */
	var $id=null;
	/** @var string The menu title for the Section (a short name)*/
	var $title=null;
	/** @var string The full name for the Section*/
	var $name=null;
	/** @var string */
	var $image=null;
	/** @var string */
	var $scope=null;
	/** @var int */
	var $image_position=null;
	/** @var string */
	var $description=null;
	/** @var boolean */
	var $published=null;
	/** @var boolean */
	var $checked_out=null;
	/** @var time */
	var $checked_out_time=null;
	/** @var int */
	var $ordering=null;
	/** @var int */
	var $access=null;
	/** @var string */
	var $params='';

	/**
	* @param database A database connector object
	*/
	function mosSection( &$db ) {
		$this->mosDBTable( '#__sections', 'id', $db );
	}
	// overloaded check function
	function check() {
		// check for valid name
		if (trim( $this->title ) == '') {
			$this->_error = "Your Section must contain a title.";
			return false;
		}
		if (trim( $this->name ) == '') {
			$this->_error = "Your Section must have a name.";
			return false;
		}
		// check for existing name
		$this->_db->setQuery( "SELECT id FROM #__sections "
		. "\nWHERE name='$this->name' AND scope='$this->scope'"
		);

		$xid = intval( $this->_db->loadResult() );
		if ($xid && $xid != intval( $this->id )) {
			$this->_error = "There is a section already with that name, please try again.";
			return false;
		}
		return true;
	}
}

/**
* Module database table class
* @package Mambo
*/
class mosContent extends mosDBTable {
	/** @var int Primary key */
	var $id=null;
	/** @var string */
	var $title=null;
	/** @var string */
	var $title_alias=null;
	/** @var string */
	var $introtext=null;
	/** @var string */
	var $fulltext=null;
	/** @var int */
	var $state=null;
	/** @var int The id of the category section*/
	var $sectionid=null;
	/** @var int DEPRECATED */
	var $mask=null;
	/** @var int */
	var $catid=null;
	/** @var datetime */
	var $created=null;
	/** @var int User id*/
	var $created_by=null;
	/** @var string An alias for the author*/
	var $created_by_alias=null;
	/** @var datetime */
	var $modified=null;
	/** @var int User id*/
	var $modified_by=null;
	/** @var boolean */
	var $checked_out=null;
	/** @var time */
	var $checked_out_time=null;
	/** @var datetime */
	var $frontpage_up=null;
	/** @var datetime */
	var $frontpage_down=null;
	/** @var datetime */
	var $publish_up=null;
	/** @var datetime */
	var $publish_down=null;
	/** @var string */
	var $images=null;
	/** @var string */
	var $urls=null;
	/** @var string */
	var $attribs=null;
	/** @var int */
	var $version=null;
	/** @var int */
	var $parentid=null;
	/** @var int */
	var $ordering=null;
	/** @var string */
	var $metakey=null;
	/** @var string */
	var $metadesc=null;
	/** @var int */
	var $access=null;
	/** @var int */
	var $hits=null;

	/**
	* @param database A database connector object
	*/
	function mosContent() {
		$db =& mamboDatabase::getInstance();
		$this->mosDBTable( '#__content', 'id', $db );
	}

	/**
	 * Validation and filtering
	 */
	function check() {
		// filter malicious code
		$ignoreList = array( 'introtext', 'fulltext' );
		$this->filter( $ignoreList );

		/*
		TODO: This filter is too rigorous,
		need to implement more configurable solution
		// specific filters
		$iFilter = new InputFilter( null, null, 1, 1 );
		$this->introtext = trim( $iFilter->process( $this->introtext ) );
		$this->fulltext =  trim( $iFilter->process( $this->fulltext ) );
		*/

		if (trim( str_replace( '&nbsp;', '', $this->fulltext ) ) == '') {
			$this->fulltext = '';
		}

		return true;
	}

	/**
	* Converts record to XML
	* @param boolean Map foreign keys to text values
	*/
	function toXML( $mapKeysToText=false ) {
		global $database;

		if ($mapKeysToText) {
			$query = 'SELECT name FROM #__sections WHERE id=' . $this->sectionid;
			$database->setQuery( $query );
			$this->sectionid = $database->loadResult();

			$query = 'SELECT name FROM #__categories WHERE id=' . $this->catid;
			$database->setQuery( $query );
			$this->catid = $database->loadResult();

			$query = 'SELECT name FROM #__users WHERE id=' . $this->created_by;
			$database->setQuery( $query );
			$this->created_by = $database->loadResult();
		}

		return parent::toXML( $mapKeysToText );
	}
}

class mosExtendedContent extends mosContent {
	/** @var numeric */
	var $rating = null;
	/** @var int */
	var $rating_count = null;
	/** @var string */
	var $author = null;
	/** @var string */
	var $usertype = null;
	/** @var string */
	var $section = null;
	/** @var string */
	var $category = null;
	/** @var string */
	var $groups = null;
	/** @var string */
	var $text = null;

	function getText () {
		return $this->text;
	}

	function saveText ($text) {
		$this->text = $text;
	}

	function getImages () {
		return $this->images;
	}

	function saveImages ($images) {
		$this->images = $images;
	}

	function getId () {
		return $this->id;
	}

	function getRating () {
		return $this->rating;
	}

	function getRatingCount () {
		return $this->rating_count;
	}

}

class contentHandler {
	var $_category_limit = 250;
	var $_category_status = 0;
	var $_category;
	var $_section_limit = 250;
	var $_section_status = 0;
	var $_sections;

	function &getInstance () {
		static $instance;
		if (!is_object($instance)) $instance = new contentHandler();
		return $instance;
	}
	/**
	* @return number of Published Blog Sections
	*/
	function getBlogSectionCount( ) {
		$menuhandler =& mosMenuHandler::getInstance();
		if (count($menuhandler->getMenusByType('content_blog_section'))) {
			$query = "SELECT COUNT( m.id )"
			."\n FROM #__content AS i"
			."\n LEFT JOIN #__sections AS s ON i.sectionid=s.id"
			."\n LEFT JOIN #__menu AS m ON m.componentid=s.id "
			."\n WHERE m.type='content_blog_section'"
			."\n AND m.published='1'"
			;
			$database =& mamboDatabase::getInstance();
			$database->setQuery( $query );
			$count = $database->loadResult();
		} else {
			$count = 0;
		}
		return $count;
	}

	/**
	* @return number of Published Blog Categories
	*/
	function getBlogCategoryCount( ) {
		$menuhandler =& mosMenuHandler::getInstance();
		if (count($menuhandler->getMenusByType('content_blog_category'))) {
			$query = "SELECT COUNT( m.id )"
			. "\n FROM #__content AS i"
			. "\n LEFT JOIN #__categories AS c ON i.catid=c.id"
			. "\n LEFT JOIN #__menu AS m ON m.componentid=c.id "
			. "\n WHERE m.type='content_blog_category'"
			. "\n  AND m.published='1'"
			;
			$database =& mamboDatabase::getInstance();
			$database->setQuery( $query );
			$count = $database->loadResult();
		}
		else $count = 0;
		return $count;
	}

	/**
	* @return number of Published Global Blog Sections
	*/
	function getGlobalBlogSectionCount( ) {
		$menuhandler =& mosMenuHandler::getInstance();
		return $menuhandler->getGlobalBlogSectionCount();
	}

	/**
	* @return number of Static Content
	*/
	function getStaticContentCount( ) {
		$menuhandler =& mosMenuHandler::getInstance();
		return $menuhandler->getMenuCount ('content_typed', 1);
	}

	/**
	* @return number of Content Item Links
	*/
	function getContentItemLinkCount( ) {
		$menuhandler =& mosMenuHandler::getInstance();
		return $menuhandler->getMenuCount ('content_item_link', 1);
	}

	function getItemid ($id, $typed=1, $link=1, $bs=1, $bc=1, $gbs=1) {
		$_Itemid = null;
		$menuhandler =& mosMenuHandler::getInstance();
		if ($typed) {
			// Search for typed link
			$_Itemid = $menuhandler->getIDByTypeLink('content_typed', "index.php?option=com_content&task=view&id=$id");
		}

		if ($_Itemid == null AND $link) {
			// Search for item link
			$_Itemid = $menuhandler->getIDByTypeLink('content_item_link', "index.php?option=com_content&task=view&id=$id");
		}
		$sectionid = $this->getSection($id);
		if ($_Itemid == null) {
			// Search in sections
			$_Itemid = $menuhandler->getIDByTypeCid ('content_section', $sectionid);
		}
		if ($_Itemid == null) {
			// Search in sections
			$_Itemid = $menuhandler->getIDByTypeCid ('content_blog_section', $sectionid);
		}
		if ($_Itemid == null) {
			// Search in sections
			$_Itemid = $menuhandler->getIDByTypeCid ('content_blog_category', $sectionid);
		}
		if ($_Itemid == null AND $gbs) {
			// Search in global blog section
			$_Itemid = $menuhandler->getIDByTypeCid('content_blog_section', 0);
		}
		/*
		if ($_Itemid == '') {
			// Search in global blog category
			$this->_db->setQuery( "SELECT id "
			."\nFROM #__menu "
			."\nWHERE type='content_blog_category' AND published='1' AND componentid=0" );
			$_Itemid = $this->_db->loadResult();
		}
		*/		
		$catid = $this->getCategory($id);
		if ($_Itemid == null) {
			// Search in blog categories
			$_Itemid = $menuhandler->getIDByTypeCid ('content_blog_category', $catid);
		}
		if ($_Itemid == null) {
			// Search in categories
			$_Itemid = $menuhandler->getIDByTypeCid ('content_category', $catid);
		}
		if ($_Itemid == null) {
			// Search in main menu
			$menus = $menuhandler->getByParentOrder(0,'mainmenu');
			$home = $menus[0];
			$_Itemid = $home->id;
		}
		if ($_Itemid) return $_Itemid;
		else return mamboCore::get('Itemid');
	}

	function getSection ($id) {
		$database =& mamboDatabase::getInstance();
		$limit = $this->_section_limit;
		if (!$this->_section_status) {
			$database->setQuery("SELECT i.id, i.sectionid FROM #__content AS i, #__sections AS s WHERE i.sectionid=s.id ORDER BY i.id DESC LIMIT $limit");
			$sections = $database->loadObjectList();
			if ($sections) {
				foreach ($sections as $section) $this->_sections[$section->id] = $section->sectionid;
				$this->_section_status = count($sections);
			}
		}
		if ($this->_section_status) {
			if (isset($this->_sections[$id])) return $this->_sections[$id];
			if (count($this->_sections) < $limit) return 0;
			$database->setQuery("SELECT i.sectionid FROM #__content AS i, #__sections AS s WHERE i.sectionid=s.id AND i.id=$id");
			return $database->loadResult();
		}
		else return 0;
	}
	
	function getCategory ($id) {
		$database =& mamboDatabase::getInstance();
		$limit = $this->_category_limit;
		if (!$this->_category_status) {
			$database->setQuery("SELECT i.id, i.catid FROM #__content AS i, #__categories AS s WHERE i.catid=s.id ORDER BY i.id DESC LIMIT $limit");
			$categories = $database->loadObjectList();
			if ($categories) {
				foreach ($categories as $category) $this->_categories[$category->id] = $category->catid;
				$this->_category_status = count($categories);
			}
		}
		if ($this->_category_status) {
			if (isset($this->_categories[$id])) return $this->_categories[$id];
			if (count($this->_categories) < $limit) return 0;
			$database->setQuery("SELECT i.catid FROM #__content AS i, #__categories AS s WHERE i.catid=s.id AND i.id=$id");
			return $database->loadResult();
		}
		else return 0;
	}
	
}

?>
