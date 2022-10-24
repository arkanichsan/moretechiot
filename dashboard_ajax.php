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

function get_dashboard(){
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

		$in = '<cmd type="dashboardList" account_uid="' . $_SESSION['account_uid'] . '" username="' . $_SESSION['username'] . '" />';
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
	print($out);
}

function add_dashboard(){
	global $login, $dashboard_permission;
	if($login == false || ($login == true && isset($dashboard_permission))){
		trap("Permission error!");
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><result/>');

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_MYSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $_SESSION["username"] : null)));
		$sth->execute();

		if($_POST["default"] == "1"){
			$sth = $dbh->prepare("UPDATE dashboard_info SET AsDefault = '0'");
			$sth->execute();
		}

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "INSERT INTO dashboard_info(Name, Lock, AsDefault, DataLength, DarkMode) VALUES(:name, :lock, :default, :data_length, :dark_mode)" : (
			DB_IS_MYSQL  ? "INSERT INTO dashboard_info(Name, `Lock`, AsDefault, DataLength, DarkMode) VALUES(:name, :lock, :default, :data_length, :dark_mode)" : (
			DB_IS_ORACLE ? "INSERT INTO dashboard_info(Name, \"LOCK\", AsDefault, DataLength, DarkMode) VALUES(:name, :lock, :default, :data_length, :dark_mode) RETURNING \"UID\" INTO :uid" : null)));

		$sth->bindValue(":name", $_POST["name"]);
		$sth->bindValue(":lock", $_POST["lock"]);
		$sth->bindValue(":default", $_POST["default"]);
		$sth->bindValue(":data_length", $_POST["data-length"]);
		$sth->bindValue(":dark_mode", $_POST["dark-mode"]);

		if(DB_IS_ORACLE){
			$sth->bindParam('uid', $uid, PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT, 32);
		}

		$sth->execute();

		if(DB_IS_MSSQL){
			$uid = $dbh->lastInsertId();
		}
		else if(DB_IS_MYSQL){
			$uid = $dbh->lastInsertId();
		}
		else if(DB_IS_ORACLE){
		}

		$xml->addAttribute('uid', $uid);
		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}

	header('Content-type: text/xml');
	print($xml->asXML());
}

function edit_dashboard(){
	global $login, $dashboard_permission;
	if($login == false || ($login == true && isset($dashboard_permission))){
		trap("Permission error!");
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><result/>');

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_MYSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $_SESSION["username"] : null)));
		$sth->execute();

		if($_POST["default"] == "1"){
			$sth = $dbh->prepare("UPDATE dashboard_info SET AsDefault = '0'");
			$sth->execute();
		}

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "UPDATE dashboard_info SET Name = ?, Lock = ?, AsDefault = ?, DataLength = ?, DarkMode = ? WHERE UID = ?" : (
			DB_IS_MYSQL  ? "UPDATE dashboard_info SET Name = ?, `Lock` = ?, AsDefault = ?, DataLength = ?, DarkMode = ? WHERE UID = ?" : (
			DB_IS_ORACLE ? "UPDATE dashboard_info SET Name = ?, \"LOCK\" = ?, AsDefault = ?, DataLength = ?, DarkMode = ? WHERE \"UID\" = ?" : null)));
		$sth->execute(array($_POST['name'], $_POST["lock"], $_POST["default"], $_POST["data-length"], $_POST["dark-mode"], $_POST['uid']));

		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}

	header('Content-type: text/xml');
	print($xml->asXML());
}

function remove_dashboard(){
	global $login, $dashboard_permission;
	if($login == false || ($login == true && isset($dashboard_permission))){
		trap("Permission error!");
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><result/>');

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_MYSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $_SESSION["username"] : null)));
		$sth->execute();

		$dbh->beginTransaction();

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "DELETE FROM dashboard_info WHERE UID = ?" : (
			DB_IS_MYSQL  ? "DELETE FROM dashboard_info WHERE UID = ?" : (
			DB_IS_ORACLE ? "DELETE FROM dashboard_info WHERE \"UID\" = ?" : null)));
		$sth->execute(array($_POST['uid']));

		$sth = $dbh->prepare("SELECT * FROM dashboard_widget WHERE InfoUID = ?");
		$sth->execute(array($_POST['uid']));

		while($row = $sth->fetch(PDO::FETCH_ASSOC)){
			$sth2 = $dbh->prepare("DELETE FROM dashboard_channel WHERE WidgetUID = ?");
			$sth2->execute(array($row["UID"]));
		}
		$sth->closeCursor();

		$sth = $dbh->prepare("DELETE FROM dashboard_widget WHERE InfoUID = ?");
		$sth->execute(array($_POST['uid']));

		$dbh->commit();
		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}

	header('Content-type: text/xml');
	print($xml->asXML());
}

function copy_dashboard(){
	global $login, $lang, $dashboard_permission;
	if($login == false || ($login == true && isset($dashboard_permission))){
		trap("Permission error!");
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><result/>');

	// Random table name
	$temp_table_name = "temp_copy_table_" . random_string(4);
	if(DB_IS_MSSQL){
		$temp_table_name = "##" . $temp_table_name;
	}
	else if(DB_IS_MYSQL){
	}
	else if(DB_IS_ORACLE){
	}

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_MYSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $_SESSION["username"] : null)));
		$sth->execute();

		$dbh->beginTransaction();
/*
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "SELECT * INTO " . $temp_table_name . " FROM dashboard_info WHERE 1 = 2" : (
			DB_IS_MYSQL  ? "CREATE TEMPORARY TABLE " . $temp_table_name . " AS (SELECT * FROM dashboard_info WHERE 1 = 2)" : (
			//DB_IS_ORACLE ? "CREATE GLOBAL TEMPORARY TABLE " . $temp_table_name . " ON COMMIT PRESERVE ROWS AS SELECT * FROM dashboard_info WHERE 1 = 2" : null)));// Failed on PHP, but success on Oracle SQL Developer
			DB_IS_ORACLE ? "CREATE TABLE " . $temp_table_name . " FOR EXCHANGE WITH TABLE dashboard_info" : null)));// Not temporary table
		$sth->execute();

		$sth = $dbh->prepare("INSERT INTO " . $temp_table_name . "(" . $info_column_names . ") SELECT " . $info_column_names . " FROM dashboard_info WHERE \"UID\" = ?");
		$sth->execute(array($_POST['uid']));

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "UPDATE " . $temp_table_name . " SET Name = Name + '(" . $lang['DASHBOARD']['COPY'] . ")', AsDefault = 0" : (
			DB_IS_MYSQL  ? "UPDATE " . $temp_table_name . " SET Name = Name + '(" . $lang['DASHBOARD']['COPY'] . ")', AsDefault = 0" : (
			DB_IS_ORACLE ? "UPDATE " . $temp_table_name . " SET Name = Name || '(" . $lang['DASHBOARD']['COPY'] . ")', AsDefault = 0" : null)));
		$sth->execute();

		$sth = $dbh->prepare("INSERT INTO dashboard_info(" . $info_column_names . ") SELECT " . $info_column_names . " FROM " . $temp_table_name);
		$sth->execute();
*/
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "SELECT * FROM dashboard_info WHERE UID = ?" : (
			DB_IS_MYSQL  ? "SELECT * FROM dashboard_info WHERE UID = ?" : (
			DB_IS_ORACLE ? "SELECT * FROM dashboard_info WHERE \"UID\" = ?" : null)));
		$sth->execute(array($_POST['uid']));

		$info_column_names = array();
		$info_row_params = array();
		$info_row_values = array();

		$row = $sth->fetchOriginal(PDO::FETCH_ASSOC);
		foreach ($row as $key => $value) {
			if(strtoupper($key) == "UID"){
				continue;
			}

			if(DB_IS_MSSQL){
				$info_column_names[] = "[" . $key . "]";
			}
			else if(DB_IS_MYSQL){
				$info_column_names[] = "`" . $key . "`";
			}
			else if(DB_IS_ORACLE){
				$info_column_names[] = "\"" . $key . "\"";
			}

			$info_row_params[] = ":" . $key;

			if(strtoupper($key) == "NAME"){
				$info_row_values[] = $value . "(" . $lang['DASHBOARD']['COPY'] . ")";
			}
			else{
				$info_row_values[] = $value;
			}
		}
		$sth->closeCursor();

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "INSERT INTO dashboard_info(" . implode(",", $info_column_names) . ") VALUES(" . implode(",", $info_row_params) . ")" : (
			DB_IS_MYSQL  ? "INSERT INTO dashboard_info(" . implode(",", $info_column_names) . ") VALUES(" . implode(",", $info_row_params) . ")" : (
			DB_IS_ORACLE ? "INSERT INTO dashboard_info(" . implode(",", $info_column_names) . ") VALUES(" . implode(",", $info_row_params) . ") RETURNING \"UID\" INTO :uid" : null)));

		foreach ($info_row_values as $key => $value){
			$sth->bindValue($info_row_params[$key], $value);
		}

		if(DB_IS_ORACLE){
			$sth->bindParam('uid', $dashboard_uid, PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT, 32);
		}

		$sth->execute();

		if(DB_IS_MSSQL){
			$dashboard_uid = $dbh->lastInsertId();
		}
		else if(DB_IS_MYSQL){
			$dashboard_uid = $dbh->lastInsertId();
		}
		else if(DB_IS_ORACLE){
		}

		$xml_dashboard = $xml->addChild('dashboard');
		$xml_dashboard->addAttribute('uid', $_POST['uid']);
		$xml_dashboard->addAttribute('new-uid', $dashboard_uid);

		$sth = $dbh->prepare("SELECT * FROM dashboard_widget WHERE InfoUID = ?");
		$sth->execute(array($_POST["uid"]));

		while($row = $sth->fetchOriginal(PDO::FETCH_ASSOC)){
			// Copy widget
			$widget_column_names = array();
			$widget_row_params = array();
			$widget_row_values = array();

			foreach ($row as $key => $value) {
				if(strtoupper($key) == "UID"){
					continue;
				}

				if(DB_IS_MSSQL){
					$widget_column_names[] = "[" . $key . "]";
				}
				else if(DB_IS_MYSQL){
					$widget_column_names[] = "`" . $key . "`";
				}
				else if(DB_IS_ORACLE){
					$widget_column_names[] = "\"" . $key . "\"";
				}

				$widget_row_params[] = ":" . $key;

				if(strtoupper($key) == "INFOUID"){
					$widget_row_values[] = $dashboard_uid;
				}
				else{
					$widget_row_values[] = $value;
				}
			}

			$sth2 = $dbh->prepare(
				DB_IS_MSSQL  ? "INSERT INTO dashboard_widget(" . implode(",", $widget_column_names) . ") VALUES(" . implode(",", $widget_row_params) . ")" : (
				DB_IS_MYSQL  ? "INSERT INTO dashboard_widget(" . implode(",", $widget_column_names) . ") VALUES(" . implode(",", $widget_row_params) . ")" : (
				DB_IS_ORACLE ? "INSERT INTO dashboard_widget(" . implode(",", $widget_column_names) . ") VALUES(" . implode(",", $widget_row_params) . ") RETURNING \"UID\" INTO :uid" : null)));

			foreach ($widget_row_values as $key => $value){
				$sth2->bindValue($widget_row_params[$key], $value);
			}

			if(DB_IS_ORACLE){
				$sth2->bindParam('uid', $widget_uid, PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT, 32);
			}

			$sth2->execute();

			if(DB_IS_MSSQL){
				$widget_uid = $dbh->lastInsertId();
			}
			else if(DB_IS_MYSQL){
				$widget_uid = $dbh->lastInsertId();
			}
			else if(DB_IS_ORACLE){
			}

			$xml_widget = $xml_dashboard->addChild('widget');
			$xml_widget->addAttribute('uid', $row["UID"]);
			$xml_widget->addAttribute('new-uid', $widget_uid);

			// Copy channel
			$sth2 = $dbh->prepare("SELECT * FROM dashboard_channel WHERE WidgetUID = ?");
			$sth2->execute(array($row["UID"]));

			while($row2 = $sth2->fetchOriginal(PDO::FETCH_ASSOC)){
				$channel_column_names = array();
				$channel_row_params = array();
				$channel_row_values = array();

				foreach ($row2 as $key => $value) {
					if(strtoupper($key) == "UID"){
						continue;
					}

					if(DB_IS_MSSQL){
						$channel_column_names[] = "[" . $key . "]";
					}
					else if(DB_IS_MYSQL){
						$channel_column_names[] = "`" . $key . "`";
					}
					else if(DB_IS_ORACLE){
						$channel_column_names[] = "\"" . $key . "\"";
					}

					$channel_row_params[] = ":" . $key;

					if(strtoupper($key) == "WIDGETUID"){
						$channel_row_values[] = $widget_uid;
					}
					else{
						$channel_row_values[] = $value;
					}
				}

				$sth3 = $dbh->prepare("INSERT INTO dashboard_channel(" . implode(",", $channel_column_names) . ") VALUES(" . implode(",", $channel_row_params) . ")");

				foreach ($channel_row_values as $key => $value){
					$sth3->bindValue($channel_row_params[$key], $value);
				}

				$sth3->execute();
			}
			$sth2->closeCursor();
		}
		$sth->closeCursor();

		$dbh->commit();
		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}

	header('Content-type: text/xml');
	print($xml->asXML());
}

function add_widget(){
	global $login, $dashboard_permission;
	if($login == false || ($login == true && isset($dashboard_permission))){
		trap("Permission error!");
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><result/>');

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_MYSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $_SESSION["username"] : null)));
		$sth->execute();

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "INSERT INTO dashboard_widget(InfoUID, X, Y, Width, Height, Setting) VALUES(:infoUID, :x, :y, :width, :height, :setting)" : (
			DB_IS_MYSQL  ? "INSERT INTO dashboard_widget(InfoUID, X, Y, Width, Height, Setting) VALUES(:infoUID, :x, :y, :width, :height, :setting)" : (
			DB_IS_ORACLE ? "INSERT INTO dashboard_widget(InfoUID, X, Y, Width, Height, Setting) VALUES(:infoUID, :x, :y, :width, :height, :setting) RETURNING \"UID\" INTO :uid" : null)));

		$sth->bindValue(":infoUID", $_POST["uid"]);
		$sth->bindValue(":x", 0);
		$sth->bindValue(":y", 0);
		$sth->bindValue(":width", 8);
		$sth->bindValue(":height", 4);
		$sth->bindValue(":setting", $_POST["settings"]);

		if(DB_IS_ORACLE){
			$sth->bindParam('uid', $uid, PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT, 32);
		}

		$sth->execute();

		if(DB_IS_MSSQL){
			$uid = $dbh->lastInsertId();
		}
		else if(DB_IS_MYSQL){
			$uid = $dbh->lastInsertId();
		}
		else if(DB_IS_ORACLE){
		}

		$xml->addAttribute('uid', $uid);

		if($_POST["channels"] != ""){
			$str_array = explode("@@", $_POST["channels"]);
			foreach($str_array as $value){
				list($account_uid, $device_uid, $module_uid, $channel, $order, $icon) = explode("||", $value);

				$loop = $phase = null;
				if (strpos($channel, '-') !== false) {// Power Meter
					list($loop, $phase, $channel) = explode("-", $channel);
				}

				// Permission check
				$account_username = null;

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
						$account_username = $row["Username"];
					}
					else{
						trap("Permission error!");
					}
				}
				else{
					$account_username = $_SESSION["username"];
				}

				$sth = $dbh->prepare(
					DB_IS_MSSQL  ? "USE " . $_SESSION["username"] : (
					DB_IS_MYSQL  ? "USE " . $_SESSION["username"] : (
					DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $_SESSION["username"] : null)));
				$sth->execute();

				// Channel exist check
				if($loop !== null && $phase !== null){// Power Meter
					$sth = $dbh->prepare(
						DB_IS_MSSQL  ? "SELECT COUNT(*) AS Counter FROM " . $account_username . ".dbo.uid_" . $device_uid . "_realtime WHERE ModuleUID = ? AND Loop = ? AND Phase = ? AND Channel = ?" : (
						DB_IS_MYSQL  ? "SELECT COUNT(*) AS Counter FROM " . $account_username . ".uid_" . $device_uid . "_realtime WHERE ModuleUID = ? AND `Loop` = ? AND Phase = ? AND Channel = ?" : (
						DB_IS_ORACLE ? "SELECT COUNT(*) AS Counter FROM " . $account_username . ".uid_" . $device_uid . "_realtime WHERE ModuleUID = ? AND Loop = ? AND Phase = ? AND Channel = ?" : null)));
					$sth->execute(array($module_uid, $loop, $phase, $channel));
				}
				else{
					$sth = $dbh->prepare(
						DB_IS_MSSQL  ? "SELECT COUNT(*) AS Counter FROM " . $account_username . ".dbo.uid_" . $device_uid . "_realtime WHERE ModuleUID = ? AND Loop IS NULL AND Phase IS NULL AND Channel = ?" : (
						DB_IS_MYSQL  ? "SELECT COUNT(*) AS Counter FROM " . $account_username . ".uid_" . $device_uid . "_realtime WHERE ModuleUID = ? AND `Loop` IS NULL AND Phase IS NULL AND Channel = ?" : (
						DB_IS_ORACLE ? "SELECT COUNT(*) AS Counter FROM " . $account_username . ".uid_" . $device_uid . "_realtime WHERE ModuleUID = ? AND Loop IS NULL AND Phase IS NULL AND Channel = ?" : null)));
					$sth->execute(array($module_uid, $channel));
				}

				$row = $sth->fetch(PDO::FETCH_ASSOC);
				$sth->closeCursor();

				if($row["Counter"] > 0){
					$sth = $dbh->prepare(
						DB_IS_MSSQL  ? "INSERT INTO dashboard_channel(WidgetUID, AccountUID, DeviceUID, ModuleUID, Loop, Phase, Channel, [Order], Icon) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)" : (
						DB_IS_MYSQL  ? "INSERT INTO dashboard_channel(WidgetUID, AccountUID, DeviceUID, ModuleUID, `Loop`, Phase, Channel, `Order`, Icon) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)" : (
						DB_IS_ORACLE ? "INSERT INTO dashboard_channel(WidgetUID, AccountUID, DeviceUID, ModuleUID, Loop, Phase, Channel, \"ORDER\", Icon) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)" : null)));
					$sth->execute(array($uid, $account_uid, $device_uid, $module_uid, $loop, $phase, $channel, $order, $icon != "" ? $icon : (DB_IS_ORACLE ? "-" : null)));
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

function edit_widget(){
	global $login, $dashboard_permission;
	if($login == false || ($login == true && isset($dashboard_permission))){
		trap("Permission error!");
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><result/>');

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_MYSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $_SESSION["username"] : null)));
		$sth->execute();

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "UPDATE dashboard_widget SET Setting = ? WHERE UID = ?" : (
			DB_IS_MYSQL  ? "UPDATE dashboard_widget SET Setting = ? WHERE UID = ?" : (
			DB_IS_ORACLE ? "UPDATE dashboard_widget SET Setting = ? WHERE \"UID\" = ?" : null)));
		$sth->execute(array($_POST["settings"], $_POST["uid"]));

		$sth = $dbh->prepare("DELETE FROM dashboard_channel WHERE WidgetUID = ?");
		$sth->execute(array($_POST["uid"]));

		if($_POST["channels"] != ""){
			$str_array = explode("@@", $_POST["channels"]);
			foreach($str_array as $value){
				list($account_uid, $device_uid, $module_uid, $channel, $order, $icon) = explode("||", $value);

				$loop = $phase = null;
				if (strpos($channel, '-') !== false) {// Power Meter
					list($loop, $phase, $channel) = explode("-", $channel);
				}

				// Permission check
				$account_username = null;

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
						$account_username = $row["Username"];
					}
					else{
						trap("Permission error!");
					}
				}
				else{
					$account_username = $_SESSION["username"];
				}

				$sth = $dbh->prepare(
					DB_IS_MSSQL  ? "USE " . $_SESSION["username"] : (
					DB_IS_MYSQL  ? "USE " . $_SESSION["username"] : (
					DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $_SESSION["username"] : null)));
				$sth->execute();

				// Channel exist check
				if($loop !== null && $phase !== null){// Power Meter
					$sth = $dbh->prepare(
						DB_IS_MSSQL  ? "SELECT COUNT(*) AS Counter FROM " . $account_username . ".dbo.uid_" . $device_uid . "_realtime WHERE ModuleUID = ? AND Loop = ? AND Phase = ? AND Channel = ?" : (
						DB_IS_MYSQL  ? "SELECT COUNT(*) AS Counter FROM " . $account_username . ".uid_" . $device_uid . "_realtime WHERE ModuleUID = ? AND `Loop` = ? AND Phase = ? AND Channel = ?" : (
						DB_IS_ORACLE ? "SELECT COUNT(*) AS Counter FROM " . $account_username . ".uid_" . $device_uid . "_realtime WHERE ModuleUID = ? AND Loop = ? AND Phase = ? AND Channel = ?" : null)));
					$sth->execute(array($module_uid, $loop, $phase, $channel));
				}
				else{
					$sth = $dbh->prepare(
						DB_IS_MSSQL  ? "SELECT COUNT(*) AS Counter FROM " . $account_username . ".dbo.uid_" . $device_uid . "_realtime WHERE ModuleUID = ? AND Loop IS NULL AND Phase IS NULL AND Channel = ?" : (
						DB_IS_MYSQL  ? "SELECT COUNT(*) AS Counter FROM " . $account_username . ".uid_" . $device_uid . "_realtime WHERE ModuleUID = ? AND `Loop` IS NULL AND Phase IS NULL AND Channel = ?" : (
						DB_IS_ORACLE ? "SELECT COUNT(*) AS Counter FROM " . $account_username . ".uid_" . $device_uid . "_realtime WHERE ModuleUID = ? AND Loop IS NULL AND Phase IS NULL AND Channel = ?" : null)));
					$sth->execute(array($module_uid, $channel));
				}

				$row = $sth->fetch(PDO::FETCH_ASSOC);
				$sth->closeCursor();

				if($row["Counter"] > 0){
					$sth = $dbh->prepare(
						DB_IS_MSSQL  ? "INSERT INTO dashboard_channel(WidgetUID, AccountUID, DeviceUID, ModuleUID, Loop, Phase, Channel, [Order], Icon) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)" : (
						DB_IS_MYSQL  ? "INSERT INTO dashboard_channel(WidgetUID, AccountUID, DeviceUID, ModuleUID, `Loop`, Phase, Channel, `Order`, Icon) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)" : (
						DB_IS_ORACLE ? "INSERT INTO dashboard_channel(WidgetUID, AccountUID, DeviceUID, ModuleUID, Loop, Phase, Channel, \"ORDER\", Icon) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)" : null)));
					$sth->execute(array($_POST["uid"], $account_uid, $device_uid, $module_uid, $loop, $phase, $channel, $order, $icon != "" ? $icon : (DB_IS_ORACLE ? "-" : null)));
				}
			}
		}

		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}

	$xml->addAttribute('uid', $_POST["uid"]);

	header('Content-type: text/xml');
	print($xml->asXML());
}

function update_widget_position(){
	global $login, $dashboard_permission;
	if($login == false || ($login == true && isset($dashboard_permission))){
		trap("Permission error!");
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><result/>');

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_MYSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $_SESSION["username"] : null)));
		$sth->execute();

		$str_array = explode(",", $_POST['data']);
		foreach ($str_array as $value){
			list($uid, $x, $y, $width, $height) = explode("|", $value);

			$sth = $dbh->prepare(
				DB_IS_MSSQL  ? "UPDATE dashboard_widget SET X = ?, Y = ?, Width = ?, Height = ? WHERE UID = ?" : (
				DB_IS_MYSQL  ? "UPDATE dashboard_widget SET X = ?, Y = ?, Width = ?, Height = ? WHERE UID = ?" : (
				DB_IS_ORACLE ? "UPDATE dashboard_widget SET X = ?, Y = ?, Width = ?, Height = ? WHERE \"UID\" = ?" : null)));
			$sth->execute(array($x, $y, $width, $height, $uid));
		}

		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}

	header('Content-type: text/xml');
	print($xml->asXML());
}

function remove_widget(){
	global $login, $dashboard_permission;
	if($login == false || ($login == true && isset($dashboard_permission))){
		trap("Permission error!");
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><result/>');

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_MYSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $_SESSION["username"] : null)));
		$sth->execute();

		$dbh->beginTransaction();

		$sth = $dbh->prepare(
				DB_IS_MSSQL  ? "DELETE FROM dashboard_widget WHERE UID = ?" : (
				DB_IS_MYSQL  ? "DELETE FROM dashboard_widget WHERE UID = ?" : (
				DB_IS_ORACLE ? "DELETE FROM dashboard_widget WHERE \"UID\" = ?" : null)));
		$sth->execute(array($_POST['uid']));

		$sth = $dbh->prepare("DELETE FROM dashboard_channel WHERE WidgetUID = ?");
		$sth->execute(array($_POST['uid']));

		$dbh->commit();
		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}

	header('Content-type: text/xml');
	print($xml->asXML());
}

function get_device_list(){
	global $login, $dashboard_permission;
	if($login == false || ($login == true && isset($dashboard_permission))){
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
	    	$device = $xml->addChild('device');
			$device->addAttribute('uid', $row["UID"]);
			$device->addAttribute('model_name', $row["ModelName"]);
			$device->addAttribute('nickname', $row["Nickname"]);
			$device->addAttribute('online', $row["Online"]);

			$device->addAttribute('account_uid', $_SESSION["account_uid"]);

			// Serach admin password
			$result = $xml_user_device->xpath("/cmd/module[@serial_number='" . $row["UID"] . "'][@status='1'][not(@account_uid)]");
			if(count($result) > 0){
				$device->addAttribute('admin_password', $result[0]['admin_password']);
			}
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
		    	$device = $xml->addChild('device');
				$device->addAttribute('uid', $row2["UID"]);
				$device->addAttribute('model_name', $row2["ModelName"]);
				$device->addAttribute('nickname', $row2["Nickname"]);
				$device->addAttribute('online', $row2["Online"]);

				$device->addAttribute('account_uid', $row["UID"]);
				$device->addAttribute('account_username', $row["Username"]);
				$device->addAttribute('account_nickname', $row["Nickname"]);
			}
			$sth2->closeCursor();
		}
		$sth->closeCursor();

		// Close connection
		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}

	header('Content-type: text/xml');
	print($xml->asXML());
}

function get_module_list(){
	global $login, $dashboard_permission;
	if($login == false || ($login == true && isset($dashboard_permission))){
		trap("Permission error!");
	}

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . DB_NAME : (
			DB_IS_MYSQL  ? "USE " . DB_NAME : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
		$sth->execute();

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

		$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><list/>');

		// Select channel
		$sth = $dbh->prepare("SELECT * FROM module WHERE DeviceUID = ?");
		$sth->execute(array($_POST['device_uid']));

		while($row = $sth->fetch(PDO::FETCH_ASSOC)){
			$module = $xml->addChild('module');
			$module->addAttribute('uid', $row["UID"]);
			$module->addAttribute('interface', $row["Interface"]);
			$module->addAttribute('number', $row["Number"]);
			$module->addAttribute('manufacturer', $row["Manufacturer"]);
			$module->addAttribute('model_name', $row["ModelName"]);
			$module->addAttribute('nickname', $row["Nickname"]);
			$module->addAttribute('type', $row["Type"]);
			$module->addAttribute('loop', $row["Loop"]);
			$module->addAttribute('phase', $row["Phase"]);

			$sth2 = $dbh->prepare("SELECT * FROM channel WHERE DeviceUID = ? AND ModuleUID = ?");
			$sth2->execute(array($_POST['device_uid'], $row["UID"]));

			if($row["Channel"] != NULL){
				while($row2 = $sth2->fetch(PDO::FETCH_ASSOC)){
			    	$channel = $module->addChild('channel');
					$channel->addAttribute('name', $row2["Channel"]);
					$channel->addAttribute('nickname', $row2["Nickname"]);
					$channel->addAttribute('unit', $row2["Unit"]);
				}
				$sth2->closeCursor();
			}
			else if($row["Loop"] !== NULL && $row["Phase"] !== NULL){// For power meter
				$nickname = array();

				while($row2 = $sth2->fetch(PDO::FETCH_ASSOC)){
					if(!array_key_exists($row2["Loop"], $nickname)){
						$nickname[$row2["Loop"]] = array();
					}

					$nickname[$row2["Loop"] - 1][$row2["Phase"] - 1] = $row2["Nickname"];
				}
				$sth2->closeCursor();

				for($loop_index = 0; $loop_index < $row["Loop"]; $loop_index++){
					$loop = $module->addChild('loop');
					$loop->addAttribute('number', $loop_index + 1);

					for($phase_index = 0; $phase_index < ($row["Phase"] >= 3 ? 4 : 1); $phase_index++){
						$phase = $loop->addChild('phase');
						$phase->addAttribute('number', $phase_index + 1);
						$phase->addAttribute('nickname', $nickname[$loop_index][$phase_index]);

						$phase->addChild('channel')->addAttribute('name', "V");
						$phase->addChild('channel')->addAttribute('name', "I");
						$phase->addChild('channel')->addAttribute('name', "KW");
						$phase->addChild('channel')->addAttribute('name', "KVAR");
						$phase->addChild('channel')->addAttribute('name', "KVA");
						$phase->addChild('channel')->addAttribute('name', "PF");
						$phase->addChild('channel')->addAttribute('name', "KWH");
						$phase->addChild('channel')->addAttribute('name', "KVARH");
						$phase->addChild('channel')->addAttribute('name', "KVAH");

						if($row["Phase"] == 1){
							$loop["nickname"] = $nickname[$loop_index][$phase_index];
						}
					}
				}
			}
		}
		$sth->closeCursor();

		$sth = $dbh->prepare("SELECT * FROM uid_" . $_POST["device_uid"] . "_realtime");
		$sth->execute();

		while($row = $sth->fetch(PDO::FETCH_ASSOC)){
			if($row["Loop"] === NULL && $row["Phase"] === NULL){
				$channel = $xml->xpath("/list/module[@uid='" . $row["ModuleUID"] . "']/channel[@name='" . $row["Channel"] . "']");

				if(count($channel) > 0){
					$channel[0]->addAttribute('available', "1");
				}
			}
			else{// For power meter
				$channel = $xml->xpath("/list/module[@uid='" . $row["ModuleUID"] . "']/loop[@number='" . $row["Loop"] . "']/phase[@number='" . $row["Phase"] . "']/channel[@name='" . $row["Channel"] . "']");

				if(count($channel) > 0){
					$channel[0]->addAttribute('available', "1");
				}
			}
		}
		$sth->closeCursor();

		// Close connection
		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}

	header('Content-type: text/xml');
	print($xml->asXML());
}

function get_data(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><list/>');

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);

		// Channel Data
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
						foreach ($module_obj as $channel => $channel_obj){
							$sql .= "(ModuleUID = ? ";
							array_push($params, $module_uid);

							$split_array = explode("-", $channel);
							if(count($split_array) == 3){
								$sql .= 
									DB_IS_MSSQL  ? "AND Loop = ? AND Phase = ? AND Channel = ?" : (
									DB_IS_MYSQL  ? "AND `Loop` = ? AND Phase = ? AND Channel = ?" : (
									DB_IS_ORACLE ? "AND Loop = ? AND Phase = ? AND Channel = ?" : null));

								array_push($params, $split_array[0], $split_array[1], $split_array[2]);
							}
							else{
								$sql .= "AND Channel = ?";
								array_push($params, $channel);
							}

							$sql .= ") OR ";
						}
					}

					// Query
					$sql = rtrim($sql, " OR ");

					$sth = $dbh->prepare($sql);
					$sth->execute($params);

					while($row = $sth->fetch(PDO::FETCH_ASSOC)){
						$data->$account_uid->$device_uid->{$row["ModuleUID"]}->{($row["Loop"] !== NULL && $row["Phase"] !== NULL ? $row["Loop"] . "-" . $row["Phase"] . "-" : "") . $row["Channel"]} = $row["Value"];
					}
					$sth->closeCursor();
				}
			}
		}

		$xml_data = $xml->addChild('data');
		$xml_data->addAttribute('type', "channel");
		foreach($data as $account_uid => $account_info){
			$xml_account = $xml_data->addChild('account');
			$xml_account->addAttribute('uid', $account_uid);

			foreach($account_info as $device_uid => $device_info){
				$xml_device = $xml_account->addChild('device');
				$xml_device->addAttribute('uid', $device_uid);

				foreach($device_info as $module_uid => $module_info){
					$xml_module = $xml_device->addChild('module');
					$xml_module->addAttribute('uid', $module_uid);

					foreach($module_info as $channel_name => $value){
						$xml_channel = $xml_module->addChild('channel');
						$xml_channel->addAttribute('name', $channel_name);
						$xml_channel[0] = $value;
					}
				}
			}
		}

		// Event Log
		$data = array();

		$data[$_SESSION["account_uid"]] = array(
			"username" => $_SESSION["username"],
			"device" => array()
		);

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_MYSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $_SESSION["username"] : null)));
		$sth->execute();

		$sth = $dbh->prepare("SELECT * FROM event_log WHERE EventCode LIKE '103%' ORDER BY DateTime DESC");
		$sth->execute();

		while($row = $sth->fetch(PDO::FETCH_ASSOC)){
			list($device_nickname, $device_uid, $message, $path) = explode("|$|", $row["Parameters"]);

			if(!array_key_exists($device_uid, $data[$_SESSION["account_uid"]]["device"])){
				$data[$_SESSION["account_uid"]]["device"][$device_uid] = array();
			}

			$data[$_SESSION["account_uid"]]["device"][$device_uid][] = array(
				"uid" => $row["UID"],
				"date_time" => $row["DateTime"],
				"event_code" => $row["EventCode"],
				"device_nickname" => $device_nickname,
				"message" => $message,
				"path" => $path
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
			if(!array_key_exists($row["AccountUIDShareBy"], $data)){
				$data[$row["AccountUIDShareBy"]] = array(
					"username" => $row["Username"],
					"device" => array()
				);
			}

			$sth2 = $dbh->prepare(
				DB_IS_MSSQL  ? "USE " . $row["Username"] : (
				DB_IS_MYSQL  ? "USE " . $row["Username"] : (
				DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $row["Username"] : null)));
			$sth2->execute();

			$sth2 = $dbh->prepare("SELECT * FROM event_log WHERE EventCode LIKE '103%' ORDER BY DateTime DESC");
			$sth2->execute();

			while($row2 = $sth2->fetch(PDO::FETCH_ASSOC)){
				list($device_nickname, $device_uid, $message, $path) = explode("|$|", $row2["Parameters"]);

				if(!array_key_exists($device_uid, $data[$row["AccountUIDShareBy"]]["device"])){
					$data[$row["AccountUIDShareBy"]]["device"][$device_uid] = array();
				}

				$data[$row["AccountUIDShareBy"]]["device"][$device_uid][] = array(
					"uid" => $row2["UID"],
					"date_time" => $row2["DateTime"],
					"event_code" => $row2["EventCode"],
					"device_nickname" => $device_nickname,
					"message" => $message,
					"path" => $path
				);
			}
			$sth2->closeCursor();
		}
		$sth->closeCursor();

		$xml_data = $xml->addChild('data');
		$xml_data->addAttribute('type', "event");
		foreach($data as $account_uid => $account_info){
			$xml_account = $xml_data->addChild('account');
			$xml_account->addAttribute('uid', $account_uid);

			foreach($account_info["device"] as $device_uid => $device_info){
				$xml_device = $xml_account->addChild('device');
				$xml_device->addAttribute('uid', $device_uid);

				foreach($device_info as $event_info){
					$xml_log = $xml_device->addChild('log');
					$xml_log->addAttribute('uid', $event_info["uid"]);
					$xml_log->addAttribute('datetime', date_format(date_create($event_info["date_time"]), 'Y/m/d H:i:s'));
					$xml_log->addAttribute('type', $event_info["event_code"] == "10301" ? "1" : "2");
					$xml_log->addAttribute('device_nickname', $event_info["device_nickname"]);
					$xml_log->addAttribute('path', $event_info["path"]);
					$xml_log[0] = $event_info["message"];
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
	global $login, $dashboard_permission;
	if($login == false || ($login == true && isset($dashboard_permission) && $dashboard_permission == 0)){
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

function add_link(){
	global $login, $dashboard_permission;
	if($login == false || ($login == true && isset($dashboard_permission))){
		trap("Permission error!");
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><result/>');

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . DB_NAME : (
			DB_IS_MYSQL  ? "USE " . DB_NAME : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
		$sth->execute();

		$token = generate_random_string(8);

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "INSERT INTO dashboard_link(Token, AccountUID) VALUES(?, ?)" : (
			DB_IS_MYSQL  ? "INSERT INTO dashboard_link(Token, AccountUID) VALUES(?, ?)" : (
			DB_IS_ORACLE ? "INSERT INTO dashboard_link(Token, AccountUID) VALUES(?, ?)" : null)));
		$sth->execute(array($token, $_SESSION["account_uid"]));

		$xml->addAttribute('token', $token);
		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}

	header('Content-type: text/xml');
	print($xml->asXML());
}

function edit_link(){
	global $login, $dashboard_permission;
	if($login == false || ($login == true && isset($dashboard_permission))){
		trap("Permission error!");
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><result/>');

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . DB_NAME : (
			DB_IS_MYSQL  ? "USE " . DB_NAME : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
		$sth->execute();

		if(isset($_POST["permission"])){
			$sth = $dbh->prepare(
				DB_IS_MSSQL  ? "UPDATE dashboard_link SET Permission = ? WHERE Token = ? AND AccountUID = ?" : (
				DB_IS_MYSQL  ? "UPDATE dashboard_link SET Permission = ? WHERE Token = ? AND AccountUID = ?" : (
				DB_IS_ORACLE ? "UPDATE dashboard_link SET Permission = ? WHERE Token = ? AND AccountUID = ?" : null)));
			$sth->execute(array($_POST['permission'], $_POST['token'], $_SESSION["account_uid"]));
		}

		if(isset($_POST["expiration_date"])){
			if(DB_IS_MSSQL){
				$expiration_date = $_POST['expiration_date'] == "" ? null : $_POST['expiration_date'];
			}
			else if(DB_IS_MYSQL){
				$expiration_date = $_POST['expiration_date'] == "" ? null : $_POST['expiration_date'];
			}
			else if(DB_IS_ORACLE){
				$expiration_date = $_POST['expiration_date'] == "" ? null : "TO_TIMESTAMP('" . $_POST['expiration_date'] . "', 'YYYY-MM-DD HH24:MI:SS')";
			}

			$sth = $dbh->prepare(
				DB_IS_MSSQL  ? "UPDATE dashboard_link SET ExpirationDate = ? WHERE Token = ? AND AccountUID = ?" : (
				DB_IS_MYSQL  ? "UPDATE dashboard_link SET ExpirationDate = ? WHERE Token = ? AND AccountUID = ?" : (
				DB_IS_ORACLE ? "UPDATE dashboard_link SET ExpirationDate = ? WHERE Token = ? AND AccountUID = ?" : null)));
			$sth->execute(array($expiration_date, $_POST['token'], $_SESSION["account_uid"]));
		}

		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}

	header('Content-type: text/xml');
	print($xml->asXML());
}

function remove_link(){
	global $login, $dashboard_permission;
	if($login == false || ($login == true && isset($dashboard_permission))){
		trap("Permission error!");
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><result/>');

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . DB_NAME : (
			DB_IS_MYSQL  ? "USE " . DB_NAME : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
		$sth->execute();

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "DELETE FROM dashboard_link WHERE Token = ? AND AccountUID = ?" : (
			DB_IS_MYSQL  ? "DELETE FROM dashboard_link WHERE Token = ? AND AccountUID = ?" : (
			DB_IS_ORACLE ? "DELETE FROM dashboard_link WHERE Token = ? AND AccountUID = ?" : null)));
		$sth->execute(array($_POST['token'], $_SESSION["account_uid"]));

		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}

	header('Content-type: text/xml');
	print($xml->asXML());
}

function generate_random_string($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $characters_length = strlen($characters);
    $random_string = '';
    for ($i = 0; $i < $length; $i++) {
        $random_string .= $characters[rand(0, $characters_length - 1)];
    }
    return $random_string;
}
?>