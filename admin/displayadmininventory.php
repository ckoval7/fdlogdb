<?php
echo "<table style='border: solid 1px black;'>";
echo '<form id="guestbook" enctype="multipart/form-data" action="/php/delete.php" method="post">
<tr><th>User</th><th>Phone</th><th>Category</th><th>Brand</th><th>Model</th><th>Description</th>';
echo '<th><input type="submit" name="delete" value="Delete" /></th><th><input type="submit" name="station" value="Station" /></th>';
echo '</tr>';

$servername = "localhost";
$username = "fdlogread";
$password = "password";
$dbname = "fdlogdb";
$uuid = $_SESSION['uuid'];
$_SESSION["key"] = "item_id";
$_SESSION["table"] = "inventory";
$_SESSION["page"] = "inventory.php";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$stmt = $conn->prepare("SELECT users.call_sign, inventory.item_id, inventory.contact_number, inventory.item_make, inventory.item_model, inventory.item_description, inventory.item_type FROM inventory INNER JOIN users ON inventory.user_id=users.uuid ORDER BY user_id");
    $stmt->execute();

    // set the resulting array to associative
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    	echo "<tr>";
		echo "<td style='width:150px;border:1px solid black;'>".$row['call_sign']."</td>";
        echo "<td style='width:150px;border:1px solid black;'>".$row['contact_number']."</td>";
        echo "<td style='width:150px;border:1px solid black;'>".$row['item_type']."</td>";
        echo "<td style='width:150px;border:1px solid black;'>".$row['item_make']."</td>";
        echo "<td style='width:150px;border:1px solid black;'>".$row['item_model']."</td>";
		echo "<td style='width:150px;border:1px solid black;'>".$row['item_description']."</td>";
        echo '<td style=\'width:75px;border:1px solid black;text-align:center;\'><input type="checkbox" name="delete[]" value="'.$row['item_id'].'" />&nbsp;</td>'."\n";
		echo '<td style=\'width:75px;border:1px solid black;text-align:center;\'><input type="checkbox" name="station[]" value="'.$row['item_id'].'" />&nbsp;</td>'."\n";
        echo "</tr>" . "\n";
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