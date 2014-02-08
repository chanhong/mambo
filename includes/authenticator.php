<?php
/**
* Authenticator class file for Mambo
* @package Mambo
* @author Mambo Foundation Inc see README.php
* @copyright Mambo Foundation Inc.
* See COPYRIGHT.php for copyright notices and details.
* @license GNU/GPL Version 2, see LICENSE.php
* Mambo is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; version 2 of the License.
*/

class mamboAuthenticator {
	
	function &getInstance () {
		static $instance;
		if (!is_object($instance)) {
			$instance =& new mamboAuthenticator();
		}
		return $instance;
	}

	/**
	* Login management function
	*
	* The current session is passed.
	* Username and encoded password is authenticated.
	* A successful authentication updates the current session record with
	* the users details.
	*/
	function loginUser ($username=null, $passwd=null, $remember=null) {
		$mambothandler =& mosMambotHandler::getInstance();
		$mambothandler->loadBotGroup('authenticator');
		$session =& mosSession::getCurrent();
		$database =& mamboDatabase::getInstance();
		if (!$username OR !$passwd) {
			$username = mosGetParam($_REQUEST, 'username', '');
			$passwd = mosGetParam($_REQUEST, 'passwd', '' );
			$bypost = 1;
		}
		else $bypost = 0;
		if ($remember === null) $remember = mosGetParam($_REQUEST, 'remember', '');

		if (!$username OR !$passwd) {
			echo "<script> alert(\"".T_('Please complete the username and password fields.')."\"); window.history.go(-1); </script>\n";
			exit();
		} else {
			$username = $database->getEscaped($username);
			$passwd = $database->getEscaped($passwd);
			$loginfo =& new mosLoginDetails($username, $passwd, $remember);
			$checkuser = true;
			$logresults = $mambothandler->trigger('requiredLogin',array($loginfo));
			if (count($logresults) == 0) $logresults[] = T_('Logins are not permitted.  There is no authentication check active.');
			foreach ($logresults as $message) {
				if ($message) $checkuser = false;
				break;
			}
			if ($checkuser) {
				$mambothandler->trigger('goodLogin', array($loginfo));
				return true;
			}
			$mambothandler->trigger('badLogin', array($loginfo));
			if (isset($bypost)) echo "<script>alert(\"".$message."\"); window.history.go(-1); </script>\n";
			@session_destroy();
		}
	}
	
	/**
	* User authentication function
	*
	* Username and encoded password are checked against the database.
	*/
	function authenticateUser (&$message, $username, $passwd, $remember=null, $session=null) {
		$message = '';
		if ($session === null) $session =& mosSession::getCurrent();
		$database =& mamboDatabase::getInstance();
		$database->setQuery( "SELECT id, gid, block, usertype"
		. "\nFROM #__users"
		. "\nWHERE username='$username' AND password='$passwd'"
		);
		if ($database->loadObject($row)) {
			if ($row->block) {
				$message = T_('Your login has been blocked. Please contact the administrator.');
				return false;
			}
			// fudge the group stuff
//			$grp = $acl->getAroGroup( $row->id );
//			if ($acl->is_group_child_of( $grp->name, 'Registered', 'ARO' ) ||
//			$acl->is_group_child_of( $grp->name, 'Public Backend', 'ARO' )) {
			// fudge Authors, Editors, Publishers and Super Administrators into the Special Group
//			$row->usertype = $grp->name;
			$session->guest = 0;
			$session->username = $username;
			$session->userid = $row->id;
			$session->usertype = $row->usertype;
			if ($row->usertype == 'Registered') $session->gid = 1;
			else $session->gid = 2;
			$session->gid = intval( $row->gid ); # what is going on here???
			$session->update();
			$currentDate = date("Y-m-d\TH:i:s");
			$query = "UPDATE #__users SET lastvisitDate='$currentDate' where id='$session->userid'";
			$database->setQuery($query);
			if (!$database->query()) {
				die($database->stderr(true));
			}
			if ($remember=="yes") {
				$lifetime = time() + 365*24*60*60;
				setcookie("usercookie[username]", $username, $lifetime, "/");
				setcookie("usercookie[password]", $passwd, $lifetime, "/");
			}
			//mosCache::cleanCache('com_content');
			mosCache::cleanCache();
		} else {
			$message = T_('Incorrect username or password. Please try again.');
			$this->clearSession($session);
			return false;
		}
		return true;
	}

	function clearSession ($session=null) {
		if ($session === null) $session =& mosSession::getCurrent();
		//mosCache::cleanCache('com_content');
		mosCache::cleanCache();
		$session->guest = 1;
		$session->username = '';
		$session->userid = '';
		$session->usertype = '';
		$session->gid = 0;
		$session->update();
		// this is daggy??
		$lifetime = time() - 1800;
		setcookie( "usercookie[username]", " ", $lifetime, "/" );
		setcookie( "usercookie[password]", " ", $lifetime, "/" );
		setcookie( "usercookie", " ", $lifetime, "/" );
        @session_destroy();
	}

	/**
	* User logout
	*
	* Reverts the current session record back to 'anonymous' parameters
	*/
	function logoutUser () {
		$session =& mosSession::getCurrent();
		if ($session) {
			$mambothandler =& mosMambotHandler::getInstance();
			$mambothandler->loadBotGroup('authenticator');
			$loginfo = new mosLoginDetails($session->username);
			$mambothandler->trigger('beforeLogout', array($loginfo));
			$this->clearSession($session);
		}
	}

	function &loginAdmin ($acl) {
		$database =& mamboDatabase::getInstance();
		/** escape and trim to minimise injection of malicious sql */
		$usrname 	= $database->getEscaped(mosGetParam($_POST, 'usrname', ''));
		$pass 		= $database->getEscaped(mosGetParam($_POST, 'pass', ''));

		$my = null;
		if (!$pass) echo "<script>alert('".T_('Please enter a password')."'); document.location.href='index.php';</script>\n";
		else $pass = md5( $pass );

		$admintypes = array ('administrator', 'superadministrator', 'super administrator');
		$admins = 0;
		$query = "SELECT u.*, a.name as usertype, a.lft as grp FROM #__users AS u, #__core_acl_aro_groups AS a"
		. "\n WHERE ( LOWER( usertype ) = 'administrator'"
		. "\n OR LOWER( usertype ) = 'superadministrator'"
		. "\n OR LOWER( usertype ) = 'super administrator'"
		. "\n OR (username='$usrname' AND block=0)) AND a.group_id = u.gid"
		;
		$users = $database->doSQLget( $query, 'mosUser' );
		foreach ($users as $key=>$oneuser) {
			if (in_array(strtolower($oneuser->usertype),$admintypes)) $admins++;
			if ($oneuser->username == $usrname) $my =& $users[$key];
		}
		if ($admins == 0) echo "<script>alert(\"".T_('You cannot login. There are no administrators set up.')."\"); window.history.go(-1); </script>\n";
		/** find the user group (or groups in the future) */
		elseif (isset($my)) {
			if (strcmp( $my->password, $pass )
			OR !$acl->acl_check( 'administration', 'login', 'users', $my->usertype )) {
				echo "<script>alert('".T_('Incorrect Username, Password, or Access Level.  Please try again')."'); document.location.href='index.php';</script>\n";
				return;
			}
			$logintime 	= time();
			$session_id = md5( "$my->id$my->username$my->usertype$logintime" );
			$query = "INSERT INTO #__session"
			. "\nSET time='$logintime', session_id='$session_id', "
			. "userid='$my->id', usertype='$my->usertype', username='$my->username', guest=-1"
			;
			$database->setQuery( $query );
			if (!$database->query()) {
				echo $database->stderr();
			}
			$_SESSION['session_id'] 		= $session_id;
			$_SESSION['session_user_id'] 	= $my->id;
			$_SESSION['session_username'] 	= $my->username;
			$_SESSION['session_usertype'] 	= $my->usertype;
			$_SESSION['session_gid'] 		= $my->gid;
			$_SESSION['session_grp']        = $my->grp;
			$_SESSION['session_logintime'] 	= $logintime;
			$_SESSION['session_userstate'] 	= array();
		}
		return $my;
	}
	
	/**
	* Random password generator
	* @return password
	*/
	function mosMakePassword() {
		$salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$len = strlen($salt);
		$makepass="";
		mt_srand(10000000*(double)microtime());
		for ($i = 0; $i < 8; $i++)
		$makepass .= $salt[mt_rand(0,$len - 1)];
		return $makepass;
	}
}
