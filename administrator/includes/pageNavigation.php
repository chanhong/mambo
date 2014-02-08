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

/**
* Page navigation support class
*/
class mosPageNav {
	/** @var int The record number to start dislpaying from */
	var $limitstart = null;
	/** @var int Number of rows to display per page */
	var $limit = null;
	/** @var int Total number of rows */
	var $total = null;

	function mosPageNav( $total, $limitstart, $limit ) {
		$this->total = intval( $total );
		$this->limitstart = max( intval($limitstart), 0 );
        $this->limit = max( intval($limit), 1 );
		if ($this->limit > $this->total) {
			$this->limitstart = 0;
		}
		if (($this->limit-1)*$this->limitstart > $this->total) {
			$this->limitstart -= $this->limitstart % $this->limit;
		}
	}
	/**
	* @return string The html for the limit # input box
	*/
	function getLimitBox () {
		$limits = array();
		for ($i=5; $i <= 30; $i+=5) {
			$limits[] = mosHTML::makeOption( "$i" );
		}
		$limits[] = mosHTML::makeOption( "50" );

		// build the html select list
		$html = mosHTML::selectList( $limits, 'limit', 'class="inputbox" size="1" onchange="document.adminForm.submit();"',
		'value', 'text', $this->limit );
		$html .= "\n<input type=\"hidden\" name=\"limitstart\" value=\"$this->limitstart\" />";
		return $html;
	}
	/**
	* Writes the html limit # input box
	*/
	function writeLimitBox () {
		echo mosPageNav::getLimitBox();
	}
	function writePagesCounter() {
		echo $this->getPagesCounter();
	}
	/**
	* @return string The html for the pages counter, eg, Results 1-10 of x
	*/
	function getPagesCounter() {
	    $html = '';
		$from_result = $this->limitstart+1;
		if ($this->limitstart + $this->limit < $this->total) {
			$to_result = $this->limitstart + $this->limit;
		} else {
			$to_result = $this->total;
		}
		if ($this->total > 0) {
			$html .= sprintf(T_("Results %d to %d of %d"), $from_result, $to_result, $this->total);
		} else {
			$html .= T_('No records found.');
		}
		return $html;
	}
	/**
	* Writes the html for the pages counter, eg, Results 1-10 of x
	*/
	function writePagesLinks() {
	    echo $this->getPagesLinks();
	}
	/**
	* @return string The html links for pages, eg, previous, next, 1 2 3 ... x
	*/
	function getPagesLinks() {
	    $html = '';
		$displayed_pages = 10;
		$total_pages = ceil( $this->total / $this->limit );
		$this_page = ceil( ($this->limitstart+1) / $this->limit );
		$start_loop = (floor(($this_page-1)/$displayed_pages))*$displayed_pages+1;
		if ($start_loop + $displayed_pages - 1 < $total_pages) {
			$stop_loop = $start_loop + $displayed_pages - 1;
		} else {
			$stop_loop = $total_pages;
		}

		if ($this_page > 1) {
			$page = ($this_page - 2) * $this->limit;
			$html .= "\n<a href=\"#beg\" class=\"pagenav\" title=\"".T_('first page')."\" onclick=\"javascript: document.adminForm.limitstart.value=0; document.adminForm.submit();return false;\"><< ".T_('Start')."</a>";
			$html .= "\n<a href=\"#prev\" class=\"pagenav\" title=\"".T_('previous page')."\" onclick=\"javascript: document.adminForm.limitstart.value=$page; document.adminForm.submit();return false;\">< ".T_('Previous')."</a>";
		} else {
			$html .= "\n<span class=\"pagenav\"><< ".T_('Start')."</span>";
			$html .= "\n<span class=\"pagenav\">< ".T_('Previous')."</span>";
		}

		for ($i=$start_loop; $i <= $stop_loop; $i++) {
			$page = ($i - 1) * $this->limit;
			if ($i == $this_page) {
				$html .= "\n<span class=\"pagenav\"> $i </span>";
			} else {
				$html .= "\n<a href=\"#$i\" class=\"pagenav\" onclick=\"javascript: document.adminForm.limitstart.value=$page; document.adminForm.submit();return false;\"><strong>$i</strong></a>";
			}
		}

		if ($this_page < $total_pages) {
			$page = $this_page * $this->limit;
			$end_page = ($total_pages-1) * $this->limit;
			$html .= "\n<a href=\"#next\" class=\"pagenav\" title=\"".T_('next page')."\" onclick=\"javascript: document.adminForm.limitstart.value=$page; document.adminForm.submit();return false;\"> ".T_('Next')." ></a>";
			$html .= "\n<a href=\"#end\" class=\"pagenav\" title=\"".T_('end page')."\" onclick=\"javascript: document.adminForm.limitstart.value=$end_page; document.adminForm.submit();return false;\"> ".T_('End')." >></a>";
		} else {
			$html .= "\n<span class=\"pagenav\">".T_('Next')." ></span>";
			$html .= "\n<span class=\"pagenav\">".T_('End')." >></span>";
		}
		return $html;
	}
	
	function getListFooter() {
	    $html = '<table class="adminlist"><tr><th colspan="3">';
		$html .= $this->getPagesLinks();
		$html .= '</th></tr><tr>';
		$html .= '<td nowrap="true" width="48%" align="right">'.T_('Display #').'</td>';
		$html .= '<td>' .$this->getLimitBox() . '</td>';
		$html .= '<td nowrap="true" width="48%" align="left">' . $this->getPagesCounter() . '</td>';
		$html .= '</tr></table>';
  		return $html;
	}
/**
* @param int The row index
* @return int
*/
	function rowNumber( $i ) {
		return $i + 1 + $this->limitstart;
	}
/**
* @param int The row index
* @param string The task to fire
* @param string The alt text for the icon
* @return string
*/
	function orderUpIcon( $i, $condition=true, $task='orderup' ) {
		if (($i > 0 || ($i+$this->limitstart > 0)) && $condition) {
		$alt = T_('Move Up');
		    return '<a href="#reorder" onClick="return listItemTask(\'cb'.$i.'\',\''.$task.'\')" title="'.$alt.'">
				<img src="images/uparrow.png" width="12" height="12" border="0" alt="'.$alt.'">
			</a>';
  		} else {
  		    return '&nbsp;';
		}
	}
/**
* @param int The row index
* @param int The number of items in the list
* @param string The task to fire
* @param string The alt text for the icon
* @return string
*/
	function orderDownIcon( $i, $n, $condition=true, $task='orderdown' ) {
		if (($i < $n-1 || $i+$this->limitstart < $this->total-1) && $condition) {
		$alt = T_('Move Down');
			return '<a href="#reorder" onClick="return listItemTask(\'cb'.$i.'\',\''.$task.'\')" title="'.$alt.'">
				<img src="images/downarrow.png" width="12" height="12" border="0" alt="'.$alt.'">
			</a>';
  		} else {
  		    return '&nbsp;';
		}
	}
}
?>