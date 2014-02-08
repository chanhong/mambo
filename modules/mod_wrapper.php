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

global $_CONFIG, $_LANG;

$params->def( 'url', 'http://www.mambo-foundation.org' );
$params->def( 'scrolling', 'auto' );
$params->def( 'height', '200' );
$params->def( 'height_auto', '0' );
$params->def( 'width', '100%' );
$params->def( 'add', '1' );

$url = $params->get( 'url' );
if ( $params->get( 'add' ) ) {
	// adds "http://" if none is set
	if ( !strstr( $url, 'http' ) && !strstr( $url, 'https' ) ) {
		$url = 'http://'. $url;
	}
}

// auto height control
if ( $params->get( 'height_auto' ) ) {
	$load = "window.onload = iFrameHeight;\n";
} else {
	$load = '';
}

?>
<script language="javascript" type="text/javascript">
<?php echo $load; ?>
function iFrameHeight() {
	var h = 0;
	if ( !document.all ) {
		h = document.getElementById('blockrandom').contentDocument.height;
		document.getElementById('blockrandom').style.height = h + 60 + 'px';
	} else if( document.all ) {
		h = document.frames('blockrandom').document.body.scrollHeight;
		document.all.blockrandom.style.height = h + 20 + 'px';
	}
}
</script>
<iframe   
id="blockrandom"
src="<?php echo $url; ?>" 
width="<?php echo $params->get( 'width' ); ?>" 
height="<?php echo $params->get( 'height' ); ?>" 
scrolling="<?php echo $params->get( 'scrolling' ); ?>" 
align="top"
frameborder="0"
class="wrapper<?php echo $params->get( 'pageclass_sfx' ); ?>">
</iframe>
