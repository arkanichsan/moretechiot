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

	function trap($outline, $detail = null){
		header("HTTP/1.0 500 Internal Server Error");
		echo $outline;
		if($detail != null){
			echo "\n" . $detail;
		}

		exit;
	}
	

	$filename = "event_list.csv";
	$f = fopen('php://memory', 'w'); 
	$string = "\xEF\xBB\xBF";
	

	if($login == false){
		trap("Permission error!");
	}
	
	
	$ErrorCodeArray = creat_ErrorCodeArray();
	$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
	$stmt = $conn->prepare(
		DB_IS_MSSQL  ? "select DateTime,EventCode,Parameters from ".$_SESSION["username"].".dbo.event_log order by DateTime desc, UID desc;" : (
		DB_IS_MYSQL  ? "select DateTime,EventCode,Parameters from ".$_SESSION["username"].".event_log order by DateTime desc, UID desc;" : (
		DB_IS_ORACLE ? "select DateTime,EventCode,\"PARAMETERS\" from ".$_SESSION["username"].".event_log order by DateTime desc, \"UID\" desc;" : null))
	);
	$stmt->execute();
	
	
	while($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
	
		$Error_Message = $ErrorCodeArray[$obj["EventCode"]];
		
		$Variable_array = explode("%variable",$Error_Message);
		$db_Variable_array = explode("|$|",($obj["Parameters"]));
		for($i = 0; $i < count($db_Variable_array); $i++){
			$Error_Message = str_replace("%variable".$i."%",$db_Variable_array[$i],$Error_Message);
		}
		
		$string .= utcToLocalTime(date_format(date_create($obj["DateTime"]), 'Y-m-d H:i:s')).",".$Error_Message.PHP_EOL;
	}
	
	fwrite($f, $string);

	fseek($f, 0);
	header('Content-Type: application/csv');
	header('Content-Disposition: attachement; filename="' . $filename . '"');
	fpassthru($f);
	
	$stmt->closeCursor();
	$conn = null;
	
	
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
	
	function utcToLocalTime($timeString){
		$date = date($timeString);
		$time = strtotime($date);
		$time = $time - ($_GET["utcdatetime"] * 60);
		$date = date("Y-m-d H:i:s", $time);
		return $date;
	}
	
?>