<?php
require_once("include.php");
$login = session_check();

// Load language file
if(isset($_COOKIE["language"])){
	$language = $_COOKIE["language"];
}
else{
	$language = "en";
}
require_once("lang." . $language . ".php");

if(function_exists($_GET["act"]) == true){
	$_GET["act"]();
}
else{
	trap("Invalid action name.");
}

exit;

function trap($outline, $detail = null){
	header("HTTP/1.0 500 Internal Server Error");
	echo $outline;
	if($detail != null){
		echo "\n" . $detail;
	}

	exit;
}

function get_module_list(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	// Request the runtime to download the rule to device
	set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
	    // error was suppressed with the @-operator
	    if (0 === error_reporting()) {
	        return false;
	    }

	    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	});

	try{
		$port = 1230;
		$address = "127.0.0.1";

		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		socket_connect($socket, $address, $port);

		$in = '<cmd type="userDevice" account_uid="' . $_SESSION['account_uid'] . '" username="' . $_SESSION['username'] . '" />';
		socket_write($socket, $in);
		socket_shutdown($socket, 1);

		$out = "";
		while($buffer = socket_read($socket, 2048)){
			$out .= $buffer;
		}

		socket_close($socket);
	}
	catch(ErrorException $e){}

	restore_error_handler();
	// Notify END

	header('Content-type: text/xml');
	echo $out;
}

function get_rule_list(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rules/>');

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . DB_NAME : (
			DB_IS_MYSQL  ? "USE " . DB_NAME : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
		$sth->execute();

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "SELECT UID, DateTime, DATALENGTH(Content) AS Size FROM [rule] WHERE DeviceUID = ? AND AccountUID = ? ORDER BY DateTime DESC" : (
			DB_IS_MYSQL  ? "SELECT UID, DateTime, LENGTH(Content) AS Size FROM rule WHERE DeviceUID = ? AND AccountUID = ? ORDER BY DateTime DESC" : (
			DB_IS_ORACLE ? "SELECT \"UID\", DateTime, LENGTH(Content) AS \"Size\" FROM rule WHERE DeviceUID = ? AND AccountUID = ? ORDER BY DateTime DESC" : null)));//LENGTHB not support NCLOB
		$sth->execute(array($_GET['serial_number'], $_SESSION['account_uid']));

		while($row = $sth->fetch(PDO::FETCH_ASSOC)){
	   		$rule = $xml->addChild('rule');
			$rule->addAttribute('uid', $row["UID"]);
			$rule->addAttribute('date_time', strtotime($row["DateTime"]));
			$rule->addAttribute('size', $row["Size"]);
		}
		$sth->closeCursor();
		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}

	header('Content-type: text/xml');
	print($xml->asXML());
}

function restore_rule_list(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	// Request the runtime to download the rule to device
	set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
	    // error was suppressed with the @-operator
	    if (0 === error_reporting()) {
	        return false;
	    }

	    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	});

	try{
		$port = 1230;
		$address = "127.0.0.1";

		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		socket_connect($socket, $address, $port);

		$in = '<cmd type="userDownloadRule" username="' . $_SESSION['username'] . '" rule_uid="' . $_GET['rule_uid'] . '" serial_number="' . $_GET['serial_number'] . '" />';
		socket_write($socket, $in);
		socket_shutdown($socket, 1);

		$out = "";
		while($buffer = socket_read($socket, 2048)){
			$out .= $buffer;
		}

		socket_close($socket);
	}
	catch(ErrorException $e){}

	restore_error_handler();
	// Notify END

	header('Content-type: text/xml');
	echo $out;
}

function remove_device(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><cmd/>');

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . DB_NAME : (
			DB_IS_MYSQL  ? "USE " . DB_NAME : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
		$sth->execute();

		$dbh->beginTransaction();
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "DELETE FROM device WHERE UID = ? AND AccountUID = ?" : (
			DB_IS_MYSQL  ? "DELETE FROM device WHERE UID = ? AND AccountUID = ?" : (
			DB_IS_ORACLE ? "DELETE FROM device WHERE \"UID\" = ? AND AccountUID = ?" : null)));
		$sth->execute(array($_GET['serial_number'], $_SESSION['account_uid']));

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "DELETE FROM [rule] WHERE DeviceUID = ? AND AccountUID = ?" : (
			DB_IS_MYSQL  ? "DELETE FROM rule WHERE DeviceUID = ? AND AccountUID = ?" : (
			DB_IS_ORACLE ? "DELETE FROM rule WHERE DeviceUID = ? AND AccountUID = ?" : null)));
		$sth->execute(array($_GET['serial_number'], $_SESSION['account_uid']));

		$dbh->commit();
		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}

	$xml->addAttribute('result', "OK");

	header('Content-type: text/xml');
	print($xml->asXML());
}

function write_log(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	// Request the runtime to download the rule to device
	set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
	    // error was suppressed with the @-operator
	    if (0 === error_reporting()) {
	        return false;
	    }

	    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	});

	try{
		$port = 1230;
		$address = "127.0.0.1";

		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		socket_connect($socket, $address, $port);

		if($_POST["type"] == "UPDATE_START"){
			$in = '<cmd type="writeLogFirmwareUpdateStart" username="' . $_SESSION['username'] . '">';
			foreach($_POST["device_uid_list"] as $serial_number){
				$in .= '<device serial_number="' . $serial_number . '"/>';
			}
			$in .= '</cmd>';
		}

		socket_write($socket, $in);
		socket_shutdown($socket, 1);

		$out = "";
		while($buffer = socket_read($socket, 2048)){
			$out .= $buffer;
		}

		socket_close($socket);
	}
	catch(ErrorException $e){}

	restore_error_handler();
	// Notify END

	header('Content-type: text/xml');
	echo $out;
}

function download_firmware(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	// Request the runtime to download the rule to device
	set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
	    // error was suppressed with the @-operator
	    if (0 === error_reporting()) {
	        return false;
	    }

	    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	});

	try{
		$port = 1230;
		$address = "127.0.0.1";

		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		socket_connect($socket, $address, $port);

		$in = '<cmd type="downloadFirmware" username="' . $_SESSION['username'] . '">';
		foreach($_POST["device_uid_list"] as $serial_number){
			$in .= '<device serial_number="' . $serial_number . '"/>';
		}
		$in .= '</cmd>';
		socket_write($socket, $in);
		socket_shutdown($socket, 1);

		$out = "";
		while($buffer = socket_read($socket, 2048)){
			$out .= $buffer;
		}

		socket_close($socket);
	}
	catch(ErrorException $e){}

	restore_error_handler();
	// Notify END

	header('Content-type: text/xml');
	echo $out;
}

function upload_firmware(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	// Request the runtime to download the rule to device
	set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
	    // error was suppressed with the @-operator
	    if (0 === error_reporting()) {
	        return false;
	    }

	    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	});

	try{
		$port = 1230;
		$address = "127.0.0.1";

		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		socket_connect($socket, $address, $port);

		$in = '<cmd type="uploadFirmware" username="' . $_SESSION['username'] . '" serial_number="' . $_POST['serial_number'] . '" filename="' . $_POST['filename'] . '"' . ($_POST['automatic'] == "false" ? ' path="' . FIRMWARE_TEMP_DIRECTORY . '"' : '') . '/>';
		socket_write($socket, $in);
		socket_shutdown($socket, 1);

		$out = "";
		while($buffer = socket_read($socket, 2048)){
			$out .= $buffer;
		}

		socket_close($socket);
	}
	catch(ErrorException $e){}

	restore_error_handler();
	// Notify END

	header('Content-type: text/xml');
	echo $out;
}

function submit_firmware(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	$temp_filename = basename($_FILES["file"]["tmp_name"]);
	move_uploaded_file($_FILES["file"]["tmp_name"], FIRMWARE_TEMP_DIRECTORY . "\\" . $temp_filename);

	header('Content-type: text/xml');
	echo "<cmd filename=\"" . $temp_filename . "\"/>";
}
?>