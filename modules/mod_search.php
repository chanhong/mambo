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

$button			= $params->get( 'button', '' );
$button_pos		= $params->get( 'button_pos', 'left' );
$button_text	= $params->get( 'button_text', T_('Search') );
$width 			= intval( $params->get( 'width', 20 ) );
$text 			= $params->get( 'text', T_('search...') );
$moduleclass_sfx 	= $params->get( 'moduleclass_sfx' );

$output = '<input alt="'.T_('search').'" class="inputbox'. $moduleclass_sfx .'" type="text" name="searchword" size="'. $width .'" value="'. $text .'"  onblur="if(this.value==\'\') this.value=\''. $text .'\';" onfocus="if(this.value==\''. $text .'\') this.value=\'\';" />';

if ( $button ) {
	$button = '<input type="submit" value="'. $button_text .'" class="button'. $moduleclass_sfx .'"/>';
}

switch ( $button_pos ) {
	case 'top':
		$button = $button .'<br />';
		$output = $button . $output;
		break;
		
	case 'bottom':
		$button =  '<br />'. $button;
		$output = $output . $button;
		break;
		
	case 'right':
		$output = $output . $button;
		break;

	case 'left':
	default:
		$output = $button . $output;
		break;
}
?>

<form action="<?php echo sefRelToAbs('index.php'); ?>" method="post">

<div align="left" class="search<?php echo $moduleclass_sfx; ?>">	
<?php echo $output; ?>
<input type="hidden" name="option" value="search" />
</div>
</form>
