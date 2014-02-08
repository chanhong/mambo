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
?>
<script type="text/javascript"> 
<!--
/** Wrapper around the editor specific update function in JavaScript
*/
function updateEditorContents( editorName, newValue ) {
	//TODO: correct call
}
//-->
</script>
<?php

function initEditor() {
}

function editorArea( $name, $content, $hiddenField, $width, $height, $col, $row ) {
?>
<object classid="clsid:0EED7206-1661-11D7-84A3-00606744831D" id="<?php echo $name; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>">
<param name="Value" value="<?php echo $content; ?>" />
</object>
<input type="hidden" name="<?php echo $hiddenField; ?>" id="<?php echo $hiddenField; ?>" value="" />
<?php
}

function getEditorContents( $editorArea, $hiddenfield ) {
?>
	document.getElementById('<?php echo $editorArea ; ?>').EscapeUNICODE = true;
	document.getElementById('<?php echo $hiddenfield ; ?>').value = document.getElementById('<?php echo $editorArea ; ?>').value;
<?php
}
?>