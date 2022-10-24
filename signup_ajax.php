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

if(function_exists($_GET["act"]) == true){
	$_GET["act"]();
}
else{
	trap("Invalid action name.");
}

exit;

function trap($outline, $detail = null){
	header("HTTP/1.0 500 Internal Server Error");
	echo $outline;
	if($detail != null){
		echo "\n" . $detail;
	}

	exit;
}

function email_validation(){
	global $lang, $language;

	if(WEB_SIGNUP_PAGE == "0"){
		trap("The sign up function has been disabled. Please contact the system administrator.");
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><signup/>');

	// Email
	if(empty($_POST["email"])){
   		$xml->addChild('email', $lang['EMAIL_VALIDATION']['AJAX']['EMPTY_FIELD']);
	}
	else if(mb_strlen($_POST["email"]) > 100){
   		$xml->addChild('email', $lang['EMAIL_VALIDATION']['AJAX']['LENGTH_LONGER_THEN_100']);
	}
	else if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
   		$xml->addChild('email', $lang['EMAIL_VALIDATION']['AJAX']['ILLEGAL_FORMAT']);
	}

	if(count($xml->children()) > 0){
		header('Content-type: text/xml');
		print($xml->asXML());

		return;
	}

	// Check complete, Notify runtime to send validation email
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

		$in = '<cmd type="sendValidationEmail" email="' . $_POST["email"] . '" language="' . $language . '"/>';
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

	$xml_out = new SimpleXMLElement($out);
	$result = $xml_out->attributes()->result;
	if($result == "ERROR"){
		$error_code = $xml_out->attributes()->error_code;
		if($error_code == "1"){
			$xml->addChild('email', $lang['EMAIL_VALIDATION']['AJAX']['EXIST_EMAIL']);
		}
	}
	else if($result == "OK"){
		$xml->addAttribute('result', "OK");
	}

	header('Content-type: text/xml');
	print($xml->asXML());
}

function registration(){
	global $lang, $language;

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><signup/>');

	// Username
	if(empty($_POST["username"])){
   		$xml->addChild('username', $lang['REGISTRATION']['AJAX']['EMPTY_FIELD']);
	}
	else if(preg_match("/[^a-z0-9_]/", $_POST["username"])){
   		$xml->addChild('username', $lang['REGISTRATION']['AJAX']['ONLY_ALLOW_LOWERCASE_ENGLISH_AND_NUMBER_ONLY']);
	}
	else if(strlen($_POST["username"]) > 16 || strlen($_POST["username"]) < 6){
   		$xml->addChild('username', $lang['REGISTRATION']['AJAX']['LENGTH_LONGER_THEN_16_OR_SHORTER_THEN_6']);
	}

	// Password
	if(empty($_POST["password"])){
   		$xml->addChild('password', $lang['REGISTRATION']['AJAX']['EMPTY_FIELD']);
	}
	else if(preg_match("/[^\x20-\x7E]/", $_POST["password"])){
   		$xml->addChild('password', $lang['REGISTRATION']['AJAX']['ILLEGAL_CHARACTER']);
	}
	else if(strlen($_POST["password"]) > 16 || strlen($_POST["password"]) < 6){
   		$xml->addChild('password', $lang['REGISTRATION']['AJAX']['LENGTH_LONGER_THEN_16_OR_SHORTER_THEN_6']);
	}

	// Confirm password
	if(empty($_POST["confirmPassword"])){
   		$xml->addChild('confirm-password', $lang['REGISTRATION']['AJAX']['EMPTY_FIELD']);
	}
	else if(preg_match("/[^\x20-\x7E]/",$_POST["confirmPassword"])){
   		$xml->addChild('confirm-password', $lang['REGISTRATION']['AJAX']['ILLEGAL_CHARACTER']);
	}
	else if(strlen($_POST["confirmPassword"]) > 16 || strlen($_POST["confirmPassword"]) < 6){
   		$xml->addChild('confirm-password', $lang['REGISTRATION']['AJAX']['LENGTH_LONGER_THEN_16_OR_SHORTER_THEN_6']);
	}
	else if($_POST["confirmPassword"] != $_POST["password"]){
   		$xml->addChild('confirm-password', $lang['REGISTRATION']['AJAX']['NOT_MATCH_PASSWORD']);
	}

	// Nickname
	if(empty($_POST["nickname"])){
   		$xml->addChild('nickname', $lang['REGISTRATION']['AJAX']['EMPTY_FIELD']);
	}
	else if(mb_strlen($_POST["nickname"]) > 50){
   		$xml->addChild('nickname', $lang['REGISTRATION']['AJAX']['LENGTH_LONGER_THEN_50']);
	}

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . DB_NAME : (
			DB_IS_MYSQL  ? "USE " . DB_NAME : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
		$sth->execute();

		// Email
		$sth = $dbh->prepare("SELECT Email FROM confirm WHERE ConfirmCode = ?");
		$sth->execute(array($_POST["code"]));

		$row = $sth->fetch(PDO::FETCH_ASSOC);
		$sth->closeCursor();

		if($row){
			$email = $row["Email"];

			$sth = $dbh->prepare("SELECT COUNT(*) AS Counter FROM account WHERE Email = ?");
			$sth->execute(array($email));

			$row = $sth->fetch(PDO::FETCH_ASSOC);
			$sth->closeCursor();

			if($row["Counter"] > 0){
				$xml->addChild('email', $lang['REGISTRATION']['AJAX']['EXIST_EMAIL']);
			}
		}
		else{
			$xml->addChild('email', $lang['REGISTRATION']['AJAX']['EXIST_EMAIL']);
		}

		// Company
		if(mb_strlen($_POST["company"]) > 45){
	   		$xml->addChild('company', $lang['REGISTRATION']['AJAX']['LENGTH_LONGER_THEN_50']);
		}

		// Country
		if(empty($_POST["country"])){
	   		$xml->addChild('country', $lang['REGISTRATION']['AJAX']['EMPTY_OPTION']);
		}

		if(count($xml->children()) > 0){
			$dbh = null;

			header('Content-type: text/xml');
			print($xml->asXML());
			return;
		}

		// Check complete, Notify runtime to create account
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

			$in = '<cmd type="createAccount" username="' . $_POST["username"] . '" password="' . $_POST["password"] . '" nickname="' . $_POST["nickname"] . '" email="' . $email . '" company="' . $_POST["company"] . '" country="' . $_POST["country"] . '" timezone="' . $_POST["timezone"] . '" language="' . $language . '"/>';
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

		$xml_out = new SimpleXMLElement($out);
		$result = $xml_out->attributes()->result;
		if($result == "ERROR"){
			$error_code = $xml_out->attributes()->error_code;
			if($error_code == "1"){
				$xml->addChild('username', $lang['REGISTRATION']['AJAX']['USERNAME_TAKEN']);
			}
		}
		else if($result == "OK"){
			$sth = $dbh->prepare("DELETE FROM confirm WHERE ConfirmCode = ?");
			$sth->execute(array($_POST["code"]));

			// Notify admin by email
			if(NOTIFICATION_ADMIN_EMAIL != "" && NOTIFICATION_SIGNUP == "1"){
				$mail = create_mail();
				$mail->addAddress(NOTIFICATION_ADMIN_EMAIL);
				$mail->Subject = $lang['LOGIN']['EMAIL']['EVENT_NOTIFICATION'];

				$mail->Body  =  $lang['EMAIL']['DEAR_ADMIN'] . '<p>';
				$mail->Body .=  str_replace("%username%", $_POST["username"], $lang['SIGNUP']['EMAIL']['USER_SIGN_UP_ACCOUNT']) . "<p>";
				$mail->Body .=  $lang['EMAIL']['DISABLE_ADMIN_NOTIFICATION'] . '<p>';
				$mail->Body .=  $lang['EMAIL']['BEST_REGARDS'] . '<br>';
				$mail->Body .=  WEB_NAME;

				$mail->AltBody  =  $lang['EMAIL']['DEAR_ADMIN'] . '\n\n';
				$mail->AltBody .=  str_replace("%username%", $_POST["username"], $lang['SIGNUP']['EMAIL']['USER_SIGN_UP_ACCOUNT']) . '\n\n';
				$mail->AltBody .=  $lang['EMAIL']['DISABLE_ADMIN_NOTIFICATION'] . '\n\n';
				$mail->AltBody .=  $lang['EMAIL']['BEST_REGARDS'] . '\n';
				$mail->AltBody .=  WEB_NAME;

				$send_ok = $mail->send();

				// System event log??
				if($send_ok == true){
				}
				else{
				}
			}

			$xml->addAttribute('result', "OK");
		}

		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}

	header('Content-type: text/xml');
	print($xml->asXML());
}
?>
