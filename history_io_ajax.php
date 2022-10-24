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
		$sth = $dbh->prepare("SELECT * FROM group_info WHERE Type = 0");
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
			DB_IS_MSSQL  ? "SELECT * FROM module WHERE DeviceUID = ? AND (Type = 0 OR Type = 2) AND Removed = 0 ORDER BY Number" : (
			DB_IS_MYSQL  ? "SELECT * FROM module WHERE DeviceUID = ? AND (Type = 0 OR Type = 2) AND Removed = 0 ORDER BY Number" : (
			DB_IS_ORACLE ? "SELECT * FROM module WHERE DeviceUID = ? AND (Type = 0 OR Type = 2) AND Removed = 0 ORDER BY \"NUMBER\"" : null)));
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
					$module->addAttribute('model_name', $row_array[$row["TableName"]]["ModelName"]);
					$module->addAttribute('nickname', $row_array[$row["TableName"]]["Nickname"]);
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
		$sth = $dbh->prepare("SELECT * FROM channel WHERE DeviceUID = ? AND ModuleUID = ?");
		$sth->execute(array($_POST['device_uid'], $_POST['module_uid']));

		while($row = $sth->fetch(PDO::FETCH_ASSOC)){
	    	$module = $xml->addChild('channel');
			$module->addAttribute('name', $row["Channel"]);
			$module->addAttribute('nickname', $row["Nickname"]);
			$module->addAttribute('unit', $row["Unit"]);
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

function get_group_data(){
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

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "SELECT * FROM group_data, manager.dbo.account WHERE GroupInfoUID = ? AND manager.dbo.account.UID = AccountUID" : (
			DB_IS_MYSQL  ? "SELECT * FROM group_data, manager.account WHERE GroupInfoUID = ? AND manager.account.UID = AccountUID" : (
			DB_IS_ORACLE ? "SELECT * FROM group_data, manager.account WHERE GroupInfoUID = ? AND manager.account.\"UID\" = AccountUID" : null)));
		$sth->execute(array($_POST["group_info_uid"]));

		while($row = $sth->fetch(PDO::FETCH_ASSOC)){
	    	$module = $xml->addChild('channel');

			// Account
			$module->addAttribute('account_uid', $row["AccountUID"]);

			// Device
			$sth2 = $dbh->prepare(
				DB_IS_MSSQL  ? "SELECT * FROM " . $row["Username"] . ".dbo.device WHERE UID = ?" : (
				DB_IS_MYSQL  ? "SELECT * FROM " . $row["Username"] . ".device WHERE UID = ?" : (
				DB_IS_ORACLE ? "SELECT * FROM " . $row["Username"] . ".device WHERE \"UID\" = ?" : null)));
			$sth2->execute(array($row["DeviceUID"]));

			$row2 = $sth2->fetch(PDO::FETCH_ASSOC);
			$sth2->closeCursor();

			$module->addAttribute('device_uid', $row["DeviceUID"]);
			$module->addAttribute('device_modelname', $row2["ModelName"]);
			$module->addAttribute('device_nickname', $row2["Nickname"]);

			// Module
			$sth2 = $dbh->prepare(
				DB_IS_MSSQL  ? "SELECT * FROM " . $row["Username"] . ".dbo.module WHERE UID = ?" : (
				DB_IS_MYSQL  ? "SELECT * FROM " . $row["Username"] . ".module WHERE UID = ?" : (
				DB_IS_ORACLE ? "SELECT * FROM " . $row["Username"] . ".module WHERE \"UID\" = ?" : null)));
			$sth2->execute(array($row["ModuleUID"]));

			$row2 = $sth2->fetch(PDO::FETCH_ASSOC);
			$sth2->closeCursor();

			$module->addAttribute('module_uid', $row["ModuleUID"]);
			$module->addAttribute('module_modelname', $row2["ModelName"]);//
			$module->addAttribute('module_nickname', $row2["Nickname"]);//

			// Channel
			$sth2 = $dbh->prepare(
				DB_IS_MSSQL  ? "SELECT * FROM " . $row["Username"] . ".dbo.channel WHERE DeviceUID = ? AND ModuleUID = ? AND Channel = ?" : (
				DB_IS_MYSQL  ? "SELECT * FROM " . $row["Username"] . ".channel WHERE DeviceUID = ? AND ModuleUID = ? AND Channel = ?" : (
				DB_IS_ORACLE ? "SELECT * FROM " . $row["Username"] . ".channel WHERE DeviceUID = ? AND ModuleUID = ? AND Channel = ?" : null)));
			$sth2->execute(array($row["DeviceUID"], $row["ModuleUID"], $row["Channel"]));

			$row2 = $sth2->fetch(PDO::FETCH_ASSOC);
			$sth2->closeCursor();

			$module->addAttribute('channel_name', $row2["Channel"]);
			$module->addAttribute('channel_nickname', $row2["Nickname"]);
			$module->addAttribute('channel_unit', $row2["Unit"]);
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

function get_channel_data(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	// Return data
	$data = array("data" => array());

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

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "SELECT COUNT(*) AS Counter FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_NAME = 'uid_" . $_POST['device_uid'] . "_" . $_POST['module_uid'] . "'" : (
			DB_IS_MYSQL  ? "SELECT COUNT(*) AS Counter FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_NAME = 'uid_" . $_POST['device_uid'] . "_" . $_POST['module_uid'] . "' AND TABLE_SCHEMA = DATABASE()" : (
			DB_IS_ORACLE ? "SELECT COUNT(*) AS Counter FROM DBA_TABLES WHERE TABLE_NAME = UPPER('uid_" . $_POST['device_uid'] . "_" . $_POST['module_uid'] . "') AND OWNER = SYS_CONTEXT('USERENV', 'CURRENT_SCHEMA')" : null)));
		$sth->execute();

		$row = $sth->fetch(PDO::FETCH_ASSOC);
		$sth->closeCursor();

		if($row["Counter"] > 0){
			if(DB_IS_MSSQL){
				$begin_datetime = "'" . $_POST['begin'] . " " . $_POST['timezone'] . "'";
				$end_datetime   = "'" . $_POST['end']   . " " . $_POST['timezone'] . "'";
			}
			else if(DB_IS_MYSQL){
				$begin_datetime = "CONVERT_TZ(STR_TO_DATE('" . $_POST['begin'] . "', '%Y-%m-%d %H:%i:%s'), '" . $_POST['timezone'] . "', '+00:00')";
				$end_datetime   = "CONVERT_TZ(STR_TO_DATE('" . $_POST['end'] . "', '%Y-%m-%d %H:%i:%s'), '" . $_POST['timezone'] . "', '+00:00')";
			}
			else if(DB_IS_ORACLE){
				$begin_datetime = "TO_TIMESTAMP_TZ('" . $_POST['begin'] . $_POST['timezone'] . "', 'YYYY-MM-DD HH24:MI:SSTZH:TZM') AT TIME ZONE 'UTC'";
				$end_datetime   = "TO_TIMESTAMP_TZ('" . $_POST['end'] . $_POST['timezone'] . "', 'YYYY-MM-DD HH24:MI:SSTZH:TZM') AT TIME ZONE 'UTC'";
			}

			$sql  = "SELECT DateTime, " . $_POST['channel'] . " AS Result FROM uid_" . $_POST['device_uid'] . "_" . $_POST['module_uid'] . " ";
			$sql .= "WHERE DateTime >= " . $begin_datetime . " AND DateTime <= " . $end_datetime . " AND " . $_POST['channel'] . " IS NOT NULL ";
			$sql .= "ORDER BY DateTime";

			$sth = $dbh->prepare($sql);
			$sth->execute();

			while($row = $sth->fetch(PDO::FETCH_ASSOC)){
				$data['data'][] = array(strtotime($row["DateTime"]), strval($row["Result"]));
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
?>
