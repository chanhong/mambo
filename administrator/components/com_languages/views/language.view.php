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
class languageView extends View
{
    function render(&$renderer, &$request)
    {
        $renderer->addvar('rows', array_values($request->get('languages')));
        $renderer->addvar('content', $renderer->fetch('languages.tpl.php'));
        $renderer->display('form.tpl.php');
    }
}
?>