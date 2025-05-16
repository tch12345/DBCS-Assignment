<?php
require "Config/session.php";
require "Config/connect.php";

if (!isset($_COOKIE['user']) || !isset($_SESSION['id'])) {
  header("Location: login2.0.php");
  exit();
}

$page_name = "Dashboard";
require "Required/header.php";

// Total sales
$salesQuery = "SELECT SUM(amount) AS total_sales FROM transactions WHERE transaction_status = 'success'";
$salesStmt = sqlsrv_query($conn, $salesQuery);
$total_sales = 0;
if ($salesStmt) {
  $data = sqlsrv_fetch_array($salesStmt, SQLSRV_FETCH_ASSOC);
  $total_sales = $data['total_sales'] ?? 0;
}

// Total users
$userQuery = "SELECT COUNT(*) AS total_users FROM users where role='user'";
$userStmt = sqlsrv_query($conn, $userQuery);
$total_users = 0;
if ($userStmt) {
  $data = sqlsrv_fetch_array($userStmt, SQLSRV_FETCH_ASSOC);
  $total_users = $data['total_users'] ?? 0;
}

// Sales by phone model
$modelSales = [];
$sql = "SELECT model, SUM(amount) AS total_sales
        FROM transactions
        WHERE transaction_status = 'success'
        GROUP BY model";
$stmt = sqlsrv_query($conn, $sql);
if ($stmt) {
  while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $modelSales[] = [
      'model' => $row['model'],
      'sales' => $row['total_sales']
    ];
  }
}
?>

<!-- Begin Page Content -->
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
  <div class="container-fluid py-4">
    <!-- Cards -->
    <div class="row">
      <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card bg-gradient-dark">
          <div class="card-body">
            <h6 class="text-white">Total Sales</h6>
            <h4 class="text-white">RM <?php echo number_format($total_sales, 2); ?></h4>
          </div>
        </div>
      </div>
      <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
        <div class="card bg-gradient-secondary">
          <div class="card-body">
            <h6 class="text-white">Total Users</h6>
            <h4 class="text-white"><?php echo $total_users; ?></h4>
          </div>
        </div>
      </div>
    </div>

    <!-- Chart Section -->
    <div class="card mt-4">
      <div class="card-header pb-0">
        <h6 class="text-white">Sales by Phone Model</h6>
      </div>
      <div class="card-body">
        <canvas id="modelSalesChart" height="60"></canvas>
      </div>
    </div>
  </div>
</main>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Chart Rendering -->
<script>
const models = <?php echo json_encode(array_column($modelSales, 'model')); ?>;
const modelSales = <?php echo json_encode(array_column($modelSales, 'sales')); ?>;

const ctx = document.getElementById('modelSalesChart').getContext('2d');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: models,
    datasets: [{
      label: 'Total Sales (RM)',
      data: modelSales,
      backgroundColor: 'rgba(54, 162, 235, 0.6)',
      borderColor: 'rgba(54, 162, 235, 1)',
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          callback: function(value) {
            return 'RM ' + value;
          }
        }
      }
    },
    plugins: {
      legend: {
        display: false
      }
    }
  }
});
</script>

<?php require "Required/Footer.php"; ?>
