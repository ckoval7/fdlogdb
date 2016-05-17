<?php
$servername = "localhost";
$dbusername = "fdlogread";
$dbpassword = "password";
$usernameErr = $passErr1 = "";
$username = $password = "";
$hash = "";
$isReady = 0;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST["username"]) or empty($_POST["password"]) {
		$isReady = 0;
	} else {
		$isReady = 1;
	}
	if (empty($_POST["username"])) {
		$usernameErr = "Please Enter your call sign or username.";
	} else {
		$first_name = test_input($_POST["first"]);
	}

	if (empty($_POST["last"])) {
		$lastnameErr = "Lastname is required";
	} else {
		$last_name = test_input($_POST["last"]);
	}


?>