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

class modules_html {

	function module( &$module, &$params, $Itemid, $style=0, $isBuffered=false ) {
		global $mosConfig_live_site, $mosConfig_sitename, $mosConfig_lang;
		$mosConfig_absolute_path = mamboCore::get('mosConfig_absolute_path');
		$m_handler =& mosModuleHandler::getInstance();
		$isBuffered = $m_handler->get('_isBuffered');

		// custom module params
		$rssurl 			= $params->get( 'rssurl' );
		$rssitems 			= $params->get( 'rssitems', 5 );
		$rssdesc 			= $params->get( 'rssdesc', 1 );
		$rssimage 			= $params->get( 'rssimage', 1 );
		$rssitemdesc		= $params->get( 'rssitemdesc', 1 );
		$moduleclass_sfx 	= $params->get( 'moduleclass_sfx' );
		$words 				= $params->def( 'word_count', 0 );

		if ($style == -1 && !$rssurl) {
			if($isBuffered)
				echo $module->buffer;
			else
				echo $module->content;
			return;
		} else {
			?>
			<table cellpadding="0" cellspacing="0" class="moduletable<?php echo $moduleclass_sfx; ?>">
			<?php
			if ( $module->showtitle != 0 ) {
				?>
				<tr>
					<th valign="top">
					<?php echo $module->title; ?>
					</th>
				</tr>
				<?php
			}

			if ( $module->content ) {

				?>
				<tr>
					<td>
					<?php 
	global $mosConfig_absolute_path;
$mosmodulefunc=$mosConfig_absolute_path."/mambots/content/mosmodule/mosmodule_func.php";
if (file_exists($mosmodulefunc)) { include_once($mosmodulefunc); }
					if($isBuffered) {
if (function_exists('mosmodule_bot')) { $module->buffer=mosmodule_bot($module->buffer); }
						echo $module->buffer;
					} else {
if (function_exists('mosmodule_bot')) { $module->content=mosmodule_bot($module->content); }
						echo $module->content;
					}
					?>
					</td>
				</tr>
				<?php
			}
		}
		// feed output
		if ( $rssurl ) {
			if (!defined('MAGPIE_CACHE_DIR')) define ('MAGPIE_CACHE_DIR', mamboCore::get('mosConfig_absolute_path').'/includes/magpie_cache');
			require_once (mamboCore::get('mosConfig_absolute_path').'/includes/magpierss/rss_fetch.php');
			$rss = fetch_rss($rssurl);
			if (isset($rss->image['title'])) $iTitle = $rss->image['title'];
			if (isset($rss->image['url'])) $iUrl = $rss->image['url'];
			// feed title
			?>
			<tr>
				<td>
				<strong>
				<a href="<?php echo $rss->channel['link']; ?>" target="_blank">
				<?php echo $rss->channel['title']; ?>
				</a>
				</strong>
				</td>
			</tr>
			<?php
			// feed description
			if ( $rssdesc ) {
				?>
				<tr>
					<td>
					<?php echo $rss->channel['description']; ?>
					</td>
				</tr>
				<?php
			}
			// feed image
			if ( $rssimage AND isset($iUrl) ) {
				?>
				<tr>
					<td align="center">
					<image src="<?php echo $iUrl; ?>" alt="<?php echo $iTitle; ?>"/>
					</td>
				</tr>
				<?php
			}
			$itemnumber = 1;
			?>
			<tr>
				<td>
				<ul class="newsfeed<?php echo $moduleclass_sfx; ?>">
			<?php
			foreach ($rss->items as $item) {
				if ($itemnumber > $rssitems) break;
				$itemnumber++;
				// item title
				?>
				<li class="newsfeed<?php echo $moduleclass_sfx; ?>">
				<strong>
				<a href="<?php echo $item['link']; ?>" target="_blank">
				<?php echo $item['title']; ?>
				</a>
				</strong>
				<?php
				// item description
				if ( $rssitemdesc ) {
					// item description
					$text = html_entity_decode( $item['description'] );
						// word limit check
					if ( $words ) {
						$texts = explode( ' ', $text );
						$count = count( $texts );
						if ( $count > $words ) {
							$text = '';
							for( $i=0; $i < $words; $i++ ) {
								$text .= ' '. $texts[$i];
							}
							$text .= '...';
						}
					}
					?>
					<div>
					<?php echo $text; ?>
					</div>
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
			<?php
		}
		?>
		</table>
		<?php
	}

	/**
	* @param object
	* @param object
	* @param int The menu item ID
	* @param int -1=show without wrapper and title, -2=x-mambo style
	*/
	function module2( &$module, &$params, $Itemid, $style=0, $count=0, $isBuffered=false) {
		global $mosConfig_live_site, $mosConfig_sitename, $mosConfig_lang;
		global $mainframe, $database, $my;
		$mosConfig_absolute_path = mamboCore::get('mosConfig_absolute_path');
		$m_handler =& mosModuleHandler::getInstance();
		$isBuffered = $m_handler->get('_isBuffered');

		$moduleclass_sfx 		= $params->get( 'moduleclass_sfx' );
		$number = '';
		if ($count > 0) $number = '<span>' . $count . '</span> ';
		
		if ($style == -3) {
			// allows for rounded corners
			echo "\n<div class=\"module$moduleclass_sfx\"><div><div><div>";
			if ($module->showtitle != 0) echo "<h3>$module->title</h3>\n";
			if ($isBuffered)
				echo $module->buffer;
			else
				include( $mosConfig_absolute_path .'/modules/'. $module->module .'.php' );
			if (isset( $content)) echo $content;
			echo "\n\n</div></div></div></div>\n";
			
		} else if ($style == -2) {
			// headder and content encapsulated with div tag
			?>
			<div class="moduletable<?php echo $moduleclass_sfx; ?>">
			<?php
			if ($module->showtitle != 0) {
				?>
				<h1><?php echo $module->title; ?></h1>
				<?php
			}
			if ($isBuffered)
				echo $module->buffer;
			else
				include( $mosConfig_absolute_path .'/modules/'. $module->module .'.php' );
			if (isset( $content)) echo $content;
			?>
			</div>
			<?php
			
		} else if ($style == -1) {
			// show a naked module - no wrapper and no title
			if ($isBuffered)
				if (isset( $module->buffer)) echo $module->buffer;
				else 
					include( $mosConfig_absolute_path .'/modules/'. $module->module .'.php' );
			else
				include( $mosConfig_absolute_path .'/modules/'. $module->module .'.php' );
			if (isset( $content)) echo $content;
		} else {
			?>
			<table cellpadding="0" cellspacing="0" class="moduletable<?php echo $moduleclass_sfx; ?>">
			<?php
			if ( $module->showtitle != 0 ) {
				?>
				<tr>
					<th valign="top">
					<?php echo $module->title; ?>
					</th>
				</tr>
				<?php
			}
			?>
			<tr>
				<td>
				<?php
			if ($isBuffered)
				echo $module->buffer;
			else
				include( $mosConfig_absolute_path .'/modules/'. $module->module .'.php' );
				if (isset( $content)) echo $content;
				?>
				</td>
			</tr>
			</table>
			<?php
		}
	}
}
?>
