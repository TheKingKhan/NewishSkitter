<?php
	$servername = 'localhost';
	$dbuname = 'root';
	$dbpass = 'root';
	$dbname = 'Skitter';
	$conn = new mysqli($servername, $dbuname, $dbpass, $dbname);
	$userid = NULL;

	if($conn->connect_error){
		die("Connection failed sorry");
	}

	$username = $_POST['displayName'];
	$email = $_POST['email'];
	$file = $_POST['fileToUpload'];

	//Let's validate the post parameters, they will be NULL if they were not entered.
	$username = validateUsername($username);
	$email = validateEmail($email);
	$file = validateFile($file);

	//Let's check if the username is already taken
	if(isset($username)){
		$stmt = $conn->prepare("SELECT userid FROM Users WHERE username = ?;");
		$stmt->bind_param("s", $username);

		if(!$stmt->execute()){
			print "Error in executing command";
		}

		$stmt->bind_result($userid);

		if(!isset($userid)){
			die "Error settings username: username already being used<br>";
		}
	}

	//Get the user's userid based on their id cookie
	$stmt->prepare("SELECT userid FROM Users WHERE idcookie = ?;");
	$stmt->bind_param("s", $_COOKIE["id"]);

	if(!$stmt->execute()){
		print "Error in executing command";
	}

	$stmt->bind_result($userid);

	if(isset($userid)){
		print "Fatal Cookie Error<br>";
	}

	$id = $_COOKIE["id"];
	$stmt = $conn->prepare("UPDATE Users SET username = ? WHERE idcookie = ?;");
	$stmt->bind_param("ss", $username, $id);

	if(!$stmt->execute()){
		print "Error in executing command";
	}

	function validateUsername($unameUnsanitized){
		$unameSanitized = strip_tags($unameUnsanitized);
		if(strlen($unameSanitized) >= 1){
			return $unameSanitized;
		}
		$unameSanitized = NULL;
		return $unameSanitized;
	}

	function validateEmail($emailUnsanitized){
		$emailSanitized = strip_tags($emailUnsanitized);
		if(strlen($emailSanitized) >= 1){
			if(filter_var($emailSanitized, FILTER_VALIDATE_EMAIL)){
				return $emailSanitized;
			} else {
				die("<br>Email is invalid<br>");
			}
		}

		$emailSanitized = NULL;
		return $fileSanitized;
	}

	function validateFile($fileUnsanitized){
		$fileSanitized = strip_tags($fileUnsanitized);
		if(strlen($fileSanitized) >= 1){
			$image = getimagesize($fileSanitized) ? true : false;

			if($image == true){
				return $fileSanitized;
			} else {
				die("<br>File uploaded was not an image<br>");
			}
		}

		$fileSanitized = NULL;
		return $fileSanitized;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Skitter</title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/home.css">
		<link rel="apple-touch-icon" sizes="180x180" href="img/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png">
		<link rel="manifest" href="img/site.webmanifest">
		<link rel="mask-icon" href="img/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="msapplication-TileColor" content="#da532c">
		<meta name="theme-color" content="#ffffff">
	</head>
	<body>

	</body>
</html>