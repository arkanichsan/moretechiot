<?php
function customized_header(){
	global $lang;
?>
<link rel="stylesheet" type="text/css" href="./css/list.css">
<link rel="stylesheet" type="text/css" href="./css/checkbox.css">
<style type="text/css">
.list-table-cell{
	padding:10px;
}

.list-table-header-group .list-table-cell{
	padding-bottom:5px;
}

.list-table-row-group .list-table-row .list-table-cell{
	background-color:#F5F5F5 !important;
}

.list-table-row-group .list-table-row.warning .list-table-cell{
	background-color:#fff9d6 !important;
}

.list-table-row-group .list-table-row.exception .list-table-cell{
	background-color:#ffe9e9 !important;
}

.list-table-row-group .list-table-row.new .list-table-cell{
	font-weight: bold;
}

#paging > *{
	float: left;
}

#paging input{
    padding-right: 10px;
    padding-left: 10px;
	margin-right:5px;
}

#paging button{
	position: relative;
    padding-right: 13px;
    padding-left: 13px;
	margin-right:5px;
}

#paging button svg{
	position:absolute;
	top:50%;
	left:50%;
	margin-top:-9px;
	margin-left:-9px;
}

.dot{
	margin:7px 5px 0 0;
}

.module-text{
	padding:40px 0;
	text-align:center;
	color:rgb(192, 192, 195);
	font-size:160%;
	font-weight:bold;
	display:block;
}

.variable{
	color:#035002;
}
</style>
<script language="JavaScript">

</script>
<?php
}
?>

<?php
function customized_body(){
	global $lang, $language;
?>
<div style="padding:20px;">
	<div class="title" style="position:relative;"><?=$lang['NOTIFICATION']['EVENT_LIST'];?></div>
	<div id="no-notification-exist" style="text-align: center; color: #707070; padding: 100px 0; display: none;">
		<div class="group-no-exist-title"><?=$lang['NOTIFICATION']['NO_EVENT_NOTIFICATION'];?></div>
	</div>
	<div class="module-text" id="module-text-online"><?=$lang['NOTIFICATION']['LOADING'];?></div>
	<div id="notification_list" class="list-table" style="display: none;">
		<div class="list-table-header-group">
			<div class="list-table-row">
				<div class="list-table-cell" style="width:1%;"><?=$lang['NOTIFICATION']['TIME'];?></div>
				<div class="list-table-cell"><?=$lang['NOTIFICATION']['EVENT'];?></div>
			</div>
		</div>

		<div class="list-table-row-group" id="list">
		
		</div>

		<div class="list-table-footer-group">
			<div class="list-table-row">
				<div class="list-table-cell"></div>
				<div class="list-table-cell"></div>
			</div>
		</div>
	</div>

	<div id = "pading_export_div" style="margin-top:-10px;" style="display: none;">
	
		<div id="paging" style="float:left; display:none;">
		
		</div>
		<div style="float:right;">
			<input type="button" value="<?=$lang['NOTIFICATION']['READ'];?>" id="read" style="display: none;">
			<input type="button" value="<?=$lang['NOTIFICATION']['EXPORT'];?>" id="export" style="display: none;">
			<input type="button" value="<?=$lang['NOTIFICATION']['CLEAR'];?>" class="red" id="clear" style="display: none;">
		</div>
		<div style="clear:both;"></div>
	</div>
</div>

<script language="JavaScript">

var page_count = "";
var PageOption_count = 20;

$(document).ready(function(){
	$("#wait-loader").show();
	init();
	$("#module-text-online").hide();
	$("#wait-loader").hide();
});

function init(){
	notification_page_count();
	if(page_count == 0){
		$("#no-notification-exist").show();
		$("#notification_list").hide();
		$("#pading_export_div").hide();
		$("#export, #clear, #read").hide();
	}
	else{
		CreatPaging(1);
		$("[value=1]").trigger( "click" );
		$("#no-notification-exist").hide();
		
	}
}

$("#export").bind("click",function(){export_notification()});
$("#clear").bind("click",function(){clear_notification()});
$("#read").bind("click",function(){read_notification()});

//計算幾筆紀錄
function notification_page_count(){
	$.ajax({
		url: "db_notification_ajax.php?act=notification_page_count",
		type: "POST",
		data: {
			page_option_count:PageOption_count
		},
		async: false,
		success: function(data, textStatus, jqXHR){
			var $xmlElement = $(data).find("notification > list");
			page_count = parseInt($($xmlElement[0]).attr("page_count"));
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0){ return; }// include timeout
			alert(jqXHR.responseText);
		}
	});
	
}

function CreatPaging(selected_page){
	$("#paging").empty();
	if(selected_page != 1)
		$("#paging").append('<button class="gray" id="lastpage">&nbsp;<svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="image/ics.svg#left"></use></svg></button>');
	if( page_count <= 5 ){
		for(var page = 1; page <= page_count; page++){
			$("#paging").append('<input type="button" name="page" value="'+page+'" class="gray" onclick="changepage(this)">');
		}
	}
	else if(page_count > 5){
		if(selected_page <= 3 && parseInt(selected_page) + 2 < page_count){
			for(var page = 1; page <= 5; page++){
				$("#paging").append('<input type="button" name="page" value="'+page+'" class="gray" onclick="changepage(this)">');
			}
			$("#paging").append('<div class="dot"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="image/ics.svg#more"></use></svg></div>');
			$("#paging").append('<input type="button" name="page" value="'+page_count+'" class="gray" onclick="changepage(this)">');
		}
		else if(selected_page <= 3 && parseInt(selected_page) + 2 >= page_count){
			for(var page = 1; page <= page_count; page++){
				$("#paging").append('<input type="button" name="page" value="'+page+'" class="gray" onclick="changepage(this)">');
			}
		}
		else if(selected_page > 3 && parseInt(selected_page) + 2 < page_count){
			$("#paging").append('<input type="button" name="page" class="gray" value="1" onclick="changepage(this)">');
			$("#paging").append('<div class="dot"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="image/ics.svg#more"></use></svg></div>');
			for(var page = parseInt(selected_page)-2; page <= parseInt(selected_page)+2; page++){
				$("#paging").append('<input type="button" name="page" value="'+page+'" class="gray" onclick="changepage(this)">');
			}
			$("#paging").append('<div class="dot"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="image/ics.svg#more"></use></svg></div>');
			$("#paging").append('<input type="button" name="page" value="'+page_count+'" class="gray" onclick="changepage(this)">');
		}
		else if(selected_page > 3 && parseInt(selected_page) + 2 >= page_count){
			$("#paging").append('<input type="button" name="page" class="gray" value="1" onclick="changepage(this)">');
			$("#paging").append('<div class="dot"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="image/ics.svg#more"></use></svg></div>');
			
			for(var page = parseInt(page_count)-4; page <= page_count; page++){
				$("#paging").append('<input type="button" name="page" value="'+page+'" class="gray" onclick="changepage(this)">');
			}
		}
	}
	if(selected_page != page_count)
		$("#paging").append('<button class="gray" id="nextpage">&nbsp;<svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="image/ics.svg#right"></use></svg></button>');
	
	$("#nextpage").bind("click",function(){
		var nextpage = parseInt($("[name=page][select=true]").val())+1;
		$("[value="+nextpage+"]").trigger( "click" );
	});
	$("#lastpage").bind("click",function(){
		var nextpage = parseInt($("[name=page][select=true]").val())-1;
		$("[value="+nextpage+"]").trigger( "click" );
	});
}

function CreatNotificationList(){
	$("#wait-loader").show();
	var selected_page = $("[name=page][select=true]").val();
	var StartOption = selected_page * PageOption_count - PageOption_count + 1;
	var EndOption = selected_page * PageOption_count;
	$.ajax({
		url: "db_notification_ajax.php?act=select_notification_list",
		type: "POST",
		data: {
			start_option:StartOption,
			end_option:EndOption
		},
		success: function(data, textStatus, jqXHR){
			$("#list").empty();
			var $xmlElement = $(data).find("notification > list");
			for(var i = 0; i < $xmlElement.length; i++){
				var error_msg = $($xmlElement[i]).attr("Error_Message");
				var event_type = "";
				switch ($($xmlElement[i]).attr("Event_Code").substr(0,1))
				{
					case "3":
						event_type = "warning";
						break;
					case "5":
						event_type = "exception";
						break;
					default:
						event_type = "";
				}
				if($($xmlElement[i]).attr("Readed") == "0"){
					$("#list").append(
						$('<div class="list-table-row new '+event_type+'"></div>')
							.append('<div class="list-table-cell" style="white-space:nowrap;">' + utcToLocalTime($($xmlElement[i]).attr("DateTime")) +'</div>')
							.append('<div class="list-table-cell">' + error_msg + '</div>')
					);
				}
				else{
					$("#list").append(
						$('<div class="list-table-row '+event_type+'"></div>')
							.append('<div class="list-table-cell" style="white-space:nowrap;">' + utcToLocalTime($($xmlElement[i]).attr("DateTime")) +'</div>')
							.append('<div class="list-table-cell">' + error_msg + '</div>')
					);
				}
			}
			
			$("#pading_export_div").show();
			$("#export, #clear, #read").show();
			$("#wait-loader").hide();
			$("#notification_list").show();
			$("#paging").show();
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0){ return; }// include timeout

			alert(jqXHR.responseText);
		},
		complete: function(){}
	});
	notificatin_unread();
}

function changepage(page_bt){
	CreatPaging($(page_bt).val());
	$("[value="+$(page_bt).val()+"]").attr("class","");
	$("[value="+$(page_bt).val()+"]").attr("select","true");
	$("[value="+$(page_bt).val()+"]").removeAttr("onclick");
	CreatNotificationList();
	$('html, body').scrollTop(0);
}

function export_notification(){
	$("#wait-loader").show();
	var date = new Date();
	location.href="db_notification_export.php?utcdatetime="+date.getTimezoneOffset();
	$("#wait-loader").hide();
	
}

function clear_notification(){
	var $message = $("<table></table>").append(
		$("<tr></tr>").append(
			$("<td></td>").attr("colSpan", "2").text("<?=$lang['NOTIFICATION']['WHICH_EVENT_TYPE_YOU_WANT_TO_CLEAR']?>")
		)
	).append(
		$("<tr></tr>").append(
			$("<td></td>").css("height", "3px").attr("colSpan", "2")
		)
	).append(
		$("<tr></tr>").append(
			$("<td></td>").attr("width", "1%").append(
				$("<div></div>").attr("class", "checkbox").append(
					$("<input type='checkbox'/>").attr({
						"id": "system-event",
						"name": "checkbox"
					})
				).append(
					$("<label></label>").attr("for", "system-event")
				)
			)
		).append(
			$("<td></td>").append(
				$("<label></label>").css("cursor", "pointer").attr("for", "system-event").text("<?=$lang['NOTIFICATION']['SYSTEM_EVENT']?>")
			)
		)
	).append(
		$("<tr></tr>").append(
			$("<td></td>").attr("width", "1%").append(
				$("<div></div>").attr("class", "checkbox").append(
					$("<input type='checkbox'/>").attr({
						"id": "video-event",
						"name": "checkbox"
					})
				).append(
					$("<label></label>").attr("for", "video-event")
				)
			)
		).append(
			$("<td></td>").append(
				$("<label></label>").css("cursor", "pointer").attr("for", "video-event").text("<?=$lang['NOTIFICATION']['VIDEO_EVENT']?>")
			)
		)
	).append(
		$("<tr></tr>").append(
			$("<td></td>").css("height", "3px").attr("colSpan", "2")
		)
	).append(
		$("<tr></tr>").append(
			$("<td></td>").attr("colSpan", "2").text("<?=$lang['NOTIFICATION']['TIME_RANGE']?>")
		)
	).append(
		$("<tr></tr>").append(
			$("<td></td>").attr("colSpan", "2").append(
				$("<select></select>").attr("id", "time-range").append(
					$("<option></option>").val(0).text("<?=$lang['NOTIFICATION']['ALL']?>")
				).append(
					$("<option></option>").val(-1).text("<?=$lang['NOTIFICATION']['OLDER_THEN_1_MON']?>")
				).append(
					$("<option></option>").val(-3).text("<?=$lang['NOTIFICATION']['OLDER_THEN_3_MON']?>")
				).append(
					$("<option></option>").val(-6).text("<?=$lang['NOTIFICATION']['OLDER_THEN_6_MON']?>")
				).append(
					$("<option></option>").val(-12).attr("selected", true).text("<?=$lang['NOTIFICATION']['OLDER_THEN_1_YEAR']?>")
				).append(
					$("<option></option>").val(-24).text("<?=$lang['NOTIFICATION']['OLDER_THEN_2_YEAR']?>")
				).append(
					$("<option></option>").val(-36).text("<?=$lang['NOTIFICATION']['OLDER_THEN_3_YEAR']?>")
				)
			)
		)
	);

	popupConfirmWindow($message, function(){
		var data = {
			system: $("#system-event").attr("checked") ? "1" : "0",
			video: $("#video-event").attr("checked") ? "1" : "0",
			time: $("#time-range").val()
		};

		popupConfirmWindow("<?=$lang['NOTIFICATION']['MAKE_SURE_YOU_WANT_TO_CLEAR_EVENT_TYPE_YOU_SELECTED']?>", function(){
			$("#wait-loader").show();
			$.ajax({
				url: "db_notification_ajax.php?act=clear_notification",
				data: data,
				type: "POST",
				success: function(data, textStatus, jqXHR){
					init();
				},
				error: function(jqXHR, textStatus, errorThrown){
					if(jqXHR.status === 0){ return; }// include timeout

					alert(jqXHR.responseText);
				},
				complete: function(){}
			});
		}, function(){
		});
	}, function(){
	});
}

function read_notification(){
	popupConfirmWindow("<?=$lang['NOTIFICATION']['MAKE_SURE_YOU_WANT_TO_MAKE_ALL_AS_READ']?>", function(){
		$("#wait-loader").show();
		$.ajax({
			url: "db_notification_ajax.php?act=read_notification",
			type: "POST",
			success: function(data, textStatus, jqXHR){
				init();
			},
			error: function(jqXHR, textStatus, errorThrown){
				if(jqXHR.status === 0){ return; }// include timeout

				alert(jqXHR.responseText);
			},
			complete: function(){}
		});
	}, function(){
	});
}

function utcToLocalTime(timeString){
	var paddingZero = function(number, digits) {
	    return Array(Math.max(digits - String(number).length + 1, 0)).join(0) + number;
	};

	var date = new Date(timeString);
	date.setTime(date.getTime() - (date.getTimezoneOffset() * 60000));

	return date.getFullYear() + "-" + paddingZero(date.getMonth() + 1, 2) + "-" + paddingZero(date.getDate(), 2) + " " + paddingZero(date.getHours(), 2) + ":" + paddingZero(date.getMinutes(), 2) + ":" + paddingZero(date.getSeconds(), 2);
};
</script>
<?php
}
?>