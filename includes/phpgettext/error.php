<?php
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

function dump($var) {
    $trace = debug_backtrace();
    echo "<div style=\"text-align:left\">dump() in {$trace[0]['file']}:{$trace[0]['line']}</div>";
    //include_once 'Var_Dump.php';
    if (class_exists('Var_Dump')) {
        Var_Dump::displayInit(array('display_mode'=>'HTML4_Table'));
        Var_Dump::display($var);
    } else {
        echo "<pre style=\"text-align:left\">";
        var_dump($var);
        echo "</pre>";
    }
}


?>