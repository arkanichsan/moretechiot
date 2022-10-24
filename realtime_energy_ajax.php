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
			DB_IS_MSSQL  ? "SELECT * FROM module WHERE DeviceUID = ? AND Type = 1 AND Removed = 0 ORDER BY Number" : (
			DB_IS_MYSQL  ? "SELECT * FROM module WHERE DeviceUID = ? AND Type = 1 AND Removed = 0 ORDER BY Number" : (
			DB_IS_ORACLE ? "SELECT * FROM module WHERE DeviceUID = ? AND Type = 1 AND Removed = 0 ORDER BY \"NUMBER\"" : null)));
		$sth->execute(array($_POST['device_uid']));

		while($row = $sth->fetch(PDO::FETCH_ASSOC)){
	    	$module = $xml->addChild('module');
			$module->addAttribute('uid', $row["UID"]);
			$module->addAttribute('interface', $row["Interface"]);
			$module->addAttribute('number', $row["Number"]);
			$module->addAttribute('model_name', $row["ModelName"]);
			$module->addAttribute('nickname', $row["Nickname"]);
			$module->addAttribute('loop', $row["Loop"]);
			$module->addAttribute('phase', $row["Phase"]);
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

		$sth = $dbh->prepare("SELECT * FROM uid_" . $_POST["device_uid"] . "_realtime WHERE ModuleUID = ?");
		$sth->execute(array($_POST['module_uid']));

		while($row = $sth->fetch(PDO::FETCH_ASSOC)){
			$channel = $xml->xpath("/list/loop[@index='" . ($row["Loop"] - 1) . "']/phase[@index='" . ($row["Phase"] - 1) . "']");
			if(count($channel) > 0){
				$channel[0]->addChild(strtolower($row["Channel"]));
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

function get_module_data(){
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

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "SELECT COUNT(*) AS Counter FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_NAME = 'uid_" . $_POST["device_uid"] . "_realtime'" : (
			DB_IS_MYSQL  ? "SELECT COUNT(*) AS Counter FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_NAME = 'uid_" . $_POST["device_uid"] . "_realtime' AND TABLE_SCHEMA = DATABASE()" : (
			DB_IS_ORACLE ? "SELECT COUNT(*) AS Counter FROM DBA_TABLES WHERE TABLE_NAME = UPPER('uid_" . $_POST["device_uid"] . "_realtime') AND OWNER = SYS_CONTEXT('USERENV', 'CURRENT_SCHEMA')" : null)));
		$sth->execute();

		$row = $sth->fetch(PDO::FETCH_ASSOC);
		$sth->closeCursor();

		if($row["Counter"] > 0){
			$sth = $dbh->prepare(
				DB_IS_MSSQL  ? "SELECT * FROM uid_" . $_POST["device_uid"] . "_realtime WHERE ModuleUID = ? AND Loop = ?" : (
				DB_IS_MYSQL  ? "SELECT * FROM uid_" . $_POST["device_uid"] . "_realtime WHERE ModuleUID = ? AND `Loop` = ?" : (
				DB_IS_ORACLE ? "SELECT * FROM uid_" . $_POST["device_uid"] . "_realtime WHERE ModuleUID = ? AND Loop = ?" : null)));
			$sth->execute(array($_POST['module_uid'], $_POST["loop"]));

			while($row = $sth->fetch(PDO::FETCH_ASSOC)){
				$channel = $xml->xpath("/list/phase[@index='" . ($row["Phase"] - 1) . "']");
				if(count($channel) <= 0){
					$channel = $xml->addChild("phase");
					$channel->addAttribute('index', $row["Phase"] - 1);
				}
				else{
					$channel = $channel[0];
				}

				$channel->addChild(strtolower($row["Channel"]), $row["Value"]);
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
?>
