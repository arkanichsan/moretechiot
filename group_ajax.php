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
function loadgrouplist(){
	global $login, $lang;
	if($login == false){
		trap("Permission error!");
	}
	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT * FROM ".$_SESSION["username"].".dbo.group_info where Type=1 ORDER BY UID;" : (
		DB_IS_MYSQL  ? "SELECT * FROM ".$_SESSION["username"].".group_info where Type=1 ORDER BY UID;" : (
		DB_IS_ORACLE ? "SELECT * FROM ".$_SESSION["username"].".group_info where \"TYPE\"=1 ORDER BY \"UID\";" : null)));
	$stmt->execute();
	
	
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><group/>');

	while($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
    	$module = $xml->addChild('list');
		$module->addAttribute('uid', $obj["UID"]);
		$module->addAttribute('name', $obj["Name"]);
	}
	$stmt->closeCursor();
	$conn = null;
	
	header('Content-type: text/xml');
	print($xml->asXML());
}

function creatgroup(){
	global $login, $lang;
	if($login == false){
		trap("Permission error!");
	}
	
	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);

	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "INSERT INTO ".$_SESSION["username"].".dbo.group_info(Name,Type) VALUES(?,1);" : (
		DB_IS_MYSQL  ? "INSERT INTO ".$_SESSION["username"].".group_info(Name,Type) VALUES(?,1);" : (
		DB_IS_ORACLE ? "INSERT INTO ".$_SESSION["username"].".group_info(\"NAME\",\"TYPE\") VALUES(?,1);" : null)));
	
	$stmt->execute(array($_POST["groupname"]));
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT MAX(UID) AS UID FROM ".$_SESSION["username"].".dbo.group_info;" : (
		DB_IS_MYSQL  ? "SELECT MAX(UID) AS UID FROM ".$_SESSION["username"].".group_info;" : (
		DB_IS_ORACLE ? "SELECT MAX(\"UID\") AS \"UID\" FROM ".$_SESSION["username"].".group_info;" : null)));
	$stmt->execute(array($_POST["groupname"]));
	
	
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><group/>');
	$module = $xml->addChild('list');
	$module->addAttribute('uid', $row["UID"]);
	
	header('Content-type: text/xml');
	print($xml->asXML());
}

function editgroupname(){
	global $login, $lang;
	if($login == false){
		trap("Permission error!");
	}
	if(!empty($_POST["groupname"]))
	{
		$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "UPDATE ".$_SESSION["username"].".dbo.group_info SET Name = ? WHERE UID=?;" : (
			DB_IS_MYSQL  ? "UPDATE ".$_SESSION["username"].".group_info SET Name = ? WHERE UID=?;" : (
			DB_IS_ORACLE ? "UPDATE ".$_SESSION["username"].".group_info SET \"NAME\" = ? WHERE \"UID\"=?;" : null)));
		$stmt->execute(array($_POST["groupname"],$_POST["group_uid"]));
	}
	$stmt->closeCursor();
	$conn = null;
}

function delgroup(){
	global $login, $lang;
	if($login == false){
		trap("Permission error!");
	}

	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
	
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "DELETE FROM ".$_SESSION["username"].".dbo.group_info WHERE UID=?;" : (
		DB_IS_MYSQL  ? "DELETE FROM ".$_SESSION["username"].".group_info WHERE UID=?;" : (
		DB_IS_ORACLE ? "DELETE FROM ".$_SESSION["username"].".group_info WHERE \"UID\"=?;" : null)));
	$stmt->execute(array($_POST["group_uid"]));	
	
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "DELETE FROM ".$_SESSION["username"].".dbo.group_data WHERE GroupInfoUID=?;" : (
		DB_IS_MYSQL  ? "DELETE FROM ".$_SESSION["username"].".group_data WHERE GroupInfoUID=?;" : (
		DB_IS_ORACLE ? "DELETE FROM ".$_SESSION["username"].".group_data WHERE GroupInfoUID=?;" : null)));
	$stmt->execute(array($_POST["group_uid"]));
	
	$stmt->closeCursor();
	$conn = null;
}

function loadcontroller(){
	global $login, $lang;
	if($login == false){
		trap("Permission error!");
	}
	
	
	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT * FROM ".$_SESSION["username"].".dbo.device ORDER BY UID;" : (
		DB_IS_MYSQL  ? "SELECT * FROM ".$_SESSION["username"].".device ORDER BY UID;" : (
		DB_IS_ORACLE ? "SELECT * FROM ".$_SESSION["username"].".device ORDER BY \"UID\";" : null)));
		
	$stmt->execute();
	
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><controller/>');
	$local = $xml -> addChild('local');
	$local->addAttribute('uid', $_SESSION["account_uid"]);
	while($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
    	$module = $local->addChild('list');
		$module->addAttribute('uid', $obj["UID"]);
		$module->addAttribute('model_name', $obj["ModelName"]);
		$module->addAttribute('nickname', $obj["Nickname"]);
	}
	$stmt->closeCursor();
	
	//share
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT UID as 'localUID' FROM manager.dbo.account WHERE Username = '".$_SESSION["username"]."';" : (
		DB_IS_MYSQL  ? "SELECT UID as 'localUID' FROM manager.account WHERE Username = '".$_SESSION["username"]."';" : (
		DB_IS_ORACLE ? "SELECT \"UID\" as \"localUID\" FROM manager.\"ACCOUNT\" WHERE Username = '".$_SESSION["username"]."';" : null)));
	$stmt->execute();
	
	while($localUID_obj = $stmt->fetch(PDO::FETCH_ASSOC)){
		$stmt2 = $conn->prepare(
			DB_IS_MSSQL  ? "SELECT AccountUIDShareBy,Username,Nickname FROM manager.dbo.share join manager.dbo.account ON share.AccountUIDShareBy = account.UID WHERE share.AccountUIDShareTo = '".$localUID_obj["localUID"]."';" : (
			DB_IS_MYSQL  ? "SELECT AccountUIDShareBy,Username,Nickname FROM manager.share join manager.account ON share.AccountUIDShareBy = account.UID WHERE share.AccountUIDShareTo = '".$localUID_obj["localUID"]."';" : (
			DB_IS_ORACLE ? "SELECT AccountUIDShareBy,Username,Nickname FROM manager.\"SHARE\" join manager.\"ACCOUNT\" ON \"SHARE\".AccountUIDShareBy = \"ACCOUNT\".\"UID\" WHERE \"SHARE\".AccountUIDShareTo = '".$localUID_obj["localUID"]."';" : null)));
		$stmt2->execute();
		
		while($shareinfo_obj = $stmt2->fetch(PDO::FETCH_ASSOC)){
			$share_info = $xml->addChild("share");
			$share_info->addAttribute('uid', $shareinfo_obj["AccountUIDShareBy"]);
			$share_info->addAttribute('Username', $shareinfo_obj["Username"]);
			$share_info->addAttribute('Nickname', $shareinfo_obj["Nickname"]);
			
			$stmt3 = $conn->prepare(
				DB_IS_MSSQL  ? "SELECT * FROM ".$shareinfo_obj["Username"].".dbo.device;" : (
				DB_IS_MYSQL  ? "SELECT * FROM ".$shareinfo_obj["Username"].".device;" : (
				DB_IS_ORACLE ? "SELECT * FROM ".$shareinfo_obj["Username"].".device;" : null)));
			$stmt3->execute();
			
			while($obj = $stmt3->fetch(PDO::FETCH_ASSOC)){
				$sharemodule = $share_info->addChild('list');
				$sharemodule->addAttribute('uid', $obj["UID"]);
				$sharemodule->addAttribute('model_name', $obj["ModelName"]);
				$sharemodule->addAttribute('nickname', $obj["Nickname"]);
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

function editgroupmodule(){
	global $login, $lang;
	if($login == false){
		trap("Permission error!");
	}
	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "DELETE FROM ".$_SESSION["username"].".dbo.group_data where GroupInfoUID=".$_POST["group_info_uid"].";" : (
		DB_IS_MYSQL  ? "DELETE FROM ".$_SESSION["username"].".group_data where GroupInfoUID=".$_POST["group_info_uid"].";" : (
		DB_IS_ORACLE ? "DELETE FROM ".$_SESSION["username"].".group_data where GroupInfoUID=".$_POST["group_info_uid"].";"  : null)));
	$stmt->execute();
	$stmt->closeCursor();
	
 	for($i = 0; $i < count($_POST["ck_loop"]); $i++)//新增選取迴路
	{
		$controller_uid = explode('_',$_POST["ck_loop"][$i])[0];
		$module_uid = explode('_',$_POST["ck_loop"][$i])[1];
		$loop = explode('_',$_POST["ck_loop"][$i])[2];
		$accountUID = explode('_',$_POST["ck_loop"][$i])[3];
		
		$stmt2 = $conn->prepare(
			DB_IS_MSSQL  ? "SELECT Username FROM manager.dbo.account WHERE UID='".$accountUID."';" : (
			DB_IS_MYSQL  ? "SELECT Username FROM manager.account WHERE UID='".$accountUID."';" : (
			DB_IS_ORACLE ? "SELECT Username FROM manager.\"ACCOUNT\" WHERE \"UID\"='".$accountUID."';" : null)));
		$stmt2->execute();
		$Username = "";
		while($obj_acc = $stmt2->fetch(PDO::FETCH_ASSOC)){
			$Username = $obj_acc["Username"];
		}
		$stmt2->closeCursor();
		
		//check module removed
		$stmt3 = $conn->prepare(
			DB_IS_MSSQL  ? "SELECT Removed FROM ".$Username.".dbo.module WHERE DeviceUID = '".$controller_uid."' AND UID = '".$module_uid."';" : (
			DB_IS_MYSQL  ? "SELECT Removed FROM ".$Username.".module WHERE DeviceUID = '".$controller_uid."' AND UID = '".$module_uid."';" : (
			DB_IS_ORACLE ? "SELECT Removed FROM ".$Username.".module WHERE DeviceUID = '".$controller_uid."' AND \"UID\" = '".$module_uid."';" : null)));
		$stmt3->execute();
		
		while($obj_module = $stmt3->fetch(PDO::FETCH_ASSOC)){
			$ck_Removed = $obj_module["Removed"];
		}
		$stmt3->closeCursor();	


		if($ck_Removed == 1){
			continue;
		}
		
		//insert group data
		$stmt4 = $conn->prepare(
			DB_IS_MSSQL  ? "INSERT INTO ".$_SESSION["username"].".dbo.group_data(AccountUID,DeviceUID,ModuleUid,Loop,GroupInfoUID) VALUES ('".$accountUID."','".$controller_uid."','".$module_uid."','".$loop."','".$_POST["group_info_uid"]."');" : (
			DB_IS_MYSQL  ? "INSERT INTO ".$_SESSION["username"].".group_data(AccountUID,DeviceUID,ModuleUid,`Loop`,GroupInfoUID) VALUES ('".$accountUID."','".$controller_uid."','".$module_uid."','".$loop."','".$_POST["group_info_uid"]."');" : (
			DB_IS_ORACLE ? "INSERT INTO ".$_SESSION["username"].".group_data(AccountUID,DeviceUID,ModuleUid,Loop,GroupInfoUID) VALUES ('".$accountUID."','".$controller_uid."','".$module_uid."','".$loop."','".$_POST["group_info_uid"]."');" : null)));
		$stmt4->execute();
		$stmt4->closeCursor();
	} 
	$conn = null;
}

function load_group_data()
{
	global $login, $lang;
	if($login == false){
		trap("Permission error!");
	}
	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT DISTINCT group_data.AccountUID as AccountUID, Username,Nickname FROM ".$_SESSION["username"].".dbo.group_data JOIN manager.dbo.account ON group_data.AccountUID = manager.dbo.account.UID WHERE GroupInfoUid=?;" : (
		DB_IS_MYSQL  ? "SELECT DISTINCT group_data.AccountUID as AccountUID, Username,Nickname FROM ".$_SESSION["username"].".group_data JOIN manager.account ON group_data.AccountUID = manager.account.UID WHERE GroupInfoUid=?;" : (
		DB_IS_ORACLE ? "SELECT DISTINCT group_data.AccountUID as AccountUID, Username,Nickname FROM ".$_SESSION["username"].".group_data JOIN manager.\"ACCOUNT\" ON group_data.AccountUID = manager.\"ACCOUNT\".\"UID\" WHERE GroupInfoUid=?;" : null)));
	$stmt->execute(array($_POST["group_uid"]));
	
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><group_data_list/>');
	$check_loop = $xml->addChild('loop');
	while($row1 = $stmt->fetch(PDO::FETCH_ASSOC)){
		
		$account = $xml->addChild('account');
		$account->addAttribute("account_uid",$row1["AccountUID"]);
		$account->addAttribute("username",$row1["Username"]);
		$account->addAttribute("nickname",$row1["Nickname"]);
		if($row1["AccountUID"] == $_SESSION["account_uid"])
			$account->addAttribute("account_type","local");
		else
			$account->addAttribute("account_type","share");
		
		$dbname = $row1["Username"];
		
		$sql = "";
		if(DB_IS_MSSQL){
			$sql =  " select DISTINCT device.UID as device_uid, device.ModelName as device_name, device.Nickname as device_nickname from ".$_SESSION["username"].".dbo.group_data ";
			$sql .= " join ".$dbname.".dbo.module on group_data.ModuleUID =  ".$dbname.".dbo.module.UID AND group_data.DeviceUID = ".$dbname.".dbo.module.DeviceUID ";
			$sql .= " join ".$dbname.".dbo.device on ".$dbname.".dbo.module.DeviceUID = ".$dbname.".dbo.device.UID ";
			$sql .= " where GroupInfoUid=?;";
		}
		else if(DB_IS_MYSQL){
			$sql =  " select DISTINCT device.UID as device_uid, device.ModelName as device_name, device.Nickname as device_nickname from ".$_SESSION["username"].".group_data ";
			$sql .= " join ".$dbname.".module on group_data.ModuleUID =  ".$dbname.".module.UID AND group_data.DeviceUID = ".$dbname.".module.DeviceUID ";
			$sql .= " join ".$dbname.".device on ".$dbname.".module.DeviceUID = ".$dbname.".device.UID ";
			$sql .= " where GroupInfoUid=?;";
		}
		else if(DB_IS_ORACLE){
			$sql =  " select DISTINCT device.\"UID\" as device_uid, device.ModelName as device_name, device.Nickname as device_nickname from ".$_SESSION["username"].".group_data ";
			$sql .= " join ".$dbname.".module on group_data.ModuleUID =  ".$dbname.".module.\"UID\" AND group_data.DeviceUID = ".$dbname.".module.DeviceUID ";
			$sql .= " join ".$dbname.".device on ".$dbname.".module.DeviceUID = ".$dbname.".device.\"UID\" ";
			$sql .= " where GroupInfoUid=?;";
		}
		$stmt2 = $conn->prepare($sql);			
		$stmt2->execute(array($_POST["group_uid"]));

		while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
			
			$controller = $account->addChild('controller');
			$controller->addAttribute('controller_uid', $row2["device_uid"]);
			$controller->addAttribute('controller_name', $row2["device_name"]);
			$controller->addAttribute('controller_nickname', $row2["device_nickname"]);
			//interface
			$sql = "";
			if(DB_IS_MSSQL){
				$sql .= " select DISTINCT device.UID as device_uid, device.ModelName as device_name, device.Nickname as device_nickname,Interface from ".$_SESSION["username"].".dbo.group_data ";
				$sql .= " join ".$dbname.".dbo.module on group_data.ModuleUID = ".$dbname.".dbo.module.UID ";
				$sql .= " join ".$dbname.".dbo.device on ".$dbname.".dbo.module.DeviceUID = ".$dbname.".dbo.device.UID ";
				$sql .= " where GroupInfoUid=? AND group_data.AccountUID=? AND ".$dbname.".dbo.device.UID = ? AND group_data.ModuleUid <> 'ir';";
			}
			else if(DB_IS_MYSQL){
				$sql .= " select DISTINCT device.UID as device_uid, device.ModelName as device_name, device.Nickname as device_nickname,Interface from ".$_SESSION["username"].".group_data ";
				$sql .= " join ".$dbname.".module on group_data.ModuleUID = ".$dbname.".module.UID ";
				$sql .= " join ".$dbname.".device on ".$dbname.".module.DeviceUID = ".$dbname.".device.UID ";
				$sql .= " where GroupInfoUid=? AND group_data.AccountUID=? AND ".$dbname.".device.UID = ? AND group_data.ModuleUid <> 'ir';";
			}
			else if(DB_IS_ORACLE){
				$sql .= " select DISTINCT device.\"UID\" as device_uid, device.ModelName as device_name, device.Nickname as device_nickname,Interface from ".$_SESSION["username"].".group_data ";
				$sql .= " join ".$dbname.".module on group_data.ModuleUID = ".$dbname.".module.\"UID\" ";
				$sql .= " join ".$dbname.".device on ".$dbname.".module.DeviceUID = ".$dbname.".device.\"UID\" ";
				$sql .= " where GroupInfoUid=? AND group_data.AccountUID=? AND ".$dbname.".device.\"UID\" = ? AND group_data.ModuleUid <> 'ir';";
			}
			$stmt3 = $conn->prepare($sql);
			
			$stmt3->execute(array($_POST["group_uid"],$row1["AccountUID"],$row2["device_uid"]));
			while($row3 = $stmt3->fetch(PDO::FETCH_ASSOC)){
				$interface = $controller->addChild('interface');
				$interface->addAttribute('interface_uid', $row3["Interface"]);
				$interface->addAttribute('controller_uid', $row3["device_uid"]);
				$interface->addAttribute('controller_name', $row3["device_name"]);
				$interface->addAttribute('controller_nickname', $row3["device_nickname"]);
				//
				////module
				$sql = "";
				if(DB_IS_MSSQL){
					$sql .= " select DISTINCT ModuleUID,group_data.DeviceUID as device_uid, Interface,module.Manufacturer as manufacturer,module.ModelName as model_name,module.Nickname as nickname,module.Phase from ".$_SESSION["username"].".dbo.group_data ";
					$sql .= " join ".$dbname.".dbo.module on group_data.ModuleUID = ".$dbname.".dbo.module.UID AND group_data.DeviceUID = ".$dbname.".dbo.module.DeviceUID ";
					$sql .= " where GroupInfoUID=? AND group_data.DeviceUID=? AND Interface=? AND group_data.AccountUID=?; ";
				}
				else if(DB_IS_MYSQL){
					$sql .= " select DISTINCT ModuleUID,group_data.DeviceUID as device_uid, Interface,module.Manufacturer as manufacturer,module.ModelName as model_name,module.Nickname as nickname,module.Phase from ".$_SESSION["username"].".group_data ";
					$sql .= " join ".$dbname.".module on group_data.ModuleUID = ".$dbname.".module.UID AND group_data.DeviceUID = ".$dbname.".module.DeviceUID ";
					$sql .= " where GroupInfoUID=? AND group_data.DeviceUID=? AND Interface=? AND group_data.AccountUID=?; ";
				}
				else if(DB_IS_ORACLE){
					$sql .= " select DISTINCT ModuleUID,group_data.DeviceUID as device_uid, Interface,module.Manufacturer as manufacturer,module.ModelName as model_name,module.Nickname as nickname,module.Phase from ".$_SESSION["username"].".group_data ";
					$sql .= " join ".$dbname.".module on group_data.ModuleUID = ".$dbname.".module.\"UID\" AND group_data.DeviceUID = ".$dbname.".module.DeviceUID ";
					$sql .= " where GroupInfoUID=? AND group_data.DeviceUID=? AND Interface=? AND group_data.AccountUID=?; ";
				}
				$stmt4 = $conn->prepare($sql);
				$stmt4->execute(array($_POST["group_uid"],$row3["device_uid"],$row3["Interface"],$row1["AccountUID"]));
				
				while($row4 = $stmt4->fetch(PDO::FETCH_ASSOC)){
					$module = $interface->addChild('module');
					$module->addAttribute('module_uid', $row4["ModuleUID"]);
					$module->addAttribute('controller_uid', $row4["device_uid"]);
					$module->addAttribute('interface', $row4["Interface"]);
					$module->addAttribute('manufacturer', $row4["manufacturer"]);
					$module->addAttribute('model_name', $row4["model_name"]);
					$module->addAttribute('nickname', $row4["nickname"]);
					
					$phase = $row4["Phase"];
					
					
					$stmt5 = $conn->prepare(
						DB_IS_MSSQL  ? "select UID as data_uid, DeviceUID ,ModuleUID,group_data.Loop as loop from ".$_SESSION["username"].".dbo.group_data where GroupInfoUID=? AND DeviceUID=? AND ModuleUID=? AND AccountUID = ? ORDER BY loop;" : (
						DB_IS_MYSQL  ? "select UID as data_uid, DeviceUID ,ModuleUID,group_data.`Loop` as `loop` from ".$_SESSION["username"].".group_data where GroupInfoUID=? AND DeviceUID=? AND ModuleUID=? AND AccountUID = ? ORDER BY `loop`;" : (
						DB_IS_ORACLE ? "select \"UID\" as data_uid, DeviceUID ,ModuleUID,group_data.Loop as loop from ".$_SESSION["username"].".group_data where GroupInfoUID=? AND DeviceUID=? AND ModuleUID=? AND AccountUID = ? ORDER BY loop;" : null)));
					$stmt5->execute(array($_POST["group_uid"],$row4["device_uid"],$row4["ModuleUID"], $row1["AccountUID"]));
					
					while($row5 = $stmt5->fetch(PDO::FETCH_ASSOC)){
 						$loop = $module->addChild('loop');
						$loop->addAttribute('data_uid', $row5["data_uid"]);
						$loop->addAttribute('controller_uid', $row5["DeviceUID"]);
						$loop->addAttribute('module_uid', $row5["ModuleUID"]);
						$loop->addAttribute('loop', $row5["loop"]);
						if($phase == 3){
							$stmt6 = $conn->prepare(
								DB_IS_MSSQL  ? "SELECT * FROM ".$dbname.".dbo.channel WHERE DeviceUID='".$row5["DeviceUID"]."' AND ModuleUID='".$row5["ModuleUID"]."' AND Loop='".$row5["loop"]."' AND Phase='4';" : (
								DB_IS_MYSQL  ? "SELECT * FROM ".$dbname.".channel WHERE DeviceUID='".$row5["DeviceUID"]."' AND ModuleUID='".$row5["ModuleUID"]."' AND `Loop`='".$row5["loop"]."' AND Phase='4';" : (
								DB_IS_ORACLE ? "SELECT * FROM ".$dbname.".channel WHERE DeviceUID='".$row5["DeviceUID"]."' AND ModuleUID='".$row5["ModuleUID"]."' AND Loop='".$row5["loop"]."' AND Phase='4';" : null))
							);
						}
						else{
							$stmt6 = $conn->prepare(
								DB_IS_MSSQL  ? "SELECT * FROM ".$dbname.".dbo.channel WHERE DeviceUID='".$row5["DeviceUID"]."' AND ModuleUID='".$row5["ModuleUID"]."' AND Loop='".$row5["loop"]."';" : (
								DB_IS_MYSQL  ? "SELECT * FROM ".$dbname.".channel WHERE DeviceUID='".$row5["DeviceUID"]."' AND ModuleUID='".$row5["ModuleUID"]."' AND `Loop`='".$row5["loop"]."';" : (
								DB_IS_ORACLE ? "SELECT * FROM ".$dbname.".channel WHERE DeviceUID='".$row5["DeviceUID"]."' AND ModuleUID='".$row5["ModuleUID"]."' AND Loop='".$row5["loop"]."';" : null))
							);
						}
						$stmt6->execute();
						
						$row = $stmt6->fetch(PDO::FETCH_ASSOC);
						$stmt6->closeCursor();
						$loop->addAttribute('nickname', $row["Nickname"]);
						
						
						//check channel list
						$checklist = $check_loop->addChild('check_uid');
						$checklist->addAttribute('data_uid', $row5["DeviceUID"]."_".$row5["ModuleUID"]."_".$row5["loop"]."_".$row1["AccountUID"]);
						
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

function load_controller_data(){
	global $login, $lang;
	if($login == false){
		trap("Permission error!");
	}
	
	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);

	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT Username FROM manager.dbo.account WHERE UID = '".$_POST["user_uid"]."';" : (
		DB_IS_MYSQL  ? "SELECT Username FROM manager.account WHERE UID = '".$_POST["user_uid"]."';" : (
		DB_IS_ORACLE ? "SELECT Username FROM manager.\"ACCOUNT\" WHERE \"UID\" = '".$_POST["user_uid"]."';" : null)));
		
	$stmt->execute();
	$user_name = $stmt->fetch(PDO::FETCH_ASSOC)["Username"];
	if(isset($stmt))
		$stmt->closeCursor();
	
	$stmt2 = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT DISTINCT Interface FROM ".$user_name.".dbo.module where DeviceUID=? AND Loop IS NOT NULL ORDER BY Interface;" : (
		DB_IS_MYSQL  ? "SELECT DISTINCT Interface FROM ".$user_name.".module where DeviceUID=? AND `Loop` IS NOT NULL ORDER BY Interface;" : (
		DB_IS_ORACLE ? "SELECT DISTINCT Interface FROM ".$user_name.".module where DeviceUID=? AND Loop IS NOT NULL ORDER BY Interface;" : null)));
		
	$stmt2->execute(array($_POST["controller_uid"]));
	
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><load_controller_data/>');
	while($interface_obj = $stmt2->fetch(PDO::FETCH_ASSOC)){
		
		
		$interface = $xml->addChild('interface');
		$interface->addAttribute('interface_name', $interface_obj["Interface"]);
		
		$stmt3 = $conn->prepare(
			DB_IS_MSSQL  ? "SELECT * FROM ".$user_name.".dbo.module where DeviceUID=? AND Interface=? AND Loop IS NOT NULL AND Type = 1 AND Removed != 1 order by Number; " : (
			DB_IS_MYSQL  ? "SELECT * FROM ".$user_name.".module where DeviceUID=? AND Interface=? AND `Loop` IS NOT NULL AND Type = 1 AND Removed != 1 order by Number;" : (
			DB_IS_ORACLE ? "SELECT * FROM ".$user_name.".module where DeviceUID=? AND Interface=? AND Loop IS NOT NULL AND \"TYPE\" = 1 AND Removed != 1 order by \"NUMBER\";" : null)));
		
		$stmt3->execute(array($_POST["controller_uid"],$interface_obj["Interface"]));
		while($module_obj = $stmt3->fetch(PDO::FETCH_ASSOC)){
			$module = $interface->addChild('module');
			
			$module->addAttribute('uid', $module_obj["UID"]);
			$module->addAttribute('controller_uid', $module_obj["DeviceUID"]);
			$module->addAttribute('interface', $module_obj["Interface"]);
			$module->addAttribute('manufacturer', $module_obj["Manufacturer"]);
			$module->addAttribute('model_name', $module_obj["ModelName"]);
			$module->addAttribute('nickname', $module_obj["Nickname"]);
			$module->addAttribute('loop', $module_obj["Loop"]);
			$module->addAttribute('phase', $module_obj["Phase"]);
			
			if($module_obj["Phase"]==3){
				$stmt4 = $conn->prepare(
					DB_IS_MSSQL  ? "SELECT * FROM ".$user_name.".dbo.channel WHERE DeviceUID='".$module_obj["DeviceUID"]."' and ModuleUID='".$module_obj["UID"]."' and Phase='4' ORDER BY Loop;" : (
					DB_IS_MYSQL  ? "SELECT * FROM ".$user_name.".channel WHERE DeviceUID='".$module_obj["DeviceUID"]."' and ModuleUID='".$module_obj["UID"]."' and Phase='4' ORDER BY `Loop`;" : (
					DB_IS_ORACLE ? "SELECT * FROM ".$user_name.".channel WHERE DeviceUID='".$module_obj["DeviceUID"]."' and ModuleUID='".$module_obj["UID"]."' and Phase='4' ORDER BY Loop;" : null)));
				
				$stmt4->execute();
				
				$channel_nickname = "";
				while($channel_obj = $stmt4->fetch(PDO::FETCH_ASSOC)){
					$channel_nickname .= $channel_obj["Nickname"].",";
				}
				$stmt4->closeCursor();
				$module->addAttribute('channel_nickname', substr($channel_nickname,0,-1));
			}
			else{
				$stmt4 = $conn->prepare(
					DB_IS_MSSQL  ? "SELECT * FROM ".$user_name.".dbo.channel WHERE DeviceUID='".$module_obj["DeviceUID"]."' and ModuleUID='".$module_obj["UID"]."' ORDER BY Loop;" : (
					DB_IS_MYSQL  ? "SELECT * FROM ".$user_name.".channel WHERE DeviceUID='".$module_obj["DeviceUID"]."' and ModuleUID='".$module_obj["UID"]."' ORDER BY `Loop`;" : (
					DB_IS_ORACLE ? "SELECT * FROM ".$user_name.".channel WHERE DeviceUID='".$module_obj["DeviceUID"]."' and ModuleUID='".$module_obj["UID"]."' ORDER BY Loop;" : null)));
				
				$stmt4->execute();
				
				$channel_nickname = "";
				while($channel_obj = $stmt4->fetch(PDO::FETCH_ASSOC)){
					$channel_nickname .= $channel_obj["Nickname"].",";
				}
				$stmt4->closeCursor();
				$module->addAttribute('channel_nickname', substr($channel_nickname,0,-1));
			}
			
		}
		if(isset($stmt3))
			$stmt3->closeCursor();
	}
	if(isset($stmt2))
		$stmt2->closeCursor();
	
	$conn = null;	
	header('Content-type: text/xml');
	print($xml->asXML());
}


function del_group_data(){
	global $login, $lang;
	if($login == false){
		trap("Permission error!");
	}
	
	$GroupDataArray=$_POST["group_data_uid_array"];
	$sql="";
	for($i=0;$i<count($GroupDataArray);$i++)
	{
		if(DB_IS_MSSQL){
			if($i==0)
				$sql .= " DELETE from ".$_SESSION["username"].".dbo.group_data Where UID = ".$GroupDataArray[$i]." ";
			else
				$sql .= " or UID = ".$GroupDataArray[$i]." ";
		}
		else if(DB_IS_MYSQL){
			if($i==0)
				$sql .= " DELETE from ".$_SESSION["username"].".group_data Where UID = ".$GroupDataArray[$i]." ";
			else
				$sql .= " or UID = ".$GroupDataArray[$i]." ";
		}
		else if(DB_IS_ORACLE){
			if($i==0)
				$sql .= " DELETE from ".$_SESSION["username"].".group_data Where \"UID\" = ".$GroupDataArray[$i]." ";
			else
				$sql .= " or \"UID\" = ".$GroupDataArray[$i]." ";
		}
	}
	
	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	
	$stmt->closeCursor();
	$conn = null;
}
?>