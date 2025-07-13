<?php
require 'connect.php'; // DB connection

$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = 3; // Load 3 more

$sql = "SELECT * FROM products LIMIT $offset, $limit";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
  echo '
  <div class="col-md-4 mb-4">
    <div class="menu-card">
      <img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '">
      <h5>' . htmlspecialchars($row['name']) . '</h5>
      <p>$' . htmlspecialchars($row['price']) . '</p>
      <a href="order.php?product_id=' . $row['id'] . '" class="btn btn-danger">Add to Order</a>
    </div>
  </div>';
}
?>
