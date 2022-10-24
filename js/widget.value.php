<?php
global $language;

$lang["WIDGET"]["VALUE"] = array(
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

widget.value = {
	minChannelNumber: 1,
	maxChannelNumber: 1,

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
					}).css("paddingBottom", "10px").text("<?=$lang['WIDGET']['VALUE']['DRAWING'][$language]?>")
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['VALUE']['FILL_OPACITY'][$language]?>")
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
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['VALUE']['FILL_GRADIENT'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<select></select>").attr("id", "widget-draw-fill-gradient").css("width", "100%").append(
							$("<option></option>").val("1").text("<?=$lang['WIDGET']['VALUE']['ENABLE'][$language]?>").attr("selected", true)
						).append(
							$("<option></option>").val("0").text("<?=$lang['WIDGET']['VALUE']['DISABLE'][$language]?>")
						)
					)
				)
			)
		);

		if(settings){
			$("#widget-draw-fill-opacity").val(settings.draw.fillOpacity);
			$("#widget-draw-fill-gradient").val(settings.draw.fillGradient == true ? "1" : "0");
		}
	},
	channelUpdated: function($content, channels){
	},
	settingSaved: function(settings, channels){
		settings.draw = {
			"fillOpacity": parseFloat($("#widget-draw-fill-opacity").val()),
			"fillGradient": $("#widget-draw-fill-gradient").val() == "1" ? true : false,
		};
	},
	widgetCreated: function($content, settings, channels){
		$content.append(
			$("<div></div>").attr("class", "chart").css({
				"width": "100%",
				"height": "100%"
			})
		).append(
			$("<div></div>").attr("class", "value-container").css({
				"position": "absolute",
				"top": "0",
				"bottom": "0",
				"left": "0",
				"right": "0",
				"padding": "20px 30px"
			}).append(
				$("<div></div>").attr("class", "value").css({
					"display": "inline-block",
					"fontWeight": "bold",
					"transformOrigin": "left top",
					"transform": "scale(0)"
				}).bind("mousemove", function(event){
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
							var data = dataSet[0].data[dataSet[0].data.length - 1];
							var date = new Date(data[0]);
							var table = "<table>";
							table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['WIDGET']['VALUE']['TIP']['CHANNEL'][$language]?></td><td>" + dataSet[0].tipText + "</td></tr>";
							table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['WIDGET']['VALUE']['TIP']['TIME'][$language]?></td><td>" + date.getFullYear() + "/" + padding(date.getMonth() + 1, 2) + "/" + padding(date.getDate(), 2) + " " + padding(date.getHours(), 2) + ":" + padding(date.getMinutes(), 2) + ":" + padding(date.getSeconds(), 2) + "</td></tr>";
							table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['WIDGET']['VALUE']['TIP']['VALUE'][$language]?></td><td>" + (data[1] !== null ? parseFloat(data[1].toPrecision(7)) : "-") + "&nbsp;" + dataSet[0].unit + "</td></tr>";
							table += "</table>";
							return table;
						})($(this).data("dataSet"))).show();

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
				})
			)
		).append(
			$("<div></div>").attr("class", "widget-loader")
		);

		$content.data("value", $content.find(".value"));

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
		this.adjustValueSize($content);
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
							dataSet.push({
								data: window.channels[accountUID][deviceUID][moduleUID][channel],
								lines: {
									steps: channel.match(/^(DI|DO|CI|CO)+\d/) ? true : false
								},
								tipText: channels[accountUID][deviceUID][moduleUID][channel].fullName,
								unit: channels[accountUID][deviceUID][moduleUID][channel].unit
							});

//							dataSet.push({
//					            points: {
//					                show: true
//					            },
//								color: "#F0C353",
//								data: [window.channels[accountUID][deviceUID][moduleUID][channel][window.channels[accountUID][deviceUID][moduleUID][channel].length - 1]],
//								lines: {
//									steps: channel.match(/^(DI|DO|CI|CO)+\d/) ? true : false
//								},
//								tipText: channels[accountUID][deviceUID][moduleUID][channel].fullName
//							});

							break;
						}
					}
				}
			}
		}

		if(dataSet.length <= 0){
			dataSet.push({
				data: []
			});
		}

		// Draw chart

		$.plot($content.find("> .chart"), dataSet, {
	        series: {
	            lines: {
	                show: true,
					fill: settings.draw.fillOpacity,
					fillColor: settings.draw.fillGradient == true ? { colors: [ { opacity: 0 }, { opacity: settings.draw.fillOpacity } ] } : { colors: [ { opacity: settings.draw.fillOpacity }, { opacity: settings.draw.fillOpacity } ] },
					lineWidth: 0
	            },
	            points: {
	                show: false
	            },
	            grow: {
	                active: true
	            },
				shadowSize: 0
			},
			colors: [isDark ? "#7EB26D" : "#F0C353"],
			xaxis: {
				show: false,
				mode: "time",
				timezone: "browser",
				tickLength: 0,
//				timeformat: "%H:%M",
				min: maxTimestamp - (dashboard.dataLength * 1000),
				max: maxTimestamp
			},
			yaxis: {
				show: false,
//				position: "right",
				tickFormatter: function (val, axis) {
					var formatter = formating(val);
					var string = formatter.toString();

					return string;
				}
			},
	        grid: {
	            hoverable: false,
//		            clickable: true,
	    		borderWidth: {
					top: 0, right: 0, bottom: 0, left: 0
				},
			    minBorderMargin: 0
	        },
	        tooltip: false
		});

		var $value = $content.find(".value").css("color", "").hide();

		$value.triggerHandler("mouseleave");// hide tip

		if(dataSet[0].data.length > 0){
			if(dataSet[0].data[dataSet[0].data.length - 1][1] != null){// Online
				if(dataSet[0].lines.steps == true){// DI, DO, CI, CO
					if(dataSet[0].data[dataSet[0].data.length - 1][1] > 0){
						$value.text("ON").css("color", "#3EA856");
					}
					else{
						$value.text("OFF").css("color", "#D24B4C");
					}
				}
				else{// AI, AO, RI, RO
					$value.text(
						numberWithCommas(parseFloat(dataSet[0].data[dataSet[0].data.length - 1][1].toPrecision(7)))
						//formating2(dataSet[0].data[dataSet[0].data.length - 1][1])
					)
				}
			}
			else{
				$value.text("-");
			}

			$value.data("dataSet", dataSet);
		}
		else{
//				$value.text("-");
		}

		this.adjustValueSize($content).show();

		$content.find("> .widget-loader").hide();
	},

	adjustValueSize: function($content){
		var $value = $content.data("value");
		var $container = $value.parent();

		var ratio = $container.width() / $value.width();

		if(ratio * $value.height() > $container.height()){
			ratio = $container.height() / $value.height();
		}

		$value.css("transform", "scale(" + ratio + ") translate(" + ((($container.width() / 2) - ($value.width() * ratio / 2)) / ratio) + "px, " + ((($container.height() / 2) - ($value.height() * ratio / 2)) / ratio) + "px)");

		return $value;
	}
};