<?php
// Start the session
session_start();
if (empty($_SESSION['phone'])) {
	$_SESSION['phone'] = '';
}
include 'php/submitinventory.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>My Inventory</title>
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
				<h3>Inventory</h3>
				On this page you can view and modify your inventory. This information is used for keeping track of user's equipment and can provide the site admin with emergency contact information if something happens to your equipment.<br><br>
					<?php
					if (!empty($_SESSION['priv']) && $_SESSION['priv'] === 'user'){
						echo'
						<form id="sublog" method="POST" action='.$_SERVER["PHP_SELF"].'>
							<b>Category:</b> <br>
							<select name="type">
								<option value="radio">Radio</option>
								<option value="antenna">Antenna</option>
								<option value="other">Other</option>
							</select><br>
							<b>Brand/Make:</b><br>
							<input type="text" name="make"><span class="error">* '. $makeErr.'</span><br>
							<b>Model:</b><br>
							<input type="text" name="model"><span class="error">* '. $modelErr.'</span><br>
							<b>Phone Number:</b><br>
							<input type="text" name="phone" value="'.$_SESSION['phone'].'"><span class="error">* '. $phoneErr.'</span><br>
							<b>Description:</b><br>
							<textarea name="description" rows="5" cols="45"></textarea><br>
							<input type="submit" value="Submit" /><br><br>
						</form>
						<hr>';
						echo "<table style='border: solid 1px black;'>";
						echo '<form id="guestbook" enctype="multipart/form-data" action="/php/delete.php" method="post"><tr><th>Category</th><th>Brand</th><th>Model</th><th>Description</th>';
						if (!empty($_SESSION['priv'])) {
								echo '<th><input type="submit" value="Delete" /></th>';
						}
						echo '</tr>';
						$uuid = $_SESSION['uuid'];
						$_SESSION["key"] = "item_id";
						$_SESSION["table"] = "inventory";
						$_SESSION["page"] = "/inventory.php";

						try {
							$conn = new PDO("mysql:host=$servername;dbname=$dbname", $rd_username, $rd_password);
							$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
							$stmt = $conn->prepare("SELECT item_make, item_model, item_description, item_type, item_id FROM inventory WHERE user_id = '$uuid' ORDER BY item_id DESC");
							$stmt->execute();

							// set the resulting array to associative
							foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
								echo "<tr>";
								echo "<td style='width:150px;border:1px solid black;'>".$row['item_type']."</td>";
								echo "<td style='width:150px;border:1px solid black;'>".$row['item_make']."</td>";
								echo "<td style='width:150px;border:1px solid black;'>".$row['item_model']."</td>";
								echo "<td style='width:150px;border:1px solid black;'>".$row['item_description']."</td>";
								
								if (!empty($_SESSION['priv'])) {
										echo '<td style=\'width:75px;border:1px solid black;text-align:center;\'><input type="checkbox" name="delete[]" value="'.$row['item_id'].'" />&nbsp;</td></tr>'."\n";
								} else {
									echo "</tr>" . "\n";
								}
								
							}
						}
						catch(PDOException $e) {
							echo "Error: " . $e->getMessage();
						}
						$conn = null;
						echo '
						</form>
						</table>';
					} elseif (!empty($_SESSION['priv']) && $_SESSION['priv'] === 'admin') {
						//Cannot enter items, but displays full table.
						echo "Soon to come!";
					}
					else {
						echo '<h2>Sign in to use this page</h2>';
					} ?>
				</div>
			</div>
		</div>
	</div>
</body>
</html>