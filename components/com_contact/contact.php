<?php
/**
* @package Mambo
* @subpackage Contact
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

// load the html drawing class
require_once( $mainframe->getPath( 'front_html' ) );
require_once( $mainframe->getPath( 'class' ) );

class contactUserControllers {
	var $manager = '';
	var $user = '';
	var $op = '';
	var $con_id = 0;
	var $contact_id = 0;
	var $catid = 0;
	var $Itemid = 0;

	function contactUserControllers ($manager) {
		$this->manager = $manager;
		$this->user = mamboCore::get('currentUser');
		$this->op = mosGetParam($_REQUEST, 'op', '');
		$this->con_id = mosGetParam( $_REQUEST ,'con_id', 0 );
		$this->contact_id = mosGetParam( $_REQUEST ,'contact_id', 0 );
		$this->catid = mosGetParam( $_REQUEST ,'catid', 0 );
		$this->Itemid = mamboCore::get('Itemid');
	}

	function sendmail () {

		global $mosConfig_usecaptcha;

		$captcha_success = 0;
		if ($mosConfig_usecaptcha == '1') {

			session_name('mos_captcha');
			session_start();

			$spamstop = mosGetParam( $_POST, 'spamstop', '' );

			if(isset($_SESSION['code']) && ($_SESSION['code'] != "") && ($_SESSION['code'] == $spamstop)) {
				$captcha_success = 1; // success
			} else {
				$captcha_success = 2; // fail
			}

		}

		if ($captcha_success != '2') {

			$contact = new mosContact();
			$contact->load($this->con_id);

			$default = mamboCore::get('mosConfig_sitename').' '.T_('Enquiry');
			$email = mosGetParam( $_POST, 'email', '' );
			$text = mosGetParam( $_POST, 'text', '' );
			$name = mosGetParam( $_POST, 'name', '' );
			$subject = mosGetParam( $_POST, 'subject', $default );
			$email_copy = mosGetParam( $_POST, 'email_copy', 0 );

			if (!$email OR !$text OR !$this->is_email($email) OR $this->has_emailheaders($text) OR $this->has_newlines($email) OR $this->has_newlines($name) OR $this->has_newlines($subject) OR !isset($_SERVER['HTTP_USER_AGENT']) OR $_SERVER['REQUEST_METHOD'] != 'POST') {
				echo "<script>alert (\"".T_('Please make sure the form is complete and valid.')."\"); window.history.go(-1);</script>";
				exit(0);
			}
			$prefix = sprintf( T_('This is an enquiry e-mail via %s from:'), mamboCore::get('mosConfig_live_site') );
			$text = $prefix ."\n". $name. ' <'. $email .'>' ."\n\n". $text;

			mosMail( $email, $name , $contact->email_to, mamboCore::get('mosConfig_fromname') .': '. $subject, $text );

			if ( $email_copy ) {
				$copy_text = sprintf( T_('The following is a copy of the message you sent to %s via %s '), $contact->name, mamboCore::get('mosConfig_sitename') );
				$copy_text = $copy_text ."\n\n". $text .'';
				$copy_subject = sprintf(T_('Copy of: %s'),$subject);
				mosMail( mamboCore::get('mosConfig_mailfrom'), mamboCore::get('mosConfig_fromname'), $email, $copy_subject, $copy_text );
			}
			?>
			<script>
			alert( "<?php echo T_('Thank you for your e-mail ').$name; ?>" );
			document.location.href='<?php echo sefRelToAbs( 'index.php?option=com_contact&Itemid='. $this->Itemid ); ?>';
			</script>
			<?php
		} else {
			echo "<SCRIPT> alert('Incorrect Security Code'); window.history.go(-1);</SCRIPT>";
		}
	}

	/**
	* Check field contains an email address:
	* Returns false if text is not an email address
	*/
	function is_email($email){
		return preg_match("/^[A-Z0-9._%-]+@[A-Z0-9.-]+.[A-Z]{2,4}$/i", $email );
	}

	/**
	* Check single-line inputs:
	* Returns true if text contains newline character
	*/
	function has_newlines($text) {
	   return preg_match("/(%0A|%0D|\n+|\r+)/i", $text);
	}

	/**
	* Check multi-line inputs:
	* Returns true if text contains newline followed by
	* email-header specific string
	*/
	function has_emailheaders($text) {
	   return preg_match("/(%0A|%0D|\n+|\r+)(content-type:|to:|cc:|bcc:)/i", $text);
	}

}

class contact_lister_Controller extends contactUserControllers {

	function lister () {
		$categories = &mosContact::getCategories($this->user);
		$count = count($categories);
		if ($count == 0 OR ($count == 1 AND $categories[0]->numlinks == 1)) {
			// No or one category that qualifies
			$this->contact_id = $count == 1 ? $categories[0]->minimum : 0;
			$controller = new contact_view_Controller ($this->manager);
			$controller->view();
			if ($this->op == 'sendmail') $this->sendmail();
			return;
		}
		$rows = array();
		$currentcat =& new stdClass();
		// Parameters
		$menuhandler = mosMenuHandler::getInstance();
		$menu =& $menuhandler->getMenuByID($this->Itemid);
		$params =& $this->makeParams ($menu->params, $menu->name);
		// page header
		$currentcat->header = $params->get( 'header' );
		// Path to images
		$path = mamboCore::get('mosConfig_live_site').'/images/stories/';
		$currentcat->descrip = '';
		$currentcat->img = '';
		if ( $this->catid ) {
			$params->set( 'type', 'category' );
			// url links info for category
			$rows = mosContact::getContacts($this->catid, $this->user);
			// current category info
			foreach ($categories as $category) {
				if ($category->id == $this->catid) {
					$currentcat =& $category;
					// show description
					$currentcat->descrip = $currentcat->description;
					// page image
					$currentcat->img = $path . $currentcat->image;
					$currentcat->align = $currentcat->image_position;
					// page header
					if ( @$currentcat->name <> '' ) $currentcat->header .= ' - '.$currentcat->name;
					break;
				}
			}
		}
		else {
			$params->set( 'type', 'section' );
			// show description
			if ( $params->get( 'description' ) ) $currentcat->descrip = $params->get( 'description_text' );
			// page image
			if ( $params->get( 'image' ) <> -1 ) {
				$currentcat->img = $path . $params->get( 'image' );
				$currentcat->align = $params->get( 'image_align' );
			}
		}
		// used to show table rows in alternating colours
		$tabclass = array( 'sectiontableentry1', 'sectiontableentry2' );
		HTML_contact::displaylist( $categories, $rows, $this->catid, $currentcat, $params, $tabclass );
		if ($this->op == 'sendmail') $this->sendmail();
	}

	function &makeParams ($rawparams, $name) {
		$params =& new mosParameters( $rawparams );
		$params->def( 'page_title', 1 );
		$params->def( 'header', $name );
		$params->def( 'pageclass_sfx', '' );
		$params->def( 'headings', 1 );
		$params->def( 'back_button', mamboCore::get('mosConfig_back_button') );
		$params->def( 'description_text', T_('The Contact list for this Website.') );
		$params->def( 'image', -1 );
		$params->def( 'image_align', 'right' );
		$params->def( 'other_cat_section', 1 );
		// Category List Display control
		$params->def( 'other_cat', 1 );
		$params->def( 'cat_description', 1 );
		$params->def( 'cat_items', 1 );
		// Table Display control
		$params->def( 'headings', 1 );
		$params->def( 'position', '1' );
		$params->def( 'email', '0' );
		$params->def( 'phone', '1' );
		$params->def( 'fax', '1' );
		$params->def( 'telephone', '1' );
		return $params;
	}

}

class contact_view_Controller extends contactUserControllers {

	function view () {
		$database = mamboDatabase::getInstance();
		$query = "SELECT a.*, a.id AS value, CONCAT_WS( ' - ', a.name, a.con_position ) AS text"
		. "\n FROM #__contact_details AS a"
		. "\n LEFT JOIN #__categories AS cc ON cc.id = a.catid"
		. "\n WHERE a.published = '1'"
		. "\n AND cc.published = '1'"
		. "\n AND a.access <=". $this->user->gid
		. "\n AND cc.access <=". $this->user->gid
		. "\n ORDER BY a.default_con DESC, a.ordering ASC"
		;
		$database->setQuery( $query );
		$list = $database->loadObjectList();
		$count = count($list);
		if ($count == 0) {
			$params =& new mosParameters('');
			$params->def( 'back_button', mamboCore::get( 'mosConfig_back_button' ) );
			HTML_contact::nocontact( $params );
			return;
		}
		if ( $this->contact_id == 0 ) $this->contact_id = $list[0]->id;
		foreach ($list as $cont) {
			if ($cont->id == $this->contact_id) {
				$contact =& $cont;
				break;
			}
		}
		if (!isset($contact)) {
			echo T_('You are not authorized to view this resource.');
			return;
		}
		// creates dropdown select list
		$contact->select = mosHTML::selectList( $list, 'contact_id', 'class="inputbox" onchange="ViewCrossReference(this);"', 'value', 'text', $this->contact_id );
		// Adds parameter handling
		$params =& $this->makeParams ( $contact->params );		
				
		// load mambot params info
		$query = "SELECT id FROM #__mambots WHERE element = 'mosemailcloak' AND folder = 'content'";
		$database->setQuery( $query );
		$id 	= $database->loadResult();
		$mambot = new mosMambot( $database );
		$mambot->load( $id );
		$params2 =& $this->makeParams ( $mambot->params );
		
		if ( $contact->email_to AND $params->get( 'email' )) {
			// email cloacking
			$contact->email = mosHTML::emailCloaking( $contact->email_to, $params2->get( 'mode' ) );
		}
		// loads current template for the pop-up window
		$pop = mosGetParam( $_REQUEST, 'pop', 0 );
		if ( $pop ) {
			$params->set( 'popup', 1 );
			$params->set( 'back_button', 0 );
		}
		if ( $params->get( 'email_description' ) ) $params->set( 'email_description', $params->get( 'email_description_text' ) );
		else $params->set( 'email_description', '' );

		// needed to control the display of the Address marker
		$temp = $params->get( 'street_address' )
		. $params->get( 'suburb' )
		. $params->get( 'state' )
		. $params->get( 'country' )
		. $params->get( 'postcode' )
		;
		$params->set( 'address_check', $temp );

		// determines whether to use Text, Images or nothing to highlight the different info groups
		$this->groupMarking($params);
		// params from menu item
		$menuhandler = mosMenuHandler::getInstance();
		$menu =& $menuhandler->getMenuByID($this->Itemid);
		$menu_params =& new mosParameters( $menu->params );

		$menu_params->def( 'page_title', 1 );
		$menu_params->def( 'header', $menu->name );
		$menu_params->def( 'pageclass_sfx', '' );

		HTML_contact::viewcontact( $contact, $params, $count, $list, $menu_params );
	}

	function &makeParams ($rawparams) {
		$params =& new mosParameters( $rawparams );
		$params->set( 'page_title', 0 );
		$params->def( 'pageclass_sfx', '' );
		$params->def( 'back_button', mamboCore::get( 'mosConfig_back_button' ) );
		$params->def( 'print', !mamboCore::get( 'mosConfig_hidePrint' ) );
		$params->def( 'name', '1' );
		$params->def( 'email', '0' );
		$params->def( 'street_address', '1' );
		$params->def( 'suburb', '1' );
		$params->def( 'state', '1' );
		$params->def( 'country', '1' );
		$params->def( 'postcode', '1' );
		$params->def( 'telephone', '1' );
		$params->def( 'fax', '1' );
		$params->def( 'misc', '1' );
		$params->def( 'image', '1' );
		$params->def( 'email_description', '1' );
		$params->def( 'email_description_text', T_('Send an Email to this Contact:') );
		$params->def( 'email_form', '1' );
		$params->def( 'email_copy', '1' );
		// global pront|pdf|email
		$params->def( 'icons', mamboCore::get( 'mosConfig_icons' ) );
		// contact only icons
		$params->def( 'contact_icons', 0 );
		$params->def( 'icon_address', '' );
		$params->def( 'icon_email', '' );
		$params->def( 'icon_telephone', '' );
		$params->def( 'icon_fax', '' );
		$params->def( 'icon_misc', '' );
		$params->def( 'drop_down', '0' );
		$params->def( 'vcard', '1' );
		return $params;
	}

	function groupMarking (&$params) {
		switch ( $params->get( 'contact_icons' ) ) {
			case 1:
			// text
				$params->set( 'marker_address', T_('Address: ') );
				$params->set( 'marker_email', T_('Email: ') );
				$params->set( 'marker_telephone', T_('Telephone: ') );
				$params->set( 'marker_fax', T_('Fax: ') );
				$params->set( 'marker_misc', T_('Information: ') );
				$params->set( 'column_width', '100px' );
				break;
			case 2:
			// none
				$params->set( 'marker_address', '' );
				$params->set( 'marker_email', '' );
				$params->set( 'marker_telephone', '' );
				$params->set( 'marker_fax', '' );
				$params->set( 'marker_misc', '' );
				$params->set( 'column_width', '0px' );
				break;
			default:
			// icons
				$mainframe = mosMainFrame::getInstance();
				$image1 = $mainframe->ImageCheck( 'con_address.png', '/images/M_images/', $params->get( 'icon_address' ) );
				$image2 = $mainframe->ImageCheck( 'emailButton.png', '/images/M_images/', $params->get( 'icon_email' ) );
				$image3 = $mainframe->ImageCheck( 'con_tel.png', '/images/M_images/', $params->get( 'icon_telephone' ) );
				$image4 = $mainframe->ImageCheck( 'con_fax.png', '/images/M_images/', $params->get( 'icon_fax' ) );
				$image5 = $mainframe->ImageCheck( 'con_info.png', '/images/M_images/', $params->get( 'icon_misc' ) );
				$params->set( 'marker_address', $image1 );
				$params->set( 'marker_email', $image2 );
				$params->set( 'marker_telephone', $image3 );
				$params->set( 'marker_fax', $image4 );
				$params->set( 'marker_misc', $image5 );
				$params->set( 'column_width', '40px' );
				break;
		}
	}

}

class contact_vcard_Controller extends contactUserControllers {

	function vcard () {
		$contact = new mosContact();
		$contact->load($this->contact_id);
		$params = new mosParameters($contact->params);
		if (!$params->get('vcard')) {
			echo "<script>alert (\"".T_('There are no vCards available for download.')."\"); window.history.go(-1);</script>";
			exit(0);
		}
		$name = explode(' ', $contact->name);
		$firstname = $name[0];
		unset($name[0]);
		$last = count($name);
		if (isset($name[$last])) {
			$surname = $name[$last];
			unset($name[$last]);
		}
		else $surname = '';
		$middlename = trim (implode(' ', $name));

		$v 	= new MambovCard();
		$v->setPhoneNumber( $contact->telephone, 'PREF;WORK;VOICE' );
		$v->setPhoneNumber( $contact->fax, 'WORK;FAX' );
		$v->setName( $surname, $firstname, $middlename, '' );
		$v->setAddress( '', '', $contact->address, $contact->suburb, $contact->state, $contact->postcode, $contact->country, 'WORK;POSTAL' );
		$v->setEmail( $contact->email_to );
		$v->setNote( $contact->misc );
		$v->setURL( mamboCore::get('mosConfig_live_site'), 'WORK' );
		$v->setTitle( $contact->con_position );
		$v->setOrg( mamboCore::get('mosConfig_sitename') );

		$filename	= str_replace( ' ', '_', $contact->name );
		$v->setFilename( $filename );

		$output 	= $v->getVCard( mamboCore::get('mosConfig_sitename') );
		$filename = $v->getFileName();

		// header info for page
		header( 'Content-Disposition: attachment; filename='. $filename );
		header( 'Content-Length: '. strlen( $output ) );
		header( 'Connection: close' );
		header( 'Content-Type: text/x-vCard; name='. $filename );

		print $output;
		//mosRedirect('index.php');
	}

}

$alternatives = array ();
$admin =& new mosComponentUserManager ('contact', 'task', $alternatives, 'lister', T_('Contact Us'), $version);

?>
