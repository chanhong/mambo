<?php
/**
* Some Components, Modules, Mambots and Templates classes
* @package Mambo
* @author Mambo Foundation Inc see README.php
* @copyright Mambo Foundation Inc.
* See COPYRIGHT.php for copyright notices and details.
* @license GNU/GPL Version 2, see LICENSE.php
* Mambo is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; version 2 of the License.
*/

/**
* Singleton class to handle with current component
*
* This class controls the start, end and send output buffer from current component
* @package Mambo
* @acces public
*/

class mosComponentHandler {
    /**
    * stores the output from current component
    *
    * @acces private
    * @var string
    */
    var $_buffer = '';
    /**
    * Return a reference to current handler
    *
    * This function returns a reference to current component handler, if none handler exists,
    * it creates one.
    *
    * Example of use:
    *
    * <code>$c_handler =& mosComponentHandler::getInstance();</code>
    *
    * @acces public
    * @return object reference to current singleton Handler
    */
    function &getInstance () {
        static $instance;
        if (!is_object($instance)) $instance = new mosComponentHandler();
        return $instance;
    }

    /**
    * Returns the admin parameters from a component
    *
    * This function returns a reference to specified component in $name param, if none parameters
    * are founded it returns null.
    *
    * @acces public
    * @return object mosParameters object with parameters, null if none was founded.
    */
    function &getParamsByName ($name) {
        $params = null;
        $row = new mosComponent();
        $query = "SELECT a.params, a.option"
        . "\n FROM #__components AS a"
        . "\n WHERE a.name = '$name'"
        ;
        $database =& mamboDatabase::getInstance();
        $database->setQuery( $query );
        // load the row from the db table
        if ($database->loadObject($row)) {
            // get params definitions
            $mainframe =& mosMainFrame::getInstance();
            $params =& new mosParameters( $row->params);
        }
        return $params;
    }

    /**
    * Writes the output from current component
    *
    * This function send to client browser the outputs from the component, it's 
    * called by mosMainBody() global function.
    *
    * @acces private
    */
    function mosMainBody() {
        // message passed via the url
        $mosmsg = mosGetParam($_REQUEST, 'mosmsg', '');
        if ($mosmsg) {
            if (!get_magic_quotes_gpc()) $mosmsg = addslashes( $mosmsg );
            echo "\n<div class=\"message\">$mosmsg</div>";
        }
        echo $this->_buffer;
//      Alternative if "popmessages" - apparently never implemented
//      echo "\n<script language=\"javascript\">alert('$mosmsg');</script>";
    }
    
    /**
    * Start the use of buffer
    *
    * This function start the use of buffers to components output
    *
    * @acces private
    */
    function startBuffer () {
        $this->_buffer = '';
        ob_start();
    }
    
    /**
    * Ends the use of buffer
    *
    * This function ends the use of buffers to components output, all outputs
    * are stored in $this->_buffer
    *
    * @acces private
    */
    function endBuffer () {
        $this->_buffer = ob_get_contents();
        ob_end_clean();
    }
    
}

/**
* Components database table class
*
* This class can be used to gain access to #_components database table
*
* @package Mambo
* @access public
*/
class mosComponent extends mosDBTable {
    /** @var int Primary key */
    var $id=null;
    /** @var string component name*/
    var $name=null;
    /** @var string component link*/
    var $link=null;
    /** @var int menu id*/
    var $menuid=null;
    /** @var int parent menu*/
    var $parent=null;
    /** @var string component admin link*/
    var $admin_menu_link=null;
    /** @var string alternative text for admin menu*/
    var $admin_menu_alt=null;
    /** @var string component option id*/
    var $option=null;
    /** @var string order*/
    var $ordering=null;
    /** @var string image from admin menu*/
    var $admin_menu_img=null;
    /** @var int 1 core component ,0 others */
    var $iscore=null;
    /** @var string component parameters*/
    var $params=null;

    /**
    * mosComponent class Contructor
    * @access private
    */
    function mosComponent() {
        $db = mamboDatabase::getInstance();
        $this->mosDBTable( '#__components', 'id', $db );
    }
}

/**
* Abstract component common base class for both user and admin sides
*
* Since 4.6 version a new way to develop components based in MVC pattern was included,
* this requires that each component should to create a instance from mosComponentUserManager
* to user(frontend) side and a instance from mosComponentAdminManager to admin(backend) side,
* both classes are derived from this abstract class
*
* @package Mambo
* @access public
*/
class mosComponentManager {
    /** @var string component name */
    var $plugin_name = '';
    var $magic_quotes_value = 0;
    /** @var int current magic quotes value, used to restore it */
    var $magic_quotes_restore = '';
    /** @var string component version*/
    var $plugin_version = '';
    /** @var string option from URL*/
    var $option = '';

    /**
    * mosComponentManager Class contructor 
    *
    * This constructor initiates all necessary members, clear all magic quotes if 
    * is present and load the language from component 
    *
    * @access private
    * @param string component name
    * @param string component version
    */
    function mosComponentManager ($component_name, $version) {
        $this->text_name = $component_name;
        $this->plugin_name = strtolower(str_replace(' ', '', $component_name));
        $this->plugin_version = $version;
        $this->option = mamboCore::get('option');
        $this->magic_quotes_restore = get_magic_quotes_runtime();
        $this->noMagicQuotes();
        $cname = 'com_'.$this->plugin_name;
        $mosConfig_absolute_path = mamboCore::get('mosConfig_absolute_path');
        if(file_exists($mosConfig_absolute_path."/components/$cname/language/".mamboCore::get('mosConfig_lang').'.php')) require_once($mosConfig_absolute_path."/components/$cname/language/".mamboCore::get('mosConfig_lang').'.php');
        else if (file_exists($mosConfig_absolute_path."/components/$cname/language/english.php")) require_once($mosConfig_absolute_path."/components/$cname/language/english.php");

    }

    /**
    * remove magic quotes from Superglobals arrays 
    *
    * This function removes the magic quotes if is present in $_REQUEST, $_GET 
    * and $_POST arrays
    *
    * @access private
    */
    function noMagicQuotes () {
        // Is magic quotes on?
        if (get_magic_quotes_gpc()) {
            // Yes? Strip the added slashes
            $_REQUEST =& $this->remove_magic_quotes($_REQUEST);
            $_GET =& $this->remove_magic_quotes($_GET);
            $_POST =& $this->remove_magic_quotes($_POST);
        }
        set_magic_quotes_runtime(0);
        $this->magic_quotes_value = 0;
    }

    /**
    * remove magic quotes and slashes from a array
    *
    * This function removes the magic quotes if is present in passed array 
    *
    * @access private
    * @param array array to strip quotes and slashes
    * @return array reference to converted array
    */
    function &remove_magic_quotes ($array) {
        foreach ($array as $k => $v) {
            if (is_array($v)) $array[$k] = $this->remove_magic_quotes($v);
            else $array[$k] = stripslashes($v);
        }
        return $array;
    }

    /**
    * restore magic quotes from old value
    *
    * This function restore the old value of magic_quotes_runtime 
    *
    * @access private
    */
    function restore_magic_quotes () {
        set_magic_quotes_runtime($this->magic_quotes_restore);
    }

    /**
    * checks for a method in a class
    *
    * This function returns TRUE when $method is a member of $object
    *
    * @access public
    * @param object Reference to the object
    * @param string method name that is looking for
    * @return bool TRUE when $method exits
    */
    function checkCallable (&$object, $method) {
        if (is_callable(array(&$object, $method))) return true;
        $name = get_class($object);
        trigger_error (sprintf(T_('Component %s error: attempt to use non-existent class %s in %s'), $this->plugin_name, $method, $name));
        return false;
    }

}

/**
* Component base controller for user(frontend) side
*
* Since 4.6 version a new way to develop components based in MVC pattern was included,
* this requires that each component should to create a instance from mosComponentUserManager
* to user(frontend) side.
*
* @package Mambo
* @access public
*/
class mosComponentUserManager extends mosComponentManager {

    /**
    * mosComponentUserManager Class contructor 
    *
    * This constructor initiates all necessary members, sets the title to browser,
    * creates a new instance of the correct class and calls the action to do, finally 
    * restore the magic quotes to it initial state
    *
    * @access private
    * @param string component name
    * @param string variable used as control
    * @param array array whin alternavite names to actions methods
    * @param string default action to do
    * @param string browser title to show when the component is in use
    * @param string component version
    */
    function mosComponentUserManager ($component_name, $control_name, $alternatives, $default, $title, $version) {
        mosComponentManager::mosComponentManager($component_name, $version);
        $mainframe =& mosMainFrame::getInstance();
        $mainframe->SetPageTitle($title);
        $func = mosGetParam ($_REQUEST, $control_name, $default);
        if (isset($alternatives[$func])) $method = $alternatives[$func];
        else $method = $func;
        $classname = $this->plugin_name.'_'.$method.'_Controller';
        if (class_exists($classname)) {
            $controller =& new $classname($this);
            if (is_callable(array(&$controller,$method))) $controller->$method($func);
            else trigger_error (sprintf(T_('Component %s error: attempt to use non-existent method %s in %s'), $this->plugin_name, $method, $controller));
        }
        else trigger_error(sprintf(T_('Component %s error: attempt to use non-existent class %s'), $this->plugin_name, $classname));
        $this->restore_magic_quotes();
    }

    /**
    * Loads and returns a class for render HTML (view Object)
    *
    * This function load a class for view html an associated controller is passed
    *
    * @access public
    * @param string HTML view class name
    * @param object reference to controller object
    * @param int not used
    * @param int list of items to show
    * @return mixed a instance to the HTML class, FALSE if the class is not founded
    */
    function newHTMLClassCheck ($name, &$controller, $total_items, $clist) {
        if (class_exists($name)) return new $name ($controller, $this->limit, $clist);
        trigger_error(sprintf(T_('Component %s error: attempt to use non-existent class %s'), $this->plugin_name, $name));
        return false;
    }

}

/**
* Component base controller for admin side
*
* Since 4.6 version a new way to develop components based in MVC pattern was included,
* this requires that each component should to create a instance from mosComponentAdminManager
* to admin(backend) side.
*
* @package Mambo
* @access public
*/
class mosComponentAdminManager extends mosComponentManager {
    /** @var string action executed */
    var $act = '';
    /** @var string task executed */
    var $task = '';
    /** @var int init offset to admin list*/
    var $limitstart = 0;
    /** @var int quantity of elements to show in list*/
    var $limit = 0;
    /** @var mixed id or id's of selected objects in admin list */
    var $cfid = 0;
    /** @var array order positions for all items */
    var $order = 0;
    /** @var int first element of cfid */
    var $currid = 0;

    /**
    * mosComponentAdminManager Class contructor 
    *
    * This constructor initiates all necessary members with values passed trought REQUEST
    * creates a new instance of the correct class and calls the task to do, finally 
    * restore the magic quotes to it initial state.
    *
    * @param string component name
    * @param string component version
    * @access private
    */
    function mosComponentAdminManager ($component_name, $version) {
        mosComponentManager::mosComponentManager($component_name, $version);
        $this->act = mosGetParam ($_REQUEST, 'act', $this->plugin_name);
        $this->task = mosGetParam($_REQUEST, 'task', 'list');
        $mainframe = mosMainFrame::getInstance();
        $default_limit  = $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', 20 );
        $this->limit = intval( mosGetParam( $_REQUEST, 'limit', $default_limit ) );
        $this->limitstart = mosGetParam($_REQUEST, 'limitstart', 0 );
        $this->cfid = mosGetParam($_REQUEST, 'cfid', array(0));
        if (is_array($this->cfid)) foreach ($this->cfid as $i=>$id) $this->cfid[$i] = intval($id);
        $this->order= mosGetParam($_REQUEST, 'order', array());
        if (is_array( $this->cfid )) $this->currid=intval($this->cfid[0]);
        else $this->currid=intval($this->cfid);
        $name = $this->getAction();
        if (class_exists($name)) {
            $controller =& new $name($this);
            $task = $this->task.'Task';
            if (is_callable(array(&$controller, 'getRequestData'))) $controller->getRequestData();
            if (is_callable(array(&$controller,$task))) $controller->$task();
            else trigger_error(sprintf(T_('MOS error in %s: method %s not found in class %s'), $this->plugin_name, $task, $name));
        }
        else trigger_error(sprintf(T_('MOS error in %s: class not found %s'), $this->plugin_name, $name));
        $this->restore_magic_quotes();
    }

    /**
    * Checks that at least one item selected 
    *
    * @param string alert message
    * @access public
    */
    function check_selection ($text) {
        if (!is_array($this->cfid) OR count( $this->cfid ) < 1) {
            echo "<script> alert('".$text."'); window.history.go(-1);</script>\n";
            exit;
        }
    }

    /**
    * returns the class name from the current action
    *
    * @return string class name from the current action
    * @access public
    */
    function getAction () {
        $actname = strtoupper(substr($this->act,0,1)).strtolower(substr($this->act,1));
        return strtolower($this->plugin_name).'Admin'.$actname;
    }

    /**
    * Loads and returns a class for render HTML (view Object)
    *
    * This function load a class for view html an associated controller is passed
    *
    * @access public
    * @param string HTML view class name
    * @param object reference to controller object
    * @param int
    * @param int list of items to show
    * @return mixed a instance to the HTML class, FALSE if the class is not founded
    */
    function newHTMLClassCheck ($name, &$controller, $total_items, $clist) {
        $controller->makePageNav($this, $total_items);
        if (class_exists($name)) return new $name ($controller, $this->limit, $clist);
        trigger_error(sprintf(T_('Component %s error: attempt to use non-existent class %s'), $this->plugin_name, $name));
        return false;
    }

}

/**
* Abstract component base class for admin side component controller logic (not used yet)
*
* @package Mambo
* @access public
* @todo This class is not used yet
*/
class mosComponentAdminControllers {
    /** @var string action executed */
    var $admin = '';
    /** @var string curren user */
    var $user = '';
    /** @var object Page navigation Object */
    var $pageNav = '';
    /** @var string curren root path */
    var $rootPath = '';
    /** @var string curren language */
    var $language = '';

    /**
    * mosComponentAdminControllers Class contructor 
    *
    * @param object $admin 
    * @access private
    */
    function mosComponentAdminControllers ($admin) {
        $this->admin = $admin;
        $this->user = mamboCore::get('currentUser');
        $this->rootPath = mamboCore::get('mosConfig_absolute_path');
        $this->language = mamboCore::get('mosConfig_lang');
    }

    /**
    * Creates a mosPageNav object 
    *
    * @param object component name
    * @param int not used
    * @access public
    */
    function makePageNav (&$admin, $total) {
        require_once(mamboCore::get('mosConfig_absolute_path').'/administrator/includes/pageNavigation.php');
        $this->pageNav =& new mosPageNav( $total, $admin->limitstart, $admin->limit );
    }

}

/**
* Template database table class 
*
* This class can be used to gain access to #_templates database table
*
* @package Mambo
* @access public
* @todo This class is not used yet
*/

class mosTemplate extends mosDBTable {
    /** @var int table primary key */
    var $id=null;
    /** @var string is act*/
    var $cur_template=null;
    /** @var int */
    var $col_main=null;

    /**
    * mosTemplate Class contructor 
    *
    * Init a mosDBTable object.
    *
    * @param object &$database reference to current database object
    * @access private
    */
    function mosTemplate( &$database ) {
        $this->mosDBTable( '#__templates', 'id', $database );
    }
}

/**
* Mambot database table class
*
* This class can be used to gain access to #_mambots database table
*
* @package Mambo
* @access public
*/
class mosMambot extends mosDBTable {
    /** @var int table primary key */
    var $id=null;
    /** @var string mambot name */
    var $name=null;
    /** @var string element name */
    var $element=null;
    /** @var string mambot kind  */
    var $folder=null;
    /** @var int access level 0 public, 1 registered, 2 special */
    var $access=null;
    /** @var int order lower first*/
    var $ordering=null;
    /** @var int 1 published, 0 unpublished */
    var $published=null;
    /** @var int 1 core mambots ,0 others */
    var $iscore=null;
    /** @var int 1 admin mambot, 0 user mambot*/
    var $client_id=null;
    /** @var int id from the user that checkout, 0 checkin */
    var $checked_out=null;
    /** @var datetime date and time from checkout*/
    var $checked_out_time=null;
    /** @var string mambot parameters */
    var $params=null;

    /**
    * mosMambot Class contructor 
    *
    * Init a mosDBTable object.
    *
    * @param object reference to current database object
    * @access private
    */
    function mosMambot( &$db ) {
        $this->mosDBTable( '#__mambots', 'id', $db );
    }
}

/**
* Singleton class to handle with modules
*
* This class loads, counts and caches modules for both sides, user and admin
*
* @package Mambo
* @acces public
*/
class mosModuleHandler {
    /**
    * @var object current database object
    * @access private
    */
    var $_db = null;
    /**
    * @var object modules cached
    * @access private
    */
    var $_modules = null;
    /**
    * @var array unpublished modules
    * @access private
    */
    var $_unpublished = null;
    /**
    * @var bool TRUE when admin modules are loaded
    * @access private
    */
    var $_isAdmin = null;
    /**
    * @var bool TRUE when content is buffered
    * @access private
    */
    var $_isBuffered = null;

    /**
    * mosModuleHandler Class contructor 
    *
    * Init the database object.
    *
    * @access private
    */
    function mosModuleHandler () {
        $this->_db =& mamboDatabase::getInstance();
    }

    /**
    * Returns a reference to current handler
    *
    * This function returns a reference to current modules handler, if none handler exists,
    * it creates one.
    *
    * @acces public
    * @return object reference to current singleton Handler
    */
    function &getInstance () {
        static $instance;
        if (!is_object($instance)) $instance = new mosModuleHandler();
        return $instance;
    }

    function get ($property) {
        if (isset($this->$property)) return $this->$property;
        return null;
	}
    /**
    * Caches some modules output
    *
    * This function cache all modules output, a $isAdmin bool value can be passed to select
    * the side (user/admin) by default user modules are loaded.
    *
    * @access private
    * @param bool TRUE when admin modules will loaded
    */
    function initBuffers($isAdmin=false) {
		$this->initModules($isAdmin);
		require_once(mamboCore::get('mosConfig_absolute_path').'/includes/frontend.html.php');
        $Itemid = mamboCore::get('Itemid');
		foreach($this->_modules as $position=>$modules) {
	        foreach ($modules as $module) {
				ob_start();
				if (mamboCore::get('mosConfig_debug')) echo (T_('buffered').'<br />');
    	        $params =& new mosParameters($module->params);
        	    if ((substr("$module->module",0,4))=="mod_")
				 $modfunc = 'module2';
            	else $modfunc = 'module';
	            if ($params->get('cache') == 1 AND mamboCore::get('mosConfig_caching') == 1) {
    	            $cache->call("modules_html::$modfunc", $module, $params, $Itemid, -1 );
        	    } else {
					modules_html::$modfunc($module, $params, $Itemid, -1, 0);
				}
				$this->_modules[$position][$module->id]->buffer = ob_get_contents();
				ob_end_clean();
	        }
		}
		$this->_isBuffered = true;
	}
    /**
    * Caches some modules information
    *
    * This function cache all modules, a $isAdmin bool value can be passed to select
    * the side (user/admin) by default user modules are loaded.
    *
    * @access private
    * @param bool TRUE when admin modules will loaded
    */
    function initModules($isAdmin=false) {
		static $frontLoaded;
		static $adminLoaded;
		
		if (!$isAdmin && isset($frontLoaded)) return;
		if ($isAdmin && isset($adminLoaded)) return;
		
		if ($isAdmin) $adminLoaded = true;
		else $frontLoaded = true;
		
		$my = mamboCore::get('currentUser');
        if (!isset($this->_modules) OR $isAdmin != $this->_isAdmin) {
            $this->_isAdmin = $isAdmin;
            if ($isAdmin) {
                $query = "SELECT id, title, module, position, content, showtitle, params, published"
                . "\n FROM #__modules AS m"
                . "\n WHERE m.published = '1'"
                . "\n AND (m.client_id = 1)"
                . "\n ORDER BY m.ordering";
            }
            else {
                $Itemid = mamboCore::get('Itemid');
				$query = "SELECT id, title, module, position, content, showtitle, params, published, m.access, m.groups"
                ."\nFROM #__modules AS m, #__modules_menu AS mm"
                . "\nWHERE m.access <= '$my->gid' AND m.client_id='0'"
                . "\nAND mm.moduleid=m.id"
                . "\nAND (mm.menuid = '$Itemid' OR mm.menuid = '0')"
                . "\nORDER BY ordering";
            }
            $this->_db->setQuery( $query );
            $modules = $this->_db->loadObjectList();
            foreach ($modules as $module) {
				if (!$isAdmin) $canAccess = $this->canAccess($module, $my->gid);
					else $canAccess = 1;
				if ($module->published == 1 && $canAccess == 1) $this->_modules[$module->position][$module->id] = $module;
				else $this->_unpublished[] = $module;
            }
        }
    }
    /**
    * Returns the number of modules in a specified position, this method is called by
    * mosCountModules global function
    *
    * @access public
    * @param string The template position
    * @param bool TRUE when admin modules will loaded
    */
    function mosCountModules( $position='left', $isAdmin=false ) {
        if (!$this->_isBuffered) 
	        $this->initModules($isAdmin);
        return isset($this->_modules[$position]) ? count($this->_modules[$position]) : 0;
    }

    /**
    * Returns a array with modules that match whit $name, when $unpublished is TRUE
    * unpublished modules are returned too.
    *
    * @access public
    * @param string Name of module required
    * @param bool TRUE when admin modules will loaded
    * @param bool TRUE whish to include unpublished modules too
    * @return array array with all modules that match
    */
    function &getByName( $name, $isAdmin=false, $unpublished=false ) {
        if (!$this->_isBuffered) 
	        $this->initModules($isAdmin);
        $modules = array();
        foreach ($this->_modules as $position) {
            foreach ($position as $module) if ($module->module == $name) $modules[] = $module;
        }
        if ($unpublished AND $this->_unpublished) foreach ($this->_unpublished as $module) if ($module->module == $name) $modules[] = $module;
        return $modules;
    }

    /**
    * Loads all published modules from a specified position, a $style can be passed 
    * to change the style of output
    *
    * @param string The position
    * @param int The style.  0=normal(default), 1=horiz, -1=no wrapper
    * @param bool TRUE when admin modules will loaded
    */
    function mosLoadModules( $position='left', $style=0, $isAdmin=false ) {
        $Itemid = mamboCore::get('Itemid');
        $tp = mosGetParam( $_GET, 'tp', 0 );
        if ($tp) {
            echo '<div style="height:50px;background-color:#eee;margin:2px;padding:10px;border:1px solid #f00;color:#700;">';
            echo $position;
            echo '</div>';
            return;
        }
        $style = intval($style);
        $cache =& mosCache::getCache('com_content');
        require_once( mamboCore::get('mosConfig_absolute_path').'/includes/frontend.html.php');
		// check for buffered output
        if (!$this->_isBuffered) {
	        $this->initModules($isAdmin);
		}
        if (isset($this->_modules[$position] )) $modules = $this->_modules[$position];
        else {
            $modules = array();
            $style = 0;
        }
        if ($style == 1) {
            echo "<table cellspacing=\"1\" cellpadding=\"0\" border=\"0\" width=\"100%\">\n";
            echo "<tr>\n";
        }
        $prepend = ($style == 1) ? "<td valign=\"top\">\n" : '';
        $postpend = ($style == 1) ? "</td>\n" : '';
        $count = 1;
        foreach ($modules as $module) {
            $params =& new mosParameters($module->params);
            echo $prepend;
            if ((substr("$module->module",0,4))=="mod_") $modfunc = 'module2';
            else $modfunc = 'module';
            if ($params->get('cache') == 1 AND mamboCore::get('mosConfig_caching') == 1) {
                $cache->call("modules_html::$modfunc", $module, $params, $Itemid, $style, $this->_isBuffered );
            }
            else {
				modules_html::$modfunc($module, $params, $Itemid, $style, $count, $this->_isBuffered);
            }
            echo $postpend;
            $count++;
        }
        if ($style == 1) echo "</tr>\n</table>\n";
    }

    /**
    * Loads admin modules from a specified position,a $style can be passed 
    * to change the style of output
    *
    * @param string The position
    * @param int The style 0 = no style(default), 1 = tabbed, 2 = use div
    */
    function mosLoadAdminModules( $position='left', $style=0 ) {
        global $my, $acl;
        $this->initModules(true);
        $cache =& mosCache::getCache( 'com_content' );
        if (isset($this->_modules[$position] )) $modules = $this->_modules[$position];
        else $modules = array();

        switch ($style) {
            case 0:
            default:
                foreach ($modules as $module) {
                    $params =& new mosParameters( $module->params );
                    if ( $module->module == '' ) mosLoadCustomModule( $module, $params );
                    else mosLoadAdminModule( substr( $module->module, 4 ), $params );
                }
                break;
            case 1:
                // Tabs
                $tabs = new mosTabs(1);
                $tabs->startPane( 'modules-' . $position );
                foreach ($modules as $module) {
                    $params =& new mosParameters( $module->params );
                    $editAllComponents  = $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'all' );
//              $authoriser = new mosAuthoriser($database);
//              $editAllComponents = $authoriser->checkPermission('mosUser', $my->id, 'edit', 'editAllComponents', 0);
                // special handling for components module
                    if ( $module->module != 'mod_components' || ( $module->module == 'mod_components' && $editAllComponents ) ) {
                        $tabs->startTab( T_($module->title), 'module' . $module->id );
                        if ( $module->module == '' ) mosLoadCustomModule( $module, $params );
                        else mosLoadAdminModule( substr( $module->module, 4 ), $params );
                        $tabs->endTab();
                    }
                }
                $tabs->endPane();
                break;
            case 2:
                // Div'd
                foreach ($modules as $module) {
                    $params =& new mosParameters( $module->params );
                    echo '<div>';
                    if ( $module->module == '' ) mosLoadCustomModule( $module, $params );
                    else mosLoadAdminModule( substr( $module->module, 4 ), $params );
                    echo '</div>';
                }
                break;
        }
    }
/**
* Module access check
*
* Used in 
*
* @param object a module object
* @param int an array of groups
*/
	function canAccess($module, $gid) {
		if($module->access == 2) {
			$groups = explode(',',$module->groups);
			if(count($groups) > 0) {
				if (in_array($gid, $groups)) {
					return 1;
				} else {
					return 0;
				}
			}
		}
		return 1;
	}
}

/**
* Modules database table class
*
* This class can be used to gain access to #_modules database table
*
* Example of use:
*
* To load all modules in a object.. 
*
* <code>
* $row = new mosModule();
* $query = "SELECT * FROM #_modules";
* $database =& mamboDatabase::getInstance();
* $database->setQuery( $query );
* if ($database->loadObject($row)) {
* ...
* }
* </code>
* @package Mambo
* @access public
*/
class mosModule extends mosDBTable {
    /** @var int Primary key */
    var $id=null;
    /** @var string module title */
    var $title=null;
    /** @var bool TRUE show title, FALSE hide title*/
    var $showtitle=null;
    /** @var string content to custom modules*/
    var $content=null;
    /** @var int order lower first*/
    var $ordering=null;
    /** @var string template position*/
    var $position=null;
    /** @var int id from the user that checkout, 0 checkin */
    var $checked_out=null;
    /** @var int date and time from checkout */
    var $checked_out_time=null;
    /** @var bool TRUE published, FALSE unpublished*/
    var $published=null;
    /** @var string module name*/
    var $module=null;
    /** @var int num of news from newsfeed modules*/
    var $numnews=null;
    /** @var int access level 0 public, 1 registered, 2 special */
    var $access=null;
    /** @var string module parameters*/
    var $params=null;
    /** @var int 1 core mambots ,0 others */
    var $iscore=null;
    /** @var int 1 admin module, 0 user module*/
    var $client_id=null;
	/** @var string group access*/
	var $groups=null;

    /**
    * mosModule Class contructor 
    *
    * Init a mosDBTable object.
    *
    * @param object reference to current database object
    * @access private
    */
    function mosModule( &$db ) {
        $this->mosDBTable( '#__modules', 'id', $db );
    }

    /**
    * overloaded check function 
    *
    * @access private
    */
    function check() {
        // check for valid name
        if (trim( $this->title ) == '') {
            $this->_error = T_('Your Module must contain a title.');
            return false;
        }

        // limitation has been removed
        // check for existing title
        //$this->_db->setQuery( "SELECT id FROM #__modules"
        //. "\nWHERE title='$this->title'"
        //);
        // check for module of same name
        //$xid = intval( $this->_db->loadResult() );
        //if ($xid && $xid != intval( $this->id )) {
        //  $this->_error = "There is a module already with that name, please try again.";
        //  return false;
        //}
        return true;
    }
}

?>