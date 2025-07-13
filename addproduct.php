<?php
session_start();
include 'connect.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $category = trim($_POST['category']);
    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $sql = "INSERT INTO products (name, description, price, category, image) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdss", $name, $description, $price, $category, $image);

        if ($stmt->execute()) {
            $message = "✅ Product added successfully!";
        } else {
            $message = "❌ Failed to add product: " . $conn->error;
        }
    } else {
        $message = "❌ Failed to upload image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product – Burger Hut</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <style>
    * {
      box-sizing: border-box;
    }

   body {
  margin: 0;
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
              url("https://images.unsplash.com/photo-1606755962773-0f2f8581e5f4?auto=format&fit=crop&w=1740&q=80") 
              no-repeat center center / cover;
  background-color: #111; /* fallback */
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
}


    .container {
      background-color: rgba(255, 255, 255, 0.97);
      padding: 35px 30px;
      border-radius: 15px;
      max-width: 500px;
      width: 100%;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }

    h2 {
      margin-bottom: 20px;
      text-align: center;
      color: #222;
    }

    label {
      display: block;
      margin-top: 12px;
      font-weight: bold;
    }

    input[type="text"],
    input[type="number"],
    input[type="file"],
    textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 8px;
      margin-top: 6px;
      background-color: #f9f9f9;
    }

    textarea {
      resize: vertical;
      min-height: 80px;
    }

    button {
      margin-top: 20px;
      width: 100%;
      padding: 12px;
      background-color: #e67e22;
      border: none;
      color: white;
      font-size: 16px;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #d35400;
    }

    p {
      text-align: center;
      font-weight: bold;
      font-size: 14px;
      margin-top: 10px;
    }

    .error {
      color: red;
    }

    @media (max-width: 600px) {
      .container {
        padding: 25px 20px;
      }

      button {
        font-size: 14px;
      }
    }
  </style>
</head>
<body>

  <div class="container" id="productForm">
    <h2>🍔 Add New Product</h2>

    <?php if ($message): ?>
      <p class="<?= str_contains($message, '❌') ? 'error' : '' ?>">
        <?= htmlspecialchars($message) ?>
      </p>
    <?php endif; ?>

    <form action="addproduct.php" method="POST" enctype="multipart/form-data">
      <label for="name">Product Name:</label>
      <input type="text" name="name" required>

      <label for="description">Description:</label>
      <textarea name="description" required></textarea>

      <label for="price">Price (PKR):</label>
      <input type="number" name="price" step="0.01" required>

      <label for="category">Category:</label>
      <input type="text" name="category" required>

      <label for="image">Image:</label>
      <input type="file" name="image" accept="image/*" required>

      <button type="submit">Add Product</button>
    </form>
  </div>

  <script>
    gsap.from("#productForm", {
      y: -80,
      opacity: 0,
      duration: 1.2,
      ease: "power3.out"
    });
  </script>

</body>
</html>
