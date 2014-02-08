<!--IE 7 quirks mode please-->
<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
// needed to seperate the ISO number from the language file constant _ISO
$iso = explode( '=', _ISO );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php mosShowHead(); ?>
<?php
if ( $my->id ) {
	initEditor();
}
?>
<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
<link href="<?php echo $mosConfig_live_site;?>/templates/waterandstone800_greenandblack/css/template_css.css" rel="stylesheet" type="text/css"/>
</head>
<body class="waterbody">

<div align="center">
<div id="container">
	<div id="containerbg">
		<div id="outerleft">
			<!-- start logo -->
			<div id="logo">
			  <a href="index.php"><img src="<?php echo $mosConfig_live_site; ?>/templates/waterandstone800_greenandblack/images/logo.gif" alt="logo image" border="0" align="top" /></a>
			</div>
			<!-- end logo -->
			<!-- start top menu. position:top -->
			<div id="topmenu">
			<?php mosLoadModules('top',-1); ?>
			</div>
			<!-- end top menu. position:top -->
			<!-- start image header -->
			<div id="imgheader">
			<img src="<?php echo $mosConfig_live_site; ?>/templates/waterandstone800_greenandblack/images/img_header.jpg" alt="header image" />
			</div>
			<!-- end image header -->
			<div id="container_inner">
				<!-- start left column.position:left,user1 -->
				<div id="leftcol">
				<?php mosLoadModules('left'); ?>
				</div>
				<div id="leftcolmenu">
				<?php mosLoadModules('user1'); ?>
				</div>
				<!-- end left column.position:left -->
				<!-- start content top wrapper -->
				<?php
				if (mosCountModules('user2') >= 1 OR mosCountModules('user3') >= 1 ) {
				?>
				<div id="content_top_wrapper">
					<!-- start content top 1. position:user2 -->
					<div id="content_top1">
					<?php mosLoadModules('user2'); ?>
					</div>
					<!-- end content top 1 -->
					<!-- start content top 2. position:user3 -->
					<div id="content_top2">
					<?php mosLoadModules('user3'); ?>
					</div>
					<!-- end content top 2 -->
				</div>
				<?php
				}
				?>
				<!-- end content top wrapper -->
				<!-- start main body -->
				<div id="content_main">
				<?php mosPathWay(); ?>
				<table width="395" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td>
					<?php mosMainBody(); ?>
					</td>
				  </tr>
				</table>
				</div>
				<!-- end main body -->
			</div>
		</div>
		<div id="outerright">
			<!-- start right top header. position:header -->
			<div id="rightcol_top">
			<?php mosLoadModules('header'); ?>
			</div>
			<!-- end right top header.-->
			<!-- start right column. position:newsflash,right,user4 -->
			<div id="rightcol">
			<?php mosLoadModules('newsflash'); ?>
			<?php mosLoadModules('right'); ?>
			<?php mosLoadModules('user4'); ?>
			</div>
			<!-- end right column. -->
		</div>
		<!-- start banner. position:banner -->
		<div class="clear">
		</div>
		<?php
		if (mosCountModules('banner') >= 1) {
		?>
		<div id="banner">
			<div align="left">
			<?php mosLoadModules('banner'); ?>
			</div>
		</div>
		<?php
		} else {
		?>
		<div id="blackline">
		</div>
		<?php
		}
		?>
		<!-- end banner. -->
		<div class="clear">
		</div>
		<div id="bottompadding">
		</div>
	</div>
	<!-- copyright notice -->
	<div id="copyright">
	<?php include_once( $GLOBALS['mosConfig_absolute_path'] . '/includes/footer.php' ); ?>
	</div>

</div>
</div>
<?php mosLoadModules('debug', -1);?>
</body>
</html>
