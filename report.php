<?php
function customized_header(){
	global $lang;
?>
<link rel="stylesheet" type="text/css" href="./css/list.css">
<link rel="stylesheet" type="text/css" href="./css/picker.css">
<link rel="stylesheet" type="text/css" href="./css/tip.css">
<link rel="stylesheet" href="./css/tinymce.css" />
<link rel="stylesheet" type="text/css" href="./css/checkbox.css" />
<link rel="stylesheet" href="./css/report.css" />

<link rel="stylesheet" href="../css/report.css" />
<link rel="stylesheet" href="../js/TinyMCE/skins/ui/mytinymceskin/content.min.css" />
<link rel="stylesheet" href="../css/general.css" />


<style type="text/css">
.content-title{
	font-weight: bolder;
    color: #2a2a2a;
    margin-bottom: 10px;
}
.tox-tinymce{
	border:0px;
}
p{
	margin: 0;
}

/* no exist/expired */
#dashboard-no-exist-container,
#dashboard-expired-container{
	text-align:center;
	color:##035002c9;
	padding:100px 0;
}

.dashboard-no-exist-title,
.dashboard-expired-title{
	margin-bottom:10px;
	font-weight:bold;
}

.dashboard-no-exist-content,
.dashboard-expired-content{
	font-size:13px;
}

.popup-background{
	position: fixed;
}
</style>
<script src="./js/jquery.tip.js"></script>
<script language="javascript" src="./js/jquery.livequery.min.js"></script>
<script language="javascript" src="./js/TinyMCE/tinymce.min.js" referrerpolicy="origin"></script>
<script language="javascript" src="./js/TinyMCE/themes/silver/theme.min.js" referrerpolicy="origin"></script>
<script language="javascript" src="./js/TinyMCE/langs/zh_TW.js" referrerpolicy="origin"></script>
<script language="javascript" src="./js/TinyMCE/langs/zh_CN.js" referrerpolicy="origin"></script>
<script language="javascript" src="./js/TinyMCE/icons/default/icons.min.js" referrerpolicy="origin"></script>
<script language="javascript" src="./js/TinyMCE/plugins/advlist/plugin.min.js" referrerpolicy="origin"></script>
<script language="javascript" src="./js/TinyMCE/plugins/autolink/plugin.min.js" referrerpolicy="origin"></script>
<script language="javascript" src="./js/TinyMCE/plugins/lists/plugin.min.js" referrerpolicy="origin"></script>
<script language="javascript" src="./js/TinyMCE/plugins/link/plugin.min.js" referrerpolicy="origin"></script>
<script language="javascript" src="./js/TinyMCE/plugins/image/plugin.min.js" referrerpolicy="origin"></script>
<script language="javascript" src="./js/TinyMCE/plugins/code/plugin.min.js" referrerpolicy="origin"></script>
<script language="javascript" src="./js/TinyMCE/plugins/fullscreen/plugin.min.js" referrerpolicy="origin"></script>
<script language="javascript" src="./js/TinyMCE/plugins/table/plugin.min.js" referrerpolicy="origin"></script>


<script language="javascript" src="./js/jspdf.umd.min.js"></script>
<script language="javascript" src="./js/html2canvas.min.js"></script>

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

function loadDeviceList(){
	$("#select-device div.select-list").empty();
	$("#select-module").hide();

	return $.Deferred(function(deferred){
		$.ajax({
			url: "report_ajax.php?act=get_device_list",
			type: "POST",
			dataType: "xml",
			success: function(data, textStatus, jqXHR){
				var $xmlCMD = $(data).find("cmd");
				if($xmlCMD.attr("result") == "ERROR"){
					$("#dashboard-expired-container").show();
					return;
				}
				$("#report-container").show();
				var $devices = $(data).find("cmd > device");
				if($devices.length > 0){
					$("#select-device div.select-list").append(
						$("<div></div>").attr("class", "select-title").text("<?=$lang['REPORT_SERVICE']['DEVICE'];?>")
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
										"tip": "<?=$lang['REPORT_SERVICE']['TIP']['SHARE_BY_USER'];?>".replace("%username%", $device.attr("account_nickname") + "(" + $device.attr("account_username") + ")")
									}).append(
										createSVGIcon("image/ics.svg", "share")
									);
								}
							})())
						);
					}
				}

				var $groups = $(data).find("cmd > group");
				if($groups.length > 0){
					$("#select-device div.select-list").append(
						$("<div></div>").attr("class", "select-title").text("<?=$lang['REPORT_SERVICE']['GROUP'];?>")
					);

					for(var i = 0; i < $groups.length; i++){
						var $group = $($groups[i]);

						$("#select-device div.select-list").append(
							$("<div></div>").attr({
								"class": "select-option",
								"group_info_uid": $group.attr("uid"),
								"type":$group.attr("type")
							}).text($group.attr("name")).bind("click", function(){
								
								isGroup = true;

								$(this).addClass("active").siblings().removeClass("active");

								hideChannelSelector().always(function(){
									$("div.period-button-container:eq(0)").find("div.period-button:first()").triggerHandler("click");
									$("div.sub-title").text($("#select-device div.select-list div.select-option.active").text());
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
				$("#select-device div.select-loader").hide();
			}
		})
	}).promise();
	
}
//讀取ModuleList
function loadModuleList(accountUID, deviceUID){//uid is 16bytes sn
	$("#select-module div.select-list").empty();
	$("#select-channel").hide();

	return $.ajax({
		url: "report_ajax.php?act=get_module_list",
		type: "POST",
		data: "account_uid=" + accountUID + "&device_uid=" + deviceUID,
		dataType: "xml",
		success: function(data, textStatus, jqXHR){
			$("#select-module div.select-list").empty();

			var $modules = $(data).find("list > module");
			if($modules.length > 0){
				var interfaces = {
					"comport": [],
					"xv": [],
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
							"phase": $module.attr("phase"),
							"module_type": $module.attr("type")
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
							"phase": $module.attr("phase"),
							"module_type": $module.attr("type")
						};
					}
					else if($module.attr("interface") == "XV"){
						if(typeof(interfaces.xv[0]) == "undefined"){
							interfaces.xv[0] = {
								name: "XV",
								modules: []
							}
						}

						interfaces.xv[0].modules[parseInt($module.attr("number"), 10)] = {
							"uid": $module.attr("uid"),
							"modelName": $module.attr("model_name"),
							"nickname": $module.attr("nickname"),
							"loop": $module.attr("loop"),
							"phase": $module.attr("phase"),
							"module_type": $module.attr("type")
						};
					}
				}

				for(var sourceTypeIndex = 0, sourceTypeArray = ["comport", "network", "xv"]; sourceTypeIndex < sourceTypeArray.length; sourceTypeIndex++){
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
									"phase": module.phase,
									"module_type": module.module_type
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
		url: "report_ajax.php?act=get_channel_list",
		type: "POST",
		data: "account_uid=" + accountUID + "&device_uid=" + deviceUID + "&module_uid=" + moduleUID,
		dataType: "xml",
		success: function(data, textStatus, jqXHR){
			// Create option(All loop)
			$("#select-channel").attr("type",$($(data).find("list > Type")[0]).attr("value"));
			if($($(data).find("list > Type")[0]).attr("value")=="1"){//PM
				$("#select-channel div.select-list").append(
					$("<div></div>").attr("class", "select-title").text("<?=$lang['HISTORY_ENERGY']['LOOP'];?>")
				);

				var $loops = $(data).find("list > loop");
				if($loops.length > 0){
					for(var i = 0; i < $loops.length; i++){
						var $loop = $($loops[i]);
						var loopname = "<?=$lang['HISTORY_ENERGY']['LOOP'];?>" + (i + 1);
						if($($loops[i]).attr("nickname") != "")
							loopname += "(" + $($loops[i]).attr("nickname") + ")";
						
						$("#select-channel div.select-list").append(
							$("<div></div>").attr({
								"class": "select-option",
								"_loop": i + 1//attr loop is use in video
							}).text(loopname).bind("click", function(){
								isGroup = false;

								// Check the container is running animate
								if($("#select-container").is(":animated")){
									return;
								}

								$(this).addClass("active").siblings().removeClass("active");

								hideChannelSelector().always(function(){
									$("div.period-button-container:eq(0)").find("div.period-button:first()").triggerHandler("click");
									$("div.sub-title").text($("#select-device div.select-list div.select-option.active").text() + " / " + $("#select-module div.select-list div.select-option.active").text() + " / " + $("#select-channel div.select-list div.select-option.active").text());
								});
							})
						);
					}
				}
			}
			else{
				$("#select-channel div.select-list").append(
					$("<div></div>").attr("class", "select-title").text("<?=$lang['REPORT_SERVICE']['IO_CHANNEL'];?>")
				);
				
				var $channels = $(data).find("list > channel");
				if($channels.length > 0){
					for(var i = 0; i < $channels.length; i++){
						var $channel = $($channels[i]);
						if($channel.attr("name")==""){
							continue;
						}
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
						
						if($channel.attr("nickname") != ""){
							channelName += "(" + $channel.attr("nickname") + ")";
						}

						$("#select-channel div.select-list").append(
							$("<div></div>").attr({
								"class": "select-option",
								"channel": $channel.attr("name")
							}).text(channelName).bind("click", function(){
								isGroup = false;
								
								// Check the container is running animate
								if($("#select-container").is(":animated")){
									return;
								}

								$(this).addClass("active").siblings().removeClass("active");

								hideChannelSelector().always(function(){
									$("div.period-button-container:eq(0)").find("div.period-button:first()").triggerHandler("click");
									$("div.sub-title").text($("#select-device div.select-list div.select-option.active").text() + " / " + $("#select-module div.select-list div.select-option.active").text() + " / " + $("#select-channel div.select-list div.select-option.active").text());
								});
							})
						);
					}
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

$(document).ready(function(){
	// time range
	$(".time-button").bind("click.time-button", function(eveny){
		if(!$(this).hasClass("active")){
			$(document).triggerHandler("click.time-button");//close everyone
			$(this).addClass("active").siblings("div.time-button-select").show();
			event.stopPropagation();
		}
	});

	$(document).bind("click.time-button", function(){
		$(".time-button").removeClass("active").siblings("div").hide();
		$("#compare_time_format-button-container div.field-button").removeClass("active").siblings("div").hide();
		$("#div_field-button-wrapper div.field-button").removeClass("active").siblings("div").hide();
		//group
		$("#report_format-button-container div.field-button").removeClass("active").siblings("div").hide();
		$("#report_type-button-container div.field-button").removeClass("active").siblings("div").hide();
	});
	
	$("#compare_time_format").change(function(){
		if($(this).find(":selected").index() == 0){
			$("#time-compare").hide();
			$("div.time-button-container[time-type='major']:eq(0)").find("div.time-button-select div.time-button-option:eq(0)").triggerHandler("click");
		}
		else{
			$("#time-compare").show()
			$("div.time-button-container[time-type='compare']:eq(0)").find("div.time-button-select div.time-button-option:eq(1)").triggerHandler("click");
		}
	});
	
	$("#compare_time_format-button-container > div.field-button").bind("click", function(event){
		$(".time-button").removeClass("active").siblings("div").hide();
		$("#div_field-button-wrapper div.field-button").removeClass("active").siblings("div").hide();
		
		if(!$(this).hasClass("active")){
			$(document).triggerHandler("click");//close everyone
			$(this).addClass("active").siblings("div.field-button-select").show();
			event.stopPropagation();
		}
	});
	
	$("#compare_time_format-button-container > div.field-button-select > div.field-button-option").bind("click", function(event){
		$("#compare_time_format-button-container > div.field-button").removeClass("active").siblings("div").hide();
		$("#interface_compare_time_format .field-button-option-name").text($(this).text());
		$("#compare_time_format").prop("selectedIndex",$(this).attr("select_id"));
		$("#compare_time_format").trigger('change');
	});
	
	$(".period-button").bind("click", function(){
		$("#interface_report_format div.field-button-select div.field-button-option").unbind("click");
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

			var begin = new Date(), end = begin;
			var $option = $("<div></div>").addClass("time-button-option").append(
				$("<div></div>").addClass("time-button-option-name").text("<?=$lang['HISTORY_ENERGY']['TODAY'];?>")
			).append(
				$("<div></div>").addClass("time-button-option-desc")
			).appendTo($select);

			$select.triggerHandler("showDesc", [$option, begin, end]);

			var begin = new Date(), end = begin;
			begin.setDate(begin.getDate() - 1);
			var $option = $("<div></div>").addClass("time-button-option").append(
				$("<div></div>").addClass("time-button-option-name").text("<?=$lang['HISTORY_ENERGY']['YESTERDAY'];?>")
			).append(
				$("<div></div>").addClass("time-button-option-desc")
			).appendTo($select);

			$select.triggerHandler("showDesc", [$option, begin, end]);

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
			var begin = new Date();
			begin.setDate(begin.getDate() - begin.getDay());
			var end = new Date();
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
		
		$("div.time-button-option").bind("click.loadData", function(event){
			
			var timeType = $(this).closest(".time-button-container").attr("time-type");
			
			var $containers = $("div.time-button-container[time-type='" + timeType + "']").attr({
				"begin": $(this).attr("begin"),
				"end": $(this).attr("end")
			});
			
			$containers.find("div.time-button div.time-button-option-name").text($(this).find("div.time-button-option-name").text());
			$containers.find("div.time-button div.time-button-option-desc").text($(this).find("div.time-button-option-desc").text());
			
			if($("#compare_time_format")[0].selectedIndex == 0){
				if(timeType == "major"){
					loadReport();
				}
			}
			else{
				loadReport();
			}

			$(this).addClass("active").siblings().removeClass("active");
			
		});
		
		$("#compare_time_format")[0].selectedIndex = 0;
		$("#compare_time_format").trigger('change');
		$("#div_time_format .field-button-option-name").text($("#interface_compare_time_format [select_id=0]").text());
		
		
		$("#select_report_type_col").html("");
		if(isGroup){
			if($("#select-device .select-wrapper .select-list .select-option.active").attr("type") == 0){//IO
				$("#select_report_type_col").append(
					$("<option><?=$lang['REPORT_SERVICE']['MAX'];?></option>").attr("col_type","_max")
				).append(
					$("<option><?=$lang['REPORT_SERVICE']['MIN'];?></option>").attr("col_type","_min")
				).append(
					$("<option><?=$lang['REPORT_SERVICE']['AVERAGE'];?></option>").attr("col_type","_avg")
				).append(
					$("<option><?=$lang['REPORT_SERVICE']['FINAL'];?></option>").attr("col_type","_fin")
				).append(
					$("<option><?=$lang['REPORT_SERVICE']['TOTAL'];?></option>").attr("col_type","_sum")
				);
				$("#select_report_type_col").change(function(){
					$("#module_list_table").html(CreateIOGroupTabel());
				});
				
				$("#interface_select_report_type_col .field-button-option-name").text($("#select_report_type_col :selected").text());
				$("#interface_select_report_type_col .field-button-select").html("");
				$("#interface_select_report_type_col .field-button-select").append('<div class="field-button-option" style="display: block;" select_id="0"><?=$lang["REPORT_SERVICE"]["MAX"];?></div>');
				$("#interface_select_report_type_col .field-button-select").append('<div class="field-button-option" style="display: block;" select_id="1"><?=$lang["REPORT_SERVICE"]["MIN"];?></div>');
				$("#interface_select_report_type_col .field-button-select").append('<div class="field-button-option" style="display: block;" select_id="2"><?=$lang["REPORT_SERVICE"]["AVERAGE"];?></div>');
				$("#interface_select_report_type_col .field-button-select").append('<div class="field-button-option" style="display: block;" select_id="3"><?=$lang["REPORT_SERVICE"]["FINAL"];?></div>');
				$("#interface_select_report_type_col .field-button-select").append('<div class="field-button-option" style="display: block;" select_id="4"><?=$lang["REPORT_SERVICE"]["TOTAL"];?></div>');
				
			}
			else{//PM
				$("#select_report_type_col").append(
					$("<option><?=$lang['REPORT_SERVICE']['MAX_DEMAND'];?></option>").attr("col_type","_demand")
				).append(
					$("<option><?=$lang['REPORT_SERVICE']['KWH_TITLE'];?></option>").attr("col_type","_kwh")
				).append(
					$("<option><?=$lang['REPORT_SERVICE']['PF'];?></option>").attr("col_type","_pf")
				).append(
					$("<option><?=$lang['REPORT_SERVICE']['I'];?></option>").attr("col_type","_i")
				).append(
					$("<option><?=$lang['REPORT_SERVICE']['V'];?></option>").attr("col_type","_v")
				).append(
					$("<option><?=$lang['REPORT_SERVICE']['KVA'];?></option>").attr("col_type","_kva")
				).append(
					$("<option><?=$lang['REPORT_SERVICE']['KVAR'];?></option>").attr("col_type","_kvar")
				);
				$("#select_report_type_col").change(function(){
					$("#module_list_table").html(CreatePMGroupTable());
				})
				
				$("#interface_select_report_type_col .field-button-option-name").text($("#select_report_type_col :selected").text());
				$("#interface_select_report_type_col .field-button-select").html("");
				$("#interface_select_report_type_col .field-button-select").append('<div class="field-button-option" style="display: block;" select_id="0"><?=$lang["REPORT_SERVICE"]["MAX_DEMAND"];?></div>');
				$("#interface_select_report_type_col .field-button-select").append('<div class="field-button-option" style="display: block;" select_id="1"><?=$lang["REPORT_SERVICE"]["KWH_TITLE"];?></div>');
				$("#interface_select_report_type_col .field-button-select").append('<div class="field-button-option" style="display: block;" select_id="2"><?=$lang["REPORT_SERVICE"]["PF"];?></div>');
				$("#interface_select_report_type_col .field-button-select").append('<div class="field-button-option" style="display: block;" select_id="3"><?=$lang["REPORT_SERVICE"]["I"];?></div>');
				$("#interface_select_report_type_col .field-button-select").append('<div class="field-button-option" style="display: block;" select_id="4"><?=$lang["REPORT_SERVICE"]["V"];?></div>');
				$("#interface_select_report_type_col .field-button-select").append('<div class="field-button-option" style="display: block;" select_id="5"><?=$lang["REPORT_SERVICE"]["KVA"];?></div>');
				$("#interface_select_report_type_col .field-button-select").append('<div class="field-button-option" style="display: block;" select_id="6"><?=$lang["REPORT_SERVICE"]["KVAR"];?></div>');
			}
		}
		
		$("#report_type-button-container > div.field-button").bind("click.field-button", function(event){
			$("#report_format-button-container div.field-button").removeClass("active").siblings("div").hide();
			if(!$(this).hasClass("active")){
				$(document).triggerHandler("click.field-button");
				$(this).addClass("active").siblings("div.field-button-select").show();
				event.stopPropagation();
			}
		});
		$("#interface_select_report_type_col div.field-button-select div.field-button-option").bind("click", function(event){
			$("#interface_select_report_type_col .field-button-option-name").text($(this).text());
			$("#select_report_type_col").prop("selectedIndex",$(this).attr("select_id"));
			$("#select_report_type_col").trigger('change');
		});
		
		
		$("#report_format-button-container > div.field-button").bind("click.field-button", function(event){
			$("#report_type-button-container div.field-button").removeClass("active").siblings("div").hide();
			if(!$(this).hasClass("active")){
				$(document).triggerHandler("click.field-button");
				$(this).addClass("active").siblings("div.field-button-select").show();
				event.stopPropagation();
			}
		});
		$("#interface_report_format .field-button-option-name").text($("#report_format :selected").text());
		$("#interface_report_format div.field-button-select div.field-button-option").bind("click", function(event){
				$("#interface_report_format div.field-button").removeClass("active").siblings("div").hide();
				$("#interface_report_format .field-button-option-name").text($(this).text());
				$("#report_format").prop("selectedIndex",$(this).attr("select_id"));
				$("#report_format").trigger('change');
		});
	});	
	
	$("#export").bind('click',function(){
		$("#select_report_template_exl").html("");
		$("#select_report_template_exl").append(
			$("<option><?=$lang['REPORT_SERVICE']['NO_TEMPLATE'];?></option>").val("-1")
		);
		var tempList=[];
		LoadTemplateList(tempList);
		
		$.each(tempList, function(index,value) {
			$("#select_report_template_exl").append($("<option></option>").val(value["id"]).text(value["name"]));
		});
		
		popupWindow2($("#window-SelectTemplate"),function(result){
			if(result == "ok"){
				export_notification($("#select_report_template_exl").val());
			}
			else if(result == "cancel"){
				
			}
			$("#window-SelectTemplate").hide();
		});
	});

	$("#select-device").show();
	$("#select-device div.select-loader").show();
	
	loadDeviceList();
});


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
				loadReport();
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
				loadReport();
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
				loadReport();
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
	$("#select-container").css({
		"opacity": 1,
		"marginLeft": 0
	}).animate({
		"opacity": 0,
		"marginLeft": (factor * 200) + "px"
	}, "fast", function(){
		$(this).hide();

		//show chart
		$("#div_report").parent().css("overflow", "");
		$("#div_report").show();

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

function padding(number, length, paddingChar){
	paddingChar = typeof(paddingChar) == "undefined" ? "0" : paddingChar;

    var str = "" + number;
    while (str.length < length) {
        str = paddingChar + str;
    }
   
    return str;
}

var xmlData;
function loadReport(){
	$("#wait-loader").show();
	var ajax_url = "";
	var ajax_data = {};
	if(!isGroup){
		var accountUID = $("#select-device div.select-list div.select-option.active").attr("account_uid");
		var deviceUID = $("#select-device div.select-list div.select-option.active").attr("device_uid");
		var moduleUID = $("#select-module div.select-list div.select-option.active").attr("module_uid");
		var loop = $("#select-channel div.select-list div.select-option.active").attr("_loop");
		var channel = $("#select-channel div.select-list div.select-option.active").attr("channel");
		var datetime_begin = $(".time-button-container").attr("begin");
		var datetime_end = $(".time-button-container").attr("end");
		var module_type = $("#select-module div.select-list div.select-option.active").attr("module_type");
		if(module_type == "1"){
			$("#title").text("<?=$lang['REPORT_SERVICE']['POWER_METER_LOOP_REPORT'];?>");
		}
		else{
			$("#title").text("<?=$lang['REPORT_SERVICE']['IO_CHANNEL_REPORT'];?>");
		}
		ajax_url = "report_ajax.php?act=load_report_data";
		
		ajax_data = {
			accountUID :accountUID,
			DeviceUID : deviceUID,
			moduleUID : moduleUID,
			Loop : loop,
			Channel : channel,
			datetime_begin : datetime_begin,
			datetime_end : datetime_end,
			report_type : $(".period-button.active").attr("period").toUpperCase(),
			timezone : getTimeZone()
		};
		if($("#compare_time_format")[0].selectedIndex == 1){
			ajax_data["compare_datetime_begin"] = $($(".time-button-container")[1]).attr("begin");
			ajax_data["compare_datetime_end"] = $($(".time-button-container")[1]).attr("end");
		}
	}
	else{
		var uid = $("#select-device .select-wrapper .select-list .select-option.active").attr("group_info_uid");
		var type = $("#select-device .select-wrapper .select-list .select-option.active").attr("type");
		var datetime_begin = $(".time-button-container").attr("begin");
		var datetime_end = $(".time-button-container").attr("end");
		if(type == "1"){
			if($("#report_format :selected").val() == "1"){
				$("#title").text("<?=$lang['REPORT_SERVICE']['POWER_METER_GROUP_REPORT'];?>");
				ajax_url= "report_ajax.php?act=load_pm_report_data_group";
				ajax_data={	
					GroupUID : uid,
					datetime_begin : datetime_begin,
					datetime_end : datetime_end,
					report_type : $(".period-button.active").attr("period").toUpperCase(),
					timezone : getTimeZone()
				};
			}
			else{
				$("#title").text("<?=$lang['REPORT_SERVICE']['POWER_METER_GROUP_REPORT'];?>");
				ajax_url= "report_ajax.php?act=load_pm_report_data_group_f2";
				ajax_data={	
					GroupUID : uid,
					datetime_begin : datetime_begin,
					datetime_end : datetime_end,
					report_type : $(".period-button.active").attr("period").toUpperCase(),
					timezone : getTimeZone()
				};
			}
		}
		else{
			$("#title").text("<?=$lang['REPORT_SERVICE']['IO_CHANNEL_GROUP_REPORT'];?>");
			ajax_url= "report_ajax.php?act=load_io_report_data_group";
			ajax_data={
				GroupUID : uid,
				datetime_begin : datetime_begin,
				datetime_end : datetime_end,
				report_type : $(".period-button.active").attr("period").toUpperCase(),
				report_attr : $("#select_report_type_col :selected").attr("report_type"),
				timezone : getTimeZone()
			};
		}
	}
	
	$.ajax({
		url: ajax_url,
		data:ajax_data,
		type: "POST",
		dataType: "xml",
		timeout: 0,
		success: function(data, textStatus, jqXHR){
			//if($("#select-channel").attr("type") == "1"){
			//	$("#title").text("<?=$lang['REPORT_SERVICE']['POWER_METER_LOOP_REPORT'];?>");
			//}
			//else{
			//	$("#title").text("<?=$lang['REPORT_SERVICE']['IO_CHANNEL_REPORT'];?>");
			//}
			if($(data).find("info>Data").length == 0){
				$("#export").hide();
				$("#report_style").hide();
				$("#export_PDF").hide();
				
				$("#div_field-button-wrapper").hide();
				$("#div_field-button-wrapper").prev().hide();
				$("#div_time_format").hide();
				$("#div_time_format").prev().hide();
				html_str = "<div id='energy-empty' class='table-empty' style='display: block;'>";
				html_str += "<div><?=$lang['REPORT_SERVICE']['NO_DATA'];?></div>";
				html_str += "</div>";
				$("#module_list_table").html(html_str);
			}
			else{
				$("#export").show();
				$("#report_style").show();
				$("#export_PDF").show();
				
				$("#div_report_format").hide();
				$("#div_report_format").prev().hide();
				$("#select_report_type_col_div").hide();
				$("#select_report_type_col_div").prev().hide();
				if(!isGroup){
					$("#div_field-button-wrapper").show();
					$("#div_field-button-wrapper").prev().show();
					$("#div_time_format").show();
					$("#div_time_format").prev().show();
					
					xmlData = data;
					var ck_array = [];
					for(var ck_idx=0; ck_idx<$(".field-button-container > .field-button-select input").length; ck_idx++){
						ck_array.push($($(".field-button-container > .field-button-select input")[ck_idx]).prop("checked")); 
					}
					html_str = CreateTable();
				}
				else{
					$("#div_field-button-wrapper").hide();
					$("#div_field-button-wrapper").prev().hide();
					$("#div_time_format").hide();
					$("#div_time_format").prev().hide();
					if(type == 1){
						//$("#title").text("<?=$lang['REPORT_SERVICE']['POWER_METER_GROUP_REPORT'];?>");
						$("#div_report_format").show();
						$("#div_report_format").prev().show();
						xmlData = data;
						html_str = CreatePMGroupTable();
					}
					else{
						//$("#title").text("<?=$lang['REPORT_SERVICE']['IO_CHANNEL_GROUP_REPORT'];?>");
						$("#select_report_type_col_div").show();
						$("#select_report_type_col_div").prev().show();
						xmlData = data;
						html_str = CreateIOGroupTabel();					
					}
				}
				$("#module_list_table").html(html_str);
				
				
				if(typeof(ck_array)!="undefined" && ck_array.length != 0){
					for(var i=0;i<ck_array.length;i++){
						if(!ck_array[i])
							$($("#div_field-button-wrapper .field-button-wrapper .field-button-option")[i]).trigger("click");
					}
				}
				
			}
			$("#wait-loader").hide();
		},
		error: function(jqXHR, textStatus, errorThrown){
			$("#wait-loader").hide();
			if(jqXHR.status === 0){ return; }

			alert(jqXHR.responseText);
		}
	});
}


function CreateTable(){
	var col_list = [];
	var col_text = [];
	var col_item = [];
	var compare_col_item = [];
	var ck_compare = ($("#compare_time_format")[0].selectedIndex == 1);
	var Unit = "";
	
	if($($(xmlData).find("info")[0]).attr("phase")==4){
		col_list = col_text = ["<?=$lang['REPORT_SERVICE']['MAX_DEMAND'];?>","<?=$lang['REPORT_SERVICE']['KWH_TITLE'];?>","<?=$lang['REPORT_SERVICE']['PF'];?>","<?=$lang['REPORT_SERVICE']['I_A'];?>","<?=$lang['REPORT_SERVICE']['V_A'];?>","<?=$lang['REPORT_SERVICE']['I_B'];?>","<?=$lang['REPORT_SERVICE']['V_B'];?>","<?=$lang['REPORT_SERVICE']['I_C'];?>","<?=$lang['REPORT_SERVICE']['V_C'];?>","<?=$lang['REPORT_SERVICE']['KVA'];?>","<?=$lang['REPORT_SERVICE']['KVAR'];?>"];
		col_item = ["Demand","kWh","PF","I_A","V_A","I_B","V_B","I_C","V_C","kVA","kvar"];
		compare_col_item = ["c_Demand","c_kWh","c_PF","c_I_A","c_V_A","c_I_B","c_V_B","c_I_C","c_V_C","c_kVA","c_kvar"];
	}
	else if($($(xmlData).find("info")[0]).attr("phase")==1){
		col_list = col_text = ["<?=$lang['REPORT_SERVICE']['MAX_DEMAND'];?>","<?=$lang['REPORT_SERVICE']['KWH_TITLE'];?>","<?=$lang['REPORT_SERVICE']['PF'];?>","<?=$lang['REPORT_SERVICE']['I'];?>","<?=$lang['REPORT_SERVICE']['V'];?>","<?=$lang['REPORT_SERVICE']['KVA'];?>","<?=$lang['REPORT_SERVICE']['KVAR'];?>"]
		col_item = ["Demand","kWh","PF","I","V","kVA","kvar"];
		compare_col_item = ["c_Demand","c_kWh","c_PF","c_I","c_V","c_kVA","c_kvar"];
	}
	else{
		Unit = $($(xmlData).find("info>ChannelInfo")[0]).attr("Unit");
		col_list = ["<?=$lang['REPORT_SERVICE']['MAX'];?>", "<?=$lang['REPORT_SERVICE']['MIN'];?>", "<?=$lang['REPORT_SERVICE']['AVERAGE'];?>", "<?=$lang['REPORT_SERVICE']['FINAL'];?>", "<?=$lang['REPORT_SERVICE']['TOTAL'];?>"];
		col_text = ["<?=$lang['REPORT_SERVICE']['MAX'];?>", "<?=$lang['REPORT_SERVICE']['MIN'];?>", "<?=$lang['REPORT_SERVICE']['AVERAGE'];?>", "<?=$lang['REPORT_SERVICE']['FINAL'];?>", "<?=$lang['REPORT_SERVICE']['TOTAL'];?>"];
		col_item = ["Max","Min","Avg","Final","Sum"];
		compare_col_item = ["c_Max","c_Min","c_Avg","c_Final","c_Sum"];
	}
	
	
	$($(".field-button-container > .field-button-select")[1]).html("");
	$(col_list).each(function(index){
		$($(".field-button-container > .field-button-select")[1]).append('<div class="field-button-option"><div class="field-button-checkbox checkbox"><input type="checkbox" id="checkbox-' + index + '"><label></label></div><div class="field-button-option-name">' + col_list[index] + '</div></div>');
	});
	
	$("#field-button-container > div.field-button").bind("click.field-button", function(event){
		$(".time-button").removeClass("active").siblings("div").hide();
		$("#compare_time_format-button-container div.field-button").removeClass("active").siblings("div").hide();
		if(!$(this).hasClass("active")){
			$(document).triggerHandler("click.field-button");
			$(this).addClass("active").siblings("div.field-button-select").show();
			event.stopPropagation();
		}
	});
	$("#field-button-container > div.field-button-select > div.field-button-option").bind("click.field-button", function(event){
		var $checkbox = $(this).find(".checkbox").find("input");
		$checkbox.prop("checked", !$checkbox.prop("checked"));
		event.stopPropagation();
	});
	$(document).bind("click.field-button", function(){
		$("#field-button-container > div.field-button").removeClass("active").siblings("div").hide();
	});
	$("#field-button-container > div.field-button").css("width", $("#field-button-container > div.field-button-select").width());
	$(".field-button-container > .field-button-select input").prop("checked", true);
	
	$(".field-button-wrapper .field-button-option").bind("click",function(){
		if($($(this).find("input")).attr("checked") == "checked")
			$("[col_show="+$($(this).find("input")).attr("id")+"]").show();
		else
			$("[col_show="+$($(this).find("input")).attr("id")+"]").hide();
	});
	
	var html_str = "";
	html_str += "<tr>";
	if($(".period-button.active").attr("period").toUpperCase()=="DAY")
		html_str += "<td class='module-list-table-cell header' style='width: 4.34%;'><?=$lang['REPORT_SERVICE']['TIME'];?></td>";
	else if($(".period-button.active").attr("period").toUpperCase()=="MONTH"||$(".period-button.active").attr("period").toUpperCase()=="WEEK")
		html_str += "<td class='module-list-table-cell header' style='width: 4.34%;'><?=$lang['REPORT_SERVICE']['DATE'];?></td>";
	else
		html_str += "<td class='module-list-table-cell header' style='width: 4.34%;'><?=$lang['REPORT_SERVICE']['MONTH_TITLE'];?></td>";
	$(col_text).each(function(index){
		if(Unit == ""){
			if(ck_compare)
				html_str += "<td class='module-list-table-cell header' col_show='checkbox-" + index + "' colspan='2'>"+col_text[index]+"</td>";
			else
				html_str += "<td class='module-list-table-cell header' col_show='checkbox-" + index + "'>"+col_text[index]+"</td>";
		}
		else{
			if(ck_compare)
				html_str += "<td class='module-list-table-cell header' col_show='checkbox-" + index + "' colspan='2'>"+col_text[index]+ "(" + Unit + ")" + "</td>";
			else
				html_str += "<td class='module-list-table-cell header' col_show='checkbox-" + index + "'>"+col_text[index]+ "(" + Unit + ")" + "</td>";
		}
		
	});
	html_str += "</tr>";
	
	
	for(var i=0;i<$(xmlData).find("info>Data").length;i++){
		html_str += "<tr>";
		
		if($(".period-button.active").attr("period").toUpperCase()=="YEAR"||$(".period-button.active").attr("period").toUpperCase()=="QUARTER"){
			var header_month = "";
			switch($($(xmlData).find("info>Data")[i]).attr("Time")) {
				case "1":header_month = "<?=$lang['REPORT_SERVICE']['JAN'];?>";break;
				case "2":header_month = "<?=$lang['REPORT_SERVICE']['FEB'];?>";break;
				case "3":header_month = "<?=$lang['REPORT_SERVICE']['MAR'];?>";break;
				case "4":header_month = "<?=$lang['REPORT_SERVICE']['APR'];?>";break;
				case "5":header_month = "<?=$lang['REPORT_SERVICE']['MAY'];?>";break;
				case "6":header_month = "<?=$lang['REPORT_SERVICE']['JUN'];?>";break;
				case "7":header_month = "<?=$lang['REPORT_SERVICE']['JUL'];?>";break;
				case "8":header_month = "<?=$lang['REPORT_SERVICE']['AUG'];?>";break;
				case "9":header_month = "<?=$lang['REPORT_SERVICE']['SEP'];?>";break;
				case "10":header_month = "<?=$lang['REPORT_SERVICE']['OCT'];?>";break;
				case "11":header_month = "<?=$lang['REPORT_SERVICE']['NOV'];?>";break;
				case "12":header_month = "<?=$lang['REPORT_SERVICE']['DEC'];?>";break;
			}
			html_str += "<td class='module-list-table-cell'>" + header_month + "</td>";
		}
		else if($(".period-button.active").attr("period").toUpperCase()=="WEEK"){
			switch($($(xmlData).find("info>Data")[i]).attr("Time")) {
				case "1":html_str += "<td class='module-list-table-cell'><?=$lang['HISTORY_ENERGY']['SUN'];?></td>";break;
				case "2":html_str += "<td class='module-list-table-cell'><?=$lang['HISTORY_ENERGY']['MON'];?></td>";break;
				case "3":html_str += "<td class='module-list-table-cell'><?=$lang['HISTORY_ENERGY']['TUE'];?></td>";break;
				case "4":html_str += "<td class='module-list-table-cell'><?=$lang['HISTORY_ENERGY']['WED'];?></td>";break;
				case "5":html_str += "<td class='module-list-table-cell'><?=$lang['HISTORY_ENERGY']['THU'];?></td>";break;
				case "6":html_str += "<td class='module-list-table-cell'><?=$lang['HISTORY_ENERGY']['FRI'];?></td>";break;
				case "7":html_str += "<td class='module-list-table-cell'><?=$lang['HISTORY_ENERGY']['SAT'];?></td>";break;
			}
		}
		else
			html_str += "<td class='module-list-table-cell'>" + $($(xmlData).find("info>Data")[i]).attr("Time") + "</td>";
		
		$(col_item).each(function(index){
			if(!ck_compare)
				html_str += "<td class='module-list-table-cell' col_show='checkbox-" + index + "'>" + $($(xmlData).find("info>Data")[i]).attr(col_item[index]) + "</td>";
			else{
				html_str += "<td class='module-list-table-cell' col_show='checkbox-" + index + "' style='background-color: #F5F5F5;'>" + $($(xmlData).find("info>Data")[i]).attr(col_item[index]) + "</td>";
				html_str += "<td class='module-list-table-cell' col_show='checkbox-" + index + "'>" + $($(xmlData).find("info>Data")[i]).attr(compare_col_item[index]) + "</td>";
			}
		});
		html_str += "</tr>";
	}
	
	if(!ck_compare){
		if($($(xmlData).find("info")[0]).attr("phase")==1 || $($(xmlData).find("info")[0]).attr("phase")==4){
			html_str += '<tr><td class="module-list-table-cell header" colspan="12" align="center"><?=$lang["REPORT_SERVICE"]["SUMMARY"];?></td></tr>';
			html_str += "<tr>";
			html_str += "<td colspan='12' class='module-list-table-cell summary' style='line-height:20px;'>";
			
			var othertitle_array = {
				DAY: {
					Max_Demand: "<?=$lang['REPORT_SERVICE']['DAILY_MAXIMUM_DEMAND'];?>",
					KWH:"<?=$lang['REPORT_SERVICE']['DAILY_TOTAL_ENERGY_CONSUMPTION'];?>"
				},
				WEEK:{
					Max_Demand: "<?=$lang['REPORT_SERVICE']['WEEKLY_MAXIMUM_DEMAND'];?>",
					KWH:"<?=$lang['REPORT_SERVICE']['WEEKLY_TOTAL_ENERGY_CONSUMPTION'];?>"
				},
				MONTH:{
					Max_Demand: "<?=$lang['REPORT_SERVICE']['MONTHLY_MAXIMUM_DEMAND'];?>",
					KWH:"<?=$lang['REPORT_SERVICE']['MONTHLY_TOTAL_ENERGY_CONSUMPTION'];?>"
				},
				QUARTER:{
					Max_Demand: "<?=$lang['REPORT_SERVICE']['QUARTERLY_MAXIMUM_DEMAND'];?>",
					KWH:"<?=$lang['REPORT_SERVICE']['QUARTERLY_TOTAL_ENERGY_CONSUMPTION'];?>"
				},
				YEAR:{
					Max_Demand: "<?=$lang['REPORT_SERVICE']['ANNUAL_MAXIMUM_DEMAND'];?>",
					KWH:"<?=$lang['REPORT_SERVICE']['ANNUAL_TOTAL_ENERGY_CONSUMPTION'];?>"
				}
			};
			html_str += othertitle_array[$(".period-button.active").attr("period").toUpperCase()]["Max_Demand"] +": <b>" + $($(xmlData).find("info>Other")[0]).attr("Max_Demand") + "kW</b><br/>";
			html_str += "<?=$lang['REPORT_SERVICE']['EVEN_TIME'];?>" +": <b>" + $($(xmlData).find("info>Other")[0]).attr("Time") + "</b><br/>";
			html_str += othertitle_array[$(".period-button.active").attr("period").toUpperCase()]["KWH"] +": <b>" + $($(xmlData).find("info>Other")[0]).attr("total_kwh")+"<?=$lang['REPORT_SERVICE']['KWH'];?>"+"</b>";
			html_str += "</td>";
			html_str += "</tr>";
		}
		else{
			html_str += '<tr><td class="module-list-table-cell  header" colspan="6" align="center"><?=$lang["REPORT_SERVICE"]["SUMMARY"];?></td></tr>';
			html_str += "<tr>";
			html_str += "<td colspan='6' class='module-list-table-cell' style='line-height:20px;'>";
			if(!isGroup){
				var othertitle_array = {
					DAY: {
						Total: "<?=$lang['REPORT_SERVICE']['TODAY_TOTAL_VALUE'];?>",
						AVG:"<?=$lang['REPORT_SERVICE']['TODAY_AVERAGE'];?>",
						MAX:"<?=$lang['REPORT_SERVICE']['TODAY_MAXIMUM'];?>",
						MAX_TIME:"<?=$lang['REPORT_SERVICE']['TODAY_MAX_TIME_OCCURRENCE'];?>",
						MIN:"<?=$lang['REPORT_SERVICE']['TODAY_MINIMUM'];?>",
						MIN_TIME:"<?=$lang['REPORT_SERVICE']['TODAY_MIN_TIME_OCCURRENCE'];?>",
					},
					WEEK:{
						Total: "<?=$lang['REPORT_SERVICE']['THIS_WEEK_TOTAL_VALUE'];?>",
						AVG:"<?=$lang['REPORT_SERVICE']['THIS_WEEK_AVERAGE'];?>",
						MAX:"<?=$lang['REPORT_SERVICE']['THIS_WEEK_MAXIMUM'];?>",
						MAX_TIME:"<?=$lang['REPORT_SERVICE']['THIS_WEEK_MAX_TIME_OCCURRENCE'];?>",
						MIN:"<?=$lang['REPORT_SERVICE']['THIS_WEEK_MINIMUM'];?>",
						MIN_TIME:"<?=$lang['REPORT_SERVICE']['THIS_WEEK_MIN_TIME_OCCURRENCE'];?>",
					},
					MONTH:{
						Total: "<?=$lang['REPORT_SERVICE']['THIS_MONTH_TOTAL_VALUE'];?>",
						AVG:"<?=$lang['REPORT_SERVICE']['THIS_MONTH_AVERAGE'];?>",
						MAX:"<?=$lang['REPORT_SERVICE']['THIS_MONTH_MAXIMUM'];?>",
						MAX_TIME:"<?=$lang['REPORT_SERVICE']['THIS_MONTH_MAX_TIME_OCCURRENCE'];?>",
						MIN:"<?=$lang['REPORT_SERVICE']['THIS_MONTH_MINIMUM'];?>",
						MIN_TIME:"<?=$lang['REPORT_SERVICE']['THIS_MONTH_MIN_TIME_OCCURRENCE'];?>",
					},
					QUARTER:{
						Total: "<?=$lang['REPORT_SERVICE']['THIS_QUARTER_TOTAL_VALUE'];?>",
						AVG:"<?=$lang['REPORT_SERVICE']['THIS_QUARTER_AVERAGE'];?>",
						MAX:"<?=$lang['REPORT_SERVICE']['THIS_QUARTER_MAXIMUM'];?>",
						MAX_TIME:"<?=$lang['REPORT_SERVICE']['THIS_QUARTER_MAX_TIME_OCCURRENCE'];?>",
						MIN:"<?=$lang['REPORT_SERVICE']['THIS_QUARTER_MINIMUM'];?>",
						MIN_TIME:"<?=$lang['REPORT_SERVICE']['THIS_QUARTER_MIN_TIME_OCCURRENCE'];?>",
					},
					YEAR:{
						Total: "<?=$lang['REPORT_SERVICE']['THIS_YEAR_TOTAL_VALUE'];?>",
						AVG:"<?=$lang['REPORT_SERVICE']['THIS_YEAR_AVERAGE'];?>",
						MAX:"<?=$lang['REPORT_SERVICE']['THIS_YEAR_MAXIMUM'];?>",
						MAX_TIME:"<?=$lang['REPORT_SERVICE']['THIS_YEAR_MAX_TIME_OCCURRENCE'];?>",
						MIN:"<?=$lang['REPORT_SERVICE']['THIS_YEAR_MINIMUM'];?>",
						MIN_TIME:"<?=$lang['REPORT_SERVICE']['THIS_YEAR_MIN_TIME_OCCURRENCE'];?>",
					}
				};
				
				html_str += '<table id="report_title" style="border-collapse: collapse; width: 100%; font-weight: bold; font-size:15px;" border="0" data-mce-style="border-collapse: collapse; width: 100%" class="mce-item-table">';
				html_str += "<tr>";
				html_str += "<td style='width:33.3%'>";
				html_str += othertitle_array[$(".period-button.active").attr("period").toUpperCase()]["MAX"] +": <b>" + $($(xmlData).find("info>Other")[0]).attr("Max_Val") + " " + Unit + "</b><br/>";
				html_str += othertitle_array[$(".period-button.active").attr("period").toUpperCase()]["MAX_TIME"] +": <b>" + $($(xmlData).find("info>Other")[0]).attr("Max_Time") + "</b>";
				html_str += "</td>";
				html_str += "<td style='width:33.3%'>";
				html_str += othertitle_array[$(".period-button.active").attr("period").toUpperCase()]["MIN"] +": <b>" + $($(xmlData).find("info>Other")[0]).attr("Min_Val") + " " + Unit + "</b><br/>";
				html_str += othertitle_array[$(".period-button.active").attr("period").toUpperCase()]["MIN_TIME"] +": <b>" + $($(xmlData).find("info>Other")[0]).attr("Min_Time")+"</b>";
				html_str += "</td>";
				html_str += "<td style='width:33.3%'>";
				html_str += othertitle_array[$(".period-button.active").attr("period").toUpperCase()]["AVG"] +": <b>" + $($(xmlData).find("info>Other")[0]).attr("Avg_Val") + " " + Unit + "</b><br/>";
				html_str += othertitle_array[$(".period-button.active").attr("period").toUpperCase()]["Total"] +": <b>" + $($(xmlData).find("info>Other")[0]).attr("Total_Val") + " " + Unit + "</b>";
				html_str += "</td>";
				html_str += "</tr>";
			
				html_str += "</table>";
			}
			html_str += "</td>";
			html_str += "</tr>";
		}
	}
	return html_str;
}


function CreatePMGroupTable(){
	if($("#report_format :selected").val()=="1"){
		var html_str = "";
		html_str += "<tr>";

		if($(".period-button.active").attr("period").toUpperCase()=="DAY")
			html_str += "<td class='module-list-table-cell header' style='width: 10%;'><?=$lang['REPORT_SERVICE']['TIME'];?></td>";
		else if($(".period-button.active").attr("period").toUpperCase()=="MONTH"||$(".period-button.active").attr("period").toUpperCase()=="WEEK")
			html_str += "<td class='module-list-table-cell header' style='width: 10%;'><?=$lang['REPORT_SERVICE']['DATE'];?></td>";
		else
			html_str += "<td class='module-list-table-cell header' style='width: 10%;'><?=$lang['REPORT_SERVICE']['MONTH_TITLE'];?></td>";
		
		html_str += "<td class='module-list-table-cell header' style='width: 40%;'><?=$lang['REPORT_SERVICE']['MAX_DEMAND'];?></td>";
		html_str += "<td class='module-list-table-cell header' style='width: 40%;'><?=$lang['REPORT_SERVICE']['KWH_TITLE'];?></td>";
		html_str += "</tr>";
		for(var i=0;i<$(xmlData).find("info>Data").length;i++)
		{
			html_str += "<tr>";
				
				if($(".period-button.active").attr("period").toUpperCase()=="YEAR"||$(".period-button.active").attr("period").toUpperCase()=="QUARTER"){
					var header_month = "";
					switch($($(xmlData).find("info>Data")[i]).attr("Time")) {
						case "1":header_month = "<?=$lang['REPORT_SERVICE']['JAN'];?>";break;
						case "2":header_month = "<?=$lang['REPORT_SERVICE']['FEB'];?>";break;
						case "3":header_month = "<?=$lang['REPORT_SERVICE']['MAR'];?>";break;
						case "4":header_month = "<?=$lang['REPORT_SERVICE']['APR'];?>";break;
						case "5":header_month = "<?=$lang['REPORT_SERVICE']['MAY'];?>";break;
						case "6":header_month = "<?=$lang['REPORT_SERVICE']['JUN'];?>";break;
						case "7":header_month = "<?=$lang['REPORT_SERVICE']['JUL'];?>";break;
						case "8":header_month = "<?=$lang['REPORT_SERVICE']['AUG'];?>";break;
						case "9":header_month = "<?=$lang['REPORT_SERVICE']['SEP'];?>";break;
						case "10":header_month = "<?=$lang['REPORT_SERVICE']['OCT'];?>";break;
						case "11":header_month = "<?=$lang['REPORT_SERVICE']['NOV'];?>";break;
						case "12":header_month = "<?=$lang['REPORT_SERVICE']['DEC'];?>";break;
					}
					html_str += "<td class='module-list-table-cell'>" + header_month + "</td>";
				}
				else if($(".period-button.active").attr("period").toUpperCase()=="WEEK"){
					switch($($(xmlData).find("info>Data")[i]).attr("Time")) {
						case "1":html_str += "<td class='module-list-table-cell'><?=$lang['HISTORY_ENERGY']['SUN'];?></td>";break;
						case "2":html_str += "<td class='module-list-table-cell'><?=$lang['HISTORY_ENERGY']['MON'];?></td>";break;
						case "3":html_str += "<td class='module-list-table-cell'><?=$lang['HISTORY_ENERGY']['TUE'];?></td>";break;
						case "4":html_str += "<td class='module-list-table-cell'><?=$lang['HISTORY_ENERGY']['WED'];?></td>";break;
						case "5":html_str += "<td class='module-list-table-cell'><?=$lang['HISTORY_ENERGY']['THU'];?></td>";break;
						case "6":html_str += "<td class='module-list-table-cell'><?=$lang['HISTORY_ENERGY']['FRI'];?></td>";break;
						case "7":html_str += "<td class='module-list-table-cell'><?=$lang['HISTORY_ENERGY']['SAT'];?></td>";break;
					}
				}
				else 
					html_str += "<td class='module-list-table-cell'>" + $($(xmlData).find("info>Data")[i]).attr("Time") + "</td>";
			html_str += "<td class='module-list-table-cell'>" + $($(xmlData).find("info>Data")[i]).attr("Demand") + "</td>";
			html_str += "<td class='module-list-table-cell'>" + $($(xmlData).find("info>Data")[i]).attr("kWh") + "</td>";
			html_str += "</tr>";
		}
		
		html_str += '<tr><td class="module-list-table-cell header" colspan="3" align="center"><?=$lang["REPORT_SERVICE"]["SUMMARY"];?></td></tr>';
		html_str += "<tr>";
		html_str += "<td class='module-list-table-cell summary'>";
		switch ($(".period-button.active").attr("period").toUpperCase()) {
			case "DAY": html_str += "<?=$lang['REPORT_SERVICE']['DAILY_TOTAL_ENERGY_CONSUMPTION'];?>"; break;
			case "WEEK": html_str += "<?=$lang['REPORT_SERVICE']['WEEKLY_TOTAL_ENERGY_CONSUMPTION'];?>";	break;
			case "MONTH": html_str += "<?=$lang['REPORT_SERVICE']['MONTHLY_TOTAL_ENERGY_CONSUMPTION'];?>"; break;
			case "QUARTER": html_str += "<?=$lang['REPORT_SERVICE']['QUARTERLY_TOTAL_ENERGY_CONSUMPTION'];?>"; break;
			case "YEAR": html_str += "<?=$lang['REPORT_SERVICE']['ANNUAL_TOTAL_ENERGY_CONSUMPTION'];?>";	break;
		}
		html_str += "</td>";
		html_str += "<td class='module-list-table-cell summary' colspan='3'>" + $($(xmlData).find("info>Other")[0]).attr("total_kwh") + "</td>";
		html_str += "</tr>";
	}
	else if($("#report_format :selected").val()=="2"){
		$("#select_report_type_col_div").show();
		$("#select_report_type_col_div").prev().show();
		html_str ="";
		
		html_str += "<tr>";

		if($(".period-button.active").attr("period").toUpperCase()=="DAY")
			html_str += "<td class='module-list-table-cell header' style='width: 10%;'><?=$lang['REPORT_SERVICE']['TIME'];?></td>";
		else if($(".period-button.active").attr("period").toUpperCase()=="MONTH"||$(".period-button.active").attr("period").toUpperCase()=="WEEK")
			html_str += "<td class='module-list-table-cell header' style='width: 10%;'><?=$lang['REPORT_SERVICE']['DATE'];?></td>";
		else
			html_str += "<td class='module-list-table-cell header' style='width: 10%;'><?=$lang['REPORT_SERVICE']['MONTH_TITLE'];?></td>";
		for(var i=0; i<$(xmlData).find("info>ModuleInfo").length; i++){
			var report_title = "";
			$DeviceName = $($(xmlData).find("info>ModuleInfo")[i]).attr("DeviceName");
			$DeviceNickname = $($(xmlData).find("info>ModuleInfo")[i]).attr("DeviceNickname");
			$ModuleName = $($(xmlData).find("info>ModuleInfo")[i]).attr("ModuleName");
			$ModuleNickname = $($(xmlData).find("info>ModuleInfo")[i]).attr("ModuleNickname");
			$module_channel = $($(xmlData).find("info>ModuleInfo")[i]).attr("module_channel");
			$ChannelNickname = $($(xmlData).find("info>ModuleInfo")[i]).attr("ChannelNickname");
			
			if($DeviceNickname == "")
				report_title += $DeviceName;
			else
				report_title += $DeviceNickname;
			report_title += "<br/>";
			if($ModuleNickname == "")
				report_title += $ModuleName;
			else
				report_title += $ModuleNickname;
			report_title += "<br/>";
			report_title += "<?=$lang['REPORT_SERVICE']['LOOP'];?>"+ $module_channel;
			
			html_str += "<td class='module-list-table-cell header'>" + report_title;
			if($ChannelNickname != "")
			html_str += "(" + $ChannelNickname + ")";
			html_str += "</td>";
		}
		
		for(var i=0;i<$(xmlData).find("info>Data").length;i++)
		{
			html_str += "<tr>";
			if($(".period-button.active").attr("period").toUpperCase()=="YEAR"||$(".period-button.active").attr("period").toUpperCase()=="QUARTER"){
				var header_month = "";
				switch($($(xmlData).find("info>Data")[i]).attr("Time")) {
					case "1":header_month = "<?=$lang['REPORT_SERVICE']['JAN'];?>";break;
					case "2":header_month = "<?=$lang['REPORT_SERVICE']['FEB'];?>";break;
					case "3":header_month = "<?=$lang['REPORT_SERVICE']['MAR'];?>";break;
					case "4":header_month = "<?=$lang['REPORT_SERVICE']['APR'];?>";break;
					case "5":header_month = "<?=$lang['REPORT_SERVICE']['MAY'];?>";break;
					case "6":header_month = "<?=$lang['REPORT_SERVICE']['JUN'];?>";break;
					case "7":header_month = "<?=$lang['REPORT_SERVICE']['JUL'];?>";break;
					case "8":header_month = "<?=$lang['REPORT_SERVICE']['AUG'];?>";break;
					case "9":header_month = "<?=$lang['REPORT_SERVICE']['SEP'];?>";break;
					case "10":header_month = "<?=$lang['REPORT_SERVICE']['OCT'];?>";break;
					case "11":header_month = "<?=$lang['REPORT_SERVICE']['NOV'];?>";break;
					case "12":header_month = "<?=$lang['REPORT_SERVICE']['DEC'];?>";break;
				}
				html_str += "<td class='module-list-table-cell'>" + header_month + "</td>";
			}
			else if($(".period-button.active").attr("period").toUpperCase()=="WEEK"){
				switch($($(xmlData).find("info>Data")[i]).attr("Time")) {
					case "1":html_str += "<td class='module-list-table-cell'><?=$lang['HISTORY_ENERGY']['SUN'];?></td>";break;
					case "2":html_str += "<td class='module-list-table-cell'><?=$lang['HISTORY_ENERGY']['MON'];?></td>";break;
					case "3":html_str += "<td class='module-list-table-cell'><?=$lang['HISTORY_ENERGY']['TUE'];?></td>";break;
					case "4":html_str += "<td class='module-list-table-cell'><?=$lang['HISTORY_ENERGY']['WED'];?></td>";break;
					case "5":html_str += "<td class='module-list-table-cell'><?=$lang['HISTORY_ENERGY']['THU'];?></td>";break;
					case "6":html_str += "<td class='module-list-table-cell'><?=$lang['HISTORY_ENERGY']['FRI'];?></td>";break;
					case "7":html_str += "<td class='module-list-table-cell'><?=$lang['HISTORY_ENERGY']['SAT'];?></td>";break;
				}
			}
			else
				html_str += "<td class='module-list-table-cell'>" + $($(xmlData).find("info>Data")[i]).attr("Time") + "</td>";
			for(var col_idx=0; col_idx<$(xmlData).find("info>ModuleInfo").length; col_idx++){
				html_str += "<td class='module-list-table-cell'>" + $($(xmlData).find("info>Data")[i]).attr("Col"+col_idx+$("#select_report_type_col :selected").attr("col_type")) + "</td>";
			}
			html_str += "</tr>";
		}
		html_str += "<tr><td class='module-list-table-cell header' colspan='" + ($(xmlData).find("info>ModuleInfo").length+1) + "' align='center' ><?=$lang['REPORT_SERVICE']['SUMMARY'];?></td></tr>";
		html_str += "<tr>";
		html_str += "<td class='module-list-table-cell summary'>";
		switch ($(".period-button.active").attr("period").toUpperCase()) {
			case "DAY": html_str += "<?=$lang['REPORT_SERVICE']['DAILY_TOTAL_MODULE_ENERGY_CONSUMPTION'];?>"; break;
			case "WEEK": html_str += "<?=$lang['REPORT_SERVICE']['THIS_WEEK_MODULE_ENERGY_CONSUMPTION'];?>";	break;
			case "MONTH": html_str += "<?=$lang['REPORT_SERVICE']['THIS_MONTH_MODULE_ENERGY_CONSUMPTION'];?>"; break;
			case "QUARTER": html_str += "<?=$lang['REPORT_SERVICE']['THIS_QUARTER_MODULE_ENERGY_CONSUMPTION'];?>"; break;
			case "YEAR": html_str += "<?=$lang['REPORT_SERVICE']['THIS_YEAR_MODULE_ENERGY_CONSUMPTION'];?>";	break;
		}
		html_str += "</td>";
		
		for(var col_idx=0; col_idx<$(xmlData).find("info>ModuleInfo").length; col_idx++){
			html_str += "<td class='module-list-table-cell summary'>" + $($(xmlData).find("info>Other")[0]).attr("Col" + col_idx + "_val") + "</td>";
		}
		html_str += "</tr>";
		html_str += "<tr>";
		html_str += "<td class='module-list-table-cell summary'>";
		switch ($(".period-button.active").attr("period").toUpperCase()) {
			case "DAY": html_str += "<?=$lang['REPORT_SERVICE']['DAILY_TOTAL_ENERGY_CONSUMPTION'];?>"; break;
			case "WEEK": html_str += "<?=$lang['REPORT_SERVICE']['WEEKLY_TOTAL_ENERGY_CONSUMPTION'];?>";	break;
			case "MONTH": html_str += "<?=$lang['REPORT_SERVICE']['MONTHLY_TOTAL_ENERGY_CONSUMPTION'];?>"; break;
			case "QUARTER": html_str += "<?=$lang['REPORT_SERVICE']['QUARTERLY_TOTAL_ENERGY_CONSUMPTION'];?>"; break;
			case "YEAR": html_str += "<?=$lang['REPORT_SERVICE']['ANNUAL_TOTAL_ENERGY_CONSUMPTION'];?>";	break;
		}
		html_str += "</td>";
		html_str += "<td class='module-list-table-cell summary' colspan='" + $(xmlData).find("info>ModuleInfo").length + "'>" + $($(xmlData).find("info>Other")[0]).attr("total_kwh") + "</td>";
		html_str += "</tr>";
	}
	return html_str;
}

function CreateIOGroupTabel(){
	var html_str = "";
	html_str += "<tr>";

	if($(".period-button.active").attr("period").toUpperCase()=="DAY")
		html_str += "<td class='module-list-table-cell header' style='width: 10%;'><?=$lang['REPORT_SERVICE']['TIME'];?></td>";
	else if($(".period-button.active").attr("period").toUpperCase()=="MONTH"||$(".period-button.active").attr("period").toUpperCase()=="WEEK")
		html_str += "<td class='module-list-table-cell header' style='width: 10%;'><?=$lang['REPORT_SERVICE']['DATE'];?></td>";
	else
		html_str += "<td class='module-list-table-cell header' style='width: 10%;'><?=$lang['REPORT_SERVICE']['MONTH_TITLE'];?></td>";
	for(var i=0; i<$(xmlData).find("info>ModuleInfo").length; i++){
		var report_title = "";
		$DeviceName = $($(xmlData).find("info>ModuleInfo")[i]).attr("DeviceName");
		$DeviceNickname = $($(xmlData).find("info>ModuleInfo")[i]).attr("DeviceNickname");
		$ModuleName = $($(xmlData).find("info>ModuleInfo")[i]).attr("ModuleName");
		$ModuleNickname = $($(xmlData).find("info>ModuleInfo")[i]).attr("ModuleNickname");
		$module_channel = $($(xmlData).find("info>ModuleInfo")[i]).attr("module_channel");
		$ChannelNickname = $($(xmlData).find("info>ModuleInfo")[i]).attr("ChannelNickname");
		$Unit = $($(xmlData).find("info>ModuleInfo")[i]).attr("Unit");
		
		if($DeviceNickname == "")
				report_title += $DeviceName;
		else
			report_title += $DeviceNickname;
		report_title += "<br/>";
		if($ModuleNickname == ""){
			if($ModuleName == "IR")
				report_title +="<?=$lang['HISTORY_IO']['INTERNAL_REGISTER'];?>";
			else
				report_title += $ModuleName;
		}
		else
			report_title += $ModuleNickname;
		report_title += "<br/>";
		if($ChannelNickname == ""){
			var splitArray = $module_channel.match(/(\D+)(\d+)/);
			report_title += {
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
		}
		else
			report_title += $ChannelNickname;
		if($Unit != ""){
			report_title += "<br/>";
			report_title += "(" + $Unit + ")";
		}
		html_str += "<td class='module-list-table-cell header'>" + report_title + "</td>";
	}
	for(var i=0;i<$(xmlData).find("info>Data").length;i++)
	{
		html_str += "<tr>";
		if($(".period-button.active").attr("period").toUpperCase()=="YEAR"||$(".period-button.active").attr("period").toUpperCase()=="QUARTER"){
			var header_month = "";
			switch($($(xmlData).find("info>Data")[i]).attr("Time")) {
				case "1":header_month = "<?=$lang['REPORT_SERVICE']['JAN'];?>";break;
				case "2":header_month = "<?=$lang['REPORT_SERVICE']['FEB'];?>";break;
				case "3":header_month = "<?=$lang['REPORT_SERVICE']['MAR'];?>";break;
				case "4":header_month = "<?=$lang['REPORT_SERVICE']['APR'];?>";break;
				case "5":header_month = "<?=$lang['REPORT_SERVICE']['MAY'];?>";break;
				case "6":header_month = "<?=$lang['REPORT_SERVICE']['JUN'];?>";break;
				case "7":header_month = "<?=$lang['REPORT_SERVICE']['JUL'];?>";break;
				case "8":header_month = "<?=$lang['REPORT_SERVICE']['AUG'];?>";break;
				case "9":header_month = "<?=$lang['REPORT_SERVICE']['SEP'];?>";break;
				case "10":header_month = "<?=$lang['REPORT_SERVICE']['OCT'];?>";break;
				case "11":header_month = "<?=$lang['REPORT_SERVICE']['NOV'];?>";break;
				case "12":header_month = "<?=$lang['REPORT_SERVICE']['DEC'];?>";break;
			}
			html_str += "<td class='module-list-table-cell'>" + header_month + "</td>";
		}
		else if($(".period-button.active").attr("period").toUpperCase()=="WEEK"){
			switch($($(xmlData).find("info>Data")[i]).attr("Time")) {
				case "1":html_str += "<td class='module-list-table-cell'><?=$lang['HISTORY_ENERGY']['SUN'];?></td>";break;
				case "2":html_str += "<td class='module-list-table-cell'><?=$lang['HISTORY_ENERGY']['MON'];?></td>";break;
				case "3":html_str += "<td class='module-list-table-cell'><?=$lang['HISTORY_ENERGY']['TUE'];?></td>";break;
				case "4":html_str += "<td class='module-list-table-cell'><?=$lang['HISTORY_ENERGY']['WED'];?></td>";break;
				case "5":html_str += "<td class='module-list-table-cell'><?=$lang['HISTORY_ENERGY']['THU'];?></td>";break;
				case "6":html_str += "<td class='module-list-table-cell'><?=$lang['HISTORY_ENERGY']['FRI'];?></td>";break;
				case "7":html_str += "<td class='module-list-table-cell'><?=$lang['HISTORY_ENERGY']['SAT'];?></td>";break;
			}
		}
		else
			html_str += "<td class='module-list-table-cell'>" + $($(xmlData).find("info>Data")[i]).attr("Time") + "</td>";
		for(var col_idx=0; col_idx<$(xmlData).find("info>ModuleInfo").length; col_idx++){
			html_str += "<td class='module-list-table-cell'>" + $($(xmlData).find("info>Data")[i]).attr("Col"+col_idx+$("#select_report_type_col :selected").attr("col_type")) + "</td>";
		}
		html_str += "</tr>";
	}
	
	html_str += "<tr><td class='module-list-table-cell header' colspan=" + ($(xmlData).find("info>ModuleInfo").length+1) + " align='center'><?=$lang['REPORT_SERVICE']['SUMMARY'];?></td></tr>";
	
	var col_name = ["max_val","min_val","avg_val","sum_val"];
	var col_title = ["TODAY_MAXIMUM","TODAY_MINIMUM","TODAY_AVERAGE","TODAY_TOTAL_VALUE"];
	
	if($(".period-button.active").attr("period").toUpperCase()=="DAY")
		col_title = ["<?=$lang['REPORT_SERVICE']['TODAY_MAXIMUM'];?>",
			"<?=$lang['REPORT_SERVICE']['TODAY_MINIMUM'];?>",
			"<?=$lang['REPORT_SERVICE']['TODAY_AVERAGE'];?>",
			"<?=$lang['REPORT_SERVICE']['TODAY_TOTAL_VALUE'];?>"
		];
	else if($(".period-button.active").attr("period").toUpperCase()=="WEEK")
		col_title = [
			"<?=$lang['REPORT_SERVICE']['THIS_WEEK_MAXIMUM'];?>",
			"<?=$lang['REPORT_SERVICE']['THIS_WEEK_MINIMUM'];?>",
			"<?=$lang['REPORT_SERVICE']['THIS_WEEK_AVERAGE'];?>",
			"<?=$lang['REPORT_SERVICE']['THIS_WEEK_TOTAL_VALUE'];?>"
		];
	else if($(".period-button.active").attr("period").toUpperCase()=="MONTH")
		col_title = [
			"<?=$lang['REPORT_SERVICE']['THIS_MONTH_MAXIMUM'];?>",
			"<?=$lang['REPORT_SERVICE']['THIS_MONTH_MINIMUM'];?>",
			"<?=$lang['REPORT_SERVICE']['THIS_MONTH_AVERAGE'];?>",
			"<?=$lang['REPORT_SERVICE']['THIS_MONTH_TOTAL_VALUE'];?>"];
	else if($(".period-button.active").attr("period").toUpperCase()=="QUARTER")
		col_title = [
			"<?=$lang['REPORT_SERVICE']['THIS_QUARTER_MAXIMUM'];?>",
			"<?=$lang['REPORT_SERVICE']['THIS_QUARTER_MINIMUM'];?>",
			"<?=$lang['REPORT_SERVICE']['THIS_QUARTER_AVERAGE'];?>",
			"<?=$lang['REPORT_SERVICE']['THIS_QUARTER_TOTAL_VALUE'];?>"
		];
	else if($(".period-button.active").attr("period").toUpperCase()=="YEAR")
		col_title = [
			"<?=$lang['REPORT_SERVICE']['THIS_YEAR_MAXIMUM'];?>",
			"<?=$lang['REPORT_SERVICE']['THIS_YEAR_MINIMUM'];?>",
			"<?=$lang['REPORT_SERVICE']['THIS_YEAR_AVERAGE'];?>",
			"<?=$lang['REPORT_SERVICE']['THIS_YEAR_TOTAL_VALUE'];?>"
		];
	
	for(var col_idx=0; col_idx<col_name.length; col_idx++){
		html_str += "<tr>";
		html_str += "<td class='module-list-table-cell' style='font-weight: bold; font-size: 15px;'>" + col_title[col_idx]+"</td>";
		
		for(var module_idx=0; module_idx<$(xmlData).find("info>Other").length; module_idx++)
			html_str += "<td class='module-list-table-cell' style='font-weight: bold; font-size: 15px;'>" + $($(xmlData).find("info>Other")[module_idx]).attr(col_name[col_idx]) + "</td>";
		html_str += "</tr>";
	}
	return html_str;
}

function export_notification(template_id){
	if(isGroup != true){
		var template_accountUID = '<?=$_SESSION["account_uid"]?>';
		var accountUID = $("#select-device div.select-list div.select-option.active").attr("account_uid");
		var DeviceUID = $("#select-device div.select-list div.select-option.active").attr("device_uid");
		var moduleUID = $("#select-module div.select-list div.select-option.active").attr("module_uid");
		var datetime_begin =  $(".time-button-container").attr("begin");
		var datetime_end =  $(".time-button-container").attr("end");
		var report_type = $(".period-button.active").attr("period").toUpperCase();
		var sub_title = $("div.sub-title").text();
		var compare_time_format = $("#compare_time_format")[0].selectedIndex;
		var compare_datetime_begin = $("#time-compare").attr("begin");
		var compare_datetime_end = $("#time-compare").attr("end");
		
		var ck_array = [];
		var col_count = 0;
		for(var i=0; i<$(".field-button-select input").length; i++){
			ck_array.push($($(".field-button-select input")[i]).prop("checked"));
			if($($(".field-button-select input")[i]).prop("checked")){
				col_count++;
			}
			if($("#compare_time_format")[0].selectedIndex==1){
				ck_array.push($($(".field-button-select input")[i]).prop("checked"));
				if($($(".field-button-select input")[i]).prop("checked")){
					col_count++;
				}
			}
		}
		
		if($("#select-channel").attr("type") == "1"){
			var loop = $("#select-channel div.select-list div.select-option.active").attr("_loop");
			var filelink = "";
			filelink += "energy_report_export.php?template_id=" + template_id;
			filelink += "&template_accountUID=" + template_accountUID;
			filelink += "&timezone=" + encodeURIComponent(getTimeZone());
			filelink += "&accountUID=" + accountUID;
			filelink += "&DeviceUID=" + DeviceUID;
			filelink += "&moduleUID=" + moduleUID;
			filelink += "&loop=" + loop;
			filelink += "&datetime_begin=" + datetime_begin;
			filelink += "&datetime_end=" + datetime_end;
			filelink += "&report_type=" + report_type;
			filelink += "&sub_title=" + sub_title;
			filelink += "&isGroup=" + isGroup;
			filelink += "&compare_time_format=" + compare_time_format;
			filelink += "&compare_datetime_begin=" + compare_datetime_begin;
			filelink += "&compare_datetime_end=" + compare_datetime_end;
			filelink += "&ck_array=" + ck_array;
			filelink += "&col_count=" + col_count;
			
			location.href = filelink;
		}
		else{
			var channel = $("#select-channel div.select-list div.select-option.active").attr("channel");
			var filelink = "";
			filelink += "io_report_export.php?template_id=" + template_id;
			filelink += "&template_accountUID=" + template_accountUID;
			filelink += "&timezone=" + encodeURIComponent(getTimeZone());
			filelink += "&accountUID="+accountUID;
			filelink += "&DeviceUID="+DeviceUID;
			filelink += "&moduleUID="+moduleUID;
			filelink += "&channel="+channel;
			filelink += "&datetime_begin="+datetime_begin;
			filelink += "&datetime_end="+datetime_end;
			filelink += "&report_type="+report_type;
			filelink += "&sub_title="+sub_title;
			filelink += "&isGroup="+isGroup;
			filelink += "&compare_time_format=" + compare_time_format;
			filelink += "&compare_datetime_begin=" + compare_datetime_begin;
			filelink += "&compare_datetime_end=" + compare_datetime_end;
			filelink += "&ck_array=" + ck_array;
			filelink += "&col_count=" + col_count;
			location.href = filelink;
		}
	}
	else{
		var template_accountUID = '<?=$_SESSION["account_uid"]?>';
		var GroupUID = $("#select-device .select-wrapper .select-list .select-option.active").attr("group_info_uid");
		var datetime_begin =  $(".time-button-container").attr("begin");
		var datetime_end =  $(".time-button-container").attr("end");
		var report_type = $(".period-button.active").attr("period").toUpperCase();
		var sub_title = $("div.sub-title").text();
		
		if($("#select-device .select-wrapper .select-list .select-option.active").attr("type") == "1"){
			if($("#report_format :selected").val() == "1"){//格式一
				location.href = "energy_report_export.php?template_accountUID=" + template_accountUID + "&template_id=" + template_id + "&timezone=" + encodeURIComponent(getTimeZone()) + "&GroupUID="+GroupUID+"&datetime_begin="+datetime_begin+"&datetime_end="+datetime_end+"&report_type="+report_type+"&sub_title="+sub_title+"&isGroup="+isGroup+"&format="+$("#report_format :selected").val()+"";
			}
			else{//格式二
				var col_type = $("#select_report_type_col :selected").attr("col_type");
				var col_text = $("#select_report_type_col :selected").text();
				location.href="energy_report_export.php?template_accountUID=" + template_accountUID + "&template_id=" + template_id + "&timezone=" + encodeURIComponent(getTimeZone()) + "&GroupUID="+GroupUID+"&datetime_begin="+datetime_begin+"&datetime_end="+datetime_end+"&report_type="+report_type+"&sub_title="+sub_title+"&isGroup="+isGroup+"&format="+$("#report_format :selected").val()+"&col_type="+col_type+"&col_text="+col_text+"";
			}
		}
		else{//IO
			var col_type = $("#select_report_type_col :selected").attr("col_type");
			var col_text = $("#select_report_type_col :selected").text();
			location.href = "io_report_export.php?template_accountUID=" + template_accountUID + "&template_id=" + template_id + "&timezone=" + encodeURIComponent(getTimeZone()) + "&GroupUID="+GroupUID+"&datetime_begin="+datetime_begin+"&datetime_end="+datetime_end+"&report_type="+report_type+"&sub_title="+sub_title+"&isGroup="+isGroup+"&col_type="+col_type+"&col_text="+col_text+"";
		}
	}
}

function getTimeZone() {
	var offset = new Date().getTimezoneOffset(), o = Math.abs(offset);
	return (offset < 0 ? "+" : "-") + ("00" + Math.floor(o / 60)).slice(-2) + ":" + ("00" + (o % 60)).slice(-2);
}

function showWindow($popup, callback){
	var $popup = $popup.detach().appendTo("#popup-window-content");
	$("#popup-window-content, #popup-window-background").show();

	$popup.css({"marginTop": "", "marginLeft": ""});

	// Margin top & left
	var marginTop = $(window).height() / 2 - $popup.outerHeight() / 2;
	$popup.css("marginTop", ((marginTop < 0 ? $(window).scrollTop() : marginTop + $(window).scrollTop()) - $popup.offset().top) + "px");

	var marginLeft = $(window).width() / 2 - $popup.outerWidth() / 2;
	$popup.css("marginLeft", ((marginLeft < 0 ? $(window).scrollLeft() : marginLeft + $(window).scrollLeft()) - $popup.offset().left) + "px");

	// Margin bottom
	var maxHeight = 0;
	$("#popup-window-content > *").each(function(){
		var height = $(this).offset().top + $(this).outerHeight();
		if(height > maxHeight){
			maxHeight = height;
		}
	});

	var height = $popup.offset().top + $popup.outerHeight();
	if(height < maxHeight){
		$popup.css("marginBottom", (maxHeight - height) + "px");
	}

	if(typeof(windowCallback) == "undefined"){
		windowCallback = [];
	}

	if(typeof(callback) == "function"){
		windowCallback.push(callback);
	}
}

function hideWindow(){
	$("#popup-window-content > *:last").detach().appendTo("#popup-container");

	if($("#popup-window-content > *").length <= 0){
		$("#popup-window-content, #popup-window-background").hide();
	}
}

var Template_obj = [];
function onClickManagementTemplate(){
	last_template = 0;
	Template_obj = [];
	$("#wait-loader").show();
	LoadTemplateList(Template_obj);
	$("#wait-loader").hide();
	
	
	showWindow($("#window-management_template"),function(result){
		if(result == "ok"){
			if(Template_obj.length != 0){
				Template_obj[$("#select_report_template").prop('selectedIndex')]["header"] = tinymce.get("edit_header").getContent();
				Template_obj[$("#select_report_template").prop('selectedIndex')]["footer"] = tinymce.get("edit_footer").getContent();
			}
			
			var accountUID = $("#select-device div.select-list div.select-option.active").attr("account_uid");
			$.ajax({
				url: "report_ajax.php?act=InsertTemplateList",
				type: "POST",
				data: {
					Template_obj: Template_obj
				},
				dataType: "xml",
				
				success: function(data, textStatus, jqXHR){
					
				},
				error: function(jqXHR, textStatus, errorThrown){
					if(jqXHR.status === 0 && textStatus != "timeout"){ return; }

					alert(jqXHR.responseText);
				},
				complete: function(){
					
				}
			});			
		}
		else if(result == "cancel"){
			
		}
		tinymce.remove();
		hideWindow();
	});
	
	/*
	try{
		tinymce.remove();
	}
	catch(e){}
	*/
	tiny_init_edit_header();
	tiny_init_edit_footer();
	
}

function TemplateToolsState(){
	if(Template_obj.length == 0){
		$("#select_report_template").hide();
		$("#edittemplate").hide();
		$("#copytemplate").hide();
	    $("#deltemplate").hide();
		$("#notemplate").show();
	}
	else{
		$("#select_report_template").show();
		$("#edittemplate").show();
		$("#copytemplate").show();
	    $("#deltemplate").show();
		$("#notemplate").hide();
	}
	
}


function onClickEditReport(){
	tiny_init();
	showWindow($("#window-report_edit"),function(result){
		if(result == "ok"){
			
		}
		else if(result == "cancel"){
			tinymce.remove();
			hideWindow();
		}
		
	});
	tiny_init($("#module_list_table").html());
}

function PreviewPDF(){
	$("#wait-loader").show();
	$("#PDF_download_div").html(tinyMCE.get("edit_report").getContent());
	$("#PDF_download_div").css("display","grid");

	var target = document.getElementById('PDF_download_div');
	var margin = $("#broadbrimmed").val();
	html2canvas(target).then(function(canvas){
		var a4Width = 595.28 - (margin * 2);
		var a4Height = 841.89 - (margin * 2);
		var pdf = new jsPDF('', 'pt', 'a4');
		if($("#select_report_Overturn").val()==0){
			a4Width = 595.28 - (margin * 2);
			a4Height = 841.89 - (margin * 2);
			pdf = new jsPDF('', 'pt', 'a4');
		}
		else{
			a4Width = 841.89 - (margin * 2);
			a4Height = 595.28 - (margin * 2);
			pdf = new jsPDF('l', 'pt', 'a4');
		}
		
		var contentWidth = canvas.width;
		var contentHeight = canvas.height;
		var pageHeight;
	
		if(contentWidth > a4Width){
			pageHeight = (contentWidth / a4Width) * a4Height;
		}
		else{
			pageHeight = a4Height;
		}
	
		var page = Math.ceil(contentHeight / pageHeight);
		var ctx = canvas.getContext('2d');
		
		var divContents = tinymce.get("edit_report").getContent();
		
		$("#div_perview").html("");
		for(var i = 0; i < page; i++){
			var canvas2 = document.createElement('canvas');
		    canvas2.width = contentWidth;
		    canvas2.height = pageHeight;
	
			var imageData = ctx.getImageData(0, i * pageHeight, contentWidth, pageHeight);
	
		    var ctx2 = canvas2.getContext('2d');
			ctx2.putImageData(imageData, 0, 0);
			imageData = canvas2.toDataURL('image/png', 1.0);
			$("#div_perview").append(
				$("<div></div>").css({"border": "1px solid", "background": "white","padding":""+margin+"pt"}).append(
					"<img src=" + imageData + " style='width: 100%;'>"
				)
			);
		}
		$("#wait-loader").hide();
	});
	$("#PDF_download_div").css("display","none");
}

var last_template = 0;
function SelectTemplate(){
	Template_obj[last_template].header = tinymce.get("edit_header").getContent();
	Template_obj[last_template].footer = tinymce.get("edit_footer").getContent();
	tinymce.get("edit_header").setContent(Template_obj[$("#select_report_template").prop('selectedIndex')]["header"]);
	tinymce.get("edit_footer").setContent(Template_obj[$("#select_report_template").prop('selectedIndex')]["footer"]);
	
	last_template = $("#select_report_template").prop('selectedIndex');
}

function onClickAddTemplate(){
	$("#TemplateName").val("");
	popupWindow2($("#window-TemplateManagement"),function(result){
		if(result == "ok"){
			if($("#TemplateName").val().length > 50){
				$("#window-TemplateManagement_wrapper").show();
				return;
			}
			if(Template_obj.length>0){
				Template_obj[$("#select_report_template").prop('selectedIndex')]["header"] = tinymce.get("edit_header").getContent();
				Template_obj[$("#select_report_template").prop('selectedIndex')]["footer"] = tinymce.get("edit_footer").getContent();
			}
			tinymce.get("edit_header").setContent("");
			tinymce.get("edit_footer").setContent("");
			
			Template_obj.push({name: $("#TemplateName").val(), header: "", footer: ""});
			$("#select_report_template").append($("<option></option>").text($("#TemplateName").val()));
			$("#select_report_template").prop('selectedIndex',Template_obj.length - 1);
			
			last_template = $("#select_report_template").prop('selectedIndex');
			$("#select_report_template").trigger('change');	
		}
		else if(result == "cancel"){
			
		}
		$("#window-TemplateManagement").hide();
		TemplateToolsState();
	});
}

function onClickEditTemplate(){
	$("#TemplateName").val($("#select_report_template").val());
	popupWindow2($("#window-TemplateManagement"),function(result){
		if(result == "ok"){
			if($("#TemplateName").val().length > 50){
				$("#window-TemplateManagement_wrapper").show();
				return;
			}
			Template_obj[$("#select_report_template").prop('selectedIndex')]["name"] = $("#TemplateName").val();
			$("#select_report_template :selected").text($("#TemplateName").val());
		}
		else if(result == "cancel"){
			
		}
		$("#window-TemplateManagement").hide();
	});
}

function popupOK(){
	$("#window-TemplateManagement_wrapper").hide();
}

function onClickCopyTemplate(){
	$("#wait-loader").show();
	setTimeout(function(){$("#wait-loader").hide();}, 500);
	
	Template_obj[$("#select_report_template").prop('selectedIndex')]["header"] = tinymce.get("edit_header").getContent();
	Template_obj[$("#select_report_template").prop('selectedIndex')]["footer"] = tinymce.get("edit_footer").getContent();
	Template_obj.push({name: Template_obj[$("#select_report_template").prop('selectedIndex')]["name"] + " - copy", header: tinymce.get("edit_header").getContent(), footer: tinymce.get("edit_footer").getContent()});
	
	
	$("#select_report_template").append($("<option></option>").text(Template_obj[$("#select_report_template").prop('selectedIndex')]["name"] + " - copy"));
	$("#select_report_template").prop('selectedIndex',Template_obj.length - 1);
	$("#select_report_template").trigger('change');	
	
}

function onClickRemoveTemplate(){
	popupWindow2($("#window-TemplateManagement_checkDel"),function(result){
		if(result == "ok"){
			Template_obj.splice($("#select_report_template").prop('selectedIndex'),1);
			$($("#select_report_template :selected")[0]).remove();
			last_template = 0 ;
			$("#select_report_template").prop('selectedIndex',0);
			if(Template_obj.length != 0){
				tinymce.get("edit_header").setContent(Template_obj[$("#select_report_template").prop('selectedIndex')]["header"]);
				tinymce.get("edit_footer").setContent(Template_obj[$("#select_report_template").prop('selectedIndex')]["footer"]);
				$("#select_report_template").trigger('change');	
			}
			TemplateToolsState();
		}
		else if(result == "cancel"){
			
		}
		$("#window-TemplateManagement_checkDel").hide();
	});
}

function DownloadPDF(){
	
	$("#select_report_Overturn").html("");
	$("#select_report_Overturn").append(
		$("<option><?=$lang['REPORT_SERVICE']['PORTRAIT'];?></option>").val("0")
	);
	$("#select_report_Overturn").append(
		$("<option><?=$lang['REPORT_SERVICE']['LANDSCAPE'];?></option>").val("1")
	);
	$("#broadbrimmed").val("10");
	$("#div_perview").html("");
	PreviewPDF();
	
	popupWindow2($("#window-SelectOverturn"),function(result){
		if(result == "ok"){
			var sub_title = $("div.sub-title").text();
			var datetime_begin =  $(".time-button-container").attr("begin");
			switch($(".period-button.active").attr("period").toUpperCase()){
				case "DAY":
					report_type = "<?=$lang['REPORT_SERVICE']['DAILY_REPORT'];?>";
					break;
				case "WEEK":
					report_type = "<?=$lang['REPORT_SERVICE']['WEEKLY_REPORT'];?>";
					break;
				case "MONTH":
					report_type = "<?=$lang['REPORT_SERVICE']['MONTHLY_REPORT'];?>";
					break;
				case "QUARTER":
					report_type = "<?=$lang['REPORT_SERVICE']['QUARTERLY_REPORT'];?>";
					break;
				case "YEAR":
					report_type = "<?=$lang['REPORT_SERVICE']['ANNUAL_REPORT'];?>";
					break;
			}
			
			var filename = "";
			
			if(!isGroup){
				if(!($("#compare_time_format")[0].selectedIndex == 1))
					filename = sub_title.replaceAll(" / ","_") +"_" +datetime_begin.split(" ")[0]+"_"+report_type;
				else
					filename = sub_title.replaceAll(" / ","_") +"_" +datetime_begin.split(" ")[0]+"_" + report_type + "_com";
			}
			else{
				if($("#select-device .select-wrapper .select-list .select-option.active").attr("type") == "1"){//PM
					filename = sub_title.replaceAll(" / ","_") +"_" +datetime_begin.split(" ")[0]+"_"+report_type + "_"+ $("#report_format :selected").text() + "_" + $("#select_report_type_col :selected").text();
				}
				else{//IO
					filename = sub_title.replaceAll(" / ","_") +"_" +datetime_begin.split(" ")[0]+"_"+report_type + "_" + $("#select_report_type_col :selected").text();
				}
			}
			
			$("#PDF_download_div").html(tinyMCE.get("edit_report").getContent());
			$("#PDF_download_div").css("display","grid");
			download(document.getElementById('PDF_download_div'),filename, $("#broadbrimmed").val()*1);
			$("#PDF_download_div").css("display","none");
		}
		else if(result == "cancel"){
			
		}
		$("#window-SelectOverturn").hide();
	});
}
var jsPDF = window.jspdf.jsPDF;

function download(target, filename, margin){
	html2canvas(target).then(function(canvas){
		var a4Width = 595.28 - (margin * 2);
		var a4Height = 841.89 - (margin * 2);
		var pdf = new jsPDF('', 'pt', 'a4');
		if($("#select_report_Overturn").val()==0){
			a4Width = 595.28 - (margin * 2);
			a4Height = 841.89 - (margin * 2);
			pdf = new jsPDF('', 'pt', 'a4');
		}
		else{
			a4Width = 841.89 - (margin * 2);
			a4Height = 595.28 - (margin * 2);
			pdf = new jsPDF('l', 'pt', 'a4');
		}
		
		var contentWidth = canvas.width;
		var contentHeight = canvas.height;
		var pageHeight;

		if(contentWidth > a4Width){
			pageHeight = (contentWidth / a4Width) * a4Height;
		}
		else{
			pageHeight = a4Height;
		}

		var page = Math.ceil(contentHeight / pageHeight);
		var ctx = canvas.getContext('2d');
		
		for(var i = 0; i < page; i++){
			var canvas2 = document.createElement('canvas');
		    canvas2.width = contentWidth;
		    canvas2.height = pageHeight;

			var imageData = ctx.getImageData(0, i * pageHeight, contentWidth, pageHeight);

		    var ctx2 = canvas2.getContext('2d');
			ctx2.putImageData(imageData, 0, 0);
			imageData = canvas2.toDataURL('image/png', 1.0);
			
			pdf.addImage(imageData, 'PNG', margin, margin, contentWidth > a4Width ? a4Width : contentWidth, a4Height);
			
			if(i + 1 < page){
				pdf.addPage();
			}
		}
		pdf.save(filename + ".pdf");
	});
}

function popupWindow2($popupWindow2, callback){
	var $wrapper = $popupWindow2.show().find(".popup-wrapper");
	windowCallback2 = callback;
}

function LoadTemplateList(Template_obj){
	var accountUID = '<?=$_SESSION["account_uid"]?>';
	
	$.ajax({
		url: "report_ajax.php?act=LoadTemplateList",
		type: "POST",
		data: "accountUID=" + accountUID,
		dataType: "xml",
		async: false,
		success: function(data, textStatus, jqXHR){
			$("#select_report_template").html("");
			for(var i=0; i<$(data).find("info>TemplateList").length; i++){
				Template_obj.push({
					id:$($(data).find("info>TemplateList")[i]).attr("id"),
					name:$($(data).find("info>TemplateList")[i]).attr("name"),
					header:$($(data).find("info>TemplateList")[i]).attr("header"),
					footer:$($(data).find("info>TemplateList")[i]).attr("footer")
				});
				$("#select_report_template").append($("<option></option>").text($($(data).find("info>TemplateList")[i]).attr("name")));
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

			alert(jqXHR.responseText);
		},
		complete: function(){
			TemplateToolsState();
		}
	});
}

function LoadTemplateContent(){
	if($("#select_report_style").val()=="default"){
		$("#bt_del_template").hide();
		$("#bt_edit_sample").hide();
		var report_html = CreateTableTitle() + "<table id='report_content' style='width:100%'>" + $("#module_list_table").html() + "</table>";
		tinymce.activeEditor.setContent('');
		tinymce.activeEditor.setContent("<p></p>" + report_html + "<p></p>");
	}
	else{
		$("#bt_del_template").show();
		$("#bt_edit_sample").show();
		var accountUID = $("#select-device div.select-list div.select-option.active").attr("account_uid");
		$.ajax({
			url: "report_ajax.php?act=LoadTemplateContent",
			type: "POST",
			data: "accountUID=" + accountUID + "&sampleUID=" + $("#select_report_style").val(),
			dataType: "xml",
			success: function(data, textStatus, jqXHR){
				
				var report_html = stringToHTML($($(data).find("info>SampleContent")[0]).text());
				
				report_html.querySelector("#report_replace").outerHTML = CreateTableTitle() + "<table id='report_content' style='width:100%'>" + $("#module_list_table").html() + "</table>";
				tinymce.activeEditor.setContent('');
				tinymce.activeEditor.setContent(report_html.innerHTML.toString());
			},
			error: function(jqXHR, textStatus, errorThrown){
				if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

				alert(jqXHR.responseText);
			},
			complete: function(){
				
			}
		});
	}
}

function tiny_init(table_html){
	try{
		Template_obj = [];
		LoadTemplateList(Template_obj);
		tinymce.remove();
	}
	catch(e){}	
	
	
	var template_tool = [];
	
	/*if(Template_obj.length == 0){
		template_tool.push({
			type: 'menuitem',
			text: "<?=$lang['REPORT_SERVICE']['NO_TEMPLATE'];?>",
			onAction: function () {}
		});
	}*/
	
	template_tool.push({
		type: 'menuitem',
		text: "<?=$lang['REPORT_SERVICE']['NO_TEMPLATE'];?>",
		onAction: function () {
			var str_html = 
				"<p></p>" +
				$(tinyMCE.get("edit_report").contentDocument.getElementById("report_title")).prop("outerHTML") + 
				$(tinyMCE.get("edit_report").contentDocument.getElementById("report_content")).prop("outerHTML") +
				"<p></p>";
				
			tinymce.activeEditor.setContent('');
			tinymce.activeEditor.setContent(str_html);
		}
	});
	
	$.each(Template_obj, function(index,value) {
		template_tool.push({
			type: 'menuitem',
			text: value["name"],
			onAction: function () {
				var str_html = 
					value["header"] + 
					$(tinyMCE.get("edit_report").contentDocument.getElementById("report_title")).prop("outerHTML") + 
					$(tinyMCE.get("edit_report").contentDocument.getElementById("report_content")).prop("outerHTML") + 
					value["footer"];
				tinymce.activeEditor.setContent('');
				tinymce.activeEditor.setContent(str_html);
			}
		});
	});
	
	
	tinymce.init(
		{
			selector: 'div#edit_report',
			skin:"mytinymceskin",
			content_css:['../css/report.css','../js/TinyMCE/skins/ui/mytinymceskin/content.min.css','../css/general.css'],
			width : "1800px",
			height : "700px",
			menubar: false,
			table_toolbar: '',
			elementpath: false,
			resize: false,
			language: (function(){
				var c_lang = "<?php echo $_COOKIE[language]; ?>"; 
				if(c_lang == "tw")
					return "zh_TW";
				else if(c_lang=="cn")
					return "zh_CN";
				else
					return null;
			})(),
			
			
			
			//font style
			content_style: 'body { background-color:' + $("body").css("backgroundColor") + '; color:' + $("#dashboard-container").css("color") + '} ',
			
			fontsize_formats: '11px 13px 15px 17px 19px 20px 24px 36px 48px',
			font_formats: (function(){
				var c_lang = "<?php echo $_COOKIE[language]; ?>"; 
				if(c_lang == "tw")
					return "微軟正黑體=微軟正黑體,Microsoft JhengHei;新細明體=PMingLiU,新細明體;標楷體=標楷體,DFKai-SB,BiauKai;黑體=黑體,SimHei,Heiti TC; Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats";
				else if(c_lang=="cn")
					return "微软雅黑='微软雅黑';宋体='宋体';黑体='黑体';仿宋='仿宋';楷体='楷体';隶书='隶书';幼圆='幼圆'; Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats";
				else
					return "Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats";
			})(),
			
			//plugins
			plugins: ['advlist autolink lists link image code fullscreen table '],
			toolbar1: ' selecttemplate myCustomToolbarButton',
			toolbar2: 'fontselect | fontsizeselect | forecolor backcolor | bold italic underline strikethrough ',
			toolbar3: 'image table | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | fullscreen code ',
			
			toolbar_mode: 'sliding',
			
			
			//stup tools
			
			setup: function (editor) {
				editor.ui.registry.addMenuButton('selecttemplate', {
					text: "<?=$lang['REPORT_SERVICE']['APPLY_TEMPLATE'];?>",
					fetch: function (callback) {
						callback(template_tool);
					}
				});
				/*
				editor.ui.registry.addButton('myCustomToolbarButton', {
					text: "<?=$lang['REPORT_SERVICE']['CLEAR_TEMPLATE'];?>",
					onAction: function () {
						var str_html = 
						"<p></p>" +
						$(tinyMCE.get("edit_report").contentDocument.getElementById("report_title")).prop("outerHTML") + 
						$(tinyMCE.get("edit_report").contentDocument.getElementById("report_content")).prop("outerHTML") +
						"<p></p>";
						editor.insertContent("");
						tinymce.activeEditor.setContent(str_html);
					}
				});
				*/
			},
			
			//Link
			typeahead_urls: false,
			
			//Image
			file_picker_types: 'image',
			file_picker_callback: function (cb, value, meta) {
				var input = document.createElement('input');
				input.setAttribute('type', 'file');
				input.setAttribute('accept', 'image/*');
				input.onchange = function () {
					var file = this.files[0];
					var reader = new FileReader();
					reader.onload = function () {
						var id = 'blobid' + (new Date()).getTime();
						var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
						var base64 = reader.result.split(',')[1];
						var blobInfo = blobCache.create(id, file, base64);
						blobCache.add(blobInfo);
						cb(blobInfo.blobUri(), { title: file.name });
					};
					reader.readAsDataURL(file);
				};
				input.click();
			},
			
			init_instance_callback : function(){
				$("#tinymce_loader").hide();
				table_title = CreateTableTitle();
				table_content = "<table id='report_content' style='width:100%'>" + table_html + "</table>";
				tinymce.get("edit_report").setContent('<p></p>' + table_title + table_content + "<p></p>" ); 
			},
			
			entity_encoding: 'raw',   
			force_br_newlines : true, 
			force_p_newlines : false, 
			forced_root_block : true,
			inline_boundaries: false
		}
	);
}

function tiny_init_edit_header(strhtml){
	tinymce.init(
		{
			selector: 'div#edit_header',
			skin:"mytinymceskin",
			content_css:['../css/report.css','../js/TinyMCE/skins/ui/mytinymceskin/content.min.css','../css/general.css'],
			width : "1200px",
			height : "298px",
			menubar: false,
			table_toolbar: '',
			elementpath: false,
			resize: false,
			language: (function(){
				var c_lang = "<?php echo $_COOKIE[language]; ?>"; 
				if(c_lang == "tw")
					return "zh_TW";
				else if(c_lang=="cn")
					return "zh_CN";
				else
					return null;
			})(),
			
			//font style
			content_style: 'body { background-color:' + $("body").css("backgroundColor") + '; color:' + $("#dashboard-container").css("color") + '} ',
			
			fontsize_formats: '11px 13px 15px 17px 19px 20px 24px 36px 48px',
			
			
			font_formats: (function(){
				var c_lang = "<?php echo $_COOKIE[language]; ?>"; 
				if(c_lang == "tw")
					return "微軟正黑體=微軟正黑體,Microsoft JhengHei;新細明體=PMingLiU,新細明體;標楷體=標楷體,DFKai-SB,BiauKai;黑體=黑體,SimHei,Heiti TC; Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats";
				else if(c_lang=="cn")
					return "微软雅黑='微软雅黑';宋体='宋体';黑体='黑体';仿宋='仿宋';楷体='楷体';隶书='隶书';幼圆='幼圆'; Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats";
				else
					return "Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats";
			})(),
			
			//plugins
			plugins: ['advlist autolink lists link image code fullscreen table '],
			toolbar1: 'fontselect | fontsizeselect | forecolor backcolor | bold italic underline strikethrough ',
			toolbar2: 'link image table | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | fullscreen code',
			toolbar_mode: 'sliding',
			
			//Link
			typeahead_urls: false,
			
			//Image
			file_picker_types: 'image',
			file_picker_callback: function (cb, value, meta) {
				var input = document.createElement('input');
				input.setAttribute('type', 'file');
				input.setAttribute('accept', 'image/*');
				input.onchange = function () {
					var file = this.files[0];
					var reader = new FileReader();
					reader.onload = function () {
						var id = 'blobid' + (new Date()).getTime();
						var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
						var base64 = reader.result.split(',')[1];
						var blobInfo = blobCache.create(id, file, base64);
						blobCache.add(blobInfo);
						cb(blobInfo.blobUri(), { title: file.name });
					};
					reader.readAsDataURL(file);
				};
				input.click();
			},
			
			init_instance_callback : function(){
				if($("#select_report_template > option").length > 0)
					tinymce.get("edit_header").setContent(Template_obj[$("#select_report_template").prop('selectedIndex')]["header"]); 
			},
			
			entity_encoding: 'raw',   
			force_br_newlines : true, 
			force_p_newlines : false, 
			forced_root_block : true,
			inline_boundaries: false
		}
	);
}

function tiny_init_edit_footer(strhtml){
	tinymce.init(
		{
			selector: 'div#edit_footer',
			skin:"mytinymceskin",
			content_css:['../css/report.css','../js/TinyMCE/skins/ui/mytinymceskin/content.min.css','../css/general.css'],
			width : "1200px",
			height : "298px",
			menubar: false,
			table_toolbar: '',
			elementpath: false,
			resize: false,
			language: (function(){
				var c_lang = "<?php echo $_COOKIE[language]; ?>"; 
				if(c_lang == "tw")
					return "zh_TW";
				else if(c_lang=="cn")
					return "zh_CN";
				else
					return null;
			})(),
			
			//font style
			content_style: 'body { background-color:' + $("body").css("backgroundColor") + '; color:' + $("#dashboard-container").css("color") + '} ',
			
			fontsize_formats: '11px 13px 15px 17px 19px 20px 24px 36px 48px',
			
			
			font_formats: (function(){
				var c_lang = "<?php echo $_COOKIE[language]; ?>"; 
				if(c_lang == "tw")
					return "微軟正黑體=微軟正黑體,Microsoft JhengHei;新細明體=PMingLiU,新細明體;標楷體=標楷體,DFKai-SB,BiauKai;黑體=黑體,SimHei,Heiti TC; Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats";
				else if(c_lang=="cn")
					return "微软雅黑='微软雅黑';宋体='宋体';黑体='黑体';仿宋='仿宋';楷体='楷体';隶书='隶书';幼圆='幼圆'; Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats";
				else
					return "Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats";
			})(),
			
			//plugins
			plugins: ['advlist autolink lists link image code fullscreen table '],
			toolbar1: 'fontselect | fontsizeselect | forecolor backcolor | bold italic underline strikethrough ',
			toolbar2: 'link image table | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | fullscreen code',
			toolbar_mode: 'sliding',
			
			//Link
			typeahead_urls: false,
			
			
			
			//Image
			file_picker_types: 'image',
			file_picker_callback: function (cb, value, meta) {
				var input = document.createElement('input');
				input.setAttribute('type', 'file');
				input.setAttribute('accept', 'image/*');
				input.onchange = function () {
					var file = this.files[0];
					var reader = new FileReader();
					reader.onload = function () {
						var id = 'blobid' + (new Date()).getTime();
						var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
						var base64 = reader.result.split(',')[1];
						var blobInfo = blobCache.create(id, file, base64);
						blobCache.add(blobInfo);
						cb(blobInfo.blobUri(), { title: file.name });
					};
					reader.readAsDataURL(file);
				};
				input.click();
			},
			
			init_instance_callback : function(){
				if($("#select_report_template > option").length > 0)
					tinymce.get("edit_footer").setContent(Template_obj[$("#select_report_template").prop('selectedIndex')]["footer"]); 
			},
			
			entity_encoding: 'raw',   
			force_br_newlines : true, 
			force_p_newlines : false, 
			forced_root_block : true,
			inline_boundaries: false
		}
	);
}

function CreateTableTitle(){
	var report_type = "";
	switch($(".period-button.active").attr("period")){
		case "day":
			report_type = "<?=$lang['REPORT_SERVICE']['DAILY_REPORT'];?>";
			break;
		case "week":
			report_type = "<?=$lang['REPORT_SERVICE']['WEEKLY_REPORT'];?>";
			break;
		case "month":
			report_type = "<?=$lang['REPORT_SERVICE']['MONTHLY_REPORT'];?>";
			break;
		case "quarter":
			report_type = "<?=$lang['REPORT_SERVICE']['QUARTERLY_REPORT'];?>";
			break;
		case "year":
			report_type = "<?=$lang['REPORT_SERVICE']['ANNUAL_REPORT'];?>";
			break;
	}
	
	var report_title = "";
	if((isGroup && $("#select-device .select-wrapper .select-list .select-option.active").attr("type") == "1" && $("#report_format").val()=="2")||
		(isGroup && $("#select-device .select-wrapper .select-list .select-option.active").attr("type") == "0")
		)
		report_title = $(".sub-title").text() +" - " + $("#select_report_type_col :selected").text() + " - "  + report_type;
	else
		report_title = $(".sub-title").text() + " - "  + report_type;
	var report_date = $($(".time-button-option-desc")[0]).text();
	var compare_date = $($("#time-compare .time-button-option-desc")[0]).text();
	
	
	var NowDate = new Date();
	var day = NowDate.getDate();
	var month = NowDate.getMonth() + 1;
	var year = NowDate.getFullYear();
	if (day < 10) {
		day = "0" + day;
	}
	if (month < 10) {
		month = "0" + month;
	}
	var print_date = year + "/" + month + "/" + day;
	if($("#compare_time_format")[0].selectedIndex == 0)
		return '<table id="report_title" style="border-collapse: collapse; width: 100%;" border="0"><tr><td style="width: 15%;"><?=$lang["REPORT_SERVICE"]["REPORT_DATE"];?>:' + report_date + '</td><td style="width: 70%; text-align: center;"><span style="font-size: 24px;"><strong>' + report_title + '</strong></span></td><td style="width: 15%; text-align: right;"><?=$lang["REPORT_SERVICE"]["PRINT_DATE"];?>:' + print_date + '</td></tr></table>';
	else
		return '<table id="report_title" style="border-collapse: collapse; width: 100%;" border="0"><tr><td style="width: 15%;"><?=$lang["REPORT_SERVICE"]["REPORT_DATE"];?>:' + report_date + '<br/><?=$lang["REPORT_SERVICE"]["COMPARE_DATE"];?>:' + compare_date + '</td><td style="width: 70%; text-align: center;"><span style="font-size: 24px;"><strong>' + report_title + '</strong></span></td><td style="width: 15%; text-align: right;"><?=$lang["REPORT_SERVICE"]["PRINT_DATE"];?>:' + print_date + '</td></tr></table>';
}

function onClickWindowButton(button){
	var callback = windowCallback[windowCallback.length - 1];
	var closeWindow = true;

	if(typeof(callback) == "function"){
		closeWindow = callback(button);
	}

	if(closeWindow !== false){
		windowCallback.pop();
	}
}

function onClickWindowButton2(button){

	if(typeof(windowCallback2) == "function"){
		windowCallback2(button);
	}
}

var stringToHTML = function (str) {
	var parser = new DOMParser();
	var doc = parser.parseFromString(str, 'text/html');
	return doc.body;
};



</script>
<?php
}
?>

<?php
function customized_body(){
	global $lang, $language;
?>

<div style="padding:20px;">
	<div id="dashboard-expired-container" style="display:none;">
        <div class="dashboard-expired-title"><?=$lang['REPORT_SERVICE']['REPORT_SERVICE_NOT_AVAILABLE_OR_EXPIRED'];?></div>
        <div class="dashboard-expired-content"><?=$lang['REPORT_SERVICE']['CONTACT_SYSTEM_ADMINISTRATOR'];?></div>
    </div>
	
	<div id="report-container" style="position:relative;overflow:hidden; min-height:500px;display:none;">
		<div id="select-container">
			<div class="title" style="border-bottom-width:0px;padding-bottom:0px;"><?=$lang['REPORT_SERVICE']['SELECT_A_LOOP_OR_GROUP'];?></div>
			
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
		</div>
		<div id="div_report">
			<div class="title" style="position:relative;">
				<div id = "title"><?=$lang['REPORT_SERVICE']['POWER_REPORT'];?></div>
				<div class="sub-title"></div>
			</div>

			<div id="time-selector" style="float: left;">
				<div class="period-button-container">
					<div class="period-button" period="day" unit="hour"><?=$lang['REPORT_SERVICE']['DAY'];?></div>
					<div class="period-button" period="week" unit="day"><?=$lang['REPORT_SERVICE']['WEEK'];?></div>
					<div class="period-button" period="month" unit="day"><?=$lang['REPORT_SERVICE']['MONTH'];?></div>
					<div class="period-button" period="quarter" unit="month"><?=$lang['REPORT_SERVICE']['QUARTER'];?></div>
					<div class="period-button" period="year" unit="month"><?=$lang['REPORT_SERVICE']['YEAR'];?></div>
				</div>
				<div style="font-size:0;margin:5px 7px 0 9px;">
					<svg><use xlink:href="image/ics.svg#chevron_right"></use></svg>
				</div>
				
				<div id="div_time_format">
					<select id="compare_time_format" style="border: 1px solid #B3B3B3; background-color: #fefefe; background-image: linear-gradient(to bottom, #fefefe, #f2f2f2); border-radius: 3px; display:none;">
						<option><?=$lang['REPORT_SERVICE']['SINGLE_PERIOD'];?></option>
						<option><?=$lang['REPORT_SERVICE']['COMPARE_PERIOD'];?></option>
					</select>
					<div id="interface_compare_time_format">
						<div class="field-button-wrapper">
							<div class="field-button-container" id="compare_time_format-button-container">
								<div class="field-button" style="min-width: 127px;">
									<div style="display: table-row;">
										<div class="field-button-option-name" style="width:100%;"><?=$lang['REPORT_SERVICE']['SINGLE_PERIOD'];?></div><div class="field-button-option-desc"></div>
									</div>
									<div style="position:absolute;right:4px;top:50%;margin-top:-9px;">
										<svg><use xlink:href="image/ics.svg#arrow_drop_down"></use></svg>
									</div>
								</div>
								<div class="field-button-select" style="min-width: 127px;">
									<div class="field-button-option" style="display: block;" select_id="0"><?=$lang['REPORT_SERVICE']['SINGLE_PERIOD'];?></div>
									<div class="field-button-option" style="display: block;" select_id="1"><?=$lang['REPORT_SERVICE']['COMPARE_PERIOD'];?></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div style="font-size:0;margin:5px 7px 0 9px;">
					<svg><use xlink:href="image/ics.svg#chevron_right"></use></svg>
				</div>
				<div class="time-button-wrapper">
					<div style="min-width:164px;">
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
						
						<div class="time-button-container" time-type="compare" id="time-compare" style="margin: 5px 0 10px; display:none;">
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
				</div>
				
				<div style="font-size:0;margin:5px 7px 0 9px;">
					<svg><use xlink:href="image/ics.svg#chevron_right"></use></svg>
				</div>

				<div id="div_field-button-wrapper">
					<div class="field-button-wrapper">
						<div class="field-button-container" id="field-button-container">
							<div class="field-button">
								<div style="display: table-row;">
									<div class="field-button-option-name" style="width:100%;"><?=$lang['REPORT_SERVICE']['COLUMN_DISPLAY'];?></div><div class="field-button-option-desc"></div>
								</div>
								<div style="position:absolute;right:4px;top:50%;margin-top:-9px;">
									<svg><use xlink:href="image/ics.svg#arrow_drop_down"></use></svg>
								</div>
							</div>
							<div class="field-button-select" style="min-width: 150px;"></div>
						</div>
					</div>
				</div>

				<div style="font-size:0;margin:5px 7px 0 9px; display:none;">
					<svg><use xlink:href="image/ics.svg#chevron_right"></use></svg>
				</div>
				<div id="div_report_format" style="display:none;">
					<select id="report_format" style="display:none;" onchange="loadReport()">
						<option value="1"><?=$lang['REPORT_SERVICE']['GROUP_LOOP_STATISTICS'];?></option>
						<option value="2"><?=$lang['REPORT_SERVICE']['GROUP_LOOP_COMPARISON'];?></option>
					</select>
					
					<div id="interface_report_format">
						<div class="field-button-wrapper">
							<div class="field-button-container" id="report_format-button-container">
								<div class="field-button" style="min-width: 180px;">
									<div style="display: table-row;">
										<div class="field-button-option-name" style="width:100%;"><!--select text--></div><div class="field-button-option-desc"></div>
									</div>
									<div style="position:absolute;right:4px;top:50%;margin-top:-9px;">
										<svg><use xlink:href="image/ics.svg#arrow_drop_down"></use></svg>
									</div>
								</div>
								<div class="field-button-select" style="min-width: 180px;">
									<!--select option-->
									<div class="field-button-option" style="display: block;" select_id="0"><?=$lang['REPORT_SERVICE']['GROUP_LOOP_STATISTICS'];?></div>
									<div class="field-button-option" style="display: block;" select_id="1"><?=$lang['REPORT_SERVICE']['GROUP_LOOP_COMPARISON'];?></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div style="font-size:0;margin:5px 7px 0 9px; display:none;">
					<svg><use xlink:href="image/ics.svg#chevron_right"></use></svg>
				</div>
				
				<div id="select_report_type_col_div" style="display:none;">
					<select id="select_report_type_col" style="display:none;"></select>
					
					<div id="interface_select_report_type_col">
						<div class="field-button-wrapper">
							<div class="field-button-container" id="report_type-button-container">
								<div class="field-button" style="min-width: 180px;">
									<div style="display: table-row;">
										<div class="field-button-option-name" style="width:100%;"><!--select text--></div><div class="field-button-option-desc"></div>
									</div>
									<div style="position:absolute;right:4px;top:50%;margin-top:-9px;">
										<svg><use xlink:href="image/ics.svg#arrow_drop_down"></use></svg>
									</div>
								</div>
								<div class="field-button-select" style="min-width: 180px;">
									<!--select option-->
									<div class="field-button-option" style="display: block;" select_id="0"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			
			<div style="float: right;">
				<input type="button" value="<?=$lang['REPORT_SERVICE']['TEMPLATE_MANAGEMENT'];?>" id="report_style" style="width: auto;" onClick="onClickManagementTemplate();">
				<input type="button" value="<?=$lang['REPORT_SERVICE']['DOWNLOAD_REPORT_PDF'];?>" id="export_PDF" style="width: auto;" onClick="onClickEditReport();">
				<input type="button" value="<?=$lang['REPORT_SERVICE']['DOWNLOAD_REPORT'];?>" id="export" style="width: auto;">
			</div>
			<table style="width:100%; margin-top: 75px;" id="module_list_table" class="module-list-table"></table>
		</div>
	</div>
</div>

<div id="popup-container" style="display:none;">
	<div class="popup-wrapper" id="window-management_template" style="width:1200px;">
		<div class="popup-container">
			<div class="popup-title"><?=$lang['REPORT_SERVICE']['TEMPLATE_MANAGEMENT'];?></div>
			<div class="popup-content window-dashboard-container" style="background: #ccc; padding: 5px;">
				<select id="select_report_template" style="min-width:140px; max-width:300px" onchange="SelectTemplate();"></select>
				<button id="dashboard-new" style="padding: 4px;" tip="<?=$lang['REPORT_SERVICE']['TIP']['CREATE'];?>" tip_position="bottom" onclick="onClickAddTemplate();"><svg style="fill: #FFF;width: 21px;height: 21px;"><use xlink:href="image/ics.svg#insert_drive_file"></use></svg></button>
				<button id="edittemplate" style="padding: 4px;" tip="<?=$lang['REPORT_SERVICE']['TIP']['EDIT'];?>" tip_position="bottom" onclick="onClickEditTemplate();"><svg style="fill: #FFF;width: 21px;height: 21px;"><use xlink:href="image/ics.svg#settings"></use></svg></button>
				<button id="copytemplate" style="padding: 4px;" tip="<?=$lang['REPORT_SERVICE']['TIP']['COPY'];?>" tip_position="bottom" onclick="onClickCopyTemplate();"><svg style="fill: #FFF;width: 21px;height: 21px;"><use xlink:href="image/ics.svg#file_copy"></use></svg></button>
				<button id="deltemplate" class="red" style="padding: 4px;" tip="<?=$lang['REPORT_SERVICE']['TIP']['REMOVE'];?>" tip_position="bottom" onclick="onClickRemoveTemplate();"><svg style="fill: #FFF;width: 21px;height: 21px;"><use xlink:href="image/ics.svg#delete"></use></svg></button>
			</div>
			<div class="popup-content window-dashboard-container">
				<div style="display:table; margin:0 auto; position:relative;">
					<div style="height:650px">
						<div class="window-module-interface" style="padding: 0px 8px;font-size: 15px;font-weight: bolder;background-color: #e5e9eb;text-align: center;"><?=$lang['REPORT_SERVICE']['HEADER'];?></div>
						<div class="popup-content window-group-container" style="height:300px" id="edit_header"></div>
						<div class="window-module-interface" style="padding: 0px 8px;font-size: 15px;font-weight: bolder;background-color: #e5e9eb;text-align: center;"><?=$lang['REPORT_SERVICE']['FOOTER'];?></div>
						<div class="popup-content window-group-container" style="height:300px" id="edit_footer"></div>
					</div>
					<div id="notemplate" style="position:absolute;width:100%; height:500px; top:0; bottom:0; left:0; right:0; background-color:rgba(255, 255, 255, 1); z-index:4;text-align:center;line-height: 500px; display:none;"><?=$lang['REPORT_SERVICE']['NO_TEMPLATE'];?></div>
				</div>
				
			</div>
			<div class="popup-footer">
				<input type="button" value="<?=$lang['OK'];?>" onClick="onClickWindowButton('ok');">
				<input type="button" class="gray" value="<?=$lang['CANCEL'];?>" onClick="onClickWindowButton('cancel');"></span>
			</div>
		</div>
	</div>

	<div class="popup-wrapper" id="window-report_edit" style="width:1800px;">
		<div class="popup-container">
			<div class="popup-title"><?=$lang['REPORT_SERVICE']['DOWNLOAD_REPORT_PDF'];?></div>
			<div class="popup-content window-dashboard-container">
				<div style="display:table;margin:0 auto;">
					<div class="popup-content window-group-container" style="height:300px" id="edit_report"></div>
				</div>
			</div>
			<div class="popup-footer">
				<input type="button" style="width: auto; min-width: 100px;" value="<?=$lang['REPORT_SERVICE']['DOWNLOAD'];?>" onClick="DownloadPDF();">
				<input type="button" class="gray" value="<?=$lang['REPORT_SERVICE']['CLOSE'];?>" onClick="onClickWindowButton('cancel');"></span>
			</div>
		</div>
	</div>
</div>


<div class="popup-background" id="window-TemplateManagement" style="z-index: 1000;">
	<div class="popup-container" style="width: 500px;margin: auto auto;margin-top: 200px; border: 7px solid transparent; border-radius: 7px; background-color: rgba(0,0,0,0.49);">
		<div class="popup-title"><?=$lang['REPORT_SERVICE']['TEMPLATE_SETTING'];?></div>
		<div style="width: 100%; text-align: center; background: white; padding: 10px 0px 10px 0px; box-sizing: border-box;">
			<div style="display: inline-block; padding-right: 15px;"><?=$lang['REPORT_SERVICE']['TEMPLATE_NAME'];?></div>
			<div style="display: inline-block;"><input type="text" id="TemplateName" style="width:400px;"></div>
		</div>
		<div class="popup-footer">
			<input type="button" value="<?=$lang['OK'];?>" onClick="onClickWindowButton2('ok');">&nbsp;&nbsp;<input type="button" class="gray" value="<?=$lang['CANCEL'];?>" onClick="onClickWindowButton2('cancel');"></span>
		</div>
	</div>
</div>

<div class="popup-background" id="window-TemplateManagement_checkDel" style="z-index: 1000;">
	<div class="popup-container" style="width: 400px; margin: auto auto; margin-top: 200px; border: 7px solid transparent; border-radius: 7px; background-color: rgba(0,0,0,0.49);">
		<div class="popup-content">
			<div class="popup-icon-wrapper"><span class="ics-popup warning" id="popup-icon"></span></div>
			<div style="display: table-cell;vertical-align: middle;padding: 10px 10px 10px 0;line-height: 150%;"><?=$lang['REPORT_SERVICE']['DELETE_THIS_TEMPLATE'];?></div>
		</div>
		<div class="popup-footer">
			<input type="button" value="<?=$lang['OK'];?>" onClick="onClickWindowButton2('ok');">&nbsp;&nbsp;<input type="button" class="gray" value="<?=$lang['CANCEL'];?>" onClick="onClickWindowButton2('cancel');"></span>
		</div>
	</div>
</div>

<div class="popup-background" id="window-TemplateManagement_wrapper" style="z-index: 1000;">
	<div class="popup-wrapper" id="popup-wrapper2" style="margin-top: 400.5px;">
		<div class="popup-container">
			<!--<div class="popup-title">Dialog</div>-->
			<div class="popup-content">
				<div class="popup-icon-wrapper"><span class="ics-popup error" id="popup-icon"></span></div>
				<div id="popup-message2"><?=$lang['REPORT_SERVICE']['LENGTH_LONGER_THEN_50'];?></div>
			</div>
			<div class="popup-footer">
				<input type="button" value="OK" onclick="popupOK();"><span id="popup-cancel-button" style="display: none;">&nbsp;&nbsp;<input type="button" class="gray" value="Cancel" onclick="popupCancel();"></span>
			</div>
		</div>
	</div>
</div>

<div class="popup-background" id="window-SelectTemplate" style="z-index: 1000;">
	<div class="popup-container" style="width: 500px;margin: auto auto;margin-top: 200px; border: 7px solid transparent; border-radius: 7px; background-color: rgba(0,0,0,0.49);">
		<div class="popup-title"><?=$lang['REPORT_SERVICE']['DOWNLOAD_REPORT'];?></div>
		<div style="width: 100%; text-align: center; background: white; padding: 10px 0px 10px 0px; box-sizing: border-box;">
			<div style="display: inline-block; padding-right: 15px;"><?=$lang['REPORT_SERVICE']['APPLY_TEMPLATE'];?></div>
			<div style="display: inline-block;"><select id="select_report_template_exl" style="min-width:400px;"></select></div>
		</div>
		<div class="popup-footer">
			<input type="button" value="<?=$lang['REPORT_SERVICE']['DOWNLOAD'];?>" onClick="onClickWindowButton2('ok');">&nbsp;&nbsp;<input type="button" class="gray" value="<?=$lang['CANCEL'];?>" onClick="onClickWindowButton2('cancel');"></span>
		</div>
	</div>
</div>

<div class="popup-background" id="window-SelectOverturn" style="z-index: 1000;">
	<div class="popup-container" style="height: 540px; width: 800px;margin: auto auto;margin-top: 200px; border: 7px solid transparent; border-radius: 7px; background-color: rgba(0,0,0,0.49);">
		<div class="popup-title"><?=$lang['REPORT_SERVICE']['DOWNLOAD_REPORT_PDF'];?></div>
		<table style="width:100%">
			<col width="80%">
			<col width="20%">
			<tr>
				<td style="height: 500px; display: block; overflow-y: scroll; background: #fff;">
					<div id="div_perview" style="width: 100%; background: white; padding: 10px 10px 10px 10px; box-sizing: border-box;"></div>
				</td>
				<td style="background: white; vertical-align: top;">
					<div style="background-color: #FFF; padding: 10px; text-align: center;">
						<?=$lang['REPORT_SERVICE']['ORIENTATION'];?>
						<select id="select_report_Overturn" style="min-width:105px; margin:5px;" onchange="PreviewPDF()"></select><br/>
						<?=$lang['REPORT_SERVICE']['MARGINS'];?>
						<select id="broadbrimmed" style="width:105px; margin:5px;" onchange="PreviewPDF()">
							<option value="0">0</option>
							<option value="5">5</option>
							<option value="10">10</option>
							<option value="15">15</option>
							<option value="20">20</option>
							<option value="25">25</option>
							<option value="30">30</option>
						</select>
						<br/>
						<input type="button" style="min-width:140px; margin: 5px;" value="<?=$lang['REPORT_SERVICE']['DOWNLOAD'];?>" onClick="onClickWindowButton2('ok');">
						<input type="button" style="min-width:140px; margin: 5px;" class="gray" value="<?=$lang['CANCEL'];?>" onClick="onClickWindowButton2('cancel');">
					</div>
				</td>
			</tr>
		</table>
	</div>
</div>

<div id="PDF_download_div" style="display:none;"></div>

<?php
}
?>