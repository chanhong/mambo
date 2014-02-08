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

global $mainframe;
$cur_template = $mainframe->getTemplate();

// titlelength can be set in module params
$titlelength = $params->get( 'title_length', 20 );
$preview_height = $params->get( 'preview_height', 90 );
$preview_width = $params->get( 'preview_width', 140 );
$show_preview = $params->get( 'show_preview', 0 );

// Read files from template directory
$template_path = "$mosConfig_absolute_path/templates";
$templatefolder = @dir( $template_path );
$darray = array();
if ($templatefolder) {
	while ($templatefile = $templatefolder->read()) {
		if ($templatefile != "." && $templatefile != ".." && $templatefile != "css" && is_dir( "$template_path/$templatefile" )  ) {
			if(strlen($templatefile) > $titlelength) {
				$templatename = substr( $templatefile, 0, $titlelength-3 );
				$templatename .= "...";
			} else {
				$templatename = $templatefile;
			}
			$darray[] = mosHTML::makeOption( $templatefile, $templatename );
		}
	}
	$templatefolder->close();
}

sort( $darray );

// Set up JavaScript for instant preview and show the preview image
$onchange = "";
if ($show_preview) {
	$onchange = "showimage();";
?>
<img src="<?php echo "templates/$cur_template/template_thumbnail.png";?>" id="templatePreview" width="<?php echo $preview_width;?>" height="<?php echo $preview_height;?>" alt="<?php echo $cur_template; ?>" />
<script type='text/javascript'>
	function showimage() {
		var tpimage=document.getElementById('templatePreview');
		tpimage.src = 'templates/' + getSelectedValue() + '/template_thumbnail.png';
	}
	function getSelectedValue() {
		var srcList = document.getElementById('mos_change_template');
		i = srcList.selectedIndex;
		if (i != null && i > -1) {
			return srcList.options[i].value;
		} else {
			return null;
		}
	}
</script>
<?php
}
?>
<?php
require_once(mamboCore::get('mosConfig_absolute_path').'/includes/phpInputFilter/class.inputfilter.php');
$iFilter = new InputFilter( null, null, 1, 1 );
$requestUri = trim($iFilter->process($_SERVER['REQUEST_URI']));
?>
<form action="<?php echo $requestUri;?>" id='templateform' method="post">
<?php
echo mosHTML::selectList( $darray, 'mos_change_template', "id=\"mos_change_template\"class=\"button\" onchange=\"$onchange\"",'value', 'text', $cur_template );
?>
<input class="button" type="submit" value="<?php echo T_('Select');?>" />
</form>