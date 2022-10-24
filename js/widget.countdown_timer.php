<?php
global $language;

$lang["WIDGET"]["COUNTDOWN_TIMER"] = array(
	"END_TIME" => array(
		"en" => "End Time",
		"tw" => "結束時間",
		"cn" => "结束时间"
	),
	"DATE" => array(
		"en" => "Date",
		"tw" => "日期",
		"cn" => "日期"
	),
	"TIME" => array(
		"en" => "Time",
		"tw" => "時間",
		"cn" => "时间"
	),
	"TIMEOUT" => array(
		"en" => "Timeout",
		"tw" => "逾時",
		"cn" => "逾时"
	),
	"DAYS" => array(
		"en" => "Days",
		"tw" => "天",
		"cn" => "天"
	),
	"SEC" => array(
		"en" => "Sec",
		"tw" => "秒",
		"cn" => "秒"
	)
);
?>

widget.countdown_timer = {
	minChannelNumber: 0,
	maxChannelNumber: 0,
	defaultWidth: 4,
	defaultHeight: 3,

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
					}).css("paddingBottom", "10px").text("<?=$lang['WIDGET']['COUNTDOWN_TIMER']['END_TIME'][$language]?>")
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['COUNTDOWN_TIMER']['DATE'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<input type='date'/>").attr({"id": "widget-date", "required": true}).css({"width": "100%", "font": "inherit"})
					)
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['COUNTDOWN_TIMER']['TIME'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<input type='time'/>").attr({"id": "widget-time", "required": true}).css({"width": "100%", "font": "inherit"})
					)
				)
			)
		);

		var date;

		if(settings){
			date = new Date(settings.dateTime);
		}
		else{
 			date = new Date();
		}

		$("#widget-date").val(date.getFullYear() + "-" + this.padLeft(date.getMonth() + 1, 2) + "-" + this.padLeft(date.getDate(), 2));
		$("#widget-time").val(this.padLeft(date.getHours(),2) + ":" + this.padLeft(date.getMinutes(), 2));
	},
	channelUpdated: function($content, channels){
	},
	settingSaved: function(settings, channels){
		var dateArray = $("#widget-date").val().split("-");
		var timeArray = $("#widget-time").val().split(":");
		var date = new Date(parseInt(dateArray[0], 10), parseInt(dateArray[1], 10) - 1, parseInt(dateArray[2], 10), parseInt(timeArray[0], 10), parseInt(timeArray[1], 10));
		settings.dateTime = date.getTime();
	},
	widgetCreated: function($content, settings){
		$content.append(
			$("<div></div>").attr("class", "clock-container").css({
				"position": "absolute",
				"top": "0",
				"bottom": "0",
				"left": "0",
				"right": "0",
				"padding": "10px"
			}).append(
				$("<div></div>").attr("class", "clock").css({
					"display": "inline-block",
					"fontWeight": "bold",
					"transformOrigin": "left top",
					"transform": "scale(0)"
				})
			)
		);

		$content.data("clock", $content.find(".clock"));
		this.widgetUpdated($content, settings);
	},
	widgetUpdated: function($content, settings){
		var that = this;
		this.setText($content, settings);
		clearInterval($content.attr("clockthread"));
		$content.attr("clockthread", setInterval(function(){that.setText($content, settings);}, 100, $content, settings));
	},
	widgetRemoved: function($content, settings){
		clearInterval($content.attr("clockthread"));
	},
	dataUpdated: function($content, settings){
	},
	dragstop: function($content, settings){
	},
	resizestart: function($content, settings){
	},
	resizing: function($content, settings){
		this.adjustValueSize($content);
	},
	resizestop: function($content, settings){
		this.adjustValueSize($content);
	},

	// Custom function
	setText: function($content, settings){
		var dashboard = window.accounts[$("#dashboard-switch-handler").attr("account_uid")].dashboards[$("#dashboard-switch-handler").attr("dashboard_uid")];
		var isDark = dashboard.darkMode;

		var remainingTime = settings.dateTime - (new Date()).getTime();
		if(remainingTime < 0){
			$content.find(".clock").empty().append(
				$("<div></div>").css({
					"font-size": "5px",
					"text-align": "center",
					"color": {"0": "#D24B4C", "1": "#F2495C"}[isDark ? "1" : "0"]
				}).html(this.timeToString(remainingTime))
			);
		}
		else{
			$content.find(".clock").empty().append(
				$("<div></div>").css({
					"font-size": "5px",
					"text-align": "center"
				}).html(this.timeToString(remainingTime))
			);
		}
		this.adjustValueSize($content);
	},
	adjustValueSize: function($content){
		var $value = $content.data("clock");
		var $container = $value.parent();

		var ratio = $container.width() / $value.width();

		if(ratio * $value.height() > $container.height()){
			ratio = $container.height() / $value.height();
		}

		$value.css("transform", "scale(" + ratio + ") translate(" + ((($container.width() / 2) - ($value.width() * ratio / 2)) / ratio) + "px, " + ((($container.height() / 2) - ($value.height() * ratio / 2)) / ratio) + "px)");

		return $value;
	},
	timeToString: function(t){
		if (t < 0) {
			return "<?=$lang['WIDGET']['COUNTDOWN_TIMER']['TIMEOUT'][$language]?>";
		}

		var cd = 24 * 60 * 60 * 1000,
			ch = 60 * 60 * 1000,
			cm = 60 * 1000,

			d = Math.floor(t / cd),
			h = Math.floor((t - d * cd) / ch),
			m = Math.floor((t - d * cd - h * ch) / 60000),
			s = Math.floor((t - d * cd - h * ch - m * cm) / 1000);
		if (s === 60) {
			m++;
			s = 0;
		}
		if (m === 60) {
			h++;
			m = 0;
		}
		if (h === 24) {
			d++;
			h = 0;
		}
		if (d > 0){
			return d + " <?=$lang['WIDGET']['COUNTDOWN_TIMER']['DAYS'][$language]?><br>" + this.padLeft(h, 2) + ":" + this.padLeft(m, 2) + ":" + this.padLeft(s, 2);
		}
		else {
			if (h > 0){
				return this.padLeft(h, 2) + ":" + this.padLeft(m, 2) + ":" + this.padLeft(s, 2);
			}
			else {
				if (m > 0){
					return this.padLeft(m, 2) + ":" + this.padLeft(s, 2);
				}
				else{
					return this.padLeft(s, 2) + " <?=$lang['WIDGET']['COUNTDOWN_TIMER']['SEC'][$language]?>";
				}
			}
		}
	},
	padLeft: function(str, lenght){
		if((str.toString()).length >= lenght){
			return str;
		}
		else{
			return this.padLeft("0" + str,lenght);
		}
	}
};
