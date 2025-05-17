<?php
require "Config/session.php";
require "Config/connect.php";
if (!isset($_COOKIE['user'])) {
  header("Location: login2.0.php");
  exit();
}

if (!isset($_GET['user_id'])) {
  die("No user ID provided.");
}

$user_id = $_GET['user_id'];
$script="";
// Fetch current user data
$query = "SELECT * FROM users WHERE user_id = ?";
$params = array($user_id);
$stmt = sqlsrv_query($conn, $query, $params);
$user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if (!$user) {
  die("User not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $role = $_POST['role'];

  // Prevent non-admin from assigning admin role
  if ($role == 'admin' && $_SESSION['id'] != md5(1)) {
    $role = 'user';
  }

  $updateQuery = "UPDATE users SET name = ?, email = ?, phone = ?, role = ? WHERE user_id = ?";
  $updateParams = array($name, $email, $phone, $role, $user_id);
  $updateStmt = sqlsrv_query($conn, $updateQuery, $updateParams);

  if ($updateStmt) {
    $script .= "
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: 'User updated successfully!',
        confirmButtonColor: '#3085d6'
      }).then(() => {
        window.location.href = 'user.php';
      });
    ";
} else {
  $script .= "
      Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: 'Failed to update user.',
        confirmButtonColor: '#d33'
      });
    ";

}

}

$page_name = "Users";
require "Required/Header.php";
?>

<div class="container-fluid py-2">
  <div class="row">
    <div class="col-12">
      <div class="card my-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
          <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
            <div class="d-flex justify-content-between align-items-center px-3">
              <h6 class="text-white text-capitalize m-0">Edit User</h6>
            </div>
          </div>
        </div>

        <div class="card-body px-0 pb-2">
          <form method="POST" class="px-3">
            <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input type="text" required class="form-control border border-secondary px-3" name="name" id="name" value="<?php echo htmlspecialchars($user['name']); ?>">
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" required class="form-control border border-secondary px-3" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
            </div>

            <div class="mb-3">
              <label for="phone" class="form-label">Phone</label>
              <input type="tel" required class="form-control border border-secondary px-3" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
            </div>

            <div class="mb-3">
              <label for="role" class="form-label">Role</label>
              <select class="form-select border border-secondary px-3" name="role" required id="role">
                <option value="">Select role</option>
                <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
                <option value="finance" <?php if ($user['role'] == 'finance') echo 'selected'; ?>>Finance</option>
                <?php 
                  if ($_SESSION['id'] == md5(1)) {
                    echo '<option value="admin" ' . ($user['role'] == 'admin' ? 'selected' : '') . '>Admin</option>';
                  }
                ?>
              </select>
            </div>

            <div class="d-flex justify-content-end px-3 pt-2">
              <input type="submit" class="btn btn-dark text" value="Update User">
              <a href="users.php" class="btn btn-secondary ms-2">Cancel</a>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>
</div>

<?php require "Required/Footer.php"; ?>
