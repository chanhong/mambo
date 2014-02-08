<?php
/**
* @package TinyMCE-EXP Admin
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once( $mainframe->getPath( 'toolbar_html' ) );

//switch ( $task ) {
        //case 'default':
                TOOLBAR_mosceConfig::_CONFIG();
       // break;
//}
?>
