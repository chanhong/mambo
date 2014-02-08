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

/**
* Basic XML parsing of installation files
***/

class mosBasicXML {
	var $xmlfile = '';
	var $opentags = array();
	var $accept = array();
	var $mosinstall = false;
	var $chardata = '';
	var $type;
	var $errors = '';
	var $mosParameter = null;
	var $name = '';

	function mosBasicXML ($file, $mosParameter=null, $name='params') {
		$this->xmlfile = $file;
		$this->mosParameter = $mosParameter;
		$this->name = $name;
		$this->errors = new mosErrorSet();
		$this->setTree();
		$parser = xml_parser_create();
		$startfunc = array (&$this, 'start_element');
		$endfunc = array (&$this, 'end_element');
		$charfunc = array (&$this, 'character_data');
		xml_set_element_handler ($parser, $startfunc, $endfunc);
		xml_set_character_data_handler ($parser, $charfunc);
		if ($fp = fopen($this->xmlfile, 'rb')) {
			while ($data = fread($fp, 4096) AND $this->errors->getMaxLevel() < _MOS_ERROR_FATAL) {
				$data = str_replace('&', ' ampersand ', $data);
				$ret = xml_parse($parser, $data, feof($fp)) or $this->errors->addErrorDetails(sprintf(T_('XML ERROR in %s: %s at line %d'),
				$this->xmlfile,
				xml_error_string(xml_get_error_code($parser)),
				xml_get_current_line_number($parser)), _MOS_ERROR_FATAL);
			}
		}
		else $this->errors->addErrorDetails(sprintf(T_('Unable to open XML file %s'),$this->xmlfile), _MOS_ERROR_FATAL);
		if (count($this->opentags) != 0) {
			$tags = implode (', ', $this->opentags);
			$this->errors->addErrorDetails(sprintf(T_('XML error in %s - unclosed tag(s) (%s) at end of file'),$this->xmlfile, $tags), _MOS_ERROR_SEVERE);
		}
		xml_parser_free($parser);
	}

	function setTree () {
		$this->accept['MOSINSTALL'] = array ('NAME', 'CREATIONDATE', 'AUTHOR', 'COPYRIGHT',
		'LICENSE', 'AUTHOREMAIL', 'AUTHORURL', 'VERSION', 'DESCRIPTION', 'FILES', 'MEDIA',
		'PARAMS', 'INSTALL', 'UNINSTALL', 'INSTALLFILE', 'UNINSTALLFILE', 'ADMINISTRATION',
		'IMAGES', 'CSS', 'GROUP', 'LOCALE', 'REMOVE_FILES');
		$this->accept['PARAMS'] = array ('PARAM');
		$this->accept['PARAM'] = array ('OPTION');
		$this->accept['FILES'] = array ('FILENAME');
		$this->accept['REMOVE_FILES'] = array ('FILENAME');
		$this->accept['INSTALL'] = array ('QUERIES');
		$this->accept['UNINSTALL'] = array ('QUERIES');
		$this->accept['QUERIES'] = array ('QUERY');
		$this->accept['ADMINISTRATION'] = array ('FILES', 'IMAGES', 'MENU', 'SUBMENU');
		$this->accept['IMAGES'] = array ('FILENAME');
		$this->accept['SUBMENU'] = array('MENU');
		$this->accept['MEDIA'] = array('FILENAME');
		$this->accept['CSS'] = array('FILENAME');
		$this->accept['LOCALE'] = array('PLURAL_FORM', 'DATE_FORMAT', 'CODESETS', 'DAYS', 'MONTHS', 'WINCODEPAGE');
		$this->accept['CODESETS'] = array('CHARSET');
	}

	function start_element ($parser, $element_name, $element_attrs) {
		if ($this->errors->getMaxLevel() >= _MOS_ERROR_FATAL) return;
		if ($this->mosinstall) {
			$container = $this->opentags[0];
			if (!isset($this->accept[$container]) OR !is_array($this->accept[$container])) $this->errors->addErrorDetails(sprintf(T_('XML error in %s: %s is not a valid containing element'), $this->xmlfile, $container), _MOS_ERROR_WARN);
			elseif (!in_array($element_name, $this->accept[$container])) $this->errors->addErrorDetails(sprintf(T_('XML error in %s: %s not permitted within %s'), $this->xmlfile, $element_name, $container), _MOS_ERROR_WARN);
		}
		if ($this->mosinstall OR $element_name == 'MOSINSTALL') {
			$this->opencount = array_unshift ($this->opentags, $element_name);
			$this->mosinstall = true;
			$method = 'element_'.$element_name;
			$specific = array (&$this, $method);
			foreach ($element_attrs as $key=>$attr) $element_attrs[$key] = str_replace(' ampersand ', '&', $attr);
			if (is_callable($specific)) $this->$method($element_attrs);
		}
		else $this->errors->addErrorDetails(sprintf(T_('XML error in %s: expected MOSINSTALL but found %s'), $this->xmlfile, $element_name), _MOS_ERROR_SEVERE);
	}

	function end_element ($parser, $element_name) {
		if ($this->errors->getMaxLevel() >= _MOS_ERROR_FATAL) return;
		if ($this->opentags[0] != $element_name) {
			$this->errors->addErrorDetails(sprintf(T_('XML error in %s: last open tag was %s, but found end of %s'), $this->xmlfile, $check, $element_name), _MOS_ERROR_SEVERE);
			return;
		}
		$this->chardata = trim(str_replace(' ampersand ', '&', $this->chardata));
		if (isset($this->opentags[1]) AND $this->opentags[1] == 'MOSINSTALL') $this->values[$this->opentags[0]] = $this->chardata;
		$method = 'end_element_'.$element_name;
		$specific = array (&$this, $method);
		if (is_callable($specific)) $this->$method();
		array_shift ($this->opentags);
		$this->opencount--;
		$this->chardata = '';
	}

	function character_data ($parser, $data) {
		if ($this->errors->getMaxLevel() >= _MOS_ERROR_FATAL) return;
		$this->chardata .= $data;
	}

	function element_mosinstall ($attrs) {
		if (isset($attrs['TYPE'])) $this->type = $attrs['TYPE'];
		else $this->errors->addErrorDetails(sprintf(T_('XML error in %s: mosinstall does not have type attribute'), $this->xmlfile), _MOS_ERROR_FATAL);
	}

	function getType () {
		return $this->type;
	}
	
	function &getErrors () {
		$errors =& $this->errors->getErrors();
		return $errors;
	}

}

/**
* Extend basic parser to extract the description for a type of install file
**/

class mosXMLDescription extends mosBasicXML {
	var $values = array();

	function getDescription ($type) {
		if ($type == $this->type AND isset($this->values['DESCRIPTION'])) return $this->values['DESCRIPTION'];
		else return '';
	}

	function getName ($type) {
		if ($type == $this->type AND isset($this->values['NAME'])) return $this->values['NAME'];
		else return '';
	}

	function getGroup ($type) {
		if ($type == $this->type AND isset($this->values['GROUP'])) return $this->values['GROUP'];
		else return '';
	}

	function getCreationDate ($type) {
		if ($type == $this->type AND isset($this->values['CREATIONDATE'])) return $this->values['CREATIONDATE'];
		else return '';
	}

	function getAuthor ($type) {
		if ($type == $this->type AND isset($this->values['AUTHOR'])) return $this->values['AUTHOR'];
		else return '';
	}

	function getCopyright ($type) {
		if ($type == $this->type AND isset($this->values['COPYRIGHT'])) return $this->values['COPYRIGHT'];
		else return '';
	}

	function getAuthorEmail ($type) {
		if ($type == $this->type AND isset($this->values['AUTHOREMAIL'])) return $this->values['AUTHOREMAIL'];
		else return '';
	}

	function getAuthorUrl ($type) {
		if ($type == $this->type AND isset($this->values['AUTHORURL'])) return $this->values['AUTHORURL'];
		else return '';
	}

	function getVersion ($type) {
		if ($type == $this->type AND isset($this->values['VERSION'])) return $this->values['VERSION'];
		else return '';
	}

}

class mosXMLParams extends mosXMLDescription {
	var $options = array();
	var $optvalue = '';
	var $paramattrs = array();
	var $paramcount = 0;
	var $html = array();
	
	function element_params ($attrs) {
		$this->html[] = '<table class="paramlist">';
		if (isset($attrs['NAME'])) {
		    $pname = $attrs['NAME'];
			$this->html[] = "<tr><td colspan='3'>$pname</td></tr>";
		}
	}

	function element_param ($attrs) {
		$this->paramattrs = $attrs;
	}
	
	function element_option ($attrs) {
		if (isset($attrs['VALUE'])) $this->optvalue = $attrs['VALUE'];
	}
	
	function end_element_option () {
		$this->options[] = mosHTML::makeOption($this->optvalue, T_($this->chardata));
		$this->optvalue = '';
	}

	function end_element_param () {
		$type = mosGetParam ($this->paramattrs, 'TYPE', '');
		$name = mosGetParam ($this->paramattrs, 'NAME', '');
		$label = T_(mosGetParam ($this->paramattrs, 'LABEL', $name));
		$default = T_(mosGetParam ($this->paramattrs, 'DEFAULT', ''));
		if ($description = mosGetParam ($this->paramattrs, 'DESCRIPTION', '')) $tooltip = mosToolTip(T_($description), $name);
		else $tooltip = '';
		if (is_object($this->mosParameter)) {
			$mp = $this->mosParameter;
			$value = $mp->get($name, $default);
		}
		else $value = $default;
		$this->html[] = '<tr>';
		if ($label == '@spacer') $label = '<hr />';
		elseif ($label) $label .= ':';
		$this->html[] = '<td width="35%" align="right" valign="top">'.$label.'</td>';
	    $controlname = $this->name;
		switch ($type) {
			case 'text':
			    $size = mosGetParam ($this->paramattrs, 'SIZE', 0);
			    $controlstring = '<input type="text" name="'.$this->name.'['.$name.']" value="'.$value.'" class="text_area" size="'.$size.'" />';
				break;
			case 'list':
			    $controlstring = mosHTML::selectList($this->options, $controlname.'['.$name.']', 'class="inputbox"', 'value', 'text', $value);
				break;
			case 'radio':
			    $controlstring = mosHTML::radioList($this->options, $controlname.'['.$name.']', '', $value);
				break;
			case 'imagelist':
			    $directory = new mosDirectory (mamboCore::get('mosConfig_absolute_path').mosGetParam($this->paramattrs, 'DIRECTORY', ''));
			    $files = $directory->listFiles ('\.png$|\.gif$|\.jpg$|\.bmp$|\.ico$');
			    $options = array();
			    foreach ($files as $file) $options[] = mosHTML::makeOption($file, $file);
			    if (!isset($this->paramattrs['HIDE_NONE'])) array_unshift($options, mosHTML::makeOption('-1', '- Do not use an image -' ));
			    if (!isset($this->paramattrs['HIDE_DEFAULT'])) array_unshift($options, mosHTML::makeOption('', '- Use Default image -'));
			    $controlstring = mosHTML::selectList ($options, $controlname.'['.$name.']', 'class="inputbox"', 'value', 'text', $value);
				break;
			case 'textarea':
		        $rows = mosGetParam ($this->paramattrs, 'ROWS', 0);
		        $cols = mosGetParam ($this->paramattrs, 'COLS', 0);
		        $value = str_replace ('<br />', "\n", $value);
		        $controlstring = "<textarea name='params[$name]' cols='$cols' rows='$rows' class='text_area'>$value</textarea>";
				break;
		    case 'spacer':
				$controlstring = $value ? $value : '<hr />';
				break;
			case 'mos_section':
				$controlstring = $this->_form_mos_section($name, $value, $controlname);
				break;
			case 'mos_category':
				$controlstring = $this->_form_mos_category($name, $value, $controlname);
				break;
			case 'mos_menu':
			    $controlstring = $this->_form_mos_menu($name, $value, $controlname);
			    break;
			default:
				$controlstring = T_('Handler not defined for type').'='.$type;
		}
//		$this->html[] = "<td>$type</td>";
		$this->html[] = "<td>$controlstring</td>";
		$this->html[] = "<td width='10%' align='left' valign='top'>$tooltip</td>";
		$this->html[] = '</tr>';
		$this->options = array();
		$this->paramattrs = array();
		$this->paramcount++;
	}
	
	function end_element_params () {
		$this->html[] = '</table>';
		if ($this->paramcount == 0) $this->html[] = '<tr><td colspan="2"><i>'.T_('There are no Parameters for this item').'</i></td></tr>';
		$this->paramcount = 0;
	}
	/**
	* @param string The name of the form element
	* @param string The value of the element
	* @param object The xml element for the parameter
	* @param string The control name
	* @return string The html for the element
	*/
	function _form_mos_section( $name, $value, $control_name ) {
		$database =& mamboDatabase::getInstance();
		$query = "SELECT id AS value, title AS text"
		. "\n FROM #__sections"
		. "\n WHERE published='1' AND scope='content'"
		. "\n ORDER BY title"
		;
		$database->setQuery( $query );
		$options = $database->loadObjectList();
		array_unshift($options, mosHTML::makeOption( '0', '- Select Content Section -' ));
		return mosHTML::selectList( $options, $control_name.'['.$name.']', 'class="inputbox"', 'value', 'text', $value );
	}
	/**
	* @param string The name of the form element
	* @param string The value of the element
	* @param object The xml element for the parameter
	* @param string The control name
	* @return string The html for the element
	*/
	function _form_mos_category( $name, $value, $control_name ) {
		$database =& mamboDatabase::getInstance();
		$query 	= "SELECT c.id AS value, CONCAT_WS( '/',s.title, c.title ) AS text"
		. "\n FROM #__categories AS c"
		. "\n LEFT JOIN #__sections AS s ON s.id=c.section"
		. "\n WHERE c.published='1' AND s.scope='content'"
		. "\n ORDER BY c.title"
		;
		$database->setQuery( $query );
		$options = $database->loadObjectList();
		array_unshift($options, mosHTML::makeOption('0', '- Select Content Category -'));
		return mosHTML::selectList( $options, $control_name.'['.$name.']', 'class="inputbox"', 'value', 'text', $value );
	}
	/**
	* @param string The name of the form element
	* @param string The value of the element
	* @param object The xml element for the parameter
	* @param string The control name
	* @return string The html for the element
	*/
	function _form_mos_menu( $name, $value, $control_name ) {
		$menuTypes = mosAdminMenus::menutypes();
		foreach($menuTypes as $menutype ) $options[] = mosHTML::makeOption( $menutype, $menutype );
		array_unshift( $options, mosHTML::makeOption( '', '- Select Menu -' ) );
		return mosHTML::selectList( $options, ''. $control_name .'['. $name .']', 'class="inputbox"', 'value', 'text', $value );
	}
}

/**
* Parameters handler
* @package Mambo
*/
class mosAdminParameters extends mosParameters {
	/** @var string Path to the xml setup file */
	var $_path = null;
	/** @var string The type of setup file */
	var $_type = null;
	/** @var object The xml params element */
	var $_xmlElem = null;
/**
* Constructor
* @param string The raw parms text
* @param string Path to the xml setup file
* @var string The type of setup file
*/
	function mosAdminParameters( $text, $path='', $type='component' ) {
	    $this->_params = $this->parse( $text );
	    $this->_raw = $text;
	    $this->_path = $path;
	    $this->_type = $type;
	}
	
}
/**
* Generally available parameter object
* @package Mambo
*/
class mosSpecialAdminParameters extends mosAdminParameters {

	function mosSpecialAdminParameters ($name, $version='') {
	    $database =& mamboDatabase::getInstance();
	    $sql = "SELECT * FROM #__parameters WHERE param_name='$name'";
	    if ($version) $sql .= " AND param_version='$version'";
	    $database->setQuery($sql);
	    $parameters = $database->loadObjectList();
	    if ($parameters) $parameters = $parameters[0];
	    parent::mosAdminParameters($parameters->params, mamboCore::get('mosConfig_absolute_path').'/parameters/'.$parameters->param_file);
	}
}

/**
* Useful HTML class for admin side components
* @package Mambo
*/
class basicAdminHTML {
	var $pageNav = '';
	var $option = '';
	var $act = '';
	var $limit = 10;

	function basicAdminHTML (&$controller, $limit) {
		$this->act = $controller->admin->act;
		$this->limit = $limit;
		$this->pageNav = $controller->pageNav;
		$this->option = strtolower(mosGetParam($_REQUEST,'option','com_admin'));
	}

	function tickBox ($object, $property) {
		if (is_object($object) AND $object->$property) $checked = "checked='checked'";
		else $checked = '';
		echo "<td><input type='checkbox' name='$property' value='1' $checked /></td>";
	}

	function yesNoList ($object, $property) {
		$yesno[] = mosHTML::makeOption( 0, _NO );
		$yesno[] = mosHTML::makeOption( 1, _YES );
		if ($object) $default = $object->$property;
		else $default = 0;
		echo '<td valign="top">';
		echo mosHTML::selectList($yesno, $property, 'class="inputbox" size="1"', 'value', 'text', $default);;
		echo '</td></tr>';
	}

	function inputTop ($title, $redstar=false, $maxsize=0) {
		?>
		<tr>
		  	<td width="30%" valign="top" align="right">
				<strong><?php if ($redstar) echo '<font color="red">*</font>'; echo $title; if ($maxsize) echo "</strong>&nbsp;<br /><em>$maxsize</em>&nbsp;"; ?></strong>&nbsp;
			</td>
		<?php
	}

	function blankRow () {
		?>
			<tr><td>&nbsp;</td></tr>
		<?php
	}

	function fileInputBox ($title, $name, $value, $width, $tooltip=null) {
		$this->inputTop($title);
		?>
			<td align="left" valign="top">
				<input class="inputbox" type="text" name="<?php echo $name; ?>" size="<?php echo $width; ?>" value="<?php echo $value; ?>" />
				<?php if ($tooltip) echo tooltip($tooltip); ?>
			</td>
		</tr>
		<?php
	}

	function fileInputArea ($title, $maxsize, $name, $value, $rows, $cols, $editor=false, $tooltip=null) {
		$this->inputTop ($title, false, $maxsize);
		echo '<td valign="top">';
		if ($editor) {
			$box = "editorArea( 'description', '$value', '$name', 500, 200, $rows, $cols );";
			eval($box);
		}
		else echo "<textarea class='inputbox' name='$name' rows='$rows' cols='$cols'>$value</textarea>";
		if ($tooltip) echo tooltip($tooltip);
		echo '</td></tr>';
	}

	function tickBoxField ($object, $property, $title) {
		?>
		<tr>
			<td width="30%" valign="top" align="right">
				<strong><?php echo $title; ?></strong>&nbsp;
			</td>
		<?php
		$this->tickBox($object,$property);
		echo '</tr>';
	}

	function simpleTickBox ($title, $name, $checked=false) {
		$this->inputTop($title);
		if ($checked) $check = 'checked="checked"';
		else $check = '';
		?>
			<td>
				<input type="checkbox" name="<?php echo $name; ?>" value="1" <?php echo $check; ?> />
			</td>
		</tr>
		<?php
	}
	function formStart ($title, $imagepath) {
		?>
		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
		<script language="Javascript" src="../includes/js/overlib_mini.js"></script>
		<form action="index2.php" method="post" name="adminForm">
		<table cellpadding="4" cellspacing="0" border="0" width="100%">
   		<tr>
			<td width="100%" colspan="4">
			<div class="title">
			<img src="<?php echo $imagepath; ?>" alt="<?php echo $title; ?>" />
			<span class="sectionname">&nbsp;<?php echo $title; ?></span>
			</div>
			</td>
    	</tr>
		<?php
	}

	function listHeadingStart ($count) {
		?>
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
			<tr>
				<th width="5" align="left">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo $count; ?>);" />
				</th>
		<?php
	}

	function headingItem ($width, $title, $colspan=1) {
		if ($colspan > 1) $colcode = " colspan=\"$colspan\"";
		else $colcode = '';
		echo "<th width=\"$width\" align=\"left\"$colcode>$title</th>";
	}

	function commonScripts ($edit_fields) {
		?>
		<script type="text/javascript">
        function submitbutton(pressbutton) {
                <?php
				if (is_array($edit_fields)) foreach ($edit_fields as $field) getEditorContents( $field, $field );
				else getEditorContents ($edit_fields, $edit_fields);
				?>
                submitform( pressbutton );
        }
        </script>
        <?php
	}

	function listFormEnd ($pagecontrol=true) {
		if ($pagecontrol) {
			?>
			<tr>
	    		<th align="center" colspan="10"> <?php echo $this->pageNav->writePagesLinks(); ?></th>
			</tr>
			<tr>
				<td align="center" colspan="10"> <?php echo $this->pageNav->writePagesCounter(); ?></td>
			</tr>
			<?php
		}
		?>
		<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="act" value="<?php echo $this->act; ?>" />
		<input type="hidden" name="boxchecked" value="0" />
		</table>
		</form>
		<?php
	}

	function editFormEnd ($id) {
		?>
		<input type="hidden" name="cfid" value="<?php echo $id; ?>" />
		<input type="hidden" name="limit" value="<?php echo $this->limit; ?>" />
		<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="act" value="<?php echo $this->act; ?>" />
		</table>
		</form>
		<?php
	}

	function multiOptionList ($name, $title, $options, $current, $tooltip=null) {
		$alternatives = explode(',',$options);
		$already = explode(',', $current);
		?>
		<tr>
	    <td width="30%" valign="top" align="right">
	  	<strong><?php echo $title; ?></strong>&nbsp;
	    </td>
	    <td valign="top">
		<?php
		foreach ($alternatives as $one) {
			if (in_array($one,$already)) $mark = 'checked="checked"';
			else $mark = '';
			$value = $name.'_'.$one;
			echo "<input type=\"checkbox\" name=\"$value\" $mark />$one";
		}
		if ($tooltip) echo '&nbsp;'.tooltip($tooltip);
		echo '</td></tr>';
	}

	function tooltip ($text) {
		return '<a href="javascript:void(0)"  onmouseover="return escape('."'".$text."'".')">'.mamboCore::get('mosConfig_live_site').'/includes/js/ThemeOffice/tooltip.png</a>';
	}

}

/**
* @param string THe template position
*/
function mosCountAdminModules(  $position='left' ) {
	$handler =& mosModuleHandler::getInstance();
	return $handler->mosCountModules($position, true);
}
/**
* Loads admin modules via module position
* @param string The position
* @param int 0 = no style, 1 = tabbed
*/
function mosLoadAdminModules( $position='left', $style=0 ) {
	$handler =& mosModuleHandler::getInstance();
	return $handler->mosLoadAdminModules($position, $style);
}
/**
* Loads an admin module
*/
function mosLoadAdminModule( $name, $params=NULL ) {
	global $mosConfig_absolute_path, $mosConfig_live_site;
	global $database, $acl, $my, $mainframe, $option;
	global $task, $act;
	
	$name = str_replace( '/', '', $name );
	$name = str_replace( '\\', '', $name );
	$path = mamboCore::get('mosConfig_absolute_path')."/administrator/modules/mod_$name.php";
	if (file_exists($path)) require $path;
}

function mosLoadCustomModule( &$module, &$params ) {
	if ($module->content) {
		$moduleclass_sfx = $params->get( 'moduleclass_sfx', '' );
		echo '<table cellpadding="0" cellspacing="0" class="moduletable' . $moduleclass_sfx . '">';
		echo '<tr>';
		echo '<td>' . $module->content . '</td>';
		echo '</tr>';
		echo '</table>';
	}

}

function mosShowSource( $filename, $withLineNums=false ) {
	ini_set('highlight.html', '000000');
	ini_set('highlight.default', '#800000');
	ini_set('highlight.keyword','#0000ff');
	ini_set('highlight.string', '#ff00ff');
	ini_set('highlight.comment','#008000');

	if (!($source = @highlight_file( $filename, true ))) {
		return 'Operation Failed';
	}
	$source = explode("<br />", $source);

	$ln = 1;

	$txt = '';
	foreach( $source as $line ) {
		$txt .= "<code>";
		if ($withLineNums) {
			$txt .= "<font color=\"#aaaaaa\">";
			$txt .= str_replace( ' ', '&nbsp;', sprintf( "%4d:", $ln ) );
			$txt .= "</font>";
		}
		$txt .= "$line<br /><code>";
		$ln++;
	}
	return $txt;
}

function mosIsChmodable($file)
{
	$perms = fileperms($file);
	if ($perms !== FALSE)
		if (@chmod($file, $perms ^ 0001)) {
			@chmod($file, $perms);
			return TRUE;
		} // if
	return FALSE;
} // mosIsChmodable

/**
* @param string An existing base path
* @param string A path to create from the base path
* @param int Directory permissions
* @return boolean True if successful
*/
function mosMakePath($base, $path='', $mode = NULL)
{
	global $mosConfig_dirperms;

	// convert windows paths
	$path = str_replace( '\\', '/', $path );
	$path = str_replace( '//', '/', $path );

	// check if dir exists
	if (file_exists( $base . $path )) return true;

	// set mode
	$origmask = NULL;
	if (isset($mode)) {
		$origmask = @umask(0);
	} else {
		if ($mosConfig_dirperms=='') {
			// rely on umask
			$mode = 0777;
		} else {
			$origmask = @umask(0);
			$mode = octdec($mosConfig_dirperms);
		} // if
	} // if

	$parts = explode( '/', $path );
	$n = count( $parts );
	$ret = true;
	if ($n < 1) {
	    $ret = @mkdir($base, $mode);
	} else {
		$path = $base;
		for ($i = 0; $i < $n; $i++) {
		    $path .= $parts[$i] . '/';
		    if (!file_exists( $path )) {
		        if (!@mkdir( $path, $mode )) {
					$ret = false;
					break;
				}
			}
		}
	}
	if (isset($origmask)) @umask($origmask);
	return $ret;
}

?>
