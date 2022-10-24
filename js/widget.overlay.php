<?php
global $language;

$lang["WIDGET"]["OVERLAY"] = array(
	"BACKGROUND" => array(
		"en" => "Background",
		"tw" => "背景",
		"cn" => "背景"
	),
	"IMAGE" => array(
		"en" => "Image",
		"tw" => "圖片",
		"cn" => "图片"
	),
	"BROWSE" => array(
		"en" => "Browse...",
		"tw" => "瀏覽...",
		"cn" => "浏览..."
	),
	"SIZE" => array(
		"en" => "Size",
		"tw" => "大小",
		"cn" => "大小"
	),
	"LABEL" => array(
		"en" => "Label",
		"tw" => "標籤",
		"cn" => "标签"
	),
	"OPACITY" => array(
		"en" => "Opacity",
		"tw" => "不透明度",
		"cn" => "不透明度"
	),
	"TITLE" => array(
		"en" => "Title",
		"tw" => "標題",
		"cn" => "标题"
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
	"HEATMAP" => array(
		"en" => "Heatmap",
		"tw" => "熱點圖(Heatmap)",
		"cn" => "热点图(Heatmap)"
	),
	"FUNCTION" => array(
		"en" => "Function",
		"tw" => "功能",
		"cn" => "功能"
	),
	"RADIUS" => array(
		"en" => "Radius",
		"tw" => "影響半徑",
		"cn" => "影响半径"
	),
	"MAXIMUM_INTENSITY" => array(
		"en" => "Maximum Intensity",
		"tw" => "最大強度",
		"cn" => "最大强度"
	),
	"PREVIEW" => array(
		"en" => "Preview",
		"tw" => "預覽",
		"cn" => "预览"
	),
	"TIP" => array(
		"CHANNEL" => array(
			"en" => "Channel:",
			"tw" => "通道:",
			"cn" => "通道:"
		),
		"NICKNAME" => array(
			"en" => "Nickname:",
			"tw" => "名稱:",
			"cn" => "名称:"
		)
	),
	"SELECT_IMG" => array(
		"en" => "Please select the Background Image.",
		"tw" => "請選擇背景圖片",
		"cn" => "请选择背景图片"
	)
);
?>

widget.overlay = {
	minChannelNumber: 1,
	maxChannelNumber: null,

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
					}).css("paddingBottom", "10px").text("<?=$lang['WIDGET']['OVERLAY']['BACKGROUND'][$language]?>")
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['OVERLAY']['IMAGE'][$language]?>")
				).append(
					$("<td></td>").append(
						$("<form></form>").attr({"enctype":"multipart/form-data"}).css({"height":"0px", "display":"none"}).append(
							$("<input>").attr({"id": "uploadImage","type":"file","accept":"image/*"}).change(function(){
								if($('#uploadImage').val() == '') {return;}//避免按取消修改內容
								$("#widget-overlay-img-size").val("1.0");
								$("#previewDiv > img").remove().css({"display":"none"}); // 清空當下預覽
								if (this.files && this.files.length >= 1) {
									$.map(this.files, file => {
										new Promise((resolve,reject)=>{
										let reader = new FileReader()
										reader.onload = () => { resolve(reader.result) }
										reader.onerror = () => { reject(reader.error) }
										reader.readAsDataURL(file)
										}).then(data => {
											let image = new Image();
											image.name = file.name;
											image.src = data; 
											image.style.cssFloat = "left";
											$content.find("#previewTitle").show();
											$content.find("#previewDiv").append(image).show();
										})
											.catch(err => console.log(err))
									})
								}
							})
						)
					).append(
						$("<button></button>").addClass("gray").bind("click",function(){$("#uploadImage").click();}).text("<?=$lang['WIDGET']['OVERLAY']['BROWSE'][$language]?>")
					)
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['OVERLAY']['SIZE'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<select></select>").attr("id", "widget-overlay-img-size").css("width", "100%").append(
							$("<option></option>").val("0.1").text("10%")
						).append(
							$("<option></option>").val("0.2").text("20%")
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
							$("<option></option>").val("1.0").text("100%").attr("selected", true)
						).append(
							$("<option></option>").val("1.1").text("110%")
						).append(
							$("<option></option>").val("1.2").text("120%")
						).append(
							$("<option></option>").val("1.3").text("130%")
						).append(
							$("<option></option>").val("1.4").text("140%")
						).append(
							$("<option></option>").val("1.5").text("150%")
						).append(
							$("<option></option>").val("1.6").text("160%")
						).append(
							$("<option></option>").val("1.7").text("170%")
						).append(
							$("<option></option>").val("1.8").text("180%")
						).append(
							$("<option></option>").val("1.9").text("190%")
						).append(
							$("<option></option>").val("2.0").text("200%")
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
					}).css("paddingBottom", "10px").text("<?=$lang['WIDGET']['OVERLAY']['LABEL'][$language]?>")
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['OVERLAY']['OPACITY'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<select></select>").attr("id", "widget-overlay-fill-opacity").css("width", "100%").append(
							$("<option></option>").val("0").text("0%")
						).append(
							$("<option></option>").val("0.1").text("10%")
						).append(
							$("<option></option>").val("0.2").text("20%")
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
							$("<option></option>").val("1").text("100%").attr("selected", true)
						)
					)
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['OVERLAY']['TITLE'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<select></select>").attr("id", "widget-overlay-title-enable").css("width", "100%").append(
							$("<option></option>").val("enable").text("<?=$lang['WIDGET']['OVERLAY']['ENABLE'][$language]?>").attr("selected", true)
						).append(
							$("<option></option>").val("disable").text("<?=$lang['WIDGET']['OVERLAY']['DISABLE'][$language]?>")
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
					}).css("paddingBottom", "10px").text("<?=$lang['WIDGET']['OVERLAY']['HEATMAP'][$language]?>")
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['OVERLAY']['FUNCTION'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<select></select>").attr("id", "widget-overlay-heatmap").css("width", "100%").append(
							$("<option></option>").val("enable").text("<?=$lang['WIDGET']['OVERLAY']['ENABLE'][$language]?>")
						).append(
							$("<option></option>").val("disable").text("<?=$lang['WIDGET']['OVERLAY']['DISABLE'][$language]?>").attr("selected", true)
						)
					)
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['OVERLAY']['RADIUS'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<input>").attr({"type":"text","id":"widget-overlay-heatmap-radius"}).val(50)
					)
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['OVERLAY']['MAXIMUM_INTENSITY'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<input>").attr({"type":"text","id":"widget-overlay-heatmap-value"}).val(50)
					)
				)
			)
		).append(
			$("<div></div>").attr({
				"id": "previewTitle",
				"class": "content-title"
			}).css({
				"display": "none",
				"marginTop": "17px"
			}).text("<?=$lang['WIDGET']['OVERLAY']['PREVIEW'][$language]?>")
		).append(
			$("<div></div>").attr("id","previewDiv").css({"border": "transparent 5px solid", "position": "relative", "outline": "#CCC solid 1px", "margin": "0px 20px 0px 0px", "overflow": "hidden", "display": "inline-block"}).hide()
		);
		
		if(settings){
			$("#previewDiv").append($("<img>").attr("src",settings.overlay_img).css({"transform-origin":"left top", "float": "left"}));
			this.set_overlay_data(settings.overlay_data, window.accounts[$("#dashboard-switch-handler").attr("account_uid")].dashboards[$("#dashboard-switch-handler").attr("dashboard_uid")].widgets[$content.attr("uid")].channels);
			if(settings.overlay_img){
				$("#previewTitle").show();
				$("#previewDiv").show();
			}
			$("#widget-overlay-img-size").val(settings.overlay_img_size_percen);
			$("#widget-overlay-fill-opacity").val(settings.overlay_fill_opacity);
			$("#previewDiv > div").css({"opacity":settings.overlay_fill_opacity});
			$("#widget-overlay-title-enable").val(settings.overlay_title_enable);
			
			$("#widget-overlay-heatmap").val(settings.overlay_heatmap_enable);
			$("#widget-overlay-heatmap-radius").val(settings.overlay_heatmap_radius);
			$("#widget-overlay-heatmap-value").val(settings.overlay_heatmap_value);
		}
		
		//image size
		$("#widget-overlay-img-size").bind("change",function(){
			try{
				$("#previewDiv > img").css({"width":""});
				$("#previewDiv > img").css({"width":$("#previewDiv > img")[0].width * $("#widget-overlay-img-size").val()});
			}
			catch{}
			
		});
		
		//label opacity
		$("#widget-overlay-fill-opacity").bind("change",function(){
			$("#previewDiv > div").css({"opacity":$("#widget-overlay-fill-opacity").val()})
		});
		
		//label title show or hide
		$("#widget-overlay-title-enable").bind("change",function(){
			if($("#widget-overlay-title-enable").val() == "enable")
				$content.find($(".overlay-data-title")).show();
			else
				$content.find($(".overlay-data-title")).hide();
		});
	},
	
	channelUpdated: function($content, channels){
		var ch_arr=[];
		for(var channels_idx in channels){
			for(var device_idx in channels[channels_idx]){
				for(var module_idx in channels[channels_idx][device_idx]){
					for(var channel_idx in channels[channels_idx][device_idx][module_idx]){
						try{
							ch_arr.push({
								"overlay_data_id":channels_idx+"_"+device_idx+"_"+module_idx+"_"+channel_idx,
								"coordinate_left":$content.find($("#previewDiv > .widget-overlay-tag#" + channels_idx + "_" + device_idx + "_"+module_idx + "_"+channel_idx + ""))[0].style.left,
								"coordinate_top":$content.find($("#previewDiv > .widget-overlay-tag#" + channels_idx + "_" + device_idx + "_"+module_idx + "_"+channel_idx + ""))[0].style.top
							});
						}
						catch{
							ch_arr.push({
								"overlay_data_id":channels_idx+"_"+device_idx+"_"+module_idx+"_"+channel_idx
							});
						}
					}
				}
			}
		}
		$content.find($("#previewDiv > .widget-overlay-tag")).remove();
		this.set_overlay_data(ch_arr, channels);
		$("#widget-overlay-img-size").trigger("change");
		$("#widget-overlay-fill-opacity").trigger("change");
		$("#widget-overlay-title-enable").trigger("change");
	},
	settingSaved: function(settings){
		if(typeof($("#previewDiv > img").attr("src")) == "undefined"){
			$("#menu-container > div.menu-item[target='property']").triggerHandler("click");
			return "<?=$lang['WIDGET']['OVERLAY']['SELECT_IMG'][$language]?>";
		}
		$("#content-view").show();
		//底圖base64
		settings.overlay_img = $("#previewDiv > img").attr("src");
		settings.overlay_img_size_percen = $("#widget-overlay-img-size").val();
		settings.overlay_img_size = $("#previewDiv > img").css("width");
		settings.overlay_fill_opacity = $("#widget-overlay-fill-opacity").val();
		settings.overlay_title_enable = $("#widget-overlay-title-enable").val();
		
		//Heatmap
		settings.overlay_heatmap_enable = $("#widget-overlay-heatmap").val();
		settings.overlay_heatmap_radius = $("#widget-overlay-heatmap-radius").val();
		settings.overlay_heatmap_value = $("#widget-overlay-heatmap-value").val();
		$("#content-property").show();
		settings.overlay_img_width = $("#previewDiv > img").css("width");
		settings.overlay_img_height = $("#previewDiv > img").css("height");
		
		//查看勾選多少Channel
		var ch_arr = [];
		for(var st_channel_idx = 0; st_channel_idx < $("#channle-list-table-body > tr").length; st_channel_idx++){
			var st_channel_id = $($("#channle-list-table-body > tr")[st_channel_idx]).attr("account_uid") + "_" +
			$($("#channle-list-table-body > tr")[st_channel_idx]).attr("device_uid") + "_" +
			$($("#channle-list-table-body > tr")[st_channel_idx]).attr("module_uid") + "_" +
			$($("#channle-list-table-body > tr")[st_channel_idx]).attr("channel");
			if(typeof($("#previewDiv > #" + st_channel_id + "")[0]) != "undefined"){
				ch_arr.push({
					"channel":$("#previewDiv > #" + st_channel_id + "").attr("channel"),
					"coordinate_left":$("#previewDiv > #" + st_channel_id + "")[0].style.left,
					"coordinate_top":$("#previewDiv > #" + st_channel_id + "")[0].style.top,
					"overlay_data_id":$("#previewDiv > #" + st_channel_id + "").attr("id")
				});
			}
			else{
				ch_arr.push({
					"overlay_data_id":st_channel_id,
				});
			}
		}
		
		settings.overlay_data = ch_arr;
	},
	widgetCreated: function($content, settings, channels){
		this.dashboard_drawoverlay($content, settings, channels);
	},
	widgetUpdated: function($content, settings, channels){
		this.dashboard_drawoverlay($content, settings, channels);
	},
	widgetRemoved: function($content, settings){
		
	},
	dataUpdated: function($content, settings, channels){
		$content.find('#heatmap').html('');
		var heatmap = h337.create({
			container: $content.find('#heatmap')[0],
			radius: settings.overlay_heatmap_radius,
			width: settings.overlay_img_width.replace(/px/,''),
			height: settings.overlay_img_height.replace(/px/,'')
		});
		var dataSet = [];
		
		for(var accountUID in channels){
			for(var deviceUID in channels[accountUID]){
				for(var moduleUID in channels[accountUID][deviceUID]){
					for(var channel in channels[accountUID][deviceUID][moduleUID]){
						var date = new Date(window.channels[accountUID][deviceUID][moduleUID][channel][window.channels[accountUID][deviceUID][moduleUID][channel].length-1][0]);
						if(window.channels[accountUID][deviceUID][moduleUID][channel][window.channels[accountUID][deviceUID][moduleUID][channel].length-1][1] == null){
							$content.find("[id="+accountUID + "_" + deviceUID + "_" + moduleUID + "_" + channel+"]").find(".overlay-data-value").text("-");
							continue;
						}
						$content.find("[id="+accountUID + "_" + deviceUID + "_" + moduleUID + "_" + channel+"]").find(".overlay-data-value").text(
							numberWithCommas(parseFloat(window.channels[accountUID][deviceUID][moduleUID][channel][window.channels[accountUID][deviceUID][moduleUID][channel].length-1][1].toPrecision(7))) + channels[accountUID][deviceUID][moduleUID][channel].unit
						);
						var x = $content.find("#"+accountUID + "_" + deviceUID + "_" + moduleUID + "_" + channel).css("left").replace(/px/,'');
						var y = $content.find("#"+accountUID + "_" + deviceUID + "_" + moduleUID + "_" + channel).css("top").replace(/px/,'');
						var v = numberWithCommas(parseFloat(window.channels[accountUID][deviceUID][moduleUID][channel][window.channels[accountUID][deviceUID][moduleUID][channel].length-1][1].toPrecision(7)))
						dataSet.push({"x":(x*1).toFixed(0),"y":(y*1).toFixed(0),"value":v});
					}
				}
			}
		}
		heatmap.setData({
			max: settings.overlay_heatmap_value,
			data: dataSet
		});
		//$content.find("> .widget-loader").hide();
	},
	dragstop: function($content, settings){
		
	},
	resizestart: function($content, settings){

	},
	resizing: function($content, settings){
		this.adjustOverlaySize($content);
	},
	resizestop: function($content, settings){
		this.adjustOverlaySize($content);
	},
	dashboard_drawoverlay: function($content, settings, channels){
		var ch_arr = settings.overlay_data;
		$content.html('');
		if(typeof(settings.overlay_img)!="undefined"){
			if(settings.overlay_heatmap_enable == "enable"){
				$overlay_content = $("<div></div>").attr("class","overlay").css({"position": "absolute", "transform-origin": "left top"}).append(
					$("<img>").attr("src",settings.overlay_img).css({"width":settings.overlay_img_size, "float": "left"})
				).append(
					$("<div id='heatmap'></div>").css({"width":"100%", "position": "absolute"}).show()
				);
			}
			else{
				$overlay_content = $("<div></div>").attr("class","overlay").css({"position": "absolute", "transform-origin": "left top"}).append(
					$("<img>").attr("src",settings.overlay_img).css({"width":settings.overlay_img_size, "float": "left"})
				).append(
					$("<div id='heatmap'></div>").css({"width":"100%", "position": "absolute"}).hide()
				);
			}
		}
		else{
			$overlay_content = $("<div></div>").attr("class","overlay").css({"position": "absolute", "transform-origin": "left top"}).append(
			$("<img>").css({"width":"100px", "float": "left"}));
		}
		
		var coordinate_left;
		var coordinate_top;
		var unset_coordinate_idx = 0;
		for(var data_idx in ch_arr){
			if(typeof(ch_arr[data_idx].coordinate_left) == "undefined"){
				coordinate_left = "0px";
				coordinate_top = 15 + unset_coordinate_idx * 15 + "px";
				unset_coordinate_idx++;
			}
			else{
				coordinate_left = ch_arr[data_idx].coordinate_left;
				coordinate_top = ch_arr[data_idx].coordinate_top;
			}
			var channel_info_arr = ch_arr[data_idx].overlay_data_id.split("_");
			var channel_nickname = "";
			var channel_fullname = "";
			var channel_icon;
			try{
				channel_icon = channels[channel_info_arr[0]][channel_info_arr[1]][channel_info_arr[2]][channel_info_arr[3]].icon;
				channel_nickname = channels[channel_info_arr[0]][channel_info_arr[1]][channel_info_arr[2]][channel_info_arr[3]].shortName;
			}
			catch{continue;}
			$overlay_content.append(
				$("<div id='" + ch_arr[data_idx].overlay_data_id + "'></div>").addClass("widget-overlay-tag").css({"left": coordinate_left, "top":coordinate_top, "opacity":settings.overlay_fill_opacity, "cursor": "default"}).attr({"channel":ch_arr[data_idx].channel}).append(
					$("<table border='0' cellspacing='0' cellpadding='0'></table>").append(
						$("<tr></tr>").append(
							$("<td rowspan='2'></td>").css({"background": "#a4ddff"}).css("display",function(){if(channel_icon == null)return "none";else return "table-cell"}).append(
								$("<div></div>").addClass("icon-container").css({"background-image":"url("+channel_icon+")","height": "24px","width": "24px","background-size": "contain", "margin":"5px","background-repeat": "no-repeat"})
							)
						).append(
							$("<td></td>").append(
								$("<div></div>").addClass("overlay-data-title").text(channel_nickname).css("display",function(){if(settings.overlay_title_enable == "disable") return "none";}).css({"white-space": "pre","color":"#2A2A2A"})
							)
						)
					).append(
						$("<tr></tr>").append(
							$("<td></td>").append(
								$("<div></div>").css({"color":"#2A2A2A"}).addClass("overlay-data-value")
							)
						)
					)
				).bind("mousemove",function(){
					$(this).css("opacity","1");
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
							var tip_channel_id_arr = $("[valuetip=" + id + "]").attr("id").split('_');
							var table = "<table>";
							table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['WIDGET']['OVERLAY']['TIP']['CHANNEL'][$language]?></td><td>";
							table += channels[tip_channel_id_arr[0]][tip_channel_id_arr[1]][tip_channel_id_arr[2]][tip_channel_id_arr[3]].fullName;
							table += "</td></tr>";
							table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['WIDGET']['OVERLAY']['TIP']['NICKNAME'][$language]?></td><td>";
							table += channels[tip_channel_id_arr[0]][tip_channel_id_arr[1]][tip_channel_id_arr[2]][tip_channel_id_arr[3]].shortName;
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
				}).bind("mouseleave", function(){
					$(this).css("opacity",settings.overlay_fill_opacity);
					$("#" + $(this).attr("valueTip")).remove();
				})
			)
					
			/*		
					
					$("<div></div>").addClass("overlay-data-title").text(channel_nickname).css("display",function(){if(settings.overlay_title_enable == "disable") return "none";}).css({"white-space": "pre","color":"#2A2A2A"})
				).append(
					$("<div></div>").css({"color":"#2A2A2A"}).addClass("overlay-data-value")
				)
			)
			*/
		}
		
		$content.append($overlay_content).append(
			$("<div></div>").attr("class", "widget-loader")
		);
		
		$content.find("img").load(function(){
			var $overlay = $content.find(".overlay");
			var $container = $overlay.parent();
			var ratio = $container.width() / $overlay.width();
			
			if(ratio * $overlay.height() > $container.height()){
				ratio = $container.height() / $overlay.height();
			}
			$overlay.css("transform", "scale(" + ratio + ")");
		});
		$content.find("> .widget-loader").hide();
	},
	adjustOverlaySize: function($content){
		var $overlay = $content.find(".overlay");
		var $container = $overlay.parent();
		var ratio = $container.width() / $overlay.width();
		
		if(ratio * $overlay.height() > $container.height()){
			ratio = $container.height() / $overlay.height();
		}
		
		$overlay.css("transform", "scale(" + ratio + ")");
		
		return $overlay;
	},
	set_overlay_data(ch_arr, channels){
		var coordinate_left;
		var coordinate_top;
		var unset_coordinate_idx = 0;
		for(var data_idx in ch_arr){
			if(typeof(ch_arr[data_idx].coordinate_left) == "undefined"){
				
				coordinate_left = "0%";
				coordinate_top = unset_coordinate_idx * 5 + "%";
				unset_coordinate_idx++;
			}
			else{
				coordinate_left = ch_arr[data_idx].coordinate_left;
				coordinate_top = ch_arr[data_idx].coordinate_top;
			}
			var channel_info_arr = ch_arr[data_idx].overlay_data_id.split("_");
			var channel_fullName = "";
			var channel_nickname = "";
			var channel_icon;
			
			try{
				channel_icon = channels[channel_info_arr[0]][channel_info_arr[1]][channel_info_arr[2]][channel_info_arr[3]].icon;		
				channel_nickname = channels[channel_info_arr[0]][channel_info_arr[1]][channel_info_arr[2]][channel_info_arr[3]].shortName;
			}
			catch{continue;}
			
			
			$("#previewDiv").append(
				$("<div id='" + ch_arr[data_idx].overlay_data_id + "'></div>").draggable({containment: "#previewDiv", scroll: false,stop:function (){
					var l = ( 100 * parseFloat($(this).css("left")) / parseFloat($(this).parent().css("width")) )+ "%" ;
					var t = ( 100 * parseFloat($(this).css("top")) / parseFloat($(this).parent().css("height")) )+ "%" ;
					$(this).css("left" , l);
					$(this).css("top" , t);
				}}).addClass("widget-overlay-tag").css({"left": coordinate_left, "top":coordinate_top}).append(
						$("<table border='0' cellspacing='0' cellpadding='0'></table>").append(
							$("<tr></tr>").append(
								$("<td rowspan='2'></td>").css({"background": "#a4ddff"}).css("display",function(){if(channel_icon == null)return "none";else return "table-cell"}).append(
									$("<div></div>").addClass("icon-container").css({"background-image":"url("+channel_icon+")","height": "24px","width": "24px","background-size": "contain", "margin":"5px","background-repeat": "no-repeat"})
								)
							).append(
								$("<td></td>").append(
									$("<div></div>").addClass("overlay-data-title").text(channel_nickname)
								)
							)
						).append(
							$("<tr></tr>").append(
								$("<td></td>").append(
									$("<div>100" + channels[channel_info_arr[0]][channel_info_arr[1]][channel_info_arr[2]][channel_info_arr[3]].unit + "</div>").css({"color":"#2A2A2A"}).addClass("overlay-data-value")
								)
							)
						)
					).bind("mousemove",function(){
						$(this).css("opacity","1");
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
								var tip_channel_id_arr = $("[valuetip=" + id + "]").attr("id").split('_');
								channel_fullName = channels[tip_channel_id_arr[0]][tip_channel_id_arr[1]][tip_channel_id_arr[2]][tip_channel_id_arr[3]].fullName;
								var table = "<table>";
								table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['WIDGET']['OVERLAY']['TIP']['CHANNEL'][$language]?></td><td>";
								table += channel_fullName;
								table += "</td></tr>";
								table += "<tr><td align='right' style='font-weight:bold;'><?=$lang['WIDGET']['OVERLAY']['TIP']['NICKNAME'][$language]?></td><td>";
								table += channels[tip_channel_id_arr[0]][tip_channel_id_arr[1]][tip_channel_id_arr[2]][tip_channel_id_arr[3]].shortName;
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
					}).bind("mouseleave", function(){
						$(this).css("opacity",$("#widget-overlay-fill-opacity").val());
						$("#" + $(this).attr("valueTip")).remove();
					})
			);
		}
	}	
};