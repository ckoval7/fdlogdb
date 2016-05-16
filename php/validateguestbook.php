<?php	
	$firstnameErr = $lastnameErr = $callsignErr = "";
	$first_name = $last_name = $callsign ="";
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (empty($_POST["name"])) {
			$firstnameErr = "First name is required";
		} else {
			$first_name = test_input($_POST["first"]);
		}

		if (empty($_POST["last"])) {
			$lastnameErrErr = "Lastname is required";
		} else {
			$last_name = test_input($_POST["last"]);
		}

		if (empty($_POST["callsign"])) {
			$callsignErr = "Callsign Required";
		} else {
			$callsign = test_input($_POST["website"]);
		}
	}
	function test_input($data) {
	$data = trim($data);
	$data = htmlspecialchars($data);
	return $data;
	}
?>