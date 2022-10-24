<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=1280">
<title><?=WEB_NAME?></title>
<link rel="stylesheet" type="text/css" href="./css/general.css">
<link rel="stylesheet" type="text/css" href="./css/popup.css">
<style type="text/css">
html, body{
	padding:0;
	margin:0;
	background-color:#FFF;
}

.form-wrapper{
	position:relative;
	width:100%;
	margin: 60px auto 0 auto;
	overflow: hidden;
}

#form-container{
	position:absolute;
	top:70px;
	left:50%;
}

.form-block{
	width:320px;
	border: 1px solid #ccc;
	padding:20px;
	background-color: rgba(255, 255, 255, 0.85);
	/*outline: 1px solid #F1F1F1;*/
	position:absolute;
}

.form-title{
	font-size: 22px;
	text-align:center;
	margin-bottom: 20px;
}

.form-content{
	display:table;
	margin:auto;
	width:300px;
}

.form-icon-wrapper{
	position:absolute;
	box-sizing:border-box;
	width:30px;
	height:100%;
	left:0px;
	border:1px solid #ccc;
	border-right-width:0px;
	background-color: #eee;
}

.form-icon{
	position:absolute;
	top:50%;
	left:50%;
	margin-top:-9px;
	margin-left:-9px;
}

.form-field-wrapper{
	position:relative;
	padding-left:30px;
}

.form-field{
	/*width:250px;*/
	width:100%;
	box-sizing:border-box;
}

.form-form-padding{
	height:10px;
}

.form-form-error{
	font-size:12px;
	color:#FF1700;
	padding-top:3px;
}

.form-result{
	text-align:center;
	color:#FF0000;
}

/* Sockets module check*/
#mask-wrapper{
	position:fixed;
	top:0;
	bottom:0;
	left:0;
	right:0;
	background-color: rgba(0,0,0,0.5);
	z-index:999;
}

#mask-container{
	position:absolute;
	top:50px;
	left:50%;
	transform: translateX(-50%);
	width:500px;
	background-color:white;
	border:1px #707070 solid;
	box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);

}

#mask-bar{
	background-color:#9c3231;
	height:3px;

}

#mask-title{
	background-color:#c33f3d;
	color:#FFF;
	padding:15px;
	font-size:18px;
	font-weight:bold;
}

#mask-content{
	padding:15px;
	font-size:15px;
	line-height:1.6;

}

@keyframes ldio-2trppgb1evd-1 {
  0% { top: 36px; height: 128px }
  50% { top: 60px; height: 80px }
  100% { top: 60px; height: 80px }
}
@keyframes ldio-2trppgb1evd-2 {
  0% { top: 41.99999999999999px; height: 116.00000000000001px }
  50% { top: 60px; height: 80px }
  100% { top: 60px; height: 80px }
}
@keyframes ldio-2trppgb1evd-3 {
  0% { top: 48px; height: 104px }
  50% { top: 60px; height: 80px }
  100% { top: 60px; height: 80px }
}
.ldio-2trppgb1evd div { position: absolute; width: 30px }.ldio-2trppgb1evd div:nth-child(1) {
  left: 35px;
  background: #052002;
  animation: ldio-2trppgb1evd-1 1s cubic-bezier(0,0.5,0.5,1) infinite;
  animation-delay: -0.2s
}
.ldio-2trppgb1evd div:nth-child(2) {
  left: 85px;
  background: #a11e01;
  animation: ldio-2trppgb1evd-2 1s cubic-bezier(0,0.5,0.5,1) infinite;
  animation-delay: -0.1s
}
.ldio-2trppgb1evd div:nth-child(3) {
  left: 135px;
  background: #d1d7d7;
  animation: ldio-2trppgb1evd-3 1s cubic-bezier(0,0.5,0.5,1) infinite;
  animation-delay: undefineds
}

.loadingio-spinner-pulse-13y8duj1w6nc {
  width: 200px;
  height: 200px;
  display: inline-block;
  overflow: hidden;
  background: #ffffff;
}
.ldio-2trppgb1evd {
  width: 100%;
  height: 100%;
  position: relative;
  transform: translateZ(0) scale(1);
  backface-visibility: hidden;
  transform-origin: 0 0; /* see note above */
}
</style>
<script src="./js/jquery.min.js"></script>
<script src="./js/jquery.easing.min.js"></script>
<script src="./js/popup.js"></script>
<script language="JavaScript">
function moveTo(id){
	$("#form-container").animate({
		marginLeft: "-" + ($("#" + id).position().left - 20) + "px"
	}, 400, "swing");

	$("#" + id).css("visibility", "visible").animate({
		opacity: 1
	}, 400, "easeInQuint").siblings().animate({
		opacity: 0
	}, 400, "easeOutQuint", function(){
		$(this).css("visibility", "hidden")
	});
}

function onClickForgotPassword(){
	moveTo("forgot-block");
}

function onClickBackToLogin(){
	moveTo("login-block");
}

function onChangeLanguage(select){
	$("body").append(
		$("<form></form>").attr({
			"id": "temp-form",
			"method": "POST",
			"action": "./?act=change_language&language=" + $(select).val()
		}).append(
			$("<input type='hidden'/>").attr({
				"name": "username",
				"value": $("#login-username").val()
			})
		).append(
			$("<input type='hidden'/>").attr({
				"name": "password",
				"value": $("#login-password").val()
			})
		)
	);

	$("#temp-form").submit();
}

function onSubmitLogin(){
	$(".submit-button").attr("disabled", true);
	$("#login-submit-button").css("color", "transparent");
	$("#login-submit-loader").show();

	var loginOK = false;

	$.ajax({
		url: "login_ajax.php?act=login",
		type: "POST",
		data: {
			username: $("#login-username").val(),
			password: $("#login-password").val(),
			language: $("#login-language").val(),
			remember: $("#login-rememberme").attr("checked") ? "1" : "0"
		},
		success: function(data, textStatus, jqXHR){
			if(data == "OK"){
				location.reload();
				loginOK = true;
			}
			else{
				var originalHeight = $("#login-result").height();
				var newHeight = $("#login-result").css("height", "auto").text(jqXHR.responseText).height();

				$("#login-result").css({
					"opacity": 0,
					"height": originalHeight + "px"
				}).animate({
					"opacity": 1,
					"height": newHeight + "px"
				}, "fast");
			}
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

			alert(jqXHR.responseText);
		},
		complete: function(){
			if(loginOK == false){
				$(".submit-button").attr("disabled", false);
				$("#login-submit-button").css("color", "");
				$("#login-submit-loader").hide();
			}
		}
	});

	return false;
}

function onSubmitForgotPassword(){
	$(".submit-button").attr("disabled", true);
	$("#forgot-submit-button").css("color", "transparent");
	$("#forgot-submit-loader").show();

	$.ajax({
		url: "login_ajax.php?act=forgot_password",
		type: "POST",
		data: {
			username: $("#forgot-username").val()
		},
		success: function(data, textStatus, jqXHR){
			var splitIndex = data.search("\\|\\|");
			var result = splitIndex >= 0 ? data.substring(0, splitIndex) : data;

			if(result == "OK"){
				popupConfirmWindow("<?=$lang['LOGIN']['POPUP']['CONFIRM_EMAIL_HAS_SENT'];?>", function(){
					$("<a></a>").attr({
						"href": "http://" + data.substring(splitIndex + 2),
						"target": "_blank"
					})[0].click();
				});

				// Clear
				$("#forgot-username").val("");
				result = "";
				//onClickBackToLogin();
			}

			var originalHeight = $("#forgot-result").height();
			var newHeight = $("#forgot-result").css("height", "auto").text(result).height();

			$("#forgot-result").css({
				"opacity": 0,
				"height": originalHeight + "px"
			}).animate({
				"opacity": 1,
				"height": newHeight + "px"
			}, "fast");
		},
		error: function(jqXHR, textStatus, errorThrown){
			if(jqXHR.status === 0 && textStatus != "timeout"){ return; }// not include timeout

			alert(jqXHR.responseText);
		},
		complete: function(){
			$(".submit-button").attr("disabled", false);
			$("#forgot-submit-button").css("color", "");
			$("#forgot-submit-loader").hide();
		}
	});

	return false;
}
<?php
function reset_password(){
	global $lang;

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);

		$sth = $dbh->prepare("SELECT confirm.UID, account.Username, account.Email FROM confirm, account WHERE confirm.AccountUID = account.UID AND AccountUID = ? AND ConfirmCode = ?;");
		$sth->execute(array($_GET["account_uid"], $_GET["confirm_code"]));

		$row = $sth->fetch(PDO::FETCH_ASSOC);
		$sth->closeCursor();

		if($row){
			$confirm_uid = $row["UID"];
			$username = $row["Username"];
			$email = $row["Email"];

			$sth = $dbh->prepare("DELETE FROM confirm WHERE UID = ?;");
			$sth->execute(array($confirm_uid));

			$password = random_string(10, "0123456789");

			$sth = $dbh->prepare("UPDATE account SET Password = ? WHERE UID = ?;");
			$sth->execute(array(strtoupper(hash('sha512', $password)), $_GET["account_uid"]));

			// Event log
			$sth = $dbh->prepare("USE " . $username . ";");
			$sth->execute();

			$sth = $dbh->prepare("INSERT INTO event_log(EventCode) VALUES(10101);");
			$sth->execute();

			$dbh = null;

			// Notify the PMMS Cloud runtime
			set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
			    // error was suppressed with the @-operator
			    if (0 === error_reporting()) {
			        return false;
			    }

			    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
			});

			try {
				$port = 1232;
				$address = "127.0.0.1";

				$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
				socket_connect($socket, $address, $port);

				$in = '<cmd type="userPasswordChange" username="' . $username . '" password="' . strtoupper(hash('sha512', $password)) . '"/>';
				socket_write($socket, $in);
				socket_shutdown($socket, 1);

				socket_close($socket);
			}
			catch (ErrorException $e) {}

			restore_error_handler();
			// Notify END

			// Send mail
			$mail = create_mail();
			$mail->addAddress($email);
			$mail->Subject = $lang['LOGIN']['EMAIL']['NEW_PASSWORD'];

			$mail->Body  =  $lang['EMAIL']['DEAR_USER'] . '<p>';
			$mail->Body .=  $lang['LOGIN']['EMAIL']['NEW_PASSWORD_AS_FOLLOWS'] . '<p>';
			$mail->Body .= '=============================================================<br>';
			$mail->Body .=  $password . "<br>";
			$mail->Body .= '=============================================================<p>';
			$mail->Body .=  $lang['EMAIL']['BEST_REGARDS'] . '<br>';
			$mail->Body .=  WEB_NAME;

			$mail->AltBody  =  $lang['EMAIL']['DEAR_USER'] . '\n\n';
			$mail->AltBody .=  $lang['LOGIN']['EMAIL']['NEW_PASSWORD_AS_FOLLOWS'] . '\n\n';
			$mail->AltBody .= '=============================================================\n';
			$mail->AltBody .=  $password. '\n';
			$mail->AltBody .= '=============================================================\n\n';
			$mail->AltBody .=  $lang['EMAIL']['BEST_REGARDS'] . '\n';
			$mail->AltBody .=  WEB_NAME;

			if(!$mail->send()) {
				throw new Exception("Send e-mail failed! " . $mail->ErrorInfo);
			}

			return true;
		}
		else{
			$dbh = null;
			return false;
		}
	}
	catch(PDOException $e) {
		throw new Exception("Database exception!" . $e->getMessage());
	}
}

if($_GET['act'] == "reset_password"){
?>
$(function() {
<?php
	try {
		if(reset_password()){
?>
    popupSuccessWindow("<?=$lang['LOGIN']['POPUP']['NEW_PASSWORD_HAS_BEEN_SENT'];?>");
	window.history.replaceState({}, '', '/');
<?php
		}
		else{
?>
    popupErrorWindow("<?=$lang['LOGIN']['POPUP']['PASSWORD_HAS_BEEN_RESET'];?>");
	window.history.replaceState({}, '', '/');
<?php
		}
	}
	catch (Exception $e) {
	    echo "alert('" . addslashes($e->getMessage()) . "');";
	}
?>
});
<?php
}
?>
</script>
</head>
<body>

<?php
	if (!extension_loaded('sockets')) {
?>
	<div id="mask-wrapper">
		<div id="mask-container">
			<div id="mask-bar"></div>
			<div id="mask-title"><img src="./image/ic_warning.png" style="vertical-align:middle;">&nbsp;<?=$lang['LOGIN']['MISSING_SOCKETS_EXTENSION'];?></div>
			<div id="mask-content"><?=$lang['LOGIN']['MISSING_SOCKETS_EXTENSION_DESCRIPTION'];?></div>
		</div>
	</div>
<?php
	}
?>
	<div class="form-wrapper">
		<div style="text-align:center; color: #035002;">
			<img src="./image/login logo.png" style="float:none;"><br>
			Monitoring Refrigeration v3.1.0 © MORETECH-UR 2022
		</div>

		<div id="form-container">
			<div class="form-block" id="login-block" style="left:20px;">
				<div class="form-title"><?=$lang['LOGIN']['LOGIN_TITLE'];?></div>

				<form method="POST" action="." onsubmit="return onSubmitLogin();">
					<div class="form-content">
						<div class="form-field-wrapper" style="margin-bottom:20px;">
							<div class="form-icon-wrapper">
								<div class="form-icon">
									<img src="./image/ic_username.png">
								</div>
							</div>
							<input type="text" id="login-username" class="form-field"<?=isset($input_username) ? " value=\"" . $input_username . "\"" : ""?> placeholder="<?=$lang['LOGIN']['USERNAME'];?>">
						</div>

						<div class="form-field-wrapper" style="margin-bottom:20px;">
							<div class="form-icon-wrapper">
								<div class="form-icon">
									<img src="./image/ic_password.png">
								</div>
							</div>
							<input type="password" id="login-password" class="form-field"<?=isset($input_password) ? " value=\"" . $input_password . "\"" : ""?> placeholder="<?=$lang['LOGIN']['PASSWORD'];?>">
						</div>

						<div class="form-field-wrapper" style="margin-bottom:20px;">
							<div class="form-icon-wrapper">
								<div class="form-icon">
									<img src="./image/ic_language.png">
								</div>
							</div>
							<select id="login-language" style="width:100%;" onChange="onChangeLanguage(this);">
								<option value="en"<?=$language == "en" ? " selected" : ""?>>English</option>
								<option value="tw"<?=$language == "tw" ? " selected" : ""?>>Traditional Chinese(繁體中文)</option>
								<option value="cn"<?=$language == "cn" ? " selected" : ""?>>Simplified Chinese(简体中文)</option>
							</select>
						</div>

						<div style="margin-bottom:5px;">
							<div style="float:left;"><input type="checkbox" id="login-rememberme" style="vertical-align:middle;"><label style="vertical-align:middle;" for="login-rememberme"><?=$lang['LOGIN']['REMEMBER_ME'];?></label></div>
							<div style="float:right;"><input type="checkbox" style="vertical-align:middle;visibility:hidden;"><a href="#" style="vertical-align:middle;" onClick="onClickForgotPassword();"><?=$lang['LOGIN']['FORGOT_PASSWORD'];?></a></div>
							<div style="clear:both;"></div>
						</div>

						<div style="margin-bottom:5px;position:relative;">
							<input type="submit" value="<?=$lang['SUBMIT'];?>" id="login-submit-button" class="submit-button" style="width:100%;">
							<div class="button-loader" id="login-submit-loader" style="display:none;">
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

						<div class="form-result" id="login-result"></div>

<?php
if(WEB_SIGNUP_PAGE == "1"){
?>
						<div style="text-align:center;padding-top:15px;">
							<a href="signup.php"><?=$lang['LOGIN']['SIGNUP_NOW'];?></a>
						</div>
<?php
}
?>
					</div>
				</form>
			</div>

			<div class="form-block" id="forgot-block" style="left:400px;visibility:hidden;opacity:0;">
				<div class="form-title"><?=$lang['LOGIN']['FORGOT_PASSWORD_TITLE'];?></div>

				<form method="POST" action="." onsubmit="return onSubmitForgotPassword();">
					<div class="form-content">
						<div class="form-field-wrapper" style="margin-bottom:20px;padding-left:0px;line-height:150%;">
							<?=$lang['LOGIN']['FORGOT_PASSWORD_DESCRIPTION'];?>
						</div>

						<div class="form-field-wrapper" style="margin-bottom:20px;">
							<div class="form-icon-wrapper">
								<div class="form-icon">
									<img src="./image/ic_username.png">
								</div>
							</div>
							<input type="text" id="forgot-username" class="form-field" placeholder="<?=$lang['LOGIN']['USERNAME'];?>">
						</div>

						<div style="margin-bottom:5px;position:relative;">
							<input type="submit" value="<?=$lang['SUBMIT'];?>" id="forgot-submit-button" class="submit-button" style="width:100%;">
							<div class="button-loader" id="forgot-submit-loader" style="display:none;">
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

						<div class="form-result" id="forgot-result"></div>

						<div style="text-align:center;padding-top:15px;">
							<a href="#" onClick="onClickBackToLogin();"><?=$lang['LOGIN']['BACKTOLOGIN'];?></a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="popup-background" id="popup-background">
		<div class="popup-wrapper" id="popup-wrapper">
			<div class="popup-container">
				<!--<div class="popup-title">Dialog</div>-->
				<div class="popup-content">
					<div class="popup-icon-wrapper"><span class="ics-popup warning" id="popup-icon"></span></div>
					<div id="popup-message"></div>
				</div>
				<div class="popup-footer">
					<input type="button" value="<?=$lang['OK'];?>" onClick="popupOK();"><span id="popup-cancel-button">&nbsp;&nbsp;<input type="button" class="gray" value="<?=$lang['CANCEL'];?>" onClick="popupCancel();"></span>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
