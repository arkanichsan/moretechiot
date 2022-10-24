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
//manager.php 顯示未讀事件數量
function notificatin_unread(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	//----------------
	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT COUNT(*) AS unread_count FROM ".$_SESSION["username"].".dbo.event_log WHERE Status = 0;" : (
		DB_IS_MYSQL  ? "SELECT COUNT(*) AS unread_count FROM ".$_SESSION["username"].".event_log WHERE Status = 0;" : (
		DB_IS_ORACLE ? "SELECT COUNT(*) AS unread_count FROM ".$_SESSION["username"].".event_log WHERE Status = 0;" : null))
	);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$unread_count = $row["unread_count"];
	$stmt->closeCursor();
	$conn = null;

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><notification/>');
	$module = $xml->addChild('list');
	$module->addAttribute('unread_count', $unread_count);
	header('Content-type: text/xml');
	print($xml->asXML());
}

function notification_page_count(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}
	
	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
	
	//計算事件數量，製作出頁數
	
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT CASE WHEN COUNT(*) = 0 THEN 0 ELSE Ceiling(CONVERT(float,(count(*)))/CONVERT(float,".$_POST["page_option_count"].")) END AS page_count FROM ".$_SESSION["username"].".dbo.event_log;" : (
		DB_IS_MYSQL  ? "SELECT CASE WHEN COUNT(*) = 0 THEN 0 ELSE Ceiling((count(*))/".$_POST["page_option_count"].") END AS page_count FROM ".$_SESSION["username"].".event_log;" : (
		DB_IS_ORACLE ? "SELECT CASE WHEN COUNT(*) = 0 THEN 0 ELSE CEIL((count(*))/".$_POST["page_option_count"].") END AS page_count FROM ".$_SESSION["username"].".event_log;" : null))
	);
	$stmt->execute();

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><notification/>');

	while($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
    	$module = $xml->addChild('list');
		$module->addAttribute('page_count', $obj["page_count"]);
	}
	
	header('Content-type: text/xml');
	print($xml->asXML());
	
	$stmt->closeCursor();
	$conn = null;
}

function creat_ErrorCodeArray(){
	global $lang;
	$ErrorCodeArray = array(
		"10000"=>$lang["NOTIFICATION"]["ERROR"]["10000"],
		"10001"=>$lang["NOTIFICATION"]["ERROR"]["10001"],
		"10002"=>$lang["NOTIFICATION"]["ERROR"]["10002"],
		"10003"=>$lang["NOTIFICATION"]["ERROR"]["10003"],
		"10004"=>$lang["NOTIFICATION"]["ERROR"]["10004"],
		"10100"=>$lang["NOTIFICATION"]["ERROR"]["10100"],
		"10101"=>$lang["NOTIFICATION"]["ERROR"]["10101"],
		"10102"=>$lang["NOTIFICATION"]["ERROR"]["10102"],
		"10103"=>$lang["NOTIFICATION"]["ERROR"]["10103"],
		"10104"=>$lang["NOTIFICATION"]["ERROR"]["10104"],
		"10105"=>$lang["NOTIFICATION"]["ERROR"]["10105"],
		"10106"=>$lang["NOTIFICATION"]["ERROR"]["10106"],
		"10107"=>$lang["NOTIFICATION"]["ERROR"]["10107"],
		"10108"=>$lang["NOTIFICATION"]["ERROR"]["10108"],
		"10109"=>$lang["NOTIFICATION"]["ERROR"]["10109"],
		"10110"=>$lang["NOTIFICATION"]["ERROR"]["10110"],
		"10111"=>$lang["NOTIFICATION"]["ERROR"]["10111"],
		"10112"=>$lang["NOTIFICATION"]["ERROR"]["10112"],
		"10113"=>$lang["NOTIFICATION"]["ERROR"]["10113"],
		"10114"=>$lang["NOTIFICATION"]["ERROR"]["10114"],
		"10115"=>$lang["NOTIFICATION"]["ERROR"]["10115"],
		"10116"=>$lang["NOTIFICATION"]["ERROR"]["10116"],
		"10301"=>$lang["NOTIFICATION"]["ERROR"]["10301"],
		"10302"=>$lang["NOTIFICATION"]["ERROR"]["10302"],
		"30000"=>$lang["NOTIFICATION"]["ERROR"]["30000"],
		"30001"=>$lang["NOTIFICATION"]["ERROR"]["30001"],
		"30002"=>$lang["NOTIFICATION"]["ERROR"]["30002"],
		"30003"=>$lang["NOTIFICATION"]["ERROR"]["30003"],
		"50000"=>$lang["NOTIFICATION"]["ERROR"]["50000"],
		"50001"=>$lang["NOTIFICATION"]["ERROR"]["50001"],
		"50002"=>$lang["NOTIFICATION"]["ERROR"]["50002"],
		"50006"=>$lang["NOTIFICATION"]["ERROR"]["50006"],
		"50103"=>$lang["NOTIFICATION"]["ERROR"]["50103"],
		"50200"=>$lang["NOTIFICATION"]["ERROR"]["50200"],
		"50202"=>$lang["NOTIFICATION"]["ERROR"]["50202"],
		"50203"=>$lang["NOTIFICATION"]["ERROR"]["50203"],
		"50211"=>$lang["NOTIFICATION"]["ERROR"]["50211"],
		"50212"=>$lang["NOTIFICATION"]["ERROR"]["50212"],
		"50213"=>$lang["NOTIFICATION"]["ERROR"]["50213"],
		"50214"=>$lang["NOTIFICATION"]["ERROR"]["50214"],
		"50215"=>$lang["NOTIFICATION"]["ERROR"]["50215"]
	);
	
	return $ErrorCodeArray;
}

function select_notification_list(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	$ErrorCodeArray = creat_ErrorCodeArray();
	
	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "select * from (select ROW_NUMBER() over (order by DateTime desc) rownum,* from ".$_SESSION["username"].".dbo.event_log) as yourselect where rownum between ".$_POST["start_option"]." and ".$_POST["end_option"]." order by DateTime desc, UID desc;" : (
		DB_IS_MYSQL  ? "select * from (select ROW_NUMBER() over (order by `DateTime` desc) as `rownum`,UID, `DateTime`, EventCode, Parameters, `Status` from ".$_SESSION["username"].".event_log) as yourselect where `rownum` between ".$_POST["start_option"]." and ".$_POST["end_option"]." order by DateTime desc, UID desc;" : (
		DB_IS_ORACLE ? "select * from (select ROW_NUMBER() over (order by DateTime desc) as \"ROWNUM\",\"UID\", DateTime, EventCode, \"PARAMETERS\", Status from ".$_SESSION["username"].".event_log) yourselect where \"ROWNUM\" between ".$_POST["start_option"]." and ".$_POST["end_option"]." order by DateTime desc, \"UID\" desc;" : null)));
	$stmt->execute();
	
	
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><notification/>');

	while($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
		$Error_Message = $ErrorCodeArray[$obj["EventCode"]];
		
		$Variable_array = explode("%variable",$Error_Message);
		$db_Variable_array = explode("|$|",($obj["Parameters"]));
		for($i = 0; $i < count($db_Variable_array); $i++){
			$Error_Message = str_replace("%variable".$i."%","<span class='variable'>".$db_Variable_array[$i]."</span>",$Error_Message);
		}
		$module = $xml->addChild('list');
		$module->addAttribute('DateTime', date_format(date_create($obj["DateTime"]), 'Y/m/d H:i:s'));
		$module->addAttribute('Error_Message', $Error_Message);
		$module->addAttribute('Event_Code', $obj["EventCode"]);
		$module->addAttribute('Readed', $obj["Status"]);
	}
	
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "UPDATE event_log SET Status='1' From ".$_SESSION["username"].".dbo.event_log INNER JOIN (select ROW_NUMBER() over (order by DateTime desc) rownum, * from ".$_SESSION["username"].".dbo.event_log) as yourselect ON yourselect.DateTime=event_log.DateTime and yourselect.EventCode=event_log.EventCode where rownum between ".$_POST["start_option"]." and ".$_POST["end_option"]." and event_log.Status='0';" : (
		DB_IS_MYSQL  ? "UPDATE ".$_SESSION["username"].".event_log INNER JOIN ( SELECT ROW_NUMBER() over (order by `DateTime` desc) as `rownum`,UID, `DateTime`, EventCode, Parameters, `Status` FROM ".$_SESSION["username"].".event_log ) as yourselect ON yourselect.DateTime=event_log.DateTime AND yourselect.EventCode=event_log.EventCode SET  ".$_SESSION["username"].".event_log.Status='1' WHERE `rownum` between ".$_POST["start_option"]." and ".$_POST["end_option"]." and event_log.Status='0'; " : (
		DB_IS_ORACLE ? "UPDATE ".$_SESSION["username"].".event_log SET STATUS = 1 where \"UID\" in (select \"UID\" from( select * from ( select (ROW_NUMBER() over (order by DateTime desc)) \"ROWNUM\",\"UID\", DateTime, EventCode, \"PARAMETERS\", Status from ".$_SESSION["username"].".event_log )where \"ROWNUM\" between ".$_POST["start_option"]." and ".$_POST["end_option"]." order by DateTime desc));" : null)));
	$stmt->execute();
	
	
	
	header('Content-type: text/xml');
	print($xml->asXML());
	$stmt->closeCursor();
	$conn = null;
}

function clear_notification(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><cmd/>');
	$xml->addAttribute('type', "lineBotRemoveMedia");

	// Prevent timeout
	set_time_limit(0);
	
	if($_POST["system"] == "1" || $_POST["video"] == "1"){
		if($_POST["video"] == "1"){
			// Get filename
			$stmt = $conn->prepare(
				DB_IS_MSSQL  ? "SELECT * FROM ".$_SESSION["username"].".dbo.event_log WHERE EventCode LIKE '103%' AND DateTime < DATEADD(MONTH, ".intval($_POST["time"]).", GETDATE());" : (
				DB_IS_MYSQL  ? "SELECT * FROM ".$_SESSION["username"].".event_log WHERE EventCode LIKE '103%' AND `DateTime` < DATE_ADD(NOW(), INTERVAL ".intval($_POST["time"])." MONTH);" : (
				DB_IS_ORACLE ? "SELECT * FROM ".$_SESSION["username"].".event_log WHERE EventCode LIKE '103%' AND DateTime < ADD_MONTHS(SYSDATE, ".intval($_POST["time"]).");" : null)));
			$stmt->execute();
			while($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
				list($device_name, $serial_number, $message, $filename) = explode("|$|", $obj["Parameters"]);
				$xml->addChild('filename', $filename);
			}
			$stmt->closeCursor();
		}

		if($_POST["system"] == "1"){
			$where[] = "EventCode NOT LIKE '103%'";
		}

		if($_POST["video"] == "1"){
			$where[] = "EventCode LIKE '103%'";
		}

		// Event log
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "DELETE FROM ".$_SESSION["username"].".dbo.event_log WHERE (" . implode(" OR ", $where) . ") AND DateTime < DATEADD(MONTH, ".intval($_POST["time"]).", GETDATE());" : (
			DB_IS_MYSQL  ? "DELETE FROM ".$_SESSION["username"].".event_log WHERE (" . implode(" OR ", $where) . ") AND `DateTime` < DATE_ADD(NOW(), INTERVAL ".intval($_POST["time"])." MONTH);" : (
			DB_IS_ORACLE ? "DELETE FROM ".$_SESSION["username"].".event_log WHERE (" . implode(" OR ", $where) . ") AND DateTime < ADD_MONTHS(SYSDATE, ".intval($_POST["time"]).");" : null)));
		$stmt->execute();
		$stmt->closeCursor();
		
		//echo "INSERT INTO ".$_SESSION["username"].".event_log(EventCode) VALUES(10115);";
		//exit;
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "INSERT INTO ".$_SESSION["username"].".dbo.event_log(EventCode) VALUES(10115);" : (
			DB_IS_MYSQL  ? "INSERT INTO ".$_SESSION["username"].".event_log(EventCode) VALUES(10115);" : (
			DB_IS_ORACLE ? "INSERT INTO ".$_SESSION["username"].".event_log(EventCode) VALUES(10115);" : null))
		);
		$stmt->execute();
		$stmt->closeCursor();
	}

	if($_POST["video"] == "1"){
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
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><notification/>');
	$module = $xml->addChild('clear');
	$module->addAttribute('reply', "OK");

	header('Content-type: text/xml');
	print($xml->asXML());
	$conn = null;
}

function read_notification(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
	// Event log
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "UPDATE ".$_SESSION["username"].".dbo.event_log SET Status = 1;" : (
		DB_IS_MYSQL  ? "UPDATE ".$_SESSION["username"].".event_log SET Status = 1;" : (
		DB_IS_ORACLE ? "UPDATE ".$_SESSION["username"].".event_log SET Status = 1;" : null))
	);
	$stmt->execute();
	
	$stmt->closeCursor();
	$conn = null;

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><notification/>');
	$module = $xml->addChild('clear');
	$module->addAttribute('reply', "OK");

	header('Content-type: text/xml');
	print($xml->asXML());
}
?>