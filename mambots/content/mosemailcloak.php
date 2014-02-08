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

$_MAMBOTS->registerFunction( 'onPrepareContent', 'botMosEmailCloak' );

/**
* Mambot that Cloaks all emails in content from spambots via javascript
*/
function botMosEmailCloak( $published, &$row, &$cparams, $page=0, $params ) {
	global $database;

	// load mambot params info
	/*$query = "SELECT id FROM #__mambots WHERE element = 'mosemailcloak' AND folder = 'content'";
	$database->setQuery( $query );
	$id 	= $database->loadResult();
	$mambot = new mosMambot( $database );
	$mambot->load( $id );*/
	$mambots =& mosMambotHandler::getInstance();
	$mambot = $mambots->getBot('mosemailcloack','content');
	$params =& new mosParameters( (isset($mambot->params)?$mambot->params:'') );
		
 	$mode		= $params->def( 'mode', 1 );
 	//$search 	= "([[:alnum:]_\.\-]+)(\@[[:alnum:]\.\-]+\.+)([[:alnum:]\.\-]+)";
 	$search 	= "([[:alnum:]_\.\-]+)(\@[[:alnum:]\.\-]+\.+)([[:alnum:]\.\-\?\=\%]+)";
 	$search_text 	= "([[:alnum:][:space:][:punct:]][^<>]+)";

	// search for derivativs of link code <a href="mailto:email@amail.com">email@amail.com</a>
	// extra handling for inclusion of title and target attributes either side of href attribute
	$searchlink	= "(<a [[:alnum:] _\"\'=\@\.\-]*href=[\"\']mailto:". $search ."[\"\'][[:alnum:] _\"\'=\@\.\-]*>)". $search ."</a>";
	if (is_callable(array($row, 'getText'))) $localtext = $row->getText();
	else $localtext = $row->text;
	while( eregi( $searchlink, $localtext, $regs ) ) {
		$mail 		= $regs[2] . $regs[3] . $regs[4];
		$mail_text 	= $regs[5] . $regs[6] . $regs[7];

		// check to see if mail text is different from mail addy
		if ( $mail_text ) {
			$replacement 	= mosHTML::emailCloaking( $mail, $mode, $mail_text );
		} else {
			$replacement 	= mosHTML::emailCloaking( $mail, $mode );
		}

		// replace the found address with the js cloacked email
		$localtext 	= str_replace( $regs[0], $replacement, $localtext );
	}

	// search for derivativs of link code <a href="mailto:email@amail.com">anytext</a>
	// extra handling for inclusion of title and target attributes either side of href attribute
	$searchlink	= "(<a [[:alnum:] _\"\'=\@\.\-]*href=[\"\']mailto:". $search ."[\"\'][[:alnum:] _\"\'=\@\.\-]*)>". $search_text ."</a>";
	while( eregi( $searchlink, $localtext, $regs ) ) {
		$mail 		= $regs[2] . $regs[3] . $regs[4];
		$mail_text 	= $regs[5];

		$replacement 	= mosHTML::emailCloaking( $mail, $mode, $mail_text, 0 );

		// replace the found address with the js cloacked email
		$localtext 	= str_replace( $regs[0], $replacement, $localtext );
	}

	// search for plain text email@amail.com
	while( eregi( $search, $localtext, $regs ) ) {
		$mail = $regs[0];

		$replacement = mosHTML::emailCloaking( $mail, $mode );

		// replace the found address with the js cloacked email
		$localtext = str_replace( $regs[0], $replacement, $localtext );
	}

	if (is_callable(array($row, 'saveText'))) $row->saveText($localtext);
	else $row->text = $localtext;

}
?>
