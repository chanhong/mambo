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
if (!defined('_VALID_MOS')) define( '_VALID_MOS', 1 );

// Include common.php
require_once( 'common.php' );
require_once( '../includes/database.php' );
include_once( 'langconfig.php' );

$DBhostname = mosGetParam( $_POST, 'DBhostname', '' );
$DBuserName = mosGetParam( $_POST, 'DBuserName', '' );
$DBpassword = mosGetParam( $_POST, 'DBpassword', '' );
$DBverifypassword = mosGetParam( $_POST, 'DBverifypassword', '' );
$DBname  	= mosGetParam( $_POST, 'DBname', '' );
$DBPrefix  	= mosGetParam( $_POST, 'DBPrefix', '' );
$DBDel  	= intval( mosGetParam( $_POST, 'DBDel', 0 ) );
$DBBackup  	= intval( mosGetParam( $_POST, 'DBBackup', 0 ) );
$DBSample	= intval( mosGetParam( $_POST, 'DBSample', 0 ) );
$DBcreated	= intval( mosGetParam( $_POST, 'DBcreated', 0 ) );
$BUPrefix = 'old_';
$configArray['sitename'] = trim( mosGetParam( $_POST, 'sitename', '' ) );

$database = null;

$errors = array();
if (!$DBcreated){
	if (!$DBhostname || !$DBuserName || !$DBname) {
		db_err ("stepBack3", T_('The database details provided are incorrect and/or empty.'));
	}
	
	if ($DBpassword !== $DBverifypassword) {
		db_err ("stepBack3", T_("The database passwords provided do not match.  Please try again."));
	}

	if (!($mysql_link = @mysql_connect( $DBhostname, $DBuserName, $DBpassword ))) {
		db_err ("stepBack2", T_("The password and username provided are incorrect."));
	}

	if($DBname == "") {
		db_err ("stepBack", T_("The database name provided is empty."));
	}

	// Does this code actually do anything???
	$configArray['DBhostname'] = $DBhostname;
	$configArray['DBuserName'] = $DBuserName;
	$configArray['DBpassword'] = $DBpassword;
	$configArray['DBname']	 = $DBname;
	$configArray['DBPrefix']   = $DBPrefix;

	$sql = "CREATE DATABASE `$DBname`";
	$mysql_result = mysql_query( $sql );
	$test = mysql_errno();

	if ($test <> 0 && $test <> 1007) {
		db_err( "stepBack", T_("A database error occurred: ") . (mysql_error()) );
	}

	// db is now new or existing, create the db object connector to do the serious work
	$database = new database( $DBhostname, $DBuserName, $DBpassword, $DBname, $DBPrefix );

	//Delete existing tables from a previous installation if found.  Backup tables if requested.
    $database->setQuery( "SHOW TABLES FROM `$DBname`" );
	$errors = array();
	if ($tables = $database->loadResultArray()) {
		foreach ($tables as $table) {
			//Check for the existance of tables with the same prefix
			if (strpos( $table, $DBPrefix ) === 0) {
				//Check to see if the user requested a backup
				if ($DBBackup==1) {
					//if they requested a backup then replace the org table prefix with old_
					$butable = str_replace( $DBPrefix, $BUPrefix, $table );
					//if a prior backup table exists with the same name then drop it before the rename
					$database->setQuery( "DROP TABLE IF EXISTS `$butable`" );
					$database->query();
					if ($database->getErrorNum()) {
						$errors[$database->getQuery()] = $database->getErrorMsg();
					}
					//Perform the actual table rename
					$database->setQuery( "RENAME TABLE `$table` TO `$butable`" );
					$database->query();
					if ($database->getErrorNum()) {
						$errors[$database->getQuery()] = $database->getErrorMsg();
					}
				} else { //No backup was requested so just drop the original table
				  $database->setQuery( "DROP TABLE IF EXISTS `$table`" );
				  $database->query();
				  if ($database->getErrorNum()) {
					  $errors[$database->getQuery()] = $database->getErrorMsg();
				  }
			  }
		   }
	    } //end foreach
	} //end if

	populate_db($DBname,$DBPrefix,'mambo.sql');
	if ($DBSample) {
		populate_db($DBname,$DBPrefix,'sample_data.sql');
	}
	$DBcreated = 1;
}

function db_err($step, $alert) {
	global $DBhostname,$DBuserName,$DBpassword,$DBDel,$DBname;
	echo "<form name=\"$step\" method=\"post\" action=\"install1.php\">
	<input type=\"hidden\" name=\"DBhostname\" value=\"$DBhostname\">
	<input type=\"hidden\" name=\"DBuserName\" value=\"$DBuserName\">
	<input type=\"hidden\" name=\"DBpassword\" value=\"$DBpassword\">
	<input type=\"hidden\" name=\"DBDel\" value=\"$DBDel\">
	<input type=\"hidden\" name=\"DBname\" value=\"$DBname\">
	</form>\n";
	//echo "<script>alert(\"$alert\"); document.$step.submit();</script>";
	echo "<script>alert(\"$alert\"); window.history.go(-1);</script>";  //this wasn't working
	exit();
}

function populate_db($DBname, $DBPrefix, $sqlfile='mambo.sql') {
	global $errors;

	mysql_select_db($DBname);
	$mqr = @get_magic_quotes_runtime();
	@set_magic_quotes_runtime(0);
	$query = fread(fopen("sql/".$sqlfile, "r"), filesize("sql/".$sqlfile));
	@set_magic_quotes_runtime($mqr);
	$pieces  = split_sql($query);

	for ($i=0; $i<count($pieces); $i++) {
		$pieces[$i] = trim($pieces[$i]);
		if(!empty($pieces[$i]) && $pieces[$i] != "#") {
			$pieces[$i] = str_replace( "#__", $DBPrefix, $pieces[$i]);
			if (!$result = mysql_query ($pieces[$i])) {
				$errors[] = array ( mysql_error(), $pieces[$i] );
			}
		}
	}
}

function split_sql($sql) {
	$sql = trim($sql);
	$sql = ereg_replace("\n#[^\n]*\n", "\n", $sql);

	$buffer = array();
	$ret = array();
	$in_string = false;

	for($i=0; $i<strlen($sql)-1; $i++) {
		if($sql[$i] == ";" && !$in_string) {
			$ret[] = substr($sql, 0, $i);
			$sql = substr($sql, $i + 1);
			$i = 0;
		}

		if($in_string && ($sql[$i] == $in_string) && $buffer[1] != "\\") {
			$in_string = false;
		}
		elseif(!$in_string && ($sql[$i] == '"' || $sql[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\")) {
			$in_string = $sql[$i];
		}
		if(isset($buffer[1])) {
			$buffer[0] = $buffer[1];
		}
		$buffer[1] = $sql[$i];
	}

	if(!empty($sql)) {
		$ret[] = $sql;
	}
	return($ret);
}

$isErr = intval( count( $errors ) );

echo "<?xml version=\"1.0\" encoding=\"".$charset."\"?".">";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $text_direction;?>">
<head>
<title><?php echo T_('Mambo - Web Installer') ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset ?>" />
<link rel="shortcut icon" href="../images/favicon.ico" />
<link rel="stylesheet" href="install<?php if($text_direction=='rtl') echo '_'.$text_direction ?>.css" type="text/css" />
<script type="text/javascript">
<!--
function check() {
	// form validation check
	var formValid = true;
	var f = document.form;
	if ( f.sitename.value == '' ) {
		alert('<?php echo T_('Please enter a Site Name') ?>');
		f.sitename.focus();
		formValid = false
	}
	return formValid;
}
//-->
</script>
</head>
<body onload="document.form.sitename.focus();">
<div id="wrapper">
	<div id="header">
	  <div id="mambo"><img src="header_install.png" alt="<?php echo T_('Mambo Installation') ?>" /></div>
	</div>
</div>

<div id="ctr" align="center">
	<form action="install3.php" method="post" name="form" id="form" onsubmit="return check();">
	<input type="hidden" name="DBhostname" value="<?php echo "$DBhostname"; ?>" />
	<input type="hidden" name="DBuserName" value="<?php echo "$DBuserName"; ?>" />
	<input type="hidden" name="DBpassword" value="<?php echo "$DBpassword"; ?>" />
	<input type="hidden" name="DBname" value="<?php echo "$DBname"; ?>" />
	<input type="hidden" name="DBPrefix" value="<?php echo "$DBPrefix"; ?>" />
	<input type="hidden" name="DBcreated" value="<?php echo "$DBcreated"; ?>" />
	<div class="install">
		<div id="stepbar">
		  	<div class="step-off"><?php echo T_('pre-installation check') ?></div>
	  		<div class="step-off"><?php echo T_('license') ?></div>
		  	<div class="step-off"><?php echo T_('step 1') ?></div>
		  	<div class="step-on"><?php echo T_('step 2') ?></div>
	  		<div class="step-off"><?php echo T_('step 3') ?></div>
		  	<div class="step-off"><?php echo T_('step 4') ?></div>
  			<div class="far-right">
<?php if (!$isErr) { ?>
  		  		<input class="button" type="submit" name="next" value="<?php echo T_('Next') ?> >>"/>
<?php } ?>
  			</div>
		</div>
		<div id="right">
	  		<div id="step">
	  		<?php 
	  		if (!$isErr) { 
	  			echo T_('Step 2');  
	  		} else 
	  		{ 
	  			echo T_('Step 1 - Error Report');
	  		}
	  		?></div>
			<div id="steposi"></div>
  			<div class="clr"></div>

  			<h1><?php if (!$isErr) { echo T_('Enter the name of your Mambo site:'); } ?></h1>
			<div class="install-text">
<?php if ($isErr) { ?>
			<?php echo T_('Looks like there have been some errors with inserting data into your database!<br /><br />
  			You cannot continue.') ?>
<?php } else { ?>
			<?php echo T_('SUCCESS!') ?>
			<br/>
			<br/>
  			<?php echo T_('Type in the name for your Mambo site. This name is used in email messages so make it something meaningful.') ?>
<?php } ?>
  		</div>
  		<div class="install-form">
  			<div class="form-block">
  				<table class="content2">
<?php
			if ($isErr) {
				echo '<tr><td colspan="2">';
				echo '<b></b>';
				echo "<br/><br />".T_('Error log:')."<br />\n";
				// abrupt failure
				echo '<textarea rows="10" cols="50">';
				foreach($errors as $error) {
					echo "SQL=$error[0]:\n- - - - - - - - - -\n$error[1]\n= = = = = = = = = =\n\n";
				}
				echo '</textarea>';
				echo "</td></tr>\n";
  			} else {
?>
  				<tr>
  					<td width="100"><?php echo T_('Site name') ?></td>
  					<td align="center"><input class="inputbox" type="text" name="sitename" size="50" value="<?php echo "{$configArray['sitename']}"; ?>" /></td>
  				</tr>
  				<tr>
  					<td width="100">&nbsp;</td>
  					<td align="center" class="small"><?php echo T_('e.g. The Home of Mambo') ?></td>
  				</tr>
  				</table>
<?php
  			} // if
?>
  			</div>
  		</div>
		</div>
		<div class="clr"></div>
	</div>
	<div class="clr"></div>
	</form>
</div>
<div class="ctr">
<?php echo T_('<a href="http://www.mambo-foundation.org" target="_blank">Mambo </a> is Free Software released under the <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">GNU/GPL License</a>.') ?>
</div>
</body>
</html>
