<?php
session_start();
//Collect guestbook submission info

/* $servername = "localhost";
$username = "fdlogwrite";
$dbpassword = "adminpassword"; */
include 'db_passwords.php';

//log in as fdlogwrite
$firstnameErr = $lastnameErr = $callsignErr = $classErr = $passErr1 = $passErr2 = "";
$first_name = $last_name = $callsign = $comments =  $license_class = "";
$isReady = 0;
$valid_classes= array('Novice', 'Technician', 'General', 'Advanced', 'Extra');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST["first"]) or empty($_POST["last"]) or empty($_POST["callsign"]) or empty($_POST["class"]) or empty($_POST["password"]) or ($_POST["repeat_password"] !== $_POST["password"])) {
		$isReady = 0;
	} else {
		$isReady = 1;
	}
	if (empty($_POST["first"])) {
		$firstnameErr = "First name is required";
		$isReady = 0;
	} else {
		$first_name = test_input($_POST["first"]);
	}

	if (empty($_POST["last"])) {
		$lastnameErr = "Lastname is required";
		$isReady = 0;
	} else {
		$last_name = test_input($_POST["last"]);
	}

	if (empty($_POST["callsign"])) {
		$callsignErr = "Callsign Required";
		$isReady = 0;
	} else {
		$callsign = strtoupper(test_input($_POST["callsign"]));
		try {
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $rd_username, $rd_password);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = $conn->prepare("SELECT COUNT(call_sign) FROM users WHERE call_sign = '$callsign'");
			$sql->execute();
			$count = $sql->fetch();
			if ($count[0] > 0) {
				$callsignErr = "User already exists";
				$isReady = 0;
			}
			$conn=null;
		}
		catch(PDOException $e)
		{
		echo "Connection failed: " . $e->getMessage();
		}
		
	}
	if (empty($_POST["class"])) {
		$callsignErr = "Class Required";
	} else {
		if(in_array($_POST["class"], $valid_classes)) {
			$license_class = test_input($_POST["class"]);
		} else {
			$classErr = "Invalid Class. Stop messing with the web form >:(";
			$isReady = 0;
		}
	}
	if (empty($_POST["password"] or $_POST["repeat_password"])) {
		$passErr2 = "Password required!";
		$isReady = 0;
	} else {
		$password1 = $_POST["password"];
		$password2 = $_POST["repeat_password"];
		if ($password1 === $password2) {
			$pass_options = ['cost' => 12];
			$password = password_hash($_POST["password"], PASSWORD_BCRYPT, $pass_options);
		}else {
			$passErr1 = "Passwords do not match!";
			$isReady = 0;
		}
	}
}
	function test_input($data) {
	$data = trim($data);
	$data = htmlspecialchars($data);
	return $data;
	}
if ($isReady === 1) {	
	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $wr_username, $wr_password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $conn->prepare("INSERT INTO users(call_sign, first_name, last_name, license_class, password, user_level) VALUES (:callsign, :first_name, :last_name, :license_class, :password, 'user')");
		$stmt->bindParam(':callsign', $callsign);
		$stmt->bindParam(':first_name', $first_name);
		$stmt->bindParam(':last_name', $last_name);
		$stmt->bindParam(':license_class', $license_class);
		$stmt->bindParam(':password', $password);
		
		$stmt->execute();
		
		echo '<META http-equiv="refresh" content="0;URL=sign-in.php">';
	}

	catch(PDOException $e)
		{
		echo "Connection failed: " . $e->getMessage();
		}
	
$conn=null;
} else {
	/*echo '<span class="error"> Enter required fields!</span>';*/
}
?>