<?php

/**
 * @author MetalMichael
 * @copyright 2012
 */
function showHeader($Options=array('search'=>true), $JSIncludes=array()) {
    
    header('Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0');
    header('Pragma: no-cache');

?>
<!DOCTYPE HTML>
<head>
	<meta http-equiv="content-type" content="text/html" />
	<meta name="author" content="MetalMichael" />

	<title>Spotify Player</title>
    <link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
    <link href="<?=RESOURCE_DIR?>style.css" rel="stylesheet" type="text/css" />
    
    <script type="text/javascript" src="<?=RESOURCE_DIR?>jquery.min.js"></script>
    <script type="text/javascript" src="<?=RESOURCE_DIR?>global.js"></script>
    <script type="text/javascript" src="<?=RESOURCE_DIR?>jquery.dataTables.js"></script>
    <script type="text/javascript" src="<?=RESOURCE_DIR?>jquery-ui-1.9.1.custom.min.js"></script>
    <script type="text/javascript" src="<?=RESOURCE_DIR?>boxshadow-hooks.js"></script>
<?php
    if(!empty($JSIncludes)) {
        $JSIncludes = explode(',', $JSIncludes);
        foreach($JSIncludes as $JS) {
?>
    <script type="text/javascript" src="<?=RESOURCE_DIR . $JS?>"></script>
<?php
        }
    }
?>
    
</head>
<body id="<?=$_SERVER['PHP_SELF']?>">
    <div id="header">       
        <h2><a href="index.php" onclick="changeNav('index.php')">Title</a></h2>
        <div id="navigation">
            <ul>
                <a href="index.php" onclick="changeNav('index.php')" id="nav-index"><li>Voting</li></a>
                <a href="history.php" onclick="changeNav('history.php')" id="nav-history"><li>History</li></a>
            </ul>
        </div>
<?php
    if($Options['search']) {
?>        
        <form id="searchbox" method="get" onsubmit="updateSearch(); return false;">
            <span class="label">Search: </span><input type="text" id="searchinput" onfocus="updateSearch();" />
        </form>
        <div id="search">
            <div id="search-results"></div>
        </div>
<?php
    }
?>
    </div>  
    <div id="content">
<?php
}
?>