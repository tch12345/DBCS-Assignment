<?php
require "../Config/session.php";
require "../Config/connect.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['product_name'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

$user_id = $_SESSION['customer_id'] ?? null;
$product_name = trim($_POST['product_name']);
if (!$user_id) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}
if (empty($product_name)) {
    echo json_encode(['status' => 'error', 'message' => 'Product name is required']);
    exit;
}

$checkSql = "SELECT cart_id, quantity FROM cart WHERE user_id = ? AND product_name = ? AND (deleted_at IS NULL OR deleted_at > GETDATE())";
$params = [$user_id, $product_name];
$stmt = sqlsrv_query($conn, $checkSql, $params);

if ($stmt === false) {
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
    exit;
}

$existing = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if ($existing) {
    $currentQty = (int)$existing['quantity'];
    if ($currentQty > 1) {
        // Decrement quantity by 1
        $updateSql = "UPDATE cart SET quantity = quantity - 1 WHERE cart_id = ?";
        $updateStmt = sqlsrv_query($conn, $updateSql, [$existing['cart_id']]);
        if ($updateStmt) {
            echo json_encode(['status' => 'success', 'message' => 'Product quantity decreased']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update quantity']);
        }
    } else {
        // Quantity is 1, cannot reduce further
        echo json_encode(['status' => 'info', 'message' => 'Minimum quantity reached']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Product not found in cart']);
}
