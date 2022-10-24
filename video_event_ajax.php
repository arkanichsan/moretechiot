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

function get_device(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><video/>');

	// Event Log
	$data = array();

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_MYSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $_SESSION["username"] : null)));
		$sth->execute();

		$sth = $dbh->prepare("SELECT * FROM event_log WHERE EventCode LIKE '103%' ORDER BY DateTime DESC");
		$sth->execute();

		while($row = $sth->fetch(PDO::FETCH_ASSOC)){
			list($device_nickname, $device_uid, $message, $path) = explode("|$|", $row["Parameters"]);

			if(!array_key_exists($device_uid, $data)){
				$data[$device_uid] = $device_nickname;
			}
		}
		$sth->closeCursor();

		// Event Log(Shared)
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . DB_NAME : (
			DB_IS_MYSQL  ? "USE " . DB_NAME : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
		$sth->execute();

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "SELECT * FROM share, account WHERE share.AccountUIDShareTo = ? AND share.AccountUIDShareBy = account.UID" : (
			DB_IS_MYSQL  ? "SELECT * FROM share, account WHERE share.AccountUIDShareTo = ? AND share.AccountUIDShareBy = account.UID" : (
			DB_IS_ORACLE ? "SELECT * FROM \"SHARE\", account WHERE \"SHARE\".AccountUIDShareTo = ? AND \"SHARE\".AccountUIDShareBy = account.\"UID\"" : null)));
		$sth->execute(array($_SESSION["account_uid"]));

		while($row = $sth->fetch(PDO::FETCH_ASSOC)){
			$sth2 = $dbh->prepare(
				DB_IS_MSSQL  ? "USE " . $row["Username"] : (
				DB_IS_MYSQL  ? "USE " . $row["Username"] : (
				DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $row["Username"] : null)));
			$sth2->execute();

			$sth2 = $dbh->prepare("SELECT * FROM event_log WHERE EventCode LIKE '103%' ORDER BY DateTime DESC");
			$sth2->execute();

			while($row2 = $sth2->fetch(PDO::FETCH_ASSOC)){
				list($device_nickname, $device_uid, $message, $path) = explode("|$|", $row2["Parameters"]);

				if(!array_key_exists($device_uid, $data)){
					$data[$device_uid] = $device_nickname;
				}
			}
			$sth2->closeCursor();
		}
		$sth->closeCursor();

		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}

	foreach ($data as $device_uid => $device_nickname){
		$device = $xml->addChild('device', $device_nickname);
		$device->addAttribute('uid', $device_uid);
	}

	header('Content-type: text/xml');
	print($xml->asXML());
}

function get_date(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	// Device filter SQL command
	$sql_device_filter = "";

	foreach ($_POST['uid'] as $device_uid) {
		$sql_device_filter .= "Parameters NOT LIKE '%|$|" . $device_uid . "|$|%' AND ";
	}

	$sql_device_filter = rtrim($sql_device_filter, " AND ");

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><video/>');

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_MYSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $_SESSION["username"] : null)));
		$sth->execute();

		$sth = $dbh->prepare("SELECT DISTINCT DateTime FROM event_log WHERE EventCode LIKE '103%'" . (strlen($sql_device_filter) > 0 ? " AND (" . $sql_device_filter . ")" : ""));
		$sth->execute();

		while($row = $sth->fetch(PDO::FETCH_ASSOC)){
			$data[strtotime($row["DateTime"])] = true;
		}
		$sth->closeCursor();

		// Event Log(Shared)
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . DB_NAME : (
			DB_IS_MYSQL  ? "USE " . DB_NAME : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
		$sth->execute();

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "SELECT * FROM share, account WHERE share.AccountUIDShareTo = ? AND share.AccountUIDShareBy = account.UID" : (
			DB_IS_MYSQL  ? "SELECT * FROM share, account WHERE share.AccountUIDShareTo = ? AND share.AccountUIDShareBy = account.UID" : (
			DB_IS_ORACLE ? "SELECT * FROM \"SHARE\", account WHERE \"SHARE\".AccountUIDShareTo = ? AND \"SHARE\".AccountUIDShareBy = account.\"UID\"" : null)));
		$sth->execute(array($_SESSION["account_uid"]));

		while($row = $sth->fetch(PDO::FETCH_ASSOC)){
			$sth2 = $dbh->prepare(
				DB_IS_MSSQL  ? "USE " . $row["Username"] : (
				DB_IS_MYSQL  ? "USE " . $row["Username"] : (
				DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $row["Username"] : null)));
			$sth2->execute();

			$sth2 = $dbh->prepare("SELECT DISTINCT DateTime FROM event_log WHERE EventCode LIKE '103%'" . (strlen($sql_device_filter) > 0 ? " AND (" . $sql_device_filter . ")" : ""));
			$sth2->execute();

			while($row2 = $sth2->fetch(PDO::FETCH_ASSOC)){
				$data[strtotime($row2["DateTime"])] = true;
			}
			$sth2->closeCursor();
		}
		$sth->closeCursor();

		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}

	foreach ($data as $timestamp => $value){
    	$date = $xml->addChild('date');
		$date->addAttribute('timestamp', $timestamp);
	}

	header('Content-type: text/xml');
	print($xml->asXML());
}

function get_event(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	// Device filter SQL command
	$sql_device_filter = "";

	foreach ($_POST['uid'] as $device_uid) {
		$sql_device_filter .= "Parameters NOT LIKE '%|$|" . $device_uid . "|$|%' AND ";
	}

	$sql_device_filter = rtrim($sql_device_filter, " AND ");

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><video/>');
	$events = array();

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_MYSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $_SESSION["username"] : null)));
		$sth->execute();

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "SELECT * FROM event_log WHERE EventCode LIKE '103%' AND DateTime >= ? AND DateTime <= ?" . (strlen($sql_device_filter) > 0 ? " AND (" . $sql_device_filter . ")" : "") : (
			DB_IS_MYSQL  ? "SELECT * FROM event_log WHERE EventCode LIKE '103%' AND DateTime >= ? AND DateTime <= ?" . (strlen($sql_device_filter) > 0 ? " AND (" . $sql_device_filter . ")" : "") : (
			DB_IS_ORACLE ? "SELECT * FROM event_log WHERE EventCode LIKE '103%' AND DateTime >= TO_TIMESTAMP(?, 'YYYY-MM-DD HH24:MI:SS') AND DateTime <= TO_TIMESTAMP(?, 'YYYY-MM-DD HH24:MI:SS')" . (strlen($sql_device_filter) > 0 ? " AND (" . $sql_device_filter . ")" : "") : null)));
		$sth->execute(array($_POST['from'], $_POST['to']));

		while($row = $sth->fetch(PDO::FETCH_ASSOC)){
			$events[] = array(
				"uid" => $row["UID"],
				"timestamp" => strtotime($row["DateTime"]),
				"event" => $row["Parameters"],
				"type" => $row["EventCode"] == "10301" ? "1" : "2"
			);
		}
		$sth->closeCursor();

		// Event Log(Shared)
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . DB_NAME : (
			DB_IS_MYSQL  ? "USE " . DB_NAME : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
		$sth->execute();

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "SELECT * FROM share, account WHERE share.AccountUIDShareTo = ? AND share.AccountUIDShareBy = account.UID" : (
			DB_IS_MYSQL  ? "SELECT * FROM share, account WHERE share.AccountUIDShareTo = ? AND share.AccountUIDShareBy = account.UID" : (
			DB_IS_ORACLE ? "SELECT * FROM \"SHARE\", account WHERE \"SHARE\".AccountUIDShareTo = ? AND \"SHARE\".AccountUIDShareBy = account.\"UID\"" : null)));
		$sth->execute(array($_SESSION["account_uid"]));

		while($row = $sth->fetch(PDO::FETCH_ASSOC)){
			$sth2 = $dbh->prepare(
				DB_IS_MSSQL  ? "USE " . $row["Username"] : (
				DB_IS_MYSQL  ? "USE " . $row["Username"] : (
				DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $row["Username"] : null)));
			$sth2->execute();

			$sth2 = $dbh->prepare(
			DB_IS_MSSQL  ? "SELECT * FROM event_log WHERE EventCode LIKE '103%' AND DateTime >= ? AND DateTime <= ?" . (strlen($sql_device_filter) > 0 ? " AND (" . $sql_device_filter . ")" : "") : (
			DB_IS_MYSQL  ? "SELECT * FROM event_log WHERE EventCode LIKE '103%' AND DateTime >= ? AND DateTime <= ?" . (strlen($sql_device_filter) > 0 ? " AND (" . $sql_device_filter . ")" : "") : (
			DB_IS_ORACLE ? "SELECT * FROM event_log WHERE EventCode LIKE '103%' AND DateTime >= TO_TIMESTAMP(?, 'YYYY-MM-DD HH24:MI:SS') AND DateTime <= TO_TIMESTAMP(?, 'YYYY-MM-DD HH24:MI:SS')" . (strlen($sql_device_filter) > 0 ? " AND (" . $sql_device_filter . ")" : "") : null)));
			$sth2->execute(array($_POST['from'], $_POST['to']));

			while($row2 = $sth2->fetch(PDO::FETCH_ASSOC)){
				$events[] = array(
					"timestamp" => strtotime($row2["DateTime"]),
					"event" => $row2["Parameters"],
					"type" => $row2["EventCode"] == "10301" ? "1" : "2",
					"username" => $row["Username"],
					"nickname" => $row["Nickname"]
				);
			}
			$sth2->closeCursor();
		}
		$sth->closeCursor();

		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}

	// Sort
	foreach ($events as $key => $obj) {
		$timestamps[$key] = $obj["timestamp"];
	}
	array_multisort($timestamps, SORT_DESC, SORT_NUMERIC, $events);

	foreach ($events as $obj){
    	$event = $xml->addChild('event', $obj["event"]);

		if(isset($obj["uid"])){
			$event->addAttribute('uid', $obj["uid"]);
		}

		if(isset($obj["username"])){
			$event->addAttribute('username', $obj["username"]);
		}

		if(isset($obj["nickname"])){
			$event->addAttribute('nickname', $obj["nickname"]);
		}

		$event->addAttribute('timestamp', $obj["timestamp"]);
		$event->addAttribute('type', $obj["type"]);
	}

	header('Content-type: text/xml');
	print($xml->asXML());
}

function remove_event(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><cmd/>');
	$xml->addAttribute('type', "lineBotRemoveMedia");

	// Prevent timeout
	set_time_limit(0);

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_MYSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $_SESSION["username"] : null)));
		$sth->execute();

		foreach ($_POST['uid'] as $event_uid) {
			// Get filename
			$sth = $dbh->prepare(
				DB_IS_MSSQL  ? "SELECT * FROM event_log WHERE UID = ?" : (
				DB_IS_MYSQL  ? "SELECT * FROM event_log WHERE UID = ?" : (
				DB_IS_ORACLE ? "SELECT * FROM event_log WHERE \"UID\" = ?" : null)));
			$sth->execute(array($event_uid));

			$row = $sth->fetch(PDO::FETCH_ASSOC);
			$sth->closeCursor();
			list($device_name, $serial_number, $message, $filename) = explode("|$|", $row["Parameters"]);
			$xml->addChild('filename', $filename);

			// Delete event in database
			$sth = $dbh->prepare(
				DB_IS_MSSQL  ? "DELETE FROM event_log WHERE UID = ?" : (
				DB_IS_MYSQL  ? "DELETE FROM event_log WHERE UID = ?" : (
				DB_IS_ORACLE ? "DELETE FROM event_log WHERE \"UID\" = ?" : null)));
			$sth->execute(array($event_uid));
/*
			// Unlink media file
			preg_match('/^[^_]{16}_(\d{8})/', $filename, $matches);
			list($filename, $extension) = explode(".", $filename);
			$filepath = MEDIA_DIRECTORY . "\\Media\\Device\\" . $matches[1] . "\\" . $filename . "*.*";
			foreach (glob($filepath) as $filename) {
				unlink($filename);
			}
*/
		}

		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}

	// Request the runtime to delete file
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

		$in = $xml->asXML();
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

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><video/>');
	$xml->addAttribute('reply', "OK");

	header('Content-type: text/xml');
	print($xml->asXML());
}
?>