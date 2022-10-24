<?php
require_once("include.php");

// Load language file
if(isset($_COOKIE["language"])){
	$language = $_COOKIE["language"];
}
else{
	$language = "en";
}
require_once("lang." . $language . ".php");

if(!isset($_GET["step"])){
	$_GET["step"] = "1";//default step
}

function step1_header(){
	global $lang;
?>
<script language="JavaScript">
function onSubmitRegistration(form){
	var $button = $(form).find("input[type='submit']");
	var $loader = $button.parent().find(".button-loader");

	$button.attr("disabled", true).css("color", "transparent");
	$loader.show();

	$.ajax({
		url: "signup_ajax.php?act=email_validation",
		type: "POST",
		data: {
			email: $("#email").val()
		},
		dataType: "xml",
		success: function(data, textStatus, jqXHR){
			var $signup = $(data).find("signup");

			$(form).find("input, select").removeClass("error");
			$(form).find("tr.form-error").remove();

			if($signup.attr("result") == "OK"){
				$("#form").hide();

				var $message = $("#message").show();
				$message.find("div.signup-block-message").text("<?=$lang['EMAIL_VALIDATION']['EMAIL_HAS_SENT'];?>".replace("%email%", $("#email").val()));
				$message.find("input[type='button']").bind("click", function(){
					var emailAddress = $("#email").val();

					$("<a></a>").attr({
						"href": "http://" + emailAddress.substring(emailAddress.indexOf("@") + 1),
						"target": "_blank"
					}).css("display", "none").appendTo("body")[0].click();
				});
			}
			else{
				var showError = function(fieldID/*equal to xml tag name*/){
					var $xml = $signup.find(fieldID);
					if($xml.length > 0){
						$("#" + fieldID).addClass("error").closest("tr").after(
							$("<tr></tr>").addClass("form-error").append(
								$("<td></td>")
							).append(
								$("<td></td>").addClass("signup-block-form-error").text($xml.text())
							)
						);
					}
				};

				showError("email");

				popupErrorWindow("<?=$lang['EMAIL_VALIDATION']['POPUP']['VALIDATION_FAILED'];?>");
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
</script>
<?php
}

function step1_body(){
	global $lang, $language;
?>
<div id="form">
	<div class="signup-block-message">
		<?=$lang['EMAIL_VALIDATION']['ENTER_EMAIL_ADDRESS'];?>
	</div>

	<form method="POST" action="." onsubmit="return onSubmitRegistration(this);" id="form" style="margin-top:30px;">
		<table align="center">
			<tr>
				<td align="right">*<?=$lang['EMAIL_VALIDATION']['EMAIL_ADDRESS'];?></td>
				<td><input type="text" id="email" style="width:300px;"></td>
			</tr>
			<tr>
				<td colSpan="2" align="center">
					<div style="position:relative;display:inline-block;margin-top:30px;">
						<input type="submit" value="<?=$lang['EMAIL_VALIDATION']['VERIFY'];?>">
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

<div style="display:none;" id="message">
	<div class="signup-block-message"></div>
	<div style="text-align:center;">
		<input type="button" value="<?=$lang['EMAIL_VALIDATION']['CHECK_EMAIL'];?>" style="margin-top:30px;">
	</div>
</div>
<?php
}

function step2_header(){
	global $lang;
?>
<script language="JavaScript">
function onSubmitRegistration(form){
	var $button = $(form).find("input[type='submit']");
	var $loader = $button.parent().find(".button-loader");

	$button.attr("disabled", true).css("color", "transparent");
	$loader.show();

	$.ajax({
		url: "signup_ajax.php?act=registration",
		type: "POST",
		data: {
			code: $("#code").val(),
			username: $("#username").val(),
			password: $("#password").val(),
			confirmPassword: $("#confirm-password").val(),
			nickname: $("#nickname").val(),
			email: $("#email").val(),
			company: $("#company").val(),
			country: $("#country").val(),
			timezone: (new Date()).getTimezoneOffset()
		},
		dataType: "xml",
		success: function(data, textStatus, jqXHR){
			var $signup = $(data).find("signup");

			if($signup.attr("result") == "OK"){
				location = "./signup.php?step=3";
			}
			else{
				$(form).find("input, select").removeClass("error");
				$(form).find("tr.form-error").remove();

				if($signup.find("email").length > 0){
				    popupErrorWindow("<?=$lang['REGISTRATION']['POPUP']['EXIST_EMAIL'];?>", function(){
						location.reload();
					});

					window.history.replaceState({}, '', '/');
				}
				else if($signup.find("limit").length > 0){
				    popupErrorWindow("<?=$lang['REGISTRATION']['POPUP']['ACCOUNT_AMOUNT_REACH_MAXIMUM'];?>");
				}
				else{
					var showError = function(fieldID/*equal to xml tag name*/){
						var $xml = $signup.find(fieldID);
						if($xml.length > 0){
							$("#" + fieldID).addClass("error").closest("tr").after(
								$("<tr></tr>").addClass("form-error").append(
									$("<td></td>")
								).append(
									$("<td></td>").addClass("signup-block-form-error").text($xml.text())
								)
							);
						}
					};

					showError("username");
					showError("password");
					showError("confirm-password");
					showError("nickname");
					showError("company");
					showError("country");

					popupErrorWindow("<?=$lang['REGISTRATION']['POPUP']['REGISTRATION_FAILED'];?>");
				}
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
</script>
<?php
}

function step2_body(){
	global $lang;

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . DB_NAME : (
			DB_IS_MYSQL  ? "USE " . DB_NAME : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
		$sth->execute();

		$sth = $dbh->prepare("SELECT Email FROM confirm WHERE ConfirmCode = ?");
		$sth->execute(array($_GET['code']));

		$row = $sth->fetch(PDO::FETCH_ASSOC);
		$sth->closeCursor();

		if($row){
			$email = $row["Email"];

			$sth = $dbh->prepare("SELECT COUNT(*) AS Counter FROM account WHERE Email = ?");
			$sth->execute(array($email));

			$row = $sth->fetch(PDO::FETCH_ASSOC);
			$sth->closeCursor();

			if($row["Counter"] > 0){
?>
<script language="JavaScript">
$(function() {
    popupErrorWindow("<?=$lang['REGISTRATION']['POPUP']['EXIST_EMAIL'];?>", function(){
		location.reload();
	});

	window.history.replaceState({}, '', '/');
});
</script>
<?php
			}
		}
		else{
?>
<script language="JavaScript">
$(function() {
    popupErrorWindow("<?=$lang['REGISTRATION']['POPUP']['VALIDATION_URL_ERROR'];?>", function(){
		location.reload();
	});

	window.history.replaceState({}, '', '/');
});
</script>
<?php
		}

		$dbh = null;
	}
	catch(PDOException $e) {
		throw new Exception("Database exception!" . $e->getMessage());
	}
?>
<form method="POST" action="." onsubmit="return onSubmitRegistration(this);">
	<input type="hidden" id="code" value="<?=$_GET['code']?>" disabled>
	<table align="center">
		<colgroup>
			<col width="130">
			<col width="300">
		</colgroup>
		<tr>
			<td align="right">*<?=$lang['REGISTRATION']['USERNAME'];?></td>
			<td><input type="text" id="username" style="width:300px;"></td>
		</tr>
		<tr><td class="signup-block-form-padding" colSpan="2"></td></tr>
		<tr>
			<td align="right">*<?=$lang['REGISTRATION']['PASSWORD'];?></td>
			<td><input type="password" id="password" style="width:300px;"></td>
		</tr>
		<tr><td class="signup-block-form-padding" colSpan="2"></td></tr>
		<tr>
			<td align="right">*<?=$lang['REGISTRATION']['RETYPE_PASSWORD'];?></td>
			<td><input type="password" id="confirm-password" style="width:300px;"></td>
		</tr>
		<tr><td class="signup-block-form-padding" colSpan="2"></td></tr>
		<tr>
			<td align="right">*<?=$lang['REGISTRATION']['NICKNAME'];?></td>
			<td><input type="text" id="nickname" style="width:300px;"></td>
		</tr>
		<tr><td class="signup-block-form-padding" colSpan="2"></td></tr>
		<tr>
			<td align="right"><?=$lang['REGISTRATION']['EMAIL_ADDRESS'];?></td>
			<td><input type="text" id="email" style="width:300px;background-color:#f5f5f5;" value="<?=$email?>" disabled></td>
		</tr>
		<tr><td class="signup-block-form-padding" colSpan="2"></td></tr>
		<tr>
			<td align="right"><?=$lang['REGISTRATION']['COMPANY'];?></td>
			<td><input type="text" id="company" style="width:300px;"></td>
		</tr>
		<tr><td class="signup-block-form-padding" colSpan="2"></td></tr>
		<tr>
			<td align="right">*<?=$lang['REGISTRATION']['COUNTRY'];?></td>
			<td>
				<select id="country" style="width:100%;">
		            <option value="">-</option>
<?php
	foreach ($lang['COUNTRY'] as $key => $value) {
		echo "<option value='" . $key . "'>" . $value . "</option>";
	}
?>
				</select>
			</td>
		</tr>
		<tr>
			<td colSpan="2" align="center">
				<div style="position:relative;display:inline-block;margin-top:30px;">
					<input type="submit" value="<?=$lang['SUBMIT'];?>">
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
<?php
}

function step3_body(){
	global $lang;
?>
<div class="signup-block-message">
	<?=$lang['DONE']['ACTIVE_SUCCESS'];?>
</div>
<div style="text-align:center;">
	<input type="button" value="<?=$lang['DONE']['LOGIN'];?>" style="margin-top:30px;" onClick="location='/';">
</div>
<?php
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title><?=WEB_NAME?></title>
<link rel="stylesheet" type="text/css" href="./css/general.css">
<link rel="stylesheet" type="text/css" href="./css/signup.css">
<link rel="stylesheet" type="text/css" href="./css/popup.css">
<script src="./js/jquery.min.js"></script>
<script src="./js/popup.js"></script>
<?php
$function_name = "step" . $_GET["step"] . "_header";
if(function_exists($function_name) == true){
	$error_message = null;
	try {
		ob_start();
		$function_name();
		ob_end_flush();
	}
	catch (Exception $e) {
		ob_end_clean();
		$error_message = $e->getMessage();
	}
}
?>
</head>
<body>
	<div class="wrapper">
		<div class="signup-block">
			<div class="signup-block-title"><?=$lang['SIGNUP']['SIGNUP_TITLE'];?></div>

			<div class="signup-block-step">
				<div class="signup-block-step-path-container">
					<div class="signup-block-step-path"></div>
				</div>

				<div class="signup-block-step-container">
					<div class="signup-block-step-wrapper">
						<div class="signup-block-step-box">
							<div class="signup-block-step-circle<?php echo $_GET['step'] >= 1 ? ' finish' : '';?>">1</div>
							<div class="signup-block-step-title"><?=$lang['SIGNUP']['EMAIL_VALIDATION'];?></div>
						</div>
					</div>

					<div class="signup-block-step-wrapper">
						<div class="signup-block-step-box" style="margin:auto;">
							<div class="signup-block-step-circle<?php echo $_GET['step'] >= 2 ? ' finish' : '';?>">2</div>
							<div class="signup-block-step-title"><?=$lang['SIGNUP']['REGISTRATION'];?></div>
						</div>
					</div>

					<div class="signup-block-step-wrapper">
						<div class="signup-block-step-box" style="float:right;">
							<div class="signup-block-step-circle<?php echo $_GET['step'] >= 3 ? ' finish' : '';?>">3</div>
							<div class="signup-block-step-title"><?=$lang['SIGNUP']['DONE'];?></div>
						</div>
					</div>
					<div style="clear:both;"></div>
				</div>
			</div>
<?php
$function_name = "step" . $_GET["step"] . "_body";
if(function_exists($function_name) == true){
	if($error_message == null){
		try {
			ob_start();
			$function_name();
			ob_end_flush();
		}
		catch (Exception $e) {
			ob_end_clean();
		    echo $e->getMessage();
		}
	}
	else{
		echo $error_message;
	}
}
?>
		</div>

		<div style="text-align:center;margin-top:20px;">© ICP DAS Co., Ltd. All Rights Reserved</div>
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