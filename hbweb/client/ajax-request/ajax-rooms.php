<?php
require('../../config.php'); 

$sql = "SELECT r.id, r.name, r.photo_path, r.description, r.price_per_night, 
               GROUP_CONCAT(DISTINCT f.name) AS features, 
               GROUP_CONCAT(DISTINCT fc.name) AS facilities
        FROM rooms r
        LEFT JOIN room_features rf ON r.id = rf.room_id
        LEFT JOIN features f ON rf.feature_id = f.id
        LEFT JOIN room_facilities rfc ON r.id = rfc.room_id
        LEFT JOIN facilities fc ON rfc.facilities_id = fc.id
        GROUP BY r.id";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode(['status' => 'error', 'message' => 'Query failed: ' . $conn->error]);
    $conn->close();
    exit;
}

$rooms = [];
while ($row = $result->fetch_assoc()) {
    $rooms[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'photo_path' => $row['photo_path'],
        'description' => $row['description'],
        'price_per_night' => $row['price_per_night'],
        'features' => $row['features'] ? explode(',', $row['features']) : [],
        'facilities' => $row['facilities'] ? explode(',', $row['facilities']) : [],
    ];
}

$conn->close();

echo json_encode(['status' => 'success', 'rooms' => $rooms]);

?>
