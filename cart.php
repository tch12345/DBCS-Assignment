<?php
require "Config/connect.php";
require "Config/session.php";

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
require "Required/header.php";
?>

<section id="cart" class="position-relative padding-large overflow-hidden">
  <div class="container">
    <div class="row">
      <div class="display-header text-uppercase text-dark text-center pb-3">
        <h2 class="display-7">Your Cart</h2>
      </div>
      <div class="col-12">
        <div id="cart-items-container">
          <!-- Cart item example -->
         
          <?php 
          $total=0;
          while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $total+=$row['price']*$row['quantity'];
                ?>
                 <div class="cart-item d-flex align-items-center justify-content-between py-3 border-bottom">
                  <div class="d-flex align-items-center">
                    <img src="<?php echo $row['image_url']?>" alt="product" style="width: 60px; height: 60px; object-fit: cover;" class="me-3 rounded">
                    <div>
                      <h6 class="mb-1 text-uppercase"><?php echo $row['name']?></h6>
                      <small class="text-muted">RM <?php echo $row['price'];?></small>
                    </div>
                  </div>
                  <div class="d-flex align-items-center ms-auto px-4">
                    <button class="btn btn-sm btn-outline-secondary me-2 decrease-quantity" data-product-name="<?php echo $row['name'];?>">−</button>
                    <span class="qty"><?php echo $row['quantity']?></span>
                    <button class="btn btn-sm btn-outline-secondary ms-2 add-to-cart" data-product-name="<?php echo $row['name'];?>" >+</button>
                  </div>
                  <div class="text-end">
                  <strong class="px-4">RM <?php echo $row['price']*$row['quantity'];?></strong>
                  <button class="btn btn-sm bg-danger text-light px-4 shadow-nones delete-cart-item" data-product-name="<?php echo $row['name'];?>">  Delete </button>
                  </div>
                </div>
                <?php
            }
          ?>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4 border-top pt-3">
          <h5>Total:</h5>
          <h5>RM<span id="cart-section-total"> <?php echo $total;?></span></h5>
        </div>
        <div class="text-end mt-3">
          <?php 
          if($total>0){
            echo '<a href="payment.php" class="btn btn-dark text-uppercase">Make Payment</a>';
          }
          ?>
          
        </div>
      </div>
    </div>
  </div>
</section>



<?php
$script=<<<EOF
 $('.add-to-cart').click(function(e) {
     e.preventDefault();
     const productName = $(this).data('product-name');
     $.ajax({
            url:"Ajax/add_to_cart_by_name.php",
            type: 'POST',
            data: { product_name: productName },
            success: function(response) {
              location.reload(); 
            },
            error: function() {
              swal.fail('Error adding product to cart');
            }
     });
 });

 $('.decrease-quantity').click(function(e) {
    e.preventDefault();

    const productName = $(this).data('product-name');
    const qtySpan = $(this).next('.qty');
    // Find the quantity value near this button (adjust selector if needed)
    const quantity = parseInt($(qtySpan).text());
      console.log(quantity);
    if (quantity === 1) {
        swal.fail("the number cannot below than 1");
        return;  // stop here, do not send AJAX
    }

    // Otherwise send AJAX to decrease quantity
    $.ajax({
        url: "Ajax/remove_from_cart.php",
        type: 'POST',
        data: { product_name: productName },
        dataType: 'json',
        success: function(response) {
            if(response.status === 'success'){
                location.reload();
            } else {
                swal.fire('Oops!', response.message, 'error');
            }
        },
        error: function() {
            swal.fire('Error', 'Error updating product quantity', 'error');
        }
    });
 


});

$('.delete-cart-item').click(function(e) {
    e.preventDefault();
    const productName = $(this).data('product-name');

    $.ajax({
        url: 'Ajax/delete_item.php',
        type: 'POST',
        data: { product_name: productName },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                Swal.fire('Deleted!', response.message, 'success').then(() => {
                    location.reload();
                });
            } else {
                swal.fail('Error', response.message, 'error');
            }
        },
        error: function() {
            swal.fail('Error', 'Failed to delete item', 'error');
        }
    });
});


EOF;
require "Required/footer.php";