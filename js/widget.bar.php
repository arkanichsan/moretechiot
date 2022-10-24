<?php
global $language;

$lang["WIDGET"]["BAR"] = array(
	"DRAWING" => array(
		"en" => "Drawing",
		"tw" => "繪圖",
		"cn" => "绘图"
	),
	"FILL_OPACITY" => array(
		"en" => "Fill Opacity",
		"tw" => "不透明度",
		"cn" => "不透明度"
	),
	"FILL_GRADIENT" => array(
		"en" => "Fill Gradient",
		"tw" => "漸層",
		"cn" => "渐层"
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
	"LINE_WIDTH" => array(
		"en" => "Line Width",
		"tw" => "線寬",
		"cn" => "线宽"
	),
	"STACKING" => array(
		"en" => "Stacking",
		"tw" => "堆疊",
		"cn" => "堆栈"
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
	"Y_AXIS" => array(
		"en" => "Y-Axis",
		"tw" => "Y軸標示",
		"cn" => "Y轴标示"
	),
	"LEFT" => array(
		"en" => "Left",
		"tw" => "左方",
		"cn" => "左方"
	),
	"MAXIMUM" => array(
		"en" => "Maximum",
		"tw" => "最大值",
		"cn" => "最大值"
	),
	"MINIMUM" => array(
		"en" => "Minimum",
		"tw" => "最小值",
		"cn" => "最小值"
	),
	"AUTO" => array(
		"en" => "Auto",
		"tw" => "自動設定",
		"cn" => "自动设定"
	),
	"TIP" => array(
		"CHANNEL" => array(
			"en" => "Channel:",
			"tw" => "通道:",
			"cn" => "通道:"
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

widget.bar = {
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
					}).css("paddingBottom", "10px").text("<?=$lang['WIDGET']['BAR']['DRAWING'][$language]?>")
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['BAR']['FILL_OPACITY'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<select></select>").attr("id", "widget-draw-fill-opacity").css("width", "100%").append(
							$("<option></option>").val("0").text("0%")
						).append(
							$("<option></option>").val("0.1").text("10%")
						).append(
							$("<option></option>").val("0.2").text("20%").attr("selected", true)
						).append(
							$("<option></option>").val("0.3").text("30%")
						).append(
							$("<option></option>").val("0.4").text("40%")
						).append(
							$("<option></option>").val("0.5").text("50%")
						).append(
							$("<option></option>").val("0.6").text("60%")
						).append(
							$("<option></option>").val("0.7").text("70%")
						).append(
							$("<option></option>").val("0.8").text("80%")
						).append(
							$("<option></option>").val("0.9").text("90%")
						).append(
							$("<option></option>").val("1").text("100%")
						)
					)
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['BAR']['FILL_GRADIENT'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<select></select>").attr("id", "widget-draw-fill-gradient").css("width", "100%").append(
							$("<option></option>").val("1").text("<?=$lang['WIDGET']['BAR']['ENABLE'][$language]?>").attr("selected", true)
						).append(
							$("<option></option>").val("0").text("<?=$lang['WIDGET']['BAR']['DISABLE'][$language]?>")
						)
					)
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['BAR']['LINE_WIDTH'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<select></select>").attr("id", "widget-draw-line-width").css("width", "100%").append(
							$("<option></option>").val("0").text("0px")
						).append(
							$("<option></option>").val("1").text("1px")
						).append(
							$("<option></option>").val("2").text("2px").attr("selected", true)
						).append(
							$("<option></option>").val("3").text("3px")
						).append(
							$("<option></option>").val("5").text("5px")
						).append(
							$("<option></option>").val("10").text("10px")
						)
					)
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['BAR']['STACKING'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<select></select>").attr("id", "widget-draw-stack").css("width", "100%").append(
							$("<option></option>").val("1").text("<?=$lang['WIDGET']['BAR']['ENABLE'][$language]?>")
						).append(
							$("<option></option>").val("0").text("<?=$lang['WIDGET']['BAR']['DISABLE'][$language]?>").attr("selected", true)
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
					}).css("paddingBottom", "10px").text("<?=$lang['WIDGET']['BAR']['LEGEND'][$language]?>")
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css({
						"padding": "3px 50px 3px 0"
					}).text("<?=$lang['WIDGET']['BAR']['LOCATION'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<select></select>").attr("id", "widget-legend-show").css("width", "100%").append(
							$("<option></option>").val("2").text("<?=$lang['WIDGET']['BAR']['RIGHT'][$language]?>")
						).append(
							$("<option></option>").val("1").text("<?=$lang['WIDGET']['BAR']['BOTTOM'][$language]?>")
						).append(
							$("<option></option>").val("0").text("<?=$lang['WIDGET']['BAR']['DISABLE'][$language]?>").attr("selected", true)
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
					}).css("paddingBottom", "10px").text("<?=$lang['WIDGET']['BAR']['Y_AXIS'][$language]?>")
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css({
						"padding": "3px 50px 3px 0"
					}).text("<?=$lang['WIDGET']['BAR']['LOCATION'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<select></select>").attr("id", "widget-yaxis-position").css("width", "100%").append(
							$("<option></option>").val("right").text("<?=$lang['WIDGET']['BAR']['RIGHT'][$language]?>").attr("selected", true)
						).append(
							$("<option></option>").val("left").text("<?=$lang['WIDGET']['BAR']['LEFT'][$language]?>")
						)
					)
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css({
						"padding": "3px 50px 3px 0"
					}).text("<?=$lang['WIDGET']['BAR']['MAXIMUM'][$language]?>")// 
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<input/>").attr({
							"id": "widget-yaxis-max",
							"type": "text",
							"placeholder": "<?=$lang['WIDGET']['BAR']['AUTO'][$language]?>"
						}).css({
							"width": "100%",
							"boxSizing": "border-box"
						})
					)
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css({
						"padding": "3px 50px 3px 0"
					}).text("<?=$lang['WIDGET']['BAR']['MINIMUM'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<input/>").attr({
							"id": "widget-yaxis-min",
							"type": "text",
							"placeholder": "<?=$lang['WIDGET']['BAR']['AUTO'][$language]?>"
						}).css({
							"width": "100%",
							"boxSizing": "border-box"
						})
					)
				)
			)
		);

		if(settings){
			$("#widget-draw-fill-opacity").val(settings.draw.fillOpacity);
			$("#widget-draw-fill-gradient").val(settings.draw.fillGradient == true ? "1" : "0");
			$("#widget-draw-line-width").val(settings.draw.lineWidth);
			$("#widget-draw-stack").val(settings.draw.stack == true ? "1" : "0");
			
			$("#widget-legend-show").val(settings.legend.show);

			$("#widget-yaxis-position").val(settings.yaxis.position);
			$("#widget-yaxis-max").val(settings.yaxis.max);
			$("#widget-yaxis-min").val(settings.yaxis.min);
		}
	},
	channelUpdated: function($content, channels){
	},
	settingSaved: function(settings, channels){
		settings.draw = {
			"fillOpacity": parseFloat($("#widget-draw-fill-opacity").val()),
			"fillGradient": $("#widget-draw-fill-gradient").val() == "1" ? true : false,
			"lineWidth": parseInt($("#widget-draw-line-width").val(), 10),
			"stack": $("#widget-draw-stack").val() == "1" ? true : false
		};
		
		settings.legend = {
			"show": parseInt($("#widget-legend-show").val(), 10)
		};

		var max = parseFloat($("#widget-yaxis-max").val());
		var min = parseFloat($("#widget-yaxis-min").val());

		settings.yaxis = {
			"position": $("#widget-yaxis-position").val(),
			"max": isNaN(max) ? null : max,
			"min": isNaN(min) ? null : min
		};

		if(settings.yaxis.max != null && settings.yaxis.min != null){
			if(settings.yaxis.max < settings.yaxis.min){
				var temp = settings.yaxis.max;
				settings.yaxis.max = settings.yaxis.min;
				settings.yaxis.min = temp;
			}
		}
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
				dataSet.push({
					label: channels[accountUID][deviceUID][moduleUID][channel].shortName,
					data: window.channels[accountUID][deviceUID][moduleUID][channel],
					tipText: channels[accountUID][deviceUID][moduleUID][channel].fullName,
					unit: channels[accountUID][deviceUID][moduleUID][channel].unit
				});
			}
		}

		// Draw chart
		var option = {
	        series: {
				stack: settings.draw.stack,
				bars: {
	                show: true,
					fill: settings.draw.fillOpacity,
					fillColor: settings.draw.fillGradient == true ? { colors: [ { opacity: settings.draw.fillOpacity }, { opacity: 0 } ] } : { colors: [ { opacity: settings.draw.fillOpacity }, { opacity: settings.draw.fillOpacity } ] },
					lineWidth: settings.draw.lineWidth,
					barWidth: 3000,
					align: "center"
				},
				grow: {
					active: true
				},
				shadowSize: 0
			},
			xaxis: {
				mode: "time",
				timezone: "browser",
				timeformat: "%H:%M:%S",
				min: maxTimestamp - (dashboard.dataLength * 1000),
				max: maxTimestamp
			},
			yaxis: {
				position: settings.yaxis.position,
				max: settings.yaxis.max,
				min: settings.yaxis.min,
				tickFormatter: function (val, axis) {
					if(isNaN(val)){
						return val;
					}

					var formatter = formating(val);
					var string = formatter.toString();

					return string;
				}
			},
			grid: {
				hoverable: true,
				borderWidth: {
					top: 0, right: 0, bottom: 0, left: 0
				}
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
					var date = new Date(xval);
					var yval = parseFloat((yval).toPrecision(7));
					var table = "<table>";
					table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['WIDGET']['BAR']['TIP']['CHANNEL'][$language]?></td><td>" + flotItem.series.tipText + "</td></tr>";
					table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['WIDGET']['BAR']['TIP']['TIME'][$language]?></td><td>" + date.getFullYear() + "/" + padding(date.getMonth() + 1, 2) + "/" + padding(date.getDate(), 2) + " " + padding(date.getHours(), 2) + ":" + padding(date.getMinutes(), 2) + ":" + padding(date.getSeconds(), 2) + "</td></tr>";
					table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['WIDGET']['BAR']['TIP']['VALUE'][$language]?></td><td>" + numberWithCommas(yval) + "&nbsp;" + flotItem.series.unit + "</td></tr>";
					table += "</table>";
					return table;
				}
			}
		}

		if(isDark){ // black
			option.grid["color"] = "#CCCCCC";
			option.colors = ["#7EB26D", "#EAB839", "#6ED0E0", "#EF843C", "#E24D42", "#1F78C1", "#BA43A9", "#705DA0", "#508642", "#CCA300", "#447EBC"];
			option["xaxis"]["font"] = {
				"color": "#CCCCCC"
			};
			option["yaxis"]["font"] = {
				"color": "#CCCCCC"
			};
		}

		$.plot($content.find("> .chart"), dataSet, option);
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