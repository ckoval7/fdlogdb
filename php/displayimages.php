<?php
$servername = "localhost";
$rd_username = "fdlogread";
$rd_password = "password";
$dbname = "fdlogdb";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $rd_username, $rd_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $stmt = $conn->prepare("SELECT display_html, description FROM images ORDER BY image_id DESC"); 
    $stmt->execute();

    // set the resulting array to associative
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
		echo '<span class="uploads">'.$row['display_html'].'<br>
		'.$row['description'].'</span>';
    }
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
?>