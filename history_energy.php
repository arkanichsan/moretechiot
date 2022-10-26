<?php
function customized_header(){
	global $lang;

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . DB_NAME : (
			DB_IS_MYSQL  ? "USE " . DB_NAME : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
		$sth->execute();

		// Select device
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "SELECT CarbonFootprintFactor FROM account WHERE UID = ?" : (
			DB_IS_MYSQL  ? "SELECT CarbonFootprintFactor FROM account WHERE UID = ?" : (
			DB_IS_ORACLE ? "SELECT CarbonFootprintFactor FROM account WHERE \"UID\" = ?" : null)));
		$sth->execute(array($_SESSION['account_uid']));

		$row = $sth->fetch(PDO::FETCH_ASSOC);
		$sth->closeCursor();

		$carbonFootprintFactor = $row["CarbonFootprintFactor"];

		$dbh = null;
	}
	catch(PDOException $e) {
		throw new Exception("Database exception!" . $e->getMessage());
	}
?>
<link rel="stylesheet" type="text/css" href="./css/picker.css">
<link rel="stylesheet" type="text/css" href="./css/tip.css">
<style type="text/css">
.sub-title{
	position:absolute;
	right:0;
	bottom:10px;
	font-size:13px;
	color:#888;
}
/******************/
#select-container{
	width:100%;
}

#select-device,
#select-module,
#select-channel{
	width:33.33%;
	height:500px;
	float:left;
	box-sizing:border-box;
	display:none;
}

#select-device{
	padding-right:20px;
	position: relative;
}

#select-module{
	padding:0 10px;
	position: relative;
}

#select-channel{
	padding-left:20px;
	position: relative;
}

.select-wrapper{
	width:100%;
	height:100%;
	position: relative;
	border-width:1px;
	border-style:solid;
	border-color:#ccc;
	background-color:#fff;
    box-sizing: border-box;
}

.select-list{
	width:100%;
	height:100%;
	position: relative;
	color:#035002c4;
	overflow-x:hidden;
	overflow-y:auto;
}

.select-list .select-title:first-child{
	border-top-width:0;
}

.select-title{
	text-align:center;
	padding:2px 0;
	border-width:1px 0;
	border-style:solid;
	border-color:#ccc;
	background: #fefefe;
	font-size:11px;
	font-weight:bold;
	color: #bbb;
}

.select-option{
	margin:2px;
	padding:6px 8px;
	border:1px solid #fff;
	background: #fff;
	cursor:pointer;
	position: relative;
	color: #035002d1;
}

.select-option-share{
	position: absolute;
	top:50%;
	right:5px;
	margin-top:-9px;
}

.select-option:not([disabled]):hover,
.select-option:not([disabled]).active{
	background: #f6f6f6;
	border-color:#ccc;
}

.select-option[disabled]{
	cursor:default;
	opacity:0.5;
}

.select-loader{
	position:absolute;
	top:50%;
	left:50%;
	margin-top:-16px;
	margin-left:-16px;
	display:none;
}

#arrow-device,
#arrow-module{
	display:none;
	position:absolute;
	right:0;
	top:50%;
	margin-top:-12px;
/*	z-index:2;*/
}
/******************/
#chart-container{
	width:100%;
	top:0;
	left:0;
	display:none;
	/*opacity:0;*/
}

.period-button{
	color: #606060;
	border: 1px solid #B3B3B3;
	background-color: #fefefe;
	background-image: linear-gradient(to bottom, #fefefe, #f2f2f2);
	float:left;
	padding:6px;
	border-radius:0px;
	border-left-width:0;
	cursor: pointer;
}

.period-button.active{
	background-color: #eeeeee;
	background-image: linear-gradient(to bottom, #eeeeee, #f0f0f0);
	box-shadow: inset 0px 0px 5px rgba(0, 0, 0, 0.2);
	/*font-weight:bold;*/
	cursor:default;
}

.period-button-container{
	float:left;
}

.period-button-container .period-button:first-child{
	border-radius:3px 0 0 3px !important;
	border-left-width:1px;
}

.period-button-container .period-button:last-child{
	border-radius:0 3px 3px 0 !important;
}

.time-button-wrapper{
	float:right;
}

.time-button-wrapper > div{
	display:inline-block;
	vertical-align:middle;
}

.time-button-container{
	position: relative;
	color: #606060;
	cursor: pointer;
}

.time-button{
	border: 1px solid #B3B3B3;
	background-color: #fefefe;
	background-image: linear-gradient(to bottom, #fefefe, #f2f2f2);
	border-radius:3px;
}

.time-button:hover, .time-button.active{
	border-color:#888;
}

.time-button.active{
	background-color: #fff;
	background-image: none;
}

.time-button:active{
	background-color: #eeeeee;
	background-image: linear-gradient(to bottom, #eeeeee, #f0f0f0);
	box-shadow: inset 0px 0px 5px rgba(0, 0, 0, 0.2);
}

.time-button-select{
	position:absolute;
	border: 1px solid #888;
	border-radius:3px;
	/*padding:6px 0;*/
	margin-top:-1px;
	background-color: #fff;
	z-index:10;
	display:none;
}

.time-button-option{
	padding:6px 6px;
	display:table-row;
}

.time-button-option:hover{
	background-color: #555;
	color:#FFF;
}

.time-button-option-name{
	padding:6px;
	display:table-cell;
	white-space:nowrap;
}

.time-button-option-desc{
	font-size:12px;
	color:#bbb;
	padding:6px 24px 6px 6px;
	display:table-cell;
	white-space:nowrap;
}

.time-button-option.customized .time-button-option-desc{
	color:transparent;
}

#layout-wrapper {
	margin-right: 210px;
	margin-top:20px;
}

#layout-left {
	float: left;
	width: 100%;
}

#layout-right {
	float: right;
	width: 200px;
	margin-right: -210px;
}

#energy-wrapper{
	width:100%;
	height:410px;
	padding:20px;
	background-color:#FFF;
	box-sizing: border-box;
	border: 1px solid #ccc;
	/*border-radius:3px;*/

}

#energy-container{
	width:100%;
	height:100%;
	position: relative;
}

#energy-chart{
	width:100%;
	height:100%;
}

.chart-loader{
	position:absolute;
	top:0;
	bottom:0;
	left:0;
	right:0;
	background-color:rgba(255, 255, 255, 0.8);
	z-index:4;
}

.chart-loader div{
	position:absolute;
	top:50%;
	left:50%;
	margin-top:-16px;
	margin-left:-16px;
	font-size:0;
}

.chart-empty{
	position:absolute;
	top:0;
	bottom:0;
	left:0;
	right:0;
	background-color:rgba(255, 255, 255, 1);
	z-index:4;
	display:none;
}

.chart-empty div{
	width:160px;
	line-height:50px;
	position:absolute;
	top:50%;
	left:50%;
	margin-top:-25px;
	margin-left:-80px;
	text-align:center;
	color:#035002ad;
	font-size:24px;
}

.info-container{
	display:table-cell;
	width:200px;
	height:130px;
	background-color:#FFF;
	box-sizing: border-box;
	border: 1px solid #ccc;
	vertical-align: middle;
    text-align: center;
}

.info-value{
	text-align:center;
	font-size:50px;
	font-weight:600;
	color:#555;
	position: relative;
	display:inline-block;
	cursor:default;
}

.info-value-unit-container{
	display:inline-block;
	position: relative;
}

.info-value-unit{
	position:absolute;
	font-size:13px;
	bottom:0;
	font-weight:normal;
	white-space:nowrap;
}

.info-value-unit.right{
	left:3px;
}

.info-value-unit.left{
	right:3px;
}

.info-title{
	text-align:center;
	font-size:13px;
	color:#777;
	margin-bottom:3px;
	padding-top:5px;
}

.info-compare-table{
	font-size:13px;
	color:#777;
	display:table;
	margin:auto;
	cursor:default;
}

.info-compare-row{
	display:table-row;
}

.info-compare-cell{
	display:table-cell;
/*
	vertical-align:middle;
*/
}

.info-compare-value{
	font-size:18px;
	padding:0 4px;
}

.info-compare-value-unit{
	white-space:nowrap;
}

#carbon-footprint-factor-setting-button{
	position:absolute;
	right:5px;
	top:5px;
	cursor:pointer;
}

#carbon-footprint-factor-setting-button svg{
	fill:#808080;
}

#carbon-footprint-factor-setting-button:hover svg{
	fill:#606060;
}

#power-phase{
	margin-top:20px;
}

#power-wrapper{
	margin-top:20px;
	width:100%;
	padding:20px;
	background-color:#FFF;
	box-sizing: border-box;
	border: 1px solid #ccc;
}

#power-item-container{
	background-color:#FFF;
	position:relative;
	overflow:hidden;
}

#power-item-container div.power-item{
	border-style:solid;
	border-color:#545454;
	border-width:0 0 1px 0;
	float:left;
	width:100px;
	text-align:center;
	padding:8px 0 7px 0;
 	letter-spacing: 2px;
	color:#555;
	cursor:pointer;
	z-index:6;
	position:relative;
}

#power-item-container div.power-item.active{
	border-width:1px 1px 1px 1px;
	border-bottom-color:#FFF;
	padding:7px 0 8px 0;
	font-weight:bold;
	color:#545454;
	background-color:#fff;
	top:0px;
	cursor:default;
}

#power-item-hr{
	position:absolute;
	bottom:1px;
	left:0;
	border-style:solid;
	border-color:#545454;
	border-width:0 0 1px 0;
	box-shadow: 0px 1px 0px #d9d9d9;
	z-index:5;
}

#power-container{
	clear:both;
	width:100%;
	height:300px;
	position:relative;
	top:-9px;
	/*left:-11px;*/
}

#power-chart{
	width:100%;
	height:100%;
}

.power-phase-wrapper{
	float:left;
	width:33.33%;
	box-sizing: border-box;
}

.power-phase-container{
	border-style:solid;
	border-width:1px;
	width:100%;
	box-sizing: border-box;
    box-shadow: 0 1px 1px rgba(0,0,0,0.6);
}

.power-phase-container.red{
	border-color:#953734;
}

.power-phase-container.green{
	border-color:#76923c;
}

.power-phase-container.blue{
	border-color:#366092;
}

.power-phase-container.purple{
	border-color:#5f497a;
}

.power-phase-title{
	color:#545454;
	text-align:center;
	padding:4px 0;
	border-style:solid;
	border-color:#545454;
	border-width:0 0 1px 0;
}

.power-phase-title.red{
	background-color:#f2dcdb;
	border-color:#953734;
}

.power-phase-title.green{
	background-color:#ebf1dd;
	border-color:#76923c;
}

.power-phase-title.blue{
	background-color:#c6d9f0;
	border-color:#366092;
}

.power-phase-title.purple{
	background-color:#e5e0ec;
	border-color:#5f497a;
}

.power-phase-content-max{
	float:left;
	width:50%;
	box-sizing: border-box;
	padding:10px 0;
	border-style:solid;
	border-color:#d9d9d9;
	border-width:0 1px 0 0;
	margin:10px 0;
    text-align: center;
}

.power-phase-content-min{
	float:right;
	width:50%;
	box-sizing: border-box;
	padding:20px 0;
    text-align: center;
}

.power-phase-content-title{
	text-align:center;
	font-size:13px;
	color:#777;
}

.power-phase-content-value{
	text-align:center;
	font-size:40px;
	font-weight:600;
	color:#555;
	position: relative;
	display:inline-block;
	cursor:default;
}

.power-phase-content-value-unit-container{
	display:inline-block;
	position:relative;
}

.power-phase-content-value-unit{
	position:absolute;
	font-size:13px;
	left: 3px;
	bottom:0;
	font-weight:normal;
}

.power-phase-content-time{
	text-align:center;
	font-size:13px;
	color:#777;
}
</style>
<script language="javascript" src="./js/jquery.flot.min.js"></script>
<script language="javascript" src="./js/jquery.flot.time.min.js"></script>
<script language="javascript" src="./js/jquery.flot.tooltip.min.js"></script>
<script language="javascript" src="./js/jquery.tip.js"></script>
<script language="javascript" src="./js/jquery.livequery.min.js"></script>
<script language="JavaScript">
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
</script>
<script language="JavaScript">
var carbonFootprintFactor = <?=$carbonFootprintFactor?>;
var dataSet = [{
    color: "#ccc",
    lines: {
        fill: true
    },
    points: {
        show: true
    }
}, {
	color: "#953734"
}], ajax = {};

function loadDeviceList(){
	$("#select-device div.select-list").empty();
	$("#select-module").hide();

	return $.ajax({
		url: "history_energy_ajax.php?act=get_device_list",
		type: "POST",
		dataType: "xml",
		success: function(data, textStatus, jqXHR){
			var $devices = $(data).find("list > device");
			if($devices.length > 0){
				$("#select-device div.select-list").append(
					$("<div></div>").attr("class", "select-title").text("<?=$lang['HISTORY_ENERGY']['DEVICE'];?>")
				);

				for(var i = 0; i < $devices.length; i++){
					var $device = $($devices[i]);

					$("#select-device div.select-list").append(
						$("<div></div>").attr({
							"class": "select-option",
							"account_uid": $device.attr("account_uid"),
							"account_username": $device.attr("account_username"),
							"account_nickname": $device.attr("account_nickname"),
							"device_uid": $device.attr("uid"),
							"disabled": !$device.attr("model_name").match(/^(PMC|PMD)/i) ? true : null
						}).text($device.attr("model_name") + ($device.attr("nickname") != "" ? "(" + $device.attr("nickname") + ")" : "")).bind("click", function(){
							if($(this).attr("disabled")){
								return;
							}

							$(this).addClass("active").siblings().removeClass("active");

							$("#select-device div.select-loader").show();
							$("#select-device div.select-list").css("opacity", 0.3);

							loadModuleList($(this).attr("account_uid"), $(this).attr("device_uid")).always(function(){
								$("#select-device div.select-loader").hide();
								$("#select-device div.select-list").css("opacity", 1);
							});
						}).append((function(){
							if($device.attr("account_username")){
								return $("<div></div>").attr({
									"class": "select-option-share",
									"tip": "<?=$lang['HISTORY_ENERGY']['TIP']['SHARE_BY_USER'];?>".replace("%username%", $device.attr("account_nickname") + "(" + $device.attr("account_username") + ")")
								}).append(
									createSVGIcon("image/ics.svg", "share")
								);
							}
						})())
					);
				}
			}

			var $groups = $(data).find("list > group");
			if($groups.length > 0){
				$("#select-device div.select-list").append(
					$("<div></div>").attr("class", "select-title").text("<?=$lang['HISTORY_ENERGY']['GROUP'];?>")
				);

				for(var i = 0; i < $groups.length; i++){
					var $group = $($groups[i]);

					$("#select-device div.select-list").append(
						$("<div></div>").attr({
							"class": "select-option",
							"group_info_uid": $group.attr("uid")
						}).text($group.attr("name")).bind("click", function(){
							isGroup = true;

							$(this).addClass("active").siblings().removeClass("active");

							$("div.sub-title").text($("#select-device div.select-list div.select-option.active").text());

							hideChannelSelector().always(function(){
								$("div.period-button-container:eq(0)").find("div.period-button:first()").triggerHandler("click");
							});
						})
					);
				}
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

			alert(jqXHR.responseText);
		},
		complete: function(){}
	});
}

function loadModuleList(accountUID, deviceUID){//uid is 16bytes sn
	$("#select-module div.select-list").empty();
	$("#select-channel").hide();

	return $.ajax({
		url: "history_energy_ajax.php?act=get_module_list",
		type: "POST",
		data: "account_uid=" + accountUID + "&device_uid=" + deviceUID,
		dataType: "xml",
		success: function(data, textStatus, jqXHR){
			$("#select-module div.select-list").empty();

			var $modules = $(data).find("list > module");
			if($modules.length > 0){
				var interfaces = {
					"comport": [],
					"network": []
				};

				for(var i = 0; i < $modules.length; i++){
					var $module = $($modules[i]);

					var match = $module.attr("interface").match(/COM(\d+)/);
					if(match && match[1]){
						if(typeof(interfaces.comport[parseInt(match[1], 10)]) == "undefined"){
							interfaces.comport[parseInt(match[1], 10)] = {
								name: $module.attr("interface"),
								modules: []
							}
						}

						interfaces.comport[parseInt(match[1], 10)].modules[parseInt($module.attr("number"), 10)] = {
							"uid": $module.attr("uid"),
							"modelName": $module.attr("model_name"),
							"nickname": $module.attr("nickname"),
							"loop": $module.attr("loop"),
							"phase": $module.attr("phase")
						};
					}
					else if($module.attr("interface") == "LAN"){
						if(typeof(interfaces.network[0]) == "undefined"){
							interfaces.network[0] = {
								name: "LAN",
								modules: []
							}
						}

						interfaces.network[0].modules[parseInt($module.attr("number"), 10)] = {
							"uid": $module.attr("uid"),
							"modelName": $module.attr("model_name"),
							"nickname": $module.attr("nickname"),
							"loop": $module.attr("loop"),
							"phase": $module.attr("phase")
						};
					}
				}

				for(var sourceTypeIndex = 0, sourceTypeArray = ["comport", "network"]; sourceTypeIndex < sourceTypeArray.length; sourceTypeIndex++){
					var sourceType = sourceTypeArray[sourceTypeIndex];

					for(var sourceIndex = 0; sourceIndex < interfaces[sourceType].length; sourceIndex++){
						if(typeof(interfaces[sourceType][sourceIndex]) == "undefined"){continue;}

						var modules = interfaces[sourceType][sourceIndex].modules;
						if(modules.length > 0){
							$("#select-module div.select-list").append(
								$("<div></div>").attr("class", "select-title").text(interfaces[sourceType][sourceIndex].name)
							);
						}

						for(var moduleIndex = 0; moduleIndex < modules.length; moduleIndex++){
							if(typeof(modules[moduleIndex]) == "undefined"){continue;}

							var module = modules[moduleIndex];

							$("#select-module div.select-list").append(
								$("<div></div>").attr({
									"class": "select-option",
									"module_uid": module.uid,
									"_loop": module.loop,
									"phase": module.phase
								}).text((function(){
									return module.modelName + (module.nickname != "" ? "(" + module.nickname + ")" : "");
								})()).bind("click", function(){
									$(this).addClass("active").siblings().removeClass("active");

									$("#select-module div.select-loader").show();
									$("#select-module div.select-list").css("opacity", 0.3);

									loadChannelList($("#select-device div.select-list div.select-option.active").attr("account_uid"), $("#select-device div.select-list div.select-option.active").attr("device_uid"), $(this).attr("module_uid")).always(function(){
										$("#select-module div.select-loader").hide();
										$("#select-module div.select-list").css("opacity", 1);
									});
								})
							);
						}
					}
				}
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

			alert(jqXHR.responseText);
		},
		complete: function(){
			$("#select-module").show();
			$("#select-module div.select-wrapper").show().css({
				"left": "-" + ($("#select-module div.select-wrapper").width() / 4) + "px",
				"opacity": 0
			}).animate({
				"left": 0,
				"opacity": 1
			}, "fast");
		}
	});
}

function loadChannelList(accountUID, deviceUID, moduleUID){
	$("#select-channel div.select-list").empty();

	return $.ajax({
		url: "history_energy_ajax.php?act=get_channel_list",
		type: "POST",
		data: "account_uid=" + accountUID + "&device_uid=" + deviceUID + "&module_uid=" + moduleUID,
		dataType: "xml",
		success: function(data, textStatus, jqXHR){
			// Create option(All loop)
			$("#select-channel div.select-list").append(
				$("<div></div>").attr("class", "select-title").text("<?=$lang['HISTORY_ENERGY']['LOOP'];?>")
			);

			var $loops = $(data).find("list > loop");
			if($loops.length > 0){
				for(var i = 0; i < $loops.length; i++){
					var $loop = $($loops[i]);

					$("#select-channel div.select-list").append(
						$("<div></div>").attr({
							"class": "select-option",
							"_loop": i + 1//attr loop is use in video
						}).text("<?=$lang['HISTORY_ENERGY']['LOOP'];?>" + (i + 1) + ($loop.attr("nickname") ? "(" + $loop.attr("nickname") + ")" : "")).bind("click", function(){
							// Check the container is running animate
							if($("#select-container").is(":animated")){
								return;
							}

							isGroup = false;

							$(this).addClass("active").siblings().removeClass("active");

							$("div.sub-title").text($("#select-device div.select-list div.select-option.active").text() + " / " + $("#select-module div.select-list div.select-option.active").text() + " / " + $("#select-channel div.select-list div.select-option.active").text());

							hideChannelSelector().always(function(){
								$("div.period-button-container:eq(0)").find("div.period-button:first()").triggerHandler("click");
							});
						})
					);
				}
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

			alert(jqXHR.responseText);
		},
		complete: function(){
			$("#select-channel").show();
			$("#select-channel div.select-wrapper").show().css({
				"left": "-" + ($("#select-channel div.select-wrapper").width() / 4) + "px",
				"opacity": 0
			}).animate({
				"left": 0,
				"opacity": 1
			}, "fast");
		}
	});
}

function getModuleData(accountUID, deviceUID, moduleUID, loop, phase, type, oper, begin, end, unit){
	return $.ajax({
		url: "history_energy_ajax.php?act=get_module_data",
		type: "POST",
		data: "account_uid=" + accountUID + "&device_uid=" + deviceUID + "&module_uid=" + moduleUID + "&loop=" + loop + "&phase=" + phase + "&type=" + type + "&oper=" + oper + "&begin=" + begin + "&end=" + end + "&timezone=" + encodeURIComponent(getTimeZone()) + "&unit=" + unit,
		dataType: "json"
	});
}

function getGroupData(accountUID, groupInfoUID, moduleUID, loop, phase, type, oper, begin, end, unit){//accountUID, loop and phase no use
	return $.ajax({
		url: "history_energy_ajax.php?act=get_group_data",
		type: "POST",
		data: "group_info_uid=" + groupInfoUID + "&type=" + type + "&oper=" + oper + "&begin=" + begin + "&end=" + end + "&timezone=" + encodeURIComponent(getTimeZone()) + "&unit=" + unit,
		dataType: "json",
		timeout: 0
	});
}

function loadLoopKWH(timeType, begin, end){
	if(isGroup){
		var deviceUID = $("#select-device div.select-list div.select-option.active").attr("group_info_uid");
		var phase = 1;
		var getData = getGroupData;
	}
	else{
		var accountUID = $("#select-device div.select-list div.select-option.active").attr("account_uid");
		var deviceUID = $("#select-device div.select-list div.select-option.active").attr("device_uid");
		var moduleUID = $("#select-module div.select-list div.select-option.active").attr("module_uid");
		var loop = $("#select-channel div.select-list div.select-option.active").attr("_loop");
		var phase = $("#select-module div.select-list div.select-option.active").attr("phase") == "3" ? 4 : 1;
		var getData = getModuleData;
	}

	$("#energy-loader").show();

	if(typeof(ajax[timeType]) != "undefined"){
		ajax[timeType].abort();
	}

	ajax[timeType] = getData(accountUID, deviceUID, moduleUID, loop, phase, "DeltaTotalKWH", "SUM", begin, end, $("div.period-button-container:eq(0)").find("div.period-button.active").attr("unit")).done(function(data){
		if(timeType == "major"){
			dataSet[1].data = processChartData(data[phase - 1].data);

			if(dataSet[1].data.length > 0){
				$("#chart-kwh div.info-value").attr({
					"value": dataSet[1].data.total
				}).find("span").text(formating2(dataSet[1].data.total));

				$("#carbon-footprint div.info-value").attr({
					"value": dataSet[1].data.total * carbonFootprintFactor
				}).find("span").text(formating2(dataSet[1].data.total * carbonFootprintFactor));
			}
			else{
				$("#chart-kwh div.info-value").removeAttr("value").find("span").text("-");
				$("#carbon-footprint div.info-value").removeAttr("value").find("span").text("-");
			}
		}
		else if(timeType == "compare"){
			dataSet[0].data = processChartData(data[phase - 1].data);

			if(dataSet[0].data.length > 0){
				$("#chart-kwh div.info-compare-value").attr({
					"value": dataSet[0].data.total
				}).text(formating2(dataSet[0].data.total));

				$("#carbon-footprint div.info-compare-value").attr({
					"value": dataSet[0].data.total * carbonFootprintFactor
				}).text(formating2(dataSet[0].data.total * carbonFootprintFactor));
			}
			else{
				$("#chart-kwh div.info-compare-value").removeAttr("value").text("-");
				$("#carbon-footprint div.info-compare-value").removeAttr("value").text("-");
			}
		}

		if(!dataSet[0].data || !dataSet[1].data){// Not load finish
			return;
		}

		// Update growth rate
		var present = 0, past = 0;
		for(var i = 0; i < dataSet[1].data.length; i++){
			if(dataSet[0].data[i]){
				present += dataSet[1].data[i][1];
				past += dataSet[0].data[i][1];
			}
		}

		if(past == 0){
			$("#chart-compare div.info-value span").text("-");
		}
		else{
			var growthRate = Math.round(((present - past) / past) * 100 * 100) / 100;
			$("#chart-compare div.info-value span").text(growthRate > 0 ? "+" + growthRate : growthRate);
		}

		$("#energy-loader").hide();

		// Draw chart
		if(dataSet[0].data.length != 0 || dataSet[1].data.length != 0){
			drawChart("energy-chart", dataSet);

			$("#energy-empty").hide();
		}
		else{
			$("#energy-empty").show();
		}
	}).always(function(){
		delete ajax[timeType];
	});
}

function loadLoopData(type, begin, end){
	var accountUID = $("#select-device div.select-list div.select-option.active").attr("account_uid");
	var deviceUID = $("#select-device div.select-list div.select-option.active").attr("device_uid");
	var moduleUID = $("#select-module div.select-list div.select-option.active").attr("module_uid");
	var loop = $("#select-channel div.select-list div.select-option.active").attr("_loop");
	var phase;

	if($("#select-module div.select-list div.select-option.active").attr("phase") == "1"){
		phase = "1";
	}
	else if($("#select-module div.select-list div.select-option.active").attr("phase") == "3"){
		if(type == "V" || type == "I" || type == "PF"){
			phase = "123";
		}
		else{
			phase = "4";
		}
	}

	$("#power-loader").show();

	if(typeof(ajax.powerItem) != "undefined"){
		ajax.powerItem.abort();
	}

	ajax.powerItem = getModuleData(accountUID, deviceUID, moduleUID, loop, phase, type, "AVG", begin, end, $("div.period-button-container:eq(0)").find("div.period-button.active").attr("unit")).done(function(data){
		var period = $("div.period-button-container:eq(0)").find("div.period-button.active").attr("period");
		var getDate = function(timestamp){
			var date = new Date(timestamp);

			if(period == "day"){
				return padding(date.getHours(), 2) + ":" + padding(date.getMinutes(), 2);
			}
			else if(period == "week"){
				return padding(date.getMonth() + 1, 2) + "/" + padding(date.getDate(), 2);
			}
			else if(period == "month"){
				return padding(date.getMonth() + 1, 2) + "/" + padding(date.getDate(), 2);
			}
			else if(period == "quarter"){
				return date.getFullYear() + "/" + padding(date.getMonth() + 1, 2);
			}
			else if(period == "year"){
				return date.getFullYear() + "/" + padding(date.getMonth() + 1, 2);
			}
		};

		if($("#select-module div.select-list div.select-option.active").attr("phase") == "1"){
			$("#power-phase-1 div.power-phase-title").text("<?=$lang['HISTORY_ENERGY']['LOOP'];?>");
		}
		else if($("#select-module div.select-list div.select-option.active").attr("phase") == "3"){
			$("#power-phase-1 div.power-phase-title").text("<?=$lang['HISTORY_ENERGY']['PHASE_A'];?>");
		}

		$(".power-phase-wrapper").hide();

		for(var i = 0; i < phase.length; i++){
			var index = parseInt(phase[i], 10) - 1;

			if(data[index].data.length > 0){
				processChartData(data[index].data);

				$("#power-phase-" + (index + 1) + " div.power-phase-content-max div.power-phase-content-value").attr({
					"value": data[index].data[data[index].data.maxIndex][1],
					"time": data[index].data[data[index].data.maxIndex].timestamp
				}).find("span").text(formating2(data[index].data[data[index].data.maxIndex][1]));

				$("#power-phase-" + (index + 1) + " div.power-phase-content-min div.power-phase-content-value").attr({
					"value": data[index].data[data[index].data.minIndex][1],
					"time": data[index].data[data[index].data.minIndex].timestamp,
				}).find("span").text(formating2(data[index].data[data[index].data.minIndex][1]));

				$("#power-phase-" + (index + 1) + " div.power-phase-content-max div.power-phase-content-time").text(getDate(data[index].data[data[index].data.maxIndex].timestamp));
				$("#power-phase-" + (index + 1) + " div.power-phase-content-min div.power-phase-content-time").text(getDate(data[index].data[data[index].data.minIndex].timestamp));
			}
			else{
				$("#power-phase-" + (index + 1) + " div.power-phase-content-max div.power-phase-content-value").removeAttr("value time").find("span").text("-");
				$("#power-phase-" + (index + 1) + " div.power-phase-content-min div.power-phase-content-value").removeAttr("value time").find("span").text("-");
				$("#power-phase-" + (index + 1) + " div.power-phase-content-max div.power-phase-content-time").text("-");
				$("#power-phase-" + (index + 1) + " div.power-phase-content-min div.power-phase-content-time").text("-");
			}

			$("#power-phase-" + (index + 1)).show();
		}

		// Update Unit
		var unit = "";
		if(type == "V"){
			unit = "V";
		}
		else if(type == "I"){
			unit = "A";
		}
		else if(type == "KW"){
			unit = "kW";
		}
		else if(type == "KVAR"){
			unit = "kvar";
		}
		else if(type == "KVA"){
			unit = "kVA";
		}
		else if(type == "PF"){
		}

		$("#power-phase div.power-phase-content-value-unit").text(unit);

		$("#power-loader").hide();

		// Draw chart
		if(data[0].data.length != 0 || data[1].data.length != 0 || data[2].data.length != 0 || data[3].data.length != 0){
			data[0].color = "#953734";
			data[1].color = "#76923c";
			data[2].color = "#366092";
			data[3].color = "#5f497a";

			drawChart("power-chart", data, {
				yaxis: {
					position: "right"
				},
		        grid: {
		    		borderWidth: {
						top: 1
					}
		        }
			});

			var $plot = $("#power-chart").data("plot");
			var data = $plot.getData()[0];

			$("#power-item-hr").css("right", data.yaxis.box.width + "px");
			//$("#power-item-hr").css("left", ($("#power-chart").width() - data.xaxis.p2c(data.xaxis.max) - data.yaxis.box.width) + "px");
			//$("#power-item-container div.power-item:first").css("marginLeft", (($("#power-chart").width() - data.xaxis.p2c(data.xaxis.max) - data.yaxis.box.width) + 10) + "px")

			$("#power-empty").hide();
		}
		else{
			$("#power-empty").show();
		}
	}).always(function(){
		delete ajax.powerItem;
	});
}

function processChartData(data){
	var period = $("div.period-button-container:eq(0)").find("div.period-button.active").attr("period");
	var max = -Infinity, min = Infinity;
	data.maxIndex = -1;
	data.minIndex = -1;
	data.total = 0;

	for(var i = 0; i < data.length; i++){
		data[i].timestamp = data[i][0] * 1000;
		var date = new Date(data[i].timestamp);

		if(period == "day"){
			data[i][0] = date.getHours();
		}
		else if(period == "week"){
			data[i][0] = date.getDay();
		}
		else if(period == "month"){
			data[i][0] = date.getDate() - 1;
		}
		else if(period == "quarter"){
			data[i][0] = date.getMonth() % 3;
		}
		else if(period == "year"){
			data[i][0] = date.getMonth();
		}

		if(data[i][1] <= min){
			min = data[i][1];
			data.minIndex = i;
		}

		if(data[i][1] >= max){
			max = data[i][1];
			data.maxIndex = i;
		}

		data.total += data[i][1];
	}

	return data;
}

function drawChart(id, dataSet, option){
	var period = $("div.period-button-container:eq(0)").find("div.period-button.active").attr("period");

	option = $.extend(true, {
        series: {
            lines: {
                show: true
            },
            points: {
                show: true
            },
            grow: {
                active: true
            }
        },
		xaxis: {
			tickLength: 0,
			ticks: function(axis){
				if(period == "day"){
					return [
//							[ 0, "00:00"], [ 1, "01:00"], [ 2, "02:00"], [ 3, "03:00"], [ 4, "04:00"], [ 5, "05:00"], [ 6, "06:00"], [ 7, "07:00"], [ 8, "08:00"], [ 9, "09:00"], [10, "10:00"], [11, "11:00"],
//							[12, "12:00"], [13, "13:00"], [14, "14:00"], [15, "15:00"], [16, "16:00"], [17, "17:00"], [18, "18:00"], [19, "19:00"], [20, "20:00"], [21, "21:00"], [22, "22:00"], [23, "23:00"]
						[ 0, "00:00"], [ 2, "02:00"], [ 4, "04:00"], [ 6, "06:00"], [ 8, "08:00"], [10, "10:00"],
						[12, "12:00"], [14, "14:00"], [16, "16:00"], [18, "18:00"], [20, "20:00"], [22, "22:00"]
					];
				}
				else if(period == "week"){
					return [
						[0, "<?=$lang['HISTORY_ENERGY']['SUN_LONG'];?>"], [1, "<?=$lang['HISTORY_ENERGY']['MON_LONG'];?>"], [2, "<?=$lang['HISTORY_ENERGY']['TUE_LONG'];?>"], [3, "<?=$lang['HISTORY_ENERGY']['WED_LONG'];?>"], [4, "<?=$lang['HISTORY_ENERGY']['THU_LONG'];?>"], [5, "<?=$lang['HISTORY_ENERGY']['FRI_LONG'];?>"], [6, "<?=$lang['HISTORY_ENERGY']['SAT_LONG'];?>"]
					];
				}
				else if(period == "month"){
					return [
						[ 0,  "1"], [ 1,  "2"], [ 2,  "3"], [ 3,  "4"], [ 4,  "5"], [ 5,  "6"], [ 6,  "7"], [ 7,  "8"], [ 8,  "9"], [ 9, "10"],
						[10, "11"], [11, "12"], [12, "13"], [13, "14"], [14, "15"], [15, "16"], [16, "17"], [17, "18"], [18, "19"], [19, "20"],
						[20, "21"], [21, "22"], [22, "23"], [23, "24"], [24, "25"], [25, "26"], [26, "27"], [27, "28"], [28, "29"], [29, "30"], [30, "31"]
					];
				}
				else if(period == "quarter"){
					return [
						[0,  "<?=$lang['HISTORY_ENERGY']['1ST_MONTH'];?>"], [1,  "<?=$lang['HISTORY_ENERGY']['2ND_MONTH'];?>"], [2,  "<?=$lang['HISTORY_ENERGY']['3RD_MONTH'];?>"]
					];
				}
				else if(period == "year"){
					return [
						[0, "<?=$lang['HISTORY_ENERGY']['JAN'];?>"], [1, "<?=$lang['HISTORY_ENERGY']['FEB'];?>"], [2, "<?=$lang['HISTORY_ENERGY']['MAR'];?>"], [3, "<?=$lang['HISTORY_ENERGY']['APR'];?>"], [4, "<?=$lang['HISTORY_ENERGY']['MAY'];?>"], [5, "<?=$lang['HISTORY_ENERGY']['JUN'];?>"], [6, "<?=$lang['HISTORY_ENERGY']['JUL'];?>"], [7, "<?=$lang['HISTORY_ENERGY']['AUG'];?>"], [8, "<?=$lang['HISTORY_ENERGY']['SEP'];?>"], [9, "<?=$lang['HISTORY_ENERGY']['OCT'];?>"], [10, "<?=$lang['HISTORY_ENERGY']['NOV'];?>"], [11, "<?=$lang['HISTORY_ENERGY']['DEC'];?>"]
					];
				}
			},
			min: 0,
			max: (function(){
				if(period == "day"){
					return 23
				}
				else if(period == "week"){
					return 6
				}
				else if(period == "month"){
					return 30
				}
				else if(period == "quarter"){
					return 2
				}
				else if(period == "year"){
					return 11
				}
			})()
		},
		yaxis: {
			tickFormatter: function (val, axis) {
				return formating(val);
			}
		},
        grid: {
            hoverable: true,
            clickable: true,
    		borderWidth: {
				top: 0, right: 0, bottom: 1, left: 0
			}
        },
        tooltip: true,
        tooltipOpts: {
			content: function(label, xval, yval, flotItem){
				var date = new Date(flotItem.series.data[flotItem.dataIndex].timestamp);
				var yval = parseFloat((yval).toPrecision(7));

				if(period == "day"){
					return tipGenerator(yval, date.getFullYear() + "/" + padding(date.getMonth() + 1, 2) + "/" + padding(date.getDate(), 2) + " " + padding(date.getHours(), 2) + ":" + padding(date.getMinutes(), 2));
				}
				else if(period == "week"){
					return tipGenerator(yval, date.getFullYear() + "/" + padding(date.getMonth() + 1, 2) + "/" + padding(date.getDate(), 2));
				}
				else if(period == "month"){
					return tipGenerator(yval, date.getFullYear() + "/" + padding(date.getMonth() + 1, 2) + "/" + padding(date.getDate(), 2));
				}
				else if(period == "quarter"){
					return tipGenerator(yval, date.getFullYear() + "/" + padding(date.getMonth() + 1, 2));
				}
				else if(period == "year"){
					return tipGenerator(yval, date.getFullYear() + "/" + padding(date.getMonth() + 1, 2));
				}
			}
        }
    }, option);

	return $.plot("#" + id, dataSet, option);
}

function tipGenerator(value, time){
	var table = "<table>";

	if(typeof(time) != "undefined"){
		table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['HISTORY_ENERGY']['TIME_WITH_COLON'];?></td><td>" + time + "</td></tr>";
	}

	table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['HISTORY_ENERGY']['VALUE_WITH_COLON'];?></td><td>" + numberWithCommas(value) + "</td></tr></table>";

	return table;
}

function randomKey(length, chars){
	chars = chars || "ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
	var randomString = "";
	for (var i = 0; i < length; i++) {
		randomString += chars.charAt(Math.floor(Math.random() * chars.length));
	}

	return randomString;
}

function padding(number, length, paddingChar){
	paddingChar = typeof(paddingChar) == "undefined" ? "0" : paddingChar;

    var str = "" + number;
    while (str.length < length) {
        str = paddingChar + str;
    }
   
    return str;
}

function formating(value){
	if(isNaN(value)){
		return null;
	}
	else{
		var retObject = {
			"value": "",
			"unit": "",
			"join": function(string){
				return this.value + string + this.unit;
			},
			"toString": function(){
				return this.join("");
			}
		};

		if(value < 1000){
			var valueString = value.toString();

			for(var i = 0, counter = 0; counter < 4 && i < valueString.length; i++){
				if(valueString[i].match(/[0-9]/)){
					counter++;
				}

				retObject.value += valueString[i];
			}
		}
		else{
		    var i = -1;
		    var unitArray = ["K", "M", "B", "T"];

		    do {
		        value /= 1000;
		        i++;
		    } while (value >= 1000);

			retObject.value = (Math.floor(value * 10) / 10).toFixed(1);
			retObject.unit = unitArray[i];
		}

		return retObject;
	}
};

function formating2(value){
	if(isNaN(value)){
		return null;
	}
	else{
	    var unitIndex = 0;
	    var unitArray = ["", "K", "M", "B", "T"];

		while (value >= 1000){
	        value /= 1000;
	        unitIndex++;
		}

		var valueArray = [];
		var valueString = value.toString();
		for(var i = 0, counter = unitArray[unitIndex].length; counter < 5 && i < valueString.length; i++){
			if(valueString[i].match(/[0-9.]/)){
				counter++;
			}

			valueArray.push(valueString[i]);
		}

		//return parseFloat(valueArray.join("")).toString() + unitArray[unitIndex];

		if(valueArray[valueArray.length - 1] == "."){
			valueArray.pop();
		}

		return valueArray.join("") + unitArray[unitIndex];
	}
}

function numberWithCommas(x) {
	return x;

//    var parts = x.toString().split(".");
//    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
//    return parts.join(".");
}

function getTimeZone() {
	var offset = new Date().getTimezoneOffset(), o = Math.abs(offset);
	return (offset < 0 ? "+" : "-") + ("00" + Math.floor(o / 60)).slice(-2) + ":" + ("00" + (o % 60)).slice(-2);
}

function createDatePicker(type){//type: date, week
	return $("<div></div>").bind("set", function(event, year, month, day){
		var date = new Date(year, month - 1, day);
		$(this).attr("date", date.getTime()).data("date", date).triggerHandler("create", [date]);
	}).bind("prev", function(){
		var date = $(this).data("date");
		date.setDate(1);
		date.setMonth(date.getMonth() - 1);
		$(this).triggerHandler("create", [date]);
	}).bind("next", function(){
		var date = $(this).data("date");
		date.setDate(1);
		date.setMonth(date.getMonth() + 1);
		$(this).triggerHandler("create", [date]);
	}).bind("create", function(event, date){
		var $group = $(this).find("div.date-picker-row-group").empty();

		var now = new Date();
		now.setHours(0);
		now.setMinutes(0);
		now.setSeconds(0);
		now.setMilliseconds(0)

		var init = new Date(parseInt($(this).attr("date"), 10));

		var temp = new Date(date);
		temp.setDate(1);
		temp.setMonth(temp.getMonth() + 1);
		temp.setDate(0);
		var lastDate = temp.getDate();
		var loopEnd = lastDate + 6 - temp.getDay();
		temp.setDate(1);
		var loopStart = (temp.getDay() - 1) * - 1;

		temp.setDate(loopStart);
		for(var loopDate = loopStart, week = 0; loopDate <= loopEnd; loopDate++, week = (week + 1) % 7){
			if(week == 0){
				var $row = $("<div></div>").attr("class", "date-picker-row").appendTo($group);
			}

			var $cell = $("<div></div>").attr("class", "date-picker-cell").appendTo($row);

			if(loopDate < 1 || loopDate > lastDate){
				$cell.addClass("out");
			}

			if(temp.getTime() == init.getTime()){
				if(type == "date"){
					$cell.addClass("active");
				}
				else if(type == "week"){
					$row.addClass("active");
				}
			}

			if(temp.getTime() == now.getTime()){
				$cell.addClass("today")
			}

			$cell.data({
				"begin": new Date(temp.getTime()),
				"end": new Date(temp.getTime())
			}).text(temp.getDate()).bind("click", function(){
				var $select = $(this).closest(".time-button-container").find("div.time-button-select");
				var $option = $select.find("div.time-button-option.customized");

				if(type == "date"){
					$select.triggerHandler("showDesc", [$option, $(this).data("begin"), $(this).data("end")]);
				}
				else if(type == "week"){
					var $cells = $(this).closest("div.date-picker-row").find("div.date-picker-cell");

					$select.triggerHandler("showDesc", [$option, $cells.filter(":eq(0)").data("begin"), $cells.filter(":eq(-1)").data("end")]);
				}

				$option.triggerHandler("click.loadData");
				$(document).triggerHandler("click.time-button");
			});

			temp.setDate(temp.getDate() + 1);
		}

		$(this).find("div.date-picker-title span").text(date.getFullYear() + "/" + padding(date.getMonth() + 1, 2));

		// adjust position
		$(this).css({
			"top": "auto",
			"right": "auto",
			"bottom": "auto",
			"left": "auto"
		});

		if($(this).offset().left + $(this).outerWidth() > $(window).scrollLeft() + $(window).width() - 20){// out screen range
			$(this).css("right", 0);
		}

		if($(this).offset().top + $(this).outerHeight() > $(window).scrollTop() + $(window).height() - 20){
			$(this).css("bottom", ($(this).siblings("div.time-button").outerHeight() - 1) + "px");
		}
	}).attr("class", "time-picker date-picker").append(
		$("<div></div>").attr("class", "date-picker-title").append(
			$("<span></span>")
		).append(
			$("<div></div>").attr("class", "date-picker-switch").css("left", "1px").append(
				createSVGIcon("image/ics.svg", "chevron_left")
			).bind("click", function(){
				$(this).closest("div.date-picker").triggerHandler("prev");
			})
		).append(
			$("<div></div>").attr("class", "date-picker-switch").css("right", "1px").append(
				createSVGIcon("image/ics.svg", "chevron_right")
			).bind("click", function(){
				$(this).closest("div.date-picker").triggerHandler("next");
			})
		)
	).append(
		$("<div></div>").attr("class", "date-picker-table").append(
			$("<div></div>").attr("class", "date-picker-row day").append(
				$("<div></div>").attr("class", "date-picker-cell").text("<?=$lang['HISTORY_ENERGY']['SUN'];?>")
			).append(
				$("<div></div>").attr("class", "date-picker-cell").text("<?=$lang['HISTORY_ENERGY']['MON'];?>")
			).append(
				$("<div></div>").attr("class", "date-picker-cell").text("<?=$lang['HISTORY_ENERGY']['TUE'];?>")
			).append(
				$("<div></div>").attr("class", "date-picker-cell").text("<?=$lang['HISTORY_ENERGY']['WED'];?>")
			).append(
				$("<div></div>").attr("class", "date-picker-cell").text("<?=$lang['HISTORY_ENERGY']['THU'];?>")
			).append(
				$("<div></div>").attr("class", "date-picker-cell").text("<?=$lang['HISTORY_ENERGY']['FRI'];?>")
			).append(
				$("<div></div>").attr("class", "date-picker-cell").text("<?=$lang['HISTORY_ENERGY']['SAT'];?>")
			)
		).append(
			$("<div></div>").attr("class", "date-picker-row-group " + type)
		)
	).bind("click", function(event){
		event.stopPropagation();
		return;
	});
}

function createMonthPicker(type){//type: month, quarter
	var monthName = ["<?=$lang['HISTORY_ENERGY']['JAN'];?>", "<?=$lang['HISTORY_ENERGY']['FEB'];?>", "<?=$lang['HISTORY_ENERGY']['MAR'];?>", "<?=$lang['HISTORY_ENERGY']['APR'];?>", "<?=$lang['HISTORY_ENERGY']['MAY'];?>", "<?=$lang['HISTORY_ENERGY']['JUN'];?>", "<?=$lang['HISTORY_ENERGY']['JUL'];?>", "<?=$lang['HISTORY_ENERGY']['AUG'];?>", "<?=$lang['HISTORY_ENERGY']['SEP'];?>", "<?=$lang['HISTORY_ENERGY']['OCT'];?>", "<?=$lang['HISTORY_ENERGY']['NOV'];?>", "<?=$lang['HISTORY_ENERGY']['DEC'];?>"]

	return $("<div></div>").bind("set", function(event, year, month){
		var date = new Date(year, month - 1, 1);
		$(this).attr("date", date.getTime()).data("date", date).triggerHandler("create", [date]);
	}).bind("prev", function(){
		var date = $(this).data("date");
		date.setDate(1);
		date.setMonth(0);
		date.setFullYear(date.getFullYear() - 1);
		$(this).triggerHandler("create", [date]);
	}).bind("next", function(){
		var date = $(this).data("date");
		date.setDate(1);
		date.setMonth(0);
		date.setFullYear(date.getFullYear() + 1);
		$(this).triggerHandler("create", [date]);
	}).bind("create", function(event, date){
		var $group = $(this).find("div.month-picker-row-group").empty();

		var now = new Date();
		now.setDate(1);
		now.setHours(0);
		now.setMinutes(0);
		now.setSeconds(0);
		now.setMilliseconds(0)

		var init = new Date(parseInt($(this).attr("date"), 10));

		var temp = new Date(date);
		temp.setDate(1);
		temp.setMonth(0);

		for(var loopMonth = 0; loopMonth < 12; loopMonth++){
			if(temp.getMonth() % 3 == 0){
				var $row = $("<div></div>").attr("class", "month-picker-row").appendTo($group).append(
					$("<div></div>").attr("class", "month-picker-cell").text("Q" + (Math.floor(temp.getMonth() / 3) + 1))
				);
			}

			var $cell = $("<div></div>").attr("class", "month-picker-cell").appendTo($row);

			if(temp.getTime() == init.getTime()){
				if(type == "month"){
					$cell.addClass("active");
				}
				else if(type == "quarter"){
					$row.addClass("active");
				}
			}

			if(temp.getTime() == now.getTime()){
				$cell.addClass("today")
			}

			var end = new Date(temp);
			end.setMonth(end.getMonth() + 1);
			end.setDate(0);

			$cell.data({
				"begin": new Date(temp.getTime()),
				"end": new Date(end.getTime())
			}).text(monthName[temp.getMonth()]).bind("click", function(){
				var $select = $(this).closest(".time-button-container").find("div.time-button-select");
				var $option = $select.find("div.time-button-option.customized");

				if(type == "month"){
					$select.triggerHandler("showDesc", [$option, $(this).data("begin"), $(this).data("end")]);
				}
				else if(type == "quarter"){
					var $cells = $(this).closest("div.month-picker-row").find("div.month-picker-cell");

					$select.triggerHandler("showDesc", [$option, $cells.filter(":eq(1)").data("begin"), $cells.filter(":eq(-1)").data("end")]);
				}

				$option.triggerHandler("click.loadData");
				$(document).triggerHandler("click.time-button");
			});

			temp.setMonth(temp.getMonth() + 1);
		}

		$(this).find("div.month-picker-title span").text(date.getFullYear());

		// adjust position
		$(this).css({
			"top": "auto",
			"right": "auto",
			"bottom": "auto",
			"left": "auto"
		});

		if($(this).offset().left + $(this).outerWidth() > $(window).scrollLeft() + $(window).width() - 20){// out screen range
			$(this).css("right", 0);
		}

		if($(this).offset().top + $(this).outerHeight() > $(window).scrollTop() + $(window).height() - 20){
			$(this).css("bottom", ($(this).siblings("div.time-button").outerHeight() - 1) + "px");
		}
	}).attr("class", "time-picker month-picker").append(
		$("<div></div>").attr("class", "month-picker-title").append(
			$("<span></span>")
		).append(
			$("<div></div>").attr("class", "month-picker-switch").css("left", "1px").append(
				createSVGIcon("image/ics.svg", "chevron_left")
			).bind("click", function(){
				$(this).closest("div.month-picker").triggerHandler("prev");
			})
		).append(
			$("<div></div>").attr("class", "month-picker-switch").css("right", "1px").append(
				createSVGIcon("image/ics.svg", "chevron_right")
			).bind("click", function(){
				$(this).closest("div.month-picker").triggerHandler("next");
			})
		)
	).append(
		$("<div></div>").attr("class", "month-picker-table").append(
			$("<div></div>").attr("class", "month-picker-row")
		).append(
			$("<div></div>").attr("class", "month-picker-row-group " + type)
		)
	).bind("click", function(event){
		event.stopPropagation();
		return;
	});
}

function createYearPicker(){
	return $("<div></div>").bind("set", function(event, year){
		var date = new Date(year, 0, 1);
		$(this).attr("date", date.getTime()).data("date", date).triggerHandler("create", [date]);
	}).bind("prev", function(){
		var date = $(this).data("date");
		date.setDate(1);
		date.setMonth(0);
		date.setFullYear(date.getFullYear() - 9);
		$(this).triggerHandler("create", [date]);
	}).bind("next", function(){
		var date = $(this).data("date");
		date.setDate(1);
		date.setMonth(0);
		date.setFullYear(date.getFullYear() + 9);
		$(this).triggerHandler("create", [date]);
	}).bind("create", function(event, date){
		var $group = $(this).find("div.year-picker-row-group").empty();

		var now = new Date();
		now.setMonth(0);
		now.setDate(1);
		now.setHours(0);
		now.setMinutes(0);
		now.setSeconds(0);
		now.setMilliseconds(0)

		var init = new Date(parseInt($(this).attr("date"), 10));

		var temp = new Date(date);
		temp.setDate(1);
		temp.setMonth(0);
		temp.setFullYear(temp.getFullYear() + 4);
		var loopEnd = temp.getFullYear();
		temp.setFullYear(temp.getFullYear() - 8);
		var loopStart = temp.getFullYear();

		temp.setFullYear(loopStart);
		for(var loopYear = loopStart, counter = 0; loopYear <= loopEnd; loopYear++, counter++){
			if(counter % 3 == 0){
				var $row = $("<div></div>").attr("class", "year-picker-row").appendTo($group);
			}

			var $cell = $("<div></div>").attr("class", "year-picker-cell").appendTo($row);

			if(temp.getTime() == init.getTime()){
				$cell.addClass("active");
			}

			if(temp.getTime() == now.getTime()){
				$cell.addClass("today")
			}

			var end = new Date(temp);
			end.setFullYear(end.getFullYear() + 1);
			end.setDate(0);

			$cell.data({
				"begin": new Date(temp.getTime()),
				"end": new Date(end.getTime())
			}).text(temp.getFullYear()).bind("click", function(){
				var $select = $(this).closest(".time-button-container").find("div.time-button-select");
				var $option = $select.find("div.time-button-option.customized");

				$select.triggerHandler("showDesc", [$option, $(this).data("begin"), $(this).data("end")]);

				$option.triggerHandler("click.loadData");
				$(document).triggerHandler("click.time-button");
			});

			temp.setFullYear(temp.getFullYear() + 1);
		}

		$(this).find("div.year-picker-title span").text(loopStart + " ~ " + loopEnd);

		// adjust position
		$(this).css({
			"top": "auto",
			"right": "auto",
			"bottom": "auto",
			"left": "auto"
		});

		if($(this).offset().left + $(this).outerWidth() > $(window).scrollLeft() + $(window).width() - 20){// out screen range
			$(this).css("right", 0);
		}

		if($(this).offset().top + $(this).outerHeight() > $(window).scrollTop() + $(window).height() - 20){
			$(this).css("bottom", ($(this).siblings("div.time-button").outerHeight() - 1) + "px");
		}
	}).attr("class", "time-picker year-picker").append(
		$("<div></div>").attr("class", "year-picker-title").append(
			$("<span></span>")
		).append(
			$("<div></div>").attr("class", "year-picker-switch").css("left", "1px").append(
				createSVGIcon("image/ics.svg", "chevron_left")
			).bind("click", function(){
				$(this).closest("div.year-picker").triggerHandler("prev");
			})
		).append(
			$("<div></div>").attr("class", "year-picker-switch").css("right", "1px").append(
				createSVGIcon("image/ics.svg", "chevron_right")
			).bind("click", function(){
				$(this).closest("div.year-picker").triggerHandler("next");
			})
		)
	).append(
		$("<div></div>").attr("class", "year-picker-table").append(
			$("<div></div>").attr("class", "year-picker-row-group")
		)
	).bind("click", function(event){
		event.stopPropagation();
		return;
	});
}

function hideChannelSelector(direction){
	var deferred = $.Deferred();
	var factor = direction == "right" ? 1 : -1;

	// animate selector
	$("#select-container").parent().css("overflow", "hidden");
	$("#select-container").css({
		"opacity": 1,
		"marginLeft": 0
	}).animate({
		"opacity": 0,
		"marginLeft": (factor * 200) + "px"
	}, "fast", function(){
		$(this).hide();

		//show chart
		$("#chart-container").parent().css("overflow", "");
		$("#chart-container").show();

		deferred.resolve();
	});

	return deferred.promise();
}

function createSVGIcon(path, name){
	var svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
	var use = document.createElementNS("http://www.w3.org/2000/svg", 'use');
	use.setAttributeNS("http://www.w3.org/1999/xlink", 'xlink:href', path + '#' + name);
	svg.appendChild(use);
	return svg;
}

function popupWindow($popupWindow, callback){
	var $wrapper = $popupWindow.show().find(".popup-wrapper");
	$wrapper.css("marginTop", ($(window).height() / 2 - $wrapper.outerHeight() / 2 + $(window).scrollTop()) + "px");
	windowCallback = callback;
}

function onClickWindowButton(button){
	if(typeof(windowCallback) == "function"){
		windowCallback(button);
	}
}

function showCarbonFootprintFactorWindow(){
	$("#window-carbon-footprint-factor").val(carbonFootprintFactor);

	popupWindow($("#window-carbon-footprint"), function(result){
		if(result == "ok"){
			if($("#window-carbon-footprint-factor").val() == ""){
				popupErrorWindow("<?=$lang['HISTORY_ENERGY']['POPUP']['FACTOR_IS_EMPTY'];?>");
				return;
			}

			if(isNaN($("#window-carbon-footprint-factor").val())){
				popupErrorWindow("<?=$lang['HISTORY_ENERGY']['POPUP']['FACTOR_MUST_BE_NUMBER'];?>");
				return;
			}

			if(parseFloat($("#window-carbon-footprint-factor").val()) <= 0){
				popupErrorWindow("<?=$lang['HISTORY_ENERGY']['POPUP']['FACTOR_MUST_BE_GREATER_THAN_ZERO'];?>");
				return;
			}

			carbonFootprintFactor = parseFloat($("#window-carbon-footprint-factor").val());
			$("#window-carbon-footprint").hide();

			$("div.time-button-container[time-type='major']:eq(0)").find("div.time-button-select div.time-button-option.active").triggerHandler("click");
			$("div.time-button-container[time-type='compare']:eq(0)").find("div.time-button-select div.time-button-option.active").triggerHandler("click");

			$.ajax({
				url: "history_energy_ajax.php?act=set_carbon_footprint_factor&factor=" + $("#window-carbon-footprint-factor").val(),
				type: "POST",
				dataType: "xml",
				success: function(data, textStatus, jqXHR){},
				error: function(jqXHR, textStatus, errorThrown){
					if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

					alert(jqXHR.responseText);
				},
				complete: function(){}
			});
		}
		else if(result == "cancel"){
			$("#window-carbon-footprint").hide();
		}
	});

	$("#window-carbon-footprint-factor").focus().select();
}

$(document).ready(function(){
	// time range
	$(".time-button").bind("click.time-button", function(event){
		if(!$(this).hasClass("active")){
			$(document).triggerHandler("click.time-button");//close everyone
			$(this).addClass("active").siblings("div.time-button-select").show();
			event.stopPropagation();
		}
	});

	$(document).bind("click.time-button", function(){
		$(".time-button").removeClass("active").siblings("div").hide();
	});

	$(window).resize(function(){
		$("div.time-button-container[time-type='major']:eq(0)").find("div.time-button-select div.time-button-option.active").triggerHandler("click.loadData");
		$("div.time-button-container[time-type='compare']:eq(0)").find("div.time-button-select div.time-button-option.active").triggerHandler("click.loadData");
	});

	// time period
	$(".period-button").bind("click", function(){
		$("div.period-button").removeClass("active").filter("[period='" + $(this).attr("period") + "']").addClass("active");

		var $containers = $("div.time-button-container");
		var $select = $containers.find("div.time-button-select").empty().unbind("showDesc");
		$containers.find("div.time-picker").remove();

		if($(this).attr("period") == "day"){
			$select.bind("showDesc", function(event, $option, begin, end){
				$option.attr({
					"begin": begin.getFullYear() + "-" + padding(begin.getMonth() + 1, 2) + "-" + padding(begin.getDate(), 2) + " 00:00:00",
					"end": end.getFullYear() + "-" + padding(end.getMonth() + 1, 2) + "-" + padding(end.getDate(), 2) + " 23:59:59"
				}).find("div.time-button-option-desc").text(begin.getFullYear() + "/" + padding(begin.getMonth() + 1, 2) + "/" + padding(begin.getDate(), 2))
			});

			//
			var begin = new Date(), end = begin;
			var $option = $("<div></div>").addClass("time-button-option").append(
				$("<div></div>").addClass("time-button-option-name").text("<?=$lang['HISTORY_ENERGY']['TODAY'];?>")
			).append(
				$("<div></div>").addClass("time-button-option-desc")
			).appendTo($select);

			$select.triggerHandler("showDesc", [$option, begin, end]);

			//
			var begin = new Date(), end = begin;
			begin.setDate(begin.getDate() - 1);
			var $option = $("<div></div>").addClass("time-button-option").append(
				$("<div></div>").addClass("time-button-option-name").text("<?=$lang['HISTORY_ENERGY']['YESTERDAY'];?>")
			).append(
				$("<div></div>").addClass("time-button-option-desc")
			).appendTo($select);

			$select.triggerHandler("showDesc", [$option, begin, end]);

			//
			var begin = new Date(), end = begin;
			begin.setDate(begin.getDate() - 7);
			var $option = $("<div></div>").addClass("time-button-option").append(
				$("<div></div>").addClass("time-button-option-name").text("<?=$lang['HISTORY_ENERGY']['TODAY_LAST_WEEK'];?>")
			).append(
				$("<div></div>").addClass("time-button-option-desc")
			).appendTo($select);

			$select.triggerHandler("showDesc", [$option, begin, end]);

			// Customized Date
			var $option = $("<div></div>").addClass("time-button-option customized").append(
				$("<div></div>").addClass("time-button-option-name").text("<?=$lang['HISTORY_ENERGY']['SPECIFY_DATE'];?>")
			).append(
				$("<div></div>").addClass("time-button-option-desc")
			).bind("click.showPicker", function(event){
				var $container = $(this).closest("div.time-button-container");
				var dateArray = $container.attr("begin").match(/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/);

				$container.find("div.time-picker").show().triggerHandler("set", [dateArray[1], dateArray[2], dateArray[3]]);
				$(this).closest("div.time-button-select").hide();

				event.stopPropagation();
				event.stopImmediatePropagation();
			}).appendTo($select);

			createDatePicker("date").appendTo($containers);
		}
		else if($(this).attr("period") == "week"){
			$select.bind("showDesc", function(event, $option, begin, end){
				$option.attr({
					"begin": begin.getFullYear() + "-" + padding(begin.getMonth() + 1, 2) + "-" + padding(begin.getDate(), 2) + " 00:00:00",
					"end": end.getFullYear() + "-" + padding(end.getMonth() + 1, 2) + "-" + padding(end.getDate(), 2) + " 23:59:59"
				}).find("div.time-button-option-desc").text(begin.getFullYear() + "/" + padding(begin.getMonth() + 1, 2) + "/" + padding(begin.getDate(), 2) + " ~ " + end.getFullYear() + "/" + padding(end.getMonth() + 1, 2) + "/" + padding(end.getDate(), 2))
			});

			//
			var begin = new Date(), end = new Date();
			begin.setDate(begin.getDate() - begin.getDay());
			end.setDate(end.getDate() + (6 - end.getDay()));
			var $option = $("<div></div>").addClass("time-button-option").append(
				$("<div></div>").addClass("time-button-option-name").text("<?=$lang['HISTORY_ENERGY']['THIS_WEEK'];?>")
			).append(
				$("<div></div>").addClass("time-button-option-desc")
			).appendTo($select);

			$select.triggerHandler("showDesc", [$option, begin, end]);

			//
			var begin = new Date(), end = new Date();
			begin.setDate(begin.getDate() - begin.getDay() - 7);
			end.setDate(end.getDate() - end.getDay() - 1);
			var $option = $("<div></div>").addClass("time-button-option").append(
				$("<div></div>").addClass("time-button-option-name").text("<?=$lang['HISTORY_ENERGY']['LAST_WEEK'];?>")
			).append(
				$("<div></div>").addClass("time-button-option-desc")
			).appendTo($select);

			$select.triggerHandler("showDesc", [$option, begin, end]);

			// Customized Date
			var $option = $("<div></div>").addClass("time-button-option customized").append(
				$("<div></div>").addClass("time-button-option-name").text("<?=$lang['HISTORY_ENERGY']['SPECIFY_DATE'];?>")
			).append(
				$("<div></div>").addClass("time-button-option-desc")
			).bind("click.showPicker", function(event){
				var $container = $(this).closest("div.time-button-container");
				var dateArray = $container.attr("begin").match(/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/);

				$container.find("div.time-picker").show().triggerHandler("set", [dateArray[1], dateArray[2], dateArray[3]]);
				$(this).closest("div.time-button-select").hide();

				event.stopPropagation();
				event.stopImmediatePropagation();
			}).appendTo($select);

			createDatePicker("week").appendTo($containers);
		}
		else if($(this).attr("period") == "month"){
			$select.bind("showDesc", function(event, $option, begin, end){
				$option.attr({
					"begin": begin.getFullYear() + "-" + padding(begin.getMonth() + 1, 2) + "-" + padding(begin.getDate(), 2) + " 00:00:00",
					"end": end.getFullYear() + "-" + padding(end.getMonth() + 1, 2) + "-" + padding(end.getDate(), 2) + " 23:59:59"
				}).find("div.time-button-option-desc").text(begin.getFullYear() + "/" + padding(begin.getMonth() + 1, 2) + "/" + padding(begin.getDate(), 2) + " ~ " + end.getFullYear() + "/" + padding(end.getMonth() + 1, 2) + "/" + padding(end.getDate(), 2))
			});

			//
			var begin = new Date(), end = new Date();
			begin.setDate(1);
			end.setDate(1);
			end.setMonth(end.getMonth() + 1);
			end.setDate(0);
			var $option = $("<div></div>").addClass("time-button-option").append(
				$("<div></div>").addClass("time-button-option-name").text("<?=$lang['HISTORY_ENERGY']['THIS_MONTH'];?>")
			).append(
				$("<div></div>").addClass("time-button-option-desc")
			).appendTo($select);

			$select.triggerHandler("showDesc", [$option, begin, end]);

			//
			var begin = new Date(), end = new Date();
			begin.setDate(1);
			begin.setMonth(begin.getMonth() - 1);
			end.setDate(1);
			end.setDate(0);
			var $option = $("<div></div>").addClass("time-button-option").append(
				$("<div></div>").addClass("time-button-option-name").text("<?=$lang['HISTORY_ENERGY']['LAST_MONTH'];?>")
			).append(
				$("<div></div>").addClass("time-button-option-desc")
			).appendTo($select);

			$select.triggerHandler("showDesc", [$option, begin, end]);

			//
			var begin = new Date(), end = new Date();
			begin.setDate(1);
			begin.setFullYear(begin.getFullYear() - 1);
			end.setDate(1);
			end.setMonth(end.getMonth() + 1);
			end.setFullYear(end.getFullYear() - 1);
			end.setDate(0);
			var $option = $("<div></div>").addClass("time-button-option").append(
				$("<div></div>").addClass("time-button-option-name").text("<?=$lang['HISTORY_ENERGY']['THIS_MONTH_LAST_YEAR'];?>")
			).append(
				$("<div></div>").addClass("time-button-option-desc")
			).appendTo($select);

			$select.triggerHandler("showDesc", [$option, begin, end]);

			// Customized Date
			var $option = $("<div></div>").addClass("time-button-option customized").append(
				$("<div></div>").addClass("time-button-option-name").text("<?=$lang['HISTORY_ENERGY']['SPECIFY_DATE'];?>")
			).append(
				$("<div></div>").addClass("time-button-option-desc")
			).bind("click.showPicker", function(event){
				var $container = $(this).closest("div.time-button-container");
				var dateArray = $container.attr("begin").match(/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/);

				$container.find("div.time-picker").show().triggerHandler("set", [dateArray[1], dateArray[2]]);
				$(this).closest("div.time-button-select").hide();

				event.stopPropagation();
				event.stopImmediatePropagation();
			}).appendTo($select);

			createMonthPicker("month").appendTo($containers);
		}
		else if($(this).attr("period") == "quarter"){
			$select.bind("showDesc", function(event, $option, begin, end){
				$option.attr({
					"begin": begin.getFullYear() + "-" + padding(begin.getMonth() + 1, 2) + "-" + padding(begin.getDate(), 2) + " 00:00:00",
					"end": end.getFullYear() + "-" + padding(end.getMonth() + 1, 2) + "-" + padding(end.getDate(), 2) + " 23:59:59"
				}).find("div.time-button-option-desc").text(begin.getFullYear() + "/" + padding(begin.getMonth() + 1, 2) + "/" + padding(begin.getDate(), 2) + " ~ " + end.getFullYear() + "/" + padding(end.getMonth() + 1, 2) + "/" + padding(end.getDate(), 2))
			});

			//
			var begin = new Date(), end = new Date();
			var quarter = Math.floor((begin.getMonth() + 3) / 3);//1 ~ 4
			begin.setDate(1);
			begin.setMonth((quarter - 1) * 3);
			end.setDate(1);
			end.setMonth((quarter - 1 + 1) * 3);
			end.setDate(0);
			var $option = $("<div></div>").addClass("time-button-option").append(
				$("<div></div>").addClass("time-button-option-name").text("<?=$lang['HISTORY_ENERGY']['THIS_QUARTER'];?>")
			).append(
				$("<div></div>").addClass("time-button-option-desc")
			).appendTo($select);

			$select.triggerHandler("showDesc", [$option, begin, end]);

			//
			var begin = new Date(), end = new Date();
			var quarter = Math.floor((begin.getMonth() + 3) / 3);//1 ~ 4
			if(quarter == 1){//last quarter is last year Q4
				begin.setDate(1);
				begin.setMonth((quarter + 2) * 3);
				begin.setFullYear(begin.getFullYear() - 1);
			}
			else{
				begin.setDate(1);
				begin.setMonth((quarter - 2) * 3);
			}

			end.setDate(1);
			end.setMonth((quarter - 1) * 3);
			end.setDate(0);
			var $option = $("<div></div>").addClass("time-button-option").append(
				$("<div></div>").addClass("time-button-option-name").text("<?=$lang['HISTORY_ENERGY']['LAST_QUARTER'];?>")
			).append(
				$("<div></div>").addClass("time-button-option-desc")
			).appendTo($select);

			$select.triggerHandler("showDesc", [$option, begin, end]);

			//
			var begin = new Date(), end = new Date();
			var quarter = Math.floor((begin.getMonth() + 3) / 3);//1 ~ 4
			begin.setDate(1);
			begin.setMonth((quarter - 1) * 3);
			begin.setFullYear(begin.getFullYear() - 1);
			end.setDate(1);
			end.setMonth(quarter * 3);
			end.setFullYear(end.getFullYear() - 1);
			end.setDate(0);
			var $option = $("<div></div>").addClass("time-button-option").append(
				$("<div></div>").addClass("time-button-option-name").text("<?=$lang['HISTORY_ENERGY']['THIS_QUARTER_LAST_YEAR'];?>")
			).append(
				$("<div></div>").addClass("time-button-option-desc")
			).appendTo($select);

			$select.triggerHandler("showDesc", [$option, begin, end]);

			// Customized Date
			$("<div></div>").addClass("time-button-option customized").append(
				$("<div></div>").addClass("time-button-option-name").text("<?=$lang['HISTORY_ENERGY']['SPECIFY_DATE'];?>")
			).append(
				$("<div></div>").addClass("time-button-option-desc")
			).bind("click.showPicker", function(event){
				var $container = $(this).closest("div.time-button-container");
				var dateArray = $container.attr("begin").match(/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/);

				$container.find("div.time-picker").show().triggerHandler("set", [dateArray[1], dateArray[2]]);
				$(this).closest("div.time-button-select").hide();

				event.stopPropagation();
				event.stopImmediatePropagation();
			}).appendTo($select);

			createMonthPicker("quarter").appendTo($containers);
		}
		else if($(this).attr("period") == "year"){
			$select.bind("showDesc", function(event, $option, begin, end){
				$option.attr({
					"begin": begin.getFullYear() + "-" + padding(begin.getMonth() + 1, 2) + "-" + padding(begin.getDate(), 2) + " 00:00:00",
					"end": end.getFullYear() + "-" + padding(end.getMonth() + 1, 2) + "-" + padding(end.getDate(), 2) + " 23:59:59"
				}).find("div.time-button-option-desc").text(begin.getFullYear() + "/" + padding(begin.getMonth() + 1, 2) + "/" + padding(begin.getDate(), 2) + " ~ " + end.getFullYear() + "/" + padding(end.getMonth() + 1, 2) + "/" + padding(end.getDate(), 2))
			});

			//
			var begin = new Date(), end = new Date();
			begin.setDate(1);
			begin.setMonth(0);
			end.setDate(1);
			end.setMonth(0);
			end.setFullYear(end.getFullYear() + 1);
			end.setDate(0);
			var $option = $("<div></div>").addClass("time-button-option").append(
				$("<div></div>").addClass("time-button-option-name").text("<?=$lang['HISTORY_ENERGY']['THIS_YEAR'];?>")
			).append(
				$("<div></div>").addClass("time-button-option-desc")
			).appendTo($select);

			$select.triggerHandler("showDesc", [$option, begin, end]);

			//
			var begin = new Date(), end = new Date();
			begin.setDate(1);
			begin.setMonth(0);
			begin.setFullYear(begin.getFullYear() - 1);
			end.setDate(1);
			end.setMonth(0);
			end.setDate(0);
			var $option = $("<div></div>").addClass("time-button-option").append(
				$("<div></div>").addClass("time-button-option-name").text("<?=$lang['HISTORY_ENERGY']['LAST_YEAR'];?>")
			).append(
				$("<div></div>").addClass("time-button-option-desc")
			).appendTo($select);

			$select.triggerHandler("showDesc", [$option, begin, end]);

			// Customized Date
			var $option = $("<div></div>").addClass("time-button-option customized").append(
				$("<div></div>").addClass("time-button-option-name").text("<?=$lang['HISTORY_ENERGY']['SPECIFY_DATE'];?>")
			).append(
				$("<div></div>").addClass("time-button-option-desc")
			).bind("click.showPicker", function(event){
				var $container = $(this).closest("div.time-button-container");
				var dateArray = $container.attr("begin").match(/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/);

				$container.find("div.time-picker").show().triggerHandler("set", [dateArray[1]]);
				$(this).closest("div.time-button-select").hide();

				event.stopPropagation();
				event.stopImmediatePropagation();
			}).appendTo($select);

			createYearPicker().appendTo($containers);
		}

		var width = $(".time-button-container div.time-button-select").width();
		$("div.time-button-container div.time-button").css("width", width + "px");

		$("div.time-button-option").bind("click.loadData", function(event){
			var timeType = $(this).closest(".time-button-container").attr("time-type");

			var $containers = $("div.time-button-container[time-type='" + timeType + "']").attr({
				"begin": $(this).attr("begin"),
				"end": $(this).attr("end")
			});

			$containers.find("div.time-button div.time-button-option-name").text($(this).find("div.time-button-option-name").text());
			$containers.find("div.time-button div.time-button-option-desc").text($(this).find("div.time-button-option-desc").text());

			$(this).addClass("active").siblings().removeClass("active");

			loadLoopKWH(timeType, $($containers[0]).attr("begin"), $($containers[0]).attr("end"));

			if(isGroup == true){
				$("#energy-quality").hide();
			}
			else{
				if(timeType == "major"){
					$("#power-item-container div.active").triggerHandler("click");
				}
			}
		});

		delete dataSet[0].data;
		delete dataSet[1].data;

		$("div.time-button-container[time-type='major']:eq(0)").find("div.time-button-select div.time-button-option:eq(0)").triggerHandler("click");
		$("div.time-button-container[time-type='compare']:eq(0)").find("div.time-button-select div.time-button-option:eq(1)").triggerHandler("click");
	});

	$("#power-item-container div[type]").bind("click", function(){
		$(this).addClass("active").siblings().removeClass("active");

		var $container = $("div.time-button-container[time-type='major']:eq(0)");

		loadLoopData($(this).attr("type"), $container.attr("begin"), $container.attr("end"));
	});

	$("div.power-phase-content-value, #chart-kwh div.info-value, #carbon-footprint div.info-value, #chart-kwh div.info-compare-value, #carbon-footprint div.info-compare-value").bind("mousemove", function(event){
		if(isNaN(parseFloat($(this).attr("value")))){return;}

		var $tip = $("#" + $(this).attr("valueTip"));

		if($tip.length == 0){
			id = randomKey(8);
			$(this).attr("valueTip", id);

			$tip = $("body > div.flotTip").clone().removeClass("flotTip").addClass("valueTip").attr("id", id).html((function(value, time){
				if(typeof(time) != "undefined"){
					var date = new Date(parseInt(time, 10));
					var value = parseFloat((value).toPrecision(7));
					var period = $("div.period-button-container:eq(0)").find("div.period-button.active").attr("period");

					if(period == "day"){
						return tipGenerator(value, date.getFullYear() + "/" + padding(date.getMonth() + 1, 2) + "/" + padding(date.getDate(), 2) + " " + padding(date.getHours(), 2) + ":" + padding(date.getMinutes(), 2));
					}
					else if(period == "week"){
						return tipGenerator(value, date.getFullYear() + "/" + padding(date.getMonth() + 1, 2) + "/" + padding(date.getDate(), 2));
					}
					else if(period == "month"){
						return tipGenerator(value, date.getFullYear() + "/" + padding(date.getMonth() + 1, 2) + "/" + padding(date.getDate(), 2));
					}
					else if(period == "quarter"){
						return tipGenerator(value, date.getFullYear() + "/" + padding(date.getMonth() + 1, 2));
					}
					else if(period == "year"){
						return tipGenerator(value, date.getFullYear() + "/" + padding(date.getMonth() + 1, 2));
					}
				}
				else{
					var value = parseFloat((value).toPrecision(7));
					return tipGenerator(value);
				}
			})(parseFloat($(this).attr("value")), $(this).attr("time"))).show();

			$("body").append($tip);
		}

		// mouse position
		var pageX = event.pageX + 10;
		var pageY = event.pageY + 19;

		// out screen range
		if(pageX + $tip.outerWidth() > $(window).scrollLeft() + $(window).width()){
			pageX = event.pageX - $tip.outerWidth();
		}

		if(pageY + $tip.outerHeight() > $(window).scrollTop() + $(window).height()){
			pageY = event.pageY - $tip.outerHeight();
		}

		// delay position
		$tip.css({
			"left": ($tip.attr("page-x") || pageX) + "px",
			"top": ($tip.attr("page-y") || pageY) + "px"
		}).attr({
			"page-x": pageX,
			"page-y": pageY
		});
	}).bind("mouseleave", function(){
		$("#" + $(this).attr("valueTip")).remove();
	});

	$("#select-device").show();
	$("#select-device div.select-loader").show();

	loadDeviceList().always(function(){
		$("#select-device div.select-loader").hide();
	});
});
</script>
<?php
}
?>

<?php
function customized_body(){
	global $lang;
?>
<div style="padding:20px;">
	<div style="position:relative;">
		<div id="select-container">
			<div class="title" style="border-bottom-width:0px;padding-bottom:0px;"><?=$lang['HISTORY_ENERGY']['SELECT_A_LOOP_OR_GROUP'];?></div>

			<div id="select-device">
				<div class="select-wrapper">
					<div class="select-list"></div>
					<div class="select-loader"><img src="./image/login-loader.gif"></div>
				</div>
			</div>

			<div id="select-module">
				<div class="select-wrapper">
					<div class="select-list"></div>
					<div class="select-loader"><img src="./image/login-loader.gif"></div>
					<div class="select-arrow" style="position:absolute;top:50%;left:0;margin-left:-24px;">
						<svg><use xlink:href="image/ics.svg#arrow_forward"></use></svg>
					</div>
				</div>
			</div>

			<div id="select-channel">
				<div class="select-wrapper">
					<div class="select-list"></div>
					<div class="select-loader"><img src="./image/login-loader.gif"></div>
					<div class="select-arrow" style="position:absolute;top:50%;left:0;margin-left:-24px;">
						<svg><use xlink:href="image/ics.svg#arrow_forward"></use></svg>
					</div>
				</div>
			</div>
		</div>

		<!--<div style="clear:both;"></div>-->

		<div id="chart-container">
			<div class="title" style="position:relative;"><?=$lang['HISTORY_ENERGY']['ENERGY_ANALYSIS'];?>
				<div class="sub-title"></div>
			</div>

			<div class="period-button-container">
				<div class="period-button" period="day" unit="hour"><?=$lang['HISTORY_ENERGY']['DAY'];?></div>
				<div class="period-button" period="week" unit="day"><?=$lang['HISTORY_ENERGY']['WEEK'];?></div>
				<div class="period-button" period="month" unit="day"><?=$lang['HISTORY_ENERGY']['MONTH'];?></div>
				<div class="period-button" period="quarter" unit="month"><?=$lang['HISTORY_ENERGY']['QUARTER'];?></div>
				<div class="period-button" period="year" unit="month"><?=$lang['HISTORY_ENERGY']['YEAR'];?></div>
			</div>

			<div class="time-button-wrapper">
				<div><?=$lang['HISTORY_ENERGY']['TIME'];?></div>
				<div class="time-button-container" time-type="major">
					<div class="time-button">
						<div style="display: table-row;">
							<div class="time-button-option-name" style="width:100%;"></div><div class="time-button-option-desc"></div>
						</div>
						<div style="position:absolute;right:4px;top:50%;margin-top:-9px;">
							<svg><use xlink:href="image/ics.svg#arrow_drop_down"></use></svg>
						</div>
					</div>
					<div class="time-button-select"></div>
				</div>

				<div style="font-size:0;margin:0 10px;"><svg style="width:24px;height:24px;fill:#606060;"><use xlink:href="image/ics.svg#swap_horiz"></use></svg></div>

				<div><?=$lang['HISTORY_ENERGY']['COMPARED'];?></div>
				<div class="time-button-container" time-type="compare" id="time-compare">
					<div class="time-button">
						<div style="display: table-row;">
							<div class="time-button-option-name" style="width:100%;"></div><div class="time-button-option-desc"></div>
						</div>
						<div style="position:absolute;right:4px;top:50%;margin-top:-9px;">
							<svg><use xlink:href="image/ics.svg#arrow_drop_down"></use></svg>
						</div>
					</div>
					<div class="time-button-select"></div>
				</div>
			</div>

			<div style="clear:both;"></div>

			<div id="layout-wrapper">
				<div id="layout-left">
					<div id="energy-wrapper">
						<div id="energy-container">
							<div id="energy-chart"></div>
							<div id="energy-empty" class="chart-empty">
								<div><?=$lang['HISTORY_ENERGY']['NO_DATA'];?></div>
							</div>
							<div id="energy-loader" class="chart-loader">
								<div><img src="image/ajax-loader.gif"></div>
							</div>
						</div>
					</div>
				</div>
				<div id="layout-right">
					<div style="display:table-row;">
						<div class="info-container" id="chart-kwh">
							<div class="info-title"><?=$lang['HISTORY_ENERGY']['ENERGY_CONSUMPTION'];?></div>
							<div class="info-value"><span>-</span><div class="info-value-unit-container"><div class="info-value-unit right"><?=$lang['HISTORY_ENERGY']['KWH'];?></div></div></div>
							<div class="info-compare-table">
								<div class="info-compare-row">
									<div class="info-compare-cell"><?=$lang['HISTORY_ENERGY']['COMPARED'];?></div>
									<div class="info-compare-cell info-compare-value">-</div>
									<div class="info-compare-cell info-compare-value-unit"><?=$lang['HISTORY_ENERGY']['KWH'];?></div>
								</div>
							</div>
						</div>
					</div>

					<div style="display:table-row;height:10px;"></div>

					<div style="display:table-row;">
						<div class="info-container" id="carbon-footprint" style="margin:10px 0;position:relative;">
							<div class="info-title"><?=$lang['HISTORY_ENERGY']['CARBON_EMISSIONS'];?></div>
							<div class="info-value"><span>-</span><div class="info-value-unit-container"><div class="info-value-unit right"><?=$lang['HISTORY_ENERGY']['KG'];?></div></div></div>
							<div class="info-compare-table">
								<div class="info-compare-row">
									<div class="info-compare-cell"><?=$lang['HISTORY_ENERGY']['COMPARED'];?></div>
									<div class="info-compare-cell info-compare-value">-</div>
									<div class="info-compare-cell info-compare-value-unit"><?=$lang['HISTORY_ENERGY']['KG'];?></div>
								</div>
							</div>

							<div id="carbon-footprint-factor-setting-button" style="" onClick="showCarbonFootprintFactorWindow();">
								<svg><use xlink:href="image/ics.svg#settings"></use></svg>
							</div>
						</div>
					</div>

					<div style="display:table-row;height:10px;"></div>

					<div style="display:table-row;">
						<div class="info-container" id="chart-compare">
							<div class="info-title"><?=$lang['HISTORY_ENERGY']['GROWTH_RATE'];?></div>
							<div class="info-value"><span>-</span><div class="info-value-unit-container"><div class="info-value-unit right">%</div></div></div>
						</div>
					</div>
				</div>
				<div style="clear:both;"></div>
			</div>

			<div id="energy-quality">
				<div class="title" style="position:relative;margin-top:40px;"><?=$lang['HISTORY_ENERGY']['POWER_DATA_ANALYSIS'];?></div>

				<div class="period-button-container">
					<div class="period-button" period="day" unit="hour"><?=$lang['HISTORY_ENERGY']['DAY'];?></div>
					<div class="period-button" period="week" unit="day"><?=$lang['HISTORY_ENERGY']['WEEK'];?></div>
					<div class="period-button" period="month" unit="day"><?=$lang['HISTORY_ENERGY']['MONTH'];?></div>
					<div class="period-button" period="quarter" unit="month"><?=$lang['HISTORY_ENERGY']['QUARTER'];?></div>
					<div class="period-button" period="year" unit="month"><?=$lang['HISTORY_ENERGY']['YEAR'];?></div>
				</div>

				<div class="time-button-wrapper">
					<div><?=$lang['HISTORY_ENERGY']['TIME'];?></div>
					<div class="time-button-container" time-type="major" style="display:inline-block;vertical-align:middle;">
						<div class="time-button">
							<div style="display: table-row;">
								<div class="time-button-option-name" style="width:100%;"></div><div class="time-button-option-desc"></div>
							</div>
							<div style="position:absolute;right:4px;top:50%;margin-top:-9px;">
								<svg><use xlink:href="image/ics.svg#arrow_drop_down"></use></svg>
							</div>
						</div>
						<div class="time-button-select"></div>
					</div>
				</div>

				<div style="clear:both;"></div>

				<div id="power-wrapper">
					<div id="power-item-container">
						<div class="power-item active" type="V" style="margin-left:10px;"><?=$lang['HISTORY_ENERGY']['V'];?></div>
						<div class="power-item" type="I"><?=$lang['HISTORY_ENERGY']['I'];?></div>
						<div class="power-item" type="KW"><?=$lang['HISTORY_ENERGY']['KW'];?></div>
						<div class="power-item" type="KVAR"><?=$lang['HISTORY_ENERGY']['KVAR'];?></div>
						<div class="power-item" type="KVA"><?=$lang['HISTORY_ENERGY']['KVA'];?></div>
						<div class="power-item" type="PF"><?=$lang['HISTORY_ENERGY']['PF'];?></div>
				<!--
						<div type="KWH">kWh</div>
						<div type="KVARH">kvarh</div>
						<div type="KVAH">kVAh</div>
				-->
						<div id="power-item-hr"></div>
						<div style="clear:both;"></div>
					</div>
					
					<div id="power-container">
						<div id="power-chart"></div>
						<div id="power-empty" class="chart-empty">
							<div><?=$lang['HISTORY_ENERGY']['NO_DATA'];?></div>
						</div>
						<div id="power-loader" class="chart-loader">
							<div><img src="image/ajax-loader.gif"></div>
						</div>
					</div>

					<div id="power-phase">
						<div class="power-phase-wrapper" id="power-phase-1" style="padding:0 4px 0 0;">
							<div class="power-phase-container red">
								<div class="power-phase-title red"><?=$lang['HISTORY_ENERGY']['PHASE_A'];?></div>
								<div class="power-phase-content-max">
									<div class="power-phase-content-title"><?=$lang['HISTORY_ENERGY']['MAX'];?></div>
									<div class="power-phase-content-value"><span>-</span><div class="power-phase-content-value-unit-container"><div class="power-phase-content-value-unit"></div></div></div>
									<div class="power-phase-content-time">-</div>
								</div>
								<div class="power-phase-content-min">
									<div class="power-phase-content-title"><?=$lang['HISTORY_ENERGY']['MIN'];?></div>
									<div class="power-phase-content-value"><span>-</span><div class="power-phase-content-value-unit-container"><div class="power-phase-content-value-unit"></div></div></div>
									<div class="power-phase-content-time">-</div>
								</div>
								<div style="clear:both;"></div>
							</div>
						</div>

						<div class="power-phase-wrapper" id="power-phase-2" style="padding:0 2px 0 2px;">
							<div class="power-phase-container green">
								<div class="power-phase-title green"><?=$lang['HISTORY_ENERGY']['PHASE_B'];?></div>
								<div class="power-phase-content-max">
									<div class="power-phase-content-title"><?=$lang['HISTORY_ENERGY']['MAX'];?></div>
									<div class="power-phase-content-value"><span>-</span><div class="power-phase-content-value-unit-container"><div class="power-phase-content-value-unit"></div></div></div>
									<div class="power-phase-content-time">-</div>
								</div>
								<div class="power-phase-content-min">
									<div class="power-phase-content-title"><?=$lang['HISTORY_ENERGY']['MIN'];?></div>
									<div class="power-phase-content-value"><span>-</span><div class="power-phase-content-value-unit-container"><div class="power-phase-content-value-unit"></div></div></div>
									<div class="power-phase-content-time">-</div>
								</div>
								<div style="clear:both;"></div>
							</div>
						</div>

						<div class="power-phase-wrapper" id="power-phase-3" style="padding:0 0 0 4px;">
							<div class="power-phase-container blue">
								<div class="power-phase-title blue"><?=$lang['HISTORY_ENERGY']['PHASE_C'];?></div>
								<div class="power-phase-content-max">
									<div class="power-phase-content-title"><?=$lang['HISTORY_ENERGY']['MAX'];?></div>
									<div class="power-phase-content-value"><span>-</span><div class="power-phase-content-value-unit-container"><div class="power-phase-content-value-unit"></div></div></div>
									<div class="power-phase-content-time">-</div>
								</div>
								<div class="power-phase-content-min">
									<div class="power-phase-content-title"><?=$lang['HISTORY_ENERGY']['MIN'];?></div>
									<div class="power-phase-content-value"><span>-</span><div class="power-phase-content-value-unit-container"><div class="power-phase-content-value-unit"></div></div></div>
									<div class="power-phase-content-time">-</div>
								</div>
								<div style="clear:both;"></div>
							</div>
						</div>

						<div class="power-phase-wrapper" id="power-phase-4" style="padding:0 4px 0 0;">
							<div class="power-phase-container purple">
								<div class="power-phase-title purple"><?=$lang['HISTORY_ENERGY']['TOTAL'];?></div>
								<div class="power-phase-content-max">
									<div class="power-phase-content-title"><?=$lang['HISTORY_ENERGY']['MAX'];?></div>
									<div class="power-phase-content-value"><span>-</span><div class="power-phase-content-value-unit-container"><div class="power-phase-content-value-unit"></div></div></div>
									<div class="power-phase-content-time">-</div>
								</div>
								<div class="power-phase-content-min">
									<div class="power-phase-content-title"><?=$lang['HISTORY_ENERGY']['MIN'];?></div>
									<div class="power-phase-content-value"><span>-</span><div class="power-phase-content-value-unit-container"><div class="power-phase-content-value-unit"></div></div></div>
									<div class="power-phase-content-time">-</div>
								</div>
								<div style="clear:both;"></div>
							</div>
						</div>
					</div>
					<div style="clear:both;"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="popup-background" id="window-carbon-footprint" style="text-align: center;">
	<div class="popup-wrapper" style="width:auto;min-width:350px;display:inline-block;">
		<div class="popup-container">
			<div class="popup-title"><?=$lang['HISTORY_ENERGY']['CARBON_EMISSIONS_SETTING'];?></div>
			<div class="popup-content" style="text-align:center;padding:20px;box-sizing: border-box;">
				<?=$lang['HISTORY_ENERGY']['FACTOR'];?>&nbsp;&nbsp;&nbsp;<input type="text" id="window-carbon-footprint-factor">
			</div>
			<div class="popup-footer">
				<input type="button" value="<?=$lang['OK'];?>" onclick="onClickWindowButton('ok');">&nbsp;&nbsp;<input type="button" class="gray" value="<?=$lang['CANCEL'];?>" onclick="onClickWindowButton('cancel');">
			</div>
		</div>
	</div>
</div>
<?php
}
?>