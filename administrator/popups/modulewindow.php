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

$css = mosGetParam( $_REQUEST, 't', '');
$iso = split( '=', _ISO );
// xml prolog
echo '<?xml version="1.0" encoding="'. $iso[1] .'"?' .'>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo T_('Module Preview') ?></title>
	<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
	<link rel="stylesheet" href="../../templates/<?php echo $css; ?>/css/template_css.css" type="text/css">
	<script>
		var content = window.opener.document.adminForm.content.value;
		var title = window.opener.document.adminForm.title.value;

		content = content.replace('#', '');
		title = title.replace('#', '');
		content = content.replace('src=images', 'src=../../images');
		content = content.replace('src=images', 'src=../../images');
		title = title.replace('src=images', 'src=../../images');
		content = content.replace('src=images', 'src=../../images');
		title = title.replace('src=\"images', 'src=\"../../images');
		content = content.replace('src=\"images', 'src=\"../../images');
		title = title.replace('src=\"images', 'src=\"../../images');
		content = content.replace('src=\"images', 'src=\"../../images');
	</script>
</head>

<body style="background-color:#FFFFFF">
<table align="center" width="160" cellspacing="2" cellpadding="2" border="0" height="100%">
	<tr>
	    <td class="moduleheading"><script>document.write(title);</script></td>
	</tr>
	<tr>
	    <td valign="top" height="90%"><script>document.write(content);</script></td>
	</tr>
	<tr>
	    <td align="center"><a href="#" onClick="window.close()"><?php echo T_('Close') ?></a></td>
	</tr>
</table>
</body>
</html>
