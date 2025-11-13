<?php
require __DIR__ . '/auth_check.php';
include __DIR__ . '/db_connect.php';

// Allow both admin and manager to access dashboard
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'manager') {
    header('Location: login.php');
    exit();
}

// Determine if user is manager and get booth filter
$isManager = ($_SESSION['role'] === 'manager');
$booth_id = $isManager ? (int)$_SESSION['booth_id'] : null;

// Enable error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Wina Bwangu Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body { background: #f5f5f7; font-family: 'Poppins', Arial, sans-serif; }
    .navbar { background: #c8102e; box-shadow: 0 2px 8px rgba(200,16,46,0.09); }
    .card { border-radius: 23px; box-shadow: 0 3px 16px rgba(200,16,46,0.09); background: #fff; }
    .card-title { color: #c8102e!important; font-weight: 700; }
    table {
      background: #fff;
      border-radius: 16px;
      overflow: hidden;
    }
    table th { color: #fff; background:  !important; }
    table tr td {
      border-bottom: 1px solid #fddbe1;
    }
    footer { background: #830c1a; color: #fff; padding: 1.5rem 0; text-align: center; }
    h5, h3, h4 { color: #c8102e; font-weight: 600; }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">Wina Bwangu</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <span class="nav-link text-white-50">
            <?php 
              $roleLabel = ucfirst($_SESSION['role']);
              echo $roleLabel . ": " . htmlspecialchars($_SESSION['username']);
              if ($isManager && isset($_SESSION['booth_name'])) {
                echo " (" . htmlspecialchars($_SESSION['booth_name']) . ")";
              }
            ?>
          </span>
        </li>
        <li class="nav-item"><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="transactions.php">Transactions</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fa-solid fa-sign-out-alt me-1"></i>Logout</a></li>
      </ul>
      <a href="download_transactions.php<?php echo $isManager ? '' : '?all=1'; ?>" class="ms-3 d-inline-flex align-items-center" style="background:none;border:none;box-shadow:none;padding:4px;outline:none;" aria-label="Download CSV" data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php echo $isManager ? 'Download booth transactions as CSV' : 'Download all transactions as CSV'; ?>">
        <i class="fa-solid fa-file-csv fa-lg text-white" style="filter: drop-shadow(0 1px 0 rgba(0,0,0,0.04));"></i>
      </a>
    </div>
  </div>
</nav>

<div class="container py-4">

  <!-- Summary Cards -->
  <div class="row mb-4">
    <?php
      // Add role-based title
      if ($isManager && isset($_SESSION['booth_name'])) {
        echo '<div class="col-12 mb-3"><h3 class="text-center"><i class="fa-solid fa-store me-2"></i>Dashboard - ' . htmlspecialchars($_SESSION['booth_name']) . '</h3></div>';
      } else {
        echo '<div class="col-12 mb-3"><h3 class="text-center"><i class="fa-solid fa-chart-line me-2"></i>Admin Dashboard - Overview</h3></div>';
      }
      
      // Get revenue and capital with proper filtering
      if ($isManager && $booth_id) {
        $revenueStmt = $conn->prepare("SELECT SUM(Transaction_amount * Revenue_per_kwacha) AS total_revenue FROM transactions WHERE Booth_id = ?");
        $revenueStmt->bind_param("i", $booth_id);
        $revenueStmt->execute();
        $revenueResult = $revenueStmt->get_result();
        $revenue = $revenueResult->fetch_assoc()['total_revenue'] ?? 0;
        $revenueStmt->close();

        $capitalStmt = $conn->prepare("SELECT SUM(Transaction_amount) AS total_capital FROM transactions WHERE Booth_id = ?");
        $capitalStmt->bind_param("i", $booth_id);
        $capitalStmt->execute();
        $capitalResult = $capitalStmt->get_result();
        $capital = $capitalResult->fetch_assoc()['total_capital'] ?? 0;
        $capitalStmt->close();
      } else {
        $revenueQuery = $conn->query("SELECT SUM(Transaction_amount * Revenue_per_kwacha) AS total_revenue FROM transactions");
        $revenue = $revenueQuery->fetch_assoc()['total_revenue'] ?? 0;

        $capitalQuery = $conn->query("SELECT SUM(Transaction_amount) AS total_capital FROM transactions");
        $capital = $capitalQuery->fetch_assoc()['total_capital'] ?? 0;
      }
    ?>
    <div class="col-md-6">
      <div class="card text-center mb-3">
        <div class="card-body">
          <h5 class="card-title text-primary"><i class="fa-solid fa-coins me-2"></i>Total Revenue</h5>
          <p class="fs-4 fw-bold text-success">ZMW <?php echo number_format($revenue, 2); ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card text-center mb-3">
        <div class="card-body">
          <h5 class="card-title text-secondary"><i class="fa-solid fa-building-columns me-2"></i>Total Capital</h5>
          <p class="fs-4 fw-bold text-dark">ZMW <?php echo number_format($capital, 2); ?></p>
        </div>
      </div>
    </div>
  </div>

  <!-- Totals per Service -->
  <div class="row">
    <div class="col-md-6 mb-4">
      <h5>Totals Per Service</h5>
      <table class="table table-striped table-bordered">
        <thead class="table-dark"><tr><th>Service</th><th>Total Amount</th></tr></thead>
        <tbody>
          <?php
            if ($isManager && $booth_id) {
              $query = "
                SELECT s.Service_name, SUM(t.Transaction_amount) AS TotalAmount
                FROM transactions t
                JOIN services s ON t.Service_id = s.Service_id
                WHERE t.Booth_id = ?
                GROUP BY s.Service_name
                ORDER BY s.Service_name
              ";
              $stmt = $conn->prepare($query);
              $stmt->bind_param("i", $booth_id);
              $stmt->execute();
              $result = $stmt->get_result();
            } else {
              $query = "
                SELECT s.Service_name, SUM(t.Transaction_amount) AS TotalAmount
                FROM transactions t
                JOIN services s ON t.Service_id = s.Service_id
                GROUP BY s.Service_name
                ORDER BY s.Service_name
              ";
              $result = $conn->query($query);
            }

            $serviceLabels = [];
            $serviceValues = [];
            while ($row = $result->fetch_assoc()) {
              echo "<tr><td>{$row['Service_name']}</td><td>ZMW ".number_format($row['TotalAmount'],2)."</td></tr>";
              $serviceLabels[] = $row['Service_name'];
              $serviceValues[] = $row['TotalAmount'];
            }
            if (isset($stmt)) $stmt->close();
          ?>
        </tbody>
      </table>
      <canvas id="totalsChart"></canvas>
    </div>

    <!-- Revenue Per Booth (Admin only) or Service Performance (Manager) -->
    <div class="col-md-6 mb-4">
      <?php if (!$isManager): ?>
      <h5>Revenue Per Booth</h5>
      <table class="table table-striped table-bordered">
        <thead class="table-dark"><tr><th>Booth</th><th>Total Revenue</th></tr></thead>
        <tbody>
          <?php
            $query = "
              SELECT b.BoothName, SUM(t.Transaction_amount * t.Revenue_per_kwacha) AS BoothRevenue
              FROM transactions t
              JOIN booths b ON t.Booth_id = b.Booth_id
              GROUP BY b.BoothName
            ";
            $result = $conn->query($query);

            $boothLabels = [];
            $boothValues = [];
            while ($row = $result->fetch_assoc()) {
              echo "<tr><td>{$row['BoothName']}</td><td>ZMW ".number_format($row['BoothRevenue'],2)."</td></tr>";
              $boothLabels[] = $row['BoothName'];
              $boothValues[] = $row['BoothRevenue'];
            }
          ?>
        </tbody>
      </table>
      <canvas id="boothChart"></canvas>
      <?php else: ?>
      <h5>Revenue Per Service (This Booth)</h5>
      <table class="table table-striped table-bordered">
        <thead class="table-dark"><tr><th>Service</th><th>Total Revenue</th></tr></thead>
        <tbody>
          <?php
            if ($isManager && $booth_id) {
              $query = "
                SELECT s.Service_name, SUM(t.Transaction_amount * t.Revenue_per_kwacha) AS ServiceRevenue
                FROM transactions t
                JOIN services s ON t.Service_id = s.Service_id
                WHERE t.Booth_id = ?
                GROUP BY s.Service_name
                ORDER BY s.Service_name
              ";
              $stmt2 = $conn->prepare($query);
              $stmt2->bind_param("i", $booth_id);
              $stmt2->execute();
              $result = $stmt2->get_result();
            } else {
              $query = "
                SELECT s.Service_name, SUM(t.Transaction_amount * t.Revenue_per_kwacha) AS ServiceRevenue
                FROM transactions t
                JOIN services s ON t.Service_id = s.Service_id
                GROUP BY s.Service_name
                ORDER BY s.Service_name
              ";
              $result = $conn->query($query);
            }

            $boothLabels = [];
            $boothValues = [];
            while ($row = $result->fetch_assoc()) {
              echo "<tr><td>{$row['Service_name']}</td><td>ZMW ".number_format($row['ServiceRevenue'],2)."</td></tr>";
              $boothLabels[] = $row['Service_name'];
              $boothValues[] = $row['ServiceRevenue'];
            }
            if (isset($stmt2)) $stmt2->close();
          ?>
        </tbody>
      </table>
      <canvas id="boothChart"></canvas>
      <?php endif; ?>
    </div>
  </div>

  <!-- Pie Chart -->
  <div class="row my-4">
    <div class="col-md-12">
      <h5>Revenue vs Capital Overview</h5>
      <canvas id="pieChart"></canvas>
    </div>
  </div>

</div>

<!-- Footer -->
<footer>
  &copy; <?php echo date("Y"); ?> Wina Bwangu Agency System
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Color maps and palettes
const serviceColorMap = {
  'Airtel Money': '#e30613',   // Airtel red
  'FNB': '#007a6e',            // FNB bluish green
  'MTN Money': '#ffcc00',      // MTN yellow
  'Zamtel Money': '#006837',   // Zamtel dark green
  'Zanaco': '#b00020'          // Zanaco deep red
};
const boothPalette = ['#c8102e','#003087','#ffcc00','#009639','#b00020','#ab47bc','#00bcd4','#6c757d','#23b26d','#ff7043'];
const bluePie = ['#1e88e5','#90caf9'];

// Build color arrays from PHP labels
const serviceLabelsJs = <?php echo json_encode($serviceLabels); ?>;
const serviceBarColors = serviceLabelsJs.map(l => serviceColorMap[l] || '#c8102e');

const boothLabelsJs = <?php echo json_encode($boothLabels); ?>;
const boothBarColors = boothLabelsJs.map((_, i) => boothPalette[i % boothPalette.length]);

const totalsCtx = document.getElementById('totalsChart').getContext('2d');
new Chart(totalsCtx, {
  type: 'bar',
  data: {
    labels: serviceLabelsJs,
    datasets: [{
      label: 'Total Amount (ZMW)',
      data: <?php echo json_encode($serviceValues); ?>,
      backgroundColor: serviceBarColors,
      borderRadius: 8
    }]
  },
  options: {
    animation: { duration: 900, easing: 'easeOutQuart' },
    plugins: { legend: { display: false } },
    scales: { x: {}, y: { beginAtZero: true } }
  }
});

const boothCtx = document.getElementById('boothChart').getContext('2d');
// For managers, boothLabelsJs contains service names, so use service colors
// For admins, it contains booth names, so use booth palette
const isManagerView = <?php echo $isManager ? 'true' : 'false'; ?>;
const boothChartColors = isManagerView 
  ? boothLabelsJs.map(l => serviceColorMap[l] || '#c8102e')
  : boothLabelsJs.map((_, i) => boothPalette[i % boothPalette.length]);
new Chart(boothCtx, {
  type: 'bar',
  data: {
    labels: boothLabelsJs,
    datasets: [{
      label: 'Revenue (ZMW)',
      data: <?php echo json_encode($boothValues); ?>,
      backgroundColor: boothChartColors,
      borderRadius: 8
    }]
  },
  options: {
    animation: { duration: 900, easing: 'easeOutQuart' },
    plugins: { legend: { display: false } },
    scales: { x: {}, y: { beginAtZero: true } }
  }
});

const pieCtx = document.getElementById('pieChart').getContext('2d');
new Chart(pieCtx, {
  type: 'pie',
  data: {
    labels: ['Total Revenue', 'Total Capital'],
    datasets: [{
      data: [<?php echo $revenue; ?>, <?php echo $capital; ?>],
      backgroundColor: ['#198754', '#6c757d']
    }]
  },
  options: {
    animation: { duration: 900, easing: 'easeOutQuart' },
    plugins: { legend: { position: 'bottom' } }
  }
});

document.addEventListener('DOMContentLoaded', function () {
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.forEach(function (tooltipTriggerEl) {
    new bootstrap.Tooltip(tooltipTriggerEl);
  });
});
</script>

</body>
</html>
