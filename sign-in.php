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
</head>
<body>
	<div id="outer_wrapper" class="grid">
		<div id="inner_wrapper" class="canvas">
			<header id="site_header" class="header">
				<div id="page_top" class="row">
					<?php include 'header.php';?>
					<?php include 'navbar.php';?>
				</div>
				<hr id="navhr">
			</header>
			<div class="row">
				<form action="submitsignin.php" method="POST">
					<input type="text" name="q" /><br>
					<input type="password" name="p" /><br>
					<input type="submit" />
				</form>
			</div>
		</div>
	</div>
</body>
</html>