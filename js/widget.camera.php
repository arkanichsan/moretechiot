<?php
global $language;

$lang["WIDGET"]["CAMERA"] = array(
	"TIME" => array(
		"en" => "Time",
		"tw" => "時間",
		"cn" => "时间"
	),
	"DEVICE" => array(
		"en" => "Device",
		"tw" => "裝置",
		"cn" => "装置"
	),
	"MESSAGE" => array(
		"en" => "Message",
		"tw" => "訊息",
		"cn" => "讯息"
	),
	"DEVICE_FILTER" => array(
		"en" => "Device Filter",
		"tw" => "裝置篩選",
		"cn" => "装置筛选"
	),
	"MODEL_NAME_OR_NICKNAME" => array(
		"en" => "Model Name / Nickname",
		"tw" => "型號 / 名稱",
		"cn" => "型号 / 名称"
	),
	"SERIAL_NUMBER" => array(
		"en" => "Serial Number",
		"tw" => "序號",
		"cn" => "序号"
	),
	"NO_CAMERA_EVENT_EXIST" => array(
		"en" => "No camera event exist.",
		"tw" => "無攝影機事件。",
		"cn" => "无摄影机事件。"
	),
	"SUN" => array(
		"en" => "S",
		"tw" => "日",
		"cn" => "日"
	),
	"MON" => array(
		"en" => "M",
		"tw" => "一",
		"cn" => "一"
	),
	"TUE" => array(
		"en" => "T",
		"tw" => "二",
		"cn" => "二"
	),
	"WED" => array(
		"en" => "W",
		"tw" => "三",
		"cn" => "三"
	),
	"THU" => array(
		"en" => "T",
		"tw" => "四",
		"cn" => "四"
	),
	"FRI" => array(
		"en" => "F",
		"tw" => "五",
		"cn" => "五"
	),
	"SAT" => array(
		"en" => "S",
		"tw" => "六",
		"cn" => "六"
	),
	"JAN" => array(
		"en" => "Jan",
		"tw" => "1月",
		"cn" => "1月"
	),
	"FEB" => array(
		"en" => "Feb",
		"tw" => "2月",
		"cn" => "2月"
	),
	"MAR" => array(
		"en" => "Mar",
		"tw" => "3月",
		"cn" => "3月"
	),
	"APR" => array(
		"en" => "Apr",
		"tw" => "4月",
		"cn" => "4月"
	),
	"MAY" => array(
		"en" => "May",
		"tw" => "5月",
		"cn" => "5月"
	),
	"JUN" => array(
		"en" => "Jun",
		"tw" => "6月",
		"cn" => "6月"
	),
	"JUL" => array(
		"en" => "Jul",
		"tw" => "7月",
		"cn" => "7月"
	),
	"AUG" => array(
		"en" => "Aug",
		"tw" => "8月",
		"cn" => "8月"
	),
	"SEP" => array(
		"en" => "Sep",
		"tw" => "9月",
		"cn" => "9月"
	),
	"OCT" => array(
		"en" => "Oct",
		"tw" => "10月",
		"cn" => "10月"
	),
	"NOV" => array(
		"en" => "Nov",
		"tw" => "11月",
		"cn" => "11月"
	),
	"DEC" => array(
		"en" => "Dec",
		"tw" => "12月",
		"cn" => "12月"
	)
);
?>

widget.camera = {
	minChannelNumber: 0,
	maxChannelNumber: 0,
	defaultHeight: 15,

	// Event function
	settingCreated: function($content, settings){// setting == undefined mean is create not modify
	},
	channelUpdated: function($content, channels){
	},
	settingSaved: function(settings, channels){
	},
	widgetCreated: function($content, settings, channels){
		var that = this;

		$content.append(
			$("<div></div>").addClass("camera").append(
				$("<div></div>").addClass("camera-video").append(
					$("<div></div>").attr("class", "camera-video-container")/*.append('<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M21 3H3C2 3 1 4 1 5v14c0 1.1.9 2 2 2h18c1 0 2-1 2-2V5c0-1-1-2-2-2zm0 15.92c-.02.03-.06.06-.08.08H3V5.08L3.08 5h17.83c.03.02.06.06.08.08v13.84zm-10-3.41L8.5 12.5 5 17h14l-4.5-6z"/></svg>')*/
				).append(
					$("<div></div>").attr("class", "camera-video-loader")
				)
			).append(
				$("<div></div>").addClass("camera-event").append(
					$("<div></div>").addClass("event-list").append(
						$("<table></table>").addClass("camera-table sticky").append(
							$("<thead></thead>").append(
								$("<tr></tr>").append(
									$("<th></th>").css({
										"width": "1%",
										"whiteSpace": "nowrap"
									}).text("<?=$lang['WIDGET']['CAMERA']['TIME'][$language]?>")
								).append(
									$("<th></th>").text("<?=$lang['WIDGET']['CAMERA']['DEVICE'][$language]?>").append(
										$("<div></div>").attr("class", "camera-filter").append(
											'<svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24" viewBox="0 0 24 24" width="24"><g><path d="M0,0h24 M24,24H0" fill="none"/><path d="M4.25,5.61C6.27,8.2,10,13,10,13v6c0,0.55,0.45,1,1,1h2c0.55,0,1-0.45,1-1v-6c0,0,3.72-4.8,5.74-7.39 C20.25,4.95,19.78,4,18.95,4H5.04C4.21,4,3.74,4.95,4.25,5.61z"/><path d="M0,0h24v24H0V0z" fill="none"/></g></svg>'
										).bind("click", function(){
											// Device filter
											var deviceList = getCookie("camera-widget-<?=$_SESSION['account_uid']?>-" + $("#dashboard-switch-handler").attr("uid") + "-" + $content.closest("div.grid-stack-item-content").attr("uid"));
											var devicePool = {};
											if(deviceList != null){
												deviceList = deviceList.split(",");
												for(var i = 0; i < deviceList.length; i++){
													devicePool[deviceList[i]] = {};
												}
											}
											else{// No filter
												devicePool = null;
											}

											var $win = $("<div></div>").attr("class", "popup-wrapper").css("width", "500px").append(
												$("<div></div>").attr("class", "popup-container").append(
													$("<div></div>").attr("class", "popup-title").text("<?=$lang['WIDGET']['CAMERA']['DEVICE_FILTER'][$language]?>")
												).append(
													$("<div></div>").attr("class", "popup-content camera-device").css("height", "400px").append((function(){
														var exist = false;
														for(var accountUID in window.events){
															for(var deviceUID in window.events[accountUID]){
																exist = true;
																break;
															}
														}

														if(exist == true){
															return $("<div></div>").addClass("device-list").append(
																$("<table></table>").addClass("scroll-table sticky").append(
																	$("<thead></thead>").append(
																		$("<tr></tr>").append(
																			$("<th></th>").css("width", "1%").append((function(){
																				var id = createID(8);
																				return $("<div></div>").attr("class", "checkbox").append(
																					$("<input type='checkbox'/>").attr({
																						"id": id,
																						"checked": devicePool == null ? true : false
																					}).bind("click", function(){
																						$(this).closest("table").find("tbody input").attr("checked", $(this).attr("checked") ? true : false);
																					})
																				).append(
																					$("<label></label>").attr("for", id)
																				)
																			})())
																		).append(
																			$("<th></th>").text("<?=$lang['WIDGET']['CAMERA']['MODEL_NAME_OR_NICKNAME'][$language]?>")
																		).append(
																			$("<th></th>").css("width", "1%").text("<?=$lang['WIDGET']['CAMERA']['SERIAL_NUMBER'][$language]?>")
																		)
																	)
																).append(
																	(function(){
																		var $tbody = $("<tbody></tbody>");
																		var device = {};

																		for(var accountUID in window.events){
																			for(var deviceUID in window.events[accountUID]){
																				if(typeof(device[deviceUID]) == "undefined"){
																					device[deviceUID] = window.events[accountUID][deviceUID][0].deviceNickname;// Use first log device nickname to show
																				}
																			}
																		}

																		for(var deviceUID in device){
																			$tbody.append(
																				$("<tr></tr>").append(
																					$("<td></td>").append((function(){
																						var id = createID(8);
																						return $("<div></div>").attr("class", "checkbox").append(
																							$("<input type='checkbox'/>").attr({
																								"id": id,
																								"device-uid": deviceUID,
																								"checked": devicePool == null ? true : devicePool[deviceUID] ? true : false
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
																					$("<td></td>").text(device[deviceUID])
																				).append(
																					$("<td></td>").text(deviceUID)
																				)
																			);
																		}

																		return $tbody;
																	})()
																)
															);
														}
														else{
															return $("<div></div>").addClass("device-empty").append(
																$("<div></div>").text("No device exist.")
															);
														}
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
													var deviceList = "";

													var $inputAll = $win.find(".device-list tbody input");
													var $inputChecked = $inputAll.filter(":checked");

													if($inputAll.length != $inputChecked.length){
														$inputChecked.each(function(){
															deviceList += $(this).attr("device-uid") + ",";
														});

														setCookie("camera-widget-<?=$_SESSION['account_uid']?>-" + $("#dashboard-switch-handler").attr("uid") + "-" + $content.closest("div.grid-stack-item-content").attr("uid"), deviceList.substring(0, deviceList.length - 1), 525600);
													}
													else{
														setCookie("camera-widget-<?=$_SESSION['account_uid']?>-" + $("#dashboard-switch-handler").attr("uid") + "-" + $content.closest("div.grid-stack-item-content").attr("uid"), deviceList.substring(0, deviceList.length - 1), -1);
													}

													$content.find("> table").removeAttr("uid timestamp");
													$content.find(".camera-video-container").empty();
													that.updateEventList($content, settings, channels);
												}
												else if(result == "cancel"){}

												hideWindow($win);
												$win.remove();
											});
										})
									)
								).append(
									$("<th></th>").text("<?=$lang['WIDGET']['CAMERA']['MESSAGE'][$language]?>")
								)
							)
						).append(
							$("<tbody></tbody>").attr("class", "list")
						).append(
							$("<tbody></tbody>").attr("class", "no-event").append(
								$("<tr></tr>").append(
									$("<td></td>").append(
										$("<div></div>").text("00:00:00")// Dummy
									)
								).append(
									$("<td></td>")
								).append(
									$("<td></td>")
								)
							).append(
								$("<tr></tr>").attr("class", "text").append(
									$("<td></td>").attr("colSpan", "3").text("<?=$lang['WIDGET']['CAMERA']['NO_CAMERA_EVENT_EXIST'][$language]?>")
								)
							).append(
								$("<tr></tr>").append(
									$("<td></td>").append(
										$("<div></div>").text("00:00:00")// Dummy
									)
								).append(
									$("<td></td>")
								).append(
									$("<td></td>")
								)
							)
						)
					)
				)
			).append(
				$("<div></div>").addClass("camera-datepicker").append(
					$("<table></table>").append(
						$("<tr></tr>").append(
							$("<td></td>").attr("class", "camera-datepicker-button").append(
								'<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path fill="none" d="m0,0l24,0l0,24l-24,0l0,-24z"/><path d="m17.835,3.87l-1.77,-1.77l-9.9,9.9l9.9,9.9l1.77,-1.77l-8.13,-8.13l8.13,-8.13z"/></svg>'
							).bind("click", function(){
								var $table = $(this).closest("table");
								var date = new Date(parseInt($table.find("td.camera-datepicker-date:first").attr("timestamp"), 10));
								$table.triggerHandler("date", date.setDate(date.getDate() - 1));
							})
						).append(
							$("<td></td>").attr("class", "camera-datepicker-year-month-wrapper").append(
								$("<table></table>").append(
									$("<tr></tr>").append(
										$("<td></td>").attr("class", "camera-datepicker-year")
									)
								).append(
									$("<tr></tr>").append(
										$("<td></td>").attr("class", "camera-datepicker-month")
									)
								)
							)
						).append(
							$("<td></td>").append(
								$("<table></table>").append(
									$("<tr></tr>").append(
										$("<td></td>").attr("class", "camera-datepicker-day").text("<?=$lang['WIDGET']['CAMERA']['SUN'][$language]?>")
									).append(
										$("<td></td>").attr("class", "camera-datepicker-day").text("<?=$lang['WIDGET']['CAMERA']['MON'][$language]?>")
									).append(
										$("<td></td>").attr("class", "camera-datepicker-day").text("<?=$lang['WIDGET']['CAMERA']['TUE'][$language]?>")
									).append(
										$("<td></td>").attr("class", "camera-datepicker-day").text("<?=$lang['WIDGET']['CAMERA']['WED'][$language]?>")
									).append(
										$("<td></td>").attr("class", "camera-datepicker-day").text("<?=$lang['WIDGET']['CAMERA']['THU'][$language]?>")
									).append(
										$("<td></td>").attr("class", "camera-datepicker-day").text("<?=$lang['WIDGET']['CAMERA']['FRI'][$language]?>")
									).append(
										$("<td></td>").attr("class", "camera-datepicker-day").text("<?=$lang['WIDGET']['CAMERA']['SAT'][$language]?>")
									)
								).append(
									$("<tr></tr>").append(
										$("<td></td>").attr("class", "camera-datepicker-date").append(
											$("<span></span>").text("00")
										).append(
											$("<div></div>")
										).bind("click", function(){
											$(this).closest("tr").triggerHandler("filter", this);
										})
									).append(
										$("<td></td>").attr("class", "camera-datepicker-date").append(
											$("<span></span>").text("00")
										).append(
											$("<div></div>")
										).bind("click", function(){
											$(this).closest("tr").triggerHandler("filter", this);
										})
									).append(
										$("<td></td>").attr("class", "camera-datepicker-date").append(
											$("<span></span>").text("00")
										).append(
											$("<div></div>")
										).bind("click", function(){
											$(this).closest("tr").triggerHandler("filter", this);
										})
									).append(
										$("<td></td>").attr("class", "camera-datepicker-date").append(
											$("<span></span>").text("00")
										).append(
											$("<div></div>")
										).bind("click", function(){
											$(this).closest("tr").triggerHandler("filter", this);
										})
									).append(
										$("<td></td>").attr("class", "camera-datepicker-date").append(
											$("<span></span>").text("00")
										).append(
											$("<div></div>")
										).bind("click", function(){
											$(this).closest("tr").triggerHandler("filter", this);
										})
									).append(
										$("<td></td>").attr("class", "camera-datepicker-date").append(
											$("<span></span>").text("00")
										).append(
											$("<div></div>")
										).bind("click", function(){
											$(this).closest("tr").triggerHandler("filter", this);
										})
									).append(
										$("<td></td>").attr("class", "camera-datepicker-date").append(
											$("<span></span>").text("00")
										).append(
											$("<div></div>")
										).bind("click", function(){
											$(this).closest("tr").triggerHandler("filter", this);
										})
									).bind("filter", function(event, td){
										if($(td).hasClass("empty")){
											return;
										}

										var $table = $content.find("> div");
										var $tbodys = $content.find("div.camera-event tbody");
										var $list = $tbodys.filter(".list");

										$table.removeAttr("timestamp uid");
										$content.find(".camera-video-container").empty();
										$table.attr("timestamp", $(td).attr("timestamp"));
										$table.triggerHandler("filter");
										$list.find("tr:visible:first").triggerHandler("play");

										$(td).addClass("active").siblings().removeClass("active");
									})
								)
							)
						).append(
							$("<td></td>").attr("class", "camera-datepicker-button").append(
								'<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path fill="none" d="m0,0l24,0l0,24l-24,0l0,-24z"/><path d="m5.94,4.12l7.88,7.88l-7.88,7.88l2.12,2.12l10,-10l-10,-10l-2.12,2.12z"/></svg>'
							).bind("click", function(){
								var $table = $(this).closest("table");
								var date = new Date(parseInt($table.find("td.camera-datepicker-date:last").attr("timestamp"), 10));
								$table.triggerHandler("date", date.setDate(date.getDate() + 1));
							})
						)
					).bind("date", function(event, dateInWeek){
						var $list = $content.find("div.camera-event tbody.list tr");
						var $dateTDs = $(this).find("td.camera-datepicker-date");
						var timestamp = parseInt($content.find("> div").attr("timestamp"), 10);

						var d = new Date(dateInWeek || Date.now());
						d.setMilliseconds(0);
						d.setSeconds(0);
						d.setMinutes(0);
						d.setHours(0);

						var day = d.getDay();
						d.setDate(d.getDate() - day);

						for(var i = 0; i < 7; i++, d.setDate(d.getDate() + 1)){
							var $td = $($dateTDs[i]);
							$td.find("div").text(d.getDate());
							$td.attr("timestamp", d.getTime());
							$td.addClass("empty");

							$list.each(function(){
								if(parseInt($(this).attr("timestamp"), 10) == d.getTime()){
									$td.removeClass("empty");
									return false;
								}
							});

							if(timestamp == d.getTime()){
								$td.addClass("active");
							}
							else{
								$td.removeClass("active");
							}

							if(i == 3){
								$(this).find("td.camera-datepicker-year").text(d.getFullYear());
								$(this).find("td.camera-datepicker-month").text(["<?=$lang['WIDGET']['CAMERA']['JAN'][$language]?>", "<?=$lang['WIDGET']['CAMERA']['FEB'][$language]?>", "<?=$lang['WIDGET']['CAMERA']['MAR'][$language]?>", "<?=$lang['WIDGET']['CAMERA']['APR'][$language]?>", "<?=$lang['WIDGET']['CAMERA']['MAY'][$language]?>", "<?=$lang['WIDGET']['CAMERA']['JUN'][$language]?>", "<?=$lang['WIDGET']['CAMERA']['JUL'][$language]?>", "<?=$lang['WIDGET']['CAMERA']['AUG'][$language]?>", "<?=$lang['WIDGET']['CAMERA']['SEP'][$language]?>", "<?=$lang['WIDGET']['CAMERA']['OCT'][$language]?>", "<?=$lang['WIDGET']['CAMERA']['NOV'][$language]?>", "<?=$lang['WIDGET']['CAMERA']['DEC'][$language]?>"][d.getMonth()]);
							}
						}
					})
				)
			).bind("filter.date", function(event){
				var timestamp = parseInt($(this).attr("timestamp"), 10);

				if(isNaN(timestamp)){
					return;
				}

				var date = new Date();

				$content.find("div.camera-event tbody.list tr").each(function(){
					date.setTime(parseInt($(this).attr("timestamp"), 10));

					if(timestamp == date.getTime()){
						$(this).show();
					}
					else{
						$(this).hide();
					}
				});
			}).bind("filter.emptyCheck", function(event){
				var $tbody = $content.find("div.camera-event tbody").show();
				var $list = $tbody.filter(".list");
				var $empty = $tbody.filter(".no-event");

				if($list.find("tr:visible").length > 0){
					$empty.hide().closest("table").css("height", "auto");
				}
				else{
					$list.hide().closest("table").css("height", "100%");
				}
			})
		).append(
			$("<div></div>").attr("class", "widget-loader")
		);

		$content.data("cameraVideoContainer", $content.find(".camera-video-container"));
		this.updateVideoSize($content, settings, channels);

		// If event data exist, draw chart immediately
		for(var accountUID in window.events){
			for(var deviceUID in window.events[accountUID]){
				this.updateEventList($content, settings, channels);
				return;
			}
		}
	},
	widgetUpdated: function($content, settings, channels){
//		this.drawChart($content, settings);
	},
	widgetRemoved: function($content, settings, channels){
	},
	dataUpdated: function($content, settings, channels){
		this.updateEventList($content, settings, channels);
	},
	dragstop: function($content, settings, channels){
	},
	resizestart: function($content, settings, channels){

	},
	resizing: function($content, settings, channels){
		this.updateVideoSize($content, settings, channels);
	},
	resizestop: function($content, settings, channels){
//		this.updateEventList($content, settings, channels);
		this.updateVideoSize($content, settings, channels);
	},

	// Custom function
	updateEventList: function($content, settings, channels){
		if(!($content.attr("state") != "resizing" && $content.attr("state") != "draging")){
			return;
		}

		// Device filter
		var deviceList = getCookie("camera-widget-<?=$_SESSION['account_uid']?>-" + $("#dashboard-switch-handler").attr("uid") + "-" + $content.closest("div.grid-stack-item-content").attr("uid"));
		var devicePool = {};
		if(deviceList != null){
			deviceList = deviceList.split(",");
			for(var i = 0; i < deviceList.length; i++){
				devicePool[deviceList[i]] = {};
			}
		}
		else{// No filter
			devicePool = null;
		}

		// Event list
		var eventList = [];

		for(var accountUID in window.events){
			for(var deviceUID in window.events[accountUID]){
				if(devicePool == null || devicePool[deviceUID]){
					eventList = eventList.concat(window.events[accountUID][deviceUID]);
				}
			}
		}

		var $table = $content.find("> div");
		var $tbodys = $content.find("div.camera-event tbody")/*.hide()*/;
		var $list = $tbodys.filter(".list");

		if(parseInt($table.attr("event-length"), 10) != eventList.length){
			eventList.sort(function(a, b) {
			    return b.timestamp - a.timestamp;
			});

			$list.empty();

			var date = new Date();
			for(var i = 0; i < eventList.length; i++){
				var log = eventList[i];
				var d = new Date(log.timestamp);

				var $tr = $("<tr></tr>").attr({
					"uid": log.uid,
					"device-uid": log.deviceUID,
					"timestamp": (function(){
						date.setTime(d.getTime());
						date.setMilliseconds(0);
						date.setSeconds(0);
						date.setMinutes(0);
						date.setHours(0);
						return date.getTime();
					})(),
					"class": (function(){
						if($table.attr("uid") == log.uid){
							return "active";
						}
					})()
				}).append(
					$("<td></td>").css("whiteSpace", "nowrap").text(
						padding(d.getHours(), 2) + ":" + padding(d.getMinutes(), 2) + ":" + padding(d.getSeconds(), 2)
					)
				).append(
					$("<td></td>").text(log.deviceNickname)
				).append(
					$("<td></td>").text(log.message)
				).bind("play.active", log, function(event){
					var log = event.data;
					$(this).addClass("active").siblings("tr").removeClass("active");
					$table.attr("uid", log.uid);
				}).bind("play.load", log, function(event){
					var log = event.data;
					var $container = $content.find(".camera-video-container");

					if(log.type == "1"){// photo
						$container.empty().append(
							$("<img/>").bind("load", function(){
								$(this).closest("div.camera-video-container").siblings("div.camera-video-loader").hide();
							}).bind("error", function(){
								$(this).closest("div.camera-video-container").empty().siblings("div.camera-video-loader").hide();
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
									$(this).closest("div.camera-video-container").empty().siblings("div.camera-video-loader").hide();
								})
							).bind("canplay", function(){
								$(this).closest("div.camera-video-container").siblings("div.camera-video-loader").hide();
							})
						);
					}

					$content.find(".camera-video-loader").show();
				}).bind("mousedown", function(event){
					$(this).data("drag", false);
				}).bind("mousemove", function(event){
					$(this).data("drag", true);
				}).bind("mouseup", function(event){
					if($(this).data("drag") == true){
						return;
					}

					// is click, not drag
					$(this).triggerHandler("play");
				});

				$list.append(
					$tr
				);
			}

			// Select last event day
			var showJustIn = true;

			if(eventList.length > 0){
				if(showJustIn == true || !$table.attr("timestamp")){
					var date = new Date(eventList[0].timestamp);
					date.setMilliseconds(0);
					date.setSeconds(0);
					date.setMinutes(0);
					date.setHours(0);

					$content.find("div.camera-datepicker > table").triggerHandler("date", date.getTime());
					$content.find("div.camera-datepicker > table td.camera-datepicker-date[timestamp='" + date.getTime() + "']").triggerHandler("click");
				}
				else{
					$content.find("div.camera-datepicker > table").triggerHandler("date", parseInt($table.attr("timestamp"), 10));
					$table.triggerHandler("filter");
				}
			}
			else{
				$table.removeAttr("timestamp uid");
				$content.find("div.camera-datepicker > table").triggerHandler("date");
				$table.triggerHandler("filter");
			}

			$table.attr("event-length", eventList.length);
		}
		else{

		}

		$content.find("> .widget-loader").hide();
	},
	updateVideoSize: function($content, settings, channels){
		var $container = $content.data("cameraVideoContainer");
		$container.css("height", ($container.width() * 9 / 16) + "px");
	}
};