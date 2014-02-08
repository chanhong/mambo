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

//$adminside = 3;
//require_once('../../index.php');

/** Set flag that this is a parent file */
if (!defined('_VALID_MOS')) define( '_VALID_MOS', 1 );

global $mainframe, $database, $mosConfig_absolute_path;

require_once('../../includes/database.php');
require_once('../../includes/core.classes.php');

$configuration =& mamboCore::getMamboCore();
$database =& mamboDatabase::getInstance();

$pollid = (int) mosGetParam( $_REQUEST, 'pollid', 0 );
$css = mosGetParam( $_REQUEST, 't', '' );

$database->setQuery( "SELECT title FROM #__polls WHERE id='$pollid'" );
$title = $database->loadResult();

$database->setQuery( "SELECT text FROM #__poll_data WHERE pollid='$pollid' order by id" );
$options = $database->loadResultArray();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title><?php echo T_('Poll Preview') ?></title>
	<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
	<link rel="stylesheet" href="../../templates/<?php echo $css; ?>/css/template_css.css" type="text/css">
</head>

<body>
<form>
<table align="center" width="90%" cellspacing="2" cellpadding="2" border="0" >
	<tr>
	    <td class="moduleheading" colspan="2"><?php echo $title; ?></td>
	</tr>
	<?php foreach ($options as $text)
	{
		if ($text <> "")
		{?>
		<tr>
	    	<td valign="top" height="30"><input type="radio" name="poll" value="<?php echo $text; ?>"></td>
			<td class="poll" width="100%" valign="top"><?php echo $text; ?></td>
		</tr>
		<?php }
	} ?>
	<tr>
	    <td valign="middle" height="50" colspan="2" align="center"><input type="button" name="submit" value="<?php echo T_('Vote') ?>">&nbsp;&nbsp;<input type="button" name="result" value="<?php echo T_('Results') ?>"></td>
	</tr>
	<tr>
	    <td align="center" colspan="2"><a href="#" onClick="window.close()"><?php echo T_('Close') ?></a></td>
	</tr>
</table>
</form>

</body>
</html>

<?php
/**
* Utility function to return a value from a named array or a specified default
*/
define( "_MOS_NOTRIM", 0x0001 );
define( "_MOS_ALLOWHTML", 0x0002 );
define( "_MOS_ALLOWRAW", 0x0004 );
define( "_MOS_NOMAGIC", 0x0008 );
function mosGetParam( &$arr, $name, $def=null, $mask=0 ) {
    if (isset( $arr[$name] )) {
        if (is_array($arr[$name])) foreach ($arr[$name] as $key=>$element) $result[$key] = mosGetParam ($arr[$name], $key, $def, $mask);
        else {
            $result = $arr[$name];
            if (!($mask&_MOS_NOTRIM)) $result = trim($result);
            if (!is_numeric( $result)) {
                if (!($mask&_MOS_ALLOWHTML)) $result = strip_tags($result);
                if (!($mask&_MOS_ALLOWRAW)) {
                    if (is_numeric($def)) $result = intval($result);
                }
            }
            if (!get_magic_quotes_gpc()) {
                $result = addslashes( $result );
            }
        }
        return $result;
    } else {
        return $def;
    }
} 
?>
