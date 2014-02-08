<?php
/**
* @package Mambo
* @subpackage Languages
* @author Mambo Foundation Inc see README.php
* @copyright Mambo Foundation Inc.
* See COPYRIGHT.php for copyright notices and details.
* @license GNU/GPL Version 2, see LICENSE.php
* Mambo is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; version 2 of the License.
*/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
class cancelAction extends Action
{
    function execute(&$controller, &$request)
    {
        if (isset($_POST['catalogs'])) {
            $_REQUEST['act'] = 'language';
        }
        $controller->redirect('index', mosGetParam($_REQUEST, 'act', $act));
    }
}

?>