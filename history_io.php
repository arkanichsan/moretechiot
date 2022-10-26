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
	padding: 5px 0;
	border-width:1px 0;
	border-style:solid;
	border-color:#ccc;
	background: linear-gradient(#035002 0px, #fff 600%);
	font-size:11px;
	font-weight:bold;
	color: #eeeeee;
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

.module-time{
	font-size:13px;
	color:#888;
}

#sync-date{
	vertical-align: middle;
	/*margin-left:0;*/
}

.sync-date-label{
	vertical-align: middle;
	font-size: 13px;
	color: #888;
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
var loaderCounter = 0;
var yAxisLabelMaxLengthWidth = 0, yAxisLabelMaxLengthString;
var colorArray = [];

function loadDeviceList(){
	$("#select-device div.select-list").empty();
	$("#select-module, #select-channel").hide();

	return $.ajax({
		url: "history_io_ajax.php?act=get_device_list",
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
							"device_uid": $device.attr("uid")
						}).text($device.attr("model_name") + ($device.attr("nickname") != "" ? "(" + $device.attr("nickname") + ")" : "")).bind("click", function(){
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
									"tip": "<?=$lang['HISTORY_IO']['TIP']['SHARE_BY_USER'];?>".replace("%username%", $device.attr("account_nickname") + "(" + $device.attr("account_username") + ")")
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
								url: "history_io_ajax.php?act=get_group_data",
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
													"DIC": "<?=$lang['HISTORY_IO']['DI_COUNTER_WITH_NO'];?>",
													"DO": "DO%channel%",
													"DOC": "<?=$lang['HISTORY_IO']['DO_COUNTER_WITH_NO'];?>",
													"AI": "AI%channel%",
													"AO": "AO%channel%",
													"CI": "Discrete Input %channel%",
													"CO": "Coil Output %channel%",
													"RI": "Input Register %channel%",
													"RO": "Holding Register %channel%",
													"IR": "<?=$lang['HISTORY_IO']['INTERNAL_REGISTER_WITH_NO'];?>"
												}[splitArray[1]].replace("%channel%", splitArray[2]);

												title += $channel.attr("channel_nickname") ? "(" + $channel.attr("channel_nickname") + ")": "";

												var $row = createRow($channel.attr("account_uid"), $channel.attr("device_uid"), $channel.attr("module_uid"), $channel.attr("channel_name"), title, $channel.attr("channel_unit"));
												$row.appendTo("#list");
												processRow($row);
											}
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
		url: "history_io_ajax.php?act=get_module_list",
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
								name: $module.attr("interface") == "XU" ? "<?=$lang['HISTORY_IO']['BUILD_IN'];?>" : $module.attr("interface") + "-Board",
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

			// Internal Register
			var $ir = $(data).find("list > module[interface='IR']");
			if($ir.length > 0){
				$("#select-module div.select-list").append(
					$("<div></div>").attr("class", "select-title").text("<?=$lang['HISTORY_IO']['OTHER'];?>")
				);

				$("#select-module div.select-list").append(
					$("<div></div>").attr({
						"class": "select-option",
						"module_uid": "ir"
					}).text("<?=$lang['HISTORY_IO']['INTERNAL_REGISTER'];?>").bind("click", function(){
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
		url: "history_io_ajax.php?act=get_channel_list",
		type: "POST",
		data: "account_uid=" + accountUID + "&device_uid=" + deviceUID + "&module_uid=" + moduleUID,
		dataType: "xml",
		success: function(data, textStatus, jqXHR){
			// Create option(All channel)
			$("#select-channel div.select-list").append(
				$("<div></div>").attr("class", "select-title").text("<?=$lang['HISTORY_IO']['CHANNEL'];?>")
			);

			var $channels = $(data).find("list > channel");
			if($channels.length > 0){
				for(var i = 0; i < $channels.length; i++){
					var $channel = $($channels[i]);

					var splitArray = $channel.attr("name").match(/(\D+)(\d+)/);
					var channelName = {
						"DI": "DI%channel%",
						"DIC": "<?=$lang['HISTORY_IO']['DI_COUNTER_WITH_NO'];?>",
						"DO": "DO%channel%",
						"DOC": "<?=$lang['HISTORY_IO']['DO_COUNTER_WITH_NO'];?>",
						"AI": "AI%channel%",
						"AO": "AO%channel%",
						"CI": "Discrete Input %channel%",
						"CO": "Coil Output %channel%",
						"RI": "Input Register %channel%",
						"RO": "Holding Register %channel%",
						"IR": "<?=$lang['HISTORY_IO']['INTERNAL_REGISTER_WITH_NO'];?>"
					}[splitArray[1]].replace("%channel%", splitArray[2]);

					$("#select-channel div.select-list").append(
						$("<div></div>").attr({
							"class": "select-option",
							"channel": $channel.attr("name"),
							"unit": $channel.attr("unit")
						}).text(channelName + ($channel.attr("nickname") ? "(" + $channel.attr("nickname") + ")" : "")).bind("click", function(){
							// Check the container is running animate
							if($("#select-container").is(":animated")){
								return;
							}

							$(this).addClass("active").siblings().removeClass("active");

							hideChannelSelector().always(function(){
								var $row = createRow($("#select-device div.select-list div.select-option.active").attr("account_uid"), $("#select-device div.select-list div.select-option.active").attr("device_uid"), $("#select-module div.select-list div.select-option.active").attr("module_uid"), $("#select-channel div.select-list div.select-option.active").attr("channel"), $("#select-device div.select-list div.select-option.active").text() + " / " + $("#select-module div.select-list div.select-option.active").text() + " / " + $("#select-channel div.select-list div.select-option.active").text(), $("#select-channel div.select-list div.select-option.active").attr("unit"));
								$row.appendTo("#list");
								processRow($row);
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
			$("<div></div>").attr("class", "time-button-wrapper").append(
				$("<div></div>").attr("class", "time-button-container").append(
					$("<div></div>").attr("class", "time-button").append(
						$("<div></div>").css("display", "table-row").append(
							$("<div></div>").attr("class", "time-button-option-name").css("width", "100%")
						).append(
							$("<div></div>").attr("class", "time-button-option-desc")
						)
					).append(
						$("<div></div>").css({
							"position": "absolute",
							"right": "4px",
							"top": "50%",
							"marginTop": "-9px"
						}).append(
							createSVGIcon("image/ics.svg", "arrow_drop_down")
						)
					).bind("click.time-button", function(){
						if(!$(this).hasClass("active")){
							//close everyone
							$(document).triggerHandler("click.time-button");

							var $select = $(this).addClass("active").siblings("div.time-button-select").show();

							// adjust position
							$select.css("bottom", "auto");

							if($select.offset().top + $select.outerHeight() > $(window).scrollTop() + $(window).height() - 20){
								$select.css("bottom", ($(this).outerHeight() - 1) + "px");
							}

							event.stopPropagation();
						}
					})
				).append(
					$("<div></div>").attr("class", "time-button-select")
				)
			)
		)
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
		$("<div></div>").attr("class", "list-table-cell").append(
			$("<input type='button'/>").attr("class", "red").css("width", "100%").val("<?=$lang['HISTORY_IO']['REMOVE'];?>").bind("click", function(){
				var $row = $(this).closest("div.list-table-row");

				var ajax = $row.data("ajax");
				if(typeof(ajax) != "undefined"){
					ajax.abort();
				}

				$row.remove();

				updateChart();
				updateChartRange();
			})
		)
	);
}

function processRow($row){
	var $container = $row.find("div.time-button-container");
	var $select = $container.find("div.time-button-select");

	$select.bind("showDesc", function(event, $option, begin, end){
		$option.attr({
			"begin": begin.getFullYear() + "-" + padding(begin.getMonth() + 1, 2) + "-" + padding(begin.getDate(), 2) + " 00:00:00",
			"end": end.getFullYear() + "-" + padding(end.getMonth() + 1, 2) + "-" + padding(end.getDate(), 2) + " 23:59:59"
		}).find("div.time-button-option-desc").text(begin.getFullYear() + "/" + padding(begin.getMonth() + 1, 2) + "/" + padding(begin.getDate(), 2))
	});

	//
	var begin = new Date(), end = begin;
	var $option = $("<div></div>").addClass("time-button-option").append(
		$("<div></div>").addClass("time-button-option-name").text("<?=$lang['HISTORY_IO']['TODAY'];?>")
	).append(
		$("<div></div>").addClass("time-button-option-desc")
	).appendTo($select);

	$select.triggerHandler("showDesc", [$option, begin, end]);

	//
	var begin = new Date(), end = begin;
	begin.setDate(begin.getDate() - 1);
	var $option = $("<div></div>").addClass("time-button-option").append(
		$("<div></div>").addClass("time-button-option-name").text("<?=$lang['HISTORY_IO']['YESTERDAY'];?>")
	).append(
		$("<div></div>").addClass("time-button-option-desc")
	).appendTo($select);

	$select.triggerHandler("showDesc", [$option, begin, end]);

	//
	var begin = new Date(), end = begin;
	begin.setDate(begin.getDate() - 7);
	var $option = $("<div></div>").addClass("time-button-option").append(
		$("<div></div>").addClass("time-button-option-name").text("<?=$lang['HISTORY_IO']['TODAY_LAST_WEEK'];?>")
	).append(
		$("<div></div>").addClass("time-button-option-desc")
	).appendTo($select);

	$select.triggerHandler("showDesc", [$option, begin, end]);

	// Customized Date
	var $option = $("<div></div>").addClass("time-button-option customized").append(
		$("<div></div>").addClass("time-button-option-name").text("<?=$lang['HISTORY_IO']['SPECIFY_DATE'];?>")
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

	createDatePicker("date").appendTo($container);

	$container.find("div.time-button").css("width", $select.width() + "px");

	var $options = $select.find("div.time-button-option").bind("click.loadData", function(event){
		var setSelector = function($row, begin, end, optionName, optionDesc){
			var $container = $row.find("div.time-button-container");

			$container.attr({
				"begin": begin,
				"end": end
			});

			$container.find("div.time-button div.time-button-option-name").text(optionName);
			$container.find("div.time-button div.time-button-option-desc").text(optionDesc);
		};

		var begin = $(this).attr("begin");
		var end = $(this).attr("end");
		var optionName = $(this).find("div.time-button-option-name").text();
		var optionDesc = $(this).find("div.time-button-option-desc").text();

		$("#sync-date").attr({
			begin: begin,
			end: end,
			optionName: optionName,
			optionDesc: optionDesc
		});

		var $rows = $(this).closest("div.list-table-row");

		if($("#sync-date").is(":checked")){
			$rows = $row.parent().children();
		}

		$rows.each(function(){
			setSelector($(this), begin, end, optionName, optionDesc);
			loadChannel($(this).attr("account_uid"), $(this).attr("device_uid"), $(this).attr("module_uid"), $(this).attr("channel"), begin, end, $(this));
		});
	});

	if($("#sync-date").is(":checked")){
		$("#sync-date").triggerHandler("syncSelector");
	}
	else{
		$options.eq(0).triggerHandler("click");
	}
}

function getChannelData(accountUID, deviceUID, moduleUID, channel, begin, end){//group uid, loop and phase no use
	return $.ajax({
		url: "history_io_ajax.php?act=get_channel_data",
		type: "POST",
		data: "account_uid=" + accountUID + "&device_uid=" + deviceUID + "&module_uid=" + moduleUID + "&channel=" + channel + "&begin=" + begin + "&end=" + end + "&timezone=" + encodeURIComponent(getTimeZone()),
		dataType: "json"
	});
}

function loadChannel(accountUID, deviceUID, moduleUID, channel, begin, end, $row){
	loaderCounter++;
	$("#loader").show();

	var ajax = $row.data("ajax");
	if(typeof(ajax) != "undefined"){
		ajax.abort();
	}

	ajax = getChannelData(accountUID, deviceUID, moduleUID, channel, begin, end).done(function(data){
		var getDate = function(timestamp){
			var date = new Date(timestamp);

			return padding(date.getHours(), 2) + ":" + padding(date.getMinutes(), 2) + ":" + padding(date.getSeconds(), 2);
		};

		//var $row = $("#list div.list-table-row[uid='" + uid + "'][channel='" + channel + "']");

		$row.data("series", {
			data: processChartData(data.data),
			lines: {
				steps: channel.match(/^(DI|DO|CI|CO)+\d/) ? true : false
			},
			accountUID: accountUID,
			deviceUID: deviceUID,
			moduleUID: moduleUID,
			channel: channel
		});

		if(data.data.length > 0){
			$row.find("span.module-value.max").text(parseFloat((data.data[data.data.maxIndex][1]).toPrecision(7)));
			$row.find("span.module-time.max").text("(" + getDate(data.data[data.data.maxIndex].timestamp) + ")");

			$row.find("span.module-value.min").text(parseFloat((data.data[data.data.minIndex][1]).toPrecision(7)));
			$row.find("span.module-time.min").text("(" + getDate(data.data[data.data.minIndex].timestamp) + ")");
		}
		else{
			$row.find("span.module-value.max").text("-");
			$row.find("span.module-time.max").text("(-)");

			$row.find("span.module-value.min").text("-");
			$row.find("span.module-time.min").text("(-)");
		}

//		data[0].color = "#953734";
//		data[1].color = "#76923c";
//		data[2].color = "#366092";
//		data[3].color = "#5f497a";
	}).always(function(){
		loaderCounter--;
		if(loaderCounter == 0){
			updateChart();
			updateChartRange();

			$("#loader").hide();
		}

		$row.removeData("ajax");
	});

	$row.data("ajax", ajax);
}

function processChartData(data){
	var max = -Infinity, min = Infinity;
	data.maxIndex = -1;
	data.minIndex = -1;
	data.total = 0;

	if(data.length > 0){
		var date = new Date(data[0][0] * 1000);
		date.setHours(0);
		date.setMinutes(0);
		date.setSeconds(0);

		data.baseTimestamp = date.getTime();
	}

	for(var i = 0; i < data.length; i++){
		data[i].timestamp = data[i][0] * 1000;

		data[i][0] = data[i].timestamp - data.baseTimestamp;
		data[i][1] = parseFloat(data[i][1]);

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

function updateChart(){
	var dataSet = [];
	var firstData = -1;

	$("#list div.list-table-row").each(function(index){
		var series = $(this).data("series");
		dataSet.push(series);

		if(series.data.length > 0 && firstData == -1){
			firstData = index;
		}
	});

	if(firstData >= 0){
		drawChart(dataSet);
		drawThumbnailChart(dataSet);

		//$("#thumbnail-chart .tickLabel").hide();
		$("#thumbnail-chart .flot-y-axis .flot-tick-label").css("visibility", "hidden");

		var $plot = $("#thumbnail-chart").data("plot");
		var data = $plot.getData()[0];

		var selectorWidth = data.xaxis.p2c(86400000);
		$("#thumbnail-selector").css({
			"width": selectorWidth + "px",
			"left": data.yaxis.box.width + "px"
		});

		if(!$("#thumbnail-selector").attr("min") && dataSet[firstData].data.length > 0){
			var min = dataSet[firstData].data[dataSet[firstData].data.length - 1][0] - 3600000;
			var max = dataSet[firstData].data[dataSet[firstData].data.length - 1][0];

			$("#thumbnail-selector").attr({
				min: min,
				max: max
			});

			$("#thumbnail-selector div.thumbnail-selector-out.left").css("width", data.xaxis.p2c(min) + "px");
			$("#thumbnail-selector div.thumbnail-selector-out.right").css("width", (selectorWidth - data.xaxis.p2c(max)) + "px");
		}

		$("#empty").hide();
	}
	else{
		dataSet.push({
			data: []
		});

		drawChart(dataSet);
		drawThumbnailChart(dataSet);

		$("#empty").show();
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
			tickLength: 0,
			//tickSize: 2,
//			ticks: function(axis){
//				return [
//					[    0, "00:00"], [ 3600, "01:00"], [ 7200, "02:00"], [10800, "03:00"], [14400, "04:00"], [18000, "05:00"], [21600, "06:00"], [25200, "07:00"], [28800, "08:00"], [32400, "09:00"], [36000, "10:00"], [39600, "11:00"],
//					[43200, "12:00"], [46800, "13:00"], [50400, "14:00"], [54000, "15:00"], [57600, "16:00"], [61200, "17:00"], [64800, "18:00"], [68400, "19:00"], [72000, "20:00"], [75600, "21:00"], [79200, "22:00"], [82800, "23:00"], [86400, "24:00"]
//				];
//
//				var date = new Date();
//				var nowDate = date.getDate();
//
//				date.setMilliseconds(0);
//				date.setSeconds(0);
//				date.setMinutes(0);
//				date.setHours(0);
//
//				var ticks = [];
//
//				while(nowDate == date.getDate()){
//					ticks.push([date.getHours() * 60 * 60  + date.getMinutes() * 60 + date.getSeconds(), padding(date.getHours(), 2) + ":" + padding(date.getMinutes(), 2)]);
//					date.setSeconds(date.getSeconds() + 300);
//				}
//
//				return ticks;
//			},
			min: 0,
			max: 86400000/*,
			labelWidth: 35*/
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
				var date = new Date(flotItem.series.data.baseTimestamp + xval);
				var yval = parseFloat((yval).toPrecision(7));
				var $row = $("#list div.list-table-row[account_uid='" + flotItem.series.accountUID + "'][device_uid='" + flotItem.series.deviceUID + "'][module_uid='" + flotItem.series.moduleUID + "'][channel='" + flotItem.series.channel + "']:first");

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
			tickLength: 5,
//			ticks: function(axis){
//				return [
//					[    0, "00:00"], [ 3600, "01:00"], [ 7200, "02:00"], [10800, "03:00"], [14400, "04:00"], [18000, "05:00"], [21600, "06:00"], [25200, "07:00"], [28800, "08:00"], [32400, "09:00"], [36000, "10:00"], [39600, "11:00"],
//					[43200, "12:00"], [46800, "13:00"], [50400, "14:00"], [54000, "15:00"], [57600, "16:00"], [61200, "17:00"], [64800, "18:00"], [68400, "19:00"], [72000, "20:00"], [75600, "21:00"], [79200, "22:00"], [82800, "23:00"], [86400, "24:00"]
//				];
//			},
			min: 0,
			max: 86400000
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

	table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['HISTORY_IO']['CHANNEL_WITH_COLON'];?></td><td>" + channel + "</td></tr>";
	table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['HISTORY_IO']['TIME_WITH_COLON'];?></td><td>" + time + "</td></tr>";
	table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['HISTORY_IO']['VALUE_WITH_COLON'];?></td><td>" + numberWithCommas(value) + "&nbsp;" + unit + "</td></tr></table>";

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
				$("<div></div>").attr("class", "date-picker-cell").text("<?=$lang['HISTORY_IO']['SUN'];?>")
			).append(
				$("<div></div>").attr("class", "date-picker-cell").text("<?=$lang['HISTORY_IO']['MON'];?>")
			).append(
				$("<div></div>").attr("class", "date-picker-cell").text("<?=$lang['HISTORY_IO']['TUE'];?>")
			).append(
				$("<div></div>").attr("class", "date-picker-cell").text("<?=$lang['HISTORY_IO']['WED'];?>")
			).append(
				$("<div></div>").attr("class", "date-picker-cell").text("<?=$lang['HISTORY_IO']['THU'];?>")
			).append(
				$("<div></div>").attr("class", "date-picker-cell").text("<?=$lang['HISTORY_IO']['FRI'];?>")
			).append(
				$("<div></div>").attr("class", "date-picker-cell").text("<?=$lang['HISTORY_IO']['SAT'];?>")
			)
		).append(
			$("<div></div>").attr("class", "date-picker-row-group " + type)
		)
	).bind("click", function(event){
		event.stopPropagation();
		return;
	});
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
		if(!$("#chart-container").is(":visible")){return;}

		updateChart();

		var $plot = $("#thumbnail-chart").data("plot");
		var data = $plot.getData()[0];
		var selectorWidth = data.xaxis.p2c(86400000);
		var $select = $("#thumbnail-selector");

		$("#thumbnail-selector div.thumbnail-selector-out.left").css("width", data.xaxis.p2c(parseFloat($select.attr("min"))) + "px");
		$("#thumbnail-selector div.thumbnail-selector-out.right").css("width", (selectorWidth - data.xaxis.p2c(parseFloat($select.attr("max")))) + "px");

		updateChartRange("chart");
	});

	$("#thumbnail-selector").bind("mousedown", function(event){
		var $plot = $("#thumbnail-chart").data("plot");
		var data = $plot.getData()[0];
		var offset = $plot.offset();
		var selectorWidth = data.xaxis.p2c(86400000);
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
			var $plot = $("#thumbnail-chart").data("plot");
			var data = $plot.getData()[0];
			var selectorWidth = data.xaxis.p2c(86400000);

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
		var selectorWidth = data.xaxis.p2c(86400000);
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

	$("#sync-date").bind("click", function(event){
		if($(this).is(":checked")){
			$(this).triggerHandler("syncSelector");
		}
	}).bind("syncSelector", function(){
		var begin = $(this).attr("begin");
		var end = $(this).attr("end");
		var optionName = $(this).attr("optionName");
		var optionDesc = $(this).attr("optionDesc");

		$("#list div.list-table-row").each(function(){
			var $container = $(this).find("div.time-button-container");

			if($container.attr("begin") == begin && $container.attr("end") == end){
				return;
			}

			$container.attr({
				"begin": begin,
				"end": end
			});

			$container.find("div.time-button div.time-button-option-name").text(optionName);
			$container.find("div.time-button div.time-button-option-desc").text(optionDesc);

			loadChannel($(this).attr("account_uid"), $(this).attr("device_uid"), $(this).attr("module_uid"), $(this).attr("channel"), begin, end, $(this));
		});
	});

	showChannelSelector(true);
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
			<div class="title" style="border-bottom-width:0px;padding-bottom:0px;"><?=$lang['HISTORY_IO']['SELECT_CHANNEL'];?></div>

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
				<div class="select-wrapper">
					<div class="select-list"></div>
					<div class="select-loader"><img src="./image/ajax-loader.gif"></div>
					<div class="select-arrow" style="position:absolute;top:50%;left:0;margin-left:-24px;">
						<svg><use xlink:href="image/ics.svg#arrow_forward"></use></svg>
					</div>
				</div>
			</div>

			<div id="select-cancel-button">
				<input type="button" value="<?=$lang['HISTORY_IO']['CANCEL'];?>" class="gray" style="width:100px;" onClick="hideChannelSelector('right');">
			</div>
		</div>

		<div id="chart-container">
			<div class="title"><?=$lang['HISTORY_IO']['MODULE_ANALYSIS'];?></div>

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
						<div><?=$lang['HISTORY_IO']['NO_DATA'];?></div>
					</div>
					<div id="loader" class="chart-loader" style="display:none;">
						<div><img src="image/ajax-loader.gif"></div>
					</div>
				</div>
			</div>

			<div class="list-table">
				<div class="list-table-header-group">
					<div class="list-table-row">
						<div class="list-table-cell"><?=$lang['HISTORY_IO']['MODULE'];?></div>
						<div class="list-table-cell" style="width:200px;"><?=$lang['HISTORY_IO']['TIME'];?></div>
						<div class="list-table-cell" style="width:200px;"><?=$lang['HISTORY_IO']['MAX'];?></div>
						<div class="list-table-cell" style="width:200px;"><?=$lang['HISTORY_IO']['MIN'];?></div>
						<div class="list-table-cell" style="width:1%;text-align:center;"><?=$lang['HISTORY_IO']['ACTHISTORY_ION'];?></div>
					</div>
				</div>

				<div class="list-table-row-group" id="list"></div>

				<div class="list-table-footer-group">
					<div class="list-table-row">
						<div class="list-table-cell"></div>
						<div class="list-table-cell"><input type="checkbox" id="sync-date"><label class="sync-date-label" for="sync-date"><?=$lang['HISTORY_IO']['SYNC_SELECTER'];?><label></div>
						<div class="list-table-cell"></div>
						<div class="list-table-cell"></div>
						<div class="list-table-cell">
							<input type="button" value="<?=$lang['HISTORY_IO']['ADD'];?>" style="width:100px;" onClick="showChannelSelector();">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="stringLength" style="display:none;"></div>
</div>
<?php
}
?>