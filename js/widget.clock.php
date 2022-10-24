<?php
global $language;

$lang["WIDGET"]["CLOCK"] = array(
	"DISPLAY" => array(
		"en" => "Display",
		"tw" => "顯示",
		"cn" => "显示"
	),
	"DATE_FORMAT" => array(
		"en" => "Date Format",
		"tw" => "日期格式",
		"cn" => "日期格式"
	),
	"24_HOUR_TIME" => array(
		"en" => "24-Hour Time",
		"tw" => "24時制",
		"cn" => "24时制"
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
	"TIME_ZONE" => array(
		"en" => "Time Zone",
		"tw" => "時區",
		"cn" => "时区"
	),
	"BROWSER_TIME" => array(
		"en" => "Browser Time Zone",
		"tw" => "瀏覽器時區",
		"cn" => "浏览器时区"
	)
);
?>

widget.clock = {
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
					}).css("paddingBottom", "10px").text("<?=$lang['WIDGET']['CLOCK']['DISPLAY'][$language]?>")
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['CLOCK']['DATE_FORMAT'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<select></select>").attr("id", "DateFormat").css("width", "100%")
					)
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['CLOCK']['24_HOUR_TIME'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<select></select>").attr("id", "ck_24h_format").css("width", "100%").append(
							$("<option></option>").val("enable").text("<?=$lang['WIDGET']['CLOCK']['ENABLE'][$language]?>").attr("selected", true)
						).append(
							$("<option></option>").val("disable").text("<?=$lang['WIDGET']['CLOCK']['DISABLE'][$language]?>")
						)
					)
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['CLOCK']['TIME_ZONE'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<select></select>").attr("id", "TimeZoneSetting").css("width", "100%")
					)
				)
			)
		);
		createDateFormatoption();
		createUTC_option();
		
		if(settings){
			$("#TimeZoneSetting").val(settings.draw.TimeZone);
			$("#ck_24h_format").val(settings.draw.hour_format);
			$("#DateFormat").val(settings.draw.DateFormat);
		}
	},
	channelUpdated: function($content, channels){
	},
	settingSaved: function(settings, channels){
		settings.draw = {
			"TimeZone":$("#TimeZoneSetting").val(),
			"hour_format":$("#ck_24h_format").val(),
			"DateFormat":$("#DateFormat").val()
		};
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
		this.TimeText($content, settings);
		clearInterval($content.attr("clockthread"));
		$content.attr("clockthread", setInterval(this.TimeText, 100, $content, settings));
	},
	widgetUpdated: function($content, settings){
		this.TimeText($content, settings);
		clearInterval($content.attr("clockthread"));
		$content.attr("clockthread", setInterval(this.TimeText, 100, $content, settings));
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
	TimeText: function($content, settings){
		var currentArrayinfo = GetTimeZone(settings.draw["TimeZone"],settings.draw["hour_format"],settings.draw["DateFormat"]);
		var currentDateTime = currentArrayinfo[0];
		var currentDate = currentArrayinfo[1];
		
		$content.find(".clock").html("");
		$content.find(".clock").append($("<div></div>").css({"font-size": "15px","text-align":"center"}).text(currentDateTime));
		if(settings.draw["DateFormat"] != "disable")
			$content.find(".clock").append($("<div></div>").css({"font-size": "5px","text-align":"center"}).text(currentDate));
		
		var $value = $content.data("clock");
		var $container = $value.parent();

		var ratio = $container.width() / $value.width();

		if(ratio * $value.height() > $container.height()){
			ratio = $container.height() / $value.height();
		}

		$value.css("transform", "scale(" + ratio + ") translate(" + ((($container.width() / 2) - ($value.width() * ratio / 2)) / ratio) + "px, " + ((($container.height() / 2) - ($value.height() * ratio / 2)) / ratio) + "px)");
	},
	
	// Custom function
	adjustValueSize: function($content){
		var $value = $content.data("clock");
		var $container = $value.parent();

		var ratio = $container.width() / $value.width();

		if(ratio * $value.height() > $container.height()){
			ratio = $container.height() / $value.height();
		}

		$value.css("transform", "scale(" + ratio + ") translate(" + ((($container.width() / 2) - ($value.width() * ratio / 2)) / ratio) + "px, " + ((($container.height() / 2) - ($value.height() * ratio / 2)) / ratio) + "px)");

		return $value;
	}
};

function createDateFormatoption(){
	var today = new Date();
	$("#DateFormat").append($("<option></option>").val("disable").text("<?=$lang['WIDGET']['CLOCK']['DISABLE'][$language]?>").attr("selected", true));
	$("#DateFormat").append($("<option></option>").val("YYYY/MM/DD").text(moment().format("YYYY/MM/DD")));
	$("#DateFormat").append($("<option></option>").val("MM/DD/YYYY").text(moment().format("MM/DD/YYYY")));
	$("#DateFormat").append($("<option></option>").val("MMMM DD, YYYY").text(moment().format("MMMM DD, YYYY")));
	$("#DateFormat").append($("<option></option>").val("DD-MM-YYYY").text(moment().format("DD-MM-YYYY")));
	$("#DateFormat").append($("<option></option>").val("MM/DD/YY").text(moment().format("MM/DD/YY")));
	$("#DateFormat").append($("<option></option>").val("DD-MMM-YY").text(moment().format("DD-MMM-YY")));
	$("#DateFormat").append($("<option></option>").val("MM/DD").text(moment().format("MM/DD")));
	$("#DateFormat").append($("<option></option>").val("DD-MM").text(moment().format("DD-MM")));
}
function createUTC_option(){
	$("#TimeZoneSetting").append($("<option></option>").val("L").text("<?=$lang['WIDGET']['CLOCK']['BROWSER_TIME'][$language]?>").attr("selected", true));
	for(var i=-12; i <= 14; i++){
		if(i < 0){
			if(Math.abs(i) < 10)
				$("#TimeZoneSetting").append($("<option></option>").val(i + "_00").text("UTC" + "-0"+Math.abs(i) + ":00"));
			else
				$("#TimeZoneSetting").append($("<option></option>").val(i + "_00").text("UTC" + i+":00"));
		}
		else if(i==0){
			$("#TimeZoneSetting").append($("<option></option>").val(i + "_00").text("UTC"));
		}
		else{
			if(i < 10)
				$("#TimeZoneSetting").append($("<option></option>").val(i + "_00").text("UTC" + "+0"+Math.abs(i) + ":00"));
			else
				$("#TimeZoneSetting").append($("<option></option>").val(i + "_00").text("UTC" + "+"+Math.abs(i) + ":00"));
		}
		//Other
		switch(i){
			case -10:
				$("#TimeZoneSetting").append($("<option></option>").val("-9_30").text("UTC" + "-09:30"));
				break;
			case -4:
				$("#TimeZoneSetting").append($("<option></option>").val("-3_30").text("UTC" + "-03:30"));
				break;
			case 8:
				$("#TimeZoneSetting").append($("<option></option>").val("8_45").text("UTC" + "+08:45"));
				break;
			case 9:
				$("#TimeZoneSetting").append($("<option></option>").val("9_30").text("UTC" + "+09:30"));
				break;	
			case 10:
				$("#TimeZoneSetting").append($("<option></option>").val("10_30").text("UTC" + "+10:30"));
				break;
			case 12:
				$("#TimeZoneSetting").append($("<option></option>").val("12_45").text("UTC" + "+12:45"));
				break;
		}
	}
}
function GetTimeZone(TimeZone,ck_24h,DateFormat){
	var today = new Date();
	var Hours, Minutes;
	var currentDateTime,currentDate,currentArrayinfo;
	if(TimeZone == "L"){
		if(ck_24h =="disable"){
			currentDateTime = moment().format("hh:mm:ss A");
		}
		else
			currentDateTime = moment().format("HH:mm:ss");
		currentDate = moment().format(DateFormat);
		currentArrayinfo = [currentDateTime,currentDate];
	}
	else{
		Hours = parseInt(TimeZone.split("_")[0]);
		Minutes = parseInt(TimeZone.split("_")[1]);
		
		if(ck_24h =="disable")
			currentDateTime = moment.utc().add(Hours,'h').add(Minutes,'m').format("hh:mm:ss A");
		else
			currentDateTime = moment.utc().add(Hours,'h').add(Minutes,'m').format("HH:mm:ss");
		currentDate = moment.utc().add(Hours,'h').add(Minutes,'m').format(DateFormat);
		currentArrayinfo = [currentDateTime,currentDate];
	}
	return currentArrayinfo;
}