<?php 
session_start();
$passErr1 = "";
include 'php/db_passwords.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $wr_username, $wr_password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if (!empty($_POST['callsign'])) {
			$sql = $conn->prepare("UPDATE users SET call_sign=:callsign WHERE uuid = :uuid");
			$sql->bindParam(':callsign', $_POST['callsign']);
			$sql->bindParam(':uuid', $_SESSION['uuid']);
			$sql->execute();
		}
		if (!empty($_POST['class'])) {
			$sql = $conn->prepare("UPDATE users SET license_class=:class WHERE uuid = :uuid");
			$sql->bindParam(':class', $_POST['class']);
			$sql->bindParam(':uuid', $_SESSION['uuid']);
			$sql->execute();
		}
		if (!empty($_POST['password'])) {
			$password1 = $_POST["password"];
			$password2 = $_POST["repeat_password"];
			if ($password1 === $password2) {
				$pass_options = ['cost' => 12];
				$password = password_hash($password1, PASSWORD_BCRYPT, $pass_options);
				$sql = $conn->prepare("UPDATE users SET password='$password' WHERE uuid = :uuid");
				$sql->bindParam(':uuid', $_SESSION['uuid']);
				$sql->execute();
			}else {
				$passErr1 = "Passwords do not match!";
			}
		}
	} catch(PDOException $e) {
		echo "Connection failed: " . $e->getMessage();
	}
	$conn=null;
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>My Account</title>
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
					<?php
						if (!empty($_SESSION['priv']) and $_SESSION['priv'] === "user") {
							echo '
										<h4>Manage your account</h4>
										<br>
										<form action='. $_SERVER["PHP_SELF"].' method="POST">
											<b>Operating Class:</b><br>
											<select name="class">
												<option value="" selected>--</option>
												<option value="Novice">Novice</option>
												<option value="Technician">Technician</option>
												<option value="General">General</option>
												<option value="Advanced">Advanced</option>
												<option value="Extra">Extra</option>
											</select>
											
											<br><br>
											<span>
												<b>New password:</b> <br>
												<input type="password" name="password" />
											</span>
											<span>
												<b>Repeat Password:</b><br>
												<input type="password" name="repeat_password" />
												</span>
											<span class="error">'.$passErr1.'</span><br>
											<input type="submit" value="Submit" /><br>
										</form>';
						}else {
							echo '<h2>Sign in to use this page.</h2>';
						}
						?>
				</div>
			</div>
		</div>	
	</body>
</html>