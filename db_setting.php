<?php
function customized_header(){
	global $lang;
?>
<link rel="stylesheet" type="text/css" href="./css/list.css">
<link rel="stylesheet" type="text/css" href="./css/checkbox.css" />
<style type="text/css">
.description{
    margin-bottom: 20px;
}

.list-table{
	margin-top:0;
}

.list-table-cell{
	padding:10px;
}

.list-table-header-group .list-table-cell{
	padding-bottom:5px;
	padding-right:0px;
}

.list-table-row-group .list-table-row .list-table-cell{
	padding-right:0px;
	cursor: pointer;
}

.remove > *{
	vertical-align: middle;
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
	<div class="title" style="position:relative;"><?=$lang['DB_SETTING']['NOTIFICATION_SETTINGS'];?></div>

	<div class="description"><?=$lang['DB_SETTING']['SELECT_THE_EVENT_NOTIFICATION_YOU_LIKE_TO_RECEIVE_VIA_EMAIL_BELOW'];?></div>

	<div class="list-table">
		<div class="list-table-header-group">
			<div class="list-table-row">
				<div class="list-table-cell" style="width:1%;"></div>
				<div class="list-table-cell"><?=$lang['DB_SETTING']['EVENT'];?></div>
			</div>
		</div>

		<div class="list-table-row-group" id="list">
			<div class="list-table-row">
				<div class="list-table-cell">
				    <div class="checkbox">
						<input type="checkbox" id="notify-0" name="check" />
						<label for="notify-0"></label>
				    </div>
				</div>
				<label class="list-table-cell" for="notify-0"><?=$lang['DB_SETTING']['INSUFFICIENT_DATABASE_SPACE'];?></label>
			</div>
		<!--
			<div class="list-table-row">
				<div class="list-table-cell">
				    <div class="checkbox">
						<input type="checkbox" id="notify-1" name="check" />
						<label for="notify-1"></label>
				    </div>
				</div>
				<label class="list-table-cell" for="notify-1"><?=$lang['DB_SETTING']['DATABASE_PROCESSING_ERROR_OR_TRANSACTION'];?></label>
			</div>
		-->
			<div class="list-table-row">
				<div class="list-table-cell">
				    <div class="checkbox">
						<input type="checkbox" id="notify-2" name="check" />
						<label for="notify-2"></label>
				    </div>
				</div>
				<label class="list-table-cell" for="notify-2"><?=$lang['DB_SETTING']['MODULE_SETTINGS_CHANGE'];?></label>
			</div>
			<div class="list-table-row">
				<div class="list-table-cell">
				    <div class="checkbox">
						<input type="checkbox" id="notify-4" name="check" />
						<label for="notify-4"></label>
				    </div>
				</div>
				<label class="list-table-cell" for="notify-4"><?=$lang['DB_SETTING']['UNKNOWN_TO_TRY_TO_LOG_IN_MORE_THAN_TEN_TIMES'];?></label>
			</div>
			<div class="list-table-row">
				<div class="list-table-cell">
				    <div class="checkbox">
						<input type="checkbox" id="notify-3" name="check" />
						<label for="notify-3"></label>
				    </div>
				</div>
				<label class="list-table-cell" for="notify-3"><?=$lang['DB_SETTING']['DEVICE_DISCONNECTED'];?></label>
			</div>
		</div>

		<div class="list-table-footer-group">
			<div class="list-table-row">
				<div class="list-table-cell"></div>
				<div class="list-table-cell">
					<div style="display: inline-block; position: relative;">
						<input type="button" id="Savebutton1" value="<?=$lang['DB_SETTING']['SAVE'];?>" style="width:100px;" onclick="onClickSaveButton()">
						<div class="button-loader" id="loader1" style="display:none;">
							<div class="circle1 circle"></div>
							<div class="circle2 circle"></div>
							<div class="circle3 circle"></div>
							<div class="circle4 circle"></div>
							<div class="circle5 circle"></div>
							<div class="circle6 circle"></div>
							<div class="circle7 circle"></div>
							<div class="circle8 circle"></div>
							<div class="circle9 circle"></div>
							<div class="circle10 circle"></div>
							<div class="circle11 circle"></div>
							<div class="circle12 circle"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="title" style="position:relative;margin-top:40px;"><?=$lang['DB_SETTING']['DATA_IMPORT'];?></div>
	<div class="description"><?=$lang['DB_SETTING']['SET_WHETHER_IO_MODULES_AND_METER_DATA_ARE_IMPORTED_INTO_THE_DATABASE'];?></div>
	<!--Realtime-->
	<div>
		<div class="checkbox" style="float:left;margin-right:5px;">
			<input type="checkbox" id="realtime-enable"/>
			<label for="realtime-enable"></label>
		</div>
		<label for="realtime-enable" style="cursor:pointer;"><?=$lang['DB_SETTING']['REALTIME_IMPORT_ENABLED'];?></label>
	</div>
	
	<!--History-->
	<div style="margin-top: 10px;">
		<div class="checkbox" style="float:left;margin-right:5px;">
			<input type="checkbox" id="database-enable"/>
			<label for="database-enable"></label>
		</div>
	<label for="database-enable" style="cursor:pointer;"><?=$lang['DB_SETTING']['HISTORY_IMPORT_ENABLED'];?></label>
	</div>
	<div style="clear:both;">
		<div style="clear:both;margin-top:20px; position: relative; display: inline-block;">
			<input type="button" id="Savebutton2" value="<?=$lang['DB_SETTING']['SAVE'];?>" style="width:100px;" onclick="onClickUpdateImportDBButton()">
			<div class="button-loader" id="loader2" style="display:none;">
				<div class="circle1 circle"></div>
				<div class="circle2 circle"></div>
				<div class="circle3 circle"></div>
				<div class="circle4 circle"></div>
				<div class="circle5 circle"></div>
				<div class="circle6 circle"></div>
				<div class="circle7 circle"></div>
				<div class="circle8 circle"></div>
				<div class="circle9 circle"></div>
				<div class="circle10 circle"></div>
				<div class="circle11 circle"></div>
				<div class="circle12 circle"></div>
			</div>
		</div>
	</div>

	<div class="title" style="position:relative;margin-top:40px;"><?=$lang['DB_SETTING']['CLEAR_DATABASE_DATA'];?></div>
	<div class="description"><?=$lang['DB_SETTING']['ALL_MODULE_DATA_WILL_BE_REMOVED_AND_CAN_NOT_BE_RECOVERED_AFTER_EXECUTION'];?></div>
	<div class="remove"><span><?=$lang['DB_SETTING']['PASSWORD'];?></span>
		<input type="password" id="password" style="width:150px;margin-left:5px;margin-right:5px;">
		<div style="position:relative;display:inline-block;">
			<input type="button" id="clearbutton" value="<?=$lang['DB_SETTING']['CLEAR'];?>" style="width:100px;" class="red" onclick="onClickCleanDBButton()">
			<div class="button-loader" id="loader3" style="display:none;">
				<div class="circle1 circle"></div>
				<div class="circle2 circle"></div>
				<div class="circle3 circle"></div>
				<div class="circle4 circle"></div>
				<div class="circle5 circle"></div>
				<div class="circle6 circle"></div>
				<div class="circle7 circle"></div>
				<div class="circle8 circle"></div>
				<div class="circle9 circle"></div>
				<div class="circle10 circle"></div>
				<div class="circle11 circle"></div>
				<div class="circle12 circle"></div>
			</div>
		</div>
	</div>
</div>
<script language="JavaScript">
select_notification();
function select_notification(){
	var Notification = "";
	var Databaseenable = "0";
	var realtime_enable = "0"
	$.ajax({
		url: "db_setting_ajax.php?act=select_notification",
		type: "POST",
		data: {},
		success: function(data, textStatus, jqXHR){
			var $xmlElement = $(data).find("setting > list");
			for(var i = 0; i < $xmlElement.length; i++)
			{
				Notification = $($xmlElement[i]).attr("notification");
				Databaseenable = $($xmlElement[i]).attr("databaseenable");
				realtime_enable = $($xmlElement[i]).attr("realtime_enable");
			}
			var b_notification = parseInt(Notification).toString(2);
			for(var i = 0 ; i < b_notification.length  ; i++){
				if(b_notification.charAt(parseInt(b_notification.length-1-i))==1){
					$("#notify-" + i).attr("checked",true);
				}
				else{
					$("#notify-" + i).attr("checked",false);
				}
			}
			if(realtime_enable == "1")
				$("#realtime-enable").prop( "checked", true );
			else
				$("#realtime-enable").prop( "checked", false );
			
			if(Databaseenable == "1")
				$("#database-enable").prop( "checked", true );
			else
				$("#database-enable").prop( "checked", false );
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0){ return; }// include timeout
			alert(jqXHR.responseText);
		}
	});
}

function onClickSaveButton(){
	$("[type=button]").attr("disabled", true);
	$("#Savebutton1").css("color", "transparent");
	$("#loader1").show();
	
	var Notification_val = 0;
	for(var ck_notify in $("#list [name=check]:checked")){
		if(typeof($("[name=check]:checked")[ck_notify].id)!="undefined"){
			Notification_val += Math.pow(2,$("#list [name=check]:checked")[ck_notify].id.split('-')[1]);
		}
	}
	$.ajax({
		url: "db_setting_ajax.php?act=save_notification",
		type: "POST",
		data: {
			notification : Notification_val
		},
		success: function(data, textStatus, jqXHR){
			var $xmlElement = $(data).find("setting > list");
			for(var i = 0; i < $xmlElement.length; i++)
				reply = $($xmlElement[i]).attr("reply");
			if(reply == "OK")
				popupSuccessWindow("<?=$lang['DB_SETTING']['SAVE_COMPLETE'];?>");
			else
				popupSuccessWindow("<?=$lang['DB_SETTING']['SAVE_FAILED'];?>");
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0){ return; }// include timeout
			alert(jqXHR.responseText);
		},
		complete: function(){
			$("[type=button]").attr("disabled", false);
			$("#Savebutton1").css("color", "");
			$("#loader1").hide();
		}
	});
}

function onClickUpdateImportDBButton(){
	$("[type=button]").attr("disabled", true);
	$("#Savebutton2").css("color", "transparent");
	$("#loader2").show();
	
	var reply = "";
	$.ajax({
		url: "db_setting_ajax.php?act=UpdateImportDB",
		type: "POST",
		data: {
			realtime_enable : $("#realtime-enable").prop("checked"),
			databaseenable : $("#database-enable").prop("checked")
		},
		success: function(data, textStatus, jqXHR){
			var $xmlElement = $(data).find("setting > list");
			for(var i = 0; i < $xmlElement.length; i++)
				reply = $($xmlElement[i]).attr("reply");
			if(reply == "OK")
				popupSuccessWindow("<?=$lang['DB_SETTING']['SAVE_COMPLETE'];?>");
			else
				popupErrorWindow("<?=$lang['DB_SETTING']['SAVE_FAILED'];?>");
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0){ return; }// include timeout
			alert(jqXHR.responseText);
		},
		complete: function(){
			$("[type=button]").attr("disabled", false);
			$("#Savebutton2").css("color", "");
			$("#loader2").hide();
		}
	});
}

function onClickCleanDBButton(){
	if($("#password").val() == ""){
		popupErrorWindow("<?=$lang['DB_SETTING']['PLEASE_ENTER_A_PASSWORD'];?>");
		return;
	}
	
	var reply = "";
	popupConfirmWindow("<?=$lang['DB_SETTING']['WHETHER_TO_CLEAR_THE_DATABASE_DATA'];?>",
		function(){
			$("[type=button]").attr("disabled", true);
			$("#clearbutton").css("color", "transparent");
			$("#loader3").show();
			
			$.ajax({
				url: "db_setting_ajax.php?act=cleandb",
				type: "POST",
				data: {
					password : $("#password").val()
				},
				success: function(data, textStatus, jqXHR){
					var $xmlElement = $(data).find("setting > list");
					for(var i = 0; i < $xmlElement.length; i++){
						reply = $($xmlElement[i]).attr("reply");
					}
					if(reply == "OK")
						popupSuccessWindow("<?=$lang['DB_SETTING']['CLEAR_COMPLETE'];?>");
					else if(reply == "password_error")
						popupErrorWindow("<?=$lang['DB_SETTING']['PASSWORD_ERROR'];?>");
					else
						popupErrorWindow("<?=$lang['DB_SETTING']['FAILED_TO_CLEAR'];?>");
				},
				error: function(jqXHR, textStatus, errorThrown){
					if(jqXHR.status === 0){ return; }// include timeout
					alert(jqXHR.responseText);
				},
				complete: function(){
					$("[type=button]").attr("disabled", false);
					$("#clearbutton").css("color", "");
					$("#loader3").hide();
				}
			});
		},
		function(){}//Cancel
	);
}


</script>
<?php
}
?>