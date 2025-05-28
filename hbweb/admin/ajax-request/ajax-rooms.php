<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli("localhost", "root", "", "hotelbooking");

    if ($conn->connect_error) {
        echo json_encode(['status' => 'error', 'message' => 'DB connection failed: '.$conn->connect_error]);
        exit;
    }

    // Get form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $status_id = (int)$_POST['status_id'];
    $price = (float)$_POST['price'];

    // File upload
    if (isset($_FILES['photo_path']) && $_FILES['photo_path']['error'] === UPLOAD_ERR_OK) {
        $photoTmpPath = $_FILES['photo_path']['tmp_name'];
        $photoName = basename($_FILES['photo_path']['name']);
        $uploadDir = "../uploads/";

        // Create uploads folder if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $photoPath = $uploadDir . $photoName;


        if (!move_uploaded_file($photoTmpPath, $photoPath)) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to upload image']);
            exit;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Image file not uploaded']);
        exit;
    }

    // Prepare insert statement with price_per_night
    $stmt = $conn->prepare("INSERT INTO rooms (name, photo_path, description, status_id, price_per_night) VALUES (?, ?, ?, ?, ?)");

    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("sssdi", $name, $photoPath, $description, $status_id, $price);

    if (!$stmt->execute()) {
        echo json_encode(['status' => 'error', 'message' => 'Execute failed: ' . $stmt->error]);
        exit;
    }

    $room_id = $stmt->insert_id;

    // Insert features
    if (!empty($_POST['features'])) {
        $features = $_POST['features'];
        $featureStmt = $conn->prepare("INSERT INTO room_features (room_id, feature_id) VALUES (?, ?)");
        foreach ($features as $feature_id) {
            $featureStmt->bind_param("ii", $room_id, $feature_id);
            $featureStmt->execute();
        }
        $featureStmt->close();
    }

    // Insert facilities
    if (!empty($_POST['facilities'])) {
        $facilities = $_POST['facilities'];
        $facilityStmt = $conn->prepare("INSERT INTO room_facilities (room_id, facilities_id) VALUES (?, ?)");
        foreach ($facilities as $facility_id) {
            $facilityStmt->bind_param("ii", $room_id, $facility_id);
            $facilityStmt->execute();
        }
        $facilityStmt->close();
    }

    echo json_encode(['status' => 'success', 'message' => 'Room added successfully']);

    $stmt->close();
    $conn->close();
}
