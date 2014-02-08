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

global $mosConfig_offset, $mosConfig_caching, $mosConfig_enable_stats;
global $mosConfig_gzip;
$serverinfo = $params->get( 'serverinfo' );
$siteinfo = $params->get( 'siteinfo' );
$moduleclass_sfx = $params->get( 'moduleclass_sfx' );

$content = "";

if ($serverinfo) {
	echo "<strong>OS:</strong> "  . substr(php_uname(),0,7) . "<br />\n";
	echo "<strong>PHP:</strong> " .phpversion() . "<br />\n";
	echo "<strong>MySQL:</strong> " .mysql_get_server_info() . "<br />\n";
	echo "<strong>".T_('Time').": </strong> " .date("H:i",time()+($mosConfig_offset*60*60)) . "<br />\n";
	$c = $mosConfig_caching ? T_('Enabled'):T_('Disabled');
	echo "<strong>Caching:</strong> " . $c . "<br />\n";
	$z = $mosConfig_gzip ? T_('Enabled'):T_('Disabled');
	echo "<strong>GZIP:</strong> " . $z . "<br />\n";
}

if ($siteinfo) {
	$query="SELECT count(id) AS count_users FROM #__users";
	$database->setQuery($query);
	echo "<strong>".T_('Members').":</strong> " .$database->loadResult() . "<br />\n";

	$query="SELECT count(id) as count_items from #__content";
	$database->setQuery($query);
	echo "<strong>".T_('News').":</strong> ".$database->loadResult() . "<br />\n";

	$query="SELECT count(id) as count_links FROM #__weblinks WHERE published='1'";
	$database->setQuery($query);
	echo "<strong>".T_('Web Links').":</strong> ".$database->loadResult() . "<br />\n";
}

if ($mosConfig_enable_stats) {
	$counter = $params->get( 'counter' );
	$increase = $params->get( 'increase' );
	if ($counter) {
		$query = "SELECT sum(hits) AS count FROM #__stats_agents WHERE type='1'";
		$database->setQuery( $query );
		$hits = $database->loadResult();

		$hits = $hits + $increase;

		if ($hits == NULL) {
			$content .= "<strong>" . T_('Visitors') . ":</strong> 0\n";
		} else {
			$content .= "<strong>" .  T_('Visitors') . ":</strong> " . $hits . "\n";
		}
	}
}
?>
