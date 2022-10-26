<?php
function customized_header(){
	global $lang;
?>
<link rel="stylesheet" type="text/css" href="./css/list.css">
<link rel="stylesheet" type="text/css" href="./css/tip.css">
<style type="text/css">
/* nav */
.device-nav{
	float:left;
	width:300px;
	border-right: 1px solid #ccc;
	padding-right:20px;
	padding-bottom:20px;
}

.device-nav-title{
	font-weight:bold;
	color:rgb(103, 106, 108);
	margin-bottom:10px;
}

.device-nav-database{
	padding:10px 0 10px 10px;
	position:relative;
	z-index: 1;
    overflow: hidden;
	font-weight:bold;
	color:#3c79dc;
}

.device-nav-database span{
	vertical-align:middle;
}

.device-nav-database:before,
.device-nav-database:after {
    position: absolute;
    top: 51%;
    overflow: hidden;
    width: 100%;
    height: 1px;
    content: '\a0';
    background-color: #CCC;
}

.device-nav-database:before {
    margin-left: -100%;
}

.device-nav-item{
	color:#707070;
	fill:#707070;
	line-height:1.5;
	padding:10px 10px 10px 10px;
	border: 1px solid transparent;
	border-left: 4px solid transparent;
	position:relative;
	cursor: pointer;
}

.device-nav-item.active{
	border: 1px solid #ccc;
	border-left: 4px solid #035002c2;
	background-color:#fff;
	/*box-shadow: 0 0 2px rgba(0,0,0,.12),0 2px 4px rgba(0,0,0,.24);*/
}

.device-nav-item svg,
.device-nav-item span{
	vertical-align:middle;
}

.device-nav-item.active > svg,
.device-nav-item.active > span,
.device-nav-item:hover > svg,
.device-nav-item:hover > span{
	color:#035002c9;
	fill:#2A2A2A;
}

.device-nav-item > svg{
	margin-right:10px;
}

.device-nav-control{
	margin-right:0px;
	float:right;
}

.device-nav-control svg{
	fill:#707070;
}

.device-nav-control svg.warning{
	fill:#ffd454;
}

.device-nav-item.active .device-nav-control svg,
.device-nav-item:hover .device-nav-control svg{
	fill:#2A2A2A;
}

.device-nav-item.active .device-nav-control svg.warning,
.device-nav-item:hover .device-nav-control svg.warning{
	fill:#ffc107;
}

/* list */
.module-list{
	margin-left:320px;
	border-left: 1px solid #ccc;
	padding-left:20px;
	padding-bottom:20px;
}

.module-list-title{
	color:#707070;
	font-weight:bold;
	margin-bottom:10px;
}

.module-list-table{
	width:100%;
	color:#707070;
	border-collapse: collapse;
	border-spacing: 0px;
}

.module-list-table-cell{
	background-color: #FFF;
	border: 1px solid #ccc;
	padding: 12px 14px;
}

.module-list-table-cell.header{
	background: linear-gradient(#035002 0px, #fff 600%);
	font-size: 13px;
	font-weight: bold;
	color: #FFF;

}

.module-list-table-cell.split{
	background: linear-gradient(to bottom, #0350024d, #efefef);
	padding: 6px 14px;
	font-size: 13px;
	font-weight: bold;
	color: #035002a3;
}

.module-list-table-cell:not(.header):not(.split):last-child {
	padding: 6px 6px;
}

.module-list-button{
	padding-right:10px !important;
	padding-left:10px !important;
	width:80px;
}

.module-list-warning{
	position:absolute;
	right:0;
	top:50%;
	margin-top:-9px;
}

.module-list-warning svg{
	fill: #ffc107;
}

/* footer*/
.hr{
	background-color: #CCC;
	height:1px;
	margin-bottom:20px;
	clear:both;
}

#footer{
	text-align:center;
	color: #707070;
	font-size: 13px;
}

/* no exist */
#info-no-exist-container{
	text-align:center;
	color:#707070;
	padding:100px 0;
}

.info-no-exist-title{
	margin-bottom:10px;
	font-weight:bold;
}

.info-no-exist-content{
	font-size:13px;
}

/*icons*/
.icons {
    background-image: url(./image/ics.png);
    background-repeat: no-repeat;
    display: inline-block;
}

.icons-database {
    width: 18px;
    height: 18px;
    background-position: -5px -5px;
}

.device-nav-item:hover .icons-device,
.device-nav-item.active .icons-device {
    width: 18px;
    height: 18px;
    background-position: -33px -5px;
}

.icons-device {
    width: 18px;
    height: 18px;
    background-position: -33px -33px;
}

#clear-database-icon-container:hover .icons-broom,
#clear-database-icon-container.active .icons-broom {
    width: 18px;
    height: 18px;
    background-position: -61px -5px;
}

.icons-broom {
    width: 18px;
    height: 18px;
    background-position: -61px -33px;
}

#clear-database-icon-container{
	position:absolute;
	top:50%;
	margin-top:-9px;
	right:6px;
	padding:0 5px;
	background-color:#FDFDFD;
	z-index:1;
	cursor: pointer;
	user-select: none;
}

#copy-table-icon-container{
	position:absolute;
	top:50%;
	margin-top:-8px;
	right:30px;
	padding:0 5px;
	background-color:#FDFDFD;
	z-index:1;
	cursor: pointer;
	user-select: none;
}

#copy-table-icon-container svg{
	fill: #707070;
}

#copy-table-icon-container:hover svg{
	fill: #2A2A2A;
}

#clear-database-container{
	border: 1px solid #ffd500;
	background-color:#fffbe5;
	margin:5px 0 10px 0;
	position: relative;
	padding:10px;
    line-height: 1.5;
	color: #707070;
	display:none;
	opacity:0;
}

#clear-database-container:after,
#clear-database-container:before {
    content: '';
    display: block;
    position: absolute;
	top:-20px;
    right: 10px;
    width: 0;
    height: 0;
    border-style: solid;
}

#clear-database-container:after {
    border-color: transparent transparent #fffbe5 transparent;
    border-width: 10px;
}

#clear-database-container:before {
	margin-top: -2px;
    margin-right: -1px;
    border-color: transparent transparent #ffd500 transparent;
    border-width: 11px;
}

</style>
<script src="./js/jquery.tip.js"></script>

<?php
}
?>

<?php
function customized_body(){
	global $lang, $language;
?>
<div style="padding:20px;">
    <div id="info-no-exist-container" style="display: none;">
        <div class="info-no-exist-title"><?=$lang['DB_INFO']['NO_DATA_SHEET_EXISTS']?></div>
        <div class="info-no-exist-content"><?=$lang['DB_INFO']['THE_INFORMATION_WILL_BE_DISPLAYED_WHEN_THE_DEVICE_RETURNS_DATA']?></div>
    </div>

	<div id="info-container" style="display:none;">
	    <div class="device-nav" id="devices_menu">
	        <div class="device-nav-title"><?=$lang['DB_INFO']['DEVICE']?></div>
	    </div>
	    <div id="module_list" class="module-list">
			<div id="info-no-exist-container" style="display: none;">
		        <div class="info-no-exist-title"><?=$lang['DB_INFO']['NO_DATA_SHEET_EXISTS']?></div>
		        <div class="info-no-exist-content"><?=$lang['DB_INFO']['THE_INFORMATION_WILL_BE_DISPLAYED_WHEN_THE_DEVICE_RETURNS_DATA']?></div>
		    </div>
			
			<div class="module-list-title"></div>

			<table id="module_list_table" class="module-list-table">
			</table>
	    </div>
		<div class="hr"></div>
		<div id="footer"></div>
	</div>
</div>

<div id="window-copy-table" class="popup-wrapper" style="width:750px;display:none;">
	<div class="popup-container">
		<div class="popup-title"><?=$lang['DB_INFO']['COPY_DATA_TABLE']?></div>
		<div class="popup-content" style="padding:10px 0;box-sizing:border-box;position:relative;">
			<table align="center">
				<tbody>
					<tr>
						<td style="width:80px;"></td>
						<td style="font-weight:bolder;" align="center"><?=$lang['DB_INFO']['SOURCE']?></td>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
						<td style="font-weight:bolder;" align="center"><?=$lang['DB_INFO']['DESTINATION']?></td>
						<td style="width:80px;"></td>
					</tr>
					<tr>
						<td colSpan="5" style="height:0px;"></td>
					</tr>
					<tr>
						<td align="right" style="padding-right:5px;"><span style="white-space:nowrap;"><?=$lang['DB_INFO']['DEVICE']?></span></td>
						<td>
							<select style="width:250px;" id="copySourceDevice" onChange="change_source_device();"></select>
						</td>
						<td></td>
						<td>
							<select style="width:250px;" id="copyDestinationDevice" onChange="change_destination_device();"></select>
						</td>
						<td></td>
					</tr>
				</tbody>
				<tbody id="copyModuleContainer"></tbody>
				<tr>
					<td colSpan="5" style="height:5px;"></td>
				</tr>
				<tr>
					<td colSpan="5" align="center">
						<table cellSpacing="0" cellPadding="0" border="0">
							<tr>
								<td style="font-size:0;padding-right:3px;"><svg style="fill:#f1b500;"><use xlink:href="image/ics.svg#warning"></use></svg></td>
								<td style="color:#f1b500;font-weight:bold;/*text-shadow:1px 1px #f9f9f9;*/"><?=$lang['DB_INFO']['SOURCE_AND_DESTINATION_MODULES_MUST_BE_SAME_MODEL']?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<div style="position:absolute;top:0;right:0;bottom:0;left:0;background-color:rgba(255,255,255,0.9);display:none;" id="copy-loader">
				<div style="position:absolute;top:50%;left:50%;margin-top:-16px;margin-left:-16px;font-size:0;"><img src="./image/loader.gif"></div>
			</div>
		</div>
		<div class="popup-footer">
			<input type="button" value="<?=$lang['DB_INFO']['COPY'];?>" onClick="onClickWindowButton('ok');">&nbsp;&nbsp;<input type="button" class="gray" value="<?=$lang['DB_INFO']['CLOSE'];?>" onClick="onClickWindowButton('cancel');"></span>
		</div>
	</div>
</div>

<script language="JavaScript">

var $xmlElement;

$(document).ready(function(){
	$("#wait-loader").show();
	init();
	$("#wait-loader").hide();
});



var selected_controller_C; 
function init(){
	load_info();
	$($(".device-nav-item")[0]).trigger("onclick");
	
}


function load_info(){
	$.ajax({
		url: "db_info_ajax.php?act=load_info",
		type: "POST",
		data: {},
		async: false,
		success: function(data, textStatus, jqXHR){
			var controller_length = $(data).find("controller").length;
			//var controller_length = 0;
			if(controller_length == 0){
				$("#info-no-exist-container").show();
				$("#info-container").hide();
			}
			else{
				$("#info-no-exist-container").hide();
				$("#info-container").show();
				$xmlElement = $(data).find("info");
				load_Controller_list();
				load_database_usage();
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0){ return; }// include timeout

			alert(jqXHR.responseText);
		}
	});
}

function load_Controller_list(){
	var $c_xmlElement = $xmlElement.find("localdb > controller");
	var str_html = "";
	if($c_xmlElement.length > 0){
		var localdb_Name=$xmlElement.find("localdb").attr("Name");
		var localdb_Nickname=$xmlElement.find("localdb").attr("Nickname");

		str_html += '<div class="device-nav-database">';
		str_html += '<span class="icons icons-database" style="margin:0 10px 0 4px;"></span><span style="margin-right:5px;">';
		str_html += localdb_Name;
		if(localdb_Nickname!="")
			str_html += '('+localdb_Nickname+')';
		str_html += '</span>';
		str_html += '<div tip="<?=$lang["DB_INFO"]["COPY_DATA_TABLE"]?>" tip_position="top" id="copy-table-icon-container" onclick="show_copy_table()"><svg><use xlink:href="image/ics.svg#file_copy"></use></svg></div>';
		str_html += '<div tip="<?=$lang["DB_INFO"]["CLEAR_DATABASE"]?>" tip_position="top" id="clear-database-icon-container" onclick="show_clear_database()"><span class="icons icons-broom"></span></div>';
		str_html += '</div>';

		str_html += '<div id="clear-database-container">';
		str_html += '<?=$lang["DB_INFO"]["CLEAR_DATABASE_DESCRIPTION"]?>';
		str_html += '<table cellSpacing="0" cellPadding="0" border="0" style="margin-top:10px;"><tr><td>';
		str_html += '<select style="margin-right:3px;" id="clear-database-time">';
		str_html += '<option value="-1"><?=$lang["DB_INFO"]["OLDER_THEN_1_MON"]?></option>';
		str_html += '<option value="-3"><?=$lang["DB_INFO"]["OLDER_THEN_3_MON"]?></option>';
		str_html += '<option value="-6"><?=$lang["DB_INFO"]["OLDER_THEN_6_MON"]?></option>';
		str_html += '<option value="-12" selected><?=$lang["DB_INFO"]["OLDER_THEN_1_YEAR"]?></option>';
		str_html += '<option value="-24"><?=$lang["DB_INFO"]["OLDER_THEN_2_YEAR"]?></option>';
		str_html += '<option value="-36"><?=$lang["DB_INFO"]["OLDER_THEN_3_YEAR"]?></option>';
		str_html += '</select>';
		str_html += '</td><td>';
		str_html += '<input type="button" value="<?=$lang["DB_INFO"]["CLEAR"]?>" id="clear-database-button" class="red module-list-button" onclick="clear_database()">';
		str_html += '</td></tr></table>';
		str_html += '</div>';

		for(var controller_num = 0; controller_num < $c_xmlElement.length; controller_num++){
			str_html += '<div class="device-nav-item" db_type="local" DBName=' + $xmlElement.find("localdb").attr("Name") + ' ControllerUID="'+ $($c_xmlElement[controller_num]).attr('UID') +'" onclick="selected_controller(this)">';
			str_html += '<div class="device-nav-control">';
			if($($c_xmlElement[controller_num]).find("[Removed=1]").length > 0){
				str_html += '<div tip="<?=$lang['DB_INFO']['MODULE_HAS_BEEN_REMOVED_FROM_THIS_DEVICE_AND_SOME_TABLES_WILL_NOT_BE_UPDATED']?>" tip_position="top" style="margin-right:5px;float:left;"><svg class="warning"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="image/ics.svg#warning"></use></svg></div>';
			}
			str_html += '<div tip="<?=$lang['DB_INFO']['REMOVE_ALL_RELEVANT_INFORMATION_ABOUT_THIS_DEVICE_INCLUDING_GROUPING']?>" device_uid="'+ $($c_xmlElement[controller_num]).attr('UID') +'" onclick="remove_device(this)" style="float:left;"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="image/ics.svg#delete"></use></svg></div>';
			str_html += '</div>';
			if($($c_xmlElement[controller_num]).attr("Nickname") != "")
				str_html +=	'<span class="icons icons-device" style="margin-right:10px;"></span><span>' + $($c_xmlElement[controller_num]).attr("Nickname") +'</span>';
			else
				str_html +=	'<span class="icons icons-device" style="margin-right:10px;"></span><span>' + $($c_xmlElement[controller_num]).attr("ModelName") +'</span>';
			str_html +=	'</div>';
		}
	}
	
	//share
	for(var share_num = 0; $xmlElement.find("sharedb").length > share_num ;share_num++){
		var $c_xmlElement = $($xmlElement.find("sharedb")[share_num]).find("controller");
				
		if($c_xmlElement.length > 0){
			var sharedb_Name=$($xmlElement.find("sharedb")[share_num]).attr("Name");
			var sharedb_Nickname=$($xmlElement.find("sharedb")[share_num]).attr("Nickname");

			str_html += '<div class="device-nav-database">';
			str_html += '<span class="icons icons-database" style="margin:0 10px 0 4px;"></span><span style="margin-right:5px;">';
			str_html += sharedb_Name;
			if(sharedb_Nickname!="")
				str_html += '('+sharedb_Nickname+')';
			str_html += '</span>';
			str_html += '</div>';

			for(var controller_num = 0; controller_num < $c_xmlElement.length; controller_num++){
				str_html += '<div class="device-nav-item" db_type="share" DBName="'+$($xmlElement.find('sharedb')[share_num]).attr('Name')+'" ControllerUID="'+ $($c_xmlElement[controller_num]).attr('UID') +'" onclick="selected_controller(this)">';
				str_html += '<div class="device-nav-control">';
				if($($c_xmlElement[controller_num]).find("[Removed=1]").length > 0){
					str_html += '<div tip="<?=$lang['DB_INFO']['MODULE_HAS_BEEN_REMOVED_FROM_THIS_DEVICE_AND_SOME_TABLES_WILL_NOT_BE_UPDATED']?>" tip_position="top" style="margin-right:5px;float:left;"><svg class="warning"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="image/ics.svg#warning"></use></svg></div>';
				}
				str_html += '<div tip="<?=$lang['DB_INFO']['REMOVE_ALL_RELEVANT_INFORMATION_ABOUT_THIS_DEVICE_INCLUDING_GROUPING']?>" device_uid="'+ $($c_xmlElement[controller_num]).attr('UID') +'" onclick="remove_device(this)" style="visibility:hidden;float:left;"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="image/ics.svg#delete"></use></svg></div>';
				str_html += '</div>';
				if($($c_xmlElement[controller_num]).attr("Nickname") != "")
					str_html +=	'<span class="icons icons-device" style="margin-right:10px;"></span><span>' + $($c_xmlElement[controller_num]).attr("Nickname") +'</span>';
				else
					str_html +=	'<span class="icons icons-device" style="margin-right:10px;"></span><span>' + $($c_xmlElement[controller_num]).attr("ModelName") +'</span>';
				str_html +=	'</div>';
			}
		}
	}
	$("#devices_menu").append(str_html);
}


function selected_controller(controller_UI){
	var db_type = $(controller_UI).attr("db_type");
	
	selected_controller_C = controller_UI;
	$("#devices_menu .active").removeClass("active");
	$(controller_UI).addClass("active");

	//create Module list
	$($("#module_list> .module-list-title")[0]).html($(controller_UI).find("span").text());
	var $db_xmlElement = $xmlElement.find("[Name=" +$(controller_UI).attr("dbname")+"]");
	var $select_c_xmlElement = $($db_xmlElement).find("[UID="+$(controller_UI).attr('controlleruid')+ "]");
	//var $select_c_xmlElement = $($xmlElement).find("[UID="+$(controller_UI).attr('controlleruid')+ "]");
	//table title
	var str_html = "";
	str_html += '<tr><td class="module-list-table-cell header"><?=$lang['DB_INFO']['MODULE']?></td><td class="module-list-table-cell header" style="width:250px;"><?=$lang['DB_INFO']['TABLE_NAME']?></td><td class="module-list-table-cell header" style="width:1%;" align="center"><?=$lang['DB_INFO']['ACTION']?></td></tr>';
	var ck_have_module = false;
	
	//create Interface
	for(var interface_num = 0; interface_num < $select_c_xmlElement.find("Interface").length; interface_num++){
		var $i_xmlElement = $($select_c_xmlElement.find("Interface")[interface_num]);
		var interface_name = $i_xmlElement.attr("Interface");
		if(interface_name == "XV"){interface_name = "XV-Board";}
		else if(interface_name == "XU"){interface_name = "<?=$lang['DB_INFO']['BUILD_IN']?>";}
		//str_html += '<tr><td class="module-list-table-cell split" colSpan="3">'+interface_name+'</td></tr>';
		var have_interface_bar = false;
		
		//create module
		for(var module_num = 0; module_num < $i_xmlElement.find("Model").length; module_num++){
			var $m_xmlElement = $($i_xmlElement.find("Model")[module_num]);
			var ModelName = ($m_xmlElement.attr("Nickname")!="")?$m_xmlElement.attr("Nickname"): $m_xmlElement.attr("ModelName");
			var ControllerUID = $m_xmlElement.attr("ControllerUID");
			var ModuleUID = $m_xmlElement.attr("ModuleUID");
			var Table_Name = "uid_" + ControllerUID + "_" + ModuleUID;
			var Removed = $m_xmlElement.attr("Removed");
			
			var check_table = "false";
			$.ajax({
				url: "db_info_ajax.php?act=check_table",
				type: "POST",
				data: {
					dbname:$(controller_UI).attr("dbname"),
					table_name:Table_Name
					},
				async: false,
				success: function(data, textStatus, jqXHR){
					check_table = $(data).find("info > check").attr("ck_table");
				},
				error: function(jqXHR, textStatus, errorThrown){
					if(jqXHR.status === 0){ return; }// include timeout

					alert(jqXHR.responseText);
				}
			});
			
			if(check_table){
				ck_have_module = true;
				if(have_interface_bar == false){
					have_interface_bar =true;
					str_html += '<tr><td class="module-list-table-cell split" colSpan="3">'+interface_name+'</td></tr>';
				}
				str_html += '<tr>';
				str_html += '<td class="module-list-table-cell"><div style="position:relative;">'+ModelName;
				if(Removed == "1")
					str_html += '<div class="module-list-warning" tip="<?=$lang['DB_INFO']['MODULE_HAS_BEEN_REMOVED_FROM_THE_DEVICE_AND_THE_TABLE_WILL_NO_LONGER_BE_UPDATED']?>" tip_position="left"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="image/ics.svg#warning"></use></svg></div>';
				str_html += '</div></td>';
				
				str_html += '<td class="module-list-table-cell">' + Table_Name +'</td>';
				if(db_type == "local"){
					if(Removed == 1)
						str_html += '<td  class="module-list-table-cell"><input type="button" value="<?=$lang['DB_INFO']['REMOVE']?>" class="red module-list-button" table_name="'+Table_Name+'" onclick="remove_module(this)"></td>';
					else
						str_html += '<td class="module-list-table-cell"><input type="button" value="<?=$lang['DB_INFO']['CLEAR']?>" class="red module-list-button" table_name="'+Table_Name+'" onclick="clear_table(this)"></td>';
				}
				else{
					str_html += '<td class="module-list-table-cell"><input type="button" class="red module-list-button" value="<?=$lang['DB_INFO']['CLEAR']?>" style="visibility:hidden;"></td>';
				}
				str_html += "</tr>";
			}
		}
	}
	
	//create IR
	var check_ir = "false";
	var ControllerUID = $select_c_xmlElement.attr("UID");
	$.ajax({
			url: "db_info_ajax.php?act=check_table",
			type: "POST",
			data: {
				dbname:$(controller_UI).attr("dbname"),
				table_name:"uid_" + ControllerUID + "_ir"
				},
			async: false,
			success: function(data, textStatus, jqXHR){
				check_ir = $(data).find("info > check").attr("ck_table");
				if(check_ir == "true" || check_ir == "1"){
					str_html += '<tr><td class="module-list-table-cell split" colSpan="3"><?=$lang['DB_INFO']['OTHER']?></td></tr>';
					str_html += '<tr>';
					str_html += '<td class="module-list-table-cell"><?=$lang['DB_INFO']['INTERNAL_REGISTER']?></td>';
					str_html += '<td class="module-list-table-cell">' + "uid_" + ControllerUID + "_ir" +'</td>';
					str_html += '<td class="module-list-table-cell">';
					if(db_type == "local"){
						str_html += '<input type="button" value="<?=$lang['DB_INFO']['CLEAR']?>" class="red module-list-button" table_name="' + "uid_" + ControllerUID + "_ir" +'" onclick="clear_table(this)">';
					}
					else{
						str_html += '<input type="button" value="<?=$lang['DB_INFO']['CLEAR']?>" class="red module-list-button" style="visibility:hidden;">';
					}
					str_html += '</td>';
					str_html += '</tr>';
				}
				
			},
			error: function(jqXHR, textStatus, errorThrown){
				if(jqXHR.status === 0){ return; }// include timeout

				alert(jqXHR.responseText);
			}
		});
		
	//create realtime
	var check_realtime = "false";
	$.ajax({
			url: "db_info_ajax.php?act=check_table",
			type: "POST",
			data: {
				dbname:$(controller_UI).attr("dbname"),
				table_name:"uid_" + ControllerUID + "_realtime"
				},
			async: false,
			success: function(data, textStatus, jqXHR){
				check_realtime = $(data).find("info > check").attr("ck_table");
				if(check_realtime == "true" || check_realtime == "1"){
					if(check_ir == "false")
						str_html += '<tr><td class="module-list-table-cell split" colSpan="3"><?=$lang['DB_INFO']['OTHER']?></td></tr>';
					str_html += '<tr>';
					str_html += '<td class="module-list-table-cell"><?=$lang['DB_INFO']['REAL_TIME_DATA']?></td>';
					str_html += '<td class="module-list-table-cell">' + "uid_" + ControllerUID + "_realtime" +'</td>';
					str_html += '<td class="module-list-table-cell">';
					if(db_type == "local"){
						str_html += '<input type="button" value="<?=$lang['DB_INFO']['CLEAR']?>" class="red module-list-button" table_name="' + "uid_" + ControllerUID + "_realtime" +'" onclick="clear_table(this)">';
					}
					else{
						str_html += '<input type="button" value="<?=$lang['DB_INFO']['CLEAR']?>" class="red module-list-button" style="visibility:hidden;">';
					}
					str_html += '</td>';
					str_html += '</tr>';
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				if(jqXHR.status === 0){ return; }// include timeout

				alert(jqXHR.responseText);
			}
		});
	
	
	if(ck_have_module || check_ir == "true" || check_realtime == "true" || check_ir == "1" || check_realtime == "1"){
		$("#module_list .module-list-title").show();
		$("#module_list #module_list_table").show();
		$(".module-list #info-no-exist-container").hide();
	}
	else{
		$("#module_list .module-list-title").hide();
		$("#module_list #module_list_table").hide();
		$(".module-list #info-no-exist-container").show();
	}
	
	$("#module_list_table").html(str_html);
	
	tip_even();
}
/*
function selected_share_controller(controller_UI){
	selected_controller_C = controller_UI;
	$("#devices_menu .active").removeClass("active");
	$(controller_UI).addClass("active");

	//create Module list
	$($("#module_list> .module-list-title")[0]).html($(controller_UI).find("span").text());
	
	var $db_xmlElement = $xmlElement.find("[Name=" +$(controller_UI).attr("dbname")+"]");
	
	//var $select_c_xmlElement = $($xmlElement).find("[UID="+$(controller_UI).attr('controlleruid')+ "]");
	var $select_c_xmlElement = $($db_xmlElement).find("[UID="+$(controller_UI).attr('controlleruid')+ "]");
	//table title
	var str_html = "";
	str_html += '<tr><td class="module-list-table-cell header"><?=$lang['DB_INFO']['MODULE']?></td><td class="module-list-table-cell header" style="width:250px;"><?=$lang['DB_INFO']['TABLE_NAME']?></td><td class="module-list-table-cell header" style="width:1%;" align="center"><?=$lang['DB_INFO']['ACTION']?></td></tr>';
	var ck_have_module = false;
	
	//create Interface
	for(var interface_num = 0; interface_num < $select_c_xmlElement.find("Interface").length; interface_num++){
		var $i_xmlElement = $($select_c_xmlElement.find("Interface")[interface_num]);
		var interface_name = $i_xmlElement.attr("Interface");
		if(interface_name == "XV"){interface_name = "XV-Board";}
		var have_interface_bar = false;
		
		//create module
		for(var module_num = 0; module_num < $i_xmlElement.find("Model").length; module_num++){
			var $m_xmlElement = $($i_xmlElement.find("Model")[module_num]);
			var ModelName = ($m_xmlElement.attr("Nickname")!="")?$m_xmlElement.attr("Nickname"): $m_xmlElement.attr("ModelName");
			var ControllerUID = $m_xmlElement.attr("ControllerUID");
			var ModuleUID = $m_xmlElement.attr("ModuleUID");
			var Table_Name = "uid_" + ControllerUID + "_" + ModuleUID;
			var Removed = $m_xmlElement.attr("Removed");
			
			var check_table = "false";
			$.ajax({
				url: "db_info_ajax.php?act=check_table",
				type: "POST",
				data: {
					dbname:$(controller_UI).attr("dbname"),
					table_name:Table_Name
					},
				async: false,
				success: function(data, textStatus, jqXHR){
					check_table = $(data).find("info > check").attr("ck_table");
				},
				error: function(jqXHR, textStatus, errorThrown){
					if(jqXHR.status === 0){ return; }// include timeout

					alert(jqXHR.responseText);
				}
			});
			
			if(check_table=="true"){
				ck_have_module = true;
				if(have_interface_bar == false){
					have_interface_bar =true;
					str_html += '<tr><td class="module-list-table-cell split" colSpan="3">'+interface_name+'</td></tr>';
				}
				str_html += '<tr>';
				str_html += '<td class="module-list-table-cell"><div style="position:relative;">'+ModelName;
				if(Removed == "1")
					str_html += '<div class="module-list-warning" tip="<?=$lang['DB_INFO']['MODULE_HAS_BEEN_REMOVED_FROM_THE_DEVICE_AND_THE_TABLE_WILL_NO_LONGER_BE_UPDATED']?>" tip_position="left"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="image/ics.svg#warning"></use></svg></div>';
				str_html += '</div></td>';
				str_html += '<td class="module-list-table-cell">' + Table_Name +'</td>';
				str_html += "</tr>";
			}
		}
	}
	//check ir
	var check_ir = "false";
	var ControllerUID = $select_c_xmlElement.attr("UID");
	$.ajax({
			url: "db_info_ajax.php?act=check_table",
			type: "POST",
			data: {
				dbname:$(controller_UI).attr("dbname"),
				table_name:"uid_" + ControllerUID + "_ir"
			},
			async: false,
			success: function(data, textStatus, jqXHR){
				check_ir = $(data).find("info > check").attr("ck_table");
				if(check_ir == "true"){
					str_html += '<tr><td class="module-list-table-cell split" colSpan="3"><?=$lang['DB_INFO']['OTHER']?></td></tr>';
					str_html += '<tr>';
					str_html += '<td class="module-list-table-cell"><?=$lang['DB_INFO']['INTERNAL_REGISTER']?></td>';
					str_html += '<td class="module-list-table-cell">' + "uid_" + ControllerUID + "_ir" +'</td>';
					str_html += '<td class="module-list-table-cell"><input type="button" value="<?=$lang['DB_INFO']['CLEAR']?>" class="red module-list-button" table_name="' + "uid_" + ControllerUID + "_ir" +'" style="visibility: hidden;" onclick="clear_table(this)"></td>';
					str_html += '</tr>';
				}
				
			},
			error: function(jqXHR, textStatus, errorThrown){
				if(jqXHR.status === 0){ return; }// include timeout

				alert(jqXHR.responseText);
			}
		});
	//check realtime
	var check_realtime = "false";
	$.ajax({
			url: "db_info_ajax.php?act=check_table",
			type: "POST",
			data: {
				dbname:$(controller_UI).attr("dbname"),
				table_name:"uid_" + ControllerUID + "_realtime"
			},
			async: false,
			success: function(data, textStatus, jqXHR){
				check_realtime = $(data).find("info > check").attr("ck_table");
				if(check_realtime == "true"){
					if(check_ir == "false")
						str_html += '<tr><td class="module-list-table-cell split" colSpan="3"><?=$lang['DB_INFO']['OTHER']?></td></tr>';
					str_html += '<tr>';
					str_html += '<td class="module-list-table-cell"><?=$lang['DB_INFO']['REAL_TIME_DATA']?></td>';
					str_html += '<td class="module-list-table-cell">' + "uid_" + ControllerUID + "_realtime" +'</td>';
					str_html += '<td class="module-list-table-cell"><input type="button" value="<?=$lang['DB_INFO']['CLEAR']?>" class="red module-list-button" table_name="' + "uid_" + ControllerUID + "_realtime" +'" style="visibility: hidden;" onclick="clear_table(this)"></td>';
					str_html += '</tr>';
				}
				
			},
			error: function(jqXHR, textStatus, errorThrown){
				if(jqXHR.status === 0){ return; }// include timeout

				alert(jqXHR.responseText);
			}
		});
	if(ck_have_module != 0||check_ir == "true"||check_realtime == "true"){
		$("#module_list .module-list-title").show();
		$("#module_list #module_list_table").show();
		$(".module-list #info-no-exist-container").hide();
	}
	else{
		$("#module_list .module-list-title").hide();
		$("#module_list #module_list_table").hide();
		$(".module-list #info-no-exist-container").show();
	}
	$("#module_list_table").html(str_html);
	
	tip_even();
}
*/
function onClickWindowButton(button){
	if(typeof(windowCallback) == "function"){
		windowCallback(button);
	}
}

function change_source_device(){
	$("#copyModuleContainer").empty();

	var $controller = $("#copySourceDevice option:selected").data("controller");

	var $interfaces = $controller.find("Interface");
	for(var i = 0; i < $interfaces.length; i++){
		var $interface = $($interfaces[i]);

		var $models = $interface.find("Model");
		for(var j = 0; j < $models.length; j++){
			var $model = $($models[j]);

			$("<tr></tr>").attr({
				"device-uid": $controller.attr("UID"),
				"module-uid": $model.attr("ModuleUID")
			}).append(
				$("<td></td>").css({
					"textAlign": "right",
					"whiteSpace": "nowrap",
					"paddingRight": "5px"
				}).text(j == 0 ? $interface.attr("Interface") == "XV" ? "XV-Board" : $interface.attr("Interface") : "")
			).append(
				$("<td></td>").css("paddingLeft", "10px").text($model.attr("Nickname") != "" ? $model.attr("Nickname") : $model.attr("ModelName"))
			).append(
				$("<td></td>").attr("align", "left").css("fontSize", "0px").append($(createSVGIcon("image/ics.svg", "arrow_forward")).css({"width": "30px", "height": "30px"}))
			).append(
				$("<td></td>").append(
					$("<select></select>").css("width", "250px").bind("change", function(){
						$(this).closest("tr").find("div.status > *").hide();
					})
				)
			).append(
				$("<td></td>").css({
					"fontSize": "0px",
					"paddingLeft": "2px"
				}).append(
					$("<div></div>").addClass("status").css("float", "left").append(
						$("<img/>").attr("src", "./image/loader3.gif").addClass("loader").hide()
					).append(
						$(createSVGIcon("image/ics.svg", "done")).css({"width": "30px", "height": "30px", "fill": "#339900"}).attr("class", "success").hide()
					).append(
						$(createSVGIcon("image/ics.svg", "clear")).css({"width": "30px", "height": "30px", "fill": "#cc3300"}).attr("class", "fail").hide()
					)
				)
			).appendTo("#copyModuleContainer");
		}
	}

	// IR
	$("<tr></tr>").attr({
		"device-uid": $controller.attr("UID"),
		"module-uid": "ir"
	}).append(
		$("<td></td>").css({
			"textAlign": "right",
			"whiteSpace": "nowrap",
			"paddingRight": "5px"
		}).text("<?=$lang['DB_INFO']['OTHER']?>")
	).append(
		$("<td></td>").css("paddingLeft", "10px").text("<?=$lang['DB_INFO']['INTERNAL_REGISTER']?>")
	).append(
		$("<td></td>").attr("align", "center").css("fontSize", "0px").append($(createSVGIcon("image/ics.svg", "arrow_forward")).css({"width": "30px", "height": "30px"}))
	).append(
		$("<td></td>").append(
			$("<select></select>").css("width", "250px").bind("change", function(){
				$(this).closest("tr").find("div.status > *").hide();
			})
		)
	).append(
		$("<td></td>").css({
			"fontSize": "0px",
			"paddingLeft": "2px"
		}).append(
			$("<div></div>").addClass("status").css("float", "left").append(
				$("<img/>").attr("src", "./image/loader3.gif").addClass("loader").hide()
			).append(
				$(createSVGIcon("image/ics.svg", "done")).css({"width": "30px", "height": "30px", "fill": "#339900"}).attr("class", "success").hide()
			).append(
				$(createSVGIcon("image/ics.svg", "clear")).css({"width": "30px", "height": "30px", "fill": "#cc3300"}).attr("class", "fail").hide()
			)
		)
	).appendTo("#copyModuleContainer");

	change_destination_device();
}

function change_destination_device(){
	var $controller = $("#copyDestinationDevice option:selected").data("controller");
	var $selectors = $("#copyModuleContainer select").empty();

	$("#copyModuleContainer").find("div.status > *").hide();

	$("<option></option>").text("None").appendTo($selectors);

	var $interfaces = $controller.find("Interface");
	for(var i = 0; i < $interfaces.length; i++){
		var $interface = $($interfaces[i]);

		$("<option></option>").css({
			"background": "#e5e9eb",
			"fontWeight": "bold",
			"color": "#707070"
		}).attr("disabled", true).text($interface.attr("Interface") == "XV" ? "XV-Board" : $interface.attr("Interface")).appendTo($selectors);

		var $models = $interface.find("Model");
		for(var j = 0; j < $models.length; j++){
			var $model = $($models[j]);

			$("<option></option>").text($model.attr("Nickname") != "" ? $model.attr("Nickname") : $model.attr("ModelName")).attr({
				"device-uid": $controller.attr("UID"),
				"module-uid": $model.attr("ModuleUID")
			}).appendTo($selectors);
		}
	}

	// IR
	$("<option></option>").css({
		"background": "#e5e9eb",
		"fontWeight": "bold",
		"color": "#707070"
	}).attr("disabled", true).text("<?=$lang['DB_INFO']['OTHER']?>").appendTo($selectors);

	$("<option></option>").text("<?=$lang['DB_INFO']['INTERNAL_REGISTER']?>").attr({
		"device-uid": $controller.attr("UID"),
		"module-uid": "ir"
	}).appendTo($selectors);
	
	$selectors.each(function(){
		$(this).find("option[module-uid='" + $(this).closest("tr").attr("module-uid") + "']").attr("selected", true);
	});
}

function show_copy_table(){
	$("#popup-window-content").empty().append(
		$("#window-copy-table").clone().show()
	);

	$("#popup-window-background").show();

	popupWindow($("#popup-window-content"), function(result){
		if(result == "ok"){
			$("#popup-window-content").find("input, select").attr("disabled", true);
			$("#copyModuleContainer").find("div.status > *").hide();

			var deferreds = [];

			var $selectors = $("#copyModuleContainer select");

			$selectors.each(function(){
				var $tr = $(this).closest("tr");
				var sourceDeviceUID = $tr.attr("device-uid");
				var sourceModuleUID = $tr.attr("module-uid");
				var destinationDeviceUID = $(this).find("option:selected").attr("device-uid");
				var destinationModuleUID = $(this).find("option:selected").attr("module-uid");

				if(destinationDeviceUID && destinationModuleUID){
					$tr.find(".loader").show();

					deferreds.push($.ajax({
						url: "db_info_ajax.php?act=copy_table",
						type: "POST",
						data: {
							"source_device_uid": sourceDeviceUID,
							"source_module_uid": sourceModuleUID,
							"destination_device_uid": destinationDeviceUID,
							"destination_module_uid": destinationModuleUID
						},
						success: function(data, textStatus, jqXHR){
							$tr.find(".loader").hide();

							var $info = $(data).find("info");
							if($info.attr("result") == "OK"){
								$tr.find(".success").show().parent("div").attr({
									"tip": "<?=$lang['DB_INFO']['COPY_SUCCESSFULLY']?>",
									"tip_position": "right"
								});
							}
							else{
								var $tip = $tr.find(".fail").show().parent("div").attr({
									"tip_color": "red",
									"tip_position": "right"
								});

								if($info.attr("error") == "MODEL_NOT_MATCH"){
									$tip.attr("tip", "<?=$lang['DB_INFO']['COPY_FAILED_SOURCE_AND_DESTINATION_MODULES_NOT_SAME_MODEL']?>");
								}
								else if($info.attr("error") == "DATA_DUPLICATE"){
									$tip.attr("tip", "<?=$lang['DB_INFO']['COPY_FAILED_DATA_ALREADY_EXIST_IN_DESTINATION_MODULE_TABLE']?>");
								}
								else if($info.attr("error") == "SPACE_NOT_ENOUGH"){
									$tip.attr("tip", "<?=$lang['DB_INFO']['COPY_FAILED_NOT_ENOUGH_FREE_SPACE']?>");
								}
								else{
									$tip.attr("tip", "<?=$lang['DB_INFO']['COPY_FAILED_UNHANDLED_EXCEPTION']?>");
								}

								tip_even();
							}
						},
						error: function(jqXHR, textStatus, errorThrown){
							if(jqXHR.status === 0){ return; }// include timeout

							alert(jqXHR.responseText);
						}
					}));
				}
			});

			$.when.apply($, deferreds).always(function(){
				$("#popup-window-content").find("input, select").attr("disabled", false);
			});
		}
		else{
			$("#popup-window-content, #popup-window-background").hide();
		}
	});

	$("#copySourceDevice, #copySourceModule").empty();
	$("#copyDestinationDevice, #copyDestinationModule").empty();

	$("#copy-loader").show();
	// ajax start
	$.ajax({
		url: "db_info_ajax.php?act=load_info",
		type: "POST",
		success: function(data, textStatus, jqXHR){
			var $controllers = $(data).find("info > localdb > controller");
			for(var i = 0; i < $controllers.length; i++){
				var $controller = $($controllers[i]);
				$("<option></option>").text($controller.attr("Nickname") + "(" + $controller.attr("UID") + ")").data("controller", $controller).appendTo("#copySourceDevice");
				$("<option></option>").text($controller.attr("Nickname") + "(" + $controller.attr("UID") + ")").data("controller", $controller).appendTo("#copyDestinationDevice");
			}

			$("#copySourceDevice").trigger("change");
			$("#copyDestinationDevice").trigger("change");

			$("#copy-loader").hide();
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0){ return; }// include timeout

			alert(jqXHR.responseText);
		}
	});
}

function show_clear_database(){
	if($("#clear-database-container").is(":visible")){
		$("#clear-database-icon-container").removeClass("active")
		$("#clear-database-container").slideUp("fast").animate({opacity: 0}, {queue: false, duration: "fast"});
	}
	else{
		$("#clear-database-icon-container").addClass("active")
		$("#clear-database-container").slideDown("fast").animate({opacity: 1}, {queue: false, duration: "fast"});
	}
}

function clear_database(){
	popupConfirmWindow("<?=$lang['DB_INFO']['MAKE_SURE_YOU_WANT_TO_CLEAR_DATABASE_DATA']?>", function(){
		$("#wait-loader").show();

		$.ajax({
			url: "db_info_ajax.php?act=clear_database",
			type: "POST",
			data: {
				time: $("#clear-database-time").val()
			},
			success: function(data, textStatus, jqXHR){
				var $xmlElement = $(data).find("info > clear");
				var reply = $($xmlElement[0]).attr("reply");
				if(reply == "OK"){
					popupSuccessWindow("<?=$lang['DB_INFO']['CLEAR_COMPLETE']?>");
					load_database_usage();
				}
				else{
					popupErrorWindow("<?=$lang['DB_INFO']['FAILED_TO_CLEAR']?>");
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				if(jqXHR.status === 0){ return; }// include timeout

				alert(jqXHR.responseText);
			},
			complete: function(){
				$("#wait-loader").hide();
			}
		});
	});
}

function clear_table(bt_clear){
	popupConfirmWindow("<?=$lang['DB_INFO']['MAKE_SURE_YOU_WANT_TO_CLEAR_THIS_MODULE_DATA']?>",function(){
		$("#wait-loader").show();
		$.ajax({
				url: "db_info_ajax.php?act=clear_table",
				type: "POST",
				data: {
					table_name:$(bt_clear).attr("table_name")
				},
				success: function(data, textStatus, jqXHR){
					var $xmlElement = $(data).find("info > clear");
					var reply = $($xmlElement[0]).attr("reply");
					if(reply == "OK"){
						popupSuccessWindow("<?=$lang['DB_INFO']['CLEAR_COMPLETE']?>");
						load_database_usage();
					}
					else
						popupErrorWindow("<?=$lang['DB_INFO']['FAILED_TO_CLEAR']?>");
				},
				error: function(jqXHR, textStatus, errorThrown){
					//popupErrorWindow("<?=$lang['DB_INFO']['FAILED_TO_CLEAR']?>");
					if(jqXHR.status === 0){ return; }// include timeout

					alert(jqXHR.responseText);
				},
				complete: function(){
					$("#wait-loader").hide();
				}
				
			});			
		},
		function(){}
	);
}

function remove_module(bt_remove){
	var table_name = $(bt_remove).attr("table_name");
	var device_UID = table_name.split("_")[1];
	var module_UID = table_name.split("_")[2];
	popupConfirmWindow("<?=$lang['DB_INFO']['MAKE_SURE_YOU_WANT_TO_REMOVE_THIS_MODULE']?>",function(){
		$("#wait-loader").show();
		$.ajax({
				url: "db_info_ajax.php?act=remove_module",
				type: "POST",
				//async: true,
				data: {
					table_name:$(bt_remove).attr("table_name"),
					device_uid:device_UID,
					module_uid:module_UID
				},
				success: function(data, textStatus, jqXHR){
					var $xmlElement = $(data).find("info > remove");
					var reply = $($xmlElement[0]).attr("reply");
					if(reply == "OK"){
						popupSuccessWindow("<?=$lang['DB_INFO']['REMOVE_COMPLETE']?>");
						load_database_usage();
					}
					else
						popupErrorWindow("<?=$lang['DB_INFO']['FAILED_TO_REMOVE']?>");
					
					str_html = ' <div class="device-nav-title"><?=$lang['DB_INFO']['DEVICE']?></div> ';
					$("#devices_menu").html(str_html);
					
					load_info();
					$($("div [controlleruid="+$(selected_controller_C).attr("controlleruid")+"]")[0]).trigger("onclick");
				},
				error: function(jqXHR, textStatus, errorThrown){
					//popupErrorWindow("<?=$lang['DB_INFO']['FAILED_TO_REMOVE']?>");
					if(jqXHR.status === 0){ return; }// include timeout

					alert(jqXHR.responseText);
				},
				complete: function(){
					$("#wait-loader").hide();
				}
			});			
		},
		function(){}
	);
}

function remove_device(device){

	var device_uid = $(device).attr("device_uid");
	popupConfirmWindow("<?=$lang['DB_INFO']['MAKE_SURE_YOU_WANT_TO_REMOVE_THIS_DEVICE_DATA']?>",
		function(data, textStatus, jqXHR){
			$("#wait-loader").show();
			$.ajax({
				url: "db_info_ajax.php?act=remove_device",
				type: "POST",
				data: {
					device_uid:device_uid
				},
				success: function(data, textStatus, jqXHR){
					var $xmlElement = $(data).find("info > remove");
					var reply = $($xmlElement[0]).attr("reply");
					if(reply == "OK"){
						popupSuccessWindow("<?=$lang['DB_INFO']['REMOVE_COMPLETE']?>");
						load_database_usage();
					}
					else
						popupErrorWindow("<?=$lang['DB_INFO']['FAILED_TO_REMOVE']?>");
					
					str_html = ' <div class="device-nav-title"><?=$lang['DB_INFO']['DEVICE']?></div> ';
					$("#devices_menu").html(str_html);

					load_info();
					$($(".device-nav-item")[0]).trigger("onclick");
				},
				error: function(jqXHR, textStatus, errorThrown){
					//popupErrorWindow("<?=$lang['DB_INFO']['FAILED_TO_REMOVE']?>");
					if(jqXHR.status === 0){ return; }// include timeout

					alert(jqXHR.responseText);
				},
				complete: function(){
					$("#wait-loader").hide();
				}
			});	
		},
		function(){}
	);
	
	event.stopPropagation();
	
}

function load_database_usage(){
	var toReadableMegaByteString = function(megaBytes){//(int)MB
		if(isNaN(megaBytes)){
			return null;
		}
		else{
			var retObject = {
				"join": function(string){
					return this.megaBytes + string + this.unit;
				}
			};

			if(megaBytes < 1024){
				retObject.megaBytes = megaBytes.toString();
				retObject.unit = "MB";
			}
			else{
			    var i = -1;
			    var unitArray = ["GB", "TB", "PB", "EB", "ZB", "YB"];

			    do {
			        megaBytes /= 1024;
			        i++;
			    } while (megaBytes > 1024);

				retObject.megaBytes = megaBytes.toFixed(1);
				retObject.unit = unitArray[i];
			}

			return retObject;
		}
	};

	$.ajax({
		url: "db_info_ajax.php?act=database_usage",
		type: "POST",
		success: function(data, textStatus, jqXHR){
			var xmlDB = $(data).find("info > db");
			var max_size = Math.round(parseFloat(xmlDB.attr("max_size")) * 100) / 100;
			var size = Math.round(parseFloat(xmlDB.attr("size")) * 100) / 100;

			if(max_size < 0){
				$("#footer").text("<?=$lang['DB_INFO']['DATABASE_USAGE']?>".replace("%size%", toReadableMegaByteString(size).join(" ")));
			}
			else{
				$("#footer").text("<?=$lang['DB_INFO']['DATABASE_USAGE_WITH_MAXSIZE']?>".replace("%size%", toReadableMegaByteString(size).join(" ")).replace("%max_size%", toReadableMegaByteString(max_size).join(" ")).replace("%percent%", Math.floor(size / max_size * 100)));
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0){ return; }// include timeout

			alert(jqXHR.responseText);
		}
	});
}

function tip_even(){
	$("*[tip]").unbind("mouseenter").unbind("mouseleave").hover(
		function(){
			var that = this;
			$(this).attr(
				"pid",
				setTimeout(function(){
					$(that).showTip({"tip": $(that).attr("tip"), "position": $(that).attr("tip_position") ? $(that).attr("tip_position") : "top", "color": $(that).attr("tip_color") ? $(that).attr("tip_color") : "black"});
				}, 100)
			);
		},
		function(){
			clearTimeout($(this).attr("pid"));
			$(this).hideTip();
		}
	);
}

function popupWindow($popupWindow, callback){
	var $wrapper = $popupWindow.show().find(".popup-wrapper");
	$wrapper.css("marginTop", ($(window).scrollTop() + 100) + "px");
	$wrapper.css("marginBottom", "100px");
	$wrapper.css("marginLeft", ($(window).width() / 2 - $wrapper.outerWidth() / 2 + $(window).scrollLeft()) + "px");

	windowCallback =  callback;
}

function createSVGIcon(path, name){
	var svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
	var use = document.createElementNS("http://www.w3.org/2000/svg", 'use');
	use.setAttributeNS("http://www.w3.org/1999/xlink", 'xlink:href', path + '#' + name);
	svg.appendChild(use);
	return svg;
}
</script>
<?php
}
?>