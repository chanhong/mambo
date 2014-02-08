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

require_once (mamboCore::get('mosConfig_absolute_path').'/components/com_content/content.class.php');

class mosFrontPage extends mosDBTable {
	/** @var int Primary key */
	var $content_id=null;
	/** @var int */
	var $ordering=null;
	
	/**
	* @param database A database connector object
	*/
	function mosFrontPage( &$db ) {
		$this->mosDBTable( '#__content_frontpage', 'content_id', $db );
	}
}
?>
