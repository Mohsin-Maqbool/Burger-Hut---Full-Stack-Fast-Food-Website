<?php
session_start();
include '../connect.php';
include '../includes/navbar.php';

// Handle order confirmation
if (isset($_GET['confirm'])) {
    $orderId = intval($_GET['confirm']);
    $sql = "UPDATE orders SET status = 'confirmed' WHERE id = $orderId";
    if (mysqli_query($conn, $sql)) {
        header("Location: orders.php?confirmed=1");
        exit;
    } else {
        header("Location: orders.php?error=1");
        exit;
    }
}

// Handle order deletion (including related order_items)
if (isset($_GET['delete'])) {
    $orderId = intval($_GET['delete']);

    $deleteItemsQuery = "DELETE FROM order_items WHERE order_id = $orderId";
    $deleteOrderQuery = "DELETE FROM orders WHERE id = $orderId";

    mysqli_begin_transaction($conn);
    try {
        mysqli_query($conn, $deleteItemsQuery); // delete child table entries
        mysqli_query($conn, $deleteOrderQuery); // then delete the order

        mysqli_commit($conn);
        header("Location: orders.php?deleted=1");
        exit;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        header("Location: orders.php?error=1");
        exit;
    }
}

// Fetch all orders
$sql = "SELECT * FROM orders ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

// Toast messages
$toast = '';
if (isset($_GET['confirmed'])) $toast = 'Order confirmed successfully!';
elseif (isset($_GET['deleted'])) $toast = 'Order deleted successfully!';
elseif (isset($_GET['error'])) $toast = 'Something went wrong.';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Orders - Burger Hut</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <style>
        body {
            background-color: #f8fafc;
        }

        .container {
            margin-top: 40px;
        }

        .table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .table th {
            background-color: #111827;
            color: white;
        }

        .btn-confirm {
            background-color: #10b981;
            color: white;
        }

        .btn-confirm:hover {
            background-color: #059669;
        }

        .btn-delete {
            background-color: #ef4444;
            color: white;
        }

        .btn-delete:hover {
            background-color: #dc2626;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2 class="mb-4">📦 Manage Orders</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center" id="orderTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Order Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= htmlspecialchars($row['customer_name'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($row['customer_email'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($row['customer_phone'] ?? 'N/A') ?></td>
                                <td>$<?= number_format($row['total_amount'] ?? 0, 2) ?></td>
                                <td><?= ucfirst($row['payment_status'] ?? 'N/A') ?></td>
                                <td>
                                    <?php
                                    $status = strtolower($row['status'] ?? '');
                                    if ($status === 'pending') {
                                        echo '<span class="badge bg-warning text-dark">Pending</span>';
                                    } elseif ($status === 'confirmed') {
                                        echo '<span class="badge bg-success">Confirmed</span>';
                                    } else {
                                        echo '<span class="badge bg-secondary">' . ucfirst($status) . '</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $created = $row['created_at'] ?? '';
                                    $formatted = ($created && strtotime($created)) ? date('d M Y, h:i A', strtotime($created)) : 'N/A';
                                    echo $formatted;
                                    ?>
                                </td>
                                <td>
                                    <?php if ($status === 'pending'): ?>
                                        <a href="orders.php?confirm=<?= $row['id'] ?>" class="btn btn-confirm btn-sm">
                                            <i class="fas fa-check-circle"></i> Confirm
                                        </a>
                                    <?php endif; ?>
                                    <a href="orders.php?delete=<?= $row['id'] ?>" class="btn btn-delete btn-sm" onclick="return confirm('Are you sure to delete this order?');">
                                        <i class="fas fa-trash"></i>
                                    </a>

                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9">No orders found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if ($toast): ?>
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '<?= $toast ?>',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
            });
        </script>
    <?php endif; ?>

    <script>
        gsap.from("tbody tr", {
            opacity: 0,
            y: 30,
            duration: 0.4,
            stagger: 0.1
        });
    </script>

</body>

</html>