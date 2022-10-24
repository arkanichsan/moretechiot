<?php
global $language;

$lang["WIDGET"]["SLIDER"] = array(
	"DRAWING" => array(
		"en" => "Drawing",
		"tw" => "繪圖",
		"cn" => "绘图"
	),
	"RANGE" => array(
		"en" => "Range",
		"tw" => "範圍",
		"cn" => "范围"
	),
	"CHANNEL" => array(
		"en" => "Channel",
		"tw" => "通道",
		"cn" => "通道"
	),
	"MIN" => array(
		"en" => "Minimum",
		"tw" => "最小值",
		"cn" => "最小值"
	),
	"MAX" => array(
		"en" => "Maximum",
		"tw" => "最大值",
		"cn" => "最大值"
	),
	"STEP" => array(
		"en" => "Step",
		"tw" => "間距",
		"cn" => "间距"
	),
	"NO_CHANNEL_Choose" => array(
		"en" => "No Channel Choose",
		"tw" => "無選擇任何通道",
		"cn" => "无选择任何通道"
	),
	"ICON" => array(
		"en" => "Icon",
		"tw" => "圖示",
		"cn" => "图标"
	),
	"ENABLE" => array(
		"en" => "Enable",
		"tw" => "啟用",
		"cn" => "启用"
	),
	"DISABLE" => array(
		"en" => "Disable",
		"tw" => "停用",
		"cn" => "停用"
	)
);
?>


widget.slider = {
	minChannelNumber: 1,
	maxChannelNumber: null,
	mousedown: false,

	// Event function
	settingCreated: function($content, settings){
		$content.append(
			$("<table></table>").attr({
				"cellSpacing": "0",
				"cellPadding": "0",
				"border": "0"
			}).append(
				$("<colgroup></colgroup>").append(
					$("<col></col>")
				).append(
					$("<col></col>").css("width", "200px")
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").attr({
						"colSpan": "999",
						"class": "content-title"
					}).css("paddingBottom", "10px").text("<?=$lang['WIDGET']['SLIDER']['DRAWING'][$language]?>")
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['SLIDER']['ICON'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<select></select>").attr("id", "widget-draw-icon-display").css("width", "100%").append(
							$("<option></option>").val("1").text("<?=$lang['WIDGET']['SLIDER']['ENABLE'][$language]?>").attr("selected", true)
						).append(
							$("<option></option>").val("0").text("<?=$lang['WIDGET']['SLIDER']['DISABLE'][$language]?>")
						)
					)
				)
			)
		);
		
		$content.append(
			$("<div></div>").attr({"id":"range_div"}).css({"padding": "0px 0px 0px 0px"})
		);
		
		if(settings){
			$("#widget-draw-icon-display").val(settings.displayIcon == true ? "1" : "0");
			this.CreateChannelList(settings.channels_data, window.accounts[$("#dashboard-switch-handler").attr("account_uid")].dashboards[$("#dashboard-switch-handler").attr("dashboard_uid")].widgets[$content.attr("uid")].channels);
		}
	},
	channelUpdated: function($content, channels){
		var ch_arr=[];
		for(var channels_idx in channels){
			for(var device_idx in channels[channels_idx]){
				for(var module_idx in channels[channels_idx][device_idx]){
					for(var channel_idx in channels[channels_idx][device_idx][module_idx]){
						try{
							var channel_id = channels_idx+"_"+device_idx+"_"+module_idx+"_"+channel_idx;
							ch_arr.push({
								"channel_id":channel_id,
								"shortName":channels[channels_idx][device_idx][module_idx][channel_idx].shortName,
								"fullName":channels[channels_idx][device_idx][module_idx][channel_idx].fullName,
								"color":$("#" + channel_id +"_color").val(),
								"min":$("#" + channel_id +"_min").val(),
								"max":$("#" + channel_id +"_max").val(),
								"step":$("#" + channel_id +"_step").val()
							});
						}
						catch{
							ch_arr.push({
								"channel_id":channels_idx+"_"+device_idx+"_"+module_idx+"_"+channel_idx,
								"shortName":channels[channels_idx][device_idx][module_idx][channel_idx].shortName,
								"fullName":channels[channels_idx][device_idx][module_idx][channel_idx].fullName
							});
						}
					}
				}
			}
		}
		this.CreateChannelList(ch_arr, channels);
	},
	settingSaved: function(settings){
		var ch_arr = [];
		for(var st_channel_idx = 0; st_channel_idx < $("#channle-list-table-body > tr").length; st_channel_idx++){
			var st_channel_id = $($("#channle-list-table-body > tr")[st_channel_idx]).attr("account_uid") + "_" +
			$($("#channle-list-table-body > tr")[st_channel_idx]).attr("device_uid") + "_" +
			$($("#channle-list-table-body > tr")[st_channel_idx]).attr("module_uid") + "_" +
			$($("#channle-list-table-body > tr")[st_channel_idx]).attr("channel");
			
			if($("#" + st_channel_id +"_min").val()*1>$("#" + st_channel_id +"_max").val()*1){
				var temp = $("#" + st_channel_id +"_min").val();
				$("#" + st_channel_id +"_min").val($("#" + st_channel_id +"_max").val());
				$("#" + st_channel_id +"_max").val(temp);
			}
			
			ch_arr.push({
				"channel_id":st_channel_id,
				"min":$("#" + st_channel_id +"_min").val()?$("#" + st_channel_id +"_min").val():0,
				"max":$("#" + st_channel_id +"_max").val()?$("#" + st_channel_id +"_max").val():100,
				"step":$("#" + st_channel_id +"_step").val()?$("#" + st_channel_id +"_step").val():1
			});
		}
		settings.displayIcon =  $("#widget-draw-icon-display").val() == "1" ? true : false;
		settings.channels_data = ch_arr;
	},
	widgetCreated: function($content, settings, channels){
		var dashboard = window.accounts[$("#dashboard-switch-handler").attr("account_uid")].dashboards[$("#dashboard-switch-handler").attr("dashboard_uid")];
		var isDark = dashboard.darkMode;
		
		var ch_arr = settings.channels_data;
		var Icon_display = settings.displayIcon;
		$content.empty();
		
		$content.append(
			$("<div></div>").addClass("range").css({"width":"100%", "margin-top": "0px","padding-top":"0px", "display": "none","transform-origin":"left top"})
		).append(
			$("<div></div>").attr("class", "widget-loader")
		);
		var color ="";
		
		var channel_idx = 0;
		for(var channel in ch_arr){
			var channel_id = ch_arr[channel].channel_id;
			var channel_info = ch_arr[channel].channel_id.split("_");
			var accountUID = channel_info[0];
			var deviceUID = channel_info[1];
			var moduleUID = channel_info[2];
			var channelUID = channel_info[3];
			var min = ch_arr[channel].min;
			var max = ch_arr[channel].max;
			var step = ch_arr[channel].step;
			var shortName = "";
			try{
				shortName = channels[accountUID][deviceUID][moduleUID][channelUID].shortName;
			}
			catch{continue;}
			
			color = generateColor(ch_arr.length, isDark)[channel_idx].toString();
			
			$content.children(".range").append(
				this.CreateRangeTool({"accountUID":accountUID,"deviceUID":deviceUID,"moduleUID":moduleUID,"channel":channelUID},max,min,step,color,shortName,channels,Icon_display,isDark)
			);
			
			channel_idx++;
			
			
			var $range = $content.children(".range");
			var $container = $range.parent();
			var ratio = $container.width() / $range.width();
			
			if(ratio * $range.height() > $container.height()){
				ratio = $container.height() / $range.height();
			}
			if(ratio >= 1){
				$range.width("100%");
			}
			else
				$range.width($content.width()/ratio+"px");
			$range.css("transform", "scale(" + ratio + ")");
		}
		
		if(dashboard.share || guestLevel == 0){//guest
			$content.children(".range").find(".slider").attr("disabled", true);
			$content.children(".range").find(".bubble").attr("disabled", true);
			$('<style type="text/css"></style>').text('.slider::-webkit-slider-thumb {cursor: default;}').appendTo('head');
			$('<style type="text/css"></style>').text('.slider::-moz-range-thumb {cursor: default;}').appendTo('head');
			$('<style type="text/css"></style>').text('.range {opacity: 0.5;}').appendTo('head');
			$('<style type="text/css"></style>').text('.slider {cursor: default;}').appendTo('head');
		}
	},
	widgetUpdated: function($content, settings, channels){
		$content.empty();
		this.widgetCreated($content, settings, channels);
	},
	widgetRemoved: function($content, settings){
	},
	dataUpdated: function($content, settings, channels){
		$content.find("> .widget-loader").hide();
		$content.find("> .range").show();
		var dashboard = window.accounts[$("#dashboard-switch-handler").attr("account_uid")].dashboards[$("#dashboard-switch-handler").attr("dashboard_uid")];
		try{
			if(!this.mousedown){//讀取資料
				for(var accountUID in channels){
					for(var deviceUID in channels[accountUID]){
						for(var moduleUID in channels[accountUID][deviceUID]){
							for(var channel in channels[accountUID][deviceUID][moduleUID]){
								var value = window.channels[accountUID][deviceUID][moduleUID][channel][window.channels[accountUID][deviceUID][moduleUID][channel].length - 1][1];
								if(value == null){
									$content.find("[accountUID="+accountUID+"][deviceuid="+deviceUID+"][moduleuid="+moduleUID+"][channel="+channel+"]").css({"cursor": "default"});
									$content.find("[accountUID="+accountUID+"][deviceuid="+deviceUID+"][moduleuid="+moduleUID+"][channel="+channel+"]").attr("disabled", true);
									$content.find("[accountUID="+accountUID+"][deviceuid="+deviceUID+"][moduleuid="+moduleUID+"][channel="+channel+"]").parent().css({"Opacity":"0.7"});
									$content.find("[accountUID="+accountUID+"][deviceuid="+deviceUID+"][moduleuid="+moduleUID+"][channel="+channel+"]").val($content.find("[accountUID="+accountUID+"][deviceuid="+deviceUID+"][moduleuid="+moduleUID+"][channel="+channel+"]").attr("min"));
									$content.find("[accountUID="+accountUID+"][deviceuid="+deviceUID+"][moduleuid="+moduleUID+"][channel="+channel+"]").parent().find(".bubble").attr("disabled", true).css({"width": "4ch","left":"0","transform":"translateX(0%)"}).val("-");
									continue;
								}
								
								if(!(dashboard.share || guestLevel == 0)){
									$content.find("[accountUID="+accountUID+"][deviceuid="+deviceUID+"][moduleuid="+moduleUID+"][channel="+channel+"]").attr("disabled", false);
									$content.find("[accountUID="+accountUID+"][deviceuid="+deviceUID+"][moduleuid="+moduleUID+"][channel="+channel+"]").css({"cursor": "pointer"});
									$content.find("[accountUID="+accountUID+"][deviceuid="+deviceUID+"][moduleuid="+moduleUID+"][channel="+channel+"]").parent().css({"Opacity":"1"});
									$content.find("[accountUID="+accountUID+"][deviceuid="+deviceUID+"][moduleuid="+moduleUID+"][channel="+channel+"]").parent().find(".bubble").attr("disabled", false);
								}
								$content.find("[accountUID="+accountUID+"][deviceuid="+deviceUID+"][moduleuid="+moduleUID+"][channel="+channel+"]");
								$content.find("[accountUID="+accountUID+"][deviceuid="+deviceUID+"][moduleuid="+moduleUID+"][channel="+channel+"]").val(value);
								
								if($content.find("[accountUID="+accountUID+"][deviceuid="+deviceUID+"][moduleuid="+moduleUID+"][channel="+channel+"]").parent().find(".bubble").is(":focus")){
									continue;
								}
								$content.find("[accountUID="+accountUID+"][deviceuid="+deviceUID+"][moduleuid="+moduleUID+"][channel="+channel+"]").parent().find(".bubble").val(parseFloat(value.toFixed(2)));
								this.SetRangeVaule($content.find("[accountUID="+accountUID+"][deviceuid="+deviceUID+"][moduleuid="+moduleUID+"][channel="+channel+"]")[0],parseFloat(value.toFixed(2)));
							}
						}
					}
				}
			}
		}
		catch{}
	},
	dragstop: function($content, settings){
	},
	resizestart: function($content, settings){
	},
	resizing: function($content, settings){
		this.adjustRangeSize($content);
	},
	resizestop: function($content, settings){
		this.adjustRangeSize($content);
	},
	adjustRangeSize:function($content){
		var $range = $content.find(".range");
		$range.width("100%");
		var $container = $range.parent();
		var ratio = $container.width() / $range.width();
		
		if(ratio * $range.height() > $container.height()){
			ratio = $container.height() / $range.height();
		}
		
		if(ratio >= 1){
			$range.width("100%");
		}
		else
			$range.width($content.width()/ratio+"px");
		$range.css("transform", "scale(" + ratio + ")");
		return $range;
	},
	CreateChannelList: function(ch_arr, channels){
		$("#range_div").empty();

		$("#range_div").append($("<div>&nbsp;</div><div class='content-title'><?=$lang['WIDGET']['SLIDER']['RANGE'][$language]?></div>"));
		if(ch_arr.length == 0){
			$("#range_div").append(
				$("<div></div>")
					.css({"border-color": "#EEEEEE","border-width": "4px","border-style": "dashed","height": "100px","align-content": "center","text-align": "center","display": "grid","font-weight": "bold","color": "#cfcfcf", "cursor": "pointer"})
					.html("<?=$lang['WIDGET']['SLIDER']['NO_CHANNEL_Choose'][$language]?>")
					.hover(function(){
						$(this).css({"border-color": "#CCCCCC","color": "#999999"});
					}, function(){
						$(this).css({"border-color": "#EEEEEE","color": "#cfcfcf"});
					}).bind("click", function(){
						$("#menu-container div.menu-item[target=channel]").click();
					})
			);
		}
		else{
			$("#range_div").append($("<table></table>").attr({"id":"table_range","cellSpacing": "0","cellPadding": "0","border": "0"}));

			$("#range_div > #table_range").append(
				$("<tr></tr>").append(
					$("<td></td>").text("<?=$lang['WIDGET']['SLIDER']['CHANNEL'][$language]?>").css({"padding": "5px 15px 5px 0px", "minWidth": "100px"})
				).append(
					$("<td></td>").text("<?=$lang['WIDGET']['SLIDER']['MIN'][$language]?>").css({"padding": "5px 15px 5px 0px"})
				).append(
					$("<td></td>").text("<?=$lang['WIDGET']['SLIDER']['MAX'][$language]?>").css({"padding": "5px 15px 5px 0px"})
				).append(
					$("<td></td>").text("<?=$lang['WIDGET']['SLIDER']['STEP'][$language]?>").css({"padding": "5px 0px 5px 0px"})
				)
			)

			for(var channel in ch_arr){
				try{
					var channel_id = ch_arr[channel].channel_id;
					var channel_info = ch_arr[channel].channel_id.split("_");
					var shortName = channels[channel_info[0]][channel_info[1]][channel_info[2]][channel_info[3]].shortName;
					$("#range_div > #table_range").append(
						$("<tr></tr>").append(
							$("<td></td>").text(shortName).css({"padding": "3px 15px 3px 0px", "borderTop": "#eee solid 1px", "minWidth": "100px"})
						).append(
							$("<td></td>").css({"padding": "3px 15px 3px 0px", "borderTop": "#eee solid 1px"}).append(
								$("<input/>").attr({"id":channel_id + "_min","type": "number"}).css({"width":"100px"}).val(ch_arr[channel]["min"]?ch_arr[channel]["min"]:0)
							)
						).append(
							$("<td></td>").css({"padding": "3px 15px 3px 0px", "borderTop": "#eee solid 1px"}).append(
								$("<input/>").attr({"id":channel_id + "_max","type": "number"}).css({"width":"100px"}).val(ch_arr[channel]["max"]?ch_arr[channel]["max"]:100)
							)
						).append(
							$("<td></td>").css({"padding": "3px 0px 3px 0px", "borderTop": "#eee solid 1px"}).append(
								$("<input/>").attr({"id":channel_id + "_step","type": "number"}).css({"width":"100px"}).val(ch_arr[channel]["step"]?ch_arr[channel]["step"]:1)
							)
						)
					)
				}
				catch{}
			}
		}
	},
	CreateRangeTool:function(channel_info,max,min,step,color,shortName,channels,Icon_display,isDark){
		var channel_info_str = channel_info.accountUID+"_"+channel_info.deviceUID+"_"+channel_info.moduleUID+"_"+channel_info.channel;
		var tool = 
			$("<div></div>").css({"width": "100%","position": "relative","height":"100px"}).append(
				$("<div></div>").css({"display": "flex","align-items": "center","padding-bottom": "6px"}).append(
					$("<div></div>").css({
						"width": "25px",
						"height": "25px",
						"backgroundSize": "contain",
						"backgroundRepeat": "no-repeat",
						"backgroundPosition": "center center",
						"float": "left",
						"background-image": "url('" + (channels[channel_info.accountUID][channel_info.deviceUID][channel_info.moduleUID][channel_info.channel].icon || defaultIcon) + "')",
						"margin-right": "5px",
						"display":(Icon_display?"block":"none")
					})
				).append(
					$("<div></div>").text(shortName).css({"float": "left","padding": "4px 0px","width":"100%"})
						.bind("mouseenter", function(event){})
						.bind("mousemove", function(){
							var $tip = $("#" + $(this).attr("valueTip"));
							if($tip.length == 0){
								var id = (function(){
									var length = 8;
									var chars = "ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
									var randomString = "";
									for (var i = 0; i < length; i++) {
										randomString += chars.charAt(Math.floor(Math.random() * chars.length));
									}
									return randomString;
								})();

								$(this).attr("valueTip", id);
								$tip = $("body > div.flotTip").clone().removeClass("flotTip").addClass("valueTip").attr("id", id).html((function(){
									var accountuid = $("[valuetip=" + id + "]").parent().next().attr("accountuid");
									var deviceuid = $("[valuetip=" + id + "]").parent().next().attr("deviceuid");
									var moduleuid = $("[valuetip=" + id + "]").parent().next().attr("moduleuid");
									var channel = $("[valuetip=" + id + "]").parent().next().attr("channel");
									var table = "<table>";
									table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['WIDGET']['OVERLAY']['TIP']['CHANNEL'][$language]?></td><td>";
									table += channels[accountuid][deviceuid][moduleuid][channel].fullName;
									table += "</td></tr>";
									table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['WIDGET']['OVERLAY']['TIP']['NICKNAME'][$language]?></td><td>";
									table += channels[accountuid][deviceuid][moduleuid][channel].shortName;
									table += "</td></tr>";
									table += "</table>";
									return table;
								})).show();
								
								$("body").append($tip);
							}
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
						})
						.bind("mouseleave", function(){
							$("#" + $(this).attr("valueTip")).remove();
						})
				)
		).append(
			$("<input></input>").attr({"type":"range","min":min,"max":max,"step":step,"list":channel_info_str+"_tickmarks"})
				.addClass("slider")
				.css({"width":"100%","margin":"0px","background":color}).attr(channel_info)
				.change(function(){
					stopUpdate();
					setChannelData($(this).attr('accountUID'), $(this).attr('deviceUID'), $(this).attr('moduleUID'), $(this).attr('channel'), $(this).val())
						.done()
						.fail()
						.always(function() {startUpdate();});
				})
				.bind('input propertychange', function() {widget.slider.mousedown = true;$(this).mouseup(function(){widget.slider.mousedown = false;});widget.slider.SetRangeVaule(this,$(this).val());})
		).append(
			this.settickmarks(channel_info_str,min,max,isDark)
		).append(
			$("<input></input>").addClass("bubble")
				.css({"font-size":"15px","width":"1ch","height":"25px","min-width":"3ch","border":"2px transparent solid","text-align":"center","background": "#5a5a5a","color": "white","position": "absolute","border-radius": "4px","left": "50%","top":"56px","transform": "translateX(-50%)","opacity": "0.7"}).val("0")
				.on("input",function(){this.value=this.value.replace(/[^\-?\d.]/g,'');this.style.width = (this.value.length+3) + "ch";})
				.focus(function(){$(this).attr("temp_val",$(this).val());$(this).css({"border":"2px #FF0000 solid","opacity": "1"});this.select();})
				.focusout(function(){$(this).val($(this).attr("temp_val"));$(this).css({"border":"2px transparent solid","opacity": "0.7"});})
				.keypress(function(event)
				{
					var range = $(this).prev().prev()[0];
					if (event.which == 13) {
						if($(this).val()=="")
							$(this).val($(this).attr("temp_val"));
						$(range).val($(this).val());
						stopUpdate();
						setChannelData($(range).attr('accountUID'), $(range).attr('deviceUID'), $(range).attr('moduleUID'), $(range).attr('channel'), $(this).val())
							.done()
							.fail()
							.always(function() {startUpdate();});
						value = $(this).val();
						$(this).blur();
						widget.slider.SetRangeVaule(range,value);
					}
				})
		).append(
			$("<span></span>").css({"position": "relative","top": "-20px","left":"8px","z-index":"-1"}).text(min)
		).append(
			$("<span></span>").css({"position": "relative","top": "-20px","float": "right","z-index":"-1"}).text(max)
		);
		
		return tool;
	},
	settickmarks:function(channel_info_str,min,max,isDark){
		var tickcolor = "#444444";
		if(isDark)
			tickcolor ="#FFFFFF";
		else
			tickcolor ="#444444";
			
		
		var ticks_cp = 0;
		if(max-min >= 1 && max-min < 10){
			ticks_cp = 1;
		}
		else{
			ticks_cp = (max-min) / 10;
		}
		
		var html_str = "";
		html_str = $("<div></div>").attr({"id":channel_info_str+"_tickmarks"}).addClass("sliderticks");
		for(var tick=min*1 ; tick<=max; tick+=ticks_cp){
			html_str.append($("<p></p>").css({"background":tickcolor}));
		}

		return html_str;
	},
	SetRangeVaule:function(range,value){
		const bubble = $(range).parent().find(".bubble")[0];
		const range_val = range.value;
		const min = range.min ? range.min : 0;
		const max = range.max ? range.max : 100;
		const newVal = Number(((range_val - min) * 100) / (max - min));
		
		$(bubble).val(value);
		bubble.style.left = `calc(${newVal}% + (${10 - newVal * 0.15}px))`;
		if(newVal < 5){
			bubble.style.transform = "translateX(0%)";
		}
		else if(newVal>95){
			bubble.style.transform = "translateX(-100%)";
		}
		else{
			bubble.style.transform = "translateX(-50%)";
		}
		bubble.style.width = (bubble.value.length + 3) + "ch";
	}
}