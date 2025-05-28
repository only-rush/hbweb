<?php
require('../config.php');

// Sanitize & validate inputs here
$name = $_POST['name'] ?? '';
$review = $_POST['message'] ?? '';
$rating = $_POST['rating'] ?? 0;

// Basic validation
if(empty($review) || empty($rating)) {
    echo json_encode(['status' => 'error', 'message' => 'Review and rating are required.']);
    exit;
}

$name = $conn->real_escape_string($name);
$review = $conn->real_escape_string($review);
$rating = (int)$rating;

// Example: if you have login sessions and login_id
$login_id = $_SESSION['login_id'] ?? 0;

$sql = "INSERT INTO review (login_id, name, review, rating) VALUES ('$login_id', '$name', '$review', '$rating')";

if ($conn->query($sql)) {
    echo json_encode(['status' => 'success', 'message' => 'Review submitted successfully!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to submit review.']);
}
?>
