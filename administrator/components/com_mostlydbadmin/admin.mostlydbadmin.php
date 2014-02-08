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

// ensure user is coming from the admin side and has access to this function
if (!($my->usertype=='Super Administrator') && $adminside>0) {
	mosRedirect( 'index2.php', T_('You are not authorized to view this resource.') );
}

require_once( $mainframe->getPath( 'admin_html' ) );
require_once( "$mosConfig_absolute_path/administrator/includes/pcl/pclzip.lib.php" );

$task	= mosGetParam( $_REQUEST, "task", "" );
$file	= mosGetParam( $_POST, "file", null );
$upfile	= mosGetParam($_FILES,"upfile",null);
$tables = mosGetParam( $_POST, "tables", null );
$OutType = mosGetParam( $_POST, "OutType", null );
$OutDest = mosGetParam( $_POST, "OutDest", null );
$toBackUp = mosGetParam( $_POST, "toBackUp", null );

switch ($task) {
	case "dbBackup":
		dbBackup( $option);
		break;

	case "doBackup":
		doBackup( $tables,$OutType,$OutDest,$toBackUp,$_SERVER['HTTP_USER_AGENT'], $local_backup_path);
		break;

	case "dbRestore":
		dbRestore( $local_backup_path);
		break;

	case "doRestore":
		doRestore( $file,$upfile,$local_backup_path);
		break;

	case "xquery":
		xquery( $option );
		break;
}


function dbBackup( $p_option ) {
	global $database;

	$database->setQuery( "SHOW tables" );
	$tables = $database->loadResultArray();
	$tables2 = array( mosHTML::makeOption( 'all', T_('All Mambo Tables') ) );
	foreach ($tables as $table) {
		$tables2[] = mosHTML::makeOption( $table );
	}

	$tablelist = mosHTML::selectList( $tables2, 'tables[]', 'class="inputbox" size="5" multiple="multiple"',
	'value', 'text', 'all' );

	HTML_dbadmin::backupIntro( $tablelist, $p_option );
}

function doBackup( $tables, $OutType, $OutDest, $toBackUp, $UserAgent, $local_backup_path) {
	global $database;
	global $mosConfig_db, $mosConfig_sitename, $version,$option,$task;

	if (!$tables[0])
	{
		HTML_dbadmin::showDbAdminMessage(T_('Error! No database table(s) specified. Please select at least one table and re-try.</p>'), T_('DB Admin'),$option,$task);
		return;
	}

	/* Need to know what browser the user has to accomodate nonstandard headers */

	if (ereg('Opera(/| )([0-9].[0-9]{1,2})', $UserAgent)) {
		$UserBrowser = "Opera";
	}
	elseif (ereg('MSIE ([0-9].[0-9]{1,2})', $UserAgent)) {
		$UserBrowser = "IE";
	} else {
		$UserBrowser = '';
	}

	/* Determine the mime type and file extension for the output file */

	if ($OutType == "bzip") {
		$filename = $mosConfig_db . "_" . date("YmdHis") . ".bz2";
		$mime_type = 'application/x-bzip';
	} elseif ($OutType == "gzip") {
		$filename = $mosConfig_db . "_" . date("YmdHis") . ".sql.gz";
		$mime_type = 'application/x-gzip';
	} elseif ($OutType == "zip") {
		$filename = $mosConfig_db . "_" . date("YmdHis") . ".zip";
		$mime_type = 'application/x-zip';
	} elseif ($OutType == "html") {
		$filename = $mosConfig_db . "_" . date("YmdHis") . ".html";
		$mime_type = ($UserBrowser == 'IE' || $UserBrowser == 'Opera') ? 'application/octetstream' : 'application/octet-stream';
	} else {
		$filename = $mosConfig_db . "_" . date("YmdHis") . ".sql";
		$mime_type = ($UserBrowser == 'IE' || $UserBrowser == 'Opera') ? 'application/octetstream' : 'application/octet-stream';
	};

	/* Store all the tables we want to back-up in variable $tables[] */

	if ($tables[0] == "all") {
		array_pop($tables);
		$database->setQuery("SHOW tables");
		$database->query();
		$tables = array_merge($tables, $database->loadResultArray());
	}

	/* Store the "Create Tables" SQL in variable $CreateTable[$tblval] */
	if ($toBackUp!="data")
	{
		foreach ($tables as $tblval)
		{
			$database->setQuery("SHOW CREATE table $tblval");
			$database->query();
			$CreateTable[$tblval] = $database->loadResultArray(1);
		}
	}

	/* Store all the FIELD TYPES being backed-up (text fields need to be delimited) in variable $FieldType*/
	if ($toBackUp!="structure")
	{
		foreach ($tables as $tblval)
		{
			$database->setQuery("SHOW FIELDS FROM $tblval");
			$database->query();
			$fields = $database->loadObjectList();
			foreach($fields as $field)
			{
				$FieldType[$tblval][$field->Field] = preg_replace("/[(0-9)]/",'', $field->Type);
			}
		}
	}

	/* Build the fancy header on the dump file */
	$OutBuffer = "";
	if ($OutType == 'html') {
	} else {
		$OutBuffer .= "#\n";
		$OutBuffer .= "# Mambo MySQL-Dump\n";
		$OutBuffer .= "# http://www.mambo-foundation.org\n";
		$OutBuffer .= "#\n";
		$OutBuffer .= "# Host: $mosConfig_sitename\n";
		$OutBuffer .= "# Generation Time: " . date("M j, Y \a\\t H:i") . "\n";
		$OutBuffer .= "# Server version: " . $database->getVersion() . "\n";
		$OutBuffer .= "# PHP Version: " . phpversion() . "\n";
		$OutBuffer .= "# Database : `" . $mosConfig_db . "`\n# --------------------------------------------------------\n";
	}

	/* Okay, here's the meat & potatoes */
	foreach ($tables as $tblval) {
		if ($toBackUp != "data") {
			if ($OutType == 'html') {
			} else {
				$OutBuffer .= "#\n# Table structure for table `$tblval`\n";
				$OutBuffer .= "#\nDROP table IF EXISTS $tblval;\n";
				$OutBuffer .= $CreateTable[$tblval][0].";\r\n";
			}
		}
		if ($toBackUp != "structure") {
			if ($OutType == 'html') {
				$OutBuffer .= "<div align=\"left\">";
				$OutBuffer .= "<table cellspacing=\"0\" cellpadding=\"2\" border=\"1\">";
				$database->setQuery("SELECT * FROM $tblval");
				$rows = $database->loadObjectList();

				$OutBuffer .= "<tr><th colspan=\"".count( @array_keys( @$rows[0] ) )."\">`$tblval`</th></tr>";
				if (count( $rows )) {
					$OutBuffer .= "<tr>";
					foreach($rows[0] as $key => $value) {
						$OutBuffer .= "<th>$key</th>";
					}
					$OutBuffer .= "</tr>";
				}

				if ($rows) foreach($rows as $row)
				{
					$OutBuffer .= "<tr>";
					foreach (get_object_vars($row) as $key=>$value)
					{
						$value = addslashes( $value );
						$value = str_replace( "\n", '\r\n', $value );
						$value = str_replace( "\r", '', $value );

						$value = htmlspecialchars( $value );

						if (preg_match ("/\b" . $FieldType[$tblval][$key] . "\b/i", "DATE TIME DATETIME CHAR VARCHAR TEXT TINYTEXT MEDIUMTEXT LONGTEXT BLOB TINYBLOB MEDIUMBLOB LONGBLOB ENUM SET"))
						{
							$OutBuffer .= "<td>'$value'</td>";
						}
						else
						{
							$OutBuffer .= "<td>$value</td>";
						}
					}
					$OutBuffer .= "</tr>";
				}
				$OutBuffer .= "</table></div><br />";
			} else {
				$OutBuffer .= "#\n# Dumping data for table `$tblval`\n#\n";
				$database->setQuery("SELECT * FROM $tblval");
				$rows = $database->loadObjectList(); if (!$rows) $rows = array();
				foreach($rows as $row)
				{
					$InsertDump = "INSERT INTO $tblval VALUES (";
					//$arr = mosObjectToArray($row);
					//foreach($arr as $key => $value)
					foreach (get_object_vars($row) as $key=>$value)
					{
						$value = addslashes( $value );
						$value = str_replace( "\n", '\r\n', $value );
						$value = str_replace( "\r", '', $value );
						if (preg_match ("/\b" . $FieldType[$tblval][$key] . "\b/i", "DATE TIME DATETIME CHAR VARCHAR TEXT TINYTEXT MEDIUMTEXT LONGTEXT BLOB TINYBLOB MEDIUMBLOB LONGBLOB ENUM SET"))
						{
							$InsertDump .= "'$value',";
						}
						else
						{
							$InsertDump .= "$value,";
						}
					}
					$OutBuffer .= rtrim($InsertDump,',') . ");\n";
				}
			}
		}
	}

	/* Send the HTML headers */
	if ($OutDest == "remote") {
		// dump anything in the buffer
		@ob_end_clean();
		ob_start();
		header('Content-Type: ' . $mime_type);
		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');

		if ($UserBrowser == 'IE') {
			header('Content-Disposition: inline; filename="' . $filename . '"');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
		} else {
			header('Content-Disposition: attachment; filename="' . $filename . '"');
			header('Pragma: no-cache');
		}
	}

	if ($OutDest == "screen" || $OutType == "html" ) {
		if ($OutType == "html") {
				echo $OutBuffer;
		} else {
			$OutBuffer = str_replace("<","&lt;",$OutBuffer);
			$OutBuffer = str_replace(">","&gt;",$OutBuffer);
			?>
			<form>
				<textarea rows="20" cols="80" name="sqldump"  style="background-color:#e0e0e0"><?php echo $OutBuffer;?></textarea>
				<br />
				<input type="button" onclick="javascript:this.form.sqldump.focus();this.form.sqldump.select();" class="button" value="Select All" />
			</form>
			<?php
		}
		exit();
	}
			
	switch ($OutType) {
		case "sql" :
			if ($OutDest == "local") {
				$fp = fopen("$local_backup_path/$filename", "w");
				if (!$fp) {
					HTML_dbadmin::showDbAdminMessage(sprintf(T_('Database backup FAILURE!!<br />File %s/%s not writable<br />Please contact your admin/webmaster!</p>'),$local_backup_path,$filename),T_('DB Admin'),$option,$task);
					return;
				} else {
					fwrite($fp, $OutBuffer);
					fclose($fp);
					HTML_dbadmin::showDbAdminMessage(sprintf(T_('Database backup successful! Your file was saved on the server in directory :<br />%s/%s</p>'),$local_backup_path,$filename),T_('DB Admin'),$option,$task);
					return;
				}
			} else {
				echo $OutBuffer;
				ob_end_flush();
				ob_start();
				// do no more
				exit();
			}
			break;
		case "bzip" :
			if (function_exists('bzcompress')) {
				if ($OutDest == "local") {
					$fp = fopen("$local_backup_path/$filename", "wb");
					if (!$fp) {
						echo "<p align=\"center\"  class=\"error\">".sprintf(T_('Database backup FAILURE!!<br />File %s/%s not writable<br />Please contact your admin/webmaster!'),$local_backup_path,$filename)."</p>";
					} else {
						fwrite($fp, bzcompress($OutBuffer));
						fclose($fp);
						HTML_dbadmin::showDbAdminMessage(sprintf(T_('Database backup successful! Your file was saved on the server in directory :<br />%s/%s</p>'),$local_backup_path,$filename),T_('DB Admin'), $option,$task);
						return;
					}
				} else {
					echo bzcompress($OutBuffer);
					ob_end_flush();
					ob_start();
					// do no more
					exit();
				}
			} else {
				echo $OutBuffer;
			}
			break;
		case "gzip" :
			if (function_exists('gzencode')) {
				if ($OutDest == "local") {
					$fp = gzopen("$local_backup_path/$filename", "wb");
					if (!$fp) {
						HTML_dbadmin::showDbAdminMessage(sprintf(T_('Database backup FAILURE!!<br />File %s/%s not writable<br />Please contact your admin/webmaster!</p>'),$local_backup_path,$filename),T_('DB Admin'),$option,$task);
						return;
					} else {
						gzwrite($fp,$OutBuffer);
						gzclose($fp);
						HTML_dbadmin::showDbAdminMessage(sprintf(T_('Database backup successful! Your file was saved on the server in directory :<br />%s/%s</p>'),$local_backup_path,$filename),T_('DB Admin'),$option,$task);
						return;
					}
				} else {
					echo gzencode($OutBuffer);
					ob_end_flush();
					ob_start();
					// do no more
					exit();
				}
			} else {
				echo $OutBuffer;
			}
			break;
		case "zip" :
			if (function_exists('gzcompress')) {
				include "classes/zip.lib.php";
				$zipfile = new zipfile();
				$zipfile -> addFile($OutBuffer, $filename . ".sql");
				}
			switch ($OutDest) {
				case "local" :
					$fp = fopen("$local_backup_path/$filename", "wb");
					if (!$fp) {
						HTML_dbadmin::showDbAdminMessage(sprintf(T_('Database backup FAILURE!!<br />File %s/%s not writable<br />Please contact your admin/webmaster!</p>'),$local_backup_path,$filename),T_('DB Admin'),$option,$task);
						return;
					} else {
						fwrite($fp, $zipfile->file());
						fclose($fp);
						HTML_dbadmin::showDbAdminMessage(sprintf(T_('Database backup successful! Your file was saved on the server in directory :<br />%s/%s</p>'),$local_backup_path,$filename),T_('DB Admin'),$option,$task);
						return;
					}
					break;
				case "remote" :
					echo $zipfile->file();
					ob_end_flush();
					ob_start();
					// do no more
					exit();
					break;
				default :
					echo $OutBuffer;
					break;
			}
			break;
	}
}

function dbRestore( $local_backup_path) {
	global $database;

	$uploads_okay = (function_exists('ini_get')) ? ((strtolower(ini_get('file_uploads')) == 'on' || ini_get('file_uploads') == 1) && intval(ini_get('upload_max_filesize'))) : (intval(@get_cfg_var('upload_max_filesize')));
	if ($uploads_okay)
	{
		$enctype = " enctype=\"multipart/form-data\"";
	}
	else
	{
		$enctype = '';
	}

	HTML_dbadmin::restoreIntro($enctype,$uploads_okay,$local_backup_path);
}

function doRestore( $file, $uploadedFile, $local_backup_path ) {
	global $database, $option,$task,$mosConfig_absolute_path;

	if(!is_null($uploadedFile) && is_array($uploadedFile) && $uploadedFile["name"] != "")
	{
		$base_Dir = $mosConfig_absolute_path . "/uploadfiles/";
		if (!move_uploaded_file($uploadedFile['tmp_name'], $base_Dir . $uploadedFile['name']))
		{
			HTML_dbadmin::showDbAdminMessage(T_('Error! could not move uploaded file.</p>'),T_('DB Admin - Restore'),$option,$task);
			return false;
		}

	}
	if ((!$file) && (!$uploadedFile['name']))
	{
		HTML_dbadmin::showDbAdminMessage(T_('Error! No restore file specified.</p>'),T_('DB Admin - Restore'),$option,$task);
		return;
	}

	if ($file)
	{
		if (isset($local_backup_path))
		{
			$infile		= $local_backup_path . "/" . $file;
			$upfileFull	= $file;
			$destfile = $mosConfig_absolute_path . "/uploadfiles/$file";

			// If it's a zip file, we copy it so we can extract it
			if(eregi(".\.zip$",$upfileFull))
			{
				copy($infile,$destfile);
			}
		}
		else
		{
			HTML_dbadmin::showDbAdminMessage(T_('Error! Backup path in your configuration file has not been configured.</p>'),T_('DB Admin - Restore'),$option,$task);
			return;
		}
	}
	else
	{

		$upfileFull	= $uploadedFile['name'];
		$infile	= $base_Dir . $uploadedFile['name']; 
		
	}

	if (!eregi(".\.sql$",$upfileFull) && !eregi(".\.bz2$",$upfileFull) && !eregi(".\.gz$",$upfileFull) && !eregi(".\.zip$",$upfileFull))
	{
		HTML_dbadmin::showDbAdminMessage(sprintf(T_('Error! Invalid file extension in input file (%s).<br />Only *.sql, *.bz2, or *.gz files may be uploaded.</p>'),$upfileFull),T_('DB Admin - Restore'),$option,$task);
		return;
	}
	
	if (substr($upfileFull,-3)==".gz")
	{
		if (function_exists('gzinflate'))
		{
			$fp=fopen("$infile","rb");
			if ((!$fp) || filesize("$infile")==0)
			{
				HTML_dbadmin::showDbAdminMessage(sprintf(T_('Error! Unable to open input file (%s) for reading or file contains no records.</p>'),$infile),T_('DB Admin - Restore'),$option,$task);
				return;
			}
			else
			{
				$content = fread($fp,filesize("$infile"));
				fclose($fp);
				$content = gzinflate(substr($content,10));
			}
		}
		else
		{
			HTML_dbadmin::showDbAdminMessage(T_('Error! Unable to process gzip file as gzinflate function is unavailable.</p>'),T_('DB Admin - Restore'),$option,$task);
			return;
		}
	}
	elseif (substr($upfileFull,-4)==".bz2")
	{
		if (function_exists('bzdecompress'))
		{
			$fp=fopen("$infile","rb");
			if ((!$fp) || filesize("$infile")==0)
			{
				HTML_dbadmin::showDbAdminMessage(sprintf(T_('Error! Unable to open input file (%s) for reading or file contains no records.</p>'),$infile),T_('DB Admin - Restore'),$option,$task);
				return;
			}
			else
			{
				$content=fread($fp,filesize("$infile"));
				fclose($fp);
				$content=bzdecompress($content);
			}
		}
		else
		{
			HTML_dbadmin::showDbAdminMessage(T_('Error! Unable to process bzip file as bzdecompress function is unavailable.</p>'),T_('DB Admin - Restore'),$option,$task);
			return;
		}
	}
	elseif (substr($upfileFull,-4)==".sql")
	{
echo T_('trying to access').' '.$infile;
		$fp=fopen("$infile","r");
		if ((!$fp) || filesize("$infile")==0)
		{
			HTML_dbadmin::showDbAdminMessage(sprintf(T_('Error! Unable to open input file (%s) for reading or file contains no records.</p>'),$infile),T_('DB Admin - Restore'),$option,$task);
			return;
		}
		else
		{
			$content=fread($fp,filesize("$infile"));
			fclose($fp);
		}
	}
	elseif (substr($upfileFull,-4)==".zip")
	{
		// unzip the file
		$base_Dir		= $mosConfig_absolute_path . "/uploadfiles/";
		$archivename	= $base_Dir . $upfileFull;
		$tmpdir			= uniqid("dbrestore_");

		$isWindows = (substr(PHP_OS, 0, 3) == 'WIN' && stristr ( $_SERVER["SERVER_SOFTWARE"], "microsoft"));
		if($isWindows)
		{
			$extractdir	= str_replace('/','\\',$base_Dir . "$tmpdir/");
			$archivename = str_replace('/','\\',$archivename);
		}
		else
		{
			$extractdir	= str_replace('\\','/',$base_Dir . "$tmpdir/");
			$archivename = str_replace('\\','/',$archivename);
		}

		$zipfile	= new PclZip($archivename);
		if($isWindows)
			define('OS_WINDOWS',1);

		$ret = $zipfile->extract(PCLZIP_OPT_PATH,$extractdir);
		if($ret == 0)
		{
			HTML_dbadmin::showDbAdminMessage(sprintf(T_('Unrecoverable error \'%s\''),$zipfile->errorName(true)),T-('DB Admin - Restore'),$option,$task);
			return false;
		}
		$filesinzip = $zipfile->listContent();
		if(is_array($filesinzip) && count($filesinzip) > 0)
		{
			$fp			= fopen($extractdir . $filesinzip[0]["filename"],"r");
			$content	= fread($fp,filesize($extractdir . $filesinzip[0]["filename"]));
			fclose($fp);

			// Cleanup temp extract dir
			deldir($extractdir);
			//unlink($mosConfig_absolute_path . "uploadfiles/$file");

		}
		else
		{
			HTML_dbadmin::showDbAdminMessage(sprintf(T_('No SQL file found in %s'),$upfileFull),T_('DB Admin - Restore'),$option,$task);
			return;
		}
	}
	else
	{
		HTML_dbadmin::showDbAdminMessage(sprintf(T_('Error! Unrecognized input file type. (%s : %s)</p>'),$infile,$upfileFull),T_('DB Admin - Restore'),$option,$task);
		return;
	}


	$decodedIn	= explode(chr(10),$content);
	$decodedOut	= "";
	$queries	= 0;

	foreach ($decodedIn as $rawdata)
	{
		$rawdata=trim($rawdata);
		if (($rawdata!="") && ($rawdata{0}!="#"))
		{
			$decodedOut .= $rawdata;
			if (substr($rawdata,-1)==";")
			{
				if  ((substr($rawdata,-2)==");") || (strtoupper(substr($decodedOut,0,6))!="INSERT"))
				{
					if (eregi('^(DROP|CREATE)[[:space:]]+(IF EXISTS[[:space:]]+)?(DATABASE)[[:space:]]+(.+)', $decodedOut))
					{
						HTML_dbadmin::showDbAdminMessage(T_('Error! Your input file contains a DROP or CREATE DATABASE statement. Please delete these statements before trying to restore the file.</p>'),T_('DB Admin - Restore'),$option,$task);
						return;
					}
					$database->setQuery($decodedOut);
					$database->query();
					$decodedOut="";
					$queries++;
				}
			}
		}
	}
	HTML_dbadmin::showDbAdminMessage(sprintf(T_('Success! Database has been restored to the backup you requested (%d SQL queries processed).</p>'),$queries),T_('DB Admin - Restore'),$option,$task);
	return;
}

function deldir($dir)
{
	$current_dir = opendir($dir);
	while($entryname = readdir($current_dir))
	{
    	if(is_dir("$dir/$entryname") and ($entryname != "." and $entryname!=".."))
    	{
			deldir("${dir}/${entryname}");
		}
		elseif($entryname != "." and $entryname!="..")
		{
			unlink("${dir}/${entryname}");
		}
	}
	closedir($current_dir);
	rmdir($dir);
}

function xquery( $option ) {
	global $database;

	$rows = null;
	$msg = '';
	$sql = trim( mosGetParam( $_POST, 'sql', '' ) );
	$batch = intval( mosGetParam( $_POST, 'batch', 0 ) );

	$allowed = array( "CREATE", "SELECT", "INSERT", "UPDATE", "DROP", "ALTER" );
	$words = preg_split( "/\s+/", $sql );
	$cmd = strtoupper( $words[0] );

	if ($sql == "") {
		$msg = T_('The query was empty.');
	} else if (!in_array( $cmd, $allowed)) {
		$msg = sprintf(T_('You are not permitted to execute a <strong>%s</strong> query'),$cmd);
	} else {
		$database->setQuery( $sql );
		if ($batch) {
			// run batch, don't abort on error
			$r = $database->query_batch( false );
		} else {
			$r = $database->query();
		}
		if ($r) {
			$msg = T_('The query executed successfully.');
			$msg .= sprintf(T_('<br />%d rows where affected.'),intval( $database->getNumRows() ));

			if ($cmd == "SELECT") {
				$rows = $database->loadObjectList();
			}
		} else {
			$msg = sprintf(T_('The query was unsuccessful.  It return the error code %d'),$database->getErrorNum());
			$msg .= "<br />" . $database->getErrorMsg() . "";
		}
	}

	HTML_dbadmin::xquery( $sql, $msg, $rows, $option );
}
?>