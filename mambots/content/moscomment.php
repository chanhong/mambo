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

$_MAMBOTS->registerFunction( 'onPrepareContent', 'botMosComment' );

function botMosComment( $published, &$row, &$params, $page=0 ) {

  global $database, $mainframe, $option, $task;
  global $mosConfig_lang, $mosConfig_absolute_path, $mosConfig_live_site, $mosConfig_usecaptcha, $my, $Itemid;

  # Load configuration file
  require($mosConfig_absolute_path."/administrator/components/com_comment/config.comment.php");

  $seclistarray = explode (",", $allow_comments_in_sections);
  if (in_array ($row->sectionid, $seclistarray)) {

    # count number of comments
    $database->setQuery( "SELECT count(*) FROM #__comment WHERE articleid='$row->id' AND published='1'" );
    $total = $database->loadResult();

    if ($option=='com_content' AND $task=='view' AND !$params->get( 'intro_only' )) {

      # Check if valid user
      $is_user   = (strtolower($my->usertype) <> '');

      if ( $total >= 0 ) {
        $comments = "<hr/ >";
        $comments .= "<table width='100%' border='0' cellspacing='1' cellpadding='4'>";
        $comments .= "<tr><td class='sectiontableheader'>".T_('User Comments')."</td></tr>";
        $database->setQuery( "SELECT id as mcid, name as mcname, startdate as mcdate, comments as mccomment FROM #__comment WHERE articleid='$row->id' AND published='1'" );
        $mcrows = $database->loadObjectList();
        for ($i=0, $n=count( $mcrows ); $i < $n; $i++) {
            $mcrow = &$mcrows[$i];
            $mcrow->mcname = stripslashes($mcrow->mcname);
            $message = stripslashes(preg_replace("/(\015\012)|(\015)|(\012)/","&nbsp;<br />", $mcrow->mccomment));  
            $comments .= "<tr class='sectiontableentry'><td valign='top'>";
            $comments .= "<hr/ >";
            $comments .= "<span class='small'>".T_('Comment by')." ".$mcrow->mcname." ".T_('on')." ".$mcrow->mcdate ."</span><br />";
            $comments .= $message;
            if(($i+1)%$comments_per_page == 0 && ($i+1) < $n) {
                $comments .= "</td></tr></table></p>";
                $comments .= "{mospagebreak}";
                $comments .= "<table border='0' cellspacing='1' cellpadding='4'>";
                $comments .= "<tr><td class='sectiontableheader'>".T_('Comments')."</td></tr>";
            }else {
                $comments .= "</td></tr>";
            }
        }
        $comments .= "</table>";
      }

      # show comments form depending on config permissions
      if (!$allow_anonymous_entries AND !$is_user) {      
        $comment_form = T_('Please login or register to add comments').'</p>';
      } else {      
        // replace previously written comment if it exists in case user
        // has simply mistyped or misread captcha code
        if (isset($_GET['comments'])) $curr_com = $_GET['comments'];
        else $curr_com = '';                
        $comment_form = '<FORM NAME="commentform" ACTION="index.php" METHOD="post">';
        $comment_form .= "<INPUT TYPE='hidden' NAME='option' value='com_comment'>";
        $comment_form .= "<INPUT TYPE='hidden' NAME='mcitemid' value='$Itemid'>";
        $comment_form .= "<INPUT TYPE='hidden' NAME='articleid' value='$row->id'>";
        $comment_form .= "<INPUT TYPE='hidden' NAME='func' value='entry'>";
        $comment_form .= "<INPUT TYPE='hidden' NAME='limit' value='".mosGetParam( $_GET, 'limit', '' )."'>";
        $comment_form .= "<INPUT TYPE='hidden' NAME='limitstart' value='".mosGetParam( $_GET, 'limitstart', '' )."'>";
        if ($my->username) {
          $comment_form .= "<INPUT TYPE='hidden' NAME='mcname' value='$my->username'>";
        } else {
          $comment_form .= "<INPUT TYPE='hidden' NAME='mcname' value='".T_('GUEST')."'>";
        }
        $comment_form .= "<TEXTAREA style='width:75%;' ROWS='8' NAME='comments' class='inputbox' wrap='VIRTUAL'>" .$curr_com. "</TEXTAREA>";
        
        if ($mosConfig_usecaptcha == '1') {
        	$cflink = 'index.php?option=com_comment&task=captcha-audio&id='.$row->id.'&Itemid='.$Itemid;
            $comment_form .= "<br />".T_('Security Check. Please enter this code')."<INPUT TYPE='text' NAME='spamstop' maxlength='5' size='5' class='inputbox' title=''> ";
            $comment_form .= "<img src='./includes/captcha.php' border='0' title='' alt='' align='absmiddle' />";
            $comment_form .= "<a href='".sefRelToAbs($cflink)."' target='_blank'>".T_("Listen to code")."</a>";
        }

        $comment_form .= "<br /><input name='go' type='submit' value='".T_('Add Comment')."'><br /></FORM>";
      }

        # show comments then comments form
        if (in_array ($row->sectionid, $seclistarray)) {
        $row->text = $row->text.$comments.$comment_form;
        }

    # If we are not on the content page itself
    } elseif (in_array ($row->sectionid, $seclistarray)) {
        
        # count number of pages
        $regex = '/{(mospagebreak)\s*(.*?)}/i';     
        $matches = array();
        preg_match_all( $regex, $row->text, $matches, PREG_SET_ORDER );
        $botLimitStart = count($matches);
        
        $link = "index.php?option=com_content&task=view&id=$row->id";
        $link .= "&Itemid=";
        $link .= $Itemid ? $Itemid : 1;
        $link .= $botLimitStart > 0 ? ('&limit=1&limitstart='.$botLimitStart) : '';
        
        $replacementlink = "<a class=\"readon\" href='".sefRelToAbs($link)."'>";
        $row->text = $row->text.$replacementlink.T_('Write Comment')." (".$total.T_(' comments').")</a>";       
        
    }

  }

  return true;
}

?>
