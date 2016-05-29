<?php session_start(); 
	$_SESSION['band'] = "";
	$_SESSION['mode'] = "";
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
	<script type="text/javascript" src="/js/fdlog.js"></script>
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
					if (!empty($_SESSION['priv']) && $_SESSION['priv'] === "user") {
						echo '
						<div class="row">
							<a href="/enter-log.php"><h3>Click here to submit logs</h3></a>
						</div><hr>';
					} ?>
					<div class="row">
						<div id="recent-contacts">
							<?php include 'php/displaylog.php'; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>