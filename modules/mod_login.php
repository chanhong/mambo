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

global $mosConfig_locale;

if (isset($_SERVER['QUERY_STRING']) AND $_SERVER['QUERY_STRING']) $return = 'index.php?'.$_SERVER['QUERY_STRING'];
else $return = 'index.php';

// converts & to &amp; for xtml compliance
$return = str_replace( '&', '&amp;', $return );

$registration_enabled 	= $mainframe->getCfg( 'allowUserRegistration' );
$message_login 			= $params->def( 'login_message', 0 );
$message_logout 		= $params->def( 'logout_message', 0 );
$pretext 	= $params->get( 'pretext' );
$posttext 	= $params->get( 'posttext' );
$login 		= $params->def( 'login', $return );
$logout 	= $params->def( 'logout', $return );
$name 		= $params->def( 'name', 1 );
$greeting 	= $params->def( 'greeting', 1 );

if ( $name AND $my->id ) {
	$query = "SELECT name FROM #__users WHERE id = ". $my->id;
	$database->setQuery( $query );
	$name = $database->loadResult();
} else {
	$name = $my->username;
}

if ( $my->id ) {
	?>
	<form action="<?php echo sefRelToAbs('index.php'); ?>" method="post">
	<?php
	if ( $greeting ) {
	    printf(T_('Hi, %s'), $name);
	}
	?>
	<br />
	<div align="center">
	<input type="submit" class="button" value="<?php echo T_('Logout'); ?>" />
	<input type="hidden" name="option" value="logout" />
	<input type="hidden" name="op2" value="logout" />
	<input type="hidden" name="lang" value="<?php echo $mosConfig_locale; ?>" />
	<input type="hidden" name="return" value="<?php echo sefRelToAbs( $logout ); ?>" />
	<input type="hidden" name="message" value="<?php echo $message_logout; ?>" />
	</div>
	</form>
	<?php
} else {
	?>
	<form action="<?php echo sefRelToAbs('index.php'); ?>" method="post" name="login" >
	<?php
	echo $pretext;
	?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td>
		<?php echo T_('Username'); ?>
		<br />
		<input name="username" type="text" class="inputbox" alt="<?php echo T_('username') ?>" size="10" />
		<br />
		<?php echo T_('Password'); ?>
		<br />
		<input type="password" name="passwd" class="inputbox" size="10" alt="<?php echo T_('password') ?>" />
		<br />
		<input type="checkbox" name="remember" class="inputbox" value="yes" alt="<?php echo T_('Remember Me'); ?>" /> 
		<?php echo T_('Remember me'); ?>
		<br />
		<input type="hidden" name="option" value="login" />
		<input type="submit" name="Submit" class="button" value="<?php echo T_('Login'); ?>" />
		</td>
	</tr>
	<tr>
		<td>
		<a href="<?php echo sefRelToAbs( 'index.php?option=com_registration&amp;task=lostPassword' ); ?>">
		<?php echo T_('Password Reminder'); ?>
		</a>
		</td>
	</tr>
	<?php
	if ( $registration_enabled ) {
		?>
		<tr>
			<td>
			<?php echo T_('No account yet?'); ?>
			<a href="<?php echo sefRelToAbs( 'index.php?option=com_registration&amp;task=register' ); ?>">
			<?php echo T_('Create one'); ?>
			</a>
			</td>
		</tr>
		<?php
	}
	?>
	</table>
	<?php
	echo $posttext;
	?>
	
	<input type="hidden" name="op2" value="login" />
	<input type="hidden" name="lang" value="<?php echo $mosConfig_locale; ?>" />
	<input type="hidden" name="return" value="<?php echo sefRelToAbs( $login ); ?>" />
	<input type="hidden" name="message" value="<?php echo $message_login; ?>" />
	</form>
	<?php
}
?>
