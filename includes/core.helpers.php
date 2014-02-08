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

class mosHtmlHelper {
	var $doctype = 'XHTML 1.0 Transitional';
	var $charset = 'utf-8';
	var $useXmlPrologue = false;
	var $_tags = array(
			'title' => '<title>%s</title>',
			'meta' => '<meta name="%s" content="%s" />',
			'metalink' => '<link href="%s" title="%s"%s />',
			'metalinkrel' => '<link rel="%s" href="%s" />',
			'charset' => '<meta http-equiv="Content-Type" content="text/html; charset=%s" />',
			'css' => '<link href="%s" rel="stylesheet" type="text/css"%s />',
			'javascript' => '<script type="text/javascript"%s>%s</script>',
			'xmlprologue' => '<?xml version="1.0" encoding="%s"?>'
		);
	var $_docTypes = array(
		'XHTML 1.0 Strict' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
		'XHTML 1.0 Transitional' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
		'XHTML 1.0 Frameset' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">',
		'XHTML 1.1' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">',
		'XHTML Mobile 1.0' => '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">',
		'XHTML Mobile 1.1' => '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.1//EN" "http://www.openmobilealliance.org/tech/DTD/xh tml-mobile11.dtd">',
		'XHTML Mobile 1.2' => '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN" "http://www.openmobilealliance.org/tech/DTD/xh tml-mobile11.dtd">',
		'XHTML Basic 1.0' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.0//EN" "http://www.w3.org/TR/xhtml-basic/xhtml-basic10.dtd">',
		'XHTML Basic 1.1' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.1//EN" "http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd">'
	);
	var $_docTypesMobile = array(
		'XHTML Mobile 1.0',
		'XHTML Mobile 1.1',
		'XHTML Mobile 1.2',
		'XHTML Basic 1.0',
		'XHTML Basic 1.1'
	);
	var $_headTags = array();

    /**
	* Singleton accessor
	*/
    function &getInstance () {
        static $instance;
        if ( !is_object($instance) ) {
			$instance = new mosHtmlHelper();
			$lang = mamboCore::get('current_language');
			$instance->charset = $lang->charset;
			if ( mamboCore::is_set('mosConfig_doctype') ) {
				$instance->doctype = mamboCore::get('mosConfig_doctype');
			}
			$instance->loadHeadTags();
		}
        return $instance;
    }
    /**
     * Get method
     *
     * @param unknown_type $var
     * @return unknown
     */
	function doctypeIsMobile($key='') {
		static $obj;
		if ( !is_object($obj) ) $obj =& mosHtmlHelper::getInstance();
		if ( trim($key) == '' ) $key = $obj->doctype;
		if ( in_array($key, $obj->_docTypesMobile) ) return true;

		return false;
    }
    /**
     * Get method
     *
     * @param unknown_type $var
     * @return unknown
     */
	function get($var) {
		$var = trim($var);
		if ( !$var ) return null;

		static $obj;
		if ( !is_object($obj) ) $obj =& mosHtmlHelper::getInstance();
		
		if( isset($obj->$var) ) return $obj->$var;
		return null;
	}
    /**
     * Set method - set public properties. Does not set a property that does not already exist
     *
     * @param unknown_type $property - the property to set
     * @param unknown_type $value - the value to set the property to
     */
	function set($property, $value) {
		$property = trim($property);
		if ( !$property ) return;
		if( $property{0} == '_' ) return; // dont set private properties

		static $obj;
		if ( !is_object($obj) ) $obj =& mosHtmlHelper::getInstance();

		if ( isset($obj->$property) ) $obj->$property = $value;
	}
    /**
     * Set method - set public properties. Does not set a property that does not already exist
     *
     * @param unknown_type $property - the property to set
     * @param unknown_type $value - the value to set the property to
     */
	function useXmlPrologue($flag=null) {
		static $obj;
		if ( !is_object($obj) ) $obj =& mosHtmlHelper::getInstance();

		if ( $flag === true && $flag === false ) $obj->useXmlProlugue = $flag;
		return $obj->useXmlPrologue;
	}
    /**
     * Generic tag construction
     *
     * @param string $key - should be a key in the _tags property list
	 * @param unknown $vars - a string or array of strings
     * @return formatted html
     */
	function tag($key, $vars=array()) {
		$key = trim($key);
		if ( !$key ) return null;
		if ( is_array($vars) )
			if ( !count($vars) ) return null;
		if ( !is_array($vars) && trim($vars) == '' ) return null;

		static $obj;
		if ( !is_object($obj) ) $obj =& mosHtmlHelper::getInstance();

		if ( !isset($obj->_tags[$key]) ) return null;
		if ( !is_array($vars) ) $vars = array($vars);
		
		$countVars = count($vars);
		$countTagVars = substr_count($obj->_tags[$key], '%s');
		// make sure we pass at least n vars
		for ($i=$countVars; $i<$countTagVars; $i++) {
			$vars[] = '';
		}
		// make sure we pass no more than n vars
		for ($i=$countVars; $i>$countTagVars-1; $i--) {
			unset($vars[$i]);
		}
		return vsprintf($obj->_tags[$key], $vars);
    }
    /**
     * Render output
     *   Default is to render output wrapped by $prepend/$postpend. Default value of postpend is \n.
     *   A case statement calls various public rendering functions
     *
     * @param string $string
     * @param string $postpend
     * @param string $prepend
     */
	function render($string, $prepend=null, $postpend="\n") {
		switch($string) {
			case 'head':
				mosHtmlHelper::showHead();
				break;
			case 'doctype':
				mosHtmlHelper::renderDoctype();
				break;
			case 'title':
				mosHtmlHelper::renderTitle();
				break;
			case 'charset':
				mosHtmlHelper::renderCharset();
				break;
			case 'css':
				mosHtmlHelper::renderCss();
				break;
			case 'javascript':
				mosHtmlHelper::renderJavascript();
				break;
			case 'meta':
				mosHtmlHelper::showMeta();
				break;
			case 'xmlprologue':
				mosHtmlHelper::renderXmlPrologue();
				break;
			default:
				echo $prepend.$string.$postpend;
				break;
		}
	}
    /**
     * Render DTD
     *
     * @param string $type - doctype
     */
	function renderDocType($type='') {
		static $obj;
		if ( !is_object($obj) ) $obj =& mosHtmlHelper::getInstance();
		
		$type = trim($type);
		if ( $type && array_key_exists($type, $obj->_docTypes) )
			$obj->render($obj->_docTypes[$type]);
		else
			$obj->render($obj->_docTypes[$obj->doctype]);
	}
    /**
     * Render xml prologue
     *
     * @param string $charset - the character set for the prologue
     * @param string $force - render prologue even if class property userXmlPrologue is false
     */
	function renderXmlPrologue($charset='', $force=false) {
		static $obj;
		if ( !is_object($obj) ) $obj =& mosHtmlHelper::getInstance();
		if ( $obj->doctypeIsMobile() ) $obj->set('useXmlPrologue', true);
		
		$charset = trim($charset);
		$charset = $charset !== '' ? $charset : $obj->charset;
		if ( $obj->useXmlPrologue !== false || ($force === true) )
			$obj->render($obj->tag('xmlprologue', $charset));
	}
    /**
     * Render title tag
     *
     * @param string $title - page title
     */
	function renderTitle($title='') {
		static $obj;
		if ( !is_object($obj) ) $obj =& mosHtmlHelper::getInstance();
		
		static $mainframe;
		if ( !is_object($mainframe) ) $mainframe =& mosMainFrame::getInstance();
		
		$title = trim($title);
		$title = $title !== '' ? $title : $mainframe->_head['title'];
		$obj->render($obj->tag('title', $title));
	}
    /**
     * Render meta tag
     */
	function renderMeta($name='', $content='') {
		$name = trim($name);
		$content = trim($content);
		if ( !$name || !$content ) return;

		static $obj;
		if ( !is_object($obj) ) $obj =& mosHtmlHelper::getInstance();

		$obj->render($obj->tag('meta', array($name, $content)));
	}
    /**
     * Render meta link href tag
     */
	function renderMetaLink($href='', $title='', $extra='') {
		$href = trim($href);
		$title = trim($title);
		if ( !$href || !$title ) return;
		
		static $obj;
		if ( !is_object($obj) ) $obj =& mosHtmlHelper::getInstance();

		$extra = (trim($extra) !== '') ? " $extra" : '';
		$obj->render($obj->tag('metalink', array($href, $title, $extra)));
	}
    /**
     * Render meta link rel tag
     */
	function renderMetaLinkRel($rel='', $href='') {
		$rel = trim($rel);
		$href = trim($href);
		if ( !$rel || !$href ) return;
		
		static $obj;
		if ( !is_object($obj) ) $obj =& mosHtmlHelper::getInstance();

		$obj->render($obj->tag('metalinkrel', array($rel, $href)));
	}
    /**
     * Render character set meta tag
     *
     * @param string $charset - character set
     */
	function renderCharset($charset='') {
		static $obj;
		if ( !is_object($obj) ) $obj =& mosHtmlHelper::getInstance();
		
		$charset = trim($charset);
		$charset = $charset !== '' ? $charset : $obj->get('charset');
		$obj->render($obj->tag('charset', $charset));
	}
    /**
     * Render css link tag
     *
     * @param string $path - css file path. Default is template_css.css in the current template folder
     */
	function renderCss($filepath='', $media='') {
		static $obj;
		if ( !is_object($obj) ) $obj =& mosHtmlHelper::getInstance();
		
		$filepath = trim($filepath);
		$media = trim($media);
		$media = $media !== '' ? " media=\"$media\"" : '';
		$mainframe =& mosMainframe::getInstance();

		if ( $filepath == '' && $media == '' ) {
			$file = mosPath(mamboCore::get('mosConfig_absolute_path').'/templates/'.$mainframe->getTemplate().'/css/template_css.css');
			if ( file_exists($file) ) {
				$filepath = mamboCore::get('mosConfig_live_site').'/templates/'.$mainframe->getTemplate().'/css/template_css.css';
				$obj->render($obj->tag('css', array($filepath)));
			}
			$file = mosPath(mamboCore::get('mosConfig_absolute_path').'/templates/'.$mainframe->getTemplate().'/css/print.css');
			if ( file_exists($file) ) {
				$filepath = mamboCore::get('mosConfig_live_site').'/templates/'.$mainframe->getTemplate().'/css/print.css';
				$obj->render($obj->tag('css', array($filepath, ' media="print"')));
			}
			return;
		}
		if ( $filepath == '' ) {
			$filepath = mamboCore::get('mosConfig_live_site').'/templates/'.$mainframe->getTemplate().'/css/template_css.css';
		}
		$obj->render($obj->tag('css', array($filepath, $media)));
	}
    /**
     * Render Javascript tags
     */
	function renderJavascript($link='', $code='') {
		static $obj;
		if ( !is_object($obj) ) $obj =& mosHtmlHelper::getInstance();
		
		if ( strlen(trim($code)) !== 0 ) {
			$obj->render($obj->tag('javascript', array('', "\n$code\n")));
		} else {
			$link = trim($link);
			if ( $link ) {
				$obj->render($obj->tag('javascript', " src=\"$link\""));
			} else {
		        $my = mamboCore::get('currentUser');
				$obj->_headTags['mambojavascript'] = $my->id ? $obj->tag('javascript', ' src="'. mamboCore::get('mosConfig_live_site')."/includes/js/mambojavascript.js\"") : '';
				if ( $obj->_headTags['mambojavascript'] !== '' )
					$obj->render($obj->_headTags['mambojavascript']);
			}
		}
	}
    /**
     * Load Mambo generated head tags into an array
     */
	function loadHeadTags() {
		static $obj;
		if ( !is_object($obj) ) $obj =& mosHtmlHelper::getInstance();

		$mainframe =& mosMainFrame::getInstance();
		$obj->_headTags = array();
        $mainframe->appendMetaTag( 'description', mamboCore::get('mosConfig_MetaDesc'), true );
        $mainframe->appendMetaTag( 'keywords', mamboCore::get('mosConfig_MetaKeys'), true );

		$title = $mainframe->_head['title'];
		$obj->_headTags['title'] = $obj->tag('title', $title);

		$obj->_headTags['meta'] = array();
        foreach ($mainframe->_head['meta'] as $name=>$meta) {
            if ( $meta[1] ) $obj->_headTags['meta'][] = $meta[1];
            $obj->_headTags['meta'][$name] = $obj->tag('meta', array($name, $meta[0]));
            if ( $meta[2] ) $obj->_headTags['meta'][] = $meta[2];
		}

		$my =& mamboCore::get('currentUser');
		$obj->_headTags['mambojavascript'] = $my->id ? $obj->tag('javascript', ' src="'.mamboCore::get('mosConfig_live_site')."/includes/js/mambojavascript.js\"") : '';
		$obj->_headTags['custom'] = array();
		foreach ($mainframe->_head['custom'] as $html)
			if ( trim($html) !== '' )
				$obj->_headTags['custom'][] = $html;

		ob_start();
   	    $mainframe->liveBookMark();
		$obj->_headTags['livebookmark'] = trim(ob_get_contents());
		ob_end_clean();

		$configuration =& mamboCore::getMamboCore();
		$obj->_headTags['favicon'] = $obj->tag('metalinkrel', array("shortcut icon", $configuration->getFavIcon()));
	}

    /**
     * Render Mambo generated head tags.
     *
     * @param string $keys - a key or array of keys in the head tag array to render
     * @param string $exclue - a key or array of keys in the head tag array to exclude
     */
    function showHead($keys='', $exclude='') {
		if ( !is_array($keys) )
			if ( $keys !== '' && !is_null($keys) )
				$keys = array($keys);
			else $keys = array();
		if ( !is_array($exclude) )
			if ( $exclude !== '' )
				$exclude = array($exclude);
			else $exclude = array();

		static $obj;
		if ( !is_object($obj) ) $obj =& mosHtmlHelper::getInstance();
		if ( count($obj->_headTags) == 0 ) $obj->loadHeadTags();
		
		if ( count($keys) == 0 ) {
			foreach($obj->_headTags as $key=>$value) {
				if ( !in_array($key, $exclude) ) {
					if ( is_array($value) ) {
						foreach ($value as $key2=>$value2)
							if (isset($value2))
								if ( $value2 !== '' ) $obj->render($value2);
					} else {
						if (isset($value))
							if ( $value !== '' ) $obj->render($value);
					}
				}
			}
		} else {
			foreach($keys as $key) {
				if ( isset($obj->_headTags[$key]) ) {
					if ( is_array($obj->_headTags[$key]) ) {
						foreach ($obj->_headTags[$key] as $key2=>$value2)
							if ( $value2 !== '' ) $obj->render($value2);
					} else {
						if ( $obj->_headTags[$key] !== '' ) $obj->render($obj->_headTags[$key]);
					}
				}
			}
		}
	}
    /**
     * Render Mambo generated meta tags.
     *
     * @param string $keys - a key or array of keys in the meta tag array to render
     * @param string $exclue - a key or array of keys in the meta tag array to exclude
     */
	function showMeta($keys='', $exclude='') {
		if ( !is_array($keys) )
			if ( $keys !== '' && !is_null($keys) )
				$keys = array($keys);
			else $keys = array();
		if ( !is_array($exclude) )
			if ( $exclude !== '' )
				$exclude = array($exclude);
			else $exclude = array();
		
		static $obj;
		if ( !is_object($obj) ) $obj =& mosHtmlHelper::getInstance();
		if ( count($obj->_headTags) == 0 ) $obj->loadHeadTags();
		if ( count($keys) == 0 ) {
			foreach($obj->_headTags['meta'] as $key=>$value) {
				if ( !in_array($key, $exclude) ) {
					if (is_array($value)) {
						foreach ($value as $key2=>$value2)
							if ( $value2 !== '' ) $obj->render($value2);
					} else {
						if ( $value2 !== '' ) $obj->render($value);
					}
				}
			}
		} else {
			foreach($keys as $key) {
				if ( isset($obj->_headTags['meta'][$key]) ) {
					if ( is_array($obj->_headTags['meta'][$key]) ) {
						foreach ($obj->_headTags['meta'][$key] as $key2=>$value2)
							if ( $value2 !== '' ) $obj->render($value2);
					} else {
						if ( $obj->_headTags['meta'][$key] !== '' ) $obj->render($obj->_headTags['meta'][$key]);
					}
				}
			}
		}
	}
} // end class mosHtmlHelper

/**
 * mosUriHelper class
 *
 * original copyright (c) 2003, binarycloud-dev
 * original license - LGPL (http://www.gnu.org/copyleft/lesser.html)
 * original author - jason hines, jason@greenhell.com
 *
 * Changelog:
 * 12-01-2007 Al Warren (alwarren)
 * 	- changed class name to mosUri
 *  - removed includes
 *  - removed references to authorizer class
 * 	- cleaned up comments
*/

/**
 * mosUriHelper is a single instance class used for altering and receiving the Uri
 * from different apps.  By default, it looks to current Uri, and provides
 * methods for retrieving the various parts of the given Uri.
 *
 * Usage:
 * $Uri =& mosUriHelper::getInstance();
 * $Uri->setUri('http://domain.com/path/to/script.php?param1=value1');
 * $Uri->pushParam('foo','bar');
 * $Uri->popParam('param1');
 * print $Uri->getUri();
 *
 * Outputs: http://domain.com/path/to/script.php?foo=bar
 *
 */
class mosUriHelper {
    /**
     * @var string Full uri
     */
    var $uri;
    /**
     * @var string Protocol
     */
    var $scheme;
    /**
     * @var string Username
     */
    var $user;
    /**
     * @var string Password
     */
    var $pass;
    /**
     * @var string Host
     */
    var $host;
    /**
     * @var integer Port
     */
    var $port;
    /**
     * @var string Path
     */
    var $path;
    /**
     * @var array Query hash
     */
    var $query;
    /**
     * @var string Anchor
     */
    var $anchor;
    /**
     * Constructor set Uri on class instantiation.
     *
     * @param     string
     * @access  public
     */
    function mosUriHelper() {
        $this->setUri();
    }
    /**
     * Singleton accessor
     *
     * @access public
     */
    function &getInstance() {
        static $instance;
        if(!isset($instance)) {
            $instance = new mosUriHelper();
        }
        return $instance;
    }
    /**
     * Looks to _SERVER vars, and sets Uri property accordingly if Uri not passed.
     *
     * @access  public
     */
    function setUri($uri = null) {
        if ($uri == null) {
            $this->scheme = 'http' . (@$_SERVER['HTTPS'] == 'on' ? 's' : '');
            $this->user   = '';
            $this->pass   = '';
            $this->host   = !empty($host) ? $host : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost');
            $this->port   = !empty($port) ? $port : (isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : 80);
            $this->path   = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : '/';
            $this->query  = isset($_SERVER['QUERY_STRING']) ? $this->_parseRawQuery($_SERVER['QUERY_STRING']) : null;
            $this->anchor = '';
        } else {
            $_parts = parse_url($uri);
            $this->scheme = isset($_parts['scheme']) ? $_parts['scheme'] : 'http';
            $this->user   = isset($_parts['user']) ? $_parts['user'] : '';
            $this->pass   = isset($_parts['pass']) ? $_parts['pass'] : '';
            $this->host   = isset($_parts['host']) ? $_parts['host'] : '';
            $this->port   = isset($_parts['port']) ? $_parts['port'] : 80;
            $this->path   = isset($_parts['path']) ? $_parts['path'] : '';
            $this->query  = isset($_parts['query']) ? $this->_parseRawQuery($_parts['query']) : array();
            $this->anchor = isset($_parts['fragment']) ? $_parts['fragment'] : '';
        }
    }
    /**
     * Returns full uri string
     *
     * @return string Full uri
     * @access public
     */
    function toString() {
        $query = $this->getQuery();
        $this->uri = $this->scheme . '://'
                     . $this->user . (!empty($this->pass) ? ':' : '')
                     . $this->pass . (!empty($this->user) ? '@' : '')
                     . $this->host . ($this->port == '80' ? '' : ':' . $this->port)
                     . $this->path
                     . (!empty($query) ? '?' . $query : '')
                     . (!empty($this->anchor) ? '#' . $this->anchor : '');
        return $this->uri;
    }
    /**
     * Alias for toString()
     *
     * @access  public
     */
    function getUri() {
        return $this->toString();
    }
    /**
     * Adds a query item
     *
     * @param  string $name       Name of item
     * @param  string $value      Value of item
     * @access public
     */
    function pushParam($name, $value) {
        $this->query[$name] = is_array($value)? array_map('urlencode', $value): urlencode($value);
    }
    /**
     * Get a query item
     *
     * @param  string $key Name of item
     * @return mixed
     * @access public
     */
	function get($key, $default='') {
		if (isset($this->query[$key])) return $this->query[$key];
			else return $default;
	}
    /**
     * Removes a query item
     *
     * @param  string $name Name of item
     * @access public
     */
    function popParam($name) {
        if (isset($this->query[$name])) {
            unset($this->query[$name]);
        }
    }
    /**
     * Sets the query to literally what you supply
     *
     * @param  string $query The query data. Should be of the format foo=bar&x=y etc
     * @access public
     */
    function setRawQuery($query) {
        $this->query = $this->_parseRawQuery($query);
    }
    /**
     * Returns flat query
     *
     * @return string Query
     * @access public
     */
    function getQuery() {
        if (!empty($this->query)) {
            $query = array();
            foreach ($this->query as $name => $value) {
                if (is_array($value)) {
                    foreach ($value as $k => $v) {
                        $query[] = $name . '=' . $v;
                    }
                }
                elseif (!is_null($value)) {
                    $query[] = $name . '=' . $value;
                }
                else {
                    $query[] = $name;
                }
            }
            $query = implode('&', $query);
        } else {
            $query = '';
        }
        return $query;
    }
    /**
     * Parses raw query and returns an array of it
     *
     * @param  string  $query   The querystring to parse
     * @return array            An array of the query data
     * @access private
     */
    function _parseRawQuery($query) {
        $query = rawurldecode($query);
		// replace ampersand entities
		$query = str_replace('&amp;', '&', $query);
        $parts = preg_split('/&/', $query, -1, PREG_SPLIT_NO_EMPTY);
        $return = array();
        foreach ($parts as $part) {
            if (strpos($part, '=') !== false) {
                $value = rawurlencode(substr($part, strpos($part, '=') + 1));
                $key   = substr($part, 0, strpos($part, '='));
            } else {
                $value = null;
                $key   = $part;
            }
            if (substr($key, -2) == '[]') {
                $key = substr($key, 0, -2);
                if (@!is_array($return[$key])) {
                    $return[$key]   = array();
                    $return[$key][] = $value;
                } else {
                    $return[$key][] = $value;
                }
            } elseif (!empty($return[$key])) {
                $return[$key]   = (array) $return[$key];
                $return[$key][] = $value;
            }
            else {
                $return[$key] = $value;
            }
        }
        return $return;
    }
    /**
     * Resolves //, ../ and ./ from a path and returns
     * the result. Eg:
     *
     * /foo/bar/../boo.php    => /foo/boo.php
     * /foo/bar/../../boo.php => /boo.php
     * /foo/bar/.././/boo.php => /foo/boo.php
     *
     * This method can also be called statically.
     *
     * @param  string $uri Uri path to resolve
     * @return string      The result
     */
    function resolvePath($path) {
        $path = explode('/', str_replace('//', '/', $path));
        for ($i=0; $i<count($path); $i++) {
            if ($path[$i] == '.') {
                unset($path[$i]);
                $path = array_values($path);
                $i--;
            }
            elseif ($path[$i] == '..' AND ($i > 1 OR ($i == 1 AND $path[0] != '') ) ) {
                unset($path[$i]);
                unset($path[$i-1]);
                $path = array_values($path);
                $i -= 2;
            }
            elseif ($path[$i] == '..' AND $i == 1 AND $path[0] == '') {
                unset($path[$i]);
                $path = array_values($path);
                $i--;
            }
            else {
                continue;
            }
        }
        return implode('/', $path);
    }
    /**
     * Get scheme - returns the scheme
     *
     * @access public
     * @return string
     */
    function getScheme() {
        return $this->scheme;
    }
    /**
     * Set scheme - sets the scheme (protocol)
     *
     * @param  string  scheme
     * @access public
     */
    function setScheme($scheme) {
        $this->scheme = $scheme;
    }
    /**
     * Get username - returns the username, or null if no username was specified
     *
     * @access public
     * @return string
     */
    function getUser() {
        return $this->user;
    }
    /**
     * Set username - sets the username
     *
     * @param  string  username
     * @access public
     */
    function setUser($user) {
        $this->user = $user;
    }
    /**
     * Get password - returns the password
     *
     * @access public
     * @return string
     */
    function getPass() {
        return $this->pass;
    }
    /**
     * Set password - sets the password
     *
     * @param  string  password
     * @access public
     */
    function setPass($pass) {
        $this->pass = $pass;
    }
    /**
     * Get host - returns the hostname/ip, or null if no hostname/ip was specifi
     *
     * @access public
     * @return string
     */
    function getHost() {
        return $this->host;
    }
    /**
     * Set host - sets the hostname/ip
     *
     * @param  string  hostname
     * @access public
     */
    function setHost($host) {
        $this->host = $host;
    }
    /**
     * Get port - returns the port number, or null if no port was specified
     *
     * @access public
     * @return int
     */
    function getPort() {
        return (isset($this->port)) ? $this->port : null;
    }
    /**
     * Set port - sets the port number
     *
     * @param  int   port number
     * @access public
     */
    function setPort($port) {
        $this->port = $port;
    }
    /**
     * Gets the path string
     *
     * @access public
     * @return string
     */
    function getPath() {
        return $this->path;
    }
    /**
     * Set path
     *
     * @param  string  fragment for page anchors
     * @access public
     */
    function setPath($path) {
        $this->path = $path;
    }
    /**
     * Gets the archor string
     *
     * @access public
     * @return string
     */
    function getAnchor() {
        return $this->anchor;
    }
    /**
     * Set anchor - sets everything after the "#"
     *
     * @param  string  fragment for page anchors
     * @access public
     */
    function setAnchor($anchor) {
        $this->anchor = $anchor;
    }
    /**
     * Checks whether the current URI is using HTTPS
     *
     * @access  public
     * @return  boolean
     */
    function checkSSL() {
        return $this->getScheme() == 'https' ? TRUE : FALSE;
    }
} // end class mosUri

require_once(mamboCore::get('rootPath').'/includes/tm_encrypt/std.encryption.class.inc');
class mosCrypto extends encryption_class {
	var $key;
	function &getInstance() {
		static $instance;
		if (!is_object($instance)) {
			$instance = new mosCrypto;
			$instance->key = mosCreateGUID();
		}
		return $instance;
	}
	function get($property, $default=null) {
		if(isset($this->$property)) {
			return $this->$property;
		} else {
			return $default;
		}
	}
	function encrypt($plain_text, $key='') {
		$this->key = $key !== '' ? $key : mosCreateGUID();
		$mainframe =& mosMainframe::getInstance();
		$enc_text = parent::encrypt($this->key, $plain_text, strlen($plain_text));
		return $enc_text;
	}
	function decrypt($enc_text, $key='') {
		$this->key = $key !== '' ? $key : mosCreateGUID();
		$plain_text = parent::decrypt($this->key, $enc_text);
		return $plain_text;
	}
	function encryptQuery($query, $key='') {
		$this->key = $key !== '' ? $key : mosCreateGUID();
		return base64_encode(urlencode($this->encrypt($query, $key)));
	}
	function decryptQuery($query, $key='') {
		$this->key = $key !== '' ? $key : mosCreateGUID();
		return $this->decrypt(urldecode(base64_decode($query)), $key);
	}
}
?>
