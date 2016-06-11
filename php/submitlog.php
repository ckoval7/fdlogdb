<?php
$servername = "localhost";
$dbusername = "fdlogwrite";
$dbpassword = "adminpassword";
$dupeErr = $sectionErr = "";

$valid_sections = array('CT', 'EMA', 'ME', 'NH', 'RI', 'VT', 'WMA', 'ENY', 'NLI', 'NNJ', 'NNY', 'SNJ', 'WNY', 'DE', 'EPA', 'MDC', 'WPA', 'AL', 'GA', 'KY', 'NC', 'NFL', 'SC', 'SFL', 'WCF', 'TN', 'VA', 'PR', 'VI', 'AR', 'LA', 'MS', 'NM', 'NTX', 'OK', 'STX', 'WTX', 'EB', 'LAX', 'ORG', 'SB', 'SCV', 'SDG', 'SF', 'SJV', 'SV', 'PAC', 'AZ', 'EWA', 'ID', 'MT', 'NV', 'OR', 'UT', 'WWA', 'WY', 'AK', 'MI', 'OH', 'WV', 'IL', 'IN', 'WI', 'CO', 'IA', 'KS', 'MN', 'MO', 'NE', 'ND', 'SD', 'MAR', 'NL', 'QC', 'ONE', 'ONN', 'ONS', 'GTA', 'MB', 'SK', 'AB', 'BC', 'NT', 'DX');

function test_input($data) {
	$data = trim($data);
	$data = htmlspecialchars($data);
	$data = strtoupper($data);
	return $data;
	}

if (!empty($_POST['band']) or !empty($_POST['mode'])) {
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$_SESSION['dbband'] = $_POST["band"];
		if (!empty($_POST["band"]) && $_POST["band"] == 125) {
			$band = "1.25m";
		} elseif (!empty($_POST["band"]) && $_POST["band"] == 247) {
			$band = "Satellite";
		} else{
			$band = $_POST["band"];
		}
		$_SESSION['band'] = $_POST['band'];
		$_SESSION['mode'] = $_POST["mode"];
		$_SESSION['power'] = preg_replace('/\D/', '', $_POST["power"]);
		if (!empty($_POST['natural_power'])) {
			$_SESSION['natural_power'] = 1;
		} else {
			$_SESSION['natural_power'] = '';
		}
		try {
			$conn = new PDO("mysql:host=$servername;dbname=fdlogdb", $dbusername, $dbpassword);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$stmt = $conn->prepare("INSERT INTO active_stations (user_id, band, mode, station_id) VALUES (:uuid, :band, :mode, 2)");
			$stmt->bindParam(':uuid', $_SESSION['uuid']);
			$stmt->bindParam(':band', $_POST["band"]);
			$stmt->bindParam(':mode', $_POST["mode"]);
			$stmt->execute();
			$stmt = $conn->prepare("SELECT session_id FROM active_stations ORDER BY session_id DESC LIMIT 1");
			$stmt->execute();
			$session_id = $stmt->fetch();
			$_SESSION['session_id']= $session_id[0];
		} catch(PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
		$conn=null;
	}
}
if (!empty($_POST['exchange'])) {
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$exchange = explode(" ", $_POST['exchange'], 3);
		if (!empty($exchange[0])) {$callsign = test_input($exchange[0]);} else {$callsign = '';}
		if (!empty($exchange[1])) {$operating_class = test_input($exchange[1]);} else {$operating_class = '';}
		if (!empty($exchange[2])) {$section = test_input($exchange[2]);} else {$section = '';}
		try {
			$dbband = $_SESSION['dbband'];
			$mode = $_SESSION['mode'];
			$power = $_SESSION['power'];
			$conn = new PDO("mysql:host=$servername;dbname=fdlogdb", $dbusername, $dbpassword);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $conn->prepare("SELECT COUNT(*) FROM logbook WHERE callsign = :callsign and band = :band and mode = :mode");
			$stmt->bindParam(':callsign', $callsign);
			$stmt->bindParam(':band', $dbband);
			$stmt->bindParam(':mode', $mode);
			$stmt->execute();
			$count = $stmt->fetch();
			if ($count[0] > 0) {
				$dupeErr = "Error! Dupe!";
			} elseif (!in_array($section, $valid_sections)){
				$sectionErr = "That is not a valid section!";
			} elseif (!preg_match('/\d{1,2}+[abcdefABCDEF]/', $operating_class)) {
				$sectionErr = "That is not a valid class!";
			} elseif (isset($callsign) and isset($operating_class)and isset($section)) {
				$stmt = $conn->prepare("INSERT INTO logbook(logger_id, callsign, operating_class, section, band, mode, power, natural_power, session_id) VALUES (:loggerid, :callsign, :opclass, :section, :band, :mode, :power, :natural_power, :session_id)");
				$stmt->bindParam(':loggerid', $_SESSION['uuid']);
				$stmt->bindParam(':callsign', $callsign);
				$stmt->bindParam(':opclass', $operating_class);
				$stmt->bindParam(':section', $section);
				$stmt->bindParam(':band', $_SESSION['dbband']);
				$stmt->bindParam(':mode', $_SESSION['mode']);
				$stmt->bindParam(':power', $_SESSION['power']);
				$stmt->bindParam(':natural_power', $_SESSION['natural_power']);
				$stmt->bindParam(':session_id', $_SESSION['session_id']);
				
				$stmt->execute();
				$conn=null;
			} else {
				$dupeErr = "Incomplete exchange. Please enter call sign, class, and section.";
			}
		} catch(PDOException $e) {
			echo "Connection failed: " . $e->getMessage();
		}
	}
}
?>