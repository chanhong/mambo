<?php
/**
* @package Mambo
* @subpackage Comment
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

class moscomment extends mosDBTable {
	/** @var int Primary key */
	var $id=null;
	/** @var int */
	var $articleid=null;
	/** @var varchar */
	var $ip=null;
	/** @var varchar */
	var $name=null;
	/** @var text */
	var $comments=null;
	/** @var datetime */
	var $startdate=null;
	/** @var tinyint */
	var $published=null;

	/**
	* @param database
	* A database connector object
	*/
  function moscomment( &$db ) {
    $this->mosDBTable( '#__comment', 'id', $db );
  }
}
?>