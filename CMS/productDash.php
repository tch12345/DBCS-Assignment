<?php
require "Config/session.php";
require "Config/connect.php";

// Redirect if user not logged in
if (!isset($_COOKIE['user'])) {
    header("Location: login2.0.php");
    exit();
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
                  <h6 class="text-white text-capitalize m-0">Products List</h6>
                   <a href="productAdd.php" class="btn btn-sm bg-dark text-light">Add Product</a>
                </div>
              </div>
            </div>

            <div class="card-body px-0 pb-2">
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Product ID</th>
                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2"> Name</th>
                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2"> Model</th>
                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2"> Price</th>
                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2"> Warranty</th>
                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2"> Color</th>
                    <!-- <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2"> Image</th> -->
                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2"> Quantity</th>
                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2"> Category</th>
                    <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT product_id, name, model, price,colors, warranty, description, image_url, quantity, category  FROM [products]";
                    $stmt = sqlsrv_query($conn, $query);

                    if ($stmt) {
                    while ($data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                        ?>
                        <tr>
                        <td>
                            <h6 class="mb-0 text-sm"><?php echo $data['product_id']; ?></h6>
                        </td>
                        <td>
                            <p class="text-sm mb-0"><?php echo $data['name']; ?></p>
                        </td>
                        <td>
                            <p class="text-sm mb-0"><?php echo $data['model']; ?></p>
                        </td>
                        <td>
                            <p class="text-sm mb-0"><?php echo $data['price']; ?></p>
                        </td>
                        <td>
                            <p class="text-sm mb-0"><?php echo $data['warranty']; ?></p>
                        </td>
                        <td class="align-center">
                            <div style=" width: 20px; height: 20px; background-color: <?php echo $data['colors']; ?>; border-radius: 50%;"></div>
                        </td>
                        <!-- <td>
                            <p class="text-sm mb-0"><?php echo $data['image_url']; ?></p>
                        </td> -->
                        <td>
                            <p class="text-sm mb-0"><?php echo $data['quantity']; ?></p>
                        </td>
                        <td>
                            <p class="text-sm mb-0"><?php echo $data['category']; ?></p>
                        </td>
                        <td>
                            <a href="productEdit.php?product_id=<?php echo $data['product_id']; ?>" class="btn btn-sm btn-warning me-1">Edit</a>
                            <a href="#" 
                                class="btn btn-sm btn-danger delete-product" 
                                data-prod-id="<?php echo $data['product_id']; ?>">
                                Delete
                            </a>
                        </td>
                        </tr>
                        <?php
                    }
                    } else {
                    echo "<tr><td colspan='6' class='text-center text-danger'>Failed to fetch users.</td></tr>";
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
$script = <<<EOF
 $('.delete-product').click(function (e) {
        e.preventDefault(); // prevent default action

        var prodId = $(this).data('prod-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to undo this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to the delete URL
                window.location.href = 'productDelete.php?product_id=' + prodId;
            }
        });
    });
EOF;
require "Required/Footer.php"; ?>
