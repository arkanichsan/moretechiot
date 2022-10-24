<?php
global $language;

$lang["WIDGET"]["VALUE_TABLE"] = array(
	"DRAWING" => array(
		"en" => "Drawing",
		"tw" => "繪圖",
		"cn" => "绘图"
	),
	"VALUE_MODE" => array(
		"en" => "Value Mode",
		"tw" => "數值模式",
		"cn" => "数值模式"
	),
	"AVERAGE_VALUE" => array(
		"en" => "Average Value",
		"tw" => "平均數值",
		"cn" => "平均数值"
	),
	"LASTEST_VALUE" => array(
		"en" => "Latest Value",
		"tw" => "最新數值",
		"cn" => "最新数值"
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
	),
	"CHANNEL" => array(
		"en" => "Channel",
		"tw" => "通道",
		"cn" => "通道"
	),
	"VALUE"  => array(
		"en" => "Value",
		"tw" => "數值",
		"cn" => "数值"
	),
	"TIP" => array(
		"CHANNEL" => array(
			"en" => "Channel:",
			"tw" => "通道:",
			"cn" => "通道:"
		),
		"TOTAL_VALUE" => array(
			"en" => "Total Value:",
			"tw" => "累積值:",
			"cn" => "累积值:"
		),
		"AVERAGE_VALUE" => array(
			"en" => "Average Value:",
			"tw" => "平均值:",
			"cn" => "平均值:"
		),
		"TIME" => array(
			"en" => "Time:",
			"tw" => "時間:",
			"cn" => "时间:"
		),
		"VALUE"  => array(
			"en" => "Value:",
			"tw" => "數值:",
			"cn" => "数值:"
		)
	)
);
?>

widget["value-table"] = {
	minChannelNumber: 1,
	maxChannelNumber: null,

	// Event function
	settingCreated: function($content, settings){// setting == undefined mean is create not modify
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
					}).css("paddingBottom", "10px").text("<?=$lang['WIDGET']['VALUE_TABLE']['DRAWING'][$language]?>")
				)
			)/*.append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['VALUE_TABLE']['VALUE_MODE'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<select></select>").attr("id", "widget-draw-value-mode").css("width", "100%").append(
							$("<option></option>").val("1").text("<?=$lang['WIDGET']['VALUE_TABLE']['AVERAGE_VALUE'][$language]?>")
						).append(
							$("<option></option>").val("0").text("<?=$lang['WIDGET']['VALUE_TABLE']['LASTEST_VALUE'][$language]?>").attr("selected", true)
						)
					)
				)
			)*/.append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['VALUE_TABLE']['ICON'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<select></select>").attr("id", "widget-draw-icon-display").css("width", "100%").append(
							$("<option></option>").val("1").text("<?=$lang['WIDGET']['VALUE_TABLE']['ENABLE'][$language]?>").attr("selected", true)
						).append(
							$("<option></option>").val("0").text("<?=$lang['WIDGET']['VALUE_TABLE']['DISABLE'][$language]?>")
						)
					)
				)
			)
		);

		if(settings){
//			$("#widget-draw-value-mode").val(settings.draw.valueMode);
			$("#widget-draw-icon-display").val(settings.draw.displayIcon == true ? "1" : "0");
		}
	},
	channelUpdated: function($content, channels){
	},
	settingSaved: function(settings, channels){
		settings.draw = {
//			"valueMode": $("#widget-draw-value-mode").val()
			"displayIcon": $("#widget-draw-icon-display").val() == "1" ? true : false
		};
	},
	widgetCreated: function($content, settings, channels){
		var widgetUID = $content.closest("div.grid-stack-item-content").attr("uid");

		$content.append(
			$("<div></div>").addClass("value-table").append(
				$("<table></table>").append(
					$("<thead></thead>").append(
						$("<tr></tr>").append(
							$("<th></th>").css("width", "70%").text("<?=$lang['WIDGET']['VALUE_TABLE']['CHANNEL'][$language]?>")
						).append(
							$("<th></th>").css({"width": "30%", "minWidth": "100px"}).text("<?=$lang['WIDGET']['VALUE_TABLE']['VALUE'][$language]?>")
						)
					)
				).append((function(){
					var $tbody = $("<tbody></tbody>");

					for(var i = 0; i < channels.order.length; i++){
						var accountUID = channels.order[i].accountUID;
						var deviceUID = channels.order[i].deviceUID;
						var moduleUID = channels.order[i].moduleUID;
						var channel = channels.order[i].channel;

						$tbody.append(
							$("<tr></tr>").append((function(){
								if(settings.draw.displayIcon == true){
									return $("<td></td>").attr("class", "name icon").text(channels[accountUID][deviceUID][moduleUID][channel].shortName).append(
										$("<div></div>").css("backgroundImage", "url('" + (channels[accountUID][deviceUID][moduleUID][channel].icon || defaultIcon) + "')")
									);
								}
								else{
									return $("<td></td>").attr("class", "name").text(channels[accountUID][deviceUID][moduleUID][channel].shortName);
								}
							})()).append(
								$("<td></td>").attr("class", "value").attr("id", widgetUID + "-" + accountUID + "-" + deviceUID + "-" + moduleUID + "-" + channel + "-value").text("-")
							).bind("mouseenter", function(event){
								if($(this).find(".value").data("dataSet") === undefined){
									$(this).data("disableTip", true);
								}
								else{
									$(this).data("disableTip", false);
								}
							}).bind("mousemove", function(event){
								if($(this).data("disableTip") == true){
									return;
								}

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

									$tip = $("body > div.flotTip").clone().removeClass("flotTip").addClass("valueTip").attr("id", id).html((function(dataSet){
										var table = "<table>";
										table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['WIDGET']['VALUE_TABLE']['TIP']['CHANNEL'][$language]?></td><td>" + dataSet.tipText + "</td></tr>";
										if(typeof(dataSet.total) != "undefined"){
											table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['WIDGET']['VALUE_TABLE']['TIP']['TOTAL_VALUE'][$language]?></td><td>" + (dataSet.average != null ? numberWithCommas(parseFloat((dataSet.total).toPrecision(7))) + " " + dataSet.unit : "-") + "</td></tr>";
											table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['WIDGET']['VALUE_TABLE']['TIP']['AVERAGE_VALUE'][$language]?></td><td>" + (dataSet.average != null ? numberWithCommas(parseFloat((dataSet.average).toPrecision(7))) + " " + dataSet.unit : "-") + "</td></tr>";
										}
										else{
											var date = new Date(dataSet.date);
											table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['WIDGET']['VALUE_TABLE']['TIP']['TIME'][$language]?></td><td>" + (dataSet.last != null ? date.getFullYear() + "/" + padding(date.getMonth() + 1, 2) + "/" + padding(date.getDate(), 2) + " " + padding(date.getHours(), 2) + ":" + padding(date.getMinutes(), 2) + ":" + padding(date.getSeconds(), 2) : "-") + "</td></tr>";
											table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['WIDGET']['VALUE_TABLE']['TIP']['VALUE'][$language]?></td><td>" + (dataSet.last != null ? numberWithCommas(parseFloat(dataSet.last.toPrecision(7))) + " " + dataSet.unit : "-") + "</td></tr>";
										}

										table += "</table>";

										return table;
									})($(this).find(".value").data("dataSet"))).show();

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
								if($(this).data("disableTip") == true){
									return;
								}

								$("#" + $(this).attr("valueTip")).remove();
							})
						);
					}

					return $tbody;
				})())
			)
		).append(
			$("<div></div>").attr("class", "widget-loader")
		);

		var $tbody = $content.find("div.value-table > table > tbody");

		if($tbody.children().length <= 0){
			$tbody.addClass("no-event").append(
				$("<tr></tr>").append(
					$("<td></td>")
				).append(
					$("<td></td>")
				)
			).append(
				$("<tr></tr>").attr("class", "text").append(
					$("<td></td>").attr("colSpan", "2").text("No channel select.")
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>")
				).append(
					$("<td></td>")
				)
			).parent("table").css("height", "100%");
		}

		// If channel data exist, draw chart immediately
		for(var accountUID in channels){
			for(var deviceUID in channels[accountUID]){
				for(var moduleUID in channels[accountUID][deviceUID]){
					for(var channel in channels[accountUID][deviceUID][moduleUID]){
						if(window.channels[accountUID][deviceUID][moduleUID][channel].length > 0){
							this.drawChart($content, settings, channels);
							return;
						}
					}
				}
			}
		}
	},
	widgetUpdated: function($content, settings, channels){
		$content.empty();
		this.widgetCreated($content, settings, channels);
	},
	widgetRemoved: function($content, settings, channels){
	},
	dataUpdated: function($content, settings, channels){
		this.drawChart($content, settings, channels);
	},
	dragstop: function($content, settings, channels){
		this.drawChart($content, settings, channels);
	},
	resizestart: function($content, settings, channels){
	},
	resizing: function($content, settings, channels){
	},
	resizestop: function($content, settings, channels){
		this.drawChart($content, settings, channels);
	},

	// Custom function
	drawChart: function($content, settings, channels){
		if($content.attr("state") == "resizing" || $content.attr("state") == "draging"){
			return
		}

		var dashboard = window.accounts[$("#dashboard-switch-handler").attr("account_uid")].dashboards[$("#dashboard-switch-handler").attr("dashboard_uid")];
		var widgetUID = $content.closest("div.grid-stack-item-content").attr("uid");

		for(var accountUID in channels){
			for(var deviceUID in channels[accountUID]){
				for(var moduleUID in channels[accountUID][deviceUID]){
					for(var channel in channels[accountUID][deviceUID][moduleUID]){
						if(window.channels[accountUID][deviceUID][moduleUID][channel].length > 0){
							var value = 0;
							var dataSet = null;

//							if(settings.draw.valueMode == "1"){
//								var data = window.channels[accountUID][deviceUID][moduleUID][channel];
//								var total = 0, counter = 0, average = null;
//
//								for(var i = 0; i < data.length; i++){
//									if(data[i][1] == null || data[i][0] < maxTimestamp - (dashboard.dataLength * 1000)){continue;}
//
//									total += data[i][1];
//									counter++;
//								}
//
//								if(counter > 0){
//									average = total / counter;
//								}
//
//								value = average;
//								dataSet = {
//									tipText: channels[accountUID][deviceUID][moduleUID][channel].fullName,
//									unit: channels[accountUID][deviceUID][moduleUID][channel].unit,
//									total: total,
//									average: average
//								}
//							}
//							else{
								value = window.channels[accountUID][deviceUID][moduleUID][channel][window.channels[accountUID][deviceUID][moduleUID][channel].length - 1][1];
								dataSet = {
									tipText: channels[accountUID][deviceUID][moduleUID][channel].fullName,
									unit: channels[accountUID][deviceUID][moduleUID][channel].unit,
									date: window.channels[accountUID][deviceUID][moduleUID][channel][window.channels[accountUID][deviceUID][moduleUID][channel].length - 1][0],
									last: value
								}
//							}

							var $value = $("#" + widgetUID + "-" + accountUID + "-" + deviceUID + "-" + moduleUID + "-" + channel + "-value").data("dataSet", dataSet);

							if(value != null){
								$value.text(numberWithCommas(parseFloat(value.toPrecision(7))) + " " + channels[accountUID][deviceUID][moduleUID][channel].unit).closest("tr").triggerHandler("mouseleave");
							}
							else{
								$value.text("-").closest("tr").triggerHandler("mouseleave");
							}
						}
					}
				}
			}
		}

		$content.find("> .widget-loader").hide();
	}
};