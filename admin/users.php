<?php
include '../connect.php';
include '../includes/navbar.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">

<div class="container py-5">
    <h2 class="text-center mb-4 fw-bold display-6 heading-title">Registered Users</h2>

    <?php if (isset($_GET['deleted'])): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'User deleted successfully!',
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    <?php elseif (isset($_GET['error'])): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error deleting user!',
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover table-bordered shadow-sm bg-white rounded text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Avatar</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Registered At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="user-table-body">
                <?php
                $query = "SELECT * FROM users WHERE role = 'user' ORDER BY id DESC";
                $result = mysqli_query($conn, $query);
                $i = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <tr class="user-row bg-light rounded shadow-sm">
                        <td><?= $i++ ?></td>
                        <td>
                            <img src="<?= !empty($row['photo']) ? $row['photo'] : '../assets/img/user.png' ?>"
                                alt="User Avatar"
                                class="rounded-circle shadow border avatar-img"
                                width="45" height="45">
                        </td>
                        <td class="fw-semibold"><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td>
                            <?= isset($row['created_at']) ? date('d M Y', strtotime($row['created_at'])) : '—' ?>
                        </td>
                        <td>
                            <button class="btn btn-delete btn-sm shadow-sm" onclick="deleteUser(<?= $row['id'] ?>)">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>
    </div>
</div>

<style>
    body {
        font-family: 'Roboto', sans-serif;
        background: #f5f7fa;
    }

    .heading-title {
        color: #111827;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
    }

    .btn-delete {
        background-color: #ef4444;
        color: white;
        transition: 0.3s ease;
    }

    .btn-delete:hover {
        background-color: #dc2626;
        transform: scale(1.05);
    }

    .user-row {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.3s ease-in-out;
    }

    .avatar-img {
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .avatar-img:hover {
        transform: scale(1.1);
    }

    @media (max-width: 768px) {

        .table th,
        .table td {
            font-size: 14px;
            padding: 8px;
        }

        .btn-delete {
            font-size: 12px;
            padding: 5px 10px;
        }

        .heading-title {
            font-size: 1.5rem;
        }
    }
</style>

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

    function deleteUser(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This user will be deleted permanently.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'delete_user.php?id=' + id;
            }
        });
    }
</script>

<?php include '../includes/footer.php'; ?>