<?php 
session_start();
//include 'php/submitsignin.php'
include 'php/db_passwords.php';
$usernameErr = $passErr1 = $passErr2 = "";
$username = $password = $user_priv = "";
$hash = "";
$isReady = 0;

function test_input($data) {
	$data = trim($data);
	$data = htmlspecialchars($data);
	return $data;
	}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST["username"])) {
		$usernameErr = "Please Enter your call sign or username.";
	} else {
		$username = strtoupper(test_input($_POST["username"]));
	}

	if (empty($_POST["password"])) {
		$passErr1 = "Please enter your password";
	} else {
		$password = test_input($_POST["password"]);
	}
	
	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $rd_username, $rd_password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = $conn->prepare("SELECT COUNT(call_sign) FROM users WHERE call_sign = :username");
		$sql->bindParam(':username', $username);
		$sql->execute();
		$count = $sql->rowCount();
		if ($count > 0) {
			$sql = $conn->prepare("SELECT password FROM users WHERE call_sign = :username");
			$sql->bindParam(':username', $username);
			$sql->execute();
			$hash = $sql->fetch();
			if (password_verify($password, $hash[0])) {
				//echo "Valid password!";
				$sql = $conn->prepare("SELECT uuid, first_name, user_level FROM users WHERE call_sign = :username");
				$sql->bindParam(':username', $username);
				$sql->execute();
				$user_array = $sql->fetch();
				$_SESSION['priv'] = $user_array[2];
				if ($_SESSION['priv'] === 'locked') {
					$passErr2 = "Sorry, your account has been locked. Please see an administrator.";
					session_unset();
					session_destroy();
				} else {
					$_SESSION['username'] = $username;
					$_SESSION['name'] = $user_array[1];
					$_SESSION['uuid'] = $user_array[0];
					$_SESSION['band'] = "";
					$_SESSION['mode'] = "";
					echo '<META http-equiv="refresh" content="0;URL=/index.php">';
				}
			} else {
				$passErr2 = 'Username or password incorrect';
			}
		} else {
			$passErr2 = 'Username or password incorrect';
		}
	}
	catch(PDOException $e)
	{
		echo "Connection failed: " . $e->getMessage();
	}
	$conn=null;
}
 ?>
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
					<h4>To submit logs and manage your inventory, please sign in.</h4>
					<form action=<?php echo $_SERVER["PHP_SELF"];?> method="POST">
						Call Sign: <br>
						<input type="text" name="username" value="<?php echo $username; ?>" />
						<span class="error">* <?php echo $usernameErr;?></span><br>
						Password: <br>
						<input type="password" name="password" />
						<span class="error">* <?php echo $passErr1;?></span><br>
						<input type="submit" value="Sign In"/><br>
						<span class="error"> <?php echo $passErr2;?></span>
						<h5>Don't have an account? Create one <a href="/register.php">here</a>.</h5>
						<h5>If you forgot your password, please talk to an on site administrator.</h5>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
</html>