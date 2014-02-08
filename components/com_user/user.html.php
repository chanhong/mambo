<?php
/**
* @package Mambo
* @subpackage Users
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

class HTML_user {
	function frontpage() {
?>
<div class="componentheading">
	<?php echo T_('Welcome!'); ?>
</div>

	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td><?php echo T_('Welcome to the user section of our site'); ?></td>
		</tr>
	</table>
<?php
	}

	function userEdit($row, $option,$submitvalue)
	{
?>
	<script language="javascript" type="text/javascript">
		function submitbutton() {
			var form = document.mosUserForm;
			var r = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]", "i");

			// do field validation
			if (form.name.value == "") {
				alert( "<?php echo T_('Please enter your name.');?>" );
			} else if (form.username.value == "") {
				alert( "<?php echo T_('Please enter a user name.');?>" );
			} else if (r.exec(form.username.value) || form.username.value.length < 3) {
				alert( "<?php printf( T_("Please enter a valid %s.  No spaces, more than %d characters and containing only the characters 0-9,a-z, or A-Z"), T_('Username:'), 4 );?>" );
			} else if (form.email.value == "") {
				alert( "<?php echo T_('Please enter a valid e-mail address.');?>" );
			} else if ((form.password.value != "") && (form.password.value != form.verifyPass.value)){
				alert( "<?php echo T_('Password and verification do not match, please try again.');?>" );
			} else if (r.exec(form.password.value)) {
				alert( "<?php printf( T_("Please enter a valid %s.  No spaces, more than %d characters and containing only the characters 0-9,a-z, or A-Z"), T_('Password:'), 4 );?>" );
			} else {
				form.submit();
			}
		}
	</script>
<form action="index.php" method="post" name="mosUserForm">
		<div class="componentheading">
			<?php echo T_('Edit Your Details'); ?>
		</div>
		<table cellpadding="5" cellspacing="0" border="0" width="100%">
    <tr>
      <td width=85><?php echo T_('Your Name:'); ?></td>
      <td><input class="inputbox" type="text" name="name" value="<?php echo $row->name;?>" size="40" /></td>
    </tr>
    <tr>
      <td><?php echo T_('e-mail:'); ?></td>
      <td><input class="inputbox" type="text" name="email" value="<?php echo $row->email;?>" size="40" /></td>
    <tr>
      <td><?php echo T_('User Name:'); ?></td>
      <td><input class="inputbox" type="text" name="username" value="<?php echo $row->username;?>" size="40" /></td>
    </tr>
    <tr>
      <td><?php echo T_('Password:'); ?></td>
      <td><input class="inputbox" type="password" name="password" value="" size="40" /></td>
    </tr>
    <tr>
      <td><?php echo T_('Verify Password:'); ?></td>
      <td><input class="inputbox" type="password" name="verifyPass" size="40" /></td>
    </tr>
    <tr>
      <td colspan="2">
        <input class="button" type="button" value="<?php echo $submitvalue; ?>" onclick="submitbutton()" />
      </td>
    </tr>
  </table>
	<input type="hidden" name="id" value="<?php echo $row->id;?>" />
	<input type="hidden" name="option" value="<?php echo $option;?>">
	<input type="hidden" name="task" value="saveUserEdit" />
</form>
<?php
	}

	function confirmation() {
		?>
	<div class="componentheading">
		<?php echo T_('Submission Success!'); ?>
	</div>
	<table>
		<tr>
			<td><?php echo T_('Your item has been submitted to the site administrators. It will be reviewed before being published on the site.'); ?></td>
		</tr>
	</table>
<?php
	}
}
?>