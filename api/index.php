<?php
require 'db.php';
session_start();

// Delete handling
if (isset($_GET['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    if ($stmt->execute([$_GET['delete_id']])) {
        $_SESSION['message'] = "User deleted successfully!";
        $_SESSION['msg_type'] = "success";
    } else {
        $_SESSION['message'] = "Failed to delete user.";
        $_SESSION['msg_type'] = "error";
    }
    header("Location: index.php");
    exit;
}

// Fetch users & calculate total count
$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
$users = $stmt->fetchAll();
$total_users = count($users);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-layout">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="brand-icon">U</div>
                <span>AdminPanel</span>
            </div>
            <ul class="sidebar-menu">
                <li><a href="index.php" class="active">📊 Dashboard</a></li>
                <li><a href="create.php">➕ Add User</a></li>
            </ul>
        </aside>

        <!-- Main Workspace -->
        <main class="main-wrapper">
            <header class="top-bar">
                <div class="top-bar-title">
                    <h1>User Management Dashboard</h1>
                </div>
                <div class="top-bar-actions">
                    <input type="text" class="search-input" placeholder="Search records...">
                    <a href="create.php" class="btn btn-primary">+ Add New User</a>
                </div>
            </header>

            <div class="content-body">
                <!-- Summary Metric Cards -->
                <div class="stats-grid">
                    <div class="stat-card primary">
                        <div class="stat-label">Total Users</div>
                        <div class="stat-value"><?= $total_users ?></div>
                    </div>
                    <div class="stat-card success">
                        <div class="stat-label">System Status</div>
                        <div class="stat-value">Active</div>
                    </div>
                    <div class="stat-card warning">
                        <div class="stat-label">Pending Actions</div>
                        <div class="stat-value">0</div>
                    </div>
                    <div class="stat-card error">
                        <div class="stat-label">Database Status</div>
                        <div class="stat-value">Connected</div>
                    </div>
                </div>

                <!-- Session Alert -->
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?= $_SESSION['msg_type'] ?>">
                        <?= $_SESSION['message']; unset($_SESSION['message']); unset($_SESSION['msg_type']); ?>
                    </div>
                <?php endif; ?>

                <!-- Data Table -->
                <div class="table-card">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Joined</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($total_users > 0): ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td class="text-muted">#<?= htmlspecialchars($user['id']) ?></td>
                                        <td class="font-weight-medium"><?= htmlspecialchars($user['name']) ?></td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td class="text-muted"><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                                        <td class="text-right">
                                            <a href="update.php?id=<?= $user['id'] ?>" class="btn-action btn-edit">Edit</a>
                                            <a href="index.php?delete_id=<?= $user['id'] ?>" class="btn-action btn-delete" onclick="return confirm('Delete this record?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="empty-state">No records found in database.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>