<?php
$servername = "localhost";
$rd_username = "fdlogread";
$rd_password = "password";
$dbname = "fdlogdb";
echo '<div class="row">';
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $rd_username, $rd_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $stmt = $conn->prepare("SELECT file_location, description FROM images ORDER BY image_id DESC"); 
    $stmt->execute();

    // set the resulting array to associative
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
		echo '<div class="uploads"><a class="lightbox_trigger" href="'.$row['file_location'].'"><img src="'.$row['file_location'].'" alt="user image" height="200" width="200"></a><br>
		'.$row['description'].'</div>
		';
    }
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
echo '</div>';
$conn = null;
?>
