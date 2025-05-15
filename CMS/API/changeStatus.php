<?php
require "../Config/session.php";
require "../Config/connect.php";
if (isset($_POST['id']) && isset($_POST['status']) && isset($_SESSION['id'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $responce = "300";
    $error = "Error";
    $query = "UPDATE transactions SET transaction_status = ? where transaction_id = ?";
    $params=array($status,$id);
    $stmt = sqlsrv_query($conn, $query, $params);
    if ($stmt) {
        $responce="200";
        $error="No Error";
    }


    $array = array(
        'responce' => $responce,
        'error' => $error
    );
    
    echo json_encode($array);
} else {
    // If required data is missing, return an error response
    echo json_encode(['error' => 'Missing parameters']);
}