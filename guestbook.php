<?php
	include 'php/submitguestbook.php';
?>
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
<body  onload="startTime()">
	<div id="outer_wrapper" class="grid">
		<div class="row">
			<div id="menu" class="col-2">
				<?php include '/navbar.php'; ?>
			</div>
			<div id="header2" class="col-10">
				<?php include '/header2.php'; ?>
				<div id="content">
					<h4>Thank you for visiting our field day site! Please take a moment to sign the guestbook!</h4><br>
					<form method="POST" action=<?php echo $_SERVER["PHP_SELF"];?>>
						<span>
							<b>First Name:</b><br>
							<input type="text" name="first" value="<?php echo $first_name; ?>">
							<span class="error">* <?php echo $firstnameErr;?></span><br>
							<b>Last Name:</b><br>
							<input type="text" name="last" value="<?php echo $last_name; ?>"/>
							<span class="error">* <?php echo $lastnameErr;?></span><br>
							Call Sign:<br>
							<input type="text" name="callsign" value="<?php echo $callsign; ?>" />
							<br>
						</span>
						<span style="width: 5%;"></span>
						<span>
							Comments:<br>
							<textarea name="comments" rows="5" cols="22"><?php echo $comments; ?></textarea><br>
							<input type="submit" name="submit" /><br>
						</span>
					</form>
					<hr>
					<div class="row">
						<?php include 'php/displayguestbook.php'; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php include '/js/scripthtml.php'; ?>
</body>
</html>