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


//$adminside = 3;
//require_once('../../index.php');

$directory = mosGetParam( $_REQUEST, 'directory', '');

$userfile2=(isset($_FILES['userfile']['tmp_name']) ? $_FILES['userfile']['tmp_name'] : "");
$userfile_name=(isset($_FILES['userfile']['name']) ? $_FILES['userfile']['name'] : "");

if (isset($_FILES['userfile'])) {
	if ($directory!="banners") {
		$base_Dir = "../images/stories/";
	} else {
		$base_Dir = "../images/banners/";
	}
	if (empty($userfile_name)) {
		echo "<script>alert('".T_('Please select an image to upload') ."'); document.location.href='uploadimage.php';</script>";
	}

	$filename = split("\.", $userfile_name);

	if (eregi("[^0-9a-zA-Z_]", $filename[0])) {
		echo "<script> alert('".T_('File must only contain alphanumeric characters and no spaces please.') ."'); window.history.go(-1);</script>\n";
		exit();
	}

	if (file_exists($base_Dir.$userfile_name)) {
		echo "<script> alert('".sprintf(T_('Image %s already exists.'),$userfile_name) ."'); window.history.go(-1);</script>\n";
		exit();
	}

	if ((strcasecmp(substr($userfile_name,-4),".gif")) && (strcasecmp(substr($userfile_name,-4),".jpg")) && (strcasecmp(substr($userfile_name,-4),".png")) && (strcasecmp(substr($userfile_name,-4),".bmp")) &&(strcasecmp(substr($userfile_name,-4),".doc")) && (strcasecmp(substr($userfile_name,-4),".xls")) && (strcasecmp(substr($userfile_name,-4),".ppt")) && (strcasecmp(substr($userfile_name,-4),".swf")) && (strcasecmp(substr($userfile_name,-4),".pdf"))) {
		echo "<script>alert('".T_('The file must be gif, png, jpg, bmp, swf, doc, xls or ppt') ."'); window.history.go(-1);</script>\n";
		exit();
	}


	if (eregi(".pdf", $userfile_name) || eregi(".doc", $userfile_name) || eregi(".xls", $userfile_name) || eregi(".ppt", $userfile_name)) {
		if (!move_uploaded_file ($_FILES['userfile']['tmp_name'],$media_path.$_FILES['userfile']['name']) || !mosChmod($media_path.$_FILES['userfile']['name'])) {
			echo "<script>alert('".sprintf(T_('Upload of %s failed'), $userfile_name) ."'); window.history.go(-1);</script>\n";
			exit();
		}
		else {
			echo "<script>alert('".sprintf(T_('Upload of %s to %s successful'), $userfile_name, $media_path) ."'); window.history.go(-1);</script>\n";
			exit();
		}
	} elseif (!move_uploaded_file ($_FILES['userfile']['tmp_name'],$base_Dir.$_FILES['userfile']['name']) || !mosChmod($base_Dir.$_FILES['userfile']['name'])) {
		echo "<script>alert('".sprintf(T_('Upload of %s failed'), $userfile_name) ."'); window.history.go(-1);</script>\n";
		exit();
	}
	else {
		echo "<script>alert('".sprintf(T_('Upload of %s to %s successful'), $userfile_name, $base_Dir) ."'); window.history.go(-1);</script>\n";
		exit();
	}


}

$iso = split( '=', _ISO );
// xml prolog
echo '<?xml version="1.0" encoding="'. $iso[1] .'"?' .'>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Upload a file</title>
<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
<?php
$css = mosGetParam($_REQUEST,"t","");
?>
<link rel="stylesheet" href="../templates/<?php echo $css; ?>/css/template_css.css" type="text/css" />
</head>
<body>
<table class="adminform">
  <form method="post" action="index3.php?pop=uploadimage.php" enctype="multipart/form-data" name="filename">
    <tr>
      <th class="title"> <?php echo T_('File Upload :') ?> <?php echo $directory; ?></th>
    </tr>
    <tr>
      <td align="center">
        <input class="inputbox" name="userfile" type="file" />
      </td>
    </tr>
    <tr>
      <td>
        <input class="button" type="submit" value="Upload" name="fileupload" />
        <?php echo T_('Max size') ?> = <?php echo ini_get( 'post_max_size' );?>
      </td>
    <tr>
      <td>
        <input type="hidden" name="directory" value="<?php echo $directory;?>" />
      </td>
    </tr>
  </form>
</table>
</body>
</html>