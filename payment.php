<?php
require "Config/connect.php";
require "Config/session.php";

require "Required/header.php";

if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['submit'])){

    $cards=$_POST['card_number'];
    $sql="Select * from cards where card_number = ?";
    $param=array();
}
$sql = "SELECT DISTINCT 
    p.name,
	c.quantity,
    CAST(p.image_url AS VARCHAR(MAX)) AS image_url,
    p.price,
    CAST(p.description AS VARCHAR(MAX)) AS description
FROM cart c
JOIN products p ON c.product_name = p.name
WHERE c.user_id =?
  AND c.deleted_at IS NULL";
$params = [$_SESSION['customer_id']];
$stmt = sqlsrv_query($conn, $sql, $params);
$total=0;
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $total+=$row['price']*$row['quantity'];
}
$sql = "SELECT c.*,u.name, u.email ,u.phone FROM cards c
LEFT JOIN users u ON c.user_id = u.user_id
 WHERE CONVERT(VARCHAR(32), HASHBYTES('MD5', CAST(c.user_id AS VARCHAR)), 2) = ?";

$params = [ $_SESSION['customer_id']];
$card = null;
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt && ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
    $card = $row;
}
$addressString="";
if($card){
     $addressData = json_decode($card['billing_address'], true); 
      $addressString = 
            ($addressData['address_line1'] ?? '') . ', ' .
            ($addressData['city'] ?? '') . ', ' .
            ($addressData['state'] ?? '') . ', ' .
            ($addressData['postal_code'] ?? '') . ', ' .
            ($addressData['country'] ?? '');


}

?>
<section id="payment" class="position-relative padding-large overflow-hidden">
  <div class="container">
    <div class="row">
      <div class="display-header text-uppercase text-dark text-center pb-3">
        <h2 class="display-7">Payment Details</h2>
      </div>
      <div class="col-md-8 offset-md-2">
        <form method="POST" class="bg-light p-4 rounded shadow-sm">
          <h5 class="mb-3">Billing Information</h5>

          <div class="mb-3">
            <label for="fullname" class="form-label">Full Name</label>
            <input type="text" placeholder="Full Name" class="form-control" <?php if ($card) echo 'value="' . htmlspecialchars($card['name']) . '"'; ?> id="fullname" name="fullname" required>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" <?php if ($card) echo 'value="' . htmlspecialchars($card['email']) . '"'; ?> name="email" required>
          </div>

          <div class="mb-3">
            <label for="address" class="form-label">Billing Address</label>
            <textarea class="form-control" id="address" name="address"  rows="2" required><?php echo $addressString?></textarea>
          </div>

          <h5 class="mt-4 mb-3">Payment Method</h5>

          <div class="mb-3">
            <label for="cardNumber" class="form-label">Card Number</label>
            <input type="text" class="form-control" id="cardNumber" <?php if ($card) echo 'value="' . htmlspecialchars($card['card_number']) . '"'; ?> name="card_number" maxlength="19" required>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="expiry" class="form-label">Expiry Date</label>
              <input type="text" class="form-control" <?php if ($card) echo 'value="' . htmlspecialchars($card['expiration_date']->format('m/y')) . '"'; ?> id="expiry"  name="expiry_date" placeholder="MM/YY" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="cvv" class="form-label">CVV</label>
              <input type="password" class="form-control" id="cvv" name="cvv" maxlength="4" required>
            </div>
          </div>
        
          <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-4">
            <h5>Total Amount:</h5>
            <h5>RM <span id="total-amount"><?php echo $total;?></span></h5>
          </div>

          <div class="text-end mt-4">
            <input type="hidden" name="payment" value="<?php echo $total;?>">
            <input type="hidden" name="submit" value="<?php echo $total;?>">
            <button type="submit" class="btn btn-dark text-uppercase">Confirm & Pay</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<?php
require "Required/footer.php";