<?php
require "Config/connect.php";
require "Config/session.php";

$script="";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    //login
    $username = $_POST['username'];
    $password = $_POST['password'];
    // SQL query with placeholders
    $sql = "SELECT * FROM users WHERE email = ? AND password = ? AND role='user'";
    // Define the parameters as references (pass by reference)
    $params = [
        &$username,  // Pass by reference
        &$password   // Pass by reference
    ];

    // Execute the query
    $stmt = sqlsrv_query($conn, $sql, $params);
    

    if($stmt){
        if ($data=sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            setcookie("customer",md5($data['user_id']),time() + (86400 * 30),"/");
            $_SESSION['customer_name']=$data['name'];
            $_SESSION['customer_id']=md5($data['user_id']);
            header("Location: index.php");
            exit();
        }else{
            $script .= "swal.error()";
        }
    }
   
}

require "Required/header.php";
?>
<section id="login" class="position-relative padding-large overflow-hidden ">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card p-4 shadow-sm rounded-4">
          <div class="card-body">
            <h3 class="mb-4 text-uppercase text-center">Login</h3>

            <form method="post"  id="login-form">
              <div class="mb-3">
                <label for="email" class="form-label text-uppercase">Email Address</label>
                <input type="email" class="form-control shadow-none" id="email" name="username" placeholder="you@example.com" required>
              </div>

              <div class="mb-3">
                <label for="password" class="form-label text-uppercase">Password</label>
                <input type="password" class="form-control shadow-none" id="password" name="password" placeholder="********" required>
              </div>

              <div class="text-end">
                <button type="submit"s class="btn btn-dark px-4 text-uppercase">Login</button>
              </div>
                 <input type="hidden" name="login" value="1">
              <div class="mt-3 text-center">
                <p class="mb-0">Don't have an account? <a href="register.php" class="text-dark">Sign Up</a></p>
                <p><a href="forgot-password.php" class="text-dark">Forgot Password?</a></p>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php
require "Required/footer.php";
?>
