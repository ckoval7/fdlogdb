<?php 
session_start();
include 'php/submitsignin.php' ?>
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
<body  onload="startTime()">
	<div id="outer_wrapper" class="grid">
		<div class="row">
			<div id="menu" class="col-2">
				<?php include '/navbar.php'; ?>
			</div>
			<div id="header2" class="col-10">
				<?php include '/header2.php'; ?>
				<div id="content">
					<h4>To submit logs and manage your inventory, please sign in.</h4>
					<form action=<?php echo $_SERVER["PHP_SELF"];?> method="POST">
						Username/Call Sign: <br>
						<input type="text" name="username" value="<?php echo $username; ?>" />
						<span class="error">* <?php echo $usernameErr;?></span><br>
						Password: <br>
						<input type="password" name="password" />
						<span class="error">* <?php echo $passErr1;?></span><br>
						<input type="submit" /><br>
						<span class="error"> <?php echo $passErr2;?></span>
						<h5>Don't have an account? Create one <a href="/register.php">here</a>.</h5>
						<h5>If you forgot your password, please talk to an on site administrator.</h5>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php include '/js/scripthtml.php'; ?>
</body>
</html>