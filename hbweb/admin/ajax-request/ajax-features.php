<?php
require('../../config.php'); 
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'DB connection failed']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add new feature
    if (isset($_POST['name'])) {
        $name = trim($_POST['name']);

        // Prevent duplicate names
        $check = $conn->prepare("SELECT id FROM features WHERE name = ?");
        $check->bind_param("s", $name);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Feature already exists']);
        } else {
            $stmt = $conn->prepare("INSERT INTO features (name) VALUES (?)");
            $stmt->bind_param("s", $name);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Feature added']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to add feature']);
            }
            $stmt->close();
        }

        $check->close();
    }

    // Delete a feature
    elseif (isset($_POST['id'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM features WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Feature deleted']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete feature']);
        }

        $stmt->close();
    }
}

// Fetch all features
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $conn->query("SELECT id, name FROM features");
    $features = [];

    while ($row = $result->fetch_assoc()) {
        $features[] = $row;
    }

    echo json_encode($features);
}

$conn->close();
?>