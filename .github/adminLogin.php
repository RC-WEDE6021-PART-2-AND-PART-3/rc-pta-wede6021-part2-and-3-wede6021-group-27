<?php
// adminLogin.php
session_start();
include 'DBConn.php';

if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: adminDashboard.php");
    exit();
}

$message     = "";
$stickyEmail = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stickyEmail = htmlspecialchars($email);

    // ✅ Hash input before comparing
    $hashedInput = md5($password);

    $sql  = "SELECT * FROM tblUser WHERE email = ? AND role = 'admin' LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($admin = $result->fetch_assoc()) {

        if ($hashedInput === $admin['password']) {
            $_SESSION['user_id']  = $admin['user_id'];
            $_SESSION['user']     = $admin['username'];
            $_SESSION['email']    = $admin['email'];
            $_SESSION['role']     = 'admin';
            $_SESSION['loggedIn'] = true;

            header("Location: adminDashboard.php");
            exit();
        } else {
            $message = "Incorrect password.";
        }

    } else {
        $message = "No admin account found with that email.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — ClothingStore</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-card admin-card">

        <div class="brand-logo">⚙️</div>
        <h2>Admin Login</h2>
        <p class="subtitle">ClothingStore Administration</p>

        <?php if (!empty($message)): ?>
            <div class="alert alert-error"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" action="adminLogin.php">

            <div class="form-group">
                <label for="email">Admin Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="admin@gmail.com"
                    value="<?php echo $stickyEmail; ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter admin password"
                    required
                    minlength="5"
                >
            </div>

            <button type="submit" class="btn-primary">Login as Admin</button>
        </form>

        <div class="auth-links">
            <p><a href="login.php">← Back to User Login</a></p>
        </div>

    </div>
</div>

</body>
</html>
