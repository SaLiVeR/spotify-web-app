<?php
//Config
require('config.php');
//Header
require(RESOURCE_DIR . 'header.php');
//Footer
require(RESOURCE_DIR . 'footer.php');

if(isset($_POST['login'])) {

    //Validation
    require(RESOURCE_DIR . 'class_validate.php');
    
    $Val = new Validate($_POST, DEBUG_MODE);
    $Val->val('username', 'username', true, 'Invalid Username');
    $Val->val('password', 'string', true, 'Invalid Password', array('maxlength'=>30,'minlength'=>6));
    
    
    $DB->query("SELECT ID, Secret, PassHash FROM users WHERE Username='" . $_POST['username'] . "'");
    if($DB->record_count() < 1) error('Username does not exist');
    
    list($UserID, $Secret, $PassHash) = $DB->next_record(MYSQLI_NUM);
    
    if($Enc->check_password($_POST['password'], $Secret, $PassHash)) {
        //Login Successful
        
        $SessionID = $Enc->random_hash();
        $DB->query("INSERT INTO users_sessions (SessionID, UserID, Date) VALUES ('" . $SessionID . "', '" . $UserID . "', '" . sqltime() . "')");
        
        $Cookie = $Enc->encrypt($SessionID . "|<~>|" . $UserID);
    	setcookie("Session", $Cookie, 0, '/', SITE_HOST, false, true);
        $Cache->set('SESSION_' . $SessionID, array('UserID' => $UserID));
        
    	header("Location: index.php");
    	exit;
    } else error('Incorrect Username or Password');
    
}

showHeader('Login', array('search'=>false, 'navigation'=>false, 'login'=>true));

?>
<div class="panel loginpanel">
	<h3>Music Player Login</h3>
	<form method="POST" action="">
    
    	<div class="panelbuttons">	
    		<div class="inputblock userblock"><input type="text" name="username" /></div>
    		<div class="inputblock passwordblock"><input type="password" name="password" /></div>
    		<a href="#"><p class="panelforgotten">Forgotten password?</p></a>
    	</div>
        
    	<div class="panelbottom">
    		<a href="register.php"><p class="panelregister">Not yet registered?</p></a>
    		<input class="active" type="submit" name="login" value="Login" />
    	</div>
    
	</form>
</div>
<?php

showFooter();

?>