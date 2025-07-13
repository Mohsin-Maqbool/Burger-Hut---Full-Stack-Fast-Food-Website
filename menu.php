<?php include 'connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Our Menu - Burger Hut</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <!-- Internal Styles -->
  <style>
   body {
  font-family: 'Poppins', sans-serif;
  margin: 0;
  overflow-x: hidden;
  background: radial-gradient(circle at top left, #ff5722 0%, #ffc107 30%, #0d6efd 80%, #0b0c10 100%);
  background-attachment: fixed;
  background-repeat: no-repeat;
  background-size: cover;
  position: relative;
}


body::before {
  content: "";
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5); /* soft dark overlay for readability */
  z-index: 0;
}




    /* Dark overlay */
    body::before {
      content: "";
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.6);
      z-index: 0;
    }

    /* Shapes */
    .background-shape {
      position: absolute;
      border-radius: 50%;
      opacity: 0.15;
      z-index: 1;
    }

    .shape1 {
      width: 200px;
      height: 200px;
      background-color: #ff5722;
      top: -60px;
      left: -60px;
    }

    .shape2 {
      width: 250px;
      height: 250px;
      background-color: #ffc107;
      bottom: -70px;
      right: -50px;
    }

    .shape3 {
      width: 140px;
      height: 140px;
      background-color: #0d6efd;
      top: 30%;
      right: 10%;
    }

    h2 {
      font-weight: 700;
      color: #fff;
      position: relative;
      z-index: 2;
    }

    .container {
      position: relative;
      z-index: 2;
    }

    .img-container {
      height: 180px;
      overflow: hidden;
      background: #fff;
      border-top-left-radius: 20px;
      border-top-right-radius: 20px;
    }

    .img-container img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease-in-out;
    }

    .img-container img:hover {
      transform: scale(1.08);
    }

    .card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border-radius: 20px;
      position: relative;
      z-index: 2;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .card-body {
      background-color: #fff;
      border-bottom-left-radius: 20px;
      border-bottom-right-radius: 20px;
    }

    .card-title {
      font-weight: 600;
      font-size: 1.2rem;
    }

    .card-text {
      margin-top: 10px;
    }

    .btn-primary {
      background-color: #ff5722;
      border: none;
      font-weight: 600;
      border-radius: 30px;
      padding: 10px 25px;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      background-color: #e64a19;
      transform: scale(1.05);
    }
  </style>
</head>
<body>

<!-- Background Shapes -->
<div class="background-shape shape1"></div>
<div class="background-shape shape2"></div>
<div class="background-shape shape3"></div>

<!-- Menu Section -->
<div class="container mt-5">
  <h2 class="text-center mb-5">Our Delicious Menu</h2>
  <div class="row" id="menu-items">
    <?php
    $sql = "SELECT * FROM products ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
    ?>
      <div class="col-md-6 col-lg-3 mb-4 menu-card">
        <div class="card h-100 shadow-sm border-0 overflow-hidden">
          <div class="img-container">
            <img src="uploads/<?= htmlspecialchars($row['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']) ?>">
          </div>
          <div class="card-body text-center">
            <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
            <p class="card-text text-danger fw-bold fs-5">Rs <?= htmlspecialchars($row['price']) ?></p>
            <a href="product.php?id=<?= $row['id'] ?>" class="btn btn-primary mt-3">Add to Order</a>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</div>

<!-- JS Libraries -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

<!-- GSAP Animation -->
<script>
  // Menu cards entrance
  gsap.from(".menu-card", {
    y: 40,
    opacity: 0,
    stagger: 0.15,
    duration: 0.6,
    ease: "power2.out"
  });

  // Floating animated shapes
  gsap.to(".shape1", {
    y: 25,
    x: 15,
    duration: 5,
    repeat: -1,
    yoyo: true,
    ease: "sine.inOut"
  });

  gsap.to(".shape2", {
    y: -30,
    x: -20,
    duration: 7,
    repeat: -1,
    yoyo: true,
    ease: "sine.inOut"
  });

  gsap.to(".shape3", {
    y: 20,
    x: 10,
    duration: 6,
    repeat: -1,
    yoyo: true,
    ease: "sine.inOut"
  });
</script>

</body>
</html>
