<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Wina Bwangu — Home</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    body {
      font-family: "Poppins", Arial, sans-serif;
      background: #f5f5f7;
    }
    .navbar {
      background: #c8102e;
      box-shadow: 0 2px 8px 0 rgba(0,0,0,0.05);
      border-bottom-left-radius: 14px;
      border-bottom-right-radius: 14px;
    }
    .navbar-brand {
      font-weight: 900;
      letter-spacing: .5px;
      color: #fff !important;
    }
    .hero {
      background: linear-gradient(140deg, #c8102e 60%, #a11a21 100%);
      color: #fff;
      padding: 7rem 2rem 4rem 2rem;
      text-align: center;
      border-bottom-left-radius: 60px;
      border-bottom-right-radius: 60px;
    }
    .hero h1 {
      font-weight: 800;
      font-size: 3rem;
      letter-spacing: 1px;
    }
    .hero p {
      font-size: 1.1rem;
      max-width: 650px;
      margin: 0 auto 2rem;
    }
    .hero .btn {
      padding: .85rem 2.4rem;
      font-size: 1.18rem;
      margin: .4rem;
      border-radius: 40px;
      box-shadow: 0 3px 10px rgba(200,16,46,0.10);
      font-weight: bold;
      transition: all .25s;
      border: none;
    }
    .hero .btn-primary,
    .hero .btn-light.text-primary {
      background: #fff;
      color: #c8102e;
      border: 2px solid #c8102e;
    }
    .hero .btn-primary:hover,
    .hero .btn-light.text-primary:hover {
      background: #ffc1c8;
      color: #c8102e;
    }
    .hero .btn-outline-light {
      color: #fff;
      border: 2px solid #fff;
      background: transparent;
    }
    .hero .btn-outline-light:hover {
      background: #fff;
      color: #c8102e;
      border: 2px solid #c8102e;
    }
    .features {
      margin-top: -80px;
      position: relative;
      z-index: 10;
    }
    .card {
      border: none;
      border-radius: 24px;
      background: #fff;
      box-shadow: 0 2px 16px rgba(200,16,46,0.06);
      transition: box-shadow .2s, transform .25s;
    }
    .card:hover {
      box-shadow: 0 8px 32px rgba(200,16,46,0.12);
      transform: translateY(-8px) scale(1.025);
    }
    .card i {
      font-size: 2.7rem;
      margin-bottom: 1rem;
      color: #c8102e;
    }
    footer {
      background: #830c1a;
      color: #fff;
      padding: 2rem 0;
      font-size: .98rem;
    }
    footer a { color: #fff; text-decoration: none; }
    footer a:hover { color: #ffc1c8; }
  </style>
</head>
<body>

<!-- Sticky Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm fixed-top" style="background: #c8102e;">
  <div class="container px-3">
    <a class="navbar-brand fs-3 d-flex align-items-center gap-2" href="index.php">
      <img src="" alt="Wina Bwangu" height="32">
      <!-- <span>Wina Bwangu</span> -->
    </a>
    <div class="d-flex">
      <a href="./login.php" class="btn btn-outline-light rounded-pill px-4 fw-semibold">Login</a>
    </div>
  </div>
</nav>

<!-- Hero Banner -->
<section class="hero d-flex align-items-center" style="min-height: 65vh; padding-top:110px">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6 text-center text-lg-start mb-5 mb-lg-0">
        <h1 style="font-size:2.5rem; font-weight:900;">Empowering Businesses<span style='color: #ffd6dc;'> the right way</span></h1>
        <p class="mt-3 mb-4" style="font-size:1.23rem; color:#fff;">
          Wina Bwangu simplifies mobile-money operations. Easily track transactions, monitor booth performance, and view real-time analytics.</p>
        <div class="d-flex justify-content-center justify-content-lg-start gap-3">
          <a href="./login.php" class="btn btn-primary btn-lg px-4 rounded-pill">Login</a>
          <a href="#features" class="btn btn-outline-light btn-lg px-4 rounded-pill">Learn More</a>
        </div>
      </div>
      <div class="col-lg-6 text-center">
        <img alt="hero" src="Static/paying.jpg" style="max-width: 370px; width: 92%; min-width: 220px;">
      </div>
    </div>
  </div>
</section>

<!-- Info Cards with Spacing -->
<section id="features" class="features py-5 bg-white" style="margin-top: -60px;">
  <div class="container">
    <div class="row g-5">
      <div class="col-md-4">
        <div class="card text-center p-4 h-100 border-0 shadow-sm">
          <span class="display-4 mb-2" style="color:#c8102e;"><i class="fa-solid fa-building-columns"></i></span>
          <h5 class="fw-bold mt-2">Agency Services</h5>
          <p class="text-muted">Manage Airtel, MTN, Zamtel, Zanaco and FNB transactions from one interface.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-center p-4 h-100 border-0 shadow-sm">
          <span class="display-4 mb-2" style="color:#ffa800;"><i class="fa-solid fa-hand-holding-dollar"></i></span>
          <h5 class="fw-bold mt-2">Revenue Insights</h5>
          <p class="text-muted">Monitor performance, calculate commissions, and keep your booths on track.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-center p-4 h-100 border-0 shadow-sm">
          <span class="display-4 mb-2" style="color:#2f4075;"><i class="fa-solid fa-chart-pie"></i></span>
          <h5 class="fw-bold mt-2">Visual Analytics</h5>
          <p class="text-muted">Dashboards turn complex data into clear, actionable business intelligence.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Footer with links and info -->
<footer class="mt-5 pt-5 pb-3" style="background:#830c1a;">
  <div class="container">
    <div class="row pb-2">
      <div class="col-md-6">
        <h5 class="text-white fw-bold mb-3">Wina Bwangu</h5>
        <p class="mb-2">&copy; <?php echo date("Y"); ?> Wina Bwangu Agency System</p>
        <small style="color:#eee;">All rights reserved. For demonstration use only.</small>
      </div>
      <div class="col-md-3">
        <h6 class="fw-bold text-white">Quick Links</h6>
        <ul class="list-unstyled">
          <li><a href="#features">Services</a></li>
          <li><a href="auth/login.php">Login</a></li>
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
      </div>
      <div class="col-md-3">
        <h6 class="fw-bold text-white">Follow Us</h6>
        <a class="text-white me-3" href="#"><i class="fab fa-facebook fa-lg"></i></a>
        <a class="text-white me-3" href="#"><i class="fab fa-x-twitter fa-lg"></i></a>
        <a class="text-white me-3" href="#"><i class="fab fa-linkedin fa-lg"></i></a>
        <a class="text-white" href="#"><i class="fab fa-instagram fa-lg"></i></a>
      </div>
    </div>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
