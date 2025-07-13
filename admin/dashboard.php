<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'admin') {
    header("Location:/burgerhut_site/login.php");
    exit();
}

require_once '../connect.php';

// Dashboard Counts
$countQueries = [
    "SELECT COUNT(*) AS c FROM products",
    "SELECT COUNT(*) AS c FROM orders",
    "SELECT COUNT(*) AS c FROM users",
    "SELECT COUNT(*) AS c FROM testimonials",
    "SELECT COUNT(*) AS c FROM messages",
    "SELECT SUM(total_amount) AS c FROM orders WHERE payment_status='Paid'"
];
$counts = array_map(function ($sql) {
    $res = mysqli_query($GLOBALS['conn'], $sql);
    return mysqli_fetch_assoc($res)['c'] ?? 0;
}, $countQueries);
list($totalProducts, $totalOrders, $totalUsers, $totalTestimonials, $totalMessages, $totalIncome) = $counts;

// Monthly Sales Chart Data
$salesLabels = [];
$salesData = [];
$salesQuery = "SELECT DATE_FORMAT(created_at, '%b') AS month, SUM(total_amount) AS total 
               FROM orders 
               WHERE payment_status='Paid' 
               GROUP BY MONTH(created_at) 
               ORDER BY MONTH(created_at)";
$result = mysqli_query($conn, $salesQuery);
while ($row = mysqli_fetch_assoc($result)) {
    $salesLabels[] = $row['month'];
    $salesData[] = $row['total'];
}
?>
<?php include '../includes/navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Admin Dashboard • Burger Hut</title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            background: #f0f2f5;
        }
        

        .card {
            border-radius: 1rem;
            box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.1);
            transition: transform .3s, box-shadow .3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1.5rem 2rem rgba(0, 0, 0, 0.2);
        }

        .card-icon {
            font-size: 2.5rem;
            width: 4rem;
            height: 4rem;
            line-height: 4rem;
            border-radius: 50%;
            display: inline-block;
            color: #fff;
            margin-bottom: 1rem;
        }

        .bg-orange {
            background: #ff6f00;
        }

        .bg-blue {
            background: #007bff;
        }

        .bg-green {
            background: #28a745;
        }

        .bg-purple {
            background: #6f42c1;
        }

        .bg-red {
            background: #dc3545;
        }

        .bg-cyan {
            background: #17a2b8;
        }
    </style>
</head>

<body>
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded mb-3 shadow-sm">
            <h3 class="mb-0">Admin Dashboard</h3>
            <p class="text-muted mb-0"><?= date('l, F j, Y') ?></p>

            <input id="cardFilter" class="form-control w-25" placeholder="Filter cards..." />
        </div>

        <div class="row g-3" id="dashboardGrid">
            <?php
            $items = [
                ['Products', $totalProducts, 'fa-box', 'bg-orange', 'products.php'],
                ['Orders', $totalOrders, 'fa-shopping-cart', 'bg-blue', 'orders.php'],
                ['Users', $totalUsers, 'fa-users', 'bg-green', 'users.php'],
                ['Testimonials', $totalTestimonials, 'fa-star', 'bg-purple', 'testimonials.php'],
                ['Messages', $totalMessages, 'fa-envelope', 'bg-red', 'messages.php'],
                ['Income', 'PKR ' . number_format($totalIncome), 'fa-coins', 'bg-cyan', '#']
            ];
            foreach ($items as $it):
            ?>
                <div class="col-md-4 dashboard-card" data-label="<?= strtolower($it[0]) ?>">
                    <div class="card p-4 text-center">
                        <div class="card-icon <?= $it[3] ?>"><i class="fas <?= $it[2] ?>"></i></div>
                        <h4><?= $it[1] ?></h4>
                        <p>Total <?= $it[0] ?></p>
                        <?php if ($it[4] !== '#'): ?>
                            <a href="<?= $it[4] ?>" class="btn btn-sm btn-outline-primary">Manage</a>
                        <?php endif ?>
                    </div>
                </div>
            <?php endforeach ?>
        </div>

        <div class="dashboard-actions text-center mt-4">
            <!-- Add Product Modal -->
            <div class="modal fade" id="addProductModal" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <form class="modal-content p-3" method="POST" action="products.php" enctype="multipart/form-data">
                        <div class="modal-header border-0">
                            <h5 class="modal-title"><i class="fas fa-plus-circle text-success me-2"></i>Add New Product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control form-control-lg" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <input type="text" name="category" class="form-control form-control-lg" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Price (PKR)</label>
                                <input type="number" name="price" class="form-control form-control-lg" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Image</label>
                                <input type="file" name="image" class="form-control form-control-lg" accept="image/*" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" rows="4" class="form-control form-control-lg" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="submit" name="add_product" class="btn btn-success w-100 py-2">
                                <i class="fas fa-check-circle me-1"></i> Add Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Add User Modal -->
            <div class="modal fade" id="addUserModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <form class="modal-content p-3" method="POST" action="users.php">
                        <div class="modal-header border-0">
                            <h5 class="modal-title"><i class="fas fa-user-plus text-primary me-2"></i>Add New User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body row g-3">
                            <div class="col-12">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control form-control-lg" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control form-control-lg" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control form-control-lg" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-select form-select-lg">
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="submit" name="add_user" class="btn btn-primary w-100 py-2">
                                <i class="fas fa-user-plus me-1"></i> Add User
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="fas fa-plus"></i> Add Product
            </button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-user-plus"></i> Add User
            </button>
            <a href="settings.php" class="btn btn-dark"><i class="fas fa-cogs"></i> Settings</a>
            <a href="../logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>

        <!-- Chart Section -->
        <div class="bg-white mt-5 p-4 rounded shadow-sm">
            <h4 class="text-center mb-4">📈 Monthly Sales Report</h4>
            <div style="height:300px;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <br><br>

        <!-- JS Libraries -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

        <script>
            document.getElementById('cardFilter').addEventListener('input', function() {
                const val = this.value.toLowerCase();
                document.querySelectorAll('.dashboard-card').forEach(card => {
                    const label = card.getAttribute('data-label');
                    card.style.display = label.includes(val) ? 'block' : 'none';
                });
            });
        </script>

        <script>
            // === GSAP Animations ===
            gsap.from(".dashboard-card", {
                duration: 1,
                opacity: 0,
                y: 50,
                stagger: 0.2,
                ease: "power3.out"
            });

            gsap.from("h3.mb-0", {
                duration: 1,
                x: -50,
                opacity: 0,
                ease: "power2.out"
            });

            // Filter Dashboard Cards
            document.getElementById("cardFilter").addEventListener("input", function() {
                const filter = this.value.toLowerCase();
                document.querySelectorAll(".dashboard-card").forEach(card => {
                    const label = card.getAttribute("data-label");
                    card.style.display = label.includes(filter) ? "block" : "none";
                });
            });

            // Chart.js: Monthly Sales Report
            const ctx = document.getElementById('salesChart').getContext('2d');
            const salesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($salesLabels) ?>,
                    datasets: [{
                        label: 'Sales (PKR)',
                        data: <?= json_encode($salesData) ?>,
                        backgroundColor: '#007bff',
                        borderRadius: 10,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => 'PKR ' + value
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => 'PKR ' + ctx.parsed.y
                            }
                        }
                    }
                }

            });
        </script>


        <?php include '../includes/footer.php'; ?>
</body>

</html>