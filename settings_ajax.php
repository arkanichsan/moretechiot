<?php
require_once("include.php");
$login = session_check();

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

function set_password(){
	global $login, $lang;
	if($login == false){
		trap("Permission error!");
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><password/>');

	if(empty($_POST["current_password"])){
   		$xml->addChild('current_password', $lang['SETTING']['AJAX']['EMPTY_FIELD']);
	}
	else if(preg_match("/[^\x20-\x7E]/",$_POST["current_password"])){
   		$xml->addChild('current_password', $lang['SETTING']['AJAX']['ILLEGAL_CHARACTER']);
	}
	else if(strlen($_POST["current_password"]) > 16 || strlen($_POST["current_password"]) < 6){
   		$xml->addChild('current_password', $lang['SETTING']['AJAX']['LENGTH_LONGER_THEN_16_OR_SHORTER_THEN_6']);
	}
	else if($_POST["current_password"] != $_SESSION["password"]){
   		$xml->addChild('current_password', $lang['SETTING']['AJAX']['INVALID_PASSWORD']);
	}

	if(empty($_POST["new_password"])){
   		$xml->addChild('new_password', $lang['SETTING']['AJAX']['EMPTY_FIELD']);
	}
	else if(preg_match("/[^\x20-\x7E]/", $_POST["new_password"])){
   		$xml->addChild('new_password', $lang['SETTING']['AJAX']['ILLEGAL_CHARACTER']);
	}
	else if(strlen($_POST["new_password"]) > 16 || strlen($_POST["new_password"]) < 6){
   		$xml->addChild('new_password', $lang['SETTING']['AJAX']['LENGTH_LONGER_THEN_16_OR_SHORTER_THEN_6']);
	}

	if(empty($_POST["confirm_password"])){
   		$xml->addChild('confirm_password', $lang['SETTING']['AJAX']['EMPTY_FIELD']);
	}
	else if(preg_match("/[^\x20-\x7E]/",$_POST["confirm_password"])){
   		$xml->addChild('confirm_password', $lang['SETTING']['AJAX']['ILLEGAL_CHARACTER']);
	}
	else if(strlen($_POST["confirm_password"]) > 16 || strlen($_POST["confirm_password"]) < 6){
   		$xml->addChild('confirm_password', $lang['SETTING']['AJAX']['LENGTH_LONGER_THEN_16_OR_SHORTER_THEN_6']);
	}
	else if($_POST["confirm_password"] != $_POST["new_password"]){
   		$xml->addChild('confirm_password', $lang['SETTING']['AJAX']['NOT_MATCH_NEW_PASSWORD']);
	}

	if(count($xml->children()) > 0){
		header('Content-type: text/xml');
		print($xml->asXML());
		return;
	}

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . DB_NAME : (
			DB_IS_MYSQL  ? "USE " . DB_NAME : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
		$sth->execute();

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "UPDATE account SET Password = ? WHERE UID = ?" : (
			DB_IS_MYSQL  ? "UPDATE account SET Password = ? WHERE UID = ?" : (
			DB_IS_ORACLE ? "UPDATE account SET Password = ? WHERE \"UID\" = ?" : null)));
		$sth->execute(array(strtoupper(hash('sha512', $_POST["new_password"])), $_SESSION["account_uid"]));

		if(DB_IS_MSSQL){
			$sth = $dbh->prepare("ALTER LOGIN " . $_SESSION["username"] . " WITH PASSWORD = '" . $_POST["new_password"] . "'");
			$sth->execute();
		}
		else if(DB_IS_MYSQL){
			$sth = $dbh->prepare("ALTER USER " . $_SESSION["username"] . " IDENTIFIED BY '" . $_POST["new_password"] . "'");
			$sth->execute();

			$sth = $dbh->prepare("FLUSH PRIVILEGES");
			$sth->execute();
		}
		else if(DB_IS_ORACLE){
			$sth = $dbh->prepare("ALTER USER " . $_SESSION["username"] . " IDENTIFIED BY \"" . $_POST["new_password"] . "\"");
			$sth->execute();
		}

		// Event log
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_MYSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $_SESSION["username"] : null)));
		$sth->execute();

		$sth = $dbh->prepare("INSERT INTO event_log(EventCode) VALUES(10101)");
		$sth->execute();

		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}

	$_SESSION["password"] = $_POST["new_password"];
	$xml->addAttribute('result', "OK");
	header('Content-type: text/xml');
	print($xml->asXML());

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

		$in = '<cmd type="userPasswordChange" username="' . $_SESSION["username"] . '" password="' . strtoupper(hash('sha512', $_POST["new_password"])) . '"/>';
		socket_write($socket, $in);
		socket_shutdown($socket, 1);

		socket_close($socket);
	}
	catch (ErrorException $e) {}

	restore_error_handler();
	// Notify END
}

function set_information(){
	global $login, $lang;
	if($login == false){
		trap("Permission error!");
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><info/>');

	if(mb_strlen($_POST["nickname"]) > 50){
   		$xml->addChild('nickname', $lang['SETTING']['AJAX']['LENGTH_LONGER_THEN_50']);
	}

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . DB_NAME : (
			DB_IS_MYSQL  ? "USE " . DB_NAME : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
		$sth->execute();

		if(empty($_POST["email"])){
	   		$xml->addChild('email', $lang['SETTING']['AJAX']['EMPTY_FIELD']);
		}
		else if(mb_strlen($_POST["email"]) > 100){
	   		$xml->addChild('email', $lang['SETTING']['AJAX']['LENGTH_LONGER_THEN_100']);
		}
		else if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
	   		$xml->addChild('email', $lang['SETTING']['AJAX']['ILLEGAL_FORMAT']);
		}
		else{
			$sth = $dbh->prepare(
				DB_IS_MSSQL  ? "SELECT COUNT(*) AS Counter FROM account WHERE Email = ? AND UID != ?" : (
				DB_IS_MYSQL  ? "SELECT COUNT(*) AS Counter FROM account WHERE Email = ? AND UID != ?" : (
				DB_IS_ORACLE ? "SELECT COUNT(*) AS Counter FROM account WHERE Email = ? AND \"UID\" != ?" : null)));
			$sth->execute(array($_POST["email"], $_SESSION["account_uid"]));

			$row = $sth->fetch(PDO::FETCH_ASSOC);
			$sth->closeCursor();

			if($row["Counter"] > 0){
	   			$xml->addChild('email', $lang['SETTING']['AJAX']['EXIST_EMAIL']);
			}
		}

		if(mb_strlen($_POST["company"]) > 50){
	   		$xml->addChild('company', $lang['SETTING']['AJAX']['LENGTH_LONGER_THEN_50']);
		}

		if(count($xml->children()) > 0){
			$dbh = null;

			header('Content-type: text/xml');
			print($xml->asXML());
			return;
		}

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "UPDATE account SET Nickname = ?, Company = ?, CountryUID = ?, TimezoneOffset = ? WHERE UID = ?" : (
			DB_IS_MYSQL  ? "UPDATE account SET Nickname = ?, Company = ?, CountryUID = ?, TimezoneOffset = ? WHERE UID = ?" : (
			DB_IS_ORACLE ? "UPDATE account SET Nickname = ?, Company = ?, CountryUID = ?, TimezoneOffset = ? WHERE \"UID\" = ?" : null)));
		$sth->execute(array($_POST["nickname"], $_POST["company"], $_POST["country"], $_POST["timezone"], $_SESSION["account_uid"]));

		// Email
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "SELECT Email FROM account WHERE UID = ?" : (
			DB_IS_MYSQL  ? "SELECT Email FROM account WHERE UID = ?" : (
			DB_IS_ORACLE ? "SELECT Email FROM account WHERE \"UID\" = ?" : null)));
		$sth->execute(array($_SESSION["account_uid"]));

		$row = $sth->fetch(PDO::FETCH_ASSOC);
		$sth->closeCursor();

		$email = $row["Email"];

		if($email != $_POST["email"]){// Email address change
			$confirm_code = random_string(10);

			$sth = $dbh->prepare("INSERT INTO confirm(AccountUID, ConfirmCode, Email) VALUES(?, ?, ?);");
			$sth->execute(array($_SESSION["account_uid"], $confirm_code, $_POST["email"]));

			// Send mail
			$mail = create_mail();
			$mail->addAddress($_POST["email"]);
			$mail->Subject = $lang['SETTING']['EMAIL']['SUBJECT'];

			$mail->Body  =  $lang['EMAIL']['DEAR_USER'] . '<p>';
			$mail->Body .=  $lang['SETTING']['EMAIL']['CONTENT'] . '<p>';
			$mail->Body .= '=============================================================<br>';
			$mail->Body .= '<a href="http://' . WEB_HOST . '/?act=settings&job=confirm_email&uid=' . $_SESSION["account_uid"] . '&confirm_code=' . $confirm_code . '">http://' . WEB_HOST . '/?act=settings&job=confirm_email&uid=' . $_SESSION["account_uid"] . '&confirm_code=' . $confirm_code . '</a><br>';
			$mail->Body .= '=============================================================<p>';
			$mail->Body .=  $lang['EMAIL']['BEST_REGARDS'] . '<br>';
			$mail->Body .=  WEB_NAME;

			$mail->AltBody  =  $lang['EMAIL']['DEAR_USER'] . '\n\n';
			$mail->AltBody .=  $lang['SETTING']['EMAIL']['CONTENT'] . '\n\n';
			$mail->AltBody .= '=============================================================\n';
			$mail->AltBody .= 'http://' . WEB_HOST . '/?act=settings&job=confirm_email&uid=' . $_SESSION["account_uid"] . '&confirm_code=' . $confirm_code . '\n';
			$mail->AltBody .= '=============================================================\n\n';
			$mail->AltBody .=  $lang['EMAIL']['BEST_REGARDS'] . '\n';
			$mail->AltBody .=  WEB_NAME;

			if(!$mail->send()) {
				trap("Send e-mail failed!", $mail->ErrorInfo);
			}
		}

		// Event log
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_MYSQL  ? "USE " . $_SESSION["username"] : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $_SESSION["username"] : null)));
		$sth->execute();

		$sth = $dbh->prepare("INSERT INTO event_log(EventCode) VALUES(10102)");
		$sth->execute();

		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}

	$xml->addAttribute('result', "OK");
	header('Content-type: text/xml');
	print($xml->asXML());
}

function exit_error($xml, $message){
	$xml[0] = $message;
	$xml->addAttribute('result', "ERROR");
	header('Content-type: text/xml');
	print($xml->asXML());
	exit;
}

function get_share_account(){
	global $login, $lang;
	if($login == false){
		trap("Permission error!");
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><list/>');

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . DB_NAME : (
			DB_IS_MYSQL  ? "USE " . DB_NAME : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
		$sth->execute();

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "SELECT AccountUIDShareTo, Username, Nickname FROM share, account WHERE share.AccountUIDShareBy = ? AND share.AccountUIDShareTo = account.UID ORDER BY account.Username" : (
			DB_IS_MYSQL  ? "SELECT AccountUIDShareTo, Username, Nickname FROM share, account WHERE share.AccountUIDShareBy = ? AND share.AccountUIDShareTo = account.UID ORDER BY account.Username" : (
			DB_IS_ORACLE ? "SELECT AccountUIDShareTo, Username, Nickname FROM \"SHARE\", account WHERE \"SHARE\".AccountUIDShareBy = ? AND \"SHARE\".AccountUIDShareTo = account.\"UID\" ORDER BY account.Username" : null)));
		$sth->execute(array($_SESSION["account_uid"]));

		while($row = $sth->fetch(PDO::FETCH_ASSOC)){
			$account = $xml->addChild('account');
			$account->addAttribute('username', $row["Username"]);
			$account->addAttribute('nickname', $row["Nickname"]);
			$account->addAttribute('account_uid', $row["AccountUIDShareTo"]);
		}
		$sth->closeCursor();

		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}

	header('Content-type: text/xml');
	print($xml->asXML());
}

function add_share_account(){
	global $login, $lang;
	if($login == false){
		trap("Permission error!");
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><share/>');

	if(empty($_GET["username"])){
		exit_error($xml, $lang['SETTING']['AJAX']['USERNAME_IS_EMPTY']);
	}
	else if($_GET["username"] == $_SESSION["username"]){
		exit_error($xml, $lang['SETTING']['AJAX']['USERNAME_IS_YOURSELF']);
	}
	else{
		try {
			$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
			$sth = $dbh->prepare(
				DB_IS_MSSQL  ? "USE " . DB_NAME : (
				DB_IS_MYSQL  ? "USE " . DB_NAME : (
				DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
			$sth->execute();

			$sth = $dbh->prepare(
				DB_IS_MSSQL  ? "SELECT UID, Username, Nickname FROM account WHERE Username = ? COLLATE SQL_Latin1_General_CP1_CS_AS" : (
				DB_IS_MYSQL  ? "SELECT UID, Username, Nickname FROM account WHERE Username = CONVERT(? USING utf8mb4) COLLATE utf8mb4_bin" : (
				DB_IS_ORACLE ? "SELECT \"UID\", Username, Nickname FROM account WHERE Username = ?" : null)));
			$sth->execute(array($_GET["username"]));

			$row = $sth->fetch(PDO::FETCH_ASSOC);
			$sth->closeCursor();

			$uid = $row["UID"];
			$username = $row["Username"];
			$nickname = $row["Nickname"];

			if($uid == NULL){
				$dbh = null;
				exit_error($xml, $lang['SETTING']['AJAX']['USERNAME_NOT_EXIST']);
			}
			else{
				$sth = $dbh->prepare(
					DB_IS_MSSQL  ? "SELECT * FROM share WHERE AccountUIDShareBy = ? AND AccountUIDShareTo = ?" : (
					DB_IS_MYSQL  ? "SELECT * FROM share WHERE AccountUIDShareBy = ? AND AccountUIDShareTo = ?" : (
					DB_IS_ORACLE ? "SELECT * FROM \"SHARE\" WHERE AccountUIDShareBy = ? AND AccountUIDShareTo = ?" : null)));
				$sth->execute(array($_SESSION["account_uid"], $uid));

				$row = $sth->fetch(PDO::FETCH_ASSOC);
				$sth->closeCursor();

				if($row){
					$dbh = null;
					exit_error($xml, $lang['SETTING']['AJAX']['ALREADY_SHARE_WITH_THIS_USERNAME']);
				}
				else{
					$sth = $dbh->prepare(
						DB_IS_MSSQL  ? "INSERT INTO share(AccountUIDShareBy, AccountUIDShareTo) VALUES(?, ?)" : (
						DB_IS_MYSQL  ? "INSERT INTO share(AccountUIDShareBy, AccountUIDShareTo) VALUES(?, ?)" : (
						DB_IS_ORACLE ? "INSERT INTO \"SHARE\"(AccountUIDShareBy, AccountUIDShareTo) VALUES(?, ?)" : null)));
					$sth->execute(array($_SESSION["account_uid"], $uid));

					if(DB_IS_MSSQL){
						$sth = $dbh->prepare("USE " . $_SESSION['username'] . ";");
						$sth->execute();

						$sth = $dbh->prepare("CREATE USER " . $username . " FOR LOGIN " . $username);
						$sth->execute();

						$sth = $dbh->prepare("EXEC sp_addrolemember 'db_datareader', '" . $username);
						$sth->execute();
					}
					else if(DB_IS_MYSQL){
						$sth = $dbh->prepare("GRANT SELECT ON " . $_SESSION["username"] . ".* TO '" . $username . "'");
						$sth->execute();
					}
					else if(DB_IS_ORACLE){
						// Not support
					}

					$dbh = null;

					$xml->addAttribute('result', "OK");
					$xml->addAttribute('account_uid', $uid);
					$xml->addAttribute('username', $username);
					$xml->addAttribute('nickname', $nickname);
					header('Content-type: text/xml');
					print($xml->asXML());
				}
			}
		}
		catch(PDOException $e) {
			trap("Database exception!", $e->getMessage());
		}
	}
}

function remove_share_account(){
	global $login, $lang;
	if($login == false){
		trap("Permission error!");
	}

	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . DB_NAME : (
			DB_IS_MYSQL  ? "USE " . DB_NAME : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
		$sth->execute();

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "SELECT Username FROM account WHERE UID = ?" : (
			DB_IS_MYSQL  ? "SELECT Username FROM account WHERE UID = ?" : (
			DB_IS_ORACLE ? "SELECT Username FROM account WHERE \"UID\" = ?" : null)));
		$sth->execute(array($_GET["account_uid"]));

		$row = $sth->fetch(PDO::FETCH_ASSOC);
		$sth->closeCursor();

		$username = $row["Username"];

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "DELETE FROM share WHERE AccountUIDShareBy = ? AND AccountUIDShareTo = ?" : (
			DB_IS_MYSQL  ? "DELETE FROM share WHERE AccountUIDShareBy = ? AND AccountUIDShareTo = ?" : (
			DB_IS_ORACLE ? "DELETE FROM \"SHARE\" WHERE AccountUIDShareBy = ? AND AccountUIDShareTo = ?" : null)));
		$sth->execute(array($_SESSION["account_uid"], $_GET["account_uid"]));

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "USE " . $username : (
			DB_IS_MYSQL  ? "USE " . $username : (
			DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . $username : null)));
		$sth->execute();

		$sth = $dbh->prepare("DELETE FROM group_data WHERE AccountUID = ?");
		$sth->execute(array($_SESSION["account_uid"]));

		$sth = $dbh->prepare("DELETE FROM dashboard_channel WHERE AccountUID = ?");
		$sth->execute(array($_SESSION["account_uid"]));

		if(DB_IS_MSSQL){
			$sth = $dbh->prepare("USE " . $_SESSION['username']);
			$sth->execute();

			$sth = $dbh->prepare("DROP USER " . $username);
			$sth->execute();
		}
		else if(DB_IS_MYSQL){
			$sth = $dbh->prepare("REVOKE ALL PRIVILEGES ON " . $_SESSION['username'] . ".* FROM '" . $username . "'");
			$sth->execute();

			$sth = $dbh->prepare("FLUSH PRIVILEGES");
			$sth->execute();
		}
		else if(DB_IS_ORACLE){
			// Not support
		}

		$dbh = null;
	}
	catch(PDOException $e) {
		trap("Database exception!", $e->getMessage());
	}

	$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><share/>');
	$xml->addAttribute('result', "OK");
	header('Content-type: text/xml');
	print($xml->asXML());
}

function change_line_bot_status(){
	global $login, $lang;
	if($login == false){
		trap("Permission error!");
	}

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

		$in = '<cmd type="lineBotChangeAccountStatus" account_uid="' . $_SESSION['account_uid'] . '" line_uid="' . $_POST['line_uid'] . '" line_status="' . $_POST['line_status'] . '" />';
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

	header('Content-type: text/xml');
	print($out);
}

function change_line_bot_nickname(){
	global $login, $lang;
	if($login == false){
		trap("Permission error!");
	}

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

		$in = '<cmd type="lineBotChangeAccountNickname" account_uid="' . $_SESSION['account_uid'] . '" line_uid="' . $_POST['line_uid'] . '" line_nickname="' . $_POST['line_nickname'] . '" />';
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

	header('Content-type: text/xml');
	print($out);
}

function remove_line_bot_account(){
	global $login, $lang;
	if($login == false){
		trap("Permission error!");
	}

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

		$in = '<cmd type="lineBotRemoveAccount" account_uid="' . $_SESSION['account_uid'] . '" line_uid="' . $_POST['line_uid'] . '" />';
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

	header('Content-type: text/xml');
	print($out);
}
?>