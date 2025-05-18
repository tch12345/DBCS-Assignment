<?php
require "Config/session.php";
require "Config/connect.php";

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    $query = "DELETE FROM [products] WHERE product_id = ?";
    $params = array($product_id);

    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt) {
        header("Location: productDash.php");
        exit();
    } else {
        echo "Failed to delete product.";
    }
    } else {
    echo "No product ID specified.";
}
?>
