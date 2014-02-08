<?php
/**
* @package Mambo
* @subpackage Banners
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

class HTML_banners {

	function showBanners( &$rows, &$pageNav, $option ) {
		global $my;

		mosCommonHTML::loadOverlib();
		?>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th>
			<?php echo T_('Banner Manager'); ?>
			</th>
		</tr>
		</table>

		<table class="adminlist">
		<tr>
			<th width="20">
			#
			</th>
			<th width="20">
			<input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $rows ); ?>);" />
			</th>
			<th align="left" nowrap>
			<?php echo T_('Banner Name'); ?>
			</th>
			<th width="10%" nowrap>
			<?php echo T_('Published'); ?>
			</th>
			<th width="11%" nowrap>
			<?php echo T_('Impressions Made'); ?>
			</th>
			<th width="11%" nowrap>
			<?php echo T_('Impressions Left'); ?>
			</th>
			<th width="8%">
			<?php echo T_('Clicks'); ?>
			</th>
			<th width="8%" nowrap>
			% <?php echo T_('Clicks'); ?>
			</th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i];

			$row->id 	= $row->bid;
			$link 		= 'index2.php?option=com_banners&task=editA&hidemainmenu=1&id='. $row->id;

			$impleft 	= $row->imptotal - $row->impmade;
			if( $impleft < 0 ) {
				$impleft 	= "unlimited";
			}

			if ( $row->impmade != 0 ) {
				$percentClicks = substr(100 * $row->clicks/$row->impmade, 0, 5);
			} else {
				$percentClicks = 0;
			}

			$task 	= $row->showBanner ? 'unpublish' : 'publish';
			$img 	= $row->showBanner ? 'publish_g.png' : 'publish_x.png';
			$alt 	= $row->showBanner ? 'Published' : 'Unpublished';

			$checked 	= mosCommonHTML::CheckedOutProcessing( $row, $i );
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td align="center">
				<?php echo $pageNav->rowNumber( $i ); ?>
				</td>
				<td align="center">
				<?php echo $checked; ?>
				</td>
				<td align="left">
				<?php
				if ( $row->checked_out && ( $row->checked_out != $my->id ) ) {
					echo $row->name;
				} else {
					?>
					<a href="<?php echo $link; ?>" title="<?php echo T_('Edit Banner'); ?>">
					<?php echo $row->name; ?>
					</a>
					<?php
				}
				?>
				</td>
				<td align="center">
				<a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $task;?>')">
				<img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="<?php echo $alt; ?>" />
				</a>
				</td>
				<td align="center">
				<?php echo $row->impmade;?>
				</td>
				<td align="center">
				<?php echo $impleft;?>
				</td>
				<td align="center">
				<?php echo $row->clicks;?>
				</td>
				<td align="center">
				<?php echo $percentClicks;?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>

		<input type="hidden" name="option" value="<?php echo $option; ?>">
		<input type="hidden" name="task" value="">
		<input type="hidden" name="boxchecked" value="0">
		<input type="hidden" name="hidemainmenu" value="0">
		</form>
		<?php
	}

	function bannerForm( &$_row, &$lists, $_option ) {
		mosMakeHtmlSafe( $_row, ENT_QUOTES, 'custombannercode' );
		?>
		<script language="javascript">
		<!--
		function changeDisplayImage() {
			if (document.adminForm.imageurl.value !='') {
				document.adminForm.imagelib.src='../images/banners/' + document.adminForm.imageurl.value;
			} else {
				document.adminForm.imagelib.src='images/blank.png';
			}
		}
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}
			// do field validation
			if (form.name.value == "") {
				alert( "<?php echo T_('You must provide a banner name'); ?>." );
			} else if (getSelectedValue('adminForm','cid') < 1) {
				alert( "<?php echo T_('Please select a client'); ?>." );
			} else if (!getSelectedValue('adminForm','imageurl') && form.custombannercode.value == "") {
				alert( "<?php echo T_('Please select an image.'); ?>" );
			} else if (form.clickurl.value == "" && form.custombannercode.value == "") {
				alert( "<?php echo T_('Please fill in the URL for the banner.'); ?>" );
			} else {
				submitform( pressbutton );
			}
		}
		//-->
		</script>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th>
			<?php echo T_('Banner:'); ?>
			<small>
			<?php echo $_row->cid ? T_('Edit') : T_('New');?>
			</small>
			</th>
		</tr>
		</table>

		<table class="adminform">
		<tr>
			<th colspan="2">
			<?php echo T_('Details'); ?>
			</th>
		</tr>
		<tr>
			<td width="20%">
			<?php echo T_('Banner Name:'); ?>
			</td>
			<td width="80%">
			<input class="inputbox" type="text" name="name" value="<?php echo $_row->name;?>">
			</td>
		</tr>
		<tr>
			<td>
			<?php echo T_('Client Name:'); ?>
			</td>
			<td align="left">
			<?php echo $lists['cid']; ?>
			</td>
		</tr>
		<tr>
			<td>
			<?php echo T_('Impressions Purchased:'); ?>
			</td>
			<?php
			if ($_row->imptotal == "0") {
				$unlimited="checked";
				$_row->imptotal="";
			} else {
				$unlimited = "";
			}
			?>
			<td>
			<input class="inputbox" type="text" name="imptotal" size="12" maxlength="11" value="<?php echo $_row->imptotal;?>">&nbsp;<?php echo T_('Unlimited'); ?> <input type="checkbox" name="unlimited" <?php echo $unlimited;?>>
			</td>
		</tr>
		<tr>
			<td valign="top">
			<?php echo T_('Banner URL'); ?>:
			</td>
			<td align="left">
			<?php echo $lists['imageurl']; ?>
			</td>
		</tr>
		<tr>
			<td>
			<?php echo T_('Show Banner:'); ?>
			</td>
			<td>
			<?php echo $lists['showBanner']; ?>
			</td>
		</tr>
		<tr>
			<td>
			<?php echo T_('Click URL:'); ?>
			</td>
			<td>
			<input class="inputbox" type="text" name="clickurl" size="50" maxlength="200" value="<?php echo $_row->clickurl;?>">
			</td>
		</tr>
		<tr>
			<td valign="top">
			<?php echo T_('Custom banner code:'); ?>
			</td>
			<td>
			<textarea class="inputbox" cols="70" rows="5" name="custombannercode"><?php echo $_row->custombannercode;?></textarea>
			</td>
		</tr>
		<tr >
			<td valign="top" align="right">
			<?php echo T_('Clicks'); ?>
			<br />
			<input name="reset_hits" type="button" class="button" value="<?php echo T_('Reset Clicks'); ?>" onClick="submitbutton('resethits');">
			</td>
			<td colspan="2">
			<?php echo $_row->clicks;?>
			</td>
		</tr>
		<tr>
			<td colspan="3">
			</td>
		</tr>
		<tr>
			<td valign="top">
			<?php echo T_('Banner Image:'); ?>
			</td>
			<td valign="top">
			<?php
			if (eregi("swf", $_row->imageurl)) {
				?>
				<img src="images/blank.png" name="imagelib">
				<?php
			} elseif (eregi("gif|jpg|png", $_row->imageurl)) {
				?>
				<img src="../images/banners/<?php echo $_row->imageurl; ?>" name="imagelib">
				<?php
			} else {
				?>
				<img src="images/blank.png" name="imagelib">
				<?php
			}
			?>
			</td>
		</tr>
		<tr>
			<td colspan="3">
			</td>
		</tr>
		</table>

		<input type="hidden" name="option" value="<?php echo $_option; ?>">
		<input type="hidden" name="bid" value="<?php echo $_row->bid; ?>">
		<input type="hidden" name="task" value="">
		<input type="hidden" name="impmade" value="<?php echo $_row->impmade; ?>">
		</form>
		<?php
	}
}

/**
* Banner clients
* @package Mambo
*/
class HTML_bannerClient {

	function showClients( &$rows, &$pageNav, $option ) {
		global $my;

		mosCommonHTML::loadOverlib();
		?>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th>
			<?php echo T_('Banner Client Manager'); ?>
			</th>
		</tr>
		</table>

		<table class="adminlist">
		<tr>
			<th width="20">
			#
			</th>
			<th width="20">
			<input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $rows ); ?>);" />
			</th>
			<th align="left" nowrap>
			<?php echo T_('Client Name'); ?>
			</th>
			<th align="left" nowrap>
			<?php echo T_('Client ID'); ?>
			</th>
			<th align="left" nowrap>
			<?php echo T_('Contact'); ?>
			</th>
			<th align="center" nowrap>
			<?php echo T_('No. of Active Banners'); ?>
			</th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i];

			$row->id 	= $row->cid;
			$link 		= 'index2.php?option=com_banners&task=editclientA&hidemainmenu=1&id='. $row->id;

			$checked 	= mosCommonHTML::CheckedOutProcessing( $row, $i );
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td width="20" align="center">
				<?php echo $pageNav->rowNumber( $i ); ?>
				</td>
				<td width="20">
				<?php echo $checked; ?>
				</td>
				<td width="30%">
				<?php
				if ( $row->checked_out && ( $row->checked_out != $my->id ) ) {
					echo $row->name;
				} else {
					?>
					<a href="<?php echo $link; ?>" title="<?php echo T_('Edit Banner Client'); ?>">
					<?php echo $row->name; ?>
					</a>
					<?php
				}
				?>
				</td>
				<td width="20%">
				<?php echo $row->id;?>
				</td>
				<td width="30%">
				<?php echo $row->contact;?>
				</td>
				<td width="20%" align="center">
				<?php echo $row->bid;?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>
		<input type="hidden" name="option" value="<?php echo $option; ?>">
		<input type="hidden" name="task" value="listclients">
		<input type="hidden" name="boxchecked" value="0">
		<input type="hidden" name="hidemainmenu" value="0">
		</form>
		<?php
	}

	function bannerClientForm( &$row, $option ) {
		mosMakeHtmlSafe( $row, ENT_QUOTES, 'extrainfo' );
		?>
		<script language="javascript">
		<!--
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancelclient') {
				submitform( pressbutton );
				return;
			}
			// do field validation
			if (form.name.value == "") {
				alert( "<?php echo T_('Please fill in the Client Name.'); ?>" );
			} else if (form.contact.value == "") {
				alert( "<?php echo T_('Please fill in the Contact Name.'); ?>" );
			} else if (form.email.value == "") {
				alert( "<?php echo T_('Please fill in the Contact Email.'); ?>" );
			} else {
				submitform( pressbutton );
			}
		}
		//-->
		</script>
		<table class="adminheading">
		<tr>
			<th>
			<?php echo T_('Banner Client:'); ?>
			<small>
			<?php echo $row->cid ? T_('Edit') : T_('New');?>
			</small>
			</th>
		</tr>
		</table>

		<form action="index2.php" method="post" name="adminForm">
		<table class="adminform">
		<tr>
			<th colspan="2">
			<?php echo T_('Details'); ?>
			</th>
		</tr>
		<tr>
			<td width="10%">
			<?php echo T_('Client Name:'); ?>
			</td>
			<td>
			<input class="inputbox" type="text" name="name" size="30" maxlength="60" valign="top" value="<?php echo $row->name; ?>">
			</td>
		</tr>
		<tr>
			<td width="10%">
			<?php echo T_('Contact Name:'); ?>
			</td>
			<td>
			<input class="inputbox" type="text" name="contact" size="30" maxlength="60" value="<?php echo $row->contact; ?>">
			</td>
		</tr>
		<tr>
			<td width="10%">
			<?php echo T_('Contact Email:'); ?>
			</td>
			<td>
			<input class="inputbox" type="text" name="email" size="30" maxlength="60" value="<?php echo $row->email; ?>">
			</td>
		</tr>
		<tr>
			<td valign="top">
			<?php echo T_('Extra Info:'); ?>
			</td>
			<td>
			<textarea class="inputbox" name="extrainfo" cols="60" rows="10"><?php echo str_replace('&','&amp;',$row->extrainfo);?></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="3">
			</td>
		</tr>
		</table>

		<input type="hidden" name="option" value="<?php echo $option; ?>">
		<input type="hidden" name="cid" value="<?php echo $row->cid; ?>">
		<input type="hidden" name="task" value="">
		</form>
		<?php
	}
}
?>