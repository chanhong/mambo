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

require_once($mosConfig_absolute_path.'/components/com_content/content.class.php');
global $mosConfig_offset, $mosConfig_hideAuthor, $mosConfig_hideModifyDate, $mosConfig_hideCreateDate, $mosConfig_live_site;

function dofreePDF ( $database ) {
	global $mosConfig_live_site, $mosConfig_sitename, $mosConfig_offset, $mosConfig_hideCreateDate, $mosConfig_hideAuthor, $mosConfig_hideModifyDate;

	$id = intval( mosGetParam( $_REQUEST, 'id', 1 ) );

	// Access check
	global $gid;
	$now = date( 'Y-m-d H:i:s', time() + $mosConfig_offset * 60 * 60 );
	$query = "SELECT COUNT(a.id)"
	. "\n FROM #__content AS a"
	. "\n LEFT JOIN #__categories AS cc ON cc.id = a.catid"
	. "\n LEFT JOIN #__sections AS s ON s.id = cc.section AND s.scope='content'"
	. "\n LEFT JOIN #__users AS u ON u.id = a.created_by"
	. "\n LEFT JOIN #__content_rating AS v ON a.id = v.content_id"
	. "\n LEFT JOIN #__groups AS g ON a.access = g.id"
	. "\n WHERE a.id='". $id ."' "
	. "\n AND (a.state = '1' OR a.state = '-1')"
	. "\n AND (publish_up = '0000-00-00 00:00:00' OR publish_up <= '$now')"
	. "\n AND (publish_down = '0000-00-00 00:00:00' OR publish_down >= '$now')"
	. "\n AND a.access <= ". intval($gid)
	;
	$database->setQuery( $query );
	if (!$database->loadResult() ) {
		exit(T_('You are not authorized to view this resource.'));
	}

	include( 'includes/class.ezpdf.php' );
	$row = new mosContent( $database );
	$row->load( $id );
	//Find Author Name
	$users_rows = new mosUser( $database );
	$users_rows->load( $row->created_by );
	$row->author = $users_rows->name;
	$row->usertype = $users_rows->usertype;

	// Ugly but needed to get rid of all the stuff the PDF class cant handle
	$row->fulltext 	= str_replace( '<p>', "\n\n" , $row->fulltext );
	$row->fulltext 	= str_replace( '<P>', "\n\n" , $row->fulltext );
	$row->fulltext 	= str_replace( '<br />', "\n" , $row->fulltext );
	$row->fulltext 	= str_replace( '<br>', "\n" , $row->fulltext );
	$row->fulltext 	= str_replace( '<BR />', "\n" , $row->fulltext );
	$row->fulltext 	= str_replace( '<BR>', "\n" , $row->fulltext );
	$row->fulltext 	= str_replace( '<li>', "\n - " , $row->fulltext );
	$row->fulltext 	= str_replace( '<LI>', "\n - " , $row->fulltext );
	$row->fulltext 	= strip_tags( $row->fulltext );
	$row->fulltext 	= str_replace( '{mosimage}', '', $row->fulltext );
	$row->fulltext 	= str_replace( '{mospagebreak}', '', $row->fulltext );
	$row->fulltext 	= decodeHTML( $row->fulltext );

	$row->introtext 	= str_replace( '<p>', "\n\n", $row->introtext );
	$row->introtext 	= str_replace( '<P>', "\n\n", $row->introtext );
	$row->introtext 	= str_replace( '<li>', "\n - " , $row->introtext );
	$row->introtext 	= str_replace( '<LI>', "\n - " , $row->introtext );
	$row->introtext 	= strip_tags( $row->introtext );
	$row->introtext 	= str_replace( '{mosimage}', '', $row->introtext );
	$row->introtext 	= str_replace( '{mospagebreak}', '', $row->introtext );
	$row->introtext 	= decodeHTML( $row->introtext );

	$pdf =& new Cezpdf( 'a4', 'P' );  //A4 Portrait
	$pdf -> ezSetCmMargins( 2, 1.5, 1, 1);
	$pdf->selectFont( './fonts/Helvetica.afm' ); //choose font

	$all = $pdf->openObject();
	$pdf->saveState();
	$pdf->setStrokeColor( 0, 0, 0, 1 );

	// footer
	$pdf->line( 10, 40, 578, 40 );
	$pdf->line( 10, 822, 578, 822 );
	$pdf->addText( 30, 34, 6, $mosConfig_live_site .' - '. $mosConfig_sitename );
	$pdf->addText( 250, 34, 6, T_('Powered by Mambo') );
	$pdf->addText( 450, 34, 6, T_('Generated:'). date( 'j F, Y, H:i', time() + $mosConfig_offset*60*60 ) );

	$pdf->restoreState();
	$pdf->closeObject();
	$pdf->addObject( $all, 'all' );
	$pdf->ezSetDy( 30 );

	$txt1 = $row->title;
	$pdf->ezText( $txt1, 14 );

	$txt2 = NULL;
	$mod_date = NULL; 
	$create_date = NULL;
	if ( intval( $row->modified ) <> 0 ) {
		$mod_date = mosFormatDate( $row->modified );
	}
	if ( intval( $row->created ) <> 0 ) {
		$create_date = mosFormatDate( $row->created );
	}

	if ( $mosConfig_hideCreateDate == '0' ) {
		$txt2 .= '('. $create_date .') - ';
	}

	if ( $mosConfig_hideAuthor == "0" ) {
		if ( $row->author != '' && $mosConfig_hideAuthor == '0' ) {
			if ($row->usertype == 'administrator' || $row->usertype == 'superadministrator') {
				$txt2 .=  T_('Written by') .' '. ( $row->created_by_alias ? $row->created_by_alias : $row->author );
			} else {
				$txt2 .=  T_('Contributed by') .' '. ( $row->created_by_alias ? $row->created_by_alias : $row->author );
			}
		}
	}

	if ( $mosConfig_hideModifyDate == "0" ) {
		$txt2 .= ' - '. T_('Last Updated') .' ('. $mod_date .') ';
	}

	$txt2 .= "\n\n";
	$pdf->ezText( $txt2, 8 );
	$txt3 = $row->introtext ."\n". $row->fulltext;
	$pdf->ezText( $txt3, 10 );
	$pdf->ezStream();
}

function decodeHTML( $string ) {
	$string = strtr( $string, array_flip(get_html_translation_table( HTML_ENTITIES ) ) );
	$string = preg_replace( "/&#([0-9]+);/me", "chr('\\1')", $string );
	return $string;
}

function get_php_setting ($val ) {
	$r = ( ini_get( $val ) == '1' ? 1 : 0 );
	return $r ? T_('ON') : T_('OFF');
}

dofreePDF ( $database );
?>
