<?php
$servername = "localhost";
$dbusername = "fdlogread";
$dbpassword = "password";
$usernameErr = $passErr1 = $passErr2 = "";
$username = $password = $user_priv = "";
$hash = "";
$isReady = 0;

function test_input($data) {
	$data = trim($data);
	$data = htmlspecialchars($data);
	return $data;
	}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST["username"])) {
		$usernameErr = "Please Enter your call sign or username.";
	} else {
		$username = strtoupper(test_input($_POST["username"]));
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
		$sql = $conn->prepare("SELECT COUNT(call_sign) FROM users WHERE call_sign = :username");
		$sql->bindParam(':username', $username);
		$sql->execute();
		$count = $sql->rowCount();
		if ($count > 0) {
			$sql = $conn->prepare("SELECT password FROM users WHERE call_sign = :username");
			$sql->bindParam(':username', $username);
			$sql->execute();
			$hash = $sql->fetch();
			if (password_verify($password, $hash[0])) {
				//echo "Valid password!";
				$sql = $conn->prepare("SELECT uuid, first_name, user_level FROM users WHERE call_sign = :username");
				$sql->bindParam(':username', $username);
				$sql->execute();
				$user_array = $sql->fetch();
				$_SESSION['priv'] = $user_array[2];
				$_SESSION['username'] = $username;
				$_SESSION['name'] = $user_array[1];
				$_SESSION['uuid'] = $user_array[0];
				$_SESSION['band'] = "";
				$_SESSION['mode'] = "";
				echo '<META http-equiv="refresh" content="0;URL=/index.php">';
			} else {
				$passErr2 = 'Username or password incorrect';
			}
		} else {
			$passErr2 = 'Username or password incorrect';
		}
	}
	catch(PDOException $e)
	{
		echo "Connection failed: " . $e->getMessage();
	}
}

?>