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

$this->registerFunction( 'onBeforeDisplayContent', 'botVoting' );

function botVoting( &$row, &$params, $page=0 ) {
	global $mosConfig_live_site, $mosConfig_absolute_path, $cur_template;
	global $Itemid;
	if (strtolower( get_class($row) ) != strtolower( 'mosExtendedContent' )) return;
	if (is_callable(array($row,'getId'))) $id = $row->getId();
	else $id = $row->id;
	$option = 'com_content';
	$task = mosGetParam( $_REQUEST, 'task', '' );

	$html = '';
	if ($params->get( 'rating' ) && !$params->get( 'popup' )){
		if (is_callable(array($row,'getRating'))) $rating = $row->getRating();
		else $rating = $row->rating;
		if (is_callable(array($row,'getRatingCount'))) $rating_count = $row->getRatingCount();
		else $rating_count = $row->rating_count;
		$html .= '<form method="post" action="' . sefRelToAbs( 'index.php' ) . '">';
		$img = '';
		
		// look for images in template if available
		$mainframe =& mosMainFrame::getInstance();
		$starImageOn = $mainframe->ImageCheck( 'rating_star.png', '/images/M_images/' );
		$starImageOff = $mainframe->ImageCheck( 'rating_star_blank.png', '/images/M_images/' );

		for ($i=0; $i < $rating; $i++) {
			$img .= $starImageOn;
		}
		for ($i=$rating; $i < 5; $i++) {
			$img .= $starImageOff;
		}
		$html .= '<span class="content_rating">';
		$html .= T_('User Rating') . ':' . $img . '&nbsp;/&nbsp;';
		$html .= intval( $rating_count );
		$html .= "</span>\n<br />\n";
		$url = @$_SERVER['REQUEST_URI'];
		$url = ampReplace( $url );
		
		require_once(mamboCore::get('mosConfig_absolute_path').'/includes/phpInputFilter/class.inputfilter.php');
		$iFilter = new InputFilter( null, null, 1, 1 );
		$url = trim( $iFilter->process( $url ) );
		
		if (!$params->get( 'intro_only' ) && $task != "blogsection") {
			$html .= '<span class="content_vote">';
			$html .= T_('Poor');
			$html .= '<input type="radio" alt="'.T_('vote 1 star').'" name="user_rating" value="1" />';
			$html .= '<input type="radio" alt="'.T_('vote 2 star').'" name="user_rating" value="2" />';
			$html .= '<input type="radio" alt="'.T_('vote 3 star').'" name="user_rating" value="3" />';
			$html .= '<input type="radio" alt="'.T_('vote 4 star').'" name="user_rating" value="4" />';
			$html .= '<input type="radio" alt="'.T_('vote 5 star').'" name="user_rating" value="5" checked="checked" />';
			$html .= T_('Best');
			$html .= '&nbsp;<input class="button" type="submit" name="submit_vote" value="'. T_('Rate') .'" />';
			$html .= '<input type="hidden" name="task" value="vote" />';
			$html .= '<input type="hidden" name="pop" value="0" />';
			$html .= '<input type="hidden" name="option" value="com_content" />';
			$html .= '<input type="hidden" name="Itemid" value="'. $Itemid .'" />';
			$html .= '<input type="hidden" name="cid" value="'. $id .'" />';
			//$html .= '<input type="hidden" name="url" value="'. $url .'" />';
			$html .= '</span>';
		}
		$html .= "</form>\n";
	}
	return $html;
}
?>
