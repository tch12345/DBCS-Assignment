<?php
require "Config/connect.php";
require "Config/session.php";

if(!isset($_GET['product_name']) || $_GET['product_name']==""){
    header("Location: index.php");
    exit();
}
$script="";
$sql="SELECT * FROM products where name = ?";
$array=array(
    $_GET['product_name']
);
$quantity=0;

  $stmt = sqlsrv_prepare($conn,  $sql, $array);
  sqlsrv_execute($stmt);
   if ($stmt) {
      $colors = array();
      $id=array();
      while ($data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) { 
        $name=$data['name'];
        $model=$data['model'];
        $price=$data['price'];
        $warranty=$data['warranty'];
        $description=$data['description'];
        $image=$data['image_url'];
        $quantity+=$data['quantity'];
        $id[]=$data['product_id'];
        $colors[] = $data['colors'];
        
      } 
        
  }

require "Required/header.php";
?>
<section id="product-detail" class="position-relative padding-large overflow-hidden">
  <div class="container">
    <div class="row g-5 align-items-start">
      <!-- Product Image -->
      <div class="col-md-5">
        <img src="<?php echo $image;?>" alt="Product Image" class="img-fluid rounded shadow-sm mx-auto d-block " style=" height: 430px; object-fit: cover;">

      </div>

      <!-- Product Info -->
      <div class="col-md-7">
        <div class="product-info p-4 bg-light rounded shadow-sm">
          <h2 class="text-uppercase mb-3"><?php echo $name;?></h2>
          <h5 class="text-muted mb-3">Model: <?php echo $model?></h5>

        <div class="mb-3">
          <strong>Color:</strong> 
          <span class="ms-2" style="display: inline-flex; align-items: center;">

            <?php 
         
           foreach ($colors as $color): ?>
            <span class="color-ball" style="background-color: <?php echo htmlspecialchars($color); ?>;"></span>
          <?php endforeach;
            ?>
          </span>
        </div>

          <div class="mb-3">
            <strong>Price:</strong> <span class="ms-2 text-danger fs-5">RM <?php echo $price?></span>
          </div>

          <div class="mb-3">
            <strong>Warranty:</strong> <span class="ms-2"><b><?php echo $warranty?></b></span>
          </div>
          <div class="mb-3">
            <strong>Stock</strong> <span class="ms-2 text-danger fs-5"><?php echo $quantity;?></span>
          </div>

          <p class="mt-4 mb-4">
            <?php echo $description?>
          </p>

          <div class="d-flex justify-content-start gap-3">
            
            <?php
            if(isset($_SESSION['customer_id'])){
              echo '<a href="#" class="btn btn-dark text-uppercase add-to-cart" data-product-name="'.$name.'">Add to Cart</a>';
            }else{
              echo '<a href="login.php" class="btn btn-outline-dark text-uppercase" disabled >Need to Login First</a>';
            }
             ?>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
$script =<<<EOF
 $('.add-to-cart').click(function(e) {
     e.preventDefault();
     const productName = $(this).data('product-name');
     $.ajax({
            url:"Ajax/add_to_cart_by_name.php",
            type: 'POST',
            data: { product_name: productName },
            success: function(response) {
              swal.success("Product Added Successful");
            },
            error: function() {
              swal.fail('Error adding product to cart');
            }
     });
 });
EOF;
require "Required/footer.php";