<?php
$servername = "localhost";
$dbusername = "fdlogread";
$dbpassword = "password";
$usernameErr = $passErr1 = "";
$username = $password = "";
$hash = "";
$isReady = 0;

function test_input($data) {
	$data = trim($data);
	$data = htmlspecialchars($data);
	return $data;
	}
	
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST["username"]) or empty($_POST["password"])) {
		$isReady = 0;
	} else {
		$isReady = 1;
	}
	if (empty($_POST["username"])) {
		$usernameErr = "Please Enter your call sign or username.";
	} else {
		$username = test_input($_POST["username"]);
	}

	if (empty($_POST["password"])) {
		$passErr1 = "Please enter your password";
	} else {
		$password = test_input($_POST["password"]);
	}
	try {
		$conn = new PDO("mysql:host=$servername;dbname=fdlogdb", $dbusername, $dbpassword);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = $conn->prepare("SELECT COUNT(call_sign) FROM users WHERE call_sign = '$callsign'");
		$sql->execute();
		$count = $sql->rowCount();
		if ($count > 0) {
			$sql = $conn->prepare("SELECT password FROM users WHERE call_sign = '$username'");
			$sql->execute();
			$hash = $sql->fetch();
		} else {
			//
		}
	}
	catch(PDOException $e)
	{
		echo "Connection failed: " . $e->getMessage();
	}
}

?>