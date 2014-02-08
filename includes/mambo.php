<?php
/**
* Main file for Mambo
* @package Mambo
* @author Mambo Foundation Inc see README.php
* @copyright Mambo Foundation Inc.
* See COPYRIGHT.php for copyright notices and details.
* @license GNU/GPL Version 2, see LICENSE.php
* Mambo is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; version 2 of the License.
*/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class mosAuthoriser {
    var $connection = '';
    var $perm_found;
    var $permissions = array();
    var $assignments = array();

    function mosAuthoriser () {
        $this->connection =& mamboDatabase::getInstance();
    }

    function &getInstance () {
        static $instance;
        if (!is_object($instance)) {
            $instance =& new mosAuthoriser();
        }
        return $instance;
    }
    
    function doSQL ($sql) {
        $this->connection->setQuery($sql);
        if (!$this->connection->query()) {
            echo "<script> alert('".$this->connection->getErrorMsg()."'); window.history.go(-1); </script>\n";
            exit();
        }
    }

    function getAccessorData ($type, $id) {
        if (isset($this->perm_found[$type][$id])) return;
        $sql = "SELECT a.*, p.control, p.action, p.subject_type, p.subject_id, p.system FROM #__assignments AS a LEFT JOIN #__permissions AS p ON p.role=a.role WHERE a.access_type='$type'";
        if (isset($this->perm_found[$type])) $sql .= " AND a.access_id='$id'";
        else $sql .= " AND (a.access_id='$id' OR a.access_id='*' OR a.access_id='+')";
        $this->doSQL($sql);
        $new_permissions = $this->connection->loadObjectList();
        if ($new_permissions) $this->permissions = array_merge($this->permissions, $new_permissions);
        $this->perm_found[$type][$id] = 1;
    }

    function &getRoles ($type, $id) {
        $this->getAccessorData ($type, $id);
        $rolenames = array();
        foreach ($this->permissions as $role) {
            if (strcasecmp($role->access_type, $type) == 0
            AND ($role->access_id == $id OR $role->access_id == '*' OR ($role->access_id == '+' AND $id != 0))
            AND !in_array($role->role,$rolenames)) $rolenames[] = $role->role;
        }
        return $rolenames;
    }

    function accessorPermissionOrControl  ($mask, $a_type, $a_id, $action, $s_type='*', $s_id='*') {
        $this->getAccessorData ($a_type, $a_id);
        foreach ($this->permissions as $permission) {
            if ((strcasecmp($permission->access_type,$a_type) == 0 OR $permission->access_type == '*')
            AND (strcasecmp($permission->access_id,$a_id) == 0 OR $permission->access_id == '*')
            AND (strcasecmp($permission->action,$action)==0 OR $permission->action == '*' OR $action == '*')
            AND (strcasecmp($permission->subject_type,$s_type)==0 OR $s_type=='*')
            AND (strcasecmp($permission->subject_id,$s_id)==0 OR $permission->subject_id == '*')
            AND ($permission->control&$mask)) return 1;
        }
        return 0;
    }
    
    function checkPermission ($a_type, $a_id, $action, $s_type='*', $s_id='*') {
        return $this->accessorPermissionOrControl(2, $a_type, $a_id, $action, $s_type='*', $s_id='*');
    }
    
    function checkControl ($a_type, $a_id, $action, $s_type='*', $s_id='*') {
        return $this->accessorPermissionOrControl(1, $a_type, $a_id, $action, $s_type='*', $s_id='*');
    }
    
    function checkGrant ($a_type, $a_id, $action, $s_type='*', $s_id='*') {
        return $this->accessorPermissionOrControl(4, $a_type, $a_id, $action, $s_type='*', $s_id='*');
    }

    function rolePermissionOrControl ($mask, $role, $action, $s_type, $s_id) {
        $sql = "SELECT * FROM #__permissions WHERE role='$role'";
        $this->connection->setQuery($sql);
        $permissions = $this->connection->loadObjectList();
        if ($permissions) {
            foreach ($permissions as $permission) {
                if (strcasecmp($permission->role,$role) == 0
                AND (strcasecmp($permission->action,$action)==0 OR $permission->action == '*')
                AND (strcasecmp($permission->subject_type,$s_type)==0)
                AND (strcasecmp($permission->subject_id,$s_id)==0 OR $permission->subject_id == '*')
                AND ($permission->control&$mask)) return 1;
            }
        }
        return 0;
    }

    function checkRolePermission  ($role, $action, $s_type, $s_id) {
        return $this->rolePermissionOrControl(2, $role, $action, $s_type, $s_id);
    }

    function checkRoleControl  ($role, $action, $s_type, $s_id) {
        return $this->rolePermissionOrControl(1, $role, $action, $s_type, $s_id);
    }

    function checkRoleGrant  ($role, $action, $s_type, $s_id) {
        return $this->rolePermissionOrControl(4, $role, $action, $s_type, $s_id);
    }

    function &listPermissions ($a_type, $a_id, $action, $property) {
        $this->getAccessorData ($a_type, $a_id);
        $results = array();
        foreach ($this->permissions as $permission) {
            if (strcasecmp($permission->access_type,$a_type) == 0
            AND (strcasecmp($permission->access_id,$a_id) == 0 OR $permission->access_id == '*' OR ($permission->access_id == '+' AND $a_id != 0))
            AND (strcasecmp($permission->action,$action) == 0 OR $permission->action == '*')
            AND $permission->subject_type != null AND $permission->subject_id != null) {
                $results[] = $permission->$property;
            }
        }
        return $results;
    }

}

class mosAuthorisationAdmin {
    var $connection;
    var $roles = array();

    function mosAuthorisationAdmin () {
        $this->connection =& mamboDatabase::getInstance();
    }

    function &getInstance () {
        static $instance;
        if (!is_object($instance)) {
            $instance =& new mosAuthorisationAdmin();
        }
        return $instance;
    }

    function doSQL ($sql) {
        $this->connection->setQuery($sql);
        if (!$this->connection->query()) {
            echo "<script> alert('".$this->connection->getErrorMsg()."'); window.history.go(-1); </script>\n";
            exit();
        }
    }
    
    function getRoles () {
        if (count($this->roles) == 0) {
            $sql = "SELECT DISTINCT role FROM #__assignments";
            $this->connection->setQuery($sql);
            $this->roles = $this->connection->loadResultArray();
            $sql = "SELECT DISTINCT role FROM #__permissions";
            $this->connection->setQuery($sql);
            $more = $this->connection->loadResultArray();
            foreach ($more as $role) $this->addRole($role);
        }
        return $this->roles;
    }
    
    function addRole ($role) {
        if (!in_array($role, $this->roles)) $this->roles[] = $role;
    }
    
    function removeRole ($role) {
        $key = array_search($role, $this->roles);
        if ($key !== false) unset($this->roles[$key]);
    }
    
    function &permissionHolders ($subject_type, $subject_id) {
        $sql = "SELECT role, action, control FROM #__permissions";
        if ($subject_type != '*') $where[] = "(subject_type='$subject_type' OR subject_type='*')";
        if ($subject_id != '*') $where[] = "(subject_id='$subject_id' OR subject_id='*')";
        if (isset($where)) $sql .= " WHERE ".implode(' AND ', $where);
        $this->connection->setQuery($sql);
        $result = $this->connection->loadObjectList();
        if (!$result) $result = array();
        return $result;
    }
    
    function &nonLocalPermissionHolders ($subject_type, $subject_id) {
        $sql = "SELECT role, action, control FROM #__permissions WHERE (action='*' OR subject_type='*' OR subject_id='*') AND ((subject_type='$subject_type' OR subject_type='*') AND (subject_id='$subject_id' OR subject_id='*'))";
        $this->connection->setQuery($sql);
        $result = $this->connection->loadObjectList();
        if (!$result) $result = array();
        return $result;
    }
    
    function permitSQL ($role, $control, $action, $subject_type, $subject_id) {
        $sql = "REPLACE INTO #__permissions (role, control, action, subject_type, subject_id) VALUES ('$role', '$control', '$action', '$subject_type', '$subject_id');";
        return $sql;
    }

    function permit ($role, $control, $action, $subject_type, $subject_id) {
        $sql = $this->permitSQL($role, $control, $action, $subject_type, $subject_id);
        $this->doSQL($sql);
        $this->addRole($role);
    }

    function assign ($role, $access_type, $access_id) {
        $sql = "REPLACE INTO #__assignments (role, access_type, access_id) VALUES ('$role', '$access_type', '$access_id')";
        $this->doSQL($sql);
        $this->addRole($role);
    }

    function dropAccess ($access_type, $access_id) {
        $sql = "DELETE FROM #__assignments WHERE access_type='$access_type' AND access_id='$access_id'";
        $this->doSQL($sql);
    }

    function &getControllingRoles ($access_type, $access_id, $action, $subject_type, $subject_id) {
        $sql = "SELECT a.role FROM #__permissions AS p, #__assignments AS a WHERE a.access_type='$access_type'"
        ." AND a.access_id='$access_id' AND a.role=p.role AND (p.control&1)"
        ." AND p.action='$action' AND p.subject_type='$subject_type' AND p.subject_id='$subject_id'";
        $this->doSQL($sql);
        $roles = $this->connection->loadResultArray();
        return $roles;
    }

    function &getMyPermissions ($access_type, $access_id) {
        $sql = 'SELECT p.action, p.subject_type, p.subject_id, control FROM #__permissions AS p, #__assignments AS a'
        . " WHERE p.role=a.role AND a.access_type='$access_type' AND (a.access_id='$access_id' OR a.access_id='*')"
        . ' AND (p.control&1)';
        $this->doSQL($sql);
        $permissions =& $this->connection->loadObjectList();
        return $permissions;
    }

    function getJointPermissions ($access_type, $access_id, $role) {
        $sql = "SELECT p2.control AS hiscontrol, p1.control AS mycontrol, p1.action, p1.subject_type, p1.subject_id"
        ." FROM `#__assignments` AS a, `#__permissions` AS p1 LEFT JOIN `#__permissions` AS p2"
        ." ON (p2.role='$role' AND p1.action=p2.action AND p1.subject_type=p2.subject_type AND p1.subject_id=p2.subject_id)"
        ." WHERE  (p1.control&1) AND p1.role=a.role AND a.access_type='$access_type' AND (a.access_id='$access_id' OR a.access_id='*')";
        $this->doSQL($sql);
        $permissions =& $this->connection->loadObjectList();
        return $permissions;
    }
    
    function getAccessLists ($access_type, $access_id, $action, $subject_type, $subject_id) {
        $authoriser =& mosAuthoriser::getInstance();
        if ($authoriser->checkControl($access_type, $access_id, $action, $subject_type, $subject_id)) {
            $cangrant = $authoriser->checkGrant($access_type, $access_id, $action, $subject_type, $subject_id);
            $permissions = $this->permissionHolders($subject_type, $subject_id);
            $allroles = $this->getRoles();
            foreach ($allroles as $role) {
                $itemc[] = $optionc = mosHTML::makeOption($role, $role);
                $itema[] = $optiona = mosHTML::makeOption($role, $role);
                if ($cangrant) $itemg[] = $optiong = mosHTML::makeOption($role, $role);
                foreach ($permissions as $permission) {
                    if (($permission->action == '*' OR $permission->action == $action) AND $permission->role == $role) {
                        if ($permission->control & 1) $cselected[] = $optionc;
                        if ($permission->control & 2) $aselected[] = $optiona;
                        if ($cangrant AND $permission->control & 4) $gselected[] = $optiong;
                    }
                }
            }
            $results[] = mosHTML::selectList($itema, $action.'_arole[]', 'multiple="multiple"', 'value', 'text', $aselected);
            $results[] = mosHTML::selectList($itemc, $action.'_crole[]', 'multiple="multiple"', 'value', 'text', $cselected);
            if ($cangrant) $results[] = mosHTML::selectList($itemg, $action.'_grole[]', 'multiple="multiple"', 'value', 'text', $gselected);
        }
        else $results = array();
        return $results;
    }
    
    function resetPermissions ($action, $subject_type, $subject_id) {
        $control_types = array ('crole', 'arole', 'grole');
        $control_values = array (1,2,4);
        $permissions = $this->nonLocalPermissionHolders($subject_type, $subject_id);
        $this->dropPermissions($action, $subject_type, $subject_id);
        foreach ($control_types as $i=>$type) {
            $key = $action.'_'.$type;
            if (isset($_POST[$key])) {
                foreach ($_POST[$key] as $role) {
                    $value = isset($newpermits[$role]) ? $newpermits[$role] : 0;
                    $newpermits[$role] = $value | $control_values[$i];
                }
            }
        }
        $sql = '';
        foreach ($newpermits as $role=>$value) {
            $needed = true;
            foreach ($permissions as $permission) {
                if (($permission->action == '*' OR $permission->action == $action) AND $permission->role == $role) {
                    if (($value & $permission->control) === $value) {
                        $needed = false;
                        break;
                    }
                }
            }
            if ($needed) $sql .= $this->permitSQL ($role, $value, $action, $subject_type, $subject_id);
        }
        if ($sql) $this->doSQL($sql);
    }

    function roleExists ($role) {
        $sql = "SELECT COUNT(role) FROM #__permissions WHERE role='$role' GROUP BY role";
        $this->doSQL($sql);
        if ($this->connection->loadResult()) return true;
        $sql = "SELECT COUNT(role) FROM #__assignments WHERE role='$role' GROUP BY role";
        $this->doSQL($sql);
        if ($this->connection->loadResult()) return true;
        else return false;
    }

    function dropRole ($role) {
        $sql = "DELETE FROM #__permissions WHERE action='administer' AND subject_type='$role' AND system=0";
        $this->doSQL($sql);
        $sql = "DELETE a FROM #__assignments AS a LEFT JOIN #__permissions AS p ON a.role=p.role WHERE a.role='$role' AND (p.system=0 OR p.system=NULL)";
        $this->doSQL($sql);
        $this->dropRolePermissions($role);
        $this->removeRole($role);
    }

    function dropRolePermissions ($role) {
        $sql = "DELETE FROM #__permissions WHERE role='$role' AND system=0";
        $this->doSQL($sql);
        $this->roles = array();
    }

    function dropPermissions ($action, $subject_type, $subject_id) {
        $sql = "DELETE FROM #__permissions WHERE action='$action' AND subject_type='$subject_type'AND subject_id='$subject_id' AND system=0";
        $this->doSQL($sql);
        $this->roles = array();
    }

}

/**
* Extended input filter that uses language character set in the decode() function - al warren
*/
// make sure Php Input Filter base class is loaded
require_once(mamboCore::get('mosConfig_absolute_path').'/includes/phpInputFilter/class.inputfilter.php');
class mosInputFilter extends InputFilter {
	var $charset=null;
	function mosInputFilter() {
		static $filter;
		if (!isset($filter)) {
			$filter = new InputFilter();
			foreach(get_object_vars($filter) as $key=>$value) {
				$this->$key = $value;
			}
			$filter = null;
		}
		$configuration =& mamboCore::getMamboCore();
		$this->charset = $configuration->current_language->charset; // could handle this easier?
	}

	function getInstance() {
		static $instance;
		if (!isset($instance)) $instance = new mosInputFilter();
		return $instance;
	}

	function decode($source) {
		// make sure were using a valid character set for html_entity_decode
		$charset = $this->mosFilterCharset($this->charset);

		// url decode
		$source = html_entity_decode($source, ENT_QUOTES, $charset);
		// convert decimal
		$source = preg_replace('/&#(\d+);/me',"chr(\\1)", $source);				// decimal notation
		// convert hex
		$source = preg_replace('/&#x([a-f0-9]+);/mei',"chr(0x\\1)", $source);	// hex notation
		return $source;
	}
	
	// make sure we're working with a valid character set. return default if not.
	function mosFilterCharset($charset) {
		static $validSets;
		if (!isset($validSets)) {
			$validSets = array(
				'iso-8859-1','iso-8859-15','utf-8',
				'cp866','ibm866','866',
				'cp1251','windows-1251','win-1251','1251',
				'cp1252','windows-1252','1252',
				'koi8-r','koi8-ru','koi8r',
				'big5','950',
				'gb2312','936',
				'big5-hkscs',
				'shift_jis','sjis','932',
				'euc-jp','eucjp'
			);
		}
		if(in_array(strtolower($charset), $validSets)) {
			return $charset;
		}
		// php default for html_entity_decode
		return 'ISO-8859-1';
	}
}


// ----- NO MORE CLASSES OR FUNCTIONS PASSED THIS POINT -----
// Post class declaration initialisations
// some version of PHP don't allow the instantiation of classes
// before they are defined

?>