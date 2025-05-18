<?php
require "Config/session.php";
require "Config/connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $model = $_POST['model'];
    $price = $_POST['price'];
    $warranty = $_POST['warranty'];
    $description = $_POST['description'];
    $image_url = $_POST['image_url'];
    $colors = $_POST['colors'];
    $quantity = $_POST['quantity'];
    $category = $_POST['category'];

    $query = "INSERT INTO [products] 
        (name, model, price, warranty, description, image_url, colors, quantity, category) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $params = array($name, $model, $price, $warranty, $description, $image_url, $colors, $quantity, $category);

    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt) {
        header("Location: productDash.php");
        exit();
    } else {
        $error = "Failed to add product.";
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
                            <h6 class="text-white text-capitalize m-0">Add Product</h6>
                        </div>
                    </div>
                </div>

                <div class="card-body px-0 pb-2">
                    <?php if (!empty($error)) echo "<p class='text-danger'>$error</p>"; ?>
                    
                    <form method="POST" class="px-3" action="">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control border border-secondary px-3" name="name" id="name" placeholder="Enter product name" required>
                        </div>

                        <div class="mb-3">
                            <label for="model" class="form-label">Model</label>
                            <input type="text" class="form-control border border-secondary px-3" name="model" id="model" placeholder="Enter model" required>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" step="0.01" class="form-control border border-secondary px-3" name="price" id="price" placeholder="Enter price" required>
                        </div>

                        <div class="mb-3">
                            <label for="warranty" class="form-label">Warranty</label>
                            <input type="text" class="form-control border border-secondary px-3" name="warranty" id="warranty" placeholder="Enter warranty" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control border border-secondary px-3" name="description" id="description" rows="4" placeholder="Enter description" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image_url" class="form-label">Image URL</label>
                            <input type="text" class="form-control border border-secondary px-3" name="image_url" id="image_url" placeholder="Enter image URL" required>
                        </div>

                        <div class="mb-3">
                            <label for="colors" class="form-label">Colors</label>
                            <input type="text" class="form-control border border-secondary px-3" name="colors" id="colors" placeholder="Enter colors" required>
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control border border-secondary px-3" name="quantity" id="quantity" placeholder="Enter quantity" required>
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <input type="text" class="form-control border border-secondary px-3" name="category" id="category" placeholder="Enter category" required>
                        </div>

                        <div class="d-flex justify-content-end px-3 pt-2">
                            <input type="submit" class="btn btn-dark" value="Add Product">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require "Required/Footer.php"; ?>
