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

function get_device_list(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><list/>');

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_MYSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $_SESSION["username"] : null)));
		$sth->execute();

		// Select device
		$sth = $dbh->prepare("SELECT * FROM device");
		$sth->execute();

		while($row = $sth->fetch(PDO::FETCH_ASSOC)){
	    	$module = $xml->addChild('device');
			$module->addAttribute('uid', $row["UID"]);
			$module->addAttribute('model_name', $row["ModelName"]);
			$module->addAttribute('nickname', $row["Nickname"]);

			$module->addAttribute('account_uid', $_SESSION["account_uid"]);
		}
		$sth->closeCursor();

		// Select group
		$sth = $dbh->prepare("SELECT * FROM group_info WHERE Type = 1");
		$sth->execute();

		while($row = $sth->fetch(PDO::FETCH_ASSOC)){
	    	$module = $xml->addChild('group');
			$module->addAttribute('uid', $row["UID"]);
			$module->addAttribute('name', $row["Name"]);
		}
		$sth->closeCursor();

		// Select share device
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

			$sth2 = $dbh->prepare("SELECT * FROM device");
			$sth2->execute();

			while($row2 = $sth2->fetch(PDO::FETCH_ASSOC)){
		    	$module = $xml->addChild('device');
				$module->addAttribute('uid', $row2["UID"]);
				$module->addAttribute('model_name', $row2["ModelName"]);
				$module->addAttribute('nickname', $row2["Nickname"]);

				$module->addAttribute('account_uid', $row["UID"]);
				$module->addAttribute('account_username', $row["Username"]);
				$module->addAttribute('account_nickname', $row["Nickname"]);
			}
			$sth2->closeCursor();
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

function get_module_list(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><list/>');

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);

		if($_POST['account_uid'] != $_SESSION["account_uid"]){// shared
			$sth = $dbh->prepare(
				DB_IS_MSSQL  ? "USE " . DB_NAME : (
				DB_IS_MYSQL  ? "USE " . DB_NAME : (
				DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
			$sth->execute();

			$sth = $dbh->prepare(
				DB_IS_MSSQL  ? "SELECT * FROM share, account WHERE share.AccountUIDShareBy = ? AND share.AccountUIDShareTo = ? AND share.AccountUIDShareBy = account.UID" : (
				DB_IS_MYSQL  ? "SELECT * FROM share, account WHERE share.AccountUIDShareBy = ? AND share.AccountUIDShareTo = ? AND share.AccountUIDShareBy = account.UID" : (
				DB_IS_ORACLE ? "SELECT * FROM \"SHARE\", account WHERE \"SHARE\".AccountUIDShareBy = ? AND \"SHARE\".AccountUIDShareTo = ? AND \"SHARE\".AccountUIDShareBy = account.\"UID\"" : null)));
			$sth->execute(array($_POST['account_uid'], $_SESSION["account_uid"]));

			$row = $sth->fetch(PDO::FETCH_ASSOC);
			$sth->closeCursor();

			if($row){
				$sth = $dbh->prepare(
					DB_IS_MSSQL  ? "USE " . $row["Username"] : (
					DB_IS_MYSQL  ? "USE " . $row["Username"] : (
					DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $row["Username"] : null)));
				$sth->execute();
			}
			else{
				trap("Permission error!");
			}
		}
		else{
			$sth = $dbh->prepare(
				DB_IS_MSSQL  ? "USE " . $_SESSION["username"] : (
				DB_IS_MYSQL  ? "USE " . $_SESSION["username"] : (
				DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $_SESSION["username"] : null)));
			$sth->execute();
		}

		// Select module
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "SELECT * FROM module WHERE DeviceUID = ? AND Type = 1 AND Removed = 0 ORDER BY Number" : (
			DB_IS_MYSQL  ? "SELECT * FROM module WHERE DeviceUID = ? AND Type = 1 AND Removed = 0 ORDER BY Number" : (
			DB_IS_ORACLE ? "SELECT * FROM module WHERE DeviceUID = ? AND Type = 1 AND Removed = 0 ORDER BY \"NUMBER\"" : null)));
		$sth->execute(array($_POST['device_uid']));

		// Table exist check
		$row_array = array();

		$table_name = "";
		while($row = $sth->fetch(PDO::FETCH_ASSOC)){
			if($table_name != ""){
				$table_name .= " OR ";
			}

			if(DB_IS_MSSQL){
				$table_name .= "TABLE_NAME = '" . "uid_" . $_POST['device_uid'] . "_" . $row["UID"] . "'";
				$row_array["uid_" . $_POST['device_uid'] . "_" . $row["UID"]] = $row;
			}
			else if(DB_IS_MYSQL){
				$table_name .= "TABLE_NAME = '" . "uid_" . $_POST['device_uid'] . "_" . $row["UID"] . "'";
				$row_array["uid_" . $_POST['device_uid'] . "_" . $row["UID"]] = $row;
			}
			else if(DB_IS_ORACLE){
				$table_name .= "TABLE_NAME = UPPER('" . "uid_" . $_POST['device_uid'] . "_" . $row["UID"] . "')";
				$row_array[strtoupper("uid_" . $_POST['device_uid'] . "_" . $row["UID"])] = $row;
			}
		}
		$sth->closeCursor();

		if($table_name != ""){
			$sth = $dbh->prepare(
				DB_IS_MSSQL  ? "SELECT TABLE_NAME AS TableName FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND (" . $table_name . ")" : (
				DB_IS_MYSQL  ? "SELECT TABLE_NAME AS TableName FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND (" . $table_name . ") AND TABLE_SCHEMA = DATABASE()" : (
				DB_IS_ORACLE ? "SELECT TABLE_NAME AS TableName FROM DBA_TABLES WHERE (" . $table_name . ") AND OWNER = SYS_CONTEXT('USERENV', 'CURRENT_SCHEMA')" : null)));
			$sth->execute();

			while($row = $sth->fetch(PDO::FETCH_ASSOC)){
				if(array_key_exists($row["TableName"], $row_array)){
			    	$module = $xml->addChild('module');
					$module->addAttribute('uid', $row_array[$row["TableName"]]["UID"]);
					$module->addAttribute('interface', $row_array[$row["TableName"]]["Interface"]);
					$module->addAttribute('number', $row_array[$row["TableName"]]["Number"]);
					$module->addAttribute('manufacturer', $row_array[$row["TableName"]]["Manufacturer"]);
					$module->addAttribute('model_name', $row_array[$row["TableName"]]["ModelName"]);
					$module->addAttribute('nickname', $row_array[$row["TableName"]]["Nickname"]);
					$module->addAttribute('loop', $row_array[$row["TableName"]]["Loop"]);
					$module->addAttribute('phase', $row_array[$row["TableName"]]["Phase"]);
				}
			}
			$sth->closeCursor();
		}

		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}

	header('Content-type: text/xml');
	print($xml->asXML());
}

function get_channel_list(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><list/>');

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);

		if($_POST['account_uid'] != $_SESSION["account_uid"]){// shared
			$sth = $dbh->prepare(
				DB_IS_MSSQL  ? "USE " . DB_NAME : (
				DB_IS_MYSQL  ? "USE " . DB_NAME : (
				DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
			$sth->execute();

			$sth = $dbh->prepare(
				DB_IS_MSSQL  ? "SELECT * FROM share, account WHERE share.AccountUIDShareBy = ? AND share.AccountUIDShareTo = ? AND share.AccountUIDShareBy = account.UID" : (
				DB_IS_MYSQL  ? "SELECT * FROM share, account WHERE share.AccountUIDShareBy = ? AND share.AccountUIDShareTo = ? AND share.AccountUIDShareBy = account.UID" : (
				DB_IS_ORACLE ? "SELECT * FROM \"SHARE\", account WHERE \"SHARE\".AccountUIDShareBy = ? AND \"SHARE\".AccountUIDShareTo = ? AND \"SHARE\".AccountUIDShareBy = account.\"UID\"" : null)));
			$sth->execute(array($_POST['account_uid'], $_SESSION["account_uid"]));

			$row = $sth->fetch(PDO::FETCH_ASSOC);
			$sth->closeCursor();

			if($row){
				$sth = $dbh->prepare(
					DB_IS_MSSQL  ? "USE " . $row["Username"] : (
					DB_IS_MYSQL  ? "USE " . $row["Username"] : (
					DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $row["Username"] : null)));
				$sth->execute();
			}
			else{
				trap("Permission error!");
			}
		}
		else{
			$sth = $dbh->prepare(
				DB_IS_MSSQL  ? "USE " . $_SESSION["username"] : (
				DB_IS_MYSQL  ? "USE " . $_SESSION["username"] : (
				DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $_SESSION["username"] : null)));
			$sth->execute();
		}

		// Select channel
		$sth = $dbh->prepare(
				DB_IS_MSSQL  ? "SELECT * FROM channel WHERE DeviceUID = ? AND ModuleUID = ? ORDER BY Loop, Phase" : (
				DB_IS_MYSQL  ? "SELECT * FROM channel WHERE DeviceUID = ? AND ModuleUID = ? ORDER BY `Loop`, Phase" : (
				DB_IS_ORACLE ? "SELECT * FROM channel WHERE DeviceUID = ? AND ModuleUID = ? ORDER BY Loop, Phase" : null)));
		$sth->execute(array($_POST['device_uid'], $_POST['module_uid']));

		$flag = 0;
		while($row = $sth->fetch(PDO::FETCH_ASSOC)){
			if($flag != $row["Loop"]){
		    	$loop = $xml->addChild('loop');
				$loop->addAttribute('index', $row["Loop"] - 1);
				$flag = $row["Loop"];
			}

//			$loop->addAttribute('nickname', $row["Nickname"]);// Not overwritable
			$loop["nickname"] = $row["Nickname"];// Overwritable

    		$phase = $loop->addChild('phase');
			$phase->addAttribute('index', $row["Phase"] - 1);
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

function get_module_data(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	// Return data
	$data = array(
		array("data" => array()),
		array("data" => array()),
		array("data" => array()),
		array("data" => array())
	);

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);

		if($_POST['account_uid'] != $_SESSION["account_uid"]){// shared
			$sth = $dbh->prepare(
				DB_IS_MSSQL  ? "USE " . DB_NAME : (
				DB_IS_MYSQL  ? "USE " . DB_NAME : (
				DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
			$sth->execute();

			$sth = $dbh->prepare(
				DB_IS_MSSQL  ? "SELECT * FROM share, account WHERE share.AccountUIDShareBy = ? AND share.AccountUIDShareTo = ? AND share.AccountUIDShareBy = account.UID" : (
				DB_IS_MYSQL  ? "SELECT * FROM share, account WHERE share.AccountUIDShareBy = ? AND share.AccountUIDShareTo = ? AND share.AccountUIDShareBy = account.UID" : (
				DB_IS_ORACLE ? "SELECT * FROM \"SHARE\", account WHERE \"SHARE\".AccountUIDShareBy = ? AND \"SHARE\".AccountUIDShareTo = ? AND \"SHARE\".AccountUIDShareBy = account.\"UID\"" : null)));
			$sth->execute(array($_POST['account_uid'], $_SESSION["account_uid"]));

			$row = $sth->fetch(PDO::FETCH_ASSOC);
			$sth->closeCursor();

			if($row){
				$sth = $dbh->prepare(
					DB_IS_MSSQL  ? "USE " . $row["Username"] : (
					DB_IS_MYSQL  ? "USE " . $row["Username"] : (
					DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $row["Username"] : null)));
				$sth->execute();
			}
			else{
				trap("Permission error!");
			}
		}
		else{
			$sth = $dbh->prepare(
				DB_IS_MSSQL  ? "USE " . $_SESSION["username"] : (
				DB_IS_MYSQL  ? "USE " . $_SESSION["username"] : (
				DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $_SESSION["username"] : null)));
			$sth->execute();
		}

		// Table exist check
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "SELECT COUNT(*) AS Counter FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_NAME = 'uid_" . $_POST['device_uid'] . "_" . $_POST['module_uid'] . "'" : (
			DB_IS_MYSQL  ? "SELECT COUNT(*) AS Counter FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_NAME = 'uid_" . $_POST['device_uid'] . "_" . $_POST['module_uid'] . "' AND TABLE_SCHEMA = DATABASE()" : (
			DB_IS_ORACLE ? "SELECT COUNT(*) AS Counter FROM DBA_TABLES WHERE TABLE_NAME = UPPER('uid_" . $_POST['device_uid'] . "_" . $_POST['module_uid'] . "') AND OWNER = SYS_CONTEXT('USERENV', 'CURRENT_SCHEMA')" : null)));
		$sth->execute();

		$row = $sth->fetch(PDO::FETCH_ASSOC);
		$sth->closeCursor();

		if($row["Counter"] > 0){
			if($_POST['unit'] == "hour"){
				if(DB_IS_MSSQL){
					$concat = "FORMAT(DateTime, 'yyyy'), '-', FORMAT(DateTime, 'MM'), '-', FORMAT(DateTime, 'dd'), ' ', FORMAT(DateTime, 'HH'), ':00:00'";
					$group = "FORMAT(DateTime, 'yyyy'), FORMAT(DateTime, 'MM'), FORMAT(DateTime, 'dd'), FORMAT(DateTime, 'HH')";
				}
				else if(DB_IS_MYSQL){
					$concat = "DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%Y'), '-', DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%m'), '-', DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%d'), ' ', DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%H'), ':00:00'";
					$group = "DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%Y'), DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%m'), DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%d'), DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%H')";
				}
				else if(DB_IS_ORACLE){
					$concat = "TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'YYYY') || '-' || TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'MM') || '-' || TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'DD') || ' ' || TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'HH24') || ':00:00'";
					$group = "TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'YYYY'), TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'MM'), TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'DD'), TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'HH24')";
				}
			}
			else if($_POST['unit'] == "day"){
				if(DB_IS_MSSQL){
					$concat = "FORMAT(DateTime, 'yyyy'), '-', FORMAT(DateTime, 'MM'), '-', FORMAT(DateTime, 'dd'), ' ', '00:00:00'";
					$group = "FORMAT(DateTime, 'yyyy'), FORMAT(DateTime, 'MM'), FORMAT(DateTime, 'dd')";
				}
				else if(DB_IS_MYSQL){
					$concat = "DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%Y'), '-', DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%m'), '-', DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%d'), ' ', '00:00:00'";
					$group = "DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%Y'), DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%m'), DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%d')";
				}
				else if(DB_IS_ORACLE){
					$concat = "TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'YYYY') || '-' || TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'MM') || '-' || TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'DD') || ' ' || '00:00:00'";
					$group = "TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'YYYY'), TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'MM'), TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'DD')";
				}
			}
			else if($_POST['unit'] == "month"){
				if(DB_IS_MSSQL){
					$concat = "FORMAT(DateTime, 'yyyy'), '-', FORMAT(DateTime, 'MM'), '-01 00:00:00'";
					$group = "FORMAT(DateTime, 'yyyy'), FORMAT(DateTime, 'MM')";
				}
				else if(DB_IS_MYSQL){
					$concat = "DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%Y'), '-', DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%m'), '-01 00:00:00'";
					$group = "DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%Y'), DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%m')";
				}
				else if(DB_IS_ORACLE){
					$concat = "TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'YYYY') || '-' || TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'MM') || '-01 00:00:00'";
					$group = "TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'YYYY'), TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'MM')";
				}
			}
			else if($_POST['unit'] == "year"){
				if(DB_IS_MSSQL){
					$concat = "FORMAT(DateTime, 'yyyy'), '-01-01 00:00:00'";
					$group = "FORMAT(DateTime, 'yyyy')";
				}
				else if(DB_IS_MYSQL){
					$concat = "DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%Y'), '-01-01 00:00:00'";
					$group = "DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%Y')";
				}
				else if(DB_IS_ORACLE){
					$concat = "TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'YYYY') || '-01-01 00:00:00'";
					$group = "TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'YYYY')";
				}
			}

			$phase = "";
			for($i = 0; $i < strlen($_POST['phase']); $i++){
				if($i != 0){
					$phase .= " OR ";
				}

				$phase .= "Phase = " . $_POST['phase'][$i];
			}

			if(DB_IS_MSSQL){
				$sql  = "SELECT FORMAT(TODATETIMEOFFSET(CONCAT(" . $concat . "), '" . $_POST['timezone'] . "'), 'yyyy-MM-dd HH:mm:sszzzz') AS DateTime, Phase, " . $_POST['oper'] . "(" . ($_POST['type'] == "PF" ? "ABS(" . $_POST['type'] . ")" : $_POST['type']) . ") AS Result ";
				$sql .= "FROM (SELECT SWITCHOFFSET(DateTime, '" . $_POST['timezone'] . "') AS DateTime, Phase, " . $_POST['type'] . " FROM uid_" . $_POST['device_uid'] . "_" . $_POST['module_uid'] . " WHERE Loop = " . $_POST['loop'] . " AND (" . $phase . ") AND DateTime >= '" . $_POST['begin'] . $_POST['timezone'] . "' AND DateTime <= '" . $_POST['end'] . $_POST['timezone'] . "' ) AS TempTable ";
				$sql .= "GROUP BY " . $group . ", Phase ";
				$sql .= "ORDER BY Phase, DateTime";
			}
			else if(DB_IS_MYSQL){
				$sql  = "SELECT DATE_FORMAT(CONVERT_TZ(STR_TO_DATE(CONCAT(" . $concat . "), '%Y-%m-%d %H:%i:%s'), '" . $_POST['timezone'] . "', '+00:00') , '%Y-%m-%d %H:%i:%s') AS DateTime, Phase, " . $_POST['oper'] . "(" . ($_POST['type'] == "PF" ? "ABS(" . $_POST['type'] . ")" : $_POST['type']) . ") AS Result ";
				$sql .= "FROM (SELECT DateTime AS DateTime, Phase, " . $_POST['type'] . " FROM uid_" . $_POST['device_uid'] . "_" . $_POST['module_uid'] . " WHERE `Loop` = " . $_POST['loop'] . " AND (" . $phase . ") AND DateTime >= CONVERT_TZ(STR_TO_DATE('" . $_POST['begin'] . "', '%Y-%m-%d %H:%i:%s'), '" . $_POST['timezone'] . "', '+00:00') AND DateTime <= CONVERT_TZ(STR_TO_DATE('" . $_POST['end'] . "', '%Y-%m-%d %H:%i:%s'), '" . $_POST['timezone'] . "', '+00:00')) AS TempTable ";
				$sql .= "GROUP BY " . $group . ", Phase ";
				$sql .= "ORDER BY Phase, DateTime";
			}
			else if(DB_IS_ORACLE){
				$sql  = "SELECT TO_CHAR(TO_TIMESTAMP_TZ(" . $concat . " || '" . $_POST['timezone'] . "', 'YYYY-MM-DD HH24:MI:SSTZH:TZM'), 'YYYY-MM-DD HH24:MI:SSTZH:TZM') AS DateTime, Phase, " . $_POST['oper'] . "(" . ($_POST['type'] == "PF" ? "ABS(" . $_POST['type'] . ")" : $_POST['type']) . ") AS Result ";
				$sql .= "FROM (SELECT DateTime AS DateTime, Phase, " . $_POST['type'] . " FROM uid_" . $_POST['device_uid'] . "_" . $_POST['module_uid'] . " WHERE Loop = " . $_POST['loop'] . " AND (" . $phase . ") AND DateTime >= TO_TIMESTAMP_TZ('" . $_POST['begin'] . $_POST['timezone'] . "', 'YYYY-MM-DD HH24:MI:SSTZH:TZM') AT TIME ZONE 'UTC' AND DateTime <= TO_TIMESTAMP_TZ('" . $_POST['end'] . $_POST['timezone'] . "', 'YYYY-MM-DD HH24:MI:SSTZH:TZM') AT TIME ZONE 'UTC') TempTable ";
				$sql .= "GROUP BY " . $group . ", Phase ";
				$sql .= "ORDER BY Phase, DateTime";
			}

			$sth = $dbh->prepare($sql);
			$sth->execute();

			while($row = $sth->fetch(PDO::FETCH_ASSOC)){
				$data[$row["Phase"] - 1]['data'][] = array(strtotime($row["DateTime"]), floatval($row["Result"]));
			}
			$sth->closeCursor();
		}

		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}

	header('Content-type: application/json');
	echo json_encode($data);
}

function get_group_data(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	// Return data
	$data = array(
		array("data" => array())
	);

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_MYSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $_SESSION["username"] : null)));
		$sth->execute();

		// Prevent timeout
		set_time_limit(0);

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "SELECT DISTINCT Username FROM group_data, manager.dbo.account WHERE group_data.GroupInfoUID = ? AND group_data.AccountUID = manager.dbo.account.UID" : (
			DB_IS_MYSQL  ? "SELECT DISTINCT Username FROM group_data, manager.account WHERE group_data.GroupInfoUID = ? AND group_data.AccountUID = manager.account.UID" : (
			DB_IS_ORACLE ? "SELECT DISTINCT Username FROM group_data, manager.account WHERE group_data.GroupInfoUID = ? AND group_data.AccountUID = manager.account.\"UID\"" : null)));
		$sth->execute(array($_POST['group_info_uid']));

		$union = "";
		while($row = $sth->fetch(PDO::FETCH_ASSOC)){
			if($union != ""){
				$union .= " UNION ";
			}

			if(DB_IS_MSSQL){
				$union .= "SELECT '" . $row["Username"] . "' AS AccountUsername, * FROM " . $row["Username"] . ".dbo.module";
			}
			else if(DB_IS_MYSQL){
				$union .= "SELECT '" . $row["Username"] . "' AS AccountUsername, " . $row["Username"] . ".module.* FROM " . $row["Username"] . ".module";
			}
			else if(DB_IS_ORACLE){
				$union .= "SELECT '" . $row["Username"] . "' AS AccountUsername, " . $row["Username"] . ".module.* FROM " . $row["Username"] . ".module";
			}
		}
		$sth->closeCursor();

		if($union != ""){
			if(DB_IS_MSSQL){
				$sql  = "SELECT Base.AccountUID, Base.Username AS AccountUsername, Base.DeviceUID, Base.ModuleUID, Base.Loop, ModuleInfo.Phase ";
				$sql .= "FROM(SELECT AccountUID, Username, DeviceUID, ModuleUID, Loop FROM group_data, manager.dbo.account WHERE group_data.GroupInfoUID = ? AND group_data.AccountUID = manager.dbo.account.UID) AS Base ";
				$sql .= "LEFT JOIN(" . trim($union) . ") AS ModuleInfo ";
				$sql .= "ON Base.Username = ModuleInfo.AccountUsername AND Base.DeviceUID = ModuleInfo.DeviceUID AND Base.ModuleUID = ModuleInfo.UID ";
				$sql .= "ORDER BY AccountUID, Base.DeviceUID, ModuleUID, ModuleInfo.Loop";
			}
			else if(DB_IS_MYSQL){
				$sql  = "SELECT Base.AccountUID, Base.Username AS AccountUsername, Base.DeviceUID, Base.ModuleUID, Base.Loop, ModuleInfo.Phase ";
				$sql .= "FROM(SELECT AccountUID, Username, DeviceUID, ModuleUID, `Loop` FROM group_data, manager.account WHERE group_data.GroupInfoUID = ? AND group_data.AccountUID = manager.account.UID) AS Base ";
				$sql .= "LEFT JOIN(" . trim($union) . ") AS ModuleInfo ";
				$sql .= "ON Base.Username = ModuleInfo.AccountUsername AND Base.DeviceUID = ModuleInfo.DeviceUID AND Base.ModuleUID = ModuleInfo.UID ";
				$sql .= "ORDER BY AccountUID, Base.DeviceUID, ModuleUID, ModuleInfo.Loop";
			}
			else if(DB_IS_ORACLE){
				$sql  = "SELECT Base.AccountUID, Base.Username AS AccountUsername, Base.DeviceUID, Base.ModuleUID, Base.Loop, ModuleInfo.Phase ";
				$sql .= "FROM(SELECT AccountUID, Username, DeviceUID, ModuleUID, Loop FROM group_data, manager.account WHERE group_data.GroupInfoUID = ? AND group_data.AccountUID = manager.account.\"UID\") Base ";
				$sql .= "LEFT JOIN(" . trim($union) . ") ModuleInfo ";
				$sql .= "ON Base.Username = ModuleInfo.AccountUsername AND Base.DeviceUID = ModuleInfo.DeviceUID AND Base.ModuleUID = ModuleInfo.\"UID\" ";
				$sql .= "ORDER BY AccountUID, Base.DeviceUID, ModuleUID, ModuleInfo.Loop";
			}

			$sth = $dbh->prepare($sql);
			$sth->execute(array($_POST['group_info_uid']));

			$row_array = array();

			// Table exist check
			while($row = $sth->fetch(PDO::FETCH_ASSOC)){
				$sth2 = $dbh->prepare(
					DB_IS_MSSQL  ? "SELECT COUNT(*) AS Counter FROM " . $row["AccountUsername"] . ".INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_NAME = 'uid_" . $row["DeviceUID"] . "_" . $row["ModuleUID"] . "';" : (
					DB_IS_MYSQL  ? "SELECT COUNT(*) AS Counter FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_NAME = 'uid_" . $row["DeviceUID"] . "_" . $row["ModuleUID"] . "' AND TABLE_SCHEMA = '" . $row["AccountUsername"] . "';" : (
					DB_IS_ORACLE ? "SELECT COUNT(*) AS Counter FROM DBA_TABLES WHERE TABLE_NAME = UPPER('uid_" . $row["DeviceUID"] . "_" . $row["ModuleUID"] . "') AND OWNER = SYS_CONTEXT('USERENV', 'CURRENT_SCHEMA')" : null)));
				$sth2->execute();

				$row2 = $sth2->fetch(PDO::FETCH_ASSOC);
				$sth2->closeCursor();

				if($row2["Counter"] == 0){
					continue;
				}
				else{
					$row_array[] = $row;
				}
			}
			$sth->closeCursor();

			if(count($row_array) > 0){
				if($_POST['unit'] == "hour"){
					if(DB_IS_MSSQL){
						$concat = "FORMAT(DateTime, 'yyyy'), '-', FORMAT(DateTime, 'MM'), '-', FORMAT(DateTime, 'dd'), ' ', FORMAT(DateTime, 'HH'), ':00:00'";
						$group = "FORMAT(DateTime, 'yyyy'), FORMAT(DateTime, 'MM'), FORMAT(DateTime, 'dd'), FORMAT(DateTime, 'HH')";
					}
					else if(DB_IS_MYSQL){
						$concat = "DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%Y'), '-', DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%m'), '-', DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%d'), ' ', DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%H'), ':00:00'";
						$group = "DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%Y'), DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%m'), DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%d'), DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%H')";
					}
					else if(DB_IS_ORACLE){
						$concat = "TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'YYYY') || '-' || TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'MM') || '-' || TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'DD') || ' ' || TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'HH24') || ':00:00'";
						$group = "TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'YYYY'), TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'MM'), TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'DD'), TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'HH24')";
					}
				}
				else if($_POST['unit'] == "day"){
					if(DB_IS_MSSQL){
						$concat = "FORMAT(DateTime, 'yyyy'), '-', FORMAT(DateTime, 'MM'), '-', FORMAT(DateTime, 'dd'), ' ', '00:00:00'";
						$group = "FORMAT(DateTime, 'yyyy'), FORMAT(DateTime, 'MM'), FORMAT(DateTime, 'dd')";
					}
					else if(DB_IS_MYSQL){
						$concat = "DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%Y'), '-', DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%m'), '-', DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%d'), ' ', '00:00:00'";
						$group = "DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%Y'), DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%m'), DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%d')";
					}
					else if(DB_IS_ORACLE){
						$concat = "TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'YYYY') || '-' || TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'MM') || '-' || TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'DD') || ' ' || '00:00:00'";
						$group = "TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'YYYY'), TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'MM'), TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'DD')";
					}
				}
				else if($_POST['unit'] == "month"){
					if(DB_IS_MSSQL){
						$concat = "FORMAT(DateTime, 'yyyy'), '-', FORMAT(DateTime, 'MM'), '-01 00:00:00'";
						$group = "FORMAT(DateTime, 'yyyy'), FORMAT(DateTime, 'MM')";
					}
					else if(DB_IS_MYSQL){
						$concat = "DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%Y'), '-', DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%m'), '-01 00:00:00'";
						$group = "DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%Y'), DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%m')";
					}
					else if(DB_IS_ORACLE){
						$concat = "TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'YYYY') || '-' || TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'MM') || '-01 00:00:00'";
						$group = "TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'YYYY'), TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'MM')";
					}
				}
				else if($_POST['unit'] == "year"){
					if(DB_IS_MSSQL){
						$concat = "FORMAT(DateTime, 'yyyy'), '-01-01 00:00:00'";
						$group = "FORMAT(DateTime, 'yyyy')";
					}
					else if(DB_IS_MYSQL){
						$concat = "DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%Y'), '-01-01 00:00:00'";
						$group = "DATE_FORMAT(CONVERT_TZ(DateTime, '+00:00', '" . $_POST['timezone'] . "'), '%Y')";
					}
					else if(DB_IS_ORACLE){
						$concat = "TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'YYYY') || '-01-01 00:00:00'";
						$group = "TO_CHAR(FROM_TZ(DateTime, 'UTC') AT TIME ZONE '" . $_POST['timezone'] . "', 'YYYY')";
					}
				}

				$sql = "SELECT DateTime, SUM(Result) AS Result FROM(";

				$prev_uid = "";
				foreach ($row_array as $row){
					$uid = 
						DB_IS_MSSQL  ? $row["AccountUsername"] . ".dbo." . "uid_" . $row["DeviceUID"] . "_" . $row["ModuleUID"] : (
						DB_IS_MYSQL  ? $row["AccountUsername"] . "." . "uid_" . $row["DeviceUID"] . "_" . $row["ModuleUID"] : (
						DB_IS_ORACLE ? $row["AccountUsername"] . "." . "uid_" . $row["DeviceUID"] . "_" . $row["ModuleUID"] : null));

					if($prev_uid != "" && $uid != $prev_uid){
						if(DB_IS_MSSQL){
							$sql .= "SELECT FORMAT(TODATETIMEOFFSET(CONCAT(" . $concat . "), '" . $_POST['timezone'] . "'), 'yyyy-MM-dd HH:mm:sszzzz') AS DateTime, Phase, " . $_POST['oper'] . "(" . ($_POST['type'] == "PF" ? "ABS(" . $_POST['type'] . ")" : $_POST['type']) . ") AS Result ";
		 					$sql .= "FROM (SELECT SWITCHOFFSET(DateTime, '" . $_POST['timezone'] . "') AS DateTime, Phase, " . $_POST['type'] . " FROM " . $prev_uid . " WHERE (" . rtrim($loop, " OR ") . ") AND Phase = " . ($prev_phase == 1 ? "1" : "4") . " AND DateTime >= '" . $_POST['begin'] . $_POST['timezone'] . "' AND DateTime <= '" . $_POST['end'] . $_POST['timezone'] . "') AS TempTable ";
							$sql .= "GROUP BY " . $group . ", Phase UNION ";
						}
						else if(DB_IS_MYSQL){
							$sql .= "SELECT DATE_FORMAT(CONVERT_TZ(STR_TO_DATE(CONCAT(" . $concat . "), '%Y-%m-%d %H:%i:%s'), '" . $_POST['timezone'] . "', '+00:00') , '%Y-%m-%d %H:%i:%s') AS DateTime, Phase, " . $_POST['oper'] . "(" . ($_POST['type'] == "PF" ? "ABS(" . $_POST['type'] . ")" : $_POST['type']) . ") AS Result ";
		 					$sql .= "FROM (SELECT DateTime AS DateTime, Phase, " . $_POST['type'] . " FROM " . $prev_uid . " WHERE (" . rtrim($loop, " OR ") . ") AND Phase = " . ($prev_phase == 1 ? "1" : "4") . " AND DateTime >= CONVERT_TZ(STR_TO_DATE('" . $_POST['begin'] . "', '%Y-%m-%d %H:%i:%s'), '" . $_POST['timezone'] . "', '+00:00') AND DateTime <= CONVERT_TZ(STR_TO_DATE('" . $_POST['end'] . "', '%Y-%m-%d %H:%i:%s'), '" . $_POST['timezone'] . "', '+00:00')) AS TempTable ";
							$sql .= "GROUP BY " . $group . ", Phase UNION ";
						}
						else if(DB_IS_ORACLE){
							$sql .= "SELECT TO_CHAR(TO_TIMESTAMP_TZ(" . $concat . " || '" . $_POST['timezone'] . "', 'YYYY-MM-DD HH24:MI:SSTZH:TZM'), 'YYYY-MM-DD HH24:MI:SSTZH:TZM') AS DateTime, Phase, " . $_POST['oper'] . "(" . ($_POST['type'] == "PF" ? "ABS(" . $_POST['type'] . ")" : $_POST['type']) . ") AS Result ";
		 					$sql .= "FROM (SELECT DateTime AS DateTime, Phase, " . $_POST['type'] . " FROM " . $prev_uid . " WHERE (" . rtrim($loop, " OR ") . ") AND Phase = " . ($prev_phase == 1 ? "1" : "4") . " AND DateTime >= TO_TIMESTAMP_TZ('" . $_POST['begin'] . $_POST['timezone'] . "', 'YYYY-MM-DD HH24:MI:SSTZH:TZM') AT TIME ZONE 'UTC' AND DateTime <= TO_TIMESTAMP_TZ('" . $_POST['end'] . $_POST['timezone'] . "', 'YYYY-MM-DD HH24:MI:SSTZH:TZM') AT TIME ZONE 'UTC') TempTable ";
							$sql .= "GROUP BY " . $group . ", Phase UNION ";
						}

						$loop = "";
					}

					$loop .= 
						DB_IS_MSSQL  ? "Loop = " . $row["Loop"] . " OR " : (
						DB_IS_MYSQL  ? "`Loop` = " . $row["Loop"] . " OR " : (
						DB_IS_ORACLE ? "Loop = " . $row["Loop"] . " OR " : null));
					$prev_uid = $uid;
					$prev_phase = $row["Phase"];
				}

				if(DB_IS_MSSQL){
					$sql .= "SELECT FORMAT(TODATETIMEOFFSET(CONCAT(" . $concat . "), '" . $_POST['timezone'] . "'), 'yyyy-MM-dd HH:mm:sszzzz') AS DateTime, Phase, " . $_POST['oper'] . "(" . ($_POST['type'] == "PF" ? "ABS(" . $_POST['type'] . ")" : $_POST['type']) . ") AS Result ";
					$sql .= "FROM (SELECT SWITCHOFFSET(DateTime, '" . $_POST['timezone'] . "') AS DateTime, Phase, " . $_POST['type'] . " FROM " . $prev_uid . " WHERE (" . rtrim($loop, " OR ") . ") AND Phase = " . ($prev_phase == 1 ? "1" : "4") . " AND DateTime >= '" . $_POST['begin'] . $_POST['timezone'] . "' AND DateTime <= '" . $_POST['end'] . $_POST['timezone'] . "') AS TempTable ";
					$sql .= "GROUP BY " . $group . ", Phase";
					$sql .= ") AS NewTable GROUP BY DateTime ORDER BY DateTime";
				}
				else if(DB_IS_MYSQL){
					$sql .= "SELECT DATE_FORMAT(CONVERT_TZ(STR_TO_DATE(CONCAT(" . $concat . "), '%Y-%m-%d %H:%i:%s'), '" . $_POST['timezone'] . "', '+00:00') , '%Y-%m-%d %H:%i:%s') AS DateTime, Phase, " . $_POST['oper'] . "(" . ($_POST['type'] == "PF" ? "ABS(" . $_POST['type'] . ")" : $_POST['type']) . ") AS Result ";
					$sql .= "FROM (SELECT DateTime AS DateTime, Phase, " . $_POST['type'] . " FROM " . $prev_uid . " WHERE (" . rtrim($loop, " OR ") . ") AND Phase = " . ($prev_phase == 1 ? "1" : "4") . " AND DateTime >= CONVERT_TZ(STR_TO_DATE('" . $_POST['begin'] . "', '%Y-%m-%d %H:%i:%s'), '" . $_POST['timezone'] . "', '+00:00') AND DateTime <= CONVERT_TZ(STR_TO_DATE('" . $_POST['end'] . "', '%Y-%m-%d %H:%i:%s'), '" . $_POST['timezone'] . "', '+00:00')) AS TempTable ";
					$sql .= "GROUP BY " . $group . ", Phase";
					$sql .= ") AS NewTable GROUP BY DateTime ORDER BY DateTime";
				}
				else if(DB_IS_ORACLE){
					$sql .= "SELECT TO_CHAR(TO_TIMESTAMP_TZ(" . $concat . " || '" . $_POST['timezone'] . "', 'YYYY-MM-DD HH24:MI:SSTZH:TZM'), 'YYYY-MM-DD HH24:MI:SSTZH:TZM') AS DateTime, Phase, " . $_POST['oper'] . "(" . ($_POST['type'] == "PF" ? "ABS(" . $_POST['type'] . ")" : $_POST['type']) . ") AS Result ";
					$sql .= "FROM (SELECT DateTime AS DateTime, Phase, " . $_POST['type'] . " FROM " . $prev_uid . " WHERE (" . rtrim($loop, " OR ") . ") AND Phase = " . ($prev_phase == 1 ? "1" : "4") . " AND DateTime >= TO_TIMESTAMP_TZ('" . $_POST['begin'] . $_POST['timezone'] . "', 'YYYY-MM-DD HH24:MI:SSTZH:TZM') AT TIME ZONE 'UTC' AND DateTime <= TO_TIMESTAMP_TZ('" . $_POST['end'] . $_POST['timezone'] . "', 'YYYY-MM-DD HH24:MI:SSTZH:TZM') AT TIME ZONE 'UTC') TempTable ";
					$sql .= "GROUP BY " . $group . ", Phase";
					$sql .= ") NewTable GROUP BY DateTime ORDER BY DateTime";
				}

				$sth = $dbh->prepare($sql);
				$sth->execute();

				while($row = $sth->fetch(PDO::FETCH_ASSOC)){
					$data[0]['data'][] = array(strtotime($row["DateTime"]), floatval($row["Result"]));
				}
				$sth->closeCursor();
			}
		}

		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}

	header('Content-type: application/json');
	echo json_encode($data);
}

function set_carbon_footprint_factor(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	if(!is_numeric($_POST["factor"]) || $_POST["factor"] <= 0){
		return;
	}

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . DB_NAME : (
			DB_IS_MYSQL  ? "USE " . DB_NAME : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
		$sth->execute();

		$sth = $dbh->prepare("UPDATE account SET CarbonFootprintFactor = ? WHERE UID = ?;");
		$sth->execute(array($_POST["factor"], $_SESSION["account_uid"]));

		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}
}
?>
