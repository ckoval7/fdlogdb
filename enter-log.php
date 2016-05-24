<?php session_start();
include '/php/submitlog.php';?>
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
		<div class="row">
			<div id="menu" class="col-2">
				<?php include '/navbar.php'; ?>
			</div>
			<div id="header2" class="col-10">
				<?php include '/header2.php'; ?>
				<div id="content">
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
	</div>
<?php include '/js/scripthtml.php'; ?>
</body>
</html>