<?php
//Collect guestbook submission info

$servername = "localhost";
$username = "fdlogwrite";
$password = "adminpassword";

//log in as fdlogwrite
$firstnameErr = $lastnameErr = $callsignErr = "";
$first_name = $last_name = $callsign = $comments ="";
$isReady = 0;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (empty($_POST["first"]) or empty($_POST["last"])) {
		$isReady = 0;
	} else {
		$isReady = 1;
	}
	if (empty($_POST["first"])) {
		$firstnameErr = "First name is required";
	} else {
		$first_name = test_input($_POST["first"]);
	}

	if (empty($_POST["last"])) {
		$lastnameErr = "Lastname is required";
	} else {
		$last_name = test_input($_POST["last"]);
	}
	$callsign = strtoupper(test_input($_POST["callsign"]));
	$comments = test_input($_POST["comments"]);
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
		$comments = htmlspecialchars($_POST["comments"]);
		$stmt = $conn->prepare("INSERT INTO guestbook(callsign, first_name, last_name, comments) VALUES (:callsign, :first_name, :last_name, :comments)");
		$stmt->bindParam(':callsign', $callsign);
		$stmt->bindParam(':first_name', $first_name);
		$stmt->bindParam(':last_name', $last_name);
		$stmt->bindParam(':comments', $comments);
		
		$stmt->execute();
	}

	catch(PDOException $e)
		{
		echo "Connection failed: " . $e->getMessage();
		}
	
$conn=null;
$first_name = $last_name = $callsign = $comments = "";
$isReady = 0;
} else {
	/*echo '<span class="error"> Enter required fields!</span>';*/
}
?>
