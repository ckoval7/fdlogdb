<?php
session_start();
$servername = "localhost";
$username = "fdlogadmin";
$password = "adminpassword";
$del_ids = "";
$table = $_SESSION["table"];
$key = $_SESSION["key"];
$page = $_SESSION["page"];

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["delete"])) {
	$del_ids = join(', ', $_POST["delete"]);
	echo 'Deleting rows '.$del_ids.'.';

	try {
		$conn = new PDO("mysql:host=$servername;dbname=fdlogdb", $username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $conn->prepare("DELETE FROM $table WHERE $key IN ($del_ids)");$stmt->execute();
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


?>
