<?php

/**
 * @author MetalMichael
 * @copyright 2012
 */

require('config.php');

//TODO: Set authentication key
if(!isset($_GET['action'])) invalid();

include(RESOURCE_DIR . 'class_mpd.php');
$MPD = new MPD('localhost',6600);

switch($_GET['action']) {
    case 'search':
        if(!isset($_GET['search'])) invalid();
        
        $Results = $MPD->Find('any', $_GET['search']);
        
        json_encode($Results);
        
        break;
}
?>