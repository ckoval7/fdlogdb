<?php
session_start();
$servername = "localhost";
$dbusername = "fdlogadmin";
$dbpassword = "adminpassword";
$dbname = "fdlogdb";
?>
<!DOCTYPE html>
<html>
<head>
	<title>Field Day</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="Corey Koval, K3CPK">
	<meta name="application-name" content="Field Day Logging Database" />
	<link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body  onload="startTime()">
	<div id="outer_wrapper" class="grid">
		<div class="row">
			<div id="menu" class="col-2">
				<?php include '../navbar.php'; ?>
			</div>
			<div id="header" class="col-10">
				<?php include '../header.php'; ?>
				<div id="content">
					<?php
						if (!empty($_SESSION['priv']) && $_SESSION['priv'] === 'admin') {
							echo "<table style='border: solid 1px black;'>";
							echo '<form enctype="multipart/form-data" action="/php/mod-users.php" method="post"><tr><th>Call Sign</th><th>Class</th><th>First Name</th><th>Last Name</th><th>Reset Password</th><th>Disable Account</th></tr>';
							try {
								$conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
								$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
								$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
								$stmt = $conn->prepare("SELECT call_sign, license_class, first_name, last_name, uuid FROM users ORDER BY uuid");
								$stmt->execute();

								// set the resulting array to associative
								foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
									echo "<tr>";
									echo "<td style='width:150px;border:1px solid black;'>".$row['call_sign']."</td>";
									echo "<td style='width:150px;border:1px solid black;'>".$row['license_class']."</td>";
									echo "<td style='width:150px;border:1px solid black;'>".$row['first_name']."</td>";
									echo "<td style='width:150px;border:1px solid black;'>".$row['last_name']."</td>";
									echo '<td style=\'width:150px;border:1px solid black;text-align:center;\'><input type="checkbox" name="reset[]" value="'.$row['uuid'].'" />&nbsp;</td>';
									echo '<td style=\'width:75px;border:1px solid black;text-align:center;\'><input type="checkbox" name="disable[]" value="'.$row['uuid'].'" />&nbsp;</td>';
									echo "</tr>" . "\n";																		
								}
							} catch(PDOException $e) {
								echo "Error: " . $e->getMessage();
							}
							$conn = null;
							echo '
							</table>
							<input type="hidden" name="key" value="uuid">
							<input type="hidden" name="table" value="users">
							<input type="hidden" name="page" value="../admin/edit_users.php">
							<span style="float:right; margin:5px;"><input style="height:25px; width: 100px;" type="submit" /></span>
							</form>';
						} else {
							echo '<h1>You do not belong here!</h1>';
						}
					?>
				</div>
			</div>
		</div>
	</div>
<?php include '../js/scripthtml.php'; ?>
</body>
</html>