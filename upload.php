<?php
//Config
require('config.php');

require(RESOURCE_DIR . 'class_upload.php');

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
        
        print_r($UploadInfo);
        
        
        break;
    case 'DELETE':
        $Upload->delete();
        break;
    default:
        $Upload->header('HTTP/1.1 405 Method Not Allowed');
}

?>