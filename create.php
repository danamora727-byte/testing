<?php
require 'db.php';
session_start();

$errors = [];
$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    if (empty($name)) { $errors[] = "Name is required."; }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = "A valid email address is required."; }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
        if ($stmt->execute([$name, $email])) {
            $_SESSION['message'] = "User added successfully!";
            $_SESSION['msg_type'] = "success";
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Database error. Could not save user.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="brand-icon">U</div>
                <span>AdminPanel</span>
            </div>
            <ul class="sidebar-menu">
                <li><a href="index.php">📊 Dashboard</a></li>
                <li><a href="create.php" class="active">➕ Add User</a></li>
            </ul>
        </aside>

        <main class="main-wrapper">
            <header class="top-bar">
                <div class="top-bar-title"><h1>Add New User</h1></div>
            </header>

            <div class="content-body">
                <div class="form-card">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-error">
                            <?php foreach ($errors as $error): ?>
                                <p><?= $error ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="create.php" novalidate>
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" placeholder="e.g. Jane Doe" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="e.g. jane@example.com" required>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Save User</button>
                            <a href="index.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>