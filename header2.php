<?php
$servername = "localhost";
$username = "fdlogread";
$password = "password";
$dbname = "fdlogdb";
$fd_callsign = "";
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
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
	<span class="col-1" id="nav_button" onclick="openNav()">&nbsp;<img src="/img/hamburger.svg" alt="Open Menu"><br>Menu</span>
	<span class="col-11" id="timeandtitle">
		<h3>Field Day Logging</h3>
		<h4>DD/MM/YYYY</h4>
		<h4>HH:mm:ss</h4>
		<h2 id="fdcallsign">'.$fd_callsign.'</h2>';
		if (!empty($_SESSION['name'])) {
			echo '<h5>Welcome, '. $_SESSION['name'].'</h5>';
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