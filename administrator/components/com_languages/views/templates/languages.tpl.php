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
<?php global $mosConfig_locale;?>
<script type="text/javascript">
function submitbutton(pressbutton) {
    var form = document.adminForm;
    if(pressbutton == 'remove') {
		if(getSelectedRadio('adminForm','lang') == "en") {
			alert( "<?php echo T_('You cannot delete English.') ?>" );
		} else {
			var defaultlang=document.adminForm.defaultlang.value;
			var mylang = document.adminForm.lang;
			var candelete=true;
			for(i=0;i<mylang.length;i++) {
				if(mylang[i].checked && mylang[i].value==defaultlang) {
					candelete=false;
					alert( "<?php echo T_('You cannot delete default language.') ?>" );
					break;
				}
			}
			if(candelete)
				submitform( pressbutton );

		}
    } else if (pressbutton == 'translate')   {
        if (getSelectedRadio('adminForm','lang') == "en") {
            alert( "<?php echo T_('You cannot translate default English.') ?>" );
        } else {
            submitform( pressbutton );
        }
    }else{
        submitform( pressbutton );
    }
}
</script>


<table class="adminlist" id="lang_table" cellpadding="3" cellspacing="0" width="80%">
<thead>
<tr>
    <th style="width:10px">&nbsp;</th>
    <th><?php echo T_('Language') ?></th>
    <th><?php echo T_('Country - Region') ?></th>
    <th><?php echo T_('Default') ?></th>
    <th><?php echo T_('Character Set') ?></th>
    <th><?php echo T_('Version') ?></th>
    <th><?php echo T_('Date') ?></th>
</tr>
</thead>
<tbody>
<?php for ($i=0, $n=count( $rows ); $i < $n; $i++) :  $row = $rows[$i];?>
	<tr id="<?php echo $i;?>">
		<td style="width:10px">
		<input type="radio" id="cb<?php echo $i;?>" name="lang" value="<?php echo $row->name; ?>" onClick="isChecked(this.checked);" />
		</td>
		<td>
		<a href="#" onclick="hideMainMenu();return listItemTask('cb<?php echo $i;?>','edit')"><?php echo $row->title; ?></a>
		</td>
		<td>
		<?php echo $row->territory ?>
		</td>
		<td>
		<?php $default = (mamboCore::get('mosConfig_locale') == $row->name) ? 1 : 0; ?>
		<a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $i;?>','default')">
		<img src="images/<?php echo ( $default ) ? 'tick.png' : 'publish_x.png';?>" width="12" height="12" border="0" alt="<?php echo ( $default ) ? T_('Yes') : T_('No');?>" />
		</a>
		</td>
		<td>
		<?php echo $row->charset ?>
		</td>
		<td>
		<?php echo $row->version; ?>
		</td>
		<td>
		<?php echo $row->creationdate; ?>
		</td>
	</tr>
<?php endfor; ?>
</tbody>
</table>
<input type="hidden" name="defaultlang" value="<?php echo $mosConfig_locale;?>" />
<script type="text/javascript">
table = new Table('lang_table');  
table.makeSortable(1,"null,str,null,str,str,date");
</script>