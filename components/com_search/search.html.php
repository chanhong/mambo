<?php
/**
* @package Mambo
* @subpackage Search
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

class search_html {
	
	function openhtml( $params ) {
		if ( $params->get( 'page_title' ) ) {
			?>
			<div class="componentheading<?php echo $params->get( 'pageclass_sfx' ); ?>">
			<?php echo $params->get( 'header' ); ?>
			</div> 
			<?php 
		}
	}

	function searchbox( $searchword, &$lists, $params ) {
		global $Itemid;
		?>
		<form action="index.php" method="post">
		<table class="contentpaneopen<?php echo $params->get( 'pageclass_sfx' ); ?>">
			<tr align="center">
			  <td colspan="3"><?php echo T_('Search Keyword'); ?>:&nbsp;
		        <input type="text" name="searchword"size="30" value="<?php echo stripslashes($searchword);?>" class="inputbox" />
		        &nbsp;<input type="submit" name="submit" value="<?php echo T_('Search');?>" class="button" /></td>
		  </tr>
			<tr align="center">
				<td colspan="3">
				<?php echo $lists['searchphrase']; ?>
				</td>
			</tr>
			<tr align="center">
				<td colspan="3"><?php echo T_('Ordering');?>: <?php echo $lists['ordering'];?></td>
			</tr>
		</table>
		
		<input type="hidden" name="option" value="com_search" />
		<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
		</form>
		<?php
	}

	function searchintro( $searchword, $params ) {
		?>
		<table class="searchintro<?php echo $params->get( 'pageclass_sfx' ); ?>">
		<tr>
			<td colspan="3" align="left">
			<?php echo T_('Search Keyword') . ' <strong>' . stripslashes($searchword) . '</strong>'; ?>	
		<?php
	}

	function message( $message, $params ) {
		?>
		<table class="searchintro<?php echo $params->get( 'pageclass_sfx' ); ?>">
		<tr>
			<td colspan="3" align="left">
			<?php eval ('echo "'.$message.'";');	?>
			</td>
		</tr>
		</table>
		<?php
	}

	function displaynoresult() {
		?>
			</td>
		</tr>
		<?php
	}

	function display( &$rows, $params ) {
		global $mosConfig_offset;
		
		$c 			= count ($rows);
		$tabclass 	= array("sectiontableentry1", "sectiontableentry2");
		$k 			= 0;
				
		// number of matches found
		printf( Tn_('returned %d match', 'returned %d matches',$c), $c );
		?>
			</td>
		</tr>
		</table>
		<br />
		<table class="contentpaneopen<?php echo $params->get( 'pageclass_sfx' ); ?>">
		<?php
		foreach ($rows as $row) {
			if ($row->created) {
				$created = mosFormatDate ($row->created, '%d %B, %Y');
			} else {
				$created = '';
			}
			?>
			<tr class="<?php echo $tabclass[$k] . $params->get( 'pageclass_sfx' ); ?>">
				<td>
				<?php
				if ($row->browsernav == 1) {
					?>
					<a href="<?php echo sefRelToAbs($row->href); ?>" target="_blank">
					<?php
				} else {
					?>
					<a href="<?php echo sefRelToAbs($row->href); ?>">
					<?php
				}
				echo $row->title;
				?>
				</a>
				<span class="small<?php echo $params->get( 'pageclass_sfx' ); ?>">
				(<?php echo $row->section; ?>)
				</span>
				</td>
			</tr>
			<tr class="<?php echo $tabclass[$k] . $params->get( 'pageclass_sfx' ); ?>">
				<td>
				<?php echo $row->text;?> &#133;
				</td>
			</tr>
			<tr>
				<td class="small<?php echo $params->get( 'pageclass_sfx' ); ?>">
				<?php echo $created; ?>
				</td>
			</tr>
			<tr>
				<td>&nbsp;
				
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
	}

	function conclusion( $totalRows, $searchword ) {
		global $mosConfig_live_site;
		?>
		<tr>
			<td colspan="3">&nbsp;
			
			</td>
		</tr>
		<tr>
			<td colspan="3">
			<?php
			printf('Total %d results found.  Search for %s with', $totalRows, "<strong>$searchword</strong>");
			?>
			<a href="http://www.google.com/search?q=<?php echo stripslashes($searchword);?>" target="_blank">
			<img src="<?php echo $mosConfig_live_site;?>/images/M_images/google.png" border="0" align="texttop" />
			</a>
			</td>
		</tr>
		</table>	
		<?php
	}
}
?>