<?php
//Custom MOStlyCE code
$mostlyceSessionRestore='';
if (isset($_GET['restore']) && !empty($_GET['restore'])) {
	$mostlyceSessionRestore=$_GET['restore'];
} else {
	die('Direct Access to this location is not allowed.');
}
session_id($mostlyceSessionRestore);
session_start(); //resume session

require('../../../../../../configuration.php');
include($mosConfig_absolute_path."/mambots/editors/mostlyce/jscripts/tiny_mce/mostlyce_config.php");
//If the Image Manager is not enabled stop them here
if ($editor_plugin_img_mgr!=='true') {
	die('Direct Access to this location is not allowed.');
}
//Get the session restore key
$mostlyceRestoreKey='';
if (isset($_SESSION['mostlyce_restore_key']) && !empty($_SESSION['mostlyce_restore_key'])) {
	$mostlyceSessionRestore=$_SESSION['mostlyce_restore_key'];
} 
//Build a fresh restore key to compare against the session restore key
$crtRestoreKey=md5($mosConfig_secret.$_SERVER['REMOTE_ADDR']);

//Get calling usertype
$usertype='';
if (isset($_SESSION['mostlyce_usertype']) && !empty($_SESSION['mostlyce_usertype'])) {
	$usertype=strtolower($_SESSION['mostlyce_usertype']);
}
//Set valid user types
$vUsers=array('author', 'editor', 'publisher', 'manager', 'administrator', 'super administrator', 'superadministrator'); //Valid usertypes
//Test usertype and restore key
if (!in_array($usertype, $vUsers) || ($mostlyceSessionRestore !== $crtRestoreKey)) {
	die('Direct Access to this location is not allowed.');
}
//End custom MOStlyCE code
?>
<!--
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003-2005 Frederico Caldeira Knabben
 * 
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 * 
 * For further information visit:
 * 		http://www.fckeditor.net/
 * 
 * File Name: browser.html
 * 	This page compose the File Browser dialog frameset.
 * 
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>
		<title>FCKeditor - Resources Browser</title>
		<link href="browser.css" type="text/css" rel="stylesheet">
	</head>
	<frameset cols="150,*" framespacing="0" bordercolor="#f1f1e3" frameborder="no" class="Frame_none">
		<frameset rows="50,*" framespacing="0"  class="Frame_r">
			<frame src="frmresourcetype.html" scrolling="no" frameborder="no">
			<frame name="frmFolders" id="frmFolders" src="frmfolders.html" scrolling="auto" frameborder="no">
		</frameset>
		<frameset rows="50,*,50" framespacing="0" class="Frame_none">
			<frame name="frmActualFolder" src="frmactualfolder.html" scrolling="no" frameborder="no">
			<frame name="frmResourcesList" id="mainWindow" src="frmresourceslist.html" scrolling="auto" frameborder="no">
			<frameset cols="150,*,0" framespacing="0" frameborder="no" class="Frame_t">
				<frame name="frmCreateFolder" id="frmCreateFolder" src="frmcreatefolder.html" scrolling="no" frameborder="no">
				<frame name="frmUpload" id="frmUpload" src="frmupload.html" scrolling="no" frameborder="no">
				<frame name="frmUploadWorker" src="" scrolling="no" frameborder="no">
			</frameset>
		</frameset>
	</frameset>
</html>
