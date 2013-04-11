<?php

/**
 * @author MetalMichael
 * @copyright 2012
 */

//Config
require('config.php');
//Header
require(RESOURCE_DIR . 'header.php');
//Footer
require(RESOURCE_DIR . 'footer.php');

$User->enforceLogin();

showHeader('', array('search'=>true, 'navigation'=>true, 'login'=>false), 'radio.js');

//*********************************************
?>
<script type="text/javascript" src="js/jquery.fileupload-ui.js"></script>

<div id="table-container" class="box">
<?php
//Try and load the table. Cos we're nice. Hacky ajax FTW
$_GET['action'] = 'table';
include(dirname(__file__) . '/ajax.php');
?>
</div>

<iframe width="" height="" src="player.php">
</iframe>
<?php
//*********************************************

showFooter();

?>