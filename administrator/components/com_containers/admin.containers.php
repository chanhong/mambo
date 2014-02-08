<?php
/**
* @package Mambo
* @author Mambo Foundation Inc see README.php
* @copyright Mambo Foundation Inc.
* See COPYRIGHT.php for copyright notices and details.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see
* LICENSE.php
* Mambo is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; version 2 of the
* License.
* Date: 10 March 2006
* Original Script: psx-dude - psx-dude@psx-dude.net
**/

// Don't allow direct linking
if (!defined( '_VALID_MOS' )) die( 'Direct Access to this location is not allowed.' );

require_once($mosConfig_absolute_path.'/components/com_containers/containers.class.php');
require_once( $mainframe->getPath( 'admin_html' ) );

class containersAdminContainers extends mosComponentAdminControllers {
	var $parentid = 0;
	
	function getRequestData () {
		$this->parentid = mosGetParam($_REQUEST, 'parentid', 0);
	}
	
	function listTask () {
		// Get the search string that will constrain the list of containers displayed
		$search = trim( strtolower( mosGetParam( $_POST, 'search', '' ) ) );
		// Get the flag that tells us whether to continue to nested containers right down to the bottom
		$descendants = intval(mosGetParam($_POST, 'descendants', 0));
		// Create the container above our present position - might be degenerate
		$handler = mosContainerHandler::getInstance();
		$container =& $handler->getBasicContainer($this->parentid);
		// Get all the containers that are to be displayed
		if ($descendants) $folders = $container->getDescendants($search);
		else $folders = $container->getChildren(false,$search);
		// Generate a container list for user to select where to be
		$clist = $container->getSelectList('parentid', 'class="inputbox" size="1" onchange="document.adminForm.submit();"', false);
		// Create and activate a View object
		$view = $this->admin->newHTMLClassCheck ('listContainersHTML', $this, count($folders), $clist);
		$view->view(array_slice($folders,$this->admin->limitstart,$this->admin->limit), $descendants, $search);
	}
	
	function addTask () {
		// This is our new container - nothing much in it to start with
		$container =& new mosContainer();
		// Its parent is by default the situation we are in at present
		$container->parentid = $this->parentid;
		// Generate a container list so the user can change the parent
		$clist = $container->getSelectList('parentid', 'class="inputbox"', false);
		// Create and activate a View object
		$view = $this->admin->newHTMLClassCheck ('editContainersHTML', $this, 0, $clist);
		$view->view($container);
	}
	
	function editTask () {
		// Create a container object that will be filled with data from the DB using currid as key
		$handler = mosContainerHandler::getInstance();
		$container =& $handler->getContainer($this->admin->currid);
		$parent =& $container->getParent();
		// Generate a container list so the user can change the parent
		$clist = $parent->getSelectList('parentid', 'class="inputbox"', false, $container->id);
		// Create and activate a View object
		$view = $this->admin->newHTMLClassCheck ('editContainersHTML', $this, 0, $clist);
		$view->view($container);
	}
	
	function saveTask () {
		// Create a container object that will be filled with data from the DB using currid as key
		$handler = mosContainerHandler::getInstance();
	    $container =& $handler->getContainer($this->admin->currid);
	    // Clear tick box fields as nothing will be received if they are unticked
	    $container->published = 0;
	    // Add the new information from the form just submitted
	    $container->addPostData();
	    // By default, a new container is automatically published
	    if ($this->admin->currid == 0) $container->published = 1;
	    // Save the new information about the container to the database
	    $container->saveValues ();
		// Next we locate ourselves where this container has finished up and list containers
		$this->parentid = $container->parentid;
		$this->listTask();
	}
	
	function saveorderTask () {
		// Create a container object that will be filled with data from the DB using currid as key
		$handler = mosContainerHandler::getInstance();
	    $container =& $handler->getContainer($this->admin->currid);
	    // Reorder based on the first container in the list
	    $where = "parentid = ".$container->parentid;
	    $container->updateOrder($where, $this->admin->cfid, $this->admin->order);
	    $handler->resetData();
		$this->parentid = $container->parentid;
		$this->listTask();
	}
	
	function orderupTask () {
		// Create a container object that will be filled with data from the DB using currid as key
		$handler = mosContainerHandler::getInstance();
	    $container =& $handler->getContainer($this->admin->currid);
	    // Reorder based on the first container in the list
	    $where = "parentid = ".$container->parentid;
	    $container->move(-1, $where);
	    $handler->resetData();
		$this->parentid = $container->parentid;
		$this->listTask();
	}

	function orderdownTask () {
		// Create a container object that will be filled with data from the DB using currid as key
		$handler = mosContainerHandler::getInstance();
	    $container =& $handler->getContainer($this->admin->currid);
	    // Reorder based on the first container in the list
	    $where = "parentid = ".$container->parentid;
	    $container->move(+1, $where);
	    $handler->resetData();
		$this->parentid = $container->parentid;
		$this->listTask();
	}
	
	function deleteTask () {
		// In case the Javascript cannot do the check, ensure at least one item selected
		$this->admin->check_selection(_DOWN_SEL_FILE_DEL);
		// For each selected container, create an object then delete (will delete from DB)
		$dlist = array();
		$handler = mosContainerHandler::getInstance();
		foreach ($this->admin->cfid as $id) $dlist[] = $handler->getDescendantIDList($id);
		$deletelist = implode (',', $dlist);
		$mambothandler = mosMambotHandler::getInstance();
		$mambothandler->loadBotGroup('container');
		$messages = $mambothandler->trigger('preDelete', $deletelist);
		foreach ($messages as $message) if ($message) {
			// Create and activate a View object
			$view = $this->admin->newHTMLClassCheck ('messageContainersHTML', $this, 0, '');
			$view->view($container);
		}
		else {
			$mambothandler->trigger('doDelete', $deletelist);
			// Now show the list of containers again
			$this->listTask();
		}
	}
	
	function publishTask () {
		$this->publishToggle(1);
	}

	function unpublishTask () {
		$this->publishToggle(0);
	}
	
	function publishToggle ($publish) {
		// Check that one or more items have been selected (Javascript may not have run)
		$this->admin->check_selection(_DOWN_PUB_PROMPT.($publish ? 'publish' : 'unpublish'));
	    mosContainer::togglePublished($this->admin->cfid,$publish);
	    // The file/folder counts only include published items, so recalculate
		$this->repository->resetCounts(array());
		// List out the containers again
		$this->listTask();
	}


}
	
new mosComponentAdminManager('Containers');

?>
