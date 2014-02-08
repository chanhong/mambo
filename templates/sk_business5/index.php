<?php echo "<?xml version=\"1.0\"?".">"; ?>

<?php

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<?php mosShowHead(); ?>
<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
<?php include ("editor/editor.php"); ?>
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

<link href="templates/sk_business5/css/template_css.css" rel="stylesheet" type="text/css" /> 

<?php initEditor(); ?>



<?php // Custom MainMenu extension...



$mymenu_content = <<<EOT

<table border="0" cellspacing="0" cellpadding="0" width="100%">

  <tr>

    <td background="templates/sk_business5/images/bg.gif" align="center"><table border="0" cellspacing="0" cellpadding="0">

		<tr><td align="center" valign="middle" class="sitename">$mosConfig_sitename</td></tr></table></td>

	<td valign="bottom"><table border="0" cellspacing="0" cellpadding="0" width="100%">

	  <tr>



EOT;



	global $database, $my;

	

	$Itemid = mosgetParam( $_REQUEST, 'Itemid', 0 );

	

	$database->setQuery( "SELECT m.* FROM #__menu AS m"

	. "\nWHERE menutype='mainmenu' AND published=1 AND access <= $my->gid"

	. "\nORDER BY sublevel,ordering"

	);

	

	$rows = $database->loadObjectList( 'id' );

	echo $database->getErrorMsg();



	$openid = $Itemid;

	if ($openid == 0) {

		$openid ="1";

	}



	$database->setQuery( "SELECT * FROM #__menu WHERE menutype='mainmenu' AND published=1 AND access <= $my->gid AND parent = '$openid' ORDER BY ordering, sublevel");



	$subs = $database->loadObjectList();

	echo $database->getErrorMsg();





	$mytabs = count($rows);

	$tabcounter = 0;

		foreach ($rows as $row) { // insert main-menu items

			if ($row->parent == 0 && (trim( $row->link ))) {

				$name = addslashes( $row->name );

				$alt = addslashes( $row->name );

				$link = $row->link ? "$row->link" : "null";

				if ($row->type != "url") {

					$link .= "&Itemid=$row->id";

				}

				$link = sefRelToAbs($link);

				if ($tabcounter == 0) {

					if ($openid == $row->id) {

						$mymenu_content .="<td width=\"54\" nowrap><img src=\"templates/sk_business5/images/on_start.gif\" width=\"54\" height=\"36\"></td>";

						$mymenu_content .= "<td background=\"templates/sk_business5/images/on_bg.gif\" nowrap>";

						$mymenu_content .= "<a href=\"$link\" class=\"mainmenu\">$name</a>";

						$closetab = true;

					} else {

						$mymenu_content .="<td width=\"54\" nowrap><img src=\"templates/sk_business5/images/off_start.gif\" width=\"54\" height=\"36\"></td>";

						$mymenu_content .= "<td background=\"templates/sk_business5/images/off_bg.gif\" nowrap>";

						$mymenu_content .= "<a href=\"$link\" class=\"mainmenu\">$name</a>";

					}					

				} else {

					if ($openid == $row->id) {

						$mymenu_content .= "<td width=\"35\"><img src=\"templates/sk_business5/images/off_on.gif\" width=\"35\" height=\"36\"></td>";

						$mymenu_content .= "<td background=\"templates/sk_business5/images/on_bg.gif\" nowrap>";

						$mymenu_content .= "<a href=\"$link\" class=\"mainmenu\">$name</a>";

						$closetab = true;

					} else {

						if ($closetab == true) {

							$mymenu_content .= "<td width=\"35\"><img src=\"templates/sk_business5/images/on_off.gif\" width=\"35\" height=\"36\"></td>";

							$closetab = false;

						} else {

							$mymenu_content .= "<td width=\"35\"><img src=\"templates/sk_business5/images/off_off.gif\" width=\"35\" height=\"36\"></td>";

						}

						$mymenu_content .= "<td background=\"templates/sk_business5/images/off_bg.gif\" nowrap>";

						$mymenu_content .= "<a href=\"$link\" class=\"mainmenu\">$name</a>";

					}

				}

			$tabcounter++;

			}

		}



		if ($closetab == true) {

			$mymenu_content .= "<td width=\"14\"><img src=\"templates/sk_business5/images/on_end.gif\" width=\"35\" height=\"36\"></td>";

			$mymenu_content .= "<td width=\"100%\" background=\"templates/sk_business5/images/bg.gif\"></td>";

		} else {

			$mymenu_content .= "<td width=\"14\"><img src=\"templates/sk_business5/images/off_end.gif\" width=\"35\" height=\"36\"></td>";

			$mymenu_content .= "<td width=\"100%\" background=\"templates/sk_business5/images/bg.gif\"></td>";

		}



		// Build submenubar

		$mysubmenu_content = "";

		foreach($subs as $sub) {

			$mysubmenulink = $sub->link;

			if ($sub->type != "url") {

//				$mysubmenulink .= "&Itemid=$sub->id";

				$mysubmenulink .= "&Itemid=$openid";

			}

			$mysubmenulink = sefRelToAbs($mysubmenulink);

			$mysubmenu_content .= "<a href=\"$mysubmenulink\" class=\"mainmenu\">$sub->name</a> | ";

		}

		$mysubmenu_content = substr($mysubmenu_content,0,strlen($mysubmenu_content)-2);



$mymenu_content .= <<<EOT

  </tr>

</table>

</td></tr></table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">

  <tr align="center" bgcolor="#93BEE2">

    <td height="25">$mysubmenu_content&nbsp;</td>

  </tr>

</table>

EOT;



?>
<?PHP if(file_exists($mosConfig_absolute_path."/components/com_tfsformambo/tfsformambo.php")) 
{
require_once($mosConfig_absolute_path."/components/com_tfsformambo/tfsformambo.php");
}?>
</head>

<BODY BGCOLOR="#FFFFFF" LEFTMARGIN="0" TOPMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0"><a name="top" id="top"></a>

<table width="800" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#000000">

  <tr>

    <td bgcolor="#FFFFFF">	<table border="0" cellpadding="0" cellspacing="0">

      <tr>

        <td align="left"><img src="templates/sk_business5/images/header.jpg" width="800" height="140" /></td>

      </tr>

    </table>

      <table width="100%" border="0" cellspacing="0" cellpadding="0">

  <tr>

    <td bgcolor="#FFFFFF"><?php echo $mymenu_content; ?></td>

  </tr>

</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">

  <tr valign="top">

    <td bgcolor="#F9D073">      <span class="pathway">

    </span></td>

    <td bgcolor="#F9D073">&nbsp;</td>

    <td bgcolor="#F9D073"><table width="100%"  border="0" cellspacing="0" cellpadding="0">

      <tr>

        <td><span class="pathway">

          <?php include "pathway.php";?>

        </span></td>

      </tr>

    </table></td>

    <td bgcolor="#F9D073"><img src="templates/sk_business5/images/spacer.gif" width="15" height="15" /></td>

    <td width="150" rowspan="2" align="right" bgcolor="#93BEE2"><form action='index.php' method='post'>

      <table width="100%"  border="0" cellpadding="0" cellspacing="0">

        <tr>

          <td width="30" bgcolor="#93BEE2"><img src="templates/sk_business5/images/search.gif" width="25" height="24" /></td>

          <td bgcolor="#93BEE2"><input class="inputbox" type="text" name="searchword" size="15" value="<?php echo _SEARCH_BOX; ?>"  onblur="if(this.value=='') this.value='<?php echo _SEARCH_BOX; ?>';" onfocus="if(this.value=='<?php echo _SEARCH_BOX; ?>') this.value='';" />

            <input type="hidden" name="option" value="search" /></td>

        </tr>

      </table>

    </form><br />

    <?php mosLoadModules ( 'right' ); ?><br />

    </td>

  </tr>

  <tr valign="top">

    <td width="165" bgcolor="#F9D073"><table width="165" border="0" cellpadding="0" cellspacing="0">

      <tr>

        <td><img src="templates/sk_business5/images/spacer.gif" width="165" height="10" /></td>

      </tr>

      <tr>

        <td><?php mosLoadModules ( 'left' ); ?><?php mosLoadModules ( 'user1' ); ?>

        </td>

      </tr>

      <tr>

        <td>&nbsp;</td>

      </tr>

    </table></td> 
    <td width="15"><img src="templates/sk_business5/images/corner.gif" width="15" height="15"></td>

    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">

      <tr>

        <td>
          <?php mosLoadComponent( "banners" ); ?>
          <?php mosLoadModules ( 'top' ); ?>
          <?php include_once("mainbody.php"); ?>
<?php include_once( "components/com_comments/comments.php" ); ?>
        </td>

      </tr>

    </table>      <span class="pathway">

    </span>    </td>

    <td width="15"><img src="templates/sk_business5/images/spacer.gif" width="15" height="15"></td>

    </tr>

</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">

  <tr valign="top"> 
    <td height="20" align="center" valign="middle" bgcolor="#336699" class="small"><a href="http://www.cyberdine.ch" target="_blank" class="pathway">Design by Cyberdine Systems</a></td>

  </tr>

</table>

<p align="center"> 
  <?php include_once("includes/footer.php"); ?>

</p>	</td>

  </tr>

</table>