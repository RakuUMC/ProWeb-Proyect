<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "kiosko_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Reviews Table
$sql = "CREATE TABLE IF NOT EXISTS reviews (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) UNSIGNED NULL,
    product_id INT(11) UNSIGNED NULL,
    user_name VARCHAR(100) NOT NULL,
    rating INT(1) NOT NULL,
    comment TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'reviews' created successfully\n";
} else {
    echo "Error creating table reviews: " . $conn->error . "\n";
}

$conn->close();
?>
