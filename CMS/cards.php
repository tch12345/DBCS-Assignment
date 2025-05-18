<?php
require "Config/session.php";
require "Config/connect.php";
if (!isset($_COOKIE['user'])) {
  header("Location: login2.0.php");
  exit();
}
$page_name="Cards";
require "Required/Header.php";
?>

    <div class="container-fluid py-2">
      <div class="row">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3">Cards List</h6>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Owner</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Card</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Brand</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Expiration Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $query = "SELECT c.*,'**** **** **** ' + RIGHT(CONVERT(nvarchar, DecryptByCert(Cert_ID('DataEncryptionCert'), card_number_encrypted)), 4)  AS card_number,u.name FROM cards c JOIN users u ON c.user_id = u.user_id WHERE u.role = 'user';";
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
                        <p class="text-xs font-weight-bold mb-0"><?php echo $data['card_number'];?></p>
                      </td>
                      <td class="align-middle text-center text-sm">
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm"><?php echo $data['card_brand'];?></h6>
                          </div>
                        </div>
                      </td>
                      <td class="align-middle text-center">
                      <?php 
                          echo is_null($data['expiration_date']) 
                              ? 'not set' 
                              : $data['expiration_date']->format('d/m/y');
                      ?>
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