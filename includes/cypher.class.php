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

class mosCypher {
	var $_key = '';
	var $plain_text;
	var $enc_text;
	function &getInstance() {
        static $instance;
        if (!is_object($instance)) {
			$instance = new mosCypher;
			$instance->_key = mamboCore::get('mosConfig_secret');
		}
        return $instance;
	}
	function encrypt($plain_text) {
		static $instance;
        if (!is_object($instance)) $instance =& mosCypher::getInstance();
		static $handler;
        if (!is_object($handler)) $handler =& mosCypherHandler::getInstance();
		$instance->enc_text = $handler->encrypt($instance->_key, $plain_text, strlen($plain_text));
		return $instance->enc_text;
	}
	function decrypt($enc_text) {
		static $instance;
        if (!is_object($instance)) $instance =& mosCypher::getInstance();
		static $handler;
        if (!is_object($handler)) $handler =& mosCypherHandler::getInstance();
		$instance->plain_text = $handler->decrypt($instance->_key, $enc_text);
		return $instance->plain_text;
	}
	function encryptQuery($query) {
		static $handler;
        if (!is_object($handler)) $handler =& mosCypherHandler::getInstance();
		return base64_encode(urlencode($handler->encrypt($query)));
	}
	function decryptQuery($query) {
		static $handler;
        if (!is_object($handler)) $handler =& mosCypherHandler::getInstance();
		return $handler->decrypt(urldecode(base64_decode($query)));
	}
	function get($key='') {
		$key = trim($key);
		if ( strlen($key) == 0 ) return null;
		if ( $key{0} == '_' ) return null;
		static $instance;
        if (!is_object($instance)) $instance =& mosCypher::getInstance();
		if ( !isset($instance->$key) ) return null;
		return $instance->$key;
	}
	function set($key='', $value='') {
		$key = trim($key);
		if ( strlen($key) == 0 ) return;
		if ( $key{0} == '_' ) return;
		static $instance;
        if (!is_object($instance)) $instance =& mosCypher::getInstance();
		if ( !isset($instance->$key) ) return;
		$instance->$key = $value;
	}
	function setKey($value='') {
		static $instance;
        if (!is_object($instance)) $instance =& mosCypher::getInstance();
		if ( strlen($value) !== 0 ) $instance->_key = $value;
	}
} // end class mosCypher

class mosCypherHandler {
	function &getInstance() {
		static $instance;
		if (!is_object($instance)) {
			require_once(mamboCore::get('mosConfig_absolute_path').'/includes/tm_encrypt/std.encryption.class.inc');
			$instance = new encryption_class();
		}
		return $instance;
	}
	function encrypt($plain_text, $key) {
		static $instance;
		if (!is_object($instance)) $instance =& mosCypherHandler::getInstance();
		return $instance->encrypt($plain_text, $key);
	}
	function decrypt($enc_text, $key) {
		static $instance;
		if (!is_object($instance)) $instance =& mosCypherHandler::getInstance();
		return $instance->decrypt($enc_text, $key);
	}
} // end class mosCypherHandler
?>