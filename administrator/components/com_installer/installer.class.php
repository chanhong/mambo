<?php
/**
* @package Mambo
* @subpackage Installer
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

class mosCommonInstallXML extends mosXMLDescription {
    var $type = '';
    var $name = '';
    var $special_attr = '';
    var $group = '';
    var $userdir = '';
    var $admindir = '';
    var $files = array();
    var $rem_files = array();
    var $images = array();
    var $admin_files = array();
    var $admin_images = array();
    var $queryid = 0;
    var $queries = array();
    var $mainmenu = '';
    var $subact = '';
    var $submenus = array();
    var $submenuordering = 0;
    var $special_filetitle = '';
    var $isspecial = false;
    var $special_file = null;
    var $installfile = '';
    var $uninstallfile = '';
    var $client = '';
    var $media = array();
    var $css = array();
    var $language_attrs = '';

    var $user_dir = '';
    var $admin_dir = '';

    function tagNestIs ($next, $last=null) {
        if ($next == $this->opentags[1] AND ($last == null OR $last == $this->opentags[2])) return true;
        else return false;
    }

    function element_mosinstall ($attrs) {
        if (isset($attrs['TYPE'])) {
            $this->type = $attrs['TYPE'];
            $method = 'init_'.$attrs['TYPE'];
            $setup = array (&$this, $method);
            if (is_callable($setup)) $this->$method($attrs);
            else $this->errors->addErrorDetails(sprintf(T_('XML error in %s: no handler %s'), $this->xmlfile, $method), _MOS_ERROR_FATAL);
        }
        else $this->errors->addErrorDetails(sprintf(T_('XML error in %s: mosinstall has no type'), $this->xmlfile), _MOS_ERROR_FATAL);
    }

    function end_element_mosinstall () {
        if ($this->name = $this->getName($this->type));
        elseif ($this->type != 'params') $this->errors->addErrorDetails(sprintf(T_('XML Error in %s: install has no name'), $this->xmlfile), _MOS_ERROR_FATAL);
    }

    function element_filename ($attrs) {
        if ($this->type == 'language') $this->language_attrs = $attrs;
        elseif (isset($attrs[$this->special_attr])) $this->special_filetitle = $attrs[$this->special_attr];
    }

    function end_element_filename () {
        if ($this->special_filetitle) $this->special_file = array($this->special_filetitle, $this->chardata);
        $this->special_filetitle = '';
        if ($this->type == 'language') $this->files[] = array($this->language_attrs, $this->chardata);
        elseif ($this->tagNestIs('FILES', 'MOSINSTALL')) $this->files[] = $this->chardata;
        elseif ($this->tagNestIs('REMOVE_FILES', 'MOSINSTALL')) $this->rem_files[] = $this->chardata;
        elseif ($this->tagNestIs('IMAGES', 'MOSINSTALL')) $this->images[] = $this->chardata;
        elseif ($this->tagNestIs('FILES', 'ADMINISTRATION')) $this->admin_files[] = $this->chardata;
        elseif ($this->tagNestIs('IMAGES', 'ADMINISTRATION')) $this->admin_images[] = $this->chardata;
        elseif ($this->tagNestIs('MEDIA', 'MOSINSTALL')) $this->media[] = $this->chardata;
        elseif ($this->tagNestIs('CSS', 'MOSINSTALL')) $this->css[] = $this->chardata;
    }

    function element_query ($attrs) {
        if (isset($attrs['ID'])) $this->queryid = $attrs['ID'];
    }

    function element_menu ($attrs) {
        if ($this->type != 'component') {
            $this->errors->addErrorDetails(sprintf(T_('XML error in %s: MENU found, but this is not component XML'), $this->xmlfile), _MOS_ERROR_WARNING);
            return;
        }
        if ($this->tagNestIs('SUBMENU', 'ADMINISTRATION')) {
            if (isset($attrs['ACT'])) $this->subact = '&act='.$attrs['ACT'];
            elseif (isset($attrs['TASK'])) $this->subact = '&task='.$attrs['TASK'];
            elseif (isset($attrs['LINK'])) $this->subact = $attrs['LINK'];
        }
    }

    function end_element_menu (){
        if ($this->tagNestIs('ADMINISTRATION')) $this->mainmenu = $this->chardata;
        elseif ($this->tagNestIs('SUBMENU', 'ADMINISTRATION')) $this->submenus[] = array ($this->chardata, $this->subact);
        $this->subact = '';
    }

    function end_element_installfile () {
        $this->installfile = $this->chardata;
    }

    function end_element_uninstallfile (){
        $this->uninstallfile = $this->chardata;
    }

    function init_component ($attrs) {
    }

    function init_module ($attrs) {
        $this->special_attr = 'MODULE';
        if (isset($attrs['CLIENT'])) $this->client = $attrs['CLIENT'];
    }

    function init_mambot ($attrs) {
        $this->special_attr = 'MAMBOT';
        if (isset($attrs['GROUP'])) {
            $this->group = $attrs['GROUP'];
        }
        else $this->errors->addErrorDetails(sprintf(T_('XML Error in %s: Mambot does not have a group specified'), $this->xmlfile), _MOS_ERROR_FATAL);
    }

    function init_template ($attrs) {
        if (isset($attrs['CLIENT'])) $this->client = $attrs['CLIENT'];
    }

    function init_language ($attrs) {
    }

    function init_params ($attrs) {
    }

    function init_include($attrs) {
    }

    function init_parameter($attrs) {
    }

    function init_patch ($attrs) {
    }

}

class mosInstallXML extends mosCommonInstallXML {

    function install () {
    	if (ini_get('safe_mode')) {
            $this->errors->addErrorDetails(sprintf(T_('Your system is running in Safe Mode component install is aborting!')), _MOS_ERROR_FATAL);
            return;
        }
        if ($this->errors->getMaxLevel() >= _MOS_ERROR_FATAL) return;
        $method = 'install_'.$this->type;
        $setup = array (&$this, $method);
        if (is_callable($setup)) $this->$method();
        else $this->errors->addErrorDetails(sprintf(T_('XML error in %s: no installer %s'), $this->xmlfile, $method), _MOS_ERROR_FATAL);
        if ($this->errors->getMaxLevel() >= _MOS_ERROR_FATAL) {
            $killmethod = 'kill_'.$this->type;
            $killer = array (&$this, $killmethod);
            if (is_callable($killer)) $this->$killmethod();
        }
    }

    function end_element_query () {
        if ($this->tagNestIs('QUERIES', 'INSTALL')) $this->queries[] = array ($this->chardata, $this->queryid);
        $this->queryid = 0;
    }

    function createDirectories ($name) {
        if ($this->admin_dir) {
            $adirectory = new mosDirectory ($this->admin_dir);
            $type = $this->type;
            if (!$adirectory->createFresh()) {
                $this->errors->addErrorDetails(sprintf(T_('Installer error with %s: unable to create admin directory for %s %s'), $this->xmlfile, $type, $name), _MOS_ERROR_SEVERE);
                return false;
            }
        }
        if ($this->user_dir) {
            $udirectory = new mosDirectory ($this->user_dir);
            if (!$udirectory->createFresh()) {
                $this->errors->addErrorDetails(sprintf(T_('Installer error with %s: unable to create user directory for %s %s'), $this->xmlfile, $type, $name), _MOS_ERROR_SEVERE);
                return false;
            }
            return true;
        }
    }

    function createComponentMenu ($item, $name, $toplevel=0) {
        $text = $item[0];
        $database =& mamboDatabase::getInstance();
        $component =& new mosComponent($database);
        $component->name = $component->admin_menu_alt = $text;
        if ($toplevel == 0) $component->link = "option=$name";
        $component->menuid = 0;
        $component->parent = $toplevel;
        $component->iscore = 0;
        $component->admin_menu_link = "option=$name";
        if ($toplevel AND ($linkdetail = $item[1])) {
            if ($linkdetail[0] == '&') $component->admin_menu_link .= $linkdetail;
            else $component->admin_menu_link = $linkdetail;
        }
        $component->option = $name;
        $component->ordering = $toplevel ? $this->submenuordering++ : 0;
        $component->admin_menu_img = 'js/ThemeOffice/component.png';
        $component->params = '';
        if ($component->store()) return $component->id;
        $this->errors->addErrorDetails(sprintf(T_('Installer error with %s: writing component menu SQL error %s'), $this->xmlfile, $database->stderr(true)), _MOS_ERROR_FATAL);
        return 0;
    }

    function install_component (){
        $com_name = 'com_'.str_replace(' ', '', strtolower($this->name));
        $this->user_dir = mosPathName(mamboCore::get('mosConfig_absolute_path').'/components/'.$com_name);
        $this->admin_dir = mosPathName(mamboCore::get('mosConfig_absolute_path').'/administrator/components/'.$com_name);
        $database =& mamboDatabase::getInstance();
        $sql = "SELECT COUNT(id) FROM #__components WHERE `option`='$com_name'";
        $database->setQuery($sql);
		if ($count = $database->loadResult()) {
            $this->errors->addErrorDetails(sprintf(T_('Installer error with %s: %s %s already installed'), $this->xmlfile, 'component', $com_name), _MOS_ERROR_WARN);
            return;
		}
        if (!$this->createDirectories($com_name)) return;
        $fmanager =& mosFileManager::getInstance();
        $here = mosPathName(dirname($this->xmlfile));
        $fmanager->forceCopy($this->xmlfile, $this->admin_dir.basename($this->xmlfile));
        foreach ($this->files as $ufile) $fmanager->forceCopy($here.$ufile, $this->user_dir.$ufile);
        foreach ($this->images as $uimage) $fmanager->forceCopy($here.$uimage, $this->user_dir.$uimage);
        foreach ($this->admin_files as $afile) $fmanager->forceCopy($here.$afile, $this->admin_dir.$afile);
        foreach ($this->admin_images as $aimage) $fmanager->forceCopy($here.$aimage, $this->admin_dir.$aimage);
        if ($this->installfile) $fmanager->forceCopy($here.$this->installfile, $this->admin_dir.$this->installfile);
        if ($this->uninstallfile) $fmanager->forceCopy($here.$this->uninstallfile, $this->admin_dir.$this->uninstallfile);
        foreach ($this->queries as $query) {
            $database->setQuery($query[0]);
            if (!$database->query()) {
                $this->errors->addErrorDetails(sprintf(T_('Installer error with %s: %s %s SQL error %s'), $this->xmlfile, 'component', $com_name, $database->stderr(true)), _MOS_ERROR_FATAL);
            }
        }
        if ($this->mainmenu) {
            $topid = $this->createComponentMenu (array($this->mainmenu), $com_name);
            foreach ($this->submenus as $submenu) {
                $this->createComponentMenu ($submenu, $com_name, $topid);
            }
        }
        if ($this->installfile) {
            $ifile = mosPathName($this->admin_dir).$this->installfile;
            if (is_file($ifile)) {
                global $mosConfig_absolute_path, $mosConfig_live_site, $acl, $database;
                require_once($ifile);
                $_ret = com_install();
				if ($_ret) {
				    // convert the first character of the returned string to be represented by an integer
				    $_type = intval(bin2hex($_ret{0}))-intval(bin2hex(_MOS_ERROR_INFORM));
				    switch ($_type) {
				    case _MOS_ERROR_INFORM:
				    case _MOS_ERROR_WARN:
				    case _MOS_ERROR_SEVERE:
				    case _MOS_ERROR_FATAL:
				        //strip off the first character
				        $_ret = substr($_ret, 1);
				        break;
				    default:
				        $_type = _MOS_ERROR_INFORM;
				        break;
				    }
				    if ($_type < _MOS_ERROR_FATAL) {
				        $this->errors->addErrorDetails(sprintf(T_('%s'), $_ret), $_type);
				    }else {
				        $this->errors->addErrorDetails(sprintf(T_('The com_install() function for component "%s" aborted with: %s'), $com_name, $_ret), $_type);
				    }
				}
            }
        }
        $this->errors->addErrorDetails($this->getDescription('component'), _MOS_ERROR_INFORM);
    }

    function install_module () {
        if (!is_array($this->special_file)) {
            $this->errors->addErrorDetails(sprintf(T_('Installer error with %s: no file identified as the %s'), $this->xmlfile, 'module'), _MOS_ERROR_FATAL);
            return;
        }
        $special = $this->special_file[0];
        $database =& mamboDatabase::getInstance();
        $client_id = $this->client == 'administrator' ? 1 : 0;
        $sql = "SELECT COUNT(id) FROM #__modules WHERE module='$special' AND client_id='$client_id'";
        $database->setQuery($sql);
        if ($database->loadResult()) {
            $this->errors->addErrorDetails(sprintf(T_('Installer error with %s: %s %s already installed'), $this->xmlfile, 'module', $com_name), _MOS_ERROR_FATAL);
            return;
        }
        if ($client_id) $this->user_dir = mosPathName(mamboCore::get('mosConfig_absolute_path').'/administrator/modules/');
        else $this->user_dir = mosPathName(mamboCore::get('mosConfig_absolute_path').'/modules');
        $fmanager =& mosFileManager::getInstance();
        $here = mosPathName(dirname($this->xmlfile));
        $fmanager->forceCopy($this->xmlfile, $this->user_dir.basename($this->xmlfile));
        foreach ($this->files as $ufile) $fmanager->forceCopy($here.$ufile, $this->user_dir.$ufile);
        foreach ($this->images as $uimage) $fmanager->forceCopy($here.$uimage, $this->user_dir.$uimage);
        $module =& new mosModule($database);
        $module->title = $this->name;
        $module->ordering = 99;
        $module->position = 'left';
        $module->showtitle = 1;
        $module->iscore = 0;
        $module->access = $client_id ? 99 : 0;
        $module->client_id = $client_id;
        $module->module = $special;
        $module->content = '';   // double check the last chance at wush to be here at SG new SVN
        $module->params = '';
        $module->store();
        if ($module->store()) $this->errors->addErrorDetails($this->getDescription('module'), _MOS_ERROR_INFORM);
        else {
            $this->errors->addErrorDetails(sprintf(T_('Installer error with %s: %s %s SQL error %s'), $this->xmlfile, 'mambot', $this->name, $database->stderr(true)), _MOS_ERROR_FATAL);
        }
        
        $database->setQuery("INSERT INTO #__modules_menu VALUES ('$module->id', 0)");
        if (!$database->query()) {
            $this->errors->addErrorDetails(sprintf(T_('Installer error with %s: %s %s SQL error %s'), $this->xmlfile, 'module', $this->name, $database->stderr(true)), _MOS_ERROR_FATAL);
        }
        //$this->errors->addErrorDetails($this->getDescription('module'), _MOS_ERROR_INFORM);
    }

    function install_mambot () {
        if (!is_array($this->special_file)) {
            $this->errors->addErrorDetails(sprintf(T_('Installer error with %s: no file identified as the %s'), $this->xmlfile, 'mambot'), _MOS_ERROR_FATAL);
            return;
        }
        $database =& mamboDatabase::getInstance();
        $name = $this->name;
        $sql = "SELECT COUNT(id) FROM #__mambots WHERE element='$name'";
        $database->setQuery($sql);
        if ($database->loadResult()) {
            $this->errors->addErrorDetails(sprintf(T_('Installer error with %s: %s %s already installed'), $this->xmlfile, 'mambot', $this->name), _MOS_ERROR_FATAL);
            return;
        }
        $this->user_dir = mosPathName(mamboCore::get('mosConfig_absolute_path').'/mambots/'.$this->group);
        $fmanager =& mosFileManager::getInstance();
        $here = mosPathName(dirname($this->xmlfile));
        $fmanager->forceCopy($this->xmlfile, $this->user_dir.basename($this->xmlfile));
        foreach ($this->files as $ufile) $fmanager->forceCopy($here.$ufile, $this->user_dir.$ufile);
        foreach ($this->images as $uimage) $fmanager->forceCopy($here.$uimage, $this->user_dir.$uimage);
        $mambot =& new mosMambot($database);
        $mambot->name = $this->name;
        $mambot->ordering = 0;
        $mambot->folder = $this->group;
        $mambot->iscore = 0;
        $mambot->access = 0;
        $mambot->client_id = 0;
        $mambot->element = $this->special_file[0];
        $mambot->params = '';
        if ($mambot->store()) $this->errors->addErrorDetails($this->getDescription('mambot'), _MOS_ERROR_INFORM);
        else {
            $this->errors->addErrorDetails(sprintf(T_('Installer error with %s: %s %s SQL error %s'), $this->xmlfile, 'mambot', $this->name, $database->stderr(true)), _MOS_ERROR_FATAL);
        }
    }

    function install_template () {
        $client_id = $this->client == 'administrator' ? 1 : 0;
        if ($client_id) $this->user_dir = mosPathName(mamboCore::get('mosConfig_absolute_path').'/administrator/templates/'.str_replace(' ', '_', strtolower($this->name)));
        else $this->user_dir = mosPathName(mamboCore::get('mosConfig_absolute_path').'/templates/'.str_replace(' ', '_', strtolower($this->name)));
        if (file_exists($this->user_dir)) {
            $this->errors->addErrorDetails(sprintf(T_('Installer error with %s: %s %s already installed'), $this->xmlfile, 'template', $this->name), _MOS_ERROR_FATAL);
            return;
        }
        $fmanager =& mosFileManager::getInstance();
        $here = mosPathName(dirname($this->xmlfile));
        $fmanager->forceCopy($this->xmlfile, $this->user_dir.basename($this->xmlfile));
        foreach ($this->files as $ufile) $fmanager->forceCopy($here.$ufile, $this->user_dir.$ufile);
        foreach ($this->images as $uimage) $fmanager->forceCopy($here.$uimage, $this->user_dir.$uimage);
        foreach ($this->css as $acss) $fmanager->forceCopy($here.$acss, $this->user_dir.$acss);
        $mediadir = mamboCore::get('mosConfig_absolute_path').'/images/stories/';
        foreach ($this->media as $simage) $fmanager->forceCopy($here.$simage, $mediadir.$simage);
        $this->errors->addErrorDetails($this->getDescription('template'), _MOS_ERROR_INFORM);
    }

    function install_language () {
        $this->user_dir = mosPathName(mamboCore::get('mosConfig_absolute_path').'/language');
        $fmanager =& mosFileManager::getInstance();
        $here = mosPathName(dirname($this->xmlfile));
        $fmanager->forceCopy($this->xmlfile, $this->user_dir.basename($this->xmlfile));
        $lang =  substr(basename($this->xmlfile), 0, -4);
        foreach ($this->files as $ufile) {
            $file = str_replace('language', '', $ufile[1]);
            $fmanager->forceCopy($here.$file, $this->user_dir.$file);
        }
        $this->errors->addErrorDetails($this->getDescription('language'), _MOS_ERROR_INFORM);
    }

    function install_include () {
        $this->user_dir = mosPathName(mamboCore::get('mosConfig_absolute_path').'/includes');
        $this->admin_dir = mosPathName(mamboCore::get('mosConfig_absolute_path').'/administrator/includes');
        $fmanager =& mosFileManager::getInstance();
        $here = mosPathName(dirname($this->xmlfile));
        foreach ($this->files as $ufile) $fmanager->lightCopy($here.$ufile, $this->user_dir.$ufile);
        foreach ($this->admin_files as $afile) $fmanager->lightCopy($here.$afile, $this->admin_dir.$afile);
    }

    function install_parameter () {
        $database =& mamboDatabase::getInstance();
        $name = $this->name;
        $sql = "SELECT COUNT(id) FROM #__parameters WHERE param_name='$name'";
        $database->setQuery($sql);
        if ($database->loadResult()) {
            $this->errors->addErrorDetails(sprintf(T_('Installer error with %s: %s %s already installed'), $this->xmlfile, 'parameter', $this->name), _MOS_ERROR_FATAL);
            return;
        }
        $this->user_dir = mosPathName(mamboCore::get('mosConfig_absolute_path').'/parameters/');
        $fmanager =& mosFileManager::getInstance();
        $xmlfile = $this->xmlfile;
        $filexmlfile = basename($xmlfile);
        $fmanager->forceCopy($xmlfile, $this->user_dir.basename($filexmlfile));
        $sql = "INSERT INTO #__parameters (param_name, param_file, param_version, params) VALUES ('$name', '$filexmlfile', '', '')";
        $database->setQuery($sql);
        if (!$database->query()) {
            $this->errors->addErrorDetails(sprintf(T_('Installer error with %s: %s %s SQL error %s'), $xmlfile, 'parameter', $name, $database->stderr(true)), _MOS_ERROR_FATAL);
        }
        $this->errors->addErrorDetails($this->getDescription('parameter'), _MOS_ERROR_INFORM);
    }

    function install_patch () {
        $permit_problem = false;
        $fmanager =& mosFileManager::getInstance();
        $here = mosPathName(dirname($this->xmlfile));
        $basedir = mosPathName(mamboCore::get('mosConfig_absolute_path'));
        foreach ($this->files as $file) {
            if (!$fmanager->acceptCopy($basedir.$file) OR (file_exists($basedir.$file) AND !is_writable($basedir.$file))) {
                $this->errors->addErrorDetails(sprintf(T_('Patch install error with %s: cannot write %s'), $this->xmlfile, $basedir.$file), _MOS_ERROR_WARN);
                $permit_problem = true;
            }
        }
        foreach ($this->rem_files as $file) {
            if (!is_writable($basedir.$file)) {
                $this->errors->addErrorDetails(sprintf(T_('Patch install error with %s: cannot delete %s'), $this->xmlfile, $basedir.$file), _MOS_ERROR_WARN);
                $permit_problem = true;
            }
        }
        if ($permit_problem) {
            $this->errors->addErrorDetails(sprintf(T_('Patch install error with %s: file permissions prevent patch application'), $this->xmlfile), _MOS_ERROR_FATAL);
            return;
        }
        foreach ($this->rem_files as $file) {
            if (!@unlink($basedir.$file)) {
                $this->errors->addErrorDetails(sprintf(T_('Patch install error with %s: failed to delete %s'), $this->xmlfile, $basedir.$file), _MOS_ERROR_WARN);
                $permit_problem = true;
            }
        }
        foreach ($this->files as $file) {
            if (!$fmanager->forceCopy($here.$file, $basedir.$file)) {
                $this->errors->addErrorDetails(sprintf(T_('Patch install error with %s: failed to write %s'), $this->xmlfile, $basedir.$file), _MOS_ERROR_WARN);
                $permit_problem = true;
            }
        }
        if ($permit_problem) $this->errors->addErrorDetails(sprintf(T_('Patch install error with %s: check was OK, but application failed'), $this->xmlfile), _MOS_ERROR_FATAL);
        else $this->errors->addErrorDetails(sprintf(T_('Patch install %s: completed successfully'), $this->xmlfile), _MOS_ERROR_INFORM);
    }

    function kill_component () {
        if (isset($this->user_dir)) {
            $dir = new mosDirectory($this->user_dir);
            $dir->deleteAll();
        }
        if (isset($this->admin_dir)) {
            $dir = new mosDirectory($this->admin_dir);
            $dir->deleteAll();
        }
        $com_name = 'com_'.strtolower($this->name);
        $sql = "DELETE FROM #__components WHERE `option`='$com_name'";
        $database =& mamboDatabase::getInstance();
        $database->setQuery($sql);
        $database->query();
    }

    function kill_module () {
        $name = $this->name;
        $sql = "DELETE FROM #__modules WHERE title='$name'";
        $database =& mamboDatabase::getInstance();
        $database->setQuery($sql);
        $database->query();
    }

    function kill_mambot () {
        $name = $this->name;
        $sql = "DELETE FROM #__mambots WHERE name='$name'";
        $database =& mamboDatabase::getInstance();
        $database->setQuery($sql);
        $database->query();
    }

    function kill_template () {
        if (isset($this->user_dir)) {
            $dir = new mosDirectory($this->user_dir);
            $dir->deleteAll();
        }
    }

    function kill_language () {
    }

    function kill_include () {
    }

    function kill_parameter () {
        $database =& mamboDatabase::getInstance();
        $name = $this->name;
        $sql = "DELETE FROM #__parameters WHERE param_name='$name'";
        $database->setQuery($sql);
        $database->query();
    }

    function kill_patch () {
    }

}


class mosUninstallXML extends mosCommonInstallXML {

    function uninstall () {
        $method = 'uninstall_'.$this->type;
        $setup = array (&$this, $method);
        if (is_callable($setup)) $this->$method();
        else $this->errors->addErrorDetails(sprintf(T_('Uninstaller error with %s: no uninstaller %s'), $this->xmlfile, $method), _MOS_ERROR_FATAL);
        if ($this->errors->getMaxLevel() >= _MOS_ERROR_FATAL) return false;
        return true;
    }

    function end_element_query () {
        if ($this->tagNestIs('QUERIES', 'UNINSTALL')) $this->queries[] = array ($this->chardata, $this->queryid);
        $this->queryid = 0;
    }

    function deleteFileSet ($files, $rootdir) {
        $dirs = array();
        $fmanager =& mosFileManager::getInstance();
        foreach ($files as $file) {
            $parts = split ('/', $file);
            if (count($parts) > 1 AND !in_array($parts[0], $dirs)) $dirs[] = $parts[0];
            $fmanager->deleteFile($rootdir.'/'.$file);
        }
        foreach ($dirs as $dir) {
            $dirobj = new mosDirectory($rootdir.'/'.$dir);
            $dirobj->deleteAll();
        }
    }


    function uninstall_component (){
        $com_name = 'com_'.str_replace(' ', '', strtolower($this->getName('component')));
        $this->user_dir = mosPathName(mamboCore::get('mosConfig_absolute_path').'/components/'.$com_name);
        $this->admin_dir = mosPathName(mamboCore::get('mosConfig_absolute_path').'/administrator/components/'.$com_name);
        if ($this->uninstallfile) {
            $ufile = mosPathName($this->admin_dir).$this->uninstallfile;
            if (is_file($ufile)) {
                require_once($ufile);
                com_uninstall();
            }
        }
        $database =& mamboDatabase::getInstance();
        foreach ($this->queries as $query) {
            $database->setQuery($query[0]);
            if (!$database->query()) {
                $this->errors->addErrorDetails(sprintf(T_('Uninstaller error with %s: %s %s SQL error %s'), $this->xmlfile, 'component', $com_name, $database->stderr(true)), _MOS_ERROR_SEVERE);
            }
        }
        $udir = new mosDirectory($this->user_dir);
        $udir->deleteAll();
        $adir = new mosDirectory($this->admin_dir);
        $adir->deleteAll();
        $sql = "DELETE FROM #__components WHERE `option`='$com_name'";
        $database->setQuery($sql);
        if (!$database->query()) {
            $this->errors->addErrorDetails(sprintf(T_('Uninstaller error with %s: %s %s not fully deleted from database'), $this->xmlfile, 'component', $com_name), _MOS_ERROR_SEVERE);
            return;
        }
        $this->errors->addErrorDetails($this->getDescription('component'), _MOS_ERROR_INFORM);
    }

    function uninstall_module () {
        $client_id = $this->client == 'administrator' ? 1 : 0;
        if ($client_id) $this->user_dir = mosPathName(mamboCore::get('mosConfig_absolute_path').'/administrator/modules/');
        else $this->user_dir = mosPathName(mamboCore::get('mosConfig_absolute_path').'/modules');
        $this->deleteFileSet($this->files, $this->user_dir);
        $this->deleteFileSet($this->images, $this->user_dir);
        if (!is_array($this->special_file)) {
            $this->errors->addErrorDetails(sprintf(T_('Uninstaller error with %s: no file identified as the %s'), $this->xmlfile, 'module'), _MOS_ERROR_FATAL);
            return;
        }
        $special = $this->special_file[0];
        $database =& mamboDatabase::getInstance();
        $name = $this->getName('module');
        $sql = "DELETE FROM #__modules WHERE module='$special' AND client_id='$client_id'";
        $database->setQuery($sql);
        if (!$database->query()) {
            $this->errors->addErrorDetails(sprintf(T_('Uninstaller error with %s: %s %s not fully deleted from database'), $this->xmlfile, 'module', $name), _MOS_ERROR_SEVERE);
            return;
        }
        $fmanager =& mosFileManager::getInstance();
        $fmanager->deleteFile($this->xmlfile);
        $this->errors->addErrorDetails($this->getDescription('module'), _MOS_ERROR_INFORM);
    }

    function uninstall_mambot () {
        $this->user_dir = mosPathName(mamboCore::get('mosConfig_absolute_path').'/mambots/'.$this->group);
        $this->deleteFileSet($this->files, $this->user_dir);
        $this->deleteFileSet($this->images, $this->user_dir);
        $database =& mamboDatabase::getInstance();
        $name = $this->getName('mambot');
        $element = $this->special_file[0];
        $group = $this->group;
        $sql = "DELETE FROM #__mambots WHERE element='$element' AND folder='$group'";
        $database->setQuery($sql);
        if (!$database->query()) {
            $this->errors->addErrorDetails(sprintf(T_('Uninstaller error with %s: %s %s not fully deleted from database'), $this->xmlfile, 'mambot', $name), _MOS_ERROR_SEVERE);
            return;
        }
        $fmanager =& mosFileManager::getInstance();
        $fmanager->deleteFile($this->xmlfile);
        $this->errors->addErrorDetails($this->getDescription('mambot'), _MOS_ERROR_INFORM);
    }

    /**
	* This routine is not called - uninstalling templates is done by just deleting
	* the whole directory.  It might be better to manage the uninstall via the XML.
	* Just deleting the template leaves all the files in the "media" directory.
	* But the following code has never been tested.
	**/
    function uninstall_template () {
        $name = str_replace(' ', '_', strtolower($this->getName('template')));
        $client_id = $this->client == 'administrator' ? 1 : 0;
        if ($client_id) $this->user_dir = mosPathName(mamboCore::get('mosConfig_absolute_path').'/administrator/templates/');
        else $this->user_dir = mosPathName(mamboCore::get('mosConfig_absolute_path').'/templates/'.$name);
        $userdir = new mosDirectory($this->user_dir);
        $userdir->deleteAll();
        $mediadir = mamboCore::get('mosConfig_absolute_path').'/images/stories/';
        $this->deleteFileSet($this->media, $mediadir);
        $fmanager =& mosFileManager::getInstance();
        $fmanager->deleteFile($this->xmlfile);
        $this->errors->addErrorDetails($this->getDescription('template'), _MOS_ERROR_INFORM);
    }

    function uninstall_language () {
        $this->user_dir = mosPathName(mamboCore::get('mosConfig_absolute_path').'/language/');
        $fmanager =& mosFileManager::getInstance();
        $here = mosPathName(dirname($this->xmlfile));
        $fmanager->deleteFile($this->user_dir.basename($this->xmlfile));
        foreach ($this->files as $ufile) $fmanager->deleteFile($this->user_dir.$ufile);
        $this->errors->addErrorDetails($this->getDescription('language'), _MOS_ERROR_INFORM);
    }

    function uninstall_params () {
    }

}

/**
* Installer class
* @package Mambo
* @subpackage Installer
* @abstract
*/
class mosInstaller {
    var $archiveName = '';
    var $extractDir = '';
    var $cleanDir = '';
    var $errors = '';

    function mosInstaller () {
        $this->errors = new mosErrorSet();
    }

    /**
	* Extracts the package archive file
	* @return boolean True on success, False on error
	*/
    function extractArchive($filename) {
        $mosConfig_absolute_path = mamboCore::get('mosConfig_absolute_path');
        $base_Dir = mosPathName($mosConfig_absolute_path.'/media');
        $this->archiveName = $base_Dir.$filename;
        $tmpdir = uniqid('install_');
        $this->extractDir = $this->cleanDir = mosPathName($base_Dir.uniqid('install_'));
        if (eregi( '.zip$', $filename )) {
            // Extract functions
            require_once( $mosConfig_absolute_path . '/administrator/includes/pcl/pclzip.lib.php' );
            require_once( $mosConfig_absolute_path . '/administrator/includes/pcl/pclerror.lib.php' );
            //require_once( $mosConfig_absolute_path . '/administrator/includes/pcl/pcltrace.lib.php' );
            //require_once( $mosConfig_absolute_path . '/administrator/includes/pcl/pcltar.lib.php' );
            $zipfile = new PclZip( $this->archiveName );
            $ret = $zipfile->extract( PCLZIP_OPT_PATH, $this->extractDir );
            if($ret == 0) {
                $this->errors->addErrorDetails(sprintf(T_('Installer unrecoverable ZIP error %s in %s'), $zipfile->errorName(true), $this->archiveName), _MOS_ERROR_FATAL);
                return false;
            }
        } else {
            require_once( $mosConfig_absolute_path . '/includes/Archive/Tar.php' );
            $archive =& new Archive_Tar( $this->archiveName );
            $archive->setErrorHandling( PEAR_ERROR_PRINT );

            if (!$archive->extractModify( $this->extractDir, '' )) {
                $this->errors->addErrorDetails(sprintf(T_('Installer unrecoverable TAR error in %s'), $this->archiveName), _MOS_ERROR_FATAL);
                return false;
            }
        }
        // Try to find the correct install dir. in case that the package have subdirs
        // Save the install dir for later cleanup
        $dir =& new mosDirectory($this->extractDir);
        $singledir = $dir->soleDir();
        if ($singledir) $this->extractDir = mosPathName($this->extractDir.$singledir);
        return true;
    }
    /**
	* Custom install method
	* @param boolean True if installing from directory
	*/
    function install($p_fromdir = null) {
        if (!is_null($p_fromdir)) $this->extractDir = $p_fromdir;
        $here = $this->extractDir;
        $installdir =& new mosDirectory($here);
        $xmlfiles =& $installdir->listFiles('.xml$');
        foreach ($xmlfiles as $file) {
            $parser = new mosInstallXML ($here.$file);
            $parser->install();
            $this->errors->mergeAnother($parser->errors);
            if ($parser->errors->getMaxLevel() >= _MOS_ERROR_WARN) return false;
        }
        return true;
    }

    function cleanUpInstall () {
        if ($this->archiveName) {
            $fmanager =& mosFileManager::getInstance();
            $fmanager->deleteFile($this->archiveName);
        }
        if ($this->cleanDir) {
            $edir =& new mosDirectory ($this->cleanDir);
            $edir->deleteAll();
        }
    }

    function getErrors () {
        return $this->errors->getErrors();
    }

}

?>
