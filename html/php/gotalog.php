<?php 
session_start();
include 'db_passwords.php';
//$session_id = $_SESSION['session_id'];

	/*try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $wr_username, $wr_password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$stmt = $conn->prepare("UPDATE active_stations SET stop_time=NOW() WHERE session_id = '$session_id'");
		$stmt->execute();
	} catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
	$conn=null;*/
	$_SESSION['gota_band'] = "";
	$_SESSION['gota_dbband'] = "";
	$_SESSION['gota_mode'] = "";
	$_SESSION['gota_first_name'] = "";
	$_SESSION['gota_last_name'] = "";
	$_SESSION['op_callsign'] = "";
	$_SESSION['gota_power'] = "";
	echo '<META http-equiv="refresh" content="0;URL=../gota-log.php">';
?>