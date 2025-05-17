<?php
require "Config/session.php";
require "Config/connect.php";

if (isset($_GET['user_id'])) {
  $user_id = $_GET['user_id'];

  $query = "DELETE FROM [users] WHERE user_id = ?";
  $params = array($user_id);

  $stmt = sqlsrv_query($conn, $query, $params);

  if ($stmt) {
    header("Location: user.php");
    exit();
  } else {
    echo "Failed to delete user.";
  }
} else {
  echo "No user ID specified.";
}
?>
