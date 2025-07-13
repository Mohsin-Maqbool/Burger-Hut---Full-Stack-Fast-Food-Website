<?php
include '../connect.php';
include '../includes/navbar.php';

// Handle delete if requested
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $deleteQuery = "DELETE FROM messages WHERE id = $id";
    mysqli_query($conn, $deleteQuery);
    header("Location: messages.php?deleted=1");
    exit;
}
?>

<!-- External Links -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container py-5">
  <h2 class="text-center mb-4 fw-bold display-6 heading-title">User Messages</h2>

  <?php if (isset($_GET['deleted'])): ?>
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Message deleted successfully!',
        toast: true,
        position: 'top-end',
        timer: 3000,
        showConfirmButton: false
      });
    </script>
  <?php endif; ?>

  <div class="table-responsive">
    <table class="table table-hover table-bordered shadow-sm bg-white text-center align-middle">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Message</th>
          <th>Received At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $query = "SELECT * FROM messages ORDER BY id DESC";
        $result = mysqli_query($conn, $query);
        $i = 1;
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr class="user-row bg-light rounded shadow-sm">
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
            <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
            <td>
              <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $row['id'] ?>)">
                <i class="fas fa-trash-alt"></i> Delete
              </button>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Styling -->
<style>
  body {
    background: #f5f7fa;
    font-family: 'Roboto', sans-serif;
  }

  .heading-title {
    color: #111827;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
  }

  .btn-danger:hover {
    transform: scale(1.05);
  }

  .user-row {
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.3s ease-in-out;
  }

  @media (max-width: 768px) {
    .table th, .table td {
      font-size: 14px;
      padding: 8px;
    }
    .heading-title {
      font-size: 1.5rem;
    }
  }
</style>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

<script>
  gsap.to(".heading-title", {
    duration: 1,
    y: 0,
    opacity: 1,
    ease: "power4.out"
  });

  gsap.to(".user-row", {
    duration: 0.6,
    opacity: 1,
    y: 0,
    stagger: 0.1,
    ease: "power3.out"
  });

  function confirmDelete(id) {
    Swal.fire({
      title: 'Are you sure?',
      text: "You want to delete this message?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, Delete!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = 'messages.php?delete_id=' + id;
      }
    });
  }
</script>

<?php include '../includes/footer.php'; ?>
