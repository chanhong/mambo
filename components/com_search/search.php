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

require_once( $mainframe->getPath( 'front_html' ) );

switch ( $task ) {
	default:
		viewSearch();
		break;
}

function viewSearch() {
	global $mainframe, $mosConfig_absolute_path, $mosConfig_lang, $my;
	global $Itemid, $database, $_MAMBOTS;
	
	$gid = $my->gid;
	
	// Adds parameter handling
	if( $Itemid > 0 ) {
		$menu =& new mosMenu( $database );
		$menu->load( $Itemid );
		$params =& new mosParameters( $menu->params );
		$params->def( 'page_title', 1 );
		$params->def( 'pageclass_sfx', '' );
		$params->def( 'header', $menu->name, T_('Search') );
		$params->def( 'back_button', $mainframe->getCfg( 'back_button' ) );
	} else {
		$params =& new mosParameters('');
		$params->def( 'page_title', 1 );
		$params->def( 'pageclass_sfx', '' );
		$params->def( 'header', T_('Search') );
		$params->def( 'back_button', $mainframe->getCfg( 'back_button' ) );
	}
	
	// html output
	search_html::openhtml( $params );
	
	$searchword = mosGetParam( $_REQUEST, 'searchword', '' );
	$searchword = $database->getEscaped( trim( $searchword ) );
	
	$search_ignore = array();
	@include "$mosConfig_absolute_path/language/$mosConfig_lang.ignore.php";
	
	$orders = array();
	$orders[] = mosHTML::makeOption( 'newest', T_('Newest first') );
	$orders[] = mosHTML::makeOption( 'oldest', T_('Oldest first') );
	$orders[] = mosHTML::makeOption( 'popular', T_('Most popular') );
	$orders[] = mosHTML::makeOption( 'alpha', T_('Alphabetical') );
	$orders[] = mosHTML::makeOption( 'category', T_('Section/Category') );
	$ordering = mosGetParam( $_REQUEST, 'ordering', 'newest');
	if (!in_array($ordering, array('newest', 'oldest', 'popular', 'alpha', 'category'))) $ordering = 'newest';
	$lists = array();
	$lists['ordering'] = mosHTML::selectList( $orders, 'ordering', 'class="inputbox"', 'value', 'text', $ordering );

	$searchphrase = mosGetParam( $_REQUEST, 'searchphrase', 'any' );
	if (!in_array($searchphrase, array('any', 'all', 'exact'))) $searchphrase = 'any';
	$searchphrases = array();
	
	$phrase = new stdClass();
	$phrase->value = 'any';
	$phrase->text = T_('Any words');
	$searchphrases[] = $phrase;
	
	$phrase = new stdClass();
	$phrase->value = 'all';
	$phrase->text = T_('All words');
	$searchphrases[] = $phrase;
	
	$phrase = new stdClass();
	$phrase->value = 'exact';
	$phrase->text = T_('Exact phrase');
	$searchphrases[] = $phrase;	

	$lists['searchphrase']= mosHTML::radioList( $searchphrases, 'searchphrase', '', $searchphrase );

	// html output
	search_html::searchbox( htmlspecialchars( $searchword ), $lists, $params );
	
	if (!$searchword) {
		if ( count( $_POST ) ) {
			// html output
			// no matches found
			search_html::message( T_('No results were found'), $params );
		}
	} else {
		foreach ($search_ignore as $ignore_word) $searchword = preg_replace("/(^|\W)$ignore_word($|\W)/i", '$1$2', $searchword);
		$searchword = trim($searchword);
		if (!$searchword) search_html::message( T_('One or more common words were ignored in the search'), $params );
	}
	if ($searchword) {
		// html output
		search_html::searchintro( htmlspecialchars( $searchword ), $params );
	
		mosLogSearch( $searchword );
		$phrase 	= mosGetParam( $_REQUEST, 'searchphrase', '' );
		if (!in_array($phrase, array('any', 'all', 'exact'))) $phrase = 'any';
		$ordering 	= mosGetParam( $_REQUEST, 'ordering', '' );
		if (!in_array($ordering, array('newest', 'oldest', 'popular', 'alpha', 'category'))) $ordering = 'newest';
	
		$_MAMBOTS->loadBotGroup( 'search' );
		$results 	= $_MAMBOTS->trigger( 'onSearch', array( $searchword, $phrase, $ordering ) );
		$rows = array();
		foreach($results as $result) {
			if ($result) $rows = array_merge($rows, $result);
		}
	
		$totalRows = count( $rows );
	
		for ($i=0; $i < $totalRows; $i++) {
			$row = &$rows[$i]->text;
			if ($phrase == 'exact') {
        $searchwords = array($searchword);
        $needle = $searchword;
      } else {
        $searchwords = explode(' ', $searchword);
        $needle = $searchwords[0];
      }
      
		$row = mosPrepareSearchContent( $row, 200, $needle );

      foreach ($searchwords as $hlword) {
			$row = preg_replace( '/'. preg_quote($hlword, '/'). '/i', "<span class=\"highlight\">\\0</span>", $row); 
      		}
	
			if (!eregi( '^http', $rows[$i]->href )) {
				// determines Itemid for Content items
				if ( strstr( $rows[$i]->href, 'view' ) ) {
					// tests to see if itemid has already been included - this occurs for typed content items
					if ( !strstr( $rows[$i]->href, 'Itemid' ) ) {
						$temp = explode( 'id=', $rows[$i]->href );
						$rows[$i]->href = $rows[$i]->href. '&amp;Itemid='. $mainframe->getItemid($temp[1]);
					}
				}
			}
		}

		$mainframe->setPageTitle( T_('Search') );
		
		if ( $totalRows ) {
		// html output
			search_html::display( $rows, $params );
		} else {
		// html output
			search_html::displaynoresult();
		}
	
		// html output
		search_html::conclusion( $totalRows, htmlspecialchars( $searchword ) );
	}
	
	// displays back button
	echo '<br />';
	mosHTML::BackButton ( $params, 0 );	
}


function mosLogSearch( $search_term ) {
	global $database;
	global $mosConfig_enable_log_searches;

	if ( @$mosConfig_enable_log_searches ) {
		$query = "SELECT hits"
		. "\n FROM #__core_log_searches"
		. "\n WHERE LOWER(search_term)='$search_term'"
		;
		$database->setQuery( $query );
		$hits = intval( $database->loadResult() );
		if ( $hits ) {
			$query = "UPDATE #__core_log_searches SET hits=(hits+1) WHERE LOWER(search_term)='$search_term'";
			$database->setQuery( $query );
			$database->query();
		} else {
			$query = "INSERT INTO #__core_log_searches VALUES ('$search_term','1')";
			$database->setQuery( $query );
			$database->query();
		}
	}
}

/**
* Prepares results from search for display
* @param string The source string
* @param int Number of chars to trim
* @param string The searchword to select around
* @return string
*/
function mosPrepareSearchContent( $text, $length=200, $searchword ) {
	// strips tags won't remove the actual jscript
	$text = preg_replace( "'<script[^>]*>.*?</script>'si", "", $text );
	$text = preg_replace( '/{.+?}/', '', $text);
	//$text = preg_replace( '/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is','\2', $text );
	return mosSmartSubstr( strip_tags( $text ), $length, $searchword );
}

/**
* returns substring of characters around a searchword
* @param string The source string
* @param int Number of chars to return
* @param string The searchword to select around
* @return string
*/
function mosSmartSubstr($text, $length=200, $searchword) {
  $wordpos = strpos(strtolower($text), strtolower($searchword));
  $halfside = intval($wordpos - $length/2 - strlen($searchword));
  if ($wordpos && $halfside > 0) {
      return '...' . substr($text, $halfside, $length);
  } else {
    return substr( $text, 0, $length);
  }
}

?>
