<?php
$fd_callsign = $fd_section = $fd_class = $gota_callsign = $admin_password = $club_name = $repeat_password = $password = $count = '';
$fd_callErr = $fd_sectionErr = $fd_classErr = $admin_passErr1 = $admin_passErr2 = '';
$servername = "localhost";
$username_wr = "fdlogwrite";
$dbpassword_wr = "adminpassword";

$valid_sections = array('CT', 'EMA', 'ME', 'NH', 'RI', 'VT', 'WMA', 'ENY', 'NLI', 'NNJ', 'NNY', 'SNJ', 'WNY', 'DE', 'EPA', 'MDC', 'WPA', 'AL', 'GA', 'KY', 'NC', 'NFL', 'SC', 'SFL', 'WCF', 'TN', 'VA', 'PR', 'VI', 'AR', 'LA', 'MS', 'NM', 'NTX', 'OK', 'STX', 'WTX', 'EB', 'LAX', 'ORG', 'SB', 'SCV', 'SDG', 'SF', 'SJV', 'SV', 'PAC', 'AZ', 'EWA', 'ID', 'MT', 'NV', 'OR', 'UT', 'WWA', 'WY', 'AK', 'MI', 'OH', 'WV', 'IL', 'IN', 'WI', 'CO', 'IA', 'KS', 'MN', 'MO', 'NE', 'ND', 'SD', 'MAR', 'NL', 'QC', 'ONE', 'ONN', 'ONS', 'GTA', 'MB', 'SK', 'AB', 'BC', 'NT', 'DX');

//log in as fdlogwrite
$isReady = 0;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$gota_callsign = test_input($_POST["gota_callsign"]);
	$club_name = test_input($_POST["club_name"]);
	if (empty($_POST["fd_callsign"]) or empty($_POST["fd_section"]) or empty($_POST["fd_class"]) or empty($_POST["admin_password"]) or ($_POST["repeat_password"] !== $_POST["admin_password"])) {
		$isReady = 0;
	} else {
		$isReady = 1;
	}
	if (empty($_POST["fd_callsign"])) {
		$fd_callErr = "Field day call sign is required";
	} else {
		$fd_callsign = strtoupper(test_input($_POST["fd_callsign"]));
	}

	if (empty($_POST["fd_section"])) {
		$fd_sectionErr = "Section is required";
	}elseif (!in_array(strtoupper($_POST["fd_section"]), $valid_sections)){
		$fd_sectionErr = "That is not a valid section!";
	}else {
		$fd_section = strtoupper(test_input($_POST["fd_section"]));
	}

	if (empty($_POST["fd_class"])) {
		$fd_classErr = "Class Required";
	} else {
		$fd_class = strtoupper(test_input($_POST["fd_class"]));
		
	}
	if (empty($_POST["admin_password"] or $_POST["repeat_password"])) {
		$admin_passErr1 = "Password required!";
	} else {
		$password1 = $_POST["admin_password"];
		$password2 = $_POST["repeat_password"];
		if ($password1 === $password2) {
			$pass_options = ['cost' => 12];
			$password = password_hash($_POST["admin_password"], PASSWORD_BCRYPT, $pass_options);
		}else {
			$admin_passErr2 = "Passwords do not match!";
			}
		}
}
function test_input($data) {
$data = trim($data);
$data = htmlspecialchars($data);
return $data;
}
if ($isReady === 1) {	
	try {
		$conn = new PDO("mysql:host=$servername;dbname=fdlogdb", $username_wr, $dbpassword_wr);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		//Update FD callsign:
		$stmt = $conn->prepare("UPDATE fd_config SET small_string='$fd_callsign' WHERE config_name='fd_callsign'");
		$stmt->execute();
		$stmt = $conn->prepare("SELECT COUNT(config_name) FROM fd_config WHERE config_name = 'fd_section'");
		$stmt->execute();
		$count = $stmt->fetch();
		//Inset/update field day section
		if ($count[0] > 0) {
			$stmt = $conn->prepare("UPDATE fd_config SET small_string='$fd_section' WHERE config_name='fd_section'");
			$stmt->execute();
		} else {
			$stmt = $conn->prepare("INSERT INTO fd_config (config_name, small_string) VALUES ('fd_section', '$fd_section')");
			$stmt->execute();
		}
		//Insert/update field day class
		$stmt = $conn->prepare("SELECT COUNT(config_name) FROM fd_config WHERE config_name = 'fd_class'");
		$stmt->execute();
		$count = $stmt->fetch();
		if ($count[0] > 0) {
			$stmt = $conn->prepare("UPDATE fd_config SET small_string='$fd_class' WHERE config_name='fd_class'");
			$stmt->execute();
		} else {
			$stmt = $conn->prepare("INSERT INTO fd_config (config_name, small_string) VALUES ('fd_class', '$fd_class')");
			$stmt->execute();
		}
		//Insert/update GOTA callsign
		$stmt = $conn->prepare("SELECT COUNT(config_name) FROM fd_config WHERE config_name = 'gota_callsign'");
		$stmt->execute();
		$count = $stmt->fetch();
		if ($count[0] > 0) {
			$stmt = $conn->prepare("UPDATE fd_config SET small_string='$gota_callsign' WHERE config_name='gota_callsign'");
			$stmt->execute();
		} else {
			$stmt = $conn->prepare("INSERT INTO fd_config (config_name, small_string) VALUES ('gota_callsign', '$gota_callsign')");
			$stmt->execute();
		}
		//Club Name:
		$stmt = $conn->prepare("UPDATE fd_config SET small_string='$club_name' WHERE config_name='club_name'");
		$stmt->execute();
		//Update Admin Password
		$stmt = $conn->prepare("UPDATE users SET password='$password' WHERE uuid=1");
		$stmt->execute();
	}

	catch(PDOException $e)
		{
		echo "Connection failed: " . $e->getMessage();
		}
	
$conn=null;
}
?>