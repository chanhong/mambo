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

class loginHTML {

	function loginpage ( &$params, $image ) {
		global $mosConfig_locale; 
		$return = $params->get( 'login','index.php' );
		?>
		<form action="<?php echo sefRelToAbs( 'index.php?option=login' ); ?>" method="post" name="login" id="login">
		<table width="100%" border="0" align="center" cellpadding="4" cellspacing="0" class="contentpane<?php echo $params->get( 'pageclass_sfx' ); ?>">
		<tr>
			<td colspan="2">
			<?php 
			if ( $params->get( 'page_title' ) ) {
				?>
				<div class="componentheading<?php echo $params->get( 'pageclass_sfx' ); ?>">
				<?php echo $params->get( 'header_login' ); ?>
				</div>
				<?php
			}
			?>
			<div>
			<?php echo $image; ?>
			<?php
			if ( $params->get( 'description_login' ) ) {
				 ?>
				<?php echo $params->get( 'description_login_text' ); ?>
				<br /><br />
				<?php
			}
			?>
			</div>
			</td>
		</tr>
		<tr>
			<td align="center" width="50%"> 
				<br />
				<table>
				<tr>
					<td align="center">
					<?php echo T_('Username'); ?>
					<br /> 
					</td>
					<td align="center">
					<?php echo T_('Password'); ?>
					<br /> 
					</td>
				</tr>
				<tr>
					<td align="center">
					<input name="username" type="text" class="inputbox" size="20" />
					</td>
					<td align="center">
					<input name="passwd" type="password" class="inputbox" size="20" />
					</td>
				</tr>
				<tr>
					<td align="center" colspan="2">
					<br />				
					<?php echo T_('Remember me'); ?>
					<input type="checkbox" name="remember" class="inputbox" value="yes" /> 
					<br />
					<a href="<?php echo sefRelToAbs( 'index.php?option=com_registration&amp;task=lostPassword' ); ?>">
					<?php echo T_('Password Reminder'); ?>
					</a>
					<?php
					if ( $params->get( 'registration' ) ) {
						?>
						<br />
						<?php echo T_('No account yet?'); ?>
						<a href="<?php echo sefRelToAbs( 'index.php?option=com_registration&amp;task=register' ); ?>">
						<?php echo T_('Create one');?>
						</a>
						<?php
					}
					?>
					<br /><br /><br />
					</td>
				</tr>
				</table>
			</td>
			<td>
			<div align="center">
			<input type="submit" name="submit" class="button" value="<?php echo T_('Login'); ?>" />
			</div>

			</td>			
		</tr>
		<tr>
			<td colspan="2"> 
			<noscript>
			<?php echo T_('!Warning! Javascript must be enabled for proper operation.'); ?>
			</noscript>
			</td>
		</tr>
		</table>
		<?php
		// displays back button
		mosHTML::BackButton ( $params );
		?>

		<input type="hidden" name="op2" value="login" />
		<input type="hidden" name="return" value="<?php echo sefRelToAbs( $return ); ?>" />
		<input type="hidden" name="lang" value="<?php echo $mosConfig_locale; ?>" />
		<input type="hidden" name="message" value="<?php echo $params->get( 'login_message' ); ?>" />
		</form>
		<?php  
  	}
	
	function logoutpage( &$params, $image ) {
		global $mosConfig_locale; 
		
		$return = $params->get( 'logout' ,'index.php');
		?>
		<form action="<?php echo sefRelToAbs( 'index.php?option=logout' ); ?>" method="post" name="login" id="login">
        	<table width="100%" border="0" align="center" cellpadding="4" cellspacing="0" class="contentpane<?php echo $params->get( 'pageclass_sfx' ); ?>">
		<tr>
			<td valign="top">
			<?php 
			if ( $params->get( 'page_title' ) ) {
				?>
				<div class="componentheading<?php echo $params->get( 'pageclass_sfx' ); ?>">
				<?php echo $params->get( 'header_logout' ); ?>
				</div>
				<?php
			}
			?>
			<div>
			<?php 
			echo $image; 
			
			if ( $params->get( 'description_logout' ) ) {
				echo $params->get( 'description_logout_text' ); 
				?>
				<br /><br />
				<?php
			}
			?>
			</div>
			</td>
		</tr>
		<tr>
			<td align="center">
			<div align="center">
			<input type="submit" name="Submit" class="button" value="<?php echo T_('Logout'); ?>" />
			</div>
			</td>
		</tr>
    	</table>
		<?php
		// displays back button
		mosHTML::BackButton ( $params );
		?>

		<input type="hidden" name="op2" value="logout" />
		<input type="hidden" name="return" value="<?php echo sefRelToAbs( $return ); ?>" />
		<input type="hidden" name="lang" value="<?php echo $mosConfig_locale; ?>" />
		<input type="hidden" name="message" value="<?php echo $params->get( 'logout_message' ); ?>" />
		</form>
	<?php
	}
}
?>
