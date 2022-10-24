<?php
global $language;

$lang["WIDGET"]["BUTTON"] = array(
	"DRAWING" => array(
		"en" => "Drawing",
		"tw" => "繪圖",
		"cn" => "绘图"
	),
	"COLOR" => array(
		"en" => "Color",
		"tw" => "顏色",
		"cn" => "颜色"
	),
	"GRAY" => array(
		"en" => "Gray",
		"tw" => "灰色",
		"cn" => "灰色"
	),
	"BLUE" => array(
		"en" => "Blue",
		"tw" => "藍色",
		"cn" => "蓝色"
	),
	"RED" => array(
		"en" => "Red",
		"tw" => "紅色",
		"cn" => "红色"
	),
	"TEXT" => array(
		"en" => "Text",
		"tw" => "文字",
		"cn" => "文字"
	),
	"ICON" => array(
		"en" => "Icon",
		"tw" => "圖示",
		"cn" => "图示"
	),
	"BROWSE" => array(
		"en" => "Browse...",
		"tw" => "瀏覽...",
		"cn" => "浏览..."
	),
	"REMOVE" => array(
		"en" => "Remove",
		"tw" => "移除",
		"cn" => "移除"
	),
	"OUTPUT" => array(
		"en" => "Output",
		"tw" => "輸出",
		"cn" => "输出"
	),
	"VALUE" => array(
		"en" => "Value",
		"tw" => "數值",
		"cn" => "数值"
	)
);
?>

widget.button = {
	minChannelNumber: 1,
	maxChannelNumber: null,
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
					}).css("paddingBottom", "10px").text("<?=$lang['WIDGET']['BUTTON']['DRAWING'][$language]?>")
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['BUTTON']['COLOR'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<select></select>").attr("id", "widget-draw-button-color").css("width", "100%").bind("change", function(){
							var $selectedOption = $(this).find("option:selected");
							$(this).css({
								"backgroundColor": $selectedOption.attr("backgroundColor"),
								"color": $selectedOption.attr("color")
							});
						}).append(
							$("<option></option>").css({"backgroundColor": "#FFF", "color": "#000"}).attr({"backgroundColor": "#f6f6f6", "color": "#000"}).val("gray").text("<?=$lang['WIDGET']['BUTTON']['GRAY'][$language]?>").attr("selected", true)
						).append(
							$("<option></option>").css({"backgroundColor": "#FFF", "color": "#000"}).attr({"backgroundColor": "#4D90FE", "color": "#FFF"}).val("blue").text("<?=$lang['WIDGET']['BUTTON']['BLUE'][$language]?>")
						).append(
							$("<option></option>").css({"backgroundColor": "#FFF", "color": "#000"}).attr({"backgroundColor": "#B90004", "color": "#FFF"}).val("red").text("<?=$lang['WIDGET']['BUTTON']['RED'][$language]?>")
						)
					)
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['BUTTON']['TEXT'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<input/>").attr({"type": "text", "id": "widget-draw-button-text"})
					)
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['BUTTON']['ICON'][$language]?>")
				).append(
					$("<td></td>").css({"display": "flex"}).append(
						$("<form></form>").attr({"enctype":"multipart/form-data"}).css({"height":"0px", "display":"none"}).append(
							$("<input>").attr({"id": "uploadIcon","type":"file","accept":"image/*"}).change(function(){
								if($('#uploadIcon').val() == '') {return;}//避免按取消修改內容
								if (this.files && this.files.length >= 1) {
									$.map(this.files, file => {
										new Promise((resolve,reject)=>{
											let reader = new FileReader()
											reader.onload = () => { resolve(reader.result) }
											reader.onerror = () => { reject(reader.error) }
											reader.readAsDataURL(file)
										}).then(data => {
											$content.find("#div_icon").html(
												$("<div></div>").css("background-image","url("+data+")").css({"height":"25px","width":"25px","margin":"2px","background-size":"contain","background-repeat":"no-repeat", "background-position": "center center"})
												
											).append(
												$("<div></div>").css("fontSize", "0").attr({
													tip: "Remove",
													tip_position: "right"
												}).append($(createSVGIcon("image/ics.svg", "delete")).css("fill", "#707070")).css({"cursor": "pointer"}).click(function(){
													document.getElementById('uploadIcon').value = '';
													$(this.previousSibling).css("background-image", "");
													$("#div_icon").hide();
												}).hover(function(){
													$(this).find("svg").css("fill", "#2A2A2A");
												}, function(){
													$(this).find("svg").css("fill", "#707070");
												})
											).show();

											bindTipEvent($content.find("#div_icon svg").closest("div"));
										})
									
									})
								}
							})
						)
					).append(
						$("<button></button>").addClass("gray").css("marginRight", "3px").text("<?=$lang['WIDGET']['BUTTON']['BROWSE'][$language]?>").bind("click",function(){$("#uploadIcon").click();})
					).append(
						$("<div></div>").attr("id","div_icon").css({"display": "flex", "alignItems": "center"})
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
					}).css("paddingBottom", "10px").text("<?=$lang['WIDGET']['BUTTON']['OUTPUT'][$language]?>")
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").css("padding", "3px 50px 3px 0").text("<?=$lang['WIDGET']['BUTTON']['VALUE'][$language]?>")
				).append(
					$("<td></td>").css("padding", "3px 0").append(
						$("<input/>").attr({"type": "number", "id": "widget-draw-button-value", "value": "0"})
					)
				)
			)
		);
		//widget Edit
		if(settings){
			$content.find("#widget-draw-button-color").val(settings.draw.button_color);
			$content.find("#widget-draw-button-text").val(settings.draw.button_text);
			$content.find("#widget-draw-button-value").val(settings.draw.button_value);
			if(typeof(settings.draw.button_icon) == "undefined" ||settings.draw.button_icon == "none")
				$content.find("#div_icon").hide();
			else{
				$img=$("<div></div>").css("background-image",settings.draw.button_icon).css({"height":"25px","width":"25px","margin":"2px","background-size":"contain","background-repeat":"no-repeat","background-position": "center center"});
				
				$content.find("#div_icon").html($img).show();
				$content.find("#div_icon").append(
					$("<div></div>").css("fontSize", "0").attr({
						tip: "<?=$lang['WIDGET']['BUTTON']['REMOVE'][$language]?>",
						tip_position: "right"
					}).append($(createSVGIcon("image/ics.svg", "delete")).css("fill", "#707070")).css({"cursor": "pointer"}).click(function(){$(this.previousSibling).css("background-image", "");$("#div_icon").hide();}).hover(function(){
						$(this).find("svg").css("fill", "#2A2A2A");
					}, function(){
						$(this).find("svg").css("fill", "#707070");
					})
				)

				bindTipEvent($content.find("#div_icon svg").closest("div"));
			}
		}
		$("#widget-draw-button-color").each(function(){
			$(this).triggerHandler("change");
		});
	},
	channelUpdated: function($content, channels){
	},
	settingSaved: function(settings, channels){
		settings.draw = {
			"button_color": $("#widget-draw-button-color").val(),
			"button_text": $("#widget-draw-button-text").val(),
			"button_value":$("#widget-draw-button-value").val(),
			"button_icon": $($("#div_icon > div")[0]).css("background-image")
		};
	},
	widgetCreated: function($content, settings, channels){
		var dashboard = window.accounts[$("#dashboard-switch-handler").attr("account_uid")].dashboards[$("#dashboard-switch-handler").attr("dashboard_uid")];
		$content.append(
			$("<div></div>").css({"height":"100%", "padding":"0px", "box-sizing": "border-box", "padding": "0px 30px"}).addClass("button").addClass(settings.draw.button_color).attr("disabled", dashboard.share || guestLevel == 0).append(
				$("<div></div>").addClass("widget_button").css({"display": "flex", "alignItems": "center"}).append(
				$("<div></div>").css("display",function(){return "block";})
					.css("background-image",settings.draw.button_icon)
					.css("display",function(){if(typeof(settings.draw.button_icon) == "undefined" ||settings.draw.button_icon == "none")return "none"; else return "block";})
					.css({"height":"12px","width":"12px","background-size":"contain","background-repeat":"no-repeat", "background-position": "center center"})
				
				).append(
					$("<div></div>").css({"fontSize": "14px","paddingLeft":"1px"}).css("display",function(){if(settings.draw.button_text == "") return "none"; else return "block";}).text(settings.draw.button_text)
				)
			).bind("mousedown", function(event){
				$(this).data("drag", false);
			}).bind("mousemove", function(event){
				$(this).data("drag", true);
			}).bind("mouseup", function(event){
				if($(this).data("drag") || dashboard.share == true || guestLevel == 0){
					return;
				}
				
				// is click
				var button_obj = $(this);
				var setChannelData_array = [];
				
				//showload
				if($(this).attr("status")== "loading")
					return;
				$(this).attr({"status":"loading"});
				$(this).html('<div class="widget_button"><div class="circle-loader"><div class="checkmark draw"></div><div class="checkfork draw"></div></div></div>');
				var $value = $(this).find(".widget_button");
				var $container = $value.parent();
				var ratio = ($container.width() / $value.width())-0.2;
				if(ratio * $value.height() > $container.height()){
					ratio = ($container.height() / $value.height())-0.2;
				}
				$value.css("transform", " translate(-50%,-50%) scale(" + ratio + ") ");
				//channels output function
				for(var accountUID in channels){
					for(var deviceUID in channels[accountUID]){
						for(var moduleUID in channels[accountUID][deviceUID]){
							for(var channel in channels[accountUID][deviceUID][moduleUID]){
								setChannelData_array.push(
									setChannelData(accountUID, deviceUID, moduleUID, channel, settings.draw.button_value).done(function(){
										
									}).fail(function(jqXHR, textStatus, errorThrown){
										
									}).always(function(){
										
									})
								);
							}
						}
					}
				}
				
				$.when.apply($, setChannelData_array).done(function() {
					if ($(button_obj.find('.circle-loader')).hasClass('load-complete')) {
						$(button_obj.find('.circle-loader')).removeClass('load-complete load-success load-failure');
						$(button_obj.find('.checkmark')).hide();
					} 
					else {
						$(button_obj.find('.circle-loader')).addClass('load-complete load-success');
						$(button_obj.find('.checkmark')).show();
					}
					
				}).fail(function(){
					if($(button_obj.find('.circle-loader')).hasClass('load-complete')) {
						$(button_obj.find('.circle-loader')).removeClass('load-complete load-success load-failure');
					}
					else{
						$(button_obj.find('.circle-loader')).addClass('load-complete load-failure');
					}
					$(button_obj.find('.checkmark')).hide();
					
					
				}).then(function(){
					setTimeout(
						function($button_obj){
							$button_obj.html(
								$('<div></div>').addClass("widget_button").css({"display": "flex", "alignItems": "center"}).append(
									$("<div></div>").css("background-image",settings.draw.button_icon).css({"height":"12px","width":"12px","background-size":"contain","background-repeat":"no-repeat","background-position": "center center"}).css("display",function(){
										if(typeof(settings.draw.button_icon) == "undefined" || settings.draw.button_icon == "none")return "none"; else return "block";
									})
								).append(
									$("<div></div>").css({"fontSize": "14px","paddingLeft":"1px"}).css("display",function(){if(settings.draw.button_text == "") return "none"; else return "block";}).text(settings.draw.button_text)
								)
							);
							var $button = $(button_obj).find(".widget_button");
							var $container = $button.parent();
							var ratio = ($container.width() / $button.width())-0.2;
							if(ratio * $button.height() > $container.height()){
								ratio = ($container.height() / $button.height())-0.2;
							}
							$button.css("transform", " translate(-50%,-50%) scale(" + ratio + ") ");
							button_obj.removeAttr("status");
						},
					2000,button_obj);
					
				},function(){
					setTimeout(
						function($button_obj){
							$button_obj.html('<div class="widget_button">' + settings.draw.button_text + '</div>');
							var $button = $(button_obj).find(".widget_button");
							var $container = $button.parent();
							var ratio = ($container.width() / $button.width())-0.2;
							if(ratio * $button.height() > $container.height()){
								ratio = ($container.height() / $button.height())-0.2;
							}
							$button.css("transform", " translate(-50%,-50%) scale(" + ratio + ") ");
							button_obj.removeAttr("status");
						},
					2000,button_obj);
				});
			})
		);
		
		$content.data("widget_button", $content.find(".widget_button"));
		this.adjustButtonSize($content);
	},
	widgetUpdated: function($content, settings, channels){
		$content.empty();
		this.widgetCreated($content, settings, channels);
	},
	widgetRemoved: function($content, settings){
	},
	dataUpdated: function($content, settings){
	},
	dragstop: function($content, settings){
	},
	resizestart: function($content, settings){
	},
	resizing: function($content, settings){
		this.adjustButtonSize($content);
	},
	resizestop: function($content, settings){
		this.adjustButtonSize($content);
	},
	adjustButtonSize: function($content){
		var $button = $content.find(".widget_button");
		var $container = $button.parent();
		var ratio = ($container.width() / $button.width())-0.2;
		if(ratio * $button.height() > $container.height()){
			ratio = ($container.height() / $button.height())-0.2;
		}
		$button.css("transform", " translate(-50%,-50%) scale(" + ratio + ") ");
	}
};
