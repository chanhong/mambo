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

class HTML_registration {
	function lostPassForm($option) {
		?>

<div class="componentheading">
	<?php echo T_('Lost your Password?'); ?>
</div>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
  <form action="index.php" method="post">
    <tr>
      <td colspan="2"><?php printf(T_('Please enter your Username and e-mail address then click on the Send Password button.%s You will receive a new password shortly.  Use the new password to access the site.'), '<br />'); ?></td>
    </tr>
    <tr>
      <td><?php echo T_('Username:'); ?></td>
      <td><input type="text" name="checkusername" class="inputbox" size="40" maxlength="25" /></td>
    </tr>
    <tr>
      <td><?php echo T_('E-mail Address:'); ?></td>
      <td><input type="text" name="confirmEmail" class="inputbox" size="40" /></td>
    </tr>
    <tr>
      <td colspan="2"> <input type="hidden" name="option" value="<?php echo $option;?>" />
        <input type="hidden" name="task" value="sendNewPass" /> <input type="submit" class="button" value="<?php echo T_('Send Password'); ?>" /></td>
    </tr>
  </form>
</table>
<?php
	}

function registerForm($option, $useractivation) {
$name = trim( mosGetParam( $_REQUEST, 'name', "" ) );
$username = trim( mosGetParam( $_REQUEST, 'username', "" ) );
$email = trim( mosGetParam( $_REQUEST, 'email', "" ) );
$useractivation = trim( mosGetParam( $_REQUEST, 'useractivation', "" ) );
?>
	<script language="javascript" type="text/javascript">
		function submitbutton() {
			var form = document.mosForm;
			//old method didn't really work, just excluded certain characters rather than limiting to a range of characters.
			//var r = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]", "i");
			var r = new RegExp("[^A-Za-z0-9]", "i");

			// do field validation
			if (form.name.value == "") {
				alert( "<?php echo html_entity_decode(T_('Please enter your name.'));?>" );
				form.name.focus();
			} else if (form.username.value == "") {
				alert( "<?php echo html_entity_decode(T_('Please enter a user name.'));?>" );
				form.username.focus();
			} else if (r.exec(form.username.value) || form.username.value.length < 3) {
				alert( "<?php printf( html_entity_decode(T_("Please enter a valid %s.  No spaces, more than %d characters and containing only the characters 0-9,a-z, or A-Z")), html_entity_decode(T_('Please enter a user name.')), 2 );?>" );
				form.username.focus();
			} else if (form.email.value == "" ) {
				alert( "<?php echo html_entity_decode(T_('Please enter a valid e-mail address.'));?>" );
				form.email.focus();
			} else if (form.email2.value == "" ) {
				alert( "<?php echo html_entity_decode(T_('Please enter a valid e-mail address for the verification.'));?>" );
				form.email2.focus();
			} else if (form.password.value.length < 6) {
				alert( "<?php echo html_entity_decode(T_('Please enter a valid password -- more than 6 characters with no spaces and containing only the characters 0-9, a-z, or A-Z'));?>" );
				form.password.focus();
			} else if (form.password2.value == "") {
				alert( "<?php echo html_entity_decode(T_('Please verify the verification password.'));?>" );
				form.password2.focus();
			} else if ((form.password.value != "") && (form.password.value != form.password2.value)){
				alert( "<?php echo html_entity_decode(T_('Password and verification do not match, please try again.'));?>" );
				form.password.value="";
				form.password2.value="";
				form.password.focus();
			} else if (r.exec(form.password.value)) {
				alert( "<?php printf( html_entity_decode(T_("Please enter a valid %s.  No spaces, more than %d characters and containing only the characters 0-9,a-z, or A-Z")), html_entity_decode(T_('Password:')), 6 );?>" );
				form.password.focus();
			} else if ((form.password.value != "") && (form.email.value != form.email2.value)){
				alert( "<?php printf( html_entity_decode(T_('Email and verification do not match, please try again.')));?>" );
				form.email.value="";
				form.email2.value="";
				form.email.focus();
			} else if ( form.accept.checked == false) {
				alert( "<?php printf( html_entity_decode(T_('You must accept the Privacy Policy and Disclaimer, to continue.')));?>" );
				form.accept.focus();
			} else {
				form.submit();
			}
		}
	</script>

<div class="componentheading">
	<?php echo T_('Registration'); ?>
</div>
<form action="index.php" method="post" name="mosForm">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
<tr>
<td colspan="2"><?php echo T_('Fields marked with an asterisk (*) are required.'); ?></td>
</tr>
    <tr>
      <td width="30%"><?php echo T_('Name:'); ?> *</td>
      <td><input type="text" name="name" size="40" value="<?php echo $name; ?>" class="inputbox" /></td>
    </tr>

    <tr>

      <td><?php echo T_('Username:'); ?> *</td>
      <td><input type="text" name="username" size="40" value="<?php echo $username; ?>" class="inputbox" /></td>
    <tr>

      <td><?php echo T_('E-mail:'); ?> *</td>
      <td><input type="text" name="email" size="40" value="<?php echo $email; ?>" class="inputbox" /></td>
    </tr>

    <tr>
      <td><?php echo T_('Verify E-mail:'); ?> *</td>
      <td><input type="text" name="email2" class="inputbox" id="email2" value="<?php echo $email; ?>" size="40" /></td>
    </tr>
    <tr>
      <td><?php echo T_('Password:'); ?> *</td>
      <td><input class="inputbox" type="password" name="password" size="40" value="" /></td>
    </tr>
    <tr>
      <td><?php echo T_('Verify Password:'); ?> *</td>
      <td><input class="inputbox" type="password" name="password2" size="40" value="" /></td>
    </tr>
      <td valign="top"><?php echo T_('Disclaimer and<br />Privacy Policy:'); ?> *</td>
      <td>
     <textarea name="privacypolicy" cols="48" rows="4" id="privacypolicy" class="inputbox" style="font-size:0.85em;" readonly><?php echo T_('put your disclaimer here..'); ?></textarea>
     </td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td><input name="accept" type="checkbox" id="accept" value="yes">        
        <?php echo T_('Yes, I Accept'); ?></td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>

    <tr>
      <td colspan=2>
  </td>
	</tr>
</table>
<input type="hidden" name="id" value="0" />
<input type="hidden" name="gid" value="0" />
<input type="hidden" name="useractivation" value="<?php echo $useractivation;?>" />
<input type="hidden" name="option" value="<?php echo $option; ?>" />
<input type="hidden" name="task" value="confirmRegistration" />
<input type="button" value="<?php echo T_('Send Registration'); ?>" class="button" onclick="submitbutton()" />
</form>
<?php
	}


function confirmForm($option, $name, $username, $password, $email, $useractivation) {
?>
<script language="javascript" type="text/javascript">
function reviseData()
{
 var form = document.mosForm;
 form.task.value='reviseRegistration';
 form.submit();
}
</script>
<div class="componentheading">
<?php echo T_('Registration'); ?></div>
<form action="index.php" method="post" name="mosForm">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
    <tr>
      <td colspan="2"><?php echo T_('Please verify that the following data is correct and click the button below to complete registration.'); ?> </td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td width="15%"><?php echo T_('Name:'); ?> </td>
      <td><strong><?php echo $name;?></strong><input type="hidden" name="name" size="40" value="<?php echo $name;?>" /></td>
    </tr>
    <tr>
      <td><?php echo T_('Username:'); ?> </td>
      <td><strong><?php echo $username;?></strong><input type="hidden" name="username" size="40" value="<?php echo $username;?>" /></td>
    <tr>
      <td><?php echo T_('E-mail:'); ?> </td>
      <td><strong><?php echo $email;?></strong><input type="hidden" name="email" size="40" value="<?php echo $email;?>" /></td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr align="center">
      <td colspan="2"><table width="80%" border="0" cellspacing="0" cellpadding="0">
        <tr align="center" valign="top">
          <td width="45%" align="center" valign="top" style="background-color:#FFE9E6;"><?php echo T_('Go back and correct your data.'); ?></td>
          <td width="10%" valign="top">&nbsp;</td>
          <td width="45%" align="center" style="background-color:#E8FFE8"><?php echo T_('The data is correct.'); ?></td>
        </tr>
        <tr align="center" valign="middle">
          <td height="32" align="center" valign="middle" style="background-color:#FFE9E6;"><input name="back" type="button" class="button" id="back" value="<?php echo T_('Correct Your Data'); ?>" onclick="reviseData()"></td>
          <td>&nbsp;</td>
          <td align="center" valign="middle" style="background-color:#E8FFE8"><input name="confirm" type="submit" class="button" id="confirm" value="<?php echo T_('Confirm Registration'); ?>"/></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan=2>
  </td>
	</tr>
</table>
<input type="hidden" name="id" value="0" />
<input type="hidden" name="gid" value="0" />
<input type="hidden" name="useractivation" value="<?php echo $useractivation;?>" />
<input type="hidden" name="password" value="<?php echo $password;?>" />
<input type="hidden" name="option" value="<?php echo $option; ?>" />
<input type="hidden" name="task" value="saveRegistration" />
</form>
<?php
}

}
?>
