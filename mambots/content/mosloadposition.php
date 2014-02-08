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

$_MAMBOTS->registerFunction( 'onPrepareContent', 'botMosLoadPosition' );

/**
* Mambot that loads module positions within content
*/
function botMosLoadPosition( $published, &$row, &$cparams, $page=0, $params ) {
	global $database;
	
 	// expression to search for
 	$regex = '/{mosloadposition\s*.*?}/i';
  	
	if (is_callable(array($row, 'getText'))) $localtext = $row->getText();
	else $localtext = $row->text;
 	// find all instances of mambot and put in $matches
	preg_match_all( $regex, $localtext, $matches );
	
	// Number of mambots
 	$count = count( $matches[0] );

 	// mambot only processes if there are any instances of the mambot in the text
 	if ( $count ) {
 	
		// load mambot params info
		/*$query = "SELECT id FROM #__mambots WHERE element = 'mosloadposition' AND folder = 'content'";
		$database->setQuery( $query );
		$id 	= $database->loadResult();
		$mambot = new mosMambot( $database );
		$mambot->load( $id );*/
		$mambots =& mosMambotHandler::getInstance();
        $mambot = $mambots->getBot('mosloadposition','content');
		$params =& new mosParameters( (isset($mambot->params)?$mambot->params:'') );
		
		$style	= $params->def( 'style', -2 );

 		processPositions( $localtext, $matches, $count, $regex, $style );
	}
	// Save the results of processing
	if (is_callable(array($row, 'saveText'))) $row->saveText($localtext);
	else $row->text = $localtext;

}

function processPositions ( &$text, &$matches, $count, $regex, $style ) {
	global $database;
	
	$query = "SELECT position"
	. "\n FROM #__template_positions"
	. "\n ORDER BY position"
	;
	$database->setQuery( $query );
 	$positions 	= $database->loadResultArray();

 	for ( $i=0; $i < $count; $i++ ) {
 		$load = str_replace( 'mosloadposition', '', $matches[0][$i] );
 		$load = str_replace( '{', '', $load );
 		$load = str_replace( '}', '', $load );
 		$load = trim( $load );
		
		foreach ( $positions as $position ) {
	 		if ( $position == @$load ) {		 			
				$modules	= loadPosition( $load, $style );					
				$text 	= preg_replace( '{'. $matches[0][$i] .'}', $modules, $text );
				break;			
	 		}	 			
 		}
 	}

  	// removes tags without matching module positions
	$text = preg_replace( $regex, '', $text );
}

function loadPosition( $position, $style=-2 ) {
	$modules = '';
	if ( mosCountModules( $position ) ) {
		ob_start();
		mosLoadModules ( $position, $style );
		$modules = ob_get_contents();
		ob_end_clean();
	}

	return $modules;
}

