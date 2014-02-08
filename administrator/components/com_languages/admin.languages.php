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

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

// ensure user has access to this component
if (!$acl->acl_check( 'administration', 'manage', 'users', $my->usertype, 'components', 'com_languages' ) ) {
    mosRedirect( 'index2.php', T_('You are not authorized to view this resource.') );
}

require_once(mamboCore::get('rootPath').'/includes/phpgettext/error.php');
require_once(mamboCore::get('rootPath').'/includes/phpgettext/phpgettext.catalog.php');
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'admin.languages.class.php');

$include_path = ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.dirname(__FILE__));

$request =& Request::getInstance('com_languages');
$session =& $request->session();
if (isset($session['mosmsg'])) {
    $request->set('mosmsg',  $session['mosmsg']);
    unset($session['mosmsg']);
}

$lang = $session['lang'] = mosGetParam($_POST, 'lang', isset($session['lang']) ? $session['lang'] : mamboCore::get('mosConfig_locale'));
$mamboLanguage =& new mamboLanguage($lang);
$languages = $mamboLanguage->getLanguages();
$task = mosGetParam($_REQUEST, 'task', 'index');
$act  = mosGetParam($_REQUEST, 'act' , 'language');
$search  = trim(mosGetParam($_POST, 'search' , ''));

$request->set('task',  $task);
$request->set('act',  $act);
$request->set('lang', $lang);
$request->set('languages', $languages);
$request->set('search', $search);


$renderer =& Renderer::getInstance();
foreach ($request->get() as $key => $value) {
    $renderer->addvar($key, $value);
}

$controller = new Controller('com_languages');
$controller->forward($task);
ini_set('include_path', $include_path);

#dump($_REQUEST);

?>
