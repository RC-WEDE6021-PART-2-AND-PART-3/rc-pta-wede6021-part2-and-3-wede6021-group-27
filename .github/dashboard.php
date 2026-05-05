<?php
// dashboard.php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: login.php");
    exit();
}

$userName = htmlspecialchars($_SESSION['user']);
$userRole = htmlspecialchars($_SESSION['role']);
$userEmail = htmlspecialchars($_SESSION['email']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — ClothingStore</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Top Navigation Bar -->
<nav class="navbar">
    <div class="nav-brand">👗 ClothingStore</div>
    <div class="nav-right">
        <span class="nav-user">👤 <?php echo $userName; ?></span>
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>
</nav>

<div class="dashboard-container">

    <!-- Rubric requirement: display "User [Name] is logged in" -->
    <div class="logged-in-banner">
        User <?php echo $userName; ?> is logged in.
    </div>

    <div class="dashboard-header">
        <h1>Welcome to your Dashboard</h1>
        <p>You are signed in as: <strong><?php echo $userEmail; ?></strong></p>
        <p>Account role: <span class="role-badge role-<?php echo $userRole; ?>"><?php echo ucfirst($userRole); ?></span></p>
    </div>

    <!-- Admin-only panel link -->
    <?php if ($userRole === 'admin'): ?>
    <div class="admin-panel-card">
        <h3>⚙️ Admin Panel</h3>
        <p>Manage customer accounts and verify new registrations.</p>
        <a href="adminDashboard.php" class="btn-primary">Go to Admin Panel</a>
    </div>
    <?php endif; ?>

    <!-- User dashboard cards -->
    <div class="dashboard-cards">
        <div class="card">
            <div class="card-icon">🛍️</div>
            <h3>Browse Clothes</h3>
            <p>View our latest clothing collection.</p>
        </div>

        <div class="card">
            <div class="card-icon">📦</div>
            <h3>My Orders</h3>
            <p>Track and manage your orders.</p>
        </div>

        <div class="card">
            <div class="card-icon">👤</div>
            <h3>My Profile</h3>
            <p>Update your account details.</p>
        </div>
    </div>

</div>

</body>
</html>
