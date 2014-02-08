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

$menuhandler = mosMenuHandler::getInstance();
$menutypes = $menuhandler->getMenuTypes();

?>
<table class="adminlist">
	<tr>
		<th class="title">
		<?php echo T_('Menu') ?>
		</th>
		<th class="title">
		<?php echo T_('# Items') ?>
		</th>
	</tr>
<?php
foreach ($menutypes as $type=>$count) {
	$link = 'index2.php?option=com_menus&amp;menutype='. $type;
	?>
	<tr>
		<td>
		<a href="<?php echo $link; ?>">
		<?php echo $type;?>
		</a>
		</td>
		<td>
		<?php echo $count;?>
		</td>
	</tr>
<?php
}
?>
</table>
