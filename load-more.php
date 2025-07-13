<?php
include 'connect.php';

$offset = isset($_POST['offset']) ? (int)$_POST['offset'] : 0;

$sql = "SELECT * FROM products LIMIT 4 OFFSET $offset";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
?>
  <div class="col-6 col-md-4 col-lg-3 mb-4 menu-card">
  <div class="card h-100 shadow-sm border-0 overflow-hidden rounded-4">
    <div class="img-container">
      <img src="uploads/<?= htmlspecialchars($row['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']) ?>">
    </div>
    <div class="card-body text-center">
      <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
      <p class="card-text text-danger fw-bold fs-5">Rs <?= htmlspecialchars($row['price']) ?></p>
    </div>
  </div>
</div>

<?php
}
?>
