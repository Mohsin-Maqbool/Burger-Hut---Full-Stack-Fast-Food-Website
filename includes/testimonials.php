<?php
// testimonials.php
?> 
<section class="testimonials py-5 bg-light" id="testimonials">
  <div class="container">
    <h2 class="text-center fw-bold display-5 mb-5" style="font-family:'Lobster',cursive;color:#ff5722;">
      🍔 What Our Customers Say
    </h2>
    <div class="row g-4">
      <?php
      include 'connect.php';
      $res = mysqli_query($conn,"SELECT * FROM testimonials WHERE status=1 ORDER BY created_at DESC");
      if (mysqli_num_rows($res)) {
        while($r=mysqli_fetch_assoc($res)) {
          $img = $r['photo'] ? 'uploads/testimonials/'.basename($r['photo']) : 'assets/img/testimonial3.jpg';
          echo '<div class="col-md-4 testimonial-card-item">';
          echo '<div class="card testimonial-card border-0 shadow-lg p-4 h-100">';
          echo '<div class="d-flex align-items-center mb-3">';
          echo "<img src='$img' class='rounded-circle me-3' width='60' height='60' alt='User'>";
          echo '<div><h6 class="mb-0 fw-bold">'.htmlspecialchars($r['name']).'</h6>';
          echo '<div class="text-warning">'.str_repeat('⭐',$r['rating']).'</div></div></div>';
          echo '<p class="text-dark">&ldquo;'.htmlspecialchars($r['message']).'&rdquo;</p>';
          echo '</div></div>';
        }
      } else {
        echo '<p class="text-center">No testimonials yet.</p>';
      }
      ?>
    </div>
  </div>
</section>

<section id="testimonial-form" class="bg-white py-5">
  <div class="container">
    <h2 class="text-center mb-4">✨ Share Your Experience</h2>
    <form id="testimonialForm" action="process-testimonial.php" method="POST" enctype="multipart/form-data"
      class="p-4 shadow rounded bg-light mx-auto" style="max-width:600px">
      <div class="mb-3">
        <label class="form-label">Your Name*</label>
        <input type="text" name="name" class="form-control" required />
      </div>
      <div class="mb-3">
  <label class="form-label">Rating*</label>
  <select name="rating" class="form-select" required>
    <option value="" disabled selected>Rate 1–5 stars</option>
    <option value="1">⭐ 1</option>
    <option value="2">⭐⭐ 2</option>
    <option value="3">⭐⭐⭐ 3</option>
    <option value="4">⭐⭐⭐⭐ 4</option>
    <option value="5">⭐⭐⭐⭐⭐ 5</option>
  </select>
</div>

      <div class="mb-3">
        <label class="form-label">Your Review*</label>
        <textarea name="message" class="form-control" rows="4" required></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Optional Photo</label>
        <input type="file" name="photo" class="form-control" accept="image/*" />
      </div>
      <div class="text-end">
        <button type="submit" class="btn btn-warning px-4">Submit</button>
      </div>
    </form>
  </div>
</section>

<script>
  gsap.registerPlugin();
  gsap.from("#testimonialForm", { opacity:0, y:50, duration:1, ease:"power2.out" });
  gsap.from(".testimonial-card-item", { opacity:0, y:50, duration:1, stagger:0.2, ease:"power2.out", scrollTrigger:{
    trigger:".testimonial-card-item", start:"top 80%"
  }});
</script>
