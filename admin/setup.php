<?php 
session_start();
include '../php/submitsignin.php';
$callsign = '';
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
		<header id="site_header" class="header">
			<div id="page_top" class="row">
				<?php include "../header.php";?>
			</div>
		</header>
		<hr>
		<div class="row">
			<div class="col-2">
				<?php include "../navbar.php";?>
			</div>
<?php
if (!empty($_SESSION['priv']) and $_SESSION['priv'] === "admin") {
	echo '

			<div id="inner_wrapper" class="col-10">
				<h4>Welcome to the Field Day setup interface.</h4>
				Here you will enter information about your station prior to the start of field day. If you haven&#39;t already done so, please run the initial configuration script&nbsp;<a href="/admin/db-config.php">here.</a><br><br>
				<form action='. $_SERVER["PHP_SELF"].' method="POST">
					Field Day Call Sign: <br>
					<input type="text" name="callsign" value="'.$callsign. '" />
					<span class="error">* <?php echo $callsignErr;?></span><br>
					New admin password: <br>
					<input type="password" name="password" />
					<span class="error">* <?php echo $passErr1;?></span><br>
					<input type="submit" /><br>
					<span class="error"> <?php echo $passErr2;?></span>
					
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