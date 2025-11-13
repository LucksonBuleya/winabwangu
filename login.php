<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Wina Bwangu</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    /* ===== Background image and blur setup ===== */
    body {
      background: url('static/paying2.webp') no-repeat center center/cover;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: "Poppins", Arial, sans-serif;
      position: relative;
    }

    /* Adds a semi-transparent blurred overlay */
    body::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      backdrop-filter: blur(8px);
      background: rgba(0, 0, 0, 0.35); /* subtle dark overlay */
      z-index: 0;
    }

    /* Make sure login form appears above the blur layer */
    .container {
      position: relative;
      z-index: 1;
    }

    .login-container {
      max-width: 450px;
      width: 100%;
    }

    .card {
      border: none;
      border-radius: 24px;
      background: rgba(255, 255, 255, 0.85); /* semi-transparent white */
      backdrop-filter: blur(4px);
      box-shadow: 0 2px 16px rgba(200,16,46,0.2);
    }

    .card-header {
      border-top-left-radius: 24px;
      border-top-right-radius: 24px;
      background: #c8102e;
      color: #fff;
      text-align: center;
      padding: 2rem;
    }

    .btn-login {
      background: #c8102e;
      border: none;
      color: white;
      font-size: 1.15rem;
      font-weight: 600;
      padding: 0.8rem;
      border-radius: 40px;
      transition: background .2s, box-shadow .2s;
      box-shadow: 0 2px 12px rgba(200,16,46,0.08);
    }

    .btn-login:hover {
      background: #830c1a;
      color: #fff;
    }

    input.form-control, select.form-select {
      border-radius: 12px;
      border: 1px solid #ffd6dc;
      font-size: 1.08rem;
    }

    input.form-control:focus, select.form-select:focus {
      border-color: #c8102e;
      box-shadow: 0 0 0 2px #ffe3e5;
    }

    .alert {
      border-radius: 10px;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6 login-container">
      
      <div class="card">
        <div class="card-header">
          <h3 class="mb-0"><i class="fa-solid fa-lock me-2"></i>Login to Wina Bwangu</h3>
        </div>
        <div class="card-body p-4">
          
          <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
              <i class="fa-solid fa-circle-exclamation me-2"></i><?php echo htmlspecialchars($_GET['error']); ?>
            </div>
          <?php endif; ?>
          
          <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
              <i class="fa-solid fa-circle-check me-2"></i><?php echo htmlspecialchars($_GET['success']); ?>
            </div>
          <?php endif; ?>
          
          <form method="POST" action="login_process.php">
            
            <div class="mb-3">
              <label for="username" class="form-label fw-semibold">Username</label>
              <input type="text" class="form-control" id="username" name="username" required autofocus>
            </div>
            
            <div class="mb-3">
              <label for="password" class="form-label fw-semibold">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            
            <div class="mb-3">
              <label for="booth_id" class="form-label fw-semibold">Select Booth</label>
              <select class="form-select" id="booth_id" name="booth_id">
                <option value="">-- Choose Your Booth --</option>
                <?php
                  include __DIR__ . '/db_connect.php';
                  $result = $conn->query("SELECT Booth_id, BoothName, Location FROM booths ORDER BY BoothName");
                  if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      echo "<option value='{$row['Booth_id']}'>{$row['BoothName']} ({$row['Location']})</option>";
                    }
                  }
                ?>
              </select>
              <small class="text-muted">Managers: select your booth. Admins: leave blank.</small>
            </div>
            
            <button type="submit" class="btn btn-login w-100">
              <i class="fa-solid fa-sign-in-alt me-2"></i>Login
            </button>
            
          </form>
          
          <div class="text-center mt-3">
            <a href="index.php" class="text-decoration-none"><i class="fa-solid fa-arrow-left me-1"></i> Back to Home</a>
          </div>
          
        </div>
      </div>
      
      <div class="text-center text-white mt-3">
        <small>Default Credentials: admin/admin or manager/manager</small>
      </div>
      
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
