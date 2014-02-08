<?php
/**
* YourTemplatesName - A Mambo 4.5.1 template
* @version 1.0
* @package YourTemplatesName
* @copyright (C) 2004 by Your Name
* @license Your license name here
*/
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
$iso = split( '=', _ISO );
echo '<?xml version="1.0" encoding="'. $iso[1] .'"?' .'>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php if ( $my->id ) initEditor(); ?>
<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
<?php mosShowHead(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo $mosConfig_live_site;?>/templates/crystal_mambo451/css/template_css.css" />
<?php // Custom MainMenu extension...
$database->setQuery("SELECT * FROM #__menu WHERE menutype = 'topmenu' AND published ='1' AND parent = '0' ORDER BY ordering");
$mymenu_rows = $database->loadObjectList();
$mymenu_content = "";
//if ($mmymenu_rows)
foreach($mymenu_rows as $mymenu_row) {
  // print_r($mymenu_rows);
  $mymenulink = $mymenu_row->link;
  if ($mymenu_row->type != "url") { $mymenulink .= "&Itemid=$mymenu_row->id"; }
  if ($mymenu_row->type != "separator") {
    $mymenu_content .= ': <a href="'.sefRelToAbs($mymenulink).'" class="bar">'.$mymenu_row->name.'</a> :';
  }
}
//$mymenu_content = ampReplace(substr($mymenu_content,1,strlen($mymenu_content)-2));
$mymenu_content = substr($mymenu_content,1,strlen($mymenu_content)-2);
?>
</head>
<body>
<table width="99%" align="center" cellpadding="0" cellspacing="0">
<tr><td colspan="3" bgcolor="#A6C59E" height="15">&nbsp;&nbsp;<?php mosPathWay(); ?></td></tr>
<tr>
    <td colspan="3" height="75" class="title_top">
 <table cellpadding="0" cellspacing="0" width="100%"> 
<tr><td></td>
<td class="titlelogo"><img src="<?php echo $mosConfig_live_site;?>/templates/crystal_mambo451/images/banner.png" width="150" alt="banner" />Dedicated for researching CMS and Tools</td>
<td align="right"><?php mosLoadComponent( "banners" ); ?></td>
</tr></table>

    </td>
</tr>
<tr>
    <td align="center" valign="middle" colspan="3" bgcolor="#A6C59E" height="15">
        <span class="bar"><?php echo $mymenu_content ?>&nbsp;</span>
    </td>
</tr>
<tr>
<!-- LEFT POSITION -->
<?php if (mosCountModules( "left" ) > 0) { ?>
<td valign="top" width="7%">
<?php } else { ?>
<td valign="top">
<?php } ?>
    <br>
    <?php if (mosCountModules( "left" ) > 0) { ?>
    <table width="96%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td>
            <?php mosLoadModules('left'); ?>
        </td>
    </tr>
    </table>
    <?php } ?>

</td>
<!-- END OF LEFT POSITION -->


<!-- CONTENT, CENTER MODULES -->
<td valign="top">
    <br>
    
    <?php if (mosCountModules( "top" ) > 0) { ?>
    <table width="96%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td>
            <?php mosLoadModules('top'); ?>
        </td>
    </tr>
    </table>
    <?php } ?>
    <br>
    <table width="96%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td>
            <?php mosMainBody(); ?>
<?php if (file_exists($mosConfig_absolute_path."/components/com_comments/comments.php")) { require_once($mosConfig_absolute_path."/components/com_comments/comments.php"); } ?>
        </td>
    </tr>
    </table>
    <br>
    <?php if (mosCountModules( "bottom" ) > 0) { ?>
    <table width="96%" cellpadding="0" cellspacing="0" align="center">
    <tr>
        <td>
            <?php mosLoadModules('bottom'); ?>
        </td>
    </tr>
    </table>
    <?php } ?>
    
    </td>
<!-- END OF CENTER -->

<!-- RIGHT MODULES -->
    <?php if (mosCountModules( "right" ) + mosCountModules( "user1" ) + mosCountModules( "user2" ) + mosCountModules( "inset" )  > 0) { ?>
    <td valign="top" width="13%">
    <?php }
          else {
    ?>
    <td valign="top">
    <?php
         }
    ?>
    <br>
        <?php if (mosCountModules( "right" ) > 0) { ?>
        <table width="96%" cellpadding="0" cellspacing="0" align="center">
        <tr>
            <td>
                <?php mosLoadModules( "right" ); ?>
            </td>
        </tr>
        </table>
        <?php } ?>
        <br>
        <table width="96%" cellpadding="0" cellspacing="0" align="center">
        <tr>
            <td width="50%" valign="top">
                <?php mosLoadModules('user1'); ?>
            </td>
            <td width="50%" valign="top">
                <?php mosLoadModules('user2'); ?>
            </td>
        </tr>
        </table>
        <br>
        <?php if (mosCountModules( "inset" ) > 0) { ?>
        <table width="96%" cellpadding="0" cellspacing="0" align="center">
        <tr>
            <td>
                <?php mosLoadModules('inset'); ?>
            </td>
        </tr>
        </table>
        <?php } ?>

    </td>
<!-- END OF RIGHT MODULES -->
</tr>
<tr>
    <td align="right" colspan="3" height="25">
<div style="float:left"><a href="http://validator.w3.org/check/referer">Validate XHTML</a></div>
		  <div style="float:right"><a href="http://jigsaw.w3.org/css-validator/check/referer">Validate CSS</a></div>
    </td>
</tr>
<tr>
    <td colspan="3" height="1" class="pathway">
       <hr size="1">
    </td>
</tr>
</table>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-250124-6";
urchinTracker();
</script>
<!--
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-250124-1";
urchinTracker();
</script>
-->
<!--
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-250124-2";
urchinTracker();
</script>
-->
<?php  
if (file_exists("mosmodule/amazoncontext.php")) { include("mosmodule/amazoncontext.php"); }
if (file_exists("mosmodule/ads.php")) { include("mosmodule/ads.php"); }
?>
</body>
</html>
