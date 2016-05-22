<?php
	include 'php/submitreg.php';
?>

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
		<div class="row">
			<div id="menu" class="col-2">
				<?php include '/navbar.php'; ?>
			</div>
			<div id="header2" class="col-10">
				<?php include '/header2.php'; ?>
				<div id="content">
					<h4>To explore the full funtionality of the site and to log your contacts you will need an account.<br>
					All fields are required. Please fill in the information below:</h4>
					<form method="POST" action=<?php echo $_SERVER["PHP_SELF"];?>>
						<b>First Name:</b><br>
						<input type="text" name="first" value="<?php echo $first_name; ?>">
						<span class="error">* <?php echo $firstnameErr;?></span><br>
						<b>Last Name:</b><br>
						<input type="text" name="last" value="<?php echo $last_name; ?>"/>
						<span class="error">* <?php echo $lastnameErr;?></span><br>
						<b>Call Sign:</b><br>
						<input type="text" name="callsign" value="<?php echo $callsign; ?>" />
						<span class="error">* <?php echo $callsignErr;?></span><br><br>
						<b>Operating Class:</b><br>
						<select name="class">
							<option value="Novice">Novice</option>
							<option value="Technician" selected>Technician</option>
							<option value="General">General</option>
							<option value="Advanced">Advanced</option>
							<option value="Extra">Extra</option>
						</select>
						<span class="error">* <?php echo $classErr;?></span><br><br>
						<span>
						<b>Password:</b><br>
						<input type="password" name="password" /></span>
						<span>
						<b>Repeat Password:</b><br>
						<input type="password" name="repeat_password" />
						</span>
						<span class="error">* <?php echo $passErr1;?></span><br>
						<span class="error"><?php echo $passErr2;?></span><br>
						<input type="submit" /><br>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php include '/js/scripthtml.php'; ?>
</body>
</html>