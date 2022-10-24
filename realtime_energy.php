<?php
function customized_header(){
	global $lang;
?>
<link rel="stylesheet" type="text/css" href="./css/picker.css">
<link rel="stylesheet" type="text/css" href="./css/tip.css">
<link rel="stylesheet" type="text/css" href="./css/thumbnail.css">
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
	color:#555;
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
}

.select-option{
	margin:2px;
	padding:6px 8px;
	border:1px solid #fff;
	background: #fff;
	cursor:pointer;
	position: relative;
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

#select-channel-warning{
	position:absolute;
	bottom:7px;
	width:100%;
	color:#5e3c0b;
	font-size:13px;
	box-sizing: border-box;
    background-color: #fffbe7;
	border:1px solid #caae19;
	line-height: 1.5;
    padding: 5px;
	display:none;
}

#select-channel-warning div.arrow{
	position:absolute;
	bottom:-8px;
	left:50%;
	margin-left:-8px;
	font-size:0;
}
/******************/
#chart-container{
	width:100%;
	top:0;
	left:0;
	display:none;
	/*opacity:0;*/
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
	color:#888;
	font-size:24px;
}

#analysis-wrapper{
	margin-top:20px;
	width:100%;
	padding:20px;
	background-color:#FFF;
	box-sizing: border-box;
	border: 1px solid #ccc;
}

#item-container{
	background-color:#FFF;
	position:relative;
	overflow:hidden;
}

#item-container div.item{
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

#item-container div.item.active{
	border-width:1px 1px 1px 1px;
	border-bottom-color:#FFF;
	padding:7px 0 8px 0;
	font-weight:bold;
	color:#545454;
	background-color:#fff;
	top:0px;
	cursor:default;
}

#item-container div.item:first-child{
	margin-left:10px;
}

#item-hr{
	position:absolute;
	bottom:1px;
	left:0;
	border-style:solid;
	border-color:#545454;
	border-width:0 0 1px 0;
	box-shadow: 0px 1px 0px #d9d9d9;
	z-index:5;
}

#analysis-container{
	clear:both;
	width:100%;
	position:relative;
	top:-9px;
	/*left:-11px;*/
}

#chart{
	width:100%;
	height:400px;
}

#thumbnail-chart-container{
	position:relative;
	height:100px;
	margin-top:10px;
	-webkit-touch-callout: none; /* iOS Safari */
	-webkit-user-select: none; /* Chrome/Safari/Opera */
	-khtml-user-select: none; /* Konqueror */
	-moz-user-select: none; /* Firefox */
	-ms-user-select: none; /* Internet Explorer/Edge */
	user-select: none; /* Non-prefixed version, currently
                                  not supported by any browser */
}

#thumbnail-chart{
	width:100%;
	height:100%;
}

#thumbnail-selector{
	width:100%;
	height:68px;
	position:absolute;
	top:8px;
	cursor:move;
}

#phase-container{
	margin-top:20px;
}

.phase-wrapper{
	float:left;
	width:33.33%;
	box-sizing: border-box;
}

.phase-container{
	border-style:solid;
	border-width:1px;
	width:100%;
	box-sizing: border-box;
    box-shadow: 0 1px 1px rgba(0,0,0,0.6);
}

.phase-container.red{
	border-color:#953734;
}

.phase-container.green{
	border-color:#76923c;
}

.phase-container.blue{
	border-color:#366092;
}

.phase-container.purple{
	border-color:#5f497a;
}

.phase-title{
	color:#545454;
	text-align:center;
	padding:4px 0;
	border-style:solid;
	border-color:#545454;
	border-width:0 0 1px 0;
}

.phase-title.red{
	background-color:#f2dcdb;
	border-color:#953734;
}

.phase-title.green{
	background-color:#ebf1dd;
	border-color:#76923c;
}

.phase-title.blue{
	background-color:#c6d9f0;
	border-color:#366092;
}

.phase-title.purple{
	background-color:#e5e0ec;
	border-color:#5f497a;
}

.phase-content-max{
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

.phase-content-min{
	float:right;
	width:50%;
	box-sizing: border-box;
	padding:20px 0;
    text-align: center;
}

.phase-content-title{
	text-align:center;
	font-size:13px;
	color:#777;
}

.phase-content-value{
	text-align:center;
	font-size:40px;
	font-weight:600;
	color:#555;
	position: relative;
	display:inline-block;
	cursor:default;
}

.phase-content-value-unit-container{
	display:inline-block;
	position:relative;
}

.phase-content-value-unit{
	position:absolute;
	font-size:13px;
	left: 3px;
	bottom:0;
	font-weight:normal;
}

.phase-content-time{
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
<script language="javascript" src="./js/jquery.easing.min.js"></script>
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
var dataPool = {
	"V": [],
	"I": [],
	"KW": [],
	"KVAR": [],
	"KVA": [],
	"PF": [],
	"KWH": [],
	"KVARH": [],
	"KVAH": []
}, ajax;

var maxTimestamp = (new Date()).getTime();

function loadDeviceList(){
	$("#select-device div.select-list").empty();
	$("#select-module, #select-channel").hide();

	return $.ajax({
		url: "realtime_energy_ajax.php?act=get_device_list",
		type: "POST",
		dataType: "xml",
		success: function(data, textStatus, jqXHR){
			var $devices = $(data).find("list > device");
			if($devices.length > 0){
				$("#select-device div.select-list").append(
					$("<div></div>").attr("class", "select-title").text("<?=$lang['HISTORY_IO']['DEVICE'];?>")
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
							"device_model_name": $device.attr("model_name"),
							"admin_password": $device.attr("admin_password"),
							"disabled": $device.attr("online") == "0" || !$device.attr("model_name").match(/^(PMC|PMD)/i) ? true : null
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
									"tip": "<?=$lang['REALTIME_IO']['TIP']['SHARE_BY_USER'];?>".replace("%username%", $device.attr("account_nickname") + "(" + $device.attr("account_username") + ")")
								}).append(
									createSVGIcon("image/ics.svg", "share")
								);
							}
						})())
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
		url: "realtime_energy_ajax.php?act=get_module_list",
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
		url: "realtime_energy_ajax.php?act=get_channel_list",
		type: "POST",
		data: "account_uid=" + accountUID + "&device_uid=" + deviceUID + "&module_uid=" + moduleUID,
		dataType: "xml",
		success: function(data, textStatus, jqXHR){
			// Create option(All loop)
			$("#select-channel div.select-list").append(
				$("<div></div>").attr("class", "select-title").text("<?=$lang['REALTIME_ENERGY']['LOOP'];?>")
			);

			var $loops = $(data).find("list > loop");
			if($loops.length > 0){
				for(var i = 0; i < $loops.length; i++){
					var $loop = $($loops[i]);

					var $types = $loop.find("> phase > *");
					var typeArray = [];
					$types.each(function(){
						var phase = parseInt($(this).parent().attr("index"), 10) + 1;
						var type = $(this).prop("tagName").toUpperCase();

						if($("#select-module div.select-list div.select-option.active").attr("phase") == "1"){
							typeArray.push(type);
						}
						else if($("#select-module div.select-list div.select-option.active").attr("phase") == "3"){
							if(type == "V" || type == "I" || type == "PF"){
								if(phase == 1 || phase == 2 || phase == 3){
									typeArray.push(type);
								}
							}
							else{
								if(phase == 4){
									typeArray.push(type);
								}
							}
						}
					});

					$("#select-channel div.select-list").append(
						$("<div></div>").attr({
							"class": "select-option",
							"_loop": i + 1,//attr loop is use in video
							"disabled": typeArray.length <= 0 ? true : null
						}).text("<?=$lang['REALTIME_ENERGY']['LOOP'];?>" + (i + 1) + ($loop.attr("nickname") ? "(" + $loop.attr("nickname") + ")" : "")).bind("click", typeArray, function(event){
							if($(this).attr("disabled")){
								return;
							}

							// Check the container is running animate
							if($("#select-container").is(":animated")){
								return;
							}

							$(this).addClass("active").siblings().removeClass("active");

							$("div.sub-title").text($("#select-device div.select-list div.select-option.active").text() + " / " + $("#select-module div.select-list div.select-option.active").text() + " / " + $("#select-channel div.select-list div.select-option.active").text());

							// Remove not send back power data type(V, I, ...)
							$("#item-container div[type]").attr("disabled", true);
							for(var i = 0; i < event.data.length; i++){
								$("#item-container div[type='" + event.data[i] + "']").removeAttr("disabled");
							}
							$("#item-container div[disabled]").remove();

							hideChannelSelector().always(function(){
								var date = new Date();
								var second = date.getSeconds();
								var ms = second * 1000 + date.getMilliseconds()
								var startUpdateAfter = ((Math.ceil(second / 5) * 5000) - ms);
								setTimeout(function(){
									updateLoop();
									setInterval(updateLoop, 5000);
								}, startUpdateAfter);

								$("#item-container div[type]:first").triggerHandler("click", false)
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
			$("#select-channel-warning").show().css({
				"bottom": "20px",
				"opacity": 0
			}).stop();

			$("#select-channel").show();
			$("#select-channel div.select-wrapper").show().css({
				"left": "-" + ($("#select-channel div.select-wrapper").width() / 4) + "px",
				"opacity": 0
			}).animate({
				"left": 0,
				"opacity": 1
			}, "fast", function(){
				var modelName = $("#select-device div.select-list div.select-option.active").attr("device_model_name");
				if(modelName.match(/^WISE-523/i) || modelName.match(/^WISE-224/i)){
					version = "1.5.0";
				}
				else if(modelName.match(/^WISE-284/i)){
					version = "1.0.0";
				}
				else if(modelName.match(/^PMC-523/i) || modelName.match(/^PMC-224/i)){
					version = "3.4.6";
				}
				else if(modelName.match(/^PMC-284/i)){
					version = "1.0.0";
				}
				else if(modelName.match(/^PMD/i)){
					version = "3.4.6";
				}

				if(version){
					var serialNumber = $("#select-device div.select-list div.select-option.active").attr("device_uid");
					var loginPassword = $("#select-device div.select-list div.select-option.active").attr("admin_password");

					$("#select-channel-warning-text")
						.html(
							"<?=$lang['REALTIME_ENERGY']['TIP']['SETUP_SENDING_SETTING_FIRST'];?>"
								.replace("%version%", version)
								.replace("%sending_setting%", loginPassword ? "<a href=\"javascript:void(0);\" onClick=\"onClickGoToSettingPage('" + serialNumber + "', '" + loginPassword + "');\"><?=$lang['REALTIME_ENERGY']['TIP']['REALTIME_DATA_SENDING_SETTING'];?></a>" : "<?=$lang['REALTIME_IO']['TIP']['REALTIME_DATA_SENDING_SETTING'];?>")
						);

					$("#select-channel-warning").animate({
						"bottom": "7px",
						"opacity": 1
					}, "slow", "easeOutQuint");
				}
			});
		}
	});
}

function refreshChart(){
	updateChart();

	var $plot = $("#thumbnail-chart").data("plot");
	var data = $plot.getData()[0];
	var selectorWidth = data.xaxis.p2c(maxTimestamp);

	$("#thumbnail-selector").attr({
		min: data.xaxis.c2p($("#thumbnail-selector div.thumbnail-selector-out.left").width()),
		max: data.xaxis.c2p(selectorWidth - $("#thumbnail-selector div.thumbnail-selector-out.right").width())
	});

	updateChartRange();

	$("#item-hr").css("right", data.yaxis.box.width + "px");

	// Update loop / phase information
	var getDate = function(timestamp){
		var date = new Date(timestamp);

		return padding(date.getHours(), 2) + ":" + padding(date.getMinutes(), 2) + ":" + padding(date.getSeconds(), 2);
	};

	// Clear first
	$("#phase-container div.phase-content-max div.phase-content-value").removeAttr("value time").find("span").text("-");
	$("#phase-container div.phase-content-min div.phase-content-value").removeAttr("value time").find("span").text("-");
	$("#phase-container div.phase-content-max div.phase-content-time").text("-");
	$("#phase-container div.phase-content-min div.phase-content-time").text("-");

	// fill out
	var type = $("#item-container div.active").attr("type");
	var data = dataPool[type];
	for(var index = 0; index < data.length; index++){
		if(typeof(data[index]) == "undefined"){continue;}

		$("#phase-" + (index + 1) + " div.phase-content-max div.phase-content-value").attr({
			"value": data[index].data[data[index].data.maxIndex][1],
			"time": data[index].data[data[index].data.maxIndex][0]
		}).find("span").text(formating2(data[index].data[data[index].data.maxIndex][1]));

		$("#phase-" + (index + 1) + " div.phase-content-min div.phase-content-value").attr({
			"value": data[index].data[data[index].data.minIndex][1],
			"time": data[index].data[data[index].data.minIndex][0],
		}).find("span").text(formating2(data[index].data[data[index].data.minIndex][1]));

		$("#phase-" + (index + 1) + " div.phase-content-max div.phase-content-time").text(getDate(data[index].data[data[index].data.maxIndex][0]));
		$("#phase-" + (index + 1) + " div.phase-content-min div.phase-content-time").text(getDate(data[index].data[data[index].data.minIndex][0]));
	}
}

function updateLoop(){
	var date = new Date();
	date.setMilliseconds(0);
	maxTimestamp = date.getTime();

	loadLoopData(maxTimestamp, $("#select-device div.select-list div.select-option.active").attr("account_uid"), $("#select-device div.select-list div.select-option.active").attr("device_uid"), $("#select-module div.select-list div.select-option.active").attr("module_uid"), $("#select-channel div.select-list div.select-option.active").attr("_loop"));
}

function getModuleData(accountUID, deviceUID, moduleUID, loop){
	return $.ajax({
		url: "realtime_energy_ajax.php?act=get_module_data",
		type: "POST",
		data: "account_uid=" + accountUID + "&device_uid=" + deviceUID + "&module_uid=" + moduleUID + "&loop=" + loop,
		cache: false,
		dataType: "xml",
		timeout: 15000
	});
}

function loadLoopData(timestamp, accountUID, deviceUID, moduleUID, loop){
	if(typeof(ajax) != "undefined"){
		return;
	}

	ajax = getModuleData(accountUID, deviceUID, moduleUID, loop).done(function(data){
		var $channels = $(data).find("list > phase > *");
		for(var i = 0; i < $channels.length; i++){
			var $channel = $($channels[i]);
			var value = parseFloat($channel.text());

			if(isNaN(value)){
				continue;
			}

			var type = $channel.prop("tagName");
			var phaseIndex = parseInt($channel.parent().attr("index"), 10);

			if(typeof(dataPool[type.toUpperCase()][phaseIndex]) == "undefined"){
				dataPool[type.toUpperCase()][phaseIndex] = {
					"data": [],
					"color": ["#953734", "#76923c", "#366092", "#5f497a"][phaseIndex]
				};
			}

			dataPool[type.toUpperCase()][phaseIndex].data.push([timestamp, value]);

			processChartData(dataPool[type.toUpperCase()][phaseIndex].data);
		}
	}).always(function(){
		$("#loader").hide();

		var isDrag = false;
		var mousemoveEvents = $._data(window, "events").mousemove || [];
		for(var i = 0; i < mousemoveEvents.length; i++){
			if(mousemoveEvents[i].namespace == "thumbnailChart"){
				isDrag = true;
				break;
			}
		}

		if(isDrag == false){
			refreshChart();
		}

		ajax = undefined;
	});
}

function processChartData(data){
	var max = -Infinity, min = Infinity;
	data.maxIndex = -1;
	data.minIndex = -1;

	for(var i = 0; i < data.length; i++){
		if(data[i][1] <= min){
			min = data[i][1];
			data.minIndex = i;
		}

		if(data[i][1] >= max){
			max = data[i][1];
			data.maxIndex = i;
		}
	}

	return data;
}

function updateChart(){
	var dataSet = [];

	var visible = [];
	$("#phase-container div.phase-wrapper:visible").each(function(){
		visible[parseInt($(this).attr("id").split("-")[1], 10) - 1] = true;
	});

	var seriesPool = dataPool[$("#item-container div.active").attr("type")];
	for(var index = 0; index < seriesPool.length; index++){
		if(visible[index] != true || seriesPool[index] === undefined){continue;}

		var series = seriesPool[index];
		dataSet.push(series);
	}

	if(dataSet.length <= 0){
		dataSet.push({
			data: []
		});

		$("#empty").show();
	}
	else{
		$("#empty").hide();
	}

	drawChart(dataSet);
	drawThumbnailChart(dataSet);

	//$("#thumbnail-chart .tickLabel").hide();
	$("#thumbnail-chart .flot-y-axis .flot-tick-label").css("visibility", "hidden");

	var $plot = $("#thumbnail-chart").data("plot");
	var data = $plot.getData()[0];

	var selectorWidth = data.xaxis.p2c(maxTimestamp);
	$("#thumbnail-selector").css({
		"width": selectorWidth + "px",
		"right": data.yaxis.box.width + "px"
	});

	if(!$("#thumbnail-selector").attr("min")){
		var min = maxTimestamp - (5 * 60 * 1000);
		var max = maxTimestamp;

		$("#thumbnail-selector").attr({
			min: min,
			max: max
		});

		$("#thumbnail-selector div.thumbnail-selector-out.left").css("width", data.xaxis.p2c(min) + "px");
		$("#thumbnail-selector div.thumbnail-selector-out.right").css("width", (selectorWidth - data.xaxis.p2c(max)) + "px");
	}
}

function drawChart(dataSet, option){
	yAxisLabelMaxLengthWidth = 0;

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
			mode: "time",
			timezone: "browser",
			tickLength: 0,
			timeformat: "%H:%M",
			min: maxTimestamp - (60 * 60 * 1000),
			max: maxTimestamp
		},
		yaxis: {
			position: "right",
			tickFormatter: function (val, axis) {
				// Calculate yaxis label width of longest string
				var formatter = formating(val);
				var string = formatter.toString();
				var width = $("#stringLength").text(string).width();
				if(width > yAxisLabelMaxLengthWidth){
					yAxisLabelMaxLengthString = string;
					yAxisLabelMaxLengthWidth = width;
				}

				return string;
			}
		},
        grid: {
            hoverable: true,
            clickable: true,
    		borderWidth: {
				top: 1, right: 0, bottom: 1, left: 0
			}
        },
        tooltip: true,
        tooltipOpts: {
			content: function(label, xval, yval, flotItem){
				var date = new Date(xval);
				var yval = parseFloat((yval).toPrecision(7));

				var sourceType = flotItem.series.sourceType ? "[source_type='" + flotItem.series.sourceType + "']" : "";
				var sourceIndex = flotItem.series.sourceIndex ? "[source_index='" + flotItem.series.sourceIndex + "']" : "";
				var moduleIndex = flotItem.series.moduleIndex ? "[module_index='" + flotItem.series.moduleIndex + "']" : "";

				return tipGenerator(yval, date.getFullYear() + "/" + padding(date.getMonth() + 1, 2) + "/" + padding(date.getDate(), 2) + " " + padding(date.getHours(), 2) + ":" + padding(date.getMinutes(), 2) + ":" + padding(date.getSeconds(), 2));
			}
        }
    }, option);

	var $plot = $.plot("#chart", dataSet, option);
	$("#chart").data("plot", $plot);

	return $plot;
}

function drawThumbnailChart(dataSet, option){
	option = $.extend(true, {
        series: {
            lines: {
                show: true,
				lineWidth: 1
            },
            points: {
                show: false,
				symbol: function(ctx, x, y, radius, shadow){//hide point
					ctx.arc(x, y, 0, 0, 0, false);
				}
            },
			shadowSize: 0
        },
		xaxis: {
			mode: "time",
			timezone: "browser",
			tickLength: 5,
			timeformat: "%H:%M",
			min: maxTimestamp - (60 * 60 * 1000),
			max: maxTimestamp
		},
		yaxis: {
			position: "right",
			tickLength: 0,
			tickFormatter: function (val, axis) {
				return yAxisLabelMaxLengthString;
			}
		},
        grid: {
            hoverable: true,
            clickable: true,
    		borderWidth: {
				top: 0, right: 0, bottom: 0, left: 0
			}
        },
        tooltip: false
    }, option);

	var $plot = $.plot("#thumbnail-chart", dataSet, option);
	$("#thumbnail-chart").data("plot", $plot);

	return $plot;
}

function updateChartRange(){
	if(!$("#empty").is(":visible")){
		var $plot = $("#chart").data("plot");

		var $select = $("#thumbnail-selector");
		var min = parseFloat($select.attr("min"));
		var max = parseFloat($select.attr("max"));

		$.each($plot.getXAxes(), function(_, axis) {
			var opts = axis.options;
			opts.min = min;
			opts.max = max;
		});

		$plot.setupGrid();
		$plot.draw();
	}
}

function tipGenerator(value, time){
	var table = "<table>";

	if(typeof(time) != "undefined"){
		table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['REALTIME_ENERGY']['TIME_WITH_COLON'];?></td><td>" + time + "</td></tr>";
	}

	table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['REALTIME_ENERGY']['VALUE_WITH_COLON'];?></td><td>" + numberWithCommas(value) + "</td></tr></table>";

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
			var valueString = parseFloat((value).toPrecision(12)).toString();

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

function hideChannelSelector(direction){
	var deferred = $.Deferred();
	var factor = direction == "right" ? 1 : -1;

	$("#select-channel-warning").hide();

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

function onClickGoToSettingPage(serialNumber, loginPassword){
	setCookie("password_" + serialNumber, loginPassword, 1);

	$("<a></a>").attr({
		"href": "./" + serialNumber + "/#home/iot/realtime",
		"target": serialNumber//"target": "_blank"
	}).hide().appendTo("body")[0].click();
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

$(document).ready(function(){
	$("#item-container div[type]").bind("click", function(event, refresh){
		$(this).addClass("active").siblings().removeClass("active");

		// Show or hide loop / phase information block
		var type = $("#item-container div.active").attr("type");

		if($("#select-module div.select-list div.select-option.active").attr("phase") == "1"){
			phase = "1";

			$("#phase-1 div.phase-title").text("<?=$lang['REALTIME_ENERGY']['LOOP'];?>");
		}
		else if($("#select-module div.select-list div.select-option.active").attr("phase") == "3"){
			if(type == "V" || type == "I" || type == "PF"){
				phase = "123";
			}
			else{
				phase = "4";
			}

			$("#phase-1 div.phase-title").text("<?=$lang['REALTIME_ENERGY']['PHASE_A'];?>");
		}

		$(".phase-wrapper").hide();

		for(var i = 0; i < phase.length; i++){
			$("#phase-" + (parseInt(phase[i], 10))).show();
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
		else if(type == "KWH"){
			unit = "kWh";
		}
		else if(type == "KVARH"){
			unit = "kvarh";
		}
		else if(type == "KVAH"){
			unit = "kVAh";
		}

		$("#phase-container div.phase-content-value-unit").text(unit);

		// Update chart
		refreshChart();
	});

	$("#phase-container div.phase-content-value").bind("mousemove", function(event){
		if(isNaN(parseFloat($(this).attr("value")))){return;}

		var $tip = $("#" + $(this).attr("valueTip"));

		if($tip.length == 0){
			id = randomKey(8);
			$(this).attr("valueTip", id);

			$tip = $("body > div.flotTip").clone().removeClass("flotTip").addClass("valueTip").attr("id", id).html((function(value, time){
				var value = parseFloat((value).toPrecision(7));

				if(typeof(time) != "undefined"){
					var date = new Date(parseInt(time, 10));

					return tipGenerator(value, padding(date.getHours(), 2) + ":" + padding(date.getMinutes(), 2) + ":" + padding(date.getSeconds(), 2));
				}
				else{
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

	$(window).resize(function(){
		if(!$("#chart-container").is(":visible")){return;}

		updateChart();

		var $plot = $("#thumbnail-chart").data("plot");
		var data = $plot.getData()[0];
		var selectorWidth = data.xaxis.p2c(maxTimestamp);
		var $select = $("#thumbnail-selector");

		$("#thumbnail-selector div.thumbnail-selector-out.left").css("width", data.xaxis.p2c(parseFloat($select.attr("min"))) + "px");
		$("#thumbnail-selector div.thumbnail-selector-out.right").css("width", (selectorWidth - data.xaxis.p2c(parseFloat($select.attr("max")))) + "px");

		updateChartRange("chart");
	});

	$("#thumbnail-selector").bind("mousedown", function(event){
		var $plot = $("#thumbnail-chart").data("plot");
		var data = $plot.getData()[0];
		var offset = $plot.offset();
		var selectorWidth = data.xaxis.p2c(maxTimestamp);
		var _posX = event.pageX - offset.left;//mouse down position

		var $leftCell = $(this).find("div.thumbnail-selector-out.left");
		var $rightCell = $(this).find("div.thumbnail-selector-out.right");

		var leftCellWidth = $leftCell.width();
		var rightCellWidth = $rightCell.width();
		var maxCellWidth = leftCellWidth + rightCellWidth;

		$leftCell.attr("width", leftCellWidth);
		$rightCell.attr("width", rightCellWidth);

		$(window).bind("mousemove.thumbnailChart", function(event){
			var posX = event.pageX - offset.left;

			var leftCellWidth = (parseFloat($leftCell.attr("width")) + (posX - _posX));
			var rightCellWidth = (parseFloat($rightCell.attr("width")) + (_posX - posX));

			if(leftCellWidth > maxCellWidth){
				leftCellWidth = maxCellWidth;
			}

			if(rightCellWidth > maxCellWidth){
				rightCellWidth = maxCellWidth;
			}

			$leftCell.css("width", leftCellWidth + "px");
			$rightCell.css("width", rightCellWidth + "px");
		}).bind("mouseup.thumbnailChart", function(event){
			refreshChart();

			$(window).unbind(".thumbnailChart");
		});
	});

	$(".thumbnail-selector-handler").bind("mousedown", function(event){
		var $plot = $("#thumbnail-chart").data("plot");
		var data = $plot.getData()[0];
		var offset = $plot.offset();
		var selectorWidth = data.xaxis.p2c(maxTimestamp);
		//var yaxisWidth = data.yaxis.box.width;
		var _posX = event.pageX - offset.left;//mouse down position

		var $currentCell = $(this).closest("div.thumbnail-selector-out");
		var $otherCell = $currentCell.siblings("div.thumbnail-selector-out");
		var otherCellWidth = $otherCell.width();
		var isRight = $currentCell.hasClass("right");

		$currentCell.attr("width", $currentCell.width());

		$(window).bind("mousemove.thumbnailChart", function(event){
			var posX = event.pageX - offset.left;

			var currentCellWidth = parseFloat($currentCell.attr("width")) + ((isRight ? -1 : 1) * (posX - _posX));
			var maxCellWidth = selectorWidth - otherCellWidth - 15;

			if(currentCellWidth > maxCellWidth){
				currentCellWidth = maxCellWidth;
			}

			$currentCell.css("width", currentCellWidth + "px");
		}).bind("mouseup.thumbnailChart", function(event){
			refreshChart();

			$(window).unbind(".thumbnailChart");
		});
	}).closest("div.thumbnail-selector-out").bind("mousedown", function(event){
		event.stopPropagation();
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
			<div class="title" style="border-bottom-width:0px;padding-bottom:0px;"><?=$lang['REALTIME_ENERGY']['SELECT_A_LOOP'];?></div>

			<div id="select-device">
				<div class="select-wrapper">
					<div class="select-list"></div>
					<div class="select-loader"><img src="./image/ajax-loader.gif"></div>
				</div>
			</div>

			<div id="select-module">
				<div class="select-wrapper">
					<div class="select-list"></div>
					<div class="select-loader"><img src="./image/ajax-loader.gif"></div>
					<div class="select-arrow" style="position:absolute;top:50%;left:0;margin-left:-24px;">
						<svg><use xlink:href="image/ics.svg#arrow_forward"></use></svg>
					</div>
				</div>
			</div>

			<div id="select-channel">
				<div style="position:relative;">
					<div id="select-channel-warning">
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td valign="top" style="font-size:0;padding-right:5px;"><svg style="fill:#f1b500;width:24px;height:24px;"><use xlink:href="image/ics.svg#info"></use></svg></td>
								<td id="select-channel-warning-text"></td>
							</tr>
						</table>
						<div class="arrow"><img src="./image/ic_arrow_down.png"></div>
					</div>
				</div>

				<div class="select-wrapper">
					<div class="select-list"></div>
					<div class="select-loader"><img src="./image/ajax-loader.gif"></div>
					<div class="select-arrow" style="position:absolute;top:50%;left:0;margin-left:-24px;">
						<svg><use xlink:href="image/ics.svg#arrow_forward"></use></svg>
					</div>
				</div>
			</div>
		</div>

		<!--<div style="clear:both;"></div>-->

		<div id="chart-container">
			<div class="title" style="position:relative;"><?=$lang['REALTIME_ENERGY']['REALTIME_POWER_DATA'];?>
				<div class="sub-title"></div>
			</div>

			<div id="analysis-wrapper">
				<div id="item-container">
					<div class="item" type="V"><?=$lang['REALTIME_ENERGY']['V'];?></div>
					<div class="item" type="I"><?=$lang['REALTIME_ENERGY']['I'];?></div>
					<div class="item" type="KW"><?=$lang['REALTIME_ENERGY']['KW'];?></div>
					<div class="item" type="KVAR"><?=$lang['REALTIME_ENERGY']['KVAR'];?></div>
					<div class="item" type="KVA"><?=$lang['REALTIME_ENERGY']['KVA'];?></div>
					<div class="item" type="PF"><?=$lang['REALTIME_ENERGY']['PF'];?></div>
					<div class="item" type="KWH">kWh</div>
					<div class="item" type="KVARH">kvarh</div>
					<div class="item" type="KVAH">kVAh</div>
					<div id="item-hr"></div>
					<div style="clear:both;"></div>
				</div>
				
				<div id="analysis-container">
					<div id="chart"></div>

					<div id="thumbnail-chart-container">
						<div id="thumbnail-chart"></div>
						<div id="thumbnail-selector">
							<div class="thumbnail-selector-out left">
								<div class="thumbnail-selector-handler"><div></div></div>
							</div>

							<div class="thumbnail-selector-out right">
								<div class="thumbnail-selector-handler"><div></div></div>
							</div>
						</div>
					</div>

					<div id="empty" class="chart-empty">
						<div><?=$lang['REALTIME_ENERGY']['NO_DATA'];?></div>
					</div>
					<div id="loader" class="chart-loader">
						<div><img src="image/ajax-loader.gif"></div>
					</div>
				</div>

				<div id="phase-container">
					<div class="phase-wrapper" id="phase-1" style="padding:0 4px 0 0;">
						<div class="phase-container red">
							<div class="phase-title red"><?=$lang['REALTIME_ENERGY']['PHASE_A'];?></div>
							<div class="phase-content-max">
								<div class="phase-content-title"><?=$lang['REALTIME_ENERGY']['MAX'];?></div>
								<div class="phase-content-value"><span>-</span><div class="phase-content-value-unit-container"><div class="phase-content-value-unit"></div></div></div>
								<div class="phase-content-time">-</div>
							</div>
							<div class="phase-content-min">
								<div class="phase-content-title"><?=$lang['REALTIME_ENERGY']['MIN'];?></div>
								<div class="phase-content-value"><span>-</span><div class="phase-content-value-unit-container"><div class="phase-content-value-unit"></div></div></div>
								<div class="phase-content-time">-</div>
							</div>
							<div style="clear:both;"></div>
						</div>
					</div>

					<div class="phase-wrapper" id="phase-2" style="padding:0 2px 0 2px;">
						<div class="phase-container green">
							<div class="phase-title green"><?=$lang['REALTIME_ENERGY']['PHASE_B'];?></div>
							<div class="phase-content-max">
								<div class="phase-content-title"><?=$lang['REALTIME_ENERGY']['MAX'];?></div>
								<div class="phase-content-value"><span>-</span><div class="phase-content-value-unit-container"><div class="phase-content-value-unit"></div></div></div>
								<div class="phase-content-time">-</div>
							</div>
							<div class="phase-content-min">
								<div class="phase-content-title"><?=$lang['REALTIME_ENERGY']['MIN'];?></div>
								<div class="phase-content-value"><span>-</span><div class="phase-content-value-unit-container"><div class="phase-content-value-unit"></div></div></div>
								<div class="phase-content-time">-</div>
							</div>
							<div style="clear:both;"></div>
						</div>
					</div>

					<div class="phase-wrapper" id="phase-3" style="padding:0 0 0 4px;">
						<div class="phase-container blue">
							<div class="phase-title blue"><?=$lang['REALTIME_ENERGY']['PHASE_C'];?></div>
							<div class="phase-content-max">
								<div class="phase-content-title"><?=$lang['REALTIME_ENERGY']['MAX'];?></div>
								<div class="phase-content-value"><span>-</span><div class="phase-content-value-unit-container"><div class="phase-content-value-unit"></div></div></div>
								<div class="phase-content-time">-</div>
							</div>
							<div class="phase-content-min">
								<div class="phase-content-title"><?=$lang['REALTIME_ENERGY']['MIN'];?></div>
								<div class="phase-content-value"><span>-</span><div class="phase-content-value-unit-container"><div class="phase-content-value-unit"></div></div></div>
								<div class="phase-content-time">-</div>
							</div>
							<div style="clear:both;"></div>
						</div>
					</div>

					<div class="phase-wrapper" id="phase-4" style="padding:0 4px 0 0;">
						<div class="phase-container purple">
							<div class="phase-title purple"><?=$lang['REALTIME_ENERGY']['TOTAL'];?></div>
							<div class="phase-content-max">
								<div class="phase-content-title"><?=$lang['REALTIME_ENERGY']['MAX'];?></div>
								<div class="phase-content-value"><span>-</span><div class="phase-content-value-unit-container"><div class="phase-content-value-unit"></div></div></div>
								<div class="phase-content-time">-</div>
							</div>
							<div class="phase-content-min">
								<div class="phase-content-title"><?=$lang['REALTIME_ENERGY']['MIN'];?></div>
								<div class="phase-content-value"><span>-</span><div class="phase-content-value-unit-container"><div class="phase-content-value-unit"></div></div></div>
								<div class="phase-content-time">-</div>
							</div>
							<div style="clear:both;"></div>
						</div>
					</div>

					<div style="clear:both;"></div>
				</div>
			</div>
		</div>
	</div>

	<div id="stringLength" style="display:none;"></div>
</div>
<?php
}
?>