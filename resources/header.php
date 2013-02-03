<?php

/**
 * @author MetalMichael
 * @copyright 2012
 */
function showHeader($PageTitle='', $Options=array('search'=>true, 'navigation'=>true), $JSIncludes=array()) {
    
    header('Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0');
    header('Pragma: no-cache');
    
    if(empty($PageTitle)) {
        $PageTitle = "LSUCS Radio";
    } else {
        $PageTitle .= " :: LSUCS Radio";
    }

?>
<!DOCTYPE HTML>
<head>
	<meta http-equiv="content-type" content="text/html" />
	<meta name="author" content="MetalMichael" />

	<title><?=$PageTitle?></title>
    <link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css' />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="css/buttons.css" rel="stylesheet" type="text/css" />    
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/global.js"></script>
<?php
    if($Options['search']) {
?>
    <script type="text/javascript" src="js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.9.1.custom.min.js"></script>
    <script type="text/javascript" src="js/boxshadow-hooks.js"></script>
<?php
    }
    if(!empty($JSIncludes)) {
        $JSIncludes = explode(',', $JSIncludes);
        foreach($JSIncludes as $JS) {
?>
    <script type="text/javascript" src="js/<?=$JS?>"></script>
<?php
        }
    }
?>
    
</head>
<body id="<?=$_SERVER['PHP_SELF']?>" class="
    <?php
        //Insert blame for Matt here
        $ar = explode('/', $_SERVER['PHP_SELF']);
        echo substr($ar[count($ar)-1], 0, -4);
    ?>
    ">
    <div id="header">
<?php
    if($Options['navigation']) {
?>       
        <h2><a href="index.php" onclick="changeNav('index.php')">Title</a></h2>
        <div id="navigation">
            <ul>
                <a href="index.php" onclick="changeNav('index.php')" id="nav-index"><li class="button indexBtn">Voting</li></a>
                <a href="history.php" onclick="changeNav('history.php')" id="nav-history"><li class="button historyBtn">History</li></a>
            </ul>
        </div>
<?php
    }
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