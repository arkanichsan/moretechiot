<?php
function customized_header(){
	global $lang;
?>
<link rel="stylesheet" href="./css/tip.css">
<link rel="stylesheet" href="./css/checkbox.css" />
<style type="text/css">
#event-no-exist-container,
#device-no-exist-container{
    text-align: center;
    color: #707070;
    padding: 100px 0;
	display:none;
}

.event-no-exist-title{
    margin-bottom: 10px;
    font-weight: bold;
}

.event-no-exist-content{
    font-size: 13px;
}

#event-container,
#device-container{
	display:none;
}

.video-calendar-button div{
	width:18px;
	height:18px;
	position:relative;
	top:50%;
	margin-top:-9px;
	left:50%;
	margin-left:-9px;
}

.video-calendar-button svg{
	fill:#9d9d9d;
}

.video-calendar-button.hover svg{
	fill:#555555;
}

.video-calendar-button{
	box-sizing: border-box;
	width:20px;
	background: #E8E8E8;
	background: linear-gradient(to bottom, #f9f9f9 0%,#D5D5D5 100%);
	border:#BBB 1px solid;
	cursor: pointer;
}

.video-calendar-button.hover{
	background: #DADADA;
	background: linear-gradient(to bottom, #f9f9f9 0%,#C3C3C3 100%);
	border:#999 1px solid;
}

.video-calendar-button.disable{
	opacity:0.3;
	cursor: default;
}

table.calendar{
	border:#BBBBBB 1px solid;
}

table.calendar td{
    position: relative;
	background-color:#F2F2F2;
	text-align:center;
	padding:3px;
	width:25px;
}

table.calendar td.calendar-selectable{
	background-color:#DBDBDB;
	cursor: pointer;
}

table.calendar td.calendar-title{
	border-bottom:#BBBBBB 1px solid;
}

table.calendar td.calendar-week{
	font-size:13px;
}

table.calendar td.calendar-selected{
	background-color:#B2CAEB;
}

.calendar-out-of-date{
	color: #ccc;
}

.calendar-event{
	width: 0;
	height: 0;
	border-style: solid;
	border-width: 8px 8px 0 0;
	border-color: #395d8b transparent transparent transparent;
	position: absolute;
    top: 0;
    left: 0;
}

#video-calendar-table-container{
	margin:0 22px;
	position: relative;
	overflow:hidden;
}

#video-calendar-table-wrapper{
	position: absolute;
}

#video-calendar-loader{
	position: absolute;
	top:0px;
	bottom:0px;
	left:0px;
	right:0px;
	display: none;
	z-index: 1;
}

#video-calendar-loader div{
	position: absolute;
	top: 50%;
    left: 50%;
    width: 72px;
    height: 72px;
    margin: -36px 0 0 -36px;
    border-radius: 10px;
    background-image: url(../image/loader.gif);
    background-color: rgba(255, 255, 255, 0.8);
    background-repeat: no-repeat;
    background-position: center;
}

#video-list{
	float:left;
	background-color:red;
	width:350px;
	position: relative;
	overflow:hidden;
}

#video-list-loader{
	position: absolute;
	top:0px;
	bottom:0px;
	left:0px;
	right:0px;
	display: none;
	z-index: 1;
}

#video-list-loader div{
	position: absolute;
    top: 50%;
    left: 50%;
    width: 72px;
    height: 72px;
    margin: -36px 0 0 -36px;
	border-radius: 10px;
    background-image: url(../image/loader.gif);
    background-color: rgba(255, 255, 255, 0.8);
    background-repeat: no-repeat;
    background-position: center;
}

#video-list-container{
	background-color: #FFF;
	border: 1px solid #ccc;
	height:550px;
	box-sizing: border-box;
	overflow-x: hidden;
	overflow-y: scroll;
}

.video-list-empty,
.video-player-empty{
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translateX(-50%) translateY(-50%);
	text-align: center;
}

.video-list-item{
	height: 60px;
	display: flex;
	flex-flow: row nowrap;
	align-items: stretch;
	padding: 5px 5px 5px 0;
	cursor:pointer;
	position: relative;
}

.video-list-item:hover,
.video-list-item.playing{
	background-color: #f2f2f2;
}

.video-list-item:hover .video-list-item-header svg,
.video-list-item.playing .video-list-item-header svg{
	fill:#555555
}

.video-list-item-header{
	display:flex;
	flex-direction:column;
	justify-content:center;
    width: 25px;
}

.video-list-item-header > div{
	margin:0 auto;
}

.video-list-item-header svg{
	fill:#9d9d9d;
}

.video-list-item-header input{
	cursor:pointer;
}

.video-list-item:hover .video-list-item-header div.arrow,
.video-list-item.checking .video-list-item-header div.arrow{
	display:none;
}

.video-list-item:hover .video-list-item-header div.checkbox,
.video-list-item.checking .video-list-item-header div.checkbox{
	display:block;
}

.video-list-item:hover .video-list-item-header input,
.video-list-item.checking .video-list-item-header input{
	display:block !important;
}

.video-list-item-preview{
	display:flex;
	width:100px;
	border:#BBB 1px solid;
/*	background-size: contain;*/
	background-size: cover;
	background-repeat: no-repeat;
	background-position: center;
/*	background-color:#000;*/
}

.video-list-item-desc{
	display:flex;
	flex-direction:column;
	justify-content: space-between;
	flex: 1;
	padding:0 5px;
}

.video-list-item-desc-text{
	overflow:hidden;
    height: calc(var(--line-height) * 2em);
    line-height: var(--line-height);
    --line-height: 1.15;
	text-overflow: ellipsis;

    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
}

.video-list-item-desc-time{
	font-size:13px;
	color:#777;
}

.video-list-item-background{
	position: absolute;
	right:0;
	bottom:0;
}

.video-list-item-background div{
	display:inline-block;
	margin-left:3px;
}

.video-list-item-background svg{
	fill:#a9a9a9;
	height:24px;
	width:24px;
}

#video-list-button{
	position:absolute;
	left:1px;
	bottom:1px;
	padding:5px;
	background-color:#f2f2f2;
	box-sizing:border-box;
	border-top: 1px solid #ccc;
	display:none;
}

#video-list-button button svg {
    fill: #FFF;
    width: 21px;
    height: 21px;
}

#video-player{
	float:right;
	background-color: #FFF;
	border: 1px solid #ccc;
	width:calc(100% - 360px);
	height:550px;
	box-sizing: border-box;
	padding:10px;
    position: relative;
}

#video-player-container{
	width: 100%;
	height: 100%;
    /*background: url(../image/video_placeholder_light.svg);*/
    background-repeat: no-repeat;
    background-size: contain;
    background-position: center center;
}

#video-player-container video,
#video-player-container img{
	width: 100%;
	height: 100%;
	object-fit: contain;
}

#video-player-loader{
	position: absolute;
	top:0px;
	bottom:0px;
	left:0px;
	right:0px;
	display: none;
	z-index: 1;
}

#video-player-loader div{
	position: absolute;
    top: 50%;
    left: 50%;
    width: 72px;
    height: 72px;
    margin: -36px 0 0 -36px;
    border-radius: 10px;
    background-image: url(../image/loader.gif);
    background-color: rgba(255, 255, 255, 0.8);
    background-repeat: no-repeat;
    background-position: center;
}

/*******************/
.camera-device > div.device-list{
	overflow-x:hidden;
	overflow-y:scroll;
	height: 100%;
}

.camera-device > div.device-list > table > thead > tr > th,
.camera-device > div.device-list > table > tbody > tr > td{
	padding: 8px !important;
}

.camera-device > div.device-empty{
	display:table;
	width:100%;
	height:100%;
}

.camera-device > div.device-empty > div{
	display: table-cell;
	vertical-align: middle;
	text-align: center;
}

/*******************/
.scroll-table {
    /*width: 100%;*/
	/*height:100px;*/
    border-collapse: separate;
    border-spacing: 0;
}

.scroll-table thead tr th { 
    text-align: left;
    background: linear-gradient(to bottom, #ffffff, #efefef);
    font-size: 13px;
    font-weight: bold;
	border-bottom: 1px solid #ccc;
}

.scroll-table tbody {
    border-top: 1px solid #ccc;
}

.scroll-table tbody td, .scroll-table thead th {
    border-right: 1px solid #ccc;
	padding: 3px 14px;
}

.scroll-table thead th{
	padding: 6px 10px;
}

.scroll-table tbody td{
	padding: 3px 10px;
	border-bottom: 1px solid #ccc;
	background-color: #fff;
}

.scroll-table tbody td:last-child, .scroll-table thead th:last-child {
    border-right: none;
}

.scroll-table tbody tr:last-child td{
/*	border-bottom: none;*/
}

.scroll-table.sticky thead tr th{
	position: sticky;
	top: 0;
/*	box-shadow: 1px 1px #ccc, inset 0 1px #ccc;*/
    z-index: 1;
}
</style>
<script language="javascript" src="./js/jquery.tip.js"></script>
<script language="JavaScript">
var devices = {};

function startLoad(){
	$("#wait-loader").show();
	generateDevice().done(function(){
		var exist = false;
		for(var deviceUID in devices){
			exist = true;
			break;
		}

		if(exist == true){
			var deviceUnchecked = [];
			for(var deviceUID in devices){
				if(devices[deviceUID].checked == false){
					deviceUnchecked.push(deviceUID);
				}
			}

			$("#event-container").show();
			generateCalendar(deviceUnchecked).done(function(){
				$("#wait-loader").hide();
			});
		}
		else{
			$("#event-no-exist-container").show();
			$("#wait-loader").hide();
		}
	}).always(function(){

	});
}

function generateDevice(){
	var _devices = {};

	return $.ajax({
		url: "video_event_ajax.php?act=get_device",
		type: "POST",
		dataType: "xml",
		success: function(xmlDoc, textStatus, jqXHR){
			var $devices = $(xmlDoc).find("video > device");

			for(var i = 0; i < $devices.length; i++){
				var $device = $($devices[i]);

				_devices[$device.attr("uid")] = {
					nickname: $device.text(),
					checked: devices[$device.attr("uid")] !== undefined ? devices[$device.attr("uid")].checked : true
				};
			}

			devices = _devices;
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

			alert(jqXHR.responseText);
		},
		complete: function(){
			
		}
	});
}

function generateCalendar(uid){
	$("#video-calendar-loader").show();
	$("#video-list-loader").show();
	$("#video-player-loader").show();

	return $.ajax({
		url: "video_event_ajax.php?act=get_date",
		type: "POST",
		dataType: "xml",
		data: {
			"uid": uid
		},
		success: function(xmlDoc, textStatus, jqXHR){
			$("#video-calendar-table").empty();

			var $dates = $(xmlDoc).find("video > date");
			var eventPool = {};
			var flag = false;

			for(var i = 0; i < $dates.length; i++){
				var $date = $($dates[i]);

				var d = new Date(parseInt($date.attr("timestamp"), 10) * 1000);
				var year = d.getFullYear();
				var month = d.getMonth() + 1;
				var date = d.getDate();
				var dateBit = 0;

				eventPool[year] = eventPool[year] || {};
				eventPool[year][month] = eventPool[year][month] || 0;
				eventPool[year][month] = eventPool[year][month] | Math.pow(2, date - 1);
				flag = true;
			}

			if(flag == true){
				$("#device-no-exist-container").hide();
				$("#device-container").show();

				var keys = Object.keys(eventPool);
				keys.sort(function(a, b) {
					return a - b;
				});

				for (var i = 0; i < keys.length; i++) {
					var y = keys[i];

					var keys2 = Object.keys(eventPool[y]);
					keys2.sort(function(a, b) {
						return a - b;
					});

					for (var j = 0; j < keys2.length; j++) {
						var m = keys2[j];

						$("#video-calendar-table").append(
							$("<td></td>").attr("valign", "top").css("paddingLeft", "2px").append(createCalendar(y, m, eventPool[y][m]))
						);
					}
				}

				$("#video-calendar-table td:first").css("paddingLeft", "0px")

				$("#video-calendar-table-container, #video-calendar-left-button, #video-calendar-right-button").css("height", $("#video-calendar-table-wrapper").height() + "px");

				// align to right when init
				if($("#video-calendar-table-container").width() < $("#video-calendar-table-wrapper").width()){
					$("#video-calendar-table-wrapper").css("left", ($("#video-calendar-table-container").width() - $("#video-calendar-table-wrapper").width()) + "px");
				}

				$("#video-calendar-table-container").triggerHandler("mousewheel");

				$("#video-calendar-table div.calendar-event").last().closest("td.calendar-selectable").triggerHandler("click");
			}
			else{
				$("#device-no-exist-container").show();
				$("#device-container").hide();
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

			alert(jqXHR.responseText);
		},
		complete: function(){
			$("#video-calendar-loader").hide();
		}
	});
}

function createCalendar(year, month, selectableDates){
	var $table = $("<table></table>").attr({
		"class": "calendar",
		"width": "240"
	}).data({"year": year, "month": month});
	$("<tr></tr>").append(
		$("<td></td>").attr("class", "calendar-title").attr("colSpan", "7").html(year + " / " + month)
	).appendTo($table);
	
	$row = $("<tr></tr>").appendTo($table);
	$.each(["<?=$lang['VIDEO']['SUN'];?>", "<?=$lang['VIDEO']['MON'];?>", "<?=$lang['VIDEO']['TUE'];?>", "<?=$lang['VIDEO']['WED'];?>", "<?=$lang['VIDEO']['THU'];?>", "<?=$lang['VIDEO']['FRI'];?>", "<?=$lang['VIDEO']['SAT'];?>"], function(index, value) { 
		$("<td></td>").attr("class", "calendar-week").html(value).appendTo($row);
	});

	var lastDate = getLastDate(year, month);
	var loopStart = (getFirstDay(year, month) - 1) * - 1;
	var loopEnd = lastDate + 6 - getLastDay(year, month) + ((6 - getWeeks(year, month)) * 7);
	for(var date = loopStart, week = 0; date <= loopEnd; date++, week = (week + 1) % 7){
		if(week == 0){
			var $row = $("<tr></tr>").appendTo($table);
		}
		if(date < 1 || date > lastDate){
			$("<td></td>").addClass("calendar-out-of-date").text(
				(new Date(year, month - 1, date)).getDate()
			).appendTo($row);
		}
		else{
			var $td = $("<td></td>").addClass("calendar-selectable").html(date);

			if(selectableDates & Math.pow(2, date - 1)){
				$td.bind("click", {
					"year": year,
					"month": month,
					"date": date
				}, function(event, select){
					var that = this;
					$("#video-calendar-table td.calendar-selected").removeClass("calendar-selected");
					$(this).addClass("calendar-selected");

					if(typeof($ajax) != "undefined"){
						$ajax.abort();
					}

					$("#video-list-loader").show();
					$("#video-player-loader").show();
					$("#video-list-button").hide();

					var from = new Date(event.data.year, event.data.month - 1, event.data.date);
					var to = new Date(event.data.year, event.data.month - 1, event.data.date + 1);

					$.ajax({
						url: "video_event_ajax.php?act=get_event",
						type: "POST",
						dataType: "xml",
						data: {
							"from": from.getUTCFullYear() + "-" + padding(from.getUTCMonth() + 1, 2) + "-" + padding(from.getUTCDate(), 2) + " " + padding(from.getUTCHours(), 2) + ":" + padding(from.getUTCMinutes(), 2) + ":00",
							"to": to.getUTCFullYear() + "-" + padding(to.getUTCMonth() + 1, 2) + "-" + padding(to.getUTCDate(), 2) + " " + padding(to.getUTCHours(), 2) + ":" + padding(to.getUTCMinutes(), 2) + ":00",
						},
						success: function(xmlDoc, textStatus, jqXHR){
							$("#video-list-container").empty();
							hideControlButton(0);

							var $events = $(xmlDoc).find("video > event");
							if($events.length > 0){
								for(var i = 0; i < $events.length; i++){
									var $event = $($events[i]);

									var splitArray = $event.text().split("|$|");
									$("<div></div>").attr("class", "video-list-item").append(
										$("<div></div>").attr("class", "video-list-item-header").append(
											$("<div></div>").addClass("arrow").append(
												$(createSVGIcon("image/ics.svg", "arrow_right"))
											)
										).append(
											$("<div></div>").addClass("checkbox").append(
												$("<input type='checkbox'/>").val($event.attr("uid")).attr("disabled", $event.attr("uid") === undefined ? true : false).bind("click", function(event){
													if($(this).attr("checked")){
														$("#video-list-container > div.video-list-item").addClass("checking");
														showControlButton();
													}
													else{
														if($("#video-list-container > div.video-list-item input:checked").length <= 0){
															$("#video-list-container > div.video-list-item").removeClass("checking");
															hideControlButton();
														}
													}

													event.stopPropagation();
												})
											)
										)
									).append(
										$("<div></div>").attr("class", "video-list-item-preview").css("backgroundImage", "url(" + (function(type, path){
											var splitArray = path.split(".");
											return "webhook.php?act=image&file=" + splitArray[0] + "_p_200" + (type == "1" ? "." + splitArray[1] : ".jpg");
										})($event.attr("type"), splitArray[3]) + ")")
									).append(
										$("<div></div>").attr("class", "video-list-item-desc").append(
											$("<div></div>").attr({
												"class": "video-list-item-desc-text",
												"title": splitArray[2]
											}).text(splitArray[2])
										).append(
											$("<div></div>").attr("class", "video-list-item-desc-time").text((function(date){
												return padding(date.getHours(), 2) + ":" + padding(date.getMinutes(), 2) + ":" + padding(date.getSeconds(), 2);
											})(new Date(parseInt($event.attr("timestamp"), 10) * 1000)))
										)
									).append(
										$("<div></div>").attr("class", "video-list-item-background").append((function(){
											if($event.attr("uid") === undefined){
												var $icon = $("<div></div>").attr("tip", "<?=$lang['VIDEO']['TIP']['SHARE_BY_USER'];?>".replace("%username%", $event.attr("nickname") + "(" + $event.attr("username") + ")")).append(
													$(createSVGIcon("image/ics.svg", "share"))
												);

												bindTipEvent($icon);

												return $icon;
											}
										})()).append((function(){
											var $icon = $("<div></div>").attr("tip", $event.attr("type") == "1" ? "<?=$lang['VIDEO']['TIP']['PHOTO'];?>" : "<?=$lang['VIDEO']['TIP']['VIDEO'];?>").append(
												$(createSVGIcon("image/ics.svg", $event.attr("type") == "1" ? "image" : "videocam"))
											);

											bindTipEvent($icon);

											return $icon;
										})())
									).bind("click", {
										type: $event.attr("type"),
										path: splitArray[3]
									}, function(event){
										$(this).addClass("playing").siblings().removeClass("playing");

										var log = event.data;
										var $container = $("#video-player-container");

										if(log.type == "1"){// photo
											$container.empty().append(
												$("<img/>").bind("load", function(){
													$("#video-player-loader").hide();
												}).bind("error", function(){
													$("#video-player-container").empty();
													$("#video-player-loader").hide();
												}).attr("src", "webhook.php?act=image&file=" + log.path)
											);
										}
										else if(log.type == "2"){// video
											$container.empty().append(
												$("<video></video>").attr({
													"controls": true,
													"autoplay": true,
													"loop": true
												}).append(
													$("<source/>").attr({
														"src": "webhook.php?act=image&file=" + log.path,
														"type": "video/mp4"
													}).bind("error", function(){
														$("#video-player-container").empty();
														$("#video-player-loader").hide();
													})
												).bind("canplay", function(){
													$("#video-player-loader").hide();
												})
											);
										}

										$("#video-player-loader").show();
									}).appendTo("#video-list-container");
								}

								if($(that).find("div.calendar-event").length <= 0){
									$(that).append(
										$("<div></div>").attr("class", "calendar-event")
									);
								}

								$("#video-list-container > div.video-list-item:first").triggerHandler("click");
							}
							else{// No event
								$("#video-list-container").append(
									$("<div></div>").addClass("video-list-empty").text("<?=$lang['VIDEO']['NO_VIDEO_EVENT_EXIST_ON_SELECT_DATE'];?>")
								);

								$("#video-player-container").empty().append(
									$("<div></div>").addClass("video-player-empty").text("<?=$lang['VIDEO']['NO_VIDEO_CAN_PLAY_ON_SELECT_DATE'];?>")
								);
								$("#video-player-loader").hide();

								$(that).css("cursor", "default").unbind("click").find("div.calendar-event").remove();
							}
						},
						error: function(jqXHR, textStatus, errorThrown){
							if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

							alert(jqXHR.responseText);
						},
						complete: function(){
							$("#video-list-loader").hide();
						}
					});
				}).append(
					$("<div></div>").addClass("calendar-event")
				);
			}
			else{
				$td.css("cursor", "default");
			}

			if(week == 0 || week == 6){
				$td.addClass("calendarWeekend");
			}
			else{
				$td.addClass("calendarWeekday");
			}

			$td.appendTo($row);
		}
	}

	return $table;
}

function generateList(){
}

//return first day in a month, 0 is Sunday, 1 is Monday
function getFirstDay(year, month){
	return (new Date(year, month - 1, 1)).getDay();
}

//return last day in a month, 0 is Sunday, 1 is Monday
function getLastDay(year, month){
	return (new Date(year, month, 0)).getDay();
}

//return last date in a month
function getLastDate(year, month){
	return (new Date(year, month, 0)).getDate();
}

function getWeeks(year, month){
	return Math.ceil((getLastDate(year, month) + getFirstDay(year, month)) / 7);
}

function padding(number, length, paddingChar){
	paddingChar = typeof(paddingChar) == "undefined" ? "0" : paddingChar;

    var str = "" + number;
    while (str.length < length) {
        str = paddingChar + str;
    }

    return str;
}

function createID(length) {
   var result = '';
   var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
   var charactersLength = characters.length;

   for (var i = 0; i < length; i++) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }

   return result;
}

function createSVGIcon(path, name){
	var svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
	var use = document.createElementNS("http://www.w3.org/2000/svg", 'use');
	use.setAttributeNS("http://www.w3.org/1999/xlink", 'xlink:href', path + '#' + name);
	svg.appendChild(use);
	return svg;
}

function bindTipEvent($element){
	$element.hover(
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
	);

//	clearTimeout($(this).attr("pid"));
//	$("#" + $(this).attr("tip_id")).remove();
}

function showControlButton(){
	if($("#video-list-button").is(":visible")){
		return;
	}

	var height = $("#video-list-button").outerHeight();

	$("#video-list-button").show().css({
		"width": $("#video-list-container > div.video-list-item").outerWidth() + "px",
		"bottom": (height * -1) + "px",
		"opacity": 0
	}).animate({
		"bottom": "1px",
		"opacity": 1
	}, "fast");

//	$("#video-list-container").animate({
//		"paddingBottom": height + "px"
//	}, "fast");

	$("#video-list-container").css({
		"paddingBottom": height + "px"
	});

	// Scrollbar at bottom
	if ($("#video-list-container").scrollTop() + $("#video-list-container").innerHeight() == $("#video-list-container").prop('scrollHeight') - height) {
		$("#video-list-container").animate({
			"scrollTop": ($("#video-list-container").scrollTop() + height) + "px"
		}, "fast");
	}
}

function hideControlButton(duration){
	duration = duration === undefined ? "fast" : duration;

	var height = $("#video-list-button").outerHeight();

	$("#video-list-button").css({
		"bottom": "1px",
		"opacity": 1
	}).animate({
		"bottom": (height * -1) + "px",
		"opacity": 0
	}, duration, function(){
		$(this).hide();
	});

	$("#video-list-container").animate({
		"paddingBottom": "0px"
	}, duration);
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

$(document).ready(function(){
	$(window).resize(function() {
		var delta;

		if($("#video-calendar-table-wrapper").position().left < 0){
			if($("#video-calendar-table-container").width() - ($("#video-calendar-table-wrapper").width() - Math.abs($("#video-calendar-table-wrapper").position().left)) > 0){
				delta = -180;
			}
		}

		$("#video-calendar-table-container").triggerHandler("mousewheel", delta);
	});

	$("#video-calendar-left-button, #video-calendar-right-button").hover(function(){
		if($(this).hasClass("disable") == false){
			$(this).addClass("hover").find("span").addClass("hover");
		}
	}, function(){
		if($(this).hasClass("disable") == false){
			$(this).removeClass("hover").find("span").removeClass("hover");
		}
	});

	$("#video-calendar-left-button").bind("click", function(){
		var left = Math.abs($("#video-calendar-table-wrapper").position().left);
		var shiftIndex = -1;

		$("#video-calendar-table-wrapper table.calendar").each(function(index){
			if($(this).position().left >= left){
				shiftIndex = index - 1;
				return false;
			}
		});

		if(shiftIndex != -1){
			$("#video-calendar-table-wrapper").animate({
			    left: "-" + $("#video-calendar-table-wrapper table.calendar:eq(" + shiftIndex + ")").position().left + "px"
			}, "fast", function(){
				$("#video-calendar-table-container").triggerHandler('mousewheel');
			});
		}
	});

	$("#video-calendar-right-button").bind("click", function(){
		var right = Math.abs($("#video-calendar-table-wrapper").position().left) + $("#video-calendar-table-container").width();
		var shiftIndex = -1;

		$("#video-calendar-table-wrapper table.calendar").each(function(index){
			if($(this).position().left + $(this).outerWidth() > right){
				shiftIndex = index;
				return false;
			}
		});

		if(shiftIndex != -1){
			$("#video-calendar-table-wrapper").animate({
			    left: "-" + (($("#video-calendar-table-wrapper table.calendar:eq(" + shiftIndex + ")").position().left + $("#video-calendar-table-wrapper table.calendar:eq(" + shiftIndex + ")").outerWidth()) - $("#video-calendar-table-container").width()) + "px"
			}, "fast", function(event){
				$("#video-calendar-table-container").triggerHandler('mousewheel');
			});
		}
	});

	$("#video-calendar-table-container").bind('mousewheel DOMMouseScroll', function(event, _delta){
		event.preventDefault();

		var offset = 40;
		var left = $("#video-calendar-table-wrapper").position().left;
		var boundry = $("#video-calendar-table-container").width() - $("#video-calendar-table-wrapper").width();

		if(boundry >= 0){
			$("#video-calendar-table-wrapper").css("left", "0px");
			$("#video-calendar-left-button, #video-calendar-right-button").removeClass("hover").addClass("disable");
			return;
		}

		if(_delta !== undefined || event.originalEvent){
			var delta = _delta !== undefined ? _delta : parseInt(event.originalEvent.wheelDelta || -event.originalEvent.detail);

		    if(delta > 0) {
				if(left == 0){
					return;
				}

				$("#video-calendar-table-wrapper").css("left", (left + offset > 0 ? 0 : left + offset) + "px");
		    }
		    else{
				if(left == boundry){
					return;
				}

				$("#video-calendar-table-wrapper").css("left", (left - offset < boundry ? boundry : left - offset) + "px");
		    }
		}

		$("#video-calendar-left-button, #video-calendar-right-button").removeClass("disable");
		left = $("#video-calendar-table-wrapper").position().left;
		if(left == 0){
			$("#video-calendar-left-button").removeClass("hover").addClass("disable");
		}
		if(left == boundry){
			$("#video-calendar-right-button").removeClass("hover").addClass("disable");
		}
	});

	$("#device-filter").bind("click", function(){
		var $win = $("<div></div>").attr("class", "popup-wrapper").css("width", "500px").append(
			$("<div></div>").attr("class", "popup-container").append(
				$("<div></div>").attr("class", "popup-title").text("<?=$lang['VIDEO']['DEVICE_FILTER']['DEVICE_FILTER'];?>")
			).append(
				$("<div></div>").attr("class", "popup-content camera-device").css("height", "400px").append((function(){
					var allCheck = true;
					for(var deviceUID in devices){
						if(devices[deviceUID].checked == false){
							allCheck = false;
							break;
						}
					}

					return $("<div></div>").addClass("device-list").append(
						$("<table></table>").addClass("scroll-table sticky").append(
							$("<thead></thead>").append(
								$("<tr></tr>").append(
									$("<th></th>").css("width", "1%").append((function(){
										var id = createID(8);
										return $("<div></div>").attr("class", "checkbox").append(
											$("<input type='checkbox'/>").attr({
												"id": id,
												"checked": allCheck
											}).bind("click", function(){
												$(this).closest("table").find("tbody input").attr("checked", $(this).attr("checked") ? true : false);
											})
										).append(
											$("<label></label>").attr("for", id)
										)
									})())
								).append(
									$("<th></th>").text("<?=$lang['VIDEO']['DEVICE_FILTER']['MODEL_NAME_AND_NICKNAME'];?>")
								).append(
									$("<th></th>").css("width", "1%").text("<?=$lang['VIDEO']['DEVICE_FILTER']['SERIAL_NUMBER'];?>")
								)
							)
						).append(
							(function(){
								var $tbody = $("<tbody></tbody>");

								for(var deviceUID in devices){
									$tbody.append(
										$("<tr></tr>").append(
											$("<td></td>").append((function(){
												var id = createID(8);
												return $("<div></div>").attr("class", "checkbox").append(
													$("<input type='checkbox'/>").attr({
														"id": id,
														"device-uid": deviceUID,
														"checked": devices[deviceUID].checked
													}).bind("click", function(){
														var $checkbox = $(this).closest("table").find("thead input");
														if($(this).attr("checked")){
															if($(this).closest("tbody").find("input:not(:checked)").length <= 0){
																$checkbox.attr("checked", true);
															}
														}
														else{
															$checkbox.attr("checked", false);
														}
													})
												).append(
													$("<label></label>").attr("for", id)
												)
											})())
										).append(
											$("<td></td>").text(devices[deviceUID].nickname)
										).append(
											$("<td></td>").text(deviceUID)
										)
									);
								}

								return $tbody;
							})()
						)
					);
				})())
			).append(
				$("<div></div>").attr("class", "popup-footer").append(
					$("<input type='button'/>").val("<?=$lang['OK'];?>").bind("click", function(){
						onClickWindowButton('ok');
					})
				).append("&nbsp;&nbsp;").append(
					$("<input type='button'/>").attr("class", "gray").val("<?=$lang['CANCEL'];?>").bind("click", function(){
						onClickWindowButton('cancel');
					})
				)
			)
		);

		showWindow($win, function(result){
			if(result == "ok"){
				var deviceUnchecked = [];
				$win.find(".device-list tbody input").each(function(){
					var deviceUID = $(this).attr("device-uid");
					var checked = $(this).attr("checked") ? true : false;

					devices[deviceUID].checked = checked;
					if(checked == false){
						deviceUnchecked.push($(this).attr("device-uid"));
					}
				});

				generateCalendar(deviceUnchecked);
			}
			else if(result == "cancel"){}

			hideWindow($win);
			$win.remove();
		});
	});

	$("#event-refresh").bind("click", function(){
		startLoad();
	});

	$("#video-list-button-select-all").bind("click", function(){
		var $inputs = $("#video-list-container > div.video-list-item input:not(:disabled)");

		if($inputs.filter(":not(:checked)").length <= 0){
			$inputs.prop("checked", false);
		}
		else{
			$inputs.prop("checked", true);
		}

		$inputs.triggerHandler("click");
	});

	$("#video-list-button-remove").bind("click", function(){
		popupConfirmWindow("<?=$lang['VIDEO']['POPUP']['ARE_YOU_SURE_REMOVE_EVENT_YOU_SELECT']?>",function(){
				$("#video-list-loader").show();

				var uid = [];
				$("#video-list-container > div.video-list-item input:checked").each(function(){
					uid.push($(this).val());
				});

				return $.ajax({
					url: "video_event_ajax.php?act=remove_event",
					type: "POST",
					dataType: "xml",
					data: {
						"uid": uid
					},
					success: function(xmlDoc, textStatus, jqXHR){
						$("#video-calendar-table td.calendar-selected").triggerHandler("click");
					},
					error: function(jqXHR, textStatus, errorThrown){
						if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

						alert(jqXHR.responseText);
					},
					complete: function(){
						$("#video-list-loader").hide();
					}
				});
			},
			function(){}
		);
	});

	bindTipEvent($("#video-list-button-select-all"));
	bindTipEvent($("#video-list-button-remove"));

	startLoad();
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
	<div id="event-no-exist-container">
        <div class="event-no-exist-title"><?=$lang['VIDEO']['NO_VIDEO_EVENT_EXIST'];?></div>
        <div class="event-no-exist-content"><?=$lang['VIDEO']['THE_EVENT_WILL_SHOW_WHEN_VIDEO_INCOMING'];?></div>
	</div>

	<div id="event-container">
		<div class="title" style="position:relative;"><?=$lang['VIDEO']['VIDEO_EVENT_DATA'];?>
			<div style="float:right;position:relative;bottom:6px;"><button class="gray" style="padding:5.5px;margin-right:5px;" id="event-refresh"><svg><use xlink:href="image/ics.svg#refresh"></use></svg></button><button class="gray" style="padding:5.5px;" id="device-filter"><svg><use xlink:href="image/ics.svg#filter"></use></svg></button></div>
		</div>

		<div id="device-no-exist-container">
			<div class="event-no-exist-title"><?=$lang['VIDEO']['NO_VIDEO_EVENT_EXIST'];?></div>
			<div class="event-no-exist-content"><?=$lang['VIDEO']['THERE_IS_NO_VIDEO_EVENT_SELECT_OTHER_DEVICE'];?></div>
		</div>
		<div id="device-container">
			<div id="video-calendar" style="position: relative;margin:10px 0 10px 0;">
				<div id="video-calendar-container">
					<div id="video-calendar-table-container">
						<div id="video-calendar-table-wrapper">
							<table cellSpacing="0" cellPadding="0" border="0">
								<tr id="video-calendar-table"></tr>
							</table>
						</div>
					</div>
					<div style="position:absolute;top:0;left:0;">
						<div id="video-calendar-left-button" class="video-calendar-button">
							<div><svg><use xlink:href="image/ics.svg#arrow_left"></use></svg></div>
						</div>
					</div>
					<div style="position:absolute;top:0;right:0;">
						<div id="video-calendar-right-button" class="video-calendar-button">
							<div><svg><use xlink:href="image/ics.svg#arrow_right"></use></svg></div>
						</div>
					</div>
				</div>

				<div id="video-calendar-loader"><div></div></div>
			</div>

			<div id="video-list">
				<div id="video-list-container"></div>
				<div id="video-list-button">
					<button class="blue" style="padding:4px;" id="video-list-button-select-all" tip="<?=$lang['VIDEO']['TIP']['SELECT_ALL_OR_UNSELECT_ALL']?>"><svg><use xlink:href="image/ics.svg#select_all"></use></svg></button>
					<button class="red" style="padding:4px;" id="video-list-button-remove" tip="<?=$lang['VIDEO']['TIP']['REMOVE']?>"><svg><use xlink:href="image/ics.svg#delete"></use></svg></button>
				</div>
				<div id="video-list-loader"><div></div></div>
			</div>

			<div id="video-player">
				<div id="video-player-container"></div>
				<div id="video-player-loader"><div></div></div>
			</div>
		</div>

		<div style="clear:both;"></div>
	</div>
</div>
<?php
}
?>