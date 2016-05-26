<?php
echo "<table style='border: solid 1px black;'>";
echo '<tr><th>First Name</th><th>Last Name</th><th>Call Sign</th>';
if (!empty($_SESSION['priv'])) {
	if ($_SESSION['priv'] === "admin") {
		echo '<th>Delete</th>';
	}
}
echo '</tr>';

class TableRows extends RecursiveIteratorIterator { 
    function __construct($it) { 
        parent::__construct($it, self::LEAVES_ONLY); 
    }

    function current() {
        return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
    }

    function beginChildren() { 
        echo "<tr>"; 
    } 

    function endChildren() {
		if (!empty($_SESSION['priv'])) {
			if ($_SESSION['priv'] === "admin") {
				echo '<td style=\'width:75px;border:1px solid black;text-align:center;\'><input type="checkbox" name="delete" />&nbsp;</td></tr>'."\n";
			}
		} else {
			echo "</tr>" . "\n";
		}
    } 
} 

$servername = "localhost";
$username = "fdlogread";
$password = "password";
$dbname = "fdlogdb";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT first_name, last_name, callsign FROM guestbook ORDER BY guest_id DESC"); 
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) { 
        echo $v;
    }
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
echo "</table>";
?>