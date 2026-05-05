<?php
// login.php
session_start();
include 'DBConn.php';

$message     = "";
$stickyEmail = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stickyEmail = htmlspecialchars($email);

    // ✅ Hash what the user typed — must match what's stored in DB
    $hashedInput = md5($password);

    $sql  = "SELECT * FROM tblUser WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {

        // ✅ Compare hashed input to stored hash
        if ($hashedInput === $user['password']) {

            if ($user['verified'] == 0) {
                $message = "Your account is pending admin verification. Please wait.";
            } else {
                $_SESSION['user_id']  = $user['user_id'];
                $_SESSION['user']     = $user['username'];
                $_SESSION['email']    = $user['email'];
                $_SESSION['role']     = $user['role'];
                $_SESSION['loggedIn'] = true;

                header("Location: dashboard.php");
                exit();
            }

        } else {
            $message = "Incorrect password. Please try again.";
        }

    } else {
        $message = "No account found with that email address.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — ClothingStore</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-card">

        <div class="brand-logo">👗</div>
        <h2>Welcome Back</h2>
        <p class="subtitle">Sign in to your ClothingStore account</p>

        <?php if (!empty($message)): ?>
            <div class="alert alert-error"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">

            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="you@example.com"
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
                    placeholder="Enter your password"
                    required
                    minlength="5"
                >
            </div>

            <button type="submit" class="btn-primary">Login</button>
        </form>

        <div class="auth-links">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
            <p><a href="adminLogin.php">Admin Login →</a></p>
        </div>

    </div>
</div>

</body>
</html>
