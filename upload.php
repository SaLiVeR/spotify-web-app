<?php
//Config
require('config.php');

require(RESOURCE_DIR . 'class_upload.php');
require(RESOURCE_DIR . 'getid3/getid3.php');

$RequiredInfo = array('title','artist');
$PotentialInfo = array('year', 'album');
$PotentialInfo = array_merge($RequiredInfo, $PotentialInfo);

$getID3 = new getID3;
$Upload = new UPLOAD;

switch ($_SERVER['REQUEST_METHOD']) {
    case 'OPTIONS':
    case 'HEAD':
        $Upload->head();
        break;
    case 'GET':
        $Upload->get(true);
        break;
    case 'PATCH':
    case 'PUT':
    case 'POST':
        $UploadInfo = $Upload->post(false);
        //Error check here        
        foreach($UploadInfo['files'] as $FileKey => $File) {
            $PassedTests = true;
            $CurrentInfo = array();
            $FailedTests = array();
            
            $FileInfo = $getID3->analyze("uploads/" . $File->name);
            $TagInfo = $FileInfo['tags']['id3v2'];
            //Error check here
            foreach($PotentialInfo as $RI) {
                if(!array_key_exists($RI, $TagInfo)) {
                    //If the missing information is "essential"
                    if(array_key_exists($RI, $RequiredInfo)) $PassedTests = false;
                    $CurrentInfo[$RI] = '';
                    $FailedTests[] = $RI;
                } else {
                    $CurrentInfo[$RI] = $TagInfo[$RI][0];
                }
            }
            //Required data exists. Track moves on to admin approval.
            $DB->query("INSERT INTO uploaded_songs (UploaderID, Filename, Title, Artist, Album, Year, Duration, Complete) VALUES (
                '" . $User->ID . "',
                '" . db_string($File->name) . "',
                '" . db_string($CurrentInfo['title']) . "',
                '" . db_string($CurrentInfo['artist']) . "',
                '" . db_string($CurrentInfo['album']) . "',
                '" . db_string($CurrentInfo['year']) . "',
                '" . db_string(round($FileInfo['playtime_seconds'])) . "',
                '" . (($PassedTests) ? 1 : 0) . "'
            )");
            $UploadInfo['files'][$FileKey]->Successful = $PassedTests;
            if(!$PassedTests) {
                $UploadInfo['files'][$FileKey]->Failed = $FailedTests;
            }
        }

        echo json_encode($UploadInfo);

        break;
    case 'DELETE':
        $Upload->delete();
        break;
    default:
        $Upload->header('HTTP/1.1 405 Method Not Allowed');
}

?>