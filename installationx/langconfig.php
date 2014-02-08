<?php
/**
* Install instructions
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
*/ 

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

$lang = "en";
$charset = "utf-8";
$text_direction = "ltr";
$gettext =& phpgettext();
$gettext->debug       = '0';
$gettext->has_gettext = '0';
$gettext->setlocale($lang);
$gettext->bindtextdomain($lang, 'language/');
$gettext->textdomain($lang);
?>