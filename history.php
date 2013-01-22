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


require(RESOURCE_DIR . 'class_history.php');
$H = new HISTORY;

showHeader(array('search'=>false), 'history.js');
/**********************************/
?>
<div id="history-container">
<h3>
    <a href="#" onclick="changeTable('recent')">Recent Songs</a>
    <a href="#" onclick="changeTable('popular')">Most Popular Songs</a>
    <a href="#" onclick="changeTable('popartist')">Most Popular Arists</a>
    <a href="#" onclick="changeTable('popuser')">Users with most votes</a>
</h3>

<?=$H->createTable('recent')?>

</div>

<?php
/**********************************/
showFooter();
?>