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
<table class="adminform">
<tr>
	<td width="50%" valign="top">
	<?php mosLoadAdminModules( 'icon', 0 ); ?>
	</td>
	<td width="50%" valign="top">
	<div style="width=100%;">
	<form action="index2.php" method="post" name="adminForm">
	<?php mosLoadAdminModules( 'cpanel', 1 ); ?>
	</form>
	</div>
	</td>
</tr>
</table>