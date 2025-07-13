<section class="testimonial-form-section py-5 bg-white">
  <div class="container">
    <h2 class="text-center fw-bold mb-4">Share Your Experience</h2>

    <?php session_start(); if (isset($_SESSION['message'])): ?>
      <div class="alert alert-success">
        <?= $_SESSION['message']; unset($_SESSION['message']); ?>
      </div>
    <?php endif; ?>

    <form action="process-testimonial.php" method="POST" enctype="multipart/form-data" class="p-4 border rounded shadow-lg bg-light">
      <div class="mb-3">
        <label for="name" class="form-label">Your Name*</label>
        <input type="text" class="form-control" id="name" name="name" required>
      </div>

      <div class="mb-3">
        <label for="rating" class="form-label">Rating (1-5)*</label>
        <select class="form-select" id="rating" name="rating" required>
          <option value="">Select Rating</option>
          <option value="5">⭐⭐⭐⭐⭐</option>
          <option value="4">⭐⭐⭐⭐</option>
          <option value="3">⭐⭐⭐</option>
          <option value="2">⭐⭐</option>
          <option value="1">⭐</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="message" class="form-label">Your Review*</label>
        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
      </div>

      <div class="mb-3">
        <label for="photo" class="form-label">Optional Photo</label>
        <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
      </div>

      <button type="submit" class="btn btn-primary w-100">Submit Testimonial</button>
    </form>
  </div>
</section>
