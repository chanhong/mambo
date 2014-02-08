<?php
/**
* @package Mambo
* @subpackage Installer
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

function writableCell( $folder ) {
	echo '<tr>';
	echo '<td class="item">' . $folder . '/</td>';
	echo '<td align="left">';
	echo is_writable( $GLOBALS['mosConfig_absolute_path'] . '/' . $folder ) ? '<strong><span class="green">'.T_('Writeable').'</span></strong>' : '<strong><span class="red">'.T_('Unwriteable').'</span></strong>' . '</td>';
	echo '</tr>';
}

/**
* @package Mambo
*/
class HTML_installer {

	function showInstallForm( $title, $option, $element, $client = "", $p_startdir = "", $backLink="" ) {
		?>
		<script language="javascript" type="text/javascript">
		function submitbutton3(pressbutton) {
			var form = document.adminForm_dir;

			// do field validation
			if (form.userfile.value == ""){
				alert( "<?php echo T_('Please select a directory'); ?>" );
			} else {
				form.submit();
			}
		}
		</script>
		<form enctype="multipart/form-data" action="index2.php" method="post" name="filename">
		<table class="adminheading">
		<tr>
			<th class="install">
			<?php echo $title;?>
			</th>
			<td align="right" nowrap="true">
			<?php echo $backLink;?>
			</td>
		</tr>
		</table>

		<table class="adminform">
		<tr>
			<th>
			<?php echo T_('Upload Package File'); ?>
			</th>
		</tr>
		<tr>
			<td align="left">
			<?php echo T_('Package File:')?>
			<input class="text_area" name="userfile" type="file" size="70"/>
			<input class="button" type="submit" value="<?php echo T_('Upload File &amp; Install')?>" />
			</td>
		</tr>
		</table>

		<input type="hidden" name="task" value="uploadfile"/>
		<input type="hidden" name="option" value="<?php echo $option;?>"/>
		<input type="hidden" name="element" value="<?php echo $element;?>"/>
		<input type="hidden" name="client" value="<?php echo $client;?>"/>
		</form>
		<br />

		<?php
		if (ini_get('allow_url_fopen')) HTML_installer::showUrlForm('http://', $option, $element, $client);
		?>
		
		<form action="index2.php" method="post" name="adminForm_dir">
		<table class="adminform">
		<tr>
			<th>
			<?php echo T_('Install from directory')?>
			</th>
		</tr>
		<tr>
			<td align="left">
			<?php echo T_('Install directory:')?>&nbsp;
			<input type="text" name="userfile" class="text_area" size="65" value="<?php echo $p_startdir; ?>"/>&nbsp;
			<input type="button" class="button" value="<?php echo T_('Install')?>" onclick="submitbutton3()" />
			</td>
		</tr>
		</table>

		<input type="hidden" name="task" value="installfromdir" />
		<input type="hidden" name="option" value="<?php echo $option;?>"/>
		<input type="hidden" name="element" value="<?php echo $element;?>"/>
		<input type="hidden" name="client" value="<?php echo $client;?>"/>
		</form>
		<br />
		<?php
	}

	function showUrlForm ($prompt, $option, $element, $client) {
		?>
		<form action="index2.php" method="post" name="adminForm_url">
		<table class="adminform">
		<tr>
			<th>
			<?php echo T_('Install from HTTP URL')?>
			</th>
		</tr>
		<tr>
			<td align="left">
			<?php echo T_('Install HTTP URL:')?>&nbsp;
			<input type="text" name="userurl[]" class="text_area" size="65" value="<?php echo $prompt; ?>" />&nbsp;
			<input type="submit" class="button" value="<?php echo T_('Upload URL &amp; Install')?>" />
			</td>
		</tr>
		</table>
		<input type="hidden" name="task" value="installfromurl" />
		<input type="hidden" name="option" value="<?php echo $option;?>"/>
		<input type="hidden" name="element" value="<?php echo $element;?>"/>
		<input type="hidden" name="client" value="<?php echo $client;?>"/>
		</form>
		<br />
		<?php
	}

	function AddonForm ($prompt, $option, $element, $client) {
		?>
        <form action="index2.php" method="post" name="adminForm_url">
  	    <?php //Load Addon XML file from The Source
			
		$fhandle = @fopen("http://source.mambo-foundation.org/external/config/addon.xml", "r");
		if (!$fhandle) {
			$error = T_('Failed to open addon.xml on The Source (source.mambo-foundation.org).  Check your Internet connection.');
			exit($error);
		} else {
   			$addon = simplexml_load_file('http://source.mambo-foundation.org/external/config/addon.xml');
		}
		
    	//Count the number of items in the XML file
    	$addon_count = 0;
   		foreach($addon->name as $i) $addon_count++; 
    	?>
  		<table width="100%" border="1" cellpadding="0"
 		cellspacing="1" class="adminform">
    	
      	<tr>
          <th colspan="7" style="text-align: left;"><?php echo T_('Add-On Installation') ?></th>
       	</tr>
      	<tr>
          <th colspan="7" style="text-align: left;"><input type="submit" name="install_packages" value="<?php echo T_('Install Package(s)') ?>" />
          <i><?php echo T_('Note: Check the package or packages you wish to install below. Any number of packages or types may be installed with a single click.') ?></i></th>
      	</tr>
        <tr style="background-color: Orange; font-weight: bold; text-align: center;">
          <td width="185"><?php echo T_('Mark for installation') ?></td>
          <td width="159"><?php echo T_('Add-On Package') ?></td>
          <td width="168"><?php echo T_('Package Description') ?></td>
          <td width="168"><?php echo T_('Author') ?></td>
          <td width="107"><?php echo T_('Latest Version'); ?></td>
          <td width="107"><?php echo T_('Installed Version'); ?></td>
          </tr>
         <!-- Display the Mambots -->
      	<tr>
      	  <td style="background-color: Yellow; font-weight: bold; text-align: center;"><strong><?php echo T_('Package Type: Mambots') ?></strong></td>
      	</tr>        
      	<?php for ( $counter = 0; $counter < $addon_count; $counter++) { 
      	if ($addon->type[$counter]=='Mambot') { ?>
      	<tr>
          <td style="text-align: center;"><div align="center">
          <input name="userurl[]" value="<?php echo $addon->url[$counter] ?>" type="checkbox" />
          </div></td>
          <td><div align="left"><?php echo $addon->name[$counter] ?></div></td>
          <td><?php echo $addon->desc[$counter] ?></td>
          <td><div align="left"><?php echo $addon->author[$counter] ?></div></td>
          <?php 
          //Find current version if installed
          $LatestVersion = $addon->version[$counter];
          $addonXMLpath = $addon->xmlfile[$counter];
          $InstalledVersion = getCurrentAddonVersion($addonXMLpath);
          ?>
          <td><?php echo $LatestVersion; ?></td>
          <td <?php if ($InstalledVersion!="Not Installed" && strcmp($InstalledVersion, $LatestVersion)<0) { 
          	echo "style=\"background-color: Red; color: White; font-weight: bold;\""; } 
          	?> >
          <?php echo $InstalledVersion; ?>
          </td>
        </tr>
 		<?php } } ?>
 		<!-- Display the Components -->
      	<tr>
      	  <td style="background-color: Yellow; font-weight: bold; text-align: center;"><strong><?php echo T_('Package Type: Components') ?></strong></td>
      	</tr>        
      	<?php for ( $counter = 0; $counter < $addon_count; $counter++) { 
      	if ($addon->type[$counter]=='Component') { ?>
      	<tr>
          <td style="text-align: center;"><div align="center">
          <input name="userurl[]" value="<?php echo $addon->url[$counter] ?>" type="checkbox" />
          </div></td>
          <td><div align="left"><?php echo $addon->name[$counter] ?></div></td>
          <td><?php echo $addon->desc[$counter] ?></td>
          <td><div align="left"><?php echo $addon->author[$counter] ?></div></td>
          <?php 
          //Find current version if installed
          $LatestVersion = $addon->version[$counter];
          $addonXMLpath = $addon->xmlfile[$counter];
          $InstalledVersion = getCurrentAddonVersion($addonXMLpath);
          ?>
          <td><?php echo $LatestVersion; ?></td>
          <td <?php if ($InstalledVersion!="Not Installed" && strcmp($InstalledVersion, $LatestVersion)<0) { 
          	echo "style=\"background-color: Red; color: White; font-weight: bold;\""; } 
          	?> >
          <?php echo $InstalledVersion; ?>
          </td>
        </tr>
 		<?php } } ?>
 		<!-- Display the Modules -->
      	<tr>
      	  <td style="background-color: Yellow; font-weight: bold; text-align: center;"><strong><?php echo T_('Package Type: Modules') ?></strong></td>
      	</tr>      
      	<?php for ( $counter = 0; $counter < $addon_count; $counter++) { 
      	if ($addon->type[$counter]=='Module') { ?>
      	<tr>
          <td style="text-align: center;"><div align="center">
          <input name="userurl[]" value="<?php echo $addon->url[$counter] ?>" type="checkbox" />
          </div></td>
          <td><div align="left"><?php echo $addon->name[$counter] ?></div></td>
          <td><?php echo $addon->desc[$counter] ?></td>
          <td><div align="left"><?php echo $addon->author[$counter] ?></div></td>
          <?php 
          //Find current version if installed
          $LatestVersion = $addon->version[$counter];
          $addonXMLpath = $addon->xmlfile[$counter];
          $InstalledVersion = getCurrentAddonVersion($addonXMLpath);
          ?>
          <td><?php echo $LatestVersion; ?></td>
          <td <?php if ($InstalledVersion!="Not Installed" && strcmp($InstalledVersion, $LatestVersion)<0) { 
          	echo "style=\"background-color: Red; color: White; font-weight: bold;\""; } 
          	?> >
          <?php echo $InstalledVersion; ?>
          </td>
        </tr>
 		<?php } } ?>
		<?php //Load Certified 3rd Party Addon XML file from The Source
   		if (fopen("http://source.mambo-foundation.org/external/config/external_addon.xml", "r")) {
		 	$exaddon = simplexml_load_file('http://source.mambo-foundation.org/external/config/external_addon.xml');
		} else {
   			exit('Failed to open external_addon.xml on The Source (source.mambo-foundation.org).');
		}
    	//Count the number of items in the XML file
    	$exaddon_count = 0;
   		foreach($exaddon->name as $i) $exaddon_count++; 
    	?>
      	<tr>
          <th colspan="7" style="text-align: left;"><?php echo T_('Peer Reviewed / Certified 3rd Party Add-On Installation')?></th>
        </tr>
        <tr style="background-color: Orange; font-weight: bold; text-align: center;">
          <td width="185"><?php echo T_('Mark for installation'); ?></td>
          <td width="159"><?php echo T_('Add-On Package'); ?></td>
          <td width="168"><?php echo T_('Package Description'); ?></td>
          <td width="168"><?php echo T_('Author'); ?></td>
          <td width="107"><?php echo T_('Latest Version'); ?></td>
          <td width="107"><?php echo T_('Installed Version'); ?></td>
      	</tr>
      	<!-- Display the Mambots -->
      	<tr>
      	  <td style="background-color: Yellow; font-weight: bold; text-align: center;"><strong><?php echo T_('Package Type: Mambots') ?></strong></td>
      	</tr> 
      	<?php 
      	//Setup variable to test for the existance of packages
      	$exmambot_count = 0;
      	for ( $counter = 0; $counter < $exaddon_count; $counter++) { 
      	if ($exaddon->type[$counter]=='Mambot') { 
      	//If we are here then 3rd party mambots exist
      	$exmambot_count = 1;
      	?>
      	<tr>
          <td style="text-align: center;"><div align="center">
          <input name="userurl" value="<?php echo $exaddon->url[$counter] ?>" type="checkbox">
          </div></td>
          <td><div align="left"><?php echo $exaddon->name[$counter] ?></div></td>
          <td><?php echo $exaddon->desc[$counter] ?></td>
          <td><div align="left"><?php echo $exaddon->author[$counter] ?></div></td>
          <td><?php echo $exaddon->version[$counter] ?></td>
        </tr>
 		<?php } } 
 		//If no 3rd party mambots exist then display the message below
 		if ($exmambot_count == 0) {?>
 		<tr>
 		<td></td>
 		<td>
 		<?php 
 		echo T_('There are no qualifying packages at this time') ?></td>
 		</tr>
        <?php } ?>
 		<!-- Display the Components -->
      	<tr>
      	  <td style="background-color: Yellow; font-weight: bold; text-align: center;"><strong><?php echo T_('Package Type: Components') ?></strong></td>
      	</tr> 
      	<?php 
      	//Setup variable to test for the existance of packages
      	$excomponent_count = 0;
      	for ( $counter = 0; $counter < $exaddon_count; $counter++) { 
      	if ($exaddon->type[$counter]=='Component') { 
      	//If we are here then 3rd party components exist
      	$excomponent_count = 1;
      	?>
      	<tr>
          <td style="text-align: center;"><div align="center">
          <input name="userurl" value="<?php echo $exaddon->url[$counter] ?>" type="checkbox">
          </div></td>
          <td><div align="left"><?php echo $exaddon->name[$counter] ?></div></td>
          <td><?php echo $exaddon->desc[$counter] ?></td>
          <td><div align="left"><?php echo $exaddon->author[$counter] ?></div></td>
          <td><?php echo $exaddon->version[$counter] ?></td>
        </tr>
 		<?php } } 
 		//If no 3rd party components exist then display the message below
 		if ($excomponent_count == 0) {?>
 		<tr>
 		<td></td>
 		<td><?php echo T_('There are no qualifying packages at this time') ?></td>
 		</tr>
        <?php } ?>
 		<!-- Display the Modules -->
      	<tr>
      	  <td style="background-color: Yellow; font-weight: bold; text-align: center;"><strong><?php echo T_('Package Type: Modules') ?></strong></td>
      	</tr> 
      	<?php 
      	//Setup variable to test for the existance of packages
      	$exmodule_count = 0;
      	for ( $counter = 0; $counter < $exaddon_count; $counter++) { 
      	if ($exaddon->type[$counter]=='Module') { 
      	//If we are here then 3rd party modules exist
      	$exmodule_count = 1;
      	?>
      	<tr>
          <td style="text-align: center;"><div align="center">
          <input name="userurl" value="<?php echo $exaddon->url[$counter] ?>" type="checkbox">
          </div></td>
          <td><div align="left"><?php echo $exaddon->name[$counter] ?></div></td>
          <td><?php echo $exaddon->desc[$counter] ?></td>
          <td><div align="left"><?php echo $exaddon->author[$counter] ?></div></td>
          <td><?php echo $exaddon->version[$counter] ?></td>
        </tr>
 		<?php } } 
 		//If no 3rd party modules exist then display the message below
 		if ($exmodule_count == 0) {?>
 		<tr>
 		<td></td>
 		<td><?php echo T_('There are no qualifying packages at this time') ?></td>
 		</tr>
        <?php } ?>
  		</table>
  		<input name="task" value="installfromurl" type="hidden" />
  		<input name="option" value="<?php echo $option;?>" type="hidden" />
  		<input name="element" value="<?php echo $element;?>" type="hidden" />
  		<input name="client" value="<?php echo $client;?>" type="hidden" />
		</form> 
		<br />
		<?php
	}
	
	function theSourceForm ($option, $element, $client) {
	    HTML_installer::showUrlForm('', $option, $element, $client);
	    echo '<object type="text/html" data="http://source.mambo-foundation.org/component/syndstyle/option,com_remository/" width="500" height="1000">'
	        .T_('Sorry, it seems that The Source is not available').'</object>';
	}

	/**
	* @param string
	* @param string
	* @param string
	* @param string
	*/
	function showInstallMessage ($messages, $title, $return) {
		global $PHP_SELF;
		if (!$return) $return = mamboCore::get('mosConfig_live_site').'/administrator/index2.php';
		?>
		<table class="adminheading">
		<tr>
			<th class="install">
			<?php echo $title; ?>
			</th>
		</tr>
		</table>

		<table class="adminform">
		<?php
		if (!is_array($messages)) $messages = array($messages);
		foreach ($messages as $message) {
			switch ($message->level) {
				case _MOS_ERROR_INFORM:
				$colour = 'green';
				$level = 'Information: ';
				break;
				case _MOS_ERROR_WARN:
				$colour = 'red';
				$level = 'Warning: ';
				break;
				case _MOS_ERROR_SEVERE:
				$colour = 'red';
				$level = 'Severe: ';
				break;
				case _MOS_ERROR_FATAL:
				$colour = 'red';
				$level = 'Fatal: ';
				break;
			}
			?>
			<tr>
				<td align="left">
				<span class="<?php echo $colour; ?>"><strong><?php echo $level.$message->text; ?></strong></span>
				</td>
		</tr>
		<?php
		}
		?>
		<tr>
			<td colspan="2" align="center">
			[&nbsp;<a href="<?php echo $return;?>" style="font-size: 16px; font-weight: bold"><?php echo T_('Continue ...')?></a>&nbsp;]
			</td>
		</tr>
		</table>
		<?php
	}
}
?>
