<?php
require('../../config.php');

$query = "SELECT contacts.id, contacts.name, contacts.message, subject.name AS subject 
          FROM contacts 
          JOIN subject ON contacts.subject_id = subject.id 
          ORDER BY contacts.id DESC";

$result = mysqli_query($conn, $query);
$messages = [];

while ($row = mysqli_fetch_assoc($result)) {
    $messages[] = $row;
}

echo json_encode($messages);
?>
