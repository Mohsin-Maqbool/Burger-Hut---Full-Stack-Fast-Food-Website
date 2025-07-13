<?php
include '../includes/navbar.php';
include '../connect.php';

$query = "SELECT * FROM settings WHERE id = 1 LIMIT 1";
$result = mysqli_query($conn, $query);
$settings = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $site_name = mysqli_real_escape_string($conn, $_POST['site_name']);
  $site_email = mysqli_real_escape_string($conn, $_POST['site_email']);
  $site_phone = mysqli_real_escape_string($conn, $_POST['site_phone']);
  $site_address = mysqli_real_escape_string($conn, $_POST['site_address']);
  $logo = $settings['site_logo'];

  if (!empty($_FILES['site_logo']['name'])) {
    $logo_name = time() . '_' . basename($_FILES['site_logo']['name']);
    $target = "../uploads/" . $logo_name;
    if (move_uploaded_file($_FILES['site_logo']['tmp_name'], $target)) {
      $logo = $logo_name;
    }
  }

  $update = "UPDATE settings SET 
              site_name='$site_name',
              site_email='$site_email',
              site_phone='$site_phone',
              site_address='$site_address',
              site_logo='$logo'
            WHERE id = 1";

  if (mysqli_query($conn, $update)) {
    echo '<div class="container"><div class="alert alert-success text-center mt-3">Settings updated successfully!</div></div>';
    $result = mysqli_query($conn, $query);
    $settings = mysqli_fetch_assoc($result);
  } else {
    echo '<div class="container"><div class="alert alert-danger text-center mt-3">Error updating settings.</div></div>';
  }
}
?>

<!-- Custom Styling -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<style>
  body {
    background: linear-gradient(to right, #f9f9f9, #e6e6ff);
    font-family: 'Segoe UI', sans-serif;
  }
  .settings-wrapper {
    max-width: 750px;
    margin: 60px auto;
    background: #fff;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    transition: 0.3s ease;
  }
  .form-label {
    font-weight: 600;
  }
  .preview-logo {
    max-height: 70px;
    margin-top: 10px;
    border-radius: 10px;
  }
  button.btn-primary {
    background: linear-gradient(135deg, #4e54c8, #8f94fb);
    border: none;
    font-weight: 600;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
  }
  button.btn-primary:hover {
    background: linear-gradient(135deg, #5d63d9, #a1a7ff);
  }
  @media (max-width: 768px) {
    .settings-wrapper {
      margin: 30px 15px;
      padding: 25px;
    }
  }
</style>

<!-- Responsive Form UI -->
<div class="container">
  <div class="settings-wrapper" id="settingsForm">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="fw-bold">Site Settings</h4>
      <span class="text-muted"><?php echo date('l, F j, Y'); ?></span>
    </div>

    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="site_name" class="form-label">Site Name</label>
        <input type="text" name="site_name" id="site_name" class="form-control" required value="<?php echo htmlspecialchars($settings['site_name']); ?>">
      </div>

      <div class="mb-3">
        <label for="site_email" class="form-label">Contact Email</label>
        <input type="email" name="site_email" id="site_email" class="form-control" required value="<?php echo htmlspecialchars($settings['site_email']); ?>">
      </div>

      <div class="mb-3">
        <label for="site_phone" class="form-label">Phone Number</label>
        <input type="text" name="site_phone" id="site_phone" class="form-control" value="<?php echo htmlspecialchars($settings['site_phone']); ?>">
      </div>

      <div class="mb-3">
        <label for="site_address" class="form-label">Address</label>
        <textarea name="site_address" id="site_address" class="form-control" rows="3"><?php echo htmlspecialchars($settings['site_address']); ?></textarea>
      </div>

      <div class="mb-3">
        <label for="site_logo" class="form-label">Site Logo</label><br>
        <?php if (!empty($settings['site_logo'])): ?>
          <img src="../uploads/<?php echo $settings['site_logo']; ?>" class="preview-logo" alt="Current Logo">
        <?php endif; ?>
        <input type="file" name="site_logo" class="form-control mt-2">
      </div>

      <button type="submit" class="btn btn-primary w-100">Save Settings</button>
    </form>
  </div>
</div>

<script>
  gsap.from("#settingsForm", {
    duration: 1,
    y: 100,
    opacity: 0,
    ease: "power4.out"
  });
</script>

<?php include '../includes/footer.php'; ?>
