<?php
/**
* @package Mambo
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

$task = mosGetParam( $_REQUEST, 'task', "" );
require_once( $mainframe->getPath( 'front_html' ) );

switch( $task ) {
	case "lostPassword":
	lostPassForm( $option );
	break;

	case "sendNewPass":
	sendNewPass( $option );
	break;

	case "register":
    case "reviseRegistration":
	registerForm( $option, $mosConfig_useractivation );
	break;

	case "confirmRegistration":
	confirmRegistration( $option );
	break;

	case "saveRegistration":
	saveRegistration( $option );
	break;

	case "activate":
	activate( $option );
	break;
}

function lostPassForm( $option ) {
	global $mainframe;
	$mainframe->SetPageTitle(T_('Lost your Password?'));
	HTML_registration::lostPassForm($option);
}

function sendNewPass( $option ) {
	global $database, $Itemid;
	global $mosConfig_live_site, $mosConfig_sitename, $mosConfig_fromname;

	$_live_site = $mosConfig_live_site;
	$_sitename = $mosConfig_sitename;

	// ensure no malicous sql gets past
	$checkusername = trim( mosGetParam( $_POST, 'checkusername', '') );
	$checkusername = $database->getEscaped( $checkusername );
	$confirmEmail = trim( mosGetParam( $_POST, 'confirmEmail', '') );
	$confirmEmail = $database->getEscaped( $confirmEmail );

	$database->setQuery( "SELECT id FROM #__users"
	. "\nWHERE username='$checkusername' AND email='$confirmEmail'"
	);

	if (!($user_id = $database->loadResult()) || !$checkusername || !$confirmEmail) {
		mosRedirect( "index.php?option=$option&task=lostPassword&mosmsg=".T_('Sorry, no corresponding user was found.  Please make sure you entered a valid username and a valid email address.  Both are required.') );
	}

	$database->setQuery( "SELECT name, email FROM #__users"
	. "\n WHERE usertype='super administrator'" );
	$rows = $database->loadObjectList();
	foreach ($rows AS $row) {
		$adminName = $row->name;
		$adminEmail = $row->email;
	}

	$rawpass = mosMakePassword();
	$message = sprintf(T_("The user account %s has this email associated with it.\n
A web user from %s has just requested that a new password be sent.\n\n
Your New Password is: %s\n\n
If you didn't ask for this, don't worry. You are seeing this message, not them. 
If this was an error just login with your new password and then change your password to what you would like it to be."),
	           $checkusername, $mosConfig_live_site, $rawpass);
	#eval ("\$message = \"$message\";");
	$subject = sprintf(T_('%s :: New password for - %s'),$_sitename, $checkusername);
	#eval ("\$subject = \"$subject\";");

	mosMail($mosConfig_mailfrom, $mosConfig_fromname, $confirmEmail, $subject, $message);

	$newpass = md5( $rawpass );
	$sql = "UPDATE #__users SET password='$newpass' WHERE id='$user_id'";
	$database->setQuery( $sql );
	if (!$database->query()) {
		die("SQL error" . $database->stderr(true));
	}
	$loginfo = new mosLoginDetails($checkusername, $rawpass);
	$mambothandler =& mosMambotHandler::getInstance();
	$mambothandler->loadBotGroup('authenticator');
	$mambothandler->trigger('userChange', array($loginfo));

	mosRedirect( "index.php?Itemid=$Itemid&mosmsg=".T_('New User Password created and sent!') );
}

function registerForm( $option, $useractivation ) {
	global $mainframe, $database, $my, $acl;

	if (!$mainframe->getCfg( 'allowUserRegistration' )) {
		mosNotAuth();
		return;
	}

  $mainframe->SetPageTitle(T_('Registration'));
	HTML_registration::registerForm($option, $useractivation);
}


function confirmRegistration ($option)
{
$name = trim( mosGetParam( $_REQUEST, 'name', "" ) );
$username = trim( mosGetParam( $_REQUEST, 'username', "" ) );
$password = trim( mosGetParam( $_REQUEST, 'password', "" ) );
$email = trim( mosGetParam( $_REQUEST, 'email', "" ) );
$useractivation = trim( mosGetParam( $_REQUEST, 'useractivation', "" ) );
	HTML_registration::confirmForm($option, $name, $username, $password, $email, $useractivation);
}

function saveRegistration( $option ) {
	global $database, $my, $acl;
	global $mosConfig_sitename, $mosConfig_live_site, $mosConfig_useractivation, $mosConfig_allowUserRegistration;
	global $mosConfig_mailfrom, $mosConfig_fromname, $mosConfig_mailfrom, $mosConfig_fromname;

	if ($mosConfig_allowUserRegistration=='0') {
		mosNotAuth();
		return;
	}

	$row = new mosUser( $database );

	if (!$row->bind( $_POST, 'usertype' )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	mosMakeHtmlSafe($row);

	$row->id = 0;
	$row->usertype = 'Registered';
	$row->gid = $acl->get_group_id( 'Registered', 'ARO' );

	if ($mosConfig_useractivation == '1') {
		$row->activation = md5( mosMakePassword() );
		$row->block = '1';
	}

	if (!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	$pwd = $row->password;
	$row->password = md5( $row->password );
	$row->registerDate = date("Y-m-d H:i:s");

	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	$row->checkin();

	$name = $row->name;
	$email = $row->email;
	$username = $row->username;

	$subject = sprintf (T_('Account details for %s at %s'), $name, $mosConfig_sitename);
	$subject = html_entity_decode($subject, ENT_QUOTES);
	$mambothandler =& mosMambotHandler::getInstance();
	$mambothandler->loadBotGroup('authenticator');
	if ($mosConfig_useractivation=="1"){
		$message = sprintf (T_('Hello %s,

Thank you for registering at %s. Your account has been created but, as a precaution, it must be activated by you before you can use it.
To activate the account click on the following link or copy and paste it in your browser:
%s

After activation you may login to %s using the following username and password:

Username - %s
Password - %s'), 
		          $name, $mosConfig_sitename, $mosConfig_live_site."/index.php?option=com_registration&task=activate&activation=".$row->activation, $mosConfig_live_site, $username, $pwd);
		$loginfo = new mosLoginDetails($username, $pwd);
		$mambothandler->trigger('userRegister', array($loginfo));
	}
	else {
		$message = sprintf (T_("Hello %s,

Thank you for registering at %s.

You may now login to %s using the username and password you registered with."),
		          $name, $mosConfig_sitename, $mosConfig_live_site);
		$loginfo = new mosLoginDetails($username, $pwd);
		$mambothandler->trigger('userRegister', array($loginfo));
		$mambothandler->trigger('userActivate', array($loginfo));
	}

	$message = html_entity_decode($message, ENT_QUOTES);
	// Send email to user
	if ($mosConfig_mailfrom != "" && $mosConfig_fromname != "") {
		$adminName2 = $mosConfig_fromname;
		$adminEmail2 = $mosConfig_mailfrom;
	} else {
		$database->setQuery( "SELECT name, email FROM #__users"
		."\n WHERE usertype='super administrator'" );
		$rows = $database->loadObjectList();
		$row2 = $rows[0];
		$adminName2 = $row2->name;
		$adminEmail2 = $row2->email;
	}

	mosMail($adminEmail2, $adminName2, $email, $subject, $message);

	// Send notification to all administrators
	$subject2 = sprintf (T_('Account details for %s at %s'), $name, $mosConfig_sitename);
	$message2 = sprintf (T_('Hello %s,

A new user has registered at %s.
This email contains their details:

Name - %s
e-mail - %s
Username - %s

Please do not respond to this message as it is automatically generated and is for information purposes only'), 
	            $adminName2, $mosConfig_sitename, $row->name, $email, $username);
	$subject2 = html_entity_decode($subject2, ENT_QUOTES);
	$message2 = html_entity_decode($message2, ENT_QUOTES);

	// get superadministrators id
	$admins = $acl->get_group_objects( 25, 'ARO' );

	foreach ( $admins['users'] AS $id ) {
		$database->setQuery( "SELECT email, sendEmail FROM #__users"
			."\n WHERE id='$id'" );
		$rows = $database->loadObjectList();

		$row = $rows[0];

		if ($row->sendEmail) {
			mosMail($adminEmail2, $adminName2, $row->email, $subject2, $message2);
		}
	}

	if ( $mosConfig_useractivation == "1" ){
	    echo '<div class="componentheading">'.T_('Registration Complete').'</div><br />';
		echo T_('Your account has been created and an activation link has been sent to the e-mail address you entered. Note that you must activate the account by clicking on the activation link before you can login.');
	} else {
	    echo '<div class="componentheading">'.T_('Registration Complete').'</div><br />';		
		echo T_('You may now login.');
	}

}

function activate( $option ) {
	global $database;
	global $mosConfig_useractivation, $mosConfig_allowUserRegistration;

	if ($mosConfig_allowUserRegistration == '0' || $mosConfig_useractivation == '0') {
		mosNotAuth();
		return;
	}

	$activation = mosGetParam( $_REQUEST, 'activation', '' );
	$activation = $database->getEscaped( $activation );

	if (empty( $activation )) {
        echo '<div class="componentheading">'.T_('Invalid Activation Link!').'</div><br />';
		echo T_('There is no such account in our database or the account has already been activated.');
		return;
	}

	$database->setQuery( "SELECT username FROM #__users"
	."\n WHERE activation='$activation' AND block='1'" );
	$username = $database->loadResult();

	if ($username) {
		$database->setQuery( "UPDATE #__users SET block='0', activation='' WHERE activation='$activation' AND block='1'" );
		if (!$database->query()) {
			echo "SQL error" . $database->stderr(true);
		}
		echo '<div class="componentheading">'.T_('Activation Complete!').'</div><br />';
		echo T_('Your account has been activated successfully. You can now login using the username and password you chose during registration.');
		$loginfo = new mosLoginDetails($username);
		$mambothandler =& mosMambotHandler::getInstance();
		$mambothandler->loadBotGroup('authenticator');
		$mambothandler->trigger('userActivate', array($loginfo));
	} else {
	    echo '<div class="componentheading">'.T_('Invalid Activation Link!').'</div><br />';
		echo T_('There is no such account in our database or the account has already been activated.');
	}
}

function is_email($email){
	$rBool=false;

	if(preg_match("/[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}/", $email)){
		$rBool=true;
	}
	return $rBool;
}
?>
