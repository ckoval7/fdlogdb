<?php
// Start the session
session_start();
include '../php/db_passwords.php';
/*$servername = "localhost";
$username = "fdlogwrite";
$dbpassword = "adminpassword";*/

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $rd_username, $rd_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	//Field Day Setup Options
	$stmt = $conn->prepare("SELECT config_name, small_string FROM fd_config WHERE category = 'fd_setup' or category = 'gota'");
    $stmt->execute();
	$setup_out = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'small_string', 'config_name');
	//Bonus Points
	$stmt = $conn->prepare("SELECT config_name, number FROM fd_config WHERE category = 'bonus'");
    $stmt->execute();
	$bonus_numbers = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'number', 'config_name');
	$stmt = $conn->prepare("SELECT config_name FROM fd_config WHERE category = 'bonus' and number = 1");
    $stmt->execute();
	$bonus_out = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'config_name');
	//Power Sources
	$stmt = $conn->prepare("SELECT config_name, number FROM fd_config WHERE category = 'power' and number = 1");
    $stmt->execute();
	$power_out = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'config_name');
	//Total CW Contacts
	$stmt = $conn->prepare("SELECT COUNT(*) FROM logbook WHERE mode = 'CW'");
    $stmt->execute();
	$cw_qso = $stmt->fetch();
	$cw_qso = $cw_qso[0];
	$cw_points = $cw_qso * 2;
	//Total Digital Contacts
	$stmt = $conn->prepare("SELECT COUNT(*) FROM logbook WHERE mode = 'Digital'");
    $stmt->execute();
	$dig_qso = $stmt->fetch();
	$dig_qso = $dig_qso[0];
	$dig_points = $dig_qso * 2;
	//Total Voice Contacts
	$stmt = $conn->prepare("SELECT COUNT(*) FROM logbook WHERE mode = 'Phone'");
    $stmt->execute();
	$pho_qso = $stmt->fetch();
	$pho_qso = $pho_qso[0];
	$pho_points = $pho_qso;
	//Total QSO Points
	$total_qso_pts= $cw_points + $dig_points + $pho_points;
	//Max Power
	$stmt = $conn->prepare("SELECT MAX(power) FROM logbook");
    $stmt->execute();
	$max_power = $stmt->fetch();
	$max_power = $max_power[0];
	//Power Multiplier
	if ($max_power <= 5 && !in_array('commercial', $power_out) && !in_array('generator', $power_out)) {
		$power_multiplier = 5;
	} elseif ($max_power >= 150) {
		$power_multiplier = 1;
	} else {
		$power_multiplier = 2;
	}
	//QSO Score
	$qso_score = $total_qso_pts * $power_multiplier;
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (!empty($_REQUEST['submit'])) {
		try {
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $wr_username, $wr_password);
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
	} elseif (!empty($_REQUEST['power_reset'])) {
		try {
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $wr_username, $wr_password);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $conn->prepare("UPDATE fd_config SET number = '0' WHERE category = 'power'");
			$stmt->execute();
		} catch(PDOException $e) {
			echo "Connection failed: " . $e->getMessage();
		}
	} elseif (!empty($_REQUEST['bonus_reset'])) {
		try {
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $wr_username, $wr_password);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $conn->prepare("UPDATE fd_config SET number = '0' WHERE category = 'bonus'");
			$stmt->execute();
		} catch(PDOException $e) {
			echo "Connection failed: " . $e->getMessage();
		}
	}
	echo '<META http-equiv="refresh" content="0;URL=post_fd.php">';
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
						<form id="postfd" action='. $_SERVER["PHP_SELF"].' method="POST">';?>
						Number of participants:<input type="number" style="width:55px;" name="participants"><br>
						<fieldset>
							<legend>Power Sources</legend>
							<input type="checkbox" name="power[]" value="commercial">Commercial Mains 
							<input type="checkbox" name="power[]" value="generator">Generator 
							<input type="checkbox" name="power[]" value="battery">Battery<br>
							<input type="checkbox" name="power[]" value="solar">Solar 
							<input type="checkbox" name="power[]" value="wind">Wind  
							<input type="checkbox" name="power[]" value="methane">Methane 
							<input type="checkbox" name="power[]" value="water">Water<br>
							Other power (Comma separated list):<input type="text" name="other_power"><br>
							<input type="submit" name="power_reset" value="Reset Power Sources">
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
							<input type="checkbox" name="messages" value="formal_mesgs">Handled formal NTS/ICS-213 messages. Number of messages handled:<input type="number" style="width:45px;text-align:right;" name="number_messages"><br>
							<input type="checkbox" name="bonus[]" value="elected_official">Site visited by an invited elected official<br>
							<input type="checkbox" name="bonus[]" value="agency_official">Site visited by an invited served agency official<br>
							<input type="checkbox" name="bonus[]" value="educational_activity">Site hosted an educational activity<br>
							<input type="checkbox" name="youth" value="youth_participation">Youth Participation. Number of youth at site:
							<input type="number" style="width:45px;text-align:right;" name="number_youth"><br>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Number of youth that completed at least 1 QSO:<input type="number" style="width:45px;text-align:right;" name="number_youth_qso"><br>
							<input type="submit" name="bonus_reset" value="Reset Bonuses">
						</fieldset>
						<span style="float: right">
							<input type="submit" name="submit" value="Submit">
						</span>
						</form><!--';-->
						<?php
						echo '<br>Formatted output to go here soon.';
						echo '<div>
						<ol>
							<li>Field Day Call:	'.$setup_out['fd_callsign'].'</li>
							<li>Club or Group Name:	'.$setup_out['club_name'].'</li>
							<li>Number of Participants:	'.$bonus_numbers['participants'].'</li>
							<li>Transmitter Class:	'.preg_replace('/\D/', '', $setup_out['fd_class']).'</li>
							<li>Entry Class:	'.preg_replace('/\d/', '', $setup_out['fd_class']).'</li>
							<br>
							<li>Power Sources Used:
								<ul>';
								foreach($power_out as $source) {
									switch($source) {
										case "battery":
											$source_formated = "Battery";
											break;
										case "commercial":
											$source_formated = "Commercial Mains";
											break;
										case "generator":
											$source_formated = "Generator";
											break;
										case "solar":
											$source_formated = "Solar";
											break;
										default:
											$source_formated ="Other. Please list on summary sheet";
											break;			
									}
									echo '<li>'.$source_formated.'</li>';
								}
								echo'</ul></li><br>
							<li>ARRL Section:	'.$setup_out['fd_section'].'</li>
							<br>
							<li> Total CW QSOs:	'.$cw_qso.'&nbsp;Points:	'.$cw_points.'</li>
							<li> Total Digital QSOs:	'.$dig_qso.'&nbsp;Points:	'.$dig_points.'</li>
							<li> Total Phone QSOs:	'.$pho_qso.'&nbsp;Points:	'.$pho_points.'</li>
							<li> Total QSO Points:	'.$total_qso_pts.'</li>
							<li> Max Power Used:	'.$max_power.' Watts</li>
							<li> Power Multiplier:	x '.$power_multiplier.'</li>
							<li> Claimed QSO Score:	'.$qso_score.'</li>
							<li> Bonus Points:
							<ul>';
								foreach($bonus_out as $type) {
									echo '<li>'.$type.'</li>';
								}
								echo'</ul></li><br>
							<li> I/We have observed all competition rules as well as all
								regulations for amateur radio in my/our country. My/Our
								report is correct and true to the best of my/our knowledge.
								I/We agree to be bound by the decisions of the ARRL
								Awards Committee.
								<ul>
								
								</ul>
							</li><br>
						</ol>
						</div>';
					} else {
						echo '<h2>Admins only please!</h2>';
					}?>
				</div>
			</div>
		</div>
	</div>
</body>
</html>