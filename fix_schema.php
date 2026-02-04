<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "kiosko_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Fixing schema...\n";

// Add updated_at to users if missing
$sql = "ALTER TABLE users ADD COLUMN IF NOT EXISTS updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at";
if ($conn->query($sql) === TRUE) {
    echo "Column 'updated_at' checked/added to 'users' table.\n";
} else {
    // If IF NOT EXISTS is not supported by their MySQL version (older versions)
    $sql = "ALTER TABLE users ADD COLUMN updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at";
    if ($conn->query($sql) === TRUE) {
        echo "Column 'updated_at' added to 'users' table.\n";
    } else {
        echo "Note: 'users.updated_at' might already exist or error: " . $conn->error . "\n";
    }
}

// Add updated_at to orders if missing
$sql = "ALTER TABLE orders ADD COLUMN IF NOT EXISTS updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at";
if ($conn->query($sql) === TRUE) {
    echo "Column 'updated_at' checked/added to 'orders' table.\n";
} else {
    $sql = "ALTER TABLE orders ADD COLUMN updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at";
    if ($conn->query($sql) === TRUE) {
        echo "Column 'updated_at' added to 'orders' table.\n";
    } else {
        echo "Note: 'orders.updated_at' might already exist or error: " . $conn->error . "\n";
    }
}

$conn->close();
echo "Done.\n";
?>
