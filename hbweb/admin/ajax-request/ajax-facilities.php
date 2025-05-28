<?php
require('../../config.php'); 

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'DB connection failed']);
    exit;
}

// Add a new facility
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['name'])) {
        $name = trim($_POST['name']);

        // Check if the facility already exists
        $check = $conn->prepare("SELECT id FROM facilities WHERE name = ?");
        $check->bind_param("s", $name);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Facility already exists']);
        } else {
            // Insert the new facility
            $stmt = $conn->prepare("INSERT INTO facilities (name) VALUES (?)");
            $stmt->bind_param("s", $name);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Facility added successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to add facility']);
            }
            $stmt->close();
        }

        $check->close();
    } elseif (isset($_POST['id'])) {
        // Delete the facility by id
        $facilityId = (int) $_POST['id'];
        $deleteStmt = $conn->prepare("DELETE FROM facilities WHERE id = ?");
        $deleteStmt->bind_param("i", $facilityId);

        if ($deleteStmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Facility deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete facility']);
        }
        $deleteStmt->close();
    }
}

// Fetch all facilities
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $conn->query("SELECT id, name FROM facilities");

    if ($result->num_rows > 0) {
        $facilities = [];
        while ($row = $result->fetch_assoc()) {
            $facilities[] = $row;
        }
        echo json_encode($facilities);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No facilities found']);
    }
}

$conn->close();
?>
