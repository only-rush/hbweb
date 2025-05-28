<?php
session_start();
require('../../config.php');

header('Content-Type: application/json');

if (!isset($_SESSION['login_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);

$login_id = $_SESSION['login_id'];
$room_id = $input['room_id'] ?? '';
$payment_method_id = $input['payment_method_id'] ?? '';
$check_in = $input['check_in'] ?? '';
$check_out = $input['check_out'] ?? '';
$price = $input['price'] ?? '';

if (!$room_id || !$payment_method_id || !$check_in || !$check_out || !$price) {
    echo json_encode(['success' => false, 'message' => 'Missing fields.']);
    exit;
}

$query = "INSERT INTO bookings (login_id, room_id, payment_method_id, check_in, check_out, price) 
          VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($query);
$stmt->bind_param("iiissd", $login_id, $room_id, $payment_method_id, $check_in, $check_out, $price);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
