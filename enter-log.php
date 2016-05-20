<?php session_start(); 


$servername = "localhost";
$dbusername = "fdlogwrite";
$dbpassword = "adminpassword";
$dupeErr = "";
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
			$sql = $conn->prepare("SELECT * FROM logbook WHERE callsign = '$callsign' and band = '$band' and mode = '$mode'");
			$sql->execute();
			$count = $sql->rowCount();
			if ($count > 0) {
				$dupeErr = "Error! Dupe!";
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
<!DOCTYPE html>
<html>
<head>
	<title>Field Day Log</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="Corey Koval, K3CPK">
	<meta name="application-name" content="Field Day Logging Database" />
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<div id="outer_wrapper" class="grid">
		<header id="site_header" class="header">
			<div id="page_top" class="row">
				<?php include 'header.php';?>
			</div>
		</header>
		<hr>
		<div class="row">
			<div class="col-2">
				<?php include 'navbar.php';?>
			</div>
			<div class="col-10"><br>
			<?php
			if (isset($_SESSION['priv'])){
				echo'
				<form id="sublog" method="POST" action='.$_SERVER["PHP_SELF"].'>';
				if (empty($_SESSION['band']) or empty($_SESSION['mode'])) {
					echo '
					<span>Choose a band:
						<select name="band">
							<option value="160m">160m</option>
							<option value="80m">80m</option>
							<option value="40m">40m</option>
							<option value="20m">20m</option>
							<option value="15m">15m</option>
							<option value="10m">10m</option>
							<option value="VHF">VHF</option>
						</select>
					</span>&nbsp;
					<span>Choose a Mode:
						<select name="mode">
							<option value="CW/Morse">CW/Morse</option>
							<option value="Phone" selected>Phone</option>
							<option value="Digital">Digital</option>
						</select>
					</span><br>
					<span>Power: 
						<input type="text" name="power"><b>W</b><br>
					</span><br>
						<input type="submit" /><br>';
				} else {
					echo '
						<h2>'.$_SESSION['band'].'&nbsp;'.$_SESSION['mode'].'</h2>
						Please enter the whole exchange on one line then press enter. For example:<br>
						"w3uas 3a mdc"<br>
						<b>Exchange:</b><br>
						<input type="text" id="exchange" name="exchange" autofocus="autofocus"/><span class="error">* '. $dupeErr.'</span><br>
						<input type="submit" /><br><br>
						<b>When you are done with '.$_SESSION['band'].'&nbsp;'.$_SESSION['mode'].' please click <a href="/view-log.php">here.</a></b>';
				}
				echo '</form>
				<hr>';
				include '/php/displayloguser.php';
			} else {
				echo '<h2>Sign in to use this page</h2>';
			} ?>
			</div>
		</div>
	</div>
<?php include '/js/scripthtml.php'; ?>
</body>
</html>