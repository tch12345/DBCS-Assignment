<?php
require "Config/connect.php"; 
require "Required/header.php";
$script="";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name             = trim($_POST['name']);
    $email            = trim($_POST['email']);
    $password         = $_POST['password'];
    $phone            = $_POST['phone'];
    $confirm_password = $_POST['conpassword'];
    $role             = 'user'; // default role

    // Basic validation
    
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $script.= "Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'All fields are required.'
              });";
    }elseif ($password !== $confirm_password) {
        $script.="Swal.fire({
                icon: 'error',
                title: 'Password Mismatch',
                text: 'Passwords do not match.'
              });";
    } else {
        // Check if email already exists
        $checkQuery = "SELECT * FROM users WHERE email = ?";
        $params = array($email);
        $stmt = sqlsrv_query($conn, $checkQuery, $params);

        if ($stmt && sqlsrv_fetch_array($stmt)) {
           $script.="Swal.fire({
                      icon: 'warning',
                      title: 'Duplicate Email',
                      text: 'Email already registered.'
                    });";
        } else {
            
          

            // Insert user
            $insertQuery = "INSERT INTO users (name, email, password,phone, role,is_verified,create_at) VALUES (?, ?,?, ?, ?,?,?)";
            $insertParams = array($name, $email, $password,$phone, $role,0,date('Y-m-d H:i:s'));
            $insertStmt = sqlsrv_query($conn, $insertQuery, $insertParams);

            if ($insertStmt) {
               $script.="Swal.fire({
                  icon: 'success',
                  title: 'Success',
                  text: 'Registration successful!',
                  confirmButtonText: 'Continue'
                }).then((result) => {
                  if (result.isConfirmed) {
                    window.location.href = 'login.php';
                  }
                });";
            }
        }
    }
}
?>

<section id="register" class="position-relative padding-large overflow-hidden">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card p-4 shadow-sm rounded-4">
          <div class="card-body">
            <h3 class="mb-4 text-uppercase text-center">Create an Account</h3>

            <form method="post" id="register-form">
              <div class="mb-3">
                <label for="fullname" class="form-label text-uppercase">Full Name</label>
                <input type="text" name="name" class="form-control shadow-none" id="fullname" placeholder="Enter your full name" required>
              </div>

              <div class="mb-3">
                <label for="email" class="form-label text-uppercase">Email Address</label>
                <input type="email"  name="email" class="form-control shadow-none" id="email" placeholder="you@example.com" required>
              </div>

              <div class="mb-3">
                <label for="phone" class="form-label text-uppercase">Phone</label>
                <input type="phone"  name="phone" class="form-control shadow-none" id="phone" placeholder="0111231234" required>
              </div>
              

              <div class="mb-3">
                <label for="password" class="form-label text-uppercase">Password</label>
                <input type="password" name="password" class="form-control shadow-none" id="password" placeholder="********" required>
              </div>
              

              <div class="mb-3">
                <label for="confirm-password" class="form-label text-uppercase">Confirm Password</label>
                <input type="password" name="conpassword" class="form-control shadow-none" id="confirm-password" placeholder="********" required>
              </div>

              <div class="text-end">
                <button type="submit" class="btn btn-dark px-4 text-uppercase">Register</button>
              </div>

              <div class="mt-3 text-center">
                <p class="mb-0">Already have an account? <a href="login.php" class="text-dark">Login</a></p>
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
