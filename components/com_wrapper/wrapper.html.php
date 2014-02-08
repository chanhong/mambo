<?php
/**
* @package Mambo
* @subpackage Wrapper
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

class HTML_wrapper {

	function displayWrap( &$row, &$params, &$menu ) {
		?>
		<script language="javascript" type="text/javascript">
		<?php echo $row->load ."\n"; ?>
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
		<div class="contentpane<?php echo $params->get( 'pageclass_sfx' ); ?>">

		<?php
		if ( $params->get( 'page_title' ) ) {
			?>
			<div class="componentheading<?php echo $params->get( 'pageclass_sfx' ); ?>">
			<?php echo $params->get( 'header' ); ?>
			</div>
			<?php
		}
		?>
		<iframe   
		id="blockrandom"
		src="<?php echo $row->url; ?>" 
		width="<?php echo $params->get( 'width' ); ?>" 
		height="<?php echo $params->get( 'height' ); ?>" 
		scrolling="<?php echo $params->get( 'scrolling' ); ?>" 
		align="top"
		frameborder="0"
		class="wrapper<?php echo $params->get( 'pageclass_sfx' ); ?>">
		<?php echo T_('This option will not work correctly.  Unfortunately, your browser does not support Inline Frames'); ?>
		</iframe>

		</div>
		<?php
		// displays back button
		mosHTML::BackButton ( $params );
	}

}
?>