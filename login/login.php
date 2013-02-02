<?php
require_once('config.php');
$DB = new MYSQL("mysql15.000webhost.com","a6560148_Elphy","1273fallen","a6560148_Data");
$result = $DB->query("SELECT Username FROM users WHERE Username='".$_POST['username']."' AND pssword='".md5($_POST['username']).md5($_POST['password'])."'");
if($result->num_rows==1){
	$user = $result->fetch_object();
	setcookie("musUser", $user->Username, 0, '/','elphy.freeiz.com',false,true);
	header("Location: ../");
	exit;
} else {
	header("Location: Error.html");
}
?>