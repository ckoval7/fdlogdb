<?php include 'php/submitsignin.php' ?>
<!DOCTYPE html>
<html>
<head>
	<title>Sign In</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="Corey Koval, K3CPK">
	<meta name="application-name" content="Field Day Logging Database" />
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<div id="outer_wrapper" class="grid">
		<header id="site_header" class="header">
			<div id="page_top" class="row">
				<?php include 'header.php';?>
			</div>
		</header>
		<hr>
		<div class="row">
			<div class="col-2">
				<?php include 'navbar.php';?>
			</div>
			<div id="inner_wrapper" class="col-10">
				<h4>To submit logs and manage your inventory, please sign in.</h4>
				<form action="submitsignin.php" method="POST">
					Username/Call Sign: <br>
					<input type="text" name="username" /><br>
					Password: <br>
					<input type="password" name="password" /><br>
					<input type="submit" />
					<h5>Don't have an account? Create one <a href="register.php">here</a>.</h5>
					<h5>If you forgot your password, please talk to an on site administrator.</h5>
				</form>
			</div>
		</div>
	</div>
</body>
</html>