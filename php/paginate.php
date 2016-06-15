<?php
$limit = 25;
function getPage($table) {
	$pages = paginate($table);
	global $limit;
	$offset = 0;
	if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET["page"])) {
		$page = preg_replace('/\D/', '', $_GET["page"]);
		$offset = ($page * $limit) - $limit;
	} else {
		$page = 1;
	}
	if ($page > $pages) {
		$offset = ($pages * $limit) - $limit;
	}
	return max($offset, 0);
}

function paginate($table) {
	$servername = "localhost";
	$dbusername = "fdlogread";
	$dbpassword = "password";
	$dbname = "fdlogdb";
	global $limit;
	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$stmt = $conn->prepare("SELECT COUNT(*) FROM $table");
		$stmt->execute();
		$count = $stmt->fetch();
	} catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}
	$pages = ceil($count[0] / $limit);
	//echo $count[0]."<br>".$limit."<br>".$pages;
	return $pages;
}

function page_buttons($table) {
	if (!empty($_GET["page"])) {
		$page = $_GET["page"];
	} else {
		$page = 1;
	}
	if (!empty($_SESSION["page"])) {
		$link = $_SESSION["page"];
	} else {
		$link = "#";
	}
	//$table = $_SESSION['table'];
	$pages = paginate($table);
	echo '<div id="pages">';

	//Back Button
	if (!empty($pages) && $pages > 1 && $page > 1) {
		$page_back = $page - 1;
		echo '<span id="back"><a href="'.$link.'?page=1">&lt;&lt; First&nbsp;&nbsp;</a><a href="'.$link.'?page='.$page_back.'">&lt; Previous</a></span>';
	} else {
		echo '<span id="back">&nbsp;</span>';
	}
	//Number of pages
	if ($pages > 1) {echo '<span id="page_count">'.$pages.' Pages<br>Page: '.max($page, 1).'</span> ';}
	//Next Button
	if (!empty($pages) && $pages > 1 && $pages > $page) {
			if (!empty($pages) && $pages > 1) {
				$page_next = $page + 1;
				echo '<span id="next"><a href="'.$link.'?page='.$page_next.'"> Next &gt; </a>&nbsp;<a href="'.$link.'?page='.$pages.'"> Last &gt;&gt; </a></span>';
			}else {
				$page_next = 2;
				echo '<span id="next"><a href="'.$link.'?page='.$page_next.'"> Next &gt; </a></span>';
		}
	}
	echo '</div>';
}