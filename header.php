<?php
echo					
'<span id="logo" class="logo col-2">
	<img src="/img/100.jpg" alt="logo goes here"/>
</span>
<div id="timeandtitle" class="col-10">
	<div id="datetime" class="col-6">
		<div id="clock" class="row">
			<h2>HH:mm:ss</h2>
		</div>
		<div id="date" class="row">
			<h2>DD/MM/YYYY</h2>
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
<span id="nav_button" onclick="openNav()"><img src="/img/hamburger.svg" alt="Open Menu">&nbsp;Menu</span>';
if (!empty($_SESSION['username']) and $_SESSION['username'] === "ADMIN") {
	echo '
		<div class="row">
			<h2>Logged in as Admin! Please do not log your contacts as admin.</h2>
		</div>';
}
?>