<?php
session_start();
session_unset();
session_destroy();
echo '<META http-equiv="refresh" content="0;URL=index.php">';
?>