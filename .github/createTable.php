<?php
// createTable.php
include 'DBConn.php';

// Drop table if it already exists
$conn->query("DROP TABLE IF EXISTS tblUser");
echo "Old tblUser dropped.<br>";

// Recreate tblUser
$sql = "CREATE TABLE tblUser (
    user_id   INT          AUTO_INCREMENT PRIMARY KEY,
    username  VARCHAR(100) NOT NULL,
    email     VARCHAR(100) NOT NULL UNIQUE,
    password  VARCHAR(255) NOT NULL,
    role      VARCHAR(20)  NOT NULL DEFAULT 'user',
    verified  TINYINT(1)   NOT NULL DEFAULT 0
)";

if ($conn->query($sql) === TRUE) {
    echo "tblUser created successfully.<br>";
} else {
    die("Error creating table: " . $conn->error);
}

// Load data from userData.txt
$filePath = "userData.txt";

if (!file_exists($filePath)) {
    die("Error: userData.txt not found.");
}

$file = fopen($filePath, "r");
$insertCount = 0;

while (($line = fgets($file)) !== false) {
    $line = trim($line);
    if (empty($line)) continue;

    // Trim each field to remove accidental spaces
    $data = array_map('trim', explode(",", $line));

    if (count($data) < 3) continue;

    $name     = $data[0];
    $email    = $data[1];
    // ✅ Hash the plain text password HERE before storing
    $password = md5($data[2]);

    if (strtolower($email) === "admin@gmail.com") {
        $role     = "admin";
        $verified = 1;
    } else {
        $role     = "user";
        $verified = 0;
    }

    $stmt = $conn->prepare(
        "INSERT INTO tblUser (username, email, password, role, verified)
         VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("ssssi", $name, $email, $password, $role, $verified);

    if ($stmt->execute()) {
        $insertCount++;
    } else {
        echo "Error inserting $email: " . $stmt->error . "<br>";
    }

    $stmt->close();
}

fclose($file);
echo "<br><strong>Done! $insertCount records inserted.</strong>";
echo "<br>All passwords hashed with MD5.";
echo "<br><a href='login.php'>Go to Login</a>";
?>
