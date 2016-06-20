<?php
// Start the session
session_start();
include 'php/db_passwords.php';
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
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script type="text/javascript" src="js/fdlog.js"></script>
	<script type="text/javascript" src="js/jquery.min.js"></script>
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
					<div id="welcome" class="row">
						Welcome to our field day site! If you are just a visitor please take a moment to sign the&nbsp;<a href="/guestbook.php">guestbook.</a>
						<br>
						If you are an operator please sign in or register to submit logs, add inventory, and submit pictures.
					</div>
					<hr>
					<div class="row">
						<div id="recent-contacts">
							<fieldset>
								<legend>Last 15 Contacts</legend>
									<table style='border: solid 1px black;'>
										<tr><th>Call Sign</th><th>Class</th><th>Section</th><th>Band</th><th>Mode</th></tr>
										<?php
										try {
											$conn = new PDO("mysql:host=$servername;dbname=$dbname", $rd_username, $rd_password);
											$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
											$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
											$stmt = $conn->prepare("SELECT callsign, operating_class, section, band, mode FROM logbook ORDER BY logid DESC LIMIT 15");
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
												echo "</tr>" . "\n";
											}
										}
										catch(PDOException $e) {
											echo "Error: " . $e->getMessage();
										}
										$conn = null;
										?>
										</table>
								
							</fieldset>
						</div>
					</div>
					<hr>
					<div class="row">
						<div id="recent-images">
							<h4> Last 6 Image Uploads: </h4>
							<?php
								try {
									$conn = new PDO("mysql:host=$servername;dbname=$dbname", $rd_username, $rd_password);
									$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
									$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
									$stmt = $conn->prepare("SELECT file_location FROM images ORDER BY image_id DESC LIMIT 6"); 
									$stmt->execute();

									// set the resulting array to associative
									foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
										echo '<div class="home_images"><a class="lightbox_trigger" href="'.$row['file_location'].'"><img src="'.$row['file_location'].'" alt="user image" height="200" width="200"></a></div>';
									}
								}
								catch(PDOException $e) {
									echo "Error: " . $e->getMessage();
								}
								$conn = null;
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<script src="js/lightbox.js"></script>
</body>
</html>