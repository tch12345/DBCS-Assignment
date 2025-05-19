<?php
require "Config/connect.php";
require "Config/session.php";

if(!isset($_SESSION['customer_id']) || !isset($_SESSION['customer_name'])){
    header("Location: index.php");
    exit();
}

$script="";
if(($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['update'])){
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $currentPassword = $_POST['current-password'] ?? '';
    $newPassword = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm-password'] ?? '';
    
    $sql="SELECT * FROM USERS 
    WHERE CONVERT(VARCHAR(32), HASHBYTES('MD5', CAST(user_id AS VARCHAR)), 2) = ? ";
    $array=array(
      $_SESSION['customer_id']
    );
    $stmt = sqlsrv_prepare($conn, $sql, $array);
    sqlsrv_execute($stmt);
    $data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    
    $checkpassword="SELECT * FROM users WHERE CONVERT(VARCHAR(32), HASHBYTES('MD5', CAST(user_id AS VARCHAR)), 2) = ? and password=?";
     $array2=array(
      $_SESSION['customer_id'],
      $currentPassword
    );
    $stmt2 = sqlsrv_prepare($conn, $checkpassword, $array2);
    sqlsrv_execute($stmt2);
    sqlsrv_fetch($stmt2);
    if(!sqlsrv_has_rows($stmt2)){
      $script .= "swal.fail('Password Incorrect','Please Check it')";
    }else{

   
    $updateFields = [];
    $params = [];

    $updateFields[] = "name = ?";
    $params[] = $name;
    $updateFields[] = "email = ?";
    $params[] = $email;
    $updateFields[] = "phone = ?";
    $params[] = $phone;
    
    if(isset($newPassword) && $newPassword!=''){
      if($newPassword == $confirmPassword){
         $updateFields[] = "password = ?";
          $params[] = $newPassword;
      }
    }
    
    $params[] = $_SESSION['customer_id'];

    $updatesql = "UPDATE users SET " . implode(", ", $updateFields) . " WHERE CONVERT(VARCHAR(32), HASHBYTES('MD5', CAST(user_id AS VARCHAR)), 2) = ?";

    $stmt = sqlsrv_prepare($conn, $updatesql, $params);
    sqlsrv_execute($stmt);
    if ($stmt === false) {
        $script .= "swal.error('Update Error','Please Check it')";
    } else {
        $_SESSION['customer_name']=$name;
        $script .= "swal.success('Profile updated successfully')";
    }
  }
}

$sql="SELECT * FROM USERS 
WHERE CONVERT(VARCHAR(32), HASHBYTES('MD5', CAST(user_id AS VARCHAR)), 2) = ? ";
$array=array(
  $_SESSION['customer_id']
);
$stmt = sqlsrv_prepare($conn, $sql, $array);
if ($stmt) {
    sqlsrv_execute($stmt);
    if ($data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
       
    } else {
          header("Location: index.php");
    exit();
    }
}



require "Required/header.php";
?>
<section id="edit-profile" class="position-relative padding-large overflow-hidden">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card p-4 shadow-sm rounded-4">
          <div class="card-body">
            <h3 class="mb-4 text-uppercase text-center">Edit Profile</h3>

            <form method="post" id="edit-profile-form">
              <div class="mb-3">
                <label for="fullname" class="form-label text-uppercase">Full Name</label>
                <input type="text" class="form-control shadow-none" name="name" id="fullname" value="<?php echo $data['name'];?>" required dplaceholder="Enter your full name">
              </div>

              <div class="mb-3">
                <label for="email" class="form-label text-uppercase">Email Address</label>
                <input type="email" class="form-control shadow-none" name="email" id="email" value="<?php echo $data['email'];?>" required placeholder="you@example.com">
              </div>

              <div class="mb-3">
                <label for="phone" class="form-label text-uppercase">Phone Number</label>
                <input type="tel" class="form-control shadow-none" name="phone" id="phone" value="<?php echo $data['phone'];?>" required placeholder="+1234567890">
              </div>

              <div class="mb-3">
                <label for="password" class="form-label text-uppercase">Current Password</label>
                <input type="password" class="form-control shadow-none" name="current-password" required id="password" placeholder="********">
              </div>

              <div class="mb-3">
                <label for="password" class="form-label text-uppercase">New Password</label>
                <input type="password" class="form-control shadow-none"  name="password" id="password" placeholder="********">
              </div>

              <div class="mb-3">
                <label for="confirm-password" class="form-label text-uppercase">Confirm Password</label>
                <input type="password" class="form-control shadow-none" name="confirm-password"  id="confirm-password" placeholder="********">
              </div>

              <div class="text-end">
                <button type="submit" class="btn btn-dark px-4 text-uppercase">Save Changes</button>
                <input type="hidden" value="1" name="update">
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