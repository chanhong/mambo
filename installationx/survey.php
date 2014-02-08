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

// Set flag that this is a parent file
define( "_VALID_MOS", 1 );

// Test to see if the user has submitted the survey
if (isset($_POST['name'])) {

	// Include common.php
	require_once( 'common.php' );
	
	// Collect the survey information
	$name = mosGetParam( $_POST, 'name', '' );
	$email = mosGetParam( $_POST, 'email', '' );
	$company = mosGetParam( $_POST, 'company', '' );
	$category = mosGetParam( $_POST, 'category', '' );
	$teammambo = mosGetParam( $_POST, 'teammambo', '' );
	$comments = mosGetParam( $_POST, 'comments', '' );
	
	// Check for user's name and a valid email address
	if (empty($name) || empty($email) || (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email))) {
	   $response = 0;
	}
	else {
	   $response = 1;
	}
	   
	//Build standard email headers
	$headers = "From: $email" . "\r\n"
	. "Reply-To: $email" . "\r\n"
	. 'X-Mailer: PHP/' . phpversion();
	
	// Check to see if the user is interested in joining Team Mambo and process
	if ($teammambo && $response) {
		$subject = "Mambo Installation - Possible New Team Member";
		$message = "$name is interested in being a member of Team Mambo.  " 
		       . "Here are the details of the installation survey:\n"
		       . "\n" 
	           . "Name: $name\n"
	           . "Email: $email\n"
	           . "Category: $category\n"
	           . "Company: $company";
	
		//OK lets send the notice of interest
		// No error checking on the send since this is just a nice to have
		mail('membership@mambo-foundation.org', $subject, $message, $headers);
	}
	
	// Check to see if the user left any comments and process
	if ($response && $comments) {
		$subject = "Mambo Installation - User Comments";
		$message = "$name left some comments on the installation survey.  " 
	           . "Here are the comments:\n"
		       . "\n"
	           . "Name: $name\n"
	           . "Email: $email\n"
		       . "Category: $category\n"
	           . "Company: $company\n"
	           . "Comments: $comments";
	
		// OK lets send the feedback email
		// No error checking on the send since this is just a nice to have
		mail('feedback@mambo-foundation.org', $subject, $message, $headers);

	}

}
 
//Redirect user to the frontpage
Header('Location: ../index.php');

?> 