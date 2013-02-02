<?php
require_once('config.php');
$username = $_POST['username'];
$salt = md5($username);
$password = $salt.md5($_POST['password']);
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
if($!email){
	$DB = new MYSQL("mysql15.000webhost.com","a6560148_Elphy","1273fallen","a6560148_Data");
	$DB->query("INSERT INTO users VALUES ('','".$username."', '".$password."', '".$email."')");
	$result = $DB->query("SELECT Username FROM `users` WHERE Username='".$username."'");
	if($result->num_rows==1){
		$user = $result->fetch_object();
		setcookie("musUser", $user->Username, 0, '/','elphy.freeiz.com',false,true);
		header("Location: ../");
		exit;
	} else {
		header("Location: Error.html");
	}
} else {
	header("Location: Error.html");
}
?>