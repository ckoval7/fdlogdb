<?php session_start();
include 'php/submitlog.php';?>
<!DOCTYPE html>
<html>
<head>
	<title>Field Day Log</title>
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
					<?php
			if (!empty($_SESSION['priv'])){
				echo'
				<form id="sublog" method="POST" action='.$_SERVER["PHP_SELF"].' autocomplete="off">';
				if (empty($_SESSION['band']) or empty($_SESSION['mode'])) {
					echo '
					<span style="margin-left:10px;text-align:center;">Choose a band:<br>
						<span class="bandmode" id="160c"><input id="1" type="radio" name="bandmode" value="160CW"><label for="1">160m C</label></span>
						<span class="bandmode" id="80c"><input id="2" type="radio" name="bandmode" value="80CW"><label for="2">80m C</label></span>
						<span class="bandmode" id="40c"><input id="3" type="radio" name="bandmode" value="40CW"><label for="3">40m C</label></span>
						<span class="bandmode" id="20c"><input id="4" type="radio" name="bandmode" value="20CW"><label for="4">20m C</label></span>
						<span class="bandmode" id="15c"><input id="5" type="radio" name="bandmode" value="15CW"><label for="5">15m C</label></span>
						<span class="bandmode" id="10c"><input id="6" type="radio" name="bandmode" value="10CW"><label for="6">10m C</label></span>
						<span class="bandmode" id="6c"><input id="7" type="radio" name="bandmode" value="6CW"><label for="7">6m C</label></span>
						<span class="bandmode" id="2c"><input id="8" type="radio" name="bandmode" value="2CW"><label for="8">2m C</label></span>
						<span class="bandmode" id="125c"><input id="9" type="radio" name="bandmode" value="125CW"><label for="9">1.25m C</label></span>
						<span class="bandmode" id="247c"><input id="10" type="radio" name="bandmode" value="247CW"><label for="10">Sat C</label></span>
						
						<hr>
						
						<span class="bandmode" id="160d"><input id="11" type="radio" name="bandmode" value="160Digital"><label for="11">160m D</label></span>
						<span class="bandmode" id="80d"><input id="12" type="radio" name="bandmode" value="80Digital"><label for="12">80m D</label></span>
						<span class="bandmode" id="40d"><input id="13" type="radio" name="bandmode" value="40Digital"><label for="13">40m D</label></span>
						<span class="bandmode" id="20d"><input id="14" type="radio" name="bandmode" value="20Digital"><label for="14">20m D</label></span>
						<span class="bandmode" id="15d"><input id="15" type="radio" name="bandmode" value="15Digital"><label for="15">15m D</label></span>
						<span class="bandmode" id="10d"><input id="16" type="radio" name="bandmode" value="10Digital"><label for="16">10m D</label></span>
						<span class="bandmode" id="6d"><input id="17" type="radio" name="bandmode" value="6Digital"><label for="17">6m D</label></span>
						<span class="bandmode" id="2d"><input id="18" type="radio" name="bandmode" value="2Digital"><label for="18">2m D</label></span>
						<span class="bandmode" id="125d"><input id="19" type="radio" name="bandmode" value="125Digital"><label for="19">1.25m D</label></span>
						<span class="bandmode" id="247d"><input id="20" type="radio" name="bandmode" value="247Digital"><label for="20">Sat D</label></span>
						
						<hr>
						
						<span class="bandmode" id="160p"><input id="21" type="radio" name="bandmode" value="160Phone"><label for="21">160m P</label></span>
						<span class="bandmode" id="80p"><input id="22" type="radio" name="bandmode" value="80Phone"><label for="22">80m P</label></span>
						<span class="bandmode" id="40p"><input id="23" type="radio" name="bandmode" value="40Phone"><label for="23">40m P</label></span>
						<span class="bandmode" id="20p"><input id="24" type="radio" name="bandmode" value="20Phone"><label for="24">20m P</label></span>
						<span class="bandmode" id="15p"><input id="25" type="radio" name="bandmode" value="15Phone"><label for="25">15m P</label></span>
						<span class="bandmode" id="10p"><input id="26" type="radio" name="bandmode" value="10Phone"><label for="26">10m P</label></span>
						<span class="bandmode" id="6p"><input id="27" type="radio" name="bandmode" value="6Phone"><label for="27">6m P</label></span>
						<span class="bandmode" id="2p"><input id="28" type="radio" name="bandmode" value="2Phone"><label for="28">2m P</label></span>
						<span class="bandmode" id="125p"><input id="29" type="radio" name="bandmode" value="125Phone"><label for="29">1.25m P</label></span>
						<span class="bandmode" id="247p"><input id="30" type="radio" name="bandmode" value="247Phone"><label for="30">Sat P</label></span>
						
						<hr>
					</span><br>&nbsp;
					<span style="margin-left:10px;">Power: 
						<input type="text" name="power"><b>W</b>&nbsp;
						<input type="checkbox" name="natural_power" value="natural_power">Natural Power<br>
					</span><br>
						<input style="margin-left:10px;" type="submit" value="Begin Session" /><br>';
				} else {
					echo '
						<h2>'.$_SESSION['band'].'m&nbsp;-&nbsp;'.$_SESSION['mode'].'</h2>
						Please enter the whole exchange on one line then press enter. For example:<br>
						"w3uas 3a mdc"<br>
						<b>Exchange:</b><br>
						<input type="text" id="exchange" name="exchange" value="'.$view_exchange.'" autofocus="autofocus" onfocus="this.value = this.value;"/><span class="error">* '. $dupeErr.'</span><span class="error">'.$sectionErr.'</span><br>
						<input type="submit" value="Submit" /><br><br>
						<b>When you are done with '.$_SESSION['band'].'m&nbsp;'.$_SESSION['mode'].' please click <a href="/php/enterlog.php">here.</a></b>';
				}
				echo '</form>
					<hr>
					<div id="content">';
				include 'php/displayloguser.php';
			} else {
				echo '
				<div id="content">
					<h2>Sign in to use this page</h2>';
			} ?>
				</div>
			</div>
		</div>
	</div>
</body>
</html>