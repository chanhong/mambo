<?php
/**
* @package Mambo
* @subpackage Admin
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

class HTML_admin_misc {

	/**
	* Check Version, Patches, and Messages
	*/
	function version_info( ) {
		global $mosConfig_absolute_path;
		?>
		<table class="adminform" border="1">
		<tr>
			<th colspan="2" class="title">
			<?php echo T_('Mambo Updates'); ?>
			</th>
		</tr>
		<tr>
			<td colspan="2">
				<table width="100%">
				<tr>
					<td>
					<?php //Load local install version file
					if (file_exists($mosConfig_absolute_path . '/administrator/components/com_admin/version.xml')) {
   						$local = simplexml_load_file($mosConfig_absolute_path . '/administrator/components/com_admin/version.xml');
					} else {
   						exit("Failed to open $mosConfig_absolute_path . '/administrator/components/com_admin/version.xml'.");
					}

					//Load server version file
					if (@fopen("http://source.mambo-foundation.org/external/config/main_version.xml", "r")) {
						$server = simplexml_load_file('http://source.mambo-foundation.org/external/config/main_version.xml');
					} else {
   						exit('Failed to open main_version.xml on The Source (source.mambo-foundation.org).');
					}

					//Build output & check version
					echo '<strong>' . T_('Mambo Version Information') . ':</strong><BR />';
					if (strcmp($local->version, $server->version)==0) {
						echo T_('Your Mambo version is up to date') . '<BR />';
						echo T_('The current stable version is') . " <strong>$server->version</strong><BR />";
					}
					else {
						echo T_('Your Mambo version is out of date.  We recommend you') . "<a href='http://sourceforge.net/project/showfiles.php?group_id=25577'>" . ' ' . T_('upgrade') . '</a>.<BR />';
    					echo T_('The current stable version is') . " <strong>$server->version</strong><BR />";
						echo T_('Your version is') . " <strong>$local->version</strong><BR />";
					}
					//Check patch
					echo  '<BR /><strong>' . T_('Mambo Security Patch Information') . ':</strong><BR />';
					if ($server->patch == '') {
						echo T_('No patches have been released') . '<BR />';
					}
					elseif ($local->patch == $server->patch) {
						echo T_('Your Mambo install has the latest recommended patch') . '<BR />';
						echo T_('The recommended patch level is') . ": <strong>$local->patch</strong><BR />";
					} 
					else {
						echo T_('Your Mambo install does not have the latest recommended security patch.  We recommend you apply the latest') . "<a href='http://sourceforge.net/project/showfiles.php?group_id=25577'>" . ' ' . T_('patch') . '</a>.<BR />';
    					echo T_('The recommended patch level is') . ": <strong>$server->patch</strong><BR />";
    					if ($local->patch == '') {
    					  echo T_('Your patch level is') . ': <strong>' . T_('No patches applied') . '</strong><BR />';
    					} 
    					else {
						  echo T_('Your patch level is') . ": <strong>$local->patch</strong><BR />";
					    }
					}
					//Check messages
					echo '<BR /><strong>' . T_('Additional Messages') . ':</strong><BR />';
					if ($server->message == ''){
						echo T_('There are no messages at this time');
					}
					else {
						print $server->message;
					} ?>
					</td>
				</tr>
					<tr>
			<th colspan="2" class="title">
			</th>
		</tr>
		</table>
    <?php } 
	
	/**
	* Control panel
	*/
	function controlPanel() {
	    global $mosConfig_absolute_path, $mainframe;
		?>
		<table class="adminheading" border="0">
		<tr>
			<th class="cpanel">
			<?php echo T_('Home') ?>
			</th>
		</tr>
		</table>
		<table width="100%" class="adminheading">
		<tr>
		<?php if($_SESSION['simple_editing'] == 'on' )
		{
			$_SESSION['simple_editing'] = 'on';
		?>
		<td align="left" width="20%">&nbsp;</td><td align="left" ><a class="selected" href="index2.php?option=simple_mode" title="<?php echo T_('Simple Mode') ?> (<?php echo T_('selected') ?>)"><?php echo T_('Simple Mode') ?></a> / <a class="unselected" href="index2.php?option=advanced_mode" title="<?php echo T_('Advanced Mode') ?> (<?php echo T_('unselected') ?>)"><?php echo T_('Advanced Mode') ?></a></td>
		<?php }else{
			$_SESSION['simple_editing'] = 'off';
		?>
		<td align="left" width="20%">&nbsp;</td><td align="left" ><a class="unselected"href="index2.php?option=simple_mode" title="<?php echo T_('Simple Mode') ?> (<?php echo T_('unselected') ?>)" ><?php echo T_('Simple Mode') ?></a> / <a class="selected" href="index2.php?option=advanced_mode" title="<?php echo T_('Advanced Mode') ?> (<?php echo T_('selected') ?>)"><?php echo T_('Advanced Mode') ?></a></td>
		<?php }?>
		</tr>
		</table>
		<?php
		$path = $mosConfig_absolute_path . '/administrator/templates/' . $mainframe->getTemplate() . '/cpanel.php';
		if (file_exists( $path )) {
		    require $path;
		} else {
		    echo '<br />';
			mosLoadAdminModules( 'cpanel', 1 );
		}
	}

	function get_php_setting($val) {
		$r =  (ini_get($val) == '1' ? 1 : 0);
		return $r ? 'ON' : 'OFF';
	}

	function get_server_software() {
		if (isset($_SERVER['SERVER_SOFTWARE'])) {
			return $_SERVER['SERVER_SOFTWARE'];
		} else if (($sf = getenv('SERVER_SOFTWARE'))) {
			return $sf;
		} else {
			return 'n/a';
		}
	}

	function system_info( $version ) {
		global $mosConfig_absolute_path, $database;
		//$tab = mosGetParam( $_REQUEST, 'tab', 'tab1' );
		$width = 400;	// width of 100%
		$tabs = new mosTabs(0);
		?>

		<table class="adminheading">
		<tr>
			<th class="info">
			<?php echo T_('Information') ?>
			</th>
		</tr>
		</table>
		<?php
		$tabs->startPane("sysinfo");
		$tabs->startTab(T_("System Info"),"system-page");
		?>
		<table class="adminform">
		<tr>
			<th colspan="2">
			<?php echo T_('System Information') ?>
			</th>
		</tr>
		<tr>
			<td valign="top" width="250">
			<strong>
			<?php echo T_('PHP built On:') ?>
			</strong>
			</td>
			<td>
			<?php echo php_uname(); ?>
			</td>
		</tr>
		<tr>
			<td>
			<strong>
			<?php echo T_('Database Version:') ?>
			</strong>
			</td>
			<td>
			<?php echo mysql_get_server_info(); ?>
			</td>
		</tr>
		<tr>
			<td>
			<strong>
			<?php echo T_('PHP Version:') ?>
			</strong>
			</td>
			<td>
			<?php echo phpversion(); ?>
			</td>
		</tr>
		<tr>
			<td>
			<strong>
			<?php echo T_('Web Server:') ?>
			</strong>
			</td>
			<td>
			<?php echo HTML_admin_misc::get_server_software(); ?>
			</td>
		</tr>
		<tr>
			<td>
			<strong>
			<?php echo T_('WebServer to PHP interface:') ?>
			</strong>
			</td>
			<td>
			<?php echo php_sapi_name(); ?>
			</td>
		</tr>
		<tr>
			<td>
			<strong>
			<?php echo T_('Mambo Version:') ?>
			</strong>
			</td>
			<td>
			<?php echo $version; ?>
			</td>
		</tr>
		<tr>
			<td>
			<strong>
			<?php echo T_('User Agent:') ?>
			</strong>
			</td>
			<td>
			<?php echo phpversion() <= "4.2.1" ? getenv( "HTTP_USER_AGENT" ) : $_SERVER['HTTP_USER_AGENT'];?>
			</td>
		</tr>
		<tr>
			<td valign="top">
			<strong>
			<?php echo T_('Relevant PHP Settings:') ?>
			</strong>
			</td>
			<td>
				<table cellspacing="1" cellpadding="1" border="0">
				<tr>
					<td>
					<?php echo T_('Safe Mode:') ?>
					</td>
					<td>
					<?php echo HTML_admin_misc::get_php_setting('safe_mode'); ?>
					</td>
				</tr>
				<tr>
					<td>
					<?php echo T_('Open basedir:') ?>
					</td>
					<td>
					<?php echo (($ob = ini_get('open_basedir')) ? $ob : 'none'); ?>
					</td>
				</tr>
				<tr>
					<td>
					<?php echo T_('Display Errors:') ?>
					</td>
					<td>
					<?php echo HTML_admin_misc::get_php_setting('display_errors'); ?>
					</td>
				</tr>
				<tr>
					<td>
					<?php echo T_('Short Open Tags:') ?>
					</td>
					<td>
					<?php echo HTML_admin_misc::get_php_setting('short_open_tag'); ?>
					</td>
				</tr>
				<tr>
					<td>
					<?php echo T_('File Uploads:') ?>
					</td>
					<td>
					<?php echo HTML_admin_misc::get_php_setting('file_uploads'); ?>
					</td>
				</tr>
				<tr>
					<td>
					<?php echo T_('Magic Quotes') ?>:
					</td>
					<td>
					<?php echo HTML_admin_misc::get_php_setting('magic_quotes_gpc'); ?>
					</td>
				</tr>
				<tr>
					<td>
					<?php echo T_('Register Globals:') ?>
					</td>
					<td>
					<?php echo HTML_admin_misc::get_php_setting('register_globals'); ?>
					</td>
				</tr>
				<tr>
					<td>
					<?php echo T_('Output Buffering:') ?>
					</td>
					<td>
					<?php echo HTML_admin_misc::get_php_setting('output_buffering'); ?>
					</td>
				</tr>
				<tr>
					<td>
					<?php echo T_('Session save path:') ?>
					</td>
					<td>
					<?php echo (($sp=ini_get('session.save_path'))?$sp:'none'); ?>
					</td>
				</tr>
				<tr>
					<td>
					<?php echo T_('Session auto start:') ?>
					</td>
					<td>
					<?php echo intval( ini_get( 'session.auto_start' ) ); ?>
					</td>
				</tr>
				<tr>
					<td>
					<?php echo T_('XML enabled:') ?>
					</td>
					<td>
					<?php echo extension_loaded('xml')?'Yes':'No'; ?>
					</td>
				</tr>
				<tr>
					<td>
					<?php echo T_('Zlib enabled:') ?>
					</td>
					<td>
					<?php echo extension_loaded('zlib')?'Yes':'No'; ?>
					</td>
				</tr>
				<tr>
					<td>
					<?php echo T_('Disabled Functions:') ?>
					</td>
					<td>
					<?php echo (($df=ini_get('disable_functions'))?$df:'none'); ?>
					</td>
				</tr>
				<?php
				$query = "SELECT name FROM #__mambots"
				. "\nWHERE folder='editors' AND published='1'"
				. "\nLIMIT 1";
				$database->setQuery( $query );
				$editor = $database->loadResult();
				?>
				<tr>
					<td>
					<?php echo T_('WYSIWYG Editor:') ?>
					</td>
					<td>
					<?php echo $editor; ?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td valign="top">
			<strong>
			<?php echo T_('Configuration File:') ?>
			</strong>
			</td>
			<td>
			<?php
			$cf = file( $mosConfig_absolute_path . '/configuration.php' );
			foreach ($cf as $k=>$v) {
				if (eregi( 'mosConfig_host', $v)) {
					$cf[$k] = '$mosConfig_host = \'xxxxxx\'';
				} else if (eregi( 'mosConfig_user', $v)) {
					$cf[$k] = '$mosConfig_user = \'xxxxxx\'';
				} else if (eregi( 'mosConfig_password', $v)) {
					$cf[$k] = '$mosConfig_password = \'xxxxxx\'';
				} else if (eregi( 'mosConfig_db ', $v)) {
					$cf[$k] = '$mosConfig_db = \'xxxxxx\'';
				} else if (eregi( '<?php', $v)) {
					$cf[$k] = '&lt;?php';
				}
			}
			echo implode( "<br />", $cf );
			?>
			</td>
		</tr>
		</table>
		<?php
		$tabs->endTab();
		$tabs->startTab(T_("PHP Info"),"php-page");
		?>
		<table class="adminform">
		<tr>
			<th colspan="2">
			<?php echo T_('PHP Information') ?>
			</th>
		</tr>
		<tr>
			<td>
			<?php
			ob_start();
			phpinfo(INFO_GENERAL | INFO_CONFIGURATION | INFO_MODULES);
			$phpinfo = ob_get_contents();
			ob_end_clean();
			preg_match_all('#<body[^>]*>(.*)</body>#siU', $phpinfo, $output);
			$output = preg_replace('#<table#', '<table class="adminlist" align="center"', $output[1][0]);
			$output = preg_replace('#(\w),(\w)#', '\1, \2', $output);
			$output = preg_replace('#border="0" cellpadding="3" width="600"#', 'border="0" cellspacing="1" cellpadding="4" width="95%"', $output);
			$output = preg_replace('#<hr />#', '', $output);
			echo $output;
			?>
			</td>
		</tr>
		</table>
		<?php
		$tabs->endTab();
		$tabs->startTab(T_('Permissions'),'perms');
		?>
		<table class="adminform">
          <tr>
            <th colspan="2"> <?php echo T_('Directory Permissions') ?></th>
          </tr>
          <tr>
            <td>
        <strong><?php echo T_('For all Mambo functions and features to work ALL of the following directories should be writeable:') ?></strong>
			<?php
mosHTML::writableCell( 'administrator/backups' );
mosHTML::writableCell( 'administrator/components' );
mosHTML::writableCell( 'administrator/modules' );
mosHTML::writableCell( 'administrator/templates' );
mosHTML::writableCell( 'cache' );
mosHTML::writableCell( 'components' );
mosHTML::writableCell( 'images' );
mosHTML::writableCell( 'images/banners' );
mosHTML::writableCell( 'images/stories' );
mosHTML::writableCell( 'language' );
mosHTML::writableCell( 'mambots' );
mosHTML::writableCell( 'mambots/content' );
mosHTML::writableCell( 'mambots/editors' );
mosHTML::writableCell( 'mambots/editors-xtd' );
mosHTML::writableCell( 'mambots/search' );
mosHTML::writableCell( 'media' );
mosHTML::writableCell( 'modules' );
mosHTML::writableCell( 'templates' );

?>

            </td>
          </tr>
        </table>
		<?php
		$tabs->endTab();
		$tabs->endPane();
		?>
		<?php
	}

	function ListComponents() {
			mosLoadAdminModule( 'components' );
		}

	/**
	* Display Help Page
	*/
	function help() {
		global $mosConfig_live_site;
		$helpurl = mosGetParam( $GLOBALS, 'mosConfig_helpurl', '' );
		$helpurl = false;
		$fullhelpurl = $helpurl . '/index2.php?option=com_content&amp;task=findkey&pop=1&keyref=';
		$fullhelpurl = $mosConfig_live_site.'/help/';

		$helpsearch = mosGetParam( $_REQUEST, 'helpsearch', '' );
		$page 		= mosGetParam( $_REQUEST, 'page', 'mambo.whatsnew.html' );
		$toc 		= getHelpToc( $helpsearch );
		if (!eregi( '\.html$', $page )) {
			$page .= '.xml';
		}
		?>
		<style type="text/css">
		.helpIndex {
			border: 0px;
			width: 95%;
			height: 100%;
			padding: 0px 5px 0px 10px;
			overflow: auto;
		}
		.helpFrame {
			border-left: 0px solid #222;
			border-right: none;
			border-top: none;
			border-bottom: none;
			width: 100%;
			height: 700px;
			padding: 0px 5px 0px 10px;
		}
		</style>
		<form name="adminForm" action="">
		<table class="adminform" border="1">
		<tr>
			<th colspan="2" class="title">
			<?php echo T_('Help') ?>
			</th>
		</tr>
		<tr>
			<td colspan="2">
				<table width="100%">
				<tr>
					<td>
					<strong><?php echo T_('Search:') ?></strong>
					<input class="text_area" type="hidden" name="option" value="com_admin" />
					<input type="text" name="helpsearch" value="<?php echo $helpsearch;?>" class="inputbox" />
					<input type="submit" value="<?php echo T_('Go') ?>" class="button" />
					<input type="button" value="<?php echo T_('Clear Results') ?>" class="button" onclick="f=document.adminForm;f.helpsearch.value='';f.submit()" />
					</td>
					<td style="text-align:right">
					<?php
					if ($helpurl) {
					?>
					<a href="<?php echo $fullhelpurl;?>mambo.glossary" target="helpFrame">
						<?php echo T_('Glossary') ?></a>
					|
					<a href="<?php echo $fullhelpurl;?>mambo.credits" target="helpFrame">
						<?php echo T_('Credits') ?></a>
					|
					<a href="<?php echo $fullhelpurl;?>mambo.support" target="helpFrame">
						<?php echo T_('Support') ?></a>
					<?php
					} else {
					?>
					<a href="<?php echo $mosConfig_live_site;?>/help/mambo.glossary.html" target="helpFrame">
						<?php echo T_('Glossary') ?></a>
					|
					<a href="<?php echo $mosConfig_live_site;?>/help/mambo.credits.html" target="helpFrame">
						<?php echo T_('Credits') ?></a>
					|
					<a href="<?php echo $mosConfig_live_site;?>/help/mambo.support.html" target="helpFrame">
						<?php echo T_('Support') ?></a>
					<?php
					}
					?>
					|
					<a href="http://www.gnu.org/copyleft/gpl.html" target="helpFrame">
						<?php echo T_('License') ?></a>
					|
					<a href="http://docs.mambo-foundation.org" target="_blank">
						docs.mambo-foundation.org</a>
					|
					<a href="<?php echo $mosConfig_live_site;?>/administrator/index2.php?option=com_admin&task=sysinfo&no_html=1" target="helpFrame">
						<?php echo T_('System Info') ?></a>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr valign="top">
			<td width="20%" valign="top">
			<?php if ($helpsearch):?>
				<strong><?php echo T_('Search Results') ?></strong>
			<?php else :?>
				<strong><?php echo T_('Index') ?></strong>
		    <?php endif;?>
				<div class="helpIndex">
				<?php
				foreach ($toc as $k=>$v) {
					if ($helpurl) {
						echo '<br /><a href="' . $fullhelpurl . urlencode( $k ) . '" target="helpFrame">' . $v . '</a>';
					} else {
						echo '<br /><a href="' . $mosConfig_live_site . '/help/' . $k . '" target="helpFrame">' . $v . '</a>';
					}
				}
				?>
				</div>
			</td>
			<td valign="top">
				<iframe name="helpFrame" src="<?php echo $mosConfig_live_site . '/help/' . $page;?>" class="helpFrame" frameborder="0" /></iframe>
			</td>
		</tr>
		</table>

		<input type="hidden" name="task" value="help" />
		</form>
		<?php
	}

	/**
	* Preview site
	*/
	function preview( $tp=0 ) {
	    global $mosConfig_live_site;
	    $tp = intval( $tp );
		?>
		<style type="text/css">
		.previewFrame {
			border: none;
			width: 95%;
			height: 600px;
			padding: 0px 5px 0px 10px;
		}
		</style>
		<table class="adminform">
		<tr>
			<th width="50%" class="title">
			<?php echo T_('Site Preview') ?>
			</th>
			<th width="50%" style="text-align:right">
			<a href="<?php echo $mosConfig_live_site . '/index.php?tp=' . $tp;?>" target="_blank">
			<?php echo T_('Open in new window') ?>
			</a>
			</th>
		</tr>
		<tr>
			<td width="100%" valign="top" colspan="2">
			<iframe name="previewFrame" src="<?php echo $mosConfig_live_site . '/index.php?tp=' . $tp;?>" class="previewFrame" /></iframe>
			</td>
		</tr>
		</table>
		<?php
	}
}

/**
 * Compiles the help table of contents
 * @param string A specific keyword on which to filter the resulting list
 */
function getHelpTOC( $helpsearch ) {
	global $mosConfig_absolute_path;
	$helpurl = mosGetParam( $GLOBALS, 'mosConfig_helpurl', '' );
	$helpurl = mamboCore::get('mosConfig_live_site');

	$files = mosReadDirectory( $mosConfig_absolute_path . '/help/', '\.xml$|\.html$' );

	$toc = array();
	foreach ($files as $file) {
		$buffer = file_get_contents( $mosConfig_absolute_path . '/help/' . $file );
		if (preg_match( '#<title>(.*?)</title>#', $buffer, $m )) {
			$title = trim( $m[1] );
			if ($title) {
				if ($helpurl) {
					// strip the extension
					#$file = preg_replace( '#\.xml$|\.html$#', '', $file );
				}
		        if ($helpsearch) {
		            if (stripos( strip_tags( $buffer ), $helpsearch ) !== false) {
				    	$toc[$file] = $title;
					}
				} else {
				    $toc[$file] = $title;
				}
			}
		}
	}
	asort( $toc );
	return $toc;
}
?>
