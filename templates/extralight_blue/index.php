<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php echo "<?xml version=\"1.0\"?>";
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
<meta name="robots" content="all" />
<?php mosShowHead(); ?>
<?php
if ($my->id) {
	include ("editor/editor.php");
	initEditor();
}
?>
<script language="JavaScript" type="text/javascript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>
<link href="templates/<?php echo $cur_template; ?>/css/template_css.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="templates/<?php echo $cur_template; ?>/templates/<?php echo $cur_template; ?>/images/favicon.ico" />
<?php // Custom MainMenu extension...
$database->setQuery("SELECT * FROM #__menu WHERE menutype = 'topmenu' AND published ='1' AND parent = '0' ORDER BY ordering");
$mymenu_rows = $database->loadObjectList();
$mymenu_content = "";
foreach($mymenu_rows as $mymenu_row) {
  // print_r($mymenu_rows);
  $mymenulink = $mymenu_row->link;
  if ($mymenu_row->type != "url") { $mymenulink .= "&Itemid=$mymenu_row->id"; }
  if ($mymenu_row->type != "separator") {
    $mymenu_content .= ': <a href="'.sefRelToAbs($mymenulink).'" class="bar">'.$mymenu_row->name.'</a> :';
  }
}
$mymenu_content = ampReplace(substr($mymenu_content,1,strlen($mymenu_content)-2));
?>

</head>
<body>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr><td>
<div style="float:left"><img src="<?php echo $mosConfig_live_site;?>/templates/extralight_blue/images/top_logo.gif" width="261" height="90" alt="OngETC Logo" /></div>
<div class="banners" ><?php mosLoadComponent( "banners","-3" ); ?></div>
</td></tr>

<tr><td align="center" bgcolor="#C2E5EF" style="border-bottom:1px dashed #40DEFF; border-top:1px dashed #40DEFF ">
<span class="bar"><?php echo $mymenu_content ?></span>
</td></tr>

  <tr><td align="left" valign="middle">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="680"><img src="<?php echo $mosConfig_live_site;?>/images/M_images/arrow.png" width="9" height="9" alt="image" />&nbsp;<?php include "pathway.php"; ?></td>
<td align="right">&nbsp;</td>
<td align="right" width="180"><?php echo (strftime (_DATE_FORMAT_LC, time()+($mosConfig_offset*60*60)));?>&nbsp;</td>
</tr>
</table>
  </td></tr>

  <tr>
    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr align="left" valign="top">
	  <?php if (mosCountModules( "left" )) { ?>
        <td width="178">
        <table width="100%"  border="0" cellspacing="3" cellpadding="0">
          <tr><td><?php mosLoadModules ( 'left',"-3" ); ?></td></tr>
          <tr><td><?php mosLoadModules ( 'user1',"-3" ); ?></td></tr>
        </table>
          </td>
		  <?php } ?>
        <td>
          <table width="100%"  border="0" cellspacing="3" cellpadding="0">
          <tr><td><?php mosLoadModules ( 'top',"-3" ); ?></td></tr>
          <tr><td>
<div class=KonaBody>
            <?php include ("mainbody.php"); ?>
            <?php if (file_exists($mosConfig_absolute_path."/components/com_comments/comments.php")) {
               require_once($mosConfig_absolute_path."/components/com_comments/comments.php"); } ?>
</div>
            </td>
          </tr>
          <tr><td><?php mosLoadModules ( 'bottom',"-3" ); ?></td></tr>
          </table>
        </td>
        <td width="135"><table width="100%"  border="0" cellspacing="3" cellpadding="0">
          <tr><td><?php mosLoadModules ( 'right',"-3" ); ?></td></tr>
          <tr><td><?php mosLoadModules ( 'user2',"-3" ); ?></td></tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" valign="middle" bgcolor="#C2E5EF" style="border-bottom:1px dashed #40DEFF; border-top:1px dashed #40DEFF "><span class="bar"><?php echo $mymenu_content ?></span></td>
  </tr>
  <tr>
    <td align="center" valign="top">
<?php mosLoadModules ( 'inset' ); ?></td>
  </tr>
</table>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-250124-1";
urchinTracker();
</script>
<?php  if (file_exists("mosaddphp/ads.php")) { include("mosaddphp/ads.php"); }
?>
</body>
</html>
