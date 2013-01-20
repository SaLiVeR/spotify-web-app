<?php

/**
 * @author MetalMichael
 * @copyright 2012
 */

require('config.php');

//TODO: Set authentication key
if(!isset($_GET['action'])) invalid();

switch($_GET['action']) {
    case 'status':
        $DB->query("SELECT running FROM site_config");
        list($status) = $DB->next_record();
        echo ($status) ? 'running' : 'false';
        break;
    case 'getsong':
        $DB->query("SELECT 
                        vl.trackid,
                        SUM(IF(v.updown, 1, -1)) as Score
                    FROM voting_list AS vl
                    LEFT JOIN votes AS v
                        ON vl.trackid = v.trackid
                    GROUP BY vl.trackid
                    ORDER BY Score DESC,
                    vl.addedDate ASC
                    LIMIT 1");
        if(!$DB->record_count()) die('empty');
        
        list($ID) = $DB->next_record(MYSQLI_NUM);
        echo $ID;
    
        //$DB->query("DELETE FROM votes WHERE trackid = '" . $ID . "'");
        //$DB->query("DELETE FROM track_info WHERE trackid = '" . $ID . "'");
        //$DB->query("DELETE FROM voting_list WHERE trackid = '" . $ID . "'");
    
        break;
    case 'update':
        if(!isset($_GET['songid']) || !isset($_GET['position'])
            || !validID($_GET['songid']) || !is_number($_GET['position'])) invalid();
        
        $DB->update("UPDATE site_config SET currentTrackID = '" . $_GET['songid'] . "', currentTrackPosition = " . $_GET['position']);
        
        break;
    default:
        invalid();
} 
?>