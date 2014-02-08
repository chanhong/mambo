<?php
/**
 * General Authorization script for MOStlyCE plugins (originally written for mosCE).
 * @author $Author: Ryan Demmer (Changed for MOStlyCE by Chad Auld)
 * @version $Id: auth_plugin.php
 * @package MOStlyCE
 * @Portions from remository.php
 */

// Don't allow direct linking
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
class authPlugin
{
    function authPlugin()
    {
    	$my = mamboCore::get('currentUser');
        $this->usertype = $my->usertype;
        $this->username = $my->username;
        $this->grp = $my->gid;
        $this->mainframe = mosMainFrame::getInstance();
    }
    function getUserName()
    {
        return $this->username;
    }
    function getUserType()
    {
        return $this->usertype;
    }
    function isAdmin()
    {
        return ( strtolower( $this->usertype ) == 'superadministrator' || strtolower( $this->usertype ) == 'super administrator' || $this->grp == 16 ) ? true : false;
    }
}

?>
