<?php
// Start the session
session_start();
$servername = "localhost";
$username = "fdlogwrite";
$dbpassword = "adminpassword";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	try {
		$conn = new PDO("mysql:host=$servername;dbname=fdlogdb", $username, $dbpassword);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if (!empty($_POST["bonus"])) {
			$bonus = join('\', \'', $_POST["bonus"]);
			$stmt = $conn->prepare("UPDATE fd_config SET number = '1' WHERE config_name IN ('$bonus')");
			$stmt->execute();
		}
		if (!empty($_POST["power"])) {
			$power = join('\', \'', $_POST["power"]);
			$stmt = $conn->prepare("UPDATE fd_config SET number = '1' WHERE config_name IN ('$power')");
			$stmt->execute();
		}
		if (!empty($_POST["other_power"])) {
			$other_power = $_POST["other_power"];
			$stmt = $conn->prepare("UPDATE fd_config SET large_string = '$other_power' WHERE config_name = 'other_power'");
			$stmt->execute();
		}
		if (!empty($_POST["youth"]) && !empty($_POST["number_youth"])) {
			$number_youth = $_POST["number_youth"];
			$stmt = $conn->prepare("UPDATE fd_config SET number = '$number_youth' WHERE config_name = 'youth_participation'");
			$stmt->execute();
			if (!empty($_POST["number_youth_qso"])) {
				$number_youth_qso = $_POST["number_youth_qso"];
				$stmt = $conn->prepare("UPDATE fd_config SET number = '$number_youth_qso' WHERE config_name = 'youth_qso'");
				$stmt->execute();
			}	
		}
		if (!empty($_POST["messages"]) && !empty($_POST["number_messages"])) {
			$number_messages = $_POST["number_messages"];
			$stmt = $conn->prepare("UPDATE fd_config SET number = '$number_messages' WHERE config_name = 'formal_mesgs'");
			$stmt->execute();
		}
		if (!empty($_POST["participants"])) {
			$participants = $_POST["participants"];
			$stmt = $conn->prepare("UPDATE fd_config SET number = '$participants' WHERE config_name = 'participants'");
			$stmt->execute();
		}
	} catch(PDOException $e) {
		echo "Connection failed: " . $e->getMessage();
	}
	$conn=null;
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Field Day</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="Corey Koval, K3CPK">
	<meta name="application-name" content="Field Day Logging Database" />
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<script type="text/javascript" src="../js/fdlog.js"></script>
</head>
<body  onload="startTime()">
	<div id="outer_wrapper" class="grid">
		<div class="row">
			<div id="menu" class="col-2">
				<?php include '../navbar.php'; ?>
			</div>
			<div id="header2" class="col-10">
				<?php include '../header.php'; ?>
				<div id="content">
					<?php
					if (!empty($_SESSION['priv']) and $_SESSION['priv'] === "admin") {
						echo '
						<form id="postfd" action='. $_SERVER["PHP_SELF"].' method="POST">
						Number of participants:<input type="number" name="participants"><br>
						<fieldset>
							<legend>Power Sources</legend>
							<input type="checkbox" name="power[]" value="commercial">Commercial Mains 
							<input type="checkbox" name="power[]" value="generator">Generator 
							<input type="checkbox" name="power[]" value="battery">Battery<br>
							<input type="checkbox" name="power[]" value="solar">Solar 
							<input type="checkbox" name="power[]" value="wind">Wind  
							<input type="checkbox" name="power[]" value="methane">Methane 
							<input type="checkbox" name="power[]" value="water">Water<br>
							Other power (Comma separated list):<input type="text" name="other_power">
						</fieldset>
						<br>
						<fieldset>
							<legend>Bonus Points</legend>
							<input type="checkbox" name="bonus[]" value="media">Received media publicity<br>
							<input type="checkbox" name="bonus[]" value="public_place">Operated from a public place<br>
							<input type="checkbox" name="bonus[]" value="social_media">Field day site promoted on social media<br>
							<input type="checkbox" name="bonus[]" value="safety_officer">Safety Officer on site<br>
							<input type="checkbox" name="bonus[]" value="info_booth">Had a club information booth on site<br>
							<input type="checkbox" name="bonus[]" value="arrl_sm_mesg">Formal message to ARRL SM/SEC<br>
							<input type="checkbox" name="bonus[]" value="w1aw_mesg">Received the W1AW Field Day bulletin<br>
							<input type="checkbox" name="messages" value="formal_mesgs">Handled formal messages. Number of messages handled:<input type="number" style="width:45px;text-align:right;" name="number_messages"><br>
							<input type="checkbox" name="bonus[]" value="elected_official">Site visited by an invited elected official<br>
							<input type="checkbox" name="bonus[]" value="agency_official">Site visited by an invited served agency official<br>
							<input type="checkbox" name="bonus[]" value="educational_activity">Site hosted an educational activity<br>
							<input type="checkbox" name="youth" value="youth_participation">Youth Participation. Number of youth at site:
							<input type="number" style="width:45px;text-align:right;" name="number_youth"><br>
							Number of youth that completed at least 1 QSO:<input type="number" style="width:45px;text-align:right;" name="number_youth_qso">
							
						</fieldset>
						<span style="float: right">
							<input type="submit" value="Submit">
						</span>
						</form>';
						echo '<br>Formatted output to go here soon.';
					} else {
						echo '<h2>Admins only please!</h2>';
					}?>
				</div>
			</div>
		</div>
	</div>
</body>
</html>