<?php
/**
* @package Mambo
* @subpackage Banners
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

class mosBannerClient extends mosDBTable {
	var	$cid = null;
	var $name = "";
	var $contact = "";
	var $email = "";
	var $extrainfo = "";
	var $checked_out = 0;
	var $checked_out_time = 0;
	var $editor	= "";
	
	function mosBannerClient( &$_db ) {
		$this->mosDBTable( '#__bannerclient', 'cid', $_db );
	}
	
	function check() {
		// check for valid client name
		if (trim($this->name == "")) {
			$this->_error = T_('You must select a name for the client.');
			return false;
		}
		
		// check for valid client contact
		if (trim($this->contact == "")) {
			$this->_error = T_('You must select a contact for the client.');
			return false;
		}
		
		// check for valid client email
		if ((trim($this->email == "")) || (preg_match("/[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}/", $this->email )==false)) {
			$this->_error = T_('You must select a valid email for the client.');
			return false;
		}
		return true;
	}
}

/**
* @package Mambo
*/
class mosBanner extends mosDBTable {
	/** @var int */
	var $bid				= null;
	/** @var int */
	var $cid				= null;
	/** @var string */
	var $type				= "";
	/** @var string */
	var $name				= "";
	/** @var int */
	var $imptotal			= 0;
	/** @var int */
	var $impmade			= 0;
	/** @var int */
	var $clicks				= 0;
	/** @var string */
	var $imageurl			= "";
	/** @var string */
	var $clickurl			= "";
	/** @var date */
	var $date				= null;
	/** @var int */
	var $showBanner			= 0;
	/** @var int */
	var $checked_out		= 0;
	/** @var date */
	var $checked_out_time	= 0;
	/** @var string */
	var $editor				= "";
	/** @var string */
	var $custombannercode	= "";
	
	function mosBanner( &$_db ) {
		$this->mosDBTable( '#__banner', 'bid', $_db );
		$this->set("date",date("Y-m-d G:i:s"));
	}
	
	function clicks() {
		$this->_db->setQuery( "UPDATE #__banner SET clicks=(clicks+1) WHERE bid=$this->bid" );
		$this->_db->query();
	}
	
	function check() {
		// check for valid client id
		if (is_null($this->cid) || $this->cid == 0) {
			$this->_error = T_('You must select a client.');
			return false;
		}
		
		if(trim($this->name) == "") {
			$this->_error = T_('You must select a name for the banner.');
			return false;
		}
		
		if(trim($this->imageurl) == "" && trim($this->custombannercode) == "") {
			$this->_error = T_('You must select a image for the banner.');
			return false;
		}
		if(trim($this->clickurl) == "" && trim($this->custombannercode) == "") {
			$this->_error = T_('You must select a URL/Custom banner code for the banner.');
			return false;
		}
		
		return true;
	}
}
?>
