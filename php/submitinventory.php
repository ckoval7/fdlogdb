<?php
$servername = "localhost";
$username = "fdlogwrite";
$password = "adminpassword";

//log in as fdlogwrite
$makeErr = $modelErr = $phoneErr = '';
$phone = $model = $make = $description = $type = '';
$isReady = 0;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST["make"]) or empty($_POST["model"])) {
		$isReady = 0;
	} else {
		$isReady = 1;
	}
	if (empty($_POST["make"])) {
		$makeErr = "Make/brand is required";
	} else {
		$make = test_input($_POST["make"]);
	}

	if (empty($_POST["model"])) {
		$modelnameErr = "Model is required";
	} else {
		$model = test_input($_POST["model"]);
	}
	$phone = test_input($_POST["phone"]);
	$phone = preg_replace('/\D/', '', $phone);
	$_SESSION['phone'] = $phone;
	$description = test_input($_POST["description"]);
	$type = test_input($_POST["type"]);
}

function test_input($data) {
$data = trim($data);
$data = htmlspecialchars($data);
return $data;
}

if ($isReady === 1) {	
	try {
		$conn = new PDO("mysql:host=$servername;dbname=fdlogdb", $username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$description = htmlspecialchars($_POST["description"]);
		$stmt = $conn->prepare("INSERT INTO inventory(user_id, contact_number, item_make, item_model, item_description, item_type) VALUES (:userid, :phone, :make, :model, :description, :type)");
		$stmt->bindParam(':userid', $_SESSION['uuid']);
		$stmt->bindParam(':phone', $phone);
		$stmt->bindParam(':make', $make);
		$stmt->bindParam(':model', $model);
		$stmt->bindParam(':description', $description);
		$stmt->bindParam(':type', $type);
		$stmt->execute();
	}

	catch(PDOException $e)
		{
		echo "Connection failed: " . $e->getMessage();
		}
	
$conn=null;
$make = $model = $phone = $description = $type = "";
$isReady = 0;
} else {
	/*echo '<span class="error"> Enter required fields!</span>';*/
}
?>