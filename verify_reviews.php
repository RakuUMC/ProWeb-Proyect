<?php
// Simple verification for Reviews

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "kiosko_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "1. Connected to DB.\n";

// Insert a Test Review (General)
$user_name = "Review Tester";
$comment = "Testing reviews";
$rating = 5;

$sql = "INSERT INTO reviews (user_name, rating, comment, created_at) VALUES ('$user_name', $rating, '$comment', NOW())";

if ($conn->query($sql) === TRUE) {
    echo "2. General Review created.\n";
} else {
    echo "Error: " . $conn->error;
}

// Insert Test Review (Product 1)
$product_id = 1;
$comment_prod = "Testing product review";
$sql = "INSERT INTO reviews (product_id, user_name, rating, comment, created_at) VALUES ($product_id, '$user_name', $rating, '$comment_prod', NOW())";

if ($conn->query($sql) === TRUE) {
    echo "3. Product Review created.\n";
} else {
    echo "Error: " . $conn->error;
}

// Check retrieval
$sql = "SELECT * FROM reviews WHERE user_name = '$user_name'";
$result = $conn->query($sql);

if ($result->num_rows >= 2) {
    echo "4. Reviews retrieved successfully (" . $result->num_rows . " found).\n";
} else {
    echo "Warning: Expected at least 2 reviews, found " . $result->num_rows . "\n";
}

// Cleanup
$conn->query("DELETE FROM reviews WHERE user_name = '$user_name'");
echo "5. Cleanup done.\n";

$conn->close();
?>
