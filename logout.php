<?php 
include "app/config.php";

unset($_SESSION['userId']);
unset($_SESSION['userName']);
unset($_SESSION['userEmail']);

header("LOCATION: index.php")
?>