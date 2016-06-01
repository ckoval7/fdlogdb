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
		$_SESSION['band'] = $_POST["band"];
		$_SESSION['mode'] = $_POST["mode"];
		$_SESSION['power'] = preg_replace('/\D/', '', $_POST["power"]);
	}
}
if (!empty($_POST['exchange'])) {
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$exchange = explode(" ", $_POST['exchange'], 3);
		if (!empty($exchange[0])) {$callsign = test_input($exchange[0]);}
		if (!empty($exchange[1])) {$operating_class = test_input($exchange[1]);}
		if (!empty($exchange[2])) {$section = test_input($exchange[2]);}
		try {
			$band = $_SESSION['band'];
			$mode = $_SESSION['mode'];
			$power = $_SESSION['power'];
			$conn = new PDO("mysql:host=$servername;dbname=fdlogdb", $dbusername, $dbpassword);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $conn->prepare("SELECT * FROM logbook WHERE callsign = :callsign and band = :band and mode = :mode");
			$stmt->bindParam(':callsign', $callsign);
			$stmt->bindParam(':band', $band);
			$stmt->bindParam(':mode', $mode);
			$stmt->execute();
			$count = $stmt->rowCount();
			if ($count > 0) {
				$dupeErr = "Error! Dupe!";
			} elseif (!in_array($section, $valid_sections)){
				$sectionErr = "That is not a valid section!";
			} elseif (isset($callsign) and isset($operating_class)and isset($section)) {
				$stmt = $conn->prepare("INSERT INTO logbook(logger_id, callsign, operating_class, section, band, mode, power)VALUES (:loggerid, :callsign, :opclass, :section, :band, :mode, :power)");
				$stmt->bindParam(':loggerid', $_SESSION['uuid']);
				$stmt->bindParam(':callsign', $callsign);
				$stmt->bindParam(':opclass', $operating_class);
				$stmt->bindParam(':section', $section);
				$stmt->bindParam(':band', $_SESSION['band']);
				$stmt->bindParam(':mode', $_SESSION['mode']);
				$stmt->bindParam(':power', $_SESSION['power']);
				
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