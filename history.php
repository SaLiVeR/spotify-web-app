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

showHeader(array('search'=>false), 'history.js');
/**********************************/
?>
<h3>
    <a href="changeTable('recent')">Recent Songs</a>
    <a href="changeTable('popular')">Most Popular Songs</a>
    <a href="changeTable('popartist')">Most Popular Arists</a>
    <a href="changeTable('popuser')">Users with most votes</a>
</h3>



<?php
/**********************************/
showFooter();
?>