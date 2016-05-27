<?php
$servername = "localhost";
$username = "fdlogadmin";
$password = "adminpassword";
$table = $del_ids = $key = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["delete"])) {
	$table = $_POST["table"];
	$key = $_POST["key"];
	$del_ids = join(', ', $_POST["delete"]);
	echo 'Deleting rows '.$del_ids.'.';

	try {
		$conn = new PDO("mysql:host=$servername;dbname=fdlogdb", $username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $conn->prepare("DELETE FROM $table WHERE $key IN ($del_ids)");$stmt->execute();
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


?>
