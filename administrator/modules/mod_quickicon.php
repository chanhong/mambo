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
<table width="100%" class="cpanel">
<?php if($_SESSION['simple_editing'] == 'off'){?>
<tr>	
	<td align="center"  width="25%">
	<a href="index2.php?option=com_content&amp;sectionid=0" style="text-decoration:none" >
<img src="images/addedit.png" width="48" height="48" align="middle" alt="<?php echo T_('All Content Items') ?>" border="0"/>
	<br />
	<?php echo T_('All Content Items') ?>	</a>	</td>
	<td align="center" style="height:100px">
	<a href="index2.php?option=com_typedcontent" style="text-decoration:none">
	<img src="images/addedit.png" width="48" height="48" align="middle" alt="<?php echo T_('Static Content') ?>" border="0"/>
	<br />
	<?php echo T_('Static Content') ?>	</a>	</td>
	<td align="center" width="25%">
	<a href="index2.php?option=com_frontpage" style="text-decoration:none"><img src="images/frontpage.png" width="48" height="48" align="middle" alt="<?php echo T_('Frontpage') ?>" border="0"/><br />
	<?php echo T_('Frontpage') ?>	</a>	</td>
	<br />
	<td align="center" style="height:100px">
	<a href="http://forum.mambo-foundation.org" style="text-decoration:none" target="_blank"><img src="images/help-browser.png" width="48" height="48" align="middle" alt="<?php echo T_('Get Support') ?>" border="0"/><br />
	<?php echo T_('Get Support') ?>	</a>	</td>
	<br />
</tr>
<tr>
	<td align="center" width="25%">
	<a href="index2.php?option=com_sections&amp;scope=content" style="text-decoration:none"><img src="images/sections.png" width="48" height="48" align="middle" alt="<?php echo T_('Sections') ?>" border="0"/><br />
	<?php echo T_('Sections') ?>	</a>	</td>
	<td align="center" style="height:100px">
	<a href="index2.php?option=com_categories&amp;section=content" style="text-decoration:none;">
	<img src="images/categories.png" width="48" height="48" align="middle" alt="<?php echo T_('Categories') ?>" border="0"/>
	<br />
	<?php echo T_('Categories') ?>	</a>	</td>
	<td align="center"  width="25%">
	<a href="index2.php?option=com_media" style="text-decoration:none;">
	<img src="images/mediamanager.png" width="48" height="48" align="middle" alt="<?php echo T_('Media') ?>" border="0"/>
	<br />
	<?php echo T_('Media') ?>	</a>	</td>
	<td align="center" style="height:100px"><a href="index2.php?option=com_languages" style="text-decoration:none;">
	<img src="images/langmanager.png" width="48" height="48" align="middle" alt="<?php echo T_('Languages') ?>" border="0"/>
	<br />
	<?php echo T_('Languages') ?>	</a>		</td>
</tr>

<tr>
	<td align="center" width="25%">
	<a href="index2.php?option=com_trash" style="text-decoration:none;">
	<img src="images/trash.png" width="48" height="48" align="middle" alt="<?php echo T_('Trash') ?>" border="0"/>
	<br />
	<?php echo T_('Trash') ?>	</a>	</td>
	<td align="center" width="25%">
	<?php
	if ( $acl->acl_check( 'administration', 'manage', 'users', $my->usertype, 'components', 'com_menumanager' ) ) {
		?>
		<a href="index2.php?option=com_menumanager" style="text-decoration:none;">
		<img src="images/menu.png" width="48" height="48" align="middle" alt="<?php echo T_('Menus') ?>" border="0"/>
		<br />
		<?php echo T_('Menus') ?>		</a>
		<?php
	}
	?>	</td>
	<td align="center" width="25%">
	<?php
	if ( $acl->acl_check( 'administration', 'manage', 'users', $my->usertype, 'components', 'com_users' ) ) {
		?>
		<a href="index2.php?option=com_users" style="text-decoration:none;">
		<img src="images/user.png" width="48" height="48" align="middle" alt="<?php echo T_('Users') ?>" border="0"/>
		<br />
		<?php echo T_('Users') ?>		</a>
		<?php
	}
	?>	</td>
	<td align="center" width="25%">
	<?php
	if ( $acl->acl_check( 'administration', 'config', 'users', $my->usertype ) ) {
		?>
		<a href="index2.php?option=com_config&amp;hidemainmenu=1" style="text-decoration:none;">
		<img src="images/config.png" width="48" height="48" align="middle" alt="<?php echo T_('Global Configuration') ?>" border="0"/>
		<br />
		<?php echo T_('Global Configuration') ?>		</a>
		<?php
	}
	?>	</td>
</tr>
<?php }else{?>
<!-- if we are simple mode we display these icons -->
<tr>

	<td align="center" width="25%">
	<a href="index2.php?option=com_content&amp;sectionid=0" style="text-decoration:none;">
	<img src="images/addedit.png" width="48" height="48" align="middle" alt="<?php echo T_('All Content Items') ?>" border="0"/>
	<br />	
	<?php echo T_('All Content Items') ?>	</a>	</td>
	
	<td align="center"  width="25%">
	<a href="index2.php?option=com_typedcontent" style="text-decoration:none;">
	<img src="images/addedit.png" width="48" height="48" align="middle" alt="<?php echo T_('Static Content') ?>" border="0"/>
	<br />
	<?php echo T_('Static Content') ?>	</a>	</td>
	
	<td align="center" width="25%">
	<a href="index2.php?option=com_frontpage" style="text-decoration:none;">
	<img src="images/frontpage.png" width="48" height="48" align="middle" alt="<?php echo T_('Frontpage') ?>" border="0"/>
	<br />
	<?php echo T_('Frontpage') ?>	</a>	</td>
	
	<td align="center" width="25%">
	<a href="http://forum.mambo-foundation.org" style="text-decoration:none" target="_blank"><img src="images/help-browser.png" width="48" height="48" align="middle" alt="<?php echo T_('Get Support') ?>" border="0"/><br />
	<?php echo T_('Get Support') ?>	</a>	</td>
	<br />
	
	<td align="center"  width="25%">&nbsp;</td>
</tr>
<?php }?>
</table>