<?php
/*CONFIG START*/
define("WEB_PROTOCOL", "http://");
define("WEB_HOST", "127.0.0.1:81");
define("WEB_NAME", "Moretech");
define("WEB_SIGNUP_PAGE", "1");
define("DB_IS_MSSQL", false);
define("DB_IS_MYSQL", true);
define("DB_IS_ORACLE", false);
define("DB_HOST", "wellracom.cc5wghrbi86u.ap-southeast-1.rds.amazonaws.com");
define("DB_PORT", 3306);
define("DB_USER", "iotstar");
define("DB_PASS", "wellracom");
define("DB_NAME", "manager");
define("DB_MAX_SIZE", "-1");
define("SMTP_HOST", "mail.satriacorp.id");
define("SMTP_PORT", 465);
define("SMTP_USER", "moretech@satriacorp.id");
define("SMTP_PASS", "100%Id/En");
define("SMTP_SECURITY", "1");
define("SMTP_SENDER_NAME", "arkanichsan");
define("SMTP_SENDER_EMAIL", "arkanichsan37@gmail.com");
define("LINE_BOT_QR_CODE", "");
define("NOTIFICATION_ADMIN_EMAIL", "");
define("NOTIFICATION_SIGNUP", "0");
define("FIRMWARE_TEMP_DIRECTORY", "D:\\App\\IoTstar\\Server\\tempfw");
define("MEDIA_DIRECTORY", "D:\\App\\IoTstar");
define("VERSION", "3.1.1");
/*CONFIG END*/

if(DB_IS_MSSQL){
	define("DB_DSN", "sqlsrv:server=" . DB_HOST . "," . DB_PORT);
	define("DB_OPTIONS", [
	    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::SQLSRV_ATTR_ENCODING => PDO::SQLSRV_ENCODING_UTF8,
		PDO::ATTR_STATEMENT_CLASS => array('MyPDOStatement')
	]);
}
else if(DB_IS_MYSQL){
	define("DB_DSN", "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";charset=utf8");
	define("DB_OPTIONS", [
	    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_STATEMENT_CLASS => array('MyPDOStatement')
	]);
}
else if(DB_IS_ORACLE){
	define("DB_DSN", "odbc:Driver={Oracle in Oracle213};Dbq=//" . DB_HOST . ":" . DB_PORT . "/IOTSTAR;Uid=" . DB_USER_ORACLE . ";Pwd=" . DB_PASS_ORACLE . " as sysdba;charset=utf8");
	define("DB_OPTIONS", [
	    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_STATEMENT_CLASS => array('MyPDOStatement')
	]);
}

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);

$timeout = ini_get('max_execution_time');// Save default timeout value
date_default_timezone_set('UTC');

function token_check($token){
	$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
	$sth = $dbh->prepare(
		DB_IS_MSSQL  ? "USE " . DB_NAME : (
		DB_IS_MYSQL  ? "USE " . DB_NAME : (
		DB_IS_ORACLE ? "ALTER SESSION SET CURRENT_SCHEMA = " . DB_NAME : null)));
	$sth->execute();

	$sth = $dbh->prepare(
		DB_IS_MSSQL  ? "SELECT * FROM dashboard_link WHERE Token = ? AND (ExpirationDate IS NULL OR ExpirationDate > ?)" : (
		DB_IS_MYSQL  ? "SELECT * FROM dashboard_link WHERE Token = ? AND (ExpirationDate IS NULL OR ExpirationDate > ?)" : (
		DB_IS_ORACLE ? "SELECT * FROM dashboard_link WHERE Token = ? AND (ExpirationDate IS NULL OR ExpirationDate > TO_TIMESTAMP(?, 'YYYY-MM-DD HH24:MI:SS'))" : null)));
	$sth->execute(array($token, gmdate("Y-m-d H:i:s")));

	$row = $sth->fetch(PDO::FETCH_ASSOC);
	$sth->closeCursor();

	if($row){
		$account_uid = $row["AccountUID"];
		$dashboard_permission = $row["Permission"];

		$sth = $dbh->prepare(
			DB_IS_MSSQL  ? "SELECT * FROM account WHERE UID = ?" : (
			DB_IS_MYSQL  ? "SELECT * FROM account WHERE UID = ?" : (
			DB_IS_ORACLE ? "SELECT * FROM account WHERE \"UID\" = ?" : null)));
		$sth->execute(array($account_uid));

		$row = $sth->fetch(PDO::FETCH_ASSOC);
		$sth->closeCursor();

		$username = $row["Username"];

		$dbh = null;

		return [$account_uid, $username, $dashboard_permission];
	}
	else{// Token not found or expired
		$dbh = null;

		return null;
	}
}

function session_check(){
	global $dashboard_permission;

	if(isset($_GET["token"])){
		try{
			list($account_uid, $username, $dashboard_permission) = token_check($_GET["token"]);

			if($account_uid != null){
				session_name("ssid-dashboard-share");
				session_start();

				$_SESSION["login"] = true;
				$_SESSION["account_uid"] = $account_uid;
				$_SESSION["username"] = $username;
				$_SESSION["password"] = $_GET["token"];// Token
				$_SESSION["remember_me"] = true;
			}
			else{
				// Show token not found or expired message
			}
		}
		catch(PDOException $e) {
			header("HTTP/1.0 500 Internal Server Error");
			echo "Database exception!\n" . $e->getMessage();
			exit;
		}
	}
	else{
		session_name("ssid");
		session_start();
	}

	$user_ip = get_user_ip();

	if(/*$_SESSION['user_ip'] != $user_ip || */$_SESSION['login'] != true){
		return false;
	}
	else{
		setcookie(session_name(), session_id(), $_SESSION['remember_me'] == true ? time() + 60 * 60 * 24 * 30 : 0);
		return true;
	}
}

function logout(){
	try {
		$dbh = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
		$sth = $dbh->prepare("USE " . $_SESSION["username"]);
		$sth->execute();

		// Event log
		$sth = $dbh->prepare("INSERT INTO event_log(EventCode, Parameters) VALUES(?, ?);");
		$sth->execute(array("10002", get_user_ip()));

		$dbh = null;
	}
	catch(PDOException $e) {}

	session_name("ssid");
	session_start();
    session_destroy();
	setcookie(session_name(), "", 0);
}

function random_string($length = 10, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
    $characters_length = strlen($characters);
    $random_string = '';
    for ($i = 0; $i < $length; $i++) {
        $random_string .= $characters[rand(0, $characters_length - 1)];
    }

    return $random_string;
}

function create_mail(){
	require './phpmailer/PHPMailerAutoload.php';

	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->Host = SMTP_HOST;
	$mail->SMTPAuth = true;
	$mail->SMTPAutoTLS = false;
	$mail->SMTPOptions = array(
	    'ssl' => array(
	        'verify_peer' => false,
	        'verify_peer_name' => false,
	        'allow_self_signed' => true
	    )
	);

	$mail->Username = SMTP_USER;
	$mail->Password = SMTP_PASS;
	$mail->Port = SMTP_PORT;

	$mail->setFrom(SMTP_SENDER_EMAIL, SMTP_SENDER_NAME);
	$mail->isHTML(true);
	$mail->CharSet = "utf-8";

	if(SMTP_SECURITY == "1"){
		$mail->SMTPSecure = 'tls';
	}
	else if(SMTP_SECURITY == "2"){
		$mail->SMTPSecure = 'ssl';
	}

//	$mail->SMTPDebug = 2;

	return $mail;
}

function encrypt($key, $encrypt){
    $block = mcrypt_get_block_size(MCRYPT_DES, MCRYPT_MODE_ECB);
    $pad = $block - (strlen($encrypt) % $block);
    $encrypt .= str_repeat(chr($pad), $pad);
    return base64_encode(mcrypt_encrypt(MCRYPT_DES, $key, $encrypt, MCRYPT_MODE_ECB));
}
 
function decrypt($key, $decrypt){
    $str = mcrypt_decrypt(MCRYPT_DES, $key, base64_decode($decrypt), MCRYPT_MODE_ECB);
    $pad = ord($str[strlen($str) - 1]);
    return substr($str, 0, strlen($str) - $pad);
}

function get_user_ip(){
	if (!empty($_SERVER['HTTP_CLIENT_IP'])){
		return $_SERVER['HTTP_CLIENT_IP'];
	}
	else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$temp = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"]);
		return $temp[0];
	}
	else{
		return $_SERVER['REMOTE_ADDR'];
	}
}

function prefered_language($support_lang) {
	if(isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])) {
		$accept_lang = strtolower($_SERVER["HTTP_ACCEPT_LANGUAGE"]);

		$x = explode(",", $accept_lang);
		foreach ($x as $val) {
			if(preg_match("/(.*);q=([0-1]{0,1}.\d{0,4})/i", $val ,$matches)){
				$lang[$matches[1]] = (float)$matches[2];
			}
			else{
				$lang[$val] = 1.0;
			}
		}

		arsort($lang);
		$support_lang = array_map('strtolower', $support_lang);

		foreach ($lang as $key => $value) {
			if (in_array($key, $support_lang)) {
				return $key;
			}
		}
	}

	return NULL;
}

class CaseInsensitiveArray implements ArrayAccess{
	private $container = array();

	public function __construct(Array $initial_array = array()){
		$this->container = array_change_key_case($initial_array, CASE_UPPER);
	}

	public function offsetSet($offset, $value){
		if(is_string($offset)){
			$offset = strtoupper($offset);
		}

		if(is_null($offset)) {
			$this->container[] = $value;
		}
		else {
			$this->container[$offset] = $value;
		}
	}

	public function offsetExists($offset){
		if(is_string($offset)){
			$offset = strtoupper($offset);
		}

		return isset($this->container[$offset]);
	}

	public function offsetUnset($offset){
		if(is_string($offset)){
			$offset = strtoupper($offset);
		}

		unset($this->container[$offset]);
	}

	public function offsetGet($offset){
		if(is_string($offset)){
			$offset = strtoupper($offset);
		}

		return isset($this->container[$offset]) ? $this->container[$offset] : null;
	}
}

class MyPDOStatement extends PDOStatement{
	public function fetch(){
		$ret = call_user_func_array(array('parent', 'fetch'), func_get_args());

		if(DB_IS_ORACLE && gettype($ret) == "array"){
			return new CaseInsensitiveArray($ret);
		}
		else{
			return $ret;
		}
	}

	public function fetchOriginal(){
		return call_user_func_array(array('parent', 'fetch'), func_get_args());
	}
}
?>
