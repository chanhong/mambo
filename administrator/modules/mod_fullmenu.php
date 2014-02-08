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
* Full DHTML Admnistrator Menus
*/
class mosFullAdminMenu {
	/**
	* Show the menu
	* @param string The current user type
	*/
	function show( $usertype='' ) {
		global $acl, $database;
		global $mosConfig_live_site, $mosConfig_enable_stats, $mosConfig_caching;

		// cache some acl checks
		$canConfig 			= $acl->acl_check( 'administration', 'config', 'users', $usertype );

		$manageTemplates 	= $acl->acl_check( 'administration', 'manage', 'users', $usertype, 'components', 'com_templates' );
		$manageTrash 		= $acl->acl_check( 'administration', 'manage', 'users', $usertype, 'components', 'com_trash' );
		$manageMenuMan 		= $acl->acl_check( 'administration', 'manage', 'users', $usertype, 'components', 'com_menumanager' );
		$manageLanguages 	= $acl->acl_check( 'administration', 'manage', 'users', $usertype, 'components', 'com_languages' );
		$installModules 	= $acl->acl_check( 'administration', 'install', 'users', $usertype, 'modules', 'all' );
		$editAllModules 	= $acl->acl_check( 'administration', 'edit', 'users', $usertype, 'modules', 'all' );
		$installMambots 	= $acl->acl_check( 'administration', 'install', 'users', $usertype, 'mambots', 'all' );
		$editAllMambots 	= $acl->acl_check( 'administration', 'edit', 'users', $usertype, 'mambots', 'all' );
		$installComponents 	= $acl->acl_check( 'administration', 'install', 'users', $usertype, 'components', 'all' );
		$editAllComponents 	= $acl->acl_check( 'administration', 'edit', 'users', $usertype, 'components', 'all' );
		$canMassMail 		= $acl->acl_check( 'administration', 'manage', 'users', $usertype, 'components', 'com_massmail' );
		$canManageUsers 	= $acl->acl_check( 'administration', 'manage', 'users', $usertype, 'components', 'com_users' );

        $query = "SELECT a.id, a.title, a.name,"
        . "\nCOUNT(DISTINCT c.id) AS numcat, COUNT(DISTINCT b.id) AS numarc"
        . "\n FROM #__sections AS a"
        . "\n LEFT JOIN #__categories AS c ON c.section=a.id"
        . "\n LEFT JOIN #__content AS b ON b.sectionid=a.id AND b.state=-1"
        . "\n WHERE a.scope='content'"
        . "\n GROUP BY a.id"
        . "\n ORDER BY a.ordering"
        ;
        $database->setQuery( $query );
        $sections = $database->loadObjectList();
		$nonemptySections = 0;
		if ($sections) foreach ($sections as $section)
			if ($section->numcat > 0)
				$nonemptySections++;
		$menuTypes = mosAdminMenus::menutypes();
?>
		<div id="myMenuID"></div>
		<script language="JavaScript" type="text/javascript">
		var myMenu =
		[
<?php
	// Home Sub-Menu
?>			[null,'<?php echo T_('Home') ?>','index2.php',null,'<?php echo T_('Control Panel') ?>'],
			_cmSplit,
<?php
	// Site Sub-Menu
?>			[null,'<?php echo T_('Site') ?>',null,null,'<?php echo T_('Site Management') ?>',
<?php
			if ($canConfig) {
?>				['<img src="../includes/js/ThemeOffice/config.png" />','<?php echo T_('Global Configuration') ?>','index2.php?option=com_config&hidemainmenu=1',null,'<?php echo T_('Configuration') ?>'],
<?php
			}
			if ($manageLanguages) {
?>				['<img src="../includes/js/ThemeOffice/language.png" />','<?php echo T_('Language Manager') ?>','index2.php?option=com_languages',null,'<?php echo T_('Manage languages') ?>'],
<?php
			}
?>				['<img src="../includes/js/ThemeOffice/media.png" />','<?php echo T_('Media Manager') ?>','index2.php?option=com_media',null,'<?php echo T_('Manage Media Files') ?>'],
					['<img src="../includes/js/ThemeOffice/preview.png" />', '<?php echo T_('Preview') ?>', null, null, '<?php echo T_('Preview') ?>',
					['<img src="../includes/js/ThemeOffice/preview.png" />','<?php echo T_('In New Window') ?>','<?php echo $mosConfig_live_site; ?>/index.php','_blank','<?php echo $mosConfig_live_site; ?>'],
					['<img src="../includes/js/ThemeOffice/preview.png" />','<?php echo T_('Inline') ?>','index2.php?option=com_admin&task=preview',null,'<?php echo $mosConfig_live_site; ?>'],
					['<img src="../includes/js/ThemeOffice/preview.png" />','<?php echo T_('Inline with Positions') ?>','index2.php?option=com_admin&task=preview2',null,'<?php echo $mosConfig_live_site; ?>'],
				],
				['<img src="../includes/js/ThemeOffice/globe1.png" />', '<?php echo T_('Statistics') ?>', null, null, '<?php echo T_('Site Statistics') ?>',
<?php
			if ($mosConfig_enable_stats == 1) {
?>					['<img src="../includes/js/ThemeOffice/globe4.png" />', '<?php echo T_('Browser, OS, Domain') ?>', 'index2.php?option=com_statistics', null, '<?php echo T_('Browser, OS, Domain') ?>'],
  					['<img src="../includes/js/ThemeOffice/globe3.png" />', '<?php echo T_('Page Impressions') ?>', 'index2.php?option=com_statistics&task=pageimp', null, '<?php echo T_('Page Impressions') ?>'],
<?php
			}
?>					['<img src="../includes/js/ThemeOffice/search_text.png" />', '<?php echo T_('Search Text') ?>', 'index2.php?option=com_statistics&task=searches', null, '<?php echo T_('Search Text') ?>']
				],
<?php
			if ($manageTemplates) {
?>				['<img src="../includes/js/ThemeOffice/template.png" />','<?php echo T_('Template Manager') ?>',null,null,'<?php echo T_('Change site template') ?>',
  					['<img src="../includes/js/ThemeOffice/template.png" />','<?php echo T_('Site Templates') ?>','index2.php?option=com_templates',null,'<?php echo T_('Change site template') ?>'],
  					['<img src="../includes/js/ThemeOffice/template.png" />','<?php echo T_('Administrator Templates') ?>','index2.php?option=com_templates&client=admin',null,'<?php echo T_('Change admin template') ?>'],
  					['<img src="../includes/js/ThemeOffice/template.png" />','<?php echo T_('Module Positions') ?>','index2.php?option=com_templates&task=positions',null,'<?php echo T_('Template positions') ?>']
  				],
<?php
			}
			if ($manageTrash) {
?>				['<img src="../includes/js/ThemeOffice/trash.png" />','<?php echo T_('Trash Manager') ?>','index2.php?option=com_trash',null,'<?php echo T_('Manage Trash') ?>'],
<?php
			}
			if ($canManageUsers || $canMassMail) {
?>				['<img src="../includes/js/ThemeOffice/users.png" />','<?php echo T_('User Manager') ?>','index2.php?option=com_users&task=view',null,'<?php echo T_('Manage users') ?>'],
<?php
				}
?>			],
<?php
	// Menu Sub-Menu
?>			_cmSplit,
			[null,'<?php echo T_('Menu') ?>',null,null,'<?php echo T_('Menu Management') ?>',
<?php
			if ($manageMenuMan) {
?>				['<img src="../includes/js/ThemeOffice/menus.png" />','<?php echo T_('Menu Manager') ?>','index2.php?option=com_menumanager',null,'<?php echo T_('Menu Manager') ?>'],
				_cmSplit,
<?php
			}
			foreach ( $menuTypes as $menuType ) {
?>				['<img src="../includes/js/ThemeOffice/menus.png" />','<?php echo $menuType;?>','index2.php?option=com_menus&menutype=<?php echo $menuType;?>',null,''],
<?php
			}
?>			],
			_cmSplit,
<?php
	// Content Sub-Menu
?>			[null,'<?php echo T_('Content') ?>',null,null,'<?php echo T_('Content Management') ?>',
<?php
			if (count($sections) > 0) {
?>				['<img src="../includes/js/ThemeOffice/edit.png" />','<?php echo T_('Content by Section') ?>',null,null,'<?php echo T_('Content Managers') ?>',
<?php
				foreach ($sections as $section) {
					$txt = addslashes( $section->title ? $section->title : $section->name );
?>					['<img src="../includes/js/ThemeOffice/document.png" />','<?php echo $txt;?>', null, null,'<?php echo $txt;?>',
<?php
					if ($section->numcat) {
?>						['<img src="../includes/js/ThemeOffice/edit.png" />', '<?php echo $txt;?> <?php echo T_('Items') ?>', 'index2.php?option=com_content&sectionid=<?php echo $section->id;?>',null,null],
<?php
					}
?>						['<img src="../includes/js/ThemeOffice/add_section.png" />', '<?php echo T_('Add/Edit') ?> <?php echo $txt;?> <?php echo T_('Categories') ?>', 'index2.php?option=com_categories&section=<?php echo $section->id;?>',null, null],
<?php
					if ($section->numarc) {
?>						['<img src="../includes/js/ThemeOffice/backup.png" />', '<?php echo $txt;?> <?php echo T_('Archive') ?>', 'index2.php?option=com_content&task=showarchive&sectionid=<?php echo $section->id;?>',null,null],
<?php
					}
?>					],
<?php
				} // foreach
?>				],
				_cmSplit,
<?php
			}
?>
				['<img src="../includes/js/ThemeOffice/edit.png" />','<?php echo T_('All Content Items') ?>','index2.php?option=com_content&sectionid=0',null,'<?php echo T_('Manage Content Items') ?>'],
  				['<img src="../includes/js/ThemeOffice/edit.png" />','<?php echo T_('Static Content Manager') ?>','index2.php?option=com_typedcontent',null,'<?php echo T_('Manage Typed Content Items') ?>'],
  				_cmSplit,
  				['<img src="../includes/js/ThemeOffice/add_section.png" />','<?php echo T_('Section Manager') ?>','index2.php?option=com_sections&scope=content',null,'<?php echo T_('Manage Content Sections') ?>'],
<?php
			if (count($sections) > 0) {
?>				['<img src="../includes/js/ThemeOffice/add_section.png" />','<?php echo T_('Category Manager') ?>','index2.php?option=com_categories&section=content',null,'<?php echo T_('Manage Content Categories') ?>'],
<?php
			}
?>				_cmSplit,
  				['<img src="../includes/js/ThemeOffice/home.png" />','<?php echo T_('Frontpage Manager') ?>','index2.php?option=com_frontpage',null,'<?php echo T_('Manage Frontpage Items') ?>'],
  				['<img src="../includes/js/ThemeOffice/edit.png" />','<?php echo T_('Archive Manager') ?>','index2.php?option=com_content&task=showarchive&sectionid=0',null,'<?php echo T_('Manage Archive Items') ?>'],
			],
<?php
	// Components Sub-Menu
	if ($installComponents) {
?>			_cmSplit,
			[null,'<?php echo T_('Components') ?>',null,null,'<?php echo T_('Component Management') ?>',
				['<img src="../includes/js/ThemeOffice/install.png" />','<?php echo T_('Review/Uninstall') ?>','index2.php?option=com_installer&element=component',null,'<?php echo T_('Install/Uninstall components') ?>'],
  				_cmSplit,
<?php
        $query = "SELECT * FROM #__components WHERE name <> 'frontpage' and name <> 'media manager' ORDER BY ordering,name"
        ;
        $database->setQuery( $query );
        $comps = $database->loadObjectList();   // component list
        $subs = array();    // sub menus
        // first pass to collect sub-menu items
        foreach ($comps as $row) {
            if ($row->parent) {
                if (!array_key_exists( $row->parent, $subs )) {
                    $subs[$row->parent] = array();
                }
                $subs[$row->parent][] = $row;
            }
        }
        $topLevelLimit = 19; //You can get 19 top levels on a 800x600 Resolution
        $topLevelCount = 0;
        foreach ($comps as $row) {
            if ($editAllComponents | $acl->acl_check( 'administration', 'edit', 'users', $usertype, 'components', $row->option )) {
                if ($row->parent == 0 && (trim( $row->admin_menu_link ) || array_key_exists( $row->id, $subs ))) {
                    $topLevelCount++;
                    if ($topLevelCount > $topLevelLimit) {
                        continue;
                    }
                    $name = addslashes( $row->name );
                    $alt = addslashes( $row->admin_menu_alt );
                    $link = $row->admin_menu_link ? "'index2.php?$row->admin_menu_link'" : "null";
                    echo "\t\t\t\t['<img src=\"../includes/$row->admin_menu_img\" />','$name',$link,null,'$alt'";
                    if (array_key_exists( $row->id, $subs )) {
                        foreach ($subs[$row->id] as $sub) {
	                        echo ",\n";
                            $name = addslashes( $sub->name );
                            $alt = addslashes( $sub->admin_menu_alt );
                            $link = $sub->admin_menu_link ? "'index2.php?$sub->admin_menu_link'" : "null";
                            echo "\t\t\t\t\t['<img src=\"../includes/$sub->admin_menu_img\" />','$name',$link,null,'$alt']";
                        }
                    }
                    echo "\n\t\t\t\t],\n";
                }
            }
        }
        if ($topLevelLimit < $topLevelCount) {
            echo "\t\t\t\t['<img src=\"../includes/js/ThemeOffice/sections.png\" />','".T_('More Components...')."','index2.php?option=com_admin&task=listcomponents',null,'".T_('More Components')."'],\n";
        }
?>
			],
<?php
	// Modules Sub-Menu
		if ($installModules | $editAllModules) {
?>			_cmSplit,
			[null,'<?php echo T_('Modules') ?>',null,null,'<?php echo T_('Module Management') ?>',
<?php
			if ($installModules) {
?>				['<img src="../includes/js/ThemeOffice/install.png" />', '<?php echo T_('Review/Uninstall') ?>', 'index2.php?option=com_installer&element=module', null, '<?php echo T_('Install custom modules') ?>'],
				_cmSplit,
<?php
			}
			if ($editAllModules) {
?>				['<img src="../includes/js/ThemeOffice/module.png" />', '<?php echo T_('Site Modules') ?>', "index2.php?option=com_modules", null, '<?php echo T_('Manage Site modules') ?>'],
				['<img src="../includes/js/ThemeOffice/module.png" />', '<?php echo T_('Administrator Modules') ?>', "index2.php?option=com_modules&client=admin", null, '<?php echo T_('Manage Administrator modules') ?>'],
<?php
			}
?>			],
<?php
		} // if ($installModules | $editAllModules)
	} // if $installComponents
	// Mambots Sub-Menu
	if ($installMambots | $editAllMambots) {
?>			_cmSplit,
			[null,'<?php echo T_('Mambots') ?>',null,null,'<?php echo T_('Mambot Management') ?>',
<?php
		if ($installMambots) {
?>				['<img src="../includes/js/ThemeOffice/install.png" />', '<?php echo T_('Review/Uninstall') ?>', 'index2.php?option=com_installer&element=mambot', null, '<?php echo T_('Install custom mambot') ?>'],
				_cmSplit,
<?php
		}
		if ($editAllMambots) {
?>				['<img src="../includes/js/ThemeOffice/module.png" />', '<?php echo T_('Site Mambots') ?>', "index2.php?option=com_mambots", null, '<?php echo T_('Manage Site Mambots') ?>'],
<?php
		}
?>			],
<?php
	}
?>
<?php
	// Installer Sub-Menu
	if ($installModules) {
?>			_cmSplit,
			[null,'<?php echo T_('Installers') ?>',null,null,'<?php echo T_('Installer List') ?>',
				['<img src="../includes/js/ThemeOffice/install.png" />','<?php echo T_('Universal') ?>','index2.php?option=com_installer&element=universal&client=admin',null,'<?php echo T_('Install Any Plugin') ?>'],
				<?php //this features uses simplexml which in not support pre php 5
				if (phpversion() >=5) { ?> 				
				['<img src="../includes/js/ThemeOffice/install.png" />','<?php echo T_('Add-on Packages') ?>','index2.php?option=com_installer&task=addon&element=universal&client=admin',null,'<?php echo T_('Mambo Add-on Pakcages') ?>'],
				<?php } ?>
				//Commenting out The Source option for now since it is not working...
				/*
				<?php if (ini_get('allow_url_fopen')) { ?>
				['<img src="../includes/js/ThemeOffice/install.png" />','<?php echo T_('The Source') ?>','index2.php?option=com_installer&task=thesource&element=universal&client=admin',null,'<?php echo T_('Install from The Source') ?>'],
				<?php } ?>
				*/
			],
<?php
	} // if ($installModules)
	// Messages Sub-Menu
	if ($canConfig) {
?>			_cmSplit,
  			[null,'<?php echo T_('Messages') ?>',null,null,'<?php echo T_('Messaging Management') ?>',
  				['<img src="../includes/js/ThemeOffice/messaging_inbox.png" />','<?php echo T_('Inbox') ?>','index2.php?option=com_messages',null,'<?php echo T_('Private Messages') ?>'],
  				['<img src="../includes/js/ThemeOffice/messaging_config.png" />','<?php echo T_('Configuration') ?>','index2.php?option=com_messages&task=config&hidemainmenu=1',null,'<?php echo T_('Configuration') ?>']
  			],
<?php
	// System Sub-Menu
?>			_cmSplit,
  			[null,'<?php echo T_('System') ?>',null,null,'<?php echo T_('System Management') ?>',
<?php
  		if ($canConfig) {
?>				['<img src="../includes/js/ThemeOffice/checkin.png" />', '<?php echo T_('Global Checkin') ?>', 'index2.php?option=com_checkin', null,'<?php echo T_('Check-in all checked-out items') ?>'],
				['<img src="../includes/js/ThemeOffice/sysinfo.png" />', '<?php echo T_('System Information') ?>', 'index2.php?option=com_admin&task=sysinfo', null, '<?php echo T_('View System Information') ?>'],
				<?php //this features uses simplexml which in not support pre php 5
				if (phpversion() >=5) { ?> 
				['<img src="../includes/js/ThemeOffice/globe2.png" />', '<?php echo T_('Check for Updates') ?>', 'index2.php?option=com_admin&task=versioninfo', null, '<?php echo T_('Check for Updates') ?>'],
				<?php } ?>
<?php
			if ($mosConfig_caching) {
?>				['<img src="../includes/js/ThemeOffice/config.png" />','<?php echo T_('Clean Cache') ?>','index2.php?option=com_content&task=clean_cache',null,'<?php echo T_('Clean the content items cache') ?>'],
<?php
			}
		}
?>			],
<?php
			}
?>			_cmSplit,
<?php
	// Help Sub-Menu
			if(file_exists(mamboCore::get('rootPath').'/help/mambo.whatsnew.html')){?>[null,'<?php echo T_('Help') ?>','index2.php?option=com_admin&task=help',null,null]<?php } ?>
		];
		cmDraw ('myMenuID', myMenu, 'hbr', cmThemeOffice, 'ThemeOffice');
		</script>
<?php
	}
}
$cache =& mosCache::getCache( 'mos_fullmenu' );

mosFullAdminMenu::show( $my->usertype );
//$cache->call( 'mosFullAdminMenu::show', $my->usertype );
?>
