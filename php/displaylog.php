<?php
echo "<table style='border: solid 1px black;'>";
echo '<form id="guestbook" enctype="multipart/form-data" action="/php/delete.php" method="post"><tr><th>Call Sign</th><th>Class</th><th>Section</th><th>Band</th><th>Mode</th>';
if (!empty($_SESSION['priv'])) {
	if ($_SESSION['priv'] === "admin") {
		echo '<th><input type="submit" value="Delete" /></th>';
	}
}
echo '</tr>';

$servername = "localhost";
$username = "fdlogread";
$password = "password";
$dbname = "fdlogdb";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$stmt = $conn->prepare("SELECT callsign, operating_class, section, band, mode, logid FROM logbook ORDER BY logid DESC");
    $stmt->execute();

    // set the resulting array to associative
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    	echo "<tr>";
        echo "<td style='width:150px;border:1px solid black;'>".$row['callsign']."</td>";
        echo "<td style='width:150px;border:1px solid black;'>".$row['operating_class']."</td>";
        echo "<td style='width:150px;border:1px solid black;'>".$row['section']."</td>";
		echo "<td style='width:150px;border:1px solid black;'>".$row['band']."</td>";
		echo "<td style='width:150px;border:1px solid black;'>".$row['mode']."</td>";
    	
        if (!empty($_SESSION['priv']) && $_SESSION['priv'] === "admin") {
        		echo '<td style=\'width:75px;border:1px solid black;text-align:center;\'><input type="checkbox" name="delete[]" value="'.$row['logid'].'" />&nbsp;</td></tr>'."\n";
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
<input type="hidden" name="key" value="logid">
<input type="hidden" name="table" value="logbook">
<input type="hidden" name="page" value="/view-log.php">
</form>';
echo "</table>";
?>