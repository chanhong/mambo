<?php 
/**
* @package Mambo
* @subpackage Languages
* @author Mambo Foundation Inc see README.php
* @copyright Mambo Foundation Inc.
* See COPYRIGHT.php for copyright notices and details.
* @license GNU/GPL Version 2, see LICENSE.php
* Mambo is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; version 2 of the License.
*/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<style type="text/css">
.sortbutton {
	border-top : solid 1px #3872B2;
	border-right : solid 1px #3872B2;
	border-bottom : solid 1px #3872B2;
	border-left : solid 1px #fff;
	background-color : #3872B2;
	color : #FFF;
	font-weight : bold;
	height : 2em;
	width : 100%;
	font-size : .9em;
	cursor: pointer;
}
</style>
<style type="text/css">
table.adminlist th {text-align:left;   
                	margin: 0px;
                	padding: 0px;
                	height: 25px;
                	font-size: 11px;
                	color: #ffffff;
                   }
</style>
<script type="text/javascript" src="<?php echo mamboCore::get('mosConfig_live_site');?>/administrator/components/com_languages/tables.js"></script>

<?php if (isset($mosmsg)) : ?><div class="message"><?php echo $mosmsg ?></div> <?php endif; ?>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" name="adminForm">
<input type="hidden" name="option" value="com_languages" />
<input type="hidden" name="task" value="<?php echo $task ?>" />
<input type="hidden" name="act" value="<?php echo $act ?>" />
<input type="hidden" name="hidemainmenu" value="0" />
<input type="hidden" name="boxchecked" value="0" />

<table class="adminheading">
	<tr>
		<th class="langmanager">
		<?php echo isset($header) ? $header : T_('Mambo Language Editor') ?>
		</th> 
		<?php /*<!-- 
        <td>
        <?php if ($task == 'index'): ? >
           <select name="lang" class="inputbox" size="1" onchange="document.adminForm.submit();">
            	<option value="en">< ?php echo T_('Select Language') ? ></option>            	
            < ?php foreach ($languages as $name => $obj): ? >        
            <option value="< ?php echo $name.'"';? > < ?php echo $lang==$name?' selected="selected"':''? >>< ?php echo $obj->title; if (!empty($obj->territory)) echo ' ('.$obj->territory.')' ?></option>  
            < ?php endforeach; ? >
           </select>
       < ?php endif; ? > 
       </td> -->
	   <?php */ ?>
	</tr>
</table>
<br />
<?php echo isset($content) ? $content : T_('No content to display.'); ?>
</form>