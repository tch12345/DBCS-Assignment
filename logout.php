<?php
setcookie("user", "", time() - 3600, "/");

session_start();
session_unset();
session_destroy();

header("Location: login2.0.php");
exit;