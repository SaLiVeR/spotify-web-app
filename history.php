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

$User->enforceLogin();
showHeader('Playback History/Stats', array('search'=>false, 'navigation'=>true, 'login'=>false), 'history.js');
/**********************************/
?>
<div id="history-container">
<div class="hbtnncontainer topBtnContainer">
<h3 id="history-navigation">
    <a href="#" class="button topBtn" onclick="changeTable('recent')">Recent Songs</a>
    <a href="#" class="button topBtn" onclick="changeTable('popular')">Most Popular Songs</a>
    <a href="#" class="button topBtn" onclick="changeTable('popartist')">Most Popular Arists</a>
    <a href="#" class="button topBtn" onclick="changeTable('popuser')">Users With Most Votes</a>
</h3>
</div>

<div class="table-container">
<?=$H->createTable('recent')?>
</div>

</div>

<?php
/**********************************/
showFooter();
?>