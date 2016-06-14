<?php
session_start();
include 'db_passwords.php';
/* $servername = "localhost";
$username = "fdlogadmin";
$password = "adminpassword"; */
$del_ids = "";
$uuid = $_SESSION['uuid'];
$table = $_SESSION["table"];
$key = $_SESSION["key"];
$page = $_SESSION["page"];
if(!empty($_SESSION['priv']) && $_SESSION['priv'] === 'admin') {
	$delete_only_yours = '';
} elseif($table === 'logbook') {
	$delete_only_yours = 'logger_id = '.$uuid.' AND ';
} elseif($table === 'inventory') {
	$delete_only_yours = 'user_id = '.$uuid.' AND ';
} else {
	$delete_only_yours = '';
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["delete"])) {
	$del_ids = join(', ', $_POST["delete"]);
	echo 'Deleting rows '.$del_ids.'.';

	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $admin_username, $admin_password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $conn->prepare("DELETE FROM $table WHERE $delete_only_yours $key IN ($del_ids)");$stmt->execute();
		$stmt->execute();
		echo "Success!";
		echo '<META http-equiv="refresh" content="0;URL='.$page.'">';		
	}

	catch(PDOException $e)
		{
		echo "Connection failed: " . $e->getMessage();
		}
	
	$conn=null;
} else {
	echo '<META http-equiv="refresh" content="0;URL='.$page.'">';
}


?>
