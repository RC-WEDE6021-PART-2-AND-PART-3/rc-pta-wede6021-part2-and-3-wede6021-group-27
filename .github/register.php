<?php
// register.php
include 'DBConn.php';

$message     = "";
$msgType     = "";
$stickyName  = "";
$stickyEmail = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name     = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm_password']);

    $stickyName  = htmlspecialchars($name);
    $stickyEmail = htmlspecialchars($email);

    if (empty($name) || empty($email) || empty($password) || empty($confirm)) {
        $message = "All fields are required.";
        $msgType = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
        $msgType = "error";

    } elseif (strlen($password) < 5) {
        $message = "Password must be at least 5 characters.";
        $msgType = "error";

    } elseif ($password !== $confirm) {
        $message = "Passwords do not match.";
        $msgType = "error";

    } else {
        // ✅ Hash the password with MD5 before storing
        $hashedPassword = md5($password);

        $sql  = "INSERT INTO tblUser (username, email, password, role, verified)
                 VALUES (?, ?, ?, 'user', 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $hashedPassword);

        if ($stmt->execute()) {
            $message     = "Registration successful! Your account is pending admin approval.";
            $msgType     = "success";
            $stickyName  = "";
            $stickyEmail = "";
        } else {
            $message = "That email address is already registered.";
            $msgType = "error";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — ClothingStore</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-card">

        <div class="brand-logo">👗</div>
        <h2>Create Account</h2>
        <p class="subtitle">Join ClothingStore today</p>

        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $msgType; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php">

            <div class="form-group">
                <label for="username">Full Name</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="John Doe"
                    value="<?php echo $stickyName; ?>"
                    required
                    minlength="2"
                >
            </div>

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
                    placeholder="Minimum 5 characters"
                    required
                    minlength="5"
                >
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="Re-enter your password"
                    required
                    minlength="5"
                >
            </div>

            <button type="submit" class="btn-primary">Register</button>
        </form>

        <div class="auth-links">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>

    </div>
</div>

</body>
</html>
