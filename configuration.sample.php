<?php
/**
* @package Mambo
* @author Mambo Foundation Inc see README.php
* @copyright Mambo Foundation Inc.
* See COPYRIGHT.php for copyright notices and details.
* @license GNU/GPL Version 2, see LICENSE.php
* Mambo is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; version 2 of the
* License.
*
* -------------------------------------------------------------------------
* If you are installing Mambo manually i.e. not using the web installer
* then rename this file to configuration.php e.g.
*
* UNIX -> mv configuration.sample.php configuration.php
* Windows -> rename configuration.sample.php configuration.php
*
* Now edit this file and configure the parameters for your site and
* database.
* -------------------------------------------------------------------------
* Database configuration section
* -------------------------------------------------------------------------
*/
$mosConfig_offline = '0';
$mosConfig_host = 'localhost';	// This is normally set to localhost
$mosConfig_user = '';			// MySQL username
$mosConfig_password = '';		// MySQL password
$mosConfig_db = '';				// MySQL database name
$mosConfig_dbprefix = 'mos_';	// Do not change unless you need to!
/**
* -------------------------------------------------------------------------
* Site specific configuration
* -------------------------------------------------------------------------
*/
$mosConfig_absolute_path = '/path/to/mambo/install';	// No trailing slash
$mosConfig_live_site = 'http://www.your_mambo_site.com';	// No trailing slash.  Make sure to use www.
$mosConfig_sitename = 'Mambo';		// Name of Mambo site
$mosConfig_shownoauth = '1';				// Display links & categories users don't have access to
$mosConfig_useractivation = '1';			// Send new registration passwords via e-mail
$mosConfig_uniquemail = '1';				// Require unique email adress for each user
$mosConfig_usecaptcha = '0';				// Enable form captcha security
$mosConfig_offline_message = 'This site is down for maintenance.<br /> Please check back again soon.';
$mosConfig_lifetime = '900'; 				// Session time
$mosConfig_MetaDesc = 'Mambo - the dynamic portal engine and content management system';
$mosConfig_MetaKeys = 'mambo, Mambo, Mambo, Mambo';
$mosConfig_MetaTitle = '1';
$mosConfig_MetaAuthor = '1';
$mosConfig_debug = '0';
$mosConfig_lang = 'english';				        // Site language
$mosConfig_locale = 'en';
$mosConfig_offset = '0';				// Local time offset
$mosConfig_locale_debug = '0';
$mosConfig_locale_use_gettext = '0';
$mosConfig_hideAuthor = '0';
$mosConfig_hideCreateDate = '0';
$mosConfig_hideModifyDate = '0';
$mosConfig_hidePdf = '0';
$mosConfig_hidePrint = '0';
$mosConfig_hideEmail = '0';
$mosConfig_enable_log_items = '0';
$mosConfig_enable_log_searches = '0';
$mosConfig_enable_stats = '0';
$mosConfig_sef = '0';
$mosConfig_vote = '0';
$mosConfig_gzip = '0';
$mosConfig_multipage_toc = '0';
$mosConfig_allowUserRegistration = '1';
$mosConfig_error_reporting = '-1';
$mosConfig_register_globals = '1';
$mosConfig_error_message = 'This site is temporarily unavailable.<br />Please contact your System Administrator.';
$mosConfig_link_titles = '0';
$mosConfig_list_limit = '50';
$mosConfig_caching = '0';
$mosConfig_cachepath = '/path/to/mambo/install/cache';
$mosConfig_cachetime = '900';
$mosConfig_mailer = 'mail';
$mosConfig_mailfrom = '';
$mosConfig_fromname = '';
$mosConfig_sendmail = '/usr/sbin/sendmail';
$mosConfig_smtpauth = '0';
$mosConfig_smtpuser = '';
$mosConfig_smtppass = '';
$mosConfig_smtphost = 'localhost';
$mosConfig_back_button = '0';
$mosConfig_item_navigation = '1';
$mosConfig_secret = 'FBVtggIk5lAzEU9H'; //Change this to something more secure
$mosConfig_pagetitles = '1';
$mosConfig_readmore = '1';
$mosConfig_hits = '1';
$mosConfig_icons = '1';
$mosConfig_favicon = 'favicon.ico';
$mosConfig_fileperms = '';
$mosConfig_dirperms = '';
$mosConfig_mbf_content='0';
$mosConfig_helpurl = 'http://docs.mambo-foundation.org';
setlocale (LC_TIME, $mosConfig_locale);			// Country locale
?>