<?php

// ensure this file is being included by a parent file
if (!defined( '_VALID_MOS' ) AND !defined('_JEXEC')) die( 'Direct Access to this location is not allowed.' );

class mosToolbar {
	var $act;
	var $task;
	// Create an instance, get the controlling parameters from the request
	function mosToolbar () {
		if ($this->act = mosGetParam ($_REQUEST, 'act', 'containers'));
		else $this->act = 'containers';
		if ($this->task = mosGetParam($_REQUEST, 'task', 'list'));
		else $this->task = 'list';
		$this->makeBar();
	}
	// create a toolbar based on the parameters found in $_REQUEST
	function makeBar () {
		$this->start();
		$act = $this->act;
		$call_check = array(&$this,$act);
		if (is_callable($call_check)) $this->$act();
		$this->finish();
	}
	// Any initial actions
	function start () {
		mosMenuBar::startTable();
	}
	// The following methods correspond exactly to the possible values
	// of 'act' in the request.  They in turn correspond to all the
	// possible options in the admin side drop down menu for Remository.
	function containers () {
		if ($this->task == 'add') $this->addMenu('Container');
		elseif ($this->task == 'edit') $this->editMenu('Container');
		else $this->listMenu('');
	}
	
	// The cancel option is always formed the same way
	function cancelButton () {
		mosMenuBar::custom( 'list', 'cancel.png', 'cancel_f2.png', 'Cancel', false );
	}
	// The menu for adding something is always the same apart from the text
	function addMenu ($entity) {
		mosMenuBar::save( 'save', 'Save '.$entity );
		$this->cancelButton();
	}
	// The menu for editing something is always the same apart from the text
	function editMenu ($entity) {
		mosMenuBar::save( 'save', 'Save '.$entity );
		$this->cancelButton();
	}
	// The menu for a list of items is always the same apart from the text
	function listMenu ($entity) {
		mosMenuBar::publishList( 'publish', 'Publish '.$entity );
		mosMenuBar::unpublishList( 'unpublish', 'UnPublish '.$entity );
		mosMenuBar::addNew( 'add', 'Add '.$entity );
		mosMenuBar::editList( 'edit', 'Edit '.$entity );
		mosMenuBar::deleteList( '', 'delete', 'Delete '.$entity );
	}
	// Any concluding actions
	function finish () {
		mosMenuBar::spacer();
		mosMenuBar::endTable();
	}

}

$toolbar = new mosToolbar();

?>
