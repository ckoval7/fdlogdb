<?php
$servername = "localhost";
$db_username = "fdlogread";
$db_password = "password";
$dbname = "fdlogdb";
$fd_callsign = "";
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT small_string FROM fd_config WHERE config_name = 'fd_callsign' LIMIT 1"); 
    $stmt->execute();
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$fd_callsign = $stmt->fetch();
	$fd_callsign = implode("", $fd_callsign);
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
echo'
<header class="header">
<div class="row">
	<span class="col-12" id="timeandtitle">
	<span id="nav_button" onclick="openNav()">&nbsp;<img src="/img/hamburger.svg" alt="Open Menu"><br>Menu</span>
		<span id="prog_name">
			<h3>Field Day Logging</h3>
		</span>
		<span id="fd_callsign">
			<h1 id="fdcallsign">'.$fd_callsign.'</h1>
		</span>
		<span id="datetime">
			<h4>'.gmdate("m/d/Y").'</h4>
			<h4 id="time"></h4>
		</span>';
		
		if (!empty($_SESSION['name'])) {
			echo '<h5 id="name">Welcome, '. $_SESSION['name'].'. If this is not you <a style="color:black;" href="sign-out.php">click here</a> to sign out.</h5>';
		}
		echo '
	</span>
</div>
</header>';
if (!empty($_SESSION['username']) and $_SESSION['username'] === "ADMIN") {
	echo '
		<div class="error row">
			<h2>Logged in as Admin! Please do not log your contacts as admin.</h2>
		</div>';
}
?>