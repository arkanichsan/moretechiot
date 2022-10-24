<?php
global $language;

$lang["WIDGET"]["GAUGE"] = array(
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
	"MARKER" => array(
		"en" => "Marker",
		"tw" => "刻度",
		"cn" => "刻度"
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
	"THRESHOLD" => array(
		"en" => "Threshold",
		"tw" => "臨界值",
		"cn" => "临界值"
	),
	"MINIMUM" => array(
		"en" => "Minimum",
		"tw" => "最小值",
		"cn" => "最小值"
	),
	"COLOR_WITH_NO" => array(
		"en" => "Color %number%",
		"tw" => "顏色%number%",
		"cn" => "颜色%number%"
	),
	"GREEN" => array(
		"en" => "Green",
		"tw" => "綠色",
		"cn" => "绿色"
	),
	"YELLOW" => array(
		"en" => "Yellow",
		"tw" => "黃色",
		"cn" => "黄色"
	),
	"RED" => array(
		"en" => "Red",
		"tw" => "紅色",
		"cn" => "红色"
	),
	"THRESHOLD_WITH_NO" => array(
		"en" => "Threshold %number%",
		"tw" => "臨界值%number%",
		"cn" => "临界值%number%"
	),
	"MAXIMUM" => array(
		"en" => "Maximum",
		"tw" => "最大值",
		"cn" => "最大值"
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

widget.gauge = {
	minChannelNumber: 1,
	maxChannelNumber: 1,

	// Event function
	settingCreated: function($content, settings){// setting == undefined mean is create not modify
		var dashboard = window.accounts[$("#dashboard-switch-handler").attr("account_uid")].dashboards[$("#dashboard-switch-handler").attr("dashboard_uid")];
		var isDark = dashboard.darkMode;

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
					}).css("paddingBottom", "10px").text("<?=$lang['WIDGET']['GAUGE']['DRAWING'][$language]?>")
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['GAUGE']['VALUE_MODE'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<select></select>").attr("id", "widget-draw-value-mode").css("width", "100%").append(
							$("<option></option>").val("1").text("<?=$lang['WIDGET']['GAUGE']['AVERAGE_VALUE'][$language]?>")
						).append(
							$("<option></option>").val("0").text("<?=$lang['WIDGET']['GAUGE']['LASTEST_VALUE'][$language]?>").attr("selected", true)
						)
					)
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['GAUGE']['MARKER'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<select></select>").attr("id", "widget-draw-marker").css("width", "100%").append(
							$("<option></option>").val("1").text("<?=$lang['WIDGET']['GAUGE']['ENABLE'][$language]?>").attr("selected", true)
						).append(
							$("<option></option>").val("0").text("<?=$lang['WIDGET']['GAUGE']['DISABLE'][$language]?>")
						)
					)
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").attr("colSpan", "999").html("&nbsp;")
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").attr({
						"colSpan": "999",
						"class": "content-title"
					}).css("paddingBottom", "10px").text("<?=$lang['WIDGET']['GAUGE']['THRESHOLD'][$language]?>")
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css({
						"padding": "3px 50px 3px 0"
					}).text("<?=$lang['WIDGET']['GAUGE']['MINIMUM'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<input/>").attr({
							"id": "widget-threshold-min",
							"type": "number",
							"placeholder": "0"
						}).css({
							"width": "100%",
							"boxSizing": "border-box"
						})
					)
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['GAUGE']['COLOR_WITH_NO'][$language]?>".replace("%number%", "1"))
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<select></select>").attr("id", "widget-threshold-color1").css("width", "100%").bind("change", function(){
							var $selectedOption = $(this).find("option:selected");

							$(this).css({
								"backgroundColor": $selectedOption.attr("backgroundColor"),
								"color": $selectedOption.attr("color")
							});
						}).append(
							$("<option></option>").css({"backgroundColor": "#FFF", "color": "#555"}).attr({"backgroundColor": isDark ? "#73BF69" : "#3EA856", "color": "#FFF"}).val("0").text("<?=$lang['WIDGET']['GAUGE']['GREEN'][$language]?>").attr("selected", true)
						).append(
							$("<option></option>").css({"backgroundColor": "#FFF", "color": "#555"}).attr({"backgroundColor": isDark ? "#EAB839" : "#F0C353", "color": "#555"}).val("1").text("<?=$lang['WIDGET']['GAUGE']['YELLOW'][$language]?>")
						).append(
							$("<option></option>").css({"backgroundColor": "#FFF", "color": "#555"}).attr({"backgroundColor": isDark ? "#F2495C" : "#D24B4C", "color": "#FFF"}).val("2").text("<?=$lang['WIDGET']['GAUGE']['RED'][$language]?>")
						)
					)
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css({
						"padding": "3px 50px 3px 0"
					}).text("<?=$lang['WIDGET']['GAUGE']['THRESHOLD_WITH_NO'][$language]?>".replace("%number%", "1"))
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<input/>").attr({
							"id": "widget-threshold-threshold1",
							"type": "number",
							"placeholder": "50"
						}).css({
							"width": "100%",
							"boxSizing": "border-box"
						})
					)
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['GAUGE']['COLOR_WITH_NO'][$language]?>".replace("%number%", "2"))
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<select></select>").attr("id", "widget-threshold-color2").css("width", "100%").bind("change", function(){
							var $selectedOption = $(this).find("option:selected");

							$(this).css({
								"backgroundColor": $selectedOption.attr("backgroundColor"),
								"color": $selectedOption.attr("color")
							});
						}).append(
							$("<option></option>").css({"backgroundColor": "#FFF", "color": "#555"}).attr({"backgroundColor": isDark ? "#73BF69" : "#3EA856", "color": "#FFF"}).val("0").text("<?=$lang['WIDGET']['GAUGE']['GREEN'][$language]?>")
						).append(
							$("<option></option>").css({"backgroundColor": "#FFF", "color": "#555"}).attr({"backgroundColor": isDark ? "#EAB839" : "#F0C353", "color": "#555"}).val("1").text("<?=$lang['WIDGET']['GAUGE']['YELLOW'][$language]?>").attr("selected", true)
						).append(
							$("<option></option>").css({"backgroundColor": "#FFF", "color": "#555"}).attr({"backgroundColor": isDark ? "#F2495C" : "#D24B4C", "color": "#FFF"}).val("2").text("<?=$lang['WIDGET']['GAUGE']['RED'][$language]?>")
						)
					)
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css({
						"padding": "3px 50px 3px 0"
					}).text("<?=$lang['WIDGET']['GAUGE']['THRESHOLD_WITH_NO'][$language]?>".replace("%number%", "2"))// 
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<input/>").attr({
							"id": "widget-threshold-threshold2",
							"type": "number",
							"placeholder": "80"
						}).css({
							"width": "100%",
							"boxSizing": "border-box"
						})
					)
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['GAUGE']['COLOR_WITH_NO'][$language]?>".replace("%number%", "3"))
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<select></select>").attr("id", "widget-threshold-color3").css("width", "100%").bind("change", function(){
							var $selectedOption = $(this).find("option:selected");

							$(this).css({
								"backgroundColor": $selectedOption.attr("backgroundColor"),
								"color": $selectedOption.attr("color")
							});
						}).append(
							$("<option></option>").css({"backgroundColor": "#FFF", "color": "#555"}).attr({"backgroundColor": isDark ? "#73BF69" : "#3EA856", "color": "#FFF"}).val("0").text("<?=$lang['WIDGET']['GAUGE']['GREEN'][$language]?>")
						).append(
							$("<option></option>").css({"backgroundColor": "#FFF", "color": "#555"}).attr({"backgroundColor": isDark ? "#EAB839" : "#F0C353", "color": "#555"}).val("1").text("<?=$lang['WIDGET']['GAUGE']['YELLOW'][$language]?>")
						).append(
							$("<option></option>").css({"backgroundColor": "#FFF", "color": "#555"}).attr({"backgroundColor": isDark ? "#F2495C" : "#D24B4C", "color": "#FFF"}).val("2").text("<?=$lang['WIDGET']['GAUGE']['RED'][$language]?>").attr("selected", true)
						)
					)
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css({
						"padding": "3px 50px 3px 0"
					}).text("<?=$lang['WIDGET']['GAUGE']['MAXIMUM'][$language]?>")// 
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<input/>").attr({
							"id": "widget-threshold-max",
							"type": "number",
							"placeholder": "100"
						}).css({
							"width": "100%",
							"boxSizing": "border-box"
						})
					)
				)
			)
		);

		if(settings){
			$("#widget-draw-value-mode").val(settings.draw.valueMode);
			$("#widget-draw-marker").val(settings.draw.marker == true ? "1" : "0");

			$("#widget-threshold-min").val(settings.threshold.min);
			$("#widget-threshold-color1").val(settings.threshold.color1);
			$("#widget-threshold-threshold1").val(settings.threshold.threshold1);
			$("#widget-threshold-color2").val(settings.threshold.color2);
			$("#widget-threshold-threshold2").val(settings.threshold.threshold2);
			$("#widget-threshold-color3").val(settings.threshold.color3);
			$("#widget-threshold-max").val(settings.threshold.max);
		}

		$("#widget-threshold-color1, #widget-threshold-color2, #widget-threshold-color3").each(function(){
			$(this).triggerHandler("change");
		});
	},
	channelUpdated: function($content, channels){
	},
	settingSaved: function(settings, channels){
		settings.draw = {
			"valueMode": $("#widget-draw-value-mode").val(),
			"marker": $("#widget-draw-marker").val() == "1" ? true : false
		};

		var sortArray = [];
		sortArray.push(parseFloat($("#widget-threshold-min").val() || 0));
		sortArray.push(parseFloat($("#widget-threshold-threshold1").val() || 50));
		sortArray.push(parseFloat($("#widget-threshold-threshold2").val() || 80));
		sortArray.push(parseFloat($("#widget-threshold-max").val() || 100));
		sortArray.sort(function(a, b) {
			return a - b;
		});

		settings.threshold = {
			"min": sortArray[0],
			"color1": $("#widget-threshold-color1").val(),
			"threshold1": sortArray[1],
			"color2": $("#widget-threshold-color2").val(),
			"threshold2": sortArray[2],
			"color3": $("#widget-threshold-color3").val(),
			"max": sortArray[3]
		};
	},
	widgetCreated: function($content, settings, channels){
		$content.append(
			$("<div></div>").attr("class", "chart").css({
				"width": "100%",
				"height": "100%"
			})
		).append(
			$("<div></div>").attr("class", "widget-loader")
		);

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
		this.drawChart($content, settings, channels);
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
		var isDark = dashboard.darkMode;

		// collect chart
		var dataSet = [];

		for(var accountUID in channels){
			for(var deviceUID in channels[accountUID]){
				for(var moduleUID in channels[accountUID][deviceUID]){
					for(var channel in channels[accountUID][deviceUID][moduleUID]){
						if(window.channels[accountUID][deviceUID][moduleUID][channel].length > 0){
							if(settings.draw.valueMode == "1"){
								var data = window.channels[accountUID][deviceUID][moduleUID][channel];
								var total = 0, average = 0, counter = 0;

								for(var i = 0; i < data.length; i++){
									if(data[i][1] == null || data[i][0] < maxTimestamp - (dashboard.dataLength * 1000)){continue;}

									total += data[i][1];
									counter++;
								}

								average = (total / counter) || 0;

								dataSet.push({
									data: [[0, average]],
									total: total,
									average: average,
									tipText: channels[accountUID][deviceUID][moduleUID][channel].fullName,
									unit: channels[accountUID][deviceUID][moduleUID][channel].unit
								});
							}
							else{
								var data = window.channels[accountUID][deviceUID][moduleUID][channel];

								dataSet.push({
									data: [[0, data[data.length - 1][1]]],
									date: data[data.length - 1][0],
									tipText: channels[accountUID][deviceUID][moduleUID][channel].fullName,
									unit: channels[accountUID][deviceUID][moduleUID][channel].unit
								});
							}

							break;
						}
					}
				}
			}
		}

		// Draw chart
		$.plot($content.find("> .chart"), dataSet, {
		    series: {
		        gauges: {
		            show: true,
				    layout: {
				        margin: 0,
						columns: 1,
						hMargin: 0,
						vMargin: 0,
				        square: false
				    },
					frame: {
						show: false
					},
		            cell: {
						border: {
							show: false
						},
						margin: 0
		            },
				    gauge: {
				        width: "auto", // a specified number, or 'auto'
				        startAngle: 0.9, // 0 - 2 factor of the radians
				        endAngle: 2.1, // 0 - 2 factor of the radians
				        min: settings.threshold.min,
				        max: settings.threshold.max,
						background: {
							color: isDark ? "#252525" : "#FFFFFF"
						},
				        border: {
							color: isDark ? "#515151" : "#D3D3D3",
				            width: 1
				        },
				        shadow: {
				            show: false
				        }
					},
		            label: {
		              show: false
		            },
				    value: {
				        show: true,
				        margin: "auto", // a specified number, or 'auto'
				        background: {
				            color: null,
				            opacity: 0
				        },
				        font: {
				            size: "auto", // a specified number, or 'auto'
				            family: "Arial,Helvetica,sans-serif"
				        },
				        color: null,
				        formatter: function(label, value) {
							return value === null ? "-" : formating2(value)
				        },
						tooltip: function(label, value, flotItem){
							var table = "<table>";
							table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['WIDGET']['GAUGE']['TIP']['CHANNEL'][$language]?></td><td>" + flotItem.tipText + "</td></tr>";
							if(typeof(flotItem.total) != "undefined"){
								table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['WIDGET']['GAUGE']['TIP']['TOTAL_VALUE'][$language]?></td><td>" + numberWithCommas(parseFloat((flotItem.total).toPrecision(7))) + "</td></tr>";
								table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['WIDGET']['GAUGE']['TIP']['AVERAGE_VALUE'][$language]?></td><td>" + numberWithCommas(parseFloat((flotItem.average).toPrecision(7))) + "</td></tr>";
							}
							else{
								var date = new Date(flotItem.date);
								table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['WIDGET']['GAUGE']['TIP']['TIME'][$language]?></td><td>" + date.getFullYear() + "/" + padding(date.getMonth() + 1, 2) + "/" + padding(date.getDate(), 2) + " " + padding(date.getHours(), 2) + ":" + padding(date.getMinutes(), 2) + ":" + padding(date.getSeconds(), 2) + "</td></tr>";
								table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['WIDGET']['GAUGE']['TIP']['VALUE'][$language]?></td><td>" + (value !== null ? parseFloat(value.toPrecision(7)) : "-") + "&nbsp;" + flotItem.unit + "</td></tr>";
							}

							table += "</table>";
							return table;
						}
				    },
				    threshold: {
				        show: settings.draw.marker,
				        width: 5, // a specified number, or 'auto'
				        label: {
				            show: settings.draw.marker,
				            margin: 5, // a specified number, or 'auto'
				            background: {
				                color: null,
				                opacity: 0
				            },
				            font: {
				                size: 13, // a specified number, or 'auto'
				                family: "Arial,Helvetica,sans-serif"
				            },
				            color: null,
				            formatter: function(value) {
				                return value;
				            }
				        },
				        values: [{
			                value: settings.threshold.threshold1,
			                color: {"0": {"0": "#3EA856", "1": "#F0C353", "2": "#D24B4C"}, "1": {"0": "#73BF69", "1": "#EAB839", "2": "#F2495C"}}[isDark ? "1" : "0"][settings.threshold.color1]
			            }, {
			                value: settings.threshold.threshold2,
			                color: {"0": {"0": "#3EA856", "1": "#F0C353", "2": "#D24B4C"}, "1": {"0": "#73BF69", "1": "#EAB839", "2": "#F2495C"}}[isDark ? "1" : "0"][settings.threshold.color2]
			            }, {
			                value: settings.threshold.max,
			                color: {"0": {"0": "#3EA856", "1": "#F0C353", "2": "#D24B4C"}, "1": {"0": "#73BF69", "1": "#EAB839", "2": "#F2495C"}}[isDark ? "1" : "0"][settings.threshold.color3]
			            }]
				    }
		        }
		    }
		});

		$content.find("> .widget-loader").hide();
	}
};