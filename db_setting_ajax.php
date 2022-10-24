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

function select_notification(){
	global $login, $lang;
	if($login == false){
		trap("Permission error!");
	}

	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);

	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT Notification,DatabaseEnable,RealTimeEnable from manager.dbo.account where UID = ".$_SESSION["account_uid"].";" : (
		DB_IS_MYSQL  ? "SELECT Notification,DatabaseEnable,RealTimeEnable from manager.account where UID = ".$_SESSION["account_uid"].";" : (
		DB_IS_ORACLE ? "SELECT Notification,DatabaseEnable,RealTimeEnable from manager.\"ACCOUNT\" where \"UID\" = ".$_SESSION["account_uid"].";" : null)));
	$stmt->execute();
	
	
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><setting/>');

	while($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
    	$module = $xml->addChild('list');
		$module->addAttribute('notification', $obj["Notification"]);
		$module->addAttribute('databaseenable', $obj["DatabaseEnable"]);
		$module->addAttribute('realtime_enable', $obj["RealTimeEnable"]);
	}
	
	$stmt->closeCursor();
	$conn = null;

	header('Content-type: text/xml');
	print($xml->asXML());
}

function save_notification(){
	global $login, $lang;
	if($login == false){
		trap("Permission error!");
	}

	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "UPDATE manager.dbo.account SET Notification = ".$_POST["notification"]." WHERE UID=".$_SESSION["account_uid"]."; " : (
		DB_IS_MYSQL  ? "UPDATE manager.account SET Notification = ".$_POST["notification"]." WHERE UID=".$_SESSION["account_uid"]."; " : (
		DB_IS_ORACLE ? "UPDATE manager.\"ACCOUNT\" SET Notification = ".$_POST["notification"]." WHERE \"UID\"=".$_SESSION["account_uid"].";" : null)));
	$stmt->execute();
	// Event log
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "INSERT INTO ".$_SESSION["username"].".dbo.event_log(EventCode) VALUES(10103);" : (
		DB_IS_MYSQL  ? "INSERT INTO ".$_SESSION["username"].".event_log(EventCode) VALUES(10103);" : (
		DB_IS_ORACLE ? "INSERT INTO ".$_SESSION["username"].".event_log(EventCode) VALUES(10103);" : null)));
	$stmt->execute();
	$stmt->closeCursor();

	// Notify the PMMS Cloud runtime
	set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
	    // error was suppressed with the @-operator
	    if (0 === error_reporting()) {
	        return false;
	    }

	    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	});

	try {
		$port = 1232;
		$address = "127.0.0.1";

		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		socket_connect($socket, $address, $port);

		$in = '<cmd type="userChangeAlertSetting" username="' . $_SESSION["username"] . '" notification="' . $_POST["notification"] . '"/>';
		socket_write($socket, $in);
		socket_shutdown($socket, 1);

		socket_close($socket);
	}
	catch (ErrorException $e) {}

	restore_error_handler();
	// Notify END

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><setting/>');
	$module = $xml->addChild('list');
	$module->addAttribute('reply', "OK");

	header('Content-type: text/xml');
	print($xml->asXML());
}

function UpdateImportDB(){
	global $login, $lang;
	if($login == false){
		trap("Permission error!");
	}

	$realtime_enable = ($_POST["realtime_enable"] == 'true') ? 1 : 0;
	$enable = ($_POST["databaseenable"] == 'true') ? 1 : 0;

	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "UPDATE manager.dbo.account SET DatabaseEnable = " . $enable . ",RealTimeEnable = " . $realtime_enable . " WHERE UID=" . $_SESSION["account_uid"] . ";" : (
		DB_IS_MYSQL  ? "UPDATE manager.account SET DatabaseEnable = " . $enable . ",RealTimeEnable = " . $realtime_enable . " WHERE UID=" . $_SESSION["account_uid"] . ";" : (
		DB_IS_ORACLE ? "UPDATE manager.\"ACCOUNT\" SET DatabaseEnable = " . $enable . ",RealTimeEnable = " . $realtime_enable . " WHERE \"UID\"=" . $_SESSION["account_uid"] . ";" : null)));
	$stmt->execute();
	$stmt->closeCursor();
	
	// Event log
	if($realtime_enable == 1){
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "INSERT INTO ".$_SESSION["username"].".dbo.event_log(EventCode) VALUES(10112);" : (
			DB_IS_MYSQL  ? "INSERT INTO ".$_SESSION["username"].".event_log(EventCode) VALUES(10112);" : (
			DB_IS_ORACLE ? "INSERT INTO ".$_SESSION["username"].".event_log(EventCode) VALUES(10112);" : null))
		);
		$stmt->execute();
	}
	else{
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "INSERT INTO ".$_SESSION["username"].".dbo.event_log(EventCode) VALUES(10113);" : (
			DB_IS_MYSQL  ? "INSERT INTO ".$_SESSION["username"].".event_log(EventCode) VALUES(10113);" : (
			DB_IS_ORACLE ? "INSERT INTO ".$_SESSION["username"].".event_log(EventCode) VALUES(10113);" : null))
		);
		$stmt->execute();
	}
	$stmt->closeCursor();
	
	if($enable == 1){
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "INSERT INTO ".$_SESSION["username"].".dbo.event_log(EventCode) VALUES(10106);" : (
			DB_IS_MYSQL  ? "INSERT INTO ".$_SESSION["username"].".event_log(EventCode) VALUES(10106);" : (
			DB_IS_ORACLE ? "INSERT INTO ".$_SESSION["username"].".event_log(EventCode) VALUES(10106);" : null))
		);
		$stmt->execute();
	}
	else{
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "INSERT INTO ".$_SESSION["username"].".dbo.event_log(EventCode) VALUES(10107);" : (
			DB_IS_MYSQL  ? "INSERT INTO ".$_SESSION["username"].".event_log(EventCode) VALUES(10107);" : (
			DB_IS_ORACLE ? "INSERT INTO ".$_SESSION["username"].".event_log(EventCode) VALUES(10107);" : null))
		);
		$stmt->execute();
	}
	$stmt->closeCursor();
	$conn = null;

	// Notify the PMMS Cloud runtime
	set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
	    // error was suppressed with the @-operator
	    if (0 === error_reporting()) {
	        return false;
	    }

	    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	});

	try {
		$port = 1232;
		$address = "127.0.0.1";

		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		socket_connect($socket, $address, $port);

		$in = '<cmd type="userChangeDBFunction" username="' . $_SESSION["username"] . '" enable="' . $enable . '" realtime="' . $realtime_enable . '"/>';
		socket_write($socket, $in);
		socket_shutdown($socket, 1);

		socket_close($socket);
	}
	catch (ErrorException $e) {}

	restore_error_handler();
	// Notify END

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><setting/>');
	$module = $xml->addChild('list');
	$module->addAttribute('reply', "OK");

	header('Content-type: text/xml');
	print($xml->asXML());
}

function cleandb(){
	global $login, $lang;
	if($login == false){
		trap("Permission error!");
	}
	
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><setting/>');
	$module = $xml->addChild('list');
	if($_POST["password"] != $_SESSION["password"]){
		$module->addAttribute('reply', "password_error");
	}
	else{
		$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		//--------------
		//查詢Device的module
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "SELECT * FROM ".$_SESSION["username"].".dbo.module;" : (
			DB_IS_MYSQL  ? "SELECT * FROM ".$_SESSION["username"].".module;" : (
			DB_IS_ORACLE ? "SELECT * FROM ".$_SESSION["username"].".module;" : null))
		);
		$stmt->execute();
		
		
		$module_count = 0;
		//刪除紀錄table
		while($obj1 = $stmt->fetch(PDO::FETCH_ASSOC)){
			$module_count++;
			$table_name = "uid_".$obj1["DeviceUID"]."_".$obj1["UID"];
			
			$stmt2 = $conn->prepare(
				DB_IS_MSSQL  ? "IF EXISTS(SELECT * FROM ".$_SESSION["username"].".sys.tables WHERE name = '".$table_name."') DROP TABLE ".$_SESSION["username"].".dbo.".$table_name.";" : (
				DB_IS_MYSQL  ? "DROP TABLE IF EXISTS ".$_SESSION["username"].".".$table_name.";" : (
				DB_IS_ORACLE ? "DECLARE cnt NUMBER; BEGIN SELECT COUNT(*) into cnt FROM all_tables WHERE owner='".strtoupper($_SESSION["username"])."' AND TABLE_NAME = '".strtoupper($table_name)."'; IF cnt <> 0 THEN EXECUTE IMMEDIATE 'DROP TABLE ".strtoupper($_SESSION["username"].".".$table_name)."'; END IF; END;" : null)));
			$stmt2->execute();
			$stmt2->closeCursor();
			
		}
		$stmt->closeCursor();
		
		//移除realtime_table
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "select * from ".$_SESSION["username"].".dbo.device" : (
			DB_IS_MYSQL  ? "select * from ".$_SESSION["username"].".device" : (
			DB_IS_ORACLE ? "select * from ".$_SESSION["username"].".device" : null))
		);
		$stmt->execute();
		while($obj_device = $stmt->fetch(PDO::FETCH_ASSOC)){
			$table_name = "uid_".$obj_device["UID"]."_realtime";
			$stmt2 = $conn->prepare(
				DB_IS_MSSQL  ? "IF EXISTS(SELECT * FROM ".$_SESSION["username"].".sys.tables WHERE name = '".$table_name."') DROP TABLE ".$_SESSION["username"].".dbo.".$table_name.";" : (
				DB_IS_MYSQL  ? "DROP TABLE IF EXISTS ".$_SESSION["username"].".".$table_name.";" : (
				DB_IS_ORACLE ? "DECLARE cnt NUMBER; BEGIN SELECT COUNT(*) into cnt FROM all_tables WHERE owner='".strtoupper($_SESSION["username"])."' AND TABLE_NAME = '".strtoupper($table_name)."'; IF cnt <> 0 THEN EXECUTE IMMEDIATE 'DROP TABLE ".strtoupper($_SESSION["username"].".".$table_name)."'; END IF; END;" : null)));
			$stmt2->execute();
			$stmt2->closeCursor();
		}
		$stmt->closeCursor();
		
		//移除group data
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "DELETE FROM ".$_SESSION["username"].".dbo.group_data;" : (
			DB_IS_MYSQL  ? "DELETE FROM ".$_SESSION["username"].".group_data;" : (
			DB_IS_ORACLE ? "DELETE FROM ".$_SESSION["username"].".group_data;" : null))
		);
		$stmt->execute();
		$stmt->closeCursor();
			
		//移除group info
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "DELETE FROM ".$_SESSION["username"].".dbo.group_info;" : (
			DB_IS_MYSQL  ? "DELETE FROM ".$_SESSION["username"].".group_info;" : (
			DB_IS_ORACLE ? "DELETE FROM ".$_SESSION["username"].".group_info;" : null))
		);
		$stmt->execute();
		$stmt->closeCursor();
			
		//移除module
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "DELETE FROM ".$_SESSION["username"].".dbo.module;" : (
			DB_IS_MYSQL  ? "DELETE FROM ".$_SESSION["username"].".module;" : (
			DB_IS_ORACLE ? "DELETE FROM ".$_SESSION["username"].".module;" : null))
		);
		$stmt->execute();
		$stmt->closeCursor();
		
		//移除device
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "DELETE FROM ".$_SESSION["username"].".dbo.device;" : (
			DB_IS_MYSQL  ? "DELETE FROM ".$_SESSION["username"].".device;" : (
			DB_IS_ORACLE ? "DELETE FROM ".$_SESSION["username"].".device;" : null))
		);
		$stmt->execute();
		$stmt->closeCursor();
		
		//移除channel
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "DELETE FROM ".$_SESSION["username"].".dbo.channel;" : (
			DB_IS_MYSQL  ? "DELETE FROM ".$_SESSION["username"].".channel;" : (
			DB_IS_ORACLE ? "DELETE FROM ".$_SESSION["username"].".channel;" : null))
		);
		$stmt->execute();
		$stmt->closeCursor();
		
		
		//移除share對象的group
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "SELECT Username FROM manager.dbo.share join manager.dbo.account ON share.AccountUIDShareTo = account.UID WHERE AccountUIDShareBy='".$_SESSION["account_uid"]."';" : (
			DB_IS_MYSQL  ? "SELECT Username FROM manager.share join manager.account ON share.AccountUIDShareTo = account.UID WHERE AccountUIDShareBy='".$_SESSION["account_uid"]."';" : (
			DB_IS_ORACLE ? "SELECT Username FROM manager.\"SHARE\" join manager.\"ACCOUNT\" ON \"SHARE\".AccountUIDShareTo = \"ACCOUNT\".\"UID\" WHERE AccountUIDShareBy='".$_SESSION["account_uid"]."';" : null)));
		$stmt->execute();
		
		while($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
			$stmt2 = $conn->prepare(
				DB_IS_MSSQL  ? "DELETE FROM ".$obj["Username"].".dbo.group_data where AccountUID = '".$_SESSION["account_uid"]."';" : (
				DB_IS_MYSQL  ? "DELETE FROM ".$obj["Username"].".group_data where AccountUID = '".$_SESSION["account_uid"]."';" : (
				DB_IS_ORACLE ? "DELETE FROM ".$obj["Username"].".group_data where AccountUID = '".$_SESSION["account_uid"]."';" : null)));
			$stmt2->execute();
			$stmt2->closeCursor();
			
			$stmt2 = $conn->prepare(
				DB_IS_MSSQL  ? "DELETE FROM ".$obj["Username"].".dbo.dashboard_channel where AccountUID = '".$_SESSION["account_uid"]."';" : (
				DB_IS_MYSQL  ? "DELETE FROM ".$obj["Username"].".dashboard_channel where AccountUID = '".$_SESSION["account_uid"]."';" : (
				DB_IS_ORACLE ? "DELETE FROM ".$obj["Username"].".dashboard_channel where AccountUID = '".$_SESSION["account_uid"]."';" : null)));
			$stmt2->execute();
			$stmt2->closeCursor();
		}
		$stmt->closeCursor();
		
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "DELETE FROM ".$_SESSION["username"].".dbo.dashboard_channel;" : (
			DB_IS_MYSQL  ? "DELETE FROM ".$_SESSION["username"].".dashboard_channel;" : (
			DB_IS_ORACLE ? "DELETE FROM ".$_SESSION["username"].".dashboard_channel;" : null))
		);
		$stmt->execute();
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "DELETE FROM ".$_SESSION["username"].".dbo.dashboard_info;" : (
			DB_IS_MYSQL  ? "DELETE FROM ".$_SESSION["username"].".dashboard_info;" : (
			DB_IS_ORACLE ? "DELETE FROM ".$_SESSION["username"].".dashboard_info;" : null))
		);
		$stmt->execute();
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "DELETE FROM ".$_SESSION["username"].".dbo.dashboard_widget;" : (
			DB_IS_MYSQL  ? "DELETE FROM ".$_SESSION["username"].".dashboard_widget;" : (
			DB_IS_ORACLE ? "DELETE FROM ".$_SESSION["username"].".dashboard_widget;" : null))
		);
		$stmt->execute();
		
		$module->addAttribute('reply', "OK");
		
		//Event log
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "INSERT INTO ".$_SESSION["username"].".dbo.event_log(EventCode) VALUES(10105);" : (
			DB_IS_MYSQL  ? "INSERT INTO ".$_SESSION["username"].".event_log(EventCode) VALUES(10105);" : (
			DB_IS_ORACLE ? "INSERT INTO ".$_SESSION["username"].".event_log(EventCode) VALUES(10105);" : null))
		);
		$stmt->execute();
		$stmt->closeCursor();
		$conn = null;

		try {
			$port = 1232;
			$address = "127.0.0.1";

			$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
			socket_connect($socket, $address, $port);

			$in = '<cmd type="userClearDBFunction" username="' . $_SESSION["username"] . '"/>';
			socket_write($socket, $in);
			socket_shutdown($socket, 1);

			socket_close($socket);
		}
		catch (ErrorException $e) {}

		restore_error_handler();
		// Notify END
	}

	header('Content-type: text/xml');
	print($xml->asXML());
}
?>