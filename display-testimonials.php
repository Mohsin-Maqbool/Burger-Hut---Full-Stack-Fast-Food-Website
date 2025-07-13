<?php
include 'connect.php';

$result = mysqli_query($conn, "SELECT * FROM testimonials WHERE status = 1 ORDER BY created_at DESC");

if (mysqli_num_rows($result) > 0): ?>
  <section class="py-5 bg-light">
    <div class="container">
      <h2 class="text-center fw-bold mb-5">What Our Customers Say</h2>
      <div class="row g-4">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm h-100 border-0 rounded-4 p-3 bg-white position-relative">
              <?php if ($row['photo']): ?>
                <img src="<?= $row['photo']; ?>" class="rounded-circle border border-2 border-primary position-absolute top-0 start-50 translate-middle" width="70" height="70" style="object-fit: cover;" alt="<?= htmlspecialchars($row['name']); ?>">
              <?php endif; ?>

              <div class="card-body mt-4">
                <h5 class="card-title text-center fw-bold"><?= htmlspecialchars($row['name']); ?></h5>
                <p class="text-center text-warning mb-2">
                  <?= str_repeat("⭐", $row['rating']); ?>
                </p>
                <p class="card-text text-muted"><?= nl2br(htmlspecialchars($row['message'])); ?></p>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  </section>
<?php else: ?>
  <div class="text-center py-5">
    <p class="text-muted">No testimonials available yet.</p>
  </div>
<?php endif; ?>
