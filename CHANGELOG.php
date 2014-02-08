<?php
/**
* Changes History from Mambo
* @package Mambo
* @author Mambo Foundation Inc see README.php
* @copyright Mambo Foundation Inc.
* See COPYRIGHT.php for copyright notices and details.
* @license GNU/GPL Version 2, see LICENSE.php
* Mambo is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; version 2 of the License.
*/
// no direct access
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
?>
Changelog:
------------
This is a non-exhaustive (but still near complete) changelog for
Mambo 4.6, including beta and release candidate versions.
Our thanks to all those people who've contributed bug reports and
code fixes.
Legend:
# -> Bug Fix
+ -> Addition
! -> Change
- -> Removed
! -> Note
--------------------------- 4.6.3 Release -------------------------------
r1498 | alwarren | 2007-12-22 23:25:14 -0800 (Sat, 22 Dec 2007) | 1 line
# Fixed missing title tag in mosHtmlHelper::showHead
---
r1496 | ocs_cms | 2007-12-22 23:09:28 -0800 (Sat, 22 Dec 2007) | 1 line
# copyright fix
---
r1495 | ocs_cms | 2007-12-22 13:57:31 -0800 (Sat, 22 Dec 2007) | 1 line
4.6.3 CHANGELOG.php
---
r1494 | alwarren | 2007-12-22 01:21:44 -0800 (Sat, 22 Dec 2007) | 2 lines
# Fixed weblinks title multiple slashes added when editing web links
# Fixed weblinks title slashes showing in frontend
---
r1493 | ocs_cms | 2007-12-21 14:18:04 -0800 (Fri, 21 Dec 2007) | 1 line
trims are not necessary. mosGetParam already trims the string.
---
r1492 | ocs_cms | 2007-12-21 13:56:38 -0800 (Fri, 21 Dec 2007) | 1 line
For the sake of sanity -http://secunia.com/advisories/28133/- some (int) typecasts, and htmlspecialchars modifications in index.php and core.classes.php .
---
r1491 | ocs_cms | 2007-12-21 12:50:32 -0800 (Fri, 21 Dec 2007) | 1 line
"null" checks in setPathway and menuCheck functions in core.classes.php . with invalid $Itemid's it was causing the good ole' Trying to get property of non-object in ...
---
r1490 | ocs_cms | 2007-12-21 12:13:11 -0800 (Fri, 21 Dec 2007) | 1 line
deleted the $doctype line.
r1489 | ocs_cms | 2007-12-18 23:25:56 -0800 (Tue, 18 Dec 2007) | 1 line
version updated
---
r1488 | alwarren | 2007-12-18 21:49:46 -0800 (Tue, 18 Dec 2007) | 1 line
# Fixed created_by changing in com_content when content modified
---
r1487 | alwarren | 2007-12-18 08:54:54 -0800 (Tue, 18 Dec 2007) | 1 line
! Removing helper code examples from templates/waterandstone/index.php
---
r1486 | alwarren | 2007-12-18 08:53:37 -0800 (Tue, 18 Dec 2007) | 2 lines
! Removing doctype from config per Cem's request.
! Removing helper code examples from templates/waterandstone/index.php
---
r1485 | cauld | 2007-12-17 23:39:13 -0800 (Mon, 17 Dec 2007) | 1 line
---
r1484 | elpie | 2007-12-17 21:02:58 -0800 (Mon, 17 Dec 2007) | 1 line
#updated the link to the security notification list
---
r1482 | alwarren | 2007-12-17 08:32:18 -0800 (Mon, 17 Dec 2007) | 1 line
! Changed default document type in configuration.sample.php
---
r1480 | alwarren | 2007-12-17 07:23:24 -0800 (Mon, 17 Dec 2007) | 1 line
! Added onAfterStart system mambot trigger per 3pd requests.
---
r1479 | elpie | 2007-12-17 03:47:09 -0800 (Mon, 17 Dec 2007) | 1 line
#updated credits and fixed incorrect file name within config file
---
r1478 | ocs_cms | 2007-12-16 23:51:04 -0800 (Sun, 16 Dec 2007) | 1 line
minor quotation issue with 'id'
---
r1477 | ocs_cms | 2007-12-16 17:13:41 -0800 (Sun, 16 Dec 2007) | 1 line
---
r1476 | ocs_cms | 2007-12-16 17:10:43 -0800 (Sun, 16 Dec 2007) | 1 line
---
r1474 | ocs_cms | 2007-12-16 16:56:44 -0800 (Sun, 16 Dec 2007) | 2 lines
Removed file/folder
configuration.sample.php replaces this file
---
r1472 | ocs_cms | 2007-12-16 16:49:22 -0800 (Sun, 16 Dec 2007) | 1 line
---
r1470 | andphe | 2007-12-16 14:14:52 -0800 (Sun, 16 Dec 2007) | 1 line
# when the flock function fails the save/apply action produce empty catalogs
---
r1469 | alwarren | 2007-12-16 06:17:54 -0800 (Sun, 16 Dec 2007) | 1 line
! More backporting for the helper class
---
r1467 | ocs_cms | 2007-12-15 17:56:56 -0800 (Sat, 15 Dec 2007) | 1 line
mosuser::store failed error fixed. this was happening when trying to modify the admin with installation-default password.
---
r1466 | ocs_cms | 2007-12-15 17:22:14 -0800 (Sat, 15 Dec 2007) | 1 line
sample data for "topmenu" changed so that, the two "Home" links won't use different Itemid
---
r1465 | cauld | 2007-12-15 14:21:51 -0800 (Sat, 15 Dec 2007) | 1 line
! Adding to last nights MOStlyCE update to fix front-end editing
---
r1464 | alwarren | 2007-12-15 13:49:26 -0800 (Sat, 15 Dec 2007) | 1 line
- Removing example.php from tm_encrypt folder
---
r1463 | cauld | 2007-12-14 22:50:31 -0800 (Fri, 14 Dec 2007) | 1 line
! Updating xml file list in 4.6.3 and 4.7 for MOStlyCE v2.4
---
r1462 | cauld | 2007-12-14 22:47:27 -0800 (Fri, 14 Dec 2007) | 1 line
! Updating MOStlyCE in 4.6.3 and 4.7 to v2.4.  Patching a potential security hole.
---
r1460 | andphe | 2007-12-14 19:41:38 -0800 (Fri, 14 Dec 2007) | 1 line
# clearing warnings at frontpage manager when no categories exists
---
r1449 | arpee | 2007-12-04 13:34:14 -0800 (Tue, 04 Dec 2007) | 1 line
Fixed mod_templatechooser.php based on Chads work on 4.7 version
---
r1447 | cauld | 2007-12-04 11:52:54 -0800 (Tue, 04 Dec 2007) | 1 line
# Fixing editor initialization check
---
r1439 | ocs_cms | 2007-11-28 23:54:32 -0800 (Wed, 28 Nov 2007) | 2 lines
$class_sfx defined in the beginning but the rest of the file was referencing to $moduleclass_sfx .
$moduleclass_sfx replaced by $class_sfx
---
r1438 | ocs_cms | 2007-11-28 23:44:00 -0800 (Wed, 28 Nov 2007) | 4 lines
changed ; <param name="type" type="list" default="0" label="Target Window" description="Target window for link.">
to; <param name="target" type="list" default="0" label="Target Window" description="Target window for link.">
http://tracker.mambo-foundation.org/index.php?do=details&task_id=214
---
r1436 | alwarren | 2007-11-28 18:14:09 -0800 (Wed, 28 Nov 2007) | 1 line
#Fixed FS#214 - Make Link Item Parameters Consistent
---
r1432 | alwarren | 2007-11-28 08:52:22 -0800 (Wed, 28 Nov 2007) | 1 line
# Fixed FS#231 - Section Module cannot find Page parameters - Itemid missing in URL
---
r1429 | alwarren | 2007-11-27 19:11:22 -0800 (Tue, 27 Nov 2007) | 1 line
! back-porting some changes to core.helpers.php from 4.7
---
r1423 | andphe | 2007-11-26 21:09:26 -0800 (Mon, 26 Nov 2007) | 1 line
# dechex function portability for 32 and 64 bits
---
r1422 | arpee | 2007-11-26 14:49:37 -0800 (Mon, 26 Nov 2007) | 1 line
changed typo error on copyright of admin.media.html.php and admin.media.php from subpackage Massmail to subpackage Media Manager
---
r1420 | andphe | 2007-11-26 07:48:44 -0800 (Mon, 26 Nov 2007) | 1 line
# login and logout redirection page doesn't work using the login component
---
r1417 | alwarren | 2007-11-23 19:15:16 -0800 (Fri, 23 Nov 2007) | 1 line
! additional html helper code
---
r1412 | ocs_cms | 2007-11-21 00:01:20 -0800 (Wed, 21 Nov 2007) | 2 lines
made a tiny "Yourself" -> "yourself" wording change. http://tracker.mambo-foundation.org/index.php?do=details&task_id=282
The rest of the tracker item looks good
---
r1400 | alwarren | 2007-11-15 14:38:07 -0800 (Thu, 15 Nov 2007) | 1 line
+ Added cypher class to address form security
---
r1396 | ocs_cms | 2007-11-13 01:59:41 -0800 (Tue, 13 Nov 2007) | 1 line
fixed header comment blocks to meet coding standards and to remove the invalid link to GNU/GPL version 2 license.
---
r1386 | alwarren | 2007-11-09 08:47:10 -0800 (Fri, 09 Nov 2007) | 1 line
# Fixed a typo in a <br /> tag
---
r1385 | andphe | 2007-11-08 03:43:23 -0800 (Thu, 08 Nov 2007) | 1 line
# validating that a content item exists and it's published before to rate it.
---
r1383 | alwarren | 2007-11-06 05:45:12 -0800 (Tue, 06 Nov 2007) | 2 lines
# Additional fixes for  FS#289 - Fix for Menu Access
$maxaccess was not set properly in mosShowVIMenu() and mosShowHFMenu()
---
r1381 | cauld | 2007-11-03 22:06:29 -0700 (Sat, 03 Nov 2007) | 1 line
! Changing editor initialization so that it is only loaded when it is actually needed (code contribed by andphe)
---
r1378 | alwarren | 2007-11-03 06:57:07 -0700 (Sat, 03 Nov 2007) | 1 line
# Fixed FS#290 - Inconsistent validation of custom banner code
---
r1376 | alwarren | 2007-11-02 19:06:25 -0700 (Fri, 02 Nov 2007) | 1 line
# Fixed FS#266 - Bug in install XML parser
---
r1375 | alwarren | 2007-11-02 16:33:13 -0700 (Fri, 02 Nov 2007) | 1 line
# Fixed typo in mosShowHead
---
r1374 | alwarren | 2007-11-02 12:28:27 -0700 (Fri, 02 Nov 2007) | 1 line
# Additional fix for FS#282 - force blocked to 0 in saveUser for super administrators
---
r1371 | alwarren | 2007-11-02 11:48:10 -0700 (Fri, 02 Nov 2007) | 1 line
# Fixed  FS#282 - Can not delete or block super administrators
---
r1369 | alwarren | 2007-11-01 11:19:40 -0700 (Thu, 01 Nov 2007) | 3 lines
+ Added mosUriHelper class
! Moved helpers to core.helpers.php
# Fixed FS#234 - Problem in getPathway() function leads to missing links in Pathway
---
r1368 | andphe | 2007-11-01 04:54:58 -0700 (Thu, 01 Nov 2007) | 1 line
! taking off the url parameter from the rate item content form, a local url will be constructed instead
---
r1366 | andphe | 2007-10-27 21:50:27 -0700 (Sat, 27 Oct 2007) | 1 line
# Fixed FS#250 - Copying menu items: incorrectly copies items; Thanks to neo_fox
---
r1364 | alwarren | 2007-10-27 19:16:31 -0700 (Sat, 27 Oct 2007) | 1 line
# Fixed FS#289 - menu access not working
---
r1362 | ocs_cms | 2007-10-24 00:27:56 -0700 (Wed, 24 Oct 2007) | 1 line
unnecessary option with hardcoded com_syndicate removed
---
r1360 | arpee | 2007-10-23 02:04:47 -0700 (Tue, 23 Oct 2007) | 1 line
minor corrections on install4.php regarding hardcoded DBcreated values
---
r1359 | arpee | 2007-10-23 01:50:31 -0700 (Tue, 23 Oct 2007) | 1 line
fixed installation step back failure in firefox, also fixed bug when step 3 and 4 does not check if DB is created or not, it always return DBcreated variable to 1
---
r1357 | arpee | 2007-10-21 12:53:09 -0700 (Sun, 21 Oct 2007) | 1 line
fixed corrupt installation of extensions caused by missing trim function in character_date function of mosBasicXML class
---
r1354 | arpee | 2007-10-20 16:38:25 -0700 (Sat, 20 Oct 2007) | 1 line
Fixed Media Manager wrong recognition of filetypes. Ex: this_is_not_a_jpg.doc filename will be misrecognized as a jpg causing Division by Zero error when media manager tries to render a thumbnail off a non-image file, plus a few minor code enhancements
---
r1349 | arpee | 2007-10-18 08:03:27 -0700 (Thu, 18 Oct 2007) | 1 line
Fixed possibility to set child and grandchild items to be the parent of its own parent resulting in orphaned menu items
---
r1348 | arpee | 2007-10-18 07:53:38 -0700 (Thu, 18 Oct 2007) | 1 line
Fixed wrong sorting of files read from a directory, fixes the image and image folder sorting issue as well
---
r1347 | arpee | 2007-10-18 07:48:19 -0700 (Thu, 18 Oct 2007) | 1 line
Added line to prevent mosConfig_locale from being blank
---
r1346 | alwarren | 2007-10-18 05:08:00 -0700 (Thu, 18 Oct 2007) | 1 line
+ adding mosConfig_doctype to install4.php and configuration.php-dist
---
r1344 | alwarren | 2007-10-16 05:38:49 -0700 (Tue, 16 Oct 2007) | 1 line
+ Added search feature to Manage Translations in LM backend
---
r1343 | alwarren | 2007-10-15 05:04:02 -0700 (Mon, 15 Oct 2007) | 1 line
#Fixed FS#286 - Itemid is undefined
---
r1342 | alwarren | 2007-10-14 23:14:58 -0700 (Sun, 14 Oct 2007) | 1 line
! remove HTML doctypes from DTD arrays in mosHtmlHelper
---
r1341 | alwarren | 2007-10-14 22:17:14 -0700 (Sun, 14 Oct 2007) | 4 lines
+ added mosHtmlHelper class with DTD utilities (core.classes.php)
+ added $html helper object to index.php
+ added mosConfig_doctype with backend editing
! changed hard-coded DTD to $html->renderDocType() (index.php and WS default template)
---
r1340 | alwarren | 2007-10-12 22:21:26 -0700 (Fri, 12 Oct 2007) | 1 line
# Fixed FS#266 - Bug in install XML parser: strips spaces randomly in data
---
r1337 | alwarren | 2007-10-12 22:06:24 -0700 (Fri, 12 Oct 2007) | 1 line
# Fixed FS#247 - missing fields in class mosCategory
---
r1335 | alwarren | 2007-10-12 21:38:24 -0700 (Fri, 12 Oct 2007) | 1 line
# Fixed FS#248 - undefined class mosAdminMenus in HTML_toolbar
---
r1334 | alwarren | 2007-10-12 21:01:53 -0700 (Fri, 12 Oct 2007) | 1 line
# fixed FS#283 - disappearing filter list
---
r1332 | alwarren | 2007-10-12 09:10:25 -0700 (Fri, 12 Oct 2007) | 1 line
! added module buffering
---
r1331 | alwarren | 2007-10-11 07:05:06 -0700 (Thu, 11 Oct 2007) | 2 lines
! mosShowHead() updated to allow selective rendering or selective exclusion of head tags.
See function comments in core.classes.php for usage
---
r1330 | ninekrit | 2007-10-10 10:00:00 -0700 (Wed, 10 Oct 2007) | 1 line
#fixed menu com_search add 2 times in mainmenu
---
r1329 | alwarren | 2007-10-09 09:46:48 -0700 (Tue, 09 Oct 2007) | 3 lines
+ mosPath() function to sanitize directory separators
+ e() utility debugging function
+ pr() utility debugging function
---
r1323 | alwarren | 2007-10-02 06:30:40 -0700 (Tue, 02 Oct 2007) | 1 line
# Fixed FS #137 - Escaping Discontinuity in content item titles
---
r1322 | alwarren | 2007-10-02 04:51:02 -0700 (Tue, 02 Oct 2007) | 1 line
# Fixed FS#107 - Incorrectly escaped single quotes (in installation)
---
r1321 | andphe | 2007-09-28 16:34:42 -0700 (Fri, 28 Sep 2007) | 1 line
# Fixed FS#100 - No default homepage causes several errors
---
r1318 | cauld | 2007-09-27 13:19:25 -0700 (Thu, 27 Sep 2007) | 1 line
! Updating mostlydbadmin to v1.5 and adding suggestion from FS#263
---
r1316 | cauld | 2007-09-27 05:48:52 -0700 (Thu, 27 Sep 2007) | 1 line
# Fixing a few minor MOStlyCE issues (i.e.) tooltip change and FS#251
---
r1313 | cauld | 2007-09-27 04:59:15 -0700 (Thu, 27 Sep 2007) | 1 line
! Updating the geshi mambot to version 1.0.7.20
---
r1310 | elpie | 2007-09-27 01:26:45 -0700 (Thu, 27 Sep 2007) | 1 line
#fixed xhtml errors
---
r1309 | elpie | 2007-09-27 01:09:01 -0700 (Thu, 27 Sep 2007) | 1 line
#fixing XHTML compliance errors
---
r1306 | alwarren | 2007-09-26 19:39:34 -0700 (Wed, 26 Sep 2007) | 1 line
Fixed FS#10 - Consistent Nav Labels for Components Subnav
---
r1305 | alwarren | 2007-09-26 18:39:28 -0700 (Wed, 26 Sep 2007) | 1 line
Fixed FS#278 - xxx showing on query failure
---
r1303 | andphe | 2007-09-26 04:24:21 -0700 (Wed, 26 Sep 2007) | 1 line
# FS#206 - configuration.php can\'t include \'$\' in data values
---
r1300 | ocs_cms | 2007-09-23 02:31:15 -0700 (Sun, 23 Sep 2007) | 3 lines
http://tracker.mambo-foundation.org/index.php?do=details&task_id=239
global ... $configuration; was missing in 4.6.3 code. Added and committing
---
r1299 | ocs_cms | 2007-09-22 17:28:08 -0700 (Sat, 22 Sep 2007) | 4 lines
On behalf of Arpee:
http://tracker.mambo-foundation.org/index.php?do=details&task_id=271
do not send emails to block users, unless the stated otherwise by the newly added checkbox
---
r1296 | chanh | 2007-09-21 22:30:34 -0700 (Fri, 21 Sep 2007) | 1 line
FS#226 - Frontend editing disable for admin after patch 4.5.5 to 4.6.2
---
r1294 | andphe | 2007-09-21 20:58:27 -0700 (Fri, 21 Sep 2007) | 1 line
# Clearing PHP warning in the author filter of static content list when doesn't exists authors yet
---
r1291 | cauld | 2007-09-18 19:42:34 -0700 (Tue, 18 Sep 2007) | 1 line
! Updating version info to 4.6.3
---
r1285 | cauld | 2007-09-18 15:28:12 -0700 (Tue, 18 Sep 2007) | 1 line
#Fixing misnamed MOStlyCE file
---
r1284 | cauld | 2007-09-18 15:16:06 -0700 (Tue, 18 Sep 2007) | 1 line
! Updating bot_mostlyce to v2.3.  This includes the latest TinyMCE core (2.1.2), Neil's "Link to another article" suggestion, and additional performance gains via JSMin compression on the file manager and image manager plugins.
---
r1283 | cauld | 2007-09-18 15:10:05 -0700 (Tue, 18 Sep 2007) | 1 line
! Removing bot_mostlyce v2.2 in preparation for the v2.3 upgrade.
---
r1281 | cauld | 2007-09-18 15:05:20 -0700 (Tue, 18 Sep 2007) | 1 line
! Upgrading MOStlyCE Admin to v2.3.  This release contains tooltip changes, supports 4.6 & 4.7, and has a number of HTML/validation cleanup related changes.
---
r1276 | andphe | 2007-09-16 16:17:33 -0700 (Sun, 16 Sep 2007) | 1 line
# untranslated strings
---
r1272 | chanh | 2007-09-15 20:40:38 -0700 (Sat, 15 Sep 2007) | 1 line
Update with Mambo-Code.org
---
r1271 | chanh | 2007-09-15 20:39:34 -0700 (Sat, 15 Sep 2007) | 1 line
Update with Mambo-Code.org
---
r1267 | chanh | 2007-09-15 18:56:10 -0700 (Sat, 15 Sep 2007) | 1 line
Update with Mambo-Code site
---
r1266 | chanh | 2007-09-15 18:36:32 -0700 (Sat, 15 Sep 2007) | 1 line
Fix FS#273
---
r1263 | cauld | 2007-09-15 12:38:48 -0700 (Sat, 15 Sep 2007) | 1 line
!Changing the survey form to point potential memembers at http://members.mambo-foundation.org/ rather than sending emails to the Mambo team directly.
---
r1257 | andphe | 2007-09-01 22:20:45 -0700 (Sat, 01 Sep 2007) | 1 line
# bug fix FS#262 - banners clients next previous buttons directing wrongly
---
r1256 | andphe | 2007-09-01 21:52:27 -0700 (Sat, 01 Sep 2007) | 1 line
# bug fix FS#249 - Install mambo administrator templates not working, suggestion of Carlos Guimaraes (sekmet)
---
r1252 | ocs_cms | 2007-08-20 21:02:58 -0700 (Mon, 20 Aug 2007) | 1 line
Arpee's fix: http://tracker.mambo-foundation.org/?do=details&task_id=258
---
r1248 | cauld | 2007-08-19 07:05:30 -0700 (Sun, 19 Aug 2007) | 1 line
! Adding a bit more security to pollid.
---
r1236 | ocs_cms | 2007-08-08 11:00:56 -0700 (Wed, 08 Aug 2007) | 1 line
Administrator -> Manage Contacts. Link to Contact Category is fixed.
---
r1231 | andphe | 2007-07-30 13:49:35 -0700 (Mon, 30 Jul 2007) | 1 line
# security bug fix for phpmailer class
---
r1229 | andphe | 2007-07-28 21:05:04 -0700 (Sat, 28 Jul 2007) | 1 line
! Updating untranslated folder
---
r1226 | andphe | 2007-07-28 20:17:02 -0700 (Sat, 28 Jul 2007) | 1 line
# typo error on text direction
---
r1225 | andphe | 2007-07-28 19:41:33 -0700 (Sat, 28 Jul 2007) | 1 line
- removing unnecessary files for distro
---
r1224 | andphe | 2007-07-28 14:14:07 -0700 (Sat, 28 Jul 2007) | 1 line
# FS#233 - Application halts on CR/LF in textarea textfield in Global Configuration
---
r1222 | andphe | 2007-07-28 14:00:17 -0700 (Sat, 28 Jul 2007) | 1 line
# contact categories doesn't translate using NokKaew
---
r1221 | chanh | 2007-07-27 23:00:33 -0700 (Fri, 27 Jul 2007) | 1 line
Fix id to class to support moduleclass_sfx when use style = -2
---
r1220 | chanh | 2007-07-27 22:33:15 -0700 (Fri, 27 Jul 2007) | 1 line
Quick fix on bug generate lot of warning when click submit to send email in com_contact.
---
r1219 | andphe | 2007-07-27 19:12:48 -0700 (Fri, 27 Jul 2007) | 1 line
! Merging bug fixes from rev 1083 to rev 1218, from 4.7 branch to 4.6 branch
---
r1118 | adi | 2007-05-30 20:25:09 -0700 (Wed, 30 May 2007) | 1 line
fix bug that caused different Itemid in blog category menu type. ref: http://forum.mambo-foundation.org/showthread.php?p=26113
---
r1082 | enjoyman | 2007-04-26 04:08:36 -0700 (Thu, 26 Apr 2007) | 1 line
I fix some bug about using xml_parser_create() for use mambo on php4.
---
r1081 | cauld | 2007-04-25 06:18:10 -0700 (Wed, 25 Apr 2007) | 2 lines
# Missed file when committing module state fix earlier
# Fixing url and version check in the "Check for Updates" feature
---
r1080 | neilt | 2007-04-25 00:49:26 -0700 (Wed, 25 Apr 2007) | 1 line
# bug fix - images added with editor still showing when module set to not show - FS#207
---
r1079 | neilt | 2007-04-25 00:31:17 -0700 (Wed, 25 Apr 2007) | 1 line
# bug fix - FS#219 - Linked title in category Mgr linked to new page
---
r1078 | cauld | 2007-04-24 05:30:50 -0700 (Tue, 24 Apr 2007) | 1 line
! Implementing neilts suggestion of adding addition list limit values to front end page navigation (ex) 100,500,1000,2500, etc
---
r1077 | neilt | 2007-04-24 04:48:18 -0700 (Tue, 24 Apr 2007) | 1 line
Minor clean up of comments prior to public release
--------------------------- 4.6.2 Release -------------------------------
r1076 | cauld | 2007-04-23 20:08:10 -0600 (Mon, 23 Apr 2007) | 1 line
# Implementing konlong fix for module state noted here:
http://forum.mambo-foundation.org/showthread.php?p=22046
! Updating Changelog for the official 4.6.2 release
---
r1075 | andphe | 2007-04-23 17:53:33 -0600 (Mon, 23 Apr 2007) | 1 line
! clearing PHP warnings from the latest commit about cached mambots.
---
r1074 | andphe | 2007-04-23 08:30:13 -0600 (Mon, 23 Apr 2007) | 1 line
! Using the cached mambot instead of load from the dabatase
---
r1073 | andphe | 2007-04-23 08:22:55 -0600 (Mon, 23 Apr 2007) | 1 line
+ Addind the new getBot function to mosMambotHandler class to get a
cached mambot
---
r1072 | andphe | 2007-04-23 08:20:03 -0600 (Mon, 23 Apr 2007) | 1 line
! Rollback the enjoyman changes to admin.php
---
r1071 | cauld | 2007-04-22 08:43:10 -0600 (Sun, 22 Apr 2007) | 1 line
!  Updating Changelog for the official 4.6.2 release
---
r1071 | cauld | 2007-04-22 08:40:21 -0600 (Sun, 22 Apr 2007) | 1 line
!  Updating Changelog for the official 4.6.2 release
---
r1070 | cauld | 2007-04-21 20:43:33 -0600 (Sat, 21 Apr 2007) | 1 line
#  Fixing FS#216 - Latest Content Module Failure. (Note: Committing
for adi since he is having SVN issues).
---
r1069 | cauld | 2007-04-21 17:25:37 -0600 (Sat, 21 Apr 2007) | 1 line
# Minor Geshi correction
---
r1068 | cauld | 2007-04-21 15:14:43 -0600 (Sat, 21 Apr 2007) | 1 line
! Updating version info for the 4.6.2 release
---
r1067 | cauld | 2007-04-21 15:12:20 -0600 (Sat, 21 Apr 2007) | 1 line
! Updating bot_geshi to the latest version of Geshi available (1.0.7.19)
---
r1066 | cauld | 2007-04-21 14:30:41 -0600 (Sat, 21 Apr 2007) | 1 line
! Adding a new feature to the Add-On Installer.  It now shows latest and
current installed versions of the various addons with red conditional
highlighting for those that are outdated.
---
r1065 | ninekrit | 2007-04-21 11:46:38 -0600 (Sat, 21 Apr 2007) | 1 line
!update Thai language for installation
---
r1064 | cauld | 2007-04-21 09:52:08 -0600 (Sat, 21 Apr 2007) | 1 line
! Replacing the htaccess file for com_legacysef
---
r1063 | enjoyman | 2007-04-21 09:41:34 -0600 (Sat, 21 Apr 2007) | 1 line
I fix some bugs in file database.php.
---
r1062 | enjoyman | 2007-04-21 09:23:09 -0600 (Sat, 21 Apr 2007) | 1 line
I fix some bugs. about convert character set.
---
r1061 | enjoyman | 2007-04-21 07:47:18 -0600 (Sat, 21 Apr 2007) | 1 line
Correction for database.php
---
r1059 | enjoyman | 2007-04-21 07:38:27 -0600 (Sat, 21 Apr 2007) | 5 lines
-edit includes/database.php follow Andres told me and I edit something to
support any encoding.
-delete some files which is unnecessary in folder includes/ConvertTables/ .
-edit charsetmapping.php
-edit language/locales.xml ,delete character set "vps" from my
locales.xml I delete 'wincodepage'.
---
r1058 | enjoyman | 2007-04-21 05:49:18 -0600 (Sat, 21 Apr 2007) | 1 line
I edit core.classes.php , add this line.header('Content-type: text/html; '._ISO);
---
r1057 | enjoyman | 2007-04-21 03:01:45 -0600 (Sat, 21 Apr 2007) | 1 line
about deleting 'wincodepage'(I forgot this step,use global $mapcharset
at above of function save().)
---
r1056 | enjoyman | 2007-04-21 02:16:01 -0600 (Sat, 21 Apr 2007) | 1 line
I delete 'wincodepage' from anywhere because now it is not use for anything.
And I edit com_language for user cannot delete default language.
---
r1055 | neilt | 2007-04-20 07:29:51 -0600 (Fri, 20 Apr 2007) | 1 line
# minor bug fix to get params limit
---
r1054 | cauld | 2007-04-20 06:54:51 -0600 (Fri, 20 Apr 2007) | 1 line
# Fixing SEF issue with mos_comment capatcha code and preping for
mos_commentV1.2 release
---
r1053 | cauld | 2007-04-18 16:49:01 -0600 (Wed, 18 Apr 2007) | 1 line
! Commiting bot_mostlyceV2.2.  v2.0 was pretty outdated and had some browser
compatability issues. v2.2 is based on the latest version TinyMCE has to offer.
---
r1052 | cauld | 2007-04-18 16:37:27 -0600 (Wed, 18 Apr 2007) | 1 line
! Removing bot_mostlyce2.0.  Preparing to upgrade to bot_mostlyce2.2.
Due to the number of changes it is better to remove and readd.
---
r1051 | cauld | 2007-04-18 15:26:16 -0600 (Wed, 18 Apr 2007) | 1 line
! Adding files for the new legacy sef component
---
r1050 | neilt | 2007-04-18 02:16:15 -0600 (Wed, 18 Apr 2007) | 1 line
# bug fix - cloaking not working on email address
---
r1049 | ninekrit | 2007-04-18 01:24:13 -0600 (Wed, 18 Apr 2007) | 1 line
#update ConvertTable Charset mapping for Chainese/Georgian/Japanese/Thai
---
r1048 | andphe | 2007-04-17 21:11:10 -0600 (Tue, 17 Apr 2007) | 1 line
# improve the database nokkaew patch
---
r1047 | andphe | 2007-04-17 19:16:44 -0600 (Tue, 17 Apr 2007) | 1 line
! reestablishing the 4.6 sef engine
---
r1046 | neilt | 2007-04-17 01:24:08 -0600 (Tue, 17 Apr 2007) | 1 line
Modifications to allow comments to link directly to relating article
---
r1044 | cauld | 2007-04-13 21:50:35 -0600 (Fri, 13 Apr 2007) | 1 line
# Fixing  FS#204 - Page Impression Statistics Error Warning
---
r1043 | andphe | 2007-04-13 07:18:18 -0600 (Fri, 13 Apr 2007) | 1 line
# FS#35 - Safe mode problem, bug fixed, now when the Safe Mode is enabled
phpgettext will be used instead of gettext and the calls to putenv will
be avoided
---
r1042 | andphe | 2007-04-13 07:15:00 -0600 (Fri, 13 Apr 2007) | 1 line
# Page title for frontpage doesn't take effect
---
r1041 | andphe | 2007-04-13 07:14:08 -0600 (Fri, 13 Apr 2007) | 1 line
! Rollback the SEF engine
---
r1040 | elpie | 2007-04-13 07:10:11 -0600 (Fri, 13 Apr 2007) | 1 line
#fixed second incorrectly written div
---
r1039 | elpie | 2007-04-13 07:04:28 -0600 (Fri, 13 Apr 2007) | 1 line
#removed deprecated name element from the form so it will now validate
---
r1038 | elpie | 2007-04-13 07:02:41 -0600 (Fri, 13 Apr 2007) | 1 line
#corrected hidden input that had been placed outside of the div
---
r1037 | elpie | 2007-04-13 00:01:44 -0600 (Fri, 13 Apr 2007) | 1 line
# Fixed header blocks that were overwritten in svn
---
r1036 | andphe | 2007-04-09 06:19:03 -0600 (Mon, 09 Apr 2007) | 1 line
# bad locale string returned in Windows when the language doesn't have a
iso3166_3 code in locales.xml
---
r1035 | andphe | 2007-04-09 06:17:19 -0600 (Mon, 09 Apr 2007) | 1 line
! alter the order of mos_polls and mos_poll_menu in the SQL query, this
doesn't affect the behavior of the code but helps to nokkaew
---
r1034 | cauld | 2007-04-08 10:27:09 -0600 (Sun, 08 Apr 2007) | 2 lines
# Fixing The Source sample data RSS feed link.  Pointed to the old url.
It was fixed before, but overwritten in SVN.
! Adjusting RSS feed timeout
---
r1033 | andphe | 2007-03-31 22:51:12 -0600 (Sat, 31 Mar 2007) | 1 line
# Bug fixed, the mambot generate wrong url's when SEF is enabled
---
r1032 | cauld | 2007-03-27 06:37:01 -0600 (Tue, 27 Mar 2007) | 1 line
#  Fixing FS#208 - Comments on NewsFlash
---
r1031 | cauld | 2007-03-26 06:43:05 -0600 (Mon, 26 Mar 2007) | 1 line
# Fixing FS#84 - HTML tags stripped from module parameter type textarea
---
r1030 | cauld | 2007-03-25 12:06:00 -0600 (Sun, 25 Mar 2007) | 1 line
! Adding notices to the Install about MySQL strict mode not being supported
---
r1029 | cauld | 2007-03-25 11:00:37 -0600 (Sun, 25 Mar 2007) | 1 line
! Adjusting mostlyce.php per FS#155.  Will come in mambot v2.2 releases+,
but wanted to adjust this version included by default.
---
r1028 | cauld | 2007-03-24 21:34:41 -0600 (Sat, 24 Mar 2007) | 1 line
! Implementing suggestions from konlong for the universal installer
---
r1027 | andphe | 2007-03-23 16:22:49 -0600 (Fri, 23 Mar 2007) | 2 lines
# Bug Fix Adding Itemid to Wrapper menu links, because when exists two
or more wrapper links in menu all them points to the last added
# Adding lang parameter to URLs when nokkaew is used
---
r1026 | cauld | 2007-03-22 13:32:46 -0600 (Thu, 22 Mar 2007) | 1 line
! Making some improvements to the post installation survey.
---
r1025 | cauld | 2007-03-21 20:49:23 -0600 (Wed, 21 Mar 2007) | 1 line
! Updating the geshi mambot from version 1.0.4 to 1.0.7.18 and adding
highlight support for mysql, perl, and python
---
r1023 | cauld | 2007-03-20 05:50:33 -0600 (Tue, 20 Mar 2007) | 1 line
! Updating copyright.php to include Tango
---
r1022 | cauld | 2007-03-20 05:42:53 -0600 (Tue, 20 Mar 2007) | 1 line
! Changing out the default logo inside each template.  Using a new Mambo
image provided by ricoflan
---
r1021 | cauld | 2007-03-19 22:59:31 -0600 (Mon, 19 Mar 2007) | 1 line
! Adding new icon to the cpanel for "Get Support" and updating the install
splash page with the forum link.  Making official support easier to find.
---
r1020 | andphe | 2007-03-19 09:11:13 -0600 (Mon, 19 Mar 2007) | 1 line
! discard nokkaew patch for administration side
---
r1019 | andphe | 2007-03-16 22:21:56 -0600 (Fri, 16 Mar 2007) | 1 line
+ adding tagalog language to locales.xml
---
r1018 | andphe | 2007-03-16 22:17:55 -0600 (Fri, 16 Mar 2007) | 1 line
# improved nokkaew support
---
r1017 | andphe | 2007-03-16 06:10:37 -0600 (Fri, 16 Mar 2007) | 1 line
# clearing PHP notices when a new user is created
---
r1016 | ninekrit | 2007-03-13 06:42:47 -0600 (Tue, 13 Mar 2007) | 1 line
#Add Nokkaew patch, Now Nokkaew don't need DOMIT anymore
---
r1015 | enjoyman | 2007-03-09 20:47:15 -0700 (Fri, 09 Mar 2007) | 1 line
I edited function mosTreeRecurse() for more performance, tree object can
identify other parent variable more than 'parent' and it can get level
of tree from 'level' variable.
---
r1014 | andphe | 2007-03-09 14:28:13 -0700 (Fri, 09 Mar 2007) | 1 line
# some strings with special chars aren't translatable
---
r1013 | andphe | 2007-03-09 12:47:15 -0700 (Fri, 09 Mar 2007) | 1 line
+ adding vietnamese language to locales.xml
---
r1012 | andphe | 2007-03-09 05:38:15 -0700 (Fri, 09 Mar 2007) | 1 line
! untranslated strings
---
r1011 | andphe | 2007-03-09 05:36:22 -0700 (Fri, 09 Mar 2007) | 1 line
# mambots description doesn't translate
---
r1010 | andphe | 2007-03-09 05:35:01 -0700 (Fri, 09 Mar 2007) | 1 line
# Some translations still empty after save the catalog
---
r1009 | andphe | 2007-03-09 05:33:37 -0700 (Fri, 09 Mar 2007) | 1 line
# clearing notices using more than two plural forms
---
r1008 | andphe | 2007-03-09 05:29:42 -0700 (Fri, 09 Mar 2007) | 1 line
! updating the untranslated folder for 4.6.2
---
r1007 | andphe | 2007-03-08 11:31:14 -0700 (Thu, 08 Mar 2007) | 1 line
# error messages is displayed in the browser when the URl parameter for
mod_wrapper is left blank
---
r1006 | cauld | 2007-03-07 19:58:12 -0700 (Wed, 07 Mar 2007) | 1 line
! Making some compatability changes as suggested by konlong here:
(http://forum.mambo-foundation.org/showthread.php?p=18280#post18280)
---------------- 4.6.2 Bug Stomp Pre-Release 2 ---------------------------
r1005 | cauld | 2007-03-04 20:24:53 -0700 (Sun, 04 Mar 2007) | 1 line
! Updating Changelog and Version info for 4.6.2 Bug Stomp Pre-Release 2
---
r1004 | chanh | 2007-03-04 11:13:53 -0700 (Sun, 04 Mar 2007) | 1 line
Correct Banner links
---
r1003 | chanh | 2007-03-04 10:20:25 -0700 (Sun, 04 Mar 2007) | 1 line
Link to Security mailing list in sample data for both type of install
---
r1001 | andphe | 2007-03-03 15:04:15 -0700 (Sat, 03 Mar 2007) | 1 line
# fs#180 SEF not working on 4.6.1
---
r1000 | andphe | 2007-03-03 10:59:39 -0700 (Sat, 03 Mar 2007) | 1 line
! Adding T_ to untranslated strings
---
r999 | alwarren | 2007-02-20 12:27:29 -0700 (Tue, 20 Feb 2007) | 1 line
Removed improper pass-by-reference on line 46
---
r998 | alwarren | 2007-02-20 11:54:56 -0700 (Tue, 20 Feb 2007) | 1 line
Fixed  FS#202 — retrieveResults does not use key with loadAssocList
---
r997 | alwarren | 2007-02-20 11:37:55 -0700 (Tue, 20 Feb 2007) | 1 line
Fixed FS#176 loadAssocList was not returning a proper list
---
r996 | adi | 2007-02-20 02:33:05 -0700 (Tue, 20 Feb 2007) | 1 line
add blank value for mosparameter if there's no contact detail
found.dicuss: http://forum.mambo-foundation.org/showthread.php?t=3418
---
r995 | cauld | 2007-02-19 06:56:53 -0700 (Mon, 19 Feb 2007) | 1 line
! Updating changelog and version info for 4.6.2 bug stomp pre-release
---------------- 4.6.2 Bug Stomp Pre-Release 1 -----------------------------
r994 | andphe | 2007-02-14 04:43:17 -0700 (Wed, 14 Feb 2007) | 1 line
# extract action doesn't go to deep folders in linux systems
---
r993 | andphe | 2007-02-12 15:55:14 -0700 (Mon, 12 Feb 2007) | 1 line
# update action doesn't create a .mo file when the language is imported
without the LC_MESSAGES folder
---
r992 | andphe | 2007-02-11 13:30:10 -0700 (Sun, 11 Feb 2007) | 1 line
! changing copyright notices
---
r991 | andphe | 2007-02-11 13:28:11 -0700 (Sun, 11 Feb 2007) | 2 lines
! ignore offline check for the admin side
# help button in cpanel isn't displayed after login to the admin side
---
r990 | cauld | 2007-02-10 16:18:31 -0700 (Sat, 10 Feb 2007) | 1 line
! Updating MOStlyCE Admin to v2.1.  Adds i18n support (Andphe made these mods).
---
r989 | cauld | 2007-02-10 15:55:42 -0700 (Sat, 10 Feb 2007) | 1 line
! Updating MOStlyDB Admin to v1.3.  Adds i18n support (Andphe made these mods).
---
r988 | elpie | 2007-02-09 03:33:34 -0700 (Fri, 09 Feb 2007) | 1 line
!corrected sample data where links were not closed
---
r987 | alwarren | 2007-02-08 23:04:03 -0700 (Thu, 08 Feb 2007) | 1 line
Fixed missing variables $gid and $acl
---
r986 | elpie | 2007-02-08 18:18:30 -0700 (Thu, 08 Feb 2007) | 1 line
!corrected typo in contact sample data
---
r985 | elpie | 2007-02-08 18:04:58 -0700 (Thu, 08 Feb 2007) | 1 line
!fixed incorrect rss feed link for The Source
---
r984 | enjoyman | 2007-02-08 06:28:45 -0700 (Thu, 08 Feb 2007) | 1 line
I edited /administrator/components/com_admin/admin.admin.html.php online
40 for clear error if internet is disabled.
---
r983 | enjoyman | 2007-02-08 05:47:14 -0700 (Thu, 08 Feb 2007) | 1 line
move calling method offlineCheck() to somewhere is better.
---
r982 | elpie | 2007-02-08 04:48:23 -0700 (Thu, 08 Feb 2007) | 1 line
!updated copyrights that had been overwritten
---
r981 | enjoyman | 2007-02-08 04:04:23 -0700 (Thu, 08 Feb 2007) | 1 line
I edited this file to check to show offline page if folder installation
is exists for backend page.
---
r980 | enjoyman | 2007-02-08 03:58:46 -0700 (Thu, 08 Feb 2007) | 1 line
I edited core.class.php to show offline page if folder installation is exists
when user go to backend page.
---
r979 | cauld | 2007-02-07 19:14:07 -0700 (Wed, 07 Feb 2007) | 1 line
# Fixing some error handling within the Universal Installer to better handle
submissions with a bad file path, no file, and/or no Internet connection.
---
r977 | alwarren | 2007-02-07 18:58:13 -0700 (Wed, 07 Feb 2007) | 1 line
Fixed Fixed FS#199 Special/registered access for content items
---
r976 | alwarren | 2007-02-07 18:56:47 -0700 (Wed, 07 Feb 2007) | 1 line
Fixed FS#199 Special/registered access for content items, mod_latestcontent.php
and mod_latestnews.php
---
r975 | enjoyman | 2007-02-07 05:59:53 -0700 (Wed, 07 Feb 2007) | 1 line
I edited file /administrator/includes/menubar.html.php for check only
default language is translated.
---
r974 | cauld | 2007-02-06 18:01:14 -0700 (Tue, 06 Feb 2007) | 1 line
# Removing php short tags found in the MOStlyCE integrated image manager
---
r973 | elpie | 2007-02-06 15:50:44 -0700 (Tue, 06 Feb 2007) | 1 line
!more copyright updates
---
r972 | elpie | 2007-02-06 06:48:02 -0700 (Tue, 06 Feb 2007) | 1 line
!removed old Miro copyright that was never updated
---
r971 | elpie | 2007-02-06 06:01:13 -0700 (Tue, 06 Feb 2007) | 1 line
!more copyright corrections
---
r970 | andphe | 2007-02-06 05:11:58 -0700 (Tue, 06 Feb 2007) | 1 line
! Change i18n to T_ functions
---
r969 | andphe | 2007-02-06 05:10:36 -0700 (Tue, 06 Feb 2007) | 1 line
+ Install button for Language Manager, Arpee's Contribution
---
r968 | elpie | 2007-02-06 04:40:15 -0700 (Tue, 06 Feb 2007) | 1 line
!updated copyright and contact email address
---
r967 | elpie | 2007-02-06 04:29:17 -0700 (Tue, 06 Feb 2007) | 1 line
!copyright notices updated
---
r966 | elpie | 2007-02-06 04:06:44 -0700 (Tue, 06 Feb 2007) | 1 line
!fixed php short open tag
---
r965 | elpie | 2007-02-06 04:05:23 -0700 (Tue, 06 Feb 2007) | 1 line
!more copyright updates
---
r964 | elpie | 2007-02-05 23:05:27 -0700 (Mon, 05 Feb 2007) | 1 line
Removed Miro notice from files and placed into copyright file.
---
r963 | enjoyman | 2007-02-05 10:26:01 -0700 (Mon, 05 Feb 2007) | 2 lines
I edit installation/sql/mambo.sql for fix bug between install mambo.
And I edit file installation/index.php, change from php short tags to
full tags
---
r962 | andphe | 2007-02-05 04:54:26 -0700 (Mon, 05 Feb 2007) | 1 line
# keeping update the xml language file to export the correct files, and
correcting various php notices
---
r961 | alwarren | 2007-02-05 03:01:21 -0700 (Mon, 05 Feb 2007) | 1 line
Restored lost commit in mosAdminMenus
---
r960 | andphe | 2007-02-04 14:02:39 -0700 (Sun, 04 Feb 2007) | 1 line
# FS#133 — Contacts don't show icons
---
r959 | cauld | 2007-02-04 09:15:37 -0700 (Sun, 04 Feb 2007) | 1 line
! Updating changelog
---
r958 | elpie | 2007-02-04 05:28:01 -0700 (Sun, 04 Feb 2007) | 1 line
!fixed short open tag
---
r957 | elpie | 2007-02-04 05:08:54 -0700 (Sun, 04 Feb 2007) | 1 line
!fixed short open tag. Updated copyright.
---
r956 | ninekrit | 2007-02-03 18:46:16 -0700 (Sat, 03 Feb 2007) | 1 line
!removed line check installation folder for admin site.
---
r955 | ninekrit | 2007-02-03 18:20:53 -0700 (Sat, 03 Feb 2007) | 1 line
!change function offlineCheck to not effect admin site.
---
r952 | cauld | 2007-02-02 05:17:47 -0700 (Fri, 02 Feb 2007) | 1 line
! Updating Mambo glossary help file
---
r951 | alwarren | 2007-02-02 00:16:30 -0700 (Fri, 02 Feb 2007) | 1 line
Removed reference to mamboxchange
---
r950 | cauld | 2007-02-01 22:22:15 -0700 (Thu, 01 Feb 2007) | 1 line
! Updating the credits file and removing 3rd party tool info since it has
been moved to the new copyright.php file
---
r949 | cauld | 2007-02-01 22:19:18 -0700 (Thu, 01 Feb 2007) | 1 line
! Updating sample data
---
r948 | cauld | 2007-02-01 22:02:04 -0700 (Thu, 01 Feb 2007) | 1 line
! Updating all old copyright statements to reference the new copyright.php
file for easier maintenance
---
r947 | cauld | 2007-02-01 06:28:51 -0700 (Thu, 01 Feb 2007) | 1 line
! Add group column to the modules table
---
r946 | ninekrit | 2007-02-01 03:35:57 -0700 (Thu, 01 Feb 2007) | 1 line
#add defined( '_VALID_MOS' ) when write langconfig.php
---
r945 | ninekrit | 2007-02-01 03:21:15 -0700 (Thu, 01 Feb 2007) | 1 line
!fixed default language to enlish language
---
r944 | cauld | 2007-01-31 23:54:07 -0700 (Wed, 31 Jan 2007) | 1 line
! New copyright file being added.  Contains Mambo copyright notice and 3rd
party copyright info as well.  To be referred to in all other files to make
future copyright updates much easier.
---
r943 | cauld | 2007-01-31 23:12:51 -0700 (Wed, 31 Jan 2007) | 1 line
! Updating credits file
---
r942 | cauld | 2007-01-31 23:04:53 -0700 (Wed, 31 Jan 2007) | 1 line
! Additional 2007 copyright updates
---
r941 | cauld | 2007-01-31 22:54:14 -0700 (Wed, 31 Jan 2007) | 1 line
! 2007 copyright update
---
r940 | andphe | 2007-01-31 17:19:00 -0700 (Wed, 31 Jan 2007) | 1 line
# FS#113 — Admin Help in Lite Version, no help button will showed when not help files exists
---
r939 | andphe | 2007-01-31 17:11:20 -0700 (Wed, 31 Jan 2007) | 1 line
# FS#175 — Missing Help file, no button help will be showed when not a file help is found
---
r938 | andphe | 2007-01-31 14:36:43 -0700 (Wed, 31 Jan 2007) | 1 line
!changing constants _LOGIN_SUCCESS and _LOGOUT_SUCCESS by T_ functions
---
r937 | andphe | 2007-01-31 14:05:48 -0700 (Wed, 31 Jan 2007) | 1 line
#FS#177 —  authenticator does not return true on valid login
---
r936 | ninekrit | 2007-01-31 06:15:10 -0700 (Wed, 31 Jan 2007) | 1 line
#change button padding for smooth in ubuntu too
---
r934 | andphe | 2007-01-29 15:52:43 -0700 (Mon, 29 Jan 2007) | 1 line
!changing mosConfig_lang to mosConfig_locale in all login frontend forms
---
r933 | andphe | 2007-01-29 15:26:57 -0700 (Mon, 29 Jan 2007) | 1 line
#plurals string isn't parsed fine
---
r932 | andphe | 2007-01-29 15:24:02 -0700 (Mon, 29 Jan 2007) | 1 line
#unescaped tool tips in edit catalogs in LM fixed
---
r931 | andphe | 2007-01-29 15:22:24 -0700 (Mon, 29 Jan 2007) | 1 line
!Changing T_( entries to T_
---
r930 | alwarren | 2007-01-29 10:06:46 -0700 (Mon, 29 Jan 2007) | 1 line
Fixed FS#99 Module group access
---
r928 | cauld | 2007-01-28 19:22:54 -0700 (Sun, 28 Jan 2007) | 1 line
! Updating changelog
r926 | ninekrit | 2007-01-28 06:25:42 -0700 (Sun, 28 Jan 2007) | 1 line
#update new installatin screen working with LM.
---
r923 | cauld | 2007-01-27 22:37:52 -0700 (Sat, 27 Jan 2007) | 1 line
! Updating credits file
---
r921 | cauld | 2007-01-27 16:15:14 -0700 (Sat, 27 Jan 2007) | 1 line
# Fixing RSS feed issue where the RSS feeds being created were ignoring
params defined within the com_syndication interface.
---
r920 | cauld | 2007-01-27 15:09:31 -0700 (Sat, 27 Jan 2007) | 1 line
! Updating sample data
---
r919 | cauld | 2007-01-27 14:32:45 -0700 (Sat, 27 Jan 2007) | 1 line
! Updating version.php
---
r918 | andphe | 2007-01-27 14:20:38 -0700 (Sat, 27 Jan 2007) | 1 line
improving language export action
---
r916 | andphe | 2007-01-27 05:37:00 -0700 (Sat, 27 Jan 2007) | 1 line
improving javascript for delete and translate actions
---
r915 | andphe | 2007-01-26 22:28:56 -0700 (Fri, 26 Jan 2007) | 1 line
adding defined( '_VALID_MOS' ) or die( 'Direct Access to this location
is not allowed.' );
---
r914 | andphe | 2007-01-26 22:18:36 -0700 (Fri, 26 Jan 2007) | 1 line
creating glossaries in another encoding different to utf-8
---
r913 | andphe | 2007-01-26 22:16:03 -0700 (Fri, 26 Jan 2007) | 1 line
adding headers for compiled .mo file and left out empty translations
---
r912 | andphe | 2007-01-26 21:59:01 -0700 (Fri, 26 Jan 2007) | 1 line
default English language cannot be deleted or translated
---
r910 | enjoyman | 2007-01-26 05:24:24 -0700 (Fri, 26 Jan 2007) | 1 line
delete folder cvs, it is not related.
---
r909 | ninekrit | 2007-01-26 05:06:09 -0700 (Fri, 26 Jan 2007) | 1 line
#change create new language will be redirect to languages screen instead
to edit screen
---
r908 | andphe | 2007-01-25 19:34:06 -0700 (Thu, 25 Jan 2007) | 1 line
using langtitle_iso3166-3 instead iso639_iso3166-2 for setlocale in
windows to load properly the translations when gettext is used
---
r907 | enjoyman | 2007-01-23 21:02:53 -0700 (Tue, 23 Jan 2007) | 1 line
edit some line in file admin.mambots.php for fix some bug about drop down
list for searching.
---
r906 | alwarren | 2007-01-23 16:30:00 -0700 (Tue, 23 Jan 2007) | 1 line
Fixed SQL injection in com_weblinks
---
r905 | alwarren | 2007-01-23 16:14:18 -0700 (Tue, 23 Jan 2007) | 1 line
Fixed SQL injection in cancel edit functions
---
r904 | enjoyman | 2007-01-23 11:33:33 -0700 (Tue, 23 Jan 2007) | 1 line
edit some line in file mambolanguage.class.php
---
r903 | enjoyman | 2007-01-23 11:16:53 -0700 (Tue, 23 Jan 2007) | 2 lines
add file charsetmapping.php,and edit file core.classes.php for include
file charsetmapping.php.edit file phpgettext.class.php for convert charsets
when you click auto translate in language manager.
---
r902 | enjoyman | 2007-01-22 07:55:06 -0700 (Mon, 22 Jan 2007) | 1 line
I edit class ConvertCharset in file core.classes.php on line 3349, comment
to not check empty string.
---
r901 | enjoyman | 2007-01-22 07:10:06 -0700 (Mon, 22 Jan 2007) | 1 line
delete file /includes/ConvertCharset.class.php because it's not used.It's
contents is in core.classes.php already.
---
r900 | enjoyman | 2007-01-22 05:47:56 -0700 (Mon, 22 Jan 2007) | 1 line
edit function iconvert() in file mambolanguage.class.php on line 217 for
create english iso-8859-1
---
r899 | andphe | 2007-01-22 05:40:58 -0700 (Mon, 22 Jan 2007) | 1 line
adding _VALID_MOS constant
---
r898 | enjoyman | 2007-01-22 05:35:57 -0700 (Mon, 22 Jan 2007) | 1 line
add if (!defined('_VALID_MOS')) define( '_VALID_MOS', 1 ); to files in
folder installation for install mambo.
---
r897 | enjoyman | 2007-01-22 05:26:07 -0700 (Mon, 22 Jan 2007) | 1 line
add if (!defined('_VALID_MOS')) define( '_VALID_MOS', 1 ); in file
/installation/index.php on line 16 for installation.
---
r896 | andphe | 2007-01-22 04:27:12 -0700 (Mon, 22 Jan 2007) | 1 line
the most functions was migrated from gettext commands to php code, some
validations for open_basedir and disable_functions was included
---
r895 | andphe | 2007-01-22 04:23:16 -0700 (Mon, 22 Jan 2007) | 1 line
The xml language file includes all the files (.po files, .mo files, and
glossary), a new "filetype" attribute was added to the xml file, now the
export action and the universal installer can donwload and upload a complete
language, the installer supports wincodepage, and the catalogs list just
show the .po files.
---
r894 | andphe | 2007-01-22 04:18:16 -0700 (Mon, 22 Jan 2007) | 1 line
export action now exports all .po files, the .mo file and the glossary
file, pclzip was fixed to work fine in windows
---
r893 | andphe | 2007-01-22 04:15:53 -0700 (Mon, 22 Jan 2007) | 1 line
now the remove action removes the glossary file too.
---
r892 | andphe | 2007-01-22 04:14:03 -0700 (Mon, 22 Jan 2007) | 1 line
restoring the old ampReplace function
---
r891 | enjoyman | 2007-01-18 07:05:15 -0700 (Thu, 18 Jan 2007) | 1 line
I change file core.classes.php in function fixLanguage() for support other
language more than english.I just change ordering of codes,define _ISO
before require file english.php.
---
r890 | adi | 2007-01-17 20:22:59 -0700 (Wed, 17 Jan 2007) | 2 lines
add IE 7 quirk mode bsed on user info
http://forum.mambo-foundation.org/showthread.php?t=2813
---
r889 | ninekrit | 2007-01-17 07:24:15 -0700 (Wed, 17 Jan 2007) | 1 line
#fixed HTML Validation
---
r888 | ninekrit | 2007-01-17 06:28:49 -0700 (Wed, 17 Jan 2007) | 3 lines
#verify html validation admin templates
#removed file not used in glossary folder
#update word "Show Banner:"
---
r887 | andphe | 2007-01-17 05:05:24 -0700 (Wed, 17 Jan 2007) | 1 line
improving auto translate and gettext detection
---
r886 | cauld | 2007-01-16 10:25:51 -0700 (Tue, 16 Jan 2007) | 1 line
# Still working on RSS / SEO bug.  This should be the final fix.  Had to
adjust $item_link a bit for the different modes.
---
r885 | cauld | 2007-01-16 09:51:42 -0700 (Tue, 16 Jan 2007) | 1 line
# Fixing RSS / SEO bug where RSS feeds were not being converted properly
when SEO was enabled.
---
r884 | andphe | 2007-01-13 21:34:55 -0700 (Sat, 13 Jan 2007) | 1 line
added the mosIsRTL function, this functions returns true when the current
language is RTL
---
r883 | cauld | 2007-01-13 20:24:58 -0700 (Sat, 13 Jan 2007) | 1 line
! Updating default meta description and keywords
---
r882 | andphe | 2007-01-12 11:52:16 -0700 (Fri, 12 Jan 2007) | 1 line
freeing the actions  "auto translate" and "update" from gettext dependencies,
and providing portability to the action "extract "
---
r881 | ninekrit | 2007-01-11 02:48:09 -0700 (Thu, 11 Jan 2007) | 1 line
+add meta http-equiv in popup
---
r880 | andphe | 2007-01-10 20:07:38 -0700 (Wed, 10 Jan 2007) | 1 line
the extract action now includes mambots, and some php and xml files
---
r879 | andphe | 2007-01-10 20:03:13 -0700 (Wed, 10 Jan 2007) | 1 line
not create a mo files after save changes
---
r878 | andphe | 2007-01-10 19:53:28 -0700 (Wed, 10 Jan 2007) | 1 line
compile the .mo file and free of gettext dependencies
---
r877 | andphe | 2007-01-10 16:12:20 -0700 (Wed, 10 Jan 2007) | 1 line
changing a line that gives a lot of problems with extract action.
---
r876 | ninekrit | 2007-01-10 04:58:11 -0700 (Wed, 10 Jan 2007) | 1 line
#fixed language don't used T_ function
---
r875 | andphe | 2007-01-09 05:32:31 -0700 (Tue, 09 Jan 2007) | 1 line
disabling the actions extract, update and auto_translate when gettext
isn't installed
---
r874 | ninekrit | 2007-01-08 20:51:29 -0700 (Mon, 08 Jan 2007) | 1 line
#fixed offline message
---
r873 | ninekrit | 2007-01-08 05:45:31 -0700 (Mon, 08 Jan 2007) | 1 line
#fixed wrong word
---
r872 | adi | 2007-01-07 20:16:22 -0700 (Sun, 07 Jan 2007) | 1 line
update image header
---
r871 | adi | 2007-01-07 19:15:46 -0700 (Sun, 07 Jan 2007) | 1 line
update link to help.mamboserver.com to docs.mambo-foundation.org
---
r870 | alwarren | 2007-01-07 16:49:38 -0700 (Sun, 07 Jan 2007) | 1 line
copyright update
---
r869 | neilt | 2007-01-06 03:39:30 -0700 (Sat, 06 Jan 2007) | 1 line
comments language work provided by Andphe
---
r866 | enjoyman | 2007-01-05 01:17:10 -0700 (Fri, 05 Jan 2007) | 1 line
I use class convertCharset instead function iconv().
---
r865 | neilt | 2007-01-04 05:16:48 -0700 (Thu, 04 Jan 2007) | 1 line
change to language feature, not yet fully implemented
---
r864 | neilt | 2007-01-04 04:59:37 -0700 (Thu, 04 Jan 2007) | 1 line
Changes to the captcha component to allow audio capture via .wav format
to aid accessibility. Removal of some fonts/rotating features that were
causing problems on some machines
---
r863 | andphe | 2007-01-03 15:54:31 -0700 (Wed, 03 Jan 2007) | 1 line
 FS#130 — Admin parameters translations added
---
r862 | andphe | 2007-01-03 14:42:29 -0700 (Wed, 03 Jan 2007) | 1 line
improving backward compatibility with old components, the english.php file
is loaded be default when no other is found
---
r861 | andphe | 2007-01-02 12:16:35 -0700 (Tue, 02 Jan 2007) | 1 line
en vs english fixed
---
r858 | andphe | 2006-12-30 17:41:18 -0700 (Sat, 30 Dec 2006) | 1 line
improving the export action
---
r857 | alwarren | 2006-12-29 13:51:50 -0700 (Fri, 29 Dec 2006) | 1 line
Fixed FS#170 - low level security issue in pdf.php
---
r854 | cauld | 2006-12-22 20:55:53 -0700 (Fri, 22 Dec 2006) | 1 line
# Fixing busted category manager admin form.  Had 4 errors and 34 warnings.
Still more work to be done, but the errors have been cleared.  It now has
0 errors and 24 warnings.
---
r853 | cauld | 2006-12-22 20:36:10 -0700 (Fri, 22 Dec 2006) | 1 line
# Fixing busted section manager admin form.  Had 4 errors and 34 warnings.
Still more work to be done, but the errors have been cleared.  It now has
0 errors and 24 warnings.
---
r846 | chanh | 2006-12-20 23:57:56 -0700 (Wed, 20 Dec 2006) | 1 line
Fix the editing on the backend that causes Mambo to be extremely slow
w/ huge user table, comment out for now
---
r842 | enjoyman | 2006-12-20 07:39:30 -0700 (Wed, 20 Dec 2006) | 1 line
I change line 226 from "return $NewEncoding->Convert($source,$fromcharset,$tocharset,true);"
to "return $NewEncoding->Convert($source,$fromcharset,$tocharset,false);"
---
r841 | enjoyman | 2006-12-19 20:09:55 -0700 (Tue, 19 Dec 2006) | 1 line
check set $adminside already or not.If yes,it is admind side.
---
r837 | chanh | 2006-12-18 19:44:53 -0700 (Mon, 18 Dec 2006) | 1 line
referencing FS#140 and FS#160
---
r836 | cauld | 2006-12-18 09:51:01 -0700 (Mon, 18 Dec 2006) | 1 line
! Updating CHANGELOG with latest change entries
---
r832 | cauld | 2006-12-17 13:38:59 -0700 (Sun, 17 Dec 2006) | 1 line
# Working on admin.content.html.php.  The edit content page had 4 errors
and 49 warnings which was hosing up the editor.  All errors have been
cleared and some warnings, down to 38.  The other warnings will be worked
on later.
---
r831 | cauld | 2006-12-16 13:32:33 -0700 (Sat, 16 Dec 2006) | 1 line
! Adjust MOStlyDB Admin again.
---
r830 | cauld | 2006-12-16 13:23:49 -0700 (Sat, 16 Dec 2006) | 1 line
! Adjusting security of MOStlyDB Admin.  Should only allow access to
super admins rather than all admins.
---
r827 | andphe | 2006-12-15 05:22:33 -0700 (Fri, 15 Dec 2006) | 1 line
FS#165 — Dropdown in Contact Component Fixed
---
r826 | enjoyman | 2006-12-14 20:38:18 -0700 (Thu, 14 Dec 2006) | 1 line
I edited administrator/components/com_languages/actions/save.action.php on
line 78,It need not to check and create $dir/$lang.po when install language.
But when you new language it proper create $dir/$domain.po from
$untranslated/$domain.pot.And I edited line 125 follow Andphe suggest me.
Not only that,I edited file
administrator/components/com_languages/views/templates/langform.tpl.php
to cannot save if _ISO value not same $language->charset value.
---
r823 | andphe | 2006-12-14 04:58:58 -0700 (Thu, 14 Dec 2006) | 1 line
Language export action fixed
---
r822 | enjoyman | 2006-12-13 20:48:46 -0700 (Wed, 13 Dec 2006) | 1 line
I use class convercharset instead iconv and edit,add some function for support it.
---
r821 | enjoyman | 2006-12-13 05:58:04 -0700 (Wed, 13 Dec 2006) | 1 line
restore locales.xml for support any character set more than utf-8.
---
r820 | enjoyman | 2006-12-12 20:22:48 -0700 (Tue, 12 Dec 2006) | 1 line
I edited file mambolanguage.class.php in line 52 from
if($page_ == "addpage") { to be if( ($page_ == "addpage") && ($task="save") )
{ and re-implement  and rename function attrEncoding() to be arrayEncoding()
and I add some function ,bindAttributes binds attributes and elements in array.
---
r819 | alwarren | 2006-12-12 14:42:48 -0700 (Tue, 12 Dec 2006) | 1 line
Fixed typo in function loadBotGroup
---
r818 | alwarren | 2006-12-11 16:53:41 -0700 (Mon, 11 Dec 2006) | 1 line
Fixed FS#169. Custom mambot fires when it shouldn't
---
r815 | andphe | 2006-12-08 21:02:08 -0700 (Fri, 08 Dec 2006) | 1 line
add phpdoc comments, copyright notices
---
r814 | ninekrit | 2006-12-08 05:53:45 -0700 (Fri, 08 Dec 2006) | 1 line
fixed bug when save configuration file.
---
r813 | enjoyman | 2006-12-08 05:24:06 -0700 (Fri, 08 Dec 2006) | 1 line
I edited this file for installation.
---
r812 | enjoyman | 2006-12-08 05:18:23 -0700 (Fri, 08 Dec 2006) | 2 lines
-I edited file includes/mambolanguage.class.php, add a function for convert
charset of attribute of this class from utf-8 to "encoding" attribute in xml
 file.Therefore I check edit or install language in save() function,if
$page_ is "addpage",it is install language.
-I edited file administrator\components\com_languages\views\templates\langform.tpl.php,
add 2 hidden fields for assigned "page_" to be "addpage" or "editpage" for
each mission.
---
r811 | ninekrit | 2006-12-08 04:45:58 -0700 (Fri, 08 Dec 2006) | 2 lines
change install1.php line 80 to T_('Next')
---
r810 | adi | 2006-12-07 00:12:53 -0700 (Thu, 07 Dec 2006) | 1 line
#fix bug in yesnoSelectList function with wrong value switch the other way around
---
r809 | alwarren | 2006-12-05 03:29:49 -0700 (Tue, 05 Dec 2006) | 1 line
#Fixed warning errors in modules/mod_newsflash.php when no items published
---
r808 | cauld | 2006-12-04 15:39:15 -0700 (Mon, 04 Dec 2006) | 1 line
# Adding a missing semicolon to emailForm function
---
r806 | neilt | 2006-12-04 02:11:18 -0700 (Mon, 04 Dec 2006) | 1 line
#bug fix - Content items still available after "Finish Publishing" date - FS#157
---
r804 | cauld | 2006-12-03 10:34:53 -0700 (Sun, 03 Dec 2006) | 1 line
# Fixing issue that prevented section & cat images from being displayed
---
r802 | neilt | 2006-12-01 07:24:11 -0700 (Fri, 01 Dec 2006) | 1 line
#bug fix - cannot send page email to a friend -FS#112
---
r798 | cauld | 2006-11-30 07:10:10 -0700 (Thu, 30 Nov 2006) | 1 line
! Updating Changelog with changes since 4.6.1
---
r796 | cauld | 2006-11-29 09:18:21 -0700 (Wed, 29 Nov 2006) | 1 line
! Updated the "Check for Updates" version.xml file for the 4.6.2 release
---
r790 | andphe | 2006-11-24 20:13:07 -0700 (Fri, 24 Nov 2006) | 1 line
Bug Fixed, liveBookMark crash when com_syndicate is not installed.
---
r789 | andphe | 2006-11-19 10:08:50 -0700 (Sun, 19 Nov 2006) | 1 line
Td_ and Tdn_ functions replaced by T_ and Tn_, from the frontend domain
to a default domain
---
r787 | neilt | 2006-11-17 00:54:16 -0700 (Fri, 17 Nov 2006) | 1 line
#bug fix - additional fix for menu error when submitting weblinks
---
r786 | neilt | 2006-11-15 01:03:55 -0700 (Wed, 15 Nov 2006) | 1 line
#Bug Fix - #FS122 - not returning ItemId when no blog categories
---
r785 | cauld | 2006-11-13 13:14:22 -0700 (Mon, 13 Nov 2006) | 1 line
! Updating JSCookMenu to latest release v2.0.3
---
r784 | neilt | 2006-11-10 13:37:54 -0700 (Fri, 10 Nov 2006) | 1 line
#Bug Fix - Search always returns Itemid=1 - FS#122
---
r783 | neilt | 2006-11-07 11:12:35 -0700 (Tue, 07 Nov 2006) | 1 line
#bug fix : Unchecked sendmail loop - FS#156
---
r782 | cauld | 2006-11-06 17:26:40 -0700 (Mon, 06 Nov 2006) | 1 line
! Adjusting grammer in regisitration.html.php
---
r781 | neilt | 2006-11-06 01:39:44 -0700 (Mon, 06 Nov 2006) | 1 line
#bug fix - ratings not showing
---
r780 | ninekrit | 2006-11-05 22:02:31 -0700 (Sun, 05 Nov 2006) | 1 line
update thai language in glossary
---
r779 | ninekrit | 2006-11-05 21:53:28 -0700 (Sun, 05 Nov 2006) | 1 line
fixed translation "Start" on tool tip display
---
r778 | neilt | 2006-11-03 06:40:36 -0700 (Fri, 03 Nov 2006) | 1 line
#bug fix - {mosimage} bot incorrectly shows intro images in main body if
intro text hidden - FS#145
---
r777 | neilt | 2006-11-03 05:52:47 -0700 (Fri, 03 Nov 2006) | 1 line
#bug fix : template parse bug when text outside of mosMainBody() - FS#102
---
r776 | neilt | 2006-11-03 05:43:17 -0700 (Fri, 03 Nov 2006) | 1 line
#bug fix : Content items automatically adding mailto links - FS#128
---
r775 | ninekrit | 2006-11-01 05:18:49 -0700 (Wed, 01 Nov 2006) | 1 line
Fixed save action
---
r774 | alwarren | 2006-10-31 23:55:03 -0700 (Tue, 31 Oct 2006) | 1 line
Fixed FS#127 — Strange problem with showing content after 4.6.1 update
---
r773 | alwarren | 2006-10-31 23:02:09 -0700 (Tue, 31 Oct 2006) | 1 line
Fixed FS#126 — Front End Editing Error. Usertype was set to '' on save.
---
r772 | alwarren | 2006-10-31 16:35:51 -0700 (Tue, 31 Oct 2006) | 1 line
Fixed FS#140 - Installer failure on Windows with magic_quotes_gpc off
---
r768 | cauld | 2006-10-29 10:21:36 -0700 (Sun, 29 Oct 2006) | 1 line
! Updating MOStlyCE mambot to v2.0. Brings TinyMCE guts to 2.0.8, fixes
issues with IE7 & FF2.  Integration with TinyMCPUK adds image / file manager
functionality.
---
r767 | cauld | 2006-10-29 10:00:22 -0700 (Sun, 29 Oct 2006) | 1 line
! Updating MOStlyCE Admin component to v2.0. Brings TinyMCE guts to 2.0.8,
fixes issues with IE7 & FF2.  Integration with TinyMCPUK adds image / file
manager functionality.
---
r766 | cauld | 2006-10-28 09:46:22 -0600 (Sat, 28 Oct 2006) | 1 line
! Updating PclZip from 2.1 to 2.5 for Language Manager export functionality
---
r764 | alwarren | 2006-10-20 14:14:59 -0600 (Fri, 20 Oct 2006) | 1 line
Fixed FS#139 - warnings in admin polls popup.
---
r763 | alwarren | 2006-10-19 14:45:31 -0600 (Thu, 19 Oct 2006) | 1 line
Reverted previous fix for FS#134 and moved to function determineOptionAndItemid
---
r762 | alwarren | 2006-10-19 14:09:24 -0600 (Thu, 19 Oct 2006) | 1 line
Added copyright block to core.classes.php
---
r761 | alwarren | 2006-10-19 14:00:14 -0600 (Thu, 19 Oct 2006) | 1 line
Fixed FS#134 — Static as a homepage is not working
---
r760 | alwarren | 2006-10-19 11:19:07 -0600 (Thu, 19 Oct 2006) | 1 line
Fixed FS#138 - Warnings when magic_quotes_gpc is off
---
r759 | alwarren | 2006-10-17 12:21:59 -0600 (Tue, 17 Oct 2006) | 1 line
Better handling of function mosComponentManager::restore_magic_quotes()
---
r758 | alwarren | 2006-10-17 10:57:44 -0600 (Tue, 17 Oct 2006) | 1 line
Fixed FS#136 - Contact Us / Custom Module / Add slashes
---
r757 | alwarren | 2006-10-17 00:26:21 -0600 (Tue, 17 Oct 2006) | 1 line
Fixed FS#132 - problem with emulate register globals off
---
r756 | chanh | 2006-10-15 22:28:51 -0600 (Sun, 15 Oct 2006) | 1 line
Fix bug when upgrade from older version that use "superadministrator" in
users.usertype rather than "Super Administrator"
---
r755 | chanh | 2006-10-15 10:40:38 -0600 (Sun, 15 Oct 2006) | 1 line
It might be confusing for the user so I revert out my change.
---
r754 | chanh | 2006-10-15 10:16:57 -0600 (Sun, 15 Oct 2006) | 1 line
Add edit title_alias on the frontend right below the title
---
r753 | adi | 2006-10-08 20:50:22 -0600 (Sun, 08 Oct 2006) | 1 line
remove xml prolog in template files
---
r752 | cauld | 2006-10-08 10:28:20 -0600 (Sun, 08 Oct 2006) | 1 line
# Removing the old checkbox DROP TABLES option from the install screen.
Should have been removed when the code was rewritten, but forgot to check it in.
-------------------- 4.6.1 Release -------------------------------------
r749 | neilt | 2006-10-04 13:15:07 -0600 (Wed, 04 Oct 2006) | 1 line
# bug fix : incorrectly assigned variable causing php notices - #FS121
---
r745 | cauld | 2006-10-03 15:40:42 -0600 (Tue, 03 Oct 2006) | 1 line
! Removing the old DROP TABLES option during install.  This is not really
an option since you have to drop tables before creating new ones.  Just
altered the way the BACKUP TABLES option works.
---
r744 | neilt | 2006-10-03 06:06:58 -0600 (Tue, 03 Oct 2006) | 1 line
# Bug fix : Pathway clone bug, link becomes a pathway - FS#106
---
r743 | cauld | 2006-10-02 17:26:25 -0600 (Mon, 02 Oct 2006) | 1 line
# Fixing a few installation bugs found during an internal QA audit
---
r741 | cauld | 2006-10-01 20:10:21 -0600 (Sun, 01 Oct 2006) | 1 line
# FS-108 - Fixes 6 array_merge warnings that are present on a fresh install
with no content.
---
r740 | cauld | 2006-10-01 17:07:08 -0600 (Sun, 01 Oct 2006) | 1 line
! Updating the Banner module & component to change the alt tag change from
"Advertisement" to "Banner".  The old text was causing banners to be
automatically blocked by some ad blockers.
---
r739 | cauld | 2006-09-28 15:21:24 -0600 (Thu, 28 Sep 2006) | 1 line
# Fixing <span> issue in function makePathway
---
r738 | neilt | 2006-09-28 11:39:07 -0600 (Thu, 28 Sep 2006) | 1 line
# Fix for some cross scripting bugs. FS#95
---
r737 | adi | 2006-09-27 00:23:45 -0600 (Wed, 27 Sep 2006) | 1 line
include PHP 5 compatibility file
---
r736 | adi | 2006-09-27 00:22:58 -0600 (Wed, 27 Sep 2006) | 1 line
added new file to provide missing functionality for older version of php
---
r734 | cauld | 2006-09-26 20:46:25 -0600 (Tue, 26 Sep 2006) | 2 lines
# Adding a getEscaped call for $passwd in the LoginUser function
# Updating mosGetParam to utilize addslashes for added security
---
r733 | neilt | 2006-09-25 13:33:41 -0600 (Mon, 25 Sep 2006) | 1 line
+ initial workaround for FS#89 — Can not add menu when Mysql strict is on
---
r732 | chanh | 2006-09-23 17:42:50 -0600 (Sat, 23 Sep 2006) | 1 line
Fix bug cause site go offline when MetaKeys reach beyond 256 chars.
---
r731 | neilt | 2006-09-23 07:21:20 -0600 (Sat, 23 Sep 2006) | 1 line
# bug fix - Email cloak incorrectly parses emails URL that include ?
---
r729 | neilt | 2006-09-23 04:46:37 -0600 (Sat, 23 Sep 2006) | 1 line
# bug fix - numerous gets missing for backward compatibility - FS#85
---
r728 | ninekrit | 2006-09-22 02:34:07 -0600 (Fri, 22 Sep 2006) | 1 line
#removed fixed encoding line 354 for support all encoding
---
r727 | neilt | 2006-09-21 12:42:10 -0600 (Thu, 21 Sep 2006) | 1 line
# Bug fix — Declaration dropped bug, removed unused css - FS#58
---
r726 | neilt | 2006-09-21 12:17:17 -0600 (Thu, 21 Sep 2006) | 1 line
# Bug Fix - missing / in check() function FS#90
---
r725 | neilt | 2006-09-21 07:33:49 -0600 (Thu, 21 Sep 2006) | 1 line
# bug fix - missing declaration
---
r724 | neilt | 2006-09-20 07:57:33 -0600 (Wed, 20 Sep 2006) | 1 line
# WebLinks email message not sent to admin bug fixed FS#76
---
r723 | neilt | 2006-09-20 04:08:13 -0600 (Wed, 20 Sep 2006) | 1 line
# Bug fix for error message when there are no weblinks FS#88
---
r722 | neilt | 2006-09-20 03:54:16 -0600 (Wed, 20 Sep 2006) | 1 line
# bug fix for login/out messages FS#82
---
r721 | adi | 2006-09-18 20:09:06 -0600 (Mon, 18 Sep 2006) | 1 line
fix incorrect br tag. FS#87
---
r719 | neilt | 2006-09-18 09:08:45 -0600 (Mon, 18 Sep 2006) | 1 line
file missed on earlier upload
---
r718 | neilt | 2006-09-18 06:48:29 -0600 (Mon, 18 Sep 2006) | 1 line
+ Enhancements to comments component to fix bugs #FS72, #FS75
---
r717 | cauld | 2006-09-17 12:27:50 -0600 (Sun, 17 Sep 2006) | 1 line
! Updating version.php & changelog.php
-------------------- 4.6 Release ---------------------------------------
r716 | cauld | 2006-09-17 12:19:53 -0600 (Sun, 17 Sep 2006) | 1 line
! Removing Mambo Raw SQL installation files and replacing with Mambo Lite SQL files.
---
r715 | alwarren | 2006-09-14 23:03:21 -0600 (Thu, 14 Sep 2006) | 1 line
# FS#80 Fixed display of number of items checked in
---
r714 | adi | 2006-09-14 19:05:36 -0600 (Thu, 14 Sep 2006) | 1 line
remove non utf-8 in locales.xml
---
r711 | alwarren | 2006-09-14 11:18:07 -0600 (Thu, 14 Sep 2006) | 1 line
! FS#12 - Change menu manager radio buttons to checkboxes to allow multiple item selection
---
r710 | alwarren | 2006-09-14 10:22:48 -0600 (Thu, 14 Sep 2006) | 2 lines
# FS#74 - Fixed problem with Itemid.
section/category links in content item were causing bad links
---
r708 | gin | 2006-09-14 20:29:00
! some minor esthetical change in the form of the advanced search
(components/com_search/search.html.php)
---
r707 | gin | 2006-09-14 16:36:00
# fixed the bug #77 noted by Mac in 2nd form's compilation during the new language creation
modfied the file includes\phpgettext\phpgettext.catalog.php
---
r706 | gin | 2006-09-14 14:10:00
! Modified the component registration in a 2 step process with double e-mail
verification and some additional test
---
r705 | alwarren | 2006-09-13 02:58:12 -0600 (Wed, 13 Sep 2006) | 1 line
FS#73 Change wording in comments mambot
---
r704 | alwarren | 2006-09-13 02:41:03 -0600 (Wed, 13 Sep 2006) | 1 line
FS#54 Added spacers to toolbar in banner manager
---
r703 | adi | 2006-09-12 23:34:45 -0600 (Tue, 12 Sep 2006) | 1 line
fix bug #63
---
r702 | adi | 2006-09-12 23:15:12 -0600 (Tue, 12 Sep 2006) | 1 line
fix bug #57 & #64
---
r701 | cauld | 2006-09-12 22:28:50 -0600 (Tue, 12 Sep 2006) | 1 line
! Updating MOS Rating to be a fully installable package for Mambo Raw
---
r700 | cauld | 2006-09-12 17:01:15 -0600 (Tue, 12 Sep 2006) | 1 line
# Forgot to add the MOStlyDBAdmin classes folder on an earlier commit
---
r699 | cauld | 2006-09-12 16:36:47 -0600 (Tue, 12 Sep 2006) | 1 line
! FS#60 — Modify News Feeds Items
---
r698 | cauld | 2006-09-12 16:30:12 -0600 (Tue, 12 Sep 2006) | 1 line
# FS#55 — Update WebLinks Items
---
r697 | cauld | 2006-09-12 16:17:48 -0600 (Tue, 12 Sep 2006) | 1 line
#  FS#53 — Change Banner Link
---
r696 | cauld | 2006-09-12 16:11:52 -0600 (Tue, 12 Sep 2006) | 1 line
#  FS#68 — Help Screens Missing
---
r695 | cauld | 2006-09-12 05:17:46 -0600 (Tue, 12 Sep 2006) | 1 line
! Pulling mosvote / MOS Rating from Mambo Raw edition
---
r694 | cauld | 2006-09-11 23:38:01 -0600 (Mon, 11 Sep 2006) | 1 line
#  Fixing FS#69 — Absolute Path to MOStlyCE Template Directory
---
r693 | cauld | 2006-09-11 23:30:28 -0600 (Mon, 11 Sep 2006) | 1 line
! Updating mostlyce admin xml file
---
r692 | cauld | 2006-09-11 23:28:16 -0600 (Mon, 11 Sep 2006) | 5 lines
# Fixed MOStlyDBAdmin zip archive issue.  Was missing the zip.lib/php class.
! Updating directory listing on the universal installer screen
# Updating MOStlyCE Spellchecker plugin warning to notify user that CURL is now a requirement.
# Update VCard to prevent vcard link from displaying when contact has not been edited or where the contact params do not exist. (suggested by alwarren)
# Fixing some missing single quotes in some of the menu manager <script>alert code. This was causing a blank page when certain error conditions exist.  (suggested by alwarren)
---
r691 | cauld | 2006-09-11 17:56:22 -0600 (Mon, 11 Sep 2006) | 1 line
# Changing install.php to us $mosConfig_lang = 'en' for configuration.php rather than 'english'.  Was causing issues with archive and create / modify dates.
---
r690 | cauld | 2006-09-10 19:35:02 -0600 (Sun, 10 Sep 2006) | 2 lines
! Updating changelog.php
! Updating version.php
---
r689 | cauld | 2006-09-10 19:08:23 -0600 (Sun, 10 Sep 2006) | 1 line
! Adding function mosLoadComponent back in to 4.6 based on 4.5.4 code.
Looks like the function was dropped by mistake in the 4.6 core rewrite.
---
r688 | neilt | 2006-09-08 12:21:44 -0600 (Fri, 08 Sep 2006) | 1 line
workarounds for $config_live_site problems on some linux hosts
---
r687 | neilt | 2006-09-08 07:17:54 -0600 (Fri, 08 Sep 2006) | 1 line
# bugfix - hits not set to show as default in sql install
---
r686 | neilt | 2006-09-08 04:36:52 -0600 (Fri, 08 Sep 2006) | 1 line
workaround to safemode gettext LANG/LC errors
---
r685 | neilt | 2006-09-07 16:55:51 -0600 (Thu, 07 Sep 2006) | 1 line
makePathway span bug fixes - FS#51
---
r684 | neilt | 2006-09-07 16:41:04 -0600 (Thu, 07 Sep 2006) | 1 line
minor code enhancements
---
r683 | cauld | 2006-09-06 08:56:20 -0600 (Wed, 06 Sep 2006) | 1 line
! menubar.html.php html cleanup
---
r682 | cauld | 2006-09-06 08:49:21 -0600 (Wed, 06 Sep 2006) | 1 line
# com_admin html cleanup on "check for updates"
---
r681 | cauld | 2006-09-05 17:30:42 -0600 (Tue, 05 Sep 2006) | 2 lines
# Cleaning up the HTML in the Add-On installer to help it validate.
! Updating handleGlobals to protect against zend_hash_del_key_or_index hole
---
r680 | cauld | 2006-09-05 16:22:41 -0600 (Tue, 05 Sep 2006) | 1 line
Applied all 4.5.4 SP2 fixes to the 4.6 branch were applicable.
---
r679 | ninekrit | 2006-09-05 08:06:39 -0600 (Tue, 05 Sep 2006) | 1 line
update language variable for translation
administrator/includes/pageNavigation.php:line 154,170
---
r667 | neilt | 2006-08-30 11:41:39 -0600 (Wed, 30 Aug 2006) | 1 line
modifications to private messaging
---
r666 | ninekrit | 2006-08-30 10:45:40 -0600 (Wed, 30 Aug 2006) | 1 line
+Update untranslated word
---
r665 | neilt | 2006-08-30 10:12:05 -0600 (Wed, 30 Aug 2006) | 1 line
bug fix : emails for private messages not sent to users when 'receive mail' ticked
---
r664 | ninekrit | 2006-08-30 08:48:20 -0600 (Wed, 30 Aug 2006) | 1 line
Add <br />  line 40
---
r663 | ninekrit | 2006-08-30 08:14:09 -0600 (Wed, 30 Aug 2006) | 1 line
FS#44 Menubar not traslations
---
r662 | ninekrit | 2006-08-30 08:12:35 -0600 (Wed, 30 Aug 2006) | 1 line
FS#20,FS#44 edit language problem
---
r660 | neilt | 2006-08-30 06:38:17 -0600 (Wed, 30 Aug 2006) | 1 line
adding CAPTCHA authentication to whats new
---
r659 | cauld | 2006-08-29 23:12:38 -0600 (Tue, 29 Aug 2006) | 1 line
Another minor update to "What's New".
---
r658 | cauld | 2006-08-29 22:56:43 -0600 (Tue, 29 Aug 2006) | 1 line
Updating the credits file to add Chanh as PM.  Also updating the
"what's new" file for the 4.6 release.
---
r657 | neilt | 2006-08-29 13:50:45 -0600 (Tue, 29 Aug 2006) | 1 line
help link and admin help modifications
---
r656 | neilt | 2006-08-29 02:39:33 -0600 (Tue, 29 Aug 2006) | 1 line
minor sef bug fixes and code cleaning
---
r655 | neilt | 2006-08-27 05:12:46 -0600 (Sun, 27 Aug 2006) | 1 line
fixed bug where archive form did not supply module id
---
r654 | cauld | 2006-08-26 10:47:12 -0600 (Sat, 26 Aug 2006) | 1 line
Turning off comments in the sample Newsflash section by default.  Suggestion
from Ricoflan.
---
r653 | cauld | 2006-08-26 10:21:52 -0600 (Sat, 26 Aug 2006) | 1 line
Removing MamboLove entries from the sample data.
---
r652 | cauld | 2006-08-26 10:11:46 -0600 (Sat, 26 Aug 2006) | 1 line
Fixing com_admin bug that was preventing help and preview template functions
from working.  Left over from the new "Check from Updates" feature.
---
r651 | cauld | 2006-08-26 09:34:47 -0600 (Sat, 26 Aug 2006) | 1 line
Fixed registration bug.  Warning messages were not displaying the correct
string, but rather the defined constant name.
---
r650 | chanh | 2006-08-24 16:54:33 -0600 (Thu, 24 Aug 2006) | 1 line
Fix for mysql 5 and remove duplicated code
---
r649 | chanh | 2006-08-24 16:54:12 -0600 (Thu, 24 Aug 2006) | 1 line
Fix for mysql 5 and remove duplicated code
---
r648 | neilt | 2006-08-24 11:06:01 -0600 (Thu, 24 Aug 2006) | 1 line
removed image upload on edit in banners as not adding to select list
---
r647 | neilt | 2006-08-24 06:36:04 -0600 (Thu, 24 Aug 2006) | 1 line
captcha authentication added to contact component
---
r646 | neilt | 2006-08-24 03:30:18 -0600 (Thu, 24 Aug 2006) | 1 line
Minor change to most read module to show hits
---
r645 | cauld | 2006-08-23 22:29:08 -0600 (Wed, 23 Aug 2006) | 1 line
Updating the XML files for the components removed with Raw.  They were
missing the CREATE TABLE statements and thus failing after install.
---
r644 | cauld | 2006-08-23 19:23:54 -0600 (Wed, 23 Aug 2006) | 1 line
Pulling MOStlyDBAdmin out of Mambo Raw now that there is an installable
package.
---
r643 | neilt | 2006-08-23 01:43:51 -0600 (Wed, 23 Aug 2006) | 1 line
Bug fixes : Fixed double install message on module upload (FS#38). Fixed
Comments component content item dropdown list disappearing behind other
page content (FS#37).
---
r642 | chanh | 2006-08-21 16:00:27 -0600 (Mon, 21 Aug 2006) | 1 line
Upgrade JSCookmenu to 2.0.1
---
r641 | cauld | 2006-08-20 23:59:58 -0600 (Sun, 20 Aug 2006) | 1 line
Updating comments bot and component with new xml files based on those
given to me by Neil.
---
r640 | cauld | 2006-08-20 23:50:29 -0600 (Sun, 20 Aug 2006) | 1 line
Updating the MOStlyCE spellchecker plugin to the latest version, 1.0.2.
---
r639 | cauld | 2006-08-20 23:46:53 -0600 (Sun, 20 Aug 2006) | 1 line
Checking in updated module xml files to allow for those removed in Raw to
become fully installable / uninstallable modules.
---
r638 | cauld | 2006-08-20 23:44:30 -0600 (Sun, 20 Aug 2006) | 1 line
Checking in updated mambot xml files to allow those removed with Raw to
become fully installable / uninstallable mambots.
---
r637 | cauld | 2006-08-20 23:41:33 -0600 (Sun, 20 Aug 2006) | 1 line
Adding com_weblink_items and weblink_items.xml.  This xml file was seperated
from com_weblinks to allow weblinks to become a fully installable / uninstallable
component.
---
r636 | cauld | 2006-08-20 23:39:59 -0600 (Sun, 20 Aug 2006) | 1 line
Removing weblink_items.xml.  Will add back in under its own directory to
allow weblinks to become a fully installable / uninstallable component.
---
r635 | cauld | 2006-08-20 23:37:55 -0600 (Sun, 20 Aug 2006) | 1 line
Checking in updated admin component xml files to allow for fully installable
components.  Focusing on those removed with Raw.
---
r634 | cauld | 2006-08-20 10:40:45 -0600 (Sun, 20 Aug 2006) | 1 line
Clearing a few notices for the addon install
---
r633 | cauld | 2006-08-20 10:32:00 -0600 (Sun, 20 Aug 2006) | 1 line
Adding the new one click Add-On Installer
---
r632 | cauld | 2006-08-20 00:08:50 -0600 (Sun, 20 Aug 2006) | 1 line
Updating admin menus.  The new "addon installer" and "check for updates"
features require php5+.  The options are now hidden in the menu when running
php < 5.
---
r631 | cauld | 2006-08-15 21:26:19 -0600 (Tue, 15 Aug 2006) | 1 line
Adding system menu entry for the "Check For Updates" feature
---
r630 | cauld | 2006-08-15 21:24:45 -0600 (Tue, 15 Aug 2006) | 1 line
Checking in the new Mambo "Check For Updates" feature
---
r629 | neilt | 2006-08-15 12:39:14 -0600 (Tue, 15 Aug 2006) | 1 line
bug fix for pollwindow.php error. (Flyspray #31)
---
r628 | chanh | 2006-08-15 10:19:08 -0600 (Tue, 15 Aug 2006) | 1 line
add mosCreateMail for backward compatible per user feedback.
---
r627 | cauld | 2006-08-07 22:54:18 -0600 (Mon, 07 Aug 2006) | 1 line
Mambo Raw changes
---
r626 | cauld | 2006-08-07 22:39:43 -0600 (Mon, 07 Aug 2006) | 1 line
More changes for Mambo Raw
---
r625 | cauld | 2006-08-07 17:26:47 -0600 (Mon, 07 Aug 2006) | 1 line
Adding Raw files to be used in building a raw core version of Mambo.
---
r619 | neilt | 2006-08-02 09:02:53 -0600 (Wed, 02 Aug 2006) | 1 line
modification to retain comment text if captcha image misread
---
r618 | neilt | 2006-07-31 05:43:43 -0600 (Mon, 31 Jul 2006) | 1 line
bug fix - Move Category Reports Errors (when category is empty) FS#22
---
r617 | neilt | 2006-07-31 04:53:05 -0600 (Mon, 31 Jul 2006) | 1 line
bug fix - Load Module Positions Mambot Parameters Failure FS#7
---
r616 | neilt | 2006-07-29 13:20:21 -0600 (Sat, 29 Jul 2006) | 1 line
general *.html.php code tidy up
---
r615 | neilt | 2006-07-29 11:00:02 -0600 (Sat, 29 Jul 2006) | 1 line
update of jscookmenu details
---
r614 | neilt | 2006-07-29 06:33:32 -0600 (Sat, 29 Jul 2006) | 1 line
bug fix to userstate session to retain search, section, category and author
values
---
r613 | neilt | 2006-07-26 08:49:44 -0600 (Wed, 26 Jul 2006) | 1 line
Modifications to allow the display of static content in the frontpage component,
adjustment of default admin list limit to 50
---
r612 | neilt | 2006-07-25 06:34:33 -0600 (Tue, 25 Jul 2006) | 1 line
bug fixes to allow correct email submissions for weblinks and registration
---
r611 | neilt | 2006-07-23 11:59:26 -0600 (Sun, 23 Jul 2006) | 1 line
small bug fixes
---
r610 | neilt | 2006-07-18 06:39:51 -0600 (Tue, 18 Jul 2006) | 1 line
modification to remove redundant help icon (Flyspray FS#14)
---
r609 | neilt | 2006-07-18 06:18:02 -0600 (Tue, 18 Jul 2006) | 1 line
bug fix to allow show/hide of section/category description and description
image
---
r608 | cauld | 2006-07-16 18:57:52 -0600 (Sun, 16 Jul 2006) | 1 line
Updating some sample content and adding a few new "other menu" items.
---
r607 | cauld | 2006-07-16 18:56:47 -0600 (Sun, 16 Jul 2006) | 1 line
Updating admin help link.
---
r606 | cauld | 2006-07-16 18:55:19 -0600 (Sun, 16 Jul 2006) | 1 line
Updating some embedded help items.
---
r605 | cauld | 2006-07-16 13:14:36 -0600 (Sun, 16 Jul 2006) | 1 line
Updating mod_quickicon based on a suggested fix from Apree.  Changing from
fixed sizing to percentages to help with the proper display of these icons.
---
r604 | cauld | 2006-07-16 13:02:53 -0600 (Sun, 16 Jul 2006) | 1 line
Updating admin com_modules to clear warnings.
---
r603 | cauld | 2006-07-16 13:00:44 -0600 (Sun, 16 Jul 2006) | 1 line
Updating the post installation survey so that it hyperlinks to the security
signup form rather than emails a specific email address.
---
r602 | cauld | 2006-07-16 12:58:50 -0600 (Sun, 16 Jul 2006) | 1 line
Removing basic TinyMCE editor in favor of the new default MOStlyCE editor
based on TinyMCE.
---
r601 | neilt | 2006-07-16 02:52:05 -0600 (Sun, 16 Jul 2006) | 1 line
removal of reserved words from comments component
---
r600 | cauld | 2006-07-12 23:10:15 -0600 (Wed, 12 Jul 2006) | 1 line
updating changelog.php
---
r599 | chanh | 2006-07-12 15:17:48 -0600 (Wed, 12 Jul 2006) | 1 line
Upgrade to JSCookMenu v1.4.4. to fix menu show to the extreme left in IE7.
---
r598 | neilt | 2006-07-12 06:47:20 -0600 (Wed, 12 Jul 2006) | 1 line
configuration changes for captcha security
---
r597 | neilt | 2006-07-12 03:59:15 -0600 (Wed, 12 Jul 2006) | 1 line
fixed contact form send and vcard download issues
---
r596 | neilt | 2006-07-11 06:14:10 -0600 (Tue, 11 Jul 2006) | 1 line
captcha code moved into the core to allow for more global inclusion.
Administration configuration amended to enable or disable
---
r595 | ninekrit | 2006-07-09 20:52:07 -0600 (Sun, 09 Jul 2006) | 1 line
Update Thai Language
---
r593 | neilt | 2006-07-07 12:06:17 -0600 (Fri, 07 Jul 2006) | 1 line
Updated ttf fonts to GPL
---
r592 | neilt | 2006-07-07 11:48:38 -0600 (Fri, 07 Jul 2006) | 1 line
spam fix font files added
---
r591 | neilt | 2006-07-07 11:47:27 -0600 (Fri, 07 Jul 2006) | 1 line
Comments component spam protection and fonts updated with new GPL version
3.4 and small session enhancements
---
r590 | neilt | 2006-07-07 01:59:57 -0600 (Fri, 07 Jul 2006) | 1 line
New comments component and mambot uploaded along with language files, help
files and modifications to the SQL install
---
r589 | chanh | 2006-07-06 12:20:44 -0600 (Thu, 06 Jul 2006) | 1 line
The textarea box is too small when not using WYSIWYG editor so make it a
little bigger for ease of editing.
---
r588 | cauld | 2006-07-04 15:41:33 -0600 (Tue, 04 Jul 2006) | 1 line
Fixing menu manager message displayed when multiple menu items were removed.
Was not displaying the total, but rather each item.
---
r587 | cauld | 2006-07-04 15:25:31 -0600 (Tue, 04 Jul 2006) | 1 line
Fixing broken com_trash multi-item selection for delete
---
r586 | cauld | 2006-07-03 16:43:23 -0600 (Mon, 03 Jul 2006) | 1 line
Fixing a roothpath issue that was causing IIS to fail.
-------------------- 4.6 (RC2) Release ---------------------------------
r584 | cauld | 2006-07-02 14:02:19 -0600 (Sun, 02 Jul 2006) | 1 line
Fixing MySQL 5 params NOT NULL issue.  Allowing default to be NULL for
components, modules, and mambots.  A broader fix will happen with v5.
---
r583 | cauld | 2006-07-02 11:05:31 -0600 (Sun, 02 Jul 2006) | 1 line
Eliminating the use of realpath() to determine dynamic URLs.  This was
creating issues with some shared host plans.
---
r582 | cauld | 2006-06-22 22:17:06 -0600 (Thu, 22 Jun 2006) | 1 line
MOStlyCE config change to fix Javascript errors.  Now not loading several
external plugins (ex) htmltemplate, mambo, etc.  These were not written for
TinyMCE 2.x and therefore do not work.  Will have to revisit at a later time.
---
r581 | cauld | 2006-06-22 21:32:32 -0600 (Thu, 22 Jun 2006) | 1 line
MOStlyCE Admin change to remove onclick config section.
---
r580 | cauld | 2006-06-20 11:43:43 -0600 (Tue, 20 Jun 2006) | 1 line
turning off MOStlyCE compression by default until the compressor works in IE.
---
r579 | csouza | 2006-06-20 05:54:04 -0600 (Tue, 20 Jun 2006) | 1 line
removing charset conversion button from language form
---
---
r577 | cauld | 2006-06-20 00:05:22 -0600 (Tue, 20 Jun 2006) | 1 line
adding some missing / unversioned MOStlyCE files
---
r576 | cauld | 2006-06-20 00:01:27 -0600 (Tue, 20 Jun 2006) | 1 line
tweaking MOStlyCE based on the 4.5.4 MOStlyCE feedback in the forums
---
r575 | cauld | 2006-06-19 23:17:39 -0600 (Mon, 19 Jun 2006) | 1 line
committing weblinks SQL injection fix
---
r572 | cauld | 2006-06-18 11:53:59 -0600 (Sun, 18 Jun 2006) | 1 line
! changing mambo.sql to fix utf8 / mysql 5 issue with #__core_acl_aro.
---
r571 | cauld | 2006-06-18 11:22:02 -0600 (Sun, 18 Jun 2006) | 1 line
! Turning off MOStlyCE compression by default since it doesn't work in IE.
Adding warning to tip so users see why it is off.  OK and recommended for use with FF.
---
r570 | neilt | 2006-06-18 04:04:34 -0600 (Sun, 18 Jun 2006) | 1 line
commit test
---
r569 | chanh | 2006-06-17 22:47:09 -0600 (Sat, 17 Jun 2006) | 1 line
Make a comment commit on SG SVN to make sure the commit work
---
r568 | cauld | 2006-06-17 19:13:30 -0600 (Sat, 17 Jun 2006) | 1 line
!test commit against Mambo's new SVN repository
---
r567 | chanh | 2006-06-16 13:03:24 -0600 (Fri, 16 Jun 2006) | 1 line
Fix bug with STRICT_TRANS_TABLES cause installer to fail in mysql 5 when
install module and mambot
---
r566 | csouza | 2006-06-14 06:50:53 -0600 (Wed, 14 Jun 2006) | 1 line
updated brazilian portuguese glossary
---
r565 | cauld | 2006-06-13 21:58:48 -0600 (Tue, 13 Jun 2006) | 1 line
!Changing format of CHANGELOG to use the SVN log.  Easier to maintain.
---
r564 | cauld | 2006-06-13 21:17:10 -0600 (Tue, 13 Jun 2006) | 2 lines
!updating mostlyce.php and mostlyce.xml based on recent test for 454.
Fixing paste plugin issue.
---
r563 | csouza | 2006-06-11 10:05:40 -0600 (Sun, 11 Jun 2006) | 1 line
bug fix in language charset conversion
---
r562 | csouza | 2006-06-10 11:37:53 -0600 (Sat, 10 Jun 2006) | 1 line
updated .pot templates
---
r560 | csouza | 2006-06-07 02:35:31 -0600 (Wed, 07 Jun 2006) | 1 line
language - updated .pot templates
---
r557 | csouza | 2006-06-07 02:21:25 -0600 (Wed, 07 Jun 2006) | 1 line
including italian, thai and brazilian portuguese language files for the rc. These should be installable.
---
r556 | csouza | 2006-06-07 02:15:51 -0600 (Wed, 07 Jun 2006) | 1 line
internationalization modifications
---
r555 | cauld | 2006-05-31 21:26:19 -0600 (Wed, 31 May 2006) | 4 lines
! removing all mentions of mamboforge and replacing with mamboxchange or something else like The Source.
! updating the support page to update links, add better wording, etc.
! updating version.php for RC2
---
r554 | csouza | 2006-05-31 10:52:24 -0600 (Wed, 31 May 2006) | 1 line
minor tweaks to HTML_admin_misc::help() in admin.admin.html.php
---
r553 | csouza | 2006-05-31 10:49:28 -0600 (Wed, 31 May 2006) | 1 line
replaced help files with xhtml compliant ones with title tag needed for help index
---
r552 | csouza | 2006-05-30 17:38:18 -0600 (Tue, 30 May 2006) | 1 line
removed obsolete help screens
---
r551 | csouza | 2006-05-30 17:35:27 -0600 (Tue, 30 May 2006) | 1 line
final installment of new and renamed help screens and component toolbars changed to reflect new naming.
---
r550 | csouza | 2006-05-29 11:21:00 -0600 (Mon, 29 May 2006) | 1 line
first installment of new and renamed help screens and component toolbars changed to reflect new naming
---
r549 | cauld | 2006-05-26 21:42:35 -0600 (Fri, 26 May 2006) | 2 lines
! Updated "What's new" & "Credits"
---
r548 | cauld | 2006-05-26 20:05:58 -0600 (Fri, 26 May 2006) | 2 lines
+ Adding new site templates (Donated by Water&Stone, coded by Nalisa)
---
r533 | csouza | 2006-05-22 08:32:01 -0600 (Mon, 22 May 2006) | 1 line
fixed some bugs caused by index.php cleanup
---
r532 | csouza | 2006-05-21 03:41:05 -0600 (Sun, 21 May 2006) | 1 line
moved commented 'ErrorHandler' class in index.php to includes/core.classes.php
---
r531 | cauld | 2006-05-20 18:54:45 -0600 (Sat, 20 May 2006) | 1 line
cauld:  (trivial) correcting a few spelling errors
---
r530 | csouza | 2006-05-20 17:17:51 -0600 (Sat, 20 May 2006) | 1 line
corrected mambocore->rootpath in includes/core.classes.php and added phpdoc templates to undocumented classes
---
r529 | csouza | 2006-05-20 15:01:36 -0600 (Sat, 20 May 2006) | 1 line
moved classes in index.php to includes/core.classes.php
---
r528 | cauld | 2006-05-20 14:57:10 -0600 (Sat, 20 May 2006) | 1 line
cauld:  # Fixing a Offline bug which caused an error rather than the offline message when MySQL was down
---
r524 | neilt | 2006-05-19 06:18:36 -0600 (Fri, 19 May 2006) | 1 line
general javascript and formatting bug fixes
---
r523 | neilt | 2006-05-19 00:20:15 -0600 (Fri, 19 May 2006) | 1 line
Fixed Trac Ticket #86
---
r521 | cauld | 2006-05-16 22:54:20 -0600 (Tue, 16 May 2006) | 1 line
cauld - # Rewrote uninstall_template() to fix bug where the wrong template dir was removed
---
r520 | neilt | 2006-05-16 07:13:53 -0600 (Tue, 16 May 2006) | 1 line
Modification to center menubar labels
---
r517 | neilt | 2006-05-16 06:05:37 -0600 (Tue, 16 May 2006) | 1 line
bug fix
---
r516 | neilt | 2006-05-16 03:44:41 -0600 (Tue, 16 May 2006) | 1 line
minor bug fixes
---
r515 | cauld | 2006-05-15 22:20:58 -0600 (Mon, 15 May 2006) | 1 line
cauld - fixing minor mis-spelling
---
r514 | neilt | 2006-05-15 06:52:09 -0600 (Mon, 15 May 2006) | 1 line
inclusion of .message class in front end css
---
r512 | cauld | 2006-05-14 17:16:29 -0600 (Sun, 14 May 2006) | 2 lines
cauld - Added the uploadfiles dir to the installation write permission check
---
r511 | cauld | 2006-05-14 16:22:54 -0600 (Sun, 14 May 2006) | 1 line
cauld - missed a few weblink name changes.
---
r510 | cauld | 2006-05-14 15:46:22 -0600 (Sun, 14 May 2006) | 1 line
cauld:  Standardized / Updated the label for New Feeds for the whole app (aka: Newsfeeds)
---
r509 | cauld | 2006-05-14 15:14:22 -0600 (Sun, 14 May 2006) | 1 line
cauld:  Updated MOStlyCE spellchecker plugin tip
---
r508 | cauld | 2006-05-14 14:48:05 -0600 (Sun, 14 May 2006) | 3 lines
! Updated the "What's New" help doc
! Standardized / Updated the label for Web Links for the whole app (aka: Weblinks)
---
r507 | cauld | 2006-05-13 19:23:34 -0600 (Sat, 13 May 2006) | 7 lines
! Upgraded MOStlyCE's guts to TinyMCE 2.0.6.1 (bugs fixes, enhancements, etc)
+ Added a layers plugin to MOStlyCE and an experimental spellchecker plugin (works well in IE)
+ Added options in MOStlyCE Admin to control layer & spellchecker plugins
! Various other minor MOStlyCE tweaks and enhancements
---
r506 | adi | 2006-05-13 00:03:27 -0600 (Sat, 13 May 2006) | 1 line
fix trac #85
---
r505 | cauld | 2006-05-11 06:54:09 -0600 (Thu, 11 May 2006) | 1 line
cauld - fixing mambo.sql table prefix bug
---
r504 | neilt | 2006-05-01 11:55:35 -0600 (Mon, 01 May 2006) | 1 line
Fixed errors occuring whilst copying empty sections - Trac #83
---
r502 | cauld | 2006-04-30 14:57:33 -0600 (Sun, 30 Apr 2006) | 1 line
cauld - Updating MOStlyCE Admin to handle the language file structure change.
---
r500 | cauld | 2006-04-30 14:27:06 -0600 (Sun, 30 Apr 2006) | 1 line
cauld - checking in tinymce 2.0.5.1
---
r499 | cauld | 2006-04-30 13:02:06 -0600 (Sun, 30 Apr 2006) | 1 line
cauld - For whatever reason the last time I tried to commit the new version of
the MOStlyCE mambot it disappeared.  Trying again.
---
r497 | cauld | 2006-04-30 12:23:50 -0600 (Sun, 30 Apr 2006) | 1 line
cauld - Removing old basic TinyMCE 2.0.1, will replace with TinyMCE 2.0.5.1.
Tons of enhancements and bug fixes.
---
r496 | cauld | 2006-04-30 12:18:38 -0600 (Sun, 30 Apr 2006) | 1 line
cauld - Rolling MOStlyCE Admin back to v1.5 since the Img & File mgr plugins have been pulled.
---
r495 | cauld | 2006-04-30 12:10:47 -0600 (Sun, 30 Apr 2006) | 1 line
cauld - Checking in MOStlyCE 1.7 mambot.  Now using TinyMCE 2.0.5.1.
The image mgr and file mgr plugins have been pulled for compatibility reasons.
---
r494 | cauld | 2006-04-30 12:05:15 -0600 (Sun, 30 Apr 2006) | 1 line
cauld - Removing the old mostlyce install.  I will be bringing in a fresh
version with the most recent TinyMCE 2.0.5.1 guts.  I didn't want to deal with
new files vs deleted files, etc.  Just easier to replace the whole darn thing :)
---
r493 | neilt | 2006-04-27 11:43:45 -0600 (Thu, 27 Apr 2006) | 1 line
Missing argument 6 for moscomponentusermanager() - Trac #81
---
r492 | neilt | 2006-04-26 15:31:58 -0600 (Wed, 26 Apr 2006) | 2 lines
Fixed empty admin module bugs
Fixed bugs adding new content with no section / categories
-------------------- 4.6 (RC1) Release ---------------------------------
---
r491 | csouza | 2006-04-23 16:43:10 -0600 (Sun, 23 Apr 2006) | 1 line
fix javascript language bugs and mosmenubar::help()
---
r490 | cauld | 2006-04-23 10:39:53 -0600 (Sun, 23 Apr 2006) | 1 line
cauld - fixing Image Manager plugin image list bug.
---
r489 | cauld | 2006-04-23 01:35:31 -0600 (Sun, 23 Apr 2006) | 1 line
cauld - Big commit here for the newly configured MOStlyCE Image & File Manager plugins.  Also contains the new MOStlyCE Admin tabs that go along with these.
---
r488 | neilt | 2006-04-22 14:46:52 -0600 (Sat, 22 Apr 2006) | 1 line
non purged multiple session problems fixed. Trac #79
---
r487 | counterpoint | 2006-04-22 02:49:01 -0600 (Sat, 22 Apr 2006) | 1 line
Further relaxation of Itemid check to avoid "not authorized" errors
---
r486 | counterpoint | 2006-04-21 11:08:02 -0600 (Fri, 21 Apr 2006) | 1 line
Fix bug in showing "shortcut" icon
---
r485 | counterpoint | 2006-04-21 07:10:48 -0600 (Fri, 21 Apr 2006) | 1 line
Modified search mambots to include correct Itemid and prevent search results from being blocked by "unauthorized access"
---
r484 | cauld | 2006-04-20 12:37:58 -0600 (Thu, 20 Apr 2006) | 1 line
cauld - updating version.php for Monday's RC1 release.
---
r483 | csouza | 2006-04-19 07:17:58 -0600 (Wed, 19 Apr 2006) | 1 line
Language Manager Javascript and bug fixes
---
r482 | neilt | 2006-04-19 02:17:21 -0600 (Wed, 19 Apr 2006) | 1 line
addition of missing index.html files to numerous folders
---
r481 | counterpoint | 2006-04-18 10:54:46 -0600 (Tue, 18 Apr 2006) | 1 line
Small bug fixes to text items
---
r479 | counterpoint | 2006-04-16 09:27:34 -0600 (Sun, 16 Apr 2006) | 1 line
Patch installer and file permission bug fixes note.
---
r478 | counterpoint | 2006-04-16 09:25:59 -0600 (Sun, 16 Apr 2006) | 2 lines
Patch installer and file handling bug fixes
---
r477 | counterpoint | 2006-04-16 09:25:27 -0600 (Sun, 16 Apr 2006) | 1 line
Patch installer
---
r475 | counterpoint | 2006-04-15 02:28:04 -0600 (Sat, 15 Apr 2006) | 1 line
Added code to put admin side custom modules into effect, not including RSS feeds.
---
r474 | neilt | 2006-04-13 10:21:17 -0600 (Thu, 13 Apr 2006) | 1 line
Atom 1.0 changes
---
r473 | counterpoint | 2006-04-13 10:18:01 -0600 (Thu, 13 Apr 2006) | 1 line
Modified to process the XML for language package installation.
---
r472 | counterpoint | 2006-04-13 09:38:43 -0600 (Thu, 13 Apr 2006) | 1 line
Extended XML tags for installing language packages.
---
r471 | neilt | 2006-04-13 07:29:26 -0600 (Thu, 13 Apr 2006) | 1 line
minor changes to dates and path
---
r470 | neilt | 2006-04-13 04:02:43 -0600 (Thu, 13 Apr 2006) | 2 lines
Corrected declaration and assigning of 3 new field values
$item_created, $item_modified and $item_author
---
r469 | neilt | 2006-04-13 03:52:39 -0600 (Thu, 13 Apr 2006) | 4 lines
Removal of depricated Atom0.3 syndication
Addition of Atom1.0 standards and RSS feed image
Modifications to language files
Trac #72
---
r468 | cauld | 2006-04-12 23:11:44 -0600 (Wed, 12 Apr 2006) | 1 line
cauld - fixing mospagebreak to work with php 4 & 5
---
r467 | csouza | 2006-04-12 21:55:19 -0600 (Wed, 12 Apr 2006) | 3 lines
Language fixes
export language functionality
italian language translations
---
r464 | chanh | 2006-04-12 13:59:36 -0600 (Wed, 12 Apr 2006) | 1 line
Fix missing cid variable for backward compatible with 3PD component.
---
r462 | cauld | 2006-04-11 23:47:58 -0600 (Tue, 11 Apr 2006) | 1 line
cauld - updating version.php with RC1 details
---
r461 | cauld | 2006-04-11 23:45:32 -0600 (Tue, 11 Apr 2006) | 1 line
cauld - adding uploadfiles dir for MOStlyDBAdmin local restore function
---
r460 | cauld | 2006-04-11 23:44:29 -0600 (Tue, 11 Apr 2006) | 1 line
cauld - Commenting out the containers component for now since we are about to
put out 4.6 RC1 and this is not ready.
---
r458 | counterpoint | 2006-04-11 08:36:50 -0600 (Tue, 11 Apr 2006) | 1 line
Fixed problem with newly created menu not appearing; other minor bugs
---
r456 | cauld | 2006-04-10 20:01:42 -0600 (Mon, 10 Apr 2006) | 1 line
cauld - Committing the last major revision of MOStlyCE for 4.6, version 1.6.
---
r455 | cauld | 2006-04-10 19:55:50 -0600 (Mon, 10 Apr 2006) | 1 line
cauld - Checking in the last major revision of MOStlyCE Admin for 4.6, version 1.5.
---
r454 | cauld | 2006-04-10 19:48:15 -0600 (Mon, 10 Apr 2006) | 1 line
cauld - Checking in updated overlib_mini.js.  We are running version 4.0 which is really old.
Updating us to the latest version 4.21.
---
r453 | neilt | 2006-04-07 06:51:31 -0600 (Fri, 07 Apr 2006) | 1 line
Fixed problem with html in parameters textfield within the admin modules. Trac ticket #73
---
r452 | neilt | 2006-04-07 06:30:18 -0600 (Fri, 07 Apr 2006) | 1 line
fixed missing close </tr> tags in admin module
---
r450 | neilt | 2006-04-06 12:41:09 -0600 (Thu, 06 Apr 2006) | 1 line
Bug fixes within the Admin Modules
---
r449 | counterpoint | 2006-04-05 12:41:48 -0600 (Wed, 05 Apr 2006) | 1 line
Modification to relax check on Itemid for components not having menu entries
---
r448 | neilt | 2006-04-03 12:37:56 -0600 (Mon, 03 Apr 2006) | 1 line
Changed 2 labels in poll component to Question from Title
---
r447 | counterpoint | 2006-04-03 11:51:53 -0600 (Mon, 03 Apr 2006) | 1 line
Modification to avoid over complex search and replace of #__ in database query strings
---
r446 | counterpoint | 2006-04-03 07:47:34 -0600 (Mon, 03 Apr 2006) | 1 line
Small bug fixes and optimisation
---
r445 | counterpoint | 2006-04-03 07:30:46 -0600 (Mon, 03 Apr 2006) | 1 line
Add user side of containers component (forgotten!)
---
r444 | neilt | 2006-04-03 05:58:09 -0600 (Mon, 03 Apr 2006) | 1 line
Added The Source URL to weblinks, Mambo Foundation and The Source links to Other Menu
---
r443 | neilt | 2006-04-03 05:51:01 -0600 (Mon, 03 Apr 2006) | 2 lines
Added The Source URL to weblinks (Trac #63)
Added Mambo Foundation and The Source links in Other Menu and changed title to show (Trac #71)
---
r442 | counterpoint | 2006-04-03 02:46:54 -0600 (Mon, 03 Apr 2006) | 1 line
Bug fix uninstaller error with array merge
---
r440 | cauld | 2006-04-02 20:40:16 -0600 (Sun, 02 Apr 2006) | 1 line
cauld - Updating MOStlyDBAdmin.xml file
---
r439 | cauld | 2006-04-02 17:33:27 -0600 (Sun, 02 Apr 2006) | 1 line
cauld - Adding the new MOStlyDBAdmin component for db backup and restore functionality.
---
r438 | cauld | 2006-04-02 15:34:16 -0600 (Sun, 02 Apr 2006) | 1 line
cauld - Making a quick mod to the MOStlyCE $adminside check
---
r437 | neilt | 2006-04-02 14:07:50 -0600 (Sun, 02 Apr 2006) | 1 line
incomplete <?php tag fixed in pathway.php
---
r435 | cauld | 2006-04-02 13:37:55 -0600 (Sun, 02 Apr 2006) | 1 line
cauld - Committing the MOStlyCE v1.4 Admin component (major overhaul)
---
r434 | cauld | 2006-04-02 13:34:14 -0600 (Sun, 02 Apr 2006) | 1 line
cauld - Commiting the MOStlyCE v1.5 mambot (major overhaul)
---
r433 | cauld | 2006-04-02 13:27:50 -0600 (Sun, 02 Apr 2006) | 1 line
cauld - MOStlyCE work: removing imgmanager, filemanager, & preview plugins.
Also removed auth_mostlyce.php.
---
r432 | counterpoint | 2006-04-01 01:30:03 -0700 (Sat, 01 Apr 2006) | 1 line
Add Mambo Containers admin side component.
---
r431 | neilt | 2006-03-31 04:13:07 -0700 (Fri, 31 Mar 2006) | 1 line
changed admin to show advanced_mode as default
---
r430 | adi | 2006-03-31 03:55:04 -0700 (Fri, 31 Mar 2006) | 1 line
fix bugs #8109
---
r429 | neilt | 2006-03-30 12:19:21 -0700 (Thu, 30 Mar 2006) | 6 lines
Fixed mod_latest_content no content error
Fixed handlers in mod_latest_content
Corrected cases in mod_latest_content
Removed mod_latest_content fixed width limit in horizontal td case
Fixed bug in content.class.php getBlogSectionCount() not returning 0 count
Module mod_latest_content changed to default unpublished
---
r428 | adi | 2006-03-29 01:20:05 -0700 (Wed, 29 Mar 2006) | 1 line
fixed bugs #8118
---
r426 | neilt | 2006-03-28 12:09:32 -0700 (Tue, 28 Mar 2006) | 1 line
omission of echo in <?php T_ statements
---
r425 | counterpoint | 2006-03-27 09:09:50 -0700 (Mon, 27 Mar 2006) | 1 line
Optimisation and bug fix for ampersand processing in the universal installer.
---
r424 | cauld | 2006-03-26 21:11:14 -0700 (Sun, 26 Mar 2006) | 1 line
cauld - updating security list email address on installation survey
---
r423 | counterpoint | 2006-03-23 09:32:52 -0700 (Thu, 23 Mar 2006) | 1 line
Bug fix to stop foreach on null failure.
---
r422 | counterpoint | 2006-03-22 11:41:29 -0700 (Wed, 22 Mar 2006) | 1 line
Optimisation of menu types handling, mostlyce.php request for template.  Fixed
admin side failure to consistently find session data.  Reorganised admin side to
ignore repeated logins.  Fixed offline check validation of admin session.
---
r421 | counterpoint | 2006-03-22 01:29:54 -0700 (Wed, 22 Mar 2006) | 1 line
Hardening of contact component.
---
r420 | counterpoint | 2006-03-22 01:29:28 -0700 (Wed, 22 Mar 2006) | 1 line
Optimisation of admin user and session management
---
r419 | counterpoint | 2006-03-21 11:12:17 -0700 (Tue, 21 Mar 2006) | 1 line
Optimisation and modified document root discovery.
---
r418 | counterpoint | 2006-03-21 11:11:00 -0700 (Tue, 21 Mar 2006) | 1 line
Changes to include index.php to establish the environment for editor popups.
Optimisation of requests for information about the current user's ACL status.
---
r417 | counterpoint | 2006-03-21 11:06:53 -0700 (Tue, 21 Mar 2006) | 1 line
Optimisation, merging of mosDBTable and mosDBTableEntry methods.
---
r416 | cauld | 2006-03-20 22:44:47 -0700 (Mon, 20 Mar 2006) | 1 line
cauld - Adding a donation icon to the new installation survey
---
r415 | csouza | 2006-03-20 18:29:11 -0700 (Mon, 20 Mar 2006) | 1 line
changes to com_admin/admin.admin.html.php to grab local help files
---
r413 | csouza | 2006-03-20 18:24:13 -0700 (Mon, 20 Mar 2006) | 1 line
changes to com_admin/admin.admin.html.php to grab local help files
---
r412 | csouza | 2006-03-20 18:19:39 -0700 (Mon, 20 Mar 2006) | 1 line
changes to com_admin/admin.admin.html.php to grab local help files
---
r411 | csouza | 2006-03-20 18:10:48 -0700 (Mon, 20 Mar 2006) | 1 line
changes to com_admin/admin.admin.html.php to grab local help files
---
r410 | csouza | 2006-03-20 17:58:54 -0700 (Mon, 20 Mar 2006) | 1 line
mod to pull help screens locally
---
r409 | csouza | 2006-03-20 17:22:21 -0700 (Mon, 20 Mar 2006) | 1 line
language stuff
---
r408 | csouza | 2006-03-20 16:47:49 -0700 (Mon, 20 Mar 2006) | 1 line
language defaults in install4.php
---
r406 | cauld | 2006-03-19 18:03:14 -0700 (Sun, 19 Mar 2006) | 1 line
cauld - Fixing MOStlyCE print plugin and disabling preview plugin in favor
own Mambo's preview option.
---
r403 | csouza | 2006-03-19 13:41:39 -0700 (Sun, 19 Mar 2006) | 1 line
language manager
---
r402 | counterpoint | 2006-03-19 07:32:34 -0700 (Sun, 19 Mar 2006) | 1 line
Extended new contact popup for linking to user, so as to show both full name and user name.
---
r401 | counterpoint | 2006-03-19 03:28:33 -0700 (Sun, 19 Mar 2006) | 1 line
Recorded changes to restore functioning of popups.
---
r400 | counterpoint | 2006-03-19 03:22:44 -0700 (Sun, 19 Mar 2006) | 1 line
Reinstate popup changes that had got lost
---
r397 | cauld | 2006-03-16 23:19:47 -0700 (Thu, 16 Mar 2006) | 1 line
cauld - Bringing back MOStlyCE / TinyMCE 2.0.2 with fixed IE issue reported during Beta 1.
---
r396 | cauld | 2006-03-16 22:57:17 -0700 (Thu, 16 Mar 2006) | 1 line
cauld - Removing mostlyce directory.  Reverting back to TinyMCE 2.0.2.
Too many issues with 2.0.4.  Fixed IE error without upgrading.
---
r393 | counterpoint | 2006-03-14 14:59:44 -0700 (Tue, 14 Mar 2006) | 1 line
Tightened security
---
r392 | counterpoint | 2006-03-14 14:59:00 -0700 (Tue, 14 Mar 2006) | 1 line
Mods to support new container component, and to secure RSS feeds.
---
r391 | counterpoint | 2006-03-13 11:15:58 -0700 (Mon, 13 Mar 2006) | 1 line
Modified SEF code so that when SEO is switched off, URL still has ampersands encoded.
---
r390 | counterpoint | 2006-03-13 11:15:11 -0700 (Mon, 13 Mar 2006) | 1 line
Reverted mosPathway makePathway, moved change into sefRelToAbs.  Modified error reporting
slightly to suppress errors during setup.
---
r388 | cauld | 2006-03-12 12:39:31 -0700 (Sun, 12 Mar 2006) | 1 line
cauld - Changing end user installation survey to hand comments a bit differently.
Comments now go to feedback@mambo-foundation.org.
---
r387 | cauld | 2006-03-12 12:03:29 -0700 (Sun, 12 Mar 2006) | 1 line
cauld - Fixing mostlyce.xml for IE contextmenu change
---
r386 | cauld | 2006-03-12 12:00:28 -0700 (Sun, 12 Mar 2006) | 1 line
cauld - Adding IE fix for the MOStlyCE contextmenu plugin
---
r385 | counterpoint | 2006-03-12 08:46:12 -0700 (Sun, 12 Mar 2006) | 1 line
Optimisation of mosDBTable move method.
---
r384 | counterpoint | 2006-03-12 08:44:55 -0700 (Sun, 12 Mar 2006) | 1 line
Bug fixes
---
r383 | counterpoint | 2006-03-12 08:43:56 -0700 (Sun, 12 Mar 2006) | 1 line
Hardened against misuse
---
r382 | counterpoint | 2006-03-12 08:43:33 -0700 (Sun, 12 Mar 2006) | 1 line
Removed reliance on register_globals
---
r380 | counterpoint | 2006-03-09 07:48:49 -0700 (Thu, 09 Mar 2006) | 1 line
Changes to mosDBTableEntry - not in live use - but needed for mosContainer development.
---
r379 | cauld | 2006-03-08 22:54:56 -0700 (Wed, 08 Mar 2006) | 1 line
cauld - updating MOStlyCE xml file for TinyMCE 2.0.4 update
---
r378 | cauld | 2006-03-08 22:51:54 -0700 (Wed, 08 Mar 2006) | 1 line
cauld - finishing MOStlyCE 1.5 / TinyMCE 2.0.4 upgrade
---
r376 | cauld | 2006-03-08 22:01:09 -0700 (Wed, 08 Mar 2006) | 1 line
cauld - MOStlyCE Admin update to disable default compression
---
r372 | cauld | 2006-03-08 21:27:28 -0700 (Wed, 08 Mar 2006) | 1 line
cauld - working on a mostlyce upgrade
---
r371 | counterpoint | 2006-03-08 10:51:49 -0700 (Wed, 08 Mar 2006) | 1 line
Fix problem with mass chmod not recursing correctly
---
r370 | counterpoint | 2006-03-08 10:51:22 -0700 (Wed, 08 Mar 2006) | 1 line
Fix access to Mambo configuration - cope in different contexts
---
r369 | counterpoint | 2006-03-08 10:50:42 -0700 (Wed, 08 Mar 2006) | 1 line
Fix foreach failure on empty database results
---
r363 | adi | 2006-03-06 19:42:01 -0700 (Mon, 06 Mar 2006) | 1 line
fix ticket #32
---
r362 | adi | 2006-03-06 19:26:20 -0700 (Mon, 06 Mar 2006) | 1 line
fix typo
---
r361 | cauld | 2006-03-06 07:02:33 -0700 (Mon, 06 Mar 2006) | 1 line
cauld - rechecking in gin's trash fix that was overwritten a while back
---
r359 | adi | 2006-03-06 04:11:53 -0700 (Mon, 06 Mar 2006) | 1 line
fix ticket #31
---
r357 | cauld | 2006-03-04 23:59:11 -0700 (Sat, 04 Mar 2006) | 1 line
cauld - updating version info for 4.6 public RC1
---
r355 | cauld | 2006-03-04 23:54:55 -0700 (Sat, 04 Mar 2006) | 1 line
cauld - Adding new end user survey functionality to the last install screen
---
r353 | counterpoint | 2006-03-02 10:26:20 -0700 (Thu, 02 Mar 2006) | 1 line
Bug fix - was reliant on register_globals.
---
r352 | oziris | 2006-03-01 12:18:50 -0700 (Wed, 01 Mar 2006) | 1 line
Ticket #10 changing www.mamboserver.com to www.mambo-foundation.org
---
r351 | oziris | 2006-03-01 11:57:37 -0700 (Wed, 01 Mar 2006) | 1 line
Ticket #8
---
r350 | oziris | 2006-03-01 11:35:50 -0700 (Wed, 01 Mar 2006) | 1 line
Ticket #9
---
r348 | cauld | 2006-03-01 07:35:46 -0700 (Wed, 01 Mar 2006) | 1 line
cauld - updating "Lost Password" error message to make it more clear that
both username and email address are required.
---
r347 | adi | 2006-02-28 21:33:11 -0700 (Tue, 28 Feb 2006) | 1 line
ticket #46
---
r346 | oziris | 2006-02-28 09:07:58 -0700 (Tue, 28 Feb 2006) | 1 line
updated calls to new help file names
---
r345 | oziris | 2006-02-28 08:32:43 -0700 (Tue, 28 Feb 2006) | 1 line
Renamed help files so they don't contain 453 in the file name
---
r342 | cauld | 2006-02-28 07:53:09 -0700 (Tue, 28 Feb 2006) | 1 line
cauld - Changing default MOStlyCE plugin layout based on suggestions from Water & Stone
---
r341 | cauld | 2006-02-28 06:31:13 -0700 (Tue, 28 Feb 2006) | 1 line
cauld - Creating a empty files directory for use by MOStlyCE.  Uses this with
HTML templates and the file manager.  Closing Trac ticket #45.
---
r339 | adi | 2006-02-27 21:48:46 -0700 (Mon, 27 Feb 2006) | 1 line
add mambo version info in administrator pages
---
r338 | adi | 2006-02-27 03:55:24 -0700 (Mon, 27 Feb 2006) | 1 line
Trac Ticket #49
---
r337 | adi | 2006-02-27 01:23:16 -0700 (Mon, 27 Feb 2006) | 1 line
fix makePathway() to correctly encode & entity into &amp;
---
r336 | adi | 2006-02-26 23:14:05 -0700 (Sun, 26 Feb 2006) | 1 line
Trac Ticket #41
---
r334 | adi | 2006-02-26 21:36:06 -0700 (Sun, 26 Feb 2006) | 1 line
add mod_latestcontent record
---
r333 | adi | 2006-02-26 21:32:34 -0700 (Sun, 26 Feb 2006) | 1 line
add latest_content module
---
r331 | cauld | 2006-02-25 12:49:00 -0700 (Sat, 25 Feb 2006) | 1 line
cauld - setting htmltemplate and caption to the list of auto started plugins within mostlyce
---
r328 | cauld | 2006-02-25 12:28:09 -0700 (Sat, 25 Feb 2006) | 1 line
cauld - fixing a bug that caused the "Not Authorized" error when someone was trying to edit content from the frontend
---
r327 | cauld | 2006-02-25 11:55:31 -0700 (Sat, 25 Feb 2006) | 1 line
cauld - correcting a mostlyce htmltemplate plugin issue
---
r326 | cauld | 2006-02-25 11:54:12 -0700 (Sat, 25 Feb 2006) | 2 lines
cauld - correcting mostlyce config files for plugin changes
---
r325 | cauld | 2006-02-25 11:03:32 -0700 (Sat, 25 Feb 2006) | 1 line
cauld - removing 4 unused mostlyce plugins
---
r324 | cauld | 2006-02-25 10:57:35 -0700 (Sat, 25 Feb 2006) | 1 line
cauld - adding back in to old tinymce plugins that I removed before (caption & htmltemplate)
---
r323 | oziris | 2006-02-24 13:57:52 -0700 (Fri, 24 Feb 2006) | 1 line
Updated for copyright notices.
---
r322 | oziris | 2006-02-24 13:55:29 -0700 (Fri, 24 Feb 2006) | 1 line
Updated Copyright notices in XML files.
---
r320 | counterpoint | 2006-02-24 09:46:13 -0700 (Fri, 24 Feb 2006) | 1 line
Improved (un)installer error handling. Modified session purge, making distinction between user side and admin side.
---
r319 | counterpoint | 2006-02-23 04:30:16 -0700 (Thu, 23 Feb 2006) | 1 line
Bug fixes
---
r316 | adi | 2006-02-22 23:25:01 -0700 (Wed, 22 Feb 2006) | 1 line
update mamboforge to mamboxchange
---
r315 | konlong | 2006-02-22 06:31:46 -0700 (Wed, 22 Feb 2006) | 2 lines
Reference to undefined variable fixed in admin.menus.php function copyMenuSave
The use of the variable $and should be avoided in deference to $_and, $and gives an unknown token notice 8 files
---
r314 | konlong | 2006-02-22 05:57:20 -0700 (Wed, 22 Feb 2006) | 2 lines
added call to addDescendants to the remove case.
Fixed undefined variable $database; in saveOrder()
---
r313 | chanh | 2006-02-21 16:20:16 -0700 (Tue, 21 Feb 2006) | 2 lines
Missing closing php tags!
---
r312 | chanh | 2006-02-21 16:08:14 -0700 (Tue, 21 Feb 2006) | 1 line
Missing closing php tags!
---
r311 | chanh | 2006-02-21 16:07:58 -0700 (Tue, 21 Feb 2006) | 1 line
Missing closing php tags!
---
r310 | cauld | 2006-02-21 07:02:46 -0700 (Tue, 21 Feb 2006) | 1 line
cauld - committing a change to database.php to fix a content update / insert
issue based on a suggested fix by counterpoint
---
r309 | adi | 2006-02-20 20:47:49 -0700 (Mon, 20 Feb 2006) | 1 line
Trac ticket #36
---
r305 | cauld | 2006-02-18 16:10:53 -0700 (Sat, 18 Feb 2006) | 1 line
cauld - Adding some stuff for MOStlyCE and fixing Safari warning
---
r304 | csouza | 2006-02-16 20:07:10 -0700 (Thu, 16 Feb 2006) | 2 lines
corrected a few non i18ned strings
---
r303 | csouza | 2006-02-16 20:06:03 -0700 (Thu, 16 Feb 2006) | 1 line
added localization vars to configuration.php-dist
---
r302 | csouza | 2006-02-16 20:05:11 -0700 (Thu, 16 Feb 2006) | 1 line
fixed fixlanguage()
---
r301 | csouza | 2006-02-16 20:04:10 -0700 (Thu, 16 Feb 2006) | 1 line
updated mod_whosonline to select a plural string from a specific language file
---
r300 | csouza | 2006-02-16 20:02:31 -0700 (Thu, 16 Feb 2006) | 1 line
updated com_languages
---
r299 | csouza | 2006-02-16 20:02:08 -0700 (Thu, 16 Feb 2006) | 1 line
updated com_languages
---
r298 | csouza | 2006-02-16 20:00:30 -0700 (Thu, 16 Feb 2006) | 1 line
updated com_languages
---
r297 | csouza | 2006-02-16 14:43:38 -0700 (Thu, 16 Feb 2006) | 1 line
replacement of copyright notices
---
r296 | cauld | 2006-02-16 08:02:03 -0700 (Thu, 16 Feb 2006) | 1 line
cauld - fixing PHP version to low notice on install
---
r295 | cauld | 2006-02-16 07:58:50 -0700 (Thu, 16 Feb 2006) | 1 line
cauld - updating install screen to display PHP version 4.3.0 as min requirement
---
r294 | adi | 2006-02-16 01:59:01 -0700 (Thu, 16 Feb 2006) | 1 line
remove td width in printicon function
---
r293 | adi | 2006-02-16 01:57:15 -0700 (Thu, 16 Feb 2006) | 1 line
remove td width in pdficon and emailicon function
---
r292 | adi | 2006-02-16 01:34:47 -0700 (Thu, 16 Feb 2006) | 1 line
Trac ticket #38
---
r290 | counterpoint | 2006-02-15 16:16:04 -0700 (Wed, 15 Feb 2006) | 1 line
Fix submit news problems by changing to submit faq (also removed itemid)
---
r289 | counterpoint | 2006-02-15 10:56:19 -0700 (Wed, 15 Feb 2006) | 1 line
Fixed bugs in resequencing various types of item
---
r288 | counterpoint | 2006-02-15 10:18:43 -0700 (Wed, 15 Feb 2006) | 1 line
Bug fixes
---
r287 | counterpoint | 2006-02-15 09:49:55 -0700 (Wed, 15 Feb 2006) | 1 line
Bug fixes
---
r286 | counterpoint | 2006-02-15 04:18:27 -0700 (Wed, 15 Feb 2006) | 2 lines
Bug fixes
---
r284 | adi | 2006-02-14 22:18:19 -0700 (Tue, 14 Feb 2006) | 1 line
fix css problem for menu height in firefox
---
r283 | counterpoint | 2006-02-14 15:39:59 -0700 (Tue, 14 Feb 2006) | 1 line
Bug fix problem analysing parameters from XML
---
r282 | counterpoint | 2006-02-14 15:38:15 -0700 (Tue, 14 Feb 2006) | 1 line
Bug fix theme.js incorrect path to find menu images
---
r281 | counterpoint | 2006-02-14 14:42:10 -0700 (Tue, 14 Feb 2006) | 2 lines
Bug fix
---
r277 | adi | 2006-02-14 01:14:48 -0700 (Tue, 14 Feb 2006) | 2 lines
hide secret word in global config based on, Trac ticket #9
---
r276 | adi | 2006-02-14 00:38:24 -0700 (Tue, 14 Feb 2006) | 1 line
Trac ticket #5
---
r275 | adi | 2006-02-14 00:32:53 -0700 (Tue, 14 Feb 2006) | 2 lines
update sample data, Trac ticket #4
---
r274 | adi | 2006-02-14 00:17:41 -0700 (Tue, 14 Feb 2006) | 2 lines
remove key reference based on
http://forum.mamboserver.com/showthread.php?t=66453
---
r273 | adi | 2006-02-13 22:46:12 -0700 (Mon, 13 Feb 2006) | 2 lines
add client id column in showClients function, based on
Trac ticket #15
---
r272 | counterpoint | 2006-02-13 08:41:51 -0700 (Mon, 13 Feb 2006) | 1 line
Installer developments and bugs, syndstyle handling.
---
r271 | counterpoint | 2006-02-13 08:37:07 -0700 (Mon, 13 Feb 2006) | 1 line
Installer bug fixes and development.
---
r270 | counterpoint | 2006-02-13 08:31:12 -0700 (Mon, 13 Feb 2006) | 1 line
Syndstyle and file handling bug fixes.
---
r269 | counterpoint | 2006-02-13 08:12:12 -0700 (Mon, 13 Feb 2006) | 1 line
Introduce syndstyle to allow components to be used as objects at other sites.
---
r268 | adi | 2006-02-13 03:59:27 -0700 (Mon, 13 Feb 2006) | 1 line
fix global variables
---
r267 | adi | 2006-02-13 03:52:27 -0700 (Mon, 13 Feb 2006) | 1 line
fix showInstallMessage function, add third parameter for redirect
---
r266 | csouza | 2006-02-12 16:49:56 -0700 (Sun, 12 Feb 2006) | 2 lines
fixed some localization bugs
---
r265 | csouza | 2006-02-12 16:49:14 -0700 (Sun, 12 Feb 2006) | 1 line
localization
---
r264 | csouza | 2006-02-12 16:45:24 -0700 (Sun, 12 Feb 2006) | 1 line
fixed some localization bugs
---
r263 | csouza | 2006-02-12 16:44:33 -0700 (Sun, 12 Feb 2006) | 1 line
localization fixes
---
r262 | csouza | 2006-02-12 16:43:25 -0700 (Sun, 12 Feb 2006) | 1 line
fixed some localization bugs
---
r260 | cauld | 2006-02-12 15:11:01 -0700 (Sun, 12 Feb 2006) | 1 line
cauld - Adding MOStlyCE onclick plugin
---
r259 | cauld | 2006-02-12 13:41:12 -0700 (Sun, 12 Feb 2006) | 1 line
cauld - MOStlyCE table plugin upgrade
---
r258 | cauld | 2006-02-12 13:39:40 -0700 (Sun, 12 Feb 2006) | 1 line
cauld - MOStlyCE advlink plugin upgrade
---
r257 | cauld | 2006-02-12 13:38:07 -0700 (Sun, 12 Feb 2006) | 1 line
cauld - MOStlyCE advimage plugin fix
---
r256 | cauld | 2006-02-12 13:36:52 -0700 (Sun, 12 Feb 2006) | 1 line
cauld - MOStlyCE advhr plugin fix
---
r255 | cauld | 2006-02-12 13:34:32 -0700 (Sun, 12 Feb 2006) | 1 line
cauld - MOStlyCE preview plugin fix
---
r254 | cauld | 2006-02-12 13:31:54 -0700 (Sun, 12 Feb 2006) | 1 line
cauld - MOStlyCE auth_plugin.php change to handle the removal of auth.php
---
r253 | csouza | 2006-02-12 12:38:26 -0700 (Sun, 12 Feb 2006) | 1 line
mostlyce popup fix
---
r252 | csouza | 2006-02-12 12:37:34 -0700 (Sun, 12 Feb 2006) | 2 lines
fix for mostlyce popup and localization calls to select language file
---
r251 | cauld | 2006-02-12 10:25:32 -0700 (Sun, 12 Feb 2006) | 1 line
cauld - more MOStlyCE changes
---
r250 | cauld | 2006-02-12 10:17:00 -0700 (Sun, 12 Feb 2006) | 1 line
cauld - adding mclayer.js for MOStlyCE
---
r247 | cauld | 2006-02-12 08:52:29 -0700 (Sun, 12 Feb 2006) | 1 line
cauld - removing old MOStlyCE onclick plugin
---
r246 | cauld | 2006-02-12 08:49:29 -0700 (Sun, 12 Feb 2006) | 1 line
cauld - Upgrading MOStlyCE guts to 2.0.2 and fixing a few MOStlyCE bugs
---
r245 | counterpoint | 2006-02-10 10:38:03 -0700 (Fri, 10 Feb 2006) | 1 line
Mambot handler fix, compatibility fixes.
---
r244 | counterpoint | 2006-02-10 07:08:05 -0700 (Fri, 10 Feb 2006) | 1 line
Compatibility fixes.
---
r243 | counterpoint | 2006-02-10 05:42:40 -0700 (Fri, 10 Feb 2006) | 1 line
Handle https and non standard ports; reduce volume of metadata by restricting to one item.
---
r238 | counterpoint | 2006-02-07 11:03:58 -0700 (Tue, 07 Feb 2006) | 1 line
Bug fixes, mostly missing ampersands.
---
r237 | counterpoint | 2006-02-06 09:36:18 -0700 (Mon, 06 Feb 2006) | 1 line
Remove "generator" tag, improve SEF to cover archived material, correct bugs in contact component.
---
r236 | csouza | 2006-02-06 08:35:49 -0700 (Mon, 06 Feb 2006) | 1 line
fixed php4 reference to component handler
---
r235 | counterpoint | 2006-02-05 10:46:04 -0700 (Sun, 05 Feb 2006) | 1 line
Bug fix
---
r234 | counterpoint | 2006-02-05 09:08:28 -0700 (Sun, 05 Feb 2006) | 1 line
Security fixes.  Add universal installer code.
---
r233 | cauld | 2006-02-04 14:29:29 -0700 (Sat, 04 Feb 2006) | 1 line
cauld - updating version info for release
---
r232 | csouza | 2006-02-04 13:36:36 -0700 (Sat, 04 Feb 2006) | 2 lines
corrected path to phpInputFilter
---
r231 | csouza | 2006-02-04 13:36:24 -0700 (Sat, 04 Feb 2006) | 2 lines
corrected path to phpInputFilter
---
r230 | csouza | 2006-02-04 13:13:58 -0700 (Sat, 04 Feb 2006) | 1 line
added admin.languages.class.php
---
r229 | cauld | 2006-02-04 11:45:08 -0700 (Sat, 04 Feb 2006) | 1 line
cauld - fixing phpInputFilter path
---
r228 | cauld | 2006-02-04 10:32:51 -0700 (Sat, 04 Feb 2006) | 1 line
cauld - fixing issue with __parameters create table statement
---
r226 | counterpoint | 2006-02-03 02:43:17 -0700 (Fri, 03 Feb 2006) | 1 line
Delete patTemplate
---
r223 | counterpoint | 2006-02-02 15:13:45 -0700 (Thu, 02 Feb 2006) | 1 line
Tidying up and bug fixing.
---
r222 | counterpoint | 2006-02-02 07:50:48 -0700 (Thu, 02 Feb 2006) | 1 line
Various bug fixes and tidying up.
---
r221 | csouza | 2006-02-01 16:44:54 -0700 (Wed, 01 Feb 2006) | 1 line
corrected notice in fixlanguages
---
r220 | csouza | 2006-02-01 16:21:57 -0700 (Wed, 01 Feb 2006) | 1 line
language fix
---
r219 | csouza | 2006-02-01 16:04:51 -0700 (Wed, 01 Feb 2006) | 1 line
language/english.xml
---
r218 | counterpoint | 2006-02-01 13:38:06 -0700 (Wed, 01 Feb 2006) | 1 line
Universal installer UI.
---
r216 | csouza | 2006-02-01 09:45:51 -0700 (Wed, 01 Feb 2006) | 1 line
complete set of language files for translation
---
r213 | counterpoint | 2006-01-31 05:11:42 -0700 (Tue, 31 Jan 2006) | 1 line
Mambot developments.
---
r212 | counterpoint | 2006-01-31 01:30:02 -0700 (Tue, 31 Jan 2006) | 1 line
Add support for free standing parameter object installation.
---
r211 | csouza | 2006-01-30 16:38:54 -0700 (Mon, 30 Jan 2006) | 1 line
added define check to prevent notices
---
r210 | csouza | 2006-01-30 16:31:55 -0700 (Mon, 30 Jan 2006) | 1 line
restored english.php and english.ignore.php
---
r209 | counterpoint | 2006-01-30 15:13:58 -0700 (Mon, 30 Jan 2006) | 1 line
Extra mambot hooks for registration, password changes.
---
r208 | counterpoint | 2006-01-30 10:58:42 -0700 (Mon, 30 Jan 2006) | 1 line
Handle language issue causing double display of modules.
---
r207 | counterpoint | 2006-01-30 07:25:33 -0700 (Mon, 30 Jan 2006) | 1 line
Possible fix for authenticator not found bug.
---
r206 | counterpoint | 2006-01-30 07:14:36 -0700 (Mon, 30 Jan 2006) | 1 line
Installer error handling, version to point to Foundation web site, attempted fix for redirect.
---
r205 | cauld | 2006-01-28 10:06:06 -0700 (Sat, 28 Jan 2006) | 1 line
---
r204 | cauld | 2006-01-28 10:03:42 -0700 (Sat, 28 Jan 2006) | 1 line
cauld - adding MOStlyCE admin component
---
r202 | cauld | 2006-01-28 09:45:00 -0700 (Sat, 28 Jan 2006) | 1 line
cauld - MOStlyCE change
---
r199 | counterpoint | 2006-01-27 08:37:12 -0700 (Fri, 27 Jan 2006) | 1 line
Installer bug fixes and changes to handle Docman latest.  Bug fix in installation/index.php.
---
r198 | csouza | 2006-01-26 17:20:20 -0700 (Thu, 26 Jan 2006) | 1 line
added the final complete language catalog containing 2027 unique strings.
---
r197 | counterpoint | 2006-01-26 16:40:05 -0700 (Thu, 26 Jan 2006) | 1 line
Correction for archive manager - error when database objects not an array - used for array merge.
---
r196 | counterpoint | 2006-01-26 16:23:04 -0700 (Thu, 26 Jan 2006) | 1 line
Improved site search.
---
r195 | csouza | 2006-01-26 13:20:17 -0700 (Thu, 26 Jan 2006) | 1 line
removed unnecessary file from com_languages
---
r194 | csouza | 2006-01-26 13:11:56 -0700 (Thu, 26 Jan 2006) | 1 line
verified and corrected all deprecated localization constants
---
r193 | counterpoint | 2006-01-26 11:09:47 -0700 (Thu, 26 Jan 2006) | 1 line
Optimise DB access in admin of users.  Correct HTML yes/no radio buttons.
---
r192 | counterpoint | 2006-01-26 11:07:55 -0700 (Thu, 26 Jan 2006) | 1 line
Replaced ACL related code for storing new users (removed during testing of new access mechanisms).
---
r191 | counterpoint | 2006-01-26 09:07:51 -0700 (Thu, 26 Jan 2006) | 1 line
Small bug fixes
---
r190 | cauld | 2006-01-26 07:45:16 -0700 (Thu, 26 Jan 2006) | 1 line
cauld - fixing a mostlyce xml issue
---
r189 | cauld | 2006-01-26 07:44:44 -0700 (Thu, 26 Jan 2006) | 1 line
cauld - fixing a mostlyce xml issue
---
r188 | csouza | 2006-01-26 06:06:55 -0700 (Thu, 26 Jan 2006) | 1 line
fixed localization notices
---
r187 | counterpoint | 2006-01-26 05:49:51 -0700 (Thu, 26 Jan 2006) | 1 line
Fix problem in mambo.sql losing user login authenticator mambot.
---
r186 | counterpoint | 2006-01-26 04:51:01 -0700 (Thu, 26 Jan 2006) | 1 line
Merge and other bug fixes.  Suppress pathway if only says "Home".  Suppress warnings from Magpie.
---
r185 | csouza | 2006-01-25 16:55:33 -0700 (Wed, 25 Jan 2006) | 1 line
replaced language constants
---
r183 | csouza | 2006-01-25 14:36:13 -0700 (Wed, 25 Jan 2006) | 1 line
fixed i18n bugs and notices
---
r182 | counterpoint | 2006-01-25 10:21:03 -0700 (Wed, 25 Jan 2006) | 1 line
Adjust for database.php moving back to /includes.  Remove executable code from version.php (should be just class, executable code is in index.php).
---
r180 | csouza | 2006-01-25 06:42:26 -0700 (Wed, 25 Jan 2006) | 1 line
fixed localization bug
---
r179 | csouza | 2006-01-25 06:41:01 -0700 (Wed, 25 Jan 2006) | 1 line
phpgettext update
---
r178 | csouza | 2006-01-24 09:16:09 -0700 (Tue, 24 Jan 2006) | 1 line
update phpgettext.catalog.php
---
r177 | csouza | 2006-01-24 09:15:11 -0700 (Tue, 24 Jan 2006) | 1 line
updating language directory and first complete string catalog
---
r176 | cauld | 2006-01-24 07:36:50 -0700 (Tue, 24 Jan 2006) | 1 line
cauld - fixing MOStlyCE undefined variable notice
---
r175 | csouza | 2006-01-24 04:45:38 -0700 (Tue, 24 Jan 2006) | 1 line
corrected return value in ngettext
---
r174 | counterpoint | 2006-01-24 03:27:45 -0700 (Tue, 24 Jan 2006) | 1 line
Correct problem with merge.
---
r172 | counterpoint | 2006-01-24 02:23:40 -0700 (Tue, 24 Jan 2006) | 1 line
Correct short tag
---
r171 | cauld | 2006-01-23 21:57:24 -0700 (Mon, 23 Jan 2006) | 1 line
cauld - fixing an include file in mostlyce.php
---
r170 | counterpoint | 2006-01-23 16:11:50 -0700 (Mon, 23 Jan 2006) | 1 line
Bring 4.5.4 /includes/frontend.php into 4.6
---
r169 | counterpoint | 2006-01-23 10:57:22 -0700 (Mon, 23 Jan 2006) | 1 line
Fix installation bugs.
---
r168 | csouza | 2006-01-22 21:47:31 -0700 (Sun, 22 Jan 2006) | 1 line
modified getConfig () in index.php to remove 'administrator' from mosConfig_live_site
---
r167 | csouza | 2006-01-22 21:45:40 -0700 (Sun, 22 Jan 2006) | 1 line
switched phpgettext debugging off
---
r166 | csouza | 2006-01-22 21:44:09 -0700 (Sun, 22 Jan 2006) | 1 line
added two css classes to remove <font> tags
---
r162 | cauld | 2006-01-22 01:10:42 -0700 (Sun, 22 Jan 2006) | 1 line
cauld - updating install sql file to include entries for MOStlyCE
---
r160 | cauld | 2006-01-22 01:04:13 -0700 (Sun, 22 Jan 2006) | 1 line
cauld - adding MOStlyCE mambot
---
r159 | cauld | 2006-01-22 01:01:09 -0700 (Sun, 22 Jan 2006) | 1 line
cauld - adding MOStlyCE component
---
r158 | cauld | 2006-01-22 00:58:59 -0700 (Sun, 22 Jan 2006) | 1 line
cauld - cleaning merge artifacts in index.php that were causing failures
---
r157 | mambo | 2006-01-21 09:20:19 -0700 (Sat, 21 Jan 2006) | 1 line
cauld - Merging in the final 4.5.4 branch revisions (155 & 156).
The 4.5.4 branch is now closed.
---
r153 | csouza | 2006-01-19 10:11:47 -0700 (Thu, 19 Jan 2006) | 1 line
added debugging capabilities
---
r152 | csouza | 2006-01-19 10:10:57 -0700 (Thu, 19 Jan 2006) | 1 line
added gettext support to mamboCore::fixLanguage()
---
r151 | csouza | 2006-01-19 07:24:56 -0700 (Thu, 19 Jan 2006) | 1 line
csouza - Merging 4.5.4 changes (revisions 139 to 148) into 4.6.
---
r150 | csouza | 2006-01-19 06:37:49 -0700 (Thu, 19 Jan 2006) | 1 line
csouza - i18n - uploaded/removed i18n files
---
r149 | csouza | 2006-01-19 06:29:35 -0700 (Thu, 19 Jan 2006) | 1 line
csouza - i18n - uploaded several internationalized files
---
r138 | csouza | 2006-01-16 04:12:43 -0700 (Mon, 16 Jan 2006) | 1 line
csouza - Merging 4.5.4 changes (revisions 131 to 137) into 4.6.
---
r129 | csouza | 2006-01-14 11:19:40 -0700 (Sat, 14 Jan 2006) | 1 line
csouza - Merging 4.5.4 changes (revisions 118 to 127) into 4.6.
---
r117 | mambo | 2006-01-07 14:26:39 -0700 (Sat, 07 Jan 2006) | 1 line
cauld - Merging the current 4.5.4 changes (revisions 2 thru 99) into 4.6.
---
r114 | mambo | 2006-01-06 14:16:56 -0700 (Fri, 06 Jan 2006) | 1 line
cauld - merging 453h revisions 30 thru 38 into 4.6
---
r113 | mambo | 2006-01-06 13:05:43 -0700 (Fri, 06 Jan 2006) | 1 line
cauld - removing the old tinymce dir from 4.6.  it is causing merge issues with this branch as well.
---
r112 | mambo | 2006-01-05 22:54:35 -0700 (Thu, 05 Jan 2006) | 1 line
cauld - merging revisions 22 thru 30 into 4.6.
---
r3 | root | 2005-12-12 20:49:45 -0700 (Mon, 12 Dec 2005) | 1 line
creating branch
---
