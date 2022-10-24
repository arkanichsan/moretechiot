<?php
function customized_header(){
	global $lang, $language;
?>
<link rel="stylesheet" type="text/css" href="./css/tip.css">
<style type="text/css">
/*.search-field-wrapper{
	float:right;
	position:relative;
	bottom:6px;
}

.search-field-wrapper .search-field-icon{
	position:absolute;
	left:6px;
	top:50%;
	margin-top:-9px;
	border-right: 1px solid #ccc;
	padding-right:5px;
	font-size:0;
}

.search-field-wrapper .search-field-input{
	width:200px;
	padding-left:36px;
	padding-right:25px;
}

.search-field-wrapper .search-field-clear{
	position:absolute;
	right:6px;
	top:50%;
	margin-top:-9px;
	cursor: pointer;
	font-size:0;
}*/

/**/
#search-field-wrapper{
	float:right;
	position:relative;
	bottom:6px;
}

#search-field-wrapper button{
	padding:5.5px;
	font-size:0;
	float:left;
}

#search-field-wrapper button.cover{
	position:absolute;
	opacity:0;
	top:0;
	left:0;
	visibility:hidden;
	cursor: default;
}

#search-field-wrapper.expand button.cover,
#search-field-wrapper.expand button.cover{
	visibility: visible;
}

#search-field-wrapper #module-filter{
	float:left;
	border-radius:0 3px 3px 0;
	box-shadow:none !important;
	border-left-width:0 !important;
	border-color: #B3B3B3 !important;
	display:none;
}

#search-field-wrapper.expand button{
	border-radius:3px 0 0 3px;
}

#search-field-wrapper.active button,
#search-field-wrapper.active #module-filter,
#search-field-wrapper.hover button,
#search-field-wrapper.hover #module-filter{
	border-color: #7D7D7D !important;
}

#search-field-wrapper #module-filter-clear{
	position:absolute;
	right:6px;
	top:50%;
	margin-top:-9px;
	cursor: pointer;
	font-size:0;
	display:none;
}

.module-block{
	width:290px;
	height:100px;
	background-color: #fff;
	border: 1px solid #ccc;
	padding: 10px;
	border-bottom-width: 3px;
	border-bottom-style: solid;
	background-image: url('./image/bg_module.png');
	background-repeat: no-repeat;
	background-position: 95% 100%;
	float:left;
	margin: 0 10px 10px 0;
	position:relative;
}

.module-block:hover{
	border-top-color:#035002;
	border-right-color:#035002;
	border-left-color:#035002;
	/*box-shadow: 0 1px 6px rgba(0,0,0,0.175);*/
}

.module-block.wise{
	border-bottom-color: #035002;
}

.module-block.pmc{
	border-bottom-color: #448A3E;
}

.module-block-title{
	font-size:20px;
	padding-bottom: 5px;
	font-weight:bold;

	text-overflow:ellipsis;
	overflow: hidden;
	white-space: nowrap; 
}

.module-block-share{
	float:right;
}

.module-block-content{
	color:#444;
}

.module-block-control{
	position:absolute;
	bottom:0px;
	left:0px;
	margin: 0 0 10px 10px;
}

.module-block-control-button{
	padding: 3px;
    margin-right: 5px;
	height:auto;
	font-size:0;
	cursor: pointer;
}

.module-block-control-button.red svg{
	fill:#FFF;
}

.module-text{
	padding:40px 0;
	text-align:center;
	color:rgb(192, 192, 195);
	font-size:160%;
	font-weight:bold;
	display:block;
}

/*******************/
.scroll-table {
    /*width: 100%;*/
	/*height:100px;*/
    /*border-collapse: collapse;*/
    border-spacing: 0;
}

.scroll-table thead tr th { 
    text-align: left;
    background: linear-gradient(to bottom, #ffffff, #efefef);
    font-size: 13px;
    font-weight: bold;
	border-bottom: 1px solid #ccc;
}

.scroll-table tbody {
    border-top: 1px solid #ccc;
}

.scroll-table tbody td, .scroll-table thead th {
    border-right: 1px solid #ccc;
	padding: 3px 14px;

}

.scroll-table thead th{
	padding: 6px 10px;
}

.scroll-table tbody td{
	padding: 3px 10px;
	border-bottom: 1px solid #ccc;
}

.scroll-table.firmware thead th{
	padding: 10px;
}

.scroll-table.firmware tbody td{
	padding: 10px;
}

.scroll-table tbody td:last-child, .scroll-table thead th:last-child {
    border-right: none;
}

.scroll-table tbody tr:last-child td{
/*
	border-bottom: none;
*/
}

#window-rule-manager{
	width:500px;
	display:none;
}

#rule-manager-container{
	display:block;
	max-height:400px;
	height:400px;
	position:relative;
	overflow-x: hidden;
    overflow-y: scroll;
}

#rule-manager-loader,
#firmware-device-loader{
	position:absolute;
	top:50%;
	left:50%;
	margin-top:-16px;
	margin-left:-16px;
}

#rule-manager-empty-wrapper,
#firmware-device-empty-wrapper{
	display:table;
	height:100%;
	width:100%;
}

#rule-manager-empty,
#firmware-device-empty{
	display:table-cell;
	vertical-align:middle;
	text-align:center;
}

.rule-manager-restore-button{
	font-size: 13px !important;
	height:25px !important;
	width:70px !important;
	padding:0 !important;
}

.firmware-update-method{
	padding:0px 10px;
	margin-bottom:20px;
	display:table-cell;
	vertical-align:middle;
	width:100%;
	height:120px;
	border:1px #e5e5e5 solid;
	cursor:pointer;
}

.firmware-update-method:hover{
	border:1px #bfddf5 solid;
}

#window-firmware-update{
	width:600px;
	display:none;
}

#firmware-update-method-container{
	padding:20px;
	box-sizing:border-box;
	display:block;
}

.firmware-update-method-title{
	font-weight:bold;
	color:#a11e01;
}

.firmware-update-method-description{
	color:#707070;
	line-height:1.5;
}

#firmware-file-name-field-container{
	padding:20px;
	box-sizing:border-box;
	display:none;
}

.firmware-file-name-field-description{
	color:#f1b500;
	font-weight:bold;
	/*text-shadow:1px 1px #f9f9f9;*/
}

#firmware-device-selector-container{
	box-sizing:border-box;
	display:none;
	max-height:500px;
	height:500px;
	position:relative;
	overflow-x: hidden;
    overflow-y: scroll;
}

/* checkbox */
.checkbox {
	position: relative;
}

.checkbox label {
	width: 15px;
	height: 15px;
	cursor: pointer;
	position: relative;
	display:block;
	background: linear-gradient(to bottom, #ededed 0%, #e5e5e5 20%, #dedede 100%);
	border-radius: 2px;
	border: 1px solid #a5a5a5;
}

.checkbox label:after {
	content: '';
	width: 7px;
	height: 4px;
	position: absolute;
	top: 3px;
	left: 3px;
	border: 2px solid #333;
	border-top: none;
	border-right: none;
	background: transparent;
	opacity: 0;
	transform: rotate(-45deg);
}

.checkbox input[type=checkbox] {
	display:none;
}

.checkbox input[type=checkbox]:checked + label:after {
	opacity: 1;
}

.checkbox input[type=checkbox]:disabled + label {
	opacity: 0.5;
	cursor: default;
}

/* Progress bar */
.firmware-device-progress-container{
	background-color: #EEE;
	box-shadow: inset 0px 1px 3px rgba(0,0,0,0.2);
	width:100%;
	height:17px;
	line-height:17px;
	position: relative;
}

.firmware-device-progress-bar{
	display: block;
	float: left;
	background: #88b6ff;
	box-shadow: inset 0px -1px 2px rgba(0, 0, 0, 0.1);
	transition: width 0.8s ease-in-out;

	background-image: -webkit-linear-gradient(135deg, rgba(255, 255, 255, 0.125) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.125) 50%, rgba(255, 255, 255, 0.125) 75%, transparent 75%, transparent);
	background-image: linear-gradient(-45deg, rgba(255, 255, 255, 0.125) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.125) 50%, rgba(255, 255, 255, 0.125) 75%, transparent 75%, transparent);
	background-size: 35px 35px;

	animation: cssProgressActive 2s linear infinite;
	width:0%;
	height:100%;

}

.firmware-device-progress-text{
	position:absolute;
	top:0;
	bottom:0;
	left:0;
	right:0;
	text-align:center;
/*    color: #000000;*/
/*    text-shadow: 1px 1px rgba(255, 255, 255, 0.5);*/
	text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white;
/*	font-size:small;*/
	font-weight:bold;
}

@keyframes cssProgressActive {
	0% {
		background-position: 0 0;
	}
	100% {
		background-position: 35px 35px;
	}
}
</style>
<script src="./js/jquery.tip.js"></script>
<script src="./js/jquery.livequery.min.js"></script>
<script src="./js/jquery.floatThead.min.js"></script>
<script src="./js/AIM.js"></script>
<script language="JavaScript">
var jqXHRRuleList = null;
var jqXHRDeviceList = null;
var jqXHRModuleList = null, pidModuleList = null;

function toReadableByteString(bytes){//(int)bytes
	if(isNaN(bytes)){
		return null;
	}
	else{
		var retObject = {
			"join": function(string){
				return this.bytes + string + this.unit;
			}
		};

		if(bytes < 1024){
			retObject.bytes = bytes.toString();
			retObject.unit = "B";
		}
		else{
		    var i = -1;
		    var unitArray = ["KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];

		    do {
		        bytes /= 1024;
		        i++;
		    } while (bytes > 1024);

			retObject.bytes = bytes.toFixed(1);
			retObject.unit = unitArray[i];
		}

		return retObject;
	}
};

function showRuleManagerWindow(serialNumber){
	$("#popup-window-content").empty().append(
		$("#window-rule-manager").clone(true).show()
	);

	$("#popup-window-background").show();

	popupWindow($("#popup-window-content"), function(result){
		if(result == "close"){
			if(jqXHRRuleList != null){
				jqXHRRuleList.abort();
			}

			$("#popup-window-content, #popup-window-background").hide();
		}
	});

	$("#rule-manager-loader").show();
	$("#rule-manager-table").hide();
	$("#rule-manager-empty-wrapper").hide();
	$("#rule-manager-table-body").empty();

	jqXHRRuleList = $.ajax({
		url: "control_ajax.php?act=get_rule_list&serial_number=" + serialNumber,
		type: "POST",
		dataType: "xml",
		success: function(data, textStatus, jqXHR){
			var $rules = $(data).find("rules > rule");

			if($rules.length > 1){
				for(var i = 1; i < $rules.length; i++){
					var $rule = $($rules[i]);

					$("#rule-manager-table-body").append(
						$("<tr></tr>").append(
							$("<td></td>").text(
								utcToLocalTime(parseInt($rule.attr("date_time"), 10) * 1000)
							)
						).append(
							$("<td></td>").css("width", "100px").text(toReadableByteString(parseInt($rule.attr("size"))).join(" "))
						).append(
							$("<td></td>").css({
								"width": "1%",
								"padding": "3px"
							}).append(
								$("<input type='button'/>").addClass("rule-manager-restore-button").val("<?=$lang['CONTROL']['RESTORE'];?>").bind("click", {"ruleUID": $rule.attr("uid")}, function(event){
									popupConfirmWindow("<?=$lang['CONTROL']['POPUP']['ARE_YOU_SURE_RESTORE_SETTING_FILE'];?>", function(){
										$("#wait-loader").show();

										$.ajax({
											url: "control_ajax.php?act=restore_rule_list&serial_number=" + serialNumber + "&rule_uid=" + event.data.ruleUID,
											type: "POST",
											dataType: "xml",
											success: function(data, textStatus, jqXHR){
												var $docCMD = $(data).find("cmd");
												if($docCMD.length > 0){
													if($docCMD.attr("result") == "OK"){
														popupSuccessWindow("<?=$lang['CONTROL']['POPUP']['RESTORE_SUCCESSFULLY'];?>");
													}
													else if($docCMD.attr("result") == "ERROR"){
														if($docCMD.attr("error_code") == "1"){
															popupErrorWindow("<?=$lang['CONTROL']['POPUP']['PERMISSION_DENIED'];?>");
														}
														else if($docCMD.attr("error_code") == "2"){
															popupErrorWindow("<?=$lang['CONTROL']['POPUP']['DEVICE_OFFLINE'];?>");
														}
														else if($docCMD.attr("error_code") == "3"){
															popupErrorWindow("<?=$lang['CONTROL']['POPUP']['SETTING_FILE_REMOVED'];?>");
														}
													}
												}
											},
											error: function(jqXHR, textStatus, errorThrown){
												if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

												alert(jqXHR.responseText);
											},
											complete: function(){
												$("#wait-loader").hide();
												$("#popup-window-content, #popup-window-background").hide();
											}
										});
									});
								})
							)
						)
					);
				}

				$("#rule-manager-table").show().floatThead({
					scrollContainer: function($table){
						return $("#rule-manager-container");
					}
				});
			}
			else{
				$("#rule-manager-empty-wrapper").show();
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

			alert(jqXHR.responseText);
		},
		complete: function(){
			$("#rule-manager-loader").hide();
		}
	});
}

function removeDevice(serialNumber){
	$("#wait-loader").show();

	$.ajax({
		url: "control_ajax.php?act=remove_device&serial_number=" + serialNumber,
		type: "POST",
		dataType: "xml",
		success: function(data, textStatus, jqXHR){
			if($(data).find("cmd").attr("result") == "OK"){
				popupSuccessWindow("<?=$lang['CONTROL']['POPUP']['REMOVE_SUCCESSFULLY'];?>");
				loadDeviceList();
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

			alert(jqXHR.responseText);
		},
		complete: function(){
			$("#wait-loader").hide();
		}
	});
}

function setCookie(name, value, expiresTime){//expiresTime in minutes
	var expires = new Date();
	expires.setTime(expires.getTime() + expiresTime * 60 * 1000);
	document.cookie = name + "=" + escape(value) + ((typeof(expiresTime) == "undefined") ? "" : ";expires=" + expires.toGMTString());
}

function createSVGIcon(path, name){
	var svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
	var use = document.createElementNS("http://www.w3.org/2000/svg", 'use');
	use.setAttributeNS("http://www.w3.org/1999/xlink", 'xlink:href', path + '#' + name);
	svg.appendChild(use);
	return svg;
}

function loadDeviceList(){
	if(jqXHRModuleList != null){
		jqXHRModuleList.abort();
	}

	if(pidModuleList != null){
		clearTimeout(pidModuleList);
	}

	jqXHRModuleList = $.ajax({
		url: "control_ajax.php?act=get_module_list",
		type: "POST",
		dataType: "xml",
		success: function(data, textStatus, jqXHR){
			var onlineCounter = 0;
			var $modules = $(data).find("cmd > module");

			$("#module-container-online, #module-container-offline").empty();
			for(var i = 0; i < $modules.length; i++){
				var $module = $($modules[i]);

				var moduleType = "wise";
				if($module.attr("model_name").match(/^WISE/i)){
					moduleType = "wise";
				}
				else if($module.attr("model_name").match(/^(PMC|PMD)/i)){
					moduleType = "pmc";
				}

				var $block = $("<div></div>").attr("class", "module-block " + moduleType).append((function(){
					if($module.attr("account_username")){
						return $("<div></div>").attr("class", "module-block-share").attr("tip", "<?=$lang['CONTROL']['TIP']['SHARE_BY_USER'];?>".replace("%username%", $module.attr("account_nickname") + "(" + $module.attr("account_username") + ")")).append(
							$(createSVGIcon("image/ics.svg", "share"))
						);
					}
				})()

				).append(
					$("<div></div>").attr("class", "module-block-title").text($module.attr("nickname") || "-")
				).append(
					$("<div></div>").attr("class", "module-block-content").html($module.attr("model_name") + "<br>" + $module.attr("serial_number"))
				).append(
					$("<div></div>").addClass("module-block-control")
				);

				if($module.attr("status") == "1"){
					$block.find("div.module-block-control").append(
						$("<button></button>").addClass("gray module-block-control-button").attr("tip", "<?=$lang['CONTROL']['TIP']['REMOTE_CONTROL'];?>").append(createSVGIcon("image/ics.svg", "computer")).bind("click", {
								"modelName": $module.attr("model_name"),
								"version": $module.attr("version"),
								"serialNumber": $module.attr("serial_number"),
								"loginPassword": $module.attr("admin_password") || $module.attr("guest_password")
							}, function(event){
								setCookie("password_" + event.data.serialNumber, event.data.loginPassword, 1);
								setCookie("language_" + event.data.serialNumber, "<?=$language?>", 1);

								$("<a></a>").attr({
									"href": "./" + event.data.serialNumber + "/",
									"target": event.data.serialNumber//"target": "_blank"
								}).hide().appendTo("body")[0].click();
							}
						)
					).append((function(){
						if($module.attr("admin_password")){
							return $("<button></button>").addClass("gray module-block-control-button").attr("tip", "<?=$lang['CONTROL']['TIP']['SETTING_FILE_RESTORE'];?>").bind("click", {
								"serialNumber": $module.attr("serial_number")
							}, function(event){
								showRuleManagerWindow(event.data.serialNumber);
							}).append(createSVGIcon("image/ics.svg", "settings"));
						}
					})());

					$block.appendTo("#module-container-online");

					if($module.attr("admin_password")){
						onlineCounter++;
					}
				}
				else{
					$block.find("div.module-block-control").append((function(){
						if(typeof($module.attr("admin_password")) != "undefined"){
							return $("<button></button>").addClass("gray module-block-control-button").attr("tip", "<?=$lang['CONTROL']['TIP']['REMOVE_DEVICE_FROM_OFFLINE_LIST'];?>").bind("click", {
								"serialNumber": $module.attr("serial_number")
							}, function(event){
								popupConfirmWindow("<?=$lang['CONTROL']['POPUP']['ARE_YOU_SURE_REMOVE_DEVICE_FROM_OFFLINE_LIST'];?>", function(){
									removeDevice(event.data.serialNumber);
								});
							}).append(createSVGIcon("image/ics.svg", "delete"))
						}
					})());

					$block.appendTo("#module-container-offline");
				}
			}

			$("#module-filter").triggerHandler("keyup");
			$("#module-online-counter").text(onlineCounter);
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0){ return; }// include timeout

			alert(jqXHR.responseText);
		},
		complete: function(){
			pidModuleList = setTimeout(function(){
				loadDeviceList();
			}, 10000);
		}
	});
}

// Firmware update(auto)
function loginDevice(deviceUID, adminPassword){
	var deferred = $.Deferred();

	$.ajax({
		url: "/" + deviceUID + "/dll/wise.dll",
		type: "POST",
		data: "<?xml version='1.0' encoding='utf-8'?><LOGIN pw='" + adminPassword + "'/>",
		contentType: "text/xml",
		processData: false,
		cache: false,
		dataType: "xml",
		success: function(xmlDoc){
			var $xmlLOGIN = $(xmlDoc).find("LOGIN");

			if($xmlLOGIN.length > 0){
				var reply = $xmlLOGIN.attr("reply");

				if(reply == "admin"){
					$("#firmware-device-table-body").find("tr[device_uid='" + deviceUID + "']").attr("key", $xmlLOGIN.attr("key"));
					deferred.resolve(["OK", deviceUID, 1]);
				}
				else if(reply == "occupied"){
					deferred.resolve(["ERROR", deviceUID, -1]);
				}
				else{
					deferred.resolve(["ERROR", deviceUID, -999]);
				}
			}
			else{
				deferred.resolve(["ERROR", deviceUID, -999]);
			}
		},
		error: function(){
			deferred.resolve(["ERROR", deviceUID, -999]);
		}
	});

	return deferred.promise();
}

function logoutDevice(deviceUID){
	var $row = $("#firmware-device-table-body").find("tr[device_uid='" + deviceUID + "']");

	$.ajax({
		url: "/" + deviceUID + "/dll/wise.dll",
		type: "POST",
		data: "<?xml version='1.0' encoding='utf-8'?><LOGOUT key='" + $row.attr("key") + "'/>",
		contentType: "text/xml",
		processData: false,
		cache: false,
		dataType: "xml"
	});
}

function startAlive(deviceUID){
	var $row = $("#firmware-device-table-body").find("tr[device_uid='" + deviceUID + "']");

	var aliveAjax = $.ajax({
		url: "/" + deviceUID + "/dll/wise.dll",
		type: "POST",
		data: "<?xml version='1.0' encoding='utf-8'?><ALIVE key='" + $row.attr("key") + "'/>",
		contentType: "text/xml",
		processData: false,
		cache: false,
		dataType: "xml"
	});

	var alivePid = setTimeout(function(){
		startAlive(deviceUID);
	}, 60 * 1000);

	// keep thread and ajax in the row(tr) data
	$row.data({
		"aliveAjax": aliveAjax,
		"alivePid": alivePid
	});
}

function stopAlive(deviceUID){
	var $row = $("#firmware-device-table-body").find("tr[device_uid='" + deviceUID + "']");
	$row.data("aliveAjax").abort();
	clearTimeout($row.data("alivePid"));
}

function downloadFirmware(deviceUIDList){
	$.ajax({
		url: "control_ajax.php?act=download_firmware",
		type: "POST",
		data: {
			"device_uid_list": deviceUIDList
		},
		dataType: "xml",
		success: function(data, textStatus, jqXHR){
			sendFirmware(data);
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0){ return; }// include timeout

			alert(jqXHR.responseText);
		}
	});
}

function uploadFirmware(deviceUIDList){
	$("#firmware-file-form").trigger("submit", [deviceUIDList]);
}

function sendFirmware(xmlDoc){
	$(xmlDoc).find("cmd > device").each(function(){
		var deviceUID = $(this).attr("serial_number");
		var result = $(this).attr("result");

		if(result == "OK"){
			var filename = $(this).attr("filename");
			var automatic = $(this).attr("automatic");

			$.ajax({
				url: "control_ajax.php?act=upload_firmware",
				type: "POST",
				data: {
					"serial_number": deviceUID,
					"filename": filename,
					"automatic": automatic
				},
				dataType: "xml",
				success: function(data, textStatus, jqXHR){
					if($(data).find("cmd").attr("result") == "OK"){
						updateProgress(deviceUID, 10);
						unzipFirmware(deviceUID, filename);
					}
					else{
						// Upload firmware to device failed
						var $row = $("#firmware-device-table-body").find("tr[device_uid='" + deviceUID + "']");

						$.ajax({
							url: "/" + deviceUID + "/dll/" + $row.attr("dll_name") + ".dll",
							type: "POST",
							data: "<?xml version='1.0' encoding='utf-8'?><FW key='" + $row.attr("key") + "' cmd='0' file='" + filename + "' success='0'/>",
							contentType: "text/xml",
							processData: false,
							cache: false,
							dataType: "xml"
						});

						updateResult(deviceUID, "fail", "<?=$lang['CONTROL']['SEND_FIRMWARE_FAILED'];?>");
						stopAlive(deviceUID);
						logoutDevice(deviceUID);
					}
				},
				error: function(jqXHR, textStatus, errorThrown){
					if(jqXHR.status === 0){ return; }// include timeout

					alert(jqXHR.responseText);
				}
			});
		}
		else if(result == "UP_TO_DATE"){
			// Device firmware is up to date, no need to update
			updateResult(deviceUID, "success", "<?=$lang['CONTROL']['FIRMWARE_IS_LATEST_VERSION'];?>");
			stopAlive(deviceUID);
			logoutDevice(deviceUID);
		}
		else{
			// Download firmware from official website failed
			updateResult(deviceUID, "fail", "<?=$lang['CONTROL']['DOWNLOAD_FIRMWARE_FAILED'];?>");
			stopAlive(deviceUID);
			logoutDevice(deviceUID);
		}
	});
}

function unzipFirmware(deviceUID, filename){
	var $row = $("#firmware-device-table-body").find("tr[device_uid='" + deviceUID + "']");

	$.ajax({
		url: "/" + deviceUID + "/dll/" + $row.attr("dll_name") + ".dll",
		type: "POST",
		data: "<?xml version='1.0' encoding='utf-8'?><FW key='" + $row.attr("key") + "' cmd='0' file='" + filename + "' success='1'/>",
		contentType: "text/xml",
		processData: false,
		cache: false,
		dataType: "xml",
		success: function(xmlDoc){
			var $xmlFW = $(xmlDoc).find("FW");

			if($xmlFW.length > 0){
				var reply = $xmlFW.attr("reply");

				if(reply == "ok"){
					unzipProgress(deviceUID, filename);
				}
				else{
					this.error();
				}
			}
			else{
				this.error();
			}
		},
		error: function(){
			updateResult(deviceUID, "fail", "<?=$lang['CONTROL']['UPDATE_FIRMWARE_FAILED'];?>");
			stopAlive(deviceUID);
			logoutDevice(deviceUID);
		}
	});
}

function unzipProgress(deviceUID, filename){
	var $row = $("#firmware-device-table-body").find("tr[device_uid='" + deviceUID + "']");

	var retryCounter = 0;

	var getProgress = function(){
		var unzipFailed = function(status){
			if(status == "1"){
				updateResult(deviceUID, "fail", "<?=$lang['CONTROL']['FIRMWARE_NOT_CORRECT'];?>");
			}
			else if(status == "2"){
				updateResult(deviceUID, "fail", "<?=$lang['CONTROL']['FREE_SPACE_NOT_ENOUGHT'];?>");
			}
			else if(status == "3"){
				updateResult(deviceUID, "fail", "<?=$lang['CONTROL']['UNZIP_FIRMWARE_FAILED'];?>");
			}
			else{
				updateResult(deviceUID, "fail", "<?=$lang['CONTROL']['UPDATE_FIRMWARE_FAILED'];?>");
			}

			stopAlive(deviceUID);
			logoutDevice(deviceUID);
		};

		$.ajax({
			url: "/" + deviceUID + "/dll/" + $row.attr("dll_name") + ".dll",
			type: "POST",
			data: "<?xml version='1.0' encoding='utf-8'?><FW key='" + $row.attr("key") + "' cmd='1' file='" + filename + "'/>",
			contentType: "text/xml",
			processData: false,
			cache: false,
			dataType: "xml",
			success: function(xmlDoc){
				var $xmlFW = $(xmlDoc).find("FW");

				if($xmlFW.length > 0){
					var reply = $xmlFW.attr("reply");

					if(reply == "-1"){
						updateProgress(deviceUID, Math.floor(parseFloat($xmlFW.attr("progress")) * 0.85 + 10));
						retryCounter = 0;
						setTimeout(getProgress, 5000);
					}
					else if(reply == "ok"){
						updateProgress(deviceUID, 95);
						stopAlive(deviceUID);
						restartRuntime(deviceUID);
					}
					else{
						unzipFailed($xmlFW.attr("status"));
					}
				}
				else{
					updateResult(deviceUID, "fail", "<?=$lang['CONTROL']['UPDATE_FIRMWARE_FAILED'];?>");
					stopAlive(deviceUID);
					logoutDevice(deviceUID);
				}
			},
			error: function(){
				if(retryCounter > 5){
					unzipFailed("0");
				}
				else{
					retryCounter++;
					setTimeout(getProgress, 5000);
				}
			}
		});
	};

	getProgress();
}

function restartRuntime(deviceUID){
	var $row = $("#firmware-device-table-body").find("tr[device_uid='" + deviceUID + "']");

	$.ajax({
		url: "/" + deviceUID + "/dll/" + $row.attr("dll_name") + ".dll",
		type: "POST",
		data: "<?xml version='1.0' encoding='utf-8'?><FW key='" + $row.attr("key") + "' cmd='2'/>",
		contentType: "text/xml",
		processData: false,
		cache: false,
		dataType: "xml",
		timeout: 30000,
		success: function(xmlDoc){
			var $xmlFW = $(xmlDoc).find("FW");

			if($xmlFW.length > 0){
				var reply = $xmlFW.attr("reply");

				if(reply == "-1"){
					setTimeout(function(){
						restartRuntime(deviceUID);
					}, 5000);
				}
				else if(reply == "success"){
					var versionSplit = $xmlFW.attr("appver").split(".");
					$row.find("td.version").text(versionSplit[0] + "." + versionSplit[1] + "." + versionSplit[2]);
					updateResult(deviceUID, "success", "<?=$lang['CONTROL']['UPDATE_FIRMWARE_SUCCESS'];?>");
				}
				else{
					updateResult(deviceUID, "fail", "<?=$lang['CONTROL']['UPDATE_FIRMWARE_FAILED'];?>");
					stopAlive(deviceUID);
					logoutDevice(deviceUID);
				}
			}
			else{
				updateResult(deviceUID, "fail", "<?=$lang['CONTROL']['UPDATE_FIRMWARE_FAILED'];?>");
				stopAlive(deviceUID);
				logoutDevice(deviceUID);
			}
		},
		error: function(){
			setTimeout(function(){
				restartRuntime(deviceUID);
			}, 5000);
		}
	});
}

function writeLogFirmwareUpdateStart(deviceUIDList){
	$.ajax({
		url: "control_ajax.php?act=write_log",
		type: "POST",
		data: {
			"type": "UPDATE_START",
			"device_uid_list": deviceUIDList
		},
		dataType: "xml",
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0){ return; }// include timeout

			alert(jqXHR.responseText);
		}
	});
}

function showFirmwareUpdateWindow(){
	$("#popup-window-content").empty().append(
		$("#window-firmware-update").clone(true).show()
	);

	$("#popup-window-background").show();

	popupWindow($("#popup-window-content"), function(result){
		if(result == "close"){
			if(jqXHRDeviceList != null){
				jqXHRDeviceList.abort();
			}

			$("#popup-window-content, #popup-window-background").hide();
		}
		else{
			if(result == "upload"){
				showDeviceSelector();
			}
			else if(result == "update"){
				popupConfirmWindow("<?=$lang['CONTROL']['POPUP']['ARE_YOU_SURE_UPDATE_FIRMWARE'];?>", function(){
					var loginDeferreds = [], completeDeferreds = [];

					$("#firmware-update-button, #firmware-close-button").attr("disabled", true);
					$("#firmware-device-checkbox-all").attr("disabled", true);

					var deviceUIDList = [];

					$("#firmware-device-table-body input[type='checkbox']").attr("disabled", true).each(function(){
						var $row = $(this).closest("tr");
						$row.find(".firmware-device-progress-container").hide();
						$row.find(".firmware-device-result-container").hide();
					}).filter(":checked").each(function(){
						var $row = $(this).closest("tr");
						var deviceUID = $row.attr("device_uid");

						$row.find(".firmware-device-progress-container").show();
						updateProgress(deviceUID, 0);

						loginDeferreds.push(
							loginDevice(deviceUID, $row.attr("admin_password"))
						);

						var deferred = $.Deferred();
						completeDeferreds.push(deferred.promise());
						$row.data("deferred", deferred);

						// For event log
						deviceUIDList.push(deviceUID);
					});

					// Event log
					writeLogFirmwareUpdateStart(deviceUIDList);

					$.when.apply($, loginDeferreds).done(function() {
						var deviceUIDList = [];

						for(var i = 0; i < arguments.length; i++){
							var result = arguments[i];

							if(result[0] == "OK"){
								var deviceUID = result[1];
								deviceUIDList.push(deviceUID);
								updateProgress(deviceUID, 5);
								startAlive(deviceUID);
							}
							else{
								var deviceUID = result[1];

								if(result[2] == -1){// Admin already loginDevice
									updateResult(deviceUID, "fail", "<?=$lang['CONTROL']['ADMIN_ALREADY_LOGIN'];?>");
								}
								else{// Unexpected errorn
									updateResult(deviceUID, "fail", "<?=$lang['CONTROL']['UPDATE_FIRMWARE_FAILED'];?>");
								}
							}
						}

						if($("#firmware-update-method-container div.firmware-update-method[selected]").attr("automatic") == "true"){
							downloadFirmware(deviceUIDList);
						}
						else{
							uploadFirmware(deviceUIDList);
						}
					});

					$.when.apply($, completeDeferreds).done(function() {
						$("#firmware-update-button, #firmware-close-button").attr("disabled", false);
						$("#firmware-device-checkbox-all").attr("disabled", false);
						$("#firmware-device-table-body input[type='checkbox']").attr("disabled", false);
						$("#firmware-device-checkbox-all").attr("checked", false).triggerHandler("click");// Uncheck all checkbox
					});
				});
			}
		}
	});

	$("#firmware-update-method-container").show();
	$("#firmware-file-name-field-container").hide();
	$("#firmware-device-selector-container").hide();

	$("#firmware-update-button").hide();
	$("#firmware-upload-button").hide();
}

function showDeviceSelector(){
	$("#firmware-device-loader").show();
	$("#firmware-device-table").hide();
	$("#firmware-device-empty-wrapper").hide();
	$("#firmware-device-table-body").empty();

	jqXHRDeviceList = $.ajax({
		url: "control_ajax.php?act=get_module_list",
		type: "POST",
		dataType: "xml",
		success: function(data, textStatus, jqXHR){
			$("#firmware-device-loader").hide();

			var $modules = $(data).find("cmd > module[status='1'][admin_password][admin_password!='']");
			if($modules.length > 0){
				for(var i = 0; i < $modules.length; i++){
					var $module = $($modules[i]);

					$("#firmware-device-table-body").append(
						$("<tr></tr>").attr({
							"device_uid": $module.attr("serial_number"),
							"admin_password": $module.attr("admin_password"),
							"dll_name": (function(){
								if($module.attr("model_name").match(/^WISE/i)){
									return "wise";
								}
								else if($module.attr("model_name").match(/^(PMC|PMD)/i)){
									return "pmc";
								}
							})()
						}).append(
							$("<td></td>").append(
								$("<div></div>").attr("class", "checkbox").append(
									$("<input type='checkbox'/>").attr("id", "checkbox-" + i).bind("click", function(){
										onClickFirmwareSelectDevice(this);
									})
								).append(
									$("<label></label>").attr("for", "checkbox-" + i)
								)
							)
						).append(
							$("<td></td>").text($module.attr("model_name") + ($module.attr("nickname") ? "(" + $module.attr("nickname") + ")" : ""))
						).append(
							$("<td></td>").attr("class", "version").css({
								"textAlign": "center",
								"whiteSpace": "nowrap"
							}).text($module.attr("version"))
						).append(
							$("<td></td>").attr("align", "center").css("padding", "0 10px").append(
								$("<div></div>").attr("class", "firmware-device-progress-container").hide().append(
									$("<div></div>").attr("class", "firmware-device-progress-bar").css("width", "0%")
								).append(
									$("<div></div>").attr("class", "firmware-device-progress-text").text("0%")
								)
							).append(
								$("<div></div>").attr("class", "firmware-device-result-container").css({
									"display": "inline-block",
									"fontSize": "0",
									"verticalAlign": "middle"
								}).append(
									$(createSVGIcon("image/ics.svg", "done")).css({"width": "30px", "height": "30px", "fill": "#339900"}).attr("class", "success").hide()
								).append(
									$(createSVGIcon("image/ics.svg", "clear")).css({"width": "30px", "height": "30px", "fill": "#cc3300"}).attr("class", "fail").hide()
								)
							)
						)
					);
				}

				$("#firmware-device-table").show().floatThead({
					scrollContainer: function($table){
						return $("#firmware-device-selector-container");
					}
				});
			}
			else{
				$("#firmware-device-empty-wrapper").show();
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0){ return; }// include timeout

			alert(jqXHR.responseText);
		}
	});

	$("#firmware-update-method-container").hide();
	$("#firmware-file-name-field-container").hide();
	$("#firmware-device-selector-container").show();

	$("#firmware-update-button").attr("disabled", true).show();
	$("#firmware-upload-button").hide();
}

function showFileSelector(){
	$("#firmware-update-method-container").hide();
	$("#firmware-file-name-field-container").show();
	$("#firmware-device-selector-container").hide();

	$("#firmware-update-button").hide();
	$("#firmware-upload-button").attr("disabled", true).show();
}

function updateProgress(deviceUID, progress){
	var $row = $("#firmware-device-table-body tr[device_uid='" + deviceUID + "']");
	var $progressBar = $row.find("div.firmware-device-progress-bar").css("width", progress + "%");
	var $progressText = $row.find("div.firmware-device-progress-text").text(progress + "%");
}

function updateResult(deviceUID, result, message){
	var $row = $("#firmware-device-table-body").find("tr[device_uid='" + deviceUID + "']");

	$row.find(".firmware-device-progress-container").hide();
	$row.find(".firmware-device-result-container").show().children().hide();

	var color = "success";
	if(result == "fail"){
		color = "red";
	}

	$row.find("." + result).show().parent("div").attr({
		"tip_color": result == "success" ? "black" : "red",
		"tip_position": "right",
		"tip": message
	});

	$row.data("deferred").resolve();
}

function onClickFirmwareSelectAllDevice(){
	var checked = $("#firmware-device-checkbox-all").attr("checked") ? true : false;

	$("#firmware-device-table-body input[type='checkbox']").each(function(){
		$(this).attr("checked", checked).triggerHandler("click");
	});
}

function onClickFirmwareSelectDevice(checkbox){
	var $checkboxs = $("#firmware-device-table-body input[type='checkbox']");
	var checkedAmount = $checkboxs.filter(":checked").length;

	$("#firmware-device-checkbox-all").attr("checked", false);
	$("#firmware-update-button").attr("disabled", true);

	if(checkedAmount > 0){
		$("#firmware-update-button").attr("disabled", false);

		if(checkedAmount == $checkboxs.length){
			$("#firmware-device-checkbox-all").attr("checked", true);
		}
	}
}

// Common function
function popupWindow($popupWindow, callback){
	var $wrapper = $popupWindow.show().find(".popup-wrapper");
	$wrapper.css("marginTop", ($(window).scrollTop() + 100) + "px");
	$wrapper.css("marginBottom", "100px");
	$wrapper.css("marginLeft", ($(window).width() / 2 - $wrapper.outerWidth() / 2 + $(window).scrollLeft()) + "px");

	windowCallback =  callback;
}

function onClickWindowButton(button){
	if(typeof(windowCallback) == "function"){
		windowCallback(button);
	}
}

function utcToLocalTime(timeStamp){
	var paddingZero = function(number, digits) {
	    return Array(Math.max(digits - String(number).length + 1, 0)).join(0) + number;
	};

	var date = new Date(timeStamp);

	return date.getFullYear() + "-" + paddingZero(date.getMonth() + 1, 2) + "-" + paddingZero(date.getDate(), 2) + " " + paddingZero(date.getHours(), 2) + ":" + paddingZero(date.getMinutes(), 2) + ":" + paddingZero(date.getSeconds(), 2);
}

$(document).ready(function(){
	$("#search-field-wrapper button").bind("click", function(event){
		if($("#search-field-wrapper").hasClass("expand")){
			return;
		}

		$("#search-field-wrapper").addClass("expand");
		$("#module-filter").show().focus();
		$("#module-filter-clear").show();
	});

	$("#module-filter").bind("focusin", function(){
		$("#search-field-wrapper").addClass("active");
		$("#search-field-wrapper button").addClass("hover");
	}).bind("focusout", function(){
		$("#search-field-wrapper").removeClass("active");
		$("#search-field-wrapper button").removeClass("hover");
	});

	$("#module-filter-clear").bind("click", function(){
		$("#search-field-wrapper").removeClass("expand");
		$("#module-filter").hide();
		$(this).hide();
	});

	$("#search-field-wrapper").hover(function(){
		$(this).addClass("hover");
		$("#search-field-wrapper button").addClass("hover");
	}, function(){
		$(this).removeClass("hover");
		if(!$("#search-field-wrapper").hasClass("active")){
			$("#search-field-wrapper button").removeClass("hover");
		}
	});

	$("#module-filter").bind("keyup", function(){
		$("#module-container-online, #module-container-offline").each(function(){
			var counter = $(this).find("div.module-block").each(function(){
				var regex = new RegExp($("#module-filter").val(), "i");
				if($(this).text().search(regex) < 0){
					$(this).hide();
				}
				else{
					$(this).show();
				}
			}).filter(":visible").length;

			if(counter <= 0){
				if($("#module-filter").val() != ""){
					$(this).next("div.module-text").text("<?=$lang['CONTROL']['NOT_FOUND'];?>").show();
				}
				else{
					$(this).next("div.module-text").text("<?=$lang['CONTROL']['NONE'];?>").show();
				}
			}
			else{
				$(this).next("div.module-text").hide();
			}
		});
	});

	$("#module-filter-clear").bind("click", function(){
		$("#module-filter").val("").triggerHandler("keyup");
	})

	$("#firmware-update").bind("click", function(){
		showFirmwareUpdateWindow();
	});

	$("#firmware-update-method-container div.firmware-update-method").bind("click", function(){
		$(this).attr("selected", true);
	});

	$("#firmware-file-name-field, #firmware-file-browse-button").bind("click", function(){
		$("#firmware-file-dialog").trigger('click');
	});

    $("#firmware-file-dialog").bind("change", function(event) {
		var filename = $(this).val();

		if(filename != ""){
			$("#firmware-file-name-field").val(filename.substr(filename.lastIndexOf('\\') + 1));
			$("#firmware-upload-button").attr("disabled", false);
		}
		else{
			$("#firmware-file-name-field").val("");
			$("#firmware-upload-button").attr("disabled", true);
		}
    });

	$("#firmware-file-form").submit(function(event, deviceUIDList){
		AIM.submit($("#firmware-file-form")[0], {
			'onComplete' : function(doc){
				var $docCMD = $(doc.XMLDocument || doc).find("cmd");
				var filename = $docCMD.attr("filename");

				if(filename != ""){
					var xmlDoc = $.parseXML("<cmd/>");

					for(var i = 0; i < deviceUIDList.length; i++){
						var deviceUID = deviceUIDList[i];

						var xmlDEVICE = xmlDoc.createElement("device");
						xmlDEVICE.setAttribute("serial_number", deviceUID);
						xmlDEVICE.setAttribute("result", "OK");
						xmlDEVICE.setAttribute("filename", filename);
						xmlDEVICE.setAttribute("automatic", "false");
						xmlDoc.documentElement.appendChild(xmlDEVICE);
					}

					sendFirmware(xmlDoc);
				}
				else{
					this.onError();
				}
			},
			'onError' : function(){
				for(var i = 0; i < deviceUIDList.length; i++){
					var deviceUID = deviceUIDList[i];
					updateResult(deviceUID, "fail", "<?=$lang['CONTROL']['UPLOAD_FIRMWARE_FAILED'];?>");
					stopAlive(deviceUID);
					logoutDevice(deviceUID);
				}
			}
		});

	});

	$("*[tip]").livequery(
		function(){
			$(this).hover(
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
			)
		},
		function(){
			clearTimeout($(this).attr("pid"));
			$("#" + $(this).attr("tip_id")).remove();
		}
	);

	loadDeviceList();
});
</script>
<?php
}
?>

<?php
function customized_body(){
	global $lang;

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . DB_NAME : (
			DB_IS_MYSQL  ? "USE " . DB_NAME : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
		$sth->execute();

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "SELECT DeviceNumber FROM account WHERE UID = ?" : (
			DB_IS_MYSQL  ? "SELECT DeviceNumber FROM account WHERE UID = ?" : (
			DB_IS_ORACLE ? "SELECT DeviceNumber FROM account WHERE \"UID\" = ?" : null)));
		$sth->execute(array($_SESSION["account_uid"]));

		$row = $sth->fetch(PDO::FETCH_ASSOC);
		$sth->closeCursor();

		$device_amount = $row["DeviceNumber"];

		$dbh = null;
	}
	catch(PDOException $e) {
		throw new Exception("Database exception!" . $e->getMessage());
	}
?>
<div style="padding:20px;">
	<div class="title" style="position:relative;"><?=$lang['CONTROL']['ONLINE_DEVICE_LIST'];?> <span style="font-size:15px;">(<span id="module-online-counter">0</span>/<?=$device_amount?>)</span>
		<div id="search-field-wrapper">
			<button class="gray cover"><svg><use xlink:href="image/ics.svg#search"></use></svg></button>
			<button class="gray"><svg><use xlink:href="image/ics.svg#search"></use></svg></button><input type="text" placeholder="<?=$lang['CONTROL']['SEARCH'];?>" id="module-filter">
			<div id="module-filter-clear"><svg><use xlink:href="image/ics.svg#clear"></use></svg></div>
		</div>

		<div style="float:right;position:relative;bottom:6px;margin-right:5px;"><button class="gray" style="padding:5.5px;" id="firmware-update"><svg><use xlink:href="image/ics.svg#cloud_upload"></use></svg></button></div>
	</div>

	<div id="module-container-online"></div>
	<div class="module-text" id="module-text-online"><?=$lang['CONTROL']['LOADING'];?></div>

	<div style="clear:both;margin-bottom:20px;"></div>

	<div class="title"><?=$lang['CONTROL']['OFFLINE_DEVICE_LIST'];?></div>
	<div id="module-container-offline"></div>
	<div class="module-text" id="module-text-offline"><?=$lang['CONTROL']['LOADING'];?></div>

	<div style="clear:both;"></div>
</div>

<div id="window-rule-manager" class="popup-wrapper">
	<div class="popup-container">
		<div class="popup-title"><?=$lang['CONTROL']['SETTING_FILE_RESTORE'];?></div>
		<div class="popup-content" id="rule-manager-container">
			<div id="rule-manager-loader"><img src="./image/loader.gif"></div>
			<div id="rule-manager-empty-wrapper">
				<div id="rule-manager-empty"><?=$lang['CONTROL']['NO_SETTING_FILE_AVAILABLE_FOR_RESTORE'];?></div>
			</div>

			<table class="scroll-table" id="rule-manager-table">
			    <thead>
			        <tr>
			            <th><?=$lang['CONTROL']['TIME'];?></th>
			            <th><?=$lang['CONTROL']['SIZE'];?></th>
			            <th style="text-align: center;padding:3px;"><?=$lang['CONTROL']['ACTION'];?></th>
			        </tr>
			    </thead>
			    <tbody id="rule-manager-table-body"></tbody>
			</table>
		</div>
		<div class="popup-footer">
			<input type="button" class="gray" value="<?=$lang['CONTROL']['CLOSE'];?>" onclick="onClickWindowButton('close');">
		</div>
	</div>
</div>

<div id="window-firmware-update" class="popup-wrapper">
	<div class="popup-container">
		<div class="popup-title"><?=$lang['CONTROL']['FIRMWARE_UPDATE'];?></div>

		<div class="popup-content" id="firmware-update-method-container">
			<div style="display:table;">
				<div style="display:table-row;">
					<div class="firmware-update-method" onclick="showDeviceSelector();" automatic="true">
						<table>
							<tr><td style="font-size:0;"><svg style="fill:#035002;width:24px;height:24px;"><use xlink:href="image/ics.svg#arrow_forward"></use></svg></td><td class="firmware-update-method-title"><?=$lang['CONTROL']['AUTO_SEARCH_AND_DOWNLOAD_LASTEST_FIRMWARE'];?></td></tr>
							<tr><td colSpan="2" style="height:5px;"></td></tr>
							<tr><td>&nbsp;</td><td class="firmware-update-method-description"><?=$lang['CONTROL']['SEARCH_AND_DOWNLOAD_FIRMWARE_AUTO'];?></td></tr>
						</table>
					</div>
				</div>

				<div style="height:20px;"></div>

				<div style="display:table-row;">
					<div class="firmware-update-method" onclick="showFileSelector();" automatic="false">
						<table>
							<tr><td style="font-size:0;"><svg style="fill:#035002;width:24px;height:24px;"><use xlink:href="image/ics.svg#arrow_forward"></use></svg></td><td class="firmware-update-method-title"><?=$lang['CONTROL']['SELECT_FIRMWARE_ON_THIS_COMPUTER'];?></td></tr>
							<tr><td colSpan="2" style="height:5px;"></td></tr>
							<tr><td>&nbsp;</td><td class="firmware-update-method-description"><?=$lang['CONTROL']['DOWNLOAD_AND_SELECT_FIRMWARE_MANUAL'];?></td></tr>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="popup-content" id="firmware-file-name-field-container">
			<?=$lang['CONTROL']['PLEASE_SELECT_HEX_FORMAT_FIRMWARE_ON_THIS_COMPUTER'];?>
			<div style="height:20px;"></div>
			<table cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td><?=$lang['CONTROL']['FIRMWARE'];?>&nbsp;</td>
					<td><input type="text" style="width:250px;box-shadow:none;border-color:#CCC;" id="firmware-file-name-field" readonly><form action="./control_ajax.php?act=submit_firmware" enctype="multipart/form-data" method="post" id="firmware-file-form"><input style="display:none;" type="file" name="file" accept=".hex" id="firmware-file-dialog"></form></td>
					<td style="padding-left:3px;"><button id="firmware-file-browse-button"><?=$lang['CONTROL']['BROWSE'];?></button></td>
				</tr>
			</table>
			<div style="height:20px;"></div>

			<table cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td valign="top" style="font-size:0;padding-right:3px;"><svg style="fill:#f1b500;"><use xlink:href="image/ics.svg#warning"></use></svg></td>
					<td class="firmware-file-name-field-description"><?=$lang['CONTROL']['SELECT_CORRECT_FIRMWARE_TO_AVOID_UPDATE_FAIL'];?></td>
				</tr>
			</table>
		</div>

		<div class="popup-content" id="firmware-device-selector-container">
			<div id="firmware-device-loader"><img src="./image/loader.gif"></div>
			<div id="firmware-device-empty-wrapper">
				<div id="firmware-device-empty"><?=$lang['CONTROL']['NO_ONLINE_DEVICE'];?></div>
			</div>
			<table class="scroll-table firmware" id="firmware-device-table">
				<colgroup>
					<col style="width: 1%;">
					<col>
					<col style="width: 1%;">
					<col style="width: 200px;">
				</colgroup>
				<thead>
					<tr>
						<th>
							<div class="checkbox">
								<input type="checkbox" id="firmware-device-checkbox-all" onclick="onClickFirmwareSelectAllDevice();">
								<label for="firmware-device-checkbox-all"></label>
						    </div>
						</th>
						<th><?=$lang['CONTROL']['MODEL_NAME_AND_NICKNAME'];?></th>
						<th style="text-align:center;white-space:nowrap;"><?=$lang['CONTROL']['VERSION'];?></th>
						<th style="text-align:center;"><?=$lang['CONTROL']['PROGRESS'];?></th>
					</tr>
				</thead>
				<tbody id="firmware-device-table-body"></tbody>
			</table>
		</div>
		<div class="popup-footer">
			<input type="button" id="firmware-upload-button" value="<?=$lang['CONTROL']['UPLOAD'];?>" onclick="onClickWindowButton('upload');">
			<input type="button" id="firmware-update-button" value="<?=$lang['CONTROL']['UPDATE'];?>" onclick="onClickWindowButton('update');">
			<input type="button" id="firmware-close-button" value="<?=$lang['CONTROL']['CLOSE'];?>" onclick="onClickWindowButton('close');" class="gray">
		</div>
	</div>
</div>
<?php
}
?>