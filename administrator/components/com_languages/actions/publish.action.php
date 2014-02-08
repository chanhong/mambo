<?php
/**
* @package Mambo
* @subpackage Languages
* @author Mambo Foundation Inc see README.php
* @copyright Mambo Foundation Inc.
* See COPYRIGHT.php for copyright notices and details.
* @license GNU/GPL Version 2, see LICENSE.php
* Mambo is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; version 2 of the License.
*/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
class publishAction extends Action
{
    function execute(&$controller, &$request)
    {
        $lang = mosGetParam( $_REQUEST, 'lang', array(0) ); 
        $root = mamboCore::get('rootPath');            
        $fp = fopen("../configuration.php","r");
        $config = "";
        
        $langfile = $root.DIRECTORY_SEPARATOR.'language'.DIRECTORY_SEPARATOR.$lang[0].'.xml';        
        $p = xml_parser_create();
        xml_parser_set_option($p, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($p, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($p, implode("", file($langfile)), $values);
        xml_parser_free($p);
        foreach($values as $key => $value)
        {
            if ($value['tag'] == 'param') {
                $name = $value['attributes']['name'];
                $language[$name] = $value['attributes']['default'];
            }
        }        
        while(!feof($fp)){
            $buffer = fgets($fp,4096);
            if (strstr($buffer,"\$mosConfig_lang =")){
                $config .= "\$mosConfig_lang = \"{$lang[0]}\";\n";
            } elseif (strstr($buffer,"\$mosConfig_locale =")){
                $config .= "\$mosConfig_locale = \"{$language['locale']}\";\n";
            } else {
                $config .= $buffer;
            }
        }
        fclose($fp);
        if ($fp = fopen("../configuration.php","w")){
            fputs($fp, $config, strlen($config));
            fclose($fp);
            $request->setAttribute('msg', T_('Configuration succesfully updated!'));
        } else {
            $request->setAttribute('msg', T_('Error! Make sure that configuration.php is writeable.'));
        }                   
        return $controller->redirect();
    }
}
?>