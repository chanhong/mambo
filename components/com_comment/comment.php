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

global $mosConfig_absolute_path, $mosConfig_usecaptcha, $task;
require_once($mosConfig_absolute_path."/administrator/components/com_comment/config.comment.php");

# valid user check
$is_user   = (strtolower($my->usertype) <> '');
$captcha_success = 0; // captcha not required

if ($task == "captcha-audio"){
   @ob_end_clean();
   ob_start();
   include ($mosConfig_absolute_path."/includes/captcha-audio.php");
   ob_end_flush();
   exit(0);
   
}

if ($mosConfig_usecaptcha == '1') {
	# spam protection
	session_name('mos_captcha');
	session_start();

	if(isset($_SESSION['code']) && ($_SESSION['code'] != "") && ($_SESSION['code'] == $spamstop)) {
		$captcha_success = 1; // success
	} else {
		$captcha_success = 2; // fail
	}
}

if ($captcha_success != '2') {

	# if registered users only
	if (!$allow_anonymous_entries AND !$is_user) {

		$link = sefRelToAbs("index.php?option=content&task=view&id=$articleid");
		echo "<SCRIPT>alert('Please register to add comments'); document.location.href='".$link."';</SCRIPT>";

	} else {

		$comments  = strip_tags($comments);
		$comments  = mysql_escape_string(strip_tags($comments));
		$startdate = date( "Y-m-d H:i:s" );
		$ip        = getenv('REMOTE_ADDR');

		$query = "INSERT INTO #__comment SET articleid='$articleid', ip='$ip', name='$mcname', comments='$comments', startdate='$startdate', published='$auto_publish_comments';";
		$database->setQuery($query);
		$database->query();


		if ($notify_new_entries == "1") {
			// messaging for new items
			require_once($mosConfig_absolute_path."/includes/mambofunc.php");
			$message = "A new comment has been added\n\n".$comments;

			if ($auto_publish_comments == "0") {
				$message = $message . "\n\nYou have chosen not to auto publish new comments. Therefore you need to log in and publish new posts to make them visible.";
			}

			mosMail ( $mosConfig_mailfrom, $mosConfig_mailfrom, $mosConfig_mailfrom, "A new comment has been submitted", $message);
		}

		$msg = 'Thanks. Your comment has been successfully saved. ';
		if ($auto_publish_comments == "0") {
			$msg = $msg. "The Administrator will review and publish your comment shortly.";
		}		
		
		mosRedirect( "index.php?option=com_content&task=view&id=".$articleid."&Itemid=".$mcitemid."&limit=".$limit."&limitstart=".$limitstart, $msg );
	}

} else {
	echo "<SCRIPT> alert('Incorrect Security Code');			document.location='index.php?option=com_content&task=view&id=$articleid&Itemid=$mcitemid&limit=$limit&limitstart=$limitstart&comments=$comments';</SCRIPT>";
}




?>