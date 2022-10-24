<?php
set_time_limit(0);
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
	try{
		$port = 1230;
		$address = "127.0.0.1";

		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		socket_connect($socket, $address, $port);

		$in = '<cmd type="reportList" account_uid="' . $_SESSION['account_uid'] . '" username="' . $_SESSION['username'] . '" />';
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

function get_module_list(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}

	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
	$username = "";
	if($_POST['account_uid'] != $_SESSION["account_uid"]){
		// shared
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "SELECT * FROM manager.dbo.share, manager.dbo.account WHERE share.AccountUIDShareBy = ? AND share.AccountUIDShareTo = ? AND share.AccountUIDShareBy = account.UID;" : (
			DB_IS_MYSQL  ? "SELECT * FROM manager.share, manager.account WHERE share.AccountUIDShareBy = ? AND share.AccountUIDShareTo = ? AND share.AccountUIDShareBy = account.UID;" : (
			DB_IS_ORACLE ? "SELECT * FROM manager.\"SHARE\", manager.\"ACCOUNT\" WHERE \"SHARE\".AccountUIDShareBy = ? AND \"SHARE\".AccountUIDShareTo = ? AND \"SHARE\".AccountUIDShareBy = \"ACCOUNT\".\"UID\";" : null)));
		$stmt->execute(array($_POST['account_uid'], $_SESSION["account_uid"]));
		$obj = $stmt->fetch(PDO::FETCH_ASSOC);
		$username = $obj["Username"];
		$stmt->closeCursor();
	}
	else{
		$username = $_SESSION["username"];
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><list/>');

	// Select module
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT * FROM ".$username.".dbo.module WHERE DeviceUID = ? AND Removed = 0 ORDER BY Number;" : (
		DB_IS_MYSQL  ? "SELECT * FROM ".$username.".module WHERE DeviceUID = ? AND Removed = 0 ORDER BY Number;" : (
		DB_IS_ORACLE ? "SELECT * FROM ".strtoupper($username).".module WHERE DeviceUID = ? AND Removed = 0 ORDER BY \"NUMBER\";" : null)));

	$stmt->execute(array($_POST['device_uid']));

	// Table exist check
	$obj_array = array();

	$table_name = "";
	
	while($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
		
		if($table_name != ""){
			$table_name .= " OR ";
		}

		$table_name .= "TABLE_NAME = '" . "uid_" . $_POST['device_uid'] . "_" . $obj["UID"] . "'";
		
		if(DB_IS_MSSQL)
			$obj_array["uid_" . $_POST['device_uid'] . "_" . $obj["UID"]] = $obj;
		else if(DB_IS_MYSQL)
			$obj_array["uid_" . $_POST['device_uid'] . "_" . $obj["UID"]] = $obj;
		else if(DB_IS_ORACLE)
			$obj_array[strtoupper("uid_" . $_POST['device_uid'] . "_" . $obj["UID"])] = $obj;
	}
	
	$stmt->closeCursor();

	if($table_name != ""){
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "SELECT TABLE_NAME AS [TableName] FROM ".$username.".INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND (" . $table_name . ");" : (
			DB_IS_MYSQL  ? "SELECT TABLE_NAME AS TableName FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '".$username."' AND TABLE_TYPE = 'BASE TABLE' AND (" . $table_name . ");" : (
			DB_IS_ORACLE ? "SELECT TABLE_NAME AS TableName FROM SYS.all_tables WHERE OWNER = '".strtoupper($username)."' AND (" . strtoupper($table_name) . ");" : null)));
		$stmt->execute();

		while($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
			if(array_key_exists($obj["TableName"], $obj_array)){
				
		    	$module = $xml->addChild('module');
				$module->addAttribute('uid', $obj_array[$obj["TableName"]]["UID"]);
				$module->addAttribute('interface', $obj_array[$obj["TableName"]]["Interface"]);
				$module->addAttribute('number', $obj_array[$obj["TableName"]]["Number"]);
				$module->addAttribute('manufacturer', $obj_array[$obj["TableName"]]["Manufacturer"]);
				$module->addAttribute('model_name', $obj_array[$obj["TableName"]]["ModelName"]);
				$module->addAttribute('nickname', $obj_array[$obj["TableName"]]["Nickname"]);
				$module->addAttribute('loop', $obj_array[$obj["TableName"]]["Loop"]);
				$module->addAttribute('phase', $obj_array[$obj["TableName"]]["Phase"]);
				$module->addAttribute('type', $obj_array[$obj["TableName"]]["Type"]);
			}
		}
		$stmt->closeCursor();
	}

	// Close connection
	$conn = null;

	header('Content-type: text/xml');
	print($xml->asXML());
}

function get_channel_list(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}
	
	$is_shared = false;
	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
	$username = "";
	if($_POST['account_uid'] != $_SESSION["account_uid"]){// shared
		$is_shared = true;
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "SELECT * FROM manager.dbo.share, manager.dbo.account WHERE share.AccountUIDShareBy = ? AND share.AccountUIDShareTo = ? AND share.AccountUIDShareBy = account.UID;" : (
			DB_IS_MYSQL  ? "SELECT * FROM manager.share, manager.account WHERE share.AccountUIDShareBy = ? AND share.AccountUIDShareTo = ? AND share.AccountUIDShareBy = account.UID;" : (
			DB_IS_ORACLE ? "SELECT * FROM manager.\"SHARE\", manager.\"ACCOUNT\" WHERE \"SHARE\".AccountUIDShareBy = ? AND \"SHARE\".AccountUIDShareTo = ? AND \"SHARE\".AccountUIDShareBy = \"ACCOUNT\".\"UID\";" : null)));
		$stmt->execute(array($_POST['account_uid'], $_SESSION["account_uid"]));
		if($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
			$username = $obj["Username"];
		}
		else{
			trap("Permission error!");
		}
	}
	else{
		$is_shared = false;
		$username = $_SESSION["username"];
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><list/>');
	
	// Select channel
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT * FROM ".$username.".dbo.module WHERE UID = ? AND DeviceUID = ?;" : (
		DB_IS_MYSQL  ? "SELECT * FROM ".$username.".module WHERE UID = ? AND DeviceUID = ?;" : (
		DB_IS_ORACLE ? "SELECT * FROM ".$username.".module WHERE \"UID\" = ? AND DeviceUID = ?;" : null))
	);
	$stmt->execute(array($_POST['module_uid'], $_POST['device_uid']));
	//
	
	if($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
		$xml->addChild('Type')->addAttribute("value", $obj["Type"]);
		if($obj["Type"] == 1){//PM
			for($loopIndex = 0; $loopIndex < $obj["Loop"]; $loopIndex++) {
				if($obj["Phase"] == 3)
					$stmt2 = $conn->prepare(
						DB_IS_MSSQL  ? "SELECT * FROM ".$username.".dbo.channel WHERE DeviceUID = '".$_POST['device_uid']."' AND ModuleUID = '".$_POST['module_uid']."' AND Loop = " .($loopIndex+1). " AND Phase = 4;" : (
						DB_IS_MYSQL  ? "SELECT * FROM ".$username.".channel WHERE DeviceUID = '".$_POST['device_uid']."' AND ModuleUID = '".$_POST['module_uid']."' AND `Loop` = " .($loopIndex+1). " AND Phase = 4;" : (
						DB_IS_ORACLE ? "SELECT * FROM ".$username.".channel WHERE DeviceUID = '".$_POST['device_uid']."' AND ModuleUID = '".$_POST['module_uid']."' AND Loop = " .($loopIndex+1). " AND Phase = 4;" : null))
					);
				else
					$stmt2 = $conn->prepare(
						DB_IS_MSSQL  ? "SELECT * FROM ".$username.".dbo.channel WHERE DeviceUID = '".$_POST['device_uid']."' AND ModuleUID = '".$_POST['module_uid']."' AND Loop = " .($loopIndex+1). ";" : (
						DB_IS_MYSQL  ? "SELECT * FROM ".$username.".channel WHERE DeviceUID = '".$_POST['device_uid']."' AND ModuleUID = '".$_POST['module_uid']."' AND `Loop` = " .($loopIndex+1). ";" : (
						DB_IS_ORACLE ? "SELECT * FROM ".$username.".channel WHERE DeviceUID = '".$_POST['device_uid']."' AND ModuleUID = '".$_POST['module_uid']."' AND Loop = " .($loopIndex+1). ";" : null))
					);
				
				$stmt2->execute();
				
				$obj2 = $stmt2->fetch(PDO::FETCH_ASSOC);;
				$loop = $xml->addChild('loop');
				$loop->addAttribute('index', $loopIndex);
				$loop->addAttribute('nickname', $obj2["Nickname"]);
			}
		}
		else{//IO、IR
			$channels = explode(",", $obj["Channel"]);
			foreach($channels as $channel) {
				$stmt2 = $conn->prepare(
					DB_IS_MSSQL  ? "SELECT * FROM ".$username.".dbo.channel WHERE DeviceUID = ? AND ModuleUID = ? AND Channel = ?" : (
					DB_IS_MYSQL  ? "SELECT * FROM ".$username.".channel WHERE DeviceUID = ? AND ModuleUID = ? AND Channel = ?" : (
					DB_IS_ORACLE ? "SELECT * FROM ".$username.".channel WHERE DeviceUID = ? AND ModuleUID = ? AND Channel = ?" : null))
				);
				$stmt2->execute(array($_POST['device_uid'],$_POST['module_uid'], $channel ));
				$obj2 = $stmt2->fetch(PDO::FETCH_ASSOC);;
				$module = $xml->addChild('channel');
				$module->addAttribute('nickname', $obj2["Nickname"]);
				$module->addAttribute('name', $channel);
			}
		}
		$stmt2->closeCursor();
	}
	$stmt->closeCursor();
	$conn = null;
	header('Content-type: text/xml');
	print($xml->asXML());
}

function load_report_data(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}
	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
	/*--------傳遞參數----------------------------*/
	$accountUID = $_POST["accountUID"];
	$DeviceUID = $_POST["DeviceUID"];
	$moduleUID = $_POST["moduleUID"];
	$Loop = $_POST["Loop"];
	$Channel = $_POST["Channel"];
	$datetime_begin =$_POST["datetime_begin"];//選擇的日期
	$datetime_end =$_POST["datetime_end"];//選擇的日期
	$report_type = $_POST["report_type"];//報表類型：日報-DAY、月報-MONTH、年-YEAR
	$timezone = $_POST["timezone"];//時區
	
	if(DB_IS_ORACLE){
		if (strpos($timezone, '+') !== false) 
			$timezone=str_replace('+','-',$timezone);
		else
			$timezone=str_replace('-','+',$timezone);
	}
	
	//對比參數
	$compare_datetime_begin = $_POST["compare_datetime_begin"];
	$compare_datetime_end = $_POST["compare_datetime_end"];
	/*--------------------------------------*/
	$table_name = "";
	if($_POST["accountUID"] != ""){
		$accountname = "";
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "select UID, Username from manager.dbo.account where UID = '".$accountUID."';" : (
			DB_IS_MYSQL  ? "select UID, Username from manager.account where UID = '".$accountUID."';" : (
			DB_IS_ORACLE ? "select \"UID\", Username from manager.\"ACCOUNT\" where \"UID\" = '".$accountUID."';" : null))
		);
		$stmt->execute();
		

		While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
			$accountname = $obj["Username"];
		}
	}
	else
		$accountname = $_SESSION["username"];
	$stmt->closeCursor();
	
	//確認是否為單向三相電錶
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT Type,Phase FROM ".$accountname.".dbo.Module WHERE DeviceUID = '".$DeviceUID."' AND UID = '".$moduleUID."';" : (
		DB_IS_MYSQL  ? "SELECT Type,Phase FROM ".$accountname.".Module WHERE DeviceUID = '".$DeviceUID."' AND UID = '".$moduleUID."';" : (
		DB_IS_ORACLE ? "SELECT Type,Phase FROM ".$accountname.".Module WHERE DeviceUID = '".$DeviceUID."' AND \"UID\" = '".$moduleUID."';" : null))
	);
	
	$stmt->execute();
	$type = 0;
	$phase = 1;	
	While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
		$type = $obj["Type"];
		if($obj["Phase"] == "1")//單相電錶
			$phase = 1;
		else
			$phase = 4;
	}
	$report_datetime = "";
	/*查詢報表*/
	$Date_array = explode("-",$selectDate);
	$table_time = "";
	if($report_type =="DAY"){
		$table_time = "HOUR";
	}
	else if($report_type =="MONTH"){
		$table_time = "DAY";
	}
	else if($report_type =="WEEK"){
		$table_time = "WEEKDAY";
	}
	else if($report_type =="YEAR"||$report_type =="QUARTER"){
		$table_time = "MONTH";
	}
	
	if($compare_datetime_begin==""){//無對比
		if($type == "1")//PM
			load_PM_report_data($conn,$accountname,$DeviceUID,$moduleUID,$datetime_begin,$datetime_end,$timezone,$report_type,$table_time,$Loop,$phase);
		else//IO
			load_IO_report_data($conn,$accountname,$DeviceUID,$moduleUID,$datetime_begin,$datetime_end,$timezone,$report_type,$table_time,$Channel);
	}
	else{//對比
		if($type == "1")//PM
			load_PM_report_data_compare($conn,$accountname,$DeviceUID,$moduleUID,$datetime_begin,$datetime_end,$timezone,$report_type,$table_time,$Loop,$phase,$compare_datetime_begin,$compare_datetime_end);
		else//IO
			load_IO_report_data_compare($conn,$accountname,$DeviceUID,$moduleUID,$datetime_begin,$datetime_end,$timezone,$report_type,$table_time,$Channel,$compare_datetime_begin,$compare_datetime_end);
	}
}

function load_PM_report_data($conn,$accountname,$DeviceUID,$moduleUID,$datetime_begin,$datetime_end,$timezone,$report_type,$table_time,$Loop,$phase){
	$total_kwh = 0;
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><info/>');
	
	$sql = "";
	if($phase == 1){//單相電表
		if(DB_IS_MSSQL){
			$sql .= " select DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."')) AS Time, Max(ABS(Demand)) as Demand,SUM(DeltaTotalKWH) as kWh,AVG(ABS(PF)) as PF,AVG(I) as I,AVG(V) as V,AVG(KVA) as kVA,AVG(KVAR) as kvar from ( ";
			$sql .= " select * from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID." ";
			$sql .= " where Loop='".$Loop."' and phase='".$phase."' and DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."' ";
			$sql .= " ) AS subquery GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."')) ORDER BY DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."'))";
		}
		else if(DB_IS_MYSQL){
			$table_time_code = "";
			if($table_time == "HOUR") $table_time_code = "%H";
			else if($table_time == "DAY") $table_time_code = "%d";
			else if($table_time == "WEEKDAY") $table_time_code = "%w";
			else if($table_time == "MONTH") $table_time_code = "%m";
			
			$sql .= " select DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'),'".$table_time_code."') AS `Time`, Max(ABS(Demand)) as Demand,SUM(DeltaTotalKWH) as kWh,AVG(ABS(PF)) as PF,AVG(I) as I,AVG(V) as V,AVG(KVA) as kVA,AVG(KVAR) as kvar from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." ";
			$sql .= " where `Loop`='".$Loop."' and `phase`='".$phase."' and CONVERT_TZ(`DateTime`,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(`DateTime`,'+00:00','".$timezone."') <= '".$datetime_end."' ";
			$sql .= " ) AS subquery GROUP BY DATE_FORMAT(CONVERT_TZ(`DateTime`,'+00:00','".$timezone."'),'".$table_time_code."') ORDER BY DATE_FORMAT(CONVERT_TZ(`DateTime`,'+00:00','".$timezone."'),'".$table_time_code."')";
		}
		else if(DB_IS_ORACLE){
			$table_time_code = "";
			if($table_time == "HOUR") $table_time_code = "HH24";
			else if($table_time == "DAY") $table_time_code = "DD";
			else if($table_time == "WEEKDAY") $table_time_code = "D";
			else if($table_time == "MONTH") $table_time_code = "MM";
			
			$sql .= " select TO_CHAR(sys_extract_utc(from_tz(DATETIME,'".$timezone."')),'".$table_time_code."') AS \"TIME\", Max(ABS(\"DEMAND\")) as \"DEMAND\",SUM(DeltaTotalKWH) as kWh,AVG(ABS(PF)) as PF,AVG(I) as I,AVG(V) as V,AVG(KVA) as kVA,AVG(KVAR) as kvar from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." ";
			$sql .= " where Loop='".$Loop."' and \"PHASE\"='".$phase."' and sys_extract_utc(from_tz(\"DATETIME\",'".$timezone."')) >= TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and sys_extract_utc(from_tz(\"DATETIME\",'".$timezone."')) <= TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS') ";
			$sql .= " ) subquery GROUP BY TO_CHAR(sys_extract_utc(from_tz(DATETIME,'".$timezone."')),'".$table_time_code."') ORDER BY TO_CHAR(sys_extract_utc(from_tz(DATETIME,'".$timezone."')),'".$table_time_code."')";
		}
	}
	
	else{//三相電表
		$sql ="";
		if(DB_IS_MSSQL){
			$sql .= " select total.Time as Time,Demand,kWh,PF,I_A,V_A,I_B,V_B,I_C,V_C,kVA,kvar from ";
			$sql .= " (select DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."')) AS Time, Max(ABS(Demand)) as Demand,SUM(DeltaTotalKWH) as kWh,AVG(ABS(PF)) as PF,AVG(KVA) as kVA,AVG(KVAR) as kvar from ( ";
			$sql .= " select * from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID."  where Loop='".$Loop."'and Phase='4' and DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."' ";
			$sql .= "  ) AS subquery GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."'))) AS total ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= " select DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."')) AS Time, AVG(I) as I_A,AVG(V) as V_A from ( ";
			$sql .= " select * from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID."  where Loop='".$Loop."' and Phase='1' and DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."' ";
			$sql .= " ) AS subquery GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."'))) AS Subquery) As A on total.Time= A.Time ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= "  select DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."')) AS Time, AVG(I) as I_B,AVG(V) as V_B from ( ";
			$sql .= " select * from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID."  where Loop='".$Loop."' and Phase='2' and DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."' ";
			$sql .= " ) AS subquery GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."'))) AS Subquery) As B on total.Time= B.Time ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= "  select DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."')) AS Time, AVG(I) as I_C,AVG(V) as V_C from ( ";
			$sql .= " select * from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID."  where Loop='".$Loop."' and Phase='3' and DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."'  ";
			$sql .= " ) AS subquery GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."'))) AS Subquery) As C on total.Time= C.Time ";
			$sql .= " order by total.Time ;";
		}
		else if(DB_IS_MYSQL){
			$table_time_code = "";
			if($table_time == "HOUR") $table_time_code = "%H";
			else if($table_time == "DAY") $table_time_code = "%d";
			else if($table_time == "WEEKDAY") $table_time_code = "%w";
			else if($table_time == "MONTH") $table_time_code = "%m";
			
			$sql .= " select total.`Time` as `Time`,Demand,kWh,PF,I_A,V_A,I_B,V_B,I_C,V_C,kVA,kvar from ";
			$sql .= " (select DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS `Time`, Max(ABS(Demand)) as Demand,SUM(DeltaTotalKWH) as kWh,AVG(ABS(PF)) as PF,AVG(KVA) as kVA,AVG(KVAR) as kvar from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." where `Loop`='".$Loop."'and `Phase`='4' and CONVERT_TZ(`DateTime`,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(`DateTime`,'+00:00','".$timezone."') <= '".$datetime_end."' ";
			$sql .= "  ) AS subquery GROUP BY DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS total ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= " select DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS `Time`, AVG(I) as I_A,AVG(V) as V_A from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where `Loop`='".$Loop."' and `Phase`='1' and CONVERT_TZ(`DateTime`,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(`DateTime`,'+00:00','".$timezone."') <= '".$datetime_end."' ";
			$sql .= " ) AS subquery GROUP BY DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS Subquery) As A on total.`Time`= A.`Time` ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= "  select DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time, AVG(I) as I_B,AVG(V) as V_B from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where `Loop`='".$Loop."' and `Phase`='2' and CONVERT_TZ(`DateTime`,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(`DateTime`,'+00:00','".$timezone."') <= '".$datetime_end."' ";
			$sql .= " ) AS subquery GROUP BY DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS Subquery) As B on total.`Time`= B.`Time` ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= "  select DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time, AVG(I) as I_C,AVG(V) as V_C from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where `Loop`='".$Loop."' and `Phase`='3' and CONVERT_TZ(`DateTime`,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(`DateTime`,'+00:00','".$timezone."') <= '".$datetime_end."'  ";
			$sql .= " ) AS subquery GROUP BY DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS Subquery) As C on total.`Time`= C.`Time` ";
			$sql .= " order by total.`Time` ;";		
		}
		else if(DB_IS_ORACLE){
			$table_time_code = "";
			if($table_time == "HOUR") $table_time_code = "HH24";
			else if($table_time == "DAY") $table_time_code = "DD";
			else if($table_time == "WEEKDAY") $table_time_code = "D";
			else if($table_time == "MONTH") $table_time_code = "MM";
			
			$sql .= " select total.\"TIME\" as \"TIME\",\"DEMAND\",kWh,PF,I_A,V_A,I_B,V_B,I_C,V_C,kVA,kvar from ";
			$sql .= " (select TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')), '".$table_time_code."') AS \"TIME\", Max(ABS(\"DEMAND\")) as \"DEMAND\",SUM(DeltaTotalKWH) as kWh,AVG(ABS(PF)) as PF,AVG(KVA) as kVA,AVG(KVAR) as kvar from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." where Loop='".$Loop."'and \"PHASE\"='4' and sys_extract_utc(from_tz(DateTime,'".$timezone."')) >= TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and sys_extract_utc(from_tz(DateTime,'".$timezone."')) <= TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS') ";
			$sql .= "  ) subquery GROUP BY TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')), '".$table_time_code."')) total ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= " select TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')), '".$table_time_code."') AS \"TIME\", AVG(I) as I_A,AVG(V) as V_A from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where Loop='".$Loop."' and \"PHASE\"='1' and sys_extract_utc(from_tz(DateTime,'".$timezone."')) >= TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and sys_extract_utc(from_tz(DateTime,'".$timezone."')) <= TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS') ";
			$sql .= " ) subquery GROUP BY TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')), '".$table_time_code."')) Subquery) A on total.\"TIME\"= A.\"TIME\" ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= "  select TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')), '".$table_time_code."') AS \"TIME\", AVG(I) as I_B,AVG(V) as V_B from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where Loop='".$Loop."' and \"PHASE\"='2' and sys_extract_utc(from_tz(DateTime,'".$timezone."')) >= TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and sys_extract_utc(from_tz(DateTime,'".$timezone."')) <= TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS') ";
			$sql .= " ) subquery GROUP BY TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')), '".$table_time_code."')) Subquery) B on total.\"TIME\"= B.\"TIME\" ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= "  select TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')), '".$table_time_code."') AS \"TIME\", AVG(I) as I_C,AVG(V) as V_C from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where Loop='".$Loop."' and \"PHASE\"='3' and sys_extract_utc(from_tz(DateTime,'".$timezone."')) >= TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and sys_extract_utc(from_tz(DateTime,'".$timezone."')) <= TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS')  ";
			$sql .= " ) subquery GROUP BY TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')), '".$table_time_code."')) Subquery) C on total.\"TIME\"= C.\"TIME\" ";
			$sql .= " order by total.\"TIME\" ;";
		}
	}
	
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	
	
	$xml->addAttribute("phase",$phase);
	While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
		$Data = $xml->addChild('Data');
		if($table_time == "WEEKDAY" && DB_IS_MYSQL)
			$Data->addAttribute("Time",$obj["Time"]+1);
		else
			$Data->addAttribute("Time",($obj["Time"]*1));

		if($phase == 1){
			$Data->addAttribute("Demand",floor_dec($obj["Demand"], 3));
			$Data->addAttribute("kWh",floor_dec($obj["kWh"], 3));
			$Data->addAttribute("PF",floor_dec(($obj["PF"])*100, 3));
			$Data->addAttribute("I",floor_dec($obj["I"], 3));
			$Data->addAttribute("V",floor_dec($obj["V"], 3));
			$Data->addAttribute("kVA",floor_dec($obj["kVA"], 3));
			$Data->addAttribute("kvar",floor_dec($obj["kvar"], 3));
			$total_kwh += $obj["kWh"];
		}
		else{
			$Data->addAttribute("Demand",floor_dec($obj["Demand"], 3));
			$Data->addAttribute("kWh",floor_dec($obj["kWh"], 3));
			$Data->addAttribute("PF" ,floor_dec(($obj["PF"])*100, 3));
			$Data->addAttribute("I_A",floor_dec($obj["I_A"], 3));
			$Data->addAttribute("V_A",floor_dec($obj["V_A"], 3));
			$Data->addAttribute("I_B",floor_dec($obj["I_B"], 3));
			$Data->addAttribute("V_B",floor_dec($obj["V_B"], 3));
			$Data->addAttribute("I_C",floor_dec($obj["I_C"], 3));
			$Data->addAttribute("V_C",floor_dec($obj["V_C"], 3));
			$Data->addAttribute("kVA",floor_dec($obj["kVA"], 3));
			$Data->addAttribute("kvar",floor_dec($obj["kvar"],3));
			$total_kwh += $obj["kWh"];
		}
	}
	$stmt->closeCursor();
	
	$sql = "";
	if(DB_IS_MSSQL){
		$sql .= " select convert(varchar,convert(datetime, SWITCHOFFSET(DateTime,'".$timezone."')),120) as DateTime,ABS(Demand) as Demand from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID." "; 
		$sql .= " where Loop='".$Loop."' and phase='".$phase."' and DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."' and ABS(Demand) =( select MAX(ABS(Demand)) from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID."  where Loop='".$Loop."' and phase='".$phase."' and DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."') ";
	}
	else if(DB_IS_MYSQL){
		
		$sql .= " select  CONVERT_TZ(`DateTime`,'+00:00','".$timezone."') as `DateTime`,ABS(Demand) as Demand from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." "; 
		$sql .= " where `Loop`='".$Loop."' and `phase`='".$phase."' and CONVERT_TZ(`DateTime`,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(`DateTime`,'+00:00','".$timezone."') <= '".$datetime_end."' and ABS(Demand) =( select MAX(ABS(Demand)) from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where `Loop`='".$Loop."' and `phase`='".$phase."' and CONVERT_TZ(`DateTime`,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(`DateTime`,'+00:00','".$timezone."') <= '".$datetime_end."'); ";
		
	}
	else if(DB_IS_ORACLE){
		$sql .= " select  TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')),'YYYY-MM-DD HH24:MI:SS') as DateTime,ABS(\"DEMAND\") as \"DEMAND\" from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." "; 
		$sql .= " where Loop='".$Loop."' and phase='".$phase."' and sys_extract_utc(from_tz(DateTime,'".$timezone."')) >= TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and sys_extract_utc(from_tz(DateTime,'".$timezone."')) <= TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS') and ABS(\"DEMAND\") =( select MAX(ABS(\"DEMAND\")) from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where Loop='".$Loop."' and phase='".$phase."' and sys_extract_utc(from_tz(DateTime,'".$timezone."')) >= TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and sys_extract_utc(from_tz(DateTime,'".$timezone."')) <= TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS')); ";
	}
	
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	//最高需量//總用電量
	While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
		$Data = $xml->addChild('Other');
		$Data->addAttribute("Time",$obj["DateTime"]);
		$Data->addAttribute("Max_Demand",floor_dec($obj["Demand"],3));
		$Data->addAttribute("total_kwh",floor_dec($total_kwh, 3));
		break;
	}
	$stmt->closeCursor();
		
	$conn = null;
	
	header('Content-type: text/xml');
	print($xml->asXML());
}

function load_PM_report_data_compare($conn,$accountname,$DeviceUID,$moduleUID,$datetime_begin,$datetime_end,$timezone,$report_type,$table_time,$Loop,$phase,$compare_datetime_begin,$compare_datetime_end){
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><info/>');
	
	$sql = "";
	if($phase == 1){//單相電表
		if(DB_IS_MSSQL){
			$sql .= " select ISNULL(major.Time,compare.Time) AS Time, major.Demand AS Demand,major.kWh AS kWh,major.PF AS PF,major.I AS I,major.V AS V,major.kVA AS kVA,major.kvar AS kvar, compare.Demand AS c_Demand ,compare.kWh AS c_kWh ,compare.PF AS c_PF,compare.I AS c_I,compare.V AS c_V,compare.kVA AS c_kVA,compare.kvar AS c_kvar from( ";
			$sql .= " select DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."')) AS Time, Max(ABS(Demand)) as Demand,SUM(DeltaTotalKWH) as kWh,AVG(ABS(PF)) as PF,AVG(I) as I,AVG(V) as V,AVG(KVA) as kVA,AVG(KVAR) as kvar from ( ";
			$sql .= " select * from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID." ";
			$sql .= " where Loop='".$Loop."' and phase='".$phase."' and DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."' ";
			$sql .= " ) AS subquery GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."')) ";
			
			$sql .= " ) AS major FULL JOIN (";
			$sql .= " select DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."')) AS Time, Max(ABS(Demand)) as Demand,SUM(DeltaTotalKWH) as kWh,AVG(ABS(PF)) as PF,AVG(I) as I,AVG(V) as V,AVG(KVA) as kVA,AVG(KVAR) as kvar from ( ";
			$sql .= " select * from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID." ";
			$sql .= " where Loop='".$Loop."' and phase='".$phase."' and DateTime >= '".$compare_datetime_begin.$timezone."' and DateTime <= '".$compare_datetime_end.$timezone."' ";
			$sql .= " ) AS subquery GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."')) ";
			
			$sql .= " ) AS compare ON major.Time = compare.Time order by Time;";
		}
		else if(DB_IS_MYSQL){
			$table_time_code = "";
			if($table_time == "HOUR") $table_time_code = "%H";
			else if($table_time == "DAY") $table_time_code = "%d";
			else if($table_time == "WEEKDAY") $table_time_code = "%w";
			else if($table_time == "MONTH") $table_time_code = "%m";
			
			$sql .= " select IFNULL(major.Time,compare.Time) AS `Time`, major.Demand AS Demand,major.kWh AS kWh,major.PF AS PF,major.I AS I,major.V AS V,major.kVA AS kVA,major.kvar AS kvar, compare.Demand AS c_Demand ,compare.kWh AS c_kWh ,compare.PF AS c_PF,compare.I AS c_I,compare.V AS c_V,compare.kVA AS c_kVA,compare.kvar AS c_kvar from( ";
			$sql .= " select DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS `Time`, Max(ABS(Demand)) as Demand,SUM(DeltaTotalKWH) as kWh,AVG(ABS(PF)) as PF,AVG(I) as I,AVG(V) as V,AVG(KVA) as kVA,AVG(KVAR) as kvar from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." ";
			$sql .= " where `Loop`='".$Loop."' and phase='".$phase."' and CONVERT_TZ(`DateTime`,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(`DateTime`,'+00:00','".$timezone."') <= '".$datetime_end."' ";
			$sql .= " ) AS subquery GROUP BY DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') ";
			
			$sql .= " ) AS major LEFT JOIN (";
			$sql .= " select DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time, Max(ABS(Demand)) as Demand,SUM(DeltaTotalKWH) as kWh,AVG(ABS(PF)) as PF,AVG(I) as I,AVG(V) as V,AVG(KVA) as kVA,AVG(KVAR) as kvar from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." ";
			$sql .= " where `Loop`='".$Loop."' and phase='".$phase."' and CONVERT_TZ(`DateTime`,'+00:00','".$timezone."') >= '".$compare_datetime_begin."' and CONVERT_TZ(`DateTime`,'+00:00','".$timezone."') <= '".$compare_datetime_end."' ";
			$sql .= " ) AS subquery GROUP BY DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') ";
			$sql .= " ) AS compare ON major.Time = compare.Time";
			$sql .= " UNION ";
			
			$sql .= " select IFNULL(major.Time,compare.Time) AS `Time`, major.Demand AS Demand,major.kWh AS kWh,major.PF AS PF,major.I AS I,major.V AS V,major.kVA AS kVA,major.kvar AS kvar, compare.Demand AS c_Demand ,compare.kWh AS c_kWh ,compare.PF AS c_PF,compare.I AS c_I,compare.V AS c_V,compare.kVA AS c_kVA,compare.kvar AS c_kvar from( ";
			$sql .= " select DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS `Time`, Max(ABS(Demand)) as Demand,SUM(DeltaTotalKWH) as kWh,AVG(ABS(PF)) as PF,AVG(I) as I,AVG(V) as V,AVG(KVA) as kVA,AVG(KVAR) as kvar from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." ";
			$sql .= " where `Loop`='".$Loop."' and phase='".$phase."' and CONVERT_TZ(`DateTime`,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(`DateTime`,'+00:00','".$timezone."') <= '".$datetime_end."' ";
			$sql .= " ) AS subquery GROUP BY DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') ";
			$sql .= " ) AS major RIGHT JOIN (";
			$sql .= " select DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time, Max(ABS(Demand)) as Demand,SUM(DeltaTotalKWH) as kWh,AVG(ABS(PF)) as PF,AVG(I) as I,AVG(V) as V,AVG(KVA) as kVA,AVG(KVAR) as kvar from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." ";
			$sql .= " where `Loop`='".$Loop."' and phase='".$phase."' and CONVERT_TZ(`DateTime`,'+00:00','".$timezone."') >= '".$compare_datetime_begin."' and CONVERT_TZ(`DateTime`,'+00:00','".$timezone."') <= '".$compare_datetime_end."' ";
			$sql .= " ) AS subquery GROUP BY DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') ";
			$sql .= " ) AS compare ON major.Time = compare.Time";
			$sql .= " order by Time;";
		}
		else if(DB_IS_ORACLE){
			$table_time_code = "";
			if($table_time == "HOUR") $table_time_code = "HH24";
			else if($table_time == "DAY") $table_time_code = "DD";
			else if($table_time == "WEEKDAY") $table_time_code = "D";
			else if($table_time == "MONTH") $table_time_code = "MM";
			
			$sql .= " select NVL(major.\"TIME\",compare.\"TIME\") AS \"TIME\", major.\"DEMAND\" AS \"DEMAND\",major.kWh AS kWh,major.PF AS PF,major.I AS I,major.V AS V,major.kVA AS kVA,major.kvar AS kvar, compare.\"DEMAND\" AS c_Demand ,compare.kWh AS c_kWh ,compare.PF AS c_PF,compare.I AS c_I,compare.V AS c_V,compare.kVA AS c_kVA,compare.kvar AS c_kvar from( ";
			
			$sql .= " select TO_CHAR(sys_extract_utc(from_tz(DATETIME,'".$timezone."')),'".$table_time_code."') AS \"TIME\", Max(ABS(\"DEMAND\")) as \"DEMAND\",SUM(DeltaTotalKWH) as kWh,AVG(ABS(PF)) as PF,AVG(I) as I,AVG(V) as V,AVG(KVA) as kVA,AVG(KVAR) as kvar from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." ";
			$sql .= " where Loop='".$Loop."' and \"PHASE\"='".$phase."' and sys_extract_utc(from_tz(\"DATETIME\",'".$timezone."')) >= TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and sys_extract_utc(from_tz(\"DATETIME\",'".$timezone."')) <= TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS') ";
			$sql .= " ) subquery GROUP BY TO_CHAR(sys_extract_utc(from_tz(DATETIME,'".$timezone."')),'".$table_time_code."') ORDER BY TO_CHAR(sys_extract_utc(from_tz(DATETIME,'".$timezone."')),'".$table_time_code."')";
			
			
			$sql .= " ) major FULL OUTER JOIN (";
			
			$sql .= " select TO_CHAR(sys_extract_utc(from_tz(DATETIME,'".$timezone."')),'".$table_time_code."') AS \"TIME\", Max(ABS(\"DEMAND\")) as \"DEMAND\",SUM(DeltaTotalKWH) as kWh,AVG(ABS(PF)) as PF,AVG(I) as I,AVG(V) as V,AVG(KVA) as kVA,AVG(KVAR) as kvar from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." ";
			$sql .= " where Loop='".$Loop."' and \"PHASE\"='".$phase."' and sys_extract_utc(from_tz(\"DATETIME\",'".$timezone."')) >= TO_TIMESTAMP ('".$compare_datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and sys_extract_utc(from_tz(\"DATETIME\",'".$timezone."')) <= TO_TIMESTAMP ('".$compare_datetime_end."', 'YYYY-MM-DD HH24:MI:SS') ";
			$sql .= " ) subquery GROUP BY TO_CHAR(sys_extract_utc(from_tz(DATETIME,'".$timezone."')),'".$table_time_code."') ORDER BY TO_CHAR(sys_extract_utc(from_tz(DATETIME,'".$timezone."')),'".$table_time_code."')";
			
			$sql .= " ) compare ON major.\"TIME\" = compare.\"TIME\" order by \"TIME\";";
		}
		
	}
	else{//三相電表
		if(DB_IS_MSSQL){
			$sql .= " select ISNULL(major.Time,compare.Time) AS Time, ";
			$sql .= " major.Demand AS Demand,major.kWh AS kWh,major.PF AS PF,major.I_A AS I_A,major.V_A AS V_A,major.I_B AS I_B,major.V_B AS V_B,major.I_C AS I_C,major.V_C AS V_C,major.kVA AS kVA,major.kvar AS kvar, ";
			$sql .= " compare.Demand AS c_Demand ,compare.kWh AS c_kWh ,compare.PF AS c_PF,compare.I_A AS c_I_A,compare.V_A AS c_V_A,compare.I_B AS c_I_B,compare.V_B AS c_V_B,compare.I_C AS c_I_C,compare.V_C AS c_V_C,compare.kVA AS c_kVA,compare.kvar AS c_kvar ";
			$sql .= " from( ";

			$sql .= " select total.Time as Time,Demand,kWh,PF,I_A,V_A,I_B,V_B,I_C,V_C,kVA,kvar from ";
			$sql .= " (select DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."')) AS Time, Max(ABS(Demand)) as Demand,SUM(DeltaTotalKWH) as kWh,AVG(ABS(PF)) as PF,AVG(KVA) as kVA,AVG(KVAR) as kvar from ( ";
			$sql .= " select * from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID."  where Loop='".$Loop."'and Phase='4' and DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."' ";
			$sql .= "  ) AS subquery GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."'))) AS total ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= " select DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."')) AS Time, AVG(I) as I_A,AVG(V) as V_A from ( ";
			$sql .= " select * from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID."  where Loop='".$Loop."' and Phase='1' and DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."' ";
			$sql .= " ) AS subquery GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."'))) AS Subquery) As A on total.Time= A.Time ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= "  select DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."')) AS Time, AVG(I) as I_B,AVG(V) as V_B from ( ";
			$sql .= " select * from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID."  where Loop='".$Loop."' and Phase='2' and DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."' ";
			$sql .= " ) AS subquery GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."'))) AS Subquery) As B on total.Time= B.Time ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= "  select DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."')) AS Time, AVG(I) as I_C,AVG(V) as V_C from ( ";
			$sql .= " select * from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID."  where Loop='".$Loop."' and Phase='3' and DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."'  ";
			$sql .= " ) AS subquery GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."'))) AS Subquery) As C on total.Time= C.Time ";
			
			$sql .= " ) AS major";
			$sql .= " FULL JOIN";
			$sql .= " ( ";
			$sql .= " select total.Time as Time,Demand,kWh,PF,I_A,V_A,I_B,V_B,I_C,V_C,kVA,kvar from ";
			$sql .= " (select DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."')) AS Time, Max(ABS(Demand)) as Demand,SUM(DeltaTotalKWH) as kWh,AVG(ABS(PF)) as PF,AVG(KVA) as kVA,AVG(KVAR) as kvar from ( ";
			$sql .= " select * from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID."  where Loop='".$Loop."'and Phase='4' and DateTime >= '".$compare_datetime_begin.$timezone."' and DateTime <= '".$compare_datetime_end.$timezone."' ";
			$sql .= "  ) AS subquery GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."'))) AS total ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= " select DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."')) AS Time, AVG(I) as I_A,AVG(V) as V_A from ( ";
			$sql .= " select * from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID."  where Loop='".$Loop."' and Phase='1' and DateTime >= '".$compare_datetime_begin.$timezone."' and DateTime <= '".$compare_datetime_end.$timezone."' ";
			$sql .= " ) AS subquery GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."'))) AS Subquery) As A on total.Time= A.Time ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= "  select DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."')) AS Time, AVG(I) as I_B,AVG(V) as V_B from ( ";
			$sql .= " select * from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID."  where Loop='".$Loop."' and Phase='2' and DateTime >= '".$compare_datetime_begin.$timezone."' and DateTime <= '".$compare_datetime_end.$timezone."' ";
			$sql .= " ) AS subquery GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."'))) AS Subquery) As B on total.Time= B.Time ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= "  select DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."')) AS Time, AVG(I) as I_C,AVG(V) as V_C from ( ";
			$sql .= " select * from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID."  where Loop='".$Loop."' and Phase='3' and DateTime >= '".$compare_datetime_begin.$timezone."' and DateTime <= '".$compare_datetime_end.$timezone."'  ";
			$sql .= " ) AS subquery GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."'))) AS Subquery) As C on total.Time= C.Time ";
			$sql .= " ) AS compare ";
			$sql .= " ON major.Time = compare.Time ";
			$sql .= " order by Time; ";
		}
		else if(DB_IS_MYSQL){
			$table_time_code = "";
			if($table_time == "HOUR") $table_time_code = "%H";
			else if($table_time == "DAY") $table_time_code = "%d";
			else if($table_time == "WEEKDAY") $table_time_code = "%w";
			else if($table_time == "MONTH") $table_time_code = "%m";
			
			$sql .= " select IFNULL(major.Time,compare.Time) AS Time, ";
			$sql .= " major.Demand AS Demand,major.kWh AS kWh,major.PF AS PF,major.I_A AS I_A,major.V_A AS V_A,major.I_B AS I_B,major.V_B AS V_B,major.I_C AS I_C,major.V_C AS V_C,major.kVA AS kVA,major.kvar AS kvar, ";
			$sql .= " compare.Demand AS c_Demand ,compare.kWh AS c_kWh ,compare.PF AS c_PF,compare.I_A AS c_I_A,compare.V_A AS c_V_A,compare.I_B AS c_I_B,compare.V_B AS c_V_B,compare.I_C AS c_I_C,compare.V_C AS c_V_C,compare.kVA AS c_kVA,compare.kvar AS c_kvar ";
			$sql .= " from( ";

			$sql .= " select total.Time as Time,Demand,kWh,PF,I_A,V_A,I_B,V_B,I_C,V_C,kVA,kvar from ";
			$sql .= " (select DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time, Max(ABS(Demand)) as Demand,SUM(DeltaTotalKWH) as kWh,AVG(ABS(PF)) as PF,AVG(KVA) as kVA,AVG(KVAR) as kvar from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where `Loop`='".$Loop."'and Phase='4' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."' ";
			$sql .= "  ) AS subquery GROUP BY DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS total ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= " select DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time, AVG(I) as I_A,AVG(V) as V_A from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where `Loop`='".$Loop."' and Phase='1' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."' ";
			$sql .= " ) AS subquery GROUP BY DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS Subquery) As A on total.Time= A.Time ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= "  select DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time, AVG(I) as I_B,AVG(V) as V_B from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where `Loop`='".$Loop."' and Phase='2' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."' ";
			$sql .= " ) AS subquery GROUP BY DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS Subquery) As B on total.Time= B.Time ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= "  select DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time, AVG(I) as I_C,AVG(V) as V_C from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where `Loop`='".$Loop."' and Phase='3' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."'  ";
			$sql .= " ) AS subquery GROUP BY DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS Subquery) As C on total.Time= C.Time ";
			$sql .= " ) AS major";
			$sql .= " LEFT JOIN";
			$sql .= " ( ";
			$sql .= " select total.Time as Time,Demand,kWh,PF,I_A,V_A,I_B,V_B,I_C,V_C,kVA,kvar from ";
			$sql .= " (select DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time, Max(ABS(Demand)) as Demand,SUM(DeltaTotalKWH) as kWh,AVG(ABS(PF)) as PF,AVG(KVA) as kVA,AVG(KVAR) as kvar from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where `Loop`='".$Loop."'and Phase='4' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$compare_datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$compare_datetime_end."' ";
			$sql .= "  ) AS subquery GROUP BY DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS total ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= " select DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time, AVG(I) as I_A,AVG(V) as V_A from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where `Loop`='".$Loop."' and Phase='1' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$compare_datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$compare_datetime_end."' ";
			$sql .= " ) AS subquery GROUP BY DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS Subquery) As A on total.Time= A.Time ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= "  select DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time, AVG(I) as I_B,AVG(V) as V_B from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where `Loop`='".$Loop."' and Phase='2' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$compare_datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$compare_datetime_end."' ";
			$sql .= " ) AS subquery GROUP BY DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS Subquery) As B on total.Time= B.Time ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= "  select DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time, AVG(I) as I_C,AVG(V) as V_C from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where `Loop`='".$Loop."' and Phase='3' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$compare_datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$compare_datetime_end."'  ";
			$sql .= " ) AS subquery GROUP BY DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS Subquery) As C on total.Time= C.Time ";
			$sql .= " ) AS compare ";
			$sql .= " ON major.Time = compare.Time ";
			$sql .= " UNION ";
			
			$sql .= " select IFNULL(major.Time,compare.Time) AS Time, ";
			$sql .= " major.Demand AS Demand,major.kWh AS kWh,major.PF AS PF,major.I_A AS I_A,major.V_A AS V_A,major.I_B AS I_B,major.V_B AS V_B,major.I_C AS I_C,major.V_C AS V_C,major.kVA AS kVA,major.kvar AS kvar, ";
			$sql .= " compare.Demand AS c_Demand ,compare.kWh AS c_kWh ,compare.PF AS c_PF,compare.I_A AS c_I_A,compare.V_A AS c_V_A,compare.I_B AS c_I_B,compare.V_B AS c_V_B,compare.I_C AS c_I_C,compare.V_C AS c_V_C,compare.kVA AS c_kVA,compare.kvar AS c_kvar ";
			$sql .= " from( ";

			$sql .= " select total.Time as Time,Demand,kWh,PF,I_A,V_A,I_B,V_B,I_C,V_C,kVA,kvar from ";
			$sql .= " (select DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time, Max(ABS(Demand)) as Demand,SUM(DeltaTotalKWH) as kWh,AVG(ABS(PF)) as PF,AVG(KVA) as kVA,AVG(KVAR) as kvar from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where `Loop`='".$Loop."'and Phase='4' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."' ";
			$sql .= "  ) AS subquery GROUP BY DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS total ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= " select DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time, AVG(I) as I_A,AVG(V) as V_A from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where `Loop`='".$Loop."' and Phase='1' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."' ";
			$sql .= " ) AS subquery GROUP BY DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS Subquery) As A on total.Time= A.Time ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= "  select DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time, AVG(I) as I_B,AVG(V) as V_B from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where `Loop`='".$Loop."' and Phase='2' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."' ";
			$sql .= " ) AS subquery GROUP BY DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS Subquery) As B on total.Time= B.Time ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= "  select DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time, AVG(I) as I_C,AVG(V) as V_C from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where `Loop`='".$Loop."' and Phase='3' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."'  ";
			$sql .= " ) AS subquery GROUP BY DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS Subquery) As C on total.Time= C.Time ";
			$sql .= " ) AS major";
			$sql .= " RIGHT JOIN";
			$sql .= " ( ";
			$sql .= " select total.Time as Time,Demand,kWh,PF,I_A,V_A,I_B,V_B,I_C,V_C,kVA,kvar from ";
			$sql .= " (select DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time, Max(ABS(Demand)) as Demand,SUM(DeltaTotalKWH) as kWh,AVG(ABS(PF)) as PF,AVG(KVA) as kVA,AVG(KVAR) as kvar from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where `Loop`='".$Loop."'and Phase='4' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$compare_datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$compare_datetime_end."' ";
			$sql .= "  ) AS subquery GROUP BY DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS total ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= " select DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time, AVG(I) as I_A,AVG(V) as V_A from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where `Loop`='".$Loop."' and Phase='1' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$compare_datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$compare_datetime_end."' ";
			$sql .= " ) AS subquery GROUP BY DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS Subquery) As A on total.Time= A.Time ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= "  select DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time, AVG(I) as I_B,AVG(V) as V_B from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where `Loop`='".$Loop."' and Phase='2' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$compare_datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$compare_datetime_end."' ";
			$sql .= " ) AS subquery GROUP BY DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS Subquery) As B on total.Time= B.Time ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= "  select DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time, AVG(I) as I_C,AVG(V) as V_C from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where `Loop`='".$Loop."' and Phase='3' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$compare_datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$compare_datetime_end."'  ";
			$sql .= " ) AS subquery GROUP BY DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS Subquery) As C on total.Time= C.Time ";
			$sql .= " ) AS compare ";
			$sql .= " ON major.Time = compare.Time ";
			$sql .= " order by Time; ";
		}
		else if(DB_IS_ORACLE){
			$table_time_code = "";
			if($table_time == "HOUR") $table_time_code = "HH24";
			else if($table_time == "DAY") $table_time_code = "DD";
			else if($table_time == "WEEKDAY") $table_time_code = "D";
			else if($table_time == "MONTH") $table_time_code = "MM";
			
			$sql .= " select NVL(major.\"TIME\",compare.\"TIME\") AS \"TIME\", ";
			$sql .= " major.\"DEMAND\" AS \"DEMAND\",major.kWh AS kWh,major.PF AS PF,major.I_A AS I_A,major.V_A AS V_A,major.I_B AS I_B,major.V_B AS V_B,major.I_C AS I_C,major.V_C AS V_C,major.kVA AS kVA,major.kvar AS kvar, ";
			$sql .= " compare.\"DEMAND\" AS c_Demand ,compare.kWh AS c_kWh ,compare.PF AS c_PF,compare.I_A AS c_I_A,compare.V_A AS c_V_A,compare.I_B AS c_I_B,compare.V_B AS c_V_B,compare.I_C AS c_I_C,compare.V_C AS c_V_C,compare.kVA AS c_kVA,compare.kvar AS c_kvar ";
			$sql .= " from( ";

			$sql .= " select total.\"TIME\" as \"TIME\",\"DEMAND\",kWh,PF,I_A,V_A,I_B,V_B,I_C,V_C,kVA,kvar from ";
			$sql .= " (select TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')), '".$table_time_code."') AS \"TIME\", Max(ABS(\"DEMAND\")) as \"DEMAND\",SUM(DeltaTotalKWH) as kWh,AVG(ABS(PF)) as PF,AVG(KVA) as kVA,AVG(KVAR) as kvar from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." where Loop='".$Loop."'and \"PHASE\"='4' and sys_extract_utc(from_tz(DateTime,'".$timezone."')) >= TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and sys_extract_utc(from_tz(DateTime,'".$timezone."')) <= TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS') ";
			$sql .= "  ) subquery GROUP BY TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')), '".$table_time_code."')) total ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= " select TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')), '".$table_time_code."') AS \"TIME\", AVG(I) as I_A,AVG(V) as V_A from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where Loop='".$Loop."' and \"PHASE\"='1' and sys_extract_utc(from_tz(DateTime,'".$timezone."')) >= TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and sys_extract_utc(from_tz(DateTime,'".$timezone."')) <= TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS') ";
			$sql .= " ) subquery GROUP BY TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')), '".$table_time_code."')) Subquery) A on total.\"TIME\"= A.\"TIME\" ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= "  select TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')), '".$table_time_code."') AS \"TIME\", AVG(I) as I_B,AVG(V) as V_B from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where Loop='".$Loop."' and \"PHASE\"='2' and sys_extract_utc(from_tz(DateTime,'".$timezone."')) >= TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and sys_extract_utc(from_tz(DateTime,'".$timezone."')) <= TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS') ";
			$sql .= " ) subquery GROUP BY TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')), '".$table_time_code."')) Subquery) B on total.\"TIME\"= B.\"TIME\" ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= "  select TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')), '".$table_time_code."') AS \"TIME\", AVG(I) as I_C,AVG(V) as V_C from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where Loop='".$Loop."' and \"PHASE\"='3' and sys_extract_utc(from_tz(DateTime,'".$timezone."')) >= TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and sys_extract_utc(from_tz(DateTime,'".$timezone."')) <= TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS')  ";
			$sql .= " ) subquery GROUP BY TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')), '".$table_time_code."')) Subquery) C on total.\"TIME\"= C.\"TIME\" ";
			
			$sql .= " ) major";
			$sql .= " FULL JOIN";
			$sql .= " ( ";
			$sql .= " select total.\"TIME\" as \"TIME\",\"DEMAND\",kWh,PF,I_A,V_A,I_B,V_B,I_C,V_C,kVA,kvar from ";
			$sql .= " (select TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')), '".$table_time_code."') AS \"TIME\", Max(ABS(\"DEMAND\")) as \"DEMAND\",SUM(DeltaTotalKWH) as kWh,AVG(ABS(PF)) as PF,AVG(KVA) as kVA,AVG(KVAR) as kvar from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." where Loop='".$Loop."'and \"PHASE\"='4' and sys_extract_utc(from_tz(DateTime,'".$timezone."')) >= TO_TIMESTAMP ('".$compare_datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and sys_extract_utc(from_tz(DateTime,'".$timezone."')) <= TO_TIMESTAMP ('".$compare_datetime_end."', 'YYYY-MM-DD HH24:MI:SS') ";
			$sql .= "  ) subquery GROUP BY TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')), '".$table_time_code."')) total ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= " select TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')), '".$table_time_code."') AS \"TIME\", AVG(I) as I_A,AVG(V) as V_A from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where Loop='".$Loop."' and \"PHASE\"='1' and sys_extract_utc(from_tz(DateTime,'".$timezone."')) >= TO_TIMESTAMP ('".$compare_datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and sys_extract_utc(from_tz(DateTime,'".$timezone."')) <= TO_TIMESTAMP ('".$compare_datetime_end."', 'YYYY-MM-DD HH24:MI:SS') ";
			$sql .= " ) subquery GROUP BY TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')), '".$table_time_code."')) Subquery) A on total.\"TIME\"= A.\"TIME\" ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= "  select TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')), '".$table_time_code."') AS \"TIME\", AVG(I) as I_B,AVG(V) as V_B from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where Loop='".$Loop."' and \"PHASE\"='2' and sys_extract_utc(from_tz(DateTime,'".$timezone."')) >= TO_TIMESTAMP ('".$compare_datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and sys_extract_utc(from_tz(DateTime,'".$timezone."')) <= TO_TIMESTAMP ('".$compare_datetime_end."', 'YYYY-MM-DD HH24:MI:SS') ";
			$sql .= " ) subquery GROUP BY TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')), '".$table_time_code."')) Subquery) B on total.\"TIME\"= B.\"TIME\" ";
			$sql .= " join ";
			$sql .= " (select * from( ";
			$sql .= "  select TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')), '".$table_time_code."') AS \"TIME\", AVG(I) as I_C,AVG(V) as V_C from ( ";
			$sql .= " select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID."  where Loop='".$Loop."' and \"PHASE\"='3' and sys_extract_utc(from_tz(DateTime,'".$timezone."')) >= TO_TIMESTAMP ('".$compare_datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and sys_extract_utc(from_tz(DateTime,'".$timezone."')) <= TO_TIMESTAMP ('".$compare_datetime_end."', 'YYYY-MM-DD HH24:MI:SS')  ";
			$sql .= " ) subquery GROUP BY TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')), '".$table_time_code."')) Subquery) C on total.\"TIME\"= C.\"TIME\" ";
			$sql .= " ) compare ";
			$sql .= " ON major.\"TIME\" = compare.\"TIME\" ";
			$sql .= " order by \"TIME\"; ";
		}
	}
	$stmt = $conn->prepare($sql);	
	$stmt->execute();
	
	$xml->addAttribute("phase",$phase);
	While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
		$Data = $xml->addChild('Data');
		
		if($table_time == "WEEKDAY" && DB_IS_MYSQL)
			$Data->addAttribute("Time",$obj["Time"]+1);
		else
			$Data->addAttribute("Time",$obj["Time"]*1);
		
		if($phase == 1){
			$Data->addAttribute("Demand",floor_dec($obj["Demand"], 3));
			$Data->addAttribute("kWh",floor_dec($obj["kWh"], 3));
			if(($obj["PF"])===NULL)
				$Data->addAttribute("PF","");
			else
				$Data->addAttribute("PF",floor_dec(($obj["PF"])*100, 3));
			$Data->addAttribute("I",floor_dec($obj["I"], 3));
			$Data->addAttribute("V",floor_dec($obj["V"], 3));
			$Data->addAttribute("kVA",floor_dec($obj["kVA"], 3));
			$Data->addAttribute("kvar",floor_dec($obj["kvar"], 3));
			
			$Data->addAttribute("c_Demand",floor_dec($obj["c_Demand"], 3));
			$Data->addAttribute("c_kWh",floor_dec($obj["c_kWh"], 3));
			if(($obj["c_PF"])===NULL)
				$Data->addAttribute("c_PF","");
			else
				$Data->addAttribute("c_PF",floor_dec(($obj["c_PF"])*100, 3));
			$Data->addAttribute("c_I",floor_dec($obj["c_I"], 3));
			$Data->addAttribute("c_V",floor_dec($obj["c_V"], 3));
			$Data->addAttribute("c_kVA",floor_dec($obj["c_kVA"], 3));
			$Data->addAttribute("c_kvar",floor_dec($obj["c_kvar"], 3));
		}
		else{
			$Data->addAttribute("Demand",floor_dec($obj["Demand"], 3));
			$Data->addAttribute("kWh",floor_dec($obj["kWh"], 3));
			if(($obj["PF"])===NULL)
				$Data->addAttribute("PF" ,"");
			else
				$Data->addAttribute("PF" ,floor_dec(($obj["PF"])*100, 3));
			$Data->addAttribute("I_A",floor_dec($obj["I_A"], 3));
			$Data->addAttribute("V_A",floor_dec($obj["V_A"], 3));
			$Data->addAttribute("I_B",floor_dec($obj["I_B"], 3));
			$Data->addAttribute("V_B",floor_dec($obj["V_B"], 3));
			$Data->addAttribute("I_C",floor_dec($obj["I_C"], 3));
			$Data->addAttribute("V_C",floor_dec($obj["V_C"], 3));
			$Data->addAttribute("kVA",floor_dec($obj["kVA"], 3));
			$Data->addAttribute("kvar",floor_dec($obj["kvar"],3));
			
			$Data->addAttribute("c_Demand",floor_dec($obj["c_Demand"], 3));
			$Data->addAttribute("c_kWh",floor_dec($obj["c_kWh"], 3));
			if(($obj["c_PF"])===NULL)
				$Data->addAttribute("c_PF" ,"");
			else
				$Data->addAttribute("c_PF" ,floor_dec(($obj["c_PF"])*100, 3));
			$Data->addAttribute("c_I_A",floor_dec($obj["c_I_A"], 3));
			$Data->addAttribute("c_V_A",floor_dec($obj["c_V_A"], 3));
			$Data->addAttribute("c_I_B",floor_dec($obj["c_I_B"], 3));
			$Data->addAttribute("c_V_B",floor_dec($obj["c_V_B"], 3));
			$Data->addAttribute("c_I_C",floor_dec($obj["c_I_C"], 3));
			$Data->addAttribute("c_V_C",floor_dec($obj["c_V_C"], 3));
			$Data->addAttribute("c_kVA",floor_dec($obj["c_kVA"], 3));
			$Data->addAttribute("c_kvar",floor_dec($obj["c_kvar"],3));
		}
	}
	$stmt->closeCursor();
	$conn = null;
	
	header('Content-type: text/xml');
	print($xml->asXML());
}

function load_IO_report_data($conn,$accountname,$DeviceUID,$moduleUID,$datetime_begin,$datetime_end,$timezone,$report_type,$table_time,$Channel){
	$sql = "";
	if(DB_IS_MSSQL){
		$sql .= " select Nickname AS ChannelNickname,Unit AS Unit from ".$accountname.".dbo.channel where DeviceUID='".$DeviceUID."' AND ModuleUID='".$moduleUID."' AND Channel='".$Channel."'; ";
	}
	else if(DB_IS_MYSQL){
		$sql .= " select Nickname AS ChannelNickname,Unit AS Unit from ".$accountname.".channel where DeviceUID='".$DeviceUID."' AND ModuleUID='".$moduleUID."' AND Channel='".$Channel."'; ";
	}
	else if(DB_IS_ORACLE){
		$sql .= " select Nickname AS ChannelNickname,Unit AS Unit from ".$accountname.".channel where DeviceUID='".$DeviceUID."' AND ModuleUID='".$moduleUID."' AND Channel='".$Channel."'; ";
	}
	$stmt_channel = $conn->prepare($sql);
	$stmt_channel->execute();
		
	$sql = "";
	if(DB_IS_MSSQL){
		$sql .= " select A.Time AS Time,A.Max_val AS Max_val,A.Min_val AS Min_val,A.Avg_val AS Avg_val,A.Sum_val AS Sum_val,B.".$Channel." AS Final_val ";
		$sql .= " from( ";
		$sql .= " 	select DATEPART(".$table_time.", SWITCHOFFSET(subquery1.DateTime,'".$timezone."')) AS Time,MAX(CONVERT(REAL,subquery1.".$Channel.")) as Max_val,MIN(CONVERT(REAL,subquery1.".$Channel.")) as Min_val,AVG(CONVERT(REAL,subquery1.".$Channel.")) as Avg_val,SUM(CONVERT(REAL,subquery1.".$Channel.")) as Sum_val ";
		$sql .= "		from (  select * from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID." Where DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."'  ) AS subquery1  ";
		$sql .= "		GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(subquery1.DateTime,'".$timezone."')) ";
		$sql .= " ) AS A ";
		$sql .= "	left join (select quest1.Time,quest2.".$Channel." from ( ";
		$sql .= "		select DATEPART(".$table_time.", SWITCHOFFSET(A.DateTime,'".$timezone."')) AS Time,MAX(A.DateTime) AS MaxTime from ( ";
		$sql .= "			select DateTime,".$Channel." ";
		$sql .= "			from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID." ";
		$sql .= "			Where DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."' AND ".$Channel." IS NOT NULL ";
		$sql .= "	) AS A ";
		$sql .= " GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(A.DateTime,'".$timezone."'))) AS quest1 ";
		$sql .= " left JOIN ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID." AS quest2 ON quest2.DateTime=quest1.MaxTime ";
		$sql .= " ) AS B ON A.Time=B.Time WHERE Max_val IS NOT NULL order by Time; ";
	}
	else if(DB_IS_MYSQL){
		$table_time_code = "";
		if($table_time == "HOUR") $table_time_code = "%H";
		else if($table_time == "DAY") $table_time_code = "%d";
		else if($table_time == "WEEKDAY") $table_time_code = "%w";
		else if($table_time == "MONTH") $table_time_code = "%m";
		
		$sql .= " select A.Time AS Time,A.Max_val AS Max_val,A.Min_val AS Min_val,A.Avg_val AS Avg_val,A.Sum_val AS Sum_val,B.".$Channel." AS Final_val ";
		$sql .= " from( ";
		$sql .= " 	select DATE_FORMAT(CONVERT_TZ(subquery1.DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time,MAX(subquery1.".$Channel.") as Max_val,MIN(subquery1.".$Channel.") as Min_val,AVG(subquery1.".$Channel.") as Avg_val,SUM(subquery1.".$Channel.") as Sum_val ";
		$sql .= "		from (  select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." Where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."'  ) AS subquery1  ";
		$sql .= "		GROUP BY DATE_FORMAT(CONVERT_TZ(subquery1.DateTime,'+00:00','".$timezone."'), '".$table_time_code."') ";
		$sql .= " ) AS A ";
		$sql .= "	left join (select quest1.Time,quest2.".$Channel." from ( ";
		$sql .= "		select DATE_FORMAT(A.DateTime, '".$table_time_code."') AS Time,MAX(A.DateTime) AS MaxTime from ( ";
		$sql .= "			select CONVERT_TZ(DateTime,'+00:00','".$timezone."') as DateTime,".$Channel." ";
		$sql .= "			from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." ";
		$sql .= "			Where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."' ";
		$sql .= "	) AS A ";
		$sql .= " GROUP BY DATE_FORMAT(CONVERT_TZ(A.DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS quest1 ";
		$sql .= " left JOIN ".$accountname.".uid_".$DeviceUID."_".$moduleUID." AS quest2 ON CONVERT_TZ(quest2.DateTime,'+00:00','".$timezone."')=quest1.MaxTime ";
		$sql .= " ) AS B ON A.Time=B.Time WHERE Max_val IS NOT NULL order by Time; ";
	}
	else if(DB_IS_ORACLE){
		$table_time_code = "";
		if($table_time == "HOUR") $table_time_code = "HH24";
		else if($table_time == "DAY") $table_time_code = "DD";
		else if($table_time == "WEEKDAY") $table_time_code = "D";
		else if($table_time == "MONTH") $table_time_code = "MM";
		
		$sql .= " select A.\"TIME\" AS \"TIME\",A.Max_val AS Max_val,A.Min_val AS Min_val,A.Avg_val AS Avg_val,A.Sum_val AS Sum_val,B.".$Channel." AS Final_val ";
		$sql .= " from( ";
		$sql .= " 	select TO_CHAR(sys_extract_utc(from_tz(subquery1.DateTime,'".$timezone."')), '".$table_time_code."') AS \"TIME\",MAX(subquery1.".$Channel.") as Max_val,MIN(subquery1.".$Channel.") as Min_val,AVG(subquery1.".$Channel.") as Avg_val,SUM(subquery1.".$Channel.") as Sum_val ";
		$sql .= "		from (  select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." Where sys_extract_utc(from_tz(DateTime,'".$timezone."')) BETWEEN TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS')  ) subquery1  ";
		$sql .= "		GROUP BY TO_CHAR(sys_extract_utc(from_tz(subquery1.DateTime,'".$timezone."')), '".$table_time_code."') ";
		$sql .= " ) A ";
		$sql .= "	left join (select quest1.\"TIME\",quest2.".$Channel." from ( ";
		$sql .= "		select TO_CHAR(A.DateTime, '".$table_time_code."') AS \"TIME\",MAX(A.DateTime) AS MaxTime from ( ";
		$sql .= "			select sys_extract_utc(from_tz(DateTime,'".$timezone."')) AS DateTime,".$Channel." ";
		$sql .= "			from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." ";
		$sql .= "			Where sys_extract_utc(from_tz(DateTime,'".$timezone."')) BETWEEN TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS') ";
		$sql .= "	) A ";
		$sql .= " GROUP BY TO_CHAR(A.DateTime, '".$table_time_code."')) quest1 ";
		$sql .= " left JOIN ".$accountname.".uid_".$DeviceUID."_".$moduleUID." quest2 ON sys_extract_utc(from_tz(quest2.DateTime,'".$timezone."'))=quest1.MaxTime ";
		$sql .= " ) B ON A.\"TIME\"=B.\"TIME\" WHERE Max_val IS NOT NULL order by \"TIME\"; ";
	}
	
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	
	
	//其他資訊(本(日/周/月/季/年)最小值及發生時間、本(日/周/月/季/年)最大值及發生時間、平均值、總計。)
	$sql = "";
	if(DB_IS_MSSQL){
		$sql .= " select AVG(CONVERT(REAL,".$Channel.")) as Avg_Val, SUM(CONVERT(REAL,".$Channel.")) as Total_Val,";
		$sql .= " ( select top 1 convert(varchar,convert(datetime, SWITCHOFFSET(DateTime,'".$timezone."')),120) as DateTime from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID." where DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."' and ".$Channel." =( select Max(CONVERT(REAL,".$Channel.")) from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID." where DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."')) as Max_Time,";
		$sql .= " ( select top 1 ".$Channel." from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID." where DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."' and ".$Channel." =( select MAX(CONVERT(REAL,".$Channel.")) from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID." where DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."')) as Max_Val,";
		$sql .= " ( select top 1 convert(varchar,convert(datetime, SWITCHOFFSET(DateTime,'".$timezone."')),120) as DateTime from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID." where DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."' and ".$Channel." =( select Min(CONVERT(REAL,".$Channel.")) from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID." where DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."')) as Min_Time,";
		$sql .= " ( select top 1 ".$Channel." from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID." where DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."' and ".$Channel." =( select MIN(CONVERT(REAL,".$Channel.")) from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID." where DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."')) as Min_Val";
		$sql .= " from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID." where DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."'; ";
	}
	else if(DB_IS_MYSQL){
		$sql .= " select AVG(".$Channel.") as Avg_Val, SUM(".$Channel.") as Total_Val,";
		$sql .= " ( select CONVERT_TZ(DateTime,'+00:00','".$timezone."') from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."' and ".$Channel." =( select Max(".$Channel.") from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."') LIMIT 1) as Max_Time,";
		$sql .= " ( select COALESCE(".$Channel.", FALSE) from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."' and ".$Channel." =( select MAX(COALESCE(".$Channel.", FALSE)) from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."') LIMIT 1) as Max_Val,";
		$sql .= " ( select CONVERT_TZ(DateTime,'+00:00','".$timezone."') from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."' and ".$Channel." =( select Min(COALESCE(".$Channel.", FALSE)) from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."') LIMIT 1) as Min_Time,";
		$sql .= " ( select COALESCE(".$Channel.", FALSE) from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."' and ".$Channel." =( select MIN(COALESCE(".$Channel.", FALSE)) from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."') LIMIT 1) as Min_Val";
		$sql .= " from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."'; ";
	}
	else if(DB_IS_ORACLE){
		$sql .= "select ";
		$sql .= " ( select AVG(".$Channel.") from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." where sys_extract_utc(from_tz(DateTime,'".$timezone."')) BETWEEN TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS'))as Avg_Val,";
		$sql .= " ( select SUM(".$Channel.") from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." where sys_extract_utc(from_tz(DateTime,'".$timezone."')) BETWEEN TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS'))as Total_Val,";
		$sql .= " ( select DateTime from(select TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')),'yyyy-mm-dd hh24:mi:ss') as DateTime from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." where sys_extract_utc(from_tz(DateTime,'".$timezone."')) BETWEEN TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS') and ".$Channel." =( select Max(".$Channel.") from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." where sys_extract_utc(from_tz(DateTime,'".$timezone."')) BETWEEN TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS'))) where ROWNUM = 1) as Max_Time,";
		$sql .= " ( select ".$Channel." from (select ".$Channel." from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." where sys_extract_utc(from_tz(DateTime,'".$timezone."')) BETWEEN TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS') and ".$Channel." =( select MAX(".$Channel.") from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." where sys_extract_utc(from_tz(DateTime,'".$timezone."')) BETWEEN TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS'))) where ROWNUM = 1) as Max_Val,";
		$sql .= " ( select DateTime from(select TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')),'yyyy-mm-dd hh24:mi:ss') as DateTime from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." where sys_extract_utc(from_tz(DateTime,'".$timezone."')) BETWEEN TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS') and ".$Channel." =( select Min(".$Channel.") from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." where sys_extract_utc(from_tz(DateTime,'".$timezone."')) BETWEEN TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS'))) where ROWNUM = 1) as Min_Time,";
		$sql .= " ( select ".$Channel." from (select ".$Channel." from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." where  sys_extract_utc(from_tz(DateTime,'".$timezone."')) BETWEEN TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS') and ".$Channel." =( select MIN(".$Channel.") from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." where sys_extract_utc(from_tz(DateTime,'".$timezone."')) BETWEEN TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS'))) where ROWNUM = 1) as Min_Val";
		$sql .= " from dual;";
	}
	
	
	$stmt_other = $conn->prepare($sql);
	$stmt_other->execute();

	
	//回傳
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><info/>');	
	While($obj = $stmt_channel->fetch(PDO::FETCH_ASSOC)){
		$Data = $xml->addChild('ChannelInfo');
		$Data->addAttribute("Unit",$obj["Unit"]);
	}
	
	While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
		$Data = $xml->addChild('Data');
		if($table_time == "WEEKDAY" && DB_IS_MYSQL)
			$Data->addAttribute("Time",$obj["Time"]+1);
		else
			$Data->addAttribute("Time",$obj["Time"]*1);
		$Data->addAttribute("Max",floor_dec($obj["Max_val"],3));
		$Data->addAttribute("Min",floor_dec($obj["Min_val"],3));
		$Data->addAttribute("Avg",floor_dec($obj["Avg_val"],3));
		$Data->addAttribute("Sum",floor_dec($obj["Sum_val"],3));
		$Data->addAttribute("Final",floor_dec($obj["Final_val"],3));
	}
	
	
	$obj = $stmt_other->fetch(PDO::FETCH_ASSOC);
	$Data = $xml->addChild('Other');
	$Data->addAttribute("Avg_Val",floor_dec($obj["Avg_Val"],3));
	$Data->addAttribute("Total_Val",floor_dec($obj["Total_Val"],3));
	$Data->addAttribute("Max_Time",$obj["Max_Time"]);
	$Data->addAttribute("Max_Val",floor_dec($obj["Max_Val"],3));
	$Data->addAttribute("Min_Time",$obj["Min_Time"]);
	$Data->addAttribute("Min_Val",floor_dec($obj["Min_Val"],3));
	
	
	$stmt_channel->closeCursor();
	$stmt->closeCursor();
	$stmt_other->closeCursor();
	$conn = null;

	header('Content-type: text/xml');
	print($xml->asXML());
}

function load_IO_report_data_compare($conn,$accountname,$DeviceUID,$moduleUID,$datetime_begin,$datetime_end,$timezone,$report_type,$table_time,$Channel,$compare_datetime_begin,$compare_datetime_end){
	
	$sql = "";
	if(DB_IS_MSSQL){
		$sql .= " select Nickname AS ChannelNickname,Unit AS Unit from ".$accountname.".dbo.channel where DeviceUID='".$DeviceUID."' AND ModuleUID='".$moduleUID."' AND Channel='".$Channel."'; ";
	}
	else if(DB_IS_MYSQL){
		$sql .= " select Nickname AS ChannelNickname,Unit AS Unit from ".$accountname.".channel where DeviceUID='".$DeviceUID."' AND ModuleUID='".$moduleUID."' AND Channel='".$Channel."'; ";
	}
	else if(DB_IS_ORACLE){
		$sql .= " select Nickname AS ChannelNickname,Unit AS Unit from ".$accountname.".channel where DeviceUID='".$DeviceUID."' AND ModuleUID='".$moduleUID."' AND Channel='".$Channel."'; ";
	}
	$stmt_channel = $conn->prepare($sql);
	$stmt_channel->execute();
	
	$sql = "";
	if(DB_IS_MSSQL){
		$sql .= " select ISNULL(major.Time,compare.Time) AS Time, ";
		$sql .= " major.Max_val AS Max_val,major.Min_val AS Min_val,major.Avg_val AS Avg_val,major.Final_val AS Final_val,major.Sum_val AS Sum_val, ";
		$sql .= " compare.Max_val AS c_Max_val,compare.Min_val AS c_Min_val,compare.Avg_val AS c_Avg_val,compare.Final_val AS c_Final_val,compare.Sum_val AS c_Sum_val ";
		$sql .= " FROM( ";
		$sql .= " select A.Time AS Time,A.Max_val AS Max_val,A.Min_val AS Min_val,A.Avg_val AS Avg_val,A.Sum_val AS Sum_val,B.".$Channel." AS Final_val ";
		$sql .= " from( ";
		$sql .= " 	select DATEPART(".$table_time.", SWITCHOFFSET(subquery1.DateTime,'".$timezone."')) AS Time,MAX(CONVERT(REAL,subquery1.".$Channel.")) as Max_val,MIN(CONVERT(REAL,subquery1.".$Channel.")) as Min_val,AVG(CONVERT(REAL,subquery1.".$Channel.")) as Avg_val,SUM(CONVERT(REAL,subquery1.".$Channel.")) as Sum_val ";
		$sql .= "		from (  select * from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID." Where DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."'  ) AS subquery1  ";
		$sql .= "		GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(subquery1.DateTime,'".$timezone."')) ";
		$sql .= " ) AS A ";
		$sql .= "	left join (select quest1.Time,quest2.".$Channel." from ( ";
		$sql .= "		select DATEPART(".$table_time.", SWITCHOFFSET(A.DateTime,'".$timezone."')) AS Time,MAX(A.DateTime) AS MaxTime from ( ";
		$sql .= "			select DateTime,".$Channel." ";
		$sql .= "			from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID." ";
		$sql .= "			Where DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."' AND ".$Channel." IS NOT NULL ";
		$sql .= "	) AS A ";
		$sql .= " GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(A.DateTime,'".$timezone."'))) AS quest1 ";
		$sql .= " left JOIN ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID." AS quest2 ON quest2.DateTime=quest1.MaxTime ";
		$sql .= " ) AS B ON A.Time=B.Time WHERE Max_val IS NOT NULL ";
		$sql .= " ) AS major ";
		$sql .= " FULL JOIN( ";
		
		$sql .= " select A.Time AS Time,A.Max_val AS Max_val,A.Min_val AS Min_val,A.Avg_val AS Avg_val,A.Sum_val AS Sum_val,B.".$Channel." AS Final_val ";
		$sql .= " from( ";
		$sql .= " 	select DATEPART(".$table_time.", SWITCHOFFSET(subquery1.DateTime,'".$timezone."')) AS Time,MAX(CONVERT(REAL,subquery1.".$Channel.")) as Max_val,MIN(CONVERT(REAL,subquery1.".$Channel.")) as Min_val,AVG(CONVERT(REAL,subquery1.".$Channel.")) as Avg_val,SUM(CONVERT(REAL,subquery1.".$Channel.")) as Sum_val ";
		$sql .= "		from (  select * from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID." Where DateTime >= '".$compare_datetime_begin.$timezone."' and DateTime <= '".$compare_datetime_end.$timezone."'  ) AS subquery1  ";
		$sql .= "		GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(subquery1.DateTime,'".$timezone."')) ";
		$sql .= " ) AS A ";
		$sql .= "	left join (select quest1.Time,quest2.".$Channel." from ( ";
		$sql .= "		select DATEPART(".$table_time.", SWITCHOFFSET(A.DateTime,'".$timezone."')) AS Time,MAX(A.DateTime) AS MaxTime from ( ";
		$sql .= "			select DateTime,".$Channel." ";
		$sql .= "			from ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID." ";
		$sql .= "			Where DateTime >= '".$compare_datetime_begin.$timezone."' and DateTime <= '".$compare_datetime_end.$timezone."' AND ".$Channel." IS NOT NULL ";
		$sql .= "	) AS A ";
		$sql .= " GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(A.DateTime,'".$timezone."'))) AS quest1 ";
		$sql .= " left JOIN ".$accountname.".dbo.uid_".$DeviceUID."_".$moduleUID." AS quest2 ON quest2.DateTime=quest1.MaxTime ";
		$sql .= " ) AS B ON A.Time=B.Time WHERE Max_val IS NOT NULL ";
		
		$sql .= " ) AS compare ";
		$sql .= " ON major.Time = compare.Time ";
		$sql .= " order by Time; ";
	}
	else if(DB_IS_MYSQL){
		$table_time_code = "";
		if($table_time == "HOUR") $table_time_code = "%H";
		else if($table_time == "DAY") $table_time_code = "%d";
		else if($table_time == "WEEKDAY") $table_time_code = "%w";
		else if($table_time == "MONTH") $table_time_code = "%m";
		$sql .= " select IFNULL(major.Time,compare.Time) AS Time, ";
		$sql .= " major.Max_val AS Max_val,major.Min_val AS Min_val,major.Avg_val AS Avg_val,major.Final_val AS Final_val,major.Sum_val AS Sum_val, ";
		$sql .= " compare.Max_val AS c_Max_val,compare.Min_val AS c_Min_val,compare.Avg_val AS c_Avg_val,compare.Final_val AS c_Final_val,compare.Sum_val AS c_Sum_val ";
		$sql .= " FROM( ";
		//主
		$sql .= " select A.Time AS Time,A.Max_val AS Max_val,A.Min_val AS Min_val,A.Avg_val AS Avg_val,A.Sum_val AS Sum_val,B.".$Channel." AS Final_val ";
		$sql .= " from( ";
		$sql .= " 	select DATE_FORMAT(CONVERT_TZ(subquery1.DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time,MAX(subquery1.".$Channel.") as Max_val,MIN(subquery1.".$Channel.") as Min_val,AVG(subquery1.".$Channel.") as Avg_val,SUM(subquery1.".$Channel.") as Sum_val ";
		$sql .= "		from (  select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." Where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."'  ) AS subquery1  ";
		$sql .= "		GROUP BY DATE_FORMAT(CONVERT_TZ(subquery1.DateTime,'+00:00','".$timezone."'), '".$table_time_code."') ";
		$sql .= " ) AS A ";
		$sql .= "	left join (select quest1.Time,quest2.".$Channel." from ( ";
		$sql .= "		select DATE_FORMAT(A.DateTime, '".$table_time_code."') AS Time,MAX(A.DateTime) AS MaxTime from ( ";
		$sql .= "			select CONVERT_TZ(DateTime,'+00:00','".$timezone."') as DateTime,".$Channel." ";
		$sql .= "			from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." ";
		$sql .= "			Where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."' ";
		$sql .= "	) AS A ";
		$sql .= " GROUP BY DATE_FORMAT(CONVERT_TZ(A.DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS quest1 ";
		$sql .= " left JOIN ".$accountname.".uid_".$DeviceUID."_".$moduleUID." AS quest2 ON CONVERT_TZ(quest2.DateTime,'+00:00','".$timezone."')=quest1.MaxTime ";
		$sql .= " ) AS B ON A.Time=B.Time WHERE Max_val IS NOT NULL";
			
		$sql .= " ) AS major ";
		
		$sql .= " Left JOIN( ";
		//對比
		$sql .= " select A.Time AS Time,A.Max_val AS Max_val,A.Min_val AS Min_val,A.Avg_val AS Avg_val,A.Sum_val AS Sum_val,B.".$Channel." AS Final_val ";
		$sql .= " from( ";
		$sql .= " 	select DATE_FORMAT(CONVERT_TZ(subquery1.DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time,MAX(subquery1.".$Channel.") as Max_val,MIN(subquery1.".$Channel.") as Min_val,AVG(subquery1.".$Channel.") as Avg_val,SUM(subquery1.".$Channel.") as Sum_val ";
		$sql .= "		from (  select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." Where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$compare_datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$compare_datetime_end."'  ) AS subquery1  ";
		$sql .= "		GROUP BY DATE_FORMAT(CONVERT_TZ(subquery1.DateTime,'+00:00','".$timezone."'), '".$table_time_code."') ";
		$sql .= " ) AS A ";
		$sql .= "	left join (select quest1.Time,quest2.".$Channel." from ( ";
		$sql .= "		select DATE_FORMAT(A.DateTime, '".$table_time_code."') AS Time,MAX(A.DateTime) AS MaxTime from ( ";
		$sql .= "			select CONVERT_TZ(DateTime,'+00:00','".$timezone."') as DateTime,".$Channel." ";
		$sql .= "			from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." ";
		$sql .= "			Where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$compare_datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$compare_datetime_end."' AND ".$Channel." IS NOT NULL ";
		$sql .= "	) AS A ";
		$sql .= " GROUP BY DATE_FORMAT(CONVERT_TZ(A.DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS quest1 ";
		$sql .= " left JOIN ".$accountname.".uid_".$DeviceUID."_".$moduleUID." AS quest2 ON CONVERT_TZ(quest2.DateTime,'+00:00','".$timezone."')=quest1.MaxTime ";
		$sql .= " ) AS B ON A.Time=B.Time WHERE Max_val IS NOT NULL ";
		
		$sql .= " ) AS compare ";
		$sql .= " ON major.Time = compare.Time ";
		$sql .= " UNION";
		
		$sql .= " select IFNULL(major.Time,compare.Time) AS Time, ";
		$sql .= " major.Max_val AS Max_val,major.Min_val AS Min_val,major.Avg_val AS Avg_val,major.Final_val AS Final_val,major.Sum_val AS Sum_val, ";
		$sql .= " compare.Max_val AS c_Max_val,compare.Min_val AS c_Min_val,compare.Avg_val AS c_Avg_val,compare.Final_val AS c_Final_val,compare.Sum_val AS c_Sum_val ";
		$sql .= " FROM( ";
		
		//主
		$sql .= " select A.Time AS Time,A.Max_val AS Max_val,A.Min_val AS Min_val,A.Avg_val AS Avg_val,A.Sum_val AS Sum_val,B.".$Channel." AS Final_val ";
		$sql .= " from( ";
		$sql .= " 	select DATE_FORMAT(CONVERT_TZ(subquery1.DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time,MAX(subquery1.".$Channel.") as Max_val,MIN(subquery1.".$Channel.") as Min_val,AVG(subquery1.".$Channel.") as Avg_val,SUM(subquery1.".$Channel.") as Sum_val ";
		$sql .= "		from (  select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." Where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."'  ) AS subquery1  ";
		$sql .= "		GROUP BY DATE_FORMAT(CONVERT_TZ(subquery1.DateTime,'+00:00','".$timezone."'), '".$table_time_code."') ";
		$sql .= " ) AS A ";
		$sql .= "	left join (select quest1.Time,quest2.".$Channel." from ( ";
		$sql .= "		select DATE_FORMAT(A.DateTime, '".$table_time_code."') AS Time,MAX(A.DateTime) AS MaxTime from ( ";
		$sql .= "			select CONVERT_TZ(DateTime,'+00:00','".$timezone."') as DateTime,".$Channel." ";
		$sql .= "			from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." ";
		$sql .= "			Where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."' ";
		$sql .= "	) AS A ";
		$sql .= " GROUP BY DATE_FORMAT(CONVERT_TZ(A.DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS quest1 ";
		$sql .= " left JOIN ".$accountname.".uid_".$DeviceUID."_".$moduleUID." AS quest2 ON CONVERT_TZ(quest2.DateTime,'+00:00','".$timezone."')=quest1.MaxTime ";
		$sql .= " ) AS B ON A.Time=B.Time WHERE Max_val IS NOT NULL";
		$sql .= " ) AS major ";
		
		
		$sql .= " RIGHT JOIN( ";
		//對比
		$sql .= " select A.Time AS Time,A.Max_val AS Max_val,A.Min_val AS Min_val,A.Avg_val AS Avg_val,A.Sum_val AS Sum_val,B.".$Channel." AS Final_val ";
		$sql .= " from( ";
		$sql .= " 	select DATE_FORMAT(CONVERT_TZ(subquery1.DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time,MAX(subquery1.".$Channel.") as Max_val,MIN(subquery1.".$Channel.") as Min_val,AVG(subquery1.".$Channel.") as Avg_val,SUM(subquery1.".$Channel.") as Sum_val ";
		$sql .= "		from (  select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." Where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$compare_datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$compare_datetime_end."'  ) AS subquery1  ";
		$sql .= "		GROUP BY DATE_FORMAT(CONVERT_TZ(subquery1.DateTime,'+00:00','".$timezone."'), '".$table_time_code."') ";
		$sql .= " ) AS A ";
		$sql .= "	left join (select quest1.Time,quest2.".$Channel." from ( ";
		$sql .= "		select DATE_FORMAT(A.DateTime, '".$table_time_code."') AS Time,MAX(A.DateTime) AS MaxTime from ( ";
		$sql .= "			select CONVERT_TZ(DateTime,'+00:00','".$timezone."') as DateTime,".$Channel." ";
		$sql .= "			from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." ";
		$sql .= "			Where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$compare_datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$compare_datetime_end."' AND ".$Channel." IS NOT NULL ";
		$sql .= "	) AS A ";
		$sql .= " GROUP BY DATE_FORMAT(CONVERT_TZ(A.DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS quest1 ";
		$sql .= " left JOIN ".$accountname.".uid_".$DeviceUID."_".$moduleUID." AS quest2 ON CONVERT_TZ(quest2.DateTime,'+00:00','".$timezone."')=quest1.MaxTime ";
		$sql .= " ) AS B ON A.Time=B.Time WHERE Max_val IS NOT NULL ";
		
		$sql .= " ) AS compare ";
		$sql .= " ON major.Time = compare.Time ";
		$sql .= " order by Time; ";
	}
	else if(DB_IS_ORACLE){
		$table_time_code = "";
		if($table_time == "HOUR") $table_time_code = "HH24";
		else if($table_time == "DAY") $table_time_code = "DD";
		else if($table_time == "WEEKDAY") $table_time_code = "D";
		else if($table_time == "MONTH") $table_time_code = "MM";
			
		$sql .= " select NVL(major.\"TIME\",compare.\"TIME\") AS \"TIME\", ";
		$sql .= " major.Max_val AS Max_val,major.Min_val AS Min_val,major.Avg_val AS Avg_val,major.Final_val AS Final_val,major.Sum_val AS Sum_val, ";
		$sql .= " compare.Max_val AS c_Max_val,compare.Min_val AS c_Min_val,compare.Avg_val AS c_Avg_val,compare.Final_val AS c_Final_val,compare.Sum_val AS c_Sum_val ";
		$sql .= " FROM( ";
		
		$sql .= " select A.\"TIME\" AS \"TIME\",A.Max_val AS Max_val,A.Min_val AS Min_val,A.Avg_val AS Avg_val,A.Sum_val AS Sum_val,B.".$Channel." AS Final_val ";
		$sql .= " from( ";
		$sql .= " 	select TO_CHAR(sys_extract_utc(from_tz(subquery1.DateTime,'".$timezone."')), '".$table_time_code."') AS \"TIME\",MAX(subquery1.".$Channel.") as Max_val,MIN(subquery1.".$Channel.") as Min_val,AVG(subquery1.".$Channel.") as Avg_val,SUM(subquery1.".$Channel.") as Sum_val ";
		$sql .= "		from (  select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." Where sys_extract_utc(from_tz(DateTime,'".$timezone."')) BETWEEN TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS')  ) subquery1  ";
		$sql .= "		GROUP BY TO_CHAR(sys_extract_utc(from_tz(subquery1.DateTime,'".$timezone."')), '".$table_time_code."') ";
		$sql .= " ) A ";
		$sql .= "	left join (select quest1.\"TIME\",quest2.".$Channel." from ( ";
		$sql .= "		select TO_CHAR(A.DateTime, '".$table_time_code."') AS \"TIME\",MAX(A.DateTime) AS MaxTime from ( ";
		$sql .= "			select sys_extract_utc(from_tz(DateTime,'".$timezone."')) AS DateTime,".$Channel." ";
		$sql .= "			from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." ";
		$sql .= "			Where sys_extract_utc(from_tz(DateTime,'".$timezone."')) BETWEEN TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS') ";
		$sql .= "	) A ";
		$sql .= " GROUP BY TO_CHAR(A.DateTime, '".$table_time_code."')) quest1 ";
		$sql .= " left JOIN ".$accountname.".uid_".$DeviceUID."_".$moduleUID." quest2 ON sys_extract_utc(from_tz(quest2.DateTime,'".$timezone."'))=quest1.MaxTime ";
		$sql .= " ) B ON A.\"TIME\"=B.\"TIME\" WHERE Max_val IS NOT NULL ";
		
		$sql .= " ) major ";
		$sql .= " FULL JOIN( ";
		
		$sql .= " select A.\"TIME\" AS \"TIME\",A.Max_val AS Max_val,A.Min_val AS Min_val,A.Avg_val AS Avg_val,A.Sum_val AS Sum_val,B.".$Channel." AS Final_val ";
		$sql .= " from( ";
		$sql .= " 	select TO_CHAR(sys_extract_utc(from_tz(subquery1.DateTime,'".$timezone."')), '".$table_time_code."') AS \"TIME\",MAX(subquery1.".$Channel.") as Max_val,MIN(subquery1.".$Channel.") as Min_val,AVG(subquery1.".$Channel.") as Avg_val,SUM(subquery1.".$Channel.") as Sum_val ";
		$sql .= "		from (  select * from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." Where sys_extract_utc(from_tz(DateTime,'".$timezone."')) BETWEEN TO_TIMESTAMP ('".$compare_datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and TO_TIMESTAMP ('".$compare_datetime_end."', 'YYYY-MM-DD HH24:MI:SS')  ) subquery1  ";
		$sql .= "		GROUP BY TO_CHAR(sys_extract_utc(from_tz(subquery1.DateTime,'".$timezone."')), '".$table_time_code."') ";
		$sql .= " ) A ";
		$sql .= "	left join (select quest1.\"TIME\",quest2.".$Channel." from ( ";
		$sql .= "		select TO_CHAR(A.DateTime, '".$table_time_code."') AS \"TIME\",MAX(A.DateTime) AS MaxTime from ( ";
		$sql .= "			select sys_extract_utc(from_tz(DateTime,'".$timezone."')) AS DateTime,".$Channel." ";
		$sql .= "			from ".$accountname.".uid_".$DeviceUID."_".$moduleUID." ";
		$sql .= "			Where sys_extract_utc(from_tz(DateTime,'".$timezone."')) BETWEEN TO_TIMESTAMP ('".$compare_datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and TO_TIMESTAMP ('".$compare_datetime_end."', 'YYYY-MM-DD HH24:MI:SS') ";
		$sql .= "	) A ";
		$sql .= " GROUP BY TO_CHAR(A.DateTime, '".$table_time_code."')) quest1 ";
		$sql .= " left JOIN ".$accountname.".uid_".$DeviceUID."_".$moduleUID." quest2 ON sys_extract_utc(from_tz(quest2.DateTime,'".$timezone."'))=quest1.MaxTime ";
		$sql .= " ) B ON A.\"TIME\"=B.\"TIME\" WHERE Max_val IS NOT NULL ";
		$sql .= " ) compare ";
		$sql .= " ON major.\"TIME\" = compare.\"TIME\" ";
		$sql .= " order by \"TIME\"; ";
	}
	
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	
	//回傳
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><info/>');	
	While($obj = $stmt_channel->fetch(PDO::FETCH_ASSOC)){
		$Data = $xml->addChild('ChannelInfo');
		$Data->addAttribute("Unit",$obj->Unit);
	}
	
	While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
		$Data = $xml->addChild('Data');
		if($table_time == "WEEKDAY" && DB_IS_MYSQL)
			$Data->addAttribute("Time",$obj["Time"]+1);
		else
			$Data->addAttribute("Time",$obj["Time"]*1);
		$Data->addAttribute("Max",floor_dec($obj["Max_val"],3));
		$Data->addAttribute("Min",floor_dec($obj["Min_val"],3));
		$Data->addAttribute("Avg",floor_dec($obj["Avg_val"],3));
		$Data->addAttribute("Sum",floor_dec($obj["Sum_val"],3));
		$Data->addAttribute("Final",floor_dec($obj["Final_val"],3));
		
		$Data->addAttribute("c_Max",floor_dec($obj["c_Max_val"],3));
		$Data->addAttribute("c_Min",floor_dec($obj["c_Min_val"],3));
		$Data->addAttribute("c_Avg",floor_dec($obj["c_Avg_val"],3));
		$Data->addAttribute("c_Sum",floor_dec($obj["c_Sum_val"],3));
		$Data->addAttribute("c_Final",floor_dec($obj["c_Final_val"],3));
	}
	
	$stmt->closeCursor();
	$stmt_channel->closeCursor();
	$conn = null;
	
	header('Content-type: text/xml');
	print($xml->asXML());
}

function load_pm_report_data_group(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}
	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
	
	//----參數
	$groupuid = $_POST["GroupUID"];//選擇群組
	$datetime_begin =$_POST["datetime_begin"];//選擇的日期
	$datetime_end =$_POST["datetime_end"];//選擇的日期
	$report_type = $_POST["report_type"];//報表類型：日報-DAY、月報-MONTH、年-YEAR
	$timezone = $_POST["timezone"];
	
	if(DB_IS_ORACLE){
		if (strpos($timezone, '+') !== false) 
			$timezone=str_replace('+','-',$timezone);
		else
			$timezone=str_replace('-','+',$timezone);
	}
	//收集群組各電錶資訊
	$sql_group = "";
	//$stmt_group = $conn->prepare("USE ".$_SESSION["username"].";");
	//$stmt_group->execute();

	if(DB_IS_MSSQL){
		$sql_group .= " select AccountUID,Username, group_data.DeviceUID as DeviceUID, ModuleUID, Loop from ".$_SESSION["username"].".dbo.group_data ";
		$sql_group .= " join manager.dbo.account as groupshare on group_data.AccountUID = groupshare.UID ";
		$sql_group .= " where GroupInfoUID = ".$groupuid."; ";
	}
	else if(DB_IS_MYSQL){
		$sql_group .= " select AccountUID,Username, group_data.DeviceUID as DeviceUID, ModuleUID, `Loop` from ".$_SESSION["username"].".group_data ";
		$sql_group .= " join manager.account as groupshare on group_data.AccountUID = groupshare.UID ";
		$sql_group .= " where GroupInfoUID = ".$groupuid."; ";
	}
	else if(DB_IS_ORACLE){
		$sql_group .= " select AccountUID,Username, group_data.DeviceUID as DeviceUID, ModuleUID, Loop from ".$_SESSION["username"].".group_data ";
		$sql_group .= " join manager.\"ACCOUNT\" groupshare on group_data.AccountUID = groupshare.\"UID\" ";
		$sql_group .= " where GroupInfoUID = ".$groupuid."; ";
	}
	$stmt_group = $conn->prepare($sql_group);
	$stmt_group->execute();
	
	$group_modules_obj = array();
	
	While($obj_group = $stmt_group->fetch(PDO::FETCH_ASSOC)){
		$AccountUID = $obj_group["AccountUID"];
		$Username = $obj_group["Username"];
		$DeviceUID = $obj_group["DeviceUID"];
		$ModuleUID = $obj_group["ModuleUID"];
		$Loop = $obj_group["Loop"];
		$sql_group_module = "";
		if(DB_IS_MSSQL){
			$sql_group_module .= " select group_data.Loop AS Loop,phase as PhaseType from ".$_SESSION["username"].".dbo.group_data ";
			$sql_group_module .= " JOIN ".$Username.".dbo.module ON module.DeviceUID = group_data.DeviceUID and module.UID = group_data.ModuleUID where Removed != 1 and GroupInfoUID = '".$groupuid."'";
			$sql_group_module .= " and group_data.AccountUID = '".$AccountUID."' and group_data.DeviceUID = '".$DeviceUID."' and group_data.ModuleUID = '".$ModuleUID."' and group_data.Loop = '".$Loop."';";
		}
		else if(DB_IS_MYSQL){
			$sql_group_module .= " select group_data.`Loop` AS `Loop`,phase as PhaseType from ".$_SESSION["username"].".group_data ";
			$sql_group_module .= " JOIN ".$Username.".module ON module.DeviceUID = group_data.DeviceUID and module.UID = group_data.ModuleUID where Removed != 1 and GroupInfoUID = '".$groupuid."'";
			$sql_group_module .= " and group_data.AccountUID = '".$AccountUID."' and group_data.DeviceUID = '".$DeviceUID."' and group_data.ModuleUID = '".$ModuleUID."' and group_data.`Loop` = '".$Loop."';";
		}
		else if(DB_IS_ORACLE){
			$sql_group_module .= " select group_data.Loop AS Loop,phase as PhaseType from ".$_SESSION["username"].".group_data ";
			$sql_group_module .= " JOIN ".$Username.".module ON module.DeviceUID = group_data.DeviceUID and module.\"UID\" = group_data.ModuleUID where Removed != 1 and GroupInfoUID = '".$groupuid."'";
			$sql_group_module .= " and group_data.AccountUID = '".$AccountUID."' and group_data.DeviceUID = '".$DeviceUID."' and group_data.ModuleUID = '".$ModuleUID."' and group_data.Loop = '".$Loop."';";
		}
		
		$stmt_group_module = $conn->prepare($sql_group_module);
		$stmt_group_module->execute();
		While($obj_group_module = $stmt_group_module->fetch(PDO::FETCH_ASSOC)){
			$group_module_info = array(
				"GroupInfoUID" => $groupuid,
				"Username" => $Username,
				"DeviceUID" => $DeviceUID,
				"ModuleUID" => $ModuleUID,
				"Loop" => $obj_group_module["Loop"],
				"PhaseType" => $obj_group_module["PhaseType"]
			);
			array_push($group_modules_obj,$group_module_info);
		}
		if(isset($stmt_group_module))
			$stmt_group_module->closeCursor();
	}
	
	if(isset($stmt_group))
		$stmt_group->closeCursor();
	
	if(count($group_modules_obj)==0){
		$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><info/>');
		$conn = null;
		header('Content-type: text/xml');
		print($xml->asXML());
		return;
	}
	
	
	
	//產生報表
	$Date_array = explode("-",$selectDate);
	
	$sql = "";
	$table_time = "";
	
	if($report_type =="DAY"){
		$table_time = "HOUR";
	}
	else if($report_type =="MONTH"){
		$table_time = "DAY";
	}
	else if($report_type =="WEEK"){
		$table_time = "WEEKDAY";
	}
	
	else if($report_type =="YEAR"||$report_type =="QUARTER"){
		$table_time = "MONTH";
	}
	
	if(DB_IS_MSSQL){
		$sql .= "SELECT DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."')) AS Time, MAX(Demand) as Demand,SUM(kWh) as kWh from(";
		$sql .= "SELECT SWITCHOFFSET(DateTime,'".$timezone."') AS DateTime,SUM(ABS(Demand)) as Demand,SUM(DeltaTotalKWH) as kWh FROM(";
		
		for($module_idx = 0;$module_idx < count($group_modules_obj); $module_idx++ ){
			$GroupInfoUID = $group_modules_obj[$module_idx]{"GroupInfoUID"};
			$Username  = $group_modules_obj[$module_idx]{"Username"};
			$DeviceUID = $group_modules_obj[$module_idx]{"DeviceUID"};
			$ModuleUID = $group_modules_obj[$module_idx]{"ModuleUID"};
			$Loop = $group_modules_obj[$module_idx]{"Loop"};
			$PhaseType = $group_modules_obj[$module_idx]{"PhaseType"};
			$Phase="";
			if($PhaseType == "1")//單相電錶
				$Phase = 1;
			else
				$Phase = 4;
			$sql .= "SELECT * FROM ".$Username.".dbo.uid_".$DeviceUID."_".$ModuleUID." where loop = ".$Loop." and phase = ".$Phase." and DateTime >= '".$datetime_begin.$timezone."' and DateTime <='".$datetime_end.$timezone."'";
			if($module_idx != (count($group_modules_obj)-1))
				$sql .= " union ";
			
		}
		$sql .= " ) AS subquery  GROUP BY SWITCHOFFSET(DateTime,'".$timezone."') ";
		$sql .= " ) AS subquery  GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."')) ORDER BY DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."')) ";
	}
	else if(DB_IS_MYSQL){
		$table_time_code = "";
		if($table_time == "HOUR") $table_time_code = "%H";
		else if($table_time == "DAY") $table_time_code = "%d";
		else if($table_time == "WEEKDAY") $table_time_code = "%w";
		else if($table_time == "MONTH") $table_time_code = "%m";
			
		$sql .= "SELECT DATE_FORMAT(DateTime,'".$table_time_code."') AS Time, MAX(Demand) as Demand,SUM(kWh) as kWh from(";
		$sql .= "SELECT DateTime AS DateTime,SUM(ABS(Demand)) as Demand,SUM(DeltaTotalKWH) as kWh FROM(";
		
		for($module_idx = 0;$module_idx < count($group_modules_obj); $module_idx++ ){
			$GroupInfoUID = $group_modules_obj[$module_idx]{"GroupInfoUID"};
			$Username  = $group_modules_obj[$module_idx]{"Username"};
			$DeviceUID = $group_modules_obj[$module_idx]{"DeviceUID"};
			$ModuleUID = $group_modules_obj[$module_idx]{"ModuleUID"};
			$Loop = $group_modules_obj[$module_idx]{"Loop"};
			$PhaseType = $group_modules_obj[$module_idx]{"PhaseType"};
			$Phase="";
			if($PhaseType == "1")//單相電錶
				$Phase = 1;
			else
				$Phase = 4;
			$sql .= "SELECT CONVERT_TZ(DateTime,'+00:00','".$timezone."') as DateTime, Demand,DeltaTotalKWH FROM ".$Username.".uid_".$DeviceUID."_".$ModuleUID." where `loop` = ".$Loop." and phase = ".$Phase." and CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <='".$datetime_end."'";
			if($module_idx != (count($group_modules_obj)-1))
				$sql .= " union ";
			
		}
		$sql .= " ) AS subquery  GROUP BY DateTime ";
		$sql .= " ) AS subquery  GROUP BY Time ORDER BY Time ";
	}
	else if(DB_IS_ORACLE){
		$table_time_code = "";
		if($table_time == "HOUR") $table_time_code = "HH24";
		else if($table_time == "DAY") $table_time_code = "DD";
		else if($table_time == "WEEKDAY") $table_time_code = "D";
		else if($table_time == "MONTH") $table_time_code = "MM";
		
		$sql .= "SELECT TO_CHAR(DateTime,'".$table_time_code."') AS Time, MAX(\"DEMAND\") as \"DEMAND\",SUM(kWh) as kWh from(";
			$sql .= "SELECT sys_extract_utc(from_tz(DateTime,'".$timezone."')) AS DateTime,SUM(ABS(\"DEMAND\")) as \"DEMAND\",SUM(DeltaTotalKWH) as kWh FROM(";
		
		for($module_idx = 0;$module_idx < count($group_modules_obj); $module_idx++ ){
			$GroupInfoUID = $group_modules_obj[$module_idx]{"GroupInfoUID"};
			$Username  = $group_modules_obj[$module_idx]{"Username"};
			$DeviceUID = $group_modules_obj[$module_idx]{"DeviceUID"};
			$ModuleUID = $group_modules_obj[$module_idx]{"ModuleUID"};
			$Loop = $group_modules_obj[$module_idx]{"Loop"};
			$PhaseType = $group_modules_obj[$module_idx]{"PhaseType"};
			$Phase="";
			if($PhaseType == "1")//單相電錶
				$Phase = 1;
			else
				$Phase = 4;
			$sql .= "SELECT * FROM ".$Username.".uid_".$DeviceUID."_".$ModuleUID." where loop = ".$Loop." and phase = ".$Phase." and sys_extract_utc(from_tz(DateTime,'".$timezone."')) between TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS')";
			if($module_idx != (count($group_modules_obj)-1))
				$sql .= " union ";
		}
		/*----*/
		$sql .= " ) subquery GROUP BY DateTime ";
		$sql .= " ) subquery GROUP BY TO_CHAR(DateTime, '".$table_time_code."') ORDER BY TO_CHAR(DateTime,'".$table_time_code."') ";
	}
	
	$stmt = $conn->prepare($sql);
	$stmt->execute();

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><info/>');
	While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
		$Data = $xml->addChild('Data');
		if($table_time == "WEEKDAY" && DB_IS_MYSQL)
			$Data->addAttribute("Time",$obj["Time"]+1);
		else
			$Data->addAttribute("Time",$obj["Time"]*1);
		$Data->addAttribute("Demand",floor_dec($obj["Demand"],3));
		$Data->addAttribute("kWh",floor_dec($obj["kWh"],3));
		$total_kwh += $obj["kWh"];
	}
	$Data = $xml->addChild('Other');
	$Data->addAttribute("total_kwh",floor_dec($total_kwh,3));

	if(isset($stmt))
		$stmt->closeCursor();
	$conn = null;
    
	header('Content-type: text/xml');
	print($xml->asXML());
	
}

function load_pm_report_data_group_f2(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}
	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
	
	//----參數
	$groupuid = $_POST["GroupUID"];//選擇群組
	$datetime_begin =$_POST["datetime_begin"];//選擇的日期
	$datetime_end =$_POST["datetime_end"];//選擇的日期
	$report_type = $_POST["report_type"];//報表類型：日報-DAY、月報-MONTH、年-YEAR
	$timezone = $_POST["timezone"];
	
	if(DB_IS_ORACLE){
		if (strpos($timezone, '+') !== false) 
			$timezone=str_replace('+','-',$timezone);
		else
			$timezone=str_replace('-','+',$timezone);
	}
	//收集群組各電錶資訊
	$sql_group = "";
	if(DB_IS_MSSQL){
		$sql_group .= " select AccountUID, Username, group_data.DeviceUID as DeviceUID, ModuleUID, Loop from ".$_SESSION["username"].".dbo.group_data ";
		$sql_group .= " join manager.dbo.account as groupshare on ".$_SESSION["username"].".dbo.group_data.AccountUID = groupshare.UID ";
		$sql_group .= " where GroupInfoUID = ".$groupuid." ";
	}
	else if(DB_IS_MYSQL){
		$sql_group .= " select AccountUID, Username, group_data.DeviceUID as DeviceUID, ModuleUID, `Loop` from ".$_SESSION["username"].".group_data ";
		$sql_group .= " join manager.account as groupshare on ".$_SESSION["username"].".group_data.AccountUID = groupshare.UID ";
		$sql_group .= " where GroupInfoUID = ".$groupuid." ";
	}
	else if(DB_IS_ORACLE){
		$sql_group .= " select AccountUID, Username, group_data.DeviceUID as DeviceUID, ModuleUID, Loop from ".$_SESSION["username"].".group_data ";
		$sql_group .= " join manager.\"ACCOUNT\" groupshare on ".$_SESSION["username"].".group_data.AccountUID = groupshare.\"UID\" ";
		$sql_group .= " where GroupInfoUID = ".$groupuid." ";
	}
	
	$stmt_group = $conn->prepare($sql_group);
	$stmt_group->execute();
	
	$group_modules_obj = array();
	While($obj_group = $stmt_group->fetch(PDO::FETCH_ASSOC)){
		$AccountUID = $obj_group["AccountUID"];
		$Username = $obj_group["Username"];
		$DeviceUID = $obj_group["DeviceUID"];
		$ModuleUID = $obj_group["ModuleUID"];
		$Loop = $obj_group["Loop"];
		$sql_phase_type = "";
		
		if(DB_IS_MSSQL)
			$sql_phase_type = " SELECT Phase FROM ".$Username.".dbo.module WHERE DeviceUID='".$DeviceUID."' AND UID='".$ModuleUID."'; ";
		else if(DB_IS_MYSQL)
			$sql_phase_type = " SELECT Phase FROM ".$Username.".module WHERE DeviceUID='".$DeviceUID."' AND UID='".$ModuleUID."'; ";
		else if(DB_IS_ORACLE)
			$sql_phase_type = " SELECT Phase FROM ".$Username.".module WHERE DeviceUID='".$DeviceUID."' AND \"UID\"='".$ModuleUID."'; ";
		
		$stmt_phase_type = $conn->prepare($sql_phase_type);
		$stmt_phase_type->execute();
		
		$row = $stmt_phase_type->fetch(PDO::FETCH_ASSOC);
		$stmt_phase_type->closeCursor();
		$PhaseType = $row[Phase];
		
		$sql_group_module = "";
		if(DB_IS_MSSQL){
			$sql_group_module .= " SELECT group_data.Loop AS Loop,Module.Phase AS PhaseType,Device.ModelName AS DeviceName,Device.Nickname AS DeviceNickname, Module.ModelName AS ModuleName, Module.Nickname AS ModuleNickname, Channel.Nickname AS ChannelNickname from ".$_SESSION["username"].".dbo.group_data  ";
			$sql_group_module .= " JOIN ".$Username.".dbo.device AS Device ON Device.UID = group_data.DeviceUID ";
			$sql_group_module .= " JOIN ".$Username.".dbo.module AS Module ON Module.DeviceUID = group_data.DeviceUID and Module.UID = group_data.ModuleUID ";
			$sql_group_module .= " JOIN ".$Username.".dbo.channel AS Channel  ON Channel.DeviceUID = group_data.DeviceUID AND Channel.ModuleUID = group_data.ModuleUID AND  Channel.Loop = group_data.Loop   ";
			$sql_group_module .= " WHERE Removed != 1 and GroupInfoUID = '".$groupuid."' ";
			if($PhaseType == 3)
				$sql_group_module .= " and group_data.AccountUID = '".$AccountUID."' and group_data.DeviceUID = '".$DeviceUID."' and group_data.ModuleUID = '".$ModuleUID."' and group_data.Loop = '".$Loop."' AND Channel.Phase='4';";
			else
				$sql_group_module .= " and group_data.AccountUID = '".$AccountUID."' and group_data.DeviceUID = '".$DeviceUID."' and group_data.ModuleUID = '".$ModuleUID."' and group_data.Loop = '".$Loop."';";
		}
		else if(DB_IS_MYSQL){
			$sql_group_module .= " SELECT group_data.`Loop` AS `Loop`,Module.Phase AS PhaseType,Device.ModelName AS DeviceName,Device.Nickname AS DeviceNickname, Module.ModelName AS ModuleName, Module.Nickname AS ModuleNickname, Channel.Nickname AS ChannelNickname from ".$_SESSION["username"].".group_data  ";
			$sql_group_module .= " JOIN ".$Username.".device AS Device ON Device.UID = group_data.DeviceUID ";
			$sql_group_module .= " JOIN ".$Username.".module AS Module ON Module.DeviceUID = group_data.DeviceUID and Module.UID = group_data.ModuleUID ";
			$sql_group_module .= " JOIN ".$Username.".channel AS Channel  ON Channel.DeviceUID = group_data.DeviceUID AND Channel.ModuleUID = group_data.ModuleUID AND  Channel.`Loop` = group_data.`Loop` ";
			$sql_group_module .= " WHERE Removed != 1 and GroupInfoUID = '".$groupuid."' ";
			if($PhaseType == 3)
				$sql_group_module .= " and group_data.AccountUID = '".$AccountUID."' and group_data.DeviceUID = '".$DeviceUID."' and group_data.ModuleUID = '".$ModuleUID."' and group_data.`Loop` = '".$Loop."' AND Channel.Phase='4';";
			else
				$sql_group_module .= " and group_data.AccountUID = '".$AccountUID."' and group_data.DeviceUID = '".$DeviceUID."' and group_data.ModuleUID = '".$ModuleUID."' and group_data.`Loop` = '".$Loop."';";
		}
		else if(DB_IS_ORACLE){
			$sql_group_module .= " SELECT group_data.Loop AS Loop,Module.Phase AS PhaseType,Device.ModelName AS DeviceName,Device.Nickname AS DeviceNickname, Module.ModelName AS ModuleName, Module.Nickname AS ModuleNickname, Channel.Nickname AS ChannelNickname from ".$_SESSION["username"].".group_data  ";
			$sql_group_module .= " JOIN ".$Username.".device Device ON Device.\"UID\" = group_data.DeviceUID ";
			$sql_group_module .= " JOIN ".$Username.".module Module ON Module.DeviceUID = group_data.DeviceUID and Module.\"UID\" = group_data.ModuleUID ";
			$sql_group_module .= " JOIN ".$Username.".channel Channel  ON Channel.DeviceUID = group_data.DeviceUID AND Channel.ModuleUID = group_data.ModuleUID AND  Channel.Loop = group_data.Loop   ";
			$sql_group_module .= " WHERE Removed != 1 and GroupInfoUID = '".$groupuid."' ";
			if($PhaseType == 3)
				$sql_group_module .= " and group_data.AccountUID = '".$AccountUID."' and group_data.DeviceUID = '".$DeviceUID."' and group_data.ModuleUID = '".$ModuleUID."' and group_data.Loop = '".$Loop."' AND Channel.Phase='4';";
			else
				$sql_group_module .= " and group_data.AccountUID = '".$AccountUID."' and group_data.DeviceUID = '".$DeviceUID."' and group_data.ModuleUID = '".$ModuleUID."' and group_data.Loop = '".$Loop."';";
		}
		
		$stmt_group_module = $conn->prepare($sql_group_module);
		$stmt_group_module->execute();
		
		While($obj_group_module = $stmt_group_module->fetch(PDO::FETCH_ASSOC)){
			$group_module_info = array(
				"GroupInfoUID" => $groupuid,
				"Username" => $Username,
				"DeviceUID" => $DeviceUID,
				"ModuleUID" => $ModuleUID,
				"DeviceName" => $obj_group_module["DeviceName"],
				"DeviceNickname" => $obj_group_module["DeviceNickname"],
				"ModuleName" => $obj_group_module["ModuleName"],
				"ModuleNickname" => $obj_group_module["ModuleNickname"],
				"Loop" => $obj_group_module["Loop"],
				"PhaseType" => $obj_group_module["PhaseType"],
				"ChannelNickname" => $obj_group_module["ChannelNickname"]
			);
			array_push($group_modules_obj,$group_module_info);
		}
		if(isset($stmt_group_module))
			$stmt_group_module->closeCursor();
	}
	if(isset($stmt_group))
		$stmt_group->closeCursor();
	if(count($group_modules_obj)==0){
		$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><info/>');
		$conn = null;
		header('Content-type: text/xml');
		print($xml->asXML());
		return;
	}
	
	
	//產生報表
	$Date_array = explode("-",$selectDate);
	
	$sql = "";
	$table_time = "";
	
	if($report_type =="DAY"){
		$table_time = "HOUR";
	}
	else if($report_type =="MONTH"){
		$table_time = "DAY";
	}
	else if($report_type =="WEEK"){
		$table_time = "WEEKDAY";
	}
	else if($report_type =="YEAR"||$report_type =="QUARTER"){
		$table_time = "MONTH";
	}
	
	$TimeCol='';//時間欄位(Null時查看其他通道是否有值)
	$ChannelCol='';//取得通道欄位
	$ChannelData = '';//每個模組通道值
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><info/>');
	if(DB_IS_MSSQL){
		for($i=0; $i<count($group_modules_obj); $i++){
			$module_table_name ="";
			$module_table_name = $group_modules_obj[$i]{"Username"}.".dbo.uid_".$group_modules_obj[$i]{"DeviceUID"}."_".$group_modules_obj[$i]{"ModuleUID"};
			$module_Loop = $group_modules_obj[$i]{"Loop"};
			$PhaseType = $group_modules_obj[$i]{"PhaseType"};
			$phase = 4;
			if($PhaseType == 1)
				$phase = 1;
			else
				$phase = 4;
			
			if($i==0){
				$TimeCol = 'Channel'.$i.".Time";
				$ChannelData = "SELECT total.Time AS Time,Demand,kWh,PF,V,I,kVA,kvar FROM (SELECT DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."')) AS Time, Max(ABS(Demand)) AS Demand,SUM(DeltaTotalKWH) AS kWh,AVG(ABS(PF)) AS PF,AVG(KVA) AS kVA,AVG(KVAR) AS kvar,AVG(V) AS V,AVG(I) AS I  FROM (SELECT * FROM ".$module_table_name." where Loop='".$module_Loop."'and Phase='".$phase."' and DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."' ) AS subquery GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."'))) AS total";
				$ChannelData .= ") As Channel".$i;
			}
			else{
				$TimeCol = ' ISNULL(Channel'.$i.'.Time,'.$TimeCol.') ';
				$ChannelData .= " FULL JOIN (";
				$ChannelData .= "SELECT total.Time AS Time,Demand,kWh,PF,V,I,kVA,kvar FROM (SELECT DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."')) AS Time, Max(ABS(Demand)) AS Demand,SUM(DeltaTotalKWH) AS kWh,AVG(ABS(PF)) AS PF,AVG(KVA) AS kVA,AVG(KVAR) AS kvar,AVG(V) AS V,AVG(I) AS I  FROM (SELECT * FROM ".$module_table_name." where Loop='".$module_Loop."'and Phase='".$phase."' and DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."' ) AS subquery GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(DateTime,'".$timezone."'))) AS total";
				$ChannelData .= " ) As Channel".$i." ON ";
				//join條件式()
				$JoinCondition = "";
				for($JoinCondition_i = $i; $JoinCondition_i>0; $JoinCondition_i--){
					if($JoinCondition_i == $i)
						$JoinCondition .= " Channel".($i-1).".Time = Channel".$i.".Time ";
					else{
						$JoinCondition .= " OR ";
						$JoinCondition .= " Channel".($JoinCondition_i-1).".Time = Channel".$i.".Time ";
					}
				}
				$ChannelData .= $JoinCondition;
			}
			
			$ChannelCol .= ',Channel'.$i.'.Demand'." AS Col".$i."_demand";
			$ChannelCol .= ',Channel'.$i.'.kWh'." AS Col".$i."_kwh";
			$ChannelCol .= ',Channel'.$i.'.PF'." AS Col".$i."_pf";
			$ChannelCol .= ',Channel'.$i.'.V'." AS Col".$i."_v";
			$ChannelCol .= ',Channel'.$i.'.I'." AS Col".$i."_i";
			$ChannelCol .= ',Channel'.$i.'.kVA'." AS Col".$i."_kva";
			$ChannelCol .= ',Channel'.$i.'.kvar'." AS Col".$i."_kvar";
			
			//欄位資訊
			$Data = $xml->addChild('ModuleInfo');
			$Data->addAttribute("DeviceName",$group_modules_obj[$i]{"DeviceName"});
			$Data->addAttribute("DeviceNickname",$group_modules_obj[$i]{"DeviceNickname"});
			$Data->addAttribute("ModuleName",$group_modules_obj[$i]{"ModuleName"});
			$Data->addAttribute("ModuleNickname",$group_modules_obj[$i]{"ModuleNickname"});
			$Data->addAttribute("module_channel",$group_modules_obj[$i]{"Loop"});
			$Data->addAttribute("ChannelNickname",$group_modules_obj[$i]{"ChannelNickname"});
		}
		$sql = "";
		$sql .= " select ".$TimeCol." AS Time ".$ChannelCol." from ( ";
		$sql .= $ChannelData;
		$sql .= " order by Time ";
	}
	else if(DB_IS_MYSQL){
		$ChannelData = "";
		$array_channel=array();
		$col_text_sql = "";
		for($i=0; $i<count($group_modules_obj); $i++){
			if($i==0)
				$TimeCol = 'Channel'.$i.".Time";
			else
				$TimeCol = ' IFNULL(Channel'.$i.'.Time,'.$TimeCol.')';
		}
		
		for($i=0; $i<count($group_modules_obj); $i++){
			if($i!=0)
				$col_text_sql .= ",''";
			$col_text_sql .=  ",Channel".$i.".Demand AS Col".$i."_demand ,Channel".$i.".kWh AS Col".$i."_kwh,Channel".$i.".PF AS Col".$i."_pf,Channel".$i.".V AS Col".$i."_v,Channel".$i.".I AS Col".$i."_i,Channel".$i.".kVA AS Col".$i."_kva,Channel".$i.".kvar AS Col".$i."_kvar";
		}
		$col_text_sql = $TimeCol." AS Time ".$col_text_sql;
		
		
		for($i=0; $i<count($group_modules_obj); $i++){
			$module_table_name ="";
			$module_table_name = $group_modules_obj[$i]{"Username"}.".uid_".$group_modules_obj[$i]{"DeviceUID"}."_".$group_modules_obj[$i]{"ModuleUID"};
			$module_Loop = $group_modules_obj[$i]{"Loop"};
			$PhaseType = $group_modules_obj[$i]{"PhaseType"};
			$phase = 4;
			if($PhaseType == 1)
				$phase = 1;
			else
				$phase = 4;
			$table_time_code = "";
			if($table_time == "HOUR") $table_time_code = "%H";
			else if($table_time == "DAY") $table_time_code = "%d";
			else if($table_time == "WEEKDAY") $table_time_code = "%w";
			else if($table_time == "MONTH") $table_time_code = "%m";
			
			
			if($i!=0){
				$ChannelData .= " UNION ALL ";
			}

			$TimeCol = 'Channel'.$i.".Time";
			$ChannelData .= "Select ".$col_text_sql." from(";
			$ChannelData .= "SELECT total.Time AS Time,Demand,kWh,PF,V,I,kVA,kvar FROM (SELECT DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'),'".$table_time_code."') AS Time, Max(ABS(Demand)) AS Demand,SUM(DeltaTotalKWH) AS kWh,AVG(ABS(PF)) AS PF,AVG(KVA) AS kVA,AVG(KVAR) AS kvar,AVG(V) AS V,AVG(I) AS I  FROM (SELECT * FROM ".$module_table_name." where `Loop`='".$module_Loop."'and Phase='".$phase."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."' ) AS subquery GROUP BY DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'),'".$table_time_code."')) AS total";
			$ChannelData .= ") As Channel".$i;
			
			for($j=0; $j<count($group_modules_obj); $j++){
				if($i==$j)
					continue;
				$module_table_name ="";
				$module_table_name = $group_modules_obj[$j]{"Username"}.".uid_".$group_modules_obj[$j]{"DeviceUID"}."_".$group_modules_obj[$j]{"ModuleUID"};
				$module_Loop = $group_modules_obj[$j]{"Loop"};
				$PhaseType = $group_modules_obj[$j]{"PhaseType"};
				$phase = 4;
				if($PhaseType == 1)
					$phase = 1;
				else
					$phase = 4;
				$table_time_code = "";
				if($table_time == "HOUR") $table_time_code = "%H";
				else if($table_time == "DAY") $table_time_code = "%d";
				else if($table_time == "WEEKDAY") $table_time_code = "%w";
				else if($table_time == "MONTH") $table_time_code = "%m";
				
				$ChannelData .= " LEFT JOIN (";
				$ChannelData .= " SELECT total.Time AS Time,Demand,kWh,PF,V,I,kVA,kvar FROM (SELECT DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'),'".$table_time_code."') AS Time, Max(ABS(Demand)) AS Demand,SUM(DeltaTotalKWH) AS kWh,AVG(ABS(PF)) AS PF,AVG(KVA) AS kVA,AVG(KVAR) AS kvar,AVG(V) AS V,AVG(I) AS I  FROM (SELECT * FROM ".$module_table_name." where `Loop`='".$module_Loop."'and Phase='".$phase."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."' ) AS subquery GROUP BY DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'),'".$table_time_code."')) AS total";
				$ChannelData .= " ) As Channel".$j;
				$ChannelData .= " ON Channel".$i.".Time=Channel".$j.".Time";
			}
			
			if($i!=0){
				for($channel_idx=0; $channel_idx<count($array_channel); $channel_idx++){
					if($channel_idx==0)
						$ChannelData .= " WHERE ";
					else
						$ChannelData .= " AND ";
					$ChannelData .= $array_channel[$channel_idx].".Time IS NULL ";
				}
			}
			array_push($array_channel,"Channel".$i);
			//欄位資訊
			$Data = $xml->addChild('ModuleInfo');
			$Data->addAttribute("DeviceName",$group_modules_obj[$i]{"DeviceName"});
			$Data->addAttribute("DeviceNickname",$group_modules_obj[$i]{"DeviceNickname"});
			$Data->addAttribute("ModuleName",$group_modules_obj[$i]{"ModuleName"});
			$Data->addAttribute("ModuleNickname",$group_modules_obj[$i]{"ModuleNickname"});
			$Data->addAttribute("module_channel",$group_modules_obj[$i]{"Loop"});
			$Data->addAttribute("ChannelNickname",$group_modules_obj[$i]{"ChannelNickname"});
		}
		$sql = "";
		$sql .= $ChannelData;
		$sql .= " order by Time ";
	}	
	else if(DB_IS_ORACLE){
		$table_time_code = "";
		if($table_time == "HOUR") $table_time_code = "HH24";
		else if($table_time == "DAY") $table_time_code = "DD";
		else if($table_time == "WEEKDAY") $table_time_code = "D";
		else if($table_time == "MONTH") $table_time_code = "MM";
		
		for($i=0; $i<count($group_modules_obj); $i++){
			$module_table_name ="";
			$module_table_name = $group_modules_obj[$i]{"Username"}.".uid_".$group_modules_obj[$i]{"DeviceUID"}."_".$group_modules_obj[$i]{"ModuleUID"};
			$module_Loop = $group_modules_obj[$i]{"Loop"};
			$PhaseType = $group_modules_obj[$i]{"PhaseType"};
			$phase = 4;
			if($PhaseType == 1)
				$phase = 1;
			else
				$phase = 4;
			
			if($i==0){
				$TimeCol = 'Channel'.$i.".TIME";
				$ChannelData = "SELECT total.TIME AS \"TIME\",\"DEMAND\",kWh,PF,V,I,kVA,kvar FROM (SELECT TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')),'".$table_time_code."') AS \"TIME\", Max(ABS(\"DEMAND\")) AS \"DEMAND\",SUM(DeltaTotalKWH) AS kWh,AVG(ABS(PF)) AS PF,AVG(KVA) AS kVA,AVG(KVAR) AS kvar,AVG(V) AS V,AVG(I) AS I  FROM (SELECT * FROM ".$module_table_name." where Loop='".$module_Loop."'and Phase='".$phase."' and sys_extract_utc(from_tz(DateTime,'".$timezone."')) between TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS') ) subquery GROUP BY TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')),'".$table_time_code."')) total";
				$ChannelData .= ") Channel".$i;
			}
			else{
				$TimeCol = ' NVL(Channel'.$i.'.TIME,'.$TimeCol.') ';
				$ChannelData .= " FULL JOIN (";
				$ChannelData .= "SELECT total.TIME AS \"TIME\",\"DEMAND\",kWh,PF,V,I,kVA,kvar FROM (SELECT TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')),'".$table_time_code."') AS \"TIME\", Max(ABS(\"DEMAND\")) AS \"DEMAND\",SUM(DeltaTotalKWH) AS kWh,AVG(ABS(PF)) AS PF,AVG(KVA) AS kVA,AVG(KVAR) AS kvar,AVG(V) AS V,AVG(I) AS I  FROM (SELECT * FROM ".$module_table_name." where Loop='".$module_Loop."'and Phase='".$phase."' and sys_extract_utc(from_tz(DateTime,'".$timezone."')) between TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS') ) subquery GROUP BY TO_CHAR(sys_extract_utc(from_tz(DateTime,'".$timezone."')),'".$table_time_code."')) total";
				$ChannelData .= " ) Channel".$i." ON ";
				//join條件式()
				$JoinCondition = "";
				for($JoinCondition_i = $i; $JoinCondition_i>0; $JoinCondition_i--){
					if($JoinCondition_i == $i)
						$JoinCondition .= " Channel".($i-1).".\"TIME\" = Channel".$i.".\"TIME\" ";
					else{
						$JoinCondition .= " OR ";
						$JoinCondition .= " Channel".($JoinCondition_i-1).".\"TIME\" = Channel".$i.".\"TIME\" ";
					}
				}
				$ChannelData .= $JoinCondition;
			}
			
			$ChannelCol .= ',Channel'.$i.'.DEMAND'." AS Col".$i."_demand";
			$ChannelCol .= ',Channel'.$i.'.kWh'." AS Col".$i."_kwh";
			$ChannelCol .= ',Channel'.$i.'.PF'." AS Col".$i."_pf";
			$ChannelCol .= ',Channel'.$i.'.V'." AS Col".$i."_v";
			$ChannelCol .= ',Channel'.$i.'.I'." AS Col".$i."_i";
			$ChannelCol .= ',Channel'.$i.'.kVA'." AS Col".$i."_kva";
			$ChannelCol .= ',Channel'.$i.'.kvar'." AS Col".$i."_kvar";
			
			//欄位資訊
			$Data = $xml->addChild('ModuleInfo');
			$Data->addAttribute("DeviceName",$group_modules_obj[$i]{"DeviceName"});
			$Data->addAttribute("DeviceNickname",$group_modules_obj[$i]{"DeviceNickname"});
			$Data->addAttribute("ModuleName",$group_modules_obj[$i]{"ModuleName"});
			$Data->addAttribute("ModuleNickname",$group_modules_obj[$i]{"ModuleNickname"});
			$Data->addAttribute("module_channel",$group_modules_obj[$i]{"Loop"});
			$Data->addAttribute("ChannelNickname",$group_modules_obj[$i]{"ChannelNickname"});
		}
		$sql = "";
		$sql .= " select ".$TimeCol." AS \"TIME\" ".$ChannelCol." from ( ";
		$sql .= $ChannelData;
		$sql .= " order by \"TIME\" ";
	}
	
	
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	
	
	//報表值
	$attr = array("_demand","_kwh","_pf","_i","_v","_kva","_kvar");
	While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
		$Data = $xml->addChild('Data');
		if($table_time == "WEEKDAY" && DB_IS_MYSQL)
			$Data->addAttribute("Time",$obj["Time"]+1);
		else
			$Data->addAttribute("Time",$obj["Time"]*1);
		for($i=0; $i<count($group_modules_obj); $i++){
			for($attr_idx=0; $attr_idx<count($attr);$attr_idx++){
				$Col = "Col".$i.$attr[$attr_idx];
				if(is_null($obj[$Col]))
					$Data->addAttribute($Col,"");
				else
					$Data->addAttribute($Col,floor_dec($obj[$Col],3));
			}
		}
	}
	if(isset($stmt))
		$stmt->closeCursor();
	
	//Other
	$sql = "";
	$totalKWH = 0;
	$Data = $xml->addChild('Other');
	for($i=0; $i<count($group_modules_obj); $i++){
		
		$module_Loop = $group_modules_obj[$i]{"Loop"};
		$PhaseType = $group_modules_obj[$i]{"PhaseType"};
		$phase = 4;
		if($PhaseType == 1)
			$phase = 1;
		else
			$phase = 4;
		
		if(DB_IS_MSSQL){
			$module_table_name = $group_modules_obj[$i]{"Username"}.".dbo.uid_".$group_modules_obj[$i]{"DeviceUID"}."_".$group_modules_obj[$i]{"ModuleUID"};
			$sql = " SELECT SUM(DeltaTotalKWH) AS SUMkwh FROM ".$module_table_name." where Loop='".$module_Loop."' and Phase='".$phase."' and DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."' ; ";
		}
		else if(DB_IS_MYSQL){
			$module_table_name = $group_modules_obj[$i]{"Username"}.".uid_".$group_modules_obj[$i]{"DeviceUID"}."_".$group_modules_obj[$i]{"ModuleUID"};
			$sql = " SELECT SUM(DeltaTotalKWH) AS SUMkwh FROM ".$module_table_name." where `Loop`='".$module_Loop."' and Phase='".$phase."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."' ; ";
		}
		else if(DB_IS_ORACLE){
			$module_table_name = $group_modules_obj[$i]{"Username"}.".uid_".$group_modules_obj[$i]{"DeviceUID"}."_".$group_modules_obj[$i]{"ModuleUID"};
			$sql = " SELECT SUM(DeltaTotalKWH) AS SUMkwh FROM ".$module_table_name." where Loop='".$module_Loop."' and Phase='".$phase."' and sys_extract_utc(from_tz(DateTime,'".$timezone."')) between TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS'); ";
		}
		
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		
		While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
			$Data->addAttribute("Col".$i."_val",floor_dec($obj["SUMkwh"],3));
			$totalKWH = $totalKWH + $obj["SUMkwh"];
		}
	}
	$Data->addAttribute("total_kwh",floor_dec($totalKWH,3));
	
	if(isset($stmt))
		$stmt->closeCursor();
	$conn = null;
    
	header('Content-type: text/xml');
	print($xml->asXML());
}

function load_io_report_data_group(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}
	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
	
	//----參數
	$groupuid = $_POST["GroupUID"];//選擇群組
	$datetime_begin =$_POST["datetime_begin"];//選擇的日期
	$datetime_end =$_POST["datetime_end"];//選擇的日期
	$report_type = $_POST["report_type"];//報表類型：日報-DAY、月報-MONTH、年-YEAR
	$timezone = $_POST["timezone"];
	
	if(DB_IS_ORACLE){
		if (strpos($timezone, '+') !== false) 
			$timezone=str_replace('+','-',$timezone);
		else
			$timezone=str_replace('-','+',$timezone);
	}
	
	//收集群組各通道
	$sql_group = "";
	if(DB_IS_MSSQL){
		$sql_group .= " select Username, group_data.DeviceUID as DeviceUID, ModuleUID,group_data.Channel from ".$_SESSION["username"].".dbo.group_data ";
		$sql_group .= " join manager.dbo.account as groupshare on group_data.AccountUID = groupshare.UID ";
		$sql_group .= " where GroupInfoUID = ".$groupuid." ";
	}
	else if(DB_IS_MYSQL){
		$sql_group .= " select Username, group_data.DeviceUID as DeviceUID, ModuleUID,group_data.Channel from ".$_SESSION["username"].".group_data ";
		$sql_group .= " join manager.account as groupshare on group_data.AccountUID = groupshare.UID ";
		$sql_group .= " where GroupInfoUID = ".$groupuid." ";
	}
	else if(DB_IS_ORACLE){
		$sql_group .= " select Username, group_data.DeviceUID as DeviceUID, ModuleUID,group_data.Channel from ".$_SESSION["username"].".group_data ";
		$sql_group .= " join manager.\"ACCOUNT\" groupshare on group_data.AccountUID = groupshare.\"UID\" ";
		$sql_group .= " where GroupInfoUID = ".$groupuid." ";
	}
	
	$stmt_group = $conn->prepare($sql_group);
	$stmt_group->execute();
	
	$group_modules_obj = array();
	
	While($obj_group = $stmt_group->fetch(PDO::FETCH_ASSOC)){
		$Username = $obj_group["Username"];
		$DeviceUID = $obj_group["DeviceUID"];
		$ModuleUID = $obj_group["ModuleUID"];
		$Channel = $obj_group["Channel"];
		
		//Channel Nickname,Unit
		$stmt_group_channel = $conn->prepare(
			DB_IS_MSSQL  ? "select Nickname AS ChannelNickname,Unit AS Unit from ".$Username.".dbo.channel where DeviceUID='".$DeviceUID."' AND ModuleUID='".$ModuleUID."' AND Channel='".$Channel."';" : (
			DB_IS_MYSQL  ? "select Nickname AS ChannelNickname,Unit AS Unit from ".$Username.".channel where DeviceUID='".$DeviceUID."' AND ModuleUID='".$ModuleUID."' AND Channel='".$Channel."';" : (
			DB_IS_ORACLE ? "select Nickname AS ChannelNickname,Unit AS Unit from ".$Username.".channel where DeviceUID='".$DeviceUID."' AND ModuleUID='".$ModuleUID."' AND Channel='".$Channel."';" : null))
		);
		$stmt_group_channel->execute();

		While($obj_channelinfo = $stmt_group_channel->fetch(PDO::FETCH_ASSOC)){
			$ChannelNickname = $obj_channelinfo["ChannelNickname"];
			$Unit = $obj_channelinfo["Unit"];
		}
		
		$sql_group_module = "";
		if(DB_IS_MSSQL){
			$sql_group_module .= " SELECT device.ModelName AS DeviceName,device.Nickname AS DeviceNickname,CASE WHEN Type =2 Then 'IR' else module.ModelName END AS ModuleName,module.Nickname AS ModuleNickname FROM ".$Username.".dbo.module  JOIN ".$Username.".dbo.device ON ".$Username.".dbo.module.DeviceUID = ".$Username.".dbo.device.UID ";
			$sql_group_module .= " WHERE ".$Username.".dbo.module.DeviceUID = '".$DeviceUID."' AND ".$Username.".dbo.module.UID = '".$ModuleUID."' AND ".$Username.".dbo.module.Removed != 1;";
		}
		else if(DB_IS_MYSQL){
			$sql_group_module .= " SELECT device.ModelName AS DeviceName,device.Nickname AS DeviceNickname,CASE WHEN Type =2 Then 'IR' else module.ModelName END AS ModuleName,module.Nickname AS ModuleNickname FROM ".$Username.".module  JOIN ".$Username.".device ON ".$Username.".module.DeviceUID = ".$Username.".device.UID ";
			$sql_group_module .= " WHERE ".$Username.".module.DeviceUID = '".$DeviceUID."' AND ".$Username.".module.UID = '".$ModuleUID."' AND ".$Username.".module.Removed != 1;";
		}
		else if(DB_IS_ORACLE){
			$sql_group_module .= " SELECT device.ModelName AS DeviceName,device.Nickname AS DeviceNickname,CASE WHEN \"TYPE\" =2 Then 'IR' else module.ModelName END AS ModuleName,module.Nickname AS ModuleNickname FROM ".$Username.".module  JOIN ".$Username.".device ON ".$Username.".module.DeviceUID = ".$Username.".device.\"UID\" ";
			$sql_group_module .= " WHERE ".$Username.".module.DeviceUID = '".$DeviceUID."' AND ".$Username.".module.\"UID\" = '".$ModuleUID."' AND ".$Username.".module.Removed != 1;";
		}
		$stmt_group_module = $conn->prepare($sql_group_module);
		$stmt_group_module->execute();
		
		While($obj_group_module = $stmt_group_module->fetch(PDO::FETCH_ASSOC)){
			$group_module_info = array(
				"Username" => $Username,
				"DeviceUID" => $DeviceUID,
				"ModuleUID" => $ModuleUID,
				"Channel" => $Channel,
				
				"DeviceName" => $obj_group_module["DeviceName"],
				"DeviceNickname" => $obj_group_module["DeviceNickname"],
				"ModuleName" => $obj_group_module["ModuleName"],
				"ModuleNickname" => $obj_group_module["ModuleNickname"],
				"ChannelNickname" => $ChannelNickname,
				"Unit" => $Unit
			);
			array_push($group_modules_obj,$group_module_info);
		}
		if(isset($stmt_group_module))
			$stmt_group_module->closeCursor();
	}
	if(isset($stmt_group))
		$stmt_group->closeCursor();
	
	if(count($group_modules_obj)==0){
		$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><info/>');
		$conn = null;
		
		header('Content-type: text/xml');
		print($xml->asXML());
		return;
	}
	$Date_array = explode("-",$selectDate);
	
	$sql = "";
	$table_time = "";
	
	if($report_type =="DAY"){
		$table_time = "HOUR";
	}
	else if($report_type =="MONTH"){
		$table_time = "DAY";
	}
	else if($report_type =="WEEK"){
		$table_time = "WEEKDAY";
	}
	else if($report_type =="YEAR"||$report_type =="QUARTER"){
		$table_time = "MONTH";
	}

	$TimeCol='';//時間欄位(Null時查看其他通道是否有值)
	$ChannelCol='';//取得通道欄位
	$ChannelData = '';//每個模組通道值
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><info/>');
	if(DB_IS_MSSQL){
		for($i=0; $i<count($group_modules_obj); $i++){
			$module_table_name = $group_modules_obj[$i]{"Username"}.".dbo.uid_".$group_modules_obj[$i]{"DeviceUID"}."_".$group_modules_obj[$i]{"ModuleUID"};
			$module_channel = $group_modules_obj[$i]{"Channel"};
			if($i==0){
				$TimeCol = 'Channel'.$i.".Time";
				$ChannelData = "select A.Time AS Time,A.Max_val AS Max_val,A.Min_val AS Min_val,A.Avg_val AS Avg_val,A.Sum_val,B.".$module_channel." AS Final_val from(	select DATEPART(".$table_time.", SWITCHOFFSET(subquery1.DateTime,'".$timezone."')) AS Time,MAX(CONVERT(REAL,subquery1.".$module_channel.")) as Max_val,MIN(CONVERT(REAL,subquery1.".$module_channel.")) as Min_val,AVG(CONVERT(REAL,subquery1.".$module_channel.")) as Avg_val,SUM(CONVERT(REAL,subquery1.".$module_channel.")) AS Sum_val from ( select * from ".$module_table_name." Where DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."' ) AS subquery1 GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(subquery1.DateTime,'".$timezone."')) ) AS A left join (select quest1.Time,quest2.".$module_channel." from (select DATEPART(".$table_time.", SWITCHOFFSET(A.DateTime,'".$timezone."')) AS Time,MAX(A.DateTime) AS MaxTime from (select DateTime,".$module_channel." from ".$module_table_name." Where DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."' AND ".$module_channel." IS NOT NULL ) AS A GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(A.DateTime,'".$timezone."'))) AS quest1 left JOIN ".$module_table_name." AS quest2 ON quest2.DateTime=quest1.MaxTime ) AS B ON A.Time=B.Time WHERE Max_val is not null ";
				$ChannelData .= ") As Channel".$i;
			}
			else{
				$TimeCol = ' ISNULL(Channel'.$i.'.Time,'.$TimeCol.') ';
				$ChannelData .= " FULL JOIN (";
				$ChannelData .= " select A.Time AS Time,A.Max_val AS Max_val,A.Min_val AS Min_val,A.Avg_val AS Avg_val,A.Sum_val,B.".$module_channel." AS Final_val from(	select DATEPART(".$table_time.", SWITCHOFFSET(subquery1.DateTime,'".$timezone."')) AS Time,MAX(CONVERT(REAL,subquery1.".$module_channel.")) as Max_val,MIN(CONVERT(REAL,subquery1.".$module_channel.")) as Min_val,AVG(CONVERT(REAL,subquery1.".$module_channel.")) as Avg_val,SUM(CONVERT(REAL,subquery1.".$module_channel.")) AS Sum_val from ( select * from ".$module_table_name." Where DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."' ) AS subquery1 GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(subquery1.DateTime,'".$timezone."')) ) AS A left join (select quest1.Time,quest2.".$module_channel." from (select DATEPART(".$table_time.", SWITCHOFFSET(A.DateTime,'".$timezone."')) AS Time,MAX(A.DateTime) AS MaxTime from (select DateTime,".$module_channel." from ".$module_table_name." Where DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."' AND ".$module_channel." IS NOT NULL ) AS A GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(A.DateTime,'".$timezone."'))) AS quest1 left JOIN ".$module_table_name." AS quest2 ON quest2.DateTime=quest1.MaxTime ) AS B ON A.Time=B.Time WHERE Max_val is not null ";
				
				$ChannelData .= " ) As Channel".$i." ON ";
				/*-join條件式()-*/
				$JoinCondition = "";
				for($JoinCondition_i = $i; $JoinCondition_i>0; $JoinCondition_i--){
					if($JoinCondition_i == $i)
						$JoinCondition .= " Channel".($i-1).".Time = Channel".$i.".Time ";
					else{
						$JoinCondition .= " OR ";
						$JoinCondition .= " Channel".($JoinCondition_i-1).".Time = Channel".$i.".Time ";
					}
				}
				$ChannelData .= $JoinCondition;
			}
			$ChannelCol .= ',Channel'.$i.'.Max_val'." AS Col".$i."_max";
			$ChannelCol .= ',Channel'.$i.'.Min_val'." AS Col".$i."_min";
			$ChannelCol .= ',Channel'.$i.'.Avg_val'." AS Col".$i."_avg";
			$ChannelCol .= ',Channel'.$i.'.Sum_val'." AS Col".$i."_sum";
			$ChannelCol .= ',Channel'.$i.'.Final_val'." AS Col".$i."_fin";
			
			//欄位資訊
			$Data = $xml->addChild('ModuleInfo');
			$Data->addAttribute("DeviceName",$group_modules_obj[$i]{"DeviceName"});
			$Data->addAttribute("DeviceNickname",$group_modules_obj[$i]{"DeviceNickname"});
			$Data->addAttribute("ModuleName",$group_modules_obj[$i]{"ModuleName"});
			$Data->addAttribute("ModuleNickname",$group_modules_obj[$i]{"ModuleNickname"});
			$Data->addAttribute("module_channel",$module_channel);
			$Data->addAttribute("ChannelNickname",$group_modules_obj[$i]{"ChannelNickname"});
			$Data->addAttribute("Unit",$group_modules_obj[$i]{"Unit"});
		}
		$sql = "";
		$sql .= " select ".$TimeCol." AS Time ".$ChannelCol." from ( ";
		$sql .= $ChannelData;
		$sql .= " order by Time ";
	}
	else if(DB_IS_MYSQL){
		for($i=0; $i<count($group_modules_obj); $i++){
			$table_time_code = "";
			if($table_time == "HOUR") $table_time_code = "%H";
			else if($table_time == "DAY") $table_time_code = "%d";
			else if($table_time == "WEEKDAY") $table_time_code = "%w";
			else if($table_time == "MONTH") $table_time_code = "%m";
			$module_table_name = $group_modules_obj[$i]{"Username"}.".uid_".$group_modules_obj[$i]{"DeviceUID"}."_".$group_modules_obj[$i]{"ModuleUID"};
			$module_channel = $group_modules_obj[$i]{"Channel"};
			if($i==0){
				$TimeCol = 'Channel'.$i.".Time";
				$ChannelData = "select A.Time AS Time,A.Max_val AS Max_val,A.Min_val AS Min_val,A.Avg_val AS Avg_val,A.Sum_val AS Sum_val,B.".$module_channel." AS Final_val from( select DATE_FORMAT(CONVERT_TZ(subquery1.DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time,MAX(subquery1.".$module_channel.") as Max_val,MIN(subquery1.".$module_channel.") as Min_val,AVG(subquery1.".$module_channel.") as Avg_val,SUM(subquery1.".$module_channel.") as Sum_val from (  select * from ".$module_table_name." Where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."'  ) AS subquery1  GROUP BY DATE_FORMAT(CONVERT_TZ(subquery1.DateTime,'+00:00','".$timezone."'), '".$table_time_code."') ) AS A left join (select quest1.Time,quest2.".$module_channel." from (select DATE_FORMAT(A.DateTime, '".$table_time_code."') AS Time,MAX(A.DateTime) AS MaxTime from ( select CONVERT_TZ(DateTime,'+00:00','".$timezone."') as DateTime,".$module_channel." from ".$module_table_name." Where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."' AND ".$module_channel." IS NOT NULL) AS A GROUP BY DATE_FORMAT(CONVERT_TZ(A.DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS quest1 left JOIN ".$module_table_name." AS quest2 ON CONVERT_TZ(quest2.DateTime,'+00:00','".$timezone."')=quest1.MaxTime ) AS B ON A.Time=B.Time WHERE Max_val IS NOT NULL  ";
				$ChannelData .= ") As Channel".$i;
			}
			else{
				$TimeCol = ' IFNULL(Channel'.$i.'.Time,'.$TimeCol.') ';
				$ChannelData .= " LEFT JOIN (";
				$ChannelData .= "select A.Time AS Time,A.Max_val AS Max_val,A.Min_val AS Min_val,A.Avg_val AS Avg_val,A.Sum_val AS Sum_val,B.".$module_channel." AS Final_val from( select DATE_FORMAT(CONVERT_TZ(subquery1.DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time,MAX(subquery1.".$module_channel.") as Max_val,MIN(subquery1.".$module_channel.") as Min_val,AVG(subquery1.".$module_channel.") as Avg_val,SUM(subquery1.".$module_channel.") as Sum_val from (  select * from ".$module_table_name." Where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."'  ) AS subquery1  GROUP BY DATE_FORMAT(CONVERT_TZ(subquery1.DateTime,'+00:00','".$timezone."'), '".$table_time_code."') ) AS A left join (select quest1.Time,quest2.".$module_channel." from (select DATE_FORMAT(A.DateTime, '".$table_time_code."') AS Time,MAX(A.DateTime) AS MaxTime from ( select CONVERT_TZ(DateTime,'+00:00','".$timezone."') as DateTime,".$module_channel." from ".$module_table_name." Where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."' AND ".$module_channel." IS NOT NULL) AS A GROUP BY DATE_FORMAT(CONVERT_TZ(A.DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS quest1 left JOIN ".$module_table_name." AS quest2 ON CONVERT_TZ(quest2.DateTime,'+00:00','".$timezone."')=quest1.MaxTime ) AS B ON A.Time=B.Time WHERE Max_val IS NOT NULL  ";
				
				$ChannelData .= " ) As Channel".$i." ON ";
				/*-join條件式()-*/
				$JoinCondition = "";
				for($JoinCondition_i = $i; $JoinCondition_i>0; $JoinCondition_i--){
					if($JoinCondition_i == $i)
						$JoinCondition .= " Channel".($i-1).".Time = Channel".$i.".Time ";
					else{
						$JoinCondition .= " OR ";
						$JoinCondition .= " Channel".($JoinCondition_i-1).".Time = Channel".$i.".Time ";
					}
				}
				$ChannelData .= $JoinCondition;
			}
			$ChannelCol .= ',Channel'.$i.'.Max_val'." AS Col".$i."_max";
			$ChannelCol .= ',Channel'.$i.'.Min_val'." AS Col".$i."_min";
			$ChannelCol .= ',Channel'.$i.'.Avg_val'." AS Col".$i."_avg";
			$ChannelCol .= ',Channel'.$i.'.Sum_val'." AS Col".$i."_sum";
			$ChannelCol .= ',Channel'.$i.'.Final_val'." AS Col".$i."_fin";
			
			//欄位資訊
			$Data = $xml->addChild('ModuleInfo');
			$Data->addAttribute("DeviceName",$group_modules_obj[$i]{"DeviceName"});
			$Data->addAttribute("DeviceNickname",$group_modules_obj[$i]{"DeviceNickname"});
			$Data->addAttribute("ModuleName",$group_modules_obj[$i]{"ModuleName"});
			$Data->addAttribute("ModuleNickname",$group_modules_obj[$i]{"ModuleNickname"});
			$Data->addAttribute("module_channel",$module_channel);
			$Data->addAttribute("ChannelNickname",$group_modules_obj[$i]{"ChannelNickname"});
			$Data->addAttribute("Unit",$group_modules_obj[$i]{"Unit"});
		}
		$sql1 = "";
		$sql1 .= " select ".$TimeCol." AS Time ".$ChannelCol." from ( ";
		$sql1 .= $ChannelData;
		
		$TimeCol='';//時間欄位(Null時查看其他通道是否有值)
		$ChannelCol='';//取得通道欄位
		$ChannelData = '';//每個模組通道值
		
		for($i=0; $i<count($group_modules_obj); $i++){
			$table_time_code = "";
			if($table_time == "HOUR") $table_time_code = "%H";
			else if($table_time == "DAY") $table_time_code = "%d";
			else if($table_time == "WEEKDAY") $table_time_code = "%w";
			else if($table_time == "MONTH") $table_time_code = "%m";
			$module_table_name = $group_modules_obj[$i]{"Username"}.".uid_".$group_modules_obj[$i]{"DeviceUID"}."_".$group_modules_obj[$i]{"ModuleUID"};
			$module_channel = $group_modules_obj[$i]{"Channel"};
			if($i==0){
				$TimeCol = 'Channel'.$i.".Time";
				$ChannelData = "select A.Time AS Time,A.Max_val AS Max_val,A.Min_val AS Min_val,A.Avg_val AS Avg_val,A.Sum_val AS Sum_val,B.".$module_channel." AS Final_val from( select DATE_FORMAT(CONVERT_TZ(subquery1.DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time,MAX(subquery1.".$module_channel.") as Max_val,MIN(subquery1.".$module_channel.") as Min_val,AVG(subquery1.".$module_channel.") as Avg_val,SUM(subquery1.".$module_channel.") as Sum_val from (  select * from ".$module_table_name." Where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."'  ) AS subquery1  GROUP BY DATE_FORMAT(CONVERT_TZ(subquery1.DateTime,'+00:00','".$timezone."'), '".$table_time_code."') ) AS A left join (select quest1.Time,quest2.".$module_channel." from (select DATE_FORMAT(A.DateTime, '".$table_time_code."') AS Time,MAX(A.DateTime) AS MaxTime from ( select CONVERT_TZ(DateTime,'+00:00','".$timezone."') as DateTime,".$module_channel." from ".$module_table_name." Where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."' AND ".$module_channel." IS NOT NULL) AS A GROUP BY DATE_FORMAT(CONVERT_TZ(A.DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS quest1 left JOIN ".$module_table_name." AS quest2 ON CONVERT_TZ(quest2.DateTime,'+00:00','".$timezone."')=quest1.MaxTime ) AS B ON A.Time=B.Time WHERE Max_val IS NOT NULL  ";
				$ChannelData .= ") As Channel".$i;
			}
			else{
				$TimeCol = ' IFNULL(Channel'.$i.'.Time,'.$TimeCol.') ';
				$ChannelData .= " RIGHT JOIN (";
				$ChannelData .= "select A.Time AS Time,A.Max_val AS Max_val,A.Min_val AS Min_val,A.Avg_val AS Avg_val,A.Sum_val AS Sum_val,B.".$module_channel." AS Final_val from( select DATE_FORMAT(CONVERT_TZ(subquery1.DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time,MAX(subquery1.".$module_channel.") as Max_val,MIN(subquery1.".$module_channel.") as Min_val,AVG(subquery1.".$module_channel.") as Avg_val,SUM(subquery1.".$module_channel.") as Sum_val from (  select * from ".$module_table_name." Where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."'  ) AS subquery1  GROUP BY DATE_FORMAT(CONVERT_TZ(subquery1.DateTime,'+00:00','".$timezone."'), '".$table_time_code."') ) AS A left join (select quest1.Time,quest2.".$module_channel." from (select DATE_FORMAT(A.DateTime, '".$table_time_code."') AS Time,MAX(A.DateTime) AS MaxTime from ( select CONVERT_TZ(DateTime,'+00:00','".$timezone."') as DateTime,".$module_channel." from ".$module_table_name." Where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."' AND ".$module_channel." IS NOT NULL) AS A GROUP BY DATE_FORMAT(CONVERT_TZ(A.DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS quest1 left JOIN ".$module_table_name." AS quest2 ON CONVERT_TZ(quest2.DateTime,'+00:00','".$timezone."')=quest1.MaxTime ) AS B ON A.Time=B.Time WHERE Max_val IS NOT NULL  ";
				
				$ChannelData .= " ) As Channel".$i." ON ";
				/*-join條件式()-*/
				$JoinCondition = "";
				for($JoinCondition_i = $i; $JoinCondition_i>0; $JoinCondition_i--){
					if($JoinCondition_i == $i)
						$JoinCondition .= " Channel".($i-1).".Time = Channel".$i.".Time ";
					else{
						$JoinCondition .= " OR ";
						$JoinCondition .= " Channel".($JoinCondition_i-1).".Time = Channel".$i.".Time ";
					}
				}
				$ChannelData .= $JoinCondition;
			}
			$ChannelCol .= ',Channel'.$i.'.Max_val'." AS Col".$i."_max";
			$ChannelCol .= ',Channel'.$i.'.Min_val'." AS Col".$i."_min";
			$ChannelCol .= ',Channel'.$i.'.Avg_val'." AS Col".$i."_avg";
			$ChannelCol .= ',Channel'.$i.'.Sum_val'." AS Col".$i."_sum";
			$ChannelCol .= ',Channel'.$i.'.Final_val'." AS Col".$i."_fin";
		}
		$sql2 = "";
		$sql2 .= " select ".$TimeCol." AS Time ".$ChannelCol." from ( ";
		$sql2 .= $ChannelData;
		
		$sql = "";
		$sql .= $sql1." UNION ".$sql2;
		$sql .= " order by Time ";
	}
	else if(DB_IS_ORACLE){
		$table_time_code = "";
		if($table_time == "HOUR") $table_time_code = "HH24";
		else if($table_time == "DAY") $table_time_code = "DD";
		else if($table_time == "WEEKDAY") $table_time_code = "D";
		else if($table_time == "MONTH") $table_time_code = "MM";
		
		for($i=0; $i<count($group_modules_obj); $i++){
			$module_table_name = $group_modules_obj[$i]{"Username"}.".uid_".$group_modules_obj[$i]{"DeviceUID"}."_".$group_modules_obj[$i]{"ModuleUID"};
			$module_channel = $group_modules_obj[$i]{"Channel"};
			if($i==0){
				$TimeCol = 'Channel'.$i.".TIME";
				$ChannelData = "select A.\"TIME\" AS \"TIME\",A.Max_val AS Max_val,A.Min_val AS Min_val,A.Avg_val AS Avg_val,A.Sum_val,B.".$module_channel." AS Final_val from(select TO_CHAR(sys_extract_utc(from_tz(subquery1.DateTime,'".$timezone."')),'".$table_time_code."') AS \"TIME\",MAX(subquery1.".$module_channel.") as Max_val,MIN(subquery1.".$module_channel.") as Min_val,AVG(subquery1.".$module_channel.") as Avg_val,SUM(subquery1.".$module_channel.") AS Sum_val from ( select * from ".$module_table_name." Where sys_extract_utc(from_tz(DateTime,'".$timezone."')) between TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS') ) subquery1 GROUP BY TO_CHAR(sys_extract_utc(from_tz(subquery1.DateTime,'".$timezone."')),'".$table_time_code."') ) A left join (select quest1.\"TIME\",quest2.".$module_channel." from (select TO_CHAR(A.DateTime,'".$table_time_code."') AS \"TIME\",MAX(A.DateTime) AS MaxTime from (select sys_extract_utc(from_tz(DateTime,'".$timezone."')) AS DateTime,".$module_channel." from ".$module_table_name." Where sys_extract_utc(from_tz(DateTime,'".$timezone."')) between TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS') AND ".$module_channel." IS NOT NULL ) A GROUP BY TO_CHAR(A.DateTime,'".$table_time_code."')) quest1 left JOIN ".$module_table_name." quest2 ON sys_extract_utc(from_tz(quest2.DateTime,'".$timezone."'))=quest1.MaxTime ) B ON A.\"TIME\"=B.\"TIME\" WHERE Max_val is not null ";
				$ChannelData .= ") Channel".$i;
			}
			else{
				$TimeCol = ' NVL(Channel'.$i.'.TIME,'.$TimeCol.') ';
				$ChannelData .= " FULL JOIN (";
				$ChannelData .= " select A.\"TIME\" AS \"TIME\",A.Max_val AS Max_val,A.Min_val AS Min_val,A.Avg_val AS Avg_val,A.Sum_val,B.".$module_channel." AS Final_val from(select TO_CHAR(sys_extract_utc(from_tz(subquery1.DateTime,'".$timezone."')),'".$table_time_code."') AS \"TIME\",MAX(subquery1.".$module_channel.") as Max_val,MIN(subquery1.".$module_channel.") as Min_val,AVG(subquery1.".$module_channel.") as Avg_val,SUM(subquery1.".$module_channel.") AS Sum_val from ( select * from ".$module_table_name." Where sys_extract_utc(from_tz(DateTime,'".$timezone."')) between TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS') ) subquery1 GROUP BY TO_CHAR(sys_extract_utc(from_tz(subquery1.DateTime,'".$timezone."')),'".$table_time_code."') ) A left join (select quest1.\"TIME\",quest2.".$module_channel." from (select TO_CHAR(A.DateTime,'".$table_time_code."') AS \"TIME\",MAX(A.DateTime) AS MaxTime from (select sys_extract_utc(from_tz(DateTime,'".$timezone."')) AS DateTime,".$module_channel." from ".$module_table_name." Where sys_extract_utc(from_tz(DateTime,'".$timezone."')) between TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS') AND ".$module_channel." IS NOT NULL ) A GROUP BY TO_CHAR(A.DateTime,'".$table_time_code."')) quest1 left JOIN ".$module_table_name." quest2 ON sys_extract_utc(from_tz(quest2.DateTime,'".$timezone."'))=quest1.MaxTime ) B ON A.\"TIME\"=B.\"TIME\" WHERE Max_val is not null ";
				
				$ChannelData .= " ) Channel".$i." ON ";
				/*-join條件式()-*/
				$JoinCondition = "";
				for($JoinCondition_i = $i; $JoinCondition_i>0; $JoinCondition_i--){
					if($JoinCondition_i == $i)
						$JoinCondition .= " Channel".($i-1).".\"TIME\" = Channel".$i.".\"TIME\" ";
					else{
						$JoinCondition .= " OR ";
						$JoinCondition .= " Channel".($JoinCondition_i-1).".\"TIME\" = Channel".$i.".\"TIME\" ";
					}
				}
				$ChannelData .= $JoinCondition;
			}
			$ChannelCol .= ',Channel'.$i.'.Max_val'." AS Col".$i."_max";
			$ChannelCol .= ',Channel'.$i.'.Min_val'." AS Col".$i."_min";
			$ChannelCol .= ',Channel'.$i.'.Avg_val'." AS Col".$i."_avg";
			$ChannelCol .= ',Channel'.$i.'.Sum_val'." AS Col".$i."_sum";
			$ChannelCol .= ',Channel'.$i.'.Final_val'." AS Col".$i."_fin";
			
			//欄位資訊
			$Data = $xml->addChild('ModuleInfo');
			$Data->addAttribute("DeviceName",$group_modules_obj[$i]{"DeviceName"});
			$Data->addAttribute("DeviceNickname",$group_modules_obj[$i]{"DeviceNickname"});
			$Data->addAttribute("ModuleName",$group_modules_obj[$i]{"ModuleName"});
			$Data->addAttribute("ModuleNickname",$group_modules_obj[$i]{"ModuleNickname"});
			$Data->addAttribute("module_channel",$module_channel);
			$Data->addAttribute("ChannelNickname",$group_modules_obj[$i]{"ChannelNickname"});
			$Data->addAttribute("Unit",$group_modules_obj[$i]{"Unit"});
		}
		$sql = "";
		$sql .= " select ".$TimeCol." AS Time ".$ChannelCol." from ( ";
		$sql .= $ChannelData;
		$sql .= " order by \"TIME\" ";
	}
	
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	
	
	/*報表值*/
	$attr = array("_max","_min","_avg","_sum","_fin");
	While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
		$Data = $xml->addChild('Data');
		if($table_time == "WEEKDAY" && DB_IS_MYSQL)
			$Data->addAttribute("Time",$obj["Time"]+1);
		else
			$Data->addAttribute("Time",$obj["Time"]*1);
		
		for($i=0; $i<count($group_modules_obj); $i++){
			for($attr_idx=0; $attr_idx<count($attr);$attr_idx++){
				$Col = "Col".$i.$attr[$attr_idx];
				if($obj[$Col]==NULL)
					$Data->addAttribute($Col,$obj[$Col]);
				else
					$Data->addAttribute($Col,floor_dec($obj[$Col],3));
			}
		}
	}
	
	//Other
	$sql = "";
	for($i=0; $i<count($group_modules_obj); $i++){
		$module_table_name = "";
		if(DB_IS_MSSQL)
			$module_table_name = $group_modules_obj[$i]{"Username"}.".dbo.uid_".$group_modules_obj[$i]{"DeviceUID"}."_".$group_modules_obj[$i]{"ModuleUID"};
		else if(DB_IS_MYSQL)
			$module_table_name = $group_modules_obj[$i]{"Username"}.".uid_".$group_modules_obj[$i]{"DeviceUID"}."_".$group_modules_obj[$i]{"ModuleUID"};
		else if(DB_IS_ORACLE){
			$module_table_name = $group_modules_obj[$i]{"Username"}.".uid_".$group_modules_obj[$i]{"DeviceUID"}."_".$group_modules_obj[$i]{"ModuleUID"};
		}
			
		$module_channel = $group_modules_obj[$i]{"Channel"};
		
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "SELECT MAX(CONVERT(REAL,".$module_channel.")) AS max_val,MIN(CONVERT(REAL,".$module_channel.")) AS min_val,AVG(CONVERT(REAL,".$module_channel.")) AS avg_val,SUM(CONVERT(REAL,".$module_channel.")) AS sum_val FROM ".$module_table_name." Where DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."';" : (
			DB_IS_MYSQL  ? "SELECT MAX(COALESCE(".$module_channel.", FALSE)) AS max_val,MIN(COALESCE(".$module_channel.", FALSE)) AS min_val,AVG(".$module_channel.") AS avg_val,SUM(".$module_channel.") AS sum_val FROM ".$module_table_name." Where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."';" : (
			DB_IS_ORACLE ? "SELECT MAX(".$module_channel.") AS max_val,MIN(".$module_channel.") AS min_val,AVG(".$module_channel.") AS avg_val,SUM(".$module_channel.") AS sum_val FROM ".$module_table_name." Where sys_extract_utc(from_tz(DateTime,'".$timezone."')) between TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and  TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS');" : null)));
		$stmt->execute();
		
		While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
			$Data = $xml->addChild('Other');
			$Data->addAttribute("max_val",floor_dec($obj["max_val"],3));
			$Data->addAttribute("min_val",floor_dec($obj["min_val"],3));
			$Data->addAttribute("avg_val",floor_dec($obj["avg_val"],3));
			$Data->addAttribute("sum_val",floor_dec($obj["sum_val"],3));
		}
		if(isset($stmt))
			$stmt->closeCursor();
	}
	$conn = null;
    
	header('Content-type: text/xml');
	print($xml->asXML());
	
}

function floor_dec($v, $precision){
	if($v===NULL)
		return "";
	$c = pow(10, $precision);
    return floor($v*$c)/$c;
}

function LoadTemplateList(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}
	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
	/*--------傳遞參數----------------------------*/
	$accountUID = $_POST["accountUID"];
	/*--------------------------------------*/
	$table_name = "";
	if($_POST["accountUID"] != ""){
		$accountname = "";
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "select UID, Username from manager.dbo.account where UID = '".$accountUID."';" : (
			DB_IS_MYSQL  ? "select UID, Username from manager.account where UID = '".$accountUID."';" : (
			DB_IS_ORACLE ? "select \"UID\", Username from manager.\"ACCOUNT\" where \"UID\" = '".$accountUID."';" : null)));
		$stmt->execute();
		
		
		While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
			$accountname = $obj["Username"];
		}
	}
	else
		$accountname = $_SESSION["username"];
	$stmt->closeCursor();
		
		
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT * FROM ".$accountname.".dbo.report_template;" : (
		DB_IS_MYSQL  ? "SELECT * FROM ".$accountname.".report_template;" : (
		DB_IS_ORACLE ? "SELECT * FROM ".$accountname.".report_template;" : null)));
	$stmt->execute();
	//回傳
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><info/>');	
	While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
		$Data = $xml->addChild('TemplateList');
		$Data->addAttribute("id",$obj["UID"]);
		$Data->addAttribute("name",$obj["Name"]);
		$Data->addAttribute("header",$obj["ReportHeader"]);
		$Data->addAttribute("footer",$obj["ReportFooter"]);
	}
	
	if(isset($stmt))
		$stmt->closeCursor();
	
	$conn = null;
	
	header('Content-type: text/xml');
	print($xml->asXML());
}

function LoadTemplateContent(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}
	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		
	/*--------傳遞參數----------------------------*/
	$accountUID = $_POST["accountUID"];
	$sampleUID = $_POST["sampleUID"];
	/*--------------------------------------*/
	$table_name = "";
	if($_POST["accountUID"] != ""){
		$accountname = "";
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "select UID, Username from manager.dbo.account where UID = '".$accountUID."';" : (
			DB_IS_MYSQL  ? "select UID, Username from manager.account where UID = '".$accountUID."';" : (
			DB_IS_ORACLE ? "select \"UID\", Username from manager.\"ACCOUNT\" where \"UID\" = '".$accountUID."';" : null)));
		$stmt->execute();
		While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
			$accountname = $obj["Username"];
		}
		$stmt->closeCursor();
	}
	else
		$accountname = $_SESSION["username"];
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "SELECT * FROM ".$accountname.".dbo.report_style Where ID='".$sampleUID."';" : (
		DB_IS_MYSQL  ? "SELECT * FROM ".$accountname.".report_style Where ID='".$sampleUID."';" : (
		DB_IS_ORACLE ? "SELECT * FROM ".$accountname.".report_style Where \"ID\"='".$sampleUID."';" : null)));
	$stmt->execute();
	//回傳
	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><info/>');	
	While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
		$Data = $xml->addChild("SampleContent",$obj["ReportContent"]);
	}
	
	if(isset($stmt))
		$stmt->closeCursor();
	
	$conn = null;
	
	header('Content-type: text/xml');
	print($xml->asXML());
}

function DelTemplate(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}
	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
	
	/*--------傳遞參數----------------------------*/
	$accountUID = $_POST["accountUID"];
	$sampleUID = $_POST["sampleUID"];
	/*--------------------------------------*/
	$table_name = "";
	if($_POST["accountUID"] != ""){
		$accountname = "";
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "select UID, Username from manager.dbo.account where UID = '".$accountUID."';" : (
			DB_IS_MYSQL  ? "select UID, Username from manager.account where UID = '".$accountUID."';" : (
			DB_IS_ORACLE ? "select \"UID\", Username from manager.\"ACCOUNT\" where \"UID\" = '".$accountUID."';" : null)));
		$stmt->execute();
		While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
			$accountname = $obj["Username"];
		}
		$stmt->closeCursor();
	}
	else
		$accountname = $_SESSION["username"];
	
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "Delete FROM ".$accountname.".dbo.report_style Where ID='".$sampleUID."';" : (
		DB_IS_MYSQL  ? "Delete FROM ".$accountname.".report_style Where ID='".$sampleUID."';" : (
		DB_IS_ORACLE ? "Delete FROM ".$accountname.".report_style Where \"ID\"='".$sampleUID."';" : null)));
	$stmt->execute();
	$stmt->closeCursor();
	
	$conn = null;
}

function InsertTemplateList(){
	global $login;
	if($login == false){
		trap("Permission error!");
	}
	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
	
	if($_POST["accountUID"] != ""){
		$accountname = "";
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "select UID, Username from manager.dbo.account where UID = '".$accountUID."';" : (
			DB_IS_MYSQL  ? "select UID, Username from manager.account where UID = '".$accountUID."';" : (
			DB_IS_ORACLE ? "select \"UID\", Username from manager.\"ACCOUNT\" where \"UID\" = '".$accountUID."';" : null)));
		$stmt->execute();
		While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
			$accountname = $obj["Username"];
		}
		$stmt->closeCursor();
	}
	else
		$accountname = $_SESSION["username"];
	
	
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "TRUNCATE TABLE ".$accountname.".dbo.report_template;" : (
		DB_IS_MYSQL  ? "TRUNCATE TABLE ".$accountname.".report_template;" : (
		DB_IS_ORACLE ? "TRUNCATE TABLE ".$accountname.".report_template;" : null)));
	$stmt->execute();
	
	$template_list = $_POST["Template_obj"];
	for($template_idx=0; $template_idx<count($template_list); $template_idx++){
		$name = $template_list[$template_idx]["name"];
		$header = $template_list[$template_idx]["header"];
		$footer = $template_list[$template_idx]["footer"];
		
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "INSERT INTO ".$accountname.".dbo.report_template (Name, ReportHeader, ReportFooter) VALUES (?,?,?);" : (
			DB_IS_MYSQL  ? "INSERT INTO ".$accountname.".report_template (Name, ReportHeader, ReportFooter) VALUES (?,?,?);" : (
			DB_IS_ORACLE ? "INSERT INTO ".$accountname.".report_template (\"NAME\", ReportHeader, ReportFooter) VALUES (?,?,?);" : null)));
		$stmt->execute(array($name, $header, $footer));
	}
	if(isset($stmt))
		$stmt->closeCursor();
	$conn = null;
}
?>