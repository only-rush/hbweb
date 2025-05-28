<?php
require('../../config.php');

$id = intval($_GET['id']);
$query = "SELECT contacts.name, contacts.message, subject.name AS subject 
          FROM contacts 
          JOIN subject ON contacts.subject_id = subject.id 
          WHERE contacts.id = $id 
          LIMIT 1";

$result = mysqli_query($conn, $query);
$message = mysqli_fetch_assoc($result);

echo json_encode($message);
?>
