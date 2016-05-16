<?php //If Guest: 
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
	/*
	if user:
	echo '
		Home
		Log
		Images
		Guestbook
		View Logs
		My Inventory
		Sign Out
		Rules';
		
	if admin:
	echo '
		Home
		Log
		Modify Logs
		Images
		Guestbook
		Modify Inventory
		Pre-FD
		Post-FD
		Admin
		Sign Out
		Rules';
	*/
?>