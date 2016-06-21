<?php 
session_start();
/*$servername = "localhost";
$dbusername = "fdlogwrite";
$dbpassword = "adminpassword";*/
include 'db_passwords.php';
$session_id = $_SESSION['session_id'];

	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $wr_username, $wr_password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$stmt = $conn->prepare("UPDATE active_stations SET stop_time=NOW() WHERE session_id = '$session_id'");
		$stmt->execute();
	} catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
	$conn=null;
	$_SESSION['band'] = "";
	$_SESSION['dbband'] = "";
	$_SESSION['mode'] = "";
	$_SESSION['natural_power'] = "";
	$_SESSION['session_id'] = "";
	echo '<META http-equiv="refresh" content="0;URL=../enter-log.php">';
?>