<?php
// Start the session
session_start();
include '/php/imageupload.php';
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
					Not implemented yet.
					<?php
						if (isset($_SESSION['priv'])){
							echo'
							<div class="row">
								<form method="POST" action="'.$_SERVER["PHP_SELF"].'" enctype="multipart/form-data">
									<span>Choose an image to upload:
										<input type="file" name="imageupload" id="imageupload"><br>
										Limit: 8MB<br>
									</span><br>
									<span>Description:<br>
										<textarea name="description" rows="5" cols="45"></textarea><br>
									</span><br>
									<input type="submit" name="submit" value="Upload Image"/><br>
								</form>
								<span class="error">
									'.$error.'<br>
									'.$error2.'
								</span>
							</div>
							<hr>';
						} else {
							echo '<h2>Sign in to use this page</h2>';
						} 
					?>			
				</div>
			</div>
		</div>
	</div>
<?php include '/js/scripthtml.php'; ?>
</body>
</html>