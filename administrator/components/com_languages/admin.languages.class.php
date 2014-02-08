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
class Request
{
    var $name;
    var $vars;

    function Request($name)
    {
        $this->name = $name;
        $this->attributes = array();
    }
    function set($var, $value)
    {
        $this->vars[$var] = $value;
    }
    function get($var=null)
    {
        return is_null($var) ? $this->vars : $this->vars[$var];
    }
    function addFromRequest($var, $global='request'){
        switch (strtolower($global))
        {
            case 'get':
            $this->vars[$var] = mosGetParam($_GET, $var);
            break;
            case 'post':
            $this->vars[$var] = mosGetParam($_POST, $var);
            break;
            case 'cookie':
            $this->vars[$var] = mosGetParam($_COOKIE, $var);
            break;
            case 'request':
            $this->vars[$var] = mosGetParam($_REQUEST, $var);
            break;
            default:
            trigger_error('Invalid Request Array', E_USER_ERROR);
            break;
        }
    }
    function setByRef($var, &$value)
    {
        $this->vars[$var] = &$value;
    }
    function &getByRef($var)
    {
        return $this->vars[$var];
    }
    function &session($reset = false)
    {
        $name = '__' . $this->name . '_session';
        if (!isset($_SESSION[$name]) || $reset) {
            $_SESSION[$name] = array();
        }
        return $_SESSION[$name];
    }
    function &getInstance($name)
    {
        static $requests;
        if (!isset($requests[$name])) {
            $requests[$name] = new Request($name);
        }
        return $requests[$name];
    }
}

class Controller
{
    var $name;
    var $dir;
    var $request;
    var $action;
    var $renderer;


    function Controller($name)
    {
        $this->name = $name;
        $this->request     =& Request::getInstance($name);
        $this->renderer    =& Renderer::getInstance();
        $this->renderer->setdir(dirname(__FILE__).DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'templates');
    }

    function forward($actionname)
    {
        if (!$actionname) return $this->view('index');

        $actionfile = dirname(__FILE__)."/actions/$actionname.action.php";
        $actionclass = $actionname.'Action';
        if (file_exists($actionfile)) include($actionfile);
        else return trigger_error("Action file '$actionfile' not found.", E_USER_ERROR);
        if (class_exists($actionclass))  $action = new $actionclass();
        else return trigger_error("Action class '$actionclass' not found.", E_USER_ERROR);
        $action->execute($this, $this->request);
    }


    function view($viewname)
    {
        $viewfile = dirname(__FILE__)."/views/$viewname.view.php";
        $viewclass = $viewname.'View';
        if (file_exists($viewfile)) include($viewfile);
        #else return trigger_error("View file '$viewfile' not found.", E_USER_ERROR);
        if (class_exists($viewclass))  $view = new $viewclass($this);
        else return trigger_error("View class '$viewclass' not found.", E_USER_ERROR);
        $view->render($this->renderer, $this->request);
    }
    function redirect($task=null, $act=null)
    {
        $url  = $_SERVER['PHP_SELF'].'?option='.$this->name;
        $url .= !is_null($task) ? '&task='.$task : '';
        $url .= !is_null($act) ? '&act='.$act : '';
        if (headers_sent()) {
            echo "<script>document.location.href='$url';</script>";
        } else {
            #if (ob_get_contents()) while (@ob_end_clean()); // clear output buffer if one exists
            header( "Location: $url" );
        }
        exit;
    }
}

class Action
{
    var $view;

    function Action()
    {
    }
    function execute(&$controller, &$request)
    {
        return trigger_error('Action::execute() must be overridden.', E_USER_ERROR);
    }
    function setView($view)
    {
        $this->viewname = $view;
    }
    function getView()
    {
        return $this->view;
    }
}

class View
{
    var $controller;

    function View(&$controller){
        $this->controller = $controller;
    }
    function render(&$request, &$renderer)
    {
        return trigger_error('View::render() must be overridden');
    }
}

class Renderer
{

    var $dir;
    var $vars = array();
    var $engine = 'php';
    var $template = '';
    var $debug = 0;

    function Renderer(){}

    function &getInstance($type = 'php') {
        static $renderer;
        if (is_null($renderer[$type])) {
            if ($type == 'php') {
                $renderer[$type] = new Renderer();
            } else {
                $classname = $type . 'Renderer';
                if (class_exists($classname))
                $renderer[$type] = new $classname();
            }
        }
        return $renderer[$type];
    }

    function display($template, $return = false){
        if ($template == NULL){
            return trigger_error('A template has not been specified', E_USER_ERROR);
        }
        $this->template = $this->dir . $template;
        if ($this->debug) echo nl2br($this->template."\n");

        if (is_readable($this->template)) {
            extract($this->getvars());
            if ($return) {
                ob_start();
                include_once($this->template);
                $ret = ob_get_contents();
                ob_end_clean();
                return $ret;
            } else {
                include_once($this->template);
            }
        } else {
            return trigger_error("Template file $template does not exist or is not readable", E_USER_ERROR);
        }
        return false;
    }

    function fetch($template){
        return $this->display($template, true);
    }

    function &getengine(){
        return $this->engine;
    }

    function addvar($key, $value){
        $this->vars[$key] = $value;
    }

    function addbyref ($key, &$value) {
        $this->vars[$key] = $value;
    }

    function getvars($name = false){
        return (isset($this->vars[$name])) ? $this->vars[$name] : $this->vars;
    }

    function setdir($dir){
        $this->dir = (substr($dir, -1) == DIRECTORY_SEPARATOR) ? $dir : $dir.DIRECTORY_SEPARATOR;
    }

    function getdir(){
        return $this->dir;
    }

    function settemplate($template){
        $this->template = $template;
    }
}

class XMLUtils
{
    function parse_into_array($xml) {
        $p = xml_parser_create();
        xml_parser_set_option($p, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($p, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($p, $xml, $values);
        xml_parser_free($p);
        $current = $prev = array();
        $xmlarray =& $current;
        foreach($values as $key => $value) {
            $index = count($xmlarray);
            switch ($value['type'])
            {
                case 'open':
                $xmlarray[$index] = array();
                $xmlarray[$index]['tag']        = isset($value["tag"]) ? $value["tag"] : null;
                $xmlarray[$index]['value']      = isset($value["value"]) ? $value["value"] : null;
                $xmlarray[$index]['attributes'] = isset($value["attributes"]) ? $value["attributes"] : null;
                $xmlarray[$index]['nodes']   = array();
                $prev[count($prev)] = &$xmlarray;
                $xmlarray = &$xmlarray[$index]['nodes'];
                break;
                case 'complete':
                $xmlarray[$index] = array();
                $xmlarray[$index]['tag']        = isset($value["tag"]) ? $value["tag"] : null;
                $xmlarray[$index]['value']      = isset($value["value"]) ? $value["value"] : null;
                $xmlarray[$index]['attributes'] = isset($value["attributes"]) ? $value["attributes"] : null;
                break;
                case 'close':
                $xmlarray = &$prev[count($prev) - 1];
                unset($prev[count($prev) - 1]);
                break;
            }
        }
        return $xmlarray;
    }
    function parse_file_into_array($file) {
        return XMLUtils::parse_into_array(file_get_contents($file));
    }

    function array_to_xml($array, $encoding='utf-8') {
        $xml = "<?xml version=\"1.0\" encoding=\"$encoding\"?>\n";
        if ((!empty($array)) AND (is_array($array))) {
            foreach ($array as $key => $value) {
                switch ($value["type"]) {
                    case "open":
                    $xml .= str_repeat("\t", $value["level"] - 1);
                    $xml .= "<" . strtolower($value["tag"]);
                    if (isset($value["attributes"])) {
                        foreach ($value["attributes"] as $k => $v) {
                            $xml .= sprintf(' %s="%s"', strtolower($k), $v);
                        }
                    }
                    $xml .= ">\n";
                    break;
                    case "complete":
                    $xml .= str_repeat("\t", $value["level"] - 1);
                    $xml .= "<" . strtolower($value["tag"]);
                    if (isset($value["attributes"])) {
                        foreach ($value["attributes"] as $k => $v) {
                            $xml .= sprintf(' %s="%s"', strtolower($k), $v);
                        }
                    }
                    $xml .= ">";
                    $xml .= isset($value['value']) ? $value['value'] : false;
                    $xml .= "</".strtolower($value["tag"]).">\n";
                    break;
                    case "close":
                    $xml .= str_repeat("\t", $value["level"] - 1);
                    $xml .= "</" . strtolower($value["tag"]) . ">\n";
                    break;
                    default:
                    break;
                }
            }
        }
        return $xml;
    }
}


?>