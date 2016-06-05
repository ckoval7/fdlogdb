<?php
$servername = "localhost";
$username = "fdlogread";
$password = "password";
$dbname = "fdlogdb";

echo "<table style='border: solid 1px black;'>
<tr><th>Call Sign</th><th>Class</th><th>Section</th><th>Band</th><th>Mode</th></tr>";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$stmt = $conn->prepare("SELECT callsign, operating_class, section, band, mode FROM logbook ORDER BY logid DESC LIMIT 15");
    $stmt->execute();

    // set the resulting array to associative
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
		if (!empty($row['band']) && $row['band'] == 125) {
			$band = "1.25m";
		} elseif (!empty($row['band']) && $row['band'] == 247) {
			$band = "Satellite";
		} else{
			$band = $row['band']."m";
		}
    	echo "<tr>";
        echo "<td style='width:150px;border:1px solid black;'>".$row['callsign']."</td>";
        echo "<td style='width:150px;border:1px solid black;'>".$row['operating_class']."</td>";
        echo "<td style='width:150px;border:1px solid black;'>".$row['section']."</td>";
		echo "<td style='width:150px;border:1px solid black;'>".$band."</td>";
		echo "<td style='width:150px;border:1px solid black;'>".$row['mode']."</td>";
        echo "</tr>" . "\n";
    }
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
echo "</table>";
?>