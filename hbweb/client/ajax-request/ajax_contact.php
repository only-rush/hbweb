<?php
require('../../config.php'); 

session_start();

// Handle POST request to submit contact form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['login_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
        exit;
    }

    $login_id = $_SESSION['login_id'];
    $name = $_POST['name'] ?? '';
    $subject_id = $_POST['subject_id'] ?? '';  // Ensure 'subject_id' matches the form input name
    $message = $_POST['message'] ?? '';

    // Basic validation
    if (empty($name) || empty($subject_id) || empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        exit;
    }

    // Insert the contact form data into the database
    $stmt = $conn->prepare("INSERT INTO contacts (login_id, name, subject_id, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isis", $login_id, $name, $subject_id, $message);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Message sent successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to send message']);
    }

    $stmt->close();
}
?>
