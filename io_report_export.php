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
		$channel = $_GET["channel"];
	}
	else{
		$GroupUID = $_GET["GroupUID"];
	}

	
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
	
	
	$compare_time_format = $_GET["compare_time_format"];
	$compare_datetime_begin = $_GET["compare_datetime_begin"];
	$compare_datetime_end = $_GET["compare_datetime_end"];

	
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
	
	//讀取頁首頁尾範本
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
		$excel_body .= read_report_data_group($header_str, $footer_str);
	}

	
	if($report_type == "WEEK"){
		$filename = str_replace ("/","_",$_GET["sub_title"])."_".$datetime_begin_fomat->format('Y-m-d').'~'.$datetime_end_fomat->format('Y-m-d')."_".$lang['REPORT_SERVICE']['WEEKLY_REPORT'];
	}
	else if($report_type == "QUARTER"){
		$filename = str_replace ("/","_",$_GET["sub_title"])."_".$date->format('Y')." Q".(floor($date->format('m')/3)+1)."_".$report_type_title;
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
	
	//Base64
	$doc = DOMDocument::loadHTML(mb_convert_encoding($table_str,"HTML-ENTITIES","UTF-8"));
	$xpath = new DOMXPath($doc);
	$query = "//img";
	$entries = $xpath->query($query);
	$img_idx = 0;
	foreach ($entries as $entry) {
		$img_src = $entry->getAttribute("src");
		array_push($img_b64,explode(',',$img_src)[1]);
		array_push($img_code,explode(';',explode(',',$img_src)[0])[1]);//base64
		array_push($img_datatype,explode(':',explode(';',explode(',',$img_src)[0])[0])[1]);//image/png
		
		//array_push($img_b64,str_replace("data:image/png;base64,","",$entry->getAttribute("src")));
	}
	foreach ($entries as $entry) {
		$entry->setAttribute("src", "cid:image".$img_idx);
		$img_idx += 1;
	}
	
	$query = "//p";
	$entries = $xpath->query($query);
	foreach ($entries as $entry) {
		if(strpos($entry->getAttribute("style"),"text-align") === false){
			$entry->setAttribute("style",$entry->getAttribute("style")."text-align: left;");
		}
	}
	
	$table_str = $doc ->saveHTML();
	

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
		header('Content-Disposition: attachment;filename="'.$filename.$_GET["col_type"].'.xls"');
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
		if($conn === false) {
			trap("Connect failed!", print_r(sqlsrv_errors(), true));
		}
		$report_Data_string = "";
		$accountUID = $_GET["accountUID"];
		$DeviceUID = $_GET["DeviceUID"];
		$moduleUID = $_GET["moduleUID"];
		$Channel = $_GET["channel"];
		$datetime_begin = $_GET["datetime_begin"];
		$datetime_end = $_GET["datetime_end"];
		$report_type = $_GET["report_type"];
		$sub_title = $_GET["sub_title"];
		$timezone = $_GET["timezone"];
		$template_id = $_GET["template_id"];
		$col_count = 0;
		$Unit = "";
		
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
			$stmt->closeCursor();
		}
		else
			$accountname = $_SESSION["username"];
		
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "select Nickname AS ChannelNickname,Unit AS Unit from ".$accountname.".dbo.channel where DeviceUID='".$DeviceUID."' AND ModuleUID='".$moduleUID."' AND Channel='".$Channel."';" : (
			DB_IS_MYSQL  ? "select Nickname AS ChannelNickname,Unit AS Unit from ".$accountname.".channel where DeviceUID='".$DeviceUID."' AND ModuleUID='".$moduleUID."' AND Channel='".$Channel."';" : (
			DB_IS_ORACLE ? "select Nickname AS ChannelNickname,Unit AS Unit from ".$accountname.".channel where DeviceUID='".$DeviceUID."' AND ModuleUID='".$moduleUID."' AND Channel='".$Channel."'; " : null))
		);
		$stmt->execute();
		
		While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
			$Unit = $obj["Unit"];
		}
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
				
		$col_count = $_GET["col_count"]*1+1;
		$ck_array = explode(",", $_GET["ck_array"]);
		//header
		$report_Data_string .= "<tr>";
		if($report_type == "WEEK"){
			$report_Data_string .= '<td class="date">'.$lang['REPORT_SERVICE']['REPORT_DATE'].': '.$datetime_begin_fomat->format('Y-m-d').' ~ '.$datetime_end_fomat->format('Y-m-d').'</td>';
		}
		
		else if($report_type == "QUARTER"){
			$report_Data_string .= '<td class="date">'.$lang['REPORT_SERVICE']['REPORT_DATE'].': '.$date->format('Y')." Q".(floor($date->format('m')/3)+1).'</td>';
		}
		else
			$report_Data_string .= '<td class="date">'.$lang['REPORT_SERVICE']['REPORT_DATE'].': '.$DateTime.'</td>';
		if(($col_count-2)<=0)
				$report_Data_string .= '<td class="title" colspan="1">'.$sub_title.'  -  '.$report_type_title.'</td>';
		else
			$report_Data_string .= '<td class="title" colspan="'.($col_count-2).'">'.$sub_title.'  -  '.$report_type_title.'</td>';
		//$report_Data_string .= '<td class="title" colspan="2">'.$sub_title.'  -  '.$report_type_title.'</td>';
		$report_Data_string .= '<td class="date" style="text-align:right;">'.$lang['REPORT_SERVICE']['PRINT_DATE'].': '.date("Y-m-d").'</td>';
		$report_Data_string .= "</tr>";

		$report_Data_string .= '<tr>';
		$title=[];
		if($Unit == "")
			$title=[$lang['REPORT_SERVICE']['MAX'],$lang['REPORT_SERVICE']['MIN'],$lang['REPORT_SERVICE']['AVERAGE'],$lang['REPORT_SERVICE']['FINAL'],$lang['REPORT_SERVICE']['TOTAL']];
		else
			$title=[$lang['REPORT_SERVICE']['MAX']."(".$Unit.")",$lang['REPORT_SERVICE']['MIN']."(".$Unit.")",$lang['REPORT_SERVICE']['AVERAGE']."(".$Unit.")",$lang['REPORT_SERVICE']['FINAL']."(".$Unit.")",$lang['REPORT_SERVICE']['TOTAL']."(".$Unit.")"];
		$report_Data_string .= '<td class="header">'.$DateTimeTitle.'</td>';
		for($i=0; $i<Count($title); $i++){
			if($ck_array[$i]=="true")
				$report_Data_string .= '<td class="header">'.$title[$i].'</td>';
		}
		$report_Data_string .= '</tr>';
		
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
			$sql .= "			Where DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."' ";
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
			$col_val = [floor_dec($obj["Max_val"], 3),floor_dec($obj["Min_val"], 3),floor_dec($obj["Avg_val"], 3),floor_dec($obj["Final_val"], 3),floor_dec($obj["Sum_val"], 3)];
			
			for($i=0; $i<Count($col_val); $i++){
				if($ck_array[$i]=="true")
					$report_Data_string .= '<td class="'.$td_class.'">'.$col_val[$i].'</td>';
			}
		}
		$report_Data_string .='<tr></tr>';
		While($obj = $stmt_other->fetch(PDO::FETCH_ASSOC)){
			if($report_type == "DAY"){
				$report_Data_string .='<tr>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['TODAY_TOTAL_VALUE'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.floor_dec($obj["Total_Val"], 3).' '.$Unit.'</td>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['TODAY_MAXIMUM'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.floor_dec($obj["Max_Val"], 3).' '.$Unit.'</td>'; 
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['TODAY_MINIMUM'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.floor_dec($obj["Min_Val"], 3).' '.$Unit.'</td>';
				$report_Data_string .= '</tr>';
				
				$report_Data_string .='<tr>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['TODAY_AVERAGE'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.floor_dec($obj["Avg_Val"], 3).' '.$Unit.'</td>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['TODAY_MAX_TIME_OCCURRENCE'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.($obj["Max_Time"]).'</td>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['TODAY_MIN_TIME_OCCURRENCE'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.($obj["Min_Time"]).'</td>';
				$report_Data_string .= '</tr>';
			}
			else if($report_type == "WEEK"){
				$report_Data_string .='<tr>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['THIS_WEEK_TOTAL_VALUE'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.floor_dec($obj["Total_Val"], 3).' '.$Unit.'</td>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['THIS_WEEK_MAXIMUM'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.floor_dec($obj["Max_Val"], 3).' '.$Unit.'</td>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['THIS_WEEK_MINIMUM'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.floor_dec($obj["Min_Val"], 3).' '.$Unit.'</td>';
				$report_Data_string .= '</tr>';
				
				$report_Data_string .='<tr>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['THIS_WEEK_AVERAGE'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.floor_dec($obj["Avg_Val"], 3).' '.$Unit.'</td>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['THIS_WEEK_MAX_TIME_OCCURRENCE'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.($obj["Max_Time"]).'</td>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['THIS_WEEK_MIN_TIME_OCCURRENCE'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.($obj["Min_Time"]).'</td>';
				$report_Data_string .= '</tr>';
			}
			else if($report_type == "MONTH"){
				$report_Data_string .='<tr>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['THIS_MONTH_TOTAL_VALUE'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.floor_dec($obj["Total_Val"], 3).' '.$Unit.'</td>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['THIS_MONTH_MAXIMUM'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.floor_dec($obj["Max_Val"], 3).' '.$Unit.'</td>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['THIS_MONTH_MINIMUM'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.floor_dec($obj["Min_Val"], 3).' '.$Unit.'</td>';
				$report_Data_string .= '</tr>';
				
				$report_Data_string .='<tr>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['THIS_MONTH_AVERAGE'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.floor_dec($obj["Avg_Val"], 3).' '.$Unit.'</td>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['THIS_MONTH_MAX_TIME_OCCURRENCE'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.($obj["Max_Time"]).'</td>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['THIS_MONTH_MIN_TIME_OCCURRENCE'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.($obj["Min_Time"]).'</td>';
				$report_Data_string .= '</tr>';
			}
			else if($report_type == "QUARTER"){
				$report_Data_string .='<tr>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['THIS_QUARTER_TOTAL_VALUE'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.floor_dec($obj["Total_Val"], 3).' '.$Unit.'</td>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['THIS_QUARTER_MAXIMUM'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.floor_dec($obj["Max_Val"], 3).' '.$Unit.'</td>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['THIS_QUARTER_MINIMUM'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.floor_dec($obj["Min_Val"], 3).' '.$Unit.'</td>';
				$report_Data_string .= '</tr>';
				
				$report_Data_string .='<tr>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['THIS_QUARTER_AVERAGE'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.floor_dec($obj["Avg_Val"], 3).' '.$Unit.'</td>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['THIS_QUARTER_MAX_TIME_OCCURRENCE'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.($obj["Max_Time"]).'</td>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['THIS_QUARTER_MIN_TIME_OCCURRENCE'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.($obj["Min_Time"]).'</td>';
				$report_Data_string .= '</tr>';
			}
			else if($report_type == "YEAR"){
				$report_Data_string .='<tr>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['THIS_YEAR_TOTAL_VALUE'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.floor_dec($obj["Total_Val"], 3).' '.$Unit.'</td>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['THIS_YEAR_MAXIMUM'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.floor_dec($obj["Max_Val"], 3).' '.$Unit.'</td>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['THIS_YEAR_MINIMUM'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.floor_dec($obj["Min_Val"], 3).' '.$Unit.'</td>';
				$report_Data_string .= '</tr>';
				
				$report_Data_string .='<tr>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['THIS_YEAR_AVERAGE'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.floor_dec($obj["Avg_Val"], 3).' '.$Unit.'</td>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['THIS_YEAR_MAX_TIME_OCCURRENCE'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.($obj["Max_Time"]).'</td>';
				$report_Data_string .= '<td class="totalHeader">'.$lang['REPORT_SERVICE']['THIS_YEAR_MIN_TIME_OCCURRENCE'].'</td>';
				$report_Data_string .= '<td class="totalValue" style="color:#3A4453;text-align:center;border: 1px solid #C4C4C4;">'.($obj["Min_Time"]).'</td>';
				$report_Data_string .= '</tr>';
			}
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
		if(isset($stmt_other))
			$stmt_other->closeCursor();
		
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
		$Channel = $_GET["channel"];
		$datetime_begin = $_GET["datetime_begin"];
		$datetime_end = $_GET["datetime_end"];
		$report_type = $_GET["report_type"];
		$sub_title = $_GET["sub_title"];
		$timezone = $_GET["timezone"];
		$Unit = "";
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
				DB_IS_ORACLE ? "select \"UID\", Username from manager.\"ACCOUNT\" where \"UID\" = '".$accountUID."';": null))
			);
			$stmt->execute();
			While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
				$accountname = $obj["Username"];
			}
		}
		else
			$accountname = $_SESSION["username"];
		$stmt->closeCursor();
		
		$stmt = $conn->prepare(
			DB_IS_MSSQL  ? "select Nickname AS ChannelNickname,Unit AS Unit from ".$accountname.".dbo.channel where DeviceUID='".$DeviceUID."' AND ModuleUID='".$moduleUID."' AND Channel='".$Channel."';" : (
			DB_IS_MYSQL  ? "select Nickname AS ChannelNickname,Unit AS Unit from ".$accountname.".channel where DeviceUID='".$DeviceUID."' AND ModuleUID='".$moduleUID."' AND Channel='".$Channel."';" : (
			DB_IS_ORACLE ? "select Nickname AS ChannelNickname,Unit AS Unit from ".$accountname.".channel where DeviceUID='".$DeviceUID."' AND ModuleUID='".$moduleUID."' AND Channel='".$Channel."';" : null))
		);
		$stmt->execute();
		While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
			$Unit = $obj["Unit"];
		}
		$stmt->closeCursor();
		
		$report_type_title = "";
		$DateTime = "";
		$DateTimeTitle = "";
		$MaximumDemandTitle = "";
		$date = new DateTime($datetime_begin);
		$datetime_begin_fomat = new DateTime($datetime_begin);
		$datetime_end_fomat = new DateTime($datetime_end);
		$compare_datetime_begin_fomat = new DateTime($compare_datetime_begin);
		$compare_datetime_end_fomat = new DateTime($compare_datetime_end);
		
		if($report_type == "DAY"){$report_type_title = $lang['REPORT_SERVICE']['DAILY_REPORT']; $DateTime = $date->format('Y-m-d'); $DateTimeTitle = $lang['REPORT_SERVICE']['TIME']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['DAILY_MAXIMUM_DEMAND'];}
		if($report_type == "WEEK"){$report_type_title = $lang['REPORT_SERVICE']['WEEKLY_REPORT']; $DateTime = $date->format('Y-m-d'); $DateTimeTitle = $lang['REPORT_SERVICE']['TIME']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['WEEKLY_MAXIMUM_DEMAND'];}
		else if($report_type == "MONTH"){$report_type_title = $lang['REPORT_SERVICE']['MONTHLY_REPORT']; $DateTime = $date->format('Y-m'); $DateTimeTitle = $lang['REPORT_SERVICE']['DATE']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['MONTHLY_MAXIMUM_DEMAND'];}
		else if($report_type == "QUARTER"){$report_type_title = $lang['REPORT_SERVICE']['QUARTERLY_REPORT']; $DateTime = $date->format('m'); $DateTimeTitle = $lang['REPORT_SERVICE']['DATE']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['QUARTERLY_MAXIMUM_DEMAND'];}
		else if($report_type == "YEAR"){$report_type_title = $lang['REPORT_SERVICE']['ANNUAL_REPORT'];$DateTime = $date->format('Y'); $DateTimeTitle = $lang['REPORT_SERVICE']['MONTH']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['ANNUAL_MAXIMUM_DEMAND'];}
		
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
		//header
		$col_count = $_GET["col_count"]*1+1;
		$ck_array = explode(",", $_GET["ck_array"]);
		
		$report_Data_string .= "<tr>";
		$report_Data_string .= '<td class="date">';
		if($report_type == "WEEK"){
			$report_Data_string .= $lang['REPORT_SERVICE']['REPORT_DATE'].': '.$datetime_begin_fomat->format('Y-m-d').' ~ '.$datetime_end_fomat->format('Y-m-d');
			$report_Data_string .= '<br/>';
			$report_Data_string .= $lang['REPORT_SERVICE']['COMPARE_DATE'].': '.$compare_datetime_begin_fomat->format('Y-m-d').' ~ '.$compare_datetime_end_fomat->format('Y-m-d');
		}
		
		else if($report_type == "QUARTER"){
			$report_Data_string .= $lang['REPORT_SERVICE']['REPORT_DATE'].': '.$date->format('Y')." Q".(floor($date->format('m')/3)+1);
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
		$report_Data_string .= '<td class="date" style="text-align:right;">'.$lang['REPORT_SERVICE']['PRINT_DATE'].': '.date("Y-m-d").'</td>';
		$report_Data_string .= "</tr>";
		
		$report_Data_string .= '<tr>';
		$title=[];
		
		if($Unit == "")
			$title=[$lang['REPORT_SERVICE']['MAX'],$lang['REPORT_SERVICE']['MIN'],$lang['REPORT_SERVICE']['AVERAGE'],$lang['REPORT_SERVICE']['FINAL'],$lang['REPORT_SERVICE']['TOTAL']];
		else
			$title=[$lang['REPORT_SERVICE']['MAX']."(".$Unit.")",$lang['REPORT_SERVICE']['MIN']."(".$Unit.")",$lang['REPORT_SERVICE']['AVERAGE']."(".$Unit.")",$lang['REPORT_SERVICE']['FINAL']."(".$Unit.")",$lang['REPORT_SERVICE']['TOTAL']."(".$Unit.")"];
		$report_Data_string .= '<td class="header">'.$DateTimeTitle.'</td>';
		for($i=0; $i<Count($title); $i++){
			if($ck_array[$i*2]=="true")
				$report_Data_string .= '<td class="header" colspan="2">'.$title[$i].'</td>';
		}
		$report_Data_string .= '</tr>';
		
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
			$col_val = [floor_dec($obj["Max_val"], 3),floor_dec($obj["c_Max_val"], 3),floor_dec($obj["Min_val"], 3),floor_dec($obj["c_Min_val"], 3),floor_dec($obj["Avg_val"], 3),floor_dec($obj["c_Avg_val"], 3),floor_dec($obj["Final_val"], 3),floor_dec($obj["c_Final_val"], 3),floor_dec($obj["Sum_val"], 3),floor_dec($obj["c_Sum_val"], 3)];
			
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
		$col_type = $_GET["col_type"];
		$col_text = $_GET["col_text"];
		
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
				DB_IS_MSSQL  ? "select Nickname AS ChannelNickname,Unit from ".$Username.".dbo.channel where DeviceUID='".$DeviceUID."' AND ModuleUID='".$ModuleUID."' AND Channel='".$Channel."';" : (
				DB_IS_MYSQL  ? "select Nickname AS ChannelNickname,Unit AS Unit from ".$Username.".channel where DeviceUID='".$DeviceUID."' AND ModuleUID='".$ModuleUID."' AND Channel='".$Channel."';" : (
				DB_IS_ORACLE ? "select Nickname AS ChannelNickname,Unit AS Unit from ".$Username.".channel where DeviceUID='".$DeviceUID."' AND ModuleUID='".$ModuleUID."' AND Channel='".$Channel."';" : null))
			);
			$stmt_group_channel->execute();
			
			While($obj_channelinfo = $stmt_group_channel->fetch(PDO::FETCH_ASSOC)){
				$ChannelNickname = $obj_channelinfo["ChannelNickname"];
				$Unit = $obj_channelinfo["Unit"];
			}
			$stmt_group_channel->closeCursor();
			
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
	
		$DateTime = "";
		$DateTimeTitle = "";
		$MaximumDemandTitle = "";
		$date = new DateTime($datetime_begin);
		$datetime_begin_fomat = new DateTime($datetime_begin);
		$datetime_end_fomat = new DateTime($datetime_end);
		
		$report_Data_string = "";
		$col_count = (count($group_modules_obj)+1);
		
		if($report_type == "DAY"){$table_time = "HOUR"; $report_type_title = $lang['REPORT_SERVICE']['DAILY_REPORT']; $DateTime = $date->format('Y-m-d'); $DateTimeTitle = $lang['REPORT_SERVICE']['TIME']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['DAILY_MAXIMUM_DEMAND'];}
		else if($report_type == "WEEK"){$table_time = "DAY";$report_type_title = $lang['REPORT_SERVICE']['WEEKLY_REPORT']; $DateTime = $date->format('Y-m-d'); $DateTimeTitle = $lang['REPORT_SERVICE']['TIME']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['WEEKLY_MAXIMUM_DEMAND'];}
		else if($report_type == "MONTH"){$table_time = "DAY";$report_type_title = $lang['REPORT_SERVICE']['MONTHLY_REPORT']; $DateTime = $date->format('Y-m'); $DateTimeTitle = $lang['REPORT_SERVICE']['DATE']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['MONTHLY_MAXIMUM_DEMAND'];}
		else if($report_type == "QUARTER"){$table_time = "MONTH";$report_type_title = $lang['REPORT_SERVICE']['QUARTERLY_REPORT']; $DateTime = $date->format('m'); $DateTimeTitle = $lang['REPORT_SERVICE']['DATE']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['QUARTERLY_MAXIMUM_DEMAND'];}
		else if($report_type == "YEAR"){$table_time = "MONTH";$report_type_title = $lang['REPORT_SERVICE']['ANNUAL_REPORT'];$DateTime = $date->format('Y'); $DateTimeTitle = $lang['REPORT_SERVICE']['MONTH']; $MaximumDemandTitle = $lang['REPORT_SERVICE']['ANNUAL_MAXIMUM_DEMAND'];}
		
		$report_Data_string = "";
		$report_Data_string .= "<tr>";
		if($report_type == "WEEK")$report_Data_string .= '<td class="date">'.$lang['REPORT_SERVICE']['REPORT_DATE'].': '.$datetime_begin_fomat->format('Y-m-d').' ~ '.$datetime_end_fomat->format('Y-m-d').'</td>';
		else if($report_type == "QUARTER")$report_Data_string .= '<td class="date">'.$lang['REPORT_SERVICE']['REPORT_DATE'].': '.$date->format('Y')." Q".(floor($date->format('m')/3)+1).'</td>';
		else $report_Data_string .= '<td class="date">'.$lang['REPORT_SERVICE']['REPORT_DATE'].': '.$DateTime.'</td>';
		
		$report_Data_string .= '<td class="title" colspan="'.(count($group_modules_obj)-1).'">'.$sub_title.'  -  '.$col_text.'  -  '.$report_type_title.'</td>';
		$report_Data_string .= '<td class="date"style="text-align:right;">'.$lang['REPORT_SERVICE']['PRINT_DATE'].': '.date("Y-m-d").'</td>';
		$report_Data_string .= "</tr>";
		
		$report_Data_string .= '<tr>';
		$report_Data_string .= '<td class="header" style="width:300px;">'.$DateTimeTitle.'</td>';
		for($col_idx = 0; $col_idx < count($group_modules_obj); $col_idx++){
			$col_title = "";
			if($group_modules_obj[$col_idx]{"DeviceNickname"} != "")
				$col_title .= $group_modules_obj[$col_idx]{"DeviceNickname"};
			else
				$col_title .= $group_modules_obj[$col_idx]{"DeviceName"};
			$col_title .= "<br/>";
			
			if($group_modules_obj[$col_idx]{"ModuleNickname"} != "")
				$col_title .= $group_modules_obj[$col_idx]{"ModuleNickname"};
			else{
				if($group_modules_obj[$col_idx]{"ModuleName"} == "IR")
					$col_title .= $lang['HISTORY_IO']['INTERNAL_REGISTER'];
				else
					$col_title .= $group_modules_obj[$col_idx]{"ModuleName"};
			}
			$col_title .= "<br/>";
			
			if($group_modules_obj[$col_idx]{"ChannelNickname"} == ""){
				preg_match("/(\D+)(\d+)/",$group_modules_obj[$col_idx]{"Channel"},$explodeArray);
				$col_title .= str_replace("%channel%",$explodeArray[2],
					array(
						"DI" => "DI%channel%",
						"DIC" => $lang['HISTORY_IO']['DI_COUNTER_WITH_NO'],
						"DO" => "DO%channel%",
						"DOC" => $lang['HISTORY_IO']['DO_COUNTER_WITH_NO'],
						"AI" => "AI%channel%",
						"AO" => "AO%channel%",
						"CI" => "Discrete Input %channel%",
						"CO" => "Coil Output %channel%",
						"RI" => "Input Register %channel%",
						"RO" => "Holding Register %channel%",
						"IR" => $lang['HISTORY_IO']['INTERNAL_REGISTER_WITH_NO']
					)[$explodeArray[1]]);
			}
			else{
				$col_title .= $group_modules_obj[$col_idx]{"ChannelNickname"};
			}
			//echo $group_modules_obj[$col_idx]{"Unit"};
			if($group_modules_obj[$col_idx]{"Unit"} != ""){
				$col_title .= "<br/>";
				$col_title .= "(".$group_modules_obj[$col_idx]{"Unit"}.")";
			}
			$report_Data_string .= '<td class="header" style="width:400px;">'.$col_title.'</td>';
		}
		$report_Data_string .= '</tr>';
		//取得Data
		$TimeCol='';//時間欄位(Null時查看其他通道是否有值)
		$ChannelCol='';//取得通道欄位
		$ChannelData = '';//每個模組通道值
		$module_info = '';
		$sql = "";
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
					$ChannelData .= " select A.Time AS Time,A.Max_val AS Max_val,A.Min_val AS Min_val,A.Avg_val AS Avg_val,A.Sum_val,B.".$module_channel." AS Final_val from( select DATEPART(".$table_time.", SWITCHOFFSET(subquery1.DateTime,'".$timezone."')) AS Time,MAX(CONVERT(REAL,subquery1.".$module_channel.")) as Max_val,MIN(CONVERT(REAL,subquery1.".$module_channel.")) as Min_val,AVG(CONVERT(REAL,subquery1.".$module_channel.")) as Avg_val,SUM(CONVERT(REAL,subquery1.".$module_channel.")) AS Sum_val from ( select * from ".$module_table_name." Where DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."' ) AS subquery1 GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(subquery1.DateTime,'".$timezone."')) ) AS A left join (select quest1.Time,quest2.".$module_channel." from (select DATEPART(".$table_time.", SWITCHOFFSET(A.DateTime,'".$timezone."')) AS Time,MAX(A.DateTime) AS MaxTime from (select DateTime,".$module_channel." from ".$module_table_name." Where DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."' AND ".$module_channel." IS NOT NULL ) AS A GROUP BY DATEPART(".$table_time.", SWITCHOFFSET(A.DateTime,'".$timezone."'))) AS quest1 left JOIN ".$module_table_name." AS quest2 ON quest2.DateTime=quest1.MaxTime ) AS B ON A.Time=B.Time WHERE Max_val is not null ";
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
					//$ChannelData = "select A.Time AS Time,A.Max_val AS Max_val,A.Min_val AS Min_val,A.Avg_val AS Avg_val,A.Sum_val,B.".$module_channel." AS Final_val from(	select DATE_FORMAT(subquery1.DateTime, '".$table_time_code."') AS Time,MAX(subquery1.".$module_channel.") as Max_val,MIN(subquery1.".$module_channel.") as Min_val,AVG(subquery1.".$module_channel.") as Avg_val,SUM(subquery1.".$module_channel.") AS Sum_val from ( select * from ".$module_table_name." Where DateTime >= '".$datetime_begin."' and DateTime <= '".$datetime_end."' ) AS subquery1 GROUP BY DATE_FORMAT(subquery1.DateTime, '".$table_time_code."') ) AS A left join (select quest1.Time,quest2.".$module_channel." from (select DATE_FORMAT(A.DateTime, '".$table_time_code."') AS Time,MAX(A.DateTime) AS MaxTime from (select DateTime,".$module_channel." from ".$module_table_name." Where DateTime >= '".$datetime_begin."' and DateTime <= '".$datetime_end."' AND ".$module_channel." IS NOT NULL ) AS A GROUP BY DATE_FORMAT(A.DateTime, '".$table_time_code."')) AS quest1 left JOIN ".$module_table_name." AS quest2 ON quest2.DateTime=quest1.MaxTime ) AS B ON A.Time=B.Time WHERE Max_val is not null ";
					$ChannelData = "select A.Time AS Time,A.Max_val AS Max_val,A.Min_val AS Min_val,A.Avg_val AS Avg_val,A.Sum_val AS Sum_val,B.".$module_channel." AS Final_val from( select DATE_FORMAT(CONVERT_TZ(subquery1.DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time,MAX(subquery1.".$module_channel.") as Max_val,MIN(subquery1.".$module_channel.") as Min_val,AVG(subquery1.".$module_channel.") as Avg_val,SUM(subquery1.".$module_channel.") as Sum_val from (  select * from ".$module_table_name." Where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."'  ) AS subquery1  GROUP BY DATE_FORMAT(CONVERT_TZ(subquery1.DateTime,'+00:00','".$timezone."'), '".$table_time_code."') ) AS A left join (select quest1.Time,quest2.".$module_channel." from (select DATE_FORMAT(A.DateTime, '".$table_time_code."') AS Time,MAX(A.DateTime) AS MaxTime from ( select CONVERT_TZ(DateTime,'+00:00','".$timezone."') as DateTime,".$module_channel." from ".$module_table_name." Where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."' AND ".$module_channel." IS NOT NULL) AS A GROUP BY DATE_FORMAT(CONVERT_TZ(A.DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS quest1 left JOIN ".$module_table_name." AS quest2 ON CONVERT_TZ(quest2.DateTime,'+00:00','".$timezone."')=quest1.MaxTime ) AS B ON A.Time=B.Time WHERE Max_val IS NOT NULL  ";
					$ChannelData .= ") As Channel".$i;
				}
				else{
					$TimeCol = ' IFNULL(Channel'.$i.'.Time,'.$TimeCol.') ';
					$ChannelData .= " LEFT JOIN (";
					//$ChannelData .= " select A.Time AS Time,A.Max_val AS Max_val,A.Min_val AS Min_val,A.Avg_val AS Avg_val,A.Sum_val,B.".$module_channel." AS Final_val from(	select DATE_FORMAT(subquery1.DateTime, '".$table_time_code."') AS Time,MAX(subquery1.".$module_channel.") as Max_val,MIN(subquery1.".$module_channel.") as Min_val,AVG(subquery1.".$module_channel.") as Avg_val,SUM(subquery1.".$module_channel.") AS Sum_val from ( select * from ".$module_table_name." Where DateTime >= '".$datetime_begin."' and DateTime <= '".$datetime_end."' ) AS subquery1 GROUP BY DATE_FORMAT(subquery1.DateTime, '".$table_time_code."') ) AS A left join (select quest1.Time,quest2.".$module_channel." from (select DATE_FORMAT(A.DateTime, '".$table_time_code."') AS Time,MAX(A.DateTime) AS MaxTime from (select DateTime,".$module_channel." from ".$module_table_name." Where DateTime >= '".$datetime_begin."' and DateTime <= '".$datetime_end."' AND ".$module_channel." IS NOT NULL ) AS A GROUP BY DATE_FORMAT(A.DateTime, '".$table_time_code."')) AS quest1 left JOIN ".$module_table_name." AS quest2 ON quest2.DateTime=quest1.MaxTime ) AS B ON A.Time=B.Time WHERE Max_val is not null ";
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
					//$ChannelData = "select A.Time AS Time,A.Max_val AS Max_val,A.Min_val AS Min_val,A.Avg_val AS Avg_val,A.Sum_val,B.".$module_channel." AS Final_val from(	select DATE_FORMAT(subquery1.DateTime, '".$table_time_code."') AS Time,MAX(subquery1.".$module_channel.") as Max_val,MIN(subquery1.".$module_channel.") as Min_val,AVG(subquery1.".$module_channel.") as Avg_val,SUM(subquery1.".$module_channel.") AS Sum_val from ( select * from ".$module_table_name." Where DateTime >= '".$datetime_begin."' and DateTime <= '".$datetime_end."' ) AS subquery1 GROUP BY DATE_FORMAT(subquery1.DateTime, '".$table_time_code."') ) AS A left join (select quest1.Time,quest2.".$module_channel." from (select DATE_FORMAT(A.DateTime, '".$table_time_code."') AS Time,MAX(A.DateTime) AS MaxTime from (select DateTime,".$module_channel." from ".$module_table_name." Where DateTime >= '".$datetime_begin."' and DateTime <= '".$datetime_end."' AND ".$module_channel." IS NOT NULL ) AS A GROUP BY DATE_FORMAT(A.DateTime, '".$table_time_code."')) AS quest1 left JOIN ".$module_table_name." AS quest2 ON quest2.DateTime=quest1.MaxTime ) AS B ON A.Time=B.Time WHERE Max_val is not null ";
					$ChannelData = "select A.Time AS Time,A.Max_val AS Max_val,A.Min_val AS Min_val,A.Avg_val AS Avg_val,A.Sum_val AS Sum_val,B.".$module_channel." AS Final_val from( select DATE_FORMAT(CONVERT_TZ(subquery1.DateTime,'+00:00','".$timezone."'), '".$table_time_code."') AS Time,MAX(subquery1.".$module_channel.") as Max_val,MIN(subquery1.".$module_channel.") as Min_val,AVG(subquery1.".$module_channel.") as Avg_val,SUM(subquery1.".$module_channel.") as Sum_val from (  select * from ".$module_table_name." Where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."'  ) AS subquery1  GROUP BY DATE_FORMAT(CONVERT_TZ(subquery1.DateTime,'+00:00','".$timezone."'), '".$table_time_code."') ) AS A left join (select quest1.Time,quest2.".$module_channel." from (select DATE_FORMAT(A.DateTime, '".$table_time_code."') AS Time,MAX(A.DateTime) AS MaxTime from ( select CONVERT_TZ(DateTime,'+00:00','".$timezone."') as DateTime,".$module_channel." from ".$module_table_name." Where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."' AND ".$module_channel." IS NOT NULL) AS A GROUP BY DATE_FORMAT(CONVERT_TZ(A.DateTime,'+00:00','".$timezone."'), '".$table_time_code."')) AS quest1 left JOIN ".$module_table_name." AS quest2 ON CONVERT_TZ(quest2.DateTime,'+00:00','".$timezone."')=quest1.MaxTime ) AS B ON A.Time=B.Time WHERE Max_val IS NOT NULL  ";
					$ChannelData .= ") As Channel".$i;
				}
				else{
					$TimeCol = ' IFNULL(Channel'.$i.'.Time,'.$TimeCol.') ';
					$ChannelData .= " RIGHT JOIN (";
					//$ChannelData .= " select A.Time AS Time,A.Max_val AS Max_val,A.Min_val AS Min_val,A.Avg_val AS Avg_val,A.Sum_val,B.".$module_channel." AS Final_val from( select DATE_FORMAT(subquery1.DateTime, '".$table_time_code."') AS Time,MAX(subquery1.".$module_channel.") as Max_val,MIN(subquery1.".$module_channel.") as Min_val,AVG(subquery1.".$module_channel.") as Avg_val,SUM(subquery1.".$module_channel.") AS Sum_val from ( select * from ".$module_table_name." Where DateTime >= '".$datetime_begin."' and DateTime <= '".$datetime_end."' ) AS subquery1 GROUP BY DATE_FORMAT(subquery1.DateTime, '".$table_time_code."') ) AS A left join (select quest1.Time,quest2.".$module_channel." from (select DATE_FORMAT(A.DateTime, '".$table_time_code."') AS Time,MAX(A.DateTime) AS MaxTime from (select DateTime,".$module_channel." from ".$module_table_name." Where DateTime >= '".$datetime_begin."' and DateTime <= '".$datetime_end."' AND ".$module_channel." IS NOT NULL ) AS A GROUP BY DATE_FORMAT(A.DateTime, '".$table_time_code."')) AS quest1 left JOIN ".$module_table_name." AS quest2 ON quest2.DateTime=quest1.MaxTime ) AS B ON A.Time=B.Time WHERE Max_val is not null ";
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
			}
			$sql = "";
			$sql .= " select ".$TimeCol." AS Time ".$ChannelCol." from ( ";
			$sql .= $ChannelData;
			$sql .= " order by \"TIME\" ";
		}
		
		
		$stmt_data = $conn->prepare($sql);
		$stmt_data->execute();
		
		//----------------------
		$td_class = "odd";
		While($obj = $stmt_data->fetch(PDO::FETCH_ASSOC)){
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
			
			for($i=0; $i<count($group_modules_obj); $i++){
				$col_name = "Col".$i.$col_type;
				$report_Data_string .= '<td class="'.$td_class.'">'.($obj[$col_name]).'</td>';
			}
			$report_Data_string .= '</tr>';
		}
		$report_Data_string .= '<tr></tr>';
		
		//Other
		$sql = "";
		$group_col_array = array();
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
				DB_IS_MSSQL  ? "SELECT MAX(CONVERT(REAL,".$module_channel.")) AS max_val,MIN(CONVERT(REAL,".$module_channel.")) AS min_val,AVG(CONVERT(REAL,".$module_channel.")) AS avg_val,SUM(CONVERT(REAL,".$module_channel.")) AS sum_val FROM ".$module_table_name." Where DateTime >= '".$datetime_begin.$timezone."' and DateTime <= '".$datetime_end.$timezone."'" : (
				DB_IS_MYSQL  ? "SELECT MAX(COALESCE(".$module_channel.", FALSE)) AS max_val,MIN(COALESCE(".$module_channel.", FALSE)) AS min_val,AVG(".$module_channel.") AS avg_val,SUM(".$module_channel.") AS sum_val FROM ".$module_table_name." Where CONVERT_TZ(DateTime,'+00:00','".$timezone."') >= '".$datetime_begin."' and CONVERT_TZ(DateTime,'+00:00','".$timezone."') <= '".$datetime_end."';" : (
				DB_IS_ORACLE ? "SELECT MAX(".$module_channel.") AS max_val,MIN(".$module_channel.") AS min_val,AVG(".$module_channel.") AS avg_val,SUM(".$module_channel.") AS sum_val FROM ".$module_table_name." Where sys_extract_utc(from_tz(DateTime,'".$timezone."')) between TO_TIMESTAMP ('".$datetime_begin."', 'YYYY-MM-DD HH24:MI:SS') and  TO_TIMESTAMP ('".$datetime_end."', 'YYYY-MM-DD HH24:MI:SS');" : null))
			);
			
			$stmt->execute();
			
			While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
				$group_col_val = array($obj["max_val"],$obj["min_val"],$obj["avg_val"],$obj["sum_val"]);
				array_push($group_col_array,$group_col_val);
			}
			$stmt->closeCursor();
		}
		$td_class = "odd";
		
		if($report_type == "DAY")$other_title = array($lang['REPORT_SERVICE']['TODAY_MAXIMUM'],$lang['REPORT_SERVICE']['TODAY_MINIMUM'],$lang['REPORT_SERVICE']['TODAY_AVERAGE'],$lang['REPORT_SERVICE']['TODAY_TOTAL_VALUE']);
		else if($report_type == "WEEK")$other_title = array($lang['REPORT_SERVICE']['THIS_WEEK_MAXIMUM'],$lang['REPORT_SERVICE']['THIS_WEEK_MINIMUM'],$lang['REPORT_SERVICE']['THIS_WEEK_AVERAGE'],$lang['REPORT_SERVICE']['THIS_WEEK_TOTAL_VALUE']);
		else if($report_type == "MONTH")$other_title = array($lang['REPORT_SERVICE']['THIS_MONTH_MAXIMUM'],$lang['REPORT_SERVICE']['THIS_MONTH_MINIMUM'],$lang['REPORT_SERVICE']['THIS_MONTH_AVERAGE'],$lang['REPORT_SERVICE']['THIS_MONTH_TOTAL_VALUE']);
		else if($report_type == "QUARTER")$other_title = array($lang['REPORT_SERVICE']['THIS_QUARTER_MAXIMUM'],$lang['REPORT_SERVICE']['THIS_QUARTER_MINIMUM'],$lang['REPORT_SERVICE']['THIS_QUARTER_AVERAGE'],$lang['REPORT_SERVICE']['THIS_QUARTER_TOTAL_VALUE']);
		else if($report_type == "YEAR")$other_title = array($lang['REPORT_SERVICE']['THIS_YEAR_MAXIMUM'],$lang['REPORT_SERVICE']['THIS_YEAR_MINIMUM'],$lang['REPORT_SERVICE']['THIS_YEAR_AVERAGE'],$lang['REPORT_SERVICE']['THIS_YEAR_TOTAL_VALUE']);
		
		
		
		for($other_idx = 0; $other_idx < count($other_title); $other_idx++){
			$report_Data_string .= '<tr>';
			$report_Data_string .= '<td class="totalHeader">'.$other_title[$other_idx].'</td>';
			for($i=0; $i<count($group_modules_obj); $i++){
				$report_Data_string .= '<td class="'.$td_class.'">'.$group_col_array[$i]{$other_idx}.'</td>';
			}
			$report_Data_string .= '</tr>';
		}
		
		if(isset($stmt_group))
			$stmt_group->closeCursor();
		if(isset($stmt_group_module))
			$stmt_group_module->closeCursor();
		
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
				DB_IS_MSSQL  ? "SELECT ReportHeader, ReportFooter FROM ".$accountname.".dbo.report_template WHERE UID = '".$_GET["template_id"]."';" : (
				DB_IS_MYSQL  ? "SELECT ReportHeader, ReportFooter FROM ".$accountname.".report_template WHERE UID = '".$_GET["template_id"]."';" : (
				DB_IS_ORACLE ? "SELECT ReportHeader, ReportFooter FROM ".$accountname.".report_template WHERE \"UID\" = '".$_GET["template_id"]."';" : null))
			);
			$stmt->execute();
			While($obj = $stmt->fetch(PDO::FETCH_ASSOC)){
				$header_str = $obj["ReportHeader"];
				$footer_str = $obj["ReportFooter"];
			}
		}
	}
?>