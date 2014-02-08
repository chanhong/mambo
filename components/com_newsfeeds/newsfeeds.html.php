<?php
/**
* @package Mambo
* @subpackage Newsfeeds
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

class HTML_newsfeed {

	function displaylist( &$categories, &$rows, $catid, $currentcat=NULL, &$params, $tabclass ) {
		global $Itemid, $mosConfig_live_site, $hide_js;
		if ( $params->get( 'page_title' ) ) {
			?>
			<div class="componentheading<?php echo $params->get( 'pageclass_sfx' ); ?>">
			<?php echo $currentcat->header; ?>
			</div>
			<?php
		}
		?>
		<form action="index.php" method="post" name="adminForm">

		<table width="100%" cellpadding="4" cellspacing="0" border="0" align="center" class="contentpane<?php echo $params->get( 'pageclass_sfx' ); ?>">
		<tr>
			<td width="60%" valign="top" class="contentdescription<?php echo $params->get( 'pageclass_sfx' ); ?>" colspan="2">
			<?php 
			// show image
			if ( $currentcat->img ) {
				?>
				<img src="<?php echo $currentcat->img; ?>" align="<?php echo $currentcat->align; ?>" hspace="6" alt="<?php echo T_('Web Links'); ?>" />
				<?php 
			}
			echo $currentcat->descrip;
			?>
			</td>
		</tr>
		<tr>
			<td>
			<?php
			if ( count( $rows ) ) {
				HTML_newsfeed::showTable( $params, $rows, $catid, $tabclass );
			}
			?>
			</td>
		</tr>
		<tr>	
			<td>&nbsp;
						
			</td>
		</tr>
		<tr>
			<td>
			<?php
			// Displays listing of Categories
			if ( ( $params->get( 'type' ) == 'category' ) && $params->get( 'other_cat' ) ) {
				HTML_newsfeed::showCategories( $params, $categories, $catid );
			} else if ( ( $params->get( 'type' ) == 'section' ) && $params->get( 'other_cat_section' ) ) {
				HTML_newsfeed::showCategories( $params, $categories, $catid );
			}
			?>
			</td>
		</tr>
		</table>
		</form>
		<?php
		// displays back button
		mosHTML::BackButton ( $params, $hide_js );
	}

	/**
	* Display Table of items
	*/
	function showTable( &$params, &$rows, $catid, $tabclass ) {
		global $mosConfig_live_site, $Itemid;
		// icon in table display
		$mainframe =& mosMainFrame::getInstance();
		$img = $mainframe->ImageCheck( 'con_info.png', '/images/M_images/', $params->get( 'icon' ) );
		?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
		<?php
		if ( $params->get( 'headings' ) ) {
			?>
			<tr>
				<?php 
				if ( $params->get( 'name' ) ) {
					?>
					<td height="20" class="sectiontableheader<?php echo $params->get( 'pageclass_sfx' ); ?>">
					<?php echo T_('Feed Name'); ?>
					</td>
					<?php 
				}
				?>
				<?php 
				if ( $params->get( 'articles' ) ) {
					?>
					<td height="20" class="sectiontableheader<?php echo $params->get( 'pageclass_sfx' ); ?>" align="center">
					<?php echo T_('# Articles'); ?>
					</td>
					<?php 
				}
				?>
				<?php 
				if ( $params->get( 'link' ) ) {
					?>
					<td height="20" class="sectiontableheader<?php echo $params->get( 'pageclass_sfx' ); ?>">
					<?php echo T_('Feed Link'); ?>
					</td>
					<?php 
				}
				?>
				<td width="100%" class="sectiontableheader<?php echo $params->get( 'pageclass_sfx' ); ?>"></td>
			</tr>
			<?php 
		} 

		$k = 0;
		foreach ($rows as $row) {
			$link = 'index.php?option=com_newsfeeds&amp;task=view&amp;feedid='. $row->id .'&amp;Itemid='. $Itemid;
			?>
			<tr>
				<?php 
				if ( $params->get( 'name' ) ) {
					?>
					<td width="30%" height="20" class="<?php echo $tabclass[$k]; ?>"> 
					<a href="<?php echo sefRelToAbs( $link ); ?>" class="category<?php echo $params->get( 'pageclass_sfx' ); ?>">
					<?php echo $row->name; ?> 
					</a> 
					</td>
					<?php 
				} 
				?>
				<?php 
				if ( $params->get( 'articles' ) ) {
					?>
					<td width="20%" class="<?php echo $tabclass[$k]; ?>" align="center">
					<?php echo $row->numarticles; ?>
					</td>
					<?php 
				} 
				?>
				<?php 
				if ( $params->get( 'link' ) ) {
					?>
					<td width="50%" class="<?php echo $tabclass[$k]; ?>">
					<?php echo $row->link; ?>
					</td>
					<?php 
				} 
				?>
				<td width="100%"></td>
			</tr>
			<?php	
			$k = 1 - $k;
		} 
		?>
		</table>
		<?php 
	}

	/**
	* Display links to categories
	*/
	function showCategories( &$params, &$categories, $catid ) {
		global $mosConfig_live_site, $Itemid;
		?>
		<ul>
		<?php
		foreach ( $categories as $cat ) {
			if ( $catid == $cat->catid ) {
				?>	
				<li>
					<strong>
					<?php echo $cat->title;?>
					</strong>
					&nbsp;
					<span class="small">
					(<?php echo $cat->numlinks;?>)
					</span>
				</li>
				<?php		
			} else {
				$link = 'index.php?option=com_newsfeeds&amp;catid='. $cat->catid .'&amp;Itemid='. $Itemid;
				?>	
				<li>
					<a href="<?php echo sefRelToAbs( $link ); ?>" class="category<?php echo $params->get( 'pageclass_sfx' ); ?>">
					<?php echo $cat->title;?> 
					</a>
					<?php
					if ( $params->get( 'cat_items' ) ) {
						?>
						&nbsp;
						<span class="small">
						(<?php echo $cat->numlinks;?>)
						</span>
						<?php
					}
					?>
					<?php
					// Writes Category Description
					if ( $params->get( 'cat_description' ) ) {
						echo '<br />';
						echo $cat->description;
					}
					?>
				</li>
				<?php		
			}
		}
		?>
		</ul>
		<?php
	}


	function showNewsfeeds( &$newsfeeds, &$params ) {
		global $mosConfig_live_site, $mosConfig_absolute_path;
		?>
		<table width="100%" class="contentpane<?php echo $params->get( 'pageclass_sfx' ); ?>">	
		<?php 
		if ( $params->get( 'header' ) ) {
			?>
			<tr>
				<td class="componentheading<?php echo $params->get( 'pageclass_sfx' ); ?>" colspan="2">
				<?php echo $params->get( 'header' ); ?>
				</td>
			</tr>
			<?php
		}

		foreach ( $newsfeeds as $newsfeed ) {
			if (!defined('MAGPIE_CACHE_DIR')) define ('MAGPIE_CACHE_DIR', mamboCore::get('mosConfig_absolute_path').'/includes/magpie_cache');
			require_once (mamboCore::get('mosConfig_absolute_path').'/includes/magpierss/rss_fetch.php');
			$rss = fetch_rss($newsfeed->link);
			if (!is_object($rss)) {
				echo '<tr><td><span>RSS feed failed</span></td></tr>';
				break;
			}
			if (isset($rss->image['title'])) $iTitle = $rss->image['title'];
			if (isset($rss->image['url'])) $iUrl = $rss->image['url'];
				?>
				<tr>
					<td class="contentheading<?php echo $params->get( 'pageclass_sfx' ); ?>">
					<a href="<?php echo $rss->channel['link']; ?>" target="_child">
					<?php echo $rss->channel['title']; ?>
					</a>
					</td>
				</tr>
				<?php 
				// feed description
				if ( $params->get( 'feed_descr' ) ) {
					?>
					<tr>
						<td>
						<?php if (isset($rss->channel['description'])) echo $rss->channel['description']; ?>
						<br /><br />
						</td>
					</tr>
					<?php
				}
				// feed image
				if ( isset($iUrl) && $params->get( 'feed_image' ) ) {
					?>
					<tr>
						<td>
						<img src="<?php echo $iUrl; ?>" alt="<?php echo $iTitle; ?>" />
						</td>
					</tr>
					<?php
				}
				?>
				<tr>
					<td>
					<ul>
					<?php
					$itemnumber = 1;
			foreach ($rss->items as $item) {
				if ($itemnumber > $newsfeed->numarticles) break;
				$itemnumber++;
						?>
							<li>
							<a href="<?php echo $item['link']; ?>" target="_child">
							<?php echo $item['title']; ?>
							</a> 
							<?php 
							// item description
							if ( $params->get( 'item_descr' ) ) {
								$text 	= html_entity_decode( $item['description'] );
								$num 	= $params->get( 'word_count' );
								
								// word limit check
								if ( $num ) {
									$texts = explode( ' ', $text );
									$count = count( $texts );
									if ( $count > $num ) {
										$text = '';
										for( $i=0; $i < $num; $i++ ) {
											$text .= ' '. $texts[$i];
										}
										$text .= '...';
									}
								}
								?>
								<br />
								<?php echo $text; ?>						
								<br /><br />
								<?php
							}
							?>
							</li>
						<?php
					}
					?>
					</ul>
					</td>
				</tr>
				<tr>
					<td>
					<br />
					</td>
				</tr>
				<?php
		}
		?>
		</table>
		<?php
		// displays back button
		mosHTML::BackButton ( $params );
	}

}
?>
