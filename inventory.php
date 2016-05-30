<?php
// Start the session
session_start();
if (empty($_SESSION['phone'])) {
	$_SESSION['phone'] = '';
}
include 'php/submitinventory.php';
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
				<h3>Inventory</h3>
				On this page you can view and modify your inventory. This information is used for keeping track of user's equipment and can provide the site admin with emergency contact information if something happens to your equipment.<br><br>
					<?php
					if (!empty($_SESSION['priv']) && $_SESSION['priv'] === 'user'){
						echo'
						<form id="sublog" method="POST" action='.$_SERVER["PHP_SELF"].'>
							<b>Category:</b> <br>
							<select name="type">
								<option value="radio">Radio</option>
								<option value="antenna">Antenna</option>
								<option value="other">Other</option>
							</select><br>
							<b>Brand/Make:</b><br>
							<input type="text" name="make"><span class="error">* '. $makeErr.'</span><br>
							<b>Model:</b><br>
							<input type="text" name="model"><span class="error">* '. $modelErr.'</span><br>
							<b>Phone Number:</b><br>
							<input type="text" name="phone" value="'.$_SESSION['phone'].'"><span class="error">* '. $phoneErr.'</span><br>
							<b>Description:</b><br>
							<textarea name="description" rows="5" cols="45"></textarea><br>
							<input type="submit" /><br><br>
						</form>
						<hr>';
						include 'php/displayinventory.php';
					} elseif (!empty($_SESSION['priv']) && $_SESSION['priv'] === 'admin') {
						//Cannot enter items, but displays full table.
						echo "Soon to come!";
					}
					else {
						echo '<h2>Sign in to use this page</h2>';
					} ?>
				</div>
			</div>
		</div>
	</div>
</body>
</html>