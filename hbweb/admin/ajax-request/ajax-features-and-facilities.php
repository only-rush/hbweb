<?php
require('../../config.php'); 


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all features
$featuresSql = "SELECT id, name FROM features";
$featuresResult = $conn->query($featuresSql);
$features = [];
if ($featuresResult->num_rows > 0) {
    while ($row = $featuresResult->fetch_assoc()) {
        $features[] = $row;
    }
}

// Fetch all facilities
$facilitiesSql = "SELECT id, name FROM facilities";
$facilitiesResult = $conn->query($facilitiesSql);
$facilities = [];
if ($facilitiesResult->num_rows > 0) {
    while ($row = $facilitiesResult->fetch_assoc()) {
        $facilities[] = $row;
    }
}

echo json_encode(['features' => $features, 'facilities' => $facilities]);

$conn->close();
?>
