<?php
echo "<table style='border: solid 1px black;'>";
echo '<form id="guestbook" enctype="multipart/form-data" action="/php/delete.php" method="post"><tr><th>Category</th><th>Brand</th><th>Model</th><th>Description</th>';
if (!empty($_SESSION['priv'])) {
		echo '<th><input type="submit" value="Delete" /></th>';
}
echo '</tr>';

$servername = "localhost";
$username = "fdlogread";
$password = "password";
$dbname = "fdlogdb";
$uuid = $_SESSION['uuid'];
$_SESSION["key"] = "item_id";
$_SESSION["table"] = "inventory";
$_SESSION["page"] = "/inventory.php";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$stmt = $conn->prepare("SELECT item_make, item_model, item_description, item_type, item_id FROM inventory WHERE user_id = '$uuid' ORDER BY item_id DESC");
    $stmt->execute();

    // set the resulting array to associative
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    	echo "<tr>";
        echo "<td style='width:150px;border:1px solid black;'>".$row['item_type']."</td>";
        echo "<td style='width:150px;border:1px solid black;'>".$row['item_make']."</td>";
        echo "<td style='width:150px;border:1px solid black;'>".$row['item_model']."</td>";
		echo "<td style='width:150px;border:1px solid black;'>".$row['item_description']."</td>";
    	
        if (!empty($_SESSION['priv'])) {
        		echo '<td style=\'width:75px;border:1px solid black;text-align:center;\'><input type="checkbox" name="delete[]" value="'.$row['item_id'].'" />&nbsp;</td></tr>'."\n";
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
?>