 <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<?php
	if(isset($_COOKIE['musUser'])){
		header("Location: ../");
	}
?>
<html>
<head>
	<title>Music Player Login</title>
	<link rel="stylesheet" href="style.css" type="text/css"></link>
	<link rel="icon" href="/portfoliostuff/images/favicon.ico" type="image/x-icon"></link>
	<link rel="shortcut icon" href="/portfoliostuff/images/favicon.ico" type="image/x-icon"></link>
</head>
<body>
<div class="panel loginpanel">
	<h3>Music Player Login</h3>
	<form method="POST" action="login.php">
	<div class="panelbuttons">	
		<div class="inputblock userblock"><input type="text" name="username" /></div>
		<div class="inputblock passwordblock"><input type="password" name="password" /></div>
		<a href="#"><p class="panelforgotten">Forgotten password?</p></a>
	</div>
	<div class="panelbottom">
		<a href="register.html"><p class="panelregister">Not yet registered?</p></a>
		<input class="active" type="submit" value="Login" />
	</div>
	</form>
</div>
</body>
</html>