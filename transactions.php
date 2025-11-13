<?php 
require __DIR__ . '/auth_check.php';
include __DIR__ . '/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Wina Bwangu — Transactions</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: "Poppins", Arial, sans-serif;
      background: #f5f5f7;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .navbar { background: #c8102e; box-shadow: 0 2px 8px rgba(200,16,46,0.12); }
    .transaction-container {
      max-width: 650px;
      margin: 5rem auto;
      flex: 1;
    }
    .card {
      border: none;
      border-radius: 22px;
      background: #fff;
      box-shadow: 0 2px 16px rgba(200,16,46,0.09);
      transition: box-shadow .2s, transform .15s;
    }
    .card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.15); }
    .card-header {
      border-top-left-radius: 22px;
      border-top-right-radius: 22px;
      background: #c8102e;
      color: #fff;
      text-align: center;
      padding: 1.55rem;
    }
    label { font-weight: 600; color: #830c1a; }
    .btn-submit {
      background: #c8102e;
      border: none;
      color: white;
      font-size: 1.13rem;
      font-weight: 600;
      padding: 0.75rem;
      border-radius: 40px;
      transition: background .2s, box-shadow .2s;
      box-shadow: 0 2px 12px rgba(200,16,46,0.09);
    }
    .btn-submit:hover { background: #830c1a; color: #fff; }
    input.form-control, select.form-select {
      border-radius: 12px;
      border: 1px solid #ffd6dc;
      font-size: 1.09rem;
    }
    input.form-control:focus, select.form-select:focus {
      border-color: #c8102e;
      box-shadow: 0 0 8px 0 #ffd6dc;
    }
    footer {
      background: #830c1a;
      color: #fff;
      text-align: center;
      padding: 1.5rem 0;
      font-size: 0.97rem;
      margin-top: auto;
    }
    footer a { color: #fff; text-decoration: none; }
    footer a:hover { color: #ffc1c8; }
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
            Logged in: <?php echo htmlspecialchars($_SESSION['username']); ?>
            <?php if (isset($_SESSION['booth_name'])): ?>
              (<?php echo htmlspecialchars($_SESSION['booth_name']); ?>)
            <?php endif; ?>
          </span>
        </li>
        <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'manager'): ?>
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link active" href="transactions.php">Transactions</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fa-solid fa-sign-out-alt me-1"></i>Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Transaction Form -->
<div class="transaction-container">
  <div class="card">
    <div class="card-header">
      <h3 class="mb-0"><i class="fa-solid fa-credit-card me-2"></i>Record a New Transaction</h3>
    </div>
    <div class="card-body p-4">
      <form id="transactionForm">
        <!-- Booth Display (pre-selected from login) -->
        <div class="alert alert-info">
          <strong>Current Booth:</strong> <?php echo htmlspecialchars($_SESSION['booth_name']); ?>
        </div>
        
        <input type="hidden" id="booth" name="boothID" value="<?php echo (int)$_SESSION['booth_id']; ?>">

        <!-- Service Dropdown -->
        <div class="mb-3">
          <label for="service" class="form-label">Select Service *</label>
          <select id="service" name="serviceID" class="form-select" required>
            <option value="">-- First select a Booth --</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="revenue" class="form-label">Revenue per Kwacha</label>
          <input type="text" id="revenue" class="form-control" readonly>
        </div>

        <div class="mb-3">
          <label for="amount" class="form-label">Transaction Amount (ZMW) *</label>
          <input type="number" id="amount" name="amount" class="form-control" placeholder="Enter amount" required>
        </div>

        <div class="mb-3">
          <label for="earned" class="form-label">Revenue Earned (Calculated)</label>
          <input type="text" id="earned" class="form-control" readonly>
        </div>

        <button type="submit" class="btn btn-submit w-100">
          <i class="fa fa-save me-2"></i>Save Transaction
        </button>
      </form>
      <div id="message" class="mt-3"></div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer>
  <div class="container">
    <p class="mb-1">&copy; <?php echo date('Y'); ?> Wina Bwangu Agency System</p>
    <p class="small">Designed by Team Wina Bwangu | <a href="index.php">Back to Home</a></p>
  </div>
</footer>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(function() {
  // Auto-load services on page load for pre-selected booth
  let boothID = $('#booth').val();
  if (boothID) {
    $('#service').html("<option>Loading...</option>");
    
    // Fetch services available for selected booth
    $.getJSON("get_services.php", { boothID: boothID }, function(data) {
      $('#service').html("<option value=''>-- Choose Service --</option>");
      if (data.length === 0) {
        $('#service').append("<option disabled>No services found</option>");
        return;
      }
      data.forEach(s => {
        $('#service').append(`<option value="${s.Service_id}" data-revenue="${s.Revenue_per_kwacha}">${s.Service_name}</option>`);
      });
    }).fail(function() {
      $('#service').html("<option disabled>Error loading services</option>");
    });
  }

  // When service changes, update revenue
  $('#service').change(function() {
    let rev = $(this).find("option:selected").data("revenue");
    $('#revenue').val(rev);
    $('#amount').trigger("input");
  });

  // Auto calculate earned
  $('#amount').on("input", function() {
    let amount = parseFloat($(this).val()) || 0;
    let rev = parseFloat($('#revenue').val()) || 0;
    $('#earned').val((amount * rev).toFixed(2));
  });

  // Save transaction
  $('#transactionForm').submit(function(e) {
    e.preventDefault();
    $.post("add_transaction.php", $(this).serialize(), function(res) {
      $('#message').html(`<div class="alert alert-success">${res}</div>`);
      $('#transactionForm')[0].reset();
      $('#service').html('<option value="">-- First select a Booth --</option>');
      $('#revenue').val('');
      $('#earned').val('');
    }).fail(() => {
      $('#message').html(`<div class="alert alert-danger">Error saving transaction</div>`);
    });
  });
});
</script>
</body>
</html>
