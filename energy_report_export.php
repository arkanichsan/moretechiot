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
	global $lang;
	

	
	if($_GET["isGroup"] != "true"){
		$accountUID = $_GET["accountUID"];
		$DeviceUID = $_GET["DeviceUID"];
		$moduleUID = $_GET["moduleUID"];
		$loop = $_GET["loop"];
	}
	else{
		$GroupUID = $_GET["GroupUID"];
	}

	
	$datetime_begin = $_GET["datetime_begin"];
	$datetime_end = $_GET["datetime_end"];
	$report_type = $_GET["report_type"];
	$sub_title = $_GET["sub_title"];
	$timezone = $_GET["timezone"];
	$format = $_GET["format"];
	$compare_time_format = $_GET["compare_time_format"];
	$compare_datetime_begin = $_GET["compare_datetime_begin"];
	$compare_datetime_end = $_GET["compare_datetime_end"];
	
	if(DB_IS_ORACLE){
		if (strpos($timezone, '+') !== false) 
			$timezone=str_replace('+','-',$timezone);
		else
			$timezone=str_replace('-','+',$timezone);
	}
	
	
	$report_type_title = "";
	
	$DateTime = "";
	$DateTimeTitle = "";
	$MaximumDemandTitle = "";
	$date = new DateTime($datetime_begin);
	$datetime_begin_fomat = new DateTime($datetime_begin);
	$datetime_end_fomat = new DateTime($datetime_end);
	
	if($report_type == "DAY"){$report_type_title = $lang['REPORT_SERVICE']['DAILY_REPORT']; $DateTime = $date->format('Y-m-d'); $DateTimeTitle = $lang['REPORT_SERVICE']['TIME']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['DAILY_MAXIMUM_DEMAND'];}
	if($report_type == "WEEK"){$report_type_title = $lang['REPORT_SERVICE']['WEEKLY_REPORT']; $DateTime = $date->format('Y-m-d'); $DateTimeTitle = $lang['REPORT_SERVICE']['TIME']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['WEEKLY_MAXIMUM_DEMAND'];}
	else if($report_type == "MONTH"){$report_type_title = $lang['REPORT_SERVICE']['MONTHLY_REPORT']; $DateTime = $date->format('Y-m'); $DateTimeTitle = $lang['REPORT_SERVICE']['DATE']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['MONTHLY_MAXIMUM_DEMAND'];}
	else if($report_type == "QUARTER"){$report_type_title = $lang['REPORT_SERVICE']['QUARTERLY_REPORT']; $DateTime = $date->format('m'); $DateTimeTitle = $lang['REPORT_SERVICE']['DATE']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['QUARTERLY_MAXIMUM_DEMAND'];}
	else if($report_type == "YEAR"){$report_type_title = $lang['REPORT_SERVICE']['ANNUAL_REPORT'];$DateTime = $date->format('Y'); $DateTimeTitle = $lang['REPORT_SERVICE']['MONTH']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['ANNUAL_MAXIMUM_DEMAND'];}
	
	$header_str = "";
	$footer_str = "";
	excel_template($header_str, $footer_str);
	
	if($_GET["isGroup"] != "true"){
		if($compare_time_format == "0"){
			$excel_body .= read_report_data($MaximumDemandTitle, $header_str, $footer_str);
		}
		else{
			$excel_body .= read_report_data_compare($header_str, $footer_str);
		}
	}
	else{
		if($format == "1"){
			$excel_body .= read_report_data_group($header_str, $footer_str);
		}
		else if($format == "2"){
			$excel_body .= read_report_data_group_f2($header_str, $footer_str);
		}
	}
	
	//return;
	if($report_type == "WEEK"){
		$filename = str_replace ("/","_",$_GET["sub_title"])."_".$datetime_begin_fomat->format('Y-m-d').'~'.$datetime_end_fomat->format('Y-m-d')."_".$lang['REPORT_SERVICE']['WEEKLY_REPORT'];
	}
	else if($report_type == "QUARTER"){
		$filename = str_replace ("/","_",$_GET["sub_title"])."_".$date->format('Y')." Q".(floor($datetime_begin_fomat->format('m')/3)+1)."_".$report_type_title;
	}
	else{
		$filename = str_replace ("/","_",$_GET["sub_title"])."_".$DateTime."_".$report_type_title;
	}

	
	$img_b64 = array();
	$img_code = array();
	$img_datatype = array();
	$p_align = array();
	
	
	$excel_head = "<head><style type='text/css'>body{font-family:'Times New Roman', 'Tahoma', 'Arial';}table td{height:30px;}.odd, .even{color:#3A4453;text-align:center;border: 1px solid #C4C4C4;}.header{border-right: 1px solid #C4C4C4;background-color: #3B3B3B;color:#FFFFFF;font-weight:bold;text-align:center;width:200px;height:60px;}.title{height:40px;font-size:23px;text-align:center;vertical-align:bottom;}.date{font-size:16px;vertical-align:bottom;}.totalHeader{background-color: #3B3B3B;color:#FFFFFF;font-weight:bold;text-align:center;border: 1px solid #C4C4C4;}.totalValue{text-align:left;}</style></head>";
	$table_str .= "<body><table>";
	$table_str .= $excel_body."\r\n";//table內容
	$table_str .= "</table></body>";
	$table_str = $excel_head . $table_str;
	
	$doc = DOMDocument::loadHTML(mb_convert_encoding($table_str,"HTML-ENTITIES","UTF-8"));
	$xpath = new DOMXPath($doc);
	$query = "//img";
	$entries = $xpath->query($query);
	$img_idx = 0;
	foreach ($entries as $entry) {//讀出
		$img_src = $entry->getAttribute("src");
		array_push($img_b64,explode(',',$img_src)[1]);
		array_push($img_code,explode(';',explode(',',$img_src)[0])[1]);//base64
		array_push($img_datatype,explode(':',explode(';',explode(',',$img_src)[0])[0])[1]);//image/png
		
		//array_push($img_b64,str_replace("data:image/png;base64,","",$entry->getAttribute("src")));
	}
	
	foreach ($entries as $entry) {//寫入
		$entry->setAttribute("src", "cid:image".$img_idx);
		$img_idx += 1;
	}
	
	$query = "//p";
	$entries = $xpath->query($query);
	foreach ($entries as $entry) {//讀出
		if(strpos($entry->getAttribute("style"),"text-align") === false){
			$entry->setAttribute("style",$entry->getAttribute("style")."text-align: left;");
		}
	}
	
	$table_str = $doc ->saveHTML();
	
	
	//製作img宣告
	$excel_declaration = "";
	$excel_declaration_end = "\r\n--12345--";
	
	
	$excel_declaration .= "MIME-Version: 1.0 \r\n";
	$excel_declaration .= 'Content-Type: multipart/mixed; boundary="12345"'."\r\n";
	$excel_declaration .= "\r\n";
	
	for($img_idx=0; $img_idx<count($img_b64); $img_idx++){
		$excel_declaration .= "--12345\r\n";
		$excel_declaration .= "Content-Type: ".$img_datatype[$img_idx]."\r\n";
		$excel_declaration .= "Content-Transfer-Encoding: ".$img_code[$img_idx]."\r\n";
		$excel_declaration .= "Content-ID: image".$img_idx."\r\n";
		$excel_declaration .= "\r\n";
		$excel_declaration .= $img_b64[$img_idx]."\r\n";//圖base64
	}
	$excel_declaration .= "--12345\r\n";
	$excel_declaration .= 'Content-Type: text/html; charset="utf-8"'."\r\n";
	$excel_declaration .= "\r\n";
	
	$report_str = "\xEF\xBB\xBF";
	$report_str .= $excel_declaration.$table_str.$excel_declaration_end;
	
	$f = fopen('php://memory', 'w');
	fwrite($f, $report_str);
	fseek($f, 0);
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	
	if($_GET["isGroup"] != "true"){
		if($compare_time_format == "0")
			header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		else
			header('Content-Disposition: attachment;filename="'.$filename.'_com.xls"');
	}
	else{
		if($format == "1"){
			header('Content-Disposition: attachment;filename="'.$filename.'_LoopStatistics.xls"');
		}
		else if($format == "2"){
			header('Content-Disposition: attachment;filename="'.$filename.'_LoopComparison_'.$_GET["col_text"].'.xls"');
		}
	}
	header('Cache-Control: max-age=0');
	fpassthru($f);
	
	
	
	
	function read_report_data($MaximumDemandTitle,$header_str,$footer_str){
		global $lang;
		global $login;
		if($login == false){
			trap("Permission error!");
		}
		$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		
		$report_Data_string = "";
		$accountUID = $_GET["accountUID"];
		$DeviceUID = $_GET["DeviceUID"];
		$moduleUID = $_GET["moduleUID"];
		$Loop = $_GET["loop"];
		$datetime_begin = $_GET["datetime_begin"];
		$datetime_end = $_GET["datetime_end"];
		$report_type = $_GET["report_type"];
		$sub_title = $_GET["sub_title"];
		$timezone = $_GET["timezone"];
		$template_id = $_GET["template_id"];
		$col_count = 0;
		
		if(DB_IS_ORACLE){
			if (strpos($timezone, '+') !== false) 
				$timezone=str_replace('+','-',$timezone);
			else
				$timezone=str_replace('-','+',$timezone);
		}
		
		//account
		if($_GET["accountUID"] != "undefined"){
			$accountname = "";
			$stmt = $conn->prepare(
				DB_IS_MSSQL  ? "select UID, Username from manager.dbo.account where UID = '".$accountUID."';" : (
				DB_IS_MYSQL  ? "select UID, Username from manager.account where UID = '".$accountUID."';" : (
				DB_IS_ORACLE ? "select UID, Username from manager.\"ACCOUNT\" where \"UID\" = '".$accountUID."';" : null))
			);
			$stmt->execute();
			While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
				$accountname = $obj["Username"];
			}
		}
		else
			$accountname = $_SESSION["username"];
		
		$stmt->closeCursor();
		
		$report_type_title = "";
		$DateTime = "";
		$DateTimeTitle = "";
		$MaximumDemandTitle = "";
		$date = new DateTime($datetime_begin);
		$datetime_begin_fomat = new DateTime($datetime_begin);
		$datetime_end_fomat = new DateTime($datetime_end);
		
		if($report_type == "DAY"){$report_type_title = $lang['REPORT_SERVICE']['DAILY_REPORT']; $DateTime = $date->format('Y-m-d'); $DateTimeTitle = $lang['REPORT_SERVICE']['TIME']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['DAILY_MAXIMUM_DEMAND'];}
		if($report_type == "WEEK"){$report_type_title = $lang['REPORT_SERVICE']['WEEKLY_REPORT']; $DateTime = $date->format('Y-m-d'); $DateTimeTitle = $lang['REPORT_SERVICE']['TIME']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['WEEKLY_MAXIMUM_DEMAND'];}
		else if($report_type == "MONTH"){$report_type_title = $lang['REPORT_SERVICE']['MONTHLY_REPORT']; $DateTime = $date->format('Y-m'); $DateTimeTitle = $lang['REPORT_SERVICE']['DATE']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['MONTHLY_MAXIMUM_DEMAND'];}
		else if($report_type == "QUARTER"){$report_type_title = $lang['REPORT_SERVICE']['QUARTERLY_REPORT']; $DateTime = $date->format('m'); $DateTimeTitle = $lang['REPORT_SERVICE']['DATE']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['QUARTERLY_MAXIMUM_DEMAND'];}
		else if($report_type == "YEAR"){$report_type_title = $lang['REPORT_SERVICE']['ANNUAL_REPORT'];$DateTime = $date->format('Y'); $DateTimeTitle = $lang['REPORT_SERVICE']['MONTH']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['ANNUAL_MAXIMUM_DEMAND'];}
			
		//單向 or 三相電錶
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "select phase from ".$accountname.".dbo.Module where DeviceUID = '".$DeviceUID."' and UID='".$moduleUID."'" : (
			DB_IS_MYSQL  ? "select phase from ".$accountname.".Module where DeviceUID = '".$DeviceUID."' and UID='".$moduleUID."'" : (
			DB_IS_ORACLE ? "select phase from ".$accountname.".Module where DeviceUID = '".$DeviceUID."' and \"UID\"='".$moduleUID."'" : null))
		);
		$stmt->execute();

		$phase = 1;
		While($ckphase_obj = $stmt->fetch(PDO::FETCH_ASSOC)){
			if($ckphase_obj["phase"] == "1")
				$phase = 1;
			else
				$phase = 4;
		}
		$stmt->closeCursor();
		
		$DateTimeTitle = "";
		$table_time = "";
		if($report_type =="DAY"){
			$table_time = "HOUR";
			$DateTimeTitle = $lang['REPORT_SERVICE']['TIME'];
		}
		else if ($report_type =="WEEK")	{
			$table_time = "WEEKDAY";
			$DateTimeTitle = $lang['REPORT_SERVICE']['DATE'];
		}
		else if($report_type =="MONTH"){
			$table_time = "DAY";
			$DateTimeTitle = $lang['REPORT_SERVICE']['DATE'];
		}
		else if($report_type =="YEAR"||$report_type =="QUARTER"){
			$table_time = "MONTH";
			$DateTimeTitle = $lang['REPORT_SERVICE']['MONTH_TITLE'];
		}
		$sql = "";
		
		$col_count = $_GET["col_count"]*1+1;
		$ck_array = explode(",", $_GET["ck_array"]);
		
		if($phase == 1){
			$col_count = $_GET["col_count"]*1+1;
			//header
			$report_Data_string .= "<tr>";
			if($report_type == "WEEK"){
				$report_Data_string .= '<td class="date" colspan="1">'.$lang['REPORT_SERVICE']['REPORT_DATE'].': '.$datetime_begin_fomat->format('Y-m-d').' ~ '.$datetime_end_fomat->format('Y-m-d').'</td>';
			}
			
			else if($report_type == "QUARTER"){
				$report_Data_string .= '<td class="date" colspan="1">'.$lang['REPORT_SERVICE']['REPORT_DATE'].': '.$datetime_begin_fomat->format('Y')." Q".(floor($datetime_begin_fomat->format('m')/3)+1).'</td>';
			}
			else
				$report_Data_string .= '<td class="date" colspan="1">'.$lang['REPORT_SERVICE']['REPORT_DATE'].': '.$DateTime.'</td>';
			if(($col_count-2)<=0)
				$report_Data_string .= '<td class="title" colspan="1">'.$sub_title.'  -  '.$report_type_title.'</td>';
			else
				$report_Data_string .= '<td class="title" colspan="'.($col_count-2).'">'.$sub_title.'  -  '.$report_type_title.'</td>';
			$report_Data_string .= '<td class="date" colspan="1" style="text-align:right;">'.$lang['REPORT_SERVICE']['PRINT_DATE'].': '.date("Y-m-d").'</td>';
			$report_Data_string .= "</tr>";
			$report_Data_string .= '<tr>';
			
			$title=[$lang['REPORT_SERVICE']['MAX_DEMAND'],$lang['REPORT_SERVICE']['KWH_TITLE'],$lang['REPORT_SERVICE']['PF'],$lang['REPORT_SERVICE']['I'],$lang['REPORT_SERVICE']['V'],$lang['REPORT_SERVICE']['KVA'],$lang['REPORT_SERVICE']['KVAR']];
			$report_Data_string .= '<td class="header">'.$DateTimeTitle.'</td>';
			for($i=0; $i<Count($title); $i++){
				if($ck_array[$i]=="true")
					$report_Data_string .= '<td class="header">'.$title[$i].'</td>';
			}
			$report_Data_string .= '</tr>';
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
			//header template
			$report_Data_string .= "<tr>";
			if($report_type == "WEEK"){
				$report_Data_string .= '<td class="date" colspan="1">'.$lang['REPORT_SERVICE']['REPORT_DATE'].': '.$datetime_begin_fomat->format('Y-m-d').' ~ '.$datetime_end_fomat->format('Y-m-d').'</td>';
			}
			else if($report_type == "QUARTER"){
				$report_Data_string .= '<td class="date" colspan="1">'.$lang['REPORT_SERVICE']['REPORT_DATE'].': '.$date->format('Y')." Q".(floor($date->format('m')/3)+1).'</td>';
			}
			else
				$report_Data_string .= '<td class="date" colspan="1">'.$lang['REPORT_SERVICE']['REPORT_DATE'].': '.$DateTime.'</td>';
			
			if(($col_count-2)<=0)
				$report_Data_string .= '<td class="title" colspan="1">'.$sub_title.'  -  '.$report_type_title.'</td>';
			else
				$report_Data_string .= '<td class="title" colspan="'.($col_count-2).'">'.$sub_title.'  -  '.$report_type_title.'</td>';
			
			$report_Data_string .= '<td class="date" colspan="1" style="text-align:right;">'.$lang['REPORT_SERVICE']['PRINT_DATE'].': '.date("Y-m-d").'</td>';
			$report_Data_string .= "</tr>";
			
			$report_Data_string .= '<tr>';
			
			$report_Data_string .= '<td class="header">'.$DateTimeTitle.'</td>';
			
			$title=[$lang['REPORT_SERVICE']['MAX_DEMAND'],$lang['REPORT_SERVICE']['KWH_TITLE'],$lang['REPORT_SERVICE']['PF'],$lang['REPORT_SERVICE']['I_A'],$lang['REPORT_SERVICE']['V_A'],$lang['REPORT_SERVICE']['I_B'],$lang['REPORT_SERVICE']['V_B'],$lang['REPORT_SERVICE']['I_C'],$lang['REPORT_SERVICE']['V_C'],$lang['REPORT_SERVICE']['KVA'],$lang['REPORT_SERVICE']['KVAR']];
			
			for($i=0; $i<Count($title); $i++){
				if($ck_array[$i]=="true")
					$report_Data_string .= '<td class="header">'.$title[$i].'</td>';
			}
			
			$report_Data_string .= '</tr>';
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
		
		$stmt_maxdemand = $conn->prepare($sql);
		$stmt_maxdemand->execute();
		
		$total_kwh = 0;
		$td_class = "odd";
		While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
			$report_Data_string.='<tr>';
			if($report_type == "QUARTER"||$report_type == "YEAR"){
				switch($obj["Time"]) {
					case "1": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['JAN'].'</td>';break;
					case "2": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['FEB'].'</td>';break;
					case "3": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['MAR'].'</td>';break;
					case "4": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['APR'].'</td>';break;
					case "5": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['MAY'].'</td>';break;
					case "6": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['JUN'].'</td>';break;
					case "7": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['JUL'].'</td>';break;
					case "8": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['AUG'].'</td>';break;
					case "9": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['SEP'].'</td>';break;
					case "10":$report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['OCT'].'</td>';break;
					case "11":$report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['NOV'].'</td>';break;
					case "12":$report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['DEC'].'</td>';break;
				}
			}
			else if($report_type == "WEEK"){
				switch($obj["Time"]) {
					case "1": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['HISTORY_ENERGY']['SUN'].'</td>';break;
					case "2": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['HISTORY_ENERGY']['MON'].'</td>';break;
					case "3": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['HISTORY_ENERGY']['TUE'].'</td>';break;
					case "4": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['HISTORY_ENERGY']['WED'].'</td>';break;
					case "5": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['HISTORY_ENERGY']['THU'].'</td>';break;
					case "6": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['HISTORY_ENERGY']['FRI'].'</td>';break;
					case "7": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['HISTORY_ENERGY']['SAT'].'</td>';break;
				}
			}
			else{
				$report_Data_string .= '<td class="'.$td_class.'">'.$obj["Time"].'</td>';
			}
			
			$col_val = [];
			if($phase == 1){
				$col_val = [floor_dec($obj["Demand"], 3),floor_dec($obj["kWh"], 3),floor_dec($obj["PF"], 3),floor_dec($obj["I"], 3),floor_dec($obj["V"], 3),floor_dec($obj["kVA"], 3),floor_dec($obj["kvar"], 3)];
				
			}
			else{
				$col_val = [floor_dec($obj["Demand"], 3),floor_dec($obj["kWh"], 3),floor_dec(($obj["PF"])*100, 3),floor_dec($obj["I_A"], 3),floor_dec($obj["V_A"], 3),floor_dec($obj["I_B"], 3),floor_dec($obj["V_B"], 3),floor_dec($obj["I_C"], 3),floor_dec($obj["V_C"], 3),floor_dec($obj["kVA"], 3),floor_dec($obj["kvar"], 3)];
			}
			
			for($i=0; $i<Count($col_val); $i++){
				if($ck_array[$i]=="true")
					$report_Data_string .= '<td class="'.$td_class.'">'.$col_val[$i].'</td>';
			}
			$report_Data_string .= '</tr>';
				
			$total_kwh += $obj["kWh"];
		}
		$report_Data_string.='<tr></tr>';
		
		While($obj = $stmt_maxdemand->fetch(PDO::FETCH_ASSOC)){
			if($phase == 1){
				$report_Data_string .= '<tr>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['TOTAL'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="background-color: #F5F5F5;"> '.floor_dec($total_kwh,3).$lang['REPORT_SERVICE']['KWH'].' </td>';
				$report_Data_string .= '</tr>';
				$report_Data_string .= '<tr>';
				$report_Data_string .= '<td class="totalHeader">'.$MaximumDemandTitle.'</td>';
				$report_Data_string .= '<td class="totalValue" style="background-color: #F5F5F5;"> '.floor_dec($obj["Demand"],3).' kW</td>';
				$report_Data_string .= '</tr>';
				$report_Data_string .= '<tr>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['EVEN_TIME'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="background-color: #F5F5F5;"> '.str_replace ("/","-",$obj["DateTime"]).'&nbsp;</td>';
				$report_Data_string .= '</tr>';
			}
			else{
				$report_Data_string .= '<tr>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['TOTAL'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="background-color: #F5F5F5;"> '.floor_dec($total_kwh,3).$lang['REPORT_SERVICE']['KWH'].' </td>';
				$report_Data_string .= '</tr>';
				$report_Data_string .= '<tr>';
				$report_Data_string .= '<td class="totalHeader">'.$MaximumDemandTitle.'</td>';
				$report_Data_string .= '<td class="totalValue" style="background-color: #F5F5F5;"> '.floor_dec($obj["Demand"],3).' kW</td>';
				$report_Data_string .= '</tr>';
				$report_Data_string .= '<tr>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['EVEN_TIME'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="background-color: #F5F5F5;"> '.str_replace ("/","-",$obj["DateTime"]).'&nbsp;</td>';
				$report_Data_string .= '</tr>';
			}
			break;
		}
		//footer template
		//header template
		$header_html="";
		$footer_html="";
		
		if($header_str!=""){
			$header_html .= "<tr>";
			$header_html .= "<td colspan='".$col_count."'>".$header_str."</td>";
			$header_html .= "</tr>";
		}

		if($footer_str!=""){
			$footer_html .= "<tr>";
			$footer_html .= "<td colspan='".$col_count."'>".$footer_str."</td>";
			$footer_html .= "</tr>";
		}
		
		$report_Data_string = $header_html.$report_Data_string.$footer_html;
		
		
		if(isset($stmt))
			$stmt->closeCursor();
		if(isset($stmt_maxdemand))
			$stmt_maxdemand->closeCursor();
		
		$conn = null;
		return $report_Data_string;
	}
	
	function read_report_data_compare($header_str, $footer_str){
		global $lang;
		global $login;
		if($login == false){
			trap("Permission error!");
		}
		$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		
		$report_Data_string = "";
		$accountUID = $_GET["accountUID"];
		$DeviceUID = $_GET["DeviceUID"];
		$moduleUID = $_GET["moduleUID"];
		$Loop = $_GET["loop"];
		$datetime_begin = $_GET["datetime_begin"];
		$datetime_end = $_GET["datetime_end"];
		$report_type = $_GET["report_type"];
		$sub_title = $_GET["sub_title"];
		$timezone = $_GET["timezone"];
		$template_id = $_GET["template_id"];
		$compare_datetime_begin = $_GET["compare_datetime_begin"];
		$compare_datetime_end = $_GET["compare_datetime_end"];
		
		if(DB_IS_ORACLE){
			if (strpos($timezone, '+') !== false) 
				$timezone=str_replace('+','-',$timezone);
			else
				$timezone=str_replace('-','+',$timezone);
		}
		
		
		//account
		if($_GET["accountUID"] != "undefined"){
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
		
		
		$report_type_title = "";
		$DateTime = "";
		$DateTimeTitle = "";
		$MaximumDemandTitle = "";
		$datetime_begin_fomat = new DateTime($datetime_begin);
		$datetime_end_fomat = new DateTime($datetime_end);
		$compare_datetime_begin_fomat = new DateTime($compare_datetime_begin);
		$compare_datetime_end_fomat = new DateTime($compare_datetime_end);
		
		if($report_type == "DAY"){$report_type_title = $lang['REPORT_SERVICE']['DAILY_REPORT']; $DateTime = $datetime_begin_fomat->format('Y-m-d'); $DateTimeTitle = $lang['REPORT_SERVICE']['TIME']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['DAILY_MAXIMUM_DEMAND'];}
		if($report_type == "WEEK"){$report_type_title = $lang['REPORT_SERVICE']['WEEKLY_REPORT']; $DateTime = $datetime_begin_fomat->format('Y-m-d'); $DateTimeTitle = $lang['REPORT_SERVICE']['TIME']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['WEEKLY_MAXIMUM_DEMAND'];}
		else if($report_type == "MONTH"){$report_type_title = $lang['REPORT_SERVICE']['MONTHLY_REPORT']; $DateTime = $datetime_begin_fomat->format('Y-m'); $DateTimeTitle = $lang['REPORT_SERVICE']['DATE']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['MONTHLY_MAXIMUM_DEMAND'];}
		else if($report_type == "QUARTER"){$report_type_title = $lang['REPORT_SERVICE']['QUARTERLY_REPORT']; $DateTime = $datetime_begin_fomat->format('m'); $DateTimeTitle = $lang['REPORT_SERVICE']['DATE']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['QUARTERLY_MAXIMUM_DEMAND'];}
		else if($report_type == "YEAR"){$report_type_title = $lang['REPORT_SERVICE']['ANNUAL_REPORT'];$DateTime = $datetime_begin_fomat->format('Y'); $DateTimeTitle = $lang['REPORT_SERVICE']['MONTH']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['ANNUAL_MAXIMUM_DEMAND'];}
		

		
		
		//單向 or 三相電錶
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "select phase from ".$accountname.".dbo.Module where DeviceUID = '".$DeviceUID."' and UID='".$moduleUID."'" : (
			DB_IS_MYSQL  ? "select phase from ".$accountname.".Module where DeviceUID = '".$DeviceUID."' and UID='".$moduleUID."'" : (
			DB_IS_ORACLE ? "select phase from ".$accountname.".Module where DeviceUID = '".$DeviceUID."' and \"UID\"='".$moduleUID."'" : null))
		);
		$stmt->execute();

		$phase = 1;
		While($ckphase_obj = $stmt->fetch(PDO::FETCH_ASSOC)){
			
			if($ckphase_obj["phase"] == "1")
				$phase = 1;
			else
				$phase = 4;
		}
		$DateTimeTitle = "";
		
		$table_time = "";
		if($report_type =="DAY"){
			$table_time = "HOUR";
			$DateTimeTitle = $lang['REPORT_SERVICE']['TIME'];
		}
		else if ($report_type =="WEEK")	{
			$table_time = "WEEKDAY";
			$DateTimeTitle = $lang['REPORT_SERVICE']['DATE'];
		}
		else if($report_type =="MONTH"){
			$table_time = "DAY";
			$DateTimeTitle = $lang['REPORT_SERVICE']['DATE'];
		}
		else if($report_type =="YEAR"||$report_type =="QUARTER"){
			$table_time = "MONTH";
			$DateTimeTitle = $lang['REPORT_SERVICE']['MONTH_TITLE'];
		}
		$sql = "";
		
		$col_count = $_GET["col_count"]*1+1;
		$ck_array = explode(",", $_GET["ck_array"]);
		if($phase == 1){
			//header template
			$report_Data_string .= "<tr>";
			$report_Data_string .= '<td class="date" colspan="1">';
			if($report_type == "WEEK"){
				$report_Data_string .= $lang['REPORT_SERVICE']['REPORT_DATE'].': '.$datetime_begin_fomat->format('Y-m-d').' ~ '.$datetime_end_fomat->format('Y-m-d');
				$report_Data_string .= '<br/>';
				$report_Data_string .= $lang['REPORT_SERVICE']['COMPARE_DATE'].': '.$compare_datetime_begin_fomat->format('Y-m-d').' ~ '.$compare_datetime_end_fomat->format('Y-m-d');
			}
			
			else if($report_type == "QUARTER"){
				$report_Data_string .= $lang['REPORT_SERVICE']['REPORT_DATE'].': '.$datetime_begin_fomat->format('Y')." Q".(floor($datetime_begin_fomat->format('m')/3)+1);
				$report_Data_string .= '<br/>';
				$report_Data_string .= $lang['REPORT_SERVICE']['COMPARE_DATE'].': '.$compare_datetime_begin_fomat->format('Y')." Q".(floor($compare_datetime_begin_fomat->format('m')/3)+1);
			}
			else{
				$report_Data_string .= $lang['REPORT_SERVICE']['REPORT_DATE'].': '.$DateTime;
				$report_Data_string .= '<br/>';
				$report_Data_string .= $lang['REPORT_SERVICE']['COMPARE_DATE'].': '.$compare_datetime_begin_fomat->format('Y-m-d');
			}
			$report_Data_string .= '</td>';
			if(($col_count-2)<=0)
				$report_Data_string .= '<td class="title" colspan="1">'.$sub_title.'  -  '.$report_type_title.'</td>';
			else
				$report_Data_string .= '<td class="title" colspan="'.($col_count-2).'">'.$sub_title.'  -  '.$report_type_title.'</td>';
			$report_Data_string .= '<td class="date" colspan="1" style="text-align:right;">'.$lang['REPORT_SERVICE']['PRINT_DATE'].': '.date("Y-m-d").'</td>';
			$report_Data_string .= "</tr>";
			$report_Data_string .= '<tr>';
			
			//$title=[$lang['REPORT_SERVICE']['MAX_DEMAND'],$lang['REPORT_SERVICE']['MAX_DEMAND'],$lang['REPORT_SERVICE']['KWH_TITLE'],$lang['REPORT_SERVICE']['KWH_TITLE'],$lang['REPORT_SERVICE']['PF'],$lang['REPORT_SERVICE']['PF'],$lang['REPORT_SERVICE']['I'],$lang['REPORT_SERVICE']['I'],$lang['REPORT_SERVICE']['V'],$lang['REPORT_SERVICE']['V'],$lang['REPORT_SERVICE']['KVA'],$lang['REPORT_SERVICE']['KVA'],$lang['REPORT_SERVICE']['KVAR'],$lang['REPORT_SERVICE']['KVAR']];
			$title=[$lang['REPORT_SERVICE']['MAX_DEMAND'],$lang['REPORT_SERVICE']['KWH_TITLE'],$lang['REPORT_SERVICE']['PF'],$lang['REPORT_SERVICE']['I'],$lang['REPORT_SERVICE']['V'],$lang['REPORT_SERVICE']['KVA'],$lang['REPORT_SERVICE']['KVAR']];
			$report_Data_string .= '<td class="header">'.$DateTimeTitle.'</td>';
			for($i=0; $i<Count($title); $i++){
				if($ck_array[$i*2]=="true")
					$report_Data_string .= '<td class="header" colspan="2">'.$title[$i].'</td>';
			}
			$report_Data_string .= '</tr>';
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
			//header template
			
			$report_Data_string .= "<tr>";
			$report_Data_string .= '<td class="date" colspan="1">';
			if($report_type == "WEEK"){
				$report_Data_string .= $lang['REPORT_SERVICE']['REPORT_DATE'].': '.$datetime_begin_fomat->format('Y-m-d').' ~ '.$datetime_end_fomat->format('Y-m-d');
				$report_Data_string .= '<br/>';
				$report_Data_string .= $lang['REPORT_SERVICE']['COMPARE_DATE'].': '.$compare_datetime_begin_fomat->format('Y-m-d').' ~ '.$compare_datetime_end_fomat->format('Y-m-d');
			}
			else if($report_type == "QUARTER"){
				$report_Data_string .= $lang['REPORT_SERVICE']['REPORT_DATE'].': '.$datetime_begin_fomat->format('Y')." Q".(floor($datetime_begin_fomat->format('m')/3)+1);
				$report_Data_string .= '<br/>';
				$report_Data_string .= $lang['REPORT_SERVICE']['COMPARE_DATE'].': '.$compare_datetime_begin_fomat->format('Y')." Q".(floor($compare_datetime_begin_fomat->format('m')/3)+1);
			}
			else{
				$report_Data_string .= $lang['REPORT_SERVICE']['REPORT_DATE'].': '.$DateTime;
				$report_Data_string .= '<br/>';
				$report_Data_string .= $lang['REPORT_SERVICE']['COMPARE_DATE'].': '.$compare_datetime_begin_fomat->format('Y-m-d');
			}
			$report_Data_string .= '</td>';
			$report_Data_string .= '<td class="title" colspan="'.($col_count-2).'">'.$sub_title.'  -  '.$report_type_title.'</td>';
			$report_Data_string .= '<td class="date" colspan="1" style="text-align:right;">'.$lang['REPORT_SERVICE']['PRINT_DATE'].': '.date("Y-m-d").'</td>';
			$report_Data_string .= "</tr>";
			
			$report_Data_string .= '<tr>';
			$report_Data_string .= '<td class="header">'.$DateTimeTitle.'</td>';

			//$title=[$lang['REPORT_SERVICE']['MAX_DEMAND'],$lang['REPORT_SERVICE']['MAX_DEMAND'],$lang['REPORT_SERVICE']['KWH_TITLE'],$lang['REPORT_SERVICE']['KWH_TITLE'],$lang['REPORT_SERVICE']['PF'],$lang['REPORT_SERVICE']['PF'],$lang['REPORT_SERVICE']['I_A'],$lang['REPORT_SERVICE']['I_A'],$lang['REPORT_SERVICE']['V_A'],$lang['REPORT_SERVICE']['V_A'],$lang['REPORT_SERVICE']['I_B'],$lang['REPORT_SERVICE']['I_B'],$lang['REPORT_SERVICE']['V_B'],$lang['REPORT_SERVICE']['V_B'],$lang['REPORT_SERVICE']['I_C'],$lang['REPORT_SERVICE']['I_C'],$lang['REPORT_SERVICE']['V_C'],$lang['REPORT_SERVICE']['V_C'],$lang['REPORT_SERVICE']['KVA_3'],$lang['REPORT_SERVICE']['KVA_3'],$lang['REPORT_SERVICE']['KVAR_3'],$lang['REPORT_SERVICE']['KVAR_3']];
			$title=[$lang['REPORT_SERVICE']['MAX_DEMAND'],$lang['REPORT_SERVICE']['KWH_TITLE'],$lang['REPORT_SERVICE']['PF'],$lang['REPORT_SERVICE']['I_A'],$lang['REPORT_SERVICE']['V_A'],$lang['REPORT_SERVICE']['I_B'],$lang['REPORT_SERVICE']['V_B'],$lang['REPORT_SERVICE']['I_C'],$lang['REPORT_SERVICE']['V_C'],$lang['REPORT_SERVICE']['KVA'],$lang['REPORT_SERVICE']['KVAR']];
			for($i=0; $i<Count($title); $i++){
				if($ck_array[$i*2]=="true")
					$report_Data_string .= '<td class="header" colspan="2">'.$title[$i].'</td>';
			}
			$report_Data_string .= '</tr>';
			
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
		
		$td_class = "odd";
		While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
			$report_Data_string.='<tr>';
			if($report_type == "QUARTER"||$report_type == "YEAR"){
				switch($obj["Time"]) {
					case "1": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['JAN'].'</td>';break;
					case "2": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['FEB'].'</td>';break;
					case "3": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['MAR'].'</td>';break;
					case "4": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['APR'].'</td>';break;
					case "5": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['MAY'].'</td>';break;
					case "6": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['JUN'].'</td>';break;
					case "7": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['JUL'].'</td>';break;
					case "8": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['AUG'].'</td>';break;
					case "9": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['SEP'].'</td>';break;
					case "10":$report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['OCT'].'</td>';break;
					case "11":$report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['NOV'].'</td>';break;
					case "12":$report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['DEC'].'</td>';break;
				}
			}			
			else if($report_type == "WEEK"){
				switch($obj["Time"]) {
					case "1": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['HISTORY_ENERGY']['SUN'].'</td>';break;
					case "2": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['HISTORY_ENERGY']['MON'].'</td>';break;
					case "3": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['HISTORY_ENERGY']['TUE'].'</td>';break;
					case "4": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['HISTORY_ENERGY']['WED'].'</td>';break;
					case "5": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['HISTORY_ENERGY']['THU'].'</td>';break;
					case "6": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['HISTORY_ENERGY']['FRI'].'</td>';break;
					case "7": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['HISTORY_ENERGY']['SAT'].'</td>';break;
				}
			}			
			else{
				$report_Data_string .= '<td class="'.$td_class.'">'.$obj["Time"].'</td>';
			}
			$col_val = [];
			if($phase == 1){
				$col_val = [floor_dec($obj["Demand"], 3),floor_dec($obj["c_Demand"], 3),floor_dec($obj["kWh"], 3),floor_dec($obj["c_kWh"], 3),floor_dec($obj["PF"], 3),floor_dec($obj["c_PF"], 3),floor_dec($obj["I"], 3),floor_dec($obj["c_I"], 3),floor_dec($obj["V"], 3),floor_dec($obj["c_V"], 3),floor_dec($obj["kVA"], 3),floor_dec($obj["c_kVA"], 3),floor_dec($obj["kvar"], 3),floor_dec($obj["c_kvar"], 3)];
			}
			else{
				$PF = "";
				$c_PF = "";
				if(($obj["PF"])===NULL)
					$PF = "";
				else
					$PF =floor_dec(($obj["PF"])*100, 3);
				
				if(($obj["c_PF"])===NULL)
					$c_PF = "";
				else
					$c_PF =floor_dec(($obj["c_PF"])*100, 3);
				
				
				$col_val = [floor_dec($obj["Demand"], 3),floor_dec($obj["c_Demand"], 3),floor_dec($obj["kWh"], 3),floor_dec($obj["c_kWh"], 3),$PF,$c_PF,floor_dec($obj["I_A"], 3),floor_dec($obj["c_I_A"], 3),floor_dec($obj["V_A"], 3),floor_dec($obj["c_V_A"], 3),floor_dec($obj["I_B"], 3),floor_dec($obj["c_I_B"], 3),floor_dec($obj["V_B"], 3),floor_dec($obj["c_V_B"], 3),floor_dec($obj["I_C"], 3),floor_dec($obj["c_I_C"], 3),floor_dec($obj["V_C"], 3),floor_dec($obj["c_V_C"], 3),floor_dec($obj["kVA"], 3),floor_dec($obj["c_kVA"], 3),floor_dec($obj["kvar"], 3),floor_dec($obj["c_kvar"], 3)];
			}
			for($i=0; $i<Count($col_val); $i++){
				if($ck_array[$i]=="true")
					if($i%2 == 1)
						$report_Data_string .= '<td class="'.$td_class.'">'.$col_val[$i].'</td>';
					else
						$report_Data_string .= '<td class="'.$td_class.'" style="background-color: #F5F5F5;">'.$col_val[$i].'</td>';
			}
			$report_Data_string .= "</tr>";
		}
		
		$header_html="";
		$footer_html="";
		
		if($header_str!=""){
			$header_html .= "<tr>";
			$header_html .= "<td colspan='".$col_count."'>".$header_str."</td>";
			$header_html .= "</tr>";
		}

		if($footer_str!=""){
			$footer_html .= "<tr>";
			$footer_html .= "<td colspan='".$col_count."'>".$footer_str."</td>";
			$footer_html .= "</tr>";
		}
		
		$report_Data_string = $header_html.$report_Data_string.$footer_html;
		if(isset($stmt))
			$stmt->closeCursor();
				
		$conn = null;
		return $report_Data_string;
	}
	
	function read_report_data_group($header_str, $footer_str){
		
		global $lang;
		global $login;
		if($login == false){
			trap("Permission error!");
		}
		$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		
		$groupuid = $_GET["GroupUID"];
		$datetime_begin = $_GET["datetime_begin"];
		$datetime_end = $_GET["datetime_end"];
		$report_type = $_GET["report_type"];
		$sub_title = $_GET["sub_title"];
		$timezone = $_GET["timezone"];
		if(DB_IS_ORACLE){
			if (strpos($timezone, '+') !== false) 
				$timezone=str_replace('+','-',$timezone);
			else
				$timezone=str_replace('-','+',$timezone);
		}
			
		$sql_group = "";
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
		
		$sql = "";
		$table_time = "";
		$report_type_title = "";
		
		$DateTime = "";
		$DateTimeTitle = "";
		$MaximumDemandTitle = "";
		$date = new DateTime($datetime_begin);
		$datetime_begin_fomat = new DateTime($datetime_begin);
		$datetime_end_fomat = new DateTime($datetime_end);
		
		if($report_type == "DAY"){$table_time = "HOUR";$report_type_title = $lang['REPORT_SERVICE']['DAILY_REPORT']; $DateTime = $date->format('Y-m-d'); $DateTimeTitle = $lang['REPORT_SERVICE']['TIME']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['DAILY_MAXIMUM_DEMAND'];}
		if($report_type == "WEEK"){$table_time = "DAY";$report_type_title = $lang['REPORT_SERVICE']['WEEKLY_REPORT']; $DateTime = $date->format('Y-m-d'); $DateTimeTitle = $lang['REPORT_SERVICE']['TIME']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['WEEKLY_MAXIMUM_DEMAND'];}
		else if($report_type == "MONTH"){$table_time = "DAY";$report_type_title = $lang['REPORT_SERVICE']['MONTHLY_REPORT']; $DateTime = $date->format('Y-m'); $DateTimeTitle = $lang['REPORT_SERVICE']['DATE']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['MONTHLY_MAXIMUM_DEMAND'];}
		else if($report_type == "QUARTER"){$table_time = "MONTH";$report_type_title = $lang['REPORT_SERVICE']['QUARTERLY_REPORT']; $DateTime = $date->format('m'); $DateTimeTitle = $lang['REPORT_SERVICE']['DATE']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['QUARTERLY_MAXIMUM_DEMAND'];}
		else if($report_type == "YEAR"){$table_time = "MONTH";$report_type_title = $lang['REPORT_SERVICE']['ANNUAL_REPORT'];$DateTime = $date->format('Y'); $DateTimeTitle = $lang['REPORT_SERVICE']['MONTH']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['ANNUAL_MAXIMUM_DEMAND'];}
		
		$report_Data_string = "";
		
		$col_count = 3;
		
		
		$report_Data_string .= "<tr>";
		if($report_type == "WEEK"){
			$report_Data_string .= '<td class="date">'.$lang['REPORT_SERVICE']['REPORT_DATE'].': '.$datetime_begin_fomat->format('Y-m-d').' ~ '.$datetime_end_fomat->format('Y-m-d').'</td>';
		}
		
		else if($report_type == "QUARTER"){
			$report_Data_string .= '<td class="date">'.$lang['REPORT_SERVICE']['REPORT_DATE'].': '.$datetime_begin_fomat->format('Y')." Q".(floor($datetime_begin_fomat->format('m')/3)+1).'</td>';
		}
		else
			$report_Data_string .= '<td class="date">'.$lang['REPORT_SERVICE']['REPORT_DATE'].': '.$DateTime.'</td>';
		$report_Data_string .= '<td class="title">'.$sub_title.'  -  '.$report_type_title.'</td>';
		$report_Data_string .= '<td class="date"style="text-align:right;">'.$lang['REPORT_SERVICE']['PRINT_DATE'].': '.date("Y-m-d").'</td>';
		$report_Data_string .= "</tr>";
			
		$report_Data_string .= '<tr>
					<td class="header" style="width:300px;">'.$DateTimeTitle.'</td>
					<td class="header" style="width:400px;">'.$lang['REPORT_SERVICE']['MAX_DEMAND'].'</td>
					<td class="header" style="width:400px;">'.$lang['REPORT_SERVICE']['KWH_TITLE'].'</td>
				</tr>';
		
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
			
			$sql .= "SELECT DATE_FORMAT(`DateTime`,'".$table_time_code."') AS Time, MAX(Demand) as Demand,SUM(kWh) as kWh from(";
			$sql .= "SELECT `DateTime` AS `DateTime`,SUM(ABS(Demand)) as Demand,SUM(DeltaTotalKWH) as kWh FROM(";
			
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
			$sql .= " ) AS subquery  GROUP BY CONVERT_TZ(DateTime,'+00:00','".$timezone."') ";
			$sql .= " ) AS subquery  GROUP BY DATE_FORMAT(CONVERT_TZ(DateTime,'+00:00','".$timezone."'), '".$table_time_code."') ORDER BY DATE_FORMAT(DateTime,'".$table_time_code."') ";
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
			$sql .= " ) subquery GROUP BY DateTime ";
			$sql .= " ) subquery GROUP BY TO_CHAR(DateTime, '".$table_time_code."') ORDER BY TO_CHAR(DateTime,'".$table_time_code."') ";
		}
		
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		
		$total_kwh = 0;
		$td_class = "odd";
		While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
			$report_Data_string.='<tr>';
			if($report_type == "QUARTER"||$report_type == "YEAR"){
				switch($obj["Time"]) {
					case "1": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['JAN'].'</td>';break;
					case "2": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['FEB'].'</td>';break;
					case "3": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['MAR'].'</td>';break;
					case "4": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['APR'].'</td>';break;
					case "5": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['MAY'].'</td>';break;
					case "6": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['JUN'].'</td>';break;
					case "7": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['JUL'].'</td>';break;
					case "8": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['AUG'].'</td>';break;
					case "9": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['SEP'].'</td>';break;
					case "10":$report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['OCT'].'</td>';break;
					case "11":$report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['NOV'].'</td>';break;
					case "12":$report_Data_string .= '<td class="'.$td_class.'">'.$lang['REPORT_SERVICE']['DEC'].'</td>';break;
				}
			}
			else if($report_type == "WEEK"){
				switch($obj["Time"]) {
					case "1": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['HISTORY_ENERGY']['SUN'].'</td>';break;
					case "2": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['HISTORY_ENERGY']['MON'].'</td>';break;
					case "3": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['HISTORY_ENERGY']['TUE'].'</td>';break;
					case "4": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['HISTORY_ENERGY']['WED'].'</td>';break;
					case "5": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['HISTORY_ENERGY']['THU'].'</td>';break;
					case "6": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['HISTORY_ENERGY']['FRI'].'</td>';break;
					case "7": $report_Data_string .= '<td class="'.$td_class.'">'.$lang['HISTORY_ENERGY']['SAT'].'</td>';break;
				}
			}
			else{
				$report_Data_string .= '<td class="'.$td_class.'">'.$obj["Time"].'</td>';
			}
				
			$report_Data_string.='<td class="'.$td_class.'">'.floor_dec($obj["Demand"], 3).'</td>
				<td class="'.$td_class.'">'.floor_dec($obj["kWh"], 3).'</td>
				</tr>';
			$total_kwh += $obj["kWh"];
		}

		$report_Data_string.='<tr><td colspan="8"></td></tr>';
		$report_Data_string.='<tr>';
		$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['TOTAL'].'</td>';
		$report_Data_string .= '<td class="totalValue"> '.floor_dec($total_kwh,3).$lang['REPORT_SERVICE']['KWH'].' </td>';
		$report_Data_string .= '</tr>';
		
		$header_html="";
		$footer_html="";
		
		if($header_str!=""){
			$header_html .= "<tr>";
			$header_html .= "<td colspan='".$col_count."'>".$header_str."</td>";
			$header_html .= "</tr>";
		}

		if($footer_str!=""){
			$footer_html .= "<tr>";
			$footer_html .= "<td colspan='".$col_count."'>".$footer_str."</td>";
			$footer_html .= "</tr>";
		}
		
		$report_Data_string = $header_html.$report_Data_string.$footer_html;

		
		if(isset($stmt))
			$stmt->closeCursor();
		$conn = null;
		
		return $report_Data_string;
	}
	
	function read_report_data_group_f2($header_str, $footer_str){
		global $lang;
		global $login;
		if($login == false){
			trap("Permission error!");
		}
		$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);

		$groupuid = $_GET["GroupUID"];
		$datetime_begin = $_GET["datetime_begin"];
		$datetime_end = $_GET["datetime_end"];
		$report_type = $_GET["report_type"];
		$sub_title = $_GET["sub_title"];
		$timezone = $_GET["timezone"];
		$col_type = $_GET["col_type"];
		$col_text = $_GET["col_text"];
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
			
			$sql_phase_type = " SELECT Phase FROM ".$Username.".dbo.module WHERE DeviceUID='".$DeviceUID."' AND UID='".$ModuleUID."'; ";
			$stmt_phase_type = $conn->prepare($sql_phase_type);
			$stmt_phase_type->execute();
		
			$row = $stmt_phase_type->fetch(PDO::FETCH_ASSOC);
			$stmt_phase_type->closeCursor();
			$PhaseType = $row[Phase];
			
			$sql_group_module = "";
			if(DB_IS_MSSQL){
				$sql_group_module .= " SELECT group_data.Loop AS Loop,Module.Phase AS PhaseType,Device.ModelName AS DeviceName,Device.Nickname AS DeviceNickname, Module.ModelName AS ModuleName, Module.Nickname AS ModuleNickname, Channel.Nickname AS ChannelNickname from ".$_SESSION["username"].".dbo.group_data  ";
				$sql_group_module .= " JOIN ".$Username.".dbo.module AS Module ON Module.DeviceUID = group_data.DeviceUID and Module.UID = group_data.ModuleUID ";
				$sql_group_module .= " JOIN ".$Username.".dbo.device AS Device ON Device.UID = Module.DeviceUID ";
				$sql_group_module .= " JOIN ".$Username.".dbo.channel AS Channel  ON Device.UID = Module.DeviceUID AND  group_data.ModuleUID = channel.ModuleUID AND group_data.Loop = Channel.Loop ";
				$sql_group_module .= " WHERE Removed != 1 and GroupInfoUID = '".$groupuid."' ";
				if($PhaseType == 3)
					$sql_group_module .= " and group_data.AccountUID = '".$AccountUID."' and group_data.DeviceUID = '".$DeviceUID."' and group_data.ModuleUID = '".$ModuleUID."' and group_data.Loop = '".$Loop."' AND Channel.Phase='4';";
				else
					$sql_group_module .= " and group_data.AccountUID = '".$AccountUID."' and group_data.DeviceUID = '".$DeviceUID."' and group_data.ModuleUID = '".$ModuleUID."' and group_data.Loop = '".$Loop."';";
			}
			else if(DB_IS_MYSQL){
				$sql_group_module .= " SELECT group_data.`Loop` AS `Loop`,Module.Phase AS PhaseType,Device.ModelName AS DeviceName,Device.Nickname AS DeviceNickname, Module.ModelName AS ModuleName, Module.Nickname AS ModuleNickname, Channel.Nickname AS ChannelNickname from ".$_SESSION["username"].".group_data  ";
				$sql_group_module .= " JOIN ".$Username.".module AS Module ON Module.DeviceUID = group_data.DeviceUID and Module.UID = group_data.ModuleUID ";
				$sql_group_module .= " JOIN ".$Username.".device AS Device ON Device.UID = Module.DeviceUID ";
				$sql_group_module .= " JOIN ".$Username.".channel AS Channel  ON Device.UID = Module.DeviceUID AND  group_data.ModuleUID = channel.ModuleUID AND group_data.`Loop` = Channel.`Loop` ";
				$sql_group_module .= " WHERE Removed != 1 and GroupInfoUID = '".$groupuid."' ";
				if($PhaseType == 3)
					$sql_group_module .= " and group_data.AccountUID = '".$AccountUID."' and group_data.DeviceUID = '".$DeviceUID."' and group_data.ModuleUID = '".$ModuleUID."' and group_data.`Loop` = '".$Loop."' AND Channel.Phase='4';";
				else
					$sql_group_module .= " and group_data.AccountUID = '".$AccountUID."' and group_data.DeviceUID = '".$DeviceUID."' and group_data.ModuleUID = '".$ModuleUID."' and group_data.`Loop` = '".$Loop."';";
			}
			else if(DB_IS_ORACLE){
				$sql_group_module .= " SELECT group_data.Loop AS Loop,Module.Phase AS PhaseType,Device.ModelName AS DeviceName,Device.Nickname AS DeviceNickname, Module.ModelName AS ModuleName, Module.Nickname AS ModuleNickname, Channel.Nickname AS ChannelNickname from ".$_SESSION["username"].".group_data  ";
				$sql_group_module .= " JOIN ".$Username.".module Module ON Module.DeviceUID = group_data.DeviceUID and Module.\"UID\" = group_data.ModuleUID ";
				$sql_group_module .= " JOIN ".$Username.".device Device ON Device.\"UID\" = Module.DeviceUID ";
				$sql_group_module .= " JOIN ".$Username.".channel AS Channel  ON Device.UID = Module.DeviceUID AND  group_data.ModuleUID = channel.ModuleUID AND group_data.Loop = Channel.Loop ";
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
		$sql = "";
		$table_time = "";
		$report_type_title = "";
		
		$DateTime = "";
		$DateTimeTitle = "";
		$MaximumDemandTitle = "";
		$date = new DateTime($datetime_begin);
		$datetime_begin_fomat = new DateTime($datetime_begin);
		$datetime_end_fomat = new DateTime($datetime_end);
		
		if($report_type == "DAY"){$table_time = "HOUR";$report_type_title = $lang['REPORT_SERVICE']['DAILY_REPORT']; $DateTime = $date->format('Y-m-d'); $DateTimeTitle = $lang['REPORT_SERVICE']['TIME']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['DAILY_MAXIMUM_DEMAND'];}
		if($report_type == "WEEK"){$table_time = "DAY";$report_type_title = $lang['REPORT_SERVICE']['WEEKLY_REPORT']; $DateTime = $date->format('Y-m-d'); $DateTimeTitle = $lang['REPORT_SERVICE']['TIME']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['WEEKLY_MAXIMUM_DEMAND'];}
		else if($report_type == "MONTH"){$table_time = "DAY";$report_type_title = $lang['REPORT_SERVICE']['MONTHLY_REPORT']; $DateTime = $date->format('Y-m'); $DateTimeTitle = $lang['REPORT_SERVICE']['DATE']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['MONTHLY_MAXIMUM_DEMAND'];}
		else if($report_type == "QUARTER"){$table_time = "MONTH";$report_type_title = $lang['REPORT_SERVICE']['QUARTERLY_REPORT']; $DateTime = $date->format('m'); $DateTimeTitle = $lang['REPORT_SERVICE']['DATE']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['QUARTERLY_MAXIMUM_DEMAND'];}
		else if($report_type == "YEAR"){$table_time = "MONTH";$report_type_title = $lang['REPORT_SERVICE']['ANNUAL_REPORT'];$DateTime = $date->format('Y'); $DateTimeTitle = $lang['REPORT_SERVICE']['MONTH']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['ANNUAL_MAXIMUM_DEMAND'];}
		
		$TimeCol='';//時間欄位(Null時查看其他通道是否有值)
		$ChannelCol='';//取得通道欄位
		$ChannelData = '';//每個模組通道值
		$module_info = '';
		if(DB_IS_MSSQL){
			for($i=0; $i<count($group_modules_obj); $i++){
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
					$module_info .= $group_modules_obj[$i]{"DeviceName"}."_".$group_modules_obj[$i]{"DeviceNickname"}."_".$group_modules_obj[$i]{"ModuleName"}."_".$group_modules_obj[$i]{"ModuleNickname"}."_".$group_modules_obj[$i]{"Loop"}."_".$group_modules_obj[$i]{"ChannelNickname"};
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
					$module_info .= ",".$group_modules_obj[$i]{"DeviceName"}."_".$group_modules_obj[$i]{"DeviceNickname"}."_".$group_modules_obj[$i]{"ModuleName"}."_".$group_modules_obj[$i]{"ModuleNickname"}."_".$group_modules_obj[$i]{"Loop"}."_".$group_modules_obj[$i]{"ChannelNickname"};
				}
				
				$ChannelCol .= ',Channel'.$i.'.Demand'." AS Col".$i."_demand";
				$ChannelCol .= ',Channel'.$i.'.kWh'." AS Col".$i."_kwh";
				$ChannelCol .= ',Channel'.$i.'.PF'." AS Col".$i."_pf";
				$ChannelCol .= ',Channel'.$i.'.V'." AS Col".$i."_v";
				$ChannelCol .= ',Channel'.$i.'.I'." AS Col".$i."_i";
				$ChannelCol .= ',Channel'.$i.'.kVA'." AS Col".$i."_kva";
				$ChannelCol .= ',Channel'.$i.'.kvar'." AS Col".$i."_kvar";
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
					$module_info .= ",".$group_modules_obj[$i]{"DeviceName"}."_".$group_modules_obj[$i]{"DeviceNickname"}."_".$group_modules_obj[$i]{"ModuleName"}."_".$group_modules_obj[$i]{"ModuleNickname"}."_".$group_modules_obj[$i]{"Loop"}."_".$group_modules_obj[$i]{"ChannelNickname"};
				}
				else{
					$module_info .= $group_modules_obj[$i]{"DeviceName"}."_".$group_modules_obj[$i]{"DeviceNickname"}."_".$group_modules_obj[$i]{"ModuleName"}."_".$group_modules_obj[$i]{"ModuleNickname"}."_".$group_modules_obj[$i]{"Loop"}."_".$group_modules_obj[$i]{"ChannelNickname"};
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
					$module_info .= $group_modules_obj[$i]{"DeviceName"}."_".$group_modules_obj[$i]{"DeviceNickname"}."_".$group_modules_obj[$i]{"ModuleName"}."_".$group_modules_obj[$i]{"ModuleNickname"}."_".$group_modules_obj[$i]{"Loop"}."_".$group_modules_obj[$i]{"ChannelNickname"};
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
					$module_info .= ",".$group_modules_obj[$i]{"DeviceName"}."_".$group_modules_obj[$i]{"DeviceNickname"}."_".$group_modules_obj[$i]{"ModuleName"}."_".$group_modules_obj[$i]{"ModuleNickname"}."_".$group_modules_obj[$i]{"Loop"}."_".$group_modules_obj[$i]{"ChannelNickname"};
				}
				
				$ChannelCol .= ',Channel'.$i.'.DEMAND'." AS Col".$i."_demand";
				$ChannelCol .= ',Channel'.$i.'.kWh'." AS Col".$i."_kwh";
				$ChannelCol .= ',Channel'.$i.'.PF'." AS Col".$i."_pf";
				$ChannelCol .= ',Channel'.$i.'.V'." AS Col".$i."_v";
				$ChannelCol .= ',Channel'.$i.'.I'." AS Col".$i."_i";
				$ChannelCol .= ',Channel'.$i.'.kVA'." AS Col".$i."_kva";
				$ChannelCol .= ',Channel'.$i.'.kvar'." AS Col".$i."_kvar";
			}
			$sql = "";
			$sql .= " select ".$TimeCol." AS \"TIME\" ".$ChannelCol." from ( ";
			$sql .= $ChannelData;
			$sql .= " order by \"TIME\" ";
		}
		
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		
		
		//-----
		$report_Data_string = "";
		$col_count = (count($group_modules_obj)+1);
		
		$report_Data_string .= "<tr>";
		if($report_type == "WEEK"){
			$report_Data_string .= '<td class="date">'.$lang['REPORT_SERVICE']['REPORT_DATE'].': '.$datetime_begin_fomat->format('Y-m-d').' ~ '.$datetime_end_fomat->format('Y-m-d').'</td>';
		}
		
		else if($report_type == "QUARTER"){
			$report_Data_string .= '<td class="date">'.$lang['REPORT_SERVICE']['REPORT_DATE'].': '.$datetime_begin_fomat->format('Y')." Q".(floor($datetime_begin_fomat->format('m')/3)+1).'</td>';
		}
		else
			$report_Data_string .= '<td class="date">'.$lang['REPORT_SERVICE']['REPORT_DATE'].': '.$DateTime.'</td>';
		
		$report_Data_string .= '<td class="title" colspan="'.(count($group_modules_obj)-1).'">'.$sub_title.'  -  '.$col_text.'  -  '.$report_type_title.'</td>';
		$report_Data_string .= '<td class="date"style="text-align:right;">'.$lang['REPORT_SERVICE']['PRINT_DATE'].': '.date("Y-m-d").'</td>';
		$report_Data_string .= "</tr>";
		//----
		$module_info = explode(',', $module_info);
		
		$report_Data_string .= "<tr>";
		$report_Data_string .= "<td class='header'>";
		$report_Data_string .= "</td>";
		for($i=0;$i<count($module_info);$i++){
			$report_title = "";
			if(explode("_",$module_info[$i])[1] == "")
				$report_title .= explode("_",$module_info[$i])[0];
			else
				$report_title .= explode("_",$module_info[$i])[1];
			$report_title .= "<br/>";
			if(explode("_",$module_info[$i])[3] == "")
				$report_title .= explode("_",$module_info[$i])[2];
			else
				$report_title .= explode("_",$module_info[$i])[3];
			$report_title .= "<br/>";
			$report_title .= $lang['REPORT_SERVICE']['LOOP'].explode("_",$module_info[$i])[4];
			
			if(explode("_",$module_info[$i])[5] == ""){
				$report_title .= "<br/>";
				$report_title .= explode("_",$module_info[$i])[5];
			}
			
			
			$report_Data_string .= "<td class='header'>".$report_title."</td>";
		}
		
		$report_Data_string .= "</tr>";
		
		$td_class = "odd";
		While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
			$report_Data_string .= "<tr>";
			$report_Data_string .= "<td class='".$td_class."'>".$obj["Time"]."</td>";
			for($i=0;$i<count($module_info);$i++){
				$col_name = "Col".$i.$col_type;
				$report_Data_string .= "<td class='".$td_class."'>".$obj[$col_name]."</td>";
			}
			
			$report_Data_string .= "</tr>";
		}
		
		$report_Data_string .= "<tr></tr>";
		$stmt->closeCursor();
		
		//Other
		$report_Data_string .= "<tr>";
		$report_Data_string .= "<td class='header' style='border: 1px solid #C4C4C4;'>";
		
		switch ($report_type){
			case "DAY": $report_Data_string .= $lang['REPORT_SERVICE']['DAILY_TOTAL_MODULE_ENERGY_CONSUMPTION']; break;
			case "WEEK": $report_Data_string .= $lang['REPORT_SERVICE']['THIS_WEEK_MODULE_ENERGY_CONSUMPTION'];	break;
			case "MONTH": $report_Data_string .= $lang['REPORT_SERVICE']['THIS_MONTH_MODULE_ENERGY_CONSUMPTION']; break;
			case "QUARTER": $report_Data_string .= $lang['REPORT_SERVICE']['THIS_QUARTER_MODULE_ENERGY_CONSUMPTION']; break;
			case "YEAR": $report_Data_string .= $lang['REPORT_SERVICE']['THIS_YEAR_MODULE_ENERGY_CONSUMPTION'];	break;
		}
		
		
		$report_Data_string .= "</td>";
		
		for($module_idx=0; $module_idx<count($group_modules_obj); $module_idx++){
			$module_Loop = $group_modules_obj[$module_idx]{"Loop"};
			$PhaseType = $group_modules_obj[$module_idx]{"PhaseType"};
			$phase = 4;
			if($PhaseType == 1)
				$phase = 1;
			else
				$phase = 4;
			
			if(DB_IS_MSSQL){
				$module_table_name = $group_modules_obj[$module_idx]{"Username"}.".dbo.uid_".$group_modules_obj[$module_idx]{"DeviceUID"}."_".$group_modules_obj[$module_idx]{"ModuleUID"};
				$sql = " SELECT SUM(DeltaTotalKWH) AS SUMkwh FROM ".$module_table_name." where Loop='".$module_Loop."'and Phase='".$phase."' and DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."' ; ";
			}
			else if(DB_IS_MYSQL){
				$module_table_name = $group_modules_obj[$module_idx]{"Username"}.".uid_".$group_modules_obj[$module_idx]{"DeviceUID"}."_".$group_modules_obj[$module_idx]{"ModuleUID"};
				$sql = " SELECT SUM(DeltaTotalKWH) AS SUMkwh FROM ".$module_table_name." where `Loop`='".$module_Loop."' and Phase='".$phase."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."' ; ";
			}
			else if(DB_IS_ORACLE){
				$module_table_name = $group_modules_obj[$module_idx]{"Username"}.".uid_".$group_modules_obj[$module_idx]{"DeviceUID"}."_".$group_modules_obj[$module_idx]{"ModuleUID"};
				$sql = " SELECT SUM(DeltaTotalKWH) AS SUMkwh FROM ".$module_table_name." where Loop='".$module_Loop."' and Phase='".$phase."' and sys_extract_utc(from_tz(DateTime,'".$timezone."')) between TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS'); ";
			}
			
			
			$stmt = $conn->prepare($sql);
			$stmt->execute();
			
			While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
				$report_Data_string .= "<td class='even'>".($obj["SUMkwh"])."</td>";
				
				$totalKWH = $totalKWH + $obj["SUMkwh"];
			}
			$stmt->closeCursor();
		}
		
		
		$report_Data_string .= "</tr>";
		$report_Data_string .= "<tr>";
		$report_Data_string .= "<td class='header' style='border: 1px solid #C4C4C4;'>";
		
		switch ($report_type){
			case "DAY": $report_Data_string .= $lang['REPORT_SERVICE']['DAILY_TOTAL_ENERGY_CONSUMPTION']; break;
			case "WEEK": $report_Data_string .= $lang['REPORT_SERVICE']['WEEKLY_TOTAL_ENERGY_CONSUMPTION'];	break;
			case "MONTH": $report_Data_string .= $lang['REPORT_SERVICE']['MONTHLY_TOTAL_ENERGY_CONSUMPTION']; break;
			case "QUARTER": $report_Data_string .= $lang['REPORT_SERVICE']['QUARTERLY_TOTAL_ENERGY_CONSUMPTION']; break;
			case "YEAR": $report_Data_string .= $lang['REPORT_SERVICE']['ANNUAL_TOTAL_ENERGY_CONSUMPTION'];	break;
		}
		
		$report_Data_string .= "</td>";
		$report_Data_string .= "<td colspan='".count($group_modules_obj)."' class='even'>".($totalKWH)."</td>";
		$report_Data_string .= "</tr>";
		$header_html="";
		$footer_html="";
		
		if($header_str!=""){
			$header_html .= "<tr>";
			$header_html .= "<td colspan='".$col_count."'>".$header_str."</td>";
			$header_html .= "</tr>";
		}

		if($footer_str!=""){
			$footer_html .= "<tr>";
			$footer_html .= "<td colspan='".$col_count."'>".$footer_str."</td>";
			$footer_html .= "</tr>";
		}
		
		$report_Data_string = $header_html.$report_Data_string.$footer_html;
		$conn = null;
		return $report_Data_string;
	}
	function floor_dec($v, $precision){
		if($v===NULL)
			return "";
		$c = pow(10, $precision);
		return floor($v*$c)/$c;
	}
	
	function excel_template(&$header_str, &$footer_str){
		$conn = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		//account
		$accountname = "";
		if($_GET["template_accountUID"] != "undefined"){
			$stmt = $conn->prepare(
				DB_IS_MSSQL  ? "select UID, Username from manager.dbo.account where UID = '".$_GET["template_accountUID"]."';" : (
				DB_IS_MYSQL  ? "select UID, Username from manager.account where UID = '".$_GET["template_accountUID"]."';" : (
				DB_IS_ORACLE ? "select \"UID\", Username from manager.\"ACCOUNT\" where \"UID\" = '".$_GET["template_accountUID"]."';" : null))
			);
			$stmt->execute();
			While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
				$accountname = $obj["Username"];
			}
			$stmt->closeCursor();
		}
		else
			$accountname = $_SESSION["username"];
		
		if($_GET["template_id"] != "-1"){
			$stmt = $conn->prepare(
				DB_IS_MSSQL  ? " SELECT ReportHeader, ReportFooter FROM ".$accountname.".dbo.report_template WHERE UID = '".$_GET["template_id"]."'; " : (
				DB_IS_MYSQL  ? " SELECT ReportHeader, ReportFooter FROM ".$accountname.".report_template WHERE UID = '".$_GET["template_id"]."'; " : (
				DB_IS_ORACLE ? " SELECT ReportHeader, ReportFooter FROM ".$accountname.".report_template WHERE \"UID\" = '".$_GET["template_id"]."';" : null))
			);
			$stmt->execute();
			While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
				$header_str = $obj["ReportHeader"];
				$footer_str = $obj["ReportFooter"];
			}
			$stmt->closeCursor();
		}
		$conn = null;
	}
?>