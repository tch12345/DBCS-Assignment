<?php
function logFailedLogin($conn, $username) {
    // Lookup user name by username/email
    $sql = "SELECT * FROM users WHERE email = ?";
    $params = [&$username];
    $stmt = sqlsrv_query($conn, $sql, $params);

    $name = null;
    $userId=0;
    $activityType="login_failed";
    if ($stmt && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $name = $row['name'];
        $userId=$row['user_id'];
    }

    $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';

    // Handle IPv6 localhost (::1)
    if ($ip === '::1') {
        $ip= '127.0.0.1';
    }
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $time = date("Y-m-d H:i:s");

    if ($name) {
        $description = "[$time] - IP $ip failed login attempt for user '$name' (username: $username)\n";
    } else {
        $description = "[$time] - IP $ip failed login attempt for unknown username '$username'\n";
    }

    $params = [
        &$userId,
        &$activityType,
        &$description,
        &$ip,
        &$userAgent,
        &$time
    ];

    // Save to a log file
    $sql = "INSERT INTO activity_logs (user_id,activity_type,activity_description,ip_address,user_agent,activity_timestamp)
            VALUES (?, ?, ?, ?, ?, ?)";
     $stmt= sqlsrv_query($conn, $sql, $params);
  
}

function logSuccessLogin($conn, $username) {
    // Lookup user name by username/email
    $sql = "SELECT * FROM users WHERE email = ?";
    $params = [&$username];
    $stmt = sqlsrv_query($conn, $sql, $params);

    $name = null;
    $userId=0;
    $activityType="login";
    if ($stmt && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $name = $row['name'];
        $userId=$row['user_id'];
    }

    $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';

    // Handle IPv6 localhost (::1)
    if ($ip === '::1') {
        $ip= '127.0.0.1';
    }
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $time = date("Y-m-d H:i:s");

    if ($name) {
        $description = "[$time] - IP $ip Login Success user '$name' (username: $username)\n";
    }

    $params = [
        &$userId,
        &$activityType,
        &$description,
        &$ip,
        &$userAgent,
        &$time
    ];

    // Save to a log file
    $sql = "INSERT INTO activity_logs (user_id,activity_type,activity_description,ip_address,user_agent,activity_timestamp)
            VALUES (?, ?, ?, ?, ?, ?)";
     $stmt= sqlsrv_query($conn, $sql, $params);
  
}