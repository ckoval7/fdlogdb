<?php
echo'
<header class="header">
	<span id="nav_button" onclick="openNav()">&nbsp;<img src="/img/hamburger.svg" alt="Open Menu"><br>Menu</span>
	<span id="title"><h3>Field Day Logging</h3></span>
	<div id="timeandtitle" class="col-10">
		<div id="datetime" class="col-6">
			<div id="date" class="row">
				<h2>DD/MM/YYYY</h2>
			</div>
			<div id="clock" class="row">
				<h2>HH:mm:ss</h2>
			</div>';
		if (!empty($_SESSION['name'])) {
			echo '<h5>Welcome, '. $_SESSION['name'].'</h5>';
		}
		echo '
		</div>
		<div id="title" class="col-6">
			<h1>KB3ABC/1</h1>
		</div>
	</div>
</header>';
if (!empty($_SESSION['username']) and $_SESSION['username'] === "ADMIN") {
	echo '
		<div class="row">
			<h2>Logged in as Admin! Please do not log your contacts as admin.</h2>
		</div>';
}
echo '</header>';
?>