<?php
global $language;

$lang["WIDGET"]["MAP"] = array(
	"MARKER" => array(
		"en" => "Marker",
		"tw" => "標記",
		"cn" => "标记"
	),
	"LABELNAME" => array(
		"en" => "Label Name",
		"tw" => "標記名稱",
		"cn" => "标记名称"
	),
	"LATITUDE" => array(
		"en" => "Latitude",
		"tw" => "緯度",
		"cn" => "纬度"
	),
	"LONGITUDE" => array(
		"en" => "Longitude",
		"tw" => "經度",
		"cn" => "经度"
	),
	"ICON" => array(
		"en" => "Icon",
		"tw" => "圖標",
		"cn" => "图标"
	),
	"GO" => array(
		"en" => "Go",
		"tw" => "前往",
		"cn" => "前往"
	),
	"BROWSE" => array(
		"en" => "Browse...",
		"tw" => "瀏覽...",
		"cn" => "浏览..."
	),
	"ADD" => array(
		"en" => "Add",
		"tw" => "新增",
		"cn" => "新增"
	),
	"SAVE" => array(
		"en" => "Save",
		"tw" => "儲存",
		"cn" => "储存"
	),
	"REMOVE" => array(
		"en" => "Remove",
		"tw" => "刪除",
		"cn" => "删除"
	),
	"DEFAULTZOOMSIZE" => array(
		"en" => "Default Zoom Size",
		"tw" => "預設縮放大小",
		"cn" => "预设缩放大小"
	),
	"BACK" => array(
		"en" => "Back",
		"tw" => "返回",
		"cn" => "返回"
	),
	"NO_MARKER_EXIST" => array(
		"en" => "No Marker Exist",
		"tw" => "無任何標記存在",
		"cn" => "无任何标记存在"
	),
	"CLICK_TO_ADD_MARKER" => array(
		"en" => "Click on the map to add a marker.",
		"tw" => "點選地圖以新增標記。",
		"cn" => "点选地图以新增标记。"
	),
	"EnterAddr" => array(
		"en" => "Enter address",
		"tw" => "請輸入地址",
		"cn" => "请输入位址"
	),
	"NotFoundMessage" => array(
		"en" => "Sorry, that address could not be found.",
		"tw" => "抱歉，無法搜尋該地址。",
		"cn" => "抱歉，无法搜寻该地址。"
	)
);
?>

widget.map = {
	minChannelNumber: 0,
	maxChannelNumber: 0,
	defaultWidth: 10,
	defaultHeight: 10,

	// Event function
	settingCreated: function($content, settings){
		$content.data("MapReloadMarkersAndList_Edit",this.MapReloadMarkersAndList_Edit);
		$content.data("MapReloadMarkers_opacity",this.MapReloadMarkers_opacity);
		
		$content.append(
			$("<table></table>").attr({"cellSpacing": "0","cellPadding": "0","border": "0"}).append(
				$("<tr></tr>").append(
					$("<td></td>").attr({
						"colSpan": "999",
						"class": "content-title"
					}).css("paddingBottom", "10px").text("<?=$lang['WIDGET']['MAP']['MARKER'][$language]?>")
				)
			).append(
				$("<tr></tr>").append(
					$("<td></td>").attr({
						"colSpan": "999"
					})
				)
			)		
		).append(
			$("<div></div>").css({
				"width":"100%",
				"height": "470px",
				"border": "1px solid #CCC",
				"width": "100%",
				"boxSizing": "border-box",
				"position": "relative",
				"overflow": "hidden"
			}).append(
				$("<div></div>").attr("id","PerviewMap").css({
					"width":"100%",
					"height":"100%",
					"position": "absolute"
				})
			).append(
				$("<div></div>").attr("id","markerList").css({
					"height":"100%",
					"width":"270px",
					"position": "absolute",
					"background-color": "#FFF",
					"right": "0px",
					"top":"0px",
					"zIndex": "400",
    				"boxSizing": "border-box",
					"overflow": "hidden auto",
    				"borderLeft": "1px solid #CCC",
					"boxShadow": "0 0 24px rgba(0, 0, 0, 0.4)",
					"display": "none"
				})
			).append(
				$("<div></div>").attr({
					"id":"markerEdit",
					"edit_idx":""
				}).css({
					"height":"100%",
					"width":"270px",
					"position": "absolute",
					"background-color": "#FFF",
					"right": "0px",
					"top":"0px",
					"zIndex": "400",
					"padding": "10px",
    				"boxSizing": "border-box",
    				"borderLeft": "1px solid #CCC",
					"boxShadow": "0 0 24px rgba(0, 0, 0, 0.4)",
					"display": "none"
				}).append(
					$("<div></div>").attr({"id":"edit_back"}).css({"cursor": "pointer", "color": "##035002c9", "position": "relative", "left": "-3px"}).append(
						$(createSVGIcon("image/ics.svg", "arrow_back")).css({"verticalAlign": "middle"})
					).append(
						$("<span></span>").addClass("edit_title").css({"verticalAlign": "middle", "marginLeft": "2px"}).text("<?=$lang['WIDGET']['MAP']['BACK'][$language]?>")
					).hover(function(){
						$(this).find("svg").css("fill", "#2A2A2A");
						$(this).css("color", "#2A2A2A");
					}, function(){
						$(this).find("svg").css("fill", "##035002c9");
						$(this).css("color", "##035002c9");
					})
				).append(
					$("<div></div>").css({
					    "border-bottom": "1px solid #cacaca",
						"box-shadow": "0 1px 0 #FFF",
						"margin": "10px 0"
					})
				).append(
					$("<div></div>").addClass("edit_title").text("<?=$lang['WIDGET']['MAP']['LABELNAME'][$language]?>")
				).append(
					$("<input>").addClass("edit_info_text").attr({"id":"widget-map-markerLabel","type":"text"})
				).append(
					"<div>&nbsp;</div>"
				).append(
					$("<div></div>").addClass("edit_title").text("<?=$lang['WIDGET']['MAP']['LATITUDE'][$language]?>")
				).append(
					$("<input>").addClass("edit_info_text").attr({"id":"widget-map-latitude-text","type":"number"})
				).append(
					"<div>&nbsp;</div>"
				).append(
					$("<div></div>").addClass("edit_title").text("<?=$lang['WIDGET']['MAP']['LONGITUDE'][$language]?>")
				).append(
					$("<input>").addClass("edit_info_text").attr({"id":"widget-map-longitude-text","type":"number"})
				).append(
					"<div>&nbsp;</div>"
				).append(
					$("<div></div>").addClass("edit_title").text("<?=$lang['WIDGET']['MAP']['ICON'][$language]?>")
				).append(
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
											$("<div></div>").attr("base64_code", data).css("background-image","url(" + data + ")").css({"height":"25px","width":"25px","margin":"2px","background-size":"contain","background-repeat":"no-repeat", "background-position": "center center"})
										).append(
											$("<div></div>").css("fontSize", "0").attr({
												tip: "Remove",
												tip_position: "right"
											}).append($(createSVGIcon("image/ics.svg", "delete")).css("fill", "##035002c9")).css({"cursor": "pointer"}).click(function(){
												document.getElementById('uploadIcon').value = '';
												$(this.previousSibling).css("background-image", "").removeAttr("base64_code");
												$("#div_icon").hide();
												
												//map Icon
												layer2.clearLayers();
												var Marker;
												if($("#widget-map-markerLabel").val() == "")
													Marker =  L.marker([$("#widget-map-latitude-text").val(), $("#widget-map-longitude-text").val()], {draggable:true});
												else
													Marker =  L.marker([$("#widget-map-latitude-text").val(), $("#widget-map-longitude-text").val()], {draggable:true}).bindTooltip($("#widget-map-markerLabel").val(),{permanent: false, direction: 'top'});
												Marker.on('drag', function(){
													var coord = Marker.getLatLng();
													$("#widget-map-latitude-text").val(coord.lat);
													$("#widget-map-longitude-text").val(coord.lng);
												});
												layer2.addLayer(Marker);
											}).hover(function(){
												$(this).find("svg").css("fill", "#2A2A2A");
											}, function(){
												$(this).find("svg").css("fill", "##035002c9");
											})
										).show();

										bindTipEvent($content.find("#div_icon svg").closest("div"));
										
										//map更換Icon
										layer2.clearLayers();
										
										var imgData = data;
										var img = new Image();
										
										img.onload = function(){
											var imgsize = [,40];
											var Anchor =[20,40];
											var tipAnchor = [0,-40];
											
											if(img.width > img.height){
												imgsize = [40,];
												Anchor = [20,(img.height/(img.width/40))]
												tipAnchor = [0,-(img.height/(img.width/40))];
											}
											else{
												imgsize = [,40];
												Anchor = [(img.width/(img.height/40))/2,40]
												tipAnchor = [0,-40];
											}
											var myIcon = L.icon({
											iconUrl: imgData,
											iconSize: imgsize,
											iconAnchor:Anchor,
											tooltipAnchor:tipAnchor
											});
											
											var Marker;
											if($("#widget-map-markerLabel").val() == "")
												Marker = L.marker([$("#widget-map-latitude-text").val(), $("#widget-map-longitude-text").val()],{icon: myIcon, draggable:true});
											else
												Marker = L.marker([$("#widget-map-latitude-text").val(), $("#widget-map-longitude-text").val()],{icon: myIcon, draggable:true}).bindTooltip($("#widget-map-markerLabel").val(),{permanent: false, direction: 'top'});
												
											Marker.on('drag', function(){
												var coord = Marker.getLatLng();
												$("#widget-map-latitude-text").val(coord.lat);
												$("#widget-map-longitude-text").val(coord.lng);
											});
											layer2.addLayer(Marker);
										}
										img.src = imgData;
									})
								})
							}
							$("#uploadIcon").val('');
						})
					)
				).append(
					$("<div></div>").css("display","flex").append(
						$("<button></button>").addClass("gray").css({"white-space":"nowrap","marginRight":"3px","padding":"6px 14px"}).text("<?=$lang['WIDGET']['MAP']['BROWSE'][$language]?>").bind("click",function(){$("#uploadIcon").click();})
					).append(
						$("<div></div>").attr("id","div_icon").css({"display": "flex", "alignItems": "center"})
					)
				).append(
					$("<div></div>").css({
					    "border-bottom": "1px solid #cacaca",
						"box-shadow": "0 1px 0 #FFF",
						"margin": "10px 0"
					})
				).append(
					$("<div></div>").css({"width": "100%","display": "flex"}).append(
						$("<input>").attr({"id":"edit_ok","type":"button"}).css({"margin-right":"5px","width":"50%"})
					).append(
						$("<input>").attr({"id":"edit_delete","type":"button"}).css({"width":"50%"}).addClass("red").val("<?=$lang['WIDGET']['MAP']['REMOVE'][$language]?>")
					)
				)
			).append(
				$("<table></table>").css({"width":"100%"}).append(
					$("<tr></tr>").css("display","none").append(
						$("<td></td>").append(
							$("<div></div>").text("<?=$lang['WIDGET']['MAP']['DEFAULTZOOMSIZE'][$language]?>:")
						).append(
							$("<input>").attr({"id":"zoom_range","type":"range", "min":"2", "max":"17", "value":"5", "step":"1"}).css({"width":"150px"})
						)
					)
				)
			)
		);

		var that = this;

		var map = L.map("PerviewMap",{
			//maxBounds: L.latLngBounds([-90, -180], [90, 180]),
			attributionControl: false,
			worldCopyJump: true,
			minZoom:2,
			maxZoom:17,
		}).setView([0,0], 4);
		L.control.attribution({position: 'bottomleft'}).addTo(map);
		
		map.setActiveArea({
		    position: 'absolute',
		    top: '0px',
		    left: '0px',
		    right: '270px',
		    bottom: '0px'
		});

		new L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '<a href="https://www.openstreetmap.org/">OSM</a>',
		}).addTo(map);
		
		map.on("zoomend", function (e) { $("#zoom_range").val(this.getZoom()); });
		$("#zoom_range").bind("change",function(){
			map.setZoom($(this).val());
		});

		new L.Control.GeoSearch({
			provider: new L.GeoSearch.Provider.OpenStreetMap(),
			searchLabel:"<?=$lang['WIDGET']['MAP']['EnterAddr'][$language]?>",
			notFoundMessage:"<?=$lang['WIDGET']['MAP']['NotFoundMessage'][$language]?>",
			showMarker: false,
			zoomLevel:14
		}).addTo(map);
		$(".leaflet-control-geosearch >form").click(function(event){event.stopPropagation();});

		var layer1 = L.layerGroup().addTo(map);//所有標記圖層
		var layer2 = L.layerGroup().addTo(map);//新標記或Edit標記圖層
		var marker_list = [];

		var show_list = function(){
			$("#PerviewMap").attr("view", "list");
			$('#markerEdit').css({right: "0px"}).animate({right: "-270px"},function(){
				$(this).hide();
				$('#markerList').css({right: "-270px"}).show().animate({right: "0px"});
				if(marker_list.length <= 0){
					$('#markerList').empty().append(
						$("<table></table>").css({
							"width": "100%",
							"height": "100%",
							"textAlign": "center",
							"color": "##035002c9"
						}).append(
							$("<tr></tr>").append(
								$("<td></td>").append(
									$("<div></div>").css({
										"marginBottom": "10px",
										"fontWeight": "bold"
									}).text("<?=$lang['WIDGET']['MAP']['NO_MARKER_EXIST'][$language]?>")
								).append(
									$("<div></div>").css({
										"fontSize": "13px"
									}).text("<?=$lang['WIDGET']['MAP']['CLICK_TO_ADD_MARKER'][$language]?>")
								)
							)
						)
					);
				}
			});
		};

		//新增marker
		map.on('click', function(e){
			if($("#PerviewMap").attr("view") == "edit"){
				return;
			}
			
			layer1.clearLayers();//將所有標記清除
			$content.data("MapReloadMarkers_opacity")(marker_list,map,layer1);
			
//			map.panTo([e.latlng.lat, e.latlng.lng]);
			var Marker = L.marker([e.latlng.lat, e.latlng.lng],{draggable:true});
			Marker.on('drag', function(){
				var coord = Marker.getLatLng();
				$("#widget-map-latitude-text").val(coord.lat);
				$("#widget-map-longitude-text").val(coord.lng);
			});
			layer2.addLayer(Marker);
			
			$("#widget-map-latitude-text").val(e.latlng.lat);
			$("#widget-map-longitude-text").val(e.latlng.lng);
			if($("#markerEdit").attr("edit_idx")==""){
				$("#edit_ok").val("<?=$lang['WIDGET']['MAP']['ADD'][$language]?>");
				$("#edit_delete").css("visibility", "hidden");
			}
			else{
				$("#edit_ok").val("<?=$lang['WIDGET']['MAP']['SAVE'][$language]?>");
				$("#edit_delete").css("visibility", "visible");
			}

			$("#PerviewMap").attr("view", "edit");
			$('#markerList').css({right: "0px"}).animate({right: "-270px"},function(){
				$(this).hide();
				$('#markerEdit').css({right: "-270px"}).show().animate({right: "0px"});
			});
		});
		
		//go lat lng
		var goLatLng = function(){
			layer2.clearLayers();
			map.panTo([$("#widget-map-latitude-text").val(),$("#widget-map-longitude-text").val()]);
			if(typeof($($("#div_icon>div")[0]).attr("base64_code"))=="undefined"){
				var Marker = L.marker([$("#widget-map-latitude-text").val(),$("#widget-map-longitude-text").val()],{draggable:true});
				Marker.on('drag', function(){
					var coord = Marker.getLatLng();
					$("#widget-map-latitude-text").val(coord.lat);
					$("#widget-map-longitude-text").val(coord.lng);
				});
				layer2.addLayer(Marker);
			}
			else{
				var imgData = $($("#div_icon>div")[0]).attr("base64_code");
				var img = new Image();
				img.onload = function(){
					var imgsize = [,40];
					var Anchor =[20,40];
					var tipAnchor = [0,-40];
					
					if(img.width > img.height){
						imgsize = [40,];
						Anchor = [20,(img.height/(img.width/40))]
						tipAnchor = [0,-(img.height/(img.width/40))];
					}
					else{
						imgsize = [,40];
						Anchor = [(img.width/(img.height/40))/2,40]
						tipAnchor = [0,-40];
					}
					var myIcon = L.icon({
					iconUrl: imgData,
					iconSize: imgsize,
					iconAnchor:Anchor,
					tooltipAnchor:tipAnchor
					});
					var Marker = L.marker([$("#widget-map-latitude-text").val(),$("#widget-map-longitude-text").val()],{icon: myIcon, draggable:true});
					Marker.on('drag', function(){
						var coord = Marker.getLatLng();
						$("#widget-map-latitude-text").val(coord.lat);
						$("#widget-map-longitude-text").val(coord.lng);
					});
					layer2.addLayer(Marker);
				}
				img.src = imgData;
			}
		}
		var timeout = null;
		$("#widget-map-latitude-text").on('keyup input', function () {
			var that = this;
			if (timeout !== null) {
				clearTimeout(timeout);
			}
			timeout = setTimeout(function () {
				goLatLng();
			}, 500);
		});
		
		var timeout = null;
		$("#widget-map-longitude-text").on('keyup input', function () {
			var that = this;
			if (timeout !== null) {
				clearTimeout(timeout);
			}
			timeout = setTimeout(function () {
				goLatLng();
			}, 500);
		});
		
		
		$("#edit_back").bind("click",function(){
			$("#markerEdit").attr("edit_idx","");

			$("#widget-map-markerLabel").val("");
			$("#div_icon").empty();
			layer2.clearLayers();
			layer1.clearLayers();
			
			var markerfunc = function(idx, markerinfo){
				if(typeof(markerinfo.icon) == "undefined"){
					var myIcon = L.icon({
						iconUrl: "./js/leaflet/images/marker-icon.png",
						iconSize: [25,41],
						iconAnchor:[(25/2),41],
						tooltipAnchor:[0,-41]
					});
					if(markerinfo.label == "")
						layer1.addLayer(L.marker([markerinfo.lat, markerinfo.lng],{icon:myIcon}).on('click',function(){$(".marker_div[idx=" + idx + "]").trigger("click"); }));
					else
						layer1.addLayer(L.marker([markerinfo.lat, markerinfo.lng],{icon:myIcon}).bindTooltip(markerinfo.label, {permanent: false, direction: 'top'}).on('click',function(){$(".marker_div[idx=" + idx + "]").trigger("click"); }));
				}
				else{
					var imgData = markerinfo.icon;
					var img = new Image();
					img.onload = function(){
						var imgsize = [,40];
						var Anchor =[20,40];
						var tipAnchor = [0,-40];
						
						if(img.width > img.height){
							imgsize = [40,];
							Anchor = [20,(img.height/(img.width/40))]
							tipAnchor = [0,-(img.height/(img.width/40))];
						}
						else{
							imgsize = [,40];
							Anchor = [(img.width/(img.height/40))/2,40]
							tipAnchor = [0,-40];
						}
						var myIcon = L.icon({
						iconUrl: imgData,
						iconSize: imgsize,
						iconAnchor:Anchor,
						tooltipAnchor:tipAnchor
						});
						if(markerinfo.label == "")
							layer1.addLayer(L.marker([markerinfo.lat, markerinfo.lng],{icon: myIcon}).on('click',function(){$(".marker_div[idx=" + idx + "]").trigger("click"); }));
						else
							layer1.addLayer(L.marker([markerinfo.lat, markerinfo.lng],{icon: myIcon}).bindTooltip(markerinfo.label, {permanent: false, direction: 'top'}).on('click',function(){$(".marker_div[idx=" + idx + "]").trigger("click"); }));
					}
					img.src = imgData;
				}
			}
			
			for(var marker in marker_list){
				markerfunc(marker, marker_list[marker]);
			}

			show_list();
		});
		
		
		$("#edit_ok").bind("click",function(){
			if($("#markerEdit").attr("edit_idx")==""){
				marker_list.push({label:$("#widget-map-markerLabel").val(),lat:$("#widget-map-latitude-text").val(),lng:$("#widget-map-longitude-text").val(),icon:$($("#div_icon > div")[0]).attr("base64_code")});
				$('#markerList').empty();
			}
			else{
				marker_list[$("#markerEdit").attr("edit_idx")] = {label:$("#widget-map-markerLabel").val(),lat:$("#widget-map-latitude-text").val(),lng:$("#widget-map-longitude-text").val(),icon:$($("#div_icon > div")[0]).attr("base64_code")};
				$('#markerList').empty();
			}
			
			//重新標記所有marker
			$content.data("MapReloadMarkersAndList_Edit")(marker_list,map,layer1,layer2);
			
			$("#markerEdit").attr("edit_idx","");

			$("#widget-map-markerLabel").val("");
			$("#div_icon").empty();
			layer2.clearLayers();

			show_list();
		});
		
		$("#edit_delete").bind("click",function(){
			marker_list.splice($("#markerEdit").attr("edit_idx"), 1);
			$('#markerList').empty();
			
			//重新標記所有marker
			$content.data("MapReloadMarkersAndList_Edit")(marker_list,map,layer1,layer2);
			
			
			$("#markerEdit").attr("edit_idx","");

			$("#widget-map-markerLabel").val("");
			$("#div_icon").empty();
			layer2.clearLayers();

			show_list();
		});
		
		if(settings){
			for(var marker in settings.draw.marker_list){
				marker_list.push(settings.draw.marker_list[marker]);
			}
			$content.data("MapReloadMarkersAndList_Edit")(marker_list,map,layer1,layer2);

			var map_center = this.cal_center(marker_list);
			map.setView(map_center,settings.draw.zoomsize);
		}

		show_list();

		var zoom = map.getZoom();
		var point = map.options.crs.latLngToPoint(map.getCenter(), zoom);
		point.x += (270 / 2);
		map.setView(map.options.crs.pointToLatLng(point, zoom));
		
		$content.data("perviewmap",map);
	},
	channelUpdated: function($content, channels){
		$content.data("perviewmap")._onResize();
	},
	settingSaved: function(settings, channels){
		var marker_list = [];
		for(var marker_idx=0; marker_idx < $("#markerList>.marker_div").length; marker_idx++){
			marker_list.push({
				label:$($("#markerList>.marker_div")[marker_idx]).attr("label"),
				lat:$($("#markerList>.marker_div")[marker_idx]).attr("lat"),
				lng:$($("#markerList>.marker_div")[marker_idx]).attr("lng"),
				icon:$($("#markerList>.marker_div")[marker_idx]).attr("icon"),
			});
		}

		settings.draw = {
			"zoomsize":$("#zoom_range").val(),
			"marker_list":marker_list
		}
	},
	widgetCreated: function($content, settings){
		var marker_list = settings.draw.marker_list;
		var map_center = [0,0];
		
		map_center = this.cal_center(marker_list);
		
		
		$content.append($("<div></div>").attr("id","widget_map").css({"width":"100%","height":"100%","z-index": "0"}));
		
		var map = L.map($content.find("#widget_map")[0],{
			attributionControl: false,
			worldCopyJump: true,
			minZoom:2,
			maxZoom:17,
		}).setView(map_center, settings.draw.zoomsize);
		L.control.attribution({position: 'bottomleft'}).addTo(map);
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '<a href="https://www.openstreetmap.org/">OSM</a>',
		}).addTo(map);
		
		var accountUID = '<?=$_SESSION["account_uid"]?>';
		if(window.accounts[$("#dashboard-switch-handler").attr("account_uid")].dashboards[$("#dashboard-switch-handler").attr("dashboard_uid")].lock || accountUID != $("#dashboard-switch-handler").attr("account_uid"))
			map.dragging.enable();//可移動
		else
			map.dragging.disable();//不可移動
		
		var layer_w = L.layerGroup().addTo(map);
		
		this.MapReloadMarkers_Widget(marker_list,map,layer_w);
		
		$content.data("map",map);
		$content.data("layer_w",layer_w);
	},
	widgetUpdated: function($content, settings){
		var map = $content.data("map");
		var layer_w = $content.data("layer_w");
		var marker_list = settings.draw.marker_list;
		layer_w.clearLayers();
		
		this.MapReloadMarkers_Widget(marker_list,map,layer_w);
		map.setView(this.cal_center(marker_list),settings.draw.zoomsize);
	},
	widgetRemoved: function($content, settings){
	},
	dataUpdated: function($content, settings, channels){
		
	},
	dragstop: function($content, settings){
		
	},
	resizestart: function($content, settings){

	},
	resizing: function($content, settings){
		
	},
	resizestop: function($content, settings){
		$content.data("map").invalidateSize();
	},
	cal_center(marker_list){
		var map_center = [0,0];
		if(marker_list.length == 1){
			map_center = [marker_list[0].lat,marker_list[0].lng];
		}
		else if(marker_list.length > 1){
			var max_lat,min_lat,max_lng,min_lng;
			for(var marker in marker_list){
				if(marker == 0){
					max_lat = marker_list[marker].lat;
					min_lat = marker_list[marker].lat;
					max_lng = marker_list[marker].lng;
					min_lng = marker_list[marker].lng;
				}
				else{
					if(marker_list[marker].lat*1 > max_lat*1)
						max_lat = marker_list[marker].lat;
					if(marker_list[marker].lng*1 > max_lng*1)
						max_lng = marker_list[marker].lng;
					if(marker_list[marker].lat*1 < min_lat*1)
						min_lat = marker_list[marker].lat;
					if(marker_list[marker].lng*1 < min_lng*1)
						min_lng = marker_list[marker].lng;
				}
			}
			
			map_center = [(max_lat*1 + min_lat*1) /2,(max_lng*1 + min_lng*1) /2];
		}		
		return map_center;
	},
	async MapReloadMarkers_opacity(marker_list,map,layer1){
		layer1.clearLayers();
		var markerfunc = await function(idx, markerinfo){
			if(typeof(markerinfo.icon) == "undefined"){
				var myIcon = L.icon({
					iconUrl: "./js/leaflet/images/marker-icon.png",
					iconSize: [25,41],
					iconAnchor:[(25/2),41],
					tooltipAnchor:[0,-41]
				});
				if(markerinfo.label == "")
					layer1.addLayer(L.marker([markerinfo.lat, markerinfo.lng],{icon:myIcon, opacity: 0.5}));
				else
					layer1.addLayer(L.marker([markerinfo.lat, markerinfo.lng],{icon:myIcon, opacity: 0.5}).bindTooltip(markerinfo.label, {permanent: false, direction: 'top'}));
			}
			else{
				var imgData = markerinfo.icon;
				var img = new Image();
				img.onload = function(){
					var imgsize = [,40];
					var Anchor =[20,40];
					var tipAnchor = [0,-40];
					
					if(img.width > img.height){
						imgsize = [40,];
						Anchor = [20,(img.height/(img.width/40))]
						tipAnchor = [0,-(img.height/(img.width/40))];
					}
					else{
						imgsize = [,40];
						Anchor = [(img.width/(img.height/40))/2,40]
						tipAnchor = [0,-40];
					}
					var myIcon = L.icon({
						iconUrl: imgData,
						iconSize: imgsize,
						iconAnchor:Anchor,
						tooltipAnchor:tipAnchor
					});
					if(markerinfo.label == "")
						layer1.addLayer(L.marker([markerinfo.lat, markerinfo.lng],{icon: myIcon,opacity: 0.5}));
					else
						layer1.addLayer(L.marker([markerinfo.lat, markerinfo.lng],{icon: myIcon,opacity: 0.5}).bindTooltip(markerinfo.label, {permanent: false, direction: 'top'}));
				}
				img.src = imgData;
			}
		}
		for(var marker in marker_list){
			markerfunc(marker,marker_list[marker]);
		}
	},
	async MapReloadMarkersAndList_Edit(marker_list,map,layer1,layer2){
		layer1.clearLayers();
		var markerfunc = await function(idx, markerinfo){
			if(typeof(markerinfo.icon) == "undefined"){
				var myIcon = L.icon({
					iconUrl: "./js/leaflet/images/marker-icon.png",
					iconSize: [25,41],
					iconAnchor:[(25/2),41],
					tooltipAnchor:[0,-41]
				});
				if(markerinfo.label == "")
					layer1.addLayer(L.marker([markerinfo.lat, markerinfo.lng],{icon:myIcon}).on('click',function(){$(".marker_div[idx=" + idx + "]").trigger("click"); }));
				else
					layer1.addLayer(L.marker([markerinfo.lat, markerinfo.lng],{icon:myIcon}).bindTooltip(markerinfo.label, {permanent: false, direction: 'top'}).on('click',function(){$(".marker_div[idx=" + idx + "]").trigger("click"); }));
			}
			else{
				var imgData = markerinfo.icon;
				var img = new Image();
				img.onload = function(){
					var imgsize = [,40];
					var Anchor =[20,40];
					var tipAnchor = [0,-40];
					
					if(img.width > img.height){
						imgsize = [40,];
						Anchor = [20,(img.height/(img.width/40))]
						tipAnchor = [0,-(img.height/(img.width/40))];
					}
					else{
						imgsize = [,40];
						Anchor = [(img.width/(img.height/40))/2,40]
						tipAnchor = [0,-40];
					}
					var myIcon = L.icon({
					iconUrl: imgData,
					iconSize: imgsize,
					iconAnchor:Anchor,
					tooltipAnchor:tipAnchor
					});
					if(markerinfo.label == "")
						layer1.addLayer(L.marker([markerinfo.lat, markerinfo.lng],{icon: myIcon}).on('click',function(){$(".marker_div[idx=" + idx + "]").trigger("click"); }));
					else
						layer1.addLayer(L.marker([markerinfo.lat, markerinfo.lng],{icon: myIcon}).bindTooltip(markerinfo.label, {permanent: false, direction: 'top'}).on('click',function(){$(".marker_div[idx=" + idx + "]").trigger("click"); }));
				}
				img.src = imgData;
			}
		}
		
		//製作Marker List
		for(var marker in marker_list){
			$('#markerList').append(
				$("<div></div>").addClass("marker_div").attr({"idx":marker,"label":marker_list[marker].label,"lat":marker_list[marker].lat,"lng":marker_list[marker].lng,"icon":marker_list[marker].icon}).css({
					//"border-style": "dashed",
					"backgroundColor": "#FFF",
					"borderBottom": "#CCC 1px solid",
					"padding":"3px",
					"cursor":"pointer"
				}).mouseenter(
					function(){
						$(this).css("backgroundColor","#ececec");
						map.panTo([$(this).attr("lat"),$(this).attr("lng")]);
					}
				).mouseleave(
					function(){
						$(this).css("background","");
					}
				).click(
					function(){
						$("#markerEdit").attr("edit_idx",$(this).attr("idx"));
						$("#widget-map-markerLabel").val($(this).attr("label"));
						$("#widget-map-latitude-text").val($(this).attr("lat"));
						$("#widget-map-longitude-text").val($(this).attr("lng"));
						//Icon load
						
						if(typeof($(this).attr("icon")) == "undefined" ||$(this).attr("icon") == "none")
							$("#div_icon").hide();
						else{
							$img=$("<div></div>").attr("base64_code",$(this).attr("icon")).css("background-image","url("+$(this).attr("icon")+")").css({"height":"25px","width":"25px","margin":"2px","background-size":"contain","background-repeat":"no-repeat","background-position": "center center"});
							
							$("#div_icon").html($img).show();
							$("#div_icon").append(
								$("<div></div>").css("fontSize", "0").attr({
									tip: "Remove",
									tip_position: "right"
								}).append($(createSVGIcon("image/ics.svg", "delete")).css("fill", "##035002c9")).css({"cursor": "pointer"}).click(function(){
									$(this.previousSibling).css("background-image", "").removeAttr("base64_code");
									$("#div_icon").hide();
									layer2.clearLayers();

									var Marker;
									if($("#widget-map-markerLabel").val() == "")
										Marker = L.marker([$("#widget-map-latitude-text").val(), $("#widget-map-longitude-text").val()],{draggable:true});
									else
										Marker = L.marker([$("#widget-map-latitude-text").val(), $("#widget-map-longitude-text").val()],{draggable:true}).bindTooltip($("#widget-map-markerLabel").val(),{permanent: false, direction: 'top'});
									Marker.on('drag', function(){
										var coord = Marker.getLatLng();
										$("#widget-map-latitude-text").val(coord.lat);
										$("#widget-map-longitude-text").val(coord.lng);
									})
									layer2.addLayer(Marker);
								}).hover(function(){
									$(this).find("svg").css("fill", "#2A2A2A");
								}, function(){
									$(this).find("svg").css("fill", "##035002c9");
								})
							)
							bindTipEvent($("#div_icon svg").closest("div"));
						}

						$("#edit_ok").val("<?=$lang['WIDGET']['MAP']['SAVE'][$language]?>");
						$("#edit_delete").css("visibility", "visible");

						layer1.clearLayers();
						
						var markerfunc = function(idx, markerinfo){
							if(typeof(markerinfo.icon) == "undefined"){
								var myIcon = L.icon({
									iconUrl: "./js/leaflet/images/marker-icon.png",
									iconSize: [25,41],
									iconAnchor:[(25/2),41],
									tooltipAnchor:[0,-41]
								});
								if(markerinfo.label == "")
									layer1.addLayer(L.marker([markerinfo.lat, markerinfo.lng],{icon: myIcon, opacity: 0.5}));
								else
									layer1.addLayer(L.marker([markerinfo.lat, markerinfo.lng],{icon: myIcon, opacity: 0.5}).bindTooltip(markerinfo.label, {permanent: false, direction: 'top'}));
							}
							else{
								var imgData = markerinfo.icon;
								var img = new Image();
								img.onload = function(){
									var imgsize = [,40];
									var Anchor =[20,40];
									var tipAnchor = [0,-40];
									
									if(img.width > img.height){
										imgsize = [40,];
										Anchor = [20,(img.height/(img.width/40))]
										tipAnchor = [0,-(img.height/(img.width/40))];
									}
									else{
										imgsize = [,40];
										Anchor = [(img.width/(img.height/40))/2,40]
										tipAnchor = [0,-40];
									}
									var myIcon = L.icon({
									iconUrl: imgData,
									iconSize: imgsize,
									iconAnchor:Anchor,
									tooltipAnchor:tipAnchor
									});
									if(markerinfo.label == "")
										layer1.addLayer(L.marker([markerinfo.lat, markerinfo.lng],{icon: myIcon,opacity: 0.5}));
									else
										layer1.addLayer(L.marker([markerinfo.lat, markerinfo.lng],{icon: myIcon,opacity: 0.5}).bindTooltip(markerinfo.label, {permanent: false, direction: 'top'}));
								}
								img.src = imgData;
							}
						}
						for(var marker in marker_list){
							if( marker == $("#markerEdit").attr("edit_idx"))
								continue;
							markerfunc(marker,marker_list[marker]);
						}
						
						if(typeof($($("#div_icon>div")[0]).attr("base64_code"))=="undefined"){
							var myIcon = L.icon({
								iconUrl: "./js/leaflet/images/marker-icon.png",
								iconSize: [25,41],
								iconAnchor:[(25/2),41],
								tooltipAnchor:[0,-41]
							});
							var Marker = L.marker([$(this).attr("lat"), $(this).attr("lng")],{icon:myIcon,draggable:true});
							Marker.on('drag', function(){
								var coord = Marker.getLatLng();
								$("#widget-map-latitude-text").val(coord.lat);
								$("#widget-map-longitude-text").val(coord.lng);
							});
							layer2.addLayer(Marker);
						}
						else{
							var imgData = $(this).attr("icon");
							var lat = $(this).attr("lat");
							var lng = $(this).attr("lng");
							var img = new Image();
							img.onload = function(){
								var imgsize = [,40];
								var Anchor =[20,40];
								var tipAnchor = [0,-40];
								
								if(img.width > img.height){
									imgsize = [40,];
									Anchor = [20,(img.height/(img.width/40))]
									tipAnchor = [0,-(img.height/(img.width/40))];
								}
								else{
									imgsize = [,40];
									Anchor = [(img.width/(img.height/40))/2,40]
									tipAnchor = [0,-40];
								}
								var myIcon = L.icon({
									iconUrl: imgData,
									iconSize: imgsize,
									iconAnchor:Anchor,
									tooltipAnchor:tipAnchor
								});
								var Marker = L.marker([lat, lng],{icon: myIcon, draggable:true});
								Marker.on('drag', function(){
									var coord = Marker.getLatLng();
									$("#widget-map-latitude-text").val(coord.lat);
									$("#widget-map-longitude-text").val(coord.lng);
								});
								layer2.addLayer(Marker);
							}
							img.src = imgData;
						}

						$("#PerviewMap").attr("view", "edit");
						$('#markerList').css({right: "0px"}).animate({right: "-270px"},function(){
							$(this).hide();
							$('#markerEdit').css({right: "-270px"}).show().animate({right: "0px"});
						});
					}
				).css("fontSize", "13px").append(
					$("<table></table>").append(
						$("<tr></tr>").append(
							$("<td></td>").attr("rowspan","3").append(
								$("<div></div>").css("background-image","url("+(marker_list[marker].icon || "./js/leaflet/images/marker-icon.png")+")").css({"height":"40px","width":"40px","background-size":"contain","background-repeat":"no-repeat","background-position": "center center"})
							)
						).append(
							$("<td></td>").css({"fontWeight": "bold", "paddingRight": "8px"}).text("<?=$lang['WIDGET']['MAP']['LABELNAME'][$language]?>")
						).append(
							$("<td></td>").css({"color": "#555"}).text(marker_list[marker].label || "-")
						)
					).append(
						$("<tr></tr>").append(
							$("<td></td>").css({"fontWeight": "bold", "paddingRight": "8px"}).text("<?=$lang['WIDGET']['MAP']['LATITUDE'][$language]?>").css("white-space","nowrap")
						).append(
							$("<td></td>").css({"color": "#555"}).text(Math.floor(marker_list[marker].lat*1 * Math.pow(10, 10)) / Math.pow(10, 10))
						)
					).append(
						$("<tr></tr>").append(
							$("<td></td>").css({"fontWeight": "bold", "paddingRight": "8px"}).text("<?=$lang['WIDGET']['MAP']['LONGITUDE'][$language]?>").css("white-space","nowrap")
						).append(
							$("<td></td>").css({"color": "#555"}).text(Math.floor(marker_list[marker].lng*1 * Math.pow(10, 10)) / Math.pow(10, 10))
						)
					)
				)
			);
			markerfunc(marker,marker_list[marker]);
		}
	},
	async MapReloadMarkers_Widget(marker_list,map,layer_w){
		layer_w.clearLayers();
		
		var markerfunc = await function(markerinfo){
			if(typeof(markerinfo.icon) == "undefined"){
				var myIcon = L.icon({
						iconUrl: "./js/leaflet/images/marker-icon.png",
						iconSize: [25,41],
						iconAnchor:[(25/2),41],
						tooltipAnchor:[0,-41]
				});
				
				if(markerinfo.label == "")
					layer_w.addLayer(L.marker([markerinfo.lat, markerinfo.lng], {icon: myIcon}));
				else
					layer_w.addLayer(L.marker([markerinfo.lat, markerinfo.lng], {icon: myIcon}).bindTooltip(markerinfo.label, {permanent: false, direction: 'top'}));
			}
			else{
				var img = new Image();
				img.onload = function(){
					var imgsize = [,40];
					var Anchor =[20,40];
					var tipAnchor = [0,-40];
					
					if(img.width > img.height){
						imgsize = [40,];
						Anchor = [20,(img.height/(img.width/40))]
						tipAnchor = [0,-(img.height/(img.width/40))];
					}
					else{
						imgsize = [,40];
						Anchor = [(img.width/(img.height/40))/2,40]
						tipAnchor = [0,-40];
					}
					var myIcon = L.icon({
						iconUrl: markerinfo.icon,
						iconSize: imgsize,
						iconAnchor:Anchor,
						tooltipAnchor:tipAnchor
					});
					if(markerinfo.label == "")
						layer_w.addLayer(L.marker([markerinfo.lat, markerinfo.lng],{icon: myIcon}));
					else
						layer_w.addLayer(L.marker([markerinfo.lat, markerinfo.lng],{icon: myIcon}).bindTooltip(markerinfo.label, {permanent: false, direction: 'top'}));
				}
				img.src = markerinfo.icon;
			}
		}
		for(var marker in marker_list){
			markerfunc(marker_list[marker]);
		}
	}
};

