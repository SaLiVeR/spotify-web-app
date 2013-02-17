<?php

/**
 * @author MetalMichael
 * @copyright 2012
 */
function showHeader($PageTitle='', $Options=array('search'=>true, 'navigation'=>true, 'login'=>false), $JSIncludes=array()) {
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
<?php
    if($Options['login']) {
?>
    <link href="css/login.css" rel="stylesheet" type="text/css" />
<?php
    } else {
?>
    <link href="css/style.css" rel="stylesheet" type="text/css" />
<?php
    }
?>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.9.1.custom.min.js"></script>
    <script type="text/javascript" src="js/global.js"></script>
<?php
    if($Options['search']) {
?>
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td class="preview"><span class="fade"></span></td>
        <td class="name"><span>{%=file.name%}</span></td>
        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
        {% if (file.error) { %}
            <td class="error" colspan="2"><span class="label label-important">Error</span> {%=file.error%}</td>
        {% } else if (o.files.valid && !i) { %}
            <td>
                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%;"></div></div>
            </td>
            <td class="start">{% if (!o.options.autoUpload) { %}
                <button class="btn btn-primary">
                    <i class="icon-upload icon-white"></i>
                    <span>Start</span>
                </button>
            {% } %}</td>
        {% } else { %}
            <td colspan="2"></td>
        {% } %}
        <td class="cancel">{% if (!i) { %}
            <button class="btn btn-warning">
                <i class="icon-ban-circle icon-white"></i>
                <span>Cancel</span>
            </button>
        {% } %}</td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        {% if (file.error) { %}
            <td></td>
            <td class="name"><span>{%=file.name%}</span></td>
            <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
            <td class="error" colspan="2"><span class="label label-important">Error</span> {%=file.error%}</td>
        {% } else { %}
            <td class="preview">{% if (file.thumbnail_url) { %}
                <a href="{%=file.url%}" title="{%=file.name%}" data-gallery="gallery" download="{%=file.name%}"><img src="{%=file.thumbnail_url%}"></a>
            {% } %}</td>
            <td class="name">
                <a href="{%=file.url%}" title="{%=file.name%}" data-gallery="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.name%}</a>
            </td>
            <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
            <td colspan="2"></td>
        {% } %}
        <td class="delete">
            <button class="btn btn-danger" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}"{% if (file.delete_with_credentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                <i class="icon-trash icon-white"></i>
                <span>Delete</span>
            </button>
            <input type="checkbox" name="delete" value="1">
        </td>
    </tr>
{% } %}
</script>
    <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/boxshadow-hooks.js"></script>
    <script type="text/javascript" src="js/jquery.fileupload"></script>
    <script type="text/javascript" src="js/jquery.fileupload-fp.js"></script>
    <script type="text/javascript" src="js/jquery.fileupload-jui.js"></script>
    <script type="text/javascript" src="js/tmpl.min.js"></script>
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
    <span class="hidden"><?=$User->ID?></span>
<?php
        //DropZone effects (the body is actually the dropzone, this just comes on top
        if($Options['search']) {
?>
    <div id="dropcanvas">
        <div id="dropzone">
            <h3>Drop Audio File(s) To Upload</h3>
            <h4>Supported formats: MP3, M4A, WAV, FLAC, OGG</h4>
            <p>Once uploaded the file(s) will be checked manually, before being approved to be added to the local library. Upload progress can be monitored in the bottom right corner.<br />
            Max File Size: <?=formatBytes(MAX_UPLOAD_SIZE)?></p>
        </div>
        <form id="fileupload" action="//jquery-file-upload.appspot.com/" method="POST" enctype="multipart/form-data">
        <div class="row fileupload-buttonbar">
            <div class="span5 fileupload-progress fade">
                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="bar" style="width:0%;"></div>
                </div>
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
        <div class="fileupload-loading"></div>
        <br />
        <table role="presentation" class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>
    </form>
    </div>
<?php
        }
?>
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
            <div id="library-buttons">
                <ul>
                    <li>
                        <label for="library-spotify">
                            <img src="img/spotify-icon.png" alt="Spotify Icon" title="Spotify" class="library-icon" />
                        </label>
                        <input type="checkbox" class="library-button" id="library-spotify" />
                    </li>
                    <li>
                        <label for="library-youtube">
                            <img src="img/youtube-icon.png" alt="Youtube Icon" title="Youtube" class="library-icon" />
                        </label>
                        <input type="checkbox" class="library-button" id="library-youtube" />
                    </li>
                    <li>
                        <label for="library-local">
                            <img src="img/folder-icon.png" alt="Local Icon" title="Local" class="library-icon" />
                        </label>
                        <input type="checkbox" class="library-button" id="library-local" />
                    </li>
                </ul>
            </div>
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