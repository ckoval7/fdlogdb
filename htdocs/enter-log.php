<?php session_start();
include 'php/submitlog.php';
$dupe_err="";
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
				if (empty($_SESSION['band']) or empty($_SESSION['mode'])) {
					echo '
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
						<input type="text" name="power"><b>W</b>&nbsp;
						<input type="checkbox" name="natural_power" value="natural_power">Natural Power<br>
					</span><br>
						<input type="submit" value="Begin Session" /><br>';
				} else {
					echo '
						<h2>'.$_SESSION['band'].'&nbsp;-&nbsp;'.$_SESSION['mode'].'</h2>
						Please enter the whole exchange on one line then press enter. For example:<br>
						"w3uas 3a mdc"<br>
						<b>Exchange:</b><br>
						<input type="text" list="callsigns" id="exchange" name="exchange" value="'.$view_exchange.'" autofocus="autofocus" onfocus="this.value = this.value;"/><span class="error">
                        <datalist id="callsigns">';
                        try {
                            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $rd_username, $rd_password);
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                            $stmt = $conn->prepare("SELECT callsign, band, mode FROM logbook ORDER BY callsign");
                            $stmt->execute();

                            // set the resulting array to associative
                            foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                                if ($row['band'] == $_SESSION['dbband'] && $row['mode'] == $_SESSION['mode']) {
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
						<b>When you are done with '.$_SESSION['band'].'&nbsp;'.$_SESSION['mode'].' please click <a href="/php/enterlog.php">here.</a></b>';
				}
				echo '</form>
				<hr>';
				include 'php/paginate.php';
				include 'php/db_passwords.php';
				$uuid = $_SESSION['uuid'];
				$_SESSION["key"] = "logid";
				$_SESSION["table"] = "logbook";
				$_SESSION["page"] = "/enter-log.php";
				$table = "logbook WHERE logger_id = '$uuid'";

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
					$stmt = $conn->prepare("SELECT callsign, operating_class, section, band, mode, logid FROM logbook WHERE logger_id = '$uuid' ORDER BY logid DESC  LIMIT $offset, $limit");
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