<?php
require_once("include.php");
$login = session_check();

// Set language
if(isset($_POST["language"])){
	setcookie("language", $_POST["language"], time() + 60 * 60 * 24 * 365);
}

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

function login(){
	$result = login_check();

	if(!isset($_POST["no_login_page"])){
		echo $result;
	}
	else{
		if($result == "OK"){
//			header("Refresh: 0; url=.");
			header('Location: .');
		}
		else{
			echo $result;
		}
	}
}

function login_check(){
	global $lang, $language;

	if(!empty($_POST["username"]) && !empty($_POST["password"])){
		try {
			$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
			$sth = $dbh->prepare(
				DB_IS_MSSQL  ? "USE " . DB_NAME : (
				DB_IS_MYSQL  ? "USE " . DB_NAME : (
				DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
			$sth->execute();

			$sth = $dbh->prepare(
				DB_IS_MSSQL  ? "SELECT *, getdate() AS NowDate FROM account WHERE Username = ? COLLATE SQL_Latin1_General_CP1_CS_AS" : (
				DB_IS_MYSQL  ? "SELECT *, CURRENT_TIMESTAMP AS NowDate FROM account WHERE Username = CONVERT(? USING utf8mb4) COLLATE utf8mb4_bin" : (
				DB_IS_ORACLE ? "SELECT account.*, LOCALTIMESTAMP AS NowDate FROM account WHERE Username = ?" : null)));
			$sth->execute(array($_POST["username"]));

			$row = $sth->fetch(PDO::FETCH_ASSOC);
			$sth->closeCursor();

			if($row){
				if($row["Password"] == strtoupper(hash('sha512', $_POST["password"]))){// Password match
					if($row["Status"] == true){// Actived
						if($row["ExpirationDate"] == NULL || $row["ExpirationDate"] > $row["NowDate"]){// Not expired
							// Update user language(use $_POST['langugae'] or $language)
							$sth = $dbh->prepare(
								DB_IS_MSSQL  ? "UPDATE account SET Language = ?, LoginRetryCounter = 0 WHERE UID = ?" : (
								DB_IS_MYSQL  ? "UPDATE account SET Language = ?, LoginRetryCounter = 0 WHERE UID = ?" : (
								DB_IS_ORACLE ? "UPDATE account SET Language = ?, LoginRetryCounter = 0 WHERE \"UID\" = ?" : null)));
							$sth->execute(array($language, $row["UID"]));

							// Check RegistrationDate is NULL or not
							$sth = $dbh->prepare(
								DB_IS_MSSQL  ? "SELECT RegistrationDate FROM account WHERE UID = ?" : (
								DB_IS_MYSQL  ? "SELECT RegistrationDate FROM account WHERE UID = ?" : (
								DB_IS_ORACLE ? "SELECT RegistrationDate FROM account WHERE \"UID\" = ?" : null)));
							$sth->execute(array($row["UID"]));

							$row2 = $sth->fetch(PDO::FETCH_ASSOC);
							if($row2["RegistrationDate"] == NULL){
								$sth = $dbh->prepare(
									DB_IS_MSSQL  ? "UPDATE account SET RegistrationDate = getdate(), ExpirationDate = DATEADD(day, 31, getdate()) WHERE UID = ?" : (
									DB_IS_MYSQL  ? "UPDATE account SET RegistrationDate = CURRENT_TIMESTAMP, ExpirationDate = DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 31 DAY) WHERE UID = ?" : (
									DB_IS_ORACLE ? "UPDATE account SET RegistrationDate = LOCALTIMESTAMP, ExpirationDate = LOCALTIMESTAMP + 31 WHERE \"UID\" = ?" : null)));
								$sth->execute(array($row["UID"]));
							}
							$sth->closeCursor();

							// Event log
							$sth = $dbh->prepare(
								DB_IS_MSSQL  ? "USE " . $row["Username"] : (
								DB_IS_MYSQL  ? "USE " . $row["Username"] : (
								DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $row["Username"] : null)));
							$sth->execute();

							$sth = $dbh->prepare("INSERT INTO event_log(EventCode, Parameters) VALUES(?, ?)");
							$sth->execute(array("10001", get_user_ip()));

							$_SESSION["login"] = true;
							$_SESSION["account_uid"] = $row["UID"];
							$_SESSION["username"] = $row["Username"];
							$_SESSION["password"] = $_POST["password"];// plain password
							$_SESSION["remember_me"] = $_POST["remember"] == "1" ? true : false;

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

								$in = '<cmd type="userLogin" username="' . $_SESSION["username"] . '" language="' . $language . '"/>';
								socket_write($socket, $in);
								socket_shutdown($socket, 1);

								socket_close($socket);
							}
							catch (ErrorException $e) {}

							restore_error_handler();
							// Notify END

							return "OK";
						}
						else{
							return $lang['LOGIN']['AJAX']['ACCOUNT_EXPIRE'];
						}
					}
					else{
						return $lang['LOGIN']['AJAX']['ACCOUNT_NOT_ACTIVE'];
					}
				}
				else{// Password not match
					// Event log
					$sth = $dbh->prepare(
						DB_IS_MSSQL  ? "USE " . $row["Username"] : (
						DB_IS_MYSQL  ? "USE " . $row["Username"] : (
						DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $row["Username"] : null)));
					$sth->execute();

					$sth = $dbh->prepare("INSERT INTO event_log(EventCode, Parameters) VALUES(?, ?)");
					$sth->execute(array("30002", get_user_ip()));

					$sth = $dbh->prepare(
						DB_IS_MSSQL  ? "USE " . DB_NAME : (
						DB_IS_MYSQL  ? "USE " . DB_NAME : (
						DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
					$sth->execute();

					if($row["LoginRetryCounter"] + 1 >= 10){// Input error password over 10 times
						$sth = $dbh->prepare(
							DB_IS_MSSQL  ? "UPDATE account SET LoginRetryCounter = 0 WHERE UID = ?" : (
							DB_IS_MYSQL  ? "UPDATE account SET LoginRetryCounter = 0 WHERE UID = ?" : (
							DB_IS_ORACLE ? "UPDATE account SET LoginRetryCounter = 0 WHERE \"UID\" = ?" : null)));
						$sth->execute(array($row["UID"]));

						// Send email if notification is set
						if(($row["Notification"] & 16) == 16){
							$mail = create_mail();
							$mail->addAddress($row["Email"]);
							$mail->Subject = $lang['LOGIN']['EMAIL']['EVENT_NOTIFICATION'];

							$mail->Body  =  $lang['EMAIL']['DEAR_USER'] . '<p>';
							$mail->Body .=  $lang['LOGIN']['EMAIL']['ENTER_INCORRECT_PASSWORD_OVER_TEN_TIMES'] . "<p>";
							$mail->Body .=  $lang['EMAIL']['DISABLE_USER_NOTIFICATION'] . '<p>';
							$mail->Body .=  $lang['EMAIL']['BEST_REGARDS'] . '<br>';
							$mail->Body .=  WEB_NAME;

							$mail->AltBody  =  $lang['EMAIL']['DEAR_USER'] . '\n\n';
							$mail->AltBody .=  $lang['LOGIN']['EMAIL']['ENTER_INCORRECT_PASSWORD_OVER_TEN_TIMES'] . '\n\n';
							$mail->AltBody .=  $lang['EMAIL']['DISABLE_USER_NOTIFICATION'] . '\n\n';
							$mail->AltBody .=  $lang['EMAIL']['BEST_REGARDS'] . '\n';
							$mail->AltBody .=  WEB_NAME;

							$send_ok = $mail->send();
							
							// Event log
							$sth = $dbh->prepare(
								DB_IS_MSSQL  ? "USE " . $row["Username"] : (
								DB_IS_MYSQL  ? "USE " . $row["Username"] : (
								DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $row["Username"] : null)));
							$sth->execute();

							if($send_ok == true){
								$sth = $dbh->prepare("INSERT INTO event_log(EventCode) VALUES(10108)");
								$sth->execute();
							}
							else{
								$sth = $dbh->prepare("INSERT INTO event_log(EventCode) VALUES(?)");
								$sth->execute(array("50006", get_user_ip()));
							}
						}
					}
					else{
						$sth = $dbh->prepare(
							DB_IS_MSSQL  ? "UPDATE account SET LoginRetryCounter = LoginRetryCounter + 1 WHERE UID = ?" : (
							DB_IS_MYSQL  ? "UPDATE account SET LoginRetryCounter = LoginRetryCounter + 1 WHERE UID = ?" : (
							DB_IS_ORACLE ? "UPDATE account SET LoginRetryCounter = LoginRetryCounter + 1 WHERE \"UID\" = ?" : null)));
						$sth->execute(array($row["UID"]));
					}

					return $lang['LOGIN']['AJAX']['INVALID_PASSWORD'];
				}
			}
			else{
				return $lang['LOGIN']['AJAX']['INVALID_USERNAME'];
			}

			$dbh = null;
		}
		catch(PDOException $e) {
			trap("Database exception!", $e->getMessage());
		}
	}
	else{
		return $lang['LOGIN']['AJAX']['EMPTY_USERNAME_OR_PASSWORD'];
	}
}

function forgot_password(){
	global $lang;

	if(!empty($_POST["username"])){
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . DB_NAME : (
			DB_IS_MYSQL  ? "USE " . DB_NAME : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
		$sth->execute();

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "SELECT UID, Email FROM account WHERE Username = ?" : (
			DB_IS_MYSQL  ? "SELECT UID, Email FROM account WHERE Username = ?" : (
			DB_IS_ORACLE ? "SELECT \"UID\", Email FROM account WHERE Username = ?" : null)));
		$sth->execute(array($_POST["username"]));

		$row = $sth->fetch(PDO::FETCH_ASSOC);
		$sth->closeCursor();
		$account_uid = $row["UID"];
		$email = $row["Email"];

		if(!isset($email)){
			echo $lang['LOGIN']['AJAX']['INVALID_USERNAME'];
		}
		else{
			$confirm_code = random_string(10);

			$sth = $dbh->prepare("INSERT INTO confirm(AccountUID, ConfirmCode) VALUES(?, ?)");
			$sth->execute(array($account_uid, $confirm_code));

			// Send mail
			$mail = create_mail();
			$mail->addAddress($email);
			$mail->Subject = $lang['LOGIN']['EMAIL']['CONFIRM_EMAIL'];

			$mail->Body  =  $lang['EMAIL']['DEAR_USER'] . '<p>';
			$mail->Body .=  $lang['LOGIN']['EMAIL']['CLICK_LINK_TO_RESET_PASSWORD'] . '<p>';
			$mail->Body .= '=============================================================<br>';
			$mail->Body .= '<a href="http://' . WEB_HOST . '/?act=reset_password&account_uid=' . $account_uid . '&confirm_code=' . $confirm_code . '">http://' . WEB_HOST . '/?act=reset_password&account_uid=' . $account_uid . '&confirm_code=' . $confirm_code . '</a><br>';
			$mail->Body .= '=============================================================<p>';
			$mail->Body .=  $lang['EMAIL']['BEST_REGARDS'] . '<br>';
			$mail->Body .=  WEB_NAME;

			$mail->AltBody  =  $lang['EMAIL']['DEAR_USER'] . '\n\n';
			$mail->AltBody .=  $lang['LOGIN']['EMAIL']['CLICK_LINK_TO_RESET_PASSWORD'] . '\n\n';
			$mail->AltBody .= '=============================================================\n';
			$mail->AltBody .= 'http://' . WEB_HOST . '/?act=reset_password&account_uid=' . $account_uid . '&confirm_code=' . $confirm_code . '\n';
			$mail->AltBody .= '=============================================================\n\n';
			$mail->AltBody .=  $lang['EMAIL']['BEST_REGARDS'] . '\n';
			$mail->AltBody .=  WEB_NAME;

			if(!$mail->send()) {
				trap("Send e-mail failed!", $mail->ErrorInfo);
			}

			preg_match("/^.+?@(.+)$/", $email, $match_array);
			echo "OK||" . $match_array[1];
			exit;
		}

		$dbh = null;
	}
	else{
		echo $lang['LOGIN']['AJAX']['EMPTY_USERNAME'];
	}
}
?>
