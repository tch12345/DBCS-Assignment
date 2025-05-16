<?php
require "Config/session.php";
require "Config/connect.php";
if (!isset($_COOKIE['user'])) {
  header("Location: login2.0.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    if($role == 'admin'){
      if($_SESSION['id']!=md5(1)){
        $role == 'user';
      }
    }
    
    // Check if email already exists
    $query = "SELECT 1 FROM users WHERE email = ?";
    $params = array($email);
    $stmt = sqlsrv_query($conn, $query, $params);
    
    if (sqlsrv_fetch($stmt)) {
        echo "<script>alert('Email is already registered.');</script>";
    } else {
        // Insert the new user
        $insertQuery = "INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, ?)";
        $params = array($name, $email, $password, $phone, $role);
        
        $stmt = sqlsrv_query($conn, $insertQuery, $params);
        
        if ($stmt) {
            echo "<script>alert('User registered successfully!');</script>";
        }
       
    }
}

$page_name="Users";
require "Required/Header.php";
?>

    <div class="container-fluid py-2">
      <div class="row">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                <div class="d-flex justify-content-between align-items-center px-3">
                  <h6 class="text-white text-capitalize m-0">Add Users</h6>
                </div>
              </div>
            </div>


            <div class="card-body px-0 pb-2">
             
                <form method="post" class="px-3">
                  <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" required class="form-control border border-secondary px-3" name="name" id="name" placeholder="Enter name">
                  </div>
                  <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" required class="form-control border border-secondary px-3" id="email"  name="email" placeholder="Enter email">
                  </div>
                  <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" required class="form-control border border-secondary px-3" id="password"  name="password" placeholder="Enter password">
                  </div>
                  <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="tel" required class="form-control border border-secondary px-3" id="phone" name="phone" placeholder="Enter phone number">
                  </div>
                 <div class="mb-3">
                  <label for="role" class="form-label">Role</label>
                  <select class="form-select border border-secondary px-3" name="role" required id="role">
                    <option value="">Select role</option>
                    <option value="user">User</option>
                    <option value="finance">Finance</option>
                    <?php 
                     if($_SESSION['id']==md5(1)){
                      echo  '<option value="admin">Admin</option>';
                    }
                    ?>
                  </select>
                </div>
                <div class="d-flex  justify-content-end px-3 pt-2 ">
                  <input type="submit" class="btn btn-dark text"> 
                </div>
                </form>
              </div>

              </form>
            </div>

          </div>
        </div>
      </div>
    </div>
<?php
require "Required/Footer.php";