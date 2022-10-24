<?php
	function warning_handler($errno, $errstr, $errfile, $errline, array $errcontext) {
	    // error was suppressed with the @-operator
	    if (0 === error_reporting()) {
	        return false;
	    }

	    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}

	preg_match("/^\/([0-9A-Za-z]{16})(\/.*)$/", $_SERVER['REQUEST_URI'], $match_array);

	if(is_null($match_array[0])){
		header("HTTP/1.1 404 Not Found");
		echo "404 Page Not Found";
	}
	else{
		$serial_number = $match_array[1];
		$request_uri = $match_array[2];

		/* Get the port for the WWW service. */
		$port = 1233;

		/* Get the IP address for the target host. */
		$address = "127.0.0.1";

		try {
			// Let all warning handle by warning_handler(), and throw exception when warning occur
			set_error_handler("warning_handler");

			set_time_limit(0);

			/* Create a TCP/IP socket. */
			$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
			socket_connect($socket, $address, $port);

			// First 16 bytes is serial number.
			$in = $serial_number;

			// Header to send
		    $in .= $_SERVER['REQUEST_METHOD'] . " " . $request_uri . " " . $_SERVER['SERVER_PROTOCOL'] . "\r\n";
			foreach (getallheaders() as $name => $value) {
				if($value == ""){continue;}// IIS use empty Conetent-Type cause ISAPI error when GET method

			    $in .= $name . ": " . $value . "\r\n";
			}
		    $in .= "\r\n";

			preg_match('/boundary=(.*)$/', isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : "", $matches);
			if(sizeof($matches) > 0){//is multipart/form-data
				$boundary = $matches[1];

				foreach ($_FILES as $name => $value){
					$in .= "--" . $boundary . "\r\n";
					$in .= "Content-Disposition: form-data; name=\"" . $name . "\"; filename=\"" . $value["name"] . "\"\r\n";
					$in .= "Content-Type: " . $value["type"] . "\r\n\r\n";

					$handle = fopen($value["tmp_name"], "r");
					$in .= fread($handle, filesize($value["tmp_name"])) . "\r\n";
					fclose($handle);
				}

				foreach ($_POST as $name => $value){
					$in .= "--" . $boundary . "\r\n";
					$in .= "Content-Disposition: form-data; name=\"" . $name . "\"\r\n\r\n";

					$in .= $value . "\r\n";
				}

				$in .= "--" . $boundary . "--\r\n";
			}
			else{
				$in .= file_get_contents('php://input');
			}

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