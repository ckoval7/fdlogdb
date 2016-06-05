<?php
include 'paginate.php';
$servername = "localhost";
$username = "fdlogread";
$password = "password";
$dbname = "fdlogdb";
$uuid = $_SESSION['uuid'];
$_SESSION["key"] = "logid";
$_SESSION["table"] = "logbook";
$_SESSION["page"] = "/enter-log.php";
$table = "logbook WHERE logger_id = '$uuid'";

$pages = paginate($table);

echo "<table style='border: solid 1px black;'>";
echo '<form id="guestbook" enctype="multipart/form-data" action="/php/delete.php" method="post">
<tr><th>Call Sign</th><th>Class</th><th>Section</th><th>Band</th><th>Mode</th>';
if (!empty($_SESSION['priv'])) {
		echo '<th><input type="submit" value="Delete" /></th>';
}
echo '</tr>';

try {
	$offset = getPage($table);
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$stmt = $conn->prepare("SELECT callsign, operating_class, section, band, mode, logid FROM logbook WHERE logger_id = '$uuid' ORDER BY logid DESC  LIMIT $offset, $limit");
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
    	
        if (!empty($_SESSION['priv'])) {
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
</form>
</table>';
page_buttons($table);
?>