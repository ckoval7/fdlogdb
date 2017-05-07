<?php
// Start the session
session_start();
include 'php/imageupload.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Images</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="Corey Koval, K3CPK">
	<meta name="application-name" content="Field Day Logging Database" />
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script type="text/javascript" src="/js/fdlog.js"></script>
	<script type="text/javascript" src="js/jquery.min.js"></script>
</head>
<body  onload="startTime()">
	<div id="outer_wrapper" class="grid">
		<div class="row">
			<div id="menu" class="col-2">
				<?php include 'navbar.php'; ?>
			</div>
			<div id="header2" class="col-10">
				<?php include 'header.php'; ?>
				<div id="imgcontent">
					<?php
						if (isset($_SESSION['priv'])){
							echo'
							<div class="row">
								<form method="POST" action="'.$_SERVER["PHP_SELF"].'" enctype="multipart/form-data">
									<span>Choose an image to upload:
										<input type="file" name="imageupload" id="imageupload"><br>
										Limit: 8MB<br>
									</span><br>
									<span>Description:<br>
										<textarea name="description" rows="5" cols="45"></textarea><br>
									</span><br>
									<input type="submit" name="submit" value="Upload Image"/><br>
								</form>
								<span class="error">
									'.$error.'<br>
									'.$error2.'
								</span>
							</div>
							<hr>';
							//include 'php/displayimages.php';
							echo '<div id="uploads" class="row">';
							try {
								$conn = new PDO("mysql:host=$servername;dbname=$dbname", $rd_username, $rd_password);
								$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
								$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
								$stmt = $conn->prepare("SELECT file_location, description FROM images ORDER BY image_id DESC"); 
								$stmt->execute();

								// set the resulting array to associative
								foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
									echo '<span class="uploads"><a class="lightbox_trigger" href="'.$row['file_location'].'"><img src="'.$row['file_location'].'" alt="user image" height="200" width="200"></a><br>
									'.$row['description'].'</span>
									';
								}
							}
							catch(PDOException $e) {
								echo "Error: " . $e->getMessage();
							}
							echo '</div>';
							$conn = null;
						} else {
							echo '<h2>Sign in to use this page</h2>';
						} 
					?>			
				</div>
			</div>
		</div>
	</div>
<script src="js/lightbox.js"></script>
</body>
</html>