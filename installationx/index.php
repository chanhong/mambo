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
	header( "Location: ../index.php" );
	exit();
}
require_once( '../includes/version.php' );

$_VERSION = new version();

$version = $_VERSION->PRODUCT .' '. $_VERSION->RELEASE .'.'. $_VERSION->DEV_LEVEL .' '
. $_VERSION->DEV_STATUS
.' [ '.$_VERSION->CODENAME .' ] '. $_VERSION->RELDATE .' '
. $_VERSION->RELTIME .' '. $_VERSION->RELTZ;

/** Include common.php */
include_once( 'common.php' );

list($tmp_lang,$directions) = getLanguages();
$lang = trim( mosGetParam( $_POST, 'lang', '' ) );
$charset="utf-8";
$text_direction="ltr";
if($lang=='') {
	$lang="en";
}else
{
	$str_charset = explode(" ",$tmp_lang[$lang]);
	$charset = $str_charset[1];
	$text_direction = $directions[$lang];
		
}
$filename = "langconfig.php";
if(is_writable($filename)) {
	$handle = fopen($filename,'w+');
	$content = "<?php\n";
	$content.="defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );\n";
	$content .= "\$lang = \"$lang\";\n";
	$content .= "\$charset = \"$charset\";\n";
	$content .= "\$text_direction = \"$text_direction\";\n";
	$content .= "\$gettext =& phpgettext();\n";
	$content .= "\$gettext->debug       = '0';\n";
	$content .= "\$gettext->has_gettext = '0';\n";
	$content .= "\$gettext->setlocale(\$lang);\n";
	$content .= "\$gettext->bindtextdomain(\$lang, 'language/');\n";
	$content .= "\$gettext->textdomain(\$lang);\n";
	$content .= "?>";
	fwrite($handle,$content);
	fclose($handle);
}
include_once( 'langconfig.php' );
function getLanguages() {
        $langfiles = glob("language/*.xml");
		$langs = array();
		
		foreach($langfiles as $xml) {
			if(is_readable($xml)) {
				$source = file_get_contents($xml);
				$encoding = "UTF-8";
				if (preg_match('/<?xml.*encoding=[\'"](.*?)[\'"].*?>/m', $source, $m)) {
					$encoding = strtoupper($m[1]);
				}
				$parser = xml_parser_create("UTF-8");
				xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
				xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
				xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
				if (!xml_parse_into_struct($parser,$source,$values)) {
					die(sprintf("XML error: %s at ".$xml." in line %d",
					xml_error_string(xml_get_error_code($parser)),
					xml_get_current_line_number($parser)));
				}
				xml_parser_free($parser);
				$flag = true;
				$title = "English";
				foreach($values as $key=>$val) {
					$tag = strtolower($val['tag']);
					if($flag) {	
						if($tag=="locale") {
							$title = $val['attributes']['title'];
							$text_direction = $val['attributes']['text_direction'];
							$flag = false;
						}
					}
				}
				$name = str_replace("language/","",$xml);
				$name = str_replace(".xml","",$name);
				$langs[$name] = $title." ".strtolower($encoding);
				$directions[$name]=$text_direction;
			}
        }
        return Array($langs,$directions);
}

function get_php_setting($val) {
	$r =  (ini_get($val) == '1' ? 1 : 0);
	return $r ? T_('ON') : T_('OFF');
}

function writableCell( $folder ) {
	echo '<tr>';
	echo '<td class="item">' . $folder . '/</td>';
	echo '<td align="left">';
	echo is_writable( "../$folder" ) ? '<b><span class="green">'.T_('Writeable').'</span></b>' : '<b><span class="red">'.T_('Unwriteable').'</span></b>' . '</td>';
	echo '</tr>';
}

echo "<?xml version=\"1.0\" encoding=\"".$charset."\"?".">";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $text_direction;?>">
<head>
<title><?php echo T_('Mambo - Web Installer') ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset; ?>" />
<link rel="shortcut icon" href="../images/favicon.ico" />
<link rel="stylesheet" href="install<?php if($text_direction=='rtl') echo '_'.$text_direction ?>.css" type="text/css" />
<script type="text/javascript">
<!--
var checkobj
function agreesubmit(el){
	checkobj=el
	if (document.all||document.getElementById){
		for (i=0;i<checkobj.form.length;i++){  //hunt down submit button
		var tempobj=checkobj.form.elements[i]
		if(tempobj.type.toLowerCase()=="submit")
		tempobj.disabled=!checkobj.checked
		}
	}
}

function defaultagree(el){
	if (!document.all&&!document.getElementById){
		if (window.checkobj&&checkobj.checked)
		return true
		else{
			alert("<?php echo T_('Please read/accept license to continue installation')?>")
			return false
		}
	}
}
//-->
</script>
</head>
<body>

<div id="wrapper">
<div id="header">
<div id="mambo"><img src="header_install.png" alt="<?php echo T_('Mambo Installation') ?>" /></div>
</div>
</div>

<div id="ctr" align="center">
<div class="install">
<div id="stepbar">
<div class="step-on"><?php echo T_('pre-installation check') ?></div>
<div class="step-off"><?php echo T_('license') ?></div>
<div class="step-off"><?php echo T_('step 1') ?></div>
<div class="step-off"><?php echo T_('step 2') ?></div>
<div class="step-off"><?php echo T_('step 3') ?></div>
<div class="step-off"><?php echo T_('step 4') ?></div>
<div class="far-right">
<input name="Button2" type="submit" class="button" value="<?php echo T_('Next') ?> >>" onclick="window.location='install.php';" />
</div>
</div>

<div id="right">

<div id="step"><?php echo T_('pre-installation check') ?></div>
<div id="steposi"></div>

<div class="clr"></div>
<h1><?php echo T_('Pre-installation check for:') ?><!-- <br/> --> <?php echo $version; ?></h1>
	<form action="index.php" method="post" name="Langue">
				<h1><?php echo T_('Mambo installation language') ?>:</h1>
				<div class="install-text"> <?php echo T_('The installer automatically detects your browser language preferences. However, you can select one of the available languages.') ?>
					<div class="ctr"></div>
				</div>

				<div class="install-form">
					<div class="form-block">
						<table class="content">
							<tr>
								<td class="item"> <?php echo T_('Installation language') ?> </td>
								<td align="left">
									<?php
									echo '<select size="1" name="lang" onchange="this.form.submit();">';
									foreach ( $tmp_lang as $key=>$lang_found ){
										if( $key == $lang ){
											echo '<option value ="'.$key.'" selected="selected">'.ucfirst($lang_found)."</option>\n";
										}else{
											echo '<option value ="'.$key.'">'.ucfirst($lang_found)."</option>\n";
										}
									}
									echo '</select>';
									?>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="install-form">
					<div class="form-block">
						<table class="content">
							<tr>
								<td><strong><?php echo T_('Language check') ?></strong></td>
							<tr>
								<td><?php echo T_('Installation language') ?></td>
								<td>
									<font color="green"><strong><?php echo ucfirst( $tmp_lang[$lang] ); ?></strong></font>
								</td>
							</tr>
							<tr>
								<td>ISO</td>
								<td>
									<font color="green"><strong><?php echo $charset; ?></strong></font>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</form>
<div class="clr"></div>
<div class="install-text">
<?php echo T_('If any of these items are highlighted in red then please take actions to correct them. Failure to do so could lead to your Mambo installation not functioning correctly.') ?>
<div class="ctr"></div>
</div>

<div class="install-form">
<div class="form-block">

<table class="content">
<tr>
	<td class="item">
	<?php echo T_('PHP version') ?> >= 4.3.0
	</td>
	<td align="left">
	<?php echo phpversion() < '4.3' ? '<b><span class="red">'.T_('No').'</span></b>' : '<b><span class="green">'.T_('Yes').'</span></b>';?>
	</td>
</tr>
<tr>
	<td>
	&nbsp; - <?php echo T_('zlib compression support') ?>
	</td>
	<td align="left">
	<?php echo extension_loaded('zlib') ? '<b><span class="green">'.T_('Available').'</span></b>' : '<b><span class="red">'.T_('Unavailable').'</span></b>';?>
	</td>
</tr>
<tr>
	<td>
	&nbsp; - <?php echo T_('XML support') ?>
	</td>
	<td align="left">
	<?php echo extension_loaded('xml') ? '<b><span class="green">'.T_('Available').'</span></b>' : '<b><span class="red">'.T_('Unavailable').'</span></b>';?>
	</td>
</tr>
<tr>
	<td>
	&nbsp; - <?php echo T_('MySQL support') ?>
	</td>
	<td align="left">
	<?php echo function_exists( 'mysql_connect' ) ? '<b><span class="green">'.T_('Available').'</span></b>' : '<b><span class="red">'.T_('Unavailable').'</span></b>';?>
	</td>
</tr>
<tr>
	<td>
	&nbsp; - <span class="red"><?php echo T_('Note:') ?></span>
	</td>
	<td align="left">
	<?php echo T_('MySQL Strict Mode is not support') ?>
	</td>
</tr>
<tr>
	<td valign="top" class="item">
	configuration.php
	</td>
	<td align="left">
	<?php
	if (@file_exists('../configuration.php') &&  @is_writable( '../configuration.php' )){
		echo '<b><span class="green">'.T_('Writeable').'</span></b>';
	} else if (is_writable( '..' )) {
		echo '<b><span class="green">'.T_('Writeable').'</span></b>';
	} else {
		echo '<b><span class="red">'.T_('Unwriteable').'</span></b><br /><span class="small">'.T_('You can still continue the install as the configuration will be displayed at the end, just copy & paste this and upload.').'</span>';
	} ?>
	</td>
</tr>
<tr>
	<td class="item">
	<?php echo T_('Session save path') ?>
	</td>
	<td align="left">
	<b><?php echo (($sp=ini_get('session.save_path'))?$sp:'Not set'); ?></b>,
	<?php echo is_writable( $sp ) ? '<b><span class="green">'.T_('Writeable').'</span></b>' : '<b><span class="red">'.T_('Unwriteable').'</span></b>';?>
	</td>
</tr>
</table>
</div>
</div>
<div class="clr"></div>

<h1><?php echo T_('Recommended settings:') ?></h1>
<div class="install-text">
<?php echo T_('These settings are recommended for PHP in order to ensure full compatibility with Mambo. However, Mambo will still operate if your settings do not quite match the recommended.') ?>

<br />


<div class="ctr"></div>
</div>

<div class="install-form">
<div class="form-block">

<table class="content">
<tr>
	<td class="toggle">
	<?php echo T_('Directive') ?>
	</td>
	<td class="toggle">
	<?php echo T_('Recommended') ?>
	</td>
	<td class="toggle">
	<?php echo T_('Actual') ?>
	</td>
</tr>
<?php
$php_recommended_settings = array(array ('Safe Mode','safe_mode',T_('OFF')),
array ('Display Errors','display_errors',T_('ON')),
array ('File Uploads','file_uploads',T_('ON')),
array ('Magic Quotes GPC','magic_quotes_gpc',T_('ON')),
array ('Magic Quotes Runtime','magic_quotes_runtime',T_('OFF')),
array ('Register Globals','register_globals',T_('OFF')),
array ('Output Buffering','output_buffering',T_('OFF')),
array ('Session auto start','session.auto_start',T_('OFF')),
);

foreach ($php_recommended_settings as $phprec) {
?>
<tr>
	<td class="item"><?php echo $phprec[0]; ?>:</td>
	<td class="toggle"><?php echo $phprec[2]; ?>:</td>
	<td>
	<?php
	if ( get_php_setting($phprec[1]) == $phprec[2] ) {
	?>
		<span class="green"><b>
	<?php
	} else {
	?>
		<span class="red"><b>
	<?php
	}
	echo get_php_setting($phprec[1]);
	?>
	</b></span>
	<td>
</tr>
<?php
}
?>
</table>
</div>
</div>
<div class="clr"></div>
<h1><?php echo T_('Directory and File Permissions:') ?></h1>
<div class="install-text">
<?php echo T_('In order for Mambo to function correctly it needs to be able to access or write to certain files or directories. If you see "Unwriteable" you need to change the permissions on the file or directory to allow Mambo to write to it.') ?>
<div class="clr">&nbsp;&nbsp;</div>
<div class="ctr"></div>
</div>

<div class="install-form">
<div class="form-block">

<table class="content">
<?php
writableCell( 'administrator/backups' );
writableCell( 'administrator/components' );
writableCell( 'administrator/modules' );
writableCell( 'administrator/templates' );
writableCell( 'cache' );
writableCell( 'components' );
writableCell( 'images' );
writableCell( 'images/banners' );
writableCell( 'images/stories' );
?>
<tr>
	<td valign="top" class="item">
	installation/langconfig.php
	</td>
	<td align="left">
	<?php
	if (@file_exists('../installation/langconfig.php') &&  @is_writable( '../installation/langconfig.php' )){
		echo '<b><span class="green">'.T_('Writeable').'</span></b>';
	} else if (is_writable( '..' )) {
		echo '<b><span class="green">'.T_('Writeable').'</span></b>';
	} else {
		echo '<b><span class="red">'.T_('Unwriteable').'</span></b>';
	} ?>
	</td>
</tr>
<?php
writableCell( 'language' );
writableCell( 'mambots' );
writableCell( 'mambots/content' );
writableCell( 'mambots/editors' );
writableCell( 'mambots/editors-xtd' );
writableCell( 'mambots/search' );
writableCell( 'media' );
writableCell( 'modules' );
writableCell( 'templates' );
writableCell( 'uploadfiles' );
?>
</table>
</div>
<div class="clr"></div>
</div>
<div class="clr"></div>
</div>
<div class="clr"></div>
</div>
</div>
<div class="ctr">
<?php echo T_('<a href="http://www.mambo-foundation.org" target="_blank">Mambo </a> is Free Software released under the <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">GNU/GPL License</a>.') ?>
</div>
</body>
</html>
