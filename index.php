<?php
// Start the session
session_start();
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
</head>
<body  onload="startTime()">
	<div id="outer_wrapper" class="grid">
		<div class="row">
			<div id="menu" class="col-2">
				<?php include '/navbar.php'; ?>
			</div>
			<div id="header2" class="col-10">
				<?php include '/header2.php'; ?>
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
								<?php include '/php/displayloghome.php'; ?>
							</fieldset>
						</div>
					</div>
					<hr>
					<div class="row">
						<div id="recent-images">
							<fieldset>
								<legend>Last 6 Image Uploads</legend>
								<?php include '/php/displayimageshome.php'; ?>
							</fieldset>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php include '/js/scripthtml.php'; ?>
</body>
</html>