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
        if(!$Return = $Cache->get('current_song_info')) {
    
            $Return = array();
            $Return['position'] = $MPD->current_track_position;
            $Return['servertime'] = time();
            
            $Return['length'] = $MPD->current_track_length;
            $Return['track'] = $MPD->current_track_title;
            $Return['artist'] = $MPD->current_track_artist;
            $Return['album'] = $MPD->current_track_album;
            $Return['year'] = $MPD->current_track_year;
            $Return['file'] = $MPD->current_track_file;
            
            
            $DB->query("SELECT 
                            vl.addedBy AS UserID,
                            u.Username,
                            u.Avatar,
                            SUM(IF(v.updown = 1), 1, -1) AS Votes 
                        FROM voting_list AS vl
                        LEFT JOIN votes AS vl 
                            ON vl.trackid = v.trackid
                        JOIN users AS u
                            ON u.ID = vl.addedBy 
                        WHERE vl.trackid = '" . db_string($Return['file']) . "'");
            if($DB->record_count() == 0) {
                invalid();
            }
            $Info = $DB->next_record(MYSQLI_ASSOC);
            
            $Return['votes'] = $Info['Votes'];
            $Return['avatar'] = $Info['Avatar'];
            $Return['username'] = $Info['Username'];
            
            $Cache->set('current_song_info', $Return);
            
        } else {
            //Update the time
            $Return['position'] += time() - $Return['servertime'];
        }
        echo json_encode($Return);

        break;
    default:
        invalid();
}
?>