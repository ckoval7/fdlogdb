<?php
// Start the session
session_start();
include '../php/db_passwords.php';
//select first_name, last_name, COUNT(*) from gota_log group by last_name, first_name;
$agency_pts = $sm_msg_pts = $education_pts = $elected_pts = $mesg_pts = $info_pts = $media_pts = $public_pts = $safety_pts = $social_pts = $w1aw_pts = $youth_pts = $emxmttrpoints = $natural_pts = $satellite_pts = 0;

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
	$stmt = $conn->prepare("SELECT config_name FROM fd_config WHERE category = 'bonus' and number >= 1");
    $stmt->execute();
	$bonus_out = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'config_name');
	//Power Sources
	$stmt = $conn->prepare("SELECT config_name, number FROM fd_config WHERE category = 'power' and number = 1");
    $stmt->execute();
	$power_out = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'config_name');
	//Natural Power Contacts
	$stmt = $conn->prepare("SELECT COUNT(*) FROM logbook WHERE natural_power = 1");
    $stmt->execute();
	$natural_qso = $stmt->fetch();
	$natural_qso = $natural_qso[0];
	//Satellite Contacts
	$stmt = $conn->prepare("SELECT COUNT(*) FROM logbook WHERE band = 247");
    $stmt->execute();
	$satellite_qso = $stmt->fetch();
	$satellite_qso = $satellite_qso[0];
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
						if(!empty($setup_out['gota_callsign'])) {
							$gota_call = "GOTA Station Call: ".$setup_out['gota_callsign'];
						} else {
							$gota_call = "";
						}
						echo '<br>Formatted output to go here soon.';
						echo '<div>
						<ol>
							<li>Field Day Call:	'.$setup_out['fd_callsign'].'&nbsp;'.$gota_call.'</li>
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
								if (!in_array('commercial', $power_out)) {
									$numTransmitters = preg_replace('/\D/', '', $setup_out['fd_class']);
									$emxmttrpoints = min($numTransmitters, 20) * 100;
									echo '<li>'.$emxmttrpoints.' 100% Emergency Power ('.$numTransmitters.' xmttrs)</li>';
								} else {
									$emxmttrpoints = 0;
								}
								if(!empty($natural_qso) && $natural_qso > 4) {
									$natural_pts = 100;
									echo '<li>100 Natural power QSOs completed ('.$natural_qso.')</li>';
								}
								if(!empty($satellite_qso) && $satellite_qso > 0) {
									$satellite_pts = 100;
									echo '<li>100 Satellite QSO(s) completed ('.$satellite_qso.')</li>';
								}
								foreach($bonus_out as $type) {
									//echo '<li>'.$type.'</li>';
									switch($type) {
										case "agency_official":
											$agency_pts = 100;
											$type_formated = "100 Site Visit by invited served agency official";
											break;
										case "arrl_sm_mesg":
											$sm_msg_pts = 100;
											$type_formated = "100 Message to ARRL SM/SEC";
											break;
										case "educational_activity":
											$education_pts = 100;
											$type_formated = "100 Educational Activity Bonus";
											break;
										case "elected_official":
											$elected_pts = 100;
											$type_formated = "100 Site Visit by invited elected official";
											break;
										case "formal_mesgs":
											$mesg_pts = min($bonus_numbers['formal_mesgs'], 10) * 10;
											$type_formated = $mesg_pts." NTS/ICS-213 messages handled (# ".$bonus_numbers['formal_mesgs'].")";
											break;
										case "info_booth":
											$info_pts = 100;
											$type_formated = "100 Information Booth";
											break;
										case "media":
											$media_pts = 100;
											$type_formated = "100 Media Publicity";
											break;
										case "public_place":
											$public_pts = 100;
											$type_formated = "100 Set-up in Public Place";
											break;
										case "safety_officer":
											$safety_pts = 100;
											$type_formated = "100 Safety Officer Bonus";
											break;
										case "social_media":
											$social_pts = 100;
											$type_formated = "100 Social Media Bonus";
											break;
										case "w1aw_mesg":
											$w1aw_pts = 100;
											$type_formated = "100 W1AW Field Day Message";
											break;
										case "youth_qso":
											$youth_pts = min((20*$bonus_numbers['youth_qso']), 100);
											$type_formated = $youth_pts." Youth Element achieved";
											break;
										default:
											$type_formated = '';
											break;			
									}
									echo '<li>'.$type_formated.'</li>';
								}
								$total_bonus = $satellite_pts + $natural_pts + $agency_pts + $sm_msg_pts + $education_pts + $elected_pts + $mesg_pts + $info_pts + $media_pts + $public_pts + $safety_pts + $social_pts + $w1aw_pts + $youth_pts + $emxmttrpoints;
								$total_score = $total_bonus + $qso_score;
								echo '<br><li>Total bonus points claimed: '.$total_bonus.'</li><br>
								<li>Total Claimed Score: '.$total_score.'</li><br>';
								?></ul></li>
							<li>Submit your log via <a href="http://www.b4h.net/cabforms/">http://www.b4h.net/cabforms/</a> for a 50 point additional bonus!</li>
							<li> I/We have observed all competition rules as well as all
								regulations for amateur radio in my/our country. My/Our
								report is correct and true to the best of my/our knowledge.
								I/We agree to be bound by the decisions of the ARRL
								Awards Committee.<br>
								Submitted by:
								<ul>
									<li>Date</li>
									<li>Submitter\'s Callsign</li>
									<li>Submitter or club\'s Address</li>
									<li>Submitter\'s Email Address</li>
								</ul>
							</li>
							<li>
							<?php
                                $cw160qso = $cw80qso = $cw40qso = $cw20qso = $cw15qso = $cw10qso = $cw6qso = $cw2qso = $cw125qso = $cwsatqso = $cwgotaqso = $dig160qso = $dig80qso = $dig40qso = $dig20qso = $dig15qso = $dig10qso = $dig6qso = $dig2qso = $dig125qso = $digsatqso = $diggotaqso = $pho160qso = $pho80qso = $pho40qso = $pho20qso = $pho15qso = $pho10qso = $pho6qso = $pho2qso = $pho125qso = $phosatqso = $phogotaqso = $cw160pwr = $cw80pwr = $cw40pwr = $cw20pwr = $cw15pwr = $cw10pwr = $cw6pwr = $cw2pwr = $cw125pwr = $cwsatpwr = $cwgotapwr = $dig160pwr = $dig80pwr = $dig40pwr = $dig20pwr = $dig15pwr = $dig10pwr = $dig6pwr = $dig2pwr = $dig125pwr = $digsatpwr = $diggotapwr = $pho160pwr = $pho80pwr = $pho40pwr = $pho20pwr = $pho15pwr = $pho10pwr = $pho6pwr = $pho2pwr = $pho125pwr = $phosatpwr = $phogotapwr = 0;
								try {
									$conn = new PDO("mysql:host=$servername;dbname=$dbname", $rd_username, $rd_password);
									$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
									$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
									$stmt = $conn->prepare("SELECT band, mode, COUNT(*) as qso, MAX(power) as power FROM logbook GROUP BY mode, band");
									$stmt->execute();
									// set the resulting array to associative
									foreach($stmt->fetchAll() as $row) {
										switch($row['band']) {
                                            case 160:
                                                switch($row['mode']) {
                                                    case "Digital":
                                                        $dig160qso = $row['qso'];
                                                        $dig160pwr = $row['power'];
                                                        break;
                                                    case "CW":
                                                        $cw160qso = $row['qso'];
                                                        $cw160pwr = $row['power'];
                                                        break;
                                                    case "Phone":
                                                        $pho160qso = $row['qso'];
                                                        $pho160pwr = $row['power'];
                                                        break;
                                                }
                                                break;
                                            case 80:
                                                switch($row['mode']) {
                                                    case "Digital":
                                                        $dig80qso = $row['qso'];
                                                        $dig80pwr = $row['power'];
                                                        break;
                                                    case "CW":
                                                        $cw80qso = $row['qso'];
                                                        $cw80pwr = $row['power'];
                                                        break;
                                                    case "Phone":
                                                        $pho80qso = $row['qso'];
                                                        $pho80pwr = $row['power'];
                                                        break;
                                                }
                                                break;
                                            case 40:
                                                switch($row['mode']) {
                                                    case "Digital":
                                                        $dig40qso = $row['qso'];
                                                        $dig40pwr = $row['power'];
                                                        break;
                                                    case "CW":
                                                        $cw40qso = $row['qso'];
                                                        $cw40pwr = $row['power'];
                                                        break;
                                                    case "Phone":
                                                        $pho40qso = $row['qso'];
                                                        $pho40pwr = $row['power'];
                                                        break;
                                                }
                                                break;
                                            case 20:
                                                switch($row['mode']) {
                                                    case "Digital":
                                                        $dig20qso = $row['qso'];
                                                        $dig20pwr = $row['power'];
                                                        break;
                                                    case "CW":
                                                        $cw20qso = $row['qso'];
                                                        $cw20pwr = $row['power'];
                                                        break;
                                                    case "Phone":
                                                        $pho20qso = $row['qso'];
                                                        $pho20pwr = $row['power'];
                                                        break;
                                                }
                                                break;
                                            case 15:
                                                switch($row['mode']) {
                                                    case "Digital":
                                                        $dig15qso = $row['qso'];
                                                        $dig15pwr = $row['power'];
                                                        break;
                                                    case "CW":
                                                        $cw15qso = $row['qso'];
                                                        $cw15pwr = $row['power'];
                                                        break;
                                                    case "Phone":
                                                        $pho15qso = $row['qso'];
                                                        $pho15pwr = $row['power'];
                                                        break;
                                                }
                                                break;
                                            case 10:
                                                switch($row['mode']) {
                                                    case "Digital":
                                                        $dig10qso = $row['qso'];
                                                        $dig10pwr = $row['power'];
                                                        break;
                                                    case "CW":
                                                        $cw10qso = $row['qso'];
                                                        $cw10pwr = $row['power'];
                                                        break;
                                                    case "Phone":
                                                        $pho10qso = $row['qso'];
                                                        $pho10pwr = $row['power'];
                                                        break;
                                                }
                                            case 6:
                                                switch($row['mode']) {
                                                    case "Digital":
                                                        $dig6qso = $row['qso'];
                                                        $dig6pwr = $row['power'];
                                                        break;
                                                    case "CW":
                                                        $cw6qso = $row['qso'];
                                                        $cw6pwr = $row['power'];
                                                        break;
                                                    case "Phone":
                                                        $pho6qso = $row['qso'];
                                                        $pho6pwr = $row['power'];
                                                        break;
                                                }
                                                break;
                                            case 2:
                                                switch($row['mode']) {
                                                    case "Digital":
                                                        $dig2qso = $row['qso'];
                                                        $dig2pwr = $row['power'];
                                                        break;
                                                    case "CW":
                                                        $cw2qso = $row['qso'];
                                                        $cw2pwr = $row['power'];
                                                        break;
                                                    case "Phone":
                                                        $pho2qso = $row['qso'];
                                                        $pho2pwr = $row['power'];
                                                        break;
                                                }
                                                break;
                                            case 125:
                                                switch($row['mode']) {
                                                    case "Digital":
                                                        $dig125qso = $row['qso'];
                                                        $dig125pwr = $row['power'];
                                                        break;
                                                    case "CW":
                                                        $cw125qso = $row['qso'];
                                                        $cw125pwr = $row['power'];
                                                        break;
                                                    case "Phone":
                                                        $pho125qso = $row['qso'];
                                                        $pho125pwr = $row['power'];
                                                        break;
                                                }
                                                break;
                                            case 247:
                                                switch($row['mode']) {
                                                    case "Digital":
                                                        $digsatqso = $row['qso'];
                                                        $digsatpwr = $row['power'];
                                                        break;
                                                    case "CW":
                                                        $cwsatqso = $row['qso'];
                                                        $cwsatpwr = $row['power'];
                                                        break;
                                                    case "Phone":
                                                        $phosatqso = $row['qso'];
                                                        $phosatpwr = $row['power'];
                                                        break;
                                                }
                                                break;
                                            default:
                                                echo '?????';
                                                break;
                                        }
                                        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $rd_username, $rd_password);
                                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                                        $stmt = $conn->prepare("SELECT mode, COUNT(*) as qso, MAX(power) as power FROM gota_log GROUP BY mode");
                                        $stmt->execute();
                                        // set the resulting array to associative
                                        foreach($stmt->fetchAll() as $row) {
                                            switch($row['mode']) {
                                                case "Digital":
                                                    $diggotaqso = $row['qso'];
                                                    $diggotapwr = $row['power'];
                                                    break;
                                                case "CW":
                                                    $cwgotaqso = $row['qso'];
                                                    $cwgotapwr = $row['power'];
                                                    break;
                                                case "Phone":
                                                    $phogotaqso = $row['qso'];
                                                    $phogotapwr = $row['power'];
                                                    break;
                                            }
                                        }
                                        $total_cw_qso = $cw160qso + $cw80qso + $cw40qso + $cw20qso + $cw15qso + $cw10qso + $cw6qso + $cw2qso + $cw125qso + $cwsatqso;
                                        $total_dig_qso = $dig160qso + $dig80qso + $dig40qso + $dig20qso + $dig15qso + $dig10qso + $dig6qso + $dig2qso + $dig125qso + $digsatqso;
                                        $total_pho_qso = $pho160qso + $pho80qso + $pho40qso + $pho20qso + $pho15qso + $pho10qso + $pho6qso + $pho2qso + $pho125qso + $phosatqso;
                                        
									}
								}
								catch(PDOException $e) {
									echo "Error: " . $e->getMessage();
								}
								$conn = null;
							?>
							<table style='border: solid 1px black;'>
								<col>
								<colgroup span="2"></colgroup>
								<colgroup span="2"></colgroup>
								<tr><td rowspan="2"></td><th colspan="2" scope="colgroup">CW</th><th colspan="2" scope="colgroup">Digital</th><th colspan="2" scope="colgroup">Phone</th></tr>
								<tr><th scope="col">QSO</th><th scope="col">Power</th><th scope="col">QSO</th><th scope="col">Power</th><th scope="col">QSO</th><th scope="col">Power</th></tr>
								<tr><th scope="row">160M</th><td><?php echo $cw160qso; ?></td><td><?php echo $cw160pwr; ?></td><td><?php echo $dig160qso; ?></td><td><?php echo $dig160pwr; ?></td><td><?php echo $pho160qso; ?></td><td><?php echo $pho160pwr; ?></td></tr>
                                
								<tr><th scope="row">80M</th><td><?php echo $cw80qso; ?></td><td><?php  echo $cw80pwr; ?></td><td><?php echo $dig80qso; ?></td><td><?php echo $dig80pwr; ?></td><td><?php echo $pho80qso; ?></td><td><?php echo $pho80pwr; ?></td></tr>
                                
								<tr><th scope="row">40M</th><td><?php echo $cw40qso; ?></td><td><?php echo $cw40pwr; ?></td><td><?php echo $dig40qso; ?></td><td><?php echo $dig40pwr; ?></td><td><?php echo $pho40qso; ?></td><td><?php echo $pho40pwr; ?></td></tr>
                                
								<tr><th scope="row">20M</th><td><?php echo $cw20qso; ?></td><td><?php echo $cw20pwr; ?></td><td><?php echo $dig20qso; ?></td><td><?php echo $dig20pwr; ?></td><td><?php echo $pho20qso; ?></td><td><?php echo $pho20pwr; ?></td></tr>
                                
                                <tr><th scope="row">15M</th><td><?php echo $cw15qso; ?></td><td><?php echo $cw15pwr; ?></td><td><?php echo $dig15qso; ?></td><td><?php echo $dig15pwr; ?></td><td><?php echo $pho15qso; ?></td><td><?php echo $pho15pwr; ?></td></tr>
                                
								<tr><th scope="row">10M</th><td><?php echo $cw10qso; ?></td><td><?php echo $cw10pwr; ?></td><td><?php echo $dig10qso; ?></td><td><?php echo $dig10pwr; ?></td><td><?php echo $pho10qso; ?></td><td><?php echo $pho10pwr; ?></td></tr>
                                
								<tr><th scope="row">6M</th><td><?php echo $cw6qso; ?></td><td><?php echo $cw6pwr; ?></td><td><?php echo $dig6qso; ?></td><td><?php echo $dig6pwr; ?></td><td><?php echo $pho6qso; ?></td><td><?php echo $pho6pwr; ?></td></tr>
                                
								<tr><th scope="row">2M</th><td><?php echo $cw2qso; ?></td><td><?php echo $cw2pwr; ?></td><td><?php echo $dig2qso; ?></td><td><?php echo $dig2pwr; ?></td><td><?php echo $pho2qso; ?></td><td><?php echo $pho2pwr; ?></td></tr>
                                
								<tr><th scope="row">1.25M</th><td><?php echo $cw125qso; ?></td><td><?php echo $cw125pwr; ?></td><td><?php echo $dig125qso; ?></td><td><?php echo $dig125pwr; ?></td><td><?php echo $pho125qso; ?></td><td><?php echo $pho125pwr; ?></td></tr>
                                
								<tr><th scope="row">Satellite</th><td><?php echo $cwsatqso; ?></td><td><?php echo $cwsatpwr; ?></td><td><?php echo $digsatqso; ?></td><td><?php echo $digsatpwr; ?></td><td><?php echo $phosatqso; ?></td><td><?php echo $phosatpwr; ?></td></tr>
    
								<tr><th scope="row">GOTA</th><td><?php echo $cwgotaqso; ?></td><td><?php echo $cwgotapwr; ?></td><td><?php echo $diggotaqso; ?></td><td><?php echo $diggotapwr; ?></td><td><?php echo $phogotaqso; ?></td><td><?php echo $phogotapwr; ?></td></tr>
    
								<tr><th scope="row">Totals</th><td><?php echo $total_cw_qso; ?></td><td>CW</td><td><?php echo $total_dig_qso; ?></td><td>Digital</td><td><?php echo $total_pho_qso; ?></td><td>Phone</td></tr>
								</table>								
							</li>
							<br>
						</ol>
						</div><?php
					} else {
						echo '<h2>Admins only please!</h2>';
					}?>
				</div>
			</div>
		</div>
	</div>
</body>
</html>