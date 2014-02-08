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
if (!defined('_VALID_MOS')) define( '_VALID_MOS', 1 );

if (file_exists( '../configuration.php' ) && filesize( '../configuration.php' ) > 10) {
	header( 'Location: ../index.php' );
	exit();
}
/** Include common.php */
include_once( 'common.php' );
include_once( 'langconfig.php' );

function writableCell( $folder ) {
	echo "<tr>";
	echo "<td class=\"item\">" . $folder . "/</td>";
	echo "<td align=\"left\">";
	echo is_writable( "../$folder" ) ? '<b><span class="green">'.T_('Writeable').'</span></b>' : '<b><span class="red">'.T_('Unwriteable').'</span></b>' . "</td>";
	echo "</tr>";
}
?>
<?php echo "<?xml version=\"1.0\" encoding=\"".$charset."\"?".">"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $text_direction;?>">
<head>
<title><?php echo T_('Mambo - Web Installer') ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset ?>" />
<link rel="shortcut icon" href="../images/favicon.ico" />
<link rel="stylesheet" href="install<?php if($text_direction=='rtl') echo '_'.$text_direction ?>.css" type="text/css" />
<script type="text/javascript">
<!--
var checkobj;

function agreesubmit(el){
	checkobj=el;
	if (document.all||document.getElementById){
		for (i=0;i<checkobj.form.length;i++){  //hunt down submit button
			var tempobj=checkobj.form.elements[i];
			if(tempobj.type.toLowerCase()=="submit")
				tempobj.disabled=!checkobj.checked;
		}
	}
}

function defaultagree(el){
	if (!document.all&&!document.getElementById){
		if (window.checkobj&&checkobj.checked)
			return true;
		else{
			alert("<?php echo T_('Please read/accept license to continue installation') ?>");
			return false;
		}
	}
}
//-->
</script>
</head>
<body onload="document.adminForm.next.disabled=true;">
<div id="wrapper">
	<div id="header">
		<div id="mambo"><img src="header_install.png" alt="<?php echo T_('Mambo Installation') ?>" /></div>
	</div>
</div>
<div id="ctr" align="center">
	<form action="install1.php" method="post" name="adminForm" id="adminForm" onSubmit="return defaultagree(this)">
	<div class="install">
	<div id="stepbar">
		<div class="step-off"><?php echo T_('pre-installation check') ?></div>
		<div class="step-on"><?php echo T_('license') ?></div>
		<div class="step-off"><?php echo T_('step 1') ?></div>
		<div class="step-off"><?php echo T_('step 2') ?></div>
		<div class="step-off"><?php echo T_('step 3') ?></div>
		<div class="step-off"><?php echo T_('step 4') ?></div>
		<div class="far-right">
		<input class="button" type="submit" name="next" value="<?php echo T_('Next') ?> >>" disabled="disabled"/>
		</div>
	</div>
	<div id="right">
		<div id="step"><?php echo T_('license') ?></div>
		<div id="steposi"></div>
		<div class="clr"></div>
		<h1><?php echo T_('GNU/GPL License:') ?></h1>
		<div class="licensetext">
		<?php echo T_('<a href="http://www.mambo-foundation.org" target="_blank">Mambo </a> is Free Software released under the <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">GNU/GPL License</a>.<div class="error">*** To continue installing Mambo you must check the box under the license ***</div>') ?>
				

		</div>
		<div class="clr"></div>
		<div class="license-form">
			<div class="form-block" style="padding: 0px;">
				<iframe src="gpl.txt" class="license" frameborder="0" scrolling="auto"></iframe>
			</div>
		</div>
		<div class="clr"></div>
		<div class="ctr">
				<input type="checkbox" name="agreecheck" id="agreecheck" class="inputbox" onClick="agreesubmit(this)" />
				<label for="agreecheck">
					<?php echo T_('I understand that this software is released under the GNU/GPL License') ?></label>
				</div>
		<div class="clr"></div>
		</div>
	<div class="clr"></div>
	<div class="clr"></div>
	</div>
	</form>
</div>
<div class="ctr">
<?php echo T_('<a href="http://www.mambo-foundation.org" target="_blank">Mambo </a> is Free Software released under the <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">GNU/GPL License</a>.') ?>
</div>
</body>
</html>
