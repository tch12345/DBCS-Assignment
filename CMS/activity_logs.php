<?php
require "Config/session.php";
require "Config/connect.php";
if ( !isset($_SESSION['name']) || $_SESSION['id']!=md5(1) ) {
  header("Location: login2.0.php");
  exit();
}

$page_name="Logs";
require "Required/Header.php";
?>

    <div class="container-fluid py-2">
      <div class="row">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                <div class="d-flex justify-content-between align-items-center px-3">
                  <h6 class="text-white text-capitalize m-0">Activity Logs</h6>
                </div>
              </div>
            </div>


            <div class="card-body px-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Activity Log ID</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">User</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Activity</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Description</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">IP Address</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">User Agent</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Time Stamp</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $query = "SELECT
                                    l.*,
                                    u.name
                                FROM
                                    activity_logs l
                                LEFT JOIN
                                    users u ON l.user_id = u.user_id
                                ORDER BY
                                    activity_log_id DESC;";
                    $stmt = sqlsrv_query($conn, $query);
                    if($stmt){
                      while($data=sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                       
                    ?>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm"><?php echo $data['activity_log_id'];?></h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0"><?php echo ($data['name']?$data['name']:"No User");?></p>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm"><?php echo $data['activity_type'];?></h6>
                          </div>
                        </div>
                      </td>
                      <td class="align-middle text-center">
                            <p class="text-xs mb-0"><?php echo $data['activity_description'];?></p>
                      </td>
                      <td class="align-middle text-center">
                            <h6 class="mb-0 text-sm"><?php echo $data['ip_address'];?></h6>
                      </td>
                      <td class="align-middle text-center">
                        <p class="text-xs mb-0"><?php echo $data['user_agent'];?></p>
                      </td>
                      <td class="align-middle text-center">
                        <h6 class="mb-0 text-sm"> <?php echo $data['activity_timestamp']->format('d F Y H:i'); ?></h6>
                      </td>
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