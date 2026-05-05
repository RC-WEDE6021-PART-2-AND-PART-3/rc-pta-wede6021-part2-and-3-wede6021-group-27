<?php
// loadClothingStore.php
// Drops and recreates ALL ClothingStore tables using MySQLi
include 'DBConn.php';

echo "<h2>Loading ClothingStore Database...</h2>";

// ── Helper: run a query or die ────────────────────────────────────────────────
function runQuery($conn, $sql, $label) {
    if ($conn->query($sql) === TRUE) {
        echo "✔ $label<br>";
    } else {
        die("✘ Error ($label): " . $conn->error);
    }
}

// ── Drop all tables (order matters due to foreign keys) ───────────────────────
runQuery($conn, "SET FOREIGN_KEY_CHECKS = 0", "Foreign key checks disabled");
runQuery($conn, "DROP TABLE IF EXISTS tblAorder",   "Dropped tblAorder");
runQuery($conn, "DROP TABLE IF EXISTS tblClothes",  "Dropped tblClothes");
runQuery($conn, "DROP TABLE IF EXISTS tblAdmin",    "Dropped tblAdmin");
runQuery($conn, "DROP TABLE IF EXISTS tblUser",     "Dropped tblUser");
runQuery($conn, "SET FOREIGN_KEY_CHECKS = 1", "Foreign key checks re-enabled");

echo "<br>";

// ── Create tblUser ────────────────────────────────────────────────────────────
runQuery($conn, "
    CREATE TABLE IF NOT EXISTS tblUser (
        user_id   INT          AUTO_INCREMENT PRIMARY KEY,
        username  VARCHAR(100) NOT NULL,
        email     VARCHAR(100) NOT NULL UNIQUE,
        password  VARCHAR(255) NOT NULL,
        role      VARCHAR(20)  NOT NULL DEFAULT 'user',
        verified  TINYINT(1)   NOT NULL DEFAULT 0,
        created_at DATETIME    DEFAULT CURRENT_TIMESTAMP
    )
", "Created tblUser");

// ── Create tblAdmin ───────────────────────────────────────────────────────────
runQuery($conn, "
    CREATE TABLE IF NOT EXISTS tblAdmin (
        admin_id  INT          AUTO_INCREMENT PRIMARY KEY,
        username  VARCHAR(100) NOT NULL,
        email     VARCHAR(100) NOT NULL UNIQUE,
        password  VARCHAR(255) NOT NULL,
        created_at DATETIME   DEFAULT CURRENT_TIMESTAMP
    )
", "Created tblAdmin");

// ── Create tblClothes ─────────────────────────────────────────────────────────
runQuery($conn, "
    CREATE TABLE IF NOT EXISTS tblClothes (
        clothes_id    INT           AUTO_INCREMENT PRIMARY KEY,
        name          VARCHAR(150)  NOT NULL,
        description   TEXT,
        category      VARCHAR(100),
        price         DECIMAL(10,2) NOT NULL,
        stock         INT           NOT NULL DEFAULT 0,
        image_url     VARCHAR(255),
        created_at    DATETIME      DEFAULT CURRENT_TIMESTAMP
    )
", "Created tblClothes");

// ── Create tblAorder ─────────────────────────────────────────────────────────
runQuery($conn, "
    CREATE TABLE IF NOT EXISTS tblAorder (
        order_id    INT           AUTO_INCREMENT PRIMARY KEY,
        user_id     INT           NOT NULL,
        clothes_id  INT           NOT NULL,
        quantity    INT           NOT NULL DEFAULT 1,
        total_price DECIMAL(10,2) NOT NULL,
        order_date  DATETIME      DEFAULT CURRENT_TIMESTAMP,
        status      VARCHAR(50)   NOT NULL DEFAULT 'pending',
        FOREIGN KEY (user_id)    REFERENCES tblUser(user_id)    ON DELETE CASCADE,
        FOREIGN KEY (clothes_id) REFERENCES tblClothes(clothes_id) ON DELETE CASCADE
    )
", "Created tblAorder");

echo "<br><strong>All tables created successfully!</strong>";
echo "<br><a href='createTable.php'>Load user data from userData.txt</a>";
?>
