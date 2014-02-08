<?php
/**
* @package Mambo
* @subpackage Newsfeeds
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

class mosNewsFeed extends mosDBTable {
/** @var int Primary key */
	var $id=null;
/** @var int */
	var $catid=null;
/** @var string */
	var $name=null;
/** @var string */
	var $link=null;
/** @var string */
	var $filename=null;
/** @var int */
	var $published=null;
/** @var int */
	var $numarticles=null;
/** @var int */
	var $cache_time=null;
/** @var int */
	var $checked_out=null;
/** @var time */
	var $checked_out_time=null;
/** @var int */
	var $ordering=null;

/**
* @param database A database connector object
*/
	function mosNewsFeed( &$db ) {
		$this->mosDBTable( '#__newsfeeds', 'id', $db );
	}

}
?>