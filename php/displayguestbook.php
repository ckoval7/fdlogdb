<?php
echo "<table style='border: solid 1px black;'>";
echo '<form id="guestbook" enctype="multipart/form-data" action="/php/delete.php" method="post"><tr><th>First Name</th><th>Last Name</th><th>Call Sign</th>';
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
    $stmt = $conn->prepare("SELECT guest_id, first_name, last_name, callsign FROM guestbook ORDER BY guest_id DESC LIMIT 25"); 
    $stmt->execute();

    // set the resulting array to associative
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    	echo "<tr>";
        echo "<td style='width:150px;border:1px solid black;'>".$row['first_name']."</td>";
        echo "<td style='width:150px;border:1px solid black;'>".$row['last_name']."</td>";
        echo "<td style='width:150px;border:1px solid black;'>".$row['callsign']."</td>";
    	
        if (!empty($_SESSION['priv'])) {
        	if ($_SESSION['priv'] === "admin") {
        		echo '<td style=\'width:75px;border:1px solid black;text-align:center;\'><input type="checkbox" name="delete[]" value="'.$row['guest_id'].'" />&nbsp;</td></tr>'."\n";
        	}
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
<input type="hidden" name="key" value="guest_id">
<input type="hidden" name="table" value="guestbook">
<input type="hidden" name="page" value="/guestbook.php">
</form>';
echo "</table>";
?>