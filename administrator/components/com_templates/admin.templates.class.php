<?php
/**
* @package Mambo
* @subpackage Templates
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

class mosTemplatePosition extends mosDBTable {
	var $id=null;
	var $position=null;
	var $description=null;

	function mosTemplatePosition() {
	    global $database;
	    $this->mosDBTable( '#__template_positions', 'id', $database );
	}
}

?>
