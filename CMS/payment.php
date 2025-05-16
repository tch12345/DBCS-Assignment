<?php
require "Config/session.php";
require "Config/connect.php";
if ( !isset($_SESSION['name']) ) {
  header("Location: login2.0.php");
  exit();
}

$page_name="Payment";

require "Required/Header.php";
?>

    <div class="container-fluid py-2">
      <div class="row">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                <div class="d-flex justify-content-between align-items-center px-3">
                  <h6 class="text-white text-capitalize m-0">Payment List</h6>
                </div>
              </div>
            </div>


            <div class="card-body px-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Payment ID</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Customer</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Items</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Price</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantity</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Create At</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $query = "SELECT 
                                u.name,
                                p.*
                                FROM 
                                    users u
                                JOIN 
                                    payment p
                                    ON p.user_id_md5 = CONVERT(VARCHAR(32), HASHBYTES('MD5', CAST(u.user_id AS VARCHAR)), 2)
                                    order by payment_id desc;";
                    $stmt = sqlsrv_query($conn, $query);
                    if($stmt){
                      while($data=sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $phonearray=json_decode($data['items'],true);
                        $price=json_decode($data['prices'],true);
                         $quantities=json_decode($data['quantities'],true);
                    ?>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm"><?php echo $data['payment_id'];?></h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0"><?php echo $data['name'];?></p>
                        
                      </td>
                      <td class="align-middle text-center text-sm">
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <?php 
                            foreach($phonearray as $phone){
                                echo '<h6 class="mb-0 text-sm">'.$phone.'</h6>';
                            }
                            ?>
                          </div>
                        </div>
                      </td>
                      <td class="align-middle text-center">
                       <?php
                       foreach($price as $p){
                                echo '<p class="text-xs mb-0">'.$p.'</p>';
                            }
                       ?>
                      </td>
                      <td class="align-middle text-center">
                       <?php
                       foreach($quantities as $quantity){
                                 echo '<h6 class="mb-0 text-sm">'.$quantity.'</h6>';
                            }
                       ?>
                      </td>
                      <td class="align-middle text-center">
                        <p class="text-xs mb-0">
                       <?php
                       
                     echo $data['created_at']->format('j F Y');
                       ?>
                       </p>
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