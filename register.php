<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
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
				<h4>To explore the full funtionality of the site and to log your contacts you will need an account.<br>
<<<<<<< HEAD
					All fields are required. Please fill in the information below:</h4>
=======
					Please fill in the information below:</h4>
>>>>>>> origin/master
				<form method="POST" action=<?php echo $_SERVER["PHP_SELF"];?>>
					<b>First Name:</b><br>
					<input type="text" name="first" value="<?php echo $first_name; ?>">
					<span class="error">* <?php echo $firstnameErr;?></span><br>
					<b>Last Name:</b><br>
					<input type="text" name="last" value="<?php echo $last_name; ?>"/>
					<span class="error">* <?php echo $lastnameErr;?></span><br>
					<b>Call Sign:</b><br>
					<input type="text" name="callsign" value="<?php echo $callsign; ?>" />
					<span class="error">* <?php echo $callsignErr;?></span><br>
<<<<<<< HEAD
					<b>Operating Class:</b><br>
					<select name="class">
						<option value="Novice">Novice</option>
						<option value="Technician">Technician</option>
						<option value="General">General</option>
						<option value="Advanced">Advanced</option>
						<option value="Extra">Extra</option>
					</select><br>
					<span>
					<b>Password:</b><br>
					<input type="password" name="password" /></span>
					<span>
					<b>Repeat Password:</b><br>
					<input type="password" name="repeat_password" />
					</span><br>
=======
					Operating Class:
					(Drop Down Menu Here)<br>					
>>>>>>> origin/master
					<input type="submit" /><br>
				</form>
			</div>
		</div>
	</div>
</body>
</html>