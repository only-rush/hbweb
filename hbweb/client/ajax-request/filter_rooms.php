<?php
require('../../config.php');

$facilities = $_POST['facilities'] ?? [];
$adults = $_POST['adults'] ?? 0;
$children = $_POST['children'] ?? 0;

// Example query – adjust based on your table structure
$query = "SELECT DISTINCT r.* FROM rooms r
LEFT JOIN room_facilities rf ON r.id = rf.room_id
LEFT JOIN facilities f ON f.id = rf.facilities_id
WHERE 1 ";

if (!empty($facilities)) {
  $facilities = array_map(function($f) use ($conn) {
    return "'" . mysqli_real_escape_string($conn, $f) . "'";
  }, $facilities);
  $fac_str = implode(',', $facilities);
  $query .= " AND f.name IN ($fac_str) ";
}

// You can add logic for guests later if needed

$res = mysqli_query($conn, $query);

if (mysqli_num_rows($res) > 0) {
  while ($room = mysqli_fetch_assoc($res)) {
    // Output room card HTML just like in the main file
    echo "<div class='card mb-3 p-3'><h5>{$room['name']}</h5><p>₱{$room['price_per_night']} per night</p></div>";
  }
} else {
  echo "<p>No rooms match your criteria.</p>";
}
?>
