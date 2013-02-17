<?php

/**
 * @author MetalMichael
 * @copyright 2012
 */

function showErrors($E) {
    $ErrMessage = '';
    foreach($E as $err) {
        $ErrMessage .= (empty($ErrMessage) ? "\n" : '') . $err;
    }
    die($ErrMessage);
}



//Config
require('config.php');
//Header
require(RESOURCE_DIR . 'header.php');
//Footer
require(RESOURCE_DIR . 'footer.php');

//Validation
require(RESOURCE_DIR . 'class_validate.php');
if(isset($_POST['register'])) {
    //Validate
    $V = new Validate($_POST, DEBUG_MODE);
    $V->val('username', 'username', true, 'Invalid Username');
    $V->val('password', 'string', true, 'Invalid Password', array('maxlength' => 40, 'minlength' => 6));
    $V->val('email', 'email', true, 'Invalid Email');
    $E = $V->getErrors();
    if(!empty($E)) showErrors($E);
    
    //Check username and email are free
    $DB->query("SELECT Username, Email FROM users WHERE Username = '" . db_string($_POST['username']) . "' OR Email = '" . db_string($_POST['email']) . "'");
    if($DB->record_count() > 0) {
        $Info = $DB->next_record(MYSQLI_ASSOC);
        if($_POST['email'] == $Info['Email']) $E[] = 'Email already in use';
        if($_POST['username'] == $Info['Username']) $E[] = 'Username already in use';
        showErrors($E);
    }
    
    $Username = $_POST['username'];
    $Email = $_POST['email'];
    
    //Create User
    $Secret = $Enc->random_hash();
    $PassHash = crypt($_POST['password'], $Enc->combine_hash($Secret, SITE_SALT));
    
	$Inserted = $DB->query("INSERT INTO users (Username, PassHash, Secret, Email, Joined, AuthKey) 
        VALUES ('" . $Username . "', '" . db_string($PassHash) . "', '" . db_string($Secret) . "', '" . $Email . "', '" . sqltime() . "', '" . db_string($Enc->random_hash()) . "')");
	
    //Create Session
    if($Inserted) {
        $UserID = $DB->inserted_id();
        if(!$UserID) error('Account could not be created');
        
        $SessionID = $Enc->random_hash();
        $DB->query("INSERT INTO users_sessions (SessionID, UserID, Date) VALUES ('" . $SessionID . "', '" . $UserID . "', '" . sqltime() . "')");
        
        $Cookie = $Enc->encrypt($SessionID . "|<~>|" . $UserID);
		setcookie("Session", $Cookie, 0, '/', SITE_HOST, false, true);
        $Cache->set('SESSION_' . $SessionID, array('UserID' => $UserID));
        
		header("Location: index.php");
		exit;
	} else {
		error('Account could not be created');
	}
}

showHeader('Register', array('search'=>false,'navigation'=>false, 'login'=>true), 'login.js')

?>
<div class="panel registerpanel">
	<h3>Register</h3>
	<form method="POST" action="">
	<div class="panelbuttons">	
		<div class="inputblock userblock"><input type="text" name="username" /></div>
		<div class="inputblock emailblock"><input type="text" name="email" /></div>
		<div class="inputblock emailcheckblock"><input type="text" name="emailcheck" /></div>
		<div class="inputblock passwordblock"><input class="passwordstrengthcheck" type="password" name="password" /></div>
		<div class="validbar"><div class="validinnerbar"></div></div>
	</div>
	<div class="panelbottom">
		<a href="../Login"><p class="panelregister">Registered already?</p></a>
		<input class="active" type="submit" name="register" value="Register" />
	</div>
	</form>
</div>
<?php

showFooter();

?>