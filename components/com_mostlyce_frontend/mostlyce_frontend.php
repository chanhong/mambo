<?php
/**
* @package Mambo
* @subpackage Content
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

$mosConfig_absolute_path = mamboCore::get('mosConfig_absolute_path');
$mosConfig_secret = mamboCore::get('mosConfig_secret');

require($mosConfig_absolute_path.'/mambots/editors/mostlyce/jscripts/tiny_mce/auth_check.php');
$result = externalCallCheck($mosConfig_absolute_path, $mosConfig_secret);
if (!$result) {
	die(T_('Direct Access to this location is not allowed.'));
}

$task = trim(mosGetParam($_GET, 'task', ''));
switch (strtolower($task)) {
	case 'imagelist':
		getImageList();
		break;
		
	case 'contentlist':
		getContentList();
		break;

	default:
		die(T_('Direct Access to this location is not allowed.'));
		break;
}

/**
*	Purpose: This function creates a list of images to be displayed as a dropdown in all image dialogs 
*	if the "external_link_image_url" option is defined in TinyMCE init.
*
*	Expected output:
*	var tinyMCEImageList = new Array(
*		// Name, URL
*		["Logo 1", "media/logo.jpg"],
*		["Logo 2 Over", "media/logo_over.jpg"]
*	);
**/
function getImageList() {
	global $mosConfig_absolute_path, $mosConfig_live_site, $mosConfig_secret;
	$pathToImages = $mosConfig_absolute_path.'images/stories';
	$imageList = scan_directory_recursively($pathToImages);

	$validExtensions = array('jpeg', 'jpg', 'png', 'gif');
	$count = 0;

	$jsCode = 'var tinyMCEImageList = new Array(';
	foreach ($imageList as $image) {
		$count++;
		$crtExtension = substr(strrchr($image, '.'), 1);
		if (in_array($crtExtension, $validExtensions)) {
			$urlImagePath = str_replace($mosConfig_absolute_path, $mosConfig_live_site.'/', $image);
			$postAbsolutePath = str_replace($mosConfig_absolute_path, '', $image);
			if ($count < count($imageList )) {
				$jsCode .= "[\"$postAbsolutePath\", \"$urlImagePath\"],";
			} else {
				$jsCode .= "[\"$postAbsolutePath\", \"$urlImagePath\"]"; //no comma on the last line
			}	
		}	  
	}
	$jsCode .= ");";

	//Dump out the newly assembled list of images for MOStlyCE
	echo $jsCode;
}

/**
*	Purpose: This function creates a list of content items to be displayed as a dropdown in all link dialogs if 
*	the "external_link_list_url" option is defined in TinyMCE init.
*	
*	var tinyMCELinkList = new Array(
*		// Name, URL
*		["Moxiecode", "http://www.moxiecode.com"],
*		["Freshmeat", "http://www.freshmeat.com"],
*		["Sourceforge", "http://www.sourceforge.com"]
*	);
**/
function getContentList() {
	global $database, $mosConfig_absolute_path, $mosConfig_offset, $mosConfig_secret;
	
	$now = date('Y-m-d H:i:s', time() + $mosConfig_offset * 60 * 60);

	/* Build an array to hold the content items.  We need to combine the results from a 
	a number of queries to build the full map */
	$jsCode = 'var tinyMCELinkList = new Array(';
	
	/* Section Query */
	$section_query = "select id as secid, title as sec_title
	from #__sections
	order by ordering";
	$database->setQuery($section_query);
	$section_rows = $database->loadObjectList();
	
	//Start the list
	foreach($section_rows as $section_row) {
	//Start the section 
	$section_link = sefRelToAbs("index.php?option=com_content&task=section&id=$section_row->secid");
	$title_keyword = T_('Section');
	$jsCode .=  "[\"$title_keyword: $section_row->sec_title\", \"$section_link\"],";
		
	/* Category Query */
	$cat_query = "select id as catid, title as cat_title
	from #__categories
	where section = $section_row->secid
	order by ordering";
	$database->setQuery($cat_query);
	$cat_rows = $database->loadObjectList();
		
		if (count($cat_rows)>0) { // count arrary first to prevent foreach() error when array is empty
			foreach($cat_rows as $cat_row) {
				//Start the category
				//Find the correct ItemID
				$itemid_query = "select id
				from #__menu
				where name = '$cat_row->cat_title'
				and type='content_category'";
				$database->setQuery($itemid_query);
				$ItemID = $database->loadResult();
				//Since we must have an ItemID default to 1 if not found to prevent "You are not authorized" errors
				if (empty($ItemID)) {
					$ItemID=1;
				}
				
				$cat_link = sefRelToAbs("index.php?option=com_content&task=category&sectionid=$section_row->secid&id=$cat_row->catid&Itemid=$ItemID");
				$cat_keyword = T_('Category');
				$jsCode .=  "[\"|_$cat_keyword: $cat_row->cat_title\", \"$cat_link\"],";
				
				/* Content Query */
				$content_query = "select id as content_id, title as content_title
				from #__content
				where sectionid = $section_row->secid
				and catid = $cat_row->catid
				order by ordering";
				
				$database->setQuery($content_query);
				$content_rows = $database->loadObjectList();
			
				if (count($content_rows)>0) { // count arrary first to prevent foreach() error when array is empty
				  foreach($content_rows as $content_row) {
				  	//Generate content items
					$content_link = sefRelToAbs("index.php?option=com_content&task=view&id=$content_row->content_id");
					$content_keyword = T_('Content Item');
					$jsCode .=  "[\"|__$content_keyword: $content_row->content_title\", \"$content_link\"],";
				  } // End content_rows foreach
			
				} // End content_rows if
			} //End cat_rows foreach
		 
		} //End cat_rows if
	} //End section_rows	
	
	/* Static Content Query */
	$static_query = "select id as content_id, title as content_title
	from #__content
	where sectionid = 0
	and catid = 0
	order by ordering";
	$database->setQuery($static_query);
	$static_rows = $database->loadObjectList();
	  
	  if (count($static_rows)>0) { // count arrary first to prevent foreach() error when arrary is empty
		  foreach($static_rows as $static_row) {
		  	  //Start the section 
			  $static_link = sefRelToAbs("index.php?option=com_content&task=view&id=$static_row->content_id");
			  $scontent_keyword = T_('Static Content Item');
			  $jsCode .=  "[\"|_$scontent_keyword: $static_row->content_title\", \"$static_link\"],";
		  } //End static_rows foreach
		  
	} //End static_rows if

	$jsCode = substr($jsCode, 0, strlen($jsCode)-1); //remove final comma
	$jsCode .= ");"; //end the js array

	//Dump out the newly assembled list of images for MOStlyCE
	echo $jsCode;
}

/**
*	Purpose: Used to recurse through the images dir/sub-dir structure and build a 
*	list that can be used to hand back a JS array to TinyMCE's image list parameter
*
*	Note: Based on lixlpixel recursive PHP function (http://lixlpixel.org/recursive_function/php/recursive_directory_scan/)
**/ 
function scan_directory_recursively($directory, $filter=FALSE) {
	$imagesArray = array();

	if (substr($directory,-1) == '/') {
		$directory = substr($directory,0,-1);
	}
	if (!file_exists($directory) || !is_dir($directory)) {
		return FALSE;
	} else if (is_readable($directory)) {
		$directory_list = opendir($directory);
		while ($file = readdir($directory_list)) {
			if ($file != '.' && $file != '..') {
				$path = $directory.'/'.$file;
				if (is_readable($path)) {
					$subdirectories = explode('/',$path);
					if (is_dir($path)) {
						$directory_tree[] = array(
							'path'      => $path,
							'name'      => end($subdirectories),
							'kind'      => 'directory',
							'content'   => scan_directory_recursively($path, $filter));
					} else if (is_file($path)) {
						$imagesArray[] = $path;

						$extension = end(explode('.',end($subdirectories)));
						if ($filter === FALSE || $filter == $extension) {
							$directory_tree[] = array(
							'path'		=> $path,
							'name'		=> end($subdirectories),
							'extension' => $extension,
							'size'		=> filesize($path),
							'kind'		=> 'file');
						}
					}
				}
			}
		}
		closedir($directory_list); 
	} else {
		return FALSE;	
	}
	
	return $imagesArray;
}

?>