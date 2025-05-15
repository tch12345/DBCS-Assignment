<?php
require "Config/session.php";
require "Config/connect.php";
if (isset($_SESSION['name'])) {
    header("Location: user.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    //login
    $username = $_POST['username'];
    $password = $_POST['password'];
    // SQL query with placeholders
    $sql = "SELECT * FROM users WHERE email = ? AND password = ? AND role = 'admin'";
    // Define the parameters as references (pass by reference)
    $params = [
        &$username,  // Pass by reference
        &$password   // Pass by reference
    ];

    // Execute the query
    $stmt = sqlsrv_query($conn, $sql, $params);
    

    if($stmt){
        if ($data=sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            setcookie("user",md5($data['user_id']),time() + (86400 * 30),"/");
            $_SESSION['name']=$data['name'];
            $_SESSION['id']=md5($data['user_id']);
            header("Location: user.php");
            exit();
        }else{
            ?>
            <script>alert("test")</script>
            <?php
        }
    }
   
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, proxy-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <!---boostrap--->
   <link href="Css/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
   <link href="Css/Main/style.css" rel="stylesheet">

    <title>Document</title>
</head>
<body class="bg-black">
    <div class="d-flex flex-column justify-content-center align-items-center" style="height: 100vh;">
        <div class="d-flex flex-column justify-content-center align-items-center">
            <div class="mb-3">
                    <img alt="logo" width="48" decoding="async" src="Image/logo.png">
            </div>
            <div class="mb-3">
                <h1 class="heading fs-5">Sign in</h1>
            </div>
        </div>
        <div class="card rounded-4">
            <form class="form" method="POST">
            <div class="card-body m-3">
                <div class="form-group mb-2">
                    <label class="mb-2 email" for="email">Username</label>
                    <input type="text" name="username" class="form-control mb-3 rounded-1 text-start" id="email" placeholder="Your username">
                </div>
                <div class="form-group mb-2">
                    <label class="mb-2 email" for="email">Password</label>
                    <input type="password" name="password" class="form-control mb-3 rounded-1 text-start" id="email" placeholder="Your password">
                    <input type="hidden" name="login"  value="1">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn border form-control mb-2 rounded-1">Login</button>
                </div>
                <div class="d-flex align-items-center justify-content-center my-2">
                    
                </div>
                
            </div>
        </form>
              
        </div>
        
    </div>
    
</body>
</html>
<script src="Plugin/jquery/dist/jquery.js"></script>