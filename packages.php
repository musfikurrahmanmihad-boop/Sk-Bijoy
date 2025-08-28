<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
check_admin_auth();

include 'includes/header.php';
include 'includes/sidebar.php';

// প্যাকেজ যুক্ত করা
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $bandwidth = $_POST['bandwidth'];
    $price = $_POST['price'];
    $server_ip = $_POST['server_ip'];
    $winbox_port = $_POST['winbox_port'];
    $api_port = $_POST['api_port'];
    $web_port = $_POST['web_port'];
    
    $stmt = $pdo->prepare("INSERT INTO packages (name, category, bandwidth, price, server_ip, winbox_port, api_port, web_port) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $category, $bandwidth, $price, $server_ip, $winbox_port, $api_port, $web_port]);
    
    header("Location: packages.php?success=1");
    exit();
}

// প্যাকেজ ডিলিট করা
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM packages WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: packages.php?deleted=1");
    exit();
}

// সব প্যাকেজ পাওয়া
$packages = $pdo->query("SELECT * FROM packages ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content">
    <h2>Package Management</h2>
    
    <?php if(isset($_GET['success'])): ?>
        <div class="alert success">Package added successfully!</div>
    <?php endif; ?>
    
    <?php if(isset($_GET['deleted'])): ?>
        <div class="alert success">Package deleted successfully!</div>
    <?php endif; ?>
    
    <!-- প্যাকেজ যুক্ত করার ফর্ম -->
    <div class="card">
        <h3>Add New Package</h3>
        <form method="POST">
            <div class="form-group">
                <label>Package Name</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category" required>
                    <option>Basic</option>
                    <option>Premium</option>
                    <option>Business</option>
                </select>
            </div>
            <div class="form-group">
                <label>Bandwidth</label>
                <input type="text" name="bandwidth" placeholder="e.g. 100GB" required>
            </div>
            <div class="form-group">
                <label>Price (BDT)</label>
                <input type="number" name="price" step="0.01" required>
            </div>
            <div class="form-group">
                <label>Server IP</label>
                <input type="text" name="server_ip" required>
            </div>
            <div class="form-group">
                <label>Winbox Port</label>
                <input type="number" name="winbox_port" required>
            </div>
            <div class="form-group">
                <label>API Port</label>
                <input type="number" name="api_port" required>
            </div>
            <div class="form-group">
                <label>Web Port</label>
                <input type="number" name="web_port" required>
            </div>
            <button type="submit" class="btn">Add Package</button>
        </form>
    </div>
    
    <!-- প্যাকেজ লিস্ট -->
    <div class="card">
        <h3>Existing Packages</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Bandwidth</th>
                    <th>Price</th>
                    <th>Server IP</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($packages as $pkg): ?>
                <tr>
                    <td><?= $pkg['id'] ?></td>
                    <td><?= $pkg['name'] ?></td>
                    <td><?= $pkg['category'] ?></td>
                    <td><?= $pkg['bandwidth'] ?></td>
                    <td>৳<?= number_format($pkg['price'], 2) ?></td>
                    <td><?= $pkg['server_ip'] ?></td>
                    <td>
                        <a href="edit_package.php?id=<?= $pkg['id'] ?>" class="btn-sm">Edit</a>
                        <a href="packages.php?delete=<?= $pkg['id'] ?>" class="btn-sm danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>