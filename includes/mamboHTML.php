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

/**
* Utility class for all HTML drawing classes
*/

class mosHTML {

	function makeOption( $value, $text='' ) {
		$obj = new stdClass;
		$obj->value = $value;
		$obj->text = trim( $text ) ? $text : $value;
		return $obj;
	}

	function writableCell( $folder ) {
	 	echo '<tr>';
	  	echo '<td class="item">' . $folder . '/</td>';
	  	echo '<td align="left">';
	  	echo is_writable( "../$folder" ) ? '<strong><span class="green">'.T_('Writeable').'</span></strong>' : '<strong><span class="red">'.T_('Unwriteable').'</span></strong>' . '</td>';
	  	echo '</tr>';
	}

	/**
	* Generates an HTML select list
	* @param array An array of objects
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param string The name of the object variable for the option value
	* @param string The name of the object variable for the option text
	* @param mixed The key that is selected
	* @returns string HTML for the select list
	*/
	function selectList ( &$arr, $tag_name, $tag_attribs, $key, $text, $selected=NULL ) {
		if (is_array($arr)){
		  reset( $arr );
		}
		$html = "\n<select name=\"$tag_name\" $tag_attribs>";
		for ($i=0, $n=count( $arr ); $i < $n; $i++ ) {
			$k = $arr[$i]->$key;
			$t = $arr[$i]->$text;
			$id = isset($arr[$i]->id) ? $arr[$i]->id : null;

			$extra = '';
			$extra .= $id ? " id=\"" . $arr[$i]->id . "\"" : '';
			if (is_array( $selected )) {
				foreach ($selected as $obj) {
					$k2 = $obj->$key;
					if ($k == $k2) {
						$extra .= " selected=\"selected\"";
						break;
					}
				}
			} else {
				$extra .= ($k == $selected ? " selected=\"selected\"" : '');
			}
			$html .= "\n\t<option value=\"".$k."\"$extra>" . $t . "</option>";
		}
		$html .= "\n</select>\n";
		return $html;
	}

	/**
	* Writes a select list of integers
	* @param int The start integer
	* @param int The end integer
	* @param int The increment
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @param string The printf format to be applied to the number
	* @returns string HTML for the select list
	*/
	function integerSelectList( $start, $end, $inc, $tag_name, $tag_attribs, $selected, $format="" ) {
		$start = intval( $start );
		$end = intval( $end );
		$inc = intval( $inc );
		$arr = array();
		for ($i=$start; $i <= $end; $i+=$inc) {
			$fi = $format ? sprintf( "$format", $i ) : "$i";
			$arr[] = mosHTML::makeOption( $fi, $fi );
		}

		return mosHTML::selectList( $arr, $tag_name, $tag_attribs, 'value', 'text', $selected );
	}

	/**
	* Writes a select list of month names based on Language settings
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @returns string HTML for the select list values
	*/
	function monthSelectList( $tag_name, $tag_attribs, $selected ) {
		$arr = array(
		mosHTML::makeOption( '01', T_('January') ),
		mosHTML::makeOption( '02', T_('February') ),
		mosHTML::makeOption( '03', T_('March') ),
		mosHTML::makeOption( '04', T_('April') ),
		mosHTML::makeOption( '05', T_('May') ),
		mosHTML::makeOption( '06', T_('June') ),
		mosHTML::makeOption( '07', T_('July') ),
		mosHTML::makeOption( '08', T_('August') ),
		mosHTML::makeOption( '09', T_('September') ),
		mosHTML::makeOption( '10', T_('October') ),
		mosHTML::makeOption( '11', T_('November') ),
		mosHTML::makeOption( '12', T_('December') )
		);

		return mosHTML::selectList( $arr, $tag_name, $tag_attribs, 'value', 'text', $selected );
	}

	/**
	* Writes a yes/no select list
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @returns string HTML for the select list values
	*/
	function yesnoSelectList( $tag_name, $tag_attribs, $selected, $yes=false, $no=false ) {
		$arr = array(
		mosHTML::makeOption( '0', $no  ? $no  : T_('No') ),
		mosHTML::makeOption( '1', $yes ? $yes : T_('Yes') ),
		);

		return mosHTML::selectList( $arr, $tag_name, $tag_attribs, 'value', 'text', $selected );
	}

	/**
	* Generates an HTML radio list
	* @param array An array of objects
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @param string The name of the object variable for the option value
	* @param string The name of the object variable for the option text
	* @returns string HTML for the select list
	*/
	function radioList( &$arr, $tag_name, $tag_attribs, $selected=null, $key='value', $text='text' ) {
		reset( $arr );
		$html = "";
		foreach ($arr as $choice) {
			$id = @$choice->id;
			$extra = $id ? " id=\"" . $choice->id . "\"" : '';
			if (is_array( $selected )) {
				foreach ($selected as $obj) {
					if ($choice->$key == $obj->$key) {
						$extra .= ' selected="selected"';
						break;
					}
				}
			} else {
				$extra .= ($choice->$key == $selected ? " checked=\"checked\"" : '');
			}
			$html .= "\n\t<input type=\"radio\" name=\"$tag_name\" value=\"".$choice->$key."\"$extra $tag_attribs />" . $choice->$text;
		}
		$html .= "\n";
		return $html;
	}

	/**
	* Writes a yes/no radio list
	* @param string The value of the HTML name attribute
	* @param string Additional HTML attributes for the <select> tag
	* @param mixed The key that is selected
	* @returns string HTML for the radio list
	*/
	function yesnoRadioList( $tag_name, $tag_attribs, $selected, $yes=false, $no=false ) {

		$arr = array(
		mosHTML::makeOption( '0', $no  ? $no  : T_('No') ),
		mosHTML::makeOption( '1', $yes ? $yes : T_('Yes') )
		);
		return mosHTML::radioList( $arr, $tag_name, $tag_attribs, $selected );
	}

	/**
	* @param int The row index
	* @param int The record id
	* @param boolean
	* @param string The name of the form element
	* @return string
	*/
	function idBox( $rowNum, $recId, $checkedOut=false, $name='cid' ) {
		if ( $checkedOut ) {
			return '';
		} else {
			return '<input type="checkbox" id="cb'.$rowNum.'" name="'.$name.'[]" value="'.$recId.'" onclick="isChecked(this.checked);" />';
		}
	}

	function sortIcon( $base_href, $field, $state='none' ) {
		$mosConfig_live_site = mamboCore::get('mosConfig_live_site');
		$alts = array(
		'none' => T_('No Sorting'),
		'asc' => T_('Sort Ascending'),
		'desc' => T_('Sort Descending'),
		);
		$next_state = 'asc';
		if ($state == 'asc') {
			$next_state = 'desc';
		} else if ($state == 'desc') {
			$next_state = 'none';
		}

		$html = "<a href=\"$base_href&field=$field&order=$next_state\">"
		. "<img src=\"$mosConfig_live_site/images/M_images/sort_$state.png\" width=\"12\" height=\"12\" border=\"0\" alt=\"{$alts[$next_state]}\" />"
		. "</a>";
		return $html;
	}

	/**
	* Writes Close Button
	*/
	function CloseButton ( &$params, $hide_js=NULL ) {
		// displays close button in Pop-up window
		if ( $params->get( 'popup' ) && !$hide_js ) {
			?>
			<div align="center" style="margin-top: 30px; margin-bottom: 30px;">
			<a href='javascript:window.close();'>
			<span class="small">
			<?php echo T_('Close Window');?>
			</span>
			</a>
			</div>
			<?php
		}
	}

	/**
	* Writes Back Button
	*/
	function BackButton ( &$params, $hide_js=NULL ) {
		// Back Button
		if ( $params->get( 'back_button' ) && !$params->get( 'popup' ) && !$hide_js) {
			?>
			<div class="back_button">
			<a href='javascript:history.go(-1)'>
			<?php echo T_('Back'); ?>
			</a>
			</div>
			<?php
		}
	}

	/**
	* Cleans text of all formating and scripting code
	*/
	function cleanText ( &$text ) {
		$text = preg_replace( "'<script[^>]*>.*?</script>'si", '', $text );
		$text = preg_replace( '/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is', '\2 (\1)', $text );
		$text = preg_replace( '/<!--.+?-->/', '', $text );
		$text = preg_replace( '/{.+?}/', '', $text );
		$text = preg_replace( '/&nbsp;/', ' ', $text );
		$text = preg_replace( '/&amp;/', ' ', $text );
		$text = preg_replace( '/&quot;/', ' ', $text );
		$text = strip_tags( $text );
		$text = htmlspecialchars( $text );
		return $text;
	}

	/**
	* Writes Print icon
	*/
	function PrintIcon( &$row, &$params, $hide_js, $link, $status=NULL ) {
		if ( $params->get( 'print' )  && !$hide_js ) {
			// use default settings if none declared
			if ( !$status ) {
				$status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';
			}

			// checks template image directory for image, if non found default are loaded
			if ( $params->get( 'icons' ) ) {
				$mainframe =& mosMainFrame::getInstance();
				$image = $mainframe->ImageCheck( 'printButton.png', '/images/M_images/', NULL, NULL, T_('Print'));
			} else {
				$image = _ICON_SEP .'&nbsp;'. T_('Print'). '&nbsp;'. _ICON_SEP;
			}

			if ( $params->get( 'popup' ) && !$hide_js ) {
				// Print Preview button - used when viewing page
				?>
				<td align="right" class="buttonheading">
				<a href="#" onclick="javascript:window.print(); return false" title="<?php echo T_('Print');?>">
				<?php echo $image;?>
				</a>
				</td>
				<?php
			} else {
				// Print Button - used in pop-up window
				?>
				<td align="right" class="buttonheading">
				<a href="javascript:void window.open('<?php echo $link; ?>', 'win2', '<?php echo $status; ?>');" title="<?php echo T_('Print');?>">
				<?php echo $image;?>
				</a>
				</td>
				<?php
			}
		}
	}

	/**
	* simple Javascript Cloaking
	* email cloacking
 	* by default replaces an email with a mailto link with email cloacked
	*/
	function emailCloaking( $mail, $mailto=1, $text='', $email=1 ) {
		// convert text
		$mail 		= mosHTML::encoding_converter( $mail );
		// split email by @ symbol
		$mail		= explode( '@', $mail );
		$mail_parts	= explode( '.', $mail[1] );
		// random number
		$rand	= rand( 1, 100000 );

		$replacement 	= "\n<script language='JavaScript' type='text/javascript'> \n";
		$replacement 	.= "<!-- \n";
		$replacement 	.= "var prefix = '&#109;a' + 'i&#108;' + '&#116;o'; \n";
		$replacement 	.= "var path = 'hr' + 'ef' + '='; \n";
		$replacement 	.= "var addy". $rand ." = '". @$mail[0] ."' + '&#64;' + '". implode( "' + '&#46;' + '", $mail_parts ) ."'; \n";
		if ( $mailto ) {
			// special handling when mail text is different from mail addy
			if ( $text ) {
				if ( $email ) {
					// convert text
					$text 	= mosHTML::encoding_converter( $text );
					// split email by @ symbol
					$text 	= explode( '@', $text );
					$text_parts	= explode( '.', $text[1] );
					$replacement 	.= "var addy_text". $rand ." = '". @$text[0] ."' + '&#64;' + '". implode( "' + '&#46;' + '", @$text_parts ) ."'; \n";
				} else {
					$text 	= mosHTML::encoding_converter( $text );
					$replacement 	.= "var addy_text". $rand ." = '". $text ."';\n";
				}
				$replacement 	.= "document.write( '<a ' + path + '\'' + prefix + ':' + addy". $rand ." + '\'>' ); \n";
				$replacement 	.= "document.write( addy_text". $rand ." ); \n";
				$replacement 	.= "document.write( '<\/a>' ); \n";
			} else {
				$replacement 	.= "document.write( '<a ' + path + '\'' + prefix + ':' + addy". $rand ." + '\'>' ); \n";
				$replacement 	.= "document.write( addy". $rand ." ); \n";
				$replacement 	.= "document.write( '<\/a>' ); \n";
			}
		} else {
			$replacement 	.= "document.write( addy". $rand ." ); \n";
		}
		$replacement 	.= "//--> \n";
		$replacement 	.= "</script> \n";
		$replacement 	.= "<noscript> \n";
		$replacement 	.= T_('This email address is being protected from spam bots, you need Javascript enabled to view it');
		$replacement 	.= "\n</noscript> \n";

		return $replacement;
	}

	function encoding_converter( $text ) {
		// replace vowels with character encoding
		$text 	= str_replace( 'a', '&#97;', $text );
		$text 	= str_replace( 'e', '&#101;', $text );
		$text 	= str_replace( 'i', '&#105;', $text );
		$text 	= str_replace( 'o', '&#111;', $text );
		$text	= str_replace( 'u', '&#117;', $text );

		return $text;
	}
}

class mosCommonHTML {

	function ContentLegend( ) {
		?>
		<table cellspacing="0" cellpadding="4" border="0" align="center">
		<tr align="center">
			<td>
			<img src="images/publish_y.png" width="12" height="12" border="0" alt="<?php echo T_('Pending') ?>" />
			</td>
			<td>
			<?php echo T_('Published, but is <u>Pending</u>') ?> |
			</td>
			<td>
			<img src="images/publish_g.png" width="12" height="12" border="0" alt="<?php echo T_('Visible') ?>" />
			</td>
			<td>
			<?php echo T_('Published and is <u>Current</u>') ?> |
			</td>
			<td>
			<img src="images/publish_r.png" width="12" height="12" border="0" alt="<?php echo T_('Finished') ?>" />
			</td>
			<td>
			<?php echo T_('Published, but has <u>Expired</u>') ?> |
			</td>
			<td>
			<img src="images/publish_x.png" width="12" height="12" border="0" alt="<?php echo T_('Finished') ?>" />
			</td>
			<td>
			<?php echo T_('Not Published') ?>
			</td>
		</tr>
		<tr>
			<td colspan="8" align="center">
			<?php echo T_('Click on icon to toggle state.') ?>
			</td>
		</tr>
		</table>
		<?php
	}

	function menuLinksContent( &$menus ) {
		?>
		<script language="javascript" type="text/javascript">
		function go2( pressbutton, menu, id ) {
			var form = document.adminForm;

			if (pressbutton == 'go2menu') {
				form.menu.value = menu;
				submitform( pressbutton );
				return;
			}

			if (pressbutton == 'go2menuitem') {
				form.menu.value 	= menu;
				form.menuid.value 	= id;
				submitform( pressbutton );
				return;
			}
		}
		</script>
		<?php
		foreach( $menus as $menu ) {
			?>
			<tr>
				<td colspan="2">
				<hr />
				</td>
			</tr>
			<tr>
				<td width="90px" valign="top">
				<?php echo T_('Menu') ?>
				</td>
				<td>
				<a href="javascript:go2( 'go2menu', '<?php echo $menu->menutype; ?>' );" title="<?php echo T_('Go to Menu') ?>">
				<?php echo $menu->menutype; ?>
				</a>
				</td>
			</tr>
			<tr>
				<td width="90px" valign="top">
				<?php echo T_('Link Name') ?>
				</td>
				<td>
				<strong>
				<a href="javascript:go2( 'go2menuitem', '<?php echo $menu->menutype; ?>', '<?php echo $menu->id; ?>' );" title="<?php echo T_('Go to Menu Item') ?>">
				<?php echo $menu->name; ?>
				</a>
				</strong>
				</td>
			</tr>
			<tr>
				<td width="90px" valign="top">
				<?php echo T_('State') ?>
				</td>
				<td>
				<?php
				switch ( $menu->published ) {
					case -2:
						echo '<font color="red">'.T_('Trashed').'</font>';
						break;
					case 0:
						echo T_('UnPublished') ;
						break;
					case 1:
					default:
						echo '<font color="green">'.T_('Published').'</font>';
						break;
				}
				?>
				</td>
			</tr>
			<?php
		}
		?>
		<input type="hidden" name="menu" value="" />
		<input type="hidden" name="menuid" value="" />
		<?php
	}

	function menuLinksSecCat( &$menus ) {
		?>
		<script language="javascript" type="text/javascript">
		function go2( pressbutton, menu, id ) {
			var form = document.adminForm;

			if (pressbutton == 'go2menu') {
				form.menu.value = menu;
				submitform( pressbutton );
				return;
			}

			if (pressbutton == 'go2menuitem') {
				form.menu.value 	= menu;
				form.menuid.value 	= id;
				submitform( pressbutton );
				return;
			}
		}
		</script>
		<?php
		foreach( $menus as $menu ) {
			?>
			<tr>
				<td colspan="2">
				<hr/>
				</td>
			</tr>
			<tr>
				<td width="90px" valign="top">
				<?php echo T_('Menu') ?>
				</td>
				<td>
				<a href="javascript:go2( 'go2menu', '<?php echo $menu->menutype; ?>' );" title="<?php echo T_('Go to Menu') ?>">
				<?php echo $menu->menutype; ?>
				</a>
				</td>
			</tr>
			<tr>
				<td width="90px" valign="top">
				<?php echo T_('Type') ?>
				</td>
				<td>
				<?php echo $menu->type; ?>
				</td>
			</tr>
			<tr>
				<td width="90px" valign="top">
				<?php echo T_('Item Name') ?>
				</td>
				<td>
				<strong>
				<a href="javascript:go2( 'go2menuitem', '<?php echo $menu->menutype; ?>', '<?php echo $menu->id; ?>' );" title="<?php echo T_('Go to Menu Item') ?>">
				<?php echo $menu->name; ?>
				</a>
				</strong>
				</td>
			</tr>
			<tr>
				<td width="90px" valign="top">
				<?php echo T_('State') ?>
				</td>
				<td>
				<?php
				switch ( $menu->published ) {
					case -2:
						echo '<font color="red">'.T_('Trashed').'</font>';
						break;
					case 0:
						echo T_('UnPublished');
						break;
					case 1:
					default:
						echo '<font color="green">'.T_('Published').'</font>';
						break;
				}
				?>
				</td>
			</tr>
			<?php
		}
		?>
		<input type="hidden" name="menu" value="" />
		<input type="hidden" name="menuid" value="" />
		<?php
	}

	function checkedOut( &$row, $overlib=1 ) {
		$hover = '';
		if ( $overlib ) {
			$date 				= mosFormatDate( $row->checked_out_time, '%A, %d %B %Y' );
			$time				= mosFormatDate( $row->checked_out_time, '%H:%M' );
			$checked_out_text 	= '<table>';
			$checked_out_text 	.= '<tr><td>'. $row->editor .'</td></tr>';
			$checked_out_text 	.= '<tr><td>'. $date .'</td></tr>';
			$checked_out_text 	.= '<tr><td>'. $time .'</td></tr>';
			$checked_out_text 	.= '</table>';
			$hover = 'onMouseOver="return overlib(\''. $checked_out_text .'\', CAPTION, \''.T_('Checked Out') .'\', BELOW, RIGHT);" onMouseOut="return nd();"';
		}
		$checked	 		= '<img src="images/checked_out.png" '. $hover .'/>';

		return $checked;
	}

	/*
	* Loads all necessary files for JS Overlib tooltips
	*/
	function loadOverlib() {
		?>
		<script language="Javascript" src="<?php echo mamboCore::get('mosConfig_live_site');?>/includes/js/overlib_mini.js"></script>
		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
		<?php
	}


	/*
	* Loads all necessary files for JS Calendar
	*/
	function loadCalendar() {
		$mosConfig_live_site = mamboCore::get('mosConfig_live_site');
		?>
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo $mosConfig_live_site;?>/includes/js/calendar/calendar-mos.css" title="green" />
		<!-- import the calendar script -->
		<script type="text/javascript" src="<?php echo $mosConfig_live_site;?>/includes/js/calendar/calendar.js"></script>
		<!-- import the language module -->
		<script type="text/javascript" src="<?php echo $mosConfig_live_site;?>/includes/js/calendar/lang/calendar-en.js"></script>
		<?php
	}

	function AccessProcessing( &$row, $i ) {
		if ( !$row->access ) {
			$color_access = 'style="color: green;"';
			$task_access = 'accessregistered';
		} else if ( $row->access == 1 ) {
			$color_access = 'style="color: red;"';
			$task_access = 'accessspecial';
		} else {
			$color_access = 'style="color: black;"';
			$task_access = 'accesspublic';
		}

		$href = '
		<a href="javascript: void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $task_access .'\')" '. $color_access .'>
		'. $row->groupname .'
		</a>'
		;

		return $href;
	}

	function CheckedOutProcessing( &$row, $i ) {
		$my = mamboCore::get('currentUser');
		if ( $row->checked_out ) {
			$checked = mosCommonHTML::checkedOut( $row );
		} else {
			$checked = mosHTML::idBox( $i, $row->id, ($row->checked_out && $row->checked_out != $my->id ) );
		}

		return $checked;
	}

	function PublishedProcessing( &$row, $i ) {
		$img 	= $row->published ? 'publish_g.png' : 'publish_x.png';
		$task 	= $row->published ? 'unpublish' : 'publish';
		$alt 	= $row->published ? T_('Published') : T_('Unpublished');
		$action	= $row->published ? T_('Unpublish Item') : T_('Publish item');

		$href = '
		<a href="javascript: void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $task .'\')" title="'. $action .'">
		<img src="images/'. $img .'" border="0" alt="'. $alt .'" />
		</a>'
		;

		return $href;
	}
}

/**
* Tab Creation handler
* @package Mambo
* @author Phil Taylor
*/
class mosTabs {
	/** @var int Use cookies */
	var $useCookies = 0;

	/**
	* Constructor
	* Includes files needed for displaying tabs and sets cookie options
	* @param int useCookies, if set to 1 cookie will hold last used tab between page refreshes
	*/
	function mosTabs($useCookies) {
		$mosConfig_live_site = mamboCore::get('mosConfig_live_site');
		echo "<link id=\"luna-tab-style-sheet\" type=\"text/css\" rel=\"stylesheet\" href=\"" . $mosConfig_live_site. "/includes/js/tabs/tabpane.css\" />";
		echo "<script type=\"text/javascript\" src=\"". $mosConfig_live_site . "/includes/js/tabs/tabpane.js\"></script>";
		$this->useCookies = $useCookies;
	}

	/**
	* creates a tab pane and creates JS obj
	* @param string The Tab Pane Name
	*/
	function startPane($id){
		echo "<div class=\"tab-page\" id=\"".$id."\">";
		echo "<script type=\"text/javascript\">\n";
		echo "   var tabPane1 = new WebFXTabPane( document.getElementById( \"".$id."\" ), ".$this->useCookies." )\n";
		echo "</script>\n";
	}

	/**
	* Ends Tab Pane
	*/
	function endPane() {
		echo "</div>";
	}

	/*
	* Creates a tab with title text and starts that tabs page
	* @param tabText - This is what is displayed on the tab
	* @param paneid - This is the parent pane to build this tab on
	*/
	function startTab( $tabText, $paneid ) {
		echo "<div class=\"tab-page\" id=\"".$paneid."\">";
		echo "<h2 class=\"tab\">".$tabText."</h2>";
		echo "<script type=\"text/javascript\">\n";
		echo "  tabPane1.addTabPage( document.getElementById( \"".$paneid."\" ) );";
		echo "</script>";
	}

	/*
	* Ends a tab page
	*/
	function endTab() {
		echo "</div>";
	}
}

?>
