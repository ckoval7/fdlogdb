<?php
session_start();
$servername = "localhost";
$username = "fdlogadmin";
$password = "adminpassword";
$del_ids = $res_ids = $pass = "";
$table = $_SESSION["table"];
$key = $_SESSION["key"];
$page = $_SESSION["page"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (!empty($_POST["disable"])) {
		$del_ids = join(', ', $_POST["disable"]);
		try {
			$conn = new PDO("mysql:host=$servername;dbname=fdlogdb", $username, $password);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $conn->prepare("UPDATE users SET user_level = 'locked' WHERE uuid IN ($del_ids)");
			$stmt->execute();
			echo "Success!";
			echo '<META http-equiv="refresh" content="0;URL='.$page.'">';		
		}

		catch(PDOException $e)
			{
			echo "Connection failed: " . $e->getMessage();
			}
		
		$conn=null;
	}
	if (!empty($_POST["reset"])) {
		$res_ids = join(', ', $_POST["reset"]);
		try {
			$pass_options = ['cost' => 12];
			$pass = password_hash("password", PASSWORD_BCRYPT, $pass_options);
			$conn = new PDO("mysql:host=$servername;dbname=fdlogdb", $username, $password);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $conn->prepare("UPDATE users SET password='$pass' WHERE uuid IN ($res_ids)");
			$stmt->execute();
			echo "Success!";
			echo '<META http-equiv="refresh" content="0;URL='.$_POST["page"].'">';		
		}

		catch(PDOException $e)
			{
			echo "Connection failed: " . $e->getMessage();
			}
		
		$conn=null;
	}
}


?>
