<?php
/*CONFIG START*/
define("ROOT", "D:\\App\\IoTstar");
/*CONFIG END*/

function warning_handler($errno, $errstr, $errfile, $errline, array $errcontext) {
    // error was suppressed with the @-operator
    if (0 === error_reporting()) {
        return false;
    }

    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

if($_GET["act"] == "image"){
	if(isset($_GET["file"])){
		preg_match('/^[^_]{16}_(\d{8})/', $_GET["file"], $matches);
		$filepath = ROOT . "\\Media\\Device\\" . $matches[1] . "\\" . $_GET["file"];
	}
	else{
		$filepath = ROOT . "\\Media\\Device\\" . $_GET["date"] . "\\" . $_GET["name"];
	}

	$filename = basename($filepath);
	$extension = strtolower(substr(strrchr($filename, "."), 1));

	if($extension == "mp4") {
/*
		$type = "video/mp4";

		$size = filesize("wfio://" . $filepath);
		$begin = 0;
		$end = $size;

		if(isset($_SERVER['HTTP_RANGE'])) {
			if(preg_match('/bytes=\h*(\d+)-(\d*)[\D.*]?/i', $_SERVER['HTTP_RANGE'], $matches)){
				$begin = intval($matches[1]);

				if(!empty($matches[2])) {
					$end = intval($matches[2]);
				}
			}
		}

		if($begin > 0 || $end < $size){
			header('HTTP/1.1 206 Partial Content');
			header('Accept-Ranges: bytes');
			header("Content-Range: bytes $begin-$end/$size");
		}
		else{
			header('HTTP/1.1 200 OK');
            $agent = $_SERVER['HTTP_USER_AGENT']; 
            if(!strpos($agent, "Chrome")){
                header('Accept-Ranges: bytes');
            }
		}

		header("Content-Type: " . $type);
		header('Content-Length: ' . ($end - $begin));

		$cur = $begin;
		$fm = fopen($filepath, 'rb');
		fseek($fm, $begin, 0);
		while(!feof($fm) && $cur < $end && connection_status() == 0) {
			echo fread($fm, min(1024 * 16, $end - $cur));
			$cur += 1024 * 16;
//				usleep(1000);
		}
*/
        
        $type = "video/mp4";
        $size = filesize($filepath);
        $length = $size;
		$begin = 0;
		$end = $size - 1;
        
        if(isset($_SERVER['HTTP_RANGE'])) {
			if(preg_match('/bytes=\h*(\d+)-(\d*)[\D.*]?/i', $_SERVER['HTTP_RANGE'], $matches)){
				$begin = intval($matches[1]);

				if(!empty($matches[2])) {
					$end = intval($matches[2]);
				}
			}
            
            $length = $end - $begin + 1;
            header('HTTP/1.1 206 Partial Content');
            header('Content-Type: ' . $type);
			header('Accept-Ranges: bytes');
			header("Content-Range: bytes $begin-$end/$size");
            header('Content-Length: ' . $length);
		}
        else{
            header('HTTP/1.1 200 OK');
            header('Content-Type: ' . $type);
            header('Content-Length: ' . $length);
        }
        
        $cur = $begin;
		$fm = fopen($filepath, 'rb');
		fseek($fm, $begin, 0);
		while(!feof($fm) && $cur <= $end && connection_status() == 0) {
			echo fread($fm, min(1024 * 16, ($end - $cur) + 1));
			$cur += 1024 * 16;
//				usleep(1000);
		}
	}
	else{
		if($extension == "gif") {
			$type = "image/gif";
		}
		else if($extension == "png") {
			$type = "image/png";
		}
		else if($extension == "jpg" || $extension == "jpeg") {
			$type = "image/jpeg";
		}

		header('Content-Type: ' . $type);
		echo file_get_contents($filepath);
	}
}
else{
	/* Get the port for the WWW service. */
	$port = 1235;

	/* Get the IP address for the target host. */
	$address = "127.0.0.1";

	try {
		// Let all warning handle by warning_handler(), and throw exception when warning occur
		set_error_handler("warning_handler");
		set_time_limit(0);

		$in = "";

		// Header to send
	    $in .= $_SERVER['REQUEST_METHOD'] . " " . $_SERVER['REQUEST_URI'] . " " . $_SERVER['SERVER_PROTOCOL'] . "\r\n";
		foreach (getallheaders() as $name => $value) {
			if($value == ""){continue;}// IIS use empty Conetent-Type cause ISAPI error when GET method

		    $in .= $name . ": " . $value . "\r\n";
		}
	    $in .= "\r\n";

		$in .= file_get_contents('php://input');

		/* Create a TCP/IP socket. */
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		socket_connect($socket, $address, $port);
		socket_write($socket, $in);
		socket_shutdown($socket, 1);

		$buffer = "";
	    while ($read = socket_read($socket, 2048)) {
			$buffer .= $read;

			$index = strpos($buffer, "\r\n\r\n");

			if($index !== false) {
				$parts = explode("\r\n\r\n", $buffer, 2);

				// Header to browser
				$fields = explode("\r\n", $parts[0]);
				foreach ($fields as $name => $value) {
			 		header($value);
				}

				// Content to browser
				echo $parts[1];

				break;
			}
	    }

	    while ($read = socket_read($socket, 2048)) {
			echo $read;
	    }

		socket_close($socket);
	}
	catch (Exception $e) {
		header("HTTP/1.1 500 Internal Server Error");
		echo $e;
	}
}
?>