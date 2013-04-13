<?php

/**
 * @author MetalMichael
 * @copyright 2012
 */
 
//Config
require('config.php');




?>

<!DOCTYPE HTML>
<head>
	<meta http-equiv="content-type" content="text/html" />
	<meta name="author" content="MetalMichael" />

    <link rel="stylesheet" href="css/player.css" />
    <script type="text/javascript" src="js/player.js"></script>
</head>
<body>

<div id="player-container">
    <div id="player-top">
        <div id="pause"><a href="#" id="pause-button"></a></div>
        <div id="info-left">
            <div id="artist">No Track Loaded</div>
            <div id="song"></div>
        </div>
        <div id="info-right">
            <div id="vote-container"><div id="votes"></div><br />Votes</div>
            <div id="user-info"><div id="avatar"><img /></div>User</div>
        </div>
    </div>
    <div id="player-bottom">
        <div id="bar-container">
            <div id="current-position"></div>
            <div id="current-time">0:00</div>
            <div id="current-time-seconds"></div>
            <div id="bar"></div>
            <div id="end-time">0:00</div>
            <div id="end-time-seconds"></div>
        </div>
        <div id="logo"><img /></div>
    </div>
</div>

</body>