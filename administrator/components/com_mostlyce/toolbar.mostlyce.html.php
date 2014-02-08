<?php
/**
* @package MOStlyCE Admin
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
* @package Mambo_4.6
*/
class TOOLBAR_mosceConfig {
        function _CONFIG() {
                mosMenuBar::startTable();
                mosMenuBar::save();
                mosMenuBar::cancel();
                mosMenuBar::endTable();
        }
}
?>
