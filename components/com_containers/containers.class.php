<?php

class mosContainer extends mosTableEntry {
	/** @var int ID for container record in database */
	public $id = 0;
	/** @var int Ordering */
	public $ordering = 0;
	/** @var string Window title */
	public $windowtitle = '';
	/** @var string Key words */
	public $keywords = '';
	/** @var int ID of parent container in database if a folder */
	public $parentid = 0;
	/** @var string Name of container */
	public $name = '';
	/** @var string Container description */
	public $description='';
	/** @var bool Is the container published? */
	public $published=false;
	/** @var string Icon - not sure how this is used */
	public $icon='';
	/** @var bool Has all the data been loaded (rather than just the basics) */
	protected $loaded = 0;
	/** @var array Child containers */
	protected $children = array();
	/** @var bool When true, the item is marked as trash */
	protected $isTrash = false;

	function tableName () {
		return '#__containers';
	}

	function notSQL () {
		return array ('id', 'loaded', 'children', 'database');
	}

	function load () {
		$database = mamboDatabase::getInstance();
		if ($this->loaded == 0 AND $this->id) {
			$database->setQuery("SELECT * FROM #__containers WHERE id=$this->id");
			$database->loadObject($this);
			$this->loaded = 1;
		}
	}

	function saveValues () {
		$database = mamboDatabase::getInstance();
		$this->prepareValues();
		if ($this->id == 0) $database->doSQL($this->insertSQL());
		else $database->doSQL($this->updateSQL());
	}

	function trash () {
		$this->isTrash = true;
	}

	function isTrash () {
		return $this->isTrash;
	}

	function addChild ($id) {
		$this->children[] = $id;
	}

	function deleteAll () {
		$folders = $this->getChildren(false);
		foreach ($folders as $folder) $folder->deleteAll ();
//      Need to delete things that are registered with this
		$this->trash();
	}

	function setMetaData () {
		$mainframe = mosMainFrame::getInstance();
		$mainframe->prependMetaTag('description', strip_tags($this->name));
		if ($this->keywords) $mainframe->prependMetaTag('keywords', $this->keywords);
		else $mainframe->prependMetaTag('keywords', $this->name);
	}

	function isCategory () {
		if ($this->parentid == 0) return true;
		else return false;
	}

	function getCategoryName ($showself=false) {
		$category = $this->getCategory();
		if ($this->parentid OR $showself) return $category->name;
		return '*';
    }

    function getCategory () {
		$container =& $this;
		while ($container->parentid) $container =& $container->getParent();
		return $container;
	}

    function getFamilyNames ($include=false) {
    	$names = $include ? '/'.$this->name : '';
    	$generation = 1;
    	$ancestor =& $this;
    	while ($ancestor->parentid AND $generation < 3) {
    		$ancestor =& $ancestor->getParent();
    		$generation++;
    		$names = '/'.$ancestor->name.$names;
    	}
    	if ($ancestor->parentid) $names = '..'.$names;
    	if ($names) return $names;
    	return '-';
    }

	function &addChildren (&$descendants, $published=true, $search='', $recurse=false) {
		$children = array();
		$handler = mosContainerHandler::getInstance();
		foreach ($this->children as $i) {
			$container =& $handler->getBasicContainer($i);
			if ($published AND $container->published == 0) continue;
			if ($search AND strpos(strtolower($container->name), strtolower($search)) === false) continue;
			$children[] =& $container;
			$descendants[] =& $container;
		}
		if ($recurse) foreach ($children as $child) $child->addChildren ($descendants, $published, $search, $recurse);
		return $children;
	}

	function &getChildren ($published=true, $search='') {
		$children = array();
		$this->addChildren($children, $published, $search);
		return $children;
	}

	function &getDescendants ($search='') {
		$descendants = array();
		$this->addChildren ($descendants, false, $search, true);
		return $descendants;
	}

	function &getParent () {
		$handler = mosContainerHandler::getInstance();
		$parent =& $handler->getBasicContainer($this->parentid);
		return $parent;
	}

	function getSelectList ($type, $parm, $published, $notThis=0) {
		$selector[] = mosHTML::makeOption(0,T_('No parent'));
		$handler = mosContainerHandler::getInstance();
		foreach ($handler->getCategories() as $category) $category->addSelectList('',$selector,$notThis,$published);
		return mosHTML::selectList( $selector, $type, $parm, 'value', 'text', $this->id );
	}

	function addSelectList ($prefix, &$selector, $notThis, $published) {
		if (($notThis == 0) OR ($this->id != $notThis)) $selector[] = mosHTML::makeOption($this->id, $prefix.htmlspecialchars($this->name));
		foreach ($this->getChildren($published) as $container) $container->addSelectList($prefix.$this->name.'/',$selector,$notThis,$published);
	}

	function getURL () {
	}

	function setPathway () {
	}

	function &getIcons () {
		$iconList = '';
		$live_site = mamboCore::get('mosConfig_live_site');
		$iconDir = new mosDirectory (mamboCore::get('mosConfig_absolute_path').'/administrator/components/com_containers/images/folder_icons');
		$files = $iconDir->listAll();
		$ss = 0;
		foreach ($files as $file) {
			$iconList.="\n<a href=\"JavaScript:paste_strinL('{$file}')\" onmouseover=\"window.status='{$file}'; return true\"><img src=\"{$live_site}/administrator/components/com_containers/images/folder_icons/{$file}\" width=\"32\" height=\"32\" border=\"0\" alt=\"{$file}\" /></a>&nbsp;&nbsp;";
	        $ss++;
			if ($ss % 10 == 0) $iconList.="<br/>\n";
		}
		return $iconList;
	}


	function togglePublished ($idlist, $value) {
		$cids = implode( ',', $idlist );
		$sql = "UPDATE #__downloads_containers SET published=$value". "\nWHERE id IN ($cids)";
		remositoryRepository::doSQL ($sql);
	}

	function mosImageURL($imageName, $width=32, $height=32) {
		$live_site = mamboCore::get('mosConfig_live_site');
		$element = '<img src="';
		$element .= $live_site.'/administrator/components/com_containers/images/'.$imageName;
		$element .= '" width="';
		$element .= $width;
		$element .= '" height="';
		$element .= $height;
		$element .= '" border="0" align="middle" alt="';
		$element .= $imageName;
		$element .= '"/>';
		return $element;
	}

}

class mosContainerHandler {
	var $rows = array();
	var $links = array();
	var $anchor = '';

	function mosContainerHandler () {
		$this->resetData();
	}

	static function &getInstance () {
		static $instance;
		if (!is_object($instance)) $instance = new mosContainerHandler();
		return $instance;
	}
	
	function resetData () {
		unset ($this->rows, $this->links, $this->anchor);
		$database = mamboDatabase::getInstance();
		$this->anchor =& new mosContainer();
		$sql = 'SELECT id, parentid, name, published, ordering FROM #__containers ORDER BY ordering, name';
		$this->rows =& $database->doSQLget($sql, 'mosContainer');
		foreach ($this->rows as $i=>$row) $this->links[$row->id] = $i;
		foreach ($this->rows as $row) if ($row->parentid) {
			$parent =& $this->rows[$this->links[$row->parentid]];
			$parent->addChild($row->id);
		}
		else $this->anchor->addChild($row->id);
	}

	function &getBasicContainer ($id) {
		if ($id == 0) return $this->anchor;
		return $this->rows[$this->links[$id]];
	}

	function &getContainer ($id) {
		global $database;
		$result =& $this->getBasicContainer($id);
		$result->load();
		return $result;
	}

	function &getCategories ($published = false, $search = null) {
		$categories = array();
		foreach ($this->anchor->getChildren() as $category) {
			if ($published AND $category->published == 0) continue;
			if ($search AND strpos(strtolower($category->name), strtolower($search)) === false) continue;
			$categories[] = $category;
		}
		return $categories;
	}

	function getDescendantIDList ($id, $search='') {
		$top = $this->getBasicContainer ($id);
		$descendants =& $top->getDescendants ($search);
		$list = $id;
		foreach ($descendants as $descendant) $list .= ','.$descendant->id;
		return $list;
	}

	function getSelectList ($allowTop, $default, $type, $parm, &$user) {
		if ($allowTop) $selector[] = mosHTML::makeOption(0,_DOWN_NO_PARENT);
		foreach ($this->getCategories() as $category) $category->addSelectList('', $selector, null, $user);
		if (isset($selector)) return mosHTML::selectList( $selector, $type, $parm, 'value', 'text', $default );
		else return '';
	}

	// Only needed for testing
	function displayChildren (&$container) {
		echo '<br />'.$container->name.' has children:<br />';
		foreach ($container->children as $child) echo $child->name.' whose parent is '.$child->parent->name.'<br />';
		foreach ($container->children as $child) displayChildren ($child);
	}

	function markTrash () {
		$database = mamboDatabase::getInstance();
		foreach ($this->rows as $row) if ($row->isTrash()) $trash[] = $row->id;
		if (isset($trash)) {
			$trashlist = implode (',', $trash);
			$sql = "DELETE FROM #__containers WHERE id IN ($trashlist)";
			$database->doSQL($sql);
		}
	}

}

?>
