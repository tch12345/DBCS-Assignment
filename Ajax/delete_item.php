<?php
require "../Config/session.php";
require "../Config/connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_name'])) {
    $user_id = $_SESSION['customer_id'] ?? null;
    $product_name = trim($_POST['product_name']);

    if (!$user_id) {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
        exit;
    }

    $sql = "UPDATE cart SET deleted_at = GETDATE() WHERE user_id = ? AND product_name = ? AND (deleted_at IS NULL OR deleted_at > GETDATE())";
    $params = [$user_id, $product_name];
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete item']);
    } else {
        echo json_encode(['status' => 'success', 'message' => 'Item deleted successfully']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
