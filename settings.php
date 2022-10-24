<?php
function customized_header(){
	global $lang;
?>
<link rel="stylesheet" type="text/css" href="./css/list.css">
<style type="text/css">
.form-title{
	border: 1px solid #ccc;
	background: linear-gradient(#035002 0px, #eee 600%);
	margin-bottom: 1px;
	padding: 12px 14px;
	font-size: 13px;
	font-weight: bold;
	color: #eee;
}

.form-container{
    border: 1px solid #ccc;
    background-color: #fff;
    /*outline: 1px solid #F1F1F1;*/
}

.form-container td{
	padding: 14px 0;
}

.form-container td.header{
	padding-right: 14px;
	text-align:right;
}

.form-container tr.hr td{
	border-top: 1px solid #eee;
}

.form-container tr.footer td{
	background-color: #fafafa;
	border-top: 1px solid #ccc;
	box-shadow: 0 1px 0 #fff inset;
}

.form-error{
	font-size:12px;
	color:#FF1700;
	padding-left:10px;
}

.description {
    margin-bottom: 20px;
}

table.list td{
	padding:10px;
}

table.list thead td{
	padding-bottom:5px;
}

table.list tbody td:last-child{
	padding:5px;
}

/* QR Code*/
.qr-code{
	width:24px;
	height:24px;
	background-image: url("./image/ic_qr_code.png");
	position:absolute;
	right:0;
	top:0;
	cursor: pointer;
}

/* switch checkbox */
.switch {
	position: relative;
	display: inline-block;
	width: 50px;
	height: 24px;
}

.switch input {display:none;}

.slider {
	position: absolute;
	cursor: pointer;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background-color: #ccc;
	-webkit-transition: .4s;
	transition: .4s;
	border: 1px solid #B3B3B3;
}

.slider:before {
	position: absolute;
	content: "";
	height: 16px;
	width: 16px;
	left: 3px;
	bottom: 3px;
	background-color: white;
	-webkit-transition: .4s;
	transition: .4s;
}

input:checked + .slider {
	background-color: #4d90fe;
	border: 1px solid #3079ed;
}

input:focus + .slider {
	box-shadow: 0 0 1px #4d90fe;
	border: 1px solid #3079ed;
}

input:checked + .slider:before {
	-webkit-transform: translateX(26px);
	-ms-transform: translateX(26px);
	transform: translateX(26px);
}

.slider.round {
	border-radius: 24px;
}

.slider.round:before {
	border-radius: 50%;
}
</style>
<script language="JavaScript">
function onSubmitPassword(form){
	var $button = $(form).find("input[type='submit']");
	var $loader = $button.parent().find(".button-loader");

	$button.attr("disabled", true).css("color", "transparent");
	$loader.show();

	$.ajax({
		url: "settings_ajax.php?act=set_password",
		type: "POST",
		data: {
			current_password: $("#current_password").val(),
			new_password: $("#new_password").val(),
			confirm_password: $("#confirm_password").val()
		},
		dataType: "xml",
		success: function(data, textStatus, jqXHR){
			var $password = $(data).find("password");

			$(form).find("input").removeClass("error");
			$(form).find("span.form-error").remove();

			if($password.attr("result") == "OK"){
				$("#current_password").val("");
				$("#new_password").val("");
				$("#confirm_password").val("");

				popupSuccessWindow("<?=$lang['SETTING']['POPUP']['SAVE_SUCCESS'];?>");
			}
			else{
				var $currentPassword = $password.find("current_password");
				if($currentPassword.length > 0){
					$("#current_password").addClass("error").after(
						$("<span></span>").addClass("form-error").text($currentPassword.text())
					);
				}

				var $newPassword = $password.find("new_password");
				if($newPassword.length > 0){
					$("#new_password").addClass("error").after(
						$("<span></span>").addClass("form-error").text($newPassword.text())
					);
				}

				var $confirmPassword = $password.find("confirm_password");
				if($confirmPassword.length > 0){
					$("#confirm_password").addClass("error").after(
						$("<span></span>").addClass("form-error").text($confirmPassword.text())
					);
				}

				popupErrorWindow("<?=$lang['SETTING']['POPUP']['SAVE_FAILED'];?>");
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

			alert(jqXHR.responseText);
		},
		complete: function(){
			$button.attr("disabled", false).css("color", "");
			$loader.hide();
		}
	});

	return false;
}

function onSubmitInformation(form){
	var $button = $(form).find("input[type='submit']");
	var $loader = $button.parent().find(".button-loader");

	$button.attr("disabled", true).css("color", "transparent");
	$loader.show();

	$.ajax({
		url: "settings_ajax.php?act=set_information",
		type: "POST",
		data: {
			nickname: $("#nickname").val(),
			email: $("#email").val(),
			company: $("#company").val(),
			country: $("#country").val(),
			timezone: (new Date()).getTimezoneOffset()
		},
		dataType: "xml",
		success: function(data, textStatus, jqXHR){
			var $info = $(data).find("info");

			$(form).find("input").removeClass("error");
			$(form).find("span.form-error").remove();

			if($info.attr("result") == "OK"){
				popupSuccessWindow("<?=$lang['SETTING']['POPUP']['SAVE_SUCCESS_AND_VALIDATE_EMAIL'];?>");
				$("#account-username").text($("#nickname").val());
			}
			else{
				var $email = $info.find("email");
				if($email.length > 0){
					$("#email").addClass("error").after(
						$("<span></span>").addClass("form-error").text($email.text())
					);
				}

				var $company = $info.find("company");
				if($company.length > 0){
					$("#company").addClass("error").after(
						$("<span></span>").addClass("form-error").text($company.text())
					);
				}

				popupErrorWindow("<?=$lang['SETTING']['POPUP']['SAVE_FAILED'];?>");
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

			alert(jqXHR.responseText);
		},
		complete: function(){
			$button.attr("disabled", false).css("color", "");
			$loader.hide();
		}
	});

	return false;
}

function onClickAddShareAccount(){
	if($("#share-account-add").val() == ""){
		popupErrorWindow("<?=$lang['SETTING']['POPUP']['USERNAME_IS_EMPTY'];?>");
		return;
	}

	popupConfirmWindow("<?=$lang['SETTING']['POPUP']['ARE_YOU_SURE_ADD_SHARE_WITH_USERNAME'];?>".replace("%username%", $("#share-account-add").val()), function(){
		$("#wait-loader").show();

		$.ajax({
			url: "settings_ajax.php?act=add_share_account&username=" + $("#share-account-add").val(),
			type: "POST",
			dataType: "xml",
			success: function(data, textStatus, jqXHR){
				var $share = $(data).find("share");

				if($share.attr("result") == "OK"){
					$("#share-account-add").val("");
					$("#share-list").show();
					$("#share-none").hide();

					var findFlag = false;
					var createRow = function(username, nickname, accountUID){
						return $("<tr></tr>").append(
							$("<td></td>").text(username)
						).append(
							$("<td></td>").text(nickname)
						).append(
							$("<td></td>").append(
								$("<input type='button'/>").val("<?=$lang['SETTING']['REMOVE'];?>").addClass("red").css("width", "100px").bind("click", function(){
									onClickRemoveShareAccount(this, accountUID, username);
								})
							)
						);
					};

					$("#share-list tr td:nth-child(1)").each(function(index){
						if($(this).text() < $share.attr("username")){
							$("#share-list tr:eq(" + index + ")").after(
								createRow($share.attr("username"), $share.attr("nickname"), $share.attr("account_uid"))
							);

							findFlag = true;
							return false;
						}
					});

					if(findFlag == false){
						$("#share-list").prepend(
							createRow($share.attr("username"), $share.attr("nickname"), $share.attr("account_uid"))
						);
					}

					popupSuccessWindow("<?=$lang['SETTING']['POPUP']['ADD_SUCCESSFULLY'];?>");
				}
				else{
					popupErrorWindow($share.text());
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

				alert(jqXHR.responseText);
			},
			complete: function(){
				$("#wait-loader").hide();
			}
		});
	});
}

function onClickRemoveShareAccount(button, accountUID, username){
	popupConfirmWindow("<?=$lang['SETTING']['POPUP']['ARE_YOU_SURE_REMOVE_SHARE_WITH_USERNAME'];?>".replace("%username%", username), function(){
		$("#wait-loader").show();

		$.ajax({
			url: "settings_ajax.php?act=remove_share_account&account_uid=" + accountUID,
			type: "POST",
			dataType: "xml",
			success: function(data, textStatus, jqXHR){
				var $share = $(data).find("share");

				if($share.attr("result") == "OK"){
					$(button).closest("tr").remove();

					if($("#share-list").children("tr").length <= 0){
						$("#share-none").show();
						$("#share-list").hide();
					}

					popupSuccessWindow("<?=$lang['SETTING']['POPUP']['REMOVE_SUCCESSFULLY'];?>");
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

				alert(jqXHR.responseText);
			},
			complete: function(){
				$("#wait-loader").hide();
			}
		});
	});
}

function popupWindow($popupWindow, callback){
	var $wrapper = $popupWindow.show().find(".popup-wrapper");
	$wrapper.css("marginTop", ($(window).height() / 2 - $wrapper.outerHeight() / 2 + $(window).scrollTop()) + "px");
	windowCallback = callback;
}

function onClickWindowButton(button){
	if(typeof(windowCallback) == "function"){
		windowCallback(button);
	}
}

function onClickLineBotQRCode(){
	popupWindow($("#window-line-bot-qr-code"), function(result){
		if(result == "close"){
			$("#window-line-bot-qr-code").hide();
		}
	});
}

function onClickLineBotStatus(id, input){
	var status = $(input).attr("checked") ? "1" : "0";

	$("#wait-loader").show();
	$.ajax({
		url: "settings_ajax.php?act=change_line_bot_status",
		data: {
			"line_uid": id,
			"line_status": status
		},
		type: "POST",
		dataType: "xml",
		success: function(data, textStatus, jqXHR){
			var $cmd = $(data).find("cmd");

			if($cmd.attr("result") == "OK"){
				if(status == "1"){
					$(input).attr("checked", true);
					popupSuccessWindow("<?=$lang['SETTING']['POPUP']['ENABLE_SUCCESS'];?>");
				}
				else{
					$(input).attr("checked", false);
					popupSuccessWindow("<?=$lang['SETTING']['POPUP']['DISABLE_SUCCESS'];?>");
				}
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

			alert(jqXHR.responseText);
		},
		complete: function(){
			$("#wait-loader").hide();
		}
	});
}

function onClickLineBotNickname(id, icon){
	var $nickname = $(icon).next("span");

	popupWindow($("#window-line-bot-nickname"), function(result){
		if(result == "ok"){
			if($("#window-line-bot-nickname-input").val() == ""){
				popupErrorWindow("<?=$lang['SETTING']['POPUP']['NICKNAME_IS_EMPTY'];?>");
				return;
			}

			$("#window-line-bot-nickname").hide();
			$("#wait-loader").show();
			$.ajax({
				url: "settings_ajax.php?act=change_line_bot_nickname",
				data: {
					"line_uid": id,
					"line_nickname": $("#window-line-bot-nickname-input").val()
				},
				type: "POST",
				dataType: "xml",
				success: function(data, textStatus, jqXHR){
					var $cmd = $(data).find("cmd");

					if($cmd.attr("result") == "OK"){
						$nickname.text($("#window-line-bot-nickname-input").val());

						popupSuccessWindow("<?=$lang['SETTING']['POPUP']['MODIFY_SUCCESS'];?>");
					}
				},
				error: function(jqXHR, textStatus, errorThrown){
					if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

					alert(jqXHR.responseText);
				},
				complete: function(){
					$("#wait-loader").hide();
				}
			});
		}
		else if(result == "cancel"){
			$("#window-line-bot-nickname").hide();
		}
	});

	$("#window-line-bot-nickname-input").val($nickname.text()).focus().select();
}

function onClickLineBotRemove(id, button){
	popupConfirmWindow("<?=$lang['SETTING']['POPUP']['ARE_YOU_SURE_REMOVE_ACCOUNT'];?>", function(){
		$("#wait-loader").show();

		$.ajax({
			url: "settings_ajax.php?act=remove_line_bot_account",
			data: {
				"line_uid": id
			},
			type: "POST",
			dataType: "xml",
			success: function(data, textStatus, jqXHR){
				var $cmd = $(data).find("cmd");

				if($cmd.attr("result") == "OK"){
					$(button).closest("tr").remove();

					if($("#line-bot-list").children("tr").length <= 0){
						$("#line-bot-none").show();
						$("#line-bot-list").hide();
					}

					popupSuccessWindow("<?=$lang['SETTING']['POPUP']['REMOVE_SUCCESSFULLY'];?>");
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

				alert(jqXHR.responseText);
			},
			complete: function(){
				$("#wait-loader").hide();
			}
		});
	});
}
</script>
<?php
}
?>

<?php
function customized_body(){
	global $lang, $language;

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . DB_NAME : (
			DB_IS_MYSQL  ? "USE " . DB_NAME : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
		$sth->execute();

		if($_GET["job"] == "confirm_email"){
			$sth = $dbh->prepare(
				DB_IS_MSSQL  ? "SELECT UID, Email FROM confirm WHERE AccountUID = ? AND ConfirmCode = ?" : (
				DB_IS_MYSQL  ? "SELECT UID, Email FROM confirm WHERE AccountUID = ? AND ConfirmCode = ?" : (
				DB_IS_ORACLE ? "SELECT \"UID\", Email FROM confirm WHERE AccountUID = ? AND ConfirmCode = ?" : null)));
			$sth->execute(array($_SESSION["account_uid"], $_GET["confirm_code"]));

			$row = $sth->fetch(PDO::FETCH_ASSOC);
			$sth->closeCursor();

			$confirm_uid = $row["UID"];
			$email = $row["Email"];

			if(!empty($email)){
				$sth = $dbh->prepare(
					DB_IS_MSSQL  ? "SELECT COUNT(*) AS Counter FROM account WHERE Email = ? AND UID != ?" : (
					DB_IS_MYSQL  ? "SELECT COUNT(*) AS Counter FROM account WHERE Email = ? AND UID != ?" : (
					DB_IS_ORACLE ? "SELECT COUNT(*) AS Counter FROM account WHERE Email = ? AND \"UID\" != ?" : null)));
				$sth->execute(array($email, $_SESSION["account_uid"]));

				$row = $sth->fetch(PDO::FETCH_ASSOC);
				$sth->closeCursor();

				if($row["Counter"] > 0){
?>
<script language="JavaScript">
$(function() {
    popupErrorWindow("<?=$lang['SETTING']['POPUP']['EXIST_EMAIL'];?>");
	window.history.replaceState({}, '', '/?act=settings');
});
</script>
<?php
				}
				else{
					$sth = $dbh->prepare(
						DB_IS_MSSQL  ? "UPDATE account SET Email = ? WHERE UID = ?" : (
						DB_IS_MYSQL  ? "UPDATE account SET Email = ? WHERE UID = ?" : (
						DB_IS_ORACLE ? "UPDATE account SET Email = ? WHERE \"UID\" = ?" : null)));
					$sth->execute(array($email, $_SESSION["account_uid"]));

					$sth = $dbh->prepare(
						DB_IS_MSSQL  ? "DELETE FROM confirm WHERE UID = ?" : (
						DB_IS_MYSQL  ? "DELETE FROM confirm WHERE UID = ?" : (
						DB_IS_ORACLE ? "DELETE FROM confirm WHERE \"UID\" = ?" : null)));
					$sth->execute(array($confirm_uid));
?>
<script language="JavaScript">
$(function() {
    popupSuccessWindow("<?=$lang['SETTING']['POPUP']['MODIFY_EMAIL_SUCCESS'];?>");
	window.history.replaceState({}, '', '/?act=settings');
});
</script>
<?php
				}
			}
			else{
?>
<script language="JavaScript">
$(function() {
    popupErrorWindow("<?=$lang['SETTING']['POPUP']['MODIFY_EMAIL_FAILED'];?>");
	window.history.replaceState({}, '', '/?act=settings');
});
</script>
<?php
			}
		}

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "SELECT * FROM account WHERE UID = ?" : (
			DB_IS_MYSQL  ? "SELECT * FROM account WHERE UID = ?" : (
			DB_IS_ORACLE ? "SELECT * FROM account WHERE \"UID\" = ?" : null)));
		$sth->execute(array($_SESSION["account_uid"]));

		$row = $sth->fetch(PDO::FETCH_ASSOC);
		$sth->closeCursor();
?>
<div style="padding:20px;">
	<div class="title" style="position:relative;"><?=$lang['SETTING']['SETTING'];?></div>

	<div class="form-title"><?=$lang['SETTING']['PASSWORD'];?></div>
	<div class="form-container">
		<form method="POST" action="." onsubmit="return onSubmitPassword(this);">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<colgroup>
					<col width="250">
				</colgroup>
				<tr>
					<td class="header">*<?=$lang['SETTING']['CURRENT_PASSOWRD'];?></td>
					<td><input type="password" id="current_password" style="width:300px;"></td>
				</tr>
				<tr class="hr">
					<td class="header">*<?=$lang['SETTING']['NEW_PASSOWRD'];?></td>
					<td><input type="password" id="new_password" style="width:300px;"></td>
				</tr>
				<tr class="hr">
					<td class="header">*<?=$lang['SETTING']['RETYPE_NEW_PASSOWRD'];?></td>
					<td><input type="password" id="confirm_password" style="width:300px;"></td>
				</tr>
				<tr class="footer">
					<td></td>
					<td>
						<div style="position:relative;display:inline-block;">
							<input type="submit" value="<?=$lang['SUBMIT'];?>" style="width:100px;">
							<div class="button-loader">
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
					</td>
				</tr>
			</table>
		</form>
	</div>

	<div class="form-title" style="margin-top:30px;"><?=$lang['SETTING']['INFORMATION'];?></div>
	<div class="form-container">
		<form method="POST" action="." onsubmit="return onSubmitInformation(this);">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<colgroup>
					<col width="250">
				</colgroup>
				<tr>
					<td class="header">*<?=$lang['SETTING']['NICKNAME'];?></td>
					<td><input type="text" id="nickname" style="width:300px;" value="<?php echo $row['Nickname']?>"></td>
				</tr>
				<tr class="hr">
					<td class="header">*<?=$lang['SETTING']['EMAIL_ADDRESS'];?></td>
					<td><input type="text" id="email" style="width:300px;" value="<?php echo $row['Email']?>"></td>
				</tr>
				<tr class="hr">
					<td class="header"><?=$lang['SETTING']['COMPANY'];?></td>
					<td><input type="text" id="company" style="width:300px;" value="<?php echo $row['Company']?>"></td>
				</tr>
				<tr class="hr">
					<td class="header"><?=$lang['SETTING']['COUNTRY'];?></td>
					<td>
						<select id="country">
<?php
		foreach ($lang['COUNTRY'] as $key => $value) {
			echo "<option value='" . $key . "'" . ($key == $row["CountryUID"] ? " selected" : "") . ">" . $value . "</option>";
		}
?>
						</select>
					</td>
				</tr>
				<tr class="footer">
					<td></td>
					<td>
						<div style="position:relative;display:inline-block;">
							<input type="submit" value="<?=$lang['SUBMIT'];?>" style="width:100px;">
							<div class="button-loader">
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
					</td>
				</tr>
			</table>
		</form>
	</div>

	<div class="title" style="position:relative;margin-top:40px;"><?=$lang['SETTING']['DEVICE_SHARE'];?></div>
	<div class="description"><?=$lang['SETTING']['DEVICE_SHARE_DESCRIPTION'];?></div>

	<table class="list">
		<thead>
			<tr>
				<td style="width:300px;"><?=$lang['SETTING']['SHARE_USERNAME'];?></td>
				<td><?=$lang['SETTING']['SHARE_NICKNAME'];?></td>
				<td style="width:1%;text-align:center;"><?=$lang['SETTING']['SHARE_ACTION'];?></td>
			</tr>
		</thead>
<?php

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "SELECT AccountUIDShareTo, Username, Nickname FROM share, account WHERE share.AccountUIDShareBy = ? AND share.AccountUIDShareTo = account.UID ORDER BY account.Username" : (
			DB_IS_MYSQL  ? "SELECT AccountUIDShareTo, Username, Nickname FROM share, account WHERE share.AccountUIDShareBy = ? AND share.AccountUIDShareTo = account.UID ORDER BY account.Username" : (
			DB_IS_ORACLE ? "SELECT AccountUIDShareTo, Username, Nickname FROM \"SHARE\", account WHERE \"SHARE\".AccountUIDShareBy = ? AND \"SHARE\".AccountUIDShareTo = account.\"UID\" ORDER BY account.Username" : null)));
		$sth->execute(array($_SESSION["account_uid"]));

		$row = $sth->fetch(PDO::FETCH_ASSOC);
		$hasRow = $row ? true : false;
?>
		<tbody id="share-list" style="<?=$hasRow ? "" : "display:none;"?>">
<?php
		if($hasRow){
			do{
?>
			<tr>
				<td><?=$row["Username"]?></td>
				<td><?=$row["Nickname"]?></td>
				<td><input type="button" value="<?=$lang['SETTING']['REMOVE'];?>" class="red" style="width:100px;" onclick="onClickRemoveShareAccount(this, <?=$row["AccountUIDShareTo"]?>, '<?=$row["Username"]?>');"></td>
			</tr>
<?php
			} while($row = $sth->fetch(PDO::FETCH_ASSOC));
		}
		$sth->closeCursor();
?>
		</tbody>
		<tbody id="share-none" style="<?=$hasRow ? "display:none;" : ""?>">
			<tr>
				<td colSpan="3" style="background-color:#FBFBFB;text-align:center;padding:40px 0;"><?=$lang['SETTING']['NO_SHARE_ACCOUNT'];?></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colSpan="2" style="padding:3px 0 0 0;">
					<input type="text" id="share-account-add" style="width:200px;vertical-align: middle;" placeholder="<?=$lang['SETTING']['SHARE_USERNAME'];?>"> <input type="button" value="<?=$lang['SETTING']['ADD'];?>" style="width:100px;vertical-align: middle;" onclick="onClickAddShareAccount();">
				</td>
				<td style="padding:5px;">
					<input type="button" value="<?=$lang['SETTING']['REMOVE'];?>" class="red" style="width:100px;visibility:hidden;">
				</td>
			</tr>
		</tfoot>
	</table>
<?php
		$dbh = null;
	}
	catch(PDOException $e) {
		throw new Exception("Database exception!" . $e->getMessage());
	}

	if(LINE_BOT_QR_CODE != ""){
?>
	<div class="title" style="position:relative;margin-top:40px;">Bot Service<div class="qr-code" onClick="onClickLineBotQRCode();"></div></div>
	<div class="description"><?=$lang['SETTING']['LINE_BOT_DESCRIPTION'];?></div>

	<table class="list">
		<thead>
			<tr>
				<td style="width:1%;text-align:center;"><?=$lang['SETTING']['LINE_BOT_STATUS'];?></td>
				<td style="width:400px;">ID</td>
				<td><?=$lang['SETTING']['LINE_BOT_NICKNAME'];?></td>
				<td style="width:1%;text-align:center;"><?=$lang['SETTING']['LINE_BOT_ACTION'];?></td>
			</tr>
		</thead>
<?php
		// Request the runtime to download the rule to device
		set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
		    // error was suppressed with the @-operator
		    if (0 === error_reporting()) {
		        return false;
		    }

		    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
		});

		try{
			$port = 1230;
			$address = "127.0.0.1";

			$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
			socket_connect($socket, $address, $port);

			$in = '<cmd type="lineBotGetAccountList" account_uid="' . $_SESSION['account_uid'] . '" />';
			socket_write($socket, $in);
			socket_shutdown($socket, 1);

			$out = "";
			while($buffer = socket_read($socket, 2048)){
				$out .= $buffer;
			}

			socket_close($socket);
		}
		catch(ErrorException $e){}

		restore_error_handler();
		// Notify END

		$cmd = simplexml_load_string($out);
		if(count($cmd->user) > 0){
			$existFlag = true;
		}
		else{
			$existFlag = false;
		}
?>
		<tbody id="line-bot-list" style="<?=$existFlag ? "" : "display:none;"?>">
<?php
		foreach ($cmd->user as $user) {
?>
			<tr>
				<td>
					<label class="switch">
						<input type="checkbox" onClick="onClickLineBotStatus('<?=$user['userId']?>', this);return false;"<?=($user['status'] == "1" ? " checked" : "")?>>
						<span class="slider round"></span>
					</label>
				</td>
				<td><?=substr($user['userId'], 0, 6) . str_repeat("*", strlen($user['userId']) - 12) . substr($user['userId'], strlen($user['userId']) - 6)?></td>
				<td style="position:relative;"><div style="position:absolute;left:-12px;cursor:pointer;" onClick="onClickLineBotNickname('<?=$user['userId']?>', this);"><svg><use xlink:href="image/ics.svg#edit"></use></svg></div><span><?=$user['name']?></span></td>
				<td><input type="button" value="<?=$lang['SETTING']['REMOVE'];?>" class="red" style="width:100px;" onClick="onClickLineBotRemove('<?=$user['userId']?>', this);"></td>
			</tr>
<?php
		}
?>
		</tbody>
		<tbody id="line-bot-none" style="<?=$existFlag ? "display:none;" : ""?>">
			<tr>
				<td colSpan="4" style="background-color:#FBFBFB;text-align:center;padding:40px 0;"><?=$lang['SETTING']['NO_LINE_BOT_ACCOUNT'];?></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td style="padding:10px;">
					<label class="switch" style="visibility:hidden;">
						<input type="checkbox">
						<span class="slider round"></span>
					</label>
				</td>
				<td></td>
				<td></td>
				<td style="padding:5px;">
					<input type="button" value="<?=$lang['SETTING']['REMOVE'];?>" class="red" style="width:100px;visibility:hidden;">
				</td>
			</tr>
		</tfoot>
	</table>
<?php
	}
?>
</div>

<?php
	if(LINE_BOT_QR_CODE != ""){
?>
<div class="popup-background" id="window-line-bot-qr-code" style="text-align: center;">
	<div class="popup-wrapper" style="width:auto;display:inline-block;">
		<div class="popup-container">
			<div class="popup-title"><?=$lang['SETTING']['POPUP']['SCAN_QR_CODE'];?></div>
			<div class="popup-content" style="text-align:center;padding:10px;box-sizing: border-box;">
				<img src="<?=LINE_BOT_QR_CODE?>" style="width:180px;height:180px;">
			</div>
			<div class="popup-footer">
				<input type="button" class="gray" value="<?=$lang['SETTING']['CLOSE'];?>" onclick="onClickWindowButton('close');">
			</div>
		</div>
	</div>
</div>

<div class="popup-background" id="window-line-bot-nickname" style="text-align: center;">
	<div class="popup-wrapper" style="width:auto;min-width:300px;display:inline-block;">
		<div class="popup-container">
			<div class="popup-title"><?=$lang['SETTING']['POPUP']['NICKNAME_SETTING'];?></div>
			<div class="popup-content" style="text-align:center;padding:10px;box-sizing: border-box;">
				<div style="margin-bottom:10px;text-align:left;"><?=$lang['SETTING']['POPUP']['PLEASE_ENTER_THE_NEW_NICKNAME'];?></div>
				<input type="text" id="window-line-bot-nickname-input" style="width:100%;box-sizing: border-box;" maxlength="20">
			</div>
			<div class="popup-footer">
				<input type="button" value="<?=$lang['OK'];?>" onclick="onClickWindowButton('ok');">&nbsp;&nbsp;<input type="button" class="gray" value="<?=$lang['CANCEL'];?>" onclick="onClickWindowButton('cancel');">
			</div>
		</div>
	</div>
</div>
<?php
	}
}
?>