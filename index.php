<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
check_admin_auth();

include 'includes/header.php';
include 'includes/sidebar.php';

// স্ট্যাটিস্টিক্স ডেটা
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_packages = $pdo->query("SELECT COUNT(*) FROM packages")->fetchColumn();
$total_orders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$pending_orders = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetchColumn();
?>

<div class="content">
    <h2>Admin Dashboard</h2>
    
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Users</h3>
            <p class="stat-number"><?php echo $total_users; ?></p>
        </div>
        <div class="stat-card">
            <h3>Total Packages</h3>
            <p class="stat-number"><?php echo $total_packages; ?></p>
        </div>
        <div class="stat-card">
            <h3>Total Orders</h3>
            <p class="stat-number"><?php echo $total_orders; ?></p>
        </div>
        <div class="stat-card">
            <h3>Pending Orders</h3>
            <p class="stat-number"><?php echo $pending_orders; ?></p>
        </div>
    </div>
    
    <!-- আরও কন্টেন্ট -->
</div>

<?php include 'includes/footer.php'; ?>