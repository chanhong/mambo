<?php echo "<?xml version=\"1.0\"?>";
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php mosShowHead(); ?>
	<?php include ("editor/editor.php"); ?>
	<?php initEditor(); ?>
	<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
	<link href="<?php echo $mosConfig_live_site;?>/templates/rhuk_blueberry_milkshake/css/template_css.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" href="<?php echo $mosConfig_live_site;?>/templates/rhuk_blueberry_milkshake/images/favicon.ico" />
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" class="redbg">
	<a name="up" id="up"></a>
	<center>
	<table width="100%"  border="0" cellpadding="0" cellspacing="0">
		<tr class="blueberry_milkshake_header">
			<td rowspan="2"><img src="<?php echo $mosConfig_live_site;?>/templates/rhuk_blueberry_milkshake/images/blueberry_milkshake.gif" width="435" height="96"></td>
			<td align="right" valign="middle"><?php mosLoadComponent( "banners" ); ?><img src="<?php echo $mosConfig_live_site;?>/templates/rhuk_blueberry_milkshake/images/spacer.gif" width="10" height="4" /></td>
		</tr>
		<tr class="blueberry_milkshake_header">
			<td align="right">
			<a href="<?php echo $mosConfig_live_site;?>/index.php?option=com_frontpage&Itemid=1" class="mainmenu">Home</a>
			<a href="<?php echo $mosConfig_live_site;?>" class="mainmenu">Members</a>
			<a href="<?php echo $mosConfig_live_site;?>" class="mainmenu">About Us</a>
			<a href="<?php echo $mosConfig_live_site;?>/index.php?option=com_contact&Itemid=3" class="mainmenu">Contact Us</a>
			<a href="<?php echo $mosConfig_live_site;?>" class="mainmenu">Sitemap</a>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="blueberry_milkshake_line"><img src="<?php echo $mosConfig_live_site;?>/templates/rhuk_blueberry_milkshake/images/spacer.gif" width="100" height="7" /></td>
		</tr>
	</table>
	<table width="100%"  border="0" cellpadding="0" cellspacing="0">

			<tr valign="top">
				<td class="leftnav" width="200">
					<?php mosLoadModules ( 'left' ); ?>
					<!-- left nav -->

					<br>
					<img src="<?php echo $mosConfig_live_site;?>/templates/rhuk_blueberry_milkshake/images/spacer.gif" width="200" height="1" /><br>
					<?php if (mosCountModules( "user1" )) { ?>
					<!-- user1 nav -->

						<?php mosLoadModules ( 'user1' ); ?>

					<?php } ?>
				</td>
				<td class="centernav" width="99%">
					<?php include "pathway.php"; ?><img src="<?php echo $mosConfig_live_site;?>/templates/rhuk_blueberry_milkshake/images/spacer.gif" width="174" height="24"/>
					<?php if (mosCountModules( "top" )) { ?>
						<div class="centerblock">
						<?php mosLoadModules ( 'top' ); ?>
						</div>
					<br>
					<?php } ?>
					
					
					<div class="centerblock">
					<?php include ("mainbody.php"); ?>
<?php include_once( "components/com_comments/comments.php" ); ?>
					</div>
					<br>
					
					<?php if (mosCountModules( "bottom" )) { ?>
						<div class="centerblock">
						<?php mosLoadModules ( 'bottom' ); ?>
						</div>
						<br>
					<?php } ?>
					
					<?php if (mosCountModules( "inset" )) { ?>
						<div class="centerblock">
						<?php mosLoadModules ( 'inset' ); ?>
						</div>
						<br>
					<?php } ?>
					
					<?php include_once("includes/footer.php"); ?>
					
				</td>
				<?php if (mosCountModules( "right" ) + mosCountModules( "user2" ) > 0) { ?>
				
				
				<td class="rightnav" width="174" align="right">
					<span class="pathway"><?php echo (strftime (_DATE_FORMAT_LC)); ?></span> <img src="<?php echo $mosConfig_live_site;?>/templates/rhuk_blueberry_milkshake/images/spacer.gif" width="4" height="24" />
					
					
					<?php if (mosCountModules( "right" )) { ?>
						<div class="rightblock">
							<?php mosLoadModules ( 'right' ); ?>
						</div>
						<br>
					<?php } ?>
					
					
					
					<?php if (mosCountModules( "user2" )) { ?>
						<div class="rightblock">
						<?php mosLoadModules ( 'user2' ); ?>
						</div>
					<?php } ?>
					<br>
					<img src="<?php echo $mosConfig_live_site;?>/templates/rhuk_blueberry_milkshake/images/spacer.gif" width="174" height="2" />
				</td>
				
				<?php } ?>
			</tr>
		</table>
	</center>
</body>
</html>
