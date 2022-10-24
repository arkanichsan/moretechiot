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

	$xml_user_device = simplexml_load_string($out);

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
			$module->addAttribute('online', $row["Online"]);

			$module->addAttribute('account_uid', $_SESSION["account_uid"]);

			// Serach admin password
			$result = $xml_user_device->xpath("/cmd/module[@serial_number='" . $row["UID"] . "'][@status='1'][not(@account_uid)]");
			if(count($result) > 0){
				$module->addAttribute('admin_password', $result[0]['admin_password']);
			}
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
				$module->addAttribute('online', $row2["Online"]);

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

		while($row = $sth->fetch(PDO::FETCH_ASSOC)){
	    	$module = $xml->addChild('module');
			$module->addAttribute('uid', $row["UID"]);
			$module->addAttribute('interface', $row["Interface"]);
			$module->addAttribute('number', $row["Number"]);
			$module->addAttribute('model_name', $row["ModelName"]);
			$module->addAttribute('nickname', $row["Nickname"]);
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

		$sth = $dbh->prepare("SELECT * FROM uid_" . $_POST["device_uid"] . "_realtime WHERE ModuleUID = ?");
		$sth->execute(array($_POST['module_uid']));

		while($row = $sth->fetch(PDO::FETCH_ASSOC)){
			$channel = $xml->xpath("/list/channel[@name='" . $row["Channel"] . "']");
			if(count($channel) > 0){
				$channel[0]->addAttribute('available', "1");
			}
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

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><list/>');

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);

		$data = json_decode($_POST["data"]);
		foreach($data as $account_uid => $account_obj){
			if($account_uid != $_SESSION["account_uid"]){// shared
				$sth = $dbh->prepare(
					DB_IS_MSSQL  ? "USE " . DB_NAME : (
					DB_IS_MYSQL  ? "USE " . DB_NAME : (
					DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
				$sth->execute();

				$sth = $dbh->prepare(
					DB_IS_MSSQL  ? "SELECT * FROM share, account WHERE share.AccountUIDShareBy = ? AND share.AccountUIDShareTo = ? AND share.AccountUIDShareBy = account.UID" : (
					DB_IS_MYSQL  ? "SELECT * FROM share, account WHERE share.AccountUIDShareBy = ? AND share.AccountUIDShareTo = ? AND share.AccountUIDShareBy = account.UID" : (
					DB_IS_ORACLE ? "SELECT * FROM \"SHARE\", account WHERE \"SHARE\".AccountUIDShareBy = ? AND \"SHARE\".AccountUIDShareTo = ? AND \"SHARE\".AccountUIDShareBy = account.\"UID\"" : null)));
				$sth->execute(array($account_uid, $_SESSION["account_uid"]));

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

			foreach($account_obj as $device_uid => $device_obj){
				$sth = $dbh->prepare(
					DB_IS_MSSQL  ? "SELECT COUNT(*) AS Counter FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_NAME = 'uid_" . $device_uid . "_realtime'" : (
					DB_IS_MYSQL  ? "SELECT COUNT(*) AS Counter FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_NAME = 'uid_" . $device_uid . "_realtime' AND TABLE_SCHEMA = DATABASE()" : (
					DB_IS_ORACLE ? "SELECT COUNT(*) AS Counter FROM DBA_TABLES WHERE TABLE_NAME = UPPER('uid_" . $device_uid . "_realtime') AND OWNER = SYS_CONTEXT('USERENV', 'CURRENT_SCHEMA')" : null)));
				$sth->execute();

				$row = $sth->fetch(PDO::FETCH_ASSOC);
				$sth->closeCursor();

				if($row["Counter"] > 0){
					$sql = "SELECT * FROM uid_" . $device_uid . "_realtime WHERE ";
					$params = array();

					foreach($device_obj as $module_uid => $module_obj){
						foreach ($module_obj as $channel){
							$sql .= "(ModuleUID = ? AND Channel = ?) OR ";
							array_push($params, $module_uid, $channel);
						}
					}

					// Query
					$sql = rtrim($sql, " OR ");

					$sth = $dbh->prepare($sql);
					$sth->execute($params);

					while($row = $sth->fetch(PDO::FETCH_ASSOC)){
				    	$channel = $xml->addChild('channel');
						$channel->addAttribute('account_uid', $account_uid);
						$channel->addAttribute('device_uid', $device_uid);
						$channel->addAttribute('module_uid', $row["ModuleUID"]);
						$channel->addAttribute('channel', $row["Channel"]);
						$channel->addAttribute('value', $row["Value"]);
					}
					$sth->closeCursor();
				}
			}
		}

		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}

	header('Content-type: text/xml');
	print($xml->asXML());
}

function set_channel_data(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><list/>');

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);

		if($_POST['account_uid'] != $_SESSION["account_uid"]){// shared
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

		// set value of channel
		$sth = $dbh->prepare("UPDATE uid_" . $_POST["device_uid"] . "_realtime SET Value = ? WHERE ModuleUID = ? AND Channel = ?");
		$sth->execute(array($_POST['value'], $_POST['module_uid'], $_POST['channel']));

		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}

	$xml->addAttribute('result', "OK");

	header('Content-type: text/xml');
	print($xml->asXML());
}
?>
