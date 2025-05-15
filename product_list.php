<?php
require "Config/connect.php";
require "Config/session.php";

require "Required/header.php";
?>

<section id="product-list" class="position-relative padding-large overflow-hidden">
  <div class="container">
    <div class="row">
      <div class="display-header text-uppercase text-dark text-center pb-3">
        <h2 class="display-7">Our Products</h2>
      </div>
      <div class="col-12">
        <div id="product-items-container" class="row">
          <?php
          $phonesql = "SELECT
                         name,
                         MAX(product_id) AS product_id,
                         MAX(image_url) AS image_path,
                         MAX(price) as price,
						             SUM(quantity) as quantity
                         FROM products
                         WHERE category = 'phone'
						             and quantity>0
                         GROUP BY name
                         ORDER BY product_id desc;" ;
          $stmt = sqlsrv_query($conn, $phonesql);
              if ($stmt === false) {
                    die(print_r(sqlsrv_errors(), true));
              }
              while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {               
          ?>
          <!-- Product item example -->
          <div class="col-md-4">
            <div class="product-item card shadow-sm border-light mb-4">
              <img src="<?php echo $row['image_path'];?>" alt="product" class="card-img-top" style="height: 200px; object-fit: cover;">
              <div class="card-body">
                <h5 class="card-title text-uppercase"><?php echo $row['name'];?></h5>
                <p class="card-text text-muted">RM <?php echo $row['price'];?></p>
                <div class="d-flex justify-content-between align-items-center">
                  <a href="product_detail.php?product_name=<?php echo $row['name']?>" class="btn btn-outline-primary">View Details</a>
                  <button class="btn btn-sm btn-dark" onclick="addToCart('Iphone 10', 980)">Add to Cart</button>
                </div>
              </div>
            </div>
          </div>
          <?php 
          }?>



          <!-- Add more products as necessary -->

        </div>
        
        <!-- Pagination (if there are more products) -->
  

      </div>
    </div>
  </div>
</section>

<!-- Add to Cart Function -->
<script>
  function addToCart(productName, price) {
    alert(productName + ' has been added to your cart at $' + price);
    // Here you can implement the logic to update the cart (e.g., update the cart icon, or store the product in a session)
  }
</script>




<?php
require "Required/footer.php";