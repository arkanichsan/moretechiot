<?php
require_once("include.php");
$login = session_check();

if($_GET["act"] == "logout"){
	logout();

	header("Location: ./");
	exit;
}
else if($_GET["act"] == "change_language"){
	setcookie("language", $_GET["language"], time() + 60 * 60 * 24 * 365);
	setcookie("temp_username", $_POST["username"], time() + 60);
	setcookie("temp_password", $_POST["password"], time() + 60);

	header("Location: ./");
	exit;
}
else if($_GET["act"] == "reset_password"){
	logout();
}

// Load language file
$language_file_map = array(
	"zh" => "tw",
	"zh-tw" => "tw",
	"zh-hk" => "tw",
	"zh-sg" => "tw",
	"zh-cn" => "cn",
	"en" => "en"
);
$language = prefered_language(array_keys($language_file_map));
$language = isset($language) ? $language : "en";// No user prefered language, use en to show
$language = $language_file_map[$language];

if(isset($_COOKIE["language"])){
	$language = $_COOKIE["language"];
}

require_once("lang." . $language . ".php");

$input_username = $_COOKIE['temp_username'];
$input_password = $_COOKIE['temp_password'];
setcookie("language", $language, time() + 60 * 60 * 24 * 365);
setcookie("temp_username", "", time() - 3600);
setcookie("temp_password", "", time() - 3600);

if($login == false){
	if(isset($_GET["token"])){
		require_once("error.php");
	}
	else{
		require_once("login.php");
	}
}
else{
	require_once("manager.php");
}
?>
