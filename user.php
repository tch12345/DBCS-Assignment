<?php
require "Config/session.php";
require "Config/connect.php";
if (!isset($_COOKIE['user'])) {
  header("Location: login2.0.php");
  exit();
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
                  <h6 class="text-white text-capitalize m-0">Users List</h6>
                  <a href="add_users.php" class="btn btn-sm bg-dark text-light">+ Add User</a>
                </div>
              </div>
            </div>


            <div class="card-body px-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Email</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Phone</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Last Login</th>
                      <?php echo '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Role</th>'; ?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $query = "SELECT * FROM USERS WHERE role = 'user'";
                    if($_SESSION['id']==md5(1)){
                      $query = "SELECT * FROM USERS ORDER BY 'ROLE'";
                    }
                    $stmt = sqlsrv_query($conn, $query);
                    if($stmt){
                      while($data=sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                    ?>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm"><?php echo $data['name'];?></h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0"><?php echo $data['email'];?></p>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm"><?php echo $data['phone'];?></h6>
                          </div>
                        </div>
                      </td>
                      <td class="align-middle text-center">
                        <span class="text-secondary text-xs font-weight-bold"><?php 
                        $lastLogin = $data['last_login']; 
                        echo is_null($lastLogin) ? 'not login' : date('d/m/y', strtotime($lastLogin));?></span>
                      </td>
                      <?php
                      if($_SESSION['id']==md5(1)){
                        ?>
                        <td class="align-middle text-center text-sm">
                          <div class="d-flex px-2 py-1">
                            <div class="d-flex flex-column text-capitalize justify-content-center">
                              <h6 class="mb-0 text-sm"><?php echo $data['role'];?></h6>
                            </div>
                          </div>
                        </td>
                        <?php
                      }
                      ?>
                      
                    </tr>
                    <?php
                      }
                    }
                   ?>
                   
                    
                   
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php
require "Required/Footer.php";