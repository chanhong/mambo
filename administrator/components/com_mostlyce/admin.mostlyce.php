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

//Ensure user has access to this function
if (!($acl->acl_check('administration', 'edit', 'users', $my->usertype, 'components', 'all') || $acl->acl_check('administration', 'edit', 'users', $my->usertype, 'components', 'com_mostlyce'))) {
	mosRedirect( 'index2.php', T_('You are not authorized to view this resource.') );
}
//Verify the MOStlyCE mambot has been installed
if (!file_exists($mosConfig_absolute_path.'/mambots/editors/mostlyce/jscripts/tiny_mce/mostlyce_config.php')){
   mosRedirect( 'index2.php', T_('Cannot find mostlyce_config.php! Please install the MOStlyCE mambot.'));
}

include($mosConfig_absolute_path.'/mambots/editors/mostlyce/jscripts/tiny_mce/mostlyce_config.php');

class mosCE_Config extends mosDBTable {
        /** @var int */
	    var $editor_themes=null;
        var $editor_compression=null;
        var $editor_lang=null;
        var $editor_lang_list=null;
        var $editor_width=null;
        var $editor_height=null;
        var $editor_css_override=null;
        var $editor_custom_css=null;
        var $editor_newlines=null;
        var $editor_convert_urls=null;
        var $editor_script_acl=null;
        var $editor_script_elms;
        var $editor_iframe_acl=null;
        var $editor_iframe_elms;
        var $editor_xtd_elms=null;
        var $editor_direction=null;
        var $editor_preview_bgcolor=null;
        var $editor_extra_plugins=null;
        var $editor_font_tools_acl=null;
        var $editor_plugin_datetime_acl=null;
        var $editor_plugin_dateformat=null;
        var $editor_plugin_timeformat=null;
        var $editor_plugin_emotions_acl=null;
        var $editor_plugin_print=null;
        var $editor_plugin_searchreplace=null;
        var $editor_plugin_media_acl=null;
        var $editor_plugin_table_acl=null;
        var $editor_plugin_spellchecker=null;
        var $editor_plugin_layer=null;
        var $editor_mosimage_icon=null;
        var $editor_plugin_img_mgr=null;
        var $editor_layout_row1=null;
        var $editor_layout_row2=null;
        var $editor_layout_row3=null;
        var $editor_layout_row4=null;
        var $editor_tmpl_dir=null;

        function mosCE_Config() {
                $this->_alias = array(
		        'editor_themes'				                 =>'editor_themes',
                'editor_compression'                         =>'editor_compression',
                'editor_lang'                                =>'editor_lang',
                'editor_lang_list'                           =>'editor_lang_list',
                'editor_width'                               =>'editor_width',
                'editor_height'                              =>'editor_height',
                'editor_css_override'                        =>'editor_css_override',
                'editor_custom_css'                          =>'editor_custom_css',
                'editor_newlines'                            =>'editor_newlines',
                'editor_convert_urls'                        =>'editor_convert_urls',
                'editor_script_acl'                          =>'editor_script_acl',
                'editor_script_elms'                         =>'editor_script_elms',
                'editor_iframe_acl'                          =>'editor_iframe_acl',
                'editor_iframe_elms'                         =>'editor_iframe_elms',
                'editor_xtd_elms'                            =>'editor_xtd_elms',
                'editor_direction'                           =>'editor_direction',
                'editor_extra_plugins'                       =>'editor_extra_plugins',
                'editor_font_tools_acl'                      =>'editor_font_tools_acl',
                'editor_plugin_datetime_acl'                 =>'editor_plugin_datetime_acl',
                'editor_plugin_dateformat'                   =>'editor_plugin_dateformat',
                'editor_plugin_timeformat'                   =>'editor_plugin_timeformat',
                'editor_plugin_emotions_acl'                 =>'editor_plugin_emotions_acl',
                'editor_plugin_print'                        =>'editor_plugin_print',
                'editor_plugin_searchreplace'                =>'editor_plugin_searchreplace',
                'editor_plugin_media_acl'                    =>'editor_plugin_media_acl',
                'editor_plugin_table_acl'                    =>'editor_plugin_table_acl',
                'editor_plugin_spellchecker'                 =>'editor_plugin_spellchecker',
                'editor_plugin_layer'                        =>'editor_plugin_layer',
                'editor_mosimage_icon'                       =>'editor_mosimage_icon',
                'editor_plugin_img_mgr'					     =>'editor_plugin_img_mgr',    
                'editor_layout_row1'                         =>'editor_layout_row1',
                'editor_layout_row2'                         =>'editor_layout_row2',
                'editor_layout_row3'                         =>'editor_layout_row3',
                'editor_layout_row4'                         =>'editor_layout_row4',
                'editor_tmpl_dir'                            =>'editor_tmpl_dir'
                );
        }

        function getVarText() {
        	$txt = '';
            foreach ($this->_alias as $k=>$v) {
            	$txt .= "\$$v = '".addslashes( $this->$k )."';\n";
            }
            return $txt;
        }

        function bindGlobals() {
        	foreach ($this->_alias as $k=>$v) {
            	if(isset($GLOBALS[$v])) {
                	$this->$k = $GLOBALS[$v];
            	} else {
	                $this->$k = "";
            	}
        	}
        }
}

require_once( 'components/com_mostlyce/admin.mostlyce.html.php' );
$confightml = new HTML_expConfig();

switch ($task) {
        case 'save':
        	saveconfig();
            break;
        case 'config':
        	showconfig($confightml, $database, $option);
            break;
        default:
            showconfig($confightml, $database, $option);
            break;
}

function showconfig($confightml, &$database, $option) {
        global $database, $mosConfig_absolute_path;
        $row = new mosCE_Config();
        $row->bindGlobals();

        // compile list of the languages
        $editor_lang = array();

        if ($handle=opendir( "$mosConfig_absolute_path/mambots/editors/mostlyce/jscripts/tiny_mce/langs/" )) {
        	$i=0;
            while (false !== ($file = readdir($handle))) {
                if ($file <> "." && $file <> "..") {
                    $editor_lang[] = mosHTML::makeOption( substr($file,0,-3) );
                }
        	}
      	closedir($handle);
      }

        // sort list of languages
        sort($editor_lang);
        reset($editor_lang);

        $lists = array();

        $editor_newlines = array(
                mosHTML::makeOption( 'br', T_('BR Elements') ),
                mosHTML::makeOption( 'p', T_('P Elements') )
        );

	    $editor_themes = array(
                mosHTML::makeOption( 'advanced', T_('Advanced') ),
                mosHTML::makeOption( 'simple', T_('Simple') )
        );

	    $editor_compression = array(
                mosHTML::makeOption( 'true', T_('On') ),
                mosHTML::makeOption( 'false', T_('Off') )
        );
        
        $editor_direction = array(
                mosHTML::makeOption( 'ltr', T_('Left to Right') ),
                mosHTML::makeOption( 'rtl', T_('Right to Left') )
        );
        
        //Non-specific, used by several plugins
        $editor_true_false = array(
                mosHTML::makeOption( 'true', T_('Yes') ),
                mosHTML::makeOption( 'false', T_('No') )
        );

        $php_settings = new mosCE_Config();

        // build the html select lists
        //Standard Editor options
        $lists['editor_themes']                             = mosHTML::selectList( $editor_themes, 'editor_themes', 'class="inputbox" size="1"', 'value', 'text', $row->editor_themes );
	    $lists['editor_compression']                        = mosHTML::selectList( $editor_compression, 'editor_compression', 'class="inputbox" size="1"', 'value', 'text', $row->editor_compression );
        $lists['editor_lang']                               = mosHTML::selectList( $editor_lang, 'editor_lang', 'class="inputbox" size="1"', 'value', 'text', $row->editor_lang );
        $lists['editor_css_override']                       = mosHTML::yesnoSelectList( 'editor_css_override', 'class="inputbox" size="1"', $row->editor_css_override );
        $lists['editor_newlines']                           = mosHTML::selectList( $editor_newlines, 'editor_newlines', 'class="inputbox" size="1"', 'value', 'text', $row->editor_newlines );
        $lists['editor_convert_urls']                       = mosHTML::selectList( $editor_true_false, 'editor_convert_urls', 'class="inputbox" size="1"', 'value', 'text', $row->editor_convert_urls );
        $lists['editor_script_acl']                         = mosHTML::selectList( $editor_true_false, 'editor_script_acl', 'class="inputbox" size="1"', 'value', 'text', $row->editor_script_acl );
        $lists['editor_iframe_acl']                         = mosHTML::selectList( $editor_true_false, 'editor_iframe_acl', 'class="inputbox" size="1"', 'value', 'text', $row->editor_iframe_acl );
        $lists['editor_direction']                          = mosHTML::selectList( $editor_direction, 'editor_direction', 'class="inputbox" size="1"', 'value', 'text', $row->editor_direction );
        //Editor Plugin options
        $lists['editor_font_tools_acl']                     = mosHTML::selectList( $editor_true_false, 'editor_font_tools_acl', 'class="inputbox" size="1"', 'value', 'text', $row->editor_font_tools_acl );
        $lists['editor_plugin_emotions_acl']                = mosHTML::selectList( $editor_true_false, 'editor_plugin_emotions_acl', 'class="inputbox" size="1"', 'value', 'text', $row->editor_plugin_emotions_acl );
        $lists['editor_plugin_print']                       = mosHTML::selectList( $editor_true_false, 'editor_plugin_print', 'class="inputbox" size="1"', 'value', 'text',  $row->editor_plugin_print );
        $lists['editor_plugin_searchreplace']               = mosHTML::selectList( $editor_true_false, 'editor_plugin_searchreplace', 'class="inputbox" size="1"', 'value', 'text',  $row->editor_plugin_searchreplace );
        $lists['editor_plugin_media_acl']                   = mosHTML::selectList( $editor_true_false, 'editor_plugin_media_acl', 'class="inputbox" size="1"', 'value', 'text', $row->editor_plugin_media_acl );
        $lists['editor_plugin_datetime_acl']                = mosHTML::selectList( $editor_true_false, 'editor_plugin_datetime_acl', 'class="inputbox" size="1"', 'value', 'text', $row->editor_plugin_datetime_acl );
        $lists['editor_plugin_table_acl']                   = mosHTML::selectList( $editor_true_false, 'editor_plugin_table_acl', 'class="inputbox" size="1"', 'value', 'text', $row->editor_plugin_table_acl );
        $lists['editor_plugin_spellchecker']                = mosHTML::selectList( $editor_true_false, 'editor_plugin_spellchecker', 'class="inputbox" size="1"', 'value', 'text', $row->editor_plugin_spellchecker );
        $lists['editor_plugin_layer']                       = mosHTML::selectList( $editor_true_false, 'editor_plugin_layer', 'class="inputbox" size="1"', 'value', 'text', $row->editor_plugin_layer );
        $lists['editor_plugin_img_mgr']                     = mosHTML::selectList( $editor_true_false, 'editor_plugin_img_mgr', 'class="inputbox" size="1"', 'value', 'text', $row->editor_plugin_img_mgr );
        $lists['editor_mosimage_icon']                     = mosHTML::selectList( $editor_true_false, 'editor_mosimage_icon', 'class="inputbox" size="1"', 'value', 'text', $row->editor_mosimage_icon );

        $confightml->showconfig($row, $lists, $option);
}

function saveconfig() {
        global $database;

        $row = new mosCE_Config();
        if (!$row->bind( $_POST )) {
                mosRedirect( "index2.php?option=com_mostlyce", $row->getError() );
        }

        $config = "<?php \n";
        $config .= $row->getVarText();
        $config .= '?>';

        if ($fp = fopen('../mambots/editors/mostlyce/jscripts/tiny_mce/mostlyce_config.php', 'w')) {
        	fputs($fp, $config, strlen($config));
        	fclose ($fp);
	        mosRedirect( 'index2.php?option=com_mostlyce&task=config', 'The configuration details have been updated!' );
        } else {
        	mosRedirect( 'index2.php?option=com_mostlyce&task=config', 'An Error Has Occurred! Unable to open config file to write!' );
        }
}
?>