<?php
session_start();

// Block access if not logged in or not admin
if (!isset($_SESSION['login_id']) || $_SESSION['usertype_id'] != 1) {
    echo "&lt;script&gt;alert('Access denied. Admins only.'); window.location.href='../index.php';&lt;/script&gt;";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin-style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome Admin!</h1>
        <a href="rooms.php">Manage Rooms</a>
        <a href="features.php">Manage Room Features</a>
        <a href="facilities.php">Manage Room Facilities</a>
        <a href="messages.php">Messages</a>
        <a href="booked.php">Bookings</a>
        <a href="../logout.php">Logout</a>
    </div>
</body>
</html>

