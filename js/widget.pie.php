<?php
global $language;

$lang["WIDGET"]["PIE"] = array(
	"DRAWING" => array(
		"en" => "Drawing",
		"tw" => "繪圖",
		"cn" => "绘图"
	),
	"DOUNT_DOLE" => array(
		"en" => "Donut Hole",
		"tw" => "顯示空心",
		"cn" => "显示空心"
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
	"LEGEND" => array(
		"en" => "Legend",
		"tw" => "圖例",
		"cn" => "图例"
	),
	"LOCATION" => array(
		"en" => "Location",
		"tw" => "顯示位置",
		"cn" => "显示位置"
	),
	"RIGHT" => array(
		"en" => "Right",
		"tw" => "右方",
		"cn" => "右方"
	),
	"BOTTOM" => array(
		"en" => "Bottom",
		"tw" => "下方",
		"cn" => "下方"
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
		),
		"PERCENTAGE"  => array(
			"en" => "Percentage:",
			"tw" => "百分比:",
			"cn" => "百分比:"
		)
	)
);
?>

widget.pie = {
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
					}).css("paddingBottom", "10px").text("<?=$lang['WIDGET']['PIE']['DRAWING'][$language]?>")
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['PIE']['DOUNT_DOLE'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<select></select>").attr("id", "widget-draw-donut-hole").css("width", "100%").append(
							$("<option></option>").val("1").text("<?=$lang['WIDGET']['PIE']['ENABLE'][$language]?>").attr("selected", true)
						).append(
							$("<option></option>").val("0").text("<?=$lang['WIDGET']['PIE']['DISABLE'][$language]?>")
						)
					)
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['PIE']['VALUE_MODE'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<select></select>").attr("id", "widget-draw-value-mode").css("width", "100%").append(
							$("<option></option>").val("1").text("<?=$lang['WIDGET']['PIE']['AVERAGE_VALUE'][$language]?>")
						).append(
							$("<option></option>").val("0").text("<?=$lang['WIDGET']['PIE']['LASTEST_VALUE'][$language]?>").attr("selected", true)
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
					}).css("paddingBottom", "10px").text("<?=$lang['WIDGET']['PIE']['LEGEND'][$language]?>")
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css({
						"padding": "3px 50px 3px 0"
					}).text("<?=$lang['WIDGET']['PIE']['LOCATION'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<select></select>").attr("id", "widget-legend-show").css("width", "100%").append(
							$("<option></option>").val("2").text("<?=$lang['WIDGET']['PIE']['RIGHT'][$language]?>")
						).append(
							$("<option></option>").val("1").text("<?=$lang['WIDGET']['PIE']['BOTTOM'][$language]?>")
						).append(
							$("<option></option>").val("0").text("<?=$lang['WIDGET']['PIE']['DISABLE'][$language]?>").attr("selected", true)
						)
					)
				)
			)
		);
		
		if(settings){
			$("#widget-draw-donut-hole").val(settings.draw.donutHole);
			$("#widget-draw-value-mode").val(settings.draw.valueMode);
			$("#widget-legend-show").val(settings.legend.show);
		}
	},
	channelUpdated: function($content, channels){
	},
	settingSaved: function(settings, channels){
		settings.draw = {
			"donutHole": $("#widget-draw-donut-hole").val(),
			"valueMode": $("#widget-draw-value-mode").val()
		};

		settings.legend = {
			"show":$("#widget-legend-show").val()
		};
	},
	widgetCreated: function($content, settings, channels){
		$content.append(
			$("<div></div>").attr("class", "chart").css({
				"width": "100%",
				"height": "100%",
				"float": "left"
			})
		).append(
			$("<div></div>").attr("class", "legend").hide()
		).append(
			$("<div></div>").attr("class", "widget-loader")
		);

		this.adjustLegendPosition($content, settings, channels);

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
		this.adjustLegendPosition($content, settings, channels);
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

		for(var i = 0; i < channels.order.length; i++){
			var accountUID = channels.order[i].accountUID;
			var deviceUID = channels.order[i].deviceUID;
			var moduleUID = channels.order[i].moduleUID;
			var channel = channels.order[i].channel;

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
						label: channels[accountUID][deviceUID][moduleUID][channel].shortName,
						data: total,
						total: total,
						average: average,
						tipText: channels[accountUID][deviceUID][moduleUID][channel].fullName,
						unit: channels[accountUID][deviceUID][moduleUID][channel].unit
					});
				}
				else{
					var data = window.channels[accountUID][deviceUID][moduleUID][channel];
					dataSet.push({
						label: channels[accountUID][deviceUID][moduleUID][channel].shortName,
						data: data[data.length - 1][1],
						date: data[data.length - 1][0],
						tipText: channels[accountUID][deviceUID][moduleUID][channel].fullName,
						unit: channels[accountUID][deviceUID][moduleUID][channel].unit
					});
				}
			}
		}

		if(dataSet.length <= 0){
			dataSet.push({
				data: []
			});
		}

		// Draw chart

		var innerRadius = 0, labelRadius = 0.5;
		if(settings.draw.donutHole == "1"){
			innerRadius = 0.5;
			labelRadius = 0.75;
		}
		var option = {
			series: {
				pie: {
					innerRadius: innerRadius,
					show: true,
					radius: 1,
					label: {
						show: true,
						radius: labelRadius,
						formatter: function(label, series) {
							return "<div style='text-align:center;font-size:smaller;color:#ffffff;'>" + Math.round(series.percent) + "%</div>";
						},
						threshold: 0.1
					}
				}
			},
			legend: {
				show: false
			},
			grid: {
				hoverable: true,
			},
			legend: {
				show: settings.legend.show > 0,
				labelFormatter: function(label, series) {
					return '<div style="white-space:nowrap;" title="' + series.tipText + '">' + label + '</div>';
				},
				container: $content.find("> .legend"),
				noColumns: settings.legend.show == 2 ? 1 : settings.legend.show == 1 ? 0 : 0,

				labelBoxBorderColor: "transparent",
				backgroundColor: null,
				backgroundOpacity: 0
			},
			tooltip: true,
			tooltipOpts: {
				content: function(label, xval, yval, flotItem){
					var table = "<table>";
					table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['WIDGET']['PIE']['TIP']['CHANNEL'][$language]?></td><td>" + flotItem.series.tipText + "</td></tr>";
					if(typeof(flotItem.series.total) != "undefined"){
						table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['WIDGET']['PIE']['TIP']['TOTAL_VALUE'][$language]?></td><td>" + numberWithCommas(parseFloat((flotItem.series.total).toPrecision(7))) + "</td></tr>";
						table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['WIDGET']['PIE']['TIP']['AVERAGE_VALUE'][$language]?></td><td>" + numberWithCommas(parseFloat((flotItem.series.average).toPrecision(7))) + "</td></tr>";
					}
					else{
						var date = new Date(flotItem.series.date);
						table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['WIDGET']['PIE']['TIP']['TIME'][$language]?></td><td>" + date.getFullYear() + "/" + padding(date.getMonth() + 1, 2) + "/" + padding(date.getDate(), 2) + " " + padding(date.getHours(), 2) + ":" + padding(date.getMinutes(), 2) + ":" + padding(date.getSeconds(), 2) + "</td></tr>";
						table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['WIDGET']['PIE']['TIP']['VALUE'][$language]?></td><td>" + parseFloat(yval.toPrecision(7)) + "&nbsp;" + flotItem.series.unit + "</td></tr>";
					}
					table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['WIDGET']['PIE']['TIP']['PERCENTAGE'][$language]?></td><td>" + Math.round(flotItem.series.percent) + "%</td></tr>";
					table += "</table>";
					return table;
				}
			}
		}

		if(isDark){ // black
			option.grid["color"] = "#CCCCCC";
			option.colors = ["#7EB26D", "#EAB839", "#6ED0E0", "#EF843C", "#E24D42", "#1F78C1", "#BA43A9", "#705DA0", "#508642", "#CCA300", "#447EBC"];
			option.series.pie.stroke = {
				color: "#252525"
			}
		}

		$.plot($content.find("> .chart"), dataSet, option);
		$content.find(".pieLabel").css("pointer-events","none");
		$content.find("> .widget-loader").hide();
	},
	adjustLegendPosition: function($content, settings, channels){
		if(settings.legend.show == 1){
			$content.find("> div.chart").css({
				"width": "100%",
				"height": "calc(100% - 25px)"
			});

			$content.find("> div.legend").css({
				"height": "25px",
				"float": "left",
				"clear": "both",
				"margin-left": "0px",
				"margin-top": "5px"
			}).show();
		}
		else if(settings.legend.show == 2){
			$content.find("> div.chart").css({
				"width": "calc(100% - 105px)",
				"height": "100%"
			});

			$content.find("> div.legend").css({
				"width": "100px",
				"float": "left",
				"clear": "none",
				"margin-left": "5px",
				"margin-top": "0px"
			}).show();
		}
		else{
			$content.find("> div.chart").css({
				"width": "100%",
				"height": "100%"
			});

			$content.find("> div.legend").hide();
		}
	}
};