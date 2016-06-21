<?php
	include 'php/submitguestbook.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Guestbook</title>
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
					<h4>Thank you for visiting our field day site! Please take a moment to sign the guestbook!</h4><br>
					<form method="POST" action=<?php echo $_SERVER["PHP_SELF"];?>>
						<span>
							<b>First Name:</b><br>
							<input type="text" name="first" value="<?php echo $first_name; ?>">
							<span class="error">* <?php echo $firstnameErr;?></span><br>
							<b>Last Name:</b><br>
							<input type="text" name="last" value="<?php echo $last_name; ?>"/>
							<span class="error">* <?php echo $lastnameErr;?></span><br>
							Call Sign:<br>
							<input type="text" name="callsign" value="<?php echo $callsign; ?>" />
							<br>
						</span>
						<span style="width: 5%;"></span>
						<span>
							Comments:<br>
							<textarea name="comments" rows="5" cols="22"><?php echo $comments; ?></textarea><br>
							<input type="submit" name="submit" value="Submit" /><br>
						</span>
					</form>
					<hr>
					<div class="row">
						<?php
						include 'php/paginate.php';
						include 'php/db_passwords.php';
						$_SESSION["key"] = "guest_id";
						$_SESSION["table"] = "guestbook";
						$_SESSION["page"] = "/guestbook.php";
						$table = $_SESSION["table"];

						$pages = paginate($table);

						page_buttons($table);
						echo "<table style='border: solid 1px black;'>";
						echo '<form id="guestbook" enctype="multipart/form-data" action="/php/delete.php" method="post"><tr><th>Date and Time</th><th>First Name</th><th>Last Name</th><th>Call Sign</th>';
						if (!empty($_SESSION['priv'])) {
							if ($_SESSION['priv'] === "admin") {
								echo '<th><input type="submit" value="Delete" /></th>';
							}
						}
						echo '</tr>';

						try {
							$offset = getPage($table);
							$conn = new PDO("mysql:host=$servername;dbname=$dbname", $rd_username, $rd_password);
							$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
							$stmt = $conn->prepare("SELECT guest_id, first_name, last_name, callsign, DATE_FORMAT(sign_time, '%M %d, %Y, %H:%i') as timestamp FROM guestbook ORDER BY guest_id DESC LIMIT $offset, $limit"); 
							$stmt->execute();

							// set the resulting array to associative
							foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
								echo "<tr>";
								echo "<td style='width:175px;border:1px solid black;'>".$row['timestamp']."</td>";
								echo "<td style='width:150px;border:1px solid black;'>".$row['first_name']."</td>";
								echo "<td style='width:150px;border:1px solid black;'>".$row['last_name']."</td>";
								echo "<td style='width:150px;border:1px solid black;'>".$row['callsign']."</td>";
								
								if (!empty($_SESSION['priv'])) {
									if ($_SESSION['priv'] === "admin") {
										echo '<td style=\'width:75px;border:1px solid black;text-align:center;\'><input type="checkbox" name="delete[]" value="'.$row['guest_id'].'" />&nbsp;</td></tr>'."\n";
									}
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
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>