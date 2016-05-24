<?php 
session_start();
include '/submitsetup.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Pre Field Day setup</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="Corey Koval, K3CPK">
	<meta name="application-name" content="Field Day Logging Database" />
	<link rel="stylesheet" type="text/css" href="/css/style.css">
</head>
<body>
	<div id="outer_wrapper" class="grid">
		<div class="row">
			<div id="menu" class="col-2">
				<?php include '../navbar.php'; ?>
			</div>
			<div id="header2" class="col-10">
				<?php include '../header2.php'; ?>
				<div id="content">
					<?php
if (!empty($_SESSION['priv']) and $_SESSION['priv'] === "admin") {
	echo '
				<h4>Welcome to the Field Day setup interface.</h4>
				Here you will enter information about your station prior to the start of field day. If you haven&#39;t already done so, please run the initial configuration script&nbsp;<a href="/admin/db-config.php">here.</a><br><br>
				<form action='. $_SERVER["PHP_SELF"].' method="POST">
					<b>Field Day Call Sign:</b> <br>
					<input type="text" name="fd_callsign" value="'.$fd_callsign.'" />
					<span class="error">* '.$fd_callErr.'</span><br>
					<b>Section:</b><br>
					<input type="text" name="fd_section" value="'.$fd_section.'" />
					<span class="error">* '.$fd_sectionErr.'</span><br>
					<b>Class:</b><br>
					<input type="text" name="fd_class" value="'.$fd_section.'" />
					<span class="error">* '.$fd_classErr.'</span><br>
					GOTA Callsign:<br>
					<input type="text" name="gota_callsign" value="'.$gota_callsign.'" />
					<br><br>
					<b>New admin password:</b> <br>
					<input type="password" name="admin_password" />
					<span class="error">* '.$admin_passErr.'</span><br>
					<input type="submit" /><br>
					
				</form>
			</div>';
}else {
	echo '<h2>You don&#39;t belong here. Go away.</h2>';
}
echo '
		</div>
	</div>
</body>
</html>';
?>