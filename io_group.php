<?php
function customized_header(){
	global $lang;
?>
<link rel="stylesheet" type="text/css" href="./css/tip.css">
<style type="text/css">
.control-button{
	padding-right:10px !important;
	padding-left:10px !important;
}

/* nav */
.group-nav{
	float:left;
	width:300px;
	border-right: 1px solid #ccc;
	padding-right:20px;
}

.group-nav-title{
	font-weight:bold;
	color:rgb(103, 106, 108);
	margin-bottom:10px;

}

.group-nav-item{
	color:#707070;
	fill:#707070;
	line-height:1.5;
	padding:10px 10px 10px 10px;
	border: 1px solid transparent;
	border-left: 4px solid transparent;
	position:relative;
	cursor: pointer;
}

.group-nav-item.active{
	border: 1px solid #ccc;
	border-left: 4px solid #ccc;
	background-color:#fff;
	/*box-shadow: 0 0 2px rgba(0,0,0,.12),0 2px 4px rgba(0,0,0,.24);*/
}

.group-nav-item svg,
.group-nav-item span{
	vertical-align:middle;
}

.group-nav-item.active > svg,
.group-nav-item.active > span,
.group-nav-item:hover > svg,
.group-nav-item:hover > span{
	color:#2A2A2A;
	fill:#2A2A2A;
}

.group-nav-item > svg{
	margin-right:10px;
}

.group-nav-control{
	margin-right:0px;
	float:right;
}

.group-nav-control svg{
	fill:#707070;
}

.group-nav-control svg.warning{
	fill:#ffd454;
}

.group-nav-item.active .group-nav-control svg,
.group-nav-item:hover .group-nav-control svg{
	fill:#2A2A2A;
}

/* list */
.group-list{
	margin-left:320px;
	border-left: 1px solid #ccc;
	padding-left:20px;
}

.group-list-title{
	color:#707070;
	font-weight:bold;
	margin-bottom:10px;
	position:relative;
}

.group-list-container{
	color:#707070;
	background-color:#fff;
	/*box-shadow: 0 0 2px rgba(0,0,0,.12),0 2px 4px rgba(0,0,0,.24);*/
	margin:0px 0 25px 0;
	border: 1px solid #ccc;
    border-bottom-width: 0;
}

.group-list-interface{
	border-bottom: 1px solid #ccc;
	padding:0px;
	font-size: 11px;
	font-weight: bold;
	background-color: #e5e9eb;
	text-align: center;
}

.group-list-wrapper{
	border-bottom: 1px solid #ccc;
	padding:8px;
}

.group-list-name{
	padding:3px 0;
	color:#2a2a2a;
}

.group-list-name > *{
	vertical-align:middle;
}

.group-list-table{
	display:table;
	width:100%;
}

.group-list-row{
	display: table-row;
}

.group-list-cell{
	display:table-cell;
	width:25%;
	padding:3px 0;
}

.group-list-cell > *{
	vertical-align:middle;
}

/* button */
#group-add-button{
	position:fixed;
	bottom:20px;
	right:20px;
	background-image: linear-gradient(to bottom, #4d90fe, #4787ed);
	box-shadow: 0 0 4px rgba(0,0,0,.14),0 4px 8px rgba(0,0,0,.28);
	border-radius: 50%;
	font-size:0;
	padding:10px;
	cursor: pointer;
}

#group-add-button:hover{
    background-image: linear-gradient(to bottom, #4d90fe, #357ae8);
}

#group-add-button:active{
    box-shadow: inset 1px 1px 5px rgba(0, 0, 0, 0.2), 0 0 4px rgba(0,0,0,.14),0 4px 8px rgba(0,0,0,.28);
}

#group-add-button > svg{
	fill:#FFF;
	width:24px;
	height:24px;
}

#group-add-button:active > svg{
/*
	position:relative;
	top:1px;
	left:1px;
*/
}

/* bar */
#group-bar-container{
	position:fixed;
	bottom:0px;
	left:0;
	background-color:#e5e9eb;
	width:100%;
	box-sizing:border-box;
	border-top:1px solid #bababa;
	padding:10px 20px 10px 230px;
	/*box-shadow: 0 0 24px rgba(0, 0, 0, 0.4);*/
}

.group-bar{
	display:table;
	width:100%;
}

#group-bar-left{
	box-sizing:border-box;
	display:table-cell;
	width:361px;
	padding-left:20px;
}

#group-bar-center{
	display:table-cell;
}

.group-bar-right{
	display:table-cell;
	text-align:right;
}

/* no exist */
#group-no-group-exist,
#group-no-module-exist{
	text-align:center;
	color:#707070;
	padding:100px 0;
}

.group-no-exist-title{
	margin-bottom:10px;
	font-weight:bold;
}

.group-no-exist-content{
	font-size:13px;
}

/* window */
.window-group-container{
	text-align:center;
	padding:20px 10px;
	box-sizing: border-box;
}

#window-group-name{
	width:200px;
}

.window-module-search{
	background-color:#e5e9eb;
	padding:5px;
	border-bottom: 1px solid #bababa;
}

.window-module-filter{
	position:relative;
	display:inline-block;
}

.window-module-filter .window-module-filter-icon{
	position:absolute;
	left:6px;
	top:50%;
	margin-top:-9px;
	border-right: 1px solid #ccc;
	padding-right:4px;
    font-size: 0;
}

.window-module-filter #window-module-filter-input{
	width:150px;
	padding-left:36px;
	padding-right:25px;
}

.window-module-filter #window-module-filter-clear{
	position:absolute;
	right:6px;
	top:50%;
	margin-top:-9px;
	cursor: pointer;
    font-size: 0;
}

.window-module-wrapper{
	position:relative;
}

.window-module-container{
	color:#707070;
	background-color:#FFF;
	overflow-x:hidden;
	overflow-y:scroll;
	height:400px;
}

.window-module-interface{
	padding:0px 8px;
	font-size:11px;
	font-weight:bolder;
	background-color:#e5e9eb;
	border-bottom: 1px solid #ccc;
	text-align:center;
}

.window-module{
	padding:8px 8px;
	border-bottom: 1px solid #ccc;
}

.window-module:after{
    content: "";
	display: block;
	clear:both;
}

.window-module-name{
	padding:3px 0;
	color:#2a2a2a;
}

.window-module-name > *{
	vertical-align:middle;
}

.window-module-table{
	display:table;
	width:100%;
}

.window-module-row{
	display: table-row;
}

.window-module-cell{
	display:table-cell;
	width:25%;
	padding:3px 0;
}

.window-module-cell > *{
	vertical-align:middle;
}

.window-module-left{
	float:left;
}

.window-module-left > *{
	vertical-align:middle;
}

.window-module-checkbox{
	margin-right:8px;
}

.window-module-right{
	float:right;
}

.window-module-right > *{
	vertical-align:middle;
}

#window-module-loader{
	position:absolute;
	top:50%;
	left:50%;
	margin-top:-16px;
	margin-left:-16px;
	display:none;
}

#window-module-no-match,
#window-module-is-empty{
	text-align:center;
	position:absolute;
	line-height:400px;
	width:100%;
	display:none;
}

.warning-message{
	display: table-cell;
    vertical-align: middle;
    padding: 10px 10px 10px 0;
    line-height: 150%;
}

.module-text{
	padding:40px 0;
	text-align:center;
	color:rgb(192, 192, 195);
	font-size:160%;
	font-weight:bold;
	display:block;
}
.ck_module{
    font-weight: bold;	
}
</style>
<script src="./js/jquery.tip.js"></script>
<script src="./js/jquery.livequery.min.js"></script>
<script language="JavaScript">
$("*[tip]").livequery(
	function(){
		$(this).hover(
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
		)
	},
	function(){
		clearTimeout($(this).attr("pid"));
		$("#" + $(this).attr("tip_id")).remove();
	}
);

loadgrouplist();
	
function createSVGIcon(path, name){
	var svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
	var use = document.createElementNS("http://www.w3.org/2000/svg", 'use');
	use.setAttributeNS("http://www.w3.org/1999/xlink", 'xlink:href', path + '#' + name);
	svg.appendChild(use);
	return svg;
}

function createGroupItem(name,uid){
	return $("<div></div>").attr({"class": "group-nav-item","name":"grouplist","group_uid": uid}).append(
		$("<div></div>").attr("class", "group-nav-control").append(
			$("<div></div>").css({
				"float": "left",
				"marginRight": "5px"
			}).attr("tip", "<?=$lang['GROUP']['EDIT'];?>").on('click', function(event){
				onClickEditGroupButton(uid);
				event.stopPropagation();
			}).append(
				$(createSVGIcon("image/ics.svg", "edit"))
			)
		).append(
			$("<div></div>").css({
				"float": "left"
			}).attr("tip", "<?=$lang['GROUP']['REMOVE'];?>").on('click', function(event){
				onClickDelGroupButton(uid,name);
				event.stopPropagation();
			}).append(
				$(createSVGIcon("image/ics.svg", "delete"))
			)
		)
	).append(
		createSVGIcon("image/ics.svg", "view_list")
	).append(
		$("<span></span>").text(name)
	).bind("click", function(){
		$(this).addClass("active").siblings().removeClass("active");
		group_uid=$(this).attr("group_uid");
		load_group_data($(this).attr("group_uid"));
		onClickBarBackButton();
	});
}

function popupWindow($popupWindow, callback){
	var $wrapper = $popupWindow.show().find(".popup-wrapper");
	$wrapper.css("marginTop", ($(window).height() / 2 - $wrapper.outerHeight() / 2 + $(window).scrollTop()) + "px");
	windowCallback = callback;
}


/********* Group List ***************/
function loadgrouplist(){
	$.ajax({
		url: "io_group_ajax.php?act=loadgrouplist",
		type: "POST",
		dataType: "xml",
		
		success: function(data, textStatus, jqXHR){
			var $xmlElement = $(data).find("group > list");
			if($xmlElement.length==0)
				$("#group-no-group-exist").show();
			else
			{
				for(var i = 0; i < $xmlElement.length; i++)
					CreatGroupOption($($xmlElement[i]).attr("name"),$($xmlElement[i]).attr("uid"));
				$($("[name=grouplist]")[0]).trigger( "click" );
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0){ return; }// include timeout

			alert(jqXHR.responseText);
		}
	});	
		
}

function onClickNewGroupButton(){
	$("#window-group-name").val("");
	popupWindow($("#window-group"), function(result){
		if(result == "ok"){
			if($("#window-group-name").val() == ""){
				popupErrorWindow("<?=$lang['GROUP']['GROUP_NAME_NOT_EMPTY'];?>")
				return;
			}
			$.ajax({
				url: "io_group_ajax.php?act=creatgroup",
				type: "POST",
				data: {
					groupname: $("#window-group-name").val()
				},
				success: function(data, textStatus, jqXHR){
					var $xmlElement = $(data).find("group > list");
					var $item = createGroupItem($("#window-group-name").val(), $($xmlElement[0]).attr("uid"));
					$("#group-nav-add").before($item);
					$item.trigger("click");
                    
					$("#group-no-group-exist").hide();
					$("#group, #group-add-button").show();
					
				},
				error: function(jqXHR, textStatus, errorThrown){
					if(jqXHR.status === 0){ return; }// include timeout

					alert(jqXHR.responseText);
				},
				complete: function(){
					$("#window-group").hide();
				}
			});	
		}
		else if(result == "cancel"){
			$("#window-group").hide();
		}
	});
	
	$("#window-group-name").focus().select();
}

	
function onClickEditGroupButton(uid){
	$("#window-groupEdit-name").val($("[group_uid='" + uid + "']>span").text());
	
	popupWindow($("#window-groupEdit"), function(result){
		if(result == "ok"){
			if($("#window-groupEdit-name").val() == ""){
				popupErrorWindow("<?=$lang['GROUP']['GROUP_NAME_NOT_EMPTY'];?>")
				return;
			}
			$("[group_uid='" + uid + "']>span").text($("#window-groupEdit-name").val());
			$("#window-groupEdit").hide();
			$.ajax({
				url: "io_group_ajax.php?act=editgroupname",
				type: "POST",
				data: {
					groupname: $("#window-groupEdit-name").val(),
					group_uid: uid
				},
				success: function(data, textStatus, jqXHR){
				},
				error: function(jqXHR, textStatus, errorThrown){
					if(jqXHR.status === 0){ return; }// include timeout

					alert(jqXHR.responseText);
				}
			});	
		}
		else if(result == "cancel"){
			$("#window-groupEdit").hide();
		}
	});

	$("#window-groupEdit-name").focus().select();
}

function onClickDelGroupButton(uid, name){
	$("#window-groupDel-name").text(name);
	
	popupConfirmWindow("<?=$lang['GROUP']['DELETE_GROUP_SURE'];?>",
		function(){$("[group_uid='" + uid + "']").remove();
				$("#window-groupDel").hide();
				module_Selected=[];
				if($("[name=grouplist]").length != 0)
					$($("[name=grouplist]")[$("[name=grouplist]").length-1]).trigger( "click" );
				else
				{	
					$("#group-no-group-exist").show();
					$("#group-add-button").hide();
					$("#group").hide();
				}
					
				$.ajax({
					url: "io_group_ajax.php?act=delgroup",
					type: "POST",
					data: {
						group_uid: uid
					},
					success: function(data, textStatus, jqXHR){
					},
					error: function(jqXHR, textStatus, errorThrown){
						if(jqXHR.status === 0){ return; }// include timeout

						alert(jqXHR.responseText);
					}
				});	
				if($(".group-nav-item").length == 0)
				{
					$("#group-no-group-exist").show();
					$("#group, #group-add-button").hide();		
				}
		},
		function(){$("#window-groupDel").hide();}
	);
}


/**********  Group List END **************/

/**********  Group's Loop ***************/
function load_group_data(group_uid){
	$("#wait-loader").show();
	$.ajax({
		url: "io_group_ajax.php?act=load_group_data",
		type: "POST",
		dataType: "xml",
		data: {
					group_uid: group_uid
				},
		success: function(data, textStatus, jqXHR){
			set_group_data_list(data);
			$("#wait-loader").hide();
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0){ return; }// include timeout

			alert(jqXHR.responseText);
		}
	});	
	
}

function set_group_data_list(data){
	var str_html = "";
	var $a_xmlElement = $(data).find("group_data_list > account");

	if($a_xmlElement.length > 0){
		$("#group-module-container").show();
		$("#group-no-module-exist").hide();
	}
	else{
		$("#group-no-module-exist").show();
		$("#group-module-container").hide();
	}
	
	for(var account_num = 0; account_num < $a_xmlElement.length; account_num++){
		var $c_xmlElement = $($a_xmlElement[account_num]).find('controller');
		var account_uid = $($a_xmlElement[account_num]).attr('account_uid');
		var account_username = $($a_xmlElement[account_num]).attr('username');
		var account_nickname = $($a_xmlElement[account_num]).attr('nickname');
		var account_info = account_nickname+"("+account_username+")";
		var account_type = $($a_xmlElement[account_num]).attr('account_type');
		
		//controller
		for(var controller_num = 0; controller_num < $c_xmlElement.length; controller_num ++){
			var controller_name = $($c_xmlElement[controller_num]).attr("controller_name");
			var controller_nickname = $($c_xmlElement[controller_num]).attr("controller_nickname");
			
			if(controller_name == "" && controller_nickname != "")
				controller_name = controller_nickname;
			else if(controller_name != "" && controller_nickname != "")
				controller_name = controller_name + "(" + controller_nickname + ")";

			str_html += "<div class = 'group-list-title'>"; + controller_name + "</div>";
			if(account_type=="share"){
				str_html += '<div style="position:absolute;" tip="' + '<?=$lang["GROUP"]["TIP"]["SHARE_BY_USER"];?>'.replace("%username%", account_info) + '"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="image/ics.svg#share"></use></svg></div>';
				str_html += '<span style="margin-left:22px;">' + controller_name + '</span>';
			}
			else{
				str_html += controller_name;
			}
			str_html += "</div>";
			str_html += "<div class = 'group-list-container'>";
			
			//interface
			var $i_xmlElement = $($c_xmlElement[controller_num]).find("controller > interface");
			for(var interface_num = 0; interface_num < $i_xmlElement.length; interface_num ++){
				if($($i_xmlElement[interface_num]).attr("interface_uid")=='IR' && $($i_xmlElement[interface_num]).find("module>channel").length == 0){
					continue;
				}
				var interface_name
				if($($i_xmlElement[interface_num]).attr("interface_uid")=="IR")
					interface_name = "<?=$lang['IO_GROUP']['OTHER'];?>";
				else if($($i_xmlElement[interface_num]).attr("interface_uid")=="XW" || $($i_xmlElement[interface_num]).attr("interface_uid")=="XV")
					interface_name = $($i_xmlElement[interface_num]).attr("interface_uid") + "-Board";
				else if($($i_xmlElement[interface_num]).attr("interface_uid")=="XU")
					interface_name = "<?=$lang['IO_GROUP']['BUILD_IN'];?>";
				else
					interface_name = $($i_xmlElement[interface_num]).attr("interface_uid");
				str_html += "<div class='group-list-interface'>" + interface_name + "</div>";
				str_html += "<div>";
				
				
				//module
				var $m_xmlElement = $($i_xmlElement[interface_num]).find("interface > module");
				for(var module_num = 0; module_num < $m_xmlElement.length; module_num ++){
					var module_name = $($m_xmlElement[module_num]).attr("model_name");
					var controller_uid = $($m_xmlElement[module_num]).attr("controller_uid");
					var module_uid = $($m_xmlElement[module_num]).attr("module_uid");
					var manufacturer = $($m_xmlElement[module_num]).attr("manufacturer");
					var module_nickname = $($m_xmlElement[module_num]).attr("nickname");

					if(module_name=="IR")
						module_name = "<?=$lang['IO_GROUP']['INTERNAL_REGISTER'];?>";
					else if(module_name == "" && module_nickname != "")
						module_name = module_nickname;
					else if(module_name != "" && module_nickname != "")
						module_name = module_name + "(" + module_nickname + ")";
					
					
					str_html += "<div class='group-list-wrapper'>";
					str_html += "<div class='group-list-name'>";
					str_html += "<div style=' padding: 3px 0;'>";
					
					str_html += "<input type='checkbox' id='select_ck_" + account_uid + "_" + controller_uid +"_"+ module_uid +"' controller_module='" + account_uid + "_"+ controller_uid +"_"+module_uid+"' model_type='module' onclick='onChangeCheckAllLoop(this);'>";
					if(manufacturer == "")
						str_html += "<label for='select_ck_" + account_uid + "_"+ controller_uid +"_"+ module_uid +"'>" + module_name +"</label>";
					else
						str_html += "<label for='select_ck_"+ account_uid + "_" + controller_uid +"_"+ module_uid +"'>" + manufacturer + " "+ module_name +"</label>";
					str_html += "</div>";
					str_html += "<div class='group-list-table' id='div_model_"+controller_uid+"_"+ module_uid +"'>";
					
					//channel
					var $l_xmlElement = $($m_xmlElement[module_num]).find("module > channel");
					var channel_row_num = 0;
					var input_channel_num = 0;
					var channel_type_tmp = "";
					
					for(var channel_num = 0; channel_num < $l_xmlElement.length; channel_num ++){
						var channel_type = $($l_xmlElement[channel_num]).attr("channel_type");
						var channel = $($l_xmlElement[channel_num]).attr("channel");
						var data_uid = $($l_xmlElement[channel_num]).attr("data_uid");
						var channel_nickname = $($l_xmlElement[channel_num]).attr("nickname");

						if(channel_row_num % 4 == 0){
							str_html += "<div class='group-list-row'>";
						}
						else if(channel_type_tmp != "" && channel_type_tmp != channel_type){
							str_html += "</div><div class='group-list-row'>";
							channel_row_num = 0;
						}
						channel_type_tmp = channel_type;
						str_html += "<div class='group-list-cell'>";
						str_html += "<input type='checkbox' id='select_ck_" + account_uid + "_"+data_uid+"' data_uid='"+data_uid+"' controller_module='" + account_uid + "_" +controller_uid+"_"+module_uid+"' model_type='channel' onclick='onChangeCheckLoop(this);'>";
						
						switch(channel_type){
							case "CI":channel_type="Discrete Input ";break;
							case "CO":channel_type="Coil Output ";break;
							case "RI":channel_type="Input Register ";break;
							case "RO":channel_type="Holding Register ";break;
						}
						
						if($($l_xmlElement[channel_num]).attr("module_uid")=="ir"){
							str_html += "<label for='select_ck_" + account_uid + "_"+data_uid+"' style='color: #707070;'>"+"<?=$lang['REALTIME_IO']['INTERNAL_REGISTER_WITH_NO'];?>".replace("%channel%", channel);
							if(channel_nickname != "")
								str_html += "(" + channel_nickname + ")" ;
							str_html += "</label>";
						}
						else{
							if(channel_type=="DIC"){
								var channel_name = "<?=$lang["IO_GROUP"]["CHANNEL_COUNTER_WITH_NO"];?>".replace("%channel_type%", "DI").replace("%channel%",channel);								
								str_html += "<label for='select_ck_" + account_uid + "_"+data_uid+"' style='color: #707070;'>"+channel_name;
								if(channel_nickname != "")
									str_html += "(" + channel_nickname + ")" ;
								str_html += "</label>";
							}
							else if(channel_type=="DOC"){
								var channel_name = "<?=$lang["IO_GROUP"]["CHANNEL_COUNTER_WITH_NO"];?>".replace("%channel_type%", "DO").replace("%channel%",channel);								
								str_html += "<label for='select_ck_" + account_uid + "_"+data_uid+"' style='color: #707070;'>"+channel_name;
								if(channel_nickname != "")
									str_html += "(" + channel_nickname + ")" ;
								str_html += "</label>";
							}
							else if(channel_type=="IR"){
								str_html += "<label for='select_ck_" + account_uid + "_"+data_uid+"' style='color: #707070;'><?=$lang["REALTIME_IO"]["INTERNAL_REGISTER"];?>"+channel;
								if(channel_nickname != "")
									str_html += "(" + channel_nickname + ")" ;
								str_html += "</label>";
							}
							else{
								str_html += "<label for='select_ck_" + account_uid + "_"+data_uid+"' style='color: #707070;'>"+channel_type+channel;
								if(channel_nickname != "")
									str_html += "(" + channel_nickname + ")" ;
								str_html += "</label>";
							}
						}
						str_html += "</div>";
						
						channel_row_num ++;
						input_channel_num ++;
						if((channel_row_num) % 4 == 0 || input_channel_num == $l_xmlElement.length){
							str_html += "</div>";
							channel_row_num = 0;
						}
						
					}
					str_html += "</div>";
					str_html += "</div>";
					str_html += "</div>";
				}
				str_html += "</div>";
			}
			str_html += "</div>";
		}
	}
	$("#group-module-container").html(str_html);
	
	//load_check_list
	module_Selected=[];
	temp_module_Selected=[];
	
	var $check_xmlElement = $(data).find("group_data_list > loop > check_uid");
	for(var i = 0; i < $check_xmlElement.length; i++)
	{
		module_Selected.push($($check_xmlElement[i]).attr("data_uid"));
		temp_module_Selected.push($($check_xmlElement[i]).attr("data_uid"));
	}
}
/**********  END Group's Loop ***************/


/********** Loop Setting option ***********/
function loadcontroller(){
	var modelname = "";
	var nickname = "";
	var controller_name = ""
	$.ajax({
		url: "io_group_ajax.php?act=loadcontroller",
		type: "POST",
		dataType: "xml",
		success: function(data, textStatus, jqXHR){
			$("#window-module-selector").html("");
			var $localuser_xmlElement = $(data).find("controller > local");
			if($localuser_xmlElement.length != 0)
				var user_idx = $($localuser_xmlElement[0]).attr("uid");
			var $xmlElement = $localuser_xmlElement.find('list');
			
			for(var localidx = 0; localidx < $xmlElement.length; localidx++)
			{
				modelname = $($xmlElement[localidx]).attr("model_name");
				nickname = $($xmlElement[localidx]).attr("nickname");
				controller_name = (nickname == "")?modelname:modelname + "(" + nickname + ")";
				$("#window-module-selector").append($("<option></option>").attr({"value": $($xmlElement[localidx]).attr("uid"),"user_idx":user_idx}).text(controller_name));
			}
			
			//share
			var $shareuser_xmlElement = $(data).find("controller > share");

			for(var shareuseridx = 0; shareuseridx < $shareuser_xmlElement.length; shareuseridx++)
			{
				var user_idx = $($shareuser_xmlElement[shareuseridx]).attr("uid");
				user_name = $($shareuser_xmlElement[shareuseridx]).attr("Username");
				user_nickname = $($shareuser_xmlElement[shareuseridx]).attr("Nickname");
				controller_name = user_nickname + "(" + user_name + ")";
				
				var $xmlElement = $($shareuser_xmlElement[shareuseridx]).find('list');
				if($xmlElement.length > 0)
					$("#window-module-selector").append($("<option disabled style='background: #e5e9eb; font-weight: bold; color: #707070;'></option>").text(controller_name));
				for(var shareidx = 0; shareidx < $xmlElement.length; shareidx++)
				{
					modelname = $($xmlElement[shareidx]).attr('model_name');
					nickname = $($xmlElement[shareidx]).attr('nickname');
					controller_name = (nickname == "")?modelname:modelname + "(" + nickname + ")";
					$("#window-module-selector").append($("<option></option>").attr({"value": $($xmlElement[shareidx]).attr('uid'),"user_idx":user_idx}).text(controller_name));
				}
			}
			if($("#window-module-selector option").size()>0)
			{
				$("#window-module-selector option").selectedIndex = 0;
				load_controller_data();
			}
			else{
				$("#window-module-selector").append(
					$("<option></option>").text("<?=$lang['GROUP']['NO_DEVICE'];?>")
				);

				SearchModuleName($("#window-module-filter-input").val());
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0){ return; }// include timeout

			alert(jqXHR.responseText);
		}
	});	
}

function load_controller_data(){
	//換頁時module_Selected 紀錄該頁設定
	for(var ck_idx = 0; ck_idx < $("[ui_type=channel]:checked").length; ck_idx++){
		module_Selected.push($($("[ui_type=channel]:checked")[ck_idx]).attr("id"));
	}
	var user_uid = $('option:selected', '#window-module-selector').attr('user_idx');
	var io_channel=["DI","DIC","DO","DOC","AI","AO","CO","CI","RI","RO","IR"];

	$("#window-module-loader").show();
	$.ajax({
		url: "io_group_ajax.php?act=load_controller_data",
		type: "POST",
		dataType: "xml",
		data: {
					user_uid: user_uid,
					controller_uid: $("#window-module-selector").val()
				},
		success: function(data, textStatus, jqXHR){
			
			var str_html="";

			//interface
			var $i_xmlElement = $(data).find("load_controller_data > interface");
			if($i_xmlElement.length == 0){
				$("#window-module-is-empty").show();
			}
			else
				$("#window-module-is-empty").hide();
			for(var interface_num = 0; interface_num < $i_xmlElement.length; interface_num ++){
				var interface_name = $($i_xmlElement[interface_num]).attr("interface_name");
				if(interface_name == "IR")
					interface_name = "<?=$lang['IO_GROUP']['OTHER'];?>";
				else if(interface_name == "XW" || interface_name == "XV")
					interface_name = interface_name + "-Board";
				else if(interface_name == "XU")
					interface_name = "<?=$lang['IO_GROUP']['BUILD_IN'];?>";
				
				str_html +="<div class='window-module-interface'>" + interface_name + "</div>";
				
				//module
				$m_xmlElement = $($i_xmlElement[interface_num]).find("interface > module");
				var module_uid = "";
				var interface = "";
				var controller_uid = "";
				var model_name = "";
				var module_nickname = "";
				var manufacturer = "";
				var name = "";
				
				for(var i = 0; i < $m_xmlElement.length; i++)
				{
					module_uid =  $($m_xmlElement[i]).attr("uid");
					interface = $($m_xmlElement[i]).attr("interface");		
					controller_uid = $("#window-module-selector").val();
					model_name = $($m_xmlElement[i]).attr("model_name");
					model_nickname = $($m_xmlElement[i]).attr("nickname");
					channel_str = $($m_xmlElement[i]).attr("channel");
					channel_list = $($m_xmlElement[i]).attr("channel").split(",");
					channel_nickname_list = $($m_xmlElement[i]).attr("channel_nickname").split(",");
					
					if(model_name=="IR")
						name = "<?=$lang["IO_GROUP"]["INTERNAL_REGISTER"];?>";
					else if(model_name=="" && model_nickname!="")
						name = model_nickname;
					else if(model_name!="" && model_nickname=="")
						name = model_name
					else
						name = model_name + "(" + model_nickname + ")";
						
					
					//產生IO列表
					//產生module checkbox...
					if(interface != "IR"){
						str_html += "<div class='window-module' div_name='divmodule' module_name='" + name + "'>";
						str_html += "<div class='window-module-name' id='div_module_"+module_uid+"'>";
						str_html += "<input type='checkbox' id='ck_module_"+module_uid+"' select_all_uid='"+controller_uid+"_"+module_uid+"' account_uid='"+user_uid+"' onclick='ck_module_all(this)'>";
						str_html += "<label class='ck_module' for='ck_module_"+module_uid+"'>"+name+"</label>";
						str_html += "</div>";
						str_html += "<div id='div_module_loop_table_" + module_uid + "' class='window-module-table'>";
					}
					else{
						str_html += "<div class='window-module' div_name='divmodule' module_name='" + name + "'>";
						str_html += "<div class='window-module-name' id='div_module_"+module_uid+"'>";
						str_html += "<input type='checkbox' id='ck_module_"+module_uid+"' select_all_uid='"+controller_uid+"_ir' account_uid='"+user_uid+"' onclick='ck_module_all(this)'>";
						str_html += "<label class='ck_module' for='ck_module_"+module_uid+"'>"+name+"</label>";
						str_html += "</div>";
						str_html += "<div id='div_module_loop_table_" + module_uid + "' class='window-module-table'>";
						//產生Channel idx checkbox...
						str_html += "<div class='window-module-table'>";
						var ck_row_end = true;
						for(var channel_idx in channel_list){
							var irchannel = channel_list[channel_idx].split('IR')[1];
							var ck_id = "channel_"+user_uid+"_"+controller_uid+"_ir_IR_"+irchannel;
							if(ck_row_end){
								str_html += '<div class="window-module-row">';
								ck_row_end = false;
							}
							str_html += "<div class='window-module-cell'>";
							str_html += "<input type='checkbox' id='"+ck_id+"' ui_type='channel' module_uid='"+controller_uid+"_ir' channel_type='"+io_channel[io_channel_idx]+"' onclick='ck_channel_array(this)'><label for='"+ck_id+"'>"+"<?=$lang['IO_GROUP']['INTERNAL_REGISTER_WITH_NO'];?>".replace("%channel%", irchannel);
							if(channel_nickname_list[channel_idx]!=""){
								str_html += "(" + channel_nickname_list[channel_idx] + ")";
							}
							str_html += "</label>";
							str_html += "</div>";
							if((channel_idx*1+1)%4==0){
								str_html += '</div>';
								ck_row_end = true;
							}
						}
						if(ck_row_end == false){
							str_html += '</div>';
							ck_row_end = true;
						}
						str_html += "</div>";
						continue;
					}
				
					/*------*/
					for(var io_channel_idx in io_channel){
						var ck_io_type = false;
						for(var channel_idx in channel_list){
							try{
								if(channel_list[channel_idx].match(/(\D+)(\d+)/)[1] == io_channel[io_channel_idx]){
									ck_io_type = true;
								}
							}
							catch(e){
							}
						}
						if(ck_io_type){
							//產生Channel Type checkbox...
							str_html += "<div class='window-module-name'>";
							str_html += "<input type='checkbox' id='"+controller_uid+"_"+module_uid+"_"+io_channel[io_channel_idx]+"'  module_uid='"+controller_uid+"_"+module_uid+"' select_all_channel_type='"+ io_channel[io_channel_idx] +"' onclick='ck_channeltype_all(this);'>";
							var channel_type = io_channel[io_channel_idx];
							switch(channel_type){
								case "CI":channel_type="Discrete Input ";break;
								case "CO":channel_type="Coil Output ";break;
								case "RI":channel_type="Input Register ";break;
								case "RO":channel_type="Holding Register ";break;
							}
							
							if(channel_type == "DIC")
								str_html += "<label for="+controller_uid+"_"+module_uid+"_"+io_channel[io_channel_idx]+">"+"<?=$lang["IO_GROUP"]["CHANNEL_COUNTER"];?>".replace("%channel_type%","DI")+"</label>";
							else if(channel_type == "DOC")
								str_html += "<label for="+controller_uid+"_"+module_uid+"_"+io_channel[io_channel_idx]+">"+"<?=$lang["IO_GROUP"]["CHANNEL_COUNTER"];?>".replace("%channel_type%","DO")+"</label>";
							else if(channel_type == "IR")
								str_html += "<label for="+controller_uid+"_"+module_uid+"_"+io_channel[io_channel_idx]+"><?=$lang["REALTIME_IO"]["INTERNAL_REGISTER"];?></label>";
							else
								str_html += "<label for="+controller_uid+"_"+module_uid+"_"+io_channel[io_channel_idx]+">"+channel_type+"</label>";
							str_html += "</div>";
							
							//產生Channel idx checkbox...
							var channel_num = 0;

							var ck_row_end = true;
							str_html += "<div class='window-module-table'>";
							for(var channel_idx in channel_list){
								
								var channel_idx_type = channel_list[channel_idx].match(/(\D+)(\d+)/)[1];
								var channel_idx_num = channel_list[channel_idx].match(/(\D+)(\d+)/)[2];
								
								if(channel_list[channel_idx].indexOf(io_channel[io_channel_idx])>-1 && (channel_idx_type!='DIC' && io_channel[io_channel_idx]!='DIC' && channel_idx_type != 'DOC'&& io_channel[io_channel_idx]!='DIC')){
									if(ck_row_end){
										str_html += '<div class="window-module-row">';
										ck_row_end = false;
									}
									var ck_id = "channel_"+user_uid+"_"+controller_uid+"_"+module_uid+"_"+io_channel[io_channel_idx]+"_"+channel_idx_num;
									str_html += "<div class='window-module-cell'>";
									str_html += "<input type='checkbox' id='"+ck_id+"' ui_type='channel' module_uid='"+controller_uid+"_"+module_uid+"' channel_type='"+io_channel[io_channel_idx]+"' onclick='ck_channel_array(this)'>";
									if(channel_idx_type == 'IR')
										str_html += "<label for='"+ck_id+"'><?=$lang["REALTIME_IO"]["INTERNAL_REGISTER"];?>" + channel_idx_num+"</label>";
									else{
										str_html += "<label for='"+ck_id+"'>"+channel_type+channel_idx_num;
										if(channel_nickname_list[channel_idx] != "")
											str_html += "("+channel_nickname_list[channel_idx]+")";
										str_html += "</label>";
									}
									str_html += "</div>";
									
									if((channel_num*1+1)%4==0){
										str_html += '</div>';
										ck_row_end = true;
									}

									channel_num++;
									
								}
								else if(channel_list[channel_idx].indexOf(io_channel[io_channel_idx])>-1 && ((channel_idx_type=='DIC' && io_channel[io_channel_idx]=='DIC') || (channel_idx_type=='DOC' && io_channel[io_channel_idx]=='DOC'))){//判斷是否為DIC
									if(ck_row_end){
										str_html += '<div class="window-module-row">';
										ck_row_end = false;
									}
									var ck_id = "channel_"+user_uid+"_"+controller_uid+"_"+module_uid+"_"+channel_idx_type+"_"+channel_idx_num;
									if(channel_idx_type=='DIC' && io_channel[io_channel_idx]=='DIC')
										var channel_name = "<?=$lang["IO_GROUP"]["CHANNEL_COUNTER_WITH_NO"];?>".replace("%channel_type%", "DI").replace("%channel%", channel_idx_num);
									else if(channel_idx_type=='DOC' && io_channel[io_channel_idx]=='DOC')
										var channel_name = "<?=$lang["IO_GROUP"]["CHANNEL_COUNTER_WITH_NO"];?>".replace("%channel_type%", "DO").replace("%channel%", channel_idx_num);
									str_html += "<div class='window-module-cell'>";
									str_html += "<input type='checkbox' id='"+ck_id+"' ui_type='channel' module_uid='"+controller_uid+"_"+module_uid+"' channel_type='"+io_channel[io_channel_idx]+"' onclick='ck_channel_array(this)'><label for='"+ck_id+"'>"+ channel_name +"</label>";
									str_html += "</div>";
									
									
									if((channel_num*1+1)%4==0){
										str_html += '</div>';
										ck_row_end = true;
									}

									channel_num++;
									
								}
								if((channel_idx*1+1) == channel_list.length && ck_row_end == false){
									str_html += '</div>';
									ck_row_end = true;
								}
							}
							str_html += "</div>";
							continue;
						}
					}
					str_html += "</div>"
					str_html += "</div>";
				}
			}
			$(".window-module-container").html(str_html);
			
			for(var i=0 ;i < module_Selected.length;i++){
				//勾選 channel
				$("#"+module_Selected[i]).attr('checked',true);

				var control_module = $("#"+module_Selected[i]).attr("module_uid");
				var channel_type = $("#"+module_Selected[i]).attr("channel_type");
				
				//勾選 channel type
				var channel_total = $("[module_uid="+control_module+"][channel_type="+ channel_type +"]").length;
				var ck_channel_total = $("[module_uid="+control_module+"][channel_type="+ channel_type +"]:checked").length;
				
				if( channel_total == ck_channel_total ){
					$("[module_uid="+control_module+"][select_all_channel_type="+channel_type+"]").attr("checked",true);
				}
				else{
					$("[module_uid="+control_module+"][select_all_channel_type="+channel_type+"]").attr("checked",false);
				}
				
				//勾選 module
				var module_channel_total = $("[module_uid="+control_module+"][ui_type=channel]").length;
				var ck_module_channel_total = $("[module_uid="+control_module+"][ui_type=channel]:checked").length;
				
				
				if( module_channel_total == ck_module_channel_total ){
					$("[select_all_uid="+control_module+"]").attr("checked",true);
				}
				else{
					$("[select_all_uid="+control_module+"]").attr("checked",false);
				}
				
				
			}

			SearchModuleName($("#window-module-filter-input").val());
			//換頁後module_Selected 清除換頁設定
			for(var ck_idx = 0; ck_idx < $("[ui_type=channel]:checked").length; ck_idx++){
				//removeItem = $($("[ui_type=channel]:checked")[ck_idx]).attr("id");
				//module_Selected= jQuery.grep(module_Selected, function(value) {
				//  return value != removeItem;
				//});
				module_Selected.splice(module_Selected.indexOf($($("[ui_type=channel]:checked")[ck_idx]).attr("id")),1);
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0){ return; }// include timeout

			alert(jqXHR.responseText);
		},
		complete: function(){
			$("#window-module-loader").hide();
		}
	});	 
	
}

//Delete 該group的group data資料，Inesert新資料
function editgroupmodule(group_uid,module_Selected){
	$.ajax({
		url: "io_group_ajax.php?act=editgroupmodule",
		type: "POST",
		async: false,
		dataType: "xml",
		data: {
					group_info_uid: group_uid,
					ck_channel:module_Selected
				},
		success: function(data, textStatus, jqXHR){
			
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0){ return; }// include timeout

			alert(jqXHR.responseText);
		}
	});	 
}


//勾選Add or cancel module事件
function ck_module_all(cb){
	var select_all_uid = $(cb).attr('select_all_uid');
	var account_uid = $(cb).attr('account_uid');
	var ck_ui = $('[module_uid='+select_all_uid + ']' );
	var channel_ui= $('[module_uid='+select_all_uid + '][ui_type="channel"]' );
	
	ck_all(cb.checked,channel_ui,ck_ui);
}

function ck_channeltype_all(cb){
	
	var module_uid = $(cb).attr('module_uid');
	var channel_type = $(cb).attr('select_all_channel_type');
	var ck_ui = $('[module_uid='+module_uid + '][channel_type='+channel_type+']' );
	var channel_ui= $('[module_uid='+module_uid + '][channel_type='+channel_type+'][ui_type="channel"]' );
	
	ck_all(cb.checked,channel_ui,ck_ui);
	
	var control_module = $(cb).attr("module_uid");
	var channel_type = $(cb).attr("channel_type");
	
	//確認是否勾選module
	if(cb.checked){
		//勾選 module
		var module_channel_total = $("[module_uid="+control_module+"][ui_type=channel]").length;
		var ck_module_channel_total = $("[module_uid="+control_module+"][ui_type=channel]:checked").length;
		
		
		if( module_channel_total == ck_module_channel_total ){
			$("[select_all_uid="+control_module+"]").attr("checked",true);
		}
		else{
			$("[select_all_uid="+control_module+"]").attr("checked",false);
		}
		
		//勾選 module
		var module_channel_total = $("[module_uid="+control_module+"][ui_type=channel]").length;
		var ck_module_channel_total = $("[module_uid="+control_module+"][ui_type=channel]:checked").length;
		
		
		if( module_channel_total == ck_module_channel_total ){
			$("[select_all_uid="+control_module+"]").attr("checked",true);
		}
		else{
			$("[select_all_uid="+control_module+"]").attr("checked",false);
		}						
	}
	else
	{
		//勾選 channel type
		var channel_total = $("[module_uid="+control_module+"][channel_type="+ channel_type +"]").length;
		var ck_channel_total = $("[module_uid="+control_module+"][channel_type="+ channel_type +"]:checked").length;
		
		if( channel_total == ck_channel_total ){
			$("[module_uid="+control_module+"][select_all_channel_type="+channel_type+"]").attr("checked",true);
		}
		else{
			$("[module_uid="+control_module+"][select_all_channel_type="+channel_type+"]").attr("checked",false);
		}
		
		//勾選 module
		var module_channel_total = $("[module_uid="+control_module+"][ui_type=channel]").length;
		var ck_module_channel_total = $("[module_uid="+control_module+"][ui_type=channel]:checked").length;
		
		
		if( module_channel_total == ck_module_channel_total ){
			$("[select_all_uid="+control_module+"]").attr("checked",true);
		}
		else{
			$("[select_all_uid="+control_module+"]").attr("checked",false);
		}						
		
	}
	/*****************************/
	
}

function ck_all(this_check,channel_ui,ck_ui){
	if(this_check)
	{
		ck_ui.prop("checked", true);
		
		//for(var i = 0;i < channel_ui.size(); i++)
		//{
		//	var ck_channel = $(channel_ui[i]).attr('id');
		//	if($.inArray(ck_channel, module_Selected)!=-1)
		//		continue;
		//	module_Selected.push(ck_channel); 
		//}
		
	}
	else
	{
		ck_ui.prop("checked", false);
		
		//for(var i = 0;i < channel_ui.size(); i++)
		//{
		//	var ck_channel = $(channel_ui[i]).attr('id');
		//	module_Selected.splice(module_Selected.indexOf(ck_channel), 1);
		//}
		
	}
}

/********** Loop Setting option END ***********/


//勾選迴路事件
function ck_channel_array(cb){
	var uid = $(cb).attr("id");
	var control_module = $(cb).attr("module_uid");
	var channel_type = $(cb).attr("channel_type");
	if(cb.checked){
		//module_Selected.push(uid);
		//勾選 channel type
		var channel_total = $("[module_uid="+control_module+"][channel_type="+ channel_type +"]").length;
		var ck_channel_total = $("[module_uid="+control_module+"][channel_type="+ channel_type +"]:checked").length;
		
		if( channel_total == ck_channel_total ){
			$("[module_uid="+control_module+"][select_all_channel_type="+channel_type+"]").attr("checked",true);
		}
		else{
			$("[module_uid="+control_module+"][select_all_channel_type="+channel_type+"]").attr("checked",false);
		}
		
		//勾選 module
		var module_channel_total = $("[module_uid="+control_module+"][ui_type=channel]").length;
		var ck_module_channel_total = $("[module_uid="+control_module+"][ui_type=channel]:checked").length;
		
		
		if( module_channel_total == ck_module_channel_total ){
			$("[select_all_uid="+control_module+"]").attr("checked",true);
		}
		else{
			$("[select_all_uid="+control_module+"]").attr("checked",false);
		}
		//勾選 channel type
		var channel_total = $("[module_uid="+control_module+"][channel_type="+ channel_type +"]").length;
		var ck_channel_total = $("[module_uid="+control_module+"][channel_type="+ channel_type +"]:checked").length;
		
		if( channel_total == ck_channel_total ){
			$("[module_uid="+control_module+"][select_all_channel_type="+channel_type+"]").attr("checked",true);
		}
		else{
			$("[module_uid="+control_module+"][select_all_channel_type="+channel_type+"]").attr("checked",false);
		}
		
		//勾選 module
		var module_channel_total = $("[module_uid="+control_module+"][ui_type=channel]").length;
		var ck_module_channel_total = $("[module_uid="+control_module+"][ui_type=channel]:checked").length;
		
		
		if( module_channel_total == ck_module_channel_total ){
			$("[select_all_uid="+control_module+"]").attr("checked",true);
		}
		else{
			$("[select_all_uid="+control_module+"]").attr("checked",false);
		}						
	}
	else
	{
		//module_Selected.splice(module_Selected.indexOf(uid),1);
		//勾選 channel type
		var channel_total = $("[module_uid="+control_module+"][channel_type="+ channel_type +"]").length;
		var ck_channel_total = $("[module_uid="+control_module+"][channel_type="+ channel_type +"]:checked").length;
		
		if( channel_total == ck_channel_total ){
			$("[module_uid="+control_module+"][select_all_channel_type="+channel_type+"]").attr("checked",true);
		}
		else{
			$("[module_uid="+control_module+"][select_all_channel_type="+channel_type+"]").attr("checked",false);
		}
		
		//勾選 module
		var module_channel_total = $("[module_uid="+control_module+"][ui_type=channel]").length;
		var ck_module_channel_total = $("[module_uid="+control_module+"][ui_type=channel]:checked").length;
		
		
		if( module_channel_total == ck_module_channel_total ){
			$("[select_all_uid="+control_module+"]").attr("checked",true);
		}
		else{
			$("[select_all_uid="+control_module+"]").attr("checked",false);
		}						
	}
}

function onClickAddModuleButton(){
	
	$(".window-module-container").empty();

	loadcontroller();
	var group_uid = $("#group_menu > .active").attr("group_uid"); //選取的group_uid
	
	popupWindow($("#window-module"), function(result){
		if(result == "ok"){
			//module_Selected push該頁設定
			for(var ck_idx = 0; ck_idx < $("[ui_type=channel]:checked").length; ck_idx++){
				module_Selected.push($($("[ui_type=channel]:checked")[ck_idx]).attr("id"));
			}
			editgroupmodule(group_uid,module_Selected);
			$("#window-module").hide();
			temp_module_Selected = [];
			for(var i = 0;i <module_Selected.length;i++)
			{
				temp_module_Selected.push(module_Selected[i]);
			}
			load_group_data(group_uid);
			onClickCancelSearch();
		}
		else if(result == "cancel"){
			$("#window-module").hide();
			onClickCancelSearch();
			
			module_Selected = [];
			for(var i = 0;i <temp_module_Selected.length;i++)
			{
				module_Selected.push(temp_module_Selected[i]);
			}
		}
	}); 
}


function onChangeCheckAllLoop(cb){
	var controller_module = $(cb).attr("controller_module");
	var bool_ck = $(cb).attr('checked')=="checked";
	if(bool_ck)
		$("[controller_module="+controller_module+"]").attr('checked',true);
	else
		$("[controller_module="+controller_module+"]").attr('checked',false);
	onChangeCheckLoop();
}

var groupbarcontainer_hide = true;

function onChangeCheckLoop(cb){
	
	if(typeof(cb)!="undefined"){
		var controller_module = $(cb).attr("controller_module")
		if($(cb).attr('checked')=="checked"){
			if($("[controller_module="+controller_module+"][model_type=channel]:checked").length==$("[controller_module="+controller_module+"][model_type=channel]").length){
				$("[controller_module="+controller_module+"][model_type=module]").attr("checked",true);
			}
		}
		else{
			$("[controller_module="+controller_module+"][model_type=module]").attr("checked",false);
		}
	}
	
	var ck_num = $("input[model_type=channel]:checked").length;
	if(ck_num == 0)
		onClickBarBackButton();
	
	$("#group-bar-center").html("<?=$lang['IO_GROUP']['SELECTED_CHANNEL'];?>");
	$("#ck_num").text(ck_num);
	if(groupbarcontainer_hide && ck_num !=0){
		groupbarcontainer_hide=false;
		$("#group-add-button").fadeOut("fast");
		
		 var height = $("#group-bar-container").show().outerHeight();
		$("#group-bar-container").css("marginBottom", "-" + height + "px").animate({
			"marginBottom": 0
		}, "fast"); 
	}
}

function onClickBarBackButton(){
	if(!groupbarcontainer_hide)
	{
		groupbarcontainer_hide=true;
		$("#group-add-button").fadeIn("fast");

		var height = $("#group-bar-container").outerHeight();
		$("#group-bar-container").animate({
			"marginBottom": "-" + height + "px"
		}, "fast");
	}
	$("input[model_type=module]:checked").attr("checked",false);
	$("input[model_type=channel]:checked").attr("checked",false);
}

function onClickWindowButton(button){
	if(typeof(windowCallback) == "function"){
		windowCallback(button);
	}
}

function CreatGroupOption(GroupName,GroupUid){
		var $item = createGroupItem(GroupName,GroupUid);
		$("#group-nav-add").before($item);
		$("#group-no-group-exist").hide();
		$("#group, #group-add-button").show();
		$("#window-group").hide();
}

function onRemoveLoopButton(){
	var GroupDataArray=[];
	for(var i=0;i<$("input[model_type=channel]:checked").length;i++)
		GroupDataArray.push($($("input[model_type=channel]:checked")[i]).attr("data_uid"));
	
	popupConfirmWindow("<?=$lang['IO_GROUP']['REMOVE_CHANNEL_SURE'];?>",function(){
		$.ajax({
				url: "io_group_ajax.php?act=del_group_data",
				type: "POST",
				data: {
						group_data_uid_array:GroupDataArray
						},
				success: function(data, textStatus, jqXHR){
					load_group_data(group_uid);
				},
				error: function(jqXHR, textStatus, errorThrown){
					if(jqXHR.status === 0){ return; }// include timeout

					alert(jqXHR.responseText);
				}
			});	
			if($(".group-nav-control").length == 0)
			{
				$("#group-no-group-exist").show();
				$("#group, #group-add-button").hide();		
			}
			onClickBarBackButton();
			$("#window-loopDel").hide();
	},
	function(){
		$("#window-loopDel").hide();
	});
}

function SearchModuleName(keyword){
	var module_name = "";
	if(keyword == "")
		$("[div_name=divmodule]").show();
	else{
		for(var i = 0;i<$("[div_name=divmodule]").length;i++)
		{
			module_name = $($("[div_name=divmodule]")[i]).attr("module_name");
			if(module_name.indexOf(keyword) == -1)
				$($("[div_name=divmodule]")[i]).hide();
			else
				$($("[div_name=divmodule]")[i]).show();
		}
	}

	var isMatch = false;
	$(".window-module-interface").each(function(){
  		if($(this).nextUntil(".window-module-interface", ".window-module:visible").length <= 0){
			$(this).hide();
		}
		else{
			$(this).show();
			isMatch = true;
		}
	});

	$("#window-module-no-match").hide();
	

	if(isMatch == false){
		if(keyword != ""){
			$("#window-module-no-match").show();
			$("#window-module-is-empty").hide();
		}
	}
}

function onClickCancelSearch(){
	$("#window-module-filter-input").val("");
	SearchModuleName("");
}



</script>
<?php
}
?>

<?php
function customized_body(){
	global $lang;
?>

<div style="padding:20px 20px 60px 20px;">
	<div id="group-no-group-exist" style="display:none;">
		<div class="group-no-exist-title"><?=$lang['GROUP']['NO_GROUP_SETTING'];?></div>
		<div class="group-no-exist-content"><?=$lang['GROUP']['ADD_GROUP_CLICK'];?></div>
	</div>

	<div id="group" style="display:none;">
		<div class="group-nav" id="group_menu">
			<div class="group-nav-title"><?=$lang['GROUP']['GROUP'];?></div>

			<div id="group-nav-add" class="group-nav-item" onClick="onClickNewGroupButton();">
				<svg><use xlink:href="image/ics.svg#add_circle"></use></svg><span><?=$lang['GROUP']['NEW'];?></span>
			</div>
		</div>
		<div class="group-list" >
			<div id="group-no-module-exist" style="display:none;">
				<div class="group-no-exist-title"><?=$lang['IO_GROUP']['NO_CHANNEL'];?></div>
				<div class="group-no-exist-content"><?=$lang['IO_GROUP']['ADD_CHANNEL_CLICK'];?></div>
			</div>

			<div id="group-module-container" style="display:none;">
				
			</div>
		</div>
		<div style="clear:both;"></div>
	</div>
</div>

<div id="group-add-button" style="display:none;" onClick="onClickAddModuleButton()"><svg><use xlink:href="image/ics.svg#add"></use></svg></div>

<div id="group-bar-container" style="display:none;">
	<div class="group-bar">
		<div id="group-bar-left">
			<input type="button" class="control-button" value="<?=$lang['GROUP']['BACK'];?>" onClick="onClickBarBackButton()">
		</div>
		<div id="group-bar-center" style="text-align: center;"></div>
		<div class="group-bar-right">
			<input type="button" class="control-button red" onClick="onRemoveLoopButton()" value="<?=$lang['IO_GROUP']['REMOVE_CHANNEL'];?>">
		</div>
	</div>
</div>

<div class="popup-background" id="window-group">
	<div class="popup-wrapper" style="width:350px;">
		<div class="popup-container">
			<div class="popup-title"><?=$lang['GROUP']['ADD_GROUP'];?></div>
			<div class="popup-content window-group-container">
				<?=$lang['GROUP']['GROUP_NAME'];?>&nbsp;&nbsp;&nbsp;<input type="text" id="window-group-name">
			</div>
			<div class="popup-footer">
				<input type="button" value="<?=$lang['OK'];?>" onClick="onClickWindowButton('ok');">&nbsp;&nbsp;<input type="button" class="gray" value="<?=$lang['CANCEL'];?>" onClick="onClickWindowButton('cancel');"></span>
			</div>
		</div>
	</div>
</div>

<div class="popup-background" id="window-groupEdit">
	<div class="popup-wrapper" style="width:350px;">
		<div class="popup-container">
			<div class="popup-title"><?=$lang['GROUP']['EDIT_GROUP'];?></div>
			<div class="popup-content window-group-container">
				<?=$lang['GROUP']['GROUP_NAME'];?>&nbsp;&nbsp;&nbsp;<input type="text" id="window-groupEdit-name">
			</div>
			<div class="popup-footer">
				<input type="button" value="<?=$lang['OK'];?>" onClick="onClickWindowButton('ok');">&nbsp;&nbsp;<input type="button" class="gray" value="<?=$lang['CANCEL'];?>" onClick="onClickWindowButton('cancel');"></span>
			</div>
		</div>
	</div>
</div>

<div class="popup-background" id="window-module">
	<div class="popup-wrapper" style="width:700px;">
		<div class="popup-container">
			<div class="popup-title"><?=$lang['IO_GROUP']['ADD_CHANNEL'];?></div>
			<div class="popup-content">
				<div class="window-module-search">
					<table cellSpacing="0" cellPadding="0" border="0" width="100%">
						<tr>
							<td>
								<select id="window-module-selector" onchange="load_controller_data()" style="min-width: 215px;max-width: 255px;">
								</select>
							</td>
							<td align="right">
								<div class="window-module-filter">
									<div class="window-module-filter-icon"><svg><use xlink:href="image/ics.svg#search"></use></svg></div>
									<input type="text" id="window-module-filter-input" placeholder="<?=$lang['GROUP']['SEARCH'];?>" onkeyup="SearchModuleName($(this).val())">
									<div id="window-module-filter-clear" onclick="onClickCancelSearch()"><svg><use xlink:href="image/ics.svg#clear"></use></svg></div>
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div class="window-module-wrapper">
					<div id="window-module-loader"><img src="./image/loader.gif"></div>
					<div id="window-module-no-match"><?=$lang['GROUP']['NO_MATCH_MODULE'];?></div>
					<div id="window-module-is-empty"><?=$lang['GROUP']['NO_MODULE'];?></div>
					<div class="window-module-container"></div>
				</div>
			</div>
			<div class="popup-footer">
				<input type="button" value="<?=$lang['OK'];?>" onClick="onClickWindowButton('ok');">&nbsp;&nbsp;<input type="button" class="gray" value="<?=$lang['CANCEL'];?>" onClick="onClickWindowButton('cancel');"></span>
			</div>
		</div>
	</div>
</div>
<?php
}
?>