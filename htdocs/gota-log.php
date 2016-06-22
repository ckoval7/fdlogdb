<?php session_start();
include 'php/db_passwords.php';
$dupeErr = $sectionErr = $pwrErr = $view_exchange = $firstnameErr = $lastnameErr = $callsignErr = $first_name = $last_name = $op_callsign = "";
$isReady = 0;

$uuid = $_SESSION['uuid'];

$valid_sections = array('CT', 'EMA', 'ME', 'NH', 'RI', 'VT', 'WMA', 'ENY', 'NLI', 'NNJ', 'NNY', 'SNJ', 'WNY', 'DE', 'EPA', 'MDC', 'WPA', 'AL', 'GA', 'KY', 'NC', 'NFL', 'SC', 'SFL', 'WCF', 'TN', 'VA', 'PR', 'VI', 'AR', 'LA', 'MS', 'NM', 'NTX', 'OK', 'STX', 'WTX', 'EB', 'LAX', 'ORG', 'SB', 'SCV', 'SDG', 'SF', 'SJV', 'SV', 'PAC', 'AZ', 'EWA', 'ID', 'MT', 'NV', 'OR', 'UT', 'WWA', 'WY', 'AK', 'MI', 'OH', 'WV', 'IL', 'IN', 'WI', 'CO', 'IA', 'KS', 'MN', 'MO', 'NE', 'ND', 'SD', 'MAR', 'NL', 'QC', 'ONE', 'ONN', 'ONS', 'GTA', 'MB', 'SK', 'AB', 'BC', 'NT', 'DX');

function test_input($data) {
	$data = trim($data);
	$data = htmlspecialchars($data);
	$data = strtoupper($data);
	return $data;
	}

if (!empty($_POST['band']) or !empty($_POST['mode']) or !empty($_POST["first"]) or !empty($_POST["last"])) {
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$_SESSION['gota_dbband'] = $_POST["band"];
		if (!empty($_POST["band"]) && $_POST["band"] == 125) {
			$band = "1.25m";
		} elseif (!empty($_POST["band"]) && $_POST["band"] == 247) {
			$band = "Satellite";
		} else{
			$band = $_POST["band"].'m';
		}
		if (empty($_POST["first"])) {
			$firstnameErr = "First name is required";
			$isReady = 0;
		} else {
			$_SESSION['gota_first_name'] = test_input($_POST["first"]);
		}
		if (empty($_POST["last"])) {
			$lastnameErr = "Lastname is required";
			$isReady = 0;
		} else {
			$_SESSION['gota_last_name'] = test_input($_POST["last"]);
		}
		if (empty($_POST["callsign"])) {
			$_SESSION['op_callsign'] = "NOCALL";
			$isReady = 0;
		} else {
			$_SESSION['op_callsign'] = test_input($_POST["callsign"]);
		}
		$_SESSION['gota_power'] = preg_replace('/\D/', '', $_POST["power"]);
		if (!empty($_SESSION['gota_power']) && $_SESSION['gota_power'] > 150) {
			$pwrErr = "A GOTA station cannot exceed 150W";
			//$isReady = 0;
		} elseif (empty($_SESSION['gota_power'])) {
			$pwrErr = "Please enter your transmitter's power";
		} else {
			//$isReady = 1;
			$_SESSION['gota_band'] = $band;
			$_SESSION['gota_mode'] = $_POST["mode"];
		}
/* 		if ($isReady == 1) {
			try {
				$conn = new PDO("mysql:host=$servername;dbname=$dbname", $wr_username, $wr_password);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				$stmt = $conn->prepare("INSERT INTO active_stations (user_id, band, mode, station_id) VALUES (:uuid, :band, :mode, 1)");
				$stmt->bindParam(':uuid', $_SESSION['uuid']);
				$stmt->bindParam(':band', $_POST["band"]);
				$stmt->bindParam(':mode', $_POST["mode"]);
				$stmt->execute();
				$stmt = $conn->prepare("SELECT session_id FROM active_stations WHERE user_id = '$uuid' ORDER BY session_id DESC LIMIT 1");
				$stmt->execute();
				$session_id = $stmt->fetch();
				$_SESSION['session_id']= $session_id[0];
			} catch(PDOException $e) {
				echo "Error: " . $e->getMessage();
			}
			$conn=null;
		} */	
	}
}
if (!empty($_POST['exchange'])) {
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$view_exchange = $_POST['exchange'];
		$exchange = explode(" ", $_POST['exchange'], 3);
		if (!empty($exchange[0])) {$callsign = test_input($exchange[0]);} else {$callsign = '';}
		if (!empty($exchange[1])) {$operating_class = test_input($exchange[1]);} else {$operating_class = '';}
		if (!empty($exchange[2])) {$section = test_input($exchange[2]);} else {$section = '';}
		try {
			$dbband = $_SESSION['gota_dbband'];
			$mode = $_SESSION['gota_mode'];
			$power = $_SESSION['gota_power'];
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $wr_username, $wr_password);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $conn->prepare("SELECT COUNT(*) FROM gota_log WHERE callsign = :callsign and band = :band and mode = :mode");
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
				$stmt = $conn->prepare("INSERT INTO gota_log(coach_id, callsign, operating_class, section, band, mode, power, first_name, last_name, op_callsign) VALUES (:coach_id, :callsign, :opclass, :section, :band, :mode, :power, :first, :last, :operator)");
				$stmt->bindParam(':coach_id', $_SESSION['uuid']);
				$stmt->bindParam(':callsign', $callsign);
				$stmt->bindParam(':opclass', $operating_class);
				$stmt->bindParam(':section', $section);
				$stmt->bindParam(':band', $_SESSION['gota_dbband']);
				$stmt->bindParam(':mode', $_SESSION['gota_mode']);
				$stmt->bindParam(':power', $_SESSION['gota_power']);
				$stmt->bindParam(':first', $_SESSION['gota_first_name']);
				$stmt->bindParam(':last', $_SESSION['gota_last_name']);
				$stmt->bindParam(':operator', $_SESSION['op_callsign']);
				
				$stmt->execute();
				$conn=null;
				$view_exchange = "";
			} else {
				$dupeErr = "Incomplete exchange. Please enter call sign, class, and section.";
			}
		} catch(PDOException $e) {
			echo "Connection failed: " . $e->getMessage();
		}
	}
}
$dupe_err="";
?>
<!DOCTYPE html>
<html>
<head>
	<title>GOTA Log</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="Corey Koval, K3CPK">
	<meta name="application-name" content="Field Day Logging Database" />
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script type="text/javascript" src="js/fdlog.js"></script>
</head>
<body  onload="startTime()">
	<div id="outer_wrapper" class="grid">
		<div class="row">
			<div id="menu" class="col-2">
				<?php include 'navbar.php'; ?>
			</div>
			<div id="header2" class="col-10">
				<?php include 'header.php'; ?>
				<div id="content">
					<?php
			if (!empty($_SESSION['priv'])){
				echo'
				<form id="sublog" method="POST" action='.$_SERVER["PHP_SELF"].' autocomplete="off">';
				if (empty($_SESSION['gota_band']) or empty($_SESSION['gota_mode'])) {
					?><b>First Name:</b><br>
						<input type="text" name="first" value="<?php echo $first_name; ?>">
						<span class="error">* <?php echo $firstnameErr;?></span><br>
						<b>Last Name:</b><br>
						<input type="text" name="last" value="<?php echo $last_name; ?>"/>
						<span class="error">* <?php echo $lastnameErr;?></span><br>
						Call Sign:<br>
						<input type="text" name="callsign" value="<?php echo $op_callsign; ?>" /><br><br>
					<span>Choose a band:
						<select name="band">
							<option value="160">160m</option>
							<option value="80">80m</option>
							<option value="40">40m</option>
							<option value="20">20m</option>
							<option value="15">15m</option>
							<option value="10">10m</option>
							<option value="6">6m</option>
							<option value="2">2m</option>
							<option value="125">1.25m</option>
							<option value="247">Satellite</option>
						</select>
					</span>&nbsp;
					<span>Choose a Mode:
						<select name="mode">
							<option value="CW">CW/Morse</option>
							<option value="Phone" selected>Phone</option>
							<option value="Digital">Digital</option>
						</select>
					</span><br>
					<span>Power: 
						<input type="text" name="power"><b>W</b><span class="error">* &nbsp; <?php echo $pwrErr; ?></span><br>
					</span><br>
						<input type="submit" value="Begin Session" /><br><?php
				} else {
					try {
						$conn = new PDO("mysql:host=$servername;dbname=$dbname", $rd_username, $rd_password);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
						$stmt = $conn->prepare("SELECT COUNT(*) FROM gota_log");
						$stmt->execute();
						$contacts = $stmt->fetch();
					} catch(PDOException $e) {
						echo "Error: " . $e->getMessage();
					}
					$conn=null;
					echo '
						<h2>'.$_SESSION['gota_band'].'&nbsp;-&nbsp;'.$_SESSION['gota_mode'].' - GOTA</h2>
						<h4>'.$contacts[0].'/500 GOTA contacts Logged</h4>
						Please enter the whole exchange on one line then press enter. For example:<br>
						"w3uas 3a mdc"<br>
						<b>Exchange:</b><br>
						<input type="text" list="callsigns" id="exchange" name="exchange" value="'.$view_exchange.'" autofocus="autofocus" onfocus="this.value = this.value;"/><span class="error">
                        <datalist id="callsigns">';
                        try {
                            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $rd_username, $rd_password);
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                            $stmt = $conn->prepare("SELECT callsign, band, mode FROM gota-log ORDER BY callsign");
                            $stmt->execute();

                            // set the resulting array to associative
                            foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                                if ($row['band'] == $_SESSION['gota_dbband'] && $row['mode'] == $_SESSION['gota_mode']) {
                                    $dupe_err = " - DUPE";
                                }
                                echo '<option value ="'.$row['callsign'].$dupe_err.'">'."\n";
                                $dupe_err = "";
                            }
                        }
                        catch(PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }
                        $conn = null;
                    echo '</datalist>
                    * '. $dupeErr.'</span><span class="error">'.$sectionErr.'</span><br>
						<input type="submit" value="Submit" /><br><br>
						<b>When you are done with '.$_SESSION['gota_band'].'&nbsp;'.$_SESSION['gota_mode'].' please click <a href="/php/gotalog.php">here.</a></b>';
				}
				echo '</form>
				<hr>';
				include 'php/paginate.php';
				$uuid = $_SESSION['uuid'];
				$_SESSION["key"] = "logid";
				$_SESSION["table"] = "gota_log";
				$_SESSION["page"] = "/gota-log.php";
				$table = "gota_log";

				$pages = paginate($table);

				echo "<table style='border: solid 1px black;'>";
				echo '<form id="guestbook" enctype="multipart/form-data" action="/php/delete.php" method="post">
				<tr><th>Call Sign</th><th>Class</th><th>Section</th><th>Band</th><th>Mode</th>';
				if (!empty($_SESSION['priv'])) {
						echo '<th><input type="submit" value="Delete" /></th>';
				}
				echo '</tr>';

				try {
					$offset = getPage($table);
					$conn = new PDO("mysql:host=$servername;dbname=$dbname", $rd_username, $rd_password);
					$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
					$stmt = $conn->prepare("SELECT callsign, operating_class, section, band, mode, logid FROM gota_log ORDER BY logid DESC LIMIT $offset, $limit");
					$stmt->execute();

					// set the resulting array to associative
					foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
						if (!empty($row['band']) && $row['band'] == 125) {
							$band = "1.25m";
						} elseif (!empty($row['band']) && $row['band'] == 247) {
							$band = "Satellite";
						} else{
							$band = $row['band']."m";
						}
						echo "<tr>";
						echo "<td style='width:150px;border:1px solid black;'>".$row['callsign']."</td>";
						echo "<td style='width:150px;border:1px solid black;'>".$row['operating_class']."</td>";
						echo "<td style='width:150px;border:1px solid black;'>".$row['section']."</td>";
						echo "<td style='width:150px;border:1px solid black;'>".$band."</td>";
						echo "<td style='width:150px;border:1px solid black;'>".$row['mode']."</td>";
						
						if (!empty($_SESSION['priv'])) {
								echo '<td style=\'width:75px;border:1px solid black;text-align:center;\'><input type="checkbox" name="delete[]" value="'.$row['logid'].'" />&nbsp;</td></tr>'."\n";
						} else {
							echo "</tr>" . "\n";
						}
						
					}
				}
				catch(PDOException $e) {
					echo "Error: " . $e->getMessage();
				}
				$conn = null;
				echo '
				</form>
				</table>';
				page_buttons($table);
			} else {
				echo '<h2>Sign in to use this page</h2>';
			} ?>
				</div>
			</div>
		</div>
	</div>
</body>
</html>