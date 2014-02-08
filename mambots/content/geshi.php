<?php
/**
* @package Mambo Open Source
* @copyright (C) 2005 - 2007 Mambo Foundation Inc.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*
* Mambo was originally developed by Miro (www.miro.com.au) in 2000. Miro assigned the copyright in Mambo to The Mambo Foundation in 2005 to ensure
* that Mambo remained free Open Source software owned and managed by the community.
* Mambo is Free Software
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class geshiXML {
	var $parser;
	var $opentags = array();
	var $opencount;
	var $accept = array();
	var $pre = false;
	var $pattrs = array();

	function geshiXML () {
		$this->parser = xml_parser_create();
		$startfunc = array (&$this, 'start_element');
		$endfunc = array (&$this, 'end_element');
		$charfunc = array (&$this, 'character_data');
		xml_set_element_handler ($this->parser, $startfunc, $endfunc);
		xml_set_character_data_handler ($this->parser, $charfunc);
	}

	function parse ($arguments) {
		xml_parse($this->parser, $arguments, true);
		if ($parser) {
			xml_parser_free($parser);
		}
		return $this->pattrs;
	}

	function start_element ($parser, $element_name, $element_attrs) {
		if ($element_name == 'PRE') $pattrs = $element_attrs;
	}

	function end_element ($parser, $element_name) {
		return;
	}

	function character_data ($parser, $data) {
		return;
	}

}

$_MAMBOTS->registerFunction( 'onPrepareContent', 'botGeshi' );

/**
* Code Highlighting Mambot
*
* Replaces <pre>...</pre> tags with highlighted text
*/
function botGeshi( $published, &$row, &$params, $page=0 ) {

	// define the regular expression for the bot
	$regex = "#<pre\s*(.*?)>(.*?)</pre>#s";

	if (is_callable(array($row, 'getText'))) $localtext = $row->getText();
	else $localtext = $row->text;
	if (!$published) {
		$localtext = preg_replace( $regex, '', $localtext );
		if (is_callable(array($row, 'saveText'))) $row->saveText($localtext);
		else $row->text = $localtext;
		return;
	}

	$GLOBALS['_MAMBOT_GESHI_PARAMS'] =& $params;

	// perform the replacement
	$localtext = preg_replace_callback( $regex, 'botGeshi_replacer', $localtext );
	if (is_callable(array($row, 'saveText'))) $row->saveText($localtext);
	else $row->text = $localtext;

	return true;
}
/**
* Replaces the matched tags an image
* @param array An array of matches (see preg_match_all)
* @return string
*/
function botGeshi_replacer( &$matches ) {
	$params =& $GLOBALS['_MAMBOT_GESHI_PARAMS'];
	include_once( dirname( __FILE__ ) . '/geshi/geshi.php' );
	$parser =& new geshiXML();
	$args = $parser->parse($matches[1]);
	$text = $matches[2];

	$lang = mosGetParam( $args, 'lang', 'php' );
	$lines = mosGetParam( $args, 'lines', 'false' );


	$html_entities_match = array( "|\<br \/\>|", "#<#", "#>#", "|&#39;|", '#&quot;#', '#&nbsp;#' );
	$html_entities_replace = array( "\n", '&lt;', '&gt;', "'", '"', ' ' );

	$text = preg_replace( $html_entities_match, $html_entities_replace, $text );

	$text = str_replace('&lt;', '<', $text);
	$text = str_replace('&gt;', '>', $text);

/*
	// Replace 2 spaces with "&nbsp; " so non-tabbed code indents without making huge long lines.
	$text = str_replace("  ", "&nbsp; ", $text);
	// now Replace 2 spaces with " &nbsp;" to catch odd #s of spaces.
	$text = str_replace("  ", " &nbsp;", $text);
*/
	// Replace tabs with "&nbsp; &nbsp;" so tabbed code indents sorta right without making huge long lines.
	//$text = str_replace("\t", "&nbsp; &nbsp;", $text);
	$text = str_replace( "\t", '  ', $text );

	$geshi = new GeSHi( $text, $lang, dirname( __FILE__ ) . '/geshi/geshi' );
	if ($lines == 'true') {
		$geshi->enable_line_numbers( GESHI_NORMAL_LINE_NUMBERS );
	}
	$text = $geshi->parse_code();

	return $text;
}


?>
