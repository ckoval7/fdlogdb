<?php
$error="";
$error2="";
$target_dir = "/img/user-uploads/";
$currentdir = getcwd();
/*$servername = "localhost";
$dbusername = "fdlogwrite";
$dbpassword = "adminpassword";*/
include 'db_passwords.php';
function test_input($data) {
	$data = trim($data);
	$data = htmlspecialchars($data);
	return $data;
	}
if (isset($_POST["submit"])) {
	$target_file = $target_dir.basename($_FILES["imageupload"]["name"]);
	echo $target_file.'<br>'.$currentdir;
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	if (!empty($_FILES["imageupload"]["tmp_name"])) {
		$check = getimagesize($_FILES["imageupload"]["tmp_name"]);	
		if($check !== false) {
			/*if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
				$error="Error! Sorry, only JPG, JPEG, PNG, and GIFs are allowed.";
				echo "Sorry, only JPG, JPEG, PNG, and GIFs are allowed.<br>";
				$uploadOk = 0;
			} else {*/
				//echo "File is an image - ".$check["mime"].".<br>";
				if ($_FILES["imageupload"]["size"] > 8192000) {
					$error="Error! Sorry, image is too large!";
					//echo "Sorry, image is too large!<br>";
					$uploadOk=0;
				} else {
					if (file_exists($target_file)) {
						$error="Error! Sorry, file already exists.";
						$uploadOk = 0;
					} else {
						echo '<br>:D<br>';
						$uploadOk = 1;
					}
				}
			//}
		} else {
			$error="Error! File is not an image.";
			$uploadOk=0;
		}
		if ($uploadOk == 0) {
			$error2="Error! Image upload failed.";
		} else {
			if (move_uploaded_file($_FILES["imageupload"]["tmp_name"],$currentdir . $target_file)) {
				$error2="The file ".basename($_FILES["imageupload"]["name"])." has been uploaded. ";
				$description = $_POST['description'];
				$description = test_input($description);
				try {
					$conn = new PDO("mysql:host=$servername;dbname=$dbname", $wr_username, $wr_password);
					// set the PDO error mode to exception
					$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$sql = $conn->prepare("INSERT INTO images (user_id, file_location, description) VALUES (:userid, '$target_file', :description)");
					$sql->bindParam(':userid', $_SESSION['uuid']);
					$sql->bindParam(':description', $description);
					$sql->execute();
				} catch(PDOException $e) {
					echo "Connection failed: " . $e->getMessage();
				}
				//echo "The file ".basename($_FILES["imageupload"]["name"])." has been uploaded. ";
			} else {
				$error="Error! Sorry, there was an error uploading your file.";
				//echo "Sorry, there was an error uploading your file.<br>";
			}
		}
	} else {
		$error="Please choose a file.";
	}
}
?>