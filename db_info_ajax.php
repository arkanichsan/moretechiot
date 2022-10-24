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

function load_info(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}
	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
	
	//localDB
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT Username,Nickname FROM manager.dbo.account WHERE Username = '". $_SESSION["username"]."';" : (
		DB_IS_MYSQL  ? "SELECT Username,Nickname FROM manager.account WHERE Username = '". $_SESSION["username"]."';" : (
		DB_IS_ORACLE ? "SELECT Username,Nickname FROM manager.\"ACCOUNT\" WHERE Username = '". $_SESSION["username"]."';" : null)));
	$stmt->execute();
	
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><info/>');
	while($localdb_obj = $stmt->fetch(PDO::FETCH_ASSOC)){
		$localdb = $xml->addChild('localdb');
		$localdb->addAttribute('Name', $localdb_obj["Username"]);
		$localdb->addAttribute('Nickname', $localdb_obj["Nickname"]);
		//controller
		$stmt2 = $conn->prepare(
			DB_IS_MSSQL  ? "SELECT UID, ModelName,Nickname FROM ".$_SESSION["username"].".dbo.device;" : (
			DB_IS_MYSQL  ? "SELECT UID, ModelName,Nickname FROM ".$_SESSION["username"].".device;" : (
			DB_IS_ORACLE ? "SELECT \"UID\", ModelName,Nickname FROM ".$_SESSION["username"].".device;" : null))
		);
		$stmt2->execute();
		while($controller_obj = $stmt2->fetch(PDO::FETCH_ASSOC)){
			$controller = $localdb->addChild('controller');
			$controller->addAttribute('UID', $controller_obj["UID"]);
			$controller->addAttribute('ModelName', $controller_obj["ModelName"]);
			$controller->addAttribute('Nickname', $controller_obj["Nickname"]);
			
			//interface
			$stmt3 = $conn->prepare(
				DB_IS_MSSQL  ? "SELECT DISTINCT Interface FROM ".$_SESSION["username"].".dbo.module WHERE DeviceUID='".$controller_obj["UID"]."' AND Interface <> 'IR';" : (
				DB_IS_MYSQL  ? "SELECT DISTINCT Interface FROM ".$_SESSION["username"].".module WHERE DeviceUID='".$controller_obj["UID"]."' AND Interface <> 'IR';" : (
				DB_IS_ORACLE ? "SELECT DISTINCT Interface FROM ".$_SESSION["username"].".module WHERE DeviceUID='".$controller_obj["UID"]."' AND Interface <> 'IR';" : null))
			);
			$stmt3->execute();
			
			While($interface_obj = $stmt3->fetch(PDO::FETCH_ASSOC)){
				$Interface = $controller->addChild('Interface');
				$Interface->addAttribute("Interface",$interface_obj["Interface"]);
				
				$stmt4 = $conn->prepare(
					DB_IS_MSSQL  ? "SELECT ModelName, Nickname, UID AS ModuleUID, DeviceUID, Removed FROM ".$_SESSION["username"].".dbo.module WHERE DeviceUID='".$controller_obj["UID"]."' AND Interface='".$interface_obj["Interface"]."' ORDER BY Number;" : (
					DB_IS_MYSQL  ? "SELECT ModelName, Nickname, UID AS ModuleUID, DeviceUID, Removed FROM ".$_SESSION["username"].".module WHERE DeviceUID='".$controller_obj["UID"]."' AND Interface='".$interface_obj["Interface"]."' ORDER BY Number;" : (
					DB_IS_ORACLE ? "SELECT ModelName, Nickname, \"UID\" AS ModuleUID, DeviceUID, Removed FROM ".$_SESSION["username"].".module WHERE DeviceUID='".$controller_obj["UID"]."' AND Interface='".$interface_obj["Interface"]."' ORDER BY \"NUMBER\";" : null))
				);
				$stmt4->execute();
				
				While($module_obj = $stmt4->fetch(PDO::FETCH_ASSOC)){
					$Module = $Interface->addChild('Model');
					$Module->addAttribute("ModelName",$module_obj["ModelName"]);
					$Module->addAttribute("Nickname",$module_obj["Nickname"]);
					$Module->addAttribute("ModuleUID",$module_obj["ModuleUID"]);
					$Module->addAttribute("ControllerUID",$module_obj["DeviceUID"]);
					$Module->addAttribute("Removed",$module_obj["Removed"]);
				}
				$stmt4->closeCursor();
			}
			$stmt3->closeCursor();
		}
		$stmt2->closeCursor();
	}
	$stmt->closeCursor();
	
	//shareDB
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT AccountUIDShareBy FROM manager.dbo.share JOIN manager.dbo.account ON account.UID = share.AccountUIDShareTo WHERE Username = '". $_SESSION["username"]."';" : (
		DB_IS_MYSQL  ? "SELECT AccountUIDShareBy FROM manager.share JOIN manager.account ON account.UID = share.AccountUIDShareTo WHERE Username = '". $_SESSION["username"]."';" : (
		DB_IS_ORACLE ? "SELECT AccountUIDShareBy FROM MANAGER.\"SHARE\" JOIN MANAGER.\"ACCOUNT\" ON MANAGER.\"ACCOUNT\".\"UID\" = MANAGER.\"SHARE\".AccountUIDShareTo WHERE Username = '". $_SESSION["username"]."';" : null)));
	$stmt->execute();
	
	While($sharedbuid_obj = $stmt->fetch(PDO::FETCH_ASSOC)){
		$stmt2 = $conn->prepare(
			DB_IS_MSSQL  ? "Select Username,Nickname FROM manager.dbo.account WHERE UID = '".$sharedbuid_obj["AccountUIDShareBy"]."';" : (
			DB_IS_MYSQL  ? "Select Username,Nickname FROM manager.account WHERE UID = '".$sharedbuid_obj["AccountUIDShareBy"]."';" : (
			DB_IS_ORACLE ? "Select Username,Nickname FROM MANAGER.\"ACCOUNT\" WHERE \"UID\" = '".$sharedbuid_obj["AccountUIDShareBy"]."';" : null)));
		$stmt2->execute();
		
		While($sharedb_obj = $stmt2->fetch(PDO::FETCH_ASSOC)){
			$sharedb = $xml->addChild('sharedb');
			$sharedb->addAttribute('Name', $sharedb_obj["Username"]);
			$sharedb->addAttribute('Nickname', $sharedb_obj["Nickname"]);
			
			$stmt3 = $conn->prepare(
				DB_IS_MSSQL  ? "SELECT UID, ModelName,Nickname FROM ".$sharedb_obj["Username"].".dbo.device;" : (
				DB_IS_MYSQL  ? "SELECT UID, ModelName,Nickname FROM ".$sharedb_obj["Username"].".device;" : (
				DB_IS_ORACLE ? "SELECT \"UID\", ModelName,Nickname FROM ".$sharedb_obj["Username"].".device;" : null)));
			$stmt3->execute();
			
			while($controller_obj = $stmt3->fetch(PDO::FETCH_ASSOC)){
				$controller = $sharedb->addChild('controller');
				$controller->addAttribute('UID', $controller_obj["UID"]);
				$controller->addAttribute('ModelName', $controller_obj["ModelName"]);
				$controller->addAttribute('Nickname', $controller_obj["Nickname"]);
				//interface
				$stmt4 = $conn->prepare(
					DB_IS_MSSQL  ? "SELECT DISTINCT Interface FROM ".$sharedb_obj["Username"].".dbo.module WHERE DeviceUID='".$controller_obj["UID"]."' AND Interface <> 'IR';" : (
					DB_IS_MYSQL  ? "SELECT DISTINCT Interface FROM ".$sharedb_obj["Username"].".module WHERE DeviceUID='".$controller_obj["UID"]."' AND Interface <> 'IR';" : (
					DB_IS_ORACLE ? "SELECT DISTINCT Interface FROM ".$sharedb_obj["Username"].".module WHERE DeviceUID='".$controller_obj["UID"]."' AND Interface <> 'IR';" : null)));
				$stmt4->execute();
				
				
				While($interface_obj = $stmt4->fetch(PDO::FETCH_ASSOC)){
					$Interface = $controller->addChild('Interface');
					$Interface->addAttribute("Interface",$interface_obj["Interface"]);
					
					$stmt5 = $conn->prepare(
						DB_IS_MSSQL  ? "SELECT ModelName, Nickname,UID AS ModuleUID ,DeviceUID,Removed FROM ".$sharedb_obj["Username"].".dbo.module WHERE DeviceUID='".$controller_obj["UID"]."' AND Interface='".$interface_obj["Interface"]."' ORDER BY Number;" : (
						DB_IS_MYSQL  ? "SELECT ModelName, Nickname,UID AS ModuleUID ,DeviceUID,Removed FROM ".$sharedb_obj["Username"].".module WHERE DeviceUID='".$controller_obj["UID"]."' AND Interface='".$interface_obj["Interface"]."' ORDER BY Number;" : (
						DB_IS_ORACLE ? "SELECT ModelName, Nickname,\"UID\" AS ModuleUID ,DeviceUID,Removed FROM ".$sharedb_obj["Username"].".module WHERE DeviceUID='".$controller_obj["UID"]."' AND Interface='".$interface_obj["Interface"]."' ORDER BY \"NUMBER\";" : null)));

					$stmt5->execute();
					While($module_obj = $stmt5->fetch(PDO::FETCH_ASSOC)){
						$Module = $Interface->addChild('Model');
						$Module->addAttribute("ModelName",$module_obj["ModelName"]);
						$Module->addAttribute("Nickname",$module_obj["Nickname"]);
						$Module->addAttribute("ModuleUID",$module_obj["ModuleUID"]);
						$Module->addAttribute("ControllerUID",$module_obj["DeviceUID"]);
						$Module->addAttribute("Removed",$module_obj["Removed"]);
					}
					$stmt5->closeCursor();
				}
				$stmt4->closeCursor();
			}
			$stmt3->closeCursor();
		}
		$stmt2->closeCursor();
	}
	$stmt->closeCursor();

	$conn = null;
	header('Content-type: text/xml');
	print($xml->asXML());
}

function check_table(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}
	
	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT (CASE WHEN count(*) > 0 THEN 'true' ELSE 'false' END) AS ck_table FROM ".$_POST["dbname"].".sys.tables WHERE name = '".$_POST["table_name"]."';" : (
		DB_IS_MYSQL  ? "SELECT (CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END) AS ck_table FROM information_schema.tables WHERE TABLE_SCHEMA='".$_POST["dbname"]."' AND TABLE_NAME = '".$_POST["table_name"]."';" : (
		DB_IS_ORACLE ? "SELECT (CASE WHEN count(*) > 0 THEN 1 ELSE 0 END) AS ck_table FROM dba_tables WHERE OWNER = '".strtoupper($_POST["dbname"])."' AND TABLE_NAME = '".strtoupper($_POST["table_name"])."';" : null)));
	$stmt->execute();
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><info/>');
	
	While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
		$check_ir = $xml->addChild('check');
		$check_ir->addAttribute("ck_table",$obj["ck_table"]);
	}
	
	$stmt->closeCursor();
	$conn = null;
	
	header('Content-type: text/xml');
	print($xml->asXML());
}

function clear_database(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
	
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT UID, DeviceUID FROM ".$_SESSION["username"].".dbo.module;" : (
		DB_IS_MYSQL  ? "SELECT UID, DeviceUID FROM ".$_SESSION["username"].".module;" : (
		DB_IS_ORACLE ? "SELECT \"UID\", DeviceUID FROM ".$_SESSION["username"].".module;" : null))
	);
	$stmt->execute();
	
	// Prevent timeout
	set_time_limit(0);

	while($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
		
		$table_name = "uid_" . $obj["DeviceUID"] . "_" . $obj["UID"];
		//echo "DELETE FROM ".$_SESSION["username"]."." . $table_name . " WHERE DateTime <  DATE_ADD(NOW(),INTERVAL -36 MONTH);";
		$stmt2 = $conn->prepare(
			DB_IS_MSSQL  ? "IF EXISTS(SELECT * FROM ".$_SESSION["username"].".sys.tables WHERE name = '" . $table_name . "') DELETE FROM ".$_SESSION["username"].".dbo." . $table_name . " WHERE DateTime < DATEADD(MONTH, ".intval($_POST["time"]).", GETDATE());" : (
			DB_IS_MYSQL  ? "DELETE FROM ".$_SESSION["username"]."." . $table_name . " WHERE DateTime <  DATE_ADD(NOW(),INTERVAL ".intval($_POST["time"])." MONTH);" : (
			DB_IS_ORACLE ? "DELETE FROM ".$_SESSION["username"]."." . $table_name . " WHERE DateTime <  ADD_MONTHS(SYSDATE, ".intval($_POST["time"]).");" : null)));
		$stmt2->execute();
		$stmt2->closeCursor();
	}
	$stmt->closeCursor();


	// Event log
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "INSERT INTO ".$_SESSION["username"].".dbo.event_log(EventCode) VALUES(10114);" : (
		DB_IS_MYSQL  ? "INSERT INTO ".$_SESSION["username"].".event_log(EventCode) VALUES(10114);" : (
		DB_IS_ORACLE ? "INSERT INTO ".$_SESSION["username"].".event_log(EventCode) VALUES(10114);" : null))
	);
	
	$stmt->execute();
	$stmt->closeCursor();
	
	$conn = null;
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><info/>');
	$module = $xml->addChild('clear');
	$module->addAttribute('reply', "OK");
	header('Content-type: text/xml');
	print($xml->asXML());
}

function clear_table(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);

	// For event log
	$table_info = explode("_", $_POST["table_name"]);
	$device_sn = $table_info[1];
	$module_uid = $table_info[2];
	
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT * FROM ".$_SESSION["username"].".dbo.device WHERE UID = ?" : (
		DB_IS_MYSQL  ? "SELECT * FROM ".$_SESSION["username"].".device WHERE UID = ?" : (
		DB_IS_ORACLE ? "SELECT * FROM ".$_SESSION["username"].".device WHERE \"UID\" = ?" : null))
	);
	$stmt->execute(array($device_sn));
	
	$obj = $stmt->fetch(PDO::FETCH_ASSOC);
	$stmt->closeCursor();

	$device_modelname = $obj["ModelName"];
	$device_nickname = $obj["Nickname"];
	if(!empty($device_nickname)){
		$device_name = $device_modelname . "(" . $device_nickname . ")";
	}
	else{
		$device_name = $device_modelname;
	}

	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT * FROM ".$_SESSION["username"].".dbo.module WHERE DeviceUID = ? AND UID = ?" : (
		DB_IS_MYSQL  ? "SELECT * FROM ".$_SESSION["username"].".module WHERE DeviceUID = ? AND UID = ?" : (
		DB_IS_ORACLE ? "SELECT * FROM ".$_SESSION["username"].".module WHERE DeviceUID = ? AND \"UID\" = ?" : null))
	);
	$stmt->execute(array($device_sn, $module_uid));
	$obj = $stmt->fetch(PDO::FETCH_ASSOC);
	$stmt->closeCursor();
	
	$module_modelname = $obj["ModelName"];
	$module_nickname = $obj["Nickname"];
	if(!empty($module_modelname)){
		$module_name = $module_modelname;
		if(!empty($module_nickname)){
			$module_name .= "(" . $module_nickname . ")";
		}
	}
	else{
		$module_name = $module_nickname;
	}
	// For event log END
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "IF EXISTS(SELECT * FROM ".$_SESSION["username"].".sys.tables WHERE name = '" . $_POST["table_name"] . "') DELETE FROM ".$_SESSION["username"].".dbo." . $_POST["table_name"] . ";" : (
		DB_IS_MYSQL  ? "DELETE FROM ".$_SESSION["username"]."." . $_POST["table_name"] . ";" : (
		DB_IS_ORACLE ? "DELETE FROM ".$_SESSION["username"]."." . $_POST["table_name"] . ";" : null)));
	$stmt->execute();
	
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><info/>');
	$module = $xml->addChild('clear');
	
	if($stmt) {
		$module->addAttribute('reply', "OK");
	}
	else{
		$module->addAttribute('reply', "Fail");
	}
	
	$stmt->closeCursor();


	// Event log
	if($module->attributes()['reply'] == "OK"){
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "INSERT INTO ".$_SESSION["username"].".dbo.event_log(EventCode, Parameters) VALUES(10110, ?);" : (
			DB_IS_MYSQL  ? "INSERT INTO ".$_SESSION["username"].".event_log(EventCode, Parameters) VALUES(10110, ?);" : (
			DB_IS_ORACLE ? "INSERT INTO ".$_SESSION["username"].".event_log(EventCode, \"PARAMETERS\") VALUES(10110, ?);" : null))
		);
	}
	else{
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "INSERT INTO ".$_SESSION["username"].".dbo.event_log(EventCode, Parameters) VALUES(50214, ?);" : (
			DB_IS_MYSQL  ? "INSERT INTO ".$_SESSION["username"].".event_log(EventCode, Parameters) VALUES(50214, ?);" : (
			DB_IS_ORACLE ? "INSERT INTO ".$_SESSION["username"].".event_log(EventCode, \"PARAMETERS\") VALUES(50214, ?);" : null))
		);
	}
	
	$stmt->execute(array($device_name . "|$|" . $device_sn . "|$|" . $module_name . "|$|" . $module_uid));

	$stmt->closeCursor();
	$conn = null;
	
	header('Content-type: text/xml');
	print($xml->asXML());
}

function remove_module(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);

	// For event log
	$table_info = explode("_", $_POST["table_name"]);
	$device_sn = $table_info[1];
	$module_uid = $table_info[2];

	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT * FROM ".$_SESSION["username"].".dbo.device WHERE UID = ?" : (
		DB_IS_MYSQL  ? "SELECT * FROM ".$_SESSION["username"].".device WHERE UID = ?" : (
		DB_IS_ORACLE ? "SELECT * FROM ".$_SESSION["username"].".device WHERE \"UID\" = ?" : null))
	);
	$stmt->execute(array($device_sn));
	
	$obj = $stmt->fetch(PDO::FETCH_ASSOC);
	$stmt->closeCursor();

	$device_modelname = $obj["ModelName"];
	$device_nickname = $obj["Nickname"];
	if(!empty($device_nickname)){
		$device_name = $device_modelname . "(" . $device_nickname . ")";
	}
	else{
		$device_name = $device_modelname;
	}
	
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT * FROM ".$_SESSION["username"].".dbo.module WHERE DeviceUID = ? AND UID = ?" : (
		DB_IS_MYSQL  ? "SELECT * FROM ".$_SESSION["username"].".module WHERE DeviceUID = ? AND UID = ?" : (
		DB_IS_ORACLE ? "SELECT * FROM ".$_SESSION["username"].".module WHERE DeviceUID = ? AND \"UID\" = ?" : null))
	);
	$stmt->execute(array($device_sn, $module_uid));

	$obj = $stmt->fetch(PDO::FETCH_ASSOC);
	$stmt->closeCursor();

	$module_modelname = $obj["ModelName"];
	$module_nickname = $obj["Nickname"];
	if(!empty($module_modelname)){
		$module_name = $module_modelname;
		if(!empty($module_nickname)){
			$module_name .= "(" . $module_nickname . ")";
		}
	}
	else{
		$module_name = $module_nickname;
	}
	
	//---------------
	//刪除紀錄table Table
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "USE " . $_SESSION["username"] : (
		DB_IS_MYSQL  ? "USE " . $_SESSION["username"] : (
		DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $_SESSION["username"] : null)));
	$stmt->execute();
	
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "IF EXISTS(SELECT * FROM ".$_SESSION["username"].".sys.tables WHERE name = '".$_POST["table_name"]."') DROP TABLE ".$_POST["table_name"].";" : (
		DB_IS_MYSQL  ? "DROP TABLE IF EXISTS ".$_SESSION["username"].".".$_POST["table_name"].";" : (
		DB_IS_ORACLE ? "DECLARE cnt NUMBER; BEGIN SELECT COUNT(*) into cnt FROM all_tables WHERE owner='".strtoupper($_SESSION["username"])."' AND TABLE_NAME = '".strtoupper($_POST["table_name"])."'; IF cnt <> 0 THEN EXECUTE IMMEDIATE 'DROP TABLE ".strtoupper($_SESSION["username"].".".$_POST["table_name"])."'; END IF; END;" : null)));
	$stmt->execute();
	$stmt->closeCursor();
	
	
	//移除group資訊
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "DELETE FROM ".$_SESSION["username"].".dbo.group_data WHERE DeviceUID = '".$_POST["device_uid"]."' and ModuleUID = '".$_POST["module_uid"]."';" : (
		DB_IS_MYSQL  ? "DELETE FROM ".$_SESSION["username"].".group_data WHERE DeviceUID = '".$_POST["device_uid"]."' and ModuleUID = '".$_POST["module_uid"]."';" : (
		DB_IS_ORACLE ? "DELETE FROM ".$_SESSION["username"].".group_data WHERE DeviceUID = '".$_POST["device_uid"]."' and ModuleUID = '".$_POST["module_uid"]."';" : null))
	);
	$stmt->execute();
	$stmt->closeCursor();
		

	//移除module
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "DELETE FROM ".$_SESSION["username"].".dbo.module where DeviceUID = '".$_POST["device_uid"]."' and UID = '".$_POST["module_uid"]."';" : (
		DB_IS_MYSQL  ? "DELETE FROM ".$_SESSION["username"].".module where DeviceUID = '".$_POST["device_uid"]."' and UID = '".$_POST["module_uid"]."';" : (
		DB_IS_ORACLE ? "DELETE FROM ".$_SESSION["username"].".module where DeviceUID = '".$_POST["device_uid"]."' and \"UID\" = '".$_POST["module_uid"]."';" : null))
	);
	$stmt->execute();
	$stmt->closeCursor();
	
	//查詢Sare的資訊、並移除
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT Username FROM manager.dbo.share join manager.dbo.account ON share.AccountUIDShareTo = account.UID WHERE AccountUIDShareBy='".$_SESSION["account_uid"]."';" : (
		DB_IS_MYSQL  ? "SELECT Username FROM manager.share join manager.account ON share.AccountUIDShareTo = account.UID WHERE AccountUIDShareBy='".$_SESSION["account_uid"]."';" : (
		DB_IS_ORACLE ? "SELECT Username FROM MANAGER.\"SHARE\" join MANAGER.\"ACCOUNT\" ON \"SHARE\".AccountUIDShareTo = \"ACCOUNT\".\"UID\" WHERE AccountUIDShareBy='".$_SESSION["account_uid"]."';" : null)));
	$stmt->execute();
	$stmt->closeCursor();
	
	while($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
		//移除share對象的group
		$stmt2 = $conn->prepare(
			DB_IS_MSSQL  ? "DELETE FROM ".$obj["Username"].".dbo.group_data where AccountUID = '".$_SESSION["account_uid"]."' and DeviceUID = '".$_POST["device_uid"]."' and ModuleUID = '".$_POST["module_uid"]."';" : (
			DB_IS_MYSQL  ? "DELETE FROM ".$obj["Username"].".group_data where AccountUID = '".$_SESSION["account_uid"]."' and DeviceUID = '".$_POST["device_uid"]."' and ModuleUID = '".$_POST["module_uid"]."';" : (
			DB_IS_ORACLE ? "DELETE FROM ".$obj["Username"].".group_data where AccountUID = '".$_SESSION["account_uid"]."' and DeviceUID = '".$_POST["device_uid"]."' and ModuleUID = '".$_POST["module_uid"]."';" : null)));
		$stmt2->execute();
		$stmt2->closeCursor();
		
		//移除share對象的Dashboard widget 資訊
		$stmt2 = $conn->prepare(
			DB_IS_MSSQL  ? "DELETE FROM ".$obj["Username"].".dbo.dashboard_channel where AccountUID = '".$_SESSION["account_uid"]."' and DeviceUID = '".$_POST["device_uid"]."' and ModuleUID = '".$_POST["module_uid"]."';" : (
			DB_IS_MYSQL  ? "DELETE FROM ".$obj["Username"].".dashboard_channel where AccountUID = '".$_SESSION["account_uid"]."' and DeviceUID = '".$_POST["device_uid"]."' and ModuleUID = '".$_POST["module_uid"]."';" : (
			DB_IS_ORACLE ? "DELETE FROM ".$obj["Username"].".dashboard_channel where AccountUID = '".$_SESSION["account_uid"]."' and DeviceUID = '".$_POST["device_uid"]."' and ModuleUID = '".$_POST["module_uid"]."';" : null)));
		$stmt2->execute();
		$stmt2->closeCursor();
	}
	$stmt->closeCursor();
	
	
	//移除Dashboard widget 資訊
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "DELETE FROM ".$_SESSION["username"].".dbo.dashboard_channel WHERE DeviceUID = '".$_POST["device_uid"]."' and ModuleUID = '".$_POST["module_uid"]."';" : (
		DB_IS_MYSQL  ? "DELETE FROM ".$_SESSION["username"].".dashboard_channel WHERE DeviceUID = '".$_POST["device_uid"]."' and ModuleUID = '".$_POST["module_uid"]."';" : (
		DB_IS_ORACLE ? "DELETE FROM ".$_SESSION["username"].".dashboard_channel WHERE DeviceUID = '".$_POST["device_uid"]."' and ModuleUID = '".$_POST["module_uid"]."';" : null))
	);
	$stmt->execute();
	$stmt->closeCursor();
	
	//移除Channel
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "DELETE FROM ".$_SESSION["username"].".dbo.channel where DeviceUID = '".$_POST["device_uid"]."' and ModuleUID = '".$_POST["module_uid"]."';" : (
		DB_IS_MYSQL  ? "DELETE FROM ".$_SESSION["username"].".channel where DeviceUID = '".$_POST["device_uid"]."' and ModuleUID = '".$_POST["module_uid"]."';" : (
		DB_IS_ORACLE ? "DELETE FROM ".$_SESSION["username"].".channel where DeviceUID = '".$_POST["device_uid"]."' and ModuleUID = '".$_POST["module_uid"]."';" : null))
	);
	$stmt->execute();
	$stmt->closeCursor();
	//--------------
	
	
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><info/>');
	$module = $xml->addChild('remove');
		
	$module->addAttribute('reply', "OK");
	
	
	// Event log
	if($module->attributes()['reply'] == "OK"){
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "INSERT INTO ".$_SESSION["username"].".dbo.event_log(EventCode, Parameters) VALUES(10111, ?);" : (
			DB_IS_MYSQL  ? "INSERT INTO ".$_SESSION["username"].".event_log(EventCode, Parameters) VALUES(10111, ?);" : (
			DB_IS_ORACLE ? "INSERT INTO ".$_SESSION["username"].".event_log(EventCode, \"PARAMETERS\") VALUES(10111, ?);" : null))
		);
	}
	else{
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "INSERT INTO ".$_SESSION["username"].".dbo.event_log(EventCode, Parameters) VALUES(50215, ?);" : (
			DB_IS_MYSQL  ? "INSERT INTO ".$_SESSION["username"].".event_log(EventCode, Parameters) VALUES(50215, ?);" : (
			DB_IS_ORACLE ? "INSERT INTO ".$_SESSION["username"].".event_log(EventCode, \"PARAMETERS\") VALUES(50215, ?);" : null))
		);
	}
	$stmt->execute(array($device_name . "|$|" . $device_sn . "|$|" . $module_name . "|$|" . $module_uid));
	$stmt->closeCursor();
	
	$conn = null;

	header('Content-type: text/xml');
	print($xml->asXML());
}

function remove_device(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	//post:	device_uid
	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);

	// For event log
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT * FROM ".$_SESSION["username"].".dbo.device WHERE UID = ?;" : (
		DB_IS_MYSQL  ? "SELECT * FROM ".$_SESSION["username"].".device WHERE UID = ?;" : (
		DB_IS_ORACLE ? "SELECT * FROM ".$_SESSION["username"].".device WHERE \"UID\" = ?;" : null))
	);
	$stmt->execute(array($_POST["device_uid"]));
	$obj = $stmt->fetch(PDO::FETCH_ASSOC);
	$device_modelname = $obj["ModelName"];
	$device_nickname = $obj["Nickname"];
	if(!empty($device_nickname)){
		$device_name = $device_modelname . "(" . $device_nickname . ")";
	}
	else{
		$device_name = $device_modelname;
	}
	$stmt->closeCursor();

	//--------------
	//查詢Device的module
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT UID FROM ".$_SESSION["username"].".dbo.module WHERE DeviceUID = '" . $_POST["device_uid"] . "';" : (
		DB_IS_MYSQL  ? "SELECT UID FROM ".$_SESSION["username"].".module WHERE DeviceUID = '" . $_POST["device_uid"] . "';" : (
		DB_IS_ORACLE ? "SELECT \"UID\" FROM ".$_SESSION["username"].".module WHERE DeviceUID = '" . $_POST["device_uid"] . "';" : null))
	);
	$stmt->execute();
	
	//刪除紀錄table
	while($obj1 = $stmt->fetch(PDO::FETCH_ASSOC)){
		$table_name = "uid_" . $_POST["device_uid"] . "_" . $obj1["UID"];
		$stmt2 = $conn->prepare(
			DB_IS_MSSQL  ? "IF EXISTS(SELECT * FROM ".$_SESSION["username"].".sys.tables WHERE name = '" . $table_name . "') DROP TABLE ".$_SESSION["username"].".dbo.".$table_name.";" : (
			DB_IS_MYSQL  ? "DROP TABLE IF EXISTS ".$_SESSION["username"].".".$table_name.";" : (
			DB_IS_ORACLE ? "DECLARE cnt NUMBER; BEGIN SELECT COUNT(*) into cnt FROM all_tables WHERE owner='".strtoupper($_SESSION["username"])."' AND TABLE_NAME = '".strtoupper($table_name)."'; IF cnt <> 0 THEN EXECUTE IMMEDIATE 'DROP TABLE ".strtoupper($_SESSION["username"].".".$table_name)."'; END IF; END;" : null)));
		$stmt2->execute();
		$stmt2->closeCursor();
	}
	$stmt->closeCursor();
	
	//刪除紀錄IR table
	$table_name = "uid_" . $_POST["device_uid"] . "_ir";
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "IF EXISTS(SELECT * FROM ".$_SESSION["username"].".sys.tables WHERE name = '" . $table_name . "') DROP TABLE ".$_SESSION["username"].".".$table_name.";" : (
		DB_IS_MYSQL  ? "DROP TABLE IF EXISTS ".$_SESSION["username"].".".$table_name.";" : (
		DB_IS_ORACLE ? "DECLARE cnt NUMBER; BEGIN SELECT COUNT(*) into cnt FROM all_tables WHERE owner='".strtoupper($_SESSION["username"])."' AND TABLE_NAME = '".strtoupper($table_name)."'; IF cnt <> 0 THEN EXECUTE IMMEDIATE 'DROP TABLE ".strtoupper($_SESSION["username"].".".$table_name)."'; END IF; END;" : null)));
	$stmt->execute();
	$stmt->closeCursor();
	
	
	//刪除紀錄realtime table
	$table_name = "uid_" . $_POST["device_uid"] . "_realtime";
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "IF EXISTS(SELECT * FROM ".$_SESSION["username"].".sys.tables WHERE name = '" . $table_name . "') DROP TABLE ".$_SESSION["username"].".dbo." . $table_name . ";" : (
		DB_IS_MYSQL  ? "DROP TABLE IF EXISTS ".$_SESSION["username"].".".$table_name.";" : (
		DB_IS_ORACLE ? "DECLARE cnt NUMBER; BEGIN SELECT COUNT(*) into cnt FROM all_tables WHERE owner='".strtoupper($_SESSION["username"])."' AND TABLE_NAME = '".strtoupper($table_name)."'; IF cnt <> 0 THEN EXECUTE IMMEDIATE 'DROP TABLE ".strtoupper($_SESSION["username"].".".$table_name)."'; END IF; END;" : null)));
	$stmt->execute();
	$stmt->closeCursor();
		
	
	//移除group資訊
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "DELETE FROM ".$_SESSION["username"].".dbo.group_data WHERE DeviceUID = '" . $_POST["device_uid"] . "';" : (
		DB_IS_MYSQL  ? "DELETE FROM ".$_SESSION["username"].".group_data WHERE DeviceUID = '" . $_POST["device_uid"] . "';" : (
		DB_IS_ORACLE ? "DELETE FROM ".$_SESSION["username"].".group_data WHERE DeviceUID = '" . $_POST["device_uid"] . "';" : null))
	);
	$stmt->execute();
	$stmt->closeCursor();
	
	//移除channel
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "DELETE FROM ".$_SESSION["username"].".dbo.channel where DeviceUID = '" . $_POST["device_uid"] . "';" : (
		DB_IS_MYSQL  ? "DELETE FROM ".$_SESSION["username"].".channel where DeviceUID = '" . $_POST["device_uid"] . "';" : (
		DB_IS_ORACLE ? "DELETE FROM ".$_SESSION["username"].".channel where DeviceUID = '" . $_POST["device_uid"] . "';" : null))
	);
	$stmt->execute();
	$stmt->closeCursor();
		
	//移除module
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "DELETE FROM ".$_SESSION["username"].".dbo.module where DeviceUID = '" . $_POST["device_uid"] . "';" : (
		DB_IS_MYSQL  ? "DELETE FROM ".$_SESSION["username"].".module where DeviceUID = '" . $_POST["device_uid"] . "';" : (
		DB_IS_ORACLE ? "DELETE FROM ".$_SESSION["username"].".module where DeviceUID = '" . $_POST["device_uid"] . "';" : null))
	);
	$stmt->execute();
	$stmt->closeCursor();
	
	//移除device
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "DELETE FROM ".$_SESSION["username"].".dbo.device where UID = '" . $_POST["device_uid"] . "';" : (
		DB_IS_MYSQL  ? "DELETE FROM ".$_SESSION["username"].".device where UID = '" . $_POST["device_uid"] . "';" : (
		DB_IS_ORACLE ? "DELETE FROM ".$_SESSION["username"].".device where \"UID\" = '" . $_POST["device_uid"] . "';" : null))
	);
	$stmt->execute();
	$stmt->closeCursor();
	
	//查詢Sare的資訊、並移除
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT Username FROM manager.dbo.share join manager.dbo.account ON share.AccountUIDShareTo = account.UID WHERE AccountUIDShareBy='".$_SESSION["account_uid"]."';" : (
		DB_IS_MYSQL  ? "SELECT Username FROM manager.share join manager.account ON share.AccountUIDShareTo = account.UID WHERE AccountUIDShareBy='".$_SESSION["account_uid"]."';" : (
		DB_IS_ORACLE ? "SELECT Username FROM MANAGER.\"SHARE\" join MANAGER.\"ACCOUNT\" ON \"SHARE\".AccountUIDShareTo = \"ACCOUNT\".\"UID\" WHERE AccountUIDShareBy='".$_SESSION["account_uid"]."';" : null)));
	$stmt->execute();
	
	while($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
		//移除share對象的group
		$stmt2 = $conn->prepare(
			DB_IS_MSSQL  ? "DELETE FROM ".$obj["Username"].".dbo.group_data where AccountUID = '".$_SESSION["account_uid"]."' and DeviceUID = '".$_POST["device_uid"]."';" : (
			DB_IS_MYSQL  ? "DELETE FROM ".$obj["Username"].".group_data where AccountUID = '".$_SESSION["account_uid"]."' and DeviceUID = '".$_POST["device_uid"]."';" : (
			DB_IS_ORACLE ? "DELETE FROM ".$obj["Username"].".group_data where AccountUID = '".$_SESSION["account_uid"]."' and DeviceUID = '".$_POST["device_uid"]."';" : null)));
		$stmt2->execute();
		$stmt2->closeCursor();
		
		//移除share對象的Dashboard widget 資訊
		$stmt2 = $conn->prepare(
			DB_IS_MSSQL  ? "DELETE FROM ".$obj["Username"].".dbo.dashboard_channel where AccountUID = '".$_SESSION["account_uid"]."' and DeviceUID = '".$_POST["device_uid"]."';" : (
			DB_IS_MYSQL  ? "DELETE FROM ".$obj["Username"].".dashboard_channel where AccountUID = '".$_SESSION["account_uid"]."' and DeviceUID = '".$_POST["device_uid"]."';" : (
			DB_IS_ORACLE ? "DELETE FROM ".$obj["Username"].".dashboard_channel where AccountUID = '".$_SESSION["account_uid"]."' and DeviceUID = '".$_POST["device_uid"]."';" : null)));
		$stmt2->execute();
		$stmt2->closeCursor();
	}
	$stmt->closeCursor();
	
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "DELETE FROM ".$_SESSION["username"].".dbo.dashboard_channel WHERE DeviceUID = '" . $_POST["device_uid"] . "';" : (
		DB_IS_MYSQL  ? "DELETE FROM ".$_SESSION["username"].".dashboard_channel WHERE DeviceUID = '" . $_POST["device_uid"] . "';" : (
		DB_IS_ORACLE ? "DELETE FROM ".$_SESSION["username"].".dashboard_channel WHERE DeviceUID = '" . $_POST["device_uid"] . "';" : null))
	);
	$stmt->execute();
	$stmt->closeCursor();
	
	//--------------

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><info/>');
	$module = $xml->addChild('remove');

	$module->addAttribute('reply', "OK");
	
	// Event log
	if($module->attributes()['reply'] == "OK"){
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "INSERT INTO ".$_SESSION["username"].".dbo.event_log(EventCode, Parameters) VALUES(10109, ?);" : (
			DB_IS_MYSQL  ? "INSERT INTO ".$_SESSION["username"].".event_log(EventCode, Parameters) VALUES(10109, ?);" : (
			DB_IS_ORACLE ? "INSERT INTO ".$_SESSION["username"].".event_log(EventCode, \"PARAMETERS\") VALUES(10109, ?);" : null))
		);
	}
	else{
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "INSERT INTO ".$_SESSION["username"].".dbo.event_log(EventCode, Parameters) VALUES(50213, ?);" : (
			DB_IS_MYSQL  ? "INSERT INTO ".$_SESSION["username"].".event_log(EventCode, Parameters) VALUES(50213, ?);" : (
			DB_IS_ORACLE ? "INSERT INTO ".$_SESSION["username"].".event_log(EventCode, \"PARAMETERS\") VALUES(50213, ?);" : null))
		);
	}
	$stmt->execute(array($device_name . "|$|" . $_POST["device_uid"]));
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

		$in = '<cmd type="userRemoveDeviceFunction" username="' . $_SESSION["username"] . '" device_uid="' . $_POST["device_uid"] . '"/>';
		socket_write($socket, $in);
		socket_shutdown($socket, 1);

		socket_close($socket);
	}
	catch (ErrorException $e) {}

	restore_error_handler();
	// Notify END

	header('Content-type: text/xml');
	print($xml->asXML());
	
}

function database_usage(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);

	// Max size
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><info/>');
	$xml_db = $xml->addChild('db');
	if(DB_IS_MSSQL){
		$stmt = $conn->prepare("USE " . $_SESSION["username"]);
		$stmt->execute();
		
		$stmt = $conn->prepare("SELECT max_size / 128.0 AS MaxSize FROM sys.database_files WHERE type = 0;");
		$stmt->execute();
		$obj = $stmt->fetch(PDO::FETCH_ASSOC);
		$xml_db->addAttribute('max_size', $obj["MaxSize"]);
		$stmt->closeCursor();
	}
	else{//MySQL、ORACLE暫時先跳過
		$xml_db->addAttribute('max_size', "-1");
	}

	// Current size
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "USE " . $_SESSION["username"] : (
		DB_IS_MYSQL  ? "USE " . $_SESSION["username"] : (
		DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $_SESSION["username"] : null)));
	$stmt->execute();
	
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "EXEC('DBCC showfilestats');" : (
		DB_IS_MYSQL  ? "select round(sum(data_length/1024/1024),2) as UsedExtents from information_schema.tables where TABLE_SCHEMA='".$_SESSION["username"]."';" : (
		DB_IS_ORACLE ? "SELECT round((sum(bytes)/1024/1024),2) as UsedExtents FROM dba_segments WHERE owner = '".strtoupper($_SESSION["username"])."';" : null)));
	$stmt->execute();
	
	$obj = $stmt->fetch(PDO::FETCH_ASSOC);
	if(DB_IS_MSSQL)
		$xml_db->addAttribute('size', $obj["UsedExtents"] / 16.0);
	else if(DB_IS_MYSQL)
		$xml_db->addAttribute('size', $obj["UsedExtents"]);
	else if(DB_IS_ORACLE)
		$xml_db->addAttribute('size', $obj["UsedExtents"]);
	$stmt->closeCursor();

	header('Content-type: text/xml');
	print($xml->asXML());
}

function copy_table(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><info/>');
	$source_table_name = "uid_" . $_POST["source_device_uid"] . "_" . $_POST["source_module_uid"];
	$destination_table_name = "uid_" . $_POST["destination_device_uid"] . "_" . $_POST["destination_module_uid"];
	
	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);

	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT (CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END) AS Exist FROM " . $_SESSION["username"] . ".sys.tables WHERE name = '".$source_table_name."';" : (
		DB_IS_MYSQL  ? "SELECT (CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END) AS Exist FROM information_schema.tables WHERE TABLE_SCHEMA='".$_SESSION["username"]."' and TABLE_NAME = '".$source_table_name."';" : (
		DB_IS_ORACLE ? "SELECT (CASE WHEN count(*) > 0 THEN 1 ELSE 0 END) AS Exist FROM dba_tables WHERE OWNER = '".strtoupper($_SESSION["username"])."' AND TABLE_NAME = '".strtoupper($source_table_name)."';" : null)));
	$stmt->execute();
	
	$row = $stmt->fetch(PDO::FETCH_ASSOC);

	if($row["Exist"] == 0){
		$xml->addAttribute("error", "SOURCE_TABLE_NOT_EXIST");// Source table not exist
		goto output;
	}
	$stmt->closeCursor();
	
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT (CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END) AS Exist FROM " . $_SESSION["username"] . ".sys.tables WHERE name = '".$destination_table_name."';" : (
		DB_IS_MYSQL  ? "SELECT (CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END) AS Exist FROM information_schema.tables WHERE TABLE_SCHEMA='".$_SESSION["username"]."' and TABLE_NAME = '".$destination_table_name."';" : (
		DB_IS_ORACLE ? "SELECT (CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END) AS Exist FROM dba_tables WHERE OWNER='".strtoupper($_SESSION["username"])."' and TABLE_NAME = '".strtoupper($destination_table_name)."';" : null)));
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	
	if($row["Exist"] == 0){
		$xml->addAttribute("error", "SOURCE_TABLE_NOT_EXIST");// Source table not exist
		goto output;
	}
	$stmt->closeCursor();
	
	$stmt1 = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT COUNT(*) AS ROW_COUNT FROM ".$_SESSION["username"].".INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$source_table_name."' AND TABLE_SCHEMA='dbo';" : (
		DB_IS_MYSQL  ? "SELECT COUNT(*) AS ROW_COUNT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='".$_SESSION["username"]."'  AND TABLE_NAME='".$source_table_name."';" : (
		DB_IS_ORACLE ? "SELECT COUNT(*) AS ROW_COUNT FROM dba_tables WHERE OWNER='".strtoupper($_SESSION["username"])."' and TABLE_NAME = '".strtoupper($source_table_name)."';" : null)));
	$stmt1->execute();
	
	$stmt2 = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT COUNT(*) AS ROW_COUNT FROM ".$_SESSION["username"].".INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$destination_table_name."' AND TABLE_SCHEMA='dbo';" : (
		DB_IS_MYSQL  ? "SELECT COUNT(*) AS ROW_COUNT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='".$_SESSION["username"]."'  AND TABLE_NAME='".$destination_table_name."';" : (
		DB_IS_ORACLE ? "SELECT COUNT(*) AS ROW_COUNT FROM dba_tables WHERE OWNER='".strtoupper($_SESSION["username"])."' and TABLE_NAME = '".strtoupper($destination_table_name)."';" : null)));
	$stmt2->execute();
	
	$row1count = $stmt1->fetch(PDO::FETCH_ASSOC)["ROW_COUNT"];
	$row2count = $stmt2->fetch(PDO::FETCH_ASSOC)["ROW_COUNT"];
		
	if($row1count != $row2count){
		$xml->addAttribute("error", "MODEL_NOT_MATCH");
		goto output;
	}
	
	$stmt1->closeCursor();
	$stmt2->closeCursor();
	
	$stmt1 = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT COLUMN_NAME AS Name FROM ".$_SESSION["username"].".INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$source_table_name."' AND TABLE_SCHEMA='dbo';" : (
		DB_IS_MYSQL  ? "SELECT COLUMN_NAME AS Name FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='".$_SESSION["username"]."'  AND TABLE_NAME='".$source_table_name."';" : (
		DB_IS_ORACLE ? "SELECT COLUMN_NAME AS \"NAME\" FROM ALL_COL_COMMENTS WHERE OWNER='".strtoupper($_SESSION["username"])."' and TABLE_NAME = '".strtoupper($source_table_name)."';" : null)));
	$stmt1->execute();
	
	$stmt2 = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT COLUMN_NAME AS Name FROM ".$_SESSION["username"].".INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$destination_table_name."' AND TABLE_SCHEMA='dbo';" : (
		DB_IS_MYSQL  ? "SELECT COLUMN_NAME AS Name FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='".$_SESSION["username"]."'  AND TABLE_NAME='".$destination_table_name."';" : (
		DB_IS_ORACLE ? "SELECT COLUMN_NAME AS \"NAME\" FROM ALL_COL_COMMENTS WHERE OWNER='".strtoupper($_SESSION["username"])."'  AND TABLE_NAME='".strtoupper($destination_table_name)."';" : null)));
	$stmt2->execute();
	
	while($obj1 = $stmt1->fetch(PDO::FETCH_ASSOC)){
		$obj2 = $stmt2->fetch(PDO::FETCH_ASSOC);
		
		if($obj1["Name"] != $obj2["Name"]){
			$xml->addAttribute("error", "MODEL_NOT_MATCH");
			goto output;
		}
	}

	$stmt1->closeCursor();
	$stmt2->closeCursor();

	// Prevent timeout
	set_time_limit(0);
	try{
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "INSERT INTO ".$_SESSION["username"].".dbo."."uid_" . $_POST["destination_device_uid"] . "_" . $_POST["destination_module_uid"] . " SELECT * FROM " . "uid_" . $_POST["source_device_uid"] . "_" . $_POST["source_module_uid"] . ";" : (
			DB_IS_MYSQL  ? "INSERT INTO ".$_SESSION["username"].".uid_" . $_POST["destination_device_uid"] . "_" . $_POST["destination_module_uid"] . " SELECT * FROM ".$_SESSION["username"].".uid_" . $_POST["source_device_uid"] . "_" . $_POST["source_module_uid"] . ";" : (
			DB_IS_ORACLE ? "INSERT INTO ".$_SESSION["username"].".uid_" . $_POST["destination_device_uid"] . "_" . $_POST["destination_module_uid"] . " SELECT * FROM ".$_SESSION["username"].".uid_" . $_POST["source_device_uid"] . "_" . $_POST["source_module_uid"] . ";" : null)));
		$stmt->execute();
		$stmt->closeCursor();
	}
	catch(PDOException $e){
		if(DB_IS_MSSQL){
			if($e->errorInfo[1] == 2627){
				$xml->addAttribute("error", "DATA_DUPLICATE");
				goto output;
			}
			else if($e->errorInfo[1] == 1101 || $e->errorInfo[1] == 1105 || $e->errorInfo[1] == 1121 || $e->errorInfo[1] == 2714){
				$xml->addAttribute("error", "SPACE_NOT_ENOUGH");
				goto output;
			}
		}
		else if(DB_IS_MYSQL){
			if($e->errorInfo[1] == 1062){
				$xml->addAttribute("error", "DATA_DUPLICATE");
				goto output;
			}
			else if($e->errorInfo[1] == 1021){
				$xml->addAttribute("error", "SPACE_NOT_ENOUGH");
				goto output;
			}
		}
		else if(DB_IS_ORACLE){
			if($e->errorInfo[1] == "ORA-00957"){
				$xml->addAttribute("error", "DATA_DUPLICATE");
				goto output;
			}
			else if($e->errorInfo[1] == "ORA-19870"){
				$xml->addAttribute("error", "SPACE_NOT_ENOUGH");
				goto output;
			}
		}
		goto output;
	}
	
	$xml->addAttribute("result", "OK");
	
	output:
		$xml->addAttribute("result", "ERROR");
	
	header('Content-type: text/xml');
	print($xml->asXML());
	
	$conn = null;
}
?>