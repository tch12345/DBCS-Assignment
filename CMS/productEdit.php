<?php
require "Config/session.php";
require "Config/connect.php";

// Get product data
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $query = "SELECT * FROM [products] WHERE product_id = ?";
    $params = array($product_id);
    $stmt = sqlsrv_query($conn, $query, $params);
    $product = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    } else {
    die("No product ID provided.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name        = $_POST['name'];
    $model       = $_POST['model'];
    $price       = $_POST['price'];
    $warranty    = $_POST['warranty'];
    $description = $_POST['description'];
    $image_url   = $_POST['image_url'];
    $colors      = $_POST['colors'];
    $quantity    = $_POST['quantity'];
    $category    = $_POST['category'];

    $updateQuery = "UPDATE [products] 
                    SET name = ?, model = ?, price = ?, warranty = ?, description = ?, image_url = ?, colors = ?, quantity = ?, category = ?
                    WHERE product_id = ?";
    $updateParams = array($name, $model, $price, $warranty, $description, $image_url, $colors, $quantity, $category, $product_id);

    $updateStmt = sqlsrv_query($conn, $updateQuery, $updateParams);

  if ($updateStmt) {
    $script .= "
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: 'Product updated successfully!',
        confirmButtonColor: '#3085d6'
      }).then(() => {
        window.location.href = 'productDash.php';
      });
    ";
} else {
  $script .= "
      Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: 'Failed to update product.',
        confirmButtonColor: '#d33'
      });
    ";

}
}
$page_name = "Product";
require "Required/Header.php";
?>

<div class="container-fluid py-2">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center px-3">
                            <h6 class="text-white text-capitalize m-0">Edit Product</h6>
                        </div>
                    </div>
                </div>

                <div class="card-body px-0 pb-2">
                    <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                    
                    <form method="POST" class="px-3" action="">
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control border border-secondary px-3" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="model" class="form-label">Model</label>
                            <input type="text" class="form-control border border-secondary px-3" name="model" value="<?php echo htmlspecialchars($product['model']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Price ($)</label>
                            <input type="number" step="0.01" class="form-control border border-secondary px-3" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="warranty" class="form-label">Warranty</label>
                            <input type="text" class="form-control border border-secondary px-3" name="warranty" value="<?php echo htmlspecialchars($product['warranty']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control border border-secondary px-3" name="description" rows="4" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image_url" class="form-label">Image URL</label>
                            <input type="text" class="form-control border border-secondary px-3" name="image_url" value="<?php echo htmlspecialchars($product['image_url']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="colors" class="form-label">Colors</label>
                            <input type="text" class="form-control border border-secondary px-3" name="colors" value="<?php echo htmlspecialchars($product['colors']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control border border-secondary px-3" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <input type="text" class="form-control border border-secondary px-3" name="category" value="<?php echo htmlspecialchars($product['category']); ?>" required>
                        </div>


                        <div class="d-flex justify-content-end px-3 pt-2">
                            <button type="submit" class="btn btn-primary">Update Product</button>
                            <a href="productDash.php" class="btn btn-secondary ms-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require "Required/Footer.php"; ?>