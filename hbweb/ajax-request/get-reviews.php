<?php
require('../config.php');

$query = "SELECT name, review, rating FROM review ORDER BY id DESC LIMIT 10";
$result = $conn->query($query);

$reviews = [];

while ($row = $result->fetch_assoc()) {
    $reviews[] = $row;
}

header('Content-Type: application/json');
echo json_encode($reviews);
?>
