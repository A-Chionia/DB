<?php

session_start();
session_unset();
session_destroy();

// Redirect to index
header("Location: ../banner.php");
exit();

?>