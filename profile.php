<?php
require "Config/connect.php";
require "Config/session.php";


if(!isset($_SESSION['customer_id']) || !isset($_SESSION['customer_name'])){
    header("Location: index.php");
    exit();
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

<section id="profile" class="position-relative padding-large overflow-hidden">
  <div class="container">
    <div class="row">
      <div class="display-header text-uppercase text-dark text-center pb-3">
        <h2 class="display-7">Your Profile</h2>
      </div>
      <div class="col-12">
        <div id="profile-details-container">
          <!-- Profile info example -->
          <div class="profile-item d-flex align-items-center justify-content-between py-3 border-bottom">
            <div class="d-flex align-items-center">
              <img src="images/logo.png" alt="user" style="width: 60px; height: 60px; object-fit: cover;" class="me-3 rounded-circle">
              <div>
                <h6 class="mb-1 text-uppercase"><?php echo $data['name'];?></h6>
                <small class="text-muted">Email: <?php echo $data['email'];?></small><br>
                <small class="text-muted">Phone: <?php echo $data['phone'];?></small>
              </div>
            </div>
          </div>
          <?php
            $sql_card= "SELECT 
                    billing_address,card_brand,RIGHT(card_number, 4) AS last4digits
                FROM cards
                WHERE CONVERT(VARCHAR(32), HASHBYTES('MD5', CAST(user_id AS VARCHAR)), 2) = ?;";
              $array=array(
                $_SESSION['customer_id']
              );
              $stmt = sqlsrv_prepare($conn,  $sql_card, $array);
            if ($stmt) {
                sqlsrv_execute($stmt);
                if ($data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                  $payment_method=$data['card_brand']." ending in ".$data['last4digits'];
                  $decoded_data = json_decode($data['billing_address'], true);
                  $formatted_address = $decoded_data['address_line1'] . ', ' .
                                       $decoded_data['city'] . ', ' .
                                       $decoded_data['state'] . ' ' .
                                       $decoded_data['postal_code'] . ', ' .
                                       $decoded_data['country'];
                } else {
                  $payment_method="None";
                  $formatted_address="None";
                }
            }
          ?>
          <div class="profile-item py-3 border-bottom">
            <div class="d-flex justify-content-between">
              <h6 class="mb-1 text-uppercase">Shipping Address</h6>
            </div>
            <div class="text-muted">
              <p><?php echo $formatted_address;?></p>
            </div>
          </div>

          <div class="profile-item py-3 border-bottom">
            <div class="d-flex justify-content-between">
              <h6 class="mb-1 text-uppercase">Payment Methods</h6>
            </div>
            <div class="text-muted">
              <p><?php echo  $payment_method;?></p>
            </div>
          </div>

          <div class="text-end mt-3 shadow-none">
            <a href="edit_profile.php"class="btn btn-dark text-uppercase">Edit Profile</a>
        </div>
          <!-- Purchase History (Styled as a Cart) -->
          <div class="profile-item py-3 border-bottom">
            <div class="d-flex justify-content-between">
              <h6 class="mb-1 text-uppercase">Purchase History</h6>
            </div>
            <div class="text-muted">
              <ul class="list-unstyled">
                <!-- Purchase 1 -->
                <li class="d-flex align-items-center py-3 border-bottom">
                  <img src="images/product-item1.jpg" alt="product" style="width: 80px; height: 80px; object-fit: cover;" class="me-3 rounded">
                  <div class="me-3">
                    <h6 class="mb-1 text-uppercase" style="font-size: 1.1rem;">Product 1</h6>
                    <small class="text-muted">Model: IP10X</small><br>
                    <small class="text-muted">SN: 12345AB67890</small><br>
                    <small class="text-muted">Warranty: Until January 2026</small>
                  </div>
                  <div class="ms-auto">
                    <button class="btn btn-outline-primary btn-sm">Get Support</button>
                  </div>
                </li>
                <!-- Purchase 2 -->
                <li class="d-flex align-items-center py-3 border-bottom">
                  <img src="images/product-item2.jpg" alt="product" style="width: 80px; height: 80px; object-fit: cover;" class="me-3 rounded">
                  <div class="me-3">
                    <h6 class="mb-1 text-uppercase" style="font-size: 1.1rem;">Product 2</h6>
                    <small class="text-muted">Model: XY9</small><br>
                    <small class="text-muted">SN: 54321DC43210</small><br>
                    <small class="text-muted">Warranty: Until December 2025</small>
                  </div>
                  <div class="ms-auto">
                    <button class="btn btn-outline-primary btn-sm">Get Support</button>
                  </div>
                </li>
                <!-- Purchase 3 -->
                <li class="d-flex align-items-center py-3">
                  <img src="images/product-item3.jpg" alt="product" style="width: 80px; height: 80px; object-fit: cover;" class="me-3 rounded">
                  <div class="me-3">
                    <h6 class="mb-1 text-uppercase" style="font-size: 1.1rem;">Product 3</h6>
                    <small class="text-muted">Model: QZ202</small><br>
                    <small class="text-muted">SN: 98765GF12321</small><br>
                    <small class="text-muted">Warranty: Until November 2026</small>
                  </div>
                  <div class="ms-auto">
                    <button class="btn btn-outline-primary btn-sm">Get Support</button>
                  </div>
                </li>
              </ul>
            </div>
          </div>
           <div id="pagination-controls" class="d-flex justify-content-center mt-3">
              <button id="prev-page-btn" class="btn btn-dark text-uppercase" disabled>Prev</button>
              <span id="page-number" class="mx-3">1/2</span>
              <button id="next-page-btn" class="btn btn-dark text-uppercase">Next</button>
            </div>
             <div id="pagination-controls" class="d-flex justify-content-center mt-3">
              <a id="logout" href="logout.php" class="btn btn-danger text-uppercase shadow-none m-4 px-4" >Log out</a>
              
            </div>

        </div>
        
      </div>
    </div>
  </div>
</section>






<?php
require "Required/footer.php";