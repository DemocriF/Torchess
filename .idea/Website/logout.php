<?php
// logout.php
session_start();
session_destroy();
header("Location: Main Page.html");
exit();
?>
