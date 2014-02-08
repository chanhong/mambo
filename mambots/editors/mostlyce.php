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

/**
* @version $Id: mostlyce.php
* @package Mambo_4.6
* @Based on tinymce.php
* @copyright (C) 2000 - 2007 The Mambo Foundation
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* Mambo is Free Software
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

global $mosConfig_live_site, $jspath, $mosConfig_absolute_path, $adminside;
include($mosConfig_absolute_path."/mambots/editors/mostlyce/jscripts/tiny_mce/mostlyce_config.php");

/* The editor needs a session.  On the backend a session_start() within the mambot is not needed as on exists.
However, on the frontend it is. */
if (!isset($adminside) || empty($adminside) || $adminside===0) {
	session_start();
}

$_MAMBOTS->registerFunction( 'onInitEditor', 'botmostlyceEditorInit' );
$_MAMBOTS->registerFunction( 'onGetEditorContents', 'botmostlyceEditorGetContents' );
$_MAMBOTS->registerFunction( 'onEditorArea', 'botmostlyceEditorEditorArea' );

// --- Start custom code DHS Informatisering - opensource@dhs.nl, www.dhs.nl  
// This code makes it possible to use mostlyce in a website that combines normal and SSL-connections
if (isset($_SERVER['HTTPS']) &&  $_SERVER['HTTPS'] == "on") { 
	$jspath = "https:" . stristr ( $mosConfig_live_site, "//" );
} else {
	$jspath = "http:" . stristr ( $mosConfig_live_site, "//" );
}
// Replaced every occurrence of '$mosConfig_live_site' with '$jspath' in this file
// --- End custom code DHS Informatisering ---

//Render Row Buttons
function renderButton( $row, $remove_font )
{
    $row = explode( ',', $row );
    foreach( $row as $key => $rowitem ) {
        if( strpos( $rowitem, '-' )  === 0) {
            unset( $row[$key] );
        }
        if( $remove_font ){
            if( $rowitem == 'fontselect' || $rowitem == 'fontsizeselect' || $rowitem == 'forecolor' || $rowitem == 'backcolor' ) {
                unset( $row[$key] );
            }
        }
    }
    return $new_row = implode( ',', array_values( $row ) );
}

/**
* TinyMCE WYSIWYG Editor - javascript initialization
*/
function botmostlyceEditorInit() {

   global $mosConfig_live_site, $my, $database, $mosConfig_absolute_path, $jspath, $adminside, $mosConfig_secret;
   include($mosConfig_absolute_path."/mambots/editors/mostlyce/jscripts/tiny_mce/mostlyce_config.php");

        //Allows for dynamic editor sizing by apps like Remository
	    $get_width = mosGetParam($_GET,'width',NULL);
	    $get_height = mosGetParam($_GET,'height',NULL);
	    if ($get_width) {
	    	$editor_width = (int) $get_width; 
	    }
	    if ($get_height) {
	    	$editor_height = (int) $get_height; 
	    } 
        
        // Get the default stylesheet
        $query = "SELECT template FROM #__templates_menu WHERE client_id='0' AND menuid='0'";
        $database->setQuery( $query );
        $cur_template = $database->loadResult();
        // Assigned template
        if (isset( $Itemid ) && $Itemid != "" && $Itemid != 0) {
            $query = "SELECT template FROM #__templates_menu WHERE client_id='0' AND menuid='$Itemid' LIMIT 1";
            $database->setQuery($query);
            $cur_template = $database->loadResult() ? $database->loadResult() : $cur_template;
        }
        
        //Plugin settings and Authorization
        $invalid_elements[] = 'object,applet';

	    //Check access level and MOStlyCE Admin preference.  Set editor items accordingly.  
        //Script Elements (Only loads for Admin)
        if (($adminside>0) && ($editor_script_acl=='true')) {
        	$elements[] = "script[$editor_script_elms]";
        } else {
            $invalid_elements[] = "script";
        }
        //IFrame Elements (Only loads for Admin)
        if (($adminside>0) && ($editor_iframe_acl=='true')) {
            $elements[] = "iframe[$editor_iframe_elms]";
        }
        //Media Plugin
        if ($editor_plugin_media_acl=='true') {
        	$plugins[] = 'media';
        } 
        //Font Options
        if ($editor_font_tools_acl=='true') {
            $elements[] = "font[face|size|color|style]";
            $remove_font = false;
       } else {
            $remove_font = true;
       }
        //Emotions Plugin
        if ($editor_plugin_emotions_acl=='true') {
            $plugins[] = 'emotions';
        } 
        //Print Plugin
        if ($editor_plugin_print=='true') {
            $plugins[] = 'print';
        } 
        //Search & Replace Plugin
        if ($editor_plugin_searchreplace=='true') {
            $plugins[] = 'searchreplace';
        } 
        //Table Plugin
        if ($editor_plugin_table_acl=='true') {
            $plugins[] = 'table';
        } 
        //DateTime Plugin
        if ($editor_plugin_datetime_acl=='true') {
            $plugins[] = 'insertdatetime';
        } 
        //SpellChecker Plugin
        if ($editor_plugin_spellchecker=='true') {
            $plugins[] = 'spellchecker';
        } 
        //Layer Plugin
        if ($editor_plugin_layer=='true') {
            $plugins[] = 'layer';
        }   
        //Image/File Manager Plugin
        if ($editor_plugin_img_mgr=='true') {
        	$file_browser_callback='fileBrowserCallBack';
			//Setup a session variable for Image Manager security checks
			$mostlyceSessionRestore=session_id();
			$mostlyceRestoreKey=md5($mosConfig_secret.$_SERVER['REMOTE_ADDR']);
			$_SESSION['mostlyce_restore_key']=$mostlyceRestoreKey;
			$_SESSION['mostlyce_usertype']=$my->usertype;
	    } else {
	    	$file_browser_callback='';
		}
          
        //Paragraphs or breaks
        if ($editor_newlines == 'p'){
            $p_newlines = "true";
            $br_newlines = "false";
        }
        if ($editor_newlines == 'br'){
            $p_newlines = "false";
            $br_newlines = "true";
        }

        $css_template = $mosConfig_live_site."/templates/".$cur_template."/css/";
        $content_css = ($editor_css_override == '1' ) ? $css_template.$editor_custom_css : $css_template."template_css.css";

        $editor_layout_row1 = renderButton( str_replace( "\r\n", "", $editor_layout_row1 ), $remove_font );
        $editor_layout_row2 = renderButton( str_replace( "\r\n", "", $editor_layout_row2 ), $remove_font );
        $editor_layout_row3 = renderButton( str_replace( "\r\n", "", $editor_layout_row3 ), $remove_font );
        $editor_layout_row4 = renderButton( str_replace( "\r\n", "", $editor_layout_row4 ), $remove_font );
        
        //Plugins List
        $plugins[] = $editor_extra_plugins;
        $plugins = implode( ',', $plugins );
        $elements[] = $editor_xtd_elms;
        $elements = implode( ',', $elements );
        $invalid_elements = implode( ',', $invalid_elements );

//Check TinyMCE compression setting and set correct file path
if ($editor_compression==true) { 
	$tinymce_file = "mambots/editors/mostlyce/jscripts/tiny_mce/tiny_mce_gzip.js"; 
} else { 
	$tinymce_file = "mambots/editors/mostlyce/jscripts/tiny_mce/tiny_mce.js"; 
}
        
        return <<<EOD
<!--//TinyMCE/MOStlyCE-->	
<script type="text/javascript" src="$jspath/$tinymce_file"></script>
<script type="text/javascript" src="$jspath/mambots/editors/mostlyce/jscripts/tiny_mce/mostlyce_functions.js"></script>
<script type="text/javascript">
if ($editor_compression==true) {
tinyMCE_GZ.init({
	plugins : "$plugins,preview,advlink,advimage,paste,fullscreen,directionality",
	themes : '$editor_themes',
	languages : '$editor_lang',
	disk_cache : true,
	debug : false
});
}
</script>
<!-- Standard init and GZ init need to be in seperate script tags! -->
<script type="text/javascript">
	tinyMCE.init({
	    mode : "specific_textareas",
		theme : "$editor_themes",
		language : "$editor_lang",
        lang_list : "$editor_lang_list",
        table_color_fields : true,
        paste_use_dialog : true,
        advimage_constrain_proportions : true,
        invalid_elements: "$invalid_elements",
        force_br_newlines : "$br_newlines",
        force_p_newlines : "$p_newlines",
        directionality : "$editor_direction",
        file_browser_callback : "$file_browser_callback",
		plugins : "$plugins,preview,advlink,advimage,paste,fullscreen,directionality",
        theme_advanced_layout_manager : "RowLayout",
        theme_advanced_containers : "top1,top2,top3,top4,mceEditor,mceElementpath",
        theme_advanced_containers_default_class : "mceToolbar",
        theme_advanced_containers_default_align : "center",
        theme_advanced_container_top1_align : "left",
        theme_advanced_container_top2_align : "left",
        theme_advanced_container_top3_align : "left",
        theme_advanced_container_top4_align : "left",
        theme_advanced_container_top1 : "$editor_layout_row1",
        theme_advanced_container_top2 : "$editor_layout_row2",
        theme_advanced_container_top3 : "$editor_layout_row3",
        theme_advanced_container_top4 : "$editor_layout_row4",
        theme_advanced_container_top1_class : "mceToolbarTop",
        theme_advanced_container_top2_class : "mceToolbarTop",
        theme_advanced_container_top3_class : "mceToolbarTop",
        theme_advanced_container_top4_class : "mceToolbarTop",
        theme_advanced_container_mceElementpath_class : "mcePathBottom",
		width : "$editor_width",
        height : "$editor_height",
        mambo_base_url: "$mosConfig_live_site/",
        document_base_url: "$mosConfig_live_site/",
        content_css : "$content_css",
        plugin_insertdate_dateFormat : "$editor_plugin_dateformat",
    	plugin_insertdate_timeFormat : "$editor_plugin_timeformat",
		extended_valid_elements : "hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style],$elements",
		theme_advanced_resize_horizontal : false,
		theme_advanced_resizing : true,
		apply_source_formatting : false,
		spellchecker_languages : "+English=en,Danish=da,Dutch=nl,Finnish=fi,French=fr,German=de,Italian=it,Polish=pl,Portuguese=pt,Spanish=es,Swedish=sv"
		});
		
		function fileBrowserCallBack(field_name, url, type, win) {
		var connector = "../../filemanager/browser.php?Connector=connectors/php/connector.php&restore=$mostlyceSessionRestore";
		var enableAutoTypeSelection = false;
		
		var cType;
		tinymcpuk_field = field_name;
		tinymcpuk = win;
		
		switch (type) {
			case "image":
				cType = "Image";
				break;
			case "flash":
				cType = "Flash";
				break;
			case "file":
				cType = "File";
				break;
			case "media":
				cType = "Media";
				break;
		}
		
		if (enableAutoTypeSelection && cType) {
			connector += "&Type=" + cType;
		}
		
		window.open(connector, "tinymcpuk", "modal,width=600,height=400");
	}
</script>
<!-- /tinyMCE -->
EOD;
}
/**
* TinyMCE WYSIWYG Editor - copy editor contents to form field
* @param string The name of the editor area
* @param string The name of the form field
*/
function botmostlyceEditorGetContents( $editorArea, $hiddenField ) {
        global $jspath;

        return <<<EOD
        tinyMCE.triggerSave();
EOD;
}
/**
* mostlyce WYSIWYG Editor - display the editor
* @param string The name of the editor area
* @param string The content of the field
* @param string The name of the form field
* @param string The width of the editor area
* @param string The height of the editor area
* @param int The number of columns for the editor area
* @param int The number of rows for the editor area
*/
function botmostlyceEditorEditorArea( $name, $content, $hiddenField, $width, $height, $col, $row, $showbut=1 ) {
        global $jspath, $_MAMBOTS, $mosConfig_absolute_path;
        include($mosConfig_absolute_path."/mambots/editors/mostlyce/jscripts/tiny_mce/mostlyce_config.php");

        $results = $_MAMBOTS->trigger( 'onCustomEditorButton' );
        $buttons = array();
        foreach ($results as $result) {
               $buttons[] = '<img src="'.$jspath.'/mambots/editors-xtd/'.$result[0].'" onclick="tinyMCE.execCommand(\'mceInsertContent\',false,\''.$result[1].'\')" />';
        }
        //Hide old {mosimage} button if requested
        if ($editor_mosimage_icon == 'false') {
          unset($buttons[array_search(0,$buttons)]);
        }
        $buttons = implode( "", $buttons );

        return <<<EOD
<textarea id="$hiddenField" name="$hiddenField" cols="$col" rows="$row" style="width:{$width}px; height:{$height}px;" mce_editable="true">$content</textarea>
<br />$buttons
EOD;
}
?>