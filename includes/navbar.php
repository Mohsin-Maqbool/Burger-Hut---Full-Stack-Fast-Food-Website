<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- Your navbar HTML -->

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold fs-3 text-warning" href="#">
      🍔 Burger Hut
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
      data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
      aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav align-items-lg-center gap-2">
        <li class="nav-item"><a class="nav-link fw-semibold" href="#home">Home</a></li>
        <li class="nav-item"><a class="nav-link fw-semibold" href="#about">About</a></li>
        <li class="nav-item"><a class="nav-link fw-semibold" href="#menu">Menu</a></li>
        <li class="nav-item"><a class="nav-link fw-semibold" href="#deals">Deals</a></li>
        <li class="nav-item"><a class="nav-link fw-semibold" href="#contact">Contact</a></li>

        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item">
            <span class="nav-link fw-semibold">👋 Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
          </li>
          <li class="nav-item">
            <a class="btn btn-sm btn-danger ms-lg-2 px-3 fw-semibold" href="logout.php">
              🚪 Logout
            </a>
          </li>


          <!-- <?php if ($_SESSION['role'] ?? '' === 'admin'): ?>
           
            <li class="nav-item">
              <a class="btn btn-sm btn-outline-dark ms-lg-2" href="/burgerhut_site/admin/dashboard.php">Admin Panel</a>
            </li>
          <?php endif; ?> -->

        <?php else: ?>
          <li class="nav-item">
            <a class="btn btn-sm btn-outline-success" href="login.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="btn btn-sm btn-warning ms-lg-2" href="register.php">Register</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>