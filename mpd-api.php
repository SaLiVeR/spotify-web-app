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
        if(!isset($_GET['search']) | empty($_GET['search'])) invalid();
        
        $Libraries = (isset($_GET['libraries']) && !empty($_GET['libraries'])) ? $_GET['libraries'] : 'any';
        
        $Results = $MPD->Find($Libraries, $_GET['search']);
        echo json_encode($Results);
        
        break;
    case 'playerinfo':
        $Return = array();
        $Return['position'] = $MPD->current_track_position;
        $Return['track'] = $MPD->current_track_id;
        
        $NowPlaying = $Cache->get('nowplaying');
        if(!$NowPlaying) {
            $DB->query("SELECT Title, Artist, Album FROM track_info WHERE trackid = " . db_string($MPD->current_track_id));
        }

        echo json_encode($Return);

        break;
    default:
        invalid();
}
?>