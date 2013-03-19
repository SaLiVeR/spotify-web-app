<?php

/**
 * @author MetalMichael
 * @copyright 2012
 */

require_once('config.php');

//Need a login system
$UserID = 5;

if(!isset($_GET['action']) || empty($_GET['action'])) invalid();

function show_arrow($TrackID, $Direction, $Counter) {
    global $UserVotes, $UserID;
    if(array_key_exists($TrackID, $UserVotes)) {
        if($UserVotes[$TrackID]['updown'] == '1') {
            if($Direction == 'up') {
                $Colour = '-green';
            } else {
                $Colour = '';
            }
        } else {
            if($Direction == 'down') {
                $Colour = '-red';
            } else {
                $Colour = '';
            }
        }
    } else {
        $Colour = '';
    }
?>
<a href="#" onclick="vote(<?=($Direction == 'up') ? 1 : 0?>,'<?=sanitizeID($TrackID)?>', <?=$Counter?>)">
    <button id="button-<?=$Direction?>-<?=sanitizeID($TrackID)?>" class="vote<?=$Direction.$Colour?> votebtn"></button>
</a>
<?php
}


switch($_GET['action']) {
    case 'add':
        //Check everything is well in Smallville
        if(!isset($_GET['track']) || !preg_match('/(spotify:(?:track:[a-zA-Z0-9]+)|http:\/\/gdata.youtube.com\/feeds\/api\/videos\/[A-Z0-9]+)/', $_GET['track'])) invalid();
        $TrackID = $_GET['track'];
        
        $DB->query("SELECT * FROM voting_list WHERE trackid = '" . $TrackID . "'");
        if($DB->record_count()) die('exists');
        
        $DB->query("SELECT * FROM track_info WHERE trackid = '" . $TrackID . "'");
        if(!$DB->record_count()) {
            //Get info on the track and add it to the database
            if(strstr($TrackID,'spotify')) {
                
                /*$ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'http://ws.spotify.com/lookup/1/.json?uri=' . $TrackID);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $data = curl_exec($ch);
                curl_close($ch);
                */
                $data = file_get_contents('http://ws.spotify.com/lookup/1/.json?uri=' . $TrackID);
                
                //Track doesn't exist in Spotify. C'est IMPOSSIBLÉ!!
                if(!$data) invalid();
                
                $data = json_decode($data);
            } else {
                /*$ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'http://gdata.youtube.com/feeds/api/videos?alt=json&q=' . $TrackID);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $data = curl_exec($ch);
                curl_close($ch);
                */
                $data = file_get_contents('http://gdata.youtube.com/feeds/api/videos?alt=json&q=' . $TrackID);
                die($data);
            }
            
            $Track = array(
                'Title' => $data->track->name,
                'Artist' => $data->track->artists[0]->name,
                'Album' => $data->track->album->name,
                'Time' => $data->track->length
            );
            
            //Add info to the track catalogue
            $DB->query("INSERT IGNORE INTO track_info (trackid, Title, Artist, Album, Duration) VALUES(
                '" . $TrackID . "',
                '" . db_string($Track['Title']) . "',
                '" . db_string($Track['Artist']) . "',
                '" . db_string($Track['Album']) . "',
                '" . db_string($Track['Time']) . "')");
        }
        
        //Add it to the voting list
        $DB->query("INSERT INTO voting_list (trackid, addedBy, addedDate) VALUES ('" . $TrackID . "', '" . $UserID . "', '" . sqltime() . "')");
        
        //Add a vote
        $DB->query("INSERT INTO votes (trackid, userid, updown) VALUES ('" . $TrackID . "', '"  . $UserID . "', 1)");
        
        break;
    case 'vote':
        if(!isset($_GET['track']) || !validID($_GET['track'])
            || !isset($_GET['direction']) || !in_array($_GET['direction'], array(0,1))) {
            invalid();
        }
        
        $DB->query("SELECT * FROM voting_list WHERE trackid = '" . $_GET['track'] . "'");
        if(!$DB->record_count()) {
            die('notrack');
        }
        
        $DB->query("SELECT updown FROM votes WHERE trackid = '" . $_GET['track'] . "' AND userid = '" . $UserID . "'");
        if($DB->record_count()) {
            list($vote) = $DB->next_record(MYSQLI_NUM);
            if($vote == $_GET['direction']) die('identical');
            $DB->query("UPDATE votes SET updown = " . $_GET['direction'] . " WHERE trackid = '" . $_GET['track'] . "' AND userid = '" . $UserID . "'");
        } else {
            $DB->query("INSERT INTO votes (trackid, userid, updown) VALUES ('" . $_GET['track'] . "', '" . $UserID . "', " . $_GET['direction'] . ")");
        }
        
        //Find out the new position in the big table.
        $DB->query("SELECT *, @rownum:=@rownum+1 as row_position FROM (
            SELECT
                vl.trackid,
                SUM(IF(v.updown, 1, -1)) AS Score,
                ti.Title,
                ti.Artist,
                ti.Album,
                ti.Duration
            FROM voting_list AS vl
            JOIN track_info AS ti
                ON vl.trackid = ti.trackid
            LEFT JOIN votes AS v
                ON vl.trackid = v.trackid
            GROUP BY vl.trackid
            ORDER BY score DESC, vl.addedDate ASC
            ) user_rank,(SELECT @rownum:=0) r");
        $NewRows = $DB->to_array('trackid', MYSQLI_ASSOC);
        $RowInfo = $NewRows[$_GET['track']];
        //Return the score, as it may have changed in the mean time, and we want to be as accurate as possible and just cos.
        echo $RowInfo['Score'] . '!!' . $RowInfo['row_position'];

        break;
    case 'table':
        //Load the active voting list
        $DB->query("SELECT 
                        vl.trackid,
                        SUM(IF(v.updown, 1, -1)) as Score,
                        ti.Title,
                        ti.Artist,
                        ti.Album,
                        ti.Duration
                    FROM voting_list AS vl
                    JOIN track_info AS ti 
                        ON vl.trackid = ti.trackid 
                    LEFT JOIN votes AS v
                        ON vl.trackid = v.trackid
                    GROUP BY vl.trackid
                    ORDER BY Score DESC, vl.addedDate ASC");
        $VotingTracks = $DB->to_array(false, MYSQL_ASSOC);
        
        //Load the users' votes
        $DB->query("SELECT trackid, updown FROM votes WHERE userid = " . $UserID);
        $UserVotes = $DB->to_array('trackid', MYSQLI_ASSOC);
?>
            <table id="voting-table">
                <thead>
                    <tr>
                        <th class="col0"></th>
                        <th class="col1">Track</th>
                        <th class="col2">Artist/Uploader</th>
                        <th class="col3"></th>
                        <th class="col4">Album</th>
                        <th class="col5"></th>
                    </tr>
                </thead>
                <tbody>
<?php
        $a = 'even';
        $counter = 0;
        if(count($VotingTracks)) {
            foreach($VotingTracks as $VT) {
                $counter++;
                $a = ($a == 'even') ? 'odd' : 'even';
?>
                    <tr id="row-<?=sanitizeID($VT['trackid'])?>" class="<?=$a?>">
                        <td class="col0"><?=$counter?></td>
                        <td class="col1"><?=display_str($VT['Title'])?></td>
                        <td class="col2"><?=display_str($VT['Artist'])?></td>
                        <td class="col3"><?=get_time($VT['Duration'])?></td>
                        <td class="col4"><?=display_str($VT['Album'])?></td>
                        <td class="col5 votebox">
                            <? show_arrow($VT['trackid'], 'down', $counter); ?>
                            <span id="score-<?=sanitizeID($VT['trackid'])?>" class="score"><?=$VT['Score']?></span>
                            <? show_arrow($VT['trackid'], 'up', $counter); ?>
                        </td>
                    </tr>
<?php
            }
        } else {
?>
                    <tr class="<?=$a?>">
                        <td colspan="6">No Current Tracks</td>
                    </tr>
<?php
        }
?>
                </tbody>
            </table>
<?php
        break;
    case 'gethistorytable':
        if(!isset($_GET['table']) || empty($_GET['table'])) invalid();
        
        require(RESOURCE_DIR . 'class_history.php');
        $H = new HISTORY;
        
        echo $H->createTable($_GET['table']);
        
        break;
    default:
        invalid();
}
?>