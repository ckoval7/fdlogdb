<?php session_start();
include 'php/submitlog.php';?>
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
						<input type="text" id="exchange" name="exchange" value="'.$view_exchange.'" autofocus="autofocus" onfocus="this.value = this.value;"/><span class="error">* '. $dupeErr.'</span><span class="error">'.$sectionErr.'</span><br>
						<input type="submit" value="Submit" /><br><br>
						<b>When you are done with '.$_SESSION['band'].'&nbsp;'.$_SESSION['mode'].' please click <a href="/php/enterlog.php">here.</a></b>';
				}
				echo '</form>
				<hr>';
				include 'php/displayloguser.php';
			} else {
				echo '<h2>Sign in to use this page</h2>';
			} ?>
				</div>
			</div>
		</div>
	</div>
</body>
</html>