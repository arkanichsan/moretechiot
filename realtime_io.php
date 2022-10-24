<?php
function customized_header(){
	global $lang;
?>
<link rel="stylesheet" type="text/css" href="./css/picker.css">
<link rel="stylesheet" type="text/css" href="./css/list.css">
<link rel="stylesheet" type="text/css" href="./css/tip.css">
<link rel="stylesheet" type="text/css" href="./css/thumbnail.css">
<style type="text/css">
#select-container{
	width:100%;
	display:none;
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

#select-cancel-button{
	float:right;
	clear:both;
	margin-top:20px;
}
/******************/
#chart-container{
	width:100%;
	top:0;
	left:0;
	display:none;
	/*opacity:0;*/
}

.time-button-wrapper{
	/*float:right;*/
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
	margin-top:10px;
	width:100%;
	padding:20px;
	background-color:#FFF;
	box-sizing: border-box;
	border: 1px solid #ccc;
}

#analysis-container{
	clear:both;
	width:100%;
	position:relative;
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
	left:0;
	cursor:move;
}

.list-table-row .list-table-cell:last-child{
	text-align: right;
}

#list .list-table-cell svg{
	fill:#707070;
}
#list .list-table-cell.hover svg{
	fill:#2a2a2a;
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
var yAxisLabelMaxLengthWidth = 0, yAxisLabelMaxLengthString;
var colorArray = [];
var maxTimestamp = (new Date()).getTime();
var ajax = null;

function loadDeviceList(){
	$("#select-device div.select-list").empty();
	$("#select-module, #select-channel").hide();

	return $.ajax({
		url: "realtime_io_ajax.php?act=get_device_list",
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
							"disabled": $device.attr("online") == "0" ? true : null
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

			var $groups = $(data).find("list > group");
			if($groups.length > 0){
				$("#select-device div.select-list").append(
					$("<div></div>").attr("class", "select-title").text("<?=$lang['HISTORY_IO']['GROUP'];?>")
				);

				for(var i = 0; i < $groups.length; i++){
					var $group = $($groups[i]);

					$("#select-device div.select-list").append(
						$("<div></div>").attr({
							"class": "select-option",
							"group_info_uid": $group.attr("uid")
						}).text($group.attr("name")).bind("click", function(){
							$("#select-device div.select-loader").show();
							$("#select-device div.select-list").css("opacity", 0.3);

							$.ajax({
								url: "realtime_io_ajax.php?act=get_group_data",
								type: "POST",
								data: "group_info_uid=" + $(this).attr("group_info_uid"),
								dataType: "xml",
								success: function(data, textStatus, jqXHR){
									hideChannelSelector().always(function(){
										var $channels = $(data).find("list > channel");
										if($channels.length > 0){
											for(var i = 0; i < $channels.length; i++){
												var $channel = $($channels[i]);

												// Title
												var title = $channel.attr("device_modelname") + ($channel.attr("device_nickname") != "" ? "(" + $channel.attr("device_nickname") + ")" : "") + " / ";

												if($channel.attr("module_uid") != "ir"){
													if(!$channel.attr("module_modelname")){
														title += $channel.attr("module_nickname") + " / ";
													}
													else{
														title += $channel.attr("module_modelname") + ($channel.attr("module_nickname") != "" ? "(" + $channel.attr("module_nickname") + ")" : "") + " / ";
													}
												}
												else{
													title += "<?=$lang['HISTORY_IO']['INTERNAL_REGISTER'];?>" + " / ";
												}

												var channel = $channel.attr("channel_name");
												var splitArray = channel.match(/(\D+)(\d+)/);
												title += {
													"DI": "DI%channel%",
													"DIC": "<?=$lang['REALTIME_IO']['DI_COUNTER_WITH_NO'];?>",
													"DO": "DO%channel%",
													"DOC": "<?=$lang['REALTIME_IO']['DO_COUNTER_WITH_NO'];?>",
													"AI": "AI%channel%",
													"AO": "AO%channel%",
													"CI": "Discrete Input %channel%",
													"CO": "Coil Output %channel%",
													"RI": "Input Register %channel%",
													"RO": "Holding Register %channel%",
													"IR": "<?=$lang['REALTIME_IO']['INTERNAL_REGISTER_WITH_NO'];?>"
												}[splitArray[1]].replace("%channel%", splitArray[2]);

												title += $channel.attr("channel_nickname") ? "(" + $channel.attr("channel_nickname") + ")": "";

												var $row = createRow($channel.attr("account_uid"), $channel.attr("device_uid"), $channel.attr("module_uid"), $channel.attr("channel_name"), title, $channel.attr("channel_unit"));
												$row.appendTo("#list");
											}

											$("#loader").show();

											updateChart();

											var $plot = $("#thumbnail-chart").data("plot");
											var plotData = $plot.getData()[0];
											var selectorWidth = plotData.xaxis.p2c(maxTimestamp);

											$("#thumbnail-selector").attr({
												min: plotData.xaxis.c2p($("#thumbnail-selector div.thumbnail-selector-out.left").width()),
												max: plotData.xaxis.c2p(selectorWidth - $("#thumbnail-selector div.thumbnail-selector-out.right").width())
											});

											updateChartRange("chart");
										}
										else{
											updateChart();
											updateChartRange();
										}
									});
								},
								error: function(jqXHR, textStatus, errorThrown){
									if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

									alert(jqXHR.responseText);
								},
								complete: function(){
									$("#select-device div.select-loader").hide();
									$("#select-device div.select-list").css("opacity", 1);
								}
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
		url: "realtime_io_ajax.php?act=get_module_list",
		type: "POST",
		data: "account_uid=" + accountUID + "&device_uid=" + deviceUID,
		dataType: "xml",
		success: function(data, textStatus, jqXHR){
			$("#select-module div.select-list").empty();

			var $modules = $(data).find("list > module");
			if($modules.length > 0){
				var interfaces = {
					"onboard": [],
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
							"nickname": $module.attr("nickname")
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
							"nickname": $module.attr("nickname")
						};
					}
					else if($module.attr("interface") == "XV" || $module.attr("interface") == "XW" || $module.attr("interface") == "XU"){
						if(typeof(interfaces.onboard[0]) == "undefined"){
							interfaces.onboard[0] = {
								name: $module.attr("interface") == "XU" ? "<?=$lang['REALTIME_IO']['BUILD_IN'];?>" : $module.attr("interface") + "-Board",
								modules: []
							}
						}

						interfaces.onboard[0].modules[parseInt($module.attr("number"), 10)] = {
							"uid": $module.attr("uid"),
							"modelName": $module.attr("model_name"),
							"nickname": $module.attr("nickname")
						};
					}
				}

				for(var sourceTypeIndex = 0, sourceTypeArray = ["onboard", "comport", "network"]; sourceTypeIndex < sourceTypeArray.length; sourceTypeIndex++){
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
									"module_uid": module.uid
								}).text((function(){
									if(!module.modelName){
										return module.nickname;
									}
									else{
										return module.modelName + (module.nickname != "" ? "(" + module.nickname + ")" : "");
									}
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

			$("#select-module div.select-list").append(
				$("<div></div>").attr("class", "select-title").text("<?=$lang['REALTIME_IO']['OTHER'];?>")
			);

			$("#select-module div.select-list").append(
				$("<div></div>").attr({
					"class": "select-option",
					"module_uid": "ir"
				}).text("<?=$lang['REALTIME_IO']['INTERNAL_REGISTER'];?>").bind("click", function(){
					$(this).addClass("active").siblings().removeClass("active");

					$("#select-module div.select-loader").show();
					$("#select-module div.select-list").css("opacity", 0.3);

					loadChannelList($("#select-device div.select-list div.select-option.active").attr("account_uid"), $("#select-device div.select-list div.select-option.active").attr("device_uid"), $(this).attr("module_uid")).always(function(){
						$("#select-module div.select-loader").hide();
						$("#select-module div.select-list").css("opacity", 1);
					});
				})
			);
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
		url: "realtime_io_ajax.php?act=get_channel_list",
		type: "POST",
		data: "account_uid=" + accountUID + "&device_uid=" + deviceUID + "&module_uid=" + moduleUID,
		dataType: "xml",
		success: function(data, textStatus, jqXHR){
			// Create option(All channel)
			$("#select-channel div.select-list").append(
				$("<div></div>").attr("class", "select-title").text("<?=$lang['REALTIME_IO']['CHANNEL'];?>")
			);

			var $channels = $(data).find("list > channel");
			if($channels.length > 0){
				for(var i = 0; i < $channels.length; i++){
					var $channel = $($channels[i]);

					var splitArray = $channel.attr("name").match(/(\D+)(\d+)/);
					var channelName = {
						"DI": "DI%channel%",
						"DIC": "<?=$lang['REALTIME_IO']['DI_COUNTER_WITH_NO'];?>",
						"DO": "DO%channel%",
						"DOC": "<?=$lang['REALTIME_IO']['DO_COUNTER_WITH_NO'];?>",
						"AI": "AI%channel%",
						"AO": "AO%channel%",
						"CI": "Discrete Input %channel%",
						"CO": "Coil Output %channel%",
						"RI": "Input Register %channel%",
						"RO": "Holding Register %channel%",
						"IR": "<?=$lang['REALTIME_IO']['INTERNAL_REGISTER_WITH_NO'];?>"
					}[splitArray[1]].replace("%channel%", splitArray[2]);

					$("#select-channel div.select-list").append(
						$("<div></div>").attr({
							"class": "select-option",
							"channel": $channel.attr("name"),
							"unit": $channel.attr("unit"),
							"disabled": $channel.attr("available") != "1" ? true : null
						}).text(channelName + ($channel.attr("nickname") ? "(" + $channel.attr("nickname") + ")" : "")).bind("click", function(){
							if($(this).attr("disabled")){
								return;
							}

							// Check the container is running animate
							if($("#select-container").is(":animated")){
								return;
							}

							$(this).addClass("active").siblings().removeClass("active");

							hideChannelSelector().always(function(){
								var $row = createRow($("#select-device div.select-list div.select-option.active").attr("account_uid"), $("#select-device div.select-list div.select-option.active").attr("device_uid"), $("#select-module div.select-list div.select-option.active").attr("module_uid"), $("#select-channel div.select-list div.select-option.active").attr("channel"), $("#select-device div.select-list div.select-option.active").text() + " / " + $("#select-module div.select-list div.select-option.active").text() + " / " + $("#select-channel div.select-list div.select-option.active").text(), $("#select-channel div.select-list div.select-option.active").attr("unit"));
								$row.appendTo("#list");

								$("#loader").show();

								updateChart();

								var $plot = $("#thumbnail-chart").data("plot");
								var plotData = $plot.getData()[0];
								var selectorWidth = plotData.xaxis.p2c(maxTimestamp);

								$("#thumbnail-selector").attr({
									min: plotData.xaxis.c2p($("#thumbnail-selector div.thumbnail-selector-out.left").width()),
									max: plotData.xaxis.c2p(selectorWidth - $("#thumbnail-selector div.thumbnail-selector-out.right").width())
								});

								updateChartRange("chart");
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
				var version;

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
							"<?=$lang['REALTIME_IO']['TIP']['SETUP_SENDING_SETTING_FIRST'];?>"
								.replace("%version%", version)
								.replace("%sending_setting%", loginPassword ? "<a href=\"javascript:void(0);\" onClick=\"onClickGoToSettingPage('" + serialNumber + "', '" + loginPassword + "');\"><?=$lang['REALTIME_IO']['TIP']['REALTIME_DATA_SENDING_SETTING'];?></a>" : "<?=$lang['REALTIME_IO']['TIP']['REALTIME_DATA_SENDING_SETTING'];?>")
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

function createRow(accountUID, deviceUID, moduleUID, channel, title, unit){
	return $("<div></div>").attr({
		"class": "list-table-row",
		"account_uid": accountUID,
		"device_uid": deviceUID,
		"module_uid": moduleUID,
		"channel": channel,
		"unit": unit
	}).append(
		$("<div></div>").attr("class", "list-table-cell module-name").text(title)
	).append(
		$("<div></div>").attr("class", "list-table-cell").append(
			$("<span></span>").attr("class", "module-value max").text("-")
		).append("&nbsp;").append(
			$("<span></span>").attr("class", "module-value unit").text(unit)
		).append("&nbsp;").append(
			$("<span></span>").attr("class", "module-time max").text("(-)")
		)
	).append(
		$("<div></div>").attr("class", "list-table-cell").append(
			$("<span></span>").attr("class", "module-value min").text("-")
		).append("&nbsp;").append(
			$("<span></span>").attr("class", "module-value unit").text(unit)
		).append("&nbsp;").append(
			$("<span></span>").attr("class", "module-time min").text("(-)")
		)
	).append(
		$("<div></div>").attr("class", "list-table-cell").css("position", "relative").append((function(){
			var channelType = channel.match(/(\D+)(\d+)/)[1];

			if(accountUID == <?=$_SESSION["account_uid"]?> && (channelType == "DO" || channelType == "CO" || channelType == "AO" || channelType == "RO" || channelType == "IR")){
				return $("<div></div>").css({
					"position": "absolute",
					"left": "-18px",
					"cursor": "pointer"
				}).append(
					$(createSVGIcon("image/ics.svg", "edit"))
				).hover(function(){
					$(this).closest("div.list-table-cell").addClass("hover");
				}, function(){
					$(this).closest("div.list-table-cell").removeClass("hover");
				}).bind("click", function(){
					var $row = $(this).closest("div.list-table-row");

					showValueWindow($row.attr("account_uid"), $row.attr("device_uid"), $row.attr("module_uid"), $row.attr("channel"), $row);
				});
			}
		})()).append(
			$("<span></span>").attr("class", "module-value current").css("verticalAlign", "middle").text("-")
		).append("&nbsp;").append(
			$("<span></span>").attr("class", "module-value unit").css("verticalAlign", "middle").text(unit)
		)
	).append(
		$("<div></div>").attr("class", "list-table-cell").append(
			$("<input type='button'/>").attr("class", "red").css("width", "100%").val("<?=$lang['REALTIME_IO']['REMOVE'];?>").bind("click", function(){
				var $row = $(this).closest("div.list-table-row");

				var series = $row.data("series");
				if(typeof(series) != "undefined"){
					delete colorArray[series.color];
				}

				$row.remove();

				updateChart();
				updateChartRange();
			})
		)
	);
}

function updateAllChannel(){
	var $rows = $("#list div.list-table-row");

	var date = new Date();
	date.setMilliseconds(0);
	maxTimestamp = date.getTime();

	var accountPool = {};// accountUID / deviceUID / row

	$rows.each(function(){
		var accountUID = $(this).attr("account_uid");
		var deviceUID = $(this).attr("device_uid");
		var moduleUID = $(this).attr("module_uid");
		var channel = $(this).attr("channel");

		if(typeof(accountPool[accountUID]) == "undefined"){
			accountPool[accountUID] = {};
		}

		if(typeof(accountPool[accountUID][deviceUID]) == "undefined"){
			accountPool[accountUID][deviceUID] = {};
		}

		if(typeof(accountPool[accountUID][deviceUID][moduleUID]) == "undefined"){
			accountPool[accountUID][deviceUID][moduleUID] = [];
		}

		accountPool[accountUID][deviceUID][moduleUID].push(channel);
	});

	if($rows.length > 0){
		loadChannels(maxTimestamp, accountPool);
	}
}

function setChannelData(accountUID, deviceUID, moduleUID, channel, value){// sourceType, sourceIndex and moduleIndex no use in local IR
	return $.ajax({
		url: "realtime_io_ajax.php?act=set_channel_data",
		type: "POST",
		data: "account_uid=" + accountUID + "&device_uid=" + deviceUID + "&module_uid=" + moduleUID + "&channel=" + channel + "&value=" + value,
		cache: false,
		dataType: "xml",
		timeout: 7000
	});
}

function getChannelsData(accountPool){
	return $.ajax({
		url: "realtime_io_ajax.php?act=get_channel_data",
		type: "POST",
		data: "data=" + JSON.stringify(accountPool),
		cache: false,
		dataType: "xml",
		timeout: 15000
	});
}

function loadChannels(timestamp, accountPool){//[[sourceType, sourceIndex, moduleIndex, channel, $row], ...]
	if(ajax != null){
		return;
	}

	ajax = getChannelsData(accountPool).done(function(data){
		var $channels = $(data).find("list > channel");

		for(var i = 0; i < $channels.length; i++){
			var $channel = $($channels[i]);

			processData(timestamp, $channel.attr("account_uid"), $channel.attr("device_uid"), $channel.attr("module_uid"), $channel.attr("channel"), parseFloat($channel.attr("value")));
		}

		finishData();
	}).fail(function(){
		finishData();
	});
}

function processData(timestamp, accountUID, deviceUID, moduleUID, channel, value){
	var getDate = function(timestamp){
		var date = new Date(timestamp);

		return padding(date.getHours(), 2) + ":" + padding(date.getMinutes(), 2) + ":" + padding(date.getSeconds(), 2);
	};

	var $row = $("#list div.list-table-row[account_uid='" + accountUID + "'][device_uid='" + deviceUID + "'][module_uid='" + moduleUID + "'][channel='" + channel + "']");

	if($row.length <= 0){
		return;
	}

	if(isNaN(value)){
		$row.find("span.module-value.current").text("-");
		return;
	}

	var series = $row.data("series");
	if(typeof(series) == "undefined"){
		var colorIndex = -1;
		for(var i = 0; i < colorArray.length; i++){
			if(typeof(colorArray[i]) == "undefined"){
				colorArray[i] = true;
				colorIndex = i;
				break;
			}
		}

		if(colorIndex == -1){
			colorIndex = colorArray.push(true) - 1;
		}

		series = {
			data: [],
			lines: {
				steps: channel.match(/^(DI|DO|CI|CO)+\d/) ? true : false
			},
			deviceUID: deviceUID,
			moduleUID: moduleUID,
			channel: channel,
			color: colorIndex
		};

		$row.data("series", series);
	}

	series.data.push([timestamp, value]);

	series.data = processChartData(series.data);

	if(series.data.length > 0){
		$row.find("span.module-value.max").text(parseFloat((series.data[series.data.maxIndex][1]).toPrecision(7)));
		$row.find("span.module-time.max").text("(" + getDate(series.data[series.data.maxIndex][0]) + ")");

		$row.find("span.module-value.min").text(parseFloat((series.data[series.data.minIndex][1]).toPrecision(7)));
		$row.find("span.module-time.min").text("(" + getDate(series.data[series.data.minIndex][0]) + ")");

		$row.find("span.module-value.current").text(parseFloat((series.data[series.data.length - 1][1]).toPrecision(7)));
	}
	else{
		$row.find("span.module-value.max").text("-");
		$row.find("span.module-time.max").text("(-)");

		$row.find("span.module-value.min").text("-");
		$row.find("span.module-time.min").text("(-)");

		$row.find("span.module-value.current").text("-");
	}

//	data[0].color = "#953734";
//	data[1].color = "#76923c";
//	data[2].color = "#366092";
//	data[3].color = "#5f497a";
}

function finishData(){
	$("#loader").hide();

	if($("#chart-container").is(":visible")){
		var isDrag = false;
		var mousemoveEvents = $._data(window, "events").mousemove || [];
		for(var i = 0; i < mousemoveEvents.length; i++){
			if(mousemoveEvents[i].namespace == "thumbnailChart"){
				isDrag = true;
				break;
			}
		}

		if(isDrag == false){
			updateChart();

			var $plot = $("#thumbnail-chart").data("plot");
			var data = $plot.getData()[0];
			var selectorWidth = data.xaxis.p2c(maxTimestamp);

			$("#thumbnail-selector").attr({
				min: data.xaxis.c2p($("#thumbnail-selector div.thumbnail-selector-out.left").width()),
				max: data.xaxis.c2p(selectorWidth - $("#thumbnail-selector div.thumbnail-selector-out.right").width())
			});

			updateChartRange("chart");
		}
	}

	ajax = null;
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

	$("#list div.list-table-row").each(function(index){
		var series = $(this).data("series");
		if(typeof(series) == "undefined"){return true;}

		dataSet.push(series);
	});

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
		"left": data.yaxis.box.width + "px"
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
				top: 0, right: 0, bottom: 1, left: 0
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

				var $row = $("#list div.list-table-row[device_uid='" + flotItem.series.deviceUID + "']" + sourceType + sourceIndex + moduleIndex + "[channel='" + flotItem.series.channel + "']:first");

				return tipGenerator(yval, date.getFullYear() + "/" + padding(date.getMonth() + 1, 2) + "/" + padding(date.getDate(), 2) + " " + padding(date.getHours(), 2) + ":" + padding(date.getMinutes(), 2) + ":" + padding(date.getSeconds(), 2), $row.find("div.list-table-cell.module-name").text(), $row.attr("unit"));
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

function tipGenerator(value, time, channel, unit){
	var table = "<table>";

	table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['REALTIME_IO']['CHANNEL_WITH_COLON'];?></td><td>" + channel + "</td></tr>";
	table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['REALTIME_IO']['TIME_WITH_COLON'];?></td><td>" + time + "</td></tr>";
	table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['REALTIME_IO']['VALUE_WITH_COLON'];?></td><td>" + numberWithCommas(value) + "&nbsp;" + unit + "</td></tr></table>";

	return table;
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

function showValueWindow(accountUID, deviceUID, moduleUID, channel, $row){
	popupWindow($("#window-value"), function(result){
		if(result == "ok"){
			if($("#window-value-input").val() == ""){
				popupErrorWindow("<?=$lang['REALTIME_IO']['POPUP']['VALUE_IS_EMPTY'];?>");
				return;
			}

			if(isNaN($("#window-value-input").val())){
				popupErrorWindow("<?=$lang['REALTIME_IO']['POPUP']['VALUE_MUST_BE_NUMBER'];?>");
				return;
			}

			$("#window-value").hide();
			$("#wait-loader").show();

			setChannelData(accountUID, deviceUID, moduleUID, channel, $("#window-value-input").val()).done(function(){
				popupSuccessWindow("<?=$lang['REALTIME_IO']['POPUP']['MODIFIED_SUCCESSFULLY'];?>");
			}).fail(function(jqXHR, textStatus, errorThrown){
				if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

				alert(jqXHR.responseText);
			}).always(function(){
				$("#wait-loader").hide();
			});
		}
		else if(result == "cancel"){
			$("#window-value").hide();
		}
	});

	$("#window-value-input").val($row.find("span.module-value.current").text()).focus().select();
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

function showChannelSelector(isFrontPage){
	$("html, body").scrollTop(0);
	$("#select-device").show();
	$("#select-device div.select-loader").show();

	loadDeviceList().always(function(){
		$("#select-device div.select-loader").hide();
	});

	// hide chart
	$("#chart-container").hide();

	// animate selector
	$("#select-container").parent().css("overflow", "hidden");
	$("#select-container").show().css({
		"opacity": 0,
		"marginLeft": "200px"
	}).animate({
		"opacity": 1,
		"marginLeft": 0
	}, isFrontPage == true ? 0 : "fast", function(){
		$("#select-container").parent().css("overflow", "");
	});

	if(isFrontPage == true){
		$("#select-cancel-button").hide();
	}
	else{
		$("#select-cancel-button").show();
	}
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
	$("#select-cancel-button input").bind("click", function(){
		hideChannelSelector('right').always(function(){
			updateChart();

			var $plot = $("#thumbnail-chart").data("plot");
			var data = $plot.getData()[0];
			var selectorWidth = data.xaxis.p2c(maxTimestamp);

			$("#thumbnail-selector").attr({
				min: data.xaxis.c2p($("#thumbnail-selector div.thumbnail-selector-out.left").width()),
				max: data.xaxis.c2p(selectorWidth - $("#thumbnail-selector div.thumbnail-selector-out.right").width())
			});

			updateChartRange("chart");
		});
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
			updateChart();

			var $plot = $("#thumbnail-chart").data("plot");
			var data = $plot.getData()[0];
			var selectorWidth = data.xaxis.p2c(maxTimestamp);

			$("#thumbnail-selector").attr({
				min: data.xaxis.c2p($("#thumbnail-selector div.thumbnail-selector-out.left").width()),
				max: data.xaxis.c2p(selectorWidth - $("#thumbnail-selector div.thumbnail-selector-out.right").width())
			});

			updateChartRange("chart");

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
			updateChart();

			var $plot = $("#thumbnail-chart").data("plot");
			var data = $plot.getData()[0];
			var selectorWidth = data.xaxis.p2c(maxTimestamp);

			$("#thumbnail-selector").attr({
				min: data.xaxis.c2p($("#thumbnail-selector div.thumbnail-selector-out.left").width()),
				max: data.xaxis.c2p(selectorWidth - $("#thumbnail-selector div.thumbnail-selector-out.right").width())
			});

			updateChartRange("chart");

			$(window).unbind(".thumbnailChart");
		});
	}).closest("div.thumbnail-selector-out").bind("mousedown", function(event){
		event.stopPropagation();
	});

	showChannelSelector(true);

	var date = new Date();
	var second = date.getSeconds();
	var ms = second * 1000 + date.getMilliseconds()
	var startUpdateAfter = ((Math.ceil(second / 5) * 5000) - ms);
	setTimeout(function(){
		updateAllChannel();
		setInterval(updateAllChannel, 5000);
	}, startUpdateAfter);
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
			<div class="title" style="border-bottom-width:0px;padding-bottom:0px;"><?=$lang['REALTIME_IO']['SELECT_CHANNEL'];?></div>

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

			<div id="select-cancel-button">
				<input type="button" value="<?=$lang['REALTIME_IO']['CANCEL'];?>" class="gray" style="width:100px;">
			</div>
		</div>

		<div id="chart-container">
			<div class="title"><?=$lang['REALTIME_IO']['REALTIME_IO_DATA'];?></div>

			<div id="analysis-wrapper">
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
						<div><?=$lang['REALTIME_IO']['NO_DATA'];?></div>
					</div>
					<div id="loader" class="chart-loader" style="display:none;">
						<div><img src="image/ajax-loader.gif"></div>
					</div>
				</div>
			</div>

			<div class="list-table">
				<div class="list-table-header-group">
					<div class="list-table-row">
						<div class="list-table-cell"><?=$lang['REALTIME_IO']['MODULE'];?></div>
						<div class="list-table-cell" style="width:200px;"><?=$lang['REALTIME_IO']['MAX'];?></div>
						<div class="list-table-cell" style="width:200px;"><?=$lang['REALTIME_IO']['MIN'];?></div>
						<div class="list-table-cell" style="width:200px;"><?=$lang['REALTIME_IO']['VALUE'];?></div>
						<div class="list-table-cell" style="width:1%;text-align:center;"><?=$lang['REALTIME_IO']['ACTION'];?></div>
					</div>
				</div>

				<div class="list-table-row-group" id="list"></div>

				<div class="list-table-footer-group">
					<div class="list-table-row">
						<div class="list-table-cell"></div>
						<div class="list-table-cell"></div>
						<div class="list-table-cell"></div>
						<div class="list-table-cell"></div>
						<div class="list-table-cell">
							<input type="button" value="<?=$lang['REALTIME_IO']['ADD'];?>" style="width:100px;" onClick="showChannelSelector();">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="stringLength" style="display:none;"></div>
</div>

<div class="popup-background" id="window-value" style="text-align: center;">
	<div class="popup-wrapper" style="width:auto;min-width:300px;display:inline-block;">
		<div class="popup-container">
			<div class="popup-title"><?=$lang['REALTIME_IO']['POPUP']['VALUE_SETTING'];?></div>
			<div class="popup-content" style="text-align:center;padding:10px;box-sizing: border-box;">
				<div style="margin-bottom:10px;text-align:left;"><?=$lang['REALTIME_IO']['POPUP']['PLEASE_ENTER_THE_VALUE'];?></div>
				<input type="text" id="window-value-input" style="width:100%;box-sizing: border-box;">
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