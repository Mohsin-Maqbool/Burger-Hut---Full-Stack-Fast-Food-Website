<?php
session_start();
include 'connect.php';

// Safe include function
function render_section($file) {
  if (file_exists($file)) include $file;
  else echo "<p class='text-danger text-center'>❌ Section file '$file' not found.</p>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Burger Hut | Home</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Lobster&family=Poppins&display=swap" rel="stylesheet"/>

  <!-- Custom CSS -->
  <link rel="stylesheet" href="styles.css">

  <!-- QR Code Scanner -->
  <script src="https://unpkg.com/html5-qrcode"></script>
</head>
<body>
<!-- <?php if (isset($_GET['logout']) && $_GET['logout'] === 'success'): ?>
  <div class="alert alert-success text-center">
    ✅ You have successfully logged out.
  </div>
<?php endif; ?> -->

<?php include 'includes/navbar.php'; ?>

<main>
  <?php
    render_section('includes/hero.php');
    render_section('includes/about.php');
    render_section('includes/menu.php');
    render_section('includes/deals.php');
  ?>

  <!-- ✅ Alert After Testimonial Submission -->
  <?php if (isset($_GET['testimonial_submitted']) && $_GET['testimonial_submitted'] == 1): ?>
    <div id="testimonialAlert" class="alert alert-success text-center mx-3">
      ✅ Your testimonial was submitted successfully and is pending approval!
    </div>
  <?php endif; ?>

  <?php render_section('includes/testimonials.php'); ?>
  <?php render_section('includes/contact.php'); ?>
</main>

<?php include 'includes/footer.php'; ?>

<!-- WhatsApp Float -->
<a href="https://wa.me/923014486345" target="_blank" class="whatsapp-float">
  <img src="https://img.icons8.com/color/48/whatsapp.png" alt="WhatsApp">
</a>

<!-- Sticky Order Button -->
<a href="#contact" class="sticky-order-btn d-md-none">Order Now</a>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<script>gsap.registerPlugin(ScrollTrigger);</script>
<script src="script.js"></script>
<script src="animations.js"></script>

<!-- ✅ Alert animation and URL cleanup -->
<script>
  const alertBox = document.getElementById("testimonialAlert");
  if (alertBox) {
    gsap.fromTo(alertBox, { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.8 });
    setTimeout(() => {
      gsap.to(alertBox, { opacity: 0, y: -20, duration: 0.8, onComplete: () => alertBox.remove() });
    }, 4000);

    const url = new URL(window.location);
    url.searchParams.delete("testimonial_submitted");
    window.history.replaceState({}, document.title, url);
  }
</script>

</body>
</html>
