<?php 
if (!empty ($_SESSION['priv'])) {
	if ($_SESSION['priv'] === "user") {
		echo '
			<div id="drawer" class="row">
				<nav class="nav">
					<ul class="nav__list">
						<li class="nav__item"><a href="index.php">Home</a></li>
						<li class="nav__item"><a href="enter-log.php">Log</a></li>
						<li class="nav__item"><a href="images.php">Images</a></li>
						<li class="nav__item"><a href="guestbook.php">Guestbook</a></li>
						<li class="nav__item"><a href="inventory.php">Inventory</a></li>
						<li class="nav__item"><a href="sign-out.php">Sign Out</a></li>
						<li class="nav__item"><a href="files/2016 Rules.pdf">2016 Rules</a></li>
					</ul>
				</nav>
			</div>';
	} elseif ($_SESSION['priv'] === "admin"){
		echo '
			<div id="drawer" class="row">
				<nav class="nav">
					<ul class="nav__list">
						<li class="nav__item"><a href="index.php">Home</a></li>
						<li class="nav__item"><a href="images.php">Images</a></li>
						<li class="nav__item"><a href="view-log.php">Edit Log</a></li>
						<li class="nav__item"><a href="guestbook.php">Edit Guestbook</a></li>
						<li class="nav__item"><a href="inventory.php">Edit Inventory</a></li>
						<li class="nav__item"><a href="admin/setup.php">Setup</a></li>
						<li class="nav__item"><a href="admin/postfd.php">Post-FD</a></li>
						<li class="nav__item"><a href="sign-out.php">Sign Out</a></li>
						<li class="nav__item"><a href="files/2016 Rules.pdf">2016 Rules</a></li>
					</ul>
				</nav>
			</div>';
	}
} else {
	echo '
		<div id="drawer" class="row">
			<nav class="nav">
				<ul class="nav__list">
					<li class="nav__item"><a href="index.php">Home</a></li>
					<li class="nav__item"><a href="sign-in.php">Sign In</a></li>
					<li class="nav__item"><a href="register.php">Register</a></li>
					<li class="nav__item"><a href="view-log.php">View Log</a></li>
					<li class="nav__item"><a href="guestbook.php">Guestbook</a></li>
					<li class="nav__item"><a href="files/2016 Rules.pdf">2016 Rules</a></li>
				</ul>
			</nav>
		</div>';
}
?>