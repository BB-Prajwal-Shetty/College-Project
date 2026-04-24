<?php
session_start();
$page_title = "Admin Dashboard";
$nav_path = "../";
$css_path = "../assets/css/";
$js_path = "../assets/js/";

include '../includes/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Get dashboard statistics
$stats_query = "
    SELECT 
        (SELECT COUNT(*) FROM users WHERE status = 'Active') as total_users,
        (SELECT COUNT(*) FROM items WHERE availability_status = 'Available') as available_items,
        (SELECT COUNT(*) FROM bookings WHERE booking_status = 'Active') as active_bookings,
        (SELECT COUNT(*) FROM bookings WHERE DATE(created_at) = CURDATE()) as today_bookings,
        (SELECT SUM(total_amount) FROM bookings WHERE payment_status = 'Paid' AND DATE(created_at) = CURDATE()) as today_revenue,
        (SELECT SUM(total_amount) FROM bookings WHERE payment_status = 'Paid' AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())) as monthly_revenue,
        (SELECT COUNT(*) FROM categories WHERE status = 'Active') as total_categories
";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

// Get recent bookings
$recent_bookings_query = "
    SELECT b.*, i.item_name, u.full_name as user_name, c.category_name
    FROM bookings b
    JOIN items i ON b.item_id = i.item_id
    JOIN users u ON b.user_id = u.user_id
    JOIN categories c ON i.category_id = c.category_id
    ORDER BY b.created_at DESC
    LIMIT 10
";
$recent_bookings = mysqli_query($conn, $recent_bookings_query);

// Get popular categories
$popular_categories_query = "
    SELECT c.category_name, COUNT(b.booking_id) as booking_count
    FROM categories c
    LEFT JOIN items i ON c.category_id = i.category_id
    LEFT JOIN bookings b ON i.item_id = b.item_id
    WHERE c.status = 'Active'
    GROUP BY c.category_id, c.category_name
    ORDER BY booking_count DESC
    LIMIT 5
";
$popular_categories = mysqli_query($conn, $popular_categories_query);

include '../includes/header.php';
?>

<div class="container-fluid py-4">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-dark text-white rounded p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="mb-2">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Admin Dashboard
                        </h2>
                        <p class="mb-0 opacity-75">
                            Welcome back, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>! 
                            Manage your rental platform efficiently.
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <span class="badge bg-light text-dark px-3 py-2">
                            <i class="fas fa-crown me-1"></i>
                            <?php echo $_SESSION['admin_role']; ?> Admin
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card dashboard-card h-100">
                <div class="card-body text-center">
                    <div class="display-6 text-primary mb-2">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="display-6 mb-1"><?php echo number_format($stats['total_users'] ?: 0); ?></h3>
                    <p class="text-muted mb-3">Total Users</p>
                    <a href="manage_users.php" class="btn btn-sm btn-primary">Manage Users</a>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card dashboard-card h-100">
                <div class="card-body text-center">
                    <div class="display-6 text-success mb-2">
                        <i class="fas fa-box"></i>
                    </div>
                    <h3 class="display-6 mb-1"><?php echo number_format($stats['available_items'] ?: 0); ?></h3>
                    <p class="text-muted mb-3">Available Items</p>
                    <a href="manage_items.php" class="btn btn-sm btn-success">Manage Items</a>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card dashboard-card h-100">
                <div class="card-body text-center">
                    <div class="display-6 text-warning mb-2">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3 class="display-6 mb-1"><?php echo number_format($stats['active_bookings'] ?: 0); ?></h3>
                    <p class="text-muted mb-3">Active Bookings</p>
                    <a href="bookings.php" class="btn btn-sm btn-warning">View Bookings</a>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card dashboard-card h-100">
                <div class="card-body text-center">
                    <div class="display-6 text-info mb-2">
                        <i class="fas fa-rupee-sign"></i>
                    </div>
                    <h3 class="display-6 mb-1"><?php echo format_currency($stats['monthly_revenue'] ?: 0); ?></h3>
                    <p class="text-muted mb-3">Monthly Revenue</p>
                    <a href="reports.php" class="btn btn-sm btn-info">View Reports</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Stats -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="mb-1"><?php echo number_format($stats['today_bookings'] ?: 0); ?></h4>
                            <p class="mb-0 opacity-75">Today's Bookings</p>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-gradient-success text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="mb-1"><?php echo format_currency($stats['today_revenue'] ?: 0); ?></h4>
                            <p class="mb-0 opacity-75">Today's Revenue</p>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Bookings -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Bookings</h5>
                    <a href="bookings.php" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if (mysqli_num_rows($recent_bookings) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>User</th>
                                        <th>Item</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($booking = mysqli_fetch_assoc($recent_bookings)): ?>
                                        <tr>
                                            <td><strong>#<?php echo $booking['booking_id']; ?></strong></td>
                                            <td><?php echo htmlspecialchars($booking['user_name']); ?></td>
                                            <td>
                                                <div>
                                                    <strong><?php echo htmlspecialchars($booking['item_name']); ?></strong>
                                                    <br><small class="text-muted"><?php echo htmlspecialchars($booking['category_name']); ?></small>
                                                </div>
                                            </td>
                                            <td><?php echo format_currency($booking['total_amount']); ?></td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $booking['booking_status'] == 'Active' ? 'success' : 
                                                        ($booking['booking_status'] == 'Completed' ? 'primary' : 'danger'); 
                                                ?>">
                                                    <?php echo $booking['booking_status']; ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M j, Y', strtotime($booking['created_at'])); ?></td>
                                            <td>
                                                <a href="booking_details.php?id=<?php echo $booking['booking_id']; ?>" 
                                                   class="btn btn-sm btn-outline-primary">View</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No recent bookings</h6>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Popular Categories & Quick Actions -->
        <div class="col-lg-4 mb-4">
            <!-- Popular Categories -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Popular Categories</h5>
                </div>
                <div class="card-body">
                    <?php if (mysqli_num_rows($popular_categories) > 0): ?>
                        <?php while ($category = mysqli_fetch_assoc($popular_categories)): ?>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="mb-0"><?php echo htmlspecialchars($category['category_name']); ?></h6>
                                </div>
                                <div>
                                    <span class="badge bg-primary"><?php echo $category['booking_count']; ?> bookings</span>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-muted text-center">No data available</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="manage_items.php?action=add" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Add New Item
                        </a>
                        <a href="manage_categories.php" class="btn btn-info">
                            <i class="fas fa-tags me-2"></i>Manage Categories
                        </a>
                        <a href="manage_users.php" class="btn btn-warning">
                            <i class="fas fa-users me-2"></i>View All Users
                        </a>
                        <a href="reports.php" class="btn btn-primary">
                            <i class="fas fa-chart-bar me-2"></i>Generate Reports
                        </a>
                        <a href="settings.php" class="btn btn-secondary">
                            <i class="fas fa-cog me-2"></i>System Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, #0056b3 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, var(--success-color) 0%, #146c43 100%);
}

.dashboard-card {
    transition: transform 0.3s ease;
}

.dashboard-card:hover {
    transform: translateY(-5px);
}
</style>

<?php include '../includes/footer.php'; ?>
