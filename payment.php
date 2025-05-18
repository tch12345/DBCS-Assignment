<?php
require "Config/connect.php";
require "Config/session.php";
$script="";
function getCardBrand($cardNumber) {
    // 移除空格和非数字字符
    $cardNumber = preg_replace('/\D/', '', $cardNumber);

    if (preg_match('/^4[0-9]{12}(?:[0-9]{3})?$/', $cardNumber)) {
        return 'Visa';
    } elseif (preg_match('/^5[1-5][0-9]{14}$/', $cardNumber) ||
              preg_match('/^2(2[2-9][0-9]{2}|[3-6][0-9]{3}|7[01][0-9]{2}|720[0-9]{2})[0-9]{10}$/', $cardNumber)) {
        return 'MasterCard';
    } elseif (preg_match('/^3[47][0-9]{13}$/', $cardNumber)) {
        return 'American Express';
    } elseif (preg_match('/^6(?:011|5[0-9]{2}|4[4-9][0-9])[0-9]{12}$/', $cardNumber)) {
        return 'Discover';
    } elseif (preg_match('/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/', $cardNumber)) {
        return 'Diners Club';
    } elseif (preg_match('/^(?:2131|1800|35\d{3})\d{11}$/', $cardNumber)) {
        return 'JCB';
    } else {
        return 'Unknown';
    }
}

if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['submit'])){

    $sql = "SELECT user_id FROM users WHERE CONVERT(VARCHAR(32), HASHBYTES('MD5', CAST(user_id AS VARCHAR)),2) = ?";
    $params = [$_SESSION['customer_id']];
    $stmt = sqlsrv_prepare($conn, $sql, $params);
    sqlsrv_execute($stmt);
    if($data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
      $user_id=$data['user_id'];
    }
    // get user id ady
    $cards = trim($_POST['card_number']);
    
   
    $sql = "
    SELECT *
    FROM cards 
    WHERE CONVERT(VARCHAR(50), DecryptByCert(Cert_ID('DataEncryptionCert'), card_number_encrypted))  = ? 
    AND valid = 1 
    AND user_id = ?
    ";
    
    $params=array(
       $cards,
       $user_id
    );
   
    $stmt = sqlsrv_query($conn, $sql, $params);

    if(sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
      #got card ignore direct do payment
    
    }else{
      # no card
      //process date
         
      $updatecard="update cards set valid=0 where user_id=".$user_id;
      sqlsrv_query($conn,  $updatecard);
      
      list($month, $year) = explode('/', $_POST['expiry_date']);
      $year = '20' . $year; 
      $expirationDate = "$year-$month-01";
      $date = DateTime::createFromFormat('Y-m-d', $expirationDate);
      $lastDay = $date->format('Y-m-t');

      $parts = array_map('trim', explode(',', $_POST['address']));
      if (count($parts) === 5) {
          $address_array = [
              "address_line1" => $parts[0],
              "city" => $parts[1],
              "state" => $parts[2],
              "postal_code" => $parts[3],
              "country" => $parts[4],
          ];

        }
      $json_string = json_encode($address_array, JSON_UNESCAPED_UNICODE);
    
     

    $insertsql = "INSERT INTO cards (
                    user_id, 
                    card_number_encrypted, 
                    card_brand, 
                    expiration_date, 
                    billing_address, 
                    created_at
                )
                VALUES (
                    ?, 
                    EncryptByCert(Cert_ID('DataEncryptionCert'),'".$cards."'), 
                    ?, 
                    ?, 
                    ?, 
                    GETDATE()
                );";

       
      $param=[
        $user_id,
        getCardBrand($cards),
        $lastDay,
        $json_string
      ];
      
      $stmt = sqlsrv_prepare($conn, $insertsql, $param);
      sqlsrv_execute($stmt);
    }
    // make payment here got card ady
    $selectCart = "SELECT product_name, quantity FROM cart WHERE user_id = ? and deleted_at is null";
    $cartStmt = sqlsrv_query($conn, $selectCart, array($_SESSION['customer_id']));
    $items = [];
    $prices = [];
    $quantities = [];
    $total_price = 0;
    while ($row = sqlsrv_fetch_array($cartStmt, SQLSRV_FETCH_ASSOC)) {
        $product_name = $row['product_name'];
        $quantity = intval($row['quantity']);
        $priceQuery = "SELECT max(price) as price FROM products WHERE name = ? group by name ";
        $priceStmt = sqlsrv_query($conn, $priceQuery, array($product_name));
        $priceRow = sqlsrv_fetch_array($priceStmt, SQLSRV_FETCH_ASSOC);
        $price = floatval($priceRow['price']);
        $items[] = $product_name;
        $prices[] = $price;
        $quantities[] = $quantity;
        $total_price += $price * $quantity;
    }
    if (!empty($items)){
      $insertPayment = "INSERT INTO payment (user_id_md5, items, prices, quantities, total_price, created_at)
                      VALUES (?, ?, ?, ?, ?, GETDATE())";
      $params = array(
        $_SESSION['customer_id'],
        json_encode($items, JSON_UNESCAPED_UNICODE),
        json_encode($prices),
        json_encode($quantities),
        $total_price
      );
      $insertStmt = sqlsrv_query($conn, $insertPayment, $params);

      $deleteCart = "DELETE FROM cart WHERE user_id = ? AND deleted_at IS NULL";
      $deleteStmt = sqlsrv_query($conn, $deleteCart,array($_SESSION['customer_id']));
      
      $insertSql = "INSERT INTO transactions (
        user_id, amount, transaction_date, 
        transaction_status, payment_method, transaction_reference, created_at
      ) VALUES (?, ?, GETDATE(), ?, ?, ?, GETDATE())";
      $params = [
        $user_id,
        $total_price,
        "success",
        "credit_card",
        "txn_". round(microtime(true) * 1000),
      ];
  
      $stmt = sqlsrv_query($conn, $insertSql, $params);
      $script.="Swal.fire('Payment Successful', 'success').then(() => {
                    window.location.href = 'profile.php';
                });";
    }


   
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
$sql = "SELECT c.*,u.name, u.email ,u.phone,CONVERT(VARCHAR(50), DecryptByCert(Cert_ID('DataEncryptionCert'), c.card_number_encrypted)) AS decrypted_card_number FROM cards c
LEFT JOIN users u ON c.user_id = u.user_id
 WHERE CONVERT(VARCHAR(32), HASHBYTES('MD5', CAST(c.user_id AS VARCHAR)), 2) = ? and valid=1";

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
require "Required/header.php";

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
            <input type="text" class="form-control" id="cardNumber" <?php if ($card) echo 'value="' .$card['decrypted_card_number'].'"'; ?> name="card_number" maxlength="19" required>
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